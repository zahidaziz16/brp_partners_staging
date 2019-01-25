<?php // ROW LAYOUT ?>
<?php if ($section != 'row_column' && $section != 'box' && $section != 'title'): ?>
<fieldset>
  <legend>Layout</legend>
  <div class="s_row_1">
    <div class="s_select">
      <select name="<?php echo $input_property; ?>[layout][type]">
        <option value="full"<?php if($layout['type'] == 'full') echo ' selected="selected"';?>>Full</option>
        <?php if ($section != 'wrapper'): ?>
        <option value="full_fixed"<?php if($layout['type'] == 'full_fixed') echo ' selected="selected"';?>>Full (fixed width content)</option>
        <?php endif; ?>
        <option value="fixed"<?php if($layout['type'] == 'fixed') echo ' selected="selected"';?>>Fixed</option>
      </select>
    </div>
  </div>
</fieldset>
<?php endif; ?>

<?php // COLUMN LAYOUT ?>

<?php if ($section == 'row_column'): ?>
<fieldset>
  <legend>Block align</legend>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_3 tb_live_1_1 tb_live_row_1">
      <label>Horizontal</label>
      <div class="s_select">
        <select name="<?php echo $input_property; ?>[layout][align]">
          <option value="start"<?php   if($layout['align'] == 'start')   echo ' selected="selected"';?>>Start</option>
          <option value="center"<?php  if($layout['align'] == 'center')  echo ' selected="selected"';?>>Center</option>
          <option value="end"<?php     if($layout['align'] == 'end')     echo ' selected="selected"';?>>End</option>
          <option value="around"<?php  if($layout['align'] == 'around')  echo ' selected="selected"';?>>Space around</option>
          <option value="between"<?php if($layout['align'] == 'between') echo ' selected="selected"';?>>Space between</option>
        </select>
      </div>
    </div>
    <div class="s_row_2 tb_col tb_1_3 tb_live_1_1 tb_live_row_1">
      <label>Vertical</label>
      <div class="s_select">
        <select name="<?php echo $input_property; ?>[layout][valign]">
          <option value="top"<?php    if($layout['valign'] == 'top')    echo ' selected="selected"';?>>Top</option>
          <option value="middle"<?php if($layout['valign'] == 'middle') echo ' selected="selected"';?>>Middle</option>
          <option value="bottom"<?php if($layout['valign'] == 'bottom') echo ' selected="selected"';?>>Bottom</option>
        </select>
      </div>
    </div>
  </div>
</fieldset>

