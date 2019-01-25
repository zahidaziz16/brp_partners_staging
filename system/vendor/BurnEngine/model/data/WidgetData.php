<?php

require_once dirname(__FILE__) . '/BuilderData.php';

class WidgetData extends BuilderData
{
    public static function getBoxStyleVars()
    {
        $default_vars = array(
            'layout' => array(
                'extra_class'      => '',
                'display'          => 'block',
                'margin_top'       => 0,
                'margin_bottom'    => 0,
                'margin_left'      => 0,
                'margin_right'     => 0,
                'margin_rtl_mode'  => 1,
                'padding_top'      => 0,
                'padding_left'     => 0,
                'padding_right'    => 0,
                'padding_bottom'   => 0,
                'padding_rtl_mode' => 1
            ),
            'box_shadow' => array(
                'rows' => array()
            ),
            'background' => array(
                'solid_color' => '',
                'solid_color_opacity' => 100,
                'rows' => array()
            ),
            'border' => array(
                'top'      => array(),
                'right'    => array(),
                'bottom'   => array(),
                'left'     => array(),
                'rtl_mode' => 1
            ),
            'border_radius' => array(
                'top_left'     => 0,
                'top_right'    => 0,
                'bottom_right' => 0,
                'bottom_left'  => 0,
                'rtl_mode'     => 1
            ),
            'colors' => array()
        );

        $theme_config = TB_Engine::instance()->getThemeConfig();
        $theme_config_title_style_vars = isset($theme_config['widget_box_style_vars']) ? $theme_config['widget_box_style_vars'] : array();

        return array_replace_recursive($default_vars, $theme_config_title_style_vars);
    }

    public static function getTitleStyleVars()
    {
        $default_vars = array(
            'layout' => array(
                'margin_top'       => 0,
                'margin_bottom'    => 20,
                'margin_left'      => 0,
                'margin_right'     => 0,
                'margin_rtl_mode'  => 1,
                'padding_top'      => 0,
                'padding_left'     => 0,
                'padding_right'    => 0,
                'padding_bottom'   => 0,
                'padding_rtl_mode' => 1
            ),
            'box_shadow' => array(
                'rows' => array()
            ),
            'background' => array(
                'solid_color' => '',
                'solid_color_opacity' => 100,
                'rows' => array()
            ),
            'border' => array(
                'top'      => array(),
                'right'    => array(),
                'bottom'   => array(),
                'left'     => array(),
                'rtl_mode' => 1
            ),
            'border_radius' => array(
                'top_left'     => 0,
                'top_right'    => 0,
                'bottom_right' => 0,
                'bottom_left'  => 0,
                'rtl_mode'     => 1
            ),
            'colors' => array()
        );

        $theme_config = TB_Engine::instance()->getThemeConfig();
        $theme_config_title_style_vars = isset($theme_config['widget_title_style_vars']) ? $theme_config['widget_title_style_vars'] : array();

        return array_replace_recursive($default_vars, $theme_config_title_style_vars);
    }

    public static function getColors($style_section_id)
    {
        return call_user_func(__CLASS__.'::get' . $style_section_id . 'Colors');
    }

    public static function getFonts($style_section_id)
    {
        return call_user_func(__CLASS__.'::get' . $style_section_id . 'Fonts');
    }

    protected static function getBoxFonts()
    {
        return array(
            'body' => array(
                'section_name'      => 'Body',
                'elements'          => '',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 13,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_spacing'       => false,
                'has_effects'       => false,
                'show_built_styles' => false,
                'multiple_variants' => true
            ),
            'h1' => array(
                'section_name'      => 'H1',
                'elements'          => 'h1, .h1',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 26,
                'line-height'       => 30,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => ''
            ),
            'h2' => array(
                'section_name'      => 'H2',
                'elements'          => 'h2:not(.panel-heading):not(.box-heading), .h2',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 16,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => ''
            ),
            'h3' => array(
                'section_name'      => 'H3',
                'elements'          => 'h3, .h3',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 15,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => ''
            ),
            'h4' => array(
                'section_name'      => 'H4',
                'elements'          => 'h4, .h4',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => ''
            )
        );
    }

    protected static function getTitleFonts()
    {
        return array(
            'title' => array(
                'section_name'      => 'Box Title',
                'elements'          => '.panel-heading, .box-heading, .tb_slider_controls > a',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 18,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => ''
            )
        );
    }

    protected static function getBoxColors()
    {
        return array(
            'body' => array(
                '_label' => 'Body',
                'accent' => array(
                    'label'       => 'Accent',
                    'elements'    => '
                        .tb_main_color,
                        .tb_main_color_hover:hover,
                        .tb_list_1 > li:before,
                        .tb_list_1 > li > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                ),
                'accent_hover' => array(
                    'label'       => 'Accent (hover)',
                    'elements'    => '
                        a.tb_main_color:hover,
                        .tb_main_color_hover:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                ),
                'accent_bg' => array(
                    'label'       => 'Accent bg',
                    'elements'    => '
                        .tb_main_color_bg,
                        .tb_hover_main_color_bg:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'body.accent'
                ),
                'accent_bg_hover' => array(
                    'label'       => 'Accent bg (hover)',
                    'elements'    => '
                        a.tb_main_color_bg:hover,
                        .tb_main_color_bg_hover:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'body.accent_hover'
                ),
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'text_links' => array(
                    'label'       => 'Links in text',
                    'elements'    => '
                        p a:not(:hover),
                        .tb_text_wrap a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text_links'
                ),
                'text_links_hover' => array(
                    'label'       => 'Links in text (hover)',
                    'elements'    => '
                        .tb_text_wrap a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text_links_hover'
                ),
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'h1' => array(
                    'label'       => 'H1',
                    'elements'    => '
                        h1, .h1
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                ),
                'h2' => array(
                    'label'       => 'H2',
                    'elements'    => '
                        h2, .h2
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                ),
                'h3' => array(
                    'label'       => 'H3',
                    'elements'    => '
                        h3, .h3
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                ),
                'h4' => array(
                    'label'       => 'H4',
                    'elements'    => '
                        h4, .h4
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                )
            )
        );
    }

    protected static function getTitleColors()
    {
        return array(
            'title' => array(
                '_label' => '',
                'accent' => array(
                    'label'       => 'Block title',
                    'elements'    => '
                        .panel-heading,
                        .box-heading
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                ),
                'icon' => array(
                    'label'       => 'Title icon',
                    'elements'    => '
                        .panel-heading .tb_icon,
                        .box-heading .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'title.accent'
                )
            )
        );
    }
}