<?php

class TB_Installer
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

    public function __construct(TB_Engine $engine, TB_AdminExtension $extension)
    {
        $this->engine         = $engine;
        $this->adminExtension = $extension;
        $this->context        = $engine->getContext();
        $this->themeData      = $engine->getThemeData();
    }

    public function installEngine()
    {
        $this->createSettingsTable();

        if ($this->checkInstallRequirements()) {
            $_SESSION['tb_rebuild_default_settings'] = array();

            foreach ($this->engine->getThemeModel()->getAllStores() as $store) {
                if ($store['store_id'] == 0 || $store['has_theme']) {
                    $result = $this->enableStore($store['store_id']);

                    if (true !== $result) {
                        $this->persistInstallErrors($result);

                        break;
                    }

                    array_push($_SESSION['tb_rebuild_default_settings'], (int) $store['store_id']);
                }
            }

            $default_theme = $this->getDefaultThemeInfo();

            $this->checkPavilionUpgrade($default_theme['id']);
            $this->createAdditionalRecords();

            if ($this->engine->gteOc2()) {
                $this->installOc2();
            } else {
                $this->installOc1();
            }

            $install_log = $this->engine->getSettingsModel('engine_log', 0)->getScopeSettings('install', true);
            $install_log['can_install_sample_data'] = 1;
            $this->engine->getSettingsModel('engine_log', 0)->setAndPersistScopeSettings('install', $install_log);
        }

        if (!$this->engine->gteOc23()) {
            TB_RequestHelper::redirect($this->adminExtension->getTbUrl()->generate('default'));
        }
    }

    protected function checkInstallRequirements()
    {
        $errors = array();

        if ($this->engine->gteOc2() && !is_writable(DIR_MODIFICATION)) {
            $errors[] = 'The folder <strong>' . DIR_MODIFICATION . '</strong> needs to be writable for OpenCart to work. Please change its permissions to 777';
        }

        if (count($this->engine->getThemes()) == 0) {
            $errors[] = 'There is no theme compatible with BurnEngine to be applied. Please, check your <strong>' . $this->context->getThemesDir() . '</strong> folder. It has either missing or invalid theme files.';
        }

        if (!empty($errors)) {
            $this->persistInstallErrors($errors);

            return false;
        }

        return true;
    }

    protected function persistInstallErrors($errors)
    {
        $this->errors = array_merge($this->errors, (array) $errors);
        $this->engine->getThemeSettingsModel()->setAndPersistScopeSettings($this->engine->getThemeId(), array('install_errors' => $this->errors));
    }

    protected function createSettingsTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'burnengine_setting` (
                  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
                  `store_id` smallint(6) unsigned NOT NULL,
                  `group` varchar(32) NOT NULL,
                  `key` varchar(128) NOT NULL,
                  `value` longtext NOT NULL,
                  `serialized` tinyint(4) NOT NULL,
                  PRIMARY KEY (`setting_id`),
                  KEY `theme_name` (`group`,`key`)
               ) ENGINE=MyISAM COLLATE=utf8_general_ci;';
        $this->engine->getDbHelper()->getDb()->query($sql);
    }

    public function getDefaultThemeInfo()
    {
        $default_theme = array('release_date' => date('d.m.Y', strtotime("2011-01-01")));
        $fallback_theme = array();

        /** @var Theme_Admin_ImportModel $importModel */
        $importModel = $this->engine->getThemeExtension()->getModel('import');

        foreach ($this->engine->getThemes() as $theme) {
            $result = $importModel->getTheme($theme['id']);
            if (!is_array($result)) {
                return $result;
            }

            list($info) = $importModel->getTheme($theme['id']);

            $fallback_theme['release_date'] = $info['release_date'];
            $fallback_theme['id'] = $theme['id'];

            $previous_date = date_create_from_format('d.m.Y', $default_theme['release_date']);
            $release_date = date_create_from_format('d.m.Y', $info['release_date']);

            if ($info['default_for_engine'] && ($release_date >= $previous_date)) {
                $default_theme = $fallback_theme;
            }
        }

        if (!isset($default_theme['id'])) {
            $default_theme = $fallback_theme;
        }

        if (!isset($default_theme['id'])) {
            return 'No valid theme can be found';
        }

        return $default_theme;
    }

    public function enableStore($store_id)
    {
        $default_theme = $this->getDefaultThemeInfo();

        if (!is_array($default_theme)) {
            return $default_theme;
        }

        /** @var Theme_Admin_ImportModel $importModel */
        $importModel = $this->engine->getThemeExtension()->getModel('import');

        $result = $importModel->saveTheme($default_theme['id'], $store_id);
        if (true !== $result) {
            return $result;
        }

        $key = 'config_' . ($this->engine->gteOc22() ? 'theme' : 'template');
        $this->engine->getDbSettingsHelper('setting')->persistKey($key, $store_id, 'config', $this->context->getBasename());

        if ($this->engine->gteOc22()) {
            $this->engine->getDbSettingsHelper('setting')->persistKey($this->context->getBasename() . '_status', $store_id, $this->context->getBasename(), 1);
        }

        $logo_path = ($this->engine->gteOc2() ? 'catalog/' : 'data/') . 'sample_data/' . $default_theme['id'];
        $this->engine->getDbSettingsHelper('setting')->persistKey('config_logo', $store_id, 'config', $logo_path . '_logo.png');

        return true;
    }

    protected function createAdditionalRecords()
    {
        $dbHelper = $this->engine->getDbHelper();

        $indexes = array(
            'product'             => 'manufacturer_id',
            'category'            => 'parent_id',
            'category_to_store'   => 'category_id',
            'product_to_category' => 'category_id'
        );
        foreach ($indexes as $table => $index) {
            if (!$dbHelper->getDb()->query('SHOW INDEX FROM `' . DB_PREFIX . $table . '` WHERE KEY_NAME = "' . $index . '"')->num_rows) {
                $dbHelper->getDb()->query('ALTER TABLE `' . DB_PREFIX . $table . '` ADD INDEX (`' . $index . '`)');
            }
        }

        if (!$this->engine->getThemeModel()->getLayoutIdByName('TB_Widgets')) {
            $dbHelper->addRecord('layout', array(
                'name' => 'TB_Widgets'
            ));
        }

        if (!$dbHelper->getDb()->query('SELECT * FROM `' . DB_PREFIX . 'layout_route` WHERE route = "product/manufacturer/info"')->rows) {
            $dbHelper->addRecord('layout', array(
                'name' => 'Manufacturer Info'
            ));
            $layout_id = $dbHelper->getLastId();

            foreach ($this->engine->getThemeModel()->getAllStores() as $store) {
                $dbHelper->addRecord('layout_route', array(
                    'layout_id' => $layout_id,
                    'store_id'  => $store['store_id'],
                    'route'     => 'product/manufacturer/info'
                ));
            }
        }

        $this->engine->getSettingsModel('engine_log', 0)->setAndPersistScopeSettings('install', array(
            'host'           => $this->context->getHost(),
            'base_http'      => $this->context->getBaseHttpsIf(),
            'ip'             => TB_Utils::getIp(),
            'date'           => date('d.m.Y H:i'),
            'oc_version'     => VERSION,
            'engine_version' => $this->engine->getVersion(),
            'language'       => $this->context->getCurrentLanguage('code')
        ));
    }

    protected function installOc1()
    {
        $index_file = $this->context->getRootDir() . '/index.php';
        if (false === strpos(file_get_contents($index_file), 'common/BurnEngine') && is_writable($index_file)) {
            file_put_contents($index_file, str_replace('$controller = new Front($registry);', "\$controller = new Front(\$registry);\n\n//TB\n\$controller->dispatch(new Action('common/BurnEngine', array('front' => \$controller)), new Action('error/not_found'));", file_get_contents($index_file)));
        }
    }

    protected function installOc2()
    {
        $this->installEngineOcMod();

        if ($this->engine->gteOc22()) {
            $old_module_controller = $this->context->getAdminDir() . '/controller/module/BurnEngine.php';
            if (is_file($old_module_controller)) {
                @unlink($old_module_controller);
            }
        }

        if ($this->engine->gteOc23()) {
            $old_theme_controller = $this->context->getAdminDir() . '/controller/theme/BurnEngine.php';
            if (is_file($old_theme_controller)) {
                @unlink($old_theme_controller);
            }
        }

        $_SESSION['tb_refresh_modifications'] = true;
    }

    protected function installEngineOcMod()
    {
        $this->removeOcMod(TB_Engine::getName());

        $engine_mod_path = $this->context->getConfigDir() . '/data/ocmod/engine.ocmod.xml';

        if (!file_exists($engine_mod_path)) {
            throw new Exception($engine_mod_path . ' cannot be found.');
        }

        $xml = file_get_contents($engine_mod_path);

        if ($this->engine->gteOc23()) {
            $xml = str_replace('<file path="catalog/controller/module/latest.php">', '<file path="catalog/controller/extension/module/latest.php">', $xml);

            $engine_2300_mod_path = $this->context->getConfigDir() . '/data/ocmod/engine2300.ocmod.xml';
            $xml = str_replace('</modification>', file_get_contents($engine_2300_mod_path) . "\n</modification>", $xml);
        }

        if (!$this->engine->gteOc21()) {
            $engine_2000_mod_path = $this->context->getConfigDir() . '/data/ocmod/engine2000.ocmod.xml';
            $xml = str_replace('</modification>', file_get_contents($engine_2000_mod_path) . "\n</modification>", $xml);
        }

        if (!$this->engine->gteOc22()) {
            $engine_2100_mod_path = $this->context->getConfigDir() . '/data/ocmod/engine2100.ocmod.xml';

            if (!file_exists($engine_2100_mod_path)) {
                throw new Exception($engine_2100_mod_path . ' cannot be found.');
            }

            $replacement = file_get_contents($engine_2100_mod_path);
        } else {
            $engine_2200_mod_path = $this->context->getConfigDir() . '/data/ocmod/engine2200.ocmod.xml';

            if (!file_exists($engine_2200_mod_path)) {
                throw new Exception($engine_2200_mod_path . ' cannot be found.');
            }

            $replacement = file_get_contents($engine_2200_mod_path);

            if ($this->engine->gteOc23()) {
                $replacement = str_replace('<file path="catalog/controller/module/latest.php">', '<file path="catalog/controller/extension/module/latest.php">', $replacement);
            }

            $this->addEvents();
        }

        $this->installOcMod(str_replace('</modification>', $replacement . "\n</modification>", $xml), true);
    }

    protected function addEvents()
    {
        if (!$this->engine->gteOc22()) {
            return;
        }

        /** @var ModelExtensionEvent $extensionEvent */
        $extensionEvent = $this->engine->getOcModel('extension/event');
        $extensionEvent->deleteEvent(TB_Engine::getName());

        $eventName = 'theme/BurnEngine/onControllerBefore';
        if ($this->engine->gteOc23()) {
            $eventName = 'extension/' . $eventName;
        }

        $extensionEvent->addEvent(TB_Engine::getName(), 'admin/controller/*/before', $eventName);
    }

    public function checkPavilionUpgrade($burnengine_theme_id)
    {
        foreach ($this->engine->getThemeModel()->getAllStores() as $store) {
            if ($theme_settings = $this->engine->getDbSettingsHelper('setting')->getKey('theme_pavilion', $store['store_id'], 'pavilion')) {
                $themeModel = $this->engine->getSettingsModel('theme', 0);

                $new_theme_settings = $themeModel->getScopeSettings($burnengine_theme_id, true);
                $new_theme_settings['upgrade_pavilion'] = 1;

                $themeModel->setAndPersistScopeSettings($burnengine_theme_id, $new_theme_settings);

                return true;
            }
        }

        return false;
    }

    public function upgradeEngine()
    {
        foreach ($this->engine->getExtensions() as $extension) {
            if (method_exists($extension, 'upgrade')) {
                $extension->upgrade();
            }
        }

        if ($this->engine->gteOc2()) {
            $this->installEngineOcMod();
            $this->addEvents();

            /** @var ModelUserUserGroup $ocUserGroupModel */
            $ocUserGroupModel = $this->engine->getOcModel('user/user_group');
            $permission = ($this->engine->gteOc22() ? 'theme' : 'module') . '/' . TB_Engine::getName();

            $ocUserGroupModel->removePermission($this->engine->getOcUser()->getGroupId(), 'access', $permission);
            $ocUserGroupModel->removePermission($this->engine->getOcUser()->getGroupId(), 'modify', $permission);

            $ocUserGroupModel->addPermission($this->engine->getOcUser()->getGroupId(), 'access', $permission);
            $ocUserGroupModel->addPermission($this->engine->getOcUser()->getGroupId(), 'modify', $permission);
        }
        
        $upgrade_log = $this->engine->getSettingsModel('engine_log', 0)->getScopeSettings('upgrade');
        if (!$upgrade_log) {
            $upgrade_log = array();
        }

        $upgrade_log[] = array(
            'date'           => date('d.m.Y H:i'),
            'host'           => $this->context->getHost(),
            'base_http'      => $this->context->getBaseHttpsIf(),
            'ip'             => TB_Utils::getIp(),
            'oc_version'     => VERSION,
            'engine_version' => $this->engine->getVersion(),
            'language'       => $this->context->getCurrentLanguage('code')
        );

        $this->engine->getSettingsModel('engine_log', 0)->setAndPersistScopeSettings('upgrade', $upgrade_log);
    }

    public function uninstallEngine()
    {
        $this->engine->wipeAllCache();

        foreach ($this->engine->getOcModel('setting/store')->getStores() as $store) {
            $this->engine->getDbSettingsHelper('setting')->deleteGroup(TB_Engine::getName(), $store['store_id']);
        }

        $indexes = array(
            'product'             => 'manufacturer_id',
            'category'            => 'parent_id',
            'category_to_store'   => 'category_id',
            'product_to_category' => 'category_id'
        );

        foreach ($indexes as $table => $index) {
            if ($this->engine->getOcDb()->query('SHOW INDEX FROM `' . DB_PREFIX . $table . '` WHERE KEY_NAME = "' . $index . '"')->num_rows) {
                $this->engine->getOcDb()->query('ALTER TABLE `' . DB_PREFIX . $table . '` DROP INDEX `' . $index . '`');
            }
        }

        $this->engine->getDbHelper()->deleteRecord('layout', array('name' => 'TB_Widgets'));
        $this->engine->getDbSettingsHelper('setting')->deleteKeyBeginsWith(TB_Engine::getName(), 0);

        foreach ($this->engine->getExtensions() as $extension) {
            if (!$extension->isCoreExtension()) {
                if (method_exists($extension, 'uninstall')) {
                    $extension->uninstall();
                }
            }
        }

        $this->engine->getOcDb()->query('DROP TABLE IF EXISTS `' . DB_PREFIX . 'burnengine_setting`');

        $this->engine->gteOc2() ? $this->uninstallOC2() : $this->uninstallOC1();
    }

    public function uninstallOC1()
    {
        $index_file = $this->context->getRootDir() . '/index.php';
        if (strpos(file_get_contents($index_file), 'common/BurnEngine') && is_writable($index_file)) {
            file_put_contents($index_file, str_replace(array("//TB\n", "\$controller->dispatch(new Action('common/BurnEngine', array('front' => \$controller)), new Action('error/not_found'));\n"), '', file_get_contents($index_file)));
        }
    }

    public function uninstallOC2()
    {
        $this->removeOcMod(TB_Engine::getName(), true);

        if (!$this->engine->gteOc22()) {
            return;
        }

        /** @var ModelExtensionEvent $extensionEvent */
        $extensionEvent = $this->engine->getOcModel('extension/event');
        $extensionEvent->deleteEvent(TB_Engine::getName());

        /** @var ModelExtensionExtension $ocExtensionModel */
        $ocExtensionModel = $this->engine->getOcModel('extension/extension');
        $ocExtensionModel->uninstall('theme', TB_Engine::getName());
        $ocExtensionModel->uninstall('module', TB_Engine::getName());
    }

    public function installVQmod($filename)
    {
        if (pathinfo($filename, PATHINFO_EXTENSION) != 'xml') {
            $filename .= '.xml';
        }

        if (!is_file($filename)) {
            $filename = $this->context->getConfigDir() . '/data/vqmod/' . basename($filename);
        }

        if (!is_file($filename)) {
            return 'The specified file does not exist: ' . $filename;
        }

        $vqmod_folder = $this->context->getRootDir() . '/vqmod/xml';

        if (!is_dir($vqmod_folder)) {
            return 'The folder ' . $vqmod_folder . ' does not exist. It seems vQmod is not installed.';
        }

        if (!is_writable($vqmod_folder)) {
            return 'The folder ' . $vqmod_folder . ' is not writable by the server. You need to install the mod manually.';
        }

        return copy($filename, $vqmod_folder . '/' . basename($filename));
    }

    public function removeVQmod($filename)
    {
        $filename = $this->context->getRootDir() . '/vqmod/xml/' . basename($filename);
        if (is_file($filename)) {
            unlink($filename);
        }
    }

    public function installOcMod($xml, $contents = false)
    {
        if (!$contents) {
            if (!file_exists($xml)) {
                throw new Exception($xml . ' cannot be found.');
            }

            $xml = file_get_contents($xml);
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXml($xml);

        $version = $dom->getElementsByTagName('version')->item(0)->nodeValue;
        if ($version == '{{version}}') {
            $version = $this->engine->getVersion();
        }

        $data = array(
            'name'    => $dom->getElementsByTagName('name')->item(0)->nodeValue,
            'author'  => $dom->getElementsByTagName('author')->item(0)->nodeValue,
            'version' => $version,
            'link'    => $dom->getElementsByTagName('link')->item(0)->nodeValue,
            'code'    => $dom->getElementsByTagName('code')->item(0)->nodeValue,
            'status'  => 1,
            'xml'     => $xml
        );

        // Ensure the mod has not been manually installed
        /** @var ModelExtensionModification $ocModification */
        $ocModification = $this->engine->getOcModel('extension/modification');

        $ocModification->deleteModification($data['code']);

        $_SESSION['tb_refresh_modifications'] = true;

        $ocModification->addModification($data);
    }

    public function removeOcMod($code, $refresh = false)
    {
        $mods = $this->engine->getDbHelper()->getRecords('modification', array('code' => $code));

        /** @var ModelExtensionModification $ocModification */
        $ocModification = $this->engine->getOcModel('extension/modification');
        foreach ((array) $mods as $mod) {
            $ocModification->deleteModification((int) $mod['modification_id']);
        }

        if (!$refresh) {
            return;
        }

        // Clear all modification files
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(DIR_MODIFICATION, 4096), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isDir() ? rmdir($path->getPathname()) : (basename($path->getPathname()) != 'index.html' && unlink($path->getPathname()));
        }

        $previous_maintenance_mode = $this->engine->getOcConfig()->get('config_maintenance');

        $this->themeData->disable_redirect = true;
        $this->engine->getThemeExtension()->loadController('extension/modification/refresh');
        $this->themeData->disable_redirect = false;

        if (empty($previous_maintenance_mode)) {
            /** @var ModelSettingSetting $OcSettingModel */
            $OcSettingModel = $this->engine->getOcModel('setting/setting');
            $OcSettingModel->editSettingValue('config', 'config_maintenance', false);
        }
    }
}