<div class="tb_subpanel tbMenuContents">
  <?php require(tb_modification(dirname(__FILE__) . '/theme_menu_contents.tpl')); ?>
</div>

<div class="s_submit clearfix">
  <a class="s_button s_red s_h_40 tbSaveMenu">Save Menu</a>
</div>

<script type="text/javascript">
  (function ($, tbApp) {

    var $container = $("#tb_cp_panel_navigation");

    var loadMenuData = function($container, menu_name, menu_id) {

      var chosen = $container.find(".tbComboBox").data("uiCombobox").exportValue();

      $container.block().css('position', '');

      menu_name = btoa(encodeURIComponent(menu_name).replace(/\(/g, "%28").replace(/\)/g, "%29"));

      $.get($sReg.get('/tb/url/menu/contents') + "&menu_name=" + menu_name + "&menu_id=" + menu_id, function(data) {

        var $newContainer = $(data).appendTo($container.unblock().find(".tbMenuContents").empty());

        initMenu($container);

        if (menu_id != 'new') {
          $newContainer.find(".tbComboBox").data("uiCombobox").importValue(chosen);
        } else {
          $newContainer.find(".tbComboBox").data("uiCombobox").customValue(menu_name, 0);
        }
      });
    };

    var initMenu = function($container) {

      beautifyForm($container);

      $container.find(".tbLanguageTabs").tabs();

      var comboBox = $container.find(".tbComboBox").combobox({

        select: function(event, ui) {

          var menu_name = ui.item.value;
          var menu_id = ui.item.optionValue;

          if (ui.item.optionValue == "add_new") {
            menu_name = prompt("Please enter the name of the preset");
            menu_id = 'new';

            if (menu_name == null) {
              setTimeout(function() {
                ui.context.uiInput.trigger("blur");
              }, 10);

              return false;
            }
          }

          loadMenuData($container, menu_name, menu_id);
        },

        remove: function(event, ui) {
          if (confirm("Are tou sure? ")) {

            $.get($sReg.get('/tb/url/menu/remove') + "&menu_id=" + ui.optionValue, function() {

              if (ui.optionValue == ui.context.value()) {
                $container.find(".tbComboBox").data("uiCombobox").value("Main Menu", true, "main");
                loadMenuData($container, "Main Menu", "main");
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

      var menu = tbApp.initMenu($container, {
        has_menu_icon:  true
      });

      $container.off("click.tbSaveMenu").on("click.tbSaveMenu", ".tbSaveMenu", function() {
        $container.block();
        menu.prepareForSave();

        $.post($sReg.get('/tb/url/menu/save'), {
          menu_data: JSON.stringify($container.find(":input[name^='menu']").serializeJSON())
        }, function(data) {
          $container.unblock();

          if (!comboBox.value()) {
            comboBox.addOption(data.menu_name, data.menu_id);
          }
        }, "json");
      });

    };

    initMenu($container);

  })(jQuery, tbApp);


</script>
