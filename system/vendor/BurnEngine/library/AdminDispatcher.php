<?php

require_once TB_THEME_ROOT . '/library/Engine.php';
require_once TB_THEME_ROOT . '/library/AdminController.php';
require_once TB_THEME_ROOT . '/library/AdminExtension.php';
require_once TB_THEME_ROOT . '/library/AdminUrl.php';
require_once TB_THEME_ROOT . '/library/AdminDataPluginInterface.php';

class TB_AdminDispatcher
{
    /**
     * @var TB_AdminDispatcher
     */
    protected static $instance;

    /**
     * @var TB_Engine
     */
    private static $engine;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var string
     */
    private $basename;

    protected function __construct($registry, $basename, $store_id)
    {
        $this->registry = $registry;
        $this->basename = $basename;
        $this->initEngine($store_id);
    }

    public static function getInstance(Registry $registry, $basename, $store_id)
    {
        if (!isset(self::$instance)) {
            if (null === $registry) {
                throw new InvalidArgumentException('You must supply Registry instance');
            }
            self::$instance = new self($registry, $basename, $store_id);
        }

        return self::$instance;
    }

    public function getEngine()
    {
        return self::$engine;
    }

    public function dispatch($extension_name, $controller_name, $action)
    {
        if (TB_RequestHelper::isRequestHTTPS() && 0 !== strpos(self::$engine->getContext()->getConfigSsl(), 'https') && !TB_RequestHelper::isAjaxRequest()) {
            if (!self::$engine->gteOc23() || 0 !== strpos(HTTPS_SERVER, 'https')) {
                $_SESSION['redirect_to_http'] = 1;
                header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit;
            }
        }

        if (!TB_RequestHelper::isRequestHTTPS() && $this->registry->get('config')->get('config_secure') && self::$engine->getConfig('admin_redirect_https') && 0 === strpos(self::$engine->getContext()->getConfigSsl(), 'https')) {
            header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit;
        }

        $extension = self::$engine->getExtension($extension_name);
        self::$engine->updateThemeDataAfterRouting($extension);

        $event = new sfEvent($this, 'core:admin_dispatch', array(
            'controller_name' => $controller_name,
            'action_name'     => $action,
            'extension_name'  => $extension_name
        ));
        self::$engine->getEventDispatcher()->notify($event);

        $controller = $extension->getController($controller_name);

        if (method_exists($controller, 'init')) {
            call_user_func(array($controller, 'init'));
        }

        if (method_exists($controller, $action)) {
            try {
                call_user_func(array($controller, $action));
            } catch (Exception $e) {
                $controller->renderString(renderException($e));
            }
        } else {
            throw new Exception('Controller action not found: ' . $controller_name . '->' . $action . ' for the extension: ' . $extension_name);
        }
    }

    protected function initEngine($store_id)
    {
        if (!$this->registry->get('url')) {
            // Some multistore or cli configurations use subfolder with custom index.php in order to use the opencart structure. They do not initialize some classes, used by BurnEngine.
            return;
        }

        /** @var Config $config */
        $config = $this->registry->get('config');

        if ($store_id != 0) {
            $query = $this->registry->get('db')->query('SELECT * FROM ' . DB_PREFIX . 'setting WHERE store_id = ' . $store_id);

            foreach ($query->rows as $setting) {
                if (!$setting['serialized']) {
                    $config->set($setting['key'], $setting['value']);
                } else {
                    $config->set($setting['key'], version_compare(VERSION, '2.1.0.0', '>=') ? json_decode($setting['value'], true) : unserialize($setting['value']));
                }
            }
        }

        $envHelper = TB_EnvHelper::getInstance($this->registry);

        $engine_config = $envHelper->getEngineConfig($this->basename);
        $current_lang  = $envHelper->getCurrentLanguage();

        $theme_info = (array) $config->get(TB_Engine::getName() . '_theme');

        if ($theme_info && $theme_info['id'] != $this->basename) {
            $theme_config = $envHelper->getThemeConfig($theme_info['id']);
        } else {
            // The BurnEngine module may have been not installed or no default theme was selected during install
            $theme_config = array();
        }

        if ($theme_info) {
            $theme_ini_file = DIR_SYSTEM . 'vendor/' . TB_BASENAME . '/themes/' . $theme_info['id'] . '/info.ini';
            if (is_file($theme_ini_file)) {
                $theme_ini = parse_ini_file($theme_ini_file);
                if (false !== $theme_ini) {
                    if (isset($theme_ini['version'])) {
                        $theme_info['version'] = $theme_ini['version'];
                    }
                    if (isset($theme_ini['description'])) {
                        $theme_info['description'] = $theme_ini['description'];
                    }
                }
            }
        }
        
        $context = new TB_Context($this->registry, $this->basename, $store_id, 'admin', $current_lang, $engine_config, $theme_info);

        self::$engine = TB_Engine::createInstance($context, $this->registry, $engine_config, $theme_config, $theme_info);

        if (!$theme_info) {
            self::$engine->getThemeSettingsModel()->setScopeSettings(self::$engine->getThemeId(), array());
        }

        $config->set('config_store_id', $store_id);

        self::$engine->boot();

        $this->registry->set('tbEngine', self::$engine);
    }
}