<?php

class ProductFields_Admin_Extension extends TB_AdminExtension
{
    public function configure()
    {
        $this->eventDispatcher->connect('core:extensions_postconfigure', array($this, 'postConfigure'));
        $this->eventDispatcher->connect('admin:resolveSystemWidgets', array($this, 'addWidgets'));

        $this->engine->getWidgetManager()->registerWidget($this->getRootDir() . '/widget/ProductFieldSystemWidget.php', 'Theme_ProductFieldSystemWidget', $this);
    }

    public function install()
    {
        /** @var ProductFields_Admin_DefaultModel $model */
        $model = $this->getModel('default');
        $model->install();
    }

    public function uninstall()
    {
        /** @var ProductFields_Admin_DefaultModel $model */
        $model = $this->getModel('default');
        $model->uninstall();
    }

    public function upgrade()
    {
        /** @var ProductFields_Admin_DefaultModel $model */
        $model = $this->getModel('default');
        $model->upgrade();
    }

    public function postConfigure(sfEvent $event)
    {
        $event['themeData']->addCallable(array($this->getModel('default'), 'getProductFields'));
        $event['themeData']->addCallable(array($this->getModel('default'), 'addProductFields'));

        $event['themeData']->addJavascriptVar('tb/url/product_fields/default/saveField', $this->getTbUrl()->generateJs('default/saveField'));
    }

    public function addWidgets(sfEvent $event)
    {
        if ($event['area_type'] != 'product' && $event['area_type'] != 'quickview') {
            return;
        }

        /** @var ArrayObject $widgetsBag */
        $widgetsBag = $event->getSubject();

        /** @var ProductFields_Admin_DefaultModel $model */
        $model = $this->getModel('default');

        foreach ($model->getFields() as $field) {
            $widgetsBag->append(array(
                'label'  => $field['block_name'],
                'slot'   => 'product_field_' . $field['id'],
                'areas'  => 'header', 'footer', 'intro', 'content', 'column_left', 'column_right',
                'class' => 'Theme_ProductFieldSystemWidget',
                'locked' => false
            ));
        }
    }
}