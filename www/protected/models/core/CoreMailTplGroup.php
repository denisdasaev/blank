<?php

/**
 * This is the model class for table "core_mail_tpl_group".
 *
 * The followings are the available columns in table 'core_mail_tpl_group':
 * @property integer $id
 * @property string $name
 */
class CoreMailTplGroup extends CoreActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'core_mail_tpl_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
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
		$criteria->compare('name', $this->name, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination'=>array(
        'pageVar'=>'page',
        'pageSize'=>Yii::app()->conf->get('SYSTEM/ADM_PAGER_SIZE'),
      ),
      'sort'=>array(
        'sortVar'=>'sort',
//        'defaultOrder'=>'t.name',
//        'attributes'=>array(
//          'name',
//        ),
      ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoreMailTplGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  protected function beforeDelete()
  {
    if (parent::beforeDelete())
    {
      CoreMailTpl::model()->updateAll(array('group_id'=>new CDbExpression('NULL')), 'group_id='.$this->id);

      return true;
    }

    return false;
  }

  public static function listHtmlOptions($selected = null)
  {
    $groups = self::model()->findAll(array('order'=>'name'));

    $result = '';
    foreach ($groups as $group)
      $result .= '<option value="'.$group->id.'"'.(($selected && $selected == $group->id)?' selected':'').'>'.$group->name.'</option>';

    return $result;
  }
}