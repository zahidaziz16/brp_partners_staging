<fieldset>
  <legend>Style</legend>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_4 tb_live_row_1 tb_live_1_1 tbSubcategoriesStyle">
      <label>Style</label>
      <div class="s_full clearfix">
        <div class="s_select">
          <select name="<?php echo $input_property; ?>[subcategories][style]">
            <option value="1"<?php if ($subcategories['style'] == '1'): ?> selected="selected"<?php endif; ?>>Text and image</option>
            <option value="2"<?php if ($subcategories['style'] == '2'): ?> selected="selected"<?php endif; ?>>Image only</option>
            <option value="3"<?php if ($subcategories['style'] == '3'): ?> selected="selected"<?php endif; ?>>Text only</option>
            <option value="4"<?php if ($subcategories['style'] == '4'): ?> selected="selected"<?php endif; ?>><?php echo $tbEngine->getThemeInfo('name'); ?> light</option>
            <option value="5"<?php if ($subcategories['style'] == '5'): ?> selected="selected"<?php endif; ?>><?php echo $tbEngine->getThemeInfo('name'); ?> dark</option>
            <option value="6"<?php if ($subcategories['style'] == '6'): ?> selected="selected"<?php endif; ?>>List</option>
          </select>
        </div>
      </div>
    </div>
    <div class="s_row_2 tb_col tb_1_4 tb_live_row_1 tb_live_1_1 tbSubcategoriesTextAlign">
      <label>Text align</label>
      <div class="s_full clearfix">
        <div class="s_select">
          <select name="<?php echo $input_property; ?>[subcategories][text_align]">
            <option value="left"  <?php if ($subcategories['text_align'] == 'left'):   ?> selected="selected"<?php endif; ?>>Left</option>
            <option value="center"<?php if ($subcategories['text_align'] == 'center'): ?> selected="selected"<?php endif; ?>>Center</option>
            <option value="right" <?php if ($subcategories['text_align'] == 'right'):  ?> selected="selected"<?php endif; ?>>Right</option>
          </select>
        </div>
      </div>
    </div>
    <div class="s_row_2 tb_col tb_1_4 tb_live_row_1 tb_live_1_1 tbSubcategoriesImagePosition">
      <label>Image position</label>
      <div class="s_full clearfix">
        <div class="s_select">
          <select name="<?php echo $input_property; ?>[subcategories][image_position]">
            <option value="top"<?php if ($subcategories['image_position'] == 'top'): ?> selected="selected"<?php endif; ?>>Top</option>
            <option value="right"<?php if ($subcategories['image_position'] == 'right'): ?> selected="selected"<?php endif; ?>>Right</option>
            <option value="left"<?php if ($subcategories['image_position'] == 'left'): ?> selected="selected"<?php endif; ?>>Left</option>
          </select>
        </div>
      </div>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbSubcategoriesImageSize">
      <label>Thumb size</label>
      <input class="inline" type="text" name="<?php echo $input_property; ?>[subcategories][image_width]" value="<?php echo $subcategories['image_width']; ?>" size="2" />
      <span class="s_input_separator">&nbsp;x&nbsp;</span>
      <input class="inline" type="text" name="<?php echo $input_property; ?>[subcategories][image_height]" value="<?php echo $subcategories['image_height']; ?>" size="2" /><span class="s_metric">px</span>
    </div>
  </div>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbSubcategoriesCount">
      <label>Product count</label>
      <span class="clear"></span>
      <input type="hidden" name="<?php echo $input_property; ?>[subcategories][product_count]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[subcategories][product_count]" value="1"<?php if ($subcategories['product_count'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbSubcategoriesSlider">
      <label>Slider (carousel) <span class="s_small color_999">(single line)</span></label>
      <span class="clear"></span>
      <input type="hidden" name="<?php echo $input_property; ?>[subcategories][is_slider]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[subcategories][is_slider]" value="1"<?php if ($subcategories['is_slider'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbSubcategoriesNextLevel">
      <label>Show next level</label>
      <span class="clear"></span>
      <input type="hidden" name="<?php echo $input_property; ?>[subcategories][show_next_level]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[subcategories][show_next_level]" value="1"<?php if ($subcategories['show_next_level'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
    </div>
  </div>
</fieldset>

<fieldset>
  <h3 class="s_mb_15">Layout</h3>

  <table class="tb_product_elements tb_restrictions_table s_table_1" cellspacing="0">
    <thead>
    <tr class="s_open">
      <th width="133">
        <label><strong>Container width</strong></label>
      </th>
      <th class="align_left" width="133">
        <label><strong>Items per row</strong></label>
      </th>
      <th class="align_left" width="133">
        <label><strong>Spacing</strong></label>
      </th>
      <th class="align_left">
      </th>
    </tr>
    </thead>
    <tbody class="tbItemsRestrictionsWrapper">
    <?php $i = 0; ?>
    <?php foreach ($subcategories['restrictions'] as $row): ?>
    <tr class="s_open s_nosep tbItemsRestrictionRow">
      <td>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[subcategories][restrictions][<?php echo $i; ?>][max_width]" value="<?php echo $row['max_width']; ?>" min="100" step="10" size="7" />
        <span class="s_metric">px</span>
      </td>
      <td class="align_left">
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[subcategories][restrictions][<?php echo $i; ?>][items_per_row]" value="<?php echo $row['items_per_row']; ?>" min="1" max="12" size="5" />
      </td>
      <td class="align_left">
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[subcategories][restrictions][<?php echo $i; ?>][items_spacing]" value="<?php echo $row['items_spacing']; ?>" step="10" min="0" max="50" size="5" />
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
</fieldset>
