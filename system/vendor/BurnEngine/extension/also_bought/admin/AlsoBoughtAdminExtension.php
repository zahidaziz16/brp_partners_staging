<?php

class AlsoBought_Admin_Extension extends TB_AdminExtension
{
    public function configure()
    {
        $this->engine->getWidgetManager()->registerWidget($this->getRootDir() . '/widget/AlsoBoughtWidget.php', 'Theme_AlsoBoughtWidget', $this);
    }

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
        /** @var AlsoBought_Admin_DefaultModel $model */
        $model = $this->getModel('default');
        $model->install();
    }

    public function uninstall()
    {
        /** @var AlsoBought_Admin_DefaultModel $model */
        $model = $this->getModel('default');
        $model->uninstall();
    }
}