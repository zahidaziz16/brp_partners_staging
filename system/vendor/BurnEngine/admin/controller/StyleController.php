<?php

class Theme_Admin_StyleController extends TB_AdminController
{
    protected $section_map = array(
        'index' => array(
            'ajax'     => false,
            'template' => 'theme_style'
        ),
        'common' => array(
            'ajax'     => false,
            'template' => 'theme_style_common'
        ),
        'wrapper' => array(
            'ajax'     => true,
            'group'    => 'style_section',
            'template' => 'theme_style_section'
        ),
        'header' => array(
            'ajax'     => true,
            'group'    => 'area',
            'template' => 'theme_style_area'
        ),
        'intro' => array(
            'ajax'     => true,
            'group'    => 'area',
            'template' => 'theme_style_area'
        ),
        'content' => array(
            'ajax'     => true,
            'group'    => 'area',
            'template' => 'theme_style_area'
        ),
        'footer' => array(
            'ajax'     => true,
            'group'    => 'area',
            'template' => 'theme_style_area'
        ),
        'bottom' => array(
            'ajax'     => true,
            'group'    => 'style_section',
            'template' => 'theme_style_section'
        ),
        'presets' => array(
            'ajax'     => true,
            'group'    => 'style_presets',
            'template' => 'theme_style_presets'
        )
    );

    protected function renderStyleSectionGroup($section)
    {
        $style_settings = $this->themeData['theme_settings']['style'];

        $this->data['settings']  = $style_settings[$section];
        $this->data['menu_name'] = ucfirst($section) . ' Styles';
    }

    protected function renderStylePresetsGroup()
    {
        $current_preset = array(
            'id'       => '0',
            'styles'   => array(),
            'is_theme' => 0
        );
        $options = array();

        $custom_presets = $this->engine->getDbSettingsHelper()->getGroup('preset', 0);
        $theme_presets = $this->engine->getDbSettingsHelper()->getGroup('preset_' . $this->engine->getThemeId(), 0);

        foreach (array_merge($theme_presets, $custom_presets) as $preset) {
            $options[$preset['id']] = array(
                'name'     => $preset['name'],
                'is_theme' => $preset['is_theme']
            );
            if (!empty($this->request->get['preset_id']) && $preset['id'] == $this->request->get['preset_id']) {
                $current_preset = $preset;
            }
        }

        if ($current_preset['id'] || isset($this->request->get['preset_id']) && (string) $this->request->get['preset_id'] == '0') {
            foreach (array('box', 'title') as $preset_section) {
                if (!isset($current_preset['styles'][$preset_section])) {
                    $current_preset['styles'][$preset_section] = array();
                }
            }
            /** @var Theme_Admin_PresetsPlugin $presetsPlugin */
            $presetsPlugin = $this->extension->getPlugin('presets');
            $presetsPlugin->initPresetSections($current_preset['styles']);
        }

        $grouped_font_keys = array();
        foreach (array_merge(array_keys(PresetsData::getBoxFontGroups()), array('box')) as $box_font_group) {
            foreach(array_keys(PresetsData::getFonts($box_font_group)) as $font_key) {
                $grouped_font_keys[$font_key] = $box_font_group;
            }
        }
        $grouped_font_keys['title'] = 'title';

        $this->data['settings']          = $current_preset['styles'];
        $this->data['is_theme']          = $current_preset['is_theme'];
        $this->data['preset_id']         = (string) $current_preset['id'];
        $this->data['preset_options']    = $options;
        $this->data['box_color_groups']  = PresetsData::getBoxColorGroups();
        $this->data['box_font_groups']   = PresetsData::getBoxFontGroups();
        $this->data['grouped_font_keys'] = $grouped_font_keys;
    }

    public function loadAreaPreset()
    {
        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $area_type = $this->request->get['area_type'];
        $area_name = $this->request->get['area_name'];
        $area_id   = $this->request->get['area_id'];

        $area_key = strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id;
        $area_settings = $this->engine->getWidgetManager()->getAreaStyle($area_name . '_' . $area_key);

        /** @var Theme_Admin_LayoutBuilderModel $layoutBuilder */
        $layoutBuilder = $this->getModel('layoutBuilder');

        if (empty($area_settings)) {
            try {
                $params = $layoutBuilder->determineAreaParams($area_name, $area_type, $area_id, 'style');
            } catch (Exception $e) {
                return $this->sendJsonError($e->getMessage());
            }

            list(, $settings_key) = $params;
            $area_settings = $this->engine->getWidgetManager()->getAreaStyle($area_name . '_' . $settings_key);
        }

        $preset = null;
        foreach (array_merge($this->engine->getSettingsModel('preset', 0)->getValues(), $this->engine->getSettingsModel('preset_' . $this->engine->getThemeId(), 0)->getValues()) as $preset) {
            if ($preset['id'] == $this->request->get['preset_id']) {
                break;
            }
        }

        if (empty($preset['styles']['box'])) {
            return $this->sendJsonError('Invalid preset');
        }

        $style_settings = $preset['styles']['box'];

        if (!empty($style_settings['font'])) {
            foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
                if (!isset($style_settings['font'][$language_code])) {
                    $style_settings['font'][$language_code] = reset($style_settings['font']);
                }
            }
        }

