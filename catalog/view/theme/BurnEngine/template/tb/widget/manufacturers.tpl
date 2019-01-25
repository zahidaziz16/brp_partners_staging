<?php if ($manufacturers): ?>

<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>

<div class="panel-body">
  <div class="tb_manufacturers tb_listing tb_grid_view tb_style_1 <?php echo $listing_classes; ?> tb_gut_30">
    <?php foreach ($manufacturers as $manufacturer):  ?>
    <div class="tb_manufacturer tb_item">
      <a class="thumbnail" href="<?php echo $url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']); ?>">
        <span class="image-holder" style="max-width: <?php echo $manufacturer['thumb_width']; ?>px;">
        <span style="padding-top: <?php echo round($manufacturer['thumb_height'] / $manufacturer['thumb_width'], 4) * 100; ?>%">
          <img
            <?php if (!$tbData->system['image_lazyload']): ?>
            src="<?php echo $manufacturer['thumb']; ?>"
            <?php else: ?>
            src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif"
            data-src="<?php echo $manufacturer['thumb']; ?>"
            class="lazyload"
            <?php endif; ?>
            width="<?php echo $manufacturer['thumb_width']; ?>"
            height="<?php echo $manufacturer['thumb_height']; ?>"
            title="<?php echo $manufacturer['name']; ?>"
            alt="<?php echo $manufacturer['name']; ?>"
            style="margin-top: -<?php echo round($manufacturer['thumb_height'] / $manufacturer['thumb_width'], 4) * 100; ?>%" />
        </span>
        </span>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<script type="text/javascript">
<?php if ($slider): ?>
tbApp.init<?php echo $widget->getDomId(); ?> = function() {
    tbApp.onScriptLoaded(function() {
        <?php // ITEM SLIDER ?>
        tbApp.itemSlider<?php echo $widget->getDomId(); ?> = createItemSlider('#<?php echo $widget->getDomId(); ?>', <?php echo count($manufacturers); ?>, <?php echo $slider_step; ?>, <?php echo $slider_speed; ?>, <?php $slider_pagination ? print '\'#' . $widget->getDomId() . ' .tb_slider_pagination\'' : print 'false' ; ?>, <?php echo $restrictions_json; ?>, <?php echo $slider_autoplay; ?>, <?php echo $slider_loop; ?>);
    });
};
<?php endif; ?>
tbApp.exec<?php echo $widget->getDomId(); ?> = function() {
    tbApp.onScriptLoaded(function() {
        <?php // ADJUST ITEM SIZE ?>
        <?php if (!empty($within_group) || (!$tbData->optimize_js_load && !$tbData->system['js_lazyload'])): ?>
        adjustItemSize('#<?php echo $widget->getDomId(); ?>', <?php echo $restrictions_json; ?>)
        <?php endif; ?>

        <?php // REFRESH SLIDER ?>
        <?php if ($slider): ?>
        tbApp.itemSlider<?php echo $widget->getDomId(); ?>.refresh();
        <?php endif; ?>
    });
};

<?php if (empty($within_group)): ?>
<?php if (!$tbData->system['js_lazyload']): ?>
<?php if ($slider): ?>
tbApp.init<?php echo $widget->getDomId(); ?>();
<?php endif; ?>
tbApp.exec<?php echo $widget->getDomId(); ?>();
<?php else: ?>
$(document).on('lazybeforeunveil', function(e) {
    if ($(e.target).filter('#<?php echo $widget->getDomId(); ?>').length) {
        <?php if ($slider): ?>
        tbApp.init<?php echo $widget->getDomId(); ?>();
        <?php endif; ?>
        tbApp.exec<?php echo $widget->getDomId(); ?>();
    }
});
<?php endif; ?>
<?php endif; ?>
</script>

<?php if (empty($within_group) && ($tbData->optimize_js_load || $tbData->system['js_lazyload'])): ?>
<script type="text/javascript" data-critical="1">
adjustItemSize('#<?php echo $widget->getDomId(); ?>', <?php echo $restrictions_json; ?>);
</script>
<?php endif; ?>

<?php endif; ?>

