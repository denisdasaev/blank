<?php

class UserController extends CoreController
{
  public $unitName = 'Пользователи';

  /**
   * Пользователи.
   */
  public function actionIndex()
	{
    if (isset($_GET['del']) && $this->accessLevel >= self::ACCESS_DELETE
        && $_GET['del'] != Yii::app()->user->id && CoreClient::model()->findByPk($_GET['del'])->delete())
      $this->redirect($this->createUrlWithGet('/'.$this->route));

    if (isset($_POST['edit'], $_POST['edit_pw']) && $this->accessLevel >= self::ACCESS_EDIT)
    {
      if (isset($_POST['item_id']) && $client = CoreClient::model()->findByPk($_POST['item_id']))
      {
        $_POST['edit']['token'] = '';

        // смена логина
        if ($client->login != $_POST['edit']['login'] && CoreClient::model()->exists('login=:login', array(':login'=>$_POST['edit']['login'])))
          $_POST['edit']['login'] = $client->login;

        // смена пароля
        if (!empty($_POST['edit']['password']) && !empty($_POST['edit_pw']) && !empty($_POST['edit_pw_cur'])
            && $_POST['edit']['password'] == $_POST['edit_pw'] && md5($_POST['edit_pw_cur']) == $client->password)
          $_POST['edit']['password'] = md5($_POST['edit']['password']);
        else
          unset($_POST['edit']['password']);
      }
      else
      {
        $client = new CoreClient();

        if (!empty($_POST['edit']['password']) && !empty($_POST['edit_pw']) && $_POST['edit']['password'] == $_POST['edit_pw'])
          $_POST['edit']['password'] = md5($_POST['edit']['password']);
        else
          unset($_POST['edit']['password']);
      }
      $client->setAttributes($_POST['edit']);
      $client->save();

      if (!$admin = CoreAdmin::model()->findByPk($client->id))
      {
        $admin = new CoreAdmin();
        $admin->client_id = $client->id;
      }
      if (isset($_POST['edit_admin']))
      {
        $admin->root = 0;
        if (empty($_POST['edit_admin']['group_id']))
          $_POST['edit_admin']['group_id'] = null;
        $admin->setAttributes($_POST['edit_admin']);
      }
      $admin->save();

      $this->redirect($this->createUrlWithGet('/'.$this->route));
    }

    $model = new CoreClient('search');
    $model->unsetAttributes();
    if (isset($_GET['filter']))
      $model->attributes = $_GET['filter'];
    if (isset($_GET['filter']['admin_root']))
      $model->admin_root = $_GET['filter']['admin_root'];
    if (isset($_GET['filter']['admin_group_id']))
      $model->admin_group_id = $_GET['filter']['admin_group_id'];
    if (isset($_GET['filter']['ts_reg_fr']))
      $model->ts_reg_fr = $_GET['filter']['ts_reg_fr'];
    if (isset($_GET['filter']['ts_reg_to']))
      $model->ts_reg_to = $_GET['filter']['ts_reg_to'];
    if (isset($_GET['filter']['ts_act_fr']))
      $model->ts_act_fr = $_GET['filter']['ts_act_fr'];
    if (isset($_GET['filter']['ts_act_to']))
      $model->ts_act_to = $_GET['filter']['ts_act_to'];
    $this->render('index', array(
      'model'=>$model,
      'rowOffset'=>((isset($_GET['page']) && $_GET['page'] > 0)?(((int)$_GET['page']-1)*Yii::app()->conf->get('SYSTEM/ADM_PAGER_SIZE')):0),
    ));
	}

  /**
   * Группы.
   */
  public function actionGroup()
  {
    if (isset($_GET['del']) && $this->accessLevel >= self::ACCESS_DELETE
        && CoreAdminGroup::model()->findByPk($_GET['del'])->delete())
      $this->redirect($this->createUrlWithGet('/'.$this->route));

    if (isset($_POST['edit']) && $this->accessLevel >= self::ACCESS_EDIT)
    {
      if (!isset($_POST['item_id']) || !($item = CoreAdminGroup::model()->findByPk($_POST['item_id'])))
        $item = new CoreAdminGroup();
      $item->setAttributes($_POST['edit']);
      $item->save();
      $this->redirect($this->createUrlWithGet('/'.$this->route));
    }

    $model = new CoreAdminGroup('search');
    $model->unsetAttributes();
    if (isset($_GET['filter']))
      $model->attributes = $_GET['filter'];
    $this->render('index', array(
      'model'=>$model,
      'rowOffset'=>((isset($_GET['page']) && $_GET['page'] > 0)?(((int)$_GET['page']-1)*Yii::app()->conf->get('SYSTEM/ADM_PAGER_SIZE')):0),
    ));
  }

  /**
   * Права группы.
   */
  public function actionRights()
  {
    if (isset($_POST['edit_unit'], $_POST['item_id']) && $this->accessLevel >= self::ACCESS_EDIT
        && is_array($_POST['edit_unit']) && $_POST['edit_unit']
        && $adminGroup = CoreAdminGroup::model()->findByPk($_POST['item_id']))
    {
      CoreAdminGroupRight::model()->deleteAll('admin_group_id='.$adminGroup->id);
      foreach ($_POST['edit_unit'] as $unit_id => $rightType)
      {
        if ($rightType > self::ACCESS_DENIED)
        {
          $right = new CoreAdminGroupRight();
          $right->admin_group_id = $adminGroup->id;
          $right->unit_id = $unit_id;
          if ($rightType > self::ACCESS_VIEW)
            $right->right_edit = 1;
          if ($rightType > self::ACCESS_EDIT)
            $right->right_delete = 1;
          $right->save(false);
        }
      }

      // разделители
      $divUnits = CoreUnit::model()->findAll('name=""');
      foreach ($divUnits as $divUnit)
      {
        $right = new CoreAdminGroupRight();
        $right->admin_group_id = $adminGroup->id;
        $right->unit_id = $divUnit->id;
        $right->save(false);
      }
    }

    $this->redirect($this->createUrlWithGet('group'));
  }

  /**
   * Фильтры.
   *
   * @return array
   */
  public function filters()
  {
    if (!defined('YII_DEBUG') || !YII_DEBUG)
      return array(
        array(
          'ext.compressPage + index, group'
        ),
      );

    return array();
  }
}