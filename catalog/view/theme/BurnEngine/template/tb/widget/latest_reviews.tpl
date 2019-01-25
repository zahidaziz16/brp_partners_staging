<?php if ($reviews): ?>

<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>

<div class="panel-body">
  <div class="tb_products tb_listing<?php echo $listing_classes; ?>">
    <?php foreach ($reviews as $product): ?>
    <div class="product-thumb">
      <?php if ($product['thumb']): ?>
      <div class="image">
        <a href="<?php echo $product['href']; ?>">
          <span class="image-holder" style="max-width: <?php echo $product['thumb_width']; ?>px;">
          <span style="padding-top: <?php echo round($product['thumb_height'] / $product['thumb_width'], 4) * 100; ?>%">
            <img
              <?php if (!$tbData->system['image_lazyload']): ?>
              src="<?php echo $product['thumb']; ?>"
              <?php else: ?>
              src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif"
              data-src="<?php echo $product['thumb']; ?>"
              class="lazyload"
              <?php endif; ?>
              width="<?php echo $product['thumb_width']; ?>"
              height="<?php echo $product['thumb_height']; ?>"
              alt="<?php echo $product['name']; ?>"
              style="margin-top: -<?php echo round($product['thumb_height'] / $product['thumb_width'], 4) * 100; ?>%;" />
            />
          </span>
          </span>
        </a>
      </div>
      <?php endif; ?>
      <div>
        <div class="caption">
          <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
          <?php if ($show_price && $product['price']): ?>
          <div class="price">
            <?php if (!$product['special']): ?>
            <span class="price-regular"><?php echo $tbData->priceFormat($product['price']); ?></span>
            <?php else: ?>
            <span class="price-old"><?php echo $tbData->priceFormat($product['price']); ?></span>
            <span class="price-new"><?php echo $tbData->priceFormat($product['special']); ?></span>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          <div class="tb_review">
            <?php if ($show_text): ?>
            <p><?php echo $product['review_text']; ?></p>
            <?php endif; ?>
            <div class="tb_meta">
              <p class="tb_author"><strong><?php echo $product['author']; ?></strong> <small>(<?php echo $product['date_added']; ?>)</small></p>
              <div class="rating">
                <div class="tb_bar">
                  <span class="tb_percent" style="width: <?php echo $product['rating'] * 20; ?>%;"></span>
                  <span class="tb_base"></span>
                </div>
                <span class="tb_average"><?php echo $product['rating']; ?>/5</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php if (($show_text && $tooltip_review == 1) || $slider): ?>
<script type="text/javascript">
tbApp.init<?php echo $widget->getDomId(); ?> = function() {
    tbApp.onScriptLoaded(function() {
        
        <?php // TOOLTIP REVIEW ?>
        <?php if ($show_text && $tooltip_review == 1): ?>
        $('#<?php echo $widget_dom_id; ?> .tb_listing .tb_review').each(function() {
            var review  = $(this).find('> p').html(),
                author  = $(this).find('.tb_author').html(),
                tooltip = '<div class="tb_review">' +
                          '  <p>' + review + '</p>' +
                          '  <div class="tb_meta"><p class="tb_author">' + author + '</p></div>' +
                          '</div>',
                template= '<div class="ui-tooltip ui-widget-content">' +
                          '  <div class="tooltip-inner"></div>' +
                          '</div>';

            $(this).tooltip({
                placement: 'auto bottom',
                title: tooltip,
                html: true,
                template: template
            });
        });
        <?php endif; ?>

        <?php // REVIEW SLIDER ?>
        <?php if ($slider): ?>
        tbApp.itemSlider<?php echo $widget->getDomId(); ?> = createItemSlider('#<?php echo $widget->getDomId(); ?>', <?php echo count($reviews); ?>, <?php echo $slider_step; ?>, <?php echo $slider_speed; ?>, <?php $slider_pagination ? print '\'#' . $widget->getDomId() . ' .tb_slider_pagination\'' : print 'false' ; ?>, {"1200":{"items_per_row":1,"items_spacing":0}}, <?php echo $slider_autoplay; ?>, <?php echo $slider_loop; ?>);
        <?php endif; ?>

    });
}
<?php if ($slider): ?>
tbApp.exec<?php echo $widget->getDomId(); ?> = function() {
    tbApp.onScriptLoaded(function() {
        tbApp.itemSlider<?php echo $widget->getDomId(); ?>.refresh();
    });
}
<?php endif; ?>

<?php if (empty($within_group)): ?>
<?php if (!$tbData->system['js_lazyload']): ?>
tbApp.init<?php echo $widget->getDomId(); ?>();
<?php if ($slider): ?>
tbApp.exec<?php echo $widget->getDomId(); ?>();
<?php endif; ?>
<?php else: ?>
$(document).on('lazybeforeunveil', function(e) {
    if ($(e.target).filter('#<?php echo $widget->getDomId(); ?>').length) {
        tbApp.init<?php echo $widget->getDomId(); ?>();
        <?php if ($slider): ?>
        tbApp.exec<?php echo $widget->getDomId(); ?>();
        <?php endif; ?>
    }
});
<?php endif; ?>
<?php endif; ?>
</script>
<?php endif; ?>
<?php endif; ?>
