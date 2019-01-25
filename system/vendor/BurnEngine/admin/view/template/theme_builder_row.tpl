<div class="s_builder_row tbBuilderRow" id="row_<?php echo $row['id']; ?>" idstr="<?php echo $row['id']; ?>">
  <div class="s_builder_row_header clearfix">
    <div class="s_builder_row_actions clearfix">
      <div class="s_column_number">
        <label>Columns</label>
        <div class="s_buttons_group tb_columns_num">
          <a class="tb_button_increase s_button s_white s_h_20">+</a>
          <input class="s_button s_white s_h_20 s_disabled" type="text" id="columns_num_<?php echo $row['id']; ?>" value="<?php echo $row['columns_number']; ?>" disabled="disabled" />
          <a class="tb_button_decrease s_button s_white s_h_20">-</a>
        </div>
      </div>
      <div class="s_buttons_group">
        <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbBuilderRowEdit" href="<?php echo $tbUrl->generateJs('LayoutBuilder/createRowSettingsForm'); ?>&row_id=<?php echo $row['id']; ?>&area_name=<?php echo $area_name; ?>&area_type=<?php echo $area_type; ?>&area_id=<?php echo $area_id; ?>"></a>
        <a class="s_button s_white s_h_20 s_icon_10 s_columns_10 tbBuilderRowCustomProportions" href="javascript:;"></a>
        <a class="s_button s_white s_h_20 s_icon_10 s_copy_10 tbBuilderRowDuplicate" href="javascript:;"></a>
        <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbBuilderRowRemove" href="javascript:;"></a>
      </div>
      <textarea class="tbBuilderRowSettings" style="display: none;"><?php echo $row_settings_encoded; ?></textarea>
      <textarea class="tbBuilderRowColumnSettings" style="display: none;"><?php echo $column_settings_encoded; ?></textarea>
      <span class="clear"></span>
    </div>
    <h3><span>Row <span class="row_order"><?php echo $key; ?></span></span></h3>
  </div>
  <div class="s_builder_cols_grid_helper clearfix">
    <?php if ($row['columns_number'] > 1): ?>
    <?php foreach ($row['columns'] as $column): ?>
    <div class="tb_grid_<?php echo $column['grid_proportion']; ?>"><span><?php echo str_replace('_', '/', $column['grid_proportion']); ?></span></div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="tb_grid_1_1"><span>1</span></div>
    <?php endif; ?>
  </div>
  <div class="s_builder_cols clearfix">
    <div class="s_builder_cols_wrap clearfix">
      <?php if (empty($row['columns'])): ?>
      <div class="s_builder_col tb_grid_1_1 tb_empty tbBuilderColumn" grid_proportion="1_1" data-column_id="<?php echo $column_id; ?>"></div>
      <?php else: $i = 0; ?>
      <?php foreach ($row['columns'] as $column): ?>
      <div id="builder_col_<?php echo $row['id'] . '_' . $i; ?>" class="s_builder_col tb_grid_<?php echo $column['grid_proportion']; ?> tbBuilderColumn" grid_proportion="<?php echo $column['grid_proportion']; ?>" data-column_id="<?php echo $column['id']; ?>"><?php if (isset($column['widgets'])): ?><?php foreach ($column['widgets'] as $widget): ?><div id="<?php echo $widget->getId(); ?>" class="s_widget tbWidget <?php echo str_replace('Theme_', 'tb', $widget->getClassName()); ?><?php if ($widget->getClassName() == 'Theme_BlockGroupWidget'): ?> tbGroupWidget<?php endif; ?>"<?php if ($widget instanceof Theme_SystemWidget): ?> data-slot_name="<?php echo $widget->getSlotName(); ?>"<?php endif; ?>>
          <h3><?php echo $widget->getPresentationTitle(); ?></h3>
          <div class="s_widget_actions">
            <div class="s_buttons_group">
              <?php if ($widget->getClassName() == 'Theme_GroupWidget' || $widget->getClassName() == 'Theme_BlockGroupWidget'): ?>
              <a class="s_button s_white s_h_20 s_icon_10 s_plus_10 tbGroupWidgetOpen" href="javascript:;">&nbsp;</a>
              <?php endif; ?>
              <?php if ($widget->hasEditableSettings()): ?>
              <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditWidget" href="<?php echo $tbUrl->generateJs('Widget/createForm'); ?>&class_name=<?php echo $widget->getClassName(); ?>&area_name=<?php echo $area_name; ?>&area_type=<?php echo $area_type; ?>&area_id=<?php echo $area_id; ?>">&nbsp;</a>
              <?php endif; ?>
              <a class="s_button s_white s_h_20 s_icon_10 s_copy_10 tbWidgetDuplicate" href="javascript:;">&nbsp;</a>
              <a class="s_button_remove_widget s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;">&nbsp;</a>
            </div>
          </div>
          <input type="hidden" class="tbWidgetDirty" value="0" />
          <textarea class="widget_settings" style="display: none"><?php echo $widget->getSettingsEncoded(); ?></textarea>
          <?php if ($widget->getClassName() == 'Theme_GroupWidget' || $widget->getClassName() == 'Theme_BlockGroupWidget'): ?>
          <textarea class="tbGroupWidgetSubwidgetMap" style="display: none"><?php echo json_encode((object) $widget->getSubWidgetMap()); ?></textarea>
          <textarea class="tbGroupWidgetSectionKeys" style="display: none"><?php echo json_encode($widget->getSectionKeys()); ?></textarea>
          <textarea class="tbGroupWidgetSectionTitlesMap" style="display: none"><?php echo json_encode((object) $widget->getSectionTitlesMap()); ?></textarea>
          <div class="s_widget_subwidgets">
            <?php foreach ($widget->getSubWidgets() as $subwidget): ?>
            <div id="<?php echo $subwidget->getId(); ?>" class="s_widget tbWidget"<?php if ($subwidget instanceof Theme_SystemWidget): ?> data-slot_name="<?php echo $subwidget->getSlotName(); ?>"<?php endif; ?>>
             <h3><?php echo $subwidget->getPresentationTitle(); ?></h3>
              <div class="s_widget_actions">
                <div class="s_buttons_group">
                  <?php if ($subwidget->hasEditableSettings()): ?>
                  <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditWidget" href="<?php echo $tbUrl->generateJs('Widget/createForm'); ?>&class_name=<?php echo $subwidget->getClassName(); ?>&area_name=<?php echo $area_name; ?>&area_type=<?php echo $area_type; ?>&area_id=<?php echo $area_id; ?>">&nbsp;</a>
                  <?php endif; ?>
                  <a class="s_button s_white s_h_20 s_icon_10 s_copy_10 tbWidgetDuplicate" href="javascript:;">&nbsp;</a>
                  <a class="s_button_remove_widget s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;">&nbsp;</a>
                </div>
              </div>
              <input type="hidden" class="tbWidgetDirty" value="0" />
              <textarea class="widget_settings" style="display: none"><?php echo $subwidget->getSettingsEncoded(); ?></textarea>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div><?php endforeach; ?><?php endif; ?></div>
      <?php $i++; endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
