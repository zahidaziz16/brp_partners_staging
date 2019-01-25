.tb_tags.tb_style_label {
  position: relative;
  margin-top: -<?php echo $base * 0.5; ?>px;
}
.tb_tags.tb_style_label li {
  display: inline-block;
  margin: <?php echo $base * 0.5; ?>px <?php echo $base * 0.5; ?>px 0 <?php echo $base * 0.5; ?>px;
  vertical-align: top;
}
.tb_tags.tb_style_label a {
  position: relative;
  display: block;
  float: left;
  line-height: <?php echo $base; ?>px;
  white-space: nowrap;
  font-size: 11px;
  padding: 0 <?php echo $base * 0.5; ?>px 0;
  border-radius: 1px;
}
.tb_tags.tb_style_label a:before,
.tb_tags.tb_style_label a:after {
  content: '';
  position: absolute;
  -webkit-transition: border-color 0.4s;
  transition: border-color 0.4s;
}
.tb_tags.tb_style_label a:before {
  height: 2px;
  <?php if ($lang_dir == 'ltr'): ?>
  left: -<?php echo $base * 0.5; ?>px;
  border-right: <?php echo $base * 0.5; ?>px solid;
  <?php else: ?>
  right: -<?php echo $base * 0.5; ?>px;
  border-left: <?php echo $base * 0.5; ?>px solid;
  <?php endif; ?>
  border-top: <?php echo $base * 0.5 - 1; ?>px solid transparent !important;
  border-bottom: <?php echo $base * 0.5 - 1; ?>px solid transparent !important;
}
.tb_tags.tb_style_label a:after {
  top: 50%;
  <?php if ($lang_dir == 'ltr'): ?>
  left: -2px;
  <?php else: ?>
  right: -2px;
  <?php endif; ?>
  width: <?php echo $base * 0.25; ?>px;
  height: <?php echo $base * 0.25; ?>px;
  margin-top: -<?php echo $base * 0.175; ?>px;
  background: #fff;
  border-radius: 50%;
}

/*** Inline tags ***/

.tb_content_inline .tb_tags.tb_style_label {
  top: 1px;
}
