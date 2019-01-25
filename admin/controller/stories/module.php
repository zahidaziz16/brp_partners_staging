<?php

require_once dirname(__FILE__) . '/lib/controller.php';

class ControllerStoriesModule extends ControllerStoriesAdmin
{
	public function index()
    {
        $this->redirect($this->url->link('stories/index', 'token=' . $this->session->data['token'], 'SSL'));

        $this->mergeLanguage('module/stories');

		$this->document->setTitle($this->data['heading_title']);

		$this->load->model('setting/setting');

		if ($this->isPost() && $this->validate()) {
			$this->getModel()->editSetting('stories_module', isset($this->request->post['stories_module']) ? $this->request->post['stories_module'] : array());

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->link('module/stories'));
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

        $this->data['success'] = '';
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

  		$this->setBreadcrumbs();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->link('extension/module'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->link('module/stories');
		$this->data['cancel'] = $this->link('extension/module');
		$this->data['modules'] = array();

		if (isset($this->request->post['stories_module'])) {
			$this->data['modules'] = $this->request->post['stories_module'];
		} elseif ($this->config->get('stories_module')) {
			$this->data['modules'] = $this->config->get('stories_module');
		}

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->renderOutput('stories/modules.tpl');
	}

    protected function validate()
    {
        return parent::validatePermission('module/stories');
    }

	public function install()
    {
		$this->getModel('system')->install();
	}

	public function uninstall()
    {
		$this->getModel('system')->uninstall();
	}
}