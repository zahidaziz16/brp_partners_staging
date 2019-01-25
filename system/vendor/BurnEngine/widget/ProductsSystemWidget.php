<?php

require_once 'SystemWidget.php';
require_once TB_THEME_ROOT . '/model/data/StoreData.php';
require_once TB_THEME_ROOT . '/model/data/MainColorsData.php';

class Theme_ProductsSystemWidget extends Theme_SystemWidget
{
    public function onFilter(array &$settings)
    {
        $theme_settings = $this->engine->getThemeModel()->getSettings();

        $default_vars = array(
            'filter'        => true,
            'filter_mt'     => 30,
            'filter_mb'     => 30,
            'filter_pt'     => 0,
            'filter_pb'     => 0,
            'filter_pl'     => 0,
            'pagination_mt' => 30,
            'pagination_mb' => 30,
            'pagination_pt' => 0,
            'pagination_pb' => 0,
            'pagination_pl' => 0,
            // Product listing styles
            'inherit_products' => 1,
            'products'         => array()
        );

        $inherit_products = isset($settings['inherit_products']) ? $settings['inherit_products'] : $default_vars['inherit_products'];

        if ($inherit_products) {
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
            }
        }

        foreach ($result['products']['grid']['restrictions'] as $i => $row) {
            if (empty($row['max_width']) || empty($row['items_per_row'])) {
                unset($result['products']['grid']['restrictions'][$i]);
            }
        }

        if (empty($result['products']['grid']['restrictions'])) {
            $result['products']['grid']['restrictions'] = $theme_settings['store']['category']['products']['grid']['restrictions'];
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
        $this->themeData->registerJavascriptResource('javascript/jquery.plugin.min.js');
        $this->themeData->registerJavascriptResource('javascript/jquery.countdown.min.js');
    }

    public function onRenderWidgetContent($content)
    {
        $settings         = $this->getSettings();
        $listing_settings = $this->themeData->products_system;

        $listing_options_css_classes  = 'tb_listing_options';
        $listing_options_css_classes .= ' tb_style_' . $listing_settings['products_style'];
        $listing_options_css_classes .= ' tb_mt_' . $settings['filter_mt'];
        $listing_options_css_classes .= ' tb_mb_' . $settings['filter_mb'];
        $listing_options_css_classes .= $settings['filter_pt'] != 0 ? ' tb_pt_' . $settings['filter_pt'] : '';
        $listing_options_css_classes .= $settings['filter_pb'] != 0 ? ' tb_pb_' . $settings['filter_pb'] : '';
        $listing_options_css_classes .= $settings['filter_pl'] != 0 ? ' tb_pl_' . $settings['filter_pl'] . ' tb_pr_' . $settings['filter_pl'] : '';

        $pagination_css_classes       = 'pagination';
        $pagination_css_classes      .= ' tb_mt_' . $settings['pagination_mt'];
        $pagination_css_classes      .= ' tb_mb_' . $settings['pagination_mb'];
        $pagination_css_classes      .= $settings['pagination_pt'] != 0 ? ' tb_pt_' . $settings['pagination_pt'] : '';
        $pagination_css_classes      .= $settings['pagination_pb'] != 0 ? ' tb_pb_' . $settings['pagination_pb'] : '';
        $pagination_css_classes      .= $settings['pagination_pl'] != 0 ? ' tb_pl_' . $settings['pagination_pl'] . ' tb_pr_' . $settings['pagination_pl'] : '';

        $content = str_replace(
            array(
                'tb_listing_options',
                'pagination'
            ),
            array(
                $listing_options_css_classes,
                $pagination_css_classes
            ),
            $content
        );

        return $content;
    }

    public function onPersist(array &$settings)
    {
        if (isset($settings['inherit_products']) && $settings['inherit_products']) {
            unset($settings['products']);
        }
    }

    public function configureFrontend()
    {
        $dispatcher = $this->engine->getEventDispatcher();

        $dispatcher->connect('core:pluginsPostBootstrap', array($this, 'setProductsOptions'));
        $dispatcher->connect('oc_system_products_filter', array($this, 'filterProducts'));
    }

    public function setProductsOptions()
    {
        if (!$this->settings['inherit_products']) {
            $products_settings = $this->settings['products'][$this->themeData->product_listing_type];
            $this->getModel('category')->initCategoryProductsSettings($products_settings, $this->themeData->product_listing_type);
        } else {
            $products_settings = $this->themeData->category_products_current;
        }

        $products_settings['listing_classes'] .= ' tb_' . $this->getDomId() . '_classes';

        $this->themeData->products_system = $products_settings;
    }

