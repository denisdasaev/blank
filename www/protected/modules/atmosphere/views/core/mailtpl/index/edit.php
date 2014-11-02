<?php /**
 * @var $this DoController
 * @var $item null | CoreMailTpl
 * @var $mfRouteParams string
 * @var $mfDelete bool
 */

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>'Шаблон'.(isset($item)?' &laquo;'.$item->name.'&raquo;':''),
  'mfRoute'=>'/'.ADMIN_MODULE.'/core/mailtpl/index',
  'mfRouteParams'=>$mfRouteParams,
  'mfDelete'=>$mfDelete,
)); ?>

  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-group-id"><?php echo CoreMailTpl::model()->label('group_id'); ?></label>
    <div class="col-lg-7">
      <select id="edit-group-id" class="form-control" name="edit[group_id]">
        <?php echo CoreMailTplGroup::listHtmlOptions(isset($item)?$item->group_id:null); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-name"><?php echo CoreMailTpl::model()->label('name'); ?></label>
    <div class="col-lg-7">
      <input id="edit-name" class="form-control" type="text" name="edit[name]" value="<?php echo (isset($item)?$item->name:''); ?>" maxlength="20">
    </div>
  </div>
  <h4>Письмо</h4>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-subj"><?php echo CoreMailTpl::model()->label('subj'); ?></label>
    <div class="col-lg-7">
      <input id="edit-subj" class="form-control" type="text" name="edit[subj]" value="<?php echo (isset($item)?$item->subj:''); ?>" maxlength="256">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-body"><?php echo CoreMailTpl::model()->label('body'); ?></label>
    <div class="col-lg-7">
      <textarea id="edit-body" class="form-control" name="edit[body]" rows="4"><?php echo (isset($item)?$item->body:''); ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-default-priority"><?php echo CoreMailTpl::model()->label('default_priority'); ?></label>
    <div class="col-lg-7">
      <select id="edit-default-priority" class="form-control" name="edit[default_priority]">
        <?php echo CoreMailer::priorityHtmlOptions((isset($item)?$item->default_priority:CoreMailer::$defaultPriority)); ?>
      </select>
    </div>
  </div>

<?php $this->endContent(); ?>