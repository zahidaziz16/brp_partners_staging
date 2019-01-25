<?php

class Theme_Admin_ModuleController extends Controller
{
    protected static $booted = false;

    public function index()
    {
        $engine = $this->getEngine();

        if (!empty($engine->getOcRequest()->get['route']) && $engine->getOcRequest()->get['route'] == 'extension/extension/theme/install' && $engine->getOcRequest()->get['extension'] == 'BurnEngine') {
            // In case the 'extension/theme/BurnEngine/install' is changed to 'theme/BurnEngine' from ControllerEventCompatibility because of the presense of the old ControllerThemeBurnEngine
            $engine->getThemeExtension()->getInstaller()->installEngine();

            return '';
        }

        if ($engine->gteOc22() && !self::$booted) {
            // Check if OpenCart is upgraded
            $themeData = $engine->getThemeData();
            if (version_compare(VERSION, $themeData['theme_settings']['install_info']['oc_version'], '>')) {
                $theme_settings = $engine->getThemeModel()->getSettings(true);

                $engine->getThemeExtension()->getInstaller()->upgradeEngine();

                $theme_settings['install_info']['oc_version']   = VERSION;
                $theme_settings['install_info']['upgrade_date'] = date('d.m.Y H:i');

                if (!isset($theme_settings['install_info']['install_date'])) {
                    $theme_settings['install_info']['install_date'] = date('d.m.Y H:i');
                }

                $engine->getThemeModel()->setAndPersistSettings($theme_settings);

                TB_RequestHelper::redirect($this->url->link('theme/BurnEngine', 'token=' . $this->session->data['token'], true));
            } else {
                die('BurnEngine has not booted. This can be a result of an improper installation or an event deletion from a third party plugin.');
            }
        }

        if ($engine->gteOc22() && $_REQUEST['route'] == 'module/BurnEngine') {
            TB_RequestHelper::redirect($this->url->link('theme/BurnEngine', 'token=' . $this->session->data['token'], true));
        }

        if (($engine->getOcRequest()->server['REQUEST_METHOD'] == 'POST')
            && !$engine->getOcUser()->hasPermission('modify', $this->getRouteType() . '/' . TB_BASENAME)
            && !$this->ignoredPostPermissions())
        {
            if (TB_RequestHelper::isAjaxRequest()) {
                $engine->getOcResponse()->setOutput(json_encode(array('success' => false, 'message' => 'Permission error')));

                return '';
            } else {
                return new Action('error/permission');
            }
        }

        if ($this->getRouteType() == 'module') {
            $extensions = $engine->getThemeModel()->getInstalledOcModules();
        } else {
            /** @var ModelExtensionExtension $ocExtensionModel */
            $ocExtensionModel = $engine->getOcModel('extension/extension');
            $extensions = $ocExtensionModel->getInstalled('theme');
        }

        if (!in_array(TB_BASENAME, $extensions)) {
            return new Action('error/permission');
        }

        $extension_name  = (string) $this->getRequestVar('extension', TB_BASENAME);
        $controller_name = (string) $this->getRequestVar('controller', 'default');
        $action          = (string) $this->getRequestVar('action', 'index');

        $this->getDispatcher()->dispatch($extension_name, $controller_name, $action);

        return '';
    }

    public function onControllerBefore()
    {
        if (self::$booted) {
            return;
        }

        $eventName = 'theme/BurnEngine/';
        if ($this->getEngine()->gteOc23()) {
            $eventName = 'extension/' . $eventName;
        }

        $this->getEngine()->getOcEvent()->register('view/*/before', new Action($eventName . 'onViewBefore'));
        $this->getEngine()->getOcEvent()->register('model/*/after', new Action($eventName . 'onModelAfter'));

        self::$booted = true;
    }

    public function onModelAfter($route)
    {
        if (true) {
            return;
        }

        if ($route == 'setting/setting/editSetting') {
            
            $engine = $this->getEngine();

            if (!isset($engine->getOcRequest()->post['config_theme'])) {
                return;
            }

            $theme_enabled = $engine->getOcRequest()->post['config_theme'] == TB_Engine::getName();
            $store_id = (int) $this->getRequestVar('store_id', 0);

            $engine->getDbSettingsHelper('setting')->persistKey(TB_Engine::getName() . '_status', $store_id, TB_Engine::getName(), (int) $theme_enabled);

            if (0 != $store_id) {
                $result = $engine->getThemeExtension()->getInstaller()->enableStore($store_id);

                if (true !== $result) {
                    trigger_error($result);
                }

                /** @var Theme_Admin_LayoutBuilderModel $layoutBuilderModel */
                $layoutBuilderModel = $engine->getThemeExtension()->getModel('layoutBuilder');
                $layoutBuilderModel->rebuildDefaultAreaSettings($store_id);
            }
        }
    }

    public function onViewBefore($route, &$data)
    {
        $data['registry'] = $this->registry;

        $check = 'extension/module';
        if ($this->getEngine()->gteOc23()) {
            $check = 'extension/' . $check;
        }

        if ($route == $check) {
            foreach ($data['extensions'] as $key => $extension) {
                if (0 === stripos(strip_tags($extension['name']), $this->getEngine()->getName())) {
                    unset($data['extensions'][$key]);
                }
            }
        }
    }

    /**
     * @return TB_Engine
     */
    protected function getEngine()
    {
        return $this->getDispatcher()->getEngine();
    }

    /**
     * @return TB_AdminDispatcher
     */
    protected function getDispatcher()
    {
        /** @var Registry $registry */
        $registry = $this->registry;
        $store_id = (int) $this->getRequestVar('store_id', 0);

        return TB_AdminDispatcher::getInstance($registry, TB_BASENAME, $store_id);
    }

    protected function getRouteType()
    {
        $type = $this->getEngine()->gteOc22() ? 'theme' : 'module';

        if ($this->getEngine()->gteOc23()) {
            $type = 'extension/' . $type;
        }

        return $type;
    }

    protected function getRequestVar($var, $default)
    {
        /** @var $request Request */
        $request = $this->request;

        if (isset($request->get[$var])) {
            return $request->get[$var];
        } else
        if (isset($request->post[$var])) {
            return $request->post[$var];
        }

        return $default;
    }

    protected function ignoredPostPermissions()
    {
        /** @var $request Request */
        $request = $this->request;

        if (!isset($request->get['controller']) || !isset($request->get['action'])) {
            return false;
        }

        if ($request->get['controller'] == 'LayoutBuilder' && $request->get['action'] == 'createRowSettingsForm') {
            return true;
        }

        if ($request->get['controller'] == 'Widget' && $request->get['action'] == 'createForm') {
            return true;
        }

        return false;
    }

    public function uninstall()
    {
        $this->getEngine()->getThemeExtension()->getInstaller()->uninstallEngine();
    }
}