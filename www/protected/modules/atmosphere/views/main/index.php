<?php
  /**
   * @var $this MainController
   * @var $groups array of CoreUnitGroup
   * @var $coreUnits array of CoreUnit
   */

//  $scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias($this->module->id.'.views.'.$this->id.'.'.$this->action->id.'.assets'), false, -1, YII_DEBUG);
//  Yii::app()->clientScript->registerScriptFile($scripts.'/script.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerCssFile($scripts.'/style.css');

  $this->pageTitle = Yii::app()->name;
?>

<?php if (!Yii::app()->user->isGuest && Yii::app()->user->admin) { ?>
  <div class="page-header">
    <h1>Добро пожаловать в административный раздел сайта!</h1>
  </div>
<?php } ?>

<?php if (Yii::app()->user->root) { ?>
  <?php foreach ($groups as $group) { ?>
    <?php if ($group->units) { ?>
      <h5><?php echo $group->name ?></h5>
      <?php foreach ($group->units as $unit) { ?>
        <dl class="dl-horizontal">
          <dt><a href="<?php echo $this->createUrl($unit->controller.'/index'); ?>"><?php echo $unit->name; ?></a></dt>
          <dd><?php echo $unit->descr; ?></dd>
        </dl>
      <?php } ?>
    <?php } ?>
  <?php } ?>
  <?php if ($coreUnits) { ?>
    <h5>Прочие разделы</h5>
    <?php foreach ($coreUnits as $coreUnit) { ?>
      <dl class="dl-horizontal">
        <dt><a href="<?php echo $this->createUrl($coreUnit->controller.'/index'); ?>"><?php echo $coreUnit->name; ?></a></dt>
        <dd><?php echo $coreUnit->descr; ?></dd>
      </dl>
    <?php } ?>
  <?php } ?>
  <h5><span class="glyphicon glyphicon-cog"></span> Настройки</h5>
  <dl class="dl-horizontal">
    <dt><a href="<?php echo $this->createUrl('core/unit/index'); ?>">Разделы</a></dt>
    <dd>Управление разделами административной части сайта и их <a href="<?php echo $this->createUrl('core/unit/group'); ?>">групами</a>.</dd>
  </dl>
  <dl class="dl-horizontal">
    <dt><a href="<?php echo $this->createUrl('core/user/index'); ?>">Пользователи</a></dt>
    <dd>Раздел для управления администраторами системы и их <a href="<?php echo $this->createUrl('core/user/group'); ?>">группами</a> (ролями) с присущими им правами (полномочиями).</dd>
  </dl>
  <dl class="dl-horizontal">
    <dt><a href="<?php echo $this->createUrl('core/mailbuf/index'); ?>">Буфер рассылки</a></dt>
    <dd>Буфер рассылки содержит электронные письма, которые подготовлены сайтом к отправке. Также, содержит <a href="<?php echo $this->createUrl('core/mailbuf/archive'); ?>">архив</a> всех отправленных писем.</dd>
  </dl>
  <dl class="dl-horizontal">
    <dt><a href="<?php echo $this->createUrl('core/mailtpl/index'); ?>">Шаблоны писем</a></dt>
    <dd>Типовые шаблоны системных писем, по которым формируются письма для клиентов и пользователей сайта, а так же их <a href="<?php echo $this->createUrl('core/mailtpl/group'); ?>">группы</a>.</dd>
  </dl>
  <dl class="dl-horizontal">
    <dt><a href="<?php echo $this->createUrl('core/config/index'); ?>">Параметры</a></dt>
    <dd>Управление основными параметрами сайта.</dd>
  </dl>
<?php } elseif (Yii::app()->user->admin) { ?>
  <?php $lastGroup = ''; ?>
  <?php foreach (Yii::app()->user->rights as $right) { ?>
    <?php $curGroup = (isset($right->unit->group)?$right->unit->group->name:'Прочие разделы'); ?>
    <?php if ($curGroup != $lastGroup) { ?>
      <?php $lastGroup = $curGroup; ?>
      <h5><?php echo $curGroup; ?></h5>
    <?php } ?>
    <dl class="dl-horizontal">
      <dt><a href="<?php echo $this->createUrl($right->unit->controller.'/index'); ?>"><?php echo $right->unit->name; ?></a></dt>
      <dd><?php echo $right->unit->descr; ?></dd>
    </dl>
  <?php } ?>
<?php } else { ?>
  <h1>Доступ запрещён!</h1>
<?php } ?>