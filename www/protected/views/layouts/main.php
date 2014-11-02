<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo CHtml::encode($this->pageTitle); ?></title>
  <?php
    $pLayouts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.views.layouts.assets'), false, -1, YII_DEBUG);
    Yii::app()->clientScript->registerCssFile($pLayouts.'/main.css');
    if (YII_DEBUG)
      Yii::app()->clientScript->registerScriptFile($pLayouts.'/debug.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile($pLayouts.'/main.js', CClientScript::POS_END);
  ?>
  <!--- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries --->
  <!--- WARNING: Respond.js doesn't work if you view the page via file:// --->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
</head>

<body>
  <div id="w-shadow" style="background:#fff;position:fixed;top:0;left:0;opacity:0.95;width:100%;height:100%;z-index:1100;<?php echo (Yii::app()->conf->get('SYSTEM/LOADER')?'':'display:none;'); ?>"></div>
  <div id="main-loading" style="position:fixed;left:50%;top:50%;width:42px;height:42px;background:#fff url('/img/loading.gif') no-repeat scroll center center;z-index:1101;border:1px solid #eee;opacity:0.5;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;behavior:url('/css/PIE.htc');<?php echo (Yii::app()->conf->get('SYSTEM/LOADER')?'':'display:none;'); ?>"></div>
	<?php echo $content; ?>
  <input id="ctkn" type="hidden" name="ctkn" value="<?php echo (Yii::app()->user->isGuest?'':Yii::app()->user->token); ?>">
  <div id="rubber-box"></div>
</body>
<!-- coded by ninja -->
</html>