<?php /**
 * @var $this DoController
 * @var $item null|CoreUnit
 * @var $mfRouteParams string
 * @var $mfDelete bool
 */

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>'Раздел'.(isset($item)?' &laquo;'.$item->name.'&raquo;':''),
  'mfRoute'=>'/'.ADMIN_MODULE.'/core/unit/index',
  'mfRouteParams'=>$mfRouteParams,
  'mfDelete'=>$mfDelete,
)); ?>

  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-group-id"><?php echo CoreUnit::model()->label('group_id'); ?></label>
    <div class="col-lg-7">
      <select id="edit-group-id" class="form-control" name="edit[group_id]">
        <option value="">(нет)</option>
        <?php echo CoreUnitGroup::listHtmlOptions(isset($item)?$item->group_id:null); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-sort"><?php echo CoreUnit::model()->label('sort'); ?></label>
    <div class="col-lg-3">
      <input id="edit-sort" class="form-control" type="number" min="0" max="65535" name="edit[sort]" value="<?php echo (isset($item)?$item->sort:'0'); ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-name"><?php echo CoreUnit::model()->label('name'); ?></label>
    <div class="col-lg-7">
      <input id="edit-name" class="form-control" type="text" name="edit[name]" value="<?php echo (isset($item)?$item->name:''); ?>" maxlength="20">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-descr"><?php echo CoreUnit::model()->label('descr'); ?></label>
    <div class="col-lg-7">
      <textarea id="edit-descr" class="form-control" name="edit[descr]" rows="4"><?php echo (isset($item)?$item->descr:''); ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-controller"><?php echo CoreUnit::model()->label('controller'); ?></label>
    <div class="col-lg-7">
      <input id="edit-controller" class="form-control" type="text" name="edit[controller]" value="<?php echo (isset($item)?$item->controller:''); ?>" maxlength="20">
    </div>
  </div>

<?php $this->endContent(); ?>