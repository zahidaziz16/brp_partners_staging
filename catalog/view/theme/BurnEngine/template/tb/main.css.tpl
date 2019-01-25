/*  -----------------------------------------------------------------------------------------
    V A R S
-----------------------------------------------------------------------------------------  */

<?php
$lang_dir                = $tbData->language_direction;
$base                    = $tbData->fonts['body']['line-height'];
$base_font_size          = $tbData->fonts['body']['size'];
$base_h2_size            = isset($tbData->fonts['h2']['size']) ? $tbData->fonts['h2']['size'] : 16;
$base_button_size        = isset($tbData->fonts['buttons']['size']) ? $tbData->fonts['buttons']['size'] : $base_font_size;
$form_border_width       = isset($tbData->theme_config['form_border_width']) ? $tbData->theme_config['form_border_width'] : 1;
$form_control_height     = round($base                * (isset($tbData->theme_config['form_control_height'])     ? $tbData->theme_config['form_control_height']     : 1.50) * 0.5) * 2;
$form_control_height_xs  = round($form_control_height * (isset($tbData->theme_config['form_control_height_xs'])  ? $tbData->theme_config['form_control_height_xs']  : 0.50) * 0.5) * 2;
$form_control_height_sm  = round($form_control_height * (isset($tbData->theme_config['form_control_height_sm'])  ? $tbData->theme_config['form_control_height_sm']  : 0.75) * 0.5) * 2;
$form_control_height_lg  = round($form_control_height * (isset($tbData->theme_config['form_control_height_lg'])  ? $tbData->theme_config['form_control_height_lg']  : 1.25) * 0.5) * 2;
$form_control_height_xl  = round($form_control_height * (isset($tbData->theme_config['form_control_height_xl'])  ? $tbData->theme_config['form_control_height_xl']  : 1.50) * 0.5) * 2;
$form_control_height_xxl = round($form_control_height * (isset($tbData->theme_config['form_control_height_xxl']) ? $tbData->theme_config['form_control_height_xxl'] : 1.75) * 0.5) * 2;
$submit_button_height    = round($form_control_height * (isset($tbData->theme_config['submit_button_height'])    ? $tbData->theme_config['submit_button_height']    : 1.25) * 0.5) * 2;
$mobile_menu_padding     = $tbData->style['mobile_menu_padding'];
$main_color              = $tbData->colors['main']['accent']['color'];
$color_bg_str_1          = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.05)';
$color_bg_str_2          = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.1)';
$color_bg_str_3          = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.15)';
$color_bg_str_4          = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.2)';
$color_bg_str_5          = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.3)';
$color_text_str_1        = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.2)';
$color_text_str_2        = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.3)';
$color_text_str_3        = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.4)';
$color_text_str_4        = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.6)';
$color_text_str_5        = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.8)';
$color_border_str_1      = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.1)';
$color_border_str_2      = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.2)';
$color_border_str_3      = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.4)';
$color_border_str_4      = 'rgba(' . TB_Utils::hex2rgb($tbData->colors['main']['text']['color'], true) . ', 0.6)';
$width                   = $tbData->style['maximum_width'];
$breakpoints             = array('xs', 'sm', 'md', 'lg');
$grid_columns            = 12;
$grid_block_columns      = 12;
$grid_gutter             = array(0, 10, 20, 30, 40, 50);
$default_grid_gutter     = 3;
$screen_xs               = 480;
$screen_sm               = 768;
$screen_md               = 1040;
$screen_lg               = 1260;
?>

/*  -----------------------------------------------------------------------------------------
    E X T E R N A L
-----------------------------------------------------------------------------------------  */

<?php echo $external_css; ?>

/*  -----------------------------------------------------------------------------------------
    C O R E
-----------------------------------------------------------------------------------------  */

<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/normalize.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/type.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/grid.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/tables.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/forms.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/buttons.css.tpl'); ?>

.ie9_divide_hook{}

/*  -----------------------------------------------------------------------------------------
    B O O T S T R A P   C O M P O N E N T S
-----------------------------------------------------------------------------------------  */

<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/dropdowns.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/button-groups.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/input-groups.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/navs.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/pagination.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/alerts.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/badges.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/panels.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/thumbnails.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/labels.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/component-animations.css.tpl'); ?>

.ie9_divide_hook{}

/*  -----------------------------------------------------------------------------------------
    B O O T S T R A P   J S   C O M P O N E N T S
-----------------------------------------------------------------------------------------  */

<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/modals.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/tooltip.css.tpl'); ?>

/*  -----------------------------------------------------------------------------------------
    U T I L I T I E S
-----------------------------------------------------------------------------------------  */

<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/utilities.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/bootstrap/responsive-utilities.css.tpl'); ?>

/*  -----------------------------------------------------------------------------------------
    O P E N C A R T
-----------------------------------------------------------------------------------------  */
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/opencart.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/opencart-2.0.css.tpl'); ?>

.ie9_divide_hook{}

/*  -----------------------------------------------------------------------------------------
    B U R N   E N G I N E   C O M P O N E N T S
-----------------------------------------------------------------------------------------  */

<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/accordion.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/countdown.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/hover-effects.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/preloader.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/rating.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/listing.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/carousel.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/listing-styles.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/products.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/tags.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/system-blocks.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/builder-blocks.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/scroll-to-top.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/stories.css.tpl'); ?>

.ie9_divide_hook{}

/*  -----------------------------------------------------------------------------------------
    J Q U E R Y   P L U G I N S
-----------------------------------------------------------------------------------------  */

<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/jquery/touchspin.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/jquery/jquery-noty.css.tpl'); ?>

/*  -----------------------------------------------------------------------------------------
    P R E S E T S
-----------------------------------------------------------------------------------------  */

<?php echo $presets_css; ?>

/*  -----------------------------------------------------------------------------------------
    B U R N   E N G I N E   S T Y L E
-----------------------------------------------------------------------------------------  */

<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/sticky-header.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/colors.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/typography.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/header.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/theme.css.tpl'); ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_catalog_template_dir . '/tb/css/theme-responsive.css.tpl'); ?>

/*  -----------------------------------------------------------------------------------------
    T H E M E   S T Y L E
-----------------------------------------------------------------------------------------  */

<?php if (is_file($tbData->theme_dir . '/styles.css.tpl')): ?>
<?php require TB_Utils::vqmodCheck($tbData->theme_dir . '/styles.css.tpl'); ?>
<?php endif; ?>
