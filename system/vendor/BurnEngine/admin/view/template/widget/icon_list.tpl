<div id="widget_icon_list" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Icon List</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_layout_holder">Icons layout</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_title_styles_holder">Title Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">
        
        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>
          
          <h2>Edit Icon List</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#icon_list_widget_language_<?php echo $language['code']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="icon_list_widget_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?> tbLanguagePanel">
            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the Icon list content block for the current language.</p>
            </div>

            <div class="s_row_1">
              <label><strong><?php echo $text_label_footer_info_title; ?></strong></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <div class="s_full">
                <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][title]" value="<?php echo $settings['lang'][$language_code]['title']; ?>" />
                <div class="s_text_align s_buttons_group">
                  <input id="text_title_align_left_<?php echo $language_code; ?>" class="tb_nostyle" type="radio" name="widget_data[lang][<?php echo $language_code; ?>][title_align]" value="left"<?php if ($settings['lang'][$language_code]['title_align'] == 'left') echo ' checked="checked"'; ?> />
                  <label class="s_button s_h_30 s_white s_icon_16 fa-align-left" for="text_title_align_left_<?php echo $language_code; ?>"></label>
                  <input id="text_title_align_center_<?php echo $language_code; ?>" class="tb_nostyle" type="radio" name="widget_data[lang][<?php echo $language_code; ?>][title_align]" value="center"<?php if ($settings['lang'][$language_code]['title_align'] == 'center') echo ' checked="checked"'; ?> />
                  <label class="s_button s_h_30 s_white s_icon_16 fa-align-center" for="text_title_align_center_<?php echo $language_code; ?>"></label>
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

            <div class="s_row_1">
              <div class="s_sortable_holder tb_style_1 tbIconListContainer"><?php $j = 0; foreach ($settings['lang'][$language_code]['rows'] as $i => $row): ?><div class="s_sortable_row tbIconListRow">
                  <div class="s_actions">
                    <div class="s_buttons_group">
                      <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_copy_10 tbDuplicateRow"></a>
                      <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveRow"></a>
                    </div>
                  </div>
                  <a class="tb_toggle_row" href="javascript:;">Toggle</a>
                  <h3 class="s_drag_area"><span>Row <span class="row_order"><?php echo $j+1; ?></span></span></h3>
                  <div class="tb_wrap">
                    <div class="tb_col tb_1_3">
                      <div class="s_row_1 tbIconRow">
                        <label>Icon</label>
                        <div class="tbIcon s_h_26<?php if (!$row['glyph_value']): ?> s_icon_holder<?php endif; ?>">
                          <?php if ($row['glyph_value']): ?>
                          <span class="glyph_symbol <?php echo $row['glyph_value']; ?>"></span>
                          <?php endif; ?>
                        </div>
                        <?php if (!$row['glyph_value']): ?>
                        <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
                        <?php else: ?>
                        <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
                        <?php endif; ?>
                        <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][rows][<?php echo $i; ?>][glyph_value]" value="<?php echo $row['glyph_value']; ?>" />
                      </div>
                      <div class="s_row_1">
                        <label>Icon color</label>
                        <div class="colorSelector"><div style="background-color: <?php echo $row['glyph_color']; ?>;"></div></div>
                        <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][rows][<?php echo $i; ?>][glyph_color]" value="<?php echo $row['glyph_color']; ?>" />
                      </div>
                      <div class="s_row_1">
                        <label>Box/border color</label>
                        <div class="colorSelector tbBackgroundColor"><div style="background-color: <?php echo $row['box_color']; ?>;"></div></div>
                        <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][rows][<?php echo $i; ?>][box_color]" value="<?php echo $row['box_color']; ?>" />
                      </div>
                      <div class="s_row_1">
                        <label>Icon size</label>
                        <input class="s_spinner glyph_size" type="text" size="6" min="8" name="widget_data[lang][<?php echo $language_code; ?>][rows][<?php echo $i; ?>][glyph_size]" value="<?php echo $row['glyph_size']; ?>" />
                        <span class="s_metric">px</span>
                      </div>
                      <div class="s_row_1">
                        <label>Box size</label>
                        <input class="s_spinner" type="text" size="6" min="10" name="widget_data[lang][<?php echo $language_code; ?>][rows][<?php echo $i; ?>][box_size]" value="<?php echo $row['box_size']; ?>" />
                        <span class="s_metric">px</span>
                      </div>
                      <div class="s_row_1">
                        <label>Icon Style</label>
                        <div class="s_full">
                          <div class="s_select">
                            <select name="widget_data[lang][<?php echo $language_code; ?>][rows][<?php echo $i; ?>][icon_style]">
                              <option value="1"<?php if ($row['icon_style'] == '1'): ?> selected="selected"<?php endif; ?>><?php echo $tbEngine->getThemeInfo('name'); ?></option>
                              <option value="2"<?php if ($row['icon_style'] == '2'): ?> selected="selected"<?php endif; ?>>Round</option>
                              <option value="3"<?php if ($row['icon_style'] == '3'): ?> selected="selected"<?php endif; ?>>Plain</option>
                              <option value="4"<?php if ($row['icon_style'] == '4'): ?> selected="selected"<?php endif; ?>>Square Bordered</option>
                              <option value="5"<?php if ($row['icon_style'] == '5'): ?> selected="selected"<?php endif; ?>>Round Bordered</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tb_col tb_2_3">
                      <div class="s_row_1 s_mb_20">
                        <label><strong>Icon Link</strong></label>
                        <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][rows][<?php echo $i; ?>][url]" value="<?php echo $row['url']; ?>" size="39" />
                        <span class="s_metric">
                          <select name="widget_data[lang][<?php echo $language_code; ?>][rows][<?php echo $i; ?>][url_target]">
                            <option value="_self"<?php if($row['url_target'] == '_self') echo ' selected="selected"';?>>_self</option>
                            <option value="_blank"<?php if($row['url_target'] == '_blank') echo ' selected="selected"';?>>_blank</option>
                          </select>
                        </span>
                      </div>
                      <div class="s_row_1">
                        <textarea id="icon_list_widget_row_text_<?php echo $language_code; ?>_<?php echo $i; ?>" class="tbCKE" name="widget_data[lang][<?php echo $language_code; ?>][rows][<?php echo $i; ?>][text]" cols="30"><?php echo $row['text']; ?></textarea>
                      </div>
                    </div>
                  </div>
                </div><?php $j++; endforeach; ?></div>

              <a href="#" class="tbAddIconRow s_button s_white s_h_30 s_icon_10 s_plus_10 right" language_code="<?php echo $language_code; ?>">Add Icon</a>

            </div>
            <span class="clear"></span>

          </div>
          <?php endforeach; ?>

        </div>
      </div>

      <div id="widget_layout_holder" class="tb_subpanel">
        <h2>Icon List Layout</h2>
        <div class="s_row_1">
          <label>Display</label>
          <div class="s_select">
            <select id="widget_icon_list_display" name="widget_data[display]">
              <option value="list"<?php if($settings['display'] == 'list') echo ' selected="selected"';?>>List</option>
              <option value="grid"<?php if($settings['display'] == 'grid') echo ' selected="selected"';?>>Grid</option>
              <option value="inline"<?php if($settings['display'] == 'inline') echo ' selected="selected"';?>>Inline</option>
            </select>
          </div>
        </div>
        <div class="s_row_1 opt_column_spacing">
          <label>Spacing between</label>
          <input class="s_spinner" name="widget_data[grid_gut]" type="text" value="<?php echo $settings['grid_gut']; ?>" min="0" max="100" step="5" size="6" />
          <span class="s_metric">px</span>
        </div>
        <div class="s_row_1 opt_restrictions">
          <label>Distribution</label>
          <div class="s_full clearfix">
            <table class="tb_product_elements s_table_1" cellspacing="0">
              <thead>
              <tr class="s_open">
                <th width="123">
                  <label><strong>Container width</strong></label>
                </th>
                <th class="align_left" width="123">
                  <label><strong>Items per row</strong></label>
                </th>
                <th class="align_left" width="123">
                  <label><strong>Spacing</strong></label>
                </th>
                <th class="align_left">
                </th>
              </tr>
              </thead>
              <tbody class="tbItemsRestrictionsWrapper">
              <?php $i = 0; ?>
              <?php foreach ($settings['restrictions'] as $row): ?>
              <tr class="s_open s_nosep tbItemsRestrictionRow">
                <td>
                  <input class="s_spinner" type="text" name="widget_data[restrictions][<?php echo $i; ?>][max_width]" value="<?php echo $row['max_width']; ?>" min="100" step="10" size="7" />
                  <span class="s_metric">px</span>
                </td>
                <td class="align_left">
                  <input class="s_spinner" type="text" name="widget_data[restrictions][<?php echo $i; ?>][items_per_row]" value="<?php echo $row['items_per_row']; ?>" min="1" max="12" size="5" />
                </td>
                <td class="align_left">
                  <input class="s_spinner" type="text" name="widget_data[restrictions][<?php echo $i; ?>][items_spacing]" value="<?php echo $row['items_spacing']; ?>" step="10" min="0" max="50" size="5" />
                  <span class="s_metric">px</span>
                </td>
                <td class="align_right">
                  <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveItemsRestrictionRow" href="javascript:;"></a>
                </td>
              </tr>
              <?php $i++; ?>
              <?php endforeach; ?>
              </tbody>
            </table>
            <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 s_mt_20 tbAddItemsRestrictionRow" href="javascript:;">Add rule</a>
          </div>
        </div>
        <div class="s_row_1 opt_desc_position">
          <label>Description position</label>
          <div class="s_select">
            <select id="widget_icon_list_description_position" name="widget_data[description_position]">
              <option value="right"<?php if($settings['description_position'] == 'right') echo ' selected="selected"';?>>Right</option>
              <option value="left"<?php if($settings['description_position'] == 'left') echo ' selected="selected"';?>>Left</option>
              <option class="opt_display_grid" value="bottom"<?php if($settings['description_position'] == 'bottom') echo ' selected="selected"';?>>Below the icon</option>
              <option class="opt_display_inline" value="tooltip"<?php if($settings['description_position'] == 'tooltip') echo ' selected="selected"';?>>Put the description in a tooltip</option>
            </select>
          </div>
        </div>
        <div class="opt_icon_valign">
          <span class="clear"></span>
          <div class="s_row_1 opt_description_position_side">
            <label>Icon vertical align</label>
            <div class="s_select">
              <select id="widget_icon_list_icon_valign" name="widget_data[icon_valign]">
                <option value="top"<?php if($settings['icon_valign'] == 'top') echo ' selected="selected"';?>>Top</option>
                <option value="middle"<?php if($settings['icon_valign'] == 'middle') echo ' selected="selected"';?>>Middle</option>
              </select>
            </div>
          </div>
        </div>
        <div class="s_row_1 opt_text_align">
          <label>Description text align</label>
          <div class="s_select">
            <select id="widget_icon_list_text_align" name="widget_data[text_align]">
              <option value="left"<?php if($settings['text_align'] == 'left') echo ' selected="selected"';?>>Left</option>
              <option value="center"<?php if($settings['text_align'] == 'center') echo ' selected="selected"';?>>Center</option>
              <option value="right"<?php if($settings['text_align'] == 'right') echo ' selected="selected"';?>>Right</option>
            </select>
          </div>
        </div>
        <div class="s_row_1 opt_display_inline">
          <label>Icons align</label>
          <div class="s_select">
            <select id="widget_icon_list_icons_align" name="widget_data[icons_align]">
              <option value="left"<?php if($settings['icons_align'] == 'left') echo ' selected="selected"';?>>Left</option>
              <option value="center"<?php if($settings['icons_align'] == 'center') echo ' selected="selected"';?>>Center</option>
              <option value="right"<?php if($settings['icons_align'] == 'right') echo ' selected="selected"';?>>Right</option>
              <option value="justify"<?php if($settings['icons_align'] == 'justify') echo ' selected="selected"';?>>Justify</option>
            </select>
          </div>
        </div>
      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <?php $layout_display = true; ?>
        <?php require tb_modification(dirname(__FILE__) . '/_common_options.tpl'); ?>
      </div>

      <div id="widget_title_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <?php $style_section_id = 'title'; ?>
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

