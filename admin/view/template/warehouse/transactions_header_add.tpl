<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_header; ?></h3>
      </div>
      
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-transaction-header" class="form-horizontal">
            <div class="tab-content">
              <div class="form-group" style="display:none;">
                <label class="col-sm-2 control-label" for="input-transaction_type"><?php echo $entry_transaction_type; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="transaction_type" value="Warehouse" placeholder="<?php echo $entry_transaction_type; ?>" id="input-transaction_type" class="form-control" />
                  <?php if ($error_transaction_type) { ?>
                  <div class="text-danger"><?php echo $error_transaction_type; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-transaction_no"><?php echo $entry_transaction_no; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="transaction_no" value="<?php echo $transaction_no; ?>" placeholder="<?php echo $entry_transaction_no; ?>" id="input-transaction_no" class="form-control" readonly="readonly" />
                  <?php if ($error_transaction_no) { ?>
                  <div class="text-danger"><?php echo $error_transaction_no; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-remarks"><?php echo $entry_remarks; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="remarks" value="<?php echo $remarks; ?>" placeholder="<?php echo $entry_remarks; ?>" id="input-remarks" class="form-control" />
                  <?php if ($error_remarks) { ?>
                  <div class="text-danger"><?php echo $error_remarks; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-received_datetime"><span data-toggle="tooltip" title="This is the expected date/time the stock is expected reach the warehouse. You can schedule this for a maximum of 14 days in advance."><?php echo $entry_received_datetime; ?></span></label>
                <div class="col-sm-10">
                  <div class="input-group date">
                    <input type="text" name="received_datetime" value="<?php echo $received_datetime; ?>" placeholder="<?php echo $entry_received_datetime; ?>" id="input-received_datetime" class="form-control" data-date-format="YYYY-MM-DD H:mm:SS" data-date-minDate="<?php echo date('Y-m-d H:i:s');?>" data-date-maxDate="<?php echo date('Y-m-d H:i:s', strtotime('+14 day', time()));?>" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                  </div>
                  <script type="text/javascript">
                  $(document).ready(function(){
                  	$('[data-toggle="tooltip"]').tooltip(); 
                  });
                  </script>
                  <style type="text/css">
				  .datepicker .disabled {
					color: #F00 !important;  
				  }
				  </style>
                  <?php if ($error_received_datetime) { ?>
                  <div class="text-danger"><?php echo $error_received_datetime; ?></div>
                  <?php } ?>
                </div>                
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-num_of_bin"><?php echo $entry_num_of_bin; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="num_of_bin" value="<?php echo $num_of_bin; ?>" placeholder="<?php echo $entry_num_of_bin; ?>" id="input-num_of_bin" class="form-control" onkeypress="return strictlyNumberOnly(event, true);" />
                  <?php if ($error_num_of_bin) { ?>
                  <div class="text-danger"><?php echo $error_num_of_bin; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php /*
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <option value="0"><?php echo $text_pending; ?></option>
                  </select>
                  <?php if ($error_status) { ?>
                  <div class="text-danger"><?php echo $error_status; ?></div>
                  <?php } ?>
                </div>
              </div>*/ ?>
              <input type="hidden" name="status" value="0" id="input-status" />
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-ecowarehouse_sync_status"><?php echo $entry_ecowarehouse_sync_status; ?></label>
                <div class="col-sm-10">
                  <select name="ecowarehouse_sync_status" id="input-ecowarehouse_sync_status" class="form-control">
                  	<option value="-" selected="selected">-</option>
                  </select>
                  <?php if ($error_ecowarehouse_sync_status) { ?>
                  <div class="text-danger"><?php echo $error_ecowarehouse_sync_status; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-warehouseeco_sync_status"><?php echo $entry_warehouseeco_sync_status; ?></label>
                <div class="col-sm-10">
                  <select name="warehouseeco_sync_status" id="input-warehouseeco_sync_status" class="form-control">
                  	<option value="-" selected="selected">-</option>
                  </select>
                  <?php if ($error_warehouseeco_sync_status) { ?>
                  <div class="text-danger"><?php echo $error_warehouseeco_sync_status; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <button type="submit" id="button-save" class="btn btn-primary pull-right"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
$('.time').datetimepicker({
	pickDate: false
});
$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
<?php echo $footer; ?>