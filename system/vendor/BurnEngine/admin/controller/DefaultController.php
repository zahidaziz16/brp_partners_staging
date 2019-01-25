<?php

class Theme_Admin_DefaultController extends TB_AdminController
{
    public function index()
    {
        /*
        $footer_global = $this->engine->getSettingsModel('theme', 5)->getScopeSettings('area_footer_global');
        unset($footer_global['rows']['0']);
        fb(base64_encode(serialize($footer_global)));
        */

        $this->document->setTitle($this->translate('heading_title'));

        $this->children = array(
            'common/header',
            'common/footer'
        );

        if ($this->engine->gteOc2()) {
            $this->children[] = 'common/column_left';
        } else {
            $this->data['column_left'] = '';
        }

        // This is needed because AdminDispatcher does setScopeSettings('theme', array()) on erroneous engine install,
        // which prevents the install errors key from retrieving.
        $theme_settings = $this->getThemeModel()->getSettings(true);
        if (!empty($theme_settings['install_errors'])) {
            $this->renderInstallErrors($theme_settings['install_errors']);

            return;
        }

        if ($this->checkPostInstallErrors()) {
            return;
        }

        if ($this->engine->getThemeInfo('id') == TB_Engine::getName()) {
            $this->renderInstallErrors('The engine cannot find at least one valid theme.');

            return;
        }

        if (!empty($_SESSION['tb_rebuild_default_settings'])) {
            // Performed immediately after module install. Can't be executed during the install because the theme id
            // is set to 'BurnEngine' when initializing Engine. Therefore an invalid record theme_BurnEngine is
            // created in burnengine_setting if rebuildDefaultAreaSettings() is called at install time from Installer.php
            /** @var Theme_Admin_LayoutBuilderModel $layoutBuilderModel */
            $layoutBuilderModel = $this->getModel('layoutBuilder');

            foreach ((array) $_SESSION['tb_rebuild_default_settings'] as $store_id) {
                $layoutBuilderModel->rebuildDefaultAreaSettings($store_id);
            }

            unset($_SESSION['tb_rebuild_default_settings']);
        }

        if (true === $this->checkPavilionUpgrade($theme_settings)) {
            return;
        }

        if (!$this->checkStoreInstalled($theme_settings)) {
            return;
        }

        if (true === $this->checkEngineUpgrade($theme_settings)) {
            return;
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $this->saveSettings($this->request->post, $theme_settings);
            if (TB_RequestHelper::isAjaxRequest()) {
                $data = array();
                if ($url = $this->getLivePreviewUrl()) {
                    $data['livePreviewUrl'] = $url;
                }

                $this->sendJsonSuccess("CP Saved!", $data);

                return;
            } else {
                TB_RequestHelper::redirect($this->tbUrl->generate('default'));
            }
        }

        $install_log = $this->engine->getSettingsModel('engine_log', 0)->getScopeSettings('install');
        $this->data['can_install_sample_data'] = !empty($install_log['can_install_sample_data']);

        if ($this->data['can_install_sample_data']) {
            $available_themes = array();
            foreach ($this->engine->getThemes() as $theme) {
                unset($theme['preview']);

                $variants_dir = $this->context->getThemesDir() . '/' . $theme['id'] . '/demo/variants';
                if (is_dir($variants_dir)) {
                    $theme['variants'] = array();
                    foreach (sfFinder::type('file')->name('*.php')->sort_by_name()->in($variants_dir) as $file) {
                        $variant_settings = require $file;

                        $theme['variants'][] = array(
                            'id'   => strtok(basename($file), '.php'),
                            'name' => $variant_settings['name']
                        );
                    }
                }

                $available_themes[] = $theme;
            }

            $this->data['available_themes'] = $available_themes;
        }

        if (!empty($_SESSION['tb_refresh_modifications'])) {
            $this->data['refresh_modifications'] = true;
            $this->data['refresh_mods_url']      = htmlspecialchars_decode($this->engine->getOcUrl()->link('extension/modification/refresh', 'token=' . $this->engine->getOcSession()->data['token'], 'SSL'));
            $this->data['check_maintenance_url'] = $this->tbUrl->generateJs('default/checkMaintenance', 'previous_maintenance_mode=' . $this->engine->getOcConfig()->get('config_maintenance'));
            unset($_SESSION['tb_refresh_modifications']);
        }

        if (!empty($_SESSION['tb_save_colors'])) {
            $this->themeData->addJavascriptVar('tb/save_colors', 1);
        }

        $this->renderTemplate('theme');
    }

