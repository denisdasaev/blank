<?php

class ClientController extends CoreController
{
  public $unitName = 'Клиенты';

  /**
   * Клиенты.
   */
  public function actionIndex()
	{
    if (isset($_GET['del']) && $this->accessLevel >= self::ACCESS_DELETE
        && CoreClient::model()->findByPk($_GET['del'])->delete())
      $this->redirect($this->createUrlWithGet('/'.$this->route));

    if (isset($_POST['edit'], $_POST['edit_pw']) && $this->accessLevel >= self::ACCESS_EDIT)
    {
      if (!isset($_POST['item_id']) || !$item = CoreClient::model()->findByPk($_POST['item_id']))
      {
        $item = new CoreClient();
        if (!empty($_POST['edit']['password']) && !empty($_POST['edit_pw'])
            && $_POST['edit']['password'] === $_POST['edit_pw'])
          $_POST['edit']['password'] = md5($_POST['edit']['password']);
        else
          $this->errMsg = 'Пароли не заполнены или различны!';
      }
      elseif (!empty($_POST['edit']['password']) || !empty($_POST['edit_pw'])) // edit pass
      {
        if ($_POST['edit']['password'] === $_POST['edit_pw'])
          $_POST['edit']['password'] = md5($_POST['edit']['password']);
        else
          $this->errMsg = 'Пароли не заполнены или различны!';
      }
      if (empty($err_msg))
      {
        $item->state = 0;
        $item->setAttributes($_POST['edit']);
        $item->save();
      }
      $this->redirect($this->createUrlWithGet('/'.$this->route));
    }

    $model = new CoreClient('search');
    $model->unsetAttributes();
    if (isset($_GET['filter']))
      $model->attributes = $_GET['filter'];
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
   * Фильтры.
   *
   * @return array
   */
  public function filters()
  {
    if (!defined('YII_DEBUG') || !YII_DEBUG)
      return array(
        array(
          'ext.compressPage + index'
        ),
      );

    return array();
  }
}