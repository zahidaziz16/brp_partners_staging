<script type="text/javascript">
  $("#tb_warning_alert").show()
    .find("span.tb_alert_text")
    .append('<a id="tb_install_sample_data" class="s_button s_white s_h_40 s_ml_20 s_mr_40 right s_icon_16 s_database_add_16" href="javascript:;">Install demo data</a><span class="block s_pr_40 align_left" style="font-weight: normal; line-height: 40px;">This message will no longer appear once you close it.</span>').end()
    .find(".s_close").bind("click", function() {
      if (confirm("Are you sure?")) {
        $.getJSON($sReg.get('/tb/url/removeInstallSampleData'));
        $("#tb_warning_alert").fadeOut(500);
      }
    });

  $("#tb_install_sample_data").bind("click", function() {
    var $output = $(Mustache.render($("#common_modal_dialog_template").text(), {
      width:       800,
      margin_left: -400
    })).appendTo($("body"));

    var $settingsWindow = $output.find(".sm_window").first();

    $settingsWindow.find(".sm_content").append($("#install_sample_data_template").text());

    $settingsWindow.find(".tbConfirmInstall").bind("click", function() {
      if (!confirm("Are you sure?")) {
        return false;
      }

      $settingsWindow.find("a.sm_closeWindowButton").trigger("click");
      $("#tb_cp_content_wrap").block({ message: "<h1>Reading theme settings...</h1>" });

      var $installButton = $(this);

      $.getJSON($installButton.attr("href") + '&' + $settingsWindow.find(":input").serialize() , function(data) {
        $("#tb_warning_alert").hide();
        $("#tb_cp_content_wrap").unblock();

        if (data.success == true) {
          $("#tb_cp_content_wrap").block({ message: "<h1>Installing demo data...</h1>" });

          var installBuilderData = function(url) {
            var jqXHR = $.getJSON(url, function(data) {
              if (data.success) {

                if (Number(data.next) == 0) {
                  $("#tb_cp_content_wrap").unblock().block({ message: "<h1>Reloading admin panel...</h1>" });
                  window.location.hash = '#tb_cp_panel_theme_settings,color_settings_tab';
                  window.location.reload(true);
                } else {
                  installBuilderData(url);
                }
              } else {
                alert('Something went wrong. Please, contact the support');
              }
            });

            jqXHR.fail(function(jqXHR) {
              alert(jqXHR.responseText);
            });
          };

          installBuilderData($installButton.data("builder_demo"));
        } else {
          displayAlertError(data.message);
        }
      });

      return false;
    });

    $settingsWindow.find(".tbStoreRow").each(function(index) {
      var $themeSelect = $(this).find(".tbStoreThemeSelect");

      if (index == 0) {
        $themeSelect.bind("change", function() {
          $settingsWindow.find(".tbStoreRow:gt(0) .tbStoreThemeSelect").val($(this).val()).trigger("change");
        });
      } else {
        $themeSelect.parent().parent().addClass("tb_disabled");
      }
    });

    $settingsWindow.find("a.sm_closeWindowButton").add($output.find(".sm_overlayBG")).bind("click", function() {
      $settingsWindow.fadeOut(300, function() {
        $settingsWindow.parent("div").remove();
      });
    });

    $settingsWindow.show();
    beautifyForm($settingsWindow);

    return false;
  });

  $(document).ready(function() {
    $('body').on('change', '.tbStoreThemeSelect', function() {
      var $variants_holder = $(this).closest('.s_row_2').next();

      $variants_holder.find('> div').hide();
      $variants_holder.find('> .' + $(this).val() + '_demo_variants').show();
    }).trigger('change');
  });
</script>

<script type="text/template" id="install_sample_data_template">
  <div class="s_widget_options_holder tb_cp">
    <h1 class="sm_title">Import Demo Data</h1>
    <div class="tb_subpanel tb_subpanel_tabs">
      <div class="s_server_msg s_msg_yellow">
        <p class="s_icon_32 s_error_32 align_left">This action is suitable for fresh install only. It <strong>cannot</strong> be undone and is not intended for live operating shops as it affects the OpenCart database.<br /><br />All your current <strong>orders</strong>, <strong>customer data</strong>, <strong>products</strong>, <strong>categories</strong>, <strong>reviews</strong> and <strong>information pages</strong> will be <strong>removed</strong> and replaced with demo records. Your localization will also be reset to <strong>English language</strong>.<br /><br />Please, make sure you don't have any important data. It's always a good idea to make a <strong>database backup</strong> first. If you are in doubt, you can refer to the <a href="http://docs.themeburn.com" target="_blank">documentation</a> or the <a href="http://support.themeburn.com" target="_blank">support forum</a>.</p>
      </div>

      <?php foreach ($all_stores as $store_item): ?>
      <span class="clear s_mb_20 s_pt_20 border_eee"></span>

      <div class="tb_wrap tb_gut_30 tbStoreRow">
        <div class="tb_col tb_1_5">
          <label><strong><?php echo $store_item['name']; ?></strong></label>
        </div>
        <div class="s_row_2 tb_col tb_1_4">
          <label>Theme</label>
          <div class="s_select">
            <select class="tbStoreThemeSelect" name="import_stores[<?php echo $store_item['store_id']; ?>][theme_id]">
              <?php foreach ($available_themes as $theme): ?>
              <option value="<?php echo $theme['id']; ?>"><?php echo $theme['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="s_row_2 tb_col tb_1_4">
          <?php foreach ($available_themes as $theme): ?>
          <?php if (!empty($theme['variants'])): ?>
          <div class="<?php echo $theme['id']; ?>_demo_variants s_row_2">
            <label>Sample Data</label>
            <div class="s_select">
              <select name="import_stores[<?php echo $store_item['store_id']; ?>][variant][<?php echo $theme['id']; ?>]">
                <option value="default">Default</option>
                <?php foreach ($theme['variants'] as $variant): ?>
                <option value="<?php echo $variant['id']; ?>"><?php echo $variant['name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <?php else: ?>
          <input type="hidden" name="import_stores[<?php echo $store_item['store_id']; ?>][variant][<?php echo $theme['id']; ?>]" value="default" />
          <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
    <div class="s_submit">
      <a class="s_button s_h_40 s_red tbConfirmInstall" href="<?php echo $tbUrl->generate('import/installSampleData'); ?>" data-builder_demo="<?php echo $tbUrl->generate('import/importBuilderDemo'); ?>">Import</a>
    </div>
  </div>
</script>