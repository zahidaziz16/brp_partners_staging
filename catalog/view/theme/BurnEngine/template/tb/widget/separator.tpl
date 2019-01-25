<div class="tb_separator">
  <?php if ($title): ?>
  <span class="tb_title">
    <?php echo $title; ?>
    <span class="tb_position_left <?php echo $separator_classes; ?>" style="<?php echo $separator_styles; ?>"></span>
    <span class="tb_position_right <?php echo $separator_classes; ?>" style="<?php echo $separator_styles; ?>"></span>
  </span>
  <?php else: ?>
  <span class="clear <?php echo $separator_classes; ?>" style="<?php echo $separator_styles; ?>"></span>
  <?php endif; ?>
</div>