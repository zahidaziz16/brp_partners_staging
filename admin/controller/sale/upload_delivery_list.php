<?php
class ControllerSaleUploadDeliveryList extends Controller {
	private $error = array();
	
	public function ajaxAPI($apitype = '', $params) {
		$array = array();
		if($apitype=="stock_levels") {
			//$this->load->model('sale/order');
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
			$this->load->model('sale/upload_delivery_list');
			$customer_id = ""; $order_id = "";
			if (isset($this->request->post['customer_id'])) { $customer_id = $this->request->post['customer_id'];
			} else if (isset($this->request->get['customer_id'])) { $customer_id = $this->request->get['customer_id'];
			} else if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
			
			if (isset($this->request->post['order_id'])) { $order_id = $this->request->post['order_id'];
			} else if (isset($this->request->get['order_id'])) { $order_id = $this->request->get['order_id'];
			} else if (isset($params['order_id'])) { $order_id = $params['order_id']; }
			
			//echo "<pre>";print_r($order_id);echo "</pre>";exit;
			//$customer_id = "GOS1|GOS2";	
			//$order_id = "D0001|D0002";	
			$customer_ids = ""; $order_ids = "";
			$customer_ids = explode("|",$customer_id);
			$order_ids = explode("|",$order_id);
			$dataJS = array();
			foreach($customer_ids as $key => $value) {
				$dataJS["TSHTable"][$key]["CustCode"] = (isset($customer_ids[$key])?$customer_ids[$key]:"");
				$dataJS["TSHTable"][$key]["OrderID"] = (isset($order_ids[$key])?$order_ids[$key]:"");
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
			$array = $this->model_sale_upload_delivery_list->coreAPI($apitype, $apiParams);
			//echo "<pre>";print_r($array);echo "</pre>";exit;
		}
		//echo "<pre>";print_r($array);echo "</pre>";exit;
		//Query Opencart - End
		else if($apitype=="upload_delivery_status") {
			$this->load->model('sale/upload_delivery_list');
			if (isset($this->request->post['order_id'])) { $order_id = $this->request->post['order_id'];
			} else if (isset($this->request->get['order_id'])) { $order_id = $this->request->get['order_id'];
			} else if (isset($params['order_id'])) { $order_id = $params['order_id']; }
			
			if (isset($this->request->post['customer_id'])) { $customer_id = $this->request->post['customer_id'];
			} else if (isset($this->request->get['customer_id'])) { $customer_id = $this->request->get['customer_id'];
			} else if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
			
			if (isset($this->request->post['unique_order_id'])) { $unique_order_id = $this->request->post['unique_order_id'];
			} else if (isset($this->request->get['unique_order_id'])) { $unique_order_id = $this->request->get['unique_order_id'];
			} else if (isset($params['unique_order_id'])) { $unique_order_id = $params['unique_order_id']; }
			
			if (isset($this->request->post['order_date'])) { $order_date = $this->request->post['order_date'];
			} else if (isset($this->request->get['order_date'])) { $order_date = $this->request->get['order_date'];
			} else if (isset($params['order_date'])) { $order_date = $params['order_date']; }
			
			if (isset($this->request->post['recipient_code'])) { $recipient_code = $this->request->post['recipient_code'];
			} else if (isset($this->request->get['recipient_code'])) { $recipient_code = $this->request->get['recipient_code'];
			} else if (isset($params['recipient_code'])) { $recipient_code = $params['recipient_code']; }
			
			if (isset($this->request->post['recipient_name'])) { $recipient_name = $this->request->post['recipient_name'];
			} else if (isset($this->request->get['recipient_name'])) { $recipient_name = $this->request->get['recipient_name'];
			} else if (isset($params['recipient_name'])) { $recipient_name = $params['recipient_name']; }
			
			if (isset($this->request->post['recipient_tel'])) { $recipient_tel = $this->request->post['recipient_tel'];
			} else if (isset($this->request->get['recipient_tel'])) { $recipient_tel = $this->request->get['recipient_tel'];
			} else if (isset($params['recipient_tel'])) { $recipient_tel = $params['recipient_tel']; }
			
			if (isset($this->request->post['recipient_add1'])) { $recipient_add1 = $this->request->post['recipient_add1'];
			} else if (isset($this->request->get['recipient_add1'])) { $recipient_add1 = $this->request->get['recipient_add1'];
			} else if (isset($params['recipient_add1'])) { $recipient_add1 = $params['recipient_add1']; }
			
			if (isset($this->request->post['recipient_add2'])) { $recipient_add2 = $this->request->post['recipient_add2'];
			} else if (isset($this->request->get['recipient_add2'])) { $recipient_add2 = $this->request->get['recipient_add2'];
			} else if (isset($params['recipient_add2'])) { $recipient_add2 = $params['recipient_add2']; }
			
			if (isset($this->request->post['recipient_add3'])) { $recipient_add3 = $this->request->post['recipient_add3'];
			} else if (isset($this->request->get['recipient_add3'])) { $recipient_add3 = $this->request->get['recipient_add3'];
			} else if (isset($params['recipient_add3'])) { $recipient_add3 = $params['recipient_add3']; }
			
			if (isset($this->request->post['recipient_add4'])) { $recipient_add4 = $this->request->post['recipient_add4'];
			} else if (isset($this->request->get['recipient_add4'])) { $recipient_add4 = $this->request->get['recipient_add4'];
			} else if (isset($params['recipient_add4'])) { $recipient_add4 = $params['recipient_add4']; }

			if (isset($this->request->post['recipient_add5'])) { $recipient_add5 = $this->request->post['recipient_add5'];
			} else if (isset($this->request->get['recipient_add5'])) { $recipient_add5 = $this->request->get['recipient_add5'];
			} else if (isset($params['recipient_add5'])) { $recipient_add5 = $params['recipient_add5']; }
			
			if (isset($this->request->post['item_code'])) { $item_code = $this->request->post['item_code'];
			} else if (isset($this->request->get['item_code'])) { $item_code = $this->request->get['item_code'];
			} else if (isset($params['item_code'])) { $item_code = $params['item_code']; }
			
			if (isset($this->request->post['order_qty'])) { $order_qty = $this->request->post['order_qty'];
			} else if (isset($this->request->get['order_qty'])) { $order_qty = $this->request->get['order_qty'];
			} else if (isset($params['order_qty'])) { $order_qty = $params['order_qty']; }
			
			if (isset($this->request->post['postcode'])) { $postcode = $this->request->post['postcode'];
			} else if (isset($this->request->get['postcode'])) { $postcode = $this->request->get['postcode'];
			} else if (isset($params['postcode'])) { $postcode = $params['postcode']; }
			
			$order_ids = ""; $customer_ids = ""; $unique_order_ids = ""; $order_dates = ""; $recipient_codes = ""; $recipient_names = ""; $recipient_tels = "";
			$recipient_add1s = ""; $recipient_add2s = ""; $recipient_add3s = ""; $recipient_add4s = ""; $recipient_add5s = ""; $item_codes = ""; $order_qtys = ""; $postcodes = "";
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
			$order_qtys = explode("|",$order_qty);
			$postcodes = explode("|",$postcode);
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
				$dataJS["TSHTable"][$key]["OrderQty"] = (isset($order_qtys[$key])?$order_qtys[$key]:"");
				$dataJS["TSHTable"][$key]["Postcode"] = (isset($postcodes[$key])?$postcodes[$key]:"");
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
			$apiParams['order_qty'] = $order_qty;
			$apiParams['postcode'] = $postcode;
			$apiParams['json_data'] = $strJson;
						
			//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
			$array = $this->model_sale_upload_delivery_list->coreAPI($apitype, $apiParams);
			//echo "<pre>";print_r($array);echo "</pre>";exit;
		} else if($apitype=="upload_gohoffice_delivery_status") {
			$this->load->model('sale/upload_delivery_list');
			if (isset($this->request->post['order_id'])) { $order_id = $this->request->post['order_id'];
			} else if (isset($this->request->get['order_id'])) { $order_id = $this->request->get['order_id'];
			} else if (isset($params['order_id'])) { $order_id = $params['order_id']; }
			
			if (isset($this->request->post['customer_id'])) { $customer_id = $this->request->post['customer_id'];
			} else if (isset($this->request->get['customer_id'])) { $customer_id = $this->request->get['customer_id'];
			} else if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
			
			if (isset($this->request->post['unique_order_id'])) { $unique_order_id = $this->request->post['unique_order_id'];
			} else if (isset($this->request->get['unique_order_id'])) { $unique_order_id = $this->request->get['unique_order_id'];
			} else if (isset($params['unique_order_id'])) { $unique_order_id = $params['unique_order_id']; }
			
			if (isset($this->request->post['order_date'])) { $order_date = $this->request->post['order_date'];
			} else if (isset($this->request->get['order_date'])) { $order_date = $this->request->get['order_date'];
			} else if (isset($params['order_date'])) { $order_date = $params['order_date']; }
			
			if (isset($this->request->post['recipient_code'])) { $recipient_code = $this->request->post['recipient_code'];
			} else if (isset($this->request->get['recipient_code'])) { $recipient_code = $this->request->get['recipient_code'];
			} else if (isset($params['recipient_code'])) { $recipient_code = $params['recipient_code']; }
			
			if (isset($this->request->post['recipient_name'])) { $recipient_name = $this->request->post['recipient_name'];
			} else if (isset($this->request->get['recipient_name'])) { $recipient_name = $this->request->get['recipient_name'];
			} else if (isset($params['recipient_name'])) { $recipient_name = $params['recipient_name']; }
			
			if (isset($this->request->post['recipient_tel'])) { $recipient_tel = $this->request->post['recipient_tel'];
			} else if (isset($this->request->get['recipient_tel'])) { $recipient_tel = $this->request->get['recipient_tel'];
			} else if (isset($params['recipient_tel'])) { $recipient_tel = $params['recipient_tel']; }
			
			if (isset($this->request->post['recipient_add1'])) { $recipient_add1 = $this->request->post['recipient_add1'];
			} else if (isset($this->request->get['recipient_add1'])) { $recipient_add1 = $this->request->get['recipient_add1'];
			} else if (isset($params['recipient_add1'])) { $recipient_add1 = $params['recipient_add1']; }
			
			if (isset($this->request->post['recipient_add2'])) { $recipient_add2 = $this->request->post['recipient_add2'];
			} else if (isset($this->request->get['recipient_add2'])) { $recipient_add2 = $this->request->get['recipient_add2'];
			} else if (isset($params['recipient_add2'])) { $recipient_add2 = $params['recipient_add2']; }
			
			if (isset($this->request->post['recipient_add3'])) { $recipient_add3 = $this->request->post['recipient_add3'];
			} else if (isset($this->request->get['recipient_add3'])) { $recipient_add3 = $this->request->get['recipient_add3'];
			} else if (isset($params['recipient_add3'])) { $recipient_add3 = $params['recipient_add3']; }
			
			if (isset($this->request->post['recipient_add4'])) { $recipient_add4 = $this->request->post['recipient_add4'];
			} else if (isset($this->request->get['recipient_add4'])) { $recipient_add4 = $this->request->get['recipient_add4'];
			} else if (isset($params['recipient_add4'])) { $recipient_add4 = $params['recipient_add4']; }

			if (isset($this->request->post['recipient_add5'])) { $recipient_add5 = $this->request->post['recipient_add5'];
			} else if (isset($this->request->get['recipient_add5'])) { $recipient_add5 = $this->request->get['recipient_add5'];
			} else if (isset($params['recipient_add5'])) { $recipient_add5 = $params['recipient_add5']; }
			
			if (isset($this->request->post['item_code'])) { $item_code = $this->request->post['item_code'];
			} else if (isset($this->request->get['item_code'])) { $item_code = $this->request->get['item_code'];
			} else if (isset($params['item_code'])) { $item_code = $params['item_code']; }
			
			if (isset($this->request->post['matching_code'])) { $matching_code = $this->request->post['matching_code'];
			} else if (isset($this->request->get['matching_code'])) { $matching_code = $this->request->get['matching_code'];
			} else if (isset($params['matching_code'])) { $matching_code = $params['matching_code']; }
			
			if (isset($this->request->post['order_qty'])) { $order_qty = $this->request->post['order_qty'];
			} else if (isset($this->request->get['order_qty'])) { $order_qty = $this->request->get['order_qty'];
			} else if (isset($params['order_qty'])) { $order_qty = $params['order_qty']; }
			
			if (isset($this->request->post['postcode'])) { $postcode = $this->request->post['postcode'];
			} else if (isset($this->request->get['postcode'])) { $postcode = $this->request->get['postcode'];
			} else if (isset($params['postcode'])) { $postcode = $params['postcode']; }
		
			if (isset($this->request->post['recipient_orderdate'])) { $recipient_orderdate = $this->request->post['recipient_orderdate'];
			} else if (isset($this->request->get['recipient_orderdate'])) { $recipient_orderdate = $this->request->get['recipient_orderdate'];
			} else if (isset($params['recipient_orderdate'])) { $recipient_orderdate = $params['recipient_orderdate']; }
			
			if (isset($this->request->post['company_name'])) { $recipient_companyname = $this->request->post['company_name'];
			} else if (isset($this->request->get['company_name'])) { $recipient_companyname = $this->request->get['company_name'];
			} else if (isset($params['company_name'])) { $recipient_companyname = $params['company_name']; }
			
			if (isset($this->request->post['recipient_shipping_add1'])) { $recipient_shipping_add1 = $this->request->post['recipient_shipping_add1'];
			} else if (isset($this->request->get['recipient_shipping_add1'])) { $recipient_shipping_add1 = $this->request->get['recipient_shipping_add1'];
			} else if (isset($params['recipient_shipping_add1'])) { $recipient_shipping_add1 = $params['recipient_shipping_add1']; }
		
			if (isset($this->request->post['recipient_shipping_add2'])) { $recipient_shipping_add2 = $this->request->post['recipient_shipping_add2'];
			} else if (isset($this->request->get['recipient_shipping_add2'])) { $recipient_shipping_add2 = $this->request->get['recipient_shipping_add2'];
			} else if (isset($params['recipient_shipping_add2'])) { $recipient_shipping_add2 = $params['recipient_shipping_add2']; }
			
			if (isset($this->request->post['recipient_shipping_city'])) { $recipient_shipping_city = $this->request->post['recipient_shipping_city'];
			} else if (isset($this->request->get['recipient_shipping_city'])) { $recipient_shipping_city = $this->request->get['recipient_shipping_city'];
			} else if (isset($params['recipient_shipping_city'])) { $recipient_shipping_city = $params['recipient_shipping_city']; }
			
			if (isset($this->request->post['recipient_shipping_postcode'])) { $recipient_shipping_postcode = $this->request->post['recipient_shipping_postcode'];
			} else if (isset($this->request->get['recipient_shipping_postcode'])) { $recipient_shipping_postcode = $this->request->get['recipient_shipping_postcode'];
			} else if (isset($params['recipient_shipping_postcode'])) { $recipient_shipping_postcode = $params['recipient_shipping_postcode']; }
					
			if (isset($this->request->post['recipient_shipping_state'])) { $recipient_shipping_state = $this->request->post['recipient_shipping_state'];
			} else if (isset($this->request->get['recipient_shipping_state'])) { $recipient_shipping_state = $this->request->get['recipient_shipping_state'];
			} else if (isset($params['recipient_shipping_state'])) { $recipient_shipping_state = $params['recipient_shipping_state']; }
			
			if (isset($this->request->post['recipient_shipping_country'])) { $recipient_shipping_country = $this->request->post['recipient_shipping_country'];
			} else if (isset($this->request->get['recipient_shipping_country'])) { $recipient_shipping_country = $this->request->get['recipient_shipping_country'];
			} else if (isset($params['recipient_shipping_country'])) { $recipient_shipping_country = $params['recipient_shipping_country']; }
			
			if (isset($this->request->post['first_name'])) { $recipient_firstname = $this->request->post['first_name'];
			} else if (isset($this->request->get['first_name'])) { $recipient_firstname = $this->request->get['first_name'];
			} else if (isset($params['first_name'])) { $recipient_firstname = $params['first_name']; }
	
			if (isset($this->request->post['last_name'])) { $recipient_lastname = $this->request->post['last_name'];
			} else if (isset($this->request->get['last_name'])) { $recipient_lastname = $this->request->get['last_name'];
			} else if (isset($params['last_name'])) { $recipient_lastname = $params['last_name']; }
			
			if (isset($this->request->post['email'])) { $recipient_email = $this->request->post['email'];
			} else if (isset($this->request->get['email'])) { $recipient_email = $this->request->get['email'];
			} else if (isset($params['email'])) { $recipient_email = $params['email']; }
			
			if (isset($this->request->post['tel'])) { $recipient_tel = $this->request->post['tel'];
			} else if (isset($this->request->get['tel'])) { $recipient_tel = $this->request->get['tel'];
			} else if (isset($params['tel'])) { $recipient_tel = $params['tel']; }
			
			if (isset($this->request->post['fax'])) { $recipient_fax = $this->request->post['fax'];
			} else if (isset($this->request->get['fax'])) { $recipient_fax = $this->request->get['fax'];
			} else if (isset($params['fax'])) { $recipient_fax = $params['fax']; }
			
			$order_ids = ""; $customer_ids = ""; $unique_order_ids = ""; $order_dates = ""; $recipient_codes = ""; $recipient_names = ""; $recipient_tels = "";
			$recipient_add1s = ""; $recipient_add2s = ""; $recipient_add3s = ""; $recipient_add4s = ""; $recipient_add5s = ""; $item_codes = ""; $matching_codes = ""; $order_qtys = ""; $postcodes = "";
			$recipient_orderdates = ""; $recipient_companynames = ""; $recipient_shipping_add1s = ""; $recipient_shipping_add2s = ""; $recipient_shipping_citys = ""; $recipient_shipping_postcodes = ""; $recipient_shipping_states = ""; $recipient_shipping_countrys = ""; $recipient_firstnames = ""; $recipient_lastnames = ""; $recipient_emails = ""; $recipient_tels = ""; $recipient_faxs = "";

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
				$dataJS["TSHTable"][$key]["RecipientOrderDate"] = (isset($recipient_orderdates[$key])?$recipient_orderdates[$key]:"");
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
			$apiParams['json_data'] = $strJson;
						
			//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
			$array = $this->model_sale_upload_delivery_list->coreAPI($apitype, $apiParams);
			//echo "<pre>";print_r($array);echo "</pre>";exit;
		} else if($apitype=="cancel_delivery_list") {
			$this->load->model('sale/upload_delivery_list');
			if (isset($this->request->post['order_id'])) { $order_id = $this->request->post['order_id'];
			} else if (isset($this->request->get['order_id'])) { $order_id = $this->request->get['order_id'];
			} else if (isset($params['order_id'])) { $order_id = $params['order_id']; }
			
			if (isset($this->request->post['customer_id'])) { $customer_id = $this->request->post['customer_id'];
			} else if (isset($this->request->get['customer_id'])) { $customer_id = $this->request->get['customer_id'];
			} else if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
			
			if (isset($this->request->post['unique_order_id'])) { $unique_order_id = $this->request->post['unique_order_id'];
			} else if (isset($this->request->get['unique_order_id'])) { $unique_order_id = $this->request->get['unique_order_id'];
			} else if (isset($params['unique_order_id'])) { $unique_order_id = $params['unique_order_id']; }
			
			$order_ids = ""; $customer_ids = ""; $unique_order_ids = "";
			$order_ids = explode("|",$order_id);
			$customer_ids = explode("|",$customer_id);
			$unique_order_ids = explode("|",$unique_order_id);
			$dataJS = array();
			foreach($customer_ids as $key => $value) {
				$dataJS["TSHTable"][$key]["CustCode"] = (isset($customer_ids[$key])?$customer_ids[$key]:"");
				$dataJS["TSHTable"][$key]["Docno"] = (isset($unique_order_ids[$key])?$unique_order_ids[$key]:"");
			}
			$strJson = json_encode($dataJS);
			
			$apiParams = array();
			$apiParams['order_id'] = $order_id;
			$apiParams['customer_id'] = $customer_id;
			$apiParams['unique_order_id'] = $unique_order_id;
			$apiParams['json_data'] = $strJson;
						
			//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
			$array = $this->model_sale_upload_delivery_list->coreAPI($apitype, $apiParams);
			//echo "<pre>";print_r($array);echo "</pre>";exit;
		}
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
	
	public function sync() {
		$this->load->model('sale/order');
		$this->load->language('sale/upload_delivery_list');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/product');
		if (isset($this->request->get['submit_of'])) {
			$submit_of = $this->request->get['submit_of'];
		} else if (isset($this->request->post['submit_of'])) {
			$submit_of = $this->request->post['submit_of'];
		} else {
			$submit_of = "";
		}
		if (isset($this->request->get['sync_orders'])) {
			$sync_orders = $this->request->get['sync_orders'];
		} else if (isset($this->request->post['selected'])) {
			$sync_orders = implode("|", (array)$this->request->post['selected']);
		} else {
			$sync_orders = "";
		}
		//echo "<pre>";print_r($submit_of);echo "</pre>";exit;
		
		if($submit_of!="" && $sync_orders!="") {
			$this->load->model('sale/upload_delivery_list');
			
			$arrOrderIDs = array();
			$arrOrderMsgs = array();
			$strCustomMsg = "";
			if($sync_orders!="") {
				$arrOrderIDs = explode("|", $sync_orders);
				asort($arrOrderIDs);
				//echo "<pre>";print_r($arrOrderIDs);echo "</pre>";exit;
				
				$products = $this->model_sale_order->getMultipleOrderProducts($arrOrderIDs); //$result['order_id']
				$apiParams = $products;
				$arrAPISLResults = $this->ajaxAPI('stock_levels', $apiParams);
				//echo "<pre>";print_r($arrAPISLResults);echo "</pre>";exit;
				//$arrAPISLResults["wms_data"]["B LC-37BK"] = 6;
				//$arrAPISLResults["wms_data"]["B11-59"] = 6;
				$isGotSuccess = false;
				$arrErrors = array();
				foreach($arrOrderIDs as $orderIDKey => $orderIDValue) {
					
					$orderProductsValidation = array();
					$boolCanBRPWarehouseSyncAllOrders = true;
					$boolCanGohofficecomSyncAllOrders = true;
					if($submit_of=="Gohoffice") { // Gohoffice Validation
						$orderProductsValidation = $this->model_sale_upload_delivery_list->getOrderProducts(" AND op.`order_id` = '".$orderIDValue."' ORDER BY op.order_product_id ASC");
						foreach($orderProductsValidation as $validationKey => $validationValue) {
							if($validationValue["data_source"]=="" || $validationValue["data_source"]=="0") {
								$boolCanGohofficecomSyncAllOrders = false;
							}
							$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$orderIDValue."' AND `order_product_id` = '".$validationValue["order_product_id"]."' ", "Gohoffice");
						}
					} else if($submit_of=="BRP Warehouse") { // BRP Warehouse Validation
						$products = $this->model_sale_order->getOrderProducts($orderIDValue);
						//echo "<pre>";print_r($products);echo "</pre>";exit;
						$boolHasZeroWMSQty = false;
						$boolOrderQtyMoreThanWMSATO = false;
						$boolOrderQtyLessThanWMSATO = false;
						foreach ($products as $product) {

$product['model'] = html_entity_decode($product['model']);
                                                    $product['matching_code'] = html_entity_decode($product['matching_code']);

							if($this->config->get('config_using_warehouse_module')) {
								if(!isset($arrAPISLResults["wms_data"][$product['matching_code']])/* || (isset($arrAPISLResults["wms_data"][$product['matching_code']]) && str_replace(".0000","",$arrAPISLResults["wms_data"][$product['matching_code']])<$product['quantity'])*/) {
									$boolCanBRPWarehouseSyncAllOrders = false;
								}
								if(isset($arrAPISLResults["wms_data"][$product['matching_code']]) && (int)$arrAPISLResults["wms_data"][$product['matching_code']]==0) {
									$boolCanBRPWarehouseSyncAllOrders = false;
								} else if(isset($arrAPISLResults["wms_data"][$product['matching_code']]) && (int)$arrAPISLResults["wms_data"][$product['matching_code']]>0 && str_replace(".0000","",$arrAPISLResults["wms_data"][$product['matching_code']])<$product['quantity']) {
									//$submit_of = "BRP Warehouse Gohoffice";
								} else if(isset($arrAPISLResults["wms_data"][$product['matching_code']]) && (int)$arrAPISLResults["wms_data"][$product['matching_code']]>0 && str_replace(".0000","",$arrAPISLResults["wms_data"][$product['matching_code']])>=$product['quantity']) {
									//$submit_of = "BRP Warehouse";
								}
								//echo $arrAPISLResults["wms_data"][$product['matching_code']]." :::: ".$product['quantity']."<br />";
								// Quick update WMS Balance and configure delivery type - Start
								$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$orderIDValue."' AND `order_product_id` = '".$product["order_product_id"]."' ", "BRP Warehouse", (int)$arrAPISLResults["wms_data"][$product['matching_code']]);
								// Quick update WMS Balance and configure delivery type - End
							} else {
								$boolCanBRPWarehouseSyncAllOrders = false;
							}
						}
						if($product['data_source']!="" && $product['data_source']!="0") {
                                                    if(isset($arrAPISLResults["wms_data"][$product['matching_code']])) {
                                                        $currentStock = $arrAPISLResults["wms_data"][$product['matching_code']];
                                                        $availToOrderQty = $arrAPISLResults["wms_data2"][$product['matching_code']];
                                                    }
                                                }else {
                                                    if(isset($arrAPISLResults["wms_data"][$product['model']])) {
                                                        $currentStock = $arrAPISLResults["wms_data"][$product['model']];
                                                        $availToOrderQty = $arrAPISLResults["wms_data2"][$product['model']];
                                                    }
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
							if($product['data_source']!="" && $product['data_source']!="0") {
                                $arrAPISLResults["wms_data2"][$product['matching_code']] = $arrAPISLResults["wms_data2"][$product['matching_code']] - $product['quantity'];
                            }else {
                                $arrAPISLResults["wms_data2"][$product['model']] = $arrAPISLResults["wms_data2"][$product['model']] - $product['quantity'];
                            }
							//break;							
						}
						
						
					} else if($submit_of=="BRP Warehouse Gohoffice") { // BRP Warehouse Validation
						$products = $this->model_sale_order->getOrderProducts($orderIDValue);
						//echo "<pre>";print_r($products);echo "</pre>";exit;
						$boolHasZeroWMSQty = false;
						foreach ($products as $product) {
							if($this->config->get('config_using_warehouse_module')) {
								if(!isset($arrAPISLResults["wms_data"][$product['matching_code']])/* || (isset($arrAPISLResults["wms_data"][$product['matching_code']]) && str_replace(".0000","",$arrAPISLResults["wms_data"][$product['matching_code']])<$product['quantity'])*/) {
									$boolCanBRPWarehouseSyncAllOrders = false;
								}
								if(isset($arrAPISLResults["wms_data"][$product['matching_code']]) && (int)$arrAPISLResults["wms_data"][$product['matching_code']]==0) {
									$boolCanBRPWarehouseSyncAllOrders = false;
								} else if(isset($arrAPISLResults["wms_data"][$product['matching_code']]) && (int)$arrAPISLResults["wms_data"][$product['matching_code']]>0 && str_replace(".0000","",$arrAPISLResults["wms_data"][$product['matching_code']])<$product['quantity']) {
									//$submit_of = "BRP Warehouse Gohoffice";
								} else if(isset($arrAPISLResults["wms_data"][$product['matching_code']]) && (int)$arrAPISLResults["wms_data"][$product['matching_code']]>0 && str_replace(".0000","",$arrAPISLResults["wms_data"][$product['matching_code']])>=$product['quantity']) {
									//$submit_of = "BRP Warehouse";
								}
								//echo $arrAPISLResults["wms_data"][$product['matching_code']]." :::: ".$product['quantity']."<br />";
								// Quick update WMS Balance and configure delivery type - Start
								$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$orderIDValue."' AND `order_product_id` = '".$product["order_product_id"]."' ", "BRP Warehouse Gohoffice", (int)$arrAPISLResults["wms_data"][$product['matching_code']]);
								// Quick update WMS Balance and configure delivery type - End
							} else {
								$boolCanBRPWarehouseSyncAllOrders = false;
							}
						}
					} else if($submit_of=="Own Arrangement") { // Own Arrangement Validation
						$products = $this->model_sale_order->getOrderProducts($orderIDValue);
						foreach ($products as $product) {
							$productInfo = $this->model_catalog_product->getProduct($product['product_id']);
							$finalQty = $productInfo['quantity'] - $product['quantity'];
							if($product['configure_delivery'] != 'Own Arrangement'){
								$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '".$finalQty."' WHERE product_id = '" .$productInfo['product_id']. "'");
								$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_product_id` = '".$product['order_product_id']."' ", "Own Arrangement", $product['wms_quantity']); // Update the configure delivery to Own Arrangement
							}
						}						
					}
					
					
					$arrConfigureDeliveryLoop = array("BRP Warehouse", "Gohoffice"/*, "BRP Warehouse Gohoffice", "Own Arrangement"*/);
					foreach($arrConfigureDeliveryLoop as $keyCDLoop => $valueCDLoop) {
						if($valueCDLoop=="BRP Warehouse") {
							$apitype = "upload_delivery_status";
						} else {
							$apitype = "upload_gohoffice_delivery_status";
						}
						
						if($submit_of=="Gohoffice" && $valueCDLoop!="Gohoffice") {
							continue;
						} else if($submit_of=="BRP Warehouse" && $valueCDLoop!="BRP Warehouse") {
							continue;
						}
						
						//$results = $this->model_sale_upload_delivery_list->getOrders(array(), " AND o.order_id IN ('".implode("','",$arrOrderIDs)."')");
						$results = $this->model_sale_upload_delivery_list->getOrders(array(), " AND o.order_id IN ('".$orderIDValue."')");
						//echo "<pre>";print_r($results[0]["unique_order_id"]);echo "</pre>";exit;
						
						if($submit_of=="Gohoffice" && !$boolCanGohofficecomSyncAllOrders) {
							$arrOrderMsgs[] = "<font color='red'>Order ID: ".$results[0]["unique_order_id"]." could not be synced for Gohoffice.com delivery. Some products in the order appear to be third-party products which cannot be fulfilled by Gohoffice.com. Please check and try again.</font>";
							$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$orderIDValue."' ", "None");
							continue 2;
						} else if($submit_of=="Gohoffice" && $boolCanGohofficecomSyncAllOrders) {
							$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Pending", array($orderIDValue));
							$arrOrderMsgs[] = "Order ID: ".$results[0]["unique_order_id"]." successfully synced for Gohoffice.com delivery.";	
						}

						if($submit_of=="BRP Warehouse" && $boolCanBRPWarehouseSyncAllOrders && $boolOrderQtyMoreThanWMSATO) {
							$arrOrderMsgs[] = "<font color='red'>Order ID: ".$results[0]["unique_order_id"]." could not be synced for BRP Warehouse delivery. Please check and try again.</font>"; // Some products in the order do not appear to have sufficient stock in the BRP Warehouse. 
							$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$orderIDValue."' ", "None");
							continue 2;
						} else if($submit_of=="BRP Warehouse" && $boolCanBRPWarehouseSyncAllOrders && $boolOrderQtyLessThanWMSATO) {
							$arrOrderMsgs[] = "<font color='red'>Order ID: ".$results[0]["unique_order_id"]." could not be synced for BRP Warehouse delivery. There is enough inventory in stock but some have been booked by a previous order. The quantity available for delivery will only be updated when previous orders have been shipped. Please restock or cancel the previous order to proceed with this order. Please view the order details to see available amounts and current stocks.</font>"; // Some products in the order do not appear to have sufficient stock in the BRP Warehouse. 
							$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$orderIDValue."' ", "None");
							continue 2;
						} else if($submit_of=="BRP Warehouse" && $boolCanBRPWarehouseSyncAllOrders) {
							$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Pending", array($orderIDValue));
							$arrOrderMsgs[] = "Order ID: ".$results[0]["unique_order_id"]." successfully synced for BRP Warehouse delivery.";
						}						

						if($submit_of=="Own Arrangement") {
							$arrOrderMsgs[] = "Order ID: ".$results[0]["unique_order_id"]." successfully selected for Own Arrangement delivery.";
							continue 2;
							
						}



						if($submit_of=="BRP Warehouse Gohoffice" && !$boolCanBRPWarehouseSyncAllOrders) {
							$arrOrderMsgs[] = "<font color='red'>Order ID: ".$results[0]["unique_order_id"]." could not be synced for BRP Warehouse & Remainder by Gohoffice.com delivery. Please check and try again.</font>"; // Some products in the order do not appear to have sufficient stock in the BRP Warehouse. 
							$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$orderIDValue."' ", "None");
							continue 2;
						} else if($submit_of=="BRP Warehouse Gohoffice" && $boolCanBRPWarehouseSyncAllOrders) {
							$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Pending", array($orderIDValue));
							$arrOrderMsgs[] = "Order ID: ".$results[0]["unique_order_id"]." successfully synced for BRP Warehouse & Remainder by Gohoffice.com delivery.";
						}
						//echo $arrOrderMsgs[0]."<br />";exit;
						
						$isGotSuccess = true;
						//echo "<pre>";print_r($arrOrderMsgs);echo "</pre>";
						//if($orderIDValue==31) exit;
						//else continue;
						
						// WMS Function Call - Start
						/*$order_ids = "";
						$customer_ids = "";
						$unique_order_ids = "";
						$order_dates = "";
						$recipient_codes = "";
						$recipient_names = "";
						$recipient_tels = "";
						$recipient_add1s = "";
						$recipient_add2s = "";
						$recipient_add3s = "";
						$recipient_add4s = "";
						$recipient_add5s = "";
						$item_codes = "";
						$order_qtys = "";
						$postcodes = "";
						if($apitype=="upload_gohoffice_delivery_status") {
							$matching_code = "";
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
							////$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$result["order_id"]."' ", $submit_of); // Update the configure delivery to BRP Warehouse
							
							$orderProducts = $this->model_sale_upload_delivery_list->getOrderProducts(" AND op.`order_id` = '".$result["order_id"]."' AND (op.`configure_delivery`='".$valueCDLoop."' OR op.`configure_delivery`='BRP Warehouse Gohoffice') ORDER BY op.order_product_id ASC");
							foreach ($orderProducts as $product) {
								//echo "<pre>x";print_r($result);echo "</pre>";
								if($customer_ids!="") {
									$order_ids .= "|" . $result['order_id'];
									$customer_ids .= "|" . $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
									$unique_order_ids .= "|" . $result['unique_order_id'];
									$order_dates .= "|" . date("d/m/Y", strtotime($result['date_added']));
									$recipient_codes .= "|" . $result['customer_code'];
									$recipient_names .= "|" . $result['customer'];
									$recipient_tels .= "|" . $result['telephone'];
									$recipient_add1s .= "|" . $result['address1'];
									$recipient_add2s .= "|" . $result['address2'];
									$recipient_add3s .= "|" . $result['address3'];
									$recipient_add4s .= "|" . $result['address4'];
									$recipient_add5s .= "|" . $result['address5'];
									if($product['data_source']!="" && $product['data_source']!="0") {
										$item_codes .= "|" . $product['matching_code'];
									} else {
										$item_codes .= "|" . $product['model'];
									}
									// Logic for BRP Warehouse Gohoffice - start
									if($submit_of=="BRP Warehouse Gohoffice" && $valueCDLoop=="BRP Warehouse" && $product['configure_delivery']=="BRP Warehouse Gohoffice") {
										$order_qtys .= "|" . (int)$product['wms_quantity'];
									} else if($submit_of=="BRP Warehouse Gohoffice" && $valueCDLoop=="Gohoffice" && $product['configure_delivery']=="BRP Warehouse Gohoffice") {
										$order_qtys .= "|" . ((int)$product['quantity']-(int)$product['wms_quantity']);
									} else {
										$order_qtys .= "|" . (int)$product['quantity'];
									}
									$postcodes .= "|" . $result['shipping_postcode'];
									// Logic for BRP Warehouse Gohoffice - End
									if($apitype=="upload_gohoffice_delivery_status") {
										$matching_code .= "|" . $product['matching_code'];
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
									$order_ids .= $result['order_id'];
									$customer_ids .= $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
									$unique_order_ids .= $result['unique_order_id'];
									$order_dates = date("d/m/Y", strtotime($result['date_added']));
									$recipient_codes = $result['customer_code'];
									$recipient_names = $result['customer'];
									$recipient_tels = $result['telephone'];
									$recipient_add1s = $result['address1'];
									$recipient_add2s = $result['address2'];
									$recipient_add3s = $result['address3'];
									$recipient_add4s = $result['address4'];
									$recipient_add5s = $result['address5'];
									if($product['data_source']!="" && $product['data_source']!="0") {
										$item_codes = $product['matching_code'];
									} else {
										$item_codes = $product['model'];
									}
									// Logic for BRP Warehouse Gohoffice - start
									if($submit_of=="BRP Warehouse Gohoffice" && $valueCDLoop=="BRP Warehouse" && $product['configure_delivery']=="BRP Warehouse Gohoffice") {
										$order_qtys = $product['wms_quantity'];
									} else if($submit_of=="BRP Warehouse Gohoffice" && $valueCDLoop=="Gohoffice" && $product['configure_delivery']=="BRP Warehouse Gohoffice") {
										$order_qtys = $product['quantity']-$product['wms_quantity'];
									} else {
										$order_qtys = $product['quantity'];
									}
									$postcodes = $result['shipping_postcode'];
									// Logic for BRP Warehouse Gohoffice - End
									if($apitype=="upload_gohoffice_delivery_status") {
										$matching_code = $product['matching_code'];
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
						if($order_ids!="") {
							if($apitype=="upload_gohoffice_delivery_status") {
								$apiParams = array(
								   "order_id"=>$order_ids, 
								   "customer_id"=>$customer_ids, 
								   "unique_order_id"=>$unique_order_ids, 
								   "order_date"=>$order_dates, 
								   "recipient_code"=>$recipient_codes, 
								   "recipient_name"=>$recipient_names, 
								   "recipient_tel"=>$recipient_tels, 
								   "recipient_add1"=>$recipient_add1s, 
								   "recipient_add2"=>$recipient_add2s, 
								   "recipient_add3"=>$recipient_add3s, 
								   "recipient_add4"=>$recipient_add4s, 
								   "recipient_add5"=>$recipient_add5s, 
								   "item_code"=>$item_codes, 
								   "matching_code"=>$matching_code, 
								   "order_qty"=>$order_qtys, 
								   "postcode"=>$postcodes, 
								   "recipient_orderdate"=>$recipient_orderdate, 
								   "company_name"=>$recipient_companyname, 
								   "recipient_shipping_add1"=>$recipient_shipping_add1, 
								   "recipient_shipping_add2"=>$recipient_shipping_add2, 
								   "recipient_shipping_city"=>$recipient_shipping_city, 
								   "recipient_shipping_postcode"=>$recipient_shipping_postcode, 
								   "recipient_shipping_state"=>$recipient_shipping_state, 
								   "recipient_shipping_country"=>$recipient_shipping_country, 
								   "first_name"=>$recipient_firstname, 
								   "last_name"=>$recipient_lastname, 
								   "email"=>$recipient_email, 
								   "tel"=>$recipient_tel, 
								   "fax"=>$recipient_fax
								);
							} else {
								$apiParams = array(
								   "order_id"=>$order_ids, 
								   "customer_id"=>$customer_ids, 
								   "unique_order_id"=>$unique_order_ids, 
								   "order_date"=>$order_dates, 
								   "recipient_code"=>$recipient_codes, 
								   "recipient_name"=>$recipient_names, 
								   "recipient_tel"=>$recipient_tels, 
								   "recipient_add1"=>$recipient_add1s, 
								   "recipient_add2"=>$recipient_add2s, 
								   "recipient_add3"=>$recipient_add3s, 
								   "recipient_add4"=>$recipient_add4s, 
								   "recipient_add5"=>$recipient_add5s, 
								   "item_code"=>$item_codes, 
								   "order_qty"=>$order_qtys, 
								   "postcode"=>$postcodes
								);
							}
							$arrAPIResults = $this->ajaxAPI($apitype, $apiParams);
							if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
								$arrErrors[] = $arrAPIResults["failure_msg"];
								$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$orderIDValue."' ", "None");
							}
						}
						*/
						// WMS Function Call - End
					}
				}
				if(isset($arrErrors) && count($arrErrors)>0) {
					$arrErrors = array_unique($arrErrors);
					$this->session->data['failure_msg'] = implode("; ",$arrErrors);
					
				} else if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
					$this->session->data['failure_msg'] = $arrAPIResults["failure_msg"];
					
				} else if($isGotSuccess) {
					$this->printtran($sync_orders, "email");
				}
				//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
			}
			//echo"XX1";exit;
			if(!isset($this->session->data['failure_msg']) || (isset($this->session->data['failure_msg']) && $this->session->data['failure_msg']=="")) {
				$this->session->data['success_msg'] = implode("<br />",$arrOrderMsgs);//."<br />Success & Email Sent";
			}
		}
		//$this->getList();
		$this->response->redirect($this->url->link('sale/upload_delivery_list', '&token=' . $this->session->data['token'], true));
	}
	
	public function sync_brpwarehouse() {
		$this->load->model('sale/order');
		$this->load->language('sale/upload_delivery_list');
		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['sync_orders'])) {
			$sync_orders = $this->request->get['sync_orders'];
		} else if (isset($this->request->post['selected'])) {
			$sync_orders = implode("|", (array)$this->request->post['selected']);
		} else {
			$sync_orders = "";
		}
		//echo "<pre>";print_r($sync_orders);echo "</pre>";exit;
			
		$this->load->model('sale/upload_delivery_list');
		
		$arrOrderIDs = array();
		$strCustomMsg = "";
		if($sync_orders!="") {
			$arrOrderIDs = explode("|", $sync_orders);
			$results = $this->model_sale_upload_delivery_list->getOrders(array(), " AND o.order_id IN ('".implode("','",$arrOrderIDs)."')");
			//echo "<pre>x";print_r($results);echo "</pre>";exit;
				
			// WMS Function Call - Start
			$order_ids = "";
			$customer_ids = "";
			$unique_order_ids = "";
			$order_dates = "";
			$recipient_codes = "";
			$recipient_names = "";
			$recipient_tels = "";
			$recipient_add1s = "";
			$recipient_add2s = "";
			$recipient_add3s = "";
			$recipient_add4s = "";
			$recipient_add5s = "";
			$item_codes = "";
			$order_qtys = "";
			$postcodes = "";
			foreach ($results as $result) {
				$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$result["order_id"]."' ", "BRP Warehouse"); // Update the configure delivery to BRP Warehouse
				$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Pending", array($result["order_id"]));
				/*$orderProducts = $this->model_sale_upload_delivery_list->getOrderProducts(" AND op.`order_id` = '".$result["order_id"]."' AND op.`configure_delivery`='BRP Warehouse' ORDER BY op.order_product_id ASC");
				foreach ($orderProducts as $product) {
					//echo "<pre>x";print_r($result);echo "</pre>";
					if($customer_ids!="") {
						$order_ids .= "|" . $result['order_id'];
						$customer_ids .= "|" . $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
						$unique_order_ids .= "|" . $result['unique_order_id'];
						$order_dates .= "|" . date("d/m/Y", strtotime($result['date_added']));
						$recipient_codes .= "|" . $result['customer_code'];
						$recipient_names .= "|" . $result['customer'];
						$recipient_tels .= "|" . $result['telephone'];
						$recipient_add1s .= "|" . $result['address1'];
						$recipient_add2s .= "|" . $result['address2'];
						$recipient_add3s .= "|" . $result['address3'];
						$recipient_add4s .= "|" . $result['address4'];
						$recipient_add5s .= "|" . $result['address5'];
						if($product['data_source']!="" && $product['data_source']!="0") {
							$item_codes .= "|" . $product['matching_code'];
						} else {
							$item_codes .= "|" . $product['model'];
						}
						$order_qtys .= "|" . $product['quantity'];
						$postcodes .= "|" . $result['shipping_postcode'];
					} else {
						$order_ids .= $result['order_id'];
						$customer_ids .= $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
						$unique_order_ids .= $result['unique_order_id'];
						$order_dates = date("d/m/Y", strtotime($result['date_added']));
						$recipient_codes = $result['customer_code'];
						$recipient_names = $result['customer'];
						$recipient_tels = $result['telephone'];
						$recipient_add1s = $result['address1'];
						$recipient_add2s = $result['address2'];
						$recipient_add3s = $result['address3'];
						$recipient_add4s = $result['address4'];
						$recipient_add5s = $result['address5'];
						if($product['data_source']!="" && $product['data_source']!="0") {
							$item_codes = $product['matching_code'];
						} else {
							$item_codes = $product['model'];
						}
						$order_qtys = $product['quantity'];
						$postcodes = $result['shipping_postcode'];
					}
				}*/
			}
			/*$apiParams = array(
			   "order_id"=>$order_ids, 
			   "customer_id"=>$customer_ids, 
			   "unique_order_id"=>$unique_order_ids, 
			   "order_date"=>$order_dates, 
			   "recipient_code"=>$recipient_codes, 
			   "recipient_name"=>$recipient_names, 
			   "recipient_tel"=>$recipient_tels, 
			   "recipient_add1"=>$recipient_add1s, 
			   "recipient_add2"=>$recipient_add2s, 
			   "recipient_add3"=>$recipient_add3s, 
			   "recipient_add4"=>$recipient_add4s, 
			   "recipient_add5"=>$recipient_add5s, 
			   "item_code"=>$item_codes, 
			   "order_qty"=>$order_qtys, 
			   "postcode"=>$postcodes
			);
			//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
			$arrAPIResults = $this->ajaxAPI("upload_delivery_status", $apiParams);
			////$arrAPIResults = array();
			*/
			if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
				$this->session->data['failure_msg'] = $arrAPIResults["failure_msg"];
				//break;
			} else {
				$this->printtran($sync_orders, "email");
			}
			//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
			// WMS Function Call - End
		}
		//echo"XX1";exit;
		if(!isset($this->session->data['failure_msg']) || (isset($this->session->data['failure_msg']) && $this->session->data['failure_msg']=="")) {
			$this->session->data['success_msg'] = "Success & Email Sent";
		}
		$this->getList();
	}
	
	public function deliver_ownarrangement() {
		$this->load->model('sale/order');
		$this->load->language('sale/upload_delivery_list');
		$this->load->model('sale/upload_delivery_list');
		$this->load->model('catalog/product');
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->get['deliver_orders'])) {
			$deliver_orders = $this->request->get['deliver_orders'];
		} else if (isset($this->request->post['selected'])) {
			$deliver_orders = implode("|", (array)$this->request->post['selected']);
		} else {
			$deliver_orders = "";
		}
		
		// var_dump($deliver_orders);
		
		$arrOrderIDs = array();
		$strCustomMsg = "";
		$arrOrderProductIds = array();
		if($deliver_orders!=""){
			$arrOrderIDs = explode("|", $deliver_orders);
			$results = $this->model_sale_upload_delivery_list->getOrders(array(), " AND o.order_id IN ('".implode("','",$arrOrderIDs)."')");
			foreach ($results as $result) {
				$orderProducts = $this->model_sale_upload_delivery_list->getOrderProducts(" AND op.`order_id` = '".$result["order_id"]."' ORDER BY op.order_product_id ASC");
				foreach ($orderProducts as $product) {
					// echo "<pre>";var_dump($product);echo "</pre>";
					// $arrOrderProductIds = $product['order_product_id'];
					// echo "<pre>";var_dump($arrOrderProductIds);echo "</pre>";
					$productInfo = $this->model_catalog_product->getProduct($product['product_id']);
					$finalQty = $productInfo['quantity'] - $product['quantity'];
					if($product['configure_delivery'] != 'Own Arrangement'){
						$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '".$finalQty."' WHERE product_id = '" .$productInfo['product_id']. "'");
						$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_product_id` = '".$product['order_product_id']."' ", "Own Arrangement", $product['wms_quantity']); // Update the configure delivery to Own Arrangement
					}
				}
			}
		$this->getList();
		}
	}

	public function sync_gohoffice() {
		$this->load->model('sale/order');
		$this->load->language('sale/upload_delivery_list');
		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['sync_orders'])) {
			$sync_orders = $this->request->get['sync_orders'];
		} else if (isset($this->request->post['selected'])) {
			$sync_orders = implode("|", (array)$this->request->post['selected']);
		} else {
			$sync_orders = "";
		}
		//echo "<pre>";print_r($sync_orders);echo "</pre>";exit;
			
