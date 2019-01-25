<?php $product = $theme_settings['product']; ?>
<h2>Product settings</h2>

<fieldset id="store_product_option_styles">
  <h3 class="s_mb_15">Options styles</h3>
  <table class="s_table_1 tb_product_elements" cellpadding="0" cellspacing="0" border="0">
    <thead>
      <tr class="s_open s_nosep">
        <th width="150">Option</th>
        <th class="align_left">Style</th>
      </tr>
    </thead>
    <tbody class="tbProductDesignList">
      <?php foreach ($product_options as $option_id => $option): ?>
      <tr class="s_open s_nosep">
        <td class="align_left"><?php echo $option['name']; ?></td>
        <td>
          <?php foreach ($product_option_styles as $style_id => $style_name): ?>
          <label class="s_radio left s_pt_0 s_pb_0">
            <input type="radio" name="product[designs][option][<?php echo $option_id; ?>][style_id]" value="<?php echo $style_id; ?>"<?php if ($product['designs']['option'][$option_id]['style_id'] == $style_id): ?> checked="checked"<?php endif; ?> />
            <span><?php echo $style_name; ?></span>
          </label>
          <?php endforeach; ?>
          <?php if ($option['type'] == 'image' || $option['has_images']): ?>
          <div class="left s_ml_20" style="margin-top: -2px; margin-bottom: -2px;">
            <input class="inline" type="text" name="product[designs][option][<?php echo $option_id; ?>][image_width]" value="<?php echo $product['designs']['option'][$option_id]['image_width']; ?>" size="3" style="height: 24px;" />
            <span class="s_input_separator" style="line-height: 24px !important;">&nbsp;x&nbsp;</span>
            <input class="inline" type="text" name="product[designs][option][<?php echo $option_id; ?>][image_height]" value="<?php echo $product['designs']['option'][$option_id]['image_height']; ?>" size="3" style="height: 24px;" />
            <span class="s_metric" style="line-height: 24px !important;">px</span>
          </div>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</fieldset>


