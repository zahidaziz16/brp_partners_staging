<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li><a href="#extension_newsletter_settings">Settings</a></li>
      <li aria-controls="extension_newsletter_subscribers"><a href="<?php echo $tbUrl->generate('default/subscribers'); ?>">Subscribers</a></li>
    </ul>
  </div>

  <div id="extension_newsletter_settings" class="tb_subpanel">
    <h2>Newsletter Settings</h2>
    <fieldset>
      <legend>Options</legend>
      <div class="s_row_1">
        <label for="newsletter_input_show_name">Name field</label>
        <input type="hidden" name="newsletter[show_name]" value="0" />
        <label class="tb_toggle"><input id="newsletter_input_show_name" type="checkbox" name="newsletter[show_name]" value="1"<?php if($newsletter['show_name'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
      </div>
      <div class="s_row_1">
        <label for="newsletter_subscribe_customers">Subscribe customers</label>
        <input type="hidden" name="newsletter[subscribe_customers]" value="0" />
        <label class="tb_toggle"><input id="newsletter_subscribe_customers" type="checkbox" name="newsletter[subscribe_customers]" value="1"<?php if($newsletter['subscribe_customers'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
      </div>
    </fieldset>
    <div class="s_submit clearfix">
      <div class="left">
        <a class="s_button s_white s_h_40 tbButtonBackToExtensions">Back to extensions</a>
      </div>
      <div class="right">
        <a class="s_button s_red s_h_40 tbSaveNewsletterSettings">Save Settings</a>
      </div>
    </div>
  </div>

  <div id="extension_newsletter_subscribers" class="tb_subpanel"></div>

</div>

<script>
(function($, tbApp) {

  var $container = $("#tb_cp_panel_extensions > .tb_tabs").first();

  $container.tabs({
    activate: function(event, ui) {
      tbApp.cookie.set("tbExtensionNewsletterTabs", ui.newTab.index());
    },
    active: tbApp.cookie.get("tbExtensionNewsletterTabs", 0),
    beforeLoad: function(event, ui) {
      if (ui.tab.data("loaded")) {
        event.preventDefault();
      } else {
        ui.panel.block();
      }
    },
    load: function(event, ui) {
      ui.tab.data("loaded", true);
      ui.panel.unblock();
    }
  });

  $container.find(".tbSaveNewsletterSettings").bind("click", function() {
    $container.block();
    $.post($sReg.get("/tb/url/newsletter/default/saveSettings"), $container.find(":input[name^='newsletter']").serializeArray(), function() {
      $container.unblock();
    }, "json");

    return false;
  });

  $container.on("click", ".subscribersToggle", function() {
    $container.find("input[name^='selected_subscribers']").prop("checked", this.checked);
  });

  $container.on("click", ".tbDeleteSubscribers", function() {
    $container.block();
    $.post($sReg.get("/tb/url/newsletter/default/deleteSubscribers"), $container.find(".tbProductsListingTable :input[name^='selected_subscribers']").serializeArray(), function() {
      $container.tabs("widget").find(".ui-tabs-active").data("loaded", false)
      $container.tabs('load', $container.tabs("option", "active"));
      $container.unblock();
    }, "json");

    return false;
  });

  $container.on("click", ".pagination a", function() {
    var $parent = $(this).closest(".tbLanguageTabs");
    var $container = $parent.hasClass('tb_tabs') ? $("#" + $parent.find("> ul > li").eq($parent.tabs("option", "active")).attr("aria-controls")) : $parent;

    $container.find('> .tb_data_holder').block();
    $.get($(this).attr("href"), function(data) {
      $container.find('> .tb_data_holder').unblock();
      $container.html(data);
    });

    return false;
  })

})(jQuery, tbApp);
</script>

