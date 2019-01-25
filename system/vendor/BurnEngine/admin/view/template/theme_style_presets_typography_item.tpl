<fieldset id="fonts_group_presets_<?php echo $presets_section; ?>_<?php echo $group_id; ?>" class="tb_font_row tbFontGroup" data-group_id="<?php echo $group_id; ?>">
  <?php if (!empty($font_section['section_name'])): ?>
  <legend><?php echo $font_section['section_name']; ?></legend>
  <?php endif; ?>
  <?php
    echo $tbData->fetchTemplate('theme_design_typography_item', array(
      'input_property' => "presets[$presets_section][font][$language_code][$name]",
      'font'           => $font_section,
      'font_data'      => $font_data
    ));
  ?>
</fieldset>