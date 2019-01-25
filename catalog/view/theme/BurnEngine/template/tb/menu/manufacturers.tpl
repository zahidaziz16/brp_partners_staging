<li id="menu_brands" class="tb_menu_brands dropdown tb_link<?php if ($is_megamenu) echo ' tb_megamenu'; ?>">
  <?php if (!empty($manufacturers)): ?>
  <span class="tb_toggle tb_bg_str_2"></span>
  <?php endif; ?>
  <a href="<?php echo $url->link('product/manufacturer'); ?>">
    <span class="tb_text"><?php echo $menu_icon; ?><?php echo $settings['label']; ?></span>
    <?php echo $accent_label; ?>
  </a>
  <?php if (!empty($manufacturers)): ?>
  <?php if ($is_megamenu): ?>
  <div class="dropdown-menu">
    <div class="row tb_separate_columns tb_ip_20">
      <div class="col col-xs-12">
        <div class="tb_multicolumn">
          <?php foreach ($manufacturers as $columns): ?>
          <?php foreach ($columns as $first_letter => $brands): ?>
          <div class="tb_letter">
            <strong class="h4"><?php echo $first_letter; ?></strong>
            <ul class="tb_list_1">
              <?php foreach ($brands as $manufacturer): ?>
              <li class="tb_link"><a href="<?php echo $url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']); ?>"><?php if ($manufacturer['display_name']) echo $manufacturer['name']; ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <?php else: ?>
  <<?php if ($settings['manufacturers_display'] == 'label'): ?>ul class="dropdown-menu tb_list_1"<?php else: ?>div class="dropdown-menu"<?php endif; ?> style="width: <?php echo $settings['width']; ?>px;">
    <?php if ($settings['manufacturers_display'] == 'image'): ?>
    <ul class="tb_images">
    <?php endif; ?>
    <?php foreach ($manufacturers as $columns): ?>
    <?php foreach ($columns as $first_letter => $brands): ?>
    <?php foreach ($brands as $manufacturer): ?>
    <?php if ($manufacturer['display_name'] || $manufacturer['image']): ?>
    <li class="tb_link">
      <a href="<?php echo $url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']); ?>">
        <?php if ($manufacturer['display_name']) echo $manufacturer['name']; ?>
        <?php if ($manufacturer['image']): ?>
        <img src="<?php echo $manufacturer['image']; ?>" width="<?php echo $manufacturer['image_width']; ?>" height="<?php echo $manufacturer['image_height']; ?>" alt="<?php echo $manufacturer['name']; ?>" />
        <?php endif; ?>
      </a>
    </li>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endforeach; ?>
    <?php endforeach; ?>
    <?php if ($settings['manufacturers_display'] == 'image'): ?>
    </ul>
    <?php endif; ?>
  </<?php if ($settings['manufacturers_display'] == 'label'): ?>ul<?php else: ?>div<?php endif; ?>>
  <?php endif; ?>
  <?php endif; ?>
</li>