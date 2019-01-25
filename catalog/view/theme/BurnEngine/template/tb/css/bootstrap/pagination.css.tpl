.pagination {
  position: relative;
  overflow: hidden;
  clear: both;
}
.pagination:empty {
  display: none;
}
.pagination .results {
  <?php if ($lang_dir == 'ltr'): ?>
  float: right;
  <?php else: ?>
  float: left;
  <?php endif; ?>
  font-size: <?php echo $base_font_size - 2; ?>px;
}
.pagination .links {
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  margin-bottom: 0;
}
.pagination .links > *,
.pagination .links a,
.pagination .links li > span
{
  display: inline-block;
  width: <?php echo $base * 1.5; ?>px;
  height: <?php echo $base * 1.5; ?>px;
  line-height: <?php echo $base * 1.5; ?>px;
  text-align: center;
  font-weight: normal;
  vertical-align: top;
  border-radius: 2px;
}
.pagination .links > * {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 3px;
  <?php else: ?>
  margin-left: 3px;
  <?php endif; ?>
}

/*** Theme ***/

.pagination .links a {
  box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
}
.table + .pagination {
  margin-top: -<?php echo $base * 1.5; ?>px;
  padding-top: <?php echo $base * 1.5; ?>px;
}

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .pagination {
    text-align: center;
  }
  .pagination * {
    float: none !important;
  }
  .pagination .links {
    float: none;
    margin-bottom: <?php echo $base / 2; ?>px;
  }
}
