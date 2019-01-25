<?php

class Theme_LatestReviewsWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');
    protected $reviews;

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Latest Reviews',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left'
        ), $settings));

        $config = $this->engine->getOcConfig();
        $config_group = $this->engine->gteOc22() ? $this->engine->getConfigTheme() : 'config';

        $settings = array_replace($settings, $this->initFlatVars(array(
            'show_thumb'               => 1,
            'show_price'               => 0,
            'show_text'                => 1,
            'rating_type'              => 'review',
            'tooltip_review'           => 0,
            'slider'                   => 0,
            'slider_step'              => 1,
            'slider_speed'             => 500,
            'slider_pagination'        => 0,
            'slider_nav_position'      => 'top',
            'slider_autoplay'          => 0,
            'slider_loop'              => 0,
            'filter_randomize'         => 0,
            'filter_category'          => 0,
            'filter_category_children' => 0,
            'filter_limit'             => 4,
            'image_size_x'             => $config->get($config_group . '_image_product_width'),
            'image_size_y'             => $config->get($config_group . '_image_product_height')
        ), $settings));
    }

    public function initFrontend()
    {
        $this->engine->getEventDispatcher()->connect('core:assignAssets', array($this, 'assignAssets'));
    }

    public function assignAssets()
    {
        if ($this->settings['slider']) {
            $this->themeData->registerJavascriptResource('javascript/swiper.min.js');
            $this->themeData->registerStylesheetResource('stylesheet/swiper.css');
        }
    }

    public function render(array $view_data = array())
    {
        $view_data['reviews'] = $this->getReviews();

        $settings = $this->settings;
        $slider   = $settings['slider'];

        if (count($view_data['reviews']) <= 1) {
            $slider = 0;
        }

        $view_data['show_text']        = $settings['show_text'];
        $view_data['slider']           = $slider;
        $view_data['slider_step']      = 1;
        $view_data['listing_classes']  = $settings['tooltip_review'] && $settings['show_text'] ? ' tb_compact_view tb_review_tooltip' : ' tb_list_view';
        $view_data['listing_classes'] .= ' tb_style_plain tb_size_1 tb_gut_30';
        $view_data['listing_classes'] .= $slider ? ' tb_slider' : '';
        $view_data['listing_classes'] .= $slider && $settings['slider_nav_position'] == 'side' ? ' tb_side_nav' : '';

        return parent::render($view_data);
    }

    protected function getReviews()
    {
        if (null === $this->reviews) {
            $options = array();

            if (isset($this->settings['filter_category']) && $this->settings['filter_category'] != 0) {
                if ($this->settings['filter_category'] == -1) {
                    $category_id = $this->themeData->category_id ? $this->themeData->category_id : 0;
                } else {
                    $category_id = (int) $this->settings['filter_category'];
                }

                if ($category_id) {
                    if ($this->settings['filter_category_children']) {
                        /** @var Theme_CategoryModel $categoryModel */
                        $categoryModel = $this->getModel('category');
                        $category = $categoryModel->getCategoryFromFlatTree($category_id);
                        $options['category_ids'] = array($category_id) + $category['successor_ids'];
                    } else {
                        $options['category_ids'] = array($category_id);
                    }
                }
            }

            $options['filter_limit'] = (int) $this->settings['filter_limit'];
            $options['show_price'] = $this->settings['show_price'];
            $options['show_average'] = $this->settings['rating_type'] == 'average';

            /** @var Theme_Catalog_ProductsModel $productsModel */
            $productsModel = $this->getModel('products');
            $reviews = $productsModel->getLatestReviews($options);
            $config = $this->engine->getOcConfig();
            $translation = $this->engine->loadOcTranslation();

            foreach ($reviews as &$product) {
                if ($this->settings['show_thumb'] && $product['image']) {
                    $product['thumb'] = $this->getThemeModel()->resizeImage($product['image'], $this->settings['image_size_x'], $this->settings['image_size_y']);
                    $product['thumb_width'] = $this->settings['image_size_x'];
                    $product['thumb_height'] = $this->settings['image_size_y'];
                } else {
                    $product['thumb'] = false;
                }

                if ($this->settings['show_price']) {
                    $price = ($product['discount'] ? $product['discount'] : $product['price']);

                    if (($config->get('config_customer_price') && $this->engine->getOcCustomer()->isLogged()) || !$config->get('config_customer_price')) {
                        $tax = $this->engine->getOcTax()->calculate($price, $product['tax_class_id'], $config->get('config_tax'));
                        $product['price'] = $this->engine->getOcCurrency()->format($tax, $this->themeData->currency_code);
                    } else {
                        $product['price'] = false;
                    }

                    $product['special_num'] = (float) $product['special'];

                    if ($product['special_num']) {
                        $tax = $this->engine->getOcTax()->calculate($product['special'], $product['tax_class_id'], $config->get('config_tax'));
                        $product['special'] = $this->engine->getOcCurrency()->format($tax, $this->themeData->currency_code);
                    } else {
                        $product['special'] = false;
                    }
                } else {
                    $product['price'] = false;
                }

                $product['href'] = $this->engine->getOcUrl()->link('product/product', 'product_id=' . $product['product_id']);
                $product['rating'] = $this->settings['rating_type'] == 'review' ? $product['rating'] : round($product['avg_rating'], 1);
                $product['date_added'] = date($translation['date_format_short'], strtotime($product['date_added']));
            }

            if ($this->settings['filter_randomize']) {
                shuffle($reviews);
            }

            $this->reviews = $reviews;
        }

        return $this->reviews;
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
        $classes .= $lazyload && (($settings['show_text'] && $settings['tooltip_review']) || $settings['slider']) ? ' lazyload' : '';

        return $classes;
    }

    protected function getBoxData()
    {
        $lazyload = $this->themeData->system['js_lazyload'];
        $settings = $this->settings;
        $data     = parent::getBoxData();
        $data    .= $lazyload && (($settings['show_text'] && $settings['tooltip_review']) || $settings['slider']) ? ' data="' . $this->themeData->system['js_lazyload_expand'] . '"' : '';

        return $data;
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $position     = $this->themeData['language_direction'] == 'ltr' ? 'left'  : 'right';
        $font_size    = $this->themeData->fonts['body']['size'];
        $font_base    = $this->themeData->fonts['body']['line-height'];
        $title_size   = $this->settings['box_styles']['font'][$this->language_code]['product_title']['size'];
        $settings     = $this->settings;
        $css          = '';

        if ($title_size > $font_size + 1 && $title_size <= $font_size + 4) {
            $css .= '#' . $this->getDomId() . ' .tb_list_view .caption > .tb_review {';
            $css .= '  margin-top: '  . ($font_base * 0.5) . 'px;';
            $css .= '}';
        }
        if ($title_size > $font_size + 4) {
            $css .= '#' . $this->getDomId() . ' .tb_list_view .caption > .tb_review {';
            $css .= '  margin-top: '  . $font_base . 'px;';
            $css .= '}';
        }
        if ($settings['image_size_x'] > 150 && $settings['image_size_x'] < 400) {
            $css .= '#' . $this->getDomId() . ' .tb_list_view .tb_item_info {';
            $css .= '  margin-' . $position . ': '  . $font_base . 'px;';
            $css .= '}';
        }

        $styleBuilder->addCss($css);
    }

    public function getDefaultBoxFonts()
    {
        return array(
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
        );
    }

    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'product_listing' => array(
                '_label' => 'Product listing',
                'product_text' => array(
                    'label'       => 'Review text',
                    'elements'    => '
                        .product-thumb
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_text'
                ),
                'product_title' => array(
                    'label'       => 'Title',
                    'elements'    => '
                        .product-thumb .name a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_title'
                ),
                'product_title_hover' => array(
                    'label'       => 'Title (hover)',
                    'elements'    => '
                        .product-thumb .name a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_title_hover'
                ),
                'product_price' => array(
                    'label'       => 'Price',
                    'elements'    => '
                        .product-thumb .price
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_price'
                ),
                'product_promo_price' => array(
                    'label'       => 'Promo price',
                    'elements'    => '
                        .product-thumb .price-new
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_promo_price'
                ),
                'product_old_price' => array(
                    'label'       => 'Old price',
                    'elements'    => '
                        .product-thumb .price-old
                    ',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_old_price'
                ),
                'rating_percent' => array(
                    'label'       => 'Rating stars (rate)',
                    'elements'    => '
                        .product-thumb .rating .tb_percent
                    ',
                    'property'    => 'color',
                    'color'       => '#ffd200',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.rating_percent'
                ),
                'rating_base' => array(
                    'label'       => 'Rating stars (base)',
                    'elements'    => '
                        .product-thumb .rating .tb_base
                    ',
                    'property'    => 'color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.rating_base'
                ),
                'rating_text' => array(
                    'label'       => 'Rating text',
                    'elements'    => '
                        .product-thumb .rating .tb_average
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.rating_text'
                ),
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