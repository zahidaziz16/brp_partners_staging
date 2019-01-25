<?php

class TB_ResourceLoader
{
    /**
     * @var TB_Context
     */
    protected $context;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var array
     */
    protected $engine_config;

    public function __construct(TB_Context $context, Registry $registry, array $engine_config)
    {
        $this->context      = $context;
        $this->registry     = $registry;
        $this->config       = $registry->get('config');
        $this->engine_config = $engine_config;

        $this->controllers = array();
        $this->models      = array();
    }

    public function fetchExtensionTemplate(TB_Extension $extension, $filename, array $data = array(), $full_path = false)
    {
        if (!is_file($filename)) {
            if ($extension->isThemeExtension() && $this->context->getArea() == 'catalog') {
                $file =  $filename . '.tpl';
                if (!$full_path) {
                    $file = 'tb/' . $file;
                }
                $file = $this->context->getCatalogTemplateDir() . '/' . $file;
            } else {
                $file = $extension->getAreaDir() . '/view/template/' . $filename . '.tpl';
            }
        } else {
            $file = $filename;
        }

        if (!file_exists($file)) {
            throw new Exception('Could not load template ' . $file);
        }

        extract($data);

        ob_start();

        /** @noinspection PhpIncludeInspection */
        require tb_modification($file);

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    public function resolveExtensionTranslationFilename(TB_Extension $extension, $filename)
    {
        $language_path = $this->resolveExtensionTranslationPath($extension);

        if (empty($language_path)) {
            return false;
        }

        if ($extension->isThemeExtension() && $this->context->getArea() == 'catalog') {
            $language_path .= '/' . $this->context->getBasename();
        }

        return $language_path . '/' . $filename . '.lang.php';
    }

    protected function resolveExtensionTranslationPath(TB_Extension $extension)
    {
        static $resolved_paths = array();

        $current_language = $this->context->getCurrentLanguage();

        if (isset($resolved_paths[$current_language['code']][$extension->getName()])) {
            return $resolved_paths[$current_language['code']][$extension->getName()];
        }

        if ($extension->isThemeExtension() && $this->context->getArea() == 'catalog') {
            $base_path = $this->context->getCatalogLanguageDir();
        } else {
            $base_path = $extension->getAreaDir() . '/language';
        }

        $path = '';

        if (is_dir($base_path . '/' . $current_language['code'])) {
            $path = $base_path . '/' . $current_language['code'];
        }

        if (empty($path) && !empty($current_language['locale'])) {
            foreach (explode(',', $current_language['locale']) as $code) {
                if (false === strpos('_', $code) && is_dir($base_path . '/' . trim($code))) {
                    $path = $base_path . '/' . trim($code);

                    break;
                }
            }
        }

        if (empty($path) && is_dir($base_path. '/' . $current_language['directory'])) {
            $path = $base_path . '/' . $current_language['directory'];
        }

        if (empty($path) && !$extension->isCoreExtension() && $this->context == 'admin') {
            throw new Exception('Cannot find valid language directory in `' . $base_path . "/`\n Current language: " . var_export($current_language, true));
        }

        if (empty($path) && is_dir($base_path. '/' . $this->engine_config['fallback_language'])) {
            $path = $base_path. '/' . $this->engine_config['fallback_language'];
        }

        $resolved_paths[$current_language['code']][$extension->getName()] = $path;

        return $path;
    }

    public function loadExtensionTranslation(TB_Extension $extension, $filename)
    {
        if ($extension->isThemeExtension() && $this->context->getArea() == 'catalog') {
            return $this->loadCatalogThemeTranslation($extension, $filename);
        }

        $file_path = $this->resolveExtensionTranslationFilename($extension, $filename);

        if (empty($file_path)) {
            return array();
        }

        if (!file_exists($file_path)) {
            $config = $this->engine_config;
            $fallback_file = $extension->getAreaDir() . '/language/' . $config['fallback_language'] . '/' . $filename . '.lang.php';
            if (!file_exists($fallback_file)) {
                return array();
            }

            $file_path = $fallback_file;
        }

        $_ = array();

        /** @noinspection PhpIncludeInspection */
        require_once tb_modification($file_path);

        return $_ ;
    }

    protected function loadCatalogThemeTranslation(TB_Extension $extension, $filename)
    {
        $language_dir = $this->resolveExtensionTranslationPath($extension);
        $language_file = $language_dir . '/' . $this->context->getBasename() . '/' . $filename . '.lang.php';

        $fallback_language = $this->engine_config['fallback_language'];
        if (!(version_compare(VERSION, '2.0.0.0') >= 0) && $fallback_language == 'en-gb') {
            $fallback_language = 'english';
        }

        $fallback_dir = $this->context->getCatalogLanguageDir() . '/' . $fallback_language;
        $fallback_file = $fallback_dir . '/' . $this->context->getBasename() . '/' . $filename . '.lang.php';

        if (!is_file($fallback_file)) {
            $notice = 'The fallback language "' . $fallback_file . '" does not exist.';
            trigger_error($notice);
        }

        if ($fallback_dir != $language_dir) {

            $_ = array();

            /** @noinspection PhpIncludeInspection */
            require tb_modification($fallback_file);

            if (file_exists($language_file)) {
                $_fallback = $_;
                $_ = array();

                /** @noinspection PhpIncludeInspection */
                require tb_modification($language_file);

                foreach ($_fallback as $key => $value) {
                    if (!isset($_[$key])) {
                        $_[$key] = $value;
                    }
                }
            }
        } else {
            $_ = array();
            if (!file_exists($language_file)) {
                $notice = 'The language file "' . $language_file . '" does not exist.';
                trigger_error($notice);
            }

            /** @noinspection PhpIncludeInspection */
            require tb_modification($language_file);
        }

        return $_ ;
    }

    public function loadOcTranslation($filename, $alternative_filename = null)
    {
        $path = $this->context->getCatalogLanguageDir() . '/' . $this->context->getCurrentLanguage('directory') . '/';
        $file = $path. $filename . '.php';
        $fallback_dir = $this->context->getCatalogLanguageDir() . '/' . $this->engine_config['fallback_language'];

        if (null !== $alternative_filename && !file_exists($file)) {
            $file = $path . $alternative_filename . '.php';

            if (!file_exists($file)) {
                $file = $fallback_dir . '/' . $alternative_filename . '.php';
            }
        }

        if (!file_exists($file)) {
            $file = $fallback_dir . '/' . $filename . '.php';

            if (!file_exists($file)) {
                $fallback_file = $path . '/default.php';

                if (!file_exists($fallback_file)) {
                    $fallback_file = $fallback_dir . '/default.php';

                    if (!file_exists($fallback_file)) {
                        throw new Exception('Could not include the language file ' . $file);
                    }
                }

                $file = $fallback_file;
            }
        }

        $_ = array();

        /** @noinspection PhpIncludeInspection */
        require tb_modification($file);

        return $_;
    }

    public function loadExtensionClasses()
    {
        $paths = sfFinder::type('dir')->maxdepth(0)->in($this->context->getExtensionDir());
        $area = $this->context->getArea();
        $classes = array();
        foreach ($paths as $extension_path) {
            if (!is_dir($extension_path . '/' . $area)) {
                continue;
            }

            $extension_name = strtolower(basename($extension_path));

            $extension_file = $extension_path . '/' . $area . '/';
            $extension_file .= TB_Utils::camelize($extension_name) . ucfirst($area) . 'Extension.php';
            if (!file_exists($extension_file)) {
                throw new Exception('Could not initialize extension file ' . $extension_file);
            }

            /** @noinspection PhpIncludeInspection */
            require_once tb_modification($extension_file);

            $class_name = TB_Utils::camelize($extension_name) . '_' . ucfirst($area) . '_Extension';
            if (!class_exists($class_name)) {
                throw new Exception('Could not initialize extension class ' . $class_name);
            }

            $classes[$extension_name] = $class_name;
        }

        return $classes;
    }

    public function loadThemeExtensionClass()
    {
        $area = $this->context->getArea();
        $class_name =  'Theme_' . ucfirst($area) . '_Extension';

        if (class_exists($class_name)) {
            return array ('theme' => $class_name);
        }

        $file = $this->context->getEngineDir() . '/' . $area . '/' . 'Theme' . ucfirst($area) . 'Extension.php';
        if (!is_file($file)) {
            throw new Exception('Could not find the theme extension file: ' . $file);
        }

        /** @noinspection PhpIncludeInspection */
        require tb_modification($file);

        if (!class_exists($class_name)) {
            throw new Exception('Could not initialize the theme extension class: ' . $class_name);
        }

        return array ('theme' => $class_name);
    }


    /**
     * @param TB_Extension $extension
     * @param string $controller_name
     *
     * @throws Exception
     *
     * @return string
     */
    public function loadExtensionController(TB_Extension $extension, $controller_name)
    {
        $extension_name = $extension->getName();
        $area = $this->context->getArea();

        $controller_name = TB_Utils::camelize($controller_name);
        $controller_file = $extension->getAreaDir() . '/controller/' . $controller_name . 'Controller.php';
        if (!file_exists($controller_file)) {
            throw new Exception('Could not include controller file ' . $controller_file);
        }

        /** @noinspection PhpIncludeInspection */
        require_once tb_modification($controller_file);

        $class = TB_Utils::camelize($extension_name) . '_' . ucfirst($area) . '_' . ucfirst($controller_name) . 'Controller';
        if (!class_exists($class)) {
            throw new Exception('Could not initialize controller ' . $class);
        }

        return $class;
    }

    /**
     * @param TB_Extension $extension
     * @param string $model_name
     *
     * @throws Exception
     *
     * @return string
     */
    public function loadExtensionModel(TB_Extension $extension, $model_name = 'default')
    {
        $extension_name = $extension->getName();
        $area = $this->context->getArea();

        $model_class =  TB_Utils::camelize($extension_name) . '_' . ucfirst($area) . '_' . ucfirst($model_name) . 'Model';

        if (class_exists($model_class)) {
            return $model_class;
        }

        $model_file = $extension->getAreaDir() . '/model/' . $model_name . 'Model.php';
        if (!is_file($model_file)) {
            throw new Exception('Could not include model file ' . $model_file);
        }

        /** @noinspection PhpIncludeInspection */
        require tb_modification($model_file);

        if (!class_exists($model_class)) {
            throw new Exception('The class does not exists: ' . $model_class);
        }

        return $model_class;
    }
}