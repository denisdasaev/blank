<?php /**
 * @var $this ConfigController
 * @var $model CoreConfig
 * @var $activeGroup CoreConfigGroup|null
 */

  $editDriver = 'AdminCore';
  $editMethod = 'configParamEdit';
?>
<div class="col-lg-12">
  <?php
    $this->widget('zii.widgets.grid.CGridView', array(
      'id'=>str_replace('/', '-', $this->id).'-'.$this->action->id.'-grid',
      'htmlOptions'=>array('class'=>'grid-view table-responsive'),
      'dataProvider'=>$model->search(),
      'summaryText'=>'',
      'emptyText'=>'Записей нет',
      'enableSorting'=>false,
      'selectableRows'=>($this->accessLevel >= $this::ACCESS_EDIT?1:0),
      'selectionChanged'=>($this->accessLevel >= $this::ACCESS_EDIT?'function(id){rc("'.$editDriver.'","'.$editMethod.'",function(data){if(data!=""){$("#rubber-box").html(data);$("#modal-content").modal("show");}$(".table tbody tr.selected").removeClass("selected");},{"i_itm_id":$.fn.yiiGridView.getSelection(id)[0],"i_g":'.($activeGroup?$activeGroup->id:'""').'});}':''),
      'itemsCssClass'=>'table table-condensed'.($this->accessLevel >= $this::ACCESS_EDIT?' table-hover':''),
      'columns'=>((defined('YII_DEBUG') && YII_DEBUG)?array(
        array(
          'name'=>'label_descr',
          'type'=>'raw',
          'value'=>'$data->cellLabelDescr()',
        ),
        array(
          'name'=>'type',
          'type'=>'raw',
          'value'=>'$data->cellType()',
        ),
        array(
          'name'=>'value',
          'type'=>'raw',
          'value'=>'$data->cellValue()',
        ),
        array(
          'name'=>'value_default',
          'type'=>'raw',
          'value'=>'$data->cellValueDefault()',
        ),
      ):array(
        array(
          'name'=>'label_descr',
          'type'=>'raw',
          'value'=>'$data->cellLabelDescr()',
        ),
        array(
          'name'=>'value',
          'type'=>'raw',
          'value'=>'$data->cellValue()',
        ),
      )),
    ));
  ?>
</div>