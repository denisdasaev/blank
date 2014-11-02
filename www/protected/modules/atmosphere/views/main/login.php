<?php /**
   * @var $this MainController
   * @var $login string
   */

  $scripts = Yii::app()->assetManager->publish(Yii::getPathOfAlias($this->module->id.'.views.'.$this->id.'.'.$this->action->id.'.assets'), false, -1, YII_DEBUG);
  Yii::app()->clientScript->registerScriptFile($scripts.'/script.js', CClientScript::POS_END);
//  Yii::app()->clientScript->registerCssFile($scripts.'/style.css');

  $this->pageTitle = 'Вход в административный раздел — '.Yii::app()->name;
?>

<h1>Административный раздел</h1>

<br><br>
<form method="post" role="form" class="form-horizontal">
  <fieldset>
    <legend>Представьтесь пожалуйста</legend>
    <br>

    <?php if (!empty($this->errMsg)): ?>
      <div class="alert alert-danger">
        <strong>Ошибка!</strong> <?php echo $this->errMsg; ?>
      </div>
    <?php endif; ?>

    <div class="form-group">
      <label for="lg" class="col-lg-3 control-label">Логин</label>
      <div class="col-lg-6">
        <input id="lg" type="text" class="form-control" name="lg" value="<?php echo $login; ?>">
      </div>
    </div>

    <div class="form-group">
      <label for="pw" class="col-lg-3 control-label">Пароль</label>
      <div class="col-lg-6">
        <input id="pw" type="password" class="form-control" name="pw">
      </div>
    </div>

    <div class="form-group">
      <div class="col-lg-offset-3 col-lg-6">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="mm"> Запомнить меня
          </label>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="col-lg-offset-3 col-lg-6">
        <button type="submit" class="btn btn-default">Войти</button>
      </div>
    </div>
  </fieldset>
</form>