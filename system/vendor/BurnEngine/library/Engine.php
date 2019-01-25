<?php

require_once TB_THEME_ROOT . '/library/vendor/sfFinder.php';
require_once TB_THEME_ROOT . '/library/vendor/sfEventDispatcher.php';
require_once TB_THEME_ROOT . '/library/vendor/MathFraction.php';

require_once TB_THEME_ROOT . '/library/Get.php';
require_once TB_THEME_ROOT . '/library/Utils.php';
require_once TB_THEME_ROOT . '/library/Cache.php';
require_once TB_THEME_ROOT . '/library/Widget.php';
require_once TB_THEME_ROOT . '/library/Context.php';
require_once TB_THEME_ROOT . '/library/DataBag.php';
require_once TB_THEME_ROOT . '/library/DbHelper.php';
require_once TB_THEME_ROOT . '/library/ViewSlot.php';
require_once TB_THEME_ROOT . '/library/Extension.php';
require_once TB_THEME_ROOT . '/library/Controller.php';
require_once TB_THEME_ROOT . '/library/FormHelper.php';
require_once TB_THEME_ROOT . '/library/WidgetsArea.php';
require_once TB_THEME_ROOT . '/library/ViewDataBag.php';
require_once TB_THEME_ROOT . '/library/OutputHandler.php';
require_once TB_THEME_ROOT . '/library/StyleBuilder.php';
require_once TB_THEME_ROOT . '/library/ColorSchemer.php';
require_once TB_THEME_ROOT . '/library/RequestHelper.php';
require_once TB_THEME_ROOT . '/library/SettingsModel.php';
require_once TB_THEME_ROOT . '/library/WidgetManager.php';
require_once TB_THEME_ROOT . '/library/ResourceLoader.php';
require_once TB_THEME_ROOT . '/library/ExtensionModel.php';
require_once TB_THEME_ROOT . '/library/DummyController.php';
require_once TB_THEME_ROOT . '/library/ExtensionPlugin.php';
require_once TB_THEME_ROOT . '/library/DbSettingsHelper.php';
require_once TB_THEME_ROOT . '/library/ImageManipulator.php';
require_once TB_THEME_ROOT . '/library/ExtensionPluginBootstrap.php';

class TB_Engine
{
    protected static $instances = array();

    /**
     * @var TB_DbSettingsHelper
     */
    protected static $dbSettingsHelper;

    /*
     * @var TB_DbHelper
     */
    protected static $dbHelper;

    /**
     * @var TB_WidgetManager
     */
    protected static $widgetManager;

    /**
     * @var TB_EnvHelper
     */
    protected static $envHelper;

    /**
     * @var TB_Cache
     */
    protected static $cache;

    /**
     * @var sfEventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var TB_Context
     */
    protected $context;

    /**
     * @var TB_SettingsModel
     */
    protected $builderSettingsModel;

    /**
     * @var TB_SettingsModel
     */
    protected $styleSettingsModel;

    /**
     * @var TB_SettingsModel
     */
    protected $themeSettingsModel;

    /**
     * @var Registry OpenCart registry
     */
    protected $registry;

    /**
     * @var TB_ResourceLoader
     */
    protected $resourceLoader;

    /**
     * @var TB_Extension
     */
    protected $themeExtension;

    /**
     * @var TB_DataBag
     */
    protected $routesBag;

    /**
     * @var TB_ViewDataBag
     */
    protected $themeData;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $theme_info;

    /**
     * @var array
     */
    protected $theme_config;

    /**
     * @var TB_Extension[]
     */
    protected $extensions = array();

    /**
     * @var TB_Extension[]
     */
    protected $notInstalledExtensions = array();

    /**
     * @var array
     */
    protected $oc_translations = array();

