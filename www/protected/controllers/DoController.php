<?php
class DoController extends CoreController
{
	public function actionIndex()
	{
		$this->render('index');
	}

  public function actionError()
  {
    if ($error = Yii::app()->errorHandler->error)
    {
      if (Yii::app()->request->isAjaxRequest)
        echo $error['message'];
      else
      {
        $this->layout = '//layouts/main';
        $this->render('error', $error);
      }
    }
  }

  public function actionAjax()
  {
    if (Yii::app()->request->isAjaxRequest && isset($_POST['request']) && is_array($_POST['request']))
    {
      $debugMode = (defined('YII_DEBUG') && YII_DEBUG);

      if (!$result = CoreAjax::run($_POST['request']))
        echo ($debugMode?'Wrong request: '.json_encode($_POST['request']):'');
      elseif ($result->errorCode != 0)
        echo ($debugMode?$result->errorMessage:'');
    }

    Yii::app()->end();
  }

  public function actionRegister()
  {
    $err_mes = '';

    if (!isset($_POST['edit']['login']) || empty($_POST['edit']['login']))
      $err_mes = 'Не задан логин';
    elseif (!isset($_POST['edit']['password']) || empty($_POST['edit']['password']))
      $err_mes = 'Не задан пароль';
    elseif (!isset($_POST['edit_pw']) || empty($_POST['edit_pw']))
      $err_mes = 'Не задан повторно пароль';
    elseif (!isset($_POST['edit']['email']) || empty($_POST['edit']['email']))
      $err_mes = 'Не задан E-Mail';
    elseif (mb_strlen($_POST['edit']['login'], 'UTF-8') < 3)
      $err_mes = 'Логин должен содержать не менее 3 символов';
    elseif (mb_strlen($_POST['edit']['password'], 'UTF-8') < 5)
      $err_mes = 'Пароль должен содержать не менее 5 символов';
    elseif ($_POST['edit']['password'] !== $_POST['edit_pw'])
      $err_mes = 'Повторный пароль не совпадает с исходным паролем';
    elseif (mb_strpos($_POST['edit']['email'], '@', null, 'UTF-8') === false
        || mb_strpos($_POST['edit']['email'], '.', null, 'UTF-8') === false)
      $err_mes = 'Не корректный E-Mail';
    elseif (CoreClient::model()->exists('login=:login', array(':login'=>$_POST['edit']['login'])))
      $err_mes = 'Логин &laquo;'.$_POST['edit']['login'].'&raquo; занят';
    elseif (CoreClient::model()->exists('email=:email', array(':email'=>$_POST['edit']['email'])))
      $err_mes = 'Электронный адрес &laquo;'.$_POST['regEmail'].'&raquo; уже зарегистрирован';

    $client = new CoreClient();
    $client->login = $_POST['edit']['login'];
    $client->password = md5($_POST['edit']['password']);
    $client->email = $_POST['edit']['email'];
    $client->ts_reg = date('Y-m-d H:i:s');
    if (!$client->save())
      $err_mes = 'На сервере произошел сбой, попробуйте повторить регистрацию позже';
    else
    {
      $vericode = new CoreClientVericode();
      $vericode->client_id = $client->id;
      $vericode->code = md5($client->id.$client->ts_reg.$client->password);
      if (!$vericode->save())
        $err_mes = 'На сервере произошел сбой, попробуйте повторить регистрацию позже';
    }

    if (empty($err_mes))
    {
      $bufferedMail = CoreMailer::in($client->login, $client->email)->useTemplate('register confirm', array(
        'reg_link'=>$this->createAbsoluteUrl('do/verify', array('code'=>$vericode->code)),
//      ))->send(true);
      ))->send();

      if (!$bufferedMail)
        $err_mes = 'Не удалось сформировать письмо с подтверждением регистрации';
      else
      {
        $this->render('reg/success');
        Yii::app()->end();
      }
    }

    $this->render('reg/fail', array('err_mes'=>$err_mes));
  }

  public function actionVerify()
  {
    if (!isset($_GET['code']) || !($client = CoreClient::regVerify($_GET['code'])))
      $this->render('reg/verifyFail');
    else
    {
      CoreMailer::in($client->login, $client->email)->useTemplate('register complete', array(
        'login'=>$client->login,
        'email'=>$client->email,
      ))->send();
      $this->render('reg/verify');
    }
  }

  public function actionLogin()
  {
    $err_mes = '';

    if (isset($_POST['fm_login'], $_POST['fm_password']) && !empty($_POST['fm_login']) && !empty($_POST['fm_password']))
    {
      $identity = new CoreUserIdentity($_POST['fm_login'], $_POST['fm_password']);
      if ($identity->authenticate(false) && $identity->errorCode == CoreUserIdentity::ERROR_NONE)
      {
        if (!$rememberMeDays = Yii::app()->conf->get('SYSTEM/REMEMBER_ME_DAYS'))
          $rememberMeDays = 7;
        $duration = (isset($_POST['fm_remember'])?(3600*24*$rememberMeDays):0);
        Yii::app()->user->login($identity, $duration);
        setcookie('lg', Yii::app()->user->login, (time()+3600*24*90), '/'); // 90 дней
        $this->redirect($this->createUrl('do/index'));
      }
      else $err_mes = 'Неверно указан логин или пароль.';
    }
    else $err_mes = 'Не задан логин или пароль.';

    $this->render('reg/loginFail', array('err_mes'=>$err_mes));
  }

  public function actionLogout()
  {
    Yii::app()->user->logout();
    Yii::app()->session->clear();
    Yii::app()->session->destroy();
    $this->redirect($this->createUrl('do/index'));
  }

  public function filters()
  {
    if (!defined('YII_DEBUG') || !YII_DEBUG)
      return array(
        array(
          'ext.compressPage + index, error, ajax, register, verify, login'
        ),
      );

    return array();
  }
}