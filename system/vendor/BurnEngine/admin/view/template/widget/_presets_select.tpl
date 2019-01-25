<div class="s_row_1">
  <div class="s_select inline s_mr_10">
    <textarea style="display: none;" class="tbPresetBoxColorKeys"><?php echo json_encode($preset_box_color_keys); ?></textarea>
    <textarea style="display: none;" class="tbPresetBoxFontKeys"><?php echo json_encode($preset_box_font_keys); ?></textarea>
    <input type="hidden" name="widget_data[preset_id]" value="<?php echo $widget_preset_id; ?>" />
    <select name="preset_id">
      <option value="0">- Select -</option>
      <?php foreach ($preset_options as $option_key => $option_value): ?>
      <option value="<?php echo $option_key; ?>" <?php if ($selected_preset_id == $option_key): ?> selected="selected" <?php endif ?>><?php echo $option_value; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <a class="s_button s_white s_h_28 s_mr_5 tbLoadPreset">Load</a>
  <a class="s_button s_white s_h_28 tbApplyPreset">Apply</a>
</div>