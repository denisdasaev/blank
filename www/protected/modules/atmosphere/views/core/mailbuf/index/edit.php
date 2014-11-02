<?php /**
 * @var $this DoController
 * @var $item null|CoreMailBuffer
 * @var $mfRouteParams string
 * @var $mfDelete integer
 */

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>'Письмо'.(isset($item)?' №'.$item->id.' от '.$item->ts_add:''),
  'mfRoute'=>'/'.ADMIN_MODULE.'/core/mailbuf/index',
  'mfRouteParams'=>$mfRouteParams,
  'mfDelete'=>$mfDelete,
)); ?>

  <div class="form-group">
    <label class="col-lg-4 control-label"><?php echo CoreMailBuffer::model()->label('ts_add'); ?></label>
    <div class="col-lg-7">
      <p class="form-control-static"><?php echo (isset($item)?$item->ts_add:''); ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label"><?php echo CoreMailBuffer::model()->label('ts_send'); ?></label>
    <div class="col-lg-7">
      <p class="form-control-static"><?php echo (isset($item)?$item->ts_send:''); ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-priority"><?php echo CoreMailBuffer::model()->label('priority'); ?></label>
    <div class="col-lg-7">
      <select id="edit-priority" class="form-control" name="edit[priority]">
        <?php echo CoreMailer::priorityHtmlOptions((isset($item)?$item->priority:CoreMailer::$defaultPriority)); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-4 col-lg-7">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="edit[sent]"<?php echo ((isset($item) && $item->sent)?' checked':''); ?>>
          <?php echo CoreMailBuffer::model()->label('sent'); ?>
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-send-retries"><?php echo CoreMailBuffer::model()->label('send_retries'); ?></label>
    <div class="col-lg-2">
      <input id="edit-send-retries" class="form-control" type="number" name="edit[send_retries]" value="<?php echo (isset($item)?$item->send_retries:''); ?>" maxlength="1" min="0" max="9">
    </div>
  </div>
  <h4>Письмо</h4>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-mail-to"><?php echo CoreMailBuffer::model()->label('mail_to'); ?></label>
    <div class="col-lg-7">
      <input id="edit-mail-to" class="form-control" type="text" name="edit[mail_to]" value="<?php echo (isset($item)?$item->mail_to:''); ?>" maxlength="150">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-mail-subj"><?php echo CoreMailBuffer::model()->label('mail_subj'); ?></label>
    <div class="col-lg-7">
      <input id="edit-mail-subj" class="form-control" type="text" name="edit[mail_subj]" value="<?php echo (isset($item)?$item->mail_subj:''); ?>" maxlength="150">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-mail-body"><?php echo CoreMailBuffer::model()->label('mail_body'); ?></label>
    <div class="col-lg-7">
      <textarea id="edit-mail-body" class="form-control" name="edit[mail_body]" rows="4"><?php echo (isset($item)?$item->mail_body:''); ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-mail-headers"><?php echo CoreMailBuffer::model()->label('mail_headers'); ?></label>
    <div class="col-lg-7">
      <textarea id="edit-mail-headers" class="form-control" name="edit[mail_headers]" rows="4"><?php echo (isset($item)?$item->mail_headers:''); ?></textarea>
    </div>
  </div>

<?php $this->endContent(); ?>