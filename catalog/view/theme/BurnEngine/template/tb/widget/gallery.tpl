<?php if ($gallery_type == 'slide'): ?>
<style scoped>
<?php if ($nav_position == 'bottom' || $nav_style != 'thumbs'): ?>
#<?php echo $widget->getDomId(); ?> > .tb_gallery { padding-top: <?php echo $ratio_plus; ?>; }
#<?php echo $widget->getDomId(); ?> > .tb_gallery > .tb_slides { margin-top: <?php echo $ratio_minus; ?>; }
<?php else: ?>
#<?php echo $widget->getDomId(); ?> > .tb_gallery { padding-top: <?php echo $ratio_plus; ?>; }
<?php endif; ?>
</style>
<?php endif; ?>

<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>

<?php // Slide gallery ?>
<?php if ($gallery_type == 'slide'): ?>
<div class="tb_gallery<?php echo $gallery_css_classes; ?>">

  <div class="tb_slides">
    <?php // Fullscreen button ?>
    <?php if ($fullscreen): ?>
    <a href="javascript:;" class="tb_fullscreen_button btn btn-<?php echo $fullscreen_button_size; ?> tb_no_text tbGoFullscreen">
      <i class="<?php echo $fullscreen_button_icon; ?>" style="font-size: <?php echo $fullscreen_button_icon_size; ?>px;"></i>
    </a>
    <?php endif; ?>

    <?php // Slides ?>
    <div class="frame" data-mightyslider="width: <?php echo $slide_width; ?>, height: <?php echo $slide_height; ?>">
      <div>
        <?php foreach ($images as $image): ?>
        <div data-mightyslider="
          type:        'image',
          cover:       '<?php echo $image['slide']['src']; ?>'
          <?php if ($nav && $nav_style == 'thumbs'): ?>
          ,thumbnail:  '<?php echo $image['thumb']['src']; ?>'
          <?php if (!$crop_thumbs): ?>
          ,thumb_size: <?php echo $nav_position == 'bottom' ? $image['thumb']['width'] : $image['thumb']['height']; ?>
          <?php endif; ?>
          <?php endif; ?>
          <?php if (!empty($image['link']['url'])): ?>
          ,icon:  'link'
          ,link: {
            url:    '<?php echo $image['link']['url']; ?>',
            target: '<?php echo $image['link']['url_target']; ?>'
          }
          <?php endif; ?>
        ">
          <?php if (!empty($image['caption'])): ?>
          <div class="tb_caption mSCaption" data-msanimation="{delay: 200, speed: 300, style: {opacity: 1}}">
            <div class="tb_text">
              <?php echo $image['caption']; ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <?php if ($images && $nav && $nav_style == 'thumbs'): ?>
  <div class="tb_thumbs_wrap">
    <div class="tb_thumbs">
      <div class="has_slider">
        <ul<?php if ($crop_thumbs && $nav_position == 'bottom'): ?> class="tb_listing tb_grid_view tb_size_<?php echo $nav_thumbs_num; ?>"<?php endif; ?>></ul>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($images && $nav && $nav_style != 'thumbs'): ?>
  <ul class="tb_pagination mSPages">
  </ul>
  <?php endif; ?>

</div>
<?php endif; ?>

<?php // Grid gallery ?>
<?php if ($gallery_type == 'grid'): ?>
<div class="tb_gallery tb_<?php echo $gallery_type; ?>_view">
  <?php foreach ($images as $image): ?>
  <div>
    <?php if ($fullscreen): ?>
    <a href="<?php echo $image['full']['src']; ?>">
    <?php endif; ?>
      <span class="image-holder" style="max-width: <?php echo $image['thumb']['width']; ?>px;">
      <span style="padding-top: <?php echo round($image['thumb']['height'] / $image['thumb']['width'], 4) * 100; ?>%">
        <img
          <?php if (!$tbData->system['image_lazyload']): ?>
          src="<?php echo $image['thumb']['src']; ?>"
          <?php else: ?>
          src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif"
          data-src="<?php echo $image['thumb']['src']; ?>"
          class="lazyload"
          <?php endif; ?>
          width="<?php echo $image['thumb']['width']; ?>"
          height="<?php echo $image['thumb']['height']; ?>"
          alt="<?php if (!empty($image['caption'])) echo $image['caption']; ?>"
          style="margin-top: -<?php echo round($image['thumb']['height'] / $image['thumb']['width'], 4) * 100; ?>%;" />
        />
      </span>
      </span>
      <?php if ($gallery_type == 'grid' && $fullscreen): ?>
      <span class="tb_icon fa-camera"></span>
      <?php endif; ?>
    <?php if ($fullscreen): ?>
    </a>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<script type="text/javascript">
