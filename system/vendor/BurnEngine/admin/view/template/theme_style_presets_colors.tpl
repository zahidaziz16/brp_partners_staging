<?php if (isset($presets_section_colors)): ?>
<?php foreach($presets_section_colors as $group_key => $group_values): ?>
<?php if ($group_key != 'custom'): ?>
<?php $group_id = $group_key; if (false !== strpos($group_key, '__')) $group_id = substr($group_key, 0, strpos($group_key, '__')); ?>
<fieldset id="colors_group_presets_box_<?php echo $group_key; ?>" class="tb_color_row tbColorGroup" data-group_id="<?php echo $group_id; ?>">
  <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
  <div class="tb_wrap">
    <?php foreach ($group_values as $section_key => $color_item): ?>
    <div id="presets_box_color_item_<?php echo $group_key; ?>_<?php echo $section_key; ?>" class="s_row_2 tb_col tb_1_4 tb_live_row_1 tb_live_1_1 tbColorItem"<?php if (!empty($color_item['parent_id'])): ?> data-parent_id="presets_box_color_item_<?php echo $color_item['parent_id']; ?>"<?php endif; ?>>
      <label><?php echo $color_item['label']; ?></label>
      <div class="colorSelector<?php if ($color_item['property'] == 'background-color' || $color_item['property'] == 'border-color'): ?> tbBackgroundColor<?php endif; ?>" name="main">
        <div style="background-color: <?php echo $color_item['color']; ?>;"></div>
      </div>
      <input type="text" name="presets[<?php echo $presets_section; ?>][colors][<?php echo $group_key; ?>][<?php echo $section_key; ?>][color]" value="<?php echo $color_item['color']; ?>" />
      <input type="hidden" name="presets[<?php echo $presets_section; ?>][colors][<?php echo $group_key; ?>][<?php echo $section_key; ?>][important]" value="<?php echo $color_item['important']; ?>" />
      <input type="hidden" name="presets[<?php echo $presets_section; ?>][colors][<?php echo $group_key; ?>][<?php echo $section_key; ?>][property]" value="<?php echo $color_item['property']; ?>" />
      <textarea name="presets[<?php echo $presets_section; ?>][colors][<?php echo $group_key; ?>][<?php echo $section_key; ?>][elements]" style="display: none;"><?php echo $color_item['elements']; ?></textarea>
    </div>
    <?php endforeach; ?>
  </div>
</fieldset>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
