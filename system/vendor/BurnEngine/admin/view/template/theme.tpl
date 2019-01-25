<?php $header = TB_Utils::removeJqueryFromHTML($header, $tbData['gteOc2']);
if ($tbEngine->getConfig('admin_external_js')) {
$tags = <<<EOD
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="//cdn.ckeditor.com/4.5.11/full/ckeditor.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/4.13.0/codemirror.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/4.13.0/mode/xml/xml.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/4.13.0/mode/css/css.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/4.13.0/mode/htmlmixed/htmlmixed.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/4.13.0/mode/javascript/javascript.min.js"></script>
EOD;
} else {
$tags = <<<EOD
<script type="text/javascript" src="{$theme_admin_javascript_url}jquery/jquery.min.js"></script>
<script type="text/javascript" src="{$theme_admin_javascript_url}jquery/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="{$theme_admin_javascript_url}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$admin_javascript_url}ckeditor/ckeditor.js"></script>
EOD;
}
if ($tbEngine->getConfig('admin_google_fonts')) {
$tags .= <<<EOD
<link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,700italic&subset=latin,cyrillic" />
EOD;
} else {
$tags .= <<<EOD
<link type="text/css" rel="stylesheet" href="{$theme_admin_resource_url}font/OpenSans/style.css" />
EOD;
}
echo TB_Utils::insertAfterBase($tags, $header);
?>

<style type="text/css">
html.blocked {
  overflow: hidden;
}
#loading_screen {
  z-index: 99999;
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  width: 100%;
  padding-top: 100px;
  text-align: center;
  letter-spacing: -0.5px;
  font-size: 16px;
  font-family: "Open Sans", sans-serif;
  font-weight: 600;
  color: #333;
  background: #fff;
}
#loading_screen img {
  display: inline-block;
  margin-right: 5px;
  margin-top: 3px;
  vertical-align: top;
}
</style>
<script type="text/javascript">

var loading_screen  = '<div id="loading_screen">';
loading_screen     += '  <img />Loading...';
loading_screen     += '</div>';

var loading_image   = 'data:image/gif;base64,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';

$("html").first().addClass('blocked');
$("body").first().addClass("<?php echo $tbData['gteOc2'] ? 'tb_oc2': 'tb_oc1'; ?>").append(loading_screen);
$("#loading_screen").find('img').attr('src', loading_image);
$("#loading_screen").width($(window).width()).height($(window).height()-90);

</script>

<?php if (!empty($refresh_modifications)): ?>
<script>
$.get("<?php echo $refresh_mods_url; ?>", function() {
  $.get("<?php echo $check_maintenance_url; ?>", function() {
    location.reload();
  });
});
</script>
<?php return; ?>
<?php endif; ?>

<link rel="stylesheet" type="text/css" href="<?php echo $theme_catalog_resource_url; ?>stylesheet/font-awesome.css?v=<?php echo $tbData->theme_version; ?>" media="screen" />
<?php foreach ($additional_icons as $resource): ?>
<link rel="stylesheet" type="text/css" href="<?php echo $resource['url']; ?>icons.css" />
<?php endforeach; ?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_admin_stylesheet_url; ?>ui-lightness/ui-lightness-1.8.16.custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $theme_admin_stylesheet_url; ?>farm-fresh.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $theme_admin_stylesheet_url; ?>cp.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $theme_admin_stylesheet_url; ?>ckeditor.css" />
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/4.13.0/codemirror.min.css" />

<script type="text/javascript" src="<?php echo $theme_admin_javascript_url; ?>libs.js"></script>

<script type="text/javascript">
  var tbApp = {};
  tbApp.domInstances = [];
  tbApp.registerDomInstance = function(id, instance, remove_callback) {
    tbApp.removeDomInstance(id);
    tbApp.domInstances[id] = {
      instance:        instance,
      remove_callback: remove_callback !== undefined ? remove_callback : $.noop()
    };
  };
  tbApp.existsDomInstance = function(id) {
    return tbApp.domInstances[id] !== undefined;
  };
  tbApp.removeDomInstance = function(id) {
    if (tbApp.domInstances[id] !== undefined) {
      tbApp.domInstances[id].remove_callback.call(tbApp.domInstances[id].instance);
      $(tbApp.domInstances[id].instance).remove();
      tbApp.domInstances[id] = undefined;
    }
  };
  <?php echo $tbData->createJavascriptVars('all'); ?>
