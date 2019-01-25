<h2><span class="tbAreaName"><?php echo ucfirst(str_replace('_', ' ', $area_name)); ?></span> area</h2>

<div class="tb_actions">
  <div class="s_row_1 tbComboBoxRow">
    <label class="inline"><strong class="tbPageType"></strong></label>

    <select class="tb_nostyle tbComboBox">
      <?php if ($area_name != 'content'): ?>
      <option key="global" value="global">GLOBAL</option>
      <?php endif; ?>
      <option key="home" value="home">Home</option>
      <option key="category" value="category_global">All categories</option>
      <?php foreach ($category_levels as $option): ?>
      <option key="category" value="<?php echo $option['value']; ?>"><?php echo $option['label']; ?></option>
      <?php endforeach; ?>
      <option key="product" value="product_global">All products</option>
      <?php if ($layouts): ?>
      <option key="choose_layout" class="ui-combobox-nocomplete ui-combobox-noselect" value="layout">Layout</option>
      <?php endif; ?>
      <option class="ui-combobox-nocomplete ui-combobox-noselect ui-combobox-disabled tb_separator">separator</option>
      <?php if ($pages): ?>
      <option key="choose_page" class="ui-combobox-nocomplete ui-combobox-noselect" value="information_page">Information page</option>
      <?php endif; ?>
      <?php if ($store_has_categories): ?>
      <option key="choose_category" class="ui-combobox-nocomplete ui-combobox-noselect" value="category">Category</option>
      <?php endif; ?>
      <option key="choose_system" class="ui-combobox-nocomplete ui-combobox-noselect" value="system_page">System page</option>
      <?php if ($area_name == 'content'): ?>
      <option class="ui-combobox-nocomplete ui-combobox-noselect ui-combobox-disabled tb_separator">separator</option>
      <option key="quickview" value="quickview">Quick view</option>
      <?php endif; ?>
      <option class="ui-combobox-nocomplete ui-combobox-noselect ui-combobox-disabled tb_separator">separator</option>
      <option key="modified" class="ui-combobox-nocomplete ui-combobox-noselect tbModified" value="modified">Modified</option>
    </select>
    <input type="hidden" name="area_type" value="<?php echo $area_type; ?>" />
    <input type="hidden" name="area_id" value="<?php echo $area_id; ?>" />
    <input type="hidden" name="area_name" value="<?php echo $area_name; ?>" />
    <input type="hidden" name="inherit_key" value="<?php echo $inherit_key; ?>" />

    <?php if ($pages): ?>
    <ul class="tbPageMenu" style="display: none;">
      <?php foreach ($pages as $page): ?>
      <li><a href="javascript:;" page_id="<?php echo $page['information_id']; ?>"><?php echo $page['title']; ?></a></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <?php if ($layouts): ?>
    <ul class="tbLayoutMenu" style="display: none;">
      <?php foreach ($layouts as $layout): ?>
      <li><a href="javascript:;" layout_id="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></a></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <?php echo $tbData->fetchTemplate('theme_builder_modified', array('modified' => $modified)); ?>

  </div>

  <div class="s_buttons_group s_h_28">
    <a class="s_button s_white s_h_28 s_icon_10 s_cog_10 tbAreaSettings" href="javascript:;"></a>
    <a class="s_button s_h_28 s_white s_icon_10 s_delete_10 tbDeleteArea" href="<?php echo $tbUrl->generate('layoutBuilder/removeSettings', array('area_type' => $area_type, 'area_id' => $area_id, 'area_name' => $area_name, 'current_area_id' => $area_id, 'current_area_type' => $area_type, 'record_type' => 'builder')); ?>"<?php if (!$can_delete): ?> style="display:none;"<?php endif; ?>></a>
  </div>
</div>

<textarea class="tbAreaWidgetClasses" style="display: none;"><?php echo $area_widget_classes; ?></textarea>
<textarea class="tbAreaTemplates" style="display: none;"><?php echo $area_templates; ?></textarea>

<?php if (!empty($inherit_msg)): ?>
<div class="s_server_msg s_msg_blue tbRecordInfoMessage1">
  <p class="s_icon_16 s_info_16">
    There are no saved blocks for <strong class="tbPageDescription"><?php if ($area_type == 'global'): ?>GLOBAL<?php else: ?>Home<?php endif; ?></strong>. <?php if (!$area_empty): ?><?php echo $inherit_msg; ?> are applied instead.<?php endif; ?>
  </p>
</div>
<?php endif; ?>

<div class="s_server_msg s_msg_yellow tbRecordInfoMessage2"<?php if (empty($override_msg)): ?> style="display: none;"<?php endif; ?>>
  <p class="s_icon_16 s_exclamation_16">
    The blocks for <strong class="tbOverrideMsg"><?php echo $override_msg; ?></strong> will be displayed on the site as they have higher priority than those for <strong class="tbPageDescription"></strong>.
  </p>
</div>

<div class="s_builder_wrap clearfix builder_container tbRowsContainer" widget_area="<?php echo $area_name; ?>">
  <?php echo $rows_html; ?>
</div>

<?php if ($can_add_rows): ?>
<div class="s_builder_new_row clearfix">
  <a class="s_button s_h_30 s_white s_icon_10 s_plus_10 tbNewWidgetsRow" href="javascript:;">Add Row</a>
  <?php if ($can_delete || $tbEngine->getConfig('admin_show_copy_area') && $stores): ?>
  <div class="right">
    <?php if ($tbEngine->getConfig('admin_show_copy_area') && $stores): ?>
    <label>Copy to:</label>
    <select>
      <?php foreach ($stores as $store_item): ?>
      <option value="<?php echo $store_item['store_id']; ?>"><?php echo $store_item['name']; ?></option>
      <?php endforeach; ?>
    </select>
    <a class="s_button s_h_30 s_white s_icon_10 s_copy_10 tbCopyArea" href="<?php echo $tbUrl->generate('layoutBuilder/copyArea', array('area_type' => $area_type, 'area_id' => $area_id, 'area_name' => $area_name)); ?>">Replace</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>
