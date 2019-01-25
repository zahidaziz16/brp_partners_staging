<div class="tb_tabs tb_fly_tabs tbProductListingSettings">
  <div class="tb_tabs_nav clearfix">
    <ul class="clearfix">
      <li><a href="#store_settings_products_<?php echo TB_Utils::slugify($input_property); ?>_grid">Grid</a></li>
      <li><a href="#store_settings_products_<?php echo TB_Utils::slugify($input_property); ?>_list">List</a></li>
      <?php if (in_array('compact', $list_types)): ?>
      <li><a href="#store_settings_products_<?php echo TB_Utils::slugify($input_property); ?>_compact">Compact</a></li>
      <?php endif; ?>
    </ul>
  </div>

  <?php foreach ($list_types as $list_type): ?>
  <?php $ptype = $products[$list_type]; ?>
  <div id="store_settings_products_<?php echo TB_Utils::slugify($input_property); ?>_<?php echo $list_type; ?>" class="tbProductsSettings<?php echo ucfirst($list_type); ?>">
    <?php if ($list_type == 'grid'): ?>
    <div class="tb_wrap tb_gut_30 s_mb_20">
      <div class="s_row_2 tb_col tb_1_5 tb_live_none">
        <label><strong>Layout</strong></label>
      </div>
      <div class="s_row_2 tb_col tb_4_5 tb_live_row_1 tb_live_1_1">
        <table class="tb_product_elements tb_restrictions_table s_table_1" cellspacing="0">
          <thead>
            <tr class="s_open">
              <th width="115">
                <label><strong>Container width</strong></label>
              </th>
              <th class="align_left" width="115">
                <label><strong>Items per row</strong></label>
              </th>
              <th class="align_left" width="115">
                <label><strong>Spacing</strong></label>
              </th>
              <th class="align_left">
              </th>
            </tr>
          </thead>
          <tbody class="tbItemsRestrictionsWrapper">
            <?php $i = 0; ?>
            <?php foreach ($products['grid']['restrictions'] as $row): ?>
            <tr class="s_open s_nosep tbItemsRestrictionRow">
              <td>
                <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][restrictions][<?php echo $i; ?>][max_width]" value="<?php echo $row['max_width']; ?>" min="100"step="10" size="7" />
                <span class="s_metric">px</span>
              </td>
              <td class="align_left">
                <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][restrictions][<?php echo $i; ?>][items_per_row]" value="<?php echo $row['items_per_row']; ?>" min="1" max="12" size="5" />
              </td>
              <td class="align_left">
                <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][restrictions][<?php echo $i; ?>][items_spacing]" value="<?php echo $row['items_spacing']; ?>" step="10" min="0" max="50" size="5" />
                <span class="s_metric">px</span>
              </td>
              <td class="align_right">
                <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveItemsRestrictionRow" href="javascript:;"></a>
              </td>
            </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
        <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 s_mt_20 tbAddItemsRestrictionRow" href="javascript:;">Add rule</a>
      </div>
    </div>

    <span class="s_mb_15 clear border_eee"></span>
    <?php endif; ?>

    <?php if ($list_type == 'grid'): ?>
    <input type="hidden" name="<?php echo $input_property; ?>[products][list][restrictions][0][max_width]" value="<?php echo $products['list']['restrictions'][0]['max_width']; ?>" />
    <input type="hidden" name="<?php echo $input_property; ?>[products][list][restrictions][0][items_per_row]" value="<?php echo $products['list']['restrictions'][0]['items_per_row']; ?>" />
    <input type="hidden" name="<?php echo $input_property; ?>[products][list][restrictions][0][items_spacing]" value="<?php echo $products['list']['restrictions'][0]['items_spacing']; ?>" />
    <?php endif; ?>

    <div class="tb_wrap tb_gut_30 s_mb_20 tbProductsItemBoxWrap">
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label><strong>Item box</strong></label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Layout</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][listing_layout]">
              <option value="plain"<?php    if ($ptype['listing_layout'] == 'default'):    ?> selected="selected"<?php endif; ?>>Default</option>
              <?php if (isset($product_listing_layouts[$list_type])): ?>
              <?php foreach ($product_listing_layouts[$list_type] as $listing_layout): ?>
              <option value="<?php echo $listing_layout['template']; ?>"<?php if ($ptype['listing_layout'] == $listing_layout['template']): ?> selected="selected"<?php endif; ?>><?php echo $listing_layout['name']; ?></option>
              <?php endforeach; ?>
              <?php endif; ?>
            </select>
            <?php if (isset($product_listing_layouts[$list_type])): ?>
            <?php foreach ($product_listing_layouts[$list_type] as $listing_layout): ?>
            <textarea class="product_listing_layout_<?php echo $list_type . '_' . $listing_layout['template']; ?>" style="display: none"><?php echo json_encode($listing_layout['disabled']); ?></textarea>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductsStyleWrap">
        <label>Style</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][products_style]">
              <option value="plain"<?php    if ($ptype['products_style'] == 'plain'):    ?> selected="selected"<?php endif; ?>>Plain</option>
              <option value="bordered"<?php if ($ptype['products_style'] == 'bordered'): ?> selected="selected"<?php endif; ?>>Bordered</option>
              <option value="separate"<?php if ($ptype['products_style'] == 'separate'): ?> selected="selected"<?php endif; ?>>Separated</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductsInnerPaddingWrap">
        <label>Inner padding</label>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][product_inner_padding]" value="<?php echo $ptype['product_inner_padding']; ?>" size="5" min="0" max="50" step="5" />
        <span class="s_metric">px</span>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductsExcludeThumbnailWrap">
        <label>Exclude thumbnail</label>
        <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][exclude_thumbnail]" value="0" />
        <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][exclude_thumbnail]" value="1"<?php if ($ptype['exclude_thumbnail'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
      </div>
    </div>

    <span class="s_mb_15 clear border_eee"></span>

    <div class="tb_wrap tb_gut_30 s_mb_20 tbProductsHoverEffectsWrap">
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label><strong>Hover effects</strong></label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductsItemHoverWrap">
        <label>Item hover</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][elements_hover_action]">
              <option value="none"<?php if ($ptype['elements_hover_action'] == 'none'): ?> selected="selected"<?php endif; ?>>No action</option>
              <option value="append"<?php if ($ptype['elements_hover_action'] == 'append'): ?> selected="selected"<?php endif; ?>>Append to visible</option>
              <option value="overlay"<?php if ($ptype['elements_hover_action'] == 'overlay'): ?> selected="selected"<?php endif; ?>>Overlay visible</option>
              <option value="flip"<?php if ($ptype['elements_hover_action'] == 'flip'): ?> selected="selected"<?php endif; ?>>Flip visible</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductsThumbHoverWrap">
        <label>Thumb hover</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][thumbs_hover_action]">
              <?php /* <option value="zoom"<?php if ($ptype['thumbs_hover_action'] == 'zoom'): ?> selected="selected"<?php endif; ?>>Zoom image</option> */ ?>
              <option value="overlay"<?php if ($ptype['thumbs_hover_action'] == 'overlay'): ?> selected="selected"<?php endif; ?>>Change (overlay) image</option>
              <option value="flip"<?php if ($ptype['thumbs_hover_action'] == 'flip'): ?> selected="selected"<?php endif; ?>>Change (flip) image</option>
              <option value="none"<?php if ($ptype['thumbs_hover_action'] == 'none'): ?> selected="selected"<?php endif; ?>>No action</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductsFadeOnHoverWrap">
        <label>Fade non hovered</label>
        <span class="clear"></span>
        <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][hover_fade]" value="0" />
        <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][hover_fade]" value="1"<?php if ($ptype['hover_fade'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
      </div>
    </div>

    <span class="s_mb_15 clear border_eee"></span>

    <div class="tbAdvancedOptions tb_wrap tb_gut_30 s_hidden">
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 s_hidden">
        <label><strong>Price</strong></label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Price design</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][price_design]">
              <option value="plain"<?php if ($ptype['price_design'] == 'plain'): ?> selected="selected"<?php endif; ?>>Plain Text</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Price size</label>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][price_size]" value="<?php echo $ptype['price_size']; ?>" size="5" />
      </div>
    </div>

    <span class="s_mb_15 clear border_eee"></span>

    <div class="tbAdvancedOptions tb_wrap tb_gut_30 tbProductsCartButtonWrap">
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label><strong>Cart button</strong></label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductCartButtonOptionsStyle">
        <label>Style</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_button_type]">
              <option value="button"<?php if ($ptype['cart_button_type'] == 'button'): ?> selected="selected"<?php endif; ?>>Button</option>
              <option value="icon_button"<?php if ($ptype['cart_button_type'] == 'icon_button'): ?> selected="selected"<?php endif; ?>>Icon only button</option>
              <option value="box"<?php if ($ptype['cart_button_type'] == 'box'): ?> selected="selected"<?php endif; ?>>Box</option>
              <option value="icon_box"<?php if ($ptype['cart_button_type'] == 'icon_box'): ?> selected="selected"<?php endif; ?>>Icon only box</option>
              <option value="plain"<?php if ($ptype['cart_button_type'] == 'plain'): ?> selected="selected"<?php endif; ?>>Text</option>
              <option value="icon_plain"<?php if ($ptype['cart_button_type'] == 'icon_plain'): ?> selected="selected"<?php endif; ?>>Icon only</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductCartButtonOptionsButtonSize">
        <label>Button/Box Size</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_button_size]">
              <option value="xs"<?php if ($ptype['cart_button_size'] == 'xs'): ?> selected="selected"<?php endif; ?>>Tiny</option>
              <option value="sm"<?php if ($ptype['cart_button_size'] == 'sm'): ?> selected="selected"<?php endif; ?>>Small</option>
              <option value="md"<?php if ($ptype['cart_button_size'] == 'md'): ?> selected="selected"<?php endif; ?>>Default</option>
              <option value="lg"<?php if ($ptype['cart_button_size'] == 'lg'): ?> selected="selected"<?php endif; ?>>Large</option>
              <option value="xl"<?php if ($ptype['cart_button_size'] == 'xl'): ?> selected="selected"<?php endif; ?>>Extra large</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbIconRow">
        <label>Icon</label>
        <div class="tbIcon<?php if (!$ptype['cart_button_icon']): ?> s_icon_holder s_h_26<?php endif; ?>">
          <?php if ($ptype['cart_button_icon']): ?>
          <span class="glyph_symbol <?php echo $ptype['cart_button_icon']; ?>"></span>
          <?php endif; ?>
        </div>
        <?php if (!$ptype['cart_button_icon']): ?>
        <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
        <?php else: ?>
        <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
        <?php endif; ?>
        <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_button_icon]" value="<?php echo $ptype['cart_button_icon']; ?>" />
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Icon size</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_button_icon_size]">
              <option value="10"<?php if ($ptype['cart_button_icon_size'] == '10'): ?> selected="selected"<?php endif; ?>>Small</option>
              <option value="16"<?php if ($ptype['cart_button_icon_size'] == '16'): ?> selected="selected"<?php endif; ?>>Medium</option>
              <option value="24"<?php if ($ptype['cart_button_icon_size'] == '24'): ?> selected="selected"<?php endif; ?>>Large</option>
              <option value="32"<?php if ($ptype['cart_button_icon_size'] == '32'): ?> selected="selected"<?php endif; ?>>Huge</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="tbAdvancedOptions tb_wrap tb_gut_30 s_mt_15 s_mb_20">
      <div class="s_row_2 tb_col tb_1_5 tb_live_none">
        <label>&nbsp;</label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductCartButtonOptionsPosition">
        <label>Position</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_button_position]">
              <option value="default"<?php if ($ptype['cart_button_position'] == 'default'): ?> selected="selected"<?php endif; ?>>Default</option>
              <option value="inline"<?php  if ($ptype['cart_button_position'] == 'inline'):  ?> selected="selected"<?php endif; ?>>Inline</option>
              <option value="br"<?php      if ($ptype['cart_button_position'] == 'br'):      ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom right)</option>
              <option value="b"<?php       if ($ptype['cart_button_position'] == 'b'):       ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom)</option>
              <option value="bl"<?php      if ($ptype['cart_button_position'] == 'bl'):      ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom left)</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Offset adjustment</label>
        <input type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_button_offset]" value="<?php echo $ptype['cart_button_offset']; ?>" size="12" />
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductCartButtonOptionsHover">
        <label>Show on hover</label>
        <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_button_hover]" value="0" />
        <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_button_hover]" value="1"<?php if ($ptype['cart_button_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
      </div>
    </div>

    <span class="s_mb_15 s_mt_20 clear border_eee"></span>

    <div class="tbAdvancedOptions tb_wrap tb_gut_30 tbProductsCompareButtonWrap">
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label><strong>Compare button</strong></label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductCompareButtonOptionsStyle">
        <label>Style</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_button_type]">
              <option value="button"<?php if ($ptype['compare_button_type'] == 'button'): ?> selected="selected"<?php endif; ?>>Button</option>
              <option value="icon_button"<?php if ($ptype['compare_button_type'] == 'icon_button'): ?> selected="selected"<?php endif; ?>>Icon only button</option>
              <option value="box"<?php if ($ptype['compare_button_type'] == 'box'): ?> selected="selected"<?php endif; ?>>Box</option>
              <option value="icon_box"<?php if ($ptype['compare_button_type'] == 'icon_box'): ?> selected="selected"<?php endif; ?>>Icon only box</option>
              <option value="plain"<?php if ($ptype['compare_button_type'] == 'plain'): ?> selected="selected"<?php endif; ?>>Text</option>
              <option value="icon_plain"<?php if ($ptype['compare_button_type'] == 'icon_plain'): ?> selected="selected"<?php endif; ?>>Icon only</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductCompareButtonOptionsButtonSize">
        <label>Button/Box Size</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_button_size]">
              <option value="xs"<?php if ($ptype['compare_button_size'] == 'xs'): ?> selected="selected"<?php endif; ?>>Tiny</option>
              <option value="sm"<?php if ($ptype['compare_button_size'] == 'sm'): ?> selected="selected"<?php endif; ?>>Small</option>
              <option value="md"<?php if ($ptype['compare_button_size'] == 'md'): ?> selected="selected"<?php endif; ?>>Default</option>
              <option value="lg"<?php if ($ptype['compare_button_size'] == 'lg'): ?> selected="selected"<?php endif; ?>>Large</option>
              <option value="xl"<?php if ($ptype['compare_button_size'] == 'xl'): ?> selected="selected"<?php endif; ?>>Extra large</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbIconRow">
        <label>Icon</label>
        <div class="tbIcon<?php if (!$ptype['compare_button_icon']): ?> s_icon_holder s_h_26<?php endif; ?>">
          <?php if ($ptype['compare_button_icon']): ?>
          <span class="glyph_symbol <?php echo $ptype['compare_button_icon']; ?>"></span>
          <?php endif; ?>
        </div>
        <?php if (!$ptype['compare_button_icon']): ?>
        <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
        <?php else: ?>
        <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
        <?php endif; ?>
        <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_button_icon]" value="<?php echo $ptype['compare_button_icon']; ?>" />
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Icon size</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_button_icon_size]">
              <option value="10"<?php if ($ptype['compare_button_icon_size'] == '10'): ?> selected="selected"<?php endif; ?>>Small</option>
              <option value="16"<?php if ($ptype['compare_button_icon_size'] == '16'): ?> selected="selected"<?php endif; ?>>Medium</option>
              <option value="24"<?php if ($ptype['compare_button_icon_size'] == '24'): ?> selected="selected"<?php endif; ?>>Large</option>
              <option value="32"<?php if ($ptype['compare_button_icon_size'] == '32'): ?> selected="selected"<?php endif; ?>>Huge</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="tbAdvancedOptions tb_wrap tb_gut_30 s_mt_15 s_mb_20">
      <div class="s_row_2 tb_col tb_1_5 tb_live_none">
        <label>&nbsp;</label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductCompareButtonOptionsPosition">
        <label>Position</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_button_position]">
              <option value="default"<?php if ($ptype['compare_button_position'] == 'default'): ?> selected="selected"<?php endif; ?>>Default</option>
              <option value="inline"<?php  if ($ptype['compare_button_position'] == 'inline'):  ?> selected="selected"<?php endif; ?>>Inline</option>
              <option value="br"<?php      if ($ptype['compare_button_position'] == 'br'):      ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom right)</option>
              <option value="b"<?php       if ($ptype['compare_button_position'] == 'b'):       ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom)</option>
              <option value="bl"<?php      if ($ptype['compare_button_position'] == 'bl'):      ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom left)</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Offset adjustment</label>
        <input type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_button_offset]" value="<?php echo $ptype['compare_button_offset']; ?>" size="12" />
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductCompareButtonOptionsHover">
        <label>Show on hover</label>
        <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_button_hover]" value="0" />
        <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_button_hover]" value="1"<?php if ($ptype['compare_button_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
      </div>
    </div>

    <span class="s_mb_15 s_mt_20 clear border_eee"></span>

    <div class="tbAdvancedOptions tb_wrap tb_gut_30 tbProductsWishlistButtonWrap">
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label><strong>Wishlist button</strong></label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductWishlistButtonOptionsStyle">
        <label>Style</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_button_type]">
              <option value="button"<?php if ($ptype['wishlist_button_type'] == 'button'): ?> selected="selected"<?php endif; ?>>Button</option>
              <option value="icon_button"<?php if ($ptype['wishlist_button_type'] == 'icon_button'): ?> selected="selected"<?php endif; ?>>Icon only button</option>
              <option value="box"<?php if ($ptype['wishlist_button_type'] == 'box'): ?> selected="selected"<?php endif; ?>>Box</option>
              <option value="icon_box"<?php if ($ptype['wishlist_button_type'] == 'icon_box'): ?> selected="selected"<?php endif; ?>>Icon only box</option>
              <option value="plain"<?php if ($ptype['wishlist_button_type'] == 'plain'): ?> selected="selected"<?php endif; ?>>Text</option>
              <option value="icon_plain"<?php if ($ptype['wishlist_button_type'] == 'icon_plain'): ?> selected="selected"<?php endif; ?>>Icon only</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductWishlistButtonOptionsButtonSize">
        <label>Button/Box Size</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_button_size]">
              <option value="xs"<?php if ($ptype['wishlist_button_size'] == 'xs'): ?> selected="selected"<?php endif; ?>>Tiny</option>
              <option value="sm"<?php if ($ptype['wishlist_button_size'] == 'sm'): ?> selected="selected"<?php endif; ?>>Small</option>
              <option value="md"<?php if ($ptype['wishlist_button_size'] == 'md'): ?> selected="selected"<?php endif; ?>>Default</option>
              <option value="lg"<?php if ($ptype['wishlist_button_size'] == 'lg'): ?> selected="selected"<?php endif; ?>>Large</option>
              <option value="xl"<?php if ($ptype['wishlist_button_size'] == 'xl'): ?> selected="selected"<?php endif; ?>>Extra large</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbIconRow">
        <label>Icon</label>
        <div class="tbIcon<?php if (!$ptype['wishlist_button_icon']): ?> s_icon_holder s_h_26<?php endif; ?>">
          <?php if ($ptype['wishlist_button_icon']): ?>
          <span class="glyph_symbol <?php echo $ptype['wishlist_button_icon']; ?>"></span>
          <?php endif; ?>
        </div>
        <?php if (!$ptype['wishlist_button_icon']): ?>
        <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
        <?php else: ?>
        <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
        <?php endif; ?>
        <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_button_icon]" value="<?php echo $ptype['wishlist_button_icon']; ?>" />
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Icon size</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_button_icon_size]">
              <option value="10"<?php if ($ptype['wishlist_button_icon_size'] == '10'): ?> selected="selected"<?php endif; ?>>Small</option>
              <option value="16"<?php if ($ptype['wishlist_button_icon_size'] == '16'): ?> selected="selected"<?php endif; ?>>Medium</option>
              <option value="24"<?php if ($ptype['wishlist_button_icon_size'] == '24'): ?> selected="selected"<?php endif; ?>>Large</option>
              <option value="32"<?php if ($ptype['wishlist_button_icon_size'] == '32'): ?> selected="selected"<?php endif; ?>>Huge</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="tbAdvancedOptions tb_wrap tb_gut_30 s_mt_15 s_mb_20">
      <div class="s_row_2 tb_col tb_1_5 tb_live_none">
        <label>&nbsp;</label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductWishlistButtonOptionsPosition">
        <label>Position</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_button_position]">
              <option value="default"<?php if ($ptype['wishlist_button_position'] == 'default'): ?> selected="selected"<?php endif; ?>>Default</option>
              <option value="inline"<?php  if ($ptype['wishlist_button_position'] == 'inline'):  ?> selected="selected"<?php endif; ?>>Inline</option>
              <option value="br"<?php      if ($ptype['wishlist_button_position'] == 'br'):      ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom right)</option>
              <option value="b"<?php       if ($ptype['wishlist_button_position'] == 'b'):       ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom)</option>
              <option value="bl"<?php      if ($ptype['wishlist_button_position'] == 'bl'):      ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom left)</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Offset adjustment</label>
        <input type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_button_offset]" value="<?php echo $ptype['wishlist_button_offset']; ?>" size="12" />
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductWishlistButtonOptionsHover">
        <label>Show on hover</label>
        <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_button_hover]" value="0" />
        <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_button_hover]" value="1"<?php if ($ptype['wishlist_button_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
      </div>
    </div>

    <span class="s_mb_15 s_mt_20 clear border_eee"></span>

    <div class="tbAdvancedOptions tb_wrap tb_gut_30 tbProductsQuickviewButtonWrap">
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label><strong>Quickview button</strong></label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductQuickviewButtonOptionsStyle">
        <label>Style</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_button_type]">
              <option value="button"<?php if ($ptype['quickview_button_type'] == 'button'): ?> selected="selected"<?php endif; ?>>Button</option>
              <option value="icon_button"<?php if ($ptype['quickview_button_type'] == 'icon_button'): ?> selected="selected"<?php endif; ?>>Icon only button</option>
              <option value="box"<?php if ($ptype['quickview_button_type'] == 'box'): ?> selected="selected"<?php endif; ?>>Box</option>
              <option value="icon_box"<?php if ($ptype['quickview_button_type'] == 'icon_box'): ?> selected="selected"<?php endif; ?>>Icon only box</option>
              <option value="plain"<?php if ($ptype['quickview_button_type'] == 'plain'): ?> selected="selected"<?php endif; ?>>Text</option>
              <option value="icon_plain"<?php if ($ptype['quickview_button_type'] == 'icon_plain'): ?> selected="selected"<?php endif; ?>>Icon only</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductQuickviewButtonOptionsButtonSize">
        <label>Button/Box Size</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_button_size]">
              <option value="xs"<?php if ($ptype['quickview_button_size'] == 'xs'): ?> selected="selected"<?php endif; ?>>Tiny</option>
              <option value="sm"<?php if ($ptype['quickview_button_size'] == 'sm'): ?> selected="selected"<?php endif; ?>>Small</option>
              <option value="md"<?php if ($ptype['quickview_button_size'] == 'md'): ?> selected="selected"<?php endif; ?>>Default</option>
              <option value="lg"<?php if ($ptype['quickview_button_size'] == 'lg'): ?> selected="selected"<?php endif; ?>>Large</option>
              <option value="xl"<?php if ($ptype['quickview_button_size'] == 'xl'): ?> selected="selected"<?php endif; ?>>Extra large</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbIconRow">
        <label>Icon</label>
        <div class="tbIcon<?php if (!$ptype['quickview_button_icon']): ?> s_icon_holder s_h_26<?php endif; ?>">
          <?php if ($ptype['quickview_button_icon']): ?>
          <span class="glyph_symbol <?php echo $ptype['quickview_button_icon']; ?>"></span>
          <?php endif; ?>
        </div>
        <?php if (!$ptype['quickview_button_icon']): ?>
        <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
        <?php else: ?>
        <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
        <?php endif; ?>
        <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_button_icon]" value="<?php echo $ptype['quickview_button_icon']; ?>" />
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Icon size</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_button_icon_size]">
              <option value="10"<?php if ($ptype['quickview_button_icon_size'] == '10'): ?> selected="selected"<?php endif; ?>>Small</option>
              <option value="16"<?php if ($ptype['quickview_button_icon_size'] == '16'): ?> selected="selected"<?php endif; ?>>Medium</option>
              <option value="24"<?php if ($ptype['quickview_button_icon_size'] == '24'): ?> selected="selected"<?php endif; ?>>Large</option>
              <option value="32"<?php if ($ptype['quickview_button_icon_size'] == '32'): ?> selected="selected"<?php endif; ?>>Huge</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="tbAdvancedOptions tb_wrap tb_gut_30 s_mt_15 s_mb_20">
      <div class="s_row_2 tb_col tb_1_5 tb_live_none">
        <label>&nbsp;</label>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductQuickviewButtonOptionsPosition">
        <label>Position</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_button_position]">
              <option value="default"<?php if ($ptype['quickview_button_position'] == 'default'): ?> selected="selected"<?php endif; ?>>Default</option>
              <option value="inline"<?php  if ($ptype['quickview_button_position'] == 'inline'):  ?> selected="selected"<?php endif; ?>>Inline</option>
              <option value="br"<?php      if ($ptype['quickview_button_position'] == 'br'):      ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom right)</option>
              <option value="b"<?php       if ($ptype['quickview_button_position'] == 'b'):       ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom)</option>
              <option value="bl"<?php      if ($ptype['quickview_button_position'] == 'bl'):      ?> selected="selected"<?php endif; ?>>Overlap thumbnail (bottom left)</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label>Offset adjustment</label>
        <input type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_button_offset]" value="<?php echo $ptype['quickview_button_offset']; ?>" size="12" />
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbProductQuickviewButtonOptionsHover">
        <label>Show on hover</label>
        <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_button_hover]" value="0" />
        <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_button_hover]" value="1"<?php if ($ptype['quickview_button_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
      </div>
    </div>

    <span class="s_mb_15 s_mt_20 clear border_eee"></span>

    <div class="tb_wrap tb_gut_30 tb_tabs">
      <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
        <label class="s_mb_10"><strong>Element visibility</strong></label>
      </div>
      <div class="s_row_2 tb_col tb_4_5 tb_live_1_1">
        <table class="tb_product_elements s_table_1" cellspacing="0">
          <thead>
          <tr class="s_open">
            <th>
              <label><strong>Element</strong></label>
            </th>
            <th width="60" class="align_center">
              <label><strong>Default</strong></label>
            </th>
            <?php if ($list_type == 'grid'): ?>
            <th width="60" class="align_center">
              <label><strong>Hover</strong></label>
            </th>
            <?php endif; ?>
          </tr>
          </thead>
          <tbody>
          <tr class="s_open s_nosep">
            <td>
              <label class="inline">Thumbnail</label>
              <div class="right" style="margin-top: -2px; margin-bottom: -2px;">
                <span class="s_metric" style="line-height: 24px !important;">Size</span>
                &nbsp;
                <input class="inline" type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][image_width]" value="<?php echo $ptype['image_width']; ?>" size="2" style="height: 24px;" />
                <span class="s_input_separator" style="line-height: 24px !important;">&nbsp;x&nbsp;</span>
                <input class="inline" type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][image_height]" value="<?php echo $ptype['image_height']; ?>" size="2" style="height: 24px;" /><span class="s_metric" style="line-height: 24px !important;">px</span>
              </div>
            </td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][thumb_default]" value="0"<?php if($ptype['thumb_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][thumb_default]" value="1"<?php if($ptype['thumb_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][thumb_hover]" value="0"<?php if($ptype['thumb_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][thumb_hover]" value="1"<?php if($ptype['thumb_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep">
            <td><label>Title</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][title_default]" value="0"<?php if($ptype['title_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][title_default]" value="1"<?php if($ptype['title_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][title_hover]" value="0"<?php if($ptype['title_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][title_hover]" value="1"<?php if($ptype['title_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep">
            <td>
              <label class="inline">Description</label>
              <div class="right" style="margin-top: -2px; margin-bottom: -2px;">
                <span class="s_metric" style="line-height: 24px !important;">Limit</span>
                &nbsp;
                <input class="inline" type="text" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][description_limit]" value="<?php echo $ptype['description_limit']; ?>" size="2" style="height: 24px;" /><span class="s_metric" style="line-height: 24px !important;">char</span>
              </div>
            </td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][description_default]" value="0"<?php if($ptype['description_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][description_default]" value="1"<?php if($ptype['description_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][description_hover]" value="0"<?php if($ptype['description_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][description_hover]" value="1"<?php if($ptype['description_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep">
            <td><label>Price</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][price_default]" value="0"<?php if($ptype['price_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][price_default]" value="1"<?php if($ptype['price_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][price_hover]" value="0"<?php if($ptype['price_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][price_hover]" value="1"<?php if($ptype['price_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep">
            <td><label>Price without tax</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][tax_default]" value="0"<?php if($ptype['tax_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][tax_default]" value="1"<?php if($ptype['tax_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
              <td>
                <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][tax_hover]" value="0"<?php if($ptype['tax_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
                <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][tax_hover]" value="1"<?php if($ptype['tax_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
              </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep">
            <td><label>Special price countdown</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][counter_default]" value="0"<?php if($ptype['counter_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][counter_default]" value="1"<?php if($ptype['counter_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][counter_hover]" value="0"<?php if($ptype['counter_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][counter_hover]" value="1"<?php if($ptype['counter_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep tbProductElementVisibilityCartButton">
            <td><label>Add to cart button</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_default]" value="0"<?php if($ptype['cart_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_default]" value="1"<?php if($ptype['cart_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_hover]" value="0"<?php if($ptype['cart_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][cart_hover]" value="1"<?php if($ptype['cart_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep tbProductElementVisibilityCompareButton">
            <td><label>Compare button</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_default]" value="0"<?php if($ptype['compare_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_default]" value="1"<?php if($ptype['compare_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_hover]" value="0"<?php if($ptype['compare_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][compare_hover]" value="1"<?php if($ptype['compare_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep tbProductElementVisibilityWishlistButton">
            <td><label>Wishlist button</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_default]" value="0"<?php if($ptype['wishlist_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_default]" value="1"<?php if($ptype['wishlist_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_hover]" value="0"<?php if($ptype['wishlist_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][wishlist_hover]" value="1"<?php if($ptype['wishlist_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep tbProductElementVisibilityQuickviewButton">
            <td><label>Quickview button</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_default]" value="0"<?php if($ptype['quickview_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_default]" value="1"<?php if($ptype['quickview_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_hover]" value="0"<?php if($ptype['quickview_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][quickview_hover]" value="1"<?php if($ptype['quickview_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep">
            <td><label>Rating</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][rating_default]" value="0"<?php if($ptype['rating_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][rating_default]" value="1"<?php if($ptype['rating_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][rating_hover]" value="0"<?php if($ptype['rating_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][rating_hover]" value="1"<?php if($ptype['rating_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep">
            <td><label>Sale label</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][label_sale_default]" value="0"<?php if($ptype['label_sale_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][label_sale_default]" value="1"<?php if($ptype['label_sale_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][label_sale_hover]" value="0"<?php if($ptype['label_sale_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][label_sale_hover]" value="1"<?php if($ptype['label_sale_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep">
            <td><label>New label</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][label_new_default]" value="0"<?php if($ptype['label_new_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][label_new_default]" value="1"<?php if($ptype['label_new_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][label_new_hover]" value="0"<?php if($ptype['label_new_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][label_new_hover]" value="1"<?php if($ptype['label_new_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          <tr class="s_open s_nosep">
            <td><label>Stock Status</label></td>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][stock_default]" value="0"<?php if($ptype['stock_default'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][stock_default]" value="1"<?php if($ptype['stock_default'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php if ($list_type == 'grid'): ?>
            <td>
              <input type="hidden" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][stock_hover]" value="0"<?php if($ptype['stock_hover'] == '0'): ?> checked="checked"<?php endif; ?> />
              <label class="s_checkbox no_label"><input type="checkbox" name="<?php echo $input_property; ?>[products][<?php echo $list_type; ?>][stock_hover]" value="1"<?php if($ptype['stock_hover'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span></label>
            </td>
            <?php endif; ?>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

</div>

