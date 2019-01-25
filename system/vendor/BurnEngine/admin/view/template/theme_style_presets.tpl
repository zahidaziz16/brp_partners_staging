<div class="tb_tabs tb_tabs_inline">

  <div class="right tbComboBoxRow">
    <select class="tb_nostyle tbComboBox">
      <option class="ui-combobox-nocomplete ui-combobox-noselect" key="add_new" value="add_new">New</option>
      <option class="ui-combobox-nocomplete ui-combobox-noselect ui-combobox-disabled tb_separator">separator</option>
      <?php foreach ($preset_options as $option_value => $option): ?>
      <option <?php if (!$option['is_theme']): ?> class="ui-combobox-remove"<?php endif; ?><?php if ($option_value == $preset_id): ?> selected="selected"<?php endif; ?> key="<?php echo $option_value; ?>" value="<?php echo $option_value; ?>"><?php echo $option['name']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <?php if (!$settings) return; ?>

  <div class="tb_tabs_nav s_h_40 s_auto_cols">
    <ul class="clearfix">
      <li data-presets_section="box"><a href="#style_settings_presets_box_styles">Box Styles</a></li>
      <li data-presets_section="title"><a href="#style_settings_presets_title_styles">Title Styles</a></li>
    </ul>
  </div>

  <?php foreach (array('box', 'title') as $presets_section): ?>
  <?php $section_settings = $settings[$presets_section]; ?>
  <div id="style_settings_presets_<?php echo $presets_section; ?>_styles" class="tb_subpanel tb_has_sidebar">

    <div class="tb_tabs clearfix">

      <div class="tb_tabs_nav s_box_1">

        <h3>Styles</h3>
        <ul class="tb_nav clearfix">
          <li><a href="#style_settings_presets_<?php echo $presets_section; ?>_layout" data-section="layout">Layout</a></li>
          <li><a href="#style_settings_presets_<?php echo $presets_section; ?>_box_shadow" data-section="box_shadow">Box Shadow</a></li>
          <li><a href="#style_settings_presets_<?php echo $presets_section; ?>_background" data-section="background">Background</a></li>
          <li><a href="#style_settings_presets_<?php echo $presets_section; ?>_border" data-section="border">Border</a></li>
          <li><a href="#style_settings_presets_<?php echo $presets_section; ?>_colors" data-section="colors">Colors</a></li>
          <li><a href="#style_settings_presets_<?php echo $presets_section; ?>_typography" data-section="typography">Typography</a></li>
        </ul>

        <div class="tb_style_preview_wrap">
          <h4>Preview</h4>
          <div class="tb_style_preview">
            <div id="presets_<?php echo $presets_section; ?>_style_preview" class="tb_style_preview_box"></div>
          </div>
        </div>
      </div>

      <div id="style_settings_presets_<?php echo $presets_section; ?>_layout">
        <h2><?php echo $presets_section; ?> Layout</h2>
        <?php echo $tbData->fetchTemplate('theme_style_section_layout', array('section' => $presets_section, 'input_property' => "presets[$presets_section]", 'layout' => $section_settings['layout'])); ?>
      </div>

      <div id="style_settings_presets_<?php echo $presets_section; ?>_box_shadow">
        <h2><?php echo $presets_section; ?> Shadow</h2>
        <?php echo $tbData->fetchTemplate('theme_style_section_box_shadows', array('section' => $presets_section, 'input_property' => "presets[$presets_section]", 'box_shadow' => $section_settings['box_shadow'])); ?>
      </div>

      <div id="style_settings_presets_<?php echo $presets_section; ?>_background">
        <h2><?php echo $presets_section; ?> Background</h2>
        <?php echo $tbData->fetchTemplate('theme_style_section_background', array('section' => $presets_section, 'input_property' => "presets[$presets_section]", 'background' => $section_settings['background'])); ?>
      </div>

      <div id="style_settings_presets_<?php echo $presets_section; ?>_border">
        <h2><?php echo $presets_section; ?> Border</h2>
        <?php echo $tbData->fetchTemplate('theme_style_section_border', array('section' => $presets_section, 'input_property' => "presets[$presets_section]", 'border' => $section_settings['border'], 'border_radius' => $section_settings['border_radius'])); ?>
      </div>

      <?php // Colors ?>
      <div id="style_settings_presets_<?php echo $presets_section; ?>_colors">
        <h2>Colors</h2>
        <?php echo $tbData->fetchTemplate('theme_style_presets_colors', array('presets_section' => $presets_section, 'presets_section_colors' => $section_settings['colors'])); ?>

        <?php if ($presets_section == 'box'): ?>
        <div class="tbControls">
          <select class="inline s_mr_10 tbColorGroups">
            <option value="0">-- Select --</option>
            <?php foreach ($box_color_groups as $group_id => $group_title): ?>
            <option value="<?php echo $group_id; ?>"><?php echo $group_title; ?></option>
            <?php endforeach; ?>
          </select>
          <a href="javacript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddColorGroup">Add color group</a>
        </div>
        <?php endif; ?>
      </div>


      <?php // Typography ?>
      <div id="style_settings_presets_<?php echo $presets_section; ?>_typography">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Typography</h2>

          <?php if (count($languages) > 1): ?>
          <div class="tb_tabs_nav">
            <ul class="clearfix">
              <?php foreach ($languages as $language): ?>
              <li class="s_language">
                <a href="#style_settings_presets_<?php echo $presets_section; ?>_typography_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                  <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="style_settings_presets_<?php echo $presets_section; ?>_typography_language_<?php echo $language_code; ?>">
            <?php foreach ($section_settings['font'][$language_code] as $name => $font_section): ?>
            <?php
              echo $tbData->fetchTemplate('theme_style_presets_typography_item', array(
                'language_code'   => $language_code,
                'name'            => $name,
                'font_section'    => $font_section,
                'presets_section' => $presets_section,
                'font_data'       => $font_data,
                'group_id'        => $grouped_font_keys[$name]
              )); ?>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>

          <?php if ($presets_section == 'box'): ?>
          <div class="tbControls">
            <span class="clear s_mb_30"></span>
            <select class="inline s_mr_10 tbFontGroups">
              <option value="0">-- Select --</option>
              <?php foreach ($box_font_groups as $group_id => $group_title): ?>
              <option value="<?php echo $group_id; ?>"><?php echo $group_title; ?></option>
              <?php endforeach; ?>
            </select>
            <a href="javacript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddFontGroup">Add font group</a>
          </div>
          <?php endif; ?>

        </div>

      </div>

    </div>
  </div>
  <?php endforeach; ?>

</div>

<div class="s_submit clearfix">
  <a class="s_button s_red s_h_40 tbSavePreset">Save Preset</a>
</div>