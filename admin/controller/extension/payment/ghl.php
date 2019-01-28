<?php 
class ControllerExtensionPaymentGHL extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('extension/payment/ghl');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ghl', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token']. "&type=payment", 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_successful'] = $this->language->get('text_successful');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');

		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_callback'] = $this->language->get('entry_callback');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_total'] = $this->language->get('entry_total');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_password'] = $this->language->get('help_password');
		$data['help_callback'] = $this->language->get('help_callback');
		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

 		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/ghl', 'token=' . $this->session->data['token'], 'SSL'),      		
		);

		$data['action'] = $this->url->link('extension/payment/ghl', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['ghl_merchant'])) {
			$data['ghl_merchant'] = $this->request->post['ghl_merchant'];
		} else {
			$data['ghl_merchant'] = $this->config->get('ghl_merchant');
		}

		if (isset($this->request->post['ghl_password'])) {
			$data['ghl_password'] = $this->request->post['ghl_password'];
		} else {
			$data['ghl_password'] = $this->config->get('ghl_password');
		}
		
		$data['callback'] = HTTP_CATALOG . 'index.php?route=payment/ghl/callback';

		if (isset($this->request->post['ghl_test'])) {
			$data['ghl_test'] = $this->request->post['ghl_test'];
		} else {
			$data['ghl_test'] = $this->config->get('ghl_test');
		}

		if (isset($this->request->post['ghl_total'])) {
			$data['ghl_total'] = $this->request->post['ghl_total'];
		} else {
			$data['ghl_total'] = $this->config->get('ghl_total'); 
		} 

		if (isset($this->request->post['ghl_order_status_id'])) {
			$data['ghl_order_status_id'] = $this->request->post['ghl_order_status_id'];
		} else {
			$data['ghl_order_status_id'] = $this->config->get('ghl_order_status_id'); 
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['ghl_geo_zone_id'])) {
			$data['ghl_geo_zone_id'] = $this->request->post['ghl_geo_zone_id'];
		} else {
			$data['ghl_geo_zone_id'] = $this->config->get('ghl_geo_zone_id'); 
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['ghl_status'])) {
			$data['ghl_status'] = $this->request->post['ghl_status'];
		} else {
			$data['ghl_status'] = $this->config->get('ghl_status');
		}

		if (isset($this->request->post['ghl_sort_order'])) {
			$data['ghl_sort_order'] = $this->request->post['ghl_sort_order'];
		} else {
			$data['ghl_sort_order'] = $this->config->get('ghl_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/ghl.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/ghl')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['ghl_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		
		if (!$this->request->post['ghl_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>