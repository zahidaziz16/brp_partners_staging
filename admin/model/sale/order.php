<?php
class ModelSaleOrder extends Model {
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
			//echo $path_url;exit;
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($curl, CURLOPT_TIMEOUT, 300);
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
					if($value["OrderStatus"]=="Draft") {
						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '21' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
					} else if($value["OrderStatus"]=="Packing") {
						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '22' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
					} else if($value["OrderStatus"]=="Shipment") {
						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '23' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
					} else {
						//$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '1' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
					}
				}
			}
			$array['wms_data'] = $arrTempData;
			//echo "<pre>XX";print_r($array['wms_data']);echo "</pre>";exit;
			//Query WMS Cloud Server - End
			//$array['oc_data'] = "Shipment";
		}
		//echo "<pre>";print_r($array);echo "</pre>";exit;
		//Query Opencart - End
		
		return $array;
	}
	
	public function deleteOrder($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE `or`, ort FROM `" . DB_PREFIX . "order_recurring` `or`, `" . DB_PREFIX . "order_recurring_transaction` `ort` WHERE order_id = '" . (int)$order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_transaction` WHERE order_id = '" . (int)$order_id . "'");

		// Delete voucher data as well
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher_history` WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT 
			o.*, 
			(SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, 
			(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, 
			
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id) AS total_prod_type, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.matching_code!='') AS total_brp, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.matching_code='') AS total_3rd_party, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.configure_delivery='BRP Warehouse') AS total_cd_brp, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.configure_delivery='Gohoffice') AS total_cd_gohoffice, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.configure_delivery='Own Arrangement') AS total_cd_ownarrangement, 
			(SELECT COUNT(op.order_product_id) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.configure_delivery='None') AS total_cd_none
			
			FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

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
				'total_prod_type'         => $order_query->row['total_prod_type'],
				'total_brp'         	  => $order_query->row['total_brp'],
				'total_3rd_party'         => $order_query->row['total_3rd_party'],
				'total_cd_brp'            => $order_query->row['total_cd_brp'],
				'total_cd_gohoffice'      => $order_query->row['total_cd_gohoffice'],
				'total_cd_none'           => $order_query->row['total_cd_none'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_gst_no'              => $order_query->row['store_gst_no'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				// 'account_id'              => $order_query->row['account_id'],
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

	public function getOrders($data = array()) {
		if($this->config->get('config_employee')) {
			$sql = "SELECT 
			o.order_id, o.unique_order_id, o.store_url, o.comment, o.customer_id,
			o.upload_delivery_status, 
			(SELECT employee_id FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS employee_id, 
			(SELECT 
			IF(LOCATE('Lazada Order No: #', o.comment), SUBSTRING_INDEX(o.comment,'Lazada Order No: #',-1), 
			IF(LOCATE('11street Order No: #', o.comment), SUBSTRING_INDEX(o.comment,'11street Order No: #',-1),
			IF(LOCATE('Shopee Order No: #', o.comment), SUBSTRING_INDEX(o.comment,'Shopee Order No: #',-1),
			'')))) AS order_no,
			CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os 
			WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, o.shipping_code, 
			o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";
		}
		else{
			$sql = "SELECT 
			o.order_id, o.unique_order_id, o.store_url, o.comment, o.customer_id,
			o.upload_delivery_status,
			(SELECT 
			IF(LOCATE('Lazada Order No: #', o.comment), SUBSTRING_INDEX(o.comment,'Lazada Order No: #',-1), 
			IF(LOCATE('11street Order No: #', o.comment), SUBSTRING_INDEX(o.comment,'11street Order No: #',-1),
			IF(LOCATE('Shopee Order No: #', o.comment), SUBSTRING_INDEX(o.comment,'Shopee Order No: #',-1),
			'')))) AS order_no,
			CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os 
			WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, o.shipping_code, 
			o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";
		}

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
			if($data['filter_order_id'] == "L_" || $data['filter_order_id'] == "l_"){
				$sql .= " AND o.comment LIKE 'Lazada Order No: #%'";
			}
			else if(stripos($data['filter_order_id'], 'L_') !== false){
				$sql .= " AND o.order_id = '" . (int)str_ireplace('L_', '', $data['filter_order_id']) . "'";
			}
			else if($data['filter_order_id'] == '11_'){
				$sql .= " AND o.comment LIKE '11street Order No: #%'";
			}
			else if(strpos($data['filter_order_id'], '11_') !== false){
				$sql .= " AND o.order_id = '" . (int)str_ireplace('11_', '', $data['filter_order_id']) . "'";
			}
			else if($data['filter_order_id'] == "S_" || $data['filter_order_id'] == "s_"){
				$sql .= " AND o.comment LIKE 'Shopee Order No: #%'";
			}
			else if(stripos($data['filter_order_id'], 'S_') !== false){
				$sql .= " AND o.order_id = '" . (int)str_ireplace('S_', '', $data['filter_order_id']) . "'";
			}
			else{
			// $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
			$sql .= " AND CAST(o.order_id as char) LIKE '%". $data['filter_order_id'] . "%'";
			}
		}
		
		if (!empty($data['filter_order_no'])) {
			$sql .= " AND (o.comment LIKE 'Lazada Order No: #%".$data['filter_order_no']."%' OR o.comment LIKE '11street Order No: #%".$data['filter_order_no']."%' OR o.comment LIKE 'Shopee Order No: #%".$data['filter_order_no']."%')";
		}
		
		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
		
		if($this->config->get('config_employee')) {
			if (!empty($data['filter_employee_id'])) {
				$sql .= " AND (SELECT employee_id FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) LIKE '%" . $this->db->escape($data['filter_employee_id']) . "%'";
			}
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		if($this->config->get('config_employee')) {
			$sort_data = array(
				'o.order_id',
				'o.unique_order_id',
				'customer',
				'employee_id',
				'order_status',
				'o.date_added',
				'o.date_modified',
				'o.total'
			);
		}else{
			$sort_data = array(
				'o.order_id',
				'o.unique_order_id',
				'customer',
				'order_status',
				'o.date_added',
				'o.date_modified',
				'o.total'
			);			
		}

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

    public function getOrderProducts($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' ORDER BY order_product_id ASC ");

        return $query->rows;
    }

	public function getMultipleOrderProducts($arr_order_ids) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id IN ('" . implode("','",$arr_order_ids) . "') ");

		return $query->rows;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderVoucherByVoucherId($voucher_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

		return $query->row;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getTotalOrders($data = array()) {
		if($this->config->get('config_employee')) {
			$sql = "SELECT COUNT(*) AS total,(SELECT employee_id FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS employee_id  FROM `" . DB_PREFIX . "order` o";
		}
		else{
			$sql = "SELECT COUNT(*) AS total  FROM `" . DB_PREFIX . "order` o";
		}

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
						if($data['filter_order_id'] == "L_" || $data['filter_order_id'] == "l_"){
				$sql .= " AND o.comment LIKE 'Lazada Order No: #%'";
			}
			else if(stripos($data['filter_order_id'], 'L_') !== false){
				$sql .= " AND o.order_id = '" . (int)str_ireplace('L_', '', $data['filter_order_id']) . "'";
			}
			else if($data['filter_order_id'] == '11_'){
				$sql .= " AND o.comment LIKE '11street Order No: #%'";
			}
			else if(strpos($data['filter_order_id'], '11_') !== false){
				$sql .= " AND o.order_id = '" . (int)str_ireplace('11_', '', $data['filter_order_id']) . "'";
			}
			if($data['filter_order_id'] == "S_" || $data['filter_order_id'] == "s_"){
				$sql .= " AND o.comment LIKE 'Shopee Order No: #%'";
			}
			else if(stripos($data['filter_order_id'], 'S_') !== false){
				$sql .= " AND o.order_id = '" . (int)str_ireplace('S_', '', $data['filter_order_id']) . "'";
			}
			else{
				$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
			}
		}
		
		if (!empty($data['filter_order_no'])) {
			$sql .= " AND (o.comment = 'Lazada Order No: #".$data['filter_order_no']."' OR o.comment = '11street Order No: #".$data['filter_order_no']."')";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
		

		if($this->config->get('config_employee')) {
			if (!empty($data['filter_employee_id'])) {
				$sql .= " AND (SELECT employee_id FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) LIKE '%" . $this->db->escape($data['filter_employee_id']) . "%'";
			}
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

	public function getTotalOrdersByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByProcessingStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_processing_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode));

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByCompleteStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode) . "");

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function createInvoiceNo($order_id) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && !$order_info['invoice_no']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");

			return $order_info['invoice_prefix'] . $invoice_no;
		}
	}

	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalOrderHistories($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

		return $query->row['total'];
	}

	public function getEmailsByProductsOrdered($products, $start, $end) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);

		return $query->rows;
	}

	public function getTotalEmailsByProductsOrdered($products) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");

		return $query->row['email'];
	}

	// public function checkEmployeeIdColumn() {
		// $checkEmployeeIdColumn = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "customer LIKE '%employee_id%'");
		
		// $employeeIdColumnExists = ($checkEmployeeIdColumn->row)?TRUE:FALSE;
		
		// return $employeeIdColumnExists;
	// }
}
