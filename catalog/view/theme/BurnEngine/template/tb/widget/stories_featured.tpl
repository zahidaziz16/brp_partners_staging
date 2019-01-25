<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>

<div class="panel-body">
  <div class="tb_articles <?php echo $listing_classes; ?>">

    <?php foreach ($stories as $story): ?>
    <div class="tb_article tb_item">
      <?php if ($story['thumb']): ?>
      <a class="thumbnail" href="<?php echo $story['url']; ?>">
        <span class="image-holder" style="max-width: <?php echo $story['thumb_width']; ?>px;">
        <span style="padding-top: <?php echo round($story['thumb_height'] / $story['thumb_width'], 4) * 100; ?>%">
          <img
            <?php if (!$tbData->system['image_lazyload']): ?>
            src="<?php echo $story['thumb']; ?>"
            <?php else: ?>
            src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif"
            data-src="<?php echo $story['thumb']; ?>"
            class="lazyload"
            <?php endif; ?>
            width="<?php echo $story['thumb_width']; ?>"
            height="<?php echo $story['thumb_height']; ?>"
            alt="<?php echo $story['title']; ?>"
            style="margin-top: -<?php echo round($story['thumb_height'] / $story['thumb_width'], 4) * 100; ?>%" />
          />
        </span>
        </span>
      </a>
      <?php endif; ?>
      <div class="tb_item_info">
        <h4><a href="<?php echo $story['url']; ?>"><?php echo $story['title']; ?></a></h4>
        <?php if ($show_text): ?>
        <p class="tb_description"><?php echo $story['description']; ?></p>
        <?php endif; ?>
        <div class="tb_meta">
          <?php if ($show_date): ?>
          <p class="tb_date">
            <i class="fa fa-calendar"></i>
            <?php echo $story['date_added']; ?>
          </p><?php endif; ?><?php if ($show_tags && $story['tags']): ?><p class="tb_tags">
            <i class="fa fa-tags"></i>
            <?php $i=1; foreach ($story['tags'] as $tag): ?>
            <a href="<?php echo $tag['url']; ?>"><?php echo $tag['name']; ?></a><?php if ($i < count($story['tags'])): ?>, <?php endif; ?>
            <?php $i++; endforeach; ?>
          </p><?php endif; ?><?php if ($show_comments && $main_settings['comments'] != 'disabled'): ?><p class="tb_commens_count">
            <i class="fa fa-comments"></i>
            <?php if ($main_settings['comments'] == 'disqus'): ?>
            <a href="<?php echo $story['url']; ?>#disqus_thread"></a>
            <?php endif; ?>
            <?php if ($main_settings['comments'] == 'facebook'): ?>
            <a href="<?php echo $story['url']; ?>#comments"><span class="fb-comments-count" data-href="<?php echo $story['url']; ?>"></span></a>
            <?php endif; ?>
          </p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</div>

<script type="text/javascript">
tbApp.init<?php echo $widget->getDomId(); ?> = function() {
    tbApp.onScriptLoaded(function() {
        <?php // ARTICLE SLIDER ?>
        <?php if (!empty($slider)): ?>
        tbApp.itemSlider<?php echo $widget->getDomId(); ?> = createItemSlider('#<?php echo $widget->getDomId(); ?>', <?php echo count($stories); ?>, <?php echo $slider_step; ?>, <?php echo $slider_speed; ?>, <?php $slider_pagination ? print '\'#' . $widget->getDomId() . ' .tb_slider_pagination\'' : print 'false' ; ?>, <?php echo $restrictions_json; ?>, <?php echo $slider_autoplay; ?>, <?php echo $slider_loop; ?>);

        if (tbApp.itemSlider<?php echo $widget->getDomId(); ?>SwiperPromiseCallback !== undefined) {
            tbApp.itemSlider<?php echo $widget->getDomId(); ?>.swiperPromise.done(tbApp.itemSlider<?php echo $widget->getDomId(); ?>SwiperPromiseCallback);
        }
        <?php endif; ?>
    });
};
tbApp.exec<?php echo $widget->getDomId(); ?> = function() {
    tbApp.onScriptLoaded(function() {
        <?php // ADJUST PRODUCT SIZE ?>
        <?php if (isset($view_mode) && $view_mode == 'grid' && (!empty($within_group) || (!$tbData->optimize_js_load && !$tbData->system['js_lazyload']))): ?>
        adjustItemSize('#<?php echo $widget->getDomId(); ?>', <?php echo $restrictions_json; ?>);
        <?php endif; ?>

        <?php // REFRESH SLIDER ?>
        <?php if (!empty($slider)): ?>
        tbApp.itemSlider<?php echo $widget->getDomId(); ?>.refresh();
        <?php endif; ?>
    });
};

<?php if (empty($within_group)): ?>
<?php if (!$tbData->system['js_lazyload']): ?>
tbApp.init<?php echo $widget->getDomId(); ?>();
tbApp.exec<?php echo $widget->getDomId(); ?>();
<?php else: ?>
$(document).on('lazybeforeunveil', function(e) {
    if ($(e.target).filter('#<?php echo $widget->getDomId(); ?>').length) {
        tbApp.init<?php echo $widget->getDomId(); ?>();
        tbApp.exec<?php echo $widget->getDomId(); ?>();
    }
});
<?php endif; ?>
<?php endif; ?>
</script>

<?php if ((isset($view_mode) && $view_mode == 'grid') && empty($within_group) && ($tbData->optimize_js_load || $tbData->system['js_lazyload'])): ?>
<script type="text/javascript" data-critical="1">
adjustItemSize('#<?php echo $widget->getDomId(); ?>', <?php echo $restrictions_json; ?>);
</script>
<?php endif; ?>
