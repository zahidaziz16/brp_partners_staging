<?php

class Theme_ManufacturersWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');
    protected $manufacturers;

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Manufacturers',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left'
        ), $settings));

        $default_restrictions = array(
            0 => array(
                'max_width'     => 1200,
                'items_per_row' => 6,
                'items_spacing' => 30
            ),
            1 => array(
                'max_width'     => 900,
                'items_per_row' => 5,
                'items_spacing' => 30
            ),
            2 => array(
                'max_width'     => 750,
                'items_per_row' => 4,
                'items_spacing' => 30
            ),
            3 => array(
                'max_width'     => 450,
                'items_per_row' => 3,
                'items_spacing' => 30
            ),
            4 => array(
                'max_width'     => 300,
                'items_per_row' => 2,
                'items_spacing' => 30
            )
        );

        $settings = array_replace($settings, $this->initFlatVars(array(
            'display_type'        => 'all',
            'manufacturer_ids'    => array(),
            'restrictions'        => $default_restrictions,
            'slider'              => 0,
            'slider_step'         => 1,
            'slider_speed'        => 500,
            'slider_pagination'   => 0,
            'slider_nav_position' => 'top',
            'slider_autoplay'     => 0,
            'slider_loop'         => 0,
            'filter_randomize'    => 0,
            'filter_limit'        => 4,
            'image_size_x'        => 100,
            'image_size_y'        => 100
        ), $settings));

        foreach ($settings['restrictions'] as $i => $row) {
            if (empty($row['max_width']) || empty($row['items_per_row'])) {
                unset($settings['restrictions'][$i]);
            }
        }

        if (empty($settings['restrictions'])) {
            $settings['restrictions'] = $default_restrictions;
        }
    }

    public function initFrontend()
    {
        $this->engine->getEventDispatcher()->connect('core:assignAssets', array($this, 'assignAssets'));
    }

    public function assignAssets()
    {
        if ($this->settings['slider']) {
            $this->themeData->registerJavascriptResource('javascript/swiper.min.js');
            $this->themeData->registerStylesheetResource('stylesheet/swiper.css');
        }
    }

    public function render(array $view_data = array())
    {
        $view_data['manufacturers'] = $this->getManufacturers();

        $restrictions_json = array();
        foreach ($this->settings['restrictions'] as $restriction) {
            $restrictions_json[$restriction['max_width']] = array(
                'items_per_row' => $restriction['items_per_row'],
                'items_spacing' => $restriction['items_spacing']
            );
        }
        krsort($restrictions_json);

        $listing_classes = 'tb_' . $this->getDomId() . '_classes';
        $listing_classes .= $this->settings['slider_nav_position'] == 'side' ? ' tb_side_nav' : '';

        $view_data['restrictions_json'] = json_encode($restrictions_json);
        $view_data['slider']            = $this->settings['slider'];
        $view_data['slider_step']       = $this->settings['slider_step'];
        $view_data['listing_classes']   = $listing_classes;
        $view_data['url']               = $this->engine->getOcUrl();

        return parent::render($view_data);
    }

    protected function getManufacturers()
    {
        if (null === $this->manufacturers) {
            $options = array();

            if ($this->settings['display_type'] == 'custom') {
                $options['manufacturer_ids'] = $this->settings['manufacturer_ids'];
            }

            $manufacturers = $this->getModel('manufacturer')->getManufacturers($options);


            foreach ($manufacturers as $key => $manufacturer) {
                if ($manufacturer['image'] && $thumb = $this->getThemeModel()->resizeImage($manufacturer['image'], $this->settings['image_size_x'], $this->settings['image_size_y'])) {
                    $manufacturers[$key]['thumb'] = $thumb;
                    $manufacturers[$key]['thumb_width'] = $this->settings['image_size_x'];
                    $manufacturers[$key]['thumb_height'] = $this->settings['image_size_y'];
                } else {
                    unset($manufacturers[$key]);
                }
            }

            if ($this->settings['filter_randomize']) {
                shuffle($manufacturers);
            }

            $this->manufacturers = $manufacturers;
        }

        return $this->manufacturers;
    }

    protected function getBoxClasses()
    {
        $settings = $this->settings;
        $lazyload = $this->themeData->system['js_lazyload'];
        $classes  = parent::getBoxClasses();
        $classes .= !$this->getLangTitle() ? ' no_title' : '';
        $classes .= $settings['slider'] ? ' has_slider' : '';
        $classes .= $settings['slider'] && $settings['slider_nav_position'] != 'side' ? ' tb_top_nav'  : '';
        $classes .= $settings['slider'] && $settings['slider_nav_position'] == 'side' ? ' tb_side_nav' : '';
        $classes .= $lazyload && $settings['slider'] ? ' lazyload' : '';

        return $classes;
    }

    protected function getBoxData()
    {
        $lazyload = $this->themeData->system['js_lazyload'];
        $settings = $this->settings;
        $data     = parent::getBoxData();
        $data    .= $lazyload && $settings['slider'] ? ' data="' . $this->themeData->system['js_lazyload_expand'] . '"' : '';

        return $data;
    }

    public function getDefaultBoxColors()
    {
        return array(
            'carousel_nav' => array(
                '_label' => 'Carousel navigation',
                'button_default' => array(
                    'label'       => 'Prev/Next button',
                    'elements'    => '
                        .tb_slider_controls a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_nav.button_default'
                ),
                'button_hover' => array(
                    'label'       => 'Prev/Next button (hover)',
                    'elements'    => '
                        .tb_slider_controls a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_nav.button_hover'
                ),
                'button_inactive' => array(
                    'label'       => 'Prev/Next button (inactive)',
                    'elements'    => '
                        .tb_slider_controls a.tb_disabled,
                        .tb_slider_controls a.tb_disabled:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_nav.button_inactive'
                ),
            ),
            'carousel_pagination' => array(
                '_label' => 'Carousel pagination',
                'pagination_default' => array(
                    'label'       => 'Pagination',
                    'elements'    => '
                        .tb_slider_pagination span:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_pagination.pagination_default'
                ),
                'pagination_hover' => array(
                    'label'       => 'Pagination (hover)',
                    'elements'    => '
                        .tb_slider_pagination span:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_pagination.pagination_hover'
                ),
                'pagination_active' => array(
                    'label'       => 'Pagination (active)',
                    'elements'    => '
                        .tb_slider_pagination span.tb_active,
                        .tb_slider_pagination span.tb_active:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_pagination.pagination_active'
                )
            ),
        );
    }
}