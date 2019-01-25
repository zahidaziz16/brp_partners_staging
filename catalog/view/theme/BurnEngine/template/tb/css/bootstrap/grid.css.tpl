/*  -----------------------------------------------------------------------------------------
    C O N T A I N E R
-----------------------------------------------------------------------------------------  */

.container,
.container-fluid
{
  clear: both;
  margin-left: auto;
  margin-right: auto;
}
.container:before,
.container:after,
.container-fluid:before,
.container-fluid:after,
.row-wrap:before
{
  content: '';
  display: table;
}
.container:after,
.container-fluid:after
{
  clear: both;
}
#wrapper.container-fluid {
  overflow-x: hidden;
}

/*  -----------------------------------------------------------------------------------------
    G R I D
-----------------------------------------------------------------------------------------  */

.row,
.col,
.row > div
{
      -ms-flex-direction: row;
  -webkit-flex-direction: row;
          flex-direction: row;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
  pointer-events: none;
}
.row {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  margin-top:   -<?php echo $grid_gutter[$default_grid_gutter]; ?>px;
  margin-left:  -<?php echo $grid_gutter[$default_grid_gutter]; ?>px;
}
.row:after {
  content: '';
  display: table;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  clear: both;
}
.row + .row {
  margin-top: 0;
}
html[dir="rtl"] .tb_no_rtl_columns {
  direction: ltr;
}
html[dir="rtl"] .tb_no_rtl_columns > .col {
  direction: rtl;
}

.col,
.row > div
{
       -ms-flex-align: start;
  -webkit-align-items: flex-start;
          align-items: flex-start;
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  max-width: 100%;
  /* min-height: 1px; */
  margin-left: <?php echo $grid_gutter[$default_grid_gutter]; ?>px;
  margin-top:  <?php echo $grid_gutter[$default_grid_gutter]; ?>px;
}
.row.row-reverse,
.row-reverse > .row
{
      -ms-flex-direction: row-reverse;
  -webkit-flex-direction: row-reverse;
          flex-direction: row-reverse;
}
.row.row-reverse > .col,
.row-reverse > .row > .col
{
  <?php if ($lang_dir == 'ltr'): ?>
  float: right;
  <?php else: ?>
  float: left;
  <?php endif; ?>
}
[class*="col-"] > * {
  pointer-events: auto;
}

.container,
.tb_width_fixed,
#wrapper:not(.container) > .tb_content_fixed > .row-wrap {
  max-width: <?php echo $width; ?>px;
}
@media (min-width: <?php echo $width + 80; ?>px) {
  .tb_content_fixed > .row {
    max-width: <?php echo $width + $grid_gutter[$default_grid_gutter] * 2; ?>px;
  }
}
@media (max-width: <?php echo $width + 80; ?>px) {
  #wrapper.container {
    margin-left:  30px;
    margin-right: 30px;
  }
}

#wrapper:not(.container) > .container,
#wrapper:not(.container) > .tb_width_fixed,
#wrapper:not(.container) > :not(.container):not(.tb_content_fixed):not(.tb_width_fixed) .tb_width_fixed
{
  min-width: 0;
  margin-left: auto;
  margin-right: auto;
}
@media (min-width: <?php echo $width + 81; ?>px) {
  #wrapper:not(.container) > .tb_content_fixed:not([class*="tb_pl_"]) > div,
  #wrapper:not(.container) > :not(.container):not(.tb_content_fixed):not(.tb_width_fixed):not([class*="tb_pl_"]) .tb_content_fixed:not([class*="tb_pl_"]) > .row
  {
    min-width: 0;
    margin-left: auto;
    margin-right: auto;
  }
}
<?php for ($padding = 5; $padding <= 50; $padding += 5): ?>
@media (min-width: <?php echo $width + $padding * 2 + 1; ?>px) {
  #wrapper:not(.container) > .tb_content_fixed.tb_pl_<?php echo $padding; ?> > div,
  #wrapper:not(.container) > :not(.container):not(.tb_content_fixed):not(.tb_width_fixed).tb_pl_<?php echo $padding; ?> .tb_content_fixed > .row,
  #wrapper:not(.container) > :not(.container):not(.tb_content_fixed):not(.tb_width_fixed) .tb_content_fixed.tb_pl_<?php echo $padding; ?> > .row
  {
    min-width: 0;
    margin-left: auto;
    margin-right: auto;
  }
}
<?php endfor; ?>

/*  -----------------------------------------------------------------------------------------
    G R I D   G U T T E R
-----------------------------------------------------------------------------------------  */

