<?php /**
 * @var $this DoController
 * @var $item null|CoreClient
 * @var $mfRouteParams string
 * @var $mfDelete bool
 */

$this->beginContent('//layouts/admin-modalform', array(
  'item'=>(isset($item)?$item:null),
  'mfTitle'=>'Пользователь'.(isset($item)?' &laquo;'.$item->login.'&raquo;':''),
  'mfRoute'=>'/'.ADMIN_MODULE.'/core/user/index',
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
  <?php if (isset($item)) { ?>
    <div class="form-group">
      <label class="col-lg-4 control-label" for="edit-pw-cur"><?php echo CoreClient::model()->label('password'); ?> (текущий)</label>
      <div class="col-lg-7">
        <input id="edit-pw-cur" type="text" name="edit_pw_cur" class="form-control">
      </div>
    </div>
  <?php } ?>
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
          <input type="checkbox" name="edit_admin[root]" value="1"<?php echo ((isset($item) && $item->admin && $item->admin->root)?' checked':''); ?>>
          <?php echo CoreAdmin::model()->label('root'); ?>
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="edit-group"><?php echo CoreAdmin::model()->label('group_id'); ?></label>
    <div class="col-lg-7">
      <select id="edit-group" name="edit_admin[group_id]" class="form-control">
        <option value="">(нет)</option>
        <?php echo CoreAdminGroup::listHtmlOptions((isset($item) && $item->admin)?$item->admin->group_id:null); ?>
      </select>
    </div>
  </div>
  <input type="hidden" name="edit[state]" value="1">

<?php $this->endContent(); ?>