<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li><a href="#store_settings_common">Common</a></li>
      <li><a href="#store_settings_category">Subcategories</a></li>
      <li><a href="#store_settings_product_listing">Product listing</a></li>
      <li><a href="#store_settings_product">Product page</a></li>
      <?php if ($gteOc22): ?>
      <li><a href="#store_settings_image">Images</a></li>
      <?php endif; ?>
      <?php $tbData->slotFlag('tb\theme_store.tabs.navigation'); ?>
    </ul>
  </div>

  <div id="store_settings_common" class="tb_subpanel">
    <?php require(tb_modification(dirname(__FILE__) . '/theme_store_common.tpl')); ?>
  </div>

  <div id="store_settings_category" class="tb_subpanel">
    <h2>Subcategories global settings</h2>

    <?php echo $tbData->fetchTemplate('theme_store_category', array('subcategories' => $store_category_default['subcategories'], 'input_property' => "store[category]")); ?>
  </div>

  <div id="store_settings_product_listing" class="tb_subpanel">
    <h2>Product listing global settings</h2>
    <?php echo $tbData->fetchTemplate('theme_store_product_listing', array('products' => $store_category_default['products'], 'input_property' => "store[category]", 'list_types' => array('list', 'grid', 'compact'))); ?>
  </div>

  <div id="store_settings_product" class="tb_subpanel">
    <?php require(tb_modification(dirname(__FILE__) . '/theme_store_product.tpl')); ?>
  </div>

  <?php if ($gteOc22): ?>
  <div id="store_settings_image" class="tb_subpanel">
    <?php require(tb_modification(dirname(__FILE__) . '/theme_store_image.tpl')); ?>
  </div>
  <?php endif; ?>

  <?php $tbData->slotFlag('tb\theme_store.tabs.content'); ?>

</div>

<div class="s_submit clearfix">
  <a class="s_button s_red s_h_40 tb_cp_form_submit"><?php echo $text_button_save_settings; ?></a>
</div>


<script type="text/javascript">

(function ($, tbApp) {

  $(tbApp).on("tbCp:initTab-store_settings", function() {

    var eventDispatcher = tbHelper.createEventDispatcher();

    var initSubcategories = function(obj) {
      if (!obj[1].find("a").is('[href="#store_settings_category"]')) {
        return;
      }

      tbApp.storeInitSubcategories($("#store_settings_category"), "store[category][subcategories]");

      eventDispatcher.removeEventListener("showTab", initSubcategories);
    };

    var initProductListing = function(obj) {

      if (!obj[1].find("a").is('[href="#store_settings_product_listing"]')) {
        return;
      }

      obj[0].find(".tbProductListingSettings").tabs({
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

      tbApp.storeInitProductListing($("#store_settings_product_listing"), "store[category][products][grid]");

      eventDispatcher.removeEventListener("showTab", initProductListing);
    };

    var initProduct = function(obj) {
      if (!obj[1].find("a").is('[href="#store_settings_product"]')) {
        return;
      }

      eventDispatcher.removeEventListener("showTab", initProduct);
    };

    eventDispatcher.addEventListener("showTab", initProduct);
    eventDispatcher.addEventListener("showTab", initProductListing);
    eventDispatcher.addEventListener("showTab", initSubcategories);

    var initOnce = function($tab, $panel) {

      if ($tab.data("initialized")) {
        return;
      }

      $tab.data("initialized", true);
      if (!$tab.is("[href='#store_settings_product_listing']")) {
        beautifyForm($panel);
      }
    };

    $("#store_settings").find(" > .tb_tabs").tabs({

      activate: function(event, ui) {
        initOnce(ui.newTab.find("a"), ui.newPanel);
        eventDispatcher.dispatchEvent("showTab", [$(ui.newPanel), $(ui.newTab)]);
        tbApp.cookie.set("tbStoreTabs", ui.newTab.index());
      },

      active: tbApp.cookie.get("tbStoreTabs", 0),

      create: function(event, ui) {
        initOnce(ui.tab.find("a"), ui.panel);
        eventDispatcher.dispatchEvent("showTab", [ui.panel, ui.tab]);
      }
    });

    $("#store_settings_category .tbAdvancedOptionsToggle").each(function() {
      $(this).bind("click", function() {
        if($(this).hasClass("tb_advanced_closed")) {
          $(this).parents("fieldset").find(".tbAdvancedOptions").hide();
          $(this)
            .removeClass("tb_advanced_closed")
            .addClass("tb_advanced_opened")
            .text("Advanced Options");
        } else {
          $(this).parents("fieldset").find(".tbAdvancedOptions").show();
          $(this)
            .addClass("tb_advanced_closed")
            .removeClass("tb_advanced_opened")
            .text("Basic Options");
        }
      });
    });

  });

})(jQuery, tbApp);

</script>
