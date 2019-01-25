<?php

require_once dirname(__FILE__) . '/../../model/layoutBuilderModel.php';

class Theme_Admin_LayoutBuilderModel extends Theme_LayoutBuilderModel
{
    public function getLayouts()
    {
        static $result = null;

        if (null !== $result) {
            return $result;
        }

        $result = $this->getThemeModel()->getLayouts();

        if ($result) {
            $tb_layout_id = $this->getThemeModel()->getLayoutIdByName('TB_Widgets');
            unset($result[$tb_layout_id]);
        }

        return $result;
    }

    public function getLayoutsExcludingRoute($route)
    {
        static $result = null;

        if (null !== $result) {
            return $result;
        }

        $sql = 'SELECT DISTINCT(l.layout_id)
                FROM ' . DB_PREFIX . 'layout_route AS lr
                LEFT JOIN ' . DB_PREFIX . 'layout AS l on lr.layout_id = l.layout_id
                WHERE lr.route LIKE "%' . implode('%" OR lr.route LIKE "%', (array) $route) . '%"';

        $ids = array_column($this->db->query($sql)->rows, 'layout_id');

        $result = $this->getLayouts();
        foreach ($result as $id => $layout) {
            if (in_array($id, $ids)) {
                unset($result[$id]);
            }
        }

        return $result;
    }

    public function getRouteForSystemPage($page_id)
    {
        foreach ($this->getSystemPages() as $group_name => $group_items) {
            foreach ($group_items as $page_name => $page) {
                if ($group_name . '/' . $page_name == $page_id) {
                    return $page['route'];
                }
            }
        }

        return false;
    }

    public function determineAreaParams($area_name, $area_type, $area_id, $type, $store_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->context->getStoreId();
        }

        // the slot prefix from the template which will display the widgets.
        // This method changes the slot prefix if it will hold a real prefix from a template, else its 'default' value is left
        // If its value is 'default' the prefix will be replaced with the current route in LayoutBuilderPlugin:getArea()
        $slot_prefix = false;
        // the settings key to load the default widgets from
        $area_key = false;
        $widgetManager = $this->engine->getWidgetManager();

