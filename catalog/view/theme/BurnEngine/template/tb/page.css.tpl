<?php
$lang_dir            = $tbData->language_direction;
$base                = $tbData->fonts['body']['line-height'];
$base_font_size      = $tbData->fonts['body']['size'];
$base_h2_size        = isset($tbData->fonts['h2']['size']) ? $tbData->fonts['h2']['size'] : 16;
$submit_button_height  = 36;
$color_bg_str_1      = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.05)';
$color_bg_str_2      = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.1)';
$color_bg_str_3      = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.2)';
$color_bg_str_4      = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.3)';
$color_text_str_1    = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.2)';
$color_text_str_2    = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.3)';
$color_text_str_3    = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.4)';
$color_text_str_4    = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.6)';
$color_text_str_5    = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.8)';
$color_border_str_1  = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.1)';
$color_border_str_2  = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.2)';
$color_border_str_3  = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.4)';
$color_border_str_4  = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.6)';
$site_width          = array(1000, 1200);
$breakpoints         = array('xs', 'sm', 'md', 'lg');
$grid_columns        = 12;
$grid_block_columns  = 12;
$grid_gutter         = array(0, 10, 20, 30, 40, 50);
$default_grid_gutter = 3;
$screen_xs           = 480;
$screen_sm           = 768;
$screen_md           = 1040;
$screen_lg           = 1260;
$left_col_metric     = !empty($tbData->content_layout_settings['left_column_width_metric'])  ? $tbData->content_layout_settings['left_column_width_metric']  : 'px';
$right_col_metric    = !empty($tbData->content_layout_settings['right_column_width_metric']) ? $tbData->content_layout_settings['right_column_width_metric'] : 'px';
$left_col_width      = $tbData->content_layout_settings['left_column_width']  . $left_col_metric;
$left_col_width     .= !$tbData->content_layout_settings['separate_columns'] && $left_col_metric  == '%' ? ' - ' . $tbData->content_layout_settings['columns_gutter'] . 'px' : '';
$right_col_width     = $tbData->content_layout_settings['right_column_width'] . $right_col_metric;
$right_col_width    .= !$tbData->content_layout_settings['separate_columns'] && $right_col_metric == '%' ? ' - ' . $tbData->content_layout_settings['columns_gutter'] . 'px' : '';
$main_width          = '100%';
$main_width         .= $tbData->hasArea('column_left')  ?  ' - ' . $tbData->content_layout_settings['left_column_width']  . $left_col_metric  : '';
$main_width         .= $tbData->hasArea('column_right') ?  ' - ' . $tbData->content_layout_settings['right_column_width'] . $right_col_metric : '';
$main_width         .= !$tbData->content_layout_settings['separate_columns'] && $left_col_metric  == 'px' && $tbData->hasArea('column_left')  ?  ' - ' . $tbData->content_layout_settings['columns_gutter'] . 'px' : '';
$main_width         .= !$tbData->content_layout_settings['separate_columns'] && $right_col_metric == 'px' && $tbData->hasArea('column_right') ?  ' - ' . $tbData->content_layout_settings['columns_gutter'] . 'px' : '';
$main_width         .= !$tbData->content_layout_settings['separate_columns'] ? ' - ' . $tbData->content_layout_settings['columns_gutter'] . 'px' : '';

$left_col_metric_md  = !empty($tbData->content_layout_settings['left_column_width_md'])  ? $tbData->content_layout_settings['left_column_width_metric_md']  : $left_col_metric;
$right_col_metric_md = !empty($tbData->content_layout_settings['right_column_width_md']) ? $tbData->content_layout_settings['right_column_width_metric_md'] : $right_col_metric;
$left_col_val_md     = !empty($tbData->content_layout_settings['left_column_width_md'])  ? $tbData->content_layout_settings['left_column_width_md']  : $tbData->content_layout_settings['left_column_width'];
$right_col_val_md    = !empty($tbData->content_layout_settings['right_column_width_md']) ? $tbData->content_layout_settings['right_column_width_md'] : $tbData->content_layout_settings['right_column_width'];
$left_col_width_md   = $left_col_val_md  . $left_col_metric_md;
$left_col_width_md  .= !$tbData->content_layout_settings['separate_columns'] && $left_col_metric_md  == '%' ? ' - ' . min($tbData->content_layout_settings['columns_gutter'], 30) . 'px' : '';
$right_col_width_md  = $right_col_val_md . $right_col_metric_md;
$right_col_width_md .= !$tbData->content_layout_settings['separate_columns'] && $right_col_metric_md == '%' ? ' - ' . min($tbData->content_layout_settings['columns_gutter'], 30) . 'px' : '';
$main_width_md       = '100%';
$main_width_md      .= $tbData->hasArea('column_left')  ?  ' - ' . $left_col_val_md  . $left_col_metric_md  : '';
$main_width_md      .= $tbData->hasArea('column_right') ?  ' - ' . $right_col_val_md . $right_col_metric_md : '';
$main_width_md      .= !$tbData->content_layout_settings['separate_columns'] && $left_col_metric_md  == 'px' && $tbData->hasArea('column_left')  ?  ' - ' . min($tbData->content_layout_settings['columns_gutter'], 30) . 'px' : '';
$main_width_md      .= !$tbData->content_layout_settings['separate_columns'] && $right_col_metric_md == 'px' && $tbData->hasArea('column_right') ?  ' - ' . min($tbData->content_layout_settings['columns_gutter'], 30) . 'px' : '';
$main_width_md      .= !$tbData->content_layout_settings['separate_columns'] ? ' - ' . min($tbData->content_layout_settings['columns_gutter'], 30) . 'px' : '';

