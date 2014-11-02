<?php
class CoreAjaxAdminCore extends CoreAjax
{
  public function configGroupEdit($data = array())
  {
    $this->accessRootOnly();

    $params = array();
    if (isset($data['i_itm_id']) && $item = CoreConfigGroup::model()->findByPk($data['i_itm_id']))
      $params['item'] = $item;

    if (isset($data['i_g']) && $data['i_g'] && CoreConfigGroup::model()->exists('id=:id', array(':id'=>$data['i_g'])))
      $params['g'] = $data['i_g'];

    $params['mfDelete'] = (defined('YII_DEBUG') && YII_DEBUG);

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.core.config.group.edit', $params, true);
  }

  public function configParamEdit($data = array())
  {
    $this->accessRootOnly();

    $params = array();
    if (isset($data['i_itm_id']) && $item = CoreConfig::model()->findByPk($data['i_itm_id']))
      $params['item'] = $item;

    if (isset($data['i_g']) && $data['i_g'] && CoreConfigGroup::model()->exists('id=:id', array(':id'=>$data['i_g'])))
      $params['g'] = $data['i_g'];

    $params['mfDelete'] = (defined('YII_DEBUG') && YII_DEBUG);

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.core.config.index.edit', $params, true);
  }

  public function deletePic($data = array())
  {
    $this->accessRootOnly();

    if (isset($data['i_itm_id']) && $item = CoreConfig::model()->findByPk($data['i_itm_id']))
    {
      if (file_exists('img/conf/'.$item->id.'.jpg'))
        unlink('img/conf/'.$item->id.'.jpg');
      if (file_exists('img/conf/'.$item->id.'.png'))
        unlink('img/conf/'.$item->id.'.png');
      $item->value = '';
      $item->save();
      return '<input id="edit-file" type="file" name="edit_file" class="form-control" accept="image/jpeg">'.
             '<input type="hidden" name="edit[value]" value="">';
    }

    return '';
  }

  public function mailTplEdit($data = array())
  {
    $this->accessRootOnly();

    $params = array();
    if (isset($data['i_itm_id']) && $item = CoreMailTpl::model()->findByPk($data['i_itm_id']))
      $params['item'] = $item;

    $params['mfRouteParams'] = (isset($data['s_url'])?$this->stdRouteParams($data['s_url']):array());
    $params['mfDelete'] = true;

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.core.mailtpl.index.edit', $params, true);
  }

  public function mailTplGroupEdit($data = array())
  {
    $this->accessRootOnly();

    $params = array();
    if (isset($data['i_itm_id']) && $item = CoreMailTplGroup::model()->findByPk($data['i_itm_id']))
      $params['item'] = $item;

    $params['mfRouteParams'] = (isset($data['s_url'])?$this->stdRouteParams($data['s_url']):array());
    $params['mfDelete'] = true;

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.core.mailtpl.group.edit', $params, true);
  }

  public function mailBufferEdit($data = array())
  {
    $this->accessRootOnly();

    $params = array();
    if (isset($data['i_itm_id']) && $item = CoreMailBuffer::model()->findByPk($data['i_itm_id']))
      $params['item'] = $item;

    $params['mfRouteParams'] = (isset($data['s_url'])?$this->stdRouteParams($data['s_url']):array());
    $params['mfDelete'] = true;

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.core.mailbuf.index.edit', $params, true);
  }

  public function unitEdit($data = array())
  {
    $this->accessRootOnly();

    $params = array();
    if (isset($data['i_itm_id']) && $item = CoreUnit::model()->findByPk($data['i_itm_id']))
      $params['item'] = $item;

    $params['mfRouteParams'] = (isset($data['s_url'])?$this->stdRouteParams($data['s_url']):array());
    $params['mfDelete'] = true;

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.core.unit.index.edit', $params, true);
  }

  public function unitGroupEdit($data = array())
  {
    $this->accessRootOnly();

    $params = array();
    if (isset($data['i_itm_id']) && $item = CoreUnitGroup::model()->findByPk($data['i_itm_id']))
      $params['item'] = $item;

    $params['mfRouteParams'] = (isset($data['s_url'])?$this->stdRouteParams($data['s_url']):array());
    $params['mfDelete'] = true;

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.core.unit.group.edit', $params, true);
  }

  public function userEdit($data = array())
  {
    $this->accessRootOnly();

    $params = array();
    $params['mfDelete'] = false;
    if (isset($data['i_itm_id']) && $item = CoreClient::model()->findByPk($data['i_itm_id']))
    {
      $params['item'] = $item;
      $params['mfDelete'] = $this->client->id != $item->id;
    }

    $params['mfRouteParams'] = (isset($data['s_url'])?$this->stdRouteParams($data['s_url']):array());

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.core.user.index.edit', $params, true);
  }

  public function userGroupEdit($data = array())
  {
    $this->accessRootOnly();

    $params = array();
    if (isset($data['i_itm_id']) && $item = CoreAdminGroup::model()->findByPk($data['i_itm_id']))
      $params['item'] = $item;

    $params['mfRouteParams'] = (isset($data['s_url'])?$this->stdRouteParams($data['s_url']):array());
    $params['mfDelete'] = true;

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.core.user.group.edit', $params, true);
  }

  public function userGroupRightsEdit($data = array())
  {
    $this->accessRootOnly();

    $params = $rights = array();

    if (isset($data['i_itm_id']) && $item = CoreAdminGroup::model()->with('rights')->findByPk($data['i_itm_id']))
      $params['item'] = $item;
    else $this->renderAlertModal('Отсутствует указанная группа!');

    $params['units'] = CoreUnit::model()->with('group')->findAll(array('order'=>'group.sort, t.sort'));
    foreach ($params['units'] as $unit)
      if ($unit->name != '')
        $rights[$unit->id] = CoreController::ACCESS_DENIED;

    foreach ($item->rights as $right)
      if (isset($rights[$right->unit_id]))
      {
        $rights[$right->unit_id] = CoreController::ACCESS_VIEW;
        if ($right->right_delete)
          $rights[$right->unit_id] = CoreController::ACCESS_DELETE;
        elseif ($right->right_edit)
          $rights[$right->unit_id] = CoreController::ACCESS_EDIT;
      }
    $params['rights'] = $rights;
    $params['mfRouteParams'] = (isset($data['s_url'])?$this->stdRouteParams($data['s_url']):array());

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.core.user.rights.edit', $params, true);
  }
}