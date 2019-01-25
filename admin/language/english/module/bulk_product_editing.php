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

$version = 'v230.1';

// Heading
$_['heading_title']				= 'Bulk Product Editing';

// Buttons
$_['button_save_exit']			= 'Save & Exit';
$_['button_save_keep_editing']	= 'Save & Keep Editing';

// Text
$_['text_help']					= 'Note: Changes cannot be undone, so make sure to double-check your edits before applying them.';

$_['text_select_which']			= '--- Select Which Products to Edit ---';
$_['text_by_category']			= 'Choose By Category';
$_['text_by_manufacturer']		= 'Choose By Manufacturer';
$_['text_by_product']			= 'Choose By Product';
$_['text_select_product']		= '--- Select Product Subset ---';
$_['text_all_products']			= 'All Products';
$_['text_manufacturers']		= 'Manufacturers';
$_['text_categories']			= 'Categories';
$_['text_DISABLED']				= 'DISABLED';
$_['text_products_that_will']	= 'Products that will be edited:';

$_['text_edit_general_data']	= 'Edit General Data';
$_['text_general_data_help']	= 'Leave blank any field you do NOT want to edit. Fields marked with <span style="color: #00F; font-weight: bold">*</span> can be entered as flat values, additions, subtractions, or percentages. For example:<ul><li>To set a value that is 5 more than the current value, enter: <strong>+5.00</strong></li><li>To set a value that is 10 less than the current value, enter: <strong>-10.00</strong></li><li>To set a value that is one-third of the current value, enter: <strong>33.3%</strong></li></ul>';
$_['text_round_percentage']		= 'Round Percentage Calculations to the Nearest Whole Value';
$_['text_no_change']			= '--- No Change ---';
$_['text_browse']				= 'Browse';
$_['text_clear']				= 'Clear';
$_['text_times_viewed']			= 'Times Viewed:';

$_['text_edit_product_links']	= 'Edit Product Links';
$_['text_add_category']			= 'Add to Categories:';
$_['text_remove_category']		= 'Remove From Categories:';
$_['text_change_manufacturer']	= 'Change Manufacturer:';
$_['text_add_store']			= 'Add to Stores:';
$_['text_remove_store']			= 'Remove From Stores:';
$_['text_add_related']			= 'Add Related Products:<br /><br /><span class="help">If "Only Relate One-Way" is checked, then products chosen for relating will only be associated with products chosen for editing, not the other way around.<br /><br />For example, if you select Product A above and Product B here, then B will appear on A\'s product page, but A will not appear on B\'s product page.</span>';
$_['text_relate_these']			= 'Relate these products:';
$_['text_only_relate_oneway']	= 'Only Relate One-Way';
$_['text_remove_related']		= 'Remove All Current Related Products';

$_['text_edit_discounts']		= 'Edit Discounts';
$_['text_price_help']			= 'Enter prices as flat values, subtractions, or percentages. For example:<ul><li>To set a discounted price of $25, enter: <strong>25.00</strong></li><li>To set a discounted price that is $10 off the current price, enter: <strong>-10.00</strong></li><li>To give a one-third discount off the current price, enter: <strong>33.3%</strong></li></ul>';
$_['text_remove_discounts']		= 'Remove All Current Discounts';
$_['text_price_discount']		= 'Price / Discount Percentage:';

$_['text_edit_specials']		= 'Edit Specials';
$_['text_remove_specials']		= 'Remove All Current Specials';

$_['button_add_discount']		= 'Add Discount';
$_['button_add_special']		= 'Add Special';

$_['text_error']				= 'No products are selected for editing!';

// Copyright
$_['copyright']					= '<div style="text-align: center" class="help">' . $_['heading_title'] . ' ' . $version . ' &copy; <a target="_blank" href="http://www.getclearthinking.com">Clear Thinking, LLC</a></div>';

// Standard Text
$_['standard_module']			= 'Modules';
$_['standard_shipping']			= 'Shipping';
$_['standard_payment']			= 'Payments';
$_['standard_total']			= 'Order Totals';
$_['standard_feed']				= 'Product Feeds';
$_['standard_success']			= 'Success: You have modified the selected products!';
$_['standard_error']			= 'Warning: You do not have permission to use ' . $_['heading_title'] . '!';
?>