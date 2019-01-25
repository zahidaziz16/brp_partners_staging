<?php

class LiveSearch_Admin_DefaultController extends TB_AdminController
{
    /**
     * @var LiveSearch_Admin_DefaultModel
     */
    protected $defaultModel;

    public function init()
    {
        $this->defaultModel = $this->getModel('default');
    }

    public function index()
    {
        $this->data['settings'] = $this->defaultModel->getSettings();

        $this->renderTemplate('index');
    }

    public function saveSettings()
    {
        if ($this->request->server['REQUEST_METHOD'] != 'POST' || !$this->validate()) {
            return $this->sendJsonError('You do not have sufficient permissions to modify this module!');
        }

        if (empty($this->request->post['live_search'])) {
            return $this->sendJsonError('Empty data!');
        }

        $this->defaultModel->saveSettings($this->request->post['live_search']);

        return $this->sendJsonSuccess('The slider has been saved');
    }

    public function checkPermissions()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('You do not have sufficient permissions to modify this module!');
        }

        return $this->sendJsonSuccess('Success');
    }
}