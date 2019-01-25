<?php
class ControllerSettingRunningNo extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('setting/running_no');
		$this->load->model('setting/running_no');
		$this->document->setTitle($this->language->get('heading_running_no'));
		$this->getList();
	}
	
	protected function getList() {
		if (isset($this->request->get['filter_module_uid'])) {
			$filter_module_uid = $this->request->get['filter_module_uid'];
		} else {
			$filter_module_uid = null;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'module_uid';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_module_uid'])) {
			$url .= '&filter_module_uid=' . urlencode(html_entity_decode($this->request->get['filter_module_uid'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['heading_title'] = $this->language->get('heading_running_no');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_running_no'),
			'href' => $this->url->link('setting/running_no', 'token=' . $this->session->data['token'], true)
		);
		$data['delete'] = $this->url->link('setting/running_no/headerdelete', 'token=' . $this->session->data['token'] . $url, true);
		$data['add'] = $this->url->link('setting/running_no/header_add', 'token=' . $this->session->data['token'] . $url, true);
		
		$filter_data = array(
			'filter_module_uid'	=> $filter_module_uid,
			'sort'            	=> $sort,
			'order'           	=> $order,
			'start'           	=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           	=> $this->config->get('config_limit_admin')
		);
		
		$record_total = $this->model_setting_running_no->getTotalRecords($filter_data);
		$results = $this->model_setting_running_no->getRecords($filter_data);
		
		$data['runns'] = array();
		foreach ($results as $result) {
			$data['runns'][] = array(
				'id'			=> $result['id'],
				'module_uid'	=> $result['module_uid'],
				'current'		=> $result['current'],
				'padding'		=> $result['padding'],
				'prefix'		=> $result['prefix'],
				'suffix'		=> $result['suffix'],
				'edit'      	=> $this->url->link('setting/running_no/header_edit', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, true)
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_running_no');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['column_module_uid'] = $this->language->get('column_module_uid');
		$data['column_current'] = $this->language->get('column_current');
		$data['column_padding'] = $this->language->get('column_padding');
		$data['column_prefix'] = $this->language->get('column_prefix');
		$data['column_suffix'] = $this->language->get('column_suffix');
		$data['column_action'] = $this->language->get('column_action');
		$data['entry_module_uid'] = $this->language->get('column_module_uid');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
			
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';
		if (isset($this->request->get['filter_module_uid'])) {
			$url .= '&filter_module_uid=' . urlencode(html_entity_decode($this->request->get['filter_module_uid'], ENT_QUOTES, 'UTF-8'));
		}
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_module_uid'] = $this->url->link('setting/running_no', 'token=' . $this->session->data['token'] . '&sort=module_uid' . $url, true);
		$data['sort_current'] = $this->url->link('setting/running_no', 'token=' . $this->session->data['token'] . '&sort=current' . $url, true);
		$data['sort_padding'] = $this->url->link('setting/running_no', 'token=' . $this->session->data['token'] . '&sort=padding' . $url, true);
		$data['sort_prefix'] = $this->url->link('setting/running_no', 'token=' . $this->session->data['token'] . '&sort=prefix' . $url, true);
		$data['sort_suffix'] = $this->url->link('setting/running_no', 'token=' . $this->session->data['token'] . '&sort=suffix' . $url, true);

		$url = '';
		if (isset($this->request->get['filter_module_uid'])) {
			$url .= '&filter_module_uid=' . urlencode(html_entity_decode($this->request->get['filter_module_uid'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $record_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('setting/running_no', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($record_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($record_total - $this->config->get('config_limit_admin'))) ? $record_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $record_total, ceil($record_total / $this->config->get('config_limit_admin')));
		$data['filter_module_uid'] = $filter_module_uid;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/running_no_header_list', $data));
	}
	
	public function header_edit() {
		$this->load->language('setting/running_no');
		$this->load->model('setting/running_no');
		//echo $this->model_setting_running_no->testing();exit;
		$this->document->setTitle($this->language->get('heading_running_no'));
		if (isset($this->request->get['id'])) {
			$headerID = $this->request->get['id'];
		} else {
			$headerID = 0;
		}
		//echo $headerID;exit;
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//echo "<pre>";print_r($this->request->post);echo "</pre>";exit;
			$this->model_setting_running_no->editRecordHeader($headerID, $this->request->post);
			$this->session->data['success'] = $this->language->get('text_header_modified_success');
		}
		
		$this->getHeaderEditForm($headerID);
	}
	
	protected function getHeaderEditForm($headerID = 0) {
		$data['header_id'] = $headerID;
		
		$data['heading_title'] = $this->language->get('heading_running_no');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_running_no'),
			'href' => $this->url->link('setting/running_no', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_running_no_edit'),
			'href' => $this->url->link('setting/running_no/header_edit', 'token=' . $this->session->data['token'] . '&id=' . $headerID, true)
		);
		
		$data['save_type'] = "edit";
		$data['text_header'] = $this->language->get('text_detail');
		$data['column_module_uid'] = $this->language->get('column_module_uid');
		$data['column_current'] = $this->language->get('column_current');
		$data['column_padding'] = $this->language->get('column_padding');
		$data['column_prefix'] = $this->language->get('column_prefix');
		$data['column_suffix'] = $this->language->get('column_suffix');
		$data['column_action'] = $this->language->get('column_action');
		$data['entry_module_uid'] = $this->language->get('entry_module_uid');
		$data['entry_current'] = $this->language->get('entry_current');
		$data['entry_padding'] = $this->language->get('entry_padding');
		$data['entry_prefix'] = $this->language->get('entry_prefix');
		$data['entry_suffix'] = $this->language->get('entry_suffix');
		$data['button_save'] = $this->language->get('button_save');
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['module_uid'])) {
			$data['error_module_uid'] = $this->error['module_uid'];
		} else {
			$data['error_module_uid'] = '';
		}
		if (isset($this->error['current'])) {
			$data['error_current'] = $this->error['current'];
		} else {
			$data['error_current'] = '';
		}
		if (isset($this->error['padding'])) {
			$data['error_padding'] = $this->error['padding'];
		} else {
			$data['error_padding'] = '';
		}
		if (isset($this->error['prefix'])) {
			$data['error_prefix'] = $this->error['prefix'];
		} else {
			$data['error_prefix'] = '';
		}
		if (isset($this->error['suffix'])) {
			$data['error_suffix'] = $this->error['suffix'];
		} else {
			$data['error_suffix'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else if (isset($this->session->data['success_msg'])) {
			$data['success'] = $this->session->data['success_msg'];
			unset($this->session->data['success_msg']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$data['action'] = $this->url->link('setting/running_no/header_edit', 'token=' . $this->session->data['token'] . "&id=" . $headerID, true);
		
		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tran_info = $this->model_setting_running_no->getRecord($this->request->get['id']);
		}
		if (isset($this->request->post['module_uid'])) {
			$data['module_uid'] = $this->request->post['module_uid'];
		} elseif (!empty($tran_info)) {
			$data['module_uid'] = $tran_info['module_uid'];
		} else {
			$data['module_uid'] = '';
		}
		if (isset($this->request->post['current'])) {
			$data['current'] = $this->request->post['current'];
		} elseif (!empty($tran_info)) {
			$data['current'] = $tran_info['current'];
		} else {
			$data['current'] = '';
		}
		if (isset($this->request->post['padding'])) {
			$data['padding'] = $this->request->post['padding'];
		} elseif (!empty($tran_info)) {
			$data['padding'] = $tran_info['padding'];
		} else {
			$data['padding'] = '';
		}
		if (isset($this->request->post['prefix'])) {
			$data['prefix'] = $this->request->post['prefix'];
		} elseif (!empty($tran_info)) {
			$data['prefix'] = $tran_info['prefix'];
		} else {
			$data['prefix'] = '';
		}
		if (isset($this->request->post['suffix'])) {
			$data['suffix'] = $this->request->post['suffix'];
		} elseif (!empty($tran_info)) {
			$data['suffix'] = $tran_info['suffix'];
		} else {
			$data['suffix'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/running_no_content_list', $data));
	}
	
	public function header_add() {
		$this->load->language('setting/running_no');
		$this->load->model('setting/running_no');
		//echo $this->model_setting_running_no->testing();exit;
		$this->document->setTitle($this->language->get('heading_running_no'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//echo "<pre>";print_r($this->request->post);echo "</pre>";exit;
			$headerID = $this->model_setting_running_no->addRecordHeader($this->request->post);
			$this->session->data['success'] = $this->language->get('text_header_added_success');
			$this->response->redirect($this->url->link('setting/running_no/header_edit', 'id=' . $headerID . '&token=' . $this->session->data['token'], true));
		}
		
		$this->getHeaderAddForm();
	}
	
	protected function getHeaderAddForm() {
		
		$data['heading_title'] = $this->language->get('heading_running_no');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_running_no'),
			'href' => $this->url->link('setting/running_no', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_running_no_add'),
			'href' => $this->url->link('setting/running_no/header_add', 'token=' . $this->session->data['token'], true)
		);
		
		$data['save_type'] = "add";
		$data['text_header'] = $this->language->get('text_detail');
		$data['column_module_uid'] = $this->language->get('column_module_uid');
		$data['column_current'] = $this->language->get('column_current');
		$data['column_padding'] = $this->language->get('column_padding');
		$data['column_prefix'] = $this->language->get('column_prefix');
		$data['column_suffix'] = $this->language->get('column_suffix');
		$data['column_action'] = $this->language->get('column_action');
		$data['entry_module_uid'] = $this->language->get('entry_module_uid');
		$data['entry_current'] = $this->language->get('entry_current');
		$data['entry_padding'] = $this->language->get('entry_padding');
		$data['entry_prefix'] = $this->language->get('entry_prefix');
		$data['entry_suffix'] = $this->language->get('entry_suffix');
		$data['button_save'] = $this->language->get('button_save');
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['module_uid'])) {
			$data['error_module_uid'] = $this->error['module_uid'];
		} else {
			$data['error_module_uid'] = '';
		}
		if (isset($this->error['current'])) {
			$data['error_current'] = $this->error['current'];
		} else {
			$data['error_current'] = '';
		}
		if (isset($this->error['padding'])) {
			$data['error_padding'] = $this->error['padding'];
		} else {
			$data['error_padding'] = '';
		}
		if (isset($this->error['prefix'])) {
			$data['error_prefix'] = $this->error['prefix'];
		} else {
			$data['error_prefix'] = '';
		}
		if (isset($this->error['suffix'])) {
			$data['error_suffix'] = $this->error['suffix'];
		} else {
			$data['error_suffix'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else if (isset($this->session->data['success_msg'])) {
			$data['success'] = $this->session->data['success_msg'];
			unset($this->session->data['success_msg']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		
		$data['action'] = $this->url->link('setting/running_no/header_add', 'token=' . $this->session->data['token'], true);

		$data['module_uid'] = '';
		$data['current'] = '';
		$data['padding'] = '';
		$data['prefix'] = '';
		$data['suffix'] = '';
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/running_no_content_list', $data));
	}
	
	public function headerdelete() {
		$this->load->language('setting/running_no');
		$this->load->model('setting/running_no');
		//echo $this->model_setting_running_no->testing();exit;
		$this->document->setTitle($this->language->get('heading_running_no'));
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			//echo "<pre>";print_r($this->request->post['selected']);echo "</pre>";exit;
			foreach ($this->request->post['selected'] as $id) {
////				$this->model_setting_running_no->deleteRecord($id);
			}
			$this->session->data['success'] = $this->language->get('text_delete_success');
			$this->response->redirect($this->url->link('setting/running_no', 'token=' . $this->session->data['token'], true));
		}
		
		$this->getList();
	}
	
	public function detailaddedit() {
		$this->load->language('setting/running_no');
		$this->load->model('setting/running_no');
		//echo $this->model_setting_running_no->testing();exit;
		$this->document->setTitle($this->language->get('heading_running_no'));
		if (isset($this->request->get['id'])) {
			$headerID = $this->request->get['id'];
		} else {
			$headerID = 0;
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')/* && $this->validateForm()*/) {
			//echo "<pre>";print_r($this->request->post);echo "</pre>";exit;
			$this->model_setting_running_no->addEditRecordDetails($headerID, $this->request->post);
			$this->session->data['success'] = $this->language->get('text_header_added_success');
		}
		
		$this->response->redirect($this->url->link('setting/running_no/header_edit', 'id=' . $headerID . '&token=' . $this->session->data['token'], true));
	}
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'setting/running_no')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (utf8_strlen($this->request->post['current']) == 0) {
			$this->error['current'] = $this->language->get('error_current');
		} if (utf8_strlen($this->request->post['padding']) == 0) {
			$this->error['padding'] = $this->language->get('error_padding');
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'setting/running_no')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	
}