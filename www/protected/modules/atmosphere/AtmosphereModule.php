<?php

class AtmosphereModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			$this->getId().'.models.*',
			$this->getId().'.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if (parent::beforeControllerAction($controller, $action))
		{
      if (Yii::app()->user->isGuest && ($controller->id != 'main' || $action->id != 'login'))
        Yii::app()->request->redirect(Yii::app()->createUrl($this->getId().'/main/login'));
      elseif (!Yii::app()->user->isGuest && $controller->id == 'main' && $action->id == 'login')
        Yii::app()->request->redirect(Yii::app()->createUrl($this->getId().'/main/index'));

			return true;
		}
		else
			return false;
	}
}