<?php
/**
 * @package Payment Gateway
 * @author Upay Technical Team
 * @version 1.0
 */

class ControllerExtensionPaymentUpay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment//upay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('upay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=payment', 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_mid'] = $this->language->get('entry_mid');
		$data['entry_vkey'] = $this->language->get('entry_vkey');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
                $data['upay_envornment'] = $this->language->get('upay_envornment');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_vkey'] = $this->language->get('help_vkey');
                $data['help_mid'] = $this->language->get('help_mid');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['mid'])) {
			$data['error_mid'] = $this->error['mid'];
		} else {
			$data['error_mid'] = '';
		}

		if (isset($this->error['vkey'])) {
			$data['error_vkey'] = $this->error['vkey'];
		} else {
			$data['error_vkey'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/upay', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('extension/payment/upay', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');
		if (isset($this->request->post['upay_env'])) {
			$data['upay_vkey'] = $this->request->post['upay_env'];
		} else {
			$data['upay_env'] = $this->config->get('upay_env');
		}
		if (isset($this->request->post['upay_mid'])) {
			$data['upay_mid'] = $this->request->post['upay_mid'];
		} else {
			$data['upay_mid'] = $this->config->get('upay_mid');
		}

		if (isset($this->request->post['upay_vkey'])) {
			$data['upay_vkey'] = $this->request->post['upay_vkey'];
		} else {
			$data['upay_vkey'] = $this->config->get('upay_vkey');
		}

		if (isset($this->request->post['upay_order_status_id'])) {
			$data['upay_order_status_id'] = $this->request->post['upay_order_status_id'];
		} else {
			$data['upay_order_status_id'] = $this->config->get('upay_order_status_id');
		}
		
		if (isset($this->request->post['upay_completed_status_id'])) {
			$data['upay_completed_status_id'] = $this->request->post['upay_completed_status_id'];
		} else {
			$data['upay_completed_status_id'] = $this->config->get('upay_completed_status_id');
		}
		
		if (isset($this->request->post['upay_pending_status_id'])) {
			$data['upay_pending_status_id'] = $this->request->post['upay_pending_status_id'];
		} else {
			$data['upay_pending_status_id'] = $this->config->get('upay_pending_status_id');
		}
		
		if (isset($this->request->post['upay_failed_status_id'])) {
			$data['upay_failed_status_id'] = $this->request->post['upay_failed_status_id'];
		} else {
			$data['upay_failed_status_id'] = $this->config->get('upay_failed_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['upay_geo_zone_id'])) {
			$data['upay_geo_zone_id'] = $this->request->post['upay_geo_zone_id'];
		} else {
			$data['upay_geo_zone_id'] = $this->config->get('upay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['upay_status'])) {
			$data['upay_status'] = $this->request->post['upay_status'];
		} else {
			$data['upay_status'] = $this->config->get('upay_status');
		}

		if (isset($this->request->post['upay_sort_order'])) {
			$data['upay_sort_order'] = $this->request->post['upay_sort_order'];
		} else {
			$data['upay_sort_order'] = $this->config->get('upay_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/upay.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/upay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['upay_mid']) {
			$this->error['mid'] = $this->language->get('error_mid');
		}

		if (!$this->request->post['upay_vkey']) {
			$this->error['vkey'] = $this->language->get('error_vkey');
		}

		return !$this->error;
	}
}