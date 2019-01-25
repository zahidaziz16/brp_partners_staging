<?php

require_once TB_THEME_ROOT . '/model/data/AreaItemData.php';

class Theme_LayoutBuilderModel extends TB_ExtensionModel
{
    public function getSystemPages()
    {
        static $result = null;

        if (null !== $result) {
            return $result;
        }

        $result = array();

        $dirs = sfFinder::type('dir')
            ->sort_by_name()
            ->in($this->engine->getContext()->getConfigDir() . '/system_widgets');

        // Must be defined in order to be used in included config files
        $gteOc2 = $this->engine->gteOc2();

        $theme_root_id = $this->context->getThemeId();
        if ($this->context->getThemeInfo('parent')) {
            $theme_root_id = $this->context->getThemeInfo('parent');
        }

        $theme_config_dir = $this->context->getThemeDir() . '/config/system_widgets';
        $theme_parent_config_dir = $this->context->getThemesDir() . '/' . $theme_root_id . '/config/system_widgets';
        $engine_config_dir = $this->context->getConfigDir() . '/system_widgets';

        foreach ($dirs as $dir) {
            $files = sfFinder::type('file')
                ->name('*.php')
                ->sort_by_name()
                ->in($dir);

            foreach ($files as $filename) {
                $filename_relative = substr($filename, strlen($engine_config_dir . '/'));

                if (is_file($theme_config_dir . '/' . $filename_relative)) {
                    $filename = $theme_config_dir . '/' . $filename_relative;
                } else
                if ($theme_config_dir != $theme_parent_config_dir && is_file($theme_parent_config_dir . '/' . $filename_relative)) {
                    $filename = $theme_parent_config_dir . '/' . $filename_relative;
                }

                $settings = require $filename;
                if (isset($settings['label']) && isset($settings['route'])) {
                    $result[basename($dir)][basename($filename, '.php')] = $settings;
                }
            }
        }

        foreach ($result as &$items) {
            ksort($items);
        }

        $system_settings = $this->getThemeModel()->getSetting('system');
        $custom = array();

        if (isset($system_settings['pages'])) {
            foreach ($system_settings['pages'] as $page) {
                $page['display'] = true;
                $widgets = array();
                foreach ($page['widgets'] as $slot => $is_active) {
                    if ($is_active) {
                        switch ($slot) {
                            case 'breadcrumbs':
                                $label  = 'Breadcrumbs';
                                $areas  = 'intro, content';
                                $locked = false;
                                break;
                            case 'page_title':
                                $label  = 'Page title';
                                $areas  = 'intro, content';
                                $locked = false;
                                break;
                            case 'page_content':
                                $label  = 'Page content';
                                $areas  = 'content';
                                $locked = true;
                                break;
                            default:
                                $label = $areas = $locked = '';
                                $slot  = false;
                        }
                        if (false !== $slot) {
                            $widgets[] = array(
                                'label'  => $label,
                                'slot'   => $slot,
                                'areas'  => $areas,
                                'locked' => $locked,
                            );
                        }
                    }
                }

                if (!empty($widgets)) {
                    $page['widgets'] = $widgets;
                    $custom[str_replace('-', '_', TB_Utils::slugify($page['route']))] = $page;
                }
            }
        }

        if (!empty($custom)) {
            $result['custom'] = $custom;
        }

        return $result;
    }

    public function getSystemPageForRoute($route)
    {
        foreach ($this->getSystemPages() as $group_items) {
            foreach ($group_items as $page) {
                if ($page['route'] == $route) {
                    return $page;
                }
            }
        }

        return false;
    }

    public function cleanSettingsDataBeforePersist(array &$settings)
    {
        if (isset($settings['box_shadow']) && !isset($settings['box_shadow']['rows']) || empty($settings['box_shadow']['rows'])) {
            unset($settings['box_shadow']);
        }

        if (isset($settings['border'])) {
            foreach (array('top', 'right', 'bottom', 'left') as $side) {
                if (empty($settings['border'][$side]['width'])) {
                    unset($settings['border'][$side]);
                }
            }

            if (empty($settings['border'])) {
                unset($settings['border']);
            }
        }

        if (isset($settings['border_radius'])) {
            foreach (array('top_left', 'top_right', 'bottom_left', 'bottom_right') as $side) {
                if (empty($settings['border_radius'][$side])) {
                    unset($settings['border_radius'][$side]);
                }
            }

            if (empty($settings['border_radius'])) {
                unset($settings['border_radius']);
            }
        }

        if (isset($settings['colors'])) {
            $this->cleanSettingsColorsBeforePersist($settings['colors']);

            if (empty($settings['colors'])) {
                unset($settings['colors']);
            }
        }

        if (isset($settings['font'])) {
            $this->cleanFontDataBeforePersist($settings['font']);

            if (empty($settings['font'])) {
                unset($settings['font']);
            }
        }

        $settings['cleaned'] = true;
    }

