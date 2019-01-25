<?php
class ProductFields_DefaultModel extends TB_ExtensionModel
{
    /**
     * @return TB_SettingsModel
     */
    public function getSettingsModel()
    {
        return $this->engine->getSettingsModel('product_fields', 0);
    }

    public function getSettings()
    {
        return $this->getSettingsModel()->getScopeSettings('settings');
    }
}