<?php

class Theme_Catalog_Extension extends TB_CatalogExtension
{
    protected $init_order = 0;
    protected $settings = array();
    protected $route = '';
    protected $request_areas = array();
    protected $request_areas_settings = array();

    /**
     * @var TB_OutputHandler
     */
    protected $outputHandler;

    /**
     * @var TB_ExtensionPluginBootstrap
     */
    protected $pluginBootstrapper;

    /**
     * @var TB_StyleBuilder
     */
    protected $styleBuilder;

    public function init()
    {
        /** @var Theme_Catalog_FontsModel $fontsModel */
        $fontsModel = $this->getModel('fonts');

        $this->styleBuilder  = new TB_StyleBuilder($this->context, $fontsModel);
        $this->outputHandler = new TB_OutputHandler($this->engine, $this, $this->styleBuilder);

        parent::init();
    }

    public function configure()
    {
        $this->eventDispatcher->connect('core:initUrl', array($this, 'initUrl'));
        $this->eventDispatcher->connect('core:beforeRouting', array($this, 'beforeRouting'));
        $this->eventDispatcher->connect('core:filterRoute',   array($this, 'determineBrandCategory'));
        $this->eventDispatcher->connect('core:afterRouting',  array($this, 'afterRouting'));
        $this->eventDispatcher->connect('core:afterDispatch', array($this, 'afterDispatch'));
        $this->eventDispatcher->connect('viewSlot:oc_footer', array($this, 'viewSlotOcFooter'));
        $this->eventDispatcher->connect('view:output',        array($this, 'viewOutput'));
        $this->eventDispatcher->connect('ocResponse:output',  array($this, 'ocResponseOutput'));
        $this->eventDispatcher->connect('viewSlot:oc_header/analytics_code', array($this, 'headerAnalyticsCode'));

        $this->pluginBootstrapper = new TB_ExtensionPluginBootstrap($this->engine, $this);
        $this->pluginBootstrapper->configureAll();
    }

    public function initUrl()
    {
        if (!$this->engine->getOcConfig('config_seo_url')) {
            return;
        }

        if (!property_exists('Url', 'enable_cache')) {
            return;
        }

        $theme_settings = $this->getThemeModel()->getSettings();
        Url::$enable_cache = $theme_settings['system']['cache_enabled'] && (!isset($theme_settings['system']['cache_url_level_2']) || $theme_settings['system']['cache_url_level_2']);
    }

    public function beforeRouting()
    {
        $this->route = '';
        $this->settings = $this->getThemeModel()->getSettings();
        $this->registerRoutes();
        $this->themeData->importVars($this->loadDefaultTranslation());
    }

    protected function registerRoutes()
    {
        $this->registerCatalogRoute(array(
            'name'       => 'category_manufacturer',
            'route'      => 'category/manufacturer',
            'controller' => 'brand_category'
        ));

        $this->registerCatalogRoute(array(
            'name'       => 'tb',
            'route'      => 'tb',
            'controller' => 'tb'
        ));
    }

    public function determineBrandCategory(sfEvent $event, $route)
    {
        if ($route != 'product/category' || !isset($this->request->get['path'])  || !is_numeric($this->request->get['path']) || !isset($this->request->get['manufacturer_id'])) {
            return $route;
        }

        $route = 'category/manufacturer';
        $this->engine->getOcRequest()->get['c_id'] = (int) $this->request->get['path'];
        $this->engine->getOcRequest()->get['man_id'] = (int) $this->request->get['manufacturer_id'];

        unset($this->engine->getOcRequest()->get['path']);

        return $route;
    }

    public function afterRouting()
    {
        $this->route = $this->themeData->route;

        $this->themeData->display_footer = true;

        $this->themeData->addCallable(array($this, 'link'));
        $this->themeData->addCallable(array($this, 'getThemeCssLink'));
        $this->themeData->addCallable(array($this, 'echoHeader'));
        $this->themeData->addCallable(array($this, 'echoFooter'));

        $this->setQuickView();
        $this->setBaseTagTarget();
        $this->setCategoryPath();
        $this->setInformationId();
        $this->setProductId();
        $this->setLayout();
        $this->determineAreasForRequest();
        $this->setCurrencyCode();
        $this->setLanguageDirection();
        $this->setSiteBackground();
        $this->outputHandler->determineStylesCacheKey();

        $this->eventDispatcher->notify(new sfEvent($this, 'core:pluginsPreBootstrap'));
        $this->pluginBootstrapper->bootstrapAll();
        $this->eventDispatcher->notify(new sfEvent($this, 'core:pluginsPostBootstrap'));
    }

