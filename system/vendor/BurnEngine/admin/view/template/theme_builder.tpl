<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li aria-controls="tab_content_builder"><a <?php echo $tbGet->layoutBuilderController->getSectionAttributes('content', 'tab_content_builder'); ?> section="content">Content</a></li>
      <li aria-controls="tab_header_builder"><a <?php echo $tbGet->layoutBuilderController->getSectionAttributes('header', 'tab_header_builder'); ?> section="header">Header</a></li>
      <li aria-controls="tab_footer_builder"><a <?php echo $tbGet->layoutBuilderController->getSectionAttributes('footer', 'tab_footer_builder'); ?> section="footer">Footer</a></li>
      <li aria-controls="tab_intro_builder"><a <?php echo $tbGet->layoutBuilderController->getSectionAttributes('intro', 'tab_intro_builder'); ?> section="intro">Intro</a></li>
      <li aria-controls="tab_column_left_builder"><a <?php echo $tbGet->layoutBuilderController->getSectionAttributes('column_left', 'tab_column_left_builder'); ?> section="column_left">Left column</a></li>
      <li aria-controls="tab_column_right_builder"><a <?php echo $tbGet->layoutBuilderController->getSectionAttributes('column_right', 'tab_column_right_builder'); ?> section="column_right">Right column</a></li>
    </ul>
  </div>

  <div id="tab_content_builder" class="tb_subpanel tbBuilderPanel">
    <?php $tbGet->layoutBuilderController->renderSection('content'); ?>
  </div>

  <div id="tab_header_builder" class="tb_subpanel tbBuilderPanel">
    <?php $tbGet->layoutBuilderController->renderSection('header'); ?>
  </div>

  <div id="tab_footer_builder" class="tb_subpanel tbBuilderPanel">
    <?php $tbGet->layoutBuilderController->renderSection('footer'); ?>
  </div>

  <div id="tab_intro_builder" class="tb_subpanel tbBuilderPanel">
    <?php $tbGet->layoutBuilderController->renderSection('intro'); ?>
  </div>

  <div id="tab_column_left_builder" class="tb_subpanel tbBuilderPanel">
    <?php $tbGet->layoutBuilderController->renderSection('column_left'); ?>
  </div>

  <div id="tab_column_right_builder" class="tb_subpanel tbBuilderPanel">
    <?php $tbGet->layoutBuilderController->renderSection('column_right'); ?>
  </div>

</div>

