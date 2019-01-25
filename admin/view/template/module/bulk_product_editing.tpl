<?php
//==============================================================================
// Bulk Product Editing v230.1
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================
?>

<?php echo $header; ?>
<style type="text/css">
	/* compatibility styling */
	.scrollbox {
		border: 1px solid #CCCCCC;
		width: 350px;
		height: 100px;
		background: #FFFFFF;
		overflow-y: scroll;
	}
	.scrollbox img {
		float: right;
		cursor: pointer;
	}
	.scrollbox div {
		padding: 3px;
	}
	.scrollbox label {
		display: block;
	}
	.scrollbox div input {
		margin: 0px;
		padding: 0px;
		margin-right: 3px;
	}
	.form-control {
		width: auto;
	}
	
	/* extension styling */
	#edit-c, #edit-m, #edit-p, .down-triangle, .bpe-hidden {
		display: none;
	}
	#edit-c, #edit-m, #edit-p {
		margin: 0 10px;
	}
	.bluebar {
		background: #E4EEF7;
		padding: 10px;
		margin: 10px 0;
		font-weight: bold;
		border-top: 1px dotted #CCC;
		border-bottom: 1px dotted #CCC;
	}
	.clickable:hover {
		cursor: pointer;
	}
	.scrollbox, select[multiple="multiple"] {
		width: 400px;
		height: 150px;
		text-align: left;
	}
	.scrollbox label:nth-child(even) div {
		background: #E4EEF7;
	}
	.selectall-links {
		font-size: 11px;
		padding: 5px 5px 0 !important;
		cursor: pointer;
	}
	thead td, tfoot td {
		background: #EEE;
		height: 24px;
	}
	.numeric {
		color: #00F;
		font-weight: bold;
	}
