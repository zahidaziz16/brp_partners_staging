<?php if ($group_type == 'tabs'): ?>
<?php if ($title && $tabs_style == 3 && $tabs_direction == 'horizontal'): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>

<div class="<?php echo $group_classes; ?>">

  <ul class="nav nav-tabs <?php echo $nav_classes; ?> <?php echo $title_classes; ?>" style="<?php echo $tabs_nav_style; ?>">
    <?php $i = 0; ?>
    <?php foreach ($sections as $id => $section): ?><li<?php if($i == 0) echo ' class="active"'; ?>>
      <a class="tb_pl_<?php echo $nav_padding; ?> tb_pr_<?php echo $nav_padding; ?>" href="#<?php echo $id; ?>_tab" data-toggle="tab">
        <?php if (!empty($section['icon'])): ?>
        <span class="tb_icon <?php echo $section['icon']; ?>" style="font-size: <?php echo $section['icon_size']; ?>px;"></span>
        <?php endif; ?>
        <?php if (!empty($section['title'])): ?>
        <span><?php echo $section['title']; ?></span>
        <?php elseif (empty($section['icon'])): ?>
        <span><?php echo 'Tab ' . ($i + 1); ?></span>
        <?php endif; ?>
      </a>
    </li><?php $i++; ?><?php endforeach; ?>
  </ul>

  <div class="tab-content">
    <?php $i = 0; ?>
    <?php foreach ($sections as $id => $section): ?>
    <div id="<?php echo $id; ?>_tab" class="tab-pane<?php if ($tabs_fade) echo ' fade'; ?><?php if($i == 0) echo ' active in'; ?>">
      <div class="panel-body tb_pl_<?php echo $content_padding_side; ?> tb_pr_<?php echo $content_padding_side; ?> tb_pt_<?php echo $content_padding_top; ?> tb_pb_<?php echo $content_padding_top; ?> clearfix">
        <?php foreach ($section['widgets'] as $widget_content): ?>
        <?php echo $widget_content; ?>
        <?php endforeach; ?>
      </div>
    </div>
    <?php $i++; ?>
    <?php endforeach; ?>
  </div>

</div>

<?php else: ?>

<div id="accordion_<?php echo $widget->getDomId(); ?>" class="panel-group <?php echo $group_classes; ?>">
  <?php $i = 0; ?>
  <?php foreach ($sections as $id => $section): ?>
  <div class="panel">
    <div class="panel-heading">
      <h2 class="panel-title">
        <a class="tb_pl_<?php echo $nav_padding; ?> tb_pr_<?php echo $nav_padding; ?><?php if($i != 0 || $accordion_closed) echo ' collapsed'; ?><?php if (!empty($section['icon'])) echo ' has_icon'; ?>" href="#panel_<?php echo $id; ?>" data-toggle="collapse" data-parent="#accordion_<?php echo $widget->getDomId(); ?>">
          <?php if (!empty($section['icon'])): ?>
          <span class="tb_icon <?php echo $section['icon']; ?>" style="font-size: <?php echo $section['icon_size']; ?>px;"></span>
          <?php endif; ?>
          <?php if (!empty($section['title'])): ?>
          <?php echo $section['title']; ?>
          <?php elseif (empty($section['icon'])): ?>
          <?php echo 'Tab ' . ($i + 1); ?>
          <?php endif; ?>
        </a>
      </h2>
    </div>
    <div id="panel_<?php echo $id; ?>" class="panel-collapse collapse<?php if($i == 0 && !$accordion_closed) echo ' in tbActivated'; ?>">
      <div class="panel-body tb_pl_<?php echo $content_padding_side; ?> tb_pr_<?php echo $content_padding_side; ?> tb_pt_<?php echo $content_padding_top; ?> tb_pb_<?php echo $content_padding_top; ?>">
        <?php foreach ($section['widgets'] as $widget_content): ?>
        <?php echo $widget_content; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php $i++; ?>
  <?php endforeach; ?>
</div>

<?php endif; ?>

<script type="text/javascript">
tbApp.on("inlineScriptsLoaded", function() {
    <?php if (!$tbData->system['js_lazyload']): ?>
    createGroup('<?php echo $widget->getDomId(); ?>', '<?php echo $group_type; ?>');
    <?php else: ?>
    $(document).on('lazybeforeunveil', function(e) {
        if ($(e.target).filter('#<?php echo $widget->getDomId(); ?>').length) {
            createGroup('<?php echo $widget->getDomId(); ?>', '<?php echo $group_type; ?>');
        }
    });
    <?php endif; ?>
});
</script>
