<?php
class ControllerSaleOrder extends Controller {
	private $error = array();
	
	public function ajaxAPI($apitype = '', $params) {
		$array = array();
		$this->load->model('sale/order');
		if($apitype=="stock_levels") {
			
			$this->load->model('catalog/product');
			$arrProductIDs = array();
			$arrProductIDs[] = "-1";
			//echo "<pre>";print_r($params);echo "</pre>";exit;
			$strMatchingCodes = "";
			$using_warehouse = $this->config->get('config_using_warehouse_module');
			$dataJS = array();
			if(is_array($params) && count($params)>0) {
				
				$countIndex = 0;
				foreach ($params as $key => $param) {
					if(isset($param["matching_code"])&&$param["matching_code"]!="" && $param["data_source"]!="" && $param["data_source"]!="0") {
						if($strMatchingCodes!="") {
							$strMatchingCodes .= "|";
						}
						$strMatchingCodes .= $param["matching_code"];
						$dataJS["TSHTable"][$countIndex]["CustCode"] = $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
						$dataJS["TSHTable"][$countIndex]["ItemCode"] = (isset($param["matching_code"])?$param["matching_code"]:""); //(isset($param["matching_code"])?$param["matching_code"]:"");
						if(isset($param["product_id"])) {
							$arrProductIDs[] = $param["product_id"];
						}
						$countIndex++;
					} else if(isset($param["model"])&&$param["model"]!="") {
						if($strMatchingCodes!="") {
							$strMatchingCodes .= "|";
						}
						$strMatchingCodes .= $param["model"];
						$dataJS["TSHTable"][$countIndex]["CustCode"] = $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
						$dataJS["TSHTable"][$countIndex]["ItemCode"] = (isset($param["model"])?$param["model"]:"");
						if(isset($param["product_id"])) {
							$arrProductIDs[] = $param["product_id"];
						}
						$countIndex++;
					}
				}
			}

			$dataJS = $this->dataSanitisation($dataJS);

			$strJson = json_encode($dataJS);
			//echo "<pre>";print_r($dataJS["TSHTable"]);echo "</pre>";exit;
			
			$apiParams = array();
			$apiParams['matching_code'] = $strMatchingCodes;
			$apiParams['json_data'] = $strJson;
			$apiParams['product_ids'] = $arrProductIDs;
			//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
			$array = $this->model_catalog_product->coreAPI($apitype, $apiParams);
			//echo "<pre>";print_r($array);echo "</pre>";exit;
			
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
			$array = $this->model_sale_order->coreAPI($apitype, $apiParams);
			//echo "<pre>";print_r($array['wms_data']);echo "</pre>";exit;
		}
		//echo "<pre>";print_r($array);echo "</pre>";exit;
		//Query Opencart - End
		
		return $array;
	}