    public function cleanSettingsColorsBeforePersist(array &$colors)
    {
        foreach ($colors as $group_key => &$sections) {
            foreach ($sections as $section_key => &$section_values) {

                if (0 === strpos($section_key, '_')) {
                    unset($sections[$section_key]);
                } else {
                    if ($section_values['inherit'] && (!isset($section_values['force_print']) || !$section_values['force_print'])) {
                        unset($colors[$group_key][$section_key]);
                    } else {
                        unset($section_values['label']);
                        unset($section_values['force_print']);
                        unset($section_values['can_inherit']);
                        unset($section_values['id']);
                        unset($section_values['parent_id']);
                        unset($section_values['parent_color']);
                        unset($section_values['inherit_title']);

                        if ($section_values['inherit'] != 2) {
                            unset($section_values['inherit_key']);
                        }

                        if (empty($section_values['elements'])) {
                            unset($section_values['elements']);
                        }

                        if (empty($section_values['important'])) {
                            unset($section_values['important']);
                        }
                    }

                    unset($section_values['children'], $section_values['context']);
                }
            }

            if (empty($sections)) {
                unset($colors[$group_key]);
            }
        }
    }

    public function cleanFontDataBeforePersist(&$font_settings)
    {
        foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
            if (isset($font_settings[$language_code])) {
                foreach ($font_settings[$language_code] as $section_key => $section) {
                    $keep = array();
                    foreach (array(1 => 'size', 2 => 'line-height', 4 => 'letter-spacing', 8 => 'word-spacing', 16 => 'transform') as $mask_value => $mask_key) {
                        if (isset($font_settings[$language_code][$section_key]['inherit_mask']) && isset($font_settings[$language_code][$section_key][$mask_key]) && !($font_settings[$language_code][$section_key]['inherit_mask'] & $mask_value)) {
                            $keep[$mask_key] = $font_settings[$language_code][$section_key][$mask_key];
                        }
                    }
                    if ($section['family'] == 'inherit') {
                        if ($keep) {
                            $keep['inherit_mask'] = $font_settings[$language_code][$section_key]['inherit_mask'];
                            $keep['elements']     = $font_settings[$language_code][$section_key]['elements'];
                        }
                        $keep['family'] = 'inherit';
                        $keep['has_line_height'] = $font_settings[$language_code][$section_key]['has_line_height'];

                        if (isset($font_settings[$language_code][$section_key]['built-in']) && empty($font_settings[$language_code][$section_key]['built-in'])) {
                            $keep['built-in'] = $font_settings[$language_code][$section_key]['built-in'];
                            $keep['section_name'] = $font_settings[$language_code][$section_key]['section_name'];
                        }

                        unset($font_settings[$language_code][$section_key]);
                        $font_settings[$language_code][$section_key] = $keep;
                    } else {
                        $can_inherit = $font_settings[$language_code][$section_key]['can_inherit'];

                        unset($font_settings[$language_code][$section_key]['inherit']);
                        unset($font_settings[$language_code][$section_key]['can_inherit']);
                        unset($font_settings[$language_code][$section_key]['show_built_styles']);

                        if (!empty($font_settings[$language_code][$section_key]['built-in'])) {
                            unset($font_settings[$language_code][$section_key]['built-in']);
                            unset($font_settings[$language_code][$section_key]['section_name']);
                        }

                        $font = $font_settings[$language_code][$section_key];

                        if (empty($font['multiple_variants'])) {
                            unset($font_settings[$language_code][$section_key]['multiple_variants']);
                        }

                        if (empty($font['elements'])) {
                            unset($font_settings[$language_code][$section_key]['elements']);
                        }

                        if (empty($font['variant'])) {
                            unset($font_settings[$language_code][$section_key]['variant']);
                        }

                        if (empty($font['subsets'])) {
                            unset($font_settings[$language_code][$section_key]['subsets']);
                        }

                        if (isset($font['has_size'])) {
                            unset($font_settings[$language_code][$section_key]['has_size']);
                        }

                        if (empty($font['size']) || $can_inherit && !isset($keep['size'])) {
                            unset($font_settings[$language_code][$section_key]['size']);
                        }

                        if (isset($font['has_line_height'])) {
                            // unset($font_settings[$language_code][$section_key]['has_line_height']);
                        }

                        if (empty($font['line-height']) || $can_inherit && !isset($keep['line-height'])) {
                            unset($font_settings[$language_code][$section_key]['line-height']);
                        }

                        if (isset($font['has_spacing'])) {
                            unset($font_settings[$language_code][$section_key]['has_spacing']);
                        }

                        if (isset($font['word-spacing']) && !strlen($font['word-spacing']) || $can_inherit && !isset($keep['word-spacing'])) {
                            unset($font_settings[$language_code][$section_key]['word-spacing']);
                        }

                        if (isset($font['letter-spacing']) && !strlen($font['letter-spacing']) || $can_inherit && !isset($keep['letter-spacing'])) {
                            unset($font_settings[$language_code][$section_key]['letter-spacing']);
                        }

                        if (isset($font['has_effects'])) {
                            unset($font_settings[$language_code][$section_key]['has_effects']);
                        }

                        if (empty($font['transform']) || $can_inherit && !isset($keep['transform'])) {
                            unset($font_settings[$language_code][$section_key]['transform']);
                        }
                    }
                }

                if (empty($font_settings[$language_code])) {
                    unset($font_settings[$language_code]);
                }
            }
        }
    }

    public function filterSettings(array &$data, $section, $default_colors = null, $parent_data = array())
    {
        if (TB_Utils::strStartsWith($section, 'area_')) {
            $default_vars = AreaItemData::getAreaStyleVars($section);
        } else
        if ($section == 'widgets_row') {
            $default_vars = AreaItemData::getRowStyleVars();
        } else
        if ($section == 'row_column') {
            if (!isset($data['grid_proportion'])) {
                $data['grid_proportion'] = '1_1';
            }
            $default_vars = AreaItemData::getColumnStyleVars($data['grid_proportion']);
        } else {
            throw new Exception('Invalid section type <strong>' . $section . '</strong>');
        }

        if (empty($data)) {
            $result = $default_vars;
        } else {
            $result = TB_FormHelper::initFlatVarsSimple($default_vars, $data);
        }

        //Viewport
        if ($section == 'row_column') {
            $result['viewport'] = TB_FormHelper::initFlatVarsSimple($default_vars['viewport'], $result['viewport']);
            foreach ($result['viewport'] as $size => $view_port) {
                $result['viewport'][$size]['layout'] = TB_FormHelper::initFlatVarsSimple($default_vars['viewport'][$size]['layout'], $view_port['layout']);
            }
        }

        // Layout
        $result['layout'] = TB_FormHelper::initFlatVarsSimple($default_vars['layout'], $result['layout']);

        // Box Shadows
        $result['box_shadow'] = TB_FormHelper::initFlatVarsSimple($default_vars['box_shadow'], $result['box_shadow']);
        foreach ($result['box_shadow']['rows'] as &$row) {
            $row = TB_FormHelper::initFlatVarsSimple(AreaItemData::getBoxShadowRow(), $row);
        }

        // Background
        if (!empty($result['background']['solid_color_inherit_key'])) {
            $default_vars['background']['solid_color_inherit_key'] = '';
        }
        $result['background'] = TB_FormHelper::initFlatVarsSimple($default_vars['background'], $result['background']);
        if (isset($result['background']['solid_color_inherit_key'])) {
            $result['background']['solid_color'] = TB_ColorSchemer::getInstance()->resolveParentColor($this->engine->getThemeData()->colors, $result['background']['solid_color_inherit_key']);
        }

        foreach ($result['background']['rows'] as &$bg_row) {
            switch ($bg_row['background_type']) {
                case 'gradient':
                    $bg_row = TB_FormHelper::initFlatVarsSimple(AreaItemData::getBackgroundGradientRow(), $bg_row);
                    foreach ($bg_row['colors'] as &$color_row) {
                        $color_row = TB_FormHelper::initFlatVarsSimple(AreaItemData::getBackgroundColorRow(), $color_row);
                    }
                    break;
                case 'image':
                    $bg_row = TB_FormHelper::initFlatVarsSimple(AreaItemData::getBackgroundImageRow(), $bg_row);
                    break;
            }
        }

        // Border
        $result['border'] = TB_FormHelper::initFlatVarsSimple($default_vars['border'], $result['border']);

        foreach (array('top', 'right', 'bottom', 'left') as $side) {
            $result['border'][$side] = TB_FormHelper::initFlatVarsSimple(AreaItemData::getBorderRow(), $result['border'][$side]);
        }

        // Border Radius
        $result['border_radius'] = TB_FormHelper::initFlatVarsSimple($default_vars['border_radius'], $result['border_radius']);

        // Font
        if (empty($data['skip_font'])) {
            $result['font'] = TB_FormHelper::initLangVarsSimple($default_vars['font'], $result['font'], $this->engine->getEnabledLanguages());
            foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
                foreach ($result['font'][$language_code] as $name => &$font_section) {
                    $font_section = TB_FormHelper::initFlatVarsSimple($default_vars['font'][$name], $font_section);
                }
            }
        }

        //Colors
        if (null !== $default_colors) {
            if (empty($result['colors'])) {
                $result['colors'] = $default_colors;
            } else {
                foreach ($result['colors'] as $group_key => &$sections) {
                    foreach ($sections as $section_key => &$section_values) {
                        if (isset($default_colors[$group_key]['sections'][$section_key]) && 0 !== strpos($section_key, '_')) {
                            $section_values = TB_FormHelper::initFlatVarsSimple($default_colors[$group_key]['sections'][$section_key], $section_values);
                        }
                    }
                }
            }
        }

        $data = $result;

        return $data;
    }
}