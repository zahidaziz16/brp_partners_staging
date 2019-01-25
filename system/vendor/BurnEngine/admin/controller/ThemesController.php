<?php

class Theme_Admin_ThemesController extends TB_AdminController
{
    /** @var  Theme_Admin_ImportModel */
    protected $importModel;

    public function init()
    {
        $this->importModel = $this->getModel('import');
        ini_set('open_basedir', null);
    }

    public function index()
    {
        $skins = array();

        $custom_skins = $this->engine->getSettingsModel('skin_custom_' . $this->engine->getThemeId(), 0)->getValues();
        $theme_skins = $this->engine->getSettingsModel('skin_' . $this->engine->getThemeId())->getValues();

        foreach (array_merge($theme_skins, $custom_skins) as $skin) {
            $skins[$skin['id']] = array(
                'name'        => $skin['name'],
                'description' => $skin['description'],
                'is_theme'    => $skin['is_theme']
            );
        }

        $this->data['skins'] = $skins;

        $this->getThemes();

        $this->renderTemplate('theme_themes');
    }

    public function themes()
    {
        $this->getThemes();

        $this->renderTemplate('theme_themes_child');
    }

    protected function getThemes()
    {
        $theme_id = '';
        if (isset($this->request->get['theme_id'])) {
            $theme_id = (string) $this->request->get['theme_id'];
        }

        $themes = $this->engine->getThemes(false);
        $is_bundle = false;
        $title = 'Themes';

        if (!empty($theme_id) && !empty($themes[$theme_id]['children'])) {
            $theme = $themes[$theme_id];
            $children = $theme['children'];

            unset($theme['children']);

            $themes = array_merge(array($theme_id => $theme), $children);
            $is_bundle = true;
            $title = $theme['name'] . ' Theme Bundle';
        }

        if (!$is_bundle) {
            foreach ($themes as $key => $theme) {
                $themes[$key]['parent_id'] = $theme['id'];
                foreach ($theme['children'] as $child) {
                    if ($child['applied']) {
                        $theme['parent_id'] = $theme['id'];
                        $themes[$key] = array_replace($theme, $child);

                        break;
                    }
                }
            }
        }

        $additional_themes = $this->engine->getCacheVar('additional_themes', array($this, 'getAdditionalThemes'));

        if (!empty($additional_themes)) {
            $additional_themes = json_decode($additional_themes, true);
        }

        if (!empty($additional_themes)) {
            foreach (array_keys($additional_themes) as $theme_id) {
                if (isset($themes[$theme_id])) {
                    unset($additional_themes[$theme_id]);
                }
            }
        }

        $this->data['additional_themes'] = !empty($additional_themes) ? $additional_themes : array();
        $this->data['themes']            = $themes;
        $this->data['title']             = $title;
        $this->data['is_bundle']         = $is_bundle;

        return $themes;
    }

    public function getAdditionalThemes()
    {
        return TB_Utils::getUrlContents('http://api.themeburn.com/all_themes');
    }

