<?php
class CoreAjaxAdminClient extends CoreAjax
{
  private $accessController = 'client';

  public function edit($data = array())
  {
    $this->accessControl($this->accessController, CoreController::ACCESS_EDIT);

    $params = array();
    if (isset($data['i_itm_id']) && $item = CoreClient::model()->with('admin')->findByPk($data['i_itm_id']))
      if (!isset($item->admin))
        $params['item'] = $item;

    $params['mfRouteParams'] = (isset($data['s_url'])?$this->stdRouteParams($data['s_url']):array());
    $params['mfDelete'] = ($this->clientAccessLevel($this->accessController) >= CoreController::ACCESS_DELETE);

    return Yii::app()->controller->renderPartial(ADMIN_MODULE.'.views.'.$this->accessController.'.index.edit', $params, true);
  }
}