<?php

class Theme_Catalog_CommonPlugin extends TB_ExtensionPlugin
{
    public function execute(TB_ViewDataBag $themeData, Request $request)
    {
        $themeData->common         = $this->getSetting('common');
        $themeData->product        = $this->getSetting('product');
        $themeData->config         = $this->engine->getOcConfig();
        $themeData->url            = $this->engine->getOcUrl();
        $themeData->customer       = $this->engine->getOcCustomer();
        $themeData->affiliate      = $this->engine->getOcAffiliate();
        $themeData->theme_config   = $this->engine->getThemeConfig();
        $themeData->language_code  = $this->language_code;
        $themeData->no_image       = $this->getOcModel('tool/image')->resize('no_image.jpg', 100, 100);
        $themeData->meta_generator = $this->context->getThemeInfo('name') . ' ' . $this->context->getThemeInfo('version') . '/BurnEngine ' . TB_Engine::getVersion() . '/OC ' . VERSION . '/PHP ' . phpversion();

        $system = $this->getSetting('system');

        if (!isset($system['js_lazyload']) || TB_RequestHelper::isAjaxRequest()) {
            $system['js_lazyload'] = false;
        }

        if (!$system['cache_enabled']) {
            $system['cache_js']       = false;
            $system['cache_styles']   = false;
            $system['cache_settings'] = false;
            $system['cache_menu']     = false;
            $system['cache_db']       = false;
        }

        if (!empty($system['optimize_exclude']) && array_search($themeData->route, explode("\n", $system['optimize_exclude'])) !== false) {
            $system['optimize_js_load'] = false;
            $system['minify_html']      = false;
            $system['minify_js']        = false;
        }

        if (!$system['optimize_js_load']) {
            // The minify html regular expression improperly removes javascript comments when they are inline
            $system['minify_html'] = false;
        }

        $themeData->system = $system;

        $engine_config = $this->engine->getConfig();
        if (TB_RequestHelper::lteIe9()) {
            $engine_config['catalog_google_fonts_js'] = false;
        }
        $themeData->engine_config = $engine_config;

        $this->setStoreCommon($themeData);
        $this->setProductListingType($themeData, $request);
        $this->addJavascriptVars($themeData);
        $this->setCategorySettings($themeData);
        $this->setPaymentImages($themeData);
        $this->setTemplateVars($themeData);

        $oc_lang = $this->engine->loadOcTranslation();
        $tbPrice = new TB_PriceFormatter($this->engine->getOcCurrency(), $themeData->currency_code, $oc_lang['decimal_point'], $oc_lang['thousand_point']);

        $themeData->addCallable(array($this, 'escapeImg'));
        $themeData->addCallable(array($this, 'extractCartText'));
        $themeData->addCallable(array($this, 'OcVersionGte'));
        $themeData->addCallable(array($tbPrice, 'format'), 'priceFormat');
        $themeData->addCallable(array($tbPrice, 'extract'), 'priceExtract');
        $themeData->addCallable(array($this->getModel('category'), 'getCategoryTreeWithTotalProductsMaxLevel2'));
        $themeData->addCallable(array($this->getModel('category'), 'getCategoriesTree'));

        $this->eventDispatcher->connect('common/header.scripts.filter', array($this, 'filterScripts'));
        $this->eventDispatcher->connect('common/header.styles.filter',  array($this, 'filterStyles'));
        $this->eventDispatcher->connect('module/language.filter', array($this, 'filterLanguageModule'));

        $facebook = $this->getSetting('facebook');
        if (isset($facebook[$this->language_code])) {
            $themeData->facebook = $facebook[$this->language_code];
        } else {
            $themeData->facebook = reset($facebook);
        }

        $twitter = $this->getSetting('twitter');
        if (isset($twitter[$this->language_code])) {
            $themeData->twitter = $twitter[$this->language_code];
        } else {
            $themeData->twitter = reset($twitter);
        }

        $themeData->optimize_js_load = $themeData->system['optimize_js_load'];

        if ($this->engine->isExtensionInstalled('seo') && !$themeData->skip_layout) {
            /** @var Seo_Catalog_DefaultModel $seoModel */
            $seoModel = $this->engine->getExtensionModel('seo');
            $themeData->seo_settings = $seoModel->getSettings();

            if ($themeData->route == 'common/home' && isset($themeData->seo_settings['store_meta'][$this->language_code])) {
                $title_var_name = $this->engine->gteOc2() ? 'config_meta_title' : 'config_title';

                $this->engine->getOcConfig()->set($title_var_name,           $themeData->seo_settings['store_meta'][$this->language_code]['title']);
                $this->engine->getOcConfig()->set('config_meta_description', $themeData->seo_settings['store_meta'][$this->language_code]['description']);
                $this->engine->getOcConfig()->set('config_meta_keyword',     $themeData->seo_settings['store_meta'][$this->language_code]['keyword']);
            }
        }

        if ($themeData->route == 'common/home' && !$this->engine->gteOc2()) {
            $this->engine->getOcDocument()->setKeywords($this->engine->getOcConfig()->get('config_meta_keyword'));
        }

        if ($this->engine->getThemeConfig('additional_icons') && !$themeData->skip_layout) {
            foreach ($this->engine->getThemeConfig('additional_icons') as $resource) {
                $resource_dir = $this->context->getThemeDir() . '/' . $resource;
                if (!is_dir($resource_dir)) {
                    continue;
                }

                $resource_url = $this->context->getThemeRootUrl() . $resource . '/';

                $css_replace = array('url(\'' => 'url(\'' . $resource_url);
                $path = array(
                    'url' => $resource_url . 'icons.css',
                    'dir' => $resource_dir . '/icons.css'
                );
                $themeData->registerStylesheetResource($path, null, $css_replace);
            }
        }
    }

