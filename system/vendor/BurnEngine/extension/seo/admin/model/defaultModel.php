<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class Seo_Admin_DefaultModel extends Seo_DefaultModel
{
    public function activateSeo()
    {
        $this->saveSettings($this->getDefaultSettings());
    }

    public function getSettings($raw = false)
    {
        $settings = parent::getSettings($raw);
        $this->insertSettingsStoreMeta($settings);

        return $settings;
    }

    protected function insertSettingsStoreMeta(&$settings)
    {
        if (count($this->engine->getEnabledLanguages()) > 1) {
            foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
                foreach (array('title', 'description', 'keyword') as $field) {
                    if (!isset($settings['store_meta'][$language_code][$field])) {
                        $var_prefix = 'config_meta';
                        if ($field == 'title' && !$this->engine->gteOc2()) {
                            $var_prefix = 'config';
                        }
                        $settings['store_meta'][$language_code][$field] = $this->getOcConfig()->get($var_prefix . '_' . $field);
                    }
                }
            }
        }
    }

    public function saveSettings(array $settings)
    {
        if (!isset($settings['store_meta'])) {
            $this->insertSettingsStoreMeta($settings);
        }

        if (isset($settings['store_meta'])) {
            $default_meta = $settings['store_meta'][$this->engine->getDefaultCatalogLanguage('code')];
            unset($settings['store_meta'][$this->engine->getDefaultCatalogLanguage('code')]);

            $title_var_name = $this->engine->gteOc2() ? 'config_meta_title' : 'config_title';

            $this->engine->getDbSettingsHelper('setting')->persistGroup('config', array(
                $title_var_name           => $default_meta['title'],
                'config_meta_description' => $default_meta['description'],
                'config_meta_keyword'     => $default_meta['keyword']
            ), $this->context->getStoreId());
        }

        $this->getSettingsModel()->setAndPersistScopeSettings('seo', $settings);
    }

    public function getGeneralSettings($raw = false)
    {
        $item_settings = $this->getItemDefaultSettings();

        foreach ($item_settings as $item => &$sections) {
            foreach ($sections as $section => &$options) {
                if (isset($options['languages'])) {
                    foreach ($this->engine->getAllLanguages() as $language_code => $language) {
                        if (!isset($options['languages']['language_code'])) {
                            $options['languages'][$language_code] = 1;
                        }
                    }
                }
            }
        }

        $settings = array_merge_recursive_distinct($item_settings, $this->getGeneralDefaultSettings());
        $current_settings = $this->getSettingsModel(0)->getScopeSettings('seo_general', $raw);

        if (!empty($current_settings)) {
            $settings = array_replace_recursive($settings, $current_settings);
        }

        return $settings;
    }

    public function getEntitySettings($entity)
    {
        $settings = $this->getGeneralSettings();

        return isset($settings[$entity]) ? $settings[$entity] : null;
    }

    public function saveGeneralSettings(array $settings)
    {
        $this->getSettingsModel(0)->setAndPersistScopeSettings('seo_general', array_replace($this->getGeneralSettings(), $settings));
    }

    public function updateOcIndexes()
    {
        if (true) {
            return;
        }

        $index_info = $this->db->query('SHOW INDEX FROM `' . DB_PREFIX . 'url_alias` WHERE COLUMN_NAME = "query"');

        if ($index_info->num_rows && $index_info->row['Non_unique']) {
            $this->db->query('ALTER TABLE `' . DB_PREFIX . 'url_alias` DROP INDEX `burnengine_query`');
        }

        if (!$index_info->num_rows || $index_info->row['Non_unique']) {
            $this->db->query('ALTER TABLE `' . DB_PREFIX . 'url_alias` ADD UNIQUE INDEX `burnengine_query` (`query`)');
        }
    }

    public function install()
    {
        $this->activateSeo();

        $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'burnengine_url_alias` (
                  `url_alias_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `language_id` smallint(6) NOT NULL,
                  `query` varchar(255) NOT NULL,
                  `keyword` varchar(255) NOT NULL,
                  PRIMARY KEY (`url_alias_id`),
                  UNIQUE KEY `language_id` (`language_id`,`query`),
                  INDEX (`keyword`)
               ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';
        $this->db->query($sql);

        $this->getSettingsModel(0)->setAndPersistScopeSettings('seo_general', $this->getGeneralSettings());

        if (!$this->engine->gteOc2()) {
            $this->dbHelper->addColumn('product_description', 'meta_title', 'VARCHAR(255)');
            $this->dbHelper->addColumn('category_description', 'meta_title', 'VARCHAR(255)');
            $this->dbHelper->addColumn('information_description', 'meta_title', 'VARCHAR(255)');
            $this->dbHelper->addColumn('information_description', 'meta_description', 'TEXT');
            $this->dbHelper->addColumn('information_description', 'meta_keyword', 'TEXT');
        }

        $this->updateOcIndexes();
        $this->installMod();
    }

    public function uninstall()
    {
        $this->db->query('DROP TABLE IF EXISTS `' . DB_PREFIX . 'burnengine_url_alias`');

        if (!$this->engine->gteOc2()) {
            /*
            $this->dbHelper->dropColumn('product_description', 'meta_title');
            $this->dbHelper->dropColumn('category_description', 'meta_title');
            $this->dbHelper->dropColumn('information_description', 'meta_title');
            $this->dbHelper->dropColumn('information_description', 'meta_description');
            $this->dbHelper->dropColumn('information_description', 'meta_keyword');
            */
        }

        $this->getSettingsModel()->deleteScopeSettings('seo');
        $this->getSettingsModel()->deleteScopeSettings('seo_general');

        $this->removeMod();
    }

    public function upgrade()
    {
        $this->installMod();
    }

    protected function removeMod()
    {
        if (!$this->engine->gteOc2()) {
            $this->engine->getThemeExtension()->getInstaller()->removeVQmod('tb_seo.xml');
        } else {
            $this->engine->getThemeExtension()->getInstaller()->removeOcMod('tbExtensionSeo', true);
        }
    }

    protected function installMod()
    {
        $this->removeMod();

        if (!$this->engine->gteOc2()) {
            $this->engine->getThemeExtension()->getInstaller()->installVQmod($this->extension->getRootDir() . '/config/data/vqmod/tb_seo.xml');
        } else {
            $contents = file_get_contents($this->extension->getRootDir() . '/config/data/ocmod/seo.ocmod.xml');
            if (!version_compare(VERSION, '2.0.3.1', '>')) {
                $contents = str_replace(array('.tpl|', '.php|'), array('.tpl,', '.php,'), $contents);
            }

            if (!$this->engine->gteOc22()) {
                $contents = str_replace('<file path="catalog/controller/startup/seo_url.php">', '<file path="catalog/controller/common/seo_url.php">', $contents);
                $contents = str_replace('class ControllerStartupSeoUrl extends Controller {', 'class ControllerCommonSeoUrl extends Controller {', $contents);
            }

            if ($this->engine->gteOc23()) {
                $remove = TB_Utils::strGetBetween($contents, '<!-- gte[2.3.0.0] -->', '<!-- /gte[2.3.0.0] -->');
                $contents = str_replace($remove, '', $contents);
            }

            $this->engine->getThemeExtension()->getInstaller()->installOcMod($contents, true);
        }
    }

    protected function getGeneralDefaultSettings()
    {
        $original_language = $this->engine->getDefaultCatalogLanguage();

        unset($original_language['url']);

        return array(
            'original_language'          => $original_language,
            'multilingual_keyword'       => 1,
            'autofill_title_desc'        => 0,
            'auto_keyword'               => 0,
            'auto_keyword_skip_existing' => 1,
            'auto_keyword_transliterate' => 1
        );
    }

    protected function getItemDefaultSettings()
    {
        $settings =  array(
            'product' => array(
                'seo_keyword' => array(
                    'pattern'       => '[name]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'transliterate' => 1,
                    'languages'     => array()
                ),
                'meta_title' => array(
                    'pattern'       => '[name]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'languages'     => array()
                ),
                'h1_heading' => array(
                    'pattern'       => '[name]',
                    'languages'     => array()
                ),
                'meta_description' => array(
                    'pattern'       => '[description:chars:160]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'languages'     => array()
                ),
                'meta_keyword' => array(
                    'pattern'       => '[description:words:20]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'languages'     => array()
                )
            ),
            'category' => array(
                'seo_keyword' => array(
                    'pattern'       => '[name]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'transliterate' => 1,
                    'languages'     => array()
                ),
                'meta_title' => array(
                    'pattern'       => '[name]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'languages'     => array()
                ),
                'h1_heading' => array(
                    'pattern'       => '[name]',
                    'languages'     => array()
                ),
                'meta_description' => array(
                    'pattern'       => '[description:chars:160]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'languages'     => array()
                ),
                'meta_keyword' => array(
                    'pattern'       => '[description:words:20]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'languages'     => array()
                )
            ),
            'information' => array(
                'seo_keyword' => array(
                    'pattern'       => '[title]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'transliterate' => 1,
                    'languages'     => array()
                ),
                'meta_title' => array(
                    'pattern'       => '[title]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'languages'     => array()
                ),
                'h1_heading' => array(
                    'pattern'       => '[title]',
                    'languages'     => array()
                ),
                'meta_description' => array(
                    'pattern'       => '[description:chars:160]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'languages'     => array()
                ),
                'meta_keyword' => array(
                    'pattern'       => '[description:words:20]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'languages'     => array()
                )
            ),
            'manufacturer' => array(
                'seo_keyword' => array(
                    'pattern'       => '[name]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'transliterate' => 1,
                    'languages'     => array()
                )
            )
        );

        if ($this->engine->getOcConfig('stories_settings')) {
            $settings['story'] = array(
                'seo_keyword' => array(
                    'pattern'       => '[title]',
                    'autofill'      => 1,
                    'skip_existing' => 1,
                    'transliterate' => 1,
                    'languages'     => array()
                )
            );
        }

        return $settings;
    }

    public function updateEntityMeta(sfEvent $event)
    {
        $entity = $event['entity_name'];
        if ($entity == 'manufacturer') {
            return;
        }

        foreach ($event['data'][$entity . '_description'] as $language_id => $description) {
            $record = array('meta_title' => $description['meta_title']);
            if ($entity == 'information') {
                $record['meta_description'] = $description['meta_description'];
            }
            $this->dbHelper->updateRecord($entity . '_description', $record, array(
                'language_id'   => $language_id,
                $entity . '_id' => $event['entity_id']
            ));
        }
    }
}