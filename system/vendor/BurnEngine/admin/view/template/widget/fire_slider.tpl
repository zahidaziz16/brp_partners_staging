<div id="fireslider_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>FireSlider</span></h1>

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

          <h2>Edit FireSlider</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#text_widget_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="text_widget_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>">
            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the Text content block for the current language.</p>
            </div>

            <div class="s_row_1">
              <label><strong>Slider</strong></label>
              <div class="s_select">
                <select name="widget_data[lang][<?php echo $language_code; ?>][slider_id]">
                  <option value="0">None</option>
                  <?php foreach ($tbData->getSliders() as $slider): ?>
                  <option value="<?php echo $slider['id']; ?>"<?php if($settings['lang'][$language_code]['slider_id'] == $slider['id']) echo ' selected="selected"';?>><?php echo $slider['name']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
            </div>
          </div>
          <?php endforeach; ?>

          <div class="s_row_1">
            <label><strong>Navigation size</strong></label>
            <div class="s_select">
              <select name="widget_data[navigation_size]">
                <option value="3"<?php if($settings['navigation_size'] == '3') echo ' selected="selected"';?>>Large</option>
                <option value="2"<?php if($settings['navigation_size'] == '2') echo ' selected="selected"';?>>Medium</option>
                <option value="1"<?php if($settings['navigation_size'] == '1') echo ' selected="selected"';?>>Small</option>
              </select>
            </div>
          </div>

        </div>

      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
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
});
</script>