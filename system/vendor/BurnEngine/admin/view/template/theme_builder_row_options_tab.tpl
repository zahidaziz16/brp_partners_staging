<div class="tb_tabs clearfix tbWidgetsRowMainTabs">

  <div class="tb_tabs_nav s_box_1">
    <h3>Styles</h3>
    <ul class="tb_nav clearfix">
      <li><a href="#style_settings_<?php echo $section_id; ?>_layout" section="layout">Layout</a></li>
      <li><a href="#style_settings_<?php echo $section_id; ?>_box_shadow" section="box_shadow">Box Shadow</a></li>
      <li><a href="#style_settings_<?php echo $section_id; ?>_background" section="background">Background</a></li>
      <li><a href="#style_settings_<?php echo $section_id; ?>_border" section="border">Border</a></li>
      <li><a href="#style_settings_<?php echo $section_id; ?>_colors" section="colors">Colors</a></li>
      <li><a href="#style_settings_<?php echo $section_id; ?>_typography" section="typography">Typography</a></li>
    </ul>
    <div class="tb_style_preview_wrap">
      <h4>Preview</h4>
      <div class="tb_style_preview">
        <div id="<?php echo $section_id; ?>_style_preview" class="tb_style_preview_box"></div>
      </div>
    </div>
  </div>

  <div id="style_settings_<?php echo $section_id; ?>_layout">
    <h2>Layout</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_layout', array('section' => $section, 'input_property' => $input_property, 'layout' => $settings['layout'])); ?>
  </div>

  <div id="style_settings_<?php echo $section_id; ?>_box_shadow">
    <h2>Shadow</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_box_shadows', array('section' => $section, 'input_property' => $input_property, 'box_shadow' => $settings['box_shadow'])); ?>
  </div>

  <div id="style_settings_<?php echo $section_id; ?>_background">
    <h2>Background</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_background', array('section' => $section, 'input_property' => $input_property, 'background' => $settings['background'])); ?>
  </div>

  <div id="style_settings_<?php echo $section_id; ?>_border">
    <h2>Border</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_border', array('section' => $section, 'input_property' => $input_property, 'border' => $settings['border'], 'border_radius' => $settings['border_radius'])); ?>
  </div>

  <?php // Colors ?>
  <div id="style_settings_<?php echo $section_id; ?>_colors">
    <h2>Colors</h2>
    <?php foreach($settings['colors'] as $group_key => $group_values): ?>
    <?php if ($group_key != 'custom'): ?>
    <fieldset id="row_colors_group_<?php echo $group_key; ?>" class="tb_color_row">
      <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
      <div class="tb_wrap">
        <?php foreach ($group_values as $section_key => $section_values): ?>
        <?php echo $tbData->fetchTemplate('theme_colors_item', array('cols_num' => 4, 'input_property' => "{$input_property}[colors][$group_key][$section_key]", 'section_id' => $section_id, 'color_item' => $section_values)); ?>
        <?php endforeach; ?>
      </div>
    </fieldset>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <?php // Typography ?>
  <div id="style_settings_<?php echo $section_id; ?>_typography">

    <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

      <h2>Typography</h2>

      <?php if (count($languages) > 1): ?>
      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <?php foreach ($languages as $language): ?>
          <li class="s_language">
            <a href="#style_settings_<?php echo $section_id; ?>_typography_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
              <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <?php foreach ($languages as $language): ?>
      <?php $language_code = $language['code']; ?>
      <div id="style_settings_<?php echo $section_id; ?>_typography_language_<?php echo $language_code; ?>">
        <?php foreach ($settings['font'][$language_code] as $name => $font_section): ?>
        <fieldset id="row_typography_group_<?php echo $name; ?>_<?php echo $language_code; ?>" class="tb_font_row">
          <legend><?php echo $font_section['section_name']; ?></legend>
          <?php echo $tbData->fetchTemplate('theme_design_typography_item', array('input_property' => $input_property . "[font][$language_code][$name]", 'font' => $font_section, 'font_data' => $font_data)); ?>
        </fieldset>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>

    </div>

  </div>

</div>