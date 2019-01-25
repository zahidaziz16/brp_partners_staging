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
          <label>Style</label>
          <span class="s_select">
            <select name="widget_data[style]">
              <option value="1"<?php if ($settings['style'] == '1'): ?> selected="selected"<?php endif; ?>>Compact</option>
              <option value="4"<?php if ($settings['style'] == '4'): ?> selected="selected"<?php endif; ?>>Compact (visible on hover)</option>
              <option value="2"<?php if ($settings['style'] == '2'): ?> selected="selected"<?php endif; ?>>Icon button</option>
              <option value="3"<?php if ($settings['style'] == '3'): ?> selected="selected"<?php endif; ?>>Text button</option>
            </select>
          </span>
        </div>

        <div class="s_row_1 tbIconRow">
          <label>Icon</label>
          <div class="tbIcon s_h_26<?php if (!$settings['search_icon']): ?> s_icon_holder<?php endif; ?>">
            <?php if ($settings['search_icon']): ?>
              <span class="glyph_symbol <?php echo $settings['search_icon']; ?>"></span>
            <?php endif; ?>
          </div>
          <?php if (!$settings['search_icon']): ?>
          <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
          <?php else: ?>
          <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
          <?php endif; ?>
          <input type="hidden" name="widget_data[search_icon]" value="<?php echo $settings['search_icon']; ?>" />
          <input class="s_spinner s_ml_10" type="text" name="widget_data[icon_size]" value="<?php echo $settings['icon_size']; ?>" min="10" size="7" />
          <span class="s_metric">%</span>
          <div class="s_row_1 s_mt_0 s_ml_40 inline-block tbIconHoverSizeOption">
            <label class="inline-block nofloat">Hover size</label>
            <input class="s_spinner s_ml_10" type="text" name="widget_data[icon_hover_size]" value="<?php echo $settings['icon_hover_size']; ?>" min="10" size="7" />
            <span class="s_metric">%</span>
          </div>
        </div>

        <div class="s_row_1">
          <label>Size</label>
          <span class="s_select">
            <select name="widget_data[size]">
              <option value="sm"<?php if ($settings['size'] == 'sm'): ?> selected="selected"<?php endif; ?>>Small</option>
              <option value="md"<?php if ($settings['size'] == 'md'): ?> selected="selected"<?php endif; ?>>Default</option>
              <option value="lg"<?php if ($settings['size'] == 'lg'): ?> selected="selected"<?php endif; ?>>Large</option>
            </select>
          </span>
        </div>

        <div class="s_row_1">
          <label>Width</label>
          <input class="s_spinner" type="text" name="widget_data[width]" value="<?php echo $settings['width']; ?>" size="7" />
          <span class="s_metric">
            <select name="widget_data[width_metric]">
              <option value="px"<?php if ($settings['width_metric'] == 'px'): ?> selected="selected"<?php endif; ?>>px</option>
              <option value="%"<?php  if ($settings['width_metric'] == '%'):  ?> selected="selected"<?php endif; ?>>%</option>
            </select>
          </span>
          <p class="s_help right">0 for no auto width.</p>
        </div>

        <div class="s_row_1">
          <label>Max Width</label>
          <input class="s_spinner" type="text" name="widget_data[max_width]" value="<?php echo $settings['max_width']; ?>" size="7" />
          <span class="s_metric">
            <select name="widget_data[max_width_metric]">
              <option value="px"<?php if ($settings['max_width_metric'] == 'px'): ?> selected="selected"<?php endif; ?>>px</option>
              <option value="%"<?php  if ($settings['max_width_metric'] == '%'):  ?> selected="selected"<?php endif; ?>>%</option>
            </select>
          </span>
          <p class="s_help right">0 for no maximum width.</p>
        </div>

        <div class="s_row_1">
          <label>Min Width</label>
          <input class="s_spinner" type="text" name="widget_data[min_width]" value="<?php echo $settings['min_width']; ?>" size="7" />
          <span class="s_metric">
            <select name="widget_data[min_width_metric]">
              <option value="px"<?php if ($settings['min_width_metric'] == 'px'): ?> selected="selected"<?php endif; ?>>px</option>
              <option value="%"<?php  if ($settings['min_width_metric'] == '%'):  ?> selected="selected"<?php endif; ?>>%</option>
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

<script type="text/javascript">
$(document).ready(function() {

  $("#widget_settings_holder").find(".tbLanguageTabs").first().tabs();

  var widgetIconListReplace = function($newIcon, $activeRow) {
    $activeRow
      .find(".tbIcon").removeClass("s_icon_holder s_h_26").empty().append($newIcon).end()
      .find('input[name*="search_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
      .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
  }

  $("#widget_settings_holder").on("click", ".tbChooseIcon", function() {
    if ($(this).hasClass("tbRemoveIcon")) {
      $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
        .parents(".tbIconRow").first()
        .find('input[name*="search_icon"]:hidden').val("").end()
        .find(".tbIcon").addClass("s_icon_holder s_h_26").empty();
    } else {
      tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
    }

    return false;
  });

  $("#widget_settings_holder").find('[name="widget_data[style]"]').bind('change', function() {
    $('.tbIconHoverSizeOption').toggle($(this).val() == 4);
  }).trigger('change');



});
</script>