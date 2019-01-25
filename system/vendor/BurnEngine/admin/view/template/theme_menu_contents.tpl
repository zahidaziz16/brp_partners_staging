<h2>Menu composer</h2>

<div class="s_actions">
  <div class="tbComboBoxRow">
    <select class="tb_nostyle tbComboBox">
      <option class="ui-combobox-nocomplete ui-combobox-noselect" key="add_new" value="add_new">New</option>
      <option class="ui-combobox-nocomplete ui-combobox-noselect ui-combobox-disabled tb_separator">separator</option>
      <?php foreach ($menu_options as $option_value => $option_text): ?>
      <option<?php if ($option_value != 'main'): ?> class="ui-combobox-remove"<?php endif; ?><?php if ($option_value == $menu['id']): ?> selected="selected"<?php endif; ?> key="<?php echo $option_value; ?>" value="<?php echo $option_value; ?>"><?php echo $option_text; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>

<div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_tabs_inline tbLanguageTabs"'; ?>>

  <?php if (count($languages) > 1): ?>
  <div class="tb_tabs_nav s_h_40 s_auto_cols">
    <ul class="clearfix">
      <?php foreach ($languages as $language): ?>
      <li class="s_language">
        <a href="#menu_builder_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
          <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
          <?php echo $language['code']; ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <input type="hidden" name="menu[id]" value="<?php echo $menu['id']; ?>" />
  <input type="hidden" name="menu[name]" value="<?php echo $menu['name']; ?>" />

  <?php foreach ($languages as $language): ?>
  <?php $language_code = $language['code']; ?>
  <div id="menu_builder_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?> tbLanguagePanel">
    <textarea name="menu[tree][<?php echo $language_code; ?>]" class="tbMenuData" style="display: none;"><?php echo json_encode($menu['tree'][$language_code]); ?></textarea>
    <input type="hidden" name="menu[is_dirty]" value="0" />

    <?php require(tb_modification(dirname(__FILE__) . '/theme_menu_contents_item.tpl')); ?>

  </div>
  <?php endforeach; ?>

</div>