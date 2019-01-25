<?php

class ProductFields_Catalog_Extension extends TB_CatalogExtension
{
    public function configure()
    {
        $this->engine->getWidgetManager()->registerWidget($this->getRootDir() . '/widget/ProductFieldSystemWidget.php', 'Theme_ProductFieldSystemWidget', $this);
    }
}