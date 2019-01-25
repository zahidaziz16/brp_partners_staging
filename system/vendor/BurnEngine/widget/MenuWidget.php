<?php

class Theme_MenuWidget extends TB_Widget
{
    protected $areas = array('header', 'footer', 'intro', 'content', 'column_left', 'column_right');
    protected $menu;

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
            'menu_id'                   => '',
            'menu_height'               => 20,
            'menu_padding'              => 0,
            'menu_spacing'              => 10,
            'orientation'               => 'vertical',
            'relative_to'               => 'content',     // content, wrapper
            'relative_to_vertical'      => 'menu',        // menu, block
            'justified_navigation'      => 0,
            'justified_dropdown'        => 0,
            'justified_align'           => 'center',      // start, center, end
            'dropdown_indicator'        => 1,
            'dropdown_min_width'        => 250,
            'ignore_tabbed_submenu'     => 0,

            // Responsive
            'responsive_stack'          => false,
            'responsive_width'          => 768,
            'responsive_margin_left'    => 0,

            // Separator
            'separator'                 => 'none',        // none, image, border, icon
            'separator_border_width'    => 1,
            'separator_border_style'    => 'solid',
            'separator_image'           => 1,
            'separator_image_position'  => 'middle',
            'separator_symbol'          => '',            // glyph icon value
            'separator_symbol_size'     => 0,

            // Level 1
            'level_1_style'             => 'list',

            // Level 2
            'level_2'                   => 'dropdown',
            'level_2_style'             => 'list',

