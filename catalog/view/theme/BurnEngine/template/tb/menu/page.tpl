<li<?php echo $menu_classes; ?>>
  <?php if ($has_submenu): ?>
  <span class="tb_toggle tb_bg_str_2"></span>
  <?php endif; ?>
  <a<?php echo $label_classes; ?> href="<?php echo $url; ?>"<?php echo $target; ?>>
    <span class="tb_text"><?php echo $menu_icon; ?><?php echo $label; ?></span>
    <?php echo $accent_label; ?>
  </a>
  <?php if ($has_submenu): ?>
  <ul<?php echo $submenu_classes; ?>>
    <?php echo $tbData->fetchMenuItems($menu_item['children']); ?>
  </ul>
  <?php endif; ?>
</li>