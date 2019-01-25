<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li aria-controls="style_settings_common"><a <?php echo $tbGet->styleController->getSectionAttributes('common', 'style_settings_common'); ?> section="common">Common</a></li>
      <li aria-controls="style_settings_wrapper"><a <?php echo $tbGet->styleController->getSectionAttributes('wrapper', 'style_settings_wrapper'); ?> section="wrapper">Wrapper</a></li>
      <li aria-controls="style_settings_header"><a <?php echo $tbGet->styleController->getSectionAttributes('header', 'style_settings_header'); ?> section="header">Header</a></li>
      <li aria-controls="style_settings_intro"><a <?php echo $tbGet->styleController->getSectionAttributes('intro', 'style_settings_intro'); ?> section="intro">Intro</a></li>
      <li aria-controls="style_settings_content"><a <?php echo $tbGet->styleController->getSectionAttributes('content', 'style_settings_content'); ?> section="content">Content wrap</a></li>
      <li aria-controls="style_settings_footer"><a <?php echo $tbGet->styleController->getSectionAttributes('footer', 'style_settings_footer'); ?> section="footer">Footer</a></li>
      <li aria-controls="style_settings_bottom"><a <?php echo $tbGet->styleController->getSectionAttributes('bottom', 'style_settings_bottom'); ?> section="bottom">Bottom</a></li>
      <li aria-controls="style_settings_presets"><a <?php echo $tbGet->styleController->getSectionAttributes('presets', 'style_settings_presets'); ?> section="presets">Presets</a></li>
      <?php $tbData->slotFlag('tb\theme_style.tabs.navigation'); ?>
    </ul>
  </div>

  <div id="style_settings_common" class="tb_subpanel">
    <?php $tbGet->styleController->renderSection('common'); ?>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit"><?php echo $text_button_save_settings; ?></a>
    </div>
  </div>

  <div id="style_settings_wrapper" class="tb_subpanel tb_has_sidebar">
    <?php $tbGet->styleController->renderSection('wrapper'); ?>
  </div>

  <div id="style_settings_header" class="tb_subpanel tb_has_sidebar">
    <?php $tbGet->styleController->renderSection('header'); ?>
  </div>

  <div id="style_settings_intro" class="tb_subpanel tb_has_sidebar">
    <?php $tbGet->styleController->renderSection('intro'); ?>
  </div>

  <div id="style_settings_content" class="tb_subpanel tb_has_sidebar">
    <?php $tbGet->styleController->renderSection('content'); ?>
  </div>

  <div id="style_settings_footer" class="tb_subpanel tb_has_sidebar">
    <?php $tbGet->styleController->renderSection('footer'); ?>
  </div>

  <div id="style_settings_bottom" class="tb_subpanel tb_has_sidebar">
    <?php $tbGet->styleController->renderSection('bottom'); ?>
  </div>

  <div id="style_settings_presets" class="tb_subpanel">
    <?php $tbGet->styleController->renderSection('presets'); ?>
  </div>

  <?php $tbData->slotFlag('tb\theme_style.tabs.content'); ?>

</div>

<script type="text/javascript">