            // Level 3
            'level_3'                   => 'dropdown',
            'level_3_style'             => 'list'
        ), $settings));
    }

    public function onTransformSettings(array &$settings)
    {
        if ($settings['menu_id'] == 'new') {

            $tree = array();

            foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
                if (isset($settings['lang'][$language_code]['menu']) && !empty($settings['lang'][$language_code]['menu'])) {
                    $str = (string) html_entity_decode($settings['lang'][$language_code]['menu'], ENT_COMPAT, 'UTF-8');
                    $tree[$language_code] = json_decode($str, true);
                    unset($settings['lang'][$language_code]['menu']);
                }
            }

            $settings['menu_id'] = TB_Utils::slugify($settings['menu_name']) . '-' . TB_Utils::genRandomString();

            $this->engine->getSettingsModel('menu')->setAndPersistScopeSettings($settings['menu_id'], array(
                'id'   => $settings['menu_id'],
                'name' => $settings['menu_name'],
                'tree' => $tree
            ));

            unset($settings['menu_name']);
        }
    }

    public function onEdit(array &$settings)
    {
        if (!empty($settings['separator_image'])  && file_exists(DIR_IMAGE . $settings['separator_image'])) {
            $settings['separator_image_preview'] = $this->engine->getOcToolImage()->resize($settings['separator_image'], 100, 100);
        } else {
            $settings['separator_image_preview'] = $this->getThemeModel()->getNoImage();
        }

        $options = array();
        foreach ($this->engine->getSettingsModel('menu')->getValues() as $menu) {
            $options[$menu['id']] = $menu['name'];
        }

        $settings['menu_options'] = $options;
    }

    public function onPersist(array &$settings)
    {
        foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
            if (isset($settings['lang'][$language_code]['menu']) && !empty($settings['lang'][$language_code]['menu'])) {
                $page_ids = array();

                foreach ($settings['lang'][$language_code]['menu'] as $item) {
                    if ($item['data']['type'] == 'page') {
                        $page_ids[] = $item['data']['id'];
                    }
                }

                if (!empty($page_ids)) {
                    $settings['lang'][$language_code]['page_ids'] = $page_ids;
                }
            }
        }
    }

    public function render(array $view_data = array())
    {
        $view_data['menu_html'] = '';

        if (!empty($this->settings['menu_id'])) {
            /** @var Theme_Catalog_MenuPlugin $menuPlugin */
            $menuPlugin = $this->engine->getThemeExtension()->getPlugin('menu');

            $menuPlugin->setWidgetSettings($this->settings, $this->getDomId());

            $view_data['menu_html'] = $this->engine->getThemeExtension()->fetchTemplate('menu/menu', array(
                    'widget_settings' => $this->settings,
                    'menu_items'      => $menuPlugin->fetchMenuItems($this->getMenu())
                ) + $this->engine->loadOcTranslation('common/footer')
            );
        }

        return parent::render($view_data);
    }

    protected function getMenu()
    {
        if (null === $this->menu) {
            $menu = $this->engine->getSettingsModel('menu')->getScopeSettings($this->settings['menu_id']);
            $menu = !empty($menu['tree'][$this->language_code]) ? $menu['tree'][$this->language_code] : array();
            $this->menu = $menu;
        }

        return $this->menu;
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $font_base        = $this->themeData->fonts['body']['line-height'];
        $box_fonts        = $this->settings['box_styles']['font'][$this->language_code];
        $menu_font_size   = $box_fonts['level_1']['size'];
        $level_2_size     = $box_fonts['level_2']['size'];
        $level_2_height   = !empty($box_fonts['level_2']['line-height']) ? $box_fonts['level_2']['line-height'] : $this->themeData->calculateLineHeight($level_2_size, $font_base);
        $menu_line_height = $this->themeData->calculateLineHeight($menu_font_size, $font_base);
        $css = '';

        foreach ($this->getMenu() as $item) {
            $settings = $item['data']['settings'];

            if ($item['data']['type'] == 'manufacturers' && !$settings['is_megamenu']) {
                $css .= '#main_navigation #menu_brands > .tb_submenu {
                  width: ' . $settings['width'] . 'px;
                }';
            }

            if (empty($settings['menu_color_inherit']) && !empty($settings['menu_color'])) {
                $css .= '#' . $this->getDomId() . ' .tb_menu_' . $item['data']['type'] . '_' . $item['data']['id'] . ' > a:not(:hover) {
                  color: ' . $settings['menu_color'] . ';
                }';
            }

            if (empty($settings['menu_icon_color_inherit']) && !empty($settings['menu_icon_color']) && !empty($settings['menu_icon_color_as_hover'])) {
                $css .= '#' . $this->getDomId() . ' .tb_menu_' . $item['data']['type'] . '_' . $item['data']['id'] . ' > a:hover {
                  color: ' . $settings['menu_icon_color'] . ' !important;
                }';
            }

            if (!empty($settings['subcategory_column_width'])) {
                $css .= '#' . $this->getDomId() . ' .tb_menu_' . $item['data']['type'] . '_' . $item['data']['id'] . ' .tb_multicolumn {
                  -webkit-column-width: ' . $settings['subcategory_column_width'] . 'px;
                     -moz-column-width: ' . $settings['subcategory_column_width'] . 'px;
                          column-width: ' . $settings['subcategory_column_width'] . 'px;
                }';
            }

            if(!empty($item['children'])) {
                foreach ($item['children'] as $child_item) {
                    $settings = $child_item['data']['settings'];

                    if (empty($settings['menu_color_inherit']) && !empty($settings['menu_color'])) {
                        $css .= '#' . $this->getDomId() . ' .tb_menu_' . $child_item['data']['type'] . '_' . $child_item['data']['id'] . ' > a:not(:hover) {
                          color: ' . $settings['menu_color'] . ';
                        }';
                    }

                    if (empty($settings['menu_icon_color_inherit']) && !empty($settings['menu_icon_color']) && !empty($settings['menu_icon_color_as_hover'])) {
                        $css .= '#' . $this->getDomId() . ' .tb_menu_' . $child_item['data']['type'] . '_' . $child_item['data']['id'] . ' > a:hover,';
                        $css .= '#' . $this->getDomId() . ' .tb_menu_' . $child_item['data']['type'] . '_' . $child_item['data']['id'] . '.active > a,';
                        $css .= '#' . $this->getDomId() . ' .tb_menu_' . $child_item['data']['type'] . '_' . $child_item['data']['id'] . '.tb_hovered > a {
                          color: ' . $settings['menu_icon_color'] . ' !important;
                        }';
                    }

                    if (!empty($settings['subcategory_column_width'])) {
                        $css .= '#' . $this->getDomId() . ' .tb_menu_' . $child_item['data']['type'] . '_' . $child_item['data']['id'] . ' .tb_multicolumn {
                          -webkit-column-width: ' . $settings['subcategory_column_width'] . 'px;
                             -moz-column-width: ' . $settings['subcategory_column_width'] . 'px;
                                  column-width: ' . $settings['subcategory_column_width'] . 'px;
                        }';
                    }

                    if (!empty($child_item['children'])) {
                        foreach ($child_item['children'] as $grandchild_item) {
                            $settings = $grandchild_item['data']['settings'];

                            if (empty($settings['menu_color_inherit']) && !empty($settings['menu_color'])) {
                                $css .= '#' . $this->getDomId() . ' .tb_menu_' . $grandchild_item['data']['type'] . '_' . $grandchild_item['data']['id'] . ' > a:not(:hover) {
                                  color: ' . $settings['menu_color'] . ';
                                }';
                            }
                            if (empty($settings['menu_icon_color_inherit']) && !empty($settings['menu_icon_color']) && !empty($settings['menu_icon_color_as_hover'])) {
                                $css .= '#' . $this->getDomId() . ' .tb_menu_' . $grandchild_item['data']['type'] . '_' . $grandchild_item['data']['id'] . '.tb_hovered > a {
                                  color: ' . $settings['menu_icon_color'] . ' !important;
                                }';
                            }
                        }
                    }
                }
            }
        }

        // Horizontal menu CSS

        if ($this->settings['orientation'] == 'horizontal' && $this->settings['menu_spacing'] > 0) {
            $css .= '#' . $this->getDomId() . ' > nav > .nav {';
            $css .= '  margin-left: -' . $this->settings['menu_spacing'] . 'px;';
            $css .= '  margin-right: -' . $this->settings['menu_spacing'] . 'px;';
            $css .= '  padding-right: ' . $this->settings['menu_spacing'] . 'px;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li {';
            $css .= '  margin-left: ' . $this->settings['menu_spacing'] . 'px;';
            $css .= '}';
        }
        if ($this->settings['orientation'] == 'horizontal' && $this->settings['menu_height'] > 0) {
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li > a,';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li.divider,';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li.dropdown-header {';
            $css .= '  line-height: ' . $this->settings['menu_height'] . 'px;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li.dropdown > a > .tb_accent_label {';
            $css .= '  margin-top: ' . (floor($this->settings['menu_height'] - $menu_line_height) * 0.5 - 12) . 'px;';
            $css .= '}';
        }
        /*
        if ($this->settings['orientation'] == 'horizontal' && $this->settings['menu_height'] > 0 && $this->settings['menu_padding'] > 0) {
            $css .= '.tbSticky > .tbStickyRow #' . $this->getDomId() . ' > nav > .nav > li > a {';
            $css .= '  line-height: ' . ($menu_line_height * 2.5). 'px !important;';
            $css .= '}';
        }
        */
        if ($this->settings['orientation'] == 'horizontal'
            && $this->settings['justified_navigation']
            && $this->settings['justified_align'] != 'center'
            )
        {
            $css .= '#' . $this->getDomId() . ' > nav > .nav-justified > li > a {';
            $css .= '      -ms-justify-content:      ' . ($this->settings['justified_align']) . ';';
            $css .= '  -webkit-justify-content: flex-' . ($this->settings['justified_align']) . ';';
            $css .= '          justify-content: flex-' . ($this->settings['justified_align']) . ';';
            $css .= '}';
        }

        // Vertical menu CSS

        if ($this->settings['orientation'] == 'vertical') {
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li.divider {';
            $css .= $this->settings['menu_height'] == 0 ? '' : '  margin-bottom:  ' . $this->settings['menu_height'] * 0.25 . 'px !important;';
            $css .= $this->settings['menu_height'] == 0 ? '' : '  padding-bottom: ' . $this->settings['menu_height'] * 0.25 . 'px !important;';
            $css .= '}';
        }
        if ($this->settings['orientation'] == 'vertical' && $this->settings['menu_height'] > 0) {
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li > a,';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li.dropdown-header {';
            $css .= '  padding-top: ' . (floor($this->settings['menu_height'] - $menu_line_height) * 0.5) . 'px !important;';
            $css .= '  padding-bottom: ' . (ceil($this->settings['menu_height'] - $menu_line_height) * 0.5) . 'px !important;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > .dropdown:after,';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > .dropdown > .hidden + ul > li:after {';
            $css .= '  margin-top: ' . ($this->settings['menu_height'] * 0.5 - 5) . 'px;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li:not(:last-child) > ul:not(.dropdown-menu) {';
            $css .= '  padding-bottom: ' . (ceil($this->settings['menu_height'] - $menu_line_height) * 0.5) . 'px;';
            $css .= '}';
        }
        if ($this->settings['orientation'] == 'vertical' && $this->settings['menu_padding'] > 0) {
            $css .= '#' . $this->getDomId() . ' .nav-stacked > li > ul:not(.dropdown-menu) > li {';
            $css .= '  padding-left: ' . $this->settings['menu_padding'] . 'px;';
            $css .= '  padding-right: ' . $this->settings['menu_padding'] . 'px;';
            $css .= '}';
        }
        if ($level_2_height > $font_base) {
            $css .= '#' . $this->getDomId() . ' nav > .nav > li > ul > .dropdown.dropdown:after {';
            $css .= '  margin-top: ' . ($level_2_height * 0.5 - 5) . 'px;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' nav > .nav > li > ul > .dropdown > .tb_toggle {';
            $css .= '  margin-top: ' . (($level_2_height  - 30) / 2) . 'px !important;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' nav > .nav > li > ul > .dropdown > a {';
            $css .= '  padding-top:    0;';
            $css .= '  padding-bottom: 0;';
            $css .= '}';
        }

        if ($this->settings['menu_padding'] > 0) {
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li.dropdown-header,';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li > a {';
            $css .= '  padding-left: ' . $this->settings['menu_padding'] . 'px;';
            $css .= '  padding-right: ' . $this->settings['menu_padding'] . 'px;';
            $css .= '}';
        }

        // Separator Border

        if ($this->settings['separator'] == 'border') {
            if ($this->settings['orientation'] == 'horizontal') {
                $css .= '#' . $this->getDomId() . ' > nav > .nav > li:not(:first-child):before {';
                $css .= '  content: "";';
                $css .= '  margin-left:  ' . $this->settings['menu_spacing'] . 'px;';
                $css .= '  margin-right: ' . $this->settings['menu_spacing'] . 'px;';
                $css .= '  border-left-width: ' . $this->settings['separator_border_width'] . 'px;';
                $css .= '  border-right-width: ' . $this->settings['separator_border_width'] . 'px;';
                $css .= '  border-left-style: ' . $this->settings['separator_border_style'] . ';';
                $css .= '  border-right-style: ' . $this->settings['separator_border_style'] . ';';
                $css .= '}';
            }
            else {
                $css .= '#' . $this->getDomId() . ' > nav > .nav > li:not(:first-child) {';
                $css .= '  content: "";';
                $css .= '  display: block;';
                $css .= '  border-top-width: ' . $this->settings['separator_border_width'] . 'px;';
                $css .= '  border-top-style: ' . $this->settings['separator_border_style'] . ';';
                $css .= '}';
            }
        }

        // Separator Image

        if ($this->settings['separator'] == 'image' && $this->settings['orientation'] == 'horizontal') {
            $separator_img      = $this->engine->getContext()->getImageUrl() . $this->settings['separator_image'];
            $separator_img_size = getimagesize(DIR_IMAGE . $this->settings['separator_image']);
            $flex_align  = '';
            $flex_align .= $this->settings['separator_image_position'] == 'top'    ? 'flex-start' : '';
            $flex_align .= $this->settings['separator_image_position'] == 'middle' ? 'center'     : '';
            $flex_align .= $this->settings['separator_image_position'] == 'bottom' ? 'flex-end'   : '';
            $ms_flex_align  = '';
            $ms_flex_align .= $this->settings['separator_image_position'] == 'top'    ? 'start'  : '';
            $ms_flex_align .= $this->settings['separator_image_position'] == 'middle' ? 'center' : '';
            $ms_flex_align .= $this->settings['separator_image_position'] == 'bottom' ? 'end'    : '';

            $css .= '#' . $this->getDomId() . ' > nav > .nav > li:not(:first-child):before {';
            $css .= '  content: "";';
            $css .= '  display: inline-block;';
            $css .= '  width: '  . $separator_img_size[0] . 'px;';
            $css .= '  height: ' . $separator_img_size[1] . 'px;';
            $css .= '  -ms-flex-item-align: ' . $ms_flex_align . ';';
            $css .= '  -webkit-align-self:  ' . $flex_align    . ';';
            $css .= '          align-self:  ' . $flex_align    . ';';
            $css .= '  margin-left: '  . $this->settings['menu_spacing'] . 'px;';
            $css .= '  margin-right: ' . $this->settings['menu_spacing'] . 'px;';
            $css .= '  vertical-align: ' . $this->settings['separator_image_position'] . ';';
            $css .= '  background: url("' . $separator_img . '") no-repeat center;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li > a {';
            $css .= '  vertical-align: ' . $this->settings['separator_image_position'] . ';';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > .dropdown:not(:first-child):after {';
            $css .= '  left:  ' . ($separator_img_size[0] + $this->settings['menu_spacing']) . 'px;';
            $css .= '  right: ' . ($separator_img_size[0] + $this->settings['menu_spacing']) . 'px;';
            $css .= '}';
        }

        // Separator Symbol

        if ($this->settings['separator'] == 'symbol' && $this->settings['orientation'] == 'horizontal') {
            $symbol_width = round($this->settings['separator_symbol_size'] * 1.28571429);

            $css .= '#' . $this->getDomId() . ' > nav > .nav > li:before {';
            $css .= '  content: "' . str_replace('&#x', '\\', $this->settings['separator_symbol']) . '";';
            $css .= '  width: ' . $symbol_width . 'px;';
            $css .= '  margin-left: '  . $this->settings['menu_spacing'] . 'px;';
            $css .= '  margin-right: ' . $this->settings['menu_spacing'] . 'px;';
            $css .= '  padding-top: '    . (floor($this->settings['menu_height'] - $menu_line_height) * 0.5) . 'px;';
            $css .= '  padding-bottom: ' . (ceil($this->settings['menu_height'] - $menu_line_height) * 0.5)  . 'px;';
            $css .= '  font-size: ' . $this->settings['separator_symbol_size'] . 'px;';
            $css .= '  font-family: FontAwesome;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li > .dropdown-menu {';
            $css .= '  margin-left: '  . ($this->settings['menu_spacing'] + $symbol_width + 1) . 'px;';
            $css .= '  margin-right: ' . ($this->settings['menu_spacing'] + $symbol_width + 1) . 'px;';
            $css .= '}';
            if ($this->settings['menu_spacing'] > 0) {
                $css .= '#' . $this->getDomId() . ' > nav > .nav {';
                $css .= '  margin-left: -'  . ($this->settings['menu_spacing'] * 2 + $symbol_width) . 'px;';
                $css .= '  margin-right: -' . ($this->settings['menu_spacing'] * 2 + $symbol_width) . 'px;';
                $css .= '  padding-right: ' . ($this->settings['menu_spacing'] * 2 + $symbol_width) . 'px;';
                $css .= '}';
                $css .= '#' . $this->getDomId() . ' > nav > .nav > li:before {';
                $css .= '  margin-left: '  . $this->settings['menu_spacing'] . 'px;';
                $css .= '  margin-right: ' . $this->settings['menu_spacing'] . 'px;';
                $css .= '}';
            }
            if (!$this->settings['justified_navigation']) {
                $css .= '#' . $this->getDomId() . ' > nav > .nav-horizontal > .dropdown:after {';
                $css .= '  left:  ' . ($this->settings['menu_spacing'] + $symbol_width) . 'px;';
                $css .= '  right: ' . ($this->settings['menu_spacing'] + $symbol_width) . 'px;';
                $css .= '}';
            }
            else {
                $css .= '#' . $this->getDomId() . ' > nav > .nav-horizontal > .dropdown:after {';
                $css .= '  margin-left: ' . (($symbol_width + $this->settings['menu_spacing']) / 2 - 5) . 'px;';
                $css .= '}';
            }
        }

        // Dropdown menus

        if (!empty($this->settings['dropdown_min_width'])) {
            $css .= '#' . $this->getDomId() . ' > nav > .nav > li > .dropdown-menu {';
            $css .= '  min-width: ' . $this->settings['dropdown_min_width'] . 'px;';
            $css .= '}';
        }

        // Responsive menu

        $css .= '@media (max-width: 768px) {';
        if (!empty($this->settings['responsive_margin_left'])) {
            $css .= '  #' . $this->getDomId() . ' > nav {';
            $css .= '    margin-left:  ' . $this->settings['responsive_margin_left'] . 'px;';
            $css .= '    margin-right: ' . $this->settings['responsive_margin_left'] . 'px;';
            $css .= '  }';
        }
        if ($this->settings['orientation'] == 'horizontal' && $this->settings['responsive_stack']) {
            $css .= '  #' . $this->getDomId() . ' > nav > .nav > li > a {';
            $css .= '    line-height: ' . $menu_line_height . 'px;';
            $css .= '  }';
        }
        if ($this->settings['orientation'] == 'vertical' && $this->settings['menu_height'] > 31) {
            $css .= '  #' . $this->getDomId() . ' > nav > .nav > li > .tb_toggle {';
            $css .= '    margin-top: ' . (($this->settings['menu_height'] - 30) / 2) . 'px !important;';
            $css .= '  }';
        }
        if ($this->settings['orientation'] == 'vertical' && $this->settings['menu_height'] - $font_base >= 20) {
            $css .= '  #' . $this->getDomId() . ' > nav > .nav > li > ul {';
            $css .= '    margin-top: 0 !important;';
            $css .= '  }';
        }
        if ($level_2_height - $this->themeData->calculateLineHeight($level_2_size, $font_base) >= 20) {
            $css .= '  #' . $this->getDomId() . ' > nav > .nav > li > ul > li {';
            $css .= '    margin-bottom: 0 !important;';
            $css .= '  }';
            $css .= '  #' . $this->getDomId() . ' .tb_subcategories > .tb_multicolumn {';
            $css .= '    margin-top: -' . $font_base . 'px;';
            $css .= '  }';
        }
        if ($level_2_height - $this->themeData->calculateLineHeight($level_2_size, $font_base) >= 10) {
            $css .= '  #' . $this->getDomId() . ' > nav > .nav > li > ul > li > a {';
            $css .= '    padding-top: 0 !important;';
            $css .= '    padding-bottom: 0 !important;';
            $css .= '  }';
        }
        if ($this->settings['orientation'] == 'vertical' && $level_2_height > 31) {
            $css .= '  #' . $this->getDomId() . ' > nav > .nav > li > ul > li > .tb_toggle {';
            $css .= '    margin-top: ' . (($level_2_height - 30) / 2) . 'px !important;';
            $css .= '  }';
        }
        $css .= '}';

        $styleBuilder->addCss($css);
    }

    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'dropdown_menu' => array(
                '_label' => 'Dropdown Menu',
                'header' => array(
                    'label'       => 'Header',
                    'elements'    => '
                        [class].dropdown-menu li.dropdown-header
                    ',
                    'property'    => 'color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.header'
                ),
                'divider' => array(
                    'label'       => 'Divider',
                    'elements'    => '
                        [class].dropdown-menu li.divider
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.divider'
                ),
                'bg' => array(
                    'label'       => 'Background',
                    'elements'    => '
                        .dropdown:after,
                        .dropdown-menu,
                        .dropdown-menu:before
                    ',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.bg'
                ),
            ),
            'menu_level_1'  => array(
                '_label' => 'Level 1',
                'text' => array(
                    'label'       => 'Menu text',
                    'elements'    => '
                        nav > ul > li.dropdown:not(:hover) > a,
                        nav > ul > li.dropdown:not(:hover) > * > a,
                        nav > ul > li:not(.dropdown) > a:not(:hover),
                        nav > ul > li:not(.dropdown) > * > a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'bg' => array(
                    'label'       => 'Menu bg',
                    'elements'    => '
                        nav > ul > li.dropdown:not(:hover) > a,
                        nav > ul > li.dropdown:not(:hover) > * > a,
                        nav > ul > li:not(.dropdown) > a:not(:hover),
                        nav > ul > li:not(.dropdown) > * > a:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'column:body.accent'
                ),
                'text_hover' => array(
                    'label'       => 'Menu text (hover)',
                    'elements'    => '
                        nav > ul > li.dropdown:not(.tb_selected):hover > a,
                        nav > ul > li.dropdown:not(.tb_selected):hover > * > a,
                        nav > ul > li:not(.dropdown):not(.tb_selected) > a:hover,
                        nav > ul > li:not(.dropdown):not(.tb_selected) > * > a:hover,
                        nav > ul > li:hover > a .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'bg_hover' => array(
                    'label'       => 'Menu bg (hover)',
                    'elements'    => '
                        nav > ul > li.dropdown:not(.tb_selected):hover > a,
                        nav > ul > li.dropdown:not(.tb_selected):hover > * > a,
                        nav > ul > li:not(.dropdown):not(.tb_selected) > a:hover,
                        nav > ul > li:not(.dropdown):not(.tb_selected) > * > a:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'column:body.accent'
                ),
                'text_selected' => array(
                    'label'       => 'Menu text (selected)',
                    'elements'    => '
                        nav > ul > li.tb_selected > a,
                        nav > ul > li.tb_selected > * > a
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'bg_selected' => array(
                    'label'       => 'Menu bg (selected)',
                    'elements'    => '
                        nav > ul > li.tb_selected > a,
                        nav > ul > li.tb_selected > * > a
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'column:body.accent'
                ),
                'bullets' => array(
                    'label'       => 'Bullets',
                    'elements'    => '
                        nav > .tb_list_1 > li:not(.tb_nobullet):not(.tb_link):before,
                        nav > .tb_list_1 > li.tb_link > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.accent'
                ),
                'bullets_hover' => array(
                    'label'       => 'Bullets (hover)',
                    'elements'    => '
                        nav > ul.tb_list_1 > li:not(.tb_nobullet):not(.tb_link):hover:before,
                        nav > ul.tb_list_1 > li.tb_link:hover > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.accent'
                ),
                'icons' => array(
                    'label'       => 'Icons',
                    'elements'    => '
                        nav > ul > li > a:not(:hover) > .tb_text > .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'header' => array(
                    'label'       => 'Header',
                    'elements'    => 'nav > ul > li.dropdown-header.dropdown-header',
                    'property'    => 'color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'dropdown_menu.header'
                ),
                'divider' => array(
                    'label'       => 'Divider',
                    'elements'    => 'nav > ul > li.divider.divider',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'dropdown_menu.divider'
                ),
                'separator_symbol' => array(
                    'label'       => 'Separator (symbol)',
                    'elements'    => 'nav > ul > li:before',
                    'property'    => 'color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'separator_border' => array(
                    'label'       => 'Separator (border)',
                    'elements'    => '
                        nav > ul > li,
                        nav > ul > li:before
                    ',
                    'property'    => 'border-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'row:body.column_border'
                )
            ),
            'menu_level_2'  => array(
                '_label' => 'Level 2 (plain)',
                'text' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        nav > ul > li:not(.dropdown) > ul > li > a:not(:hover),
                        nav > ul > li:not(.dropdown):not(.dropdown) > ul > li > * > a:not(:hover),
                        nav > ul > li.tb_hidden_menu > ul > li > a:not(:hover),
                        nav > ul > li.tb_hidden_menu > ul > li > * > a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.links'
                ),
                'text_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        nav > ul > li:not(.dropdown) > ul > li > a:hover,
                        nav > ul > li:not(.dropdown) > ul > li.tb_hovered > a:hover,
                        nav > ul > li:not(.dropdown) > ul > li > * > a:hover,
                        nav > ul > li.tb_hidden_menu > ul > li > a:hover,
                        nav > ul > li.tb_hidden_menu > ul > li.tb_hovered > a:hover,
                        nav > ul > li.tb_hidden_menu > ul > li > * > a:hover,
                        nav > ul > li.tb_hidden_menu > ul > li:hover > a .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'Bullets',
                    'elements'    => '
                        nav > ul > li:not(.dropdown) > [class*="tb_list_"] > li:not(.tb_nobullet):not(.tb_link):before,
                        nav > ul > li:not(.dropdown) > [class*="tb_list_"] > li.tb_link > a:before,
                        nav > ul > li.tb_hidden_menu > [class*="tb_list_"] > li:not(.tb_nobullet):not(.tb_link):before,
                        nav > ul > li.tb_hidden_menu > [class*="tb_list_"] > li.tb_link > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.accent'
                ),
                'icons' => array(
                    'label'       => 'Icons',
                    'elements'    => '
                        nav > ul > li > ul > li > a:not(:hover) > .tb_text > .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'menu_level_2.text'
                )
            ),
            'menu_level_3'  => array(
                '_label' => 'Level 3 (plain)',
                'text' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        nav > ul > li:not(.dropdown) > ul > li:not(.dropdown) > ul > li > a:not(:hover),
                        nav > ul > li:not(.dropdown) > ul > li:not(.dropdown) > ul > li > * > a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.links'
                ),
                'text_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        nav > ul > li:not(.dropdown) > ul > li:not(.dropdown) > ul > li > a:hover,
                        nav > ul > li:not(.dropdown) > ul > li:not(.dropdown) > ul > li.tb_hovered > a:hover,
                        nav > ul > li:not(.dropdown) > ul > li:not(.dropdown) > ul > li > * > a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'Bullets',
                    'elements'    => '
                        nav > ul > li:not(.dropdown) > ul > li:not(.dropdown) > [class*="tb_list_"] > li:not(.tb_nobullet):not(.tb_link):before,
                        nav > ul > li:not(.dropdown) > ul > li:not(.dropdown) > [class*="tb_list_"] > li.tb_link > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.accent'
                ),
                'icons' => array(
                    'label'       => 'Icons',
                    'elements'    => '
                        nav > ul > li:not(.dropdown) > ul > li:not(.dropdown) > ul > li > a:not(:hover) > .tb_icon
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'menu_level_3.text'
                )
            ),
            'megamenu'      => array(
                '_label' => 'Megamenu',
                'accent' => array(
                    'label'       => 'Accent',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu .tb_main_color,
                        .tb_megamenu > [class].dropdown-menu .tb_hover_main_color:hover,
                        .tb_megamenu > [class].dropdown-menu .colorbox,
                        .tb_megamenu > [class].dropdown-menu .agree
                    ',
                    'property'    => 'color',
                    'color'       => '#bff222',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.accent'
                ),
                'accent_hover' => array(
                    'label'       => 'Accent (hover)',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu a.tb_main_color:hover,
                        .tb_megamenu > [class].dropdown-menu a.colorbox:hover,
                        .tb_megamenu > [class].dropdown-menu a.agree:hover,
                        .tb_megamenu > [class].dropdown-menu .tb_main_color_hover:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.accent_hover'
                ),
                'accent_bg' => array(
                    'label'       => 'Accent bg',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu .tb_main_color_bg,
                        .tb_megamenu > [class].dropdown-menu .tb_hover_main_color_bg:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'megamenu.accent'
                ),
                'accent_bg_hover' => array(
                    'label'       => 'Accent bg (hover)',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu a.tb_main_color_bg:hover,
                        .tb_megamenu > [class].dropdown-menu .tb_main_color_bg_hover:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'megamenu.accent_hover'
                ),
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '
                        .tb_megamenu > .dropdown-menu
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.text'
                ),
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.links'
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu a:not(.h1):not(.h2):not(.h3):not(.h4):not(.h5):not(.h6):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.links_hover'
                ),
                'text_links' => array(
                    'label'       => 'Links in text',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu .tb_text_wrap a:not(.btn):not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.text_links'
                ),
                'text_links_hover' => array(
                    'label'       => 'Links in text (hover)',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu .tb_text_wrap a:not(.btn):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.text_links_hover'
                ),
                'titles' => array(
                    'label'       => 'Titles',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu h1,
                        .tb_megamenu > [class].dropdown-menu h2,
                        .tb_megamenu > [class].dropdown-menu h3,
                        .tb_megamenu > [class].dropdown-menu h4,
                        .tb_megamenu > [class].dropdown-menu h5,
                        .tb_megamenu > [class].dropdown-menu h6,
                        .tb_megamenu > [class].dropdown-menu .h1:not(a),
                        .tb_megamenu > [class].dropdown-menu .h2:not(a),
                        .tb_megamenu > [class].dropdown-menu .h3:not(a),
                        .tb_megamenu > [class].dropdown-menu .h4:not(a),
                        .tb_megamenu > [class].dropdown-menu .h5:not(a),
                        .tb_megamenu > [class].dropdown-menu .h6:not(a),
                        .tb_megamenu > [class].dropdown-menu a.h1:not(:hover),
                        .tb_megamenu > [class].dropdown-menu a.h2:not(:hover),
                        .tb_megamenu > [class].dropdown-menu a.h3:not(:hover),
                        .tb_megamenu > [class].dropdown-menu a.h4:not(:hover),
                        .tb_megamenu > [class].dropdown-menu a.h5:not(:hover),
                        .tb_megamenu > [class].dropdown-menu a.h6:not(:hover),
                        .tb_megamenu > [class].dropdown-menu legend,
                        .tb_megamenu > [class].dropdown-menu .panel-heading,
                        .tb_megamenu > [class].dropdown-menu .box-heading
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.titles'
                ),
                'titles_hover' => array(
                    'label'       => 'Titles',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu a.h1:hover,
                        .tb_megamenu > [class].dropdown-menu a.h2:hover,
                        .tb_megamenu > [class].dropdown-menu a.h3:hover,
                        .tb_megamenu > [class].dropdown-menu a.h4:hover,
                        .tb_megamenu > [class].dropdown-menu a.h5:hover,
                        .tb_megamenu > [class].dropdown-menu a.h6:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.titles_hover'
                ),
                'bullets' => array(
                    'label'       => 'List bullets',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu[class*="tb_list_"] > li:before,
                        .tb_megamenu > [class].dropdown-menu[class*="tb_list_"] > li > a:before,
                        .tb_megamenu > [class].dropdown-menu [class*="tb_list_"] > li:before,
                        .tb_megamenu > [class].dropdown-menu [class*="tb_list_"] > li > a:before
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'megamenu.accent'
                ),
                'column_border' => array(
                    'label'       => 'Column border',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu .tb_separate_columns > [class*="col-"]
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.column_border'
                ),
                'subtle_base' => array(
                    'label'       => 'Subtle color base',
                    'elements'    => '
                        .tb_megamenu > [class].dropdown-menu
                    ',
                    'property'    => 'subtle',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:dropdown_menu.subtle_base'
                ),
            ),
        );

        return $default_colors;
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'level_1' => array(
                'section_name'      => 'Level 1',
                'elements'          => '
                    nav > .nav > li > a,
                    nav > .nav > li > span
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
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
            'level_2' => array(
                'section_name'      => 'Level 2',
                'elements'          => '
                    nav > .nav > li > ul > li > a,
                    nav > .nav > li > ul > li > span,
                    nav > .nav > li > .dropdown-menu > .tb_tabs > .nav > li > a
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
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
            'level_3' => array(
                'section_name'      => 'Level 3',
                'elements'          => '
                    nav > .nav > li > ul > li > ul > li > a,
                    nav > .nav > li > ul > li > ul > li > span
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
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
            'level_1_header' => array(
                'section_name'      => 'Level 1 header',
                'elements'          => '
                    nav > .nav > li.dropdown-header
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
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
            'level_2_header' => array(
                'section_name'      => 'Level 2 header',
                'elements'          => '
                    nav > .nav > li > ul > li.dropdown-header
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
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
            'level_3_header' => array(
                'section_name'      => 'Level 3 header',
                'elements'          => '
                    nav > .nav > li > ul > li > ul > li.dropdown-header
                ',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
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
            'category_title' => array(
                'section_name'      => 'Category information title',
                'elements'          => '
                    .tb_category_info h2,
                    .tb_category_info h3,
                    .tb_category_info h4
                ',
                'family'            => 'inherit',
                'size'              => 16,
            ),
            'category_description' => array(
                'section_name'      => 'Category information description',
                'elements'          => '
                    .tb_category_info .tb_desc
                ',
                'family'            => 'inherit',
                'size'              => 13,
            ),
        );
    }

    public function getPresentationTitle()
    {
        $title = $this->getName();
        if (!empty($this->settings['menu_id'])) {
            $menu = $this->engine->getSettingsModel('menu')->getScopeSettings($this->settings['menu_id']);
            $title = $menu['name'] . ' <span>(' . $title . ')</span>';
        }

        return $title;
    }

}