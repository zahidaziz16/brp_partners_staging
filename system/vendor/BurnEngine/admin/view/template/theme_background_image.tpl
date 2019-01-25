<?php if ($section_name == 'page'): ?>
<h3><span class="tbBgCategoryAction">New</span> Page Background</h3>
<?php elseif ($section_name == 'category'): ?>
<h3><span class="tbBgCategoryAction">New</span> Category Background</h3>
<?php else: ?>
<h3>Global Background</h3>
<?php endif; ?>
<div class="tb_wrap tb_gut_40">
  <div class="tb_col tb_5_12">
    <?php if ($section_name != 'global'): ?>
    <div class="s_row_2">
      <?php if ($section_name == 'page'): ?>
      <div class="s_select">
        <select class="tbSelectSiteBackground">
          <option>- Choose <?php echo $section_name; ?> -</option>
        </select>
      </div>
      <?php else: ?>
      <div class="s_full">
        <select class="tbSelectSiteBackground">
        </select>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="s_row_2<?php if ($section_name != 'global'): ?> tb_disabled<?php endif; ?> tbSiteBackgroundType">
      <label>Background:</label>
      <span class="clear"></span>
      <label class="s_radio"><input type="radio" name="<?php echo $input_name_property; ?>[type]" value="custom"<?php if ($background['type'] == 'custom'): ?> checked="checked"<?php endif; ?> /><span>Image</span></label>
      <label class="s_radio"><input type="radio" name="<?php echo $input_name_property; ?>[type]" value="none"<?php if ($background['type'] == 'none'): ?> checked="checked"<?php endif; ?> /><span>None</span></label>
    </div>
  </div>
  <div class="tb_col tb_7_12">
    <div class="tb_image_listing tb_list_view<?php if ($section_name != 'global' || $background['type'] == 'none'): ?> tb_disabled<?php endif; ?> tbSiteBackgroundImageForm">
      <div class="tb_wrap s_mb_20">
        <input type="hidden" name="<?php echo $input_name_property; ?>[image]" value="<?php echo $background['image']; ?>" id="site_background_<?php echo $section_name; ?>_image"  />
        <div class="tb_col">
          <span class="tb_thumb">
            <img src="<?php echo $background['preview']; ?>" id="site_background_<?php echo $section_name; ?>_preview" class="image" onclick="image_upload('site_background_<?php echo $section_name; ?>_image', 'site_background_<?php echo $section_name; ?>_preview');" />
          </span>
        </div>
        <div class="tb_col tbImageOptions">
          <div class="s_row_1">
            <label><?php echo $text_position; ?></label>
            <div class="s_select">
              <select name="<?php echo $input_name_property; ?>[position]">
                <option value="top left"<?php      if ($background['position'] == 'top left')      echo ' selected="selected"'; ?>><?php echo $text_opt_position_1; ?></option>
                <option value="top center"<?php    if ($background['position'] == 'top center')    echo ' selected="selected"'; ?>><?php echo $text_opt_position_2; ?></option>
                <option value="top right"<?php     if ($background['position'] == 'top right')     echo ' selected="selected"'; ?>><?php echo $text_opt_position_3; ?></option>
                <option value="right"<?php         if ($background['position'] == 'right')         echo ' selected="selected"'; ?>><?php echo $text_opt_position_4; ?></option>
                <option value="bottom right"<?php  if ($background['position'] == 'bottom right')  echo ' selected="selected"'; ?>><?php echo $text_opt_position_5; ?></option>
                <option value="bottom center"<?php if ($background['position'] == 'bottom center') echo ' selected="selected"'; ?>><?php echo $text_opt_position_6; ?></option>
                <option value="bottom left"<?php   if ($background['position'] == 'bottom left')   echo ' selected="selected"'; ?>><?php echo $text_opt_position_7; ?></option>
                <option value="left"<?php          if ($background['position'] == 'left')          echo ' selected="selected"'; ?>><?php echo $text_opt_position_8; ?></option>
                <option value="center"<?php        if ($background['position'] == 'center')        echo ' selected="selected"'; ?>><?php echo $text_opt_position_9; ?></option>
                <option value="custom"<?php        if ($background['position'] == 'custom')        echo ' selected="selected"'; ?>>Custom</option>
              </select>
            </div>
          </div>
          <div class="s_row_1 tbPositionX"<?php if ($background['position'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
            <label>X</label>
            <input class="s_spinner" type="text" name="<?php echo $input_name_property; ?>[position_x]" value="<?php echo $background['position_x']; ?>" size="7" min="0" />
            <span class="s_metric">
              <select class="s_metric" name="<?php echo $input_name_property; ?>[position_x_metric]">
                <option value="px"<?php if ($background['position_x_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
                <option value="%"<?php  if ($background['position_x_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
              </select>
            </span>
          </div>
          <div class="s_row_1 tbPositionY"<?php if ($background['position'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
            <label>Y</label>
            <input class="s_spinner" type="text" name="<?php echo $input_name_property; ?>[position_y]" value="<?php echo $background['position_y']; ?>" size="7" min="0" />
            <span class="s_metric">
              <select class="s_metric" name="<?php echo $input_name_property; ?>[position_y_metric]">
                <option value="px"<?php if ($background['position_y_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
                <option value="%"<?php  if ($background['position_y_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
              </select>
            </span>
          </div>
          <div class="s_row_1">
            <label><?php echo $text_repeat; ?></label>
            <div class="s_select">
              <select name="<?php echo $input_name_property; ?>[repeat]">
                <option value="no-repeat"<?php if ($background['repeat'] == 'no-repeat') echo ' selected="selected"'; ?>><?php echo $text_opt_repeat_1; ?></option>
                <option value="repeat-x"<?php  if ($background['repeat'] == 'repeat-x')  echo ' selected="selected"'; ?>><?php echo $text_opt_repeat_3; ?></option>
                <option value="repeat-y"<?php  if ($background['repeat'] == 'repeat-y')  echo ' selected="selected"'; ?>><?php echo $text_opt_repeat_4; ?></option>
                <option value="repeat"<?php    if ($background['repeat'] == 'repeat')    echo ' selected="selected"'; ?>><?php echo $text_opt_repeat_2; ?></option>
              </select>
            </div>
          </div>
          <div class="s_row_1">
            <label><?php echo $text_attachment; ?></label>
            <div class="s_select">
              <select name="<?php echo $input_name_property; ?>[attachment]">
                <option value="scroll"<?php if ($background['attachment'] == 'scroll') echo ' selected="selected"'; ?>><?php echo $text_opt_attachment_1; ?></option>
                <option value="fixed"<?php  if ($background['attachment'] == 'fixed')  echo ' selected="selected"'; ?>><?php echo $text_opt_attachment_2; ?></option>
              </select>
            </div>
          </div>
          <div class="s_row_1">
            <label>Size</label>
            <div class="s_select">
              <select name="<?php echo $input_name_property; ?>[size]">
                <option value="auto"<?php    if ($background['size'] == 'auto') echo ' selected="selected"'; ?>>Auto</option>
                <option value="contain"<?php if ($background['size'] == 'contain') echo ' selected="selected"'; ?>>Contain</option>
                <option value="cover"<?php   if ($background['size'] == 'cover')  echo ' selected="selected"'; ?>>Cover</option>
                <option value="custom"<?php  if ($background['size'] == 'custom')  echo ' selected="selected"'; ?>>Custom</option>
              </select>
            </div>
          </div>
          <div class="s_row_1 tbSizeX"<?php if ($background['size'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
            <label>Width</label>
            <input class="s_spinner" type="text" name="<?php echo $input_name_property; ?>[size_x]" value="<?php echo $background['size_x']; ?>" size="6" min="0" />
            <span class="s_metric">
              <select class="s_metric" name="<?php echo $input_name_property; ?>[size_x_metric]">
                <option value="px"<?php if ($background['size_x_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
                <option value="%"<?php  if ($background['size_x_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
              </select>
            </span>
          </div>
          <div class="s_row_1 tbSizeY"<?php if ($background['size'] != 'custom'): ?>  style="display: none;"<?php endif; ?>>
            <label>Height</label>
            <input class="s_spinner" type="text" name="<?php echo $input_name_property; ?>[size_y]" value="<?php echo $background['size_y']; ?>" size="6" min="0" />
            <span class="s_metric">
              <select class="s_metric" name="<?php echo $input_name_property; ?>[size_y_metric]">
                <option value="px"<?php if ($background['size_y_metric'] == 'px') echo ' selected="selected"'; ?>>px</option>
                <option value="%"<?php  if ($background['size_y_metric'] == '%')  echo ' selected="selected"'; ?>>%</option>
              </select>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if ($section_name != 'global'): ?>
<div class="tb_disabled tbAddSiteBackground">
  <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10"><span class="tbBgCategoryAction">New</span> Background</a>
</div>
<?php endif; ?>
<?php if ($section_name == 'category'): ?>
<p class="s_help tbBgCategoryInheritInfo" style="display: none;">The selected category is currently using the background settings of <strong><span></span></strong></p>
<?php endif; ?>
