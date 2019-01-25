<?php
class ControllerWarehouseTransactions extends Controller {
	private $error = array();
	
	public function ajaxAPI($apitype = '', $params) {
		$array = array();
		$this->load->model('warehouse/transactions');
		if($apitype=="upload_receiving_list") {
			if (isset($this->request->post['header_id'])) { $header_id = $this->request->post['header_id'];
			} else if (isset($this->request->get['header_id'])) { $header_id = $this->request->get['header_id'];
			} else if (isset($params['header_id'])) { $header_id = $params['header_id']; }
			
			if (isset($this->request->post['detail_id'])) { $detail_id = $this->request->post['detail_id'];
			} else if (isset($this->request->get['detail_id'])) { $detail_id = $this->request->get['detail_id'];
			} else if (isset($params['detail_id'])) { $detail_id = $params['detail_id']; }
			
			if (isset($this->request->post['customer_id'])) { $customer_id = $this->request->post['customer_id'];
			} else if (isset($this->request->get['customer_id'])) { $customer_id = $this->request->get['customer_id'];
			} else if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
			
			if (isset($this->request->post['customer_name'])) { $customer_name = $this->request->post['customer_name'];
			} else if (isset($this->request->get['customer_name'])) { $customer_name = $this->request->get['customer_name'];
			} else if (isset($params['customer_name'])) { $customer_name = $params['customer_name']; }
			
			if (isset($this->request->post['doc_no'])) { $doc_no = $this->request->post['doc_no'];
			} else if (isset($this->request->get['doc_no'])) { $doc_no = $this->request->get['doc_no'];
			} else if (isset($params['doc_no'])) { $doc_no = $params['doc_no']; }
			
			if (isset($this->request->post['doc_date'])) { $doc_date = $this->request->post['doc_date'];
			} else if (isset($this->request->get['doc_date'])) { $doc_date = $this->request->get['doc_date'];
			} else if (isset($params['doc_date'])) { $doc_date = $params['doc_date']; }
			
			if (isset($this->request->post['item_line_no'])) { $item_line_no = $this->request->post['item_line_no'];
			} else if (isset($this->request->get['item_line_no'])) { $item_line_no = $this->request->get['item_line_no'];
			} else if (isset($params['item_line_no'])) { $item_line_no = $params['item_line_no']; }
			
			if (isset($this->request->post['item_code'])) { $item_code = $this->request->post['item_code'];
			} else if (isset($this->request->get['item_code'])) { $item_code = $this->request->get['item_code'];
			} else if (isset($params['item_code'])) { $item_code = $params['item_code']; }
			
			if (isset($this->request->post['item_desc1'])) { $item_desc1 = $this->request->post['item_desc1'];
			} else if (isset($this->request->get['item_desc1'])) { $item_desc1 = $this->request->get['item_desc1'];
			} else if (isset($params['item_desc1'])) { $item_desc1 = $params['item_desc1']; }
			
			if (isset($this->request->post['item_desc2'])) { $item_desc2 = $this->request->post['item_desc2'];
			} else if (isset($this->request->get['item_desc2'])) { $item_desc2 = $this->request->get['item_desc2'];
			} else if (isset($params['item_desc2'])) { $item_desc2 = $params['item_desc2']; }
			
			if (isset($this->request->post['item_uom'])) { $item_uom = $this->request->post['item_uom'];
			} else if (isset($this->request->get['item_uom'])) { $item_uom = $this->request->get['item_uom'];
			} else if (isset($params['item_uom'])) { $item_uom = $params['item_uom']; }
			
			if (isset($this->request->post['item_contain_serial'])) { $item_contain_serial = $this->request->post['item_contain_serial'];
			} else if (isset($this->request->get['item_contain_serial'])) { $item_contain_serial = $this->request->get['item_contain_serial'];
			} else if (isset($params['item_contain_serial'])) { $item_contain_serial = $params['item_contain_serial']; }
			
			if (isset($this->request->post['item_category'])) { $item_category = $this->request->post['item_category'];
			} else if (isset($this->request->get['item_category'])) { $item_category = $this->request->get['item_category'];
			} else if (isset($params['item_category'])) { $item_category = $params['item_category']; }
			
			if (isset($this->request->post['item_brand'])) { $item_brand = $this->request->post['item_brand'];
			} else if (isset($this->request->get['item_brand'])) { $item_brand = $this->request->get['item_brand'];
			} else if (isset($params['item_brand'])) { $item_brand = $params['item_brand']; }
			
			if (isset($this->request->post['item_barcode'])) { $item_barcode = $this->request->post['item_barcode'];
			} else if (isset($this->request->get['item_barcode'])) { $item_barcode = $this->request->get['item_barcode'];
			} else if (isset($params['item_barcode'])) { $item_barcode = $params['item_barcode']; }
			
			if (isset($this->request->post['item_qty'])) { $item_qty = $this->request->post['item_qty'];
			} else if (isset($this->request->get['item_qty'])) { $item_qty = $this->request->get['item_qty'];
			} else if (isset($params['item_qty'])) { $item_qty = $params['item_qty']; }
			
			if (isset($this->request->post['item_num_of_bin'])) { $item_num_of_bin = $this->request->post['item_num_of_bin'];
			} else if (isset($this->request->get['item_num_of_bin'])) { $item_num_of_bin = $this->request->get['item_num_of_bin'];
			} else if (isset($params['item_num_of_bin'])) { $item_num_of_bin = $params['item_num_of_bin']; }
                        
                        if (isset($this->request->post['item_width'])) { $item_width = $this->request->post['item_width'];
			} else if (isset($this->request->get['item_width'])) { $item_width = $this->request->get['item_width'];
			} else if (isset($params['item_width'])) { $item_width = $params['item_width']; }
                        
                        if (isset($this->request->post['item_height'])) { $item_height = $this->request->post['item_height'];
			} else if (isset($this->request->get['item_height'])) { $item_height = $this->request->get['item_height'];
			} else if (isset($params['item_height'])) { $item_height = $params['item_height']; }
                        
                        if (isset($this->request->post['item_depth'])) { $item_depth = $this->request->post['item_depth'];
			} else if (isset($this->request->get['item_depth'])) { $item_depth = $this->request->get['item_depth'];
			} else if (isset($params['item_depth'])) { $item_depth = $params['item_depth']; }
                        
                        if (isset($this->request->post['item_sizeqty'])) { $item_sizeqty = $this->request->post['item_sizeqty'];
			} else if (isset($this->request->get['item_sizeqty'])) { $item_sizeqty = $this->request->get['item_sizeqty'];
			} else if (isset($params['item_sizeqty'])) { $item_sizeqty = $params['item_sizeqty']; }
			
			$header_ids = ""; $detail_ids = ""; $customer_ids = ""; $customer_names = ""; $doc_nos = ""; $doc_dates = ""; $item_line_nos = ""; $item_codes = ""; $item_desc1s = "";
			$item_desc2s = ""; $item_uoms = ""; $item_contain_serials = ""; $item_categorys = ""; $item_brands = ""; $item_barcodes = ""; $item_qtys = ""; $item_num_of_bins = "";
                        $item_widths = ""; $item_heights = ""; $item_depths = ""; $item_sizeqtys = "";
			
			if($header_id!="") {
				$header_ids = explode("|",$header_id); 
				$header_ids = array_unique($header_ids);
				foreach($header_ids as $keyHeader => $valueHeader) {
				
					$arrHeaderData = $this->model_warehouse_transactions->getTransaction($valueHeader);
					//echo "<pre>";print_r($arrHeaderData);echo "</pre>";exit;
					
					// DownloadAvailableLocation API - Start
					//$path_url = "http://localhost:8080/atoz_opencart/download_available_location.txt"; // Local
					$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadAvailableLocation"; // Live
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
					if($this->model_warehouse_transactions->isJSON($resp)) {
						$resp = json_decode($resp, true);
					}
					$arrAvailableBinData = $resp;
					//echo "<pre>";print_r($arrAvailableBinData);echo "</pre>";exit;
					//echo "<pre>";print_r($arrAvailableBinData);echo "</pre>";exit;
					$intAvailableBin = (isset($arrAvailableBinData["TSHTable"][0]["NumOfLoc"])?$arrAvailableBinData["TSHTable"][0]["NumOfLoc"]:"0");
					//echo "<pre>";print_r($intAvailableBin);echo "</pre>";exit;
					// DownloadAvailableLocation API - End
					if($intAvailableBin==0) { 
						$array["failure_msg"] = "Insufficient number of bins for storage.";
						break;
					}
					if($arrHeaderData["num_of_bin"]=="" || !is_numeric($arrHeaderData["num_of_bin"])) {
						$arrHeaderData["num_of_bin"] = "1";	
					}
					if($intAvailableBin<$arrHeaderData["num_of_bin"]) { 
						$array["failure_ignore_msg"] = "Insufficient number of bins for storage.";
						//break;
					}
					
					/*$detail_ids = explode("|",$detail_id);
					$customer_ids = explode("|",$customer_id);
					$customer_names = explode("|",$customer_name);
					$doc_nos = explode("|",$doc_no);
					//$doc_dates = explode("|",$doc_date);
					$item_line_nos = explode("|",$item_line_no);
					$item_codes = explode("|",$item_code);
					$item_desc1s = explode("|",$item_desc1);
					$item_desc2s = explode("|",$item_desc2);
					$item_uoms = explode("|",$item_uom);
					$item_contain_serials = explode("|",$item_contain_serial);
					$item_categorys = explode("|",$item_category);
					$item_brands = explode("|",$item_brand);
					$item_barcodes = explode("|",$item_barcode);
					$item_qtys = explode("|",$item_qty);
					//$item_num_of_bins = explode("|",$item_num_of_bin);
                                        ////$item_widths = explode("|",$item_width);
                                        ////$item_heights = explode("|",$item_height);
                                        ////$item_depths = explode("|",$item_depth);
                                        ////$item_sizeqtys = explode("|",$item_sizeqty);
					$dataJS = array();
					foreach($customer_ids as $key => $value) {
						$dataJS["TSHTable"][$key]["CustCode"] = (isset($customer_ids[$key])?$customer_ids[$key]:"");
						$dataJS["TSHTable"][$key]["CustName"] = (isset($customer_names[$key])?addslashes($customer_names[$key]):"");
						$dataJS["TSHTable"][$key]["Docno"] = (isset($doc_nos[$key])?$doc_nos[$key]:"");
						$dataJS["TSHTable"][$key]["DocDate"] = date("d/m/Y", strtotime($arrHeaderData["received_datetime"])); //(isset($doc_dates[$key])?$doc_dates[$key]:"");
						$dataJS["TSHTable"][$key]["ItemLineNo"] = (isset($item_line_nos[$key])?$item_line_nos[$key]:"");
						$dataJS["TSHTable"][$key]["ItemCode"] = (isset($item_codes[$key])?$item_codes[$key]:"");
						$dataJS["TSHTable"][$key]["ItemDesc1"] = (isset($item_desc1s[$key])?addslashes($item_desc1s[$key]):"");
						$dataJS["TSHTable"][$key]["ItemDesc2"] = (isset($item_desc2s[$key])?addslashes($item_desc2s[$key]):"");
						$dataJS["TSHTable"][$key]["ItemUOM"] = (isset($item_uoms[$key])?$item_uoms[$key]:"");
						$dataJS["TSHTable"][$key]["ItemContainSerial"] = (isset($item_contain_serials[$key])?$item_contain_serials[$key]:"");
						$dataJS["TSHTable"][$key]["ItemCategory"] = (isset($item_categorys[$key])?$item_categorys[$key]:"");
						$dataJS["TSHTable"][$key]["ItemBrand"] = (isset($item_brands[$key])?$item_brands[$key]:"");
						$dataJS["TSHTable"][$key]["ItemBarcode"] = (isset($item_barcodes[$key])?addslashes($item_barcodes[$key]):"");
						$dataJS["TSHTable"][$key]["ItemQty"] = (isset($item_qtys[$key])?$item_qtys[$key]:"");
						$dataJS["TSHTable"][$key]["NumOfBin"] = $arrHeaderData["num_of_bin"]; //(isset($item_num_of_bins[$key])?$item_num_of_bins[$key]:"");
                                                $dataJS["TSHTable"][$key]["Width"] = (isset($item_widths[$key])?$item_widths[$key]:"");
                                                $dataJS["TSHTable"][$key]["Height"] = (isset($item_heights[$key])?$item_heights[$key]:"");
                                                $dataJS["TSHTable"][$key]["Depth"] = (isset($item_depths[$key])?$item_depths[$key]:"");
                                                $dataJS["TSHTable"][$key]["Qty"] = (isset($item_sizeqtys[$key])?$item_sizeqtys[$key]:"");
					}
					$strJson = json_encode($dataJS);
					
					//echo "<pre>";print_r($this->request);echo "</pre>";exit;
					$apiParams = array();
					$apiParams['header_id'] = $header_id;
					$apiParams['detail_id'] = $detail_id;
					$apiParams['customer_id'] = $customer_id;
					$apiParams['customer_name'] = $customer_name;
					$apiParams['doc_no'] = $doc_no;
					$apiParams['item_line_no'] = $item_line_no;
					$apiParams['item_code'] = $item_code;
					$apiParams['item_desc1'] = $item_desc1;
					$apiParams['item_desc2'] = $item_desc2;
					$apiParams['item_uom'] = $item_uom;
					$apiParams['item_contain_serial'] = $item_contain_serial;
					$apiParams['item_category'] = $item_category;
					$apiParams['item_brand'] = $item_brand;
					$apiParams['item_barcode'] = $item_barcode;
					$apiParams['item_qty'] = $item_qty;
					$apiParams['json_data'] = $strJson;
					$apiParams['valueHeader'] = $valueHeader;
					$array = $this->model_warehouse_transactions->coreAPI($apitype, $apiParams);*/
				}
			} else {
				$array["failure_msg"] = "Please add one or more record on Transaction Detail"; //$resp;	
			}
			
		} else if($apitype=="download_receiving_progress") {
			
			if (isset($this->request->post['header_id'])) { $header_id = $this->request->post['header_id'];
			} else if (isset($this->request->get['header_id'])) { $header_id = $this->request->get['header_id'];
			} else if (isset($params['header_id'])) { $header_id = $params['header_id']; }
			
			if (isset($this->request->post['customer_id'])) { $customer_id = $this->request->post['customer_id'];
			} else if (isset($this->request->get['customer_id'])) { $customer_id = $this->request->get['customer_id'];
			} else if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
			
			if (isset($this->request->post['doc_no'])) { $doc_no = $this->request->post['doc_no'];
			} else if (isset($this->request->get['doc_no'])) { $doc_no = $this->request->get['doc_no'];
			} else if (isset($params['doc_no'])) { $doc_no = $params['doc_no']; }
			
			$header_ids = ""; $customer_ids = ""; $doc_dates = "";
			$header_ids = explode("|",$header_id); $header_ids = array_unique($header_ids);
			$customer_ids = explode("|",$customer_id);
			$doc_nos = explode("|",$doc_no);
			foreach($header_ids as $keyHeader => $valueHeader) {
				
				$arrHeaderData = $this->model_warehouse_transactions->getTransaction($valueHeader);
				//echo "<pre>";print_r($arrHeaderData);echo "</pre>";exit;
				
				//$item_num_of_bins = explode("|",$item_num_of_bin);
				$dataJS = array();
				foreach($customer_ids as $key => $value) {
					$dataJS["TSHTable"][$key]["CustCode"] = (isset($customer_ids[$key])?$customer_ids[$key]:"");
					$dataJS["TSHTable"][$key]["Docno"] = (isset($doc_nos[$key])?$doc_nos[$key]:"");
				}
				$strJson = json_encode($dataJS);
				
				$apiParams = array();
				$apiParams['header_id'] = $header_id;
				$apiParams['customer_id'] = $customer_id;
				$apiParams['doc_no'] = $doc_no;
				$apiParams['json_data'] = $strJson;
				$apiParams['valueHeader'] = $valueHeader;
	
				$array = $this->model_warehouse_transactions->coreAPI($apitype, $apiParams);
			}
		} else if($apitype=="cancel_receiving_list") {
			
			if (isset($this->request->post['header_id'])) { $header_id = $this->request->post['header_id'];
			} else if (isset($this->request->get['header_id'])) { $header_id = $this->request->get['header_id'];
			} else if (isset($params['header_id'])) { $header_id = $params['header_id']; }
			
			if (isset($this->request->post['customer_id'])) { $customer_id = $this->request->post['customer_id'];
			} else if (isset($this->request->get['customer_id'])) { $customer_id = $this->request->get['customer_id'];
			} else if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
			
			if (isset($this->request->post['doc_no'])) { $doc_no = $this->request->post['doc_no'];
			} else if (isset($this->request->get['doc_no'])) { $doc_no = $this->request->get['doc_no'];
			} else if (isset($params['doc_no'])) { $doc_no = $params['doc_no']; }
			
			$header_ids = ""; $customer_ids = ""; $doc_dates = "";
			$header_ids = explode("|",$header_id); $header_ids = array_unique($header_ids);
			$customer_ids = explode("|",$customer_id);
			$doc_nos = explode("|",$doc_no);
			foreach($header_ids as $keyHeader => $valueHeader) {
				
				$arrHeaderData = $this->model_warehouse_transactions->getTransaction($valueHeader);
				//echo "<pre>";print_r($arrHeaderData);echo "</pre>";exit;
				
				//$item_num_of_bins = explode("|",$item_num_of_bin);
				$dataJS = array();
				foreach($customer_ids as $key => $value) {
					$dataJS["TSHTable"][$key]["CustCode"] = (isset($customer_ids[$key])?$customer_ids[$key]:"");
					$dataJS["TSHTable"][$key]["Docno"] = (isset($doc_nos[$key])?$doc_nos[$key]:"");
				}
				$strJson = json_encode($dataJS);
				
				$apiParams = array();
				$apiParams['header_id'] = $header_id;
				$apiParams['customer_id'] = $customer_id;
				$apiParams['doc_no'] = $doc_no;
				$apiParams['json_data'] = $strJson;
				$apiParams['valueHeader'] = $valueHeader;
	
				$array = $this->model_warehouse_transactions->coreAPI($apitype, $apiParams);
			}
			
		}
		return $array;
	}
	
