<?php

/**
 * This is the model class for table "core_mail_tpl".
 *
 * The followings are the available columns in table 'core_mail_tpl':
 * @property integer $id
 * @property integer $group_id
 * @property string $name
 * @property string $subj
 * @property string $body
 * @property integer $default_priority
 */
class CoreMailTpl extends CoreActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'core_mail_tpl';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('group_id, name, body', 'required'),
			array('default_priority', 'numerical', 'integerOnly'=>true),
			array('group_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>20),
			array('subj', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, group_id, name, subj, body, default_priority', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
      'group'=>array(self::BELONGS_TO, 'CoreMailTplGroup', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'group_id' => 'Группа',
			'name' => 'Название',
			'subj' => 'Тема письма',
			'body' => 'Текст письма',
			'default_priority' => 'Приоритет',
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
    $criteria->with = array('group');
		$criteria->compare('t.group_id', $this->group_id);
		$criteria->compare('t.name', $this->name, true);
		$criteria->compare('t.subj', $this->subj, true);
		$criteria->compare('t.body', $this->body, true);
		$criteria->compare('t.default_priority', $this->default_priority);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination'=>array(
        'pageVar'=>'page',
        'pageSize'=>Yii::app()->conf->get('SYSTEM/ADM_PAGER_SIZE'),
      ),
      'sort'=>array(
        'sortVar'=>'sort',
        'defaultOrder'=>'group.name, t.name',
        'attributes'=>array(
          'group.name',
          'name',
          'descr',
          'subj',
          'body',
          'default_priority',
        ),
      ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoreMailTpl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}