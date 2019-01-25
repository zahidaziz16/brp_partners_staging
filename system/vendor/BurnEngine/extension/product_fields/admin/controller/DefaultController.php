<?php

class ProductFields_Admin_DefaultController extends TB_AdminController
{
    /**
     * @var ProductFields_Admin_DefaultModel
     */
    protected $defaultModel;

    public function init()
    {
        $this->defaultModel = $this->getModel('default');
    }

    public function index()
    {
        $this->data['fields'] = $this->defaultModel->getFields();

        $this->renderTemplate('index');
    }

    public function editField()
    {
        $field = array();
        $action = 'add';

        if (isset($this->request->get['id'])) {
            $field = $this->defaultModel->getField($this->request->get['id']);
            $action = 'edit';
        }

        $this->defaultModel->initField($field);

        $this->data['action'] = $action;
        $this->data['field'] = $field;

        /** @var Theme_CategoryModel $categoryModel */
        $categoryModel = $this->engine->getThemeExtension()->getModel('category');
        $this->data['categories_json'] = json_encode($categoryModel->getCategoriesFlatTree());

        $this->renderTemplate('edit');
    }

    public function saveField()
    {
        if ($this->request->server['REQUEST_METHOD'] != 'POST' || !$this->validate()) {
            return $this->sendJsonError('You do not have sufficient permissions to modify this module!');
        }

        if (empty($this->request->post['field'])) {
            return $this->sendJsonError('Empty data!');
        }

        $this->defaultModel->saveField($this->request->post['field']);

        return $this->sendJsonSuccess('The field has been saved');
    }

    public function deleteField()
    {
        if ($this->request->server['REQUEST_METHOD'] != 'GET' || !$this->validate()) {
            return $this->sendJsonError('You do not have sufficient permissions to modify this module!');
        }

        if (empty($this->request->get['id'])) {
            return $this->sendJsonError('Empty data!');
        }

        $this->defaultModel->deleteField((int) $this->request->get['id']);

        return $this->sendJsonSuccess('Success');
    }

    public function checkPermissions()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('You do not have sufficient permissions to modify this module!');
        }

        return $this->sendJsonSuccess('Success');
    }
}