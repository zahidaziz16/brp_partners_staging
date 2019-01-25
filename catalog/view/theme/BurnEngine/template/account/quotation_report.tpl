<?php echo $header; ?>
<link href="catalog/view/javascript/datatables/glyph.css" rel="stylesheet" />
<link href="catalog/view/javascript/datatables/datatables.min.css" rel="stylesheet" />
<link href="catalog/view/javascript/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<script src="catalog/view/javascript/datatables/datatables.min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>


<script>
	 if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", '<?php echo $current_page;?>');
    }
</script>
<style>
	.btn-info{
		box-shadow: none !important;
		border: 1px solid white !important;
		width: 50px !important;
	}
	.btn-default{
		border: 1px solid #ccc;
	}
	.img-responsive{
		max-width: 350px;
		max-height: 350px;
	}
	.pagination{
		display:inline-flex;
	}
	.pagination > li > a, .pagination > li > span {
    position: relative;
    float: left;
    padding: 6px 12px;
    line-height: 1.42857143;
    text-decoration: none;
    color: #428bca;
    background-color: #fff;
    border: 1px solid #ddd;
	}
	td .btn + .btn {
    margin-left: 0em;
	}
    .payment-method textarea {
    width: 100% !important;
    }
</style>
<?php // Breadcrumbs ------------------------------------------------- ?>

<?php $tbData->slotStart('account/quotation/report.breadcrumbs'); ?>
  <ul class="breadcrumb hidden-xs">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php $tbData->slotStop(); ?>
<?php if ($success) { ?>
  <div class="alert alert-success" id="div-success"><i class="fa fa-check-circle"></i> <span id="success-msg"><?php echo $success; ?></span></div>
<?php } ?>
  <div class="alert alert-danger" id="div-danger" style="display:none;"><i class="fa fa-exclamation-circle"></i> <span id="err-msg"><?php echo $error_warning; ?></span></div>
  
<?php // Page title -------------------------------------------------- ?>
  
<?php $tbData->slotStart('account/quotation/report.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ----------------------------------------------- ?>

<?php $tbData->slotStart('account/quotation/report.page_content'); ?>

<div class="row">
<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="">Approver:</span></label>
<div class="col-sm-4">
  <input type="text" id="approver"  value="" placeholder=""  class="form-control" />
</div>
<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="">Approved:</span></label>
<div class="col-sm-4">
   <select id="approved">
    <option value="all" selected>All</option>
    <option value="yes">Yes</option>
    <option value="no">No</option>
</select> 
</div>
</div>
<div class="row">
<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="">Start Date:</span></label>
<div class="col-sm-4">
  <input type="text" id="start_date"  value="" placeholder=""  class="form-control" />
</div>
<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="">End Date:</span></label>
<div class="col-sm-4">
  <input type="text" id="end_date"  value="" placeholder=""  class="form-control" />
</div>
</div>
<div class="buttons clearfix">
    <div class="pull-right"><a onclick="window.location = 'index.php?route=account/quotation/exportPSExcel&approver='+$('#approver').val()+'&start_date='+$('#start_date').val()+'&end_date='+$('#end_date').val()+'&approved='+$('#approved').val()+'';" data-toggle="tooltip" title="Generate Excel" class="btn btn-info btn-default tb_no_text"><i class="fa fa-file-excel-o" style="font-size:17px;"></i></a>&nbsp;<button onclick="reloadTable();" class="btn btn-primary">Filter</button></div>
</div>

<table id="tbl1" class="table table-striped table-bordered" width="100%" cellspacing="0" style="margin:0px auto;">
<thead>
<tr>
<th >Quotation ID</th>
<th >Quotation Date</th>
<th >Transaction Status</th>
<th >Delivery Date</th>
<th >User Name</th>
<th >Approver Name</th>
<th >Approved Date</th>
<th >Material Purchase Price</th>
</tr>
</thead>
<tbody></tbody>
</table>

<div class="row">
    <div class="col-xs-6">
        <strong><label class="col-sm-6 control-label" >Total Quotations:</label></strong><label class="col-sm-6 control-label" id="totalQuotations"></label>
        <strong><label class="col-sm-6 control-label" >Total Approved Quotations:</label></strong><label class="col-sm-6 control-label" id="totalApproved"></label>
        <strong><label class="col-sm-6 control-label" >Total Purchase Price:</label></strong><label class="col-sm-6 control-label" id="totalPurchase"></label>
    </div>
</div>
      
      
      <div class="buttons clearfix">
        
      </div>
      <?php echo $content_bottom; ?>
    <?php //echo $column_right; ?>
	<?php $tbData->slotStop(); ?>


<script type="text/javascript">
$(document).ready(function() {
    
    $('#start_date').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    });

    $('#end_date').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    });
    
var report = $('#tbl1').DataTable({
    "columnDefs": [{
        //targets: [0, 6, 9, 10],
        //orderable: false
    }, {
        //targets: [8],
        //className: "text-center"
    }],
    "bFilter": false,
    "order": [
        [0, "asc"]
    ],
    "stateSave": true,
    "lengthMenu": [
        [10, 20, -1],
        [10, 20, 'All']
    ],
    "serverSide": true,
    "ajax": {
        url: 'index.php?route=account/quotation/reportTables',
        type: "GET",
        data: {
            'approver': $('#approver').val(),
            'start_date': $('#start_date').val(),
            'end_date': $('#end_date').val(),
            'approved': $('#approved').val()
        }
    },
    "fnInitComplete": function(oSettings, json) {
        //console.log(json);
        //Ext.getBody().unmask();
    },
    "fnDrawCallback": function() {
        //Ext.getBody().unmask();
    },
    "fnPreDrawCallback": function() {
        //Ext.getBody().mask('Loading...');
    }
});

