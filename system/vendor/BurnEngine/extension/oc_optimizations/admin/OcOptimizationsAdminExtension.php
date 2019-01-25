<?php

class OcOptimizations_Admin_Extension extends TB_AdminExtension
{
    public function checkRequirements()
    {
        if ($this->engine->gteOc2()) {
            return true;
        }

        return $this->engine->getThemeExtension()->getModel('extensions')->requireVQMod();
    }

    public function canEdit()
    {
        return false;
    }

    public function install()
    {
        if ($this->engine->gteOc2()) {
            $this->engine->getThemeExtension()->getInstaller()->installOcMod($this->getRootDir() . '/config/data/ocmod/optimizations.ocmod.xml');
        } else {
            $this->engine->getThemeExtension()->getInstaller()->installVQmod($this->getRootDir() . '/config/data/vqmod/tb_optimizations.xml');
        }
    }

    public function uninstall()
    {
        if ($this->engine->gteOc2()) {
            $this->engine->getThemeExtension()->getInstaller()->removeOcMod('tbOptimizations', true);
        } else {
            $this->engine->getThemeExtension()->getInstaller()->removeVQmod('tb_optimizations.xml');
        }
    }

    public function upgrade()
    {
        if ($this->engine->gteOc2()) {
            $this->engine->getThemeExtension()->getInstaller()->removeOcMod('tbOptimizations');
            $this->engine->getThemeExtension()->getInstaller()->installOcMod($this->getRootDir() . '/config/data/ocmod/optimizations.ocmod.xml');
        } else {
            $this->engine->getThemeExtension()->getInstaller()->removeVQmod('tb_optimizations.xml');
            $this->engine->getThemeExtension()->getInstaller()->installVQmod($this->getRootDir() . '/config/data/vqmod/tb_optimizations.xml');
        }
    }
}