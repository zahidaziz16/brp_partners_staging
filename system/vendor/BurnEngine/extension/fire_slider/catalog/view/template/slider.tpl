<?php if ($slider['layout'] != 'fullscreen'): ?>
<div class="fire_slider_container <?php echo $slider['css_class']; ?>">
<div class="fire_slider_wrap_outer <?php echo $slider['css_class']; ?>">
<div class="fire_slider_wrap_inner <?php echo $slider['css_class']; ?>">
<?php endif; ?>
  <div class="mightySlider mightyslider_custom_skin <?php echo $slider['css_class']; ?> tbLoading<?php if ($tbData->system['js_lazyload']): ?> lazyload" data-expand="<?php echo $tbData->system['js_lazyload_expand']; ?><?php endif; ?>">
    <div class="frame"<?php echo $slider['frame_attributes']; ?>>
      <div class="slideelement">
        <?php $slide_num = 1; foreach ($slider['slides'] as $slide): ?>
        <div <?php echo str_replace('{{uid}}', $uid, $slide['attributes']); ?>>
          <?php if ($slide['link_url']): ?>
          <a href="<?php echo $slide['link_url']; ?>" target="<?php echo $slide['link_target']; ?>">
          <?php endif; ?>
          <?php if (!empty($slide['layers'])): ?>
          <div class="ms_scene">
            <?php $layer_num = 1; foreach ($slide['layers'] as $layer): if ($layer['background_origin'] == 'content'): ?>
            <div <?php echo str_replace('{{uid}}', $uid, $layer['attributes']); ?>>
              <?php if ($layer['link_url']): ?>
              <a href="<?php echo $layer['link_url']; ?>" target="<?php echo $layer['link_target']; ?>">
              <?php endif; ?>
              <?php if ($layer['type'] == 'html'): ?>
              <?php echo $layer['content']; ?>
              <?php else: ?>
              <img src="<?php echo $layer['image_src']; ?>"<?php if ($layer['image_width']) echo ' width="' . $layer['image_width'] . '"'; if ($layer['image_height']) echo ' height="' . $layer['image_height'] . '"'; ?> alt="" />
              <?php endif; ?>
              <?php if ($layer['link_url']): ?>
              </a>
              <?php endif; ?>
            </div>
            <?php $layer_num ++; endif; endforeach; ?>
          </div>
          <?php $layer_num = 1; foreach ($slide['layers'] as $layer): if ($layer['background_origin'] == 'row'): ?>
          <div <?php echo str_replace('{{uid}}', $uid, $layer['attributes']); ?>>
            <?php if ($layer['link_url']): ?>
            <a href="<?php echo $layer['link_url']; ?>" target="<?php echo $layer['link_target']; ?>">
            <?php endif; ?>
            <?php if ($layer['type'] == 'html'): ?>
            <?php echo $layer['content']; ?>
            <?php else: ?>
            <img src="<?php echo $layer['image_src']; ?>"<?php if ($layer['image_width']) echo ' width="' . $layer['image_width'] . '"'; if ($layer['image_height']) echo ' height="' . $layer['image_height'] . '"'; ?> alt="" />
            <?php endif; ?>
            <?php if ($layer['link_url']): ?>
            </a>
            <?php endif; ?>
          </div>
          <?php $layer_num ++; endif; endforeach; ?>
          <?php endif; ?>
          <?php if ($slide['link_url']): ?>
          </a>
          <?php endif; ?>
        </div>
        <?php $slide_num ++; endforeach; ?>
      </div>
    </div>
  </div>
<?php if ($slider['layout'] != 'fullscreen'): ?>
</div>
</div>
</div>
<?php endif; ?>

<?php // FULLSCREEN SLIDER ?>

<?php if ($slider['layout'] == 'fullscreen'): ?>
<script type="text/javascript" data-critical="1">
window.tbApp = window.tbApp || {};
tbApp.slider = {};
tbApp.slider.body           = document.getElementsByTagName('body')[0];
tbApp.slider.wrapper        = document.getElementById('wrapper');
tbApp.slider.header         = document.getElementById('header');
tbApp.slider.row            = document.getElementById('<?php echo $uid; ?>').parentNode.parentNode.parentNode;
tbApp.slider.content        = document.getElementById('content');
tbApp.slider.content_height = document.documentElement.clientHeight - tbApp.slider.header.offsetHeight;
tbApp.slider.container_old  = document.getElementById('<?php echo $uid; ?>');
tbApp.slider.container      = document.createElement('DIV');
tbApp.slider.margin_left    = window.getComputedStyle(tbApp.slider.container_old).marginLeft;
tbApp.slider.margin_right   = window.getComputedStyle(tbApp.slider.container_old).marginRight;

tbApp.slider.container.id        = '<?php echo $uid; ?>';
tbApp.slider.container.classes   = tbApp.slider.container_old.className;
tbApp.slider.container.innerHTML = document.getElementById('<?php echo $uid; ?>').innerHTML;
document.getElementById('<?php echo $uid; ?>').outerHTML = '';

<?php if ($slider['header_style'] == 'visible'): ?>
tbApp.slider.wrapper.insertBefore(tbApp.slider.container, tbApp.slider.content);
document.getElementById('<?php echo $uid; ?>').className = tbApp.slider.container.classes;
document.getElementById('<?php echo $uid; ?>').style.height = (tbApp.slider.content_height - tbApp.slider.header.getBoundingClientRect().top) + 'px';
document.getElementById('<?php echo $uid; ?>').style.marginBottom = window.getComputedStyle(tbApp.slider.header).marginBottom;
document.getElementById('<?php echo $uid; ?>').style.marginLeft  = tbApp.slider.margin_left;
document.getElementById('<?php echo $uid; ?>').style.marginRight = tbApp.slider.margin_right;
tbApp.slider.header.className += ' tb_header_visible';
<?php endif; ?>

