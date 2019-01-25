<fieldset>
  <script type="text/javascript">
  $(document).ready(function() {
    $("#widget_settings_holder").find(':checkbox[name="widget_data[slider]"]').bind("change", function() {
      var $wrapper = $(this).closest(".tbProductOptionsSliderWrap");

      $wrapper
              .find(".tbSettingsRow").add($wrapper.next(".tbSettingsRow")).not($(this).closest(".tbSettingsRow"))
              .toggleClass("tb_disabled", !$(this).is(":checked"));
    }).trigger("change");
  });
  </script>
  <legend>Slider (carousel)</legend>
  <div class="tb_wrap tb_gut_30 tbProductOptionsSliderWrap">
    <div class="s_row_2 tb_col tb_1_5 tbSettingsRow">
      <label>Enabled</label>
      <input type="hidden" name="widget_data[slider]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="widget_data[slider]" value="1"<?php if ($settings['slider'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tbSettingsRow tbSliderSettingsStep">
      <label>Slide step</label>
      <input class="s_spinner" type="text" name="widget_data[slider_step]" value="<?php echo $settings['slider_step']; ?>" size="5" min="1" max="8" />
    </div>
    <div class="s_row_2 tb_col tb_1_5 tbSettingsRow">
      <label>Slide speed</label>
      <input class="s_spinner" type="text" name="widget_data[slider_speed]" value="<?php echo $settings['slider_speed']; ?>" size="7" min="100" step="100" />
      <span class="s_metric">ms</span>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tbSettingsRow">
      <label>Show pagination</label>
      <span class="clear"></span>
      <input type="hidden" name="widget_data[slider_pagination]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="widget_data[slider_pagination]" value="1"<?php if ($settings['slider_pagination'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_5">
      <label>Navigation position</label>
      <span class="clear"></span>
      <div class="s_full clearfix">
        <div class="s_select">
          <select name="widget_data[slider_nav_position]">
            <option value="top"<?php if ($settings['slider_nav_position'] == 'top'): ?> selected="selected"<?php endif; ?>>Top</option>
            <option value="side"<?php if ($settings['slider_nav_position'] == 'side'): ?> selected="selected"<?php endif; ?>>Side</option>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="tb_wrap tb_gut_30 tbProductOptionsSliderWrap">
    <div class="s_row_2 tb_col tb_1_5 tbSettingsRow">
      <label>Loop mode</label>
      <input type="hidden" name="widget_data[slider_loop]" value="0" />
      <label class="tb_toggle"><input type="checkbox" name="widget_data[slider_loop]" value="1"<?php if ($settings['slider_loop'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tbSettingsRow">
      <label>Autoplay</label>
      <input class="s_spinner" type="text" name="widget_data[slider_autoplay]" value="<?php echo $settings['slider_autoplay']; ?>" size="7" min="100" step="100" />
      <span class="s_metric">ms</span>
    </div>
  </div>
</fieldset>