<?php
class ControllerProductProduct extends Controller {
	private $error = array();
	
	public function ajaxAPI() {
				
		$json = array();
		$apitype = "";
		if (isset($this->request->post['apitype'])) {
			$apitype = $this->request->post['apitype'];
		} else if (isset($this->request->get['apitype'])) {
			$apitype = $this->request->get['apitype'];
		}
		
		if($apitype=="stock_levels") {
			
			$using_warehouse = "";
			$product_id = "";
			$data_source = "";
			$matching_code = "";
			if (isset($this->request->post['using_warehouse'])) {
				$using_warehouse = $this->request->post['using_warehouse'];
			} else if (isset($this->request->get['using_warehouse'])) {
				$using_warehouse = $this->request->get['using_warehouse'];
			} if (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
			} else if (isset($this->request->get['product_id'])) {
				$product_id = $this->request->get['product_id'];
			} if (isset($this->request->post['data_source'])) {
				$data_source = $this->request->post['data_source'];
			} else if (isset($this->request->get['data_source'])) {
				$data_source = $this->request->get['data_source'];
			} if (isset($this->request->post['model'])) {
				$model = $this->request->post['model'];
			} else if (isset($this->request->get['model'])) {
				$model = $this->request->get['model'];
			} if (isset($this->request->post['matching_code'])) {
				$matching_code = $this->request->post['matching_code'];
			} else if (isset($this->request->get['matching_code'])) {
				$matching_code = $this->request->get['matching_code'];
			}
			$apiParams = array();
			$apiParams['using_warehouse'] = $using_warehouse;
			$apiParams['product_id'] = $product_id;
			$apiParams['data_source'] = $data_source;
			$apiParams['model'] = $model;
			$apiParams['matching_code'] = $matching_code;
			//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
			
			$this->load->model('catalog/product');
			$json = $this->model_catalog_product->coreAPI($apitype, $apiParams);
		
		} else if($apitype=="delivery_status") {
			
			$customer_id = ""; $order_id = "";
			if (isset($params['customer_id'])) {
				$customer_id = $params['customer_id'];
			} else if (isset($this->request->post['customer_id'])) {
				$customer_id = $this->request->post['customer_id'];
			} else if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} if (isset($params['order_id'])) {
				$order_id = $params['order_id'];
			} else if (isset($this->request->post['order_id'])) {
				$order_id = $this->request->post['order_id'];
			} else if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			}
//echo "<pre>";print_r($order_id);echo "</pre>";//exit;
//$customer_id = "GOS1|GOS2";	
//$order_id = "D0001|D0002";	
			$customer_ids = ""; $order_ids = "";
			$customer_ids = explode("|",$customer_id);
			$order_ids = explode("|",$order_id);
			$dataJS = array();
			foreach($customer_ids as $key => $value) {
				$dataJS["TSHTable"][$key]["CustCode"] = $customer_ids[$key];
				$dataJS["TSHTable"][$key]["OrderID"] = $order_ids[$key];
			}
			
			//Array(
			//	[TSHTable] => Array(
			//		[0] => Array(
			//			[CustCode] => partUnique1234
			//			[OrderID] => partUnique1234@1
			//		)
			//		[1] => Array(
			//			[CustCode] => partUnique1234
			//			[OrderID] => partUnique1234@2
			//		)
			//	)
			//)
			$strJson = json_encode($dataJS);
			//echo "<pre>";print_r($strJson);echo "</pre>";exit;
			
