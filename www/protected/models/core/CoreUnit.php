<?php

/**
 * This is the model class for table "core_unit".
 *
 * The followings are the available columns in table 'core_unit':
 * @property integer $id
 * @property integer $group_id
 * @property integer $sort
 * @property string $name
 * @property string $descr
 * @property string $controller
 */
class CoreUnit extends CoreActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'core_unit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
      array('name, controller', 'required'),
      array('group_id, sort', 'numerical', 'integerOnly'=>true),
			array('name, controller', 'length', 'max'=>20),
      array('descr', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, group_id, sort, descr, name, controller', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
      'group'=>array(self::BELONGS_TO, 'CoreUnitGroup', 'group_id'), // 'joinType'=>'JOIN'
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
			'sort' => 'Порядок',
			'name' => 'Название',
			'descr' => 'Описание',
			'controller' => 'Контроллер',
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
		$criteria->compare('t.controller', $this->controller, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination'=>array(
        'pageVar'=>'page',
        'pageSize'=>Yii::app()->conf->get('SYSTEM/ADM_PAGER_SIZE'),
      ),
      'sort'=>array(
        'sortVar'=>'sort',
        'defaultOrder'=>'group.sort, group.name, t.sort, t.name',
        'attributes'=>array(
          'group.name',
          'sort',
          'name',
          'descr',
          'controller',
        ),
      ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoreUnit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public static function menuItems()
  {
    $items = array();

    if (!Yii::app()->user->isGuest && Yii::app()->user->admin)
    {
      if (Yii::app()->user->root)
      {
        // группированные элементы
        $core_groups = CoreUnitGroup::model()->findAll(array('order'=>'sort, name'));
        foreach ($core_groups as $core_group)
        {
          $core_sub_units = self::model()->findAll(array('condition'=>'group_id='.$core_group->id, 'order'=>'sort, name'));
          $sub_items = array();
          foreach ($core_sub_units as $core_sub_unit)
          {
            $item = array(
              'label'=>$core_sub_unit->name,
              'linkOptions'=>array('tabindex'=>'-1'),
            );
            if (empty($core_sub_unit->name))
              $item['itemOptions'] = array('class'=>'divider');
            else
              $item['url'] = array($core_sub_unit->controller.'/index');

            $sub_items[] = $item;
          }
          if ($sub_items)
          {
            $items[] = array(
              'label'=>$core_group->name.' <b class="caret"></b>',
              'url'=>'#',
              'itemOptions'=>array('class'=>'dropdown'),
              'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown', 'tabindex'=>'-1'),
              'items'=>$sub_items,
            );
          }
        }

        // корневые элементы
        $core_units = self::model()->findAll(array('condition'=>'group_id IS NULL', 'order'=>'sort, name'));
        foreach ($core_units as $core_unit)
        {
          $items[] = array(
            'label'=>$core_unit->name,
            'url'=>array($core_unit->controller.'/index'),
            'linkOptions'=>array('tabindex'=>'-1'),
          );
        }
      }
      else
      {
        $lastGroup = '';
        $isSubItem = false;
        $sub_items = array();
        foreach (Yii::app()->user->rights as $right)
        {
          $isSubItem = ($right->unit->group?true:false);

          $curGroup = ($isSubItem?$right->unit->group->name:'');

          if ($lastGroup != $curGroup)
          {
            if ($sub_items)
            {
              $items[] = array(
                'label'=>$lastGroup.' <b class="caret"></b>',
                'url'=>'#',
                'itemOptions'=>array('class'=>'dropdown'),
                'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown', 'tabindex'=>'-1'),
                'items'=>$sub_items,
              );
              $sub_items = array();
            }

            $lastGroup = $curGroup;
          }

          $item = array(
            'label'=>$right->unit->name,
            'linkOptions'=>array('tabindex'=>'-1'),
          );
          if (empty($right->unit->name))
            $item['itemOptions'] = array('class'=>'divider');
          else
            $item['url'] = array($right->unit->controller.'/index');

          if ($isSubItem)
            $sub_items[] = $item;
          else
            $items[] = $item;
        }
        if ($sub_items)
          $items[] = array(
            'label'=>$curGroup.' <b class="caret"></b>',
            'url'=>'#',
            'itemOptions'=>array('class'=>'dropdown'),
            'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown', 'tabindex'=>'-1'),
            'items'=>$sub_items,
          );
      }
    }

    return $items;
  }
}