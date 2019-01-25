<?php echo $header; ?>

<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('account/order/info.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('account/order/info.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('account/order/info.page_content'); ?>
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

<div class="tb_order_info">

  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left" colspan="2"><?php echo $text_order_detail; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-left" style="width: 50%;"><?php if ($invoice_no) { ?>
          <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
          <?php } ?>
          <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
          <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
        <td class="text-left"><?php if ($payment_method) { ?>
          <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
          <?php } ?>
          <?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
          <?php } ?></td>
      </tr>
    </tbody>
  </table>

  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $text_delivery_status; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td id="div_delivery_status" class="text-left" style="width: 100%;">&nbsp;</td>
      </tr>
    </tbody>
  </table>
    <?php //}
    //$product_id
    //$data_source
    //$model
    //$matching_code
    //echo "<pre>";print_r($data_source);echo "</pre>";
    ?>
    <script type="text/javascript">
	$(document).ready(function() {
		callLoadingImg();
		apiCheckDeliveryStatus('<?php echo $config_unique_brp_partner_id;?>', '<?php echo $unique_order_id?>');
	});
    function callLoadingImg() {
        if(document.getElementById("div_delivery_status")) {
            document.getElementById("div_delivery_status").innerHTML = '<img src="admin/view/image/data_sync/loading.gif" />';
        }
    }
    function apiCheckDeliveryStatus(customer_id, order_id){
        //matching_code = "R04-09|HPB5L24A|HPB3Q11A";
        //matching_code = "R04-09|HPB3Q11A";
        $.ajax({
            url: 'index.php?route=product/product/ajaxAPI',
            type: 'post',
            data: 'apitype=delivery_status&customer_id=' + encodeURIComponent(customer_id) + '&order_id=' + encodeURIComponent(order_id),
            dataType: 'json',
            beforeSend: function() {
                //$('#recurring-description').html('');
            },
            success: function(json) {
                //$('.alert, .text-danger').remove();
                var jsonData = json.data;
				//console.log(jsonData);
				$('#div_delivery_status').html("-");
				if(jsonData.length==1) {
					//console.log(jsonData[0]["Bal"].replace(".0000",""));
					if(jsonData[0]["OrderStatus"]!="") {
						$('#div_delivery_status').html(jsonData[0]["OrderStatus"]);
					} else {
						$('#div_delivery_status').html("-");
					}
				}
            }
        });
    }
    </script>
    
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left" style="width: 50%;"><?php echo $text_payment_address; ?></td>
        <?php if ($shipping_address) { ?>
        <td class="text-left"><?php echo $text_shipping_address; ?></td>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-left"><?php echo $payment_address; ?></td>
        <?php if ($shipping_address) { ?>
        <td class="text-left"><?php echo $shipping_address; ?></td>
        <?php } ?>
      </tr>
    </tbody>
  </table>

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <td class="text-left"><?php echo $column_name; ?></td>
          <td class="text-left"><?php echo $column_model; ?></td>
          <td class="text-right"><?php echo $column_quantity; ?></td>
          <td class="text-right"><?php echo $column_price; ?></td>
          <td class="text-right"><?php echo $column_total; ?></td>
          <?php if ($products) { ?>
          <td style="width: 20px;"></td>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="text-left"><?php echo $product['name']; ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?></td>
          <td class="text-left"><?php echo $product['model']; ?></td>
          <td class="text-right"><?php echo $product['quantity']; ?></td>
          <td class="text-right"><?php echo $product['price']; ?></td>
          <td class="text-right"><?php echo $product['total']; ?></td>
          <td class="text-right" style="white-space: nowrap;"><?php if ($product['reorder']) { ?>
            <a href="<?php echo $product['reorder']; ?>" data-toggle="tooltip" title="<?php echo $button_reorder; ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></a>
            <?php } ?>
            <?php if ($tbData->common['returns_enabled']): ?>
            <a href="<?php echo $product['return']; ?>" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a>
            <?php endif; ?>
          </td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="text-left"><?php echo $voucher['description']; ?></td>
          <td class="text-left"></td>
          <td class="text-right">1</td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <?php if ($products) { ?>
          <td></td>
          <?php } ?>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td colspan="3"></td>
          <td class="text-right"><b><?php echo $total['title']; ?></b></td>
          <td class="text-right"><?php echo $total['text']; ?></td>
          <?php if ($products) { ?>
          <td></td>
          <?php } ?>
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

  <?php if ($histories) { ?>
  <h3><?php echo $text_history; ?></h3>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-left"><?php echo $column_status; ?></td>
        <td class="text-left"><?php echo $column_comment; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($histories) { ?>
      <?php foreach ($histories as $history) { ?>
      <tr>
        <td class="text-left"><?php echo $history['date_added']; ?></td>
        <td class="text-left"><?php echo $history['status']; ?></td>
        <td class="text-left"><?php echo $history['comment']; ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
        <td colspan="3" class="text-center"><?php echo $text_no_results; ?></td>
        <?php else: ?>
        -
        <?php endif; ?>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php } ?>

  <div class="buttons clearfix">
    <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
  </div>

</div>

<script type="text/javascript" data-critical="1">
Array.prototype.forEach.call(document.getElementById('{{widget_dom_id}}').querySelectorAll('td .btn'), function(el) {
    tbUtils.addClass(el, 'tb_no_text');
});
</script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>