<div id="tb_cp_layout_builder_widgets_panel" class="tb_cp_side_panel">
  <div class="tb_cp_side_panel_wrap">
    <div class="tb_tabs tb_subpanel_tabs tb_side_panel_tabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#layout_builder_widgets_custom"><span class="fa-cube"></span></a></li>
          <li><a href="#layout_builder_widgets_modules"><span class="s_icon_24 s_oc_24"></span></a></li>
          <li><a href="#layout_builder_widgets_blocks"><span class="fa-cogs"></span></a></li>
          <li id="widgets_favourites_tab"><a href="#layout_builder_widgets_favourites"><span class="fa-star"></span></a></li>
        </ul>
      </div>

      <div id="layout_builder_widgets_custom" class="tb_subpanel">
        <div class="s_builder_row_widgets_listing clearfix">
          <h3>Theme blocks</h3>
          <div class="tbWidgetsList">
            <?php foreach ($widgets as $widget): ?>
            <?php if ($widget->getClassName() != 'Theme_OpenCartWidget'): ?>
            <div class="s_widget <?php echo str_replace('Theme_', 'tb', $widget->getClassName()); ?><?php if ($widget->getClassName() == 'Theme_BlockGroupWidget'): ?> tbGroupWidget<?php endif; ?> tbWidget" id_prefix="<?php echo $widget->getClassName(); ?>"<?php if (!$widget->hasArea($area_name)): ?> style="display: none;"<?php endif; ?>>
              <h3><?php echo $widget->getName(); ?></h3>
              <div class="s_widget_actions">
                <div class="s_buttons_group">
                  <?php if ($widget->getClassName() == 'Theme_GroupWidget' || $widget->getClassName() == 'Theme_BlockGroupWidget'): ?>
                  <a class="s_button s_white s_h_20 s_icon_10 s_plus_10 tbGroupWidgetOpen" href="javascript:;">&nbsp;</a>
                  <?php endif; ?>
                  <?php if ($widget->hasEditableSettings()): ?>
                  <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditWidget" href="<?php echo $tbUrl->generateJs('Widget/createForm'); ?>&class_name=<?php echo $widget->getClassName(); ?>">&nbsp;</a>
                  <?php endif; ?>
                  <a class="s_button s_white s_h_20 s_icon_10 s_copy_10 tbWidgetDuplicate" href="javascript:;">&nbsp;</a>
                  <a class="s_button_remove_widget s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;">&nbsp;</a>
                </div>
              </div>
              <input type="hidden" class="tbWidgetDirty" value="0" />
              <textarea class="widget_settings" style="display: none"><?php echo $widget->getSettingsEncoded(); ?></textarea>
              <?php if ($widget->getClassName() == 'Theme_GroupWidget' || $widget->getClassName() == 'Theme_BlockGroupWidget'): ?>
              <div class="s_widget_subwidgets"></div>
              <textarea class="tbGroupWidgetSubwidgetMap" style="display: none"><?php echo json_encode((object) $widget->getSubWidgetMap()); ?></textarea>
              <textarea class="tbGroupWidgetSectionKeys" style="display: none"><?php echo json_encode($widget->getSectionKeys()); ?></textarea>
              <textarea class="tbGroupWidgetSectionTitlesMap" style="display: none"><?php echo json_encode((object) $widget->getSectionTitlesMap()); ?></textarea>
              <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div id="layout_builder_widgets_modules" class="tb_subpanel">
        <div class="s_builder_row_widgets_listing clearfix">
          <h3>Module blocks</h3>
          <div class="tbOcWidgetsList">
            <?php $has_oc_widgets = false; foreach ($widgets as $widget): ?>
            <?php if ($widget->getClassName() == 'Theme_OpenCartWidget'): ?>
            <div class="s_widget tbWidget" id_prefix="Theme_OpenCartWidget">
              <h3><?php echo $widget->getName(); ?></h3>
              <div class="s_widget_actions">
                <div class="s_buttons_group">
                  <?php if ($widget->hasEditableSettings()): ?>
                  <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditWidget" href="<?php echo $tbUrl->generateJs('Widget/createForm'); ?>&class_name=<?php echo $widget->getClassName(); ?>">&nbsp;</a>
                  <?php endif; ?>
                  <a class="s_button s_white s_h_20 s_icon_10 s_copy_10 tbWidgetDuplicate" href="javascript:;">&nbsp;</a>
                  <a class="s_button_remove_widget s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;">&nbsp;</a>
                </div>
              </div>
              <input type="hidden" class="tbWidgetDirty" value="0" />
              <textarea class="widget_settings" style="display: none"><?php echo $widget->getSettingsEncoded(); ?></textarea>
            </div>
            <?php $has_oc_widgets = true; endif; ?>
            <?php endforeach; ?>
          </div>
          <?php if (!$has_oc_widgets): ?>
          <p class="help">Third party modules need to be <a class="tb_main_color" href="http://docs.themeburn.com/burnengine/content-blocks/opencart/" target="_blank"><strong>exported</strong></a>, before you can use them in Page Builder.</p>
          <?php endif; ?>
        </div>
      </div>

      <div id="layout_builder_widgets_blocks" class="tb_subpanel">
        <div class="s_builder_row_widgets_listing clearfix">
          <h3>System blocks</h3>
          <div class="tbSystemWidgetsList">
            <div>
              <?php echo $system_widgets_html; ?>
            </div>
          </div>
        </div>
      </div>

      <div id="layout_builder_widgets_favourites" class="tb_subpanel">
        <div class="s_builder_row_widgets_listing clearfix">
          <h3>Favourites</h3>
          <div class="tbFavouritesWidgetsList">
          <?php foreach ($favourite_widgets as $widget): ?>
          <?php if ($widget->getClassName() != 'Theme_OpenCartWidget'): ?>
            <div id="<?php echo $widget->getId(); ?>" class="s_widget <?php echo str_replace('Theme_', 'tb', $widget->getClassName()); ?><?php if ($widget->getClassName() == 'Theme_BlockGroupWidget'): ?> tbGroupWidget<?php endif; ?> tbWidget" id_prefix="<?php echo $widget->getClassName(); ?>"<?php if (!$widget->hasArea($area_name)): ?> style="display: none;"<?php endif; ?>>
              <h3><?php echo $widget->getName(); ?></h3>
              <div class="s_widget_actions">
                <div class="s_buttons_group">
                  <?php if ($widget->getClassName() == 'Theme_GroupWidget' || $widget->getClassName() == 'Theme_BlockGroupWidget'): ?>
                    <a class="s_button_add_subwidgets s_button s_white s_h_20 s_icon_10 s_plus_10" href="javascript:;">&nbsp;</a>
                  <?php endif; ?>
                  <?php if ($widget->hasEditableSettings()): ?>
                    <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditWidget" href="<?php echo $tbUrl->generateJs('Widget/createForm'); ?>&class_name=<?php echo $widget->getClassName(); ?>">&nbsp;</a>
                  <?php endif; ?>
                  <a class="s_button s_white s_h_20 s_icon_10 s_copy_10 tbWidgetDuplicate" href="javascript:;">&nbsp;</a>
                  <a class="s_button_remove_widget s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;">&nbsp;</a>
                </div>
              </div>
              <input type="hidden" class="tbWidgetDirty" value="0" />
              <textarea class="widget_settings" style="display: none"><?php echo $widget->getSettingsEncoded(); ?></textarea>
              <?php if ($widget->getClassName() == 'Theme_GroupWidget' || $widget->getClassName() == 'Theme_BlockGroupWidget'): ?>
              <div class="s_widget_subwidgets"></div>
              <textarea class="tbGroupWidgetSubwidgetMap" style="display: none"><?php echo json_encode((object) $widget->getSubWidgetMap()); ?></textarea>
              <textarea class="tbGroupWidgetSectionKeys" style="display: none"><?php echo json_encode($widget->getSectionKeys()); ?></textarea>
              <textarea class="tbGroupWidgetSectionTitlesMap" style="display: none"><?php echo json_encode((object) $widget->getSectionTitlesMap()); ?></textarea>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <?php endforeach; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<a class="tbWidgetPanelToggle" href="javascript:;">Show blocks</a>

