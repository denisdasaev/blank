<?php /**
 *  @var $this DoController
 *  @var $err_mes string
 */

//  $scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot.protected.views.'.$this->id.'.'.$this->action->id.'.assets'), false, -1, YII_DEBUG);
//  Yii::app()->clientScript->registerScriptFile($scripts.'/script.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerCssFile($scripts.'/style.css');
  $this->pageTitle = Yii::app()->name.' — Регистрация';
?>
<h1>Ошибка регистрации</h1>
<p>
  При попытке регистрации возникла следующая ошибка:
</p>
<p>
  <em><?php echo $err_mes; ?></em>
</p>
<p>
  Попробуйте <a href="#" class="register">повторить регистрацию</a> ещё раз!
</p>