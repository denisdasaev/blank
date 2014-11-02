<?php

class UnitController extends CoreController
{
  public $unitName = 'Разделы';

  /**
   * Разделы.
   */
  public function actionIndex()
	{
    if (isset($_GET['del']) && $this->accessLevel >= self::ACCESS_DELETE
        && CoreUnit::model()->findByPk($_GET['del'])->delete())
      $this->redirect($this->createUrlWithGet('/'.$this->route));

    if (isset($_POST['edit']) && $this->accessLevel >= self::ACCESS_EDIT)
    {
      if (!isset($_POST['item_id']) || !$item = CoreUnit::model()->findByPk($_POST['item_id']))
        $item = new CoreUnit();
      if (empty($_POST['edit']['group_id']))
        $_POST['edit']['group_id'] = null;
      $item->setAttributes($_POST['edit']);
      $item->save();
      $this->redirect($this->createUrlWithGet('/'.$this->route));
    }

    $model = new CoreUnit('search');
    $model->unsetAttributes();
    if (isset($_GET['filter']))
      $model->attributes = $_GET['filter'];
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
        && CoreUnitGroup::model()->findByPk($_GET['del'])->delete())
      $this->redirect($this->createUrlWithGet('/'.$this->route));

    if (isset($_POST['edit']) && $this->accessLevel >= self::ACCESS_EDIT)
    {
      if (!isset($_POST['item_id']) || !$item = CoreUnitGroup::model()->findByPk($_POST['item_id']))
        $item = new CoreUnitGroup();
      $item->setAttributes($_POST['edit']);
      $item->save();
      $this->redirect($this->createUrlWithGet('/'.$this->route));
    }

    $model = new CoreUnitGroup('search');
    $model->unsetAttributes();
    if (isset($_GET['filter']))
      $model->attributes = $_GET['filter'];
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
          'ext.compressPage + index, group'
        ),
      );

    return array();
  }
}