<?php
class CoreActiveRecord extends CActiveRecord
{
  /**
   * Вспомогательный метод для интерпертаторов PHP ниже версии 5.4.0, не
   * поддерживающих форму:
   *
   *   echo Client::model()->attributeLabels()['login'];
   *
   * То же самое получаем, используя такой код:
   *
   *   echo Client::model()->label('login');
   *
   * Если версия интерпертатора PHP >= 5.4.0, то данный метод не актуален,
   * а все модели следует наследовать напрямую от CActiveRecord!
   *
   * @param $attribute название атрибута
   * @return string лэйбл атрибута, возвращаемый из массива attributeLabels()
   */
  public function label($attribute)
	{
    $labels = $this->attributeLabels();

    if (!empty($attribute) && isset($labels[$attribute]))
		  return $labels[$attribute];

    return ucfirst($attribute);
	}

  /**
   * Вывод порядкового номера записи с меткой для позиционирования.
   *
   * @param $rowOffset порядковый номер
   * @return string
   */
  public function cellNumber($rowOffset)
  {
    return '<span id="item-'.$this->id.'">'.$rowOffset.'</span>';
  }
}