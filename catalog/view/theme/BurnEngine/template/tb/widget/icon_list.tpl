<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>

<ul class="tb_icon_list<?php echo $listing_classes; ?>">
  <?php foreach ($rows as $row): ?><li>
    <div class="tb_icon_wrap" style="<?php echo $widget->generateIconWrapStyles($row); ?>">
      <<?php echo $row['tag'] . $row['url_html']; ?> class="tb_icon tb_style_<?php echo $row['icon_style']; ?> <?php echo $row['glyph_value']; ?>" style="<?php echo $widget->generateIconStyles($row); ?>"></<?php echo $row['tag']; ?>>
    </div><?php if ($row['text']): ?><div class="tb_description_wrap<?php echo $description_classes; ?>" style="<?php echo $widget->generateDescStyles($row); ?>">
      <div class="tb_description tb_text_wrap"><?php echo $row['text']; ?></div>
    </div>
    <?php endif; ?>
  </li><?php if ($icons_align == 'justify') echo ' '; ?><?php endforeach; ?>
</ul>

<?php if ($description_position == 'tooltip' || ($display == 'grid' && (!empty($within_group) || !$tbData->optimize_js_load))): ?>
<script type="text/javascript">
tbApp.exec<?php echo $widget->getDomId(); ?> = function() {
    tbApp.onScriptLoaded(function() {

        <?php // Tooltip ?>
        <?php if ($description_position == 'tooltip'): ?>
        $('#<?php echo $widget_dom_id; ?> .tb_icon_wrap').each(function() {
            if ($(this).next('.tb_description_wrap').length) {
                var tooltip  = $(this).next('.tb_description_wrap').find('.tb_description').html(),
                    template = '<div class="ui-tooltip ui-widget-content">' +
                               '  <div class="tooltip-inner"></div>' +
                               '</div>';

                $(this).tooltip({
                    placement: 'auto top',
                    title: tooltip,
                    html: true,
                    template: template
                });
            }
        });
        <?php endif; ?>

        <?php // Grid size ?>
        <?php if ($display == 'grid' && (!empty($within_group) || (!$tbData->optimize_js_load && !$tbData->system['js_lazyload']))): ?>
        adjustItemSize('#<?php echo $widget->getDomId(); ?>', <?php echo $restrictions_json; ?>);
        <?php endif; ?>
    });
};

<?php if (empty($within_group)): ?>
<?php if (!$tbData->system['js_lazyload']): ?>
tbApp.exec<?php echo $widget->getDomId(); ?>();
<?php else: ?>
$(document).on('lazybeforeunveil', function(e) {
    if ($(e.target).filter('#<?php echo $widget->getDomId(); ?>').length) {
        tbApp.exec<?php echo $widget->getDomId(); ?>();
    }
});
<?php endif; ?>
<?php endif; ?>
</script>
<?php endif; ?>

<?php if ($display == 'grid' && empty($within_group) && ($tbData->optimize_js_load || $tbData->system['js_lazyload'])): ?>
<script type="text/javascript" data-critical="1">
adjustItemSize('#<?php echo $widget->getDomId(); ?>', <?php echo $restrictions_json; ?>);
</script>
<?php endif; ?>