<div class="s_submit clearfix">
  <a class="s_button s_red s_h_40 tbSaveAreaSettings">Save <span class="tbAreaName"><?php echo ucfirst(str_replace('_', ' ', $area_name)); ?></span> settings</a>
</div>

<script type="text/javascript">

$(document).ready(function() {
  
  $widget_panel = $('#tb_cp_layout_builder_widgets_panel');
  
  $widget_panel.addClass('tb_hide');
  
  $('.tbWidgetPanelToggle').bind('click', function() {
      if ($widget_panel.hasClass('tb_hide')) {
          $widget_panel.removeClass('tb_hide');
      } else {
          $widget_panel.addClass('tb_hide');
      }
  });

  var $builderPanel = $("#tb_cp_panel_layout_builder");

  $builderPanel.on("click", function(e) {
    tbApp.removeDomInstance("widget_duplicate_submenu");
  });

  $builderPanel.find("> .tb_tabs").first().tabs({

    activate: function (e, ui) {

      tbApp.removeDomInstance("widget_duplicate_submenu");

      var $systemWidgetsList = $("#layout_builder_widgets_blocks").find(".tbSystemWidgetsList");

      tbApp.cookie.set("tbLayoutBuilderTabs", {
        index:     ui.newTab.index(),
        area_name: ui.newTab.find("a").attr("section")
      });

      ui.oldTab.data("systemWidgets", $systemWidgetsList.find("> div").detach());
      if (typeof ui.newTab.data("systemWidgets") != "undefined") {
        $systemWidgetsList.append(ui.newTab.data("systemWidgets"));
      }

      if (ui.newPanel.find("> *").length) {
        $builderPanel.find(".tbSaveAreaSettings .tbAreaName").text(ui.newPanel.find(".tbAreaName").text());
      }

      ui.oldPanel.find(".tbGroupWidgetUpdate").trigger("click");

      if (ui.newTab.data("area_widget_ids")) {
        var area_widget_ids = ui.newTab.data("area_widget_ids");

        $("#layout_builder_widgets_custom, #layout_builder_widgets_favourites").find(".tbWidget").each(function() {
          $(this).toggle(area_widget_ids.indexOf($(this).attr("id_prefix")) != -1);
        });
      }
    },

    active: tbApp.cookie.getObjProp("tbLayoutBuilderTabs", "index", 0),

    create: function(event, ui) {
      tbApp.initBuilderSection(ui.tab, ui.panel);
      ui.tab.data("area_widget_ids", JSON.parse(ui.panel.find("textarea.tbAreaWidgetClasses").text()));
    },

    beforeLoad: function( event, ui ) {
      if (ui.tab.data("loaded")) {
        event.preventDefault();

        return;
      }

      $("#layout_builder_widgets_custom, #layout_builder_widgets_favourites").block();
      $("#loading_screen").fadeOut("normal");
      $("html").removeClass('blocked');
      ui.panel.addClass("tb_loading");

      ui.jqXHR.success(function() {
        if ($sReg.get('/tb/Theme-Machine-Name') != ui.jqXHR.getResponseHeader("Theme-Machine-Name")) {
          $("body").eq(0).empty();
          location.reload();
        }
        ui.tab.data("loaded", true);
      });
    },

    load: function(event, ui) {
      tbApp.initBuilderSection(ui.tab, ui.panel);
      tbApp.builderComboBoxFactory(ui.panel, ui.panel.find(".tbRowsContainer").attr("widget_area")).reloadSystemBlocks();
      ui.panel.removeClass("tb_loading");

      $builderPanel.find(".tbSaveAreaSettings .tbAreaName").text(ui.panel.find(".tbAreaName").text());

      var area_widget_ids = JSON.parse(ui.panel.find("textarea.tbAreaWidgetClasses").text());

      $("#layout_builder_widgets_custom, #layout_builder_widgets_favourites").unblock().find(".tbWidget").each(function() {
        $(this).toggle(area_widget_ids.indexOf($(this).attr("id_prefix")) != -1);
      });

      ui.tab.data("area_widget_ids", area_widget_ids);
    }
  });

  $builderPanel.find(".tbSaveAreaSettings").bind("click", function() {

    var data = {
      section: $builderPanel.find("> .tb_tabs > .tb_tabs_nav li.ui-tabs-active").attr("aria-controls")
    };

    $(tbApp).trigger("tbCp:saveBuilderTab", [data]);

    return false;
  });

  $(tbApp).on("tbCp:builderAfterSave", function(event, result, config, data, $panel) {
    $panel.find(".tbDeleteArea").show();
  });

  $widget_panel.find("div.tb_tabs").first().tabs();

  $("#layout_builder_widgets_favourites").on("click", "a.s_button_remove_widget", function() {
    if (confirm('Are you sure?')) {
      var $widget = $(this).closest(".tbWidget");

      $.get($sReg.get('/tb/url/widget/removeFromFavourites'), {
        widget_id: $widget.attr("id")
      });

      $widget.fadeOut(400, function() {
        $widget.remove();
      });
    }
  });

});
</script>

