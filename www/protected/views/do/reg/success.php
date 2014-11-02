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
  <h3>Шаг 1 из 2</h3>
</hgroup>
<p>
  На Вашу электронную почту направлено письмо, в котором находится ссылка для подтверждения регистрации.
  Активируйте свой аккаунт, пройдя по указанной ссылке!
</p>