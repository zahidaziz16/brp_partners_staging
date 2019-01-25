<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li><a href="#social_settings_facebook">Facebook</a></li>
      <li><a href="#social_settings_twitter">Twitter</a></li>
      <?php $tbData->slotFlag('tb\theme_social.tabs.navigation'); ?>
    </ul>
  </div>

  <div id="social_settings_facebook" class="tb_subpanel">
    <?php require(tb_modification(dirname(__FILE__) . '/theme_social_facebook.tpl')); ?>
  </div>

  <div id="social_settings_twitter" class="tb_subpanel">
    <?php require(tb_modification(dirname(__FILE__) . '/theme_social_twitter.tpl')); ?>
  </div>

  <?php $tbData->slotFlag('tb\theme_social.tabs.content'); ?>

</div>

<div class="s_submit clearfix">
  <a class="s_button s_red s_h_40 tb_cp_form_submit"><?php echo $text_button_save_settings; ?></a>
</div>


<script type="text/javascript">

(function ($, tbApp) {

  $(tbApp).on("tbCp:initTab-social_settings", function() {

    var initOnce = function($tab, $panel) {

      if ($tab.data("initialized")) {
        return;
      }

      $tab.data("initialized", true);
      beautifyForm($panel)
    };

    $("#social_settings")
      .find(" > .tb_tabs").tabs({
        activate: function(event, ui) {
          initOnce(ui.newTab.find("a"), ui.newPanel);
          tbApp.cookie.set("tbSocialTabs", ui.newTab.index());
        },
        active: tbApp.cookie.get("tbSocialTabs", 0),
        create: function(event, ui) {
          initOnce(ui.tab.find("a"), ui.panel);
        }
      }).end()
      .find(".tbLanguageTabs").tabs({
        activate: function(event, ui) {
          initOnce(ui.newTab.find("a"), ui.newPanel);
        },
        create: function(event, ui) {
          initOnce(ui.tab.find("a"), ui.panel);
        }
      });
  });

})(jQuery, tbApp);

</script>
