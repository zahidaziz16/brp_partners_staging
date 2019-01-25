<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li><a href="#theme_subpanel_child_themes">Themes</a></li>
      <li><a href="#theme_subpanel_skins">Skins</a></li>
    </ul>
  </div>

  <?php // Skins Tab ?>
  <div id="theme_subpanel_skins" class="tb_subpanel">
    <h2><?php echo $theme_info['name']; ?> Skins</h2>
    <table class="s_table_1 s_mb_30" cellpadding="0" cellspacing="0">
      <thead>
      <tr>
        <th class="align_left">Name</th>
        <th class="align_left">Description</th>
        <th width="1">Actions</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($skins as $skin_id => $skin): ?>
        <tr class="s_open">
          <td class="align_left"><?php echo $skin['name']; ?></td>
          <td class="align_left"><?php echo $skin['description']; ?></td>
          <td>
            <div class="nowrap align_left">
              <a class="s_button s_white s_h_20 s_icon_10 s_tick_10 tbApplySkin" href="<?php echo $tbUrl->generate('themes/applySkin', '&skin_id=' . $skin_id . '&is_theme=' . $skin['is_theme']); ?>">Apply</a>
              <?php if (!$skin['is_theme']): ?>
              <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbDeleteSkin" href="<?php echo $tbUrl->generate('themes/deleteSkin', '&skin_id=' . $skin_id . '&is_theme=' . $skin['is_theme']); ?>">Delete</a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <a id="save_skin" href="<?php echo $tbUrl->generate('themes/saveSkin'); ?>" class="s_button s_white s_h_30 s_icon_10 s_plus_10 left">New skin</a>
    <p class="s_help">Current theme settings and styling will be saved as a new skin. Menu sets, Page Builder content and sliders are not included.</p>
  </div>

  <?php // Themes Tab ?>
  <div id="theme_subpanel_child_themes" class="tb_subpanel">
    <div class="tb_tabs tb_fly_tabs clearfix">

    <div class="tb_tabs_nav" style="visibility: hidden;">
      <ul class="tb_nav clearfix">
        <li><a href="#theme_subpanel_child_themes_installed">Installed</a></li>
        <li><a href="#theme_subpanel_child_themes_new">Add New</a></li>
      </ul>
    </div>

    <div id="theme_subpanel_child_themes_installed">
      <?php require(tb_modification($theme_area_template_dir . '/theme_themes_child.tpl')); ?>
    </div>

    <div id="theme_subpanel_child_themes_new">
      <h2>Add Theme</h2>
      <?php if (!class_exists('ZipArchive')): ?>
      <div class="s_server_msg s_msg_yellow">
        <p class="s_icon_32 s_error_32"><span class="tb_alert_text">The PHP Zip extension is not enabled on your server. It is used to upload themes. Please, contact your server administrator to resolve this issue.</span></p>
      </div>
      <?php elseif (ini_get('open_basedir')): ?>
      <div class="s_server_msg s_msg_yellow">
        <p class="s_icon_32 s_error_32"><span class="tb_alert_text">PHP open_basedir restriction in effect. The script is unable to exstract the uploaded themes. Please, contact your server administrator to resolve this issue.</span></p>
      </div>
      <?php else: ?>
      <span class="s_button s_white s_h_30 s_icon_10 s_arrow_up_10 fileinput-button">
        <span>Import package</span>
        <input id="fileupload_theme" type="file" name="files[]" data-url="<?php echo $tbUrl->generate('import/uploadFiles'); ?>">
      </span>
      <div id="import_theme_file_upload_queue" class="tb_upload_queue"></div>
      <?php endif; ?>
    </div>
  </div>
  </div>

</div>