    protected function __construct(TB_Context $context, Registry $registry, array $config, array $theme_config, array $theme_info)
    {
        $this->registry      = $registry;
        $this->context       = $context;
        $this->config        = $config;
        $this->theme_config  = $theme_config;

        if (empty($theme_info)) {
            $theme_info = array(
                'id'          => self::getName(),
                'version'     => self::getVersion(),
                'name'        => $config['name'],
                'description' => $config['description']
            );
        }
        $theme_info['engine_version'] = self::getVersion();
        $this->theme_info = $theme_info;

        if (empty(self::$instances)) {
            self::$dbHelper         = new TB_DbHelper($registry->get('db'), DB_PREFIX);
            self::$dbSettingsHelper = new TB_DbSettingsHelper(self::$dbHelper);
            self::$widgetManager    = new TB_WidgetManager($this);
            self::$cache            = new TB_Cache($this->context->getTbCacheDir(), $config['default_cache'], 'BE');
            self::$envHelper        = TB_EnvHelper::getInstance($registry);
        }

        $this->eventDispatcher = new sfEventDispatcher();
        $this->resourceLoader  = new TB_ResourceLoader($context, $registry, $config);
        $this->themeData       = new TB_ViewDataBag($context, new TB_ViewSlot($this->eventDispatcher));
        $this->routesBag       = new TB_DataBag();

        $this->themeSettingsModel   = $this->getSettingsModel('theme');
        $this->builderSettingsModel = $this->getSettingsModel('builder');
        $this->styleSettingsModel   = $this->getSettingsModel('style');
    }

    /**
     * @param TB_Context $context
     *
     * @param Registry $registry
     * @param array $config
     * @param array $theme_config
     * @param array $theme_info
     * @return TB_Engine
     */
    public static function createInstance(TB_Context $context, Registry $registry, array $config, array $theme_config, array $theme_info)
    {
        $area = $context->getArea();
        if (!isset(self::$instances[$area])) {
            self::$instances[$area] = new self($context, $registry, $config, $theme_config, $theme_info);
        }

        return self::$instances[$area];
    }

    /**
     * @return TB_Engine
     * @throws Exception
     */
    public static function instance()
    {
        if (!self::hasInstance()) {
            throw new Exception('There is no engine instance');
        }

        return reset(self::$instances);
    }

    public static function hasInstance()
    {
        return !empty(self::$instances);
    }

    public static function ocConfig($key = null, $value = null)
    {
        if (null === $key) {
            return self::instance()->getOcConfig();
        }

        if (null === $value) {
            return self::instance()->getOcConfig()->get($key);
        }

        self::instance()->getOcConfig()->set($key, $value);
    }

    /**
     * @return Registry
     */
    public static function ocRegistry()
    {
        return self::instance()->getOcRegistry();
    }

    /**
     * @param null|int $store_id
     * @param null|string $settings_group
     * @return TB_SettingsModel
     */
    public function getSettingsModel($settings_group, $store_id = null)
    {
        static $models = array();

        if (null === $store_id) {
            $store_id = $this->context->getStoreId();
        }

        $hash = $settings_group . '_' . $store_id;

        if (!isset($models[$hash])) {
            $models[$hash] = new TB_SettingsModel(self::$dbSettingsHelper, $settings_group, $store_id);
        }

        return $models[$hash];
    }

    public function boot()
    {
        if (!empty($this->extensions)) {
            // Already booted
            return $this;
        }

        $resourceLoader = $this->resourceLoader;
        $this->initThemeData();
        $this->getWidgetManager()->registerCoreWidgets();

        $theme_extension_class = $resourceLoader->loadThemeExtensionClass();
        list($theme_name, $theme_class) = each($theme_extension_class);

        TB_ColorSchemer::$color_inheritance_error_type = $this->config['color_inheritance_error_type'];

        /** @var $themeExtension TB_Extension */
        $themeExtension = new $theme_class($this, $theme_name);
        $themeExtension->setThemeData($this->themeData);
        $themeExtension->loadDefaultTranslation();
        $themeExtension->init();
        $themeExtension->configure();

        $this->themeExtension = $themeExtension;

        $this->eventDispatcher->notify(new sfEvent($this, 'core:extensions_preconfigure', array('themeData' => $this->themeData)));

        $notInstalledExtensions = array();
        $installedExtensions = array();
        $extensions_classes = $resourceLoader->loadExtensionClasses();

        foreach ($extensions_classes as $name => $class) {
            /** @var $extension TB_Extension */
            $extension = new $class($this, $name);
            if ($this->context->getArea() == 'admin') {
                $extension->loadDefaultTranslation();
            }

            if (!$extension->canInstall()) {
                continue;
            }

            /** @var $extensionsModel Theme_ExtensionsModel */
            $extensionsModel = $themeExtension->getModel('extensions');
            if (!$extensionsModel->isExtensionInstalled($name) && !$extension->isCoreExtension()) {
                $notInstalledExtensions[$name] = $extension;
            } else {
                $installedExtensions[$extension->getInitOrder() . '-' . TB_Utils::genRandomString()] = $extension;
            }
        }

        $this->notInstalledExtensions = $notInstalledExtensions;
        ksort($installedExtensions);

        foreach ($installedExtensions as $extension) {
            $extension->setThemeData($this->themeData);
            $extension->configure();
            $event = new sfEvent($this, 'core:extension_postconfigure.' . $extension->getName(), array('extension' => $extension));
            $this->eventDispatcher->notify($event);
            $this->extensions[$extension->getName()] = $extension;
        }

        $this->eventDispatcher->notify(new sfEvent($this, 'core:extensions_postconfigure', array('themeData' => $this->themeData)));

        return $this;
    }

