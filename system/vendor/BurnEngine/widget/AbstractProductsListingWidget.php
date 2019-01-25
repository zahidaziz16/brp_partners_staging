<?php

require_once TB_THEME_ROOT . '/model/data/StoreData.php';

abstract class AbstractProductsListingWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');
    protected $frontend_data = array();

    public function onFilterSystem(array &$settings, $area = 'admin')
    {
        $default_vars = array(
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
            'products'  => array(
                'image_width'  => 200,
                'image_height' => 200
            )
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

        if  ($result['view_mode'] == 'grid') {
            foreach ($result['products']['grid']['restrictions'] as $i => $row) {
                if (empty($row['max_width']) || empty($row['items_per_row'])) {
                    unset($result['products']['grid']['restrictions'][$i]);
                }
            }

            if (empty($result['products']['grid']['restrictions'])) {
                $theme_settings = $this->engine->getThemeModel()->getSettings();
                $result['products']['grid']['restrictions'] = $theme_settings['store']['category']['products']['grid']['restrictions'];
            }
        }

        $settings = array_replace($settings, $result);

        parent::onFilterSystem($settings, $area);
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
        $this->engine->getEventDispatcher()->connect('core:pluginsPostBootstrap', array($this, 'setProductsOptions'));
    }

    public function assignAssets()
    {
        if ($this->settings['slider']) {
            $this->themeData->registerJavascriptResource('javascript/swiper.min.js');
        }
        if (!empty($this->settings['special_counter'])) {
            $this->themeData->registerJavascriptResource('javascript/jquery.plugin.min.js');
            $this->themeData->registerJavascriptResource('javascript/jquery.countdown.min.js');
        }
    }

    public function setProductsOptions()
    {
        if ($this->settings['inherit_products']) {
            $product_settings = $this->themeData->category['products'][$this->settings['view_mode']];
        } else {
            $product_settings = $this->settings['products'][$this->settings['view_mode']];
        }

        if (isset($this->settings['filter_category']) && (int) $this->settings['filter_category'] == -1) {
            $cache_key = $this->getId() . '.' . $this->themeData->category_id . '.' . $this->language_code;
        } else {
            $cache_key = $this->getId() . '.' . $this->language_code;
        }

        $cache_key .= '.' . $this->themeData->currency_code;

        $get_product_listing = true;
        if ($products = $this->engine->getCacheVar($cache_key)) {
            $get_product_listing = false;
        }

        if ($get_product_listing) {
            $products = $this->getProductsListing();
            if ($products
                && $this->themeData['system']['cache_enabled']
                && $this->themeData['system']['cache_content']
                && isset($this->themeData['system']['cache_widgets'][get_class($this)]['ttl'])
                && $this->themeData['system']['cache_widgets'][get_class($this)]['enabled']
            ) {
                $this->engine->setCacheVar($cache_key, $products, (int) $this->themeData['system']['cache_widgets'][get_class($this)]['ttl'] * 60);
            }
        }

        if (!$products) {
            return;
        }

        if (($this->settings['view_mode'] == 'compact' || $this->settings['view_mode'] == 'list') && count($products) <= 1) {
            $this->settings['slider'] = 0;
        }

        if ($this->settings['view_mode'] == 'compact') {
            $product_settings['products_style'] = 1;
        }

        if ($product_settings['products_style'] == 2) {
            $product_settings['products_spacing'] = 0;
        }

        $this->getModel('category')->initCategoryProductsSettings($product_settings, $this->settings['view_mode']);
        $this->engine->getThemeExtension()->getPlugin('product')->addProductData($products, $product_settings);

        if ($this->settings['view_mode'] == 'grid' && !empty($this->settings['grid_max_rows'])) {
            $product_settings['listing_classes'] .= ' tb_max_rows_' . $this->settings['grid_max_rows'];
        }

        $this->settings['products'] = array(
            $this->settings['view_mode'] => $product_settings
        );

        $this->frontend_data['products'] = $products;
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        if (empty($this->frontend_data['products'])) {
            return '';
        }

        $active_elements = $this->settings['products'][$this->settings['view_mode']]['active_elements'];
        
        if ($active_elements
            && $this->settings['view_mode'] != 'compact'
            && $this->settings['products'][$this->settings['view_mode']]['elements_hover_action'] != 'none'
            && !($this->settings['slider'] && $this->settings['products'][$this->settings['view_mode']]['elements_hover_action'] == 'append'))
        {
            $css = '.no_touch #' . $this->getDomId() . ' .tb_grid_view:not(.tbHoverReady) ' . str_replace(', ', ', #' . $this->getDomId() . ' .tb_grid_view:not(.tbHoverReady) ', $active_elements) . ' {
              display: none;
            }';
            $styleBuilder->addCss($css);
        }
    }

    public function allowAddToAreaContentCache()
    {
        /*
        if (isset($this->settings['filter_category'])
            && (int) $this->settings['filter_category'] == -1
            && $this->themeData->system['cache_enabled']
            && $this->themeData->system['cache_content']
            && isset($this->themeData->system['cache_widgets'][get_class($this)])
            && $this->themeData->system['cache_widgets'][get_class($this)]['enabled']) {

            $this->local_cache = (int) $this->themeData->system['cache_widgets'][get_class($this)]['ttl'];

            return false;
        }
        */

        return true;
    }

    public function render(array $view_data = array())
    {
        /*
        $cache_key = $this->getId() . '.' . $this->themeData->category_id . '.' . $this->language_code;

        if (!$this->allowAddToAreaContentCache() && $this->local_cache > 0) {
            if ($html = $this->engine->getCacheVar($cache_key)) {
                return $html;
            }
        }
        */

        if (empty($this->frontend_data['products'])) {
            return '';
        }

        $special_lang = $this->engine->loadOcTranslation('product/special');

        // $this->settings['products'][$this->settings['view_mode']]['listing_classes'] .= ' tb_' . $this->getDomId() . '_classes';

        $view_data['text_tax']              = $special_lang['text_tax'];
        $view_data['view_mode']             = $this->settings['view_mode'];
        $view_data['slider']                = $this->settings['slider'];
        $view_data['slider_step']           = $this->settings['slider_step'];
        $view_data['slider_speed']          = $this->settings['slider_speed'];
        $view_data['slider_pagination']     = $this->settings['slider_pagination'];
        $view_data['slider_autoplay']       = $this->settings['slider_autoplay'];
        $view_data['slider_loop']           = $this->settings['slider_loop'];
        $view_data['special_counter']       = $this->settings['special_counter'];
        $view_data['restrictions_json']     = $this->settings['products'][$this->settings['view_mode']]['restrictions_json'];
        $view_data['config_stock_checkout'] = $this->engine->getOcConfig()->get('config_stock_checkout');

        $view_data = array_merge($view_data, $this->frontend_data);
        $view_data += $this->settings['products'][$this->settings['view_mode']];

        $view_data += array(
            'header'         => '',
            'footer'         => '',
            'breadcrumbs'    => array(),
            'heading_title'  => '',
            'pagination'     => '',
            'results'        => '',
            'text_grid'      => '',
            'text_list'      => '',
            'text_limit'     => '',
            'text_sort'      => '',
            'text_compare'   => '',
            'compare'        => '',
            'limits'         => array(),
            'sorts'          => array(),
            'products_route'           => 'internal_request',
            'products_filter_name'     => 'internal_request',
            'product_settings_context' => array()
        );

        $this->engine->getThemeExtension()->setHeaderFilter(false);
        $this->engine->getThemeExtension()->setFooterFilter(false);
        $this->engine->getThemeExtension()->fetchTemplate('product/special', $view_data, true);
        $this->engine->getThemeExtension()->setHeaderFilter(true);
        $this->engine->getThemeExtension()->setFooterFilter(true);

        $view_data['products_html'] = $this->themeData->viewSlot->getContent('internal_request.products_listing');

        $parent_id = $this->parent !== null ? $this->parent->getDomId() : 0;

        $js = $this->themeData->viewSlot->getContent('internal_request.products_js');

        $js = trim(str_replace(
            array('{{widget_dom_id}}', '{{within_group}}', '{{group_id}}', '{{optimize_js_load}}'),
            array($this->getDomId(), ($this->isWithinGroup() ? 'true' : 'false'), $parent_id, ($this->themeData->optimize_js_load ? 'true' : 'false')),
            $js)
        );

        $view_data['js'] = $js;
        $html = parent::render($view_data);

        /*
        if ($this->local_cache > 0) {
            $this->engine->setCacheVar($cache_key, $html, $this->local_cache);
        }
        */

        return $html;
    }

    protected function getProductsListing(array $options = array())
    {
        $config = $this->engine->getOcConfig();
        $latest_lang = $this->engine->loadOcTranslation('product/product');

        $options['secondary_image'] = in_array($this->settings['products'][$this->settings['view_mode']]['thumbs_hover_action'], array('overlay', 'flip'));
        $options['special_fields']  = $this->settings['special_counter'];

        if (isset($this->settings['filter_category']) && $this->settings['filter_category'] != 0) {
            if ($this->settings['filter_category'] == -1) {
                $category_id = $this->themeData->category_id ? $this->themeData->category_id : 0;
            } else {
                $category_id = (int) $this->settings['filter_category'];
            }

            if ($category_id) {
                if ($this->settings['filter_category_children']) {
                    $category = $this->getModel('category')->getCategoryFromFlatTree($category_id);
                    if ($category) {
                        $options['category_ids'] = array_merge(array($category_id), $category['successor_ids']);
                    }
                } else {
                    $options['category_ids'] = array($category_id);
                }
            }
        }

        /** @var Theme_Catalog_ProductsModel $productsModel */
        $productsModel = $this->getModel('products');

        $products = $productsModel->getProducts($options);
        $products_modified = $productsModel->compatibilityProducts($products, $this->themeData);

        foreach ($products as &$product) {
            if (!$products_modified) {
                if (($config->get('config_customer_price') && $this->engine->getOcCustomer()->isLogged()) || !$config->get('config_customer_price')) {
                    $tax = $this->engine->getOcTax()->calculate($product['price'], $product['tax_class_id'], $config->get('config_tax'));
                    $product['price'] = $this->engine->getOcCurrency()->format($tax, $this->themeData->currency_code);
                } else {
                    $product['price'] = false;
                }

                if ($product['special_num']) {
                    $tax = $this->engine->getOcTax()->calculate($product['special'], $product['tax_class_id'], $config->get('config_tax'));
                    $product['special'] = $this->engine->getOcCurrency()->format($tax, $this->themeData->currency_code);
                } else {
                    $product['special'] = false;
                }

                $product['href'] = $this->engine->getOcUrl()->link('product/product', 'product_id=' . $product['product_id']);
            }

            $product['secondary_image'] = $options['secondary_image'] ? $product['secondary_image'] : false;
            $product['reviews'] = sprintf($latest_lang['text_reviews'], (int) $product['reviews']);
        }

        if ($products && !empty($this->settings['filter_randomize'])) {
            shuffle($products);
        }

        return $products;
    }

    protected function getBoxClasses()
    {
        $settings = $this->settings;
        $lazyload = $this->themeData->system['js_lazyload'];
        $classes  = parent::getBoxClasses();

        $classes .= !$this->getLangTitle() ? ' no_title' : '';
        $classes .= $settings['slider'] ? ' has_slider' : '';
        $classes .= $settings['slider'] && $settings['slider_nav_position'] != 'side' ? ' tb_top_nav'  : '';
        $classes .= $settings['slider'] && $settings['slider_nav_position'] == 'side' ? ' tb_side_nav' : '';
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
            ),
        );

        $theme_config = $this->engine->getThemeConfig();
        if (isset($theme_config['colors']['products_system_widget'])) {
            $default_colors = array_replace_recursive($default_colors, $theme_config['colors']['products_system_widget']);
        }

        return $default_colors;
    }
}