<?php

class Theme_Catalog_MaintenancePlugin extends TB_ExtensionPlugin
{
    protected $init_order = 0;
    protected $is_maintenance;

    /**
     * Some 3rd party controllers are accessing this->registry from outside the controller class, which
     * results in non-existing property error when this file is required from execute()
     * @var Registry
     */
    protected $registry;

    public function configure(TB_ViewDataBag $themeData)
    {
        $this->registry = $this->engine->getOcRegistry();
        $this->eventDispatcher->connect('core:beforeRouting', array($this, 'checkMaintenance'));
    }

    public function checkMaintenance(sfEvent $event)
    {
        $this->bootstrap();
        if ($this->is_maintenance) {
            $event->setReturnValue('common/maintenance');
            $event->setProcessed(true);
        }
    }

    public function execute(TB_ViewDataBag $themeData, Request $request)
    {
        $action = new Action(($this->engine->gteOc22() ? 'startup' : 'common') . '/maintenance');

        if ($this->engine->gteOc2()) {
            $result = $action->execute($this->engine->getOcRegistry());
        } else {
            require_once tb_modification($action->getFile());

            $class = $action->getClass();
            $controller = new $class($this->engine->getOcRegistry());
            $result = call_user_func(array($controller, 'index'));
        }

        $this->is_maintenance = is_object($result) && get_class($result) == 'Action';
    }
}