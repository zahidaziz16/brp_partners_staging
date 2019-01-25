<div id="products_system_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span><?php echo $widget->getName(); ?></span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_product_styles_holder">Product styles</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">
        <h2>Edit <?php echo $widget->getName(); ?></h2>

        <fieldset>
          <legend>Listing options</legend>
          <div class="s_actions">
            <div class="tbListingFilter">
              <input type="hidden" name="widget_data[filter]" value="0" />
              <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="widget_data[filter]" value="1"<?php if($settings['filter'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
          </div>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Margin top</label>
              <input class="s_spinner" type="text" name="widget_data[filter_mt]" value="<?php echo $settings['filter_mt']; ?>" size="7" step="5" min="-50" max="50" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Margin bottom</label>
              <input class="s_spinner" type="text" name="widget_data[filter_mb]" value="<?php echo $settings['filter_mb']; ?>" size="7" step="5" min="-50" max="50" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding top</label>
              <input class="s_spinner" type="text" name="widget_data[filter_pt]" value="<?php echo $settings['filter_pt']; ?>" size="7" step="5" min="0" max="50" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding bottom</label>
              <input class="s_spinner" type="text" name="widget_data[filter_pb]" value="<?php echo $settings['filter_pb']; ?>" size="7" step="5" min="0" max="50" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding left/right</label>
              <input class="s_spinner" type="text" name="widget_data[filter_pl]" value="<?php echo $settings['filter_pl']; ?>" size="7" step="5" min="0" max="50" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Pagination</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Margin top</label>
              <input class="s_spinner" type="text" name="widget_data[pagination_mt]" value="<?php echo $settings['pagination_mt']; ?>" size="7" step="5" min="-50" max="50" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Margin bottom</label>
              <input class="s_spinner" type="text" name="widget_data[pagination_mb]" value="<?php echo $settings['pagination_mb']; ?>" size="7" step="5" min="-50" max="50" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding top</label>
              <input class="s_spinner" type="text" name="widget_data[pagination_pt]" value="<?php echo $settings['pagination_pt']; ?>" size="7" step="5" min="0" max="50" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding bottom</label>
              <input class="s_spinner" type="text" name="widget_data[pagination_pb]" value="<?php echo $settings['pagination_pb']; ?>" size="7" step="5" min="0" max="50" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding left/right</label>
              <input class="s_spinner" type="text" name="widget_data[pagination_pl]" value="<?php echo $settings['pagination_pl']; ?>" size="7" step="5" min="0" max="50" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>
      </div>

      <div id="widget_product_styles_holder" class="tb_subpanel tbNoBeautify">
        <div class="tb_cp">
          <h2>Product styles</h2>
        </div>
        <div class="s_actions tbWidgetCustomStyles">
          <label class="inline left s_mr_10"><strong>Global settings:</strong></label>
          <input type="hidden" name="widget_data[inherit_products]" value="0" />
          <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="widget_data[inherit_products]" value="1"<?php if ($settings['inherit_products'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
        </div>
        <fieldset class="s_pb_30<?php if ($settings['inherit_products'] == '1'): ?> tb_disabled<?php endif; ?>">
          <?php echo $tbData->fetchTemplate('theme_store_product_listing', array('products' => $settings['products'], 'input_property' => "widget_data", 'list_types' => array('list', 'grid'))); ?>
        </fieldset>
      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <input type="hidden" name="widget_data[widget_name]" value="<?php echo $settings['widget_name']; ?>" />
        <input type="hidden" name="widget_data[slot_name]" value="<?php echo $settings['slot_name']; ?>" />
        <input type="hidden" name="widget_data[slot_prefix]" value="<?php echo $settings['slot_prefix']; ?>" />
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

    var $container = $("#products_system_widget_content");

    beautifyForm($container.find(".tbWidgetCustomStyles"));

    $container.find('.tbWidgetCustomStyles input[name*="inherit_products"]').on("change", function() {
        $(this).parents(".tbWidgetCustomStyles").next("fieldset").toggleClass("tb_disabled", $(this).is(":checked"));
    }).end()
    .find(".tbProductListingSettings").tabs({
      activate: function(event, ui) {
        if (!ui.newTab.data('initialized')) {
          beautifyForm(ui.newPanel);
          ui.newTab.data('initialized', 1);
        }
      },
      create: function(event, ui) {
        if (!ui.tab.data('initialized')) {
          beautifyForm(ui.panel);
          ui.tab.data('initialized', 1);
        }
      }
    });

    tbApp.storeInitProductListing($container, "widget_data[products][grid]");

    $('.tbListingFilter :input').on("change", function() {
      $(this).parents('.s_actions').first().next('.tb_wrap').toggleClass("tb_disabled", !$(this).is(":checked"));
    }).trigger('change');

  });
</script>