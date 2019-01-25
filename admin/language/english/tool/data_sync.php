<?php
// Heading
$_['heading_title']                         = 'Data Sync';
/*
// Text
$_['text_success']                          = 'Success: You have successfully imported your data!';
$_['text_success_settings']                 = 'Success: You have successfully updated the settings for this tool!';
$_['text_export_type_category']             = 'Categories (including category data and filters)';
$_['text_export_type_category_old']         = 'Categories';
$_['text_export_type_product']              = 'Products (including product data, options, specials, discounts, rewards, attributes and filters)';
$_['text_export_type_product_old']          = 'Products (including product data, options, specials, discounts, rewards and attributes)';
$_['text_export_type_option']               = 'Option definitions';
$_['text_export_type_attribute']            = 'Attribute definitions';
$_['text_export_type_filter']               = 'Filter definitions';
$_['text_export_type_customer']             = 'Customers';
$_['text_yes']                              = 'Yes';
$_['text_no']                               = 'No';
$_['text_nochange']                         = 'No server data has been changed.';
$_['text_log_details']                      = 'See also \'System &gt; Error Logs\' for more details.';
$_['text_log_details_2_0_x']                = 'See also \'Tools &gt; Error Logs\' for more details.';
$_['text_log_details_2_1_x']                = 'See also \'System &gt; Tools &gt; Error Logs\' for more details.';
$_['text_loading_notifications']            = 'Getting messages';
$_['text_retry']                            = 'Retry';

// Entry
$_['entry_import']                          = 'Import from a XLS, XLSX or ODS spreadsheet file';
$_['entry_export']                          = 'Sync the data from Centralized Data Server.'; //Export requested data to a XLSX spreadsheet file
$_['entry_export_type']                     = 'Select what data you want to sync:'; //Select what data you want to export:
$_['entry_range_type']                      = 'Please select the data range you want to sync:'; //Please select the data range you want to export:
$_['entry_start_id']                        = 'Start id:';
$_['entry_start_index']                     = 'Counts per batch:';
$_['entry_end_id']                          = 'End id:';
$_['entry_end_index']                       = 'The batch number:';
$_['entry_incremental']                     = 'Use incremental Import';
$_['entry_upload']                          = 'File to be uploaded';
$_['entry_settings_use_option_id']          = 'Use <em>option_id</em> instead of <em>option name</em> in worksheets \'ProductOptions\' and \'ProductOptionValues\'';
$_['entry_settings_use_option_value_id']    = 'Use <em>option_value_id</em> instead of <em>option_value name</em> in worksheet \'ProductOptionValues\'';
$_['entry_settings_use_attribute_group_id'] = 'Use <em>attribute_group_id</em> instead of <em>attribute_group name</em> in worksheet \'ProductAttributes\'';
$_['entry_settings_use_attribute_id']       = 'Use <em>attribute_id</em> instead of <em>attribute name</em> in worksheet \'ProductAttributes\'';
$_['entry_settings_use_filter_group_id']    = 'Use <em>filter_group_id</em> instead of <em>filter_group name</em> in worksheets \'ProductFilters\' and \'CategoryFilters\'';
$_['entry_settings_use_filter_id']          = 'Use <em>filter_id</em> instead of <em>filter name</em> in worksheets \'ProductFilters\' and \'CategoryFilters\'';
$_['entry_settings_use_export_cache']       = 'Use phpTemp cache for large Exports (will be slightly slower)';
$_['entry_settings_use_import_cache']       = 'Use phpTemp cache for large Imports (will be slightly slower)';

// Error
$_['error_permission']                      = 'Warning: You do not have permission to modify Export/Import!';
$_['error_upload']                          = 'Uploaded spreadsheet file has validation errors!';
$_['error_categories_header']               = 'Export/Import: Invalid header in the Categories worksheet';
$_['error_category_filters_header']         = 'Export/Import: Invalid header in the CategoryFilters worksheet';
$_['error_products_header']                 = 'Export/Import: Invalid header in the Products worksheet';
$_['error_additional_images_header']        = 'Export/Import: Invalid header in the AdditionalImages worksheet';
$_['error_specials_header']                 = 'Export/Import: Invalid header in the Specials worksheet';
$_['error_discounts_header']                = 'Export/Import: Invalid header in the Discounts worksheet';
$_['error_rewards_header']                  = 'Export/Import: Invalid header in the Rewards worksheet';
$_['error_product_options_header']          = 'Export/Import: Invalid header in the ProductOptions worksheet';
$_['error_product_option_values_header']    = 'Export/Import: Invalid header in the ProductOptionValues worksheet';
$_['error_product_attributes_header']       = 'Export/Import: Invalid header in the ProductAttributes worksheet';
$_['error_product_filters_header']          = 'Export/Import: Invalid header in the ProductFilters worksheet';
$_['error_options_header']                  = 'Export/Import: Invalid header in the Options worksheet';
$_['error_option_values_header']            = 'Export/Import: Invalid header in the OptionValues worksheet';
$_['error_attribute_groups_header']         = 'Export/Import: Invalid header in the AttributeGroups worksheet';
$_['error_attributes_header']               = 'Export/Import: Invalid header in the Attributes worksheet';
$_['error_filter_groups_header']            = 'Export/Import: Invalid header in the FilterGroups worksheet';
$_['error_filters_header']                  = 'Export/Import: Invalid header in the Filters worksheet';
$_['error_customers_header']                = 'Export/Import: Invalid header in the Customers worksheet';
$_['error_addresses_header']                = 'Export/Import: Invalid header in the Addresses worksheet';
$_['error_product_options']                 = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before ProductOptions';
$_['error_product_option_values']           = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before ProductOptionValues';
$_['error_product_option_values_2']         = 'Export/Import: Missing ProductOptions worksheet, or ProductOptions worksheet not listed before ProductOptionValues';
$_['error_product_option_values_3']         = 'Export/Import: ProductOptionValues worksheet also expected after a ProductOptions worksheet';
$_['error_additional_images']               = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before AdditionalImages';
$_['error_specials']                        = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before Specials';
$_['error_discounts']                       = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before Discounts';
$_['error_rewards']                         = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before Rewards';
$_['error_product_attributes']              = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before ProductAttributes';
$_['error_attributes']                      = 'Export/Import: Missing AttributeGroups worksheet, or AttributeGroups worksheet not listed before Attributes';
$_['error_attributes_2']                    = 'Export/Import: Attributes worksheet also expected after an AttributeGroups worksheet';
$_['error_category_filters']                = 'Export/Import: Missing Categories worksheet, or Categories worksheet not listed before CategoryFilters';
$_['error_product_filters']                 = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before ProductFilters';
$_['error_filters']                         = 'Export/Import: Missing FilterGroups worksheet, or FilterGroups worksheet not listed before Filters';
$_['error_filters_2']                       = 'Export/Import: Filters worksheet also expected after a FilterGroups worksheet';
$_['error_option_values']                   = 'Export/Import: Missing Options worksheet, or Options worksheet not listed before OptionValues';
$_['error_option_values_2']                 = 'Export/Import: OptionValues worksheet also expected after an Options worksheet';
$_['error_post_max_size']                   = 'File size is greater than %1 (see PHP setting \'post_max_size\')';
$_['error_upload_max_filesize']             = 'File size is greater than %1 (see PHP setting \'upload_max_filesize\')';
$_['error_select_file']                     = 'Please select a file before clicking \'Import\'';
$_['error_id_no_data']                      = 'No data between start-id and end-id.';
$_['error_page_no_data']                    = 'No more data.';
$_['error_param_not_number']                = 'Values for data range must be whole numbers.';
$_['error_upload_name']                     = 'Missing file name for upload';
$_['error_upload_ext']                      = 'Uploaded file has not one of the \'.xls\', \'.xlsx\' or \'.ods\' file name extensions, it might not be a spreadsheet file!';
$_['error_notifications']                   = 'Could not load messages from MHCCORP.COM.';
$_['error_no_news']                         = 'No messages';
$_['error_batch_number']                    = 'Batch number must be greater than 0';
$_['error_min_item_id']                     = 'Start id must be greater than 0';
$_['error_option_name']                     = 'Option \'%1\' is defined multiple times!<br />';
$_['error_option_name']                    .= 'In the Settings-tab please activate the following:<br />';
$_['error_option_name']                    .= "Use <em>option_id</em> instead of <em>option name</em> in worksheets 'ProductOptions' and 'ProductOptionValues'";
$_['error_option_value_name']               = 'Option value \'%1\' is defined multiple times within its option!<br />';
$_['error_option_value_name']              .= 'In the Settings-tab please activate the following:<br />';
$_['error_option_value_name']              .= "Use <em>option_value_id</em> instead of <em>option_value name</em> in worksheet 'ProductOptionValues'";
$_['error_attribute_group_name']            = 'AttributeGroup \'%1\' is defined multiple times!<br />';
$_['error_attribute_group_name']           .= 'In the Settings-tab please activate the following:<br />';
$_['error_attribute_group_name']           .= "Use <em>attribute_group_id</em> instead of <em>attribute_group name</em> in worksheets 'ProductAttributes'";
$_['error_attribute_name']                  = 'Attribute \'%1\' is defined multiple times within its attribute group!<br />';
$_['error_attribute_name']                 .= 'In the Settings-tab please activate the following:<br />';
$_['error_attribute_name']                 .= "Use <em>attribute_id</em> instead of <em>attribute name</em> in worksheet 'ProductAttributes'";
$_['error_filter_group_name']               = 'FilterGroup \'%1\' is defined multiple times!<br />';
$_['error_filter_group_name']              .= 'In the Settings-tab please activate the following:<br />';
$_['error_filter_group_name']              .= "Use <em>filter_group_id</em> instead of <em>filter_group name</em> in worksheets 'ProductFilters'";
$_['error_filter_name']                     = 'Filter \'%1\' is defined multiple times within its filter group!<br />';
$_['error_filter_name']                    .= 'In the Settings-tab please activate the following:<br />';
$_['error_filter_name']                    .= "Use <em>filter_id</em> instead of <em>filter name</em> in worksheet 'ProductFilters'";

$_['error_missing_customer_group']                      = 'Export/Import: Missing customer_groups in worksheet \'%1\'!';
$_['error_invalid_customer_group']                      = 'Export/Import: Undefined customer_group \'%2\' used in worksheet \'%1\'!';
$_['error_missing_product_id']                          = 'Export/Import: Missing product_ids in worksheet \'%1\'!';
$_['error_missing_option_id']                           = 'Export/Import: Missing option_ids in worksheet \'%1\'!';
$_['error_invalid_option_id']                           = 'Export/Import: Undefined option_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_option_name']                         = 'Export/Import: Missing option_names in worksheet \'%1\'!';
$_['error_invalid_product_id_option_id']                = 'Export/Import: Option_id \'%3\' not specified for product_id \'%2\' in worksheet \'%4\', but it is used in worksheet \'%1\'!';
$_['error_missing_option_value_id']                     = 'Export/Import: Missing option_value_ids in worksheet \'%1\'!';
$_['error_invalid_option_id_option_value_id']           = 'Export/Import: Undefined option_value_id \'%3\' for option_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_option_value_name']                   = 'Export/Import: Missing option_value_names in worksheet \'%1\'!';
$_['error_invalid_option_id_option_value_name']         = 'Export/Import: Undefined option_value_name \'%3\' for optiion_id \'%2\' used in worksheet \'%1\'!'; 
$_['error_invalid_option_name']                         = 'Export/Import: Undefined option_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_product_id_option_name']              = 'Export/Import: Option_name \'%3\' not specified for product_id \'%2\' in worksheet \'%4\', but it is used in worksheet \'%1\'!';
$_['error_invalid_option_name_option_value_id']         = 'Export/Import: Undefined option_value_id \'%3\' for option_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_option_name_option_value_name']       = 'Export/Import: Undefined option_value_name \'%3\' for option_name \'%2\' used in worksheet \'%1\'!';
$_['error_missing_attribute_group_id']                  = 'Export/Import: Missing attribute_group_ids in worksheet \'%1\'!';
$_['error_invalid_attribute_group_id']                  = 'Export/Import: Undefined attribute_group_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_attribute_group_name']                = 'Export/Import: Missing attribute_group_names in worksheet \'%1\'!';
$_['error_missing_attribute_id']                        = 'Export/Import: Missing attribute_ids in worksheet \'%1\'!';
$_['error_invalid_attribute_group_id_attribute_id']     = 'Export/Import: Undefined attribute_id \'%3\' for attribute_group_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_attribute_name']                      = 'Export/Import: Missing attribute_names in worksheet \'%1\'!';
$_['error_invalid_attribute_group_id_attribute_name']   = 'Export/Import: Undefined attribute_name \'%3\' for optiion_id \'%2\' used in worksheet \'%1\'!'; 
$_['error_invalid_attribute_group_name']                = 'Export/Import: Undefined attribute_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_attribute_group_name_attribute_id']   = 'Export/Import: Undefined attribute_id \'%3\' for attribute_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_attribute_group_name_attribute_name'] = 'Export/Import: Undefined attribute_name \'%3\' for attribute_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_missing_filter_group_id']                     = 'Export/Import: Missing filter_group_ids in worksheet \'%1\'!';
$_['error_invalid_filter_group_id']                     = 'Export/Import: Undefined filter_group_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_filter_group_name']                   = 'Export/Import: Missing filter_group_names in worksheet \'%1\'!';
$_['error_missing_filter_id']                           = 'Export/Import: Missing filter_ids in worksheet \'%1\'!';
$_['error_invalid_filter_group_id_filter_id']           = 'Export/Import: Undefined filter_id \'%3\' for filter_group_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_filter_name']                         = 'Export/Import: Missing filter_names in worksheet \'%1\'!';
$_['error_invalid_filter_group_id_filter_name']         = 'Export/Import: Undefined filter_name \'%3\' for optiion_id \'%2\' used in worksheet \'%1\'!'; 
$_['error_invalid_filter_group_name']                   = 'Export/Import: Undefined filter_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_filter_group_name_filter_id']         = 'Export/Import: Undefined filter_id \'%3\' for filter_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_filter_group_name_filter_name']       = 'Export/Import: Undefined filter_name \'%3\' for filter_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_product_id']                          = 'Export/Import: Invalid product_id \'%2\' used in worksheet \'%1\'!';
$_['error_duplicate_product_id']                        = 'Export/Import: Duplicate product_id \'%2\' used in worksheet \'%1\'!';
$_['error_unlisted_product_id']                         = 'Export/Import: Worksheet \'%1\' cannot use product_id \'%2\' because it is not listed in worksheet \'Products\'!';
$_['error_wrong_order_product_id']                      = 'Export/Import: Worksheet \'%1\' uses product_id \'%2\' in the wrong order. Ascending order expected!';
$_['error_filter_not_supported']                        = 'Export/Import: Filters are not supported in your OpenCart version!';
$_['error_missing_category_id']                         = 'Export/Import: Missing category_ids in worksheet \'%1\'!';
$_['error_invalid_category_id']                         = 'Export/Import: Invalid category_id \'%2\' used in worksheet \'%1\'!';
$_['error_duplicate_category_id']                       = 'Export/Import: Duplicate category_id \'%2\' used in worksheet \'%1\'!';
$_['error_wrong_order_category_id']                     = 'Export/Import: Worksheet \'%1\' uses category_id \'%2\' in the wrong order. Ascending order expected!';
$_['error_unlisted_category_id']                        = 'Export/Import: Worksheet \'%1\' cannot use category_id \'%2\' because it is not listed in worksheet \'Categories\'!';
$_['error_addresses']                                   = 'Export/Import: Missing Cutomers worksheet, or Customers worksheet not listed before Addresses!';
$_['error_addresses_2']                                 = 'Export/Import: Addresses worksheet also expected after Customers worksheet';
$_['error_invalid_store_id']                            = 'Export/Import: Invalid store_id=\'%1\' used in worksheet \'%2\'!';
$_['error_missing_customer_id']                         = 'Export/Import: Missing customer_ids in worksheet \'%1\'!';
$_['error_invalid_customer_id']                         = 'Export/Import: Invalid customer_id \'%2\' used in worksheet \'%1\'!';
$_['error_duplicate_customer_id']                       = 'Export/Import: Duplicate customer_id \'%2\' used in worksheet \'%1\'!';
$_['error_wrong_order_customer_id']                     = 'Export/Import: Worksheet \'%1\' uses customer_id \'%2\' in the wrong order. Ascending order expected!';
$_['error_unlisted_customer_id']                        = 'Export/Import: Worksheet \'%1\' cannot use customer_id \'%2\' because it is not listed in worksheet \'Customers\'!';
$_['error_missing_country_col']                         = 'Export/Import: Worksheet \'%1\' has no \'country\' column heading!';
$_['error_missing_zone_col']                            = 'Export/Import: Worksheet \'%1\' has no \'zone\' column heading!';
$_['error_undefined_country']                           = 'Export/Import: Undefined country \'%1\' used in worksheet \'%2\'!';
$_['error_undefined_zone']                              = 'Export/Import: Undefined zone \'%2\' for country \'%1\' used in worksheet \'%3\'!';
$_['error_incremental_only']                            = 'Export/Import: Worksheet \'%1\' can only be imported in incremental mode for the time being!';

// Tabs
$_['tab_sync']                               = 'Sync'; //Export
$_['tab_history']                            = 'History'; //Import
$_['tab_settings']                           = 'Settings'; //Settings

// Button labels
$_['button_sync']                         	 = 'Sync'; //Export
$_['button_sync_all']                     	 = 'Sync All'; //Export
$_['button_history']                         = 'History'; //Import
$_['button_settings']                        = 'Update Settings';

// Help
$_['help_range_type']                       = '(Optional, leave empty if not needed)';
$_['help_incremental_yes']                  = '(Update and/or add data)';
$_['help_incremental_no']                   = '(Delete all old data before Import)';
$_['help_import']                           = 'Spreadsheet can have categories, products, attribute definitions, option definitions, or filter definitions. ';
$_['help_import_old']                       = 'Spreadsheet can have categories, products, attribute definitions, or option definitions. ';
$_['help_format']                           = 'Do an Export first to see the exact format of the worksheets!';
*/


