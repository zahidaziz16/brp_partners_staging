<?php

class Theme_Admin_MenuController extends TB_AdminController
{
    public function index()
    {
        $this->determineParameters();
        $this->renderTemplate('theme_menu');
    }

    public function contents()
    {
        $this->determineParameters();
        $this->renderTemplate('theme_menu_contents');
    }

    public function contentsByLanguage()
    {
        $menu_id = !empty($this->request->get['menu_id']) ? (string) $this->request->get['menu_id'] : '';
        $menu = $this->engine->getSettingsModel('menu')->getScopeSettings($menu_id, true);

        $result = array(
            'contents' => array(),
            'menu'     => !empty($menu) ? $menu : array()
        );

        foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
            $result['contents'][$language_code] = $this->fetchTemplate('theme_menu_contents_item', array(
                'language_code' => $language_code
            ));
        }

        $this->setOutput(json_encode($result));
    }

    protected function determineParameters()
    {
        $name = 'Main menu';
        if (!empty($this->request->get['menu_name'])) {
            $name = urldecode(base64_decode($this->request->get['menu_name']));
        }

        $current_menu = array(
            'id'   => !empty($this->request->get['menu_id']) ? $this->request->get['menu_id'] : 'main',
            'name' => $name,
            'tree' => array()
        );
        $menu_options = array();

        foreach ($this->engine->getSettingsModel('menu')->getValues() as $menu) {
            if (!isset($menu['name'])) {
                continue;
            }

            $menu_options[$menu['id']] = $menu['name'];
            if ($menu['id'] == $current_menu['id']) {
                $current_menu = $menu;
            }
        }

        if ($current_menu['id'] == 'new') {
            $current_menu['id'] = TB_Utils::genRandomString();
        }

        if (empty($menu_options)) {
            $menu_options[$current_menu['id']] = $current_menu['name'];
        }

        if (empty($current_menu['tree'])) {
            $current_menu['tree'] = TB_FormHelper::initLangVarsSimple(array(), array(), $this->engine->getEnabledLanguages());
            $current_menu['tree_ids'] = array();
        } else {
            foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
                if (empty($current_menu['tree'][$language_code])) {
                    $current_menu['tree'][$language_code] = array();
                }
            }
        }

        $this->data['menu']         = $current_menu;
        $this->data['menu_options'] = $menu_options;
    }

    public function save()
    {
        if (empty($this->request->post['menu_data'])) {
            $this->sendJsonError('Invalid menu data');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $menu_data = json_decode(html_entity_decode($this->request->post['menu_data'], ENT_COMPAT, 'UTF-8'), true);

        if (empty($menu_data) || !isset($menu_data['menu'])) {
            $this->sendJsonError('Invalid menu data');
        }

        $menu = $menu_data['menu'];

        foreach ($menu['tree'] as $language_code => &$item) {
            $item = json_decode((string) html_entity_decode($item, ENT_COMPAT, 'UTF-8'), true);
        }

        if ($menu['id'] != 'main' && !$this->engine->getThemeModel()->keyExists($menu['id'], 'menu')) {
            $menu['id'] = TB_Utils::slugify($menu['name']) . '-' . $menu['id'];
        }

        $db_menu = $menu;

        foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
            if (!empty($menu['tree'][$language_code])) {
                $db_menu['tree'][$language_code] = $menu['tree'][$language_code];
                $db_menu['tree_ids'][$language_code] = $this->extractMenuTreeIds($db_menu['tree'][$language_code]);
            }
        }

        if (!empty($db_menu['is_dirty'])) {
            unset($db_menu['is_dirty']);
            //$this->engine->wipeVarsCache('*.menu_html.*');
        }

        $this->engine->getSettingsModel('menu')->setAndPersistScopeSettings($menu['id'], $db_menu);

        $this->sendJsonSuccess('The menu has been saved!', array(
            'menu_id'   => $menu['id'],
            'menu_name' => $menu['name']
        ));
    }

    public function remove()
    {
        if (empty($this->request->get['menu_id']) || $this->request->get['menu_id'] == 'main') {
            return $this->sendJsonError('Invalid arguments.');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $menu = $this->engine->getSettingsModel('menu')->getScopeSettings((string) $this->request->get['menu_id'], true);
        if (empty($menu)) {
            return $this->sendJsonError('Cannot find menu');
        }

        $this->engine->getSettingsModel('menu')->deleteScopeSettings((string) $this->request->get['menu_id']);

        return $this->sendJsonSuccess('Menu deleted.');
    }

    protected function extractMenuTreeIds($tree)
    {
        $page_ids = array();
        $category_ids = array();

        foreach ($tree as $item) {
            if ($item['data']['type'] == 'page') {
                $page_ids[] = $item['data']['id'];
            }

            if ($item['data']['type'] == 'category') {
                $category_ids[] = $item['data']['id'];
            }

            if (isset($item['children']) && !empty($item['children'])) {
                $extracted = $this->extractMenuTreeIds($item['children']);
                $page_ids += $extracted['page_ids'];
                $category_ids += $extracted['category_ids'];
            }
        }

        return array('page_ids' => $page_ids, 'category_ids' => $category_ids);
    }
}