<fieldset class="tbColumnSticky">
  <legend>Sticky</legend>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_3 tb_live_1_1 tb_live_row_1">
      <label>Enabled</label>
      <input type="hidden" name="<?php echo $input_property; ?>[layout][is_sticky]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][is_sticky]" value="1"<?php if($layout['is_sticky'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_3 tb_live_1_1 tb_live_row_1">
      <label>Offset top</label>
      <input type="text" name="<?php echo $input_property; ?>[layout][sticky_offset]" value="<?php echo $layout['sticky_offset']; ?>" size="5" />
      <span class="s_metric">px</span>
    </div>
  </div>
</fieldset>
<?php endif; ?>

<?php // MARGIN ?>
<?php if ($section != 'row_column'): ?>
<fieldset class="tbMarginFieldset">
  <legend>Margin</legend>
  <div class="tb_wrap">
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Top</label>
      <input class="s_spinner" type="text" min="-150" max="150" step="5" name="<?php echo $input_property; ?>[layout][margin_top]" value="<?php echo $layout['margin_top']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Bottom</label>
      <input class="s_spinner" type="text" min="-150" max="150" step="5" name="<?php echo $input_property; ?>[layout][margin_bottom]" value="<?php echo $layout['margin_bottom']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbStyleMarginLeftWrap">
      <label>Left</label>
      <input class="s_spinner" type="text" min="-150" max="150" step="5" name="<?php echo $input_property; ?>[layout][margin_left]" value="<?php echo $layout['margin_left']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbStyleMarginRightWrap">
      <label>Right</label>
      <input class="s_spinner" type="text" min="-150" max="150" step="5" name="<?php echo $input_property; ?>[layout][margin_right]" value="<?php echo $layout['margin_right']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <?php if ($tbData->has_rtl): ?>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbStyleMarginRTL">
      <label>Reverse for RTL languages</label>
      <input type="hidden" name="<?php echo $input_property; ?>[layout][margin_rtl_mode]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][margin_rtl_mode]" value="1"<?php if($layout['margin_rtl_mode'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <?php endif; ?>
  </div>
</fieldset>
<?php endif; ?>

<?php // PADDING ?>

<fieldset class="tbPaddingFieldset">
  <legend>Padding</legend>
  <?php if ($section == 'row_column'): ?>
  <div class="s_actions tbRowColumnPadding">
    <label class="inline left"><strong>Inherit:</strong></label>
    <input type="hidden" name="<?php echo $input_property; ?>[layout][inherit_padding]" value="0" />
    <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="<?php echo $input_property; ?>[layout][inherit_padding]" value="1"<?php if($layout['inherit_padding'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
  </div>
  <?php endif; ?>
  <div class="tb_wrap tbPaddingWrap">
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Top</label>
      <input class="s_spinner" type="text" min="0" max="100" step="5" name="<?php echo $input_property; ?>[layout][padding_top]" value="<?php echo $layout['padding_top']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Bottom</label>
      <input class="s_spinner" type="text" min="0" max="100" step="5" name="<?php echo $input_property; ?>[layout][padding_bottom]" value="<?php echo $layout['padding_bottom']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Left</label>
      <input class="s_spinner" type="text" min="0" max="100" step="5" name="<?php echo $input_property; ?>[layout][padding_left]" value="<?php echo $layout['padding_left']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Right</label>
      <input class="s_spinner" type="text" min="0" max="100" step="5" name="<?php echo $input_property; ?>[layout][padding_right]" value="<?php echo $layout['padding_right']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <?php if ($tbData->has_rtl): ?>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbStylePaddingRTL">
      <label>Reverse for RTL languages</label>
      <input type="hidden" name="<?php echo $input_property; ?>[layout][padding_rtl_mode]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][padding_rtl_mode]" value="1"<?php if($layout['padding_rtl_mode'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <?php endif; ?>
  </div>
</fieldset>

<?php // COLUMN SIZES + ORDER ?>

<?php if ($section == 'row_column'): ?>
<fieldset class="tbColumnWidths">
  <legend>Width</legend>
  <div class="tb_wrap tb_gut_30">
    <?php foreach ($layout['viewport'] as $size => $settings): ?>
      <div class="s_row_2 tb_col tb_1_3">
        <?php if ($size == 'xs'): ?><label>Phones</label><?php endif; ?>
        <?php if ($size == 'sm'): ?><label>Tablets (portrait)</label><?php endif; ?>
        <?php if ($size == 'md'): ?><label>Tablets (landscape)</label><?php endif; ?>
        <div class="s_select">
          <select name="<?php echo $input_property; ?>[viewport][<?php echo $size; ?>][layout][grid_proportion]">
            <option value="1_6"  <?php if($settings['grid_proportion'] == '1_6'):  ?> selected="selected"<?php endif; ?>>1/6</option>
            <option value="1_5"  <?php if($settings['grid_proportion'] == '1_5'):  ?> selected="selected"<?php endif; ?>>1/5</option>
            <option value="1_4"  <?php if($settings['grid_proportion'] == '1_4'):  ?> selected="selected"<?php endif; ?>>1/4</option>
            <option value="1_3"  <?php if($settings['grid_proportion'] == '1_3'):  ?> selected="selected"<?php endif; ?>>1/3</option>
            <option value="2_5"  <?php if($settings['grid_proportion'] == '2_5'):  ?> selected="selected"<?php endif; ?>>2/5</option>
            <option value="5_12" <?php if($settings['grid_proportion'] == '5_12'): ?> selected="selected"<?php endif; ?>>5/12</option>
            <option value="1_2"  <?php if($settings['grid_proportion'] == '1_2'):  ?> selected="selected"<?php endif; ?>>1/2</option>
            <option value="7_12" <?php if($settings['grid_proportion'] == '7_12'): ?> selected="selected"<?php endif; ?>>7/12</option>
            <option value="3_5"  <?php if($settings['grid_proportion'] == '3_5'):  ?> selected="selected"<?php endif; ?>>3/5</option>
            <option value="2_3"  <?php if($settings['grid_proportion'] == '2_3'):  ?> selected="selected"<?php endif; ?>>2/3</option>
            <option value="3_4"  <?php if($settings['grid_proportion'] == '3_4'):  ?> selected="selected"<?php endif; ?>>3/4</option>
            <option value="4_5"  <?php if($settings['grid_proportion'] == '4_5'):  ?> selected="selected"<?php endif; ?>>4/5</option>
            <option value="5_6"  <?php if($settings['grid_proportion'] == '5_6'):  ?> selected="selected"<?php endif; ?>>5/6</option>
            <option value="11_12"<?php if($settings['grid_proportion'] == '11_12'):?> selected="selected"<?php endif; ?>>11/12</option>
            <option value="1_1"  <?php if($settings['grid_proportion'] == '1_1'):  ?> selected="selected"<?php endif; ?>>1/1</option>
            <option value="auto" <?php if($settings['grid_proportion'] == 'auto'): ?> selected="selected"<?php endif; ?>>Auto</option>
            <option value="fill" <?php if($settings['grid_proportion'] == 'fill'): ?> selected="selected"<?php endif; ?>>Fill</option>
            <option value="none" <?php if($settings['grid_proportion'] == 'none'): ?> selected="selected"<?php endif; ?>>Hidden</option>
          </select>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</fieldset>

<fieldset class="tbColumnHeight">
  <legend>Height</legend>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_3">
      <label>Min. height</label>
      <input type="text" name="<?php echo $input_property; ?>[layout][height]" value="<?php echo $layout['height']; ?>" size="8" />
    </div>
  </div>
</fieldset>

<fieldset class="tbColumnOrder">
  <legend>Order</legend>
  <div class="tb_wrap tb_gut_30">
    <?php foreach ($layout['viewport'] as $size => $settings): ?>
      <div class="s_row_2 tb_col tb_1_3">
        <?php if ($size == 'xs'): ?><label>Phones</label><?php endif; ?>
        <?php if ($size == 'sm'): ?><label>Tablets (portrait)</label><?php endif; ?>
        <?php if ($size == 'md'): ?><label>Tablets (landscape)</label><?php endif; ?>
        <div class="s_select">
          <select name="<?php echo $input_property; ?>[viewport][<?php echo $size; ?>][layout][column_order]">
            <option value="default"<?php if($settings['column_order'] == 'default'): ?> selected="selected"<?php endif; ?>>Default</option>
            <option value="1"<?php if($settings['column_order'] == '1'): ?> selected="selected"<?php endif; ?>>1</option>
            <option value="2"<?php if($settings['column_order'] == '2'): ?> selected="selected"<?php endif; ?>>2</option>
            <option value="3"<?php if($settings['column_order'] == '3'): ?> selected="selected"<?php endif; ?>>3</option>
            <option value="4"<?php if($settings['column_order'] == '4'): ?> selected="selected"<?php endif; ?>>4</option>
            <option value="5"<?php if($settings['column_order'] == '5'): ?> selected="selected"<?php endif; ?>>5</option>
            <option value="6"<?php if($settings['column_order'] == '6'): ?> selected="selected"<?php endif; ?>>6</option>
          </select>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</fieldset>
<?php endif; ?>

<?php // COLUMN GUTTER / STYLE ?>

<?php if ($section == 'widgets_row'): ?>
  <fieldset>
    <legend>Columns</legend>
    <div class="tb_wrap tb_gut_30">
      <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
        <label>Separate</label>
        <input type="hidden" name="<?php echo $input_property; ?>[layout][separate_columns]" value="0" />
        <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][separate_columns]" value="1"<?php if($layout['separate_columns'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
      </div>
      <?php /*
      <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbSeparateColumns">
        <label>Border width</label>
        <input class="s_spinner" type="text" min="1" max="20" step="1" name="<?php echo $input_property; ?>[layout][separate_columns_border_width]" value="<?php echo $layout['separate_columns_border_width']; ?>" size="6" />
        <span class="s_metric">px</span>
      </div>
      */ ?>
      <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbNormalColumns">
        <label>Gutter</label>
        <input class="s_spinner" type="text" min="0" max="50" step="10" name="<?php echo $input_property; ?>[layout][columns_gutter]" value="<?php echo $layout['columns_gutter']; ?>" size="6" />
        <span class="s_metric">px</span>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbSeparateColumns">
        <label>Inner padding</label>
        <input class="s_spinner" type="text" min="0" max="100" step="5" name="<?php echo $input_property; ?>[layout][inner_padding]" value="<?php echo $layout['inner_padding']; ?>" size="6" />
        <span class="s_metric">px</span>
      </div>
      <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
        <label>Merge empty</label>
        <input type="hidden" name="<?php echo $input_property; ?>[layout][merge_columns]" value="0" />
        <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][merge_columns]" value="1"<?php if($layout['merge_columns'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
      </div>
      <?php if ($tbData->has_rtl): ?>
      <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbStylePaddingRTL">
        <label>Reverse for RTL languages</label>
        <input type="hidden" name="<?php echo $input_property; ?>[layout][columns_rtl_mode]" value="0" />
        <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][columns_rtl_mode]" value="1"<?php if($layout['columns_rtl_mode'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
      </div>
      <?php endif; ?>
    </div>
  </fieldset>
<?php endif; ?>

<?php // SECTION -> CONTENT WRAP ?>
<?php if ($section == 'content'): ?>
<fieldset>
  <legend>Sidebars</legend>
  <div class="tb_wrap tbSidebarsWrap">
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Left column width</label>
      <input class="s_spinner" type="text" min="100" name="<?php echo $input_property; ?>[layout][left_column_width]" value="<?php echo $layout['left_column_width']; ?>" size="6" />
      <span class="s_metric">
        <select class="s_metric" name="<?php echo $input_property; ?>[layout][left_column_width_metric]">
          <option value="px"<?php if ($layout['left_column_width_metric'] == 'px') echo 'selected="selected"'; ?>>px</option>
          <option value="%" <?php if ($layout['left_column_width_metric'] == '%')  echo 'selected="selected"'; ?>>%</option>
        </select>
      </span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Right column width</label>
      <input class="s_spinner" type="text" min="100" name="<?php echo $input_property; ?>[layout][right_column_width]" value="<?php echo $layout['right_column_width']; ?>" size="6" />
      <span class="s_metric">
        <select class="s_metric" name="<?php echo $input_property; ?>[layout][right_column_width_metric]">
          <option value="px"<?php if ($layout['right_column_width_metric'] == 'px') echo 'selected="selected"'; ?>>px</option>
          <option value="%" <?php if ($layout['right_column_width_metric'] == '%')  echo 'selected="selected"'; ?>>%</option>
        </select>
      </span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Separate</label>
      <input type="hidden" name="<?php echo $input_property; ?>[layout][separate_columns]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][separate_columns]" value="1"<?php if($layout['separate_columns'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <?php /*
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbSeparateColumns">
      <label>Border width</label>
      <input class="s_spinner" type="text" min="1" max="20" step="1" name="<?php echo $input_property; ?>[layout][separate_columns_border_width]" value="<?php echo $layout['separate_columns_border_width']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    */ ?>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbSeparateColumns">
      <label>Inner padding</label>
      <input class="s_spinner" type="text" min="0" max="50" step="5" name="<?php echo $input_property; ?>[layout][inner_padding]" value="<?php echo $layout['inner_padding']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbNormalColumns">
      <label>Gutter</label>
      <input class="s_spinner" type="text" min="0" max="50" step="10" name="<?php echo $input_property; ?>[layout][columns_gutter]" value="<?php echo $layout['columns_gutter']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <?php if ($tbData->has_rtl): ?>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1 tbStylePaddingRTL">
      <label>Reverse for RTL languages</label>
      <input type="hidden" name="<?php echo $input_property; ?>[layout][columns_rtl_mode]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][columns_rtl_mode]" value="1"<?php if($layout['columns_rtl_mode'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <?php endif; ?>
  </div>
</fieldset>
<fieldset>
  <legend>Responsive sidebars</legend>
  <p class="s_help">Set zero value will inherit global sidebar width.</p>
  <div class="tb_wrap tbSidebarsWrap">
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Left (tablet)</label>
      <input class="s_spinner" type="text" min="100" name="<?php echo $input_property; ?>[layout][left_column_width_sm]" value="<?php echo $layout['left_column_width_sm']; ?>" size="6" />
      <span class="s_metric">
        <select class="s_metric" name="<?php echo $input_property; ?>[layout][left_column_width_metric_sm]">
          <option value="px"<?php if ($layout['left_column_width_metric_sm'] == 'px') echo 'selected="selected"'; ?>>px</option>
          <option value="%" <?php if ($layout['left_column_width_metric_sm'] == '%')  echo 'selected="selected"'; ?>>%</option>
        </select>
      </span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Left (small desktop)</label>
      <input class="s_spinner" type="text" min="100" name="<?php echo $input_property; ?>[layout][left_column_width_md]" value="<?php echo $layout['left_column_width_md']; ?>" size="6" />
      <span class="s_metric">
        <select class="s_metric" name="<?php echo $input_property; ?>[layout][left_column_width_metric_md]">
          <option value="px"<?php if ($layout['left_column_width_metric_md'] == 'px') echo 'selected="selected"'; ?>>px</option>
          <option value="%" <?php if ($layout['left_column_width_metric_md'] == '%')  echo 'selected="selected"'; ?>>%</option>
        </select>
      </span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Right (tablet)</label>
      <input class="s_spinner" type="text" min="100" name="<?php echo $input_property; ?>[layout][right_column_width_sm]" value="<?php echo $layout['right_column_width_sm']; ?>" size="6" />
      <span class="s_metric">
        <select class="s_metric" name="<?php echo $input_property; ?>[layout][right_column_width_metric_sm]">
          <option value="px"<?php if ($layout['right_column_width_metric_sm'] == 'px') echo 'selected="selected"'; ?>>px</option>
          <option value="%" <?php if ($layout['right_column_width_metric_sm'] == '%')  echo 'selected="selected"'; ?>>%</option>
        </select>
      </span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Right (small desktop)</label>
      <input class="s_spinner" type="text" min="100" name="<?php echo $input_property; ?>[layout][right_column_width_md]" value="<?php echo $layout['right_column_width_md']; ?>" size="6" />
      <span class="s_metric">
        <select class="s_metric" name="<?php echo $input_property; ?>[layout][right_column_width_metric_md]">
          <option value="px"<?php if ($layout['right_column_width_metric_md'] == 'px') echo 'selected="selected"'; ?>>px</option>
          <option value="%" <?php if ($layout['right_column_width_metric_md'] == '%')  echo 'selected="selected"'; ?>>%</option>
        </select>
      </span>
    </div>
  </div>
</fieldset>
<fieldset>
  <legend>Sticky</legend>
  <div class="tb_wrap">
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Left column</label>
      <input type="hidden" name="<?php echo $input_property; ?>[layout][left_column_is_sticky]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][left_column_is_sticky]" value="1"<?php if($layout['left_column_is_sticky'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Offset</label>
      <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[layout][left_column_sticky_offset]" value="<?php echo $layout['left_column_sticky_offset']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Right column</label>
      <input type="hidden" name="<?php echo $input_property; ?>[layout][right_column_is_sticky]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][right_column_is_sticky]" value="1"<?php if($layout['right_column_is_sticky'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_1_1 tb_live_row_1">
      <label>Offset</label>
      <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[layout][right_column_sticky_offset]" value="<?php echo $layout['right_column_sticky_offset']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
  </div>
</fieldset>
<?php endif; ?>