    protected function initThemeData()
    {
        $context = $this->context;

        $init_vars['root_dir']     = $context->getRootDir();
        $init_vars['theme_dir']    = $context->getThemeDir();
        $init_vars['current_url']  = $context->getRequestUrl();
        $init_vars['image_url']    = $context->getImageUrl();
        $init_vars['base_http']    = $context->getBaseHttp();
        $init_vars['base_https']   = $context->getBaseHttps();
        $init_vars['base_httpsIf'] = $context->getBaseHttpsIf();
        $init_vars['basename']     = $context->getBasename();
        $init_vars['store_id']     = $context->getStoreId();
        $init_vars['theme_id']     = $this->getThemeId();
        $init_vars['token']        = isset($this->getOcSession()->data['token']) ? $this->getOcSession()->data['token'] : null;
        $init_vars['isHTTPS']      = TB_RequestHelper::isRequestHTTPS();

        $init_vars['theme_catalog_resource_dir'] = $context->getCatalogResourceDir();
        $init_vars['theme_catalog_template_dir'] = $context->getCatalogTemplateDir();
        $init_vars['theme_area_template_dir']    = $context->getEngineAreaTemplateDir();

        $init_vars['theme_catalog_resource_url']   = $context->getThemeCatalogResourceUrl();
        $init_vars['theme_catalog_image_url']      = $context->getThemeCatalogImageUrl();
        $init_vars['theme_catalog_stylesheet_url'] = $context->getThemeCatalogStylesheetUrl();
        $init_vars['theme_catalog_javascript_url'] = $context->getThemeCatalogJavascriptUrl();
        $init_vars['theme_engine_root_url']        = $context->getThemeRootUrl();

        $init_vars['theme_admin_resource_url']   = $context->getThemeAdminResourceUrl();
        $init_vars['theme_admin_image_url']      = $context->getThemeAdminImageUrl();
        $init_vars['theme_admin_font_url']       = $context->getThemeAdminFontUrl();
        $init_vars['theme_admin_stylesheet_url'] = $context->getThemeAdminStylesheetUrl();
        $init_vars['theme_admin_javascript_url'] = $context->getThemeAdminJavascriptUrl();
        $init_vars['theme_admin_javascript_relative_url'] = $context->getThemeAdminJavascriptUrl(true);

        $this->themeData->importVars($init_vars);
    }

    public function updateThemeDataAfterRouting(TB_Extension $extension)
    {
        static $booted = false;

        if ($booted) {
            throw new Exception('The theme data can be updated only once after routing');
        }

        if ($extension->isThemeExtension()) {
            $init_vars['extension_catalog_resource_url'] = $this->context->getThemeCatalogResourceUrl();
            $init_vars['extension_admin_resource_url']   = $this->context->getThemeAdminResourceUrl();
        } else {
            $extension_url = $this->context->getExtensionsUrl() . $extension->getName() . '/';
            $init_vars['extension_catalog_resource_url'] = $extension_url . 'catalog/view/';
            $init_vars['extension_admin_resource_url']   = $extension_url . 'admin/view/';
        }

        $booted = true;

        $this->themeData->importVars($init_vars);
    }

