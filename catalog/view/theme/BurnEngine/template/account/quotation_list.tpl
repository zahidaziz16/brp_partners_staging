<?php echo $header; ?>
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

<?php $tbData->slotStart('account/quotation.breadcrumbs'); ?>
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
  
<?php $tbData->slotStart('account/quotation.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ----------------------------------------------- ?>

<?php $tbData->slotStart('account/quotation.page_content'); ?>
      
      <?php if ($quotations) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-center"><?php echo $column_quotation_id; ?></td>
              <td class="text-center"><?php echo $column_quotation_by; ?></td>
			  <td class="text-center"><?php echo $column_date_added; ?></td>
              <td class="text-center"><?php echo $column_product; ?></td>
              <td class="text-center"><?php echo $column_total; ?></td>
			  <td class="text-center"><?php echo $column_action; ?></td>
                          <?php if ($enable_approval) { ?>
                          <td class="text-center"><?php echo $column_action; ?></td>
                          <?php } ?>
			  <td class="text-center" style="width:170px;"><?php echo $column_status; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($quotations as $quotation) { ?>
            <tr>
              <td class="text-center">#<?php echo $quotation['quotation_id']; ?></td>
              <td class="text-center"><?php echo $quotation['name'];?></td>
			  <td class="text-center"><?php echo $quotation['date_added']; ?></td>
              <td class="text-center"><?php echo $quotation['products']; ?></td>
			  <?php if($quotation['quotation_under_review']) { ?>
              <td class="text-center"><?php echo $quotation['total']; ?></td>
              <?php } else { ?>
              <td class="text-center">N/A</td>
              <?php } ?>
			  <td class="text-center">
                <a href="<?php echo $quotation['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info btn-default tb_no_text"><i class="fa fa-eye" style="font-size:17px;"></i></a>

                <?php if($quotation['quotation_under_review'] && $quotation['quotation_status_id'] != $qexpired) { ?>
                <a href="<?php echo $quotation['pdf']; ?>" target="_blank" data-toggle="tooltip" title="Generate PDF" class="btn btn-info btn-default tb_no_text"><i class="fa fa-file-pdf-o" style="font-size:17px;"></i></a>
                  <?php } else if($quotation['quotation_status_id'] != $qexpired){ ?>
                  <a href="<?php echo $quotation['generate_inv']; ?>" data-toggle="tooltip" title="Generate PDF" class="btn btn-info btn-default tb_no_text"><i class="fa fa-cog" style="font-size:17px;"></i></a>
                  <?php } else { ?>
				  <a disabled class="btn btn-info btn-default tb_no_text"><i class="fa fa-ban" style="font-size:17px;"></i></a>
                  <?php } ?>
				  
				<?php if($quotation['quotation_under_review'] && $quotation['quotation_status_id'] != $qexpired && $quotation['quotation_status_id'] != $qipay88) { ?>
                <a id="modal-<?php echo $quotation['quotation_id'];?>" onclick="openModal('<?php echo $quotation['quotation_id'];?>')" data-toggle="tooltip" title="Upload Attachment" class="btn btn-info btn-default tb_no_text" style="display:none !important; cursor:pointer; <?php if($quotation['invoice_attachment'] != '') echo "color:#ee1c25;";?>">
					<i class="fa fa-file-image-o" style="font-size:17px;"></i>
				</a>
                  <?php } else { ?>
                  <a disabled data-toggle="tooltip" title="Upload Attachment" class="btn btn-info btn-default tb_no_text" style="display:none !important;"><i class="fa fa-file-image-o" style="font-size:17px;"></i></a>
                  <?php } ?>
				  
				  <div id="div-attach-<?php echo $quotation['quotation_id'];?>" class="modal fade" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"></button>
							<h4 class="modal-title">Payment Attachment Details</h4>
						  </div>
						  <div class="modal-body">
							<?php if($quotation['invoice_attachment'] == '') { ?>
								<label class="control-label" for="file-attach-<?php echo $quotation['quotation_id'];?>">Upload File</label>&emsp; 
								<input type="file" id="file-attach-<?php echo $quotation['quotation_id'];?>" class="form-control" style="width:70%; height:auto; display:inline-block;">
							<?php } ?>
								
							<?php if($quotation['invoice_attachment'] != '' && $quotation['quotation_under_review']) { 
								$info = pathinfo($quotation['invoice_attachment']);
								$ext = $info['extension'];
								if($ext == 'pdf'){ ?>
									<embed style="width:90%; height:350px;" src="<?php echo HTTP_UPLOAD.'quotation/'.$quotation['invoice_attachment']; ?>"></embed>
							<?php } else { ?>
									<img class="img-responsive center-block" src="<?php echo HTTP_UPLOAD.'quotation/'.$quotation['invoice_attachment']; ?>"></img>
							<?php } } ?>
						  </div>
						  <div class="modal-footer">
							<?php if($quotation['invoice_attachment'] == '') { ?>
						    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="saveFile('<?php echo $quotation['quotation_id'];?>')">Save</button>
							<?php } else if($quotation['quotation_status_id'] != $qcompleted){ ?>
							<button type="button" class="btn btn-default" data-dismiss="modal" onclick="deleteFile('<?php echo $quotation['quotation_id'];?>')">Remove</button>
							<?php } ?>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						  </div>
						</div>
					</div>
				  </div>
              </td>
              <?php if ($enable_approval) { ?>
              <td class="text-center">
                <?php if ($quotation['status_id']==$qpending && !($quotationSuperAdmin && $quotationCustomerId == $quotation['customer_id'])) { ?>
                <?php if (!$isPetronasUser) { ?>
                <a id="modal-approve-<?php echo $quotation['quotation_id'];?>" onclick="openModalApprove('<?php echo $quotation['quotation_id'];?>')" data-toggle="tooltip" title="Approve" class="btn btn-info btn-default tb_no_text" ><i class="fa fa-thumbs-o-up" style="font-size:17px;"></i></a>
                <div id="div-approve-<?php echo $quotation['quotation_id'];?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"></button>
                                    <h4 class="modal-title">Approve</h4>
                              </div>
                              <div class="modal-body">
                                  <label class="control-label" >Approve Quotation By:&nbsp;</label><label class="control-label" ><?php echo $quotation['name'];?></label>
                                  <br>
                                  <label class="control-label" >Created On:&nbsp;</label><label class="control-label" ><?php echo $quotation['date_added'];?></label>
                                  <br>
                                  <label class="control-label" >Total:&nbsp;</label><label class="control-label" > <?php echo $quotation['total'];?></label>
                              </div>
                              <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="approveQuotation('<?php echo $quotation['quotation_id'];?>')">Approve</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                    </div>
              </div>
                <?php } ?>
                <a id="modal-approveconfirm-<?php echo $quotation['quotation_id'];?>" onclick="openModalApproveConfirm('<?php echo $quotation['quotation_id'];?>');quotation_id = <?php echo $quotation['quotation_id'];?>; $('[id^=payment-bar]').html('');" data-toggle="tooltip" title="Approve and Confirm" class="btn btn-info btn-default tb_no_text" ><i class="fa fa-check-square-o" style="font-size:17px;"></i></a>
                <div id="div-approveconfirm-<?php echo $quotation['quotation_id'];?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"></button>
                                <h4 class="modal-title">Approve and Confirm</h4>
                          </div>
                          <div class="modal-body">
                              <label class="control-label" >Approve Quotation By:&nbsp;</label><label class="control-label" ><?php echo $quotation['name'];?></label>
                              <br>
                              <label class="control-label" >Created On:&nbsp;</label><label class="control-label" ><?php echo $quotation['date_added'];?></label>
                              <br>
                              <label class="control-label" >Total:&nbsp;</label><label class="control-label" > <?php echo $quotation['total'];?></label>
                              <br>
                              <br>
                              <input type="hidden" name="quotation_id" value="<?php echo $quotation['quotation_id'];?>">
                                <div class="panel panel-default">
                                        <div class="panel-heading">
                                                <h4 class="panel-title">
                                                        <a href="#collapse-payment-method-<?php echo $quotation['quotation_id'];?>" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle btn" onclick="getPaymentData('<?php echo $quotation['quotation_id'];?>')"><?php if($isPetronasUser) { ?>Approve and Confirm <?php } else { ?>Payment Method <?php } ?><i class="fa fa-caret-down"></i></a>
                                                </h4>
                                        </div>
                                        <div class="panel-collapse collapse payment-method" id="collapse-payment-method-<?php echo $quotation['quotation_id'];?>" style="padding-bottom:60px;">
                                                <div id="quotation-payment" class="panel-body"></div>
                                                <div id="payment-bar-<?php echo $quotation['quotation_id'];?>" class="container payment"><?php echo $payment; ?></div>
                                        </div>
                                </div>
                              
                          </div>
                          <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                    </div>
              </div>
                <a id="modal-reject-<?php echo $quotation['quotation_id'];?>" onclick="openModalReject('<?php echo $quotation['quotation_id'];?>')" data-toggle="tooltip" title="Reject" class="btn btn-info btn-default tb_no_text" ><i class="fa fa-thumbs-o-down" style="font-size:17px;"></i></a>
                <div id="div-reject-<?php echo $quotation['quotation_id'];?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"></button>
                                    <h4 class="modal-title">Reject</h4>
                              </div>
                              <div class="modal-body">
                                  <label class="control-label" >Reject Quotation By:&nbsp;</label><label class="control-label" ><?php echo $quotation['name'];?></label>
                                  <br>
                                  <label class="control-label" >Created On:&nbsp;</label><label class="control-label" ><?php echo $quotation['date_added'];?></label>
                                  <br>
                                  <label class="control-label" >Total:&nbsp;</label><label class="control-label" > <?php echo $quotation['total'];?></label>
                                  <br><br>
                                  <label class="control-label" >Reason:&nbsp;</label>
                                  <br><textarea style="width:100%;" id="text-reject-<?php echo $quotation['quotation_id'];?>"></textarea>
                              </div>
                              <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="rejectQuotation('<?php echo $quotation['quotation_id'];?>')">Reject</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                    </div>
              </div>
                <?php } ?>
              </td>
              <?php } ?>
              <td class="text-center">
				<?php echo $quotation['status']; if($quotation['status_id']!=$qcompleted && $quotation['status_id']!=$qexpired && $quotation['status_id']!=$qipay88 && $quotation['status_id']!=$qcredit && $quotation['status_id']!=$qcod && $quotation['status_id']!=$qbanktransfer && $quotation['status_id']!=$qpaypal && $quotation['status_id']!=$qghl) echo ' - '.$quotation['expiry'].' day(s) left'; ?>
                                <?php if ($enable_approval) { ?>
                                <?php if($qreject == $quotation['status_id']) { ?>
                                <br>
                                <?php echo ' - '.$quotation['reject_reason']; ?>
                                <?php }} ?>
			  </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?>
    <?php //echo $column_right; ?>
	<?php $tbData->slotStop(); ?>


<script type="text/javascript">
    
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
                                $( 'input[name=\'payment_method\']' ).trigger( 'change' );
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
                        
                var fd = new FormData();
		fd.append("quotation_id", quotation_id);
		$.ajax({
			url: 'index.php?route=account/quotation/getquotationipay88',
			dataType: 'json',
			type: 'post',
			processData: false,
			contentType: false,
			cache: false,
			data: fd,
			success: function(json) {
				//console.log(json);
				if(json['error_warning'] != ''){
					$('#err-msg').text(json['error_warning']);
					$('#div-danger').show();
				}
				else{
					$("#payment-bar-"+quotation_id).html(json['ipay88']);
                                        $('.payment img').css('display', 'none');
				}
			},				
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
                    
                        
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