.row {
  margin-left: -30px;
}
[class*="col-"] {
  margin-top:  30px;
  margin-left: 30px;
}

<?php foreach ($grid_gutter as $gutter): ?>
.row.tb_gut_<?php echo $gutter; ?> {
  margin-left: -<?php echo $gutter; ?>px;
}
.row.tb_gut_<?php echo $gutter; ?> > .col {
  margin-left: <?php echo $gutter; ?>px;
}
<?php endforeach; ?>

<?php foreach ($breakpoints as $breakpoint): ?>
/*** <?php echo $breakpoint; ?> ***/

<?php if ($breakpoint != 'xs'): ?>
@media (min-width: <?php echo (${'screen_' . $breakpoint} + 1) . 'px'; ?>) {
<?php endif; ?>
  <?php foreach ($grid_gutter as $gutter): ?>
  .row.tb_gut_<?php echo $breakpoint; ?>_<?php echo $gutter; ?> {
    margin-top: -<?php echo $gutter; ?>px;
    margin-left:  -<?php echo $gutter; ?>px;
    margin-right: -<?php echo $gutter; ?>px;
    padding-right: <?php echo $gutter; ?>px;
  }
  .row.tb_gut_<?php echo $breakpoint; ?>_<?php echo $gutter; ?> > .col {
    margin-left: <?php echo $gutter; ?>px;
  }
  .row.tb_gut_<?php echo $breakpoint; ?>_<?php echo $gutter; ?> > [class*="col-<?php echo $breakpoint; ?>-"] {
    margin-top:  <?php echo $gutter; ?>px;
  }
  <?php endforeach; ?>
<?php if ($breakpoint != 'xs'): ?>
}
<?php endif; ?>
<?php endforeach; ?>

@media (min-width: <?php echo $width + 80; ?>px) {
  .tb_content_fixed > .row {
    margin-right: -30px;
    padding-right: 30px;
  }
  <?php foreach ($breakpoints as $breakpoint): ?>
  <?php if ($breakpoint < $width + 80): ?>
  <?php foreach ($grid_gutter as $gutter): ?>
  .tb_content_fixed > .row.tb_gut_<?php echo $breakpoint; ?>_<?php echo $gutter; ?> {
    max-width: <?php echo $width + $gutter * 2; ?>px;
    margin-right: -<?php echo $gutter; ?>px;
    padding-right: <?php echo $gutter; ?>px;
  }
  <?php endforeach; ?>
  <?php endif; ?>
  <?php endforeach; ?>
}

/*  -----------------------------------------------------------------------------------------
    G R I D   S I Z E
-----------------------------------------------------------------------------------------  */

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .col:empty {
    display: none !important;
  }
}

<?php foreach ($breakpoints as $breakpoint): ?>
/*** <?php echo $breakpoint; ?> ***/

