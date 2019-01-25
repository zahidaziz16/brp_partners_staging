  <div class="tb_tabs tb_subpanel_tabs tbEditSliderPanel">

  <div class="tb_tabs_nav tbSliderTabsNavigation">
    <ul class="clearfix">
      <li><a href="#fireslider_settings">Settings</a></li>
      <li><a href="#fireslider_slides">Slides</a></li>
    </ul>
  </div>

  <form id="slider_edit_form" action="<?php echo $tbUrl->generate('default/saveSlider'); ?>" method="post">

    <div id="fireslider_settings" class="tb_subpanel">
      <h2><?php if ($action == 'add') echo 'New:'; else echo 'Edit:'; ?> Slider Settings</h2>

      <input type="hidden" name="slider[id]" value="<?php echo $slider['id']; ?>" />

      <div class="s_row_1 first">
        <label>Name</label>
        <input id="slider_name" type="text" name="slider[name]" value="<?php echo $slider['name']; ?>" size="40" />
      </div>

      <fieldset>
        <legend>Style</legend>
        <div class="tb_wrap tb_gut_30">
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbLayoutOptionRow">
            <label>Layout</label>
            <div class="s_select">
              <select name="slider[layout]">
                <option value="fixed"<?php      if ($slider['layout'] == 'fixed')      echo ' selected="selected"'; ?>>Fixed</option>
                <option value="fullwidth"<?php  if ($slider['layout'] == 'fullwidth')  echo ' selected="selected"'; ?>>Full width</option>
                <option value="fullscreen"<?php if ($slider['layout'] == 'fullscreen') echo ' selected="selected"'; ?>>Full screen</option>
              </select>
            </div>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Header Style</label>
            <div class="s_select">
              <select name="slider[header_style]">
                <option value="visible"<?php if ($slider['header_style'] == 'visible') echo ' selected="selected"'; ?>>Visible</option>
                <option value="hidden" <?php if ($slider['header_style'] == 'hidden')  echo ' selected="selected"'; ?>>Hidden</option>
                <option value="overlay"<?php if ($slider['header_style'] == 'overlay') echo ' selected="selected"'; ?>>Overlay</option>
              </select>
            </div>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
            <label>Slides viewport</label>
            <div class="s_select">
              <select name="slider[viewport]">
                <option value="fill"<?php    if ($slider['viewport'] == 'fill')    echo ' selected="selected"'; ?>>Fill</option>
                <option value="center"<?php  if ($slider['viewport'] == 'center')  echo ' selected="selected"'; ?>>Center</option>
                <option value="fit"<?php     if ($slider['viewport'] == 'fit')     echo ' selected="selected"'; ?>>Fit</option>
                <option value="stretch"<?php if ($slider['viewport'] == 'stretch') echo ' selected="selected"'; ?>>Stretch</option>
              </select>
            </div>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbAutoScaleOptionRow">
            <label>Auto scale</label>
            <input type="hidden" name="slider[autoscale]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="slider[autoscale]" value="1"<?php if($slider['autoscale'] == 1) echo ' checked="checked"'; ?> /><span></span><span></span></label>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbTransparentHeaderOptionRow">
            <label>Transparent header</label>
            <input type="hidden" name="slider[transparent_header]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="slider[transparent_header]" value="1"<?php if($slider['transparent_header'] == 1) echo ' checked="checked"'; ?> /><span></span><span></span></label>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend>Dimensions</legend>
        <div class="tb_wrap tb_gut_30">
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Width</label>
            <input id="slider_width" class="s_spinner" type="text" name="slider[width]" value="<?php echo $slider['width']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Height</label>
            <input id="slider_height" class="s_spinner" type="text" name="slider[height]" value="<?php echo $slider['height']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Responsive width</label>
            <input id="slider_responsive_width" class="s_spinner" type="text" name="slider[responsive_width]" value="<?php echo $slider['responsive_width']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Responsive height</label>
            <input id="slider_responsive_height" class="s_spinner" type="text" name="slider[responsive_height]" value="<?php echo $slider['responsive_height']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Minimum height</label>
            <input id="slider_minimum_height" class="s_spinner" type="text" name="slider[min_height]" value="<?php echo $slider['min_height']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Maximum height</label>
            <input id="slider_minimum_height" class="s_spinner" type="text" name="slider[max_height]" value="<?php echo $slider['max_height']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
          </div>
        </div>
      </fieldset>

      <fieldset class="tbSceneOptions">
        <legend>Scene</legend>
        <div class="tb_wrap tb_gut_30">
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Maximum width</label>
            <input id="slider_scene_max_width" class="s_spinner" type="text" name="slider[scene_width]" value="<?php echo $slider['scene_width']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Maximum height</label>
            <input id="slider_scene_max_width" class="s_spinner" type="text" name="slider[scene_height]" value="<?php echo $slider['scene_height']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Offset top/bottom</label>
            <input id="slider_offset_top" class="s_spinner" type="text" name="slider[offset_top]" value="<?php echo $slider['offset_top']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Offset left/right</label>
            <input id="slider_offset_left" class="s_spinner" type="text" name="slider[offset_left]" value="<?php echo $slider['offset_left']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
          </div>
        </div>
      </fieldset>

      <fieldset class="tbManualScaling">
        <legend>Manual scaling</legend>
        <div class="tbSliderSizes">
          <?php $i=0; foreach ($slider['sizes'] as $size): ?>
          <div class="tb_wrap s_mb_20 tbSliderSizeRow">
            <div class="s_row_1 tb_col tb_live_row_1 tb_live_1_1">
              <label class="inline">Max width</label>
              <input class="s_spinner" type="text" name="slider[sizes][<?php echo $i; ?>][max_width]" value="<?php echo $size['max_width']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_1 tb_col tb_live_row_1 tb_live_1_1">
              <label class="inline">Height</label>
              <input class="s_spinner" type="text" name="slider[sizes][<?php echo $i; ?>][height]" value="<?php echo $size['height']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_1 tb_col tb_1_4">
              <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbRemoveSliderSize">Remove</a>
            </div>
          </div>
          <?php $i++; endforeach; ?>
        </div>
        <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddSliderSize">Add rule</a>
      </fieldset>

      <fieldset>
        <legend>Slides animation</legend>
        <div class="tb_wrap tb_gut_30">
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
            <label>Transition Speed</label>
            <input id="slider_speed" class="s_spinner" type="text" name="slider[speed]" value="<?php echo $slider['speed']; ?>" size="7" min="0" step="100" />
            <span class="s_metric">ms</span>
          </div>
          <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
            <label>Easing</label>
            <div class="s_select">
              <select name="slider[easing]">
                <option value="linear"<?php if ($slider['easing'] == 'linear') echo ' selected="selected"'; ?>>linear</option>
                <option value="swing"<?php  if ($slider['easing'] == 'swing')  echo ' selected="selected"'; ?>>swing</option>
                <option value="easeInQuad"<?php if ($slider['easing'] == 'easeInQuad') echo ' selected="selected"'; ?>>easeInQuad</option>
                <option value="easeOutQuad"<?php if ($slider['easing'] == 'easeOutQuad') echo ' selected="selected"'; ?>>easeOutQuad</option>
                <option value="easeInOutQuad"<?php if ($slider['easing'] == 'easeInOutQuad') echo ' selected="selected"'; ?>>easeInOutQuad</option>
                <option value="easeInCubic"<?php if ($slider['easing'] == 'easeInCubic') echo ' selected="selected"'; ?>>easeInCubic</option>
                <option value="easeOutCubic"<?php if ($slider['easing'] == 'easeOutCubic') echo ' selected="selected"'; ?>>easeOutCubic</option>
                <option value="easeInOutCubic"<?php if ($slider['easing'] == 'easeInOutCubic') echo ' selected="selected"'; ?>>easeInOutCubic</option>
                <option value="easeInQuart"<?php if ($slider['easing'] == 'easeInQuart') echo ' selected="selected"'; ?>>easeInQuart</option>
                <option value="easeOutQuart"<?php if ($slider['easing'] == 'easeOutQuart') echo ' selected="selected"'; ?>>easeOutQuart</option>
                <option value="easeInOutQuart"<?php if ($slider['easing'] == 'easeInOutQuart') echo ' selected="selected"'; ?>>easeInOutQuart</option>
                <option value="easeInQuint"<?php if ($slider['easing'] == 'easeInQuint') echo ' selected="selected"'; ?>>easeInQuint</option>
                <option value="easeOutQuint"<?php if ($slider['easing'] == 'easeOutQuint') echo ' selected="selected"'; ?>>easeOutQuint</option>
                <option value="easeInOutQuint"<?php if ($slider['easing'] == 'easeInOutQuint') echo ' selected="selected"'; ?>>easeInOutQuint</option>
                <option value="easeInSine"<?php if ($slider['easing'] == 'easeInSine') echo ' selected="selected"'; ?>>easeInSine</option>
                <option value="easeOutSine"<?php if ($slider['easing'] == 'easeOutSine') echo ' selected="selected"'; ?>>easeOutSine</option>
                <option value="easeInOutSine"<?php if ($slider['easing'] == 'easeInOutSine') echo ' selected="selected"'; ?>>easeInOutSine</option>
                <option value="easeInExpo"<?php if ($slider['easing'] == 'easeInExpo') echo ' selected="selected"'; ?>>easeInExpo</option>
                <option value="easeOutExpo"<?php if ($slider['easing'] == 'easeOutExpo') echo ' selected="selected"'; ?>>easeOutExpo</option>
                <option value="easeInOutExpo"<?php if ($slider['easing'] == 'easeInOutExpo') echo ' selected="selected"'; ?>>easeInOutExpo</option>
                <option value="easeInCirc"<?php if ($slider['easing'] == 'easeInCirc') echo ' selected="selected"'; ?>>easeInCirc</option>
                <option value="easeOutCirc"<?php if ($slider['easing'] == 'easeOutCirc') echo ' selected="selected"'; ?>>easeOutCirc</option>
                <option value="easeInOutCirc"<?php if ($slider['easing'] == 'easeInOutCirc') echo ' selected="selected"'; ?>>easeInOutCirc</option>
                <option value="easeInElastic"<?php if ($slider['easing'] == 'easeInElastic') echo ' selected="selected"'; ?>>easeInElastic</option>
                <option value="easeOutElastic"<?php if ($slider['easing'] == 'easeOutElastic') echo ' selected="selected"'; ?>>easeOutElastic</option>
                <option value="easeInOutElastic"<?php if ($slider['easing'] == 'easeInOutElastic') echo ' selected="selected"'; ?>>easeInOutElastic</option>
                <option value="easeInBack"<?php if ($slider['easing'] == 'easeInBack') echo ' selected="selected"'; ?>>easeInBack</option>
                <option value="easeOutBack"<?php if ($slider['easing'] == 'easeOutBack') echo ' selected="selected"'; ?>>easeOutBack</option>
                <option value="easeInOutBack"<?php if ($slider['easing'] == 'easeInOutBack') echo ' selected="selected"'; ?>>easeInOutBack</option>
                <option value="easeInBounce"<?php if ($slider['easing'] == 'easeInBounce') echo ' selected="selected"'; ?>>easeInBounce</option>
                <option value="easeOutBounce"<?php if ($slider['easing'] == 'easeOutBounce') echo ' selected="selected"'; ?>>easeOutBounce</option>
                <option value="easeInOutBounce"<?php if ($slider['easing'] == 'easeInOutBounce') echo ' selected="selected"'; ?>>easeInOutBounce</option>
              </select>
            </div>
          </div>
        </div>
      </fieldset>
      <fieldset>
        <legend>Slideshow</legend>
        <div class="tb_wrap tb_gut_30">
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
            <label>Auto play</label>
            <input type="hidden" name="slider[autoplay]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="slider[autoplay]" value="1"<?php if($slider['autoplay'] == 1) echo ' checked="checked"'; ?> /><span></span><span></span></label>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Slide time</label>
            <input id="slider_speed" class="s_spinner" type="text" name="slider[pause_time]" value="<?php echo $slider['pause_time']; ?>" size="7" min="0" step="100" />
            <span class="s_metric">ms</span>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingsRow">
            <label>Pause on hover</label>
            <input type="hidden" name="slider[hover_pause]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="slider[hover_pause]" value="1"<?php if($slider['hover_pause'] == 1) echo ' checked="checked"'; ?> /><span></span><span></span></label>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
            <label class="tb_two_lines">Start random</label>
            <input type="hidden" name="slider[random]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="slider[random]" value="1"<?php if($slider['random'] == 1) echo ' checked="checked"'; ?> /><span></span><span></span></label>
          </div>
        </div>
      </fieldset>
      <fieldset>
        <legend>Custom CSS</legend>
        <div class="s_row_2 tb_live_row_1 tb_live_1_1">
          <textarea class="tb_1_1" name="slider[custom_css]" rows="10"><?php echo $slider['custom_css']; ?></textarea>
        </div>
      </fieldset>

    </div>

    <div id="fireslider_slides" class="tb_subpanel">
      <h2>Slider slides</h2>
      <div class="s_sortable_holder tb_style_1 tbSlidesContainer"><?php $i = 0; foreach ($slider['slides'] as $slide): ?><div class="s_sortable_row<?php if ($i > 0): ?> tb_closed<?php else: ?> tb_opened<?php endif; ?> tbSlideRow tbRow">
          <input type="hidden" class="tbSlideRowNum" value="<?php echo $i; ?>" />
          <textarea name="slider[slides][<?php echo $i; ?>][layers]" style="display: none;"><?php echo json_encode($slide['layers']); ?></textarea>
          <div class="s_actions">
            <div class="s_buttons_group">
              <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_plus_10 tbSlideLayers">Layers</a>
              <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_copy_10 tbDuplicateSlide"></a>
              <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveRow"></a>
            </div>
          </div>
          <a class="tb_toggle_row tbToggleRow" href="javascript:;">Toggle</a>
          <h3 class="s_drag_area"><span class="tbRowTitle">Slide <span><?php echo $i+1; ?></span></span></h3>
          <div class="s_sortable_contents"<?php if ($i > 0): ?> style="display: none;"<?php endif; ?>>
            <?php // Slide Type ?>
            <div class="s_row_1">
              <label>Type</label>
              <div class="s_select">
                <select name="slider[slides][<?php echo $i; ?>][type]">
                  <option value="image"<?php if ($slide['type']  == 'image'):  ?> selected="selected"<?php endif; ?>>Image</option>
                  <option value="video"<?php if ($slide['type']  == 'video'):  ?> selected="selected"<?php endif; ?>>Video</option>
                  <option value="iframe"<?php if ($slide['type'] == 'iframe'): ?> selected="selected"<?php endif; ?>>Iframe</option>
                </select>
              </div>
            </div>
            <?php // CSS Class ?>
            <div class="s_row_1">
              <label>Class</label>
              <input type="text" name="slider[slides][<?php echo $i; ?>][class]" value="<?php echo $slide['class']; ?>" />
            </div>
            <?php // Image Cover ?>
            <div class="s_row_1">
              <label>Image Cover</label>
              <input type="hidden" name="slider[slides][<?php echo $i; ?>][cover]" value="<?php echo $slide['cover']; ?>" id="slider_slides_cover_<?php echo $i; ?>" />
              <span class="tb_thumb">
                <img src="<?php echo $slide['cover_image_preview']; ?>" id="slider_slides_cover_preview_<?php echo $i; ?>" class="image" onclick="image_upload('slider_slides_cover_<?php echo $i; ?>', 'slider_slides_cover_preview_<?php echo $i; ?>');" />
                <a class="block align_center s_mt_5" href="javascript:;" onclick="$(this).prev().attr('src', $sReg.get('/tb/no_image'));$(this).closest('.s_row_1').find('> input').val('');">Remove</a>
              </span>
            </div>
            <?php // Cover Viewport ?>
            <div class="s_row_1">
              <label>Cover Viewport</label>
              <div class="s_select">
                <select name="slider[slides][<?php echo $i; ?>][viewport]">
                  <option value=""<?php        if ($slide['viewport'] == ''):        ?> selected="selected"<?php endif; ?>>Inherit</option>
                  <option value="fill"<?php    if ($slide['viewport'] == 'fill'):    ?> selected="selected"<?php endif; ?>>Fill</option>
                  <option value="fit"<?php     if ($slide['viewport'] == 'fit'):     ?> selected="selected"<?php endif; ?>>Fit</option>
                  <option value="stretch"<?php if ($slide['viewport'] == 'stretch'): ?> selected="selected"<?php endif; ?>>Stretch</option>
                  <option value="center"<?php  if ($slide['viewport'] == 'center'):  ?> selected="selected"<?php endif; ?>>Center</option>
                </select>
              </div>
            </div>
            <?php // Tiled cover ?>
            <div class="s_row_1">
              <label>Tiled cover</label>
              <input type="hidden" name="slider[slides][<?php echo $i; ?>][background_repeat]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="slider[slides][<?php echo $i; ?>][background_repeat]" value="1"<?php if($slide['background_repeat'] == 1) echo ' checked="checked"'; ?> /><span></span><span></span></label>
            </div>
            <?php // Video Url ?>
            <div class="s_row_1 tbSlideVideo">
              <label>Video url</label>
              <input type="text" name="slider[slides][<?php echo $i; ?>][video]" value="<?php echo $slide['video']; ?>" />
            </div>
            <?php // Iframe Url ?>
            <div class="s_row_1 tbSlideIframe">
              <label>Iframe url</label>
              <input type="text" name="slider[slides][<?php echo $i; ?>][source]" value="<?php echo $slide['source']; ?>" />
            </div>
            <?php // Slide Link ?>
            <div class="s_row_1">
              <label>Slide Link</label>
              <input type="text" name="slider[slides][<?php echo $i; ?>][link_url]" value="<?php echo $slide['link_url']; ?>" />
              <span class="s_metric">
                <select name="slider[slides][<?php echo $i; ?>][link_target]">
                  <option value="_self"<?php  if ($slide['link_target'] == '_self'):  ?> selected="selected"<?php endif; ?>>_self</option>
                  <option value="_blank"<?php if ($slide['link_target'] == '_blank'): ?> selected="selected"<?php endif; ?>>_blank</option>
                </select>
              </span>
            </div>
            <?php // Background Color ?>
            <div class="s_row_1">
              <label>Background color</label>
              <div class="colorSelector tbBgColor"><div style="background-color: <?php echo $slide['bg_color']; ?>;"></div></div>
              <input type="text" name="slider[slides][<?php echo $i; ?>][bg_color]" value="<?php echo $slide['bg_color']; ?>" />
            </div>

            <?php
            isset($slide['kenburns'])           || $slide['kenburns'] = 0;
            isset($slide['kenburns_animation']) || $slide['kenburns_animation'] = 'zoomIn';
            isset($slide['kenburns_zoom'])      || $slide['kenburns_zoom']      = 150;
            isset($slide['kenburns_timing'])    || $slide['kenburns_timing']    = 20;
            isset($slide['kenburns_origin'])    || $slide['kenburns_origin']    = 'center';
            ?>

            <fieldset>
              <legend>Ken Burns effect</legend>
              <div class="s_actions">
                <input type="hidden" name="slider[slides][<?php echo $i; ?>][kenburns]" value="0" />
                <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="slider[slides][<?php echo $i; ?>][kenburns]" value="1"<?php if($slide['kenburns'] == 1) echo ' checked="checked"'; ?> /><span></span><span></span></label>
              </div>
              <div class="tb_wrap tb_gut_30">
                <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                  <label>Animation</label>
                  <div class="s_full clearfix">
                    <select name="slider[slides][<?php echo $i; ?>][kenburns_animation]">
                      <option value="zoomIn">Zoom in</option>
                      <option value="zoomOut">Zoom out</option>
                    </select>
                  </div>
                </div>
                <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                  <label>Zoom level</label>
                  <input class="s_spinner" type="text" name="slider[slides][<?php echo $i; ?>][kenburns_zoom]" value="<?php echo $slide['kenburns_zoom']; ?>" size="7" min="110" />
                  <span class="s_metric">%</span>
                </div>
                <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                  <label>Speed</label>
                  <input class="s_spinner" type="text" name="slider[slides][<?php echo $i; ?>][kenburns_timing]" value="<?php echo $slide['kenburns_timing']; ?>" size="7" min="2" />
                  <span class="s_metric">s</span>
                </div>
                <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
                  <label>Zoom origin</label>
                  <div class="s_select">
                    <select name="slider[slides][<?php echo $i; ?>][kenburns_origin]">
                      <option<?php if($slide['kenburns_origin'] == 'center') echo ' selected="selected"'; ?> value="center" selected="selected">Center</option>
                      <option<?php if($slide['kenburns_origin'] == 'top') echo ' selected="selected"'; ?> value="top">Top</option>
                      <option<?php if($slide['kenburns_origin'] == 'top right') echo ' selected="selected"'; ?> value="top right">Top Right</option>
                      <option<?php if($slide['kenburns_origin'] == 'right') echo ' selected="selected"'; ?> value="right">Right</option>
                      <option<?php if($slide['kenburns_origin'] == 'bottom right') echo ' selected="selected"'; ?> value="bottom right">Botttom Right</option>
                      <option<?php if($slide['kenburns_origin'] == 'bottom') echo ' selected="selected"'; ?> value="bottom">Bottom</option>
                      <option<?php if($slide['kenburns_origin'] == 'bottom left') echo ' selected="selected"'; ?> value="bottom left">Bottom Left</option>
                      <option<?php if($slide['kenburns_origin'] == 'left') echo ' selected="selected"'; ?> value="left">Left</option>
                      <option<?php if($slide['kenburns_origin'] == 'top left') echo ' selected="selected"'; ?> value="top left">Top Left</option>
                    </select>
                  </div>
                </div>
              </div>
            </fieldset>

          </div>
        </div><?php $i++; endforeach; ?></div>

      <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddSlide">Add Slide</a>
    </div>

  </form>

