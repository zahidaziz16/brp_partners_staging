.tb_item:after {
  content: '';
  clear: both;
  display: table;
}
.tb_listing {
  position: relative;
  clear: both;
  margin-bottom: <?php echo $base * 1.5; ?>px;
}
/*
.tb_listing:hover {
  z-index: 3;
}
*/
.tb_listing:after {
  clear: both;
}
.tb_listing:last-child {
  margin-bottom: 0;
}

/*  -----------------------------------------------------------------------------------------
    G R I D   V I E W
-----------------------------------------------------------------------------------------  */

.tb_grid_view,
.tb_grid_view > div,
.tb_grid_view > li,
.tb_grid_view > span,
.tb_grid_view .tb_item,
.tb_grid_view .tb_flip,
.tb_grid_view .tb_overlay,
.tb_grid_view .tb_item_info_active,
.tb_grid_view .tb_item_info_hover
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
  min-width: 0;
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
}
.tb_grid_view {
  opacity: 0;
  -webkit-transition: opacity 0.2s;
          transition: opacity 0.2s;
}
.tb_grid_view[class*="tb_size_"] {
  opacity: 1;
}
.tb_grid_view > div,
.tb_grid_view > li,
.tb_grid_view > span
{
  position: relative;
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
}
.tb_grid_view .tb_item {
  width: 100%;
  text-align: center;
}
.tb_grid_view .tb_item,
.tb_grid_view .tb_item > *
{
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
}
.tb_grid_view .tb_flip:not(.image),
.tb_grid_view .tb_item_info_hover
{
  width: 100%;
  height: 100%;
}
<?php foreach ($grid_gutter as $gutter): ?>
.tb_grid_view.tb_gut_<?php echo $gutter; ?> {
  margin-left: -<?php echo $gutter; ?>px;
  margin-right: -<?php echo $gutter; ?>px;
  padding-right: <?php echo $gutter; ?>px;
}
.tb_grid_view.tb_gut_<?php echo $gutter; ?> > div,
.tb_grid_view.tb_gut_<?php echo $gutter; ?> > li,
.tb_grid_view.tb_gut_<?php echo $gutter; ?> > span
{
  margin-bottom: <?php echo $gutter; ?>px;
  padding-left: <?php echo $gutter; ?>px;
}
<?php endforeach; ?>

.tb_grid_view.tb_size_1  > div,
.tb_grid_view.tb_size_1  > li,
.tb_grid_view.tb_size_1  > span
{
  height: auto !important;
}
<?php for ($column = 1; $column <= $grid_block_columns; $column++): ?>
<?php $column_width = truncate_float(100 / $column, 8); ?>
.tb_grid_view.tb_size_<?php echo $column; ?> > div,
.tb_grid_view.tb_size_<?php echo $column; ?> > li,
.tb_grid_view.tb_size_<?php echo $column; ?> > span
{
  width: <?php echo $column_width; ?>%;
  max-width: <?php echo $column_width; ?>%;
      -ms-flex: 0 0 <?php echo $column_width; ?>%;
  -webkit-flex: 0 0 <?php echo $column_width; ?>%;
          flex: 0 0 <?php echo $column_width; ?>%;
}
<?php endfor; ?>
<?php for ($column = 2; $column <= $grid_block_columns; $column++): ?>
.tb_grid_view.tb_size_<?php echo $column; ?> > .tb_size_<?php echo $column; ?>_last,
<?php endfor; ?>
.tb_grid_view[class] > :last-child,
.tb_grid_view[class] > [class]:last-child
{
  margin-bottom: 0;
}

/*** Max rows ***/

<?php for ($column = 1; $column <= $grid_block_columns; $column++): ?>
<?php for ($row = 1; $row <= 10; $row++): ?>
.tb_size_<?php echo $column; ?>.tb_max_rows_<?php echo $row; ?> > div:nth-child(n + <?php echo $column * $row + 1; ?>),
<?php endfor; ?>
<?php endfor; ?>
.tb_grid_view.tb_size_13.tb_max_rows_11
{
  display: none;
}

/*  -----------------------------------------------------------------------------------------
    L I S T   V I E W
-----------------------------------------------------------------------------------------  */

.tb_list_view > div,
.tb_list_view > li,
.tb_list_view > span
{
  position: relative;
  clear: both;
  margin-top: <?php echo $base * 1.5; ?>px;
}
.tb_list_view > div:first-child,
.tb_list_view > li:first-child,
.tb_list_view > span:first-child
{
  margin-top: 0 !important;
}

/*  -----------------------------------------------------------------------------------------
    C O M P A C T   V I E W
-----------------------------------------------------------------------------------------  */

.tb_compact_view > div,
.tb_compact_view > li,
.tb_compact_view > span
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
  padding: 0 !important;
  background: transparent;
}
.tb_compact_view > div + div,
.tb_compact_view > li + li,
.tb_compact_view > span + span
{
  margin-top: <?php echo $base; ?>px;
}
.tb_compact_view .image,
.tb_compact_view .thumbnail
{
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
  margin: 0 !important;
}
.tb_compact_view .image:not(:last-child),
.tb_compact_view .thumbnail:not(:last-child)
{
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
  margin: 0 !important;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.75; ?>px !important;
  <?php else: ?>
  margin-left: <?php echo $base * 0.75; ?>px !important;
  <?php endif; ?>
}
.tb_compact_view .image + div,
.tb_compact_view .thumbnail + div
{
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
  min-width: 100px;
}
.tb_compact_view h3,
.tb_compact_view h4,
.tb_compact_view .name,
.tb_compact_view.tb_compact_view .price
{
  margin: 0;
  line-height: <?php echo $base; ?>px;
  font-size: <?php echo $base_font_size; ?>px;
}
.tb_compact_view h3 a,
.tb_compact_view h4 a,
.tb_compact_view .name a
{
  display: block;
}

/*  -----------------------------------------------------------------------------------------
    P A G I N A T I O N
-----------------------------------------------------------------------------------------  */

.tb_listing + .pagination {
  border-top-width: 1px;
  border-top-style: solid;
}

/*  -----------------------------------------------------------------------------------------
    S I M P L E   G R I D
-----------------------------------------------------------------------------------------  */

.tb_grid {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
  margin-top:  -30px;
  margin-left: -30px;
}
.tb_grid > div,
.tb_grid > li,
.tb_grid > span
{
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
  min-width: 0;
  margin-top:   30px;
  padding-left: 30px;
}
.tb_grid > :empty {
  display: block !important;
  height: 0;
  margin: 0 !important;
  border-top:    none;
  border-bottom: none;
}