</script>

<script type="text/javascript" src="<?php echo $theme_admin_javascript_url; ?>common.js"></script>
<script type="text/javascript" src="<?php echo $theme_admin_javascript_url; ?>styles.js"></script>
<script type="text/javascript" src="<?php echo $theme_admin_javascript_url; ?>fonts.js"></script>
<script type="text/javascript" src="<?php echo $theme_admin_javascript_url; ?>store.js"></script>
<script type="text/javascript" src="<?php echo $theme_admin_javascript_url; ?>builder.js"></script>
<script type="text/javascript" src="<?php echo $theme_admin_javascript_url; ?>menu.js"></script>
<script type="text/javascript" src="<?php echo $theme_admin_javascript_url; ?>tags.js"></script>
<script type="text/javascript" src="<?php echo $theme_admin_javascript_url; ?>jquery/jquery.fileupload.js"></script>
<?php if ($tbData['gteOc2']): ?>
<script type="text/javascript" src="<?php echo $theme_admin_javascript_url; ?>oc2_common.js"></script>
<?php endif; ?>

<script type="text/javascript">

$.jStorage.flush();

<?php if (empty($uninstall_pavilion)): ?>
if (!$sReg.get("/tb/save_colors")) {
    $(window).load(function () {
        $("#loading_screen").fadeOut("normal");
        $("html").removeClass('blocked');
    });
}
<?php endif; ?>
$.blockUI.defaults.message = '<h1>Loading</h1>';

</script>

<?php echo $column_left; ?>

<div id="content">

<div id="tb_cp" class="tb_cp">

<?php if (!empty($pavilion_files)): ?>
  <?php require(tb_modification(dirname(__FILE__) . '/pavilion_upgade_files.tpl')); ?>
<?php endif; ?>

<div id="tb_error_alert" class="s_server_msg s_msg_red"<?php if(!$error_alert): ?> style="display: none"<?php endif; ?>>
  <p class="s_icon_32 s_cancel_32"><span class="tb_alert_text"><?php echo $error_alert; ?></span></p>
  <a class="s_close" href="javascript:;"></a>
</div>
<div id="tb_warning_alert" class="s_server_msg s_msg_yellow"<?php if(!$warning_alert): ?> style="display: none"<?php endif; ?>>
  <p class="s_icon_32 s_error_32"><span class="tb_alert_text"><?php echo $warning_alert; ?></span></p>
  <a class="s_close" href="javascript:;"></a>
</div>
<div id="tb_success_alert" class="s_server_msg s_msg_green"<?php if(!$success_alert): ?> style="display: none"<?php endif; ?>>
  <p class="s_icon_32 s_accept_32"><span class="tb_alert_text"><?php echo $success_alert; ?></span></p>
  <a class="s_close" href="javascript:;"></a>
</div>

<?php require(tb_modification(dirname(__FILE__) . '/theme_mods.tpl')); ?>

