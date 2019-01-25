<?php

require_once 'SystemWidget.php';

class Theme_ProductOptionsSystemWidget extends Theme_SystemWidget
{
    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'block_title_align' => 'default'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'block_title' => true,
        ), $settings));
    }

    public function onRenderWidgetContent($content)
    {

        $lang_settings = $this->getLangSettings();

        $title_classes  = 'panel-heading';
        $title_classes .= $this->getDistanceClasses('title');
        $title_classes .= $lang_settings['block_title_align'] != 'default' ? ' text-' . $lang_settings['block_title_align'] : '';
        $content = str_replace('panel-heading', $title_classes, $content);

        return $content;
    }

    public function getDefaultBoxColors()
    {
        return array(
            'options' => array(
                '_label' => 'Custom options',
                'text_selected' => array(
                    'label'       => 'Custom option text (selected)',
                    'elements'    => '.tb_main_color_bg',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'bg_selected' => array(
                    'label'       => 'Custom option bg (selected)',
                    'elements'    => '.tb_main_color_bg',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_bg'
                ),
            ),
            'forms' => array(
                '_label' => 'Forms',
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
                    'inherit_key' => 'column:forms.input_text'
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
                    'inherit_key' => 'column:forms.input_bg'
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
                    'inherit_key' => 'column:forms.input_border_top_left'
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
                    'inherit_key' => 'column:forms.input_border_bottom_right'
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
                    'inherit_key' => 'column:forms.input_text_hover'
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
                    'inherit_key' => 'column:forms.input_bg_hover'
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
                    'inherit_key' => 'column:forms.input_border_top_left_hover'
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
                    'inherit_key' => 'column:forms.input_border_bottom_right_hover'
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
                    'inherit_key' => 'column:forms.input_text_focus'
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
                    'inherit_key' => 'column:forms.input_bg_focus'
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
                    'inherit_key' => 'column:forms.input_border_top_left_focus'
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
                    'inherit_key' => 'column:forms.input_border_bottom_right_focus'
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
                    'inherit_key' => 'column:forms.input_text_error'
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
                    'inherit_key' => 'column:forms.input_bg_error'
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
                    'inherit_key' => 'column:forms.input_border_top_left_error'
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
                    'inherit_key' => 'column:forms.input_border_bottom_right_error'
                )
            ),
            'buttons' => array(
                '_label' => 'Buttons',
                'button' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        .btn:not(:hover):not(.btn-default)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button'
                ),
                'button_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        .btn:not(:hover):not(.btn-default)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_text'
                ),
                'button_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '
                        .btn:not(.btn-default):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '
                        .btn:not(.active):not(.btn-default):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_text_hover'
                ),
            ),
        );
    }

    public function getDefaultBoxFonts()
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
                'can_inherit'       => true
            )
        );
    }
}