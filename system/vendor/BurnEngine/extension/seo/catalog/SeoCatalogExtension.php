<?php

class Seo_Catalog_Extension extends TB_CatalogExtension
{
    /**
     * @var ControllerCommonSeoUrl
     */
    protected $seoUrlController;

    /** @var  Seo_Catalog_DefaultModel */
    protected $defaultModel;

    /**
     * @var Seo_Catalog_GeneratorModel
     */
    protected $generatorModel;

    /**
     * @var array
     */
    protected $settings = array();

    public function configure()
    {
        if (!defined('TB_SEO_MOD')) {
            return;
        }

        if ($this->engine->getOcConfig('config_seo_url')) {
            $this->eventDispatcher->connect('core:beforeRouting', array($this, 'beforeRouting'));
            $this->eventDispatcher->connect('core:pluginsPreBootstrap', array($this, 'setLanguages'));
            $this->eventDispatcher->connect('oc:ControllerCommonSeoUrl/index', array($this, 'initOcSeoUrlController'));
            $this->eventDispatcher->connect('catalog.template.header', array($this, 'addHeaderMeta'));

            if ($this->engine->gteOc23()) {
                $this->eventDispatcher->connect('oc:controller/startup/seo_url/after', array($this, 'afterSeoUrl'));
            }
        }

        $this->eventDispatcher->connect('product/product.page_title.filter',  array($this, 'filterProductHeadingTitle'));

        if ($this->themeData->category_id) {
            $this->eventDispatcher->connect('product/category.page_title.filter', array($this, 'filterCategoryHeadingTitle'));
        }

        $this->defaultModel   = $this->getModel('default');
        $this->generatorModel = $this->getModel('generator');
    }

    public function afterSeoUrl()
    {
        null !== ControllerStartupSeoUrl::$new_route && ($this->engine->getOcRequest()->get['route'] = ControllerStartupSeoUrl::$new_route);
    }

    public function filterProductHeadingTitle(sfEvent $event, array $data)
    {
        $settings = $this->defaultModel->getGeneralSettings();

        $data['heading_title'] = $this->generatorModel->buildH1Heading('product', $event['data']['product_id'], $settings['product']['h1_heading']);
    }

    public function filterCategoryHeadingTitle(sfEvent $event, array $data)
    {
        $settings = $this->defaultModel->getGeneralSettings();

        $data['heading_title'] = $this->generatorModel->buildH1Heading('category', $this->themeData->category_id, $settings['category']['h1_heading']);
    }

    public function addHeaderMeta(TB_ViewSlotEvent $event)
    {
        if (null === $this->seoUrlController) {
            return;
        }

        if (!$this->settings['hreflang_tag'] || (!$this->settings['multilingual_keywords'] && !$this->settings['language_prefix'])) {
            return;
        }

        $content = '';
        foreach ($this->themeData->languages as $language) {
            $content .= sprintf('<link rel="alternate" href="%s" hreflang="%s" />', $language['current_url'], $language['code']) . "\n";
        }

        $event->insertContentAfter($content);
    }

    public function beforeRouting(sfEvent $event)
    {
        $this->settings = array_merge($this->defaultModel->getSettings(), $this->defaultModel->getGeneralSettings());
        $request = $this->engine->getOcRequest();

        if ($this->settings['redirect_to_seo'] && $this->engine->getOcConfig('config_seo_url') && empty($request->get['tb_quickview'])) {
            $this->redirectGetRoute();
        }

        if ($this->engine->getOcConfig('config_seo_url') && $this->settings['language_prefix']) {
            if (!TB_RequestHelper::isAjaxRequest() || strpos($this->context->getRequestUrl(), '?') === false || (!empty($request->get['_route_']) && strpos($this->context->getRequestUrl(), '?') !== false)) {
                $this->checkLanguageRequest();
            } else
            if (TB_RequestHelper::isAjaxRequest() && !empty($request->get['route']) && !empty($request->cookie['language']) && !empty($_SERVER['HTTP_REFERER'])) {
                // Some ajax requests do not contain language information as they are not seo_urls, like 'index.php?route=checkout/cart/add'
                // They change the system language to the default one
                $url_parts = parse_url($_SERVER['HTTP_REFERER']);
                list($language_code) = $parts = explode('/', ltrim($url_parts['path'], '/'));

                if ($settings_code = array_search($language_code, $this->settings['language_prefix_codes'])) {
                    $language_code = $settings_code;
                }

                $languages = $this->engine->getEnabledLanguages();

                if (!isset($languages[$language_code])) {
                    $language_code = $this->engine->getDefaultCatalogLanguage('code');
                }

                if ($language_code != $this->context->getCurrentLanguage('code')) {
                    $this->changeCurrentLanguage($languages[$language_code], $request->server['HTTP_HOST']);
                }
            }
        }

        $event['themeData']['seo_settings'] = $this->settings;
    }