$('#tbl1').DataTable().on( 'xhr', function () {
    var json = $('#tbl1').DataTable().ajax.json();
    $('#totalQuotations').html(json.recordsFiltered);
    $('#totalApproved').html(json.totalApproved);
    $('#totalPurchase').html(json.totalPurchase);
} );

} );  

function reloadTable() {
    $('#tbl1').DataTable().destroy();
    report = $('#tbl1').DataTable({
    "columnDefs": [{
        //targets: [0, 6, 9, 10],
        //orderable: false
    }, {
        //targets: [8],
        //className: "text-center"
    }],
    "bFilter": false,
    "order": [
        [0, "asc"]
    ],
    "stateSave": true,
    "lengthMenu": [
        [10, 20, -1],
        [10, 20, 'All']
    ],
    "serverSide": true,
    "ajax": {
        url: 'index.php?route=account/quotation/reportTables',
        type: "GET",
        data: {
            'approver': $('#approver').val(),
            'start_date': $('#start_date').val(),
            'end_date': $('#end_date').val(),
            'approved': $('#approved').val()
        }
    },
    "fnInitComplete": function(oSettings, json) {
        //console.log(json);
        //Ext.getBody().unmask();
    },
    "fnDrawCallback": function() {
        //Ext.getBody().unmask();
    },
    "fnPreDrawCallback": function() {
        //Ext.getBody().mask('Loading...');
    }
});
}
    
    var quotation_id = 0;
    
    function openModal(id){
		$('#div-attach-'+id).modal('show');
	}
        
        function openModalApprove(id){
		$('#div-approve-'+id).modal('show');
	}
        
        function openModalApproveConfirm(id){
		$('#div-approveconfirm-'+id).modal('show');
	}
        
        function openModalReject(id){
		$('#div-reject-'+id).modal('show');
	}
	
	function saveFile(id){
		var fd = new FormData();
		fd.append("filename", $('#file-attach-'+id)[0].files[0]);
		fd.append("quotation_id", id);
		$.ajax({
			url: 'index.php?route=account/quotation/saveAttachment',
			dataType: 'json',
			type: 'post',
			processData: false,
			contentType: false,
			cache: false,
			data: fd,
			success: function(json) {
				console.log(json);
				if(json['error_warning'] != ''){
					$('#err-msg').text(json['error_warning']);
					$('#div-danger').show();
				}
				else{
					setTimeout(function() {
						if(json['redirect'])
							location.href = json['redirect'];
					}, 500);
				}
			},				
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
		return false;
	}
        
        function approveQuotation(id){
		var fd = new FormData();
		fd.append("quotation_id", id);
		$.ajax({
			url: 'index.php?route=account/quotation/approveQuotation',
			dataType: 'json',
			type: 'post',
			processData: false,
			contentType: false,
			cache: false,
			data: fd,
			success: function(json) {
				console.log(json);
				if(json['error_warning'] != ''){
					$('#err-msg').text(json['error_warning']);
					$('#div-danger').show();
				}
				else{
					setTimeout(function() {
						if(json['redirect'])
							location.href = json['redirect'];
					}, 500);
				}
			},				
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
		return false;
	}
        
        function rejectQuotation(id){
		var fd = new FormData();
		fd.append("quotation_id", id);
                fd.append("reject_reason", $('#text-reject-'+id).val());
		$.ajax({
			url: 'index.php?route=account/quotation/rejectQuotations',
			dataType: 'json',
			type: 'post',
			processData: false,
			contentType: false,
			cache: false,
			data: fd,
			success: function(json) {
				console.log(json);
				if(json['error_warning'] != ''){
					$('#err-msg').text(json['error_warning']);
					$('#div-danger').show();
				}
				else{
					setTimeout(function() {
						if(json['redirect'])
							location.href = json['redirect'];
					}, 500);
				}
			},				
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
		return false;
	}
        
        function getPaymentData(id){
                var fd = new FormData();
		fd.append("quotation_id", id);
                $.ajax({
                        url: 'index.php?route=checkout/payment_method',
                        dataType: 'html',
                        type: 'post',
			processData: false,
			contentType: false,
			cache: false,
			data: fd,
                        success: function(html) {
                                $('#collapse-payment-method-'+id+' .panel-body').html(html);

                                $('.panel-body .buttons .pull-right').html('');
                                $('.payment img').css('display', 'none');
                                $('.pull-left').parent().hide();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                });	
        }
        
        $(document).on('change', 'input[name=\'payment_method\']', function() {
                var method = $('input[name=payment_method]:checked').val(); 
                if(method == "free_checkout"){
                        $("#payment-bar-"+quotation_id).html(<?php echo json_encode($payment2); ?>);
                }
                else if(method == "ipay88"){ //ipay88
                        $("#payment-bar-"+quotation_id).html(<?php echo json_encode($payment); ?>);
                        $('.payment img').css('display', 'none');
                }
                else if(method == "cod"){
                        $("#payment-bar-"+quotation_id).html(<?php echo json_encode($payment3); ?>);
                }
                else if(method == "bank_transfer"){
                        $("#payment-bar-"+quotation_id).html(<?php echo json_encode($payment4); ?>);
                }
        });

	function deleteFile(id){
		$.ajax({
			url: 'index.php?route=account/quotation/deleteAttachment&quotation_id='+id,
			dataType: 'json',
			type: 'post',
			success: function(json) {
				console.log(json);
				if(json['error_warning'] != ''){
					$('#err-msg').text(json['error_warning']);
					$('#div-danger').show();
				}
				else{
					setTimeout(function() {
						if(json['redirect'])
							location.href = json['redirect'];
					}, 500);
				}
			},		
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
		return false;
	}
</script>
<?php echo $footer; ?>