<?php if ($breakpoint != 'xs'): ?>
@media (min-width: <?php echo (${'screen_' . $breakpoint} + 1) . 'px'; ?>) {
.col-<?php echo $breakpoint; ?>-auto:empty {
  display: none;
}
<?php endif; ?>
.col-<?php echo $breakpoint; ?>-auto.col-<?php echo $breakpoint; ?>-auto,
.col-<?php echo $breakpoint; ?>-fill.col-<?php echo $breakpoint; ?>-fill
{
      width: auto;
  max-width: none;
}
<?php for ($column = 1; $column <= $grid_columns; $column++): ?>
<?php $column_width = truncate_float($column * 100 / $grid_columns, 8); ?>
.col-<?php echo $breakpoint; ?>-<?php echo $column; ?>,
<?php endfor; ?>
/* 5 columns */
<?php for ($column = 1; $column < 5; $column++): ?>
<?php $column_width = truncate_float($column * 100 / 5, 8); ?>
.col-<?php echo $breakpoint; ?>-<?php echo $column; ?>-5,
<?php endfor; ?>
/* 8 columns */
<?php foreach (array (1, 3, 5, 7) as $column): ?>
<?php $column_width = truncate_float($column * 100 / 8, 8); ?>
.col-<?php echo $breakpoint; ?>-<?php echo $column; ?>-8,
<?php endforeach; ?>
.col-<?php echo $breakpoint; ?>-auto,
.col-<?php echo $breakpoint; ?>-auto > .tb_wt.tb_wt
{
      -ms-flex: 0 1 auto;
  -webkit-flex: 0 1 auto;
          flex: 0 1 auto;
}
.col-<?php echo $breakpoint; ?>-fill {
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
  min-width:        0px;
}
<?php for ($column = 1; $column <= $grid_columns; $column++): ?>
<?php $column_width = truncate_float($column * 100 / $grid_columns, 8); ?>
.col-<?php echo $breakpoint; ?>-<?php echo $column; ?> {
      width: calc(<?php echo $column_width; ?>% - <?php echo $grid_gutter[$default_grid_gutter]; ?>px);
  max-width: calc(<?php echo $column_width; ?>% - <?php echo $grid_gutter[$default_grid_gutter]; ?>px);
}
.tb_gut_<?php echo $breakpoint; ?>_0  > .col-<?php echo $breakpoint; ?>-<?php echo $column; ?> {
      width: <?php echo $column_width; ?>%;
  max-width: <?php echo $column_width; ?>%;
}
<?php foreach ($grid_gutter as $gutter): ?>
<?php if ($gutter > 0): ?>
.tb_gut_<?php echo $breakpoint; ?>_<?php echo $gutter; ?> > .col-<?php echo $breakpoint; ?>-<?php echo $column; ?> {
      width: calc(<?php echo $column_width; ?>% - <?php echo $gutter; ?>px);
  max-width: calc(<?php echo $column_width; ?>% - <?php echo $gutter; ?>px);
}
<?php endif; ?>
<?php endforeach; ?>
.tb_separate_columns > .col-<?php echo $breakpoint; ?>-<?php echo $column; ?> {
      width: <?php echo $column_width; ?>%;
  max-width: <?php echo $column_width; ?>%;
}
<?php endfor; ?>
/* 5 columns */
<?php for ($column = 1; $column < 5; $column++): ?>
<?php $column_width = truncate_float($column * 100 / 5, 8); ?>
.col-<?php echo $breakpoint; ?>-<?php echo $column; ?>-5 {
      width: calc(<?php echo $column_width; ?>% - <?php echo $grid_gutter[$default_grid_gutter]; ?>px);
  max-width: calc(<?php echo $column_width; ?>% - <?php echo $grid_gutter[$default_grid_gutter]; ?>px);
}
.tb_gut_<?php echo $breakpoint; ?>_0  > .col-<?php echo $breakpoint; ?>-<?php echo $column; ?>-5 {
      width: <?php echo $column_width; ?>%;
  max-width: <?php echo $column_width; ?>%;
}
<?php foreach ($grid_gutter as $gutter): ?>
<?php if ($gutter > 0): ?>
.tb_gut_<?php echo $breakpoint; ?>_<?php echo $gutter; ?> > .col-<?php echo $breakpoint; ?>-<?php echo $column; ?>-5 {
      width: calc(<?php echo $column_width; ?>% - <?php echo $gutter; ?>px);
  max-width: calc(<?php echo $column_width; ?>% - <?php echo $gutter; ?>px);
}
<?php endif; ?>
<?php endforeach; ?>
.tb_separate_columns > .col-<?php echo $breakpoint; ?>-<?php echo $column; ?>-5 {
      width: <?php echo $column_width; ?>%;
  max-width: <?php echo $column_width; ?>%;
}
<?php endfor; ?>
/* 8 columns */
<?php foreach (array (1, 3, 5, 7) as $column): ?>
<?php $column_width = truncate_float($column * 100 / 8, 8); ?>
.col-<?php echo $breakpoint; ?>-<?php echo $column; ?>-8 {
      width: calc(<?php echo $column_width; ?>% - <?php echo $grid_gutter[$default_grid_gutter]; ?>px);
  max-width: calc(<?php echo $column_width; ?>% - <?php echo $grid_gutter[$default_grid_gutter]; ?>px);
}
.tb_gut_<?php echo $breakpoint; ?>_0  > .col-<?php echo $breakpoint; ?>-<?php echo $column; ?>-8 {
      width: <?php echo $column_width; ?>%;
  max-width: <?php echo $column_width; ?>%;
}
<?php foreach ($grid_gutter as $gutter): ?>
<?php if ($gutter > 0): ?>
.tb_gut_<?php echo $breakpoint; ?>_<?php echo $gutter; ?> > .col-<?php echo $breakpoint; ?>-<?php echo $column; ?>-8 {
      width: calc(<?php echo $column_width; ?>% - <?php echo $gutter; ?>px);
  max-width: calc(<?php echo $column_width; ?>% - <?php echo $gutter; ?>px);
}
<?php endif; ?>
<?php endforeach; ?>
.tb_separate_columns > .col-<?php echo $breakpoint; ?>-<?php echo $column; ?>-8 {
      width: <?php echo $column_width; ?>%;
  max-width: <?php echo $column_width; ?>%;
}
<?php endforeach; ?>
<?php if ($breakpoint != 'xs'): ?>
}
<?php endif; ?>