    protected function redirectGetRoute()
    {
        $request = $this->engine->getOcRequest();

        if ($this->context->getArea() == 'admin' || !isset($request->get['route']) || !empty($request->post) || TB_RequestHelper::isAjaxRequest()) {
            return;
        }

        $route    = $request->get['route'];
        $arg      = '';
        $cat_path = false;

        if ($route == 'product/product' && isset($request->get['product_id'])) {
            $route = 'product_id=' . $request->get['product_id'];
        } else
        if ($route == 'product/category' && isset($request->get['path'])) {
            $cat_path = '';

            foreach (explode('_', $request->get['path']) as $category_id) {
                $query = $this->engine->getOcDb()->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category_id . "'");

                if ($query->num_rows && $query->row['keyword']) {
                    $cat_path .= '/' . $query->row['keyword'];
                } else {
                    $cat_path = false;
                    break;
                }
            }

            $arg = trim($cat_path, '/');
        } elseif ($route == 'product/manufacturer/info' && isset($request->get['manufacturer_id'])) {
            $route = 'manufacturer_id=' . (int) $request->get['manufacturer_id'];
        } elseif ($route == 'information/information' && isset($request->get['information_id'])) {
            $route = 'information_id=' . (int) $request->get['information_id'];
        } elseif (sizeof($request->get) > 1) {
            $args = '?' . str_replace('route=' . $route.'&amp;', '', $request->server['QUERY_STRING']);
            $arg = str_replace('&amp;', '&', $args);
        } elseif ($route == 'common/home') {
            $arg = HTTP_SERVER;
        }

        $record = $this->engine->getDbHelper()->getRecord('url_alias', array('query' => $route));

