<div id="system_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span><?php echo $widget->getName(); ?></span></h1>

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

          <h2>Edit <?php echo $settings['widget_name']; ?></h2>

        </div>

        <fieldset>
          <legend>Text logo</legend>
          <div class="s_row_1">
            <input type="hidden" name="widget_data[text_logo]" value="0" />
            <label class="tb_toggle"><input type="checkbox" name="widget_data[text_logo]" value="1"<?php if($settings['text_logo'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
        </fieldset>


        <fieldset class="tbTextLogo">
          <legend>Layout Change</legend>
          <div class="s_row_1">
            <input class="s_spinner" type="text" name="widget_data[text_logo_new_row]" value="<?php echo $settings['text_logo_new_row']; ?>" size="7" min="0" />
            <span class="s_metric">px</span>
            <p class="s_help right">Put text logo single in a row, if resolution is lower than specified.</p>
          </div>
        </fieldset>

        <fieldset class="tbImageLogo">
          <legend>Large screens</legend>
          <p class="s_help">Devices with a resolution width greater than 1024px.</p>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Max width</label>
              <input class="s_spinner" type="text" name="widget_data[max_width_lg]" value="<?php echo $settings['max_width_lg']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Max height</label>
              <input class="s_spinner" type="text" name="widget_data[max_height_lg]" value="<?php echo $settings['max_height_lg']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>

        <fieldset class="tbImageLogo">
          <legend>Medium screens</legend>
          <p class="s_help">Devices with a resolution width in the 768px - 1024px range.</p>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Max width</label>
              <input class="s_spinner" type="text" name="widget_data[max_width_md]" value="<?php echo $settings['max_width_md']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Max height</label>
              <input class="s_spinner" type="text" name="widget_data[max_height_md]" value="<?php echo $settings['max_height_md']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>

        <fieldset class="tbImageLogo">
          <legend>Small screens</legend>
          <p class="s_help">Devices with a resolution width in the 480px - 768px range.</p>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Max width</label>
              <input class="s_spinner" type="text" name="widget_data[max_width_sm]" value="<?php echo $settings['max_width_sm']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Max height</label>
              <input class="s_spinner" type="text" name="widget_data[max_height_sm]" value="<?php echo $settings['max_height_sm']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>

        <fieldset class="tbImageLogo">
          <legend>Extra Small screens</legend>
          <p class="s_help">Devices with a resolution up to 480px.</p>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Max width</label>
              <input class="s_spinner" type="text" name="widget_data[max_width_xs]" value="<?php echo $settings['max_width_xs']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Max height</label>
              <input class="s_spinner" type="text" name="widget_data[max_height_xs]" value="<?php echo $settings['max_height_xs']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>

        <fieldset class="tbImageLogo">
          <legend>Sticky header</legend>
          <div class="tb_wrap tb_gut_30">
            <div class="s_row_2 tb_col tb_1_5">
              <label>Max width</label>
              <input class="s_spinner" type="text" name="widget_data[max_width_sticky]" value="<?php echo $settings['max_width_sticky']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_2 tb_col tb_1_5">
              <label>Max height</label>
              <input class="s_spinner" type="text" name="widget_data[max_height_sticky]" value="<?php echo $settings['max_height_sticky']; ?>" size="7" min="0" />
              <span class="s_metric">px</span>
            </div>
          </div>
        </fieldset>

      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <input type="hidden" name="widget_data[widget_name]" value="<?php echo $settings['widget_name']; ?>" />
        <input type="hidden" name="widget_data[slot_name]" value="<?php echo $settings['slot_name']; ?>" />
        <input type="hidden" name="widget_data[slot_prefix]" value="<?php echo $settings['slot_prefix']; ?>" />
        <?php $layout_display = true; ?>
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

    $("#widget_settings_holder").find('[name="widget_data[text_logo]"]').on('change', function() {
      $('.tbTextLogo').toggle($(this).is(":checked"));
      $('.tbImageLogo').toggle(!$(this).is(":checked"));
    }).trigger('change');

  });
</script>