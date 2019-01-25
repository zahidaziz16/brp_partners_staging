<?php

class Theme_Catalog_RenderSlotPlugin extends TB_ExtensionPlugin
{
    public function execute(TB_ViewDataBag $themeData, Request $request)
    {
        $themeData->addCallable(array($this, 'getSlotContents'));
        if ($_SERVER['REMOTE_ADDR'] == '192.168.2.19') {
            //fb($this->getSlotContents('ProductsSystem', array(), 'default'));
        }
    }

    public function getSlotContents($slot, array $data = array(), $route = null)
    {
        $themeData = $this->getThemeData();

        if (null === $route) {
            $route = $themeData->route;
        }

        if ('default' == $route) {
            $route = 'product/category';
        }

        if ($themeData->route != $route) {
            $keys = $this->getSetting('area_keys');
            $area_name = 'content';
            $result = array();

            if (isset($keys[$area_name . '_category_global'])) {
                $result[$area_name] = 'category_global';
            } else {
                $has_category_defaults = isset($keys[$area_name . '_default_' . $route]);
                $has_global_record = isset($keys[$area_name . '_global']);

                if ($has_category_defaults && (!$has_global_record || $keys[$area_name . '_default_' . $route] == 0)) {
                    $result[$area_name] = 'default_' . $route;
                } else
                if ($has_global_record) {
                    $result[$area_name] = 'global';
                } else
                if (isset($keys[$area_name . '_default'])) {
                    $result[$area_name] = 'default';
                }
            }

            $request_areas = $result;
        } else {
            $request_areas = $themeData->request_areas;
        }

        $parts = explode('_', $slot);
        $partial = false;

        if (count($parts) == 1) {
            $widget_id = 'Theme_' . $parts[0] . 'Widget';
            $partial = true;
        } else {
            $widget_id = 'Theme_' . $parts[0] . 'Widget_' . $parts[1];
        }

        $widget_data = $this->getWidgetData($request_areas, $widget_id, $partial);
        if (!$widget_data) {
            return '';
        }

        list($widget_id, $widget_settings) = $widget_data;

        $widget = $this->engine->getWidgetManager()->createAndFilterWidgetFromId($widget_id, $widget_settings, 'catalog');

        if (method_exists($widget, 'configureFrontend')) {
            $widget->configureFrontend();
        }

        if (method_exists($widget, 'setProductsOptions')) {
            $widget->setProductsOptions();
        }

        if (method_exists($widget, 'initFrontend')) {
            $widget->initFrontend($themeData);
        }

        if ($widget instanceof Theme_SystemWidget) {
            $settings = $widget->getSettings();

            if ($widget instanceof Theme_SubcategoriesSystemWidget) {
                if (!$settings['inherit_subcategories']) {
                    if (isset($themeData['category'])) {
                        // Avoid "Indirect modification of overloaded element of TB_ViewDataBag has no effect" error
                        $category = $themeData['category'];
                        $category['subcategories'] = $settings['subcategories'];
                        $themeData['category'] = $category;
                    } else {
                        $themeData['category'] = array('subcategories' => $settings['subcategories']);
                    }
                }
            }

            $themeData[$widget->getSlotFullName()] = $settings;
        }

        $route_parts = explode('/', $route);
        $template = array_shift($route_parts) . '/' . implode('_', $route_parts) . '.tpl';

        $file = $this->context->getCatalogTemplateDir() . '/' . $template;

        if (!file_exists($file)) {
            throw new Exception('Could not load template ' . $file);
        }

        if ($themeData->route != $route) {
            $themeData->viewSlot->addGroupKey('system', 'product/category.' . $widget->getSlotName());
        }

        extract($data);
        $tbData = $themeData;
        $header = '';
        $footer = '';
        $this->extension->setHeaderFilter(false);
        $this->extension->setFooterFilter(false);

        ob_start();
        ob_implicit_flush(0);

        require tb_modification($file);

        ob_end_clean();

        return $widget->render();
    }

    protected function getWidgetData($request_areas, $widget_id, $partial = false)
    {
        $area_keys = array();
        foreach ($request_areas as $area_name => $area_id) {
            $area_keys[] = $area_name . '_' . $area_id;
        }

        $keys = $this->engine->getWidgetManager()->loadAreasData($area_keys);

        foreach ($request_areas as $area_name => $area_id) {

            if (!isset($keys['area'][$area_name])) {
                continue;
            }

            if ($keys['area'][$area_name] != $area_id) {
                throw new Exception('Area detection mismatch');
            }

            $area_data = $this->engine->getWidgetManager()->getAreaBuilder($area_name . '_' . $area_id);

            foreach ($area_data['rows'] as $row) {
                foreach ($row['columns'] as $column) {
                    foreach ($column['widgets'] as $widget) {
                        if ($widget['id'] == $widget_id || $partial && 0 === strpos($widget['id'], $widget_id)) {
                            return array($widget['id'], $widget['settings']);
                        }
                    }
                }
            }
        }

        return false;
    }
}