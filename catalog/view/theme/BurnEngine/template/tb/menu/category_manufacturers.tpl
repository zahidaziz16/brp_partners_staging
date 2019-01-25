<?php if (!empty($manufacturers)): ?>
<ul class="tb_category_brands">
  <li class="tb_menu_brands">
    <span class="tb_toggle tb_bg_str_2"></span>
    <?php if($title && !empty($settings['manufacturers_title'])): ?>
    <span<?php if ($is_megamenu) echo ' class="h4 display-block"'; ?>><?php echo $title; ?></span>
    <?php endif; ?>
    <ul class="<?php if ($settings['manufacturers_display'] == 'label'): ?>tb_list_1 tb_multicolumn<?php else: ?>tb_images<?php endif; ?>">
      <?php foreach ($manufacturers as $manufacturer): ?>
      <?php if ($settings['manufacturers_display'] == 'image' && $manufacturer['image']): ?>
      <li class="text-center">
        <a href="<?php echo $manufacturer['href']; ?>">
          <?php if ($manufacturer['image']): ?>
          <img src="<?php echo $manufacturer['image']; ?>" width="<?php echo $manufacturer['image_width']; ?>" height="<?php echo $manufacturer['image_height']; ?>" alt="<?php echo $manufacturer['name']; ?>" />
          <?php endif; ?>
        </a>
      </li>
      <?php endif; ?>
      <?php if ($manufacturer['display_name']): ?>
      <li class="tb_link"><a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></a></li>
      <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </li>
</ul>
<?php endif; ?>