<div id="group_widget_content" class="s_widget_options_holder tb_cp">

  <style id="group_widget_content_styles">
  #group_widget_content .ui-tabs-panel [data-language_code]:not([data-language_code="<?php echo $tbData->first_language_code; ?>"]) {
    display: none;
  }
  </style>
  <style>
  #group_widget_content [class*="opt_"],
  #group_widget_content.tb_show_opt_htabs .opt_vtabs.opt_vtabs.opt_vtabs,
  #group_widget_content.tb_show_opt_vtabs .opt_htabs.opt_htabs.opt_htabs,
  #group_widget_content.tb_show_opt_tabs_1 .opt_tabs_2.opt_tabs_2,
  #group_widget_content.tb_show_opt_tabs_1 .opt_tabs_3.opt_tabs_3,
  #group_widget_content.tb_show_opt_tabs_2 .opt_tabs_1.opt_tabs_1,
  #group_widget_content.tb_show_opt_tabs_2 .opt_tabs_3.opt_tabs_3,
  #group_widget_content.tb_show_opt_tabs_3 .opt_tabs_1.opt_tabs_1,
  #group_widget_content.tb_show_opt_tabs_3 .opt_tabs_2.opt_tabs_2
  {
    display: none;
  }
  #group_widget_content.tb_show_opt_tabs .opt_tabs,
  #group_widget_content.tb_show_opt_acco .opt_accordion,
  #group_widget_content.tb_show_opt_tabs_1 .opt_tabs_1.opt_tabs_1,
  #group_widget_content.tb_show_opt_tabs_2 .opt_tabs_2.opt_tabs_2,
  #group_widget_content.tb_show_opt_tabs_3 .opt_tabs_3.opt_tabs_3
  {
    display: block;
  }
  </style>

  <h1 class="sm_title"><span>Tabs/Accordion</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_options_holder">Display</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_title_styles_holder">Nav Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Edit Tabs/Accordion</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#widget_group_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="widget_group_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>" data-language_code="<?php echo $language_code; ?>" data-language-url="<?php echo $language['url']; ?>">
            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the Tabs/Accordion content block for the current language.</p>
            </div>
            <div class="s_row_1">
              <label><strong>Title</strong></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <div class="s_full">
                <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][title]" value="<?php echo $settings['lang'][$language_code]['title']; ?>" />
                <div class="s_text_align s_buttons_group" style="margin-left: -59px;">
                  <input id="text_title_align_left_<?php echo $language_code; ?>" class="tb_nostyle" type="radio" name="widget_data[lang][<?php echo $language_code; ?>][title_align]" value="left"<?php if ($settings['lang'][$language_code]['title_align'] == 'left') echo ' checked="checked"'; ?> />
                  <label class="s_button s_h_30 s_white s_icon_16 fa-align-left" for="text_title_align_left_<?php echo $language_code; ?>"></label>
                  <input id="text_title_align_right_<?php echo $language_code; ?>" class="tb_nostyle" type="radio" name="widget_data[lang][<?php echo $language_code; ?>][title_align]" value="right"<?php if ($settings['lang'][$language_code]['title_align'] == 'right') echo ' checked="checked"'; ?> />
                  <label class="s_button s_h_30 s_white s_icon_16 fa-align-right" for="text_title_align_right_<?php echo $language_code; ?>"></label>
                </div>
              </div>
            </div>

            <?php $row = $settings['lang'][$language_code]; ?>

            <div class="s_row_1 tbIconRow">
              <label><strong>Title Icon</strong></label>
              <div class="tbIcon s_h_30<?php if (!$row['title_icon']): ?> s_icon_holder<?php endif; ?>">
                <?php if ($row['title_icon']): ?>
                <span class="glyph_symbol <?php echo $row['title_icon']; ?>"></span>
                <?php endif; ?>
              </div>
              <?php if (!$row['title_icon']): ?>
              <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
              <?php else: ?>
              <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
              <?php endif; ?>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][title_icon]" value="<?php echo $row['title_icon']; ?>" />
              <input class="s_spinner s_ml_10" type="text" min="10" step="5" name="widget_data[lang][<?php echo $language_code; ?>][title_icon_size]" value="<?php echo $row['title_icon_size']; ?>" size="6" />
              <span class="s_metric">%</span>
              <span class="s_language_icon right"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
            </div>

          </div>
          <?php endforeach; ?>

          <textarea class="tbGroupWidgetSectionTitlesData" style="display: none;"><?php echo json_encode($settings['section_titles']); ?></textarea>

          <div class="s_sortable_holder tb_style_1 s_row_1 tbGroupWidgetSectionTitles"></div>

          <div class="clearfix">
            <a href="javascript:;" class="s_button addTabTitle s_h_30 s_white s_icon_10 s_plus_10 left s_mr_20">Add Tab/Accordion</a>
            <p class="s_999 s_mb_0">Empty tabs/accordions will not appear.</p>
          </div>

        </div>

      </div>

      <div id="widget_options_holder" class="tb_subpanel">

        <h2>Display Options</h2>

        <div class="s_row_1">
          <label><strong>Group Type</strong></label>
          <div class="s_select">
            <select name="widget_data[group_type]" id="widget_group_group_type">
              <option value="tabs"<?php if($settings['group_type'] == 'tabs') echo ' selected="selected"';?>>Tabs</option>
              <option value="accordion"<?php if($settings['group_type'] == 'accordion') echo ' selected="selected"';?>>Accordion</option>
            </select>
          </div>
        </div>

        <div class="s_row_1 opt_tabs">
          <label><strong>Tabs Direction</strong></label>
          <div class="s_select">
            <select name="widget_data[tabs_direction]" id="widget_group_tabs_direction">
              <option value="horizontal"<?php if($settings['tabs_direction'] == 'horizontal') echo ' selected="selected"';?>>Horizontal</option>
              <option value="vertical"<?php   if($settings['tabs_direction'] == 'vertical')   echo ' selected="selected"';?>>Vertical</option>
            </select>
          </div>
        </div>

        <div class="s_row_1 opt_tabs opt_vtabs">
          <label><strong>Tabs Position</strong></label>
          <div class="s_select">
            <select name="widget_data[tabs_position]" id="widget_group_tabs_position">
              <option value="left"<?php if($settings['tabs_position'] == 'left') echo ' selected="selected"';?>>Left</option>
              <option value="right"<?php if($settings['tabs_position'] == 'right') echo ' selected="selected"';?>>Right</option>
            </select>
          </div>
        </div>

        <div class="s_row_1 opt_tabs opt_vtabs">
          <div class="s_row_1 opt_tabs">
            <label><strong>Tabs Width</strong></label>
            <input id="widget_group_tabs_width" class="s_spinner" type="text" name="widget_data[tabs_width]" value="<?php echo $settings['tabs_width'] ?>" size="7" min="50" step="10" />
            <span class="s_metric">px</span>
          </div>
        </div>

        <div class="s_row_1 opt_tabs">
          <label><strong>Tabs Style</strong></label>
          <div class="s_select">
            <select name="widget_data[tabs_style]" id="widget_group_tabs_style">
              <option value="1"<?php if($settings['tabs_style'] == '1') echo ' selected="selected"';?>>Classic</option>
              <option value="2"<?php if($settings['tabs_style'] == '2') echo ' selected="selected"';?>>Inline</option>
              <option value="3"<?php if($settings['tabs_style'] == '3') echo ' selected="selected"';?>>Pills</option>
            </select>
          </div>
        </div>

        <div class="s_row_1 opt_tabs opt_htabs opt_tabs_1">
          <div class="s_row_1 opt_tabs">
            <label><strong>Justify tabs</strong></label>
            <input type="hidden" name="widget_data[tabs_justify]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="widget_data[tabs_justify]" value="1"<?php if($settings['tabs_justify'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
        </div>

        <div class="s_row_1 opt_tabs opt_htabs opt_tabs_2 opt_tabs_3">
          <label><strong>Tabs align</strong></label>
          <div class="s_select">
            <select name="widget_data[tabs_align]" id="widget_group_tabs_align">
              <option value="start"<?php  if($settings['tabs_align'] == 'start')  echo ' selected="selected"';?>>Start</option>
              <option value="center"<?php if($settings['tabs_align'] == 'center') echo ' selected="selected"';?>>Center</option>
              <option value="end"<?php    if($settings['tabs_align'] == 'end')    echo ' selected="selected"';?>>End</option>
            </select>
          </div>
        </div>

        <div class="s_row_1 opt_tabs">
          <label><strong>Tabs Transition</strong></label>
          <div class="s_select">
            <select name="widget_data[tabs_transition]" id="widget_group_tabs_transition">
              <option value="none"<?php if($settings['tabs_transition'] == 'none') echo ' selected="selected"';?>>None</option>
              <option value="fade"<?php if($settings['tabs_transition'] == 'fade') echo ' selected="selected"';?>>Fade</option>
            </select>
          </div>
        </div>

        <div class="s_row_1 opt_accordion">
          <label><strong>Accordion Style</strong></label>
          <div class="s_select">
            <select name="widget_data[accordion_style]" id="widget_group_tabs_style">
              <option value="1"<?php if($settings['accordion_style'] == '1') echo ' selected="selected"';?>>Classic</option>
              <option value="2"<?php if($settings['accordion_style'] == '2') echo ' selected="selected"';?>>Inline</option>
            </select>
          </div>
        </div>

        <div class="s_row_1 opt_accordion">
          <label><strong>Close all panels</strong></label>
          <input type="hidden" name="widget_data[accordion_closed]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="widget_data[accordion_closed]" value="1"<?php if($settings['accordion_closed'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>

        <div class="s_row_1 tbGroupAutoHeightRow">
          <label><strong>Auto height</strong></label>
          <input type="hidden" name="widget_data[auto_height]" value="0" />
          <label class="tb_toggle"><input type="checkbox" name="widget_data[auto_height]" value="1"<?php if($settings['auto_height'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>

        <div class="s_row_1">
          <label><strong>Navigation padding (left/right)</strong></label>
          <input class="s_spinner" type="text" name="widget_data[nav_padding]" value="<?php echo $settings['nav_padding'] ?>" size="7" min="0" max="50" step="5" />
          <span class="s_metric">px</span>
        </div>

        <div class="s_row_1 opt_tabs opt_tabs_3">
          <label><strong>Navigation spacing</strong></label>
          <input class="s_spinner" type="text" name="widget_data[nav_spacing]" value="<?php echo $settings['nav_spacing'] ?>" size="7" min="0" max="50" step="5" />
          <span class="s_metric">px</span>
        </div>

        <div class="s_row_1">
          <label><strong>Content padding (top/bottom)</strong></label>
          <input class="s_spinner" type="text" name="widget_data[content_padding_top]" value="<?php echo $settings['content_padding_top'] ?>" size="7" min="0" max="50" step="5" />
          <span class="s_metric">px</span>
        </div>

        <div class="s_row_1">
          <label><strong>Content padding (left/right)</strong></label>
          <input class="s_spinner" type="text" name="widget_data[content_padding_side]" value="<?php echo $settings['content_padding_side'] ?>" size="7" min="0" max="50" step="5" />
          <span class="s_metric">px</span>
        </div>

      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar clearfix tbWidgetCommonOptions">
        <?php $available_options = array('layout', 'box_shadow', 'background', 'border', 'colors'); ?>
        <?php require tb_modification(dirname(__FILE__) . '/_common_options.tpl'); ?>
      </div>

      <div id="widget_title_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <?php $style_section_id   = 'title'; ?>
        <?php $style_section_name = 'Nav'; ?>
        <?php $available_options = array('layout', 'box_shadow', 'colors', 'typography'); ?>
        <?php require tb_modification(dirname(__FILE__) . '/_common_options.tpl'); ?>
      </div>

      <div id="widget_advanced_settings_holder" class="tb_subpanel">
        <?php require tb_modification(dirname(__FILE__) . '/_advanced.tpl'); ?>
      </div>

    </div>

    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tbWidgetUpdate" href="javascript:;">Update Settings</a>
    </div>

  </form>

</div>

<script type="text/javascript">
(function ($) {

  var lang_codes = $sReg.get('/tb/language_codes');
  var languages =  $sReg.get('/tb/languages');

  var reArrangeTabTitles = function() {

    $("#group_widget_content").find(".tbGroupWidgetSectionTitles").each(function() {
      var $rows = $(this).find(" div.s_row_1");

      $rows.each(function() {
        var row_index = $rows.index(this);
        $(this).find("span.row_order").eq(0).text(parseInt(row_index) + 1);
      });
    });
  };

  $(document).ready(function() {

    var $container = $("#group_widget_content");

    $("#widget_settings_holder").find(".tbLanguageTabs").first().tabs({
      activate: function(event, ui) {
        $("#group_widget_content_styles").html('#group_widget_content .ui-tabs-panel [data-language_code]:not([data-language_code="' + ui.newPanel.data("language_code") + '"]) { display: none; }');
      }
    });

    var addSectionTitle = function(data) {

      var output = Mustache.render($("#tab_title_template").text(), {
        key:       data.key,
        icon:      data.icon,
        lang:      data.lang,
        icon_size: data.icon_size
      });

      var $row = $(output).appendTo($container.find(".tbGroupWidgetSectionTitles"));

      $row.find(".s_spinner").spinner();
    };

    $container.closest(".sm_window").on("widgetEditContentsShow", function(e, widget_id) {
      $.each(JSON.parse($("#" + widget_id).find(".tbGroupWidgetSectionKeys").text()), function(section_index, section_key) {
        var section_data = {
          key:  section_key,
          lang: []
        };
        var lang = [];

        $.each(JSON.parse($container.find(".tbGroupWidgetSectionTitlesData").text()), function(title_key, title_data) {
          if (title_key == section_key) {
            lang                   = title_data.lang;
            section_data.icon      = title_data.icon;
            section_data.icon_size = title_data.icon_size;
          }
        });

        $.each(lang_codes, function(lkey, lcode) {
          section_data.lang.push({
            title:          lang !== undefined && lang[lcode] !== undefined ? lang[lcode]["title"] : "Section " + (Number(section_index) + 1),
            language_code:  lcode,
            language_url:   languages[lcode].url,
            language_image: languages[lcode].image
          });
        });

        addSectionTitle(section_data);
      });
    });

    $(tbApp).off("tbWidget:onUpdate.groupWidget").one("tbWidget:onUpdate.groupWidget", function(event, $widget) {

      if (!$widget.is(".tbGroupWidget")) {
          return;
      }

      var section_keys = [];
      var subwidget_map = JSON.parse($widget.find(".tbGroupWidgetSubwidgetMap").text());
      var titles_map = {};

      $container.find(".tbGroupWidgetSectionTitles .tbTabTitleRow").each(function() {
        var section_key = $(this).data("key");

        section_keys.push(section_key);

        if (subwidget_map[section_key] === undefined) {
          subwidget_map[section_key] = [];
        }

        titles_map[section_key] = $(this).find("input[name$='[title]']").first().val();
      });

      $.each(subwidget_map, function(index) {
        if (-1 == section_keys.indexOf(index)) {
          delete subwidget_map[index];
        }
      });

      $widget.find(".tbGroupWidgetSectionKeys").text(JSON.stringify(section_keys));
      $widget.find(".tbGroupWidgetSubwidgetMap").text(JSON.stringify(subwidget_map));
      $widget.find(".tbGroupWidgetSectionTitlesMap").text(JSON.stringify(titles_map))
    });

    $container.find(".tbGroupWidgetSectionTitles").sortable({
      handle: ".s_drag_area",
      tolerance: "pointer"
    });

    $("#widget_group_group_type").bind("change", function() {
      $container.toggleClass('tb_show_opt_tabs', $(this).val() == 'tabs');
      $container.toggleClass('tb_show_opt_acco', $(this).val() == 'accordion');
    }).trigger("change");

    $("#widget_group_tabs_direction").bind("change", function() {
      $container.toggleClass('tb_show_opt_htabs', $(this).val() == 'horizontal');
      $container.toggleClass('tb_show_opt_vtabs', $(this).val() == 'vertical');
    }).trigger("change");

    $("#widget_group_tabs_style").bind("change", function() {
      $container.toggleClass('tb_show_opt_tabs_1', $(this).val() == 1);
      $container.toggleClass('tb_show_opt_tabs_2', $(this).val() == 2);
      $container.toggleClass('tb_show_opt_tabs_3', $(this).val() == 3);
    }).trigger("change");

    $container.find(".addTabTitle").bind("click", function() {
      var lang = [];

      $.each(lang_codes, function(key, value) {
        lang.push({
          title:          "",
          language_code:  value,
          language_url:   languages[value].url,
          language_image: languages[value].image
        });
      });

      addSectionTitle({
        lang: lang,
        key:  tbHelper.generateUniqueId(5)
      });

      reArrangeTabTitles();

      return false;
    });

    var widgetIconListReplace = function($newIcon, $activeRow) {
      $activeRow
        .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
        .find('input[name*="icon"]:hidden').val($newIcon.attr("glyph_value")).end()
        .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove").end()
        .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
        .find('input[name*="title_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
        .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
    };

    $container.on("click", ".tbChooseIcon", function() {
      if ($(this).hasClass("tbRemoveIcon")) {
        $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
          .parents(".tbIconRow").first()
          .find('input[name*="icon"]:hidden').val("").end()
          .find(".tbIcon").addClass("s_icon_holder").empty().end()
          .find('input[name*="title_icon"]:hidden').val("").end()
          .find(".tbIcon").addClass("s_icon_holder").empty();
      } else {
        tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
      }

      return false;
    });

    $container.on("click", ".tbRemoveRow", function() {

      if (confirm("Are you sure?")) {
        var $currentRow = $(this).closest(".tbTabTitleRow");
        var $rows = $currentRow.siblings(".tbTabTitleRow").addBack();

        $("#group_widget_content").find(".tbGroupWidgetSectionTitles").each (function() {
          $(this).find(".tbTabTitleRow").eq($rows.index($currentRow)).remove();
        });
        reArrangeTabTitles();
      }

      return false;
    });

    reArrangeTabTitles();

  });

})(jQuery);
</script>

<script type="text/template" id="tab_title_template">
  <div class="s_sortable_row tbTabTitleRow" data-key="{{key}}">
    <div class="s_actions">
      <a class="tbRemoveRow s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;">Remove</a>
    </div>
    <h3 class="s_drag_area">
      <span>Tab/Accordion label</span>
    </h3>
    <div class="tb_wrap tb_gut_30">
      <div class="s_row_2 tb_col tb_3_5">
        <label>Title <span class="row_order"></span></label>
        {{#lang}}
        <input type="text" name="widget_data[section_titles][{{key}}][lang][{{language_code}}][title]" value="{{title}}" data-language_code="{{language_code}}" size="44" />
        <span class="s_language_icon" data-language_code="{{language_code}}"><img src="{{language_url}}{{language_image}}" /></span>
        {{/lang}}
      </div>
      <div class="s_row_2 tb_col tbIconRow">
        <label>Icon</label>
        <div class="tbIcon s_h_26{{^icon}} s_icon_holder{{/icon}}">
          {{#icon}}
          <span class="glyph_symbol {{icon}}"></span>
          {{/icon}}
        </div>
        {{^icon}}
        <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
        {{/icon}}
        {{#icon}}
        <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
        {{/icon}}
        <input type="hidden" name="widget_data[section_titles][{{key}}][icon]" value="{{icon}}" />
      </div>
      <div class="s_row_2 tb_col">
        <label>Icon Size</label>
        <input class="s_spinner" type="text" name="widget_data[section_titles][{{key}}][icon_size]" value="{{icon_size}}" size="6" min="10" />
        <span class="s_metric">px</span>
      </div>
    </div>
  </div>
</script>