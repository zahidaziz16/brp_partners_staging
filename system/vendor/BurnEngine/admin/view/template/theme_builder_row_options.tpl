<div class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Row</span></h1>

  <form action="<?php echo $tbUrl->generateJs('LayoutBuilder/convertRowFormDataToSettings'); ?>&section=<?php echo $section; ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbRowMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#row_settings_holder">Row</a></li>
          <?php foreach ($column_settings as $column_id => $column): ?>
          <li><a href="#column_<?php echo $column_id; ?>_settings_holder">Column <?php echo $column['order']; ?></a></li>
          <?php endforeach; ?>
          <li><a href="#row_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="row_settings_holder" class="tb_subpanel" data-section_id="widgets_row" data-input_property="widgets_row">
        <?php require(tb_modification(dirname(__FILE__) . '/theme_builder_row_options_tab.tpl')); ?>
      </div>

      <?php foreach ($column_settings as $column_id => $column): ?>
      <div id="column_<?php echo $column_id; ?>_settings_holder" class="tb_subpanel" data-section_id="row_column_<?php echo $column_id; ?>" data-input_property="widgets_row[columns][<?php echo $column_id; ?>]">
        <?php echo $column['html']; ?>
      </div>
      <?php endforeach; ?>

      <div id="row_advanced_settings_holder" class="tb_subpanel" data-section_id="widgets_row" data-input_property="widgets_row">
        <h2>Advanced</h2>
        <fieldset>
          <legend>Style preset</legend>
          <div class="s_row_1">
            <div class="s_select inline s_mr_10">
              <textarea style="display: none;" class="tbPresetBoxColorKeys"><?php echo json_encode($preset_box_color_keys); ?></textarea>
              <textarea style="display: none;" class="tbPresetBoxFontKeys"><?php echo json_encode($preset_box_font_keys); ?></textarea>
              <input type="hidden" name="widgets_row[preset_id]" value="<?php echo $row_preset_id; ?>" />
              <select name="preset_id">
                <option value="0">- Select -</option>
                <?php foreach ($preset_options as $option_key => $option_value): ?>
                <option value="<?php echo $option_key; ?>" <?php if ($selected_preset_id == $option_key): ?> selected="selected" <?php endif ?>><?php echo $option_value; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <a class="s_button s_white s_h_28 s_mr_5 tbLoadPreset">Load</a>
            <a class="s_button s_white s_h_28 tbApplyPreset">Apply</a>
          </div>
        </fieldset>
        <?php if ($column_settings > 1): ?>
        <fieldset>
          <legend>Sticky columns</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Choose</label>
              <div class="s_select">
                <select name="<?php echo $input_property; ?>[layout][sticky_columns]">
                  <option value="none"<?php if ($settings['layout']['sticky_columns'] == 'none') echo ' selected="selected"'; ?>>None</option>
                  <option value="all"<?php if ($settings['layout']['sticky_columns'] == 'all') echo ' selected="selected"'; ?>>All</option>
                  <option value="custom"<?php if ($settings['layout']['sticky_columns'] == 'custom') echo ' selected="selected"'; ?>>Custom</option>
                </select>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Offset top</label>
              <input type="text" name="<?php echo $input_property; ?>[layout][sticky_offset]" value="<?php echo $settings['layout']['sticky_offset']; ?>" size="5" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>
        <?php endif; ?>
        <fieldset class="tb_tabs tb_fly_tabs tbExtraClassTabs">
          <legend>Extra class</legend>
          <div class="tb_tabs_nav s_actions">
            <ul class="clearfix">
              <li><a href="#extra_class_tab_row">Row</a></li>
              <?php foreach ($column_settings as $column_id => $column): ?>
              <li><a href="#extra_class_tab_column_<?php echo $column['order']; ?>">Col <?php echo $column['order']; ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div id="extra_class_tab_row" class="s_row_2">
            <div class="s_full">
              <input type="text" name="<?php echo $input_property; ?>[layout][extra_class]" value="<?php echo $settings['layout']['extra_class']; ?>" />
            </div>
            <p class="s_help clear s_mb_0">Separate multiple classes with space.</p>
          </div>
          <?php foreach ($column_settings as $column_id => $column): ?>
          <div id="extra_class_tab_column_<?php echo $column['order']; ?>" class="s_row_2">
            <div class="s_full">
              <input type="text" name="widgets_row[columns][<?php echo $column_id; ?>][layout][extra_class]" value="<?php echo $column_settings[$column_id]['layout']['extra_class']; ?>" />
            </div>
            <p class="s_help clear s_mb_0">Separate multiple classes with space.</p>
          </div>
          <?php endforeach; ?>
        </fieldset>
      </div>

    </div>

    <div class="s_submit clearfix">
      <a class="update_settings s_button s_red s_h_40 tb_cp_form_submit" href="javascript:;">Update row settings</a>
    </div>

  </form>

</div>