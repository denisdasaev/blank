<?php

class CoreAjax extends CComponent
{
  /**
   * Здесь задается имя контролера (раздела) уровень прав которого следует
   * приравнять к текущему ajax-запросу.
   *
   * @var string
   */
  private $accessController = '';

  /**
   * Вызываемый метод.
   *
   * @var string
   */
  private $method;

  /**
   * Параметры, передаваемые вызываемому методу.
   *
   * @var array
   */
  private $params;

  /**
   * Объект клиента для авторизованных вызовов методов.
   *
   * @var CoreClient
   */
  protected $client;

  /**
   * Номер ошибки.
   *
   * @var int
   */
  private $errorCode = 0;

  /**
   * Строка состояния ошибки.
   *
   * @var string
   */
  private $errorMessage = '';

  /**
   * Конструктор объекта.
   *
   * @param string $method
   * @param array $params
   */
  public function __construct($method = '', $params = array())
  {
    $this->method = $method;
    $this->params = $params;
    $this->client = null;
  }

  /**
   * Массив имён методов, доступных для анонимных вызовов. Если метод существует
   * в (суб)классе, но не указан в этом массиве, то доступ к нему имеют только
   * авторизованные клиенты.
   *
   * @return array
   */
  protected function anonymousMethods()
  {
    return array(
    );
  }

  /**
   * Возвращает массив стандартных (системных) GET параметров:
   *   filter(array)  - примененные к таблице фильтры,
   *   page(string)   - текущая страница таблицы,
   *   sort(string)   - выбранная сортировка таблицы.
   *
   * @param string $url
   * @return array
   */
  protected function stdRouteParams($url)
  {
    if (!empty($url) && strpos($url, '?') !== false)
    {
      $arr = array();
      parse_str(substr($url, strpos($url, '?')+1), $arr);
      foreach($arr as $name => $value)
        if (!in_array($name, array('filter', 'sort', 'page')))
          unset($arr[$name]);

      return $arr;
    }

    return array();
  }

  /**
   * Главная точка входа для Ajax-запросов, полученных контроллером и перенаправленных им сюда.
   *
   * Параметры в передаваемом массиве:
   *   drv  - имя драйвера, которому будет передано управление
   *   proc - имя метода, который содержится в указанном драйвере
   *   prms - массив параметров, который будет передан в метод
   *   ctkn - клиентский токен для вызова авторизованых методов
   *
   * @param array $request
   * @return bool|object
   */
  public static function run($request = array())
  {
    $class = __CLASS__;
    if (isset($request['drv']) && !empty($request['drv'])) $class .= $request['drv'];

    if (!isset($request['proc']) || empty($request['proc'])) return false;
    else $method = $request['proc'];

    if (!class_exists($class) || !is_subclass_of($class, 'CoreAjax') || !method_exists($class, $method))
      return false;

    if (!isset($request['prms']) || empty($request['prms']) || !is_array($request['prms'])) $params = array();
    else $params = $request['prms'];

    if (!isset($request['ctkn']) || empty($request['ctkn'])) $cToken = '';
    else $cToken = $request['ctkn'];

    $ajax = new $class($method, $params);

    if (!empty($cToken)) // авторизованный запрос
    {
      if (empty($cToken) || strlen($cToken) != 64)
        return $ajax->riseError(2, 'Token is incorrect');

      if (!$client = CoreClient::model()->with('admin_group.rights.unit')->find('t.token=:token', array(':token'=>$cToken)))
        return $ajax->riseError(3, 'Token not found');

      if ($cToken !== md5($client->id.$client->login).md5($client->login))
        return $ajax->riseError(2, 'Token is incorrect');

      $ajax->client = $client;
    }
    else // анонимный запрос
    {
      // проверка анонимного доступа к методу
      if (!in_array($method, $ajax->anonymousMethods()))
        return $ajax->riseError(1, 'Anonymous call for not anonymous method');
    }

    set_time_limit(0);
    echo $ajax->validateInputParams($params)->$method($params);

    return $ajax;
  }