    public function applyTheme()
    {
        if (empty($this->request->get['theme_id'])) {
            return $this->sendJsonError('No theme id provided');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $theme_id = (string) $this->request->get['theme_id'];

        if (empty($this->request->get['reset'])) {
            $result = $this->importModel->saveTheme($theme_id, $this->context->getStoreId());
        } else {
            $result = $this->importModel->resetTheme($theme_id, $this->context->getStoreId());
        }

        if (true !== $result) {
            return $this->sendJsonError($result);
        }

        $this->engine->wipeAllCache();
        $_SESSION['tb_rebuild_default_settings'] = $this->context->getStoreId();

        if (empty($this->request->get['reset'])) {
            $this->sendJsonSuccess('Theme reset', array('reload' => 1));
        } else {
            TB_RequestHelper::redirect($this->engine->getOcUrl()->link('module/BurnEngine', 'token=' . $this->engine->getOcSession()->data['token'], 'SSL'));
        }

        return true;
    }

    public function extractTheme()
    {
        if (empty($this->request->get['file'])) {
            return $this->sendJsonError('No file name');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $file = $this->context->getImageDir() . '/cache/tb/' . (string) $this->request->get['file'];
        if (!is_file($file)) {
            return $this->sendJsonError('The file does not exist');
        }

        $zip = new ZipArchive();
        if (false === $zip->open($file)) {
            return $this->sendJsonError('Failed to open archive.');
        }

        $stat = $zip->statIndex(0);

        if (false === ($data = $zip->getFromName($stat['name'] . 'data.bin')) || false === ($info = $zip->getFromName($stat['name'] . 'info.ini')) || false === ($preview = $zip->getFromName($stat['name'] . 'preview.png'))) {
            return $this->sendJsonError('Invalid archive contents');
        }

        $check = $this->importModel->checkImportSettings($data);
        if ($check['error']) {
            return $this->sendJsonError($check['error']);
        }

        if (is_dir($this->engine->getContext()->getThemesDir() . '/' . $stat['name'])) {
            return $this->sendJsonError('A theme with the same id already exists!');
        }

        if (version_compare(phpversion(), '5.3.0', '>=')) {
            $info = parse_ini_string($info);
        } else {
            $tmpFile = $this->context->getImageDir() . '/cache/tb/tmp.ini';
            file_put_contents($tmpFile, $info);
            $info = parse_ini_file($tmpFile, true);
            unlink($tmpFile);
        }

        if (false === $info) {
            return $this->sendJsonError('The info file is invalid');
        }

        $info['preview'] = base64_encode($preview);
        $info['theme_id'] = trim($stat['name'], '/');

        $zip->extractTo($this->engine->getContext()->getThemesDir());
        $zip->close();

        unlink($file);

        return $this->sendJsonSuccess('Theme successfully uploaded', $info);
    }

    public function applySkin()
    {
        if (empty($this->request->get['skin_id'])) {
            return $this->sendJsonError('No skin id provided');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $skin_id = (string) $this->request->get['skin_id'];

        $group = 'skin' . (empty($this->request->get['is_theme']) ? '_custom_' : '_') . $this->engine->getThemeId();
        $store_id = empty($this->request->get['is_theme']) ? 0 : $this->context->getStoreId();
        $skin = $this->engine->getSettingsModel($group, $store_id)->getScopeSettings($skin_id);

        if (!$skin || empty($skin['data'])) {
            return $this->sendJsonError('There are no settings for skin id ' . $skin_id);
        }

        $check = $this->importModel->checkImportSettings($skin['data']);
        if ($check['error']) {
            return $this->sendJsonError($check['error']);
        }

        $skin_data = $check['data'];

        if ($skin['is_theme']) {
            $this->importModel->deleteDefaultAreaStyleSettings($this->context->getStoreId());
        } else {
            $this->importModel->deleteCustomAreaStyleSettings($this->context->getStoreId());
        }

        unset($skin_data['theme_settings']['install_info']);

        $theme_settings = $this->engine->getThemeModel()->getSettings(true);
        $theme_settings = array_intersect_key($theme_settings, array_fill_keys(array('first_time', 'payment_images', 'facebook', 'twitter', 'area_keys', 'system', 'install_info'), 1));
        $theme_settings['store_id'] = $this->context->getStoreId();
        $theme_settings = array_replace_recursive($theme_settings, $skin_data['theme_settings']);

        if (!empty($theme_settings['colors'])) {
            /** @var Theme_Admin_ImportModel $importModel */
            $importModel = $this->engine->getThemeExtension()->getModel('import');
            $importModel->mergeThemeColorsWithDefaultColors($theme_settings['colors']);
        }

        if (!empty($skin_data['area_settings'])) {
            foreach ($skin_data['area_settings'] as $style_key => $style) {
                $this->engine->getStyleSettingsModel()->setAndPersistScopeSettings($style_key, $style);
            }
        }

        $this->engine->getThemeModel()->setAndPersistSettings($theme_settings);
        $this->engine->wipeAllCache();

        return $this->sendJsonSuccess('The skin has been applied', array('reload' => 1));
    }

    public function saveSkin()
    {
        if (empty($this->request->post['skin_name'])) {
            return $this->sendJsonError('No skin name supplied.');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $theme_settings = $this->engine->getThemeModel()->getSettings(true);

        if (empty($theme_settings)) {
            return $this->sendJsonError('There are no saved settings for the selected store');
        }

        unset(
            $theme_settings['store_id'],
            $theme_settings['payment_images'],
            $theme_settings['facebook'],
            $theme_settings['twitter'],
            $theme_settings['install_info'],
            $theme_settings['system']
        );

        $export_features = array('settings', 'presets');
        /** @var Theme_Admin_ExportModel $exportModel */
        $exportModel = $this->getModel('export');
        $export = $exportModel->generate($theme_settings, $export_features);

        $is_theme = !empty($this->request->post['is_theme']);

        if ($is_theme) {
            $styles = array();
            foreach ($export['area_settings'] as $style_key => $style) {
                $styles[$this->determineAreaDefaultKey($style_key)] = $style;
            }
            $export['area_settings'] = $styles;
        }

        $skin_name = (string) $this->request->post['skin_name'];
        $skin_id = TB_Utils::slugify($skin_name) . '-' . TB_Utils::genRandomString();
        $record = array(
            'id'          => $skin_id,
            'name'        => $skin_name,
            'description' => html_entity_decode((string) $this->request->post['skin_description'], ENT_COMPAT, 'UTF-8'),
            'is_theme'    => $is_theme ? 1 : 0,
            'data'        => base64_encode(gzcompress(serialize($export), 9))
        );

        $group = 'skin' . (!$is_theme ? '_custom_' : '_') . $this->engine->getThemeId();
        $store_id = empty($this->request->get['is_theme']) ? 0 : $this->context->getStoreId();
        $this->engine->getSettingsModel($group, $store_id)->persistCustomSettings($record, $skin_id);

        $this->sendJsonSuccess('Skin saved!', array('skin_id' => $skin_id));
    }

    public function deleteSkin()
    {
        if (empty($this->request->get['skin_id'])) {
            return $this->sendJsonError('No skin id provided');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $group = 'skin' . (empty($this->request->get['is_theme']) ? '_custom_' : '_') . $this->engine->getThemeId();
        $store_id = empty($this->request->get['is_theme']) ? 0 : $this->context->getStoreId();
        $this->engine->getSettingsModel($group, $store_id)->deleteScopeSettings((string) $this->request->get['skin_id']);

        return $this->sendJsonSuccess('The skin has been removed');
    }

    protected function determineAreaDefaultKey($area_key)
    {
        $new_key = '';

        foreach (array('header', 'footer', 'intro', 'content', 'column_left', 'column_right') as $area_name) {
            if ($area_key == $area_name . '_category_global') {
                $new_key = $area_name . '_product/category__default';
                break;
            } else
            if ($area_key == $area_name . '_product_global') {
                $new_key = $area_name . '_product/product__default';
                break;
            } else
            if ($area_key == $area_name . '_global') {
                $new_key = $area_name . '__default';
                break;
            } else
            if ($area_key == $area_name . '_home') {
                $new_key = $area_key;
                break;
            } else {
                $new_key = $area_key . '__default';
            }
        }

        if (empty($new_key)) {
            throw new Exception('The key has not been matched: ' . $area_key);
        }

        return $new_key;
    }
}