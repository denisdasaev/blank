<?php /**
 * @var $this UserController
 * @var $model CoreClient
 * @var $rowOffset integer
 */

  $editDriver = 'AdminCore';
  $editMethod = 'userEdit';
?>

<div class="col-md-2">
  <?php if ($this->accessLevel >= $this::ACCESS_EDIT) { ?>
    <button class="btn btn-primary btn-xs btn-block standard-add" data-driver="<?php echo $editDriver; ?>" data-method="<?php echo $editMethod; ?>">
      <span class="glyphicon glyphicon-plus"></span> Добавить польз.
    </button>
  <?php } ?>
  <button class="btn btn-primary btn-xs btn-block standard-filter"><span class="glyphicon glyphicon-filter"></span> Отфильтровать</button><br>
  <aside id="filters-group" style="display:none;">
    <input type="hidden" name="filter[name]" value="" class="filter-control">
    <div class="form-group">
      <label for="filter-root"><?php echo CoreAdmin::model()->label('root'); ?></label>
      <select id="filter-root" name="filter[admin_root]" class="form-control filter-control">
        <option value="">(все)</option>
        <option value="1"<?php echo ((isset($_GET['filter']['admin_root']) && $_GET['filter']['admin_root']==1)?' selected':''); ?>>Да</option>
        <option value="0"<?php echo ((isset($_GET['filter']['admin_root']) && $_GET['filter']['admin_root']==0)?' selected':''); ?>>Нет</option>
      </select>
    </div>
    <div class="form-group">
      <label for="filter-login"><?php echo CoreClient::model()->label('login'); ?></label>
      <input id="filter-login" type="text" name="filter[login]" value="<?php echo (isset($_GET['filter']['login'])?$_GET['filter']['login']:''); ?>" class="form-control filter-control">
    </div>
    <div class="form-group">
      <label for="filter-group"><?php echo CoreAdmin::model()->label('group_id'); ?></label>
      <select id="filter-group" name="filter[admin_group_id]" class="form-control filter-control">
        <option value="">(все)</option>
        <?php echo CoreAdminGroup::listHtmlOptions((isset($_GET['filter']['admin_group_id'])?$_GET['filter']['admin_group_id']:null)); ?>
      </select>
    </div>
    <div class="form-group">
      <label for="filter-email"><?php echo CoreClient::model()->label('email'); ?></label>
      <input id="filter-email" type="text" name="filter[email]" value="<?php echo (isset($_GET['filter']['email'])?$_GET['filter']['email']:''); ?>" class="form-control filter-control">
    </div>
    <div class="form-group">
      <label for="filter-ts-reg"><?php echo CoreClient::model()->label('ts_reg'); ?></label>
      <input id="filter-ts-reg" type="text" name="filter[ts_reg_fr]" value="<?php echo (isset($_GET['filter']['ts_reg_fr'])?$_GET['filter']['ts_reg_fr']:''); ?>" class="form-control filter-control datepkr" placeholder="с...">
    </div>
    <div class="form-group">
      <input type="text" name="filter[ts_reg_to]" value="<?php echo (isset($_GET['filter']['ts_reg_to'])?$_GET['filter']['ts_reg_to']:''); ?>" class="form-control filter-control datepkr" placeholder="по...">
    </div>
    <div class="form-group">
      <label for="filter-ts-act"><?php echo CoreClient::model()->label('ts_act'); ?></label>
      <input id="filter-ts-act" type="text" name="filter[ts_act_fr]" value="<?php echo (isset($_GET['filter']['ts_act_fr'])?$_GET['filter']['ts_act_fr']:''); ?>" class="form-control filter-control datepkr" placeholder="с...">
    </div>
    <div class="form-group">
      <input type="text" name="filter[ts_act_to]" value="<?php echo (isset($_GET['filter']['ts_act_to'])?$_GET['filter']['ts_act_to']:''); ?>" class="form-control filter-control datepkr" placeholder="по...">
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
          'name'=>'admin.root',
          'type'=>'raw',
          'value'=>'$data->cellRoot()',
        ),
        'login',
        array(
          'name'=>'admin_group.name',
          'header'=>CoreAdmin::model()->label('group_id'),
        ),
        'email',
        'ts_reg',
        'ts_act',
      ),
    ));
  ?>
</div>
<?php $this->renderPartial('application.views.layouts.datepicker-settings', array()); ?>