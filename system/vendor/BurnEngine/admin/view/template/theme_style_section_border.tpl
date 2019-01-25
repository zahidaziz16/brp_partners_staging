<fieldset class="tb_border_style">
  <a class="tbBorderLock s_icon_10 tb_lock s_button s_white s_h_20" href="javascript:;"></a>
  <legend>Border Styles</legend>
  <div class="tb_box_border_row tb_wrap clear s_mb_0">
    <div class="s_row_2 tb_col tb_1_6">
      <label>Width</label>
    </div>
    <div class="s_row_2 tb_col tb_1_5">
      <label>Style</label>
    </div>
    <div class="s_row_2 tb_col tb_1_4">
      <label>Color</label>
    </div>
    <div class="s_row_2 tb_col tb_1_3">
      <label>Opacity</label>
    </div>
  </div>
  <?php foreach (array('top', 'right', 'bottom', 'left') as $side): ?>
  <div class="tb_box_border_row tb_wrap tbBorderStylesRow">
    <span class="s_border_<?php echo $side; ?>" title="<?php echo ucfirst($side); ?>"></span>
    <div class="tb_border_width s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
      <input name="<?php echo $input_property; ?>[border][<?php echo $side; ?>][width]" class="s_spinner" type="text" size="5" min="0" value="<?php echo $border[$side]['width']; ?>" />
      <span class="s_metric">px</span>
    </div>
    <div class="tb_border_style s_row_2 tb_col tb_1_5 s_full tb_live_row_1 tb_live_1_1">
      <div class="s_select">
        <select name="<?php echo $input_property; ?>[border][<?php echo $side; ?>][style]">
          <option value="solid"<?php  if ($border[$side]['style'] == 'solid'):  ?> selected="selected"<?php endif; ?>>Solid</option>
          <option value="dotted"<?php if ($border[$side]['style'] == 'dotted'): ?> selected="selected"<?php endif; ?>>Dotted</option>
          <option value="dashed"<?php if ($border[$side]['style'] == 'dashed'): ?> selected="selected"<?php endif; ?>>Dashed</option>
          <option value="double"<?php if ($border[$side]['style'] == 'double'): ?> selected="selected"<?php endif; ?>>Double</option>
          <option value="groove"<?php if ($border[$side]['style'] == 'groove'): ?> selected="selected"<?php endif; ?>>Groove</option>
          <option value="ridge"<?php  if ($border[$side]['style'] == 'ridge'):  ?> selected="selected"<?php endif; ?>>Ridge</option>
        </select>
      </div>
    </div>
    <div class="tb_border_color s_row_2 tb_col tb_1_4 tb_live_row_1 tb_live_1_1">
      <div class="colorSelector">
        <div style="background-color: <?php echo $border[$side]['color']; ?>"></div>
      </div>
      <input name="<?php echo $input_property; ?>[border][<?php echo $side; ?>][color]" type="text" value="<?php echo $border[$side]['color']; ?>" />
    </div>
    <div class="tb_border_color_opacity s_row_2 tb_col tb_1_3 tb_live_row_1 tb_live_1_1">
      <div class="tb_slider"><div></div></div>
      <input name="<?php echo $input_property; ?>[border][<?php echo $side; ?>][opacity]" class="s_spinner" type="text" min="0" max="100" size="6" value="<?php echo $border[$side]['opacity']; ?>" />
      <span class="s_metric">%</span>
    </div>
  </div>
  <?php endforeach; ?>
</fieldset>

<fieldset class="tb_border_radius">
  <a class="tbRadiusLock s_icon_10 tb_lock s_button s_white s_h_20" href="javascript:;"></a>
  <legend>Border Radius</legend>
  <div class="tb_wrap clear">
    <div class="s_row_1 tb_col tb_1_4 tbBorderRadiusRow">
      <span class="s_icon_24 s_border_radius_top_left" title="Top Left"></span>
      <input name="<?php echo $input_property; ?>[border_radius][top_left]" class="s_spinner" type="text" size="5" min="0" value="<?php echo $border_radius['top_left']; ?>" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_1 tb_col tb_1_4 tbBorderRadiusRow">
      <span class="s_icon_24 s_border_radius_top_right" title="Top Right"></span>
      <input name="<?php echo $input_property; ?>[border_radius][top_right]" class="s_spinner" type="text" size="5" min="0" value="<?php echo $border_radius['top_right']; ?>" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_1 tb_col tb_1_4 tbBorderRadiusRow">
      <span class="s_icon_24 s_border_radius_bottom_right" title="Bottom Right"></span>
      <input name="<?php echo $input_property; ?>[border_radius][bottom_right]" class="s_spinner" type="text" size="5" min="0" value="<?php echo $border_radius['bottom_right']; ?>" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_1 tb_col tb_1_4 tbBorderRadiusRow">
      <span class="s_icon_24 s_border_radius_bottom_left" title="Bottom Left"></span>
      <input name="<?php echo $input_property; ?>[border_radius][bottom_left]" class="s_spinner" type="text" size="5" min="0" value="<?php echo $border_radius['bottom_left']; ?>" />
      <span class="s_metric">px</span>
    </div>

    <span class="clear s_mb_20"></span>

  </div>

</fieldset>

<?php if ($tbData->has_rtl): ?>
<fieldset class="tb_border_style">
  <legend>Reverse for RTL languages</legend>
  <div class="tb_wrap clear">
    <div class="s_row_2 tb_col tb_1_4">
      <label>Border</label>
      <input type="hidden" name="<?php echo $input_property; ?>[border][rtl_mode]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[border][rtl_mode]" value="1"<?php if($border['rtl_mode'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_4">
      <label>Border radius</label>
      <input type="hidden" name="<?php echo $input_property; ?>[border_radius][rtl_mode]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[border_radius][rtl_mode]" value="1"<?php if($border_radius['rtl_mode'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
  </div>
</fieldset>
<?php endif; ?>