tbApp.exec<?php echo $widget->getDomId(); ?> = function exec<?php echo $widget->getDomId(); ?>() {
    tbApp.onScriptLoaded(function() {

        <?php // Slide gallery ?>
        <?php if ($gallery_type == 'slide'): ?>
        var $slider = new mightySlider('#<?php echo $widget->getDomId(); ?> .frame', {
            speed:             500,
            easing:            'easeOutExpo',
            viewport:          'fit',
            autoScale:         1,
            preloadMode:       'instant',
            navigation: {
                slideSize:     '100%',
                keyboardNavBy: 'slides'
            },
            commands: {
                thumbnails:    <?php echo $nav && $nav_style == 'thumbs' && $images > 1 ? 1 : 0; ?>,
                pages:         <?php echo $nav && $nav_style != 'thumbs' && $images ? 1 : 0; ?>,
                buttons:       <?php echo $nav && $nav_buttons ? 1 : 0; ?>
            },
            <?php if ($nav && $nav_style != 'thumbs'): ?>
            pages: {
                pagesBar:      '#<?php echo $widget->getDomId(); ?> .tb_pagination',
                activateOn:    'click'
            },
            <?php endif; ?>
            dragging: {
                swingSync:     5,
                swingSpeed:    0.2
            },
            <?php if ($nav && $nav_style == 'thumbs'): ?>
            thumbnails: {
                thumbnailsBar:     '#<?php echo $widget->getDomId(); ?> .tb_thumbs ul',
                thumbnailsButtons: 0,
                horizontal:        <?php echo $nav_position == 'bottom' ? 1 : 0; ?>,
                thumbnailNav:      'centered',
                thumbnailSize:     <?php echo $crop_thumbs ? '\'' . (100 / $nav_thumbs_num) . '%\'' : 0; ?>
            },
            <?php endif; ?>
            classes: {
                loaderClass:   'tb_loading_bar'
            }
        });

        var $vertical_thumbs = $('#<?php echo $widget->getDomId(); ?>').find('.tb_thumbs_vertical .tb_thumbs');

        $slider.one('coverLoaded', function (eventName) {
            $('#<?php echo $widget->getDomId(); ?> .tb_thumbs ul').removeClass('tb_grid_view tb_size_1 tb_size_2 tb_size_3 tb_size_4 tb_size_5 tb_size_6 tb_size_7 tb_size_8');
        });

        $slider.init();
        if (!$vertical_thumbs.length) {
            $('#<?php echo $widget->getDomId(); ?> .tb_thumbs').find('li').each(function(index) {
                var thumb = document.querySelectorAll('.tb_thumbs li')[index].querySelector('img').getBoundingClientRect();

                $(this).width(thumb.width);
            });
            $slider.reload();
        }

        <?php if ($fullscreen): ?>
        $('#<?php echo $widget->getDomId(); ?> .tbGoFullscreen').bind('click', function(){
            lightbox_gallery('<?php echo $widget->getDomId(); ?>', $slider, false, <?php echo $images_array; ?>);

            return false;
        });
        <?php endif; ?>

        <?php // Grid gallery ?>
        <?php else: ?>
        <?php if ($fullscreen): ?>
        $('#<?php echo $widget->getDomId(); ?> .tb_gallery.tb_grid_view a').bind('click', function(){
            lightbox_gallery('<?php echo $widget->getDomId(); ?>', false, $(this).parent().index(), <?php echo $images_array; ?>);

            return false;
        });
        <?php endif; ?>

        <?php if (!empty($within_group) || (!$tbData->optimize_js_load && !$tbData->system['js_lazyload'])): ?>
        adjustItemSize('#<?php echo $widget->getDomId(); ?>', <?php echo $restrictions_json; ?>);
        <?php endif; ?>
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

<?php if ($gallery_type == 'grid' && empty($within_group) && ($tbData->optimize_js_load || $tbData->system['js_lazyload'])): ?>
<script type="text/javascript" data-critical="1">
adjustItemSize('#<?php echo $widget->getDomId(); ?>', <?php echo $restrictions_json; ?>);
</script>
<?php endif; ?>