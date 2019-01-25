<?php

class FireSlider_Admin_Extension extends TB_AdminExtension
{
    public function configure()
    {
        $this->eventDispatcher->connect('theme.tabs.main.navigation', array($this, 'addTabNavigation'));
        $this->eventDispatcher->connect('theme.tabs.main.content', array($this, 'addTabContent'));
        $this->eventDispatcher->connect('core:admin_dispatch', array($this, 'afterRouting'));
    }

    public function addTabNavigation(TB_ViewSlotEvent $event)
    {
        $event->insertContentAfter('<li title="Fire Slider" id="menu_fireslider" aria-controls="tb_fireslider_tab_content"><a href="' . $this->getTbUrl()->generate('default') . '"><span class="s_icon_16"><span class="s_icon s_advertising_16"></span>Slider</span></a></li>');
    }

    public function addTabContent(TB_ViewSlotEvent $event)
    {
        $event->insertContentAfter('<div id="tb_fireslider_tab_content"></div>');
    }

    public function afterRouting()
    {
        $this->themeData->addCallable(array($this, 'getSliders'));
    }

    public function getSliders()
    {
        return $this->engine->getSettingsModel('fire_slider', 0)->getValues();
    }

    public function isCoreExtension()
    {
        return true;
    }
}