    public function afterDispatch(sfEvent $event)
    {
        /*
        if (isset($this->request->get['returnThemeLayoutBuilderData'])) {
            $result = array();
            foreach ($this->themeData->request_areas as $area_name => $area_key) {
                $key_parts = explode('_', $area_key);
                $result[] = array(
                    'area_name' => $area_name,
                    'area_type' => array_shift($key_parts),
                    'area_id'   => implode('_', $key_parts)
                );
            }
            foreach ($result as &$area) {
                switch ($area['area_type']) {
                    case 'home':
                        $area['label'] = 'Home';
                        break;
                    case 'default':
                        // Може да е system или layout
                        if ($system_page = $this->getModel('layoutBuilder')->getSystemPageForRoute($this->route)) {
                            $area['label'] = $system_page['label'];
                        } else
                        if ($route_layout_id = (int) $this->engine->getOcModel('design/layout')->getLayout($this->route)) {

                        } else {
                            $area['label'] = 'GLOBAL';
                        }

                        break;
                }
            }
            $event->setReturnValue(json_encode(
                $result
            ));
            $event->setProcessed(true);
        }
        */
        if (isset($this->request->get['setLivePreviewMode'])) {
            $key = $this->engine->getOcConfig()->get($this->engine->getThemeId() . '_livePreviewToken');
            if ($key == base64_decode((string) $this->request->get['setLivePreviewMode'])) {
                $cookie_value = empty($this->request->get['forceHTTPS']) ? 1 : 2;
                setcookie('livePreviewMode', $cookie_value, time() + 14400, '/', $this->request->server['HTTP_HOST']);
            }
        }

        if (!empty($_COOKIE['livePreviewMode'])) {
            $key = $this->engine->getOcConfig()->get($this->engine->getThemeId() . '_livePreviewToken');
            $parts = explode('!*!', $key);
            if (!$key || $parts[1] != TB_Utils::getIp()) {
                $this->engine->getDbSettingsHelper('setting')->deleteKey($this->engine->getThemeId() . '_livePreviewToken', $this->context->getStoreId());
                unset($_COOKIE['livePreviewMode']);
                setcookie('livePreviewMode', null, -1, '/', $this->request->server['HTTP_HOST']);
            } else {
                if (!TB_RequestHelper::isAjaxRequest() && $this->route != 'error/not_found' && false === stripos($this->route, 'captcha') && (!isset($_SERVER['HTTP_REFERER']) || false === stripos($_SERVER['HTTP_REFERER'], 'setLivePreviewMode'))) {
                    $url = TB_Utils::removeQueryStringVar($this->context->getRequestUrl(), 'setLivePreviewMode');
                    $this->engine->getDbSettingsHelper('setting')->persistKey($this->engine->getThemeId() . '_livePreviewToken', $this->context->getStoreId(), 'config', $url . '!*!' . $parts[1] . '!*!' . time());
                }
                if ((int) $_COOKIE['livePreviewMode'] == 2) {
                    $this->forceHTTPS = true;
                }
            }
        }

        $this->outputHandler->assignJavascriptAfterDispatch();

        $tb_setting_keys = $this->themeData->getTbSettingsLoadKeys();
        if (!empty($tb_setting_keys)) {
            $settings = $this->engine->getDbSettingsHelper('setting')->getKeyCollection($tb_setting_keys, $this->context->getStoreId());
            foreach ($settings as $key => $value) {
                $this->themeData[$key] = $value;
            }

            $event = new sfEvent($this->themeData, 'core:loadTbSettings');
            $this->engine->getEventDispatcher()->notify($event);
        }
    }

    public function setHeaderFilter($do_filter)
    {
        $this->outputHandler->setHeaderFilter($do_filter);
    }

    public function setFooterFilter($do_filter)
    {
        $this->outputHandler->setFooterFilter($do_filter);
    }

    public function echoHeader($header)
    {
        $this->outputHandler->echoHeader($header);
    }