(function ($, tbApp) {

  var initialized_sections = [];

  $(tbApp).on("tbCp:initTab-style_settings", function() {

    $('#style_common_header_layout').bind('change', function() {
      if ($(this).val() == '2_1' || $(this).val() == '2_2' || $(this).val() == '2_3' || $(this).val() == '2_4' || $(this).val() == '2_5') {
        $('#style_common_menu_height_wrap').removeClass('tb_disabled');
        $('#style_common_menu_padding_wrap').removeClass('tb_disabled');
      } else {
        $('#style_common_menu_height_wrap').addClass('tb_disabled');
        $('#style_common_menu_padding_wrap').addClass('tb_disabled');
      }

      if ($(this).val() == 3 || $(this).val() == '2_1' || $(this).val() == '2_2' || $(this).val() == '2_3' || $(this).val() == '2_4') {
        $('#tb_cp').removeClass('color_header_welcome_disabled').addClass('color_header_top_bar_disabled');
      } else {
        $('#tb_cp').removeClass('color_header_top_bar_disabled').addClass('color_header_welcome_disabled');
      }
    }).trigger("change");

    $('#style_common_header_text_logo').bind('change', function() {
      $('#tb_cp').toggleClass('color_header_site_logo_disabled', !$(this).is(":checked"));
    }).trigger("change");

    var initSection = function($tab, $panel) {

      var section = $tab.find("a").attr("section");

      if (!section || -1 != initialized_sections.indexOf(section)) {
        return;
      }

      var $section = $("#style_settings_" + section);

      if (!/[\S]/.test($section.html())) {
        return;
      }

      initialized_sections.push(section);

      if (section == "common") {
        beautifyForm($panel);
      } else {
        if (section == "content" || section == "footer" || section == "intro" || section == "header") {
          tbApp.initStyleArea(section);
        } else
        if (section == "presets") {

          var initPresetsStyling = function($presets_tab, $presets_panel) {

            var presets_section = $presets_tab.data("presets_section");

            $presets_panel.find("> .tb_tabs").tabs();
            $presets_panel.find("> .tb_tabs > .tb_tabs_nav").first().stickySidebar({
              padding: 30
            });
            $presets_tab.data("initialized", 1);

            tbApp.initBoxShadow("presets_" + presets_section, "presets[" + presets_section + "]");
            tbApp.initBackground("presets_" + presets_section, "presets[" + presets_section + "]");
            tbApp.initBorder("presets_" + presets_section);

            beautifyForm($presets_panel);
            tbApp.updatePreviewBox("presets_" + presets_section);
          };

          var loadPresetData = function($section, preset_name, preset_id) {

            var chosen = $section.find(".tbComboBox").data("uiCombobox").exportValue();

            $section.block().css('position', '');

            $.get($sReg.get('/tb/url/style/renderSection') + "&section=presets&preset_id=" + preset_id, function(data) {

              var $newSection = $(data).appendTo($section.empty().unblock());

              initPresetSection($newSection.parent());
              if (preset_id != 0) {
                $newSection.find(".tbComboBox").data("uiCombobox").importValue(chosen);
              } else {
                $newSection.find(".tbComboBox").data("uiCombobox").customValue(preset_name, 0);
              }
            });
          };

          var initPresetSection = function($section) {

            $section.find("> .tb_tabs").tabs({
              activate: function(event, ui) {
                tbApp.cookie.set("tbPresetsTabs", ui.newTab.index());
                if (ui.newTab.data("initialized")) {
                  return;
                }

                initPresetsStyling(ui.newTab, ui.newPanel);
              },
              create: function(event, ui) {
                initPresetsStyling(ui.tab, ui.panel);
              },
              active: tbApp.cookie.get("tbPresetsTabs", 0)
            });

            var comboBox = $section.find(".tbComboBox").combobox({

              select: function(event, ui) {

                var preset_name = ui.item.value;
                var preset_id = ui.item.optionValue;

                if (ui.item.optionValue == "add_new") {
                  preset_name = prompt("Please enter the name of the preset");
                  preset_id = 0;

                  if (preset_name == null) {
                    setTimeout(function() {
                      ui.context.uiInput.trigger("blur");
                    }, 10);

                    return false;
                  }
                }

                loadPresetData($section, preset_name, preset_id);
              },

              remove: function(event, ui) {
                if (confirm("Are tou sure? ")) {

                  $.get($sReg.get('/tb/url/style/removePreset') + "&preset_id=" + ui.optionValue, function() {

                    if (ui.optionValue == ui.context.value()) {
                      loadPresetData($section, "-- Select --", "");
                    }

                    $(ui.element).remove();
                  });
                }

                return false;
              }
            }).data("uiCombobox");

            if (comboBox.value() == "add_new") {
              comboBox.customValue("-- Select --");
            }

            var initColors = function($section) {
              $section.find(".colorSelector").each(function() {
                assignColorPicker($(this), $(this).hasClass("tbBackgroundColor"));
              });

              $section.find(".tbColorItem").each(function() {
                var row_id = $(this).attr("id");

                $(this).find('input[name$="[color]"]').bind("changeColor", function() {
                  $section.find('[data-parent_id="' + row_id + '"]')
                          .find('input[name$="color]"]').val($(this).val()).triggerAll("updateColor changeColor");
                });
              });
            };

            initColors($section);
            $section.find("div[id$='_typography']").each(function() {
              tbApp.initFontItems($(this));
            });


            var checkDisabledColors = function($section) {

              var $select = $section.find(".tbColorGroups");

              $select.find("option").each(function() {

                if ($(this).val() == '0') {
                  return true;
                }

                var $fieldsets = $section.find(".tbColorGroup[data-group_id='" + $(this).val() + "']");

                $(this).prop("disabled", $fieldsets.length > 0);

                if (!$fieldsets.length) {
                  return true;
                }

                $fieldsets.each(function() {
                  if (!$(this).find('> .s_actions').length) {
                    $(this).prepend('<div class="s_actions"><a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveColorGroup" href="javascript:;">Remove</a></div>');
                  }
                });
              }).end().val("0");

              $select.closest(".tbControls").toggleClass("tb_disabled", $select.find("option:not(:disabled):not([value='0'])").length == 0);
            };

            checkDisabledColors($section);

            $("#style_settings_presets_box_colors").on("click", ".tbRemoveColorGroup", function() {
              if (confirm("Are you sure?")) {
                $("#style_settings_presets_box_colors").find(".tbColorGroup[data-group_id='" + $(this).closest(".tbColorGroup").data("group_id") + "']").remove();
                checkDisabledColors($section);
              }

              return false;
            });

            var checkDisabledFonts = function() {

              var $section = $("#style_settings_presets_box_typography");
              var $select = $section.find(".tbFontGroups");

              $select.find("option").each(function() {
                var $fieldsets = $section.find(".tbFontGroup[data-group_id='" + $(this).val() + "']");

                $(this).prop("disabled", $fieldsets.length > 0);

                if (!$fieldsets.length) {
                  return true;
                }

                $fieldsets.each(function() {
                  if (!$(this).find('> .s_actions').length) {
                    $(this).prepend('<div class="s_actions"><a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveFontGroup" href="javascript:;">Remove</a></div>');
                  }
                });
              }).end().val("0");

              $select.closest(".tbControls").toggleClass("tb_disabled", $select.find("option:not(:disabled):not([value='0'])").length == 0);
            };

            checkDisabledFonts();

            $("#style_settings_presets_box_typography").on("click", ".tbRemoveFontGroup", function() {
              if (confirm("Are you sure?")) {
                $("#style_settings_presets_box_typography").find(".tbFontGroup[data-group_id='" + $(this).closest(".tbFontGroup").data("group_id") + "']").remove();
                checkDisabledFonts();
              }

              return false;
            });

            $section.find(".tbAddFontGroup").bind("click", function() {
              var optionVal = $section.find(".tbFontGroups").val();

              if (String(optionVal) == "0" || $("#fonts_group_presets_box_" + optionVal).length) {
                return false;
              }

              var self = this;

              $(this).parent(".tbControls").block();

              $.get($sReg.get('/tb/url/style/getPresetFontGroup') + "&group_id=" + optionVal, function(response) {

                $section.find("#style_settings_presets_box_typography div[id*='box_typography_language_']").each(function() {
                  var $html = $(response.replace(/\{\{language_code\}\}/g, $(this).attr("id").match(/.*_(\w{1,2})/)[1])).appendTo($(this));

                  tbApp.initFontItems($html);
                  beautifyForm($html);
                });

                checkDisabledFonts();
                $(self).parent(".tbControls").unblock();
              });

              return false;
            });

            $section.find(".tbAddColorGroup").bind("click", function() {

              var optionVal = $section.find(".tbColorGroups").val();

              if (String(optionVal) == "0" || $("#colors_group_presets_box_" + optionVal).length) {
                return false;
              }

              var self = this;

              $(this).parent(".tbControls").block();

              $.get($sReg.get('/tb/url/style/getPresetColorGroup') + "&group_id=" + optionVal, function(response) {
                var $group = $(response).insertBefore($("#style_settings_presets_box_colors > .tbControls"));

                initColors($group);
                checkDisabledColors($section);
                $(self).parent(".tbControls").unblock();
              });

              return false;
            });

          };

          initPresetSection($section);

          (function($section) {
            $section.parent().on("click", ".tbSavePreset", function () {
              $section.block({message: '<h1>Saving settings</h1>'});
              setTimeout(function () {
                tbHelper.createCallbackRegister($(tbApp)).collectEvent('tbCp:beforeSave', function () {
                  var form_data = $section.find(":input").serializeJSON();
                  var comboBox = $section.find(".tbComboBox").data("uiCombobox");
                  var is_theme = $("#preset_is_theme").is(":checked");

                  form_data.preset_record = {
                    name:     comboBox.exportValue()['custom'] !== undefined ? comboBox.exportValue()['custom'] : comboBox.label(),
                    id:       comboBox.value() ? comboBox.value() : tbHelper.generateUniqueId(),
                    is_theme: is_theme ? 1 : 0
                  };

                  $.post($("#tb_cp_form").attr("action"), $.param({form_data: JSON.stringify(form_data)}), function () {
                    $section.unblock();
                    if (!comboBox.value()) {
                      comboBox.addOption(form_data.preset_record.name, form_data.preset_record.id, false, !is_theme);
                    }
                  }, "json");
                });
              }, 50);
            });
          }($section));

          $(tbApp).on("tbCp:beforeSerialize.presets", function() {
            $section.find(':input[name^="presets"]').attr("disabled", "disabled");
          });

          $(tbApp).on("tbCp:afterSave.presets", function() {
            $section.find(':input[name^="presets"]').removeAttr("disabled");
          });
        } else {

          $section.find("> .tb_tabs").tabs({
            activate: function(event, ui) {
              tbApp.cookie.set("tbStyle" + section + "Tabs", ui.newTab.index());
            },
            create: function() {
              $panel.find("> .tb_tabs > .tb_tabs_nav").first().stickySidebar({
                padding: 30
              });
            },
            active: tbApp.cookie.get("tbStyle" + section + "Tabs", 0)
          });

          tbApp.initBoxShadow(section, "style[" + section + "]");
          tbApp.initBackground(section, "style[" + section + "]");
          tbApp.initBorder(section);

          if (section == 'bottom') {
            tbApp.initColors(section);
          }

          beautifyForm($panel);
          tbApp.updatePreviewBox(section);
        }
      }
    };

    $("#style_settings").find("> .tb_tabs").tabs({
      beforeLoad: function( event, ui ) {
        if (ui.tab.data("loaded")) {
          event.preventDefault();

          return;
        }

        $("#loading_screen").fadeOut("normal");
        $("html").removeClass('blocked');
        ui.panel.addClass("tb_loading");

        ui.jqXHR.success(function() {
          if ($sReg.get('/tb/Theme-Machine-Name') != ui.jqXHR.getResponseHeader("Theme-Machine-Name")) {
            $("body").eq(0).empty();
            if (ui.jqXHR.responseText.match(/<b>Fatal error<\/b>:/gi) || ui.jqXHR.responseText.match(/<b>Parse error<\/b>:/gi) || ui.jqXHR.responseText.match(/Stack trace:/g)) {
              $("body").html(ui.jqXHR.responseText);
            } else {
              if (ui.jqXHR.responseText.match(/<b>Notice<\/b>:/gi)) {
                console.log(ui.jqXHR.responseText);
              }
              //location.reload();
            }
          }
          ui.tab.data("loaded", true);
        });
      },
      activate: function(event, ui) {
        initSection(ui.newTab, ui.newPanel);
        tbApp.cookie.set("tbStyleTabs", ui.newTab.index());
      },
      active: tbApp.cookie.get("tbStyleTabs", 0),
      create: function(event, ui) {
        initSection(ui.tab, ui.panel);
      },
      load: function(event, ui) {
        initSection(ui.tab, ui.panel);
        $(ui.panel).removeClass("tb_loading");
      }
    });

  });

})(jQuery, tbApp);

</script>
