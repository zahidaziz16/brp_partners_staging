<?php $profile_border ? $border_offset = 10 : $border_offset = 0; ?>
<?php if ($like_box_style == 'custom'): ?>
<style scoped>
#<?php echo $widget->getDomId(); ?> ul.uiList {
  <?php if ($profile_name): ?>
  max-height: <?php echo ($tbData->fonts['body']['line-height'] * 1.5 + 50 + $border_offset) * $profiles_rows; ?>px;
  <?php else: ?>
  max-height: <?php echo ($tbData->fonts['body']['line-height'] + 50 + $border_offset) * $profiles_rows; ?>px;
  <?php endif; ?>
}
</style>
<?php endif; ?>

<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>
<?php if ($like_box_style == 'custom'): ?>
<div class="tb_fb_likebox<?php echo $like_box_classes; ?> tb_social_box tb_custom">
  <div class="tb_fb_like tb_social_button">
    <div class="fb-like" data-href="<?php echo $page_url; ?>" data-layout="<?php echo $like_button_style; ?>" data-action="like" data-show-faces="false" data-share="false"></div>
  </div>
  <?php echo $content; ?>
</div>
<?php else: ?>
<div class="tb_fb_likebox tb_social_box tb_default<?php if ($default_small_header): ?> tb_small_header<?php endif; ?>">
  <div class="tb_social_box_wrap">
    <div class="fb-page" data-href="<?php echo $page_url; ?>" data-small-header="<?php echo $default_small_header; ?>" data-width="500" data-adapt-container-width="true" data-hide-cover="<?php echo $default_hide_cover; ?>" data-show-facepile="true" data-show-posts="false"></div>
  </div>
</div>
<?php endif; ?>

<?php if ($tbData->system['js_lazyload']): ?>
<script>
tbApp.onScriptLoaded(function() {
    $(document).on('lazybeforeunveil', function(e) {
        if ($(e.target).filter('#<?php echo $widget->getDomId(); ?>').length) {
            var parseFBXML = function() {
                FB.XFBML.parse(document.getElementById('<?php echo $widget->getDomId(); ?>'));
            };
            if (window.FB_XFBML_parsed === undefined) {
                window.FB_XFBML_parsed = parseFBXML;
            } else {
                parseFBXML();
            }
        }
    });
});
</script>
<?php endif; ?>