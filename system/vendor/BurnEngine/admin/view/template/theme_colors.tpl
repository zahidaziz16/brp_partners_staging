<div class="tb_subpanel tb_has_sidebar">
  <div id="colors_form_rows" class="tb_tabs clearfix">

    <div class="tb_tabs_nav s_box_1">
      <h3><?php echo $text_title_design_colors; ?></h3>
      <ul class="tb_nav clearfix">
        <li><a href="#color_settings_main_tab">Main</a></li>
        <li><a href="#color_settings_products_tab">Products</a></li>
        <li><a href="#color_settings_tables_tab">Tables</a></li>
        <li><a href="#color_settings_forms_tab">Forms</a></li>
        <li><a href="#color_settings_menu_tab">Dropdowns</a></li>
        <li><a href="#color_settings_widgets_tab">Widgets</a></li>
        <li><a href="#color_settings_gallery_tab">Gallery</a></li>
        <li><a href="#color_settings_carousel_tab">Carousel</a></li>
        <li><a href="#color_settings_sticky_tab">Sticky header</a></li>
        <li><a href="#color_settings_mobile_tab">Mobile header</a></li>
        <li><a href="#color_settings_system_tab">System</a></li>
        <li><a href="#color_settings_custom_tab">Custom</a></li>
      </ul>
    </div>

    <?php // MAIN ?>
    <div id="color_settings_main_tab">
      <h2>Main Colors</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
      <?php if ($group_key == 'main'): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
          <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // STICKY ?>
    <div id="color_settings_sticky_tab">
      <h2>Sticky Header Colors</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
      <?php if ($group_key == 'sticky_header'
                || $group_key == 'sticky_header_forms'
                || $group_key == 'sticky_header_menu'
                || $group_key == 'sticky_header_cart'
      ): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
            <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // MOBILE ?>
    <div id="color_settings_mobile_tab">
      <h2>Mobile Header (Menu) Colors</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
        <?php if ($group_key == 'mobile_header'
                || $group_key == 'mobile_tables_thead'
                || $group_key == 'mobile_tables_tbody'
                || $group_key == 'mobile_forms'
                || $group_key == 'mobile_buttons'
        ): ?>
          <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
            <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
            <div class="tb_wrap">
              <?php foreach ($group_values as $section_key => $section_values): ?>
                <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
              <?php endforeach; ?>
            </div>
          </fieldset>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // NAVIGATION ?>
    <div id="color_settings_menu_tab">
      <h2>Dropdowns</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
      <?php if ($group_key == 'dropdown_menu'
                || $group_key == 'dropdown_menu_forms'
                || $group_key == 'dropdown_menu_buttons'
                || $group_key == 'dropdown_tables_thead'
                || $group_key == 'dropdown_tables_tbody'
      ): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
          <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // PRODUCTS ?>
    <div id="color_settings_products_tab">
      <h2>Products Colors</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
      <?php if ($group_key == 'product_listing' || $group_key == 'product_listing_hover'): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
          <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // FORMS ?>
    <div id="color_settings_forms_tab">
      <h2>Main Colors</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
      <?php if ($group_key == 'forms' || $group_key == 'buttons'): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
          <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // TABLES ?>
    <div id="color_settings_tables_tab">
      <h2>Tables</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
      <?php if ($group_key == 'tables'
              || $group_key == 'tables_thead'
              || $group_key == 'tables_tbody'
              || $group_key == 'tables_zebra'
              || $group_key == 'tables_hover'
      ): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
          <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // WIDGETS ?>
    <div id="color_settings_widgets_tab">
      <h2>Widgets</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
      <?php if ($group_key == 'ui'
                || $group_key == 'ui_tabs'
                || $group_key == 'ui_accordion'
                || $group_key == 'autocomplete_ui'
                || $group_key == 'dialog_ui'
                || $group_key == 'dialog_forms'
                || $group_key == 'dialog_buttons'
      ): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
          <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // COMPONENTS ?>
    <div id="color_settings_gallery_tab">
      <h2>Gallery</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
      <?php if ($group_key == 'gallery_navigation'
                || $group_key == 'gallery_pagination'
                || $group_key == 'gallery_fullscreen_button'
                || $group_key == 'gallery_caption'
      ): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
            <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // CAROUSEL ?>
    <div id="color_settings_carousel_tab">
      <h2>Carousel</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
      <?php if ($group_key == 'carousel_nav'
                || $group_key == 'carousel_pagination'
      ): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
            <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // SYSTEM ?>
    <div id="color_settings_system_tab">
      <h2>System</h2>
      <?php foreach($colors as $group_key => $group_values): ?>
      <?php if ($group_key == 'system_messages'
                || $group_key == 'pagination'
      ): ?>
      <fieldset id="colors_group_<?php echo $group_key; ?>" class="tb_color_row">
        <legend><?php echo array_remove_key($group_values, '_label'); ?></legend>
        <div class="tb_wrap">
          <?php foreach ($group_values as $section_key => $section_values): ?>
          <?php echo $tbData->fetchTemplate('theme_colors_item', array('input_property' => "colors[$group_key][$section_key]", 'color_item' => $section_values, 'cols_num' => 5)); ?>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php // CUSTOM ?>
    <div id="color_settings_custom_tab">
      <h2>Custom Colors</h2>
      <div id="colors_group_custom">
        <?php foreach ($colors['custom'] as $section_key => $section_values): ?>
        <?php echo $tbData->fetchTemplate('theme_colors_item_custom', array('group_key' => 'custom', 'section_key' => $section_key, 'color_item' => $section_values)); ?>
        <?php endforeach; ?>
      </div>
      <div class="s_box_1 tbNewItemDiv">
        <h3>New Color Rule</h3>
        <div class="s_row_1 s_mb_20">
          <label>Title:</label>
          <input type="text" name="color_rule_label" value="" />
        </div>
        <a class="tbAddNewItem s_button s_white s_h_30 s_icon_10 s_plus_10" href="javascript:;">Add new</a>
      </div>
    </div>

  </div>
  
  <textarea id="colors_inherit_menu" style="display: none;"><?php echo json_encode($inherit_menu); ?></textarea>

  <div class="s_submit clearfix">
    <a class="s_button s_red s_h_40 tbSaveColors">Save colors</a>
  </div>

