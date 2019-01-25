<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>

<div class="tb_map_wrap" style="height: <?php echo $height; ?>px;">
  <?php if ($map_code === false): ?>
  <span class="tb_loading_wrap" style="margin-top: <?php echo $height * 0.5 - 8; ?>px;"><span class="wait"></span></span>
  <?php endif; ?>
  <div class="tb_map_holder">
    <div class="tb_map">
      <?php if ($map_code !== false): ?>
      <?php echo $map_code; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script type="text/javascript">
tbApp.onScriptLoaded(function() {
    var width = $('#<?php echo $widget->getDomId(); ?>').outerWidth();

    var loadMap = function() {
        <?php if ($map_code === false): ?>
        var iframeEl = document.createElement("iframe");

        <?php foreach ($iframe_attributes as $key => $value): ?>
        <?php if ($key == 'style' && TB_RequestHelper::lteIe9()) continue; ?>
        iframeEl.<?php echo $key; ?> = "<?php echo $value; ?>";
        <?php endforeach; ?>
        iframeEl.onload = function () {
            $("#<?php echo $widget->getDomId(); ?> .tb_map_wrap").find("> .tb_loading_wrap").remove();
        };
        $("#<?php echo $widget->getDomId(); ?> .tb_map").append(iframeEl);
        <?php endif; ?>
        tbUtils.onSizeChange(function() {
            var temp_width = $('#<?php echo $widget->getDomId(); ?>').outerWidth();
            if (temp_width != width) {
                var map_iframe = document.getElementById('<?php echo $widget->getDomId(); ?>').getElementsByTagName('iframe');
                map_iframe[0].src = map_iframe[0].src;
                width = temp_width;
            }
        });
    };

    <?php if ($tbData->system['js_lazyload']): ?>
    $(document).on('lazybeforeunveil', function(e) {
        if ($(e.target).filter('#<?php echo $widget->getDomId(); ?>').length) {
            loadMap();
        }
    })
    <?php else: ?>
    loadMap();
    <?php endif; ?>
});
</script>
