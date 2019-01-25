<?php

class TB_WidgetManager
{
    /**
     * @var TB_Engine
     */
    protected $engine;
    protected $area_settings = array();
    protected $areas = array('header', 'content', 'footer', 'intro', 'column_left', 'column_right');
    protected $widgets_info = array();

    public function __construct(TB_Engine $engine)
    {
        $this->engine = $engine;
    }

    public function registerCoreWidgets()
    {
        $files = sfFinder::type('file')
            ->not_name('/^Abstract.*$/')
            ->name('/^[a-zA-Z]+Widget.php$/')
            ->sort_by_name()
            ->in($this->engine->getContext()->getWidgetsDir());

        foreach ($files as $filename) {
            $this->registerWidget($filename, 'Theme_' . basename(basename($filename), '.php'));
        }
    }

    public function registerWidget($file, $class_name, TB_Extension $extension = null, $allow_cache = true)
    {
        $name = TB_Utils::underscore(str_replace('Widget', '', substr($class_name, strrpos($class_name, '_') + 1)));
        $name = explode('_', $name);
        $name = implode(' ', array_map('ucfirst', $name));

        $this->widgets_info[$class_name] = array(
            'class'     => $class_name,
            'name'      => $name,
            'file'      => $file,
            'cache'     => $allow_cache,
            'system'    => TB_Utils::strEndsWith($class_name, 'SystemWidget'),
            'extension' => $extension
        );
    }

    /**
     * @return array
     */
    public function getWidgetsInfo()
    {
        $widgets_info_temp = array();

        foreach ($this->widgets_info as $info) {
            $widgets_info_temp[$info['name']] = $info;
        }

        ksort($widgets_info_temp);

        $this->widgets_info = array();

        foreach ($widgets_info_temp as $widget) {
            $this->widgets_info[$widget['class']] = $widget;
        }

        return $this->widgets_info;
    }


    /**
     * @param string $name
     * @throws Exception
     * @return TB_Widget[]
     */
    public function getWidgetsByArea($name)
    {
        $result = array();

        foreach ($this->widgets_info as $info) {
            $widget = $this->createWidgetInstance($info['class'], $info['file']);

            if ($widget->hasArea($name)) {
                $settings = array();
                $this->filterWidgetInstance($widget, $settings);
                $this->initWidgetInstance($widget, $settings);

                $result[] = $widget;
            }
        }

        return array_merge($result, $this->caveOpenCartWidgets());
    }

    public function loadWidgetClasses()
    {
        if (defined('TB_CLASS_CACHE')) {
            return;
        }

        $abstract = sfFinder::type('file')
            ->name('/^Abstract[a-zA-Z]+Widget.php$/')
            ->in($this->engine->getContext()->getWidgetsDir());

        foreach ($abstract as $filename) {
            require_once tb_modification($filename);
        }

        foreach ($this->widgets_info as $info) {
            require_once tb_modification($info['file']);
        }
    }

    public function loadAreasData(array $builder_keys, array $style_keys = array())
    {
        $db_keys = array();

        foreach (array('builder', 'style') as $type) {
            foreach (${$type . '_keys'} as $key) {
                foreach ($this->areas as $area) {
                    if (0 === strpos($key, $area)) {
                        $db_keys[$type][] = $key;
                    }
                }
            }
        }

        $result = array(
            'area'          => array(),
            'area_settings' => array()
        );

        if (empty($db_keys)) {
            return $result;
        }

        $records = $this->engine->getDbSettingsHelper()->getKeysGroupCollection($db_keys, $this->engine->getCurrentStoreId());

        foreach ($records as $key => $record) {
            $scope = (0 === strpos($key, 'style_')) ? 'area_settings' : 'area';
            $value = str_replace(array('style_', 'builder_'), '', $key);

            foreach ($this->areas as $area) {
                if (0 === strpos($value, $area)) {
                    $result[$scope][$area] = substr($value, strlen($area) + 1);
                } else
                if ($value == 'default_' . $area) {
                    $result[$scope][$area] = '_default';
                }
            }

            if ($scope == 'area') {
                $this->engine->getBuilderSettingsModel()->setScopeSettings($value, $record);
            } else {
                $this->engine->getStyleSettingsModel()->setScopeSettings($value, $record);
            }
        }

        return $result;
    }