</style>
<?php if (isset($column_left)) echo $column_left; ?>
<div id="content">
<?php if (version_compare(VERSION, '2.0', '<')) { ?>
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
<?php } ?>
<?php if ($error_warning) { ?><div class="warning alert alert-danger"><?php echo $error_warning; ?></div><?php } ?>
<?php if ($success) { ?><div class="success alert-success alert"><?php echo $success; ?></div><?php } ?>
<div class="box">
	<?php if (version_compare(VERSION, '1.5', '<')) { ?><div class="left"></div><div class="right"></div><?php } ?>
	<div class="heading page-header">
		<div class="container-fluid">
			<h1 style="padding: 10px 2px 0">
				<?php if (version_compare(VERSION, '2.0', '<')) { ?>
					<img src="view/image/<?php echo $type; ?>.png" alt="" style="vertical-align: middle" />
				<?php } ?>
				<?php echo $heading_title; ?>
			</h1>
			<?php if (version_compare(VERSION, '2.0', '>=')) { ?>
				<ul class="breadcrumb">
					<?php foreach ($breadcrumbs as $breadcrumb) { ?>
						<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
					<?php } ?>
				</ul>
			<?php } ?>
			<div class="buttons pull-right">
				<a onclick="$('#form').attr('action', location + '&exit=true'); $('#form').submit()" class="button btn btn-primary"><span><?php echo $button_save_exit; ?></span></a>
				<a onclick="$('#form').submit()" class="button btn btn-primary"><span><?php echo $button_save_keep_editing; ?></span></a>
				<a onclick="location = '<?php echo $exit; ?>'" class="button btn btn-default"><span><?php echo $button_cancel; ?></span></a>
			</div>
		</div>
	</div>
	<div class="content container-fluid">
		<form action="" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
			<div class="help"><?php echo $text_help; ?></div>
			<div class="bluebar">
				<select class="form-control" name="edit" onchange="$(this).find('option[value=0]').remove(); $('#edit-c').slideUp(); $('#edit-m').slideUp(); $('#edit-p').slideUp(); if ($(this).val()) $('#' + $(this).attr('name') + '-' + $(this).val().substring(0,1)).slideDown();">
					<option value="0"><?php echo $text_select_which; ?></option>
					<option value="c"><?php echo $text_by_category; ?></option>
					<option value="m"><?php echo $text_by_manufacturer; ?></option>
					<option value="p"><?php echo $text_by_product; ?></option>
				</select>
			</div>
			<div id="edit-c">
				<div class="scrollbox">
					<?php foreach ($categories as $c) { ?>
						<label><div><input type="checkbox" name="edit-c[]" value="<?php echo $c['category_id']; ?>" /><?php echo (!isset($c['status']) || $c['status']) ? $c['name'] : '<em>' . $c['name'] . '</em>'; ?></div></label>
					<?php } ?>
				</div>
				<?php echo $selectall_links; ?>
			</div>
			<div id="edit-m">
				<div class="scrollbox">
					<?php foreach ($manufacturers as $m) { ?>
						<label><div><input type="checkbox" name="edit-m[]" value="<?php echo $m['manufacturer_id']; ?>" /><?php echo $m['name']; ?></div></label>
					<?php } ?>
				</div>
				<?php echo $selectall_links; ?>
			</div>
			<div id="edit-p">
				<span style="display: inline-block">
					<select class="form-control" id="editable-list" onchange="getProducts('#editable-list', '#editing-list')">
						<option value="-1"><?php echo $text_select_product; ?></option>
						<optgroup label="<?php echo $text_all_products; ?>">
							<option value="0"><?php echo $text_all_products; ?></option>
						</optgroup>
						<optgroup label="<?php echo $text_manufacturers; ?>">
							<?php foreach ($manufacturers as $m) { ?>
								<option value="m<?php echo $m['manufacturer_id']; ?>"><?php echo $m['name']; ?></option>
							<?php } ?>
						</optgroup>
						<optgroup label="<?php echo $text_categories; ?>">
							<?php foreach ($categories as $c) { ?>
								<option value="c<?php echo $c['category_id']; ?>"><?php echo $c['name']; ?></option>
							<?php } ?>
						</optgroup>
					</select>
					<br />
					<select class="form-control" id="editing-list" multiple="multiple"></select>
				</span>
				<span style="display: inline-block; vertical-align: top">
					<br /><br /><br /><br />
					<input type="button" onclick="addProduct('#editing-list', '#edited-list', 'edit-p')" value="--&gt;" />
					<br /><br /><br />
					<input type="button" onclick="removeProduct('#edited-list')" value="&lt;--" />
				</span>
				<span style="display: inline-block">
					<em><?php echo $text_products_that_will; ?></em>
					<br />
					<select class="form-control" id="edited-list" multiple="multiple"></select>
				</span>
			</div>
			<div class="clickable bluebar"><span class="right-triangle">&#9658;</span> <span class="down-triangle">&#9660;</span> <?php echo $text_edit_general_data; ?></div>
			<div class="bpe-hidden">
				<table class="form table table-hover">
				<tr><td colspan="2">
						<span class="help"><?php echo $text_general_data_help; ?></span>
						<label><input type="checkbox" name="round-g" value="1" /> <?php echo $text_round_percentage; ?></label>
					</td>
				</tr>
				<tr>
					<td style="width: 250px"><?php echo $entry_status; ?></td>
					<td><select class="form-control" name="status">
							<option value=""><?php echo $text_no_change; ?></option>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_model; ?></td>
					<td><input type="text" name="model" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_sku; ?></td>
					<td><input type="text" name="sku" /></td>
				</tr>
				<tr <?php if (version_compare(VERSION, '1.5', '<')) echo 'style="display: none"'; ?>>
					<td><?php echo (isset($entry_upc)) ? $entry_upc: ''; ?></td>
					<td><input type="text" name="upc" /></td>
				</tr>
				<tr <?php if (version_compare(VERSION, '1.5.4', '<')) echo 'style="display: none"'; ?>>
					<td><?php echo (isset($entry_ean)) ? $entry_ean : ''; ?></td>
					<td><input type="text" name="ean" /></td>
				</tr>
				<tr <?php if (version_compare(VERSION, '1.5.4', '<')) echo 'style="display: none"'; ?>>
					<td><?php echo (isset($entry_jan)) ? $entry_jan : ''; ?></td>
					<td><input type="text" name="jan" /></td>
				</tr>
				<tr <?php if (version_compare(VERSION, '1.5.4', '<')) echo 'style="display: none"'; ?>>
					<td><?php echo (isset($entry_isbn)) ? $entry_isbn : ''; ?></td>
					<td><input type="text" name="isbn" /></td>
				</tr>
				<tr <?php if (version_compare(VERSION, '1.5.4', '<')) echo 'style="display: none"'; ?>>
					<td><?php echo (isset($entry_mpn)) ? $entry_mpn : ''; ?></td>
					<td><input type="text" name="mpn" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_location; ?></td>
					<td><input type="text" name="location" /></td>
				</tr>
				<tr>
					<td><span class="numeric">*</span> <?php echo $entry_price; ?></td>
					<td><input type="text" name="price" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_tax_class; ?></td>
					<td><select class="form-control" name="tax_class_id">
							<option value=""><?php echo $text_no_change; ?></option>
							<option value="0"><?php echo $text_none; ?></option>
							<?php foreach ($tax_classes as $tax_class) { ?>
								<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><span class="numeric">*</span> <?php echo $entry_quantity; ?></td>
					<td><input type="text" name="quantity" size="2" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_minimum; ?></td>
					<td><input type="text" name="minimum" size="2" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_subtract; ?></td>
					<td><select class="form-control" name="subtract">
							<option value=""><?php echo $text_no_change; ?></option>
							<option value="1"><?php echo $text_yes; ?></option>
							<option value="0"><?php echo $text_no; ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_stock_status; ?></td>
					<td><select class="form-control" name="stock_status_id">
							<option value=""><?php echo $text_no_change; ?></option>
							<?php foreach ($stock_statuses as $stock_status) { ?>
								<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_shipping; ?></td>
					<td><select class="form-control" name="shipping">
							<option value=""><?php echo $text_no_change; ?></option>
							<option value="1"><?php echo $text_yes; ?></option>
							<option value="0"><?php echo $text_no; ?></option>
						</select>
					</td>
				</tr>
			<?php if (version_compare(VERSION, '2.0', '<')) { ?>
				<tr>
					<td><?php echo $entry_image; ?></td>
					<td><div class="image">
							<a onclick="image_upload('image', 'thumb');"><img src="<?php echo $no_image; ?>" alt="" id="thumb" /></a>
							<input type="hidden" name="image" id="image" />
							<br />
							<a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>
							&nbsp; | &nbsp;
							<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
						</div>
					</td>
				</tr>
			<?php } ?>
				<tr>
					<td><?php echo $entry_date_available; ?></td>
					<td><input type="text" name="date_available" size="12" class="date" placeholder="YYYY-MM-DD" /></td>
				</tr>
				<tr>
					<td><span class="numeric">*</span> <?php echo $entry_dimension; ?></td>
					<td><input type="text" name="length" size="4" />
						<input type="text" name="width" size="4" />
						<input type="text" name="height" size="4" />
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_length; ?></td>
					<td><select class="form-control" name="length_class_id">
							<option value=""><?php echo $text_no_change; ?></option>
							<?php foreach ($length_classes as $length_class) { ?>
								<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><span class="numeric">*</span> <?php echo $entry_weight; ?></td>
					<td><input type="text" name="weight" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_weight_class; ?></td>
					<td><select class="form-control" name="weight_class_id">
							<option value=""><?php echo $text_no_change; ?></option>
							<?php foreach ($weight_classes as $weight_class) { ?>
								<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_sort_order; ?></td>
					<td><input type="text" name="sort_order" size="2" /></td>
				</tr>
				<tr  <?php if (version_compare(VERSION, '1.5', '<')) echo 'style="display: none"'; ?>>
					<td><span class="numeric">*</span> <?php echo (isset($entry_points)) ? $entry_points: ''; ?></td>
					<td><input type="text" name="points" /></td>
				</tr>
				<tr  <?php if (version_compare(VERSION, '1.5', '<')) echo 'style="display: none"'; ?>>
					<td><?php echo $entry_reward; ?></td>
					<td><table class="list table table-hover" style="width: 500px; margin-bottom: 0">
							<thead>
								<tr>
									<td class="left"><?php echo $entry_customer_group; ?></td>
									<td class="left"><?php echo $entry_reward; ?></td>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($customer_groups as $customer_group) { ?>
									<tr>
										<td class="left"><?php echo $customer_group['name']; ?></td>
										<td class="left"><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" /></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td><?php echo $text_times_viewed; ?></td>
					<td><input type="text" name="viewed" size="2" /></td>
				</tr>
				</table>
			</div>
			<div class="clickable bluebar"><span class="right-triangle">&#9658;</span> <span class="down-triangle">&#9660;</span> <?php echo $text_edit_product_links; ?></div>
			<div class="bpe-hidden">
				<table class="form table table-hover">
					<tr>
						<td><?php echo $text_change_manufacturer; ?></td>
						<td><select class="form-control" name="manufacturer_id">
								<option value=""><?php echo $text_no_change; ?></option>
								<?php foreach ($manufacturers as $m) { ?>
									<option value="<?php echo $m['manufacturer_id']; ?>"><?php echo $m['name']; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $text_add_category; ?></td>
						<td><div class="scrollbox">
								<?php foreach ($categories as $c) { ?>
									<label><div><input type="checkbox" name="add-c[]" value="<?php echo $c['category_id']; ?>" /><?php echo (!isset($c['status']) || $c['status']) ? $c['name'] : '<em>' . $c['name'] . '</em>'; ?></div></label>
								<?php } ?>
							</div>
							<?php echo $selectall_links; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo $text_remove_category; ?></td>
						<td><div class="scrollbox">
								<?php foreach ($categories as $c) { ?>
									<label><div><input type="checkbox" name="remove-c[]" value="<?php echo $c['category_id']; ?>" /><?php echo (!isset($c['status']) || $c['status']) ? $c['name'] : '<em>' . $c['name'] . '</em>'; ?></div></label>
								<?php } ?>
							</div>
							<?php echo $selectall_links; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo $text_add_store; ?></td>
						<td><div class="scrollbox">
								<?php foreach ($stores as $s) { ?>
									<label><div><input type="checkbox" name="add-s[]" value="<?php echo $s['store_id']; ?>" /><?php echo $s['name']; ?></div></label>
								<?php } ?>
							</div>
							<?php echo $selectall_links; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo $text_remove_store; ?></td>
						<td><div class="scrollbox">
								<?php foreach ($stores as $s) { ?>
									<label><div><input type="checkbox" name="remove-s[]" value="<?php echo $s['store_id']; ?>" /><?php echo $s['name']; ?></div></label>
								<?php } ?>
							</div>
							<?php echo $selectall_links; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo $text_add_related; ?></td>
						<td><span style="display: inline-block">
								<select class="form-control" id="relatable-list" onchange="getProducts('#relatable-list', '#relating-list')">
									<option value="-1"><?php echo $text_select_product; ?></option>
									<optgroup label="<?php echo $text_all_products; ?>">
										<option value="0"><?php echo $text_all_products; ?></option>
									</optgroup>
									<optgroup label="<?php echo $text_manufacturers; ?>">
										<?php foreach ($manufacturers as $m) { ?>
											<option value="m<?php echo $m['manufacturer_id']; ?>"><?php echo $m['name']; ?></option>
										<?php } ?>
									</optgroup>
									<optgroup label="<?php echo $text_categories; ?>">
										<?php foreach ($categories as $c) { ?>
											<option value="c<?php echo $c['category_id']; ?>"><?php echo $c['name']; ?></option>
										<?php } ?>
									</optgroup>
								</select>
								<br />
								<select class="form-control" id="relating-list" multiple="multiple"></select>
							</span>
							<span style="display: inline-block; vertical-align: top">
								<br /><br /><br /><br />
								<input type="button" onclick="addProduct('#relating-list', '#related-list', 'related')" value="--&gt;" />
								<br /><br /><br />
								<input type="button" onclick="removeProduct('#related-list')" value="&lt;--" />
							</span>
							<span style="display: inline-block">
								<em><?php echo $text_relate_these; ?></em>
								<br />
								<select class="form-control" id="related-list" multiple="multiple"></select>
							</span>
							<p><label><input type="checkbox" name="oneway" value="1" /> <?php echo $text_only_relate_oneway; ?></label></p>
							<p><label><input type="checkbox" name="remove-all-r" value="1" /> <?php echo $text_remove_related; ?></label></p>
						</td>
					</tr>
				</table>
			</div>
			<div class="clickable bluebar"><span class="right-triangle">&#9658;</span> <span class="down-triangle">&#9660;</span> <?php echo $text_edit_discounts; ?></div>
			<div class="bpe-hidden">
				<span class="help"><?php echo $text_price_help; ?></span> 
				<p><label><input type="checkbox" name="remove-all-d" value="1" /> <?php echo $text_remove_discounts; ?></label></p>
				<p><label><input type="checkbox" name="round-d" value="1" /> <?php echo $text_round_percentage; ?></label></p>
				<table class="list table table-hover">
					<thead>
						<tr>
							<td class="left"><?php echo $entry_customer_group; ?></td>
							<td class="left"><?php echo $entry_quantity; ?></td>
							<td class="left"><?php echo $entry_priority; ?></td>
							<td class="left"><?php echo $text_price_discount; ?></td>
							<td class="left"><?php echo $entry_date_start; ?></td>
							<td class="left"><?php echo $entry_date_end; ?></td>
							<td></td>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<td colspan="7" class="left"><a onclick="addRow($(this), 'discount')" class="button btn btn-primary"><span><?php echo $button_add_discount; ?></span></a></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="clickable bluebar"><span class="right-triangle">&#9658;</span> <span class="down-triangle">&#9660;</span> <?php echo $text_edit_specials; ?></div>
			<div class="bpe-hidden">
				<span class="help"><?php echo $text_price_help; ?></span> 
				<p><label><input type="checkbox" name="remove-all-s" value="1" /> <?php echo $text_remove_specials; ?></label></p>
				<p><label><input type="checkbox" name="round-s" value="1" /> <?php echo $text_round_percentage; ?></label></p>
				<table class="list table table-hover">
					<thead>
						<tr>
							<td class="left"><?php echo $entry_customer_group; ?></td>
							<td class="left"><?php echo $entry_priority; ?></td>
							<td class="left"><?php echo $text_price_discount; ?></td>
							<td class="left"><?php echo $entry_date_start; ?></td>
							<td class="left"><?php echo $entry_date_end; ?></td>
							<td></td>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<td colspan="6" class="left"><a onclick="addRow($(this), 'special')" class="button btn btn-primary"><span><?php echo $button_add_special; ?></span></a></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</form>
		<?php echo $copyright; ?>
	</div>
</div>
<?php if (version_compare(VERSION, '1.5', '<')) { ?>
	<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
	<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
	<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
	<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<?php } else { ?>
	</div>
<?php } ?>
<?php if (version_compare(VERSION, '2.0', '<')) { ?>
	<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
	<script type="text/javascript"><!--
		$(document).ready(function() {
			$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		});
	//--></script>
<?php } ?>
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('.clickable').click(function(){
			$(this).find('.down-triangle').toggle();
			$(this).next().slideToggle();
			$(this).find('.right-triangle').toggle();
		});
	});
	
	function getProducts(dropdown, productlist) {
		$(productlist + ' option').remove();
		if ($(dropdown).val() == -1) return;
		$.ajax({
			url: 'index.php?route=extension/module/<?php echo $name; ?>/getProducts&id=' + $(dropdown).val() + '&token=<?php echo $token; ?>',
			dataType: 'json',
			success: function(data) {
				for (i = 0; i < data.length; i++) {
					$(productlist).append('<option value="' + data[i]['product_id'] + '">' + (data[i]['status'] == '1' ? '' : '<?php echo $text_DISABLED; ?>: ') + data[i]['name'] + ' (' + data[i]['model'] + ')</option>');
				}
			}
		});
	}
	
	function addProduct(productlist, addtolist, inputname) {
		var comparisonArray = [];
		$(addtolist).find('option').each(function(){
			comparisonArray[comparisonArray.length] = $(this).val();
		});
		$(productlist + ' :selected').each(function(){
			if ($.inArray($(this).val(), comparisonArray) == -1) {
				$(productlist).find('option[value="' + $(this).val() + '"]').remove();
				$(addtolist).append('<option value="' + $(this).val() + '">' + $(this).html() + '</option>');
				$(addtolist).after('<input type="hidden" name="' + inputname + '[]" value="' + $(this).val() + '" />');
			}
		});
	}
	
	function removeProduct(addtolist) {
		$(addtolist).find(':selected').each(function(){
			$(this).remove();
			$(addtolist).parent().find('input[value="' + $(this).val() + '"]').remove();
		});
	}
	
	function addRow(element, type) {
		element.parent().parent().parent().prev().append('\
			<tr>\
			<td class="left">\
				<select class="form-control" name="product_' + type + '[customer_group_id][]">\
					<?php foreach ($customer_groups as $customer_group) { ?>\
						<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>\
					<?php } ?>\
				</select>\
			</td>\
			' + (type == 'discount' ? '<td class="left"><input type="text" name="product_' + type + '[quantity][]" size="2" /></td>' : '') + '\
			<td class="left"><input type="text" name="product_' + type + '[priority][]" size="2" /></td>\
			<td class="left"><input type="text" name="product_' + type + '[price][]" /></td>\
			<td class="left"><input type="text" name="product_' + type + '[date_start][]" class="date" placeholder="YYYY-MM-DD" /></td>\
			<td class="left"><input type="text" name="product_' + type + '[date_end][]" class="date" placeholder="YYYY-MM-DD" /></td>\
			<td class="left"><a class="button btn btn-danger" onclick="$(this).parent().parent().remove()"><span><?php echo $button_remove; ?></span></a></td>\
			</tr>\
		');
		<?php if (version_compare(VERSION, '2.0', '<')) { ?>
			$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		<?php } ?>
	}
	
	<?php if (version_compare(VERSION, '2.0', '<')) { ?>
		function image_upload(field, thumb) {
			$('#dialog').remove();
			$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
			$('#dialog').dialog({
				title: '<?php echo $text_image_manager; ?>',
				close: function (event, ui) {
					if ($('#' + field).attr('value')) {
						$.ajax({
							url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
							type: 'POST',
							data: 'image=' + encodeURIComponent($('#' + field).attr('value')),
							dataType: 'text',
							success: function(data) {
								$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
							}
						});
					}
				},	
				bgiframe: false,
				width: 800,
				height: 400,
				resizable: false,
				modal: false
			});
		};
	<?php } ?>
//--></script>
<?php echo $footer; ?>