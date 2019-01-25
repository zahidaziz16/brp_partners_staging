<div id="category_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Categories</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_title_styles_holder">Title Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Edit Categories</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#category_widget_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="category_widget_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>">
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
              <div class="s_full clearfix">
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

          <fieldset>
            <legend>Display</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <div class="s_full">
                  <div class="s_select">
                    <select name="widget_data[layout]">
                      <option value="list"<?php if ($settings['layout'] == 'list'): ?> selected="selected"<?php endif; ?>>List</option>
                      <option value="grid"<?php if ($settings['layout'] == 'grid'): ?> selected="selected"<?php endif; ?>>Grid</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="tbGridOptions">
            <legend>Style</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbSubcategoriesStyle">
                <label>Style</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[style]">
                      <option value="1"<?php if ($settings['style'] == '1'): ?> selected="selected"<?php endif; ?>>Text and image</option>
                      <option value="2"<?php if ($settings['style'] == '2'): ?> selected="selected"<?php endif; ?>>Image only</option>
                      <option value="3"<?php if ($settings['style'] == '3'): ?> selected="selected"<?php endif; ?>>Text only</option>
                      <option value="4"<?php if ($settings['style'] == '4'): ?> selected="selected"<?php endif; ?>><?php echo $tbEngine->getThemeInfo('name'); ?> light</option>
                      <option value="5"<?php if ($settings['style'] == '5'): ?> selected="selected"<?php endif; ?>><?php echo $tbEngine->getThemeInfo('name'); ?> dark</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbSubcategoriesTextAlign">
                <label>Text align</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[text_align]">
                      <option value="left"  <?php if ($settings['text_align'] == 'left'):   ?> selected="selected"<?php endif; ?>>Left</option>
                      <option value="center"<?php if ($settings['text_align'] == 'center'): ?> selected="selected"<?php endif; ?>>Center</option>
                      <option value="right" <?php if ($settings['text_align'] == 'right'):  ?> selected="selected"<?php endif; ?>>Right</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbSubcategoriesImagePosition">
                <label>Image position</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[image_position]">
                      <option value="top"<?php   if ($settings['image_position'] == 'top'):   ?> selected="selected"<?php endif; ?>>Top</option>
                      <option value="right"<?php if ($settings['image_position'] == 'right'): ?> selected="selected"<?php endif; ?>>Right</option>
                      <option value="left"<?php  if ($settings['image_position'] == 'left'):  ?> selected="selected"<?php endif; ?>>Left</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbSubcategoriesImageSize">
                <label>Thumb size</label>
                <input class="inline" type="text" name="widget_data[image_width]" value="<?php echo  $settings['image_width']; ?>" size="2" />
                <span class="s_input_separator">&nbsp;x&nbsp;</span>
                <input class="inline" type="text" name="widget_data[image_height]" value="<?php echo $settings['image_height']; ?>" size="2" /><span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbSubcategoriesNextLevel">
                <label>Show level 2</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[show_next_level]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[show_next_level]" value="1"<?php if ($settings['show_next_level'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1 tbSubcategoriesNextLevel">
                <label>Product count</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[product_count]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[product_count]" value="1"<?php if ($settings['product_count'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
            </div>
          </fieldset>

          <fieldset class="tbGridOptions">
            <legend>Layout</legend>
            <div class="s_row_2 tbGridTable">
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
          </fieldset>

          <fieldset class="tbListOptions">
            <legend>Level 1</legend>
            <div class="tb_wrap">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Visibility</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[level_1]">
                      <option value="show"<?php if ($settings['level_1'] == 'show')  echo ' selected="selected"'; ?>>Always show</option>
                      <option value="hide"<?php if ($settings['level_1'] == 'hide') echo ' selected="selected"'; ?>>Hide unselected</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Style</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[level_1_style]">
                      <option value="list"<?php  if ($settings['level_1_style'] == 'list')  echo ' selected="selected"'; ?>>List</option>
                      <option value="h2"<?php if ($settings['level_1_style'] == 'h2') echo ' selected="selected"'; ?>>H2</option>
                      <option value="h3"<?php if ($settings['level_1_style'] == 'h3') echo ' selected="selected"'; ?>>H3</option>
                      <option value="h4"<?php if ($settings['level_1_style'] == 'h4') echo ' selected="selected"'; ?>>H4</option>
                      <option value="hide"<?php if ($settings['level_1_style'] == 'hide') echo ' selected="selected"'; ?>>Hidden</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Respect 'Top'</label>
                <input type="hidden" name="widget_data[respect_top]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[respect_top]" value="1"<?php if($settings['respect_top'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              </div>
            </div>
          </fieldset>
          <fieldset class="tbListOptions">
            <legend>Level 2</legend>
            <div class="tb_wrap">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Visibility</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[level_2]">
                      <option value="show_select"<?php if ($settings['level_2'] == 'show_select')  echo ' selected="selected"'; ?>>Show on parent selected</option>
                      <option value="show"<?php if ($settings['level_2'] == 'show')  echo ' selected="selected"'; ?>>Always show</option>
                      <option value="accordion"<?php if ($settings['level_2'] == 'accordion')  echo ' selected="selected"'; ?>>Accordion</option>
                      <option value="toggle"<?php if ($settings['level_2'] == 'toggle')  echo ' selected="selected"'; ?>>Toggle</option>
                      <option value="hide"<?php if ($settings['level_2'] == 'hide') echo ' selected="selected"'; ?>>Hidden</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Style</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[level_2_style]">
                      <option value="list"<?php  if ($settings['level_2_style'] == 'list')  echo ' selected="selected"'; ?>>List</option>
                      <option value="h2"<?php if ($settings['level_2_style'] == 'h2') echo ' selected="selected"'; ?>>H2</option>
                      <option value="h3"<?php if ($settings['level_2_style'] == 'h3') echo ' selected="selected"'; ?>>H3</option>
                      <option value="h4"<?php if ($settings['level_2_style'] == 'h4') echo ' selected="selected"'; ?>>H4</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset class="tbListOptions">
            <legend>Level 3</legend>
            <div class="tb_wrap">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Visibility</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[level_3]">
                      <option value="show_select"<?php if ($settings['level_3'] == 'show_select')  echo ' selected="selected"'; ?>>Show on parent select</option>
                      <option value="show"<?php if ($settings['level_3'] == 'show')  echo ' selected="selected"'; ?>>Always show</option>
                      <option value="hide"<?php if ($settings['level_3'] == 'hide') echo ' selected="selected"'; ?>>Hidden</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Style</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[level_3_style]">
                      <option value="list"<?php  if ($settings['level_3_style'] == 'list')  echo ' selected="selected"'; ?>>List</option>
                      <option value="h2"<?php if ($settings['level_3_style'] == 'h2') echo ' selected="selected"'; ?>>H2</option>
                      <option value="h3"<?php if ($settings['level_3_style'] == 'h3') echo ' selected="selected"'; ?>>H3</option>
                      <option value="h4"<?php if ($settings['level_3_style'] == 'h4') echo ' selected="selected"'; ?>>H4</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>

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
$(document).ready(function() {

  $("#widget_settings_holder").find(".tbLanguageTabs").first().tabs();

  $('[name="widget_data[layout]"]').bind('change', function() {
      $('.tbGridOptions').toggle($(this).val() == 'grid');
      $('.tbListOptions').toggle($(this).val() == 'list');
  }).trigger('change');

  var widgetIconListReplace = function($newIcon, $activeRow) {
    $activeRow
            .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
            .find('input[name*="title_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
            .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
  };

  $("#widget_settings_holder").on("click", ".tbChooseIcon", function() {
    if ($(this).hasClass("tbRemoveIcon")) {
      $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
             .parents(".tbIconRow").first()
             .find('input[name*="title_icon"]:hidden').val("").end()
             .find(".tbIcon").addClass("s_icon_holder").empty();
    } else {
      tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
    }

    return false;
  });

  var $container = $("#category_widget_content");

  tbApp.initRestrictionRows($container, "widget_data");
});
</script>