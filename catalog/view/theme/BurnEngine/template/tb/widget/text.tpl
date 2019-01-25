<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>
<?php if ($text): ?>
<div class="panel-body tb_text_wrap">
<?php echo $text; ?>
</div>
<?php endif; ?>