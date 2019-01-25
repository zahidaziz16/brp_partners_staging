<?php

if (false && defined('TB_STORE_ID') && is_file(TB_THEME_ROOT . '/config/data/class_cache_' . TB_STORE_ID . '.php')) {
    define('TB_CLASS_CACHE', 1);
    require TB_THEME_ROOT . '/config/data/class_cache_' . TB_STORE_ID . '.php';
} else {
    require TB_THEME_ROOT . '/library/EnvHelper.php';
    require TB_THEME_ROOT . '/library/Utils.php';
    require TB_THEME_ROOT . '/library/Context.php';
    require TB_THEME_ROOT . '/library/CatalogDispatcher.php';
}

final class TB_Boot
{
    const ERROR_THEME_INFO = 1;
    const ERROR_SETTINGS   = 2;

    /**
     * @param Registry $registry
     * @param $basename
     * @param $store_id
     *
     * @return TB_Engine|string
     * @throws Exception
     */
    public static function initEngine(Registry $registry, $basename, $store_id)
    {
        /** @var Config $config */
        $config = $registry->get('config');
        $theme_info = (array) $config->get($basename . '_theme');

        if (!$theme_info) {
            return self::ERROR_THEME_INFO;
        }

        $engine_config = TB_EnvHelper::getInstance($registry)->getEngineConfig($basename);
        $current_lang  = TB_EnvHelper::getInstance($registry)->getCurrentLanguage(null, $engine_config['fallback_language']);

        self::setFirePHP($engine_config);

        $context      = new TB_Context($registry, $basename, $store_id, 'catalog', $current_lang, $engine_config, $theme_info);
        $theme_config = TB_EnvHelper::getInstance($registry)->getThemeConfig($theme_info['id']);
        $engine       = TB_Engine::createInstance($context, $registry, $engine_config, $theme_config, $theme_info)->boot();

        if (!$engine->getThemeModel()->getSettings()) {
            return self::ERROR_SETTINGS;
        }

        $registry->set('tbEngine', $engine);

        return $engine;
    }


    public static function dispatchRequest(TB_Engine $engine)
    {
        $dispatcher = new TB_CatalogDispatcher($engine);
        $dispatcher->dispatch();
    }

    protected static function setFirePHP($engine_config)
    {
        if ($engine_config['firephp']) {
            if (!defined('TB_CLASS_CACHE')) {
                require_once TB_THEME_ROOT . '/library/vendor/FirePHP.php';
            }

            FB::setOptions(array(
                'maxObjectDepth'      => 7,
                'maxArrayDepth'       => 7,
                'maxDepth'            => 10,
                'useNativeJsonEncode' => true,
                'includeLineNumbers'  => true
            ));
        } else
        if (!function_exists('fb')) {
            function fb($arg) {
                return $arg;
            }
        }
    }
}