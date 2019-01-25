<?php

class Theme_CallToActionWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $langVars = array(
            'is_active'         => 1,
            'text'              => '<p>A simple call to action text.</p>',
            'button_url'        => '',
            'url_target'        => '_self',
            'button_position'   => 'right',
            'button_size'       => 'lg',
            'button_text'       => 'Click Here!',
            'button_icon'       => '',
            'button_icon_size'  => 16
        );
        $lang_settings = $this->initLangVars($langVars, $settings);
        foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
            $lang_settings['lang'][$language_code]['text'] = html_entity_decode($lang_settings['lang'][$language_code]['text'], ENT_COMPAT, 'UTF-8');
        }

        $settings = array_replace($settings, $lang_settings);
    }

    public function render(array $view_data = array())
    {
        $lang_vars = $this->getLangSettings();

        $button_classes  = '';
        $button_classes .= ' btn-' . $lang_vars['button_size'];

        $view_data['button_classes'] = $button_classes;

        return parent::render($view_data);
    }

    protected function renderContent($content)
    {
        if ($content && TB_RequestHelper::isRequestHTTPS()) {
            $content = str_replace(' src="http://', ' src="https://', $content);
        }

        return parent::renderContent($content);
    }

    public function onRenderWidgetContent($content)
    {
        if ($this->themeData['system']['image_lazyload']) {
            $content = $this->getThemeModel()->alignImagesAttributes($content);
        }

        return $content;
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
                'subsets'           => '',
                'variant'           => '',
                'size'              => 26,
                'line-height'       => 30,
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
            ),
            'h2' => array(
                'section_name'      => 'H2',
                'elements'          => 'h2, .h2',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 16,
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
            ),
            'h3' => array(
                'section_name'      => 'H3',
                'elements'          => 'h3, .h3',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 15,
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
            ),
            'h4' => array(
                'section_name'      => 'H4',
                'elements'          => 'h4, .h4 ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
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
            ),
            'add_to_cart_button' => array(
                'section_name'      => 'Action button',
                'elements'          => '.btn',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 15,
                'line-height'       => '',
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => true,
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
        $default_colors = array(
            'body' => array(
                '_label' => 'Body',
                'accent' => array(
                    'label'       => 'Accent',
                    'elements'    => '.tb_main_color, .tb_main_color_hover:hover, .colorbox, .tb_list_1 > li:before',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                ),
                'accent_hover' => array(
                    'label'       => 'Accent (hover)',
                    'elements'    => 'a.tb_main_color:hover, a.colorbox:hover',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                ),
                'accent_bg' => array(
                    'label'       => 'Accent bg',
                    'elements'    => '.tb_main_color_bg',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'body.accent'
                ),
                'accent_bg_hover' => array(
                    'label'       => 'Accent bg (hover)',
                    'elements'    => 'a.tb_main_color_bg:hover',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'body.accent_hover'
                ),
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'text_links' => array(
                    'label'       => 'Links',
                    'elements'    => '.tb_text_wrap a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text_links'
                ),
                'text_links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '.tb_text_wrap a:hover',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text_links_hover'
                ),
                'h1' => array(
                    'label'       => 'H1',
                    'elements'    => 'h1, .h1',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                ),
                'h2' => array(
                    'label'       => 'H2',
                    'elements'    => 'h2, .h2',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                ),
                'h3' => array(
                    'label'       => 'H3',
                    'elements'    => 'h3, .h3',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                ),
                'h4' => array(
                    'label'       => 'H4',
                    'elements'    => 'h4, .h4',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                )
            ),
            'buttons' => array(
                '_label' => 'Action button',
                'button' => array(
                    'label'       => 'Button bg',
                    'elements'    => '
                        .btn:not(:hover)
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
                    'label'       => 'Button text',
                    'elements'    => '
                        .btn:not(:hover)
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
                    'label'       => 'Button bg (hover)',
                    'elements'    => '
                        .btn:hover
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
                        .btn:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:buttons.button_text_hover'
                ),
            )
        );

        return $default_colors;
    }

    public function hasTitleStyles()
    {
        return false;
    }
}