<?php

require_once 'SystemWidget.php';

class Theme_ProductSpecialPriceCounterSystemWidget extends Theme_SystemWidget
{

    public function initFrontend()
    {
        $this->engine->getEventDispatcher()->connect('core:assignAssets', array($this, 'assignAssets'));
    }

    public function assignAssets()
    {
        $this->themeData->registerJavascriptResource('javascript/jquery.plugin.min.js');
        $this->themeData->registerJavascriptResource('javascript/jquery.countdown.min.js');
    }

    public function getDefaultBoxColors()
    {
        return array(
            'body' => array(
                '_label' => '',
                'product_counter_text' => array(
                    'label'       => 'Counter text',
                    'elements'    => '
                        .tb_counter
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_counter_text'
                ),
                'product_counter_bg' => array(
                    'label'       => 'Counter bg',
                    'elements'    => '
                        .tb_counter
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_counter_bg'
                ),
                'product_counter_title_text' => array(
                    'label'       => 'Counter label',
                    'elements'    => '
                        .tb_counter_label
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_counter_title_text'
                ),
                'product_counter_title_bg' => array(
                    'label'       => 'Counter label bg',
                    'elements'    => '
                        .tb_counter_label
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_counter_title_bg'
                )
            )
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

    public function hasTitleStyles()
    {
        return false;
    }
}