<input type="hidden" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][background_type]" value="gradient" data-bg-row-key="<?php echo $bg_row_key; ?>" />
<div class="s_row_1 tb_live_row_2">
  <label>Type</label>
    <div class="s_select">
      <select name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][type]" class="tbGradientType">
        <option value="linear"<?php if($bg_row['type'] == 'linear') echo ' selected="selected"';?>>Linear</option>
        <option value="radial"<?php if($bg_row['type'] == 'radial') echo ' selected="selected"';?>>Radial</option>
      </select>
  </div>
</div>
<div class="tb_angle s_row_1 tb_gradient_color_row tbLinearGroup"<?php if($bg_row['type'] == 'radial'):?> style="display: none;"<?php endif; ?>>
  <label>Angle</label>
  <div class="tb_knob">
    <div class="tb_knob_dial">
      <div class="tb_knob_pointer"></div>
    </div>
    <input type="text" class="s_spinner" min="-179" max="180" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][angle]" value="<?php echo $bg_row['angle']; ?>" />
    <span class="s_metric">&deg;</span>
  </div>
</div>
<?php if (0 !== strpos($input_property, 'widget_data')): ?>
<div class="s_row_1 tb_live_row_2">
  <label>Bg container</label>
  <div class="s_select">
    <select name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][container]">
      <option value="row"<?php      if ($bg_row['container'] == 'row')     echo ' selected="selected"'; ?>>Row</option>
      <option value="content"<?php  if ($bg_row['container'] == 'content') echo ' selected="selected"'; ?>>Content area</option>
    </select>
  </div>
</div>
<?php endif; ?>
<div class="s_row_1 tb_live_row_2">
  <label><?php echo $text_position; ?></label>
  <div class="s_select">
    <select name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][position]">
      <option value="top left"<?php      if ($bg_row['position'] == 'top left')      echo ' selected="selected"'; ?>><?php echo $text_opt_position_1; ?></option>
      <option value="top center"<?php    if ($bg_row['position'] == 'top center')    echo ' selected="selected"'; ?>><?php echo $text_opt_position_2; ?></option>
      <option value="top right"<?php     if ($bg_row['position'] == 'top right')     echo ' selected="selected"'; ?>><?php echo $text_opt_position_3; ?></option>
      <option value="right"<?php         if ($bg_row['position'] == 'right')         echo ' selected="selected"'; ?>><?php echo $text_opt_position_4; ?></option>
      <option value="bottom right"<?php  if ($bg_row['position'] == 'bottom right')  echo ' selected="selected"'; ?>><?php echo $text_opt_position_5; ?></option>
      <option value="bottom center"<?php if ($bg_row['position'] == 'bottom center') echo ' selected="selected"'; ?>><?php echo $text_opt_position_6; ?></option>
      <option value="bottom left"<?php   if ($bg_row['position'] == 'bottom left')   echo ' selected="selected"'; ?>><?php echo $text_opt_position_7; ?></option>
      <option value="left"<?php          if ($bg_row['position'] == 'left')          echo ' selected="selected"'; ?>><?php echo $text_opt_position_8; ?></option>
      <option value="center"<?php        if ($bg_row['position'] == 'center')        echo ' selected="selected"'; ?>><?php echo $text_opt_position_9; ?></option>
      <option value="custom"<?php        if ($bg_row['position'] == 'custom')        echo ' selected="selected"'; ?>>Custom</option>
    </select>
  </div>
