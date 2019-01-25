<div id="banner_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Banner</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_banner_styles_holder">Banner Styles</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Edit Banner</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#banner_widget_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="banner_widget_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>">
            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the content block for the current language.</p>
            </div>

            <div class="s_row_1">
              <label><strong>Line 1</strong></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <div class="s_full clearfix">
                <textarea name="widget_data[lang][<?php echo $language_code; ?>][line_1]" rows="2"><?php echo $settings['lang'][$language_code]['line_1']; ?></textarea>
              </div>
            </div>

            <div class="s_row_1">
              <label><strong>Line 2</strong></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <div class="s_full clearfix">
                <textarea name="widget_data[lang][<?php echo $language_code; ?>][line_2]" rows="2"><?php echo $settings['lang'][$language_code]['line_2']; ?></textarea>
              </div>
            </div>

            <div class="s_row_1">
              <label><strong>Line 3</strong></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <div class="s_full clearfix">
                <textarea name="widget_data[lang][<?php echo $language_code; ?>][line_3]" rows="2"><?php echo $settings['lang'][$language_code]['line_3']; ?></textarea>
              </div>
            </div>

            <div class="s_row_1">
              <label><strong>Link</strong></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <div class="s_full">
                <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][url]" value="<?php echo $settings['lang'][$language_code]['url']; ?>" />
              </div>
              <span class="s_metric s_target">
                <select name="widget_data[lang][<?php echo $language_code; ?>][url_target]">
                  <option value="_self"<?php if($settings['lang'][$language_code]['url_target']  == '_self')  echo ' selected="selected"';?>>_self</option>
                  <option value="_blank"<?php if($settings['lang'][$language_code]['url_target'] == '_blank') echo ' selected="selected"';?>>_blank</option>
                </select>
              </span>
            </div>

            <div class="s_row_1">
              <label><strong>Text align</strong></label>
              <div class="s_select">
                <select name="widget_data[lang][<?php echo $language_code; ?>][text_align]">
                  <option value="left"<?php if($settings['lang'][$language_code]['text_align']  == 'left')  echo ' selected="selected"';?>>Left</option>
                  <option value="center"<?php if($settings['lang'][$language_code]['text_align'] == 'center') echo ' selected="selected"';?>>Center</option>
                  <option value="right"<?php if($settings['lang'][$language_code]['text_align'] == 'right') echo ' selected="selected"';?>>Right</option>
                  <option value="justify"<?php if($settings['lang'][$language_code]['text_align'] == 'justify') echo ' selected="selected"';?>>Justify</option>
                </select>
              </div>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
            </div>

            <div class="s_row_1">
              <label><strong>Text valign</strong></label>
              <div class="s_select">
                <select name="widget_data[lang][<?php echo $language_code; ?>][text_valign]">
                  <option value="top"<?php if($settings['lang'][$language_code]['text_valign']  == 'top')  echo ' selected="selected"';?>>Top</option>
                  <option value="middle"<?php if($settings['lang'][$language_code]['text_valign'] == 'middle') echo ' selected="selected"';?>>Middle</option>
                  <option value="bottom"<?php if($settings['lang'][$language_code]['text_valign'] == 'bottom') echo ' selected="selected"';?>>Bottom</option>
                </select>
              </div>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
            </div>

          </div>
          <?php endforeach; ?>

        </div>
        
        <fieldset class="tb_image_row">
          <legend>Banner image</legend>
          
          <div class="tb_wrap tb_gut_30">
            <div class="tb_col">
              <input type="hidden" name="widget_data[image]" value="<?php echo $settings['image']; ?>" id="banner_widget_image" />
              <span class="tb_thumb">
                <img src="<?php echo $settings['image_preview']; ?>" id="banner_widget_image_preview" class="image" onclick="image_upload('banner_widget_image', 'banner_widget_image_preview');" />
              </span>
            </div>
            <div class="tb_col tb_1_2">
              <div class="s_row_1">
                <label>Aspect Ratio</label>
                <input class="inline" type="text" name="widget_data[ratio_w]" value="<?php echo $settings['ratio_w']; ?>" size="5" />
                <span class="s_input_separator">&nbsp;/&nbsp;</span>
                <input class="inline" type="text" name="widget_data[ratio_h]" value="<?php echo $settings['ratio_h']; ?>" size="5" />
              </div>
              <div class="s_row_1">
                <label>Max Height</label>
                <input class="s_spinner" type="text" name="widget_data[max_height]" value="<?php echo $settings['max_height']; ?>" size="7" />
                <span class="s_metric">px</span>
                <p class="s_help right">0 for no maximum height.</p>
              </div>
              <div class="s_row_1">
                <label>Min Height</label>
                <input class="s_spinner" type="text" name="widget_data[min_height]" value="<?php echo $settings['min_height']; ?>" size="7" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_1">
                <label>Position</label>
                <div class="s_full clearfix tb_2_5">
                  <div class="s_select">
                    <select name="widget_data[image_position]">
                      <option value="center"<?php       if ($settings['image_position'] == 'center')       echo ' selected="selected"'; ?>>Center</option>
                      <option value="top"<?php          if ($settings['image_position'] == 'top')          echo ' selected="selected"'; ?>>Top</option>
                      <option value="top right"<?php    if ($settings['image_position'] == 'top right')    echo ' selected="selected"'; ?>>Top Right</option>
                      <option value="right"<?php        if ($settings['image_position'] == 'right')        echo ' selected="selected"'; ?>>Right</option>
                      <option value="bottom right"<?php if ($settings['image_position'] == 'bottom right') echo ' selected="selected"'; ?>>Botttom Right</option>
                      <option value="bottom"<?php       if ($settings['image_position'] == 'bottom')       echo ' selected="selected"'; ?>>Bottom</option>
                      <option value="bottom left"<?php  if ($settings['image_position'] == 'bottom left')  echo ' selected="selected"'; ?>>Bottom Left</option>
                      <option value="left"<?php         if ($settings['image_position'] == 'left')         echo ' selected="selected"'; ?>>Left</option>
                      <option value="top left"<?php     if ($settings['image_position'] == 'top left')     echo ' selected="selected"'; ?>>Top Left</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </fieldset>

      </div>

      <div id="widget_banner_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix">
        <h2>Banner Styles</h2>
        <fieldset>
          <legend>Image hover</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5 tbBannerToggleZoom">
              <label>Zoom</label>
              <input type="hidden" name="widget_data[hover_zoom]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[hover_zoom]" value="1"<?php if($settings['hover_zoom'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <div class="s_row_2 tb_col tb_1_5 tbBannerToggleColor">
              <label>Color overlay</label>
              <input type="hidden" name="widget_data[hover_color]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[hover_color]" value="1"<?php if($settings['hover_color'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
          </div>
        </fieldset>
        <fieldset class="tbBannerHoverZoom">
          <legend>Image Zoom</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Origin</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[zoom_origin]">
                    <option value="0"<?php            if ($settings['zoom_origin'] == '0')            echo ' selected="selected"'; ?>>Center</option>
                    <option value="top"<?php          if ($settings['zoom_origin'] == 'top')          echo ' selected="selected"'; ?>>Top</option>
                    <option value="top right"<?php    if ($settings['zoom_origin'] == 'top right')    echo ' selected="selected"'; ?>>Top Right</option>
                    <option value="right"<?php        if ($settings['zoom_origin'] == 'right')        echo ' selected="selected"'; ?>>Right</option>
                    <option value="bottom right"<?php if ($settings['zoom_origin'] == 'bottom right') echo ' selected="selected"'; ?>>Botttom Right</option>
                    <option value="bottom"<?php       if ($settings['zoom_origin'] == 'bottom')       echo ' selected="selected"'; ?>>Bottom</option>
                    <option value="bottom left"<?php  if ($settings['zoom_origin'] == 'bottom left')  echo ' selected="selected"'; ?>>Bottom Left</option>
                    <option value="left"<?php         if ($settings['zoom_origin'] == 'left')         echo ' selected="selected"'; ?>>Left</option>
                    <option value="top left"<?php     if ($settings['zoom_origin'] == 'top left')     echo ' selected="selected"'; ?>>Top Left</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Zoom</label>
              <input class="s_spinner" type="text" name="widget_data[zoom_size]" value="<?php echo $settings['zoom_size']; ?>" min="1.01" step="0.01" size="7" />
            </div>
          </div>
        </fieldset>
        <fieldset class="tbBannerHoverColor">
          <legend>Color overlay</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Opacity</label>
              <input class="s_spinner" type="text" name="widget_data[color_opacity]" value="<?php echo $settings['color_opacity']; ?>" min="0.05" step="0.05" max="1" size="7" />
            </div>
          </div>
        </fieldset>
        <fieldset class="tbBannerLine1">
          <legend>Line 1</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding top</label>
              <input class="s_spinner" type="text" name="widget_data[line_1_padding_top]" value="<?php echo $settings['line_1_padding_top']; ?>" min="0" step="5" max="50" size="7" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding bottom</label>
              <input class="s_spinner" type="text" name="widget_data[line_1_padding_bottom]" value="<?php echo $settings['line_1_padding_bottom']; ?>" min="0" step="5" max="50" size="7" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Show on hover</label>
              <input type="hidden" name="widget_data[line_1_hover]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[line_1_hover]" value="1"<?php if($settings['line_1_hover'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Move direction</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[line_1_move_direction]">
                    <option value="top"<?php    if ($settings['line_1_move_direction'] == 'top')    echo ' selected="selected"'; ?>>From top</option>
                    <option value="right"<?php  if ($settings['line_1_move_direction'] == 'right')  echo ' selected="selected"'; ?>>From right</option>
                    <option value="bottom"<?php if ($settings['line_1_move_direction'] == 'bottom') echo ' selected="selected"'; ?>>From bottom</option>
                    <option value="left"<?php   if ($settings['line_1_move_direction'] == 'left')   echo ' selected="selected"'; ?>>From left</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Show delay</label>
              <input class="s_spinner" type="text" name="widget_data[line_1_show_delay]" value="<?php echo $settings['line_1_show_delay']; ?>" min="0" step="100" size="7" />
              <span class="s_metric">ms</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Hide delay</label>
              <input class="s_spinner" type="text" name="widget_data[line_1_hide_delay]" value="<?php echo $settings['line_1_hide_delay']; ?>" min="0" step="100" size="7" />
              <span class="s_metric">ms</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Offset</label>
              <input class="s_spinner" type="text" name="widget_data[line_1_offset]" value="<?php echo $settings['line_1_offset']; ?>" min="0" step="5" size="7" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>
        <fieldset class="tbBannerLine2">
          <legend>Line 2</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding top</label>
              <input class="s_spinner" type="text" name="widget_data[line_2_padding_top]" value="<?php echo $settings['line_2_padding_top']; ?>" min="0" step="5" max="50" size="7" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding bottom</label>
              <input class="s_spinner" type="text" name="widget_data[line_2_padding_bottom]" value="<?php echo $settings['line_2_padding_bottom']; ?>" min="0" step="5" max="50" size="7" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Show on hover</label>
              <input type="hidden" name="widget_data[line_2_hover]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[line_2_hover]" value="1"<?php if($settings['line_2_hover'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Move direction</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[line_2_move_direction]">
                    <option value="top"<?php    if ($settings['line_2_move_direction'] == 'top')    echo ' selected="selected"'; ?>>From top</option>
                    <option value="right"<?php  if ($settings['line_2_move_direction'] == 'right')  echo ' selected="selected"'; ?>>From right</option>
                    <option value="bottom"<?php if ($settings['line_2_move_direction'] == 'bottom') echo ' selected="selected"'; ?>>From bottom</option>
                    <option value="left"<?php   if ($settings['line_2_move_direction'] == 'left')   echo ' selected="selected"'; ?>>From left</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Show delay</label>
              <input class="s_spinner" type="text" name="widget_data[line_2_show_delay]" value="<?php echo $settings['line_2_show_delay']; ?>" min="0" step="100" size="7" />
              <span class="s_metric">ms</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Hide delay</label>
              <input class="s_spinner" type="text" name="widget_data[line_2_hide_delay]" value="<?php echo $settings['line_2_hide_delay']; ?>" min="0" step="100" size="7" />
              <span class="s_metric">ms</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Offset</label>
              <input class="s_spinner" type="text" name="widget_data[line_2_offset]" value="<?php echo $settings['line_2_offset']; ?>" min="0" step="5" size="7" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>
        <fieldset class="tbBannerLine3">
          <legend>Line 3</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding top</label>
              <input class="s_spinner" type="text" name="widget_data[line_3_padding_top]" value="<?php echo $settings['line_3_padding_top']; ?>" min="0" step="5" max="50" size="7" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Padding bottom</label>
              <input class="s_spinner" type="text" name="widget_data[line_3_padding_bottom]" value="<?php echo $settings['line_3_padding_bottom']; ?>" min="0" step="5" max="50" size="7" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Show on hover</label>
              <input type="hidden" name="widget_data[line_3_hover]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[line_3_hover]" value="1"<?php if($settings['line_3_hover'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Move direction</label>
              <div class="s_full clearfix">
                <div class="s_select">
                  <select name="widget_data[line_3_move_direction]">
                    <option value="top"<?php    if ($settings['line_3_move_direction'] == 'top')    echo ' selected="selected"'; ?>>From top</option>
                    <option value="right"<?php  if ($settings['line_3_move_direction'] == 'right')  echo ' selected="selected"'; ?>>From right</option>
                    <option value="bottom"<?php if ($settings['line_3_move_direction'] == 'bottom') echo ' selected="selected"'; ?>>From bottom</option>
                    <option value="left"<?php   if ($settings['line_3_move_direction'] == 'left')   echo ' selected="selected"'; ?>>From left</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Show delay</label>
              <input class="s_spinner" type="text" name="widget_data[line_3_show_delay]" value="<?php echo $settings['line_3_show_delay']; ?>" min="0" step="100" size="7" />
              <span class="s_metric">ms</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Hide delay</label>
              <input class="s_spinner" type="text" name="widget_data[line_3_hide_delay]" value="<?php echo $settings['line_3_hide_delay']; ?>" min="0" step="100" size="7" />
              <span class="s_metric">ms</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Offset</label>
              <input class="s_spinner" type="text" name="widget_data[line_3_offset]" value="<?php echo $settings['line_3_offset']; ?>" min="0" step="5" size="7" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>
      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
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
  $("#widget_settings_holder")
    .find(".tbLanguageTabs").first().tabs().end().end()
    .on('change', "input[name$='[image]']", function() {
      var img = new Image();
      var $el = $(this).closest(".tb_image_row");

      img.onload = function() {
        $el
          .find("input[name$='[ratio_w]']").val(this.width).end()
          .find("input[name$='[ratio_h]']").val(this.height);
      };

      img.src = $sReg.get("/tb/url/image_url") + $(this).val();
    });

  $('.tbBannerToggleZoom input').bind('change', function() {
      $('.tbBannerHoverZoom').toggle($(this).is(':checked'));
      $('.tbBannerToggleMove').toggleClass('tb_disabled', $(this).is(':checked'));
  }).trigger('change');
  $('.tbBannerToggleMove input').bind('change', function() {
      $('.tbBannerHoverMove').toggle($(this).is(':checked'));
      $('.tbBannerToggleZoom').toggleClass('tb_disabled', $(this).is(':checked'));
  }).trigger('change');
  $('.tbBannerToggleColor input').bind('change', function() {
      $('.tbBannerHoverColor').toggle($(this).is(':checked'));
  }).trigger('change');

});
</script>