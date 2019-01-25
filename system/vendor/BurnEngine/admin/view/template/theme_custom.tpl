<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li><a href="#custom_code_css">Stylesheet</a></li>
      <li><a href="#custom_code_javascript">Javascript</a></li>
      <?php $tbData->slotFlag('tb\theme_custom.tabs.navigation'); ?>
    </ul>
  </div>

  <?php // Custom CSS ?>
  <div id="custom_code_css" class="tb_subpanel">

    <h2>Custom Stylesheet</h2>

    <div class="s_row_2">
      <div class="s_full">
        <textarea id="common_custom_css" name="common[custom_css]" rows="20"><?php echo $common['custom_css']; ?></textarea>
      </div>
    </div>
    
  </div>


  <?php // Custom Javascript ?>
  <div id="custom_code_javascript" class="tb_subpanel">

    <h2>Custom Javascript</h2>

    <div class="s_row_2">
      <div class="s_full">
        <textarea id="common_custom_javascript" name="common[custom_javascript]" rows="20"><?php echo $common['custom_javascript']; ?></textarea>
      </div>
    </div>
  </div>


  <?php $tbData->slotFlag('tb\theme_custom.tabs.content'); ?>

</div>

<div class="s_submit clearfix">
  <a class="s_button s_red s_h_40 tb_cp_form_submit">Save settings</a>
</div>

<script type="text/javascript">

(function ($, tbApp) {

  $(tbApp).on("tbCp:initTab-custom_code", function() {

    $("#custom_code").find("> .tb_tabs").tabs({
      activate: function(event, ui) {
        tbApp.cookie.set("tbCustomTabs", ui.newTab.index());
      },
      active: tbApp.cookie.get("tbCustomTabs", 0)
    });

    var cmStyles = CodeMirror.fromTextArea(document.getElementById("common_custom_css"), {
      mode:        "css",
      lineNumbers: true,
      tabMode:     "indent"
    });

    var cmScript = CodeMirror.fromTextArea(document.getElementById("common_custom_javascript"), {
      mode:        "javascript",
      lineNumbers: true,
      tabMode:     "indent"
    });

    $(tbApp).on("tbCp:beforeSave", function() {
      cmStyles.save();
      cmScript.save();
    });
  });

})(jQuery, tbApp);

</script>