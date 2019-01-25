<div id="subcategories_system_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Subcategories</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_product_styles_holder">Edit</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_title_styles_holder">Title Styles</a></li>
        </ul>
      </div>

      <div id="widget_product_styles_holder" class="tb_subpanel">
        <h2>Edit Subcategories</h2>

        <fieldset>
          <legend>Block title</legend>

          <div class="tb_wrap">
            <div class="s_row_2 tb_col tb_1_6">
              <label>Show</label>
              <input type="hidden" name="widget_data[block_title]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[block_title]" value="1"<?php if($settings['block_title'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>

            <?php foreach ($languages as $language): ?>
            <div class="s_row_2 tb_col tb_1_5 tbBlockTitleAlign">
              <label>Text align</label>
              <div class="s_full" data-language_code="<?php echo $language['code']; ?>">
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
            </div>
            <?php endforeach; ?>

          </div>
        </fieldset>

        <div class="s_actions tbWidgetCustomStyles">
          <label class="inline left s_mr_10"><strong>Use global category settings:</strong></label>
          <input type="hidden" name="widget_data[inherit_subcategories]" value="0" />
          <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="widget_data[inherit_subcategories]" value="1"<?php if ($settings['inherit_subcategories'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
        </div>
        <div class="clear<?php if ($settings['inherit_subcategories'] == '1'): ?> tb_disabled<?php endif; ?>">
          <?php echo $tbData->fetchTemplate('theme_store_category', array('subcategories' => $settings['subcategories'], 'input_property' => "widget_data")); ?>
        </div>
      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <input type="hidden" name="widget_data[widget_name]" value="<?php echo $settings['widget_name']; ?>" />
        <input type="hidden" name="widget_data[slot_name]" value="<?php echo $settings['slot_name']; ?>" />
        <input type="hidden" name="widget_data[slot_prefix]" value="<?php echo $settings['slot_prefix']; ?>" />
        <?php require tb_modification(dirname(__FILE__) . '/_common_options.tpl'); ?>
      </div>

      <div id="widget_title_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <?php $style_section_id = 'title'; ?>
        <?php require tb_modification(dirname(__FILE__) . '/_common_options.tpl'); ?>
      </div>

    </div>

    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tbWidgetUpdate" href="javascript:;">Update Settings</a>
    </div>

  </form>

</div>

<script type="text/javascript">
$(document).ready(function() {

  $("#subcategories_system_widget_content").find('.tbWidgetCustomStyles input[name*="inherit_subcategories"]').on("change", function() {
    $(this).parents(".tbWidgetCustomStyles").next().toggleClass("tb_disabled", $(this).is(":checked"));
  });

  tbApp.storeInitSubcategories($("#widget_product_styles_holder"), "widget_data[subcategories]");

});
</script>