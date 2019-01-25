<?php echo $header; ?>


<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('payment/amazon_checkout.page_title'); ?>
<h1><?php echo $heading_payment; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('payment/amazon_checkout.page_content'); ?>
<div style="float: left" id="amazon-wallet-widget"></div>
      
<div style="clear: both;"></div>
      
<div class="buttons">
  <div class="pull-left">
    <a href="<?php echo $back; ?>" class="btn btn-primary"><?php echo $text_back; ?></a>
  </div>
  <div class="pull-right">
    <input class="btn btn-primary" id="continue-button" type="submit" value="<?php echo $text_continue; ?>" />
  </div>
</div>
<input type="hidden" name="payment_method" value="" />
      
<script type="text/javascript"><!--
$(document).ready(function(){
        
    $('#continue-button').click(function(){
        $('div.warning').remove();
            
        if ($("input[name='payment_method']").val() == '1') {
            location = '<?php echo $continue ?>';
        } else {
            $('#amazon-wallet-widget').before('<div class="warning"><?php echo $error_payment_method ?></div>');
        }
    });
        
    new CBA.Widgets.WalletWidget({
        merchantId: '<?php echo $merchant_id; ?>',
        displayMode: 'edit',
        onPaymentSelect: function(widget){
            $("input[name='payment_method']").val('1');
        }
            
    }).render('amazon-wallet-widget');
        
});
//--></script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>