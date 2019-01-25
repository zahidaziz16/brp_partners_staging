<div id="call_to_action_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Call To Action</span></h1>

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
          
          <h2>Edit Call To Action</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#call_to_action_widget_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <?php $row = $settings['lang'][$language_code]; ?>
          <div id="call_to_action_widget_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>">
            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($row['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the Information content block for the current language.</p>
            </div>
            <fieldset>
              <legend>Button</legend>
              <a class="tb_block_help">
                <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              </a>
              <div class="s_row_1">
                <label>Url</label>
                <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][button_url]" value="<?php echo $row['button_url']; ?>" />
                <span class="s_metric">
                  <select id="widget_call_to_action_url_target" name="widget_data[lang][<?php echo $language_code; ?>][url_target]">
                    <option value="_self"<?php if($row['url_target'] == '_self') echo ' selected="selected"';?>>_self</option>
                    <option value="_blank"<?php if($row['url_target'] == '_blank') echo ' selected="selected"';?>>_blank</option>
                  </select>
                </span>
              </div>
              <div class="s_row_1">
                <label>Position</label>
                <div class="s_select">
                  <select id="widget_call_to_action_button_position" name="widget_data[lang][<?php echo $language_code; ?>][button_position]">
                    <option value="left"<?php if($row['button_position'] == 'left') echo ' selected="selected"';?>>Left</option>
                    <option value="right"<?php if($row['button_position'] == 'right') echo ' selected="selected"';?>>Right</option>
                    <option value="bottom"<?php if($row['button_position'] == 'bottom') echo ' selected="selected"';?>>Bottom</option>
                  </select>
                </div>
              </div>
              <div class="s_row_1">
                <label>Size</label>
                <div class="s_select">
                  <select id="widget_call_to_action_button_size" name="widget_data[lang][<?php echo $language_code; ?>][button_size]">
                    <option value="md"<?php  if($row['button_size'] == 'md')  echo ' selected="selected"';?>>Default</option>
                    <option value="lg"<?php  if($row['button_size'] == 'lg')  echo ' selected="selected"';?>>Large</option>
                    <option value="xl"<?php  if($row['button_size'] == 'xl')  echo ' selected="selected"';?>>Extra Large</option>
                    <option value="xxl"<?php if($row['button_size'] == 'xxl') echo ' selected="selected"';?>>Huge</option>
                  </select>
                </div>
                <p class="s_help">Height in pixels.</p>
              </div>
              <div class="s_row_1">
                <label>Label</label>
                <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][button_text]" value="<?php echo $row['button_text']; ?>" />
              </div>
              <div class="s_row_1 tbIconRow">
                <label>Icon</label>
                <div class="tbIcon<?php if (!$row['button_icon']): ?> s_icon_holder s_h_26<?php endif; ?>">
                  <?php if ($row['button_icon']): ?>
                  <span class="glyph_symbol <?php echo $row['button_icon']; ?>"></span>
                  <?php endif; ?>
                </div>
                <?php if (!$row['button_icon']): ?>
                <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
                <?php else: ?>
                <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
                <?php endif; ?>
                <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][button_icon]" value="<?php echo $row['button_icon']; ?>" />
                <input class="s_spinner s_ml_10" type="text" min="8" name="widget_data[lang][<?php echo $language_code; ?>][button_icon_size]" value="<?php echo $row['button_icon_size']; ?>" size="6" />
                <span class="s_metric">px</span>
              </div>
            </fieldset>
            <fieldset>
              <legend>Text</legend>
              <a class="tb_block_help">
                <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              </a>
              <div class="s_row_1">
                <textarea name="widget_data[lang][<?php echo $language_code; ?>][text]" id="call_to_action_widget_text_<?php echo $language_code; ?>" cols="43" rows="12"><?php echo $row['text']; ?></textarea>
              </div>
            </fieldset>
          </div>
          <?php endforeach; ?>

        </div>

      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix  tbWidgetCommonOptions">
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
      .find('input[name*="button_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
      .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
  }

  $("#call_to_action_widget_content").on("click", ".tbChooseIcon", function() {
    if ($(this).hasClass("tbRemoveIcon")) {
      $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
        .parents(".tbIconRow").first()
        .find('input[name*="button_icon"]:hidden').val("").end()
        .find(".tbIcon").addClass("s_icon_holder s_h_26").empty();
    } else {
      tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
    }

    return false;
  });
  
  <?php foreach ($languages as $language): ?>
  CKEDITOR.replace('call_to_action_widget_text_<?php echo $language['code']; ?>', {
    customConfig:              '<?php echo $theme_admin_javascript_relative_url; ?>ckeditor/custom/config.js',
    contentsCss:               '<?php echo $theme_admin_javascript_relative_url; ?>ckeditor/custom/styles.css',
    filebrowserBrowseUrl:      '<?php echo $tbData['fileManagerUrl']; ?>',
    filebrowserImageBrowseUrl: '<?php echo $tbData['fileManagerUrl']; ?>',
    filebrowserImageUploadUrl: null,
    filebrowserImageUploadUrl: null
  });
  <?php endforeach; ?>
});
</script>