    public function echoFooter($footer)
    {
        $this->outputHandler->echoFooter($footer);
    }

    public function ocResponseOutput(sfEvent $event, $output)
    {
        return $this->outputHandler->ocResponseOutput($output);
    }

    public function viewOutput(TB_ViewSlotEvent $event)
    {
        $html = $event->getContent();
        $this->outputHandler->viewOutput($html);
        $event->setContent($html);
    }

    public function headerAnalyticsCode(TB_ViewSlotEvent $event)
    {
        $event->setContent(str_replace(array('<script>', '<script type="text/javascript">'), '<script data-capture="0">', $event->getContent()));
    }

    public function link($route, $args = '', $connection = 'NONSSL')
    {
        if (!$this->engine->getOcConfig()->get('config_seo_url')) {
            return $this->url->link($route, $args, $connection);
        }

        $base_url = $connection ==  'NONSSL' ? $this->context->getBaseHttp() : $this->context->getBaseHttps();
        $base_url .= 'index.php?route=';
        $url = $base_url . $route;
        if ($args) {
            $url .= '&' . ltrim($args, '&');
        }

        $url_info = parse_url(str_replace('&amp;', '&', $url));
        $data = array();
        parse_str($url_info['query'], $data);

        if (isset($data['route'])) {

            if ($data['route'] == 'category/manufacturer' && isset($data['c_id']) && isset($data['man_id'])) {

                $category_url = $this->url->link('product/category' , 'path=' . (int) $data['c_id']);
                if (str_replace('&amp;', '&', $category_url) == $base_url . 'product/category&category_id=' . (int) $data['c_id']) {
                    return str_replace('&', '&amp;', $url);
                }

                $brand_url = $this->url->link('product/manufacturer/info' , 'manufacturer_id=' . (int) $data['man_id']);
                if (str_replace('&amp;', '&', $brand_url) == $base_url . 'product/manufacturer/info&manufacturer_id=' . (int) $data['man_id']) {
                    return $category_url . '&amp;manufacturer_id=' . (int) $data['man_id'];
                }

                $category_parts = explode('/', $category_url);
                $brand_parts    = explode('/', $brand_url);

                foreach ($category_parts as $index => $part) {
                    if ($part == $brand_parts[$index]) {
                        unset($category_parts[$index], $brand_parts[$index]);
                    } else {
                        break;
                    }
                }

                return $this->getBaseHomeUrl(). '/' . implode('/', $category_parts) . '/' . implode('/', $brand_parts);
            }
        }

        return $this->url->link($route, $args, $connection);
    }

    public function getBaseHomeUrl()
    {
        static $home_url;

        if (null !== $home_url) {
            return $home_url;
        }

        $parsed_url = parse_url(str_replace('&amp;', '&', $this->engine->getOcUrl()->link('common/home')));

        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host   = isset($parsed_url['host'])   ? $parsed_url['host'] : '';
        $port   = isset($parsed_url['port'])   ? ':' . $parsed_url['port'] : '';
        $path   = isset($parsed_url['path'])   ? str_replace('/index.php', '', $parsed_url['path']) : '';

        $home_url = rtrim(sprintf('%s%s%s%s', $scheme, $host, $port, $path), '/');

        return $home_url;
    }

    public function addJsContents($str, $hash = null)
    {
        $this->outputHandler->addJsContents($str, $hash);
    }

    protected function setQuickView()
    {
        $this->themeData->is_quickview = !empty($this->request->get['tb_quickview']) && (int) $this->request->get['tb_quickview'] == 1;

        if ($this->themeData->is_quickview) {
            $this->eventDispatcher->connect('core:pluginsPostBootstrap', array($this, 'overrideQuickViewVars'));
            $this->eventDispatcher->connect('layoutBuilder:initAreas',   array($this, 'overrideQuickViewAreas'));
            $this->eventDispatcher->connect('outputHandler:fetchLayout', array($this, 'renderQuickView'));
        }
    }

    protected function setBaseTagTarget()
    {
        $target = '_self';

        if ($this->themeData->is_quickview) {
            $target = '_parent';
        }

        $this->themeData->base_target = $target;
    }

