<?php
class ModelWarehouseTransactions extends Model {
	
	public function testing() {
		echo "Testing...";
	}
	
	public function isJSON($string){
	   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	public function coreAPI($apitype = '', $params) {
		$array = array();
		if($apitype=="upload_receiving_list") {
			
			$header_id = (isset($params['header_id'])?$params['header_id']:"");
			$detail_id = (isset($params['detail_id'])?$params['detail_id']:"");
			$customer_id = (isset($params['customer_id'])?$params['customer_id']:"");
			$customer_name = (isset($params['customer_name'])?$params['customer_name']:"");
			$doc_no = (isset($params['doc_no'])?$params['doc_no']:"");
			$item_line_no = (isset($params['item_line_no'])?$params['item_line_no']:"");
			$item_code = (isset($params['item_code'])?$params['item_code']:"");
			$item_desc1 = (isset($params['item_desc1'])?$params['item_desc1']:"");
			$item_desc2 = (isset($params['item_desc2'])?$params['item_desc2']:"");
			$item_uom = (isset($params['item_uom'])?$params['item_uom']:"");
			$item_contain_serial = (isset($params['item_contain_serial'])?$params['item_contain_serial']:"");
			$item_category = (isset($params['item_category'])?$params['item_category']:"");
			$item_brand = (isset($params['item_brand'])?$params['item_brand']:"");
			$item_barcode = (isset($params['item_barcode'])?$params['item_barcode']:"");
			$item_qty = (isset($params['item_qty'])?$params['item_qty']:"");
			$strJson = (isset($params['json_data'])?$params['json_data']:"");
			$valueHeader = (isset($params['valueHeader'])?$params['valueHeader']:"");
			
			//Query WMS Cloud Server - Start
			//http://192.168.1.30:8090/api/WMS/UploadReceivingList?js=
			//$path_url = "http://localhost:8080/atoz_opencart/upload_receiving_list_return1.txt?js=".$strJson.""; // Local
			$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/UploadReceivingList?js=".$strJson.""; // Live
			//echo $path_url;exit;
			
			// WMS API
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($curl, CURLOPT_TIMEOUT, 300);
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => str_replace(array("&amp;", " ", "+"),array("%26", "%20", "%2B"),$path_url),
				CURLOPT_POST => 0,
				CURLOPT_POSTFIELDS => array()
			));
			$resp = curl_exec($curl);
			$resp = trim(str_replace("","",strip_tags($resp)));
			curl_close($curl);
			//$arrData = json_decode($resp, true);
			//$resp = "\"Success\"";
			$resp = json_decode($resp, true);
			if($this->isJSON($resp)) {
				$resp = json_decode($resp, true);
			}
			//echo "<pre>";print_r($resp);echo "</pre>";exit;
			$new_header_ids = array($valueHeader);
			/*if($resp=="Success") {
				$array["success_msg"] = "Transaction successfully saved"; //$resp;
				$this->model_warehouse_transactions->updateUploadReceivingListToWarehouse("Completed", $new_header_ids);
			} else {
				if(!is_array($resp)) {
					$array["failure_msg"] = $resp;
				} else {
					$array["failure_msg"] = (isset($resp["Message"])?$resp["Message"]:"An error has occurred.");
				}
				$this->model_warehouse_transactions->updateUploadReceivingListToWarehouse("Pending", $new_header_ids);
			}*/
			
		} else if($apitype=="download_receiving_progress") {
			
			$header_id = (isset($params['header_id'])?$params['header_id']:"");
			$customer_id = (isset($params['customer_id'])?$params['customer_id']:"");
			$doc_no = (isset($params['doc_no'])?$params['doc_no']:"");
			$strJson = (isset($params['json_data'])?$params['json_data']:"");
			$valueHeader = (isset($params['valueHeader'])?$params['valueHeader']:"");
				
			//Query WMS Cloud Server - Start
			//http://192.168.1.30:8090/api/WMS/DownloadReceivingProgress?js=
			//$path_url = "http://localhost:8080/atoz_opencart/download_receiving_progress_return1.txt?js=".$strJson.""; // Local
			$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadReceivingProgress?js=".$strJson.""; // Live
			//echo $path_url;exit;
			
			// WMS API
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($curl, CURLOPT_TIMEOUT, 300);
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
			$arrData = $resp;
			//echo "<pre>";print_r($arrData);echo "</pre>";exit;
			if(isset($arrData["TSHTable"]) && count($arrData["TSHTable"])>0) {
				foreach($arrData["TSHTable"] as $key => $value) {
					$new_header_ids = array($header_ids[$key]);
					/*if($doc_nos[$key] == $value["Docno"] && $value["ProgressStatus"] == "Completed") {
						$array["success_msg"] = "Transaction successfully saved";
						$this->model_warehouse_transactions->updateDownloadReceivingProgressFromWarehouse($value["ProgressStatus"], $new_header_ids);
					} else {
						$array["failure_msg"] = "Transaction has failed to save";
						$this->model_warehouse_transactions->updateDownloadReceivingProgressFromWarehouse($value["ProgressStatus"], $new_header_ids);
					}*/
				}
			} else {
				$array["failure_msg"] = "Transaction has failed to save";
			}
		} else if($apitype=="cancel_receiving_list") {
			
			$header_id = (isset($params['header_id'])?$params['header_id']:"");
			$customer_id = (isset($params['customer_id'])?$params['customer_id']:"");
			$doc_no = (isset($params['doc_no'])?$params['doc_no']:"");
			$strJson = (isset($params['json_data'])?$params['json_data']:"");
			$valueHeader = (isset($params['valueHeader'])?$params['valueHeader']:"");
				
			//Query WMS Cloud Server - Start
			$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/CancelReceivingList?js=".$strJson.""; // Live
			//echo $path_url;exit;
			
			// WMS API
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($curl, CURLOPT_TIMEOUT, 300);
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
			$arrData = $resp;
			//echo "<pre>";print_r($arrData);echo "</pre>";exit;
			if($resp=="Success") {
				$array["success_msg"] = "Transaction successfully cancelled"; //$resp;
				$new_header_ids = array($header_id);
				// $this->model_warehouse_transactions->updateUploadReceivingListToWarehouse("-", $new_header_ids);
				// $this->model_warehouse_transactions->updateDownloadReceivingProgressFromWarehouse("-", $new_header_ids);

				$this->model_warehouse_transactions->updateDownloadReceivingProgressFromWarehouse("Cancelled", $new_header_ids);
			} else {
				if(!is_array($resp)) {
					$array["failure_msg"] = $resp;
				} else {
					$array["failure_msg"] = (isset($resp["Message"])?$resp["Message"]:"An error has occurred.");
				}
			}
		}
		return $array;
	}
	
	public function getTotalTransactions($data = array()) {
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "stockmovement_headers WHERE 1=1";
		if (!empty($data['filter_transaction_type'])) {
			$sql .= " AND transaction_type LIKE '%" . trim($this->db->escape($data['filter_transaction_type'])) . "%'";
		}
		if (!empty($data['filter_transaction_no'])) {
			$sql .= " AND transaction_no LIKE '%" . trim($this->db->escape($data['filter_transaction_no'])) . "%'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getTransaction($id) {
		//echo "SELECT DISTINCT * FROM " . DB_PREFIX . "stockmovement_headers WHERE id = '" . (int)$id . "' ";exit;
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "stockmovement_headers WHERE id = '" . (int)$id . "' ");
		return $query->row;
	}

	public function getTransactions($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "stockmovement_headers WHERE 1=1";
		if (!empty($data['filter_transaction_type'])) {
			$sql .= " AND transaction_type LIKE '%" . trim($this->db->escape($data['filter_transaction_type'])) . "%'";
		}
		if (!empty($data['filter_transaction_no'])) {
			$sql .= " AND transaction_no LIKE '%" . trim($this->db->escape($data['filter_transaction_no'])) . "%'";
		}
		$sql .= " GROUP BY id";

		$sort_data = array(
			'transaction_type',
			'transaction_no',
			'total_amount',
			'net_amount',
			'total_item_lines',
			'received_datetime',
			'status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
		}
		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 300;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTotalDetailTransactions($data = array()) {
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "stockmovement_details WHERE 1=1";
		if (!empty($data['tran_id'])) {
			$sql .= " AND transaction_id = '" . trim($this->db->escape($data['tran_id'])) . "'";
		} else {
			$sql .= " AND 1=0";
		}
		if (!empty($data['filter_product_name'])) {
			$sql .= " AND product_name = '" . trim($this->db->escape($data['filter_product_name'])) . "'";
		}
		if (!empty($data['filter_product_type'])) {
			$sql .= " AND product_type LIKE '%" . trim($this->db->escape($data['filter_product_type'])) . "%'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getDetailTransaction($id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "stockmovement_details WHERE id = '" . (int)$id . "' ");
		return $query->row;
	}

	public function getDetailTransactions($data = array()) {
		$sql = "SELECT " . DB_PREFIX . "stockmovement_details.*," . DB_PREFIX . "product.width, " . DB_PREFIX . "product.height, " . DB_PREFIX . "product.length FROM " . DB_PREFIX . "stockmovement_details LEFT JOIN " . DB_PREFIX . "product ON " . DB_PREFIX . "stockmovement_details.product_id = " . DB_PREFIX . "product.product_id WHERE 1=1";
		if (!empty($data['tran_id'])) {
			$sql .= " AND transaction_id = '" . trim($this->db->escape($data['tran_id'])) . "'";
		} else {
			$sql .= " AND 1=0";
		}
		if (!empty($data['filter_product_name'])) {
			$sql .= " AND product_name = '" . trim($this->db->escape($data['filter_product_name'])) . "'";
		}
		if (!empty($data['filter_product_type'])) {
			$sql .= " AND product_type LIKE '%" . trim($this->db->escape($data['filter_product_type'])) . "%'";
		}
		if (!empty($data['filter_transaction_type'])) {
			$sql .= " AND transaction_type LIKE '%" . trim($this->db->escape($data['filter_transaction_type'])) . "%'";
		}
		$sql .= " GROUP BY id";

		$sort_data = array(
			'transaction_id',
			'row_no',
			'product_name',
			'product_model',
			'product_type',
			'quantity',
			'uom',
			'remarks'/*,
			'product_status',
			'sync_status'*/
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY row_no";
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		//if (isset($data['start']) || isset($data['limit'])) {
		//	if ($data['start'] < 0) {
		//		$data['start'] = 0;
		//	}

		//	if ($data['limit'] < 1) {
		//		$data['limit'] = 300;
		//	}

		//	$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		//}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function editTransactionHeader($id, $data) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stockmovement_details WHERE transaction_id = '" . (int)$id . "'");
		$total_item_lines = $query->row['total'];
		//echo $total_item_lines;exit;
		//ecowarehouse_sync_status = '" . $this->db->escape($data['ecowarehouse_sync_status']) . "',
		//warehouseeco_sync_status = '" . $this->db->escape($data['warehouseeco_sync_status']) . "',
		$this->db->query("UPDATE " . DB_PREFIX . "stockmovement_headers SET transaction_type = '" . $this->db->escape($data['transaction_type']) . "', 
			transaction_no = '" . $this->db->escape($data['transaction_no']) . "', 
			remarks = '" . $this->db->escape($data['remarks']) . "', 
			total_item_lines = '" . $total_item_lines . "', 
			received_datetime = '" . $this->db->escape($data['received_datetime']) . "',
			num_of_bin = '" . $this->db->escape($data['num_of_bin']) . "',
			status = '" . $this->db->escape($data['status']) . "',
			date_modified = NOW() WHERE id = '" . (int)$id . "'");
	}
	
	public function addTransactionHeader($data) {
		//echo "<pre>";print_r($data);echo "</pre>";exit;		
		
		//Check is unique transaction no - Start
		$maxTry = 0;
		$data['transaction_no'] = $this->generateRunningNo('Storage');
		while($data['transaction_no']!="" && !$this->checkIsUniqueData($data['transaction_no'])){
			$this->updateRunningNo('Storage');
			$data['transaction_no'] = $this->generateRunningNo('Storage');
			$maxTry++;
			if($maxTry>1000){break;}
		}
		//Check is unique transaction no - End
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "stockmovement_headers SET transaction_type = '" . $this->db->escape($data['transaction_type']) . "', 
			transaction_no = '" . $this->db->escape($data['transaction_no']) . "', 
			remarks = '" . $this->db->escape($data['remarks']) . "', 
			total_item_lines = '0', 
			received_datetime = '" . $this->db->escape($data['received_datetime']) . "',
			status = '" . $this->db->escape($data['status']) . "',
			num_of_bin = '" . $this->db->escape($data['num_of_bin']) . "',
			ecowarehouse_sync_status = '" . $this->db->escape($data['ecowarehouse_sync_status']) . "',
			warehouseeco_sync_status = '" . $this->db->escape($data['warehouseeco_sync_status']) . "',
			date_added = NOW()");
		$id = $this->db->getLastId();
		return $id;
	}
	
	public function deleteTransaction($id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "stockmovement_headers WHERE id = '" . (int)$id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "stockmovement_details WHERE transaction_id = '" . (int)$id . "'");
	}
	
	public function addEditTransactionDetails($id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "stockmovement_details WHERE transaction_id = '" . (int)$id . "'");
		if (isset($data['warehouse_tran'])) {
			foreach ($data['warehouse_tran'] as $warehouse_tran) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "stockmovement_details SET transaction_id = '" . (int)$id . "', 
					row_no = '" . (int)$warehouse_tran['row_no'] . "', 
					product_name = '" . $this->db->escape($warehouse_tran['product_name']) . "', 
					product_id = '" . $this->db->escape($warehouse_tran['product_id']) . "', 
					product_model = '" . $this->db->escape($warehouse_tran['product_model']) . "', 
					product_type = '" . $this->db->escape($warehouse_tran['product_type']) . "', 
					matching_code = '" . $this->db->escape($warehouse_tran['matching_code']) . "', 
					quantity = '" . $warehouse_tran['quantity'] . "', 
					remarks = '" . $this->db->escape($warehouse_tran['remarks']) . "', 
					date_added = NOW()");
			}
			$total_item_lines = count($data['warehouse_tran']);
			$this->db->query("UPDATE " . DB_PREFIX . "stockmovement_headers SET total_item_lines = '" . $total_item_lines . "' WHERE id = '" . (int)$id . "'");
		}
	}
	
	
	public function addProduct($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

		$product_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'");

						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				if ((int)$product_reward['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
				}
			}
		}

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['product_recurring'])) {
			foreach ($data['product_recurring'] as $recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$recurring['customer_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
			}
		}

		$this->cache->delete('product');

		return $product_id;
	}

	public function editProduct($product_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				if ((int)$value['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int)$product_id);

		if (isset($data['product_recurring'])) {
			foreach ($data['product_recurring'] as $product_recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$product_recurring['customer_group_id'] . ", `recurring_id` = " . (int)$product_recurring['recurring_id']);
			}
		}

		$this->cache->delete('product');
	}

	public function copyProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int)$product_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['product_attribute'] = $this->getProductAttributes($product_id);
			$data['product_description'] = $this->getProductDescriptions($product_id);
			$data['product_discount'] = $this->getProductDiscounts($product_id);
			$data['product_filter'] = $this->getProductFilters($product_id);
			$data['product_image'] = $this->getProductImages($product_id);
			$data['product_option'] = $this->getProductOptions($product_id);
			$data['product_related'] = $this->getProductRelated($product_id);
			$data['product_reward'] = $this->getProductRewards($product_id);
			$data['product_special'] = $this->getProductSpecials($product_id);
			$data['product_category'] = $this->getProductCategories($product_id);
			$data['product_download'] = $this->getProductDownloads($product_id);
			$data['product_layout'] = $this->getProductLayouts($product_id);
			$data['product_store'] = $this->getProductStores($product_id);
			$data['product_recurrings'] = $this->getRecurrings($product_id);

			$this->addProduct($data);
		}
	}

	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_recurring WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE product_id = '" . (int)$product_id . "'");

		$this->cache->delete('product');
	}

	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON(pov.option_value_id = ov.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY ov.sort_order ASC");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductOptionValue($product_id, $product_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}

	public function getProductLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	public function getProductRelated($product_id) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getRecurrings($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_image']) && !is_null($data['filter_image'])) {
			if ($data['filter_image'] == 1) {
				$sql .= " AND (p.image IS NOT NULL AND p.image <> '' AND p.image <> 'no_image.png')";
			} else {
				$sql .= " AND (p.image IS NULL OR p.image = '' OR p.image = 'no_image.png')";
			}
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalProductsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}
        
        public function getProductLengthValue($product_id) {
		$query = $this->db->query("SELECT oc_length_class.value AS value FROM " . DB_PREFIX . "product LEFT JOIN oc_length_class ON oc_product.length_class_id = oc_length_class.length_class_id WHERE oc_product.product_id = '" . (int)$product_id . "'");

		return $query->row['value'];
	}

	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
	
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int)$product_id . "' ");

		return $query->row;
	}
	
	public function updateUploadReceivingListToWarehouse($strStatus = '', $arrIDs = array()) {
		if (is_array($arrIDs) && count($arrIDs)>0 && $arrIDs[0]!="") {
			//echo "UPDATE `" . DB_PREFIX . "stockmovement_headers` SET ecowarehouse_sync_status = '".$strStatus."', ecowarehouse_sync_timest = '".date("Y-m-d H:i:s")."' WHERE id IN ('" . implode("','",$arrIDs) . "')";exit;
			$this->db->query("UPDATE `" . DB_PREFIX . "stockmovement_headers` SET ecowarehouse_sync_status = '".$strStatus."', ecowarehouse_sync_timest = '".date("Y-m-d H:i:s")."' WHERE id IN ('" . implode("','",$arrIDs) . "')");
			return true;
		}
	}
	public function updateDownloadReceivingProgressFromWarehouse($strStatus = '', $arrIDs = array()) {
		if (is_array($arrIDs) && count($arrIDs)>0 && $arrIDs[0]!="") {
			$this->db->query("UPDATE `" . DB_PREFIX . "stockmovement_headers` SET warehouseeco_sync_status = '".$strStatus."', warehouseeco_sync_timest = '".date("Y-m-d H:i:s")."' WHERE id IN ('" . implode("','",$arrIDs) . "')");
			return true;
		}
	}
	
	
	public function checkIsUniqueData($value) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stockmovement_headers WHERE transaction_no = '" . $value . "'");
		$total_item_lines = $query->row['total'];
		//echo $total_item_lines;exit;
		if($total_item_lines==0) {
			return true;
		} else {
			return false;	
		}
	}
	
	public function generateRunningNo($id){
		$no = "";
		if($id != ""){
			$sql = "SELECT * FROM `oc_running_number` WHERE `module_uid`='".$id."' LIMIT 1";
			$query = $this->db->query($sql);
			foreach ($query->rows as $result) {
				$no .= $result['prefix'];
				$iteration = $result['current'] + 1;
				$digit = $result['padding'];
				for($i = strlen($iteration); $i<$digit; $i++){
					$no .= "0";
				}
				$no .= $iteration.$result['suffix'];
			}
		}
		return $no;
	}
	
	public function updateRunningNo($id){
		$sql = "SELECT * FROM `oc_running_number` WHERE `module_uid`='".$id."'";
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$current = $result['current'] + 1;
		}
		if(isset($current)){
			$sql = "UPDATE `oc_running_number` SET `current`='".$current."' WHERE `module_uid`='".$id."'";
			$this->db->query($sql);
		}
	}
}