</div>

<div class="s_submit clearfix">
  <div class="left">
    <a href="javascript:;" class="s_button s_white s_h_40 tbCancelSlider">Back to sliders</a>
  </div>
  <a href="javascript:;" class="s_button s_red s_h_40 tbSaveSlider">Save Slider</a>
</div>

<script type="text/javascript">
(function ($) {

// Beautify Form
beautifyForm($('#fireslider_settings'));

var $container = $("#tb_fireslider_tab_content > .tbEditSliderPanel");
var $containerPanel = $container.parent().tabs();

$container.find('[name="slider[layout]"]').bind("change", function() {
  $container.find('input[name="slider[max_height]"]').closest(".tbSettingsRow").toggle($(this).val() == 'fullscreen');
  if ($(this).val() == 'fixed') {
      $container.find('input[name="slider[responsive_height]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[responsive_width]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[min_height]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[offset_top]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[offset_left]"]').closest(".tbSettingsRow").hide();
      $container.find('select[name="slider[header_style]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[width]"]').closest(".tbSettingsRow").show();
      $container.find('input[name="slider[height]"]').closest(".tbSettingsRow").show();
      $container.find('.tbSceneOptions').hide();
      $container.find('.tbTransparentHeaderOptionRow').hide();
      $('.tbAutoScaleOptionRow').removeClass('tb_disabled');
  }
  if ($(this).val() == 'fullwidth') {
      $container.find('input[name="slider[width]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[responsive_height]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[min_height]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[offset_top]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[offset_left]"]').closest(".tbSettingsRow").hide();
      $container.find('select[name="slider[header_style]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[responsive_width]"]').closest(".tbSettingsRow").show();
      $container.find('input[name="slider[height]"]').closest(".tbSettingsRow").show();
      $container.find('.tbSceneOptions').hide();
      $container.find('.tbTransparentHeaderOptionRow').hide();
      $('.tbAutoScaleOptionRow').removeClass('tb_disabled');
  }
  if ($(this).val() == 'fullscreen') {
      $container.find('input[name="slider[width]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[height]"]').closest(".tbSettingsRow").hide();
      $container.find('input[name="slider[responsive_width]"]').closest(".tbSettingsRow").show();
      $container.find('input[name="slider[responsive_height]"]').closest(".tbSettingsRow").show();
      $container.find('input[name="slider[min_height]"]').closest(".tbSettingsRow").show();
      $container.find('input[name="slider[offset_top]"]').closest(".tbSettingsRow").show();
      $container.find('input[name="slider[offset_left]"]').closest(".tbSettingsRow").show();
      $container.find('select[name="slider[header_style]"]').closest(".tbSettingsRow").show();
      $container.find('.tbTransparentHeaderOptionRow').toggle($container.find('select[name="slider[header_style]"]').val() == 'overlay');
      $container.find('.tbSceneOptions').show();
      $('.tbAutoScaleOptionRow').addClass('tb_disabled');
      $('.tbAutoScaleOptionRow').find('input[name="slider[autoscale]"]').prop('checked', true).trigger('change');
  }
}).trigger("change");

$container.find('select[name="slider[header_style]"]').bind("change", function() {
  $container.find('[name="slider[layout]"]').trigger('change');
  $container.find('.tbTransparentHeaderOptionRow').toggle($(this).val() == 'overlay' && $container.find('[name="slider[layout]"]').val() == 'fullscreen');
}).trigger('change');

$container.find('input[name="slider[autoscale]"]').bind("change", function() {
  $container.find(".tbManualScaling").toggle(!$(this).is(":checked"));
}).trigger("change");

$container.find('input[name="slider[autoplay]"]').bind("change", function() {
  $container.find('input[name="slider[pause_time]"]').closest(".tbSettingsRow").toggleClass("tb_disabled", !$(this).is(":checked"));
  $container.find('input[name="slider[hover_pause]"]').closest(".tbSettingsRow").toggleClass("tb_disabled", !$(this).is(":checked"));
}).trigger("change");

var size_rows_num = $container.find(".tbSliderSizeRow").length;

$container.find(".tbAddSliderSize").bind("click", function() {
  var output = Mustache.render($("#fireslider_size_template").text(), {
    row_num: size_rows_num++
  });

  $(output).appendTo($container.find('.tbSliderSizes')).find(".s_spinner").each(function() {
    $(this).spinner({
      mouseWheel: true
    });
  });

  return false;
}).parent().on("click", ".tbRemoveSliderSize", function() {
  if (confirm("Are you sure?")) {
    $(this).parents('.tbSliderSizeRow').first().remove();
  }
});

$container.on("click", ".tbToggleRow", function() {
  var $row = $(this).closest(".tbRow");

  if ($row.hasClass("tb_closed")) {
    if ($row.is(".tbLayerRow")) {
      var $previous_row = $row.parent().find("> .tbLayerRow.tb_opened");
      if ($previous_row.length) {
        updateLayerRowTitleParameters($previous_row);
      }

    }

    $row.parent().find("> .tbRow").removeClass("tb_opened").addClass("tb_closed");
    $row.parent().find("> .tbRow .s_sortable_contents").slideUp();

    $row
      .find(".s_sortable_contents").slideDown().end()
      .removeClass("tb_closed")
      .addClass("tb_opened");
  }

  if ($row.is(".tbSlideRow")) {
    if (!$row.hasClass("tbSlideRowInit")) {
      beautifyForm($row);
      $row.addClass("tbSlideRowInit")
    }
  }
});

$container.find('.s_sortable_holder').sortable({
  handle: ".s_drag_area",
  tolerance: "pointer"
});

$containerPanel.find(".tbSaveSlider").bind("click", function() {
  if ($("#slider_name").val()) {
    $container.block("<h1>Saving...</h1>");

    $container.find(".tbLayersContainer").each(function() {
      var layers = [];

      $(this).find("> .tbRow").each(function() {
        layers.push(form2js(this, ".", false, function(node) {
          var $node = $(node);

          if ($node.is("input.tbAutoValue")) {
            if ($node.val() == "auto" || !tbHelper.is_numeric(($node.val()))) {
              return {
                name:  $node.attr("name"),
                value: ""
              };
            }
          }

          return false;
        }, false));
      });

      var num = parseInt($(this).parent().attr("id").match(/^.*_(\d+)$/)[1]);

      $container.find('textarea[name="slider[slides][' + num + '][layers]"]').text(JSON.stringify(layers));
    });

    $.getJSON("<?php echo $tbUrl->generateJs('default/checkPermissions'); ?>", function(response) {
      if (response.success == true) {
        $("#slider_edit_form").ajaxSubmit({
          type: "post",
          dataType: "json",
          success: function(response) {
            if (response.success != true) {
              displayAlertWarning(response.message);
            }
            $container.unblock();
          }
        });
      } else {
        displayAlertWarning(response.message);
        $container.unblock();
      }
    });
  } else {
    alert("Please, enter a set name!");
  }

  return false;
});

$containerPanel.find(".tbCancelSlider").bind("click", function() {
  $containerPanel
    .block("<h1>Loading...</h1>")
    .tabs("destroy")
    .load("<?php echo $tbUrl->generateJs('default/index'); ?>", function() {
      $containerPanel.unblock();
    });

  return false;
});

function initAccordionRow($row) {
  // Colorpicker Init
  $row.find(".colorSelector").each(function() {
    assignColorPicker($(this), $(this).hasClass("tbBgColor"));
  });

  // Spinner Init
  $row.find("input.s_spinner").each(function() {
    $(this).spinner({
      mouseWheel: true
    });
  });

  /*
  $row.find("textarea.ckeditor").each(function() {
    CKEDITOR.replace($(this)[0], {
      toolbar: [
        ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript', '-', 'RemoveFormat'],
        ['NumberedList', 'BulletedList'],
        ['Link', 'Unlink'],
        '/',
        ['Styles', 'Format'],
        ['SpecialChar'],
        ['Source'],
        ['Maximize']
      ]
    });
  });
  */
}

var slide_rows_num = $container.find(".tbSlideRow").length;

$container.find(".tbAddSlide").bind("click", function() {

  var output = Mustache.render($("#fireslider_slide_template").text(), {
    row_num: slide_rows_num++,
    row_title_num: $container.find(".tbSlideRow").length + 1,
    no_image: $sReg.get("/tb/no_image")
  });

  $row = $(output).appendTo($container.find(".tbSlidesContainer"));

  initAccordionRow($row);

  $row.find(".tbToggleRow").trigger("click");
  $row.find('select[name$="[type]"]').bind("change", function() {
    $row.find('.tbSlideVideo').toggle($(this).val() == 'video');
    $row.find('.tbSlideIframe').toggle($(this).val() == 'iframe');
  }).trigger("change");
  $row.find('[name*="[kenburns]"]').bind("change", function() {
    var $button = $(this);

    $button.closest("fieldset").find('.tb_wrap').toggleClass('tb_disabled', !$button.is(':checked'));
  }).trigger("change");

  return false;
});

$container.find(".tbSlideRow").each(function() {
  var $row = $(this);

  $row.find('select[name$="[type]"]').bind("change", function() {
    $row.find('.tbSlideVideo').toggle($(this).val() == 'video');
    $row.find('.tbSlideIframe').toggle($(this).val() == 'iframe');
  }).trigger("change")
});

$container.find(".tbSlideRow").each(function() {
  var $row = $(this);

  $row.find('[name*="[kenburns]"]').bind("change", function() {
    var $button = $(this);

    $button.closest("fieldset").find('.tb_wrap').toggleClass('tb_disabled', !$button.is(':checked'));
  }).trigger("change");
});

$container.on("click", ".tbRemoveRow", function() {
  if (confirm("Are you sure?")) {
    var $row = $(this).closest(".tbRow");
    var $nextRow = $row.next(".tbRow").length ?  $row.next(".tbRow") : $row.prev(".tbRow");

    if ($row.is(".tb_opened") && $nextRow.length) {
      $nextRow.find(".tbToggleRow").trigger("click");
    }

    if ($row.is(".tbSlideRow")) {
      var $layersTab = $container.find('a[href="#fireslider_slide_' + $row.find("input.tbSlideRowNum").val() + '"]');

      if ($layersTab.length) {
        $layersTab.parent().find(".tbCloseTab").trigger("click");
      }
    }

    var $rowParent = $row.parent();

    $row.remove();

    $rowParent.find(".tbRow").each(function(i) {
      $(this).find(".tbRowTitle span").text(i + 1);
    });
  }

  return false;
});

function updateLayerRowTitleParameters($layer_row) {
  var position = [];


  $.each(["top", "left", "right", "bottom"], function(i, key) {
    var $input_val = $layer_row.find('input[name="keyframes[0].style.' + key + '"]').val();

    if ($input_val) {
      position.push({label: tbHelper.ucfirst(key), value: $input_val});
    }
  });

  $layer_row.find(".tbLayerParameters").empty()
    .append('<span class="tb_label">Type</span><span>' + $layer_row.find('select[name="type"]').val() + '</span>')
    .append('<span class="tb_label">' + position[1].label + '</span><span>' + position[1].value + '</span>')
    .append('<span class="tb_label">' + position[0].label + '</span><span>' + position[0].value + '</span>')
    .append('<span class="tb_label">Speed</span><span>' + $layer_row.find('input[name="keyframes[0].speed"]').val() + '</span>')
    .append('<span class="tb_label">Delay</span><span>' + $layer_row.find('input[name="keyframes[0].delay"]').val() + '</span>');
}

var layer_rows_num = {};

$container.on("click", ".tbDuplicateSlide", function() {
  $container.find(".tbAddSlide").trigger("click");

  var $currentRow = $(this).closest(".tbSlideRow");

  $(this).closest(".tbSlidesContainer").find("> .tbSlideRow").last()
    .find("textarea[name$='[layers]']").text($currentRow.find("textarea[name$='[layers]']").text()).end()
    .find(":input").each(function() {
        var name = $(this).attr("name");

        if (name) {
          $(this).val($currentRow.find(":input[name$='" + name.match(/.*(\[.*\])/)[1] + "']").val());
        }
    }).end()
    .find("img[id^='slider_slides_cover_preview']").attr("src", $currentRow.find("img[id^='slider_slides_cover_preview']").attr("src"));
});

$container.on("click", ".tbSlideLayers", function() {

  var $row = $(this).closest(".tbRow ");
  var slide_num = $row.find("input.tbSlideRowNum").val();
  var content_id = "fireslider_slide_" + slide_num;

  if ($("#" + content_id).length) {
    var $anchor = $container.find('a[href="#' + content_id + '"]').parent();

    $containerPanel.tabs({ active: $anchor.siblings().addBack().index($anchor) });

    return false;
  }

  layer_rows_num[content_id] = 0;

  $('<li><span class="tbCloseTab">close</span><a href="#' + content_id + '">#' + $row.find(".tbRowTitle").text() + '</a></li>')
    .appendTo($container.find(".tbSliderTabsNavigation ul"))
    .find(".tbCloseTab").bind("click", function() {
      if ($(this).closest("li").is(".ui-state-active")) {
        $containerPanel.tabs({ active: 1 });
      }
      $(this).closest("li").remove();
      $("#" + content_id).remove();

      return false;
    });

  var $layersContent = $(Mustache.render($("#fireslider_slide_layers_template").text(), {
    content_id:  content_id,
    slide_title: $row.find(".tbRowTitle").text()
  })).appendTo($container.find("form"));

  $layersContent.find('.s_sortable_holder').sortable({
    handle: ".s_drag_area",
    tolerance: "pointer"
  });

  $layersContent.on("change", 'select[name="type"]', function() {
    $(this).closest(".tbLayerRow").find(".tbLayerImage").toggle($(this).val() == "image");
    $(this).closest(".tbLayerRow").find(".tbLayerHtml").toggle($(this).val() == "html");

    if ($(this).val() == "html") {
      $(this).closest(".tbLayerRow").find(".tbLayerImage")
        .find('input[name="image_src"]').val("").end()
        .find('img').attr("src", $sReg.get('/tb/no_image'));
    }
  });

  $layersContent.on("change", 'input[name="image_src"]', function() {
      var img = new Image();
      var $img_element = $(this);

      img.onload = function() {
        $img_element.closest(".tbLayerRow").find('input[name="html_width"]')
          .val(this.width)
          .closest(".tbSettingWrap")
          .removeClass("tb_autovalue tb_disabled")
          .addClass("tb_no_autovalue");
      };

      img.src = $sReg.get("/tb/url/image_url") + $(this).val();
  });

  var initLayerRowTab = function($tab, $panel) {

    if ($tab.data("initialized")) {
      return;
    }

    $tab.data("initialized", true);

    $panel.find("input.tbAutoValue").each(function() {
      if (!$(this).val().toString().length) {
        $(this).closest(".tbSettingWrap").addClass("tb_autovalue tb_disabled");
        $(this).val("auto");
      } else {
        $(this).closest(".tbSettingWrap").addClass("tb_no_autovalue");
      }
    });

    beautifyForm($panel);
  };

  $layersContent.on("click", ".tbToggleRow", function() {
    var $row = $(this).closest(".tbRow");

    if (!$row.hasClass("tbLayerInit")) {
      initAccordionRow($row);
      $row.find(".tb_tabs").tabs({
        activate: function(event, ui) {
          initLayerRowTab(ui.newTab.find("a"), ui.newPanel);
        },
        create: function(event, ui) {
          initLayerRowTab(ui.tab.find("a"), ui.panel);
        }
      });
    }

    $row.addClass("tbLayerInit");
  });

  $layersContent.on("click", "label", function() {
    var $parent = $(this).parent(".tbSettingWrap");

    if (!$parent.has("input.tbAutoValue")) {
      return;
    }

    var toggleRow = function($row, autovalue) {

      if (typeof autovalue == "undefined") {
        autovalue = $parent.hasClass("tb_autovalue");
      }

      if (autovalue === true) {
        $row
          .removeClass("tb_autovalue tb_disabled")
          .addClass("tb_no_autovalue")
          .find("input.tbAutoValue").val(0);
      } else {
        $row
          .removeClass("tb_no_autovalue")
          .addClass("tb_autovalue tb_disabled")
          .find("input.tbAutoValue").val("auto");
      }
    };

    toggleRow($parent);

    var $input = $parent.find("input.tbAutoValue");

    if ($input.is('[name$="style.left"]')) {
      toggleRow($parent.siblings().find('input[name$="style.right"]').closest(".tbSettingWrap"), $parent.hasClass("tb_autovalue"));
    } else
    if ($input.is('[name$="style.right"]')) {
      toggleRow($parent.siblings().find('input[name$="style.left"]').closest(".tbSettingWrap"), $parent.hasClass("tb_autovalue"));
    } else
    if ($input.is('[name$="style.top"]')) {
      toggleRow($parent.siblings().find('input[name$="style.bottom"]').closest(".tbSettingWrap"), $parent.hasClass("tb_autovalue"));
    } else
    if ($input.is('[name$="style.bottom"]')) {
      toggleRow($parent.siblings().find('input[name$="style.top"]').closest(".tbSettingWrap"), $parent.hasClass("tb_autovalue"));
    }
  });

  $.each(JSON.parse($row.find(':input[name$="[layers]"]').text()), function(i, val) {
    var $layer_row = $(Mustache.render($("#fireslider_slide_layer_template").text(), {
      row_num:       slide_num,
      row_title_num: ++layer_rows_num[content_id],
      no_image:      val.image_preview,
      color:         val.style.color,
      bg_color:      val.style.bg_color
    })).appendTo($layersContent.find(".tbLayersContainer"));

    js2form($layer_row[0], val);
    updateLayerRowTitleParameters($layer_row);
  });

  $layersContent.find(".tbLayersContainer > .tbRow").first().find(".tbToggleRow").trigger("click");
  $layersContent.find('select[name="type"]').trigger("change");

  $layersContent.on("click", ".tbAddLayer", function() {
    var $layer_row = $(Mustache.render($("#fireslider_slide_layer_template").text(), {
      row_num:       slide_num,
      row_title_num: ++layer_rows_num[content_id],
      no_image:      $sReg.get("/tb/no_image"),
      bg_color:      "#ffffff"
    })).prependTo($layersContent.find(".tbLayersContainer"));

    initAccordionRow($layer_row);
    $layer_row.find('select[name="type"]').trigger("change");

    $layer_row.find("input.tbAutoValue").each(function() {
      if (!$(this).val().toString().length || $(this).val() == "auto") {
        $(this).closest(".tbSettingWrap").addClass("tb_autovalue tb_disabled");
        $(this).val("auto");
      } else {
        $(this).closest(".tbSettingWrap").addClass("tb_no_autovalue");
      }
    });

    $layer_row.find(".tbToggleRow").trigger("click");
    $layer_row.find(".tb_tabs").tabs();
  });

  $containerPanel.tabs("refresh");
  $containerPanel.tabs({ active: -1 });

  return false;
});

$container.find(".tbSlideRow").each(function() {
  initAccordionRow($(this));
});
$container.find(".tbSlideRow").first().find(".tbToggleRow").trigger("click");

})(jQuery);
</script>

