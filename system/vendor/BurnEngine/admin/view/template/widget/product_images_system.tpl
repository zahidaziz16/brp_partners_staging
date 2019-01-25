<div id="product_images_system_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Product Images</span></h1>

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

        <h2>Edit Product Images</h2>

        <fieldset>
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

        <fieldset>
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

        <fieldset>
          <legend>"Go Fullscreen" button</legend>
          <div class="s_actions">
            <div class="tbImagesFullscreenButton">
              <input type="hidden" name="widget_data[fullscreen]" value="0" />
              <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="widget_data[fullscreen]" value="1"<?php if($settings['fullscreen'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
          </div>
          <div class="tb_wrap tb_gut_30">
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
              <div class="tbIcon<?php if (!$settings['fullscreen_button_icon']): ?> s_icon_holder s_h_26<?php endif; ?>">
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
          <legend>Zoom</legend>
          <div class="s_actions">
            <div class="tbImagesZoom">
              <input type="hidden" name="widget_data[zoom]" value="0" />
              <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="widget_data[zoom]" value="1"<?php if($settings['zoom'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
          </div>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Trigger</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[zoom_trigger]">
                    <option value="click"<?php     if($settings['zoom_trigger'] == 'click')     echo ' selected="selected"';?>>Click</option>
                    <option value="mouseover"<?php if($settings['zoom_trigger'] == 'mouseover') echo ' selected="selected"';?>>Hover</option>
                    <option value="grab"<?php      if($settings['zoom_trigger'] == 'grab')      echo ' selected="selected"';?>>Drag</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </fieldset>

      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <input type="hidden" name="widget_data[widget_name]" value="<?php echo $settings['widget_name']; ?>" />
        <input type="hidden" name="widget_data[slot_name]" value="<?php echo $settings['slot_name']; ?>" />
        <input type="hidden" name="widget_data[slot_prefix]" value="<?php echo $settings['slot_prefix']; ?>" />
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

    var $container = $("#product_images_system_widget_content");

    var widgetIconListReplace = function($newIcon, $activeRow) {
      $activeRow
              .find(".tbIcon").removeClass("s_icon_holder s_h_26").empty().append($newIcon).end()
              .find('input[name*="fullscreen_button_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
              .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
    };

    $container.on("click", ".tbChooseIcon", function() {
      if ($(this).hasClass("tbRemoveIcon")) {
        $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
                .parents(".tbIconRow").first()
                .find('input[name*="fullscreen_button_icon"]:hidden').val("").end()
                .find(".tbIcon").addClass("s_icon_holder s_h_26").empty();
      } else {
        tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
      }

      return false;
    });


    $('.tbImagesNavigation :input').on("change", function() {
        $(this).parents('.s_actions').first().next('.tb_wrap').toggleClass("tb_disabled", !$(this).is(":checked"));
    }).trigger('change');

    $('.tbImagesNavigationButtons :input').on("change", function() {
      $(this).parents('.s_actions').first().next('.tb_wrap').toggleClass("tb_disabled", !$(this).is(":checked"));
    }).trigger('change');

    $('.tbImagesNavigationStyle select').on("change", function() {
      $('.tbImagesNavigationSpacing, .tbImagesNavigationThumbsNum').toggleClass('tb_disabled', $(this).val() != 'thumbs');
      $('.tbImagesNavigationPosition').toggle($(this).val() == 'thumbs');
      $('.tbImagesNavigationDotsPosition').toggle($(this).val() != 'thumbs');
    }).trigger('change');

  });
</script>