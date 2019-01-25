<?php

require_once TB_THEME_ROOT . '/library/vendor/UploadHandler.php';
require_once TB_THEME_ROOT . '/library/vendor/Archive_Tar.php';

class Theme_Admin_ImportController extends TB_AdminController
{
    protected $current_error = '';

    /**
     * @var Theme_Admin_ImportModel
     */
    protected $importModel;

    /** @var  Theme_Admin_LayoutBuilderModel */
    protected $layoutBuilderModel;

    public function init()
    {
        $this->importModel = $this->getModel('import');
        $this->layoutBuilderModel = $this->getModel('layoutBuilder');
        ini_set('open_basedir', null);
    }

    public function uploadFiles()
    {
        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        new UploadHandler(array(
            'accept_file_types' => '/\.(zip)$/i',
            'upload_dir'        => $this->context->getImageDir() . '/cache/tb/',
            'upload_url'        => $this->context->getImageUrl() . 'cache/tb/',
        ));
    }

    public function evaluateFile()
    {
        if (empty($this->request->get['file'])) {
            return $this->sendJsonError('No file provided');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $filename = (string) $this->request->get['file'];
        $filepath = $this->context->getImageDir() . '/cache/tb' . '/' . $filename;

        if (!is_file($filepath)) {
            return $this->sendJsonError('Non-existing file ' . $filepath);
        }

        $zip = new ZipArchive();

        if (false === $zip->open($filepath)) {
            return $this->sendJsonError('Failed to open archive.');
        }

        if (false === ($settings = $zip->getFromName('settings.dat'))) {
            return $this->sendJsonError('Invalid archive contents');
        }

        $check = $this->importModel->checkImportSettings($settings);
        if ($check['error']) {
            return $this->sendJsonError($check['error']);
        }

        return $this->sendJsonSuccess('The settings are valid', array(
            'settings' => $settings,
            'keys'     => array_keys($check['data'])
        ));
    }

    public function checkSettings()
    {
        if (empty($this->request->post['import_settings_input'])) {
            return $this->sendJsonError('No settings provided');
        }

        $check = $this->importModel->checkImportSettings((string) $this->request->post['import_settings_input']);
        if ($check['error']) {
            return $this->sendJsonError($check['error']);
        }

        $keys = array_flip(array_keys($check['data']));
        if ($check['data']['from_theme']['id'] != $this->engine->getThemeId()) {
            unset($keys['colors'], $keys['skins'], $keys['area_settings'], $keys['theme_settings']);
        }
        $keys = array_keys($keys);

        return $this->sendJsonSuccess('The settings are valid', array('keys' => $keys));
    }

    public function import()
    {
        if (empty($this->request->post['import_settings_input'])) {
            return $this->sendJsonError('No settings provided');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $check = $this->importModel->checkImportSettings((string) $this->request->post['import_settings_input']);
        if ($check['error']) {
            return $this->sendJsonError($check['error']);
        }

        $import_data = $check['data'];
        if ($check['data']['from_theme']['id'] != $this->engine->getThemeId()) {
            unset($check['data']['colors'], $check['data']['skins'], $check['data']['area_settings'], $check['data']['theme_settings']);
        }

        $import_features = !empty($this->request->post['import']) ? (array) $this->request->post['import'] : array();

        try {
            $this->importSettings($import_data, $import_features);
        } catch(Exception $e) {
            return $this->sendJsonError($e->getMessage());
        }

        if (!empty($this->request->post['import_file']) && !empty($import_data['images'])) {

            $cache_dir = $this->context->getImageDir() . '/cache/tb';
            $import_file = $cache_dir . '/' . (string) $this->request->post['import_file'];

            if (is_file($import_file)) {
                $zip = new ZipArchive();

                if (false === $zip->open($import_file)) {
                    return $this->sendJsonError('Failed to open images archive.');
                }

                $zip->extractTo($cache_dir, $import_data['images']);
                $zip->close();

                if (!is_dir($cache_dir . '/images')) {
                    return $this->sendJsonError('Cannot find extracted images');
                }

                foreach ($import_data['images'] as $image) {
                    list(, $imported_image) = explode('/', $image, 2) + array( 1 => NULL);
                    $imported_image = $this->context->getImageDir() . '/' . ($this->engine->gteOc2() ? 'catalog/' : 'data/') . $imported_image;

                    if (is_file($cache_dir . '/' . $image)) {
                        TB_Utils::makePath(dirname($imported_image));

                        if (is_dir(dirname($imported_image))) {
                            if (is_file($imported_image)) {
                                unlink($imported_image);
                            }

                            copy($cache_dir . '/' . $image, $imported_image);
                            unlink($cache_dir . '/' . $image);
                        }
                    }
                }

                foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($cache_dir . '/images', 4096), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
                    $path->isDir() ? rmdir($path->getPathname()) : unlink($path->getPathname());
                }
                rmdir($cache_dir . '/images');

                unlink($import_file);
            }
        }

        $this->engine->wipeAllCache();
        $this->session->data['success_alert'] = 'The settings have been successfully imported!';

        return $this->sendJsonSuccess('Successful import', array('reload' => 1));
    }

    protected function importSettings(array $import_data, array $import_features)
    {
        $store_id = $this->context->getStoreId();
        $theme_settings = $this->engine->getThemeModel()->getSettings(true);
        $enabled_languages = $this->engine->getEnabledLanguages();

        if (in_array('settings', $import_features) && !empty($import_data['theme_settings'])) {

            if (!in_array('builder', $import_features) || !isset($import_data['builder'])) {
                unset($import_data['theme_settings']['area_keys']);
            }

            $theme_settings = array_replace_recursive($theme_settings, $import_data['theme_settings']);

            if (!empty($theme_settings['background']['global'])) {
                $this->changeImagePath($theme_settings['background']['global']['image']);
            }

            if (!empty($theme_settings['background']['page'])) {
                foreach ($theme_settings['background']['page'] as &$page_background) {
                    $this->changeImagePath($page_background['image']);
                }
            }

            if (!empty($theme_settings['background']['category'])) {
                foreach ($theme_settings['background']['category'] as &$category_background) {
                    $this->changeImagePath($category_background['image']);
                }
            }

            if (!empty($theme_settings['payment_images']['rows'])) {
                foreach ($theme_settings['payment_images']['rows'] as &$payment_image) {
                    $this->changeImagePath($payment_image['file']);
                }
            }

            if (!empty($import_data['area_settings'])) {
                $this->importModel->deleteCustomAreaStyleSettings($store_id);

                foreach ($import_data['area_settings'] as $style_key => $style_settings) {
                    $this->engine->getDbSettingsHelper()->persistKey($style_key, $store_id, 'style', $style_settings);
                }
            }
        }

        if (in_array('builder', $import_features) && !empty($import_data['builder'])) {
            foreach ($import_data['builder'] as $builder_key => $builder_settings) {
                $this->importModel->modifyBuilderSettings($builder_settings, $enabled_languages);
                $this->engine->getDbSettingsHelper()->persistKey($builder_key, $store_id, 'builder', $builder_settings);
            }

            if (!empty($import_data['templates'])) {
                foreach ($import_data['templates'] as $template_key => $template) {
                    $this->engine->getDbSettingsHelper()->persistKey($template_key, 0, 'template', $template);
                }
            }
        }

        if (in_array('colors', $import_features) && !empty($import_data['colors'])) {
            $theme_settings['colors'] = $import_data['colors'];
        }

        $this->engine->getThemeModel()->setAndPersistSettings($theme_settings);

        if (in_array('presets', $import_features) && !empty($import_data['presets'])) {
            foreach ($import_data['presets'] as $preset_key => $preset) {
                $this->importModel->handlePresetImages($preset);
                $this->importModel->modifyStyleSettings($preset['styles']['box'], $enabled_languages);
                $this->importModel->modifyStyleSettings($preset['styles']['title'], $enabled_languages);


                $this->engine->getDbSettingsHelper()->persistKey($preset_key, 0, 'preset', $preset);
            }
        }

        if (in_array('menu', $import_features) && !empty($import_data['menu'])) {
            foreach ($import_data['menu'] as $menu_key => $menu) {
                $this->importModel->modifyMenu($menu, $enabled_languages);
                $this->engine->getDbSettingsHelper()->persistKey($menu_key, $store_id, 'menu', $menu);
            }
        }

        if (!empty($import_data['skins']) && !empty($import_data['skins_theme_id'])) {
            foreach ($import_data['skins'] as $skin_key => $skin) {
                $this->engine->getDbSettingsHelper()->persistKey($skin_key, 0, 'skin_custom_' . $import_data['skins_theme_id'], $skin);
            }
        }

        if (in_array('slider', $import_features) && !empty($import_data['slider'])) {
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
    }

    protected function modifyBuilderRows(array &$rows, array $enabled_languages)
    {
        foreach ($rows as &$area_row) {
            foreach ($area_row['columns'] as &$area_column) {
                if (!empty($area_column['widgets'])) {
                    foreach ($area_column['widgets'] as &$widget) {
                        $this->importModel->modifyWidgetSettings($widget, $enabled_languages);
                    }
                }

                if (!empty($area_column['settings']['background']['rows'])) {
                    foreach ($area_column['settings']['background']['rows'] as &$area_column_row) {
                        if (!empty($area_column_row['image'])) {
                            $this->changeImagePath($area_column_row['image']);
                        }
                    }
                }
            }

            if (!empty($area_row['settings']['background']['rows'])) {
                foreach ($area_row['settings']['background']['rows'] as &$area_background_row) {
                    if (!empty($area_background_row['image'])) {
                        $this->changeImagePath($area_background_row['image']);
                    }
                }
            }
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

    public function installSampleData()
    {
        if (!$this->validate()) {
            $this->sendJsonError($this->error['warning']);

            return;
        }

        $parent_theme_id = null;
        $themes = $this->engine->getThemes();
        $import_stores = array();
        foreach ((array) $this->request->get['import_stores'] as $store_id => $import_settings) {
            if ($import_settings['theme_id'] != '0' && isset($themes[$import_settings['theme_id']])) {

                if (null !== $themes[$import_settings['theme_id']]['parent']) {
                    $parent_theme_id = $themes[$import_settings['theme_id']]['parent'];
                } else {
                    $parent_theme_id = $import_settings['theme_id'];
                }

                $import_stores[$store_id] = $import_settings;
            }
        }

        if (empty($import_stores)) {
            $this->sendJsonError('No stores selected for import');

            return;
        }

        $demo_sql_dir = $this->context->getThemesDir() . '/' . $parent_theme_id . '/demo';
        $demo_sql_file = $demo_sql_dir . '/store';

        if (!$this->engine->gteOc2()) {
            $demo_sql_file .= '_oc1';
        }

        if (!$demo_sql = $this->getDemoSqlFile($demo_sql_file)) {
            return;
        };

        if (!$stories_sql = $this->getDemoSqlFile($demo_sql_dir . '/stories')) {
            return;
        };

        $install_log = $this->engine->getSettingsModel('engine_log', 0)->getScopeSettings('install', true);

        $language_table_truncated = $this->handleLanguageData();
        $this->importDemoSettings($import_stores);
        $this->importDemoData($import_stores, $demo_sql, $language_table_truncated);
        $this->importStoriesData($import_stores, $stories_sql);

        unset($install_log['can_install_sample_data']);
        $this->engine->getSettingsModel('engine_log', 0)->setAndPersistScopeSettings('install', $install_log);

        /** @var Theme_Admin_ExtensionsModel $extensionsModel */
        $extensionsModel = $this->getModel('extensions');
        $extensionsModel->installExtension('newsletter');

        $this->layoutBuilderModel->rebuildDefaultAreaSettings($this->context->getStoreId());
        $this->engine->wipeAllCache();
    }

    protected function handleLanguageData()
    {
        $all_languages = $this->engine->getEnabledLanguages();
        $english_language_id = false;

        /** @var ModelLocalisationLanguage $ocLanguageModel */
        $ocLanguageModel = $this->engine->getOcModel('localisation/language');
        $latest_language_id = 0;

        foreach ($all_languages as &$language) {
            if ($language['language_id'] == 1 && ($language['code'] == 'en' || $language['code'] == 'en-gb')) {
                return 0;
            }

            if ($language['code'] == 'en' || $language['code'] == 'en-gb') {
                $english_language_id = $language['language_id'];
            }

            if ($language['sort_order'] == 1) {
                $language['sort_order']++;
            }

            $latest_language_id = $language['language_id'];
        }

        $this->engine->getDbHelper()->truncateTable('language');

        if ($english_language_id) {
            $this->engine->getOcConfig()->set('config_language_id', $english_language_id);
        }

        $english_language_data = array(
            'name'       => 'English',
            'code'       => $this->engine->gteOc22() ? 'en-gb' : 'en',
            'locale'     => 'en_US.UTF-8,en_US,en-gb,english',
            'image'      => 'gb.png',
            'directory'  => 'english',
            'sort_order' => 1,
            'status'     => 1
        );

        if (!$this->engine->gteOc2()) {
            $data['filename'] = 'english';
        }

        $this->cleanDescriptionTables();

        // We have to be sure that there are records in '_status' tables
        if (count($all_languages) > 1 || $latest_language_id != 1) {
            // Sometimes there is leftover language data from improper upgrades/imports
            foreach (array('return_status', 'order_status', 'customer_group_description') as $table) {
                $this->engine->getDbHelper()->deleteRecord($table, array('language_id' => 1));
            }
        }

        $ocLanguageModel->addLanguage($english_language_data);
        $this->engine->getOcConfig()->set('config_language_id', 1);

        if ($english_language_id) {
            $ocLanguageModel->deleteLanguage($english_language_id);
        }

        foreach ($all_languages as $language) {
            if ($english_language_id && $language['language_id'] == $english_language_id) {
                continue;
            }

            unset($language['id']);
            if ($this->engine->gteOc2()) {
                unset($language['filename']);
            }

            unset($language['url']);

            $this->engine->getDbHelper()->addRecord('language', $language);
        }

        return 1;
    }

    protected function importDemoData(array $import_stores, $sql, $language_table_truncated)
    {
        $tables_to_truncate = array(
            'category', 'category_filter', 'category_path', 'category_to_layout',
            'customer',
            'filter', 'filter_group',
            'information', 'information_to_layout',
            'layout', 'layout_route',
            'manufacturer',
            'option', 'option_value',
            'attribute', 'attribute_group',
            'product', 'product_discount', 'product_filter', 'product_option', 'product_image',
            'product_option_value', 'product_related', 'product_special', 'product_to_category', 'product_to_layout',
            'review',
            'url_alias'
        );

        $this->engine->getDbHelper()->truncateTable($tables_to_truncate);

        if (!$language_table_truncated) {
            $this->cleanDescriptionTables(true);
        }

        foreach ($sql as &$line) {
            if ($this->engine->gteOc2()) {
                $line = str_replace(', `option_value`', ', `value`', $line);
                $line = str_replace('\'data/', '\'catalog/', $line);
            } else {
                $line = str_replace(', `value`', ', `option_value`', $line);
                $line = str_replace('\'catalog/', '\'data/', $line);
            }
            if (DB_PREFIX) {
                $line = str_replace('INSERT INTO `', 'INSERT INTO `' . DB_PREFIX, $line);
            }
        }

        $this->engine->getDbHelper()->importSQL($sql);

        $image_data = array('canon_eos_5d', 'hp', 'htc_touch_hd', 'imac', 'iphone', 'ipod_classic', 'ipod_nano', 'ipod_shuffle', 'ipod_touch', 'macbook', 'macbook_air', 'macbook_pro', 'nikon_d300', 'palm_treo_pro', 'samsung_tab', 'sony_vaio');
        $product_images_new = array();
        foreach ($this->engine->getOcDb()->query('SELECT * FROM ' . DB_PREFIX . 'product')->rows as $product_row) {
            $image = current($image_data);

            $image = $this->engine->gteOc2() ? 'catalog/demo/' . $image : 'data/demo/' . $image;
            $this->engine->getDbHelper()->updateRecord('product', array('image' => $image . '_1.jpg'), 'product_id = ' . $product_row['product_id']);

            $product_images = array();
            foreach ($this->engine->getOcDb()->query('SELECT * FROM ' . DB_PREFIX . 'product_image WHERE product_id = ' . $product_row['product_id'])->rows as $row) {
                $product_images[$row['product_id']][] = $row['product_image_id'];
            }

            foreach ($product_images as $product_id => $product_image_ids) {
                for ($i = 1; $i <= count($product_image_ids); $i++) {
                    $product_images_new[] = array($product_id, $image . '_' . ($i + 1) . '.jpg');
                }
            }

            if (false === next($image_data)) {
                reset($image_data);
            }
        }

        if (!empty($product_images_new)) {
            $this->engine->getDbHelper()->truncateTable('product_image');
            $this->engine->getDbHelper()->addMultipleRecords('product_image', array('product_id', 'image'), $product_images_new);
        }

        foreach ($this->engine->getOcDb()->query('SELECT * FROM ' . DB_PREFIX . 'category')->rows as $category_row) {
            $image = current($image_data);
            $image = $this->engine->gteOc2() ? 'catalog/demo/' . $image : 'data/demo/' . $image;

            $this->engine->getDbHelper()->updateRecord('category', array('image' => $image . '_1.jpg'), 'category_id = ' . $category_row['category_id']);

            if (false === next($image_data)) {
                reset($image_data);
            }
        }

        $key = 'config_' . ($this->engine->gteOc22() ? 'theme' : 'template');

        foreach (array_keys($import_stores) as $store_id) {
            $this->engine->getDbSettingsHelper('setting')->persistGroup('config', array(
                'config_country_id' => 222,
                'config_zone_id'    => 3563,
                'config_language'   => $this->engine->gteOc22() ? 'en-gb' : 'en',
                'config_currency'   => 'USD',
                $key                => $this->context->getBasename()
            ), $store_id);
        }

        $this->engine->getDbSettingsHelper('setting')->persistKey('config_admin_language', 0, 'config', $this->engine->gteOc22() ? 'en-gb' : 'en');

        $this->engine->getEnabledLanguages(false);

        $tables_to_truncate = array('category_to_store', 'information_to_store', 'manufacturer_to_store', 'product_to_store');
        $this->engine->getDbHelper()->truncateTable($tables_to_truncate);

        $category_ids     = array_column($this->getModel('category')->getAllCategories(), 'category_id');
        $information_ids  = array_column($this->getThemeModel()->getAllInformationPages(), 'information_id');
        $manufacturer_ids = array_column($this->getThemeModel()->getAllManufacturers(), 'manufacturer_id');
        $product_ids      = array_column($this->getThemeModel()->getAllProducts(), 'product_id');

        foreach (array_keys($import_stores) as $store_id) {
            $category_to_store_data = $this->arrayAddColumn('store_id', $store_id, 'category_id', $category_ids);
            $this->engine->getDbHelper()->addMultipleRecords('category_to_store', array('store_id', 'category_id'), $category_to_store_data);

            $information_to_store_data = $this->arrayAddColumn('store_id', $store_id, 'information_id', $information_ids);
            $this->engine->getDbHelper()->addMultipleRecords('information_to_store', array('store_id', 'information_id'), $information_to_store_data);

            $manufacturer_to_store_data = $this->arrayAddColumn('store_id', $store_id, 'manufacturer_id', $manufacturer_ids);
            $this->engine->getDbHelper()->addMultipleRecords('manufacturer_to_store', array('store_id', 'manufacturer_id'), $manufacturer_to_store_data);

            $product_to_store_data = $this->arrayAddColumn('store_id', $store_id, 'product_id', $product_ids);
            $this->engine->getDbHelper()->addMultipleRecords('product_to_store', array('store_id', 'product_id'), $product_to_store_data);
        }
    }

    protected function cleanDescriptionTables($all_languages = false)
    {
        $description_tables = array(
            'category_description', 'filter_description', 'filter_group_description', 'information_description',
            'option_description', 'option_value_description', 'product_description', 'attribute_description',
            'attribute_group_description'
        );

        foreach ($description_tables as $table) {
            if ($all_languages) {
                $this->engine->getDbHelper()->truncateTable($table, true);
            } else {
                $this->engine->getDbHelper()->deleteRecord($table, array('language_id' => 1));
            }
        }
    }

    protected function importStoriesData(array $import_stores, $sql)
    {
        $tables_to_truncate = array('story', 'story_description', 'story_tag', 'story_to_layout', 'story_to_store', 'story_to_tag');
        $this->engine->getDbHelper()->truncateTable($tables_to_truncate, true);

        if (!$this->engine->getDbSettingsHelper('setting')->getKey('stories_settings', 0, 'stories')) {
            if ($this->engine->gteOc2()) {
                $this->engine->getOcModel('extension/extension')->install('module', 'stories');
            } else {
                $this->engine->getOcModel('setting/extension')->install('module', 'stories');
            }

            /** @var ModelStoriesSystem $storiesModule */
            $storiesModule = $this->engine->getOcModel('stories/system');
            $this->engine->getDbHelper()->deleteRecord('url_alias', array(
                'query'   => 'stories/index'
            ));
            $storiesModule->install(false);
        }

        foreach ($sql as &$line) {
            if ($this->engine->gteOc2()) {
                $line = str_replace('\'data/', '\'catalog/', $line);
            } else {
                $line = str_replace('\'catalog/', '\'data/', $line);
            }
            if (DB_PREFIX) {
                $line = str_replace('INSERT INTO `', 'INSERT INTO `' . DB_PREFIX, $line);
            }
        }
        $this->engine->getDbHelper()->importSQL($sql);

        $story_ids = array_column($this->getOcModel('stories/index')->getStories(), 'story_id');
        $stories_settings = unserialize('a:13:{s:4:"lang";a:2:{i:1;a:3:{s:10:"page_title";s:4:"Blog";s:10:"meta_title";s:4:"Blog";s:16:"meta_description";s:0:"";}i:3;a:3:{s:10:"page_title";s:0:"";s:10:"meta_title";s:0:"";s:16:"meta_description";s:0:"";}}s:16:"stories_per_page";i:2;s:7:"keyword";s:4:"blog";s:12:"auto_seo_url";i:1;s:18:"thumbnail_position";s:3:"top";s:10:"text_limit";i:400;s:16:"image_list_width";i:800;s:17:"image_list_height";i:320;s:23:"image_description_width";i:800;s:24:"image_description_height";i:480;s:8:"comments";s:6:"disqus";s:16:"disqus_shortname";s:9:"themeburn";s:12:"social_share";s:0:"";}');

        foreach ($import_stores as $store_id => $import_settings) {
            $story_to_store_data = $this->arrayAddColumn('store_id', $store_id, 'story_id', $story_ids);
            $this->engine->getDbHelper()->addMultipleRecords('story_to_store', array('store_id', 'story_id'), $story_to_store_data);

            $this->engine->getDbSettingsHelper('setting')->persistKey('stories_settings', $store_id, 'stories', $stories_settings);
        }
    }

    protected function getDemoSqlFile($name)
    {
        $file = $name . '.gz';

        if (!file_exists($file)) {
            $this->sendJsonError('Cannot find valid demo file ' . $file);

            return false;
        }

        $tar = new Archive_Tar($file, 'gz');
        $error = false;
        $demo_sql = '';

        try {
            $demo_sql = $tar->extractInString(basename($file));

            if (!$demo_sql) {
                $error = 'Cannot decompress sql file ' . $file;
            }
        } catch (Exception $e) {
            $error = 'Cannot decompress sql file ' . $file . "\n<br />" . $e->getMessage();
        }

        if ($error) {
            $this->sendJsonError($error);
            return false;
        }

        $sql_array = array();
        // Instead of file($data_sql_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) which needs temporary file
        $handle = fopen('php://temp', 'r+');
        fputs($handle, $demo_sql);
        rewind($handle);

        while (!feof($handle)) {
            $line = trim(fgets($handle));

            if (empty($line)) {
                continue;
            }

            $sql_array[] = $line;
        }

        fclose($handle);

        return $sql_array;
    }

    public function importBuilderDemo()
    {
        $enabled_languages = $this->engine->getEnabledLanguages(false);

        foreach ($this->engine->getThemeModel()->getAllStores() as $store) {
            $builder_file = $this->context->getImageDir() . '/cache/demo_builder_' . $store['store_id'] . '.bin';

            if (!is_file($builder_file)) {
                continue;
            }

            $builder_data = json_decode(file_get_contents($builder_file), true);

            $i = 0;
            $j = count($builder_data);
            foreach ($builder_data as $builder_key => $builder_settings) {
                $this->importModel->importBuilderData($builder_key, $builder_settings, $store['store_id'], $enabled_languages, true);
                unset($builder_data[$builder_key]);
                $i++;

                if ($i == 5 && $i < $j) {
                    file_put_contents($builder_file, json_encode($builder_data));

                    return $this->sendJsonSuccess('Next iteration', array('next' => 1));
                }
            }

            unlink($builder_file);
        }

        $_SESSION['tb_save_colors'] = 1;
        $colors_url = $this->extension->getTbUrl()->generateJs('default', '', array(
            'hash' => 'tb_cp_panel_theme_settings,color_settings_tab'
        ));

        return $this->sendJsonSuccess('Demo builder settings have been imported', array(
            'next'       => 0,
            'colors_url' => $colors_url
        ));
    }

    protected function importDemoSettings(array $import_stores)
    {
        foreach ($import_stores as $store_id => $import_settings) {

            if ($import_settings['theme_id'] == '0') {
                continue;
            }

            $demo_content_file = $this->context->getThemesDir() . '/' . $import_settings['theme_id'] . '/demo/theme.bin';
            if (!file_exists($demo_content_file)) {
                $this->sendJsonError('Cannot find the sample data file: ' . $demo_content_file);

                return false ;
            }

            if (!empty($import_settings['variant'][$import_settings['theme_id']]) && $import_settings['variant'][$import_settings['theme_id']] != 'default') {
                $variant_file = $this->context->getThemesDir() . '/' . $import_settings['theme_id'] . '/demo/variants/' . $import_settings['variant'][$import_settings['theme_id']] . '.bin';
                if (file_exists($variant_file)) {
                    $demo_content_file = $variant_file;
                }
            }

            $tar = new Archive_Tar($demo_content_file);

            $demo_data = $tar->extractInString('theme');

            if (null === $demo_data) {
                $this->sendJsonError('Cannot decompress demo data file ' . $demo_content_file);

                return false;
            }

            $check = $this->importModel->checkImportSettings($demo_data);
            if ($check['error']) {
                $this->sendJsonError($check['error']);

                return false;
            }

            $import_data = $check['data'];

            // Save memory
            $builder_file = $this->context->getImageDir() . '/cache/demo_builder_' . $store_id . '.bin';
            if (false === file_put_contents($builder_file, json_encode($import_data['builder']))) {
                $this->sendJsonError('Cannot save demo builder data in ' . $builder_file);

                return false;
            }
            unset($import_data['builder']);

            if ($this->engine->gteOc2()) {
                $import_data = json_decode(str_replace('data\/sample_data\/', 'catalog\/sample_data\/', json_encode($import_data)), true);
            } else {
                $import_data = json_decode(str_replace('catalog\/sample_data\/', 'data\/sample_data\/', json_encode($import_data)), true);
            }

            $result = $this->importModel->saveTheme($import_settings['theme_id'], $store_id, $import_data);

            $logo_path = ($this->engine->gteOc2() ? 'catalog/' : 'data/') . 'sample_data/' . $import_settings['theme_id'];
            $this->engine->getDbSettingsHelper('setting')->persistGroup('config', array('config_logo' => $logo_path . '_logo.png'), $store_id);

            if (true !== $result) {
                $this->sendJsonError(var_export($result, true));

                return false;
            }

        }

        return $this->sendJsonSuccess('Demo settings have been imported');
    }

    protected function arrayAddColumn($column_key, $column_value, $array_key, array $array)
    {
        $result = array();

        foreach ($array as $value) {
            $result[] = array(
                $column_key => $column_value,
                $array_key  => $value
            );
        }

        return $result;
    }
}