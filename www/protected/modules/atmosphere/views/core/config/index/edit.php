<?php /**
 * @var $this DoController
 * @var $item null|CoreConfig
 * @var $g integer|null
 * @var $mfDelete integer
 */

$controller_id = 'config';
$method_id = 'index';
$scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias(ADMIN_MODULE.'.views.core.'.$controller_id.'.'.$method_id.'.assets'), false, -1, YII_DEBUG);

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>(isset($item)?$item->label:'Новый параметр'),
  'mfRoute'=>'/'.ADMIN_MODULE.'/core/config/index',
  'mfRouteParams'=>(isset($g)?array('g'=>$g):array()),
  'mfFileUploading'=>(isset($item) && in_array($item->type, array(CoreConfig::TYPE_FILE_JPEG, CoreConfig::TYPE_FILE_PNG))),
  'mfDelete'=>$mfDelete,
)); ?>

  <script src="<?php echo $scripts.'/edit.js'; ?>"></script>
  <?php if (defined('YII_DEBUG') && YII_DEBUG) { ?>
    <div class="form-group">
      <label class="col-lg-4 control-label" for="edit-group-id"><?php echo CoreConfig::model()->label('group_id'); ?></label>
      <div class="col-lg-7">
        <select id="edit-group-id" class="form-control" name="edit[group_id]">
          <?php echo CoreConfigGroup::listHtmlOptions(isset($item)?$item->group_id:(isset($g)?$g:null)); ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-4 control-label" for="edit-param"><?php echo CoreConfig::model()->label('param'); ?></label>
      <div class="col-lg-7">
        <input id="edit-param" type="text" name="edit[param]" value="<?php echo (isset($item)?$item->param:''); ?>" class="form-control">
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-4 control-label" for="edit-type"><?php echo CoreConfig::model()->label('type'); ?></label>
      <div class="col-lg-7">
        <select id="edit-type" class="form-control" name="edit[type]">
          <?php foreach (CoreConfig::$typeLabels as $type => $label) { ?>
            <option value="<?php echo $type; ?>"<?php echo ((isset($item) && $item->type == $type)?' selected':''); ?>><?php echo $label; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
  <?php } ?>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-value"><?php echo CoreConfig::model()->label('value'); ?></label>
    <div class="col-lg-<?php echo ((isset($item) && $item->type == CoreConfig::TYPE_NUMBER)?3:7); ?>"<?php echo ((isset($item) && $item->type == CoreConfig::TYPE_FILE_JPEG)?' id="edit-file-input"':''); ?>>
      <?php if (isset($item) && $item->type == CoreConfig::TYPE_TEXT) { ?>
        <div class="btn-group">
          <button id="text-editor-bold" type="button" class="btn btn-info btn-xs" title="Жирный"><span class="icon-bold"></span></button>
          <button id="text-editor-italic" type="button" class="btn btn-info btn-xs" title="Курсив"><span class="icon-italic"></span></button>
          <button id="text-editor-strike" type="button" class="btn btn-info btn-xs" title="Зачеркнутый"><span class="icon-strikethrough"></span></button>
          <button id="text-editor-underline" type="button" class="btn btn-info btn-xs" title="Подчеркнутый"><span class="icon-underline"></span></button>
          <button id="text-editor-ulist" type="button" class="btn btn-info btn-xs" title="Простой список"><span class="icon-list-ul"></span></button>
          <button id="text-editor-olist" type="button" class="btn btn-info btn-xs" title="Нумерованный список"><span class="icon-list-ol"></span></button>
          <button id="text-editor-link" type="button" class="btn btn-info btn-xs" title="Ссылка"><span class="icon-link"></span></button>
        </div>
        <textarea id="edit-value" name="edit[value]" rows="5" class="form-control"><?php echo strip_tags($item->value, '<p><h1><h2><h3><h4><h5><h6><a><strong><b><i><em><small><u><ul><ol><li><img>'); ?></textarea>
      <?php } elseif (isset($item) && $item->type == CoreConfig::TYPE_BOOL) { ?>
        <select id="edit-value" name="edit[value]" class="form-control">
          <option value="0">Нет</option>
          <option value="1"<?php echo ($item->value?' selected':''); ?>>Да</option>
        </select>
      <?php } elseif (isset($item) && $item->type == CoreConfig::TYPE_NUMBER) { ?>
        <input id="edit-value" type="number" name="edit[value]" value="<?php echo $item->value; ?>" class="form-control">
      <?php } elseif (isset($item) && $item->type == CoreConfig::TYPE_FILE_JPEG) { ?>
        <?php if ($item->value) { ?>
          <a href="/img/conf/<?php echo $item->id; ?>.jpg" target="_blank"><img src="/img/conf/<?php echo $item->id; ?>.jpg" height="70" style="float:left;"></a>
          <a id="edit-del-img" href="#" class="btn btn-xs btn-danger" title="Удалить" data-conf-id="<?php echo $item->id; ?>"><span class="glyphicon glyphicon-remove"></span></a>
        <?php } else { ?>
          <input id="edit-value" type="file" name="edit_file" class="form-control" accept="image/jpeg">
          <input type="hidden" name="edit[value]" value="">
        <?php } ?>
      <?php } elseif (isset($item) && $item->type == CoreConfig::TYPE_FILE_PNG) { ?>
        <?php if ($item->value) { ?>
          <a href="/img/conf/<?php echo $item->id; ?>.png" target="_blank"><img src="/img/conf/<?php echo $item->id; ?>.png" height="70" style="float:left;"></a>
          <a id="edit-del-img" href="#" class="btn btn-xs btn-danger" title="Удалить" data-conf-id="<?php echo $item->id; ?>"><span class="glyphicon glyphicon-remove"></span></a>
        <?php } else { ?>
          <input id="edit-value" type="file" name="edit_file" class="form-control" accept="image/png">
          <input type="hidden" name="edit[value]" value="">
        <?php } ?>
      <?php } elseif (isset($item) && $item->type == CoreConfig::TYPE_EMAIL) { ?>
        <input id="edit-value" type="email" name="edit[value]" value="<?php echo $item->value; ?>" class="form-control">
      <?php } else { ?>
        <input id="edit-value" type="text" name="edit[value]" value="<?php echo (isset($item)?$item->value:''); ?>" class="form-control">
      <?php } ?>
    </div>
  </div>
  <?php if (defined('YII_DEBUG') && YII_DEBUG) { ?>
    <div class="form-group">
      <div class="col-lg-offset-4 col-lg-7">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="edit_value_def" value="1" <?php echo ((isset($item) && $item->value_default == $item->value)?' checked':''); ?>>
            <?php echo CoreConfig::model()->label('value_default'); ?>
          </label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-4 control-label" for="edit-label"><?php echo CoreConfig::model()->label('label'); ?></label>
      <div class="col-lg-7">
        <input id="edit-label" type="text" name="edit[label]" value="<?php echo (isset($item)?$item->label:''); ?>" class="form-control">
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-4 control-label" for="edit-descr"><?php echo CoreConfig::model()->label('descr'); ?></label>
      <div class="col-lg-7">
        <textarea id="edit-descr" name="edit[descr]" rows="4" class="form-control"><?php echo (isset($item)?$item->descr:''); ?></textarea>
      </div>
    </div>
  <?php } ?>

<?php $this->endContent(); ?>