        if ($area_type == 'page') {
            $layout_id = $this->getInformationLayoutId($area_id, $store_id);
            $slot_prefix = 'information/information';

            if (!$layout_id || !$widgetManager->areaKeyExists($area_name . '_layout_' . $layout_id, $type, $store_id)) {
                $layout_id = $this->getLayout($slot_prefix, $store_id);
            }

            if ($layout_id && $widgetManager->areaKeyExists($area_name . '_layout_' . $layout_id, $type, $store_id)) {
                $area_key = 'layout_' . $layout_id;
            } else
            if ($widgetManager->areaKeyExists($area_name . '_global', $type, $store_id)) {
                $area_key = 'global';
            } else
            if ($widgetManager->areaKeyExists($area_name . '_information/information__default', $type, $store_id)) {
                $area_key = 'information/information__default';
            }
            else {
                $area_key = '_default';
            }
        } else
        if ($area_type == 'product') {
            $slot_prefix = 'product/product';

            if ($area_id == 'product_global' && $widgetManager->areaKeyExists($area_name . '_product_global', $type, $store_id)) {
                $area_key = 'product_global';
            } else
            if ($widgetManager->areaKeyExists($area_name . '_system_product/product__default', $type, $store_id)) {
                $area_key = 'system_product/product__default';
            } else
            if ($widgetManager->areaKeyExists($area_name . '_global', $type, $store_id)) {
                $area_key = 'global';
            } else {
                $area_key = '_default';
            }
        } else
        if ($area_type == 'quickview') {
            $slot_prefix = 'product/product';

            if ($widgetManager->areaKeyExists($area_name . '_quickview', $type, $store_id)) {
                $area_key = 'quickview';
            } else {
                $area_key = 'quickview__default';
            }
        } else
        if ($area_type == 'category') {
            $layout_id = $this->getCategoryLayoutId($area_id, $store_id);
            $slot_prefix = 'product/category';

            if (!$layout_id || !$widgetManager->areaKeyExists($area_name . '_layout_' . $layout_id, $type, $store_id)) {
                $layout_id = $this->getLayout($slot_prefix, $store_id);
            }

            if ($layout_id && $widgetManager->areaKeyExists($area_name . '_layout_' . $layout_id, $type, $store_id)) {
                $area_key = 'layout_' . $layout_id;
            } else {

                if (is_numeric($area_id)) {
                    $categoryTree = $this->getModel('category')->getCategoriesFlatTree($store_id);
                    $level = $categoryTree[$area_id]['level'];
                    if ($widgetManager->areaKeyExists($area_name . '_category_level_' . $level, $type, $store_id)) {
                        $area_key = 'category_level_' . $level;
                    }
                }

                if (false == $area_key) {
                    if ($widgetManager->areaKeyExists($area_name . '_category_global', $type, $store_id)) {
                        $area_key = 'category_global';
                    } else {
                        $category_defaults = $widgetManager->getAreaSettings($area_name . '_system_product/category__default', $type, $store_id);
                        $global_record = $widgetManager->getAreaSettings($area_name . '_global', $type, $store_id);

                        if ($category_defaults && !$global_record) {
                            $area_key = 'system_product/category__default';
                        } else
                        if ($global_record) {
                            $area_key = 'global';
                        } else {
                            $area_key = '_default';
                        }
                    }
                }
            }
        } else {
            $area_key = '_default';

            if ($area_type == 'layout') {
                $sql = 'SELECT `route`
                        FROM ' . DB_PREFIX . 'layout_route
                        WHERE `store_id` = ' . $store_id. ' AND
                              `layout_id` = ' . (int) $area_id;
                $routes = array_column($this->db->query($sql)->rows, 'route');

                foreach ($routes as $route) {
                    $system_page = $this->getSystemPageForRoute($route);
                    if (false === $system_page) {
                        continue;
                    }
                    if ($system_page['route'] == 'product/product' && $widgetManager->areaKeyExists($area_name . '_product_global', $type, $store_id)) {
                        $area_type   = 'product';
                        $area_key    = 'product_global';
                        $slot_prefix = 'product/product';
                    } else {
                        $area_type = 'system';
                        $area_id = $route;
                    }
                    // Exit on first found route
                    break;
                }
            }

            if ($area_type == 'system') {
                $system_page = $this->getSystemPageForRoute($area_id);
                if (false === $system_page) {
                    throw new InvalidArgumentException('A matching system configuration has not been found');
                }

                $slot_prefix = $system_page['route'];
                $layout_id = $this->getLayout($slot_prefix, $store_id);

                if ($layout_id && $widgetManager->areaKeyExists($area_name . '_layout_' . $layout_id, $type, $store_id)) {
                    $area_key = 'layout_' . $layout_id;
                } else {
                    if ($widgetManager->getAreaSettings($area_name . '_system_' . $system_page['route'] . '_global', $type, $store_id)) {
                        // System Global
                        // ToDo change product_global to system_product/product_global
                        // category_global to system_category/category_global
                        $area_key = 'system_' . $system_page['route'] . '_global';
                    } else {
                        $system_defaults = $widgetManager->getAreaSettings($area_name . '_system_' . $system_page['route'] . '__default', $type, $store_id);
                        $global_record = $widgetManager->areaKeyExists($area_name . '_global', $type, $store_id);

                        if ($system_defaults && !$global_record) {
                            $area_key = 'system_' . $system_page['route'] . '__default';
                        }
                    }
                }
            } else
            if ($area_type == 'global') {
                $slot_prefix = 'default';
            }

            if ($area_key == '_default' && $widgetManager->areaKeyExists($area_name . '_global', $type, $store_id)) {
                $area_key = 'global';
            } else
            if ($area_type == 'home' && $area_name == 'content') {
                $area_key = false;
            }
        }