    public function getThemes($flat = true)
    {
        static $themes;

        if (null !== $themes) {
            return $themes;
        }

        $current_theme_id = !empty($this->themeData->theme_settings['current_theme']) ? $this->themeData->theme_settings['current_theme'] : $this->getThemeId();

        $themes = array();
        $child_themes = array();

        $theme_folders = sfFinder::type('dir')
            ->not_name('BurnEngine')
            ->maxdepth(1)
            ->sort_by_name()
            ->in($this->getContext()->getThemesDir());

        foreach ($theme_folders as $theme_folder) {
            if (!is_file($theme_folder . '/data.bin') || !is_file($theme_folder . '/info.ini') || !is_file($theme_folder . '/preview.png')) {
                continue;
            }

            $info = parse_ini_file($theme_folder . '/info.ini');

            if (false === $info) {
                throw new Exception('The info file is invalid: ' . $theme_folder . '/info.ini');
            }

            if (!$data = gzuncompress(base64_decode(file_get_contents($theme_folder . '/data.bin')))) {
                continue;
            }

            $theme_id = basename($theme_folder);

            $data = unserialize($data);
            if (!is_array($data) || empty($data['theme_id']) || $data['theme_id'] != $theme_id) {
                continue;
            }

            $theme = array(
                'id'          => $theme_id,
                'name'        => $info['name'],
                'description' => $info['description'],
                'version'     => $info['version'],
                'parent'      => isset($info['parent']) ? $info['parent'] : null,
                'preview'     => base64_encode(file_get_contents($theme_folder . '/preview.png')),
                'applied'     => $current_theme_id == $theme_id
            );

            if ($flat || !isset($info['parent'])) {
                $themes[$theme_id] = $theme;
            } else {
                $child_themes[$info['parent']][] = $theme;
            }
        }

        if (!$flat) {
            foreach ($themes as $key => $theme) {
                $themes[$key]['children'] = isset($child_themes[$key]) ? $child_themes[$key] : array();
            }
        }

        return $themes;
    }

    /**
     * @param $key
     * @param null $callback
     * @param array $args
     * @param null $expire
     * @return mixed|null
     * @throws Exception
     */
    public function getCacheVar($key, $callback = null, array $args = array(), $expire = null, $eager = false, $cache_enabled = null)
    {
        if (null === $cache_enabled) {
            $cache_enabled = $this->themeData['system']['cache_enabled'];
        }

        if (!$eager && !$cache_enabled) {
            return null !== $callback ? call_user_func_array($callback, $args) : null;
        }

        $result = self::$cache->get($key);
        if (empty($result)) {

            if (null === $callback) {
                return null;
            }

            if (is_callable($callback, false, $callable_name)) {
                $result = call_user_func_array($callback, $args);
                $this->setCacheVar($key, $result, $expire, false, $cache_enabled);
            } else {
                throw new Exception('Invalid callback supplied: ' . $callable_name);
            }
        }

        return $result;
    }

    public function setCacheVar($key, $var, $expire = null, $keep_timestamp = false, $cache_enabled = null)
    {
        if (null === $cache_enabled) {
            $cache_enabled = $this->themeData['system']['cache_enabled'];
        }

        if ($cache_enabled) {
            self::$cache->set($key, $var, $expire, $keep_timestamp);
        }
    }

    public function wipeAllCache($pattern = '*', $brace = false)
    {
        $this->wipeVarsCache($pattern, $brace);
        $this->wipeStylesCache($pattern, $brace);
    }

    public function wipeVarsCache($pattern = '*', $brace = false, $path = null, $prefix = true)
    {
        $flags = GLOB_NOSORT;
        if ($brace) {
            $flags |= GLOB_BRACE;
        }

        if (null === $path) {
            $path = $this->context->getTbCacheDir() .'/';
        }

        if ($prefix) {
            $pattern = self::$cache->getPrefix() . $pattern;
        }

        is_dir($path) && ($files = glob($path . 'cache.' . $pattern, $flags)) && array_map('unlink', array_filter($files, 'is_file'));
    }

    public function wipeStylesCache($pattern = '*', $brace = false)
    {
        $flags = GLOB_NOSORT;
        if ($brace) {
            $flags |= GLOB_BRACE;
        }

        $path = $this->context->getImageDir() . '/cache/tb/';
        is_dir($path) && ($files = glob($path . $pattern, $flags)) && array_map('unlink', array_filter($files, 'is_file'));
    }

