<?php

class ConfigController extends CoreController
{
  public $unitName = 'Параметры сайта';

  /**
   * Параметры.
   */
  public function actionIndex()
	{
    if (isset($_GET['del']) && $this->accessLevel >= self::ACCESS_DELETE && defined('YII_DEBUG')
        && YII_DEBUG && CoreConfig::model()->findByPk($_GET['del'])->delete())
      $this->redirect($this->createUrlWithGet('/'.$this->route, (isset($_GET['g'])?array('g'=>$_GET['g']):array())));

    if (isset($_POST['edit']) && $this->accessLevel >= self::ACCESS_EDIT)
    {
      if (!isset($_POST['item_id']) || !$item = CoreConfig::model()->findByPk($_POST['item_id']))
        $item = new CoreConfig();
      if (isset($_POST['edit']['value']))
        $_POST['edit']['value'] = nl2br($_POST['edit']['value']);
      if (defined('YII_DEBUG') && YII_DEBUG)
      {
        if (isset($_POST['edit_value_def'], $_POST['edit']['value']) && $_POST['edit_value_def'])
          $_POST['edit']['value_default'] = $_POST['edit']['value'];
        $item->setAttributes($_POST['edit']);
      }
      else
        $item->value = $_POST['edit']['value'];

      if (isset($_FILES['edit_file']) && $_FILES['edit_file']['error'] == 0 && $_FILES['edit_file']['size']
          && in_array($_FILES['edit_file']['type'], array('image/jpeg', 'image/png'))
          && is_uploaded_file($_FILES['edit_file']['tmp_name']))
      {
        $ext = '.jpg';
        if ($_FILES['edit_file']['type'] == 'image/png')
          $ext = '.png';
        if (move_uploaded_file($_FILES['edit_file']['tmp_name'], CoreConfig::FILE_PATH.$item->id.$ext))
          $item->value = $item->id.$ext;
        else
          $this->errMsg = 'Не удалось переместить загруженный файл "'.$_FILES['edit_file']['tmp_name'].'" в "'.CoreConfig::FILE_PATH.$item->id.$ext.'"!';
      }

      $item->save();
      $this->redirect($this->createUrlWithGet('/'.$this->route, (isset($_GET['g'])?array('g'=>$_GET['g']):array())));
    }

    $groups = CoreConfigGroup::model()->findAll(array('order'=>'name'));
    if (isset($_GET['g']) && (int)$_GET['g'] > 0
        && CoreConfigGroup::model()->exists('id=:id', array(':id'=>$_GET['g'])))
      $activeGroupId = (int)$_GET['g'];
    else
      $activeGroupId = ($groups?$groups[0]->id:null);
    $activeGroup = ($activeGroupId?CoreConfigGroup::model()->findByPk($activeGroupId):null);
    $model = new CoreConfig('search');
    $model->group_id = $activeGroupId;
		$this->render('index', array(
      'model'=>$model,
      'groups'=>$groups,
      'activeGroup'=>$activeGroup,
    ));
	}

  /**
   * Группы.
   */
  public function actionGroup()
  {
    if (isset($_GET['del']) && $this->accessLevel >= self::ACCESS_DELETE)
      CoreConfigGroup::model()->findByPk($_GET['del'])->delete();

    if (isset($_POST['edit']) && $this->accessLevel >= self::ACCESS_EDIT)
    {
      if (!isset($_POST['item_id']) || !$item = CoreConfigGroup::model()->findByPk($_POST['item_id']))
        $item = new CoreConfigGroup();
      $item->setAttributes($_POST['edit']);
      $item->save();
    }

    $this->redirect($this->createUrlWithGet('index', (isset($_GET['g']) && !isset($_GET['del'])?array('g'=>$_GET['g']):array())));
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