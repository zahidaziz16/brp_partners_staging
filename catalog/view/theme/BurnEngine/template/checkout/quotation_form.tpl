<form class="form-horizontal" id="form-quotation">
  <div class="radio">
    <label>
	<input type="radio" name="payment_address" value="existing" checked />
	<?php echo $text_address_existing; ?></label>
	</div>
	<div id="payment-existing">
	<select name="address_id" id="payment_address_option" class="form-control" onchange="populateForm(this.value);">
	  <?php foreach ($addresses as $address) { ?>
	  <?php if ($address['address_id'] == $address_id) { ?>
	  <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['company']; ?>, <?php echo $address['address_1']; ?>,<?php echo $address['address_2']; ?>, <?php echo $address['city']; ?>, <?php echo $address['postcode']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
	  <?php } else { ?>
	  <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['company']; ?>, <?php echo $address['address_1']; ?>,<?php echo $address['address_2']; ?>, <?php echo $address['city']; ?>, <?php echo $address['postcode']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
	  <?php } ?>
	  <?php } ?>
    </select>
  </div>
  <div class="radio">
    <label>
      <input type="radio" name="payment_address" value="new" />
      <?php echo $text_address_new; ?></label>
  </div>
  <span class="clear tb_sep"></span>
  <div id="payment-new" style="display: <?php if($addresses) echo "none"; else echo "block"; ?>;">
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-payment-firstname"><?php echo $entry_firstname; ?></label>
      <div class="col-sm-10">
        <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-payment-firstname" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-payment-lastname"><?php echo $entry_lastname; ?></label>
      <div class="col-sm-10">
        <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-payment-lastname" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-payment-company"><?php echo $entry_company; ?></label>
      <div class="col-sm-10">
		<input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-payment-company" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-payment-address-1"><?php echo $entry_address_1; ?></label>
      <div class="col-sm-10">
        <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-payment-address-1" class="form-control" />
      </div>
    </div>
	<div class="form-group">
      <label class="col-sm-2 control-label" for="input-payment-address_2"><?php echo $entry_address_2; ?></label>
      <div class="col-sm-10">
		<input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-payment-address-2" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-payment-postcode"><?php echo $entry_postcode; ?></label>
      <div class="col-sm-10">
        <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-payment-postcode" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-payment-city"><?php echo $entry_city; ?></label>
      <div class="col-sm-10">
        <input type="text" name="city" value="<?php echo $city; ?>" id="input-payment-city" class="form-control">
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-payment-country"><?php echo $entry_country; ?></label>
      <div class="col-sm-10">
        <select name="country_id" id="input-payment-country" class="form-control">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($countries as $country) { ?>
          <?php if ($country['country_id'] == $country_id) { ?>
          <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
          <?php } else { ?>
          <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
          <?php } ?>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-payment-zone"><?php echo $entry_zone; ?></label>
      <div class="col-sm-10">
        <select name="zone_id" id="input-payment-zone" class="form-control">
			<option value="">Select</option>
                <?php foreach ($zones as $zone) { ?>
                    <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
                <?php } ?>
		</select>
      </div>
    </div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="input-payment-telephone">Telephone No</label>
		<div class="col-sm-10">
		  <input type="text" name="telephone" value="<?php if(isset($telephone)){echo $telephone;} ?>" id="input-payment-telephone" class="form-control" />
		</div>
	</div>
  </div>
  <div class="buttons clearfix">
    <div class="pull-right">
		<input type="hidden" value="<?php echo $quote; ?>" name="shipping_method">
		<input type="button" value="<?php if($isPetronasUser) { ?>Confirm Order<?php }else { ?><?php echo $button_generate; ?><?php } ?>" id="button-quotation-submit" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>

<script type="text/javascript">
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
$('input[name=\'payment_address\']').on('change', function() {
	if (this.value == 'new') {
		$('#payment-new').show();
	} else {
		$('#payment-new').hide();
	}
});
</script>
<script type="text/javascript">
 
	
	function populateForm(addrId){
		$.ajax({
			url:'index.php?route=checkout/quotation_form/populateAddress',
			type:'POST',
			dataType:'json',
			data:{address_id: addrId},
			success:function(data){
				$('#input-payment-firstname').val(data.firstname);
				$('#input-payment-lastname').val(data.lastname);
				$('#input-payment-company').val(data.company);
				$('#input-payment-address-1').val(data.address_1);
				$('#input-payment-address-2').val(data.address_2);
				$('#input-payment-postcode').val(data.postcode);
				$('#input-payment-city').val(data.city);
				$('#input-payment-country').val(data.country_id);
				$('#input-payment-zone').val(data.zone_id);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.statusText);
				console.log(thrownError);
				console.log(xhr.responseText);
			}
		});
	}
</script>