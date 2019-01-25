<?php

abstract class TB_Extension
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var TB_Engine
     */
    protected $engine;

    /**
     * @var sfEventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var TB_Context
     */
    protected $context;

    /**
     * @var TB_ViewDataBag
     */
    protected $themeData;

    /**
     * @var TB_Controller[]
     */
    protected $controllers = array();

    /**
     * @var TB_ExtensionModel[]
     */
    protected $models = array();

    /**
     * @var array
     */
    protected $translations = array();

    protected $init_order = 500;

    public function __construct(TB_Engine $engine, $name)
    {
        $this->engine = $engine;
        $this->name   = $name;

        $this->context         = $engine->getContext();
        $this->eventDispatcher = $engine->getEventDispatcher();
    }

    public function init()
    {
        $this->themeData->addCallable(array($this, 'createJavascriptVars'));
        $this->themeData->addCallable(array($this, 'loadController'));
    }

    public function configure(){}

    public function setThemeData(TB_ViewDataBag $themeData)
    {
        $this->themeData = $themeData;
    }

    /**
     * @return TB_ViewDataBag
     */
    public function getThemeData()
    {
        return $this->themeData;
    }

    /**
     * @return TB_ViewDataBag
     */
    public function getDataBag()
    {
        return $this->themeData;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getInitOrder()
    {
        return $this->init_order;
    }

    public function isThemeExtension()
    {
        return $this->name == 'theme';
    }

    // Indicates whether the extension should be installed or is enabled by default
    public function isCoreExtension()
    {
        return false;
    }

    public function canInstall()
    {
        return true;
    }

    public function canEdit()
    {
        return true;
    }

    public function getRootDir()
    {
        if (null === $this->rootDir) {
            if ($this->isThemeExtension()) {
                $this->rootDir = $this->context->getEngineDir();
            } else {
                $r = new ReflectionObject($this);
                $this->rootDir = realpath(dirname($r->getFileName()) . '/../');
            }
        }

        return $this->rootDir;
    }

    public function getAreaDir()
    {
        return $this->getRootDir() . '/' . $this->engine->getContext()->getArea();
    }

    public function getViewDir()
    {
        return $this->getAreaDir() . '/view';
    }

    public function getAreaViewUrl($relative = false)
    {
        return $this->engine->getContext()->getExtensionsUrl($relative) . $this->getName() . '/' . $this->engine->getContext()->getArea() . '/view/';
    }

    protected function loadTranslation($filename)
    {
        $resourceLoader = $this->engine->getResourceLoader();

        $file_path = $resourceLoader->resolveExtensionTranslationFilename($this, $filename);

        if (empty($file_path)) {
            return array();
        }

        if (isset($this->translations[$file_path])) {
            return $this->translations[$file_path];
        }

        $contents = $resourceLoader->loadExtensionTranslation($this, $filename);
        $this->translations[$file_path] = $contents;

        return $contents;
    }

    public function loadDefaultTranslation()
    {
        return $this->loadTranslation($this->name);
    }

    public function getTranslation($name = '')
    {
        return (empty($name)) ? $this->loadDefaultTranslation() : $this->loadTranslation($name);
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function removeTranslation($filename)
    {
        $file_path = $this->engine->getResourceLoader()->resolveExtensionTranslationFilename($this, $filename);

        if (!empty($file_path)) {
            unset($this->translations[$file_path]);
        }
    }


    public function translate($key)
    {
        foreach ($this->translations as $translation) {
            if (isset($translation[$key])) {
                return $translation[$key];
            }
        }

        return null;
    }

    /**
     * @param string $controller_name
     *
     * @return TB_Controller
     */
    public function getController($controller_name)
    {
        $class_name = $this->engine->getResourceLoader()->loadExtensionController($this, $controller_name);
        if (isset($this->controllers[$class_name])) {
            return $this->controllers[$class_name];
        }

        $class = new $class_name($this->engine, $this);
        $this->controllers[$class_name] = $class;

        return $class;
    }

    /**
     * @param $model_name
     *
     * @return TB_ExtensionModel
     */
    public function getModel($model_name)
    {
        $class_name = $this->engine->getResourceLoader()->loadExtensionModel($this, $model_name);
        if (isset($this->models[$class_name])) {
            return $this->models[$class_name];
        }

        $class = new $class_name($this->engine, $this);
        $this->models[$class_name] = $class;

        return $class;
    }

    /**
     * @return Theme_Catalog_DefaultModel|Theme_Admin_DefaultModel
     */
    public function getThemeModel()
    {
        return $this->getModel('default');
    }

    public function fetchTemplate($filename, array $data = array(), $full_path = false)
    {
        $data['tbData'] = $this->themeData;

        if (!empty($this->translations)) {
            foreach ($this->translations as $translation) {
                foreach ($translation as $key => $value) {
                    $data['text_' . $key] = $value;
                }
            }
        }

        return $this->engine->getResourceLoader()->fetchExtensionTemplate($this, $filename, $data, $full_path);
    }


    public function createJavascriptVars($cacheable = true)
    {
        $bag = $this->themeData;
        if (!isset($bag['javascript']) || !is_array($bag['javascript'])) {
            return '';
        }

        $js_arr = array();
        foreach ($bag['javascript'] as $section => $vars) {
            foreach ($vars as $key => $value) {
                if ($cacheable == 'all' || $cacheable && $bag->isJavascriptVarCacheable($section, $key) || !$cacheable && !$bag->isJavascriptVarCacheable($section, $key)) {
                    if ($section == 'lang') {
    	                $value = utf8_encode($value);
                    }
	                
                    $js_arr['/' . $section . '/' . $key] = $value;
                }
            }
        }

        if (empty($js_arr)) {
            return '';
        }

        return trim($this->fetchTemplate($this->context->getEngineAreaTemplateDir() . '/javascript_vars.tpl', array('jsarr' => $js_arr), true));
    }

    public function includeController($route)
    {
        $action = new Action($route);

        if ($this->engine->gteOc2()) {
            $ref = new ReflectionObject($action);
            $f = $ref->getProperty('file');
            $f->setAccessible(true);
            require_once(tb_modification($f->getValue($action)));
        } else {
            require_once($action->getFile());
        }
    }

    public function loadController($route, $args = array())
    {
        if ($this->engine->gteOc2()) {
            if ($this->engine->gteOc22()) {
                $action = new Action($route);
                $content = $action->execute($this->engine->getOcRegistry(), array($args));
            } else {
                $action = new Action($route, $args);
                $content = $action->execute($this->engine->getOcRegistry());
            }
        } else {
            $controller = new TB_DummyController($this->engine->getOcRegistry());
            $content = $controller->getChildController($route, $args);
        }

        if ($content instanceof Exception) {
            throw $content;
        }

        return $content;
    }
}