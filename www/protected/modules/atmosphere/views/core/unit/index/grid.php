<?php /**
 * @var $this UnitController
 * @var $model CoreUnit
 * @var $rowOffset integer
 */

  $editDriver = 'AdminCore';
  $editMethod = 'unitEdit';
?>
<div class="col-md-2">
  <?php if ($this->accessLevel >= $this::ACCESS_EDIT) { ?>
    <button class="btn btn-primary btn-xs btn-block standard-add" data-driver="<?php echo $editDriver; ?>" data-method="<?php echo $editMethod; ?>">
      <span class="glyphicon glyphicon-plus"></span> Добавить раздел
    </button>
  <?php } ?>
  <button class="btn btn-primary btn-xs btn-block standard-filter"><span class="glyphicon glyphicon-filter"></span> Отфильтровать</button><br>
  <aside id="filters-group" style="display:none;">
    <div class="form-group">
      <label for="filter-group"><?php echo CoreUnit::model()->label('group_id'); ?></label>
      <select id="filter-group" name="filter[group_id]" class="form-control filter-control">
        <option value="">(все)</option>
        <?php echo CoreUnitGroup::listHtmlOptions((isset($_GET['filter']['group_id'])?$_GET['filter']['group_id']:null)); ?>
      </select>
    </div>
    <div class="form-group">
      <label for="filter-name"><?php echo CoreUnit::model()->label('name'); ?></label>
      <input id="filter-name" type="text" name="filter[name]" value="<?php echo (isset($_GET['filter']['name'])?$_GET['filter']['name']:''); ?>" class="form-control filter-control">
    </div>
    <div class="form-group">
      <label for="filter-controller"><?php echo CoreUnit::model()->label('controller'); ?></label>
      <input id="filter-controller" type="text" name="filter[controller]" value="<?php echo (isset($_GET['filter']['controller'])?$_GET['filter']['controller']:''); ?>" class="form-control filter-control">
    </div>
  </aside>
</div>
<div class="col-md-10">
  <?php
    $this->widget('zii.widgets.grid.CGridView', array(
      'id'=>str_replace('/', '-', $this->id).'-'.$this->action->id.'-grid',
      'htmlOptions'=>array('class'=>'grid-view table-responsive'),
      'dataProvider'=>$model->search(),
      'summaryText'=>'<small class="text-muted">Страница {page} из {pages}, записи {start} &mdash; {end} из {count}</small>',
      'emptyText'=>'Записей нет',
      'selectableRows'=>($this->accessLevel >= $this::ACCESS_EDIT?1:0),
      'selectionChanged'=>($this->accessLevel >= $this::ACCESS_EDIT?'function(id){rc("'.$editDriver.'","'.$editMethod.'",function(data){if(data!=""){$("#rubber-box").html(data);$("#modal-content").modal("show");}$(".table tbody tr.selected").removeClass("selected");},{"i_itm_id":$.fn.yiiGridView.getSelection(id)[0],"s_url":$.fn.yiiGridView.getUrl("'.str_replace('/', '-', $this->id).'-'.$this->action->id.'-grid")});}':''),
      'itemsCssClass'=>'table table-condensed'.($this->accessLevel >= $this::ACCESS_EDIT?' table-hover':''),
      'filterSelector'=>'{filter}, .filter-control',
      'showTableOnEmpty'=>false,
      'pagerCssClass'=>'pull-right',
      'pager'=>array(
        'id'=>str_replace('/', '-', $this->id).'-'.$this->action->id.'-grid-pager',
        'htmlOptions'=>array('class'=>'pagination pagination-sm'),
        'firstPageLabel'=>'<span class="glyphicon glyphicon-fast-backward"></span>',
        'lastPageLabel'=>'<span class="glyphicon glyphicon-fast-forward"></span>',
        'prevPageLabel'=>'<span class="glyphicon glyphicon-arrow-left"></span>',
        'nextPageLabel'=>'<span class="glyphicon glyphicon-arrow-right"></span>',
        'header'=>'',
        'internalPageCssClass'=>'',
        'selectedPageCssClass'=>'active',
      ),
      'columns'=>array(
        array(
          'header'=>'№',
          'type'=>'raw',
          'value'=>'$data->cellNumber($row+1+'.$rowOffset.')',
        ),
        array(
          'name'=>'group.name',
          'header'=>CoreUnit::model()->label('group_id'),
          'type'=>'raw',
          'htmlOptions'=>array('nowrap'=>''),
        ),
        array(
          'name'=>'sort',
        ),
        array(
          'name'=>'name',
          'htmlOptions'=>array('nowrap'=>''),
        ),
        array(
          'name'=>'descr',
        ),
        array(
          'name'=>'controller',
        ),
      ),
    ));
  ?>
</div>