<div class="tb_tabs clearfix">

  <div class="tb_tabs_nav s_box_1 s_has_combobox">
    <div class="s_actions tbAreaActions">
      <div class="s_buttons_group">
        <a class="s_button s_white s_h_20 s_icon_10 s_cog_10 tbAreaLoadPresetDialog" href="javascript:;"></a>
      </div>
      <div class="s_submenu tbPresetMenu">
        <div class="s_row_2">
          <label class="s_mb_10"><strong>Choose preset</strong></label>
          <div class="s_full s_mb_15">
            <div class="s_select">
              <select name="">
                <?php foreach ($preset_options as $preset_id => $preset_name): ?>
                <option value="<?php echo $preset_id; ?>"><?php echo $preset_name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <a class="s_button s_white s_h_30 tbAreaLoadPreset" href="javascript:;">Load</a>
        </div>
      </div>
    </div>
    <h3>Styles</h3>
    <ul class="tb_nav clearfix">
      <li><a href="#style_settings_<?php echo $group; ?>_<?php echo $section; ?>_layout"     data-section="layout">Layout</a></li>
      <li><a href="#style_settings_<?php echo $group; ?>_<?php echo $section; ?>_box_shadow" data-section="box_shadow">Box Shadow</a></li>
      <li><a href="#style_settings_<?php echo $group; ?>_<?php echo $section; ?>_background" data-section="background">Background</a></li>
      <li><a href="#style_settings_<?php echo $group; ?>_<?php echo $section; ?>_border"     data-section="border">Border</a></li>
      <li><a href="#style_settings_<?php echo $group; ?>_<?php echo $section; ?>_colors"     data-section="colors">Colors</a></li>
      <li><a href="#style_settings_<?php echo $group; ?>_<?php echo $section; ?>_typography" data-section="typography">Typography</a></li>
    </ul>

    <div class="tbComboBoxRow">
      <select id="area_type_select" class="tb_nostyle tbComboBox">
        <option key="global" value="global">GLOBAL</option>
        <option key="home" value="home">Home</option>
        <option key="category" value="category_global">All categories</option>
        <?php foreach ($category_levels as $option): ?>
        <option key="category" value="<?php echo $option['value']; ?>"><?php echo $option['label']; ?></option>
        <?php endforeach; ?>
        <option key="product" value="product_global">All products</option>
        <?php if ($layouts): ?>
        <option key="choose_layout" class="ui-combobox-nocomplete ui-combobox-noselect" value="layout">Layout</option>
        <?php endif; ?>
        <option class="ui-combobox-nocomplete ui-combobox-noselect ui-combobox-disabled tb_separator">separator</option>
        <?php if ($pages): ?>
        <option key="choose_page" class="ui-combobox-nocomplete ui-combobox-noselect" value="information_page">Information page</option>
        <?php endif; ?>
        <?php if ($store_has_categories): ?>
        <option key="choose_category" class="ui-combobox-nocomplete ui-combobox-noselect" value="category">Category</option>
        <?php endif; ?>
        <option key="choose_system" class="ui-combobox-nocomplete ui-combobox-noselect" value="system_page">System page</option>
        <option class="ui-combobox-nocomplete ui-combobox-noselect ui-combobox-disabled tb_separator">separator</option>
        <option key="modified" class="ui-combobox-nocomplete ui-combobox-noselect tbModified" value="modified">Modified</option>
      </select>
      <input type="hidden" name="area_type" value="<?php echo $area_type; ?>" />
      <input type="hidden" name="area_id" value="<?php echo $area_id; ?>" />

      <?php if ($pages): ?>
      <ul class="tbPageMenu" style="display: none;">
        <?php foreach ($pages as $page): ?>
        <li><a href="javascript:;" page_id="<?php echo $page['information_id']; ?>"><?php echo $page['title']; ?></a></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <?php if ($layouts): ?>
      <ul class="tbLayoutMenu" style="display: none;">
        <?php foreach ($layouts as $layout): ?>
        <li><a href="javascript:;" layout_id="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></a></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <?php echo $tbData->fetchTemplate('theme_builder_modified', array('modified' => $modified)); ?>

    </div>

    <div class="tb_style_preview_wrap">
      <h4>Preview</h4>
      <div class="tb_style_preview">
        <div id="<?php echo $group; ?>_<?php echo $section; ?>_style_preview" class="tb_style_preview_box"></div>
      </div>
    </div>
  </div>

  <input type="hidden" class="tbAreaSettingsKey" name="<?php echo "{$group}_{$section}"; ?>_key" value="<?php echo $area_key; ?>" />

  <div id="style_settings_<?php echo $group; ?>_<?php echo $section; ?>_layout">

    <?php if (!empty($inherit_msg)): ?>
    <div class="s_server_msg s_msg_blue tbRecordInfoMessage1">
      <p class="s_icon_16 s_info_16">
        There are no saved settings for <strong class="tbPageDescription">GLOBAL</strong>. <?php echo $inherit_msg; ?> are applied instead.
      </p>
    </div>
    <?php endif; ?>

    <?php if (!empty($override_msg)): ?>
    <div class="s_server_msg s_msg_yellow tbRecordInfoMessage2"<?php if (empty($display_override_msg)): ?> style="display: none;"<?php endif; ?>>
      <p class="s_icon_16 s_exclamation_16">
        The blocks for <strong class="tbOverrideMsg"><?php echo $override_msg; ?></strong> will be displayed on the site as they have higher priority than <strong class="tbPageDescription"></strong>.
      </p>
    </div>
    <?php endif; ?>

    <h2>Layout</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_layout', array('section' => $section, 'input_property' => "{$group}[{$section}]", 'layout' => $settings['layout'])); ?>
  </div>

  <div id="style_settings_<?php echo $group; ?>_<?php echo $section; ?>_box_shadow">
    <h2>Shadow</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_box_shadows', array('section' => $section, 'input_property' => "{$group}[{$section}]", 'box_shadow' => $settings['box_shadow'])); ?>
  </div>

  <div id="style_settings_<?php echo $group; ?>_<?php echo $section; ?>_background">
    <h2>Background</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_background', array('section' => $section, 'input_property' => "{$group}[{$section}]", 'background' => $settings['background'])); ?>
  </div>

  <div id="style_settings_<?php echo $group; ?>_<?php echo $section; ?>_border">
    <h2>Border</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_border', array('section' => $section, 'input_property' => "{$group}[{$section}]", 'border' => $settings['border'], 'border_radius' => $settings['border_radius'])); ?>
  </div>

  <?php // Colors ?>
  <div id="style_settings_<?php echo $group; ?>_<?php echo $section; ?>_colors">
    <h2>Colors</h2>
    <?php foreach($settings['colors'] as $group_key => $group_values): ?>
    <?php if ($group_key != 'custom'): ?>
    <fieldset id="colors_group_<?php echo $group; ?>_<?php echo $section; ?>_<?php echo $group_key; ?>" class="tb_color_row">
      <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
      <div class="tb_wrap">
        <?php foreach ($group_values as $section_key => $color_item): ?>
        <?php $color_item['id'] = str_replace('area_', 'area_' . $area_name . '_', $color_item['id']); ?>
        <?php $color_item['parent_id'] = str_replace('area_', 'area_' . $area_name . '_', $color_item['parent_id']); ?>
        <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "{$group}[{$section}][colors][$group_key][$section_key]", 'color_item' => $color_item, 'cols_num' => 4)); ?>
        <?php endforeach; ?>
      </div>
    </fieldset>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <?php // Typography ?>
  <div id="style_settings_<?php echo $group; ?>_<?php echo $section; ?>_typography">

    <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

      <h2>Typography</h2>

      <?php if (count($languages) > 1): ?>
      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <?php foreach ($languages as $language): ?>
          <li class="s_language">
            <a href="#style_settings_<?php echo $group; ?>_<?php echo $section; ?>_typography_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
              <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <?php foreach ($languages as $language): ?>
      <?php $language_code = $language['code']; ?>
      <div id="style_settings_<?php echo $group; ?>_<?php echo $section; ?>_typography_language_<?php echo $language_code; ?>">
        <?php foreach ($settings['font'][$language_code] as $name => $font_section): ?>
        <fieldset class="tb_font_row">
          <legend><?php echo $font_section['section_name']; ?></legend>
          <?php echo $tbData->fetchTemplate('theme_design_typography_item', array('input_property' => "{$group}[{$section}][font][$language_code][$name]", 'font' => $font_section, 'font_data' => $font_data)); ?>
        </fieldset>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>

    </div>

  </div>

</div>

<div class="s_submit clearfix">
  <a class="s_button s_red s_h_40 tbSaveAreaSettings">Save <?php echo ucfirst($area_name); ?> Settings</a>
</div>