<div id="system_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span><?php echo $widget->getName(); ?></span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <div class="tb_cp">
            <h2>Edit <?php echo $settings['widget_name']; ?></h2>
          </div>

        </div>

        <div class="s_row_1">
          <label>Menu Type</label>
          <span class="s_select">
            <select name="widget_data[menu_type]">
              <option value="dropdown"<?php if ($settings['menu_type'] == 'dropdown'): ?> selected="selected"<?php endif; ?>>Dropdown</option>
              <option value="inline"<?php   if ($settings['menu_type'] == 'inline'):   ?> selected="selected"<?php endif; ?>>Inline</option>
            </select>
          </span>
        </div>

        <div class="s_row_1">
          <label>Label Text</label>
          <span class="s_select">
            <select name="widget_data[label_text]">
              <option value="code"<?php   if ($settings['label_text'] == 'code'):   ?> selected="selected"<?php endif; ?>>Code</option>
              <option value="symbol"<?php if ($settings['label_text'] == 'symbol'): ?> selected="selected"<?php endif; ?>>Symbol</option>
              <option value="title"<?php  if ($settings['label_text'] == 'title'):  ?> selected="selected"<?php endif; ?>>Title</option>
            </select>
          </span>
        </div>

      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <input type="hidden" name="widget_data[widget_name]" value="<?php echo $settings['widget_name']; ?>" />
        <input type="hidden" name="widget_data[slot_name]" value="<?php echo $settings['slot_name']; ?>" />
        <input type="hidden" name="widget_data[slot_prefix]" value="<?php echo $settings['slot_prefix']; ?>" />
        <?php $layout_display = true; ?>
        <?php require tb_modification(dirname(__FILE__) . '/_common_options.tpl'); ?>
      </div>

      <div id="widget_advanced_settings_holder" class="tb_subpanel">
        <?php require tb_modification(dirname(__FILE__) . '/_advanced.tpl'); ?>
      </div>

    </div>

    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tbWidgetUpdate" href="javascript:;">Update Settings</a>
    </div>

  </form>

</div>