<?php

require_once 'AbstractHeaderSystemWidget.php';

class Theme_HeaderCartMenuSystemWidget extends AbstractHeaderSystemWidget
{
    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initFlatVars(array(
            'menu_height'  => 20,
            'menu_padding' => 0,
            'show_icon'    => 1,
            'show_label'   => 1,
            'show_items'   => 1,
            'show_total'   => 1,
            'cart_icon'    => 'fa-shopping-cart',
            'icon_size'    => 100,
            'sticky_style' => 'compact', // default, compact
            'sticky_size'  => 'md'       // sm, md, lg
        ), $settings));
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $css = '';

        if ($this->settings['menu_height'] > 0 || $this->settings['menu_padding'] > 0) {
            $css .= '#cart > .nav > li > h3,';
            $css .= '#cart > .nav > li > h3 > a {';
            if ($this->settings['menu_height'] > 0) {
                $css .= '  line-height: ' . $this->settings['menu_height'] . 'px;';
            }
            $css .= '}';
            $css .= '#cart > .nav > li > h3 > a {';
            if ($this->settings['menu_padding'] > 0) {
                $css .= '  padding-left: ' . $this->settings['menu_padding'] . 'px;';
                $css .= '  padding-right: ' . $this->settings['menu_padding'] . 'px;';
            }
            $css .= '}';
        }
        if (!empty($this->settings['icon_size']) && $this->settings['icon_size'] != 100) {
            $css .= '#cart > .nav > li > h3 > a > .tb_icon {';
            $css .= '  font-size: ' . $this->settings['icon_size'] . '%;';
            $css .= '}';
        }

        $styleBuilder->addCss($css);
    }

    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'cart_menu' => array(
                '_label' => 'Cart Menu',
                'icon' => array(
                    'label'       => 'Icon',
                    'elements'    => '
                        #cart > .nav > li:not(:hover) > .heading > a > .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                ),
                'label' => array(
                    'label'       => 'Label',
                    'elements'    => '
                        #cart > .nav > li:not(:hover) > .heading > a > .tb_label,
                        #cart > .nav > li:not(:hover) > .heading > a > .tb_items
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'total' => array(
                    'label'       => 'Total',
                    'elements'    => '
                        #cart > .nav > li:not(:hover) > .heading > a > .tb_total
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                ),
                'bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        #cart > .nav > li:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'column:body.accent'
                ),
                'icon_hover' => array(
                    'label'       => 'Icon (hover)',
                    'elements'    => '
                        #cart > .nav > li:hover > .heading > a > .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                ),
                'label_hover' => array(
                    'label'       => 'Label (hover)',
                    'elements'    => '
                        #cart > .nav > li:hover > .heading > a > .tb_label,
                        #cart > .nav > li:hover > .heading > a > .tb_items
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'total_hover' => array(
                    'label'       => 'Total (hover)',
                    'elements'    => '
                        #cart > .nav > li:hover > .heading > a > .tb_total
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                ),
                'bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '
                        #cart > .nav > li:hover > .heading > a
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'column:body.accent'
                )
            ),
            'dropdown_menu' => array(
                '_label' => 'Dropdown Menu',
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        .dropdown-menu
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.text'
                ),
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
                'titles' => array(
                    'label'       => 'Titles',
                    'elements'    => '
                        .dropdown-menu h3
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.titles'
                ),
                'subtle_base' => array(
                    'label'       => 'Subtle color base',
                    'elements'    => '
                        .dropdown-menu
                    ',
                    'property'    => 'subtle',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.subtle_base'
                ),
                'subtle_base_hidden_color' => array(
                    'label'       => 'Subtle color base',
                    'elements'    => '
                        .dropdown-menu .buttons:before,
                        .dropdown-menu .mini-cart-total:before
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
            ),
            'dropdown_menu_buttons' => array(
                '_label' => 'Buttons',
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
                    'inherit_key' => 'theme:dropdown_menu_buttons.button'
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
                    'inherit_key' => 'theme:dropdown_menu_buttons.button_text'
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
                    'inherit_key' => 'theme:dropdown_menu_buttons.button_hover'
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
                    'inherit_key' => 'theme:dropdown_menu_buttons.button_text_hover'
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
                    'inherit_key' => 'theme:dropdown_menu_buttons.button_default'
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
                    'inherit_key' => 'theme:dropdown_menu_buttons.button_default_text'
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
                    'inherit_key' => 'theme:dropdown_menu_buttons.button_default_hover'
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
                    'inherit_key' => 'theme:dropdown_menu_buttons.button_default_text_hover'
                )
            ),
            'dropdown_tables_tbody' => array(
                '_label' => 'Table body / footer',
                'td_text' => array(
                    'label'       => 'Cell text',
                    'elements'    => '
                        .table > tbody > tr > th,
                        .table > tbody > tr > td,
                        .table > tfoot > tr > th,
                        .table > tfoot > tr > td
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_tables_tbody.td_text'
                ),
                'td_border' => array(
                    'label'       => 'Cell border',
                    'elements'    => '
                        .table > tbody > tr > th,
                        .table > tbody > tr > td,
                        .table > tfoot > tr > th,
                        .table > tfoot > tr > td
                        .table-bordered,
                        .cart-info.tb_max_w_500 .table > tbody > tr:not(:last-child),
                        .cart-info.tb_max_w_300 .table > tbody > tr:not(:last-child)
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_tables_tbody.td_border'
                )
            )
        );

        return $default_colors;
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'label' => array(
                'section_name'      => 'Label',
                'elements'          => '
                    .heading.heading .tb_label,
                    .heading.heading .tb_items
                ',
                'transform'         => 'uppercase',
                'has_line_height'   => false
            ),
            'total' => array(
                'section_name'      => 'Total',
                'elements'          => '
                    .heading.heading .tb_total
                ',
                'size'              => 18,
                'has_line_height'   => false
            ),
            'dropdown_title' => array(
                'section_name'      => 'Dropdown title',
                'elements'          => '
                    .dropdown-menu h3
                ',
                'has_line_height'   => false
            ),
        );
    }

    public function hasTitleStyles()
    {
        return false;
    }
}