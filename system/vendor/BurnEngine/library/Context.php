<?php

class TB_Context
{
    /**
     * @var string
     */
    protected $basename;

    /*
     * @var int
     */
    protected $store_id;

    /**
     * @var string
     */
    protected $store_url;

    /**
     * @var string
     */
    protected $area;

    /**
     * @var array
     */
    protected $current_language;

    /** @var  string */
    protected $language_direction;

    /**
     * @var array
     */
    protected $engine_config;

    /**
     * @var array
     */
    protected $theme_info;

    protected $base_http;
    protected $base_https;
    protected $config_ssl;
    protected $base_httpsIf;
    protected $request_url;
    protected $host;
    protected $relative_url;

    public function __construct(Registry $registry, $basename, $store_id, $area, array $current_language, array $engine_config, array $theme_info)
    {
        $this->basename         = $basename;
        $this->store_id         = $store_id;
        $this->area             = $area;
        $this->current_language = $current_language;
        $this->engine_config    = $engine_config;
        $this->theme_info       = $theme_info;
        
        $this->language_direction = $registry->get('language')->get('direction');

        /** @var $config Config */
        $config = $registry->get('config');

        if ($area == 'admin') {
            if (defined('HTTP_SERVER')) {
                $config_url = $config_ssl = dirname(HTTP_SERVER) . '/';
            } else {
                $config_url = $config_ssl = HTTP_CATALOG;
            }

            if ($config->get('config_secure') || (TB_RequestHelper::isRequestHTTPS() && !empty($_SESSION['redirect_to_http']))) {
                unset($_SESSION['redirect_to_http']);

                if (defined('HTTPS_SERVER')) {
                    $config_ssl = dirname(HTTPS_SERVER) . '/';
                } else
                if (defined('HTTP_SERVER')) {
                    $config_ssl = str_replace('http://', 'https://', dirname(HTTP_SERVER)) . '/';
                } else {
                    $config_ssl = defined('HTTPS_CATALOG') ? HTTPS_CATALOG : str_replace('http://', 'https://', HTTP_CATALOG);
                }
            }
        } else {
            $config_url = $config->get('config_url');
            $config_ssl = $config->get('config_ssl') ? $config->get('config_ssl') : $config_url;
        }

        $this->config_ssl = $config_ssl;

        $this->host = TB_Utils::getHost();
        if (empty($this->host)) {
            $this->host = $config_url;
        }
        $this->host = rtrim($this->host, '/');

        $this->base_http = TB_Utils::modifyBase($config_url, $this->host);

        if (TB_Utils::compareUrlHostsEqual($config_url, $config_ssl)) {
            $this->base_https =  TB_Utils::modifyBase($config_ssl, $this->host, true);
        } else {
            $this->base_https =  $config_ssl;
        }

        if (TB_RequestHelper::isRequestHTTPS()) {
            $this->request_url = "https://" . $this->host . $_SERVER['REQUEST_URI'];
            $this->base_httpsIf = $this->base_https;
        } else {
            $this->request_url = "http://" . $this->host . $_SERVER['REQUEST_URI'];
            $this->base_httpsIf = $this->base_http;
        }

        $relative = trim(substr($this->base_http, stripos($this->base_http, $this->host) + strlen($this->host)), '/');
        $this->relative_url = !empty($relative) ? '/' . $relative . '/' : '/';

        $this->store_url = $config->get('config_url') ? $config->get('config_url') : HTTP_CATALOG;
    }

    public function isDebug()
    {
        static $debug = null;

        if (null === $debug) {
            $debug = TB_Utils::parseDebug($this->engine_config['debug']);
        }

        return $debug;
    }

    public function getBasename()
    {
        return $this->basename;
    }

    /**
     * Returns the id of the current theme.
     *
     * @return string
     */
    public function getThemeId()
    {
        $theme_id = $this->getThemeInfo('id');

        if (null === $theme_id) {
            return $this->basename;
        }

        return $theme_id;
    }

    public function getThemeInfo($key = null)
    {
        if (null == $key) {
            return $this->theme_info;
        }

        if (isset($this->theme_info[$key])) {
            return $this->theme_info[$key];
        }

        return null;
    }

    /**
     * @param null|string $key
     *
     * @return mixed
     */
    public function getEngineConfig($key = null)
    {
        if (null !== $key) {
            return $this->engine_config[$key];
        }

        return $this->engine_config;
    }

    public function getStoreId()
    {
        return $this->store_id;
    }

    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param null $key
     * @return mixed
     */
    public function getCurrentLanguage($key = null)
    {
        if (null !== $key) {
            return $this->current_language[$key];
        }

        return $this->current_language;
    }

    public function setCurrentLanguage(array $language)
    {
        $this->current_language = $language;
    }

