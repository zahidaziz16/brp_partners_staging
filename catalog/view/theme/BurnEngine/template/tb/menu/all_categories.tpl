<?php // Categories --------------------------------------------------- ?>

<?php $tbData->slotStart('menu_all_categories.categories'); ?>
<ul<?php echo $wrapper_classes; ?>>
  <?php foreach ($categories as $category): ?>
  <?php
  $current_menu_classes  = $level_1_menu_classes;
  $current_menu_classes .= !$is_megamenu && !empty($category['submenu']) ? ' dropdown' : '';
  $current_menu_classes  = !empty($current_menu_classes)  ? ' class="' . trim($current_menu_classes)  . '"' : '';
  ?>
  <li<?php echo $current_menu_classes; ?>>
    <?php if (!empty($category['submenu'])): ?>
    <span class="tb_toggle tb_bg_str_2"></span>
    <?php endif; ?>
    <?php if ($is_megamenu && $settings['category_thumb'] && !empty($settings['category_thumb_position']) && $settings['category_thumb_position'] == 'top'): ?>
    <a class="thumbnail" href="<?php echo $category['url']; ?>">
      <img src="<?php echo $toolImage->resizeImage($category['image'] ? $category['image'] : 'no_image.jpg', $settings['cat_image_size_x'], $settings['cat_image_size_y']); ?>" width="<?php echo $settings['cat_image_size_x']; ?>" height="<?php echo $settings['cat_image_size_y']; ?>" alt="<?php echo $category['name']; ?>" />
    </a>
    <?php endif; ?>
    <a<?php echo $level_1_label_classes; ?> href="<?php echo $category['url']; ?>">
      <span class="tb_text"><?php echo $category['name']; ?></span>
    </a>
    <?php if ($is_megamenu && $settings['category_thumb'] && (empty($settings['category_thumb_position']) || $settings['category_thumb_position'] != 'top')): ?>
    <span class="thumbnail">
      <img src="<?php echo $toolImage->resizeImage($category['image'] ? $category['image'] : 'no_image.jpg', $settings['cat_image_size_x'], $settings['cat_image_size_y']); ?>" width="<?php echo $settings['cat_image_size_x']; ?>" height="<?php echo $settings['cat_image_size_y']; ?>" alt="<?php echo $category['name']; ?>" />
    </span>
    <?php endif; ?>
    <?php if (!empty($category['submenu'])): ?>
    <?php echo $category['submenu']; ?>
    <?php endif; ?>
  </li>
  <?php endforeach; ?>
  <?php if ($is_megamenu): ?>
  <li></li><li></li><li></li><li></li><li></li>
  <?php endif; ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Echo slots -------------------------------------------------- ?>

<li id="menu_all_categories_<?php echo $menu_item_id; ?>"<?php echo $menu_classes; ?>>
  <span class="tb_toggle tb_bg_str_2"></span>
  <a<?php echo $label_classes; ?> href="javascript:;">
    <span class="tb_text"><?php echo $menu_icon; ?><?php echo $settings['label']; ?></span>
    <?php echo $accent_label; ?>
  </a>
  <?php if ($is_megamenu): ?>
  <div class="dropdown-menu">
    <div class="row tb_separate_columns tb_ip_20">
      <?php if ($settings['menu_banner'] && ($settings['menu_banner_position'] == 0 || $settings['menu_banner_position'] == 1)): ?>
      <?php echo $menu_banner; ?>
      <?php endif; ?>
      <div class="col col-xs-12 col-sm-fill">
        <div class="tb_subcategories"><?php $tbData->slotEcho('menu_all_categories.categories'); ?></div>
        <?php if(!empty($manufacturers) && $settings['manufacturers_position'] == 'below'): ?>
        <span class="clear border tb_mt_20 tb_mb_20 tb_ml_-20 tb_mr_-20"></span>
        <?php echo $category_manufacturers; ?>
        <?php endif; ?>
        <?php if ($settings['menu_banner'] && $settings['menu_banner_position'] == 2): ?>
        <?php echo $menu_banner; ?>
        <?php endif; ?>
      </div>
      <?php if(!empty($manufacturers) && $settings['manufacturers_position'] == 'column'): ?>
      <div class="col col-xs-12 col-sm-1-5">
        <?php echo $category_manufacturers; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php if ($settings['menu_banner'] && $settings['menu_banner_position'] == 3): ?>
    <?php echo $menu_banner; ?>
    <?php endif; ?>
  </div>
  <?php else: ?>
  <?php $tbData->slotEcho('menu_all_categories.categories'); ?>
  <?php endif; ?>
</li>

<?php if ($is_megamenu): ?>
<?php if ($settings['subcategory_hover_thumb']): ?>
<script>
$("#menu_all_categories_<?php echo $menu_item_id; ?>")
.on("mouseenter", "li[data-thumb]", function() {
  var $image = $(this).parent().closest('li[class*="tb_menu_category"]').find("> .thumbnail img");

    if (!$image.is("[original_src]")) {
      $image.attr("original_src", $image.attr("src"));
    }
    $image.attr("src", $(this).attr("data-thumb"));
})
.on("mouseleave", "li[data-thumb]", function() {
  var $image = $(this).parent().closest('li[class*="tb_menu_category"]').find("> .thumbnail img");

    $image.attr("src", $image.attr("original_src"));
});
</script>
<?php endif; ?>
<?php if (!empty($settings['subcategory_column_width']) && $settings['subcategory_direction'] == 'row'): ?>
<style scoped>
[id*="menu_all_categories_<?php echo $menu_item_id; ?>"] .tb_subcategories .tb_grid > li,
[id*="menu_all_categories_<?php echo $menu_item_id; ?>"] .tb_subcategories .tb_grid > div
{
      -ms-flex: 1 1 <?php echo $settings['subcategory_column_width'] + 20; ?>px;
  -webkit-flex: 1 1 <?php echo $settings['subcategory_column_width'] + 20; ?>px;
          flex: 1 1 <?php echo $settings['subcategory_column_width'] + 20; ?>px;
}
</style>
<?php endif; ?>
<?php if (!empty($settings['subcategory_column_width']) && $settings['subcategory_direction'] == 'column'): ?>
<style scoped>
[id*="menu_all_categories_<?php echo $menu_item_id; ?>"] .tb_subcategories .tb_multicolumn {
  -webkit-column-width: <?php echo $settings['subcategory_column_width']; ?>px;
     -moz-column-width: <?php echo $settings['subcategory_column_width']; ?>px;
          column-width: <?php echo $settings['subcategory_column_width']; ?>px;
}
</style>
<?php endif; ?>
<?php endif; ?>