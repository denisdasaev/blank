<?php

/**
 * This is the model class for table "core_client_vericode".
 *
 * The followings are the available columns in table 'core_client_vericode':
 * @property integer $client_id
 * @property string $code
 */
class CoreClientVericode extends CoreActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'core_client_vericode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('client_id, code', 'required'),
      array('client_id', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('client_id, code', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'client_id' => 'Клиент',
			'code' => 'Код подтверждения',
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
	 * @return CoreClientVericode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}