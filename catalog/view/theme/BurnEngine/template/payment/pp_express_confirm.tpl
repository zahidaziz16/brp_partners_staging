<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('payment/pp_express/expressConfirm.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('payment/pp_express/expressConfirm.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('payment/pp_express/expressConfirm.page_content'); ?>
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
        
<?php if ($coupon || $voucher || $reward) { ?>
<div class="panel-group" id="accordion"><?php echo $coupon; ?><?php echo $voucher; ?><?php echo $reward; ?></div>
<?php } ?>

<span class="clear tb_sep border"></span>

<?php if($has_shipping) { ?>
<?php if(!isset($shipping_methods)) { ?>
<div class="warning"><?php echo $error_no_shipping ?></div>
<?php } else { ?>
<form action="<?php echo $action_shipping; ?>" method="post" id="shipping_form">
  <div class="panel-body">
    <?php foreach ($shipping_methods as $shipping_method) { ?>
    <p><strong><?php echo $shipping_method['title']; ?></strong></p>
    <?php if (!$shipping_method['error']) { ?>
    <?php foreach ($shipping_method['quote'] as $quote) { ?>
    <div class="radio">
      <label>
        <?php if ($quote['code'] == $code || !$code) { ?>
        <?php $code = $quote['code']; ?>
        <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
        <?php } else { ?>
        <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
        <?php } ?>
        <?php echo $quote['title']; ?> </label>
    </div>
    <?php } ?>
    <?php } else { ?>
    <div class="warning"><?php echo $shipping_method['error']; ?></div>
    <?php } ?>
    <?php } ?>
  </div>
</form>
<?php } ?>
<?php } ?>

<span class="clear tb_sep"></span>

<div class="checkout-product cart-info">
  <table class="table">
    <thead>
      <tr>
        <th class="name"><?php echo $column_name; ?></th>
        <th class="model"><?php echo $column_model; ?></th>
        <th class="quantity"><?php echo $column_quantity; ?></th>
        <th class="price"><?php echo $column_price; ?></th>
        <th class="total"><?php echo $column_total; ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="name">
          <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <div>
            <?php foreach ($product['option'] as $option) { ?>
            <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
            <?php } ?>
            <?php if($product['recurring']): ?>
            <small><strong><?php echo $text_recurring_item; ?>:</strong> (<?php echo $product['recurring_description'] ?>)</small>
            <?php endif; ?>
          </div>
        </td>
        <td class="model"><?php echo $product['model']; ?></td>
        <td class="quantity"><?php echo $product['quantity']; ?></td>
        <td class="price"><?php echo $product['price']; ?></td>
        <td class="total"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="name"><?php echo $voucher['description']; ?></td>
        <td class="model"></td>
        <td class="quantity">1</td>
        <td class="price"><?php echo $voucher['amount']; ?></td>
        <td class="total"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
  
<div class="cart-total tb_sep clearfix">
  <table id="total">
    <?php foreach ($totals as $total) { ?>
    <tr>
      <td class="right"><b><?php echo $total['title']; ?>:</b></td>
      <td class="right"><?php echo $total['text']; ?></td>
    </tr>
    <?php } ?>
  </table>
</div>

<div class="buttons">
  <div class="pull-right"><a href="<?php echo $action_confirm; ?>" class="btn btn-primary"><?php echo $button_confirm; ?></a></div>
</div>

<script type="text/javascript">
$('input[name=\'shipping_method\']').change(function() {
    $('#shipping_form').submit();
});

$('input[name=\'next\']').bind('change', function() {
    $('.cart-discounts > div').hide();

    $('#' + this.value).show();
});
</script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>