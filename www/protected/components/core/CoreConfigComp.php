<?php
class CoreConfigComp extends CApplicationComponent
{
  public $cache = 0;

  public $dependency = null;

  protected $data = array();

  protected function getDbConnection()
  {
    return ($this->cache?Yii::app()->db->cache($this->cache, $this->dependency):Yii::app()->db);
  }

  public function reset()
  {
    $db = $this->getDbConnection();
    $items = $db->createCommand('SELECT core_config.*, core_config_group.name AS root FROM core_config '.
        'JOIN core_config_group ON (core_config.group_id=core_config_group.id)')->queryAll();
    $this->data = array();
    foreach ($items as $item)
      if ($item['root'] && $item['param'])
        $this->data[$item['root']][$item['param']] = ($item['value']===''?$item['value_default']:$item['value']);
  }

  public function init()
  {
    $this->reset();
    parent::init();
  }

  public function get($key)
  {
    if (empty($key))
      return null;

    $key = explode('/', $key);

    if (!is_array($key) || count($key) != 2 || $key[0] === '' || $key[1] === '' || !isset($this->data[$key[0]][$key[1]]))
      return null;

    return $this->data[$key[0]][$key[1]];
  }
}