<?php
class ControllerMarketplaceShopeeCategory extends Controller {
	private $error = array();

	public function AjaxAPI() {
		//return json_encode("TEST"); exit;
		$this->load->model('marketplace/shopee');
		$name = $this->request->get['q'];
		$page = $this->request->get['page'];
		$filter = array(
		'name' => $name,
		'page' => $page
		);
		$shopeeTree = $this->model_marketplace_shopee->getShopeeCategories($filter);
		
		$result = array();
		$categories = array();
		if ($page==1){
			$categories[] = array(
			'id' => 0,
			'full_name' => 'Not Set / Disabled'
			);
		}
		foreach ($shopeeTree as $value){
			$categories[] = array(
			'id' => $value['shopee_id'],
			'full_name' => $value['full_path_name']
			);
		}
		
		$result['total_count'] = $this->model_marketplace_shopee->getTotalShopeeCategories($name);
		$result['items'] = $categories;
		//echo substr(json_encode($result), 1, -1);
		echo json_encode($result);
		
	}
	
	public function syncCategoryTree(){
		error_reporting(E_ALL);
		ini_set("display_errors","On");
		ini_set("memory_limit","-1");
		ini_set('max_execution_time', 0);
		date_default_timezone_set('Asia/Kuala_Lumpur');
		$this->load->language('marketplace/shopeeCategory');
		
		$error_url = '';

		$conn = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if (mysqli_connect_errno()){
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		$date = new DateTime();	
		
		$shopee_partner_id = (integer)$this->config->get('shopee_partner_id');
		//$shopee_partner_id = 26082;
		$shopee_shop_id = (integer)$this->config->get('shopee_shop_id');
		//$shopee_shop_id = 61620520;
		$shopee_API_key = $this->config->get('shopee_API_key');
		//$shopee_api_key = 'fb57c50b432fefdfdeddc77a16df6888e722acb2329f7bbd49bae7276d22a42b';

		if(empty($shopee_partner_id)){
			$this->error['warning'] = $this->language->get('error_user_id');
			$error_url .= "&error=error_user_id";
			$this->response->redirect($this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'] . $error_url, true));
			exit;
		}

		if(empty($shopee_API_key)){
			$this->error['warning'] = $this->language->get('error_api_key');
			$error_url .= "&error=error_api_key";
			$this->response->redirect($this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'] . $error_url, true));
			exit;
		}
		
		$url = "https://partner.shopeemobile.com/api/v1/item/categories/get";
		$parameters = array(
				'shopid' => $shopee_shop_id,
				'partner_id' => $shopee_partner_id,
				'timestamp' => $date->getTimestamp()
			);
		$data_string = json_encode($parameters);
		$concatenated = $url . "|" . $data_string;
		$api_key = $shopee_API_key;
		$signature = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, FALSE));
		$ch = curl_init();
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: '. $signature;

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		$response = curl_exec($ch);
		$data = json_decode($response, true);
		//print_r($data);
		
		if(!isset($data['categories'])){
			$error_url .= "&error=failed";
			$this->response->redirect($this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'] . $error_url, true));
		}
		
		if($data['categories']){
			$body = $data['categories'];
			$categories = array();
			//print_r($body);
			foreach ($body as $value){
				if(!isset($categories[$value['category_id']])){
				$categories[$value['category_id']] = $value;
				}
			}
			//print_r($categories); exit;
			foreach($categories as $key=> $value){
				$cat_array = array(
				'category_id' => $value['category_id'],
				'category_name' => $value['category_name'],
				'category_fullpath_name' => $this->getFullPathName($value['category_name'], $value['parent_id'], $value['category_id'], $categories),
				'parent_id' => $value['parent_id'], 
				'has_children' => $value['has_children'] ? $value['has_children'] : 0
				);
				$category_tree_array[] = $cat_array;
			}
			//print("<pre>".print_r($category_tree_array,true)."</pre>"); exit;
			if (!empty($category_tree_array)){
				$count = 0;
				$emptyTable = "TRUNCATE TABLE `oc_category_tree_shopee`";
				mysqli_query($conn, $emptyTable) or die(mysqli_error($conn));
				
				$sql = "INSERT INTO `oc_category_tree_shopee` (`shopee_id`, `name`, `full_path_name`, `parent_id`, `has_children`,  `date_added`) 
					VALUES";
				foreach($category_tree_array as $value){
					$category_id = $value['category_id'];
					$category_name =  $conn->real_escape_string($value['category_name']);
					$category_fullpath_name = $conn->real_escape_string($value['category_fullpath_name']);
					$parent_id = $value['parent_id'];
					$has_children = $value['has_children'];
					$now = date('Y-m-d H:i:s');
					
					$sql .= "('$category_id', '$category_name', '$category_fullpath_name', '$parent_id', '$has_children', '$now'),";
									
					
				$count++;
				}
				$sql = rtrim($sql, ',');
				$sql .= ";";
				//echo $sql; exit;
				$conn->set_charset("utf8");
				mysqli_query($conn, $sql) or die(mysqli_error($conn));
				echo $count . " categories inserted.";
			}
			$this->session->data['success'] = "Successfully updated Shopee category tree.";
			
			$this->response->redirect($this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'], true));

		}

	curl_close($ch);
	}
	
	public function resetCategoryTree(){
		error_reporting(E_ALL);
		ini_set("display_errors","On");
		ini_set("memory_limit","-1");
		ini_set('max_execution_time', 0);
		date_default_timezone_set('Asia/Kuala_Lumpur');
		$this->load->language('marketplace/shopeeCategory');
		$this->load->model('marketplace/shopee');

		$error_url = '';
		
		//require_once('../config.php');
		$conn = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if (mysqli_connect_errno()){
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$now = new DateTime();
		
		$userId = $this->config->get('shopee_partner_id');
		
		if(empty($userId)){
			$this->error['warning'] = $this->language->get('error_user_id');
			$error_url .= "&error=error_user_id";
			$this->response->redirect($this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'] . $error_url, true));
			exit;
		}
		
		$apikey = $this->config->get('shopee_API_key');
		if(empty($apikey)){
			$this->error['warning'] = $this->language->get('error_api_key');
			$error_url .= "&error=error_api_key";
			$this->response->redirect($this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'] . $error_url, true));
			exit;
		}
		
		//If no modify permission
		if(!$this->validateDelete()){
			$this->error['warning'] = $this->language->get('error_permission');
			$error_url .= "&error=error_permission";
			$this->response->redirect($this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'] . $error_url, true));
		}
		//exit;
		$success = $this->model_marketplace_shopee->resetCategoryTree();
		if($success == 1){

			$this->session->data['success'] = "Successfully unmatched all categories.";
			
			$this->response->redirect($this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'], true));

		}else{
			$this->response->redirect($this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'], true));
		}

	}
	
	public function index() {
		$this->load->language('marketplace/shopeeCategory');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('marketplace/shopee');
		
		$this->getList();
	}
	
	public function save() {
		//print_r($_POST); 
		
		$this->load->language('marketplace/shopeeCategory');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('marketplace/shopee');
		
		$categories = array();
		$prefix = 'shopee_select_';
		
		foreach ($_POST as $key => $value){
			$id = '';
			if (substr($key, 0, strlen($prefix)) == $prefix) {
				$id = substr($key, strlen($prefix));
			}

			$categories[] = array(
			'category_id' => $id,
			'shopee_id' => $value
			);
		}
		
		if(!empty($categories)){

			$this->model_marketplace_shopee->linkCategoryToShopee($categories);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'] . $url, true));

		}
		//print_r($categories); exit;


		$this->getList();
	}

	protected function getList(){
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		//Category tree sync error check
		$error = "";
		
		if (isset($this->request->get['error'])) {
			$error = $this->request->get['error'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'] . $url, true)
		);
		
		$data['save'] = $this->url->link('marketplace/shopeeCategory/save', 'token=' . $this->session->data['token'] . $url, true);
		$data['sync_category'] = $this->url->link('marketplace/shopeeCategory/syncCategoryTree', 'token=' . $this->session->data['token'] . $url, true);
		$data['reset_category'] = $this->url->link('marketplace/shopeeCategory/resetCategoryTree', 'token=' . $this->session->data['token'] . $url, true);
		
		$data['categories'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$category_total = $this->model_marketplace_shopee->getTotalCategories();

		$results = $this->model_marketplace_shopee->getCategories($filter_data);

		foreach ($results as $result) {
			$data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'shopee_id' => $result['shopee_id'],
				'shopee_full_path_name' => $this->model_marketplace_shopee->getShopeeFullPathNameById($result['shopee_id'])
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_confirm_delete'] = $this->language->get('text_confirm_delete');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_shopee_category'] = $this->language->get('column_shopee_category');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_sync_category'] = $this->language->get('button_sync_category');
		$data['button_reset_category'] = $this->language->get('button_reset_category');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if ($error == "error_user_id"){
			$data['error_warning'] = $this->language->get('error_user_id');
		}else if($error == "error_api_key"){
			$data['error_warning'] = $this->language->get('error_api_key');
		}else if($error == "failed"){
			$data['error_warning'] = $this->language->get('error_failed');
		}else if($error == "error_permission"){
			$data['error_warning'] = $this->language->get('error_permission');
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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		//$data['sort_sort_order'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('marketplace/shopeeCategory', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/shopeeCategory', $data));
	}
	
	function getFullPathName($name, $parent_id = 0, $current_id, $arr_categories){
		$current_name = $name;
		if($parent_id > 0){
			$parent_name = $arr_categories[$parent_id]['category_name'];
			$parent_id = $arr_categories[$parent_id]['parent_id'];
			$current_name = $parent_name . " > " . $current_name;
			if ($parent_id > 0){
				$current_name = $this->getFullPathName($current_name, $parent_id, $current_id, $arr_categories);
			}
		} 
		return $current_name;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'marketplace/shopeeCategory')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'marketplace/shopee')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['shopee_partner_id']) {
			$this->error['user_id'] = $this->language->get('error_user_id');
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

