<?php

class Theme_IconListWidget extends TB_Widget
{
    protected $areas = array('header', 'footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Icon List',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left',
            'rows'             => array()
        ), $settings));

        $default_restrictions = array(
            0 => array(
                'max_width'     => 1200,
                'items_per_row' => 5,
                'items_spacing' => 30
            ),
            1 => array(
                'max_width'     => 1000,
                'items_per_row' => 4,
                'items_spacing' => 30
            ),
            2 => array(
                'max_width'     => 750,
                'items_per_row' => 3,
                'items_spacing' => 30
            ),
            3 => array(
                'max_width'     => 450,
                'items_per_row' => 2,
                'items_spacing' => 30
            ),
            4 => array(
                'max_width'     => 300,
                'items_per_row' => 1,
                'items_spacing' => 30
            )
        );

        $settings = array_replace($settings, $this->initFlatVars(array(
            'display'              => 'list',
            'restrictions'         => $default_restrictions,
            'grid_cols'            => 3,
            'grid_gut'             => 30,
            'description_position' => 'right',
            'icon_valign'          => 'top',
            'text_align'           => 'left',
            'icons_align'          => 'left'
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

    public function render(array $view_data = array())
    {
        foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {

            if (!isset($this->settings['lang'][$language_code])) {
                continue;
            }

            $i = 1;
            foreach ($this->settings['lang'][$language_code]['rows'] as &$row) {
                $icon_tag           = 'span';
                $icon_url           = '';

                if ($row['url']) {
                    $icon_tag = 'a';
                    $icon_url = ' href="' . $row['url'] . '"' . ' target="' . $row['url_target'] . '"';
                }

                $row['text']     = html_entity_decode($row['text'], ENT_COMPAT, 'UTF-8');
                $row['tag']      = $icon_tag;
                $row['url_html'] = $icon_url;

                $i++;
            }
        }

        $listing_classes        = '';
        $description_classes    = '';

        $listing_classes       .= ' tb_' . $this->settings['display'] . '_view';
        $listing_classes       .= ' tb_description_' . $this->settings['description_position'];

        if ($this->settings['display'] == 'grid') {
            $restrictions_json = array();
            foreach ($this->settings['restrictions'] as $restriction) {
                $restrictions_json[$restriction['max_width']] = array(
                    'items_per_row' => $restriction['items_per_row'],
                    'items_spacing' => $restriction['items_spacing']
                );
            }
            krsort($restrictions_json);
            $view_data['restrictions_json'] = json_encode($restrictions_json);

            $listing_classes        .= ' tb_listing';
            $listing_classes        .= ' tb_' . $this->getDomId() . '_classes';
            $listing_classes        .= $this->settings['icon_valign'] == 'top' ? ' tb_icons_top' : '';
            $description_classes    .= $this->settings['display'] == 'grid' && $this->settings['description_position'] == 'bottom' ? ' text-' . $this->settings['text_align'] : '';
        }

        if ($this->settings['display'] == 'list') {
            $listing_classes .= ' tb_icons_' . $this->settings['icon_valign'];
        }

        if ($this->settings['display'] == 'inline') {
            $listing_classes .= ' text-'  . $this->settings['icons_align'];
            $listing_classes .= ' tb_ml_-' . $this->settings['grid_gut'];
            $listing_classes .= ' tb_mr_-' . $this->settings['grid_gut'];

            if ($this->settings['description_position'] == 'left' || $this->settings['description_position'] == 'tooltip') {
                $listing_classes .= ' tb_pl_'  . $this->settings['grid_gut'];
            }

            if ($this->settings['description_position'] == 'right') {
                $listing_classes .= ' tb_pr_'  . $this->settings['grid_gut'];
            }
        }

        $view_data['listing_classes']        = $listing_classes;
        $view_data['description_classes']    = $description_classes;

        return parent::render($view_data);
    }

    public function generateIconStyles($row)
    {
        $font_base  = $this->themeData->fonts['body']['line-height'];

        $icon_styles  = 'width: '             . $row['box_size']    . 'px;';
        $icon_styles .= 'height: '            . $row['box_size']    . 'px;';
        $icon_styles .= 'line-height: '       . $row['box_size']    . 'px;';
        $icon_styles .= 'color: '             . $row['glyph_color'] . ';';
        $icon_styles .= 'font-size: '         . $row['glyph_size']  . 'px;';
        $icon_styles .= $row['box_color'] ? 'background-color: ' . $row['box_color'] . ';' : '';
        $icon_styles .= $row['box_color'] ? 'border-color: ' . $row['box_color'] . ';' : '';

        if ($this->settings['display'] == 'list' && $this->settings['icon_valign'] == 'top') {
          $v1 = $row['box_size'];
          $v2 = $this->themeData->calculateLineHeight($row['box_size'], $font_base);
          $margin = floor(($v2 - $v1)/2);
          $icon_styles .= 'margin-top: '      . $margin . 'px;';
        }

        return $icon_styles;
    }

    public function generateIconWrapStyles($row)
    {
        $font_base  = $this->themeData->fonts['body']['line-height'];

        $icon_wrap_styles  = 'width: ' . $this->themeData->calculateLineHeight(($row['box_size'] - 2), $font_base) . 'px;';
        $icon_wrap_styles .= ' height: ' . $this->themeData->calculateLineHeight(($row['box_size'] - 2), $font_base) . 'px;';

        if ($this->settings['display'] == 'grid' && ($this->settings['description_position'] == 'left' || $this->settings['description_position'] == 'right')) {
            $icon_wrap_styles .= ' margin-top: -' . ($this->themeData->calculateLineHeight($row['box_size'] - 2, $font_base)/2) . 'px;';
        }
        if ($this->settings['display'] == 'inline') {
            if ($this->settings['description_position'] == 'left' || $this->settings['description_position'] == 'tooltip') {
                $icon_wrap_styles .= ' margin-right: ' . $this->settings['grid_gut'] . 'px;';
            }
            if ($this->settings['description_position'] == 'right') {
                $icon_wrap_styles .= ' margin-left: ' . $this->settings['grid_gut'] . 'px;';
            }
        }

        return $icon_wrap_styles;
    }

    public function generateDescStyles($row)
    {
        $font_base    = $this->themeData->fonts['body']['line-height'];
        $desc_styles  = '';

        if ($this->settings['display'] == 'list') {
            if ($this->settings['description_position'] == 'left') {
                $desc_styles .= 'padding-right: ' . ($row['box_size']/2 - 2) . 'px;';
            }
            if($this->settings['description_position'] == 'right') {
                $desc_styles .= 'padding-left: ' . ($row['box_size']/2 - 2) . 'px;';
            }
        }

        if ($this->settings['display'] == 'inline') {
            if ($this->themeData['language_direction'] == 'rtl') {
                $desc_styles .= 'padding-right: ' . ($row['box_size']/2 - 2) . 'px;';
            }
            if ($this->themeData['language_direction'] == 'ltr') {
                $desc_styles .= 'padding-left: ' . ($row['box_size']/2 - 2) . 'px;';
            }
            // $desc_styles .= ' line-height: ' . $this->themeData->calculateLineHeight($row['box_size'], $font_base) . 'px;';
        }

        if ($this->settings['display'] == 'grid') {
            if ($this->settings['description_position'] == 'left') {
                $desc_styles .= 'padding-right: ' . ($this->themeData->calculateLineHeight($row['box_size'], $font_base) + 20) . 'px;';
            }
            if($this->settings['description_position'] == 'right') {
                $desc_styles .= 'padding-left: ' . ($this->themeData->calculateLineHeight($row['box_size'], $font_base) + 20) . 'px;';
            }
        }

        return $desc_styles;
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $css = '';

        if ($this->settings['display'] == 'list') {
            $css .= '#' . $this->getDomId() . ' .tb_icon_list.tb_list_view li:not(:last-child) > * {';
            $css .= '  padding-bottom: ' . $this->settings['grid_gut'] . 'px;';
            $css .= '}';
        }
        if ($this->settings['display'] == 'inline') {
            $css .= '#' . $this->getDomId() . ' .tb_icon_list.tb_inline_view {';
            $css .= '  margin-top: -' . min($this->settings['grid_gut'], 20) . 'px;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' .tb_icon_list.tb_inline_view li {';
            $css .= '  margin-top: '  . min($this->settings['grid_gut'], 20) . 'px;';
            $css .= '}';
        }

        $styleBuilder->addCss($css);
    }

    protected function getBoxClasses()
    {
        $settings = $this->settings;
        $lazyload = $this->themeData->system['js_lazyload'];
        $classes  = parent::getBoxClasses();
        $classes .= $lazyload && $settings['description_position'] == 'tooltip' ? ' lazyload' : '';

        return $classes;
    }

    protected function getBoxData()
    {
        $settings = $this->settings;
        $lazyload = $this->themeData->system['js_lazyload'];
        $data     = parent::getBoxData();
        $data    .= $lazyload && $settings['description_position'] == 'tooltip' ? ' data="' . $this->themeData->system['js_lazyload_expand'] . '"' : '';

        return $data;
    }

    public function getDefaultBoxFonts()
    {
        $additional_fonts = array(
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
                'elements'          => 'h4, .h4',
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
            )
        );

        return array_merge(parent::getDefaultBoxFonts(), $additional_fonts);
    }

    public function getDefaultBoxColors()
    {
        return array(
            'body' => array(
                '_label' => 'Body',
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
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '.tb_text_wrap a:hover',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text_links_hover'
                ),
                'title' => array(
                    'label'       => 'Titles',
                    'elements'    => 'h1, h2, h3, h4, .h1, .h2, .h3, .h4',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                )
            ),
            'icons' => array(
                '_label' => 'Icons',
                'icon_text_hover' => array(
                    'label'       => 'Icon (hover)',
                    'elements'    => 'a.tb_icon:hover',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'icon_bg_hover' => array(
                    'label'       => 'Icon bg (hover)',
                    'elements'    => 'a.tb_icon:hover',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                ),
                'tooltip_bg' => array(
                    'label'       => 'Tooltip backgroud',
                    'elements'    => '.tb_tooltip',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'tooltip_text' => array(
                    'label'       => 'Tooltip text',
                    'elements'    => '.tb_tooltip',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            )
        );
    }

    public function getPresentationTitle()
    {
        $title = $this->getName();
        $lang_title = $this->getLangTitle();
        if (!empty($lang_title)) {
            $title = $lang_title . ' <span>(' . $title . ')</span>';
        }

        return $title;
    }

}