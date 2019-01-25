<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('checkout/cart.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('checkout/cart.page_title'); ?>
<h1><?php echo $heading_title; ?>
  <?php if ($weight) { ?>
  &nbsp;(<?php echo $weight; ?>)
  <?php } ?>
</h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStartSystem('checkout/cart.page_content'); ?>
<?php if ($attention) { ?>
<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
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

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="cart_form">
  <div class="cart-info">
    <table class="table">
      <thead>
        <tr>
          <td class="image"><?php echo $column_image; ?></td>
          <td class="name"><?php echo $column_name; ?></td>
          <td class="model"><?php echo $column_model; ?></td>
          <td class="quantity"><?php echo $column_quantity; ?></td>
          <td class="price"><?php echo $column_price; ?></td>
          <td class="total"><?php echo $column_total; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="image"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
          <td class="name"><a id="model-<?php echo str_replace(' ','-',$product['model']); ?>" href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
            <?php if (!$product['stock']) { ?>
            <span class="text-danger">***</span>
            <?php } ?>
            <?php if ($product['option']) { ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?>
            <?php } ?>
            <?php if ($product['reward']) { ?>
            <br />
            <small><?php echo $product['reward']; ?></small>
            <?php } ?>
            <?php if ($product['recurring']) { ?>
            <br />
            <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
            <?php } ?></td>
          <td class="model"><?php echo $product['model']; ?></td>
          <td class="quantity"><div class="input-group btn-block" style="max-width: 200px;">
                    <?php if ($tbData->OcVersionGte('2.1.0.0')): ?>
                    <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control" />
                    <?php else: ?>
                    <input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control" />
                    <?php endif; ?>
                    <span class="input-group-btn">
                    <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
                    <?php if ($tbData->OcVersionGte('2.1.0.0')): ?>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><i class="fa fa-times-circle"></i></button></span></div>
                    <?php else: ?>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['key']; ?>');"><i class="fa fa-times-circle"></i></button></span></div>
                    <?php endif; ?>
          </td>
          <td class="price"><?php echo $product['price']; ?></td>
          <td class="total"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $vouchers) { ?>
        <tr>
          <td class="image"></td>
          <td class="name"><?php echo $vouchers['description']; ?></td>
          <td class="model"></td>
          <td class="quantity"><div class="input-group btn-block" style="max-width: 200px;">
                    <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
                    <span class="input-group-btn"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="voucher.remove('<?php echo $vouchers['key']; ?>');"><i class="fa fa-times-circle"></i></button></span></div></td>
          <td class="price"><?php echo $vouchers['amount']; ?></td>
          <td class="total"><?php echo $vouchers['amount']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</form>

<?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
<?php if ($modules) { ?>
<h2><?php echo $text_next; ?></h2>
<p><?php echo $text_next_choice; ?></p>
<div class="panel-group" id="accordion">
  <?php foreach ($modules as $module) { ?>
  <?php echo $module; ?>
  <?php } ?>
</div>
<?php } ?>
<?php else: ?>
<?php if ($coupon || $voucher || $reward || $shipping) { ?>
<h2><?php echo $text_next; ?></h2>
<p><?php echo $text_next_choice; ?></p>
<div class="panel-group" id="accordion"><?php echo $coupon; ?><?php echo $voucher; ?><?php echo $reward; ?><?php echo $shipping; ?></div>
<?php } ?>
<?php endif; ?>
<div class="cart-total">
  <table id="total">
    <?php foreach ($totals as $total) { ?>
    <tr>
      <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
      <td class="text-right"><?php echo $total['text']; ?></td>
    </tr>
    <?php } ?>
  </table>
</div>

<div class="buttons">
	<div class="pull-left"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a></div>
	<div class="pull-right">
	<div>
        <?php if(!$isPetronasUser) { ?>
	<div style="vertical-align: top; margin-bottom: 5px;">
	<a id="checkout-btn" class="btn btn-primary" style= "width: 100%;"><?php echo $button_checkout; ?></a>
	<img id="loadimg" style="display:none;" src="admin/view/image/data_sync/loading.gif" />
        </div>
        <?php } ?>
	<?php if($logged == 1 && $quotation_permission){ ?><br>
	<div style="vertical-align: bottom;">
	<a class="btn btn-primary" id="quotation-btn" style="width: 100%;"><?php if(!$isPetronasUser) { ?>Get Quotation<?php }else { ?><?php echo $button_checkout; ?><?php } ?></a>
	</div>
	<?php } else if($logged == 1 && !$quotation_permission){ ?><br>
	<div style="vertical-align: bottom;">
	You do no have the permission to proceed with this order.
	</div>
        <?php } ?>
	</div>
	</div>
	
</div>

<script type="text/javascript" data-critical="1">
Array.prototype.forEach.call(document.getElementById('{{widget_dom_id}}').querySelectorAll('td .btn-primary'), function(el) {
  tbUtils.removeClass(el, 'btn-primary');
  tbUtils.addClass(el, 'btn-default tb_no_text');
});
Array.prototype.forEach.call(document.getElementById('{{widget_dom_id}}').querySelectorAll('td .btn-danger'), function(el) {
  tbUtils.removeClass(el, 'btn-primary');
  tbUtils.addClass(el, 'tb_no_text');
});
Array.prototype.forEach.call(document.getElementById('{{widget_dom_id}}').querySelectorAll('.panel-title'), function(el) {
  tbUtils.addClass(el, 'tb_bg_str_2');
});
</script>

<script type="text/javascript">


document.getElementById("checkout-btn").onclick = function() {
        <?php if($onWarning){ ?>
            $.ajax({
                    url: 'index.php?route=checkout/cart/checkStock',
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function() {
                            //$('#button-cart').button('loading');
                            $('#checkout-btn').hide();
                            $('#loadimg').show();
                            //document.getElementById("stock_count1").innerHTML = '<img id="loadimg" src="admin/view/image/data_sync/loading.gif" />';
                            //$(window).ploading({action: 'show'});
                    },
                    complete: function() {
                            //$('#button-cart').button('reset');
                            $('#checkout-btn').show();
                            $('#loadimg').hide();
                            $(window).ploading({action: 'hide'});
                    },
                    success: function(json) {
                        console.log(json['error_warning']);
                            if (json['error_warning']!= '') {
                                    
                                    for (i = 0; i < json['outOfStock'].length; i++) {
                                        
                                        if(!$('#model-'+json['outOfStock'][i]).next('span').length) {
                                        $( '<span class="text-danger">***</span>' ).insertAfter( '#model-'+json['outOfStock'][i] );
                                    }
                                    } 
                                    
                                    
                                    $('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+json['error_warning']+' <button type="button" class="close" data-dismiss="alert">&times;</button></div>').insertBefore('#cart_form');
                                    $('.text-danger').parent().parent().addClass('has-error');
                                    <?php if($onStockCheckout){ ?>
                                        var r = confirm("Some products you are ordering are not available in the desired quantity or not in stock! It will take some time to delivery the products to you. Do you wish to proceed?");
                                        if (r == true) {
                                            location.href = "<?php echo $checkout; ?>";
                                        } else {
                                            
                                        } 
                                        
                                    <?php }else { ?>
                                        alert('The products you are ordering are not available in the desired quantity or not in stock!');
                                    <?php } ?>    
                                    
                            }else {
                                location.href = "<?php echo $checkout; ?>";
                            }

                            if (json['success']) {
                                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                            }
                    },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            });
        <?php }else { ?>
            location.href = "<?php echo $checkout; ?>";
        <?php } ?>
};
</script>
<script type="text/javascript">
$('#cart_form .quantity input[name*="quantity"]').bind('change', function() {
  $('#cart_form').submit();
});

<?php if($logged == 1){ ?>
	document.getElementById("quotation-btn").onclick = function() {
		<?php if($error_warning==""){ ?>
			location.href = "<?php echo $quotation; ?>";
		<?php } ?>
    };
	<?php } ?>
</script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>