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
//  Yii::app()->clientScript->registerCssFile($pLayouts.'/public.css');

  Yii::app()->clientScript->registerCoreScript('jquery');
  Yii::app()->clientScript->registerScriptFile('//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerScriptFile($pBootstrap.'/js/bootstrap.min.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerScriptFile($pFancybox.'/lib/jquery.mousewheel-3.0.6.pack.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerScriptFile($pFancybox.'/source/jquery.fancybox.pack.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerScriptFile($pFlexSlider.'/jquery.flexslider-min.js', CClientScript::POS_END);
  Yii::app()->clientScript->registerScriptFile($pLayouts.'/public.js', CClientScript::POS_END);

  $this->beginContent('//layouts/main');
?>

  <header class="container" xmlns="http://www.w3.org/1999/html">
    <article class="pull-left">
      <h3><a href="/"><?php echo Yii::app()->name; ?></a></h3>
    </article>

    <nav class="pull-right">
      <br>
      <ul class="list-inline">
        <?php if (Yii::app()->user->isGuest) { ?>
          <li><a class="login" href="#">login</a></li>
          <li><a class="register" href="#">register</a></li>
        <?php } else { ?>
          <li><a href="/logout">logout (<?php echo Yii::app()->user->login; ?>)</a></li>
        <?php } ?>
      </ul>
    </nav>

    <div class="clearfix"></div>
    <hr>
  </header>

  <article class="container">
    <?php echo $content; ?>
  </article>

  <footer class="container">
    <hr>
    &copy; <?php echo Yii::app()->name.', '.date('Y'); ?>.
  </footer>
<?php $this->endContent(); ?>