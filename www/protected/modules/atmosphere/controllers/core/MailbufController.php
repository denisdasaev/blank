<?php

class MailbufController extends CoreController
{
  public $unitName = 'Буфер рассылки писем';

  /**
   * Буфер: текущая очередь.
   */
  public function actionIndex()
	{
    if (isset($_GET['del']) && $this->accessLevel >= self::ACCESS_DELETE
        && CoreMailBuffer::model()->findByPk($_GET['del'])->delete())
      $this->redirect($this->createUrlWithGet('/'.$this->route));

    if (isset($_POST['edit'], $_POST['item_id']) && $this->accessLevel >= self::ACCESS_EDIT
        && $item = CoreMailBuffer::model()->findByPk($_POST['item_id']))
    {
      $item->attributes = $_POST['edit'];
      $item->save();
      $this->redirect($this->createUrlWithGet('/'.$this->route));
    }

    $model = new CoreMailBuffer('search');
    $model->unsetAttributes();
    if (isset($_GET['filter']))
      $model->attributes = $_GET['filter'];
    if (isset($_GET['filter']['client_login']))
      $model->client_login = $_GET['filter']['client_login'];
    if (isset($_GET['filter']['ts_add_fr']))
      $model->ts_add_fr = $_GET['filter']['ts_add_fr'];
    if (isset($_GET['filter']['ts_add_to']))
      $model->ts_add_to = $_GET['filter']['ts_add_to'];
    $model->sent = 0;
		$this->render('index', array(
      'model'=>$model,
      'rowOffset'=>((isset($_GET['page']) && $_GET['page'] > 0)?(((int)$_GET['page']-1)*Yii::app()->conf->get('SYSTEM/ADM_PAGER_SIZE')):0),
    ));
	}

  /**
   * Буфер: отправленные.
   */
  public function actionArchive()
  {
    if (isset($_GET['del']) && $this->accessLevel >= self::ACCESS_DELETE
        && CoreMailBuffer::model()->findByPk($_GET['del'])->delete())
      $this->redirect($this->createUrlWithGet('/'.$this->route));

    if (isset($_POST['edit'], $_POST['item_id']) && $this->accessLevel >= self::ACCESS_EDIT
        && $item = CoreMailBuffer::model()->findByPk($_POST['item_id']))
    {
      $item->attributes = $_POST['edit'];
      $item->save();
      $this->redirect($this->createUrlWithGet('/'.$this->route));
    }

    $model = new CoreMailBuffer('search');
    $model->unsetAttributes();
    if (isset($_GET['filter']))
      $model->attributes = $_GET['filter'];
    if (isset($_GET['filter']['client_login']))
      $model->client_login = $_GET['filter']['client_login'];
    if (isset($_GET['filter']['ts_add_fr']))
      $model->ts_add_fr = $_GET['filter']['ts_add_fr'];
    if (isset($_GET['filter']['ts_add_to']))
      $model->ts_add_to = $_GET['filter']['ts_add_to'];
    if (isset($_GET['filter']['ts_send_fr']))
      $model->ts_send_fr = $_GET['filter']['ts_send_fr'];
    if (isset($_GET['filter']['ts_send_to']))
      $model->ts_send_to = $_GET['filter']['ts_send_to'];
    $model->sent = 1;
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
          'ext.compressPage + index, archive'
        ),
      );

    return array();
  }
}