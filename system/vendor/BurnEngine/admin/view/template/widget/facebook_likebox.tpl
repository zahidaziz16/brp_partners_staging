<div id="<?php echo $settings['widget_admin_prefix']; ?>_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span><?php echo $settings['widget_admin_title']; ?></span></h1>

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

          <h2>Edit <?php echo $settings['widget_admin_title']; ?></h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#<?php echo $settings['widget_admin_prefix']; ?>_widget_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="<?php echo $settings['widget_admin_prefix']; ?>_widget_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>">

            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the <?php echo $settings['widget_admin_title']; ?> content block for the current language.</p>
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

            <div class="s_row_1">
              <label><strong>Page url</strong></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <div class="s_full">
                <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][page_url]" value="<?php echo $settings['lang'][$language_code]['page_url']; ?>" />
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="s_row_1">
          <label><strong>Likebox style</strong></label>
          <div class="s_select">
            <select name="widget_data[like_box_style]">
              <option value="default"<?php if($settings['like_box_style'] == 'default') echo ' selected="selected"';?>>Default</option>
              <option value="custom"<?php if($settings['like_box_style'] == 'custom')   echo ' selected="selected"';?>><?php echo $tbEngine->getThemeInfo('name'); ?></option>
            </select>
          </div>
        </div>
        <div class="s_row_1 tbOptCustom">
          <label><strong>Like button</strong></label>
          <div class="s_select">
            <select name="widget_data[like_button_style]">
              <option value="button"<?php if($settings['like_button_style'] == 'button')             echo ' selected="selected"';?>>Standard</option>
              <option value="button_count"<?php if($settings['like_button_style'] == 'button_count') echo ' selected="selected"';?>>Button count</option>
            </select>
          </div>
        </div>
        
        <?php /*
        <div class="s_row_1">
          <label><strong>Show user profile</strong></label>
          <input type="hidden" name="widget_data[user_profile]" value="0" />
          <input class="tb_toggle" type="checkbox" name="widget_data[user_profile]" value="1"<?php if($settings['user_profile'] == '1') echo ' checked="checked"';?> />
        </div>
        */ ?>

        <div class="s_row_1 tbOptCustom">
          <label><strong>Profiles number</strong></label>
          <input class="s_spinner" type="text" size="6" min="3" max="13" name="widget_data[profiles_num]" value="<?php echo $settings['profiles_num']; ?>" />
        </div>

        <div class="s_row_1 tbOptCustom">
          <label><strong>Profiles max rows</strong></label>
          <input class="s_spinner" type="text" size="6" min="1" name="widget_data[profiles_rows]" value="<?php echo $settings['profiles_rows']; ?>" />
        </div>

        <div class="s_row_1 tbOptCustom">
          <label><strong>Put border on profile</strong></label>
          <input type="hidden" name="widget_data[profile_border]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="widget_data[profile_border]" value="1"<?php if($settings['profile_border'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>

        <div class="s_row_1 tbOptCustom">
          <label><strong>Show profile name</strong></label>
          <input type="hidden" name="widget_data[profile_name]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="widget_data[profile_name]" value="1"<?php if($settings['profile_name'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>


        <div class="s_row_1 tbOptDefault">
          <label><strong>Small header</strong></label>
          <input type="hidden" name="widget_data[default_small_header]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="widget_data[default_small_header]" value="1"<?php if($settings['default_small_header'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>

        <div class="s_row_1 tbOptDefault">
          <label><strong>Hide cover</strong></label>
          <input type="hidden" name="widget_data[default_hide_cover]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="widget_data[default_hide_cover]" value="1"<?php if($settings['default_hide_cover'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
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

  var $container = $("#widget_settings_holder")

  $container.find(".tbLanguageTabs").first().tabs();

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

  $container.find('select[name$="[like_box_style]"]').bind("change", function() {
      $container.find("div.tbOptCustom").toggle($(this).val() != 'default');
      $container.find("div.tbOptDefault").toggle($(this).val() == 'default');
  }).trigger("change");

});
</script>
