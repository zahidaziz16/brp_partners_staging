<?php

class MainColorsData
{
    public static function getInheritMenuColors()
    {
        return array(
            0 => array(
                'inherit_key' => 'theme:main.accent'
            ),
            5 => array(
                'inherit_key' => 'theme:main.accent_hover'
            ),
            6 => array(
                'inherit_key' => 'theme:main.text'
            ),
            7 => array(
                'inherit_key' => 'theme:main.column_border'
            )
        );
    }

    public static function getColors($key = null)
    {
        $colors = array(
            /* ------------------------------------------------------
               M A I N
            ------------------------------------------------------ */
            'main' => array(
                '_label' => 'Main',
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'inherit_key' => 'main.accent'
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
                    'inherit_key' => 'main.accent_hover'
                ),
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        body
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'text_links' => array(
                    'label'       => 'Links in text',
                    'elements'    => '
                        .tb_text_wrap a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'column_border' => array(
                    'label'       => 'Column border',
                    'elements'    => '
                        .tb_separate_columns > .col,
                        hr
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'subtle_base' => array(
                    'label'       => 'Subtle color base',
                    'elements'    => '',
                    'property'    => 'subtle',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'body' => array(
                    'label'       => 'Body background',
                    'elements'    => '
                        body
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            ),

            /* ------------------------------------------------------
               S T Y L E   A R E A S
            ------------------------------------------------------ */
            'bottom' => array(
                '_label' => 'Bottom',
                'accent' => array(
                    'label'       => 'Accent',
                    'elements'    => '
                        .tb_area_bottom .tb_main_color,
                        .tb_area_bottom .tb_hover_main_color:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'accent_hover' => array(
                    'label'       => 'Accent (hover)',
                    'elements'    => '
                        .tb_area_bottom a.tb_main_color:hover,
                        .tb_area_bottom .tb_main_color_hover:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent_hover'
                ),
                'accent_bg' => array(
                    'label'       => 'Accent bg',
                    'elements'    => '
                        .tb_area_bottom .tb_main_color_bg,
                        .tb_area_bottom .tb_hover_main_color_bg:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'bottom.accent'
                ),
                'accent_bg_hover' => array(
                    'label'       => 'Accent bg (hover)',
                    'elements'    => '
                        .tb_area_bottom a.tb_main_color_bg:hover,
                        .tb_area_bottom .tb_main_color_bg_hover:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'bottom.accent_hover'
                ),
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        .tb_area_bottom
                    ',
                    'property'    => 'color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        .tb_area_bottom a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#666666',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        .tb_area_bottom a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'bottom.accent'
                )
            ),

            /* ------------------------------------------------------
               B O O T S T R A P   C O R E
            ------------------------------------------------------ */
            'tables_thead' => array(
                '_label' => 'Table head',
                'th_text' => array(
                    'label'       => 'Cell text',
                    'elements'    => '
                        .table > thead > tr > th,
                        .table > thead > tr > td,
                        .table > table > thead > tr > th,
                        .table > table > thead > tr > td
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text'
                ),
                'th_bg' => array(
                    'label'       => 'Cell bg',
                    'elements'    => '
                        .table > thead > tr > th,
                        .table > thead > tr > td,
                        .table > table > thead > tr > th,
                        .table > table > thead > tr > td
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e9e9e9',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'th_border' => array(
                    'label'       => 'Cell border',
                    'elements'    => '
                        .table > thead > tr > th,
                        .table > thead > tr > td,
                        .table > table > thead > tr > th,
                        .table > table > thead > tr > td
                    ',
                    'property'    => 'border-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            ),
            'tables_tbody' => array(
                '_label' => 'Table body / footer',
                'td_text' => array(
                    'label'       => 'Cell text',
                    'elements'    => '
                        .table > tbody > tr > th,
                        .table > tbody > tr > td,
                        .table > tfoot > tr > th,
                        .table > tfoot > tr > td,
                        .table > table > tbody > tr > th,
                        .table > table > tbody > tr > td,
                        .table > table > tfoot > tr > th,
                        .table > table > tfoot > tr > td
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text'
                ),
                'td_bg' => array(
                    'label'       => 'Cell bg',
                    'elements'    => '
                        .table > tbody > tr > th,
                        .table > tbody > tr > td,
                        .table > tfoot > tr > th,
                        .table > tfoot > tr > td,
                        .table > table > tbody > tr > th,
                        .table > table > tbody > tr > td,
                        .table > table > tfoot > tr > th,
                        .table > table > tfoot > tr > td
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'td_border' => array(
                    'label'       => 'Cell border',
                    'elements'    => '
                        .table > tbody > tr > th,
                        .table > tbody > tr > td,
                        .table > tfoot > tr > th,
                        .table > tfoot > tr > td,
                        .table > table > tbody > tr > th,
                        .table > table > tbody > tr > td,
                        .table > table > tfoot > tr > th,
                        .table > table > tfoot > tr > td,
                        .table-bordered,
                        .cart-info.tb_max_w_500 .table > tbody > tr:not(:last-child),
                        .cart-info.tb_max_w_300 .table > tbody > tr:not(:last-child)
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'td_bg_zebra' => array(
                    'label'       => 'Cell bg (zebra)',
                    'elements'    => '
                        .table-striped > tbody > tr:nth-child(even),
                        .table-striped > table > tbody > tr:nth-child(even)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#f9f9f9',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'td_bg_hover' => array(
                    'label'       => 'Cell bg (hover)',
                    'elements'    => '
                        .table-hover > tbody > tr:hover,
                        .table-hover > table > tbody > tr:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#f5f5f5',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                        .ui-button.ui-state-hover:not(.btn):not(.ui-state-focus),
                        .ui-button.ui-state-active:not(.ui-state-focus),
                        .ui-slider .ui-slider-handle.ui-state-hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent_hover'
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'button_default_hover' => array(
                    'label'       => 'Default bg (hover)',
                    'elements'    => '
                        .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent_hover'
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
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            ),

            /* ------------------------------------------------------
               S T I C K Y   H E A D E R
            ------------------------------------------------------ */

            'sticky_header' => array(
                '_label' => 'Main',
                'accent' => array(
                    'label'       => 'Accent',
                    'elements'    => '
                        [class].tbStickyRow .tb_main_color,
                        [class].tbStickyRow .tb_hover_main_color:hover,
                        [class].tbStickyRow .colorbox,
                        [class].tbStickyRow .agree,
                        [class].tbStickyRow .tb_list_1 > li:before,
                        [class].tbStickyRow .tb_list_1 > li > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#bff222',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'accent_hover' => array(
                    'label'       => 'Accent (hover)',
                    'elements'    => '
                        [class].tbStickyRow a.tb_main_color:hover,
                        [class].tbStickyRow a.colorbox:hover,
                        [class].tbStickyRow a.agree:hover,
                        [class].tbStickyRow .tb_main_color_hover:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'accent_bg' => array(
                    'label'       => 'Accent bg',
                    'elements'    => '
                        [class].tbStickyRow .tb_main_color_bg,
                        [class].tbStickyRow .tb_hover_main_color_bg:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'sticky_header.accent'
                ),
                'accent_bg_hover' => array(
                    'label'       => 'Accent bg (hover)',
                    'elements'    => '
                        [class].tbStickyRow a.tb_main_color_bg:hover,
                        [class].tbStickyRow .tb_main_color_bg_hover:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'sticky_header.accent_hover'
                ),
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        [class].tbStickyRow
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text'
                ),
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        [class].tbStickyRow a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links'
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        [class].tbStickyRow a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links_hover'
                ),
                'text_links' => array(
                    'label'       => 'Links in text',
                    'elements'    => '
                        [class].tbStickyRow .tb_text_wrap a:not(.btn):not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text_links'
                ),
                'text_links_hover' => array(
                    'label'       => 'Links in text (hover)',
                    'elements'    => '
                        [class].tbStickyRow .tb_text_wrap a:not(.btn):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text_links_hover'
                ),
                'titles' => array(
                    'label'       => 'Titles',
                    'elements'    => '
                        [class].tbStickyRow h1,
                        [class].tbStickyRow h2,
                        [class].tbStickyRow h3,
                        [class].tbStickyRow h4,
                        [class].tbStickyRow h5,
                        [class].tbStickyRow h6,
                        [class].tbStickyRow .h1,
                        [class].tbStickyRow .h2,
                        [class].tbStickyRow .h3,
                        [class].tbStickyRow .h4,
                        [class].tbStickyRow .h5,
                        [class].tbStickyRow .h6,
                        [class].tbStickyRow a.h1:not(:hover),
                        [class].tbStickyRow a.h2:not(:hover),
                        [class].tbStickyRow a.h3:not(:hover),
                        [class].tbStickyRow a.h4:not(:hover),
                        [class].tbStickyRow a.h5:not(:hover),
                        [class].tbStickyRow a.h6:not(:hover),
                        [class].tbStickyRow legend,
                        [class].tbStickyRow .panel-heading,
                        [class].tbStickyRow .box-heading
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.titles'
                ),
                'titles_hover' => array(
                    'label'       => 'Title links (hover)',
                    'elements'    => '
                        [class].tbStickyRow a.h1:hover,
                        [class].tbStickyRow a.h2:hover,
                        [class].tbStickyRow a.h3:hover,
                        [class].tbStickyRow a.h4:hover,
                        [class].tbStickyRow a.h5:hover,
                        [class].tbStickyRow a.h6:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links_hover'
                ),
                'subtle_base' => array(
                    'label'       => 'Subtle color base',
                    'elements'    => '
                        [class].tbStickyRow
                    ',
                    'property'    => 'subtle',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.subtle_base'
                ),
                'bg' => array(
                    'label'       => 'Sticky header bg',
                    'elements'    => '
                        #sticky_header
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            ),
            'sticky_header_forms' => array(
                '_label' => 'Form elements',
                'input_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        [class].tbStickyRow input:not(:hover):not(:focus),
                        [class].tbStickyRow select:not(:hover):not(:focus),
                        [class].tbStickyRow textarea:not(:hover):not(:focus),
                        [class].tbStickyRow .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text'
                ),
                'input_bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        [class].tbStickyRow input:not(:hover):not(:focus),
                        [class].tbStickyRow select:not(:hover):not(:focus),
                        [class].tbStickyRow textarea:not(:hover):not(:focus),
                        [class].tbStickyRow .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg'
                ),
                'input_border_top_left' => array(
                    'label'       => 'Top/left border',
                    'elements'    => '
                        [class].tbStickyRow input:not(:hover):not(:focus),
                        [class].tbStickyRow select:not(:hover):not(:focus),
                        [class].tbStickyRow textarea:not(:hover):not(:focus),
                        [class].tbStickyRow .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left'
                ),
                'input_border_bottom_right' => array(
                    'label'       => 'Bottom/right border',
                    'elements'    => '
                        [class].tbStickyRow input:not(:hover):not(:focus),
                        [class].tbStickyRow select:not(:hover):not(:focus),
                        [class].tbStickyRow textarea:not(:hover):not(:focus),
                        [class].tbStickyRow .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right'
                ),
                'input_text_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '
                        [class].tbStickyRow input:hover:not(:focus),
                        [class].tbStickyRow select:hover:not(:focus),
                        [class].tbStickyRow textarea:hover:not(:focus),
                        [class].tbStickyRow .input-group:hover:not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_hover'
                ),
                'input_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '
                        [class].tbStickyRow input:hover:not(:focus),
                        [class].tbStickyRow select:hover:not(:focus),
                        [class].tbStickyRow textarea:hover:not(:focus),
                        [class].tbStickyRow .input-group:hover:not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_hover'
                ),
                'input_border_top_left_hover' => array(
                    'label'       => 'Top/left border (hover)',
                    'elements'    => '
                        [class].tbStickyRow input:hover:not(:focus),
                        [class].tbStickyRow select:hover:not(:focus),
                        [class].tbStickyRow textarea:hover:not(:focus),
                        [class].tbStickyRow .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_hover'
                ),
                'input_border_bottom_right_hover' => array(
                    'label'       => 'Bottom/right border (hover)',
                    'elements'    => '
                        [class].tbStickyRow input:hover:not(:focus),
                        [class].tbStickyRow select:hover:not(:focus),
                        [class].tbStickyRow textarea:hover:not(:focus),
                        [class].tbStickyRow .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_hover'
                ),
                'input_text_focus' => array(
                    'label'       => 'Text (focus)',
                    'elements'    => '
                        [class].tbStickyRow input:focus,
                        [class].tbStickyRow select:focus,
                        [class].tbStickyRow textarea:focus,
                        [class].tbStickyRow .input-group:focus
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_focus'
                ),
                'input_bg_focus' => array(
                    'label'       => 'Background (focus)',
                    'elements'    => '
                        [class].tbStickyRow input:focus,
                        [class].tbStickyRow select:focus,
                        [class].tbStickyRow textarea:focus,
                        [class].tbStickyRow .input-group:focus
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_focus'
                ),
                'input_border_top_left_focus' => array(
                    'label'       => 'Top/left border (focus)',
                    'elements'    => '
                        [class].tbStickyRow input:focus,
                        [class].tbStickyRow select:focus,
                        [class].tbStickyRow textarea:focus,
                        [class].tbStickyRow .input-group:focus
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_focus'
                ),
                'input_border_bottom_right_focus' => array(
                    'label'       => 'Bottom/right border (focus)',
                    'elements'    => '
                        [class].tbStickyRow input:focus,
                        [class].tbStickyRow select:focus,
                        [class].tbStickyRow textarea:focus,
                        [class].tbStickyRow .input-group:focus
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_focus'
                ),
                'input_text_error' => array(
                    'label'       => 'Text (error)',
                    'elements'    => '
                        [class].tbStickyRow .has-error input,
                        [class].tbStickyRow .has-error select,
                        [class].tbStickyRow .has-error textarea
                    ',
                    'property'    => 'color',
                    'color'       => '#84290a',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_error'
                ),
                'input_bg_error' => array(
                    'label'       => 'Background (error)',
                    'elements'    => '
                        [class].tbStickyRow .has-error input,
                        [class].tbStickyRow .has-error select,
                        [class].tbStickyRow .has-error textarea
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_error'
                ),
                'input_border_top_left_error' => array(
                    'label'       => 'Top/left border (error)',
                    'elements'    => '
                        [class].tbStickyRow .has-error input,
                        [class].tbStickyRow .has-error select,
                        [class].tbStickyRow .has-error textarea
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#ffcdbc',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_error'
                ),
                'input_border_bottom_right_error' => array(
                    'label'       => 'Bottom/right border (error)',
                    'elements'    => '
                        [class].tbStickyRow .has-error input,
                        [class].tbStickyRow .has-error select,
                        [class].tbStickyRow .has-error textarea
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_error'
                )
            ),
            'sticky_header_menu' => array(
                '_label' => 'Menu',
                'text' => array(
                    'label'       => 'Menu text',
                    'elements'    => '
                        [class].tbStickyRow nav > ul > li.dropdown:not(:hover) > a,
                        [class].tbStickyRow nav > ul > li.dropdown:not(:hover) > * > a,
                        [class].tbStickyRow nav > ul > li:not(.dropdown) > a:not(:hover),
                        [class].tbStickyRow nav > ul > li:not(.dropdown) > * > a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'sticky_header.links'
                ),
                'bg' => array(
                    'label'       => 'Menu bg',
                    'elements'    => '
                        [class].tbStickyRow nav > ul > li.dropdown:not(:hover) > a,
                        [class].tbStickyRow nav > ul > li.dropdown:not(:hover) > * > a,
                        [class].tbStickyRow nav > ul > li:not(.dropdown) > a:not(:hover),
                        [class].tbStickyRow nav > ul > li:not(.dropdown) > * > a:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'sticky_header.accent'
                ),
                'text_hover' => array(
                    'label'       => 'Menu text (hover)',
                    'elements'    => '
                        [class].tbStickyRow nav > ul > li.dropdown:not(.tb_selected):hover > a,
                        [class].tbStickyRow nav > ul > li.dropdown:not(.tb_selected):hover > * > a,
                        [class].tbStickyRow nav > ul > li:not(.dropdown):not(.tb_selected) > a:hover,
                        [class].tbStickyRow nav > ul > li:not(.dropdown):not(.tb_selected) > * > a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'sticky_header.links_hover'
                ),
                'bg_hover' => array(
                    'label'       => 'Menu bg (hover)',
                    'elements'    => '
                        [class].tbStickyRow nav > ul > li.dropdown:not(.tb_selected):hover > a,
                        [class].tbStickyRow nav > ul > li.dropdown:not(.tb_selected):hover > * > a,
                        [class].tbStickyRow nav > ul > li:not(.dropdown):not(.tb_selected) > a:hover,
                        [class].tbStickyRow nav > ul > li:not(.dropdown):not(.tb_selected) > * > a:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'sticky_header.accent'
                ),
                'text_selected' => array(
                    'label'       => 'Menu text (selected)',
                    'elements'    => '
                        [class].tbStickyRow nav > ul > li.tb_selected > a,
                        [class].tbStickyRow nav > ul > li.tb_selected > * > a
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'sticky_header.links_hover'
                ),
                'bg_selected' => array(
                    'label'       => 'Menu bg (selected)',
                    'elements'    => '
                        [class].tbStickyRow nav > ul > li.tb_selected > a,
                        [class].tbStickyRow nav > ul > li.tb_selected > * > a
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'sticky_header.accent'
                ),
                'icons' => array(
                    'label'       => 'Icons',
                    'elements'    => '
                        [class].tbStickyRow nav > ul > li > a:not(:hover) > .tb_text > .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'sticky_header.text'
                )
            ),
            'sticky_header_cart' => array(
                '_label' => 'Cart compact',
                'button' => array(
                    'label'       => 'Button bg',
                    'elements'    => '
                        #cart .nav > li > a.btn:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button'
                ),
                'button_text' => array(
                    'label'       => 'Button text',
                    'elements'    => '
                        #cart .nav > li > a.btn:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text'
                ),
                'button_hover' => array(
                    'label'       => 'Button bg (hover)',
                    'elements'    => '
                        #cart .nav > li > a.btn:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'label'       => 'Button text (hover)',
                    'elements'    => '
                        #cart .nav > li > a.btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text_hover'
                ),
                'button_badge_bg' => array(
                    'label'       => 'Badge bg',
                    'elements'    => '
                        #sticky_header #cart .nav > li > .heading > a > .tb_items
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'buttons.button_hover'
                ),
                'button_badge_text' => array(
                    'label'       => 'Badge color',
                    'elements'    => '
                        #sticky_header #cart .nav > li > .heading > a > .tb_items
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'buttons.button_text_hover'
                )
            ),

            /* ------------------------------------------------------
               B O O T S T R A P   C O M P O N E N T S
            ------------------------------------------------------ */
            'dropdown_menu' => array(
                '_label' => 'Dropdown Menu',
                'accent' => array(
                    'label'       => 'Accent',
                    'elements'    => '
                        [class].dropdown-menu .tb_main_color,
                        [class].dropdown-menu .tb_hover_main_color:hover,
                        [class].dropdown-menu .colorbox,
                        [class].dropdown-menu .agree
                    ',
                    'property'    => 'color',
                    'color'       => '#bff222',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'accent_hover' => array(
                    'label'       => 'Accent (hover)',
                    'elements'    => '
                        [class].dropdown-menu a.tb_main_color:hover,
                        [class].dropdown-menu a.colorbox:hover,
                        [class].dropdown-menu a.agree:hover,
                        [class].dropdown-menu .tb_main_color_hover:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent_hover'
                ),
                'accent_bg' => array(
                    'label'       => 'Accent bg',
                    'elements'    => '
                        [class].dropdown-menu .tb_main_color_bg,
                        [class].dropdown-menu .tb_hover_main_color_bg:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'dropdown_menu.accent'
                ),
                'accent_bg_hover' => array(
                    'label'       => 'Accent bg (hover)',
                    'elements'    => '
                        [class].dropdown-menu a.tb_main_color_bg:hover,
                        [class].dropdown-menu .tb_main_color_bg_hover:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'dropdown_menu.accent_hover'
                ),
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        .dropdown-menu,
                        .tb_no_text > span[data-tooltip]:before
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text'
                ),
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        [class].dropdown-menu a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links'
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        [class].dropdown-menu a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links_hover'
                ),
                'text_links' => array(
                    'label'       => 'Links in text',
                    'elements'    => '
                        [class].dropdown-menu .tb_text_wrap a:not(.btn):not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text_links'
                ),
                'text_links_hover' => array(
                    'label'       => 'Links in text (hover)',
                    'elements'    => '
                        [class].dropdown-menu .tb_text_wrap a:not(.btn):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text_links_hover'
                ),
                'titles' => array(
                    'label'       => 'Titles',
                    'elements'    => '
                        [class].dropdown-menu h1,
                        [class].dropdown-menu h2,
                        [class].dropdown-menu h3,
                        [class].dropdown-menu h4,
                        [class].dropdown-menu h5,
                        [class].dropdown-menu h6,
                        [class].dropdown-menu .h1,
                        [class].dropdown-menu .h2,
                        [class].dropdown-menu .h3,
                        [class].dropdown-menu .h4,
                        [class].dropdown-menu .h5,
                        [class].dropdown-menu .h6,
                        [class].dropdown-menu a.h1:not(:hover),
                        [class].dropdown-menu a.h2:not(:hover),
                        [class].dropdown-menu a.h3:not(:hover),
                        [class].dropdown-menu a.h4:not(:hover),
                        [class].dropdown-menu a.h5:not(:hover),
                        [class].dropdown-menu a.h6:not(:hover),
                        [class].dropdown-menu legend,
                        [class].dropdown-menu .panel-heading,
                        [class].dropdown-menu .box-heading
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.titles'
                ),
                'titles_hover' => array(
                    'label'       => 'Title links (hover)',
                    'elements'    => '
                        [class].dropdown-menu a.h1:hover,
                        [class].dropdown-menu a.h2:hover,
                        [class].dropdown-menu a.h3:hover,
                        [class].dropdown-menu a.h4:hover,
                        [class].dropdown-menu a.h5:hover,
                        [class].dropdown-menu a.h6:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'List bullets',
                    'elements'    => '
                        [class].dropdown-menu.tb_list_1  > li:before,
                        [class].dropdown-menu.tb_list_1  > li > a:before,
                        [class].dropdown-menu .tb_list_1 > li:before,
                        [class].dropdown-menu .tb_list_1 > li > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'dropdown_menu.accent'
                ),
                'header' => array(
                    'label'       => 'Header',
                    'elements'    => '
                        [class].dropdown-menu li.dropdown-header
                    ',
                    'property'    => 'color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'divider' => array(
                    'label'       => 'Divider',
                    'elements'    => '
                        [class].dropdown-menu li.divider
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.column_border'
                ),
                'column_border' => array(
                    'label'       => 'Column border',
                    'elements'    => '
                        [class].dropdown-menu .tb_separate_columns > .col
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.column_border'
                ),
                'subtle_base' => array(
                    'label'       => 'Subtle color base',
                    'elements'    => '
                        [class].dropdown-menu
                    ',
                    'property'    => 'subtle',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.subtle_base'
                ),
                'subtle_base_hidden_color' => array(
                    'label'       => 'Subtle color base',
                    'elements'    => '
                        [class].dropdown-menu .buttons:before,
                        [class].dropdown-menu .mini-cart-total:before
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'dropdown_menu.subtle_base'
                ),
                'bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        .dropdown:after,
                        .dropdown-menu,
                        .dropdown-menu:before,
                        .tb_no_text > span[data-tooltip]:before,
                        .tb_no_text > span[data-tooltip]:after
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
            ),
            'dropdown_menu_forms' => array(
                '_label' => 'Dropdown forms',
                'input_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        .dropdown-menu input:not(:hover):not(:focus),
                        .dropdown-menu select:not(:hover):not(:focus),
                        .dropdown-menu textarea:not(:hover):not(:focus),
                        .dropdown-menu .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text'
                ),
                'input_bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        .dropdown-menu input:not(:hover):not(:focus),
                        .dropdown-menu select:not(:hover):not(:focus),
                        .dropdown-menu textarea:not(:hover):not(:focus),
                        .dropdown-menu .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg'
                ),
                'input_border_top_left' => array(
                    'label'       => 'Top/left border',
                    'elements'    => '
                        .dropdown-menu input:not(:hover):not(:focus),
                        .dropdown-menu select:not(:hover):not(:focus),
                        .dropdown-menu textarea:not(:hover):not(:focus),
                        .dropdown-menu .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left'
                ),
                'input_border_bottom_right' => array(
                    'label'       => 'Bottom/right border',
                    'elements'    => '
                        .dropdown-menu input:not(:hover):not(:focus),
                        .dropdown-menu select:not(:hover):not(:focus),
                        .dropdown-menu textarea:not(:hover):not(:focus),
                        .dropdown-menu .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right'
                ),
                'input_text_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '
                        .dropdown-menu input:hover:not(:focus),
                        .dropdown-menu select:hover:not(:focus),
                        .dropdown-menu textarea:hover:not(:focus),
                        .dropdown-menu .input-group:hover:not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_hover'
                ),
                'input_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '
                        .dropdown-menu input:hover:not(:focus),
                        .dropdown-menu select:hover:not(:focus),
                        .dropdown-menu textarea:hover:not(:focus),
                        .dropdown-menu .input-group:hover:not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_hover'
                ),
                'input_border_top_left_hover' => array(
                    'label'       => 'Top/left border (hover)',
                    'elements'    => '
                        .dropdown-menu input:hover:not(:focus),
                        .dropdown-menu select:hover:not(:focus),
                        .dropdown-menu textarea:hover:not(:focus),
                        .dropdown-menu .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_hover'
                ),
                'input_border_bottom_right_hover' => array(
                    'label'       => 'Bottom/right border (hover)',
                    'elements'    => '
                        .dropdown-menu input:hover:not(:focus),
                        .dropdown-menu select:hover:not(:focus),
                        .dropdown-menu textarea:hover:not(:focus),
                        .dropdown-menu .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_hover'
                ),
                'input_text_focus' => array(
                    'label'       => 'Text (focus)',
                    'elements'    => '
                        .dropdown-menu input:focus,
                        .dropdown-menu select:focus,
                        .dropdown-menu textarea:focus,
                        .dropdown-menu .input-group:focus
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_focus'
                ),
                'input_bg_focus' => array(
                    'label'       => 'Background (focus)',
                    'elements'    => '
                        .dropdown-menu input:focus,
                        .dropdown-menu select:focus,
                        .dropdown-menu textarea:focus,
                        .dropdown-menu .input-group:focus
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_focus'
                ),
                'input_border_top_left_focus' => array(
                    'label'       => 'Top/left border (focus)',
                    'elements'    => '
                        .dropdown-menu input:focus,
                        .dropdown-menu select:focus,
                        .dropdown-menu textarea:focus,
                        .dropdown-menu .input-group:focus
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_focus'
                ),
                'input_border_bottom_right_focus' => array(
                    'label'       => 'Bottom/right border (focus)',
                    'elements'    => '
                        .dropdown-menu input:focus,
                        .dropdown-menu select:focus,
                        .dropdown-menu textarea:focus,
                        .dropdown-menu .input-group:focus
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_focus'
                ),
                'input_text_error' => array(
                    'label'       => 'Text (error)',
                    'elements'    => '
                        .dropdown-menu .has-error input,
                        .dropdown-menu .has-error select,
                        .dropdown-menu .has-error textarea
                    ',
                    'property'    => 'color',
                    'color'       => '#84290a',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_error'
                ),
                'input_bg_error' => array(
                    'label'       => 'Background (error)',
                    'elements'    => '
                        .dropdown-menu .has-error input,
                        .dropdown-menu .has-error select,
                        .dropdown-menu .has-error textarea
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_error'
                ),
                'input_border_top_left_error' => array(
                    'label'       => 'Top/left border (error)',
                    'elements'    => '
                        .dropdown-menu .has-error input,
                        .dropdown-menu .has-error select,
                        .dropdown-menu .has-error textarea
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#ffcdbc',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_error'
                ),
                'input_border_bottom_right_error' => array(
                    'label'       => 'Bottom/right border (error)',
                    'elements'    => '
                        .dropdown-menu .has-error input,
                        .dropdown-menu .has-error select,
                        .dropdown-menu .has-error textarea
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_error'
                )
            ),
            'dropdown_menu_buttons' => array(
                '_label' => 'Dropdown buttons',
                'button' => array(
                    'label'       => 'Primary bg',
                    'elements'    => '
                        .dropdown-menu .btn:not(:hover):not(.btn-default)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button'
                ),
                'button_text' => array(
                    'label'       => 'Primary text',
                    'elements'    => '
                        .dropdown-menu .btn:not(:hover):not(.btn-default)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text'
                ),
                'button_hover' => array(
                    'label'       => 'Primary bg (hover)',
                    'elements'    => '
                        .dropdown-menu .btn:not(.active):not(.btn-default):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'label'       => 'Primary text (hover)',
                    'elements'    => '
                        .dropdown-menu .btn:not(.active):not(.btn-default):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text_hover'
                ),
                'button_default' => array(
                    'label'       => 'Default bg',
                    'elements'    => '
                        .dropdown-menu .btn.btn-default:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#d6d6d6',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default'
                ),
                'button_default_text' => array(
                    'label'       => 'Default text',
                    'elements'    => '
                        .dropdown-menu .btn.btn-default:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_text'
                ),
                'button_default_hover' => array(
                    'label'       => 'Default bg (hover)',
                    'elements'    => '
                        .dropdown-menu .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_hover'
                ),
                'button_default_text_hover' => array(
                    'label'       => 'Default text (hover)',
                    'elements'    => '
                        .dropdown-menu .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_text_hover'
                )
            ),
            'dropdown_tables_thead' => array(
                '_label' => 'Dropdown table head',
                'th_text' => array(
                    'label'       => 'Cell text',
                    'elements'    => '
                        .dropdown-menu .table > thead > tr > th,
                        .dropdown-menu .table > thead > tr > td
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_thead.th_text'
                ),
                'th_bg' => array(
                    'label'       => 'Cell bg',
                    'elements'    => '
                        .dropdown-menu .table > thead > tr > th,
                        .dropdown-menu .table > thead > tr > td
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e9e9e9',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_thead.th_bg'
                ),
                'th_border' => array(
                    'label'       => 'Cell border',
                    'elements'    => '
                        .dropdown-menu .table > thead > tr > th,
                        .dropdown-menu .table > thead > tr > td
                    ',
                    'property'    => 'border-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_thead.th_border'
                )
            ),
            'dropdown_tables_tbody' => array(
                '_label' => 'Dropdown table body / footer',
                'td_text' => array(
                    'label'       => 'Cell text',
                    'elements'    => '
                        .dropdown-menu .table > tbody > tr > th,
                        .dropdown-menu .table > tbody > tr > td,
                        .dropdown-menu .table > tfoot > tr > th,
                        .dropdown-menu .table > tfoot > tr > td
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_tbody.td_text'
                ),
                'td_bg' => array(
                    'label'       => 'Cell bg',
                    'elements'    => '
                        .dropdown-menu .table > tbody > tr > th,
                        .dropdown-menu .table > tbody > tr > td,
                        .dropdown-menu .table > tfoot > tr > th,
                        .dropdown-menu .table > tfoot > tr > td
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_tbody.td_bg'
                ),
                'td_border' => array(
                    'label'       => 'Cell border',
                    'elements'    => '
                        .dropdown-menu .table > tbody > tr > th,
                        .dropdown-menu .table > tbody > tr > td,
                        .dropdown-menu .table > tfoot > tr > th,
                        .dropdown-menu .table > tfoot > tr > td,
                        .dropdown-menu .table-bordered,
                        .dropdown-menu .cart-info.tb_max_w_500 .table > tbody > tr:not(:last-child),
                        .dropdown-menu .cart-info.tb_max_w_300 .table > tbody > tr:not(:last-child)
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_tbody.td_border'
                ),
                'td_bg_zebra' => array(
                    'label'       => 'Cell bg (zebra)',
                    'elements'    => '
                        .dropdown-menu .table-striped > tbody > tr:nth-child(even),
                        .dropdown-menu .table-striped > table > tbody > tr:nth-child(even)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#f9f9f9',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_tbody.td_bg_zebra'
                ),
                'td_bg_hover' => array(
                    'label'       => 'Cell bg (hover)',
                    'elements'    => '
                        .dropdown-menu .table-hover > tbody > tr:hover,
                        .dropdown-menu .table-hover > table > tbody > tr:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#f5f5f5',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_tbody.td_bg_hover'
                )
            ),
            'pagination' => array(
                '_label' => 'Pagination',
                'links_text' => array(
                    'label'       => 'Links text',
                    'elements'    => '
                        .pagination a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text'
                ),
                'links_bg' => array(
                    'label'       => 'Links bg',
                    'elements'    => '
                        .pagination a:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'links_text_hover' => array(
                    'label'       => 'Links text (hover)',
                    'elements'    => '
                        .pagination a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'links_bg_hover' => array(
                    'label'       => 'Links bg (hover)',
                    'elements'    => '
                        .pagination a:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'links_text_active' => array(
                    'label'       => 'Links text (active)',
                    'elements'    => '
                        .pagination b,
                        .pagination span
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text'
                ),
                'links_bg_active' => array(
                    'label'       => 'Links bg (active)',
                    'elements'    => '
                        .pagination b,
                        .pagination span
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'results' => array(
                    'label'       => 'Results text',
                    'elements'    => '.pagination .results',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            ),
            'ui_tabs' => array(
                '_label' => 'Tabs',
                'header_bg' => array(
                    'label'       => 'Toolbar bg',
                    'elements'    => '
                        .nav.nav-tabs,
                        .dropdown.tb_tabbed_menu:not(.tb_first_tab_selected):after
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e6e6e6',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'header_border' => array(
                    'label'       => 'Toolbar border',
                    'elements'    => '
                        .nav.nav-tabs
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'content_border' => array(
                    'label'       => 'Content border',
                    'elements'    => '
                        .tab-content
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_text' => array(
                    'label'       => 'Clickable text',
                    'elements'    => '
                        .nav.nav-tabs > li:not(:hover):not(.active):not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_text_str_"])
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_bg' => array(
                    'label'       => 'Clickable bg',
                    'elements'    => '
                        .nav.nav-tabs > li:not(:hover):not(.active):not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_bg_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e6e6e6',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_border' => array(
                    'label'       => 'Clickable border',
                    'elements'    => '
                        .nav.nav-tabs > li:not(:hover):not(.active):not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_border_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_icon' => array(
                    'label'       => 'Clickable icon',
                    'elements'    => '
                        .nav.nav-tabs > li:not(:hover):not(.active):not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_bg_str_"]) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'ui_tabs.clickable_text'
                ),
                'clickable_text_hover' => array(
                    'label'       => 'Clickable text (hover)',
                    'elements'    => '
                        .nav.nav-tabs:not(.tb_style_2) > li:hover:not(.active):not(.ui-state-active):not([class*="tb_text_hover_str_"]),
                        .ui-tabs-nav:not(.tb_style_2) .ui-state-focus:not(.ui-state-active):not([class*="tb_text_hover_str_"])
                    ',
                    'property'    => 'color',
                    'color'       => '#111111',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_bg_hover' => array(
                    'label'       => 'Clickable bg (hover)',
                    'elements'    => '
                        .nav.nav-tabs > li:hover:not(.active):not(.ui-state-active):not([class*="tb_bg_hover_str_"]),
                        .ui-tabs-nav .ui-state-focus:not(.ui-state-active):not([class*="tb_bg_hover_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_border_hover' => array(
                    'label'       => 'Clickable border (hover)',
                    'elements'    => '
                        .nav.nav-tabs > li:hover:not(.active):not(.ui-state-active):not([class*="tb_border_hover_str_"]),
                        .ui-tabs-nav .ui-state-focus:not(.ui-state-active):not([class*="tb_border_hover_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_icon_hover' => array(
                    'label'       => 'Clickable icon (hover)',
                    'elements'    => '
                        .nav.nav-tabs > li:hover:not(.active):not(.ui-state-active) .tb_icon,
                        .ui-tabs-nav .ui-state-focus:not(.ui-state-active) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#111111',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'ui_tabs.clickable_text_hover'
                ),
                'clickable_text_active' => array(
                    'label'       => 'Clickable text (active)',
                    'elements'    => '
                        .nav.nav-tabs.tb_style_2 > li.active a,
                        .nav.nav-tabs.tb_style_2 > li.ui-state-active a,
                        .nav.nav-tabs:not(.tb_style_2) > li.active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"]),
                        .ui-tabs-nav:not(.tb_style_2) .ui-state-active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"])
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'clickable_bg_active' => array(
                    'label'       => 'Clickable bg (active)',
                    'elements'    => '
                        .nav.nav-tabs > li.active:not([class*="tb_bg_str_"]):not([class*="tb_bg_hover_str_"]),
                        .ui-tabs-nav .ui-state-active:not([class*="tb_bg_str_"]):not([class*="tb_bg_hover_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_border_active' => array(
                    'label'       => 'Clickable border (active)',
                    'elements'    => '
                        .nav.nav-tabs > li.active:not([class*="tb_border_str_"]):not([class*="tb_border_hover_str_"]),
                        .ui-tabs-nav .ui-state-active:not([class*="tb_border_str_"]):not([class*="tb_border_hover_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_icon_active' => array(
                    'label'       => 'Clickable icon (active)',
                    'elements'    => '
                        .nav.nav-tabs > li.active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"]) .tb_icon,
                        .ui-tabs-nav .ui-state-active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"]) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'ui_tabs.clickable_text_active'
                )
            ),
            'ui_accordion' => array(
                '_label' => 'Accordion',
                'content_border' => array(
                    'label'       => 'Content border',
                    'elements'    => '
                        .tb_accordion.tb_style_1,
                        .panel-group > .panel > .panel-collapse,
                        .tb_accordion.tb_style_1 .tb_title + div,
                        .ui-accordion > .ui-widget-content
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_text' => array(
                    'label'       => 'Clickable text',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:not(:hover),
                        .tb_accordion > .tb_title,
                        .ui-accordion .ui-state-default:not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_text_str_"])
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_bg' => array(
                    'label'       => 'Clickable bg',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:not(:hover),
                        .ui-accordion .ui-state-default:not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_bg_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e6e6e6',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_border' => array(
                    'label'       => 'Clickable border',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:not(:hover),
                        .ui-accordion .ui-state-default:not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_border_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_icon' => array(
                    'label'       => 'Clickable icon',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:not(:hover) .tb_icon,
                        .ui-accordion .ui-state-default:not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'ui_accordion.clickable_text'
                ),
                'clickable_text_hover' => array(
                    'label'       => 'Clickable text (hover)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:hover,
                        .ui-accordion .ui-state-hover:not(.ui-state-active):not([class*="tb_text_hover_str_"]),
                        .ui-accordion .ui-state-focus:not(.ui-state-active):not([class*="tb_text_hover_str_"])
                    ',
                    'property'    => 'color',
                    'color'       => '#111111',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_bg_hover' => array(
                    'label'       => 'Clickable bg (hover)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:hover,
                        .ui-accordion .ui-state-hover:not(.ui-state-active):not([class*="tb_bg_hover_str_"]),
                        .ui-accordion .ui-state-focus:not(.ui-state-active):not([class*="tb_bg_hover_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_border_hover' => array(
                    'label'       => 'Clickable border (hover)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:hover,
                        .ui-accordion .ui-state-hover:not(.ui-state-active):not([class*="tb_border_hover_str_"]),
                        .ui-accordion .ui-state-focus:not(.ui-state-active):not([class*="tb_border_hover_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_icon_hover' => array(
                    'label'       => 'Clickable icon (hover)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:hover .tb_icon,
                        .ui-accordion .ui-state-hover:not(.ui-state-active) .tb_icon,
                        .ui-accordion .ui-state-focus:not(.ui-state-active) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#111111',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'ui_accordion.clickable_text_hover'
                ),
                'clickable_text_active' => array(
                    'label'       => 'Clickable text (active)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed),
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed):hover,
                        .ui-accordion .ui-state-active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"])
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'clickable_bg_active' => array(
                    'label'       => 'Clickable bg (active)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed),
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed):hover,
                        .ui-accordion .ui-state-active:not([class*="tb_bg_str_"]):not([class*="tb_bg_hover_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_border_active' => array(
                    'label'       => 'Clickable border (active)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed),
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed):hover,
                        .ui-accordion .ui-state-active:not([class*="tb_border_str_"]):not([class*="tb_border_hover_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_icon_active' => array(
                    'label'       => 'Clickable icon (active)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed) .tb_icon,
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed):hover .tb_icon,
                        .ui-accordion .ui-state-active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"]) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'ui_accordion.clickable_text_active'
                )
            ),
            'dialog_ui' => array(
                '_label' => 'Dialog / Datepicker / Timepicker / Tooltip / Autocomplete',
                'header_text' => array(
                    'label'       => 'Header/toolbar text',
                    'elements'    => '
                        .modal-header,
                        .bootstrap-datetimepicker-widget thead,
                        .ui-dialog .ui-widget-header,
                        .ui-datepicker .ui-widget-header
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'header_bg' => array(
                    'label'       => 'Header/toolbar bg',
                    'elements'    => '
                        .modal-header,
                        .bootstrap-datetimepicker-widget thead,
                        .ui-dialog .ui-widget-header,
                        .ui-datepicker .ui-widget-header
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent_bg'
                ),
                'header_border' => array(
                    'label'       => 'Header/toolbar border',
                    'elements'    => '
                        .modal-header,
                        .bootstrap-datetimepicker-widget tbody,
                        .ui-dialog .ui-widget-header,
                        .ui-datepicker .ui-widget-header
                    ',
                    'property'    => 'border-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent_bg'
                ),
                'content_text' => array(
                    'label'       => 'Content text',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget tbody,
                        .bootstrap-datetimepicker-widget tfoot,
                        .ui-dialog.ui-widget-content,
                        .ui-dialog.ui-widget-content a,
                        .ui-datepicker.ui-widget-content,
                        .ui-datepicker.ui-widget-content a,
                        .ui-tooltip.ui-widget-content,
                        .ui-tooltip.ui-widget-content a,
                        .ui-autocomplete.ui-widget-content,
                        .ui-autocomplete.ui-widget-content a
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'content_bg' => array(
                    'label'       => 'Content bg',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget,
                        .ui-dialog.ui-widget-content,
                        .ui-datepicker.ui-widget-content,
                        .ui-tooltip.ui-widget-content,
                        .ui-autocomplete.ui-widget-content
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'content_border' => array(
                    'label'       => 'Content border',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget,
                        .ui-dialog.ui-widget-content,
                        .ui-datepicker.ui-widget-content,
                        .ui-tooltip.ui-widget-content,
                        .ui-autocomplete.ui-widget-content
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_text' => array(
                    'label'       => 'Clickable text',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget td:not(:hover),
                        .bootstrap-datetimepicker-widget td > span:not(:hover),
                        .ui-dialog .ui-state-default,
                        .ui-dialog .ui-state-default a,
                        .ui-datepicker .ui-state-default,
                        .ui-datepicker .ui-state-default a,
                        .ui-tooltip .ui-state-default,
                        .ui-tooltip .ui-state-default a,
                        .ui-autocomplete .ui-state-default,
                        .ui-autocomplete .ui-state-default a
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_bg' => array(
                    'label'       => 'Clickable bg',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget td:not(:hover),
                        .bootstrap-datetimepicker-widget td > span:not(:hover),
                        .ui-dialog .ui-state-default,
                        .ui-datepicker .ui-state-default,
                        .ui-tooltip .ui-state-default,
                        .ui-autocomplete .ui-state-default
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e3e3e3',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_border' => array(
                    'label'       => 'Clickable border',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget td:not(:hover),
                        .bootstrap-datetimepicker-widget td > span:not(:hover),
                        .ui-dialog .ui-state-default,
                        .ui-datepicker .ui-state-default,
                        .ui-tooltip .ui-state-default,
                        .ui-autocomplete .ui-state-default
                    ',
                    'property'    => 'border-color',
                    'color'       => '#d3d3d3',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_icon' => array(
                    'label'       => 'Clickable icon',
                    'elements'    => '
                        .ui-dialog .ui-state-default .ui-icon,
                        .ui-datepicker .ui-state-default .ui-icon,
                        .ui-tooltip .ui-state-default .ui-icon,
                        .ui-autocomplete .ui-state-default .ui-icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_text_hover' => array(
                    'label'       => 'Clickable text (hover)',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget td:hover,
                        .bootstrap-datetimepicker-widget td > span:hover,
                        .ui-dialog .ui-state-hover,
                        .ui-dialog .ui-state-focus,
                        .ui-dialog .ui-state-hover a,
                        .ui-dialog .ui-state-hover a:hover,
                        .ui-datepicker .ui-state-hover,
                        .ui-datepicker .ui-state-focus,
                        .ui-datepicker .ui-state-hover a,
                        .ui-datepicker .ui-state-hover a:hover,
                        .ui-tooltip .ui-state-hover,
                        .ui-tooltip .ui-state-focus,
                        .ui-tooltip .ui-state-hover a,
                        .ui-tooltip .ui-state-hover a:hover,
                        .ui-autocomplete .ui-state-hover,
                        .ui-autocomplete .ui-state-focus,
                        .ui-autocomplete .ui-state-hover a,
                        .ui-autocomplete .ui-state-hover a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#111111',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_bg_hover' => array(
                    'label'       => 'Clickable bg (hover)',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget td:hover,
                        .bootstrap-datetimepicker-widget td > span:hover,
                        .ui-dialog .ui-state-hover,
                        .ui-dialog .ui-state-focus,
                        .ui-datepicker .ui-state-hover,
                        .ui-datepicker .ui-state-focus,
                        .ui-tooltip .ui-state-hover,
                        .ui-tooltip .ui-state-focus,
                        .ui-autocomplete .ui-state-hover,
                        .ui-autocomplete .ui-state-focus
                    ',
                    'property'    => 'background-color',
                    'color'       => '#d3d3d3',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_border_hover' => array(
                    'label'       => 'Clickable border (hover)',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget td:hover,
                        .bootstrap-datetimepicker-widget td > span:hover,
                        .ui-dialog .ui-state-hover,
                        .ui-dialog .ui-state-focus,
                        .ui-datepicker .ui-state-hover,
                        .ui-datepicker .ui-state-focus,
                        .ui-tooltip .ui-state-hover,
                        .ui-tooltip .ui-state-focus,
                        .ui-autocomplete .ui-state-hover,
                        .ui-autocomplete .ui-state-focus
                    ',
                    'property'    => 'border-color',
                    'color'       => '#c3c3c3',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_icon_hover' => array(
                    'label'       => 'Clickable icon (hover)',
                    'elements'    => '
                        .ui-dialog .ui-state-hover .ui-icon,
                        .ui-dialog .ui-state-focus .ui-icon,
                        .ui-datepicker .ui-state-hover .ui-icon,
                        .ui-datepicker .ui-state-focus .ui-icon,
                        .ui-tooltip .ui-state-hover .ui-icon,
                        .ui-tooltip .ui-state-focus .ui-icon,
                        .ui-autocomplete .ui-state-hover .ui-icon,
                        .ui-autocomplete .ui-state-focus .ui-icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_text_active' => array(
                    'label'       => 'Clickable text (active)',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget td.active,
                        .bootstrap-datetimepicker-widget td > span.active,
                        .ui-dialog .ui-state-active,
                        .ui-dialog .ui-state-active a,
                        .ui-dialog.ui-widget-content .ui-state-active,
                        .ui-dialog.ui-widget-content .ui-state-active a,
                        .ui-datepicker .ui-state-active,
                        .ui-datepicker .ui-state-active a,
                        .ui-datepicker.ui-widget-content .ui-state-active,
                        .ui-datepicker.ui-widget-content .ui-state-active a,
                        .ui-tooltip .ui-state-active,
                        .ui-tooltip .ui-state-active a,
                        .ui-tooltip.ui-widget-content .ui-state-active,
                        .ui-tooltip.ui-widget-content .ui-state-active a,
                        .ui-autocomplete .ui-state-active,
                        .ui-autocomplete .ui-state-active a,
                        .ui-autocomplete.ui-widget-content .ui-state-active,
                        .ui-autocomplete.ui-widget-content .ui-state-active a
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_bg_active' => array(
                    'label'       => 'Clickable bg (active)',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget td.active,
                        .bootstrap-datetimepicker-widget td > span.active,
                        .ui-dialog .ui-state-active,
                        .ui-datepicker .ui-state-active,
                        .ui-tooltip .ui-state-active,
                        .ui-autocomplete .ui-state-active
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_border_active' => array(
                    'label'       => 'Clickable border (active)',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget td.active,
                        .bootstrap-datetimepicker-widget td > span.active,
                        .ui-dialog .ui-state-active,
                        .ui-datepicker .ui-state-active,
                        .ui-tooltip .ui-state-active,
                        .ui-autocomplete .ui-state-active
                    ',
                    'property'    => 'border-color',
                    'color'       => '#222222',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'clickable_icon_active' => array(
                    'label'       => 'Clickable icon (active)',
                    'elements'    => '
                        .ui-dialog .ui-state-active .ui-icon,
                        .ui-datepicker .ui-state-active .ui-icon,
                        .ui-tooltip .ui-state-active .ui-icon,
                        .ui-autocomplete .ui-state-active .ui-icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'highlight_text' => array(
                    'label'       => 'Highlight text',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget .today:not(.active),
                        .ui-dialog .ui-state-highlight,
                        .ui-dialog .ui-state-highlight a,
                        .ui-datepicker .ui-state-highlight,
                        .ui-datepicker .ui-state-highlight a,
                        .ui-tooltip .ui-state-highlight,
                        .ui-tooltip .ui-state-highlight a,
                        .ui-autocomplete .ui-state-highlight,
                        .ui-autocomplete .ui-state-highlight a
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'highlight_bg' => array(
                    'label'       => 'Highlight bg',
                    'elements'    => '
                        .bootstrap-datetimepicker-widget .today:not(.active),
                        .ui-dialog .ui-state-highlight,
                        .ui-datepicker .ui-state-highlight,
                        .ui-tooltip .ui-state-highlight,
                        .ui-autocomplete .ui-state-highlight
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'highlight_border' => array(
                    'label'       => 'Highlight border',
                    'elements'    => '
                        .ui-dialog .ui-state-highlight,
                        .ui-datepicker .ui-state-highlight,
                        .ui-tooltip .ui-state-highlight,
                        .ui-autocomplete .ui-state-highlight
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'error_text' => array(
                    'label'       => 'Error text',
                    'elements'    => '
                        .ui-dialog .ui-state-error,
                        .ui-dialog .ui-state-error a,
                        .ui-dialog .ui-state-error-text,
                        .ui-datepicker .ui-state-error,
                        .ui-datepicker .ui-state-error a,
                        .ui-datepicker .ui-state-error-text,
                        .ui-tooltip .ui-state-error,
                        .ui-tooltip .ui-state-error a,
                        .ui-tooltip .ui-state-error-text,
                        .ui-autocomplete .ui-state-error,
                        .ui-autocomplete .ui-state-error a,
                        .ui-autocomplete .ui-state-error-text
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'error_bg' => array(
                    'label'       => 'Error bg',
                    'elements'    => '
                        .ui-dialog .ui-state-error,
                        .ui-datepicker .ui-state-error,
                        .ui-tooltip .ui-state-error,
                        .ui-autocomplete .ui-state-error
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'error_border' => array(
                    'label'       => 'Error border',
                    'elements'    => '
                        .ui-dialog .ui-state-error,
                        .ui-datepicker .ui-state-error,
                        .ui-tooltip .ui-state-error,
                        .ui-autocomplete .ui-state-error
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            ),
            'dialog_forms' => array(
                '_label' => 'Dialog forms',
                'input_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        .modal input:not(:hover):not(:focus),
                        .modal select:not(:hover):not(:focus),
                        .modal textarea:not(:hover):not(:focus),
                        .modal .input-group:not(:hover):not(:focus),
                        .ui-dialog input:not(:hover):not(:focus),
                        .ui-dialog select:not(:hover):not(:focus),
                        .ui-dialog textarea:not(:hover):not(:focus),
                        .ui-dialog .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text'
                ),
                'input_bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        .modal input:not(:hover):not(:focus),
                        .modal select:not(:hover):not(:focus),
                        .modal textarea:not(:hover):not(:focus),
                        .modal .input-group:not(:hover):not(:focus),
                        .ui-dialog input:not(:hover):not(:focus),
                        .ui-dialog select:not(:hover):not(:focus),
                        .ui-dialog textarea:not(:hover):not(:focus),
                        .ui-dialog .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg'
                ),
                'input_border_top_left' => array(
                    'label'       => 'Top/left border',
                    'elements'    => '
                        .modal input:not(:hover):not(:focus),
                        .modal select:not(:hover):not(:focus),
                        .modal textarea:not(:hover):not(:focus),
                        .modal .input-group:not(:hover):not(:focus),
                        .ui-dialog input:not(:hover):not(:focus),
                        .ui-dialog select:not(:hover):not(:focus),
                        .ui-dialog textarea:not(:hover):not(:focus),
                        .ui-dialog .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left'
                ),
                'input_border_bottom_right' => array(
                    'label'       => 'Bottom/right border',
                    'elements'    => '
                        .modal input:not(:hover):not(:focus),
                        .modal select:not(:hover):not(:focus),
                        .modal textarea:not(:hover):not(:focus),
                        .modal .input-group:not(:hover):not(:focus),
                        .ui-dialog input:not(:hover):not(:focus),
                        .ui-dialog select:not(:hover):not(:focus),
                        .ui-dialog textarea:not(:hover):not(:focus),
                        .ui-dialog .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right'
                ),
                'input_text_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '
                        .modal input:hover:not(:focus),
                        .modal select:hover:not(:focus),
                        .modal textarea:hover:not(:focus),
                        .modal .input-group:hover:not(:focus),
                        .ui-dialog input:hover:not(:focus),
                        .ui-dialog select:hover:not(:focus),
                        .ui-dialog textarea:hover:not(:focus),
                        .ui-dialog .input-group:hover:not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_hover'
                ),
                'input_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '
                        .modal input:hover:not(:focus),
                        .modal select:hover:not(:focus),
                        .modal textarea:hover:not(:focus),
                        .modal .input-group:hover:not(:focus),
                        .ui-dialog input:hover:not(:focus),
                        .ui-dialog select:hover:not(:focus),
                        .ui-dialog textarea:hover:not(:focus),
                        .ui-dialog .input-group:hover:not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_hover'
                ),
                'input_border_top_left_hover' => array(
                    'label'       => 'Top/left border (hover)',
                    'elements'    => '
                        .modal input:hover:not(:focus),
                        .modal select:hover:not(:focus),
                        .modal textarea:hover:not(:focus),
                        .modal .input-group:hover:not(:focus),
                        .ui-dialog input:hover:not(:focus),
                        .ui-dialog select:hover:not(:focus),
                        .ui-dialog textarea:hover:not(:focus),
                        .ui-dialog .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_hover'
                ),
                'input_border_bottom_right_hover' => array(
                    'label'       => 'Bottom/right border (hover)',
                    'elements'    => '
                        .modal input:hover:not(:focus),
                        .modal select:hover:not(:focus),
                        .modal textarea:hover:not(:focus),
                        .modal .input-group:hover:not(:focus),
                        .ui-dialog input:hover:not(:focus),
                        .ui-dialog select:hover:not(:focus),
                        .ui-dialog textarea:hover:not(:focus),
                        .ui-dialog .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_hover'
                ),
                'input_text_focus' => array(
                    'label'       => 'Text (focus)',
                    'elements'    => '
                        .modal input:focus,
                        .modal select:focus,
                        .modal textarea:focus,
                        .modal .input-group:focus,
                        .ui-dialog input:focus,
                        .ui-dialog select:focus,
                        .ui-dialog textarea:focus,
                        .ui-dialog .input-group:focus
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_focus'
                ),
                'input_bg_focus' => array(
                    'label'       => 'Background (focus)',
                    'elements'    => '
                        .modal input:focus,
                        .modal select:focus,
                        .modal textarea:focus,
                        .modal .input-group:focus,
                        .ui-dialog input:focus,
                        .ui-dialog select:focus,
                        .ui-dialog textarea:focus,
                        .ui-dialog .input-group:focus
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_focus'
                ),
                'input_border_top_left_focus' => array(
                    'label'       => 'Top/left border (focus)',
                    'elements'    => '
                        .modal input:focus,
                        .modal select:focus,
                        .modal textarea:focus,
                        .modal .input-group:focus,
                        .ui-dialog input:focus,
                        .ui-dialog select:focus,
                        .ui-dialog textarea:focus,
                        .ui-dialog .input-group:focus
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_focus'
                ),
                'input_border_bottom_right_focus' => array(
                    'label'       => 'Bottom/right border (focus)',
                    'elements'    => '
                        .modal input:focus,
                        .modal select:focus,
                        .modal textarea:focus,
                        .modal .input-group:focus,
                        .ui-dialog input:focus,
                        .ui-dialog select:focus,
                        .ui-dialog textarea:focus,
                        .ui-dialog .input-group:focus
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_focus'
                ),
                'input_text_error' => array(
                    'label'       => 'Text (error)',
                    'elements'    => '
                        .modal .has-error input,
                        .modal .has-error select,
                        .modal .has-error textarea,
                        .ui-dialog .has-error input,
                        .ui-dialog .has-error select,
                        .ui-dialog .has-error textarea
                    ',
                    'property'    => 'color',
                    'color'       => '#84290a',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_error'
                ),
                'input_bg_error' => array(
                    'label'       => 'Background (error)',
                    'elements'    => '
                        .modal .has-error input,
                        .modal .has-error select,
                        .modal .has-error textarea,
                        .ui-dialog .has-error input,
                        .ui-dialog .has-error select,
                        .ui-dialog .has-error textarea
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_error'
                ),
                'input_border_top_left_error' => array(
                    'label'       => 'Top/left border (error)',
                    'elements'    => '
                        .modal .has-error input,
                        .modal .has-error select,
                        .modal .has-error textarea,
                        .ui-dialog .has-error input,
                        .ui-dialog .has-error select,
                        .ui-dialog .has-error textarea
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#ffcdbc',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_error'
                ),
                'input_border_bottom_right_error' => array(
                    'label'       => 'Bottom/right border (error)',
                    'elements'    => '
                        .modal .has-error input,
                        .modal .has-error select,
                        .modal .has-error textarea,
                        .ui-dialog .has-error input,
                        .ui-dialog .has-error select,
                        .ui-dialog .has-error textarea
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_error'
                )
            ),
            'dialog_buttons' => array(
                '_label' => 'Dialog buttons',
                'button' => array(
                    'label'       => 'Primary bg',
                    'elements'    => '
                        .modal-body .btn:not(:hover):not(.btn-default),
                        .ui-dialog .btn:not(:hover):not(.btn-default)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button'
                ),
                'button_text' => array(
                    'label'       => 'Primary text',
                    'elements'    => '
                        .modal-body .btn:not(:hover):not(.btn-default),
                        .ui-dialog .btn:not(:hover):not(.btn-default)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text'
                ),
                'button_hover' => array(
                    'label'       => 'Primary bg (hover)',
                    'elements'    => '
                        .modal-body .btn:not(.active):not(.btn-default):hover,
                        .ui-dialog .btn:not(.active):not(.btn-default):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'label'       => 'Primary text (hover)',
                    'elements'    => '
                        .modal-body .btn:not(.active):not(.btn-default):hover,
                        .ui-dialog .btn:not(.active):not(.btn-default):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text_hover'
                ),
                'button_default' => array(
                    'label'       => 'Default bg',
                    'elements'    => '
                        .modal-body .btn.btn-default:not(:hover),
                        .ui-dialog .btn.btn-default:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#d6d6d6',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default'
                ),
                'button_default_text' => array(
                    'label'       => 'Default text',
                    'elements'    => '
                        .modal-body .btn.btn-default:not(:hover),
                        .ui-dialog .btn.btn-default:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_text'
                ),
                'button_default_hover' => array(
                    'label'       => 'Default bg (hover)',
                    'elements'    => '
                        .modal-body .btn.btn-default:not(.active):hover,
                        .ui-dialog .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_hover'
                ),
                'button_default_text_hover' => array(
                    'label'       => 'Default text (hover)',
                    'elements'    => '
                        .modal-body .btn.btn-default:not(.active):hover,
                        .ui-dialog .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_text_hover'
                )
            ),
            'system_messages' => array(
                '_label' => 'System messages',
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '.noty_message',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text'
                ),
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => '.noty_message a',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text_links'
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '.noty_message a:not(.btn):hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text_links_hover'
                ),
                'title' => array(
                    'label'       => 'Title',
                    'elements'    => '.noty_message h3',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.titles'
                ),
                'button' => array(
                    'label'       => 'Button bg',
                    'elements'    => '
                        .noty_message .btn:not(:hover):not(.btn-default)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button'
                ),
                'button_text' => array(
                    'label'       => 'Button text',
                    'elements'    => '
                        .noty_message .btn:not(:hover):not(.btn-default)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text'
                ),
                'button_hover' => array(
                    'label'       => 'Button bg (hover)',
                    'elements'    => '
                        .noty_message .btn:not(.active):not(.btn-default):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'label'       => 'Primary text (hover)',
                    'elements'    => '
                        .noty_message .btn:not(.active):not(.btn-default):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text_hover'
                ),
                'button_default' => array(
                    'label'       => 'Close button bg',
                    'elements'    => '
                        .noty_message .btn.btn-default:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#d6d6d6',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default'
                ),
                'button_default_text' => array(
                    'label'       => 'Close button text',
                    'elements'    => '
                        .noty_message .btn.btn-default:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_text'
                ),
                'button_default_hover' => array(
                    'label'       => 'Close button bg (hover)',
                    'elements'    => '
                        .noty_message .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_hover'
                ),
                'button_default_text_hover' => array(
                    'label'       => 'Close button text (hover)',
                    'elements'    => '
                        .noty_message .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_text_hover'
                ),
                'bg' => array(
                    'label'       => 'Background',
                    'elements'    => '.noty_message',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
            ),

            /* ------------------------------------------------------
               T H E M E   C O M P O N E N T S
            ------------------------------------------------------ */
            'product_listing' => array(
                '_label' => 'Product box',
                'product_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text'
                ),
                'product_links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb a:not(.btn):not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links'
                ),
                'product_links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb a:not(.btn):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links_hover'
                ),
                'product_title' => array(
                    'label'       => 'Title',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb.product-thumb .name a:not(:hover),
                        :not(.tb_item_info_hover) .product-thumb.product-thumb h4 a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text'
                ),
                'product_title_hover' => array(
                    'label'       => 'Title (hover)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb.product-thumb .name a:hover,
                        :not(.tb_item_info_hover) .product-thumb.product-thumb h4 a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'product_price' => array(
                    'label'       => 'Price',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .price
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'product_promo_price' => array(
                    'label'       => 'Promo price',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .price-new
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'product_old_price' => array(
                    'label'       => 'Old price',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .price-old
                    ',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'product_tax_price' => array(
                    'label'       => 'Price without tax',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .price-tax
                    ',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'rating_percent' => array(
                    'label'       => 'Rating stars (rate)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .rating .tb_percent
                    ',
                    'property'    => 'color',
                    'color'       => '#ffd200',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'rating_base' => array(
                    'label'       => 'Rating stars (base)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .rating .tb_base
                    ',
                    'property'    => 'color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'rating_text' => array(
                    'label'       => 'Rating text',
                    'elements'    => '
                        :not(.tb_item_info_hover) .rating .tb_average
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_text'
                ),
                'product_add_to_cart_bg' => array(
                    'label'       => 'Cart button bg',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_add_to_cart .btn:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button'
                ),
                'product_add_to_cart_text' => array(
                    'label'       => 'Cart button text',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_add_to_cart .btn:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text'
                ),
                'product_add_to_cart_bg_hover' => array(
                    'label'       => 'Cart button bg (hover)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_add_to_cart .btn:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_hover'
                ),
                'product_add_to_cart_text_hover' => array(
                    'label'       => 'Cart button text (hover)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_add_to_cart .btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text_hover'
                ),
                'product_compare_bg' => array(
                    'label'       => 'Compare button bg',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_compare .btn:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button'
                ),
                'product_compare_text' => array(
                    'label'       => 'Compare button text',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_compare .btn:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text'
                ),
                'product_compare_bg_hover' => array(
                    'label'       => 'Compare button bg (hover)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_compare .btn:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_hover'
                ),
                'product_compare_text_hover' => array(
                    'label'       => 'Compare button text (hover)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_compare .btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text_hover'
                ),
                'product_wishlist_bg' => array(
                    'label'       => 'Wishlist button bg',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_wishlist .btn:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button'
                ),
                'product_wishlist_text' => array(
                    'label'       => 'Wishlist button text',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_wishlist .btn:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text'
                ),
                'product_wishlist_bg_hover' => array(
                    'label'       => 'Wishlist button bg (hover)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_wishlist .btn:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_hover'
                ),
                'product_wishlist_text_hover' => array(
                    'label'       => 'Wishlist button text (hover)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_wishlist .btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text_hover'
                ),
                'product_quickview_bg' => array(
                    'label'       => 'Quickview button bg',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_quickview .btn:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button'
                ),
                'product_quickview_text' => array(
                    'label'       => 'Quickview button text',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_quickview .btn:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text'
                ),
                'product_quickview_bg_hover' => array(
                    'label'       => 'Quickview button bg (hover)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_quickview .btn:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_hover'
                ),
                'product_quickview_text_hover' => array(
                    'label'       => 'Quickview button text (hover)',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_button_quickview .btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text_hover'
                ),
                'product_counter_text' => array(
                    'label'       => 'Counter text',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_counter
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_text'
                ),
                'product_counter_bg' => array(
                    'label'       => 'Counter bg',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_counter
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'product_counter_title_text' => array(
                    'label'       => 'Counter label',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_counter_label
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'product_listing.product_text'
                ),
                'product_counter_title_bg' => array(
                    'label'       => 'Counter label bg',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_counter_label
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'product_sale_label_bg' => array(
                    'label'       => 'Sale label bg',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_label_special
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'product_sale_label_text' => array(
                    'label'       => 'Sale label text',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_label_special
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'product_new_label_bg' => array(
                    'label'       => 'New label bg',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_label_new
                    ',
                    'property'    => 'background-color',
                    'color'       => '#90c819',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'product_new_label_text' => array(
                    'label'       => 'New label text',
                    'elements'    => '
                        :not(.tb_item_info_hover) .product-thumb .tb_label_new
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'product_separator' => array(
                    'label'       => 'Product border (bordered style)',
                    'elements'    => '
                        .tb_style_bordered,
                        .tb_style_bordered > *,
                        .tb_style_bordered > *:after,
                        .tb_style_bordered > *:before,
                        .tb_style_bordered + .pagination,
                        .swiper-wrapper,
                        .swiper-slide > *,
                        .swiper-slide > *:after,
                        .tb_slider_pagination

                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.column_border'
                ),
                'product_bg' => array(
                    'label'       => 'Product bg',
                    'elements'    => '
                        :not(.tb_back) > .product-thumb,
                        .tb_item_info_active .product-thumb
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
            ),
            'product_listing_hover' => array(
                '_label' => 'Product box (hover)',
                'product_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_text'
                ),
                'product_links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb a:not(.btn):not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_links'
                ),
                'product_links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb a:not(.btn):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_links_hover'
                ),
                'product_title' => array(
                    'label'       => 'Title',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb.product-thumb .name a:not(:hover),
                        .tb_item_info_hover .product-thumb.product-thumb h4 a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_title'
                ),
                'product_title_hover' => array(
                    'label'       => 'Title (hover)',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb.product-thumb .name a:hover,
                        .tb_item_info_hover .product-thumb.product-thumb h4 a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_title_hover'
                ),
                'product_price' => array(
                    'label'       => 'Price',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .price
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_price'
                ),
                'product_promo_price' => array(
                    'label'       => 'Promo price',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .price-new
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_promo_price'
                ),
                'product_old_price' => array(
                    'label'       => 'Old price',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .price-old
                    ',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_old_price'
                ),
                'product_tax_price' => array(
                    'label'       => 'Price without tax',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .price-tax
                    ',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_tax_price'
                ),
                'rating_percent' => array(
                    'label'       => 'Rating stars (rate)',
                    'elements'    => '
                        .tb_item_info_hover .rating .tb_percent
                    ',
                    'property'    => 'color',
                    'color'       => '#ffd200',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.rating_percent'
                ),
                'rating_base' => array(
                    'label'       => 'Rating stars (base)',
                    'elements'    => '
                        .tb_item_info_hover .rating .tb_base
                    ',
                    'property'    => 'color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.rating_base'
                ),
                'rating_text' => array(
                    'label'       => 'Rating text',
                    'elements'    => '
                        .tb_item_info_hover .rating .tb_average
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.rating_text'
                ),
                'product_add_to_cart_bg' => array(
                    'label'       => 'Cart button bg',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_add_to_cart .btn:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_add_to_cart_bg'
                ),
                'product_add_to_cart_text' => array(
                    'label'       => 'Cart button text',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_add_to_cart .btn:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_add_to_cart_text'
                ),
                'product_add_to_cart_bg_hover' => array(
                    'label'       => 'Cart button bg (hover)',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_add_to_cart .btn:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_add_to_cart_bg_hover'
                ),
                'product_add_to_cart_text_hover' => array(
                    'label'       => 'Cart button text (hover)',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_add_to_cart .btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_add_to_cart_text_hover'
                ),
                'product_compare_bg' => array(
                    'label'       => 'Compare button bg',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_compare .btn:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_compare_bg'
                ),
                'product_compare_text' => array(
                    'label'       => 'Compare button text',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_compare .btn:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_compare_text'
                ),
                'product_compare_bg_hover' => array(
                    'label'       => 'Compare button bg (hover)',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_compare .btn:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_compare_bg_hover'
                ),
                'product_compare_text_hover' => array(
                    'label'       => 'Compare button text (hover)',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_compare .btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_compare_text_hover'
                ),
                'product_wishlist_bg' => array(
                    'label'       => 'Wishlist button bg',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_wishlist .btn:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_wishlist_bg'
                ),
                'product_wishlist_text' => array(
                    'label'       => 'Wishlist button text',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_wishlist .btn:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_wishlist_text'
                ),
                'product_wishlist_bg_hover' => array(
                    'label'       => 'Wishlist button bg (hover)',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_wishlist .btn:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_wishlist_bg_hover'
                ),
                'product_wishlist_text_hover' => array(
                    'label'       => 'Wishlist button text (hover)',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_wishlist .btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_wishlist_text_hover'
                ),
                'product_quickview_bg' => array(
                    'label'       => 'Quickview button bg',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_quickview .btn:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_quickview_bg'
                ),
                'product_quickview_text' => array(
                    'label'       => 'Quickview button text',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_quickview .btn:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_quickview_text'
                ),
                'product_quickview_bg_hover' => array(
                    'label'       => 'Quickview button bg (hover)',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_quickview .btn:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_quickview_bg_hover'
                ),
                'product_quickview_text_hover' => array(
                    'label'       => 'Quickview button text (hover)',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_button_quickview .btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_quickview_text_hover'
                ),
                'product_counter_text' => array(
                    'label'       => 'Counter text',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_counter
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing_hover.product_text'
                ),
                'product_counter_bg' => array(
                    'label'       => 'Counter bg',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_counter
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_counter_bg'
                ),
                'product_counter_title_text' => array(
                    'label'       => 'Counter label',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_counter_label
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_counter_title_text'
                ),
                'product_counter_title_bg' => array(
                    'label'       => 'Counter label bg',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_counter_label
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_counter_title_bg'
                ),
                'product_sale_label_bg' => array(
                    'label'       => 'Sale label bg',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_label_special
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_sale_label_bg'
                ),
                'product_sale_label_text' => array(
                    'label'       => 'Sale label text',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_label_special
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_sale_label_text'
                ),
                'product_new_label_bg' => array(
                    'label'       => 'Sale label bg',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_label_new
                    ',
                    'property'    => 'background-color',
                    'color'       => '#90c819',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_new_label_bg'
                ),
                'product_new_label_text' => array(
                    'label'       => 'Sale label text',
                    'elements'    => '
                        .tb_item_info_hover .product-thumb .tb_label_new
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'product_listing.product_new_label_text'
                ),
                'product_bg' => array(
                    'label'       => 'Product bg',
                    'elements'    => '
                        .tb_item_hovered,
                        .tb_item_info_hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
            ),

            /* Gallery & Fire slider ----------------------------- */

            'gallery_navigation' => array(
                '_label' => 'Gallery Prev/Next buttons',
                'button_default' => array(
                    'label'       => 'Text',
                    'elements'    => '.mSButtons:not(:hover) svg',
                    'property'    => 'fill',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'button_bg_default' => array(
                    'label'       => 'Background',
                    'elements'    => '.mSButtons:not(:hover):after',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'button_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '.mSButtons:hover svg',
                    'property'    => 'fill',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'button_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '.mSButtons:hover:after',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
            ),
            'gallery_pagination' => array(
                '_label' => 'Gallery Pagination',
                'default' => array(
                    'label'       => 'Text',
                    'elements'    => '.mSPages li:not(:hover):not(.active)',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '.mSPages li:hover',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'active' => array(
                    'label'       => 'Text (active)',
                    'elements'    => '.mSPages li.active, .mSPages li.active:hover',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            ),
            'gallery_fullscreen_button' => array(
                '_label' => '"Go fullscreen" button',
                'fbutton_default' => array(
                    'label'       => 'Text',
                    'elements'    => '.tb_fullscreen_button',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'fbutton_bg_default' => array(
                    'label'       => 'Background',
                    'elements'    => '.tb_fullscreen_button',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'fbutton_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '.tb_fullscreen_button:hover',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'fbutton_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '.tb_fullscreen_button:hover',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent_hover'
                ),
            ),
            'gallery_caption' => array(
                '_label' => 'Caption',
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '.tb_caption .tb_text',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'bg' => array(
                    'label'       => 'Background',
                    'elements'    => '.tb_caption:after',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
            ),

            /* Carousel (swiper) --------------------------------- */

            'carousel_nav' => array(
                '_label' => 'Carousel navigation',
                'button_default' => array(
                    'label'       => 'Prev/Next button',
                    'elements'    => '
                        .tb_slider_controls a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'button_hover' => array(
                    'label'       => 'Prev/Next button (hover)',
                    'elements'    => '
                        .tb_slider_controls a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent_hover'
                ),
                'button_inactive' => array(
                    'label'       => 'Prev/Next button (inactive)',
                    'elements'    => '
                        .tb_slider_controls a.tb_disabled,
                        .tb_slider_controls a.tb_disabled:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
            ),
            'carousel_pagination' => array(
                '_label' => 'Carousel pagination',
                'pagination_default' => array(
                    'label'       => 'Pagination',
                    'elements'    => '
                        .tb_slider_pagination span:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'pagination_hover' => array(
                    'label'       => 'Pagination (hover)',
                    'elements'    => '
                        .tb_slider_pagination span:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'pagination_active' => array(
                    'label'       => 'Pagination (active)',
                    'elements'    => '
                        .tb_slider_pagination span.tb_active,
                        .tb_slider_pagination span.tb_active:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            ),

            /* ------------------------------------------------------
               M O B I L E   H E A D E R
            ------------------------------------------------------ */
            'mobile_header' => array(
                '_label' => 'Main',
                'accent' => array(
                    'label'       => 'Accent',
                    'elements'    => '
                        .tbMobileMenu .tb_main_color,
                        .tbMobileMenu .tb_hover_main_color:hover,
                        .tbMobileMenu .colorbox,
                        .tbMobileMenu .agree,
                        .tbMobileMenu .tb_list_1 > li:before,
                        .tbMobileMenu .tb_list_1 > li > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#bff222',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'accent_hover' => array(
                    'label'       => 'Accent (hover)',
                    'elements'    => '
                        .tbMobileMenu a.tb_main_color:hover,
                        .tbMobileMenu a.colorbox:hover,
                        .tbMobileMenu a.agree:hover,
                        .tbMobileMenu .tb_main_color_hover:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.accent'
                ),
                'accent_bg' => array(
                    'label'       => 'Accent bg',
                    'elements'    => '
                        .tbMobileMenu .tb_main_color_bg,
                        .tbMobileMenu .tb_hover_main_color_bg:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'mobile_header.accent'
                ),
                'accent_bg_hover' => array(
                    'label'       => 'Accent bg (hover)',
                    'elements'    => '
                        .tbMobileMenu a.tb_main_color_bg:hover,
                        .tbMobileMenu .tb_main_color_bg_hover:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'mobile_header.accent_hover'
                ),
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        .tbMobileMenu,
                        .tbMobileMenu .nav-responsive .dropdown-menu,
                        .tbMobileMenu .nav-responsive .dropdown-menu .dropdown-menu
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text'
                ),
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        .tbMobileMenu a:not(:hover),
                        .tbMobileMenu .nav-responsive .dropdown-menu a:not(:hover),
                        .tbMobileMenu .nav-responsive > li.tb_selected > a > span.tb_text
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links'
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        .tbMobileMenu a:hover,
                        .tbMobileMenu .nav-responsive .dropdown-menu a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links_hover'
                ),
                'text_links' => array(
                    'label'       => 'Links in text',
                    'elements'    => '
                        .tbMobileMenu .tb_text_wrap a:not(.btn):not(:hover),
                        .tbMobileMenu .nav-responsive .dropdown-menu .tb_text_wrap a:not(.btn):not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text_links'
                ),
                'text_links_hover' => array(
                    'label'       => 'Links in text (hover)',
                    'elements'    => '
                        .tbMobileMenu .tb_text_wrap a:not(.btn):hover,
                        .tbMobileMenu .nav-responsive .dropdown-menu .nav-responsive .dropdown-menu .tb_text_wrap a:not(.btn):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.text_links_hover'
                ),
                'titles' => array(
                    'label'       => 'Titles',
                    'elements'    => '
                        .tbMobileMenu h1,
                        .tbMobileMenu h2,
                        .tbMobileMenu h3,
                        .tbMobileMenu h4,
                        .tbMobileMenu h5,
                        .tbMobileMenu h6,
                        .tbMobileMenu .h1,
                        .tbMobileMenu .h2,
                        .tbMobileMenu .h3,
                        .tbMobileMenu .h4,
                        .tbMobileMenu .h5,
                        .tbMobileMenu .h6,
                        .tbMobileMenu a.h1:not(:hover),
                        .tbMobileMenu a.h2:not(:hover),
                        .tbMobileMenu a.h3:not(:hover),
                        .tbMobileMenu a.h4:not(:hover),
                        .tbMobileMenu a.h5:not(:hover),
                        .tbMobileMenu a.h6:not(:hover),
                        .tbMobileMenu .nav-responsive .dropdown-menu h1,
                        .tbMobileMenu .nav-responsive .dropdown-menu h2,
                        .tbMobileMenu .nav-responsive .dropdown-menu h3,
                        .tbMobileMenu .nav-responsive .dropdown-menu h4,
                        .tbMobileMenu .nav-responsive .dropdown-menu h5,
                        .tbMobileMenu .nav-responsive .dropdown-menu h6,
                        .tbMobileMenu .nav-responsive .dropdown-menu .h1,
                        .tbMobileMenu .nav-responsive .dropdown-menu .h2,
                        .tbMobileMenu .nav-responsive .dropdown-menu .h3,
                        .tbMobileMenu .nav-responsive .dropdown-menu .h4,
                        .tbMobileMenu .nav-responsive .dropdown-menu .h5,
                        .tbMobileMenu .nav-responsive .dropdown-menu .h6,
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h1:not(:hover),
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h2:not(:hover),
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h3:not(:hover),
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h4:not(:hover),
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h5:not(:hover),
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h6:not(:hover),
                        .tbMobileMenu legend,
                        .tbMobileMenu .panel-heading,
                        .tbMobileMenu .box-heading
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.titles'
                ),
                'titles_hover' => array(
                    'label'       => 'Title links (hover)',
                    'elements'    => '
                        .tbMobileMenu a.h1:hover,
                        .tbMobileMenu a.h2:hover,
                        .tbMobileMenu a.h3:hover,
                        .tbMobileMenu a.h4:hover,
                        .tbMobileMenu a.h5:hover,
                        .tbMobileMenu a.h6:hover,
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h1:hover,
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h2:hover,
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h3:hover,
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h4:hover,
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h5:hover,
                        .tbMobileMenu .nav-responsive .dropdown-menu a.h6:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'List bullets',
                    'elements'    => '
                        .tbMobileMenu .tb_list_1 > li:before,
                        .tbMobileMenu .tb_list_1 > li > a:before,
                        .tbMobileMenu .nav-responsive .dropdown-menu.tb_list_1  > li:before,
                        .tbMobileMenu .nav-responsive .dropdown-menu.tb_list_1  > li > a:before,
                        .tbMobileMenu .nav-responsive .dropdown-menu .tb_list_1 > li:before,
                        .tbMobileMenu .nav-responsive .dropdown-menu .tb_list_1 > li > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'dropdown_menu.accent'
                ),
                'column_border' => array(
                    'label'       => 'Column border',
                    'elements'    => '
                        .tbMobileMenu .tb_separate_columns > .col,
                        .tbMobileMenu .nav-responsive .dropdown-menu .tb_separate_columns > .col
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.column_border'
                ),
                'subtle_base' => array(
                    'label'       => 'Subtle color base',
                    'elements'    => '
                        .tbMobileMenu,
                        .tbMobileMenu .nav-responsive .dropdown-menu
                    ',
                    'property'    => 'subtle',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'main.subtle_base'
                ),
                'bg' => array(
                    'label'       => 'Mobile menu bg',
                    'elements'    => '
                        #wrapper .tbMobileMenu
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            ),
            'mobile_tables_thead' => array(
                '_label' => 'Table head',
                'th_text' => array(
                    'label'       => 'Cell text',
                    'elements'    => '
                        #wrapper .tbMobileMenu .table > thead > tr > th,
                        #wrapper .tbMobileMenu .table > thead > tr > td
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_thead.th_text'
                ),
                'th_bg' => array(
                    'label'       => 'Cell bg',
                    'elements'    => '
                        #wrapper .tbMobileMenu .table > thead > tr > th,
                        #wrapper .tbMobileMenu .table > thead > tr > td
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e9e9e9',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_thead.th_bg'
                ),
                'th_border' => array(
                    'label'       => 'Cell border',
                    'elements'    => '
                        #wrapper .tbMobileMenu .table > thead > tr > th,
                        #wrapper .tbMobileMenu .table > thead > tr > td
                    ',
                    'property'    => 'border-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_thead.th_border'
                )
            ),
            'mobile_tables_tbody' => array(
                '_label' => 'Table body / footer',
                'td_text' => array(
                    'label'       => 'Cell text',
                    'elements'    => '
                        #wrapper .tbMobileMenu .table > tbody > tr > th,
                        #wrapper .tbMobileMenu .table > tbody > tr > td,
                        #wrapper .tbMobileMenu .table > tfoot > tr > th,
                        #wrapper .tbMobileMenu .table > tfoot > tr > td
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_tbody.td_text'
                ),
                'td_bg' => array(
                    'label'       => 'Cell bg',
                    'elements'    => '
                        #wrapper .tbMobileMenu .table > tbody > tr > th,
                        #wrapper .tbMobileMenu .table > tbody > tr > td,
                        #wrapper .tbMobileMenu .table > tfoot > tr > th,
                        #wrapper .tbMobileMenu .table > tfoot > tr > td
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_tbody.td_bg'
                ),
                'td_border' => array(
                    'label'       => 'Cell border',
                    'elements'    => '
                        #wrapper .tbMobileMenu .table > tbody > tr > th,
                        #wrapper .tbMobileMenu .table > tbody > tr > td,
                        #wrapper .tbMobileMenu .table > tfoot > tr > th,
                        #wrapper .tbMobileMenu .table > tfoot > tr > td,
                        #wrapper .tbMobileMenu .table-bordered,
                        #wrapper .tbMobileMenu .cart-info.tb_max_w_500 .table tbody tr:not(:last-child),
                        #wrapper .tbMobileMenu .cart-info.tb_max_w_300 .table tbody tr:not(:last-child)
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_tbody.td_border'
                ),
                'td_bg_zebra' => array(
                    'label'       => 'Cell bg (zebra)',
                    'elements'    => '
                        #wrapper .tbMobileMenu .table-striped > tbody > tr:nth-child(even),
                        #wrapper .tbMobileMenu .table-striped > table > tbody > tr:nth-child(even)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#f9f9f9',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_tbody.td_bg_zebra'
                ),
                'td_bg_hover' => array(
                    'label'       => 'Cell bg (hover)',
                    'elements'    => '
                        #wrapper .tbMobileMenu .table-hover > tbody > tr:hover,
                        #wrapper .tbMobileMenu .table-hover > table > tbody > tr:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#f5f5f5',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tables_tbody.td_bg_hover'
                )
            ),
            'mobile_forms' => array(
                '_label' => 'Form elements',
                'input_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu select:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu textarea:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text'
                ),
                'input_bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu select:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu textarea:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg'
                ),
                'input_border_top_left' => array(
                    'label'       => 'Top/left border',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu select:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu textarea:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left'
                ),
                'input_border_bottom_right' => array(
                    'label'       => 'Bottom/right border',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu select:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu textarea:not(:hover):not(:focus),
                        #wrapper .tbMobileMenu .input-group:not(:hover):not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right'
                ),
                'input_text_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:hover:not(:focus),
                        #wrapper .tbMobileMenu select:hover:not(:focus),
                        #wrapper .tbMobileMenu textarea:hover:not(:focus),
                        #wrapper .tbMobileMenu .input-group:hover:not(:focus)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_hover'
                ),
                'input_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:hover:not(:focus),
                        #wrapper .tbMobileMenu select:hover:not(:focus),
                        #wrapper .tbMobileMenu textarea:hover:not(:focus),
                        #wrapper .tbMobileMenu .input-group:hover:not(:focus)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_hover'
                ),
                'input_border_top_left_hover' => array(
                    'label'       => 'Top/left border (hover)',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:hover:not(:focus),
                        #wrapper .tbMobileMenu select:hover:not(:focus),
                        #wrapper .tbMobileMenu textarea:hover:not(:focus),
                        #wrapper .tbMobileMenu .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_hover'
                ),
                'input_border_bottom_right_hover' => array(
                    'label'       => 'Bottom/right border (hover)',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:hover:not(:focus),
                        #wrapper .tbMobileMenu select:hover:not(:focus),
                        #wrapper .tbMobileMenu textarea:hover:not(:focus),
                        #wrapper .tbMobileMenu .input-group:hover:not(:focus)
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_hover'
                ),
                'input_text_focus' => array(
                    'label'       => 'Text (focus)',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:focus,
                        #wrapper .tbMobileMenu select:focus,
                        #wrapper .tbMobileMenu textarea:focus,
                        #wrapper .tbMobileMenu .input-group:focus
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_focus'
                ),
                'input_bg_focus' => array(
                    'label'       => 'Background (focus)',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:focus,
                        #wrapper .tbMobileMenu select:focus,
                        #wrapper .tbMobileMenu textarea:focus,
                        #wrapper .tbMobileMenu .input-group:focus
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_focus'
                ),
                'input_border_top_left_focus' => array(
                    'label'       => 'Top/left border (focus)',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:focus,
                        #wrapper .tbMobileMenu select:focus,
                        #wrapper .tbMobileMenu textarea:focus,
                        #wrapper .tbMobileMenu .input-group:focus
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_focus'
                ),
                'input_border_bottom_right_focus' => array(
                    'label'       => 'Bottom/right border (focus)',
                    'elements'    => '
                        #wrapper .tbMobileMenu input:focus,
                        #wrapper .tbMobileMenu select:focus,
                        #wrapper .tbMobileMenu textarea:focus,
                        #wrapper .tbMobileMenu .input-group:focus
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_focus'
                ),
                'input_text_error' => array(
                    'label'       => 'Text (error)',
                    'elements'    => '
                        #wrapper .tbMobileMenu .has-error input,
                        #wrapper .tbMobileMenu .has-error select,
                        #wrapper .tbMobileMenu .has-error textarea
                    ',
                    'property'    => 'color',
                    'color'       => '#84290a',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_text_error'
                ),
                'input_bg_error' => array(
                    'label'       => 'Background (error)',
                    'elements'    => '
                        #wrapper .tbMobileMenu .has-error input,
                        #wrapper .tbMobileMenu .has-error select,
                        #wrapper .tbMobileMenu .has-error textarea
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_bg_error'
                ),
                'input_border_top_left_error' => array(
                    'label'       => 'Top/left border (error)',
                    'elements'    => '
                        #wrapper .tbMobileMenu .has-error input,
                        #wrapper .tbMobileMenu .has-error select,
                        #wrapper .tbMobileMenu .has-error textarea
                    ',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#ffcdbc',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_top_left_error'
                ),
                'input_border_bottom_right_error' => array(
                    'label'       => 'Bottom/right border (error)',
                    'elements'    => '
                        #wrapper .tbMobileMenu .has-error input,
                        #wrapper .tbMobileMenu .has-error select,
                        #wrapper .tbMobileMenu .has-error textarea
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#ffe7df',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'forms.input_border_bottom_right_error'
                )
            ),
            'mobile_buttons' => array(
                '_label' => 'Buttons',
                'button' => array(
                    'label'       => 'Primary bg',
                    'elements'    => '
                        #wrapper .tbMobileMenu .btn:not(:hover):not(.btn-default)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button'
                ),
                'button_text' => array(
                    'label'       => 'Primary text',
                    'elements'    => '
                        #wrapper .tbMobileMenu .btn:not(:hover):not(.btn-default)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text'
                ),
                'button_hover' => array(
                    'label'       => 'Primary bg (hover)',
                    'elements'    => '
                        #wrapper .tbMobileMenu .btn:not(.active):not(.btn-default):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'label'       => 'Primary text (hover)',
                    'elements'    => '
                        #wrapper .tbMobileMenu .btn:not(.active):not(.btn-default):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_text_hover'
                ),
                'button_default' => array(
                    'label'       => 'Default bg',
                    'elements'    => '
                        #wrapper .tbMobileMenu .btn.btn-default:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#d6d6d6',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default'
                ),
                'button_default_text' => array(
                    'label'       => 'Default text',
                    'elements'    => '
                        #wrapper .tbMobileMenu .btn.btn-default:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_text'
                ),
                'button_default_hover' => array(
                    'label'       => 'Default bg (hover)',
                    'elements'    => '
                        #wrapper .tbMobileMenu .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_hover'
                ),
                'button_default_text_hover' => array(
                    'label'       => 'Default text (hover)',
                    'elements'    => '
                        #wrapper .tbMobileMenu .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'buttons.button_default_text_hover'
                )
            ),

            /* ------------------------------------------------------
               C U S T O M   C O L O R S
            ------------------------------------------------------ */
            'custom' => array()
        );

        if (null !== $key) {
            return $colors[$key];
        }

        return $colors;
    }

    public static function getProductListingColors()
    {
        $main_colors = self::getColors();
        $modified_colors = array(
            'product_listing' => $main_colors['product_listing'],
            'product_listing_hover' => $main_colors['product_listing_hover']
        );

        foreach ($modified_colors as $section_key => &$section) {
            foreach ($section as $key => &$value) {
                if (0 === strpos($key, '_')) {
                    continue;
                }

                $value['force_print'] = 0;
                $value['can_inherit'] = 1;
                $value['inherit']     = 1;
                $value['inherit_key'] = 'theme:' . $section_key . '.' . $key;
            }
        }

        return $modified_colors;
    }
}