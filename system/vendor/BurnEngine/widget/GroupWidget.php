<?php

class Theme_GroupWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');

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
        return 'Tabs/Accordion';
    }

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => '',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'section_titles'    => array(),
            'group_type'        => 'tabs',
            'tabs_direction'    => 'horizontal',
            'tabs_position'     => 'left',
            'tabs_width'        => 140,
            'tabs_style'        => 2,
            'tabs_align'        => 'start',         // start, center, end
            'tabs_justify'      => 0,
            'tabs_transition'   => 'none',
            'accordion_style'   => 1,
            'accordion_closed'  => 0,
            'nav_padding'       => 20,
            'nav_spacing'       => 20,
            'content_padding_top'  => 0,
            'content_padding_side' => 0,
            'auto_height'       => 0
        ), $settings));
    }

    public function render(array $view_data = array())
    {
        if (!($sections = $this->getSectionsContent())) {
            return parent::renderContent('');
        }

        $box_layout = $this->settings['box_styles']['layout'];

        $group_classes  = 'tb_' . $this->settings['group_type'];

        if ($this->settings['group_type'] == 'tabs') {
            $group_classes .= ' tbWidgetTabs';
            if ($this->settings['tabs_direction'] == 'vertical') {
                $group_classes .= ' tabs-' . $this->settings['tabs_position'];
            }
        }
        if ($this->settings['group_type'] == 'accordion') {
            $group_classes .= ' tb_style_' . $this->settings['accordion_style'];
            $group_classes .= ' tbWidgetAccordion';
        }

        $nav_classes  = 'tb_style_' . $this->settings['tabs_style'];

        if ($this->settings['tabs_direction'] == 'horizontal') {
            $nav_classes .= ' htabs';
            $nav_classes .= $this->settings['tabs_style'] != 2 && $this->settings['tabs_justify'] ? ' nav-justified' : '';
            $nav_classes .= $this->settings['tabs_style'] != 1 ? ' nav-tabs-align-' . $this->settings['tabs_align'] : '';
            $nav_classes .= $this->settings['tabs_style'] != 3 ? ' ' . $this->getDistanceClasses('title') : '';
            if ($this->settings['tabs_style'] == 3) {
                $nav_classes .= $box_layout['padding_top']    != 0 ? ' tb_pt_' . $box_layout['padding_top']    : '';
                $nav_classes .= $box_layout['padding_right']  != 0 ? ' tb_pr_' . $box_layout['padding_right']  : '';
                $nav_classes .= $box_layout['padding_bottom'] != 0 ? ' tb_pb_' . $box_layout['padding_bottom'] : '';
                $nav_classes .= $box_layout['padding_left']   != 0 ? ' tb_pl_' . $box_layout['padding_left']   : '';
            };
        } else {
            $nav_classes .= ' vtabs';
            $nav_classes .= $this->settings['tabs_style'] == 3 ? ' nav-stacked' : '';
            $nav_classes .= ' ' . $this->getDistanceClasses('title');
        }

        $tabs_nav_style = '';

        if ($this->settings['tabs_direction'] == 'vertical') {
            $tabs_nav_style .= 'width: ' . ($this->settings['tabs_width'] - 1) . 'px;';
            $tabs_nav_style .= ' min-width: ' . ($this->settings['tabs_width'] - 1) . 'px;';
        }

        $has_icon = false;

        foreach ($sections as $section) {
            if (!empty($section['icon'])) {
                $has_icon = true;
            }
        }

        $fade = $this->settings['tabs_transition'] == 'fade';

        $widget_settings  = '{';
        $widget_settings .= 'auto_height: ' . $this->settings['auto_height'] . ',';
        $widget_settings .= 'has_icon:    ' . (int) $has_icon . ',';
        $widget_settings .= 'fade:        ' . (int) $fade . ',';
        $widget_settings .= 'closed:      ' . $this->settings['accordion_closed'];
        $widget_settings .= '}';

        $view_data['sections']        = $sections;
        $view_data['group_classes']   = $group_classes;
        $view_data['nav_classes']     = $nav_classes;
        $view_data['tabs_nav_style']  = $tabs_nav_style;
        $view_data['tabs_fade']       = $this->settings['tabs_transition'] == 'fade';
        $view_data['widget_settings'] = $widget_settings;

        return parent::render($view_data);
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
            if (isset($this->settings['section_titles'][$key]['lang'][$this->language_code])) {
                $titles[$key] = array(
                    'title'     => $this->settings['section_titles'][$key]['lang'][$this->language_code]['title'],
                    'icon'      => $this->settings['section_titles'][$key]['icon'],
                    'icon_size' => $this->settings['section_titles'][$key]['icon_size']
                );
            } else {
                $titles[$key] = array(
                    'title'     => '',
                    'icon'      => '',
                    'icon_size' => ''
                );
            }

            $i++;
        }

        return $titles;
    }

    protected function getBoxClasses()
    {
        $lazyload = $this->themeData->system['js_lazyload'];
        $classes  = parent::getBoxClasses();

        if ($this->settings['group_type'] == 'tabs') {
            $classes = rtrim($classes);
        }

        $classes .= $lazyload ? ' lazyload' : '';

        return $classes;
    }

    protected function getBoxData()
    {
        $lazyload = $this->themeData->system['js_lazyload'];
        $data     = parent::getBoxData();
        $data    .= $lazyload ? ' data="' . $this->themeData->system['js_lazyload_expand'] . '"' : '';

        return $data;
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

    public function getDefaultBoxFonts()
    {
        return array();
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $css = '';

        // Pills

        if ($this->settings['tabs_style'] == 3 && $this->settings['tabs_direction'] == 'horizontal' && !empty($this->settings['nav_spacing'])) {
            $css .= '#' . $this->getDomId() . ' > .tb_tabs > .nav {';
            $css .= '  margin-left: -'  . $this->settings['nav_spacing'] . 'px;';
            $css .= '  margin-right: -' . $this->settings['nav_spacing'] . 'px;';
            $css .= '  padding-right: ' . $this->settings['nav_spacing'] . 'px;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' > .tb_tabs > .nav > li {';
            $css .= '  margin-left: ' . $this->settings['nav_spacing'] . 'px;';
            $css .= '}';
        }

        if ($this->settings['tabs_style'] == 3 && $this->settings['tabs_direction'] == 'vertical' && !empty($this->settings['nav_spacing'])) {
            $css .= '#' . $this->getDomId() . ' > .tb_tabs > .nav > li + li {';
            $css .= '  margin-top: ' . $this->settings['nav_spacing'] . 'px;';
            $css .= '}';
        }

        $styleBuilder->addCss($css);
    }

    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'toolbar_tabs' => array(
                '_label' => 'Tabs',
                'content_border' => array(
                    'label'       => 'Content border',
                    'elements'    => '
                        .tab-content
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.content_border'
                )
            ),
            'toolbar_accordion' => array(
                '_label' => 'Accordion',
                'content_border' => array(
                    'label'       => 'Content border',
                    'elements'    => '
                        .tb_accordion,
                        .panel-collapse,
                        .tb_title + div,
                        .ui-accordion > .ui-widget-content
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.content_border'
                )
            )
        );

        return $default_colors;
    }

    public function getDefaultTitleFonts()
    {
        $default_fonts = array(
            'tabs_navigation' => array(
                'section_name'      => 'Tabs / Accordion',
                'elements'          => '
                    .nav.nav-tabs > li,
                    .nav.nav-tabs > li > a,
                    .tb_accordion_content > .tb_title,
                    .panel-group > .panel > .panel-heading > .panel-title,
                    .ui-accordion-header,
                    .tb_slider_controls .tb_prev,
                    .tb_slider_controls .tb_next
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 18,
                'line-height'       => 20,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            )
        );

        return $default_fonts;
    }

    public function getDefaultTitleColors()
    {
        $default_colors = array(
            'toolbar_tabs' => array(
                '_label' => 'Tabs',
                'header_bg' => array(
                    'label'       => 'Toolbar bg',
                    'elements'    => '
                        .nav.nav-tabs
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e6e6e6',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.header_bg'
                ),
                'header_border' => array(
                    'label'       => 'Toolbar border',
                    'elements'    => '
                        .nav.nav-tabs
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.header_border'
                ),
                'clickable_text' => array(
                    'label'       => 'Clickable text',
                    'elements'    => '
                        .nav.nav-tabs:not(.tb_style_2) > li:not(:hover):not(.active):not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active),
                        .nav.nav-tabs.tb_style_2 > li,
                        .nav.nav-tabs.tb_style_2 > li:not(:hover):not(.active):not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active) > a
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_text'
                ),
                'clickable_bg' => array(
                    'label'       => 'Clickable bg',
                    'elements'    => '
                        .nav.nav-tabs > li:not(:hover):not(.active):not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_bg_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e6e6e6',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_bg'
                ),
                'clickable_border' => array(
                    'label'       => 'Clickable border',
                    'elements'    => '
                        .nav.nav-tabs > li:not(:hover):not(.active):not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_border_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_border'
                ),
                'clickable_icon' => array(
                    'label'       => 'Clickable icon',
                    'elements'    => '
                        .nav.nav-tabs > li:not(:hover):not(.active):not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_bg_str_"]) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'toolbar_tabs.clickable_text'
                ),
                'clickable_text_hover' => array(
                    'label'       => 'Clickable text (hover)',
                    'elements'    => '
                        .nav.nav-tabs:not(.tb_style_2) > li:hover:not(.active):not(.ui-state-active):not([class*="tb_text_hover_str_"]),
                        .ui-tabs-nav:not(.tb_style_2) .ui-state-focus:not(.ui-state-active):not([class*="tb_text_hover_str_"]),
                        .nav.nav-tabs.tb_style_2 > li:not(.active):not(.ui-state-active):hover > a
                    ',
                    'property'    => 'color',
                    'color'       => '#111111',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_text_hover'
                ),
                'clickable_bg_hover' => array(
                    'label'       => 'Clickable bg (hover)',
                    'elements'    => '
                        .nav.nav-tabs > li:hover:not(.active):not(.ui-state-active):not([class*="tb_bg_hover_str_"]),
                        .ui-tabs-nav .ui-state-focus:not(.ui-state-active):not([class*="tb_bg_hover_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_bg_hover'
                ),
                'clickable_border_hover' => array(
                    'label'       => 'Clickable border (hover)',
                    'elements'    => '
                        .nav.nav-tabs > li:hover:not(.active):not(.ui-state-active):not([class*="tb_border_hover_str_"]),
                        .ui-tabs-nav .ui-state-focus:not(.ui-state-active):not([class*="tb_border_hover_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_border_hover'
                ),
                'clickable_icon_hover' => array(
                    'label'       => 'Clickable icon (hover)',
                    'elements'    => '
                        .nav.nav-tabs > li:hover:not(.active):not(.ui-state-active) .tb_icon,
                        .ui-tabs-nav .ui-state-focus:not(.ui-state-active) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#111111',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'toolbar_tabs.clickable_text_hover'
                ),
                'clickable_text_active' => array(
                    'label'       => 'Clickable text (active)',
                    'elements'    => '
                        .nav.nav-tabs.tb_style_2 > li.active a,
                        .nav.nav-tabs.tb_style_2 > li.ui-state-active a,
                        .nav.nav-tabs:not(.tb_style_2) > li.active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"]),
                        .ui-tabs-nav:not(.tb_style_2) .ui-state-active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"])
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_text_active'
                ),
                'clickable_bg_active' => array(
                    'label'       => 'Clickable bg (active)',
                    'elements'    => '
                        .nav.nav-tabs > li.active:not([class*="tb_bg_str_"]):not([class*="tb_bg_hover_str_"]),
                        .ui-tabs-nav .ui-state-active:not([class*="tb_bg_str_"]):not([class*="tb_bg_hover_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_bg_active'
                ),
                'clickable_border_active' => array(
                    'label'       => 'Clickable border (active)',
                    'elements'    => '
                        .nav.nav-tabs > li.active:not([class*="tb_border_str_"]):not([class*="tb_border_hover_str_"]),
                        .ui-tabs-nav .ui-state-active:not([class*="tb_border_str_"]):not([class*="tb_border_hover_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_border_active'
                ),
                'clickable_icon_active' => array(
                    'label'       => 'Clickable icon (active)',
                    'elements'    => '
                        .nav.nav-tabs > li.active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"]) .tb_icon,
                        .ui-tabs-nav .ui-state-active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"]) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'toolbar_tabs.clickable_text_active'
                )
            ),
            'toolbar_accordion' => array(
                '_label' => 'Accordion',
                'clickable_text' => array(
                    'label'       => 'Clickable text',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:not(:hover),
                        .tb_accordion > .tb_title,
                        .ui-accordion .ui-state-default:not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_text_str_"])
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_text'
                ),
                'clickable_bg' => array(
                    'label'       => 'Clickable bg',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:not(:hover),
                        .ui-accordion .ui-state-default:not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_bg_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#e6e6e6',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_bg'
                ),
                'clickable_border' => array(
                    'label'       => 'Clickable border',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:not(:hover),
                        .ui-accordion .ui-state-default:not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active):not([class*="tb_border_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_border'
                ),
                'clickable_icon' => array(
                    'label'       => 'Clickable icon',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:not(:hover) .tb_icon,
                        .ui-accordion .ui-state-default:not(.ui-state-hover):not(.ui-state-focus):not(.ui-state-active) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'toolbar_accordion.clickable_text'
                ),
                'clickable_text_hover' => array(
                    'label'       => 'Clickable text (hover)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:hover,
                        .ui-accordion .ui-state-hover:not(.ui-state-active):not([class*="tb_text_hover_str_"]),
                        .ui-accordion .ui-state-focus:not(.ui-state-active):not([class*="tb_text_hover_str_"])
                    ',
                    'property'    => 'color',
                    'color'       => '#111111',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_text_hover'
                ),
                'clickable_bg_hover' => array(
                    'label'       => 'Clickable bg (hover)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:hover,
                        .ui-accordion .ui-state-hover:not(.ui-state-active):not([class*="tb_bg_hover_str_"]),
                        .ui-accordion .ui-state-focus:not(.ui-state-active):not([class*="tb_bg_hover_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_bg_hover'
                ),
                'clickable_border_hover' => array(
                    'label'       => 'Clickable border (hover)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:hover,
                        .ui-accordion .ui-state-hover:not(.ui-state-active):not([class*="tb_border_hover_str_"]),
                        .ui-accordion .ui-state-focus:not(.ui-state-active):not([class*="tb_border_hover_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_border_hover'
                ),
                'clickable_icon_hover' => array(
                    'label'       => 'Clickable icon (hover)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle].collapsed:hover .tb_icon,
                        .ui-accordion .ui-state-hover:not(.ui-state-active) .tb_icon,
                        .ui-accordion .ui-state-focus:not(.ui-state-active) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#111111',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'toolbar_accordion.clickable_text_hover'
                ),
                'clickable_text_active' => array(
                    'label'       => 'Clickable text (active)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed),
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed):hover,
                        .ui-accordion .ui-state-active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"])
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_text_active'
                ),
                'clickable_bg_active' => array(
                    'label'       => 'Clickable bg (active)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed),
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed):hover,
                        .ui-accordion .ui-state-active:not([class*="tb_bg_str_"]):not([class*="tb_bg_hover_str_"])
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_bg_active'
                ),
                'clickable_border_active' => array(
                    'label'       => 'Clickable border (active)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed),
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed):hover,
                        .ui-accordion .ui-state-active:not([class*="tb_border_str_"]):not([class*="tb_border_hover_str_"])
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_border_active'
                ),
                'clickable_icon_active' => array(
                    'label'       => 'Clickable icon (active)',
                    'elements'    => '
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed) .tb_icon,
                        .panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed):hover .tb_icon,
                        .ui-accordion .ui-state-active:not([class*="tb_text_str_"]):not([class*="tb_text_hover_str_"]) .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'toolbar_accordion.clickable_text_active'
                )
            )
        );

        return $default_colors;
    }
}