<?php endforeach; ?>

<?php $prev_breakpoint = false; ?>
<?php foreach ($breakpoints as $breakpoint): ?>
<?php if (!empty($prev_breakpoint)): ?>
<?php if ($prev_breakpoint == 'xs'): ?>
@media (max-width: <?php echo ${'screen_' . $breakpoint} . 'px'; ?>) {
<?php else: ?>
@media (min-width: <?php echo (${'screen_' . $prev_breakpoint} + 1) . 'px'; ?>) and (max-width: <?php echo ${'screen_' . $breakpoint} . 'px'; ?>) {
<?php endif; ?>
  .col-<?php echo $prev_breakpoint; ?>-auto.flex-wrap {
        -ms-flex: 0 1 auto;
    -webkit-flex: 0 1 auto;
            flex: 0 1 auto;
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
      -ms-flex-wrap: nowrap;
  -webkit-flex-wrap: nowrap;
          flex-wrap: nowrap;
  }
  .col-<?php echo $prev_breakpoint; ?>-auto.flex-wrap > * {
        -ms-flex: 0 1 auto !important;
    -webkit-flex: 0 1 auto !important;
            flex: 0 1 auto !important;
  }
}
<?php endif; ?>
<?php $prev_breakpoint = $breakpoint; ?>
<?php endforeach; ?>
@media (min-width: <?php echo (${'screen_' . $prev_breakpoint} + 1) . 'px'; ?>) {
  .col-<?php echo $prev_breakpoint; ?>-auto.flex-wrap {
        -ms-flex: 0 1 auto;
    -webkit-flex: 0 1 auto;
            flex: 0 1 auto;
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
      -ms-flex-wrap: nowrap;
  -webkit-flex-wrap: nowrap;
          flex-wrap: nowrap;
  }
  .col-<?php echo $prev_breakpoint; ?>-auto.flex-wrap > * {
        -ms-flex: 0 1 auto !important;
    -webkit-flex: 0 1 auto !important;
            flex: 0 1 auto !important;
  }
}
<?php $prev_breakpoint = false; ?>

.col-valign-top.flex-wrap {
            -ms-flex-pack: start;
  -webkit-justify-content: flex-start;
          justify-content: flex-start;
}
.col-valign-middle.flex-wrap {
            -ms-flex-pack: center;
  -webkit-justify-content: center;
          justify-content: center;
}
.col-valign-bottom.flex-wrap {
            -ms-flex-pack: end;
  -webkit-justify-content: flex-end;
          justify-content: flex-end;
}


/*  -----------------------------------------------------------------------------------------
    C O L U M N   A L I G N
-----------------------------------------------------------------------------------------  */

.col > .display-block,
.col > address:not(.display-inline-block),
.col > article:not(.display-inline-block),
.col > aside:not(.display-inline-block),
.col > audio:not(.display-inline-block),
.col > blockquote:not(.display-inline-block),
.col > canvas:not(.display-inline-block),
.col > div:not(.display-inline-block),
.col > dl:not(.display-inline-block),
.col > fieldset:not(.display-inline-block),
.col > figure:not(.display-inline-block),
.col > form:not(.display-inline-block),
.col > h1:not(.display-inline-block),
.col > h1:not(.display-inline-block),
.col > h2:not(.display-inline-block),
.col > h3:not(.display-inline-block),
.col > h4:not(.display-inline-block),
.col > h5:not(.display-inline-block),
.col > h6:not(.display-inline-block),
.col > hr:not(.display-inline-block),
.col > nav:not(.display-inline-block),
.col > ol:not(.display-inline-block),
.col > p:not(.display-inline-block),
.col > pre:not(.display-inline-block),
.col > section:not(.display-inline-block),
.col > table:not(.display-inline-block),
.col > ul:not(.display-inline-block),
.col > video:not(.display-inline-block),
.row > div > .display-block,
.row > div > address:not(.display-inline-block),
.row > div > article:not(.display-inline-block),
.row > div > aside:not(.display-inline-block),
.row > div > audio:not(.display-inline-block),
.row > div > blockquote:not(.display-inline-block),
.row > div > canvas:not(.display-inline-block),
.row > div > div:not(.display-inline-block),
.row > div > dl:not(.display-inline-block),
.row > div > fieldset:not(.display-inline-block),
.row > div > figure:not(.display-inline-block),
.row > div > form:not(.display-inline-block),
.row > div > h1:not(.display-inline-block),
.row > div > h1:not(.display-inline-block),
.row > div > h2:not(.display-inline-block),
.row > div > h3:not(.display-inline-block),
.row > div > h4:not(.display-inline-block),
.row > div > h5:not(.display-inline-block),
.row > div > h6:not(.display-inline-block),
.row > div > hr:not(.display-inline-block),
.row > div > nav:not(.display-inline-block),
.row > div > ol:not(.display-inline-block),
.row > div > p:not(.display-inline-block),
.row > div > pre:not(.display-inline-block),
.row > div > section:not(.display-inline-block),
.row > div > table:not(.display-inline-block),
.row > div > ul:not(.display-inline-block),
.row > div > video:not(.display-inline-block),
.col > .display-block > *,
.col > address:not(.display-inline-block) > *,
.col > article:not(.display-inline-block) > *,
.col > aside:not(.display-inline-block) > *,
.col > audio:not(.display-inline-block) > *,
.col > blockquote:not(.display-inline-block) > *,
.col > canvas:not(.display-inline-block) > *,
.col > div:not(.display-inline-block) > *,
.col > dl:not(.display-inline-block) > *,
.col > fieldset:not(.display-inline-block) > *,
.col > figure:not(.display-inline-block) > *,
.col > form:not(.display-inline-block) > *,
.col > h1:not(.display-inline-block) > *,
.col > h1:not(.display-inline-block) > *,
.col > h2:not(.display-inline-block) > *,
.col > h3:not(.display-inline-block) > *,
.col > h4:not(.display-inline-block) > *,
.col > h5:not(.display-inline-block) > *,
.col > h6:not(.display-inline-block) > *,
.col > hr:not(.display-inline-block) > *,
.col > nav:not(.display-inline-block) > *,
.col > ol:not(.display-inline-block) > *,
.col > p:not(.display-inline-block) > *,
.col > pre:not(.display-inline-block) > *,
.col > section:not(.display-inline-block) > *,
.col > table:not(.display-inline-block) > *,
.col > ul:not(.display-inline-block) > *,
.col > video:not(.display-inline-block) > *
{
      -ms-flex: 1 1 100%;
  -webkit-flex: 1 1 100%;
          flex: 1 1 100%;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
}
<?php foreach ($breakpoints as $breakpoint): ?>
<?php if ($breakpoint != 'xs'): ?>
@media (min-width: <?php echo (${'screen_' . $breakpoint} + 1) . 'px'; ?>) {
<?php endif; ?>
  .col-<?php echo $breakpoint; ?>-auto > address:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > article:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > aside:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > audio:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > blockquote:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > canvas:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > div:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > dl:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > fieldset:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > figure:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > form:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > h1:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > h1:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > h2:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > h3:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > h4:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > h5:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > h6:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > hr:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > nav:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > ol:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > p:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > pre:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > section:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > table:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > ul:not(.display-inline-block),
  .col-<?php echo $breakpoint; ?>-auto > video:not(.display-inline-block)
  {
    min-width: 100%;
  }
<?php if ($breakpoint != 'xs'): ?>
}
<?php endif; ?>
<?php endforeach; ?>

.row-wrap {
  display: block !important;
}
.col-align-start {
            -ms-flex-pack: start;
  -webkit-justify-content: flex-start;
          justify-content: flex-start;
}
.col-align-center {
            -ms-flex-pack: center;
  -webkit-justify-content: center;
          justify-content: center;
}
@media (min-width: <?php echo ($screen_sm + 1) . 'px'; ?>) {
  .col-align-start   > *,
  .col-align-center  > *,
  .col-align-end     > *,
  .col-align-between > *,
  .col-align-around  > *
  {
        -ms-flex: 0 1 auto;
    -webkit-flex: 0 1 auto;
            flex: 0 1 auto;
  }
  .col-align-end {
              -ms-flex-pack: end;
    -webkit-justify-content: flex-end;
            justify-content: flex-end;
  }
  .col-align-between {
              -ms-flex-pack: justify;
    -webkit-justify-content: space-between;
            justify-content: space-between;
  }
  .col-align-around {
             -ms-flex-pack: justify;
    -webkit-justify-content: space-around;
            justify-content: space-around;
  }
}
.col-align-start,
.col-align-center,
.col-align-end,
.col-align-between,
.col-align-around,
.col-valign-top,
.col-valign-middle,
.col-valign-bottom
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
}
.col-valign-top {
     -ms-flex-line-pack: start;
  -webkit-align-content: flex-start;
          align-content: flex-start;
       -ms-flex-align: start;
  -webkit-align-items: flex-start;
          align-items: flex-start;
}
.col-top,
.col-valign-top > *
{
  -ms-flex-item-align: start;
  -webkit-align-self: flex-start;
          align-self: flex-start;
}
.col-valign-middle {
     -ms-flex-line-pack: center;
  -webkit-align-content: center;
          align-content: center;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
}
.col-middle,
.col-valign-middle > *
{
  -ms-flex-item-align: center;
  -webkit-align-self: center;
          align-self: center;
}
.col-valign-bottom {
     -ms-flex-line-pack: end;
  -webkit-align-content: flex-end;
          align-content: flex-end;
       -ms-flex-align: end;
  -webkit-align-items: flex-end;
          align-items: flex-end;
}
.col-bottom,
.col-valign-bottom > *
{
  -ms-flex-item-align: end;
  -webkit-align-self: flex-end;
          align-self: flex-end;
}

