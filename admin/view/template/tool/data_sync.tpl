<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
                            <!--
                            <a onclick="javascript:syncAllNew();" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default">Sync All New Data <i class="fa fa-cloud-download"></i></a>
				<a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
                            -->
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
		<?php if (isset($error_warning)&&$error_warning!="") { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if (isset($success)&&$success!="") { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
                <?php if (isset($runninghalt)&&$runninghalt!="") { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $runninghalt; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
                <?php if (isset($timezonehalt)&&$timezonehalt!="") { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $timezonehalt; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
                <?php if ($partner_sync_all) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $partner_sync_all_message; ?>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php } ?>
		<?php if ((!$error_warning) && (!$success)) { ?>
        	<div id="data_sync_notification"></div>
			<?php /*
                <div id="data_sync_notification" class="alert alert-info"><i class="fa fa-info-circle"></i>
                    <div id="data_sync_loading"><img src="view/image/data_sync/loading.gif" /><?php echo $text_loading_notifications; ?></div>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            */ ?>
		<?php } ?>

		<div class="panel panel-default">
			<div class="panel-body">

				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-sync" data-toggle="tab"><?php echo $tab_sync; ?></a></li>
					<li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
					<li><a href="#tab-settings" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
				</ul>

				<div class="tab-content">

					<div class="tab-pane active" id="tab-sync">

                        
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-list"></i> Select Table</h3>
                        </div>
                        <div class="well">
                            <div id="syncstatusform" class="row" <?php if($selected_table!="oc_product") { ?> style="display:none;"<?php } ?>>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        
                                        <label for="sync-status" class="control-label"><span data-toggle="tooltip" title="Hover each option to show details.">Sync Status</span></label>
                                        <br>
                                        <label class="radio-inline">
                                            <input value="" <?php if($selected_tempMode == "") { ?>checked="checked"<?php  } ?> type="radio" name="tempMode"><span data-toggle="tooltip" title="Show all products"> All</span>
                                          </label>
                                          <label class="radio-inline">
                                              <input id="radN" value="N" <?php if($selected_tempMode == "N") { ?>checked="checked"<?php  } ?>  type="radio" name="tempMode"><span data-toggle="tooltip" title="Show only new products."> New</span>
                                          </label>
                                          <label class="radio-inline">
                                            <input id="radM" value="M" <?php if($selected_tempMode == "M") { ?>checked="checked"<?php  } ?>  type="radio" name="tempMode"><span data-toggle="tooltip" title="Show all modified products including EOLs and price updated products."> Modified</span>
                                          </label>
                                        <label class="radio-inline">
                                            <input id="radMP" value="MP" <?php if($selected_tempMode == "MP") { ?>checked="checked"<?php  } ?>  type="radio" name="tempMode"><span data-toggle="tooltip" title="Show only price updated products. Modified - Price products are products that has their price changed in BRP. The system will list down the products if there is a difference between current BRP price and the BRP price since you last sync regardless of your selling price."> Modified - Price</span>
                                          </label>
                                        <label class="radio-inline">
                                            <input id="radEOL" value="EOL" <?php if($selected_tempMode == "EOL") { ?>checked="checked"<?php  } ?>  type="radio" name="tempMode"><span data-toggle="tooltip" title="Show only end of life products."> End Of Life</span>
                                          </label>
                                        <label class="radio-inline">
                                            <input id="radREL" value="REL" <?php if($selected_tempMode == "REL") { ?>checked="checked"<?php  } ?>  type="radio" name="tempMode"><span data-toggle="tooltip" title="Show only products that has been removed locally."> Deleted by User</span>
                                          </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="input-table">Table</label>
                                        <select name="table" id="input-table" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($sync_tables as $keyTable => $valueTable) { ?>
                                                <option value="<?php echo $keyTable;?>" <?php echo ($selected_table==$keyTable?'selected="selected"':'')?>><?php echo $valueTable; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <button type="button" id="button-table" class="btn btn-primary pull-right"><?php echo $button_next; ?></button>
                                </div>
                            </div>
                        </div>
                        
                        <?php if($selected_table=="oc_product") { ?>
                        
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
                            </div>
                            <div class="well">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                            <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="input-manufacturer-name">Manufacturer</label>
                                            <input type="text" name="filter_manufacturer_name" value="<?php echo $filter_manufacturer_name; ?>" placeholder="Manufacturer" id="input-manufacturer-name" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="input-category-name">Category</label>
                                            <input type="text" name="filter_category_name" value="<?php echo $filter_category_name; ?>" placeholder="Category" id="input-category-name" class="form-control" />
                                        </div>
                                        
                                        <!-- 
                                        <div class="form-group">
                                            <label class="control-label" for="input-sync_status"><?php echo $entry_sync_status; ?></label>
                                            <select name="filter_sync_status" id="input-sync_status" class="form-control">
                                                <option value="*"></option>
                                                <?php if ($filter_sync_status) { ?>
                                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                <?php } else { ?>
                                                    <option value="1"><?php echo $text_enabled; ?></option>
                                                <?php } ?>
                                                <?php if (!$filter_sync_status && !is_null($filter_sync_status)) { ?>
                                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                <?php } else { ?>
                                                    <option value="0"><?php echo $text_disabled; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div> 
                                        -->
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
                                            <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                                            <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                                            <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                                            <select name="filter_status" id="input-status" class="form-control">
                                                <option value="*"></option>
                                                <?php if ($filter_status) { ?>
                                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                <?php } else { ?>
                                                    <option value="1"><?php echo $text_enabled; ?></option>
                                                <?php } ?>
                                                <?php if (!$filter_status && !is_null($filter_status)) { ?>
                                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                <?php } else { ?>
                                                    <option value="0"><?php echo $text_disabled; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="input-image"><?php echo $entry_image; ?></label>
                                            <select name="filter_image" id="input-image" class="form-control">
                                                <option value="*"></option>
                                                <?php if ($filter_image) { ?>
                                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                <?php } else { ?>
                                                    <option value="1"><?php echo $text_enabled; ?></option>
                                                <?php } ?>
                                                <?php if (!$filter_image && !is_null($filter_image)) { ?>
                                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                <?php } else { ?>
                                                    <option value="0"><?php echo $text_disabled; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="input-force_update_price"><?php echo $entry_force_update_price; ?></label>
                                            <select name="filter_force_update_price" id="input-force_update_price" class="form-control">
                                                <option value="*"></option>
                                                <?php if ($filter_force_update_price) { ?>
                                                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                                <?php } else { ?>
                                                    <option value="1"><?php echo $text_yes; ?></option>
                                                <?php } ?>
                                                <?php if (!$filter_force_update_price && !is_null($filter_force_update_price)) { ?>
                                                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                                <?php } else { ?>
                                                    <option value="0"><?php echo $text_no; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
                                    </div>
                                </div>
                            </div>
                            <form action="<?php echo $sync; ?>" method="post" enctype="multipart/form-data" id="form-sync">
                                <input type="hidden" id="sync_type" name="sync_type" value="" />
                                <input type="hidden" id="sync_all_new" name="sync_all_new" value="" />
                                <input type="hidden" id="sync_table" name="sync_table" value="<?php echo $selected_table;?>" />
                                <input type="hidden" id="updateprice" name="updateprice" value="" />
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <td style="width: 1px;" class="text-center">
                                                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                                                </td>
                                                <td style="width: 5px;" class="text-center">
                                                    Sync Status
                                                </td>
                                                <td class="text-center"><?php echo $column_image; ?></td>
                                                <td class="text-left">
                                                    <?php if ($sort == 'pd.name') { ?>
                                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-left">
                                                    <?php if ($sort == 'pm.name') { ?>
                                                        <a href="<?php echo $sort_manufacturer_name; ?>" class="<?php echo strtolower($order); ?>">Manufacturer</a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_manufacturer_name; ?>">Manufacturer</a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-left">
                                                    <?php if ($sort == 'pc.name') { ?>
                                                        <a href="<?php echo $sort_category_name; ?>" class="<?php echo strtolower($order); ?>">Category</a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_category_name; ?>">Category</a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-left">
                                                    <?php if ($sort == 'p.model') { ?>
                                                        <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center"><?php echo $column_selling_price; ?></td>
                                                <td class="text-right">
                                                    <?php if ($sort == 'p.price') { ?>
                                                        <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">Quantitiy Available</td>
                                                <td class="text-center">At Principal's Warehouse</td>
                                                <td class="text-center">At Our Warehouse</td>
                                                <td class="text-right">
                                                    <?php if ($sort == 'p.quantity') { ?>
                                                        <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-left">
                                                    <?php if ($sort == 'p.status') { ?>
                                                        <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-left">
                                                    <?php if ($sort == 'p.force_update_price') { ?>
                                                        <a href="<?php echo $sort_force_update_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_force_update_price; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_force_update_price; ?>"><?php echo $column_force_update_price; ?></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($products) { ?>
                                                <?php foreach ($products as $product) { ?>
                                                    <tr style="<?php if($product['sync_status']=='End Of Life'||$product['sync_status']=='Modified - Price'||$product['sync_status']=='Modified'||$product['sync_status']=='Deleted by User') { ?> background-color:#f6fa63 <?php } ?>">
                                                        <td class="text-center">
                                                            <?php if (in_array($product['product_id'], $selected)) { ?>
                                                                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                                                            <?php } else { ?>
                                                                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                                                            <?php } ?>
                                                            <?php 
                                                                //echo $product['product_id'];
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo $product['sync_status']; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($product['image']) { ?>
                                                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" style="max-height: 40px;max-width: 40px;" />
                                                            <?php } else { ?>
                                                                <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-left"><?php echo $product['name']; ?></td>
                                                         <td class="text-left"><?php echo $product['manufacturer_name']; ?></td>
                                                          <td class="text-left"><?php echo $product['category_name']; ?></td>
                                                        <td class="text-left"><?php echo $product['model']; ?></td>
                                                        <td class="text-right"><?php echo $product['selling_price']; ?></td>
                                                        <td class="text-right"><?php echo $product['price']; ?></td>
                                                        <td class="text-right"><?php if ($product['quantity_available'] == "-" || $product['quantity_available'] == "") { ?>
                                                                        <?php echo $product['quantity_available']; ?>
                                                            <?php } elseif ($product['quantity_available'] <= 0) { ?>
                                                                <span class="label label-warning"><?php echo $product['quantity_available']; ?></span>
                                                            <?php } elseif ($product['quantity_available'] <= 5) { ?>
                                                                <span class="label label-danger"><?php echo $product['quantity_available']; ?></span>
                                                            <?php } else { ?>
                                                                <span class="label label-success"><?php echo $product['quantity_available']; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php if ($product['erp_balance'] == "-" || $product['erp_balance'] == "") { ?>
                                                                <?php echo $product['erp_balance']; ?>
                                                            <?php } elseif ($product['erp_balance'] <= 0) { ?>
                                                                <span class="label label-warning"><?php echo $product['erp_balance']; ?></span>
                                                            <?php } elseif ($product['erp_balance'] <= 5) { ?>
                                                                <span class="label label-danger"><?php echo $product['erp_balance']; ?></span>
                                                            <?php } else { ?>
                                                                <span class="label label-success"><?php echo $product['erp_balance']; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php if ($product['wms_balance'] == "-" || $product['wms_balance'] == "") { ?>
                                                                <?php echo $product['wms_balance']; ?>
                                                            <?php } elseif ($product['wms_balance'] <= 0) { ?>
                                                                <span class="label label-warning"><?php echo $product['wms_balance']; ?></span>
                                                            <?php } elseif ($product['wms_balance'] <= 5) { ?>
                                                                <span class="label label-danger"><?php echo $product['wms_balance']; ?></span>
                                                            <?php } else { ?>
                                                                <span class="label label-success"><?php echo $product['wms_balance']; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php if ($product['quantity'] <= 0) { ?>
                                                                <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                                                            <?php } elseif ($product['quantity'] <= 5) { ?>
                                                                <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                                                            <?php } else { ?>
                                                                <span class="label label-success"><?php echo $product['quantity']; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-left"><?php echo $product['status']; ?></td>
                                                         <td class="text-left"><?php echo $product['force_update_price']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                            
                        <?php } else if($selected_table!="") { ?>
                        
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
                            </div>
                            <form action="<?php echo $sync; ?>" method="post" enctype="multipart/form-data" id="form-sync">
                                <input type="hidden" id="sync_type" name="sync_type" value="" />
                                <input type="hidden" id="sync_all_new" name="sync_all_new" value="" />
                                <input type="hidden" id="sync_table" name="sync_table" value="<?php echo $selected_table;?>" />
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                                <td style="width: 5px;" class="text-center">
                                                    Sync Status
                                                </td>
                                                <?php if(count($table_columns)>0) { 
                                                	foreach($table_columns as $keyColumn => $valueColumn) { ?>
                                                    <td class="text-center">
                                                        <?php if ($sort == $valueColumn) { ?>
                                                            <a href="<?php echo ${$valueColumn}; ?>" class="<?php echo strtolower($order); ?>"><?php echo $valueColumn; ?></a>
                                                        <?php } else { ?>
                                                            <a href="<?php echo ${$valueColumn}; ?>"><?php echo $valueColumn; ?></a>
                                                        <?php } ?>
                                                    </td>
                                                <?php }
                                                } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($products) && count($products)>0) { ?>
                                                <?php foreach ($products as $product) { ?>
                                                    <tr style="<?php if($product['sync_status']=='End Of Life'||$product['sync_status']=='Modified'||$product['sync_status']=='Deleted by User') { ?> background-color:#f6fa63 <?php } ?>">
                                                        <td class="text-center">
                                                            <input type="checkbox" name="selected[]" value="<?php echo $product['sync_unique_id']; ?>" />
                                                            <?php //echo $product['sync_unique_id']; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo $product['sync_status']; ?>
                                                        </td>
                                                        <?php foreach($table_columns as $keyColumn => $valueColumn) { ?>
                                                            <td class="text-left"><?php echo $product[$valueColumn]; ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td class="text-center" colspan="100"><?php echo $text_no_results; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                            
                        <?php }else { ?>
                                            
                        <form action="<?php echo $sync; ?>" method="post" enctype="multipart/form-data" id="form-sync">
                                <input type="hidden" id="sync_type" name="sync_type" value="" />
                                <input type="hidden" id="sync_all_new" name="sync_all_new" value="" />
                        </form>
                                            
                        <?php } ?>
                        
                        <div class="row">
                            <div class="col-sm-8 text-left"><?php if(isset($pagination)) { echo $pagination; } ?></div>
                            <div class="col-sm-4 text-right"><?php if(isset($results)) { echo $results; } ?></div>
                        </div>
                        <br />
                        <?php if(isset($total_records)&&$total_records>0) { ?>
                        <?php if($curr_selected_table=="product") { ?>
                            <div class="row">
                                <div class="col-sm-1 text-left">
                                    <label class="control-label">Option:</label>
                                </div>
                                <div class="col-sm-1 text-left">
                                    <label class="control-label">:</label>
                                </div>
                                <div class="col-sm-10">
                                <label for="forceprice" class="btn btn-info">Force update with BRP price<input id="forceprice" name="forceprice" class="badgebox" type="checkbox" checked><span class="badge">&check;</span></label>
                                    
                                </div>
                                
                            </div>
                        <div class="row"><div class="col-sm-12">&nbsp;</div></div>
                        <?php } ?>
                            <div class="row">
                                <div class="col-sm-1 text-left">
                                    <label class="control-label">Sync</label>
                                </div>
                                <div class="col-sm-1 text-left">
                                    <label class="control-label">:</label>
                                </div>
                                <div class="col-sm-10">
                                    
                                    <a onclick="javascript:syncSelected();" class="btn btn-primary"><span><?php echo $button_sync_selected; ?></span></a>
                                    &nbsp;
                                    <?php /*
                                    <a onclick="javascript:syncNewUpdate();" class="btn btn-primary"><span><?php echo $button_sync_new_changes; ?></span></a>
                                    <a onclick="javascript:syncAll();" class="btn btn-primary"><span><?php echo $button_sync_all; ?></span></a>
                                    */ ?>
                                    <span class="rounded-border">
                                        <a onclick="javascript:sync();" class="btn btn-primary"><span><?php echo $button_sync; ?></span></a>
                                        <label for="new" class="btn btn-info">New <input type="checkbox" id="new" name="new" class="badgebox"><span class="badge">&check;</span></label>
                                        <label for="modified" class="btn btn-info">Modified <input type="checkbox" id="modified" name="modified" class="badgebox"><span class="badge">&check;</span></label>
                                        <?php if($curr_selected_table=="product") { ?>
                                            <label for="eol" class="btn btn-info">EOL <input type="checkbox" id="eol" name="eol" class="badgebox"><span class="badge">&check;</span></label>
                                         <?php } ?>
                                         <?php if($curr_selected_table!="banner" && $curr_selected_table!="manufacturer" && $curr_selected_table!="attribute" && $curr_selected_table!="filter" && $curr_selected_table!="customer_group" && $curr_selected_table!="zone" && $curr_selected_table!="country" && $curr_selected_table!="weight_class" && $curr_selected_table!="length_class") { ?>
                                            <label for="rel" class="btn btn-info">Deleted by User <input type="checkbox" id="rel" name="rel" class="badgebox"><span class="badge">&check;</span></label>
                                         <?php } ?>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>

        
					</div>
                    
                    
					<div class="tab-pane" id="tab-history">
                    	
						<div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-list"></i> Sync Histories</h3>
                        </div>
                            
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td style="width: 1px;" class="text-center">
                                            Latest Synced Table
                                        </td>
                                        <td style="width: 5px;" class="text-center">
                                            Latest Synced Date
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($sync_histories) && count($sync_histories)>0) { ?>
                                        <?php foreach ($sync_histories as $sync_history) { ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php echo $sync_history['sync_table']; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $sync_history['sync_date']; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        
					</div>
                    
                    
					<div class="tab-pane" id="tab-settings">
                        <form action="<?php echo $settings; ?>" method="post" enctype="multipart/form-data" id="settings">
                        
                            <div class="well">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="input-name"><?php echo $entry_api_url; ?></label>
                                            <input type="text" name="data_sync_api_url" value="<?php echo $data_sync_api_url; ?>" placeholder="<?php echo $entry_api_url; ?>" id="input-api-url" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="input-model"><?php echo $entry_api_partner_key; ?></label>
                                            <input type="text" name="data_sync_api_partner_key" value="<?php echo $data_sync_api_partner_key; ?>" placeholder="<?php echo $entry_api_partner_key; ?>" id="input-api-partner-key" class="form-control" />
                                        </div>
                                        <button type="button" class="btn btn-primary pull-right" onclick="javascript:updateSettings();"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
                                   </div>
                                </div>
                            </div>
                            
						</form>
					</div>

				</div>
			</div>
		</div>

	</div>

<script type="text/javascript"><!--
function getLoading() {
        if($('#sync_all_new').val() == "") {
            $('#data_sync_notification').html('<div class="alert alert-info"><i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> <div id="data_sync_loading"><img src="view/image/data_sync/loading.gif" /><?php echo $text_loading_notifications; ?></div></div>');
        }else {
            $('#data_sync_notification').html('<div class="alert alert-info"><i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> <div id="data_sync_loading"><img src="view/image/data_sync/loading.gif" /><?php echo $text_loading_notifications_all; ?></div></div>');
        }
}
/*function getNotifications() {
	$('#data_sync_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> <div id="data_sync_loading"><img src="view/image/export-import/loading.gif" /><?php echo $text_loading_notifications; ?></div>');
	setTimeout(
		function(){
			$.ajax({
				type: 'GET',
				url: 'index.php?route=tool/data_sync/getNotifications&token=<?php echo $token; ?>',
				dataType: 'json',
				success: function(json) {
					if (json['error']) {
						$('#data_sync_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+json['error']+' <span style="cursor:pointer;font-weight:bold;text-decoration:underline;float:right;" onclick="getNotifications();"><?php echo $text_retry; ?></span>');
					} else if (json['message']) {
						$('#data_sync_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+json['message']);
					} else {
						$('#data_sync_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+'<?php echo $error_no_news; ?>');
					}
				},
				failure: function(){
					$('#data_sync_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+'<?php echo $error_notifications; ?> <span style="cursor:pointer;font-weight:bold;text-decoration:underline;float:right;" onclick="getNotifications();"><?php echo $text_retry; ?></span>');
				},
				error: function() {
					$('#data_sync_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+'<?php echo $error_notifications; ?> <span style="cursor:pointer;font-weight:bold;text-decoration:underline;float:right;" onclick="getNotifications();"><?php echo $text_retry; ?></span>');
				}
			});
		},
		500
	);
}

function check_range_type(export_type) {
	if ((export_type=='p') || (export_type=='c') || (export_type=='u')) {
		$('#range_type').show();
		$('#range_type_id').prop('checked',true);
		$('#range_type_page').prop('checked',false);
		$('.id').show();
		$('.page').hide();
	} else {
		$('#range_type').hide();
	}
}

$(document).ready(function() {

	check_range_type($('input[name=export_type]:checked').val());

	$("#range_type_id").click(function() {
		$(".page").hide();
		$(".id").show();
	});

	$("#range_type_page").click(function() {
		$(".id").hide();
		$(".page").show();
	});

	$('input[name=export_type]').click(function() {
		check_range_type($(this).val());
	});

	$('span.close').click(function() {
		$(this).parent().remove();
	});

	$('a[data-toggle="tab"]').click(function() {
		$('#data_sync_notification').remove();
	});

	getNotifications();
	
});

function checkFileSize(id) {
	// See also http://stackoverflow.com/questions/3717793/javascript-file-upload-size-validation for details
	var input, file, file_size;

	if (!window.FileReader) {
		// The file API isn't yet supported on user's browser
		return true;
	}

	input = document.getElementById(id);
	if (!input) {
		// couldn't find the file input element
		return true;
	}
	else if (!input.files) {
		// browser doesn't seem to support the `files` property of file inputs
		return true;
	}
	else if (!input.files[0]) {
		// no file has been selected for the upload
		alert( "<?php echo $error_select_file; ?>" );
		return false;
	}
	else {
		file = input.files[0];
		file_size = file.size;
		<?php if (!empty($post_max_size)) { ?>
		// check against PHP's post_max_size
		post_max_size = <?php echo $post_max_size; ?>;
		if (file_size > post_max_size) {
			alert( "<?php echo $error_post_max_size; ?>" );
			return false;
		}
		<?php } ?>
		<?php if (!empty($upload_max_filesize)) { ?>
		// check against PHP's upload_max_filesize
		upload_max_filesize = <?php echo $upload_max_filesize; ?>;
		if (file_size > upload_max_filesize) {
			alert( "<?php echo $error_upload_max_filesize; ?>" );
			return false;
		}
		<?php } ?>
		return true;
	}
}

function uploadData() {
	if (checkFileSize('upload')) {
		$('#import').submit();
	}
}

function isNumber(txt){ 
	var regExp=/^[\d]{1,}$/;
	return regExp.test(txt); 
}

function validateExportForm(id) {
	var export_type = $('input[name=export_type]:checked').val();
	if ((export_type!='c') && (export_type!='p') && (export_type!='u')) {
		return true;
	}

	var val = $("input[name=range_type]:checked").val();
	var min = $("input[name=min]").val();
	var max = $("input[name=max]").val();

	if ((min=='') && (max=='')) {
		return true;
	}

	if (!isNumber(min) || !isNumber(max)) {
		alert("<?php echo $error_param_not_number; ?>");
		return false;
	}

	var count_item;
	switch (export_type) {
		case 'p': count_item = <?php echo $count_product-1; ?>;  break;
		case 'c': count_item = <?php echo $count_category-1; ?>; break;
		default:  count_item = <?php echo $count_customer-1; ?>; break;
	}
	var batchNo = parseInt(count_item/parseInt(min))+1; // Maximum number of item-batches, namely, item number/min, and then rounded up (that is, integer plus 1)
	var minItemId;
	switch (export_type) {
		case 'p': minItemId = parseInt( <?php echo $min_product_id; ?> );  break;
		case 'c': minItemId = parseInt( <?php echo $min_category_id; ?> ); break;
		default:  minItemId = parseInt( <?php echo $min_customer_id; ?> ); break;
	
	}
	var maxItemId;
	switch (export_type) {
		case 'p': maxItemId = parseInt( <?php echo $max_product_id; ?> );  break;
		case 'c': maxItemId = parseInt( <?php echo $max_category_id; ?> ); break;
		default:  maxItemId = parseInt( <?php echo $max_customer_id; ?> ); break;
	
	}

	if (val=="page") {  // Min for the batch size, Max for the batch number
		if (parseInt(max) <= 0) {
			alert("<?php echo $error_batch_number; ?>");
			return false;
		}
		if (parseInt(max) > batchNo) {        
			alert("<?php echo $error_page_no_data; ?>"); 
			return false;
		} else {
			$("input[name=max]").val(parseInt(max)+1);
		}
	} else {
		if (minItemId <= 0) {
			alert("<?php echo $error_min_item_id; ?>");
			return false;
		}
		if (parseInt(min) > maxItemId || parseInt(max) < minItemId || parseInt(min) > parseInt(max)) {  
			alert("<?php echo $error_id_no_data; ?>"); 
			return false;
		}
	}
	return true;
}

function downloadData() {
	if (validateExportForm('export')) {
		$('#export').submit();
	}
}*/

function updateSettings() {
	$('#settings').submit();
}
function syncSelected() {
	getLoading();
	var total = $('[name="selected[]"]:checked').length;
	if(total==0) {
		alert("Please select row to sync.");
	} else {
		$("#sync_type").val("sync_selected");
                
                if(document.getElementById('forceprice') != null) {
                    if(!document.getElementById('forceprice').disabled) {
                        if(document.getElementById('forceprice').checked) {
                            $('#updateprice').val('forceupdate');
                        }
                    }
                }
                
		$('#form-sync').submit();
	}
}
function syncNewUpdate() {
	getLoading();
	$("#sync_type").val("sync_new_update");
	$('#form-sync').submit();
}
function syncAll() {
	getLoading();
	$("#sync_type").val("sync_all");
	$('#form-sync').submit();
}
function sync() {
	getLoading();
	var sync_new = $('input[name=new]:checked').val();
	var sync_mod = $('input[name=modified]:checked').val();
	var sync_eol = $('input[name=eol]:checked').val();
        var sync_rel = $('input[name=rel]:checked').val();
	var syncOf = "sync";
	if(sync_new) {
		syncOf += "_new";
		//alert(sync_new);
	} if(sync_mod) {
		syncOf += "_mod";
		//alert(sync_mod);
	} if(sync_eol) {
		syncOf += "_eol";
		//alert(sync_eol);
	} if(sync_rel) {
		syncOf += "_rel";
		//alert(sync_eol);
	}
        
        if(document.getElementById('forceprice') != null) {
            if(!document.getElementById('forceprice').disabled) {
                if(document.getElementById('forceprice').checked) {
                    $('#updateprice').val('forceupdate');
                }
            }
        }
        
	if(syncOf=="sync") {
		alert("Please select option(s) to sync.");
	} else {
		$("#sync_type").val(syncOf);
		$('#form-sync').submit();
	}
}
function syncAllNew() {
    
        if(confirm("Are you sure you want to initiate your first time sync to the BRP server? This may take some time.")) {
            $('#sync_all_new').val('allnew');
            getLoading();
            $("#sync_type").val('sync_new');
            $('#form-sync').submit();
        }
    
	
	
}
$('#button-table').on('click', function() {
	var url = 'index.php?route=tool/data_sync&token=<?php echo $token; ?>';
	var table = $('select[name=\'table\']').val();
        var tempMode = $('input[name=\'tempMode\']:checked').val();
	if (table) {
		url += '&table=' + encodeURIComponent(table);
                url += '&firstGen=' + encodeURIComponent(tempMode);
                url += '&tempMode=' + encodeURIComponent(tempMode);
	}
	location = url;
});
$('#button-filter').on('click', function() {
	var url = 'index.php?route=tool/data_sync&token=<?php echo $token; ?>';
	var table = $('select[name=\'table\']').val();
	if (table) {
		url += '&table=' + encodeURIComponent(table);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
        var filter_manufacturer_name = $('input[name=\'filter_manufacturer_name\']').val();
	if (filter_manufacturer_name) {
		url += '&filter_manufacturer_name=' + encodeURIComponent(filter_manufacturer_name);
	}
        var filter_category_name = $('input[name=\'filter_category_name\']').val();
	if (filter_category_name) {
		url += '&filter_category_name=' + encodeURIComponent(filter_category_name);
	}
	var filter_model = $('input[name=\'filter_model\']').val();
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	var filter_price = $('input[name=\'filter_price\']').val();
	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}
	var filter_quantity = $('input[name=\'filter_quantity\']').val();
	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}
	var filter_status = $('select[name=\'filter_status\']').val();
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_force_update_price = $('select[name=\'filter_force_update_price\']').val();
	if (filter_force_update_price != '*') {
		url += '&filter_force_update_price=' + encodeURIComponent(filter_force_update_price);
	}
	var filter_image = $('select[name=\'filter_image\']').val();
	if (filter_image != '*') {
		url += '&filter_image=' + encodeURIComponent(filter_image);
	}
        var tempMode = $('input[name=\'tempMode\']:checked').val();
        if (tempMode != '') {
                url += '&tempMode=' + encodeURIComponent(tempMode);
	}
        
	//alert(url);
	location = url;
});

$( "#input-table" ).change(function() {
    if($( "#input-table" ).val() == "oc_product" ){
        $( "#syncstatusform" ).show();
    }else {
        $( "#syncstatusform" ).hide();
    }
});

$('#radN, #radM, #radMP, #radEOL').change(function(){
    if(this.checked) {
        alert("The option you selected will initiate the comparison for all the products and may take time to load.");
    }
});

/*$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});*/
//--></script>
</div>
<?php echo $footer; ?>