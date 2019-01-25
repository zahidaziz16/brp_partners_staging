<?php

require_once 'SystemWidget.php';
require_once TB_THEME_ROOT . '/model/data/StoreData.php';

class Theme_RelatedProductsSystemWidget extends Theme_SystemWidget
{
    protected $products_count = 0;

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'block_title_align'   => 'default'
        ), $settings));

        $default_vars = array(
            'block_title'         => true,
            'slider'              => 0,
            'slider_step'         => 1,
            'slider_speed'        => 500,
            'slider_pagination'   => 0,
            'slider_nav_position' => 'top',
            'slider_autoplay'     => 0,
            'slider_loop'         => 0,
            'inherit_products'    => 1,
            'special_counter'     => 0,
            'view_mode'           => 'grid',
            'grid_max_rows'       => 0,
            'products'            => array()
        );

        $inherit_products = isset($settings['inherit_products']) ? $settings['inherit_products'] : $default_vars['inherit_products'];

        if ($inherit_products) {
            $theme_settings = $this->engine->getThemeModel()->getSettings();
            $result = $this->initFlatVars($default_vars, $settings);

            if (isset($settings['products'])) {
                $result['products'] = $this->initFlatVars($default_vars['products'], $settings['products']);
            }

            if (isset($theme_settings['store']['category']['products'])) {
                $result['products'] = array_replace($result['products'], $theme_settings['store']['category']['products']);
            } else {
                $result['products'] = array_replace(StoreData::getProductListSettings($this->engine->getOcConfig()), $result['products']);
            }
        } else {
            $default_vars['products'] = array_replace(StoreData::getProductListSettings($this->engine->getOcConfig()), $default_vars['products']);
            $result = $this->initFlatVars($default_vars, $settings);
            if (isset($settings['products'])) {
                $result['products'] = $this->initFlatVars($default_vars['products'], $settings['products']);
                $result['products']['grid'] = TB_FormHelper::initFlatVarsSimple($default_vars['products']['grid'], $result['products']['grid']);
                $result['products']['list'] = TB_FormHelper::initFlatVarsSimple($default_vars['products']['list'], $result['products']['list']);
                $result['products']['compact'] = TB_FormHelper::initFlatVarsSimple($default_vars['products']['compact'], $result['products']['compact']);
            }
        }

        foreach ($result['products']['grid']['restrictions'] as $i => $row) {
            if (empty($row['max_width']) || empty($row['items_per_row'])) {
                unset($result['products']['grid']['restrictions'][$i]);
            }
        }

        if (empty($result['products']['grid']['restrictions'])) {
            $theme_settings = $this->engine->getThemeModel()->getSettings();
            $result['products']['grid']['restrictions'] = $theme_settings['store']['category']['products']['grid']['restrictions'];
        }

        $settings = array_replace($settings, $result);

        parent::onFilter($settings);
    }

    public function onRenderWidgetContent($content)
    {
        $lang_settings = $this->getLangSettings();

        $title_classes  = 'panel-heading';
        $title_classes .= $this->getDistanceClasses('title');
        $title_classes .= $lang_settings['block_title_align'] != 'default' ? ' text-' . $lang_settings['block_title_align'] : '';

        return str_replace(
            array(
                'panel-heading'
            ),
            array(
                $title_classes
            ),
            $content
        );
    }

    public function onPersist(array &$settings)
    {
        if (isset($settings['inherit_products']) && $settings['inherit_products']) {
            unset($settings['products']);
        } else {
            $settings['products'] = array(
                $settings['view_mode'] => $settings['products'][$settings['view_mode']]
            );
        }
    }

    public function initFrontend()
    {
        $this->engine->getEventDispatcher()->connect('core:assignAssets', array($this, 'assignAssets'));
    }

    public function configureFrontend()
    {
        $this->engine->getEventDispatcher()->connect('product/product.related_products.filter', array($this, 'filterProducts'));
    }

    public function assignAssets()
    {
        if ($this->settings['slider']) {
            $this->themeData->registerJavascriptResource('javascript/swiper.min.js');
        }
    }

    protected function setProductsOptions()
    {
        if ($this->settings['inherit_products']) {
            $product_settings = $this->themeData->category['products'][$this->settings['view_mode']];
        } else {
            $product_settings = $this->settings['products'][$this->settings['view_mode']];
        }

        if ($this->settings['view_mode'] == 'compact') {
            $this->settings['slider'] = 0;
        }
        if ($this->settings['view_mode'] == 'list' && $this->products_count <= 1) {
            $this->settings['slider'] = 0;
        }
        if ($this->settings['view_mode'] == 'compact') {
            $product_settings['products_style'] = 1;
        }
        if ($product_settings['products_style'] == 2) {
            $product_settings['products_spacing'] = 0;
        }

        if (!$this->settings['inherit_products'] || $this->themeData->product_listing_type != $this->settings['view_mode'] || $this->settings['view_mode'] == 'compact') {
            $this->getModel('category')->initCategoryProductsSettings($product_settings, $this->settings['view_mode']);
        } 

        $product_settings['listing_classes'] .= $this->settings['slider'] && $this->settings['slider_nav_position'] == 'side' ? ' tb_side_nav' : '';
        $product_settings['listing_classes'] .= ' tb_' . $this->getDomId() . '_classes';

        $product_settings['view_mode']         = $this->settings['view_mode'];
        $product_settings['slider']            = $this->settings['slider'];
        $product_settings['slider_step']       = $this->settings['slider_step'];
        $product_settings['slider_speed']      = $this->settings['slider_speed'];
        $product_settings['slider_pagination'] = $this->settings['slider_pagination'];
        $product_settings['slider_autoplay']   = $this->settings['slider_autoplay'];
        $product_settings['slider_loop']       = $this->settings['slider_loop'];

        $this->settings['products'] = array(
            $this->settings['view_mode'] => $product_settings
        );

        $this->themeData->products_related = $product_settings;
    }


    public function filterProducts(sfEvent $event, array $data)
    {
        $this->products_count = count($data['products']);

        if (!$this->products_count) {
            return;
        }

        foreach ($data['products'] as &$product) {
            unset($product['thumb']);
        }

        $this->setProductsOptions();

        $this->engine->getThemeExtension()->getPlugin('product')->modifyProducts($data['products'], $this->themeData->products_related);
    }

    protected function getBoxClasses()
    {
        $classes  = parent::getBoxClasses();
        $slider   = $this->settings['slider'];
        $lazyload = $this->themeData->system['js_lazyload'];

        $classes .= ' tb_products_style_' . $this->settings['products'][$this->settings['view_mode']]['products_style'];
        $classes .= $slider ? ' has_slider' : '';
        $classes .= $slider && $this->settings['slider_nav_position'] != 'side' ? ' tb_top_nav'  : '';
        $classes .= $slider && $this->settings['slider_nav_position'] == 'side' ? ' tb_side_nav' : '';
        $classes .= $lazyload ? ' lazyload' : '';

        return $classes;
    }

    protected function getBoxData()
    {
        $lazyload = $this->themeData->system['js_lazyload'];
        $data     = parent::getBoxData();
        $data    .= $lazyload ? ' data="' . $this->themeData->system['js_lazyload_expand'] . '"' : '';

        return $data;
    }

    public function getDefaultBoxFonts()
    {
        $default_fonts = array(
            'product_title' => array(
                'section_name'      => 'Product title',
                'elements'          => '
                    .product-thumb h4,
                    .product-thumb .name,
                    .box-product .name,
                    .product-grid .name,
                    .product-list .name
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
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
            'product_price' => array(
                'section_name'      => 'Product price',
                'elements'          => '
                    .product-thumb .price
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 18,
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
            'product_buttons' => array(
                'section_name'      => 'Product buttons',
                'elements'          => '
                    .product-thumb .btn
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 13,
                'line-height'       => '',
                'letter-spacing'    => '0',
                'word-spacing'      => '0',
                'transform'         => 'none',
                'has_size'          => true,
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

        return $default_fonts;
    }

    public function getDefaultBoxColors()
    {
        $modified_colors = MainColorsData::getProductListingColors();

        $default_colors = array(
            'product_listing' => $modified_colors['product_listing'],
            'product_listing_hover' => $modified_colors['product_listing_hover'],
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

        $theme_config = $this->engine->getThemeConfig();
        if (isset($theme_config['colors']['products_system_widget'])) {
            $default_colors = array_replace_recursive($default_colors, $theme_config['colors']['products_system_widget']);
        }

        return $default_colors;
    }
}