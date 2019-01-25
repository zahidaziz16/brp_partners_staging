<div class="tb_color_row tb_wrap tbColorItem">
  <div class="tb_col tb_2_5 s_row_2 s_full">
    <label><strong><?php echo $color_item['label']; ?></strong> (Selectors)</label>
    <textarea name="colors[custom][<?php echo $section_key; ?>][elements]"><?php echo $color_item['elements']; ?></textarea>
    <input type="hidden" name="colors[custom][<?php echo $section_key; ?>][label]" value="<?php echo $color_item['label']; ?>" />
    <input type="hidden" name="colors[custom][<?php echo $section_key; ?>][inherit]" value="<?php echo $color_item['inherit']; ?>" />
  </div>
  <div class="s_row_2 tb_col tb_1_5">
    <label>CSS property</label>
    <div class="s_full clearfix">
      <div class="s_select">
        <select name="colors[custom][<?php echo $section_key; ?>][property]">
          <option value="color"<?php if($color_item['property'] == 'color'): ?> selected="selected"<?php endif; ?>>Color</option>
          <option value="border-color"<?php if($color_item['property'] == 'border-color'): ?> selected="selected"<?php endif; ?>>Border color</option>
          <option value="background-color"<?php if($color_item['property'] == 'background-color'): ?> selected="selected"<?php endif; ?>>Background color</option>
        </select>
      </div>
    </div>
  </div>
  <div class="s_row_2 tb_col tb_1_5">
    <label>Color</label>
    <div class="colorSelector" name="main">
      <div style="background-color: <?php echo $color_item['color']; ?>;"></div>
    </div>
    <input type="text" name="colors[custom][<?php echo $section_key; ?>][color]" value="<?php echo $color_item['color']; ?>" />
  </div>
  <div class="s_row_2 tb_col tb_1_5">
    <label>Important</label>
    <div class="s_full clearfix">
      <input type="hidden" name="colors[custom][<?php echo $section_key; ?>][important]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="colors[custom][<?php echo $section_key; ?>][important]" value="1"<?php if($color_item['important']): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
    </div>
  </div>
  <div class="s_actions">
    <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveItem" href="javascript:;"></a>
  </div>
</div>