<?php if ($slider['header_style'] == 'hidden'): ?>
tbApp.slider.body.insertBefore(tbApp.slider.container, tbApp.slider.body.childNodes[0]);
document.getElementById('<?php echo $uid; ?>').className = tbApp.slider.container.classes;
document.getElementById('<?php echo $uid; ?>').style.height = document.documentElement.clientHeight + 'px';
tbApp.slider.header.className += ' tb_header_hidden';
<?php endif; ?>

<?php if ($slider['header_style'] == 'overlay'): ?>
tbApp.slider.body.insertBefore(tbApp.slider.container, tbApp.slider.body.childNodes[0]);
document.getElementById('<?php echo $uid; ?>').className = tbApp.slider.container.classes;
document.getElementById('<?php echo $uid; ?>').style.height      = document.documentElement.clientHeight + 'px';
document.getElementById('<?php echo $uid; ?>').style.marginLeft  = tbApp.slider.margin_left;
document.getElementById('<?php echo $uid; ?>').style.marginRight = tbApp.slider.margin_right;
tbApp.slider.header.className += ' tb_header_overlay';
<?php if ($slider['transparent_header']): ?>
tbApp.slider.header.className += ' tb_header_transparent';
<?php endif; ?>
<?php endif; ?>

tbApp.slider.row.outerHTML = '';
</script>
<?php endif; ?>

<script type="text/javascript" data-prepend="1">
init_slider_<?php echo $uid; ?> = function() {
    tbApp.onScriptLoaded(function() {
        setTimeout(function() {

            <?php // SLIDER INIT ?>

            var $slider = new mightySlider(
                '#<?php echo $uid; ?> .mightySlider .frame',
                {
                    speed:       <?php echo $slider['speed']; ?>,
                    easing:      '<?php echo $slider['easing']; ?>',
                    startRandom: <?php echo $slider['random']; ?>,
                    viewport:    '<?php echo $slider['viewport']; ?>',
                    autoScale:   <?php echo $slider['layout'] == 'fullwidth' || $slider['layout'] == 'fullscreen' ? '0' : $slider['autoscale']; ?>,
                    navigation: {
                        slideSize:      '100%',
                        keyboardNavBy:  'slides'
                    },
                    <?php if ($slide_num > 2): ?>
                    commands: {
                        pages: 1,
                        buttons: 1
                    },
                    pages: {
                      activateOn: 'click'
                    },
                    dragging: {
                        releaseSwing: 1,
                        swingSync:     5,
                        swingSpeed:    0.2
                    },
                    <?php else: ?>
                    dragging: {
                        mouseDragging: 0,
                        touchDragging: 0,
                    },
                    <?php endif; ?>
                    <?php if ($slider['autoplay']): ?>
                    cycling: {
                        cycleBy: 'slides',
                        pauseTime: <?php echo $slider['pause_time']; ?>,
                        pauseOnHover: <?php echo $slider['hover_pause']; ?>
                    },
                    <?php endif; ?>
                    classes: {
                      loaderClass:   'tb_loading_bar'
                    }
                }
            ).init();

            <?php $fist_slide = reset($slider['slides']);
            if ($fist_slide['cover']): ?>
            $slider.one('coverInserted', function (eventName) {
                setTimeout(function(){
                  $('#<?php echo $uid; ?> .mightySlider').removeClass('tbLoading');
                }, 10);
            });
            <?php else: ?>
            setTimeout(function(){
              $('#<?php echo $uid; ?> .mightySlider').removeClass('tbLoading');
            }, 10);
            <?php endif; ?>

            <?php // FULLWIDTH SLIDER ?>

            <?php if ($slider['layout'] == 'fullwidth' && $slider['autoscale']): ?>
            tbUtils.onSizeChange(function() {
              fireslider_fullwidth_resize($slider, <?php echo $slider['responsive_width']; ?>)
            }, false, false, '#<?php echo $uid; ?>');
            fireslider_fullwidth_resize($slider, <?php echo $slider['responsive_width']; ?>)
            <?php endif; ?>

            <?php // FULLSCREEN SLIDER ?>

            <?php if ($slider['layout'] == 'fullscreen'): ?>
            tbUtils.onSizeChange(function() {
                <?php if ($slider['header_style'] == 'visible'): ?>
                var slider_height = $(window).height() - $('#header').outerHeight();
                <?php endif; ?>
                <?php if ($slider['header_style'] != 'visible'): ?>
                var slider_height = $(window).height();
                <?php endif; ?>

                $('#<?php echo $uid; ?>').height(slider_height);
                fireslider_fullscreen_resize($slider, $('#<?php echo $uid; ?>'), <?php echo $slider['responsive_width']; ?>, <?php echo $slider['responsive_height']; ?>, <?php echo $slider['offset_top']; ?>, <?php echo $slider['offset_left']; ?>)
            }, false, false, '#<?php echo $uid; ?>');

            fireslider_fullscreen_resize($slider, $('#<?php echo $uid; ?>'), <?php echo $slider['responsive_width']; ?>, <?php echo $slider['responsive_height']; ?>, <?php echo $slider['offset_top']; ?>, <?php echo $slider['offset_left']; ?>)

            <?php endif; ?>

        }, 250);
    });
};

<?php if (!$tbData->system['js_lazyload']): ?>
init_slider_<?php echo $uid; ?>();
<?php else: ?>
$(document).on('lazybeforeunveil', function(e) {
    if ($(e.target).filter('#<?php echo $uid; ?> .mightySlider').length) {
        init_slider_<?php echo $uid; ?>();
    }
});
<?php endif; ?>
</script>