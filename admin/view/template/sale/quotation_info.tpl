<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <br>
      <small>Version : 3.01</small>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-order" data-toggle="tab"><?php echo $tab_order; ?></a></li>
          <li><a href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li>
          <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-order">
            <table class="table table-bordered">
              <tr>
                <td><?php echo $text_quotation_id; ?></td>
                <td>#<?php echo $quotation_id; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_store_name; ?></td>
                <td><?php echo $store_name; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_store_url; ?></td>
                <td><a href="<?php echo $store_url; ?>" target="_blank"><?php echo $store_url; ?></a></td>
              </tr>
              <?php if ($customer) { ?>
              <tr>
                <td><?php echo $text_customer; ?></td>
                <td><a href="<?php echo $customer; ?>" target="_blank"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>
              </tr>
              <?php } else { ?>
              <tr>
                <td><?php echo $text_customer; ?></td>
                <td><?php echo $firstname; ?> <?php echo $lastname; ?></td>
              </tr>
              <?php } ?>
              <?php if ($customer_group) { ?>
              <tr>
                <td><?php echo $text_customer_group; ?></td>
                <td><?php echo $customer_group; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_email; ?></td>
                <td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
              </tr>
              <tr>
                <td><?php echo $text_telephone; ?></td>
                <td><?php echo $telephone; ?></td>
              </tr>
              <?php if ($fax) { ?>
              <tr>
                <td><?php echo $text_fax; ?></td>
                <td><?php echo $fax; ?></td>
              </tr>
              <?php } ?>
              <?php foreach ($account_custom_fields as $custom_field) { ?>
              <tr>
                <td><?php echo $custom_field['name']; ?>:</td>
                <td><?php echo $custom_field['value']; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_total; ?></td>
                <td><?php echo $total; ?></td>
              </tr>
              <?php if ($quotation_status) { ?>
              <tr>
                <td><?php echo $text_quotation_status; ?></td>
                <td id="order-status"><?php echo $quotation_status; ?></td>
              </tr>
              <?php } ?>
              <?php if ($comment) { ?>
              <tr>
                <td><?php echo $text_comment; ?></td>
                <td><?php echo $comment; ?></td>
              </tr>
              <?php } ?>
              <?php if ($affiliate) { ?>
              <tr>
                <td><?php echo $text_affiliate; ?></td>
                <td><a href="<?php echo $affiliate; ?>"><?php echo $affiliate_firstname; ?> <?php echo $affiliate_lastname; ?></a></td>
              </tr>
              <tr>
                <td><?php echo $text_commission; ?></td>
                <td><?php echo $commission; ?>
                  <?php if (!$commission_total) { ?>
                  <button id="button-commission-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_commission_add; ?></button>
                  <?php } else { ?>
                  <button id="button-commission-remove" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_remove; ?></button>
                  <?php } ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip) { ?>
              <tr>
                <td><?php echo $text_ip; ?></td>
                <td><?php echo $ip; ?></td>
              </tr>
              <?php } ?>
              <?php if ($user_agent) { ?>
              <tr>
                <td><?php echo $text_user_agent; ?></td>
                <td><?php echo $user_agent; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_date_added; ?></td>
                <td><?php echo $date_added; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_date_modified; ?></td>
                <td><?php echo $date_modified; ?></td>
              </tr>
            </table>
          </div>
          <div class="tab-pane" id="tab-product">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td class="text-left"><?php echo $column_image; ?></td>
                  <td class="text-left"><?php echo $column_product; ?></td>
                  <td class="text-left"><?php echo $column_model; ?></td>
                  <td class="text-right"><?php echo $column_quantity; ?></td>
                  <td class="text-right"><?php echo $column_price; ?></td>
                  <td class="text-right"><?php echo $column_qprice; ?></td>
                  <td class="text-right"><?php echo $column_total; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-center"><?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                  <?php } ?></td>
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
                  <td class="text-right"><?php echo $product['quantity']; ?></td>
                  <td class="text-right"><?php echo $product['price']; ?></td>
                  <td class="text-right"><?php echo $product['qprice']; ?></td>
                  <td class="text-right"><?php echo $product['total']; ?></td>
                </tr>
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
                <tr>
                  <td colspan="6" class="text-right"><?php echo $total['title']; ?>:</td>
                  <td class="text-right"><?php echo $total['text']; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="tab-history">
            <div id="history"></div>
            <br />
            <fieldset>
              <legend><?php echo $text_history; ?></legend>
              <form class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                  <div class="col-sm-10">
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
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
                  <div class="col-sm-10">
                    <input type="checkbox" name="notify" value="1" id="input-notify" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                  <div class="col-sm-10">
                    <textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
                  </div>
                </div>
              </form>
              <div class="text-right">
                <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
              </div>
            </fieldset>
          </div>
         
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$(document).delegate('#button-invoice', 'click', function() {
  $.ajax({
    url: 'index.php?route=quotation/quotation/createinvoiceno&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>',
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
        $('#tab-order').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }
      
      if (json['invoice_no']) {
        $('#button-invoice').replaceWith(json['invoice_no']);
      }
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$(document).delegate('#button-reward-add', 'click', function() {
  $.ajax({
    url: 'index.php?route=sale/quotation/addreward&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>',
    type: 'post',
    dataType: 'json',
    beforeSend: function() {
      $('#button-reward-add').button('loading');
    },
    complete: function() {
      $('#button-reward-add').button('reset');
    },                  
    success: function(json) {
      $('.alert').remove();
            
      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }
      
      if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
        
        $('#button-reward-add').replaceWith('<button id="button-reward-remove" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_reward_remove; ?></button>');
      }
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$(document).delegate('#button-reward-remove', 'click', function() {
  $.ajax({
    url: 'index.php?route=sale/quotation/removereward&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>',
    type: 'post',
    dataType: 'json',
    beforeSend: function() {
      $('#button-reward-remove').button('loading');
    },
    complete: function() {
      $('#button-reward-remove').button('reset');
    },        
    success: function(json) {
      $('.alert').remove();
            
      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }
      
      if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
        
        $('#button-reward-remove').replaceWith('<button id="button-reward-add" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>');
      }
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$(document).delegate('#button-commission-add', 'click', function() {
  $.ajax({
    url: 'index.php?route=sale/quotation/addcommission&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>',
    type: 'post',
    dataType: 'json',
    beforeSend: function() {
      $('#button-commission-add').button('loading');
    },
    complete: function() {
      $('#button-commission-add').button('reset');
    },      
    success: function(json) {
      $('.alert').remove();
            
      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }
      
      if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                
        $('#button-commission-add').replaceWith('<button id="button-commission-remove" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_remove; ?></button>');
      }
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$(document).delegate('#button-commission-remove', 'click', function() {
  $.ajax({
    url: 'index.php?route=sale/quotation/removecommission&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>',
    type: 'post',
    dataType: 'json',
    beforeSend: function() {
      $('#button-commission-remove').button('loading');
    
    },
    complete: function() {
      $('#button-commission-remove').button('reset');
    },    
    success: function(json) {
      $('.alert').remove();
            
      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }
      
      if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
        
        $('#button-commission-remove').replaceWith('<button id="button-commission-add" class="btn btn-success btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_add; ?></button>');
      }
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$('#history').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();
  
  $('#history').load(this.href);
});     

$('#history').load('index.php?route=sale/quotation/history&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>');

$('#button-history').on('click', function() {
  if(typeof verifyStatusChange == 'function'){
    if(verifyStatusChange() == false){
      return false;
    }else{
      addOrderInfo();
    }
  }else{
    addOrderInfo();
  }

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
        $('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      } 
    
      if (json['success']) {
        $('#history').load('index.php?route=sale/quotation/history&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>');
        
        $('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        
        $('textarea[name=\'comment\']').val('');
        
        $('#order-status').html($('select[name=\'quotation_status_id\'] option:selected').text());      
      }     
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

function changeStatus(){
  var status_id = $('select[name="quotation_status_id"]').val();

  $('#openbay-info').remove();

  $.ajax({
    url: 'index.php?route=extension/openbay/getorderinfo&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>&status_id='+status_id,
    dataType: 'html',
    success: function(html) {
      $('#history').after(html);
    }
  });
}

function addOrderInfo(){
  var status_id = $('select[name="quotation_status_id"]').val();

  $.ajax({
    url: 'index.php?route=extension/openbay/addorderinfo&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>&status_id='+status_id,
    type: 'post',
    dataType: 'html',
    data: $(".openbay-data").serialize()
  });
}

$(document).ready(function() {
  changeStatus();
});

$('select[name="quotation_status_id"]').change(function(){ changeStatus(); });
//--></script>

<script type="text/javascript"><!--
// Sort the custom fields
$('#tab-payment tr[data-sort]').detach().each(function() {
  if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-payment tr').length) {
    $('#tab-payment tr').eq($(this).attr('data-sort')).before(this);
  }

  if ($(this).attr('data-sort') > $('#tab-payment tr').length) {
    $('#tab-payment tr:last').after(this);
  }

  if ($(this).attr('data-sort') < -$('#tab-payment tr').length) {
    $('#tab-payment tr:first').before(this);
  }
});

$('#tab-shipping tr[data-sort]').detach().each(function() {
  if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-shipping tr').length) {
    $('#tab-shipping tr').eq($(this).attr('data-sort')).before(this);
  }

  if ($(this).attr('data-sort') > $('#tab-shipping tr').length) {
    $('#tab-shipping tr:last').after(this);
  }

  if ($(this).attr('data-sort') < -$('#tab-shipping tr').length) {
    $('#tab-shipping tr:first').before(this);
  }
});
//--></script></div>
<?php echo $footer; ?> 