function initIconListWidgetRow($row) {

  // Color Picker Init
  $row.find("div.colorSelector").each(function() {
    assignColorPicker($(this), $(this).hasClass("tbBackgroundColor"));
  });

  // Spinner Init
  $row.find("input.s_spinner").each(function() {
    $(this).spinner({
      step: 1,
      mouseWheel: true
    });
  });

  beautifyForm($row);
}

function simple_accordion($row) {
  $row.find(".tb_toggle_row").bind("click", function() {
    if ($row.hasClass("tb_closed")) {
      $("#widget_settings_holder .s_sortable_row").removeClass("tb_opened").addClass("tb_closed");
      $("#widget_settings_holder .s_sortable_row .tb_wrap").slideUp();
      $row.find(".tb_wrap").slideDown();
      $row.removeClass("tb_closed");
      $row.addClass("tb_opened");
    }
  })
}

function addIconRow(language_code, row_num) {
  var output = Mustache.render($("#icon_list_widget_row_template").text(), {
    uid:           tbHelper.generateUniqueId(5),
    language_code: language_code,
    row_num:       row_num
  });

  var $row = $(output).appendTo("#icon_list_widget_language_" + language_code + " div.s_sortable_holder");

  initRichEditor($row.find("textarea").attr("id"));
  simple_accordion($row);
  $row.find(".tb_toggle_row").trigger("click");

  return $row;
}

