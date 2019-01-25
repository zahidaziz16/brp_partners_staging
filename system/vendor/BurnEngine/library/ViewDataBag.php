<?php

class TB_ViewDataBag extends TB_DataBag
{
    /**
     * @var TB_Context
     */
    protected $context;

    /**
     * @var TB_ViewSlot
     */
    public $viewSlot;

    protected $non_cacheable_javascript_vars = array();
    protected $javascript_resources = array();
    protected $stylesheet_resources = array();
    protected $tb_setting_load_keys = array();

    public function __construct(TB_Context $context, TB_ViewSlot $viewSlot)
    {
        parent::__construct();

        $this->context  = $context;
        $this->viewSlot = $viewSlot;
    }

    public function addJavascriptVar($space, $var, $cacheable = true)
    {
        if (!strpos($space, '/')) {
            throw new Exception('You must specify a javascript registry space for the variable ' . $space);
        }

        list($section, $name) = explode('/', $space, 2);
        $this->container['javascript'][$section][$name] = $var;

        if (false === $cacheable) {
            $this->non_cacheable_javascript_vars[$section][$name] = 1;
        }
    }

    public function isJavascriptVarCacheable($section, $name)
    {
        return !isset($this->non_cacheable_javascript_vars[$section][$name]);
    }

    protected function registerResource($type, $path, TB_CatalogExtension $extension = null, $prepend = false)
    {
        if (is_string($path)) {
            if (0 !== strpos($path, '//') && 0 !== strpos($path, 'http') && 0 !== strpos($path, 'https')) {
                $view_url = null !== $extension ? $extension->getAreaViewUrl() : $this->context->getThemeCatalogResourceUrl();
                $view_url .= $path;
            } else {
                $view_url = $path;
            }
            $hash = md5($path);
            $view_dir = (null !== $extension ? $extension->getViewDir() : $this->context->getCatalogResourceDir()) . '/' . $path;
        } else {
            $view_url = $path['url'];
            $view_dir = $path['dir'];
            $hash = md5($path['dir']);
        }

        $resource = array(
            'dir' => $view_dir,
            'url' => $view_url
        );

        if (!$prepend) {
            $this->{"{$type}_resources"}[$hash] = $resource;
        } else {
            $this->{"{$type}_resources"} = array($hash => $resource) + $this->{"{$type}_resources"};
        }

        return $hash;
    }

    protected function getResource($type, $key = null)
    {
        if (null !== $key) {
            if (isset($this->{"{$type}_resources"}[$key])) {
                return $this->{"{$type}_resources"}[$key];
            }

            return array();
        }

        return $this->{"{$type}_resources"};
    }

    public function registerJavascriptResource($path, TB_CatalogExtension $extension = null, $prepend = false)
    {
        $this->registerResource('javascript', $path, $extension, $prepend);
    }

    public function appendJavascriptResource($path, TB_CatalogExtension $extension = null)
    {
        $this->registerResource('javascript', $path, $extension, false);
    }

    public function prependJavascriptResource($path, TB_CatalogExtension $extension = null)
    {
        $this->registerResource('javascript', $path, $extension, true);
    }

    public function getJavascriptResources($key = null)
    {
        return $this->getResource('javascript', $key);
    }

    public function registerStylesheetResource($path, TB_CatalogExtension $extension = null, $replacements = null)
    {
        $hash = $this->registerResource('stylesheet', $path, $extension);
        if (is_array($replacements)) {
            $url = null !== $extension ? $extension->getAreaViewUrl() : $this->context->getThemeCatalogResourceUrl();
            foreach ($replacements as $search => $replace) {
                $this->stylesheet_resources[$hash]['path_replace'][$search] = str_replace('{path}', $url, $replace);
            }
        }
    }

    public function getStylesheetResources($key = null)
    {
        return $this->getResource('stylesheet', $key);
    }

    public function addTbSettingsLoadKey($key)
    {
        $this->tb_setting_load_keys[] = $key;
    }

    public function getTbSettingsLoadKeys()
    {
        return $this->tb_setting_load_keys;
    }

    public function slotStartSystem($name = '', array $params = array(), $check_existence = false)
    {
        if ($check_existence) {
            $slot_parts = explode('.', $name);
            if (!in_array(end($slot_parts), $this['areas_system_slots'])) {
                return false;
            }
        }

        $this->viewSlot->startSystem($name, $params);

        return true;
    }

    public function slotStartHeader()
    {
        if ($this['filter_header'] === false) {
            return;
        }
        
        $this->viewSlot->start('oc_header');
    }

    public function slotStopHeader()
    {
        if ($this['filter_header'] === false) {
            return;
        }

        $this->viewSlot->stop(true);
        ob_start();
        ob_implicit_flush(0);
        $this['header_caught'] = true;
    }

    public function slotStartFooter()
    {
        if ($this['filter_footer'] === false) {
            return;
        }

        $this->viewSlot->start('footer_slot_internal');
    }

    public function slotStopFooter()
    {
        if ($this['filter_footer'] === false) {
            return;
        }

        $this->viewSlot->stop(true);
        $this->echoFooter($this->viewSlot->getContent('footer_slot_internal'));
    }

    public function slotStart($name = '', array $params = array())
    {
        $this->viewSlot->start($name, $params);
    }

    public function slotStop($capture = true, $stack = false)
    {
        $this->viewSlot->stop($capture, $stack);
    }

    public function slotStartJs()
    {
        $this->viewSlot->startJs();
    }

    public function slotStopJs()
    {
        $this->viewSlot->stopJs();
    }

    public function slotCaptureEcho()
    {
        $name = $this->viewSlot->stop(true);
        $this->viewSlot->display($name);
    }

    public function slotStopEcho()
    {
        $this->slotStop(false);
    }

    public function slotStopStack()
    {
        $this->slotStop(true, true);
    }

    public function slotEcho($name)
    {
        $this->viewSlot->display($name);
    }

    public function slotEchoClear($name)
    {
        $this->slotEcho($name);
        $this->viewSlot->removeContent($name);
    }

    public function slotFlag($name, array $params = array())
    {
        $this->viewSlot->flag($name, $params);
    }

    public function slotFilter($name, &$value, array $params = array())
    {
        return $this->viewSlot->filter($name, $value, $params);
    }

    public function slotHasContent($name)
    {
        return $this->viewSlot->hasContent($name);
    }
}