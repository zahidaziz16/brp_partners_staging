<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li><a href="#extension_live_search_settings">Settings</a></li>
      <li><a href="#extension_live_search_style">Style</a></li>
    </ul>
  </div>

  <div id="extension_live_search_settings" class="tb_subpanel">
    <h2>Search Settings</h2>
    <div class="tb_wrap tb_gut_30">
      <div class="tb_col tb_1_2">
        <fieldset>
          <legend>Search in</legend>
          <?php foreach ($settings['search_in'] as $option_name => $option_value): ?>
          <div class="s_row_1 s_mt_0 left tb_1_2">
            <label class="s_checkbox">
              <input type="hidden" name="live_search[search_in][<?php echo $option_name; ?>]" value="0" />
              <input id="live_search_input_search_in" type="checkbox" name="live_search[search_in][<?php echo $option_name; ?>]" value="1"<?php if($option_value) echo ' checked="checked"';?> />
              <span><?php echo $option_name; ?></span>
            </label>
          </div>
          <?php endforeach; ?>
        </fieldset>
      </div>
      <div class="tb_col tb_1_2">
        <fieldset>
          <legend>Search results</legend>
          <div class="s_row_1">
            <label for="live_search_min_length">Query min length</label>
            <input id="live_search_min_length" type="text" name="live_search[min_length]" value="<?php echo $settings['min_length']; ?>" min="1" size="6" />
            <span class="s_metric">symbols</span>
          </div>
          <div class="s_row_1">
            <label for="live_search_max_results">Max results</label>
            <input id="live_search_max_results" type="text" name="live_search[max_results]" value="<?php echo $settings['max_results']; ?>" min="1" size="6" />
            <span class="s_metric">rows</span>
          </div>
          <div class="s_row_1">
            <label for="live_search_show_image">Show image</label>
            <input type="hidden" name="live_search[show_image]" value="0" />
            <label class="tb_toggle"><input id="live_search_show_image" type="checkbox" name="live_search[show_image]" value="1"<?php if($settings['show_image'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1">
            <label for="live_search_show_model">Show model</label>
            <input type="hidden" name="live_search[show_model]" value="0" />
            <label class="tb_toggle"><input id="live_search_show_model" type="checkbox" name="live_search[show_model]" value="1"<?php if($settings['show_model'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1">
            <label for="live_search_show_price">Show price</label>
            <input type="hidden" name="live_search[show_price]" value="0" />
            <label class="tb_toggle"><input id="live_search_show_price" type="checkbox" name="live_search[show_price]" value="1"<?php if($settings['show_price'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
        </fieldset>
      </div>
    </div>
  </div>

  <div id="extension_live_search_style" class="tb_subpanel">
    <h2>Search Styling</h2>
    <div class="s_row_1">
      <label for="live_search_highlight_results">Highlight results</label>
      <input type="hidden" name="live_search[highlight_results]" value="0" />
      <label class="tb_toggle"><input id="live_search_highlight_results" type="checkbox" name="live_search[highlight_results]" value="1"<?php if($settings['highlight_results'] == '1') echo ' checked="checked"';?> size="6" /><span></span><span></span></label>
    </div>
    <div class="s_row_1">
      <label for="live_search_image_width">Image width</label>
      <input id="live_search_image_width" type="text" name="live_search[image_width]" value="<?php echo $settings['image_width']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_1">
      <label for="live_search_image_height">Image height</label>
      <input id="live_search_image_height" type="text" name="live_search[image_height]" value="<?php echo $settings['image_height']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_1">
      <label for="live_search_dropdown_width">Results width</label>
      <input id="live_search_dropdown_width" type="text" name="live_search[dropdown_width]" value="<?php echo $settings['dropdown_width']; ?>" size="6" />
      <span class="s_metric">px</span>
    </div>
    <div class="s_row_1">
      <label for="live_search_title_style_select">Title style</label>
      <div class="s_select">
          <select name="live_search[title_style]" id="live_search_title_style_select">
          <option value="h2"<?php if($settings['title_style'] == 'h2'): ?> selected="selected"<?php endif; ?>>H2</option>
          <option value="h3"<?php if($settings['title_style'] == 'h3'): ?> selected="selected"<?php endif; ?>>H3</option>
          <option value="h4"<?php if($settings['title_style'] == 'h4'): ?> selected="selected"<?php endif; ?>>H4</option>
          <option value="p" <?php if($settings['title_style'] ==  'p'): ?> selected="selected"<?php endif; ?>>P</option>
        </select>
      </div>
    </div>
  </div>
</div>

<div class="s_submit clearfix">
  <div class="left">
    <a class="s_button s_white s_h_40 tbButtonBackToExtensions">Back to extensions</a>
  </div>
  <div class="right">
    <a class="s_button s_red s_h_40 tbSaveLiveSearchSettings">Save Settings</a>
  </div>
</div>

<script>
(function($, tbApp) {

  var $container = $("#tb_cp_panel_extensions > .tb_tabs").first();

  $container.tabs({
    activate: function(event, ui) {
      tbApp.cookie.set("tbExtensionLiveSearchTabs", ui.newTab.index());
    },
    active: tbApp.cookie.get("tbExtensionLiveSearchTabs", 0),
    beforeLoad: function(event, ui) {
      if (ui.tab.data("loaded")) {
        event.preventDefault();
      } else {
        ui.panel.block();
      }
    },
    load: function(event, ui) {
      ui.tab.data("loaded", true);
      ui.panel.unblock();
    }
  });

  $container.find("input[name='live_search[show_image]']").bind("change", function() {

  });

  $container.parent().find(".tbSaveLiveSearchSettings").bind("click", function() {
    $container.block();
    $.post($sReg.get("/tb/url/live_search/default/saveSettings"), $container.find(":input[name^='live_search']").serializeArray(), function() {
      $container.unblock();
    }, "json");

    return false;
  });

})(jQuery, tbApp);
</script>

