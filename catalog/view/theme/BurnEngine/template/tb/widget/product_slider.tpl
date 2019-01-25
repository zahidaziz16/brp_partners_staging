<?php if ($products): ?>

<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>
<div class="panel-body">
  <div class="row tb_gut_xs_30 tb_gut_sm_40 tb_gut_md_50">
    <div class="col-xs-12 col-sm-<?php echo $column_size; ?> col-md-<?php echo $column_size; ?> col-valign-middle">
      <div class="tb_item_thumb_wrap">
        <div class="tb_item_thumb">
        <?php echo $products_html; ?>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-<?php echo 12 - $column_size; ?> col-md-<?php echo 12 - $column_size; ?> col-valign-middle">
      <div class="tb_item_info tb_list_view"></div>
    </div>
  </div>
</div>

<?php if (!empty($js)): ?>
<?php echo $js; ?>
<?php endif; ?>

<script>
tbApp.onScriptLoaded(function() {
    var callback = function(swiperObj) {

        var $cont = $('#<?php echo $widget->getDomId(); ?>');

        var slideCallBack = function(swiper) {
            var $el = $(swiper.slides[swiper.activeIndex]).find('.product-thumb').clone();

            $el.find('.image > a, > p').remove().end();

            $cont.find('.tb_item_info').addClass('tbShowInfo').html($el);
        };

        slideCallBack(swiperObj);
        swiperObj.on('slideChangeStart', function() {
            $cont.find('.tb_item_info').removeClass('tbShowInfo');
        });
        swiperObj.on('slideChangeEnd', slideCallBack);

        <?php if (!empty($slider_autoplay)): ?>
        swiperObj.params.autoplay = <?php echo $slider_autoplay; ?>;
        swiperObj.onResize();
        swiperObj.startAutoplay();
        <?php endif; ?>
    };

    if (tbApp.itemSlider<?php echo $widget->getDomId(); ?> !== undefined) {
        tbApp.itemSlider<?php echo $widget->getDomId(); ?>.swiperPromise.done(callback);
    } else {
        tbApp.itemSlider<?php echo $widget->getDomId(); ?>SwiperPromiseCallback = callback;
    }
});
</script>

<?php endif; ?>