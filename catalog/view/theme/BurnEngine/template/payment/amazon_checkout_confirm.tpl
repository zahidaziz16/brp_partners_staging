<?php echo $header; ?>


<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('payment/amazon_checkout/confirm.page_title'); ?>
<h1><?php echo $heading_confirm; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('payment/amazon_checkout/confirm.page_content'); ?>
<form class="payment-form" method="POST" action="<?php echo $process_order ?>">
  <div style="float: left" id="amazon-address-widget"></div>
  <div style="float: left; margin-left: 15px" id="amazon-wallet-widget"></div>
  <div style="clear: both;"></div>
</form>
        
  <div class="checkout-product cart-info" style="margin-top: 15px">
    <table class="table">
      <thead>
        <tr>
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
            <td class="name"><?php echo $product['name']; ?>
                <?php foreach ($product['option'] as $option) { ?>
                    <br />
                    &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?></td>
            <td class="model"><?php echo $product['model']; ?></td>
            <td class="quantity"><?php echo $product['quantity']; ?></td>
            <td class="price"><?php echo $product['price']; ?></td>
            <td class="total"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        </tbody>
            
        <tfoot>
            <?php foreach ($totals as $total) { ?>
                <tr>
                    <td colspan="4" class="price"><b><?php echo $total['title']; ?>:</b></td>
                    <td class="total"><?php echo $total['text']; ?></td>
                </tr>
            <?php } ?>
        </tfoot>
    </table>
</div>
    
<div class="buttons">
  <div class="pull-left">
    <a href="<?php echo $back; ?>" class="btn btn-primary"><?php echo $text_back; ?></a>
  </div>
  <div class="pull-right">
    <input class="btn btn-primary" id="confirm-button" type="submit" value="<?php echo $text_confirm; ?>" />
  </div>
</div>
</div>
      
<script type="text/javascript"><!--
$(document).ready(function(){
    new CBA.Widgets.AddressWidget({
        merchantId: '<?php echo $merchant_id; ?>',
        displayMode: 'read'
    }).render('amazon-address-widget');
        
    new CBA.Widgets.WalletWidget({
        merchantId: '<?php echo $merchant_id; ?>',
        displayMode: 'read'
    }).render('amazon-wallet-widget');
        
    $('#confirm-button').click(function(){
        if ($(this).attr('disabled') != 'disabled') {
            $('.payment-form').submit();
        }
            
        $(this).attr('disabled', 'disabled');
    });
});
//--></script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>