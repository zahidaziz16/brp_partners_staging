<?php

require_once TB_THEME_ROOT . '/model/data/PresetsData.php';
require_once TB_THEME_ROOT . '/model/data/MainColorsData.php';
require_once tb_modification(dirname(__FILE__) . '/StylePlugin.php');

class Theme_Admin_PresetsPlugin extends Theme_Admin_StylePlugin
{
    protected $sections = array('box', 'title');

    public function getConfigKey()
    {
        return 'presets';
    }

    public function initPresetSections(&$settings)
    {
        $this->initStyleSections($settings);

        $all_default_box_fonts = PresetsData::getFonts('box');
        foreach (array_keys(PresetsData::getBoxFontGroups()) as $box_group_id) {
            $all_default_box_fonts = array_merge($all_default_box_fonts, PresetsData::getFonts($box_group_id));
        }

        foreach ($this->sections as $section) {

            if (!isset($settings[$section]['colors'])) {
                $default_colors = PresetsData::getColors($section);
                $this->initColors($default_colors);
                $settings[$section]['colors'] = $default_colors;
            } else {
                foreach ($settings[$section]['colors'] as $group_key => &$sections) {
                    if (false !== strpos($group_key, '__')) {
                        $default_colors = PresetsData::getColors(substr($group_key, 0, strpos($group_key, '__')));
                    } else {
                        $default_colors = PresetsData::getColors($group_key);
                    }
                    $this->initColors($default_colors);

                    foreach ($sections as $section_key => &$section_values) {
                        if (isset($default_colors[$group_key][$section_key])) {
                            unset($section_values['elements'], $section_values['important'], $section_values['property']);
                            $section_values = array_replace($default_colors[$group_key][$section_key], $section_values);
                        }
                    }
                    $sections['_label'] = $default_colors[$group_key]['_label'];
                }
            }

            if (!isset($settings[$section]['font'])) {
                $settings[$section]['font'] = TB_FormHelper::initLangVarsSimple(PresetsData::getFonts($section), array(), $this->engine->getEnabledLanguages());
            } else {
                foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
                    if (!isset($settings[$section]['font'][$language_code])) {
                        $locale_map = $this->engine->getLocaleMap();

                        if (array_key_exists($language_code, $locale_map) && isset($settings[$section]['font'][$locale_map[$language_code]])) {
                            $font_items = $settings[$section]['font'][$locale_map[$language_code]];
                        } else
                        if (($code_key = array_search($language_code, $locale_map)) && isset($settings[$section]['font'][$code_key])) {
                            $font_items = $settings[$section]['font'][$code_key];
                        } else {
                            $font_items = reset($settings[$section]['font']);
                        }
                        
                        foreach ($font_items as $font_key => &$font_item) {
                            if ($section == 'box') {
                                $default_font_item = $all_default_box_fonts[$font_key];
                            } else {
                                $default_font_item = array_replace(MainFontsData::getDefaultFontItem(), PresetsData::getFonts('title'));
                            }

                            $font_item = array_replace($default_font_item, $font_item);
                        }

                        $settings[$section]['font'][$language_code] = $font_items;
                    } else {
                        if ($section == 'box') {
                            $settings[$section]['font'][$language_code] = array_replace_recursive(array_intersect_key($all_default_box_fonts, $settings[$section]['font'][$language_code]), $settings[$section]['font'][$language_code]);
                        } else {
                            $settings[$section]['font'][$language_code] = array_replace_recursive(PresetsData::getFonts($section), $settings[$section]['font'][$language_code]);
                        }
                    }
                }

                $settings[$section]['font'] = array_intersect_key($settings[$section]['font'], $this->engine->getEnabledLanguages());
            }
        }
    }

    public function initColors(&$colors)
    {
        if (!isset($colors)) {
            return;
        }

        foreach ($colors as $group_key => &$sections) {
            foreach ($sections as $section_key => &$section_values) {
                if ($section_key == '_label' || empty($section_values['inherit_key'])) {
                    continue;
                }

                $inherit_parent = 'self';
                $inherit_key = $section_values['inherit_key'];

                if (false !== strpos($inherit_key, ':')) {
                    list ($inherit_parent, $inherit_key) = explode(':', $section_values['inherit_key']);
                    if ($inherit_parent != 'theme') {
                        throw new Exception("Invalid inherit parent: {$inherit_parent}");
                    }
                }

                if (false === strpos($inherit_key, '.') || count(explode('.', $inherit_key)) > 2) {
                    throw new Exception("Wrong inherit key {$inherit_key} for {$group_key}[{$section_key}]");
                }

                $parent_default_colors = $inherit_parent == 'theme' ? $this->getThemeData()->colors : $colors;

                list ($inherit_group, $inherit_section) = explode('.', $inherit_key);

                if (!isset($parent_default_colors[$inherit_group][$inherit_section])) {
                    $msg = 'Color inheritance issue';
                    $msg .= "\nCurrent rule: {$group_key} -> {$section_key} -> {$inherit_key} \nMissing parent: {$inherit_parent}[$inherit_group][$inherit_section]";

                    throw new Exception($msg);
                }

                if ($inherit_parent == 'self' && !empty($section_values['hidden'])) {
                    if (!isset($colors[$inherit_group][$inherit_section]['children'])) {
                        $colors[$inherit_group][$inherit_section]['children'] = array();
                    }
                    $colors[$inherit_group][$inherit_section]['children'][] = $group_key . '_' . $section_key;
                    $section_values['parent_id'] = $inherit_group . '_' . $inherit_section;
                    unset($section_values['color']);
                } else
                if ($inherit_parent == 'theme') {
                    unset($section_values['color']);
                    $section_values = array_replace($parent_default_colors[$inherit_group][$inherit_section], $section_values);
                    unset($section_values['force_print'], $section_values['can_inherit'], $section_values['inherit'], $section_values['inherit_key']);
                }

                if ($inherit_parent == 'self') {
                    //unset($section_values['color']);
                }
            }
        }

        foreach ($colors as &$group_values) {
            foreach ($group_values as $section_key => &$section_values) {
                if (0 === strpos($section_key, '_')) {
                    continue;
                }

                if (!isset($section_values['color']) || !empty($section_values['inherit_key'])) {
                    $parent = $this->resolveParentElement($colors, $section_values['inherit_key']);
                    unset($section_values['color']);
                    $section_values = array_replace($parent, $section_values);
                    unset($section_values['inherit_key']);
                }
            }
        }
    }

    protected function resolveParentElement($colors, $inherit_key)
    {

        list ($inherit_group, $inherit_section) = explode('.', $inherit_key);

        if (!isset($colors[$inherit_group][$inherit_section])) {
            throw new Exception('The parent defined by ' . $inherit_key . ' does not exist');
        }

        $parent = $colors[$inherit_group][$inherit_section];

        if (!isset($parent['color'])) {
            if (empty($parent['inherit_key'])) {
                throw new Exception('Empty parent inherit key : ' . $inherit_key);
            }

            if ($parent['inherit_key'] == $inherit_key) {
                throw new Exception('Infinite recursion detected when trying to resolve the color, defined by inherit key ' . $inherit_key);
            }

            return $this->resolveParentElement($colors, $parent['inherit_key']);
        }

        return $parent;
    }

    public function saveData($post_data)
    {
        if (!isset($post_data['presets']) || !isset($post_data['preset_record'])) {
            return;
        }

        $data = array(
            'id'       => (string) $post_data['preset_record']['id'],
            'name'     => (string) $post_data['preset_record']['name'],
            'styles'   => (array) $post_data['presets'],
            'is_theme' => !empty($post_data['preset_record']['is_theme']) ? 1 : 0
        );

        $group = $data['is_theme'] ? 'preset_' . $this->engine->getThemeId() : 'preset';
        $store_id = $data['is_theme'] ? $this->context->getStoreId() : 0;

        if (!$this->getThemeModel()->keyExists($data['id'], $group, $store_id)) {
            $data['id'] = TB_Utils::slugify($data['name']) . '-' . $data['id'];
            $this->engine->wipeVarsCache('preset_ids*');
        }

        $this->engine->getSettingsModel($group, $store_id)->setAndPersistScopeSettings($data['id'], $data);
    }
}