function initRichEditor(element_id) {
  CKEDITOR.replace(element_id, {
    height: 138,
    customConfig:              '<?php echo $theme_admin_javascript_relative_url; ?>ckeditor/custom/config.js',
    contentsCss:               '<?php echo $theme_admin_javascript_relative_url; ?>ckeditor/custom/styles.css',
    filebrowserBrowseUrl:      '<?php echo $tbData['fileManagerUrl']; ?>',
    filebrowserImageBrowseUrl: '<?php echo $tbData['fileManagerUrl']; ?>',
    filebrowserImageUploadUrl: null
  });
}

$(document).ready(function() {
  $("#widget_settings_holder").find(".tbLanguageTabs").first().tabs();
  $("#widget_settings_holder").find("div.s_sortable_holder").sortable({
      handle: ".s_drag_area",
      tolerance: "pointer",
      start: function(event, ui) {
        CKEDITOR.instances[ui.item.find("textarea").attr("id")].destroy();
      },
      stop: function(event, ui) {
        initRichEditor(ui.item.find("textarea").attr("id"));
      }
    })
    .find("textarea").each(function() {
      initRichEditor($(this).attr("id"));
    }).end()
    .find(".s_sortable_row").each(function() {
      simple_accordion($(this));
      $(this).addClass("tb_closed");
    }).end()
    .find(".s_sortable_row .tb_wrap").hide().end()
    .find(".s_sortable_row:first-child .tb_wrap").show().end()
    .find(".s_sortable_row:first-child").addClass("tb_opened").removeClass("tb_closed");

  var $widget_icon_list = $("#widget_icon_list");
  var $icon_list_widget_form = $widget_icon_list.find("> form").eq(0);

  tbApp.initRestrictionRows($widget_icon_list, "widget_data");

  var widgetIconListReplace = function($newIcon, $activeRow) {
    $activeRow
      .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
      .find('input[name*="glyph_value"]:hidden').val($newIcon.attr("glyph_value")).end()
      .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove").end()
      .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
      .find('input[name*="title_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
      .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
  };

  $("#widget_settings_holder").on("click", ".tbChooseIcon", function() {
    if ($(this).hasClass("tbRemoveIcon")) {
      $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
        .parents(".tbIconRow").first()
        .find('input[name*="glyph_value"]:hidden').val("").end()
        .find(".tbIcon").addClass("s_icon_holder").empty().end()
        .find('input[name*="title_icon"]:hidden').val("").end()
        .find(".tbIcon").addClass("s_icon_holder").empty();
    } else {
      tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
    }

    return false;
  });

  $icon_list_widget_form.on("click", ".tbRemoveRow", function() {
    if (confirm("Are you sure?")) {
      $(this).parents(".tbIconListRow").eq(0).remove();
    }

    return false;
  });

  $icon_list_widget_form.find(".tbIconListRow").each(function() {
    initIconListWidgetRow($(this));
  });

  var num_rows = {};

  $.each($sReg.get('/tb/language_codes'), function(key, value) {
    num_rows[value] = $("#icon_list_widget_language_" + value).find(".tbIconListRow").length;
  });

  $widget_icon_list.find(".tbAddIconRow").bind("click", function() {

    var language_code = $(this).attr("language_code");
    var $new_row = addIconRow(language_code, ++num_rows[language_code]);

    initIconListWidgetRow($new_row);

    return false;
  });

  $widget_icon_list.on("click", ".tbDuplicateRow", function() {

    if (!confirm("Are you sure?")) {
      return false;
    }

    $(this).parents(".tbLanguagePanel").find(".tbAddIconRow").trigger("click");

    var $newRow = $(this).parents(".tbIconListContainer").first().find(".tbIconListRow").last();

    $(this).parents(".tbIconListRow").find(":input").each(function() {
      var name = $(this).attr("name").match(/.*\[(.*)\]/);

      if (null !== name) {
        $newRow.find(':input[name$="[' + name[1] + ']"]').val($(this).val());
      }
    });

    $newRow.find(":input[name$='color]']").trigger("updateColor");

    return false;
  });

  var $elf = function($el) {
    return $widget_icon_list.find($el);
  };

  var $description_position = $elf('select[name$="[description_position]"]');

  // Toggle Options
  $description_position.bind("change", function() {

    var val = $(this).val();

    $elf(".opt_description_position_side").toggle($("#widget_icon_list_display").val() != "inline" && (val == "left" || val == "right"));
    $elf(".opt_text_align").toggle(val == "bottom" && ($("#widget_icon_list_display").val() == "grid"));
  }).trigger("change");

  $("#widget_icon_list_display").bind("change", function() {

    var val = $(this).val();

    $elf(".opt_display_grid").toggle(val == "grid");
    $elf(".opt_column_spacing").toggle(val == 'inline' || val == 'list');
    $elf(".opt_restrictions").toggle(val == "grid");
    $elf(".opt_display_inline").toggle(val == "inline");
    $elf(".opt_description_position_side").toggle(val != "inline");
    $elf(".opt_text_align").toggle(val == "grid" && $description_position.val() == 'bottom');

    if ((val != "inline" && $description_position.val() == 'tooltip') || (val != "grid" && $description_position.val() == 'bottom')) {
      $description_position
        .find("option").removeAttr("selected").end()
        .find("option").first().attr("selected", "selected").end()
        .trigger("change");
    }
  }).trigger("change");

});

})(jQuery);
</script>

