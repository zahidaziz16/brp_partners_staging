<?php

require_once 'SystemWidget.php';

class Theme_ProductAttributesSystemWidget extends Theme_SystemWidget
{
    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'block_title_align' => 'default'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'block_title' => false
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
            'tables_thead' => array(
                '_label' => 'Table head',
                'th_text' => array(
                    'label'       => 'Cell text',
                    'elements'    => '
                        .table > thead > tr > th,
                        .table > thead > tr > td
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_thead.th_text'
                ),
                'th_bg' => array(
                    'label'       => 'Cell bg',
                    'elements'    => '
                        .table > thead > tr > th,
                        .table > thead > tr > td
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e9e9e9',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_thead.th_bg'
                ),
                'th_border' => array(
                    'label'       => 'Cell border',
                    'elements'    => '
                        .table > thead > tr > th,
                        .table > thead > tr > td
                    ',
                    'property'    => 'border-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_thead.th_border'
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
                        .table > tfoot > tr > td
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_tbody.td_text'

                ),
                'td_bg' => array(
                    'label'       => 'Cell bg',
                    'elements'    => '
                        .table > tbody > tr > th,
                        .table > tbody > tr > td,
                        .table > tfoot > tr > th,
                        .table > tfoot > tr > td
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_tbody.td_bg'
                ),
                'td_border' => array(
                    'label'       => 'Cell border',
                    'elements'    => '
                        .table > tbody > tr > th,
                        .table > tbody > tr > td,
                        .table > tfoot > tr > th,
                        .table > tfoot > tr > td,
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
                    'inherit_key' => 'theme:tables_tbody.td_border'
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
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_tbody.td_bg_zebra'
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
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_tbody.td_bg_hover'
                )
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