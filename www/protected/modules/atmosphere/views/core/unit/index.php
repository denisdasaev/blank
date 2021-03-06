<?php
  /**
   * @var $this UnitController
   * @var $model CoreUnit|CoreUnitGroup
   * @var $rowOffset integer
   */

  $this->pageTitle = $this->unitName.' | '.Yii::app()->name;
?>
<article>
  <div class="page-header">
    <h1><?php echo $this->unitName; ?></h1>
  </div>
  <nav>
    <ul class="nav nav-tabs">
      <li<?php echo ($this->action->id == 'index'?' class="active"':''); ?>><a href="<?php echo $this->createUrl('index'); ?>">Разделы</a></li>
      <li<?php echo ($this->action->id == 'group'?' class="active"':''); ?>><a href="<?php echo $this->createUrl('group'); ?>">Группы</a></li>
    </ul>
  </nav>
  <article class="tabs-panel">
    <br>
    <?php if (isset($this->errMsg) && !empty($this->errMsg)) { ?>
      <div class="col-md-offset-2 col-md-8 alert alert-danger"><?php echo $this->errMsg; ?></div>
      <div class="clearfix"></div>
    <?php } ?>
    <?php $this->renderPartial($this->action->id.'/grid', array('model'=>$model, 'rowOffset'=>$rowOffset)); ?>
    <div class="clearfix"></div>
  </article>
</article>