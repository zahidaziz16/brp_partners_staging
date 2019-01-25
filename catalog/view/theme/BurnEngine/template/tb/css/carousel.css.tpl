.tb_slider {
  overflow: hidden;
  position: static;
  display: block !important;
  float: none;
  margin: -60px 0 !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
  padding-top: 60px;
  padding-bottom: 60px;
  direction: ltr;
  -webkit-transition: height 0.5s ease-out;
  transition: height 0.5s ease-out;
}
.swiper-container {
  overflow: visible;
  display: block !important;
  float: none !important;
  width: auto !important;
  padding: 0 !important;
  contain: layout;
}
.swiper-container:before {
  content: none !important;
}
.swiper-container > .swiper-wrapper {
  box-sizing: content-box;
}
.swiper-container,
.swiper-container > .swiper-wrapper,
.swiper-container > .swiper-wrapper > .swiper-slide
{
  max-width: none !important;
  height: auto !important;
}
.swiper-container > .swiper-wrapper > .swiper-slide {
  direction: <?php echo $lang_dir; ?>;
  width: auto !important;
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
}
.swiper-container > .swiper-wrapper > .swiper-slide > * {
  width: auto;
  height: 100%;
  max-width: none;
  min-width: 0;
  padding: 0;
}
.tb_slider_controls {
  z-index: 40;
  position: absolute;
  top: 0;
  direction: ltr;
}
.tb_slider_controls a {
  position: relative;
  display: inline-block;
  width: 20px;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 0.25; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 0.25; ?>px;
  <?php endif; ?>
  line-height : inherit;
  text-align: center;
  cursor: pointer;
  outline: none !important;
  vertical-align: top;
}
.tb_slider_controls a:before {
  content: '>';
  position: relative;
  z-index: 1;
  display: block;
  opacity: 0;
}
.tb_slider_controls a.tb_prev {
  -webkit-transform: rotate(180deg);
          transform: rotate(180deg);
}
.tb_slider_controls svg {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 24px;
  height: 24px;
  max-width: none;
  max-height: none;
  margin: -12px 0 0 -12px;
}
.tb_slider_controls .tb_disabled {
  opacity: 0.5;
}
.has_slider.tb_side_nav .tb_grid_view.tb_style_bordered.tb_multiline,
:not(.panel-body) > .has_slider.no_title .tb_grid_view.tb_multiline:not(.tb_slider),
.tb_side_nav .tb_slider
{
  margin-left:  <?php echo $base * 2; ?>px !important;
  margin-right: <?php echo $base * 2; ?>px !important;
}
<?php foreach ($grid_gutter as $gutter): ?>
.has_slider.tb_side_nav .tb_grid_view.tb_gut_<?php echo $gutter; ?>.tb_multiline:not(.tb_slider) {
  margin-left:  <?php echo $base * 2 - $gutter; ?>px !important;
  margin-right: <?php echo $base * 2 - $gutter; ?>px !important;
}
<?php endforeach; ?>

.has_slider .tb_grid_view.tb_multiline > * {
  margin-bottom: 0;
}

.tb_side_nav .tb_slider_controls {
  position: static;
  visibility: hidden;
}
.tb_side_nav .tb_slider_controls a {
  position: absolute;
  margin-top: -<?php echo $base; ?>px;
  margin-left: 0;
  margin-right: 0;
}
.tb_slider_controls .tb_prev { left: -3px; }
.tb_slider_controls .tb_next { right: -3px; }


.tb_slider_pagination {
  overflow: hidden;
  text-align: center;
  min-height: <?php echo $base; ?>px;
  margin: <?php echo $base; ?>px 0 0 0;
}
.tb_slider_pagination.tb_compact {
  margin: <?php echo $base * 0.5; ?>px 0 0 0;
}
.tb_slider_pagination span {
  position: relative;
  z-index: 20;
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  cursor: pointer;
  background: #e3e3e3;
}
.tb_slider_pagination span:hover {
  background: #d9d9d9;
}
.tb_slider_pagination span + span {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base / 4 + 2; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base / 4 + 2; ?>px;
  <?php endif; ?>
}
.tb_slider_pagination span.tb_active {
  background: #bbb;
}
.tb_slider_pagination .swiper-active-switch {
  padding: 0;
}


.swiper-slide > * {
  float: none !important;
  width: auto !important;
  margin: 0 !important;
  vertical-align: top;
}
.tb_listing.tb_gut_10 > .swiper-container { margin-left: -10px; }
.tb_listing.tb_gut_20 > .swiper-container { margin-left: -20px; }
.tb_listing.tb_gut_30 > .swiper-container { margin-left: -30px; }
.tb_listing.tb_gut_40 > .swiper-container { margin-left: -40px; }
.tb_listing.tb_gut_50 > .swiper-container { margin-left: -50px; }
.tb_listing.tb_gut_10 .swiper-slide { padding-left: 10px; }
.tb_listing.tb_gut_20 .swiper-slide { padding-left: 20px; }
.tb_listing.tb_gut_30 .swiper-slide { padding-left: 30px; }
.tb_listing.tb_gut_40 .swiper-slide { padding-left: 40px; }
.tb_listing.tb_gut_50 .swiper-slide { padding-left: 50px; }

<?php for ($column = 1; $column <= $grid_block_columns; $column++): ?>
<?php $column_width = truncate_float(100 / $column, 8); ?>
.tb_listing.tb_size_<?php echo $column; ?> .swiper-slide { width: <?php echo $column_width; ?>%; }
<?php endfor; ?>

/*** Carousel Init ***/

.swiper-container.tb_slider_init > .swiper-wrapper,
.swiper-container.tb_slider_init > .swiper-wrapper > .swiper-slide,
.swiper-container.tb_slider_init > .swiper-wrapper > .swiper-slide > *
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
}
.swiper-container.tb_slider_init > .swiper-wrapper > .swiper-slide > * {
  width: 100% !important;
}
.swiper-container.tb_slider_init > .swiper-wrapper > .swiper-slide > * > * {
  max-width: 100%;
}

/*** Carousel Before Init ***/

<?php for ($column = 1; $column <= $grid_block_columns; $column++): ?>
.has_slider .tb_grid_view.tb_size_<?php echo $column; ?> > :nth-child(n+<?php echo $column + 1; ?>),
<?php endfor; ?>
.has_slider .tb_list_view    > :nth-child(n+2),
.has_slider .tb_compact_view > :nth-child(n+2)
{
  display: none !important;
}

/*** Carousel in tabs ***/

.tab-content .has_slider.no_title {
  position: static;
}

/*** Carousel overlay ***/

.has_slider {
  pointer-events: none;
}
.has_slider .tb_listing:not(.tb_slider),
.has_slider .product-thumb,
.has_slider .tb_item,
.has_slider .tb_item_info,
.swiper-container,
.tb_slider_controls *,
.tb_slider_pagination
{
  pointer-events: auto;
}