$left_col_metric_sm  = !empty($tbData->content_layout_settings['left_column_width_sm'])  ? $tbData->content_layout_settings['left_column_width_metric_sm']  : $left_col_metric;
$right_col_metric_sm = !empty($tbData->content_layout_settings['right_column_width_sm']) ? $tbData->content_layout_settings['right_column_width_metric_sm'] : $right_col_metric;
$left_col_val_sm     = !empty($tbData->content_layout_settings['left_column_width_sm'])  ? $tbData->content_layout_settings['left_column_width_sm']  : $tbData->content_layout_settings['left_column_width'];
$right_col_val_sm    = !empty($tbData->content_layout_settings['right_column_width_sm']) ? $tbData->content_layout_settings['right_column_width_sm'] : $tbData->content_layout_settings['right_column_width'];
$left_col_width_sm   = $left_col_val_sm  . $left_col_metric_sm;
$left_col_width_sm  .= !$tbData->content_layout_settings['separate_columns'] && $left_col_metric_sm  == '%' ? ' - ' . min($tbData->content_layout_settings['columns_gutter'], 30) . 'px' : '';
$right_col_width_sm  = $right_col_val_sm . $right_col_metric_sm;
$right_col_width_sm .= !$tbData->content_layout_settings['separate_columns'] && $right_col_metric_sm == '%' ? ' - ' . min($tbData->content_layout_settings['columns_gutter'], 30) . 'px' : '';
$main_width_sm       = '100%';
$main_width_sm      .= $tbData->hasArea('column_left')  ?  ' - ' . $left_col_val_sm  . $left_col_metric_sm  : '';
$main_width_sm      .= $tbData->hasArea('column_right') ?  ' - ' . $right_col_val_sm . $right_col_metric_sm : '';
$main_width_sm      .= !$tbData->content_layout_settings['separate_columns'] && $left_col_metric_sm  == 'px' && $tbData->hasArea('column_left')  ?  ' - ' . min($tbData->content_layout_settings['columns_gutter'], 30) . 'px' : '';
$main_width_sm      .= !$tbData->content_layout_settings['separate_columns'] && $right_col_metric_sm == 'px' && $tbData->hasArea('column_right') ?  ' - ' . min($tbData->content_layout_settings['columns_gutter'], 30) . 'px' : '';
$main_width_sm      .= !$tbData->content_layout_settings['separate_columns'] ? ' - ' . min($tbData->content_layout_settings['columns_gutter'], 30) . 'px' : '';
?>

/*****************************************************/
/******   P A G E    S T Y L E S   *******************/
/*****************************************************/

<?php if ($tbData->body_background['image']): ?>
body {
  background-image: url("<?php echo $tbData->base_httpsIf . 'image/' . $tbData->body_background['image']; ?>");
  background-repeat: <?php echo $tbData->body_background['repeat']; ?>;
  <?php if ($tbData->body_background['position'] == 'custom'): ?>
  background-position: <?php echo $tbData->body_background['position_x']; ?><?php echo $tbData->body_background['position_x_metric']; ?> <?php echo $tbData->body_background['position_y']; ?><?php echo $tbData->body_background['position_y_metric']; ?>;
  <?php else: ?>
  background-position: <?php echo $tbData->body_background['position']; ?>;
  <?php endif; ?>
  background-attachment: <?php echo $tbData->body_background['attachment']; ?>;
  <?php if ($tbData->body_background['size'] == 'custom'): ?>
  background-size: <?php echo $tbData->body_background['size_x']; ?><?php echo $tbData->body_background['size_x'] != 'auto' ? $tbData->body_background['size_x_metric'] : ''; ?> <?php echo $tbData->body_background['size_y']; ?><?php echo $tbData->body_background['size_y'] != 'auto' ? $tbData->body_background['size_y_metric'] : ''; ?>;
  <?php else: ?>
  background-size: <?php echo $tbData->body_background['size']; ?>;
  <?php endif; ?>
}
<?php endif; ?>
<?php echo $scoped_colors_css; ?>
<?php echo $fonts_css; ?>
<?php echo $plugins_css; ?>

<?php if ($tbData->hasArea('column_left')): ?>
#content > .row {
            -ms-flex-pack: end;
  -webkit-justify-content: flex-end;
          justify-content: flex-end;
}
<?php endif; ?>

