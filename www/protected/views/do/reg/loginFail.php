<?php /**
 *  @var $this DoController
 *  @var $err_mes string
 */

//  $scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot.protected.views.'.$this->id.'.'.$this->action->id.'.assets'), false, -1, YII_DEBUG);
//  Yii::app()->clientScript->registerScriptFile($scripts.'/script.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerCssFile($scripts.'/style.css');
  $this->pageTitle = Yii::app()->name.' — Вход на сайт';
?>
<h1>Вход запрещён</h1>
<p>
  При попытке входа на сайт возникла следующая ошибка:
</p>
<p>
  <em><?php echo $err_mes; ?></em>
</p>
<p>
  Попробуйте <a href="#" class="login">войти</a> ещё раз!
</p>