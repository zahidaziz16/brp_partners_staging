<?php

class Theme_Admin_ImportModel extends TB_ExtensionModel
{
    private $current_error;

    public function getTheme($theme_id)
    {
        $theme_dir = $this->engine->getContext()->getThemesDir() . '/' . $theme_id;

        if (!is_dir($theme_dir)) {
            return 'A theme with the provided <strong>' . $theme_id . '</strong> id does not exists';
        }

        if (false === ($data = file_get_contents($theme_dir . '/data.bin'))) {
            return 'The theme data file cannot be found';
        }

        $check = $this->checkImportSettings($data);
        if ($check['error']) {
            return $check['error'];
        }
        $import_data = $check['data'];

        if (!is_file($theme_dir . '/info.ini')) {
            return 'The theme info file cannot be found';
        }

        $info = parse_ini_file($theme_dir . '/info.ini');
        if (false === $info) {
            return 'Cannot parse info file: ' . $theme_dir . '/info.ini';
        }

        if (empty($info['version']) || empty($info['name'])) {
            return 'The info file is invalid: ' . $theme_dir . '/info.ini';
        }

        if (empty($import_data['theme_id']) || $theme_id != $import_data['theme_id']) {
            return 'Invalid theme id';
        }

        if (!is_file($theme_dir . '/config.php')) {
            return 'The theme config file cannot be found';
        }
        $config = require $theme_dir . '/config.php';

        return array($info, $import_data, $config);
    }

    public function resetTheme($theme_id, $store_id)
    {
        $result = $this->getTheme($theme_id);
        if (!is_array($result)) {
            return $result;
        }

        list(, $import_data) = $result;

        $this->deleteDefaultAreaSettings($store_id);

        $enabled_languages = $this->engine->getEnabledLanguages(false);

        foreach ($import_data['area_settings'] as $style_key => $style_settings) {
            if (!TB_Utils::strEndsWith($style_key, '__default')) {
                continue;
            }

            $this->modifyStyleSettings($style_settings, $enabled_languages);
            $this->engine->getDbSettingsHelper()->persistKey($style_key, $store_id, 'style', $style_settings);
        }

        foreach ($import_data['builder'] as $builder_key => $builder_settings) {
            if (!TB_Utils::strEndsWith($builder_key, '__default')) {
                continue;
            }

            $this->modifyBuilderSettings($builder_settings, $enabled_languages);
            $this->engine->getDbSettingsHelper()->persistKey($builder_key, $store_id, 'builder', $builder_settings);
        }

        if (!empty($import_data['theme_templates'])) {
            foreach ($import_data['theme_templates'] as $template_key => $template) {
                $this->engine->getDbSettingsHelper()->persistKey($template_key, 0, 'template_' . $theme_id, $template);
            }
        }

        if (!empty($import_data['theme_skins'])) {
            foreach ($import_data['theme_skins'] as $skin_key => $skin) {
                $this->engine->getDbSettingsHelper()->persistKey($skin_key, $store_id, 'skin_' . $theme_id, $skin);
            }
        }

        if (!empty($import_data['theme_presets'])) {
            foreach ($import_data['theme_presets'] as $preset_key => $preset) {
                $this->handlePresetImages($preset);
                $this->modifyStyleSettings($preset['styles']['box'], $enabled_languages);
                $this->modifyStyleSettings($preset['styles']['title'], $enabled_languages);

                $this->engine->getDbSettingsHelper()->persistKey($preset_key, $store_id, 'preset_' . $theme_id, $preset);
            }
        }

        return true;
    }

