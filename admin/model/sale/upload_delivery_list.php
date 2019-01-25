<?php
class ModelSaleUploadDeliveryList extends Model {
	public function isJSON($string){
	   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	public function coreAPI($apitype = '', $params) {
		$array = array();
		if($apitype=="delivery_status") {
			
			$customer_id = $params['customer_id'];
			$order_id = $params['order_id'];
			$strJson = $params['json_data'];
			
			//Query WMS Cloud Server - Start
			//DownloadDeliveryProgress
			//$path_url = "http://app1.gohofficesupplies.com/brp_bal.php?prod=".$matching_code;
			//$path_url = "http://localhost:8080/atoz_opencart/delivery_progress2.txt?CustCode=".$customer_id."&OrderID=".$order_id;
			//http://192.168.1.30:8090/api/WMS/DownloadDeliveryProgress?js={"TSHTable":[{"CustCode":"GOS","OrderID":"D0002"}]}
			//http://192.168.1.30:8090/api/WMS/DownloadDeliveryProgress?js={"TSHTable":[{"CustCode":"GOS","OrderID":"D0001"},{"CustCode":"GOS","OrderID":"D0002"}]}
			//$path_url = "http://localhost:8080/atoz_opencart/delivery_progress2.txt?js=".$strJson."";
			//http://192.168.1.30:8090/api/WMS/DownloadDeliveryProgress?js={"TSHTable":[{"CustCode":"GOS","OrderID":"D0001"}]}
			//[{"prod_code":"R04-09 ","Bal":"24.0000"},{"prod_code":"HPB3Q11A ","Bal":"2.0000"},{"prod_code":"HPB5L24A ","Bal":".0000"}]
			//http://localhost:8080/atoz_opencart/index.php?route=product/product/ajaxAPI&using_warehouse=1&product_id=2703&data_source=2707&matching_code=B%20LT-5300|HPB3Q11A
			//{"using_warehouse":"1","product_id":"2703","data_source":"2707","matching_code":"B LT-5300|HPB3Q11A",
				//"data":[{"prod_code":"B LT-5300","Bal":".0000"},{"prod_code":"HPB3Q11A","Bal":"2.0000"}],"data2":[{"prod_code":"B LT-5300","Bal":".0000"},{"prod_code":"HPB3Q11A","Bal":"2.0000"}]}
			$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadDeliveryProgress?js=".$strJson."";
			
			// WMS API
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($curl, CURLOPT_TIMEOUT, 10);
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => str_replace(array("&amp;", " "),array("%26", "%20"),$path_url),
				CURLOPT_POST => 0,
				CURLOPT_POSTFIELDS => array(
					'CustCode' => $customer_id,
					'OrderID' => $order_id
				)
			));
			$resp = curl_exec($curl);
			$resp = trim(str_replace("","",strip_tags($resp)));
			curl_close($curl);
			$resp = json_decode($resp, true);
			if($this->isJSON($resp)) {
				$resp = json_decode($resp, true);
			}
			$arrData = $resp;
			//function cleanData(&$item) { $item = trim($item); }
			//array_walk_recursive($arrData, 'cleanData');
			//echo "<pre>XX";print_r($arrData);echo "</pre>";exit;
			//Array(
			//	[TSHTable] => Array(
			//		[0] => Array(
			//			[OrderID] => partUnique1234@1
			//			[OrderStatus] => Draft
			//		)
			//		[1] => Array(
			//			[OrderID] => partUnique1234@2
			//			[OrderStatus] => Draft
			//		)
			//	)
			//)
			$arrData = (isset($arrData["TSHTable"])?$arrData["TSHTable"]:array());
			$arrTempData = array();
			if(is_array($arrData) && count($arrData)>0) {
				foreach($arrData as $key => $value) {
					$arrTempData[$value["OrderID"]] = $value["OrderStatus"];
					$arrOrderIDs = explode("@", $value["OrderID"]);
                                        
                                        $currStatus = 2;
                                        $sqlStatus = "SELECT `order_status_id` FROM `oc_order` WHERE `order_id`='" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "' ";
                                        $resultStatus = $this->db->query($sqlStatus);
                                        if ($resultStatus->num_rows) {
                                                     $currStatus = $resultStatus->row['order_status_id'];

                                        }
                                        
                                        if($currStatus != '5') {
                                        
					if($value["OrderStatus"]=="Draft") {
						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '21' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
                                                
                                                if($currStatus != '21') {
                                                    $this->db->query("INSERT INTO `oc_order_history` (`order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "', '21', '0', '', NOW())");
                                                }
                                                
					} else if($value["OrderStatus"]=="Packing") {
						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '22' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
                                                
                                                if($currStatus != '22') {
                                                    $this->db->query("INSERT INTO `oc_order_history` (`order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "', '22', '0', '', NOW())");
                                                }
                                                
					} else if($value["OrderStatus"]=="Shipment") {
						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '23' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
                                                
                                                if($currStatus != '23') {
                                                    $this->db->query("INSERT INTO `oc_order_history` (`order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "', '23', '0', '', NOW())");
                                                }
					} else {
						//$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '1' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
					}
				}
			}
			}
			$array['wms_data'] = $arrTempData;
			//echo "<pre>XX";print_r($array['wms_data']);echo "</pre>";exit;
			//Query WMS Cloud Server - End
		}
		//echo "<pre>";print_r($array);echo "</pre>";exit;
		//Query Opencart - End
		else if($apitype=="upload_delivery_status") {
			
			$order_id = $params['order_id'];
			$order_ids = explode("|",$order_id);
			$customer_id = $params['customer_id'];
			$unique_order_id = $params['unique_order_id'];
			$order_date = $params['order_date'];
			$recipient_code = $params['recipient_code'];
			$recipient_name = $params['recipient_name'];
			$recipient_tel = $params['recipient_tel'];
			$recipient_add1 = $params['recipient_add1'];
			$recipient_add2 = $params['recipient_add2'];
			$recipient_add3 = $params['recipient_add3'];
			$recipient_add4 = $params['recipient_add4'];
			$recipient_add5 = $params['recipient_add5'];
			$item_code = $params['item_code'];
			$order_qty = $params['order_qty'];
			$postcode = $params['postcode'];
			$strJson = $params['json_data'];
			//Query WMS Cloud Server - Start
			//http://192.168.1.30:8090/api/WMS/UploadDeliveryList?js=
			//$path_url = "http://localhost:8080/atoz_opencart/upload_delivery_list_return1.txt?js=".$strJson."";
			$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/UploadDeliveryList?js=".$strJson."";
			//echo $path_url;//exit;
			
			// WMS API
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($curl, CURLOPT_TIMEOUT, 10);
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => str_replace(array("&amp;", " ", "+"),array("%26", "%20", "%2B"),$path_url),
				CURLOPT_POST => 0,
				CURLOPT_POSTFIELDS => array()
			));
			$resp = curl_exec($curl);
			$resp = trim(str_replace("","",strip_tags($resp)));
			curl_close($curl);
			
			$resp = json_decode($resp, true);
			if($this->isJSON($resp)) {
				$resp = json_decode($resp, true);
			}
			
			//$arrData = json_decode($resp, true);
			if($resp=="Success") {
				$array["success_msg"] = "Successfully uploaded delivery list"; //$resp;
				$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Completed", $order_ids);
			} else {
				if(!is_array($resp)) {
					$array["failure_msg"] = $resp;
				} else {
					$array["failure_msg"] = (isset($resp["Message"])?$resp["Message"]:"An error has occurred.");
				}
				$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Pending", $order_ids);
			}
			//$array['wms_data'] = $arrTempData;
		} else if($apitype=="upload_gohoffice_delivery_status") {
			
			$order_id = $params['order_id'];
			$order_ids = explode("|",$order_id);
			$customer_id = $params['customer_id'];
			$unique_order_id = $params['unique_order_id'];
			$order_date = $params['order_date'];
			$recipient_code = $params['recipient_code'];
			$recipient_name = $params['recipient_name'];
			$recipient_tel = $params['recipient_tel'];
			$recipient_add1 = $params['recipient_add1'];
			$recipient_add2 = $params['recipient_add2'];
			$recipient_add3 = $params['recipient_add3'];
			$recipient_add4 = $params['recipient_add4'];
			$recipient_add5 = $params['recipient_add5'];
			$item_code = $params['item_code'];
			$matching_code = $params['matching_code'];
			$order_qty = $params['order_qty'];
			$postcode = $params['postcode'];
			$recipient_orderdate = $params['recipient_orderdate'];
			$company = $params['company_name'];
			$recipient_shipping_add1 = $params['recipient_shipping_add1'];
			$recipient_shipping_add2 = $params['recipient_shipping_add2'];
			$recipient_shipping_city = $params['recipient_shipping_city'];
			$recipient_shipping_postcode = $params['recipient_shipping_postcode'];
			$recipient_shipping_state = $params['recipient_shipping_state'];
			$recipient_shipping_country = $params['recipient_shipping_country'];
			$first_name = $params['first_name'];
			$last_name = $params['last_name'];
			$email = $params['email'];
			$tel = $params['tel'];
			$fax = $params['fax'];
			$strJson = $params['json_data'];
			
			//Gohoffice API - Start
			$partner_unique_id = $this->config->get('config_unique_brp_partner_id');
			$partner_gohoffice_url = $this->config->get('config_gohoffice_sync_url');
			$partner_gohoffice_key = $this->config->get('config_gohoffice_sync_key');
			//$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/UploadDeliveryList?js=".$strJson."";
			//$path_url = $partner_gohoffice_url."?key=".$partner_gohoffice_key."&sync=delivery&partner=".$partner_unique_id."&js=".$strJson."";
			//echo "<pre>";print_r($order_ids);echo "</pre>";exit;
			//echo $path_url;exit;
			$path_url_only = $partner_gohoffice_url;
			//echo $partner_gohoffice_url."?key=".$partner_gohoffice_key."&sync=delivery&partner=".$partner_unique_id."&js=".$strJson.""."<br />";//exit;
			$path_all_params = array(
				'key' => $partner_gohoffice_key,
				'sync' => "delivery",
				'partner' => $partner_unique_id,
				'js' => $strJson
			);
			
			// WMS API
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($curl, CURLOPT_TIMEOUT, 10);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:'));
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => str_replace(array("&amp;", " "),array("%26", "%20"),$path_url_only),
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => http_build_query($path_all_params)
			));
			$resp = curl_exec($curl);
			$resp = trim(str_replace("","",strip_tags($resp)));
			curl_close($curl);
			
