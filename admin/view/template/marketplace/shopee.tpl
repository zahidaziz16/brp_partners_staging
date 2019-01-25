<?php echo $header; ?>
<link href="view/stylesheet/select2.css" rel="stylesheet" />
<script src="view/javascript/select2/select2.min.js">
</script>
<script>
$(document).ready(function() {
    $('.select2').select2();
	var url_string = window.location.href;
    var url = new URL(url_string);
    var token = url.searchParams.get("token");
	$(".select2").select2({
  ajax: {
    url: "index.php?route=marketplace/shopee/AjaxAPI",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
        page: params.page || 1,
		token: token
      };
    },
    processResults: function (data, params) {

      params.page = params.page || 1;

      return {
        results: data.items,
        pagination: {
          more: (params.page * 30) < data.total_count
        }
      };
    },
    cache: true
  },
  placeholder: 'Search for a repository',
  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  minimumInputLength: 1,
  templateResult: formatRepo,
  templateSelection: formatRepoSelection
}).on('select2:open',function(){

            $('.select2-dropdown--above').attr('id','fix');
            $('#fix').removeClass('select2-dropdown--above');
            $('#fix').addClass('select2-dropdown--below');

        });

function formatRepo (repo) {
  if (repo.loading) {
    return repo.text;
  }

  var markup = "<div class='select2-result-repository clearfix'>" +
    "<div class='select2-result-repository__meta'>" +
      "<div class='select2-result-repository__title'>" + repo.full_name + "</div>" +
  "</div></div>";

  return markup;
}