	public function sync() {
		$this->load->language('warehouse/transactions');
		$this->document->setTitle($this->language->get('heading_warehouse_transaction'));

		if (isset($this->request->get['sync_storage'])) {
			$sync_storages = $this->request->get['sync_storage'];
		} else {
			$sync_storages = "";
		}
		//echo "<pre>";print_r($sync_storages);echo "</pre>";exit;
			
		$this->load->model('warehouse/transactions');
		
		$arrStorageIDs = array();
		$headerID = "";
		if($sync_storages!="") {
			$arrStorageIDs = explode("|", $sync_storages);
			foreach($arrStorageIDs as $key => $value) {
				$header_result[$value] = $this->model_warehouse_transactions->getTransaction($value);
				//echo "<pre>";print_r($header_result);echo "</pre>";exit;
				
				$data['tran_id'] = $value;
				if($headerID=="") { $headerID = $value; }
				$results = $this->model_warehouse_transactions->getDetailTransactions($data);
				//echo "<pre>";print_r($data);echo "</pre>";exit;
				
				/**"CustCode":"TEST",
				"CustName":"Test Sdn. Bhd.",
				"Docno":"PO123456",
				"DocDate":"18/11/2016",
				"ItemLineNo":"1",
				"ItemCode":"ABCTEST",
				"ItemDesc1":"ABCTEST",
				"ItemDesc2":"",
				"ItemUOM":"PC",
				"ItemContainSerial":"N",
				"ItemCategory":"Test",
				"ItemBrand":"TestBrand",
				"ItemBarcode":"TestBarcode",
				"ItemQty":"1000",
				"NumOfBin":"2"**/
				
				// WMS Function Call - Start
				$header_ids = "";
				$detail_ids = "";
				$customer_ids = "";
				$customer_names = "";
				$doc_nos = "";
				$doc_dates = "";
				$item_line_nos = "";
				$item_codes = "";
				$item_desc1s = "";
				$item_desc2s = "";
				$item_uoms = "";
				$item_contain_serials = "";
				$item_categorys = "";
				$item_brands = "";
				$item_barcodes = "";
				$item_qtys = "";
				$item_num_of_bins = "";
                                $item_widths = "";
                                $item_heights = "";
                                $item_depths = "";
                                $item_sizeqtys = "";
				foreach ($results as $result) {
					$arrProductData = $this->model_warehouse_transactions->getProduct($result['product_id']);
                                        
                                        if(empty($arrProductData)) {
                                            $valtomm = 0;
                                        }else {
                                            $valtomm = 10/floatval($this->model_warehouse_transactions->getProductLengthValue($result['product_id']));
                                        }
                                        
					//echo "<pre>x";print_r($arrProductData);echo "</pre>";exit;
					if($customer_ids!="") {
						$header_ids .= "|" . $value;
						$detail_ids .= "|" . $result['id'];
						$customer_ids .= "|" . $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
						$customer_names .= "|" . $this->config->get('config_name'); //constant("PARTNER_UNIQUE_NAME");
						//$doc_nos .= "|" . constant("PARTNER_UNIQUE_ID") . "@" . $header_result[$value]["transaction_no"]; // partnerID + delimiter + transaction number
						$doc_nos .= "|" . $this->config->get('config_unique_brp_partner_id') . "@" . $header_result[$value]["transaction_no"]; // partnerID + delimiter + transaction number
						$doc_dates .= "|" . ""; //date("d/m/Y", strtotime($result['date_added']));
						$item_line_nos .= "|" . $result['row_no'];
						if($arrProductData['data_source']!="" && $arrProductData['data_source']!="0") {
							$item_codes .= "|" . $result['matching_code'];
						} else {
							$item_codes .= "|" . $result['product_model'];
						}
						$item_desc1s .= "|" . $result['remarks'];
						$item_desc2s .= "|" . "";
						$item_uoms .= "|" . $result['uom'];
                                                if($this->config->get('config_enable_serialno')) {
                                                    $item_contain_serials .= "|" . ($result['contain_serialno'] ? "Y" : "N" );
                                                }else {
                                                    $item_contain_serials .= "|" . "N";
                                                }
						$item_categorys .= "|" . "";
						$item_brands .= "|" . "";
						$item_barcodes .= "|" . (isset($arrProductData["upc"])?$arrProductData["upc"]:""); //constant("PARTNER_UNIQUE_ID") . "@" . $result['id'];
						$item_qtys .= "|" . $result['quantity'];
						$item_num_of_bins .= "|" . "";
                                                $item_widths .= "|" . (floatval($result['width'])*$valtomm);
                                                $item_heights .= "|" . (floatval($result['height'])*$valtomm);
                                                $item_depths .= "|" . (floatval($result['length'])*$valtomm);
                                                $item_sizeqtys .= "|" . (floatval(1));
					} else {
						$header_ids .= $value;
						$detail_ids .= $result['id'];
						$customer_ids .= $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
						$customer_names .= $this->config->get('config_name'); //constant("PARTNER_UNIQUE_NAME");
						//$doc_nos .= constant("PARTNER_UNIQUE_ID") . "@" . $header_result[$value]["transaction_no"]; // partnerID + delimiter + transaction number
						$doc_nos .= $this->config->get('config_unique_brp_partner_id') . "@" . $header_result[$value]["transaction_no"]; // partnerID + delimiter + transaction number
						$doc_dates .= ""; //date("d/m/Y", strtotime($result['date_added']));
						$item_line_nos .= $result['row_no'];
						if($arrProductData['data_source']!="" && $arrProductData['data_source']!="0") {
							$item_codes .= $result['matching_code'];
						} else {
							$item_codes .= $result['product_model'];
						}
						$item_desc1s .= $result['remarks'];
						$item_desc2s .= "";
						$item_uoms .= $result['uom'];
                                                if($this->config->get('config_enable_serialno')) {
                                                    $item_contain_serials .= ($result['contain_serialno'] ? "Y" : "N" );
                                                }else {
                                                    $item_contain_serials .= "N";
                                                }
						$item_categorys .= "";
						$item_brands .= "";
						$item_barcodes .= (isset($arrProductData["upc"])?$arrProductData["upc"]:""); //constant("PARTNER_UNIQUE_ID") . "@" . $result['id'];
						$item_qtys .= $result['quantity'];
						$item_num_of_bins .= "";
                                                $item_widths .= (floatval($result['width'])*$valtomm);
                                                $item_heights .= (floatval($result['height'])*$valtomm);
                                                $item_depths .= (floatval($result['length'])*$valtomm);
                                                $item_sizeqtys .= (floatval(1));
					}
				}
				$apiParams = array(
				   "header_id"=>$header_ids, 
				   "detail_id"=>$detail_ids, 
				   "customer_id"=>$customer_ids, 
				   "customer_name"=>$customer_names, 
				   "doc_no"=>$doc_nos, 
				   "doc_date"=>$doc_dates, 
				   "item_line_no"=>$item_line_nos, 
				   "item_code"=>$item_codes, 
				   "item_desc1"=>$item_desc1s, 
				   "item_desc2"=>$item_desc2s, 
				   "item_uom"=>$item_uoms, 
				   "item_contain_serial"=>$item_contain_serials, 
				   "item_category"=>$item_categorys, 
				   "item_brand"=>$item_brands, 
				   "item_barcode"=>$item_barcodes, 
				   "item_qty"=>$item_qtys, 
				   "item_num_of_bin"=>$item_num_of_bins
                                   ////"item_width"=>$item_widths, 
                                   ////"item_height"=>$item_heights, 
                                   ////"item_depth"=>$item_depths, 
                                   ////"item_sizeqty"=>$item_sizeqtys
				);
				//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
				$arrAPIResults = $this->ajaxAPI("upload_receiving_list", $apiParams);
				if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
					$this->session->data['failure_msg'] = $arrAPIResults["failure_msg"];
					break;
				} if(isset($arrAPIResults["failure_ignore_msg"]) && $arrAPIResults["failure_ignore_msg"]!="") {
					$this->session->data['failure_ignore_msg'] = $arrAPIResults["failure_ignore_msg"];
				}
				//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
				// WMS Function Call - End
				
			}
		}
		//echo"XX1";exit;
		if(!isset($this->session->data['failure_msg']) || (isset($this->session->data['failure_msg']) && $this->session->data['failure_msg']=="")) {
			$this->session->data['success_msg'] = "Transaction successfully saved";
		}		
		$this->response->redirect($this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'], true));
		//$this->getHeaderList();
	}
	
