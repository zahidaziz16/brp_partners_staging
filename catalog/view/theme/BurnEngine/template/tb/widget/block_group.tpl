<div class="row tb_gut_xs_<?php echo $columns_gutter; ?> tb_gut_sm_<?php echo $columns_gutter; ?> tb_gut_md_<?php echo $columns_gutter; ?> tb_gut_lg_<?php echo $columns_gutter; ?>">
  <?php foreach ($sections as $id => $section): ?>
  <div class="col col-xs-<?php echo $section['width_xs']; ?> col-sm-<?php echo $section['width_sm']; ?> col-md-<?php echo $section['width_md']; ?> col-lg-<?php echo $section['width_md']; ?> col-align-<?php echo $section['halign']; ?> col-valign-<?php echo $section['valign']; ?>">
    <?php foreach ($section['widgets'] as $widget_content): ?>
    <?php echo $widget_content; ?>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
</div>