    public function getLanguageDirection()
    {
        return $this->language_direction;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getRelativeUrl()
    {
        return $this->relative_url;
    }

    public function getAreaRelativeUrl()
    {
        if ($this->area == 'admin') {
            return $this->relative_url . rtrim($this->engine_config['admin_folder'], '/') . '/';
        }

        return $this->relative_url;
    }

    public function getRequestUrl()
    {
        return $this->request_url;
    }

    public function getCurrentBaseUrl()
    {
        if ($url = parse_url($this->request_url)) {
            return sprintf('%s://%s%s', $url['scheme'], $url['host'], isset($url['path']) ? str_replace('index.php' , '', $url['path']) : '');
        } else {
            return $this->request_url;
        }
    }

    public function getBaseHttp()
    {
        return $this->base_http;
    }

    public function getBaseHttps()
    {
        return $this->base_https;
    }

    public function getConfigSsl()
    {
        return $this->config_ssl;
    }

    /**
     * Returns the base ssl url only if the request is secure
     *
     * @return string
     */
    public function getBaseHttpsIf()
    {
        return $this->base_httpsIf;
    }

    public function getEngineRootUrl($relative = false)
    {
        return (!$relative ? $this->base_httpsIf : $this->relative_url) . 'system/vendor/' . $this->basename . '/';
    }

    public function getThemesRootUrl($relative = false)
    {
        return $this->getEngineRootUrl($relative) . 'themes/';
    }

    public function getThemeRootUrl($relative = false)
    {
        return $this->getThemesRootUrl($relative) . $this->getThemeId() . '/';
    }

    public function getThemeAdminResourceUrl($relative = false)
    {
        return $this->getEngineRootUrl($relative) . $this->engine_config['admin_folder'] . '/view/';
    }

    public function getThemeAdminImageUrl($relative = false)
    {
        return $this->getThemeAdminResourceUrl($relative) . 'images/';
    }

    public function getThemeAdminStylesheetUrl($relative = false)
    {
        return $this->getThemeAdminResourceUrl($relative) . 'stylesheet/';
    }

    public function getThemeAdminFontUrl($relative = false)
    {
        return $this->getThemeAdminResourceUrl($relative) . 'font/';
    }

    public function getThemeAdminJavascriptUrl($relative = false)
    {
        return $this->getThemeAdminResourceUrl($relative) . 'javascript/';
    }

    public function getCatalogUrl($relative = false)
    {
        return (!$relative ? $this->base_httpsIf : $this->relative_url) . 'catalog/';
    }

    public function getThemeCatalogResourceUrl($relative = false)
    {
        return $this->getCatalogUrl($relative) . 'view/theme/' . $this->basename . '/';
    }

    public function getThemeCatalogImageUrl($relative = false)
    {
        return $this->getThemeCatalogResourceUrl($relative) . 'image/';
    }

    public function getThemeCatalogStylesheetUrl($relative = false)
    {
        return $this->getThemeCatalogResourceUrl($relative) . 'stylesheet/';
    }

    public function getThemeCatalogFontUrl($relative = false)
    {
        return $this->getThemeCatalogResourceUrl($relative) . 'font/';
    }

    public function getThemeCatalogJavascriptUrl($relative = false)
    {
        return $this->getThemeCatalogResourceUrl($relative) . 'javascript/';
    }

    public function getExtensionsUrl($relative = false)
    {
        return $this->getEngineRootUrl($relative) . 'extension/';
    }

    public function getEngineDir()
    {
        return DIR_SYSTEM . 'vendor/' . $this->basename;
    }

    public function getThemesDir()
    {
        return $this->getEngineDir() . '/themes';
    }

    public function getThemeDir()
    {
        return $this->getThemesDir() . '/' . $this->getThemeId();
    }

    public function getEngineAreaDir()
    {
        if ($this->area == 'admin') {
            return $this->getEngineDir() . '/admin';
        } else {
            return $this->getEngineDir() . '/catalog';
        }
    }

    public function getEngineAreaTemplateDir()
    {
        return $this->getEngineAreaDir() . '/view/template';
    }

    public function getExtensionDir()
    {
        return $this->getEngineDir(). '/extension';
    }

    public function getConfigDir()
    {
        return $this->getEngineDir() . '/config';
    }

    public function getRootDir()
    {
        return dirname($this->getAreaDir());
    }

    public function getAreaDir()
    {
        if ($this->area == 'admin') {
            return $this->getAdminDir();
        } else {
            return $this->getCatalogDir();
        }
    }

    public function getCatalogDir()
    {
        if ($this->area == 'admin') {
            return rtrim(DIR_CATALOG, '/');
        }

        return rtrim(DIR_APPLICATION, '/');
    }

    public function getCacheDir()
    {
        return rtrim(DIR_CACHE, '/');
    }

    public function getTbCacheDir()
    {
        return $this->getCacheDir() . '/tb';
    }

    public function getImageDir()
    {
        return rtrim(DIR_IMAGE, '/');
    }

    public function getCatalogResourceDir()
    {
        return $this->getCatalogDir() . '/view/theme/' .  $this->basename;
    }

    public function getCatalogTemplateDir()
    {
        return $this->getCatalogResourceDir() . '/template';
    }

    public function getCatalogLanguageDir()
    {
        return $this->getCatalogDir() . '/language';
    }

    public function getAdminDir()
    {
        if ($this->area == 'admin') {
            return rtrim(DIR_APPLICATION, '/');
        }

        return dirname(DIR_APPLICATION) . '/' . $this->engine_config['admin_folder'];
    }

    public function getWidgetsDir()
    {
        return $this->getEngineDir() . '/widget';
    }

    public function getImageUrl()
    {
        if (!$this->engine_config['image_serve_domain']) {
            return $this->base_httpsIf . 'image/';
        }

        return 'http:' . (TB_RequestHelper::isRequestHTTPS() ? 's' : '') . '//' . $this->engine_config['image_serve_domain'] . 'image/';
    }

    public function getAdminBaseUrl()
    {
        return $this->base_httpsIf . $this->engine_config['admin_folder'] . '/';
    }

    public function getAdminJavascriptUrl()
    {
        return $this->getAdminBaseUrl() . 'view/javascript/';
    }

    public function getStoreUrl()
    {
        return $this->store_url;
    }

    public function getOcVersion()
    {
        return VERSION;
    }
}
