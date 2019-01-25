<div id="group_widget_content" class="s_widget_options_holder tb_cp">

  <style id="group_widget_content_styles">
  #group_widget_content .ui-tabs-panel [data-language_code]:not([data-language_code="<?php echo $tbData->first_language_code; ?>"]) {
    display: none;
  }
  </style>

  <h1 class="sm_title"><span>Block Group</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Edit Block Group</h2>

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
              <p class="s_help">Enables the Block Group block for the current language.</p>
            </div>
          </div>
          <?php endforeach; ?>

          <fieldset>
            <legend>Row</legend>
            <div class="tb_wrap">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Columns spacing</label>
                <input id="widget_group_tabs_width" class="s_spinner" type="text" name="widget_data[columns_gutter]" value="<?php echo $settings['columns_gutter'] ?>" size="7" min="0" max="50" step="10" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Equal columns</label>
                <input type="hidden" name="widget_data[equal_columns]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[equal_columns]" value="1"<?php if($settings['equal_columns'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              </div>
            </div>
          </fieldset>

          <textarea class="tbGroupWidgetSectionTitlesData" style="display: none;"><?php echo json_encode($settings['section_titles']); ?></textarea>

          <h3>Columns</h3>

          <div class="s_sortable_holder tb_style_1 s_row_1 tbGroupWidgetSectionTitles"></div>

          <div class="clearfix">
            <a href="javascript:;" class="s_button addTabTitle s_h_30 s_white s_icon_10 s_plus_10 left s_mr_20">Add Column</a>
            <p class="s_999 s_mb_0">Empty columns will be discarded.</p>
          </div>

        </div>

      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar clearfix tbWidgetCommonOptions">
        <?php $available_options = array('layout', 'box_shadow', 'background', 'border', 'colors'); ?>
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

  var reArrangeTabTitles = function() {

    var $columns = $("#group_widget_content").find(".tbGroupWidgetSectionTitles .tbTabTitleRow");

    $columns.each(function() {
      $(this).find("span.row_order").eq(0).text(Number($columns.index(this)) + 1);
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

      var output = Mustache.render($("#block_column_template").text(), {
        key: data.key
      });

      var $row = $(output).appendTo($container.find(".tbGroupWidgetSectionTitles"));

      data.width_sm && $row.find("select[name$='[width_sm]']").val(data.width_sm);
      data.width_xs && $row.find("select[name$='[width_xs]']").val(data.width_xs);
      data.width_md && $row.find("select[name$='[width_md]']").val(data.width_md);
      data.halign && $row.find("select[name$='[halign]']").val(data.halign);
      data.valign && $row.find("select[name$='[valign]']").val(data.valign);
    };

    $container.closest(".sm_window").on("widgetEditContentsShow", function(e, widget_id) {
      $.each(JSON.parse($("#" + widget_id).find(".tbGroupWidgetSectionKeys").text()), function(section_index, section_key) {
        var section_data = {
          key:  section_key
        };

        $.each(JSON.parse($container.find(".tbGroupWidgetSectionTitlesData").text()), function(title_key, title_data) {
          if (title_key == section_key) {
            $.extend(section_data, title_data);
          }
        });

        addSectionTitle(section_data);
      });

      reArrangeTabTitles();
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

    $container.find(".addTabTitle").bind("click", function() {
      addSectionTitle({
        key:  tbHelper.generateUniqueId(5)
      });

      reArrangeTabTitles();

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

  });

})(jQuery);
</script>

<script type="text/template" id="block_column_template">
  <div class="s_sortable_row tbTabTitleRow" data-key="{{key}}">
    <div class="s_actions">
      <a class="tbRemoveRow s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;">Remove</a>
    </div>
    <h3 class="s_drag_area">
      <span>Column #<span class="row_order"></span> settings</span>
    </h3>
    <div class="tb_wrap tb_gut_30">
      <div class="s_row_2 tb_col tb_1_5">
        <label>Width phones</label>
        <div class="s_full">
          <div class="s_select">
            <select name="widget_data[section_titles][{{key}}][width_xs]">
              <option value="1">1/12</option>
              <option value="2">1/6</option>
              <option value="1-5">1/5</option>
              <option value="3">1/4</option>
              <option value="4">1/3</option>
              <option value="2-5">2/5</option>
              <option value="5">5/12</option>
              <option value="6">1/2</option>
              <option value="7">7/12</option>
              <option value="3-5">3/5</option>
              <option value="8">2/3</option>
              <option value="9">3/4</option>
              <option value="4-5">4/5</option>
              <option value="10">5/6</option>
              <option value="11">11/12</option>
              <option value="12" selected="selected">1/1</option>
              <option value="auto">Auto</option>
              <option value="fill">Fill</option>
              <option value="none">Hidden</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5">
        <label>Width tablets</label>
        <div class="s_full">
          <div class="s_select">
            <select name="widget_data[section_titles][{{key}}][width_sm]">
              <option value="1">1/12</option>
              <option value="2">1/6</option>
              <option value="1-5">1/5</option>
              <option value="3">1/4</option>
              <option value="4">1/3</option>
              <option value="2-5">2/5</option>
              <option value="5">5/12</option>
              <option value="6">1/2</option>
              <option value="7">7/12</option>
              <option value="3-5">3/5</option>
              <option value="8">2/3</option>
              <option value="9">3/4</option>
              <option value="4-5">4/5</option>
              <option value="10">5/6</option>
              <option value="11">11/12</option>
              <option value="12">1/1</option>
              <option value="auto">Auto</option>
              <option value="fill">Fill</option>
              <option value="none">Hidden</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5">
        <label>Width desktops</label>
        <div class="s_full">
          <div class="s_select">
            <select name="widget_data[section_titles][{{key}}][width_md]">
              <option value="1">1/12</option>
              <option value="2">1/6</option>
              <option value="1-5">1/5</option>
              <option value="3">1/4</option>
              <option value="4">1/3</option>
              <option value="2-5">2/5</option>
              <option value="5">5/12</option>
              <option value="6">1/2</option>
              <option value="7">7/12</option>
              <option value="3-5">3/5</option>
              <option value="8">2/3</option>
              <option value="9">3/4</option>
              <option value="4-5">4/5</option>
              <option value="10">5/6</option>
              <option value="11">11/12</option>
              <option value="12">1/1</option>
              <option value="auto">Auto</option>
              <option value="fill">Fill</option>
              <option value="none">Hidden</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5">
        <label>Align</label>
        <div class="s_full">
          <div class="s_select">
            <select name="widget_data[section_titles][{{key}}][halign]">
              <option value="start">Start</option>
              <option value="center">Center</option>
              <option value="end">End</option>
              <option value="around">Space around</option>
              <option value="between">Space between</option>
            </select>
          </div>
        </div>
      </div>
      <div class="s_row_2 tb_col tb_1_5">
        <label>Vertical align</label>
        <div class="s_full">
          <div class="s_select">
            <select name="widget_data[section_titles][{{key}}][valign]">
              <option value="top">Top</option>
              <option value="middle">Middle</option>
              <option value="bottom">Bottom</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</script>