			$resp = json_decode($resp, true);
			if($this->isJSON($resp)) {
				$resp = json_decode($resp, true);
			}			
			
			//$arrData = json_decode($resp, true);
			if(isset($resp["success"]) && $resp["success"]) {
				$array["success_msg"] = "Successfully uploaded Gohoffice delivery list"; //$resp;
				$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Completed", $order_ids);
			} else {
				if(!is_array($resp)) {
					$array["failure_msg"] = $resp;
				} else {
					$array["failure_msg"] = (isset($resp["error"])?$resp["error"]:"An error has occurred.");
				}
				$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Pending", $order_ids);
			}
			//$array['wms_data'] = $arrTempData;
		} else if($apitype=="cancel_delivery_list") {
			
			$order_id = $params['order_id'];
			$order_ids = explode("|",$order_id);
			$customer_id = $params['customer_id'];
			$unique_order_id = $params['unique_order_id'];
			$strJson = $params['json_data'];
			//Query WMS Cloud Server - Start
			$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/CancelDeliveryList?js=".$strJson."";
			//echo $path_url;exit;
			
			// WMS API
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($curl, CURLOPT_TIMEOUT, 10);
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => str_replace(array("&amp;", " "),array("%26", "%20"),$path_url),
				CURLOPT_POST => 0,
				CURLOPT_POSTFIELDS => array()
			));
			$resp = curl_exec($curl);
			$resp = trim(str_replace("","",strip_tags($resp)));
			curl_close($curl);
			
			$resp = json_decode($resp, true);
			if($this->isJSON($resp)) {
				$resp = json_decode($resp, true);
			}
			
			//$arrData = json_decode($resp, true);
			if($resp=="Success") {
				$array["success_msg"] = "Successfully cancelled delivery list"; //$resp;
				$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Cancelled", $order_ids);
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '2' WHERE order_id IN ('" . implode("','",$order_ids) . "')");
                                foreach($order_ids as $ohkey => $ohvalue) {
                                    $this->db->query("INSERT INTO `oc_order_history` (`order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('" . $ohvalue . "', '2', '0', '', NOW())");
                                }
			} else {
				if(!is_array($resp)) {
					$array["failure_msg"] = $resp;
				} else {
					$array["failure_msg"] = (isset($resp["Message"])?$resp["Message"]:"An error has occurred.");
				}
			}
			//$array['wms_data'] = $arrTempData;
		}
		return $array;
	}
	
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}
			
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			$this->load->model('marketing/affiliate');

			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'unique_order_id'         => $order_query->row['unique_order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => json_decode($order_query->row['custom_field'], true),
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_custom_field'    => json_decode($order_query->row['payment_custom_field'], true),
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => json_decode($order_query->row['shipping_custom_field'], true),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified']
			);
		} else {
			return;
		}
	}

	public function getOrders($data = array(), $strConditions = "") {
		$sql = "SELECT 
			o.order_id, 
			o.order_status_id, 
			o.unique_order_id, 
			o.upload_delivery_status, 
			CONCAT(o.firstname, ' ', o.lastname) AS customer, 
			
			(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, 
			'' AS model, 
			(SELECT SUM(op.quantity) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id) AS quantity, 
			(SELECT SUM(op.price) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id) AS price, 
			(SELECT oc.customer_id FROM " . DB_PREFIX . "customer oc WHERE oc.customer_id = o.customer_id) AS customer_code, 
			
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id) AS total_prod_type, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.matching_code!='') AS total_brp, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.matching_code='') AS total_3rd_party, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.configure_delivery='BRP Warehouse') AS total_cd_brp, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.configure_delivery='Gohoffice') AS total_cd_gohoffice, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.configure_delivery='Own Arrangement') AS total_cd_ownarrangement, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.configure_delivery='None') AS total_cd_none, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND (op.data_source='0' OR op.data_source='')) AS total_third_parties, 
			
			o.firstname, 
			o.lastname, 
			o.email, 
			o.fax,
			o.shipping_company,
			o.shipping_address_1,
			o.shipping_address_2,
			o.shipping_city,
			o.shipping_postcode,
			o.shipping_zone,
			o.shipping_country,
				
			o.telephone AS telephone,  
			
			CASE WHEN o.shipping_company != '' THEN concat(o.shipping_company, '\r\n', o.shipping_address_1) ELSE o.shipping_address_1 END AS address1,
			o.shipping_address_2 AS address2,  
			concat(o.shipping_city, ' ', o.shipping_postcode) AS address3,
			o.shipping_zone AS address4,  
			o.shipping_country AS address5, 
			
			o.shipping_code, 
			o.total, 
			o.currency_code, 
			o.currency_value, 
			o.date_added, 
			o.date_modified 
		FROM `" . DB_PREFIX . "order` o";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_upload_delivery_status'])) {
			//$sql .= " AND o.upload_delivery_status = '" . $data['filter_upload_delivery_status'] . "'";
			/*if($data['filter_upload_delivery_status']=="Selected") {
				$sql .= " AND o.order_status_id IN ('21','22','23')";
			} else {
				$sql .= " AND o.order_status_id NOT IN ('21','22','23')";
			}*/
			if($data['filter_upload_delivery_status']=="Selected for BRP Warehouse") {
				$sql .= " HAVING total_cd_brp > 0 AND total_prod_type = total_cd_brp ";
			} else if($data['filter_upload_delivery_status']=="Selected for Gohoffice.com") {
				$sql .= " HAVING total_cd_gohoffice > 0 AND total_prod_type = total_cd_gohoffice ";
			} else if($data['filter_upload_delivery_status']=="Selected for Mixed Delivery") {
				$sql .= " HAVING total_cd_ownarrangement > 0 ";
			} else {
			}
		}
		
		if(!empty($strConditions)) {
			$sql .= $strConditions;
		}
		//echo $sql;exit;
		

		$sort_data = array(
			'o.order_id',
			'o.unique_order_id',
			'customer',
			'order_status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getOrderProducts($strConditions = " AND 1=0 ") {
		$sql = "SELECT 
			op.order_product_id, 
			op.product_id, 
			op.name, 
			op.data_source, 
			op.model, 
			op.matching_code, 
			op.quantity, 
			op.wms_quantity, 
			op.configure_delivery, 
			op.price, 
			op.tax, 
			op.total
		FROM `" . DB_PREFIX . "order_product` op WHERE 1=1 ";
		
		if(!empty($strConditions)) {
			$sql .= $strConditions;
		}
		//echo $sql;exit;

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function updateOrderProducts($strConditions = " AND 1=0 ", $strConfigureDelivery = "None", $intWMSQty = 0) {
		if($intWMSQty>0) {
			$sql = "UPDATE `" . DB_PREFIX . "order_product` SET `configure_delivery` = '".$strConfigureDelivery."', `wms_quantity` = '".$intWMSQty."' WHERE 1=1 ";
		} else {
			$sql = "UPDATE `" . DB_PREFIX . "order_product` SET `configure_delivery` = '".$strConfigureDelivery."' WHERE 1=1 ";
		}
		if(!empty($strConditions)) {
			$sql .= $strConditions;
		}
		//echo $sql;exit;
		return $this->db->query($sql);
	}

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function updateUploadDeliveryStatus($strStatus = 'Pending', $arrOrderIDs = array()) {
		if (is_array($arrOrderIDs) && count($arrOrderIDs)>0) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET upload_delivery_status = '".$strStatus."' WHERE order_id IN ('" . implode("','",$arrOrderIDs) . "')");
			return true;
		}
	}

	public function getUploadDeliveryStatus($order_id) {
		$sql = "SELECT upload_delivery_status FROM `" . DB_PREFIX . "order` WHERE order_id='".$order_id."' ";
		
		$query = $this->db->query($sql);

		return $query->row['upload_delivery_status'];
	}

}
