<?php

class Theme_SystemWidget extends TB_Widget
{
    protected $locked = false;

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initFlatVars(array(
            'slot_prefix' => '',
            'slot_name'   => ''
        ), $settings));
    }

    public function render(array $view_data = array())
    {
        if (!$this->themeData->viewSlot->hasContent($this->getSlotFullName())) {
            foreach ($this->themeData->viewSlot->getSystemKeys() as $key) {
                list($prefix, $name) = explode('.', $key);
                if ($name == $this->getSlotName()) {
                    // The current template can be rendered with a route that is different from the prefix used in the template
                    // Most likely this is 'error/not_found'
                    $this->setSlotPrefix($prefix);
                    break;
                }
            }
            //$this->setSlotPrefix('error/not_found');
        }

        $parent_id = $this->parent !== null ? $this->parent->getDomId() : 0;

        $content = $this->themeData->viewSlot->getContent($this->getSlotFullName());
        $content = trim(str_replace(
            array('{{widget_dom_id}}', '{{within_group}}', '{{group_id}}', '{{optimize_js_load}}'),
            array($this->getDomId(), ($this->isWithinGroup() ? 'true' : 'false'), $parent_id, ($this->themeData->optimize_js_load ? 'true' : 'false')),
            $content)
        );

        return parent::renderContent($content);
    }

    public function getNoCacheRoutes()
    {
        return array();
    }

    public function getSlotFullName()
    {
        return $this->getSlotPrefix() . '.' . $this->getSlotName();
    }

    public function setSlotName($slot_name)
    {
        $this->settings['slot_name'] = $slot_name;
    }

    public function getSlotName()
    {
        return  $this->settings['slot_name'];
    }

    public function getSlotPrefix()
    {
        return $this->settings['slot_prefix'];
    }

    public function setSlotPrefix($prefix)
    {
        $this->settings['slot_prefix'] = $prefix;
    }

    public function lock()
    {
        $this->locked = true;
    }

    public function isLocked()
    {
        return $this->locked;
    }

    public function getNameId()
    {
        return TB_Utils::underscore($this->getName());
    }

    protected function getBoxClasses()
    {
        $classes  = parent::getBoxClasses();
        $classes .= ' tb_system_' . $this->getSlotName();

        return $classes;
    }
}