    public function areaKeyExists($area_key, $group, $store_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->engine->getContext()->getStoreId();
        }

        return $this->engine->getDbSettingsHelper()->keyExists($area_key, $store_id, $group);
    }

    public function getAreaSettings($area_key, $type, $store_id = null, $raw = false)
    {
        if (null === $store_id) {
            $store_id = $this->engine->getContext()->getStoreId();
        }

        return $this->engine->getSettingsModel($type, $store_id)->getScopeSettings($area_key, $raw);
    }

    public function getAreaStyle($area_key, $store_id = null, $raw = false)
    {
        return $this->getAreaSettings($area_key, 'style', $store_id, $raw);
    }

    public function getAreaBuilder($area_key, $store_id = null, $raw = false)
    {
        return $this->getAreaSettings($area_key, 'builder', $store_id, $raw);
    }

    public function removeWidgetArea($area_key, $type)
    {
        $this->engine->getSettingsModel($type)->deleteScopeSettings($area_key);
    }

    public function caveOpenCartWidgets()
    {
        static $widgets = null;

        if (null !== $widgets) {
            return $widgets;
        }

        if (!class_exists('Theme_OpenCartWidget')) {
            require_once tb_modification($this->engine->getContext()->getWidgetsDir() . '/OpenCartWidget.php');
        }

        $widgets = $this->engine->gteOc2() ? $this->caveOC2Widgets() : $this->caveOC1Widgets();

        return $widgets;
    }

    protected function caveOC1Widgets()
    {
        $widgets = array();
        $layout_id = $this->engine->getThemeModel()->getLayoutIdByName('TB_Widgets');

        foreach ($this->engine->getThemeModel()->getInstalledOcModules() as $extension) {
            if ($modules = $this->engine->getOcConfig()->get($extension . '_module')) {
                $sort_orders = array();

                foreach ($modules as $settings) {
                    $layouts = (array) $settings['layout_id'];
                    if (strlen($settings['sort_order']) && !in_array($settings['sort_order'], $sort_orders) && $settings['status'] && in_array($layout_id, $layouts)) {

                        $widget = new Theme_OpenCartWidget($this->engine, $this);
                        $widget->setName('OC ' . ucfirst($extension) . ' ' . $settings['sort_order']);
                        $widget->setSettings(array(
                            'code'       => $extension,
                            'position'   => $settings['position'],
                            'sort_order' => $settings['sort_order']
                        ));

                        $widgets[] = $widget;
                        $sort_orders[] = $settings['sort_order'];
                    }
                }
            }
        }

        return $widgets;
    }

    protected function caveOC2Widgets()
    {
        $layout_id = $this->engine->getThemeModel()->getLayoutIdByName('TB_Widgets');
        if (!$layout_modules = $this->engine->getOcModel('design/layout')->getLayoutModules($layout_id)) {
            return array();
        }

        $widgets = array();
        $sort_orders = array();

        foreach ($layout_modules as $module) {

            if (!isset($module['code']) || !strlen($module['sort_order'])) {
                continue;
            }

            $parts = explode('.', $module['code']);
            $type = $parts[0];

            if (!isset($sort_orders[$type])) {
                $sort_orders[$type] = array();
            }

            if(empty($type) || in_array($module['sort_order'], $sort_orders[$type]) || (!isset($parts[1]) && !$this->engine->getOcConfig($type . '_status'))) {
                continue;
            }

            if (isset($part[1]) && !(($module_settings = $this->engine->getOcModel('extension/module')->getModule($part[1])) || !$module_settings['status'])) {
                continue;
            }

            $widget = new Theme_OpenCartWidget($this->engine, $this);
            $widget->setName('OC ' . ucfirst($type) . ' ' . $module['sort_order']);
            $widget->setSettings(array(
                'code'       => $module['code'],
                'position'   => $module['position'],
                'sort_order' => $module['sort_order']
            ));

            $widgets[] = $widget;
            $sort_orders[$type][] = $module['sort_order'];
        }

        return $widgets;
    }

    public function persistWidgets(array $widgets_data, $area_key, $store_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->engine->getContext()->getStoreId();
        }

        /** @var Theme_Admin_LayoutBuilderModel $layoutBuilderModel */
        $layoutBuilderModel = $this->engine->getThemeExtension()->getModel('layoutBuilder');

        $area_settings = array();
        if (isset($widgets_data['settings'])) {
            // Used only when importing config/system_widgets
            if (!empty($widgets_data['settings'])) {
                $area_settings = $widgets_data['settings'];
                if (empty($area_settings['cleaned'])) {
                    $layoutBuilderModel->cleanSettingsDataBeforePersist($area_settings);
                }
                unset($area_settings['cleaned']);
            }
            unset($widgets_data['settings']);
        }

        if (!empty($widgets_data['rows'])) {
            foreach ($widgets_data['rows'] as &$row) {

                if (empty($row['settings']['cleaned'])) {
                    $layoutBuilderModel->cleanSettingsDataBeforePersist($row['settings']);
                }
                unset($row['settings']['cleaned']);

                if (isset($row['columns']) && !empty($row['columns'])) {
                    foreach ($row['columns'] as &$column) {

                        if (empty($column['settings']['cleaned'])) {
                            $layoutBuilderModel->cleanSettingsDataBeforePersist($column['settings']);
                        }
                        unset($column['settings']['cleaned']);

                        if (!empty($column['widgets'])) {

                            foreach ($column['widgets'] as &$widget_form) {

                                $class_name = substr($widget_form['id'], 0, strrpos($widget_form['id'], '_'));
                                $widget = $this->createWidgetInstance($class_name);
                                $widget->setId($widget_form['id']);

                                if (($widget instanceof Theme_GroupWidget || $widget instanceof Theme_BlockGroupWidget) && isset($widget_form['subwidgets'])) {
                                    foreach ($widget_form['subwidgets'] as &$subwidget_form) {
                                        $class_name = substr($subwidget_form['id'], 0, strrpos($subwidget_form['id'], '_'));
                                        $subWidget = $this->createWidgetInstance($class_name);
                                        $subWidget->setId($subwidget_form['id']);
                                        $subWidget->setParent($widget);

                                        $this->filterWidgetInstance($subWidget, $subwidget_form['settings']);
                                        $subWidget->onPersistSystem($subwidget_form['settings']);

                                        if (is_callable(array($subWidget, 'onPersist'))) {
                                            $subWidget->onPersist($subwidget_form['settings']);
                                        }
                                    }
                                }

                                $this->filterWidgetInstance($widget, $widget_form['settings']);

                                $widget->onPersistSystem($widget_form['settings']);

                                if (is_callable(array($widget, 'onPersist'))) {
                                    $widget->onPersist($widget_form['settings']);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (isset($widgets_data['rows'])) {
            $this->engine->getBuilderSettingsModel()->setAndPersistScopeSettings($area_key, $widgets_data, $store_id);
        }

        if (!empty($area_settings)) {
            $this->engine->getStyleSettingsModel()->setAndPersistScopeSettings($area_key, $area_settings, $store_id);
        }
    }

    /**
     * @param string $id
     * @param array $settings
     * @return TB_Widget
     */
    public function createWidgetFromId($id, array $settings = array())
    {
        $widget_class = substr($id, 0, strrpos($id, '_'));

        try {
            $widget = $this->createWidget($widget_class, $settings);
            $widget->setId($id);
        } catch (Exception $e) {
            $this->engine->fbLog('Trying to create non-existing widget ' . $id);
            $widget = null;
        }

        return $widget;
    }

    /**
     * @param string $id
     * @param array $settings
     * @return TB_Widget
     */
    public function createAndFilterWidgetFromId($id, array $settings = array(), $area)
    {
        try {
            $widget = $this->createWidgetInstance(substr($id, 0, strrpos($id, '_')));
            $widget->setId($id);
            $this->filterWidgetInstance($widget, $settings, $area);
            $widget->setSettings($settings);
            $this->initWidgetInstance($widget, $settings);
        } catch (Exception $e) {
            $this->engine->fbLog('Trying to create non-existing widget ' . $id);
            $widget = null;
        }

        return $widget;

    }

    /**
     * @param $class_name
     * @param array $settings
     *
     * @return TB_Widget
     */
    public function createAndFilterWidget($class_name, array $settings = array())
    {
        $widget = $this->createWidgetInstance($class_name);
        $this->filterWidgetInstance($widget, $settings);
        $widget->setSettings($settings);

        $this->initWidgetInstance($widget, $settings);

        return $widget;
    }

    /**
     * @param $class_name
     * @param array $settings
     *
     * @return TB_Widget
     */
    public function createFilterAndEditWidget($class_name, array $settings = array())
    {
        $widget = $this->createWidgetInstance($class_name);
        $this->filterWidgetInstance($widget, $settings);
        $widget->setSettings($settings);

        $widget->onEditSystem($settings);
        if (is_callable(array($widget, 'onEdit'))) {
            $widget->onEdit($settings);
        }

        $this->initWidgetInstance($widget, $settings);

        return $widget;
    }

    /**
     * @param $class_name
     * @param array $settings
     *
     * @return TB_Widget
     */
    public function createTransformAndFilterWidget($class_name, array $settings)
    {
        $widget = $this->createWidgetInstance($class_name);
        if (is_callable(array($widget, 'onTransformSettings'))) {
            $widget->onTransformSettings($settings);
        }
        $this->filterWidgetInstance($widget, $settings);
        $this->initWidgetInstance($widget, $settings);

        return $widget;
    }

    /**
     * @param string $widget_class
     * @param string|array $settings
     * @return TB_Widget
     * @throws Exception
     */
    public function createWidget($widget_class, $settings = array())
    {
        $widget = $this->createWidgetInstance($widget_class);
        $this->initWidgetInstance($widget, $settings);

        return $widget;
    }

    /**
     * @param $widget_class
     * @param null $widget_file
     * @return TB_Widget
     * @throws Exception
     */
    public function createWidgetInstance($widget_class, $widget_file = null)
    {
        if (!class_exists($widget_class)) {
            if (null === $widget_file) {
                $widget_file = $this->engine->getContext()->getWidgetsDir() . '/' . str_replace('Theme_', '', $widget_class) . '.php';
            }

            if (!file_exists($widget_file)) {
                throw new Exception ('The widget file cannot be found: ' . $widget_file);
            }

            require_once tb_modification($widget_file);
        }

        if (!isset($this->widgets_info[$widget_class])) {
            throw new Exception ('The widget has not been registered: ' . $widget_class);
        }

        /** @var TB_Widget $widget */
        $widget = new $widget_class($this->engine, $this);

        $this->setWidgetExtension($widget);

        if (is_callable(array($widget, 'onCreate'))) {
            $widget->onCreate();
        }

        return $widget;
    }

    public function setWidgetExtension(TB_Widget $widget)
    {
        $extension = $this->widgets_info[get_class($widget)]['extension'];

        if (null === $extension) {
            $extension = $this->engine->getThemeExtension();
        }

        $widget->setExtension($extension);
    }

    public function filterWidgetInstance(TB_Widget $widget, array &$settings, $area = 'admin')
    {
        $widget->onFilterSystem($settings, $area);
        if (is_callable(array($widget, 'onFilter'))) {
            $widget->onFilter($settings);
        }
    }

    /**
     * @param TB_Widget $widget
     * @param array $settings
     */
    protected function initWidgetInstance(TB_Widget $widget, array $settings)
    {
        if (is_callable(array($widget, 'onInit'))) {
            $widget->onInit($settings);
        }
        $widget->setSettings($settings);
    }

    /**
     * @param string|array $settings
     * @return TB_Widget[]
     * @throws Exception
     */
    public function createSystemWidgets($settings)
    {
        $result = array();

        if (!isset($settings['widgets']) || !is_array($settings['widgets'])) {
            return $result;
        }

        foreach ($settings['widgets'] as $widget_config) {
            $class = isset($widget_config['class']) ? $widget_config['class'] : 'Theme_SystemWidget';
            $widget = $this->createWidget($class, array(
                'is_active'   => 1,
                'slot_prefix' => $settings['route'],
                'slot_name'   => $widget_config['slot']
            ));
            $widget->setName($widget_config['label']);

            if (isset($widget_config['locked']) && $widget_config['locked']) {
                $widget->lock();
            }

            $result[] = $widget;
        }

        return $result;
    }
}