    public function saveTheme($theme_id, $store_id, $demo_data = false)
    {
        $result = $this->getTheme($theme_id);
        if (!is_array($result)) {
            return $result;
        }

        $current_theme_settings = false === $demo_data ? $this->getThemeModel()->getSettings(true) : array();
        $enabled_languages = $this->engine->getEnabledLanguages(false);

        list($info, $import_data) = $result;

        $info['id']             = $theme_id;
        $info['engine_version'] = $this->engine->getVersion();
        $info['install_date']   = date('d.m.Y H:i');
        $info['oc_version']     = VERSION;
        $info['languages']      = $enabled_languages;

        $theme_dir = $this->engine->getContext()->getThemesDir() . '/' . $theme_id;
        $images_dirs = array ($theme_dir . '/images/system');

        if ($demo_data) {
            $images_dirs[] = $theme_dir . '/demo/images';
            $available_themes = $this->engine->getThemes();
            $parent = $available_themes[$theme_id]['parent'];
            if (!empty($parent)) {
                $images_dirs[] = $this->engine->getContext()->getThemesDir() . '/' . $parent . '/demo/images';
            }
        }

        foreach ($images_dirs as $image_dir) {
            if (!is_dir($image_dir)) {
                continue;
            }

            $images = array();
            foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($image_dir, 4096), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
                if ($path->isDir()) {
                    continue;
                }

                $new_path = $this->context->getImageDir() . '/' . ($this->engine->gteOc2() ? 'catalog' : 'data');
                $images[$path->getPathname()] = $new_path . TB_Utils::strGetAfter($path->getPathname(), $image_dir);
            }

            foreach ($images as $src_image => $dest_image) {
                if (!TB_Utils::makePath(dirname($dest_image))) {
                    continue;
                }

                if (is_file($dest_image)) {
                    unlink($dest_image);
                }
                copy($src_image, $dest_image);
            }
        }

        if ($current_theme_info = $this->engine->getDbSettingsHelper('setting')->getKey(TB_Engine::getName() . '_theme', $store_id, TB_Engine::getName())) {
            $this->engine->getDbSettingsHelper()->deleteGroup('template_' . $current_theme_info['id'], $store_id);
            $this->engine->getDbSettingsHelper()->deleteGroup('preset_'   . $current_theme_info['id'], $store_id);
            $this->engine->getDbSettingsHelper()->deleteGroup('skin_'     . $current_theme_info['id'], $store_id);
        }

        $this->deleteDefaultAreaSettings($store_id);
        $this->engine->getDbSettingsHelper()->deleteGroup('style', $store_id);
        $this->engine->getDbSettingsHelper()->deleteGroup('theme', $store_id);

        if ($demo_data) {
            $import_data['area_settings']            = array_merge($import_data['area_settings'], $demo_data['area_settings']);
            $import_data['theme_settings']           = array_merge($import_data['theme_settings'], $demo_data['theme_settings']);
            $import_data['theme_settings']['colors'] = $demo_data['colors'];
            $import_data['menu']                     = !empty($demo_data['menu']) ? $demo_data['menu'] : array();
            $import_data['slider']                   = !empty($demo_data['slider']) ? $demo_data['slider'] : array();
        }

        if (!isset($import_data['builder'])) {
            $import_data['builder'] = array();
        }

        if (!$demo_data && !empty($import_data['theme_settings']['colors'])) {
            $this->mergeThemeColorsWithDefaultColors($import_data['theme_settings']['colors']);
        }

        foreach ($import_data['area_settings'] as $style_key => $style_settings) {
            $this->modifyStyleSettings($style_settings, $enabled_languages);
            $this->engine->getDbSettingsHelper()->persistKey($style_key, $store_id, 'style', $style_settings);
        }

        if ($demo_data) {
            $this->engine->getDbSettingsHelper()->deleteGroup('builder', $store_id);
        }

        foreach ($import_data['builder'] as $builder_key => $builder_settings) {
            $this->importBuilderData($builder_key, $builder_settings, $store_id, $enabled_languages, $demo_data !== false);
        }

        if (!empty($import_data['theme_presets'])) {
            foreach ($import_data['theme_presets'] as $preset_key => $preset) {
                $this->handlePresetImages($preset);
                $this->modifyStyleSettings($preset['styles']['box'], $enabled_languages);
                $this->modifyStyleSettings($preset['styles']['title'], $enabled_languages);

                $this->engine->getDbSettingsHelper()->persistKey($preset_key, $store_id, 'preset_' . $theme_id, $preset);
            }
        }

