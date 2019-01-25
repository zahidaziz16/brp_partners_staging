<?php

class Oc1Compatibility_Admin_Extension extends TB_AdminExtension
{
    public function checkRequirements()
    {
        return $this->engine->getThemeExtension()->getModel('extensions')->requireVQMod();
    }

    public function canInstall()
    {
        return !$this->engine->gteOc2();
    }

    public function canEdit()
    {
        return false;
    }

    public function install()
    {
        $this->engine->getThemeExtension()->getInstaller()->installVQmod($this->getRootDir() . '/config/data/vqmod/tb_compatibility.xml');
    }

    public function uninstall()
    {
        $this->engine->getThemeExtension()->getInstaller()->removeVQmod('tb_compatibility.xml');
    }
}