    public function overrideQuickViewVars()
    {
        $style = $this->themeData->style;
        $style['sticky_header'] = false;
        $this->themeData->style = $style;

        $common = $this->themeData->common;
        $common['cookie_policy'] = false;
        $common['scroll_to_top'] = false;
        $this->themeData->common = $common;

        $this->themeData->display_footer = false;
        $this->themeData->fbMeta      = '';
        $this->themeData->twitterMeta = '';
    }

    public function overrideQuickViewAreas()
    {
        $system_product_images = $this->themeData['system.product_images'];
        $system_product_images['fullscreen'] = false;
        $this->themeData['system.product_images'] = $system_product_images;
    }

    public function renderQuickView(sfEvent $event)
    {
        ob_start();
        ob_implicit_flush(0);

        $this->themeData->slotEcho('oc_header');
        echo '<div id="content" class="product-info">';
        echo $this->themeData->renderArea('content');
        echo '</div>';
        $this->themeData->slotEcho('oc_footer');

        $event->setReturnValue(ob_get_clean());
        $event->setProcessed(true);
    }

    protected function setCategoryPath()
    {
        $path = (substr($this->route, 0, 16) == 'product/category' && isset($this->request->get['path']) && !empty($this->request->get['path'])) ? (string) $this->request->get['path'] : null;

        if (null == $path) {
            return;
        }

        $path = array_map(create_function('$value', 'return abs((int) $value);'), explode('_', $path));
        $cnt = count($path);
        $this->themeData->category_id = $path[$cnt-1];
        if ($cnt > 1) {
            $this->themeData->category_top_id = $path[0];
            $this->themeData->category_parent_id = $path[$cnt-2];
        }
        $this->themeData->category_path = implode('_', $path);
    }

    protected function setCurrencyCode()
    {
        $currency_code = $this->engine->gteOc22() ? $this->engine->getOcSession()->data['currency'] : $this->engine->getOcRegistry()->get('currency')->getCode();

        if (isset($this->request->post['currency_code'])) {
            /** @var ModelLocalisationCurrency $ocModelLocalisationCurrency */
            $ocModelLocalisationCurrency = $this->engine->getOcModel('localisation/currency');
            foreach ($ocModelLocalisationCurrency->getCurrencies() as $currency) {
                if ($currency == (string) $this->request->post['currency_code']) {
                    $currency_code = $this->request->post['currency_code'];
                }
            }
        }

        $this->themeData->currency_code = $currency_code;
    }

    protected function setLanguageDirection()
    {
        $lang = $this->engine->loadOcTranslation();
        $direction = $lang['direction'];

        if (!in_array($direction, array('ltr', 'rtl'))) {
            $direction = 'ltr';
        }

        $this->themeData->language_direction = $direction;
    }

    protected function setInformationId()
    {
        if ($this->route == 'information/information' && isset($this->request->get['information_id'])) {
            $this->themeData->information_id = (int) $this->request->get['information_id'];
        }
    }

    protected function setProductId()
    {
        if ($this->route == 'product/product' && isset($this->request->get['product_id'])) {
            $this->themeData->product_id = (int) $this->request->get['product_id'];
        }
    }

    protected function setLayout()
    {
        $layout_id = 0;

        if ($this->themeData->category_id) {
            /** @var ModelCatalogCategory $ocModelCatalogCategory */
            $ocModelCatalogCategory = $this->engine->getOcModel('catalog/category');
            $layout_id = (int) $ocModelCatalogCategory->getCategoryLayoutId($this->themeData->category_id);
        }

        if (substr($this->route, 0, 16) == 'product/product' && isset($this->request->get['product_id'])) {
            /** @var ModelCatalogProduct $ocModelCatalogProduct */
            $ocModelCatalogProduct = $this->engine->getOcModel('catalog/product');
            $layout_id = (int) $ocModelCatalogProduct->getProductLayoutId((int) $this->request->get['product_id']);
            $this->themeData->product_layout_id = $layout_id;
        }

        if (substr($this->route, 0, 24) == 'information/information' && isset($this->request->get['information_id'])) {
            /** @var ModelCatalogInformation $ocModelCatalogInformation */
            $ocModelCatalogInformation = $this->engine->getOcModel('catalog/information');
            $layout_id = (int) $ocModelCatalogInformation->getInformationLayoutId((int) $this->request->get['information_id']);
        }

        /* If there are multiple layouts with the same route, it will just return the first layout */
        $route_layout_id = (int) $this->engine->getThemeModel()->getLayoutIdByRoute($this->route);

        if (!$layout_id && $route_layout_id) {
            $layout_id = $route_layout_id;
        }

        $this->themeData->layout_id = $layout_id;
        $this->themeData->route_layout_id = $route_layout_id;
    }

