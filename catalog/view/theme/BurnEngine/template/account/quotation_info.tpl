<?php echo $header; ?>
<style type="text/css">
.helper{
  z-index: 10001;
  border-top: 1px solid rgba(255,255,255,0.5);
  border-bottom: 1px dotted rgba(255,255,255,0.5);
  padding: 10px 15px;
  text-align: center;
  font-size: 13px;
  font-family: "Droid Serif", sans-serif;
    background: #C1FFCC;
  color: #184B21;
}
.breadcrumb li:last-child:not(:nth-child(2)):not(:nth-child(3)) a {
 display: inline-block;
}

</style>

<?php $tbData->slotStart('account/quotation/info.breadcrumbs'); ?>
  <ul class="breadcrumb hidden-xs">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
<?php $tbData->slotStop(); ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php $tbData->slotStart('account/quotation/info.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>
     
      <?php if(!$quotation_under_review) { ?>
      <div class="helper"><?php echo $text_quotation_under_review; ?></div>
      <?php } ?>

      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" colspan="2"><?php echo $text_quotation_detail; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" style="width: 50%;">
              <b><?php echo $text_quotation_id; ?></b> #<?php echo $quotation_id; ?><br />
              <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?>
			</td>
			<td class="text-left" style="width: 50%;">
				<b>Name:</b> <?php echo $customer_name; ?><br />
				<b>Email:</b> <?php echo $email; ?><br />
				<b>Telephone:</b> <?php echo $telephone; ?><br />
				<b>Address:</b> <?php echo $address; ?><br />
				<b>Company:</b> <?php echo $company; ?>
			</td>
          </tr>
        </tbody>
      </table>

      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_image; ?></td>
              <td class="text-left"><?php echo $column_name; ?></td>
              <td class="text-left"><?php echo $column_model; ?></td>
              <td class="text-right"><?php echo $column_quantity; ?></td>
               <?php if($quotation_under_review) { ?>
              <td class="text-right"><?php echo $column_price; ?></td>
			  <td class="text-right"><?php echo $column_gst; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td class="text-center">
                <?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                  <?php } ?>
               </td>
              <td class="text-left"><?php echo $product['name']; ?>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?></td>
              <td class="text-left"><?php echo $product['model']; ?></td>
              <td class="text-right"><?php echo $product['quantity']; ?></td>
               <?php if($quotation_under_review) { ?>
              <td class="text-right"><?php echo $product['qprice']; ?></td>
			  <td class="text-right"><?php echo $product['gst']; ?></td>
              <td class="text-right"><?php echo $product['total']; ?></td>
              <?php } ?>
            </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
              <td class="text-left"><?php echo $voucher['description']; ?></td>
              <td class="text-left"></td>
              <td class="text-right">1</td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td colspan="6" class="text-right"><b><?php echo $total['title']; ?></b></td>
              <td colspan="6" class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </tfoot>
        </table>
      </div>
      <?php if ($comment) { ?>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $text_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $comment; ?></td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
	  <div id="div-reject-<?php echo $quotation_id;?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"></button>
                                            <h4 class="modal-title">Reject</h4>
                                      </div>
                                      <div class="modal-body">
                                          <label class="control-label" >Reject Quotation By:&nbsp;</label><label class="control-label" ><?php echo $customer_name;?></label>
                                          <br>
                                          <label class="control-label" >Created On:&nbsp;</label><label class="control-label" ><?php echo $date_added;?></label>
                                          <br>
                                          <?php foreach ($totals as $total) { ?>
                                            <label class="control-label" ><?php echo $total['title']; ?>:&nbsp;</label><label class="control-label" ><?php echo $total['text']; ?></label><br>
                                            <?php } ?>
                                          <br>
                                        <label class="control-label" >Reason:&nbsp;</label>
                                        <br><textarea style="width:100%;" id="text-reject-<?php echo $quotation_id;?>"></textarea>
                                      </div>
                                      <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="rejectQuotation('<?php echo $quotation_id;?>')">Reject</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                            </div>
                      </div>
	  <div class="buttons clearfix" style="padding-bottom:10px;">
		
                    
                        <div class="pull-right">
			<button class="btn btn-danger" onclick="printInvoice('<?php echo $invoice_page;?>')">Print</button>
		</div>
	  </div>
	  
	  <?php if((!$enable_approval &&($quotation_status_id != $qcompleted && $quotation_status_id != $qexpired && $quotation_status_id != $qipay88 && $quotation_status_id != $qcredit && $quotation_status_id != $qcod && $quotation_status_id != $qbanktransfer && $quotation_status_id != $qppstandard && $quotation_status_id != $qghl)) || ($enable_approval && ($quotation_status_id == $qapprove || ($quotation_approval && ($quotation_status_id == $qapprove || $quotation_status_id == $qpending)))) ) { ?>
		<input type="hidden" name="quotation_id" value="<?php echo $quotation_id; ?>">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
                                        <?php if($quotationSuperAdmin && $quotationCustomerId == $customer_id) { ?>
					You do no have the permission to approve this quotation.
                                        <?php }else { ?>
                                        <a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle btn"><?php if($isPetronasUser && $quotation_approval) { ?>Approve and Confirm <?php } else { ?>Payment Method <?php } ?><i class="fa fa-caret-down"></i></a>
                                        <?php if((!$enable_approval &&($quotation_status_id != $qcompleted && $quotation_status_id != $qexpired && $quotation_status_id != $qipay88 && $quotation_status_id != $qcredit && $quotation_status_id != $qcod && $quotation_status_id != $qbanktransfer && $quotation_status_id != $qppstandard && $quotation_status_id != $qghl)) || ($enable_approval && ($quotation_status_id == $qapprove || ($quotation_approval && ($quotation_status_id == $qapprove || $quotation_status_id == $qpending)))) ) { ?>
                                        <a id="modal-reject-<?php echo $quotation_id;?>" onclick="openModalReject('<?php echo $quotation_id;?>')" data-toggle="tooltip" title="Reject" class="btn btn-info btn-default">Reject</a>
                                        <?php } ?>
                                        <?php } ?>
                                        
				</h4>
			</div>
			<div class="panel-collapse collapse" id="collapse-payment-method" style="padding-bottom:60px;">
				<div id="quotation-payment" class="panel-body"></div>
				<div id="payment-bar" class="container payment"><?php echo $payment; ?></div>
			</div>
		</div>
	  
      <?php } ?>

<?php echo $footer; ?>
<script type="text/javascript">
var quotation_id = '<?php echo $quotation_id; ?>';

$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=checkout/payment_method',
		dataType: 'html',
		success: function(html) {
			$('#collapse-payment-method .panel-body').html(html);

			$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');

			$('.panel-body .buttons .pull-right').html('');
			$('.payment img').css('display', 'none');
			$('.pull-left').parent().hide();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});
$(document).on('change', 'input[name=\'payment_method\']', function() {
	var method = $('input[name=payment_method]:checked').val(); 
	if(method == "free_checkout"){
		$("#payment-bar").html(<?php echo json_encode($payment2); ?>);
	}
	else if(method == "ipay88"){ //ipay88
		$("#payment-bar").html(<?php echo json_encode($payment); ?>);
		$('.payment img').css('display', 'none');
	}
	else if(method == "cod"){
		$("#payment-bar").html(<?php echo json_encode($payment3); ?>);
	}
	else if(method == "bank_transfer"){
		$("#payment-bar").html(<?php echo json_encode($payment4); ?>);
	}
	else if(method == "pp_standard"){
		$("#payment-bar").html(<?php echo json_encode($payment5); ?>);
	}
	else if(method == "ghl"){
		$("#payment-bar").html(<?php echo json_encode($payment6); ?>);
	}
});

function printInvoice(url){
	window.open(url, '_blank');
	win.focus();
}

function openModalReject(id){
        $('#div-reject-'+id).modal('show');
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

</script>