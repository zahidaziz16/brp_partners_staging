/*  -----------------------------------------------------------------------------------------
    B O R D E R E D   L I S T I N G
-----------------------------------------------------------------------------------------  */

.tb_listing.tb_style_bordered {
  margin-bottom: 0;
}
.tb_listing.tb_style_bordered:first-child {
  border-top-width: 0;
}
.tb_listing.tb_style_bordered > * {
  margin-top: 0;
  margin-bottom: 0;
}
.tb_listing_options + .tb_listing.tb_style_bordered {
  border-top-width: 1px;
  border-top-style: solid;
}

/*  List view   -------------------------------------------------------------------------  */

.tb_listing.tb_list_view.tb_style_bordered > * {
  margin-bottom: 0;
  border-top-width: 1px;
  border-top-style: solid;
}
.tb_listing.tb_list_view.tb_style_bordered > :first-child {
  border-top: none;
}

/*  Grid view   -------------------------------------------------------------------------  */

.tb_listing.tb_grid_view.tb_style_bordered,
.tb_listing.tb_grid_view.tb_style_bordered > *
{
  margin-left: 0;
  margin-right: 0;
  padding-left: 0;
  padding-right: 0;
}
.tb_listing.tb_grid_view.tb_style_bordered {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: -1px;
  <?php else: ?>
  margin-left: -1px;
  <?php endif; ?>
}
.tb_listing.tb_grid_view.tb_style_bordered > * {
  margin-bottom: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  border-right-style: solid;
  border-right-width: 1px;
  <?php else: ?>
  border-left-style: solid;
  border-left-width: 1px;
  <?php endif; ?>
  border-bottom-style: solid;
  border-bottom-width: 1px;
}
.tb_listing.tb_grid_view.tb_style_bordered > *:before,
.tb_listing.tb_grid_view.tb_style_bordered > *:after
{
  content: '';
  position: absolute;
  display: block;
}
.tb_listing.tb_grid_view.tb_style_bordered > *:after
{
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  right: -1px;
  <?php else: ?>
  left: -1px;
  <?php endif; ?>
  width: 0;
  height: 100%;
  border-right-style: solid;
  border-right-width: 1px;
}
.tb_listing.tb_grid_view.tb_style_bordered > *:before
{
  bottom: -1px;
  left: 0;
  width: 100%;
  height: 0;
  border-bottom-style: solid;
  border-bottom-width: 1px;
}
<?php for ($column = 2; $column <= $grid_block_columns; $column++): ?>
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_<?php echo $column; ?> > .tb_size_<?php echo $column; ?>_last,
<?php endfor; ?>
.tb_listing.tb_grid_view.tb_style_bordered > :last-child
{
  border-bottom: 0 none;
}
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_1 > *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_2  .clear2  + *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_3  .clear3  + * + *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_4  .clear4  + * + * + *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_5  .clear5  + * + * + * + *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_6  .clear6  + * + * + * + * + *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_7  .clear7  + * + * + * + * + * + *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_8  .clear8  + * + * + * + * + * + * + *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_9  .clear9  + * + * + * + * + * + * + * + *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_10 .clear10 + * + * + * + * + * + * + * + * + *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_11 .clear11 + * + * + * + * + * + * + * + * + * + *,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_11 .clear12 + * + * + * + * + * + * + * + * + * + * + *
{
  border-left-color: transparent !important;
  border-right-color: transparent !important;
}
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_1 > *:last-child:before,
<?php for ($column = 2; $column <= $grid_block_columns; $column++): ?>
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_<?php echo $column; ?> > .tb_size_<?php echo $column; ?>_last:before,
<?php endfor; ?>
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_1 > *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_2  .clear2  + *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_3  .clear3  + * + *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_4  .clear4  + * + * + *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_5  .clear5  + * + * + * + *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_6  .clear6  + * + * + * + * + *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_7  .clear7  + * + * + * + * + * + *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_8  .clear8  + * + * + * + * + * + * + *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_9  .clear9  + * + * + * + * + * + * + * + *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_10 .clear10 + * + * + * + * + * + * + * + * + *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_11 .clear11 + * + * + * + * + * + * + * + * + * + *:after,
.tb_listing.tb_grid_view.tb_style_bordered.tb_size_11 .clear12 + * + * + * + * + * + * + * + * + * + * + *:after
{
  content: none;
}

/*  Carousel   --------------------------------------------------------------------------  */

.tb_listing.tb_grid_view.tb_style_bordered.tb_slider {
  border-top: 0 none !important;
}
.tb_listing.tb_grid_view.tb_style_bordered.tb_slider > *:after {
  content: none;
}
.tb_listing.tb_style_bordered.tb_slider .swiper-container {
  margin-right: -2px;
  margin-left:  -2px;
}
.tb_listing.tb_style_bordered.tb_slider .swiper-container .swiper-wrapper {
  <?php if ($lang_dir == 'ltr'): ?>
  border-left-width: 1px;
  border-left-style: solid;
  <?php else: ?>
  border-right-width: 1px;
  border-right-style: solid;
  <?php endif; ?>
}
.tb_listing.tb_style_bordered.tb_size_1.tb_slider .swiper-container {
  margin-left:  0;
  margin-right: 0;
}
.tb_listing.tb_style_bordered.tb_size_1.tb_slider .swiper-container .swiper-wrapper,
.tb_listing.tb_style_bordered.tb_size_1.tb_slider .swiper-container .swiper-wrapper .swiper-slide > *
{
  border-left: 0;
  border-right: 0;
}
.tb_listing.tb_style_bordered.tb_slider .swiper-slide {
  padding: 0;
}
.tb_style_bordered .swiper-slide > * {
  border-right-style: solid;
  border-right-width: 1px;
}
.tb_style_bordered .swiper-slide > *:after {
  top: 0;
  right: -1px;
  width: 0;
  height: 100%;
  border-right-style: solid;
  border-right-width: 1px;
}
.tb_slider_pagination.tb_bordered {
  margin-top: 0;
  padding: <?php echo $base / 2; ?>px 0;
  border-top-width: 1px;
  border-top-style: solid;
}
.tb_pb_10 > .tb_slider_pagination.tb_bordered:last-child { margin-top: 10px; margin-bottom: -10px; }
.tb_pb_20 > .tb_slider_pagination.tb_bordered:last-child { margin-top: 20px; margin-bottom: -20px; }
.tb_pb_30 > .tb_slider_pagination.tb_bordered:last-child { margin-top: 30px; margin-bottom: -30px; }
.tb_pb_40 > .tb_slider_pagination.tb_bordered:last-child { margin-top: 40px; margin-bottom: -40px; }
.tb_pb_50 > .tb_slider_pagination.tb_bordered:last-child { margin-top: 50px; margin-bottom: -50px; }

/*  -----------------------------------------------------------------------------------------
    S E P A R A T E   L I S T I N G
-----------------------------------------------------------------------------------------  */

.tb_listing.tb_style_separate .product-thumb {
  box-shadow: 0 1px 0 0 rgba(0, 0, 0, 0.1);
}
