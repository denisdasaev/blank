<?php
  /**
   * @var $this ClientController
   * @var $model CoreClient
   * @var $rowOffset integer
   */

//  $scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias($this->module->id.'.views.'.$this->id.'.'.$this->action->id.'.assets'), false, -1, YII_DEBUG);
//  Yii::app()->clientScript->registerScriptFile($scripts.'/script.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerCssFile($scripts.'/style.css');

  $this->pageTitle = $this->unitName.' | '.Yii::app()->name;
?>
<article>
  <div class="page-header">
    <h1><?php echo $this->unitName; ?></h1>
  </div>
  <article class="tabs-panel tabs-panel-top">
    <br>
    <?php if (isset($this->errMsg) && !empty($this->errMsg)) { ?>
      <div class="col-md-offset-2 col-md-8 alert alert-danger"><?php echo $this->errMsg; ?></div>
      <div class="clearfix"></div>
    <?php } ?>
    <?php $this->renderPartial($this->action->id.'/grid', array('model'=>$model, 'rowOffset'=>$rowOffset)); ?>
    <div class="clearfix"></div>
  </article>
</article>