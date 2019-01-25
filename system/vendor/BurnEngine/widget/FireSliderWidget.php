<?php

class Theme_FireSliderWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');

    /**
     * @var string
     */
    protected $slider_html;

    /**
     * @var FireSlider_Catalog_DefaultModel
     */
    protected $sliderModel;

    /**
     * @var FireSlider_Catalog_Extension
     */
    protected $sliderExtension;

    protected $slider_config;

    public function onCreate()
    {
        $this->sliderExtension = $this->engine->getExtension('fire_slider');
        $this->sliderModel = $this->sliderExtension->getModel('default');
    }

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active' => 1,
            'slider_id' => '0'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'navigation_size' => '3'
        ), $settings));
    }

    public function allowAddToAreaContentCache()
    {
        return false;
    }

    public function initFrontend()
    {
        $this->sliderExtension->registerJavascriptResources();
    }

    public function configureFrontend()
    {
        if (empty($this->settings['lang'][$this->language_code]['slider_id'])) {
            return;
        }

        $slider_id = $this->settings['lang'][$this->language_code]['slider_id'];
        $cache_key = 'fireslider_' . $slider_id . '.' . (TB_RequestHelper::isRequestHTTPS() ? 'https.': '') . $this->getId();

        if ($this->themeData['system']['cache_content'] && ($slider_html = $this->engine->getCacheVar($cache_key))) {
            $this->slider_html = $slider_html;
        } else {
            $slider_config = $this->getSliderConfig();

            if (empty($slider_config)) {
                return;
            }

            $this->slider_html = $this->sliderModel->generateSliderMarkup($slider_config, $this->getDomId());

            $this->engine->setCacheVar($cache_key, $this->slider_html);
        }
    }

    protected function getSliderConfig()
    {
        if (null == $this->slider_config) {
            $this->slider_config = array();

            if (!empty($this->settings['lang'][$this->language_code]['slider_id'])) {
                $key = $this->settings['lang'][$this->language_code]['slider_id'];
                $this->slider_config = $this->engine->getSettingsModel('fire_slider', 0)->getScopeSettings($key, false, 0);
            }
        }

        return $this->slider_config;
    }

    public function render(array $view_data = array())
    {
        $view_data['slider'] = $this->slider_html;

        return parent::render($view_data);
    }

    public function onAreaContentOutput(&$widget_html)
    {
        if ($this->themeData['system']['optimize_js_load']) {
            return;
        }

        if (!preg_match('/<script type="text\/javascript" data-prepend="1">(.*?)<\/script>/is', $widget_html, $matches)) {
            return;
        }

        $widget_html = str_replace($matches[0], '', $widget_html);

        $this->themeData->viewSlot->addJsContents($matches[1]);
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        if (!$slider_config = $this->getSliderConfig()) {
            return;
        }

        $css = $this->sliderModel->generateSliderStyles($slider_config, $this->getDomId());

        $styleBuilder->addCss($css);

    }

    protected function getBoxClasses()
    {
        $classes  = parent::getBoxClasses();
        $classes .= ' tb_nav_size_' . $this->settings['navigation_size'];

        return $classes;
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'body' => array(
                'section_name'      => 'Body',
                'elements'          => '',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 13,
                'line-height'       => 20,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => false,
                'has_effects'       => false,
                'show_built_styles' => false,
                'multiple_variants' => true,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'h1' => array(
                'section_name'      => 'H1',
                'elements'          => 'h1, .h1',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => 'latin',
                'variant'           => 'regular',
                'size'              => 26,
                'line-height'       => 30,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => false,
                'has_line_height'   => false,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'h2' => array(
                'section_name'      => 'H2',
                'elements'          => '
                    h2,
                    .h2
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => 'latin',
                'variant'           => 'regular',
                'size'              => 16,
                'line-height'       => 20,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => false,
                'has_line_height'   => false,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'h3' => array(
                'section_name'      => 'H3',
                'elements'          => 'h3, .h3',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => 'latin',
                'variant'           => 'regular',
                'size'              => 15,
                'line-height'       => 20,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => false,
                'has_line_height'   => false,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'h4' => array(
                'section_name'      => 'H4',
                'elements'          => 'h4, .h4 ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => 'latin',
                'variant'           => 'regular',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => false,
                'has_line_height'   => false,
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
    }

    public function getDefaultBoxColors()
    {
        $additional_colors = array(
            'gallery_navigation' => array(
                '_label' => 'Gallery Prev/Next buttons',
                'button_default' => array(
                    'label'       => 'Text',
                    'elements'    => '.mSButtons:not(:hover) svg',
                    'property'    => 'fill',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_default'
                ),
                'button_bg_default' => array(
                    'label'       => 'Background',
                    'elements'    => '.mSButtons:not(:hover):after',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_bg_default'
                ),
                'button_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '.mSButtons:hover svg',
                    'property'    => 'fill',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_hover'
                ),
                'button_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '.mSButtons:hover:after',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_bg_hover'
                ),
            ),
            'gallery_pagination' => array(
                '_label' => 'Gallery Pagination',
                'default' => array(
                    'label'       => 'Text',
                    'elements'    => '.mSPages li:not(:hover):not(.active)',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_pagination.default'
                ),
                'hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '.mSPages li:hover',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_pagination.hover'
                ),
                'active' => array(
                    'label'       => 'Text (active)',
                    'elements'    => '.mSPages li.active, .mSPages li.active:hover',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_pagination.active'
                )
            ),
            'buttons' => array(
                '_label' => 'Buttons',
                'button' => array(
                    'label'       => 'Primary bg',
                    'elements'    => '
                        .btn:not(:hover):not(.btn-default),
                        .btn.active:hover,
                        .button:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button'
                ),
                'button_text' => array(
                    'label'       => 'Primary text',
                    'elements'    => '
                        .btn:not(:hover):not(.btn-default),
                        .btn.active:hover,
                        .button:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_text'
                ),
                'button_hover' => array(
                    'label'       => 'Primary bg (hover)',
                    'elements'    => '
                        .btn:not(.active):not(.btn-default):hover,
                        .button:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_hover'
                ),
                'button_text_hover' => array(
                    'label'       => 'Primary text (hover)',
                    'elements'    => '
                        .btn:not(.active):not(.btn-default):hover,
                        .button:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_text_hover'
                )
            )
        );

        return array_merge(parent::getDefaultBoxColors(), $additional_colors);
    }

    public function hasTitleStyles()
    {
        return false;
    }
}