// Tabs
$_['tab_sync']                               = 'Sync';
$_['tab_history']                            = 'History';
$_['tab_settings']                           = 'Settings';

// Button labels
$_['button_sync']                     	 	 = 'Sync';
$_['button_sync_selected']                   = 'Sync Selection';
$_['button_sync_all']                     	 = 'Sync All';
$_['button_sync_new_changes']                = 'Sync New and Changes';
$_['button_history']                         = 'History';
$_['button_settings']                        = 'Update Settings';

$_['entry_api_url']                         = 'API URL';
$_['entry_api_partner_key']                 = 'API Partner Key';
$_['button_save']                			= 'Save';
$_['button_next']                			= 'Next';








//Product
// Heading
//$_['heading_title']          = 'Products';

// Text
//$_['text_success']           = 'Success: You have modified products!';
$_['text_list']              = 'Product List';
$_['text_add']               = 'Add Product';
$_['text_edit']              = 'Edit Product';
$_['text_plus']              = '+';
$_['text_minus']             = '-';
$_['text_default']           = 'Default';
$_['text_option']            = 'Option';
$_['text_option_value']      = 'Option Value';
$_['text_percent']           = 'Percentage';
$_['text_amount']            = 'Fixed Amount';

// Column
$_['column_name']            = 'Product Name';
$_['column_model']           = 'Model';
$_['column_image']           = 'Image';
$_['column_price']           = 'Price';
$_['column_quantity']        = 'Quantity';
$_['column_status']          = 'Status';
$_['column_action']          = 'Action';