    /**
     * @return TB_ViewDataBag
     */
    public function getThemeData()
    {
        return $this->themeData;
    }

    /**
     * @param null|string $key
     *
     * @return mixed
     */
    public function getConfig($key = null)
    {
        if (null !== $key) {
            return $this->config[$key];
        }

        return $this->config;
    }

    /**
     * @param null|string $key
     * @param null|mixed $default
     *
     * @return mixed
     */
    public function getThemeConfig($key = null, $default = null)
    {
        if (null !== $key) {
            return isset($this->theme_config[$key]) ? $this->theme_config[$key] : $default;
        }

        return $this->theme_config;
    }

    /**
     * @return TB_Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return TB_DbHelper
     */
    public function getDbHelper()
    {
        return self::$dbHelper;
    }

    public function getResourceLoader()
    {
        return $this->resourceLoader;
    }

    /**
     * @return TB_SettingsModel
     */
    public function getThemeSettingsModel()
    {
        return $this->themeSettingsModel;
    }

    /**
     * @return TB_SettingsModel
     */
    public function getBuilderSettingsModel()
    {
        return $this->builderSettingsModel;
    }

    /**
     * @return TB_SettingsModel
     */
    public function getStyleSettingsModel()
    {
        return $this->styleSettingsModel;
    }

    /**
     * @return TB_WidgetManager
     */
    public function getWidgetManager()
    {
        return self::$widgetManager;
    }

    /**
     * @return TB_DbSettingsHelper
     */
    public function getDbSettingsHelper($table = 'burnengine_setting')
    {
        static $instances = array();

        if ($table == 'burnengine_setting') {
            return self::$dbSettingsHelper;
        }

        if (!isset($instances[$table])) {
            $use_serialize = true;
            if ($table == 'setting' && version_compare(VERSION, '2.1.0.0', '>=')) {
                $use_serialize = false;
            }

            $instances[$table] = new TB_DbSettingsHelper(self::$dbHelper, $table, $use_serialize);
            if ($this->gteOc2()) {
                $instances[$table]->setGroupFieldName('code');
            }
        }

        return $instances[$table];
    }

    public function getExtensions()
    {
        return $this->extensions;
    }

    public function getCoreExtensions()
    {
        $result = array();

        foreach ($this->extensions as $extension) {
            if ($extension->isCoreExtension()) {
                $result[] = $extension;
            }
        }

        return $result;
    }

    /**
     * @param $name
     * @throws Exception
     * @return TB_Extension
     */
    public function getExtension($name)
    {
        if ($name == $this->getName() || $name == 'theme') {
            return $this->themeExtension;
        }

        if (!isset($this->extensions[$name])) {
            throw new Exception('The following extension does not exists: ' . $name);
        }

        return $this->extensions[$name];
    }

    /**
     * @param $name
     * @return TB_ExtensionModel
     * @throws Exception
     */
    public function getExtensionModel($name)
    {
        $parts = explode('/', $name);

        if (2 != count($parts)) {
            $parts[1] = 'default';
        }

        return $this->getExtension($parts[0])->getModel($parts[1]);
    }

