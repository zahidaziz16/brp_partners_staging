<div class="tb_description tb_text_wrap tb_button_<?php echo $button_position; ?>">
  <?php echo $text; ?>
</div>
<div class="tb_button_holder">
  <a class="btn<?php echo $button_classes; ?>" href="<?php echo $button_url; ?>" target="<?php echo $url_target; ?>">
    <?php if ($button_icon): ?>
    <span class="<?php echo $button_icon; ?>" style="font-size: <?php echo $button_icon_size; ?>px"></span>&nbsp;
    <?php endif; ?>
    <?php echo $button_text; ?>
  </a>
</div>
