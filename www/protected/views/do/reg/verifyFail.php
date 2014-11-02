<?php /**
 *  @var $this DoController
 */

//  $scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot.protected.views.'.$this->id.'.'.$this->action->id.'.assets'), false, -1, YII_DEBUG);
//  Yii::app()->clientScript->registerScriptFile($scripts.'/script.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerCssFile($scripts.'/style.css');
  $this->pageTitle = Yii::app()->name.' — Завершение регистрации';
?>
<hgroup>
  <h1>Завершение регистрации</h1>
  <h3>Шаг 2 из 2</h3>
</hgroup>
<p class="text-error">
  Активация вашего аккаунта невозможна!
</p>
<p>
  Может быть ваш аккаунт уже был активирован, попробуйте просто <a href="#" class="login">войти в систему</a>.
</p>
<p>
  Если войти в систему не удалось, значит ваш аккаунт небыл вовремя активирован, из-за чего был удалён. Вы можете еще раз <a href="#" class="register">зарегистрироваться</a> в системе под своим логином.
</p>