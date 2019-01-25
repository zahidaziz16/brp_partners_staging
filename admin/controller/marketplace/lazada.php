<?php
class ControllerMarketplaceLazada extends Controller {
	private $error = array();
	
		public function AjaxAPI() {

		$this->load->model('marketplace/lazada');
		$name = $this->request->get['q'];
		$page = $this->request->get['page'];
		$filter = array(
		'name' => $name,
		'page' => $page
		);
		$lazadaTree = $this->model_marketplace_lazada->getCustomerByName($filter);
		
		$result = array();
		$customers = array();
		if ($page==1){
			$customers[] = array(
			'id' => 0,
			'full_name' => 'Not Set / Disabled'
			);
		}
		foreach ($lazadaTree as $value){
			$customers[] = array(
			'id' => $value['id'],
			'full_name' => $value['full_name']
			);
		}
		
		$result['total_count'] = $this->model_marketplace_lazada->getTotalCustomerByName($name);
		$result['items'] = $customers;
		//echo substr(json_encode($result), 1, -1);
		echo json_encode($result);
		
	}

	public function index() {
		$this->load->language('marketplace/lazada');
		
		$this->load->model('marketplace/lazada');

		$this->document->setTitle($this->language->get('heading_title'));
		//echo "Welcome to lazada!";
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('lazada', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/lazada', 'token=' . $this->session->data['token'], 'SSL'));
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

		// Label, Product Sync Options
		$data['entry_product_cronjob'] = $this->language->get('label_product_cronjob');

		// Entries for Product Sync Options
		$data['entry_add_new_products'] = $this->language->get('entry_add_new_products');
		$data['entry_product_price_stock_level'] = $this->language->get('entry_product_price_stock_level');
		$data['entry_stock_level_only'] = $this->language->get('entry_stock_level_only');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_access_token'] = $this->language->get('entry_access_token');
		$data['entry_app_key'] = $this->language->get('entry_app_key');
		$data['entry_app_secret'] = $this->language->get('entry_app_secret');
		$data['entry_callback_url'] = $this->language->get('entry_callback_url');
		$data['entry_last_cronjob_date_order'] = $this->language->get('entry_last_cronjob_date_order');
		$data['entry_last_cronjob_date_product'] = $this->language->get('entry_last_cronjob_date_product');
		$data['entry_lazada_dummy_customer'] = $this->language->get('entry_lazada_dummy_customer');
		$data['entry_lazada_price_markup_percentage'] = $this->language->get('entry_lazada_price_markup_percentage');
		$data['entry_lazada_price_markup_flat'] = $this->language->get('entry_lazada_price_markup_flat');
		$data['entry_lazada_price_threshold'] = $this->language->get('entry_lazada_price_threshold');
		
		$data['help_lazada_dummy_customer'] = $this->language->get('help_lazada_dummy_customer');
		$data['help_app_key'] = $this->language->get('help_app_key');
		$data['help_app_secret'] = $this->language->get('help_app_secret');
		$data['help_callback_url'] = $this->language->get('help_callback_url');
		$data['help_lazada_price_markup_percentage'] = $this->language->get('help_lazada_price_markup_percentage');
		$data['help_lazada_price_markup_flat'] = $this->language->get('help_lazada_price_markup_flat');
		$data['help_lazada_price_threshold'] = $this->language->get('help_lazada_price_threshold');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_get_access_token'] = $this->language->get('button_get_access_token');
		
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
		
		if (isset($this->error['app_key'])) {
			$data['error_app_key'] = $this->error['app_key'];
		} else {
			$data['error_app_key'] = '';
		}
		if (isset($this->error['app_secret'])) {
			$data['error_app_secret'] = $this->error['app_secret'];
		} else {
			$data['error_app_secret'] = '';
		}
		if (isset($this->error['callback_url'])) {
			$data['error_callback_url'] = $this->error['callback_url'];
		} else {
			$data['error_callback_url'] = '';
		}
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_lazada'),
			'href' => $this->url->link('marketplace/lazada', 'token=' . $this->session->data['token'], 'SSL'),
		);
		
		$data['action'] = $this->url->link('marketplace/lazada', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('marketplace/lazada', 'token=' . $this->session->data['token'], 'SSL');
	
		if (isset($this->request->post['lazada_app_key'])) {
			$data['lazada_app_key'] = $this->request->post['lazada_app_key'];
		} else {
			$data['lazada_app_key'] = $this->config->get('lazada_app_key');
		}
		
		if (isset($this->request->post['lazada_app_secret'])) {
			$data['lazada_app_secret'] = $this->request->post['lazada_app_secret'];
		} else {
			$data['lazada_app_secret'] = $this->config->get('lazada_app_secret');
		}
		
		if (isset($this->request->post['lazada_callback_url'])) {
			$data['lazada_callback_url'] = $this->request->post['lazada_callback_url'];
		} else {
			$data['lazada_callback_url'] = $this->config->get('lazada_callback_url');
		}
		
		if (isset($this->request->post['lazada_enable'])) {
			$data['lazada_enable'] = $this->request->post['lazada_enable'];
		} else {
			$data['lazada_enable'] = $this->config->get('lazada_enable');
		}

		// Lazada Update Product Sync Option / Product Sync Options
		if (isset($this->request->post['lazada_product_enable'])){
			$data['lazada_product_enable'] = $this->request->post['lazada_product_enable'];
		} else {
			$data['lazada_product_enable'] = $this->config->get('lazada_product_enable');
		}
		
		if (isset($this->request->post['lazada_dummy_customer_id'])) {
			$data['lazada_dummy_customer_id'] = $this->request->post['lazada_dummy_customer_id'];
		} else {
			$data['lazada_dummy_customer_id'] = $this->config->get('lazada_dummy_customer_id');
		}
		
		if($data['lazada_dummy_customer_id'] == '0'){
			$data['lazada_dummy_customer_name'] = 'Not Set / Disabled';
		} else {
			$data['lazada_dummy_customer_name'] = $this->model_marketplace_lazada->getCustomerNameById($data['lazada_dummy_customer_id']);
		}
		
		$lazada_access_token = $this->config->get('lazada_openapi_access_token');
		if ($lazada_access_token){
			$data['lazada_access_token'] = $lazada_access_token;
		}
		
		$last_cronjob_date_order = $this->config->get('lazada_last_cronjob_date_order');
		$data['last_cronjob_date_order_datetime'] = $last_cronjob_date_order;
		
		if ($last_cronjob_date_order == '0000-00-00 00:00:00'){
		$data['last_cronjob_date_order'] = "-";
		} else {
		$last_cronjob_date_order = new DateTime($last_cronjob_date_order);
		$data['last_cronjob_date_order'] = date_format($last_cronjob_date_order, 'g:ia jS F Y \(l\)');
		}
		
		$last_cronjob_date_product = $this->config->get('lazada_last_cronjob_date_product');
		$data['last_cronjob_date_product_datetime'] = $last_cronjob_date_product;
		
		if ($last_cronjob_date_product == '0000-00-00 00:00:00'){
		$data['last_cronjob_date_product'] = "-";
		} else {
		$last_cronjob_date_product = new DateTime($last_cronjob_date_product);
		$data['last_cronjob_date_product'] = date_format($last_cronjob_date_product, 'g:ia jS F Y \(l\)');
		}

		if (isset($this->request->post['lazada_price_markup_percentage'])) {
			$data['lazada_price_markup_percentage'] = $this->request->post['lazada_price_markup_percentage'];
		} else {
			$data['lazada_price_markup_percentage'] = $this->config->get('lazada_price_markup_percentage');
		}

		if (isset($this->request->post['lazada_price_markup_flat'])) {
			$data['lazada_price_markup_flat'] = $this->request->post['lazada_price_markup_flat'];
		} else {
			$data['lazada_price_markup_flat'] = $this->config->get('lazada_price_markup_flat');
		}

		if (isset($this->request->post['lazada_price_threshold'])) {
			$data['lazada_price_threshold'] = $this->request->post['lazada_price_threshold'];
		} else {
			$data['lazada_price_threshold'] = $this->config->get('lazada_price_threshold');
		}	

		$data['access_token_url'] = '';
		
		$lazada_auth_url = 'https://auth.lazada.com/oauth/authorize?response_type=code&force_auth=true';
		
		$callback_url = $data['lazada_callback_url'];
		$client_id = $data['lazada_app_key'];
		
		$lazada_auth_url .= '&redirect_uri='.$callback_url;
		$lazada_auth_url .= '&country=my';
		$lazada_auth_url .= '&client_id='.$client_id;
		$lazada_auth_url .= '&state='.$this->session->data['token']."MMMM".$this->config->get('config_unique_brp_partner_id');
		if(!empty($callback_url) && !empty($client_id)){
			$data['access_token_url'] = $lazada_auth_url;
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/lazada.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'marketplace/lazada')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['lazada_app_key']) {
			$this->error['app_key'] = $this->language->get('error_app_key');
		}
		
		if (!$this->request->post['lazada_app_secret']) {
			$this->error['app_secret'] = $this->language->get('error_app_secret');
		}
		
		if (!$this->request->post['lazada_callback_url']) {
			$this->error['callback_url'] = $this->language->get('error_callback_url');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}