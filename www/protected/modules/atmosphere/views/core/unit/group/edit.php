<?php /**
 * @var $this DoController
 * @var $item null|CoreUnitGroup
 * @var $mfRouteParams string
 * @var $mfDelete bool
 */

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>'Группа'.(isset($item)?' &laquo;'.$item->name.'&raquo;':''),
  'mfRoute'=>'/'.ADMIN_MODULE.'/core/unit/group',
  'mfRouteParams'=>$mfRouteParams,
  'mfDelete'=>$mfDelete,
)); ?>

  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-sort"><?php echo CoreUnitGroup::model()->label('sort'); ?></label>
    <div class="col-lg-3">
      <input id="edit-sort" class="form-control" type="number" min="0" max="65535" name="edit[sort]" value="<?php echo (isset($item)?$item->sort:'0'); ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-name"><?php echo CoreUnitGroup::model()->label('name'); ?></label>
    <div class="col-lg-7">
      <input id="edit-name" class="form-control" type="text" name="edit[name]" value="<?php echo (isset($item)?$item->name:''); ?>" maxlength="20" required>
    </div>
  </div>

<?php $this->endContent(); ?>