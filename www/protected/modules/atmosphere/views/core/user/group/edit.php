<?php /**
 * @var $this DoController
 * @var $item null|CoreAdminGroup
 * @var $mfRouteParams string
 * @var $mfDelete bool
 */

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>'Группа'.(isset($item)?' &laquo;'.$item->name.'&raquo;':''),
  'mfRoute'=>'/'.ADMIN_MODULE.'/core/user/group',
  'mfRouteParams'=>$mfRouteParams,
  'mfDelete'=>$mfDelete,
)); ?>

  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-name"><?php echo CoreAdminGroup::model()->label('name'); ?></label>
    <div class="col-lg-7">
      <input id="edit-name" type="text" name="edit[name]" value="<?php echo (isset($item)?$item->name:''); ?>" class="form-control" maxlength="30">
    </div>
  </div>

<?php $this->endContent(); ?>