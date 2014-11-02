<?php
class CoreMailer extends CComponent
{
  /**
   * Приоритет письма на отправку: самый низкий.
   * Будет отправлен после всех высших приоритетов.
   */
  const PRIOR_LOWER = 0;

  /**
   * Приоритет письма на отправку: низкий.
   * Будет отправлен после всех высших приоритетов.
   */
  const PRIOR_LOW = 1;

  /**
   * Приоритет письма на отправку: обычный.
   * Будет отправлен после всех высших приоритетов.
   */
  const PRIOR_NORMAL = 2;

  /**
   * Приоритет письма на отправку: высокий.
   * Будет отправлен после самого высокого приоритета.
   */
  const PRIOR_HIGH = 3;

  /**
   * Приоритет письма на отправку: самый высокий.
   * Будет отправлен первым, но в порядке очереди себе подобных.
   */
  const PRIOR_HIGHER = 4;

  /**
   * Приоритет письма на отправку по умолчанию.
   * Параметр read-only.
   * @var int
   */
  public static $defaultPriority = self::PRIOR_NORMAL;

  /**
   * Имя в поле "От кого".
   * @var string
   */
  private $fromName;

  /**
   * Электронный адрес отправителя письма.
   * @var string
   */
  private $fromEmail;

  /**
   * Имя в поле "Кому".
   * @var string
   */
  private $toName;

  /**
   * Электронный адрес получателя письма.
   * @var string
   */
  private $toEmail;

  /**
   * Тема письма.
   * @var string
   */
  private $mailSubj;

  /**
   * Тело письма.
   * @var string
   */
  private $mailBody;

  /**
   * Приоритет отправки письма.
   * @var int
   */
  private $priority;

  /**
   * Конструктор объекта.
   *
   * @param string $toName
   * @param string $toEmail
   * @param string $mailSubj
   * @param string $mailBody
   */
  private function __construct($toName = '', $toEmail = '', $mailSubj = '', $mailBody = '')
  {
    $this->fromName = Yii::app()->name;
    $this->fromEmail = 'no-reply';
    $this->toName = $toName;
    $this->toEmail = $toEmail;
    $this->mailSubj = $mailSubj;
    $this->mailBody = $mailBody;
    $this->priority = self::$defaultPriority;
  }

  /**
   * Статично создаёт и возвращает объект.
   *
   * @param string $toName
   * @param string $toEmail
   * @param string $mailSubj
   * @param string $mailBody
   * @return Mailer
   */
  public static function in($toName = '', $toEmail = '', $mailSubj = '', $mailBody = '')
  {
    return new self($toName, $toEmail, $mailSubj, $mailBody);
  }

  /**
   * Загрузка шаблона письма из базы данных с подстановкой параметров, переданных в массив
   * $params. Формат параметров в шаблоне: {param_name}
   *
   * @param string $template_name
   * @param array $params
   * @return $this|bool
   */
  public function useTemplate($template_name, $params = array())
  {
    if ($template_name == '' || !is_array($params)
      || !($template = CoreMailTpl::model()->find('name=:name', array(':name'=>$template_name))))
      return false;

    if (!isset($params['site']))
      $params['site'] = '<a href="http://'.Yii::app()->request->serverName.'">'.Yii::app()->name.'</a>';

    $this->mailSubj = $template->subj;
    $this->mailBody = $template->body;
    $this->priority = $template->default_priority;

    foreach ($params as $key => $val)
    {
      $this->mailSubj = str_replace('{'.$key.'}', $val, $this->mailSubj);
      $this->mailBody = str_replace('{'.$key.'}', $val, $this->mailBody);
    }

    return $this;
  }

  /**
   * "Отправка" письма.
   * Если $immediately не разрешен, то письмо отправляется в буфер отправки письма и встает в
   * общую очередь на отсылку. В случае установки флага $immediately, скрипт выполнит одну попытку
   * немедленной отправки письма (в обход общей очереди), но если попытка не удастся, письмо
   * будет отправлено в буфер отправки в общую очередь. На выходе получим модель письма в буфере
   * или false в случае неудачи.
   *
   * @param bool $immediately попытаться отправить немедленно (приоритет роли не играет)
   * @return bool|MailBuf модель или false
   */
  public function send($immediately = false)
  {
    if (empty($this->toEmail))
      return false;

    $to = $this->toEmail;
    if (!empty($this->toName))
      $to = '=?UTF-8?B?'.base64_encode($this->toName).'?= <'.$to.'>';

    $subj = '=?UTF-8?B?'.base64_encode($this->mailSubj).'?=';

    $body = '<html>'."\r\n".'<body>'."\r\n".$this->mailBody."\r\n".'</body>'."\r\n".'</html>';

    $headers =
      'From: =?UTF-8?B?'.base64_encode($this->fromName).'?= <'.$this->fromEmail.'@'.Yii::app()->request->serverName.">\r\n".
        'Reply-To: '.$this->fromEmail.'@'.Yii::app()->request->serverName."\r\n".
        'MIME-Version: 1.0'."\r\n".
        'Content-type: text/html; charset=UTF-8';

    $mailBuf = new CoreMailBuffer();
    $mailBuf->ts_add = date('Y-m-d H:i:s');
    $mailBuf->ts_send = '0000-00-00 00:00:00';
    $mailBuf->priority = $this->priority;
    $mailBuf->mail_to = $to;
    $mailBuf->mail_subj = $subj;
    $mailBuf->mail_body = $body;
    $mailBuf->mail_headers = $headers;
    $mailBuf->sent = 0;
    $mailBuf->send_retries = 0;
    $mailBuf->client_id = (Yii::app()->user->isGuest?null:Yii::app()->user->id);

    if ($immediately && mail($to, $subj, $body, $headers))
    {
      $mailBuf->ts_send = date('Y-m-d H:i:s');
      $mailBuf->sent = 1;
      $mailBuf->send_retries++;
    }

    if ($mailBuf->save())
      return $mailBuf;

    return false;
  }

