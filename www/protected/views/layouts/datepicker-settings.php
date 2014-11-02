<?php /**
   * @var $date_range array|null
   */
  $scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.views.layouts.assets'), false, -1, YII_DEBUG);
  Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
  Yii::app()->clientScript->registerCssFile($scripts.'/jquery-ui.css');
?>
<script>
  $(".datepkr").datepicker({
    "showAnim":"fade",
    "dayNames":["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"],
    "dayNamesShort":["Вск","Пнд","Втр","Срд","Чтв","Птн","Сбб"],
    "dayNamesMin":["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],
    "firstDay":1,
    "monthNames":["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
    "monthNamesShort":["Янв","Фев","Мрт","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"],
    "dateFormat":"dd-mm-yy",
    "changeMonth":true,
    "minDate":"<?php echo (isset($date_range['min'])?$date_range['min']:''); ?>",
    "maxDate":"<?php echo (isset($date_range['max'])?$date_range['max']:''); ?>",
    "nextText":"Следующий месяц",
    "prevText":"Предыдущий месяц"
  });
</script>