<?php

class MainFontsData
{
    public static function getDefaultFontItem()
    {
        return array(
            'section_name'      => '{{section_name}}',
            'elements'          => '',
            'type'              => 'inherit',
            'family'            => 'inherit',
            'subsets'           => 'latin',
            'variant'           => 'regular',
            'size'              => 13,
            'line-height'       => 20,
            'letter-spacing'    => 0,
            'word-spacing'      => 0,
            'transform'         => 'none',
            'has_size'          => true,
            'has_line_height'   => true,
            'has_spacing'       => true,
            'has_effects'       => true,
            'show_built_styles' => true,
            'multiple_variants' => false,
            'built-in'          => true,
            'can_inherit'       => true,
            'inherit_mask'      => 31,
            'css_weight'        => '',
            'css_style'         => ''
        );
    }

    public static function getFonts()
    {
        return array(
            'body' => array(
                'section_name'      => 'Body',
                'elements'          => '
                    body,
                    .tb_no_text > span:before
                ',
                'type'              => 'google',
                'family'            => 'Open Sans',
                'variant'           => '700italic,700,600italic,600,italic,regular',
                'has_spacing'       => false,
                'has_effects'       => false,
                'show_built_styles' => false,
                'multiple_variants' => true,
                'can_inherit'       => false
            ),
            'h1' => array(
                'section_name'      => 'H1',
                'elements'          => 'h1, .h1',
                'type'              => 'google',
                'family'            => 'Montserrat',
                'size'              => 26,
                'line-height'       => 30
            ),
            'h2' => array(
                'section_name'      => 'H2 / Block title / Tabs & accordion navigation',
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
                'type'              => 'google',
                'family'            => 'Montserrat',
                'size'              => 16,
                'line-height'       => 20
            ),
            'h3' => array(
                'section_name'      => 'H3',
                'elements'          => 'h3, .h3',
                'type'              => 'google',
                'family'            => 'Montserrat',
                'size'              => 15,
                'line-height'       => 20
            ),
            'h4' => array(
                'section_name'      => 'H4',
                'elements'          => '
                    h4, .h4,
                    .product-thumb .name,
                    .box-product .name,
                    .product-grid .name,
                    .product-list .name
                ',
                'type'              => 'google',
                'family'            => 'Montserrat',
                'size'              => 14,
                'line-height'       => 20
            ),
            'buttons' => array(
                'section_name'      => 'Buttons',
                'elements'          => '
                    .btn,
                    .button,
                    button,
                    input[type="button"],
                    input[type="submit"],
                    input[type="reset"]
                ',
                'type'              => 'google',
                'family'            => 'Montserrat',
                'transform'         => 'uppercase',
                'size'              => 14,
                'has_line_height'   => false
            ),
            'main_navigation' => array(
                'section_name'      => 'Main navigation',
                'elements'          => '
                    .tbMainNavigation nav > .nav > li > a:not(.btn) > .tb_text,
                    .tbMainNavigation .nav > li > a:not(.btn) > .tb_text,
                    .tbMainNavigation .nav > li > .heading > a
                ',
                'type'              => 'google',
                'family'            => 'Montserrat',
                'transform'         => 'uppercase',
                'size'              => 14,
                'has_line_height'   => false
            ),
            'product_title' => array(
                'section_name'      => 'Product title',
                'elements'          => '
                    .product-thumb h4,
                    .product-thumb .name,
                    .box-product .name,
                    .product-grid .name,
                    .product-list .name
                ',
                'size'              => 14,
                'line-height'       => 20
            ),
            'product_price' => array(
                'section_name'      => 'Product price',
                'elements'          => '
                    .product-thumb .price,
                    .product-info .price
                ',
                'size'              => 18,
                'line-height'       => 30
            ),
            'product_buttons' => array(
                'section_name'      => 'Product buttons',
                'elements'          => '
                    .product-thumb .btn
                ',
                'size'              => 13,
                'has_line_height'   => false
            ),
        );
    }
}