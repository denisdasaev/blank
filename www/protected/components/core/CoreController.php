<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class CoreController extends CController
{
  /**
   * Уровень доступа к разделу (контроллеру): запрещено.
   */
  const ACCESS_DENIED = 0;

  /**
   * Уровень доступа к разделу (контроллеру): просмотр.
   */
  const ACCESS_VIEW = 1;

  /**
   * Уровень доступа к разделу (контроллеру): просмотр, изменение.
   */
  const ACCESS_EDIT = 2;

  /**
   * Уровень доступа к разделу (контроллеру): просмотр, изменение, удаление.
   */
  const ACCESS_DELETE = 3;

  /**
   * Уровень доступа к разделу (контроллеру): без ограничений.
   */
  const ACCESS_FULL = self::ACCESS_DELETE;

  /**
   * Сообщение об ошибке.
   *
   * @var string
   */
  public $errMsg = '';

  /**
   * Название модуля.
   *
   * @var string
   */
  public $unitName = 'default';

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/public';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();

	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

  /**
   * Уровень доступа к текущему разделу(контроллеру).
   * @var int
   */
  public $accessLevel = 0;

  /**
   * Значения уровней доступа.
   * @var array
   */
  public $accessLevelLabel = array(
    0=>'Запрещено',
    1=>'Просмотр',
    2=>'Просмотр, изменение',
    3=>'Просмотр, изменение, удаление',
  );

  /**
   * Контроль доступа к разделам.
   *
   * @param CAction $action
   * @return bool
   */
  public function beforeAction($action)
  {
    if ($action->controller->module && $action->controller->module->id == ADMIN_MODULE)
      $this->layout = '//layouts/admin';

    if (!Yii::app()->user->isGuest && Yii::app()->user)
      CoreClient::model()->updateByPk(Yii::app()->user->id, array('ts_act'=>new CDbExpression('NOW()')));

    if (!$action->controller->module || $action->controller->module->id != ADMIN_MODULE
        || Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && Yii::app()->user->root)
        || in_array($action->controller->id, array('ajax', 'main', 'config', 'mailtpl', 'mailbuf'))
    )
    {
      $this->accessLevel = 3;
      return true;
    }

    $this->accessLevel = 0; // запрещено

    if (Yii::app()->user->admin && Yii::app()->user->rights)
      foreach (Yii::app()->user->rights as $right)
        if ($right->unit->controller == $action->controller->id)
        {
          if (!$right->right_edit)
            $this->accessLevel = 1; // просмотр
          elseif ($right->right_edit && !$right->right_delete)
            $this->accessLevel = 2; // изменение
          elseif ($right->right_delete)
            $this->accessLevel = 3; // удаление
          break;
        }

    if ($this->accessLevel == 0)
    {
      $this->redirect($this->createUrl('main/index'));
      return false;
    }

    return true;
  }

  public function accessLabel()
  {
    return $this->accessLevelLabel[$this->accessLevel];
  }

  public function createUrlWithGet($route, $params=array(), $ampersand='&')
  {
    $stdParams = array();
    if (isset($_GET['filter']) && !isset($params['filter']))
      $stdParams['filter'] = $_GET['filter'];
    if (isset($_GET['page']) && !isset($params['page']))
      $stdParams['page'] = $_GET['page'];
    if (isset($_GET['sort']) && !isset($params['sort']))
      $stdParams['sort'] = $_GET['sort'];

    return parent::createUrl($route, array_merge($stdParams, $params), $ampersand);
  }
}