<script type="text/template" id="fireslider_size_template">
  <div class="tb_wrap s_mb_20 tb_live_1_1 tbSliderSizeRow">
    <div class="s_row_1 tb_col tb_live_row_1 tb_live_1_1">
      <label class="inline">Max width</label>
      <input class="s_spinner" type="text" name="slider[sizes][{{row_num}}][max_width]" size="7" min="0" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_1 tb_col tb_live_row_1 tb_live_1_1">
      <label class="inline">Height</label>
      <input class="s_spinner" type="text" name="slider[sizes][{{row_num}}][height]" size="7" min="0" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_1 tb_col tb_1_4">
      <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbRemoveSliderSize">Remove</a>
    </div>
  </div>
</script>

<script type="text/template" id="fireslider_slide_template">
  <div class="s_sortable_row tb_closed tbSlideRow tbRow">
    <input type="hidden" class="tbSlideRowNum" value="{{row_num}}" />
    <textarea name="slider[slides][{{row_num}}][layers]" style="display: none;">[]</textarea>
    <div class="s_actions">
      <div class="s_buttons_group">
        <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_plus_10 tbSlideLayers">Layers</a>
        <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_copy_10 tbDuplicateSlide"></a>
        <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveRow"></a>
      </div>
    </div>
    <a class="tb_toggle_row tbToggleRow" href="javascript:;">Toggle</a>
    <h3 class="s_drag_area"><span class="tbRowTitle">Slide <span>{{row_title_num}}</span></span></h3>
    <div class="s_sortable_contents" style="display: none;">
      <?php // Slide Type ?>
      <div class="s_row_1">
        <label>Type</label>
        <div class="s_select">
          <select name="slider[slides][{{row_num}}][type]">
            <option value="image">Image</option>
            <option value="video">Video</option>
            <option value="iframe">Iframe</option>
          </select>
        </div>
      </div>
      <?php // CSS Class ?>
      <div class="s_row_1">
        <label>Class</label>
        <input type="text" name="slider[slides][{{row_num}}][class]" />
      </div>
      <?php // Image Cover ?>
      <div class="s_row_1">
        <label>Image Cover</label>
        <input type="hidden" name="slider[slides][{{row_num}}][cover]" id="slider_slides_cover_{{row_num}}" />
        <span class="tb_thumb">
          <img src="{{no_image}}" id="slider_slides_cover_preview_{{row_num}}" class="image" onclick="image_upload('slider_slides_cover_{{row_num}}', 'slider_slides_cover_preview_{{row_num}}');" />
          <a class="block align_center s_mt_5" href="javascript:;" onclick="$(this).prev().attr('src', '{{no_image}}');$(this).closest('.s_row_1').find('> input').val('');">Remove</a>
        </span>
      </div>
      <?php // Cover Viewport ?>
      <div class="s_row_1">
        <label>Cover Viewport</label>
        <div class="s_select">
          <select name="slider[slides][{{row_num}}][viewport]">
            <option value="" selected="selected">Inherit</option>
            <option value="fill">Fill</option>
            <option value="fit">Fit</option>
            <option value="stretch">Stretch</option>
            <option value="center">Center</option>
          </select>
        </div>
      </div>
      <?php // Tiled Cover ?>
      <div class="s_row_1">
        <label>Tiled cover</label>
        <input type="hidden" name="slider[slides][{{row_num}}][background_repeat]" value="0" />
        <label class="tb_toggle"><input type="checkbox" name="slider[slides][{{row_num}}][background_repeat]" value="1" /><span></span><span></span></label>
      </div>
      <?php // Video Url ?>
      <div class="s_row_1 tbSlideVideo">
        <label>Video url</label>
        <input type="text" name="slider[slides][{{row_num}}][video]" />
      </div>
      <?php // Iframe Url ?>
      <div class="s_row_1 tbSlideIframe">
        <label>Iframe url</label>
        <input type="text" name="slider[slides][{{row_num}}][source]" />
      </div>
      <?php // Slide Link ?>
      <div class="s_row_1">
        <label>Slide Link</label>
        <input type="text" name="slider[slides][{{row_num}}][link_url]" />
        <span class="s_metric">
          <select class="s_metric" name="slider[slides][{{row_num}}][link_target]">
            <option value="_self" selected="selected">_self</option>
            <option value="_blank">_blank</option>
          </select>
        </span>
      </div>
      <?php // Background Color ?>
      <div class="s_row_1">
        <label>Background color</label>
        <div class="colorSelector tbBgColor colorpicker_no_color"><div></div></div>
        <input type="text" name="slider[slides][{{row_num}}][bg_color]" />
      </div>

      <fieldset>
        <legend>Ken Burns effect</legend>
        <div class="s_actions">
          <input type="hidden" name="slider[slides][{{row_num}}][kenburns]" value="0" />
          <label class="tb_toggle tb_toggle_small"><input type="checkbox" name="slider[slides][{{row_num}}][kenburns]" value="1" /><span></span><span></span></label>
        </div>
        <div class="tb_wrap tb_gut_30">
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
            <label>Animation</label>
            <div class="s_full clearfix">
              <select name="slider[slides][{{row_num}}][kenburns_animation]">
                <option value="zoomIn">Zoom in</option>
                <option value="zoomOut">Zoom out</option>
                <?php /*
                <option value="move">Move</option>
                */ ?>
              </select>
            </div>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
            <label>Zoom level</label>
            <input class="s_spinner" type="text" name="slider[slides][{{row_num}}][kenburns_zoom]" value="120" size="7" min="100" />
            <span class="s_metric">%</span>
          </div>
          <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
            <label>Speed</label>
            <input class="s_spinner" type="text" name="slider[slides][{{row_num}}][kenburns_timing]" value="20" size="7" min="2" />
            <span class="s_metric">s</span>
          </div>
          <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
            <label>Zoom origin</label>
            <div class="s_select">
              <select name="slider[slides][{{row_num}}][kenburns_origin]">
                <option value="center" selected="selected">Center</option>
                <option value="top">Top</option>
                <option value="top right">Top Right</option>
                <option value="right">Right</option>
                <option value="bottom right">Botttom Right</option>
                <option value="bottom">Bottom</option>
                <option value="bottom left">Bottom Left</option>
                <option value="left">Left</option>
                <option value="top left">Top Left</option>
              </select>
            </div>
          </div>
        </div>
      </fieldset>

    </div>
  </div>
