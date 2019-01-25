<?php
class ControllerMarketplaceLazadaCategory extends Controller {
	private $error = array();

	public function AjaxAPI() {
		//return json_encode("TEST"); exit;
		$this->load->model('marketplace/lazada');
		$name = $this->request->get['q'];
		$page = $this->request->get['page'];
		$filter = array(
		'name' => $name,
		'page' => $page
		);
		$lazadaTree = $this->model_marketplace_lazada->getLazadaCategories($filter);
		
		$result = array();
		$categories = array();
		if ($page==1){
			$categories[] = array(
			'id' => 0,
			'full_name' => 'Not Set / Disabled'
			);
		}
		foreach ($lazadaTree as $value){
			$categories[] = array(
			'id' => $value['lazada_id'],
			'full_name' => $value['full_path_name']
			);
		}
		
		$result['total_count'] = $this->model_marketplace_lazada->getTotalLazadaCategories($name);
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
		$this->load->language('marketplace/lazadaCategory');
		$SDKdirectory = DIR_APPLICATION;
		$SDKdirectory = rtrim($SDKdirectory, "admin/");
		$SDKdirectory .= "/cron/LazadaSDK/LazopSdk.php";
		include_once($SDKdirectory);
		$error_url = '';
		
		$url = 'https://auth.lazada.com/rest';
		$appkey = $this->config->get('lazada_app_key');
		$appSecret = $this->config->get('lazada_app_secret');
		
		$client = new LazopClient($url,$appkey,$appSecret);
		$request = new LazopRequest('/category/tree/get','GET');
		$response = $client->execute($request);
		$response = json_decode($response, true);
		$data = $response;

		$conn = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if (mysqli_connect_errno()){
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$now = new DateTime();

		$category_tree_array = array();
		$parent_id_main = 0;
		$i = 0;
		//print_r($data);
		if(!isset($data['data'])){
			$error_url .= "&error=failed";
			$this->response->redirect($this->url->link('marketplace/lazadaCategory', 'token=' . $this->session->data['token'] . $error_url, true));
		}
		if($data['data']){
			$body = $data['data'];
			foreach($body as $key=> $value){
				$leaf = $value['leaf'];
				$cat_array = array(
				'category_id' => $value['category_id'],
				'category_name' => $value['name'],
				'category_fullpath_name' => $value['name'],
				'parent_id' => $parent_id_main,
				'leaf' => !empty($leaf) ? $leaf : 0
				);
				$category_tree_array[] = $cat_array;
				$category_tree_array = $this->ifChildExist($value, $value['name'], $value['category_id'], $category_tree_array);
			}
			if (!empty($category_tree_array)){
				$count = 0;
				$emptyTable = "TRUNCATE TABLE `oc_category_tree_lazada`";
				mysqli_query($conn, $emptyTable) or die(mysqli_error($conn));
				
				$sql = "INSERT INTO `oc_category_tree_lazada` (`lazada_id`, `name`, `full_path_name`, `parent_id`, `is_leaf`,  `date_added`) 
					VALUES";
				foreach($category_tree_array as $value){
					$category_id = $value['category_id'];
					$category_name =  $conn->real_escape_string($value['category_name']);
					$category_fullpath_name = $conn->real_escape_string($value['category_fullpath_name']);
					$parent_id = $value['parent_id'];
					$leaf = $value['leaf'];
					$now = date('Y-m-d H:i:s');
					
					$sql .= "('$category_id', '$category_name', '$category_fullpath_name', '$parent_id', $leaf, '$now'),";
				
					
				$count++;
				}
				$sql = rtrim($sql, ',');
				$sql .= ";";
				$conn->set_charset("utf8");
				mysqli_query($conn, $sql) or die(mysqli_error($conn));
				echo $count . " categories inserted.";
			}
			$this->session->data['success'] = "Successfully updated Lazada category tree.";
			
			$this->response->redirect($this->url->link('marketplace/lazadaCategory', 'token=' . $this->session->data['token'], true));

		}else{
			print_r($data);
		}

	curl_close($ch);
	}
	
	public function resetCategoryTree(){
		error_reporting(E_ALL);
		ini_set("display_errors","On");
		ini_set("memory_limit","-1");
		ini_set('max_execution_time', 0);
		date_default_timezone_set('Asia/Kuala_Lumpur');
		$this->load->language('marketplace/lazadaCategory');
		$this->load->model('marketplace/lazada');

		$error_url = '';

		//require_once('../config.php');
		$conn = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if (mysqli_connect_errno()){
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$now = new DateTime();
		
		$userId = $this->config->get('lazada_user_id');
		
		if(empty($userId)){
			$this->error['warning'] = $this->language->get('error_user_id');
			$error_url .= "&error=error_user_id";
			$this->response->redirect($this->url->link('marketplace/lazadaCategory', 'token=' . $this->session->data['token'] . $error_url, true));
			exit;
		}
		
		$apikey = $this->config->get('lazada_API_key');
		if(empty($apikey)){
			$this->error['warning'] = $this->language->get('error_api_key');
			$error_url .= "&error=error_api_key";
			$this->response->redirect($this->url->link('marketplace/lazadaCategory', 'token=' . $this->session->data['token'] . $error_url, true));
			exit;
		}

		$success = $this->model_marketplace_lazada->resetCategoryTree();
		if($success == 1){

			$this->session->data['success'] = "Successfully unmatched all categories.";
			
			$this->response->redirect($this->url->link('marketplace/lazadaCategory', 'token=' . $this->session->data['token'], true));

		}else{
			$this->response->redirect($this->url->link('marketplace/lazadaCategory', 'token=' . $this->session->data['token'], true));
		}

	}
	
	public function index() {
		$this->load->language('marketplace/lazadaCategory');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('marketplace/lazada');
		
		$this->getList();
	}
	
	public function save() {
		//print_r($_POST); 
		
		$this->load->language('marketplace/lazadaCategory');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('marketplace/lazada');
		
		$categories = array();
		$prefix = 'lazada_select_';
		
		foreach ($_POST as $key => $value){
			$id = '';
			if (substr($key, 0, strlen($prefix)) == $prefix) {
				$id = substr($key, strlen($prefix));
			}

			$categories[] = array(
			'category_id' => $id,
			'lazada_id' => $value
			);
		}
		
		if(!empty($categories)){

			$this->model_marketplace_lazada->linkCategoryToLazada($categories);
			
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

			$this->response->redirect($this->url->link('marketplace/lazadaCategory', 'token=' . $this->session->data['token'] . $url, true));

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
			'href' => $this->url->link('marketplace/lazadaCategory', 'token=' . $this->session->data['token'] . $url, true)
		);
		
		$data['save'] = $this->url->link('marketplace/lazadaCategory/save', 'token=' . $this->session->data['token'] . $url, true);
		$data['sync_category'] = $this->url->link('marketplace/lazadaCategory/syncCategoryTree', 'token=' . $this->session->data['token'] . $url, true);
		$data['reset_category'] = $this->url->link('marketplace/lazadaCategory/resetCategoryTree', 'token=' . $this->session->data['token'] . $url, true);
		
		$data['categories'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$category_total = $this->model_marketplace_lazada->getTotalCategories();

		$results = $this->model_marketplace_lazada->getCategories($filter_data);

		foreach ($results as $result) {
			$data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'lazada_id' => $result['lazada_id'],
				'lazada_full_path_name' => $this->model_marketplace_lazada->getLazadaFullPathNameById($result['lazada_id'])
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_confirm_delete'] = $this->language->get('text_confirm_delete');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_lazada_category'] = $this->language->get('column_lazada_category');

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

		$data['sort_name'] = $this->url->link('marketplace/lazadaCategory', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
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
		$pagination->url = $this->url->link('marketplace/lazadaCategory', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/lazadaCategory', $data));
	}
	
	function ifChildExist($array, $name, $parentId = 0, $resultArray){
		//Global $category_tree_array;
		$parentName = '';
		$category = $resultArray;
		//$parentName .= $name;
		if(array_key_exists('children', $array)){
			$child = $array['children'];
			$parentName .= $name;
			$parent_Id = $parentId;
			foreach($child as $k => $v){
				$currentName = $parentName . " > " .$v['name'];
				$currentId = $v['category_id'];
				$leaf = $v['leaf'];
				$cat_array = array(
				'category_id' => $currentId,
				'category_name' => $v['name'],
				'category_fullpath_name' => $currentName,
				'parent_id' => $parent_Id,
				'leaf' => !empty($leaf) ? $leaf : 0
				);
				$category[] = $cat_array;
				//echo $currentId . ", Parent Id = " . $parent_Id . " ; ";
				//echo $currentName . "<br>";

				$category = $this->ifChildExist($v, $currentName, $currentId, $category);
			}		
		}
		return $category;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'marketplace/lazadaCategory')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'marketplace/lazada')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['lazada_user_id']) {
			$this->error['user_id'] = $this->language->get('error_user_id');
		}
		
		if (!$this->request->post['lazada_API_key']) {
			$this->error['API_key'] = $this->language->get('error_API_key');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}

