<li id="menu_html_<?php echo $menu_item['data']['id']; ?>"<?php echo $menu_classes; ?>>
  <span class="tb_toggle tb_bg_str_2"></span>
  <?php if (!empty($menu_item['data']['settings']['url'])): ?>
  <a<?php echo $label_classes; ?> href="<?php echo $url; ?>"<?php echo $target; ?><?php echo $title; ?>>
    <span class="tb_text"><?php echo $menu_icon; ?><?php echo $label; ?></span>
    <?php echo $accent_label; ?>
  </a>
  <?php else: ?>
  <a href="javascript:;">
    <span class="tb_text"><?php echo $menu_icon; ?><?php echo $menu_item['data']['settings']['label']; ?></span>
    <?php echo $accent_label; ?>
  </a>
  <?php endif; ?>
  <?php if (!empty($menu_item['data']['settings']['html_text'])): ?>
  <div class="dropdown-menu" style="width: <?php echo $menu_item['data']['settings']['dropdown_width']; ?><?php echo $menu_item['data']['settings']['dropdown_width_metric']; ?>">
    <?php echo $menu_item['data']['settings']['html_text']; ?>
  </div>
  <?php endif; ?>
</li>