function formatRepoSelection (repo) {
  return repo.full_name || repo.text;
}
});
</script>
<?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-worldpay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-worldpay" class="form-horizontal">
		   <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="shopee_enable" id="input-status" class="form-control">
                <?php if ($shopee_enable) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
		      <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_add_product_cronjob; ?></label>
		            <div class="col-sm-10">
		              <select name="shopee_add_product_enable" id="input-status" class="form-control">
		                <?php if ($shopee_add_product_enable == 1) { ?>
		                <option value="3"><?php echo $entry_add_new_products; ?></option>
		                <option value="2"><?php echo $entry_stock_level_only; ?></option>
		                <option value="1" selected="selected"><?php echo $entry_product_price_stock_level; ?></option>
		                <option value="0"><?php echo $text_disabled; ?></option>
		                <?php }
		                else if ($shopee_add_product_enable == 2) { ?>
		                <option value="3"><?php echo $entry_add_new_products; ?></option>
		                <option value="2" selected="selected"><?php echo $entry_stock_level_only; ?></option>
		                <option value="1"><?php echo $entry_product_price_stock_level; ?></option>
		                <option value="0"><?php echo $text_disabled; ?></option>
		                <?php }
		                else if ($shopee_add_product_enable == 3) { ?>
		                <option value="3" selected="selected"><?php echo $entry_add_new_products; ?></option>
		                <option value="2"><?php echo $entry_stock_level_only; ?></option>
		                <option value="1"><?php echo $entry_product_price_stock_level; ?></option>
		                <option value="0"><?php echo $text_disabled; ?></option>
		                <?php } else { ?>
		                <option value="3"><?php echo $entry_add_new_products; ?></option>
		                <option value="2"><?php echo $entry_stock_level_only; ?></option>
		                <option value="1"><?php echo $entry_product_price_stock_level; ?></option>
		                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
		                <?php } ?>
		              </select>
		       </div>
		  </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-userid"><span data-toggle="tooltip" title="<?php echo $help_user_id; ?>"><?php echo $entry_user_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="shopee_partner_id" value="<?php echo $shopee_partner_id; ?>" placeholder="<?php echo $entry_user_id; ?>" id="input-userid" class="form-control" />
              <?php if ($error_partner_id) { ?>
              <div class="text-danger"><?php echo $error_partner_id; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-shopid"><span data-toggle="tooltip" title="<?php echo $help_shop_id; ?>"><?php echo $entry_shop_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="shopee_shop_id" value="<?php echo $shopee_shop_id; ?>" placeholder="<?php echo $entry_shop_id; ?>" id="input-shopid" class="form-control" />
              <?php if ($error_shop_id) { ?>
              <div class="text-danger"><?php echo $error_shop_id; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-APIkey"><span data-toggle="tooltip" title="<?php echo $help_API_key; ?>"><?php echo $entry_API_key; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="shopee_API_key" value="<?php echo $shopee_API_key; ?>" placeholder="<?php echo $entry_API_key; ?>" id="input-password" class="form-control" />
              <?php if ($error_API_key) { ?>
              <div class="text-danger"><?php echo $error_API_key; ?></div>
              <?php } ?>
            </div>
          </div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_last_cronjob_date_order; ?></label>
            <div class="col-sm-10">
				<input type="text" value="<?php echo $last_cronjob_date_order; ?>" class="form-control" readonly/>
				<input type="hidden" name="shopee_last_cronjob_date_order" value="<?php echo $last_cronjob_date_order_datetime; ?>" class="form-control" readonly/>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_last_cronjob_date_product; ?></label>
            <div class="col-sm-10">
				<input type="text" value="<?php echo $last_cronjob_date_product; ?>" class="form-control" readonly/>
				<input type="hidden" name="shopee_last_cronjob_date_product" value="<?php echo $last_cronjob_date_product_datetime; ?>" class="form-control" readonly/>
            </div>
          </div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="<?php echo $help_shopee_dummy_customer; ?>"><?php echo $entry_shopee_dummy_customer; ?></span></label>
			
            <div class="col-sm-10">
				  <select name='shopee_dummy_customer_id' style='width:100%;' class='select2' id='<?php echo $shopee_dummy_customer_id; ?>'>
				  <?php if ($shopee_dummy_customer_id != 0){ ?>
				  <option value='<?php echo $shopee_dummy_customer_id; ?>' selected = 'selected'><?php echo $shopee_dummy_customer_name; ?></option>
				  <?php } else { ?>
				  <option value='<?php echo $shopee_dummy_customer_id; ?>' selected = 'selected'>Not Set / Disabled</option>
				  <?php }?>
				  </select>
				
            </div>

          </div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-price-percentage"><span data-toggle="tooltip" title="<?php echo $help_shopee_price_markup_percentage; ?>"><?php echo $entry_shopee_price_markup_percentage; ?></span></label>
				<div class="col-sm-10">
				  <input type="text" name="shopee_price_markup_percentage" value="<?php echo $shopee_price_markup_percentage; ?>" onkeypress="return isNumberKey(event)" placeholder="Please enter numbers only. Leave empty or enter '0' to disable percentage price markup." id="input-price-percentage" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-price-flat"><span data-toggle="tooltip" title="<?php echo $help_shopee_price_markup_flat; ?>"><?php echo $entry_shopee_price_markup_flat; ?></span></label>
				<div class="col-sm-10">
				  <input type="text" name="shopee_price_markup_flat" value="<?php echo $shopee_price_markup_flat; ?>" onkeypress="return isNumberKeyWithDecimal(event)" placeholder="Please enter numbers only. Leave empty or enter '0' to disable flat price markup." id="input-price-flat" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-price-threshold"><span data-toggle="tooltip" title="<?php echo $help_shopee_price_threshold; ?>"><?php echo $entry_shopee_price_threshold; ?></span></label>
				<div class="col-sm-10">
				  <input type="text" name="shopee_price_threshold" value="<?php echo $shopee_price_threshold; ?>" onkeypress="return isNumberKeyWithDecimal(event)" placeholder="Please enter numbers only. Leave empty to disable price threshold." id="input-price-threshold" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-price-flat"><span data-toggle="tooltip" title="<?php echo $help_shipping_shopee; ?>"><?php echo $entry_shipping_shopee; ?></span></label>
				<div class="col-sm-10">
				  <input type="text" name="shopee_shipping_fee" value="<?php echo $shopee_shipping_fee; ?>" onkeypress="return isNumberKeyWithDecimal(event)" placeholder="Please enter numbers only. Leave empty or enter '0' to disable shipping fee." id="input-price-flat" class="form-control" />
				</div>
			</div>
			<!--
            <div class ="form-group">
                <label class="col-sm-2 control-label" for="Shopee Log"><span data-toggle="tooltip" title="Shopee Log">Shopee Log</span></label>
                <div class="col-sm-10">
                    <a href="localhost/work/atoz_brp_partner/cron/shopee_updateProducts.log">shopee_updateProduct.log</a>
                </div>
            </div>
        -->
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	} else {
		return true;
	}
}

function isNumberKeyWithDecimal(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
		return false;
	} else {
		return true;
	}
}
</script>
<?php echo $footer; ?> 