        if ($record) {
            $this->engine->getOcResponse()->redirect($record['keyword'] . $arg, 301);
        } elseif ($cat_path) {
            $this->engine->getOcResponse()->redirect($arg, 301);
        }
    }

    protected function checkLanguageRequest()
    {
        $request = $this->engine->getOcRequest();

        if (!empty($request->get['_route_'])) {
            list($language_code) = $parts = explode('/', $request->get['_route_']);

            if ($settings_code = array_search($language_code, $this->settings['language_prefix_codes'])) {
                $language_code = $settings_code;
            }

            $languages = $this->engine->getEnabledLanguages();

            if (!isset($languages[$language_code])) {
                $language_code = $this->engine->getDefaultCatalogLanguage('code');
            } else {
                array_shift($parts);
                $request->get['_route_'] = implode('/', $parts);
            }

            if (empty($request->get['_route_']) && empty($request->get['route'])) {
                $request->get['route'] = 'common/home';
                unset($request->get['_route_']);
            }

            $url_language_code = $this->getUrlLanguageCode();
            if (empty($url_language_code) && !$this->settings['default_language_prefix']) {
                $url_language_code = $this->engine->getDefaultCatalogLanguage('code');
            }

            if ($language_code != $url_language_code) {
                $this->changeCurrentLanguage($languages[$language_code], $request->server['HTTP_HOST']);
            }
        } else
        if ($request->server['REQUEST_METHOD'] != 'POST' && !$this->settings['default_language_prefix'] && (empty($request->get['route']) || $request->get['route'] == 'common/home')) {
            $request->get['route'] = 'common/home';
            if ($this->context->getCurrentLanguage('code') != $this->engine->getDefaultCatalogLanguage('code')) {
                $this->changeCurrentLanguage($this->engine->getDefaultCatalogLanguage(), $request->server['HTTP_HOST']);
            }
        } else
        if ($request->server['REQUEST_METHOD'] != 'POST' && !empty($request->get['route']) && $this->settings['language_prefix'] && !$this->settings['default_language_prefix']) {
            if ($this->context->getCurrentLanguage('code') != $this->engine->getDefaultCatalogLanguage('code')) {
                $this->changeCurrentLanguage($this->engine->getDefaultCatalogLanguage(), $request->server['HTTP_HOST']);
            }
        }
    }

    protected function changeCurrentLanguage($language_data, $HTTP_HOST)
    {
        $this->engine->getThemeExtension()->removeTranslation('theme');

        $language_code = $language_data['code'];

        setcookie('language', $language_code, time() + 60 * 60 * 24 * 30, '/', $HTTP_HOST);
        $this->engine->getOcSession()->data['language'] = $language_code;
        $this->engine->getOcConfig()->set('config_language_id', $language_data['language_id']);
        $this->engine->getOcConfig()->set('config_language', $language_code);

        $this->context->setCurrentLanguage($language_data);

        foreach ($this->engine->getThemeExtension()->getPlugins() as $plugin) {
            $plugin->refreshLanguage();
        }

        $this->themeData->importVars($this->engine->getThemeExtension()->loadDefaultTranslation());

        foreach ($this->engine->getExtensions() as $extension) {
            $extension->loadDefaultTranslation();
        }

        $language = new Language($language_data['directory']);
        $language->load($language_data['filename']);
        $this->engine->getOcRegistry()->set('language', $language);
    }

    public function getUrlLanguageCode()
    {
        $settings = $this->defaultModel->getSettings();

        if (!$settings['language_prefix']) {
            return '';
        }

        $url_code = $this->context->getCurrentLanguage('code');

        if (!$settings['default_language_prefix'] && $url_code == $this->engine->getDefaultCatalogLanguage('code')) {
            $url_code = '';
        } else
        if (!empty($this->settings['language_prefix_codes'][$url_code])) {
            $url_code = $this->settings['language_prefix_codes'][$url_code];
        }


        return $url_code;
    }

    public function initOcSeoUrlController(sfEvent $event)
    {
        $this->seoUrlController = $event->getSubject();

        $this->seoUrlController->setUrlLanguageCode($this->getUrlLanguageCode());
        $this->seoUrlController->setUrlLanguageId($this->context->getCurrentLanguage('id'));

        $request = $this->engine->getOcRequest();

        if (!empty($request->get['_route_']) && $this->settings['pretty_urls'] && $this->engine->getOcConfig()->get('config_seo_url')) {
            $route_parts = explode('/', $request->get['_route_']);

            if (count($route_parts) >= 2) {
                $found = false;
                $file = str_replace(array('../', './'), '', $route_parts[0] . '/' . $route_parts[1]) . '.php';

                if (is_file($this->context->getAreaDir() . '/controller/' . $file)) {
                    $found = true;
                }

                if (!$found && isset($route_parts[3])) {
                    $file = str_replace(array('../', './'), '', $route_parts[0] . '/' . $route_parts[1] . '/' . $route_parts[2]) . '.php';

                    if (is_file($this->context->getAreaDir() . '/controller/' . $file)) {
                        $found = true;
                    }
                }

                if (!$found) {
                    foreach ($this->engine->getRoutesBag()->exportVars() as $extension => $routes) {
                        foreach ($routes as $route) {
                            if (0 === stripos($request->get['_route_'], $route['route'])) {
                                $found = true;

                                break;
                            }
                        }
                    }
                }

                if ($found) {
                    if ($this->engine->gteOc22()) {
                        ControllerStartupSeoUrl::$new_route = $request->get['_route_'];
                    } else {
                        ControllerCommonSeoUrl::$new_route = $request->get['_route_'];
                    }
                }
            }
        }

        if ($this->engine->gteOc22()) {
            ControllerStartupSeoUrl::$use_original_table = !$this->settings['multilingual_keywords'] || $this->settings['original_language']['code'] == $this->engine->getContext()->getCurrentLanguage('code');
            ControllerStartupSeoUrl::$use_original_table_fallback = true;
            ControllerStartupSeoUrl::$pretty_urls = !empty($this->settings['pretty_urls']);
        } else {
            ControllerCommonSeoUrl::$use_original_table = !$this->settings['multilingual_keywords'] || $this->settings['original_language']['code'] == $this->engine->getContext()->getCurrentLanguage('code');
            ControllerCommonSeoUrl::$use_original_table_fallback = true;
            ControllerCommonSeoUrl::$pretty_urls = !empty($this->settings['pretty_urls']);
        }
    }

    public function setLanguages()
    {
        if (null === $this->seoUrlController) {
            return;
        }

        $languages = $this->engine->getEnabledLanguages();

        if (!$this->settings['multilingual_keywords'] && !$this->settings['language_prefix']) {
            $this->themeData->languages = $languages;

            return;
        }

        $current_language = $this->engine->getContext()->getCurrentLanguage();

        $url = $this->engine->getOcUrl();
        $config = $this->engine->getOcConfig();

        $request_get = $this->engine->getOcRequest()->get;
        unset($request_get['route'], $request_get['_route_']);
        $get_query = http_build_query($request_get);

        if ($this->engine->gteOc22()) {
            $use_original_table = ControllerStartupSeoUrl::$use_original_table;
        } else {
            $use_original_table = ControllerCommonSeoUrl::$use_original_table;
        }

        $url_enable_cache = null;
        if (defined('TB_OPTIMIZATIONS_MOD') && property_exists('Url', 'enable_cache')) {
            $url_enable_cache = Url::$enable_cache;
            Url::$enable_cache = false;
        }

        foreach ($languages as &$language) {
            $config->set('config_language', $language['code']);
            $config->set('config_language_id', $languages[$language['code']]['id']);

            $this->context->setCurrentLanguage($languages[$language['code']]);
            $this->seoUrlController->setUrlLanguageCode($this->getUrlLanguageCode());
            $this->seoUrlController->setUrlLanguageId($languages[$language['code']]['id']);
            if (defined('TB_OPTIMIZATIONS_MOD') && property_exists('Url', 'current_language_id')) {
                Url::$current_language_id = $languages[$language['code']]['id'];
            }

            if ($this->engine->gteOc22()) {
                ControllerStartupSeoUrl::$use_original_table = !$this->settings['multilingual_keywords'] || $language['code'] == $this->settings['original_language']['code'];
            } else {
                ControllerCommonSeoUrl::$use_original_table = !$this->settings['multilingual_keywords'] || $language['code'] == $this->settings['original_language']['code'];
            }

            $language['current_url'] = $url->link($this->themeData->route, $get_query, TB_RequestHelper::isRequestHTTPS());
        }

        if (defined('TB_OPTIMIZATIONS_MOD') && property_exists('Url', 'enable_cache')) {
            Url::$enable_cache = $url_enable_cache;
        }

        $this->context->setCurrentLanguage($current_language);
        $this->seoUrlController->setUrlLanguageCode($this->getUrlLanguageCode());
        $this->seoUrlController->setUrlLanguageId($current_language['id']);

        if ($this->engine->gteOc22()) {
            ControllerStartupSeoUrl::$use_original_table = $use_original_table;
        } else {
            ControllerCommonSeoUrl::$use_original_table = $use_original_table;
        }

        if (defined('TB_OPTIMIZATIONS_MOD') && property_exists('Url', 'current_language_id')) {
            Url::$current_language_id = $current_language['language_id'];
        }

        $config->set('config_language', $current_language['code']);
        $config->set('config_language_id', $current_language['language_id']);

        $this->themeData->languages = $languages;
    }
}