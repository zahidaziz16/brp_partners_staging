<?php

abstract class TB_AdminExtension extends TB_Extension
{
    /**
     * @var TB_AdminUrl
     */
    private $tbUrl;

    protected $init_options;

    public function getInitOptions()
    {
        if (null !== $this->init_options) {
            return $this->init_options;
        }

        $this->init_options = array(
            'title'   => $this->translate('display_name'),
            'id'      => $this->name,
            'actions' => array()
        );

        return $this->init_options;
    }

    public function getInitOption($name)
    {
        $options = $this->getInitOptions();
        if (isset($options[$name])) {
            return $options[$name];
        }

        return false;
    }

    /**
     * @param string $controller_name
     *
     * @throws Exception
     * @return TB_AdminController
     */
    public function getController($controller_name)
    {
        $controller = parent::getController($controller_name);
        if (!is_subclass_of($controller, 'TB_AdminController')) {
            throw new Exception('Controller not subclass of TB_AdminController');
        }

        return $controller;
    }

    /**
     * @param $model_name
     *
     * @return Theme_Admin_ExtensionsModel
     */
    public function getModel($model_name)
    {
        return parent::getModel($model_name);
    }

    public function getTbUrl()
    {
        if (null != $this->tbUrl) {
            return $this->tbUrl;
        }

        $registry = $this->engine->getOcRegistry();

        $tbUrl = new TB_AdminUrl($registry);
        $tbUrl->setBasename($this->context->getBasename());
        $tbUrl->setDefaultStoreId($this->context->getStoreId());
        $tbUrl->setDefaultConnection('SSL');
        $tbUrl->setDefaultArgs('token=' . $this->themeData['token']);
        if (!$this->isThemeExtension()) {
            $tbUrl->setDefaultExtension($this->name);
        }
        $this->tbUrl = $tbUrl;

        return $this->tbUrl;
    }

    public function fetchTemplate($filename, array $data = array(), $full_path = false)
    {
        if (!isset($data['tbUrl'])) {
            $data['tbUrl'] = $this->getTbUrl();
        }

        if (!isset($data['tbExtension'])) {
            $data['tbExtension'] = $this;
        }

        if (!isset($data['tbEngine'])) {
            $data['tbEngine'] = $this->engine;
        }

        if (!isset($data['tbGet'])) {
            $data['tbGet'] = TB_Get::getInstance($this);
        }

        if (!$this->themeData->hasCallable('fetchTemplate')) {
            $this->themeData->addCallable(array($this, 'fetchTemplate'));
        }

        foreach ($this->themeData->exportVars() as $key => $value) {
            $data[$key] = $value;
        }

        return parent::fetchTemplate($filename, $data, $full_path);
    }
}