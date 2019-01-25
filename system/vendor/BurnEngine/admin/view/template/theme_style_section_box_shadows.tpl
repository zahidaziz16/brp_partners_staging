<div class="s_sortable_holder tb_style_2">
  <?php $i = 0; ?>
  <?php foreach ($box_shadow['rows'] as $row_key => $row): ?>
  <div class="s_sortable_row tb_box_shadow_row">
    <div class="s_actions">
      <a href="javascript:;" class="tbRemoveRow s_button s_white s_h_20 s_icon_10 s_delete_10">Remove</a>
    </div>
    <h3 class="s_drag_area"><span>Shadow <span class="row_order"><?php echo $i+1; ?></span></span></h3>
    <input type="hidden" name="<?php echo $input_property; ?>[box_shadow][rows][<?php echo $row_key; ?>][size_x]" value="<?php echo $row['size_x']; ?>" />
    <input type="hidden" name="<?php echo $input_property; ?>[box_shadow][rows][<?php echo $row_key; ?>][size_y]" value="<?php echo $row['size_y']; ?>" />
    <div class="tb_color s_row_1 tb_live_row_1">
      <label>Color</label>
      <div class="colorSelector"><div style="background-color: <?php echo $row['color']; ?>"></div></div>
      <input type="text" name="<?php echo $input_property; ?>[box_shadow][rows][<?php echo $row_key; ?>][color]" value="<?php echo $row['color']; ?>" />
    </div>
    <div class="tb_opacity s_row_1 tb_live_row_1">
      <label>Opacity</label>
      <div class="tb_slider"><div></div></div>
      <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[box_shadow][rows][<?php echo $row_key; ?>][opacity]" min="0" max="100" value="<?php echo $row['opacity']; ?>" />
      <span class="s_metric">%</span>
    </div>
    <div class="tb_angle s_row_1 tb_live_row_1">
      <label>Position</label>
      <input type="hidden" name="<?php echo $input_property; ?>[box_shadow][rows][<?php echo $row_key; ?>][inner]" value="0" />
      <label class="s_checkbox">
        <input type="checkbox" name="<?php echo $input_property; ?>[box_shadow][rows][<?php echo $row_key; ?>][inner]" value="1" <?php if($row['inner'] == 1) echo ' checked="checked"'; ?>>
        <span>Inset</span>
      </label>
      <div class="tb_knob">
        <div class="tb_knob_dial">
          <div class="tb_knob_pointer"></div>
        </div>
        <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[box_shadow][rows][<?php echo $row_key; ?>][angle]" min="-179" max="180"  value="<?php echo $row['angle']; ?>" />
        <span class="s_metric">&deg;</span>
      </div>
    </div>
    <div class="tb_distance s_row_1 tb_live_row_1">
      <label>Distance</label>
      <div class="tb_slider"><div></div></div>
      <input class="s_spinner" type="text" min="0" max="100" name="<?php echo $input_property; ?>[box_shadow][rows][<?php echo $row_key; ?>][distance]"  value="<?php echo $row['distance']; ?>" />
      <span class="s_metric">px</span>
    </div>
    <div class="tb_blur s_row_1 tb_live_row_1">
      <label>Blur</label>
      <div class="tb_slider"><div></div></div>
      <input class="s_spinner" type="text" min="0" max="100" name="<?php echo $input_property; ?>[box_shadow][rows][<?php echo $row_key; ?>][blur]" value="<?php echo $row['blur']; ?>" />
      <span class="s_metric">px</span>
    </div>
    <div class="tb_spread s_row_1 tb_live_row_1">
      <label>Spread</label>
      <div class="tb_slider"><div></div></div>
      <input class="s_spinner" type="text" min="0" max="100" name="<?php echo $input_property; ?>[box_shadow][rows][<?php echo $row_key; ?>][spread]" value="<?php echo $row['spread']; ?>" />
      <span class="s_metric">px</span>
    </div>
  </div>
  <?php $i++; ?>
  <?php endforeach; ?>
</div>

<a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 left s_mr_20 tbAddRow">Add Shadow</a>

<span class="clear"></span>