  /**
   * Валидация параметров на принадлежность к скалярным типам.
   *
   * @param $data array
   * @return $this object
   */
  public function validateInputParams(&$data)
	{
		foreach ($data as $key => $val)
      if (mb_substr($key, 1, 1) == '_') // параметр с префиксом типа переменной (если префикса нет, то параметр не проверяется)
      {
        $varType = strtolower(mb_substr($key, 0, 1, 'utf-8'));

        switch ($varType)
        {
          case 'i': // int
            $data[$key] = (int) $val;
            break;

          case 'b': // bool
            $data[$key] = (int) $val;
            if (!in_array($data[$key], array(0, 1)))
              $data[$key] = 0;
            break;

          case 's': // string
          default:
            break;
        }
      }

    return $this;
	}

  /**
   * Устанавливает состояние ошибки.
   *
   * @param $code
   * @param $message
   * @return $this
   */
  public function riseError($code, $message)
  {
    $this->errorCode = (int) $code;
    $this->errorMessage = $message;

    return $this;
  }

  /**
   * Вывод модального окна с сообщением.
   * Возможные типы:
   *     danger  - ошибка (красное окно)
   *     warning - внимание (жёлтое)
   *     info    - информация (голубое)
   *     success - успех (зелёное)
   *
   * @param string $message
   * @param string $head
   * @param string $type
   * @param bool $closeButton
   * @return string
   */
  public function renderAlertModal($message, $head = '', $type = 'danger', $closeButton = true)
  {
    return '<div id="modal-content" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">'.
             '<div class="modal-dialog">'.
               '<div class="modal-content">'.
                 '<div class="alert alert-'.$type.'" style="margin-bottom:0;">'.
                   ($closeButton?'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>':'').
                   (empty($head)?'':'<strong>'.$head.'</strong><br>').$message.
                 '</div>'.
               '</div>'.
             '</div>'.
           '</div>';
  }

  /**
   * Проверка текущего пользователя на наличие права root.
   *
   * @return bool
   */
  public function clientIsRoot()
  {
    return (isset($this->client->admin->root) && $this->client->admin->root);
  }

  /**
   * Возвращает уровень доступа к указанному разделу для текущего клиента.
   *
   * @param string $controllerId
   * @return int
   */
  public function clientAccessLevel($controllerId)
  {
    if ($this->clientIsRoot())
      return CoreController::ACCESS_FULL;

    if (!empty($controllerId) && isset($this->client->admin_group->rights))
      foreach ($this->client->admin_group->rights as $right)
        if ($right->unit->controller == $controllerId)
        {
          if (!$right->right_edit)
            return CoreController::ACCESS_VIEW;
          elseif ($right->right_edit && !$right->right_delete)
            return CoreController::ACCESS_EDIT;
          elseif ($right->right_delete)
            return CoreController::ACCESS_DELETE; // удаление
        }

    return CoreController::ACCESS_DENIED;
  }

  /**
   * Проверка текущего пользователя на наличие права root. Если такого права нет, то
   * дальнейшее выполнение программы блокируется с отправкой соответствующего сообщения.
   */
  public function accessRootOnly()
  {
    if (!$this->clientIsRoot())
    {
      echo $this->renderAlertModal('Вы не можете управлять данными в этом разделе.', 'Ошибка!');
      Yii::app()->end();
    }
  }

  /**
   * Проверка текущего пользователя на наличие соответствующих прав. Если права нет, то
   * дальнейшее выполнение программы блокируется с отправкой соответствующего сообщения.
   *
   * @param string $controllerId
   * @param int $accessLevel
   */
  public function accessControl($controllerId, $accessLevel)
  {
    if (empty($controllerId) || empty($accessLevel) || $this->clientAccessLevel($controllerId) < $accessLevel)
    {
      echo $this->renderAlertModal('Вы не можете управлять данными в этом разделе.', 'Ошибка!');
      Yii::app()->end();
    }
  }

  /**
   * Возвращает номер ошибки.
   *
   * @return int
   */
  public function getErrorCode()
  {
    return $this->errorCode;
  }

  /**
   * Возвращает строку состояния ошибки.
   *
   * @return string
   */
  public function getErrorMessage()
  {
    return $this->errorMessage;
  }
}