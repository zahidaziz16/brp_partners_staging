<div id="stories_tags_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Stories Tags</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_title_styles_holder">Title Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Edit Tags</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#stories_tags_widget_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="stories_tags_widget_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?> tbLanguagePanel">
            <textarea name="widget_data[lang][<?php echo $language_code; ?>][menu]" class="tbMenuData" style="display: none;"><?php echo json_encode($settings['lang'][$language_code]['menu']); ?></textarea>

            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the Tags block for the current language.</p>
            </div>

            <div class="s_row_1">
              <label><strong>Title</strong></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <div class="s_full">
                <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][title]" value="<?php echo $settings['lang'][$language_code]['title']; ?>" />
                <div class="s_text_align s_buttons_group">
                  <input id="text_title_align_left_<?php echo $language_code; ?>" class="tb_nostyle" type="radio" name="widget_data[lang][<?php echo $language_code; ?>][title_align]" value="left"<?php if ($settings['lang'][$language_code]['title_align'] == 'left') echo ' checked="checked"'; ?> />
                  <label class="s_button s_h_30 s_white s_icon_16 fa-align-left" for="text_title_align_left_<?php echo $language_code; ?>"></label>
                  <input id="text_title_align_center_<?php echo $language_code; ?>" class="tb_nostyle" type="radio" name="widget_data[lang][<?php echo $language_code; ?>][title_align]" value="center"<?php if ($settings['lang'][$language_code]['title_align'] == 'center') echo ' checked="checked"'; ?> />
                  <label class="s_button s_h_30 s_white s_icon_16 fa-align-center" for="text_title_align_center_<?php echo $language_code; ?>"></label>
                  <input id="text_title_align_right_<?php echo $language_code; ?>" class="tb_nostyle" type="radio" name="widget_data[lang][<?php echo $language_code; ?>][title_align]" value="right"<?php if ($settings['lang'][$language_code]['title_align'] == 'right') echo ' checked="checked"'; ?> />
                  <label class="s_button s_h_30 s_white s_icon_16 fa-align-right" for="text_title_align_right_<?php echo $language_code; ?>"></label>
                </div>
              </div>
            </div>

            <?php $row = $settings['lang'][$language_code]; ?>

            <div class="s_row_1 tbIconRow">
              <label><strong>Title Icon</strong></label>
              <div class="tbIcon s_h_30<?php if (!$row['title_icon']): ?> s_icon_holder<?php endif; ?>">
                <?php if ($row['title_icon']): ?>
                <span class="glyph_symbol <?php echo $row['title_icon']; ?>"></span>
                <?php endif; ?>
              </div>
              <?php if (!$row['title_icon']): ?>
              <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
              <?php else: ?>
              <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
              <?php endif; ?>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][title_icon]" value="<?php echo $row['title_icon']; ?>" />
              <input class="s_spinner s_ml_10" type="text" min="10" step="5" name="widget_data[lang][<?php echo $language_code; ?>][title_icon_size]" value="<?php echo $row['title_icon_size']; ?>" size="6" />
              <span class="s_metric">%</span>
              <span class="s_language_icon right"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
            </div>

            <span class="clear s_mb_20"></span>

            <div class="tb_wrap tb_gut_10 s_mb_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label><strong>Style</strong></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Level 1</label>
                <div class="s_select">
                  <select name="widget_data[level_1_style]">
                    <option value="h2"<?php if ($settings['level_1_style'] == 'h2'): ?> selected="selected"<?php endif; ?>>H2</option>
                    <option value="h3"<?php if ($settings['level_1_style'] == 'h3'): ?> selected="selected"<?php endif; ?>>H3</option>
                    <option value="h4"<?php if ($settings['level_1_style'] == 'h4'): ?> selected="selected"<?php endif; ?>>H4</option>
                    <option value="list"<?php if ($settings['level_1_style'] == 'list'): ?> selected="selected"<?php endif; ?>>List</option>
                  </select>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 s_ml_20">
                <label>Level 2</label>
                <div class="s_select">
                  <select name="widget_data[level_2_style]">
                    <option value="h2"<?php if ($settings['level_2_style'] == 'h2'): ?> selected="selected"<?php endif; ?>>H2</option>
                    <option value="h3"<?php if ($settings['level_2_style'] == 'h3'): ?> selected="selected"<?php endif; ?>>H3</option>
                    <option value="h4"<?php if ($settings['level_2_style'] == 'h4'): ?> selected="selected"<?php endif; ?>>H4</option>
                    <option value="list"<?php if ($settings['level_2_style'] == 'list'): ?> selected="selected"<?php endif; ?>>List</option>
                  </select>
                </div>
              </div>
            </div>

            <span class="clear s_mb_30 border_ddd"></span>

            <div class="tb_menu_listing clearfix">
              <ol class="left tbSortable"></ol>
              <div class="s_box_1 tb_tabs tb_tabs_inline s_white right">
                <h3>Tags list</h3>
                <div class="tb_add_menu_item tbMenuItemsTab">
                  <div class="s_row_2">
                    <label>Filter:</label>
                    <input type="text" class="tbItemFilter tb_1_1 s_mb_20" value="">
                  </div>
                  <ul class="tbItemsList">
                    <?php foreach ($settings['tags'][$language_code] as $tag): ?>
                    <li><a class="s_button_add_subwidgets s_button s_white s_h_20 s_icon_10 s_plus_10 left tbAddMenuItem" href="javascript:;" item_id="tag_<?php echo $tag['tag_id']; ?>"></a><span><?php echo $tag['name']; ?></span></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>

        </div>

      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <?php $layout_display = true; ?>
        <?php require tb_modification(dirname(__FILE__) . '/_common_options.tpl'); ?>
      </div>

      <div id="widget_title_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <?php $style_section_id = 'title'; ?>
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

  var $widgetForm = $("#stories_tags_widget_content");

  var widgetIconListReplace = function($newIcon, $activeRow) {
    $activeRow
            .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
            .find('input[name*="title_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
            .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
  };

  $("#widget_settings_holder").on("click", ".tbChooseIcon", function() {
    if ($(this).hasClass("tbRemoveIcon")) {
      $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
             .parents(".tbIconRow").first()
             .find('input[name*="title_icon"]:hidden').val("").end()
             .find(".tbIcon").addClass("s_icon_holder").empty();
    } else {
      tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
    }

    return false;
  });

  var menu = tbApp.initTags($widgetForm, {
    width:          600,
    margin_left:    -300,
    sticky_sidebar: false
  });

  var event_id = tbHelper.generateUniqueId(10);

  $widgetForm.find(".tbLanguageTabs").first().tabs();

  $(tbApp).one("tbWidget:onUpdate." + event_id, function(event, widget) {
    menu.prepareForSave();
    $(tbApp).unbind("tbWidget:onUpdate." + event_id);
  });

  $(tbApp).one("tbWidget:closeForm", function(event, widget) {
    $(tbApp).unbind("tbWidget:onUpdate." + event_id);
  });

});
</script>