        unset($area_settings['box_shadow'], $area_settings['background'], $area_settings['border_radius']);

        $area_settings = array_replace_recursive($area_settings, $preset['styles']['box']);
        $colors = AreaItemData::getAreaColors($area_name);
        $preset_color_keys = !empty($style_settings['colors']) ? array_keys(array_intersect_key($style_settings['colors'], $colors)) : array();

        if ($preset_color_keys) {
            foreach (array_keys($colors) as $color_group_key) {
                if (!in_array($color_group_key, $preset_color_keys) && isset($area_settings['colors'][$color_group_key])) {
                    $colors[$color_group_key] = array_replace_recursive($colors[$color_group_key], $area_settings['colors'][$color_group_key]);
                }
            }
        }

        foreach ($colors as $color_group_key => &$color_sections) {
            if (isset($style_settings['colors'][$color_group_key])) {
                $color_sections = array_replace_recursive($color_sections, array_intersect_key($style_settings['colors'][$color_group_key], $color_sections));
                foreach ($color_sections as $color_section_key => &$color_section_values) {
                    if ($color_section_key != "_label") {
                        $color_section_values['inherit'] = 0;
                    }
                }
            }
        }

        $area_settings['colors'] = $colors;

        $default_fonts = AreaItemData::getFonts();
        $preset_font_keys = !empty($style_settings['font']) ? array_keys(array_intersect_key(reset($style_settings['font']), $default_fonts)) : array();

        if ($preset_font_keys) {
            foreach ($area_settings['font'] as &$settings_fonts) {
                $settings_fonts = array_intersect_key($settings_fonts, $default_fonts);

                foreach (array_keys($default_fonts) as $font_group_key) {
                    if (!in_array($font_group_key, $preset_font_keys) && isset($settings_fonts[$font_group_key])) {
                        $settings_fonts[$font_group_key] = array_replace_recursive($default_fonts[$font_group_key], $settings_fonts[$font_group_key]);
                    }
                }
            }
        }