			$apiParams = array();
			$apiParams['customer_id'] = $customer_id;
			$apiParams['order_id'] = $order_id;
			$apiParams['json_data'] = $strJson;
			//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
			$this->load->model('catalog/product');
			$json = $this->model_catalog_product->coreAPI($apitype, $apiParams);
			//echo "<pre>";print_r($array);echo "</pre>";exit;
			//echo "<pre>";print_r($array['wms_data']);echo "</pre>";exit;
		
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        
	public function index() {
		$this->load->language('product/product');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$this->load->model('catalog/category');
                
		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);
                                
				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);
                        
			if ($category_info) {
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

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('product/manufacturer')
			);

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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$data['breadcrumbs'][] = array(
					'text' => $manufacturer_info['name'],
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url)
			);
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);
                
                $productsCats = $this->model_catalog_product->getCategories($product_id);
                $petronas_only = false;
                
                foreach($productsCats AS $proKey => $proVal) {
                    
                    $category_infoP = $this->model_catalog_category->getCategory($proVal['category_id']);
                    
                    if(!empty($category_infoP)) {
                        if($category_infoP['petronas_only'] == '1') {
                            $petronas_only = true;
                            break;
                        }
                    }
                }
                
                
                $this->load->model('account/customer');
                
                $isPetronasUser = $this->model_account_customer->isPetronasUser();
                

                if($petronas_only && !$isPetronasUser) {
                    $product_info = array();
                }
                
                
                if ($product_info) {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $product_info['name'],
				'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
			);

			$this->document->setTitle($product_info['meta_title']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['heading_title'] = $product_info['name'];

			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/review');

			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['data_source'] = $product_info['data_source'];
			$data['model'] = $product_info['model'];
			$data['matching_code'] = $product_info['matching_code'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];
			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			
            if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');
			
			// BRP Customized Part
			if($product_info['image'] && (strpos($product_info['image'], "brp.com.my")===false)) {
				$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
			} else if ($product_info['image']) {
                                $data['popup'] = $this->model_tool_image->resizeBRP($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
				//$data['popup'] = $product_info['image'];
			} else {
				$data['popup'] = '';
			}
			
			// BRP Customized Part
			if($product_info['image'] && (strpos($product_info['image'], "brp.com.my")===false)) {
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
			} else if ($product_info['image']) {
                                $data['thumb'] = $this->model_tool_image->resizeBRP($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
				//$data['thumb'] = $product_info['image'];
			} else {
				$data['thumb'] = '';
			}

			$data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
			
			// BRP Customized Part
			$data['thumb_width'] = $this->config->get($this->config->get('config_theme') . '_image_thumb_width');
			$data['thumb_height'] = $this->config->get($this->config->get('config_theme') . '_image_thumb_height');
			//echo "<pre>";print_r($product_info);echo "</pre>";exit;
//			if($data['popup']!="" && $data['thumb']!="") {
//				$data['images'][] = array(
//					'popup' => $data['popup'],
//					'thumb' => $data['thumb']
//				);
//			}
			
			foreach ($results as $result) {
				// BRP Customized Part
				if((strpos($result['image'], "brp.com.my")===false)) {
					$data['images'][] = array(
						'popup' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
						'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
					);
				} else {
					$data['images'][] = array(
						'popup' => $this->model_tool_image->resizeBRP($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
						'thumb' => $this->model_tool_image->resizeBRP($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
					);
				}
			}
//--------------------------------------------------wani codes starts-------------------------------------------
			// if ($this->customer->isLogged()) {
				// $data['customer_group_id'] = $this->customer->getGroupId();
				// $data['parent_customer_group_id'] = $this->model_account_customer->custParentsGroup();
			// } else {
				// $data['customer_group_id'] = '';
				// $data['parent_customer_group_id'] = '';
			// }

	
			// if ($this->customer->isLogged()) {
				// if($data['parent_customer_group_id']){
					// foreach($data['parent_customer_group_id'] as $custval){
						// $data['parentSpecialPrice'] = $this->model_account_customer->getParentSpecial($custval, $product_info['product_id']);
						// $product_info['special']=$data['parentSpecialPrice'];
					// }
				// }
			// }
			// else{
				// $data['parentSpecialPrice'] = '';
			// }
	
			 // if ($this->customer->isLogged()) {
				// $data['prioritySpecial']=$this->model_account_customer->getPrioritySpecial($product_info['product_id']);
				// // echo "<pre>";var_dump($data['prioritySpecial']);echo "</pre>";exit;
				// $priority=array();
				// foreach($data['prioritySpecial'] as $prior=>$val)
				// {
				// 	$priority[$val['priority']][]=$val['price'];
				// }
				// if($priority){
				// 	$highestPriority = min(array_keys($priority));
				// 	$specialPriority = $priority[$highestPriority];
				// 	$minSpecial = min($specialPriority);
				// 	$product_info['special']=$minSpecial;
				// 	// echo "<pre>";var_dump($minSpecial);echo "</pre>";exit;
				// }
			 // }
			 
				// if(!empty($data['priority'])){
					// $product_info['special']=$data['priority'];
				// }else{
					// foreach($data['parent_customer_group_id'] as $custval){
						// $data['parentSpecialPrice'] = $this->model_account_customer->getParentSpecial($custval, $product_info['product_id']);
						// $product_info['special']=$data['parentSpecialPrice'];
					// }
				// }
			// }
			// else{
				// $data['parentSpecialPrice'] = '';				
			// }
			
			// if ($this->customer->isLogged()) {
				// if($data['parent_customer_group_id']){
					// foreach($data['parent_customer_group_id'] as $custval){
						// $data['parentSpecialPrice'] = $this->model_account_customer->getParentSpecial($custval, $product_info['product_id']);
						// $product_info['special']=$data['parentSpecialPrice'];
					// }
				// }
			// }
			// else{
				// $data['parentSpecialPrice'] = '';				
			// }			
			//var_dump($product_info['special']);exit;
	//--------------------------------------------------wani codes ends-------------------------------------------		
			
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['price'] = false;
			}
			
			//var_dump($product_info['special']);
			if ((float)$product_info['special']) {
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['special'] = false;
			}
			
			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
			} else {
				$data['tax'] = false;
			}

			if ($this->config->get('config_using_warehouse_module')) { // new added
				$data['config_using_warehouse_module'] = true;
			} else {
				$data['config_using_warehouse_module'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
				);
			}

			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
						} else {
							$price = false;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
			}

			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

			$data['review_status'] = $this->config->get('config_review_status');

			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$data['rating'] = (int)$product_info['rating'];

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}

			$data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']);

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			foreach ($results as $result) {
				// BRP Customized Part
				if((strpos($result['image'], "brp.com.my")===false)) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
					}
				} else {
                                        $image = $this->model_tool_image->resizeBRP($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
					//$image = $result['image'];
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				 // if ($this->customer->isLogged()) {
					// $data['prioritySpecial']=$this->model_account_customer->getPrioritySpecial($result['product_id']);
					// $priority=array();
					// foreach($data['prioritySpecial'] as $prior=>$val)
					// {
					// 	$priority[$val['priority']][]=$val['price'];
					// }
					// if($priority){
					// 	$highestPriority = min(array_keys($priority));
					// 	$specialPriority = $priority[$highestPriority];
					// 	$minSpecial = min($specialPriority);
					// 	$result['special']=$minSpecial;
					// }
				 // }				
				
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}

			$data['tags'] = array();

			if ($product_info['tag']) {
				$tags = explode(',', $product_info['tag']);

				foreach ($tags as $tag) {
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('product/product', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function review() {
		$this->load->language('product/product');

		$this->load->model('catalog/review');

		$data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		$this->response->setOutput($this->load->view('product/review', $data));
	}

	public function write() {
		$this->load->language('product/product');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getRecurringDescription() {
		$this->load->language('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function sendMail(){ //lazada
		$this->language->load('mail/order');
		$this->load->model('catalog/product');
		
		//back to 5 level up in directory
		$url = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));

		$orderDataURL = $url . "/cron/orderData.txt";
		$itemGroupURL = $url . "/cron/itemGroup.txt";
		$itemIdQtyURL = $url . "/cron/itemIdQty.txt";

		$writeLocation = '/var/www/html/partner/cron/hasilLazadaUrl.txt';
		file_put_contents($writeLocation, $orderDataURL);
		
		$brp_ord_id = $this->request->post['order_id'];

		$orderData = json_decode(file_get_contents($orderDataURL), true);
		$itemGroup = json_decode(file_get_contents($itemGroupURL), true);
		$itemIdQty = json_decode(file_get_contents($itemIdQtyURL), true);
		
		// HTML Mail
		$data = array();
		$data['title'] = sprintf($this->language->get('text_new_subject'), str_replace(".com","",$this->config->get('config_name')), $brp_ord_id);
		$data['text_order_detail'] = $this->language->get('text_new_order_detail');
		$data['text_instruction'] = $this->language->get('text_new_instruction');
		$data['text_order_id'] = $this->language->get('text_new_order_id');
		$data['text_date_added'] = $this->language->get('text_new_date_added');
		$data['text_payment_method'] = $this->language->get('text_new_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_new_shipping_method');
		$data['text_email'] = $this->language->get('text_new_email');
		$data['text_telephone'] = $this->language->get('text_new_telephone');
		$data['text_ip'] = $this->language->get('text_new_ip');
		$data['text_order_status'] = $this->language->get('text_new_order_status');
		$data['text_payment_address'] = $this->language->get('text_new_payment_address');
		$data['text_shipping_address'] = $this->language->get('text_new_shipping_address');
		$data['text_product'] = $this->language->get('text_new_product');
		$data['text_model'] = $this->language->get('text_new_model');
		$data['text_quantity'] = $this->language->get('text_new_quantity');
		$data['text_price'] = $this->language->get('text_new_price');
		$data['text_total'] = $this->language->get('text_new_total');
		$data['text_gst'] = $this->language->get('text_new_gst');
		$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
		$data['store_name'] = $this->config->get('config_name');
		$data['store_url'] = "https://www.".$this->config->get('config_name')."/";
		$data['customer_id'] = false;
		$data['order_id'] = $brp_ord_id;
		$data['date_added'] = date($this->language->get('date_format_short'), strtotime($orderData['created_at']));
		$data['payment_method'] = $orderData['payment_method'];
		$data['shipping_method'] = $orderData['shipping_method'];
		$data['email'] = $orderData['address_billing']['customer_email'];
		$data['telephone'] = $orderData['address_billing']['phone'];
		$data['comment'] = $orderData['comment'];
		
		$format = '{firstname} {lastname}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		$find = array(
			'{firstname}',
			'{lastname}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{country}'
		);
		$replace = array(
			'firstname' => $orderData['address_billing']['first_name'],
			'lastname'  => $orderData['address_billing']['last_name'],
			'address_1' => $orderData['address_billing']['address1'],
			'address_2' => $orderData['address_billing']['address2'],
			'city'      => $orderData['address_billing']['address4'],
			'postcode'  => $orderData['address_billing']['post_code'],
			'zone'      => $orderData['address_billing']['address3'],
			'country'   => $orderData['address_billing']['country']
		);
		$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		$replace = array(
			'firstname' => $orderData['address_shipping']['first_name'],
			'lastname'  => $orderData['address_shipping']['last_name'],
			'address_1' => $orderData['address_shipping']['address1'],
			'address_2' => $orderData['address_shipping']['address2'],
			'city'      => $orderData['address_shipping']['address4'],
			'postcode'  => $orderData['address_shipping']['post_code'],
			'zone'      => $orderData['address_shipping']['address3'],
			'country'   => $orderData['address_shipping']['country']
		);
		$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		$orderSubTotal = 0; $orderTax = 0;
		foreach($itemGroup AS $item){
			$price = $item['item_price'];
			$quantity = $itemIdQty[$item['sku']];
			$tax = 0; // GST 0%
			$total = $price*$quantity;
			$data['products'][] = array(
				'name'     => $item['name'],
				'model'    => $item['sku'],
				'option'   => array(),
				'quantity' => $quantity,
				'price'    => 'RM'.number_format($price, 2, '.', ','),
				'total'    => 'RM'.number_format(($total + $tax), 2, '.', ','),
				'gst'      => $tax
			);
			$orderSubTotal += $total; $orderTax += $tax;
		}
		$data['vouchers'] = array();
		$data['totals'][] = array(
			'title' => 'Sub-Total (before GST)',
			'text'  => 'RM'.number_format($orderSubTotal, 2, '.', ','),
		);
		$data['totals'][] = array(
			'title' => 'Shipping',
			'text'  => 'RM'.number_format(str_replace(",", "", $orderData['shipping_fee']), 2, '.', ','),
		);
		if(!empty($orderData['voucher'])){
			$data['totals'][] = array(
				'title' => 'Voucher',
				'text'  => 'RM-'.number_format(str_replace(",", "", $orderData['voucher']), 2, '.', ','),
			);
		}
		$data['totals'][] = array(
			'title' => 'GST',
			'text'  => 'RM'.number_format($orderTax, 2, '.', ','),
		);
		$data['totals'][] = array(
			'title' => 'Total',
			'text'  => 'RM'.number_format(($orderSubTotal + $orderTax + $orderData['shipping_fee'] - $orderData['voucher']), 2, '.', ','),
		);
		//alert admin
		$subject = sprintf($this->language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $brp_ord_id);
		$data['text_greeting'] = $this->language->get('text_new_received');
		$data['text_download'] = '';
		$data['text_footer'] = '';
		$data['text_link'] = '';
		$data['link'] = '';
		$data['download'] = '';
		$data['ip'] = '-';
		$data['order_status'] = $orderData['status'];
		/*
		$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id);
	
		if ($order_status_query->num_rows) {
			$data['order_status'] = $order_status_query->row['name'];
		} else {
			$data['order_status'] = '';
		}
		*/
		$html = $this->load->view('mail/order.tpl', $data);
		
		$mail = new Mail();
		$mail->SMTPDebug = 2;
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(str_replace(".com","",$this->config->get('config_name')));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($html);
		if($mail->send()){
			//echo "<br>Success<br>";
		}else{
			//echo "<br>Cannot send mail.<br>";
		}
	}
	
	public function sendMail3(){ //Shopee
		$this->language->load('mail/order');
		$this->load->model('catalog/product');

		$brp_ord_id = $this->request->post['order_id'];
		$this->log->write($brp_ord_id);
		$orderData = array();
		$sql = "SELECT a.*, b.value AS shipping_fee FROM oc_order a LEFT JOIN oc_order_total b ON (a.order_id=b.order_id) WHERE a.order_id = '$brp_ord_id' AND b.code = 'shipping'";
		$data = $this->db->query($sql);
		foreach($data->rows as $order){
			$orderData = $order;
		}
		
		$orderProduct = array();
		$sql = "SELECT * FROM oc_order_product WHERE order_id = '$brp_ord_id'";
		$data = $this->db->query($sql);
		foreach($data->rows as $product){
			$orderProduct[] = $product;
		}
		// HTML Mail
		$data = array();
		$data['title'] = sprintf($this->language->get('text_new_subject'), str_replace(".com","",$this->config->get('config_name')), $brp_ord_id);
		$data['text_order_detail'] = $this->language->get('text_new_order_detail');
		$data['text_instruction'] = $this->language->get('text_new_instruction');
		$data['text_order_id'] = $this->language->get('text_new_order_id');
		$data['text_date_added'] = $this->language->get('text_new_date_added');
		$data['text_payment_method'] = $this->language->get('text_new_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_new_shipping_method');
		$data['text_email'] = $this->language->get('text_new_email');
		$data['text_telephone'] = $this->language->get('text_new_telephone');
		$data['text_ip'] = $this->language->get('text_new_ip');
		$data['text_order_status'] = $this->language->get('text_new_order_status');
		$data['text_payment_address'] = $this->language->get('text_new_payment_address');
		$data['text_shipping_address'] = $this->language->get('text_new_shipping_address');
		$data['text_product'] = $this->language->get('text_new_product');
		$data['text_model'] = $this->language->get('text_new_model');
		$data['text_quantity'] = $this->language->get('text_new_quantity');
		$data['text_price'] = $this->language->get('text_new_price');
		$data['text_total'] = $this->language->get('text_new_total');
		$data['text_gst'] = $this->language->get('text_new_gst');
		$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
		$data['store_name'] = $this->config->get('config_name');
		$data['store_url'] = "https://www.".$this->config->get('config_name')."/";
		$data['customer_id'] = false;
		$data['order_id'] = $brp_ord_id;
		$data['date_added'] = date($this->language->get('date_format_short'), strtotime($orderData['date_added']));
		$data['payment_method'] = $orderData['payment_method'];
		$data['shipping_method'] = $orderData['shipping_method'];
		$data['email'] = $orderData['email'];
		$data['telephone'] = $orderData['telephone'];
		$data['comment'] = $orderData['comment'];
		
		$format = '{firstname} {lastname}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		$find = array(
			'{firstname}',
			'{lastname}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{country}'
		);
		$replace = array(
			'firstname' => $orderData['payment_firstname'],
			'lastname'  => $orderData['payment_lastname'],
			'address_1' => $orderData['payment_address_1'],
			'address_2' => $orderData['payment_address_2'],
			'city'      => $orderData['payment_city'],
			'postcode'  => $orderData['payment_postcode'],
			'zone'      => $orderData['payment_zone'],
			'country'   => $orderData['payment_country']
		);
		$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		$replace = array(
			'firstname' => $orderData['shipping_firstname'],
			'lastname'  => $orderData['shipping_lastname'],
			'address_1' => $orderData['shipping_address_1'],
			'address_2' => $orderData['shipping_address_2'],
			'city'      => $orderData['shipping_city'],
			'postcode'  => $orderData['shipping_postcode'],
			'zone'      => $orderData['shipping_zone'],
			'country'   => $orderData['shipping_country']
		);
		$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		$orderSubTotal = 0; $orderTax = 0;
		foreach($orderProduct AS $item){
			$price = $item['price'];
			$quantity = $item['quantity'];
			$tax = 0; // GST 0%
			$total = $item['total'];
			$data['products'][] = array(
				'name'     => $item['name'],
				'model'    => $item['model'],
				'option'   => array(),
				'quantity' => $quantity,
				'price'    => 'RM'.number_format($price, 2, '.', ','),
				'total'    => 'RM'.number_format(($total + $tax), 2, '.', ','),
				'gst'      => $tax
			);
			$orderSubTotal += $total; $orderTax += $tax;
		}
		$data['vouchers'] = array();
		$data['totals'][] = array(
			'title' => 'Sub-Total (before GST)',
			'text'  => 'RM'.number_format($orderSubTotal, 2, '.', ','),
		);
		$data['totals'][] = array(
			'title' => 'Shipping',
			'text'  => 'RM'.number_format(str_replace(",", "", $orderData['shipping_fee']), 2, '.', ','),
		);
		$data['totals'][] = array(
			'title' => 'GST',
			'text'  => 'RM'.number_format($orderTax, 2, '.', ','),
		);
		$data['totals'][] = array(
			'title' => 'Total',
			'text'  => 'RM'.number_format(($orderSubTotal + $orderTax + $orderData['shipping_fee']), 2, '.', ',')
		);
		//alert admin
		$subject = sprintf($this->language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $brp_ord_id);
		$data['text_greeting'] = $this->language->get('text_new_received');
		$data['text_download'] = '';
		$data['text_footer'] = '';
		$data['text_link'] = '';
		$data['link'] = '';
		$data['download'] = '';
		$data['ip'] = '-';
		$data['order_status'] = '';
		$order_status_id = $orderData['order_status_id'];
		
		$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = " . (int)$order_status_id);
	
		if ($order_status_query->num_rows) {
			$data['order_status'] = $order_status_query->row['name'];
		} else {
			$data['order_status'] = '';
		}
		
		$html = $this->load->view('mail/order.tpl', $data);
		
		$mail = new Mail();
		$mail->SMTPDebug = 2;
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(str_replace(".com","",$this->config->get('config_name')));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($html);
		if($mail->send()){
			//echo "<br>Success<br>";
		}else{
			//echo "<br>Cannot send mail.<br>";
		}
	}

}