<script type="text/javascript">
$(document).ready(function() {
  var $container = $("#tb_cp_panel_themes");

  $container.find("> .tb_tabs").tabs({
    activate: function(event, ui) {
        tbApp.cookie.set("tbThemesTabs", ui.newTab.index());
    },
    active: tbApp.cookie.get("tbThemesTabs", 0)
  });

  $("#save_skin").bind("click", function() {

    var $output = $(Mustache.render($("#common_modal_dialog_template").text(), {
      name: "skin_" + tbHelper.generateUniqueId()
    })).appendTo($("body"));
    var $promptWindow = $output.find(".sm_window").first();

    $promptWindow.css('width', 400).css('margin-left', -200).find(".sm_content").append($("#new_skin_template").text());

    $promptWindow.show().find("a.sm_closeWindowButton").add($output.find(".sm_overlayBG")).bind("click", function() {
      $promptWindow.fadeOut(300, function() {
        $promptWindow.parent("div").remove();
      });
    });

    $promptWindow.find(".tbSaveSkin").bind("click", function() {
      $promptWindow.block();

      var skin_name = $promptWindow.find("input[name='skin_name']").val();
      var skin_description = $promptWindow.find("[name='skin_description']").val();

      $.post($("#save_skin").attr("href"), {
        skin_name       : skin_name,
        skin_description: skin_description,
        is_theme        : $promptWindow.find("[name='is_theme']").is(":checked") ? 1 : 0
      } , function(data) {

        $promptWindow.unblock();
        $output.find(".sm_overlayBG").trigger("click");

        if (!data.success) {
          return false;
        }

        var row = Mustache.render($("#skin_row_template").text(), {
          skin_name       : skin_name,
          skin_description: skin_description,
          skin_id         : data.skin_id
        });

        $(row).appendTo($("#theme_subpanel_skins table"));
      }, "json");

      return false;
    });

    return false;
  });

  $container.on("click", ".tbChildThemes, .tbListAllThemes", function() {
    $("#theme_subpanel_child_themes_installed").block().load($(this).attr("href"), function() {
      $("#theme_subpanel_child_themes_installed").unblock();
    });

    return false;
  });

  $("#theme_subpanel_skins").on("click", ".tbApplySkin, .tbDeleteSkin", function() {

    if (!confirm("Are you sure?")) {
      return false;
    }

    var $button = $(this);

    $container.block();

    $.getJSON($(this).attr("href") , function(data) {
      $("#tb_warning_alert").hide();

      if (data.success == true) {

        if ($button.is(".tbDeleteSkin")) {
          $button.closest("tr").remove();
        }

        if (data.reload) {
          location.reload();
        } else {
          $container.unblock();
        }
      } else {
        $container.unblock();
        displayAlertError(data.message);
      }
    });

    return false;
  });

  $("#theme_subpanel_child_themes").find("> .tb_tabs").tabs({
    activate: function(event, ui) {
        tbApp.cookie.set("tbExportChildThemesTabs", ui.newTab.index());
    },
    active: tbApp.cookie.get("tbExportChildThemesTabs", 0)
  });

  $("#theme_subpanel_child_themes").on("click", ".tbApplyTheme, .tbResetTheme", function() {

    if (!confirm("Are you sure?")) {
      return false;
    }

    $container.block();

    $.getJSON($(this).attr("href") , function(data) {
      $("#tb_warning_alert").hide();

      if (data.success == true) {
        if (data.reload) {
          location.reload();
        }
      } else {
        $container.unblock();
        displayAlertError(data.message);
      }
    });

    return false;
  });

  $('#fileupload_theme').fileupload({
    dataType: 'json',
    add: function (e, data) {

      $("#theme_subpanel_child_themes .tbAlert").remove();

      var acceptFileTypes = /(\.|\/)(zip)$/i;

      if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
        $("#theme_subpanel_child_themes_new").find('> h2').after('<div class="s_server_msg s_msg_red tbAlert"><p class="s_icon_32 s_cancel_32"><span class="tb_alert_text">Not an accepted file type</span></p></div>');

        return false;
      }

      $("#fileupload_theme").closest("span").hide();
      $("#import_theme_file").remove();
      $("#import_theme_file_upload_queue").append('<div id="import_theme_file" class="tb_file" data-file_name=""><div class="tb_actions"><h3>' + data.files[0].name+ '</h3><a class="s_icon_10 s_spam_10 tbCancel" href="">Cancel</a></div><p class="tb_progress" data-progress="0"><span class="tb_progress_bar"></span></p></div>');
      $.post($sReg.get("/tb/url/import/uploadFiles") + "&file=" + data.files[0].name + "&_method=DELETE", function() {
        data.submit();
      });
    },
    done: function (e, data) {
      var file = data.jqXHR.responseJSON.files[0];

      setTimeout(function() {
        $container.block();
        $.get($sReg.get("/tb/url/themes/extractTheme") + "&file=" + file.name, function(data) {
          $container.unblock();
          $("#import_theme_file_upload_queue").empty();
          $("#fileupload_theme").closest("span").show();

          if (!data.success) {
            $("#theme_subpanel_child_themes_new").find('> h2').after('<div class="s_server_msg s_msg_red tbAlert"><p class="s_icon_32 s_cancel_32"><span class="tb_alert_text">' + data.message + '</span></p></div>');

            return false;
          } else {
            $("#theme_subpanel_child_themes_new").find('> h2').after('<div class="s_server_msg s_msg_green tbAlert"><p class="s_icon_32 s_accept_32"><span class="tb_alert_text"><strong>' + data.theme.name + '</strong> theme has been installed successfully!</span></p></div>');
          }

          var row = Mustache.render($("#theme_row_template").text(), {
            theme_name  : data.theme.name,
            description : data.theme.description,
            version     : data.theme.version,
            preview     : data.preview,
            theme_id    : data.theme_id
          });

          $("#theme_subpanel_child_themes_installed .tb_listing").append(row);
        }, "json");
      }, 250);
    },
    maxChunkSize: Math.min(Number($sReg.get("/tb/post_max_size")) - 1000, 10000000),
    fail: function (e, data) {
      $container.unblock();
      $("#import_theme_file_upload_queue").empty();
      $("#fileupload_theme").closest("span").show();
    },
    progress: function (e, data) {
      var progress = parseInt(data.loaded / data.total * 100, 10);

      $("#import_theme_file .tb_progress").attr("data-progress", progress);
      $("#import_theme_file .tb_progress_bar").css("width", progress + "%");
    }
  })
    .prop('disabled', !$.support.fileInput)
    .parent().addClass($.support.fileInput ? undefined : 'disabled');

});
</script>

