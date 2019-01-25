<li id="menu_home_<?php echo $menu_item['data']['id']; ?>" class="tb_menu_home <?php echo $menu_classes; ?>">
  <?php if ($has_submenu): ?>
  <span class="tb_toggle tb_bg_str_2"></span>
  <?php endif; ?>
  <a<?php echo $label_classes; ?> href="<?php echo $home_url; ?>">
    <span class="tb_text">
      <?php if ($display == 'icon' || $display == 'label_icon'): ?>
      <i class="fa fa-home"></i>
      <?php endif; ?>
      <?php if ($display == 'label' || $display =='label_icon') echo ' ' . $menu_item['data']['settings']['label']; ?>
    </span>
  </a>
  <?php if ($has_submenu): ?>
  <ul<?php echo $submenu_classes; ?>>
  <?php echo $tbData->fetchMenuItems($menu_item['children']); ?>
  </ul>
  <?php endif; ?>
</li>