</div>
<div class="s_row_1 tb_live_row_2"<?php if ($bg_row['position'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
  <label>X</label>
  <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][position_x]" value="<?php echo $bg_row['position_x']; ?>" size="7" />
  <span class="s_metric">
    <select class="s_metric" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][position_x_metric]">
      <option value="px"<?php if ($bg_row['position_x_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
      <option value="%"<?php  if ($bg_row['position_x_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
    </select>
  </span>
</div>
<div class="s_row_1 tb_live_row_2"<?php if ($bg_row['position'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
  <label>Y</label>
  <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][position_y]" value="<?php echo $bg_row['position_y']; ?>" size="7" />
  <span class="s_metric">
    <select class="s_metric" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][position_y_metric]">
      <option value="px"<?php if ($bg_row['position_y_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
      <option value="%"<?php  if ($bg_row['position_y_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
    </select>
  </span>
</div>
<div class="s_row_1 tb_live_row_2">
  <label><?php echo $text_repeat; ?></label>
  <div class="s_select">
    <select name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][repeat]">
      <option value="no-repeat"<?php if ($bg_row['repeat'] == 'no-repeat') echo ' selected="selected"'; ?>><?php echo $text_opt_repeat_1; ?></option>
      <option value="repeat-x"<?php  if ($bg_row['repeat'] == 'repeat-x')  echo ' selected="selected"'; ?>><?php echo $text_opt_repeat_3; ?></option>
      <option value="repeat-y"<?php  if ($bg_row['repeat'] == 'repeat-y')  echo ' selected="selected"'; ?>><?php echo $text_opt_repeat_4; ?></option>
      <option value="repeat"<?php    if ($bg_row['repeat'] == 'repeat')    echo ' selected="selected"'; ?>><?php echo $text_opt_repeat_2; ?></option>
    </select>
  </div>
</div>
<div class="s_row_1 tb_live_row_2">
  <label>Size</label>
  <div class="s_select">
    <select name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][size]">
      <option value="auto"<?php    if ($bg_row['size'] == 'auto') echo ' selected="selected"'; ?>>Auto</option>
      <option value="custom"<?php  if ($bg_row['size'] == 'custom')  echo ' selected="selected"'; ?>>Custom</option>
    </select>
  </div>
</div>
<div class="s_row_1 tb_live_row_2"<?php if ($bg_row['size'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
  <label>Width</label>
  <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][size_x]" value="<?php echo $bg_row['size_x']; ?>" size="7" min="0" />
  <span class="s_metric">
    <select class="s_metric" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][size_x_metric]">
      <option value="px"<?php if ($bg_row['size_x_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
      <option value="%"<?php  if ($bg_row['size_x_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
    </select>
  </span>
</div>
<div class="s_row_1 tb_live_row_2"<?php if ($bg_row['size'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
  <label>Height</label>
  <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][size_y]" value="<?php echo $bg_row['size_y']; ?>" size="7" min="0" />
  <span class="s_metric">
    <select class="s_metric" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][size_y_metric]">
      <option value="px"<?php if ($bg_row['size_y_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
      <option value="%"<?php  if ($bg_row['size_y_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
    </select>
  </span>
</div>
<div class="s_row_1 tb_live_row_2">
  <label><?php echo $text_attachment; ?></label>
  <div class="s_select">
    <select name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][attachment]">
      <option value="scroll"<?php if ($bg_row['attachment'] == 'scroll') echo ' selected="selected"'; ?>><?php echo $text_opt_attachment_1; ?></option>
      <option value="fixed"<?php  if ($bg_row['attachment'] == 'fixed')  echo ' selected="selected"'; ?>><?php echo $text_opt_attachment_2; ?></option>
    </select>
  </div>
</div>
<div class="s_sortable_holder tb_style_2">
  <?php $i = 0; ?>
  <?php foreach ($bg_row['colors'] as $row_key => $color_row): ?>
  <div class="s_sortable_row tb_gradient_color_row tbBgColorRow">
    <div class="s_actions">
      <a href="javascript:;" class="tbRemoveColorRow s_button s_white s_h_20 s_icon_10 s_delete_10">Remove</a>
    </div>
    <h3 class="s_drag_area"><span>Color <span class="row_order"><?php echo $i+1; ?></span></span></h3>
    <div class="tb_color s_row_1 tb_live_row_1">
      <label>Color</label>
      <div class="colorSelector">
        <div style="background-color: <?php echo $color_row['color']; ?>"></div>
      </div>
      <input type="text" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][colors][<?php echo $row_key; ?>][color]" value="<?php echo $color_row['color']; ?>" />
    </div>
    <div class="tb_opacity s_row_1 tb_live_row_1">
      <label>Opacity</label>
      <div class="tb_slider"><div></div></div>
      <input type="text" class="s_spinner" min="0" max="100" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][colors][<?php echo $row_key; ?>][opacity]" value="<?php echo $color_row['opacity']; ?>" />
      <span class="s_metric">%</span>
    </div>
    <div class="s_row_1 tb_wrap tbBgColorRowOffset">
      <div class="tb_distance s_row_1 tb_live_row_2 tb_col<?php if($color_row['offset_auto']) echo ' tb_disabled'; ?>">
        <label>Offset</label>
        <div class="tb_slider"><div></div></div>
        <input type="text" class="s_spinner" min="0" max="100" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][colors][<?php echo $row_key; ?>][offset]" value="<?php echo $color_row['offset']; ?>" />
        <span class="s_metric">%</span>
      </div>
      <div class="s_row_1 tb_live_row_2 tb_col">
        <input type="hidden" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][colors][<?php echo $row_key; ?>][offset_auto]" value="0" />
        <label class="s_checkbox">
          <input type="checkbox" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][colors][<?php echo $row_key; ?>][offset_auto]" value="1"<?php if($color_row['offset_auto'] == '1') echo ' checked="checked"';?> />
          <span>Auto</span>
        </label>
      </div>
    </div>
  </div>
  <?php $i++; ?>
  <?php endforeach; ?>
</div>
<a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddColorRow">Add Color</a>
<a href="javascript:;" class="s_button s_h_30 s_white s_icon_10 s_delete_10 tbRemoveBackgroundRow">Remove Gradient</a>