    public function OcVersionGte($version)
    {
        return version_compare(VERSION, $version) >= 0;
    }

    public function escapeImg($src)
    {
        return TB_Utils::escapeHtmlImage($src);
    }

    public function extractCartText($text, $type)
    {
        static $types;

        if (null === $types) {
            $oc_lang = $this->engine->loadOcTranslation($this->engine->gteOc2() ? 'common/cart' : 'module/cart');
            $parts = explode('%s', $oc_lang['text_items']);
            if (isset($parts[2])) {
                $pattern = '/(' . preg_quote($parts[0], '/') . ')(.*)(' . preg_quote($parts[1], '/') . ')(.*)(' . preg_quote($parts[2], '/') . ')/';
                preg_match($pattern, $text, $matches);

                if (isset($matches[5])) {
                    $types['count'] = $matches[2];
                    $types['total'] = $this->engine->getThemeData()->priceFormat($matches[4]);
                }
            }
        }

        return isset($types[$type]) ? $types[$type] : $text;
    }

    public function filterStyles(sfEvent $event, $styles)
    {
        foreach ($styles as $key => $style) {
            if ($this->engine->gteOc2()) {
                if (stripos($key, 'magnific-popup.css') !== false) {
                    unset($styles[$key]);
                }
            } else {
                $system = $this->getThemeData()->system;

                if (!empty($system['compatibility_colorbox']) && stripos($style['href'], 'colorbox') !== false) {
                    unset($styles[$key]);
                }
            }
        }

        if (!empty($this->getThemeData()->system['critical_css'])) {
            $this->getThemeData()->oc_styles = $styles;
            $styles = array();
        }

        return $styles;
    }

    public function filterScripts(sfEvent $event, $scripts)
    {
        foreach ($scripts as $key => $script) {
            if (basename($script) == 'tabs.js') {
                unset($scripts[$key]);
            }

            $system = $this->getThemeData()->system;

            if (!$this->engine->gteOc2() && !empty($system['compatibility_colorbox']) && stripos($script, 'colorbox') !== false) {
                unset($scripts[$key]);
            }

            if ($this->engine->gteOc2()) {
                if (isset($system['compatibility_moment_js']) && !$system['compatibility_moment_js'] && stripos($script, 'moment.min.js') !== false) {
                    unset($scripts[$key]);
                }

                if (stripos($script, 'jquery.magnific-popup.min.js') !== false) {
                    unset($scripts[$key]);
                }
            }
        }

        return $scripts;
    }

    public function filterLanguageModule(sfEvent $event, $languages)
    {
        $enabled_languages = isset($this->getThemeData()->languages) ? $this->getThemeData()->languages : $this->engine->getEnabledLanguages();

        foreach ($languages as &$language) {
            $language = $enabled_languages[$language['code']];
        }

        return $languages;
    }

    protected function setStoreCommon(TB_ViewDataBag $themeData)
    {
        $store = $this->getSetting('store');
        $store_common = $store['common'];
        $stock_statuses = $this->getThemeModel()->getStockStatuses();

        $store_common['preorder_stock_status_name'] = '';
        $store_common['backorder_stock_status_name'] = '';
        $store_common['disable_checkout_stock_status_name'] = '';

        foreach ($stock_statuses as $status) {
            if ($status['stock_status_id'] == $store_common['preorder_stock_status_id']) {
                $store_common['preorder_stock_status_name'] = $status['name'];
            }
            if ($status['stock_status_id'] == $store_common['backorder_stock_status_id']) {
                $store_common['backorder_stock_status_name'] = $status['name'];
            }
            if ($status['stock_status_id'] == $store_common['disable_checkout_stock_status_id']) {
                $store_common['disable_checkout_stock_status_name'] = $status['name'];
            }
        }

        $themeData->store_common = $store_common;
    }

    public function setProductListingType(TB_ViewDataBag $themeData, Request $request)
    {
        if (isset($_COOKIE['listingType']) && ($_COOKIE['listingType'] == 'grid' || $_COOKIE['listingType'] == 'list')) {
            $listingType = $_COOKIE['listingType'];
        } else {
            $store = $this->getSetting('store');
            $listingType = $store['common']['product_listing_view_mode'];
        }

        $themeData->product_listing_type = $listingType;
    }

