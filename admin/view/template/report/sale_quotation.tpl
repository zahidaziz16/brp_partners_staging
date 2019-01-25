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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_quotation_status_id" id="input-status" class="form-control">
                  <option value="0"><?php echo $text_all_status; ?></option>
                  <?php foreach ($quotation_statuses as $quotation_status) { ?>
                  <?php if ($quotation_status['quotation_status_id'] == $filter_quotation_status_id) { ?>
                  <option value="<?php echo $quotation_status['quotation_status_id']; ?>" selected="selected"><?php echo $quotation_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $quotation_status['quotation_status_id']; ?>"><?php echo $quotation_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-approver">Approver</label>
                <input name="filter_approver" type="text" id="input-approver" value="<?php echo $filter_approver; ?>" class="form-control" />
              </div>
            </div>
              <div class="col-sm-6">
              <div class="form-group">
                  <label class="control-label" for="input-approved">Approved</label>
                   <select name="filter_approved" id="input-approved"  class="form-control"> 
                    <option value="" <?php if($filter_approved == ""){ echo "selected"; } ?> >All</option>
                    <option value="yes" <?php if($filter_approved == "yes"){ echo "selected"; } ?> >Yes</option>
                    <option value="no" <?php if($filter_approved == "no"){ echo "selected"; } ?> >No</option>
                   </select>
                </div>
                  <div class=" pull-right"><a onclick="window.location = 'index.php?route=report/sale_quotation/exportPSExcel&approver='+$('#input-approver').val()+'&start_date='+$('#input-date-start').val()+'&end_date='+$('#input-date-end').val()+'&approved='+$('#input-approved').val()+'&quotation_status='+$('#input-status').val()+'&token=<?php echo $token; ?>';" data-toggle="tooltip" title="Generate Excel" class="btn btn-info btn-default tb_no_text"><i class="fa fa-file-excel-o" style="font-size:17px;"></i></a>&nbsp;<button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button></div>
              </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bquotationed">
            <thead>
              <tr>
                  <td class="text-left">Quotation ID</td>
                  <td class="text-left">Quotation Date</td>
                  <td class="text-left">Transaction Status</td>
                  <td class="text-left">Delivery Date</td>
                  <td class="text-left">User Name</td>
                  <td class="text-left">Approver Name</td>
                  <td class="text-right">Price (exl GST)</td>
                  <td class="text-right">GST</td>
                  <td class="text-right">Material Purchase Price</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($quotations) { ?>
              <?php foreach ($quotations as $quotation) { ?>
              <tr>
                <td class="text-left"><?php echo $quotation['quotation_id']; ?></td>
                <td class="text-left"><?php echo $quotation['quotation_date']; ?></td>
                <td class="text-left"><?php echo $quotation['bord']; ?></td>
                <td class="text-left"><?php echo $quotation['delivery_date']; ?></td>
                <td class="text-left"><?php echo $quotation['user_name']; ?></td>
                <td class="text-left"><?php echo $quotation['approver_name']; ?></td>
                <td class="text-right"><?php echo $quotation['sub_total']; ?></td>
                <td class="text-right"><?php echo $quotation['tax']; ?></td>
                <td class="text-right"><?php echo $quotation['total']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/sale_quotation&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').val();
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
        
        var filter_approver = $('input[name=\'filter_approver\']').val();
        
        if (filter_approver) {
		url += '&filter_approver=' + encodeURIComponent(filter_approver);
	}
        
        var filter_approved = $('select[name=\'filter_approved\']').val();
        
        if (filter_approved) {
		url += '&filter_approved=' + encodeURIComponent(filter_approved);
	}
	
	var filter_quotation_status_id = $('select[name=\'filter_quotation_status_id\']').val();
	
	if (filter_quotation_status_id != 0) {
		url += '&filter_quotation_status_id=' + encodeURIComponent(filter_quotation_status_id);
	}	

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>