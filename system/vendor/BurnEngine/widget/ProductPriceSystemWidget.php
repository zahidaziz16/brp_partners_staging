<?php

require_once 'SystemWidget.php';

class Theme_ProductPriceSystemWidget extends Theme_SystemWidget
{
    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'text_align'          => 'left',
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'old_price_new_line'  => true,
            'show_label'          => true,
            'show_tax'            => true,
            'show_reward'         => true,
            'show_savings'        => true,
            'show_savings_sum'    => true
        ), $settings));
    }

    protected function getBoxClasses()
    {
        $lang_settings = $this->getLangSettings();
        $classes = parent::getBoxClasses();

        $classes .= ' text-' . $lang_settings['text_align'];

        return $classes;
    }

    public function getDefaultBoxColors()
    {
        return array(
            'price' => array(
                '_label' => '',
                'regular' => array(
                    'label'       => 'Regular price',
                    'elements'    => '.price',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'new' => array(
                    'label'       => 'Special price',
                    'elements'    => '.price-new',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'price.regular'
                ),
                'old' => array(
                    'label'       => 'Old price',
                    'elements'    => '.price-old',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'label' => array(
                    'label'       => 'Label',
                    'elements'    => '.tb_label',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'tax' => array(
                    'label'       => 'Price without tax',
                    'elements'    => '.price-tax',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'price.label'
                ),
                'reward' => array(
                    'label'       => 'Reward points',
                    'elements'    => '.reward',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'price.label'
                ),
                'savings_text' => array(
                    'label'       => 'Savings text',
                    'elements'    => '.price-savings',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'savings_bg' => array(
                    'label'       => 'Savings bg',
                    'elements'    => '.price-savings',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                )
            )
        );
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'price' => array(
                'section_name'      => 'Price',
                'elements'          => '.price-new, .price-regular',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 24,
                'line-height'       => 30,
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
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'old_price' => array(
                'section_name'      => 'Old Price',
                'elements'          => '.price-old',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 16,
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
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'label' => array(
                'section_name'      => 'Label',
                'elements'          => '.tb_label',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
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
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'tax' => array(
                'section_name'      => 'Price without tax',
                'elements'          => '.price-tax',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 11,
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
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'reward' => array(
                'section_name'      => 'Reward points',
                'elements'          => '.reward',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 11,
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
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'savings' => array(
                'section_name'      => 'Savings',
                'elements'          => '.price-savings',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'uppercase',
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

    public function hasTitleStyles()
    {
        return false;
    }
}