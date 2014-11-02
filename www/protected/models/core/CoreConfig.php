<?php

/**
 * @property integer $id
 * @property integer $group_id
 * @property string $param
 * @property integer $type
 * @property string $value
 * @property string $value_default
 * @property string $label
 * @property string $descr
 */
class CoreConfig extends CoreActiveRecord
{
  /**
   * Тип параметра: строка.
   */
  const TYPE_STRING = 0;

  /**
   * Тип параметра: многострочный текст.
   */
  const TYPE_TEXT = 1;

  /**
   * Тип параметра: эл. почта.
   */
  const TYPE_EMAIL = 2;

  /**
   * Тип параметра: число.
   */
  const TYPE_NUMBER = 3;

  /**
   * Тип параметра: галочка.
   */
  const TYPE_BOOL = 4;

  /**
   * Тип параметра: jpeg-файл.
   */
  const TYPE_FILE_JPEG = 5;

  /**
   * Тип параметра: png-файл.
   */
  const TYPE_FILE_PNG = 6;

  /**
   * Тип параметра по умолчанию.
   */
  const TYPE_DEFAULT = self::TYPE_STRING;

  /**
   * Путь хранения файлов.
   */
  const FILE_PATH = 'img/conf/';

  /**
   * @var array
   */
  public static $typeLabels = array(
    self::TYPE_STRING => 'Строка',
    self::TYPE_TEXT => 'Многострочный текст',
    self::TYPE_EMAIL => 'Эл. почта',
    self::TYPE_NUMBER => 'Число',
    self::TYPE_BOOL => 'Булево значение',
    self::TYPE_FILE_JPEG => 'Файл JPEG',
    self::TYPE_FILE_PNG => 'Файл PNG',
  );

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'core_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('group_id, param', 'required'),
			array('group_id, type', 'numerical', 'integerOnly'=>true),
			array('param', 'length', 'max'=>20),
			array('label', 'length', 'max'=>40),
			array('descr', 'length', 'max'=>256),
			array('value, value_default', 'safe'),
			// The following rule is used by search().
			array('id, group_id, param, type, value, value_default, label, descr', 'safe', 'on'=>'search'),
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
			'group_id' => 'Группа',
			'param' => 'Параметр',
			'type' => 'Тип',
			'value' => 'Значение',
			'value_default' => 'Знач. по умлч.',
			'label' => 'Название',
			'descr' => 'Описание',

      'label_descr' => 'Параметр/Описание',
		);
	}

	/**
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('group_id', $this->group_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination'=>false,
      'sort'=>array(
        'sortVar'=>'sort',
        'defaultOrder'=>'t.label',
//        'attributes'=>array(
//          'name',
//        ),
      ),
		));
	}

	/**
	 * @param string $className active record class name.
	 * @return CoreConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  /**
   * @return bool
   */
  protected function beforeDelete()
  {
    if (parent::beforeDelete())
    {
      if (in_array($this->type, array(self::TYPE_FILE_JPEG, self::TYPE_FILE_PNG)) && file_exists(self::FILE_PATH.$this->value))
        unlink(self::FILE_PATH.$this->value);

      return true;
    }

    return false;
  }

  /**
   * @return string
   */
  public function cellLabelDescr()
  {
    return '<span id="item-'.$this->id.'"></span>'.((defined('YII_DEBUG') && YII_DEBUG)?'<code>'.$this->param.'</code><br>':'').'<strong>'.$this->label.'</strong><br><small class="tiny">'.$this->descr.'</small>';
  }

  /**
   * @return string
   */
  public function cellType()
  {
    return self::$typeLabels[$this->type];
  }

  /**
   * @return string
   */
  public function cellValue()
  {
    if ($this->type == self::TYPE_BOOL)
      $result = ($this->value?'Да':'Нет');
    elseif (in_array($this->type, array(self::TYPE_FILE_JPEG, self::TYPE_FILE_PNG)) && $this->value)
      $result = '<img src="/'.self::FILE_PATH.$this->value.'" height="70">';
    else
      $result = '<em>'.strip_tags($this->value).'</em>';

    return $result;
  }

  /**
   * @return string
   */
  public function cellValueDefault()
  {
    if ($this->type == self::TYPE_BOOL)
      $result = ($this->value_default?'Да':'Нет');
    elseif (in_array($this->type, array(self::TYPE_FILE_JPEG, self::TYPE_FILE_PNG)) && $this->value_default)
      $result = '<img src="/'.self::FILE_PATH.$this->value_default.'" height="70">';
    else
      $result = '<em>'.strip_tags($this->value_default).'</em>';

    return $result;
  }
}