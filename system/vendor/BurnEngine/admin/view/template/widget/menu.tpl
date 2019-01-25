<div id="menu_widget_content" class="s_widget_options_holder tb_cp">

  <style id="menu_widget_content_styles" scoped>
  #menu_widget_content .ui-tabs-panel [data-language_code]:not([data-language_code="<?php echo $tbData->first_language_code; ?>"]) {
    display: none;
  }
  </style>
  <style scoped>
  <?php foreach ($languages as $language): ?>
  [data-language_code="<?php echo $language['code']; ?>"] .tb_menu_component .tbMenuTabs h3:before {
    content: url(<?php echo $language['url'] . $language['image']; ?>);
    float: right;
    margin: 3px 0 0 0;
  }
  <?php endforeach; ?>
  </style>

  <h1 class="sm_title"><span>Menu</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_menu_styles_holder">Menu Styles</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_title_styles_holder">Title Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Edit Menu</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#menu_widget_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="menu_widget_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>" data-language_code="<?php echo $language_code; ?>"></div>
          <?php endforeach; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div data-language_code="<?php echo $language_code; ?>">
            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the Text content block for the current language.</p>
            </div>

            <div class="s_row_1">
              <label><strong>Title</strong></label>
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

          </div>
          <?php endforeach; ?>

          <div class="s_row_1">
            <label><strong>Select menu</strong></label>
            <div class="tbComboBoxRow">
              <input type="hidden" name="widget_data[menu_id]" value="<?php echo $settings['menu_id']; ?>" />
              <input type="hidden" name="widget_data[menu_name]" value="" />
              <select class="tb_nostyle tbComboBox">
                <option class="ui-combobox-nocomplete ui-combobox-noselect" key="add_new" value="add_new">New</option>
                <option class="ui-combobox-nocomplete ui-combobox-noselect ui-combobox-disabled tb_separator">separator</option>
                <?php foreach ($settings['menu_options'] as $option_value => $option_text): ?>
                <option<?php if ($option_value != 'main'): ?> class="ui-combobox-remove"<?php endif; ?><?php if ($option_value == $settings['menu_id']): ?> selected="selected"<?php endif; ?> key="<?php echo $option_value; ?>" value="<?php echo $option_value; ?>"><?php echo $option_text; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div class="tbLanguagePanel" data-language_code="<?php echo $language_code; ?>">
            <span class="clear s_mb_30"></span>
            <textarea name="widget_data[lang][<?php echo $language_code; ?>][menu]" class="tbMenuData" style="display: none;">[]</textarea>
            <div class="tbMenuContentsItem"></div>
          </div>
          <?php endforeach; ?>

        </div>

      </div>

      <div id="widget_menu_styles_holder" class="tb_subpanel">

        <div class="tb_cp">
          <h2>Menu Styles</h2>
        </div>

        <fieldset>
          <legend>Level 1</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_4 tbOrientationWrap">
              <label>Orientation</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[orientation]">
                    <option value="horizontal"<?php  if ($settings['orientation'] == 'horizontal') echo ' selected="selected"'; ?>>Horizontal</option>
                    <option value="vertical"<?php    if ($settings['orientation'] == 'vertical')   echo ' selected="selected"'; ?>>Vertical</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_4 tbLevel1StyleWrap">
              <label>Style</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[level_1_style]">
                    <option value="hidden"<?php if ($settings['level_1_style'] == 'hidden') echo ' selected="selected"'; ?>>Hidden</option>
                    <option value="none"<?php   if ($settings['level_1_style'] == 'none')   echo ' selected="selected"'; ?>>None</option>
                    <option value="list"<?php   if ($settings['level_1_style'] == 'list')   echo ' selected="selected"'; ?>>List</option>
                    <option value="h2"<?php     if ($settings['level_1_style'] == 'h2')     echo ' selected="selected"'; ?>>H2</option>
                    <option value="h3"<?php     if ($settings['level_1_style'] == 'h3')     echo ' selected="selected"'; ?>>H3</option>
                    <option value="h4"<?php     if ($settings['level_1_style'] == 'h4')     echo ' selected="selected"'; ?>>H4</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_4 tbLevel1JustifiedWrap">
              <label>Style</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[justified_navigation]">
                    <option value="0"<?php if ($settings['justified_navigation'] == 0) echo ' selected="selected"'; ?>>None</option>
                    <option value="1"<?php if ($settings['justified_navigation'] == 1) echo ' selected="selected"'; ?>>Justified width</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_6 tbLevel1HeightWrap">
              <label>Height</label>
              <input class="s_spinner" type="text" name="widget_data[menu_height]" min="0" size="7" value="<?php echo $settings['menu_height']; ?>" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_6 tbLevel1PaddingWrap">
              <label>Inner padding</label>
              <input class="s_spinner" type="text" name="widget_data[menu_padding]" min="0" size="7" value="<?php echo $settings['menu_padding']; ?>" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_6 tbLevel1SpacingWrap">
              <label>Spacing</label>
              <input class="s_spinner" type="text" name="widget_data[menu_spacing]" min="0" size="7" value="<?php echo $settings['menu_spacing']; ?>" />
              <span class="s_metric">px</span>
            </div>
          </div>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_4 tbJustifiedAlignWrap">
              <label>Menu text align</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[justified_align]">
                    <option value="start" <?php if ($settings['justified_align'] == 'start')  echo ' selected="selected"'; ?>>Start</option>
                    <option value="center"<?php if ($settings['justified_align'] == 'center') echo ' selected="selected"'; ?>>Center</option>
                    <option value="end"   <?php if ($settings['justified_align'] == 'end')    echo ' selected="selected"'; ?>>End</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_4 tbIgnoreTabbedSubmenu">
              <label>Ignore tabbed submenus</label>
              <input type="hidden" name="widget_data[ignore_tabbed_submenu]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[ignore_tabbed_submenu]" value="1"<?php if($settings['ignore_tabbed_submenu'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
          </div>
        </fieldset>
        
        <fieldset>
          <legend>Level 2</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_4 tbLevel2VisibilityWrap">
              <label>Visibility</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[level_2]">
                    <option value="visible"<?php  if ($settings['level_2'] == 'visible')  echo ' selected="selected"'; ?>>Visible</option>
                    <option value="dropdown"<?php if ($settings['level_2'] == 'dropdown') echo ' selected="selected"'; ?>>Dropdown</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_4 tbLevel2StyleWrap">
              <label>Style</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[level_2_style]">
                    <option value="none"<?php if ($settings['level_2_style'] == 'none') echo ' selected="selected"'; ?>>None</option>
                    <option value="list"<?php if ($settings['level_2_style'] == 'list') echo ' selected="selected"'; ?>>List</option>
                    <option value="h2"<?php   if ($settings['level_2_style'] == 'h2')   echo ' selected="selected"'; ?>>H2</option>
                    <option value="h3"<?php   if ($settings['level_2_style'] == 'h3')   echo ' selected="selected"'; ?>>H3</option>
                    <option value="h4"<?php   if ($settings['level_2_style'] == 'h4')   echo ' selected="selected"'; ?>>H4</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Level 3</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_4 tbLevel3VisibilityWrap">
              <label>Visibility</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[level_3]">
                    <option value="visible"<?php  if ($settings['level_3'] == 'visible')  echo ' selected="selected"'; ?>>Visible</option>
                    <option value="dropdown"<?php if ($settings['level_3'] == 'dropdown') echo ' selected="selected"'; ?>>Dropdown</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_4 tbLevel3StyleWrap">
              <label>Style</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[level_3_style]">
                    <option value="none"<?php if ($settings['level_3_style'] == 'none') echo ' selected="selected"'; ?>>None</option>
                    <option value="list"<?php if ($settings['level_3_style'] == 'list') echo ' selected="selected"'; ?>>List</option>
                    <option value="h2"<?php   if ($settings['level_3_style'] == 'h2')   echo ' selected="selected"'; ?>>H2</option>
                    <option value="h3"<?php   if ($settings['level_3_style'] == 'h3')   echo ' selected="selected"'; ?>>H3</option>
                    <option value="h4"<?php   if ($settings['level_3_style'] == 'h4')   echo ' selected="selected"'; ?>>H4</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Dropdown menus</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_4 tbDropdownMenuRelativeToHorizontal">
              <label>Megamenu relative to</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[relative_to]">
                    <option value="content"<?php if ($settings['relative_to'] == 'content') echo ' selected="selected"'; ?>>Section width (excl. padding)</option>
                    <option value="section"<?php if ($settings['relative_to'] == 'section') echo ' selected="selected"'; ?>>Section width (incl. padding)</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_4 tbDropdownMenuRelativeToVertical">
              <label>Megamenu relative to</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[relative_to_vertical]">
                    <option value="menu"<?php  if ($settings['relative_to_vertical'] == 'menu')  echo ' selected="selected"'; ?>>Menu</option>
                    <option value="block"<?php if ($settings['relative_to_vertical'] == 'block') echo ' selected="selected"'; ?>>Block</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Justified dropdowns</label>
              <input type="hidden" name="widget_data[justified_dropdown]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[justified_dropdown]" value="1"<?php if($settings['justified_dropdown'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Dropdown indicator</label>
              <input type="hidden" name="widget_data[dropdown_indicator]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[dropdown_indicator]" value="1"<?php if($settings['dropdown_indicator'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <div class="s_row_2 tb_col tb_1_4">
              <label>Minimum width</label>
              <input class="s_spinner" type="text" min="0" name="widget_data[dropdown_min_width]" value="<?php echo $settings['dropdown_min_width']; ?>" size="7" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Separator</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_4 tbSeparatorWrap">
              <label>Type</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[separator]">
                    <option value="none"<?php   if ($settings['separator'] == 'none')   echo ' selected="selected"'; ?>>None</option>
                    <option value="border"<?php if ($settings['separator'] == 'border') echo ' selected="selected"'; ?>>Border</option>
                    <option value="image"<?php  if ($settings['separator'] == 'image')  echo ' selected="selected"'; ?>>Image</option>
                    <option value="symbol"<?php if ($settings['separator'] == 'symbol') echo ' selected="selected"'; ?>>Symbol</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="tb_col tbSeparatorImageWrap">
              <input type="hidden" name="widget_data[separator_image]" value="<?php echo $settings['separator_image']; ?>" id="separator_image" />
              <span class="tb_thumb">
                <img src="<?php echo $settings['separator_image_preview']; ?>" id="separator_image_preview" class="image" onclick="image_upload('separator_image', 'separator_image_preview');" />
              </span>
            </div>
            <div class="s_row_2 tb_col tb_1_4 tbSeparatorImageWrap">
              <label>Position</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[separator_image_position]">
                    <option value="top"<?php    if ($settings['separator_image_position'] == 'top')    echo ' selected="selected"'; ?>>Top</option>
                    <option value="middle"<?php if ($settings['separator_image_position'] == 'middle') echo ' selected="selected"'; ?>>Middle</option>
                    <option value="bottom"<?php if ($settings['separator_image_position'] == 'bottom') echo ' selected="selected"'; ?>>Bottom</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_2_3 tbSeparatorSymbolWrap tbIconRow">
              <label>Icon</label>
              <div class="tbIcon s_h_26<?php if (!$settings['separator_symbol']): ?> s_icon_holder<?php endif; ?>">
                <?php if ($settings['separator_symbol']): ?>
                <span class="glyph_symbol"><span><?php echo $settings['separator_symbol']; ?></span></span>
                <?php endif; ?>
              </div>
              <?php if (!$settings['separator_symbol']): ?>
              <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
              <?php else: ?>
              <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
              <?php endif; ?>
              <input type="hidden" name="widget_data[separator_symbol]" value="<?php echo $settings['separator_symbol']; ?>" />
              <input class="s_spinner s_ml_10" type="text" min="8" name="widget_data[separator_symbol_size]" value="<?php echo $settings['separator_symbol_size']; ?>" size="6" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_4 tbSeparatorBorderWrap">
              <label>Border style</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[separator_border_style]">
                    <option value="solid"<?php  if ($settings['separator_border_style'] == 'solid')  echo ' selected="selected"'; ?>>Solid</option>
                    <option value="dotted"<?php if ($settings['separator_border_style'] == 'dotted') echo ' selected="selected"'; ?>>Dotted</option>
                    <option value="dashed"<?php if ($settings['separator_border_style'] == 'dashed') echo ' selected="selected"'; ?>>Dashed</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_4 tbSeparatorBorderWrap">
              <label>Border width</label>
              <input class="s_spinner" type="text" min="1" name="widget_data[separator_border_width]" value="<?php echo $settings['separator_border_width']; ?>" size="6" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>


        <fieldset>
          <legend>Responsive</legend>
          <div class="s_actions">
            <input type="hidden" name="widget_data[responsive_stack]" value="0" />
            <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="widget_data[responsive_stack]" value="1"<?php if($settings['responsive_stack'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_4">
              <label>Margin left/right</label>
              <input class="s_spinner" type="text" min="0" name="widget_data[responsive_margin_left]" value="<?php echo $settings['responsive_margin_left']; ?>" size="6" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>

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
$(document).ready(function() {

    var $widgetForm = $("#menu_widget_content");

    var loadMenuData = function($container, menu_name, menu_id) {

      $container.block().css('position', '');

      return $.get($sReg.get('/tb/url/menu/contentsByLanguage') + "&menu_id=" + menu_id, function(data) {

        $.each(data.contents, function(language_code, contents) {
          $widgetForm.find("textarea[name='widget_data[lang][" + language_code + "][menu]']")
            .text(data.menu.tree !== undefined ? JSON.stringify(data.menu.tree[language_code]) : "[]")
            .next(".tbMenuContentsItem").empty().append(contents);
        });

        $container.unblock();

        initMenu();

      }, "json");
    };

    var comboBox = $widgetForm.find(".tbComboBox").combobox({

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

          loadMenuData($widgetForm, menu_name, menu_id).then(function() {
            $widgetForm
              .find("input[name='widget_data[menu_name]']").val(menu_name).end()
              .find(".tbComboBox").data("uiCombobox").customValue(menu_name, menu_id);
            ui.context.uiInput.trigger("blur");
          });
        } else {
          $widgetForm.find(".tbMenuContentsItem, .tbMenuData").empty().end()
        }

        $widgetForm.find("input[name='widget_data[menu_id]']").val(menu_id);
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

    var initMenu = function() {

      var menu = tbApp.initMenu($widgetForm, {
        width:          600,
        margin_left:    -300,
        allow_nesting:  true,
        sticky_sidebar: false,
        has_menu_icon:  true
      });

      $(tbApp).off("tbWidget:onUpdate.menuWidget").one("tbWidget:onUpdate.menuWidget", function() {
        menu.prepareForSave();
      });

      $(tbApp).off("tbWidget:closeForm.menuWidget").one("tbWidget:closeForm.menuWidget", function() {
        $(tbApp).off("tbWidget:onUpdate.menuWidget");
      });
    };

    $widgetForm.find(".tbLanguageTabs").first().tabs({
        activate: function(event, ui) {
            $("#menu_widget_content_styles").html('#menu_widget_content [data-language_code]:not([data-language_code="' + ui.newPanel.data("language_code") + '"]) { display: none; }');
        }
    });

    var widgetIconListReplace = function($newIcon, $activeRow) {
      $activeRow
          .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
          .find('input[name*="separator_symbol"]:hidden').val($newIcon.attr("glyph_code")).end()
          .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove").end()
          .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
          .find('input[name*="title_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
          .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
    }

    $("#menu_widget_content").on("click", ".tbChooseIcon", function() {
      if ($(this).hasClass("tbRemoveIcon")) {
        $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
            .parents(".tbIconRow").first()
            .find('input[name*="separator_symbol"]:hidden').val("").end()
            .find(".tbIcon").addClass("s_icon_holder").empty().end()
            .find('input[name*="title_icon"]:hidden').val("").end()
            .find(".tbIcon").addClass("s_icon_holder").empty();
      } else {
        tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
      }

      return false;
    });

    // Options visibility

    var $menu_justify     = $widgetForm.find('.tbLevel1JustifiedWrap'),
        $level_1_style    = $widgetForm.find('.tbLevel1StyleWrap'),
        $level_2          = $widgetForm.find('.tbLevel2VisibilityWrap'),
        $level_2_style    = $widgetForm.find('.tbLevel2StyleWrap'),
        $level_3          = $widgetForm.find('.tbLevel3VisibilityWrap'),
        $level_3_style    = $widgetForm.find('.tbLevel3StyleWrap');

    function filterStyles($visibility, $style) {
        $visibility.find('select').bind('change', function() {
            $style.find('option[value="h2"]').toggle($(this).val() != 'dropdown');
            $style.find('option[value="h3"]').toggle($(this).val() != 'dropdown');
            $style.find('option[value="h4"]').toggle($(this).val() != 'dropdown');
            if ($style.find('select').val() != 'none' && $style.find('select').val() != 'list') {
                $style.find('select').prop('selectedIndex',0).trigger('change');
            }
        }).trigger('change');
    }

    $menu_justify.find('select').bind('change', function() {
        $('.tbJustifiedAlignWrap').toggleClass('tb_disabled', $(this).val() == 0);
    }).trigger('change');

    $('.tbOrientationWrap').find('select').bind('change', function() {
        $('.tbLevel1StyleWrap').toggle($(this).val() == 'vertical');
        $('.tbDropdownMenuRelativeToHorizontal').toggle($(this).val() == 'horizontal');
        $('.tbDropdownMenuRelativeToVertical').toggle($(this).val() == 'vertical');
        $('.tbLevel1SpacingWrap').toggleClass("tb_disabled", $(this).val() == 'vertical');
        $('.tbLevel1JustifiedWrap').toggle($(this).val() != 'vertical');
        $('.tbLevel2VisibilityWrap').toggleClass('tb_disabled', $(this).val() != 'vertical');
        $('.tbLevel3VisibilityWrap').toggleClass('tb_disabled', $(this).val() != 'vertical');
    }).trigger('change');

    $('.tbLevel2VisibilityWrap').find('select').bind('change', function() {
        $('.tbLevel3VisibilityWrap').toggleClass("tb_disabled", $(this).val() == 'dropdown');
        $('.tbLevel3VisibilityWrap').toggleClass("tb_disabled", $(this).val() == 'dropdown');
        $('.tbLevel3StyleWrap').toggleClass("tb_disabled", $(this).val() == 'dropdown');
        if ($('.tbLevel2VisibilityWrap').find('select').val() == 'dropdown') {
            $('.tbLevel3VisibilityWrap').find('select').val('dropdown').trigger('change');
        }
    }).trigger('change');

    $('.tbLevel2StyleWrap').find('select').bind('change', function() {
      if ($('.tbLevel2VisibilityWrap').find('select').val() == 'dropdown') {
        $('.tbLevel3StyleWrap').find('select').val($(this).val()).trigger('change');
      }
    }).trigger('change');

    $('[name="widget_data[responsive_stack]"]').bind('change', function() {
        $(this).closest('fieldset').find('.tb_wrap').toggleClass('tb_disabled', !$(this).prop('checked'));
    }).trigger('change');

    filterStyles($('.tbLevel2VisibilityWrap'), $('.tbLevel2StyleWrap'));
    filterStyles($('.tbLevel3VisibilityWrap'), $('.tbLevel3StyleWrap'));

    $('.tbSeparatorWrap').find('select').bind('click', function() {
        $('.tbSeparatorImageWrap').toggle($(this).val() == 'image');
        $('.tbSeparatorSymbolWrap').toggle($(this).val() == 'symbol');
        $('.tbSeparatorBorderWrap').toggle($(this).val() == 'border');
    }).trigger('click');

});
</script>