<div id="widget_featured_products" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Featured Products</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <input type="hidden" name="widget_id" value="<?php echo $widget->getId(); ?>" />
    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_product_choose_holder" add_modal_class="sm_wide">Choose products</a></li>
          <li><a href="#widget_product_styles_holder">Product styles</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_title_styles_holder">Title Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Edit Featured Products</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#widget_featured_products_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="widget_featured_products_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>">
            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the Featured Products content block for the current language.</p>
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
                <label>Limit products</label>
                <input class="s_spinner" type="text" name="widget_data[filter_limit]" value="<?php echo $settings['filter_limit']; ?>" min="1" size="5" />
              </div>
            </div>
          </fieldset>

          <fieldset>
            <legend>Sorting</legend>
            <div class="tb_wrap tb_gut_30">

              <div class="s_row_2 tb_col tb_1_5 tbSortPropertyDiv">
                <label>Property</label>
                <span class="clear"></span>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[sort_property]">
                      <option value="pd.name"<?php if ($settings['sort_property'] == 'pd.name') echo ' selected="selected"'; ?>>Name</option>
                      <option value="p.price"<?php if ($settings['sort_property'] == 'p.price') echo ' selected="selected"'; ?>>Price</option>
                      <option value="rating"<?php if ($settings['sort_property'] == 'rating') echo ' selected="selected"'; ?>>Rating</option>
                      <option value="p.date_added"<?php if ($settings['sort_property'] == 'p.date_added') echo ' selected="selected"'; ?>>Date Added</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="s_row_2 tb_col tb_1_5 tbSortOrderDiv">
                <label>Order</label>
                <span class="clear"></span>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[sort_order]">
                      <option value="ASC"<?php if ($settings['sort_order'] == 'ASC') echo ' selected="selected"'; ?>>Ascending</option>
                      <option value="DESC"<?php if ($settings['sort_order'] == 'DESC') echo ' selected="selected"'; ?>>Descending</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="s_row_2 tb_col tb_1_5">
                <label>Randomize</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[filter_randomize]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[filter_randomize]" value="1"<?php if ($settings['filter_randomize'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
            </div>
          </fieldset>

          <?php require tb_modification(dirname(__FILE__) . '/_product_slider_options.tpl'); ?>

          <?php require tb_modification(dirname(__FILE__) . '/_countdown_options.tpl'); ?>

        </div>

      </div>

      <div id="widget_product_choose_holder" class="tb_subpanel tb_has_sidebar clearfix">
        <h2>Choose Products</h2>
        <?php echo $tbGet->productsModalController->getProductsList($widget->getProductIds()); ?>
        <div class="tbFeaturedProductIds">
          <?php foreach ($widget->getProductIds() as $id): ?>
          <input type="hidden" name="widget_data[product_ids][]" value="<?php echo $id; ?>" />
          <?php endforeach; ?>
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

    var $container = $("#widget_featured_products");

    beautifyForm($container.find(".tbWidgetCustomStyles"));

    $container
      .find('.tbWidgetCustomStyles input[name*="inherit_products"]').on("change", function() {
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
      }).trigger("change").end()
      .find(":checkbox[name$='[filter_randomize]']").bind("click", function() {
        $container.find(".tbSortPropertyDiv, .tbSortOrderDiv").toggleClass("tb_disabled", $(this).is(":checked"));
      });

    $container.find(".tbSortPropertyDiv, .tbSortOrderDiv").toggleClass("tb_disabled", $container.find(":checkbox[name$='[filter_randomize]']").is(":checked"));

    tbApp.storeInitProductListing($container, "widget_data[products][grid]");
  });

  function submitFeaturedUrl(url, $container, callback) {

    $container.find(".tbProductsListing").block();
    $.get(url, function(data) {
      $container.find(".tbProductsListing").replaceWith(data);
      beautifyForm($container);
      if ($.isFunction(callback)) {
        callback.call(this);
      }
      $container.find(".tbProductsListing").unblock();
    });
  }

  var $container = $("#widget_product_choose_holder");

  tbApp.createCategoryComboBox($container.find('select.tbComboBox').first(), {
    onSelect: function(event, ui) {
      ui.context.widget().parent().find('input[name="filter_category_id"]').val(ui.item.optionValue);

      var query = $.jurlp($container.find('input[name="filter_request_url"]').val()).query();
      var url = getProductsFilterUrl() + "&sort=" + query.sort + "&order=" + query.order;

      submitFeaturedUrl(url, $container, function() {
        ui.context.uiInput.trigger("blur");
      });
    },
    customValue: "All"
  });

  $container.on("click", ".tbSubmitFilter", function() {
    var query = $.jurlp($container.find('input[name="filter_request_url"]').val()).query();
    var url = getProductsFilterUrl() + "&sort=" + query.sort + "&order=" + query.order;

    submitFeaturedUrl(url, $container);

    return false;
  });

  $container.on("click", ".tbResetFilter", function() {
    $container.find(".tbFilterForm").clearForm().find(":hidden").val("");
    submitFeaturedUrl(getProductsFilterUrl(), $container);

    return false;
  });

  $container.on("click", "div.pagination a", function() {
    var query = $.jurlp($(this).attr("href")).query();
    var url = getProductsFilterUrl() + "&page=" + query.page + "&sort=" + query.sort + "&order=" + query.order;

    submitFeaturedUrl(url, $container);

    return false;
  });

  $container.on("click", ".tbProductsListingTable thead a", function() {
    var query = $.jurlp($(this).attr("href")).query();
    var url = getProductsFilterUrl() + "&page=" + query.page + "&sort=" + query.sort + "&order=" + query.order;

    submitFeaturedUrl(url, $container);

    return false;
  });

  $container.on("click", ".tbButtonProductSelect", function() {
    var parent = $(this).parents("tr:first");
    var product_id = parent.find("input").val();

    if (!parent.hasClass("tb_selected")) {
      parent.addClass("tb_selected");
      $container.find(".tbFeaturedProductIds")
        .append('<input type="hidden" name="widget_data[product_ids][]" value="' + product_id + '" />');
    } else {
      parent.removeClass("tb_selected");
      $container.find('.tbFeaturedProductIds input[value="' + product_id + '"]').remove();
    }

    return false;
  });

  $container.on("keydown", ".tbFilterForm input", function(e) {
    if (e.keyCode == 13) {
      submitFeaturedUrl(getProductsFilterUrl(), $container);
    }
  });

  function getProductsFilterUrl() {
    var url = '<?php echo str_replace('&amp;', '&', $tbUrl->generate('productsModal/getProductsOnly')); ?>';
    var input_params = tbHelper.collectInputParams([
      "filter_name",
      "filter_model",
      "filter_price_less",
      "filter_price_more",
      "filter_quantity_more",
      "filter_category_id",
      "filter_disabled",
      "filter_selected",
      "filter_specials"
    ], $container);

    if (input_params != "") {
      url += "&" + input_params;
    }

    url += '&' + decodeURIComponent($.param({
      products_ids : jQuery.map($container.find(".tbFeaturedProductIds input").serializeArray(), function(obj) {
        return obj.value;
      })
    }));

    return url;
  }
</script>