<?php

require_once DIR_SYSTEM . 'vendor/stories/model.php';

class ModelStoriesSystem extends ModelStoriesModel
{
    public function install($redirect = true)
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "story` (
              `story_id` int(11) NOT NULL AUTO_INCREMENT,
              `image` varchar(255) NOT NULL,
              `status` tinyint(1) NOT NULL,
              `sticky` tinyint(1) NOT NULL,
              `disable_comments` tinyint(1) NOT NULL,
              `views` int(11) NOT NULL,
              `date_added` datetime NOT NULL,
              `date_updated` datetime NOT NULL,
              PRIMARY KEY (`story_id`)
		    ) ENGINE=MyISAM COLLATE=utf8_general_ci;
		");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "story_description` (
              `story_id` int(11) NOT NULL,
              `language_id` int(11) NOT NULL,
              `title` varchar(255) NOT NULL,
              `teaser` text NOT NULL,
              `description` text NOT NULL,
              `meta_title` text NOT NULL,
              `meta_description` text NOT NULL,
              PRIMARY KEY (`story_id`, `language_id`)
            ) ENGINE=MyISAM COLLATE=utf8_general_ci;
		");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "story_to_store` (
              `story_id` int(11) NOT NULL,
              `store_id` int(11) NOT NULL,
              PRIMARY KEY (`story_id`, `store_id`)
            )
		");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "story_to_layout` (
              `story_id` int(11) NOT NULL,
              `store_id` int(11) NOT NULL,
              `layout_id` int(11) NOT NULL,
              PRIMARY KEY (`story_id`, `store_id`)
            )
		");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "story_tag` (
              `tag_id` int(11) NOT NULL AUTO_INCREMENT,
              `language_id` int(11) NOT NULL,
              `name` varchar(255) NOT NULL,
              `description` text NOT NULL,
              `meta_title` text NOT NULL,
              `meta_description` text NOT NULL,
              `status` tinyint(1) NOT NULL,
              `date_added` datetime NOT NULL,
              `date_updated` datetime NOT NULL,
              PRIMARY KEY (`tag_id`)
            ) ENGINE=MyISAM COLLATE=utf8_general_ci;
		");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "story_to_tag` (
              `story_id` int(11) NOT NULL,
              `tag_id` int(11) NOT NULL,
              PRIMARY KEY (`story_id`, `tag_id`)
            )
		");

        foreach ($this->getModel()->getAllStores() as $store) {
            $default_settings = $this->getDefaultSettings();
            $default_settings['lang'] = array();
            foreach ($this->getModel('localisation/language')->getLanguages() as $language) {
                $default_settings['lang'][$language['language_id']] = array(
                    'page_title'       => '',
                    'meta_title'       => '',
                    'meta_description' => ''
                );
            }

            $this->getModel()->editSetting('stories_settings', $default_settings, $store['store_id']);
        }

        /** @var ModelUserUserGroup $userGroupModel */
        $userGroupModel = $this->getModel('user/user_group');
        /** @var \Cart\User|User $user */
        $user = $this->user;

        foreach (array('access', 'modify') as $permission) {
            if (method_exists($userGroupModel, 'removePermission')) {
                $userGroupModel->removePermission($user->getId(), $permission, 'stories/index');
                $userGroupModel->removePermission($user->getId(), $permission, 'stories/tag');
                $userGroupModel->removePermission($user->getId(), $permission, 'stories/module');
            }

            $userGroupModel->addPermission($user->getId(), $permission, 'stories/index');
            $userGroupModel->addPermission($user->getId(), $permission, 'stories/tag');
            $userGroupModel->addPermission($user->getId(), $permission, 'stories/module');
        }

        $this->dbHelper->insert('url_alias', array(
            'query'   => 'stories/index',
            'keyword' => 'blog'
        ));

        if (version_compare(VERSION, '2.0.0.0') > 0) {
            $this->installOcMod(DIR_SYSTEM . '/vendor/stories/ocmod/stories.ocmod.xml');
        } else {
            $this->installVQmod(DIR_SYSTEM . '/vendor/stories/vqmod/stories.xml');
        }

        if ($redirect && !version_compare(VERSION, '2.3.0.0', '>=')) {
            $this->response->redirect($this->url->link('module/stories', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function getDefaultSettings()
    {
        return array(
            'keyword'                  => 'blog',
            'stories_per_page'         => 10,
            'auto_seo_url'             => 1,
            'skip_existing_seo_url'    => 1,
            'thumbnail_position'       => 'top',
            'text_limit'               => 400,
            'image_list_width'         => 800,
            'image_list_height'        => 320,
            'image_description_width'  => 800,
            'image_description_height' => 480,
            'comments'                 => 'disabled',
            'disqus_shortname'         => '',
            'social_share'             => ''
        );
    }

    public function uninstall()
    {
        $this->dbHelper->dropTable(array('story', 'story_description', 'story_to_store', 'story_to_layout', 'story_tag', 'story_to_tag'));
        $this->dbHelper->delete('setting', array(version_compare(VERSION, '2.0.0.0') > 0 ? 'code' : 'group' => 'stories'));

        if (!version_compare(VERSION, '2.0.0.0') > 0) {
            /** @var ModelUserUserGroup $userGroupModel */
            $userGroupModel = $this->getModel('user/user_group');

            $userGroupModel->removePermission($this->user->getId(), 'access', 'stories/index');
            $userGroupModel->removePermission($this->user->getId(), 'access', 'stories/tag');
            $userGroupModel->removePermission($this->user->getId(), 'access', 'stories/module');

            $userGroupModel->removePermission($this->user->getId(), 'modify', 'stories/index');
            $userGroupModel->removePermission($this->user->getId(), 'modify', 'stories/tag');
            $userGroupModel->removePermission($this->user->getId(), 'modify', 'stories/module');
        }

        $this->dbHelper->delete('url_alias', array('query' => 'stories/index'));
        $this->db->query('DELETE FROM ' . DB_PREFIX . 'url_alias WHERE query LIKE "story_id=%"');

        if (defined('TB_SEO_MOD') && class_exists('TB_Engine') && TB_Engine::hasInstance()) {
            $this->db->query('DELETE FROM ' . DB_PREFIX . 'burnengine_url_alias WHERE query LIKE "story_id=%"');
        }

        if (version_compare(VERSION, '2.0.0.0') > 0) {
            $this->removeOcMod('tbStories', true);
        } else {
            $this->removeVQmod('stories.xml');
        }
    }

    public function installVQmod($filename)
    {
        if (!array_key_exists('vqmod', $GLOBALS) || !class_exists('VQMod')) {
            return;
        }

        if (!is_file($filename)) {
            throw new Exception($filename . ' cannot be found.');
        }

        $vqmod_folder = DIR_APPLICATION . '/vqmod/xml';

        if (!is_dir($vqmod_folder)) {
            return;
        }

        if (!is_writable($vqmod_folder)) {
            throw new Exception('The folder ' . $vqmod_folder . ' is not writable by the server. You need to install the mod manually.');
        }

        copy($filename, $vqmod_folder . '/' . basename($filename));
    }

    public function removeVQmod($filename)
    {
        $filename = DIR_APPLICATION . '/vqmod/xml/' . basename($filename);
        if (is_file($filename)) {
            unlink($filename);
        }
    }

    public function installOcMod($mod_path)
    {
        if (!file_exists($mod_path)) {
            throw new Exception($mod_path . ' cannot be found.');
        }

        $xml = file_get_contents($mod_path);

        if (!version_compare(VERSION, '2.2.0.0', '>=')) {
            $xml = str_replace('<file path="catalog/controller/startup/seo_url.php">', '<file path="catalog/controller/common/seo_url.php">', $xml);
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXml($xml);

        $data = array(
            'name'    => $dom->getElementsByTagName('name')->item(0)->nodeValue,
            'author'  => $dom->getElementsByTagName('author')->item(0)->nodeValue,
            'version' => $dom->getElementsByTagName('version')->item(0)->nodeValue,
            'link'    => $dom->getElementsByTagName('link')->item(0)->nodeValue,
            'code'    => $dom->getElementsByTagName('code')->item(0)->nodeValue,
            'status'  => 1,
            'xml'     => $xml
        );

        // Ensure the mod has not been manually installed
        $this->removeOcMod($data['code']);

        /** @var ModelExtensionModification $ocModification */
        $ocModification = $this->getModel('extension/modification');
        $ocModification->addModification($data);

        $_SESSION['tb_refresh_modifications'] = true;
    }

    public function removeOcMod($code, $refresh = false)
    {
        $mods = $this->dbHelper->getRecords('modification', array('code' => $code));

        /** @var ModelExtensionModification $ocModification */
        $ocModification = $this->getModel('extension/modification');
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

        $action = new Action('extension/modification/refresh');
        $action->execute($this->registry, array('redirect' => false));
    }
}