@media (min-width: <?php echo ($screen_sm + 1). 'px'; ?>) {
  <?php if (($left_col_val_sm != 100 && $left_col_metric_sm != '%') && ($right_col_val_sm != 100 && $right_col_metric_sm != '%')): ?>
  .main {
    max-width: calc(<?php echo $main_width_sm; ?>) !important;
  }
  <?php endif; ?>
  <?php if ($tbData->hasArea('column_left')): ?>
  #left_col {
    width:     calc(<?php echo $left_col_width_sm; ?>) !important;
    max-width: calc(<?php echo $left_col_width_sm; ?>) !important;
    flex: 0 0  calc(<?php echo $left_col_width_sm; ?>) !important;
  }
  <?php endif; ?>
  <?php if ($tbData->hasArea('column_right')): ?>
  #right_col {
    width:     calc(<?php echo $right_col_width_sm; ?>) !important;
    max-width: calc(<?php echo $right_col_width_sm; ?>) !important;
    flex: 0 0  calc(<?php echo $right_col_width_sm; ?>) !important;
  }
  <?php endif; ?>
}

@media (min-width: <?php echo ($screen_md + 1). 'px'; ?>) {
  <?php if (($left_col_val_md != 100 && $left_col_metric_md != '%') && ($right_col_val_md != 100 && $right_col_metric_md != '%')): ?>
  .main {
    max-width: calc(<?php echo $main_width_md; ?>) !important;
  }
  <?php endif; ?>
  <?php if ($tbData->hasArea('column_left')): ?>
  #left_col {
    width:     calc(<?php echo $left_col_width_md; ?>) !important;
    max-width: calc(<?php echo $left_col_width_md; ?>) !important;
    flex: 0 0  calc(<?php echo $left_col_width_md; ?>) !important;
  }
  <?php endif; ?>
  <?php if ($tbData->hasArea('column_right')): ?>
  #right_col {
    width:     calc(<?php echo $right_col_width_md; ?>) !important;
    max-width: calc(<?php echo $right_col_width_md; ?>) !important;
    flex: 0 0  calc(<?php echo $right_col_width_md; ?>) !important;
  }
  <?php endif; ?>
}
@media (min-width: <?php echo ($screen_lg + 1). 'px'; ?>) {
  .main {
    max-width: calc(<?php echo $main_width; ?>) !important;
  }
  <?php if ($tbData->hasArea('column_left')): ?>
  #left_col {
    width:     calc(<?php echo $left_col_width; ?>) !important;
    max-width: calc(<?php echo $left_col_width; ?>) !important;
    flex: 0 0  calc(<?php echo $left_col_width; ?>) !important;
  }
  <?php endif; ?>
  <?php if ($tbData->hasArea('column_right')): ?>
  #right_col {
    width:     calc(<?php echo $right_col_width; ?>) !important;
    max-width: calc(<?php echo $right_col_width; ?>) !important;
    flex: 0 0  calc(<?php echo $right_col_width; ?>) !important;
  }
  <?php endif; ?>
}

<?php if ($tbData->hasArea('column_left') || $tbData->hasArea('column_right')): ?>
@media (min-width: <?php echo ($screen_sm + 1). 'px'; ?>) and (max-width: <?php echo ($screen_md). 'px'; ?>) {
  <?php if ($left_col_width_sm == '100%'): ?>
  .row.tb_separate_columns > #left_col {
    border-top-width: 1px;
    border-top-style: solid;
    border-left-style: none;
    border-left-width: 0;
    border-right-style: none;
    border-right-width: 0;
  }
  .main {
        -ms-order: 1;
    -webkit-order: 1;
            order: 1;
  }
  #left_col {
        -ms-order: 2;
    -webkit-order: 2;
            order: 2;
  }
  <?php endif; ?>
  <?php if ($right_col_width_sm == '100%'): ?>
  .row.tb_separate_columns > #right_col {
    border-top-width: 1px;
    border-top-style: solid;
    border-left-style: none;
    border-left-width: 0;
    border-right-style: none;
    border-right-width: 0;
  }
  <?php endif; ?>
}

@media (min-width: <?php echo ($screen_md + 1). 'px'; ?>) and (max-width: <?php echo ($screen_lg). 'px'; ?>) {
  <?php if ($left_col_width_md == '100%'): ?>
  .row.tb_separate_columns > #left_col {
    border-top-style: 1px;
    border-top-width: solid;
    border-left-style: none;
    border-left-width: none;
    border-right-style: none;
    border-right-width: none;
  }
  .main {
        -ms-order: 1;
    -webkit-order: 1;
            order: 1;
  }
  #left_col {
        -ms-order: 2;
    -webkit-order: 2;
            order: 2;
  }
  <?php endif; ?>
  <?php if ($right_col_width_md == '100%'): ?>
  .row.tb_separate_columns > #right_col {
    border-top-style: 1px;
    border-top-width: solid;
    border-left-style: none;
    border-left-width: none;
    border-right-style: none;
    border-right-width: none;
  }
  <?php endif; ?>
}
<?php endif; ?>

<?php if ($tbData->common['custom_css']): ?>

/*****************************************************/
/******   C U S T O M    S T Y L E S   ***************/
/*****************************************************/

<?php echo html_entity_decode($tbData->common['custom_css'], ENT_COMPAT, 'UTF-8'); ?>

<?php endif; ?>