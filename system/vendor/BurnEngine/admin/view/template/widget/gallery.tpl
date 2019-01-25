<div id="widget_gallery" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Gallery</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_images_holder">Images</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_title_styles_holder">Title Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Edit Gallery</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#widget_gallery_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="widget_gallery_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>">
            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the Gallery content block for the current language.</p>
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

          <fieldset>
            <legend>Style</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Gallery type</label>
                <div class="s_select">
                  <select name="widget_data[gallery_type]" id="widget_gallery_type">
                    <option value="slide"<?php if($settings['gallery_type'] == 'slide') echo ' selected="selected"';?>>Slide</option>
                    <option value="grid"<?php  if($settings['gallery_type'] == 'grid')  echo ' selected="selected"';?>>Grid</option>
                  </select>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tbOptGalleryGrid">
                <label>Thumb gutter</label>
                <input class="s_spinner" type="text" name="widget_data[thumb_gutter]" value="<?php echo $settings['thumb_gutter']; ?>" size="7" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6">
                <label>Randomize</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[filter_randomize]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[filter_randomize]" value="1"<?php if ($settings['filter_randomize'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tbImagesFullscreenButton">
                <label>Fullsceen gallery</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[fullscreen]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[fullscreen]" value="1"<?php if($settings['fullscreen'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              </div>
            </div>
          </fieldset>

          <fieldset class="tbOptGalleryGrid">
            <legend>Image grid</legend>
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
          </fieldset>

          <fieldset class="tbOptGallerySlide">
            <legend>Gallery Pagination</legend>
            <div class="s_actions">
              <div class="tbImagesNavigation">
                <input type="hidden" name="widget_data[nav]" value="0" />
                <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="widget_data[nav]" value="1"<?php if($settings['nav'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5 tbImagesNavigationStyle">
                <label>Style</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[nav_style]">
                      <option value="thumbs"<?php  if($settings['nav_style'] == 'thumbs') echo ' selected="selected"';?>>Thumbnails</option>
                      <option value="dots"<?php    if($settings['nav_style'] == 'dots')   echo ' selected="selected"';?>>Dots</option>
                      <?php /* <option value="numbers"<?php if($settings['nav_style'] == 'number') echo ' selected="selected"';?>>Numbers</option> */ ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbImagesNavigationPosition">
                <label>Position</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[nav_position]">
                      <option value="bottom"<?php if($settings['nav_position'] == 'bottom') echo ' selected="selected"';?>>Bottom</option>
                      <option value="right"<?php  if($settings['nav_position'] == 'right')  echo ' selected="selected"';?>>Right</option>
                      <option value="left"<?php   if($settings['nav_position'] == 'left')   echo ' selected="selected"';?>>Left</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbImagesNavigationDotsPosition">
                <label>Position</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[nav_dots_position]">
                      <option value="inside"<?php  if($settings['nav_dots_position'] == 'inside')  echo ' selected="selected"';?>>Inside</option>
                      <option value="outside"<?php if($settings['nav_dots_position'] == 'outside') echo ' selected="selected"';?>>Outside</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbImagesNavigationSpacing">
                <label>Spacing</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[nav_spacing]">
                      <option value="none"<?php if($settings['nav_spacing'] == 'none') echo ' selected="selected"';?>>None</option>
                      <option value="1px"<?php  if($settings['nav_spacing'] == '1px')  echo ' selected="selected"';?>>1px</option>
                      <option value="xs"<?php   if($settings['nav_spacing'] == 'xs')   echo ' selected="selected"';?>>Extra small</option>
                      <option value="sm"<?php   if($settings['nav_spacing'] == 'sm')   echo ' selected="selected"';?>>Small</option>
                      <option value="md"<?php   if($settings['nav_spacing'] == 'md')   echo ' selected="selected"';?>>Medium</option>
                      <option value="lg"<?php   if($settings['nav_spacing'] == 'lg')   echo ' selected="selected"';?>>Large</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbImagesNavigationThumbsNum">
                <label>Thumbs per row</label>
                <input class="s_spinner" type="text" name="widget_data[nav_thumbs_num]" value="<?php echo $settings['nav_thumbs_num']; ?>" size="7" min="3" max="8" />
              </div>
            </div>
          </fieldset>

          <fieldset class="tbOptGallerySlide">
            <legend>Prev/Next buttons</legend>
            <div class="s_actions">
              <div class="tbImagesNavigationButtons">
                <input type="hidden" name="widget_data[nav_buttons]" value="0" />
                <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="widget_data[nav_buttons]" value="1"<?php if($settings['nav_buttons'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5 tbImagesNavigationButtonsSize">
                <label>Size</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[nav_buttons_size]">
                      <option value="1"<?php if($settings['nav_buttons_size'] == '1') echo ' selected="selected"';?>>Small</option>
                      <option value="2"<?php if($settings['nav_buttons_size'] == '2') echo ' selected="selected"';?>>Medium</option>
                      <option value="3"<?php if($settings['nav_buttons_size'] == '3') echo ' selected="selected"';?>>Large</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbImagesNavigationButtonsVisibility">
                <label>Visibility</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[nav_buttons_visibility]">
                      <option value="visible"<?php if($settings['nav_buttons_visibility'] == 'visible') echo ' selected="selected"';?>>Visible</option>
                      <option value="hover"<?php   if($settings['nav_buttons_visibility'] == 'hover')   echo ' selected="selected"';?>>Show on hover</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="tbOptGallerySlide">
            <legend>"Go Fullscreen" button</legend>
            <div class="tb_wrap tb_gut_30 tbImagesFullscreenSettings">
              <div class="s_row_2 tb_col tb_1_5 tbImagesFullscreenButtonSize">
                <label>Size</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[fullscreen_button_size]">
                      <option value="md"<?php  if($settings['fullscreen_button_size'] == 'md')  echo ' selected="selected"';?>>Small</option>
                      <option value="lg"<?php  if($settings['fullscreen_button_size'] == 'lg')  echo ' selected="selected"';?>>Medium</option>
                      <option value="xxl"<?php if($settings['fullscreen_button_size'] == 'xxl') echo ' selected="selected"';?>>Large</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbImagesFullscreenButtonVisibility">
                <label>Visibility</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[fullscreen_button_visibility]">
                      <option value="visible"<?php if($settings['fullscreen_button_visibility'] == 'visible') echo ' selected="selected"';?>>Visible</option>
                      <option value="hover"<?php   if($settings['fullscreen_button_visibility'] == 'hover')   echo ' selected="selected"';?>>Show on hover</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbImagesFullscreenButtonVisibility">
                <label>Position</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="widget_data[fullscreen_button_position]">
                      <option value="tr"<?php if($settings['fullscreen_button_position'] == 'tr') echo ' selected="selected"';?>>Top right</option>
                      <option value="br"<?php if($settings['fullscreen_button_position'] == 'br') echo ' selected="selected"';?>>Bottom right</option>
                      <option value="bl"<?php if($settings['fullscreen_button_position'] == 'bl') echo ' selected="selected"';?>>Bottom left</option>
                      <option value="tl"<?php if($settings['fullscreen_button_position'] == 'tl') echo ' selected="selected"';?>>Top left</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_2_5 tbImagesFullscreenButtonIcon tbIconRow">
                <label>Icon</label>
                <div class="tbIcon s_h_26<?php if (!$settings['fullscreen_button_icon']): ?> s_icon_holder<?php endif; ?>">
                  <?php if ($settings['fullscreen_button_icon']): ?>
                  <span class="glyph_symbol <?php echo $settings['fullscreen_button_icon']; ?>"></span>
                  <?php endif; ?>
                </div>
                <?php if (!$settings['fullscreen_button_icon']): ?>
                <a class="s_button s_white s_h_26 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
                <?php else: ?>
                <a class="s_button s_white s_h_26 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
                <?php endif; ?>
                <input type="hidden" name="widget_data[fullscreen_button_icon]" value="<?php echo $settings['fullscreen_button_icon']; ?>" />
                <input class="s_spinner s_ml_10" type="text" min="8" name="widget_data[fullscreen_button_icon_size]" value="<?php echo $settings['fullscreen_button_icon_size']; ?>" size="6" />
                <span class="s_metric">px</span>
              </div>
            </div>
          </fieldset>

          <fieldset>
            <legend>Images</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Thumb size</label>
                <input class="s_spinner" type="text" name="widget_data[thumb_width]" value="<?php echo $settings['thumb_width']; ?>" size="7" />
                <span class="s_input_separator">&nbsp;x&nbsp;</span>
                <input class="s_spinner" type="text" name="widget_data[thumb_height]" value="<?php echo $settings['thumb_height']; ?>" size="7" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_4 tbOptGallerySlide">
                <label>Preview size</label>
                <input class="s_spinner" type="text" name="widget_data[slide_width]" value="<?php echo $settings['slide_width']; ?>" size="7" />
                <span class="s_input_separator">&nbsp;x&nbsp;</span>
                <input class="s_spinner" type="text" name="widget_data[slide_height]" value="<?php echo $settings['slide_height']; ?>" size="7" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Full size</label>
                <input class="s_spinner" type="text" name="widget_data[full_width]" value="<?php echo $settings['full_width']; ?>" size="7" />
                <span class="s_input_separator">&nbsp;x&nbsp;</span>
                <input class="s_spinner" type="text" name="widget_data[full_height]" value="<?php echo $settings['full_height']; ?>" size="7" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_4 tbOptGallerySlide">
                <label>Crop thumb/preview</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[crop_thumbs]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[crop_thumbs]" value="1"<?php if ($settings['crop_thumbs'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
            </div>
          </fieldset>

          <fieldset class="tbOptGallerySlide">
            <legend>Caption</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Position</label>
                <div class="s_select">
                  <select name="widget_data[caption_position]">
                    <option value="top"<?php    if($settings['caption_position'] == 'top')    echo ' selected="selected"';?>>Top</option>
                    <option value="bottom"<?php if($settings['caption_position'] == 'bottom') echo ' selected="selected"';?>>Bottom</option>
                  </select>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Opacity</label>
                <input class="s_spinner" type="text" name="widget_data[caption_opacity]" value="<?php echo $settings['caption_opacity']; ?>" min="0" max="1" step="0.1" size="7" />
              </div>
            </div>
          </fieldset>

      </div>

      </div>

      <div id="widget_images_holder" class="tb_subpanel clearfix tbWidgetGalleryImages">
        <div class="tb_cp">
          <h2>Gallery Images</h2>
        </div>

        <div class="s_sortable_holder tb_style_3 clearfix tbImagesList"><?php $i = 0; ?><?php foreach ($settings['images'] as $row_num => $image): ?><div class="s_sortable_row tbImageRow">
            <input type="hidden" name="widget_data[images][<?php echo $row_num; ?>][file]" value="<?php echo $image['file']; ?>" id="bg_image_style_widget_gallery_<?php echo $row_num; ?>" />
            <?php foreach ($languages as $language): ?>
            <input type="hidden" name="widget_data[images][<?php echo $row_num; ?>][lang][<?php echo $language['code']; ?>][url]" value="<?php if(isset($image['lang'][$language['code']]['url'])) echo $image['lang'][$language['code']]['url']; ?>" />
            <input type="hidden" name="widget_data[images][<?php echo $row_num; ?>][lang][<?php echo $language['code']; ?>][url_target]" value="<?php if(isset($image['lang'][$language['code']]['url_target'])) echo $image['lang'][$language['code']]['url_target']; ?>" />
            <input type="hidden" name="widget_data[images][<?php echo $row_num; ?>][lang][<?php echo $language['code']; ?>][caption]" value="<?php if(isset($image['lang'][$language['code']]['caption'])) echo $image['lang'][$language['code']]['caption']; ?>" />
            <?php endforeach; ?>
            <img src="<?php echo $image['preview']; ?>" id="bg_preview_style_widget_gallery_<?php echo $row_num; ?>" class="image" onclick="image_upload('bg_image_style_widget_gallery_<?php echo $row_num; ?>', 'bg_preview_style_widget_gallery_<?php echo $row_num; ?>');" />
            <div class="s_actions">
              <div class="s_buttons_group">
                <a class="tbEditImage s_button s_white s_h_20 s_icon_10 s_edit_10" href="javascript:;"></a>
                <a class="tbRemoveImage s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;"></a>
              </div>
            </div>
            <h3 class="s_drag_area"></h3>
          </div><?php $i++; ?><?php endforeach; ?></div>

        <span class="clear"></span>

        <div class="clearfix">
          <a href="javascript:;" class="s_button s_h_30 s_white s_icon_10 s_plus_10 left s_mr_20 tbAddImage">Add Image</a>
          <p class="s_999 s_mb_0 s_pt_5">Each title will appear as a tab/accordion heading. Any extra titles will be ignored.</p>
        </div>
      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar clearfix tbWidgetCommonOptions">
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

    var $container = $("#widget_gallery");

    $container.find(".tbLanguageTabs").first().tabs();

    var widgetIconListReplace = function($newIcon, $activeRow) {
      $activeRow
              .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
              .find('input[name*="fullscreen_button_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
              .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove").end()
              .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
              .find('input[name*="title_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
              .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
    };

    $container.on("click", ".tbChooseIcon", function() {
      if ($(this).hasClass("tbRemoveIcon")) {
        $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
                .parents(".tbIconRow").first()
                .find('input[name*="fullscreen_button_icon"]:hidden').val("").end()
                .find(".tbIcon").addClass("s_icon_holder").empty().end()
                .find('input[name*="title_icon"]:hidden').val("").end()
                .find(".tbIcon").addClass("s_icon_holder").empty();
      } else {
        tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
      }

      return false;
    });

    tbApp.initRestrictionRows($('#widget_gallery'), "widget_data");

    $('.tbImagesNavigation :input').on("change", function() {
      $(this).parents('.s_actions').first().next('.tb_wrap').toggleClass("tb_disabled", !$(this).is(":checked"));
    }).trigger('change');

    $('.tbImagesNavigationButtons :input').on("change", function() {
      $(this).parents('.s_actions').first().next('.tb_wrap').toggleClass("tb_disabled", !$(this).is(":checked"));
    }).trigger('change');


    $('.tbImagesFullscreenButton :input').on("change", function() {
      $('.tbImagesFullscreenSettings').toggleClass("tb_disabled", !$(this).is(":checked"));
    }).trigger('change');

    $('.tbImagesNavigationStyle select').on("change", function() {
      $('.tbImagesNavigationSpacing, .tbImagesNavigationThumbsNum').toggleClass('tb_disabled', $(this).val() != 'thumbs');
      $('.tbImagesNavigationPosition').toggle($(this).val() == 'thumbs');
      $('.tbImagesNavigationDotsPosition').toggle($(this).val() != 'thumbs');
    }).trigger('change');

    $("#widget_gallery_type").bind("change", function() {
        $container.find(".tbOptGalleryGrid").toggle($(this).val() != 'slide');
        $container.find(".tbOptGallerySlide").toggle($(this).val() == 'slide');
    }).trigger("change");

    $container.find(".tbWidgetGalleryImages .tbImagesList").sortable({
        handle: ".s_drag_area",
        tolerance: "pointer"
    });

    $container.find(".tbWidgetGalleryImages .tbAddImage").bind("click", function() {
        var output = Mustache.render($("#widget_gallery_image_template").text(), {
            row_num: tbHelper.generateUniqueId(5)
        });

        $(output).appendTo($container.find(".tbWidgetGalleryImages .tbImagesList"))//.find("img").trigger("click");

        return false;
    });

    $container.find(".tbWidgetGalleryImages").on("click", ".tbRemoveImage", function() {

        if (confirm("Are you sure?")) {
            $(this).parents(".tbImageRow").first().remove();
        }

        return false;
    });

    $container.find(".tbWidgetGalleryImages").on("click", ".tbEditImage", function() {

      var languages = [];

      $.each($sReg.get("/tb/languages"), function(index, value) {
        languages.push(value);
      });

      var $output = $(Mustache.render($("#common_modal_dialog_template").text(), {
        width:       600,
        margin_left: -300
      })).appendTo($("body"));
      var $promptWindow = $output.find(".sm_window").first();

      $promptWindow.find(".sm_content").append(Mustache.render($("#widget_gallery_image_options_template").text(), {
        theme_catalog_resource_url: $sReg.get("/tb/url/theme_catalog_resource_url"),
        languages: languages
      }));

      var $imageRow = $(this).closest(".tbImageRow");

      $promptWindow.find(":input").each(function() {
        $(this).val($imageRow.find("input[name$='" + $(this).attr("name")+ "']").val());
      });

      $promptWindow.show().find("a.sm_closeWindowButton").add($output.find(".sm_overlayBG")).bind("click", function() {
        $promptWindow.fadeOut(300, function() {
          $promptWindow.parent("div").remove();
        });
      });

      $promptWindow.tabs();

      $promptWindow.find(".tbUpdateImageData").bind("click", function() {
        $promptWindow.find(":input").each(function() {
          $imageRow.find("input[name$='" + $(this).attr("name") + "']").val($(this).val());
        });
        $promptWindow.find("a.sm_closeWindowButton").trigger("click");
      });
    });
  });
</script>

<script type="text/template" id="widget_gallery_image_template">
  <div class="s_sortable_row tbImageRow">
    <input type="hidden" name="widget_data[images][{{row_num}}][file]" value="" id="bg_image_style_widget_gallery_{{row_num}}" />
    {{#languages}}
    <input type="hidden" name="widget_data[images][{{row_num}}][lang][{{code}}][url]" value="" />
    <input type="hidden" name="widget_data[images][{{row_num}}][lang][{{code}}][url_target]" value="_self" />
    <input type="hidden" name="widget_data[images][{{row_num}}][lang][{{code}}][caption]" value="" />
    {{/languages}}
    <img src="<?php echo $no_image; ?>" id="bg_preview_style_widget_gallery_{{row_num}}" class="image" onclick="image_upload('bg_image_style_widget_gallery_{{row_num}}', 'bg_preview_style_widget_gallery_{{row_num}}');" />
    <div class="s_actions">
      <div class="s_buttons_group">
        <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditImage" href="javascript:;"></a>
        <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveImage" href="javascript:;"></a>
      </div>
    </div>
    <h3 class="s_drag_area"></h3>
  </div>
</script>

<script type="text/template" id="widget_gallery_image_options_template">
  <h1 class="sm_title"><span>Edit Image</span></h1>
  <div class="s_widget_options_holder tb_cp">
    <div class="tb_subpanel">
      <div class="tb_tabs tb_fly_tabs">
        <h2>Edit Image</h2>
        <ul class="tb_tabs_nav clearfix">
          {{#languages}}
          <li class="s_language">
            <a href="#widget_gallery_image_options_language_{{code}}" title="{{name}}">
              <img class="inline" src="{{url}}{{image}}" title="{{name}}" />
              {{code}}
            </a>
          </li>
          {{/languages}}
        </ul>
        {{#languages}}
        <div id="widget_gallery_image_options_language_{{code}}" class="s_language_{{code}}">
          <div class="s_row_1">
            <label>Image link</label>
            <div class="s_full">
              <input type="text" name="[{{code}}][url]" value="" />
            </div>
            <span class="s_metric s_target">
              <select name="[{{code}}][url_target]">
                <option value="_self">_self</option>
                <option value="_blank">_blank</option>
              </select>
            </span>
          </div>
          <div class="s_row_1">
            <label>Caption</label>
            <div class="s_full">
              <textarea name="[{{code}}][caption]"></textarea>
            </div>
          </div>
        </div>
        {{/languages}}
      </div>
    </div>
    <div class="s_submit">
      <a class="s_button s_h_40 s_red tbUpdateImageData" href="javascript:;">Update</a>
    </div>
  </div>
</script>