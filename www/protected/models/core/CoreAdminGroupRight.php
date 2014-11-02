<?php

/**
 * This is the model class for table "core_admin_group_right".
 *
 * The followings are the available columns in table 'core_admin_group_right':
 * @property integer $id
 * @property integer $admin_group_id
 * @property integer $unit_id
 * @property integer $right_edit
 * @property integer $right_delete
 */
class CoreAdminGroupRight extends CoreActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'core_admin_group_right';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('admin_group_id, unit_id', 'required'),
			array('admin_group_id, unit_id, right_edit, right_delete', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, admin_group_id, unit_id, right_edit, right_delete', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
      'unit'=>array(self::BELONGS_TO, 'CoreUnit', 'unit_id'),//, 'joinType'=>'JOIN'
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'admin_group_id' => 'Адм. группа',
			'unit_id' => 'Раздел',
			'right_edit' => 'Изменение',
			'right_delete' => 'Удаление',
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
	 * @return CoreAdminGroupRight the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}