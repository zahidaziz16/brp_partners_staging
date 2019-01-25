<ul<?php echo $submenu_classes; ?>>
  <?php foreach ($category['children'] as $category): ?>
  <?php $submenu = $tbData->getCategorySubMenu($category, $settings, $max_depth); ?>
  <li<?php echo $category['menu_classes']; ?><?php if ($settings['max_level_thumb'] <= $category['level'] && $category['image']): ?> data-thumb="<?php echo $toolImage->resizeImage($category['image'], $settings['cat_image_size_x'], $settings['cat_image_size_y']); ?>"<?php endif; ?>>
    <?php if($submenu): ?>
    <span class="tb_toggle tb_bg_str_2"></span>
    <?php endif; ?>
    <a<?php echo $category['label_classes']; ?> href="<?php echo $category['url']; ?>">
      <span class="tb_text"><?php echo $category['name']; ?></span>
    </a>
    <?php if($submenu): ?>
    <?php echo $submenu; ?>
    <?php endif; ?>
  </li>
  <?php $current_cat_level = $category['level']; ?>
  <?php endforeach; ?>
  <?php if (!empty($settings['is_megamenu']) && $current_cat_level < 3): ?>
  <li></li><li></li><li></li><li></li><li></li>
  <?php endif; ?>
</ul>