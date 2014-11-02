<?php
  /**
   * @var $this DoController
   * @var $code int
   * @var $message string
   */

  Yii::app()->clientScript->registerCssFile('//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css');
  Yii::app()->clientScript->registerCoreScript('jquery');
  $scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.views.'.$this->id.'.'.$this->action->id.'.assets'), false, -1, YII_DEBUG);
  Yii::app()->clientScript->registerCssFile($scripts.'/style.css');

  $this->pageTitle='Ошибка '.$code.' | '.Yii::app()->name;
?>
<article>
  <div id="display">
    <hgroup class="text-center">
      <h1><?php echo $code; ?></h1>
      <h2>Произошла ошибка</h2>
      <h4><?php echo CHtml::encode($message); ?></h4>
    </hgroup>
    <p class="text-center">
      <br>
      <a href="#" onclick="history.go(-1);" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-arrow-left"></span> Вернуться назад</a>
    </p>
  </div>
  <div id="display2">&nbsp;</div>
  <div id="display3">&nbsp;</div>
</article>