    public function viewSlotOcFooter(TB_ViewSlotEvent $event)
    {
        $this->outputHandler->viewSlotOcFooter();
    }

    protected function setSiteBackground()
    {
        if ($this->themeData->skip_layout) {
            return;
        }

        $settings  = $this->settings['background'];
        $background = null;
        $themeData = $this->themeData;

        if ($themeData->category_id) {
            if (isset($settings['category'][$themeData->category_id])) {
                $background = $settings['category'][$themeData->category_id];
            } else
            if ($settings['options']['category_inherit'] == 'parent' && isset($settings['category'][$themeData->category_parent_id])) {
                $background = $settings['category'][$themeData->category_parent_id];
            } else
            if (isset($settings['category'][0])) {
                $background = $settings['category'][0];
            } else
            if ($settings['options']['category_inherit'] == 'none') {
                $background = false;
            }
        } else
            if ($themeData->information_id) {
                if (isset($settings['page'][$themeData->information_id])) {
                    $background = $settings['page'][$themeData->information_id];
                } else
                if ($settings['options']['page_inherit'] == 'none') {
                    $background = false;
                }
            }

        if (null === $background && isset($settings['global']) && $settings['global']['type'] != 'none') {
            $background = $settings['global'];
        }

        if (null !== $background) {
            $themeData->body_background = $background;
        }
    }

    public function getStylesCacheKey()
    {
        return $this->outputHandler->getStylesCacheKey();
    }

    public function getStylesFileHash()
    {
        return $this->outputHandler->getStylesFileHash();
    }

    protected function determineAreasForRequest()
    {
        $themeData = $this->themeData;

        $themeData->skip_layout = false;

        if (in_array($themeData->route, array(
            'tb/getProductPrice',
            'product/product/review',
            'checkout/cart/add',
            'checkout/cart/remove',
            'checkout/login',
            'checkout/register',
            'checkout/guest',
            'checkout/checkout/customfield',
            'checkout/checkout/country',
            'checkout/register/save',
            'checkout/payment_method',
            'checkout/payment_address',
            'checkout/payment_address/save',
            'checkout/payment_method/save',
            'checkout/confirm'
        ))) {
            $themeData->skip_layout = true;
        }

        if ($themeData->skip_layout) {
            return;
        }

        $area_keys = $this->getThemeModel()->getAreaKeys();

        $builder_areas = $this->determineAreaData($area_keys['builder'], 'builder');
        $style_areas = $this->determineAreaData($area_keys['style'], 'style');

        foreach (array_keys($style_areas) as $area_name) {
            if (!isset($builder_areas[$area_name])) {
                unset($style_areas[$area_name]);
            }
        }

        $this->request_areas = $themeData->request_areas = $builder_areas;
        $this->request_areas_settings = $themeData->request_areas_settings = $style_areas;

        if ($this->context->isDebug()) {
            $this->engine->fbLog(array(
                'builder'      => $this->request_areas,
                'style'        => $this->request_areas_settings,
                'layout'       => $this->themeData->layout_id,
                'route layout' => $this->themeData->route_layout_id,
                'route'        => $this->themeData->route,
                'rnd'          => mt_rand()
            ));
        }
    }

