<?php
  /**
   * @var $this ConfigController
   * @var $model CoreConfig
   * @var $groups array of CoreConfigGroup
   * @var $activeGroup CoreConfigGroup|null
   */

  $scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot.protected.modules.'.$this->module->id.'.views.'.$this->id.'.'.$this->action->id.'.assets'), false, -1, YII_DEBUG);
  Yii::app()->clientScript->registerScriptFile($scripts.'/script.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerCssFile($scripts.'/style.css');

  $this->pageTitle = $this->unitName.' | '.Yii::app()->name;
?>
<article>
  <?php if (defined('YII_DEBUG') && YII_DEBUG) { ?>
  <div class="page-header">
    <div class="left">
  <?php } ?>
      <h1><?php echo $this->unitName; ?></h1>
  <?php if (defined('YII_DEBUG') && YII_DEBUG) { ?>
    </div>
    <div class="right" style="padding-top:20px;">
      <button id="add-group" class="btn btn-default" data-group="<?php echo ($activeGroup?$activeGroup->id:''); ?>" title="Добавить группу"><span class="glyphicon glyphicon-plus"></span> Группа</button>
    </div>
    <div class="clearfix"></div>
  </div>
  <?php } ?>
  <nav>
    <ul class="nav nav-tabs">
      <?php foreach ($groups as $group) { ?>
        <li<?php echo (($activeGroup && $group->id == $activeGroup->id)?' class="active"':''); ?>><a href="<?php echo $this->createUrl('index', array('g'=>$group->id))?>"><?php echo $group->name; ?></a></li>
      <?php } ?>
    </ul>
  </nav>
  <article class="tabs-panel">
    <br>
    <?php if (isset($this->errMsg) && !empty($this->errMsg)) { ?>
      <div class="col-md-offset-2 col-md-8 alert alert-danger"><?php echo $this->errMsg; ?></div>
      <div class="clearfix"></div>
    <?php } ?>
    <div class="col-md-10">
      <?php if ($activeGroup && $activeGroup->descr) { ?>
        <p><?php echo $activeGroup->descr; ?></p>
      <?php } ?>
    </div>
    <?php if ($activeGroup && defined('YII_DEBUG') && YII_DEBUG) { ?>
      <div class="col-md-2">
        <button id="edit-group" class="btn btn-default btn-xs btn-block" data-group="<?php echo $activeGroup->id; ?>" title="Редактировать группу &laquo;<?php echo $activeGroup->name; ?>&raquo;"><span class="glyphicon glyphicon-pencil"></span> Группа</button>
        <button id="add-param" class="btn btn-default btn-xs btn-block" data-group="<?php echo $activeGroup->id; ?>" title="Добавить параметр"><span class="glyphicon glyphicon-plus"></span> Параметр</button>
      </div>
      <div class="clearfix"></div>
    <?php } ?>
    <?php $this->renderPartial($this->action->id.'/grid', array(
      'model'=>$model,
      'activeGroup'=>$activeGroup,
    )); ?>
    <div class="clearfix"></div>
  </article>
</article>