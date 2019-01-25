<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      	<?php /*<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>*/ ?>
        <?php if($transaction_no!="" && $ecowarehouse_sync_status == "-" && $warehouseeco_sync_status == "-") { ?>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-tran').submit() : false;"><i class="fa fa-trash-o"></i></button>
        <?php } ?>
        <?php /*
        <button type="button" id="button-sync" form="form-transaction-header" formaction="<?php echo $sync; ?>" data-toggle="tooltip" title="<?php echo $sync_upload_receiving_list; ?>" class="btn btn-info"><?php echo $sync_upload_receiving_list; ?></button>
        <button type="button" id="button-sync2" form="form-transaction-header" formaction="<?php echo $sync2; ?>" data-toggle="tooltip" title="<?php echo $sync_download_receiving_progress; ?>" class="btn btn-info"><?php echo $sync_download_receiving_progress; ?></button>
        */ ?>
      </div>
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
                  <input type="text" name="transaction_type" value="<?php echo $transaction_type; ?>" placeholder="<?php echo $entry_transaction_type; ?>" id="input-transaction_type" class="form-control" readonly="readonly" />
                  <?php if ($error_transaction_type) { ?>
                  <div class="text-danger"><?php echo $error_transaction_type; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-transaction_no"><?php echo $entry_transaction_date; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="transaction_date" value="<?php echo $date_added; ?>" placeholder="<?php echo $entry_transaction_date; ?>" id="input-transaction_date" class="form-control" readonly="readonly" />
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
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-actual_num_of_bin"><?php echo $entry_actual_num_of_bin; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="actual_num_of_bin" value="<?php echo $actual_num_of_bin; ?>" placeholder="<?php echo $entry_actual_num_of_bin; ?>" id="input-actual_num_of_bin" class="form-control" onkeypress="return strictlyNumberOnly(event, true);" readonly="readonly" />
                  <?php if ($error_actual_num_of_bin) { ?>
                  <div class="text-danger"><?php echo $error_actual_num_of_bin; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php /*
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status == "1") { ?>
                    	<option value="1" selected="selected"><?php echo $text_completed; ?></option>
                    	<option value="0"><?php echo $text_pending; ?></option>
                    <?php } else { ?>
                    	<option value="1"><?php echo $text_completed; ?></option>
                    	<option value="0" selected="selected"><?php echo $text_pending; ?></option>
                    <?php } ?>
                  </select>
                  <?php if ($error_status) { ?>
                  <div class="text-danger"><?php echo $error_status; ?></div>
                  <?php } ?>
                </div>
              </div>*/ ?>
              <div class="form-group required">
                <input type="hidden" name="status" value="0" id="input-status" />
                <label class="col-sm-2 control-label" for="input-ecowarehouse_sync_status"><?php echo $entry_ecowarehouse_sync_status; ?></label>
                <div class="col-sm-10">
                  <select name="ecowarehouse_sync_status" id="input-ecowarehouse_sync_status" class="form-control" disabled="disabled">
                    <?php if ($ecowarehouse_sync_status == "-") { ?>
                    	<option value="-" selected="selected">-</option>
                    	<option value="Completed"><?php echo $text_completed; ?></option>
                    	<option value="Pending"><?php echo $text_pending; ?></option>
                    <?php } else if ($ecowarehouse_sync_status == "Completed") { ?>
                    	<option value="-">-</option>
                    	<option value="Completed" selected="selected"><?php echo $text_completed; ?></option>
                    	<option value="Pending"><?php echo $text_pending; ?></option>
                    <?php } else { ?>
                    	<option value="-">-</option>
                    	<option value="Completed"><?php echo $text_completed; ?></option>
                    	<option value="Pending" selected="selected"><?php echo $text_pending; ?></option>
                    <?php } ?>
                  </select>
                  <?php if ($ecowarehouse_sync_timest!="" && $ecowarehouse_sync_timest!="0000-00-00 00:00:00") { ?>
                  <?php echo $ecowarehouse_sync_timest; ?>
                  <?php } ?>
                  <?php if ($error_ecowarehouse_sync_status) { ?>
                  <div class="text-danger"><?php echo $error_ecowarehouse_sync_status; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-warehouseeco_sync_status"><?php echo $entry_warehouseeco_sync_status; ?></label>
                <div class="col-sm-10">
                  <select name="warehouseeco_sync_status" id="input-warehouseeco_sync_status" class="form-control" disabled="disabled">
                    <?php if ($warehouseeco_sync_status == "-") { ?>
                    	<option value="-1" selected="selected">-</option>
                    <?php } else { ?>
                    	<option value="<?php echo $warehouseeco_sync_status;?>" selected="selected"><?php echo $warehouseeco_sync_status; ?></option>
                    <?php } ?>
                  </select>
                  <?php if ($warehouseeco_sync_timest!="" && $warehouseeco_sync_timest!="0000-00-00 00:00:00") { ?>
                  <?php echo $warehouseeco_sync_timest; ?>
                  <?php } ?>
                  <?php if ($error_warehouseeco_sync_status) { ?>
                  <div class="text-danger"><?php echo $error_warehouseeco_sync_status; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
			<button type="button" class="btn btn-primary pull-left" onclick="confirm('Confirm to Duplicate?') ? $('#form-duplicate').submit() : false;"><i class="fa fa-copy"></i> <?php echo "Duplicate"; ?></button>
            <?php if($warehouseeco_sync_status == "Draft") { ?>
                <button type="button" class="btn btn-danger pull-right" onclick="confirm('Confirm to Cancel?') ? $('#form-cancel').submit() : false;"><i class="fa fa-trash-o"></i> <?php echo $button_cancel; ?></button>
            <?php } else if($warehouseeco_sync_status == "-") { ?>
                
            <?php } else { ?>
                <button type="button" class="btn btn-danger pull-right" onclick="alert('You are not allowed to cancel this transaction as it has passed the &quot;Draft&quot; status.');" style="margin-left:6px"><i class="fa fa-trash-o"></i> <?php echo $button_cancel; ?></button>
            <?php } ?>
            <?php if($ecowarehouse_sync_status != "Completed") { ?>
                <button type="submit" id="button-save" class="btn btn-primary pull-right"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
            <?php } else { ?>
            	<font color="#FF0000" class="pull-right">You are not allowed to amend this transaction as it has already been synchronised to the BRP warehouse.</font>
            <?php } ?>
        </form>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_detail_list; ?></h3>
      </div>
      <div class="panel-body">
        <?php /*
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-product_name"><?php echo $entry_product_name; ?></label>
                <input type="text" name="filter_product_name" value="<?php echo $filter_product_name; ?>" placeholder="<?php echo $entry_product_name; ?>" id="input-product_name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-product_type"><?php echo $entry_product_type; ?></label>
                <input type="text" name="filter_product_type" value="<?php echo $filter_product_type; ?>" placeholder="<?php echo $entry_product_type; ?>" id="input-product_type" class="form-control" />
              </div>
            </div>
          </div>
        </div>
        */ ?>
        <form action="<?php echo $action2; ?>" method="post" enctype="multipart/form-data" id="form_transaction_header" name="form_transaction_header" class="form-horizontal">
        <div id="listing">
          <div class="table-responsive">
            <table id="tran" class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-center" style="width:8%;">
                  	<?php if ($sort == 'row_no') { ?>
                    	<a href="<?php echo $sort_row_no; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_row_no; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_row_no; ?>"><?php echo $column_row_no; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center" style="width:17%;">
                  	<?php if ($sort == 'product_name') { ?>
                    	<a href="<?php echo $sort_product_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product_name; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_product_name; ?>"><?php echo $column_product_name; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center" style="width:15%;">
                  	<?php if ($sort == 'product_model') { ?>
                    	<a href="<?php echo $sort_product_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product_model; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_product_model; ?>"><?php echo $column_product_model; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center" style="width:15%;">
                  	<?php if ($sort == 'product_type') { ?>
                    	<a href="<?php echo $sort_product_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product_type; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_product_type; ?>"><?php echo $column_product_type; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center" style="width:5%;">
                  	<?php if ($sort == 'quantity') { ?>
                    	<a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                    <?php } ?>
                  </td>
                  <?php /*
                  <td class="text-center" style="width:10%;">
                  	<?php if ($sort == 'uom') { ?>
                    	<a href="<?php echo $sort_uom; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_uom; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_uom; ?>"><?php echo $column_uom; ?></a>
                    <?php } ?>
                  </td>*/ ?>
                  <td class="text-center" style="width:25%;">
                  	<?php if ($sort == 'remarks') { ?>
                    	<a href="<?php echo $sort_remarks; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_remarks; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_remarks; ?>"><?php echo $column_remarks; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center" style="width:5%;"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php $trans_row = 0; ?>
                <?php if ($trans) { ?>
                    <?php foreach ($trans as $tran) { ?>
                        <tr id="tran-row<?php echo $trans_row; ?>">
                            <td class="text-right" style="width:8%;">
                                <input type="text" name="warehouse_tran['<?php echo $trans_row; ?>'][row_no]" id="warehouse_tran_row_no_<?php echo $trans_row; ?>" value="<?php echo $tran['row_no']; ?>" placeholder="<?php echo $column_row_no; ?>" class="form-control" />
                            </td>
                            <td class="text-left" style="width:17%;">
                                <input type="text" name="warehouse_tran['<?php echo $trans_row; ?>'][product_name]" id="warehouse_tran_product_name_<?php echo $trans_row; ?>" onkeyup="autoAjax('warehouse_tran_product_name_<?php echo $trans_row; ?>','<?php echo $trans_row; ?>','name')" value="<?php echo $tran['product_name']; ?>" placeholder="<?php echo $column_product_name; ?>" class="form-control" />
                                <input type="hidden" name="warehouse_tran['<?php echo $trans_row; ?>'][product_id]" id="warehouse_tran_product_id_<?php echo $trans_row; ?>" value="<?php echo $tran['product_id']; ?>" class="form-control" />
                                <input type="hidden" name="warehouse_tran['<?php echo $trans_row; ?>'][matching_code]" id="warehouse_tran_matching_code_<?php echo $trans_row; ?>" value="<?php echo $tran['matching_code']; ?>" class="form-control" />
                            </td>
                            <td class="text-left" style="width:15%;">
                                <input type="text" name="warehouse_tran['<?php echo $trans_row; ?>'][product_model]" id="warehouse_tran_product_model_<?php echo $trans_row; ?>" onkeyup="autoAjax('warehouse_tran_product_model_<?php echo $trans_row; ?>','<?php echo $trans_row; ?>','model')" value="<?php echo $tran['product_model']; ?>" placeholder="<?php echo $column_product_model; ?>" class="form-control" readonly="readonly" />
                            </td>
                            <td class="text-left" style="width:15%;">
                                <input type="text" name="warehouse_tran['<?php echo $trans_row; ?>'][product_type]" id="warehouse_tran_product_type_<?php echo $trans_row; ?>" value="<?php echo $tran['product_type']; ?>" placeholder="<?php echo $column_product_type; ?>" class="form-control" readonly="readonly" />
                            	<?php /*
                                <select name="warehouse_tran['<?php echo $trans_row; ?>'][product_type]" id="warehouse_tran_product_type_<?php echo $trans_row; ?>" class="form-control">
                                    <?php if ($tran['product_type'] == "BRP") { ?>
                                    	<option value="BRP" selected="selected">BRP</option>
                                    	<option value="Third-Party">Third-Party</option>
                                    <?php } else if ($tran['product_type'] == "Third-Party") { ?>
                                    	<option value="BRP">BRP</option>
                                    	<option value="Third-Party" selected="selected">Third-Party</option>
                                    <?php } else { ?>
                                    	<option value="BRP">BRP</option>
                                    	<option value="Third-Party">Third-Party</option>
                                    <?php } ?>
                      	        </select>
                                */ ?>
                            </td>
                            <td class="text-right" style="width:5%;">
                                <input type="text" name="warehouse_tran['<?php echo $trans_row; ?>'][quantity]" value="<?php echo $tran['quantity']; ?>" placeholder="<?php echo $column_quantity; ?>" class="form-control" onkeypress="return strictlyNumberOnly(event, true);" />
                            </td>
                            <?php /*
                            <td class="text-left" style="width:10%;">
                                <input type="text" name="warehouse_tran['<?php echo $trans_row; ?>'][uom]" value="<?php echo $tran['uom']; ?>" placeholder="<?php echo $column_uom; ?>" class="form-control" />
                            </td>*/ ?>
                            <td class="text-left" style="width:15%;">
                                <input type="text" name="warehouse_tran['<?php echo $trans_row; ?>'][remarks]" value="<?php echo $tran['remarks']; ?>" placeholder="<?php echo $column_remarks; ?>" class="form-control" />
                            </td>
                            <td class="text-left" style="width:5%;">
                              <button type="button" onclick="$('#tran-row<?php echo $trans_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                            </td>
                        </tr>
                        <?php $trans_row++; ?>
                    <?php } ?>
                <?php } else { ?>
                    <tr id="no_result_id">
                      <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="6"></td>
                  <td class="text-left"><button type="button" onclick="addTransactionDetailRow();" data-toggle="tooltip" title="<?php echo $button_tran_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                </tr>
              </tfoot>
            </table>
            <?php if ($trans && count($trans)>0) { ?>
            <?php //if($ecowarehouse_sync_status == "Completed") { ?>
                <button type="button" id="button-print" form="form-transaction-header" formaction="<?php echo $print; ?>" class="btn btn-primary"><i class="fa fa-print"></i>  Print&nbsp;&nbsp;</button>
                <button type="button" id="button-email" form="form-transaction-header" formaction="<?php echo $email; ?>" class="btn btn-primary" style="margin-left:4px;"><i class="fa fa-envelope"></i> Resend Transaction via Email&nbsp;&nbsp;</button>
            <?php } ?>
            <?php if($warehouseeco_sync_status == "Draft") { ?>
                <button type="button" class="btn btn-danger pull-right" onclick="confirm('Confirm to Cancel?') ? $('#form-cancel').submit() : false;"><i class="fa fa-trash-o"></i> <?php echo $button_cancel; ?></button>
            <?php } else if($warehouseeco_sync_status == "-") { ?>
                
            <?php } else { ?>
                <button type="button" class="btn btn-danger pull-right" onclick="alert('You are not allowed to cancel this transaction as it has passed the &quot;Draft&quot; status.');" style="margin-left:6px"><i class="fa fa-trash-o"></i> <?php echo $button_cancel; ?></button>
            <?php } ?>
            
            <?php if($ecowarehouse_sync_status != "Completed") { ?>
                <button type="button" id="button-save" class="btn btn-primary pull-right" onclick="javascript:form_submit();"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
            <?php } else { ?>
            <?php } ?>
          </div>
        </div>
        </form><br />
        <form action="<?php echo $duplicate; ?>" method="post" enctype="multipart/form-data" id="form-duplicate">
            <input type="hidden" name="selected[]" value="<?php echo $transaction_id; ?>" />
        </form>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-tran">
            <input type="hidden" name="selected[]" value="<?php echo $transaction_id; ?>" />
        </form>
        <form action="<?php echo $cancel; ?>" method="post" enctype="multipart/form-data" id="form-cancel">
            <input type="hidden" name="selected[]" value="<?php echo $transaction_id; ?>" />
        </form>
        <div class="row">
          <div class="col-sm-6 text-left">
            <?php if ($trans && count($trans)>0) { ?>
            <?php //if($ecowarehouse_sync_status == "Completed") { ?>
				<font color="#FF0000">Please print a copy of this transaction and attach it with the items when sending for storage.</font>
            <?php } ?>
          </div>
          <div class="col-sm-6 text-right">
            <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
            <div class="col-sm-6 text-right"><?php echo $results; ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
function printSelection(node) {
	var content=node.innerHTML
	var pwin = window.open('','print_content','width=500,height=500');
	pwin.document.open();
	pwin.document.write('<html><body onload="window.print()">'+content+'</body></html>');
	pwin.document.close();
	setTimeout(function(){pwin.close();},1000);
}
</script>
<script type="text/javascript"><!--
function form_submit() {
	var isValid = true;
	for(i=0; i<200; i++) {
		if(document.getElementById("warehouse_tran_product_name_"+i) && document.getElementById("warehouse_tran_product_name_"+i).value=="") {
			alert("Product Name must be filled!");
			document.getElementById("warehouse_tran_product_name_"+i).focus();
			isValid = false;
			break;
		} else if(document.getElementById("warehouse_tran_product_model_"+i) && document.getElementById("warehouse_tran_product_model_"+i).value=="") {
			alert("Model must be filled!");
			document.getElementById("warehouse_tran_product_model_"+i).focus();
			isValid = false;
			break;
		}
	}
	if(isValid) {
		document.forms.form_transaction_header.submit();
	}
}
$('#button-filter').on('click', function() {
	var url = 'index.php?route=warehouse/transactions/headertran&token=<?php echo $token; ?>&id=<?php echo $header_id; ?>';
	var filter_product_name = $('input[name=\'filter_product_name\']').val();
	if (filter_product_name) {
		url += '&filter_product_name=' + encodeURIComponent(filter_product_name);
	}
	var filter_product_type = $('input[name=\'filter_product_type\']').val();
	if (filter_product_type) {
		url += '&filter_product_type=' + encodeURIComponent(filter_product_type);
	}
	var filter_transaction_type = $('input[name=\'filter_transaction_type\']').val();
	if (filter_transaction_type) {
		url += '&filter_transaction_type=' + encodeURIComponent(filter_transaction_type);
	}
	location = url;
});
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

  <script type="text/javascript"><!--
var trans_row = <?php echo $trans_row; ?>;

function addTransactionDetailRow() {
	if(document.getElementById("no_result_id")) {
		document.getElementById("no_result_id").style.display = 'none';
	}
	html  = '<tr id="tran-row' + trans_row + '">';
		html += '  <td class="text-right"><input type="text" name="warehouse_tran[\'' + trans_row + '\'][row_no]" value="' + (trans_row+1) + '" placeholder="<?php echo $column_row_no; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><input type="text" name="warehouse_tran[\'' + trans_row + '\'][product_name]" id="warehouse_tran_product_name_' + trans_row + '" onkeyup="autoAjax(\'warehouse_tran_product_name_' + trans_row + '\',\''+ trans_row + '\',\'name\')" value="" placeholder="<?php echo $column_product_name; ?>" class="form-control" /><input type="hidden" name="warehouse_tran[\'' + trans_row + '\'][product_id]" id="warehouse_tran_product_id_' + trans_row + '" value="" class="form-control" /><input type="hidden" name="warehouse_tran[\'' + trans_row + '\'][matching_code]" id="warehouse_tran_matching_code_' + trans_row + '" value="" class="form-control" /></td>';
		html += '  <td class="text-left"><input type="text" name="warehouse_tran[\'' + trans_row + '\'][product_model]" id="warehouse_tran_product_model_' + trans_row + '" value="" placeholder="<?php echo $column_product_model; ?>" class="form-control" readOnly="readOnly" /></td>';
		html += '  <td class="text-left"><input type="text" name="warehouse_tran[\'' + trans_row + '\'][product_type]" id="warehouse_tran_product_type_' + trans_row + '" value="" placeholder="<?php echo $column_product_type; ?>" class="form-control" readOnly="readOnly" /></td>';
		//html += '  <td class="text-left"><select name="warehouse_tran[\'' + trans_row + '\'][product_type]" class="form-control"><option value="BRP" selected="selected">BRP</option><option value="Third-Party">Third-Party</option></select></td>';
		html += '  <td class="text-right"><input type="text" name="warehouse_tran[\'' + trans_row + '\'][quantity]" value="" placeholder="<?php echo $column_quantity; ?>" class="form-control" onkeypress="return strictlyNumberOnly(event, true);" /></td>';
		//html += '  <td class="text-left"><input type="text" name="warehouse_tran[\'' + trans_row + '\'][uom]" value="" placeholder="<?php echo $column_uom; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><input type="text" name="warehouse_tran[\'' + trans_row + '\'][remarks]" value="" placeholder="<?php echo $column_remarks; ?>" class="form-control" /></td>';
		//html += '  <td class="text-left"><select name="warehouse_tran[' + trans_row + '][product_status]" class="form-control">';
		//html += '      <option value="1"><?php echo $text_completed; ?></option>';
		//html += '      <option value="0"><?php echo $text_pending; ?></option>';
		//html += '  </select></td>';
		//html += '  <td class="text-left"><select name="warehouse_tran[' + trans_row + '][sync_status]" class="form-control">';
		//html += '      <option value="1"><?php echo $text_completed; ?></option>';
		//html += '      <option value="0"><?php echo $text_pending; ?></option>';
		//html += '  </select></td>';
		html += '  <td class="text-left"><button type="button" onclick="$(\'#tran-row' + trans_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#tran tbody').append(html);

	/*$('.date').datetimepicker({
		pickTime: false
	});*/

	trans_row++;
}
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_product_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item["name"],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_product_name\']').val(item['label']);
	}
});
var gloAllNames = [];
function autoAjax(name, rownum, type) {
	if($.inArray( name, gloAllNames )==-1) {
		gloAllNames[gloAllNames.length] = name;
		//alert(name)
		//console.log(gloAllNames);
		$('input[id=\''+name+'\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_'+type+'_model=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							var productType = 'BRP';
							if(item['data_source'] == '' || item['data_source'] == '0') {
								productType = 'Third-Party';
							}                     
							return {
								label: item[type],
								labelModel: item['model'],
								labelProductType: productType,
								value: item['product_id'],
								matchingCode: item['matching_code']
							}
						}));
					}
				});
			},
			'select': function(item) {
				//console.log(item);
				$('input[id=\''+name+'\']').val(item['label']);
				$('input[id=\'warehouse_tran_product_model_'+rownum+'\']').val(item['labelModel']);
				$('input[id=\'warehouse_tran_product_type_'+rownum+'\']').val(item['labelProductType']);
				//$('select[id=\'warehouse_tran_product_type_'+rownum+'\']').val(item['labelProductType']);
				$('input[id=\'warehouse_tran_product_id_'+rownum+'\']').val(item['value']);
				$('input[id=\'warehouse_tran_matching_code_'+rownum+'\']').val(item['matchingCode']);
			}
		});
	}
}
//--></script>
  <script type="text/javascript"><!--
$('#button-sync').on('click', function(e) {
	$('#form-transaction-header').attr('action', this.getAttribute('formAction'));
	$('#form-transaction-header').attr('target', "");
	$('#form-transaction-header').submit();
});
$('#button-sync2').on('click', function(e) {
	$('#form-transaction-header').attr('action', this.getAttribute('formAction'));
	$('#form-transaction-header').attr('target', "");
	$('#form-transaction-header').submit();
});
$('#button-print').on('click', function(e) {
	$('#form-transaction-header').attr('action', this.getAttribute('formAction'));
	$('#form-transaction-header').attr('target', "_blank");
	$('#form-transaction-header').submit();
});
$('#button-email').on('click', function(e) {
	$('#form-transaction-header').attr('action', this.getAttribute('formAction'));
	$('#form-transaction-header').attr('target', "");
	$('#form-transaction-header').submit();
});
//--></script> 
</div>
<?php echo $footer; ?>