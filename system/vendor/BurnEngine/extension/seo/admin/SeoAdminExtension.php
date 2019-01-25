<?php

class Seo_Admin_Extension extends TB_AdminExtension
{
    public function configure()
    {
        $this->eventDispatcher->connect('core:extensions_postconfigure', array($this, 'postConfigure'));
        $this->eventDispatcher->connect('oc:updateEntity', array($this->getModel('default'), 'updateEntityMeta'));
    }

    public function checkRequirements()
    {
        return $this->engine->getThemeExtension()->getModel('extensions')->requireVQMod();
    }

    public function install()
    {
        /** @var Seo_Admin_DefaultModel $model */
        $model = $this->getModel('default');
        $model->install();
    }

    public function uninstall()
    {
        /** @var Seo_Admin_DefaultModel $model */
        $model = $this->getModel('default');
        $model->uninstall();
    }

    public function upgrade()
    {
        /** @var Seo_Admin_DefaultModel $model */
        $model = $this->getModel('default');
        $model->upgrade();
    }

    public function postConfigure(sfEvent $event)
    {
        $event['themeData']->addCallable(array($this->getModel('generator'), 'getItemLanguageKeywords'));
        $event['themeData']->addCallable(array($this->getModel('generator'), 'insertItemLanguageKeywords'));
        $event['themeData']->addCallable(array($this->getModel('default'), 'getSettings'), 'getSeoSettings');
        $event['themeData']->addCallable(array($this->getModel('default'), 'getEntitySettings'), 'getSeoEntitySettings');

        $event['themeData']->addJavascriptVar('tb/url/seo/default/editField',    $this->getTbUrl()->generateJs('default/editField'));
        $event['themeData']->addJavascriptVar('tb/url/seo/default/preview',      $this->getTbUrl()->generateJs('default/preview'));
        $event['themeData']->addJavascriptVar('tb/url/seo/default/generate',     $this->getTbUrl()->generateJs('default/generate'));
        $event['themeData']->addJavascriptVar('tb/url/seo/default/clear',        $this->getTbUrl()->generateJs('default/clear'));
        $event['themeData']->addJavascriptVar('tb/url/seo/default/saveSettings', $this->getTbUrl()->generateJs('default/saveSettings'));
    }
}