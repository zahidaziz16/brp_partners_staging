<?php

require_once 'SystemWidget.php';

class Theme_ProductRatingSystemWidget extends Theme_SystemWidget
{
    public function getDefaultBoxColors()
    {
        return array(
            'rating' => array(
                '_label' => 'Rating',
                'rating_percent' => array(
                    'label'       => 'Rate',
                    'elements'    => '.rating .tb_percent',
                    'property'    => 'color',
                    'color'       => '#ffd200',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.rating_percent'
                ),
                'rating_base' => array(
                    'label'       => 'Base',
                    'elements'    => '.rating .tb_base',
                    'property'    => 'color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.rating_base'
                ),
                'rating_text' => array(
                    'label'       => 'Text (number)',
                    'elements'    => '.rating .tb_average',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.rating_text'
                ),
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