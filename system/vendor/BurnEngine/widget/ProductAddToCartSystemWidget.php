<?php

require_once 'SystemWidget.php';

class Theme_ProductAddToCartSystemWidget extends Theme_SystemWidget
{
    public function assignAssets()
    {
        $this->themeData->registerJavascriptResource('javascript/jquery.bootstrap-touchspin.min.js');
    }

    public function initFrontend()
    {
        $this->engine->getEventDispatcher()->connect('core:assignAssets', array($this, 'assignAssets'));
    }

    public function getDefaultBoxColors()
    {
        return array(
            'body' => array(
                '_label' => 'Body',
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
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => 'a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => 'a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                ),
            ),
            'forms' => array(
                '_label' => 'Quantity input',
                'input_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        input:not(:hover):not(:focus),
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
                'button_default' => array(
                    'label'       => 'Spinner button bg',
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
                    'inherit_key' => 'column:buttons.button_default'
                ),
                'button_default_text' => array(
                    'label'       => 'Spinner button text',
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
                    'inherit_key' => 'column:buttons.button_default_text'
                ),
                'button_default_hover' => array(
                    'label'       => 'Spinner button bg (hover)',
                    'elements'    => '
                        .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_default_hover'
                ),
                'button_default_text_hover' => array(
                    'label'       => 'Spinner button text (hover)',
                    'elements'    => '
                        .btn.btn-default:not(.active):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_default_text_hover'
                )
            ),
            'buttons' => array(
                '_label' => 'Add to cart button',
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
            ),
            'add_to_cart_button' => array(
                'section_name'      => 'Add to cart button',
                'elements'          => '.tb_button_add_to_cart',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 13,
                'line-height'       => '',
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_size'          => true,
                'has_line_height'   => false,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
        );
    }

    public function hasTitleStyles()
    {
        return false;
    }
}