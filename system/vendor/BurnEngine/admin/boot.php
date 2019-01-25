<?php

!defined('TB_BASENAME')   && define('TB_BASENAME',   basename(dirname(dirname(__FILE__))));
!defined('TB_THEME_ROOT') && define('TB_THEME_ROOT', DIR_SYSTEM . 'vendor/' . TB_BASENAME);

require_once TB_THEME_ROOT . '/library/EnvHelper.php';
require_once TB_THEME_ROOT . '/library/Utils.php';
require_once TB_THEME_ROOT . '/library/Context.php';
require_once TB_THEME_ROOT . '/library/AdminDispatcher.php';

$registry = isset($registry) ? $registry : $this->registry;

$engine_config = TB_EnvHelper::getInstance($registry)->getEngineConfig(TB_BASENAME);

if (!TB_RequestHelper::isRequestHTTPS() && $registry->get('config')->get('config_secure') && $engine_config['admin_redirect_https'] && 0 === strpos('https', HTTPS_SERVER)) {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

if (TB_Utils::parseDebug($engine_config['firephp'])) {
    require_once TB_THEME_ROOT . '/library/vendor/FirePHP.php';

    FB::setOptions(array(
        'maxObjectDepth'      => 7,
        'maxArrayDepth'       => 7,
        'maxDepth'            => 10,
        'useNativeJsonEncode' => true,
        'includeLineNumbers'  => true
    ));
} else {
    if (!function_exists('fb')) {
        function fb($arg) {
            return $arg;
        }
    }
}