    public function setCategorySettings(TB_ViewDataBag $themeData)
    {
        if ($themeData->skip_layout) {
            return;
        }

        $store = $this->getSetting('store');
        $view_mode = $themeData->product_listing_type;

        $this->getModel('category')->initCategoryProductsSettings($store['category']['products'][$view_mode], $view_mode);

        $themeData->category = $store['category'];

        $special_lang = $this->engine->loadOcTranslation('product/special');
        $store['category']['products'][$view_mode]['text_tax'] = $special_lang['text_tax'];
        $themeData->category_products_current = $store['category']['products'][$view_mode];
    }

    public function setPaymentImages(TB_ViewDataBag $themeData)
    {
        $payment_images = $this->getSetting('payment_images');

        if (!empty($payment_images['rows'])) {
            foreach($payment_images['rows'] as $key => &$image) {
                if ($image['type'] != 'image') {
                    continue;
                }

                if (empty($image['file']) || !is_file(DIR_IMAGE . $image['file'])) {
                    unset($payment_images['rows'][$key]);
                    continue;
                }

                $image['http_file'] = $this->context->getImageUrl() . $image['file'];
                list($image['width'], $image['height']) = getimagesize(DIR_IMAGE . $image['file']);
            }
        } else {
            $payment_images = array();
        }

        $themeData->payment_images = $payment_images;
    }

    protected function setTemplateVars(TB_ViewDataBag $themeData)
    {
        $url = $this->engine->getOcUrl();

        if ($this->engine->gteOc2()) {
            $themeData['text_welcome'] = sprintf($themeData['text_welcome'], $url->link('account/login', '', 'SSL'), $url->link('account/register', '', 'SSL'));
            $themeData['text_logged'] = sprintf($themeData['text_logged'], $url->link('account/account', '', 'SSL'), $this->engine->getOcCustomer()->getFirstName(), $url->link('account/logout', '', 'SSL'));
        }

        $themeData->gteOc2 = $this->engine->gteOc2();
    }


    protected function addJavascriptVars(TB_ViewDataBag $themeData)
    {
        $themeData->addJavascriptVar('tb/basename',                   $themeData->basename);
        $themeData->addJavascriptVar('tb/no_image',                   $themeData->no_image);
        $themeData->addJavascriptVar('tb/category_path',              $themeData->category_path, false);
        $themeData->addJavascriptVar('tb/route',                      $themeData->route, false);
        $themeData->addJavascriptVar('tb/cache_enabled',              (int) $themeData->system['cache_enabled'], false);
        $themeData->addJavascriptVar('tb/url/shopping_cart',          $this->engine->getOcUrl()->link('checkout/cart'), false);
        $themeData->addJavascriptVar('tb/url/search',                 $this->engine->getOcUrl()->link('product/search'), false);
        $themeData->addJavascriptVar('tb/url/image_cache',            $this->context->getImageUrl() . 'cache/' . ($this->engine->gteOc2() ? 'catalog/' : 'data/'), false);
        $themeData->addJavascriptVar('tb/url/wishlist',               $this->engine->getOcUrl()->link('account/wishlist'), false);
        $themeData->addJavascriptVar('tb/url/compare',                $this->engine->getOcUrl()->link('product/compare'), false);
        $themeData->addJavascriptVar('tb/is_customer_logged',         (int) $this->engine->getOcCustomer()->isLogged(), false);

        $style = $this->getSetting('style');
        $themeData->addJavascriptVar('tb/maximum_width',(int) $style['maximum_width']);
        $themeData->addJavascriptVar('tb/msg_position', $style['msg_position']);
        $themeData->addJavascriptVar('tb/msg_stack',    $style['msg_stack']);
        $themeData->addJavascriptVar('tb/msg_timeout',  $style['msg_timeout']);

        $themeData->addJavascriptVar('lang/text_failure',              $themeData->text_failure);
        $themeData->addJavascriptVar('lang/text_continue',             $themeData->text_continue);
        $themeData->addJavascriptVar('lang/text_continue_shopping',    $themeData->text_continue_shopping);
        $themeData->addJavascriptVar('lang/text_shopping_cart',        $themeData->text_shopping_cart);
        $themeData->addJavascriptVar('lang/text_wishlist',             $themeData->text_wishlist);
        $themeData->addJavascriptVar('lang/text_cart_updated',         $themeData->text_cart_updated);
        $themeData->addJavascriptVar('lang/text_wishlist_updated',     $themeData->text_wishlist_updated);
        $themeData->addJavascriptVar('lang/text_compare_updated',      $themeData->text_compare_updated);
        $themeData->addJavascriptVar('lang/text_product_comparison',   $themeData->text_product_comparison);
        $themeData->addJavascriptVar('lang/text_previous',             $themeData->text_previous);
        $themeData->addJavascriptVar('lang/text_next',                 $themeData->text_next);
        $themeData->addJavascriptVar('lang/text_cookie_policy_title',  $themeData->text_cookie_policy_title);
        $themeData->addJavascriptVar('lang/text_cookie_policy_button', $themeData->text_cookie_policy_button);
    }
}