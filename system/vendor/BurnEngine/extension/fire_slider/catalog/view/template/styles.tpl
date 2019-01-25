<?php
$slide    = reset($slider['slides']);
$viewport = !empty($slide['viewport']) ? $slide['viewport'] : $slider['viewport'];
$viewport = $viewport == 'fill'    ? 'cover'     : $viewport;
$viewport = $viewport == 'fit'     ? 'contain'   : $viewport;
$viewport = $viewport == 'stretch' ? '100% 100%' : $viewport;
$viewport = $viewport == 'center'  ? 'auto'      : $viewport;
?>

<?php // LOADING BACKGROUND ?>

#<?php echo $uid; ?> .mightySlider.tbLoading .frame {
  background: <?php echo (!empty($slide['bg_color']) ? $slide['bg_color'] : ''); ?> url('<?php echo $slide['cover']; ?>') <?php echo $slide['background_repeat'] ? 'repeat' : 'no-repeat'; ?> center / <?php echo $viewport; ?>;
  <?php if (!empty($slide['kenburns']) && $slide['kenburns_animation'] == 'zoomOut'): ?>
  -webkit-transform: scale(<?php echo $slide['kenburns_zoom'] / 100; ?>);
          transform: scale(<?php echo $slide['kenburns_zoom'] / 100; ?>);
  -webkit-transform-origin: <?php echo $slide['kenburns_origin']; ?>;
          transform-origin: <?php echo $slide['kenburns_origin']; ?>;
  <?php endif; ?>
}

<?php // FIXED WIDTH RESPONSIVE ?>

<?php if ($slider['layout'] == 'fixed' && $slider['autoscale']): ?>
#<?php echo $uid; ?> .fire_slider_wrap_outer {
  max-width: <?php echo $slider['width']; ?>px;
}
#<?php echo $uid; ?> .fire_slider_wrap_inner {
  padding-top: <?php echo ($slider['height'] / $slider['width']) * 100; ?>%;
}
<?php endif; ?>

<?php // FIXED WIDTH STATIC ?>

<?php if ($slider['layout'] == 'fixed' && !$slider['autoscale']): ?>
#<?php echo $uid; ?> .mightySlider,
#<?php echo $uid; ?> .mightySlider .frame
{
  height: <?php echo $slider['height']; ?>px;
}
#<?php echo $uid; ?> .mightySlider {
  position: static;
}
<?php endif; ?>

<?php // FULLWIDTH SLIDER ?>

<?php if ($slider['layout'] == 'fullwidth'): ?>
#<?php echo $uid; ?> .fire_slider_wrap_outer {
  max-width: <?php echo $slider['responsive_width']; ?>px;
}
#<?php echo $uid; ?> .fire_slider_wrap_inner {
  padding-top: <?php echo ($slider['height'] / $slider['responsive_width']) * 100; ?>%;
}
#<?php echo $uid; ?> .mightySlider .ms_scene {
  width:   <?php echo $slider['responsive_width']; ?>px;
}
#<?php echo $uid; ?> .mightySlider.fullwidth:not(.scaled) .frame,
#<?php echo $uid; ?> .mightySlider.fullwidth:not(.scaled) + .tb_placeholder
{
  height: <?php echo $slider['height']; ?>px;
}
<?php endif; ?>

<?php // FULLSCREEN SLIDER ?>

<?php if ($slider['layout'] == 'fullscreen'): ?>
<?php $scene_width  = !empty($slider['scene_width'])  ? $slider['scene_width']  : $slider['responsive_width'];  ?>
<?php $scene_height = !empty($slider['scene_height']) ? $slider['scene_height'] : $slider['responsive_height']; ?>
#<?php echo $uid; ?> .mightySlider .ms_scene {
  width:   <?php echo $scene_width; ?>px;
  height:  <?php echo $scene_height; ?>px;
}
#<?php echo $uid; ?> {
  <?php if (!empty($slider['min_height'])): ?>
  min-height: <?php echo $slider['min_height']; ?>px;
  <?php endif; ?>
  <?php if (!empty($slider['max_height'])): ?>
  max-height: <?php echo $slider['max_height']; ?>px;
  <?php endif; ?>
}
<?php endif; ?>

