<?php

class Theme_BlockGroupWidget extends TB_Widget
{
    protected $areas = array('header', 'footer', 'intro', 'content', 'column_left', 'column_right');

    /**
     * @var TB_Widget[]
     */
    protected $widgets = array();

    /**
     * @var array
     */
    protected $subwidget_map = array();

    /**
     * @var array
     */
    protected $section_keys = array();

    public function getName()
    {
        return 'Block Group';
    }

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active' => 1,
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'section_titles' => array(),
            'columns_gutter' => 30,
            'equal_columns'  => true
        ), $settings));
    }

    public function render(array $view_data = array())
    {
        if (!($sections = $this->getSectionsContent())) {
            return parent::renderContent('');
        }

        $view_data['sections'] = $sections;

        return parent::render($view_data);
    }

    public function informChildrenOnRender()
    {
        return false;
    }

    protected function getSectionsContent()
    {
        $subwidgets_content = array();
        foreach ($this->getSubWidgets() as $widget) {
            $widget_content = $widget->render();
            if ($widget->hasContent()) {
                if ($widget->getAttribute('renderPlaceHolder')) {
                    $subwidgets_content[$widget->getId()] = '{{' . $widget->getId() . '}}';
                } else {
                    $subwidgets_content[$widget->getId()] = $widget_content;
                }
            }
        }

        if (empty($subwidgets_content)) {
            return false;
        }

        $sections = array();
        $titles = $this->getSectionTitles();
        $subwidget_map = $this->getSubWidgetMap();

        foreach ($this->getSectionKeys() as $key) {
            if (isset($subwidget_map[$key])) {
                $section = $titles[$key];
                $section['widgets'] = array();

                foreach ($subwidget_map[$key] as $widget_id) {
                    if (isset($subwidgets_content[$widget_id])) {
                        $section['widgets'][$widget_id] = $subwidgets_content[$widget_id];
                    }
                }

                if (!empty($section['widgets'])) {
                    $sections[$key] = $section;
                }
            }
        }

        return $sections;
    }

    protected function getSectionTitles()
    {
        $titles = array();
        $i = 0;

        foreach ($this->getSectionKeys() as $key) {
            if (isset($this->settings['section_titles'][$key])) {
                $titles[$key] = array(
                    'width_xs' => $this->settings['section_titles'][$key]['width_xs'],
                    'width_sm' => $this->settings['section_titles'][$key]['width_sm'],
                    'width_md' => $this->settings['section_titles'][$key]['width_md'],
                    'halign'   => $this->settings['section_titles'][$key]['halign'],
                    'valign'   => $this->settings['section_titles'][$key]['valign']
                );
            } else {
                $titles[$key] = array(
                    'width_xs' => 12,
                    'width_sm' => 12,
                    'width_md' => 12,
                    'halign'   => 'start',
                    'valign'   => 'top'
                );
            }

            $i++;
        }

        return $titles;
    }

    protected function getBoxClasses()
    {
        $classes = parent::getBoxClasses();

        if ($this->settings['equal_columns']) {
            $classes .= ' tb_equal_columns';
        }

        return $classes;
    }

    public function setSubWidgets(array $widgets)
    {
        foreach ($widgets as $widget) {
            $this->addSubWidget($widget);
        }
    }

    public function addSubWidget(TB_Widget $widget)
    {
        $this->widgets[$widget->getId()] = $widget;
    }

    public function getSubWidgets()
    {
        return $this->widgets;
    }

    public function setSubwidgetMap(array $map)
    {
        $this->subwidget_map = $map;
    }

    public function getSubWidgetMap()
    {
        return $this->subwidget_map;
    }

    public function getSectionTitlesMap()
    {
        $titles_map = array();

        foreach ($this->settings['section_titles'] as $key => $data) {
            if (isset($data['lang'][$this->language_code])) {
                $titles_map[$key] = $data['lang'][$this->language_code]['title'];
            }
        }

        return $titles_map;
    }

    public function setSectionsKeys(array $keys)
    {
        $this->section_keys = $keys;
    }

    public function getSectionKeys()
    {
        return $this->section_keys;
    }

    public function __sleep()
    {
        return array_merge(parent::__sleep(), array('widgets', 'subwidget_map', 'section_keys'));
    }


    public function hasTitleStyles()
    {
        return false;
    }
}