    public function removePavilion()
    {
        $pavilion_files = array(
            'admin/controller/module/pavilion.php',
            'admin/language/english/module/pavilion.php',
            'catalog/controller/common/tb_themes.php',
            'catalog/language/english/pavilion/theme.lang.php',
            'catalog/view/theme/pavilion/',
            'tb_themes/pavilion/'
        );

        $removed = true;

        foreach ($pavilion_files as $file) {

            $file = $this->context->getRootDir() . '/' . $file;

            if (is_file($file)) {
                if (is_writable($file)) {
                    unlink($file);
                } else {
                    $removed = false;
                }
            } else
            if (is_dir($file)) {
                if (is_writable($file)) {
                    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($file, 4096), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
                        if (!is_writable($path->getPathname())) {
                            $removed = false;
                        } else {
                            $path->isDir() ? rmdir($path->getPathname()) : unlink($path->getPathname());
                        }
                    }
                    rmdir($file);
                } else {
                    $removed = false;
                }
            }
        }

        if ($removed) {
            $this->sendJsonSuccess('Pavilion files have been removed');
        } else {
            $this->sendJsonError('Some Pavilion files were not removed due to insufficient permissions');
        }
    }

    public function checkMaintenance()
    {
        if (isset($this->request->get['previous_maintenance_mode']) && empty($this->request->get['previous_maintenance_mode'])) {
            /** @var ModelSettingSetting $OcSettingModel */
            $OcSettingModel = $this->engine->getOcModel('setting/setting');
            $OcSettingModel->editSettingValue('config', 'config_maintenance', false);
        }

        $this->sendJsonSuccess('maintenance checked');
    }

    public function removeInstallSampleData()
    {
        $install_log = $this->engine->getSettingsModel('engine_log', 0)->getScopeSettings('install', true);
        unset($install_log['can_install_sample_data']);
        $this->engine->getSettingsModel('engine_log', 0)->setAndPersistScopeSettings('install', $install_log);

        $this->sendJsonSuccess('Sample data check removed');
    }

    protected function checkEngineUpgrade(&$theme_settings)
    {
        $check1 = $theme_settings['install_info']['engine_version'] != $this->engine->getVersion();
        $check2 = !isset($theme_settings['install_info']['oc_version']) || $theme_settings['install_info']['oc_version'] != VERSION;

        if (!$check1 && !$check2) {
            return false;
        }

        $this->data['reason']           = $check1 ? 'engine' : 'opencart';
        $this->data['current_version']  = $theme_settings['install_info']['engine_version'];
        $this->data['new_version']      = $this->engine->getVersion();
        $this->data['oc_version']       = isset($theme_settings['install_info']['oc_version']) ? $theme_settings['install_info']['oc_version'] : false;
        $this->data['upgrade_url']      = $this->tbUrl->generateJs("default/upgradeEngine");
        $this->data['refresh_mods_url'] = htmlspecialchars_decode($this->engine->getOcUrl()->link('extension/modification/refresh', 'token=' . $this->engine->getOcSession()->data['token'], 'SSL'));

        $this->renderTemplate('engine_upgrade');

        return true;
    }

    protected function checkStoreInstalled(&$theme_settings)
    {
        if (!empty($theme_settings) || $this->context->getStoreId() == 0) {
            return true;
        }

        $key = 'config_' . ($this->engine->gteOc22() ? 'theme' : 'template');

        foreach ($this->engine->getThemeModel()->getAllStores() as $store) {
            if ($store['store_id'] == $this->context->getStoreId()) {
                if ($this->engine->getDbSettingsHelper('setting')->getKey($key, $store['store_id'], 'config') == $this->context->getBasename()) {
                    $result = $this->engine->getThemeExtension()->getInstaller()->enableStore($this->context->getStoreId());

                    if (true === $result) {
                        TB_RequestHelper::redirect($this->tbUrl->generate('default'));
                    }
                }

                $this->data['default_store_url'] = $this->tbUrl->generateJs("default/index", '', array('store_id' => 0));
                $this->data['enable_store_url']  = $this->tbUrl->generateJs("default/enableStore");
                $this->data['store']             = $store;

                $default_theme = $this->engine->getThemeExtension()->getInstaller()->getDefaultThemeInfo();
                $this->data['theme_error'] = !is_array($default_theme) ? $default_theme : false;

                $this->renderTemplate('store_not_enabled');

                return false;
            }
        }

        return true;
    }

    public function enableStore()
    {
        $result = $this->engine->getThemeExtension()->getInstaller()->enableStore($this->context->getStoreId());

        /** @var Theme_Admin_LayoutBuilderModel $layoutBuilderModel */
        $layoutBuilderModel = $this->getModel('layoutBuilder');
        $layoutBuilderModel->rebuildDefaultAreaSettings($this->context->getStoreId());

        if (true !== $result) {
            $this->sendJsonError($result);
        } else {
            $this->sendJsonSuccess('Store enabled');
        }
    }

    public function upgradeEngine()
    {
        $theme_settings = $this->getThemeModel()->getSettings(true);

        $check1 = $theme_settings['install_info']['engine_version'] != $this->engine->getVersion();
        $check2 = !isset($theme_settings['install_info']['oc_version']) || $theme_settings['install_info']['oc_version'] != VERSION;

        if (!$check1 && !$check2) {
            return $this->sendJsonError('No need to upgrade as the current installed version matches the uploaded one');
        }

        $this->engine->getThemeExtension()->getInstaller()->upgradeEngine();

        if (!empty($theme_settings['colors'])) {
            /** @var Theme_Admin_ImportModel $importModel */
            $importModel = $this->engine->getThemeExtension()->getModel('import');
            $importModel->mergeThemeColorsWithDefaultColors($theme_settings['colors']);
        }

        $theme_settings['install_info']['engine_version'] = $this->engine->getVersion();
        $theme_settings['install_info']['oc_version']     = VERSION;
        $theme_settings['install_info']['upgrade_date']   = date('d.m.Y H:i');

        if (!isset($theme_settings['install_info']['install_date'])) {
            $theme_settings['install_info']['install_date'] = date('d.m.Y H:i');
        }

        $this->getThemeModel()->setAndPersistSettings($theme_settings);

        foreach ($this->engine->getOcModel('setting/store')->getStores() as $store) {
            $store_theme_settings = $this->engine->getSettingsModel('theme', $store['store_id'])->getScopeSettings($this->engine->getThemeId(), true);

            $store_theme_settings['install_info']['engine_version'] = $this->engine->getVersion();

            $this->engine->getSettingsModel('theme', $store['store_id'])->persistCustomSettings($store_theme_settings, $this->engine->getThemeId());
        }

        $colors_url = $this->extension->getTbUrl()->generateJs('default', '', array(
            'hash' => 'tb_cp_panel_theme_settings,color_settings_tab'
        ));

        $_SESSION['tb_save_colors'] = 1;

        return $this->sendJsonSuccess('The engine has been upgraded', array('colors_url' => $colors_url));
    }

    protected function checkPavilionUpgrade(&$theme_settings)
    {
        if (!empty($theme_settings['upgrade_pavilion'])) {
            if (!$this->engine->getThemeExtension()->getInstaller()->checkPavilionUpgrade($this->engine->getThemeId())) {
                $pavilion_files = array(
                    'admin/controller/module/pavilion.php',
                    'admin/language/english/module/pavilion.php',
                    'catalog/controller/common/tb_themes.php',
                    'catalog/language/english/pavilion/theme.lang.php',
                    'catalog/view/theme/pavilion/',
                    'tb_themes/pavilion/'
                );

                $can_remove = true;
                foreach ($pavilion_files as $index => $file) {

                    $file = $this->context->getRootDir() . '/' . $file;

                    if (!file_exists($file)) {
                        unset($pavilion_files[$index]);
                    } else
                    if (!is_writable($file)) {
                        $can_remove = false;
                    } else
                    if (is_dir($file)) {
                        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($file, 4096), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
                            if (!is_writable($path->getPathname())) {
                                $can_remove = false;
                                break;
                            }
                        }
                    }
                }

                if (empty($pavilion_files)) {
                    unset($theme_settings['upgrade_pavilion']);
                    $this->getThemeModel()->setAndPersistSettings($theme_settings);
                } else {
                    $this->data['pavilion_files'] = $pavilion_files;
                    $this->data['remove_pavilion_url'] = $can_remove ? $this->tbUrl->generateJs("default/removePavilion") : false;
                }

                return false;
            }

            $uninstall_url = $this->engine->getOcUrl()->link('extension/module/uninstall', 'token=' . $this->engine->getOcSession()->data['token'] . '&extension=pavilion', 'SSL');
            $this->data['uninstall_pavilion'] = htmlspecialchars_decode($uninstall_url);

            if (!empty($this->request->get['doUpgrade']) && !empty($this->request->get['theme_id'])) {

                require_once TB_THEME_ROOT . '/library/PavilionUpgrade.php';

                $pavilionUpgrade = new TB_PavilionUpgrade($this->engine, $this->extension);
                $progress = $pavilionUpgrade->upgrade((string) $this->request->get['theme_id'], $theme_settings['upgrade_pavilion']);

                if (!is_array($theme_settings['upgrade_pavilion']) && $theme_settings['upgrade_pavilion'] == '1' || $progress != $theme_settings['upgrade_pavilion']['progress']) {
                    $this->sendJsonSuccess('The theme has been updated', array('progress' => $progress));
                } else {
                    $this->sendJsonError('There is an error with the update');
                }
            } else {
                $this->data['burnengine_url']   = $this->tbUrl->generateJs("default/index");
                $this->data['uninstall_url']    = str_replace("&amp;", "&", $uninstall_url);
                $this->data['upgrade_url']      = $this->tbUrl->generateJs("default/index", "doUpgrade=1&chunk=0");
                $this->data['refresh_mods_url'] = htmlspecialchars_decode($this->engine->getOcUrl()->link('extension/modification/refresh', 'token=' . $this->engine->getOcSession()->data['token'] . '&extension=pavilion', 'SSL'));

                $this->renderTemplate('pavilion_upgrade');
            }

            return true;
        }

        return false;
    }

    protected function checkPostInstallErrors()
    {
        $errors = array();

        if (!$this->engine->gteOc2()) {
            $index_file = $this->context->getRootDir() . '/index.php';
            if (false === strpos(file_get_contents($index_file), 'common/BurnEngine')) {
                $errors[] = 'The OpenCart root <code style="padding: 1px 5px; background: #eee; vertical-align: top;">index.php</code> file does not contain the BurnEngine initializaiton code. Please, refer to the <a href="">corresponding documentation</a> in order to resolve this issue.';
            }
        }


        if (empty($errors)) {
            return false;
        }

        $this->data['errors'] = $errors;
        $this->data['heading_message'] = '<strong class="text-capitalize">BurnEngine</strong> has some errors, which prevent it from working properly:';
        $this->data['reload_message'] = 'Please, correct the errors above and then <a href="' . $this->tbUrl->generate('default') . '">reload the theme module</a>.';

        $this->renderTemplate('install_errors');

        return true;
    }

    protected function renderInstallErrors($errors)
    {
        $this->data['errors'] = (array) $errors;
        $this->data['theme_name'] = $this->context->getThemeInfo('name');
        $this->data['heading_message'] = '<strong class="text-capitalize">BurnEngine</strong> has not been installed due to the following reason(s):';

        $install_theme_url = $this->engine->getOcUrl()->link('extension/module/install', 'token=' . $this->session->data['token'] . '&extension=' . $this->context->getBasename(), 'SSL');
        $this->data['reload_message'] = 'Please, correct the errors above and then try to <a href="' . $install_theme_url . '">install the theme module</a> again';

        if ($this->engine->gteOc2()) {
            $this->engine->getOcModel('extension/extension')->uninstall('module', $this->context->getBasename());
        } else {
            $this->engine->getOcModel('setting/extension')->uninstall('module', $this->context->getBasename());
        }

        $this->renderTemplate('install_errors');
    }

    protected function saveSettings($post_data, $theme_settings)
    {
        if (empty($theme_settings)) {
            $theme_settings = array();
        }

        if (isset($post_data['form_data'])) {
             $post_data = json_decode(html_entity_decode((string) $post_data['form_data'], ENT_COMPAT, 'UTF-8'), true);
        }

        unset($theme_settings['colors']['header'], $theme_settings['colors']['intro'], $theme_settings['colors']['content'], $theme_settings['colors']['footer']);

        foreach ($this->extension->getPlugins() as $plugin) {
            if ($plugin instanceof TB_AdminDataPlugin && (isset($post_data[$plugin->getConfigKey()]) || $plugin->saveAlways())) {
                $result = $plugin->saveData($post_data, $theme_settings);
                if (false !== $result && null !== $result && is_array($result)) {
                    foreach ($result as $key => $value) {
                        if (isset($theme_settings[$key]) && !empty($value)) {
                            // Sort $theme_settings[$key] by keys based on $value
                            $value = array_merge(array_flip(array_keys($value)), array_replace($theme_settings[$key], $value));
                        }
                        $theme_settings[$key] = $value;
                    }
                }
            }
        }

        $filterEvent = new sfEvent($this->getThemeModel(), 'admin.beforePersistMainForm', array('form' => $post_data, 'theme_settings' => $theme_settings));
        $this->engine->getEventDispatcher()->notify($filterEvent);

        $theme_settings['store_id'] = $this->context->getStoreId();
        //unset($theme_settings['area']);

        $this->getThemeModel()->setAndPersistSettings($theme_settings);

        if (empty($theme_settings['system']['cache_enabled'])) {
            $this->engine->wipeStylesCache();
        }

        $filterEvent = new sfEvent($this->getThemeModel(), 'admin.afterPersistMainForm', array('form' => $post_data, 'theme_settings' => $theme_settings));
        $this->engine->getEventDispatcher()->notify($filterEvent);
    }

    public function getLivePreviewToken()
    {
        $token = $this->context->getStoreUrl() . '!*!' . TB_Utils::getIp() . '!*!' . time();

        $this->engine->getDbSettingsHelper('setting')->persistKey($this->engine->getThemeId() . '_livePreviewToken', $this->context->getStoreId(), 'config', $token);

        return $this->sendJsonSuccess("Preview mode activated", array('livePreviewToken' => base64_encode($token)));
    }

    public function clearCache()
    {
        if (!$this->validate()) {
            $this->sendJsonError($this->error['warning']);

            return;
        }

        $this->engine->wipeAllCache();
        $this->engine->wipeVarsCache('*', false, $this->context->getTbCacheDir() .'/db/', false);

        //TB_ClassCacheGenerator::deleteCache();
        /** @var Theme_Admin_LayoutBuilderModel $layoutBuilderModel */
        $layoutBuilderModel = $this->getModel('layoutBuilder');
        $layoutBuilderModel->rebuildDefaultAreaSettings($this->context->getStoreId());

        $this->sendJsonSuccess('The cache has been removed');
    }

    public function clearDbCache()
    {
        if (!$this->validate()) {
            $this->sendJsonError($this->error['warning']);

            return;
        }

        $this->engine->wipeVarsCache('*', false, $this->context->getTbCacheDir() .'/db/', false);

        $this->sendJsonSuccess('The database cache has been removed');
    }

    public function getCategoryFlatTreeJSON()
    {
        /** @var Theme_Admin_CategoryModel $categoryModel */
        $categoryModel = $this->getModel('category');
        $flat_tree = $categoryModel->getCategoriesFlatTree();
        $result = array();
        // hack because json_encode doesn't respect hashes if the key is numeric - it reorders the array by key
        foreach ($flat_tree as $item) {
            $result[] = $item;
        }

        $this->setOutput(json_encode($result));
    }

    public function fileManager()
    {
        if (!isset($this->request->get['target'])) {
            // OC >= 2.1.0.1
            $this->request->get['target'] = 'foo';
        }

        $this->load->controller('common/filemanager');
        $this->data['filemanager'] = $this->response->getOutput();

        $this->renderTemplate('filemanager');
    }

    public function getImagePath()
    {
        if (!isset($this->request->get['filename'])) {
            return $this->sendJsonError('Not existing path to image');
        }

        $filename = (string) $this->request->get['filename'];

        if (!is_file(DIR_IMAGE . $filename)) {
            return $this->sendJsonError('Invalid path to image ' . $filename);
        }

        $width = $this->getArrayKey('width', $this->request->get, 100);
        $height = $this->getArrayKey('height', $this->request->get, 100);

        $this->setOutput(json_encode(array('path' => $this->getOcModel('tool/image')->resize($filename, $width, $height))));
    }
}
