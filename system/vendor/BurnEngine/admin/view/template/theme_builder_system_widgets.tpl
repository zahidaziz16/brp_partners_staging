<?php foreach ($widgets as $widget): ?>
<div class="s_widget tbWidget<?php if ($widget->isLocked()): ?> tbWidgetLocked<?php endif; ?>" id_prefix="<?php echo $widget->getClassName(); ?>" data-slot_name="<?php echo $widget->getSlotName(); ?>">
  <h3><?php echo $widget->getName(); ?></h3>
  <div class="s_widget_actions">
    <div class="s_buttons_group">
      <?php if ($widget->hasEditableSettings()): ?>
      <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditWidget" href="<?php echo $tbUrl->generateJs('Widget/createForm'); ?>&class_name=<?php echo $widget->getClassName(); ?>">&nbsp;</a>
      <?php endif; ?>
      <a class="s_button_remove_widget s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;">&nbsp;</a>
    </div>
  </div>
  <input type="hidden" class="tbWidgetDirty" value="0" />
  <textarea class="widget_settings" style="display: none"><?php echo $widget->getSettingsEncoded(); ?></textarea>
</div>
<?php endforeach; ?>