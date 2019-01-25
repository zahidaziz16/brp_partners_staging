<?php

class Theme_Catalog_CompatibilityPlugin extends TB_ExtensionPlugin
{
    protected $has_module = false;

    public function configure(TB_ViewDataBag $themeData)
    {
        /** @var Theme_DefaultModel $defaultModel */
        $defaultModel = $this->getModel('default');
        if (!in_array('mega_filter', $defaultModel->getInstalledOcModules())) {
            return;
        }

        $this->eventDispatcher->connect('core:resolveOcModuleSettings',   array($this, 'resolveModule'));
        $this->eventDispatcher->connect('core:ocWidgetCacheConfig',       array($this, 'cacheConfig'));
        $this->eventDispatcher->connect('ocResponse:raw_output.filter',   array($this, 'checkRawOutput'));
        $this->eventDispatcher->connect('view:output',                    array($this, 'viewOutput'));
        $this->eventDispatcher->connect('core:prependTwitterBootstrap',   array($this, 'twitterBootstrap'));
        $this->eventDispatcher->connect('outputHandler:ocResponseOutput', array($this, 'outputHandlerOutput'));
    }

    public function resolveModule(sfEvent $event)
    {
        foreach (array('resolveMegaFilterPro') as $method) {
            $module_settings = $event['module_settings'];
            if ($this->$method($module_settings)) {
                $event->setReturnValue($module_settings);
                $this->has_module = true;

                return true;
            }
        }

        return null;
    }

    public function outputHandlerOutput(sfEvent $event, $skip_return_output)
    {
        if (!$this->engine->gteOc2() && isset($this->engine->getOcRequest()->get['mfilterAjax']) && TB_RequestHelper::isAjaxRequest()) {
            // In OC1 $tbData->slotStopHeader() is executed in every template, which sets $themeData->header_caught to true
            // Then TB_OutputHandler->ocResponseOutput returns the output and ocResponse:raw_output.filter is never fired
            return true;
        }

        return $skip_return_output;
    }

    public function cacheConfig(sfEvent $event)
    {
        /** @var Theme_OpenCartWidget $widget */
        $widget = $event->getSubject();

        $oc_module_settings = $widget->getOcModuleSettings();

        if (!empty($oc_module_settings['code']) && strtolower($oc_module_settings['code']) == 'mega_filter') {
            $widget->addAttribute('renderPlaceHolder', 1);
            $widget->addAttribute('cacheContent', 0);
        }
    }

    protected function resolveMegaFilterPro(array &$module_settings)
    {
        $parts = explode('.', $module_settings['code']);

        if ($parts[0] != 'mega_filter' || !isset($parts[1])) {
            return false;
        }

        if (null === ($mfp_setting = $this->engine->getOcConfig()->get($parts[0] . '_module'))) {
            if (version_compare( $this->engine->getOcConfig()->get('mfilter_version'), '2.0.4.5.2', '<' ) ||
                null == ($mfp_setting = $this->engine->getOcDB()->query( 'SELECT * FROM ' . DB_PREFIX . 'mfilter_settings WHERE idx=' . (int) $parts[1])->row))
            {
                return false;
            } else {
                $mfp_setting = json_decode($mfp_setting['settings'], true);
            }
        }

        $mfp_setting['code']     = $parts[0];
        $mfp_setting['_idx']     = $parts[1];
        $mfp_setting['position'] = $module_settings['position'];

        $module_settings = (array) $mfp_setting + $module_settings;

        return true;
    }

    public function checkRawOutput(sfEvent $event)
    {
        if (!isset($this->engine->getOcRequest()->get['mfilterAjax']) || !TB_RequestHelper::isAjaxRequest()) {
            return;
        }

        /** @var TB_OutputHandler $outputHandler */
        $outputHandler = $event->getSubject();

        // get the sloted content
        $content = $outputHandler->customOutput();

        if (false !== strpos($event['raw_output'], 'id="mfilter-json"')) {
            // May be the div has been inserted in a slot that is not present in the raw output
            $content = $event['raw_output'] . $content;
        }

        $this->modifyMegaFilterOutput($content);
        $event->setReturnValue($content);
        $event->setProcessed(true);
    }

    public function viewOutput(TB_ViewSlotEvent $event)
    {
        $content = str_replace('<script src="catalog/view/javascript/mf/jquery-ui.min.js" type="text/javascript"></script>', '' , $event->getContent());
        $event->setContent($content);
    }

    public function twitterBootstrap()
    {
        if (!$this->has_module) {
            return;
        }

        $this->getThemeData()->prependJavascriptResource(array(
            'url' => $this->context->getCatalogUrl() . 'view/javascript/mf/jquery-ui.min.js',
            'dir' => $this->context->getCatalogDir() . 'view/javascript/mf/jquery-ui.min.js'
        ));
    }

    protected function modifyMegaFilterOutput(&$html)
    {
        if (!preg_match_all('/((<script (?:type="text\/javascript" data-(?:critical|capture)="1")>|<script>).*?<\/script>)/is', $html, $matches)) {
            return;
        }

        $js = '';
        foreach ($matches[1] as $match_key => $match) {
            $js .= preg_replace('/<script[^>]*>(.*)<\/script>/Uis', '$1', $match) . "\n";
        }

        $textarea = '<textarea id="productsGlobalEval" style="display: none;">' . $js . '</textarea>';

        if (false !== strpos($html, '<div id="mfilter-content-container">')) {
            $html = str_replace('<div id="mfilter-content-container">', '<div id="mfilter-content-container">' . $textarea, $html);
        } else
        if (false !== strpos($html, 'tb_system_products">')) {
            $html = str_replace('tb_system_products">', 'tb_system_products">' . $textarea, $html);
        } else {
            $html = str_replace('<div id="mfilter-json"', $textarea . '<div id="mfilter-json"', $html);
        }
    }
}