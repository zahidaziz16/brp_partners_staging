<?php $style = $theme_settings['style']; ?>
<div class="tb_tabs clearfix">

  <div class="tb_tabs_nav s_box_1">
    <?php /* <h3><?php echo $menu_name; ?></h3> */ ?>
    <h3>Styles</h3>
    <ul class="tb_nav clearfix">
      <li><a href="#style_settings_<?php echo $section; ?>_layout" data-section="layout">Layout</a></li>
      <li><a href="#style_settings_<?php echo $section; ?>_box_shadow" data-section="box_shadow">Box Shadow</a></li>
      <li><a href="#style_settings_<?php echo $section; ?>_background" data-section="background">Background</a></li>
      <li><a href="#style_settings_<?php echo $section; ?>_border" data-section="border">Border</a></li>
      <?php if ($section == 'bottom'): ?>
      <li><a href="#style_settings_<?php echo $section; ?>_colors" data-section="colors">Colors</a></li>
      <?php endif; ?>
    </ul>

    <div class="tb_style_preview_wrap">
      <h4>Preview</h4>
      <div class="tb_style_preview">
        <div id="<?php echo $section; ?>_style_preview" class="tb_style_preview_box"></div>
      </div>
    </div>
  </div>

  <div id="style_settings_<?php echo $section; ?>_layout">

    <?php if (!empty($inherit_msg)): ?>
    <div class="s_server_msg s_msg_blue tbRecordInfoMessage1">
      <p class="s_icon_16 s_info_16">
        There are no saved settings for <strong class="tbPageDescription">GLOBAL</strong>. <?php echo $inherit_msg; ?> are applied instead.
      </p>
    </div>
    <?php endif; ?>

    <h2><?php echo $section; ?> Layout</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_layout', array('section' => $section, 'input_property' => "style[$section]", 'layout' => $settings['layout'])); ?>
  </div>

  <div id="style_settings_<?php echo $section; ?>_box_shadow">
    <h2><?php echo $section; ?> Shadow</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_box_shadows', array('section' => $section, 'input_property' => "style[$section]", 'box_shadow' => $settings['box_shadow'])); ?>
  </div>

  <div id="style_settings_<?php echo $section; ?>_background">
    <h2><?php echo $section; ?> Background</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_background', array('section' => $section, 'input_property' => "style[$section]", 'background' => $settings['background'])); ?>
  </div>

  <div id="style_settings_<?php echo $section; ?>_border">
    <h2><?php echo $section; ?> Border</h2>
    <?php echo $tbData->fetchTemplate('theme_style_section_border', array('section' => $section, 'input_property' => "style[$section]", 'border' => $settings['border'], 'border_radius' => $settings['border_radius'])); ?>
  </div>

  <?php if ($section == 'bottom'): ?>
  <div id="style_settings_<?php echo $section; ?>_colors">
    <h2><?php echo $section; ?> Colors</h2>
    <?php foreach($colors as $group_key => $group_values): ?>
    <?php if (in_array($group_key, array('bottom'))): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <?php array_remove_key($group_values, '_label'); ?>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
          <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</div>

<div class="s_submit clearfix">
  <a class="s_button s_red s_h_40 tb_cp_form_submit"><?php echo $text_button_save_settings; ?></a>
</div>