</div>

<script type="text/javascript">

(function ($, tbApp) {

  var $container = $("#colors_form_rows");

  $(tbApp).on("tbCp:initTab-color_settings_tab", function() {

    $container.tabs({
      activate: function(event, ui) {
        tbApp.cookie.set("tbDefaultColorsTabs", ui.newTab.index());
      },
      active: tbApp.cookie.get("tbDefaultColorsTabs", 0)
    });

    $container
      .find(".colorSelector").each(function() {
        assignColorPicker($(this), $(this).hasClass("tbBackgroundColor"));
      }).end()
      .find(".tbAddNewItem").bind("click", function() {
        var $label = $(this).closest(".tbNewItemDiv").find('input[name="color_rule_label"]');
        var title = $label.val().trim();

        if (title) {
          var item = Mustache.render($("#colors_custom_row_template").text(), {
            section_key: tbHelper.underscore(title + "_" + tbHelper.generateUniqueId(5).toLowerCase()),
            label:       title
          });

          $row = $(item).appendTo($("#colors_group_custom"));
          beautifyForm($row);
          assignColorPicker($row.find(".colorSelector"));
          $label.val("");
        }

        return false;
      }).end()
      .find(".tbColorItem").each(function() {
        var $row = $(this);

        $row.find('input[name$="[color]"]').bind("changeColor", function() {
          $("#colors_form_rows, #style_settings_area_content_colors, #style_settings_area_footer_colors, #style_settings_area_intro_colors, #style_settings_area_header_colors, #style_settings_bottom_colors, #style_settings_wrapper_background > .tb_background_color, #style_settings_area_header_background > .tb_background_color, #style_settings_area_intro_background > .tb_background_color, #style_settings_area_content_background > .tb_background_color, #style_settings_area_footer_background > .tb_background_color, #style_settings_area_bottom_background > .tb_background_color")
            .find('.tb_inherit[parent_id="' + $row.attr("id") + '"]')
            .find('input[name$="color]"]').val($(this).val()).triggerAll("updateColor changeColor");
        });
      }).end()
      .on("click", "a.tbRemoveItem", function() {
        if (confirm("Are you sure?")) {
          $(this).parents(".tbColorItem").first().remove();
        }

        return false;
      })
      .on("click", ".tbColorToggleInherit", function() {
        var $row = $(this).parents(".tbColorItem").first();

        if ($row.hasClass("tb_inherit")) {
          $row
            .removeClass("tb_inherit tb_disabled")
            .addClass("tb_no_inherit")
            .find('input[name$="[inherit]"]').first().val(0);
        } else
        if ($row.hasClass("tb_no_inherit")) {
          $row
            .removeClass("tb_no_inherit")
            .addClass("tb_inherit tb_disabled")
            .find('input[name$="[inherit]"]').first().val(1);

          var new_color = $("#" + $row.attr("parent_id")).find('input[name$="[color]"]').val();

          $row.find('input[name$="[color]"]').val(new_color).trigger("updateColor");

          $("#colors_form_rows, #style_settings_area_content_colors, #style_settings_area_footer_colors, #style_settings_area_intro_colors, #style_settings_area_header_colors, #style_settings_bottom_colors, #style_settings_wrapper_background > .tb_background_color, #style_settings_area_header_background > .tb_background_color, #style_settings_area_intro_background > .tb_background_color, #style_settings_area_content_background > .tb_background_color, #style_settings_area_footer_background > .tb_background_color, #style_settings_area_bottom_background > .tb_background_color")
            .find('.tb_inherit[parent_id="' + $row.attr("id") + '"]')
            .find('input[name$="color]"]').val(new_color).triggerAll("updateColor changeColor");
        }
      })
      .on("click", ".tbInheritMenuButton", function() {
        return tbApp.displayColorInheritMenu($(this), 'theme');
      })
      .parent().on("click", ".tbSaveColors", function() {
        $container.block({ message: '<h1>Saving settings</h1>' });
        $.post($("#tb_cp_form").attr("action"), $.param({form_data: JSON.stringify($container.find(":input").serializeJSON())}), function() {
          $container.unblock();
        }, "json");
      })
      .on("click", function() {
        tbApp.removeDomInstance("colors_inherit_menu");
      });

    beautifyForm($("#colors_group_custom"));
  });

  $(tbApp).on("tbCp:beforeSerialize.global_colors", function(e, $form) {
    $container.find(':input[name^="colors"]').attr("disabled", "disabled");
  });

  $(tbApp).on("tbCp:afterSave.global_colors", function(e, data, $form) {
    $container.find(':input[name^="colors"]').removeAttr("disabled");
  });

})(jQuery, tbApp);

</script>

<script type="text/template" id="colors_custom_row_template">
<?php
echo $tbData->fetchTemplate('theme_colors_item_custom', array(
  'section_key' => "{{section_key}}",
  'color_item'  => array(
    'label'       => '{{label}}',
    'elements'    => 'a',
    'property'    => 'color',
    'color'       => '#ff0000',
    'important'   => 0,
    'inherit'     => 0,
  )
));
?>
</script>