<script type="text/template" id="icon_list_widget_row_template">
  <div class="s_sortable_row tbIconListRow tb_closed">
    <div class="s_actions">
      <div class="s_buttons_group">
        <a href="javascript:;" class="tbDuplicateRow s_button s_white s_h_20 s_icon_10 s_copy_10"></a>
        <a href="javascript:;" class="tbRemoveRow s_button s_white s_h_20 s_icon_10 s_delete_10"></a>
      </div>
    </div>
    <a class="tb_toggle_row" href="javascript:;">Toggle</a>
    <h3 class="s_drag_area"><span>Row <span class="row_order">{{row_num}}</span></span></h3>
    <div class="tb_wrap" style="display: none;">
      <div class="tb_col tb_1_3">
        <div class="s_row_1 tbIconRow">
          <label>Icon</label>
          <div class="tbIcon s_icon_holder s_h_26"></div>
          <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
          <input type="hidden" name="widget_data[lang][{{language_code}}][rows][{{uid}}][glyph_value]" />
        </div>
        <div class="s_row_1">
          <label>Icon color</label>
          <div class="colorSelector"><div style="background-color: #333333;"></div></div>
          <input type="text" name="widget_data[lang][{{language_code}}][rows][{{uid}}][glyph_color]" value="#333333" />
        </div>
        <div class="s_row_1">
          <label>Box/border color</label>
          <div class="colorSelector tbBackgroundColor colorpicker_no_color"><div></div></div>
          <input type="text" name="widget_data[lang][{{language_code}}][rows][{{uid}}][box_color]" value="" />
        </div>
        <div class="s_row_1">
          <label>Icon size</label>
          <input class="s_spinner" type="text" size="5" min="8" name="widget_data[lang][{{language_code}}][rows][{{uid}}][glyph_size]" value="14" />
          <span class="s_metric">px</span>
        </div>
        <div class="s_row_1">
          <label>Box size</label>
          <input class="s_spinner glyph_size" type="text" size="5" min="10" name="widget_data[lang][{{language_code}}][rows][{{uid}}][box_size]" value="20" />
          <span class="s_metric">px</span>
        </div>
        <div class="s_row_1">
          <label>Icon Style</label>
          <div class="s_full">
            <div class="s_select">
              <select name="widget_data[lang][{{language_code}}][rows][{{uid}}][icon_style]">
                <option value="1"><?php echo $tbEngine->getThemeInfo('name'); ?></option>
                <option value="2">Round</option>
                <option selected="selected" value="3">Plain</option>
                <option value="4">Square Bordered</option>
                <option value="5">Round Bordered</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="tb_col tb_2_3">
        <div class="s_row_1 s_mb_20">
          <label><strong>Icon Link</strong></label>
          <input type="text" name="widget_data[lang][{{language_code}}][rows][{{uid}}][url]" size="39" />
          <span class="s_metric">
            <select name="widget_data[lang][{{language_code}}][rows][{{uid}}][url_target]">
              <option>_self</option>
              <option>_blank</option>
            </select>
          </span>
        </div>
        <div class="s_row_1">
          <textarea id="icon_list_widget_row_text_{{uid}}" class="tbCKE" name="widget_data[lang][{{language_code}}][rows][{{uid}}][text]" cols="30"></textarea>
        </div>
      </div>
    </div>
  </div>
</script>
