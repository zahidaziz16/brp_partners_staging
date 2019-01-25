<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Back" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <br>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-edit"></i> Change price and quantity from below</h3>
      </div>
      <div class="panel-body">
        <div class="tab-content">
            <table class="table table-bordered savedetails">
              <thead>
                <tr>
                   <td class="text-left"><?php echo $column_image; ?></td>
                  <td class="text-left"><?php echo $column_product; ?></td>
                  <td class="text-left"><?php echo $column_model; ?></td>
                  <td class="text-right"><?php echo $column_price; ?></td>
                   <td class="text-right"><?php echo $column_percent; ?></td>
                  <td class="text-right"><?php echo $column_quantity; ?></td>
                  <td class="text-right"><?php echo $column_qprice; ?></td>
                  <td class="text-right" width="10%"><?php echo $column_total; ?></td>
                </tr>
              </thead>
              <tbody>
                 <?php $product_row = 0; ?>
                <?php foreach ($products as $product) { ?>
                <tr class="rowvalue<?php echo $product_row; ?> productstocart">
                  <input type="hidden"  name="product[<?php echo $product_row; ?>][product_id]" value="<?php echo $product['product_id']; ?>">
                  <td class="text-left">
                  <?php if ($product['thumb']) { ?>
                    <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                    <?php } ?>
                 </td>
                  <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                    <?php foreach ($product['option'] as $option) { ?>
                    <br />
                    <?php if ($option['type'] != 'file') { ?>
                    &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                    <?php } else { ?>
                    &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                    <?php } ?>
                    <?php if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'image') { ?>
                      <input type="hidden" name="product[<?php echo $product_row; ?>][option][<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['product_option_value_id']; ?>" />
                      <?php } ?>
                      <?php if ($option['type'] == 'checkbox') { ?>
                      <input type="hidden" name="product[<?php echo $product_row; ?>][option][<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option['product_option_value_id']; ?>" />
                      <?php } ?>
                      <?php if ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') { ?>
                      <input type="hidden" name="product[<?php echo $product_row; ?>][option][<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" />
                      <?php } ?>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['model']; ?></td>
                  <td class="text-right "><?php echo $currency_symbol; ?><?php echo $product['ogprice']; ?></td>
                  <input type="hidden" name="product[<?php echo $product_row; ?>][price]" class="ogprice<?php echo $product_row; ?>" value="<?php echo $product['ogprice']; ?>">
                  <td class="text-right">
                    <select onchange="" name="product[<?php echo $product_row; ?>][price_prefix]" class="prefix<?php echo $product_row; ?> form-control" readonly>
                      <?php if ($product['price_prefix'] == 'n') { ?>
                      <option value="n" selected="selected">New Price</option>
                      <?php } else { ?>
                      <option value="n">New Price</option>
                      <?php } ?>
                      <?php if ($product['price_prefix'] == 'p') { ?>
                      <option value="p" selected="selected">Percentage Discount</option>
                      <?php } else { ?>
                      <option value="p">Percentage Discount</option>
                      <?php } ?>
                      <?php if ($product['price_prefix'] == 'f') { ?>
                      <option value="f" selected="selected">Fixed Discount</option>
                      <?php } else { ?>
                      <option value="f">Fixed Discount</option>
                      <?php } ?>
                    </select>
                    <input type="text" onkeyup="" name="product[<?php echo $product_row; ?>][percent]" value="<?php echo $product['percent']; ?>" placeholder="" class="form-control percent<?php echo $product_row; ?>" readonly/>
                  </td>
                  <td class="text-right"><input type="text" onkeyup="" name="product[<?php echo $product_row; ?>][quantity]" value="<?php echo $product['quantity']; ?>" class="form-control quantity<?php echo $product_row; ?>" readonly/></td>
                  <td class="text-right quoteprice<?php echo $product_row; ?>"><?php echo $currency_symbol; ?><?php echo $product['qprice']; ?></td>
                    <input type="hidden" class="qprice<?php echo $product_row; ?>" name="product[<?php echo $product_row; ?>][qprice]" value="<?php echo $product['qprice']; ?>">
                  <td class="text-right producttotal quotetotal<?php echo $product_row; ?>"><?php echo $currency_symbol; ?><?php echo $product['total']; ?></td>
                  <input type="hidden" class="qtotal<?php echo $product_row; ?>" name="product[<?php echo $product_row; ?>][total]" value="<?php echo $product['total']; ?>">
                </tr>
                <?php $product_row++; ?>
                <?php } ?>
                <?php foreach ($vouchers as $voucher) { ?>
                <tr>
                  <td class="text-left"><a href="<?php echo $voucher['href']; ?>"><?php echo $voucher['description']; ?></a></td>
                  <td class="text-left"></td>
                  <td class="text-right">1</td>
                  <td class="text-right"><?php echo $voucher['amount']; ?></td>
                  <td class="text-right"><?php echo $voucher['amount']; ?></td>
                </tr>
                <?php } ?>
                <?php foreach ($totals as $total) { ?>
                <tr class="total">
                  <td colspan="7" class="text-right"><?php echo $total['title']; ?>:</td>
                  <td class="text-right"><?php echo $total['text']; ?></td>
                </tr>
                <?php } ?>
                <tr class="calculatetotal" style="display:none;">
                   <td colspan="7" class="text-right"></td>
                   <td>
                    <button onclick="updateprice();" id="button-savetotal" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_savetotal; ?></button>
                   </td>
                </tr>
              </tbody>
            </table>
            <br>
            <fieldset>
              <legend><?php echo $text_quotationhistory; ?></legend>
              <form class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                  <div class="col-sm-9">
                    <select name="quotation_status_id" id="input-order-status" class="form-control">
                      <?php foreach ($quotation_statuses as $quotation_statuses) { ?>
                      <?php if ($quotation_statuses['quotation_status_id'] == $quotation_status_id) { ?>
                      <option value="<?php echo $quotation_statuses['quotation_status_id']; ?>" selected="selected"><?php echo $quotation_statuses['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $quotation_statuses['quotation_status_id']; ?>"><?php echo $quotation_statuses['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
				<!-- Remove payment attachment
				<div class="form-group">
                  <label class="col-sm-3 control-label" for="input-attachment">Payment Attachment</label>
                  <div class="col-sm-9">
					<?php //if($attachment_url != ''){ ?>
						<label class="control-label" style="font-weight:normal;"><a href="<?php// echo HTTP_UPLOAD.'quotation/'.$attachment_url; ?>" target="blank">View</a></label>
					<?php// } else{ ?>
						<label class="control-label" style="font-weight:normal; color:#555;">N/A</label>
					<?php// } ?>
                  </div>
                </div>
				-->
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
                  <div class="col-sm-9">
                    <label class="control-label" style="font-weight:normal;"><input type="checkbox" name="notify" value="1" id="input-notify" checked /></label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                  <div class="col-sm-9">
                    <textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
                  </div>
                </div>
              </form>
              <div class="text-right">
                <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_savequotation; ?></button>
              </div>
            </fieldset>
            <input type="hidden" name="currency" class="currency" value="<?php echo $currency_code; ?>">
            <input type="hidden" name="store_id" class="store_id" value="<?php echo $store_id; ?>">
          </div>
        </div>
      </div>
  </div>
</div>
 <script type="text/javascript">

 function calculate(id){
  $('tr.total').html("").fadeOut(400);
      var quantity = $(".quantity"+id).val();
    if(quantity == ""){
      quantity = 0;
    }
    var percent = $(".percent"+id).val();
    if(percent == ""){
      percent = 0;
    }
    var prefix = $(".prefix"+id+" option:selected").val();

    var price = $(".ogprice"+id).val().replace(/[A-Za-z$-]/g, "");
    console.log("quantity="+quantity);console.log("percent="+percent);console.log("prefix="+prefix);console.log("id="+id);
    var symbol = '<?php echo $currency_symbol ?>';
    var quoteprice = getquoteprice(price,percent,prefix);
    $(".quoteprice"+id).html(symbol+quoteprice);
    $(".qprice"+id).val(quoteprice);
    var quotetotal = getquotetotal(quoteprice,quantity);
    $(".quotetotal"+id).html(symbol+quotetotal);
    $(".qtotal"+id).val(quotetotal);
   // calculatesubtotal();
   $('.calculatetotal').show();

  };
  </script>
  <script type="text/javascript">

  function getquoteprice(price,percent,prefix) {
    if(prefix == "n") {
      var quoteunitprice = parseFloat(percent);
    } else if(prefix == "p") {
      var discount = parseFloat((price*percent)/100);
      var quoteunitprice = parseFloat(price)-parseFloat(discount);
    } else {
      var quoteunitprice = parseFloat(price)-parseFloat(percent);
    }
    quoteunitprice.toFixed(2);
    return quoteunitprice;
  };
   function getquotetotal(price,quantity) {
    var quotetotal = (parseFloat(price)*parseFloat(quantity)).toFixed(2);
    return quotetotal;
  };
</script>
<script type="text/javascript">
  function updateprice() {
  $.ajax({
    url: 'index.php?route=sale/quotation/api&token=<?php echo $token; ?>&api=api/quotation/setall&quotation_id=<?php echo $quotation_id; ?>',
    type: 'post',
    dataType: 'json',
    data: $('.productstocart input[type=\'text\'],.productstocart input[type=\'hidden\'],.productstocart select'),
    beforeSend: function() {
      $('#button-savetotal').button('Calculating...');     
    },
    complete: function() {
      $('#button-savetotal').button('Completed'); 
    },
    success: function(json) {
      $('.alert').remove();
      
      if (json['error']) {
        $('.savedetails').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      } 
    
      if (json['success']) {
        $('.savedetails').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          html = "";
         if (json['totals']) {
          for (i in json['totals']) {
            total = json['totals'][i];
            
            html += '<tr class="total">';
            html += '  <td class="text-right" colspan="7">' + total['title'] + ':</td>';
            html += '  <td class="text-right">' + total['text'] + '</td>';
            html += '</tr>';
          }
         
        }
        
        if (!json['totals'] && !json['products'] && !json['vouchers']) {        
          html += '<tr>';
          html += '  <td colspan="5" class="text-center">No results</td>';
          html += '</tr>';  
        }
       
        $('.calculatetotal').before(html).show();
        $('.calculatetotal').fadeOut(400);
        $('html, body').animate({ scrollTop: 0 }, 'slow');
      }     
     
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
};

  </script>
  <script type="text/javascript">
  $('#button-history').on('click', function() {
  /*if(typeof verifyStatusChange == 'function'){
    if(verifyStatusChange() == false){
      return false;
    }else{
      addOrderInfo();
    }
  }else{
    addOrderInfo();
  }
  
  index.php?route=sale/quotation/api&token=<?php echo $token; ?>&api=api/quotation/history&quotation_id=<?php echo $quotation_id; ?>
  */

  $.ajax({
    url: 'index.php?route=sale/quotation/api&token=<?php echo $token; ?>&api=api/quotation/history&quotation_id=<?php echo $quotation_id; ?>',
    type: 'post',
    dataType: 'json',
    data: 'quotation_status_id=' + encodeURIComponent($('select[name=\'quotation_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
    beforeSend: function() {
      $('#button-history').button('loading');     
    },
    complete: function() {
      $('#button-history').button('reset'); 
    },
    success: function(json) {
      $('.alert').remove();
      
      if (json['error']) {
        $('.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      } 
    
      if (json['success']) {
        $('textarea[name=\'comment\']').val('');
         $('.savedetails').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }  
      $('html, body').animate({ scrollTop: 0 }, 'slow');   
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
  
  /*function addOrderInfo(){
    var status_id = $('select[name="quotation_status_id"]').val();

    $.ajax({
      url: 'index.php?route=extension/openbay/addorderinfo&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>&status_id='+status_id,
      type: 'post',
      dataType: 'html',
      data: $(".openbay-data").serialize()
    });
  }*/
});
  </script>
<?php echo $footer; ?>