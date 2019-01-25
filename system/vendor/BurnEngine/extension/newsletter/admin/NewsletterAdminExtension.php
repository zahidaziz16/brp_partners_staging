<?php

class Newsletter_Admin_Extension extends TB_AdminExtension
{
    public function configure()
    {
        $this->eventDispatcher->connect('core:extensions_postconfigure', array($this, 'postConfigure'));
        $this->engine->getWidgetManager()->registerWidget($this->getRootDir() . '/widget/NewsletterWidget.php', 'Theme_NewsletterWidget', $this);
    }

    public function install()
    {
        /** @var Newsletter_Admin_DefaultModel $model */
        $model = $this->getModel('default');
        $model->install();
    }

    public function uninstall()
    {
        /** @var Newsletter_Admin_DefaultModel $model */
        $model = $this->getModel('default');
        $model->uninstall();
    }

    public function postConfigure(sfEvent $event)
    {
        $event['themeData']->addJavascriptVar('tb/url/newsletter/default/saveSettings', $this->getTbUrl()->generateJs('default/saveSettings'));
        $event['themeData']->addJavascriptVar('tb/url/newsletter/default/deleteSubscribers', $this->getTbUrl()->generateJs('default/deleteSubscribers'));
    }
}