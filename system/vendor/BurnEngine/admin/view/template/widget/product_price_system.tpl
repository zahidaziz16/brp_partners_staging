<div id="product_price_system_widget_content" class="s_widget_options_holder tb_cp">

  <style id="product_price_system_widget_content_styles">
  #product_price_system_widget_content .ui-tabs-panel [data-language_code]:not([data-language_code="<?php echo $tbData->first_language_code; ?>"]) {
    display: none;
  }
  </style>

  <h1 class="sm_title"><span>Product Price</span></h1>

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

          <h2>Edit Product Price</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#widget_product_price_system_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <div id="widget_product_price_system_language_<?php echo $language['code']; ?>" class="s_language_<?php echo $language['code']; ?>" data-language_code="<?php echo $language['code']; ?>"></div>
          <?php endforeach; ?>

          <div class="s_row_1 tb_1_2">
            <label>Text align</label>
            <?php foreach ($languages as $language): ?>
            <div class="s_full clearfix" data-language_code="<?php echo $language['code']; ?>">
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <span class="s_select">
                <select name="widget_data[lang][<?php echo $language['code']; ?>][text_align]">
                  <option value="left"<?php   if ($settings['lang'][$language['code']]['text_align'] == 'left'):   ?> selected="selected"<?php endif; ?>>Left</option>
                  <option value="center"<?php if ($settings['lang'][$language['code']]['text_align'] == 'center'): ?> selected="selected"<?php endif; ?>>Center</option>
                  <option value="right"<?php  if ($settings['lang'][$language['code']]['text_align'] == 'right'):  ?> selected="selected"<?php endif; ?>>Right</option>
                </select>
              </span>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="s_row_1">
            <label>Old price on new line</label>
            <input type="hidden" name="widget_data[old_price_new_line]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="widget_data[old_price_new_line]" value="1"<?php if($settings['old_price_new_line'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>

          <div class="s_row_1">
            <label>Show label</label>
            <input type="hidden" name="widget_data[show_label]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="widget_data[show_label]" value="1"<?php if($settings['show_label'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>

          <div class="s_row_1">
            <label>Show tax</label>
            <input type="hidden" name="widget_data[show_tax]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="widget_data[show_tax]" value="1"<?php if($settings['show_tax'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>

          <div class="s_row_1">
            <label>Show reward points</label>
            <input type="hidden" name="widget_data[show_reward]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="widget_data[show_reward]" value="1"<?php if($settings['show_reward'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>

          <div class="s_row_1">
            <label>Show savings</label>
            <input type="hidden" name="widget_data[show_savings]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="widget_data[show_savings]" value="1"<?php if($settings['show_savings'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>

          <div class="s_row_1">
            <label>Savings sum</label>
            <input type="hidden" name="widget_data[show_savings_sum]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="widget_data[show_savings_sum]" value="1"<?php if($settings['show_savings_sum'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            <p class="s_help">Show savings total, instead of percent.</p>
          </div>
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
$(document).ready(function () {

  $("#widget_settings_holder").find(".tbLanguageTabs").first().tabs({
    activate: function (event, ui) {
      $("#product_price_system_widget_content_styles").html('#product_price_system_widget_content .ui-tabs-panel [data-language_code]:not([data-language_code="' + ui.newPanel.data("language_code") + '"]) { display: none; }');
    }
  });

});
</script>