        if (!empty($import_data['theme_templates'])) {
            foreach ($import_data['theme_templates'] as $template_key => $template) {
                $this->engine->getDbSettingsHelper()->persistKey($template_key, 0, 'template_' . $theme_id, $template);
            }
        }

        if (!empty($import_data['menu'])) {
            foreach ($import_data['menu'] as $menu_key => $menu) {
                if ($menu_key == 'main' && false === $demo_data && $this->engine->getDbSettingsHelper()->keyExists('main', 0, 'menu')) {
                    continue;
                }

                $this->modifyMenu($menu, $enabled_languages);
                $this->engine->getDbSettingsHelper()->persistKey($menu_key, $store_id, 'menu', $menu);
            }
        }

        if (!empty($import_data['theme_skins'])) {
            foreach ($import_data['theme_skins'] as $skin_key => $skin) {
                $this->engine->getDbSettingsHelper()->persistKey($skin_key, $store_id, 'skin_' . $theme_id, $skin);
            }
        }

        if (!empty($import_data['slider'])) {
            foreach ($import_data['slider'] as $slider_key => &$slider) {
                if (!empty($slider['slides'])) {
                    foreach ($slider['slides'] as &$slide) {
                        if (!empty($slide['cover'])) {
                            $this->changeImagePath($slide['cover']);
                        }
                        if (!empty($slide['layers'])) {
                            foreach ($slide['layers'] as &$layer) {
                                if (!empty($layer['image_src'])) {
                                    $this->changeImagePath($layer['image_src']);
                                }
                            }
                        }
                    }
                }
            }

            foreach ($import_data['slider'] as $slider_key => $slider_config) {
                $this->engine->getDbSettingsHelper()->persistKey($slider_key, 0, 'fire_slider', $slider_config);
            }
        }

        $lang_var = reset($import_data['theme_settings']['font']);
        $import_data['theme_settings']['font'] = array();
        foreach ($enabled_languages as $language) {
            $import_data['theme_settings']['font'][$language['code']] = $lang_var;
        }

        // Twitter
        $lang_var = reset($import_data['theme_settings']['twitter']);
        $import_data['theme_settings']['twitter'] = array();
        foreach ($enabled_languages as $language) {
            if (isset($current_theme_settings['twitter'][$language['code']])) {
                $import_data['theme_settings']['twitter'][$language['code']] = $current_theme_settings['twitter'][$language['code']];
            } else {
                $import_data['theme_settings']['twitter'][$language['code']] = $lang_var;
            }
        }

        // Facebook
        $lang_var = reset($import_data['theme_settings']['facebook']);
        $import_data['theme_settings']['facebook'] = array();
        foreach ($enabled_languages as $language) {
            if (isset($current_theme_settings['facebook'][$language['code']])) {
                $import_data['theme_settings']['facebook'][$language['code']] = $current_theme_settings['facebook'][$language['code']];
            } else {
                $import_data['theme_settings']['facebook'][$language['code']] = $lang_var;
            }
        }

        foreach (array('wrapper', 'bottom') as $area_name) {
            if (!empty($import_data['theme_settings']['style'][$area_name]['background']['rows'])) {
                foreach ($import_data['theme_settings']['style'][$area_name]['background']['rows'] as &$area_row) {
                    if (!empty($area_row['image'])) {
                        $this->changeImagePath($area_row['image']);
                    }
                }
            }
        }

        if (!empty($import_data['theme_settings']['background']['global'])) {
            $this->changeImagePath($import_data['theme_settings']['background']['global']['image']);
        }

        if (!empty($import_data['theme_settings']['background']['page'])) {
            foreach ($import_data['theme_settings']['background']['page'] as &$page_background) {
                $this->changeImagePath($page_background['image']);
            }
        }

