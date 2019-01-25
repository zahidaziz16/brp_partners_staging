<?php
class ModelCatalogProduct extends Model {
	
	public function isJSON($string){
	   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	public function coreAPI($apitype = '', $params) {
		$json = array();
		/*$apitype = "";
		if (isset($this->request->post['apitype'])) {
			$apitype = $this->request->post['apitype'];
		} else if (isset($this->request->get['apitype'])) {
			$apitype = $this->request->get['apitype'];
		}*/
		
		$using_warehouse = (isset($params['using_warehouse'])?$params['using_warehouse']:"");
		$product_id = (isset($params['product_id'])?$params['product_id']:"");
		$data_source = (isset($params['data_source'])?$params['data_source']:"");
		$model = (isset($params['model'])?$params['model']:"");
		$matching_code = (isset($params['matching_code'])?$params['matching_code']:"");
		if($apitype=="stock_levels") {
			//$data_source=""; // debug
			if($data_source!="" && $data_source!="0") {
				//Query ERP Cloud Server - Start
				$path_url = "http://app1.gohofficesupplies.com/brp_bal.php?prod=".$matching_code;
				$prod = $matching_code;
				
				// ERP API
				//http://app1.gohofficesupplies.com/brp_bal.php?prod=R04-09|HPB5L24A|HPB3Q11A
				//http://app1.gohofficesupplies.com/brp_bal.php?prod=EPS LW400
				//[{"prod_code":"R04-09 ","Bal":"24.0000"},{"prod_code":"HPB3Q11A ","Bal":"2.0000"},{"prod_code":"HPB5L24A ","Bal":".0000"}]
				//http://localhost:8080/atoz_opencart/index.php?route=product/product/ajaxAPI&using_warehouse=1&product_id=2703&data_source=2707&matching_code=B%20LT-5300|HPB3Q11A
				//{"using_warehouse":"1","product_id":"2703","data_source":"2707","matching_code":"B LT-5300|HPB3Q11A",
					//"data":[{"prod_code":"B LT-5300","Bal":".0000"},{"prod_code":"HPB3Q11A","Bal":"2.0000"}],"data2":[{"prod_code":"B LT-5300","Bal":".0000"},{"prod_code":"HPB3Q11A","Bal":"2.0000"}]}
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curl, CURLOPT_TIMEOUT, 300);
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => str_replace(array("&amp;", " "),array("%26", "%20"),$path_url),
					CURLOPT_POST => 0,
					CURLOPT_POSTFIELDS => array(
						'prod' => $prod
					)
				));
				$resp = curl_exec($curl);
				$resp = trim(str_replace("Stock Balance","",strip_tags($resp)));
				curl_close($curl);
				$arrData = json_decode($resp, true);
				function cleanData(&$item) { $item = trim($item); }
				array_walk_recursive($arrData, 'cleanData');
				//echo "<pre>";print_r($arrData);echo "</pre>";exit;
				//Array([0] => Array (
				//	[prod_code] => R04-09
				//	[Bal] => 24.0000
				//))
				if($arrData!="") {
                                    if($arrData[0]["Bal"] < 0) {
                                        $arrData[0]["Bal"] = 0;
                                    }
					$arrData[0]["Bal"] = "" . $arrData[0]["Bal"] . "";
					$json['data'] = $arrData;
				} else {
					$arrData[0]["Bal"] = "Cannot retrieve stock value. Please try again.";
					$json['data'] = $arrData;
				}
				//Query ERP Cloud Server - End
				//Query WMS Cloud Server - Start
				if($using_warehouse) {
					//DownloadStockBalance
					//http://192.168.1.30:8090/api/WMS/DownloadStockBalance?js={"TSHTable":[{"CustCode":"GOS","ItemCode":"G01-40"}]}
					$data_sources = explode("|",$data_source);
					$models = explode("|",$model);
					$matching_codes = explode("|",$matching_code);
					$dataJS = array();
					foreach($matching_codes as $key => $value) {
						$dataJS["TSHTable"][$key]["CustCode"] = $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
						if($data_sources[$key]!="" && $data_sources[$key]!="0") {
							$dataJS["TSHTable"][$key]["ItemCode"] = $value;
						} else {
							$dataJS["TSHTable"][$key]["ItemCode"] = $models[$key];
						}
					}

					$dataJS = $this->dataSanitisation($dataJS);

					$strJson = json_encode($dataJS);
					//$path_url = "http://localhost:8080/atoz_opencart/DownloadStockBalance.txt?js=".$strJson.""; // Local
					$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadStockBalance?js=".$strJson.""; // Live
					//echo $path_url;exit;
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
					curl_setopt($curl, CURLOPT_TIMEOUT, 300);
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => $path_url,
						CURLOPT_POST => 0
					));
					$resp = curl_exec($curl);
					$resp = trim(str_replace("","",strip_tags($resp)));
					//echo "<pre>";print_r($resp);echo "</pre>";exit;
					curl_close($curl);
					
