<?php

class AlsoBought_Catalog_Extension extends TB_CatalogExtension
{
    public function configure()
    {
        $this->engine->getWidgetManager()->registerWidget($this->getRootDir() . '/widget/AlsoBoughtWidget.php', 'Theme_AlsoBoughtWidget', $this);
        $this->themeData->addCallable(array($this->getModel('default'), 'updateBoughtCombinations'));
    }
}