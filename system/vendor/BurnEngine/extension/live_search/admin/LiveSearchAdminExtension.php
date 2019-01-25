<?php

class LiveSearch_Admin_Extension extends TB_AdminExtension
{
    public function configure()
    {
        $this->eventDispatcher->connect('core:extensions_postconfigure', array($this, 'postConfigure'));
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
        $event['themeData']->addJavascriptVar('tb/url/live_search/default/saveSettings', $this->getTbUrl()->generateJs('default/saveSettings'));
    }
}