<?php
class CoreAjaxDo extends CoreAjax
{
  protected function anonymousMethods()
  {
    return array(
      'registerForm',
      'validRegLogin',
      'validRegPassword',
      'validRegPassword2',
      'validRegEmail',
      'loginForm',
    );
  }

  public function registerForm($data = array())
  {
    $params = array();
    return Yii::app()->controller->renderPartial('application.views.do.reg.form-register', $params, true);
  }

  public function validRegLogin($data = array())
  {
    if (isset($data['s_login']) && !empty($data['s_login']) && mb_strlen($data['s_login'], 'utf-8') > 2
        && !CoreClient::model()->exists('login=:login', array(':login'=>$data['s_login'])))
      return '1';

    return '0';
  }

  public function validRegPassword($data = array())
  {
    if (isset($data['s_password']) && !empty($data['s_password']) && mb_strlen($data['s_password'], 'utf-8') > 4)
      return '1';

    return '0';
  }

  public function validRegPassword2($data = array())
  {
    if (isset($data['s_password'], $data['s_password2']) && !empty($data['s_password'])
        && mb_strlen($data['s_password'], 'utf-8') > 4 && $data['s_password'] == $data['s_password2'])
      return '1';

    return '0';
  }

  public function validRegEmail($data = array())
  {
    if (isset($data['s_email']) && !empty($data['s_email']) && mb_strlen($data['s_email'], 'utf-8') > 3
        && mb_strpos($data['s_email'], '@', null, 'utf-8') && mb_strpos($data['s_email'], '.', null, 'utf-8')
        && !mb_strpos($data['s_email'], ' ', null, 'utf-8')
        && !CoreClient::model()->exists('email=:email', array(':email'=>$data['s_email'])))
      return 1;

    return 0;
  }

  public function loginForm($data = array())
  {
    $params = array();
    $params['login'] = (isset($_COOKIE['lg'])?$_COOKIE['lg']:'');

    return Yii::app()->controller->renderPartial('application.views.do.reg.form-login', $params, true);
  }
}