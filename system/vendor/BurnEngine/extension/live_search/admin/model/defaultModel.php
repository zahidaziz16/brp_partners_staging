<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class LiveSearch_Admin_DefaultModel extends LiveSearch_DefaultModel
{
    public function install()
    {
        $this->saveSettings($this->getDefaultSettings());
    }

    public function uninstall()
    {
        $this->getSettingsModel()->deleteScopeSettings('settings');
    }

    public function saveSettings(array $settings)
    {
        $this->getSettingsModel()->setAndPersistScopeSettings('settings', $settings);
    }
}