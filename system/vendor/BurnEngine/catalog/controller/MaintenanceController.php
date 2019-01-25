<?php

class Theme_Catalog_MaintenanceController extends TB_CatalogController
{
    public function index()
    {
        $this->load->language('common/maintenance');

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['message'] = $this->language->get('text_message');
        $this->data['lang'] = $this->language->get('code');
        $this->data['direction'] = $this->language->get('direction');
        $this->data['name'] = $this->config->get('config_name');
        $this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');

        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
            $this->data['logo'] = $this->context->getImageUrl() . $this->config->get('config_logo');
        } else {
            $this->data['logo'] = '';
        }

        $this->children = array(
            'module/language'
        );

        $this->template = $this->engine->getConfigTheme() . '/template/common/maintenance.tpl';

        $this->response->setOutput($this->render());
    }
}