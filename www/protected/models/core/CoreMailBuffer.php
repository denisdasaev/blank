<?php

/**
 * This is the model class for table "core_mail_buffer".
 *
 * The followings are the available columns in table 'core_mail_buffer':
 * @property integer $id
 * @property string $ts_add
 * @property string $ts_send
 * @property integer $priority
 * @property string $mail_to
 * @property string $mail_subj
 * @property string $mail_body
 * @property string $mail_headers
 * @property integer $sent
 * @property integer $send_retries
 * @property integer $client_id
 */
class CoreMailBuffer extends CoreActiveRecord
{
  public $client_login;

  public $ts_add_fr;

  public $ts_add_to;

  public $ts_send_fr;

  public $ts_send_to;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'core_mail_buffer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('mail_to, mail_subj, mail_body, mail_headers', 'required'),
			array('priority, sent, send_retries', 'numerical', 'integerOnly'=>true),
			array('mail_to, mail_subj', 'length', 'max'=>150),
			array('mail_headers', 'length', 'max'=>256),
			array('client_id', 'length', 'max'=>10),
			array('ts_add, ts_send', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ts_add, ts_send, priority, mail_to, mail_subj, mail_body, mail_headers, sent, send_retries, client_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
      'client'=>array(self::BELONGS_TO, 'CoreClient', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ts_add' => 'Создано',
			'ts_send' => 'Отправлено',
			'priority' => 'Приоритет',
			'mail_to' => 'Кому',
			'mail_subj' => 'Тема',
			'mail_body' => 'Текст',
			'mail_headers' => 'Заголовки',
			'sent' => 'Отправлено',
			'send_retries' => 'Попыток отправки',
			'client_id' => 'Пользователь',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
    $criteria->with = array('client');
		$criteria->compare('t.sent', $this->sent);
		$criteria->compare('t.priority', $this->priority);
		$criteria->compare('t.mail_to', $this->mail_to, true);
		$criteria->compare('t.mail_body', $this->mail_body, true);
		$criteria->compare('client.login', $this->client_login, true);
    if (!empty($this->ts_add_fr) && !empty($this->ts_add_to))
    {
      $date_fr = date('Y-m-d', strtotime($this->ts_add_fr));
      $date_to = date('Y-m-d', strtotime($this->ts_add_to));
      $criteria->condition .= ' AND t.ts_add BETWEEN STR_TO_DATE(\''.$date_fr.' 00:00:00\', \'%Y-%m-%d %H:%i:%s\')'.
                                               ' AND STR_TO_DATE(\''.$date_to.' 23:59:59\', \'%Y-%m-%d %H:%i:%s\')';
    }
    if (!empty($this->ts_send_fr) && !empty($this->ts_send_to))
    {
      $date_fr = date('Y-m-d', strtotime($this->ts_send_fr));
      $date_to = date('Y-m-d', strtotime($this->ts_send_to));
      $criteria->condition .= ' AND t.ts_send BETWEEN STR_TO_DATE(\''.$date_fr.' 00:00:00\', \'%Y-%m-%d %H:%i:%s\')'.
                                                ' AND STR_TO_DATE(\''.$date_to.' 23:59:59\', \'%Y-%m-%d %H:%i:%s\')';
    }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination'=>array(
        'pageVar'=>'page',
        'pageSize'=>Yii::app()->conf->get('SYSTEM/ADM_PAGER_SIZE'),
      ),
      'sort'=>array(
        'sortVar'=>'sort',
        'defaultOrder'=>'ts_add DESC',
        'attributes'=>array(
          'ts_add',
          'ts_send',
          'priority',
          'mail_to',
          'mail_subj',
          'mail_body',
          'mail_headers',
          'sent',
          'send_retries',
          'client.login',
        ),
      ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoreMailBuffer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}