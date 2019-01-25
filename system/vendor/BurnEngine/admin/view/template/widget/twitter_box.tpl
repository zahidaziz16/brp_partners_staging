<div id="widget_twitter_box" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Latest Tweets</span></h1>

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

          <h2>Edit Latest Tweets</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#widget_twitter_box_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="widget_twitter_box_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?> tbLanguagePanel">
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
              <label><strong>Follow button text</strong></label>
              <?php $button_lang = $settings['lang'][$language_code]['follow_button_lang']; ?>
              <div class="s_select">
                <select name="widget_data[lang][<?php echo $language_code; ?>][follow_button_lang]">
                  <option value="ja"<?php if($button_lang == 'ja') echo ' selected="selected"'; ?>>Japanese - 日本語</option>
                  <option value="pt"<?php if($button_lang == 'pt') echo ' selected="selected"'; ?>>Portuguese - Português</option>
                  <option value="da"<?php if($button_lang == 'da') echo ' selected="selected"'; ?>>Danish - Dansk</option>
                  <option value="sv"<?php if($button_lang == 'sv') echo ' selected="selected"'; ?>>Swedish - Svenska</option>
                  <option value="uk"<?php if($button_lang == 'uk') echo ' selected="selected"'; ?>>Ukrainian - Українська мова</option>
                  <option value="it"<?php if($button_lang == 'it') echo ' selected="selected"'; ?>>Italian - Italiano</option>
                  <option value="es"<?php if($button_lang == 'es') echo ' selected="selected"'; ?>>Spanish - Español</option>
                  <option value="fr"<?php if($button_lang == 'fr') echo ' selected="selected"'; ?>>French - français</option>
                  <option value="tr"<?php if($button_lang == 'tr') echo ' selected="selected"'; ?>>Turkish - Türkçe</option>
                  <option value="hi"<?php if($button_lang == 'hi') echo ' selected="selected"'; ?>>Hindi - हिन्दी</option>
                  <option value="he"<?php if($button_lang == 'he') echo ' selected="selected"'; ?>>Hebrew - עִבְרִית</option>
                  <option value="id"<?php if($button_lang == 'id') echo ' selected="selected"'; ?>>Indonesian - Bahasa Indonesia</option>
                  <option value="th"<?php if($button_lang == 'th') echo ' selected="selected"'; ?>>Thai - ภาษาไทย</option>
                  <option value="ar"<?php if($button_lang == 'ar') echo ' selected="selected"'; ?>>Arabic - العربية</option>
                  <option value="en"<?php if($button_lang == 'en') echo ' selected="selected"'; ?>>English</option>
                  <option value="de"<?php if($button_lang == 'de') echo ' selected="selected"'; ?>>German - Deutsch</option>
                  <option value="pl"<?php if($button_lang == 'pl') echo ' selected="selected"'; ?>>Polish - Polski</option>
                  <option value="ca"<?php if($button_lang == 'ca') echo ' selected="selected"'; ?>>Catalan - català</option>
                  <option value="ko"<?php if($button_lang == 'ko') echo ' selected="selected"'; ?>>Korean - 한국어</option>
                  <option value="no"<?php if($button_lang == 'no') echo ' selected="selected"'; ?>>Norwegian - Norsk</option>
                  <option value="nl"<?php if($button_lang == 'nl') echo ' selected="selected"'; ?>>Dutch - Nederlands</option>
                  <option value="hu"<?php if($button_lang == 'hu') echo ' selected="selected"'; ?>>Hungarian - Magyar</option>
                  <option value="fa"<?php if($button_lang == 'fa') echo ' selected="selected"'; ?>>Farsi - فارسی</option>
                  <option value="ur"<?php if($button_lang == 'ur') echo ' selected="selected"'; ?>>Urdu - اردو</option>
                  <option value="ru"<?php if($button_lang == 'ru') echo ' selected="selected"'; ?>>Russian - Русский</option>
                  <option value="fi"<?php if($button_lang == 'fi') echo ' selected="selected"'; ?>>Finnish - Suomi</option>
                  <option value="msa"<?php if($button_lang == 'msa') echo ' selected="selected"'; ?>>Malay - Bahasa Melayu</option>
                  <option value="zh-tw"<?php if($button_lang == 'zh-tw') echo ' selected="selected"'; ?>>Traditional Chinese - 繁體中文</option>
                  <option value="zh-cn"<?php if($button_lang == 'zh-cn') echo ' selected="selected"'; ?>>Simplified Chinese - 简体中文</option>
                  <option value="fil"<?php if($button_lang == 'fil') echo ' selected="selected"'; ?>>Filipino - Filipino</option>
                </select>
              </div>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="s_row_1">
          <label><strong>Profiles type</strong></label>
          <div class="s_select">
            <select name="widget_data[profiles_type]">
              <option value="followers"<?php if($settings['profiles_type'] == 'followers') echo ' selected="selected"';?>>Followers</option>
              <option value="friends"<?php if($settings['profiles_type'] == 'friends')     echo ' selected="selected"';?>>Friends</option>
            </select>
          </div>
        </div>

        <div class="s_row_1">
          <label><strong>Show user profile</strong></label>
          <input type="hidden" name="widget_data[user_profile]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="widget_data[user_profile]" value="1"<?php if($settings['user_profile'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>

        <div class="s_row_1">
          <label><strong>Profiles number</strong></label>
          <input class="s_spinner" type="text" size="6" min="3" name="widget_data[profiles_num]" value="<?php echo $settings['profiles_num']; ?>" />
        </div>

        <div class="s_row_1">
          <label><strong>Profiles max rows</strong></label>
          <input class="s_spinner" type="text" size="6" min="1" name="widget_data[profiles_rows]" value="<?php echo $settings['profiles_rows']; ?>" />
        </div>

        <div class="s_row_1">
          <label><strong>Put border on profile</strong></label>
          <input type="hidden" name="widget_data[profile_border]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="widget_data[profile_border]" value="1"<?php if($settings['profile_border'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>

        <div class="s_row_1">
          <label><strong>Show profile name</strong></label>
          <input type="hidden" name="widget_data[profile_name]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="widget_data[profile_name]" value="1"<?php if($settings['profile_name'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
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

  $("#widget_settings_holder").find(".tbLanguageTabs").first().tabs();

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

});
</script>