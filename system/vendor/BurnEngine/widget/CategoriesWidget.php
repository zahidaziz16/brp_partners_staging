<?php

class Theme_CategoriesWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $config_group = version_compare(VERSION, '2.2.0.0', '>=') ? TB_Engine::getName() : 'config';

        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Categories',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left'
        ), $settings));

        $default_restrictions = array(
            0 => array(
                'max_width'     => 1200,
                'items_per_row' => 5,
                'items_spacing' => 30
            ),
            1 => array(
                'max_width'     => 1000,
                'items_per_row' => 4,
                'items_spacing' => 30
            ),
            2 => array(
                'max_width'     => 750,
                'items_per_row' => 3,
                'items_spacing' => 30
            ),
            3 => array(
                'max_width'     => 450,
                'items_per_row' => 2,
                'items_spacing' => 30
            ),
            4 => array(
                'max_width'     => 300,
                'items_per_row' => 1,
                'items_spacing' => 30
            )
        );

        $settings = array_replace($settings, $this->initFlatVars(array(
            'layout'            => 'list',  //list, grid
            'style'             => 1,
            'text_align'        => 'center',
            'image_position'    => 1,
            'image_width'       => $this->engine->getOcConfig($config_group . '_image_category_width'),
            'image_height'      => $this->engine->getOcConfig($config_group . '_image_category_height'),
            'restrictions'      => $default_restrictions,
            'show_next_level'   => 0,
            'product_count'     => 0,
            'level_1'           => 'show',
            'level_1_style'     => 'h3',
            'level_2'           => 'show_select',
            'level_2_style'     => 'list',
            'level_3'           => 'show_select',
            'level_3_style'     => 'list',
            'respect_top'       => 1
        ), $settings));

        foreach ($settings['restrictions'] as $i => $row) {
            if (empty($row['max_width']) || empty($row['items_per_row'])) {
                unset($settings['restrictions'][$i]);
            }
        }

        if (empty($settings['restrictions'])) {
            $settings['restrictions'] = $default_restrictions;
        }
    }

    public function render(array $view_data = array())
    {
        $config_group    = version_compare(VERSION, '2.2.0.0', '>=') ? TB_Engine::getName() : 'config';
        $settings        = $this->settings;
        $categories      = $settings['layout'] == 'grid' && $settings['product_count'] ? $this->getModel('category')->getCategoryTreeWithTotalProductsMaxLevel2() : $this->getModel('category')->getCategoriesTree();
        $listing_classes = '';
        $show_thumb      = $settings['style'] == 1 || $settings['style'] == 4 || $settings['style'] == 5;
        $show_next_level = $settings['style'] == 4 || $settings['style'] == 5 ? false : $settings['show_next_level'];

        if ($settings['level_1_style'] == 'hide'
            && $settings['level_2'] == 'show_select'
            && !$this->themeData->category_id
            || empty($categories))
        {
            return '';
        }

        if ($settings['layout'] == 'grid') {
            $listing_classes   = 'tb_cstyle_' . $settings['style'];
            $listing_classes  .= ' text-' . $settings['text_align'];
            $listing_classes  .= $settings['style'] == 1 ? ' tb_image_' . $settings['image_position'] : '';
            $listing_classes  .= $show_next_level ? ' tb_complex' : '';

            foreach ($categories as &$category) {
                if ($show_thumb) {
                    $category['image'] ? $image = $category['image'] : $image = 'no_image.jpg';
                    $category['thumb'] = (string) $this->getThemeModel()->resizeImage($image, $this->engine->getOcConfig()->get($config_group . '_image_category_width'), $this->engine->getOcConfig()->get($config_group . '_image_category_height'));
                    $category['thumb_width']  = $settings['image_width'];
                    $category['thumb_height'] = $settings['image_height'];

                    if ($category['image']) {
                        $category['thumb'] = $this->engine->getOcModel('tool/image')->resize($category['image'], $settings['image_width'], $settings['image_height']);
                        $category['thumb_width']  = $settings['image_width'];
                        $category['thumb_height'] = $settings['image_height'];
                    }
                    if (!empty($category['thumb']) && $this->themeData->system['image_lazyload']) {
                        $category['thumb_original'] = $category['thumb'];
                        $category['thumb'] = $this->themeData->theme_catalog_image_url . 'pixel.gif';
                    }
                }
            }

            $restrictions_json = array();

            foreach ($this->settings['restrictions'] as $restriction) {
                $restrictions_json[$restriction['max_width']] = array(
                    'items_per_row' => $restriction['items_per_row'],
                    'items_spacing' => $restriction['items_spacing']
                );
            }

            krsort($restrictions_json);
            $view_data['restrictions_json'] = json_encode($restrictions_json);
        }

        $view_data['grid']            = $settings['layout'] == 'grid';
        $view_data['listing_classes'] = $listing_classes;
        $view_data['categories']      = $categories;
        $view_data['show_next_level'] = $show_next_level;

        return parent::render($view_data);
    }

    protected function getBoxClasses()
    {
        $classes = parent::getBoxClasses();
        $classes .= ' tb_wt_menu';

        return $classes;
    }


    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'categories_level_1' => array(
                '_label' => 'Level 1',
                'text' => array(
                    'label'       => 'Links',
                    'elements'    => '.panel-body > ul > li > * > a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'text_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '.panel-body > ul > li > * > a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'Bullets',
                    'elements'    => '.panel-body > ul.tb_list_1 > li:before',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                )
            ),
            'categories_level_2' => array(
                '_label' => 'Level 2',
                'text' => array(
                    'label'       => 'Links',
                    'elements'    => '.panel-body > ul > li > ul > li > * > a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'text_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '.panel-body > ul > li > ul > li > * > a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'Bullets',
                    'elements'    => '.panel-body > ul > li > ul.tb_list_1 > li:before',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                )
            ),
            'categories_level_3' => array(
                '_label' => 'Level 3',
                'text' => array(
                    'label'       => 'Links',
                    'elements'    => '.panel-body > ul > li > ul > li > ul > li > * > a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'text_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '.panel-body > ul > li > ul > li > ul > li > * > a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'Bullets',
                    'elements'    => '.panel-body > ul > li > ul > li > ul.tb_list_1 > li:before',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                )
            )
        );

        return $default_colors;
    }
}