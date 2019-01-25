<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <?php /** temporary commentted out
        <button type="button" id="button-refresh" form="form-order" formaction="<?php echo $sync_selected; ?>" data-toggle="tooltip" title="<?php echo $button_sfdelivery; ?>" class="btn btn-info"><?php echo $button_sfdelivery; ?></button>
        **/  ?>
        Select multiple deliveries: 
        <button type="button" id="button-brpwarehouse-bygohofficecom" form="form-order" formaction="<?php echo $sync_brp_warehouse_gohofficecom; ?>" data-toggle="tooltip" title="By BRP Warehouse & Remainder by G.I" class="btn btn-info">By BRP Warehouse &amp; Remainder by G.I</button>
        <button type="button" id="button-brpwarehouse" form="form-order" formaction="<?php echo $sync_brp_warehouse; ?>" data-toggle="tooltip" title="By BRP Warehouse" class="btn btn-info">By BRP Warehouse</button>
        <button type="button" id="button-bygohofficecom" form="form-order" formaction="<?php echo $sync_by_gohofficecom; ?>" data-toggle="tooltip" title="By G.I" class="btn btn-info">By G.I</button>
		<button type="button" id="button-ownarrangement" form="form-order" formaction="<?php echo $deliverall_ownarrangement; ?>" data-toggle="tooltip" title="By Own Arrangement" class="btn btn-info">By Own Arrangement</button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <select name="filter_order_status" id="input-order-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_order_status == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_missing; ?></option>
                  <?php } ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-total"><?php echo $entry_upload_delivery_status; ?></label>
                <select name="filter_upload_delivery_status" id="input-upload-delivery-status" class="form-control">
                  <option value=""></option>
                  <option value="Selected for BRP Warehouse" <?php if ("Selected for BRP Warehouse" == $filter_upload_delivery_status) { ?> selected="selected" <?php } ?>>Selected for BRP Warehouse</option>
                  <option value="Selected for Gohoffice.com" <?php if ("Selected for Gohoffice.com" == $filter_upload_delivery_status) { ?> selected="selected" <?php } ?>>Selected for G.I</option>
                  <option value="Selected for Mixed Delivery" <?php if ("Selected for Mixed Delivery" == $filter_upload_delivery_status) { ?> selected="selected" <?php } ?>>Selected for Mixed Delivery</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form method="post" action="" enctype="multipart/form-data" id="form-order">
			<input type="hidden" name="submit_of" id="submit_of" value="" />
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center">
                  	<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                  </td>
                  <td class="text-right"><?php if ($sort == 'o.order_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                    <?php /*
                    <td class="text-right"><?php if ($sort == 'o.unique_order_id') { ?>
                    <a href="<?php echo $sort_unique_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_unique_order_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_unique_order; ?>"><?php echo $column_unique_order_id; ?></a>
                    <?php } ?></td>
                    <td class="text-left"><?php if ($sort == 'o.upload_delivery_status') { ?>
                    <a href="<?php echo $sort_upload_delivery_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_upload_delivery_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_upload_delivery_status; ?>"><?php echo $column_upload_delivery_status; ?></a>
                    <?php } ?></td>*/ ?>
                  <td class="text-left"><?php if ($sort == 'customer') { ?>
                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?></td>
                    <?php /*
                  <td class="text-left"><?php if ($sort == 'order_status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left">
                    <?php echo $column_delivery_status; ?></a>
                    </td>*/ ?>
                  <td class="text-right"><?php if ($sort == 'o.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'order_status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_modified') { ?>
                    <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_action; ?></td>
                  <td class="text-right">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { ?>
                <tr>
                  <td class="text-center">
                  	
                    <?php if( $order['order_status_id']!="7" && !($order['can_sync_gohoffice'] && $order['is_syned_gohoffice']) && !($order['can_sync_warehouse'] && in_array($order['order_status_id'], array('5','21','22','23'))) && !($order['can_sync_partially'] && $order['is_syned_mixed']) ) { ?>
                        <?php if( ($order['can_sync_gohoffice'] && !$order['is_syned_gohoffice']) || ($order['can_sync_warehouse'] && !$order['has_zero_wms_qty'] && !in_array($order['order_status_id'], array('5','21','22','23'))) ) { ?>
                            <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                        <?php } ?>
                    <?php } ?>
                  </td>
                  <td class="text-right"><?php echo $order['order_id']; ?></td>
                  <td class="text-left"><?php echo $order['date_added']; ?></td>
                  <?php /*<td class="text-left"><?php echo $order['unique_order_id']; ?></td>*/ ?>
                  <?php /*<td class="text-left"><?php echo $order['upload_delivery_status']; ?></td>*/?>
                  <td class="text-left"><?php echo $order['customer']; ?></td>
                  <?php /*<td class="text-left"><?php echo $order['delivery_status']; ?></td>*/?>
                  <td class="text-right"><?php echo $order['total']; ?></td>
                  <td class="text-left"><?php echo $order['order_status']; ?></td>
                  <td class="text-left"><?php echo $order['date_modified']; ?></td>
                  <td class="text-left">
                      <?php if($order['order_status']=="Canceled") { ?>
                        Order Cancelled
                      <?php } else if($order['upload_delivery_status']=="Cancelled") { ?>
                        Delivery Cancelled
                      <?php } else { ?>
                          <?php if($order['can_sync_gohoffice'] && $order['is_syned_gohoffice']) { ?>
                            Selected for G.I
                          <?php //} else if($order['can_sync_warehouse'] && in_array($order['order_status_id'], array('5','21','22','23'))) { ?>
                          <?php //} else if(($order['can_sync_warehouse'] && in_array($order['order_status_id'], array('5','21','22','23'))) || $order['is_syned_warehouse']) { ?>
                          <?php } else if($order['can_sync_warehouse'] && $order['is_syned_warehouse']) { ?>
                            Selected for BRP Warehouse
                          <?php } else if($order['can_sync_partially'] && $order['is_syned_mixed']) { ?>
                            Selected for Mixed Delivery
                          <?php } else if(in_array($order['order_status_id'], array('5'))) { ?>
                            Order Completed
                          <?php } else { ?>
                            <div class="btn-group">
                            
                                <?php if($order['can_sync_gohoffice'] && $order['total_third_parties']==0) { ?>
                                    <?php if(!$order['is_syned_gohoffice']) { ?>
                                        <button type="button" class="btn btn-primary" onclick="window.location.href='<?php echo $order['sync_gohoffice']; ?>'" style="width:200px;">
                                            <?php echo $button_bygohoffice; ?>
                                        </button>
                                    <?php } else { ?>
                                        <button type="button" class="btn btn-primary disabled" onclick="javascript:void(0);" style="width:200px;">
                                            Selected for G.I
                                        </button>
                                    <?php } ?>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary disabled" onclick="javascript:void(0);" style="width:200px;">
                                        <?php echo $button_bygohoffice; ?>
                                    </button>
                                <?php } ?>
                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" style="width:225px;">
                                    <?php if($order['can_sync_warehouse'] && !$order['has_zero_wms_qty']) { ?>
                                        <?php if(!in_array($order['order_status_id'], array('5','21','22','23'))) { ?>
											<?php if($order['has_moreQty']){?>
													<li class="disabled">
														<a href="javascript:void(0);">
															<center><?php echo $button_brpwarehouse; ?></center>
														</a>
													</li>
												<?php } elseif($order['has_lessQty']){ ?>
													<li>
														<a style="cursor: pointer;" onclick="alert('There is enough inventory in stock but some have been booked by a previous order. The quantity available for delivery will only be updated when previous orders have been shipped. Please restock or cancel the previous order to proceed with this order. Please view the order details to see available amounts and current stocks.');">
															<center><?php echo $button_brpwarehouse; ?></center>
														</a>
													</li>
												<?php } else{ ?>
													<li>
														<a style="cursor: pointer;" href="<?php echo $order['sync_brpwarehouse']; ?>" onclick="return confirm('Confirm delivery by BRP warehouse?');">
															<center><?php echo $button_brpwarehouse; ?></center>
														</a>
													</li>
												<?php } ?>
										
										
                                            <!--<li>
                                                <a href="<?php echo $order['sync_brpwarehouse']; ?>">
                                                    <center><?php echo $button_brpwarehouse; ?></center>
                                                </a>
                                            </li>-->
                                        <?php } else { ?>
                                            <li class="disabled">
                                                <a href="javascript:void(0);">
                                                    <center>Selected for BRP Warehouse</center>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <li class="disabled">
                                            <a href="javascript:void(0);">
                                                <center><?php echo $button_brpwarehouse; ?></center>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    
                                    <?php if($order['can_sync_partially']) { ?>
                                        <li>
                                            <a href="<?php echo $order['sync_configure']; ?>">
                                                <center><?php echo $button_configuredelivery; ?></center>
                                            </a>
                                        </li>
                                    <?php } else { ?>
                                        <li class="disabled">
                                            <a href="javascript:void(0);">
                                                <center><?php echo $button_configuredelivery; ?></center>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                          <?php } ?>
                      <?php } ?>
                  </td>
                  <td class="text-right">
                    <?php /**if(strtolower($order['delivery_status'])!="packing" && strtolower($order['delivery_status'])!="shipment") { ?>
                  		<a href="<?php echo $order['cancel']; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> <?php echo $button_cancel; ?></a>
                    <?php } else { ?>
                    	<a href="javascript:void(0);" onclick="javascript:alert('You are not allow to cancel this transaction.');" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> <?php echo $button_cancel; ?></a>
                    <?php }**/ ?>
                    <a href="<?php echo $order['delivery_view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> 
                    <?php /*<a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>*/ ?>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <br /><br />
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=sale/upload_delivery_list&token=<?php echo $token; ?>';

	var filter_order_id = $('input[name=\'filter_order_id\']').val();

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_customer = $('input[name=\'filter_customer\']').val();

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}

	var filter_order_status = $('select[name=\'filter_order_status\']').val();

	if (filter_order_status != '*') {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}

	var filter_upload_delivery_status = $('select[name=\'filter_upload_delivery_status\']').val();
	
	if (filter_upload_delivery_status) {
		url += '&filter_upload_delivery_status=' + encodeURIComponent(filter_upload_delivery_status);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name^=\'selected\']').on('change', function() {
	$('#button-refresh').prop('disabled', true);

	var selected = $('input[name^=\'selected\']:checked');

	if (selected.length) {
		$('#button-refresh').prop('disabled', false);
	}
});

$('#button-shipping, #button-invoice').prop('disabled', true);

$('input[name^=\'selected\']:first').trigger('change');

// IE and Edge fix!
$('#button-shipping, #button-invoice').on('click', function(e) {
	$('#form-order').attr('action', this.getAttribute('formAction'));
});

$('#button-refresh').on('click', function(e) {
	$('#form-order').attr('action', this.getAttribute('formAction'));
	$('#form-order').submit();
});

$('#button-brpwarehouse').on('click', function(e) {
	document.getElementById("submit_of").value = "BRP Warehouse";
	$('#form-order').attr('action', this.getAttribute('formAction'));
	$('#form-order').submit();
});
$('#button-brpwarehouse-bygohofficecom').on('click', function(e) {
	document.getElementById("submit_of").value = "BRP Warehouse Gohoffice";
	$('#form-order').attr('action', this.getAttribute('formAction'));
	$('#form-order').submit();
});
$('#button-bygohofficecom').on('click', function(e) {
	document.getElementById("submit_of").value = "Gohoffice";
	$('#form-order').attr('action', this.getAttribute('formAction'));
	$('#form-order').submit();
});
$('#button-ownarrangement').on('click', function(e) {
	document.getElementById("submit_of").value = "Own Arrangement";
	$('#form-order').attr('action', this.getAttribute('formAction'));
	$('#form-order').submit();
});
//--></script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 