		$this->load->model('sale/upload_delivery_list');
		
		$arrOrderIDs = array();
		$strCustomMsg = "";
		if($sync_orders!="") {
			$arrOrderIDs = explode("|", $sync_orders);
			$results = $this->model_sale_upload_delivery_list->getOrders(array(), " AND o.order_id IN ('".implode("','",$arrOrderIDs)."')");
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
			foreach ($results as $result) {
				$this->model_sale_upload_delivery_list->updateOrderProducts(" AND `order_id` = '".$result["order_id"]."' ", "Gohoffice"); // Update the configure delivery to BRP Warehouse
				$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Pending", array($result["order_id"]));
				/*$orderProducts = $this->model_sale_upload_delivery_list->getOrderProducts(" AND op.`order_id` = '".$result["order_id"]."' AND op.`configure_delivery`='Gohoffice' ORDER BY op.order_product_id ASC");
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
						$item_code .= "|" . $product['model'];
						$matching_code .= "|" . $product['matching_code'];
						$order_qty .= "|" . $product['quantity'];
						$postcode .= "|" . $result['shipping_postcode'];
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
						$item_code = $product['model'];
						$matching_code = $product['matching_code'];
						$order_qty = $product['quantity'];
						$postcode = $result['shipping_postcode'];
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
				}*/
			}
			/*$apiParams = array(
			   "order_id"=>$order_id, 
			   "customer_id"=>$customer_id, 
			   "unique_order_id"=>$unique_order_id, 
			   "order_date"=>$order_date, 
			   "recipient_code"=>$recipient_code, 
			   "recipient_name"=>$recipient_name, 
			   "recipient_tel"=>$recipient_tel, 
			   "recipient_add1"=>$recipient_add1, 
			   "recipient_add2"=>$recipient_add2, 
			   "recipient_add3"=>$recipient_add3, 
			   "recipient_add4"=>$recipient_add4, 
			   "recipient_add5"=>$recipient_add5, 
			   "item_code"=>$item_code, 
			   "matching_code"=>$matching_code, 
			   "order_qty"=>$order_qty, 
			   "postcode"=>$postcode, 
			   "recipient_orderdate"=>$recipient_orderdate, 
			   "company_name"=>$recipient_companyname, 
			   "recipient_shipping_add1"=>$recipient_shipping_add1, 
			   "recipient_shipping_add2"=>$recipient_shipping_add2, 
			   "recipient_shipping_city"=>$recipient_shipping_city, 
			   "recipient_shipping_postcode"=>$recipient_shipping_postcode, 
			   "recipient_shipping_state"=>$recipient_shipping_state, 
			   "recipient_shipping_country"=>$recipient_shipping_country, 
			   "first_name"=>$recipient_firstname, 
			   "last_name"=>$recipient_lastname, 
			   "email"=>$recipient_email, 
			   "tel"=>$recipient_tel, 
			   "fax"=>$recipient_fax
			);
			//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
			$arrAPIResults = $this->ajaxAPI("upload_gohoffice_delivery_status", $apiParams);
			////$arrAPIResults = array();
			*/
			if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
				$this->session->data['failure_msg'] = $arrAPIResults["failure_msg"];
				//break;
			} else {
				$this->printtran($sync_orders, "email");
			}
			//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
			// WMS Function Call - End
		}
		//echo"XX1";exit;
		if(!isset($this->session->data['failure_msg']) || (isset($this->session->data['failure_msg']) && $this->session->data['failure_msg']=="")) {
			$this->session->data['success_msg'] = "Success";
		}
		$this->getList();
	}
	
	public function printtran($sync_orders = "", $str_print_type = "email") {

		$this->load->model('sale/order');
		$this->load->language('sale/upload_delivery_list');
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->get['sync_orders'])) {
			$sync_orders = $this->request->get['sync_orders'];
		} else {
			$sync_orders = $sync_orders;
		}
		if (isset($this->request->get['printtype'])) {
			$strPrintType = $this->request->get['printtype'];
		} else {
			$strPrintType = $str_print_type;
		}
		if (isset($this->request->get['savenemail'])) {
			$strSaveAndEmail = $this->request->get['savenemail'];
		} else {
			$strSaveAndEmail = 0;
		}
		if($sync_orders!="") {
			$arrOrderIDs = explode("|", $sync_orders);
			asort($arrOrderIDs);
			if(count($arrOrderIDs)>0) {
				
				foreach($arrOrderIDs as $keyOrderID => $valueOrderID) {
					
					$order_info = $this->model_sale_order->getOrder($valueOrderID);
					$data['title'] = $this->language->get('heading_title');
					$data['direction'] = $this->language->get('direction');
					$data['lang'] = $this->language->get('code');
					if ($this->request->server['HTTPS']) {
						$data['base'] = HTTPS_SERVER;
					} else {
						$data['base'] = HTTP_SERVER;
					}
					
					$data['unique_order_id'] = $order_info["unique_order_id"];
					$data['heading_title'] = $this->language->get('heading_title');
					$data['text_detail_list'] = $this->language->get('text_detail_list');
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
					$data['text_order'] = sprintf($this->language->get('text_order'), $valueOrderID);
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
				
					$data['shipping'] = $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$valueOrderID, true);
					$data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$valueOrderID, true);
					$data['edit'] = $this->url->link('sale/order/edit', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$valueOrderID, true);
					$data['cancel'] = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . $url, true);
					$data['cancel_delivery'] = $this->url->link('sale/upload_delivery_list/cancel', 'token=' . $this->session->data['token'] . '&sync_orders=' . (int)$valueOrderID, true);
				
					$data['token'] = $this->session->data['token'];
				
					$data['order_id'] = $valueOrderID;
				
					$data['logo'] = HTTP_CATALOG . 'image/' . str_replace(" ","%20",$this->config->get('config_logo'));
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
				
					////$products = $this->model_sale_order->getOrderProducts($valueOrderID);
					$this->load->model('sale/upload_delivery_list');
					//$products = $this->model_sale_upload_delivery_list->getOrderProducts(" AND op.`order_id` = '".$valueOrderID."' AND op.`configure_delivery`='BRP Warehouse' ORDER BY op.order_product_id ASC");
					$products = $this->model_sale_upload_delivery_list->getOrderProducts(" AND op.`order_id` = '".$valueOrderID."' ORDER BY op.order_product_id ASC");
					
					foreach ($products as $product) {
						$option_data = array();
				
						$options = $this->model_sale_order->getOrderOptions($valueOrderID, $product['order_product_id']);
				
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
							'configure_delivery' => $product['configure_delivery'],
							'wms_quantity'     => $product['wms_quantity'],
							'price'    		   => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
							'total'    		   => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
							'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], true)
						);
					}
				
					$data['vouchers'] = array();
				
					$vouchers = $this->model_sale_order->getOrderVouchers($valueOrderID);
				
					foreach ($vouchers as $voucher) {
						$data['vouchers'][] = array(
							'description' => $voucher['description'],
							'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
							'href'        => $this->url->link('sale/voucher/edit', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], true)
						);
					}
				
					$data['totals'] = array();
				
					$totals = $this->model_sale_order->getOrderTotals($valueOrderID);
				
					foreach ($totals as $total) {
						$data['totals'][] = array(
							'title' => $total['title'],
							'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
						);
					}
				
					$data['comment'] = nl2br($order_info['comment']);
				
					$this->load->model('customer/customer');
				
					$data['reward'] = $order_info['reward'];
				
					$data['reward_total'] = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($valueOrderID);
				
					$data['affiliate_firstname'] = $order_info['affiliate_firstname'];
					$data['affiliate_lastname'] = $order_info['affiliate_lastname'];
				
					if ($order_info['affiliate_id']) {
						$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], true);
					} else {
						$data['affiliate'] = '';
					}
				
					$data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);
				
					$this->load->model('marketing/affiliate');
				
					$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($valueOrderID);
				
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
					
					$data['header'] = $this->load->controller('common/header');
					$data['column_left'] = $this->load->controller('common/column_left');
					$data['footer'] = $this->load->controller('common/footer');
					
					if(isset($strPrintType) && $strPrintType=="email") {
						$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol'); // "smtp"; //
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname'); // "ssl://smtp.gmail.com"; //
						$mail->smtp_username = $this->config->get('config_mail_smtp_username'); // "noreply.webmail.polarisnet@gmail.com"; //
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'); // "12348765QQQQ"; //
						$mail->smtp_port = $this->config->get('config_mail_smtp_port'); // "465"; //
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
						
						$strEmails = constant("WMS_ADMIN_EMAILS");
						$arrEmails = explode(";",$strEmails);
						
						//echo "<pre>";print_r($this->config);echo "</pre>";exit;
						
						$text = "You have received a delivery transaction.";
						$isEmailed = 1;
						foreach($arrEmails as $keyEmail => $valueEmail) {
							$data["token"] = $this->session->data['token'];
							$data["id"] = $valueOrderID;
							$mail->setTo($valueEmail);
							$mail->setFrom($this->config->get('config_email'));
							$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode($this->config->get('config_name')." - Delivery #".(isset($data['unique_order_id'])?$data['unique_order_id']:""), ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($this->load->view('sale/upload_email_delivery_info', $data));
							$mail->setText($text);
							try {
								$mail->send();
							} catch (Exception $e) {
								error_reporting(0);
								$isEmailed = 2;
								$this->session->data['failure_msg'] = "Email(s) failed to send.";
							}
						}
						if($strSaveAndEmail=="1") {
							$this->response->redirect($this->url->link('sale/order/delivery_info', 'order_id=' . $sync_orders . '&emailed='.$isEmailed.'&savenemail='.$strSaveAndEmail.'&token=' . $this->session->data['token'], true));
						}
						//$this->response->redirect($this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&emailed=1&savenemail='.$strSaveAndEmail.'&token=' . $this->session->data['token'], true));
						//$this->getContentList($headerID);
					} else {
						$this->response->setOutput($this->load->view('sale/upload_print_delivery_info', $data));
					}
				}
				
			}
		}
	}
	
	
	public function cancel() {
		$this->load->language('sale/upload_delivery_list');
		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['sync_orders'])) {
			$sync_orders = $this->request->get['sync_orders'];
		} else if (isset($this->request->post['selected'])) {
			$sync_orders = implode("|", (array)$this->request->post['selected']);
		} else {
			$sync_orders = "";
		}
		//echo "<pre>";print_r($sync_orders);echo "</pre>";exit;
			
		$this->load->model('sale/upload_delivery_list');
		
		$arrOrderIDs = array();
		if($sync_orders!="") {
			$arrOrderIDs = explode("|", $sync_orders);
			$results = $this->model_sale_upload_delivery_list->getOrders(array(), " AND o.order_id IN ('".implode("','",$arrOrderIDs)."')");
			//echo "<pre>x";print_r($results);echo "</pre>";exit;
				
			// WMS Function Call - Start
			$order_ids = "";
			$customer_ids = "";
			$unique_order_ids = "";
			
			foreach ($results as $result) {
				//$orderProducts = $this->model_sale_upload_delivery_list->getOrderProducts(" AND op.order_id = '".$result["order_id"]."' ORDER BY op.order_product_id ASC");
				//foreach ($orderProducts as $product) {
					//echo "<pre>x";print_r($result);echo "</pre>";
					if($customer_ids!="") {
						$order_ids .= "|" . $result['order_id'];
						$customer_ids .= "|" . $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
						$unique_order_ids .= "|" . $result['unique_order_id'];
					} else {
						$order_ids .= $result['order_id'];
						$customer_ids .= $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
						$unique_order_ids .= $result['unique_order_id'];
					}
				//}
			}
			$apiParams = array(
			   "order_id"=>$order_ids, 
			   "customer_id"=>$customer_ids, 
			   "unique_order_id"=>$unique_order_ids, 
			);
			//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
			$arrAPIResults = $this->ajaxAPI("cancel_delivery_list", $apiParams);
			if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
				$this->session->data['failure_msg'] = $arrAPIResults["failure_msg"];
				//break;
			}
			//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
			// WMS Function Call - End
		}
		//echo"XX1";exit;
		if(!isset($this->session->data['failure_msg']) || (isset($this->session->data['failure_msg']) && $this->session->data['failure_msg']=="")) {
			$this->session->data['success_msg'] = "Transaction successfully cancelled.";
		}
		$this->getList();
	}
	
	public function index() {
		$this->load->language('sale/upload_delivery_list');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/upload_delivery_list');

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
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

		if (isset($this->request->get['filter_upload_delivery_status'])) {
			$filter_upload_delivery_status = $this->request->get['filter_upload_delivery_status'];
		} else {
			$filter_upload_delivery_status = null;
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

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_upload_delivery_status'])) {
			$url .= '&filter_upload_delivery_status=' . $this->request->get['filter_upload_delivery_status'];
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

		////$data['sync_selected'] = $this->url->link('sale/upload_delivery_list/sync', 'token=' . $this->session->data['token'], true);
		$data['sync_brp_warehouse'] = $this->url->link('sale/upload_delivery_list/sync', 'token=' . $this->session->data['token'], true);
		$data['sync_by_gohofficecom'] = $this->url->link('sale/upload_delivery_list/sync', 'token=' . $this->session->data['token'], true);
		$data['sync_brp_warehouse_gohofficecom'] = $this->url->link('sale/upload_delivery_list/sync', 'token=' . $this->session->data['token'], true);
		$data['deliverall_ownarrangement'] = $this->url->link('sale/upload_delivery_list/sync', 'token=' . $this->session->data['token'], true);
		//$data['deliverall_ownarrangement'] = $this->url->link('sale/upload_delivery_list/deliverall_ownarrangement', 'token=' . $this->session->data['token'], true);

		$data['orders'] = array();

		$filter_data = array(
			'filter_order_id'      => $filter_order_id,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => $filter_order_status,
			'filter_upload_delivery_status'   => $filter_upload_delivery_status,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);
		
		$order_total = $this->model_sale_upload_delivery_list->getTotalOrders($filter_data);

		$results = $this->model_sale_upload_delivery_list->getOrders($filter_data);
		// $order_total = count($results);
		//echo "<pre>";print_r($results);echo "</pre>";exit;

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
		// $arrAPIResults = $this->ajaxAPI("delivery_status", $apiParams);
		//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
		// WMS Function Call - End
		
		
		$arrTheOrderIDs = array();
		foreach ($results as $result) {
			$arrTheOrderIDs[] = $result['order_id'];
		}
		//echo "<pre>";print_r($arrTheOrderIDs);echo "</pre>";exit;
		$this->load->model('sale/order');
		if(count($arrTheOrderIDs)>0) {
			$data['products'] = array();
			$products = $this->model_sale_order->getMultipleOrderProducts($arrTheOrderIDs); //$result['order_id']
			$apiParams = $products;
			$arrAPISLResults = $this->ajaxAPI('stock_levels', $apiParams);
		}

		foreach ($results as $result) {
			//echo "<pre>";print_r($result);echo "</pre><br />";//exit;
			$can_sync_gohoffice = false; $can_sync_warehouse = false; $can_sync_partially = false; $own_arrangement = false;
			// First Filter
			if($result["total_prod_type"]==$result["total_cd_none"] || $result["total_prod_type"]==$result["total_cd_gohoffice"]) {
				$can_sync_gohoffice = true;
			}
			//if($result["total_prod_type"]==$result["total_brp"]) {
				$can_sync_warehouse = true;
				if(!($result["total_prod_type"]==$result["total_cd_none"] || $result["total_prod_type"]==$result["total_cd_brp"])) {
					$can_sync_warehouse = false;
				}
			//}
			if($result["total_prod_type"]!=$result["total_cd_gohoffice"] && $result["total_prod_type"]!=$result["total_cd_brp"]) {
				$can_sync_partially = true;
			}
			if($result["total_prod_type"]==$result["total_cd_ownarrangement"]) {
				$own_arrangement = true;
			}
			// Second Filter
			if($result["total_cd_none"]==0 && $result["total_prod_type"]==$result["total_cd_gohoffice"]) {
				$can_sync_warehouse = false; $can_sync_partially = false;
			}
			if($result["total_cd_none"]==0 && $result["total_prod_type"]==$result["total_cd_brp"]) {
				$can_sync_gohoffice = false; $can_sync_partially = false;
			}
			/*if($result["total_prod_type"]!=$result["total_cd_gohoffice"] && $result["total_prod_type"]!=$result["total_cd_brp"]) {
				$can_sync_gohoffice = false; $can_sync_warehouse = false;
			}*/
			
/*$intNewWMSBalance = "-";
if($this->config->get('config_using_warehouse_module')&&$product['data_source']!=""&&$product['data_source']!="0") {
	$intNewWMSBalance = (isset($arrAPIResults["wms_data"][$product['model']])?str_replace(".0000","",$arrAPIResults["wms_data"][$product['model']]):"0");
} if($this->config->get('config_using_warehouse_module')&&$product['data_source']=="") {
	$intNewWMSBalance = (isset($arrAPIResults["wms_data"][$product['model']])?str_replace(".0000","",$arrAPIResults["wms_data"][$product['model']]):"0");
}*/
			$products = $this->model_sale_order->getOrderProducts($result['order_id']);
			//echo "<pre>";print_r($products);echo "</pre>";exit;
			$boolHasZeroWMSQty = false;
			$boolOrderQtyMoreThanWMSATO = false;
			$boolOrderQtyLessThanWMSATO = false;
			foreach ($products as $product) {

$product['model'] = html_entity_decode($product['model']);
                                $product['matching_code'] = html_entity_decode($product['matching_code']);

				if($this->config->get('config_using_warehouse_module')) {

					if($product['data_source']!="" && $product['data_source']!="0") {
						if(isset($arrAPISLResults["wms_data"][$product['matching_code']])) {
							$currentStock = $arrAPISLResults["wms_data"][$product['matching_code']];
							$availToOrderQty = $arrAPISLResults["wms_data2"][$product['matching_code']];
						}
						if(!isset($arrAPISLResults["wms_data"][$product['matching_code']]) || (isset($arrAPISLResults["wms_data"][$product['matching_code']]) && str_replace(".0000","",$arrAPISLResults["wms_data"][$product['matching_code']])=="0")) {
						$boolHasZeroWMSQty = true;
						break;
					}
					}else {
						if(isset($arrAPISLResults["wms_data"][$product['model']])) {
							$currentStock = $arrAPISLResults["wms_data"][$product['model']];
							$availToOrderQty = $arrAPISLResults["wms_data2"][$product['model']];
						}
						if(!isset($arrAPISLResults["wms_data"][$product['model']]) || (isset($arrAPISLResults["wms_data"][$product['model']]) && str_replace(".0000","",$arrAPISLResults["wms_data"][$product['model']])=="0")) {
						$boolHasZeroWMSQty = true;
						break;
					}
					}		
					
					

					if($product['quantity']>$currentStock && $availToOrderQty<$product['quantity']){
						$boolOrderQtyMoreThanWMSATO = true;
						break;
					}
					elseif($product['quantity']<=$currentStock && $availToOrderQty<$product['quantity']){
						$boolOrderQtyLessThanWMSATO = true;
					}	
	
				}
			}
			//echo "<pre>";print_r($boolHasZeroWMSQty);echo "</pre><br />";//exit;
			
			$data['orders'][] = array(
				'order_id'      => $result['order_id'],
				'order_status_id'=> $result['order_status_id'],
				'unique_order_id'=> $result['unique_order_id'],
				'upload_delivery_status'=> ($result['upload_delivery_status']!=""?$result['upload_delivery_status']:""),
				'customer'      => $result['customer'],
				'order_status'  => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
				'delivery_status'=> $result['order_status'],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $result['shipping_code'],
				
				'can_sync_gohoffice' => $can_sync_gohoffice,
				'can_sync_warehouse' => $can_sync_warehouse,
				'can_sync_partially' => $can_sync_partially,
				'is_syned_warehouse' => ($result["total_cd_none"]==0&&$result["total_prod_type"]==$result["total_cd_brp"]?true:false),
				'is_syned_gohoffice' => ($result["total_cd_none"]==0&&$result["total_prod_type"]==$result["total_cd_gohoffice"]?true:false),
				'is_syned_mixed' => ($result["total_cd_none"]==0&&$can_sync_partially?true:false),
				'own_arrangement' => $own_arrangement,
				'total_third_parties' => $result["total_third_parties"],
				'has_zero_wms_qty' => $boolHasZeroWMSQty,
				'has_moreQty'		 => $boolOrderQtyMoreThanWMSATO,
				'has_lessQty'		 => $boolOrderQtyLessThanWMSATO,
				
				'view'          => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
				'delivery_view' => $this->url->link('sale/order/delivery_info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
				'edit'          => $this->url->link('sale/order/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
				'sync'          => $this->url->link('sale/upload_delivery_list/sync', 'token=' . $this->session->data['token'] . '&sync_orders=' . $result['order_id'] . $url, true),
				'sync_brpwarehouse'	=> $this->url->link('sale/upload_delivery_list/sync_brpwarehouse', 'token=' . $this->session->data['token'] . '&sync_orders=' . $result['order_id'] . $url, true),
				'sync_gohoffice'=> $this->url->link('sale/upload_delivery_list/sync_gohoffice', 'token=' . $this->session->data['token'] . '&sync_orders=' . $result['order_id'] . $url, true),
				'deliver_ownarrangement'=> $this->url->link('sale/upload_delivery_list/deliver_ownarrangement', 'token=' . $this->session->data['token'] . '&deliver_orders=' . $result['order_id'] . $url, true),
				'sync_configure'=> $this->url->link('sale/order/delivery_info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . '&configure=1' . $url, true),
				'cancel'        => $this->url->link('sale/upload_delivery_list/cancel', 'token=' . $this->session->data['token'] . '&sync_orders=' . $result['order_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_pending'] = $this->language->get('text_pending');
		$data['text_completed'] = $this->language->get('text_completed');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_unique_order_id'] = $this->language->get('column_unique_order_id');
		$data['column_upload_delivery_status'] = $this->language->get('column_upload_delivery_status');
		$data['column_brp_warehouse_qty'] = $this->language->get('column_brp_warehouse_qty');
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
		$data['entry_upload_delivery_status'] = $this->language->get('entry_upload_delivery_status');
		$data['entry_brp_warehouse_qty'] = $this->language->get('entry_brp_warehouse_qty');
		$data['entry_delivery_status'] = $this->language->get('entry_delivery_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_sfdelivery'] = $this->language->get('button_sfdelivery');
		$data['button_sendaction'] = $this->language->get('button_sendaction');
		$data['button_bygohoffice'] = $this->language->get('button_bygohoffice');
		$data['button_brpwarehouse'] = $this->language->get('button_brpwarehouse');
		$data['button_ownarrangement'] = $this->language->get('button_ownarrangement');
		$data['button_configuredelivery'] = $this->language->get('button_configuredelivery');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['failure_msg'])) {
			$data['error_warning'] = $this->session->data['failure_msg'];
			unset($this->session->data['failure_msg']);
		} else {
			$data['error_warning'] = '';
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

		if (isset($this->request->get['filter_upload_delivery_status'])) {
			$url .= '&filter_upload_delivery_status=' . $this->request->get['filter_upload_delivery_status'];
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

		$data['sort_order'] = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, true);
		$data['sort_unique_order'] = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . '&sort=o.unique_order_id' . $url, true);
		$data['sort_upload_delivery_status'] = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . '&sort=o.upload_delivery_status' . $url, true);
		$data['sort_customer'] = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_status'] = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . '&sort=order_status' . $url, true);
		$data['sort_total'] = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, true);
		$data['sort_date_added'] = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, true);

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

		if (isset($this->request->get['filter_upload_delivery_status'])) {
			$url .= '&filter_upload_delivery_status=' . $this->request->get['filter_upload_delivery_status'];
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
		$pagination->url = $this->url->link('sale/upload_delivery_list', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_upload_delivery_status'] = $filter_upload_delivery_status;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/upload_delivery_list', $data));
	}
	
	public function configure_delivery_email() {
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = "";
		}
		$this->printtran($order_id, "email");
	}
	
}
