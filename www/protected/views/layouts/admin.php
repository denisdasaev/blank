<?php /**
 * @var $this Controller
 */

//  $pBootstrap = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.bootstrap'), false, -1, YII_DEBUG);
//  $pFontAwesome = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.font_awesome'), false, -1, YII_DEBUG);
//  $pFancybox = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.fancybox'), false, -1, YII_DEBUG);
//  $pFlexSlider = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.flexslider'), false, -1, YII_DEBUG);
  $pLayouts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.views.layouts.assets'), false, -1, YII_DEBUG);

  Yii::app()->clientScript->registerCssFile('//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css');
//  Yii::app()->clientScript->registerCssFile('//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css');
//  Yii::app()->clientScript->registerCssFile($pBootstrap.'/css/bootstrap.min.css');
//  Yii::app()->clientScript->registerCssFile($pBootstrap.'/css/bootstrap-theme.min.css');
//  Yii::app()->clientScript->registerCssFile($pFontAwesome.'/css/font-awesome.min.css');
//  Yii::app()->clientScript->registerCssFile($pFancybox.'/source/jquery.fancybox.css');
//  Yii::app()->clientScript->registerCssFile($pFlexSlider.'/flexslider.css');
  Yii::app()->clientScript->registerCssFile($pLayouts.'/admin.css');

  Yii::app()->clientScript->registerCoreScript('jquery');
  Yii::app()->clientScript->registerScriptFile('//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerScriptFile($pBootstrap.'/js/bootstrap.min.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerScriptFile($pFancybox.'/lib/jquery.mousewheel-3.0.6.pack.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerScriptFile($pFancybox.'/source/jquery.fancybox.pack.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerScriptFile($pFlexSlider.'/jquery.flexslider-min.js', CClientScript::POS_END);
  Yii::app()->clientScript->registerScriptFile($pLayouts.'/admin.js', CClientScript::POS_END);
  if (!Yii::app()->params['isMobileBrowser'] && Yii::app()->conf->get('SYSTEM/ADM_MENU_AUTO_HIDE'))
    Yii::app()->clientScript->registerScriptFile($pLayouts.'/admin-mhide.js', CClientScript::POS_END);

  $this->beginContent('//layouts/main');
?>

  <?php if (!Yii::app()->user->isGuest && Yii::app()->user->admin) { ?>
    <header>
      <div id="nav-main-area"></div>
      <nav id="nav-main" class="navbar navbar-default navbar-fixed-top" role="navigation"<?php if (!Yii::app()->params['isMobileBrowser'] && Yii::app()->conf->get('SYSTEM/ADM_MENU_AUTO_HIDE')) echo ' style="display:none;"'; ?>>
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $this->createUrl('main/index') ?>"><?php echo Yii::app()->name; ?></a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php $this->widget('zii.widgets.CMenu', array('items'=>CoreUnit::menuItems(), 'encodeLabel'=>false, 'htmlOptions'=>array('class'=>'nav navbar-nav'), 'submenuHtmlOptions'=>array('class'=>'dropdown-menu'))); ?>
          <?php $this->widget('zii.widgets.CMenu', array(
            'items'=>array(
              array('label'=>'<span class="glyphicon glyphicon-cog"></span> Настройки <b class="caret"></b>', 'url'=>'#', 'itemOptions'=>array('class'=>'dropdown'), 'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown', 'tabindex'=>'-1'), 'visible'=>Yii::app()->user->root, 'items'=>array(
                array('label'=>'Разделы', 'url'=>array('core/unit/index'), 'linkOptions'=>array('tabindex'=>'-1')),
                array('label'=>'Пользователи', 'url'=>array('core/user/index'), 'linkOptions'=>array('tabindex'=>'-1')),
                array('label'=>'', 'itemOptions'=>array('class'=>'divider')),
                array('label'=>'Буфер рассылки', 'url'=>array('core/mailbuf/index'), 'linkOptions'=>array('tabindex'=>'-1')),
                array('label'=>'Шаблоны писем', 'url'=>array('core/mailtpl/index'), 'linkOptions'=>array('tabindex'=>'-1')),
                array('label'=>'', 'itemOptions'=>array('class'=>'divider')),
                array('label'=>'Параметры', 'url'=>array('core/config/index'), 'linkOptions'=>array('tabindex'=>'-1')),
              )),
              array('label'=>(Yii::app()->user->root?'<span class="glyphicon glyphicon-star" title="Главный администратор"></span> ':'').Yii::app()->user->login.' <b class="caret"></b>', 'url'=>'#', 'itemOptions'=>array('class'=>'dropdown'), 'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown', 'tabindex'=>'10'), 'items'=>array(
                array('label'=>'Выйти', 'url'=>array('main/logout'), 'linkOptions'=>array('tabindex'=>'-1')),
              )),
            ), 'encodeLabel'=>false, 'htmlOptions'=>array('class'=>'nav navbar-nav navbar-right'), 'submenuHtmlOptions'=>array('class'=>'dropdown-menu'))); ?>
        </div>
      </nav>
    </header>
  <?php } ?>

  <article class="container shift-content">
    <?php echo $content; ?>
  </article>

  <footer>
    <div class="container">
      <section>
        <small>
          <?php if (!Yii::app()->user->isGuest && Yii::app()->user->admin) { ?>
            <br><br>
            Доступ к разделу: <em><?php echo $this->accessLabel(); ?></em>
          <?php } ?>
          <br>
          &copy; <?php echo Yii::app()->name.', '.date('Y'); ?>. Все права защищены.
        </small>
      </section>
      <?php if (YII_DEBUG) { ?>
        <section class="alert alert-danger shift-content">
          <h4>Внимание!</h4>
          Сайт находится в режиме разработки!
        </section>
      <?php } ?>
    </div>
  </footer>
<?php $this->endContent(); ?>