	public function sync2() {
		$this->load->language('warehouse/transactions');
		$this->document->setTitle($this->language->get('heading_warehouse_transaction'));

		if (isset($this->request->get['sync_storage'])) {
			$sync_storages = $this->request->get['sync_storage'];
		} else {
			$sync_storages = "";
		}
		//echo "<pre>";print_r($sync_storages);echo "</pre>";exit;
			
		$this->load->model('warehouse/transactions');
		
		$arrStorageIDs = array();
		$headerID = "";
		if($sync_storages!="") {
			$arrStorageIDs = explode("|", $sync_storages);
			foreach($arrStorageIDs as $key => $value) {
				$header_result[$value] = $this->model_warehouse_transactions->getTransaction($value);
				
				if($headerID=="") { $headerID = $value; }
				$data['tran_id'] = $value;
				/**"CustCode":"TEST",
				"Docno":"PO123456"**/
				
				// WMS Function Call - Start
				$header_ids = $value;
				$customer_ids = $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
				//$doc_nos = constant("PARTNER_UNIQUE_ID") . "@" . $header_result[$value]["transaction_no"]; // partnerID + delimiter + transaction number
				$doc_nos = $this->config->get('config_unique_brp_partner_id') . "@" . $header_result[$value]["transaction_no"]; // partnerID + delimiter + transaction number
				$apiParams = array(
				   "header_id"=>$header_ids, 
				   "customer_id"=>$customer_ids, 
				   "doc_no"=>$doc_nos, 
				);
				//echo "<pre>";print_r($apiParams);echo "</pre>";exit;
				$arrAPIResults = $this->ajaxAPI("download_receiving_progress", $apiParams);
				if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
					$this->session->data['failure_msg'] = $arrAPIResults["failure_msg"];
					break;
				} if(isset($arrAPIResults["failure_ignore_msg"]) && $arrAPIResults["failure_ignore_msg"]!="") {
					$this->session->data['failure_ignore_msg'] = $arrAPIResults["failure_ignore_msg"];
				}
				//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
				// WMS Function Call - End
				
			}
		}
		//echo"XX1";exit;
		if(!isset($this->session->data['failure_msg']) || (isset($this->session->data['failure_msg']) && $this->session->data['failure_msg']=="")) {
			$this->session->data['success_msg'] = "Transaction successfully saved";
		}		
		$this->response->redirect($this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'], true));
		//$this->getHeaderList();
	}

	public function index() {
		$this->load->language('warehouse/transactions');
		$this->load->model('warehouse/transactions');
		//echo $this->model_warehouse_transactions->testing();exit;
		$this->document->setTitle($this->language->get('heading_warehouse_transaction'));
		$this->getHeaderList();
	}
	
