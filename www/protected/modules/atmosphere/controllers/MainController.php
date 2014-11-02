<?php

class MainController extends CoreController
{
  /**
   * Главная страница.
   */
  public function actionIndex()
	{
    if (Yii::app()->user->isGuest || !Yii::app()->user->admin)
      $this->redirect($this->createUrl('main/logout'));

		$this->render('index', array(
      'groups'=>CoreUnitGroup::model()->with('units')->findAll(array('condition'=>'units.name<>""', 'order'=>'t.sort, t.name, units.sort, units.name')),
      'coreUnits'=>CoreUnit::model()->findAll(array('condition'=>'group_id IS NULL', 'order'=>'sort, name')),
    ));
	}

  /**
   * Вход пользователя.
   */
  public function actionLogin()
  {
    $login = (isset($_COOKIE['lg'])?$_COOKIE['lg']:'');

    if (isset($_POST['lg'], $_POST['pw']))
    {
      $login = $_POST['lg'];
      $identity = new CoreUserIdentity($_POST['lg'], $_POST['pw']);
      if ($identity->authenticate(true) && $identity->errorCode == CoreUserIdentity::ERROR_NONE)
      {
        if (!$rememberMeDays = Yii::app()->conf->get('SYSTEM/REMEMBER_ME_DAYS'))
          $rememberMeDays = 7;
        $duration = (isset($_POST['mm'])?(3600*24*$rememberMeDays):0);
        Yii::app()->user->login($identity, $duration);
        setcookie('lg', Yii::app()->user->login, (time()+3600*24*90), '/'); // 90 дней
        $this->redirect($this->createUrl('main/index'));
      }
      else
        $this->errMsg = 'Введены неверные данные!'.(YII_DEBUG?' (Код ошибки: '.$identity->errorCode.')':'');
    }

    $this->render('login', array('login'=>$login));
  }

  /**
   * Выход пользователя.
   */
  public function actionLogout()
  {
    Yii::app()->user->logout();
    Yii::app()->session->clear();
    Yii::app()->session->destroy();
    $this->redirect($this->createUrl('main/index'));
  }

  /**
   * Фильтры.
   *
   * @return array
   */
  public function filters()
  {
    if (!defined('YII_DEBUG') || !YII_DEBUG)
      return array(
        array(
          'ext.compressPage + index, login'
        ),
      );

    return array();
  }
}