	public function dataSanitisation($dataJS) {
        foreach($dataJS["TSHTable"] as $tskkey => $tshvalue) {
                        foreach($tshvalue as $paramkey => $paramvalue) {

                            $insertValue = html_entity_decode($paramvalue);
                            $insertValue = utf8_encode($insertValue);//utf8_decode($valueData[$valCol]);//CBE-818S-Name-Card-Case

                            //$code = "\u0097";
                            //echo (html_entity_decode("\u0097", ENT_NOQUOTES));
                            //$insertValue = str_replace("\u0097","VVVVVVVVVV",$insertValue);
                            $insertValue = json_encode($insertValue);
                            $insertValue = str_replace("\u0097","\u2014",$insertValue);
                            $insertValue = str_replace("\u001f","",$insertValue);
                            //strip iso-8859-1 and windows-1252 control characters
    //                                $insertValue = str_replace("\u0081\u008D\u008F\u0090\u009D",'');
                            //Shift iso-8859-1 and windows-1252 up to unicode websafe equivalents
                            $insertValue = str_replace("\u0080",'\u20AC',$insertValue); //euro sign
                            $insertValue = str_replace("\u0082",'\u201A',$insertValue); //single low-9 quotation mark
                            $insertValue = str_replace("\u0083",'\u0192',$insertValue); //florin currency symbol
                            $insertValue = str_replace("\u0084",'\u201E',$insertValue); //double low-9 quotation mark
                            $insertValue = str_replace("\u0085",'\u2026',$insertValue); //horizontal ellipsis
                            $insertValue = str_replace("\u0086",'\u2020',$insertValue); //dagger
                            $insertValue = str_replace("\u0087",'\u2021',$insertValue); //double dagger
                            $insertValue = str_replace("\u0088",'\u02C6',$insertValue); //modifier letter circumflex accent
                            $insertValue = str_replace("\u0089",'\u2030',$insertValue); //per mille sign
                            $insertValue = str_replace("\u008a",'\u0160',$insertValue); //latin capital letter s with caron
                            $insertValue = str_replace("\u008b",'\u2039',$insertValue); //single left-pointing angle quotation mark
                            $insertValue = str_replace("\u008c",'\u0152',$insertValue); //latin capital ligature oe
                            $insertValue = str_replace("\u008e",'\u017D',$insertValue); //latin capital letter z with caron
                            $insertValue = str_replace("\u0091",'\u2018',$insertValue); //left single quotation mark
                            $insertValue = str_replace("\u0092",'\u2019',$insertValue); //right single quotation mark
                            $insertValue = str_replace("\u0093",'\u201C',$insertValue); //left double quotation mark
                            $insertValue = str_replace("\u0094",'\u201D',$insertValue); //right double quotation mark
                            $insertValue = str_replace("\u0095",'\u2022',$insertValue); //bullet
                            $insertValue = str_replace("\u0096",'\u2013',$insertValue); //en dash
                            $insertValue = str_replace("\u0097",'\u2014',$insertValue); //em dash
                            $insertValue = str_replace("\u0098",'\u02DC',$insertValue); //small tilde
                            $insertValue = str_replace("\u0099",'\u2122',$insertValue); //trade mark sign
                            $insertValue = str_replace("\u009a",'\u0161',$insertValue); //latin small letter s with caron
                            $insertValue = str_replace("\u009b",'\u203A',$insertValue); //single right-pointing angle quotation mark
                            $insertValue = str_replace("\u009c",'\u0153',$insertValue); //latin small ligature oe
                            $insertValue = str_replace("\u009e",'\u017E',$insertValue); //latin small letter z with caron
                            $insertValue = str_replace("\u009f",'\u0178',$insertValue); //latin capital letter y with diaeresis
                            //Replace nonbreaking space with regular space
    //                                $insertValue = str_replace("\u00A0",'\u0020',$insertValue);
                            //Shift common punctiation down to ASCII
                                    //soft hyphen, hyphen, non-breaking-hyphen, figure dash, en dash, em dash, horizontal bar,
                            //hyphen bullet, small em dash, small hyphen-minus and fullwidth hyphen-minus to hyphen-minus
    //                                $insertValue = str_replace("\u00AD\u2010\u2011\u2012\u2013\u2014\u2015\u2043\uFE58\uFE63\uFE0D",'\u002D');
                            //left, right and high-reversed-9 single quotation mark to apostrophe
    //                                $insertValue = str_replace("\u2018\u2019\u201B",'\u0027');
                            //left, right and high-reversed-9 double quotation mark to quotation mark
    //                                $insertValue = str_replace("\u201C\u201D\u201F",'\u0022');
                            //single and double low-9 quotation mark to comma
    //                                $insertValue = str_replace("\u201A\u201E",'\u002C');

                                $insertValue = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                                   return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
                                }, $insertValue);

                            $insertValue = json_decode($insertValue);

                            if(get_magic_quotes_gpc()){$insertValue = stripslashes($insertValue);}
                            $insertValue = $this->db->escape($insertValue);

                            $dataJS["TSHTable"][$tskkey][$paramkey] = urlencode($insertValue);
                        }
                    }
                    return $dataJS;
    }

	public function index() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getList();
	}

	public function add() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $order_id) {
				$this->model_sale_order->deleteOrder($order_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_order_no'])) {
				$url .= '&filter_order_no=' . $this->request->get['filter_order_no'];
			}
	
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
	
			if (isset($this->request->get['filter_order_status'])) {
				$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
			}
	
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
	
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
	
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			$this->response->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}
	
	protected function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_order_no'])) {
			$filter_order_no = $this->request->get['filter_order_no'];
		} else {
			$filter_order_no = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_no'])) {
			$url .= '&filter_order_no=' . $this->request->get['filter_order_no'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], true);
		$data['shipping'] = $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'], true);
		$data['add'] = $this->url->link('sale/order/add', 'token=' . $this->session->data['token'], true);
		$data['delete'] = $this->url->link('sale/order/delete', 'token=' . $this->session->data['token'], true);

		$data['orders'] = array();

		$filter_data = array(
			'filter_order_id'      => $filter_order_id,
			'filter_order_no'      => $filter_order_no,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => $filter_order_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_sale_order->getTotalOrders($filter_data);

		$results = $this->model_sale_order->getOrders($filter_data);

		// WMS Function Call - Start
		$customer_ids = "";
		$order_ids = "";
		foreach ($results as $result) {
			if($customer_ids!="") {
				$customer_ids .= "|" . $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
				$order_ids .= "|" . $result['unique_order_id'];
			} else {
				$customer_ids .= $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
				$order_ids .= $result['unique_order_id'];
			}
		}
		$apiParams = array("customer_id"=>$customer_ids, "order_id"=>$order_ids);
		////////$arrAPIResults = $this->ajaxAPI("delivery_status", $apiParams);
		//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
		// WMS Function Call - End

		foreach ($results as $result) {
			$externalStore = '';
			if(strpos($result['comment'], 'Lazada Order No: #') !== false){
				$externalStore = 'lazada';
			} else if(strpos($result['comment'], '11street Order No: #') !== false) {
				$externalStore = '11street';
			} else if(strpos($result['comment'], 'Shopee Order No: #') !== false) {
				$externalStore = 'shopee';
			}
			$data['orders'][] = array(
				'order_id'      => $result['order_id'],
				'order_no'      => $result['order_no'],
				'external_store' => $externalStore,
				'unique_order_id'=> $result['unique_order_id'],
				'customer'      => $result['customer'],
				'store_url'     => $result['store_url'],
				'order_status'  => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
				'delivery_status'=> (isset($arrAPIResults["wms_data"][$result['unique_order_id']])?$arrAPIResults["wms_data"][$result['unique_order_id']]:"-"),
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $result['shipping_code'],
				'view'          => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
				'edit'          => $this->url->link('sale/order/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_order_no'] = $this->language->get('column_order_no');
		$data['column_unique_order_id'] = $this->language->get('column_unique_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_delivery_status'] = $this->language->get('column_delivery_status');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_delivery_status'] = $this->language->get('entry_delivery_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');
		$data['entry_order_no'] = $this->language->get('entry_order_no');

		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

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

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_no'])) {
			$url .= '&filter_order_no=' . $this->request->get['filter_order_no'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, true);
		$data['sort_unique_order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.unique_order_id' . $url, true);
		$data['sort_customer'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=order_status' . $url, true);
		$data['sort_total'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, true);
		$data['sort_date_added'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_no'])) {
			$url .= '&filter_order_no=' . $this->request->get['filter_order_no'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_order_no'] = $filter_order_no;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_list', $data));
	}

	public function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
		$data['text_product'] = $this->language->get('text_product');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_order_detail'] = $this->language->get('text_order_detail');

		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_zone_code'] = $this->language->get('entry_zone_code');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_to_name'] = $this->language->get('entry_to_name');
		$data['entry_to_email'] = $this->language->get('entry_to_email');
		$data['entry_from_name'] = $this->language->get('entry_from_name');
		$data['entry_from_email'] = $this->language->get('entry_from_email');
		$data['entry_theme'] = $this->language->get('entry_theme');
		$data['entry_message'] = $this->language->get('entry_message');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
		$data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$data['entry_coupon'] = $this->language->get('entry_coupon');
		$data['entry_voucher'] = $this->language->get('entry_voucher');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_delivery_status'] = $this->language->get('entry_delivery_status');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_product_add'] = $this->language->get('button_product_add');
		$data['button_voucher_add'] = $this->language->get('button_voucher_add');
		$data['button_apply'] = $this->language->get('button_apply');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['tab_order'] = $this->language->get('tab_order');
		$data['tab_customer'] = $this->language->get('tab_customer');
		$data['tab_payment'] = $this->language->get('tab_payment');
		$data['tab_shipping'] = $this->language->get('tab_shipping');
		$data['tab_product'] = $this->language->get('tab_product');
		$data['tab_voucher'] = $this->language->get('tab_voucher');
		$data['tab_total'] = $this->language->get('tab_total');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_no'])) {
				$url .= '&filter_order_no=' . $this->request->get['filter_order_no'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true);

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
		}

		if (!empty($order_info)) {
			$data['order_id'] = $this->request->get['order_id'];
			$data['store_id'] = $order_info['store_id'];
			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

			$data['customer'] = $order_info['customer'];
			$data['customer_id'] = $order_info['customer_id'];
			$data['customer_group_id'] = $order_info['customer_group_id'];
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];
			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['fax'] = $order_info['fax'];
			$data['account_custom_field'] = $order_info['custom_field'];

			$this->load->model('customer/customer');

			$data['addresses'] = $this->model_customer_customer->getAddresses($order_info['customer_id']);

			$data['payment_firstname'] = $order_info['payment_firstname'];
			$data['payment_lastname'] = $order_info['payment_lastname'];
			$data['payment_company'] = $order_info['payment_company'];
			$data['payment_address_1'] = $order_info['payment_address_1'];
			$data['payment_address_2'] = $order_info['payment_address_2'];
			$data['payment_city'] = $order_info['payment_city'];
			$data['payment_postcode'] = $order_info['payment_postcode'];
			$data['payment_country_id'] = $order_info['payment_country_id'];
			$data['payment_zone_id'] = $order_info['payment_zone_id'];
			$data['payment_custom_field'] = $order_info['payment_custom_field'];
			$data['payment_method'] = $order_info['payment_method'];
			$data['payment_code'] = $order_info['payment_code'];

			$data['shipping_firstname'] = $order_info['shipping_firstname'];
			$data['shipping_lastname'] = $order_info['shipping_lastname'];
			$data['shipping_company'] = $order_info['shipping_company'];
			$data['shipping_address_1'] = $order_info['shipping_address_1'];
			$data['shipping_address_2'] = $order_info['shipping_address_2'];
			$data['shipping_city'] = $order_info['shipping_city'];
			$data['shipping_postcode'] = $order_info['shipping_postcode'];
			$data['shipping_country_id'] = $order_info['shipping_country_id'];
			$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
			$data['shipping_custom_field'] = $order_info['shipping_custom_field'];
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['shipping_code'] = $order_info['shipping_code'];

			// Products
			$data['order_products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$data['order_products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']),
					'quantity'   => $product['quantity'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'reward'     => $product['reward']
				);
			}

			// Vouchers
			$data['order_vouchers'] = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';

			$data['order_totals'] = array();

			$order_totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');

				if ($start && $end) {
					$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
				}
			}

			$data['order_status_id'] = $order_info['order_status_id'];
			$data['comment'] = $order_info['comment'];
			$data['affiliate_id'] = $order_info['affiliate_id'];
			$data['affiliate'] = $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'];
			$data['currency_code'] = $order_info['currency_code'];
		} else {
			$data['order_id'] = 0;
			$data['store_id'] = 0;
			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			
			$data['customer'] = '';
			$data['customer_id'] = '';
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['fax'] = '';
			$data['customer_custom_field'] = array();

			$data['addresses'] = array();

			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_custom_field'] = array();
			$data['payment_method'] = '';
			$data['payment_code'] = '';

			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_custom_field'] = array();
			$data['shipping_method'] = '';
			$data['shipping_code'] = '';

			$data['order_products'] = array();
			$data['order_vouchers'] = array();
			$data['order_totals'] = array();

			$data['order_status_id'] = $this->config->get('config_order_status_id');
			$data['comment'] = '';
			$data['affiliate_id'] = '';
			$data['affiliate'] = '';
			$data['currency_code'] = $this->config->get('config_currency');

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';
		}

		// Stores
		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']
			);
		}

		// Customer Groups
		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		// Custom Fields
		$this->load->model('customer/custom_field');

		$data['custom_fields'] = array();

		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'order' => 'ASC'
		);

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order'],
				'sort_unique_order'	 => $custom_field['sort_unique_order']
			);
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$data['voucher_min'] = $this->config->get('config_voucher_min');

		$this->load->model('sale/voucher_theme');

		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		// API login
		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
		
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		
		// Call to APIs - Start
		$apiParams = array(); //array(array("customer_id"=>$data['customer_id']), array("order_id"=>$data['order_id']));
		$arrAPIResults = $this->ajaxAPI("delivery_status", $apiParams);
		//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
		$data['api_delivery_status'] = (isset($arrAPIResults["oc_data"])?$arrAPIResults["oc_data"]:"-");
		//echo "<pre>";print_r($data);echo "</pre>";exit;
		// Call to APIs - End
		$this->response->setOutput($this->load->view('sale/order_form', $data));
	}

	public function info() {
		$this->load->model('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			$this->load->language('sale/order');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_customer_detail'] = $this->language->get('text_customer_detail');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_store'] = $this->language->get('text_store');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_customer'] = $this->language->get('text_customer');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_telephone'] = $this->language->get('text_telephone');
			$data['text_invoice'] = $this->language->get('text_invoice');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_affiliate'] = $this->language->get('text_affiliate');
			//$data['text_order'] = sprintf($this->language->get('text_order'), $this->request->get['order_id'].(isset($order_info['unique_order_id'])&&$order_info['unique_order_id']!=""?("); BRP Order ID (".$order_info['unique_order_id']):""));
			$data['text_order'] = sprintf($this->language->get('text_order'), $this->request->get['order_id']);
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_account_custom_field'] = $this->language->get('text_account_custom_field');
			$data['text_payment_custom_field'] = $this->language->get('text_payment_custom_field');
			$data['text_shipping_custom_field'] = $this->language->get('text_shipping_custom_field');
			$data['text_browser'] = $this->language->get('text_browser');
			$data['text_ip'] = $this->language->get('text_ip');
			$data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
			$data['text_user_agent'] = $this->language->get('text_user_agent');
			$data['text_accept_language'] = $this->language->get('text_accept_language');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_history_add'] = $this->language->get('text_history_add');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['column_product'] = $this->language->get('column_product');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');

			$data['entry_order_status'] = $this->language->get('entry_order_status');
			$data['entry_delivery_status'] = $this->language->get('entry_delivery_status');
			$data['entry_notify'] = $this->language->get('entry_notify');
			$data['entry_override'] = $this->language->get('entry_override');
			$data['entry_comment'] = $this->language->get('entry_comment');

			$data['help_override'] = $this->language->get('help_override');

			$data['button_invoice_print'] = $this->language->get('button_invoice_print');
			$data['button_shipping_print'] = $this->language->get('button_shipping_print');
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_generate'] = $this->language->get('button_generate');
			$data['button_reward_add'] = $this->language->get('button_reward_add');
			$data['button_reward_remove'] = $this->language->get('button_reward_remove');
			$data['button_commission_add'] = $this->language->get('button_commission_add');
			$data['button_commission_remove'] = $this->language->get('button_commission_remove');
			$data['button_history_add'] = $this->language->get('button_history_add');
			$data['button_ip_add'] = $this->language->get('button_ip_add');

			$data['tab_history'] = $this->language->get('tab_history');
			$data['tab_additional'] = $this->language->get('tab_additional');

			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_order_no'])) {
				$url .= '&filter_order_no=' . $this->request->get['filter_order_no'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status'])) {
				$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true)
			);

			$data['shipping'] = $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
			$data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
			$data['edit'] = $this->url->link('sale/order/edit', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
			$data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true);

			$data['token'] = $this->session->data['token'];

			$data['order_id'] = $this->request->get['order_id'];
			// display total for lazada & 11street
			$data['order_total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
			$data['store_id'] = $order_info['store_id'];
			$data['store_name'] = $order_info['store_name'];
                        $data['store_gst_no'] = $order_info['store_gst_no'];
			
			if ($order_info['store_id'] == 0) {
				$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			} else {
				$data['store_url'] = $order_info['store_url'];
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];

			if ($order_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], true);
			} else {
				$data['customer'] = '';
			}

			$this->load->model('customer/customer_group');

			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($order_info['customer_group_id']);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];

			$data['shipping_method'] = $order_info['shipping_method'];
			$data['payment_method'] = $order_info['payment_method'];

			// Payment Address
			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}' . "\n" . 'Telephone: {telephone}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}',
				'{telephone}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country'],
				'telephone'   => $order_info['telephone']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			// Shipping Address
			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}' . "\n" . 'Telephone: {telephone}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}',
				'{telephone}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country'],
				'telephone'   => $order_info['telephone']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			// Uploaded files
			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], true)
							);
						}
					}
				}

				$data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], true)
				);
			}

			$data['vouchers'] = array();

			$vouchers = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'href'        => $this->url->link('sale/voucher/edit', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], true)
				);
			}

			$data['totals'] = array();

			$totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			$data['comment'] = nl2br($order_info['comment']);

			$this->load->model('customer/customer');

			$data['reward'] = $order_info['reward'];

			$data['reward_total'] = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

			$data['affiliate_firstname'] = $order_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $order_info['affiliate_lastname'];

			if ($order_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], true);
			} else {
				$data['affiliate'] = '';
			}

			$data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);

			$this->load->model('marketing/affiliate');

			$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);

			$this->load->model('localisation/order_status');

			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

			if ($order_status_info) {
				$data['order_status'] = $order_status_info['name'];
			} else {
				$data['order_status'] = '';
			}

			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			$data['order_status_id'] = $order_info['order_status_id'];

			$data['account_custom_field'] = $order_info['custom_field'];

			// Custom Fields
			$this->load->model('customer/custom_field');

			$data['account_custom_fields'] = array();

			$filter_data = array(
				'sort'  => 'cf.sort_order',
				'order' => 'ASC'
			);

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($order_info['custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['account_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['account_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['custom_field'][$custom_field['custom_field_id']]
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name']
							);
						}
					}
				}
			}

			// Custom fields
			$data['payment_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order'],
								'sort_unique_order'	 => $custom_field['sort_unique_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['payment_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order'],
									'sort_unique_order'	 => $custom_field['sort_unique_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['payment_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order'],
							'sort_unique_order'	 => $custom_field['sort_unique_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order'],
								'sort_unique_order'	 => $custom_field['sort_unique_order']
							);
						}
					}
				}
			}

			// Shipping
			$data['shipping_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order'],
								'sort_unique_order'	 => $custom_field['sort_unique_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['shipping_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order'],
									'sort_unique_order'	 => $custom_field['sort_unique_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['shipping_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['shipping_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order'],
							'sort_unique_order'	 => $custom_field['sort_unique_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order'],
								'sort_unique_order'	 => $custom_field['sort_unique_order']
							);
						}
					}
				}
			}

			$data['ip'] = $order_info['ip'];
			$data['forwarded_ip'] = $order_info['forwarded_ip'];
			$data['user_agent'] = $order_info['user_agent'];
			$data['accept_language'] = $order_info['accept_language'];

			// Additional Tabs
			$data['tabs'] = array();

			if ($this->user->hasPermission('access', 'extension/payment/' . $order_info['payment_code'])) {
				if (is_file(DIR_CATALOG . 'controller/extension/payment/' . $order_info['payment_code'] . '.php')) {
					$content = $this->load->controller('extension/payment/' . $order_info['payment_code'] . '/order');
				} else {
					$content = null;
				}

				if ($content) {
					$this->load->language('extension/payment/' . $order_info['payment_code']);

					$data['tabs'][] = array(
						'code'    => $order_info['payment_code'],
						'title'   => $this->language->get('heading_title'),
						'content' => $content
					);
				}
			}

			$this->load->model('extension/extension');

			$extensions = $this->model_extension_extension->getInstalled('fraud');

			foreach ($extensions as $extension) {
				if ($this->config->get($extension . '_status')) {
					$this->load->language('extension/fraud/' . $extension);

					$content = $this->load->controller('extension/fraud/' . $extension . '/order');

					if ($content) {
						$data['tabs'][] = array(
							'code'    => $extension,
							'title'   => $this->language->get('heading_title'),
							'content' => $content
						);
					}
				}
			}
			
			// The URL we send API requests to
			$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			
			// API login
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info) {
				$data['api_id'] = $api_info['api_id'];
				$data['api_key'] = $api_info['key'];
				$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
			} else {
				$data['api_id'] = '';
				$data['api_key'] = '';
				$data['api_ip'] = '';
			}

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/order_info', $data));
		} else {
			return new Action('error/not_found');
		}
	}
	
	public function delivery_info_update() {
		
		//echo "<pre>";print_r($_REQUEST);echo "</pre>";exit;
		$this->load->model('sale/order');
		$this->load->language('sale/upload_delivery_list');
		$this->load->model('sale/upload_delivery_list');
		$this->load->model('catalog/product');
		$this->document->setTitle($this->language->get('heading_title'));
		
		$arrOrderProductIds = array();
		$arrWMSProductBalances = array();
		$arrConfigureDeliveries = array();
		if (isset($this->request->post['order_product_ids'])) {
			$arrOrderProductIds = $this->request->post['order_product_ids'];
		} if (isset($this->request->post['wms_product_balances'])) {
			$arrWMSProductBalances = $this->request->post['wms_product_balances'];
		} if (isset($this->request->post['configure_delivery'])) {
			$arrConfigureDeliveries = $this->request->post['configure_delivery'];
		}
		
		$arrOrderProductIdDelivery =  array();
		if (isset($this->request->post['order_product_ids']) && isset($this->request->post['configure_delivery'])) {
			foreach(array_combine($arrOrderProductIds,$arrConfigureDeliveries) as $f=>$n){
					$arrOrderProductIdDelivery[$f] = $n;
			}
		}
		// echo "<pre>";var_dump($arrOrderProductIdDelivery);echo "</pre>";

		foreach($arrOrderProductIdDelivery as $k=>$m){
			if($m=='own_arrangement'){
				$orderProductId = $this->model_sale_upload_delivery_list->getOrderProducts(" AND `order_product_id` = '".$k."'");
				foreach($orderProductId as $orderProductId2)
				{
					$productInfo = $this->model_catalog_product->getProduct($orderProductId2['product_id']);
					$finalQty = $productInfo['quantity'] - $orderProductId2['quantity'];
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '".$finalQty."' WHERE product_id = '" .$orderProductId2['product_id']. "'");
				}
			}
		}
		
		$the_order_id = 0;
		if (isset($this->request->get['order_id'])) {
			$the_order_id = $this->request->get['order_id'];
		}
			
		$this->load->model('sale/upload_delivery_list');
		

		/**if(count($arrConfigureDeliveries)>0) {
			$products = $this->model_sale_order->getOrderProducts($the_order_id);
			$apiParams = $products;
			$arrAPIResults = $this->ajaxAPI('stock_levels', $apiParams);
			//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
			$arrHasShortsProducts = array();
			foreach($products as $keyProd => $valueProd) {
				if($arrConfigureDeliveries[$keyProd] == "brp_warehouse") {
					//$arrAPIResults["wms_data"]["B11 59"] = 5;
					$intNewWMSBalance = (isset($arrAPIResults["wms_data"][$valueProd['model']])?str_replace(".0000","",$arrAPIResults["wms_data"][$valueProd['model']]):"0");
					if($intNewWMSBalance<$valueProd['quantity']) {
						$arrHasShortsProducts[$the_order_id][$valueProd['model']] = "WMS Stock Balance: <b>".$intNewWMSBalance."</b>. Short of Balance: <b>".($valueProd['quantity']-$intNewWMSBalance)."</b>";
					}
				}
			}
			if(count($arrHasShortsProducts)>0) {
				$this->response->redirect($this->url->link('sale/order/delivery_info', 'token=' . $this->session->data['token'] . "&order_id=".$the_order_id."&configure=1&short_qty=1", true));
			}
		}**/
				

		
		$arrOrderIDs = array();
		$strCustomMsg = "";
		$this->session->data['success_msg'] = "";
		if(count($arrOrderProductIds)>0 && count($arrOrderProductIds)==count($arrConfigureDeliveries)) {
			foreach ($arrConfigureDeliveries as $keyResult => $valueResult) {
				if ((strpos($valueResult, "brp_warehouse") !== false) && $valueResult != "brp_warehouse_gohoffice") {
					$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_product_id` = '".$arrOrderProductIds[$keyResult]."' ", "BRP Warehouse", $arrWMSProductBalances[$keyResult]); // Update the configure delivery to BRP Warehouse
				} else if($valueResult=="brp_warehouse_gohoffice") {
					$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_product_id` = '".$arrOrderProductIds[$keyResult]."' ", "BRP Warehouse Gohoffice", $arrWMSProductBalances[$keyResult]); // Update the configure delivery to BRP Warehouse & Gohoffice
				} else if($valueResult=="by_gohoffice") {
					$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_product_id` = '".$arrOrderProductIds[$keyResult]."' ", "Gohoffice", $arrWMSProductBalances[$keyResult]); // Update the configure delivery to Gohoffice
				} else if($valueResult=="own_arrangement") {
					$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_product_id` = '".$arrOrderProductIds[$keyResult]."' ", "Own Arrangement", $arrWMSProductBalances[$keyResult]); // Update the configure delivery to Own Arrangement
				}
				$this->session->data['success_msg'] = "Successfully update configure delivery";
			}

			$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Pending", array($the_order_id));
		}
		
		/*if($the_order_id!="") {
			$arrConfigureDeliveryLoop = array("BRP Warehouse", "Gohoffice"/*, "BRP Warehouse Gohoffice", "Own Arrangement");
			foreach($arrConfigureDeliveryLoop as $keyCDLoop => $valueCDLoop) {
				if($valueCDLoop=="BRP Warehouse") {
					$apitype = "upload_delivery_status";
				} else {
					$apitype = "upload_gohoffice_delivery_status";
				}
				
				$results = $this->model_sale_upload_delivery_list->getOrders(array(), " AND o.order_id IN ('".$the_order_id."')");
				//echo "<pre>x";print_r($results);echo "</pre>";exit;
				// WMS Function Call - Start
				$order_id = "";
				$customer_id = "";
				$unique_order_id = "";
				$order_date = "";
				$recipient_code = "";
				$recipient_name = "";
				$recipient_tel = "";
				$recipient_add1 = "";
				$recipient_add2 = "";
				$recipient_add3 = "";
				$recipient_add4 = "";
				$recipient_add5 = "";
				$item_code = "";
				$matching_code = "";
				$order_qty = "";
				$postcode = "";
				if($apitype=="upload_gohoffice_delivery_status") {
					$recipient_orderdate = "";
					$recipient_companyname = "";
					$recipient_shipping_add1 = "";
					$recipient_shipping_add2 = "";
					$recipient_shipping_city = "";
					$recipient_shipping_postcode = "";
					$recipient_shipping_state = "";
					$recipient_shipping_country = "";
					$recipient_firstname = "";
					$recipient_lastname = "";
					$recipient_email = "";
					$recipient_tel = "";
					$recipient_fax = "";
				}
				foreach ($results as $result) {
					//$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$result["order_id"]."' ", "Gohoffice"); // Update the configure delivery to BRP Warehouse
					$orderProducts = $this->model_sale_upload_delivery_list->getOrderProducts(" AND op.`order_id` = '".$result["order_id"]."' AND (op.`configure_delivery`='".$valueCDLoop."' OR op.`configure_delivery`='BRP Warehouse Gohoffice') ORDER BY op.order_product_id ASC");
					//echo "<pre>x";print_r($orderProducts);echo "</pre>";exit;
					foreach ($orderProducts as $product) {
						//echo "<pre>x";print_r($result);echo "</pre>";
						if($customer_id!="") {
							$order_id .= "|" . $result['order_id'];
							$customer_id .= "|" . $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
							$unique_order_id .= "|" . $result['unique_order_id'];
							$order_date .= "|" . date("d/m/Y", strtotime($result['date_added']));
							$recipient_code .= "|" . $result['customer_code'];
							$recipient_name .= "|" . $result['customer'];
							$recipient_tel .= "|" . $result['telephone'];
							$recipient_add1 .= "|" . $result['address1'];
							$recipient_add2 .= "|" . $result['address2'];
							$recipient_add3 .= "|" . $result['address3'];
							$recipient_add4 .= "|" . $result['address4'];
							$recipient_add5 .= "|" . $result['address5'];
							if($product['data_source']!="" && $product['data_source']!="0") {
								$item_code .= "|" . $product['matching_code'];
							} else {
								$item_code .= "|" . $product['model'];
							}
							$matching_code .= "|" . $product['matching_code'];
							// Logic for BRP Warehouse Gohoffice - start
							if($valueCDLoop=="BRP Warehouse" && $product['configure_delivery']=="BRP Warehouse Gohoffice") {
								$order_qty .= "|" . $product['wms_quantity'];
							} else if($valueCDLoop=="Gohoffice" && $product['configure_delivery']=="BRP Warehouse Gohoffice") {
								$order_qty .= "|" . $product['quantity']-$product['wms_quantity'];
							} else {
								$order_qty .= "|" . $product['quantity'];
							}
							$postcode .= "|" . $result['shipping_postcode'];
							// Logic for BRP Warehouse Gohoffice - End
							if($apitype=="upload_gohoffice_delivery_status") {
								$recipient_orderdate .= "|" . date("Y-m-d H:i:s", strtotime($result['date_added']));
								$recipient_companyname .= "|" . $result['shipping_company'];
								$recipient_shipping_add1 .= "|" . $result['shipping_address_1'];
								$recipient_shipping_add2 .= "|" . $result['shipping_address_2'];
								$recipient_shipping_city .= "|" . $result['shipping_city'];
								$recipient_shipping_postcode .= "|" . $result['shipping_postcode'];
								$recipient_shipping_state .= "|" . $result['shipping_zone'];
								$recipient_shipping_country .= "|" . $result['shipping_country'];
								$recipient_firstname .= "|" . $result['firstname'];
								$recipient_lastname .= "|" . $result['lastname'];
								$recipient_email .= "|" . $result['email'];
								$recipient_tel .= "|" . $result['telephone'];
								$recipient_fax .= "|" . $result['fax'];
							}
						} else {
							$order_id .= $result['order_id'];
							$customer_id .= $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
							$unique_order_id .= $result['unique_order_id'];
							$order_date = date("d/m/Y", strtotime($result['date_added']));
							$recipient_code = $result['customer_code'];
							$recipient_name = $result['customer'];
							$recipient_tel = $result['telephone'];
							$recipient_add1 = $result['address1'];
							$recipient_add2 = $result['address2'];
							$recipient_add3 = $result['address3'];
							$recipient_add4 = $result['address4'];
							$recipient_add5 = $result['address5'];
							if($product['data_source']!="" && $product['data_source']!="0") {
								$item_code = $product['matching_code'];
							} else {
								$item_code = $product['model'];
							}
							$matching_code = $product['matching_code'];
							// Logic for BRP Warehouse Gohoffice - start
							if($valueCDLoop=="BRP Warehouse" && $product['configure_delivery']=="BRP Warehouse Gohoffice") {
								$order_qty = $product['wms_quantity'];
							} else if($valueCDLoop=="Gohoffice" && $product['configure_delivery']=="BRP Warehouse Gohoffice") {
								$order_qty = $product['quantity']-$product['wms_quantity'];
							} else {
								$order_qty = $product['quantity'];
							}
							$postcode = $result['shipping_postcode'];
							// Logic for BRP Warehouse Gohoffice - End
							if($apitype=="upload_gohoffice_delivery_status") {
								$recipient_orderdate = date("Y-m-d H:i:s", strtotime($result['date_added']));
								$recipient_companyname = $result['shipping_company'];
								$recipient_shipping_add1 = $result['shipping_address_1'];
								$recipient_shipping_add2 = $result['shipping_address_2'];
								$recipient_shipping_city = $result['shipping_city'];
								$recipient_shipping_postcode = $result['shipping_postcode'];
								$recipient_shipping_state = $result['shipping_zone'];
								$recipient_shipping_country = $result['shipping_country'];
								$recipient_firstname = $result['firstname'];
								$recipient_lastname = $result['lastname'];
								$recipient_email = $result['email'];
								$recipient_tel = $result['telephone'];
								$recipient_fax = $result['fax'];
							}
						}
					}
				}
				$order_ids = explode("|",$order_id);
				$customer_ids = explode("|",$customer_id);
				$unique_order_ids = explode("|",$unique_order_id);
				$order_dates = explode("|",$order_date);
				if(isset($recipient_code) && $recipient_code!="") { $recipient_codes = explode("|",$recipient_code); } else { $recipient_codes = ""; }
				$recipient_names = explode("|",$recipient_name);
				$recipient_tels = explode("|",$recipient_tel);
				$recipient_add1s = explode("|",$recipient_add1);
				$recipient_add2s = explode("|",$recipient_add2);
				$recipient_add3s = explode("|",$recipient_add3);
				$recipient_add4s = explode("|",$recipient_add4);
				$recipient_add5s = explode("|",$recipient_add5);
				$item_codes = explode("|",$item_code);
				$matching_codes = explode("|",$matching_code);
				$order_qtys = explode("|",$order_qty);
				$postcodes = explode("|",$postcode);
				if($apitype=="upload_gohoffice_delivery_status") {
					$recipient_orderdates = explode("|",$recipient_orderdate);
					$recipient_companynames = explode("|",$recipient_companyname);
					$recipient_shipping_add1s = explode("|",$recipient_shipping_add1);
					$recipient_shipping_add2s = explode("|",$recipient_shipping_add2);
					$recipient_shipping_citys = explode("|",$recipient_shipping_city);
					$recipient_shipping_postcodes = explode("|",$recipient_shipping_postcode);
					$recipient_shipping_states = explode("|",$recipient_shipping_state);
					$recipient_shipping_countrys = explode("|",$recipient_shipping_country);
					$recipient_firstnames = explode("|",$recipient_firstname);
					$recipient_lastnames = explode("|",$recipient_lastname);
					$recipient_emails = explode("|",$recipient_email);
					$recipient_tels = explode("|",$recipient_tel);
					$recipient_faxs = explode("|",$recipient_fax);
				}
				
				$dataJS = array();
				foreach($customer_ids as $key => $value) {
					$dataJS["TSHTable"][$key]["CustCode"] = (isset($customer_ids[$key])?$customer_ids[$key]:"");
					$dataJS["TSHTable"][$key]["OrderID"] = (isset($unique_order_ids[$key])?$unique_order_ids[$key]:"");
					$dataJS["TSHTable"][$key]["OrderDate"] = (isset($order_dates[$key])?$order_dates[$key]:"");
					$dataJS["TSHTable"][$key]["RecipientCode"] = (isset($recipient_codes[$key])?$recipient_codes[$key]:"");
					$dataJS["TSHTable"][$key]["RecipientName"] = (isset($recipient_names[$key])?addslashes($recipient_names[$key]):"");
					$dataJS["TSHTable"][$key]["RecipientTel"] = (isset($recipient_tels[$key])?$recipient_tels[$key]:"");
					$dataJS["TSHTable"][$key]["RecipientAddress1"] = (isset($recipient_add1s[$key])?addslashes($recipient_add1s[$key]):"");
					$dataJS["TSHTable"][$key]["RecipientAddress2"] = (isset($recipient_add2s[$key])?addslashes($recipient_add2s[$key]):"");
					$dataJS["TSHTable"][$key]["RecipientAddress3"] = (isset($recipient_add3s[$key])?addslashes($recipient_add3s[$key]):"");
					$dataJS["TSHTable"][$key]["RecipientAddress4"] = (isset($recipient_add4s[$key])?addslashes($recipient_add4s[$key]):"");
					$dataJS["TSHTable"][$key]["RecipientAddress5"] = (isset($recipient_add5s[$key])?addslashes($recipient_add5s[$key]):"");
					$dataJS["TSHTable"][$key]["ItemCode"] = (isset($item_codes[$key])?$item_codes[$key]:"");
					$dataJS["TSHTable"][$key]["MatchingCode"] = (isset($matching_codes[$key])?$matching_codes[$key]:"");
					$dataJS["TSHTable"][$key]["OrderQty"] = (isset($order_qtys[$key])?$order_qtys[$key]:"");
					$dataJS["TSHTable"][$key]["Postcode"] = (isset($postcodes[$key])?$postcodes[$key]:"");
					if($apitype=="upload_gohoffice_delivery_status") {
						$dataJS["TSHTable"][$key]["RecipientOrderDate"] = (isset($recipient_orderdates[$key])?addslashes($recipient_orderdates[$key]):"");
						$dataJS["TSHTable"][$key]["CompanyName"] = (isset($recipient_companynames[$key])?addslashes($recipient_companynames[$key]):"");
						$dataJS["TSHTable"][$key]["RecipientShippingAddress1"] = (isset($recipient_shipping_add1s[$key])?addslashes($recipient_shipping_add1s[$key]):"");
						$dataJS["TSHTable"][$key]["RecipientShippingAddress2"] = (isset($recipient_shipping_add2s[$key])?addslashes($recipient_shipping_add2s[$key]):"");
						$dataJS["TSHTable"][$key]["RecipientShippingCity"] = (isset($recipient_shipping_citys[$key])?addslashes($recipient_shipping_citys[$key]):"");
						$dataJS["TSHTable"][$key]["RecipientShippingPostcode"] = (isset($recipient_shipping_postcodes[$key])?addslashes($recipient_shipping_postcodes[$key]):"");
						$dataJS["TSHTable"][$key]["RecipientShippingState"] = (isset($recipient_shipping_states[$key])?addslashes($recipient_shipping_states[$key]):"");
						$dataJS["TSHTable"][$key]["RecipientShippingCountry"] = (isset($recipient_shipping_countrys[$key])?addslashes($recipient_shipping_countrys[$key]):"");
						$dataJS["TSHTable"][$key]["FirstName"] = (isset($recipient_firstnames[$key])?addslashes($recipient_firstnames[$key]):"");
						$dataJS["TSHTable"][$key]["LastName"] = (isset($recipient_lastnames[$key])?addslashes($recipient_lastnames[$key]):"");
						$dataJS["TSHTable"][$key]["Email"] = (isset($recipient_emails[$key])?$recipient_emails[$key]:"");
						$dataJS["TSHTable"][$key]["Tel"] = (isset($recipient_tels[$key])?$recipient_tels[$key]:"");
						$dataJS["TSHTable"][$key]["Fax"] = (isset($recipient_faxs[$key])?$recipient_faxs[$key]:"");
					}
				}
				$strJson = json_encode($dataJS);
				
				$apiParams = array();
				$apiParams['order_id'] = $order_id;
				$apiParams['customer_id'] = $customer_id;
				$apiParams['unique_order_id'] = $unique_order_id;
				$apiParams['order_date'] = $order_date;
				if(isset($recipient_code) && $recipient_code!="") { $apiParams['recipient_code'] = $recipient_code; } else { $apiParams['recipient_code'] = ""; }
				$apiParams['recipient_name'] = $recipient_name;
				$apiParams['recipient_tel'] = $recipient_tel;
				$apiParams['recipient_add1'] = $recipient_add1;
				$apiParams['recipient_add2'] = $recipient_add2;
				$apiParams['recipient_add3'] = $recipient_add3;
				$apiParams['recipient_add4'] = $recipient_add4;
				$apiParams['recipient_add5'] = $recipient_add5;
				$apiParams['item_code'] = $item_code;
				$apiParams['matching_code'] = $matching_code;
				$apiParams['order_qty'] = $order_qty;
				$apiParams['postcode'] = $postcode;
				if($apitype=="upload_gohoffice_delivery_status") {
					$apiParams['recipient_orderdate'] = $recipient_orderdate;
					$apiParams['company_name'] = $recipient_companyname;
					$apiParams['recipient_shipping_add1'] = $recipient_shipping_add1;
					$apiParams['recipient_shipping_add2'] = $recipient_shipping_add2;
					$apiParams['recipient_shipping_city'] = $recipient_shipping_city;
					$apiParams['recipient_shipping_postcode'] = $recipient_shipping_postcode;
					$apiParams['recipient_shipping_state'] = $recipient_shipping_state;
					$apiParams['recipient_shipping_country'] = $recipient_shipping_country;
					$apiParams['first_name'] = $recipient_firstname;
					$apiParams['last_name'] = $recipient_lastname;
					$apiParams['email'] = $recipient_email;
					$apiParams['tel'] = $recipient_tel;
					$apiParams['fax'] = $recipient_fax;
				}
				$apiParams['json_data'] = $strJson;
				//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
				$arrAPIResults = $this->model_sale_upload_delivery_list->coreAPI($apitype, $apiParams);
				// WMS Function Call - End														  
			}
			if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
				$this->session->data['failure_msg'] = $arrAPIResults["failure_msg"];
				//break;
			} else {
				////$this->printtran($sync_orders, "email");
			}
		}*/
		
		$url = "&configure=1&savenemail=1&order_id=" . $the_order_id;
		//$this->response->redirect($this->url->link('sale/order/delivery_info', 'token=' . $this->session->data['token'] . $url, true));
		$this->response->redirect($this->url->link('sale/upload_delivery_list/configure_delivery_email', 'token=' . $this->session->data['token'] . $url, true));
	}
	
	public function delivery_info() {
		$this->load->model('sale/order');
		$this->load->model('sale/upload_delivery_list');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		} if (isset($this->request->get['emailed']) && $this->request->get['emailed']=="1") {
			$isEmailed = $this->request->get['emailed'];
		} else if (isset($this->request->get['emailed']) && $this->request->get['emailed']=="2") {
			$isEmailed = $this->request->get['emailed'];
		} else {
			$isEmailed = 0;
		}

		$order_info = $this->model_sale_order->getOrder($order_id);
		//echo "<pre>";print_r($order_info);echo "</pre>";exit;
		if ($order_info) {
			//$this->load->language('sale/order');
			$this->load->language('sale/upload_delivery_list');

			$data['is_syned_gohoffice'] = ($order_info["total_cd_none"]==0&&$order_info["total_prod_type"]==($order_info["total_cd_brp"]+$order_info["total_cd_gohoffice"])?true:false); // New added
			//echo $order_info["total_cd_gohoffice"];exit;
			
			$this->document->setTitle($this->language->get('heading_title'));
			//$this->document->setTitle($this->language->get('heading_title'));

			// WMS Function Call - Start
			$customer_ids = $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
			$order_ids = $order_info["unique_order_id"];
			$apiParams = array("customer_id"=>$customer_ids, "order_id"=>$order_ids);
			$arrAPIResults = $this->ajaxAPI("delivery_status", $apiParams);
			//echo $order_ids;exit;
			//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
			// WMS Function Call - End
			$data['upload_delivery_status'] = $this->model_sale_upload_delivery_list->getUploadDeliveryStatus($order_id);
			$data['delivery_status'] = (isset($arrAPIResults["wms_data"][$order_info["unique_order_id"]])?$arrAPIResults["wms_data"][$order_info["unique_order_id"]]:"-");
			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_customer_detail'] = $this->language->get('text_customer_detail');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_store'] = $this->language->get('text_store');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_customer'] = $this->language->get('text_customer');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_telephone'] = $this->language->get('text_telephone');
			$data['text_invoice'] = $this->language->get('text_invoice');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_affiliate'] = $this->language->get('text_affiliate');
			//$data['text_order'] = sprintf($this->language->get('text_order'), $this->request->get['order_id'].(isset($order_info['unique_order_id'])&&$order_info['unique_order_id']!=""?("); BRP Order ID (".$order_info['unique_order_id']):""));
			$data['text_order'] = sprintf($this->language->get('text_order'), $this->request->get['order_id']);
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_account_custom_field'] = $this->language->get('text_account_custom_field');
			$data['text_payment_custom_field'] = $this->language->get('text_payment_custom_field');
			$data['text_shipping_custom_field'] = $this->language->get('text_shipping_custom_field');
			$data['text_browser'] = $this->language->get('text_browser');
			$data['text_ip'] = $this->language->get('text_ip');
			$data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
			$data['text_user_agent'] = $this->language->get('text_user_agent');
			$data['text_accept_language'] = $this->language->get('text_accept_language');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_history_add'] = $this->language->get('text_history_add');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['column_product'] = $this->language->get('column_product');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_product_type'] = $this->language->get('column_product_type');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_brp_warehouse_qty'] = $this->language->get('column_brp_warehouse_qty');

			$data['entry_order_status'] = $this->language->get('entry_order_status');
			$data['entry_delivery_status'] = $this->language->get('entry_delivery_status');
			$data['entry_notify'] = $this->language->get('entry_notify');
			$data['entry_override'] = $this->language->get('entry_override');
			$data['entry_comment'] = $this->language->get('entry_comment');
			$data['entry_brp_warehouse_qty'] = $this->language->get('entry_brp_warehouse_qty');

			$data['help_override'] = $this->language->get('help_override');

			$data['button_invoice_print'] = $this->language->get('button_invoice_print');
			$data['button_shipping_print'] = $this->language->get('button_shipping_print');
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_generate'] = $this->language->get('button_generate');
			$data['button_reward_add'] = $this->language->get('button_reward_add');
			$data['button_reward_remove'] = $this->language->get('button_reward_remove');
			$data['button_commission_add'] = $this->language->get('button_commission_add');
			$data['button_commission_remove'] = $this->language->get('button_commission_remove');
			$data['button_history_add'] = $this->language->get('button_history_add');
			$data['button_ip_add'] = $this->language->get('button_ip_add');

			$data['tab_history'] = $this->language->get('tab_history');
			$data['tab_additional'] = $this->language->get('tab_additional');

			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status'])) {
				$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['breadcrumbs'][] = array(
				'text' => "Delivery Information",
				'href' => $this->url->link('sale/upload_delivery_list/delivery_info', 'token=' . $this->session->data['token'] . $url, true)
			);

			$data['shipping'] = $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
			$data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
			$data['edit'] = $this->url->link('sale/order/edit', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
			$data['cancel'] = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel_delivery'] = $this->url->link('sale/upload_delivery_list/cancel', 'token=' . $this->session->data['token'] . '&sync_orders=' . (int)$this->request->get['order_id'], true);
			$data['print_delivery'] = $this->url->link('sale/upload_delivery_list/printtran', 'printtype=print&token=' . $this->session->data['token'] . '&sync_orders=' . (int)$this->request->get['order_id'], true);
			$data['email_delivery'] = $this->url->link('sale/upload_delivery_list/printtran', 'printtype=email&token=' . $this->session->data['token'] . '&sync_orders=' . (int)$this->request->get['order_id'] . '&savenemail=1', true);
			$data['update_configure_delivery'] = $this->url->link('sale/order/delivery_info_update', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], true);

			$data['token'] = $this->session->data['token'];

			$data['order_id'] = $this->request->get['order_id'];

			$data['store_id'] = $order_info['store_id'];
			$data['store_name'] = $order_info['store_name'];
			
			if ($order_info['store_id'] == 0) {
				$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			} else {
				$data['store_url'] = $order_info['store_url'];
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];

			if ($order_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], true);
			} else {
				$data['customer'] = '';
			}

			$this->load->model('customer/customer_group');

			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($order_info['customer_group_id']);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];

			$data['shipping_method'] = $order_info['shipping_method'];
			$data['payment_method'] = $order_info['payment_method'];

			// Payment Address
			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}' . "\n" . 'Telephone: {telephone}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}',
				'{telephone}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country'],
				'telephone'   => $order_info['telephone']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			// Shipping Address
			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}' . "\n" . 'Telephone: {telephone}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}',
				'{telephone}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country'],
				'telephone'   => $order_info['telephone']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			// Uploaded files
			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
			
			// New Added
			$apiParams = $products;
			$arrAPIResults = $this->ajaxAPI('stock_levels', $apiParams);
			//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
			$boolOrderQtyMoreThanWMSATO = false;
			$boolOrderQtyLessThanWMSATO = false;
			
			foreach ($products as $product) {
				$option_data = array();

                                $product['model'] = html_entity_decode($product['model']);
                                $product['matching_code'] = html_entity_decode($product['matching_code']);

				$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], true)
							);
						}

					}
				}
				
				// New added
				$intNewWMSBalance = "-";
				if($this->config->get('config_using_warehouse_module')&&$product['data_source']!=""&&$product['data_source']!="0") {
                                        $currentStock = (isset($arrAPIResults["wms_data"][$product['matching_code']])?str_replace(".0000","",$arrAPIResults["wms_data"][$product['matching_code']]):"0");
                                        $availToOrderQty = (isset($arrAPIResults["wms_data2"][$product['matching_code']])?str_replace(".0000","",$arrAPIResults["wms_data2"][$product['matching_code']]):"0");
					$intNewWMSBalance = (isset($arrAPIResults["wms_data"][$product['matching_code']])?str_replace(".0000","",$arrAPIResults["wms_data"][$product['matching_code']]):"0");
				} if($this->config->get('config_using_warehouse_module')&&$product['data_source']=="") {
                                        $currentStock = (isset($arrAPIResults["wms_data"][$product['model']])?str_replace(".0000","",$arrAPIResults["wms_data"][$product['model']]):"0");
                                        $availToOrderQty = (isset($arrAPIResults["wms_data2"][$product['model']])?str_replace(".0000","",$arrAPIResults["wms_data2"][$product['model']]):"0");
					$intNewWMSBalance = (isset($arrAPIResults["wms_data"][$product['model']])?str_replace(".0000","",$arrAPIResults["wms_data"][$product['model']]):"0");
				}
				
				if($product['quantity']>$currentStock && $availToOrderQty<$product['quantity']){
					$boolOrderQtyMoreThanWMSATO = true;
					//break;
				}
				elseif($product['quantity']<=$currentStock && $availToOrderQty<$product['quantity']){
					$boolOrderQtyLessThanWMSATO = true;
					//break;	
				}
				else{
					$boolOrderQtyMoreThanWMSATO = false;
					$boolOrderQtyLessThanWMSATO = false;
					//break;							
				}				
				
				$data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'product_type'     => ($product['data_source']==""||$product['data_source']=="0"?"Third-Party":"BRP"),
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'availToOrderQty'  => $availToOrderQty,
					'configure_delivery'=> $product['configure_delivery'],
					'total_prod_type'  => $order_info['total_prod_type'],
					'total_cd_none'	   => $order_info['total_cd_none'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'wms_balance'      => $intNewWMSBalance, // Auto adjust balances
					'has_moreQty'	   => $boolOrderQtyMoreThanWMSATO,
					'has_lessQty'	   => $boolOrderQtyLessThanWMSATO,
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], true)
				);
			}

			$data['vouchers'] = array();

			$vouchers = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'href'        => $this->url->link('sale/voucher/edit', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], true)
				);
			}

			$data['totals'] = array();

			$totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			$data['comment'] = nl2br($order_info['comment']);

			$this->load->model('customer/customer');

			$data['reward'] = $order_info['reward'];

			$data['reward_total'] = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

			$data['affiliate_firstname'] = $order_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $order_info['affiliate_lastname'];

			if ($order_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], true);
			} else {
				$data['affiliate'] = '';
			}

			$data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);

			$this->load->model('marketing/affiliate');

			$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);

			$this->load->model('localisation/order_status');

			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

			if ($order_status_info) {
				$data['order_status'] = $order_status_info['name'];
			} else {
				$data['order_status'] = '';
			}
			
			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			$data['order_status_id'] = $order_info['order_status_id'];

			$data['account_custom_field'] = $order_info['custom_field'];

			// Custom Fields
			$this->load->model('customer/custom_field');

			$data['account_custom_fields'] = array();

			$filter_data = array(
				'sort'  => 'cf.sort_order',
				'order' => 'ASC'
			);

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($order_info['custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['account_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['account_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['custom_field'][$custom_field['custom_field_id']]
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name']
							);
						}
					}
				}
			}

			// Custom fields
			$data['payment_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order'],
								'sort_unique_order'	 => $custom_field['sort_unique_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['payment_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order'],
									'sort_unique_order'	 => $custom_field['sort_unique_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['payment_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order'],
							'sort_unique_order'	 => $custom_field['sort_unique_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order'],
								'sort_unique_order'	 => $custom_field['sort_unique_order']
							);
						}
					}
				}
			}

			// Shipping
			$data['shipping_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order'],
								'sort_unique_order'	 => $custom_field['sort_unique_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['shipping_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order'],
									'sort_unique_order'	 => $custom_field['sort_unique_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['shipping_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['shipping_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order'],
							'sort_unique_order'	 => $custom_field['sort_unique_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order'],
								'sort_unique_order'	 => $custom_field['sort_unique_order']
							);
						}
					}
				}
			}

			$data['ip'] = $order_info['ip'];
			$data['forwarded_ip'] = $order_info['forwarded_ip'];
			$data['user_agent'] = $order_info['user_agent'];
			$data['accept_language'] = $order_info['accept_language'];

			// Additional Tabs
			$data['tabs'] = array();

			if ($this->user->hasPermission('access', 'extension/payment/' . $order_info['payment_code'])) {
				if (is_file(DIR_CATALOG . 'controller/extension/payment/' . $order_info['payment_code'] . '.php')) {
					$content = $this->load->controller('extension/payment/' . $order_info['payment_code'] . '/order');
				} else {
					$content = null;
				}

				if ($content) {
					$this->load->language('extension/payment/' . $order_info['payment_code']);

					$data['tabs'][] = array(
						'code'    => $order_info['payment_code'],
						'title'   => $this->language->get('heading_title'),
						'content' => $content
					);
				}
			}

			$this->load->model('extension/extension');

			$extensions = $this->model_extension_extension->getInstalled('fraud');

			foreach ($extensions as $extension) {
				if ($this->config->get($extension . '_status')) {
					$this->load->language('extension/fraud/' . $extension);

					$content = $this->load->controller('extension/fraud/' . $extension . '/order');

					if ($content) {
						$data['tabs'][] = array(
							'code'    => $extension,
							'title'   => $this->language->get('heading_title'),
							'content' => $content
						);
					}
				}
			}
			
			// The URL we send API requests to
			$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			
			// API login
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info) {
				$data['api_id'] = $api_info['api_id'];
				$data['api_key'] = $api_info['key'];
				$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
			} else {
				$data['api_id'] = '';
				$data['api_key'] = '';
				$data['api_ip'] = '';
			}
			
			$data['configure_delivery'] = ((isset($this->request->get['configure']) && $this->request->get['configure']=="1")?"1":"0");
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			if($isEmailed=="1") {
				$data['success_msg'] = "Email(s) has successfully sent.";
				if (isset($this->session->data['success_msg'])) {
					$data['success_msg'] .= "<br />".$this->session->data['success_msg'];
					unset($this->session->data['success_msg']);
				}
			} else if($isEmailed=="2") {
				$this->session->data['failure_msg'] = "Email(s) failed to send.";
				if (isset($this->session->data['success_msg'])) {
					$data['success_msg'] = "<br />".$this->session->data['success_msg'];
					unset($this->session->data['success_msg']);
				}
			} else if (isset($this->session->data['success_msg'])) {
				$data['success_msg'] = $this->session->data['success_msg'];
				unset($this->session->data['success_msg']);
			} if (isset($this->session->data['failure_msg'])) {
				$data['error_warning'] = $this->session->data['failure_msg'];
				unset($this->session->data['failure_msg']);
			} else {
				$data['error_warning'] = '';
			}
			
			if ($this->config->get('config_using_warehouse_module')) { // new added
				$data['config_using_warehouse_module'] = true;
			} else {
				$data['config_using_warehouse_module'] = false;
			}
			
			$this->response->setOutput($this->load->view('sale/upload_delivery_info', $data));
		} else {
			return new Action('error/not_found');
		}
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function createInvoiceNo() {
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (isset($this->request->get['order_id'])) {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$invoice_no = $this->model_sale_order->createInvoiceNo($order_id);

			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addReward() {
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info && $order_info['customer_id'] && ($order_info['reward'] > 0)) {
				$this->load->model('customer/customer');

				$reward_total = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($order_id);

				if (!$reward_total) {
					$this->model_customer_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['reward'], $order_id);
				}
			}

			$json['success'] = $this->language->get('text_reward_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeReward() {
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$this->model_customer_customer->deleteReward($order_id);
			}

			$json['success'] = $this->language->get('text_reward_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addCommission() {
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('marketing/affiliate');

				$affiliate_total = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($order_id);

				if (!$affiliate_total) {
					$this->model_marketing_affiliate->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);
				}
			}

			$json['success'] = $this->language->get('text_commission_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeCommission() {
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('marketing/affiliate');

				$this->model_marketing_affiliate->deleteTransaction($order_id);
			}

			$json['success'] = $this->language->get('text_commission_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history() {
		$this->load->language('sale/order');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_delivery_status'] = $this->language->get('column_delivery_status');
		$data['column_notify'] = $this->language->get('column_notify');
		$data['column_comment'] = $this->language->get('column_comment');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$this->load->model('sale/order');

		$results = $this->model_sale_order->getOrderHistories($this->request->get['order_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('sale/order/history', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/order_history', $data));
	}

	public function invoice() {
		$this->load->language('sale/order');

		$data['title'] = $this->language->get('text_invoice');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_invoice'] = $this->language->get('text_invoice');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_payment_address'] = $this->language->get('text_payment_address');
		$data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_comment'] = $this->language->get('text_comment');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');

		$this->load->model('sale/order');

		$this->load->model('setting/setting');

		$data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}

		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}' . "\n" . 'Telephone: {telephone}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}',
					'{telephone}'
				);

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country'],
					'telephone'   => $order_info['telephone']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}' . "\n" . 'Telephone: {telephone}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}',
					'{telephone}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country'],
					'telephone'   => $order_info['telephone']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

				$product_data = array();

				$products = $this->model_sale_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$voucher_data = array();

				$vouchers = $this->model_sale_order->getOrderVouchers($order_id);

				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$total_data = array();

				$totals = $this->model_sale_order->getOrderTotals($order_id);

				foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$data['orders'][] = array(
					'order_id'	       => $order_id,
					'unique_order_id'  => $order_info['unique_order_id'],
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       => $order_info['store_name'],
                                        'store_gst_no'       => $order_info['store_gst_no'],
					'store_url'        => rtrim($order_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'store_fax'        => $store_fax,
					'email'            => $order_info['email'],
					'telephone'        => $order_info['telephone'],
					'shipping_address' => $shipping_address,
					'shipping_method'  => $order_info['shipping_method'],
					'payment_address'  => $payment_address,
					'payment_method'   => $order_info['payment_method'],
					'product'          => $product_data,
					'voucher'          => $voucher_data,
					'total'            => $total_data,
					'comment'          => nl2br($order_info['comment'])
				);
			}
		}

		$this->response->setOutput($this->load->view('sale/order_invoice', $data));
	}

	public function shipping() {
		$this->load->language('sale/order');

		$data['title'] = $this->language->get('text_shipping');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_picklist'] = $this->language->get('text_picklist');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_sku'] = $this->language->get('text_sku');
		$data['text_upc'] = $this->language->get('text_upc');
		$data['text_ean'] = $this->language->get('text_ean');
		$data['text_jan'] = $this->language->get('text_jan');
		$data['text_isbn'] = $this->language->get('text_isbn');
		$data['text_mpn'] = $this->language->get('text_mpn');
		$data['text_comment'] = $this->language->get('text_comment');

		$data['column_location'] = $this->language->get('column_location');
		$data['column_reference'] = $this->language->get('column_reference');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_weight'] = $this->language->get('column_weight');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');

		$this->load->model('sale/order');

		$this->load->model('catalog/product');

		$this->load->model('setting/setting');

		$data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}

		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			// Make sure there is a shipping method
			if ($order_info && $order_info['shipping_code']) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}' . "\n" . 'Telephone: {telephone}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}',
					'{telephone}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country'],
					'telephone'   => $order_info['telephone']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

				$product_data = array();

				$products = $this->model_sale_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_weight = '';

					$product_info = $this->model_catalog_product->getProduct($product['product_id']);

					if ($product_info) {
						$option_data = array();

						$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

						foreach ($options as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
							} else {
								$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

								if ($upload_info) {
									$value = $upload_info['name'];
								} else {
									$value = '';
								}
							}

							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $value
							);

							$product_option_value_info = $this->model_catalog_product->getProductOptionValue($product['product_id'], $option['product_option_value_id']);

							if ($product_option_value_info) {
								if ($product_option_value_info['weight_prefix'] == '+') {
									$option_weight += $product_option_value_info['weight'];
								} elseif ($product_option_value_info['weight_prefix'] == '-') {
									$option_weight -= $product_option_value_info['weight'];
								}
							}
						}

						$product_data[] = array(
							'name'     => $product_info['name'],
							'model'    => $product_info['model'],
							'option'   => $option_data,
							'quantity' => $product['quantity'],
							'location' => $product_info['location'],
							'sku'      => $product_info['sku'],
							'upc'      => $product_info['upc'],
							'ean'      => $product_info['ean'],
							'jan'      => $product_info['jan'],
							'isbn'     => $product_info['isbn'],
							'mpn'      => $product_info['mpn'],
							'weight'   => $this->weight->format(($product_info['weight'] + $option_weight) * $product['quantity'], $product_info['weight_class_id'], $this->language->get('decimal_point'), $this->language->get('thousand_point'))
						);
					}
				}

				$data['orders'][] = array(
					'order_id'	       => $order_id,
					'unique_order_id'  => $order_info['unique_order_id'],
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       => $order_info['store_name'],
                                        'store_gst_no'       => $order_info['store_gst_no'],
					'store_url'        => rtrim($order_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'store_fax'        => $store_fax,
					'email'            => $order_info['email'],
					'telephone'        => $order_info['telephone'],
					'shipping_address' => $shipping_address,
					'shipping_method'  => $order_info['shipping_method'],
					'product'          => $product_data,
					'comment'          => nl2br($order_info['comment'])
				);
			}
		}

		$this->response->setOutput($this->load->view('sale/order_shipping', $data));
	}
}