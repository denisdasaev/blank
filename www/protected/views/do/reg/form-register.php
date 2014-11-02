<?php /**
 * @var $this DoController
 */

$scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.views.do.reg.assets'), false, -1, YII_DEBUG);

$this->beginContent('//layouts/admin-modalform', array(
  'mfTitle'=>'Регистрация',
  'mfRoute'=>'do/register',
  'mfOkLabel'=>'Зарегистрироваться',
  'mfOkId'=>'reg-btn-ok',
  'mfOkClass'=>'btn btn-success btn-lg btn-block',
  'mfOkDisabled'=>true,
  'mfCancel'=>false,
)); ?>

  <script src="<?php echo $scripts.'/form-register.js'; ?>"></script>
  <div id="reg-login-group" class="form-group">
    <label class="col-lg-4 control-label" for="edit-login">
      <?php echo CoreClient::model()->label('login'); ?>
    </label>
    <div class="col-lg-7">
      <input id="edit-login" type="text" maxlength="20" name="edit[login]" class="form-control" placeholder="От 3 до 20 символов">
    </div>
    <div class="col-lg-1">
      <i id="reg-login-loading" class="loading16" style="display:none;"></i>
      <p class="form-control-static">
        <span id="reg-login-icon"></span>
      </p>
    </div>
  </div>
  <div id="reg-password-group" class="form-group">
    <label class="col-lg-4 control-label" for="edit-password">
      <?php echo CoreClient::model()->label('password'); ?>
    </label>
    <div class="col-lg-7">
      <input id="edit-password" type="password" name="edit[password]" class="form-control" placeholder="Не менее 5 символов">
    </div>
    <div class="col-lg-1">
      <i id="reg-password-loading" class="loading16" style="display:none;"></i>
      <p class="form-control-static">
        <span id="reg-password-icon"></span>
      </p>
    </div>
  </div>
  <div id="reg-password2-group" class="form-group">
    <label class="col-lg-4 control-label" for="edit-password2">
      <?php echo CoreClient::model()->label('password'); ?> (ещё раз)
    </label>
    <div class="col-lg-7">
      <input id="edit-password2" type="password" name="edit_pw" class="form-control" placeholder="Не менее 5 символов">
    </div>
    <div class="col-lg-1">
      <i id="reg-password2-loading" class="loading16" style="display:none;"></i>
      <p class="form-control-static">
        <span id="reg-password2-icon"></span>
      </p>
    </div>
  </div>
  <div id="reg-email-group" class="form-group">
    <label class="col-lg-4 control-label" for="edit-email">
      <?php echo CoreClient::model()->label('email'); ?>
    </label>
    <div class="col-lg-7">
      <input id="edit-email" type="email" maxlength="30" name="edit[email]" class="form-control" placeholder="Не более 30 символов">
    </div>
    <div class="col-lg-1">
      <i id="reg-email-loading" class="loading16" style="display:none;"></i>
      <p class="form-control-static">
        <span id="reg-email-icon"></span>
      </p>
    </div>
  </div>

<?php $this->endContent(); ?>