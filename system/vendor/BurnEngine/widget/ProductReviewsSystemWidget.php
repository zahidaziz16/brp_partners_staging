<?php

require_once 'SystemWidget.php';

class Theme_ProductReviewsSystemWidget extends Theme_SystemWidget
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

        $title_classes  = 'panel-heading ';
        $title_classes .= $this->getDistanceClasses('title');
        $title_classes .= $lang_settings['block_title_align'] != 'default' ? ' text-' . $lang_settings['block_title_align'] : '';
        $content = str_replace('panel-heading', $title_classes, $content);

        return $content;
    }

    public function getDefaultBoxColors()
    {
        return array(
            'body' => array(
                '_label' => 'Body',
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '.tb_review',
                    'property'    => 'color',
                    'color'       => '#ffd200',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'button' => array(
                    'label'       => 'Primary bg',
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
                    'label'       => 'Primary text',
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
                    'label'       => 'Primary bg (hover)',
                    'elements'    => '
                        .btn:not(.active):not(.btn-default):hover
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
                    'label'       => 'Primary text (hover)',
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
                )
            ),
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
            ),
            'pagination' => array(
                '_label' => 'Pagination',
                'links_text' => array(
                    'label'       => 'Links text',
                    'elements'    => '
                        .pagination a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_text'
                ),
                'links_bg' => array(
                    'label'       => 'Links bg',
                    'elements'    => '
                        .pagination a:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_bg'
                ),
                'links_text_hover' => array(
                    'label'       => 'Links text (hover)',
                    'elements'    => '
                        .pagination a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_text_hover'
                ),
                'links_bg_hover' => array(
                    'label'       => 'Links bg (hover)',
                    'elements'    => '
                        .pagination a:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_bg_hover'
                ),
                'links_text_active' => array(
                    'label'       => 'Links text (active)',
                    'elements'    => '
                        .pagination b,
                        .pagination span
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_text_active'
                ),
                'links_bg_active' => array(
                    'label'       => 'Links bg (active)',
                    'elements'    => '
                        .pagination b,
                        .pagination span
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_bg_active'
                ),
                'results' => array(
                    'label'       => 'Results text',
                    'elements'    => '.pagination .results',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.results'
                ),
                'border' => array(
                    'label'       => 'Border',
                    'elements'    => '
                        .pagination
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.column_border'
                ),
                'bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        .pagination
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
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
}