					$resp = json_decode($resp, true);
					if($this->isJSON($resp)) {
						$resp = json_decode($resp, true);
					}
					$arrData2 = $resp;
					//echo "<pre>";print_r($arrData2);echo "</pre>";exit;
					
					$arrData2 = (isset($arrData2["TSHTable"])?$arrData2["TSHTable"]:array());
					//echo "<pre>";print_r($arrData);echo "</pre>";exit;
					//echo "<pre>";print_r($arrData2);echo "</pre>";exit;
					foreach($arrData as $innerKey => $innerValue) {
						// Get model by matching code, $arrData[$innerKey]["prod_code"] is matching code
						$theModelData = $arrData[$innerKey]["prod_code"];
						//if(isset($arrData2[$innerKey]["ItemCode"]) && str_replace("-","",$arrData[$innerKey]["prod_code"])==str_replace("-","",$arrData2[$innerKey]["ItemCode"])) {
						if(isset($arrData2[$innerKey]["ItemCode"]) && $theModelData==$arrData2[$innerKey]["ItemCode"]) {
							$arrData[$innerKey]["Bal"] = "" . ($arrData[$innerKey]["Bal"] + $arrData2[$innerKey]["CurrentStock"]) . "";
						}
					}
					//echo "<pre>";print_r($arrData);echo "</pre>";exit;
					$json['data'] = $arrData;
				}
                                
                                $this->load->model('catalog/product');
				$product_info = $this->model_catalog_product->getProduct($product_id);
				$arrData3 = $product_info;
				//echo "<pre>";print_r($arrData["quantity"]);echo "</pre>";exit;
				//$arrData["quantity"] = 10; // debug
				$arrData3 = array(array("prod_code"=>$model, "Bal"=>$arrData3["quantity"]));
				//Query Opencart - End
				//echo "<pre>";print_r($arrData3);echo "</pre>";exit;
				
				if($arrData3!="") {
                                    $arrData[$innerKey]["Bal"] = "" . ($arrData[$innerKey]["Bal"] + $arrData3[0]["Bal"]) . "";
				}
                                
                                $json['data'] = $arrData;
                                
				//Query WMS Cloud Server - End
			} else if($data_source=="") {
				
				//Query Opencart - Start
				$this->load->model('catalog/product');
				$product_info = $this->model_catalog_product->getProduct($product_id);
				$arrData = $product_info;
				//echo "<pre>";print_r($arrData["quantity"]);echo "</pre>";exit;
				//$arrData["quantity"] = 10; // debug
				$arrData = array(array("prod_code"=>$model, "Bal"=>$arrData["quantity"]));
				//Query Opencart - End
				//echo "<pre>";print_r($arrData);echo "</pre>";exit;
				
				if($arrData!="") {
					$arrData[0]["Bal"] = "" . $arrData[0]["Bal"] . "";
					$json['data'] = $arrData;
				} else {
					$arrData[0]["Bal"] = "Cannot retrieve stock value. Please try again.";
					$json['data'] = $arrData;
				}
				
				//Query WMS Cloud Server - Start
				if($using_warehouse) {
					//DownloadStockBalance
					//http://192.168.1.30:8090/api/WMS/DownloadStockBalance?js={"TSHTable":[{"CustCode":"GOS","ItemCode":"G01-40"}]}
					$data_sources = explode("|",$data_source);
					$models = explode("|",$model);
					$matching_codes = explode("|",$matching_code);
					$dataJS = array();
					foreach($matching_codes as $key => $value) {
						$dataJS["TSHTable"][$key]["CustCode"] = $this->config->get('config_unique_brp_partner_id'); //constant("PARTNER_UNIQUE_ID");
						if(false&&$data_sources[$key]!="" && $data_sources[$key]!="0") {
							$dataJS["TSHTable"][$key]["ItemCode"] = $value;
						} else {
							$dataJS["TSHTable"][$key]["ItemCode"] = $models[$key];
						}
					}
					$strJson = json_encode($dataJS);
					//$path_url = "http://localhost:8080/atoz_opencart/DownloadStockBalance.txt?js=".$strJson.""; // Local
					$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadStockBalance?js=".$strJson.""; // Live
					//echo $path_url;exit;
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
					curl_setopt($curl, CURLOPT_TIMEOUT, 300);
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => str_replace(array("&amp;", " "),array("%26", "%20"),$path_url),
						CURLOPT_POST => 0
					));
					$resp = curl_exec($curl);
					$resp = trim(str_replace("","",strip_tags($resp)));
					//echo "<pre>";print_r($resp);echo "</pre>";exit;
					curl_close($curl);
					
					$resp = json_decode($resp, true);
					if($this->isJSON($resp)) {
						$resp = json_decode($resp, true);
					}
					$arrData2 = $resp;
					//echo "<pre>";print_r($arrData2);echo "</pre>";exit;
					
					$arrData2 = (isset($arrData2["TSHTable"])?$arrData2["TSHTable"]:array());
					//echo "<pre>";print_r($arrData);echo "</pre>";exit;
					//echo "<pre>";print_r($arrData2);echo "</pre>";exit;
					foreach($arrData as $innerKey => $innerValue) {
						if(isset($arrData2[$innerKey]["ItemCode"]) && $arrData[$innerKey]["prod_code"]==$arrData2[$innerKey]["ItemCode"]) {
							$arrData[$innerKey]["Bal"] = "" . ($arrData[$innerKey]["Bal"] + $arrData2[$innerKey]["CurrentStock"]) . "";
						}
					}
					//echo "<pre>";print_r($arrData);echo "</pre>";exit;
					$json['data'] = $arrData;
				}
				//Query WMS Cloud Server - End
			}
		} else if($apitype=="delivery_status") {
			/*$customer_id = "";
			$order_id = "";
			if (isset($this->request->post['customer_id'])) {
				$customer_id = $this->request->post['customer_id'];
			} else if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} if (isset($this->request->post['order_id'])) {
				$order_id = $this->request->post['order_id'];
			} else if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			}*/
			$customer_id = (isset($params['customer_id'])?$params['customer_id']:"");
			$order_id = (isset($params['order_id'])?$params['order_id']:"");
			
			$customer_ids = ""; $order_ids = "";
			$customer_ids = explode("|",$customer_id);
			$order_ids = explode("|",$order_id);
			$dataJS = array();
			foreach($customer_ids as $key => $value) {
				$dataJS["TSHTable"][$key]["CustCode"] = $customer_ids[$key];
				$dataJS["TSHTable"][$key]["OrderID"] = $order_ids[$key];
			}
			$strJson = json_encode($dataJS);
			
			//Query WMS Cloud Server - Start
			//DownloadDeliveryProgress
			//$path_url = "http://app1.gohofficesupplies.com/brp_bal.php?prod=".$matching_code;
			//$path_url = 'http://localhost:8080/atoz_opencart/delivery_progress1.txt?js={"TSHTable":[{"CustCode":"'.$customer_id.'","OrderID":"'.$order_id.'"}]}'; // Local
			$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadDeliveryProgress?js=".$strJson.""; // Live
			//$path_url = constant("GOHOFFICE_WMS_API_URL").'api/WMS/DownloadDeliveryProgress?js={"TSHTable":[{"CustCode":"BRPBTS001","OrderID":"BRPBTS001-163"}]}'; // Live
			//echo $path_url;exit;
			//http://192.168.1.30:8090/api/WMS/DownloadDeliveryProgress?js={"TSHTable":[{"CustCode":"GOS","OrderID":"D0001"}]}
			//http://192.168.1.30:8090/api/WMS/DownloadDeliveryProgress?js={"TSHTable":[{"CustCode":"GOS","OrderID":"D0002"}]}
			//http://192.168.1.30:8090/api/WMS/DownloadDeliveryProgress?js={"TSHTable":[{"CustCode":"GOS","OrderID":"D0001"},{"CustCode":"GOS","OrderID":"D0002"}]}
			
			// WMS API
			//http://192.168.1.30:8090/api/WMS/DownloadDeliveryProgress?js={"TSHTable":[{"CustCode":"GOS","OrderID":"D0001"}]}
			//[{"prod_code":"R04-09 ","Bal":"24.0000"},{"prod_code":"HPB3Q11A ","Bal":"2.0000"},{"prod_code":"HPB5L24A ","Bal":".0000"}]
			//http://localhost:8080/atoz_opencart/index.php?route=product/product/ajaxAPI&using_warehouse=1&product_id=2703&data_source=2707&matching_code=B%20LT-5300|HPB3Q11A
			//{"using_warehouse":"1","product_id":"2703","data_source":"2707","matching_code":"B LT-5300|HPB3Q11A",
				//"data":[{"prod_code":"B LT-5300","Bal":".0000"},{"prod_code":"HPB3Q11A","Bal":"2.0000"}],"data2":[{"prod_code":"B LT-5300","Bal":".0000"},{"prod_code":"HPB3Q11A","Bal":"2.0000"}]}
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
			if(!is_array($resp)) {
				$resp = json_decode($resp, true);
			}
			$arrData = $resp;
			//echo "<pre>";print_r($arrData);echo "</pre>";exit;
			
			//echo "<pre>XX";print_r($arrData);echo "</pre>";exit;
			//Array(
			//	[TSHTable] => Array(
			//		[0] => Array(
			//			[OrderID] => D0001
			//			[OrderStatus] => Draft
			//		)
			//		[1] => Array(
			//			[OrderID] => D0002
			//			[OrderStatus] => Draft
			//		)
			//	)
			//)
			$arrData = $arrData["TSHTable"];
			$json['data'] = $arrData;
			//$json['data'] = "Shipment";
			//Query WMS Cloud Server - End=
		}
		return $json;
		
		//$this->response->addHeader('Content-Type: application/json');
		//$this->response->setOutput(json_encode($json));
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
        
        public function coreAPIAdmin($apitype = '', $params) {
		$array = array();
		
		if($apitype=="stock_levels") {
			
			$strMatchingCodes = (isset($params['matching_code'])?$params['matching_code']:"");
			$strJson = (isset($params['json_data'])?$params['json_data']:"");
			$arrProductIDs = (isset($params['product_ids'])?$params['product_ids']:"");
			//TGS15SEOUL-KHA|TGS15SEOUL-ORG|F03-09|ZTHUMB DRIVE|B01-32|B01-31|F15-28|E07-06|E04-13|G03-04|B01-25|G02-06|G02-15|G08-08|TIMI NC2000|F13-118|XEXM255Z|XEXCP215W|F03-10
			//9138|9139|9868|9140|9141|9142|9143|9144|9145|9146|9147|9148|9149|9150|9151|9152|9153|9154|9155|9156
			//echo $strMatchingCodes."<br />";
			//echo $strProductIDs."<br />";exit;
			//echo "<pre>";print_r($arrProductIDs);echo "</pre>";exit;
			//echo "<pre>";print_r($strMatchingCodes);echo "</pre>";exit;
			
			//Query ERP Cloud Server - Start
			$path_url = "http://app1.gohofficesupplies.com/brp_bal.php?prod=".urlencode($strMatchingCodes);
			//echo $path_url;echo "<br />";//exit;
	
			// ERP API
			//http://app1.gohofficesupplies.com/brp_bal.php?prod=R04-09|HPB5L24A|HPB3Q11A
			//[{"prod_code":"R04-09 ","Bal":"24.0000"},{"prod_code":"HPB3Q11A ","Bal":"2.0000"},{"prod_code":"HPB5L24A ","Bal":".0000"}]
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($curl, CURLOPT_TIMEOUT, 300);
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => str_replace(array("&amp;", " "),array("%26", "%20"),$path_url),
				CURLOPT_POST => 0,
				CURLOPT_POSTFIELDS => array(
					'prod' => $strMatchingCodes
				)
			));
			$resp = curl_exec($curl);
			$resp = trim(str_replace("Stock Balance","",strip_tags($resp)));
			curl_close($curl);
			$arrData = json_decode($resp, true);
			function cleanData(&$item) { $item = trim($item); }
			if(count($arrData)>0) {
				array_walk_recursive($arrData, 'cleanData');
			}
			//echo "<pre>";print_r($arrData);echo "</pre>";exit;
			//Array([0] => Array (
			//	[prod_code] => R04-09
			//	[Bal] => 24.0000
			//))
			$arrTempData = array();
			if(count($arrData)>0) {
				foreach($arrData as $key => $value) {
					$arrTempData[$value["prod_code"]] = $value["Bal"];
				}
			} else {
				$arrMatchingCodes = explode("|",$strMatchingCodes);
				foreach($arrMatchingCodes as $key => $value) {
					$arrTempData[$value] = "0"; //"Cannot retrieve stock value. Please try again.";
				}
			}
			$array['erp_data'] = $arrTempData;
			//Query ERP Cloud Server - End
			
			//Query WMS Cloud Server - Start
			//DownloadStockBalance
			$array['wms_data'] = array();
			//$path_url = "http://localhost:8080/atoz_opencart/download_stock_balance_multiple.txt?js=".$strJson."";
			$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadStockBalance?js=".$strJson.""; // Live
			//echo $path_url;exit;
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
			$arrData = ((isset($arrData["TSHTable"])&&count($arrData["TSHTable"])>0)?$arrData["TSHTable"]:array());
			//echo "<pre>";print_r($arrData);echo "</pre>";exit;
			$arrTempData = array();
			if(count($arrData)>0) {
				foreach($arrData as $key => $value) {
					$arrTempData[$value["ItemCode"]] = $value["CurrentStock"];
				}
			} else {
				$arrMatchingCodes = explode("|",$strMatchingCodes);
				foreach($arrMatchingCodes as $key => $value) {
					$arrTempData[$value] = "0"; //"Cannot retrieve stock value. Please try again.";
				}
			}
			$array['wms_data'] = $arrTempData;
			//echo "<pre>";print_r($array['wms_data']);echo "</pre>";exit;
			//Query WMS Cloud Server - End
			
				
			//Query Opencart - Start
			$this->load->model('catalog/product');
			$arrData = array("filter_products"=>$arrProductIDs);
			$product_info = $this->model_catalog_product->getProducts($arrData);
			$arrData = $product_info;
			$arrTempData = array();
			if(count($arrData)>0) {
				foreach($arrData as $key => $value) {
					$arrTempData[$value["product_id"]] = $value["quantity"];
				}
			}
			$array['oc_data'] = $arrTempData;
			//echo "<pre>";print_r($array);echo "</pre>";exit;
			//Query Opencart - End
		}
		
		return $array;
	}
	
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	

	public function getModelByMatchingCode($matching_code) {
		$query = $this->db->query("SELECT model FROM " . DB_PREFIX . "product WHERE matching_code = '" . $matching_code . "' LIMIT 1");

		if ($query->num_rows) {
			return $query->row['model'];
		} else {
			return $matching_code;
		}
	}
	
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, p.data_source AS product_data_source, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
                
		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'data_source'      => $query->row['product_data_source'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'matching_code'    => $query->row['matching_code'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getProducts($data = array()) {
		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
                
                $this->load->model('account/customer');
                
                $isPetronasUser = $this->model_account_customer->isPetronasUser();
                
                if(!$isPetronasUser){
                    $sql .= " LEFT JOIN (SELECT group_concat(oc_category.petronas_only separator ',') as `petronas_only`, oc_product_to_category.product_id FROM oc_product_to_category LEFT JOIN oc_category ON oc_category.category_id = oc_product_to_category.category_id GROUP BY oc_product_to_category.product_id) AS petronas ON p.product_id = petronas.product_id";
                }
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
                if(!$isPetronasUser) {
                    $sql .= " AND petronas.petronas_only NOT LIKE '%1%' ";
                }
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = $this->cache->get('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
	
		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);
	
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			$this->cache->set('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}
		
		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
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

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductRelated($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
                
                $this->load->model('account/customer');
                
                $isPetronasUser = $this->model_account_customer->isPetronasUser();
                
                if(!$isPetronasUser){
                    $sql .= " LEFT JOIN (SELECT group_concat(oc_category.petronas_only separator ',') as `petronas_only`, oc_product_to_category.product_id FROM oc_product_to_category LEFT JOIN oc_category ON oc_category.category_id = oc_product_to_category.category_id GROUP BY oc_product_to_category.product_id) AS petronas ON p.product_id = petronas.product_id";
                }
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
                if(!$isPetronasUser){
                    $sql .= " AND petronas.petronas_only NOT LIKE '%1%' ";
                }
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfile($product_id, $recurring_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r JOIN " . DB_PREFIX . "product_recurring pr ON (pr.recurring_id = r.recurring_id AND pr.product_id = '" . (int)$product_id . "') WHERE pr.recurring_id = '" . (int)$recurring_id . "' AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

		return $query->row;
	}

	public function getProfiles($product_id) {
		$query = $this->db->query("SELECT rd.* FROM " . DB_PREFIX . "product_recurring pr JOIN " . DB_PREFIX . "recurring_description rd ON (rd.language_id = " . (int)$this->config->get('config_language_id') . " AND rd.recurring_id = pr.recurring_id) JOIN " . DB_PREFIX . "recurring r ON r.recurring_id = rd.recurring_id WHERE pr.product_id = " . (int)$product_id . " AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
        
        public function hasStock($products) {
		foreach ($products as $product) {
			if (!$product['stock']) {
				return false;
			}
		}

		return true;
	}
}
