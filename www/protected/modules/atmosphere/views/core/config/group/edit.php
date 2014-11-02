<?php /**
 * @var $this DoController
 * @var $item null|CoreConfigGroup
 * @var $g integer|null
 * @var $mfDelete integer
 */

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>'Группа'.(isset($item)?' &laquo;'.$item->name.'&raquo;':''),
  'mfRoute'=>'/'.ADMIN_MODULE.'/core/config/group',
  'mfRouteParams'=>(isset($g)?array('g'=>$g):array()),
  'mfDelete'=>$mfDelete,
)); ?>

  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-name"><?php echo CoreConfigGroup::model()->label('name'); ?></label>
    <div class="col-lg-7">
      <input id="edit-name" type="text" name="edit[name]" value="<?php echo (isset($item->name)?$item->name:''); ?>" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-descr"><?php echo CoreConfigGroup::model()->label('descr'); ?></label>
    <div class="col-lg-7">
      <textarea id="edit-descr" name="edit[descr]" rows="5" class="form-control"><?php echo (isset($item)?$item->descr:''); ?></textarea>
    </div>
  </div>

<?php $this->endContent(); ?>