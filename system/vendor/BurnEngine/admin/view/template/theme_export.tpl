<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li><a href="#theme_subpanel_export">Export</a></li>
      <li><a href="#theme_subpanel_import">Import</a></li>
    </ul>
  </div>

  <?php // Export Tab ?>
  <div id="theme_subpanel_export">
    <div class="tb_subpanel">
      <div class="tb_row">
        <div class="tb_wrap tb_gut_30">
          <div class="tb_col tb_1_3">

            <h2>Export options</h2>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_export_settings">Settings/Styles</label>
              <label class="tb_toggle"><input id="theme_export_settings" type="checkbox" name="export[]" value="settings" checked="checked" /><span></span><span></span></label>
            </div>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_export_colors">Theme colors</label>
              <label class="tb_toggle"><input id="theme_export_colors" type="checkbox" name="export[]" value="colors" checked="checked" /><span></span><span></span></label>
            </div>

            <?php if ($presets_exist): ?>
            <div class="s_row_1 tb_live_row_1">
              <label for="theme_export_presets">Style presets</label>
              <label class="tb_toggle"><input id="theme_export_presets" type="checkbox" name="export[]" value="presets" checked="checked" /><span></span><span></span></label>
            </div>
            <?php endif; ?>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_export_menu">Menu sets</label>
              <label class="tb_toggle"><input id="theme_export_menu" type="checkbox" name="export[]" value="menu" checked="checked" /><span></span><span></span></label>
            </div>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_export_builder">Builder Pages</label>
              <label class="tb_toggle"><input id="theme_export_builder" type="checkbox" name="export[]" value="builder" checked="checked" /><span></span><span></span></label>
            </div>

            <?php if ($sliders_exist): ?>
            <div class="s_row_1 tb_live_row_1">
              <label for="theme_export_slider">Sliders</label>
              <label class="tb_toggle"><input id="theme_export_slider" type="checkbox" name="export[]" value="slider" checked="checked" /><span></span><span></span></label>
            </div>
            <?php endif; ?>

            <?php if ($skins_exist): ?>
            <div class="s_row_1 tb_live_row_1">
              <label for="theme_export_images">Custom Skins</label>
              <label class="tb_toggle"><input id="theme_export_skins" type="checkbox" name="export[]" value="skins" checked="checked" /><span></span><span></span></label>
            </div>
            <?php endif; ?>

            <?php if (extension_loaded('zip')): ?>
            <div class="s_row_1 tb_live_row_1">
              <label for="theme_export_images">Images</label>
              <label class="tb_toggle"><input id="theme_export_images" type="checkbox" name="export[]" value="images" checked="checked" /><span></span><span></span></label>
            </div>
            <?php endif; ?>

          </div>

          <div id="export_settings_help" class="tb_col tb_2_3">
            <div class="s_server_message s_msg_blue s_p_20">
              <h3 class="s_icon_16 s_info_16">Export information</h3>
              <h4>Styles</h4>
              <p>Settings, styles, colors and skins are theme specific and can be only exported/imported between same themes.</p>
              <br>
              <h4>Content</h4>
              <p>Menus, Page Builder content, sliders and presets can be imported between different themes.</p>
              <br>
              <h4>Images</h4>
              <p>By default all images used for styling (e.g. background) or in content (e.g. gallery images) are exported with the other data. Due to limitations on some servers, you may need to disable image export and copy images manually. <strong>Images are stored in the downloadable data package only.</strong></p>
            </div>
          </div>

          <div id="export_download" class="tb_col tb_2_3" style="display: none;">

            <h2>Output</h2>

            <div class="s_row_1 tb_live_row_1">
              <textarea id="export_settings_result" class="tb_1_1 s_mb_20" rows="13"></textarea>
              <a class="s_button s_white s_h_30 s_icon_10 s_arrow_down_10 tbSaveSettings" href="javascript:;" style="display: none;">Download exported settings</a>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="s_submit clearfix">
      <a id="export_settings" href="<?php echo $tbUrl->generate('export/export'); ?>" class="s_button s_red s_h_40">Export</a>
    </div>

  </div>

  <?php // Import Tab ?>
  <div id="theme_subpanel_import" class="tb_subpanel">
    <div class="tb_subpanel">
      <div class="tb_row">
        <div class="tb_wrap tb_gut_30">
          <div class="tb_col tb_2_3">
            <h2>Settings Input</h2>
            <textarea id="import_settings_input" class="tb_1_1 s_mb_20" rows="13" name="import_settings_input"></textarea>
            <input type="hidden" id="import_file" name="import_file" value="" />
            <span class="s_button s_white s_h_30 s_icon_10 s_arrow_up_10 s_mb_30 fileinput-button">
              <span>Upload exported settings</span>
              <input id="fileupload_images" type="file" name="files[]" data-url="<?php echo $tbUrl->generate('import/uploadFiles'); ?>">
            </span>
            <div id="import_files" class="tb_upload_queue"></div>
          </div>

          <div id="import_settings_help" class="tb_col tb_1_3">
            <div class="s_server_message s_msg_blue s_p_20 s_mt_60">
              <h3 class="s_icon_16 s_info_16">Import information</h3>
              <p>You can either paste the data string in the textarea, or upload the entire data package.</p>
              <p>You should use the upload form, if you have your images exported.</p>
            </div>

            <div class="s_server_message s_msg_yellow s_p_20 s_mt_20">
              <p class="s_icon_16 s_exclamation_16">It's always recommended to make a database backup before import.</p>
            </div>
          </div>

          <div class="tb_col tb_1_3 tbImportOptions" style="display: none;">
            <h2>Import options</h2>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_import_theme_settings">Settings/Styles</label>
              <label class="tb_toggle"><input id="theme_import_theme_settings" type="checkbox" name="import[]" value="settings" checked="checked" /><span></span><span></span></label>
            </div>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_import_colors">Theme colors</label>
              <label class="tb_toggle"><input id="theme_import_colors" type="checkbox" name="import[]" value="colors" checked="checked" /><span></span><span></span></label>
            </div>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_import_presets">Style presets</label>
              <label class="tb_toggle"><input id="theme_import_presets" type="checkbox" name="import[]" value="presets" checked="checked" /><span></span><span></span></label>
            </div>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_import_menu">Menu sets</label>
              <label class="tb_toggle"><input id="theme_import_menu" type="checkbox" name="import[]" value="menu" checked="checked" /><span></span><span></span></label>
            </div>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_import_builder">Builder Pages</label>
              <label class="tb_toggle"><input id="theme_import_builder" type="checkbox" name="import[]" value="builder" checked="checked" /><span></span><span></span></label>
            </div>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_import_slider">Sliders</label>
              <label class="tb_toggle"><input id="theme_import_slider" type="checkbox" name="import[]" value="slider" checked="checked" /><span></span><span></span></label>
            </div>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_import_skins">Skins</label>
              <label class="tb_toggle"><input id="theme_import_skins" type="checkbox" name="import[]" value="skins" checked="checked" /><span></span><span></span></label>
            </div>

            <div class="s_row_1 tb_live_row_1">
              <label for="theme_import_images">Images</label>
              <label class="tb_toggle"><input id="theme_import_images" type="checkbox" name="import[]" value="images" checked="checked" /><span></span><span></span></label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="s_submit clearfix">
      <a id="theme_import_submit" href="<?php echo $tbUrl->generate('import/import'); ?>" class="s_button s_red s_h_40" style="display: none;">Import</a>
    </div>

  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var $container = $("#tb_cp_panel_export");

  $container.find("> .tb_tabs").tabs({
    activate: function(event, ui) {
        tbApp.cookie.set("tbExportTabs", ui.newTab.index());
    },
    active: tbApp.cookie.get("tbExportTabs", 0)
  });

  $("#export_settings").bind("click", function() {
    $container.block();

    $.post($(this).attr("href"), $container.find(":input[name^='export']").serializeArray(), function(data) {

      $container.unblock();
      $("#export_settings_result").empty();

      if (!data.success || !data.export) {
          return false;
      }

      $("#export_settings_result").text(data.export);
      $("#export_settings_help").hide();
      $("#export_download").show();

      var newDate = new Date();
      var time_suffix = newDate.getDate() + "-" + (newDate.getMonth() + 1) + "-" + newDate.getFullYear() + "_" + newDate.getHours() + "-" + newDate.getMinutes();

      if (data.export_url) {
        $("#export_download")
                .find(".tbSaveSettings")
                .show()
                .attr("href", data.export_url)
                .attr("download", $sReg.get("/tb/Theme-Machine-Name") + "_export_" + time_suffix + ".zip");
      }

    }, "json")
        .fail(function(jqXHR) {
          $container.unblock();

          if (jqXHR.responseText.match(/Fatal error/gi) && jqXHR.responseText.match(/execution time/gi)) {
            $("#export_download").before('<div class="s_server_msg s_msg_red tbAlert"><p class="s_icon_32 s_cancel_32"><span class="tb_alert_text">The script hit the execution time limit and was interrupted by the server. It was taking too long to finish probably due to a large amount of images that need to be exported. You can either set the "max_execution_time" php directive to a higher value or copy your images manually.<br />If this message continues to appear, uncheck the "Images" option before exporting.<br />The server error message is:<br /> ' + jqXHR.responseText + '</span></p></div>');
          } else {
            displayAlertError(jqXHR.responseText);
          }
        });

    return false;
  });

  $("#export_defaults").bind("click", function() {
    var theme_id = prompt("Enter theme id");

    if (theme_id === null) {
      return false;
    }

    $container.block();

    $.post($(this).attr("href"), { theme_id: theme_id }, function() {
      $container.unblock();
    });

    return false;
  });

  $("#export_demo").bind("click", function() {

    if (!confirm("Are you sure ?")) {
      return false;
    }

    $container.block();

    $.post($(this).attr("href"), $container.find(":input[name^='export']").serializeArray(), function(data) {
      $container.unblock();
    }, "json");

    return false;
  });


  $("#theme_import_submit").bind("click", function() {
    if ($("#import_settings_input").val().trim() == "") {
      return false;
    }

    $("#theme_subpanel_import .tbAlert").remove();
    $container.block();

    $.post($(this).attr("href"), $container.find("#import_settings_input, #import_file, :input[name^='import']").serializeArray(), function(result) {

      $("#import_settings_input").val("");
      $("#import_files").empty();
      $container.find(".tbImportOptions").toggle(result.success);
      $('#import_settings_help').toggle(!result.success);
      $("#theme_import_submit").toggle(result.success);

      if (!result.success) {
        $("#theme_subpanel_import").prepend('<div class="s_server_msg s_msg_red tbAlert"><p class="s_icon_32 s_cancel_32"><span class="tb_alert_text">' + result.message + '</span></p></div>');
        $container.unblock();

        return false;
      }

      if (result.reload) {
        location.reload();
      } else {
        $container.unblock();
      }
    }, "json");

    return false;
  });

  function validateSettingsResult(result) {

    $("#fileupload_images").closest("span").toggle(result.success);
    $container.find(".tbImportOptions").toggle(result.success);
    $('#import_settings_help').toggle(!result.success);
    $("#theme_import_submit").toggle(result.success);

    if (!result.success) {
      $("#theme_subpanel_import").prepend('<div class="s_server_msg s_msg_red tbAlert"><p class="s_icon_32 s_cancel_32"><span class="tb_alert_text">' + result.message + '</span></p></div>');
      $("#import_settings_input").val("");

      return false;
    }

    var setChecked = function(properties) {
      for (var i = 0; i < properties.length; i++) {
        $("#theme_import_" + properties[i])
          .prop("checked", result.keys.indexOf(properties[i]) != -1)
          .closest("div").toggle(result.keys.indexOf(properties[i]) != -1);
      }
    };

    setChecked(["slider", "builder", "menu", "presets", "colors", "theme_settings", "skins", "images"]);

    return true;
  }

  $("#import_settings_input").on('paste', function() {
    setTimeout(function() {

      $container.find(".tbImportOptions").block();
      $.post($sReg.get("/tb/url/import/checkSettings"), {
        import_settings_input: $("#import_settings_input").val()
      }, function(result) {

        $container.find(".tbImportOptions").unblock();

        return validateSettingsResult(result);
      }, "json");

    }, 100);
    $("#theme_subpanel_import .tbAlert").remove();
  });

  var upload_settings = {

    maxChunkSize: Math.min(Number($sReg.get("/tb/post_max_size")) - 1000, 10000000),
    dataType: 'json',

    add: function (e, data) {

      $("#theme_subpanel_import .tbAlert").remove();

      var acceptFileTypes = /(\.|\/)(zip)$/i;

      if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
        $("#theme_subpanel_import").prepend('<div class="s_server_msg s_msg_red tbAlert"><p class="s_icon_32 s_cancel_32"><span class="tb_alert_text">Not an accepted file type</span></p></div>');

        return false;
      }

      $("#tb_file_images").remove();
      $("#import_files").append('<div id="tb_file_images" class="tb_file" data-file_name=""><div class="tb_actions"><h3>' + data.files[0].name+ '</h3><a class="s_icon_10 s_spam_10 tbCancel" href="">Cancel</a></div><p class="tb_progress" data-progress="0"><span class="tb_progress_bar"></span></p></div>');
      $.post($sReg.get("/tb/url/import/uploadFiles") + "&file=" + data.files[0].name + "&_method=DELETE", function() {
        data.submit();
      });
    },

    submit: function (e, data) {
      $("#import_file").val("");
      $("#tb_file_images .tbCancel").bind("click", function() {
        data.jqXHR.abort();
        data.jqXHR = null;
        $("#tb_file_images").remove();

        return false;
      });
    },

    fail: function (e, data) {
      $container.block();
      $.post($sReg.get("/tb/url/import/uploadFiles") + "&file=" + data.files[0].name + "&_method=DELETE", function() {
        $container.unblock();
      });
    },

    done: function (e, data) {
      $container.find(".tbImportOptions").block();

      $("#tb_file_images").data("file_name", data.files[0].name).find(".tbCancel").remove();
      $("#import_file").val(data.files[0].name);

      $.getJSON($sReg.get("/tb/url/import/evaluateFile") + "&file=" + data.files[0].name, function(result) {
        $container.find(".tbImportOptions").unblock();

        if (validateSettingsResult(result)) {
          $("#import_settings_input").val(result.settings);
        }
      });
    },

    progress: function (e, data) {
      var progress = parseInt(data.loaded / data.total * 100, 10);

      $("#tb_file_images .tb_progress").attr("data-progress", progress);
      $("#tb_file_images .tb_progress_bar").css("width", progress + "%");
    }
  };

  $("#fileupload_images")
    .fileupload(upload_settings)
    .prop('disabled', !$.support.fileInput)
    .parent().addClass($.support.fileInput ? undefined : 'disabled');

});
</script>