<script type="text/template" id="theme_row_template">
  <div class="tb_item_wrap">
    <div class="tb_item tb_theme">
      <div class="tb_thumb">
        <img src="data:image/png;base64, {{preview}}" />
        <div class="tb_actions">
          <a class="s_button s_white s_h_30 s_icon_10 s_tick_10 tbApplyTheme" href="<?php echo $tbUrl->generate('themes/applyTheme', '&theme_id={{theme_id}}'); ?>">Apply</a>
        </div>
      </div>
      <div class="tb_item_info">
        <h3>{{theme_name}}</h3>
        <p class="tb_version">{{version}}</p>
        <p class="tb_description">{{description}}</p>
      </div>
    </div>
  </div>
</script>

<script type="text/template" id="skin_row_template">
  <tr class="s_open">
    <td class="align_left">{{skin_name}}</td>
    <td class="align_left">{{&skin_description}}</td>
    <td>
      <div class="nowrap align_left">
        <a class="s_button s_white s_h_20 s_icon_10 s_tick_10 tbApplySkin" href="<?php echo $tbUrl->generate('themes/applySkin', '&skin_id={{skin_id}}'); ?>">Apply</a>
        <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbDeleteSkin" href="<?php echo $tbUrl->generate('themes/deleteSkin', '&skin_id={{skin_id}}'); ?>">Delete</a>
      </div>
    </td>
  </tr>
</script>

<script type="text/template" id="new_skin_template">
<div class="s_widget_options_holder tb_cp">
  <h1 class="sm_title">New skin</span></h1>
  <div class="tb_subpanel">
    <div class="s_row_2">
      <label><strong>Name</strong></label>
      <div class="s_full clearfix">
        <input type="text" name="skin_name" />
      </div>
    </div>
    <div class="s_row_2">
      <label><strong>Description</strong></label>
      <div class="s_full clearfix">
        <textarea name="skin_description"></textarea>
      </div>
    </div>
    <!-- new_skin_options_end -->
  </div>
  <div class="s_submit">
    <a class="s_button s_red s_h_40 tbSaveSkin" href="javacript:;">Save</a>
  </div>
</div>
</script>