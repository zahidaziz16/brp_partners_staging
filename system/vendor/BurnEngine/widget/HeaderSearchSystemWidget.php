<?php

require_once 'AbstractHeaderSystemWidget.php';

class Theme_HeaderSearchSystemWidget extends AbstractHeaderSystemWidget
{
    public function onFilter(array &$settings)
    {
        // Search Style Options
        // 1 - input + icon search button inside
        // 2 - input + icon button button outside
        // 3 - input + icon inside + text button outside

        $settings = array_replace($settings, $this->initFlatVars(array(
            'search_icon'       => 'fa fa-search',
            'icon_size'         => 100,
            'icon_hover_size'   => 100,
            'size'              => 'md',
            'style'             => 1,
            'width'             => 0,
            'width_metric'      => 'px',
            'max_width'         => 0,
            'max_width_metric'  => 'px',
            'min_width'         => 0,
            'min_width_metric'  => 'px',
        ), $settings));
    }

    protected function getBoxClasses()
    {
        $classes = parent::getBoxClasses();
        $style  = $this->settings['style'];

        $classes .= ' tb_style_' . $style;

        return $classes;
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $css  = '#' . $this->getDomId() . ':not(.tb_style_4) .tb_search_wrap {';
        $css .= $this->settings['width']     == 0 ? '' : '  width: '     . $this->settings['width']     . $this->settings['width_metric']     . ';';
        $css .= $this->settings['max_width'] == 0 ? '' : '  max-width: ' . $this->settings['max_width'] . $this->settings['max_width_metric'] . ';';
        $css .= $this->settings['min_width'] == 0 ? '' : '  min-width: ' . $this->settings['min_width'] . $this->settings['min_width_metric'] . ';';
        $css .= '}';
        $css .= '#' . $this->getDomId() . '.tb_style_4:hover .tb_search_wrap > input,';
        $css .= '#' . $this->getDomId() . '.tb_style_4:hover .tb_search_wrap > .twitter-typeahead,';
        $css .= '#' . $this->getDomId() . '.tb_style_4 .tb_search_wrap > .twitter-typeahead.dropdown-open {';
        $css .= '  width: ' . ($this->settings['width'] == 0 ? '150' : $this->settings['width']) . 'px !important;';
        $css .= '}';
        if ($this->settings['style'] == 4) {
            $css .= '#' . $this->getDomId() . '.tb_style_4 .tb_search_button:before {';
            $css .= '  font-size: ' . $this->settings['icon_size'] . '%;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . '.tb_style_4:hover .tb_search_button:before,';
            $css .= '#' . $this->getDomId() . '.tb_style_4 .twitter-typeahead.dropdown-open + .tb_search_button:before,';
            $css .= '.tbMobileMenu #' . $this->getDomId() . '.tb_style_4 .tb_search_button:before {';
            $css .= '  font-size: ' . $this->settings['icon_hover_size'] . '%;';
            $css .= '}';
        } else if ($this->settings['style'] == 3) {
            $css .= '#' . $this->getDomId() . '.tb_style_4 .tb_search_wrap:before {';
            $css .= '  font-size: ' . $this->settings['icon_size'] . '%;';
            $css .= '}';
        } else {
            $css .= '#' . $this->getDomId() . ' .tb_search_button {';
            $css .= '  font-size: ' . $this->settings['icon_size'] . '%;';
            $css .= '}';
        }

        $styleBuilder->addCss($css);
    }

    public function getDefaultBoxColors()
    {
        return array(
            'search' => array(
                '_label' => 'Search input',
                'input_text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        input:not(:hover):not(:focus)
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
                        input:not(:hover):not(:focus)
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
                        input:not(:hover):not(:focus)
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
                        input:not(:hover):not(:focus)
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
                        input:hover:not(:focus)
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
                        input:hover:not(:focus)
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
                        input:hover:not(:focus)
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
                        input:hover:not(:focus)
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
                        input:focus
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
                        input:focus
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
                        input:focus
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
                        input:focus
                    ',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:forms.input_border_bottom_right_focus'
                ),
                'input_icon' => array(
                    'label'       => 'Icon',
                    'elements'    => '
                        .tb_search_wrap:not(:hover):before
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'input_icon_hover' => array(
                    'label'       => 'Icon (hover)',
                    'elements'    => '
                        .tb_search_wrap:hover:before
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
            ),
            'button' => array(
                '_label' => 'Search Button',
                'button' => array(
                    'label'       => 'Primary bg',
                    'elements'    => '
                        .btn:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button'
                ),
                'button_text' => array(
                    'label'       => 'Primary text',
                    'elements'    => '
                        .btn:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_text'
                ),
                'button_hover' => array(
                    'label'       => 'Primary bg (hover)',
                    'elements'    => '
                        .btn:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'label'       => 'Primary text (hover)',
                    'elements'    => '
                        .btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_text_hover'
                ),
            )
        );
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'input' => array(
                'section_name'      => 'Search input',
                'elements'          => 'input',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => '',
                'line-height'       => '',
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_size'          => false,
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
            'button' => array(
                'section_name'      => 'Search Button',
                'elements'          => '.btn',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 13,
                'line-height'       => '',
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_size'          => false,
                'has_line_height'   => false,
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