<?php $common = $theme_settings['common']; ?>
<h2>Common Settings</h2>

<fieldset id="store_common_cart_options">
  <legend>Cart</legend>
  <a class="tb_block_help s_icon_16 s_question_gray_16" href="">Help</a>
  <div class="s_full tb_wrap">
    <div class="s_row_2 tb_col tb_1_7 tb_live_row_1 tb_live_1_1">
      <label for="common_manufacturers_enabled"><?php echo $text_label_toggle_manufacturers; ?></label>
      <span class="clear"></span>
      <input type="hidden" name="common[manufacturers_enabled]" value="0" />
      <label class="tb_toggle"><input id="common_manufacturers_enabled" type="checkbox" name="common[manufacturers_enabled]" value="1"<?php if($common['manufacturers_enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_7 tb_live_row_1 tb_live_1_1">
      <label for="common_compare_enabled"><?php echo $text_label_toggle_compare; ?></label>
      <span class="clear"></span>
      <input type="hidden" name="common[compare_enabled]" value="0" />
      <label class="tb_toggle"><input id="common_compare_enabled" type="checkbox" name="common[compare_enabled]" value="1"<?php if($common['compare_enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_7 tb_live_row_1 tb_live_1_1">
      <label for="common_wishlist_enabled"><?php echo $text_label_toggle_wishlist; ?></label>
      <span class="clear"></span>
      <input type="hidden" name="common[wishlist_enabled]" value="0" />
      <label class="tb_toggle"><input id="common_wishlist_enabled" type="checkbox" name="common[wishlist_enabled]" value="1"<?php if($common['wishlist_enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_7 tb_live_row_1 tb_live_1_1">
      <label for="common_checkout_enabled"><?php echo $text_label_toggle_checkout; ?></label>
      <span class="clear"></span>
      <input type="hidden" name="common[checkout_enabled]" value="0" />
      <label class="tb_toggle"><input id="common_checkout_enabled" type="checkbox" name="common[checkout_enabled]" value="1"<?php if($common['checkout_enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_7 tb_live_row_1 tb_live_1_1">
      <label for="common_voucher_enabled"><?php echo $text_label_toggle_voucher; ?></label>
      <input type="hidden" name="common[voucher_enabled]" value="0" />
      <label class="tb_toggle"><input id="common_voucher_enabled" type="checkbox" name="common[voucher_enabled]" value="1"<?php if($common['voucher_enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_7 tb_live_row_1 tb_live_1_1">
      <label for="common_affiliate_enabled"><?php echo $text_label_toggle_affiliate; ?></label>
      <input type="hidden" name="common[affiliate_enabled]" value="0" />
      <label class="tb_toggle"><input id="common_affiliate_enabled" type="checkbox" name="common[affiliate_enabled]" value="1"<?php if($common['affiliate_enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_7 tb_live_row_1 tb_live_1_1">
      <label for="store_common_returns_enabled"><?php echo $text_label_toggle_returns; ?></label>
      <input type="hidden" name="common[returns_enabled]" value="0" />
      <label class="tb_toggle"><input id="store_common_returns_enabled" type="checkbox" name="common[returns_enabled]" value="1"<?php if($common['returns_enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
  </div>
</fieldset>

<fieldset id="store_common_product_listing">
  <legend>Product listing</legend>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
      <label>Default list type</label>
      <div class="s_full clearfix">
        <div class="s_select">
          <select name="store[common][product_listing_view_mode]">
            <option value="grid"<?php if ($theme_settings['store']['common']['product_listing_view_mode'] == 'grid'): ?> selected="selected"<?php endif; ?>>Grid</option>
            <option value="list"<?php if ($theme_settings['store']['common']['product_listing_view_mode'] == 'list'): ?> selected="selected"<?php endif; ?>>List</option>
          </select>
        </div>
      </div>
    </div>
    <?php if ($gteOc22): ?>
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
      <label>Default Items Per Page</label>
      <input class="s_spinner" type="text" name="store[common][product_listing_items_per_page]" value="<?php echo $theme_settings['store']['common']['product_listing_items_per_page']; ?>" size="7" min="1" />
      <input type="hidden" name="store[common][product_listing_description_limit]" value="<?php echo $theme_settings['store']['common']['product_listing_description_limit']; ?>" />
    </div>
    <?php endif; ?>
  </div>
</fieldset>

<fieldset id="store_common_product_labels">
  <legend>Labels</legend>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_4 tb_live_row_1 tb_live_1_1">
      <label for="store_common_label_new_days">New Product</label>
      <input id="store_common_label_new_days" class="s_spinner" type="text" name="common[label_new_days]" min="0" size="7" value="<?php echo $common['label_new_days']; ?>" />
      <span class="s_metric s_long">days <br /> or newer</span>
    </div>
  </div>
</fieldset>

<fieldset id="store_common_product_stock_statutes_options">
  <legend>Product stock statuses</legend>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_3 tb_live_1_1">
      <label for="store_common_preorder_status">Pre-Order status</label>
      <div class="s_select">
        <select id="store_common_preorder_status" name="store[common][preorder_stock_status_id]">
          <option value="0"<?php if($theme_settings['store']['common']['preorder_stock_status_id'] == '0') echo ' selected="selected"';?>>- Disabled -</option>
          <?php foreach ($stock_statuses as $stock_status): ?>
          <option value="<?php echo $stock_status['stock_status_id']; ?>"<?php if($theme_settings['store']['common']['preorder_stock_status_id'] == $stock_status['stock_status_id']) echo ' selected="selected"';?>><?php echo $stock_status['name']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="s_row_2 tb_col tb_1_3 tb_live_1_1">
      <label for="store_common_backorder_status">Backorder status</label>
      <div class="s_select">
        <select id="store_common_backorder_status" name="store[common][backorder_stock_status_id]">
          <option value="0"<?php if($theme_settings['store']['common']['backorder_stock_status_id'] == '0') echo ' selected="selected"';?>>- Disabled -</option>
          <?php foreach ($stock_statuses as $stock_status): ?>
          <option value="<?php echo $stock_status['stock_status_id']; ?>"<?php if($theme_settings['store']['common']['backorder_stock_status_id'] == $stock_status['stock_status_id']) echo ' selected="selected"';?>><?php echo $stock_status['name']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="s_row_2 tb_col tb_1_3 tb_live_1_1">
      <label for="store_common_disable_checkout_status">Disable checkout status</label>
      <div class="s_select">
        <select id="store_common_disable_checkout_status" name="store[common][disable_checkout_stock_status_id]">
          <option value="0"<?php if($theme_settings['store']['common']['disable_checkout_stock_status_id'] == '0') echo ' selected="selected"';?>>- Disabled -</option>
          <?php foreach ($stock_statuses as $stock_status): ?>
          <option value="<?php echo $stock_status['stock_status_id']; ?>"<?php if($theme_settings['store']['common']['disable_checkout_stock_status_id'] == $stock_status['stock_status_id']) echo ' selected="selected"';?>><?php echo $stock_status['name']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
</fieldset>

<fieldset id="store_common_product_labels">
  <legend>Miscellaneous</legend>
  <div class="s_full tb_wrap">
    <div class="s_row_2 tb_col tb_1_7 tb_live_row_1 tb_live_1_1">
      <label>Scroll to top</label>
      <span class="clear"></span>
      <input type="hidden" name="common[scroll_to_top]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="common[scroll_to_top]" value="1"<?php if($common['scroll_to_top'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_7 tb_live_row_1 tb_live_1_1">
      <label>Cookie policy</label>
      <span class="clear"></span>
      <input type="hidden" name="common[cookie_policy]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="common[cookie_policy]" value="1"<?php if($common['cookie_policy'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
  </div>
</fieldset>