<?php

abstract class TB_ExtensionPlugin
{
    /**
     * @var TB_ExtensionPluginBootstrap
     */
    protected $bootstrapper;

    /**
     * @var TB_Extension | Theme_Admin_Extension | Theme_Catalog_Extension
     */
    protected $extension;

    /**
     * @var TB_Engine
     */
    protected $engine;

    /**
     * @var TB_Context
     */
    protected $context;

    /**
     * @var sfEventDispatcher
     */
    protected $eventDispatcher;

    /**
     * The current active language id
     *
     * @var int
     */
    protected $language_id;

    /**
     * @var string The current active language id
     */
    protected $language_code;

    protected $name;
    protected $init_order = 500;

    public function __construct(TB_ExtensionPluginBootstrap $bootstrapper, TB_Extension $extension, TB_Engine $engine)
    {
        $this->bootstrapper    = $bootstrapper;
        $this->extension       = $extension;
        $this->engine          = $engine;
        $this->context         = $engine->getContext();
        $this->eventDispatcher = $engine->getEventDispatcher();
        $this->language_id     = $this->context->getCurrentLanguage('id');
        $this->language_code   = $this->context->getCurrentLanguage('code');
    }

    public function configure(TB_ViewDataBag $themeData) {}
    public function execute(TB_ViewDataBag $themeData, Request $request) {}

    protected function bootstrap($plugin_name = '')
    {
        if (empty($plugin_name)) {
            $plugin_name = $this->name;
        }

        $this->bootstrapper->bootstrap($plugin_name);
    }

    public function refreshLanguage()
    {
        $this->language_id   = $this->engine->getContext()->getCurrentLanguage('language_id');
        $this->language_code = $this->engine->getContext()->getCurrentLanguage('code');
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getInitOrder()
    {
        return $this->init_order;
    }

    /**
     * @param $name
     *
     * @return TB_ExtensionModel
     */
    protected function getModel($name)
    {
        return $this->extension->getModel($name);
    }

    /**
     * @return Theme_Catalog_DefaultModel| Theme_Admin_DefaultModel
     */
    protected function getThemeModel()
    {
        return $this->extension->getThemeModel();
    }

    protected function getOcModel($name)
    {
        return $this->engine->getOcModel($name);
    }

    protected function getSettings()
    {
        if (!$this instanceof TB_AdminDataPlugin) {
            throw new LogicException('The plugin ' . $this->getName() . 'does not implement TB_AdminDataPlugin interface');
        }

        $db_data = $this->getThemeModel()->getSettings();
        if (!isset($db_data[$this->getConfigKey()])) {
            return array();
        }

        return $db_data[$this->getConfigKey()];
    }

    protected function getSetting($key)
    {
        return $this->getThemeModel()->getSetting($key);
    }

    /**
     * @return TB_ViewDataBag
     */
    protected function getThemeData()
    {
        return $this->extension->getThemeData();
    }

    public function saveAlways()
    {
        return false;
    }
}