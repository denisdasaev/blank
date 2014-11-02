<?php /**
 * @var $this DoController
 * @var $item null|CoreUnitGroup
 * @var $mfRouteParams string
 * @var $mfDelete integer
 */

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>'Группа шаблонов'.(isset($item)?' &laquo;'.$item->name.'&raquo;':''),
  'mfRoute'=>'/'.ADMIN_MODULE.'/core/mailtpl/group',
  'mfRouteParams'=>$mfRouteParams,
  'mfDelete'=>$mfDelete,
)); ?>

  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-name"><?php echo CoreMailTplGroup::model()->label('name'); ?></label>
    <div class="col-lg-7">
      <input id="edit-name" class="form-control" type="text" name="edit[name]" value="<?php echo (isset($item)?$item->name:''); ?>" maxlength="30" required>
    </div>
  </div>

<?php $this->endContent(); ?>