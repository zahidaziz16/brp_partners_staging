<?php
abstract class TB_ExtensionModel
{
    /**
     * @var TB_Engine
     */
    protected $engine;

    /**
     * @var TB_Extension
     */
    protected $extension;

    /**
     * @var TB_SettingsModel
     */
    protected $settingsModel;

    /*
     * @var TB_Context
     */
    protected $context;

    /**
     * @var TB_DbHelper
     */
    protected $dbHelper;

    /**
     * OpenCart DB class
     *
     * @var DB
     */
    protected $db;

    /**
     * @var Registry
     */
    protected $registry;

    public function __construct(TB_Engine $engine, TB_Extension $extension)
    {
        $this->engine   = $engine;
        $this->extension = $extension;

        $this->registry = $engine->getOcRegistry();
        $this->context  = $engine->getContext();
        $this->dbHelper = $engine->getDbHelper();
        $this->db       = $engine->getOcDb();
    }

    protected function getOcConfig()
    {
        return $this->engine->getOcConfig();
    }

    protected function getOcModel($name)
    {
        return $this->engine->getOcModel($name);
    }

    /**
     * @return Theme_Admin_DefaultModel|Theme_Catalog_DefaultModel
     */
    protected function getThemeModel()
    {
        return $this->engine->getThemeModel();
    }

    /**
     * @param string $name
     * @return TB_ExtensionModel
     */
    protected function getModel($name = 'default')
    {
        return $this->engine->getThemeExtension()->getModel($name);
    }
}