        return array($slot_prefix, $area_key);
    }

    public function buildOverrideInformationMessage($area_name, $area_type, $area_id, $type)
    {
        $layout_id = 0;
        $msg = '';
        $widgetManager = $this->engine->getWidgetManager();

        if ($area_type == 'category' && !is_numeric($area_id)) {
            $layout_id = $this->getCategoryLayoutId($area_id);

            if (!$layout_id || !$widgetManager->areaKeyExists($area_name . '_layout_' . $layout_id, $type)) {
                $layout_id = $this->getLayout('product/category');
            }
        }

        if ($area_type == 'page' && !is_numeric($area_id)) {
            $layout_id = $this->getInformationLayoutId($area_id);

            if (!$layout_id || !$widgetManager->areaKeyExists($area_name . '_layout_' . $layout_id, $type)) {
                $layout_id = $this->getLayout('information/information');
            }
        }

        if ($area_type == 'system' && !$widgetManager->areaKeyExists($area_name . '_' . $area_type . '_' . $area_id, $type)) {
            $system_page = $this->getSystemPageForRoute($area_id);
            if (false === $system_page) {
                throw new InvalidArgumentException('A matching system configuration has not been found');
            }

            $layout_id = $this->getLayout($system_page['route']);
        }

        $layout = false;
        if ($layout_id && $widgetManager->areaKeyExists($area_name . '_layout_' . $layout_id, $type)) {
            $layout = $this->getOcModel('design/layout')->getLayout($layout_id);
        }

        if (false !== $layout) {
            $msg = 'Layout: ' . $layout['name'];
        }

        return $msg;
    }

    public function buildInheritInformationMessage($area_key, $area_type)
    {
        if ($area_type == 'layout' && $area_key == 'product_global') {
            return 'The settings for <strong>All products</strong>';
        }

        $msg = 'Default built-in settings';

        if (TB_Utils::strEndsWith($area_key, 'product/category__default')) {
            $msg = 'Default built-in category settings';
        } else
        if (0 === strpos($area_key, 'category_level_')) {
            $msg = 'The settings for <strong class="tbParentArea">Level ' . substr($area_key, 15) . ' categories</strong>';
        } else
        if ($area_key == 'category_global') {
            $msg = 'The settings for <strong class="tbParentArea">All categories</strong>';
        } else
        if (0 === strpos($area_key, 'layout_') && is_numeric(substr($area_key, 7))) {
            $layout_id = substr($area_key, 7);
            $layout = $this->getOcModel('design/layout')->getLayout($layout_id);
            $msg = 'The settings for <strong class="tbParentArea">Layout: ' . $layout['name'] . '</strong>';
        } else
        if ($area_key == 'global') {
            $msg = 'The settings for <strong class="tbParentArea">GLOBAL</strong>';
        }

        return $msg;
    }

    public function getAreaSystemSettings($area_name, $area_type, $area_id)
    {
        $settings = array();

        if ($area_type == 'layout') {
            $sql = 'SELECT `route`
                    FROM ' . DB_PREFIX . 'layout_route
                    WHERE `store_id` = ' . $this->context->getStoreId(). ' AND `layout_id` = ' . (int) $area_id;
            $routes = array_column($this->db->query($sql)->rows, 'route');

            foreach ($routes as $route) {
                $system_page = $this->getSystemPageForRoute($route);
                if (false !== $system_page) {
                    $area_type = 'system';
                    $area_id = $route;
                    break;
                }
            }

            if ($area_type != 'system') {
                $layout = $this->getOcModel('design/layout')->getLayout($area_id);
                if (strpos(strtolower($layout['name']), 'product*') !== false) {
                    $area_type = 'product';
                }
            } else
            if ($area_id == 'product/product') {
                $area_type = 'product';
            }
        }

        if ($area_type == 'system') {
            $settings = $this->getSystemPageForRoute($area_id);
        } else
        if ($area_type == 'category') {
            $settings = $this->getSystemPageForRoute('product/category');
        } else
        if ($area_type == 'page') {
            $settings = $this->getSystemPageForRoute('information/information');
        }

        $extra_widgets = array();
        if ($area_type == 'product' || $area_type == 'quickview') {
            $settings = $this->getSystemPageForRoute('product/product');
            $product_tpl = file_get_contents(TB_Utils::vqmodCheck($this->context->getCatalogTemplateDir() . '/product/product.tpl'));
            preg_match_all('/tbData\->slotStart\(\'product\/.*?\.([^\']+)/', $product_tpl, $matches);

            if (isset($matches[1])) {
                $extra_widgets = $matches[1];
            }
        }

        $widget_slots = array();
        if (!empty($settings['widgets'])) {
            foreach ($settings['widgets'] as $key => $widget_config) {
                $areas = array_map('trim', explode(',', $widget_config['areas']));
                if (!in_array($area_name, $areas)) {
                    unset($settings['widgets'][$key]);
                } else {
                    $widget_slots[] = $settings['widgets'][$key]['slot'];
                }
            }
        } else {
            $settings['widgets'] = array();
        }

        foreach ($extra_widgets as $slot_name) {
            if (in_array($slot_name, $widget_slots) || $area_name != 'content') {
                continue;
            }

            $settings['widgets'][] = array(
                'label'  => ucwords(str_replace('_', ' ', $slot_name)),
                'slot'   => $slot_name,
                'areas'  => 'content',
                'locked' => true
            );

            $widget_slots[] = $slot_name;
        }

        $widgetsBag = new ArrayObject();
        $this->engine->getEventDispatcher()->notify(new sfEvent($widgetsBag, 'admin:resolveSystemWidgets', array(
            'area_name' => $area_name,
            'area_type' => $area_type,
            'area_id'   => $area_id
        )));

        foreach ($widgetsBag as $event_widget) {
            $settings['widgets'][] = $event_widget;
            $widget_slots[]        = $event_widget['slot'];
        }

        if (!empty($settings['merge_global']) || $area_type == 'global' || $area_type == 'home' || $area_type == 'layout') {
            $system_global = require $this->engine->getContext()->getConfigDir() . '/system_widgets/global.php';
            $global_widgets = array();

            if (!empty($system_global['widgets'])) {
                foreach ($system_global['widgets'] as $widget) {
                    if (in_array($area_name, array_map('trim', explode(',', $widget['areas'])))) {
                        $global_widgets[] = $widget;
                    }
                }
            }

            if ($area_type == 'global' || $area_type == 'layout' || $area_type == 'home') {
                if ($area_type != 'home' || $area_name != 'content' && $area_name != 'intro') {
                    $settings = array(
                        'route'   => 'default',
                        'widgets' => $global_widgets
                    );
                }
            } else
            if (!is_array($settings['merge_global']) || in_array($area_name, $settings['merge_global'])) {
                foreach ($global_widgets as $widget) {
                    if (!in_array($widget['slot'], $widget_slots)) {
                        array_unshift($settings['widgets'], $widget);
                    }
                }
            }
        }

        return $settings;
    }

    public function buildModifiedMenu($area_name, $type)
    {
        $settings_keys = array_values($this->engine->getDbSettingsHelper()->getKeys($this->context->getStoreId(), $type));

        if (!empty($area_name)) {
            foreach ($settings_keys as $key => $value) {
                if (0 !== strpos($value, $area_name) || 0 === strpos($value, $area_name . '_default')) {
                    unset($settings_keys[$key]);
                } else {
                    $settings_keys[$key] = str_replace($area_name . '_', '', $value);
                }
            }
        }

        $modified = array(
            'global' => array(
                'items' => array()
            ),
            'home' => array(
                'items' => array()
            ),
            'page' => array(
                'title' => 'Information Pages',
                'items' => array()
            ),
            'product' => array(
                'title' => 'Products',
                'items' => array()
            ),
            'category' => array(
                'title' => 'Categories',
                'items' => array()
            ),
            'layout' => array(
                'title' => 'Layouts',
                'items' => array()
            ),
            'system' => array(
                'title' => 'System pages',
                'items' => array()
            )
        );
        $has_modified = false;

        if (false !== array_search('global', $settings_keys)) {
            $modified['global']['items'][] = array(
                'value' => 'global',
                'label' => 'GLOBAL'
            );
            $has_modified = true;
        }

        if (false !== array_search('home', $settings_keys)) {
            $modified['home']['items'][] = array(
                'value' => 'home',
                'label' => 'Home'
            );
            $has_modified = true;
        }

        if (false !== array_search('product_global', $settings_keys)) {
            $modified['product']['items'][] = array(
                'value' => 'product_global',
                'label' => 'All products'
            );
            $has_modified = true;
        }

        if (false !== array_search('category_global', $settings_keys)) {
            $modified['category']['items'][] = array(
                'value' => 'category_global',
                'label' => 'All categories'
            );
            $has_modified = true;
        }

        foreach ($this->getModel('category')->getCategoryLevels() as $level) {
            if (false !== array_search('category_level_' . $level['level'], $settings_keys)) {
                $modified['category']['items'][] = array(
                    'label'      => 'Level ' . $level['level'] . ' categories',
                    'value'      => $level['value']
                );
                $has_modified = true;
            }
        }

        $categories_flat = $this->getModel('category')->getCategoriesFlatTree();
        $information_pages = $this->getThemeModel()->getInformationPages();
        $layouts = $this->getLayouts();

        foreach ($settings_keys as $key) {

            if (preg_match('/^page_(\d+)$/', $key, $matches) && isset($information_pages[$matches[1]])) {
                $modified['page']['items'][] = array(
                    'value' => $matches[1],
                    'label' => $information_pages[$matches[1]]['title']
                );
                $has_modified = true;
            }

            if (preg_match('/^category_(\d+)$/', $key, $matches) && isset($categories_flat[$matches[1]])) {
                $name = array();
                foreach (explode('_', $categories_flat[$matches[1]]['path']) as $category_id) {
                    $name[] =  $categories_flat[$category_id]['name'];
                }

                $modified['category']['items'][] = array(
                    'value' => $matches[1],
                    'label' => implode(' > ', $name)
                );
                $has_modified = true;
            }

            if (preg_match('/^layout_(\d+)$/', $key, $matches) && isset($layouts[$matches[1]])) {
                $modified['layout']['items'][] = array(
                    'value' => $matches[1],
                    'label' => $layouts[$matches[1]]['name']
                );
                $has_modified = true;
            }

            if (preg_match('/^system_(.*)$/', $key, $matches)) {
                $system_page = $this->getSystemPageForRoute($matches[1]);
                if (false !== $system_page && $system_page['display']) {
                    $modified['system']['items'][] = array(
                        'value' => $matches[1],
                        'label' => $system_page['label']
                    );
                    $has_modified = true;
                }
            }
        }

        if (!$has_modified) {
            $modified = array();
        }

        return $modified;
    }

    public function rebuildDefaultAreaSettings($store_id)
    {
        $store_theme = $this->engine->getDbSettingsHelper('setting')->getKey($this->context->getBasename() . '_theme', $store_id, $this->context->getBasename());
        $theme_settings = $this->engine->getThemeSettingsModel()->getScopeSettings($store_theme['id'], true, $store_id);

        $system_global = require $this->engine->getContext()->getConfigDir() . '/system_widgets/global.php';
        $theme_config = TB_EnvHelper::getInstance($this->registry)->getThemeConfig($store_theme['id']);

        if (isset($theme_config['builder_defaults'])) {
            if (empty($system_global['builder_defaults'])) {
                $system_global['builder_defaults'] = array();
            }
            $system_global['builder_defaults'] = array_merge($system_global['builder_defaults'], $theme_config['builder_defaults']);
        }

        if (!empty($system_global['builder_defaults'])) {
            foreach ($system_global['builder_defaults'] as $area_name => $area) {
                $global_rows = $this->createSystemGlobalRows($area, $area_name);
                $this->engine->getWidgetManager()->persistWidgets($global_rows, $area_name . '__default', $store_id);
            }
        }

        $system_routes = array();
        foreach ($this->getSystemPages() as $pages) {
            foreach ($pages as $page) {
                $system_routes[$page['route']] = !empty($page['ssl']) ? 1 : 0;
                if (!empty($page['append_defaults'])) {
                    foreach ($page['append_defaults'] as $area_name => $area) {
                        if (!empty($system_global['builder_defaults'][$area_name])) {
                            $area = array_merge_recursive($system_global['builder_defaults'][$area_name], $area);
                        }

                        $global_rows = $this->createSystemGlobalRows($area, $area_name);
                        $this->engine->getWidgetManager()->persistWidgets($global_rows, $area_name . '_system_' . $page['route'] . '__default', $store_id);
                    }
                }

                if (!empty($page['replace_defaults'])) {
                    foreach ($page['replace_defaults'] as $area_name => $area) {
                        $global_rows = $this->createSystemGlobalRows($area, $area_name);
                        $this->engine->getWidgetManager()->persistWidgets($global_rows, $area_name . '_system_' . $page['route'] . '__default', $store_id);
                    }
                }
            }
        }

        $theme_settings['system_routes'] = $system_routes;

        $this->engine->getThemeSettingsModel()->persistCustomSettings($theme_settings, $store_theme['id'], $store_id);

        if ($store_id == $this->context->getStoreId()) {
            $theme_settings = $this->engine->getThemeModel()->getSettings();
            $theme_settings['system_routes'] = $system_routes;
            $this->engine->getThemeModel()->setSettings($theme_settings);
        }
    }

    protected function createSystemGlobalRows($area, $area_name)
    {
        $settings = array();
        if (isset($area['settings'])) {
            $settings = $area['settings'];
        }

        $this->filterSettings($settings, 'area_' . $area_name, AreaItemData::getAreaColors($area_name));
        $area['settings'] = $settings;

        if (!isset($area['rows'])) {
            return $area;
        }

        $rows = array();
        $i = 0;
        foreach ($area['rows'] as $row) {
            $rows[$i]['id'] = isset($row['id']) ? $row['id'] : TB_Utils::genRandomString();
            $rows[$i]['columns_number'] = count($row['columns']);

            if (!isset($row['settings'])) {
                $row['settings'] = array();
            }
            //$row_defaults = array();
            $this->filterSettings($row['settings'], 'widgets_row', AreaItemData::getRowColors(), $area['settings']);
            $rows[$i]['settings'] = $row['settings'];
            //$rows[$i]['settings'] = array_merge_recursive_distinct($row_defaults, $row['settings']);

            $j = 0;
            foreach ($row['columns'] as $column) {
                $rows[$i]['columns'][$j]['id'] = isset($column['id']) ? $column['id'] : TB_Utils::genRandomString();
                $rows[$i]['columns'][$j]['grid_proportion'] = $column['grid_proportion'];
                $rows[$i]['columns'][$j]['widgets'] = array();

                if (!isset($column['settings'])) {
                    $column['settings'] = array();
                }

                $this->filterSettings($column['settings'], 'row_column', AreaItemData::getColumnColors(), $row['settings']);
                $rows[$i]['columns'][$j]['settings'] = $column['settings'];

                foreach ($column['widgets'] as $widget) {
                    $class = 'Theme_SystemWidget';
                    if (isset($widget['class'])) {
                        $class = $widget['class'];
                    }

                    $instance_settings = array();
                    if (isset($widget['settings'])) {
                        $instance_settings = $widget['settings'];
                    }

                    $instance_settings['is_active']   = 1;

                    if (substr($class, -12) == 'SystemWidget') {
                        $instance_settings['slot_prefix'] = isset($area['route']) ? $area['route'] : 'default';
                        $instance_settings['slot_name']   = $widget['slot'];
                    }

                    $widget_id = $class . '_' . (isset($widget['id']) ? $widget['id'] : TB_Utils::genRandomString(8));
                    $instance = $this->engine->getWidgetManager()->createWidgetFromId($widget_id, $instance_settings);
                    if (null === $instance) {
                        continue;
                    }

                    $instance->setName($widget['label']);

                    $instance_data = array(
                        'id'       => $widget_id,
                        'settings' => $instance->getSettings()
                    );

                    if (($instance instanceof Theme_GroupWidget || $instance instanceof Theme_BlockGroupWidget) && isset($widget['subwidgets'])) {
                        $instance_data['subwidgets'] = array();

                        foreach ($widget['subwidgets'] as $subwidget) {
                            $widget_class = substr($subwidget['id'], 0, strrpos($subwidget['id'], '_'));

                            if (substr($widget_class, -12) == 'SystemWidget') {
                                $subwidget['settings']['slot_prefix'] = isset($area['route']) ? $area['route'] : 'default';
                                $subwidget['settings']['slot_name']   = $subwidget['slot'];
                            }

                            $subwidget_instance = $this->engine->getWidgetManager()->createWidgetFromId($subwidget['id'], $subwidget['settings']);

                            if (null !== $subwidget_instance) {
                                $subwidget_instance->setName($subwidget['label']);
                                $instance->addSubWidget($subwidget_instance);

                                $instance_data['subwidgets'][] = array(
                                    'id'       => $subwidget_instance->getId(),
                                    'settings' => $subwidget_instance->getSettings()
                                );
                            }
                        }

                        if (!empty($instance_data['subwidgets']) && !empty($widget['subwidget_map']) && !empty($widget['section_keys'])) {
                            $instance->setSubwidgetMap($widget['subwidget_map']);
                            $instance->setSectionsKeys($widget['section_keys']);

                            $instance_data['subwidget_map'] = $widget['subwidget_map'];
                            $instance_data['section_keys']  = $widget['section_keys'];
                        } else {
                            unset($instance_data['subwidgets']);
                        }
                    }

                    $instances[$widget_id] = $instance;
                    $rows[$i]['columns'][$j]['widgets'][] = $instance_data;
                }
                $j++;
            }
            $i++;
        }

        unset($area['settings']);
        $area['rows'] = $rows;

        return $area;
    }

    public function filterAreaSettings(array &$area_settings, $area_name, array $theme_colors = array())
    {
        if ($area_name == 'area_column_left' || $area_name == 'area_column_right') {
            $area_name = 'area_content';
        }

        $this->filterSettings($area_settings, $area_name);
        TB_ColorSchemer::getInstance()
            ->filterThemeColors($theme_colors, $this->engine->getThemeData()->colors)
            ->filterAreaColors($area_settings['colors'], AreaItemData::getAreaColors($area_name), $area_name);
    }

    public function filterRowSettings(array &$row_settings, $area_name = '', array $area_settings = array(), array $theme_colors = array())
    {
        static $filtered_areas = array();

        if (!empty($area_name) && !isset($filtered_areas[$area_name])) {
            if ($area_name == 'area_column_left' || $area_name == 'area_column_right') {
                $area_name = 'area_content';
            }
            $this->filterAreaSettings($area_settings, $area_name, $theme_colors);
        }

        $this->filterSettings($row_settings, 'widgets_row');
        TB_ColorSchemer::getInstance()->filterRowColors($row_settings['colors'], AreaItemData::getRowColors());
    }

    public function filterColumnSettings(array &$column_settings, array $row_settings)
    {
        $this->filterSettings($column_settings, 'row_column', null, $row_settings);
        TB_ColorSchemer::getInstance()->filterColumnColors($column_settings['colors'], AreaItemData::getColumnColors());
    }

    public function getInformationLayoutId($information_id, $store_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->context->getStoreId();
        }

        $sql = "SELECT *
                FROM " . DB_PREFIX . "information_to_layout
                WHERE information_id = '" . (int) $information_id . "' AND store_id = '" . $store_id . "'";
        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getCategoryLayoutId($category_id, $store_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->context->getStoreId();
        }

        $sql = "SELECT *
                FROM " . DB_PREFIX . "category_to_layout
                WHERE category_id = '" . (int) $category_id . "' AND store_id = '" . $store_id . "'";
        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getLayout($route, $store_id = null)
    {

        if (null === $store_id) {
            $store_id = $this->context->getStoreId();
        }

        $sql = "SELECT *
                FROM " . DB_PREFIX . "layout_route
                WHERE '" . $this->db->escape($route) . "' LIKE CONCAT(route, '%') AND store_id = '" . $store_id . "'
                ORDER BY route DESC LIMIT 1";
        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }
}