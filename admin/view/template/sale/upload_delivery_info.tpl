<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<?php if(!$is_syned_gohoffice) { ?>
        	<button type="button" id="button-delivery" form="form-delivery" formaction="<?php echo $update_configure_delivery; ?>" data-toggle="tooltip" title="Save" class="btn btn-info">Save</button>
      	<?php } ?>
        <a href="<?php echo $invoice; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></a> 
        <a href="<?php echo $shipping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></a> 
        <?php /*<a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> */ ?>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Back" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
  	
    <?php if(isset($success_msg) && $success_msg!="") { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success_msg;?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
    <?php } ?>
    <?php if (isset($error_warning) && $error_warning!="") { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
    <?php } ?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_order; ?></h3>
      </div>
      <div class="panel-body">
       <form method="post" action="" enctype="multipart/form-data" id="form-delivery">
        <table class="table table-bordered">
          <thead>
            <tr>
              <td style="width: 50%;" class="text-left"><?php echo $text_payment_address; ?></td>
              <?php if ($shipping_method) { ?>
              <td style="width: 50%;" class="text-left"><?php echo $text_shipping_address; ?></td>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-left"><?php echo $payment_address; ?></td>
              <?php if ($shipping_method) { ?>
              <td class="text-left"><?php echo $shipping_address; ?></td>
              <?php } ?>
            </tr>
          </tbody>
        </table>
        <table class="table table-bordered">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_product; ?></td>
              <td class="text-left"><?php echo $column_model; ?></td>
              <td class="text-left"><?php echo $column_product_type; ?></td>
              <td class="text-right"><?php echo $column_quantity; ?></td>
              <?php if($config_using_warehouse_module) { ?>
                <td class="text-right"><?php echo $column_brp_warehouse_qty; ?></td>
              <?php } ?>
              <?php //if($configure_delivery) { ?>
                <td class="text-left">Configure Delivery</td>
              <?php //} ?>
              <td class="text-right"><?php echo $column_price; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                <?php if ($option['type'] != 'file') { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } else { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                <?php } ?>
                <?php } ?></td>
              <td class="text-left"><?php echo $product['model']; ?></td>
              <td class="text-left"><?php echo $product['product_type']; ?></td>
              <td class="text-right"><?php echo $product['quantity']; ?></td>
              <?php if($config_using_warehouse_module) { ?>
                  <td class="text-right">
                    <?php if ($product['wms_balance'] == "-" || $product['wms_balance'] == "") { ?>
                        <?php //echo $product['wms_balance'];
							echo "-";
						?>
                    <?php } elseif ($product['wms_balance'] <= 0) { ?>
                        <span class="label label-warning"><?php echo $product['wms_balance'];?></span><?php echo " / ";?><span class="label label-warning"><?php echo $product['availToOrderQty']; ?></span>
                    <?php } elseif ($product['wms_balance'] <= 5) { ?>
                        <span class="label label-danger"><?php echo $product['wms_balance'];?></span><?php echo " / ";?><span class="label label-danger"><?php echo $product['availToOrderQty']; ?></span>
                    <?php } else { ?>
                        <span class="label label-success"><?php echo $product['wms_balance'];?></span><?php echo " / ";?><span class="label label-success"><?php echo $product['availToOrderQty']; ?></span>
                    <?php } ?>
                  </td>
              <?php } ?>
              <?php if($configure_delivery) { ?>
                <td class="text-left">
                    <?php if($product["configure_delivery"]=="None") { ?>
                        <input type="hidden" name="order_product_ids[]" value="<?php echo $product['order_product_id']; ?>" />
                        <input type="hidden" name="wms_product_balances[]" value="<?php echo $product['wms_balance']; ?>" />
                        <select class="btn btn-primary btn-select btn-select-light" name="configure_delivery[]" id="select-delivery" onchange="getIt(this);" style="width:320px;">
                            <?php if($product['product_type']!="Third-Party") { ?>
                                <option value="by_gohoffice" <?php if($product["configure_delivery"]=="Gohoffice") { ?> selected='selected' <?php } ?>>By G.I</option>
                            <?php } ?>
                            <?php /*if($product['wms_balance']>=$product['quantity']) { ?>
                            	<option value="brp_warehouse" <?php if($product["configure_delivery"]=="BRP Warehouse") { ?> selected='selected' <?php } ?>>By BRP Warehouse</option>
                            <?php } else */
								if($product['wms_balance']>0) { ?>
                            	<option value="brp_warehouse_gohoffice" <?php if($product["configure_delivery"]=="BRP Warehouse") { ?> selected='selected' <?php } ?>>By BRP Warehouse &amp; Remainder by G.I</option>
                            <?php } ?>
							
							<?php if($product['has_moreQty']){?>
								<option class="brp_warehouse1" value="brp_warehouse1" <?php if($product["configure_delivery"]=="BRP Warehouse") { ?> selected='selected' <?php } ?> disabled>By BRP Warehouse</option>
							<?php } elseif($product['has_lessQty']){ ?>
								<option class="brp_warehouse2" value="brp_warehouse2" <?php if($product["configure_delivery"]=="BRP Warehouse") { ?> selected='selected' <?php } ?>>By BRP Warehouse</option>
							<?php } else{ ?>
								<option class="brp_warehouse3" value="brp_warehouse3" <?php if($product["configure_delivery"]=="BRP Warehouse") { ?> selected='selected' <?php } ?>>By BRP Warehouse</option>
							<?php } ?>
							
                            <option value="own_arrangement" <?php if($product["configure_delivery"]=="Own Arrangement") { ?> selected='selected' <?php } ?>>Own Arrangement</option>
                        </select>
                    <?php } else { ?>
                    	<?php if($product["configure_delivery"]=="Gohoffice") { ?>
                        	By G.I
                        <?php } else if($product["configure_delivery"]=="BRP Warehouse") { ?>
                            By BRP Warehouse
                        <?php } else if($product["configure_delivery"]=="BRP Warehouse Gohoffice") { ?>
                            By BRP Warehouse &amp; Remainder by G.I
                        <?php } else if($product["configure_delivery"]=="Own Arrangement") { ?>
                        	Own Arrangement
                        <?php } else { ?>
                        	-
                        <?php } ?>
                    <?php } ?>
                </td>
              <?php } else { ?>
              	<td class="text-left">
                    <?php if($product["configure_delivery"]=="Gohoffice") { ?>
                        By G.I
                    <?php } else if($product["configure_delivery"]=="BRP Warehouse") { ?>
                        By BRP Warehouse
                    <?php } else if($product["configure_delivery"]=="BRP Warehouse Gohoffice") { ?>
                        By BRP Warehouse &amp; Remainder by G.I
                    <?php } else if($product["configure_delivery"]=="Own Arrangement") { ?>
                        Own Arrangement
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
              <?php } ?>
              <td class="text-right"><?php echo $product['price']; ?></td>
              <td class="text-right"><?php echo $product['total']; ?></td>
            </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
              <?php if($configure_delivery) { ?>
                <td class="text-left">&nbsp;</td>
              <?php } ?>
              <td class="text-left"><a href="<?php echo $voucher['href']; ?>"><?php echo $voucher['description']; ?></a></td>
              <td class="text-left"></td>
              <td class="text-left"></td>
              <td class="text-left"></td>
              <td class="text-right">1</td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
            </tr>
            <?php } ?>
            <?php foreach ($totals as $total) { ?>
            <tr>
              <?php if($configure_delivery) { ?>
                  <td colspan="7" class="text-right"><?php echo $total['title']; ?></td>
              <?php } else { ?>
                  <td colspan="7" class="text-right"><?php echo $total['title']; ?></td>
              <?php } ?>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php if ($comment) { ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <td><?php echo $text_comment; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $comment; ?></td>
            </tr>
          </tbody>
        </table>
       </form>
       <?php } ?>
        <a href="<?php echo $print_delivery; ?>" target="_blank">
            <button type="button" id="button-print" onclick="javascript:void(0);" class="btn btn-primary"><i class="fa fa-print"></i>  Print&nbsp;&nbsp;</button>
        </a>
        <?php if(isset($products[0]["total_prod_type"]) && isset($products[0]["total_cd_none"]) && $products[0]["total_prod_type"] != $products[0]["total_cd_none"]) { ?>
            <a href="<?php echo $email_delivery; ?>">
                <button type="button" id="button-email" onclick="javascript:void(0);" class="btn btn-primary" style="margin-left:4px;"><i class="fa fa-envelope"></i> Resend Transaction via Email&nbsp;&nbsp;</button>
            </a>
        <?php } ?>
        <?php if(strtolower($delivery_status)=="draft") { ?>
            <a style="margin-left:6px" href="javascript:void(0);" onclick="javascript:confirm('Confirm to Cancel?') ? window.location='<?php echo $cancel_delivery; ?>' : void(0);" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger pull-right"><i class="fa fa-trash-o"></i> <?php echo $button_cancel; ?></a>
          <?php } else if(strtolower($delivery_status)=="-") { ?>
          <?php } else { ?>
            <a style="margin-left:6px" href="javascript:void(0);" onclick="javascript:alert('You are not allow to cancel this transaction.');" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger pull-right"><i class="fa fa-trash-o"></i> <?php echo $button_cancel; ?></a>
          <?php } ?>
          <?php if($upload_delivery_status == "Cancelled") { ?>
              <font color="#FF0000" class="pull-right">Delivery has been previously cancelled. Please duplicate the order to instruct for the same delivery again.</font>
        <?php } ?>
      </div>
    </div>
  </div>
  
  
    
  <script type="text/javascript"><!--
function getIt(wms) {
   if(wms.options[wms.selectedIndex].value=='brp_warehouse2'){
		alert("There is enough inventory in stock but some have been booked by a previous order. The quantity available for delivery will only be updated when previous orders have been shipped. Please restock or cancel the previous order to proceed with this order. Please view the order details to see available amounts and current stocks.");
		$(".brp_warehouse1").prop('disabled', true);
		$('#select-delivery  option[value="by_gohoffice"]').prop("selected", true); 
   }
}
  
$('#button-delivery').on('click', function(e) {
	$('#form-delivery').attr('action', this.getAttribute('formAction'));
	//configure_delivery
	//var selection_values = $("select[name='configure_delivery[]']").map(function(){return $(this).val();}).get();
	//var own_arrangement = selection_values.toString().match(/own_arrangement/g);
	//if(own_arrangement && selection_values.length == own_arrangement.length) {
		//alert("Please select a delivery method other than 'Own Arrangement' on at least one item for this delivery");
	//} else {
		//alert("OKAY");
		$('#form-delivery').submit();
	//}
});

$(document).delegate('#button-ip-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',
		type: 'post',
		data: 'ip=<?php echo $api_ip; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-ip-add').button('loading');
		},
		complete: function() {
			$('#button-ip-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-invoice', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-invoice').button('loading');
		},
		complete: function() {
			$('#button-invoice').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['invoice_no']) {
				$('#invoice').html(json['invoice_no']);

				$('#button-invoice').replaceWith('<button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-cog"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script> 
</div>
<?php echo $footer; ?> 
