<?php

require_once 'SystemWidget.php';
require_once TB_THEME_ROOT . '/model/data/StoreData.php';

class Theme_SubcategoriesSystemWidget extends Theme_SystemWidget
{
    protected $subcategory_count = null;

    public function onFilter(array &$settings)
    {

        $settings = array_replace($settings, $this->initLangVars(array(
            'block_title_align' => 'default'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'block_title' => false
        ), $settings));

        $default_vars = array(
            'inherit_subcategories' => 1,
            'subcategories'         => array()
        );

        $inherit_subcategories = isset($settings['inherit_subcategories']) ? $settings['inherit_subcategories'] : $default_vars['inherit_subcategories'];

        if ($inherit_subcategories) {
            $theme_settings = $this->engine->getThemeModel()->getSettings();
            $result = $this->initFlatVars($default_vars, $settings);

            if (isset($settings['subcategories'])) {
                $result['subcategories'] = $this->initFlatVars($default_vars['subcategories'], $settings['subcategories']);
            }

            if (isset($theme_settings['store']['category']['subcategories'])) {
                $result['subcategories'] = array_replace($result['subcategories'], $theme_settings['store']['category']['subcategories']);
            } else {
                $result['subcategories'] = array_replace(StoreData::getSubcategoryListSettings($this->engine->getOcConfig()), $result['subcategories']);
            }
        } else {
            $default_vars['subcategories'] = array_replace(StoreData::getSubcategoryListSettings($this->engine->getOcConfig()), $default_vars['subcategories']);
            $result = $this->initFlatVars($default_vars, $settings);
            if (isset($settings['subcategories'])) {
                $result['subcategories'] = $this->initFlatVars($default_vars['subcategories'], $settings['subcategories']);
            }
        }

        foreach ($result['subcategories']['restrictions'] as $i => $row) {
            if (empty($row['max_width']) || empty($row['items_per_row'])) {
                unset($result['subcategories']['restrictions'][$i]);
            }
        }

        if (empty($result['subcategories']['restrictions'])) {
            $theme_settings = $this->engine->getThemeModel()->getSettings();
            $result['subcategories']['restrictions'] = $theme_settings['store']['category']['subcategories']['restrictions'];
        }

        $settings = array_replace($settings, $result);

        parent::onFilter($settings);
    }

    public function initFrontend()
    {
        $this->engine->getEventDispatcher()->connect('core:assignAssets', array($this, 'assignAssets'));
    }