/*  ---   Separate Columns   ------------------------------------------------------------  */

.row.tb_separate_columns {
  margin:  0;
  padding: 0;
}
.row.tb_separate_columns > .col {
  margin: 0 !important;
  <?php if ($lang_dir == 'ltr'): ?>
  border-left-width: 1px;
  border-left-style: solid;
  <?php else: ?>
  border-right-width: 1px;
  border-right-style: solid;
  <?php endif; ?>
}
.tb_content_fixed > .tb_separate_columns {
  max-width: <?php echo $width; ?>px;
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .tb_separate_columns > .col {
    border-top-width: 1px;
    border-top-style: solid;
    border-left-width: 0 !important;
    border-right-width: 0 !important;
  }
  html[dir="ltr"] .tb_separate_columns > .tb_pl_0:first-child:not(:last-child) {
    padding-right: 0;
    padding-bottom: 30px;
  }
  html[dir="rtl"] .tb_separate_columns > .tb_pr_0:first-child:not(:last-child) {
    padding-left: 0;
    padding-bottom: 30px;
  }
}
<?php foreach ($breakpoints as $breakpoint): ?>
<?php if (empty($prev_breakpoint)): ?>
@media (max-width: <?php echo ${'screen_' . $breakpoint} . 'px'; ?>) {
<?php else: ?>
@media (min-width: <?php echo (${'screen_' . $prev_breakpoint} + 1) . 'px'; ?>) and (max-width: <?php echo ${'screen_' . $breakpoint} . 'px'; ?>) {
<?php endif; ?>
  <?php if (empty($prev_breakpoint)) { $prev_breakpoint = 'xs'; } ?>
  <?php for ($position = 1; $position <= 6; $position++): ?>
  .tb_separate_columns > .col.pos-<?php echo $prev_breakpoint; ?>-<?php echo $position; ?> + .col,
  <?php endfor; ?>
  .tb_separate_columns > .col:first-child
  {
    border-style: none;
  }
}
<?php $prev_breakpoint = $breakpoint; ?>
<?php endforeach; ?>
@media (min-width: <?php echo $screen_lg + 1; ?>px) {
  <?php for ($position = 1; $position <= 6; $position++): ?>
  .tb_separate_columns > .col.pos-<?php echo $prev_breakpoint; ?>-<?php echo $position; ?> + .col,
  <?php endfor; ?>
  .tb_separate_columns > .col:first-child
  {
    border-style: none;
  }
}

/*** Inner padding ***/

<?php for ($padding = 0; $padding <= 100; $padding += 5): ?>
.row.tb_ip_<?php echo $padding; ?> > .col:not([class*="tb_pt_"]) {
  padding: <?php echo $padding; ?>px;
}
<?php endfor; ?>

@media (max-width: <?php echo $screen_md . 'px'; ?>) {
  <?php for ($padding = 35; $padding <= 100; $padding += 5): ?>
  .row.tb_ip_<?php echo $padding; ?> > .col:not([class*="tb_pt_"]),
  <?php endfor; ?>
  .row.tb_ip_105 > .col:not([class*="tb_pt_"])
  {
    padding: 30px;
  }
}


/*  -----------------------------------------------------------------------------------------
    S P A C I N G
-----------------------------------------------------------------------------------------  */

/*  ---   Margin   ----------------------------------------------------------------------  */

<?php foreach (array ('top'=>'mt', 'right'=>'mr', 'bottom'=>'mb', 'left'=>'ml') as $property=>$abbrev): ?>
<?php for ($margin = -150; $margin <= 150; $margin += 5): ?>
.tb_<?php echo $abbrev; ?>_<?php echo $margin; ?> {
  margin-<?php echo $property; ?>: <?php echo $margin; ?>px;
}
<?php endfor; ?>
<?php endforeach; ?>

@media (max-width: <?php echo $screen_md . 'px'; ?>) {
  <?php foreach (array ('top'=>'mt', 'right'=>'mr', 'bottom'=>'mb', 'left'=>'ml') as $property=>$abbrev): ?>
  <?php for ($margin = 35; $margin <= 150; $margin += 5): ?>
  .tb_<?php echo $abbrev; ?>_<?php echo $margin; ?>,
  <?php endfor; ?>
  .tb_<?php echo $abbrev; ?>_105
  {
    margin-<?php echo $property; ?>: 30px;
  }
  <?php endforeach; ?>
  <?php foreach (array ('top'=>'mt', 'right'=>'mr', 'bottom'=>'mb', 'left'=>'ml') as $property=>$abbrev): ?>
  <?php for ($margin = -150; $margin <= -35; $margin += 5): ?>
  .tb_<?php echo $abbrev; ?>_<?php echo $margin; ?>,
  <?php endfor; ?>
  .tb_<?php echo $abbrev; ?>_-105
  {
    margin-<?php echo $property; ?>: -30px;
  }
  <?php endforeach; ?>
}

/*  ---   Padding   ---------------------------------------------------------------------  */

<?php for ($padding = 0; $padding <= 100; $padding += 5): ?>
.tb_p_<?php echo $padding; ?> {
  padding: <?php echo $padding; ?>px;
}
<?php endfor; ?>
<?php foreach (array ('top'=>'pt', 'right'=>'pr', 'bottom'=>'pb', 'left'=>'pl') as $property=>$abbrev): ?>
<?php for ($padding = 0; $padding <= 100; $padding += 5): ?>
.tb_<?php echo $abbrev; ?>_<?php echo $padding; ?><?php if ($property == 'top' || $property == 'bottom'): ?>,
.row-wrap.tb_<?php echo $abbrev; ?>_<?php echo $padding; ?>  > .row<?php endif; ?> {
  padding-<?php echo $property; ?>: <?php echo $padding; ?>px;
}
<?php endfor; ?>
<?php endforeach; ?>

@media (max-width: <?php echo $screen_md . 'px'; ?>) {
  <?php foreach (array ('top'=>'pt', 'right'=>'pr', 'bottom'=>'pb', 'left'=>'pl') as $property=>$abbrev): ?>
  <?php for ($padding = 35; $padding <= 100; $padding += 5): ?>
  .tb_<?php echo $abbrev; ?>_<?php echo $padding; ?><?php if ($property == 'top' || $property == 'bottom'): ?>,
  .row-wrap.tb_<?php echo $abbrev; ?>_<?php echo $padding; ?>  > .row<?php endif; ?>,
  <?php endfor; ?>
  .tb_<?php echo $abbrev; ?>_105<?php if ($property == 'top' || $property == 'bottom'): ?>,
  .row-wrap.tb_<?php echo $abbrev; ?>_105  > .row<?php endif; ?>
  {
    padding-<?php echo $property; ?>: 30px;
  }
  <?php endforeach; ?>
}


.row-wrap:not(#wrapper) {
  padding-top: 0;
  padding-bottom: 0;
}

/*  ---   Responsive   -----------------------------------------------------------------------------  */

@media (max-width: <?php echo $width + 80; ?>px) {
  #wrapper:not(.container) > .tb_width_fixed,
  #wrapper:not(.container) > .container,
  #wrapper:not(.container) > :not(.container):not(.tb_width_fixed):not(.tb_content_fixed) > .tb_width_fixed
  {
    margin-left:  40px;
    margin-right: 40px;
  }
  #wrapper:not(.container) > .tb_content_fixed:not([class*="tb_pl_"]):not([class*="tb_pr_"]),
  #wrapper:not(.container) > :not(.container):not(.tb_width_fixed):not(.tb_content_fixed):not([class*="tb_pl_"]):not([class*="tb_pr_"]) > .tb_content_fixed:not([class*="tb_pl_"]):not([class*="tb_pr_"]),
  #wrapper:not(.container) > :not(.container):not(.tb_width_fixed):not(.tb_content_fixed):not([class*="tb_pl_"]):not([class*="tb_pr_"]) > .row:not(.tb_separate_columns) > .col > .tb_content_fixed:not([class*="tb_pl_"]):not([class*="tb_pr_"])
  {
    padding-left:  40px;
    padding-right: 40px;
  }
  #wrapper:not(.container) > .tb_content_fixed,
  #wrapper:not(.container) > :not(.tb_content_fixed):not([class*="tb_pl_"]):not([class*="tb_pr_"]) > .tb_content_fixed:not([class*="tb_pl_"]):not([class*="tb_pr_"]),
  #wrapper:not(.container) > :not(.container):not(.tb_width_fixed):not(.tb_content_fixed):not([class*="tb_pl_"]):not([class*="tb_pr_"]) > div:not(.tb_separate_columns) .tb_content_fixed
  {
    min-width: 0;
  }
}

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  #wrapper.container {
    margin-left:  10px;
    margin-right: 10px;
  }
  #wrapper:not(.container) > :not(.container):not(.tb_width_fixed):not(.tb_content_fixed) > .tb_width_fixed,
  #wrapper:not(.container) > :not(.container):not(.tb_width_fixed):not(.tb_content_fixed) > :not(.tb_separate_columns) .tb_width_fixed
  {
    min-width: 0;
    margin-left:  20px !important;
    margin-right: 20px !important;
  }
  #wrapper:not(.container) > .tb_width_fixed,
  #wrapper:not(.container) > .container,
  #wrapper:not(.container) > :not(.container):not(.tb_width_fixed):not(.tb_content_fixed) > .tb_width_fixed
  {
    margin-left:  20px;
    margin-right: 20px;
  }
  #wrapper:not(.container) > .tb_content_fixed:not([class*="tb_pl_"]):not([class*="tb_pr_"]),
  #wrapper:not(.container) > :not(.container):not(.tb_width_fixed):not(.tb_content_fixed):not([class*="tb_pl_"]):not([class*="tb_pr_"]) > .tb_content_fixed:not([class*="tb_pl_"]):not([class*="tb_pr_"]),
  #wrapper:not(.container) > :not(.container):not(.tb_width_fixed):not(.tb_content_fixed):not([class*="tb_pl_"]):not([class*="tb_pr_"]) > .row:not(.tb_separate_columns) > .col > .tb_content_fixed:not([class*="tb_pl_"]):not([class*="tb_pr_"])
  {
    padding-left:  20px;
    padding-right: 20px;
  }
}

