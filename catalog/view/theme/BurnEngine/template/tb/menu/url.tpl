<li id="<?php echo $menu_id; ?>"<?php echo $menu_classes; ?>>
  <?php if ($has_submenu): ?>
  <span class="tb_toggle tb_bg_str_2"></span>
  <?php endif; ?>
  <a<?php echo $label_classes; ?> href="<?php echo $url; ?>"<?php echo $target; ?>>
    <span class="tb_text"><?php echo $menu_icon; ?><?php echo $label; ?></span>
    <?php echo $accent_label; ?>
  </a>
  <?php if ($has_submenu): ?>
  <?php if (!empty($settings['tabbed_submenus']) && empty($widget_settings['ignore_tabbed_submenu'])): ?>
  <div class="dropdown-menu">
    <div class="tb_tabs<?php if ($tbData->language_direction == 'ltr') { echo ' tabs-left'; } else { echo ' tabs-right'; } ?>">
      <ul class="nav nav-tabs vtabs tb_style_1 tb_mb_0">
        <?php echo $tbData->fetchMenuItems($menu_item['children'], 'labels'); ?>
      </ul>
      <ul class="tab-content">
        <?php echo $tbData->fetchMenuItems($menu_item['children'], 'menus'); ?>
      </ul>
    </div>
  </div>
  <?php else: ?>
  <ul<?php echo $submenu_classes; ?>>
    <?php echo $tbData->fetchMenuItems($menu_item['children']); ?>
  </ul>
  <?php endif; ?>
  <?php endif; ?>
</li>

<?php if ($has_submenu && !empty($settings['tabbed_submenus']) && empty($widget_settings['ignore_tabbed_submenu'])): ?>
<script type="text/javascript">
tbApp.onScriptLoaded(function() {
  tabbed_menu('<?php echo $menu_id; ?>');
});
</script>
<?php endif; ?>
