<?php

class Theme_ExtensionsModel extends TB_ExtensionModel
{
    public function isExtensionInstalled($name)
    {
        if (!$this->getOcConfig()->get(TB_Engine::getName() . '_theme')) {
            return false;
        }

        $extensions = (array) $this->engine->getSettingsModel('extensions', 0)->getScopeSettings('extensions');

        return isset($extensions[$name]);
    }
}