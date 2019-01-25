<?php

class TB_WidgetsArea
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var TB_WidgetManager
     */
    protected $widgetManager;

    /**
     * @var TB_Widget[]
     */
    protected $widgets = array();

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $js_contents = '';

    public function __construct($name, TB_WidgetManager $widgetManager, array $attributes = array())
    {
        $this->name = $name;
        $this->widgetManager = $widgetManager;
        $this->attributes    = $attributes;
    }

    public function createFromArray(array $data)
    {
        if (!isset($data['rows']) || empty($data['rows'])) {
            return;
        }

        foreach ($data['rows'] as &$row) {
            foreach ($row['columns'] as &$column) {
                if (isset($column['widgets'])) {
                    foreach ($column['widgets'] as $key => $value) {
                        $widget = $this->widgetManager->createAndFilterWidgetFromId($value['id'], $value['settings'], 'catalog');
                        if (null === $widget) {
                            unset($column['widgets'][$key]);
                            continue;
                        }
                        if ($widget->isActive()) {
                            $this->addWidget($widget);

                            if (($widget instanceof Theme_GroupWidget || $widget instanceof Theme_BlockGroupWidget) && isset($value['subwidgets'])) {
                                foreach ($value['subwidgets'] as $vkey => $vvalue) {
                                    $subWidget = $this->widgetManager->createAndFilterWidgetFromId($vvalue['id'], $vvalue['settings'], 'catalog');

                                    if (null === $subWidget) {
                                        unset($column['widgets'][$key]['subwidgets'][$vkey]);
                                        continue;
                                    }

                                    if ($subWidget->isActive()) {
                                        $subWidget->setParent($widget);
                                        $widget->addSubWidget($subWidget);
                                        $this->addWidget($subWidget);
                                    }
                                }

                                if (!empty($value['subwidget_map']) && !empty($value['section_keys'])) {
                                    $widget->setSubwidgetMap($value['subwidget_map']);
                                    $widget->setSectionsKeys($value['section_keys']);
                                }
                            }
                            $column['widgets'][$key] = $widget;
                        } else {
                            unset($column['widgets'][$key]);
                        }
                    }
                }
            }
        }

        $this->data = $data;
    }

    public function addAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function getAttribute($key)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function  getSystemSlots()
    {
        return $this->getAttribute('system_slots');
    }

    /**
     * Executed on every request
     */
    public function initWidgets(TB_ViewDataBag $themeData)
    {
        $system_slots = array();

        foreach ($this->widgets as $widget) {

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

                if (isset($settings['lang'])) {
                    if (isset($settings['lang'][$themeData->language_code])) {
                        $settings['lang'] = $settings['lang'][$themeData->language_code];
                    } else {
                        $settings['lang'] = reset($settings['lang']);
                    }
                }

                if (isset($themeData['system.' . $widget->getSlotName()])) {
                    $themeData['system.' . $widget->getSlotName()] = array_merge($themeData['system.' . $widget->getSlotName()], $settings);
                } else {
                    $themeData['system.' . $widget->getSlotName()] = $settings;
                }
                $themeData[$widget->getSlotFullName()] = $themeData['system.' . $widget->getSlotName()];

                $system_slots[] = $widget->getSlotName();
            }
        }

        $this->addAttribute('system_slots', $system_slots);
    }

    /**
     * Executed on cache refresh only
     */
    public function configureWidgets()
    {
        foreach ($this->widgets as $widget) {
            $this->configureWidget($widget);
        }
    }

    public function configureWidget(TB_Widget $widget)
    {
        if (method_exists($widget, 'configureFrontend')) {
            $widget->configureFrontend();
            $widget->addAttribute('configured', 1);
        }
    }

    public function configureWidgetById($widget_id)
    {
        if (isset($this->widgets[$widget_id])) {
            $this->configureWidget($this->widgets[$widget_id]);
        }
    }


    protected function addWidget(TB_Widget $widget)
    {
        $this->widgets[$widget->getId()] = $widget;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return TB_Widget[]
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * @param $id
     * @throws Exception
     * @return null|TB_Widget
     */
    public function getWidget($id)
    {
        if (!isset($this->widgets[$id])) {
            throw new Exception('Trying to access non existing widget ' . $id);
        }

        return isset($this->widgets[$id]) ? $this->widgets[$id] : null;
    }

    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getData($key = null)
    {
        if (null == $key) {
            return $this->data;
        }

        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function addJsContents($contents)
    {
        $this->js_contents .= "\n" . $contents;
    }

    public function getJsContents()
    {
        return $this->js_contents;
    }

    public function getSettings()
    {
        return (isset($this->data['settings'])) ? $this->data['settings'] : array();
    }

    public function getSetting($key, $default = null)
    {
        return isset($this->data['settings'][$key]) ? $this->data['settings'][$key] : $default;
    }

    public function getRows()
    {
        return (isset($this->data['rows'])) ? $this->data['rows'] : array();
    }

    public function __sleep()
    {
        if (isset($this->data['rows'])) {
            foreach ($this->data['rows'] as &$row) {
                unset(
                    $row['settings']['colors'],
                    $row['settings']['font'],
                    $row['settings']['border'],
                    $row['settings']['border_radius'],
                    $row['settings']['box_shadow']
                );
            }
        }

        unset(
            $this->data['settings']['colors'],
            $this->data['settings']['font'],
            $this->data['settings']['border'],
            $this->data['settings']['border_radius'],
            $this->data['settings']['box_shadow']
        );

        return array('name', 'data');
    }

    public function __wakeup()
    {
        $this->widgetManager = TB_Engine::instance()->getWidgetManager();

        if (!isset($this->data['rows'])) {
            return;
        }

        foreach ($this->data['rows'] as &$row) {
            foreach ($row['columns'] as &$column) {
                if (isset($column['widgets'])) {
                    foreach ($column['widgets'] as $widget) {
                        $this->widgetManager->setWidgetExtension($widget);
                        $this->addWidget($widget);

                        if ($widget instanceof Theme_GroupWidget || $widget instanceof Theme_BlockGroupWidget) {
                            foreach ($widget->getSubWidgets() as $subWidget) {
                                $subWidget->setParent($widget);
                                $this->widgetManager->setWidgetExtension($subWidget);
                                $this->addWidget($subWidget);
                            }
                        }
                    }
                }
            }
        }
    }
}