<?php /**
 * @var $this DoController
 * @var $login string
 */

//$scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.views.do.reg.assets'), false, -1, YII_DEBUG);

$this->beginContent('//layouts/admin-modalform', array(
  'mfTitle'=>'Вход на сайт',
  'mfRoute'=>'do/login',
  'mfOkLabel'=>'Войти',
  'mfOkId'=>'btn-ok',
  'mfOkClass'=>'btn btn-success',
)); ?>

<!--  <script src="--><?php //echo $scripts.'/form-login.js'; ?><!--"></script>-->
  <div class="form-group">
    <label class="col-lg-4 control-label" for="fm-login">
      <?php echo CoreClient::model()->label('login'); ?>
    </label>
    <div class="col-lg-7">
      <input id="fm-login" type="text" maxlength="20" name="fm_login" class="form-control" value="<?php echo $login; ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label" for="fm-password">
      <?php echo CoreClient::model()->label('password'); ?>
    </label>
    <div class="col-lg-7">
      <input id="fm-password" type="password" name="fm_password" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-offset-4 col-lg-7">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="fm_remember"> Запомнить меня
        </label>
      </div>
    </div>
  </div>

<?php $this->endContent(); ?>