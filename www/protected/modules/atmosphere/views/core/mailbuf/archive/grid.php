<?php /**
 * @var $this MailbufController
 * @var $model CoreMailBuffer
 * @var $rowOffset integer
 */

  $editDriver = 'AdminCore';
  $editMethod = 'mailBufferEdit';
?>
<div class="col-md-2">
  <button class="btn btn-primary btn-xs btn-block standard-filter"><span class="glyphicon glyphicon-filter"></span> Отфильтровать</button><br>
  <aside id="filters-group" style="display:none;">
    <input type="hidden" name="filter[name]" value="" class="filter-control">
    <div class="form-group">
      <label for="filter-ts-add"><?php echo CoreMailBuffer::model()->label('ts_add'); ?></label>
      <input id="filter-ts-add" type="text" name="filter[ts_add_fr]" value="<?php echo (isset($_GET['filter']['ts_add_fr'])?$_GET['filter']['ts_add_fr']:''); ?>" class="form-control filter-control datepkr" placeholder="с...">
    </div>
    <div class="form-group">
      <input type="text" name="filter[ts_add_to]" value="<?php echo (isset($_GET['filter']['ts_add_to'])?$_GET['filter']['ts_add_to']:''); ?>" class="form-control filter-control datepkr" placeholder="по...">
    </div>
    <div class="form-group">
      <label for="filter-ts-send"><?php echo CoreMailBuffer::model()->label('ts_send'); ?></label>
      <input id="filter-ts-send" type="text" name="filter[ts_send_fr]" value="<?php echo (isset($_GET['filter']['ts_send_fr'])?$_GET['filter']['ts_send_fr']:''); ?>" class="form-control filter-control datepkr" placeholder="с...">
    </div>
    <div class="form-group">
      <input type="text" name="filter[ts_send_to]" value="<?php echo (isset($_GET['filter']['ts_send_to'])?$_GET['filter']['ts_send_to']:''); ?>" class="form-control filter-control datepkr" placeholder="по...">
    </div>
    <div class="form-group">
      <label for="filter-priority"><?php echo CoreMailBuffer::model()->label('priority'); ?></label>
      <select id="filter-priority" name="filter[priority]" class="form-control filter-control">
        <option value="">(все)</option>
        <?php echo CoreMailer::priorityHtmlOptions((isset($_GET['filter']['priority'])?$_GET['filter']['priority']:null)); ?>
      </select>
    </div>
    <div class="form-group">
      <label for="filter-mail-to"><?php echo CoreMailBuffer::model()->label('mail_to'); ?></label>
      <input id="filter-mail-to" type="text" name="filter[mail_to]" value="<?php echo (isset($_GET['filter']['mail_to'])?$_GET['filter']['mail_to']:''); ?>" class="form-control filter-control">
    </div>
    <div class="form-group">
      <label for="filter-mail-body"><?php echo CoreMailBuffer::model()->label('mail_body'); ?></label>
      <input id="filter-mail-body" type="text" name="filter[mail_body]" value="<?php echo (isset($_GET['filter']['mail_body'])?$_GET['filter']['mail_body']:''); ?>" class="form-control filter-control">
    </div>
    <div class="form-group">
      <label for="filter-client-login"><?php echo CoreMailBuffer::model()->label('client_id'); ?></label>
      <input id="filter-client-login" type="text" name="filter[client_login]" value="<?php echo (isset($_GET['filter']['client_login'])?$_GET['filter']['client_login']:''); ?>" class="form-control filter-control">
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
      'emptyText'=>'Записей нет!',
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
          'name'=>'ts_add',
          'value'=>'date("H:i:s d-m-Y", strtotime($data->ts_add));',
        ),
        array(
          'name'=>'ts_send',
          'type'=>'raw',
          'value'=>'date("H:i:s d-m-Y", strtotime($data->ts_send))." <span class=\'glyphicon glyphicon-info-sign\' title=\'Спустя ".Tools::timeDifference(strtotime($data->ts_add), strtotime($data->ts_send))."\'></span>";',
        ),
        array(
          'name'=>'priority',
          'value'=>'CoreMailer::priorityLabels($data->priority);',
        ),
        'mail_to',
        array(
          'name'=>'mail_body',
          'type'=>'raw',
          'value'=>'Tools::strLimit(strip_tags($data->mail_body));',
        ),
        'send_retries',
        array(
          'name'=>'client.login',
          'header'=>'Пользователь',
        ),
      ),
    ));
  ?>
</div>
<?php $this->renderPartial('application.views.layouts.datepicker-settings', array()); ?>