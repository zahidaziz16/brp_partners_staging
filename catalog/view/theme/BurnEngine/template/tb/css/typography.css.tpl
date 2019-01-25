h1 {
  line-height: <?php echo $base * 1.5; ?>px;
}
p.tb_empty {
  line-height: <?php echo $tbData->calculateLineHeight($base_font_size * 1.25, $base); ?>px;
}
.pagination .results {
  line-height: <?php echo $base * 1.5; ?>px;
}
.pagination .results:first-child {
  line-height: <?php echo $base; ?>px;
}
.pagination .links a,
.pagination .links b
{
  line-height: <?php echo $base * 1.5; ?>px;
}
.tb_compact_view .name,
.tb_compact_view .price,
.tb_compact_view h4
{
  line-height: <?php echo $base; ?>px;
}
.options .tb_radio_row.tb_style_2 label,
.options .tb_checkbox_row.tb_style_2 label
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin: <?php echo $base / 2; ?>px <?php echo $base / 2; ?>px 0 0;
  <?php else: ?>
  margin: <?php echo $base / 2; ?>px 0 0 <?php echo $base / 2; ?>px;
  <?php endif; ?>
  height: <?php echo $form_control_height; ?>px;
  line-height: <?php echo $form_control_height; ?>px;
}
.options .tb_radio_row.tb_style_2 label span,
.options .tb_checkbox_row.tb_style_2 label span
{
  display: none;
}

.picker-switch,
.ui-datepicker-title
{
  font-size: <?php echo min((isset($tbData->fonts['h2']['size']) ? $tbData->fonts['h2']['size'] : 16),20); ?>px !important;
}