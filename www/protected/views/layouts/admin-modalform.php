<?php /**
 * @var $this DoController    - при Ajax запросах
 * @var $item null|object     - режим создания/изменения сущности
 * @var $mfTitle string       - заголовок модального окна
 * @var $mfRoute string       - путь перенаправления данных формы
 * @var $mfRouteParams string - параметры пути перенаправления
 * @var $mfFileUploading bool - флаг, указывающий на предназначение формы под загрузку файлов на сервер
 * @var $mfDelete bool        - флаг отображения кнопки "Удалить", если не указано, то на автомате
 * @var $mfOk bool            - флаг отображения кнопки "Сохранить"
 * @var $mfOkLabel string     - подпись на кнопке "OK", если не указано, то "Сохранить"
 * @var $mfOkId string        - ID HTML-элемента кнопки "OK"
 * @var $mfOkClass string     - класс HTML-элемента кнопки "OK"
 * @var $mfOkDisabled bool    - доступность кнопки "OK"
 * @var $mfCancel bool        - флаг отображения кнопки "Отмена"
 * @var $mfCancelLabel string - подпись на кнопке "Cancel", если не указано, то "Отмена"
 */

  $scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.views.layouts.assets'), false, -1, YII_DEBUG);
?>
<script type="text/javascript" src="<?php echo $scripts.'/admin-modalform.js'; ?>"></script>
<div id="modal-content" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalEdit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="modalEdit" class="modal-title"><?php echo $mfTitle; ?></h3>
      </div>
      <div class="modal-body">
        <form id="modal-form" action="<?php echo Yii::app()->createUrl($mfRoute, (isset($mfRouteParams)?$mfRouteParams:array())).(isset($item)?'#item-'.$item->id:''); ?>" method="post" class="form-horizontal"<?php echo ((isset($mfFileUploading) && $mfFileUploading)?' enctype="multipart/form-data"':''); ?>>
          <?php if (isset($mfFileUploading) && $mfFileUploading) { ?>
            <input type="hidden" name="MAX_FILE_SIZE" value="100000000"><!--- 100Mb --->
          <?php } ?>
          <?php echo $content; ?>
        </form>
      </div>
      <div class="modal-footer">
        <?php if (isset($item)) { ?>
          <input type="hidden" name="item_id" value="<?php echo $item->id; ?>" form="modal-form">
          <span id="mf-button-delete">
            <?php if (isset($mfDelete) && $mfDelete) { ?>
              <a class="btn btn-danger pull-left"  href="<?php echo Yii::app()->createUrl($mfRoute, (isset($mfRouteParams)?array_merge($mfRouteParams,array('del'=>$item->id)):array('del'=>$item->id))); ?>" onclick="return confirm('Удалить эту запись?')">Удалить</a>
            <?php } ?>
          </span>
        <?php } ?>
        <span id="mf-button-ok">
          <?php if (!isset($mfOk) || $mfOk) { ?>
            <button<?php echo (isset($mfOkId)?' id="'.$mfOkId.'"':''); ?> class="<?php echo (isset($mfOkClass)?$mfOkClass:'btn btn-primary'); ?>" form="modal-form"<?php echo ((isset($mfOkDisabled) && $mfOkDisabled)?' disabled':''); ?>><?php echo (isset($mfOkLabel)?$mfOkLabel:'Сохранить'); ?></button>
          <?php } ?>
        </span>
        <span id="mf-button-cancel">
          <?php if (!isset($mfCancel) || $mfCancel) { ?>
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo (isset($mfCancelLabel)?$mfCancelLabel:'Отмена'); ?></button>
          <?php } ?>
        </span>
      </div>
    </div>
  </div>
</div>