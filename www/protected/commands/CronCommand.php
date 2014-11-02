<?php
class CronCommand extends CConsoleCommand
{
  /**
   * Отправка писем.
   *
   * @return bool
   */
  private function sendMail()
  {
    //  Приоритет 0 (самый низкий) - вероятность 3%
    //  Приоритет 1 (низкий) - вероятность 5%
    //  Приоритет 2 (обычный) - вероятность 11%
    //  Приоритет 3 (высокий) - вероятность 21%
    //  Приоритет 4 (самый высокий) - вероятность 60%

    $n = rand(1, 100);

    if ($n < 61) $priority = Mailer::PRIOR_HIGHER;
    elseif ($n < 82) $priority = Mailer::PRIOR_HIGH;
    elseif ($n < 93) $priority = Mailer::PRIOR_NORMAL;
    elseif ($n < 98) $priority = Mailer::PRIOR_LOW;
    else $priority = Mailer::PRIOR_LOWER;

    $crit = new CDbCriteria();
    $crit->condition = 'priority>=:priority AND sent=0';
    $crit->order = 'priority, ts_add';
    $crit->limit = '1';
    $crit->params = array(':priority'=>$priority);

    if (!$mailbuf = CoreMailBuffer::model()->find($crit))
    {
      $crit->condition = 'priority<:priority AND sent=0';
      if (!$mailbuf = CoreMailBuffer::model()->find($crit))
        return true;
    }

    if (mail($mailbuf->mail_to, $mailbuf->mail_subj, $mailbuf->mail_body, $mailbuf->mail_headers))
    {
      $mailbuf->ts_send = date('Y-m-d H:i:s');
      $mailbuf->sent = 1;
    }
    $mailbuf->send_retries++;

    return $mailbuf->save();
  }

  /**
   * Удаление из базы клиентов, не подтвердивших регистрацию.
   *
   * @return bool
   */
  private function clientClean()
  {
    $lifeTime = 60*60*24;

    $clients = CoreClient::model()->findAll(
      't.state=0'.
      ' AND t.ts_reg<STR_TO_DATE(\''.date('Y-m-d H:i:s', (time()-$lifeTime)).'\', \'%Y-%m-%d %H:%i:%s\')'
    );

    foreach ($clients as $client)
    {
      CoreClientVericode::model()->deleteByPk($client->id);
      $client->delete();
    }

    return true;
  }

  public function run($args = '')
  {
    $err_level = 0;

    if (!$this->sendMail())
      $err_level = 1;

    if (!$this->clientClean())
      $err_level = 2;

  	return $err_level;
  }
}