<script type="text/template" id="group_widget_template">
  <div class="s_widget s_expanded group_widget_container_interface ui-state-disabled">
    <h3>Tabs/Accordion</h3>
    <div class="s_sortable_holder tb_style_1 tbGroupWidgetSections">
    </div>
    <div class="s_save_box">
      <a class="s_button s_white s_h_24 s_icon_10 s_plus_10 left tbGroupWidgetAddGroup">Add new</a>
      <a class="s_button s_red s_h_24 tbGroupWidgetUpdate" href="#">Update</a>
    </div>
  </div>
</script>

<script type="text/template" id="block_group_widget_template">
  <div class="s_widget s_expanded group_widget_container_interface ui-state-disabled">
    <h3>Block Group</h3>
    <div class="s_sortable_holder tb_style_1 tbGroupWidgetSections">
    </div>
    <div class="s_save_box">
      <a class="s_button s_white s_h_24 s_icon_10 s_plus_10 left tbGroupWidgetAddGroup">Add column</a>
      <a class="s_button s_red s_h_24 tbGroupWidgetUpdate" href="#">Update</a>
    </div>
  </div>
</script>

<script type="text/template" id="group_widget_section_template">
  <div class="s_sortable_row s_widget_section tbGroupWidgetSection">
    <input type="hidden" name="section_hash" value="{{section_hash}}" />
    <div class="s_actions">
      <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbGroupWidgetRemoveGroup" href="javascript:;" title="Remove Tab/Accordion Section">Remove</a>
    </div>
    <h3 class="s_drag_area s_widget_section_label"><span>#<span class="tbRowTitle">{{row_title}}</span></span></h3>
    <div class="s_widget_subwidgets clearfix"></div>
  </div>
