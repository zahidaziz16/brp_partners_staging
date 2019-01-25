<?php

require_once dirname(__FILE__) . '/BuilderData.php';
require_once dirname(__FILE__) . '/MainFontsData.php';

class AreaItemData extends BuilderData
{
    public static function getAreaStyleVars($type)
    {
        $default_vars = array(
            'layout' => array(
                'type'                         => 'full',
                'margin_top'                   => 0,
                'margin_bottom'                => 30,
                'margin_right'                 => 0,
                'margin_left'                  => 0,
                'margin_rtl_mode'              => 1,
                'padding_top'                  => 0,
                'padding_bottom'               => 0,
                'padding_right'                => 0,
                'padding_left'                 => 0,
                'padding_rtl_mode'             => 1,
                'inner_padding'                => 30,
                'columns_gutter'               => 30,
                'columns_rtl_mode'             => 1,
                'left_column_width'            => 160,
                'left_column_width_md'         => 0,
                'left_column_width_sm'         => 0,
                'left_column_width_metric'     => 'px',
                'left_column_width_metric_md'  => 'px',
                'left_column_width_metric_sm'  => 'px',
                'right_column_width'           => 240,
                'right_column_width_md'        => 0,
                'right_column_width_sm'        => 0,
                'right_column_width_metric'    => 'px',
                'right_column_width_metric_md' => 'px',
                'right_column_width_metric_sm' => 'px',
                'separate_columns'             => 0,
                'extra_class'                  => ''
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
            'colors' => array(),
            'font'   => self::getFonts()
        );

        foreach ($default_vars['font'] as $key => $value) {
            $default_vars['font'][$key] = array_merge(MainFontsData::getDefaultFontItem(), $value);
        }

        if ($type == 'area_content') {
            $default_vars['layout']['left_column_is_sticky']      = 0;
            $default_vars['layout']['left_column_sticky_offset']  = 0;
            $default_vars['layout']['right_column_is_sticky']     = 0;
            $default_vars['layout']['right_column_sticky_offset'] = 0;
        }

        return $default_vars;
    }

    public static function getRowStyleVars()
    {
        $default_vars = array(
            'layout' => array(
                'type'             => 'full_fixed',
                'margin_top'       => 0,
                'margin_bottom'    => 30,
                'margin_right'     => 0,
                'margin_left'      => 0,
                'margin_rtl_mode'  => 1,
                'padding_top'      => 0,
                'padding_bottom'   => 0,
                'padding_right'    => 0,
                'padding_left'     => 0,
                'padding_rtl_mode' => 1,
                'inner_padding'    => 30,
                'columns_gutter'   => 30,
                'columns_rtl_mode' => 1,
                'merge_columns'    => 1,
                'separate_columns' => 0,
                'extra_class'      => '',
                'sticky_columns'   => 'none',
                'sticky_offset'    => 0
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
            'colors'    => array(),
            'font'      => self::getFonts(),
            'preset_id' => ''
        );

        foreach ($default_vars['font'] as $key => $value) {
            $default_vars['font'][$key] = array_merge(MainFontsData::getDefaultFontItem(), $value);
        }

        $theme_config = TB_Engine::instance()->getThemeConfig();
        $theme_config_title_style_vars = isset($theme_config['row_style_vars']) ? $theme_config['row_style_vars'] : array();

        return array_replace_recursive($default_vars, $theme_config_title_style_vars);
    }

    public static function getColumnStyleVars($grid_proportion)
    {
        $default_vars = array(
            'layout' => array(
                'height'           => 0,
                'padding_top'      => 0,
                'padding_bottom'   => 0,
                'padding_right'    => 0,
                'padding_left'     => 0,
                'padding_rtl_mode' => 1,
                'inner_padding'    => 30,
                'align'            => 'default',
                'valign'           => 'top',
                'inherit_padding'  => 0,
                'is_sticky'        => 0,
                'sticky_offset'    => 0,
                'extra_class'      => '',

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
            'colors' => array(),
            'font'   => self::getFonts(),
            'viewport' => array(
                'xs' => array(
                    'layout' => array(
                        'grid_proportion' => '1_1',
                        'column_order'    => 'default',
                    )
                ),
                'sm' => array(
                    'layout' => array(
                        'grid_proportion' => $grid_proportion,
                        'column_order'    => 'default',
                    )
                ),
                'md' => array(
                    'layout' => array(
                        'grid_proportion' => $grid_proportion,
                        'column_order'    => 'default',
                    )
                )
            )
        );

        foreach ($default_vars['font'] as $key => $value) {
            $default_vars['font'][$key] = array_merge(MainFontsData::getDefaultFontItem(), $value);
        }

        $theme_config = TB_Engine::instance()->getThemeConfig();
        $theme_config_title_style_vars = isset($theme_config['column_style_vars']) ? $theme_config['column_style_vars'] : array();

        return array_replace_recursive($default_vars, $theme_config_title_style_vars);
    }

    public static function getBackgroundGradientRow()
    {
        $result = parent::getBackgroundGradientRow();

        $result['container'] = 'row';

        return $result;
    }

    public static function getBackgroundImageRow()
    {
        $result = parent::getBackgroundImageRow();

        $result['container'] = 'row';

        return $result;
    }

    public static function getFonts()
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
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => false,
                'has_effects'       => false,
                'show_built_styles' => false,
                'multiple_variants' => true,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
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
                'transform'         => '',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'h2' => array(
                'section_name'      => 'H2 / Block & module title / Tabs & accordion navigation',
                'elements'          => '
                    h2,
                    .h2,
                    legend,
                    .box-heading,
                    .panel-heading,
                    .checkout-heading,
                    .modal-title,
                    .nav-tabs > li,
                    .picker-switch,
                    .tb_accordion_content > .tb_title,
                    .ui-accordion-header,
                    .ui-datepicker-title,
                    .ui-dialog-title,
                    .tb_slider_controls
                    ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 16,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
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
                'transform'         => '',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'h4' => array(
                'section_name'      => 'H4',
                'elements'          => 'h4, .h4 ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            )
        );
    }

    public static function getAreaColors($area_name)
    {
        $default_colors = array(
            'body' => array(
                '_label' => 'Body',
                'accent' => array(
                    'label'       => 'Accent',
                    'elements'    => '
                        .tb_main_color,
                        .tb_hover_main_color:hover,
                        .colorbox, .agree,
                        .tb_list_1 > li:before,
                        .tb_list_1 > li > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.accent'
                ),
                'accent_hover' => array(
                    'label'       => 'Accent (hover)',
                    'elements'    => '
                        a.tb_main_color:hover,
                        a.colorbox:hover,
                        a.agree:hover,
                        .tb_main_color_hover:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.accent_hover'
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
                    'inherit_key' => 'theme:main.text'
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
                    'inherit_key' => 'theme:main.links'
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
                    'inherit_key' => 'theme:main.links_hover'
                ),
                'text_links' => array(
                    'label'       => 'Links in text',
                    'elements'    => '
                        .tb_text_wrap a:not(.btn):not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.text_links'
                ),
                'text_links_hover' => array(
                    'label'       => 'Links in text (hover)',
                    'elements'    => '
                        .tb_text_wrap a:not(.btn):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.text_links_hover'
                ),
                'titles' => array(
                    'label'       => 'Titles',
                    'elements'    => '
                        h1,
                        h2,
                        h3,
                        h4,
                        h5,
                        h6,
                        .h1,
                        .h2,
                        .h3,
                        .h4,
                        .h5,
                        .h6,
                        legend,
                        .panel-heading,
                        .box-heading
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.titles'
                ),
                'column_border' => array(
                    'label'       => 'Column border',
                    'elements'    => '
                        > .tb_separate_columns > [class*="col-"],
                        hr
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.column_border'
                ),
                'subtle_base' => array(
                    'label'       => 'Subtle color base',
                    'elements'    => '',
                    'property'    => 'subtle',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.subtle_base'
                ),
            ),
            'forms' => array(
                '_label' => 'Form elements',
                'input_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        input:not(:hover):not(:focus),
                        select:not(:hover):not(:focus),
                        textarea:not(:hover):not(:focus),
                        .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_text'
                ),
                'input_bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        input:not(:hover):not(:focus),
                        select:not(:hover):not(:focus),
                        textarea:not(:hover):not(:focus),
                        .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_bg'
                ),
                'input_border_top_left' => array(
                    'label'       => 'Top/left border',
                    'elements'    => '
                        input:not(:hover):not(:focus),
                        select:not(:hover):not(:focus),
                        textarea:not(:hover):not(:focus),
                        .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_top_left'
                ),
                'input_border_bottom_right' => array(
                    'label'       => 'Bottom/right border',
                    'elements'    => '
                        input:not(:hover):not(:focus),
                        select:not(:hover):not(:focus),
                        textarea:not(:hover):not(:focus),
                        .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_bottom_right'
                ),
                'input_text_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '
                        input:hover:not(:focus),
                        select:hover:not(:focus),
                        textarea:hover:not(:focus),
                        .input-group:hover:not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_text_hover'
                ),
                'input_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '
                        input:hover:not(:focus),
                        select:hover:not(:focus),
                        textarea:hover:not(:focus),
                        .input-group:hover:not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_bg_hover'
                ),
                'input_border_top_left_hover' => array(
                    'label'       => 'Top/left border (hover)',
                    'elements'    => '
                        input:hover:not(:focus),
                        select:hover:not(:focus),
                        textarea:hover:not(:focus),
                        .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_top_left_hover'
                ),
                'input_border_bottom_right_hover' => array(
                    'label'       => 'Bottom/right border (hover)',
                    'elements'    => '
                        input:hover:not(:focus),
                        select:hover:not(:focus),
                        textarea:hover:not(:focus),
                        .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_bottom_right_hover'
                ),
                'input_text_focus' => array(
                    'label'       => 'Text (focus)',
                    'elements'    => '
                        input:focus,
                        select:focus,
                        textarea:focus,
                        .input-group:focus
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_text_focus'
                ),
                'input_bg_focus' => array(
                    'label'       => 'Background (focus)',
                    'elements'    => '
                        input:focus,
                        select:focus,
                        textarea:focus,
                        .input-group:focus
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_bg_focus'
                ),
                'input_border_top_left_focus' => array(
                    'label'       => 'Top/left border (focus)',
                    'elements'    => '
                        input:focus,
                        select:focus,
                        textarea:focus,
                        .input-group:focus
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_top_left_focus'
                ),
                'input_border_bottom_right_focus' => array(
                    'label'       => 'Bottom/right border (focus)',
                    'elements'    => '
                        input:focus,
                        select:focus,
                        textarea:focus,
                        .input-group:focus
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_bottom_right_focus'
                ),
                'input_text_error' => array(
                    'label'       => 'Text (error)',
                    'elements'    => '
                        .has-error input,
                        .has-error select,
                        .has-error textarea
                    ',
                    'property'    => 'color',
                    'color'       => '#84290a',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_text_error'
                ),
                'input_bg_error' => array(
                    'label'       => 'Background (error)',
                    'elements'    => '
                        .has-error input,
                        .has-error select,
                        .has-error textarea
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_bg_error'
                ),
                'input_border_top_left_error' => array(
                    'label'       => 'Top/left border (error)',
                    'elements'    => '
                        .has-error input,
                        .has-error select,
                        .has-error textarea
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#ffcdbc',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_top_left_error'
                ),
                'input_border_bottom_right_error' => array(
                    'label'       => 'Bottom/right border (error)',
                    'elements'    => '
                        .has-error input,
                        .has-error select,
                        .has-error textarea
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_bottom_right_error'
                )
            ),
            'buttons' => array(
                '_label' => 'Buttons',
                'button' => array(
                    'label'       => 'Primary bg',
                    'elements'    => '
                        .btn:not(:hover):not(.btn-default),
                        .btn.active:hover,
                        .button:not(:hover),
                        button:not(:hover):not(.btn),
                        [type=button]:not(:hover):not(.btn),
                        [type=submit]:not(:hover):not(.btn),
                        [type=reset]:not(:hover):not(.btn),
                        .ui-button.ui-state-default:not(.ui-state-hover):not(.ui-state-active):not(.ui-state-focus),
                        .ui-slider .ui-slider-handle.ui-state-default:not(.ui-state-hover):not(.ui-state-active):not(.ui-state-focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:buttons.button'
                ),
                'button_text' => array(
                    'label'       => 'Primary text',
                    'elements'    => '
                        .btn:not(:hover):not(.btn-default),
                        .btn.active:hover,
                        .button:not(:hover),
                        button:not(:hover):not(.btn),
                        [type=button]:not(:hover):not(.btn),
                        [type=submit]:not(:hover):not(.btn),
                        [type=reset]:not(:hover):not(.btn),
                        .ui-button.ui-state-default:not(.ui-state-hover):not(.ui-state-active):not(.ui-state-focus),
                        .ui-slider .ui-slider-handle.ui-state-default:not(.ui-state-hover):not(.ui-state-active):not(.ui-state-focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:buttons.button_text'
                ),
                'button_hover' => array(
                    'label'       => 'Primary bg (hover)',
                    'elements'    => '
                        .btn:not(.active):not(.btn-default):hover,
                        .button:hover,
                        button:not(.btn):hover,
                        [type=button]:not(.btn):hover,
                        [type=submit]:not(.btn):hover,
                        [type=reset]:not(.btn):hover,
                        .ui-button.ui-state-hover:not(.ui-state-focus),
                        .ui-button.ui-state-active:not(.ui-state-focus),
                        .ui-slider .ui-slider-handle.ui-state-hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'label'       => 'Primary text (hover)',
                    'elements'    => '
                        .btn:not(.active):not(.btn-default):hover,
                        .button:hover,
                        button:not(.btn):hover,
                        [type=button]:not(.btn):hover,
                        [type=submit]:not(.btn):hover,
                        [type=reset]:not(.btn):hover,
                        .ui-button.ui-state-hover:not(.ui-state-focus),
                        .ui-button.ui-state-active:not(.ui-state-focus),
                        .ui-slider .ui-slider-handle.ui-state-hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:buttons.button_text_hover'
                ),
                'button_default' => array(
                    'label'       => 'Default bg',
                    'elements'    => '
                        .btn.btn-default:not(:hover),
                        .btn.btn-default.active:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#d6d6d6',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:buttons.button_default'
                ),
                'button_default_text' => array(
                    'label'       => 'Default text',
                    'elements'    => '
                        .btn.btn-default:not(:hover),
                        .btn.btn-default.active:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:buttons.button_default_text'
                ),
                'button_default_hover' => array(
                    'label'       => 'Default bg (hover)',
                    'elements'    => '
                        .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:buttons.button_default_hover'
                ),
                'button_default_text_hover' => array(
                    'label'       => 'Default text (hover)',
                    'elements'    => '
                        .btn.btn-default:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:buttons.button_default_text_hover'
                )
            )
        );

        $theme_config = TB_Engine::instance()->getThemeConfig();
        $theme_config_colors = isset($theme_config['colors']['area'][$area_name]) ? $theme_config['colors']['area'][$area_name] : array();

        $final_colors = array_replace_recursive($default_colors, $theme_config_colors);

        foreach ($final_colors as $group_key => &$sections) {
            foreach ($sections as $section_key => $section_values) {
                if (0 === strpos($section_key, '_')) {
                    continue;
                }
                if (isset($section_values['render_before'])) {
                    $position = array_search($section_values['render_before'], array_keys($sections));
                    unset($section_values['render_before']);
                    unset($sections[$section_key]);

                    TB_Utils::arrayInsert($sections, $position, array($section_key => $section_values));
                }
            }
        }

        return $final_colors;
    }

    public static function getRowColors()
    {
        $default_colors = array(
            'body' => array(
                '_label' => 'Body',
                'accent' => array(
                    'label'       => 'Accent',
                    'elements'    => '
                        .tb_main_color,
                        .tb_hover_main_color:hover,
                        .colorbox, .agree,
                        .tb_list_1 > li:before,
                        .tb_list_1 > li > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:body.accent'
                ),
                'accent_hover' => array(
                    'label'       => 'Accent (hover)',
                    'elements'    => '
                        a.tb_main_color:hover,
                        a.colorbox:hover,
                        a.agree:hover,
                        .tb_main_color_hover:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:body.accent_hover'
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
                    'inherit_key' => 'area:body.text'
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
                    'inherit_key' => 'area:body.links'
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
                    'inherit_key' => 'area:body.links_hover'
                ),
                'text_links' => array(
                    'label'       => 'Links in text',
                    'elements'    => '
                        .tb_text_wrap a:not(.btn):not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:body.text_links'
                ),
                'text_links_hover' => array(
                    'label'       => 'Links in text (hover)',
                    'elements'    => '
                        .tb_text_wrap a:not(.btn):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:body.text_links_hover'
                ),
                'titles' => array(
                    'label'       => 'Titles',
                    'elements'    => '
                        h1,
                        h2,
                        h3,
                        h4,
                        h5,
                        h6,
                        .h1,
                        .h2,
                        .h3,
                        .h4,
                        .h5,
                        .h6,
                        legend,
                        .panel-heading,
                        .box-heading
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:body.titles'
                ),
                'column_border' => array(
                    'label'       => 'Column border',
                    'elements'    => '
                        > .tb_separate_columns > [class*="col-"],
                        hr
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:body.column_border'
                ),
                'subtle_base' => array(
                    'label'       => 'Subtle color base',
                    'elements'    => '',
                    'property'    => 'subtle',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:body.subtle_base'
                ),
            ),
            'forms' => array(
                '_label' => 'Form elements',
                'input_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        input:not(:hover):not(:focus),
                        select:not(:hover):not(:focus),
                        textarea:not(:hover):not(:focus),
                        .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_text'
                ),
                'input_bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        input:not(:hover):not(:focus),
                        select:not(:hover):not(:focus),
                        textarea:not(:hover):not(:focus),
                        .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_bg'
                ),
                'input_border_top_left' => array(
                    'label'       => 'Top/left border',
                    'elements'    => '
                        input:not(:hover):not(:focus),
                        select:not(:hover):not(:focus),
                        textarea:not(:hover):not(:focus),
                        .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_border_top_left'
                ),
                'input_border_bottom_right' => array(
                    'label'       => 'Bottom/right border',
                    'elements'    => '
                        input:not(:hover):not(:focus),
                        select:not(:hover):not(:focus),
                        textarea:not(:hover):not(:focus),
                        .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_border_bottom_right'
                ),
                'input_text_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '
                        input:hover:not(:focus),
                        select:hover:not(:focus),
                        textarea:hover:not(:focus),
                        .input-group:hover:not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_text_hover'
                ),
                'input_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '
                        input:hover:not(:focus),
                        select:hover:not(:focus),
                        textarea:hover:not(:focus),
                        .input-group:hover:not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_bg_hover'
                ),
                'input_border_top_left_hover' => array(
                    'label'       => 'Top/left border (hover)',
                    'elements'    => '
                        input:hover:not(:focus),
                        select:hover:not(:focus),
                        textarea:hover:not(:focus),
                        .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_border_top_left_hover'
                ),
                'input_border_bottom_right_hover' => array(
                    'label'       => 'Bottom/right border (hover)',
                    'elements'    => '
                        input:hover:not(:focus),
                        select:hover:not(:focus),
                        textarea:hover:not(:focus),
                        .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_border_bottom_right_hover'
                ),
                'input_text_focus' => array(
                    'label'       => 'Text (focus)',
                    'elements'    => '
                        input:focus,
                        select:focus,
                        textarea:focus,
                        .input-group:focus
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_text_focus'
                ),
                'input_bg_focus' => array(
                    'label'       => 'Background (focus)',
                    'elements'    => '
                        input:focus,
                        select:focus,
                        textarea:focus,
                        .input-group:focus
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_bg_focus'
                ),
                'input_border_top_left_focus' => array(
                    'label'       => 'Top/left border (focus)',
                    'elements'    => '
                        input:focus,
                        select:focus,
                        textarea:focus,
                        .input-group:focus
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_border_top_left_focus'
                ),
                'input_border_bottom_right_focus' => array(
                    'label'       => 'Bottom/right border (focus)',
                    'elements'    => '
                        input:focus,
                        select:focus,
                        textarea:focus,
                        .input-group:focus
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_border_bottom_right_focus'
                ),
                'input_text_error' => array(
                    'label'       => 'Text (error)',
                    'elements'    => '
                        .has-error input,
                        .has-error select,
                        .has-error textarea
                    ',
                    'property'    => 'color',
                    'color'       => '#84290a',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_text_error'
                ),
                'input_bg_error' => array(
                    'label'       => 'Background (error)',
                    'elements'    => '
                        .has-error input,
                        .has-error select,
                        .has-error textarea
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_bg_error'
                ),
                'input_border_top_left_error' => array(
                    'label'       => 'Top/left border (error)',
                    'elements'    => '
                        .has-error input,
                        .has-error select,
                        .has-error textarea
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#ffcdbc',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_border_top_left_error'
                ),
                'input_border_bottom_right_error' => array(
                    'label'       => 'Bottom/right border (error)',
                    'elements'    => '
                        .has-error input,
                        .has-error select,
                        .has-error textarea
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:forms.input_border_bottom_right_error'
                )
            ),
            'buttons' => array(
                '_label' => 'Buttons',
                'button' => array(
                    'label'       => 'Primary bg',
                    'elements'    => '
                        .btn:not(:hover):not(.btn-default),
                        .btn.active:hover,
                        .button:not(:hover),
                        button:not(:hover):not(.btn),
                        [type=button]:not(:hover):not(.btn),
                        [type=submit]:not(:hover):not(.btn),
                        [type=reset]:not(:hover):not(.btn),
                        .ui-button.ui-state-default:not(.btn):not(.ui-state-hover):not(.ui-state-active):not(.ui-state-focus),
                        .ui-slider .ui-slider-handle.ui-state-default:not(.ui-state-hover):not(.ui-state-active):not(.ui-state-focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:buttons.button'
                ),
                'button_text' => array(
                    'label'       => 'Primary text',
                    'elements'    => '
                        .btn:not(:hover):not(.btn-default),
                        .btn.active:hover,
                        .button:not(:hover),
                        button:not(:hover):not(.btn),
                        [type=button]:not(:hover):not(.btn),
                        [type=submit]:not(:hover):not(.btn),
                        [type=reset]:not(:hover):not(.btn),
                        .ui-button.ui-state-default:not(.btn):not(.ui-state-hover):not(.ui-state-active):not(.ui-state-focus),
                        .ui-slider .ui-slider-handle.ui-state-default:not(.ui-state-hover):not(.ui-state-active):not(.ui-state-focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:buttons.button_text'
                ),
                'button_hover' => array(
                    'label'       => 'Primary bg (hover)',
                    'elements'    => '
                        .btn:not(.btn-default):hover,
                        .button:hover,
                        button:not(.btn):hover,
                        [type=button]:not(.btn):hover,
                        [type=submit]:not(.btn):hover,
                        [type=reset]:not(.btn):hover,
                        .ui-button.ui-state-hover:not(.btn):not(.ui-state-focus),
                        .ui-button.ui-state-active:not(.ui-state-focus),
                        .ui-slider .ui-slider-handle.ui-state-hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'label'       => 'Primary text (hover)',
                    'elements'    => '
                        .btn:not(.active):not(.btn-default):hover,
                        .button:hover,
                        button:not(.btn):hover,
                        [type=button]:not(.btn):hover,
                        [type=submit]:not(.btn):hover,
                        [type=reset]:not(.btn):hover,
                        .ui-button.ui-state-hover:not(.btn):not(.ui-state-focus),
                        .ui-button.ui-state-active:not(.ui-state-focus),
                        .ui-slider .ui-slider-handle.ui-state-hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:buttons.button_text_hover'
                ),
                'button_default' => array(
                    'label'       => 'Default bg',
                    'elements'    => '
                        .btn.btn-default:not(:hover),
                        .btn.btn-default.active:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#d6d6d6',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:buttons.button_default'
                ),
                'button_default_text' => array(
                    'label'       => 'Default text',
                    'elements'    => '
                        .btn.btn-default:not(:hover),
                        .btn.btn-default.active:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:buttons.button_default_text'
                ),
                'button_default_hover' => array(
                    'label'       => 'Default bg (hover)',
                    'elements'    => '
                        .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:buttons.button_default_hover'
                ),
                'button_default_text_hover' => array(
                    'label'       => 'Default text (hover)',
                    'elements'    => '
                        .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'area:buttons.button_default_text_hover'
                )
            )
        );

        $theme_config = TB_Engine::instance()->getThemeConfig();
        $theme_config_colors = isset($theme_config['colors']['row']) ? $theme_config['colors']['row'] : array();
        $final_colors = array_replace_recursive($default_colors, $theme_config_colors);

        foreach ($final_colors as $group_key => &$sections) {
            foreach ($sections as $section_key => $section_values) {
                if (0 === strpos($section_key, '_')) {
                    continue;
                }
                if (isset($section_values['render_before'])) {
                    $position = array_search($section_values['render_before'], array_keys($sections));
                    unset($section_values['render_before']);
                    unset($sections[$section_key]);

                    TB_Utils::arrayInsert($sections, $position, array($section_key => $section_values));
                }
            }
        }

        return $final_colors;
    }

    public static function getColumnColors()
    {
        $default_colors = array(
            'body' => array(
                'accent' => array(
                    'inherit_key' => 'row:body.accent'
                ),
                'accent_hover' => array(
                    'inherit_key' => 'row:body.accent_hover'
                ),
                'accent_bg' => array(
                    'inherit_key' => 'body.accent'
                ),
                'accent_bg_hover' => array(
                    'inherit_key' => 'body.accent_hover'
                ),
                'text' => array(
                    'inherit_key' => 'row:body.text'
                ),
                'titles' => array(
                    'inherit_key' => 'row:body.titles'
                ),
                'links' => array(
                    'inherit_key' => 'row:body.links'
                ),
                'links_hover' => array(
                    'inherit_key' => 'row:body.links_hover'
                ),
                'text_links' => array(
                    'inherit_key' => 'row:body.text_links'
                ),
                'text_links_hover' => array(
                    'inherit_key' => 'row:body.text_links_hover'
                ),
                'subtle_base' => array(
                    'inherit_key' => 'row:body.subtle_base'
                ),
            ),
            'forms' => array(
                'input_text' => array(
                    'inherit_key' => 'row:forms.input_text'
                ),
                'input_bg' => array(
                    'inherit_key' => 'row:forms.input_bg'
                ),
                'input_border_top_left' => array(
                    'inherit_key' => 'row:forms.input_border_top_left'
                ),
                'input_border_bottom_right' => array(
                    'inherit_key' => 'row:forms.input_border_bottom_right'
                ),
                'input_text_hover' => array(
                    'inherit_key' => 'row:forms.input_text_hover'
                ),
                'input_bg_hover' => array(
                    'inherit_key' => 'row:forms.input_bg_hover'
                ),
                'input_border_top_left_hover' => array(
                    'inherit_key' => 'row:forms.input_border_top_left_hover'
                ),
                'input_border_bottom_right_hover' => array(
                    'inherit_key' => 'row:forms.input_border_bottom_right_hover'
                ),
                'input_text_focus' => array(
                    'inherit_key' => 'row:forms.input_text_focus'
                ),
                'input_bg_focus' => array(
                    'inherit_key' => 'row:forms.input_bg_focus'
                ),
                'input_border_top_left_focus' => array(
                    'inherit_key' => 'row:forms.input_border_top_left_focus'
                ),
                'input_border_bottom_right_focus' => array(
                    'inherit_key' => 'row:forms.input_border_bottom_right_focus'
                ),
                'input_text_error' => array(
                    'inherit_key' => 'row:forms.input_text_error'
                ),
                'input_bg_error' => array(
                    'inherit_key' => 'row:forms.input_bg_error'
                ),
                'input_border_top_left_error' => array(
                    'inherit_key' => 'row:forms.input_border_top_left_error'
                ),
                'input_border_bottom_right_error' => array(
                    'inherit_key' => 'row:forms.input_border_bottom_right_error'
                ),
            ),
            'buttons' => array(
                'button' => array(
                    'inherit_key' => 'row:buttons.button'
                ),
                'button_text' => array(
                    'inherit_key' => 'row:buttons.button_text'
                ),
                'button_hover' => array(
                    'inherit_key' => 'row:buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'inherit_key' => 'row:buttons.button_text_hover'
                ),
                'button_default' => array(
                    'inherit_key' => 'row:buttons.button_default'
                ),
                'button_default_text' => array(
                    'inherit_key' => 'row:buttons.button_default_text'
                ),
                'button_default_hover' => array(
                    'inherit_key' => 'row:buttons.button_default_hover'
                ),
                'button_default_text_hover' => array(
                    'inherit_key' => 'row:buttons.button_default_text_hover'
                )
            )
        );

        $column_colors = self::getRowColors();

        unset($column_colors['body']['column_border']);

        $theme_config = TB_Engine::instance()->getThemeConfig();
        $theme_config_colors = isset($theme_config['colors']['column']) ? $theme_config['colors']['column'] : array();

        return array_replace_recursive(array_replace_recursive($column_colors, $default_colors), $theme_config_colors);
    }
}