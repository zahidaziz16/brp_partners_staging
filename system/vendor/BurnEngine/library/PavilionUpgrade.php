<?php

class TB_PavilionUpgrade
{
    /**
     * @var TB_Engine
     */
    protected $engine;

    /**
     * @var TB_AdminExtension
     */
    protected $adminExtension;

    /**
     * @var TB_Context
     */
    protected $context;

    /**
     * @var TB_ViewDataBag
     */
    protected $themeData;

    protected $errors = array();

    protected $upgrade_step = 7;

    public function __construct(TB_Engine $engine, TB_AdminExtension $extension)
    {
        $this->engine        = $engine;
        $this->adminExtension = $extension;
        $this->context        = $engine->getContext();
        $this->themeData      = $engine->getThemeData();
    }

    public function upgrade($theme_id, $upgrade_info)
    {
        if (!is_array($upgrade_info) && $upgrade_info == '1') {
            $this->backup();
        }

        $stores = array();
        foreach ($this->engine->getThemeModel()->getAllStores() as $store) {
            if ($theme_settings = $this->engine->getDbSettingsHelper('setting')->getKey('theme_pavilion', $store['store_id'], 'pavilion')) {
                $store['theme_settings'] = $theme_settings;
                $stores[$store['store_id']] = $store;
            }
        }


        $themeModel = $this->engine->getSettingsModel('theme', $upgrade_info['store_id']);
        $current_settings = $themeModel->getScopeSettings($theme_id, true);
        $this->engine->getEnabledLanguages(false);

        if (!is_array($upgrade_info) && $upgrade_info == '1') {
            $store = current($stores);

            $upgrade_info = array(
                'store_id'     => $store['store_id'],
                'num_stores'   => count($stores),
                'all_rows'   => $this->getCountKeysBeginWith('area_', 'theme_pavilion', 'area_settings_'),
                'progress'   => 0
            );

            foreach ($stores as $temp_store) {
                $upgrade_info['stores'][$temp_store['store_id']] = array(
                    'offset' => 0,
                    'rows'   => 0
                );
            }

            if ($theme_id != $this->engine->getThemeId()) {
                /** @var Theme_Admin_ImportModel $importModel */
                $importModel = $this->engine->getThemeExtension()->getModel('import');
                if (true !== $importModel->saveTheme($theme_id, $store['store_id'])) {
                    return false;
                }
                $current_settings = $themeModel->getScopeSettings($theme_id, true);
            }


            $this->upgradeSettingsAndStyles($store['store_id'], $store['theme_settings'], $current_settings);
        } else {
            $store = $stores[$upgrade_info['store_id']];
        }

        $this->upgradeContent($upgrade_info);

        if ($upgrade_info['stores'][$store['store_id']]['offset'] == $upgrade_info['stores'][$store['store_id']]['rows']) {
            $key = 'config_' . ($this->engine->gteOc22() ? 'theme' : 'template');
            $this->engine->getDbSettingsHelper('setting')->persistGroup('config', array($key => $this->context->getBasename()), $store['store_id']);
            $store = next($stores);
        }

        if ($store === false) {
            $upgrade_info['store_id'] = $store['store_id'];
            $upgrade_info['progress'] = 100;
            $this->clean();
        } else {
            $progress = 0;
            foreach ($stores as $store) {
                $progress += $upgrade_info['stores'][$store['store_id']]['offset'];
            }

            $progress = $progress == $upgrade_info['all_rows'] ? 100 : round(($progress / $upgrade_info['all_rows']) * 100);
            $upgrade_info['progress'] = $progress;
        }

        $current_settings['upgrade_pavilion'] = $upgrade_info;

        $themeModel->setAndPersistScopeSettings($theme_id, $current_settings);

        return $upgrade_info['progress'];
    }

