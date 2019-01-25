<?php

require_once 'AbstractHeaderSystemWidget.php';

class Theme_HeaderLanguageMenuSystemWidget extends AbstractHeaderSystemWidget
{
    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initFlatVars(array(
            'menu_type'  => 'dropdown',  // dropdown | inline
            'label_text' => 'flag+code'  // flag+code | flag+name | flag | name | code
        ), $settings));
    }

    public function configureFrontend()
    {
        $this->themeData->addCallable(array($this, 'getLanguageSymbol'));

        $this->settings['label_has_name'] = $this->settings['label_text'] == 'name' || $this->settings['label_text'] == 'flag+name';
        $this->settings['label_has_code'] = $this->settings['label_text'] == 'code' || $this->settings['label_text'] == 'flag+code';
        $this->settings['label_has_flag'] = $this->settings['label_text'] == 'flag' || $this->settings['label_text'] == 'flag+code' || $this->settings['label_text'] == 'flag+name';
    }

    protected function getBoxClasses()
    {
        $has_code   = $this->settings['label_text'] == 'code' || $this->settings['label_text'] == 'flag+code';
        $classes    = parent::getBoxClasses();
        $classes   .= $has_code ? ' tb_code' : '';

        return $classes;
    }

    public function getLanguageSymbol($language)
    {
        $symbol = '';

        if ($this->settings['label_has_name']) {
            $symbol .= $language['name'];
        }

        if ($this->settings['label_has_code']) {
            $symbol .= $language['code'];
        }

        return $symbol;
    }

    public function getDefaultBoxColors()
    {
        return array(
            'inline' => array(
                '_label' => 'Inline',
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
                'links_active' => array(
                    'label'       => 'Selected',
                    'elements'    => '
                        .tb_selected:not(:only-child) > a,
                        .tb_selected:not(:only-child) > a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                )
            ),
            'dropdown_menu' => array(
                '_label' => 'Dropdown Menu',
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        [class].dropdown-menu a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.links'
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        [class].dropdown-menu a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'List bullets',
                    'elements'    => '
                        .dropdown-menu [class*="tb_list_"] > li:before,
                        .dropdown-menu [class*="tb_list_"] > li > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.bullets'
                ),
                'bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        .dropdown:after,
                        .dropdown-menu,
                        .dropdown-menu:before
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.bg'
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
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => true,
                'has_line_height'   => false,
                'has_spacing'       => false,
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