<?php /**
 * @var $this DoController
 * @var $item null|CoreClient
 * @var $mfRouteParams string
 * @var $mfDelete bool
 */

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>'Клиент'.(isset($item)?' &laquo;'.$item->login.'&raquo;':''),
  'mfRoute'=>'/'.ADMIN_MODULE.'/client/index',
  'mfRouteParams'=>$mfRouteParams,
  'mfDelete'=>$mfDelete,
)); ?>

  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-login"><?php echo CoreClient::model()->label('login'); ?></label>
    <div class="col-lg-7">
      <input id="edit-login" type="text" name="edit[login]" value="<?php echo (isset($item)?$item->login:''); ?>" class="form-control" maxlength="20">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-password"><?php echo CoreClient::model()->label('password'); ?></label>
    <div class="col-lg-7">
      <input id="edit-password" type="text" name="edit[password]" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-password2"><?php echo CoreClient::model()->label('password'); ?> (ещё раз)</label>
    <div class="col-lg-7">
      <input id="edit-password2" type="text" name="edit_pw" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-email"><?php echo CoreClient::model()->label('email'); ?></label>
    <div class="col-lg-7">
      <input id="edit-email" type="email" name="edit[email]" value="<?php echo (isset($item)?$item->email:''); ?>" class="form-control" maxlength="30">
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-4 col-lg-7">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="edit[state]" value="1" <?php echo ((isset($item) && $item->state)?' checked':''); ?>>
          <?php echo CoreClient::model()->label('state'); ?>
        </label>
      </div>
    </div>
  </div>

<?php $this->endContent(); ?>