<?php echo $header; ?>


<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('payment/amazon_checkout/address.page_title'); ?>
<h1><?php echo $heading_address; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('payment/amazon_checkout/address.page_content'); ?>
<div style="float: left" id="amazon-address-widget"></div>
<div style="float: left; width: 58%" class="shipping-methods"></div>
<div class="buttons">
  <div class="pull-left">
    <a href="<?php echo $cart; ?>" class="btn btn-primary"><?php echo $text_cart; ?></a>
  </div>
  <div class="pull-right">
    <input class="btn btn-primary" id="continue-button" type="submit" value="<?php echo $text_continue; ?>" />
  </div>
</div>

<input type="hidden" name="addressSelected" value="0" />

<script type="text/javascript"><!--
$(document).ready(function(){
    $('#continue-button').click(function(){
        $('div.warning').remove();
            
        if ($('input[name="addressSelected"]').val() == '0') {
            $('#amazon-address-widget').before('<div class="warning"><?php echo $error_shipping_address ?></div>');
        } else if($('input[name="shipping_method"]:checked').length == 0) {
            $('#amazon-address-widget').before('<div class="warning"><?php echo $error_shipping ?></div>');
        } else {
            $.ajax({
                url: 'index.php?route=payment/amazon_checkout/setshipping',
                type: 'post',
                data: $('input[name="shipping_method"]:checked'),
                dataType: 'json',

                success: function(json) {
                    location = json['redirect'];
                },

                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
            
    });
        
    new CBA.Widgets.AddressWidget({
        merchantId: '<?php echo $merchant_id ?>',
        displayMode: 'edit',
        onAddressSelect: function(widget) {
                
            $('input[name="addressSelected"]').val('1');
                
            $('div.warning').remove();
            $('div.shipping-methods').html('');
                
            $.get('<?php echo $shipping_quotes ?>', {}, function(data) {
                    
                $('.shipping-methods').html('');
                
                if (data.error) {
                    $('#amazon-address-widget').before('<div class="warning">' + data.error + '</div>');
                } else if (data.quotes) {
                    var html = '';

                    $.each(data.quotes, function(code, shippingMethod){
                        html += '<p><strong>' + shippingMethod.title + '</strong></p>';
                            
                        $.each(shippingMethod.quote, function(i, quote){
                            html += '<div class="radio">';
                            html += '<label>';
                                
                            if (data.selected == quote.code) {
                                html += '<input type="radio" name="shipping_method" value="' + quote.code + '" id="' + quote.code + '" checked="checked" />';
                            } else {
                                html += '<input type="radio" name="shipping_method" value="' + quote.code + '" id="' + quote.code + '" />';
                            }
                                
                            html += quote.title + ' - ';
                            html += quote.text;
                            html += '</label>';
                            html += '</div>';
                                
                        });
                            
                    });
                        
                    $('.shipping-methods').html(html);

                    if ($('input[name="shipping_method"]:checked').length == 0) {
                        $('input[name="shipping_method"]:first').attr('checked', 'checked');
                    }
                }
                
            }, 'json');
        }
    }).render('amazon-address-widget');
});
//--></script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>