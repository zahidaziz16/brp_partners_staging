<?php
  if (!isset($available_options))  $available_options = array('layout', 'box_shadow', 'background', 'border', 'colors', 'typography');
  if (isset($custom_widget_title)) $settings['widget_admin_title'] = $custom_widget_title;
  if (!isset($style_section_id))   $style_section_id = 'box';

  $input_property = 'widget_data[' . $style_section_id . '_styles]';
  $options = $settings[$style_section_id . '_styles'];

  if (empty($options['box_shadow'])) {
    unset($available_options[array_search('box_shadow', $available_options)]);
  }
?>

<div class="tb_tabs tbWidgetCommonOptionsTabs">

  <div class="tb_tabs_nav s_box_1">
    <h3>Styles</h3>
    <ul class="tb_nav clearfix" data-style_id="<?php echo $style_section_id; ?>" data-input_property="<?php echo $input_property; ?>">
      <?php if (in_array('layout',     $available_options)): ?><li><a href="#style_settings_widget_<?php echo $style_section_id; ?>_layout"     data-section="layout">Layout</a></li><?php endif; ?>
      <?php if (in_array('box_shadow', $available_options)): ?><li><a href="#style_settings_widget_<?php echo $style_section_id; ?>_box_shadow" data-section="box_shadow">Shadow</a></li><?php endif; ?>
      <?php if (in_array('background', $available_options)): ?><li><a href="#style_settings_widget_<?php echo $style_section_id; ?>_background" data-section="background">Background</a></li><?php endif; ?>
      <?php if (in_array('border',     $available_options)): ?><li><a href="#style_settings_widget_<?php echo $style_section_id; ?>_border"     data-section="border">Border</a></li><?php endif; ?>
      <?php if (in_array('colors',     $available_options)): ?><li><a href="#style_settings_widget_<?php echo $style_section_id; ?>_colors"     data-section="colors">Colors</a></li><?php endif; ?>
      <?php if (in_array('typography', $available_options)): ?><li><a href="#style_settings_widget_<?php echo $style_section_id; ?>_typography" data-section="typography">Typography</a></li><?php endif; ?>
    </ul>
    <?php if (in_array('box_shadow', $available_options) || in_array('background', $available_options) || in_array('border', $available_options)): ?>
    <div class="tb_style_preview_wrap">
      <h4>Preview</h4>
      <div class="tb_style_preview">
        <div id="widget_<?php echo $style_section_id; ?>_style_preview" class="tb_style_preview_box"></div>
      </div>
    </div>
    <?php endif; ?>
  </div>
  
  <?php // Layout settings ?>
  <?php if (in_array('layout', $available_options)): ?>
  <div id="style_settings_widget_<?php echo $style_section_id; ?>_layout">
    <h2><?php echo isset($style_section_name) ? $style_section_name : $style_section_id; ?> Layout</h2>

    <?php if (!empty($layout_display) && $style_section_id == 'box'): ?>
    <fieldset>
      <legend>Display</legend>
      <div class="s_row_1">
        <div class="s_select">
          <select id="widget_data_common_layout_display" name="<?php echo $input_property; ?>[layout][display]">
            <option value="block"<?php if($options['layout']['display'] == 'block') echo ' selected="selected"';?>> Block</option>
            <option value="inline-block"<?php if($options['layout']['display'] == 'inline-block') echo ' selected="selected"';?>> Inline</option>
          </select>
        </div>
      </div>
    </fieldset>
    <?php endif; ?>

    <fieldset class="tbMarginFieldset">
      <legend>Margin</legend>
      <div class="tb_wrap">
        <div class="s_row_2 tb_col tb_1_5">
          <label>Top</label>
          <input class="s_spinner" type="text" min="-150" max="150" step="5" name="<?php echo $input_property; ?>[layout][margin_top]" value="<?php echo $options['layout']['margin_top']; ?>" size="6" />
          <span class="s_metric">px</span>
        </div>
        <div class="s_row_2 tb_col tb_1_5">
          <label>Bottom</label>
          <input class="s_spinner" type="text" min="-150" max="150" step="5" name="<?php echo $input_property; ?>[layout][margin_bottom]" value="<?php echo $options['layout']['margin_bottom']; ?>" size="6" />
          <span class="s_metric">px</span>
        </div>
        <div class="s_row_2 tb_col tb_1_5">
          <label>Left</label>
          <input class="s_spinner" type="text" min="-150" max="150" step="5" name="<?php echo $input_property; ?>[layout][margin_left]" value="<?php echo $options['layout']['margin_left']; ?>" size="6" />
          <span class="s_metric">px</span>
        </div>
        <div class="s_row_2 tb_col tb_1_5">
          <label>Right</label>
          <input class="s_spinner" type="text" min="-150" max="150" step="5" name="<?php echo $input_property; ?>[layout][margin_right]" value="<?php echo $options['layout']['margin_right']; ?>" size="6" />
          <span class="s_metric">px</span>
        </div>
        <?php if ($tbData->has_rtl): ?>
        <div class="s_row_2 tb_col tb_1_5 tbStyleMarginRTL">
          <label>Reverse for RTL languages</label>
          <input type="hidden" name="<?php echo $input_property; ?>[layout][margin_rtl_mode]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][margin_rtl_mode]" value="1"<?php if($options['layout']['margin_rtl_mode'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
        <?php endif; ?>
      </div>
    </fieldset>

    <fieldset class="tbPaddingFieldset">
      <legend>Padding</legend>
      <div class="tb_wrap">
        <div class="s_row_2 tb_col tb_1_5">
          <label>Top</label>
          <input class="s_spinner" type="text" min="0" max="100" step="5" name="<?php echo $input_property; ?>[layout][padding_top]" value="<?php echo $options['layout']['padding_top']; ?>" size="6" />
          <span class="s_metric">px</span>
        </div>
        <div class="s_row_2 tb_col tb_1_5">
          <label>Bottom</label>
          <input class="s_spinner" type="text" min="0" max="100" step="5" name="<?php echo $input_property; ?>[layout][padding_bottom]" value="<?php echo $options['layout']['padding_bottom']; ?>" size="6" />
          <span class="s_metric">px</span>
        </div>
        <div class="s_row_2 tb_col tb_1_5">
          <label>Left</label>
          <input class="s_spinner" type="text" min="0" max="100" step="5" name="<?php echo $input_property; ?>[layout][padding_left]" value="<?php echo $options['layout']['padding_left']; ?>" size="6" />
          <span class="s_metric">px</span>
        </div>
        <div class="s_row_2 tb_col tb_1_5">
          <label>Right</label>
          <input class="s_spinner" type="text" min="0" max="100" step="5" name="<?php echo $input_property; ?>[layout][padding_right]" value="<?php echo $options['layout']['padding_right']; ?>" size="6" />
          <span class="s_metric">px</span>
        </div>
        <?php if ($tbData->has_rtl): ?>
        <div class="s_row_2 tb_col tb_1_5 tbStylePaddingRTL">
          <label>Reverse for RTL languages</label>
          <input type="hidden" name="<?php echo $input_property; ?>[layout][padding_rtl_mode]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="<?php echo $input_property; ?>[layout][padding_rtl_mode]" value="1"<?php if($options['layout']['padding_rtl_mode'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
        <?php endif; ?>
      </div>
    </fieldset>

  </div>
  <?php endif; ?>

  <?php // Box Shadow ?>
  <?php if (in_array('box_shadow', $available_options)): ?>
  <div id="style_settings_widget_<?php echo $style_section_id; ?>_box_shadow">
    <h2><?php echo isset($style_section_name) ? $style_section_name : $style_section_id; ?> Shadow</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_box_shadows', array('section' => 'box_styles', 'input_property' => $input_property, 'box_shadow' => $options['box_shadow'])); ?>
  </div>
  <?php endif; ?>

  <?php // Background Color ?>
  <?php if (in_array('background', $available_options)): ?>
  <div id="style_settings_widget_<?php echo $style_section_id; ?>_background">
    <h2><?php echo isset($style_section_name) ? $style_section_name : $style_section_id; ?> Background</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_background', array('section' => 'box_styles', 'input_property' => $input_property, 'background' => $options['background'])); ?>
  </div>
  <?php endif; ?>

  <?php // Border ?>
  <?php if (in_array('border', $available_options)): ?>
  <div id="style_settings_widget_<?php echo $style_section_id; ?>_border">
    <h2><?php echo isset($style_section_name) ? $style_section_name : $style_section_id; ?> Border</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_border', array('section' => 'box_styles', 'input_property' => $input_property, 'border' => $options['border'], 'border_radius' => $options['border_radius'])); ?>
  </div>
  <?php endif; ?>

  <?php // Typography ?>
  <?php if (in_array('typography', $available_options)): ?>
  <div id="style_settings_widget_<?php echo $style_section_id; ?>_typography">
    
    <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

      <h2><?php echo isset($style_section_name) ? $style_section_name : $style_section_id; ?> Typography</h2>

      <?php if (count($languages) > 1): ?>
      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <?php foreach ($languages as $language): ?>
          <li class="s_language">
            <a href="#style_settings_widget_<?php echo $style_section_id; ?>_typography_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
              <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <?php foreach ($languages as $language): ?>
      <?php $language_code = $language['code']; ?>
      <div id="style_settings_widget_<?php echo $style_section_id; ?>_typography_language_<?php echo $language_code; ?>">
        <?php foreach ($options['font'][$language_code] as $name => $section): ?>
        <fieldset id="widget_<?php echo $style_section_id; ?>_typography_group_<?php echo $name; ?>_<?php echo $language_code; ?>" class="tb_font_row">
          <?php if (!empty($section['section_name'])): ?>
          <legend><?php echo $section['section_name']; ?></legend>
          <?php endif; ?>
          <?php echo $tbData->fetchTemplate('theme_design_typography_item', array('input_property' => "{$input_property}[font][$language_code][$name]", 'font' => $section, 'font_data' => $font_data)); ?>
        </fieldset>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>

    </div>

  </div>
  <?php endif; ?>

  <?php // Colors ?>
  <?php if (in_array('colors', $available_options)): ?>
  <div id="style_settings_widget_<?php echo $style_section_id; ?>_colors">
    <h2><?php echo isset($style_section_name) ? $style_section_name : $style_section_id; ?> Colors</h2>
    <?php foreach($options['colors'] as $group_key => $group_values): ?>
    <fieldset id="widget_<?php echo $style_section_id; ?>_colors_group_<?php echo $group_key; ?>" class="tb_color_row">
      <?php if ($label = array_remove_key($group_values, '_label')): ?>
      <legend><?php echo $label; ?></legend>
      <?php endif; ?>
      <div class="tb_wrap">
        <?php foreach ($group_values as $section_key => $section_values): ?>
        <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "{$input_property}[colors][$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 4)); ?>
        <?php endforeach; ?>
      </div>
    </fieldset>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
      
</div>