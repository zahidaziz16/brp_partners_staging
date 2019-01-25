<?php if (!$font['built-in']): ?>
<div class="s_actions">
  <a class="tbRemoveItem s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;"></a>
</div>
<?php endif; ?>

<?php
$options = (int) $font['has_size'] + (int) $font['has_effects'] + (int) $font['has_spacing'] * 2;
if ($font['built-in'] && $options > 1 || !$font['built-in']) {
  $font_option_col = 'tb_1_5';
  $option_col      = 'tb_2_3';
  $font_style_col  = 'tb_1_2';
} else {
  $font_option_col = 'tb_1_6';
  $option_col      = 'tb_1_1';
  $font_style_col  = 'tb_1_3';
}
?>

<div class="tb_wrap tb_gut_30 tbFontItem">
  <div class="tb_col tb_1_3 s_row_2 s_full tbFontSelectors"<?php if ($font['built-in']) echo ' style="display: none;"'; ?>>
    <label>Selectors</label>
    <textarea name="<?php echo $input_property; ?>[elements]" style="height: 100px;"><?php echo $font['elements']; ?></textarea>
  </div>
  <div class="tb_col <?php echo $option_col; ?> s_row_2">
    <div class="tb_wrap tb_gut_30">
      <div class="tb_col <?php echo $font_style_col; ?> s_row_2 tb_live_row_1 tb_live_1_1 fontfamily">
        <label>Font Family</label>
        <div class="s_full clearfix">
          <input type="hidden" name="<?php echo $input_property; ?>[family]" value="<?php echo $font['family']; ?>" />
          <select class="tb_nostyle fontname<?php if($font['show_built_styles']): ?> tbHasBuiltStyles<?php endif; ?><?php if ($font['can_inherit']): ?> tbCanInherit<?php endif; ?>"></select>
          <input type="hidden" name="<?php echo $input_property; ?>[type]" value="<?php echo $font['type']; ?>" />
          <input type="hidden" name="<?php echo $input_property; ?>[show_built_styles]" value="<?php echo $font['show_built_styles'] ? 1 : 0; ?>" />
        </div>
      </div>
      <div class="tb_col <?php echo $font_style_col; ?> s_row_2 tb_live_row_1 tb_live_1_1 fontstyle tbFontProperty"<?php if (!$font['type'] || $font['family'] == 'inherit' || ($font['type'] == 'built' && !$font['show_built_styles'])): ?> style="display: none"<?php endif; ?>>
        <label>Font Style</label>
        <div class="s_full clearfix">
          <select class="tb_multiselect<?php if ($font['multiple_variants']): ?> multiple_variants<?php endif; ?>" multiple="multiple">
            <optgroup label="Style" class="font_variants">
            <?php if ($font['type'] == 'google'): ?>
              <?php foreach ($font_data['google_font_list'][$font['family']]->variants as $variant): ?>
              <option value="<?php echo $variant['code']; ?>"<?php if (in_array($variant['code'], explode(',', $font['variant']))) echo ' selected="selected"'; ?>><?php echo $variant['code']; ?></option>
              <?php endforeach; ?>
            <?php elseif ($font['type'] == 'built'): ?>
              <?php foreach ($font_data['built_font_variants'] as $variant): ?>
              <option value="<?php echo $variant; ?>"<?php if (in_array($variant, explode(',', $font['variant']))) echo ' selected="selected"'; ?>><?php echo $variant; ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
            </optgroup>
            <optgroup label="Subset" class="font_subsets">
              <?php if ($font['type'] == 'google'): ?>
              <?php foreach ($font_data['google_font_list'][$font['family']]->subsets as $subset): ?>
              <option value="<?php echo $subset; ?>"<?php if (in_array($subset, explode(',', $font['subsets']))) echo ' selected="selected"'; ?>><?php echo $subset; ?></option>
              <?php endforeach; ?>
              <?php endif; ?>
            </optgroup>
          </select>
          <input type="hidden" name="<?php echo $input_property; ?>[variant]" value="<?php echo $font['variant']; ?>" />
          <input type="hidden" name="<?php echo $input_property; ?>[subsets]" value="<?php echo $font['subsets']; ?>" />
        </div>
      </div>
    <?php if($options > 1 || !$font['built-in']): ?>
    </div>
    <div class="tb_wrap tb_gut_30">
    <?php endif; ?>
      <?php if ($font['has_size']): ?>
      <div class="tb_col <?php echo $font_option_col; ?> s_row_2 tb_live_row_1 tb_live_1_1 <?php if ($font['can_inherit']) if ($font['inherit_mask'] & 1): ?>tb_inherit tb_disabled<?php else: ?>tb_no_inherit<?php endif; ?> tbFontProperty">
        <label<?php if ($font['can_inherit']): ?> class="tbInheritLabel"<?php endif; ?> data-binary="1" data-value="<?php echo $font['size']; ?>">Font size</label>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[size]" value="<?php echo $font['size']; ?>" min="8" size="8" />
        <span class="s_metric">px</span>
      </div>
      <?php endif; ?>
      <?php if ($font['has_line_height']): ?>
      <div class="tb_col <?php echo $font_option_col; ?> s_row_2 tb_live_row_1 tb_live_1_1 <?php if ($font['can_inherit']) if ($font['inherit_mask'] & 2): ?>tb_inherit tb_disabled<?php else: ?>tb_no_inherit<?php endif; ?> tbFontProperty tbLineHeight">
        <label<?php if ($font['can_inherit']): ?> class="tbInheritLabel"<?php endif; ?> data-binary="2" data-value="<?php echo $font['line-height']; ?>">Line height</label>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[line-height]" value="<?php echo $font['line-height']; ?>" min="10" size="8" />
        <span class="s_metric">px</span>
      </div>
      <?php endif; ?>
      <?php if ($font['has_spacing']): ?>
      <div class="tb_col <?php echo $font_option_col; ?> s_row_2 tb_live_row_1 tb_live_1_1 <?php if ($font['can_inherit']) if ($font['inherit_mask'] & 4): ?>tb_inherit tb_disabled<?php else: ?>tb_no_inherit<?php endif; ?> tbFontProperty">
        <label<?php if ($font['can_inherit']): ?> class="tbInheritLabel"<?php endif; ?> data-binary="4" data-value="<?php echo $font['letter-spacing']; ?>">Letter spacing</label>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[letter-spacing]" value="<?php echo $font['letter-spacing']; ?>" step="0.1" size="8" />
        <span class="s_metric">px</span>
      </div>
      <div class="tb_col <?php echo $font_option_col; ?> s_row_2 tb_live_row_1 tb_live_1_1 <?php if ($font['can_inherit']) if ($font['inherit_mask'] & 8): ?>tb_inherit tb_disabled<?php else: ?>tb_no_inherit<?php endif; ?> tbFontProperty">
        <label<?php if ($font['can_inherit']): ?> class="tbInheritLabel"<?php endif; ?> data-binary="8" data-value="<?php echo $font['word-spacing']; ?>">Word spacing</label>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[word-spacing]" value="<?php echo $font['word-spacing']; ?>" size="8" />
        <span class="s_metric">px</span>
      </div>
      <?php endif; ?>
      <?php if ($font['has_effects']): ?>
      <div class="tb_col <?php echo $font_option_col; ?> s_row_2 tb_live_row_1 tb_live_1_1 <?php if ($font['can_inherit']) if ($font['inherit_mask'] & 16): ?>tb_inherit tb_disabled<?php else: ?>tb_no_inherit<?php endif; ?> tb_select_row tbFontProperty">
        <label<?php if ($font['can_inherit']): ?> class="tbInheritLabel"<?php endif; ?> data-binary="16" data-value="<?php echo $font['transform']; ?>">Transform</label>
        <div class="s_full clearfix">
          <div class="s_select">
            <select name="<?php echo $input_property; ?>[transform]">
              <option value="none"<?php if ($font['transform'] == 'none') echo ' selected="selected"'; ?>>None</option>
              <option value="uppercase"<?php if ($font['transform'] == 'uppercase') echo ' selected="selected"'; ?>>Uppercase</option>
              <option value="lowercase"<?php if ($font['transform'] == 'lowercase') echo ' selected="selected"'; ?>>Lowercase</option>
              <option value="capitalize"<?php if ($font['transform'] == 'capitalize') echo ' selected="selected"'; ?>>Capitalize</option>
            </select>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <input type="hidden" name="<?php echo $input_property; ?>[multiple_variants]" value="<?php echo (int) $font['multiple_variants']; ?>" />
  <input type="hidden" name="<?php echo $input_property; ?>[can_inherit]" value="<?php echo (int) $font['can_inherit']; ?>" />
  <input type="hidden" name="<?php echo $input_property; ?>[has_line_height]" value="<?php echo (int) $font['has_line_height']; ?>" />
  <?php if (!$font['built-in']): ?>
  <input type="hidden" name="<?php echo $input_property; ?>[section_name]" value="<?php echo $font['section_name']; ?>" />
  <input type="hidden" name="<?php echo $input_property; ?>[built-in]" value="0" />
  <?php endif; ?>
  <?php if ($font['can_inherit']): ?>
  <input type="hidden" name="<?php echo $input_property; ?>[inherit_mask]" value="<?php echo $font['inherit_mask']; ?>" />
  <?php endif; ?>
</div>

<!--
<a class="tb_button_search right" href="javascript:;"><?php echo $text_view; ?></a>
<div class="s_full s_font_preview">
  <a class="s_button_close_small" href="javascript:;">Close</a>
  <p><?php echo $text_font_preview; ?></p>
</div>
-->