<div id="tb_cp_wrap">

  <div id="tb_cp_header">
    <div id="tb_cp_header_wrap" class="clearfix">
      <h1>
        <?php echo $theme_info['name']; ?> Control Panel
        <span id="tb_cp_theme_version">/BurnEngine v<?php echo $tbData->engine_version; ?>/</span>
      </h1>
      <div id="tb_store_select">
        <?php if (count($stores) >= 1): ?>
        <label class="inline"><strong>Store:</strong></label>
        <select id="oc_store">
          <option value="<?php echo $tbUrl->generate('default', '', array('store_id' => 0)); ?>"<?php if($store_id == 0) echo ' selected="selected"'; ?>>Default</option>
          <?php foreach ($stores as $store_item): ?>
          <option value="<?php echo $tbUrl->generate('default', '', array('store_id' => $store_item['store_id'])); ?>"<?php if($store_id == $store_item['store_id']) echo ' selected="selected"'; ?>><?php echo $store_item['name']; ?></option>
          <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <a id="tbLivePreviewButton" class="s_button s_white s_h_24" href="javascript:;">Live preview</a>
        <a id="tbLivePreviewCloseButton" href="javascript:;">Close Live preview</a>
      </div>
    </div>
  </div>

  <span class="clear"></span>

  <div id="tb_cp_content" class="clearfix">

    <div id="tb_cp_content_wrap" class="clearfix">

      <div id="tb_cp_panels_nav">
        <ul>
          <li title="Theme Settings" id="menu_theme_settings"><a href="#tb_cp_panel_theme_settings">Theme Settings</a></li>
          <li title="Menu Composer" id="menu_navigation" aria-controls="tb_cp_panel_navigation"><a href="<?php echo $tbUrl->generate('menu/index'); ?>">Menus</a></li>
          <li title="Layout Builder" id="menu_builder" aria-controls="tb_cp_panel_layout_builder"><a href="<?php echo $tbUrl->generate('layoutBuilder/index'); ?>">Layout Builder</a></li>
          <?php $tbData->slotFlag('theme.tabs.main.navigation'); ?>
          <li title="Import & Export" id="menu_export" aria-controls="tb_cp_panel_export"><a href="<?php echo $tbUrl->generate('export/index'); ?>">Export</a></li>
          <li title="Extensions" id="menu_extensions" aria-controls="tb_cp_panel_extensions"><a href="<?php echo $tbUrl->generate('extensions/index'); ?>">Extensions</a></li>
          <li title="Help & Support" id="menu_help"><a href="#tb_cp_panel_help">Help & Support</a></li>
        </ul>
      </div>

      <div id="tb_cp_panels">

        <?php //Theme Settings ?>
        <div id="tb_cp_panel_theme_settings">
          <div id="tb_cp_panel_theme_settings_nav" class="tb_tabs tb_subnav">
            <h1>Theme Settings</h1>
            <div class="tb_tabs_nav clearfix">
              <ul>
                <li><a href="#store_settings"><span class="s_icon_16"><span class="s_icon s_cart_16"></span><?php echo $text_menu_store; ?></span></a></li>
                <li><a <?php echo $tbGet->styleController->getSectionAttributes('index', 'style_settings'); ?>><span class="s_icon_16 tb_color_fill_16">Style</span></a></li>
                <li><a href="#color_settings_tab"><span class="s_icon_16 tb_colors_16">Colors</span></a></li>
                <li aria-controls="typography_settings"><a href="<?php echo $tbUrl->generate('fonts/index'); ?>"><span class="s_icon_16 s_style_16">Fonts</span></a></li>
                <li><a href="#background_settings"><span class="s_icon_16"><span class="s_icon s_image_16"></span>Background</span></a></li>
                <li><a href="#social_settings"><span class="s_icon_16"><span class="s_icon s_group_16"></span>Social</span></a></li>
                <li><a href="#payment_images_settings"><span class="s_icon_16"><span class="s_icon s_credit_16"></span>Payment images</span></a></li>
                <li><a href="#custom_code"><span class="s_icon_16"><span class="s_icon s_css_16"></span>Custom</span></a></li>
                <li><a href="#system_settings"><span class="s_icon_16"><span class="s_icon s_cog_16"></span>System</span></a></li>
              </ul>
            </div>
          </div>
          <div id="tb_cp_modules" class="tb_subpanels">
            <form id="tb_cp_form" action="<?php echo $tbUrl->generate('default'); ?>" method="post" autocomplete="off">
              <input type="hidden" name="store_id" value="<?php echo $store_id; ?>" />
              <div id="store_settings">
                <?php require(tb_modification(dirname(__FILE__) . '/theme_store.tpl')); ?>
              </div>
              <div id="color_settings_tab">
                <?php require(tb_modification(dirname(__FILE__) . '/theme_colors.tpl')); ?>
              </div>
              <div id="typography_settings"></div>
              <div id="style_settings">
                <?php $tbGet->styleController->renderSection('index'); ?>
              </div>
              <div id="background_settings">
                <?php require(tb_modification(dirname(__FILE__) . '/theme_background.tpl')); ?>
              </div>
              <div id="social_settings">
                <?php require(tb_modification(dirname(__FILE__) . '/theme_social.tpl')); ?>
              </div>
              <div id="payment_images_settings">
                <?php require(tb_modification(dirname(__FILE__) . '/theme_payment_images.tpl')); ?>
              </div>
              <div id="custom_code">
                <?php require(tb_modification(dirname(__FILE__) . '/theme_custom.tpl')); ?>
              </div>
              <div id="system_settings">
                <?php require(tb_modification(dirname(__FILE__) . '/theme_system_settings.tpl')); ?>
              </div>
            </form>
          </div>
        </div>

        <?php $tbData->slotFlag('theme.tabs.main.content'); ?>

        <?php //Menus ?>
        <div id="tb_cp_panel_navigation" class="clearfix"></div>

        <?php //Layout Builder ?>
        <div id="tb_cp_panel_layout_builder" class="clearfix"></div>

        <?php //Export ?>
        <div id="tb_cp_panel_export"></div>
        
        <?php //Extensions ?>
        <?php if ($show_extensions): ?>
        <div id="tb_cp_panel_extensions"></div>
        <?php endif; ?>

        <?php //Help ?>
        <div id="tb_cp_panel_help">
          <div id="tb_cp_help_wrap">
            <!-- <iframe src="http://shoppica2.com/docs/"></iframe> -->
          </div>
        </div>

      </div>

    </div>

  </div>

  <div id="tbSystemPagesMenu" style="display: none">
    <ul>
      <?php foreach ($tbData->system_pages as $parent_name => $child_pages): ?>
      <li class="tb_multiple<?php if (count($child_pages) > 12): ?> tb_wide<?php endif ?>">
        <a href="javascript:;" class="ui-menu-noselect"><?php echo ucfirst($parent_name); ?></a>
        <ul>
          <?php foreach ($child_pages as $child_name => $child_values): ?>
          <?php if ($child_values['display']): ?>
          <li><a href="javascript:;" system_id="<?php echo $child_values['route']; ?>"><?php echo $child_values['label']; ?></a></li>
          <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div id="tb_cp_footer">
  </div>

  <div id="#ajax_errorlog"></div>

