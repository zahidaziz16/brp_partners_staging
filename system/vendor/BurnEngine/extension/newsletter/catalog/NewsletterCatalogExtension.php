<?php

class Newsletter_Catalog_Extension extends TB_CatalogExtension
{
    public function configure()
    {
        $this->eventDispatcher->connect('core:beforeRouting', array($this, 'beforeRouting'));
        $this->engine->getWidgetManager()->registerWidget($this->getRootDir() . '/widget/NewsletterWidget.php', 'Theme_NewsletterWidget', $this);
    }

    public function beforeRouting()
    {
        $this->registerCatalogRoute(array(
            'name'       => 'newsletter',
            'route'      => 'newsletter',
            'controller' => 'newsletter'
        ));
    }
}