</script>

<script type="text/template" id="row_background_image_template">
  <div class="s_sortable_row background_image_row">
    <div class="s_tr">
      <div class="s_td">
        <span class="s_drag_area"></span>
        <input type="hidden" value="{{row_id}}" id="row_id_{{row_id}}"  />
        <div class="s_image_select">
          <input type="hidden" value="" id="bg_image_{{row_id}}"  />
          <img src="{{no_image}}" id="bg_preview_{{row_id}}" class="image" onclick="image_upload('bg_image_{{row_id}}', 'bg_preview_{{row_id}}');" />
        </div>
        <div class="tb_col tb_1_4 s_row_2">
          <label>Background Position</label>
          <div class="s_full clearfix">
            <div class="s_select">
              <select id="bg_position_{{row_id}}">
                <option value="top left"><?php echo $text_opt_position_1; ?></option>
                <option value="top center"><?php echo $text_opt_position_2; ?></option>
                <option value="top right"><?php echo $text_opt_position_3; ?></option>
                <option value="right"><?php echo $text_opt_position_4; ?></option>
                <option value="bottom right"><?php echo $text_opt_position_5; ?></option>
                <option value="bottom center"><?php echo $text_opt_position_6; ?></option>
                <option value="bottom left"><?php echo $text_opt_position_7; ?></option>
                <option value="left"><?php echo $text_opt_position_8; ?></option>
                <option value="center"><?php echo $text_opt_position_9; ?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="tb_col tb_1_4 s_row_2">
          <label>Background Repeat</label>
          <div class="s_full clearfix">
            <div class="s_select">
              <select id="bg_repeat_{{row_id}}">
                <option value="no-repeat"><?php echo $text_opt_repeat_1; ?></option>
                <option value="repeat-x"><?php echo $text_opt_repeat_3; ?></option>
                <option value="repeat-y"><?php echo $text_opt_repeat_4; ?></option>
                <option value="repeat"><?php echo $text_opt_repeat_2; ?></option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="s_td s_actions">
        <a href="#" class="s_button_close">Remove</a>
      </div>
    </div>
  </div>
</script>