/*  -----------------------------------------------------------------------------------------
    S T Y L I N G
-----------------------------------------------------------------------------------------  */

.row,
html[dir="ltr"] .col:first-child,
html[dir="rtl"] div:not(.tb_no_rtl_columns) > .col:last-child,
html[dir="rtl"] .tb_no_rtl_columns > .col:first-child
{
  border-top-left-radius: inherit;
  border-bottom-left-radius: inherit;
}
.row,
html[dir="ltr"] .col:last-child,
html[dir="rtl"] div:not(.tb_no_rtl_columns) > .col:first-child,
html[dir="rtl"] .tb_no_rtl_columns > .col:last-child
{
  border-top-right-radius:    inherit;
  border-bottom-right-radius: inherit;
}
<?php $prev_breakpoint = false; ?>
<?php foreach ($breakpoints as $breakpoint): ?>
<?php if (empty($prev_breakpoint)): ?>
@media (max-width: <?php echo ${'screen_' . $breakpoint} . 'px'; ?>) {
<?php else: ?>
@media (min-width: <?php echo (${'screen_' . $prev_breakpoint} + 1) . 'px'; ?>) and (max-width: <?php echo ${'screen_' . $breakpoint} . 'px'; ?>) {
<?php endif; ?>
  html[dir] .col-<?php echo $breakpoint; ?>-12:first-child {
    border-top-left-radius:  inherit;
    border-top-right-radius: inherit;
  }
  html[dir] .col-<?php echo $breakpoint; ?>-12:last-child {
    border-bottom-left-radius:  inherit;
    border-bottom-right-radius: inherit;
  }
  html[dir] .col-<?php echo $breakpoint; ?>-12:not(:first-child) {
    border-top-left-radius:  0;
    border-top-right-radius: 0;
  }
  html[dir] .col-<?php echo $breakpoint; ?>-12:not(:last-child) {
    border-bottom-left-radius:  0;
    border-bottom-right-radius: 0;
  }
}
<?php $prev_breakpoint = $breakpoint; ?>
<?php endforeach; ?>
