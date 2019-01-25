<?php if ($image
          || !empty($hover_zoom)
          || !empty($hover_color)
          || ($line_1 && $line_1_hover)
          || ($line_2 && $line_2_hover)
          || ($line_3 && $line_3_hover)
): ?>
<style scoped>
<?php if ($image): ?>
#<?php echo $widget->getDomId(); ?> .tb_image {
  background-image: url('<?php echo $image; ?>');
  <?php if (!empty($image_position)): ?>
  background-position: <?php echo $image_position; ?>;
  <?php endif; ?>
  <?php if (!empty($zoom_origin)): ?>
  -webkit-transform-origin: <?php echo $zoom_origin; ?>;
          transform-origin: <?php echo $zoom_origin; ?>;
  <?php endif; ?>
}
<?php if (!empty($hover_zoom)): ?>
#<?php echo $widget->getDomId(); ?>:hover .tb_image {
  -webkit-transform: scale(<?php echo $zoom_size; ?>);
          transform: scale(<?php echo $zoom_size; ?>);
}
<?php endif; ?>
<?php endif; ?>
<?php if (!empty($hover_color)): ?>
#<?php echo $widget->getDomId(); ?>:hover .tb_hover_color:before {
  opacity: <?php echo $color_opacity; ?>;
}
<?php endif; ?>
<?php if ($line_1 && ($line_1_hover || $line_1_offset)): ?>
#<?php echo $widget->getDomId(); ?>:not(:hover) .tb_line_1 {
  <?php if (!empty($line_1_offset)): ?>
  <?php echo $line_1_move_direction; ?>: -<?php echo $line_1_offset; ?>px;
  <?php endif; ?>
  <?php if (!empty($line_1_hide_delay)): ?>
  -webkit-transition-delay: <?php echo $line_1_hide_delay; ?>ms;
          transition-delay: <?php echo $line_1_hide_delay; ?>ms;
  <?php endif; ?>
}
#<?php echo $widget->getDomId(); ?>:hover .tb_line_1 {
  <?php if (!empty($line_1_offset)): ?>
  <?php echo $line_1_move_direction; ?>: 0;
  <?php endif; ?>
  <?php if (!empty($line_1_show_delay)): ?>
  -webkit-transition-delay: <?php echo $line_1_show_delay; ?>ms;
          transition-delay: <?php echo $line_1_show_delay; ?>ms;
  <?php endif; ?>
}
<?php endif; ?>
<?php if ($line_2 && ($line_2_hover || $line_2_offset)): ?>
#<?php echo $widget->getDomId(); ?>:not(:hover) .tb_line_2 {
  <?php if (!empty($line_2_offset)): ?>
  <?php echo $line_2_move_direction; ?>: -<?php echo $line_2_offset; ?>px;
  <?php endif; ?>
  <?php if (!empty($line_2_hide_delay)): ?>
  -webkit-transition-delay: <?php echo $line_2_hide_delay; ?>ms;
          transition-delay: <?php echo $line_2_hide_delay; ?>ms;
  <?php endif; ?>
}
#<?php echo $widget->getDomId(); ?>:hover .tb_line_2 {
  <?php if (!empty($line_2_offset)): ?>
  <?php echo $line_2_move_direction; ?>: 0;
  <?php endif; ?>
  <?php if (!empty($line_2_show_delay)): ?>
  -webkit-transition-delay: <?php echo $line_2_show_delay; ?>ms;
          transition-delay: <?php echo $line_2_show_delay; ?>ms;
  <?php endif; ?>
}
<?php endif; ?>
<?php if ($line_3 && ($line_3_hover || $line_3_offset)): ?>
#<?php echo $widget->getDomId(); ?>:not(:hover) .tb_line_3 {
  <?php if (!empty($line_3_offset)): ?>
  <?php echo $line_3_move_direction; ?>: -<?php echo $line_3_offset; ?>px;
  <?php endif; ?>
  <?php if (!empty($line_3_hide_delay)): ?>
  -webkit-transition-delay: <?php echo $line_3_hide_delay; ?>ms;
          transition-delay: <?php echo $line_3_hide_delay; ?>ms;
  <?php endif; ?>
}
#<?php echo $widget->getDomId(); ?>:hover .tb_line_3 {
  <?php if (!empty($line_3_offset)): ?>
  <?php echo $line_3_move_direction; ?>: 0;
  <?php endif; ?>
  <?php if (!empty($line_3_show_delay)): ?>
  -webkit-transition-delay: <?php echo $line_3_show_delay; ?>ms;
          transition-delay: <?php echo $line_3_show_delay; ?>ms;
  <?php endif; ?>
}
<?php endif; ?>
</style>
<?php endif; ?>
<div class="tb_banner<?php echo $padding; ?>">
  <div class="tb_text_wrap">
    <?php if ($line_1 || $line_2 || $line_3): ?>
    <div class="tb_text text-<?php echo $text_align; ?> valign-<?php echo $text_valign; ?>">
      <?php if ($line_1): ?>
      <span class="tb_line_1 <?php echo $line_1_classes; ?>">
        <?php echo html_entity_decode($line_1); ?>
      </span>
      <?php endif; ?>
      <?php if ($line_2): ?>
      <span class="tb_line_2 <?php echo $line_2_classes; ?>">
        <?php echo html_entity_decode($line_2); ?>
      </span>
      <?php endif; ?>
      <?php if ($line_3): ?>
      <span class="tb_line_3 <?php echo $line_3_classes; ?>">
        <?php echo html_entity_decode($line_3); ?>
      </span>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
<div class="tb_image<?php if (!empty($hover_color)) echo ' tb_hover_color'; ?>"></div>
<?php if (!empty($url)): ?>
<a href="<?php echo $url; ?>" target="<?php echo $url_target; ?>"></a>
<?php endif; ?>
<img class="tb_ratio<?php if (!$max_height) echo ' tb_no_max_height'; ?>" src="<?php echo $ratio_img; ?>"<?php if ($min_height) echo ' style="min-height: ' . $min_height . 'px;"'; ?> alt="proportion" />
