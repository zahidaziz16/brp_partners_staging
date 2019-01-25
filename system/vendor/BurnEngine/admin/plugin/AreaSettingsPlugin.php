<?php

class Theme_Admin_AreaSettingsPlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    public function getConfigKey()
    {
        return 'area';
    }

    public function filterSettings(array &$style_settings)
    {

    }

    public function setDataForView(&$style_settings, TB_ViewDataBag $themeData)
    {

    }

    public function saveData($post_data)
    {
        foreach ($post_data['area'] as $area_name => &$area_settings) {
            if (!isset($post_data['area_' . $area_name . '_key'])) {
                continue;
            }

            $area_key = $area_name . '_' . $post_data['area_' . $area_name . '_key'];

            $this->getModel('layoutBuilder')->cleanSettingsDataBeforePersist($area_settings);
            $this->engine->getStyleSettingsModel()->setAndPersistScopeSettings($area_key, $area_settings);

            $this->engine->wipeAllCache('*' . str_replace('/', '_', $area_key) . '*');
            $this->engine->wipeVarsCache('*area_keys.' . $this->context->getStoreId() . '*');
        }
    }
}