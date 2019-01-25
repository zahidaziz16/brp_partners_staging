<?php

class Theme_Admin_CommonPlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    /** @var  Theme_Admin_LayoutBuilderModel */
    protected $layoutBuilderModel;

    /** @var  Theme_Admin_CategoryModel */
    protected $categoryModel;

    public function getConfigKey()
    {
        return 'common';
    }

    public function configure(TB_ViewDataBag $themeData)
    {
        $this->layoutBuilderModel = $this->getModel('layoutBuilder');
        $this->categoryModel = $this->getModel('category');

        $this->eventDispatcher->connect('core:extensions_postconfigure',  array($this, 'onEngineBoot'));
    }

    public function filterSettings(array &$common_settings)
    {
        $default_vars = array(
            'manufacturers_enabled'       => 1,
            'compare_enabled'             => 1,
            'wishlist_enabled'            => 1,
            'checkout_enabled'            => 1,
            'voucher_enabled'             => 1,
            'affiliate_enabled'           => 1,
            'returns_enabled'             => 1,
            'scroll_to_top'               => 1,
            'label_new_days'              => 5,
            'custom_css'                  => '',
            'custom_javascript'           => '',
            'cookie_policy'               => ''
        );

        $common_settings = TB_FormHelper::initFlatVarsSimple($default_vars, $common_settings);
    }

    public function setDataForView(&$data, TB_ViewDataBag $themeData)
    {
        $themeData->addCallable(array($this, 'getCategoriesLevel1'));
        $themeData->addCallable(array($this, 'getCategoryName'));
        $themeData->addCallable(array($this->getModel('default'), 'getInformationPages'));

        $themeData->engine_version          = TB_Engine::getVersion();
        $themeData->gteOc22                 = $this->engine->gteOc22();
        $themeData->theme_info              = $this->context->getThemeInfo();
        $themeData->theme_id                = $this->engine->getThemeId();
        $themeData->current_store           = $this->getThemeModel()->getCurrentStore();
        $themeData->oc_layouts              = $this->layoutBuilderModel->getLayouts();
        $themeData->system_pages            = $this->layoutBuilderModel->getSystemPages();
        $themeData->system_menu_pages       = $this->getSystemMenuPages();
        $themeData->current_language_code   = $this->engine->getContext()->getCurrentLanguage('code');
        $themeData->system                  = $this->getSetting('system');
        $themeData->product_listing_layouts = $this->engine->getThemeConfig('product_listing_layouts', array());

        $enabled_languages = $this->engine->getEnabledLanguages();
        $first_language = reset($enabled_languages);
        $themeData->first_language_code = $first_language['code'];
        $themeData->enabled_languages   = $enabled_languages;

        if ($this->engine->getThemeConfig()) {
            // The table burnengine_setting is not available during install
            $engine_log_install = $this->engine->getSettingsModel('engine_log', 0)->getScopeSettings('install');
            if (empty($engine_log_install)) {
                $install_info = $this->getSetting('install_info');

                if (!isset($install_info['install_date'])) {
                    $install_info['install_date'] = '';
                }

                if (!isset($install_info['oc_version'])) {
                    $install_info['oc_version'] = '';
                }

                $engine_log_install = array(
                    'host'           => $this->context->getHost(),
                    'base_http'      => $this->context->getBaseHttpsIf(),
                    'ip'             => TB_Utils::getIp(),
                    'date'           => $install_info['install_date'],
                    'oc_version'     => $install_info['oc_version'],
                    'engine_version' => $install_info['engine_version'],
                    'language'       => $this->context->getCurrentLanguage('code')
                );
            }

            $themeData->engine_log_install = $engine_log_install;
        }

        $additional_icons = array();

        if ($this->engine->getThemeConfig('additional_icons')) {
            foreach ($this->engine->getThemeConfig('additional_icons') as $name => $resource) {
                $resource_dir = $this->context->getThemeDir() . '/' . $resource;
                if (!is_dir($resource_dir)) {
                    continue;
                }

                $additional_icons[] = array(
                    'name' => $name,
                    'dir'  => $resource_dir . '/',
                    'url'  => $this->context->getThemeRootUrl() . $resource . '/'
                );
            }
        }

        $themeData->additional_icons = $additional_icons;

        $themeData->has_rtl = false;
        foreach ($themeData->enabled_languages as $language) {
            $_ = array();

            $language_dir = $this->context->getCatalogLanguageDir() . '/' . $language['directory'];
            $lang_file = $language_dir . '/' . $language['filename'] . '.php';

            if (!is_file($lang_file)) {
                $lang_file = $language_dir . '/default.php';
            }

            if (!is_file($lang_file)) {
                continue;
            }

            require $lang_file;

            if (isset($_['direction']) && $_['direction'] == 'ltr') {
                $themeData->has_rtl = true;

                break;
            }
        }

        $missing_files = array(
            $this->context->getImageDir() . '/no_image.jpg',
            $this->context->getImageDir() . '/no_image.png',
            $this->context->getImageDir() . '/placeholder.png'
        );

        foreach ($missing_files as $key => $file) {
            if (is_file($file)) {
                unset($missing_files[$key]);
            }
        }

        $themeData->missing_files = $missing_files;
    }

    public function onEngineBoot()
    {
        $this->assignOcAdminMenu();
    }

    public function assignOcAdminMenu()
    {
        if (!isset($this->engine->getOcRequest()->request['route'])) {
            $this->getThemeData()->oc_admin_menu = '';

            return;
        }

        $menu = array();
        $in_BurnEngine = false !== strpos($this->engine->getOcRequest()->request['route'], 'BurnEngine');

        $menu[] = array(
            'url'  => $in_BurnEngine ? 0 : $this->extension->getTbUrl()->generate('default', '', array('hash' => 'tb_cp_panel_theme_settings')),
            'name' => 'Theme Settings'
        );

        $menu[] = array(
            'url'  => $in_BurnEngine ? 1 : $this->extension->getTbUrl()->generate('default', '', array('hash' => 'tb_cp_panel_navigation')),
            'name' => 'Menu Composer'
        );

        $menu[] = array(
            'url'  => $in_BurnEngine ? 2 : $this->extension->getTbUrl()->generate('default', '', array('hash' => 'tb_cp_panel_layout_builder')),
            'name' => 'Page Builder'
        );

        $menu[] = array(
            'url'  => $in_BurnEngine ? 3 : $this->extension->getTbUrl()->generate('default', '', array('hash' => 'tb_fireslider_tab_content')),
            'name' => 'Fire Slider'
        );

        $menu[] = array(
            'url'  => $in_BurnEngine ? 6 : $this->extension->getTbUrl()->generate('default', '', array('hash' => 'tb_cp_panel_extensions')),
            'name' => 'Extensions'
        );

        $this->getThemeData()->oc_admin_menu = $this->extension->fetchTemplate('oc_admin_menu', array(
           'menus' => $menu
        ));
    }

    public function getCategoriesLevel1()
    {
        return $this->categoryModel->getCategoriesByParent(0);
    }

    public function getCategoryName($category_id)
    {
        $category = $this->categoryModel->getCategory($category_id);

        return $category['name'];
    }

    public function getSystemMenuPages()
    {
        $fallback_language = $this->context->getEngineConfig('fallback_language');
        if (!(version_compare(VERSION, '2.0.0.0') >= 0) && $fallback_language == 'en-gb') {
            $fallback_language = 'english';
        }

        $lang_file = $this->context->getCatalogLanguageDir() . '/' . $fallback_language . '/' . $this->context->getBasename() . '/theme.lang.php';

        if (!is_file($lang_file)) {
            throw new Exception('A file required by ' . ucfirst($this->engine->getThemeId()) . ' theme is missing. Filepath: ' . $lang_file);
        }

        $_ = array();
        /** @noinspection PhpIncludeInspection */
        require tb_modification($lang_file);

        $default = array();
        foreach ($this->layoutBuilderModel->getSystemPages() as $pages) {
            foreach ($pages as $page) {
                if (isset($_['text_' . str_replace('/', '_', $page['route'])])) {
                    $default[str_replace('/', '_', $page['route'])] = $_['text_' . str_replace('/', '_', $page['route'])];
                }
            }
        }

        $result = array();
        foreach ($this->engine->getEnabledLanguages() as $language_code => $language) {
            $lang_file = $this->context->getCatalogLanguageDir() . '/' . $language['directory'] . '/' . $this->context->getBasename() . '/theme.lang.php';

            if (is_file($lang_file) && !in_array(strtolower($language['directory']), array('en-gb', 'english'))) {
                $_ = array();
                /** @noinspection PhpIncludeInspection */
                require tb_modification($lang_file);

                foreach ($this->layoutBuilderModel->getSystemPages() as $pages) {
                    foreach ($pages as $page) {
                        if (isset($_['text_' . str_replace('/', '_', $page['route'])])) {
                            $result[$language_code][str_replace('/', '_', $page['route'])] = $_['text_' . str_replace('/', '_', $page['route'])];
                        } else
                        if (isset($default[str_replace('/', '_', $page['route'])])){
                            $result[$language_code][str_replace('/', '_', $page['route'])] = $default[str_replace('/', '_', $page['route'])];
                        }
                    }
                }
            } else {
                $result[$language_code] = $default;
            }
        }

        return $result;
    }

    public function saveData($post_data)
    {
        return array(
            $this->getConfigKey() => $post_data[$this->getConfigKey()]
        );
    }
}