<?php

class Theme_Admin_ExtensionsController extends TB_AdminController
{
    /**
     * @var Theme_Admin_ExtensionsModel
     */
    protected $extensionsModel;

    public function init()
    {
        $this->extensionsModel = $this->getModel('extensions');
    }

    public function index()
    {
        $this->data['not_installed_extensions'] = $this->engine->getNotInstalledExtensions();
        $this->data['installed_extensions'] = $this->engine->getExtensions();

        $this->renderTemplate('theme_extensions');
    }

    public function installExtension()
    {
        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $name = $this->request->get['name'];
        $result = $this->extensionsModel->installExtension($name);

        if (true == $result['success']) {
            $this->session->data['success_alert'] = $result['message'];
            return $this->sendJsonSuccess($result['message'], array('reload' => 1));
        } else {
            return $this->sendJsonError($result['message']);
        }
    }

    public function uninstallExtension()
    {
        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $name = $this->request->get['name'];
        $result = $this->extensionsModel->uninstallExtension($name);

        if (true == $result['success']) {
            $this->session->data['success_alert'] = $result['message'];
            return $this->sendJsonSuccess($result['message'], array('reload' => 1));
        } else {
            return $this->sendJsonError($result['message']);
        }
    }
}