        if (!empty($import_data['theme_settings']['background']['category'])) {
            foreach ($import_data['theme_settings']['background']['category'] as &$category_background) {
                $this->changeImagePath($category_background['image']);
            }
        }

        if (!empty($import_data['theme_settings']['payment_images']['rows'])) {
            foreach ($import_data['theme_settings']['payment_images']['rows'] as &$payment_image) {
                $this->changeImagePath($payment_image['file']);
            }
        }

        $this->engine->getThemeExtension()->getPlugin('store')->filterSettings($import_data['theme_settings']['store']);
        $this->engine->getThemeExtension()->getPlugin('product')->filterSettings($import_data['theme_settings']['product']);
        $this->engine->getThemeExtension()->getPlugin('system')->filterSettings($import_data['theme_settings']['system']);
        $this->engine->getThemeExtension()->getPlugin('common')->filterSettings($import_data['theme_settings']['common']);

        $import_data['theme_settings']['install_info'] = $info;
        $import_data['theme_settings']['store_id']     = $store_id;

        $this->engine->getDbSettingsHelper()->persistKey($theme_id, $store_id, 'theme', $import_data['theme_settings']);
        $this->engine->getDbSettingsHelper('setting')->persistKey(TB_Engine::getName() . '_theme', $store_id, TB_Engine::getName(), $info);
        $this->engine->getDbSettingsHelper('setting')->persistKey('config_product_count', $store_id, 'config', 0);

        $theme_config = TB_EnvHelper::getInstance($this->engine->getOcRegistry())->getThemeConfig($theme_id);
        if (!empty($theme_config['oc_image_dimensions'])) {
            $config_images = array();
            $config_group = $this->engine->gteOc22() ? TB_Engine::getName() : 'config';

            foreach ($theme_config['oc_image_dimensions'] as $key => $value) {
                $config_images[$config_group . '_image_' . $key] = $value;
            }

            $this->engine->getDbSettingsHelper('setting')->persistGroup($config_group, $config_images, $this->context->getStoreId());
        }

        if ($this->engine->gteOc22()) {
            $this->engine->getDbSettingsHelper('setting')->persistGroup(TB_Engine::getName(), array(
                TB_Engine::getName() . '_product_limit'              => $import_data['theme_settings']['store']['common']['product_listing_items_per_page'],
                TB_Engine::getName() . '_product_description_length' => $import_data['theme_settings']['store']['common']['product_listing_description_limit']
            ), $this->context->getStoreId());
        }

        $save_log = $this->engine->getSettingsModel('engine_log', 0)->getScopeSettings('theme');
        if (!$save_log) {
            $save_log = array();
        }

        $info['host']      = $this->context->getHost();
        $info['base_http'] = $this->context->getBaseHttpsIf();
        $info['ip']        = TB_Utils::getIp();

        $save_log[] = $info;
        $this->engine->getSettingsModel('engine_log', 0)->setAndPersistScopeSettings('theme', $save_log);