// Entry
$_['entry_name']             = 'Product Name';
$_['entry_description']      = 'Description';
$_['entry_meta_title'] 	     = 'Meta Tag Title';
$_['entry_meta_keyword'] 	 = 'Meta Tag Keywords';
$_['entry_meta_description'] = 'Meta Tag Description';
$_['entry_keyword']          = 'SEO URL';
$_['entry_model']            = 'Model';
$_['entry_sku']              = 'SKU';
$_['entry_upc']              = 'UPC';
$_['entry_ean']              = 'EAN';
$_['entry_jan']              = 'JAN';
$_['entry_isbn']             = 'ISBN';
$_['entry_mpn']              = 'MPN';
$_['entry_location']         = 'Location';
$_['entry_shipping']         = 'Requires Shipping';
$_['entry_manufacturer']     = 'Manufacturer';
$_['entry_store']            = 'Stores';
$_['entry_date_available']   = 'Date Available';
$_['entry_quantity']         = 'Quantity';
$_['entry_minimum']          = 'Minimum Quantity';
$_['entry_stock_status']     = 'Out Of Stock Status';
$_['entry_price']            = 'Price';
$_['entry_tax_class']        = 'Tax Class';
$_['entry_points']           = 'Points';
$_['entry_option_points']    = 'Points';
$_['entry_subtract']         = 'Subtract Stock';
$_['entry_weight_class']     = 'Weight Class';
$_['entry_weight']           = 'Weight';
$_['entry_dimension']        = 'Dimensions (L x W x H)';
$_['entry_length_class']     = 'Length Class';
$_['entry_length']           = 'Length';
$_['entry_width']            = 'Width';
$_['entry_height']           = 'Height';
$_['entry_image']            = 'Image';
$_['entry_additional_image'] = 'Additional Images';
$_['entry_customer_group']   = 'Customer Group';
$_['entry_date_start']       = 'Date Start';
$_['entry_date_end']         = 'Date End';
$_['entry_priority']         = 'Priority';
$_['entry_attribute']        = 'Attribute';
$_['entry_attribute_group']  = 'Attribute Group';
$_['entry_text']             = 'Text';
$_['entry_option']           = 'Option';
$_['entry_option_value']     = 'Option Value';
$_['entry_required']         = 'Required';
$_['entry_status']           = 'Status';
$_['entry_sort_order']       = 'Sort Order';
$_['entry_category']         = 'Categories';
$_['entry_filter']           = 'Filters';
$_['entry_download']         = 'Downloads';
$_['entry_related']          = 'Related Products';
$_['entry_tag']          	 = 'Product Tags';
$_['entry_reward']           = 'Reward Points';
$_['entry_layout']           = 'Layout Override';
$_['entry_recurring']        = 'Recurring Profile';

