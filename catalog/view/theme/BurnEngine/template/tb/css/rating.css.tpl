.rating * {
  display: inline-block;
  vertical-align: top;
}
.rating .tb_bar {
  position: relative;
}
.rating .tb_bar {
  white-space: nowrap;
  font-size: 15px;
}
.rating .tb_bar .tb_base,
.rating .tb_bar .tb_percent
{
  display: inline-block;
  letter-spacing: 0.15em;
  font-family: FontAwesome;
}
.rating .tb_bar .tb_base:before {
  content: '\f006\f006\f006\f006\f006';
}
.rating .tb_bar .tb_percent {
  overflow: hidden;
  position: absolute;
  top: 0;
  left: 0;
}
.rating .tb_bar .tb_percent:before {
  content: '\f005\f005\f005\f005\f005';
}
.rating .tb_bar:not(:last-child) {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0.2em;
  <?php else: ?>
  margin-left: 0.2em;
  <?php endif; ?>
}
.rating .tb_average {
  margin-top: -1px;
  letter-spacing: 1px;
  vertical-align: top;
  font-weight: bold;
}
.tb_grid_view .rating .tb_average {
  display: none;
}
.tb_compact_view .rating .tb_average {
  display: inline-block;
}
.rating .tb_total {
  display: inline-block;
  margin-top: -1px;
}
.rating .tb_total,
.rating + .tb_total
{
  letter-spacing: 0;
  font-weight: normal;
  font-size: 11px;
  vertical-align: top;
  opacity: 0.6;
}
.rating .tb_review_write {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 5px;
  padding-left: 7px;
  border-left-width: 1px;
  border-left-style: solid;
  <?php else: ?>
  margin-right: 5px;
  padding-right: 7px;
  border-right-width: 1px;
  border-right-style: 1px;
  <?php endif; ?>
}

/*** Small ***/

.rating.tb_max_w_320 .tb_review_write {
  display: block;
  margin: <?php echo $base * 0.25; ?>px 0 0 0;
  padding: 0;
  border: none;
}