    public function filterProducts(sfEvent $event, array $data)
    {
        $this->engine->getThemeExtension()->getPlugin('product')->modifyProducts($data['products'], $this->themeData->products_system);
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $active_elements = $this->themeData->products_system['active_elements'];

        if ($active_elements
            && $this->themeData->product_listing_type != 'compact'
            && $this->themeData->products_system['elements_hover_action'] != 'none')
        {
            $css = '.no_touch #' . $this->getDomId() . ' .tb_grid_view:not(.tbHoverReady) ' . str_replace(', ', ', #' . $this->getDomId() . ' .tb_grid_view:not(.tbHoverReady) ', $active_elements) . ' {
              display: none;
            }';
            $styleBuilder->addCss($css);
        }
    }

    protected function getBoxClasses()
    {
        $lazyload = $this->themeData->system['js_lazyload'];
        $classes  = parent::getBoxClasses();

        $classes .= $lazyload ? ' lazyload' : '';

        return $classes;
    }

    protected function getBoxData()
    {
        $lazyload = $this->themeData->system['js_lazyload'];
        $data     = parent::getBoxData();
        $data    .= $lazyload ? ' data-expand="' . $this->themeData->system['js_lazyload_expand'] . '"' : '';

        return $data;
    }

    public function getDefaultBoxFonts()
    {
        $default_fonts = array(
            'product_title' => array(
                'section_name'      => 'Product title',
                'elements'          => '
                    .product-thumb .name,
                    .product-thumb h4
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
            'product_filter' => array(
                '_label' => 'Products Filter',
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        .tb_listing_options
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        .tb_listing_options a:not(:hover)
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
                        .tb_listing_options a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'accent' => array(
                    'label'       => 'Links (selected)',
                    'elements'    => '
                        .tb_listing_options .tb_main_color,
                        .tb_listing_options .tb_hover_main_color:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                ),
                'accent_hover_hidden' => array(
                    'label'       => 'Links (selected hover)',
                    'elements'    => '
                        .tb_listing_options a.tb_main_color:hover,
                        .tb_listing_options .tb_main_color_hover:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                ),
                'bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        .tb_listing_options
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'border' => array(
                    'label'       => 'Border',
                    'elements'    => '
                        .tb_listing_options
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'row:body.column_border'
                ),
                'input_text' => array(
                    'label'       => 'Dropdown text',
                    'elements'    => '.tb_listing_options select:not(:hover):not(:focus)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_text'
                ),
                'input_bg' => array(
                    'label'       => 'Dropdown bg',
                    'elements'    => '.tb_listing_options select:not(:hover):not(:focus)',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_bg'
                ),
                'input_border_top_left' => array(
                    'label'       => 'Dropdown top/left border',
                    'elements'    => '.tb_listing_options select:not(:hover):not(:focus)',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_top_left'
                ),
                'input_border_bottom_right' => array(
                    'label'       => 'Dropdown bottom/right border',
                    'elements'    => '.tb_listing_options select:not(:hover):not(:focus)',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_bottom_right'
                ),
                'input_text_hover' => array(
                    'label'       => 'Dropdown text (hover)',
                    'elements'    => '.tb_listing_options select:hover:not(:focus)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_text_hover'
                ),
                'input_bg_hover' => array(
                    'label'       => 'Dropdown bg (hover)',
                    'elements'    => '.tb_listing_options select:hover:not(:focus)',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_bg_hover'
                ),
                'input_border_top_left_hover' => array(
                    'label'       => 'Dropdown top/left border (hover)',
                    'elements'    => '.tb_listing_options select:hover:not(:focus)',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_top_left_hover'
                ),
                'input_border_bottom_right_hover' => array(
                    'label'       => 'Dropdown bottom/right border (hover)',
                    'elements'    => '.tb_listing_options select:hover:not(:focus)',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_bottom_right_hover'
                ),
                'input_text_focus' => array(
                    'label'       => 'Dropdown text (focus)',
                    'elements'    => '.tb_listing_options select:focus',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_text_focus'
                ),
                'input_bg_focus' => array(
                    'label'       => 'Dropdown bg (focus)',
                    'elements'    => '.tb_listing_options select:focus',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_bg_focus'
                ),
                'input_border_top_left_focus' => array(
                    'label'       => 'Dropdown top/left border (focus)',
                    'elements'    => '.tb_listing_options select:focus',
                    'property'    => 'border-top-color, border-left-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_top_left_focus'
                ),
                'input_border_bottom_right_focus' => array(
                    'label'       => 'Dropdown bottom/right border (focus)',
                    'elements'    => '.tb_listing_options select:focus',
                    'property'    => 'border-bottom-color, border-right-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:forms.input_border_bottom_right_focus'
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

        $theme_config = $this->engine->getThemeConfig();
        if (isset($theme_config['colors']['products_system_widget'])) {
            $default_colors = array_replace_recursive($default_colors, $theme_config['colors']['products_system_widget']);
        }

        return $default_colors;
    }

    public function hasTitleStyles()
    {
        return false;
    }
}