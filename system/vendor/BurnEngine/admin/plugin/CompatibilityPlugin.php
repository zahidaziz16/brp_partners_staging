<?php

class Theme_Admin_CompatibilityPlugin extends TB_ExtensionPlugin
{
    public function configure(TB_ViewDataBag $themeData)
    {
        $this->eventDispatcher->connect('core:resolveOcModuleSettings', array($this, 'resolveModule'));
        $this->eventDispatcher->connect('core:canCacheOcModule', array($this, 'canCacheModule'));
    }

    public function canCacheModule(sfEvent $event)
    {
        if (!empty($event['module_settings']['code']) && $event['module_settings']['code'] == 'mega_filter') {
            return true;
        }

        return false;
    }

    public function resolveModule(sfEvent $event)
    {
        foreach (array('resolveMegaFilterPro') as $method) {
            $module_settings = $event['module_settings'];
            if ($this->$method($module_settings)) {
                $event->setReturnValue($module_settings);

                return true;
            }
        }

        return null;
    }

    protected function resolveMegaFilterPro(array &$module_settings)
    {
        $parts = explode('.', $module_settings['code']);

        if ($parts[0] != 'mega_filter' || !isset($parts[1])) {
            return false;
        }

        if (!$mfp_setting = $this->engine->getOcConfig()->get($parts[0] . '_module')) {
            return false;
        }

        $module_settings['code'] = $parts[0];
        $module_settings['_idx'] = $parts[1];

        return true;
    }
}