    protected function determineAreaData(array $keys, $type)
    {
        $result = array(
            'header'       => null,
            'intro'        => null,
            'content'      => null,
            'column_left'  => null,
            'column_right' => null,
            'footer'       => null
        );

        $themeData = $this->themeData;

        $category_level = 0;
        if ($themeData->category_id) {
            $category_level = count(explode('_', $themeData->category_path));
        }

        foreach (array_keys($result) as $area_name) {

            if ($themeData->is_quickview && $area_name != 'content') {
                continue;
            }

            if ($this->route == 'common/home' && isset($keys[$area_name . '_home'])) {
                $result[$area_name] = 'home';
            } else

            if ($themeData->information_id) {
                if (isset($keys[$area_name . '_page_' . $themeData->information_id])) {
                    $result[$area_name] = 'page_' . $themeData->information_id;
                } else
                if (isset($keys[$area_name . '_layout_' . $themeData->layout_id])) {
                    $result[$area_name] = 'layout_' . $themeData->layout_id;
                } else
                if (isset($keys[$area_name . '_layout_' . $themeData->route_layout_id])) {
                    $result[$area_name] = 'layout_' . $themeData->route_layout_id;
                } else
                if (isset($keys[$area_name . '_global'])) {
                    $result[$area_name] = 'global';
                } else
                if (isset($keys[$area_name . '_' . $this->route . '__default'])) {
                    $result[$area_name] = $this->route . '__default';
                } else
                if (isset($keys[$area_name . '__default'])) {
                    $result[$area_name] = '_default';
                }
            } else

            if ($themeData->product_id) {
                if ($themeData->is_quickview && $type == 'builder') {
                    if (isset($keys[$area_name . '_quickview'])) {
                        $result[$area_name] = 'quickview';
                    } else {
                        $result[$area_name] = 'quickview__default';
                    }
                } else
                if ($themeData->product_layout_id && isset($keys[$area_name . '_layout_' . $themeData->product_layout_id])) {
                    $result[$area_name] = 'layout_' . $themeData->product_layout_id;
                } else
                if (isset($keys[$area_name . '_product_global'])) {
                    $result[$area_name] = 'product_global';
                }
            } else

            if ($themeData->category_id || $themeData->route == 'product/category') {
                if (isset($keys[$area_name . '_category_' . $themeData->category_id])) {
                    $result[$area_name] = 'category_' . $themeData->category_id;
                } else
                if (isset($keys[$area_name . '_layout_' . $themeData->layout_id])) {
                    $result[$area_name] = 'layout_' . $themeData->layout_id;
                } else
                if (isset($keys[$area_name . '_layout_' . $themeData->route_layout_id])) {
                    $result[$area_name] = 'layout_' . $themeData->route_layout_id;
                } else
                if (isset($keys[$area_name . '_category_level_' . $category_level])) {
                    $result[$area_name] = 'category_level_' . $category_level;
                } else
                if (isset($keys[$area_name . '_category_global'])) {
                    $result[$area_name] = 'category_global';
                }
            } else

            if (isset($keys[$area_name . '_system_' . $this->route])) {
                $result[$area_name] = 'system_' . $this->route;
            } else

            if (isset($keys[$area_name . '_layout_' . $themeData->layout_id]) && ($type == 'style' || $this->route != 'common/home')) {
                $result[$area_name] = 'layout_' . $themeData->layout_id;
            }

            if (!isset($result[$area_name])) {

                $has_system_defaults = false;
                if (false !== in_array($this->route, $this->getThemeModel()->getSetting('system_routes'))) {
                    $has_system_defaults = isset($keys[$area_name . '_system_' . $this->route . '__default']);
                }

                if (isset($keys[$area_name . '_global'])) {
                    $result[$area_name] = 'global';
                } else
                if ($has_system_defaults) {
                    $result[$area_name] = 'system_' . $this->route . '__default';
                } else
                if (isset($keys[$area_name . '__default'])) {
                    $result[$area_name] = '_default';
                }
            }

        }

        foreach ($result as $key => $value) {
            if (null === $value) {
                unset($result[$key]);
            }
        }

        return $result;
    }

    protected function checkCategoryLevelArea($category_level, $area_widgets)
    {
        if ($category_level && isset($area_widgets['category_level_' . $category_level]) && !empty($area_widgets['category_level_' . $category_level]['rows'])) {
            return true;
        }

        return false;
    }

    protected function checkCategoryGlobalArea($category_level, $area_widgets)
    {
        if ($category_level && isset($area_widgets['category_global']) && !empty($area_widgets['category_global']['rows'])) {
            return true;
        }

        return false;
    }

    public function getRequestAreas()
    {
        return $this->request_areas;
    }

    /**
     * @return array|TB_ExtensionPlugin[]
     */
    public function getPlugins()
    {
        return $this->pluginBootstrapper->getPlugins();
    }

    /**
     * @param $name
     * @return TB_ExtensionPlugin
     */
    public function getPlugin($name)
    {
        return $this->pluginBootstrapper->getPlugin($name);
    }

    public function getStyleBuilder()
    {
        return $this->styleBuilder;
    }
}