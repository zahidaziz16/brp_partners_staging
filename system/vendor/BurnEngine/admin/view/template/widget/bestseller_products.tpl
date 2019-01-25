<div id="widget_bestseller_products" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Bestseller Products</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_product_styles_holder">Product styles</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_title_styles_holder">Title Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Edit Bestseller Products</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#widget_bestseller_products_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="widget_bestseller_products_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>">
            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the Bestseller Products content block for the current language.</p>
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

          </div>
          <?php endforeach; ?>

          <?php require tb_modification(dirname(__FILE__) . '/_product_list_type_options.tpl'); ?>

          <fieldset>
            <legend>Filter</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Narrow results</label>
                <input type="hidden" name="widget_data[filter_category]" value="<?php echo $settings['filter_category']; ?>" />
                <select class="tb_nostyle" name="filter_category" category_name="<?php if ($settings['filter_category'] > 0) echo $tbData->getCategoryName($settings['filter_category']); ?>"></select>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbFilterIncludeSubcategories"<?php if($settings['filter_category'] == 0): ?> style="display: none;"<?php endif; ?>>
                <label>Include subcategories</label>
                <input type="hidden" name="widget_data[filter_category_children]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[filter_category_children]" value="1"<?php if ($settings['filter_category_children'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Limit products</label>
                <input class="s_spinner" type="text" name="widget_data[filter_limit]" value="<?php echo $settings['filter_limit']; ?>" min="1" size="5" />
              </div>
            </div>
          </fieldset>

          <?php require tb_modification(dirname(__FILE__) . '/_product_slider_options.tpl'); ?>

          <?php require tb_modification(dirname(__FILE__) . '/_countdown_options.tpl'); ?>

        </div>

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
        <fieldset<?php if ($settings['inherit_products'] == '1'): ?> class="tb_disabled"<?php endif; ?>>
          <?php echo $tbData->fetchTemplate('theme_store_product_listing', array('products' => $settings['products'], 'input_property' => "widget_data", 'list_types' => array('list', 'grid', 'compact'))); ?>
        </fieldset>
      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar clearfix tbWidgetCommonOptions">
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

    var $container = $("#widget_bestseller_products");

    beautifyForm($container.find(".tbWidgetCustomStyles"));

    $container.find('.tbWidgetCustomStyles input[name*="inherit_products"]').on("change", function() {
      $(this).parents(".tbWidgetCustomStyles").next("fieldset").toggleClass("tb_disabled", $(this).is(":checked"));
    }).end()
    .find(".tbProductListingSettings > div").hide().end()
    .find("select[name$='[view_mode]']").bind("change", function() {
      var $listingSettingsDiv = $container.find(".tbProductListingSettings");
      var $visible = $listingSettingsDiv.find("> div.tbProductsSettings" + tbHelper.ucfirst($(this).val()));

      $listingSettingsDiv.find("> div").hide();
      $visible.show();

      if (!$visible.hasClass("tbBeautified")) {
        setTimeout(function() {
          beautifyForm($visible);
        }, 100);
        $visible.addClass("tbBeautified");
      }
    }).trigger("change");

    tbApp.storeInitProductListing($container, "widget_data[products][grid]");

    var category_name = $container.find('select[name="filter_category"]').attr("category_name");

    if (category_name == "") {
      if ($container.find('input[name$="[filter_category]"]').val() == -1) {
        category_name = "Autodetect";
      } else {
        category_name = "All";
      }
    }

    tbApp.createCategoryComboBox($container.find('select[name="filter_category"]').first(), {
      onSelect: function(event, ui) {
        $container.find('input[name$="[filter_category]"]').val(ui.item.optionValue);
        $container.find(".tbFilterIncludeSubcategories").toggle(ui.item.optionValue != "0");
        ui.context.uiInput.trigger("blur");

      },
      customValue: category_name,
      prependHtml: '<li><a href="javascript:;" category_id="-1">Autodetect</a></li>'
    });

  });
</script>