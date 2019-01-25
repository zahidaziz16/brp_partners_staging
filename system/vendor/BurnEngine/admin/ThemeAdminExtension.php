<?php

class Theme_Admin_Extension extends TB_AdminExtension
{
    protected $init_order = 0;

    /**
     * @var TB_ExtensionPluginBootstrap
     */
    protected $pluginBootstrapper;

    public function getConfig() {}

    public function configure()
    {
        $this->eventDispatcher->connect('core:extensions_preconfigure',  array($this, 'configurePlugins'));
        $this->eventDispatcher->connect('core:extensions_postconfigure', array($this, 'bootstrapPlugins'));
        $this->eventDispatcher->connect('core:admin_dispatch',           array($this, 'afterRouting'));
        $this->eventDispatcher->connect('core:admin_module_install',     array($this, 'installTheme'));
        $this->eventDispatcher->connect('core:admin_module_uninstall',   array($this, 'uninstallTheme'));
    }

    public function configurePlugins()
    {
        $this->pluginBootstrapper = new TB_ExtensionPluginBootstrap($this->engine, $this);
        $this->pluginBootstrapper->configureAll();
    }

    function afterRouting()
    {
        $languages = $this->engine->getEnabledLanguages();

        $language_ids = array();
        foreach ($languages as $language) {
            $language_ids[] = $language['id'];
        }

        $this->themeData->manufacturers = $this->engine->getOcModel('catalog/manufacturer')->getManufacturers();
        $this->themeData->gteOc2 = $this->engine->gteOc2();

        $js_manufacturers = array();
        foreach ($this->themeData->manufacturers as $manufacturer) {
            $js_manufacturers[] = array(
                'id'   => $manufacturer['manufacturer_id'],
                'name' => $manufacturer['name']
            );
        }

        $this->themeData->user = $this->engine->getOcUser();
        $this->themeData->admin_javascript_url = $this->context->getAdminJavascriptUrl();
        $this->themeData->fileManagerUrl = $this->engine->gteOc2() ? $this->getTbUrl()->generateJs('default/fileManager') : 'index.php?route=common/filemanager&token=' . $this->themeData['token'];
        $tbUrl = $this->getTbUrl();

        $this->themeData->addJavascriptVar('tb/Theme-Machine-Name',                  $this->engine->getThemeId());
        $this->themeData->addJavascriptVar('tb/languages',                           $languages);
        $this->themeData->addJavascriptVar('tb/language_ids',                        $language_ids);
        $this->themeData->addJavascriptVar('tb/language_codes',                      array_keys($languages));
        $this->themeData->addJavascriptVar('tb/manufacturers',                       $js_manufacturers);
        $this->themeData->addJavascriptVar('tb/no_image',                            $this->getThemeModel()->getNoImage());
        $this->themeData->addJavascriptVar('tb/url/storeUrl',                        $this->context->getStoreUrl());
        $this->themeData->addJavascriptVar('tb/url/host',                            $this->context->getHost());
        $this->themeData->addJavascriptVar('tb/url/baseHttpsIf',                     $this->context->getBaseHttpsIf());
        $this->themeData->addJavascriptVar('tb/url/theme_admin_javascript_url',      $this->context->getThemeAdminJavascriptUrl());
        $this->themeData->addJavascriptVar('tb/url/theme_catalog_resource_url',      $this->context->getThemeCatalogResourceUrl());
        $this->themeData->addJavascriptVar('tb/url/image_url',                       $this->context->getImageUrl());
        $this->themeData->addJavascriptVar('tb/url/themes_root_url',                 $this->context->getThemesRootUrl());
        $this->themeData->addJavascriptVar('tb/url/fileManager',                     $this->themeData->fileManagerUrl);
        $this->themeData->addJavascriptVar('tb/url/fonts/getFontData',               $tbUrl->generateJs('fonts/getFontData'));
        $this->themeData->addJavascriptVar('tb/url/fonts/getFontsList',              $tbUrl->generateJs('fonts/getFontsList'));
        $this->themeData->addJavascriptVar('tb/url/import/uploadFiles',              $tbUrl->generateJs('import/uploadFiles'));
        $this->themeData->addJavascriptVar('tb/url/import/checkSettings',            $tbUrl->generateJs('import/checkSettings'));
        $this->themeData->addJavascriptVar('tb/url/import/evaluateFile',             $tbUrl->generateJs('import/evaluateFile'));
        $this->themeData->addJavascriptVar('tb/url/themes/extractTheme',             $tbUrl->generateJs('themes/extractTheme'));
        $this->themeData->addJavascriptVar('tb/url/menu/contents',                   $tbUrl->generateJs('menu/contents'));
        $this->themeData->addJavascriptVar('tb/url/menu/save',                       $tbUrl->generateJs('menu/save'));
        $this->themeData->addJavascriptVar('tb/url/menu/remove',                     $tbUrl->generateJs('menu/remove'));
        $this->themeData->addJavascriptVar('tb/url/menu/contentsByLanguage',         $tbUrl->generateJs('menu/contentsByLanguage'));
        $this->themeData->addJavascriptVar('tb/url/icon/getList',                    $tbUrl->generateJs('icon/getList'));
        $this->themeData->addJavascriptVar('tb/url/style/loadAreaPreset',            $tbUrl->generateJs('style/loadAreaPreset'));
        $this->themeData->addJavascriptVar('tb/url/style/renderSection',             $tbUrl->generateJs('style/renderSection'));
        $this->themeData->addJavascriptVar('tb/url/style/removePreset',              $tbUrl->generateJs('style/removePreset'));
        $this->themeData->addJavascriptVar('tb/url/style/getPresetColorGroup',       $tbUrl->generateJs('style/getPresetColorGroup'));
        $this->themeData->addJavascriptVar('tb/url/style/getPresetFontGroup',        $tbUrl->generateJs('style/getPresetFontGroup'));
        $this->themeData->addJavascriptVar('tb/url/getLivePreviewToken',             $tbUrl->generateJs('default/getLivePreviewToken'));
        $this->themeData->addJavascriptVar('tb/url/getImagePath',                    $tbUrl->generateJs('default/getImagePath'));
        $this->themeData->addJavascriptVar('tb/url/removeInstallSampleData',         $tbUrl->generateJs('default/removeInstallSampleData'));
        $this->themeData->addJavascriptVar('tb/url/getCategoryFlatTreeJSON',         $tbUrl->generateJs('default/getCategoryFlatTreeJSON'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/areaBuilder',       $tbUrl->generateJs('layoutBuilder/areaBuilder'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/modifiedMenu',      $tbUrl->generateJs('layoutBuilder/modifiedMenu'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/systemBlocks',      $tbUrl->generateJs('layoutBuilder/systemBlocks'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/removeSettings',    $tbUrl->generateJs('layoutBuilder/removeSettings'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/getNewWidgetRow',   $tbUrl->generateJs('layoutBuilder/getNewWidgetRow'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/saveRows',          $tbUrl->generateJs('layoutBuilder/saveRows'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/saveAreaTemplate',  $tbUrl->generateJs('layoutBuilder/saveAreaTemplate'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/serializeArea',     $tbUrl->generateJs('layoutBuilder/serializeArea'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/loadAreaExport',    $tbUrl->generateJs('layoutBuilder/loadAreaExport'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/removeAreaTemplate',$tbUrl->generateJs('layoutBuilder/removeAreaTemplate'));
        $this->themeData->addJavascriptVar('tb/url/layoutBuilder/loadAreaTemplate',  $tbUrl->generateJs('layoutBuilder/loadAreaTemplate'));
        $this->themeData->addJavascriptVar('tb/url/widget/saveToFavourites',         $tbUrl->generateJs('widget/saveToFavourites'));
        $this->themeData->addJavascriptVar('tb/url/widget/removeFromFavourites',     $tbUrl->generateJs('widget/removeFromFavourites'));
        $this->themeData->addJavascriptVar('tb/url/adminBase',                       $this->context->getAdminBaseUrl());
        $this->themeData->addJavascriptVar('tb/auth_token',                          $this->themeData->token);
        $this->themeData->addJavascriptVar('tb/post_max_size',                       $this->convertBytes(ini_get('post_max_size')));
    }

    private function convertBytes($val) {
        $last = strtolower($val[strlen($val)-1]);
        $val  = floatval(trim($val));

        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    /**
     * @return TB_Installer
     */
    public function getInstaller()
    {
        static $installer;

        if (null === $installer) {
            require_once TB_THEME_ROOT . '/library/Installer.php';

            $installer = new TB_Installer($this->engine, $this);
        }

        return $installer;
    }

    public function getInitOptions()
    {
        return false;
    }

    public function canEdit()
    {
        return false;
    }

    public function bootstrapPlugins()
    {
        $this->pluginBootstrapper->bootstrapAll();
        $theme_settings = $this->getThemeModel()->getSettings();

        if (empty($theme_settings)) {
            $theme_settings = array();
        }

        $this->filterThemeSettings($theme_settings);
        $this->getThemeModel()->setSettings($theme_settings);
        $this->themeData['theme_settings'] = $theme_settings;
    }

    public function filterThemeSettings(array &$data)
    {
        $plugins = $this->pluginBootstrapper->getPlugins();
        $colorsPlugin = $plugins['Colors'];
        unset($plugins['Colors']);
        TB_Utils::arrayInsert($plugins, 0, array('Colors' => $colorsPlugin));

        foreach ($plugins as $plugin) {
            if ($plugin instanceof TB_AdminDataPlugin) {
                if  (!isset($data[$plugin->getConfigKey()])) {
                    $data[$plugin->getConfigKey()] = array();
                }
                $plugin->filterSettings($data[$plugin->getConfigKey()]);
            }
        }
    }

    public function setPluginsViewData(array &$data)
    {
        foreach ($this->pluginBootstrapper->getPlugins() as $plugin) {
            if ($plugin instanceof TB_AdminDataPlugin && $plugin->getConfigKey() == 'common') {
                $plugin->setDataForView($data, $this->themeData);
            }
        }

        foreach ($this->pluginBootstrapper->getPlugins() as $plugin) {
            if ($plugin instanceof TB_AdminDataPlugin && isset($data[$plugin->getConfigKey()])) {
                if ($plugin->getConfigKey() != 'common') {
                    $plugin->setDataForView($data[$plugin->getConfigKey()], $this->themeData);
                }
            } else
            if (method_exists($plugin, 'setDataForView')) {
                $plugin->setDataForView($data, $this->themeData);
            }
        }
    }

    /**
     * @return array|TB_ExtensionPlugin[]
     */
    public function getPlugins()
    {
        return $this->pluginBootstrapper->getPlugins();
    }

    /**
     * @param string $name
     * @return TB_ExtensionPlugin
     */
    public function getPlugin($name)
    {
        return $this->pluginBootstrapper->getPlugin($name);
    }

    public function loadDefaultTranslation()
    {
        return $this->loadTranslation('theme');
    }
}