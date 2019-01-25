<div id="page_content_product_system_widget_content" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Page content</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">
        <h2>Edit Articles Listing</h2>
        <div class="s_row_1 tbOptionRowViewMode">
          <label>Display</label>
          <div class="s_select">
            <select name="widget_data[view_mode]">
              <option value="list"<?php if($settings['view_mode'] == 'list') echo ' selected="selected"';?>>List</option>
              <option value="grid"<?php if($settings['view_mode'] == 'grid') echo ' selected="selected"';?>>Grid</option>
            </select>
          </div>
        </div>
        <div class="s_row_1 tbOptionRowRestrictions">
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
      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <input type="hidden" name="widget_data[widget_name]" value="<?php echo $settings['widget_name']; ?>" />
        <input type="hidden" name="widget_data[slot_name]" value="<?php echo $settings['slot_name']; ?>" />
        <input type="hidden" name="widget_data[slot_prefix]" value="<?php echo $settings['slot_prefix']; ?>" />
        <?php require tb_modification(dirname(__FILE__) . '/_common_options.tpl'); ?>
      </div>

    </div>

    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tbWidgetUpdate" href="javascript:;">Update Settings</a>
    </div>

  </form>

</div>

<script>
$(document).ready(function() {
  var $container = $("#page_content_product_system_widget_content");

  $('.tbOptionRowViewMode select').bind('change', function() {
    $('.tbOptionRowRestrictions').toggle($(this).val() == 'grid');
  }).trigger('change');

  tbApp.initRestrictionRows($container, "widget_data");
});
</script>
