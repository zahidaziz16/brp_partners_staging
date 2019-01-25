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

        <h2>Edit <?php echo $settings['widget_name']; ?></h2>

        <fieldset>
          <legend>Menu spacing</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_6">
              <label>Height</label>
              <input class="s_spinner" type="text" name="widget_data[menu_height]" min="0" size="7" value="<?php echo $settings['menu_height']; ?>" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_6">
              <label>Inner padding</label>
              <input class="s_spinner" type="text" name="widget_data[menu_padding]" min="0" size="7" value="<?php echo $settings['menu_padding']; ?>" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Menu style</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_6">
              <label>Show icon</label>
              <input type="hidden" name="widget_data[show_icon]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[show_icon]" value="1"<?php if($settings['show_icon'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <div class="s_row_2 tb_col tb_1_6">
              <label>Show label</label>
              <input type="hidden" name="widget_data[show_label]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[show_label]" value="1"<?php if($settings['show_label'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <div class="s_row_2 tb_col tb_1_6">
              <label>Show items</label>
              <input type="hidden" name="widget_data[show_items]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[show_items]" value="1"<?php if($settings['show_items'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <div class="s_row_2 tb_col tb_1_6">
              <label>Show total</label>
              <input type="hidden" name="widget_data[show_total]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[show_total]" value="1"<?php if($settings['show_total'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <div class="s_row_2 tb_col tb_1_3 tbSeparatorSymbolWrap tbIconRow">
              <label>Icon</label>
              <div class="tbIcon s_h_26<?php if (!$settings['cart_icon']): ?> s_icon_holder<?php endif; ?>">
                <?php if ($settings['cart_icon']): ?>
                <span class="glyph_symbol <?php echo $settings['cart_icon']; ?>"></span>
                <?php endif; ?>
              </div>
              <?php if (!$settings['cart_icon']): ?>
              <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
              <?php else: ?>
              <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
              <?php endif; ?>
              <input type="hidden" name="widget_data[cart_icon]" value="<?php echo $settings['cart_icon']; ?>" />
              <input class="s_spinner s_ml_10" type="text" name="widget_data[icon_size]" value="<?php echo $settings['icon_size']; ?>" min="10" size="7" />
              <span class="s_metric">%</span>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Sticky header</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_6 tbCartMenuStickyStyle">
              <label>Menu style</label>
              <div class="s_select">
                <select name="widget_data[sticky_style]">
                  <option value="default"<?php if($settings['sticky_style'] == 'default'): ?> selected="selected"<?php endif; ?>>Default</option>
                  <option value="compact"<?php if($settings['sticky_style'] == 'compact'): ?> selected="selected"<?php endif; ?>>Compact</option>
                </select>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_6 tbCartMenuStickySize">
              <label>Button size</label>
              <div class="s_select">
                <select name="widget_data[sticky_size]">
                  <option value="sm"<?php if($settings['sticky_size'] == 'sm'): ?> selected="selected"<?php endif; ?>>Small</option>
                  <option value="md"<?php if($settings['sticky_size'] == 'md'): ?> selected="selected"<?php endif; ?>>Default</option>
                  <option value="lg"<?php if($settings['sticky_size'] == 'lg'): ?> selected="selected"<?php endif; ?>>Large</option>
                </select>
              </div>
            </div>
          </div>
        </fieldset>

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
            .find('input[name*="cart_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
            .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
  }

  $("#widget_settings_holder").on("click", ".tbChooseIcon", function() {
    if ($(this).hasClass("tbRemoveIcon")) {
      $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
              .parents(".tbIconRow").first()
              .find('input[name*="cart_icon"]:hidden').val("").end()
              .find(".tbIcon").addClass("s_icon_holder s_h_26").empty();
    } else {
      tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
    }

    return false;
  });

});
</script>