	protected function getHeaderList() {
		if (isset($this->request->get['filter_transaction_type'])) {
			$filter_transaction_type = $this->request->get['filter_transaction_type'];
		} else {
			$filter_transaction_type = null;
		}
		if (isset($this->request->get['filter_transaction_no'])) {
			$filter_transaction_no = $this->request->get['filter_transaction_no'];
		} else {
			$filter_transaction_no = null;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
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
		if (isset($this->request->get['filter_transaction_type'])) {
			$url .= '&filter_transaction_type=' . urlencode(html_entity_decode($this->request->get['filter_transaction_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_transaction_no'])) {
			$url .= '&filter_transaction_no=' . urlencode(html_entity_decode($this->request->get['filter_transaction_no'], ENT_QUOTES, 'UTF-8'));
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
		
		$data['heading_title'] = $this->language->get('heading_warehouse_transaction');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_warehouse_transaction'),
			'href' => $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'], true)
		);
		
		$data['add'] = $this->url->link('warehouse/transactions/headeradd', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('warehouse/transactions/headerdelete', 'token=' . $this->session->data['token'] . $url, true);
		$data['cancel'] = $this->url->link('warehouse/transactions/headercancel', 'token=' . $this->session->data['token'] . $url, true);

		$data['trans'] = array();

		$filter_data = array(
			'filter_transaction_type'	=> $filter_transaction_type,
			'filter_transaction_no'		=> $filter_transaction_no,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
		
		//$this->load->model('tool/image');

		$product_total = $this->model_warehouse_transactions->getTotalTransactions($filter_data);

		$results = $this->model_warehouse_transactions->getTransactions($filter_data);
		//$product_total = 0;
		//$results = array();

		foreach ($results as $result) {
			if($result['ecowarehouse_sync_status']=="Completed" && $result['warehouseeco_sync_status']=="Completed")
			{
				$status = $this->language->get('text_completed');
			}
			elseif($result['ecowarehouse_sync_status']=="Completed" && $result['warehouseeco_sync_status']=="Cancelled")
			{
				$status = $this->language->get('text_cancelled');
			}
			elseif($result['ecowarehouse_sync_status']=="Completed" && $result['warehouseeco_sync_status']=="Draft")
			{
				$status = $this->language->get('text_draft');
			}
			else{
				$status = $this->language->get('text_pending');
			}
			
			
			$data['trans'][] = array(
				'id'				=> $result['id'],
				'transaction_type'	=> $result['transaction_type'],
				'transaction_no'	=> $result['transaction_no'],
				'date_added'		=> $result['date_added'],
				'total_item_lines'	=> $result['total_item_lines'],
				'received_datetime'	=> $result['received_datetime'],
				'num_of_bin'		=> $result['num_of_bin'],
				'actual_num_of_bin'	=> $result['actual_num_of_bin'],
				'status'     		=> $status,				
				//'status'     		=> ($result['ecowarehouse_sync_status']=="Completed" && $result['warehouseeco_sync_status']=="Completed") ? $this->language->get('text_completed') : $this->language->get('text_pending'),
				'ecowarehouse_sync_status'	=> $result['ecowarehouse_sync_status'], // ? $this->language->get('text_completed') : $this->language->get('text_pending'),
				'warehouseeco_sync_status'	=> $result['warehouseeco_sync_status'], // ? $this->language->get('text_completed') : $this->language->get('text_pending'),
				'ecowarehouse_sync_timest'	=> $result['ecowarehouse_sync_timest'], 
				'warehouseeco_sync_timest'	=> $result['warehouseeco_sync_timest'], 
				'edit'      		=> $this->url->link('warehouse/transactions/headertran', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, true)
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_warehouse_transaction');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_transaction_type'] = $this->language->get('column_transaction_type');
		$data['column_transaction_no'] = $this->language->get('column_transaction_no');
		$data['column_total_item_lines'] = $this->language->get('column_total_item_lines');
		$data['column_received_datetime'] = $this->language->get('column_received_datetime');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_ecowarehouse_sync_status'] = $this->language->get('column_ecowarehouse_sync_status');
		$data['column_warehouseeco_sync_status'] = $this->language->get('column_warehouseeco_sync_status');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['entry_transaction_type'] = $this->language->get('entry_transaction_type');
		$data['entry_transaction_date'] = $this->language->get('entry_transaction_date');
		$data['entry_transaction_no'] = $this->language->get('entry_transaction_no');
		
		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->request->get['failure_msg']) && $this->request->get['failure_msg']=="1") {
			$data['error_warning'] = "Insufficient number of bins for storage.";
		} else if (isset($this->request->get['failure_ignore_msg']) && $this->request->get['failure_ignore_msg']=="1") {
			$data['error_warning'] = "Insufficient number of bins for storage.";
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
		if (isset($this->request->get['filter_transaction_type'])) {
			$url .= '&filter_transaction_type=' . urlencode(html_entity_decode($this->request->get['filter_transaction_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_transaction_no'])) {
			$url .= '&filter_transaction_no=' . urlencode(html_entity_decode($this->request->get['filter_transaction_no'], ENT_QUOTES, 'UTF-8'));
		}
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_transaction_type'] = $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'] . '&sort=transaction_type' . $url, true);
		$data['sort_transaction_no'] = $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'] . '&sort=transaction_no' . $url, true);
		$data['sort_date_added'] = $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, true);
		$data['sort_total_item_lines'] = $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'] . '&sort=total_item_lines' . $url, true);
		$data['sort_received_datetime'] = $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'] . '&sort=received_datetime' . $url, true);
		$data['sort_status'] = $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);
		$data['sort_ecowarehouse_sync_status'] = $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'] . '&sort=ecowarehouse_sync_status' . $url, true);
		$data['sort_warehouseeco_sync_status'] = $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'] . '&sort=warehouseeco_sync_status' . $url, true);

		$url = '';
		if (isset($this->request->get['filter_transaction_type'])) {
			$url .= '&filter_transaction_type=' . urlencode(html_entity_decode($this->request->get['filter_transaction_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_transaction_no'])) {
			$url .= '&filter_transaction_no=' . urlencode(html_entity_decode($this->request->get['filter_transaction_no'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));
		$data['filter_transaction_type'] = $filter_transaction_type;
		$data['filter_transaction_no'] = $filter_transaction_no;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('warehouse/transactions_header_list', $data));
	}
	
	public function headertran() {
		$this->load->language('warehouse/transactions');
		$this->load->model('warehouse/transactions');
		//echo $this->model_warehouse_transactions->testing();exit;
		$this->document->setTitle($this->language->get('heading_warehouse_transaction'));
		if (isset($this->request->get['id'])) {
			$headerID = $this->request->get['id'];
		} else {
			$headerID = 0;
		} if (isset($this->request->get['emailed']) && $this->request->get['emailed']=="1") {
			$isEmailed = $this->request->get['emailed'];
		} else if (isset($this->request->get['emailed']) && $this->request->get['emailed']=="2") {
			$isEmailed = $this->request->get['emailed'];
		} else {
			$isEmailed = 0;
		} if (isset($this->request->get['savenemail']) && $this->request->get['savenemail']=="1") {
			$isSaveNEmail = $this->request->get['savenemail'];
		} else {
			$isSaveNEmail = 0;
		}
		//echo $headerID;exit;
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//echo "<pre>";print_r($this->request->post);echo "</pre>";exit;
			$arrPost = $this->request->post;
			$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadAvailableLocation"; // Live
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
			if($this->model_warehouse_transactions->isJSON($resp)) {
				$resp = json_decode($resp, true);
			}
			$arrAvailableBinData = $resp;
			//echo "<pre>";print_r($arrAvailableBinData);echo "</pre>";exit;
			//echo "<pre>";print_r($arrAvailableBinData);echo "</pre>";exit;
			$intAvailableBin = (isset($arrAvailableBinData["TSHTable"][0]["NumOfLoc"])?$arrAvailableBinData["TSHTable"][0]["NumOfLoc"]:"0");
			//echo "<pre>";print_r($intAvailableBin);echo "</pre>";exit;
			// DownloadAvailableLocation API - End
			if($intAvailableBin==0) { 
				$this->session->data['failure_msg'] = "Insufficient number of bins for storage.";
			} else if($intAvailableBin<$arrPost["num_of_bin"]) { 
				$this->session->data['failure_ignore_msg'] = "Insufficient number of bins for storage.";
				$this->model_warehouse_transactions->editTransactionHeader($headerID, $this->request->post);
			} else {
				$this->session->data['success'] = $this->language->get('text_header_modified_success');
				$this->model_warehouse_transactions->editTransactionHeader($headerID, $this->request->post);
			}
		} 
		$strNewMsg = "";
		$strNew2Msg = "";
		if($isSaveNEmail) {
			$strNewMsg .= $this->language->get('text_header_added_success');
		} if($isEmailed=="1") {
			if($strNewMsg!="") { $strNewMsg .= "<br />"; }
			$strNewMsg .= "Email(s) has successfully sent.";
		} else if($isEmailed=="2") {
			if($strNewMsg!="") { $strNewMsg .= "<br />"; }
			$strNew2Msg .= "Email(s) failed to send.";
		}
		$this->session->data['success_msg'] = $strNewMsg;
		$this->session->data['failure_msg'] = $strNew2Msg;
		
		$this->getContentList($headerID);
	}
	
	protected function getContentList($headerID = 0) {
		$data['header_id'] = $headerID;
		if (isset($this->request->get['filter_product_name'])) {
			$filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$filter_product_name = null;
		}
		if (isset($this->request->get['filter_product_type'])) {
			$filter_product_type = $this->request->get['filter_product_type'];
		} else {
			$filter_product_type = null;
		}
		if (isset($this->request->get['filter_transaction_type'])) {
			$filter_transaction_type = $this->request->get['filter_transaction_type'];
		} else {
			$filter_transaction_type = null;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'transaction_no';
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
		if (isset($this->request->get['entry_product_name'])) {
			$url .= '&entry_product_name=' . urlencode(html_entity_decode($this->request->get['entry_product_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['entry_product_type'])) {
			$url .= '&entry_product_type=' . urlencode(html_entity_decode($this->request->get['entry_product_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['entry_transaction_type'])) {
			$url .= '&entry_transaction_type=' . urlencode(html_entity_decode($this->request->get['entry_transaction_type'], ENT_QUOTES, 'UTF-8'));
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
		
		$data['heading_title'] = $this->language->get('heading_warehouse_transaction');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_warehouse_transaction'),
			'href' => $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_warehouse_transaction_edit'),
			'href' => $this->url->link('warehouse/transactions/headertran', 'token=' . $this->session->data['token'] . '&id=' . $headerID, true)
		);
		
		$data['add'] = $this->url->link('warehouse/transactions/add', 'token=' . $this->session->data['token'] . $url, true);
		//$data['delete'] = $this->url->link('warehouse/transactions/delete', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('warehouse/transactions/headerdelete', 'token=' . $this->session->data['token'] . $url, true);
		$data['cancel'] = $this->url->link('warehouse/transactions/headercancel', 'token=' . $this->session->data['token'] . $url, true);
		$data['duplicate'] = $this->url->link('warehouse/transactions/headerduplicate', 'token=' . $this->session->data['token'] . '&id=' . $headerID, true);
		$data['sync'] = $this->url->link('warehouse/transactions/sync', 'token=' . $this->session->data['token'] . '&sync_storage=' . $headerID, true); // UploadReceivingList
		$data['sync2'] = $this->url->link('warehouse/transactions/sync2', 'token=' . $this->session->data['token'] . '&sync_storage=' . $headerID, true); // DownloadReceivingProgress

		$data['trans'] = array();

		$filter_data = array(
			'filter_product_name'		=> $filter_product_name,
			'filter_product_type'		=> $filter_product_type,
			'tran_id'            => $headerID,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => 5000 //$this->config->get('config_limit_admin')
		);

		//$this->load->model('tool/image');

		$product_total = $this->model_warehouse_transactions->getTotalDetailTransactions($filter_data);
		$results = $this->model_warehouse_transactions->getDetailTransactions($filter_data);
		//$product_total = 0;
		//$results = array();

		foreach ($results as $result) {
			$data['trans'][] = array(
				'id'				=> $result['id'],
				'transaction_id'	=> $result['transaction_id'],
				'row_no'			=> $result['row_no'],
				'product_id'		=> $result['product_id'],
				'product_name'		=> $result['product_name'],
				'product_model'		=> $result['product_model'],
				'product_type'		=> $result['product_type'],
				'matching_code'		=> $result['matching_code'],
				'quantity'			=> $result['quantity'],
				'uom'				=> $result['uom'],
				'remarks'			=> $result['remarks'],
				//'product_status'  => $result['product_status'] ? $this->language->get('text_completed') : $this->language->get('text_pending'),
				//'sync_status'     => $result['sync_status'],
				'edit'      		=> $this->url->link('warehouse/transactions/detailtran', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, true)
			);
		}
		$data['heading_title'] = $this->language->get('heading_warehouse_transaction');
		$data['text_header'] = $this->language->get('text_header');
		$data['text_detail_list'] = $this->language->get('text_detail_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_completed'] = $this->language->get('text_completed');
		$data['text_pending'] = $this->language->get('text_pending');
		$data['text_none'] = $this->language->get('text_none');

		$data['column_transaction_id'] = $this->language->get('column_transaction_id');
		$data['column_row_no'] = $this->language->get('column_row_no');
		$data['column_product_name'] = $this->language->get('column_product_name');
		$data['column_product_model'] = $this->language->get('column_product_model');
		$data['column_product_type'] = $this->language->get('column_product_type');
		$data['column_transaction_type'] = $this->language->get('column_transaction_type');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_uom'] = $this->language->get('column_uom');
		$data['column_num_of_bin'] = $this->language->get('column_num_of_bin');
		$data['column_actual_num_of_bin'] = $this->language->get('column_actual_num_of_bin');
		$data['column_remarks'] = $this->language->get('column_remarks');
		//$data['column_product_status'] = $this->language->get('column_product_status');
		//$data['column_sync_status'] = $this->language->get('column_sync_status');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['entry_product_name'] = $this->language->get('entry_product_name');
		$data['entry_product_model'] = $this->language->get('entry_product_model');
		$data['entry_product_type'] = $this->language->get('entry_product_type');
		$data['entry_transaction_type'] = $this->language->get('entry_transaction_type');
		$data['entry_transaction_date'] = $this->language->get('entry_transaction_date');
		$data['entry_transaction_no'] = $this->language->get('entry_transaction_no');
		$data['entry_remarks'] = $this->language->get('entry_remarks');
		$data['entry_received_datetime'] = $this->language->get('entry_received_datetime');
		$data['entry_num_of_bin'] = $this->language->get('entry_num_of_bin');
		$data['entry_actual_num_of_bin'] = $this->language->get('entry_actual_num_of_bin');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_ecowarehouse_sync_status'] = $this->language->get('entry_ecowarehouse_sync_status');
		$data['entry_warehouseeco_sync_status'] = $this->language->get('entry_warehouseeco_sync_status');
		
		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_tran_add'] = $this->language->get('button_add');
		$data['sync_transaction'] = $this->language->get('sync_transaction');
		$data['sync_upload_receiving_list'] = $this->language->get('sync_upload_receiving_list');
		$data['sync_download_receiving_progress'] = $this->language->get('sync_download_receiving_progress');
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['failure_msg'])) {
			$data['error_warning'] = $this->session->data['failure_msg'];
			unset($this->session->data['failure_msg']);
		} else {
			$data['error_warning'] = '';
		} if (isset($this->session->data['failure_ignore_msg'])) {
			if($data['error_warning']!="") {
				$data['error_warning'] = "<br />";
			}
			$data['error_warning'] .= $this->session->data['failure_ignore_msg'];
			unset($this->session->data['failure_ignore_msg']);
		}
		
		if (isset($this->error['transaction_type'])) {
			$data['error_transaction_type'] = $this->error['transaction_type'];
		} else {
			$data['error_transaction_type'] = '';
		}
		if (isset($this->error['transaction_no'])) {
			$data['error_transaction_no'] = $this->error['transaction_no'];
		} else {
			$data['error_transaction_no'] = '';
		}
		if (isset($this->error['remarks'])) {
			$data['error_remarks'] = $this->error['remarks'];
		} else {
			$data['error_remarks'] = '';
		}
		if (isset($this->error['received_datetime'])) {
			$data['error_received_datetime'] = $this->error['received_datetime'];
		} else {
			$data['error_received_datetime'] = '';
		}
		if (isset($this->error['num_of_bin'])) {
			$data['error_num_of_bin'] = $this->error['num_of_bin'];
		} else {
			$data['error_num_of_bin'] = '';
		}
		if (isset($this->error['actual_num_of_bin'])) {
			$data['error_actual_num_of_bin'] = $this->error['actual_num_of_bin'];
		} else {
			$data['error_actual_num_of_bin'] = '';
		}
		if (isset($this->error['status'])) {
			$data['error_status'] = $this->error['status'];
		} else {
			$data['error_status'] = '';
		}
		if (isset($this->error['ecowarehouse_sync_status'])) {
			$data['error_ecowarehouse_sync_status'] = $this->error['ecowarehouse_sync_status'];
		} else {
			$data['error_ecowarehouse_sync_status'] = '';
		}
		if (isset($this->error['warehouseeco_sync_status'])) {
			$data['error_warehouseeco_sync_status'] = $this->error['warehouseeco_sync_status'];
		} else {
			$data['error_warehouseeco_sync_status'] = '';
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
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_product_type'])) {
			$url .= '&filter_product_type=' . urlencode(html_entity_decode($this->request->get['filter_product_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_transaction_type'])) {
			$url .= '&filter_transaction_type=' . urlencode(html_entity_decode($this->request->get['filter_transaction_type'], ENT_QUOTES, 'UTF-8'));
		}
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$url .= "#listing";
		
		$data['sort_row_no'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=row_no' . $url, true);
		$data['sort_product_name'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=product_name' . $url, true);
		$data['sort_product_model'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=product_model' . $url, true);
		$data['sort_product_type'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=product_type' . $url, true);
		$data['sort_transaction_type'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=transaction_type' . $url, true);
		$data['sort_quantity'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=quantity' . $url, true);
		$data['sort_uom'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=uom' . $url, true);
		$data['sort_price'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=price' . $url, true);
		$data['sort_amount'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=amount' . $url, true);
		$data['sort_remarks'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=remarks' . $url, true);
		//$data['sort_product_status'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=product_status' . $url, true);
		//$data['sort_sync_status'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=sync_status' . $url, true);

		$url = '&id=' . $headerID;
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_product_type'])) {
			$url .= '&filter_product_type=' . urlencode(html_entity_decode($this->request->get['filter_product_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_transaction_type'])) {
			$url .= '&filter_transaction_type=' . urlencode(html_entity_decode($this->request->get['filter_transaction_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$data['action'] = $this->url->link('warehouse/transactions/headertran', 'token=' . $this->session->data['token'] . $url, true);
		$data['action2'] = $this->url->link('warehouse/transactions/detailaddedit', 'token=' . $this->session->data['token'] . $url, true);
		$data['print'] = $this->url->link('warehouse/transactions/printtran', 'printtype=print&token=' . $this->session->data['token'] . $url, true);
		$data['email'] = $this->url->link('warehouse/transactions/printtran', 'printtype=email&token=' . $this->session->data['token'] . $url, true);

		
		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tran_info = $this->model_warehouse_transactions->getTransaction($this->request->get['id']);
		}
		/*if (isset($this->request->post['transaction_type'])) {
			$data['transaction_type'] = $this->request->post['transaction_type'];
		} elseif (!empty($tran_info)) {
			$data['transaction_type'] = $tran_info['transaction_type'];
		} else {
			$data['transaction_type'] = '';
		}*/
		if (!empty($tran_info)) {
			$data['transaction_id'] = $tran_info['id'];
		} else {
			$data['transaction_id'] = '';
		}
		
		$data['transaction_type'] = 'Warehouse';
		if (isset($this->request->post['transaction_date'])) {
			$data['date_added'] = $this->request->post['transaction_date'];
		} elseif (!empty($tran_info)) {
			$data['date_added'] = date("Y-m-d", strtotime($tran_info['date_added']));
		} else {
			$data['date_added'] = '';
		}
		if (isset($this->request->post['transaction_no'])) {
			$data['transaction_no'] = $this->request->post['transaction_no'];
		} elseif (!empty($tran_info)) {
			$data['transaction_no'] = $tran_info['transaction_no'];
		} else {
			$data['transaction_no'] = '';
		}
		if (isset($this->request->post['remarks'])) {
			$data['remarks'] = $this->request->post['remarks'];
		} elseif (!empty($tran_info)) {
			$data['remarks'] = $tran_info['remarks'];
		} else {
			$data['remarks'] = '';
		}
		if (isset($this->request->post['received_datetime'])) {
			$data['received_datetime'] = $this->request->post['received_datetime'];
		} elseif (!empty($tran_info)) {
			$data['received_datetime'] = $tran_info['received_datetime'];
		} else {
			$data['received_datetime'] = '';
		}
		if (isset($this->request->post['num_of_bin'])) {
			$data['num_of_bin'] = (int)$this->request->post['num_of_bin'];
		} elseif (!empty($tran_info)) {
			$data['num_of_bin'] = $tran_info['num_of_bin'];
		} else {
			$data['num_of_bin'] = '';
		}
		if (isset($this->request->post['actual_num_of_bin'])) {
			$data['actual_num_of_bin'] = (int)$this->request->post['actual_num_of_bin'];
		} elseif (!empty($tran_info)) {
			$data['actual_num_of_bin'] = $tran_info['actual_num_of_bin'];
		} else {
			$data['actual_num_of_bin'] = '';
		}
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($tran_info)) {
			$data['status'] = $tran_info['status'];
		} else {
			$data['status'] = '';
		}
		if (isset($this->request->post['ecowarehouse_sync_status'])) {
			$data['ecowarehouse_sync_status'] = $this->request->post['ecowarehouse_sync_status'];
		} elseif (!empty($tran_info)) {
			$data['ecowarehouse_sync_status'] = $tran_info['ecowarehouse_sync_status'];
		} else {
			$data['ecowarehouse_sync_status'] = '-';
		}
		if (isset($this->request->post['ecowarehouse_sync_timest'])) {
			$data['ecowarehouse_sync_timest'] = $this->request->post['ecowarehouse_sync_timest'];
		} elseif (!empty($tran_info)) {
			$data['ecowarehouse_sync_timest'] = $tran_info['ecowarehouse_sync_timest'];
		} else {
			$data['ecowarehouse_sync_timest'] = '';
		}
		if (isset($this->request->post['warehouseeco_sync_status'])) {
			$data['warehouseeco_sync_status'] = $this->request->post['warehouseeco_sync_status'];
		} elseif (!empty($tran_info)) {
			$data['warehouseeco_sync_status'] = $tran_info['warehouseeco_sync_status'];
		} else {
			$data['warehouseeco_sync_status'] = '-';
		}
		if (isset($this->request->post['warehouseeco_sync_timest'])) {
			$data['warehouseeco_sync_timest'] = $this->request->post['warehouseeco_sync_timest'];
		} elseif (!empty($tran_info)) {
			$data['warehouseeco_sync_timest'] = $tran_info['warehouseeco_sync_timest'];
		} else {
			$data['warehouseeco_sync_timest'] = '';
		}

		if (isset($this->request->post['warehouseeco_sync_status'])) {
			$data['warehouseeco_sync_status'] = $this->request->post['warehouseeco_sync_status'];
			$test1 = "test1";
		} elseif (!empty($tran_info)) {
			$data['warehouseeco_sync_status'] = $tran_info['warehouseeco_sync_status'];
			$test1 = "test2";
		} else {
			$data['warehouseeco_sync_status'] = '-';
			$test1 = "test3";
		}
		
		$pagination_limit = 1000;//$this->config->get('config_limit_admin');
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $pagination_limit;
		$pagination->url = $this->url->link('warehouse/transactions/headertran', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $pagination_limit) + 1 : 0, ((($page - 1) * $pagination_limit) > ($product_total - $pagination_limit)) ? $product_total : ((($page - 1) * $pagination_limit) + $pagination_limit), $product_total, ceil($product_total / $pagination_limit));
		$data['filter_product_name'] = $filter_product_name;
		$data['filter_product_type'] = $filter_product_type;
		$data['filter_transaction_type'] = $filter_transaction_type;
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('warehouse/transactions_content_list', $data));
	}
	
	public function headeradd() {
		$this->load->language('warehouse/transactions');
		$this->load->model('warehouse/transactions');
		//echo $this->model_warehouse_transactions->testing();exit;
		$this->document->setTitle($this->language->get('heading_warehouse_transaction'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//echo "<pre>";print_r($this->request->post);echo "</pre>";exit;
			$arrPost = $this->request->post;
			$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadAvailableLocation"; // Live
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
			if($this->model_warehouse_transactions->isJSON($resp)) {
				$resp = json_decode($resp, true);
			}
			$arrAvailableBinData = $resp;
			//echo "<pre>";print_r($arrAvailableBinData);echo "</pre>";exit;
			//echo "<pre>";print_r($arrAvailableBinData);echo "</pre>";exit;
			$intAvailableBin = (isset($arrAvailableBinData["TSHTable"][0]["NumOfLoc"])?$arrAvailableBinData["TSHTable"][0]["NumOfLoc"]:"0");
			//echo "<pre>";print_r($intAvailableBin);echo "</pre>";exit;
			// DownloadAvailableLocation API - End
			if($intAvailableBin==0) { 
				$this->session->data['failure_msg'] = "Insufficient number of bins for storage.";
				$this->response->redirect($this->url->link('warehouse/transactions/headeradd', 'failure_msg=1&token=' . $this->session->data['token'], true));
			} else if($intAvailableBin<$arrPost["num_of_bin"]) { 
				$this->session->data['failure_ignore_msg'] = "Insufficient number of bins for storage.";
				$headerID = $this->model_warehouse_transactions->addTransactionHeader($this->request->post);
				$this->model_warehouse_transactions->updateRunningNo('Storage');
				$this->response->redirect($this->url->link('warehouse/transactions/headertran', 'failure_ignore_msg=1&id=' . $headerID . '&token=' . $this->session->data['token'], true));
			} else {
				//echo "<pre>";print_r($this->request->post);echo "</pre>";exit;
				$headerID = $this->model_warehouse_transactions->addTransactionHeader($this->request->post);
				$this->model_warehouse_transactions->updateRunningNo('Storage');
				$this->session->data['success'] = $this->language->get('text_header_added_success');
				$this->response->redirect($this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'], true));
			}
		}
		
		$this->getHeaderAddForm();
	}
	
	protected function getHeaderAddForm() {
		
		$data['heading_title'] = $this->language->get('details_title');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_warehouse_transaction'),
			'href' => $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_warehouse_transaction_add'),
			'href' => $this->url->link('warehouse/transactions/headeradd', 'token=' . $this->session->data['token'], true)
		);
		
		$data['heading_title'] = $this->language->get('heading_warehouse_transaction');
		$data['text_header'] = $this->language->get('text_header');
		$data['text_detail_list'] = $this->language->get('text_detail_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_completed'] = $this->language->get('text_completed');
		$data['text_pending'] = $this->language->get('text_pending');

		$data['column_transaction_id'] = $this->language->get('column_transaction_id');
		$data['column_row_no'] = $this->language->get('column_row_no');
		$data['column_product_name'] = $this->language->get('column_product_name');
		$data['column_product_model'] = $this->language->get('column_product_model');
		$data['column_product_type'] = $this->language->get('column_product_type');
		$data['column_transaction_type'] = $this->language->get('column_transaction_type');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_uom'] = $this->language->get('column_uom');
		$data['column_num_of_bin'] = $this->language->get('column_num_of_bin');
		$data['column_actual_num_of_bin'] = $this->language->get('column_actual_num_of_bin');
		$data['column_remarks'] = $this->language->get('column_remarks');
		//$data['column_product_status'] = $this->language->get('column_product_status');
		//$data['column_sync_status'] = $this->language->get('column_sync_status');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['entry_product_name'] = $this->language->get('entry_product_name');
		$data['entry_product_model'] = $this->language->get('entry_product_model');
		$data['entry_product_type'] = $this->language->get('entry_product_type');
		$data['entry_transaction_type'] = $this->language->get('entry_transaction_type');
		$data['entry_transaction_date'] = $this->language->get('entry_transaction_date');
		$data['entry_transaction_no'] = $this->language->get('entry_transaction_no');
		$data['entry_remarks'] = $this->language->get('entry_remarks');
		$data['entry_received_datetime'] = $this->language->get('entry_received_datetime');
		$data['entry_num_of_bin'] = $this->language->get('entry_num_of_bin');
		$data['entry_actual_num_of_bin'] = $this->language->get('entry_actual_num_of_bin');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_ecowarehouse_sync_status'] = $this->language->get('entry_ecowarehouse_sync_status');
		$data['entry_warehouseeco_sync_status'] = $this->language->get('entry_warehouseeco_sync_status');
		
		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_tran_add'] = $this->language->get('button_add');

		$data['token'] = $this->session->data['token'];
			
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['failure_msg'])) {
			$data['error_warning'] = $this->session->data['failure_msg'];
			unset($this->session->data['failure_msg']);
		} else {
			$data['error_warning'] = '';
		} if (isset($this->session->data['failure_ignore_msg'])) {
			if($data['error_warning']!="") {
				$data['error_warning'] = "<br />";
			}
			$data['error_warning'] .= $this->session->data['failure_ignore_msg'];
			unset($this->session->data['failure_ignore_msg']);
		}
	
		if (isset($this->error['transaction_type'])) {
			$data['error_transaction_type'] = $this->error['transaction_type'];
		} else {
			$data['error_transaction_type'] = '';
		}
		if (isset($this->error['transaction_no'])) {
			$data['error_transaction_no'] = $this->error['transaction_no'];
		} else {
			$data['error_transaction_no'] = '';
		}
		if (isset($this->error['remarks'])) {
			$data['error_remarks'] = $this->error['remarks'];
		} else {
			$data['error_remarks'] = '';
		}
		if (isset($this->error['received_datetime'])) {
			$data['error_received_datetime'] = $this->error['received_datetime'];
		} else {
			$data['error_received_datetime'] = '';
		}
		if (isset($this->error['num_of_bin'])) {
			$data['error_num_of_bin'] = $this->error['num_of_bin'];
		} else {
			$data['error_num_of_bin'] = '';
		}
		if (isset($this->error['actual_num_of_bin'])) {
			$data['error_actual_num_of_bin'] = $this->error['actual_num_of_bin'];
		} else {
			$data['error_actual_num_of_bin'] = '';
		}
		if (isset($this->error['status'])) {
			$data['error_status'] = $this->error['status'];
		} else {
			$data['error_status'] = '';
		}
		if (isset($this->error['ecowarehouse_sync_status'])) {
			$data['error_ecowarehouse_sync_status'] = $this->error['ecowarehouse_sync_status'];
		} else {
			$data['error_ecowarehouse_sync_status'] = '';
		}
		if (isset($this->error['warehouseeco_sync_status'])) {
			$data['error_warehouseeco_sync_status'] = $this->error['warehouseeco_sync_status'];
		} else {
			$data['error_warehouseeco_sync_status'] = '';
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
		
		$data['action'] = $this->url->link('warehouse/transactions/headeradd', 'token=' . $this->session->data['token'], true);

		$data['transaction_type'] = 'Warehouse';
		
		$this->load->model('warehouse/transactions');
		$newTRNo = $this->model_warehouse_transactions->generateRunningNo('Storage');
		//$this->model_warehouse_transactions->updateRunningNo('Storage');
		$data['date_added'] = '';
		$data['transaction_no'] = $newTRNo;
		$data['remarks'] = '';
		$data['received_datetime'] = '';
		$data['num_of_bin'] = '';
		$data['actual_num_of_bin'] = '';
		$data['status'] = '';
		$data['ecowarehouse_sync_status'] = '';
		$data['warehouseeco_sync_status'] = '';
		$data['ecowarehouse_sync_timest'] = '';
		$data['warehouseeco_sync_timest'] = '';
		
		// Retain the pre entered value - Start
		if (isset($this->request->post['transaction_no'])) {
			$data['transaction_no'] = $this->request->post['transaction_no'];
		}
		if (isset($this->request->post['remarks'])) {
			$data['remarks'] = $this->request->post['remarks'];
		}
		if (isset($this->request->post['received_datetime'])) {
			$data['received_datetime'] = $this->request->post['received_datetime'];
		}
		if (isset($this->request->post['num_of_bin'])) {
			$data['num_of_bin'] = (int)$this->request->post['num_of_bin'];
		}
		if (isset($this->request->post['actual_num_of_bin'])) {
			$data['actual_num_of_bin'] = (int)$this->request->post['actual_num_of_bin'];
		}
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		}
		if (isset($this->request->post['ecowarehouse_sync_status'])) {
			$data['ecowarehouse_sync_status'] = $this->request->post['ecowarehouse_sync_status'];
		}
		if (isset($this->request->post['warehouseeco_sync_status'])) {
			$data['warehouseeco_sync_status'] = $this->request->post['warehouseeco_sync_status'];
		}
		// Retain the pre entered value - End
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('warehouse/transactions_header_add', $data));
	}
	
	public function headerdelete() {
		$this->load->language('warehouse/transactions');
		$this->load->model('warehouse/transactions');
		//echo $this->model_warehouse_transactions->testing();exit;
		$this->document->setTitle($this->language->get('heading_warehouse_transaction'));
		if (isset($this->request->post['selected']) && $this->validateModify()) {
			//echo "<pre>";print_r($this->request->post['selected']);echo "</pre>";exit;
			foreach ($this->request->post['selected'] as $id) {
				$this->model_warehouse_transactions->deleteTransaction($id);
			}
			$this->session->data['success'] = $this->language->get('text_delete_success');
			$this->response->redirect($this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'], true));
		}
		$this->getHeaderList();
	}
	public function headercancel() {
		$this->load->language('warehouse/transactions');
		$this->load->model('warehouse/transactions');
		//echo $this->model_warehouse_transactions->testing();exit;
		$this->document->setTitle($this->language->get('heading_warehouse_transaction'));
		if (isset($this->request->post['selected']) && $this->validateModify()) {
			//echo "<pre>";print_r($this->request->post['selected']);echo "</pre>";exit;
			foreach ($this->request->post['selected'] as $id) {
				//$this->model_warehouse_transactions->deleteTransaction($id);
				$header_result[$id] = $this->model_warehouse_transactions->getTransaction($id);
				
				// WMS Function Call - Start
				$headerID = $id;
				$header_ids = $id;
				$customer_ids = $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
				//$doc_nos = constant("PARTNER_UNIQUE_ID") . "@" . $header_result[$id]["transaction_no"]; // partnerID + delimiter + transaction number
				$doc_nos = $this->config->get('config_unique_brp_partner_id') . "@" . $header_result[$id]["transaction_no"]; // partnerID + delimiter + transaction number
				$apiParams = array(
				   "header_id"=>$header_ids, 
				   "customer_id"=>$customer_ids, 
				   "doc_no"=>$doc_nos, 
				);
				$arrAPIResults = $this->ajaxAPI('cancel_receiving_list', $apiParams);
				if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
					$this->session->data['failure_msg'] = $arrAPIResults["failure_msg"];
					break;
				} if(isset($arrAPIResults["failure_ignore_msg"]) && $arrAPIResults["failure_ignore_msg"]!="") {
					$this->session->data['failure_ignore_msg'] = $arrAPIResults["failure_ignore_msg"];
				}				
			}
			if(!isset($this->session->data['failure_msg']) || (isset($this->session->data['failure_msg']) && $this->session->data['failure_msg']=="")) {
				$this->session->data['success_msg'] = "Transaction successfully cancelled.";
			}
			//$this->response->redirect($this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'], true));
			$this->response->redirect($this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'], true));
		}
		$this->getHeaderList();
	}
	
	public function headerduplicate() {
		$this->load->language('warehouse/transactions');
		$this->load->model('warehouse/transactions');
		//echo $this->model_warehouse_transactions->testing();exit;
		$this->document->setTitle($this->language->get('heading_warehouse_transaction'));
		if (isset($this->request->post['selected']) && $this->validateModify()) {
			$headerID = "";
			foreach ($this->request->post['selected'] as $id) {
				//$this->model_warehouse_transactions->deleteTransaction($id);
				$headerData = $this->model_warehouse_transactions->getTransaction($id);
				unset($headerData["id"]);
				unset($headerData["transaction_no"]);
				//echo "<pre>";print_r($headerData);echo "</pre>";exit;
				
				$headerID = $this->model_warehouse_transactions->addTransactionHeader($headerData);
				$this->model_warehouse_transactions->updateRunningNo('Storage');
				
				$detailData = $this->model_warehouse_transactions->getDetailTransactions(array("tran_id"=>$id));
				//echo "<pre>";print_r($detailData);echo "</pre>";exit;
				
				if(is_array($detailData) && count($detailData)>0) {
					$arrData['warehouse_tran'] = array();
					foreach($detailData as $keyDetailData => $valueDetailData) {
						$valueDetailData["transaction_id"] = $headerID;
						$arrData['warehouse_tran'][$keyDetailData] = $valueDetailData;
					}
					if(count($arrData['warehouse_tran'])>0) {
						$this->model_warehouse_transactions->addEditTransactionDetails($headerID, $arrData);
					}
				}
			}
			if(!isset($this->session->data['failure_msg']) || (isset($this->session->data['failure_msg']) && $this->session->data['failure_msg']=="")) {
				$this->session->data['success_msg'] = "Transaction successfully duplicated.";
			}
			//$this->response->redirect($this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'], true));
			$this->response->redirect($this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'], true));
		}
		$this->getHeaderList();
	}
	
	public function detailaddedit() {
		$this->load->language('warehouse/transactions');
		$this->load->model('warehouse/transactions');
		//echo $this->model_warehouse_transactions->testing();exit;
		$this->document->setTitle($this->language->get('heading_warehouse_transaction'));
		$this->document->setTitle($this->language->get('heading_warehouse_transaction'));
		if (isset($this->request->get['id'])) {
			$headerID = $this->request->get['id'];
		} else {
			$headerID = 0;
		}
		
		$isSave = false;
		if (($this->request->server['REQUEST_METHOD'] == 'POST')/* && $this->validateForm()*/) {
			//echo "<pre>";print_r($this->request->post);echo "</pre>";exit;
			$this->model_warehouse_transactions->addEditTransactionDetails($headerID, $this->request->post);
			//$this->session->data['success'] = $this->language->get('text_header_added_success');
			$isSave = true;
		}
		
		if($isSave && $headerID>0) {
			$this->response->redirect($this->url->link('warehouse/transactions/printtran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&printtype=email&savenemail=1', true));
		} else {
			$this->response->redirect($this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'], true));
		}
	}
	

	

	/*public function testingmailer1234() {
		echo ". . . .";exit;
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol'); // "smtp"; //
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname'); // "ssl://smtp.gmail.com"; //
		$mail->smtp_username = $this->config->get('config_mail_smtp_username'); // "noreply.webmail.polarisnet@gmail.com"; //
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'); // "12348765QQQQ"; //
		$mail->smtp_port = $this->config->get('config_mail_smtp_port'); // "465"; //
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		
		$arrEmails = array("michael.lim@polarisnet.com.my");
		
		$text = "Testing Mailing.";
		foreach($arrEmails as $keyEmail => $valueEmail) {
			$mail->setTo($valueEmail);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($text));
			$mail->setText($text);
			$r = $mail->send();
			if($r) {
				//echo "Okay";	
			} else {
				//echo "Not Okay";
			}
			echo "<pre>Emailing";print_r($r);echo "</pre>";
		}
	}*/
	
	public function printtran() {
		if (isset($this->request->get['id'])) {
			$headerID = $this->request->get['id'];
		} else {
			$headerID = 0;
		}
		if (isset($this->request->get['printtype'])) {
			$strPrintType = $this->request->get['printtype'];
		} else {
			$strPrintType = "print";
		}
		if (isset($this->request->get['savenemail'])) {
			$strSaveAndEmail = $this->request->get['savenemail'];
		} else {
			$strSaveAndEmail = 0;
		}
		
		
		$data['header_id'] = $headerID;
		$this->load->language('warehouse/transactions');
		$this->load->model('warehouse/transactions');
		
		$data['title'] = $this->language->get('heading_warehouse_transaction');
		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');
		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}
		
		if (isset($this->request->get['filter_product_name'])) {
			$filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$filter_product_name = null;
		}
		if (isset($this->request->get['filter_product_type'])) {
			$filter_product_type = $this->request->get['filter_product_type'];
		} else {
			$filter_product_type = null;
		}
		if (isset($this->request->get['filter_transaction_type'])) {
			$filter_transaction_type = $this->request->get['filter_transaction_type'];
		} else {
			$filter_transaction_type = null;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'transaction_no';
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
		if (isset($this->request->get['entry_product_name'])) {
			$url .= '&entry_product_name=' . urlencode(html_entity_decode($this->request->get['entry_product_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['entry_product_type'])) {
			$url .= '&entry_product_type=' . urlencode(html_entity_decode($this->request->get['entry_product_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['entry_transaction_type'])) {
			$url .= '&entry_transaction_type=' . urlencode(html_entity_decode($this->request->get['entry_transaction_type'], ENT_QUOTES, 'UTF-8'));
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
		
		$data['heading_title'] = $this->language->get('heading_warehouse_transaction');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_warehouse_transaction'),
			'href' => $this->url->link('warehouse/transactions', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_warehouse_transaction_edit'),
			'href' => $this->url->link('warehouse/transactions/headertran', 'token=' . $this->session->data['token'] . '&id=' . $headerID, true)
		);
		
		$data['add'] = $this->url->link('warehouse/transactions/add', 'token=' . $this->session->data['token'] . $url, true);
		//$data['delete'] = $this->url->link('warehouse/transactions/delete', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('warehouse/transactions/headerdelete', 'token=' . $this->session->data['token'] . $url, true);
		$data['cancel'] = $this->url->link('warehouse/transactions/headercancel', 'token=' . $this->session->data['token'] . $url, true);
		$data['duplicate'] = $this->url->link('warehouse/transactions/headerduplicate', 'token=' . $this->session->data['token'] . '&id=' . $headerID, true);
		$data['sync'] = $this->url->link('warehouse/transactions/sync', 'token=' . $this->session->data['token'] . '&sync_storage=' . $headerID, true); // UploadReceivingList
		$data['sync2'] = $this->url->link('warehouse/transactions/sync2', 'token=' . $this->session->data['token'] . '&sync_storage=' . $headerID, true); // DownloadReceivingProgress

		$data['trans'] = array();

		$filter_data = array(
			'filter_product_name'		=> $filter_product_name,
			'filter_product_type'		=> $filter_product_type,
			'tran_id'            => $headerID,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => 5000 //$this->config->get('config_limit_admin')
		);

		//$this->load->model('tool/image');

		$product_total = $this->model_warehouse_transactions->getTotalDetailTransactions($filter_data);
		$results = $this->model_warehouse_transactions->getDetailTransactions($filter_data);
		//$product_total = 0;
		//$results = array();

		foreach ($results as $result) {
			$data['trans'][] = array(
				'id'				=> $result['id'],
				'transaction_id'	=> $result['transaction_id'],
				'row_no'			=> $result['row_no'],
				'product_id'		=> $result['product_id'],
				'product_name'		=> $result['product_name'],
				'product_model'		=> $result['product_model'],
				'product_type'		=> $result['product_type'],
				'matching_code'		=> $result['matching_code'],
				'quantity'			=> $result['quantity'],
				'uom'				=> $result['uom'],
				'remarks'			=> $result['remarks'],
				//'product_status'  => $result['product_status'] ? $this->language->get('text_completed') : $this->language->get('text_pending'),
				//'sync_status'     => $result['sync_status'],
				'edit'      		=> $this->url->link('warehouse/transactions/detailtran', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, true)
			);
		}
		$data['heading_title'] = $this->language->get('heading_warehouse_transaction');
		$data['text_header'] = $this->language->get('text_header');
		$data['text_detail_list'] = $this->language->get('text_detail_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_completed'] = $this->language->get('text_completed');
		$data['text_pending'] = $this->language->get('text_pending');
		$data['text_none'] = $this->language->get('text_none');

		$data['column_transaction_id'] = $this->language->get('column_transaction_id');
		$data['column_row_no'] = $this->language->get('column_row_no');
		$data['column_product_name'] = $this->language->get('column_product_name');
		$data['column_product_model'] = $this->language->get('column_product_model');
		$data['column_product_type'] = $this->language->get('column_product_type');
		$data['column_transaction_type'] = $this->language->get('column_transaction_type');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_uom'] = $this->language->get('column_uom');
		$data['column_num_of_bin'] = $this->language->get('column_num_of_bin');
		$data['column_actual_num_of_bin'] = $this->language->get('column_actual_num_of_bin');
		$data['column_remarks'] = $this->language->get('column_remarks');
		//$data['column_product_status'] = $this->language->get('column_product_status');
		//$data['column_sync_status'] = $this->language->get('column_sync_status');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['entry_product_name'] = $this->language->get('entry_product_name');
		$data['entry_product_model'] = $this->language->get('entry_product_model');
		$data['entry_product_type'] = $this->language->get('entry_product_type');
		$data['entry_transaction_type'] = $this->language->get('entry_transaction_type');
		$data['entry_transaction_date'] = $this->language->get('entry_transaction_date');
		$data['entry_transaction_no'] = $this->language->get('entry_transaction_no');
		$data['entry_remarks'] = $this->language->get('entry_remarks');
		$data['entry_received_datetime'] = $this->language->get('entry_received_datetime');
		$data['entry_num_of_bin'] = $this->language->get('entry_num_of_bin');
		$data['entry_actual_num_of_bin'] = $this->language->get('entry_actual_num_of_bin');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_ecowarehouse_sync_status'] = $this->language->get('entry_ecowarehouse_sync_status');
		$data['entry_warehouseeco_sync_status'] = $this->language->get('entry_warehouseeco_sync_status');
		
		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_tran_add'] = $this->language->get('button_add');
		$data['sync_transaction'] = $this->language->get('sync_transaction');
		$data['sync_upload_receiving_list'] = $this->language->get('sync_upload_receiving_list');
		$data['sync_download_receiving_progress'] = $this->language->get('sync_download_receiving_progress');
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['failure_msg'])) {
			$data['error_warning'] = $this->session->data['failure_msg'];
			unset($this->session->data['failure_msg']);
		} else {
			$data['error_warning'] = '';
		} if (isset($this->session->data['failure_ignore_msg'])) {
			if($data['error_warning']!="") {
				$data['error_warning'] = "<br />";
			}
			$data['error_warning'] .= $this->session->data['failure_ignore_msg'];
			unset($this->session->data['failure_ignore_msg']);
		}
		
		if (isset($this->error['transaction_type'])) {
			$data['error_transaction_type'] = $this->error['transaction_type'];
		} else {
			$data['error_transaction_type'] = '';
		}
		if (isset($this->error['transaction_no'])) {
			$data['error_transaction_no'] = $this->error['transaction_no'];
		} else {
			$data['error_transaction_no'] = '';
		}
		if (isset($this->error['remarks'])) {
			$data['error_remarks'] = $this->error['remarks'];
		} else {
			$data['error_remarks'] = '';
		}
		if (isset($this->error['received_datetime'])) {
			$data['error_received_datetime'] = $this->error['received_datetime'];
		} else {
			$data['error_received_datetime'] = '';
		}
		if (isset($this->error['num_of_bin'])) {
			$data['error_num_of_bin'] = $this->error['num_of_bin'];
		} else {
			$data['error_num_of_bin'] = '';
		}
		if (isset($this->error['actual_num_of_bin'])) {
			$data['error_actual_num_of_bin'] = $this->error['actual_num_of_bin'];
		} else {
			$data['error_actual_num_of_bin'] = '';
		}
		if (isset($this->error['status'])) {
			$data['error_status'] = $this->error['status'];
		} else {
			$data['error_status'] = '';
		}
		if (isset($this->error['ecowarehouse_sync_status'])) {
			$data['error_ecowarehouse_sync_status'] = $this->error['ecowarehouse_sync_status'];
		} else {
			$data['error_ecowarehouse_sync_status'] = '';
		}
		if (isset($this->error['warehouseeco_sync_status'])) {
			$data['error_warehouseeco_sync_status'] = $this->error['warehouseeco_sync_status'];
		} else {
			$data['error_warehouseeco_sync_status'] = '';
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
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_product_type'])) {
			$url .= '&filter_product_type=' . urlencode(html_entity_decode($this->request->get['filter_product_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_transaction_type'])) {
			$url .= '&filter_transaction_type=' . urlencode(html_entity_decode($this->request->get['filter_transaction_type'], ENT_QUOTES, 'UTF-8'));
		}
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$url .= "#listing";
		
		/*$data['sort_row_no'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=row_no' . $url, true);
		$data['sort_product_name'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=product_name' . $url, true);
		$data['sort_product_model'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=product_model' . $url, true);
		$data['sort_product_type'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=product_type' . $url, true);
		$data['sort_transaction_type'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=transaction_type' . $url, true);
		$data['sort_quantity'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=quantity' . $url, true);
		$data['sort_uom'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=uom' . $url, true);
		$data['sort_price'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=price' . $url, true);
		$data['sort_amount'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=amount' . $url, true);
		$data['sort_remarks'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=remarks' . $url, true);
		*///$data['sort_product_status'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=product_status' . $url, true);
		//$data['sort_sync_status'] = $this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&token=' . $this->session->data['token'] . '&sort=sync_status' . $url, true);

		$url = '&id=' . $headerID;
		$url = '&printtype=' . $strPrintType;
		$url = '&savenemail=' . $strSaveAndEmail;
		/*if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_product_type'])) {
			$url .= '&filter_product_type=' . urlencode(html_entity_decode($this->request->get['filter_product_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_transaction_type'])) {
			$url .= '&filter_transaction_type=' . urlencode(html_entity_decode($this->request->get['filter_transaction_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}*/
		$data['action'] = $this->url->link('warehouse/transactions/headertran', 'token=' . $this->session->data['token'] . $url, true);
		$data['action2'] = $this->url->link('warehouse/transactions/detailaddedit', 'token=' . $this->session->data['token'] . $url, true);
		$data['print'] = $this->url->link('warehouse/transactions/printtran', 'printtype=print&token=' . $this->session->data['token'] . $url, true);
		$data['email'] = $this->url->link('warehouse/transactions/printtran', 'printtype=email&token=' . $this->session->data['token'] . $url, true);

		
		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tran_info = $this->model_warehouse_transactions->getTransaction($this->request->get['id']);
		} else {
			$tran_info = $this->model_warehouse_transactions->getTransaction($headerID);
		}
		/*if (isset($this->request->post['transaction_type'])) {
			$data['transaction_type'] = $this->request->post['transaction_type'];
		} elseif (!empty($tran_info)) {
			$data['transaction_type'] = $tran_info['transaction_type'];
		} else {
			$data['transaction_type'] = '';
		}*/
		if (!empty($tran_info)) {
			$data['transaction_id'] = $tran_info['id'];
		} else {
			$data['transaction_id'] = '';
		}
		
		$data['transaction_type'] = 'Warehouse';
		/*if (isset($this->request->post['date_added'])) {
			$data['date_added'] = $this->request->post['date_added'];
		} else*/if (!empty($tran_info)) {
			$data['date_added'] = $tran_info['date_added'];
		} else {
			$data['date_added'] = '';
		}
		if (isset($this->request->post['transaction_no'])) {
			$data['transaction_no'] = $this->request->post['transaction_no'];
		} elseif (!empty($tran_info)) {
			$data['transaction_no'] = $tran_info['transaction_no'];
		} else {
			$data['transaction_no'] = '';
		}
		if (isset($this->request->post['remarks'])) {
			$data['remarks'] = $this->request->post['remarks'];
		} elseif (!empty($tran_info)) {
			$data['remarks'] = $tran_info['remarks'];
		} else {
			$data['remarks'] = '';
		}
		if (isset($this->request->post['received_datetime'])) {
			$data['received_datetime'] = $this->request->post['received_datetime'];
		} elseif (!empty($tran_info)) {
			$data['received_datetime'] = $tran_info['received_datetime'];
		} else {
			$data['received_datetime'] = '';
		}
		if (isset($this->request->post['num_of_bin'])) {
			$data['num_of_bin'] = (int)$this->request->post['num_of_bin'];
		} elseif (!empty($tran_info)) {
			$data['num_of_bin'] = $tran_info['num_of_bin'];
		} else {
			$data['num_of_bin'] = '';
		}
		if (isset($this->request->post['actual_num_of_bin'])) {
			$data['actual_num_of_bin'] = (int)$this->request->post['actual_num_of_bin'];
		} elseif (!empty($tran_info)) {
			$data['actual_num_of_bin'] = $tran_info['actual_num_of_bin'];
		} else {
			$data['actual_num_of_bin'] = '';
		}
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($tran_info)) {
			$data['status'] = $tran_info['status'];
		} else {
			$data['status'] = '';
		}
		if (isset($this->request->post['ecowarehouse_sync_status'])) {
			$data['ecowarehouse_sync_status'] = $this->request->post['ecowarehouse_sync_status'];
		} elseif (!empty($tran_info)) {
			$data['ecowarehouse_sync_status'] = $tran_info['ecowarehouse_sync_status'];
		} else {
			$data['ecowarehouse_sync_status'] = '';
		}
		if (isset($this->request->post['warehouseeco_sync_status'])) {
			$data['warehouseeco_sync_status'] = $this->request->post['warehouseeco_sync_status'];
		} elseif (!empty($tran_info)) {
			$data['warehouseeco_sync_status'] = $tran_info['warehouseeco_sync_status'];
		} else {
			$data['warehouseeco_sync_status'] = '';
		}
		$data['barcode'] = $this->config->get('config_unique_brp_partner_id')."@".$data['transaction_no'];
		
		$pagination_limit = 1000;//$this->config->get('config_limit_admin');
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $pagination_limit;
		$pagination->url = $this->url->link('warehouse/transactions/headertran', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $pagination_limit) + 1 : 0, ((($page - 1) * $pagination_limit) > ($product_total - $pagination_limit)) ? $product_total : ((($page - 1) * $pagination_limit) + $pagination_limit), $product_total, ceil($product_total / $pagination_limit));
		$data['filter_product_name'] = $filter_product_name;
		$data['filter_product_type'] = $filter_product_type;
		$data['filter_transaction_type'] = $filter_transaction_type;
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['logo'] = HTTP_CATALOG . 'image/' . str_replace(" ","%20",$this->config->get('config_logo'));
		$data['store_name'] = $this->config->get('store_name');
		$data['store_url'] = $this->config->get('store_url');
		
		if($strPrintType=="email") {
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
			
			$isEmailed = 1;
			$text = "You have received a transaction.";
			foreach($arrEmails as $keyEmail => $valueEmail) {
				$mail->setTo($valueEmail);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($this->config->get('config_name')." - ".$this->language->get('heading_warehouse_transaction')." #".(isset($data['transaction_no'])?$data['transaction_no']:""), ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($this->load->view('warehouse/email_transaction', $data));
				$mail->setText($text);
				try {
					error_reporting(0);
					$mail->send();
				} catch (Exception $e) {
					$isEmailed = 2;
					$this->session->data['failure_msg'] = "Email(s) failed to send.";
				}
			}
			$this->response->redirect($this->url->link('warehouse/transactions/headertran', 'id=' . $headerID . '&emailed='.$isEmailed.'&savenemail='.$strSaveAndEmail.'&token=' . $this->session->data['token'], true));
			//$this->getContentList($headerID);
		} else {
			$this->response->setOutput($this->load->view('warehouse/print_transaction', $data));
		}
	}
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'warehouse/transactions')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		/*if (utf8_strlen($this->request->post['transaction_type']) == 0) {
			$this->error['transaction_type'] = $this->language->get('error_transaction_type');
		} */if (utf8_strlen($this->request->post['transaction_no']) == 0) {
			$this->error['transaction_no'] = $this->language->get('error_transaction_no');
		} if (utf8_strlen($this->request->post['received_datetime']) == 0) {
			$this->error['received_datetime'] = $this->language->get('error_received_datetime');
		} else {
			if(date('w', strtotime($this->request->post['received_datetime'])) == 0) {
				$this->error['received_datetime'] = "Proposed Receiving Date/Time must not be Sunday!";
			}
		} if (utf8_strlen($this->request->post['num_of_bin']) > 0 && $this->request->post['num_of_bin'] == 0) {
			$this->error['num_of_bin'] = "Please enter 1 or more Number of Bin(s)";
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'warehouse/transactions')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	protected function validateModify() {
		if (!$this->user->hasPermission('modify', 'warehouse/transactions')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	
}