    protected function upgradeContent(&$upgrade_info)
    {
        $store_id = $upgrade_info['store_id'];
        /** @var Theme_Admin_LayoutBuilderModel $layoutBuilderModel */
        $layoutBuilderModel = $this->engine->getThemeExtension()->getModel('layoutBuilder');

        $result = $this->getKeysBeginWith('area_', $store_id, 'theme_pavilion', 'area_settings_', array($this->upgrade_step, $upgrade_info['stores'][$store_id]['offset']));

        $upgrade_info['stores'][$store_id]['rows'] = $result['found_rows'];
        $upgrade_info['stores'][$store_id]['offset'] += count($result['records']);

        if ($result['found_rows'] <= $upgrade_info['stores'][$store_id]['offset']) {
            $upgrade_info['stores'][$store_id]['offset'] = $result['found_rows'];
        }

        /**
         * CONTENT AREAS
         */
        foreach ($result['records'] as $builder_key => &$builder_settings) {

            foreach (array('footer', 'intro', 'content', 'column_left', 'column_right') as $area_name) {
                if (0 === strpos($builder_key, 'area_' . $area_name) && 0 !== strpos($builder_key, 'area_' . $area_name . '_default')) {

                    $import_area = true;

                    $area_style_settings = array();
                    if ($area_segments = $this->determineAreaSegments($area_name, $builder_key, 'area')) {
                        list($area_type, $area_id) = $area_segments;
                        $area_style_settings = $this->getAreaStyleSettings($area_name, $area_type, $area_id);

                        if (!empty($area_style_settings)) {
                            switch($area_style_settings['layout']['type']) {
                                case 'no_max_width':
                                    $area_style_settings['layout']['type'] = 'full';
                                    break;
                                case 'content_separate':
                                    $area_style_settings['layout']['type'] = 'fixed';
                                    $area_style_settings['layout']['separate_columns'] = 1;
                                    break;
                            }

                            $area_style_settings['layout']['padding_left'] = $area_style_settings['layout']['padding_right'];
                            $area_style_settings['layout']['margin_left'] = $area_style_settings['layout']['margin_right'];
                        }
                    }

                    $layoutBuilderModel->filterAreaSettings($area_style_settings, 'area_' . $area_name);

                    if (!empty($builder_settings['rows'])) {
                        foreach ($builder_settings['rows'] as &$row) {
                            if (!$import_area) {
                                continue;
                            }

                            switch($row['settings']['layout']['type']) {
                                case 'full':
                                    $row['settings']['layout']['type'] = 'full_fixed';
                                    break;
                                case 'no_max_width':
                                    $row['settings']['layout']['type'] = 'full';
                                    break;
                                case 'no_max_width tb_row_separate':
                                    $row['settings']['layout']['type'] = 'full';
                                    $row['settings']['layout']['separate_columns'] = 1;
                                    break;
                                case 'separate':
                                    $row['settings']['layout']['type'] = 'fixed';
                                    $row['settings']['layout']['separate_columns'] = 1;
                                    break;
                            }

                            if (!isset($row['settings']['layout']['margin_right'])) {
                                $row['settings']['layout']['margin_right'] = 0;
                            }

                            $row['settings']['layout']['padding_left'] = $row['settings']['layout']['padding_right'];
                            $row['settings']['layout']['margin_left'] = $row['settings']['layout']['margin_right'];

                            $layoutBuilderModel->filterRowSettings($row['settings']);
                            $layoutBuilderModel->cleanSettingsDataBeforePersist($row['settings']);

                            if (!empty($row['columns'])) {
                                foreach ($row['columns'] as &$row_column) {
                                    if (!$import_area) {
                                        continue;
                                    }
                                    if (empty($row_column['settings'])) {
                                        $row_column['settings'] = array();
                                    }
                                    if (!isset($row_column['id'])) {
                                        $row_column['id'] = TB_Utils::genRandomString();
                                    }
                                    $layoutBuilderModel->filterColumnSettings($row_column['settings'], $row['settings']);
                                    $layoutBuilderModel->cleanSettingsDataBeforePersist($row_column['settings']);
                                    $column_settings[$row_column['id']] = $row_column['settings'];

                                    if (!empty($row_column['widgets'])) {
                                        foreach ($row_column['widgets'] as &$widget_configuration) {

                                            if (0 === strpos($widget_configuration['id'], 'Theme_PageContentProductSystemWidget')) {
                                                // All products scope
                                                $import_area = false;
                                                break;
                                            }

                                            $widget = $this->processWidgetConfiguration($widget_configuration, $store_id);

                                            if ($widget instanceof Theme_GroupWidget && isset($widget_configuration['subwidgets'])) {
                                                $widget_configuration['subwidget_map'] = array();
                                                $widget_configuration['section_keys']  = array();

                                                foreach ($widget_configuration['subwidgets'] as &$subwidget_configuration) {

                                                    $subWidget = $this->processWidgetConfiguration($subwidget_configuration, $store_id);
                                                    $subwidget_configuration['settings'] = $subWidget->getSettings();

                                                    $tab_key = TB_Utils::genRandomString();
                                                    $widget_configuration['subwidget_map'][$tab_key] = array($subwidget_configuration['id']);
                                                    $widget_configuration['section_keys'][] = $tab_key;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($import_area) {
                        $new_builder_key = TB_Utils::strGetAfter($builder_key, 'area_');
                        $this->engine->getSettingsModel('builder', $store_id)->setAndPersistScopeSettings($new_builder_key, $builder_settings);
                    }
                }
            }
        }
    }

    protected function upgradeSettingsAndStyles($store_id, $theme_settings, &$current_settings)
    {
        /** @var Theme_Admin_LayoutBuilderModel $layoutBuilderModel */
        $layoutBuilderModel = $this->engine->getThemeExtension()->getModel('layoutBuilder');

        $dbHelper = $this->engine->getDbSettingsHelper('tb_setting');
        $dbHelper->setGroupFieldName('group');

        /**
         * SLIDERS
         */
        foreach ($dbHelper->getKeysBeginWith('slider_', $store_id, 'slider_pavilion') as $slider) {
            $slider['id'] = TB_Utils::slugify($slider['name']) . '-' . (string) $slider['id'];
            $this->engine->getSettingsModel('fire_slider', 0)->setAndPersistScopeSettings($slider['id'], $slider);
        }

        /**
         * TWITTER
         */
        foreach ($theme_settings['facebook'] as $language_id => $facebook) {
            $language = $this->engine->getLanguageById($language_id);
            if (false === $language) {
                continue;
            }

            $current_settings['facebook'][$language['code']] = $facebook;
        }

        /**
         * FACEBOOK
         */
        foreach ($theme_settings['twitter'] as $language_id => $twitter) {
            $language = $this->engine->getLanguageById($language_id);
            if (false === $language) {
                continue;
            }

            $current_settings['twitter'][$language['code']] = $twitter;
        }

        /**
         * PRODUCT
         */
        if (isset($theme_settings['product']['designs']['option'])) {
            foreach ($theme_settings['product']['designs']['option'] as $option_id => $option) {
                $current_settings['product']['designs']['option'][$option_id] = $option;
            }
        }

        /**
         * COMMON
         */
        foreach ($theme_settings['common'] as $option_id => $option) {
            if (isset($current_settings['common'][$option_id])) {
                $current_settings['common'][$option_id] = $option;
            }
        }

        /**
         * STORE
         */
        $current_settings['store']['common'] = array_replace($current_settings['store']['common'], $theme_settings['store']['common']);

        foreach (array('list', 'grid', 'compact') as $product_list_type) {
            switch ($theme_settings['store']['category']['products'][$product_list_type]['products_style']) {
                case 1:
                    $theme_settings['store']['category']['products'][$product_list_type]['products_style'] = 'plain';
                    break;
                case 2:
                    $theme_settings['store']['category']['products'][$product_list_type]['products_style'] = 'bordered';
                    break;
                case 3:
                    $theme_settings['store']['category']['products'][$product_list_type]['products_style'] = 'separate';
                    break;
            }

            foreach (array('cart_button_size', 'compare_button_size', 'wishlist_button_size') as $button_size) {
                $size = $theme_settings['store']['category']['products'][$product_list_type][$button_size];
                $map = array(20 => 'xs', '24' => 'sm', 30 => 'md', 34 => 'lg', 40 => 'xl');
                if (isset($map[$size])) {
                    $theme_settings['store']['category']['products'][$product_list_type][$button_size] = $map[$size];
                }
            }
        }

        $current_settings['store']['category'] = array_replace_recursive($current_settings['store']['category'], $theme_settings['store']['category']);

        /**
         * MENU
         */
        if ($store_id == 0 && isset($theme_settings['store']['menu']['tree'])) {
            $main_menu = $this->engine->getSettingsModel('menu', $store_id)->getScopeSettings('main', true);

            foreach ($theme_settings['store']['menu']['tree'] as $language_id => $menu_tree) {
                $language = $this->engine->getLanguageById($language_id);
                if (false === $language) {
                    continue;
                }

                $main_menu['tree'][$language['code']] = $menu_tree;
                if (isset($main_menu['tree_ids'][$language['code']]) && isset($theme_settings['store']['menu']['tree_ids'][$language_id])) {
                    $main_menu['tree_ids'][$language['code']] = $theme_settings['store']['menu']['tree_ids'][$language_id];
                }
            }

            $this->engine->getSettingsModel('menu', $store_id)->setAndPersistScopeSettings('main', $main_menu);
        }

        /**
         * PAYMENT IMAGES
         */
        if (!empty($theme_settings['payment_images'])) {
            $current_settings['payment_images'] = $theme_settings['payment_images'];
        }

        /**
         * COLORS
         */
        foreach ($theme_settings['colors'] as $color_section => $color_values) {
            if (!isset($current_settings['colors'][$color_section])) {
                continue;
            }

            foreach ($color_values as $color_name => $color_settings) {
                if (isset($current_settings['colors'][$color_section][$color_name])) {
                    $current_settings['colors'][$color_section][$color_name]['color'] = $color_settings['color'];
                }
            }
        }

        /**
         * FONT
         */
        foreach ($theme_settings['font'] as $language_id => $fonts) {
            $language = $this->engine->getLanguageById($language_id);
            if (false === $language || !isset($current_settings['font'][$language['code']])) {
                continue;
            }

            foreach ($fonts as $font_section => $font) {
                if (!isset($current_settings['font'][$language['code']][$font_section])) {
                    continue;
                }

                unset($font['elements'], $font['section_name']);
                $current_settings['font'][$language['code']][$font_section] = array_replace($current_settings['font'][$language['code']][$font_section], $font);
            }
        }

        /**
         * STYLE
         */
        $current_settings['style']['maximum_width'] = $theme_settings['style']['maximum_width'];
        $current_settings['style']['msg_position']  = $theme_settings['style']['msg_position'];
        $current_settings['style']['msg_stack']     = $theme_settings['style']['msg_stack'];
        $current_settings['style']['msg_timeout']   = $theme_settings['style']['msg_timeout'];

        foreach (array('wrapper', 'bottom') as $style_area) {
            if (!isset($theme_settings['style'][$style_area])) {
                continue;
            }

            if (!empty($theme_settings['style']['background']['rows'])) {
                unset($current_settings['style']['background']['rows']);
            }

            foreach ($theme_settings['style'][$style_area] as $section_name => $section) {
                if (!isset($current_settings['style'][$style_area][$section_name])) {
                    $current_settings['style'][$style_area][$section_name] = array();
                }
                $current_settings['style'][$style_area][$section_name] = array_replace_recursive($current_settings['style'][$style_area][$section_name], $section);
            }

            switch($current_settings['style'][$style_area]['layout']['type']) {
                case 'no_max_width':
                    $current_settings['style'][$style_area]['layout']['type'] = 'full';
                    break;
                case 'content_separate':
                    $current_settings['style'][$style_area]['layout']['type']= 'fixed';
                    $current_settings['style'][$style_area]['layout']['separate_columns'] = 1;
                    break;
            }

            $current_settings['style'][$style_area]['layout']['padding_left'] = $current_settings['style'][$style_area]['layout']['padding_right'];
            $current_settings['style'][$style_area]['layout']['margin_left'] = $current_settings['style'][$style_area]['layout']['margin_right'];
        }

        /*
        if (isset($theme_settings['style']['header'])) {
            $header_settings = $this->engine->getStyleSettingsModel()->getScopeSettings('header_global', true);
            foreach ($theme_settings['style']['header'] as $section_name => $section) {
                if (isset($header_settings[$section_name])) {
                    $header_settings[$section_name] = array_replace_recursive($header_settings[$section_name], $section);
                } else {
                    $header_settings[$section_name] = $section;
                }
            }

            $layoutBuilderModel->filterAreaSettings($header_settings, 'area_header');
            $layoutBuilderModel->cleanSettingsDataBeforePersist($header_settings);

            $this->engine->getSettingsModel('style', $store_id)->setAndPersistScopeSettings('header_global', $header_settings);
        }
        */

        /**
         * STYLE AREAS
         */
        //$new_area_settings = $this->engine->getDbSettingsHelper()->getGroup('style', $store_id);
        //unset($new_area_settings['content_home']);

        foreach ($dbHelper->getKeysBeginWith('area_settings_', $store_id, 'theme_pavilion') as $setting_name => $setting) {
            foreach (array('footer', 'intro', 'content') as $area_name) {
                if (0 === strpos($setting_name, 'area_settings_' . $area_name) && 0 !== strpos($setting_name, 'area_settings_' . $area_name . '_default')) {

                    $new_setting_name = TB_Utils::strGetAfter($setting_name, 'area_settings_');

                    /*
                    if (isset($new_area_settings[$new_setting_name])) {
                        $setting = array_replace_recursive($new_area_settings[$new_setting_name], $setting);
                    }
                    */

                    switch($setting['layout']['type']) {
                        case 'no_max_width':
                            $setting['layout']['type'] = 'full';
                            break;
                        case 'content_separate':
                            $setting['layout']['type'] = 'fixed';
                            $setting['layout']['separate_columns'] = 1;
                            break;
                    }

                    $setting['layout']['padding_left'] = $setting['layout']['padding_right'];
                    $setting['layout']['margin_left'] = $setting['layout']['margin_right'];

                    $layoutBuilderModel->filterAreaSettings($setting, 'area_' . $area_name);
                    $layoutBuilderModel->cleanSettingsDataBeforePersist($setting);

                    $this->engine->getSettingsModel('style', $store_id)->setAndPersistScopeSettings($new_setting_name, $setting);
                }
            }
        }
    }

    protected function processWidgetConfiguration(array &$widget_configuration, $store_id)
    {
        if (0 === strpos($widget_configuration['id'], 'Theme_MenuWidget')) {
            $this->processMenuWidgetSettings($widget_configuration, $store_id);
        }

        $settings = $widget_configuration['settings'];

        if (0 === strpos($widget_configuration['id'], 'Theme_CallToActionWidget')) {
            foreach ($settings['lang'] as $language_id => $language_data) {
                switch($language_data['button_size']) {
                    case 30:
                        $language_data['button_size'] = 'md';
                        break;
                    case 40:
                        $language_data['button_size'] = 'lg';
                        break;
                    case 50:
                        $language_data['button_size'] = 'xl';
                        break;
                    case 60:
                        $language_data['button_size'] = 'xxl';
                        break;
                }
                $settings['lang'][$language_id] = $language_data;
            }
        }

        if (0 === strpos($widget_configuration['id'], 'Theme_FireSliderWidget')) {

            $dbHelper = $this->engine->getDbSettingsHelper('tb_setting');
            $dbHelper->setGroupFieldName('group');
            $slider = $dbHelper->getKey('slider_' . $settings['slider_id'], 0, 'slider_pavilion');

            foreach ($settings['lang'] as $language_id => $language_data) {
                $settings['lang'][$language_id]['slider_id'] = TB_Utils::slugify($slider['name']) . '-' . (string) $slider['id'];
                $settings['lang'][$language_id]['navigation_size'] = $settings['navigation_size'];
            }
        }

        if (0 === strpos($widget_configuration['id'], 'Theme_GalleryWidget')) {
            $settings['nav_style']        = $settings['gallery_nav'];
            $settings['nav_buttons_size'] = $settings['navigation_size'];
            $settings['thumb_width']      = $settings['thumb_size_x'];
            $settings['thumb_height']     = $settings['thumb_size_y'];
            $settings['slide_width']      = $settings['preview_size_x'];
            $settings['slide_height']     = $settings['preview_size_y'];
            $settings['full_width']       = $settings['full_size_x'];
            $settings['full_height']      = $settings['full_size_y'];
        }

        if (0 === strpos($widget_configuration['id'], 'Theme_GroupWidget')) {
            $settings['nav_padding'] = $settings['inner_padding'];
        }

        if (isset($settings['lang'])) {
            $settings_lang = array();

            foreach ($settings['lang'] as $language_id => $language_data) {
                $language = $this->engine->getLanguageById($language_id);
                if (false === $language) {
                    continue;
                }

                $settings_lang[$language['code']] = $language_data;
            }

            $settings['lang'] = $settings_lang;
        }

        if (!empty($settings['common'])) {
            $settings['box_styles'] = $settings['common'];
            unset($settings['common']);
        }

        $settings['box_styles']['layout']['padding_left'] = $settings['box_styles']['layout']['padding_right'];
        $settings['box_styles']['layout']['margin_left'] = $settings['box_styles']['layout']['margin_right'];

        if (!empty($settings['box_styles']['font'])) {
            $font_lang = array();
            foreach ($settings['box_styles']['font'] as $language_id => $language_data) {
                $language = $this->engine->getLanguageById($language_id);
                if (false === $language) {
                    continue;
                }

                $font_lang[$language['code']] = $language_data;
            }
            $settings['box_styles']['font'] = $font_lang;
        }

        if (0 === strpos($widget_configuration['id'], 'Theme_PageContentInformationSystemWidget')) {
            $widget_configuration['id'] = str_replace('Theme_PageContentInformationSystemWidget', 'Theme_SystemWidget', $widget_configuration['id']);
        }

        $widget = $this->engine->getWidgetManager()->createAndFilterWidgetFromId($widget_configuration['id'], $settings, 'admin');

        $settings = $widget->getSettings();

        $widget->onPersistSystem($settings);

        if (is_callable(array($widget, 'onPersist'))) {
            $widget->onPersist($settings);
        }

        $widget_configuration['settings'] = $settings;

        return $widget;
    }

    protected function processMenuWidgetSettings(array &$widget_configuration, $store_id)
    {
        if (empty($widget_configuration['settings']['lang'])) {
            return;
        }

        $widget = $this->engine->getWidgetManager()->createWidgetFromId($widget_configuration['id'], $widget_configuration['settings']);
        $menu_name = $widget->getLangTitle() . ' ' . TB_Utils::genRandomString();

        $menu_upgrade = array();
        if (is_file($this->context->getCacheDir() . '/menu_upgrade.dat')) {
            $menu_upgrade = unserialize(file_get_contents($this->context->getCacheDir() . '/menu_upgrade.dat'));
        }

        $menu_hash = md5(serialize($widget_configuration['settings']['lang']));
        if (!isset($menu_upgrade[$menu_hash])) {
            $menu_upgrade[$menu_hash] = TB_Utils::slugify($menu_name);
        }

        $menu_id = $menu_upgrade[$menu_hash];
        $menu = array(
            'id'       => $menu_id,
            'name'     => $menu_name,
            'tree'     => array(),
            'tree_ids' => array()
        );

        file_put_contents($this->context->getCacheDir() . '/menu_upgrade.dat', serialize($menu_upgrade));

        foreach ($widget_configuration['settings']['lang'] as $language_id => &$language_data) {
            $language = $this->engine->getLanguageById($language_id);
            if (false === $language) {
                continue;
            }

            $menu['tree'][$language['code']] = $language_data['menu'];

            unset($language_data['menu']);

            if (isset($language_data['page_ids'])) {
                $menu['tree_ids'][$language['code']]['page_ids'] = $language_data['page_ids'];
                unset($language_data['page_ids']);
            }

            if (isset($language_data['category_ids'])) {
                $menu['tree_ids'][$language['code']]['category_ids'] = $language_data['category_ids'];
                unset($language_data['category_ids']);
            }
        }

        if (empty($menu['tree_ids'])) {
            unset($menu['tree_ids']);
        }

        $widget_configuration['settings']['menu_id'] = $menu_id;

        $this->engine->getSettingsModel('menu', $store_id)->setAndPersistScopeSettings($menu_id, $menu);
    }

    protected function determineAreaSegments($area_name, $area_setting_name, $type)
    {
        $area_id = $area_type = '';
        $type .= '_';

        if (0 === strpos($area_setting_name, $type . $area_name . '_system_')) {
            $area_id   = substr($area_setting_name, strlen($type . $area_name . '_system_'));
            $area_type = 'system';
        } else
        if ($area_setting_name == $type . $area_name . '_category_global') {
            $area_id   = 'category_global';
            $area_type = 'category';
        } else
        if ($area_setting_name == $type . $area_name . '_category_level_1') {
            $area_id   = 'level_1';
            $area_type = 'category';
        } else
        if ($area_setting_name == $type . $area_name . '_home') {
            $area_id   = 'home';
            $area_type = 'home';
        } else
        if (0 === strpos($area_setting_name, $type . $area_name . '_global')) {
            $area_id   = 'global';
            $area_type = 'global';
        }

        if (empty($area_id) || empty($area_type)) {
            return false;
        }

        return array($area_type, $area_id);
    }

    protected function getAreaStyleSettings($area_name, $area_type, $area_id)
    {
        $area_key = strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id;
        $area_settings = $this->engine->getWidgetManager()->getAreaSettings($area_name . '_' . $area_key, 'style');

        if (empty($area_settings)) {
            try {
                /** @var Theme_Admin_LayoutBuilderModel $layoutBuilderModel */
                $layoutBuilderModel = $this->engine->getThemeExtension()->getModel('layoutBuilder');
                $params = $layoutBuilderModel->determineAreaParams($area_name, $area_type, $area_id, 'style');
            } catch (Exception $e) {
                $params = null;
            }

            if (null !== $params) {
                list(, $area_key) = $params;
                $area_settings = $this->engine->getWidgetManager()->getAreaSettings($area_name . '_' . $area_key, 'style');
            }
        }

        if (empty($area_settings)) {
            $area_settings = array();
        }

        return $area_settings;
    }

    protected function clean()
    {
        if (file_exists($this->context->getCacheDir() . '/menu_upgrade.dat')) {
            unlink($this->context->getCacheDir() . '/menu_upgrade.dat');
        }

        if (!$this->engine->gteOc2()) {
            $index_file = $this->context->getRootDir() . '/index.php';
            if (false === strpos(file_get_contents($index_file), 'common/tb') && is_writable($index_file)) {
                file_put_contents($index_file, str_replace('$controller->dispatch(new Action(\'common/tb\', array(\'front\' => $controller)), new Action(\'error/not_found\'));', '', file_get_contents($index_file)));
            }
        }
    }

    protected function backup()
    {

    }

    protected function getKeysBeginWith($begin_with, $store_id, $group, $not_begin_with, array $limit)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS `key`, `value`, serialized
                FROM ' . $this->engine->getDbHelper()->getDbPrefix() . 'tb_setting
                WHERE `store_id` = ' . $store_id . ' AND
                      `group` = ' . $this->engine->getDbHelper()->quote($group) . ' AND
                      `key` LIKE "' . $begin_with . '%" AND `key` NOT LIKE "' . $not_begin_with . '%"
                LIMIT ' . $limit[0] . ' OFFSET ' . $limit[1];

        $result = array();
        foreach ($this->engine->getDbHelper()->getDb()->query($sql)->rows as $row) {
            $result[$row['key']] = unserialize($row['value']);
        }

        return array('records' => $result, 'found_rows' => $this->engine->getDbHelper()->getFoundRows());
    }

    protected function getCountKeysBeginWith($begin_with, $group, $not_begin_with)
    {
        $sql = 'SELECT COUNT(*) as cnt
                FROM ' . $this->engine->getDbHelper()->getDbPrefix() . 'tb_setting
                WHERE `group` = ' . $this->engine->getDbHelper()->quote($group) . ' AND
                      `key` LIKE "' . $begin_with . '%" AND `key` NOT LIKE "' . $not_begin_with . '%"';

        return (int) $this->engine->getDbHelper()->getDb()->query($sql)->row['cnt'];
    }
}