  /**
   * Названия приоритетов.
   *
   * @param null|string $key
   * @return array|string
   */
  public static function priorityLabels($key = null)
  {
    $result = array(
      self::PRIOR_LOWER => 'Самый низкий',
      self::PRIOR_LOW => 'Низкий',
      self::PRIOR_NORMAL => 'Обычный',
      self::PRIOR_HIGH => 'Высокий',
      self::PRIOR_HIGHER => 'Самый высокий',
    );
    if (!is_null($key) && isset($result[$key]))
      $result = $result[$key];
    return $result;
  }

  public static function priorityHtmlOptions($selected = null)
  {
    $items = self::priorityLabels();

    $result = '';
    foreach ($items as $k => $v)
      $result .= '<option value="'.$k.'"'.(($selected && $selected == $k)?' selected':'').'>'.$v.'</option>';

    return $result;
  }

  /**
   * Возвращает свойство defaultPriority.
   *
   * @return int
   */
  public function getDefaultPriority()
  {
    return $this->defaultPriority;
  }

  /**
   * Установщик свойства fromName.
   * Поддержка текучего интерфейса.
   *
   * @param string $fromName
   * @return Mailer
   */
  public function setFromName($fromName)
  {
    $this->fromName = $fromName;
    return $this;
  }

  /**
   * Возвращает свойство fromName.
   *
   * @return string
   */
  public function getFromName()
  {
    return $this->fromName;
  }

  /**
   * Установщик свойства fromEmail.
   * Поддержка текучего интерфейса.
   *
   * @param string $fromEmail
   * @return Mailer
   */
  public function setFromEmail($fromEmail)
  {
    $this->fromEmail = $fromEmail;
    return $this;
  }

  /**
   * Возвращает свойство fromEmail.
   *
   * @return string
   */
  public function getFromEmail()
  {
    return $this->fromEmail;
  }

  /**
   * Установщик свойства toName.
   * Поддержка текучего интерфейса.
   *
   * @param string $toName
   * @return Mailer
   */
  public function setToName($toName)
  {
    $this->toName = $toName;
    return $this;
  }

  /**
   * Возвращает свойство toName.
   *
   * @return string
   */
  public function getToName()
  {
    return $this->toName;
  }

  /**
   * Установщик свойства toEmail.
   * Поддержка текучего интерфейса.
   *
   * @param string $toEmail
   * @return Mailer
   */
  public function setToEmail($toEmail)
  {
    $this->toEmail = $toEmail;
    return $this;
  }

  /**
   * Возвращает свойство toEmail.
   *
   * @return string
   */
  public function getToEmail()
  {
    return $this->toEmail;
  }

  /**
   * Установщик свойства mailSubj.
   * Поддержка текучего интерфейса.
   *
   * @param string $mailSubj
   * @return Mailer
   */
  public function setMailSubj($mailSubj)
  {
    $this->mailSubj = $mailSubj;
    return $this;
  }

  /**
   * Возвращает свойство mailSubj.
   *
   * @return string
   */
  public function getMailSubj()
  {
    return $this->mailSubj;
  }

  /**
   * Установщик свойства mailBody.
   * Поддержка текучего интерфейса.
   *
   * @param string $mailBody
   * @return Mailer
   */
  public function setMailBody($mailBody)
  {
    $this->mailBody = $mailBody;
    return $this;
  }

  /**
   * Возвращает свойство mailBody.
   *
   * @return string
   */
  public function getMailBody()
  {
    return $this->mailBody;
  }

  /**
   * Установщик свойства priority.
   * Поддержка текучего интерфейса.
   *
   * @param int $priority
   * @return Mailer
   */
  public function setPriority($priority)
  {
    if (!in_array($priority, array(
      self::PRIOR_LOWER,
      self::PRIOR_LOW,
      self::PRIOR_NORMAL,
      self::PRIOR_HIGH,
      self::PRIOR_HIGHER,
    )))
      $priority = self::PRIOR_NORMAL;

    $this->priority = $priority;
    return $this;
  }

  /**
   * Возвращает свойство priority.
   *
   * @return int
   */
  public function getPriority()
  {
    return $this->priority;
  }
}