// Help
$_['help_keyword']           = 'Do not use spaces, instead replace spaces with - and make sure the SEO URL is globally unique.';
$_['help_sku']               = 'Stock Keeping Unit';
$_['help_upc']               = 'Universal Product Code';
$_['help_ean']               = 'European Article Number';
$_['help_jan']               = 'Japanese Article Number';
$_['help_isbn']              = 'International Standard Book Number';
$_['help_mpn']               = 'Manufacturer Part Number';
$_['help_manufacturer']      = '(Autocomplete)';
$_['help_minimum']           = 'Force a minimum ordered amount';
$_['help_stock_status']      = 'Status shown when a product is out of stock';
$_['help_points']            = 'Number of points needed to buy this item. If you don\'t want this product to be purchased with points leave as 0.';
$_['help_category']          = '(Autocomplete)';
$_['help_filter']            = '(Autocomplete)';
$_['help_download']          = '(Autocomplete)';
$_['help_related']           = '(Autocomplete)';
$_['help_tag']               = 'Comma separated';

// Error
$_['error_warning']          = 'Warning: Please check the form carefully for errors!';
$_['error_permission']       = 'Warning: You do not have permission to modify products!';
$_['error_name']             = 'Product Name must be greater than 3 and less than 255 characters!';
$_['error_meta_title']       = 'Meta Title must be greater than 3 and less than 255 characters!';
$_['error_model']            = 'Product Model must be greater than 1 and less than 64 characters!';
$_['error_keyword']          = 'SEO URL already in use!';
?>