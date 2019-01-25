<?php

class ControllerCommonBurnEngine extends Controller
{
    /**
     * @var TB_Engine
     */
    protected static $engine;

    public function onGetEventsAfter()
    {
        $this->index();
    }

    public function onViewBefore(&$route, &$data)
    {
        $data['registry'] = $this->registry;
        $data['data']     = $data;
        $data['tbData']   = self::$engine->getThemeExtension()->getDataBag();
    }

    public function onControllerAfter($route)
    {
        self::$engine->getEventDispatcher()->notify(new sfEvent($this, 'oc:controller/' . $route . '/after'), array('route' =>$route));
    }

    public function index()
    {
        if (!empty($this->request->get['route'])) {
            $route_parts = explode('/', $this->request->get['route']);
            if ($route_parts[0] == 'api') {
                return;
            }
        }

        /** @var Config $config */
        $config = $this->registry->get('config');
        $basename = 'BurnEngine';
        $key = 'config_' . (version_compare(VERSION, '2.2.0.0', '>=') ? 'theme' : 'template');

        if ($config->get($key) != $basename) {
            return;
        }

        if (!$config->get($basename . '_theme')) {
            die('There <strong>BurnEngine</strong> module has not been installed. Please, enter the admin area and install it.');
        }

        define('TB_THEME_ROOT', DIR_SYSTEM . 'vendor/' . $basename);
        define('TB_STORE_ID', $config->get('config_store_id'));
        define('TB_BASENAME', $basename);

        if (!is_file(TB_THEME_ROOT . '/catalog/boot.php')) {
            die ('The <strong>BurnEngine</strong> boot file cannot be found. Please, check if everything is uploaded correctly');
        }

        require TB_THEME_ROOT . '/catalog/boot.php';

        $engine = TB_Boot::initEngine($this->registry, $basename, TB_STORE_ID);

        if (!$engine instanceof TB_Engine) {
            switch ($engine) {
                case TB_Boot::ERROR_THEME_INFO:
                    die('The <strong>BurnEngine</strong> module cannot be detected. Are you sure it is installed from the admin panel ?');
                case TB_Boot::ERROR_SETTINGS:
                    die('There is no applied theme for the current store. Please, enter the <strong>BurnEngine</strong> control panel, select the current store and apply a theme.');
            }
        }

        self::$engine = $engine;

        if ($engine->gteOc22()) {
            $engine->getOcEvent()->register('view/*/before', new Action('common/BurnEngine/onViewBefore'));
            $engine->getOcEvent()->register('controller/*/after', new Action('common/BurnEngine/onControllerAfter'));
        }

        TB_Boot::dispatchRequest($engine);
    }
}