<?php // MANUAL SCALING ?>

<?php if (!$slider['autoscale']): ?>
<?php foreach ($slider['sizes'] as $size): ?>
@media only screen and (max-width: <?php echo $size['max_width']; ?>px) {
  #<?php echo $uid; ?> .mightySlider,
  #<?php echo $uid; ?> .mightySlider .frame
  {
    height: <?php echo $size['height']; ?>px;
  }
}
<?php endforeach; ?>
<?php endif; ?>

<?php // SLIDES STYLES ?>

<?php $slide_num = 1; foreach ($slider['slides'] as $slide): ?>
<?php if(!empty($slide['bg_color'])): ?>
#<?php echo $uid . '_slide_' . $slide_num; ?> {
  background-color: <?php echo $slide['bg_color']; ?>;
}
<?php endif; ?>
<?php if (!empty($slide['background_repeat']) || !empty($slide['kenburns'])): ?>
.mightySlider.tbLoading #<?php echo $uid . '_slide_' . $slide_num; ?> .mSCover {
    opacity: 0;
}
#<?php echo $uid . '_slide_' . $slide_num; ?> .mSCover {
  <?php if (!empty($slide['background_repeat'])): ?>
  background-repeat: repeat;
  <?php endif; ?>
  <?php if (!empty($slide['kenburns'])): ?>
  <?php if ($slide['kenburns_animation'] == 'zoomIn'): ?>
  -webkit-transform: scale(1);
          transform: scale(1);
  <?php endif; ?>
  <?php if ($slide['kenburns_animation'] == 'zoomOut'): ?>
  -webkit-transform: scale(<?php echo $slide['kenburns_zoom'] / 100; ?>);
          transform: scale(<?php echo $slide['kenburns_zoom'] / 100; ?>);
  <?php endif; ?>
  -webkit-transition: transform 0s;
          transition: transform 0s;
  -webkit-transition-delay: 1s;
          transition-delay: 1s;
  -webkit-transform-origin: <?php echo $slide['kenburns_origin']; ?>;
          transform-origin: <?php echo $slide['kenburns_origin']; ?>;
  <?php endif; ?>
}
<?php endif; ?>
<?php if (!empty($slide['kenburns'])): ?>
.mightySlider:not(.tbLoading) #<?php echo $uid . '_slide_' . $slide_num; ?>.active .mSCover {
  -webkit-transition: transform <?php echo $slide['kenburns_timing']; ?>s;
          transition: transform <?php echo $slide['kenburns_timing']; ?>s;
  -webkit-transition-delay: 1s;
          transition-delay: 1s;
  <?php if ($slide['kenburns_animation'] == 'zoomIn'): ?>
  -webkit-transform: scale(<?php echo $slide['kenburns_zoom'] / 100; ?>);
          transform: scale(<?php echo $slide['kenburns_zoom'] / 100; ?>);
  <?php endif; ?>
  <?php if ($slide['kenburns_animation'] == 'zoomOut'): ?>
  -webkit-transform: scale(1);
          transform: scale(1);
  <?php endif; ?>
}
<?php endif; ?>
<?php $layer_num = 1; foreach ($slide['layers'] as $layer): ?>
<?php if ($layer['css_styles']): ?>
#<?php echo $uid . '_slide_' . $slide_num . '_layer_' . $layer_num; ?> {
  <?php if (!$layer['html_width']) echo ' width: auto !important;'; ?>
  <?php echo $layer['css_styles']; ?>
}
#<?php echo $uid . '_slide_' . $slide_num . '_layer_' . $layer_num; ?> * {
  opacity: 1;
}
<?php endif; ?>
<?php $layer_num ++; endforeach; ?>
<?php $slide_num ++; endforeach; ?>

<?php echo $slider['custom_css']; ?>