</div>
</div>

</div>

<?php require(tb_modification(dirname(__FILE__) . '/javascript_templates.tpl')); ?>

<script type="text/javascript">

  $(document).ready(function () {
    tbApp.bootAdmin();
  });

  <?php if ($tbData['gteOc2']): ?>
  function image_upload(field, preview) {
    $('#modal-image').remove();

    if (window.getSelection) {
      if (window.getSelection().empty) {  // Chrome
        window.getSelection().empty();
      } else if (window.getSelection().removeAllRanges) {  // Firefox
        window.getSelection().removeAllRanges();
      }
    } else if (document.selection) {  // IE?
      document.selection.empty();
    }

    var parent_id = "img_" + tbHelper.generateUniqueId();

    $("#" + preview).parent().attr("id", parent_id);
    $.ajax({
      url: 'index.php?route=common/filemanager&token=<?php echo $tbData['token']; ?>&target=' + field + '&thumb=' + parent_id,
      dataType: 'html',
      success: function(html) {
        $('body').append('<div id="modal-image" class="modal">' + html + '</div>');
        $("#modal-image").on("click", "img", function() {
          $('#' + field).trigger("change");
        }).modal('show');
      }
    });
  }
  <?php else: ?>
  function image_upload(field, preview) {
    $('#dialog').remove();

    $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

    $('#dialog').dialog({
      title: '<?php echo $text_image_manager; ?>',
      close: function (event, ui) {
        if ($('#' + field).attr('value')) {
          $.ajax({
            url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
            dataType: 'text',
            success: function(data) {
              $('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
              $('#' + field).trigger("change");
            }
          });
        }
      },
      bgiframe: false,
      width: 700,
      height: 400,
      resizable: false,
      modal: false,
      open: function(event, ui) {
        $(event.target).parents("div.ui-dialog:first").wrap('<div class="tb_jquery_ui"></div>');
      }
    });
  }
  <?php endif; ?>

  $("#ajax_errorlog").ajaxError(function(e, jqxhr, settings, exception) {
    displayAlertError(exception + "\r\n" + jqxhr.statusText + "\r\n" + jqxhr.responseText);
    $(this).append(exception + "\r\n" + jqxhr.statusText + "\r\n" + jqxhr.responseText);
  });

  function displayAlertError(msg)
  {
    $("#tb_error_alert").show().find("span.tb_alert_text").html(msg);
  }

  function displayAlertWarning(msg)
  {
    $("#tb_warning_alert").show().find("span.tb_alert_text").html(msg);
  }

  function displayAlertSuccess(msg)
  {
    $("#tb_success_alert").show().find("span.tb_alert_text").html(msg);
  }

</script>
<?php if ($can_install_sample_data): ?>
<?php require(tb_modification(dirname(__FILE__) . '/sample_data_dialog.tpl')); ?>
<?php endif; ?>

<?php echo $footer; ?>