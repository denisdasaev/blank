<?php

/**
 * This is the model class for table "core_client".
 *
 * The followings are the available columns in table 'core_client':
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $ts_reg
 * @property string $ts_act
 * @property string $token
 * @property integer $state
 */
class CoreClient extends CoreActiveRecord
{
  const STATE_NEW = 0;

  const STATE_VERIFIED = 1;

  public $admin_root;

  public $admin_group_id;

  public $ts_reg_fr;

  public $ts_reg_to;

  public $ts_act_fr;

  public $ts_act_to;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'core_client';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('login', 'required'),
			array('login', 'length', 'max'=>20),
			array('password', 'length', 'max'=>32),
			array('email', 'length', 'max'=>30),
			array('token', 'length', 'max'=>64),
			array('ts_reg', 'safe'),
      array('ts_reg', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false, 'on'=>'insert'),
//      array('ts_act', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false, 'on'=>'update'),
      array('state', 'length', 'max'=>3),
      // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, login, password, email, ts_reg, ts_act, token, state', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
      'admin'=>array(self::HAS_ONE, 'CoreAdmin', array('client_id'=>'id')),
        'admin_group'=>array(self::HAS_ONE, 'CoreAdminGroup', array('group_id'=>'id'), 'through'=>'admin'),
//          'rights'=>array(self::HAS_MANY, 'CoreAdminGroupRight', array('id'=>'admin_group_id'), 'through'=>'admin_group'),
//            'units'=>array(self::HAS_MANY, 'CoreUnit', array('unit_id'=>'id'), 'through'=>'rights'),
      'vericode'=>array(self::HAS_ONE, 'CoreClientVericode', array('client_id'=>'id'), 'joinType'=>'JOIN'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Логин',
			'password' => 'Пароль',
			'email' => 'Эл. почта',
			'ts_reg' => 'Регистрация',
			'ts_act' => 'Активность',
			'token' => 'Токен',
			'state' => 'Активирован',
		);
	}

	public function search($clientOnly = false)
	{
		$criteria=new CDbCriteria;
    $criteria->with = array('admin_group');
    $criteria->condition = 'admin.client_id IS'.($clientOnly?'':' NOT').' NULL';
    $criteria->compare('admin.root', $this->admin_root);
    $criteria->compare('t.login', $this->login, true);
    $criteria->compare('admin.group_id', $this->admin_group_id);
    $criteria->compare('t.email', $this->email, true);
    $criteria->compare('t.state', $this->state);
    if (!empty($this->ts_reg_fr) && !empty($this->ts_reg_to))
    {
      $date_fr = date('Y-m-d', strtotime($this->ts_reg_fr));
      $date_to = date('Y-m-d', strtotime($this->ts_reg_to));
      $criteria->condition .= ' AND t.ts_reg BETWEEN STR_TO_DATE(\''.$date_fr.' 00:00:00\', \'%Y-%m-%d %H:%i:%s\')'.
                                               ' AND STR_TO_DATE(\''.$date_to.' 23:59:59\', \'%Y-%m-%d %H:%i:%s\')';
    }
    if (!empty($this->ts_act_fr) && !empty($this->ts_act_to))
    {
      $date_fr = date('Y-m-d', strtotime($this->ts_act_fr));
      $date_to = date('Y-m-d', strtotime($this->ts_act_to));
      $criteria->condition .= ' AND t.ts_act BETWEEN STR_TO_DATE(\''.$date_fr.' 00:00:00\', \'%Y-%m-%d %H:%i:%s\')'.
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
        'defaultOrder'=>'login',
        'attributes'=>array(
          'admin.root',
          'login',
          'admin_group.name',
          'email',
          'ts_reg',
          'ts_act',
          'state',
        ),
      ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoreClient the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  protected function beforeDelete()
  {
    if (parent::beforeDelete())
    {
      CoreClientVericode::model()->deleteAll('client_id='.$this->id);
      CoreAdmin::model()->deleteAll('client_id='.$this->id);
      CoreMailBuffer::model()->updateAll(array('client_id'=>new CDbExpression('NULL')), 'client_id='.$this->id);

      return true;
    }

    return false;
  }

  public static function regVerify($code)
  {
    if (!empty($code) && $client = self::model()->with('vericode')->find('vericode.code=:code', array(':code'=>$code)))
    {
      $client->state = self::STATE_VERIFIED;
      $client->vericode->delete();
      $client->save();
      return $client;
    }

    return false;
  }

  public function cellRoot()
  {
    return ($this->admin && $this->admin->root?'<span class="glyphicon glyphicon-star"></span>':'');
  }
}