        return true;
    }

    public function importBuilderData($builder_key, $builder_settings, $store_id, array $enabled_languages, $is_demo = false)
    {
        if (!TB_Utils::strEndsWith($builder_key, '__default') && !$is_demo && $this->engine->getDbSettingsHelper()->keyExists($builder_key, $store_id, 'builder')) {
            return;
        }

        $this->modifyBuilderSettings($builder_settings, $enabled_languages);
        $this->engine->getDbSettingsHelper()->persistKey($builder_key, $store_id, 'builder', $builder_settings);
    }

    public function mergeThemeColorsWithDefaultColors(array &$theme_colors)
    {
        $default_colors = array();
        TB_ColorSchemer::getInstance()->filterThemeColors($default_colors, MainColorsData::getColors());

        foreach ($default_colors as $color_group_key => &$color_sections) {
            foreach ($color_sections as $color_section_key => &$color_section_values) {
                if (0 === strpos($color_section_key, '_')) {
                    unset($color_sections[$color_section_key]);
                } else {
                    unset($color_section_values['label']);
                    unset($color_section_values['can_inherit']);
                    unset($color_section_values['id']);
                    unset($color_section_values['parent_id']);
                    unset($color_section_values['parent_color']);
                    unset($color_section_values['inherit_title']);
                    unset($color_section_values['children']);
                    unset($color_section_values['context']);

                    if ($color_section_values['inherit'] != 2) {
                        unset($color_section_values['inherit_key']);
                    }
                }
            }
        }
        // Merge the theme colors with the default colors in case of any default color addition, that is not present in the theme's export (to prevent color inheritance issues)
        $theme_colors = array_replace_recursive($default_colors, $theme_colors);
    }

    public function modifyBuilderSettings(&$builder_settings, $enabled_languages)
    {
        foreach ($builder_settings['rows'] as &$area_row) {
            foreach ($area_row['columns'] as &$area_column) {

                if (!empty($area_column['widgets'])) {
                    foreach ($area_column['widgets'] as &$widget) {
                        $this->modifyWidgetSettings($widget, $enabled_languages);
                    }
                }

                $this->modifyStyleSettings($area_column['settings'], $enabled_languages);
            }

            $this->modifyStyleSettings($area_row['settings'], $enabled_languages);
        }
    }

    public function modifyStyleSettings(&$style_settings, $enabled_languages)
    {
        if (!empty($style_settings['font'])) {
            $locale_map = $this->engine->getLocaleMap();

            foreach (array_keys($enabled_languages) as $language_code) {
                if (array_key_exists($language_code, $locale_map) && isset($style_settings['font'][$locale_map[$language_code]])) {
                    $font_items = $style_settings['font'][$locale_map[$language_code]];
                } else
                if (($code_key = array_search($language_code, $locale_map)) && isset($style_settings['font'][$code_key])) {
                    $font_items = $style_settings['font'][$code_key];
                } else {
                    $font_items = reset($style_settings['font']);
                }

                $style_settings['font'][$language_code] = $font_items;
            }

            $style_settings['font'] = array_intersect_key($style_settings['font'], $enabled_languages);
        }

        if (!empty($style_settings['background']['rows'])) {
            foreach ($style_settings['background']['rows'] as &$style_row) {
                if (!empty($style_row['image'])) {
                    $this->changeImagePath($style_row['image']);
                }
            }
        }
    }

    public function modifyWidgetSettings(&$widget, $enabled_languages)
    {
        if (isset($widget['settings']['lang'])) {
            $lang_var = reset($widget['settings']['lang']);

            if ($lang_var) {
                $widget['settings']['lang'] = array_intersect_key($widget['settings']['lang'], $enabled_languages);
            } else {
                $lang_var = $widget['settings']['lang'] = array();
            }

            foreach ($enabled_languages as $language) {
                if (!isset($widget['settings']['lang'][$language['code']])) {
                    $widget['settings']['lang'][$language['code']] = $lang_var;
                }
            }

            if (0 === strpos($widget['id'], 'Theme_GroupWidget') || 0 === strpos($widget['id'], 'Theme_BlockGroupWidget')) {
                if (!empty($widget['settings']['section_titles'])) {
                    foreach ($widget['settings']['section_titles'] as &$widget_section) {
                        if (!empty($widget_section['lang'])) {
                            $lang_var = reset($widget_section['lang']);
                            $widget_section['lang'] = array_intersect_key($widget_section['lang'], $enabled_languages);

                            foreach ($enabled_languages as $language) {
                                if (!isset($widget_section['lang'][$language['code']])) {
                                    $widget_section['lang'][$language['code']] = $lang_var;
                                }
                            }
                        } else {
                            $widget_section['lang'] = array();
                        }
                    }
                }

                if (!empty($widget['subwidgets'])) {
                    foreach ($widget['subwidgets'] as &$subwidget) {
                        $this->modifyWidgetSettings($subwidget, $enabled_languages);
                    }
                }
            }
        }

        $this->handleWidgetImages($widget);

        foreach (array('box', 'title') as $styles_type) {
            if (isset($widget['settings'][$styles_type . '_styles']['font'])) {
                $lang_var = reset($widget['settings'][$styles_type . '_styles']['font']);
                $widget['settings'][$styles_type . '_styles']['font'] = array();
                foreach ($enabled_languages as $language) {
                    $widget['settings'][$styles_type . '_styles']['font'][$language['code']] = $lang_var;
                }
            }
            if (!empty($widget['settings'][$styles_type . '_styles']['background']['rows'])) {
                foreach ($widget['settings'][$styles_type . '_styles']['background']['rows'] as &$widget_row) {
                    if (!empty($widget_row['image'])) {
                        $this->changeImagePath($widget_row['image']);
                    }
                }
            }
        }
    }

    public function modifyMenu(&$menu, $enabled_languages)
    {
        $lang_var = reset($menu['tree']);
        $menu['tree'] = array_intersect_key($menu['tree'], $enabled_languages);

        foreach ($enabled_languages as $language) {
            if (!isset($menu['tree'][$language['code']])) {
                $menu['tree'][$language['code']] = $lang_var;
            }
        }

        if (!empty($menu['tree_ids'])) {
            $lang_var = reset($menu['tree_ids']);
            $menu['tree_ids'] = array_intersect_key($menu['tree_ids'], $enabled_languages);

            foreach ($enabled_languages as $language) {
                if (!isset($menu['tree_ids'][$language['code']])) {
                    $menu['tree_ids'][$language['code']] = $lang_var;
                }
            }
        }

        $this->handleMenuImages($menu);
    }

    public function handlePresetImages(&$preset)
    {
        if (!empty($preset['styles']['box']['background']['rows'])) {
            foreach ($preset['styles']['box']['background']['rows'] as &$preset_background_row) {
                if ($preset_background_row['background_type'] == 'image' && !empty($preset_background_row['image'])) {
                    $this->changeImagePath($preset_background_row['image']);
                }
            }
        }
    }

    public function handleMenuImages(&$menu)
    {
        foreach ($menu['tree'] as &$language_tree) {
            foreach ($language_tree as &$menu_item) {
                if ($menu_item['data']['type'] == 'category' && !empty($menu_item['data']['settings']['category_custom_bg'])) {
                    $this->changeImagePath($menu_item['data']['settings']['category_custom_bg']);
                }

                if ($menu_item['data']['type'] == 'category' && !empty($menu_item['data']['settings']['menu_banner'])) {
                    $this->changeImagePath($menu_item['data']['settings']['menu_banner']);
                }

                if ($menu_item['data']['type'] == 'html' && !empty($menu_item['data']['settings']['html_text'])) {
                    $image_url = $this->context->getImageUrl() . ($this->engine->gteOc2() ? 'catalog/' : 'data/');
                    $menu_item['data']['settings']['html_text'] = str_replace('src="{{image_url}}', 'src="' . $image_url, $menu_item['data']['settings']['html_text']);
                }

                if (!empty($menu_item['data']['settings']['menu_icon_image'])) {
                    $this->changeImagePath($menu_item['data']['settings']['menu_icon_image']);
                }

                if (!empty($menu_item['children'])) {
                    foreach ($menu_item['children'] as &$menu_item_child) {
                        if ($menu_item_child['data']['type'] == 'category' && !empty($menu_item_child['data']['settings']['category_custom_bg'])) {
                            $this->changeImagePath($menu_item_child['data']['settings']['category_custom_bg']);
                        }

                        if ($menu_item_child['data']['type'] == 'category' && !empty($menu_item_child['data']['settings']['menu_banner'])) {
                            $this->changeImagePath($menu_item_child['data']['settings']['menu_banner']);
                        }

                        if (!empty($menu_item_child['data']['settings']['menu_icon_image'])) {
                            $this->changeImagePath($menu_item_child['data']['settings']['menu_icon_image']);
                        }
                    }
                }
            }
        }
    }

    protected function handleWidgetImages(array &$widget_settings)
    {
        if (0 === strpos($widget_settings['id'], 'Theme_TextWidget') || 0 === strpos($widget_settings['id'], 'Theme_CallToActionWidget')) {
            foreach ($widget_settings['settings']['lang'] as &$widget_lang_settings) {
                $image_url = $this->context->getImageUrl() . ($this->engine->gteOc2() ? 'catalog/' : 'data/');
                $widget_lang_settings['text'] = str_replace('src="{{image_url}}', 'src="' . $image_url, $widget_lang_settings['text']);
            }
        }

        if (0 === strpos($widget_settings['id'], 'Theme_IconListWidget')) {
            foreach ($widget_settings['settings']['lang'] as &$widget_lang_settings) {
                foreach ($widget_lang_settings['rows'] as &$widget_lang_setting_row) {
                    $image_url = $this->context->getImageUrl() . ($this->engine->gteOc2() ? 'catalog/' : 'data/');
                    $widget_lang_setting_row['text'] = str_replace('src="{{image_url}}', 'src="' . $image_url, $widget_lang_setting_row['text']);
                }
            }
        }

        if (0 === strpos($widget_settings['id'], 'Theme_GalleryWidget') && !empty($widget_settings['settings']['images'])) {
            foreach ($widget_settings['settings']['images'] as &$gallery_image) {
                $this->changeImagePath($gallery_image['file']);
            }
        }

        if (0 === strpos($widget_settings['id'], 'Theme_MenuWidget') && !empty($widget_settings['settings']['separator_image'])) {
            $this->changeImagePath($widget_settings['settings']['separator_image']);
        }

        if (0 === strpos($widget_settings['id'], 'Theme_BannerWidget') && !empty($widget_settings['settings']['image'])) {
            $this->changeImagePath($widget_settings['settings']['image']);
        }
    }

    protected function changeImagePath(&$path)
    {
        if (0 === strpos($path, 'data/') && $this->engine->gteOc2()) {
            $path = substr_replace($path, 'catalog/', 0, 5);
        } else
        if (0 === strpos($path, 'catalog/') && !$this->engine->gteOc2()) {
            $path = substr_replace($path, 'data/', 0, 8);
        }
    }

    public function deleteDefaultAreaSettings($store_id)
    {
        $this->dbHelper->deleteRecord('burnengine_setting', '`store_id` = ' . $store_id . ' AND `group` IN ("builder", "style") AND `key` LIKE "%__default"');
    }

    public function deleteCustomAreaStyleSettings($store_id)
    {
        $this->dbHelper->deleteRecord('burnengine_setting', '`store_id` = ' . $store_id . ' AND `group` = "style" AND `key` NOT LIKE "%__default"');
    }

    public function deleteDefaultAreaStyleSettings($store_id)
    {
        $this->dbHelper->deleteRecord('burnengine_setting', '`store_id` = ' . $store_id . ' AND `group` = "style" AND `key` LIKE "%__default"');
    }

    public function checkImportSettings($settings)
    {
        $old_error_handler = set_error_handler(array($this, 'errorHandler'));
        $error = false;

        if (!$import_data = base64_decode($settings)) {
            $error = 'Cannot decode settings';
        }

        if (!$error && !$import_data = gzuncompress($import_data)) {
            $error = 'Cannot decompress settings<br />' . $this->current_error;
        }

        if (!$error && !$import_data = unserialize($import_data)) {
            $error = 'Cannot unserialize settings<br />' . $this->current_error;
        }

        if (!$error && !is_array($import_data)) {
            $error = 'Invalid data format';
        }

        set_error_handler($old_error_handler);

        return array(
            'error' => $error,
            'data'  => $import_data
        );
    }

    public function errorHandler($errno, $errstr)
    {
        $this->current_error = "[$errno] $errstr";
    }
}