</script>

<script type="text/template" id="fireslider_slide_layers_template">
  <div id="{{content_id}}" class="tb_subpanel">
    <h2>{{slide_title}} Layers</h2>
    <div class="s_sortable_holder tb_style_1 tbLayersContainer"></div>
    <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddLayer">Add Layer</a>
  </div>
</script>

<script type="text/template" id="fireslider_slide_layer_template">
  <div class="s_sortable_row tb_closed tbLayerRow tbRow">
    <div class="s_actions">
      <div class="s_buttons_group">
        <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveRow"></a>
      </div>
    </div>
    <a class="tb_toggle_row tbToggleRow" href="javascript:;">Toggle</a>
    <h3 class="s_drag_area">
      <span class="tbRowTitle">Layer <span>{{row_title_num}}</span></span>
      <span class="tbLayerParameters"></span>
    </h3>
    <div class="s_sortable_contents tb_subpanel clearfix" style="display: none;">
      <div class="tb_tabs">
        <div class="tb_tabs_nav s_box_3">
          <ul class="tb_nav clearfix">
            <li><a href="#slide_{{row_num}}_layer_{{row_title_num}}_content">Content</a></li>
            <li><a href="#slide_{{row_num}}_layer_{{row_title_num}}_transition">Transition</a></li>
            <li><a href="#slide_{{row_num}}_layer_{{row_title_num}}_styles">Styles</a></li>
            <li><a href="#slide_{{row_num}}_layer_{{row_title_num}}_attributes">Attributes</a></li>
          </ul>
        </div>
        <div id="slide_{{row_num}}_layer_{{row_title_num}}_content">
          <div class="s_row_1">
            <label>Type</label>
            <div class="s_select">
              <select name="type">
                <option value="image" selected="selected">Image</option>
                <option value="html">Html</option>
              </select>
            </div>
          </div>
          <div class="s_row_1 tbLayerHtml">
            <label>Content</label>
            <div class="s_full clearfix">
              <textarea name="content" rows="3" class="ckeditor"></textarea>
            </div>
          </div>
          <?php // Image ?>
          <div class="s_row_1 tbLayerImage">
            <label>Image</label>
            <div class="s_full tb_wrap">
              <div class="tb_col">
                <input type="hidden" name="image_src" value="" id="slider_slide_{{row_num}}_layer_{{row_title_num}}_preview_hidden" />
                <span class="tb_thumb">
                  <img src="{{no_image}}" id="slider_slide_{{row_num}}_layer_{{row_title_num}}_preview" class="image" onclick="image_upload('slider_slide_{{row_num}}_layer_{{row_title_num}}_preview_hidden', 'slider_slide_{{row_num}}_layer_{{row_title_num}}_preview');" />
                  <a class="block align_center s_mt_5" href="javascript:;" onclick="$(this).prev().attr('src', $sReg.get('/tb/no_image'));$(this).closest('.s_row_1').find('[name=image_src]').val('');">Remove</a>
                </span>
              </div>
              <div class="tb_col s_hidden">
                <div class="s_row_1">
                  <label class="tb_two_lines">Width <small>0 for auto</small></label>
                  <input type="text" name="image_width" size="7" value="0" />
                  <span class="s_metric">px</span>
                </div>
                <div class="s_row_1">
                  <label class="tb_two_lines">Height <small>0 for auto</small></label>
                  <input type="text" name="image_height" size="7" value="0" />
                  <span class="s_metric">px</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="slide_{{row_num}}_layer_{{row_title_num}}_transition">
          <fieldset>
            <legend>Animation</legend>
            <div class="tb_wrap">
              <?php // Animation ?>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Animation</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="keyframes[0].animation">
                      <option value="noslide">Fade in</option>
                      <option value="top">Slide from Top</option>
                      <option value="right">Slide from Right</option>
                      <option value="bottom">Slide from Bottom</option>
                      <option value="left">Slide from Left</option>
                    </select>
                  </div>
                </div>
              </div>
              <?php // Start Position ?>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Start position</label>
                <input class="s_spinner" type="text" name="style.position_modify" value="50" size="7" min="0" step="10" />
                <span class="s_metric">px</span>
              </div>
              <?php // Start Rotation ?>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Start rotation</label>
                <input class="s_spinner" type="text" name="style.rotate" value="0" size="7" />
                <span class="s_metric">deg</span>
              </div>
              <?php // Start scale ?>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Start scale</label>
                <input class="s_spinner" type="text" name="style.scale" size="7" value="1" min="0" step="0.1" />
              </div>
              <?php // Animation easing ?>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Easing</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="keyframes[0].easing">
                      <option value="linear">linear</option>
                      <option value="swing">swing</option>
                      <option value="easeInQuad">easeInQuad</option>
                      <option value="easeOutQuad" selected="selected">easeOutQuad</option>
                      <option value="easeInOutQuad">easeInOutQuad</option>
                      <option value="easeInCubic">easeInCubic</option>
                      <option value="easeOutCubic">easeOutCubic</option>
                      <option value="easeInOutCubic">easeInOutCubic</option>
                      <option value="easeInQuart">easeInQuart</option>
                      <option value="easeOutQuart">easeOutQuart</option>
                      <option value="easeInOutQuart">easeInOutQuart</option>
                      <option value="easeInQuint">easeInQuint</option>
                      <option value="easeOutQuint">easeOutQuint</option>
                      <option value="easeInOutQuint">easeInOutQuint</option>
                      <option value="easeInSine">easeInSine</option>
                      <option value="easeOutSine">easeOutSine</option>
                      <option value="easeInOutSine">easeInOutSine</option>
                      <option value="easeInExpo">easeInExpo</option>
                      <option value="easeOutExpo">easeOutExpo</option>
                      <option value="easeInOutExpo">easeInOutExpo</option>
                      <option value="easeInCirc">easeInCirc</option>
                      <option value="easeOutCirc">easeOutCirc</option>
                      <option value="easeInOutCirc">easeInOutCirc</option>
                      <option value="easeInElastic">easeInElastic</option>
                      <option value="easeOutElastic">easeOutElastic</option>
                      <option value="easeInOutElastic">easeInOutElastic</option>
                      <option value="easeInBack">easeInBack</option>
                      <option value="easeOutBack">easeOutBack</option>
                      <option value="easeInOutBack">easeInOutBack</option>
                      <option value="easeInBounce">easeInBounce</option>
                      <option value="easeOutBounce">easeOutBounce</option>
                      <option value="easeInOutBounce">easeInOutBounce</option>
                    </select>
                  </div>
                </div>
              </div>
              <?php // Opacity ?>
              <input type="hidden" name="style.opacity" value="0" />
            </div>
          </fieldset>
          <fieldset>
            <legend>Timing</legend>
            <div class="tb_wrap">
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Delay</label>
                <input class="s_spinner" type="text" name="keyframes[0].delay" value="0" size="7" min="0" step="50" />
                <span class="s_metric">ms</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Speed</label>
                <input class="s_spinner" type="text" name="keyframes[0].speed" value="500" size="7" min="0" step="50" />
                <span class="s_metric">ms</span>
              </div>
            </div>
          </fieldset>
        </div>
        <div id="slide_{{row_num}}_layer_{{row_title_num}}_styles">
          <?php // Position ?>
          <fieldset>
            <legend>Position</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingWrap">
                <label>Width</label>
                <input class="s_spinner tbAutoValue" type="text" name="html_width" size="7" />
                <span class="s_metric">
                  <select name="html_width_metric">
                    <option value="px" selected="selected">px</option>
                    <option value="%">%</option>
                  </select>
                </span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingWrap">
                <label>Top</label>
                <input class="s_spinner tbAutoValue" type="text" name="keyframes[0].style.top" value="0" size="7" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingWrap">
                <label>Left</label>
                <input class="s_spinner tbAutoValue" type="text" name="keyframes[0].style.left" value="0" size="7" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingWrap">
                <label>Bottom</label>
                <input class="s_spinner tbAutoValue" type="text" name="keyframes[0].style.bottom" value="auto" size="7" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingWrap">
                <label>Right</label>
                <input class="s_spinner tbAutoValue" type="text" name="keyframes[0].style.right" value="auto" size="7" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Position origin</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="background_origin">
                      <option value="content" selected="selected">Content</option>
                      <option value="row">Row</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <?php // Padding ?>
          <fieldset>
            <legend>Padding</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Top</label>
                <input class="s_spinner" type="text" name="style.padding_top" size="7" value="0" min="0" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Right</label>
                <input class="s_spinner" type="text" name="style.padding_right" size="7" value="0" min="0" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Bottom</label>
                <input class="s_spinner" type="text" name="style.padding_bottom" size="7" value="0" min="0" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Left</label>
                <input class="s_spinner" type="text" name="style.padding_left" size="7" value="0" min="0" />
                <span class="s_metric">px</span>
              </div>
            </div>
          </fieldset>
          <?php // Font ?>
          <fieldset>
            <legend>Typography</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingWrap">
                <label>Font size</label>
                <input class="s_spinner tbAutoValue" type="text" name="style.font_size" size="7" value="auto" min="0" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1 tbSettingWrap">
                <label>Line height</label>
                <input class="s_spinner tbAutoValue" type="text" name="style.line_height" size="7" value="auto" min="0" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Text align</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="style.text_align">
                      <option value="left" selected="selected">Left</option>
                      <option value="center">Center</option>
                      <option value="right">Right</option>
                      <option value="justify">Justify</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Text transform</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="style.text_transform">
                      <option value="none" selected="selected">None</option>
                      <option value="uppercase">Uppercase</option>
                      <option value="capitalize">Capitalize</option>
                      <option value="lowercase">Right</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
                <label>Color</label>
                <div class="colorSelector"><div style="background-color: {{color}};"></div></div>
                <input type="text" name="style.color" value="#333333" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Effects</legend>
            <input type="hidden" name="keyframes[0].style.opacity" value="1" />
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Rotation</label>
                <input class="s_spinner" type="text" name="keyframes[0].style.rotate" value="0" size="7" />
                <span class="s_metric">deg</span>
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Scale</label>
                <input class="s_spinner" type="text" name="keyframes[0].style.scale" value="1" size="7" min="0" step="0.1" />
              </div>
              <div class="s_row_2 tb_col tb_1_6 tb_live_row_1 tb_live_1_1">
                <label>Border radius</label>
                <input class="s_spinner" type="text" name="style.border_radius" value="0" size="7" min="0" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
                <label>Bg color</label>
                <div class="colorSelector tbBgColor"><div style="background-color: {{bg_color}};"></div></div>
                <input type="text" name="style.bg_color" />
              </div>
              <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
                <label>Transform origin</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="transform_origin">
                      <option value="center" selected="selected">Center</option>
                      <option value="top">Top</option>
                      <option value="top right">Top Right</option>
                      <option value="right">Right</option>
                      <option value="bottom right">Botttom Right</option>
                      <option value="bottom">Bottom</option>
                      <option value="bottom left">Bottom Left</option>
                      <option value="left">Left</option>
                      <option value="top left">Top Left</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
        <div id="slide_{{row_num}}_layer_{{row_title_num}}_attributes">
          <?php // Layer Class ?>
          <div class="s_row_1">
            <label>Class</label>
            <input type="text" name="class" />
          </div>
          <?php // Layer Link ?>
          <div class="s_row_1">
            <label>Link</label>
            <input type="text" name="link_url" />
            <span class="s_metric">
              <select name="link_target">
                <option value="_self" selected="selected">_self</option>
                <option value="_blank">_blank</option>
              </select>
            </span>
          </div>
        </div>
    </div>
  </div>
</script>
