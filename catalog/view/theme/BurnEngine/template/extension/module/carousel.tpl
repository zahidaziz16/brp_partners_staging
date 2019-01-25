<?php if (!isset($tbData)) require DIR_APPLICATION . '/view/theme/BurnEngine/template/tb/install_error.tpl'; ?>
<?php if (!$banners) return; ?>
<style scoped>
#{{widget_dom_id}} .tb_carousel > div:not(.swiper-container) {
  float: left;
  width: <?php echo 100/count($banners); ?>%;
}
</style>

<div class="tb_carousel tb_listing tb_grid_view tb_size_4 tb_slider tb_side_nav">
  <?php foreach ($banners as $banner): ?>
  <div>
    <?php if ($banner['link']): ?>
    <a href="<?php echo $banner['link']; ?>">
      <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" />
    </a>
    <?php else: ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" />
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>

<script type="text/javascript">
tbApp.init{{widget_dom_id}} = function() {
    tbApp.onScriptLoaded(function() {
        <?php // ITEM SLIDER ?>
        tbApp.itemSlider{{widget_dom_id}} = createItemSlider('#{{widget_dom_id}}', <?php echo count($banners); ?>, 1, 500, '#{{widget_dom_id}} .tb_slider_pagination', {"1200":{"items_per_row":4,"items_spacing":30},"767":{"items_per_row":3,"items_spacing":30},"480":{"items_per_row":2,"items_spacing":30},"300":{"items_per_row":1,"items_spacing":30}});
    });
}
tbApp.exec{{widget_dom_id}} = function() {
    tbApp.onScriptLoaded(function() {
        <?php // REFRESH SLIDER ?>
        tbApp.itemSlider{{widget_dom_id}}.refresh();
    });
}

if ({{within_group}}) {
  tbApp.init{{widget_dom_id}}();
  tbApp.exec{{widget_dom_id}}();
}

</script>