    /**
     * @return TB_Extension|Theme_Admin_Extension|Theme_Catalog_Extension
     */
    public function getThemeExtension()
    {
        return $this->themeExtension;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function isExtensionInstalled($name)
    {
        return isset($this->extensions[$name]);
    }

    /**
     * @return bool
     */
    public function isThemeExtensionLoaded()
    {
        return null !== $this->themeExtension;
    }

    public function getNotInstalledExtensions()
    {
        return $this->notInstalledExtensions;
    }

    /**
     * @return Registry
     */
    public function getOcRegistry()
    {
        return $this->registry;
    }

    /**
     * @return Cache
     */
    public function getOcCache()
    {
        return $this->registry->get('cache');
    }

    /**
     * @return Session
     */
    public function getOcSession()
    {
        return $this->registry->get('session');
    }

    /**
     * @return Request
     */
    public function getOcRequest()
    {
        return $this->registry->get('request');
    }

    /**
     * @return Response
     */
    public function getOcResponse()
    {
        return $this->registry->get('response');
    }

    /**
     * @param string|null $key
     * @return Config|mixed
     */
    public function getOcConfig($key = null)
    {
        if (null !== $key) {
            return $this->registry->get('config')->get($key);
        }

        return $this->registry->get('config');
    }

    /**
     * @return Event
     */
    public function getOcEvent()
    {
        return $this->registry->get('event');
    }

    /**
     * @return \Cart\Cart|Cart
     */
    public function getOcCart()
    {
        return $this->registry->get('cart');
    }

    /**
     * @return \Cart\Customer|Customer
     */
    public function getOcCustomer()
    {
        return $this->registry->get('customer');
    }

    /**
     * @return \Cart\Affiliate|Affiliate
     */
    public function getOcAffiliate()
    {
        return $this->registry->get('affiliate');
    }

    /**
     * @return \Cart\Currency|Currency
     */
    public function getOcCurrency()
    {
        return $this->registry->get('currency');
    }

    /**
     * @return \Cart\User|User
     */
    public function getOcUser()
    {
        return $this->registry->get('user');
    }

    /**
     * @return \Cart\Tax|Tax
     */
    public function getOcTax()
    {
        return $this->registry->get('tax');
    }

    /**
     * @return ModelToolImage
     */
    public function getOcToolImage()
    {
        return $this->getOcModel('tool/image');
    }

    /**
     * @return Url
     */
    public function getOcUrl()
    {
        return $this->registry->get('url');
    }

    /**
     * @return DB
     */
    public function getOcDb()
    {
        return $this->registry->get('db');
    }

    /**
     * @param $name
     * @return Model
     */
    public function getOcModel($name)
    {
        $model_full_name = 'model_' . str_replace('/', '_', $name);
        if (!$this->registry->has($model_full_name)) {
            $this->registry->get('load')->model($name);
        }

        return $this->registry->get($model_full_name);
    }

    /**
     * @return Document
     */
    public function getOcDocument()
    {
        return $this->registry->get('document');
    }

    public function loadOcTranslation($filename = '')
    {
        $alternative_filename = null;

        if (empty($filename)) {
            $filename = $this->context->getCurrentLanguage('filename');

            if ($this->gteOc22()) {
                $alternative_filename = $this->context->getEngineConfig('fallback_language');
            } else
            if ($this->gteOc2()) {
                $alternative_filename = $this->context->getCurrentLanguage('directory');
            }
        }

        if (isset($this->oc_translations['oc_' . $filename])) {
            return $this->oc_translations['oc_' . $filename];
        }

        $translation = $this->resourceLoader->loadOcTranslation($filename, $alternative_filename);
        $this->oc_translations['oc_' . $filename] = $translation;

        return $translation;
    }

    public function getAllLanguages($cache = true)
    {
        $languages = self::$envHelper->getLanguages($cache);
        $this->resolveLanguagesFlags($languages);

        return $languages;
    }

    public function getEnabledLanguages($cache = true)
    {
        static $languages_cache;

        if (null !== $languages_cache && $cache) {
            return $languages_cache;
        }

        $languages = self::$envHelper->getLanguages($cache);

        foreach ($languages as $code => $language) {
            if ($language['status'] == 0) {
                unset($languages[ $code ]);
            }
        }

        $this->resolveLanguagesFlags($languages);

        if ($cache) {
            $languages_cache = $languages;
        }

        return $languages;
    }

    protected function resolveLanguagesFlags(&$languages)
    {
        $flags_dir = $this->context->getCatalogResourceDir() . '/image/flags';
        $flags_url = $this->context->getThemeCatalogResourceUrl() . 'image/flags/';

        foreach ($languages as $code => &$language) {

            $language['url'] = $flags_url;

            if (empty($language['image']) || !is_file($flags_dir . '/' . $language['image'])) {
                $flags_map = $this->getFlagsMap();
                $found_code = false;

                if (isset($flags_map[$language['code']]) && is_file($flags_dir . '/' . $flags_map[$language['code']] . '.png')) {
                    $language['image'] = $flags_map[$language['code']] . '.png';
                    $found_code = true;
                } else
                    if (!empty($language['locale'])) {
                        foreach (explode(',', $language['locale']) as $code) {
                            if (false === strpos('_', $code) && isset($flags_map[$code]) && is_file($flags_dir . '/' . $flags_map[$code] . '.png')) {
                                $language['image'] = $flags_map[$code] . '.png';
                                $found_code = true;

                                break;
                            }
                        }
                    }

                if ($found_code) {
                    continue;
                }

                if (!empty($language['image']) && is_file($this->context->getImageDir() . '/flags/') . $language['image']) {
                    $language['url'] = $this->context->getImageUrl() . 'flags/';

                    continue;
                } else {
                    $language['image'] = $language['code'] . '.png';
                }

                if (is_file($flags_dir . '/' . $language['image'])) {
                    continue;
                }

                if (!is_file($this->context->getImageDir() . '/flags/' . $language['image'])) {
                    $language['url'] = $this->context->getCatalogUrl() . 'language/' . $language['code']  . '/';
                } else {
                    $language['url'] = $this->context->getImageUrl() .'flags/';
                }
            }
        }

        unset($language);

        foreach ($languages as $code => &$language) {
            $language['image_url'] = $language['url'] . $language['image'];
        }
    }

    protected function getFlagsMap()
    {
        static $map;

        if (null === $map) {
            $map = require $this->context->getConfigDir() . '/data/flags_map.php';
        }

        return $map;
    }

    public function getLanguageById($id)
    {
        foreach ($this->getEnabledLanguages() as $language) {
            if ($language['language_id'] == $id) {
                return $language;
            }
        }

        return false;
    }

    public function getLanguageByCode($code)
    {
        $languages = $this->getEnabledLanguages();

        if (isset($languages[$code])) {
            return $languages[$code];
        }

        return false;
    }

    public function getDefaultCatalogLanguage($key = null)
    {
        static $default_language = null;

        if (null == $default_language) {
            $default_language_code = $this->getDbSettingsHelper('setting')->getKey('config_language', $this->context->getStoreId(), 'config');
            foreach ($this->getEnabledLanguages() as $language) {
                if ($language['code'] == $default_language_code) {
                    $default_language = $language;
                    break;
                }
            };

            if (null == $default_language) {
                foreach ($this->getEnabledLanguages() as $language) {
                    if ($language['code'] == $this->getOcConfig()->get('config_language')) {
                        $default_language = $language;
                        break;
                    }
                };
            }
        }

        return $key !== null ? $default_language[$key]: $default_language;
    }

    public function getLocaleMap()
    {
        static $map;

        if (null === $map) {
            $map = require $this->context->getConfigDir() . '/data/locale_map.php';
        }

        return $map;
    }

    /**
     * @return Theme_Catalog_DefaultModel| Theme_Admin_DefaultModel
     */
    public function getThemeModel()
    {
        return $this->getThemeExtension()->getThemeModel();
    }

    public function getCurrentStoreId()
    {
        return $this->context->getStoreId();
    }

    /**
     * @return sfEventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @return TB_DataBag
     */
    public function getRoutesBag()
    {
        return $this->routesBag;
    }

    public function gteOc2()
    {
        return version_compare(VERSION, '2.0.0.0') >= 0;
    }

    public function gteOc21()
    {
        return version_compare(VERSION, '2.1.0.0', '>=');
    }

    public function gteOc22()
    {
        return version_compare(VERSION, '2.2.0.0', '>=');
    }

    public function gteOc23()
    {
        return version_compare(VERSION, '2.3.0.0', '>=');
    }

    public function fbLog($msg)
    {
        if ($this->context->isDebug()) {
            fb($msg);
        }
    }

    public function isThemeEnabled()
    {
        return $this->getConfigTheme() == self::getName();
    }

    public function getConfigTheme()
    {
        return $this->getOcConfig()->get('config_' . ($this->gteOc22() ? 'theme' : 'template'));
    }

    /**
     * Returns the engine version
     *
     * @return string
     */
    public static function getVersion()
    {
        return '1.2.6';
    }

    /**
     * Returns the theme machine name.
     * !!! Do not change it, it may prevent extensions from working !!!
     *
     * @return string
     */
    public static function getName()
    {
        static $name;

        if (null === $name) {
            $name = base64_decode('QnVybkVuZ2luZQ==');
        }

        return $name;
    }

    /**
     * Returns the id of the current theme.
     *
     * @return string
     */
    public function getThemeId()
    {
        return $this->getThemeInfo('id');
    }

    public function getThemeInfo($key = null)
    {
        if (null == $key) {
            return $this->theme_info;
        }

        return $this->theme_info[$key];
    }
}
