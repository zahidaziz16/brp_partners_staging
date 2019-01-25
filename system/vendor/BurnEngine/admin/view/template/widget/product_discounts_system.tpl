<div id="product_discounts_system_widget_content" class="s_widget_options_holder tb_cp">

  <style id="product_discounts_system_widget_content_styles">
  #product_discounts_system_widget_content .ui-tabs-panel [data-language_code]:not([data-language_code="<?php echo $tbData->first_language_code; ?>"]) {
    display: none;
  }
  </style>

  <h1 class="sm_title"><span>Product Discount</span></h1>

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

          <h2>Edit Product Discout</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#widget_product_discounts_system_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <div id="widget_product_discounts_system_language_<?php echo $language['code']; ?>" class="s_language_<?php echo $language['code']; ?>" data-language_code="<?php echo $language['code']; ?>"></div>
          <?php endforeach; ?>

          <fieldset>
            <legend>Block title</legend>

            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Show</label>
                <input type="hidden" name="widget_data[block_title]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[block_title]" value="1"<?php if($settings['block_title'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              </div>

              <div class="s_row_2 tb_col tb_1_4 tbBlockTitleAlign">
                <label>Text align</label>
                <?php foreach ($languages as $language): ?>
                <div class="s_full clearfix" data-language_code="<?php echo $language['code']; ?>">
                  <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
                  <div class="s_select">
                    <select name="widget_data[lang][<?php echo $language['code']; ?>][block_title_align]">
                      <option value="default"<?php if($settings['lang'][$language['code']]['block_title_align'] == 'default') echo ' selected="selected"';?>>Default</option>
                      <option value="left"<?php    if($settings['lang'][$language['code']]['block_title_align'] == 'left')    echo ' selected="selected"';?>>Left</option>
                      <option value="center"<?php  if($settings['lang'][$language['code']]['block_title_align'] == 'center')  echo ' selected="selected"';?>>Center</option>
                      <option value="right"<?php   if($settings['lang'][$language['code']]['block_title_align'] == 'right')   echo ' selected="selected"';?>>Right</option>
                    </select>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </fieldset>

          <fieldset>
            <legend>Discount</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5 tbDiscountStyle">
                <label>Style</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[style]">
                      <option value="table"<?php  if($settings['style'] == 'table')  echo ' selected="selected"';?>>Table</option>
                      <option value="inline"<?php if($settings['style'] == 'inline') echo ' selected="selected"';?>>Inline</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbStyleTableCondensed">
                <label>Condensed table</label>
                <input type="hidden" name="widget_data[table_condensed]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[table_condensed]" value="1"<?php if($settings['table_condensed'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              </div>
            </div>
          </fieldset>

        </div>
      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <input type="hidden" name="widget_data[widget_name]" value="<?php echo $settings['widget_name']; ?>" />
        <input type="hidden" name="widget_data[slot_name]" value="<?php echo $settings['slot_name']; ?>" />
        <input type="hidden" name="widget_data[slot_prefix]" value="<?php echo $settings['slot_prefix']; ?>" />
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
  $(document).ready(function () {

    $("#widget_settings_holder").find(".tbLanguageTabs").first().tabs({
      activate: function (event, ui) {
        $("#product_discounts_system_widget_content_styles").html('#product_discounts_system_widget_content .ui-tabs-panel [data-language_code]:not([data-language_code="' + ui.newPanel.data("language_code") + '"]) { display: none; }');
      }
    });

    $('.tbDiscountStyle').find('select').bind('change', function() {
      $('.tbStyleTableCondensed').toggleClass("tb_disabled", $(this).val() != 'table');
    }).trigger('change');

  });
</script>