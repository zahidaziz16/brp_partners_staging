<?php
class ControllerMarketplaceShopee extends Controller {
	private $error = array();
	
		public function AjaxAPI() {

		$this->load->model('marketplace/shopee');
		$name = $this->request->get['q'];
		$page = $this->request->get['page'];
		$filter = array(
		'name' => $name,
		'page' => $page
		);
		$shopeeTree = $this->model_marketplace_shopee->getCustomerByName($filter);
		
		$result = array();
		$customers = array();
		if ($page==1){
			$customers[] = array(
			'id' => 0,
			'full_name' => 'Not Set / Disabled'
			);
		}
		foreach ($shopeeTree as $value){
			$customers[] = array(
			'id' => $value['id'],
			'full_name' => $value['full_name']
			);
		}
		
		$result['total_count'] = $this->model_marketplace_shopee->getTotalCustomerByName($name);
		$result['items'] = $customers;
		//echo substr(json_encode($result), 1, -1);
		echo json_encode($result);
		
	}

	public function index() {
		$this->load->language('marketplace/shopee');
		
		$this->load->model('marketplace/shopee');

		$this->document->setTitle($this->language->get('heading_title'));
		//echo "Welcome to shopee!";
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shopee', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/shopee', 'token=' . $this->session->data['token'], 'SSL'));
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
		$data['entry_add_product_cronjob'] = $this->language->get('entry_add_product_cronjob');

		// Entries for Product Sync Options
		$data['entry_add_new_products'] = $this->language->get('entry_add_new_products');
		$data['entry_product_price_stock_level'] = $this->language->get('entry_product_price_stock_level');
		$data['entry_stock_level_only'] = $this->language->get('entry_stock_level_only');
		
		$data['entry_user_id'] = $this->language->get('entry_user_id');
		$data['entry_shop_id'] = $this->language->get('entry_shop_id');
		$data['entry_API_key'] = $this->language->get('entry_API_key');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_last_cronjob_date_order'] = $this->language->get('entry_last_cronjob_date_order');
		$data['entry_last_cronjob_date_product'] = $this->language->get('entry_last_cronjob_date_product');
		$data['entry_shopee_dummy_customer'] = $this->language->get('entry_shopee_dummy_customer');
		$data['entry_shopee_price_markup_percentage'] = $this->language->get('entry_shopee_price_markup_percentage');
		$data['entry_shopee_price_markup_flat'] = $this->language->get('entry_shopee_price_markup_flat');
		$data['entry_shopee_price_threshold'] = $this->language->get('entry_shopee_price_threshold');
		$data['entry_shipping_shopee'] = $this->language->get('entry_shipping_shopee');
		
		$data['help_shopee_dummy_customer'] = $this->language->get('help_shopee_dummy_customer');
		$data['help_API_key'] = $this->language->get('help_API_key');
		$data['help_shop_id'] = $this->language->get('help_shop_id');
		$data['help_user_id'] = $this->language->get('help_user_id');
		$data['help_shipping_shopee'] = $this->language->get('help_shipping_shopee');
		$data['help_shopee_price_markup_percentage'] = $this->language->get('help_shopee_price_markup_percentage');
		$data['help_shopee_price_markup_flat'] = $this->language->get('help_shopee_price_markup_flat');
		$data['help_shopee_price_threshold'] = $this->language->get('help_shopee_price_threshold');

		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
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

 		if (isset($this->error['partner_id'])) {
			$data['error_partner_id'] = $this->error['partner_id'];
		} else {
			$data['error_partner_id'] = '';
		}
		
		if (isset($this->error['shop_id'])) {
			$data['error_shop_id'] = $this->error['shop_id'];
		} else {
			$data['error_shop_id'] = '';
		}

 		if (isset($this->error['API_key'])) {
			$data['error_API_key'] = $this->error['API_key'];
		} else {
			$data['error_API_key'] = '';
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shopee'),
			'href' => $this->url->link('marketplace/shopee', 'token=' . $this->session->data['token'], 'SSL'),
		);
		
		$data['action'] = $this->url->link('marketplace/shopee', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('marketplace/shopee', 'token=' . $this->session->data['token'], 'SSL');

        //$data['log'] = $this->url->link('marketplace/shopee?log=true', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['shopee_partner_id'])) {
			$data['shopee_partner_id'] = $this->request->post['shopee_partner_id'];
		} else {
			$data['shopee_partner_id'] = $this->config->get('shopee_partner_id');
		}
		
		if (isset($this->request->post['shopee_shipping_fee'])) {
			$data['shopee_shipping_fee'] = $this->request->post['shopee_shipping_fee'];
		} else {
			$data['shopee_shipping_fee'] = $this->config->get('shopee_shipping_fee');
		}
		
		if (isset($this->request->post['shopee_shop_id'])) {
			$data['shopee_shop_id'] = $this->request->post['shopee_shop_id'];
		} else {
			$data['shopee_shop_id'] = $this->config->get('shopee_shop_id');
		}

		if (isset($this->request->post['shopee_API_key'])) {
			$data['shopee_API_key'] = $this->request->post['shopee_API_key'];
		} else {
			$data['shopee_API_key'] = $this->config->get('shopee_API_key');
		}
		
		if (isset($this->request->post['shopee_enable'])) {
			$data['shopee_enable'] = $this->request->post['shopee_enable'];
		} else {
			$data['shopee_enable'] = $this->config->get('shopee_enable');
		}
		
		// updateCronjob enable
		if (isset($this->request->post['shopee_add_product_enable'])) {
			$data['shopee_add_product_enable'] = $this->request->post['shopee_add_product_enable'];
		} else {
			$data['shopee_add_product_enable'] = $this->config->get('shopee_add_product_enable');
			//var_dump( $data['shopee_add_product_enable'] );
		}
		


		if (isset($this->request->post['shopee_dummy_customer_id'])) {
			$data['shopee_dummy_customer_id'] = $this->request->post['shopee_dummy_customer_id'];
		} else {
			$data['shopee_dummy_customer_id'] = $this->config->get('shopee_dummy_customer_id');
		}
		
		if($data['shopee_dummy_customer_id'] == '0'){
			$data['shopee_dummy_customer_name'] = 'Not Set / Disabled';
		} else {
			$data['shopee_dummy_customer_name'] = $this->model_marketplace_shopee->getCustomerNameById($data['shopee_dummy_customer_id']);
		}
		
		$last_cronjob_date_order = $this->config->get('shopee_last_cronjob_date_order');
		$data['last_cronjob_date_order_datetime'] = $last_cronjob_date_order;
		
		if ($last_cronjob_date_order == '0000-00-00 00:00:00'){
		$data['last_cronjob_date_order'] = "-";
		} else {
		$last_cronjob_date_order = new DateTime($last_cronjob_date_order);
		$data['last_cronjob_date_order'] = date_format($last_cronjob_date_order, 'g:ia jS F Y \(l\)');
		}
		
		$last_cronjob_date_product = $this->config->get('shopee_last_cronjob_date_product');
		$data['last_cronjob_date_product_datetime'] = $last_cronjob_date_product;
		
		if ($last_cronjob_date_product == '0000-00-00 00:00:00'){
		$data['last_cronjob_date_product'] = "-";
		} else {
		$last_cronjob_date_product = new DateTime($last_cronjob_date_product);
		$data['last_cronjob_date_product'] = date_format($last_cronjob_date_product, 'g:ia jS F Y \(l\)');
		}

		if (isset($this->request->post['shopee_price_markup_percentage'])) {
			$data['shopee_price_markup_percentage'] = $this->request->post['shopee_price_markup_percentage'];
		} else {
			$data['shopee_price_markup_percentage'] = $this->config->get('shopee_price_markup_percentage');
		}

		if (isset($this->request->post['shopee_price_markup_flat'])) {
			$data['shopee_price_markup_flat'] = $this->request->post['shopee_price_markup_flat'];
		} else {
			$data['shopee_price_markup_flat'] = $this->config->get('shopee_price_markup_flat');
		}

		if (isset($this->request->post['shopee_price_threshold'])) {
			$data['shopee_price_threshold'] = $this->request->post['shopee_price_threshold'];
		} else {
			$data['shopee_price_threshold'] = $this->config->get('shopee_price_threshold');
		}		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/shopee.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'marketplace/shopee')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['shopee_partner_id']) {
			$this->error['partner_id'] = $this->language->get('error_user_id');
		}
		
		if (!$this->request->post['shopee_shop_id']) {
			$this->error['shop_id'] = $this->language->get('error_shop_id');
		}
		
		if (!$this->request->post['shopee_API_key']) {
			$this->error['API_key'] = $this->language->get('error_API_key');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}