<div class="tb_image_row tbBgImageRow">
  <input type="hidden" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][background_type]" value="image" />
  <input type="hidden" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][image]" value="<?php echo $bg_row['image']; ?>" id="bg_image_style_<?php echo $section . '_' . $bg_row_key; ?>"  />
  <div class="tb_wrap">
    <div class="tb_col">
        <span class="tb_thumb">
          <img src="<?php echo $bg_row['preview']; ?>" id="bg_preview_style_<?php echo $section . '_' . $bg_row_key; ?>" class="image" onclick="image_upload('bg_image_style_<?php echo $section . '_' . $bg_row_key; ?>', 'bg_preview_style_<?php echo $section . '_' . $bg_row_key; ?>');" />
        </span>
    </div>
    <div class="tb_col tb_auto tbImageOptions">
      <div class="s_row_1">
        <label><strong>Filename:</strong></label><em class="tb_filename tbFilename"><?php echo basename($bg_row['image']); ?></em>
      </div>
      <?php if (0 !== strpos($input_property, 'widget_data')): ?>
      <div class="s_row_1">
        <label>Bg container</label>
        <div class="s_select">
          <select name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][container]">
            <option value="row"<?php      if ($bg_row['container'] == 'row')     echo ' selected="selected"'; ?>>Row</option>
            <option value="content"<?php  if ($bg_row['container'] == 'content') echo ' selected="selected"'; ?>>Content area</option>
          </select>
        </div>
      </div>
      <?php endif; ?>
      <div class="s_row_1">
        <label><?php echo $text_position; ?></label>
        <div class="s_select">
          <select name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][position]">
            <option value="top left"<?php     if ($bg_row['position'] == 'top left')     echo ' selected="selected"'; ?>><?php echo $text_opt_position_1; ?></option>
            <option value="top"<?php          if ($bg_row['position'] == 'top')          echo ' selected="selected"'; ?>><?php echo $text_opt_position_2; ?></option>
            <option value="top right"<?php    if ($bg_row['position'] == 'top right')    echo ' selected="selected"'; ?>><?php echo $text_opt_position_3; ?></option>
            <option value="right"<?php        if ($bg_row['position'] == 'right')        echo ' selected="selected"'; ?>><?php echo $text_opt_position_4; ?></option>
            <option value="bottom right"<?php if ($bg_row['position'] == 'bottom right') echo ' selected="selected"'; ?>><?php echo $text_opt_position_5; ?></option>
            <option value="bottom"<?php       if ($bg_row['position'] == 'bottom')       echo ' selected="selected"'; ?>><?php echo $text_opt_position_6; ?></option>
            <option value="bottom left"<?php  if ($bg_row['position'] == 'bottom left')  echo ' selected="selected"'; ?>><?php echo $text_opt_position_7; ?></option>
            <option value="left"<?php         if ($bg_row['position'] == 'left')         echo ' selected="selected"'; ?>><?php echo $text_opt_position_8; ?></option>
            <option value="center"<?php       if ($bg_row['position'] == 'center')       echo ' selected="selected"'; ?>><?php echo $text_opt_position_9; ?></option>
            <option value="custom"<?php       if ($bg_row['position'] == 'custom')       echo ' selected="selected"'; ?>>Custom</option>
          </select>
        </div>
      </div>
      <div class="s_row_1"<?php if ($bg_row['position'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
        <label>X</label>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][position_x]" value="<?php echo $bg_row['position_x']; ?>" size="7" />
        <span class="s_metric">
          <select class="s_metric" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][position_x_metric]">
            <option value="px"<?php if ($bg_row['position_x_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
            <option value="%"<?php  if ($bg_row['position_x_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
          </select>
        </span>
      </div>
      <div class="s_row_1"<?php if ($bg_row['position'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
        <label>Y</label>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][position_y]" value="<?php echo $bg_row['position_y']; ?>" size="7" />
        <span class="s_metric">
          <select class="s_metric" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][position_y_metric]">
            <option value="px"<?php if ($bg_row['position_y_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
            <option value="%"<?php  if ($bg_row['position_y_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
          </select>
        </span>
      </div>
      <div class="s_row_1">
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
      <div class="s_row_1">
        <label>Size</label>
        <div class="s_select">
          <select name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][size]">
            <option value="auto"<?php    if ($bg_row['size'] == 'auto') echo ' selected="selected"'; ?>>Auto</option>
            <option value="contain"<?php if ($bg_row['size'] == 'contain') echo ' selected="selected"'; ?>>Contain</option>
            <option value="cover"<?php   if ($bg_row['size'] == 'cover')  echo ' selected="selected"'; ?>>Cover</option>
            <option value="custom"<?php  if ($bg_row['size'] == 'custom')  echo ' selected="selected"'; ?>>Custom</option>
          </select>
        </div>
      </div>
      <div class="s_row_1"<?php if ($bg_row['size'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
        <label>Width</label>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][size_x]" value="<?php echo $bg_row['size_x']; ?>" size="7" min="0" />
        <span class="s_metric">
          <select class="s_metric" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][size_x_metric]">
            <option value="px"<?php if ($bg_row['size_x_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
            <option value="%"<?php  if ($bg_row['size_x_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
          </select>
        </span>
      </div>
      <div class="s_row_1"<?php if ($bg_row['size'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
        <label>Height</label>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][size_y]" value="<?php echo $bg_row['size_y']; ?>" size="7" min="0" />
        <span class="s_metric">
          <select class="s_metric" name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][size_y_metric]">
            <option value="px"<?php if ($bg_row['size_y_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
            <option value="%"<?php  if ($bg_row['size_y_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
          </select>
        </span>
      </div>
      <div class="s_row_1">
        <label><?php echo $text_attachment; ?></label>
        <div class="s_select">
          <select name="<?php echo $input_property; ?>[background][rows][<?php echo $bg_row_key; ?>][attachment]">
            <option value="scroll"<?php if ($bg_row['attachment'] == 'scroll') echo ' selected="selected"'; ?>><?php echo $text_opt_attachment_1; ?></option>
            <option value="fixed"<?php  if ($bg_row['attachment'] == 'fixed')  echo ' selected="selected"'; ?>><?php echo $text_opt_attachment_2; ?></option>
          </select>
        </div>
      </div>
    </div>
  </div>
  <span class="clear s_mb_20"></span>
  <a href="javascript:;" class="s_button s_h_30 s_white s_icon_10 s_delete_10 tbRemoveBackgroundRow">Remove Image</a>
</div>