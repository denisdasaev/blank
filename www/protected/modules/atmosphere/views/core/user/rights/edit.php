<?php /**
 * @var $this DoController
 * @var $item null|CoreAdminGroup with CoreAdminGroupRight
 * @var $units CoreUnit with CoreUnitGroup
 * @var $rights array where key is a unit_id and value is a type of right
 * @var $mfRouteParams string
 */

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>'Права для группы'.(isset($item)?' &laquo;'.$item->name.'&raquo;':''),
  'mfRoute'=>'/'.ADMIN_MODULE.'/core/user/rights',
  'mfRouteParams'=>$mfRouteParams,
)); ?>

  <?php foreach ($units as $unit) if ($unit->name != '') { ?>
    <div class="form-group">
      <label class="col-lg-4 control-label" for="edit-unit-<?php echo $unit->id; ?>">
        <?php echo (isset($unit->group)?$unit->group->name.' / ':'').$unit->name; ?>
      </label>
      <div class="col-lg-7">
        <select id="edit-unit-<?php echo $unit->id; ?>" name="edit_unit[<?php echo $unit->id; ?>]" class="form-control">
          <option value="<?php echo $this::ACCESS_DENIED; ?>"<?php echo ($rights[$unit->id] == $this::ACCESS_DENIED?' selected':''); ?>><?php echo $this->accessLevelLabel[$this::ACCESS_DENIED]; ?></option>
          <option value="<?php echo $this::ACCESS_VIEW; ?>"<?php echo ($rights[$unit->id] == $this::ACCESS_VIEW?' selected':''); ?>><?php echo $this->accessLevelLabel[$this::ACCESS_VIEW]; ?></option>
          <option value="<?php echo $this::ACCESS_EDIT; ?>"<?php echo ($rights[$unit->id] == $this::ACCESS_EDIT?' selected':''); ?>><?php echo $this->accessLevelLabel[$this::ACCESS_EDIT]; ?></option>
          <option value="<?php echo $this::ACCESS_DELETE; ?>"<?php echo ($rights[$unit->id] == $this::ACCESS_DELETE?' selected':''); ?>><?php echo $this->accessLevelLabel[$this::ACCESS_DELETE]; ?></option>
        </select>
      </div>
    </div>
  <?php } ?>

<?php $this->endContent(); ?>