        $this->engine->getStyleSettingsModel()->setAndPersistScopeSettings($area_name . '_' .$area_key, $area_settings);
        /*
        $layoutBuilder->filterAreaSettings($area_settings, 'area_' . $area_name);

        if (isset($area_settings['background']['rows']) && !empty($area_settings['background']['rows'])) {
            foreach ($area_settings['background']['rows'] as &$bg_row) {
                if ($bg_row['background_type'] == 'image') {
                    if (!empty($bg_row['image'])  && file_exists(DIR_IMAGE . $bg_row['image'])) {
                        $bg_row['preview'] = $this->engine->getOcToolImage()->resize($bg_row['image'], 100, 100);
                    } else {
                        $bg_row['image'] = '';
                        $bg_row['preview'] = $this->getThemeModel()->getNoImage();
                    }
                }
            }
        }

        $preset_options = array();
        foreach (array_merge($this->engine->getSettingsModel('preset', 0)->getValues(), $this->engine->getSettingsModel('preset_' . $this->engine->getThemeId(), 0)->getValues()) as $preset) {
            $preset_options[$preset['id']] = $preset['name'];
        }

        $this->data['pages']          = $this->getThemeModel()->getInformationPages();
        $this->data['layouts']        = $layoutBuilder->getLayoutsExcludingRoute(array('common/home', 'product_category'));
        $this->data['modified']       = $layoutBuilder->buildModifiedMenu($area_name, 'style');
        $this->data['area_name']      = $area_name;
        $this->data['area_type']      = $area_type;
        $this->data['area_id']        = $area_id;
        $this->data['area_key']       = $area_key;
        $this->data['menu_name']      = ucfirst($area_name) . ' Styles';
        $this->data['settings']       = $area_settings;
        $this->data['inherit_msg']    = '';
        $this->data['override_msg']   = $override_msg;
        $this->data['preset_options'] = $preset_options;

        $this->data['display_override_msg'] = $display_override_msg;
        $this->data['category_levels']      = $this->getCategoryModel()->getCategoryLevels();
        $this->data['store_has_categories'] = $this->getCategoryModel()->storeHasCategories();

        $html = $this->fetchTemplate($this->section_map[$area_name]['template'], $this->data);
        */
        return $this->sendJsonSuccess('Preset loaded');
    }

    public function getPresetColorGroup()
    {
        $colors = PresetsData::getColors((string) $this->request->get['group_id']);
        /** @var Theme_Admin_PresetsPlugin $presetsPlugin */
        $presetsPlugin = $this->extension->getPlugin('presets');
        $presetsPlugin->initColors($colors);

        $this->data['presets_section_colors'] = $colors;
        $this->data['presets_section'] = 'box';

        $this->renderTemplate('theme_style_presets_colors');
    }

    public function getPresetFontGroup()
    {
        $group_id = (string) $this->request->get['group_id'];
        $fonts = PresetsData::getFonts($group_id);

        $result = '';
        foreach ($fonts as $name => $font_section) {
            $result .= $this->fetchTemplate('theme_style_presets_typography_item', array(
                'group_id'        => $group_id,
                'name'            => $name,
                'font_section'    => $font_section,
                'presets_section' => 'box',
                'language_code'   => '{{language_code}}',
                'font_data'       => $this->themeData->fontData
            ));
        }

        $this->setOutput($result);
    }

    public function removePreset()
    {
        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $this->engine->getSettingsModel('preset', 0)->deleteScopeSettings((string) $this->request->get['preset_id'], 0);
        $this->engine->getSettingsModel('preset_' . $this->engine->getThemeId(), 0)->deleteScopeSettings((string) $this->request->get['preset_id'], 0);
        $this->engine->wipeVarsCache('preset_ids*');

        return $this->sendJsonSuccess('Preset removed');
    }

    protected function renderAreaGroup($area_name)
    {
        $area_type = 'global';
        if (isset($this->request->get['area_type'])) {
            $type = (string) $this->request->get['area_type'];
            if (in_array($type, array('global', 'home', 'page', 'category', 'layout', 'system', 'product'))) {
                $area_type = $type;
            }
        }

        if (isset($this->request->get['area_id'])) {
            $area_id = (string) $this->request->get['area_id'];
        } else {
            $area_id = $area_type;
        }

        $area_key = strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id;
        $area_settings = $this->engine->getWidgetManager()->getAreaStyle($area_name . '_' . $area_key);

        $inherit_msg = '';
        $display_override_msg = false;
        /** @var Theme_Admin_LayoutBuilderModel $layoutBuilder */
        $layoutBuilder = $this->getModel('layoutBuilder');

        if (empty($area_settings)) {
            try {
                $params = $layoutBuilder->determineAreaParams($area_name, $area_type, $area_id, 'style');
            } catch (Exception $e) {
                $this->sendJsonError($e->getMessage());
                return;
            }

            list(, $settings_key) = $params;

            $area_settings = $this->engine->getWidgetManager()->getAreaStyle($area_name . '_' . $settings_key);
            $inherit_msg = $layoutBuilder->buildInheritInformationMessage($settings_key, $area_type);
        } else {
            $display_override_msg = true;
        }

        $override_msg = $layoutBuilder->buildOverrideInformationMessage($area_name, $area_type, $area_id, 'style');

        if (empty($area_settings)) {
            $area_settings = array();
        }

        $layoutBuilder->filterAreaSettings($area_settings, 'area_' . $area_name);

        if (isset($area_settings['background']['rows']) && !empty($area_settings['background']['rows'])) {
            foreach ($area_settings['background']['rows'] as &$bg_row) {
                if ($bg_row['background_type'] == 'image') {
                    if (!empty($bg_row['image'])  && file_exists(DIR_IMAGE . $bg_row['image'])) {
                        $bg_row['preview'] = $this->engine->getOcToolImage()->resize($bg_row['image'], 100, 100);
                    } else {
                        $bg_row['image'] = '';
                        $bg_row['preview'] = $this->getThemeModel()->getNoImage();
                    }
                }
            }
        }

        $preset_options = array();
        foreach (array_merge($this->engine->getSettingsModel('preset', 0)->getValues(), $this->engine->getSettingsModel('preset_' . $this->engine->getThemeId(), 0)->getValues()) as $preset) {
            $preset_options[$preset['id']] = $preset['name'];
        }

        $this->data['pages']          = $this->getThemeModel()->getInformationPages();
        $this->data['layouts']        = $layoutBuilder->getLayoutsExcludingRoute(array('common/home', 'product_category'));
        $this->data['modified']       = $layoutBuilder->buildModifiedMenu($area_name, 'style');
        $this->data['area_name']      = $area_name;
        $this->data['area_type']      = $area_type;
        $this->data['area_id']        = $area_id;
        $this->data['area_key']       = $area_key;
        $this->data['menu_name']      = ucfirst($area_name) . ' Styles';
        $this->data['settings']       = $area_settings;
        $this->data['inherit_msg']    = $inherit_msg;
        $this->data['override_msg']   = $override_msg;
        $this->data['preset_options'] = $preset_options;

        $this->data['display_override_msg'] = $display_override_msg;
        $this->data['category_levels']      = $this->getCategoryModel()->getCategoryLevels();
        $this->data['store_has_categories'] = $this->getCategoryModel()->storeHasCategories();
    }
}