    public function assignAssets()
    {
        if ($this->settings['subcategories']['is_slider']) {
            $this->themeData->registerJavascriptResource('javascript/swiper.min.js');
            $this->themeData->registerStylesheetResource('stylesheet/swiper.css');
        }
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

    public function configureFrontend()
    {
        $this->engine->getEventDispatcher()->connect('product/category.subcategory_listing.filter', array($this, 'filterSubcategories'));
    }

    public function filterSubcategories(sfEvent $event, $data)
    {
        $settings = $this->settings['subcategories'];

        if ($settings['product_count']) {
            $subcategories = $this->getModel('category')->getCategoriesByParentWithTotalProducts($this->themeData->category_id);
        } else {
            $subcategories = $this->getModel('category')->getCategoriesByParent($this->themeData->category_id);
        }

        if ($settings['style'] != 6) {
            $settings['listing_classes']  = 'tb_cstyle_' . $settings['style'];
            $settings['listing_classes'] .= ' text-' . $settings['text_align'];
            $settings['listing_classes'] .= $settings['style'] == 1 ? ' tb_image_' . $settings['image_position'] : '';
            $settings['listing_classes'] .= $settings['show_next_level'] ? ' tb_complex' : '';
            $settings['listing_classes'] .= ' tb_' . $this->getDomId() . '_classes';
            $settings['show_thumb']       = $settings['style'] != 3;
            $settings['show_title']       = $settings['style'] != 2;
            $settings['show_children']    = $settings['style'] != 4 && $settings['style'] != 5;
        } else {
            $settings['listing_classes'] = '';
            $settings['show_thumb']      = 0;
            $settings['is_slider']       = 0;
            $settings['show_children']   = $settings['show_next_level'];
        }


        $restrictions_json = array();
        foreach ($settings['restrictions'] as $restriction) {
            $restrictions_json[$restriction['max_width']] = array(
                'items_per_row' => $restriction['items_per_row'],
                'items_spacing' => $restriction['items_spacing']
            );
        }
        krsort($restrictions_json);
        $settings['restrictions_json'] = json_encode($restrictions_json);

        $this->themeData->subcategories = $settings;

        $parent_category = null;
        if ($settings['show_next_level']) {
            $parent_category = $this->getModel('category')->getTreeById($this->themeData->category_id);
        }

        $config = $this->engine->getOcConfig();
        $config_group = $this->engine->gteOc22() ? TB_Engine::getName() : 'config';
        $resize_images = !$this->settings['inherit_subcategories'] && ($settings['image_width'] != $config->get($config_group . '_image_category_width') || $settings['image_height'] != $config->get($config_group . '_image_category_height'));

        foreach ($subcategories as &$category) {
            if ($settings['show_thumb']) {
                if (empty($category['image'])) {
                    $category['image'] = 'no_image.jpg';
                }

                $category['thumb'] = (string) $this->getThemeModel()->resizeImage($category['image'], $this->engine->getOcConfig()->get($config_group . '_image_category_width'), $this->engine->getOcConfig()->get($config_group . '_image_category_height'));
                $category['thumb_width']  = $this->engine->getOcConfig()->get($config_group . '_image_category_width');
                $category['thumb_height'] = $this->engine->getOcConfig()->get($config_group . '_image_category_height');
            }

            if (!$settings['product_count']) {
                $category['products_count'] = null;
            }

            $category_from_tree = $this->getModel('category')->getCategoryFromFlatTree($category['category_id']);
            $category['url'] = $category_from_tree['url'];

            $category['description'] = html_entity_decode($category['description'], ENT_QUOTES, 'UTF-8');
            $category['path']  = $this->themeData->category_path . '_' . $category['category_id'];

            if (null !== $parent_category) {
                $category['children'] = $parent_category['children'][$category['category_id']]['children'];
            }

            if ($resize_images && $category['image']) {
                $category['thumb'] = $this->engine->getOcModel('tool/image')->resize($category['image'], $settings['image_width'], $settings['image_height']);
                $category['thumb_width']  = $settings['image_width'];
                $category['thumb_height'] = $settings['image_height'];
            }
            if (!empty($category['thumb']) && $this->themeData->system['image_lazyload']) {
                $category['thumb_original'] = $category['thumb'];
                $category['thumb'] = $this->themeData->theme_catalog_image_url . 'pixel.gif';
            }

        }

        $data['categories'] = $subcategories;
        $this->subcategory_count = count($subcategories);
    }

    protected function getBoxClasses()
    {

        $classes = parent::getBoxClasses();

        if ($this->settings['subcategories']['is_slider']) {
            $classes .= ' has_slider tb_side_nav';
        }

        return $classes;
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'categories_level_1' => array(
                'section_name'      => 'Level 1',
                'elements'          => 'h3, .tb_subcategories > ul.tb_list_1 > li > a',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true
            ),
            'categories_level_2' => array(
                'section_name'      => 'Level 2',
                'elements'          => 'h3 + ul > li > a, ul ul > li > a',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true
            )
        );
    }

    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'categories_level_1' => array(
                '_label' => 'Level 1',
                'text' => array(
                    'label'       => 'Links',
                    'elements'    => 'h3 > a',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.links'
                ),
                'text_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => 'h3 > a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'Bullets',
                    'elements'    => '.tb_subcategories > ul.tb_list_1 > li:before',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.accent'
                )
            ),
            'categories_level_2' => array(
                '_label' => 'Level 2',
                'text' => array(
                    'label'       => 'Links',
                    'elements'    => 'h3 + ul > li > a, ul ul > li > a',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.links'
                ),
                'text_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => 'h3 + ul > li > a:hover, ul ul > li > a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'Bullets',
                    'elements'    => 'ul ul.tb_list_1 > li:before',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.accent'
                )
            ),
            'carousel_nav' => array(
                '_label' => 'Carousel navigation',
                'button_default' => array(
                    'label'       => 'Prev/Next button',
                    'elements'    => '
                        .tb_slider_controls a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_nav.button_default'
                ),
                'button_hover' => array(
                    'label'       => 'Prev/Next button (hover)',
                    'elements'    => '
                        .tb_slider_controls a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_nav.button_hover'
                ),
                'button_inactive' => array(
                    'label'       => 'Prev/Next button (inactive)',
                    'elements'    => '
                        .tb_slider_controls a.tb_disabled,
                        .tb_slider_controls a.tb_disabled:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_nav.button_inactive'
                ),
            ),
            'carousel_pagination' => array(
                '_label' => 'Carousel pagination',
                'pagination_default' => array(
                    'label'       => 'Pagination',
                    'elements'    => '
                        .tb_slider_pagination span:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_pagination.pagination_default'
                ),
                'pagination_hover' => array(
                    'label'       => 'Pagination (hover)',
                    'elements'    => '
                        .tb_slider_pagination span:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_pagination.pagination_hover'
                ),
                'pagination_active' => array(
                    'label'       => 'Pagination (active)',
                    'elements'    => '
                        .tb_slider_pagination span.tb_active,
                        .tb_slider_pagination span.tb_active:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_pagination.pagination_active'
                )
            )
        );

        return $default_colors;
    }
}