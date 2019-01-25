<li<?php echo $menu_attributes; ?>>
  <span class="tb_toggle tb_bg_str_2"></span>

  <?php if (!empty($category_label)): ?>
  <a<?php echo $label_classes; ?> href="<?php echo $category['url']; ?>"<?php echo $label_attributes; ?>>
    <span class="tb_text"><?php echo $menu_icon; ?><span><?php echo $category_label; ?></span></span>
    <?php echo $accent_label; ?>
  </a>
  <?php endif; ?>

  <?php if (!empty($subcategories)): ?>
  <?php if ($settings['is_megamenu']): ?>
  <div<?php echo $dropdown_attributes; ?>>
    <div class="row tb_separate_columns tb_ip_20">
      <?php if ($settings['menu_banner'] && ($settings['menu_banner_position'] == 0 || $settings['menu_banner_position'] == 1)): ?>
      <?php echo $menu_banner; ?>
      <?php endif; ?>
      <div class="col col-xs-12 col-sm-fill">
        <div class="tb_subcategories"><?php echo $subcategories; ?></div>
        <?php if(!empty($manufacturers) && $settings['manufacturers_position'] == 'below'): ?>
        <span class="clear border tb_mt_20 tb_mb_20 tb_ml_-20 tb_mr_-20"></span>
        <?php echo $category_manufacturers; ?>
        <?php endif; ?>
        <?php if ($settings['menu_banner'] && $settings['menu_banner_position'] == 2): ?>
        <?php echo $menu_banner; ?>
        <?php endif; ?>
      </div>
      <?php if(!empty($manufacturers) && $settings['manufacturers_position'] == 'column'): ?>
      <div class="col col-xs-12 col-sm-1-5"<?php if (!empty($settings['manufacturers_column_size'])) { echo ' style="width: ' . $settings['manufacturers_column_size'] . '%; max-width: none;"'; } ?>>
        <?php echo $category_manufacturers; ?>
      </div>
      <?php endif; ?>
      <?php if($category_info): ?>
      <div class="col col-xs-12 col-sm-1-5 col-valign-<?php echo $settings['information_valign']; ?> tb_category_info_col tb_pt_20 tb_pr_20 tb_pb_20 tb_pl_20"<?php if (!empty($settings['info_column_size'])) { echo ' style="width: ' . $settings['info_column_size'] . '%; max-width: none;"'; } ?>>
        <div class="tb_category_info">
          <?php if ($settings['show_title']): ?>
          <h2><?php echo $category['name']; ?></h2>
          <?php endif; ?>
          <?php if ($settings['show_main_thumbnail'] && $settings['is_megamenu']): ?>
          <img class="thumbnail" src="<?php echo $category['thumb']; ?>" width="<?php echo $category['thumb_width']; ?>" height="<?php echo $category['thumb_height']; ?>" alt="<?php echo $category['name']; ?>" />
          <?php endif; ?>
          <?php if ($settings['description']): ?>
          <p class="tb_desc"><?php echo $settings['description']; ?></p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php if(!empty($manufacturers) && $settings['manufacturers_position'] == 'bottom'): ?>
    <span class="clear border tb_mt_20 tb_mb_20 tb_ml_-20 tb_mr_-20"></span>
    <?php echo $category_manufacturers; ?>
    <?php endif; ?>
    <?php if ($settings['menu_banner'] && $settings['menu_banner_position'] == 3): ?>
    <?php echo $menu_banner; ?>
    <?php endif; ?>
  </div>
  <?php else: ?>
  <?php echo $subcategories; ?>
  <?php endif; ?>
  <?php endif; ?>

</li>

<?php if ($settings['is_megamenu'] && ($settings['category_thumb'] || $settings['subcategory_hover_thumb'])): ?>
<script>

<?php // Category thumb hover ?>
<?php if ($settings['category_thumb']): ?>
var margin = '<?php echo $tbData->language_direction == 'left' ? 'margin-left' : 'margin-right'; ?>';

$("#menu_category_<?php echo $menu_item_id; ?>").find(".tb_subcategories > ul > li").each(function() {
    var image_source = $(this).is("[data-thumb]") ? $(this).attr("data-thumb") : "<?php echo $toolImage->resizeImage('no_image.jpg', $settings['cat_image_size_x'], $settings['cat_image_size_y']); ?>";

    $(this).find("> a").after('<span class="thumbnail"><img src="' + image_source + '" /></span>');
    $(this).find("> .tb_submenu > ul").css(margin, <?php echo $settings['cat_image_size_x']; ?>);
});
<?php endif; ?>

<?php // Subcategory thumb hover ?>
<?php if ($settings['subcategory_hover_thumb']): ?>
$('#menu_category_<?php echo $menu_item_id; ?>')
.on("mouseenter", "li[data-thumb]", function() {
  var $image = $(this).parent().closest('li[class*="category_"]').find("> .thumbnail img");

  if ($image.length) {
    if (!$image.is("[original_src]")) {
      $image.attr("original_src", $image.attr("src"));
    }
    $image.attr("src", $(this).attr("data-thumb"));
  }
})
.on("mouseleave", "li[data-thumb]", function() {
  var $image = $(this).parent().closest('li[class*="category_"]').find("> .thumbnail img");

  if ($image.length) {
    $image.attr("src", $image.attr("original_src"));
  }
});
<?php endif; ?>
</script>
<?php endif; ?>

<?php if (!empty($settings['subcategory_column_width']) && $settings['subcategory_direction'] == 'row'): ?>
<style scoped>
[id*="menu_category_<?php echo $menu_item_id; ?>"] .tb_subcategories .tb_grid > li,
[id*="menu_category_<?php echo $menu_item_id; ?>"] .tb_subcategories .tb_grid > div
{
      -ms-flex: 1 1 <?php echo $settings['subcategory_column_width'] + 20; ?>px;
  -webkit-flex: 1 1 <?php echo $settings['subcategory_column_width'] + 20; ?>px;
          flex: 1 1 <?php echo $settings['subcategory_column_width'] + 20; ?>px;
}
</style>
<?php endif; ?>
<?php if (!empty($settings['subcategory_column_width']) && $settings['subcategory_direction'] == 'column'): ?>
<style scoped>
[id*="menu_category_<?php echo $menu_item_id; ?>"] .tb_subcategories .tb_multicolumn {
  -webkit-column-width: <?php echo $settings['subcategory_column_width']; ?>px;
     -moz-column-width: <?php echo $settings['subcategory_column_width']; ?>px;
          column-width: <?php echo $settings['subcategory_column_width']; ?>px;
}
</style>
<?php endif; ?>
<?php if (!empty($subcategories) && $category_info): ?>
<style scoped>
[id*="menu_category_<?php echo $menu_item_id; ?>"] .tb_category_info_col {
  <?php if (!empty($settings['category_information_color'])): ?>
  color: <?php echo $settings['category_information_color']; ?>;
  <?php endif; ?>
  <?php if(!empty($settings['category_custom_bg'])): ?>
  background: url("<?php echo $settings['category_custom_bg']; ?>") no-repeat top center / cover;
  <?php endif; ?>
}
</style>
<?php endif; ?>
