<?php

class FireSlider_Catalog_Extension extends TB_CatalogExtension
{
    public function configure()
    {
        $this->registerStylesheetResources();
    }

    public function registerStylesheetResources()
    {
        $this->themeData->registerStylesheetResource('stylesheet/mightyslider.css', $this, array(
            '../img/' => '../../../system/vendor/' . $this->context->getBasename() . '/extension/fire_slider/catalog/view/img/'
        ));
    }

    public function registerJavascriptResources()
    {
        $this->registerMightySlider();
        $this->registerTweenlite();
        //$this->themeData->registerJavascriptResource('javascript/mightyslider-v2.1.3.mod.js', $this);
        //$this->themeData->registerJavascriptResource('javascript/mightyslider.animate.plugin.min', $this);
    }

    public function registerMightySlider()
    {
        $this->themeData->registerJavascriptResource('javascript/mightyslider.min.js', $this);
    }

    public function registerTweenlite()
    {
        $this->themeData->registerJavascriptResource('javascript/tweenlite.min.js', $this);
    }

    public function isCoreExtension()
    {
        return true;
    }
}