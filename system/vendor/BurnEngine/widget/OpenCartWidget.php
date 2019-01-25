<?php

class Theme_OpenCartWidget extends TB_Widget
{
    protected $areas = array();
    protected $oc_module_settings = null;

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active' => 1
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'code'       => '',
            'position'   => '',
            'sort_order' => 1
        ), $settings));
    }

    public function initFrontend()
    {
        $this->engine->getEventDispatcher()->connect('core:assignAssets', array($this, 'assignAssets'));
    }

    public function assignAssets()
    {
        $module_settings = $this->getOcModuleSettings();

        if ($module_settings['code'] == 'carousel') {
            $this->themeData->registerJavascriptResource('javascript/swiper.min.js');
            $this->themeData->registerStylesheetResource('stylesheet/swiper.css');
        }
    }

    public function render(array $view_data = array())
    {
        if ($this->isRendered()) {
            return $this->getAttribute('content');
        }

        $content                   = '';
        $module_settings           = $this->getOcModuleSettings();

        $title_css_classes         = 'panel-heading';
        $title_css_classes        .= $this->getDistanceClasses('title');
        $title_legacy_css_classes  = 'box-heading';
        $title_legacy_css_classes .= $this->getDistanceClasses('title');

        if (!empty($module_settings)) {
            $parent_id = $this->parent !== null ? $this->parent->getDomId() : 0;

            $module = 'module/' . $module_settings['code'];

            try {
                $content = $this->themeData->loadController($module, $module_settings);
            } catch (Exception $e) {
                if ($this->engine->gteOc23()) {
                    $content = $this->themeData->loadController('extension/' . $module, $module_settings);
                }
            }

            $content = trim(str_replace(
                array(
                    '{{widget_dom_id}}',
                    '{{within_group}}',
                    '{{group_id}}',
                    '{{optimize_js_load}}',
                    'panel-heading',
                    'box-heading'
                ),
                array(
                    $this->getDomId(),
                    ($this->isWithinGroup() ? 'true' : 'false'),
                    $parent_id,
                    $this->themeData->optimize_js_load ? 'true' : 'false',
                    $title_css_classes,
                    $title_legacy_css_classes
                ),
                $content
            ));
        }

        return parent::renderContent($content);
    }

    public function isActive()
    {
        $module_settings = $this->getOcModuleSettings();
        $result = isset($module_settings['status']) && !empty($module_settings['status']);

        return $result;
    }

    public function getOcModuleSettings()
    {
        if (null !== $this->oc_module_settings) {
            return $this->oc_module_settings;
        }

        return $this->engine->gteOc2() ? $this->getOcModuleSettings2() : $this->getOcModuleSettings1();
    }

    protected function getOcModuleSettings1()
    {
        $this->oc_module_settings = array();

        $modules = $this->engine->getOcConfig()->get($this->settings['code'] . '_module');
        if (!$modules) {
            return $this->oc_module_settings;
        }

        $layout_id = $this->getThemeModel()->getLayoutIdByName('TB_Widgets');

        foreach ($modules as $module) {
            $layouts = (array) $module['layout_id'];

            if (!strlen($module['sort_order']) || $module['sort_order'] != $this->settings['sort_order'] || !in_array($layout_id, $layouts)) {
                continue;
            }

            $module['code'] = $this->settings['code'];
            $this->oc_module_settings = $module;
            $this->oc_module_settings['_idx'] = $this->settings['sort_order'];
            break;
        }

        return $this->oc_module_settings;
    }

    protected function getOcModuleSettings2()
    {
        $module_settings = array(
            'status'      => 0,
            'code'        => $this->settings['code'],
            'position'    => $this->settings['position'],
            'sort_order'  => $this->settings['sort_order'],
            'widget_name' => $this->settings['widget_name']
        );

        $event = new sfEvent($this, 'core:resolveOcModuleSettings', array('module_settings' => $module_settings));
        $this->engine->getEventDispatcher()->notifyUntil($event);

        if ($event->isProcessed()) {
            $this->oc_module_settings = $event->getReturnValue();
            $this->oc_module_settings['status'] = 1;

            return $this->oc_module_settings;
        }

        $parts = explode('.', $this->settings['code']);
        $type = $parts[0];

        if (isset($parts[1])) {
            /** @var ModelExtensionModule $extensionModuleModel */
            $extensionModuleModel = $this->getOcModel('extension/module');
            $module_settings = $extensionModuleModel->getModule($parts[1]);

            if (!$module_settings || !$module_settings['status']) {
                return $module_settings;
            }
        } else
        if (!$this->engine->getOcConfig()->get($type . '_status')) {
            return $module_settings;
        }

        $module_settings['code']   = $type;
        $module_settings['status'] = 1;

        $this->oc_module_settings = $module_settings;

        return $module_settings;
    }

    protected function getBoxClasses()
    {
        $classes = parent::getBoxClasses();

        $module_settings = $this->getOcModuleSettings();
        $classes .= ' tb_module_' . $module_settings['code'];

        return $classes;
    }
}