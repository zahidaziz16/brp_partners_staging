<?php class ControllerToolDataSync extends Controller { 
	private $error = array();
	private $ssl = 'SSL';

	public function __construct( $registry ) {
		parent::__construct( $registry );
		$this->ssl = (defined('VERSION') && version_compare(VERSION,'2.2.0.0','>=')) ? true : 'SSL';
		date_default_timezone_set('Asia/Kuala_Lumpur');
	}

        //public function ajaxAPI($using_warehouse, $product_id, $data_source, $matching_code) {
	public function ajaxAPI($using_warehouse, $results) {
		$array = array();
		$arrProductIDs = array();
		$arrProductIDs[] = "-1";
		//echo "<pre>";print_r($results);echo "</pre>";exit;
		$strMatchingCodes = "";
		if(is_array($results) && count($results)>0) {
			foreach ($results as $result) {
				if(isset($result["matching_code"])&&$result["matching_code"]!="") {
					if($strMatchingCodes!="") {
						$strMatchingCodes .= "|";
					}
					$strMatchingCodes .= $result["matching_code"];
				}
				if(isset($result["product_id"])) {
					$arrProductIDs[] = $result["product_id"];
				}
			}
		}
		//TGS15SEOUL-KHA|TGS15SEOUL-ORG|F03-09|ZTHUMB DRIVE|B01-32|B01-31|F15-28|E07-06|E04-13|G03-04|B01-25|G02-06|G02-15|G08-08|TIMI NC2000|F13-118|XEXM255Z|XEXCP215W|F03-10
		//9138|9139|9868|9140|9141|9142|9143|9144|9145|9146|9147|9148|9149|9150|9151|9152|9153|9154|9155|9156
		//echo $strMatchingCodes."<br />";
		//echo $strProductIDs."<br />";exit;
		//echo "<pre>";print_r($arrProductIDs);echo "</pre>";exit;
		
		//Query ERP Cloud Server - Start
		$path_url = "http://app1.gohofficesupplies.com/brp_bal.php?prod=".urlencode($strMatchingCodes);
		//echo $path_url;exit;

		// ERP API
		//http://app1.gohofficesupplies.com/brp_bal.php?prod=R04-09|HPB5L24A|HPB3Q11A
		//[{"prod_code":"R04-09 ","Bal":"24.0000"},{"prod_code":"HPB3Q11A ","Bal":"2.0000"},{"prod_code":"HPB5L24A ","Bal":".0000"}]
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $path_url,
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
		}
		$array['erp_data'] = $arrTempData;
		//Query ERP Cloud Server - End
		
		//Query WMS Cloud Server - Start
		$array['wms_data'] = array();
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
		
		return $array;
	}

	public function index() {
		/*define('NDB_DRIVER', 'mysqli');
		define('NDB_HOSTNAME', 'localhost');
		define('NDB_USERNAME', 'dataserver');
		define('NDB_PASSWORD', 'dataserver');
		define('NDB_DATABASE', 'opencart_dataserver_db');
		define('NDB_PORT', '3306');
		define('NDB_PREFIX', 'oc_');
		$newsddb = new DB(NDB_DRIVER, NDB_HOSTNAME, NDB_USERNAME, NDB_PASSWORD, NDB_DATABASE);
		$this->registry->set('dsdb', $newsddb);
		//$aTemp = $this->dsdb->query("SELECT * FROM `" . NDB_PREFIX . "product` WHERE 1=1")->rows;
		//echo "<pre>";print_r($aTemp);echo "</pre>";exit;*/
				
		$this->load->language('tool/data_sync');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('tool/data_sync');
		////$this->getForm(); // Product
		
		//$this->load->language('catalog/product');
		//$this->document->setTitle($this->language->get('heading_title'));
		$this->getList();
	}


	/*public function upload() {
		$this->load->language('tool/data_sync');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('tool/data_sync');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateUploadForm())) {
			if ((isset( $this->request->files['upload'] )) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
				$file = $this->request->files['upload']['tmp_name'];
				$incremental = ($this->request->post['incremental']) ? true : false;
				if ($this->model_tool_data_sync->upload($file,$this->request->post['incremental'])==true) {
					$this->session->data['success'] = $this->language->get('text_success');
					$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));
				}
				else {
					$this->error['warning'] = $this->language->get('error_upload');
					if (defined('VERSION')) {
						if (version_compare(VERSION,'2.1.0.0') > 0) {
							$this->error['warning'] .= "<br />\n".$this->language->get( 'text_log_details_2_1_x' );
						} else
							$this->error['warning'] .= "<br />\n".$this->language->get( 'text_log_details_2_0_x' );
					} else {
						$this->error['warning'] .= "<br />\n".$this->language->get( 'text_log_details' );
					}
				}
			}
		}

		$this->getForm();
	}*/


	protected function return_bytes($val)
	{
		$val = trim($val);
	
		switch (strtolower(substr($val, -1)))
		{
			case 'm': $val = (int)substr($val, 0, -1) * 1048576; break;
			case 'k': $val = (int)substr($val, 0, -1) * 1024; break;
			case 'g': $val = (int)substr($val, 0, -1) * 1073741824; break;
			case 'b':
				switch (strtolower(substr($val, -2, 1)))
				{
					case 'm': $val = (int)substr($val, 0, -2) * 1048576; break;
					case 'k': $val = (int)substr($val, 0, -2) * 1024; break;
					case 'g': $val = (int)substr($val, 0, -2) * 1073741824; break;
					default : break;
				} break;
			default: break;
		}
		return $val;
	}


	/*public function download() {
		$this->load->language( 'tool/data_sync' );
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model( 'tool/data_sync' );
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateDownloadForm()) {
			$export_type = $this->request->post['export_type'];
			switch ($export_type) {
				case 'c':
				case 'p':
				case 'u':
					$min = null;
					if (isset( $this->request->post['min'] ) && ($this->request->post['min']!='')) {
						$min = $this->request->post['min'];
					}
					$max = null;
					if (isset( $this->request->post['max'] ) && ($this->request->post['max']!='')) {
						$max = $this->request->post['max'];
					}
					if (($min==null) || ($max==null)) {
						$this->model_tool_data_sync->download($export_type, null, null, null, null);
					} else if ($this->request->post['range_type'] == 'id') {
						$this->model_tool_data_sync->download($export_type, null, null, $min, $max);
					} else {
						$this->model_tool_data_sync->download($export_type, $min*($max-1-1), $min, null, null);
					}
					break;
				case 'o':
					$this->model_tool_data_sync->download('o', null, null, null, null);
					break;
				case 'a':
					$this->model_tool_data_sync->download('a', null, null, null, null);
					break;
				case 'f':
					if ($this->model_tool_data_sync->existFilter()) {
						$this->model_tool_data_sync->download('f', null, null, null, null);
						break;
					}
					break;
				default:
					break;
			}
			$this->response->redirect( $this->url->link( 'tool/data_sync', 'token='.$this->request->get['token'], $this->ssl) );
		}

		$this->getForm();
	}*/
	
	public function sync() {
                if(isset($this->request->post['auto_sync_all']) &&  $this->request->post['auto_sync_all'] != "" ) {
                   if($this->config->get('config_auto_sync_all') != 1) {
                       exit;
                   }
                }
                
		$this->load->language('tool/data_sync');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('tool/data_sync');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateSettingsForm())) {
			
			$this->session->data['start_timer'] = date("Y-m-d H:i:s");
			
			//echo "<pre>";print_r($this->request->post);echo "</pre>";exit;
			if(isset($this->request->post['sync_type']) && (isset($this->request->post['sync_table']) || (isset($this->request->post['sync_all_new']) &&  $this->request->post['sync_all_new'] != "" ) ) ) {
				
				$strSyncType = $this->request->post['sync_type'];
				$syncAllNew = $this->request->post['sync_all_new'];
				$arrSyncTables = array();
				if($syncAllNew != "") {
					$tempArrSyncTables = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_list_tables");
                                        foreach($tempArrSyncTables as $tempKey => $tempVal) {
                                            array_push($arrSyncTables, $tempKey);
                                        }
				} else {
					$table = $this->request->post['sync_table'];
					array_push($arrSyncTables, $table);
				}
                                
                                $nowDT = (new DateTime('NOW'))->format('Y-m-d H:i:s');
                                
                                if(isset($this->request->post['updateprice'])) {
                                    $forceUpdatePrice =  $this->request->post['updateprice'];
                                }else {
                                    $forceUpdatePrice = "";
                                }

                                if(isset($this->request->post['markup_percentage'])) {
                                    $markupPercentage =  $this->request->post['markup_percentage'];
                                }else {
                                    $markupPercentage = "";
                                }							
                                if(isset($this->request->post['auto_sync_all']) &&  $this->request->post['auto_sync_all'] != "" ) {
									if($this->config->get('config_auto_sync_all_updateprice') == 1) {
										$forceUpdatePrice = "forceupdate";
									}

									//---------------------------------------------------------------
									if($this->config->get('config_auto_sync_all_markupPercentage') == 1) {
									    if($this->config->get('config_markupPercentage')) {
											$markupPercentage = $this->config->get('config_markupPercentage');
										}else {
											$markupPercentage = "";
										}
									}

									$strSyncType = "sync".implode("",$this->config->get('config_auto_sync_all_type'));
								}
								//var_dump($markupPercentage);
                                
				$boolCanSync = false;
				if($strSyncType=="sync_selected") {
					// var_dump($this->request->post['selected']);
					if(count($this->request->post['selected'])>0) {
						$arrSelectedRows = $this->request->post['selected'];
						//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
						//echo $valueSelected;echo "<br />";
						if(count($arrSelectedRows)>0) {
							$boolCanSync = true;
						}
					}
				} else if($strSyncType!="") {
					$boolCanSync = true;
				}
				
				
				
				//echo $table;exit;
                                
//                                print_r($arrSyncTables);exit;
				

				foreach($arrSyncTables AS $keyTables => $table) {
                     //wani's code starts

					 // if($strSyncType=="sync_selected") {
						// $arrSyncDataa = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", " AND p.`product_id` IN ('".implode("','",$arrSelectedRows)."')");
					// } else {
						// $arrSyncDataa = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", " ");
					// }
					 // $resultss = (isset($arrSyncDataa["tables"][$table])?$arrSyncDataa["tables"][$table]:"");
                     // foreach ($resultss as $keyresult => $result) {
						// foreach($arrSelectedRows as $kvKey => $kvValue) {
							// $kvValue = explode("_",$kvValue);
							// // var_dump($kvValue[0]);
							// if($kvValue[0]==$result['product_id']){
								// // var_dump("yes");
								// if (in_array($result['model'], $productmodel_thirdparty)){
									// echo $result['model']." match found<br>";
									// $boolCanSync = false;
									// var_dump($boolCanSync);
									// echo "<br>";
								// }
								// else{
									// echo $result['model']." not found<br>";
									// $boolCanSync = true;
									// var_dump($boolCanSync);
									// echo "<br>";
								// }
							// }
						// }
					 // }
					 // exit;
					 //wani's code ends

					
                                    if(isset($this->request->post['auto_sync_all']) &&  $this->request->post['auto_sync_all'] != "" ) {
                                        $arrSyncAll = $this->config->get('config_auto_sync_all_type_'.$table);
                                        if(!empty($arrSyncAll)) {
                                            $strSyncType = "sync".implode("",$this->config->get('config_auto_sync_all_type_'.$table));
                                        }else {
                                            continue;
                                        }
                                     }
                                    
                                    if($table!= "") {
                                        $tableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($table)[0]['sync_date'];
                                    }
					
					if($boolCanSync) {
							
						if($table=="oc_product") {
							$intDefaultClassID = 0;
							$arrDefaultClassID = $this->model_tool_data_sync->getDefaultTaxClassID();
							if(isset($arrDefaultClassID["tax_class_id"])) {
								$intDefaultClassID = $arrDefaultClassID["tax_class_id"];
							}
							//echo "<pre>";print_r($arrDefaultClassID);echo "</pre>";exit;
							
//							$intSyncTotal = 0;
//							if($strSyncType=="sync_selected") {
//								$arrSyncTotal = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "total", $table, "", " AND p.`product_id` IN ('".implode("','",$arrSelectedRows)."') ");
//								$intSyncTotal = (isset($arrSyncTotal["tables"][$table][0]["total"])?$arrSyncTotal["tables"][$table][0]["total"]:"");
//							} else {
//								$arrSyncTotal = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "total", $table, "", "");
//								$intSyncTotal = (isset($arrSyncTotal["tables"][$table][0]["total"])?$arrSyncTotal["tables"][$table][0]["total"]:"");
//							} if(!is_numeric($intSyncTotal)) {
//								$intSyncTotal = 0;
//							}
							
//							for($intPaginationLoop = 0; $intPaginationLoop<$intSyncTotal; $intPaginationLoop=$intPaginationLoop+10) {
								//LIMIT 0, 10
								//echo " LIMIT $intPaginationLoop, 10<br />";
								//if($intPaginationLoop>500) break;
								
//								$filter_data = array(
//									'start'           => $intPaginationLoop,
//									'limit'           => 10
//								);
								if($strSyncType=="sync_selected") {
									$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", " AND p.`product_id` IN ('".implode("','",$arrSelectedRows)."')");
								} else {
									$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", " ");
								}
                                                                
                                                            while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncData == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                                
								$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
								//echo "<pre>";print_r($results);echo "</pre>";exit;
								//echo "<pre>";print_r($arrSyncTotal);echo "</pre>";exit;
								//echo "<pre>";print_r($arrSyncData);echo "</pre>";exit;
								
								// $boolThirdParty = false;
								foreach ($results as $keyresult => $result) {
										// foreach($arrSelectedRows as $kvKey => $kvValue) {
											// $kvValue = explode("_",$kvValue);
											// // var_dump($kvValue[0]);
											// if($kvValue[0]==$result['product_id']){
												// // var_dump("yes");
												// if (in_array($result['model'], $productmodel_thirdparty)){
													// echo $result['model']." match found<br>";
													// $boolCanSync = false;
													// var_dump($boolCanSync);
													// echo "<br>";
												// }
												// else{
													// echo $result['model']." not found<br>";
													// $boolCanSync = true;
													// var_dump($boolCanSync);
													// echo "<br>";
												// }
											// }
										// }
								
									// echo "<br><br><br>";echo $result['model'];echo "<br>";
									// exit;
									// if (in_array($result['model'], $productmodel_thirdparty)){
										// echo "Match found<br>";
										// // $boolThirdParty = 
									// }else{
										// echo "not found";
									// }
									
									
									// exit;
                                                                        if($result["sync_brp"] == 0) {
                                                                            continue;
                                                                        }
                                                                        
                                                                        $syncOptions = $result["sync_options"];
                                                                        
                                                                        $result["last_sync_date"] = $nowDT;
									//echo $keyresult;exit;
									$arrDataSourceIDs = array();
									$arrDataSourceIDs[] = $result['product_id'];
									$compareResults = array();
									$compareTempResults = $this->model_tool_data_sync->getProductsNew(array(), $arrDataSourceIDs);
									//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
									foreach ($compareTempResults as $tempResult) {
										$compareResults[$tempResult['data_source']] = $tempResult;
									}
                                                                        
                                                                        $arrManDataSourceIDs = array();
									$arrManDataSourceIDs[] = $result['manufacturer_id'];
									$compareManResults = array();
									$compareManTempResults = $this->model_tool_data_sync->getManufacturersNew(array(), $arrManDataSourceIDs);
									//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
									foreach ($compareManTempResults as $tempManResult) {
										$compareManResults[$tempManResult['data_source']] = $tempManResult;
									}
									//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
									
                                                                        //$arrSyncedRow = $this->model_tool_data_sync->getSyncedHistoryNew(" AND `sync_table`='".$table."' AND `sync_unique_id`='".$result['product_id']."' ORDER BY `sync_date` DESC LIMIT 1 ");
                                                                        
                                                                        if($result["rr_price"] != 0) {
                                                                            $result["price"] = $result["rr_price"];
                                                                        }
									$strHighlightStatus = "New";
									if(isset($compareResults[$result['product_id']])) {
										// For the oc_product, will highlight the products has changes (comparing the date_modified field), EOL product (status field is 0), also the price changes will be highlighed too.
										if($compareResults[$result['product_id']]['status'] != $result['status'] && $result['status']==0) {
                                                                                        $strHighlightStatus = "End Of Life";
                                                                                } else if($result['price']!=$compareResults[$result['product_id']]['data_sync_price']) {
                                                                                        $strHighlightStatus = "Modified";
                                                                                } else if($result['date_modified']>$compareResults[$result['product_id']]['last_sync_date']) {
                                                                                        $strHighlightStatus = "Modified";
                                                                                } else {

                                                                                        $strHighlightStatus = "-";
                                                                                }
										//echo $strHighlightStatus;exit;
										//echo $result['date_modified']." :: ".$compareResults[$result['product_id']]['date_modified'];echo "<br />";
										//echo $result['price']." :: ".$compareResults[$result['product_id']]['price'];echo "<br />";
										//echo "<pre>";print_r($compareResults[$result['product_id']]);echo "</pre>";exit;

										// exit;
									}else {
										
										//$query = $this->db->query("SELECT * FROM oc_product WHERE `model`='".$result['model']."'");
										//if($query->row['data_source']==''){
										//	continue;
										//}
										// $query = $this->db->query("SELECT * FROM oc_product WHERE `data_source`=''");
									// $productmodel_thirdparty = array();
										// foreach($query->rows as $row){
											// $productmodel_thirdparty[] = $row['model'];
										// }
										// if (in_array($result['model'], $productmodel_thirdparty)){
											// continue;
											// // echo $result['model']." match found<br>";
											// // echo "<br>";
										// }
										// else{
											// // echo $result['model']." not found<br>";
											// // echo "<br>";
										// }
										// exit;
										
                                                                            $dateAdded = new DateTime($result['date_added']);
                                                                            $dateSynced = new DateTime($tableSyncDate);

                                                                            if($dateAdded < $dateSynced) {
                                                                                $strHighlightStatus = "Deleted by User";
                                                                            }
                                                                        }
									//echo $strHighlightStatus;exit;
                                                                        
                                                                        if(isset($compareManResults[$result['manufacturer_id']])) {
                                                                            $result['manufacturer_id'] = $compareManResults[$result['manufacturer_id']]['manufacturer_id'];
                                                                        }else {
                                                                            $result['manufacturer_id'] = 0;
                                                                        }
                                                                        
									
									//unset($result["matching_code"]);
                                                                        unset($result["sync_brp"]);
                                                                        unset($result["sync_options"]);
									unset($result["in_stock_status_id"]);
									unset($result["new_pro"]);
									unset($result["erp_status"]);
									unset($result["maxquantity"]);
                                                                        unset($result["minimum"]);
									$result["data_source"] = $result["product_id"];
                                                                        unset($result["rr_price"]);
                                                                        $result["data_sync_price"] = $result["price"];
                                                                        $oriProductId = $result["product_id"];
									unset($result["product_id"]);
                                                                        $result["subtract"] = 0;
									
									// Customize value settings - Start
									//- Replicate/transfer product only where status = 1
									//- 'quantity', 'stock_status_id', 'stock_status_out' fields do not need to be transferred as there will be a direct query to fetch stock from ERP Cloud Server
									//- 'minimum' will be set to 1
									// 'maxquantity' will be set to 0
									// 'sort_order' will be set to 0
									// 'viewed' will be set to 0
									// 'date_added' will be the date the record is inserted into the database and not the value from atoz2u.com
									// 'date_modified' will be set to empty / 0000-00-00 00:00:00
									// 'tax_class_id' to be defaulted to the id set on 'oc_tax_class' table
									// 'new_pro' denotes a new product; when new product is inserted, new_pro = 1
									// 'erp_status' can be ignored as it is used to indicate Goh Office's ERP status (by default all text files imported to Atoz2u.com will have value of 1) 
									// 'status' indicates the status of a product. Note: Changes to product status data via a direct write to the database may cause display errors in Opencart's product details page. Potential solution is to use Opencart API to simulate save of product details form on admin interface.  
									unset($result["quantity"]);
									//unset($result["stock_status_id"]);
									unset($result["name"]);
                                                                        unset($result["manufacturer_name"]);
                                                                        unset($result["category_name"]);
									//$result["minimum"] = "1";
									unset($result["sort_order"]);
									$result["viewed"] = "0";
//									$result["date_added"] = date("Y-m-d H:i:s");
//									$result["date_modified"] = "0000-00-00 00:00:00";
									////$result["tax_class_id"] = $intDefaultClassID;
									// Customize value settings - End
									if($result["image"]!="") {
										$result["image"] = "http://brp.com.my/media/data-server-image/".$result["image"];
									}
									
									//echo "<pre>";print_r($result);echo "</pre>";exit;
									//echo $strHighlightStatus;echo "<br />";exit;
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New" || $strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false) || ($strHighlightStatus=="New" && $result['status']=="0" && strpos($strSyncType, "_eol")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && ($strHighlightStatus=="End Of Life"||$strHighlightStatus=="Modified"||$strHighlightStatus=="-")) || (($strHighlightStatus=="Modified"||$strHighlightStatus=="-") && strpos($strSyncType, "_mod")!==false) || ($strHighlightStatus=="End Of Life" && strpos($strSyncType, "_eol")!==false)) {
										
                                                                                        if(strpos($strSyncType, "_modprice")!==false) {
                                                                                            $tempPrice = $result["price"];
                                                                                            $tempDataSource = $result["data_source"];
                                                                                            $result = array();
                                                                                            $result["price"] = $tempPrice;
                                                                                            $result["data_source"] = $tempDataSource;
                                                                                        }
                                                                                    
                                                                                        if($forceUpdatePrice == "") {
                                                                                            unset($result["price"]);
                                                                                        }else if($compareResults[$oriProductId]['force_update_price'] == 0) {
                                                                                            unset($result["price"]);
                                                                                        }
                                                                                                                                                                             
																						 if($forceUpdatePrice != "" && isset($result["price"]) && $markupPercentage != ""){
																							//$result["price"]=$result["price"];
																							$result["price"]+=$result["price"]*($markupPercentage/100);
																							//var_dump($result["price"]);exit;
																						 }

										
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, " AND `data_source`='".$result["data_source"]."' ");
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
									if(!strpos($strSyncType, "_modprice")!==false) { // Save to history
										//echo "<pre>";print_r($arrHistory);echo "</pre>";exit;
										
										$intDataServerProductID = $result["data_source"];
										$intProductID = $this->model_tool_data_sync->getProductIDBySourceID($result["data_source"]);
										// Custom save to oc_product_to_store table
										/*$this->model_tool_data_sync->ManualDeleteRecord("oc_product_to_store", " AND `product_id`='".$intProductID."' AND `store_id`='0'");
										$arrSyncCheckingData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", "oc_product_to_store", "", " AND `product_id` IN ('".$result["data_source"]."')");
										//echo "<pre>";print_r();echo "</pre>";exit;
										if(isset($arrSyncCheckingData["tables"]["oc_product_to_store"][0]["product_id"])) {
											$arrProduct2Store = array();
											$arrProduct2Store["product_id"] = $intProductID;
											$arrProduct2Store["store_id"] = "0";
											$boolP2SStatus = $this->model_tool_data_sync->ManualInsertRecord($arrProduct2Store, "oc_product_to_store");
											//echo $intProductID;exit;
										}*/
                                                                                
                                                                                if($syncOptions == 1) {
                                                                                    $arrSyncDataOpts = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", "oc_product_option", "", " AND `product_id` = '".$intDataServerProductID."' ");
                                                                                    if($arrSyncDataOpts == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncDataOpts == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                                                    if(isset($arrSyncDataOpts["tables"]["oc_product_option"])&&is_array($arrSyncDataOpts["tables"]["oc_product_option"])&&count($arrSyncDataOpts["tables"]["oc_product_option"])>0) {
                                                                                        $this->model_tool_data_sync->ManualDeleteJoinRecord("oc_product_option_value t", "INNER JOIN oc_product_option c ON t.product_option_id = c.product_option_id", " AND t.`product_id`='".$intProductID."' AND c.data_source !=''");
                                                                                        $this->model_tool_data_sync->ManualDeleteRecord("oc_product_option", " AND `product_id`='".$intProductID."'  AND data_source !=''"); 
                                                                                        foreach($arrSyncDataOpts["tables"]["oc_product_option"] AS $productOption) {
                                                                                            
                                                                                            if($intOptionID = $this->model_tool_data_sync->getOptionIDBySourceID($productOption['option_id']) == 0) {
                                                                                            
                                                                                            $arrSyncDataOption = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", "oc_option", "", " AND `option_id` = '".$productOption['option_id']."' ");
                                                                                            if($arrSyncDataOption == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncDataOption == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                                                            if(isset($arrSyncDataOption["tables"]["oc_option"])&&is_array($arrSyncDataOption["tables"]["oc_option"])&&count($arrSyncDataOption["tables"]["oc_option"])>0) {
                                                                                                foreach($arrSyncDataOption["tables"]["oc_option"] AS $options) {
                                                                                                    $arrQuickOption = $options;
                                                                                                    $arrQuickOption['option_id'] = 'NULL';
                                                                                                    $arrQuickOption['data_source'] = $options['option_id'];
                                                                                                    $boolDescStatus = $this->model_tool_data_sync->ManualInsertRecord($arrQuickOption, "oc_option");
                                                                                                    $intOptionID = $this->model_tool_data_sync->getOptionIDBySourceID($arrQuickOption["data_source"]);
                                                                                                    
                                                                                                    $arrSyncDataOptionDesc = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", "oc_option_description", "", " AND `option_id` = '".$arrQuickOption["data_source"]."' ");
                                                                                                    if($arrSyncDataOptionDesc == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncDataOptionDesc == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                                                                    if(isset($arrSyncDataOptionDesc["tables"]["oc_option_description"])&&is_array($arrSyncDataOptionDesc["tables"]["oc_option_description"])&&count($arrSyncDataOptionDesc["tables"]["oc_option_description"])>0) {
                                                                                                        foreach($arrSyncDataOptionDesc["tables"]["oc_option_description"] AS $optionsDesc) {
                                                                                                            $arrQuickOptionDesc = $optionsDesc;
                                                                                                            $arrQuickOptionDesc['option_id'] = $this->model_tool_data_sync->getOptionIDBySourceID($optionsDesc["option_id"]);
                                                                                                            $boolDescStatus = $this->model_tool_data_sync->ManualInsertRecord($arrQuickOptionDesc, "oc_option_description");

                                                                                                        }
                                                                                                    }
                                                                                                    
                                                                                                }
                                                                                            }
                                                                                            }
                                                                                            
                                                                                                $arrQuickOpts = $productOption;
                                                                                                $arrQuickOpts['product_option_id'] = 'NULL';
                                                                                                $arrQuickOpts['data_source'] = $productOption['product_option_id'];
                                                                                                $arrQuickOpts["product_id"] = $intProductID;
                                                                                                $arrQuickOpts["option_id"] = $this->model_tool_data_sync->getOptionIDBySourceID($productOption["option_id"]);
                                                                                                $boolDescStatus = $this->model_tool_data_sync->ManualInsertRecord($arrQuickOpts, "oc_product_option");
                                                                                                $intProductOptionID = $this->model_tool_data_sync->getProductOptionIDBySourceID($arrQuickOpts["data_source"]);
                                                                                                
                                                                                                if($boolDescStatus) {
                                                                                                    $arrSyncDataOptsVal = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", "oc_product_option_value", "", " AND `product_option_id` = '".$arrQuickOpts["data_source"]."' ");
                                                                                                    if($arrSyncDataOptsVal == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncDataOptsVal == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                                                                    if(isset($arrSyncDataOptsVal["tables"]["oc_product_option_value"])&&is_array($arrSyncDataOptsVal["tables"]["oc_product_option_value"])&&count($arrSyncDataOptsVal["tables"]["oc_product_option_value"])>0) {
                                                                                                        foreach($arrSyncDataOptsVal["tables"]["oc_product_option_value"] AS $productOptionValue) {
                                                                                                            
                                                                                                            if($this->model_tool_data_sync->getOptionValueIDBySourceID($productOptionValue['option_value_id']) == 0) {
                                                                                                            
                                                                                                            $arrSyncDataOptionValue = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", "oc_option_value", "", " AND `option_value_id` = '".$productOptionValue['option_value_id']."' ");
                                                                                                            if($arrSyncDataOptionValue == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncDataOptionValue == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                                                                            if(isset($arrSyncDataOptionValue["tables"]["oc_option_value"])&&is_array($arrSyncDataOptionValue["tables"]["oc_option_value"])&&count($arrSyncDataOptionValue["tables"]["oc_option_value"])>0) {
                                                                                                                foreach($arrSyncDataOptionValue["tables"]["oc_option_value"] AS $optionsValue) {
                                                                                                                    $arrQuickOptionVal = $optionsValue;
                                                                                                                    $arrQuickOptionVal['option_value_id'] = 'NULL';
                                                                                                                    $arrQuickOptionVal['data_source'] = $optionsValue['option_value_id'];
                                                                                                                    $arrQuickOptionVal['option_id'] = $this->model_tool_data_sync->getOptionIDBySourceID($optionsValue["option_id"]);
                                                                                                                    $boolDescStatus = $this->model_tool_data_sync->ManualInsertRecord($arrQuickOptionVal, "oc_option_value");
                                                                                                                    $intOptionValueID = $this->model_tool_data_sync->getOptionValueIDBySourceID($arrQuickOptionVal["data_source"]);
                                                                                                                    
                                                                                                                    $arrSyncDataOptionValueDesc = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", "oc_option_value_description", "", " AND `option_value_id` = '".$productOptionValue['option_value_id']."' ");
                                                                                                                    if($arrSyncDataOptionValueDesc == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncDataOptionValueDesc == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                                                                                    if(isset($arrSyncDataOptionValueDesc["tables"]["oc_option_value_description"])&&is_array($arrSyncDataOptionValueDesc["tables"]["oc_option_value_description"])&&count($arrSyncDataOptionValueDesc["tables"]["oc_option_value_description"])>0) {
                                                                                                                        foreach($arrSyncDataOptionValueDesc["tables"]["oc_option_value_description"] AS $optionsValueDesc) {
                                                                                                                            $arrQuickOptionValDesc = $optionsValueDesc;
                                                                                                                            $arrQuickOptionValDesc['option_value_id'] = $this->model_tool_data_sync->getOptionValueIDBySourceID($optionsValueDesc["option_value_id"]);
                                                                                                                            $arrQuickOptionValDesc['option_id'] = $this->model_tool_data_sync->getOptionIDBySourceID($optionsValueDesc["option_id"]);
                                                                                                                            $boolDescStatus = $this->model_tool_data_sync->ManualInsertRecord($arrQuickOptionValDesc, "oc_option_value_description");
                                                                                                                        }
                                                                                                                    }
                                                                                                                    
                                                                                                                }
                                                                                                            }
                                                                                                            
                                                                                                            }
                                                                                                            
                                                                                                            $arrQuickOptsVal = $productOptionValue;
                                                                                                            $arrQuickOptsVal['product_option_value_id'] = 'NULL';
                                                                                                            $arrQuickOptsVal["product_id"] = $intProductID;
                                                                                                            $arrQuickOptsVal["product_option_id"] = $this->model_tool_data_sync->getProductOptionIDBySourceID($productOptionValue["product_option_id"]);
                                                                                                            $arrQuickOptsVal["option_id"] = $this->model_tool_data_sync->getOptionIDBySourceID($productOptionValue["option_id"]);
                                                                                                            $arrQuickOptsVal["option_value_id"] = $this->model_tool_data_sync->getOptionValueIDBySourceID($productOptionValue["option_value_id"]);
                                                                                                            $boolDescStatus = $this->model_tool_data_sync->ManualInsertRecord($arrQuickOptsVal, "oc_product_option_value");
                                                                                                            $intProductOptionID = $this->model_tool_data_sync->getProductOptionIDBySourceID($arrQuickOpts["data_source"]);
                                                                                                        }
                                                                                                    }
                                                                                                    
                                                                                                }
                                                                                                
                                                                                        }
                                                                                    }
                                                                                }
										
										// Custom save to oc_product_description table
										$arrSyncDataDesc = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", "oc_product_description", "", " AND `product_id` = '".$intDataServerProductID."' ");
                                                                                if($arrSyncDataDesc == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncDataDesc == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
										if(isset($arrSyncDataDesc["tables"]["oc_product_description"][0])&&is_array($arrSyncDataDesc["tables"]["oc_product_description"][0])&&count($arrSyncDataDesc["tables"]["oc_product_description"][0])>0) {
											$arrQuickDesc = $arrSyncDataDesc["tables"]["oc_product_description"][0];
											$arrQuickDesc["product_id"] = $intProductID;
											unset($arrQuickDesc["quotationtext"]); unset($arrQuickDesc["custom_alt"]); unset($arrQuickDesc["custom_h1"]); unset($arrQuickDesc["custom_h2"]); unset($arrQuickDesc["custom_imgtitle"]); unset($arrQuickDesc["custom_title"]);
                                                                                        $arrQuickDesc["name"] = str_replace(["atoz2u","atoz"], "", $arrQuickDesc["name"]);
                                                                                        $arrQuickDesc["description"] = str_replace(["atoz2u","atoz"], "", $arrQuickDesc["description"]);
                                                                                        $arrQuickDesc["tag"] = str_replace(["atoz2u","atoz"], "", $arrQuickDesc["tag"]);
                                                                                        $arrQuickDesc["meta_title"] = str_replace(["atoz2u","atoz"], "", $arrQuickDesc["meta_title"]);
                                                                                        $arrQuickDesc["meta_description"] = str_replace(["atoz2u","atoz"], "", $arrQuickDesc["meta_description"]);
                                                                                        $arrQuickDesc["meta_keyword"] = str_replace(["atoz2u","atoz"], "", $arrQuickDesc["meta_keyword"]);
											//echo "<pre>";print_r($arrQuickDesc);echo "</pre>";exit;
											$this->model_tool_data_sync->ManualDeleteRecord("oc_product_description", " AND `product_id`='".$intProductID."'");
                                                                                        $this->model_tool_data_sync->ManualDeleteRecord("oc_product_image", " AND `product_id`='".$intProductID."'");
                                                                                        $this->model_tool_data_sync->ManualDeleteJoinRecord("oc_product_to_category t", "LEFT JOIN oc_category c ON t.category_id = c.category_id", " AND t.`product_id`='".$intProductID."' AND (c.data_source !='' OR c.data_source IS NULL) ");
                                                                                        $this->model_tool_data_sync->ManualDeleteRecord("oc_product_filter", " AND `product_id`='".$intProductID."'");
											$boolDescStatus = $this->model_tool_data_sync->ManualInsertRecord($arrQuickDesc, "oc_product_description");
										}
										
										
									}
									
								}
                                                                
                                                                if($strSyncType=="sync_selected") {
                                                                    $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", " AND p.`product_id` IN ('".implode("','",$arrSelectedRows)."')", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
                                                                } else {
                                                                    $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", " ", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
                                                                }
                                                                
                                                        }
                                                        
//							}
							//echo "Done";exit;
                                                        if(!strpos($strSyncType, "_modprice")!==false) {
                                                        
                                                        $subTables = array(
                                                                "oc_product_attribute",
                                                                "oc_product_description",
                                                                "oc_product_discount",
                                                                "oc_product_filter",
                                                                "oc_product_image",
                                                                "oc_product_maxqty_groups",
//                                                                "oc_product_option",
//                                                                "oc_product_option_value",
                                                                "oc_product_recurring",
                                                                "oc_product_related",
                                                                "oc_product_reward",
//                                                                "oc_product_special",
                                                                "oc_product_to_category",
                                                                "oc_product_to_download",
                                                                "oc_product_to_field",
                                                                "oc_product_to_layout",
                                                                "oc_product_to_store"
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_product_attribute" => array("product_id", "attribute_id", "language_id"),
                                                                    "oc_product_description" => array("product_id", "language_id"),
                                                                    "oc_product_discount" => array("product_discount_id"), //array("product_id", "product_discount_id", "customer_group_id"),
                                                                    "oc_product_filter" => array("product_id", "filter_id"),
                                                                    "oc_product_image" => array("product_image_id"), //array("product_id", "product_image_id"),
                                                                    "oc_product_option" => array("product_option_id"), //array("product_id", "product_option_id", "option_id"),
                                                                    "oc_product_option_value" => array("product_option_value_id"), //array("product_id", "product_option_value_id", "product_option_id", "option_id", "option_value_id"),
                                                                    "oc_product_recurring" => array("product_id", "recurring_id", "customer_group_id"),
                                                                    "oc_product_related" => array("product_id", "related_id"),
                                                                    "oc_product_reward" => array("product_reward_id"), //array("product_id", "product_reward_id", "customer_group_id"),
//                                                                    "oc_product_special" => array("product_special_id"), //array("product_id", "product_special_id", "customer_group_id"),
                                                                    "oc_product_to_category" => array("product_id", "category_id"),
                                                                    "oc_product_to_download" => array("product_id", "download_id"),
                                                                    "oc_product_to_layout" => array("product_id", "store_id"),
                                                                    "oc_product_to_store" => array("product_id", "store_id"),
                                                                    "oc_product_maxqty_groups" => array("product_id", "customer_group_id"), // new table
                                                                    "oc_product_to_field" => array("product_id", "additional_product_id", "language_id") // new table
                                                            );

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `product_id` IN ('".implode("','",$arrSelectedRows)."')");
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            // Product ID Mapping - Start
                                                            $arrDataSourceIDs = array();
                                                            foreach ($results as $result) {
                                                                    $arrDataSourceIDs[] = $result["product_id"];
                                                                    if($subTable=="oc_product_related") {
                                                                        if(!in_array($result["related_id"], $arrDataSourceIDs)) {
                                                                            $arrDataSourceIDs[] = $result["related_id"];
                                                                        }
                                                                    }
                                                                    
                                                            }
                                                            $arrMappingSourceProdcutData = array();
                                                            $arrMappingProductSourceData = array();
                                                            $arrTempMappingData = $this->model_tool_data_sync->getProductMappingsNew($arrDataSourceIDs);
                                                            foreach ($arrTempMappingData as $keyTemp => $keyResult) {
                                                                    $arrMappingSourceProdcutData[$keyResult["product_id"]] = $keyResult["data_source"];
                                                                    $arrMappingProductSourceData[$keyResult["data_source"]] = $keyResult["product_id"];
                                                            }
                                                            
                                                            if($subTable=="oc_product_to_category") {
                                                                $arrCatDataSourceIDs = array();
                                                                foreach ($results as $result) {
                                                                        $arrCatDataSourceIDs[] = $result["category_id"];

                                                                }
                                                                $arrMappingSourceCategoryData = array();
                                                                $arrMappingCategorySourceData = array();
                                                                $arrTempMappingData = $this->model_tool_data_sync->getCategoryMappingsNew($arrCatDataSourceIDs);
                                                                foreach ($arrTempMappingData as $keyTemp => $keyResult) {
                                                                        $arrMappingSourceCategoryData[$keyResult["category_id"]] = $keyResult["data_source"];
                                                                        $arrMappingCategorySourceData[$keyResult["data_source"]] = $keyResult["category_id"];
                                                                }
                                                            }
                                                            
                                                            //echo "<pre>";print_r($arrMappingSourceProdcutData);echo "</pre>";
                                                                    //Array([19914] => 19899, [30126] => 19901)
                                                            //echo "<pre>";print_r($arrMappingProductSourceData);echo "</pre>";
                                                                    //Array([19899] => 19914, [19901] => 30126)
                                                            //exit;
                                                            // Product ID Mapping - End

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            if($valueKey=="product_id") {
                                                                                    $arrCustomTableKeyValues[$valueKey][] = (isset($arrMappingProductSourceData[$result[$valueKey]])?$arrMappingProductSourceData[$result[$valueKey]]:$result[$valueKey]);
                                                                                    //$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                            } else if($subTable=="oc_product_related" && $valueKey=="related_id") {
                                                                                    $arrCustomTableKeyValues[$valueKey][] = (isset($arrMappingProductSourceData[$result[$valueKey]])?$arrMappingProductSourceData[$result[$valueKey]]:$result[$valueKey]);
                                                                                    //$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                            } else if($subTable=="oc_product_to_category" && $valueKey=="category_id") {
                                                                                    $arrCustomTableKeyValues[$valueKey][] = (isset($arrMappingCategorySourceData[$result[$valueKey]])?$arrMappingCategorySourceData[$result[$valueKey]]:$result[$valueKey]);
                                                                            } else {
                                                                                    $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                            }
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";//exit;
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";exit;
                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if($arrCustomTableKey[$subTable][0]=="product_id") {
                                                                            $tempResult[$arrCustomTableKey[$subTable][0]] = (isset($arrMappingSourceProdcutData[$tempResult["product_id"]])?$arrMappingSourceProdcutData[$tempResult["product_id"]]:$tempResult["product_id"]); // convert the data_source to partner product_id
                                                                    }
                                                                    if($subTable=="oc_product_related") {
                                                                        if($arrCustomTableKey[$subTable][1]=="related_id") {
                                                                                $tempResult[$arrCustomTableKey[$subTable][1]] = (isset($arrMappingSourceProdcutData[$tempResult["related_id"]])?$arrMappingSourceProdcutData[$tempResult["related_id"]]:$tempResult["related_id"]); // convert the data_source to partner product_id
                                                                        }
                                                                    }
                                                                    if($subTable=="oc_product_to_category") {
                                                                        if($arrCustomTableKey[$subTable][1]=="category_id") {
                                                                                $tempResult[$arrCustomTableKey[$subTable][1]] = (isset($arrMappingSourceCategoryData[$tempResult["category_id"]])?$arrMappingSourceCategoryData[$tempResult["category_id"]]:$tempResult["category_id"]); // convert the data_source to partner product_id
                                                                        }
                                                                    }
                                                                    //echo "<pre>";print_r($tempResult);echo "</pre>";exit;
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                                                            /*// Product Related - Start
                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getProductRelatedNew($subTable, $arrMappingProductSourceData);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $result) {
                                                                    $compareResults[$arrMappingSourceProdcutData[$result["product_id"]]] = $result;
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;
                                                            // Product Related - End*/

                                                            if(
                                                                    $subTable=="oc_product_discount" || 
                                                                    $subTable=="oc_product_image" || 
                                                                    $subTable=="oc_product_option" || 
                                                                    $subTable=="oc_product_option_value" ||
                                                                    $subTable=="oc_product_reward" 
//                                                                    || $subTable=="oc_product_special"
                                                                    ) {
                                                                    foreach ($results as $keyResult => $result) {
                                                                        $result["last_sync_date"] = $nowDT;
                                                                            //if($result["product_image_id"]!="6901") {continue;}
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
                                                                            if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]])) {
                                                                                    if(($subTable=="new_tablex") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }
                                                                            }else {
                                                                                if(($subTable=="new_tablex")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
                                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;
                                                                            $arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];

                                                                                    $arrMapping["product_id"] = (isset($arrMappingProductSourceData[$result["product_id"]])?$arrMappingProductSourceData[$result["product_id"]]:"");
                                                                            }
                                                                            //echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
                                                                            //$data['products'][] = $arrMapping;

                                                                            if($subTable=="oc_product_image"&&$result["image"]!="") {
                                                                                    $result["image"] = "http://brp.com.my/media/data-server-image/".$result["image"];
                                                                                    $result["product_image_id"] = 'NULL';
                                                                                    $strHighlightStatus = "New";
                                                                            }else if($subTable=="oc_product_image") {
                                                                                    $result["product_image_id"] = 'NULL';
                                                                                    $strHighlightStatus = "New";
                                                                            }

                                                                            $result["product_id"] = $arrMapping["product_id"];
                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                            $boolStatus = false;
                                                                    if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false) || ($subTable=="oc_product_image")) { // Insert
                                                                                    //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                    $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
                                                                                    //echo $boolStatus;echo "<br />";//exit;
                                                                            } else { // Update
                                                                                    if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                            $strCondition = " AND 1=0 ";
                                                                                            if(count($arrCustomTableKey[$subTable])=="1"){
                                                                                                    $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' ";
                                                                                            }
                                                                                            //echo $strCondition;exit;
                                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
                                                                                            //echo $boolStatus;echo "<br />";//exit;
                                                                                    }
                                                                            }
                                                                    }						
                                                            } else if(
                                                                    $subTable=="oc_product_description" || 
                                                                    $subTable=="oc_product_filter" || 
                                                                    $subTable=="oc_product_related" || 
                                                                    $subTable=="oc_product_to_category" || 
                                                                    $subTable=="oc_product_to_download" || 
                                                                    $subTable=="oc_product_to_store" || 
                                                                    $subTable=="oc_product_maxqty_groups" ||  
                                                                    $subTable=="oc_product_to_layout"
                                                                    ) {
                                                                    foreach ($results as $keyResult => $result) {
                                                                        $result["last_sync_date"] = $nowDT;
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
//                                                                            print_r($compareResults[$result[$arrCustomTableKey[$subTable][0]]]);
                                                                            //print_r($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]);
                                                                            if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]])) {
                                                                                    if(($subTable=="new_tablex") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }
                                                                            }else {
                                                                                if(($subTable=="new_tablex")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
                                                                            //$arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                    $arrMapping["product_id"] = (isset($arrMappingProductSourceData[$result["product_id"]])?$arrMappingProductSourceData[$result["product_id"]]:"");
                                                                                    if($subTable=="oc_product_related") {
                                                                                        $arrMapping["related_id"] = (isset($arrMappingProductSourceData[$result["related_id"]])?$arrMappingProductSourceData[$result["related_id"]]:"");
                                                                                    }
                                                                                    if($subTable=="oc_product_to_category") {
                                                                                        $arrMapping["category_id"] = (isset($arrMappingCategorySourceData[$result["category_id"]])?$arrMappingCategorySourceData[$result["category_id"]]:"");
                                                                                    }
                                                                            }
                                                                            
                                                                            if($subTable=="oc_product_description") {
                                                                                    unset($result["quotationtext"]); unset($result["custom_alt"]); unset($result["custom_h1"]); unset($result["custom_h2"]); unset($result["custom_imgtitle"]); unset($result["custom_title"]);
                                                                                    $result["name"] = str_replace(["atoz2u","atoz"], "", $result["name"]);
                                                                                    $result["description"] = str_replace(["atoz2u","atoz"], "", $result["description"]);
                                                                                    $result["tag"] = str_replace(["atoz2u","atoz"], "", $result["tag"]);
                                                                                    $result["meta_title"] = str_replace(["atoz2u","atoz"], "", $result["meta_title"]);
                                                                                    $result["meta_description"] = str_replace(["atoz2u","atoz"], "", $result["meta_description"]);
                                                                                    $result["meta_keyword"] = str_replace(["atoz2u","atoz"], "", $result["meta_keyword"]);
                                                                            }
                                                                            //echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
                                                                            //$data['products'][] = $arrMapping;

                                                                            $result["product_id"] = $arrMapping["product_id"];
                                                                            if($subTable=="oc_product_related") {
                                                                                $result["related_id"] = $arrMapping["related_id"];
                                                                            }
                                                                            if($subTable=="oc_product_to_category") {
                                                                                $result["category_id"] = $arrMapping["category_id"];
                                                                            }
                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                            $boolStatus = false;
                                                                            if($result["product_id"]!="") {
                                                                                
                                                                                if(($subTable=="oc_product_related" && $result["related_id"]=="") || ($subTable=="oc_product_to_category" && $result["category_id"]=="")) {
                                                                                    
                                                                                }else {
                                                                                
                                                                                    if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false) || ($subTable=="oc_product_to_category")) { // Insert

                                                                                        if($subTable=="oc_product_to_category") {
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualInsertIgnoreRecord($result, $subTable);
                                                                                        }else {
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
                                                                                        }
                                                                                            //echo $boolStatus;echo "<br />";//exit;
                                                                                    } else { // Update
                                                                                            if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                                    $strCondition = " AND 1=0 ";
                                                                                                    if(count($arrCustomTableKey[$subTable])=="2"){
                                                                                                            $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' ";
                                                                                                    }
                                                                                                    //echo $strCondition;exit;
                                                                                                    //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                                    $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
                                                                                                    //echo $boolStatus;echo "<br />";//exit;
                                                                                            }
                                                                                    }
                                                                                }
                                                                            }
                                                                    }
                                                            } else if(
//                                                                    $subTable=="oc_product_to_field" ||
                                                                    $subTable=="oc_product_attribute" || 
                                                                    $subTable=="oc_product_recurring"
                                                                    ) {
                                                                    foreach ($results as $keyResult => $result) {
                                                                        $result["last_sync_date"] = $nowDT;
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
                                                                            if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]][$result[$arrCustomTableKey[$subTable][2]]])) {
                                                                                    if(($subTable=="new_tablex") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]][$result[$arrCustomTableKey[$subTable][2]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }
                                                                            }else {
                                                                                if(($subTable=="new_tablex")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
                                                                            $arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                    $arrMapping["product_id"] = (isset($arrMappingProductSourceData[$result["product_id"]])?$arrMappingProductSourceData[$result["product_id"]]:"");
                                                                            }
                                                                            //echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
                                                                            //$data['products'][] = $arrMapping;


                                                                            $result["product_id"] = $arrMapping["product_id"];
                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                            if($result["product_id"]!="") {
                                                                                    $boolStatus = false;
                                                                                    if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
                                                                                            //echo $boolStatus;echo "<br />";//exit;
                                                                                    } else { // Update
                                                                                            if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                                    $strCondition = " AND 1=0 ";
                                                                                                    if(count($arrCustomTableKey[$subTable])=="3"){
                                                                                                            $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' AND `".$arrCustomTableKey[$subTable][2]."`='".$arrMapping[$arrCustomTableKey[$subTable][2]]."' ";
                                                                                                    }
                                                                                                    //echo $strCondition;exit;
                                                                                                    //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                                    $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
                                                                                                    //echo $boolStatus;echo "<br />";//exit;
                                                                                            }
                                                                                    }
                                                                            }
                                                                    }
                                                            }

                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "",  " AND `product_id` IN ('".implode("','",$arrSelectedRows)."')", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                            } else {
                                                                $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                            }

                                                        }
                                                        
                                                        $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                            
                                                        }
                                                }
							
						} 
                                                
                                                else if($table=="oc_banner") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_banner" => array("banner_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_banner") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
										
									}else {
                                                                                if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									//$data['products'][] = $arrMapping;
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $subTables = array(
                                                                "oc_banner_image",
                                                                "oc_banner_image_description"
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_banner_image" => array("banner_image_id"),
                                                                    "oc_banner_image_description" => array("banner_image_id", "language_id")
                                                            );
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `banner_id` IN ('".implode("','",$arrSelectedRows)."')");
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }
    //                                                        $subArrSyncData2 = array();
    //                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
    //                                                        
    //                                                        print_r($subArrSyncData);exit;

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
                                                            //exit;

                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                                                            if($subTable=="oc_banner_image") {
                                                                    foreach ($results as $keyResult => $result) {
                                                                        $result["last_sync_date"] = $nowDT;
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
                                                                            if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]])) {
                                                                                    if(($subTable=="oc_category"||$subTable=="oc_tax_class"||$subTable=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }

                                                                            }else {
                                                                                if(($subTable=="oc_category"||$subTable=="oc_tax_class"||$subTable=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }

                                                                            // Phsically grab the image - Start
                                                                            if($subTable=="oc_banner_image" && $result["image"]!="") {
                                                                                    $result["language_id"] = "1";
                                                                                    //$result["image"] = "http://brp.com.my/media/data-server-image/".$result["image"];

                                                                                    //echo $result["image"];exit;
                                                                                    $arrImgNames = explode("/", $result["image"]);
                                                                                    $strImgName = end($arrImgNames);
                                                                                    array_pop($arrImgNames); // Remove last array element
                                                                                    //echo "<pre>";print_r($arrImgNames);echo "</pre>";exit;

                                                                                    $currImgFolderLevel = DIR_IMAGE;
                                                                                    foreach($arrImgNames as $keyImgNames => $valueImgNames) {
                                                                                            if($keyImgNames>0) {
                                                                                                    $currImgFolderLevel .= "/";
                                                                                            } $currImgFolderLevel .= $valueImgNames;
                                                                                            //echo "<pre>";print_r($currImgFolderLevel);echo "</pre>";
                                                                                            if(!is_dir($currImgFolderLevel)) {
                                                                                                    mkdir($currImgFolderLevel, 0777, true);	
                                                                                            }
                                                                                    }

                                                                                    $image ="http://brp.com.my/media/data-server-image/".implode("/", $arrImgNames)."/".rawurlencode($strImgName);
                                                                                    //$image ="http://brp.com.my/media/data-server-image/".rawurlencode("HP Logo.png");
                                                                                    //$image ="http://brp.com.my/media/data-server-image/catalog/Manufacturer/HP%20Logo.png";
                                                                                    //echo $image."<br />";exit;

                                                                                    $ch = curl_init($image);
                                                                                    curl_setopt($ch, CURLOPT_HEADER, 0);
                                                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                                                    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                                                                                    $rawdata=curl_exec($ch);
                                                                                    curl_close ($ch);
                                                                                    //echo "<pre>";print_r($rawdata);echo "</pre>";exit;

                                                                                    $fp = fopen(DIR_IMAGE.$result["image"],'w');
                                                                                    fwrite($fp, $rawdata); 
                                                                                    fclose($fp);
                                                                                    //exit;
                                                                            }
                                                                            // Phsically grab the image - End

                                                                            $arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                    $arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]];
                                                                            }
                                                                            //$data['products'][] = $arrMapping;
                                                                            unset($result["start_date"]);
                                                                            unset($result["end_date"]);

                                                                            $boolStatus = false;
                                                                            if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
                                                                                    $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
                                                                                    //echo $boolStatus;echo "<br />";//exit;
                                                                            } else { // Update
                                                                                    if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                            $strCondition = " AND 1=0 ";
                                                                                            if(count($arrCustomTableKey[$subTable])=="1"){
                                                                                                    $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' ";
                                                                                            }
                                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
                                                                                            //echo $boolStatus;echo "<br />";//exit;
                                                                                    }
                                                                            }
                                                                    }

                                                            }  else if($subTable=="oc_banner_image_description") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]])) {
										if(($subTable=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($subTable=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]]."_".$result[$arrCustomTableKey[$subTable][1]];
									}
									//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$subTable])=="2"){
												$strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
							}

                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `banner_id` IN ('".implode("','",$arrSelectedRows)."')", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }

                                                    }
                                                    
                                                    $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                            }                   
							
                                        } 
                                        else if($table=="oc_manufacturer") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_manufacturer" => array("manufacturer_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							// Category ID Mapping - Start
                                                        $arrDataSourceIDs = array();
                                                        foreach ($results as $result) {
                                                                $arrDataSourceIDs[] = $result["manufacturer_id"];

                                                        }
							
                                                        $arrMappingSourceManufacturerData = array();
                                                        $arrMappingManufacturerSourceData = array();
                                                        $arrTempMappingData = $this->model_tool_data_sync->getManufacturerMappingsNew($arrDataSourceIDs);
                                                        foreach ($arrTempMappingData as $keyTemp => $keyResult) {
                                                                $arrMappingSourceManufacturerData[$keyResult["manufacturer_id"]] = $keyResult["data_source"];
                                                                $arrMappingManufacturerSourceData[$keyResult["data_source"]] = $keyResult["manufacturer_id"];
                                                        }


                                                        $arrCustomTableKeyValues = array();
                                                        foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
                                                                foreach ($results as $result) {
                                                                        if($valueKey=="manufacturer_id") {
                                                                                $arrCustomTableKeyValues[$valueKey][] = (isset($arrMappingManufacturerSourceData[$result[$valueKey]])?$arrMappingManufacturerSourceData[$result[$valueKey]]:$result[$valueKey]);
                                                                        }else {
                                                                                $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                        }
                                                                }
                                                        }
                                                       
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_manufacturer") {
								foreach ($results as $keyResult => $result) {
                                                                    
                                                                    $arrDataSourceIDs = array();
                                                                    $arrDataSourceIDs[] = $result['manufacturer_id'];
                                                                    $compareResults = array();
                                                                    $compareTempResults = $this->model_tool_data_sync->getManufacturersNew(array(), $arrDataSourceIDs);
                                                                    //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                                    foreach ($compareTempResults as $tempResult) {
                                                                            $compareResults[$tempResult['data_source']] = $tempResult;
                                                                    }
                                                                    
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                $arrMapping["manufacturer_id"] = (isset($arrMappingManufacturerSourceData[$result["manufacturer_id"]])?$arrMappingManufacturerSourceData[$result["manufacturer_id"]]:"");
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									//$data['products'][] = $arrMapping;
                                                                        
                                                                        if($table=="oc_manufacturer") {
                                                                                $result["data_source"] = $result["manufacturer_id"];
                                                                                unset($result["manufacturer_id"]);
									}
                                                                        
                                                                        $result["manufacturer_id"] = $arrMapping["manufacturer_id"];
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							} 
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $subTables = array(
                                                                "oc_manufacturer_description",
                                                                "oc_manufacturer_to_store"
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_manufacturer_description" => array("manufacturer_id", "language_id"), // new table
                                                                    "oc_manufacturer_to_store" => array("manufacturer_id", "store_id")
                                                            );
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `manufacturer_id` IN ('".implode("','",$arrSelectedRows)."')");
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }
    //                                                        $subArrSyncData2 = array();
    //                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
    //                                                        
    //                                                        print_r($subArrSyncData);exit;

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
                                                            //exit;

                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                                                            if($subTable=="oc_manufacturer_to_store" || $subTable=="oc_manufacturer_description") {
                                                                    foreach ($results as $keyResult => $result) {
                                                                        $result["last_sync_date"] = $nowDT;
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
                                                                            if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]])) {
                                                                                    if(($subTable=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }
                                                                            }else {
                                                                                if(($subTable=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
                                                                            if($subTable=="oc_manufacturer_description") {
                                                                                unset($result["description"]);
                                                                            }
                                                                            
                                                                            $arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                if($subTable=="oc_manufacturer_description" && $valueColumn == "description") {
                                                                                    continue;
                                                                                }
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                    $arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]]."_".$result[$arrCustomTableKey[$subTable][1]];
                                                                            }
                                                                            //echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
                                                                            $boolStatus = false;
                                                                            if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
                                                                                    $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
                                                                                    //echo $boolStatus;echo "<br />";//exit;
                                                                            } else { // Update
                                                                                    if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                            $strCondition = " AND 1=0 ";
                                                                                            if(count($arrCustomTableKey[$subTable])=="2"){
                                                                                                    $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' ";
                                                                                            }
                                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
                                                                                            //echo $boolStatus;echo "<br />";//exit;
                                                                                    }
                                                                            }
                                                                    }
                                                            }

                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `manufacturer_id` IN ('".implode("','",$arrSelectedRows)."')", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }


                                                    }
                                                    
                                                    $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                    
                                                }
                                                
							
						}
                                                
                                                else if($table=="oc_category") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_category" => array("category_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							// Category ID Mapping - Start
                                                            $arrDataSourceIDs = array();
                                                            foreach ($results as $result) {
                                                                    $arrDataSourceIDs[] = $result["category_id"];
                                                                    if($table=="oc_product_related") {
                                                                        if(!in_array($result["category_id"], $arrDataSourceIDs)) {
                                                                            $arrDataSourceIDs[] = $result["path_id"];
                                                                        }
                                                                    }
                                                                    
                                                            }
                                                            $arrMappingSourceCategoryData = array();
                                                            $arrMappingCategorySourceData = array();
                                                            $arrTempMappingData = $this->model_tool_data_sync->getCategoryMappingsNew($arrDataSourceIDs);
                                                            foreach ($arrTempMappingData as $keyTemp => $keyResult) {
                                                                    $arrMappingSourceCategoryData[$keyResult["category_id"]] = $keyResult["data_source"];
                                                                    $arrMappingCategorySourceData[$keyResult["data_source"]] = $keyResult["category_id"];
                                                            }
                                                            

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            if($valueKey=="category_id") {
                                                                                    $arrCustomTableKeyValues[$valueKey][] = (isset($arrMappingCategorySourceData[$result[$valueKey]])?$arrMappingCategorySourceData[$result[$valueKey]]:$result[$valueKey]);
                                                                            } else if($table=="oc_category_path" && $valueKey=="path_id") {
                                                                                    $arrCustomTableKeyValues[$valueKey][] = (isset($arrMappingCategorySourceData[$result[$valueKey]])?$arrMappingCategorySourceData[$result[$valueKey]]:$result[$valueKey]);
                                                                                    //$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                            }else {
                                                                                    $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                            }
                                                                    }
                                                            }
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
                                                                if($arrCustomTableKey[$table][0]=="category_id") {
                                                                            $tempResult[$arrCustomTableKey[$table][0]] = (isset($arrMappingSourceCategoryData[$tempResult["category_id"]])?$arrMappingSourceCategoryData[$tempResult["category_id"]]:$tempResult["category_id"]); // convert the data_source to partner product_id
                                                                    }
                                                            
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_category") {
								foreach ($results as $keyResult => $result) {
                                                                    unset($result["name"]);
                                                                    
                                                                    $arrDataSourceIDs = array();
                                                                    $arrDataSourceIDs[] = $result['category_id'];
                                                                    $compareResults = array();
                                                                    $compareTempResults = $this->model_tool_data_sync->getCategoriesNew(array(), $arrDataSourceIDs);
                                                                    //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                                    foreach ($compareTempResults as $tempResult) {
                                                                            $compareResults[$tempResult['data_source']] = $tempResult;
                                                                    }
                                                                    
                                                                    
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result['category_id']])) {
										// For the oc_product, will highlight the products has changes (comparing the date_modified field), EOL product (status field is 0), also the price changes will be highlighed too.
										if($result['date_modified']>$compareResults[$result['category_id']]['last_sync_date']) {
                                                                                        $strHighlightStatus = "Modified";
                                                                                } else {

                                                                                        $strHighlightStatus = "Modified"; // -
                                                                                }
									}else {
                                                                            $dateAdded = new DateTime($result['date_added']);
                                                                            $dateSynced = new DateTime($tableSyncDate);

                                                                            if($dateAdded < $dateSynced) {
                                                                                $strHighlightStatus = "Deleted by User";
                                                                            }
                                                                        }
                                                                        
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                $arrMapping["category_id"] = (isset($arrMappingCategorySourceData[$result["category_id"]])?$arrMappingCategorySourceData[$result["category_id"]]:"");
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									//$data['products'][] = $arrMapping;
									
									if($table=="oc_category") {
										$result["top"] = "0";
                                                                                $result["data_source"] = $result["category_id"];
                                                                                unset($result["category_id"]);
									}
                                                                        
                                                                        $result["category_id"] = $arrMapping["category_id"];
                                                                        
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $subTables = array(
                                                                "oc_category",
                                                                "oc_category_description",
                                                                "oc_category_filter",
                                                                "oc_category_path",
                                                                "oc_category_to_layout",
                                                                "oc_category_to_store"
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_category" => array("category_id"),
                                                                    "oc_category_description" => array("category_id", "language_id"),
                                                                    "oc_category_filter" => array("category_id", "filter_id"),
                                                                    "oc_category_path" => array("category_id", "path_id"),
                                                                    "oc_category_to_layout" => array("category_id", "store_id"),
                                                                    "oc_category_to_store" => array("category_id", "store_id")
                                                            );
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `category_id` IN ('".implode("','",$arrSelectedRows)."')");
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }
    //                                                        $subArrSyncData2 = array();
    //                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
    //                                                        
    //                                                        print_r($subArrSyncData);exit;

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            // Category ID Mapping - Start
                                                            $arrDataSourceIDs = array();
                                                            foreach ($results as $result) {
                                                                    $arrDataSourceIDs[] = $result["category_id"];
                                                                    if($subTable=="oc_product_related") {
                                                                        if(!in_array($result["category_id"], $arrDataSourceIDs)) {
                                                                            $arrDataSourceIDs[] = $result["path_id"];
                                                                        }
                                                                    }
                                                                    
                                                            }
                                                            $arrMappingSourceCategoryData = array();
                                                            $arrMappingCategorySourceData = array();
                                                            $arrTempMappingData = $this->model_tool_data_sync->getCategoryMappingsNew($arrDataSourceIDs);
                                                            foreach ($arrTempMappingData as $keyTemp => $keyResult) {
                                                                    $arrMappingSourceCategoryData[$keyResult["category_id"]] = $keyResult["data_source"];
                                                                    $arrMappingCategorySourceData[$keyResult["data_source"]] = $keyResult["category_id"];
                                                            }

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            if($valueKey=="category_id") {
                                                                                    $arrCustomTableKeyValues[$valueKey][] = (isset($arrMappingCategorySourceData[$result[$valueKey]])?$arrMappingCategorySourceData[$result[$valueKey]]:$result[$valueKey]);
                                                                            } else if($subTable=="oc_category_path" && $valueKey=="path_id") {
                                                                                    $arrCustomTableKeyValues[$valueKey][] = (isset($arrMappingCategorySourceData[$result[$valueKey]])?$arrMappingCategorySourceData[$result[$valueKey]]:$result[$valueKey]);
                                                                                    //$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                            }else {
                                                                                    $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                            }
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
                                                            //exit;

                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if($arrCustomTableKey[$subTable][0]=="category_id") {
                                                                            $tempResult[$arrCustomTableKey[$subTable][0]] = (isset($arrMappingSourceCategoryData[$tempResult["category_id"]])?$arrMappingSourceCategoryData[$tempResult["category_id"]]:$tempResult["category_id"]); // convert the data_source to partner product_id
                                                                    }
                                                                    if($subTable=="oc_category_path") {
                                                                        if($arrCustomTableKey[$subTable][1]=="path_id") {
                                                                                $tempResult[$arrCustomTableKey[$subTable][1]] = (isset($arrMappingSourceCategoryData[$tempResult["path_id"]])?$arrMappingSourceCategoryData[$tempResult["path_id"]]:$tempResult["path_id"]); // convert the data_source to partner product_id
                                                                        }
                                                                    }
                                                                
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                                                            if($subTable=="oc_category") {
                                                                    foreach ($results as $keyResult => $result) {
                                                                        unset($result["name"]);

                                                                        $arrDataSourceIDs = array();
                                                                        $arrDataSourceIDs[] = $result['category_id'];
                                                                        $compareResults = array();
                                                                        $compareTempResults = $this->model_tool_data_sync->getCategoriesNew(array(), $arrDataSourceIDs);
                                                                        //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                                        foreach ($compareTempResults as $tempResult) {
                                                                                $compareResults[$tempResult['data_source']] = $tempResult;
                                                                        }


                                                                            $result["last_sync_date"] = $nowDT;
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
                                                                            if(isset($compareResults[$result['category_id']])) {
                                                                                    // For the oc_product, will highlight the products has changes (comparing the date_modified field), EOL product (status field is 0), also the price changes will be highlighed too.
                                                                                    if($result['last_sync_date']>$compareResults[$result['category_id']]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {

                                                                                            $strHighlightStatus = "New"; // -
                                                                                    }
                                                                            }else {
                                                                                $strHighlightStatus =  "-";
                                                                            }
                                                                            
                                                                            $arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                    $arrMapping["category_id"] = (isset($arrMappingCategorySourceData[$result["category_id"]])?$arrMappingCategorySourceData[$result["category_id"]]:"");
                                                                                    $arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
                                                                            }
                                                                            
                                                                            $updateResult = array();
                                                                            
                                                                            $updateResult['parent_id'] = $this->model_tool_data_sync->getCategoryIDBySourceID($result["parent_id"]);
                                                                            
                                                                            //echo $strSyncType.$strHighlightStatus;exit;
                                                                            
                                                                            if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
                                                                                            $strCondition = " AND 1=0 ";
                                                                                            if(count($arrCustomTableKey[$subTable])=="1"){
                                                                                                    $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' ";
                                                                                            }
                                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                            
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($updateResult, $subTable, $strCondition);
                                                                            } else { // Update
                                                                                    if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                            $strCondition = " AND 1=0 ";
                                                                                            if(count($arrCustomTableKey[$subTable])=="1"){
                                                                                                    $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' ";
                                                                                            }
                                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($updateResult, $subTable, $strCondition);
                                                                                            //echo $boolStatus;echo "<br />";//exit;
                                                                                    }
                                                                            }
                                                                    }

                                                            }
                                                            
                                                            if($subTable=="oc_category_filter" || $subTable=="oc_category_path" || $subTable=="oc_category_to_store" || $subTable=="oc_category_description" || $subTable=="oc_category_to_layout") {
                                                                    foreach ($results as $keyResult => $result) {
                                                                        $result["last_sync_date"] = $nowDT;
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
                                                                            if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]])) {
                                                                                    if(($subTable=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }
                                                                            }else {
                                                                                if(($subTable=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
                                                                            $arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                if($subTable=="oc_category_description" && $valueColumn == "description") {
                                                                                    continue;
                                                                                }
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                    $arrMapping["category_id"] = (isset($arrMappingCategorySourceData[$result["category_id"]])?$arrMappingCategorySourceData[$result["category_id"]]:"");
                                                                                    if($subTable=="oc_category_path") {
                                                                                        $arrMapping["path_id"] = (isset($arrMappingCategorySourceData[$result["path_id"]])?$arrMappingCategorySourceData[$result["path_id"]]:"");
                                                                                    }
                                                                                    $arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]]."_".$result[$arrCustomTableKey[$subTable][1]];
                                                                            }
                                                                            
                                                                            $result["category_id"] = $arrMapping["category_id"];
                                                                            
                                                                            if($subTable=="oc_category_path") {
                                                                                $result["path_id"] = $arrMapping["path_id"];
                                                                            }
                                                                            
                                                                            if($subTable=="oc_category_description") {
                                                                                    unset($result["custom_title"]);
                                                                                    unset($result["description"]);
                                                                                    $result["name"] = str_replace(["atoz2u","atoz"], "", $result["name"]);
                                                                                    //$result["description"] = str_replace(["atoz2u","atoz"], "", $result["description"]);
                                                                                    $result["meta_title"] = str_replace(["atoz2u","atoz"], "", $result["meta_title"]);
                                                                            }
                                                                            //echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
                                                                            $boolStatus = false;
                                                                            if($result["category_id"]=="" || ($subTable=="oc_category_path" && $result["path_id"]=="")) {
                                                                                    
                                                                            }else {
                                                                                if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
                                                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
                                                                                        //echo $boolStatus;echo "<br />";//exit;
                                                                                } else { // Update
                                                                                        if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                                $strCondition = " AND 1=0 ";
                                                                                                if(count($arrCustomTableKey[$subTable])=="2"){
                                                                                                        $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' ";
                                                                                                }
                                                                                                //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                                $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
                                                                                                //echo $boolStatus;echo "<br />";//exit;
                                                                                        }
                                                                                }
                                                                            }
                                                                    }
                                                            }

                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `category_id` IN ('".implode("','",$arrSelectedRows)."')", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }


                                                    }
                                                    
                                                    $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                    
                                            }
							
						}
                                                
                                                else if($table=="oc_attribute") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_attribute" => array("attribute_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_attribute") {
								foreach ($results as $keyResult => $result) {
                                                                    unset($result["name"]);
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									//$data['products'][] = $arrMapping;
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                
                                                $subTables = array(
                                                                "oc_attribute_description",
                                                                "oc_attribute_group",
                                                                "oc_attribute_group_description"
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_attribute" => array("attribute_id", "attribute_group_id"),
                                                                    "oc_attribute_description" => array("attribute_id", "language_id"),
                                                                    "oc_attribute_group" => array("attribute_group_id"),
                                                                    "oc_attribute_group_description" => array("attribute_group_id", "language_id")
                                                            );
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    $strConditions = "";
                                                                    $strConditions .= " AND (";
                                                                            $arrOuterConditions = array();
                                                                            foreach($arrSelectedRows as $kvKey => $kvValue) {
                                                                                    $kvValue = explode("_",$kvValue);
                                                                                    //echo "<pre>";print_r($kvValue);echo "</pre>";exit;
                                                                                    foreach($kvValue as $innerKVKey => $innerKVVlue) {
                                                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;
                                                                                            $arrInnerConditions = array();
                                                                                            foreach($arrCustomTableKey["oc_attribute"] as $kKey => $kValue) {
                                                                                                if($subTable == "oc_attribute_description" && $kValue == "attribute_id") {
                                                                                                    $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                }else if(($subTable == "oc_attribute_group" || $subTable == "oc_attribute_group_description") && $kValue == "attribute_group_id") {
                                                                                                    $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                }
                                                                                                    
                                                                                            }
                                                                                            $arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
                                                                                            break; // just need loop 1 time only.*/
                                                                                    }
                                                                            }
                                                                            $strConditions .= implode(" OR ", $arrOuterConditions);
                                                                    $strConditions .= ")";
//                                                                    echo "<pre>";print_r($strConditions);echo "</pre>";//exit;
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }
    //                                                        $subArrSyncData2 = array();
    //                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
    //                                                        
    //                                                        print_r($subArrSyncData);exit;

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
                                                            //exit;

                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                                                            if($subTable=="oc_attribute_group") {
                                                                    foreach ($results as $keyResult => $result) {
                                                                        $result["last_sync_date"] = $nowDT;
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
                                                                            if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]])) {
                                                                                    if(($subTable=="oc_category"||$subTable=="oc_tax_class"||$subTable=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }
                                                                            }else {
                                                                                if(($subTable=="oc_category"||$subTable=="oc_tax_class"||$subTable=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }


                                                                            $arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                    $arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]];
                                                                            }
                                                                            $boolStatus = false;
                                                                            if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
                                                                                    $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
                                                                                    //echo $boolStatus;echo "<br />";//exit;
                                                                            } else { // Update
                                                                                    if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                            $strCondition = " AND 1=0 ";
                                                                                            if(count($arrCustomTableKey[$subTable])=="1"){
                                                                                                    $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' ";
                                                                                            }
                                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
                                                                                            //echo $boolStatus;echo "<br />";//exit;
                                                                                    }
                                                                            }
                                                                    }

                                                            } else if($subTable=="oc_attribute_description" || $subTable=="oc_attribute_group_description") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]])) {
										if(($subTable=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($subTable=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]]."_".$result[$arrCustomTableKey[$subTable][1]];
									}
									//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$subTable])=="2"){
												$strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
							}

                                                            if($strSyncType=="sync_selected") {
                                                                    $strConditions = "";
                                                                    $strConditions .= " AND (";
                                                                            $arrOuterConditions = array();
                                                                            foreach($arrSelectedRows as $kvKey => $kvValue) {
                                                                                    $kvValue = explode("_",$kvValue);
                                                                                    //echo "<pre>";print_r($kvValue);echo "</pre>";exit;
                                                                                    foreach($kvValue as $innerKVKey => $innerKVVlue) {
                                                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;
                                                                                            $arrInnerConditions = array();
                                                                                            foreach($arrCustomTableKey["oc_attribute"] as $kKey => $kValue) {
                                                                                                if($subTable == "oc_attribute_description" && $kValue == "attribute_id") {
                                                                                                    $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                }else if(($subTable == "oc_attribute_group" || $subTable == "oc_attribute_group_description") && $kValue == "attribute_group_id") {
                                                                                                    $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                }
                                                                                                    
                                                                                            }
                                                                                            $arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
                                                                                            break; // just need loop 1 time only.*/
                                                                                    }
                                                                            }
                                                                            $strConditions .= implode(" OR ", $arrOuterConditions);
                                                                    $strConditions .= ")";
                                                                    //echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions, $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }


                                                    }
                                                    
                                                    $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                    
                                            }
							
						}
                                                
                                                else if($table=="oc_filter") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_filter" => array("filter_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_filter") {
								foreach ($results as $keyResult => $result) {
                                                                    unset($result["name"]);
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $subTables = array(
                                                                "oc_filter_description",
                                                                "oc_filter_group",
                                                                "oc_filter_group_description"
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }
							
							$arrCustomTableKey = array(
								"oc_filter" => array("filter_id", "filter_group_id"),
								"oc_filter_description" =>  array("filter_id", "language_id"),
								"oc_filter_group" => array("filter_group_id"),
								"oc_filter_group_description" => array("filter_group_id", "language_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
                                                                    $strConditions .= " AND (";
                                                                            $arrOuterConditions = array();
                                                                            foreach($arrSelectedRows as $kvKey => $kvValue) {
                                                                                    $kvValue = explode("_",$kvValue);
                                                                                    //echo "<pre>";print_r($kvValue);echo "</pre>";exit;
                                                                                    foreach($kvValue as $innerKVKey => $innerKVVlue) {
                                                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;
                                                                                            $arrInnerConditions = array();
                                                                                            foreach($arrCustomTableKey["oc_filter"] as $kKey => $kValue) {
                                                                                                if($subTable == "oc_filter_description" && $kValue == "filter_id") {
                                                                                                    $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                }else if(($subTable == "oc_filter_group" || $subTable == "oc_filter_group_description") && $kValue == "filter_group_id") {
                                                                                                    $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                }
                                                                                                    
                                                                                            }
                                                                                            $arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
                                                                                            break; // just need loop 1 time only.*/
                                                                                    }
                                                                            }
                                                                            $strConditions .= implode(" OR ", $arrOuterConditions);
                                                                    $strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
							} else {
								$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
							}
//                                                        $subArrSyncData2 = array();
//                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
//                                                        
//                                                        print_r($subArrSyncData);exit;
                                                        
                                                    while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$subTable])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$subTable])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$subTable])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$subTable])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$subTable])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($subTable=="oc_filter_group") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]])) {
										if(($subTable=="oc_category"||$subTable=="oc_tax_class"||$subTable=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($subTable=="oc_category"||$subTable=="oc_tax_class"||$subTable=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]];
									}
									//$data['products'][] = $arrMapping;
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$subTable])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							} else if($subTable=="oc_filter_description" || $subTable=="oc_filter_group_description") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]])) {
										if(($subTable=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($subTable=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]]."_".$result[$arrCustomTableKey[$subTable][1]];
									}
									//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$subTable])=="2"){
												$strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
                                                                    $strConditions .= " AND (";
                                                                            $arrOuterConditions = array();
                                                                            foreach($arrSelectedRows as $kvKey => $kvValue) {
                                                                                    $kvValue = explode("_",$kvValue);
                                                                                    //echo "<pre>";print_r($kvValue);echo "</pre>";exit;
                                                                                    foreach($kvValue as $innerKVKey => $innerKVVlue) {
                                                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;
                                                                                            $arrInnerConditions = array();
                                                                                            foreach($arrCustomTableKey["oc_filter"] as $kKey => $kValue) {
                                                                                                if($subTable == "oc_filter_description" && $kValue == "filter_id") {
                                                                                                    $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                }else if(($subTable == "oc_filter_group" || $subTable == "oc_filter_group_description") && $kValue == "filter_group_id") {
                                                                                                    $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                }
                                                                                                    
                                                                                            }
                                                                                            $arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
                                                                                            break; // just need loop 1 time only.*/
                                                                                    }
                                                                            }
                                                                            $strConditions .= implode(" OR ", $arrOuterConditions);
                                                                    $strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions, $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
								//$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
							} else {
                                                                $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
								//$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                
                                            }
							
						}
                                                
                                                else if($table=="oc_additional_product") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_additional_product" => array("additional_product_id", "language_id"), // new table
								"oc_additional_product_value" => array("additional_product_value_id", "additional_product_id", "language_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_additional_product") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]][$result[$arrCustomTableKey[$table][1]]])) {
										if(($table=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]][$result[$arrCustomTableKey[$table][1]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]]."_".$result[$arrCustomTableKey[$table][1]];
									}
									//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="2"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' AND `".$arrCustomTableKey[$table][1]."`='".$arrMapping[$arrCustomTableKey[$table][1]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                
                                                $subTables = array(
                                                                "oc_additional_product_value"
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_additional_product_value" => array("additional_product_value_id", "additional_product_id", "language_id")
                                                            );
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `additional_product_id` IN ('".implode("','",$arrSelectedRows)."')");
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }
    //                                                        $subArrSyncData2 = array();
    //                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
    //                                                        
    //                                                        print_r($subArrSyncData);exit;

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
                                                            //exit;

                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                                                            if($subTable=="oc_additional_product_value") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]][$result[$arrCustomTableKey[$subTable][2]]])) {
										if(($subTable=="oc_zone_to_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]][$result[$arrCustomTableKey[$subTable][2]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($subTable=="oc_zone_to_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]]."_".$result[$arrCustomTableKey[$subTable][1]]."_".$result[$arrCustomTableKey[$subTable][2]];
									}
									//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$subTable])=="3"){
												$strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' AND `".$arrCustomTableKey[$subTable][2]."`='".$arrMapping[$arrCustomTableKey[$subTable][2]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
							}

                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `additional_product_id` IN ('".implode("','",$arrSelectedRows)."')", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }


                                                    }
                                                    
                                                    $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                    
                                            }
							
						}
                                                
                                                else if($table=="oc_customer_group") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_customer_group" => array("customer_group_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_customer_group") {
								foreach ($results as $keyResult => $result) {
                                                                    unset($result["name"]);
                                                                    if($result['customer_group_id'] != 8) {
                                                                        continue;
                                                                    }
                                                                    
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									//$data['products'][] = $arrMapping;
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                
                                                $subTables = array(
                                                                "oc_customer_group_description"
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_customer_group_description" => array("customer_group_id", "language_id")
                                                            );
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `customer_group_id` IN ('".implode("','",$arrSelectedRows)."')");
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }
    //                                                        $subArrSyncData2 = array();
    //                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
    //                                                        
    //                                                        print_r($subArrSyncData);exit;

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
                                                            //exit;

                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                                                            if($subTable=="oc_customer_group_description") {
								foreach ($results as $keyResult => $result) {
                                                                    if($result['customer_group_id'] != 8) {
                                                                        continue;
                                                                    }
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]])) {
										if(($subTable=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($subTable=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]]."_".$result[$arrCustomTableKey[$subTable][1]];
									}
									//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$subTable])=="2"){
												$strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
							}

                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `customer_group_id` IN ('".implode("','",$arrSelectedRows)."')", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }


                                                    }
                                                    
                                                    $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                    
                                            }
							
						}
                                                
                                                else if($table=="oc_tax_rate") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
                                                                "oc_tax_rate" => array("tax_rate_id")
								
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_tax_rate") {
								foreach ($results as $keyResult => $result) {
                                                                    
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
                                                                                    if(($table=="oc_tax_class" || $table=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }
                                                                            }else {
                                                                                if(($table=="oc_tax_class" || $table=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									//$data['products'][] = $arrMapping;
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                
                                                $subTables = array(
                                                                "oc_tax_rule",
                                                                "oc_tax_class",
                                                                "oc_tax_rate_to_customer_group"
                                                                
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_tax_rule" =>  array("tax_rule_id"),
                                                                    "oc_tax_rate" => array("tax_rate_id"),
                                                                    "oc_tax_class" =>  array("tax_class_id"),
                                                                    "oc_tax_rate_to_customer_group" => array("tax_rate_id", "customer_group_id")

                                                            );
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    $strConditions = "";
                                                                     if($subTable != "oc_tax_class") {                           
                                                                        $strConditions .= " AND (";
                                                                                $arrOuterConditions = array();
                                                                                foreach($arrSelectedRows as $kvKey => $kvValue) {
                                                                                        $kvValue = explode("_",$kvValue);
                                                                                        //echo "<pre>";print_r($kvValue);echo "</pre>";exit;
                                                                                        foreach($kvValue as $innerKVKey => $innerKVVlue) {
                                                                                                //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;
                                                                                                $arrInnerConditions = array();
                                                                                                foreach($arrCustomTableKey['oc_tax_rate'] as $kKey => $kValue) {
                                                                                                    if(($subTable == "oc_tax_rule" || $subTable == "oc_tax_rate_to_customer_group") && $kValue == "tax_rate_id") {
                                                                                                        $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                    }

                                                                                                }
                                                                                                $arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
                                                                                                break; // just need loop 1 time only.*/
                                                                                        }
                                                                                }
                                                                                $strConditions .= implode(" OR ", $arrOuterConditions);
                                                                        $strConditions .= ")";
                                                                     }
                                                                    //echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }
    //                                                        $subArrSyncData2 = array();
    //                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
    //                                                        
    //                                                        print_r($subArrSyncData);exit;

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
                                                            //exit;

                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                                                            if($subTable=="oc_tax_class" || $subTable == "oc_tax_rule") {
                                                                
                                                                    foreach ($results as $keyResult => $result) {
                                                                        $result["last_sync_date"] = $nowDT;
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
                                                                            if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]])) {
                                                                                    if(($subTable=="oc_tax_class" || $subTable=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }
                                                                            }else {
                                                                                if(($subTable=="oc_tax_class" || $subTable=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }

                                                                            $arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                    $arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]];
                                                                            }

                                                                            $boolStatus = false;
                                                                            if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
                                                                                $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
                                                                                    //echo $boolStatus;echo "<br />";//exit;
                                                                            } else { // Update
                                                                                    if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                            $strCondition = " AND 1=0 ";
                                                                                            if(count($arrCustomTableKey[$subTable])=="1"){
                                                                                                    $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' ";
                                                                                            }
                                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
                                                                                            //echo $boolStatus;echo "<br />";//exit;
                                                                                    }
                                                                            }
                                                                            
//                                                                            if($subTable == "oc_tax_rule") {
//                                                                                // Custom save to oc_tax_class table
//                                                                                $arrSyncDataClass = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", "oc_tax_class", "", " AND `tax_class_id` = '".$result['tax_class_id']."' ");
//                                                                                if($arrSyncDataClass == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncDataClass == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
//                                                                                if(isset($arrSyncDataClass["tables"]["oc_tax_class"][0])&&is_array($arrSyncDataClass["tables"]["oc_tax_class"][0])&&count($arrSyncDataClass["tables"]["oc_tax_class"][0])>0) {
//                                                                                        $arrQuickClass = $arrSyncDataClass["tables"]["oc_tax_class"][0];
//                                                                                        $arrQuickClass["tax_class_id"] = $result['tax_class_id'];
//
//                                                                                        //echo "<pre>";print_r($arrQuickClass);echo "</pre>";exit;
//                                                                                        $this->model_tool_data_sync->ManualDeleteRecord("oc_tax_class", " AND `tax_class_id`='".$result['tax_class_id']."'");
//                                                                                        $boolDescStatus = $this->model_tool_data_sync->ManualInsertRecord($arrQuickClass, "oc_tax_class");
//                                                                                }
//                                                                            }
                                                                            
                                                                    }

                                                            } else if($subTable=="oc_tax_rate_to_customer_group") {
                                                                    foreach ($results as $keyResult => $result) {
                                                                        $result["last_sync_date"] = $nowDT;
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
                                                                            if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]])) {
                                                                                    if(($subTable=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }
                                                                            }else {
                                                                                if(($subTable=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
                                                                            $arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                    $arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]]."_".$result[$arrCustomTableKey[$subTable][1]];
                                                                            }
                                                                            //echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
                                                                            $boolStatus = false;
                                                                            if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
                                                                                    $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
                                                                                    //echo $boolStatus;echo "<br />";//exit;
                                                                            } else { // Update
                                                                                    if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                            $strCondition = " AND 1=0 ";
                                                                                            if(count($arrCustomTableKey[$subTable])=="2"){
                                                                                                    $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' ";
                                                                                            }
                                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
                                                                                            //echo $boolStatus;echo "<br />";//exit;
                                                                                    }
                                                                            }
                                                                    }
                                                            }

                                                            if($strSyncType=="sync_selected") {
                                                                    $strConditions = "";
                                                                        if($subTable != "oc_tax_class") {                           
                                                                        $strConditions .= " AND (";
                                                                                $arrOuterConditions = array();
                                                                                foreach($arrSelectedRows as $kvKey => $kvValue) {
                                                                                        $kvValue = explode("_",$kvValue);
                                                                                        //echo "<pre>";print_r($kvValue);echo "</pre>";exit;
                                                                                        foreach($kvValue as $innerKVKey => $innerKVVlue) {
                                                                                                //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;
                                                                                                $arrInnerConditions = array();
                                                                                                foreach($arrCustomTableKey['oc_tax_rate'] as $kKey => $kValue) {
                                                                                                    if(($subTable == "oc_tax_rule" || $subTable == "oc_tax_rate_to_customer_group") && $kValue == "tax_rate_id") {
                                                                                                        $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                    }

                                                                                                }
                                                                                                $arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
                                                                                                break; // just need loop 1 time only.*/
                                                                                        }
                                                                                }
                                                                                $strConditions .= implode(" OR ", $arrOuterConditions);
                                                                        $strConditions .= ")";
                                                                     }
                                                                    //echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions, $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }


                                                    }
                                                    
                                                    $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                    
                                                }
							
						}
                                                
                                                else if($table=="oc_geo_zone") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_geo_zone" => array("geo_zone_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_geo_zone") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									//$data['products'][] = $arrMapping;
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
							
						}
                                                
                                                else if($table=="oc_zone") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_zone" => array("zone_id"), 
								"oc_zone_to_geo_zone" => array("zone_to_geo_zone_id", "country_id", "zone_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_zone") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									//
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                
                                                $subTables = array(
                                                                "oc_zone_to_geo_zone"
                                                                
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_zone" => array("zone_id", "country_id"), 
                                                                    "oc_zone_to_geo_zone" => array("zone_to_geo_zone_id")
                                                            );
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    $strConditions = "";
                                                                    $strConditions .= " AND (";
                                                                            $arrOuterConditions = array();
                                                                            foreach($arrSelectedRows as $kvKey => $kvValue) {
                                                                                    $kvValue = explode("_",$kvValue);
                                                                                    //echo "<pre>";print_r($kvValue);echo "</pre>";exit;
                                                                                    foreach($kvValue as $innerKVKey => $innerKVVlue) {
                                                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;
                                                                                            $arrInnerConditions = array();
                                                                                            foreach($arrCustomTableKey["oc_zone"] as $kKey => $kValue) {
                                                                                                if($subTable == "oc_zone_to_geo_zone" && $kValue == "zone_id") {
                                                                                                    $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                }
                                                                                                    
                                                                                            }
                                                                                            $arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
                                                                                            break; // just need loop 1 time only.*/
                                                                                    }
                                                                            }
                                                                            $strConditions .= implode(" OR ", $arrOuterConditions);
                                                                    $strConditions .= ")";
                                                                    //echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }
    //                                                        $subArrSyncData2 = array();
    //                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
    //                                                        
    //                                                        print_r($subArrSyncData);exit;

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
                                                            //exit;

                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;
                                                            if($subTable=="oc_zone_to_geo_zone") {
                                                                    foreach ($results as $keyResult => $result) {
                                                                        $result["last_sync_date"] = $nowDT;
                                                                            $arrMapping = array();
                                                                            $strHighlightStatus = "New";
                                                                            if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]])) {
                                                                                    if(($subTable=="oc_category"||$subTable=="oc_tax_class"||$subTable=="oc_geo_zone" || $subTable=="oc_zone_to_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]]['last_sync_date']) {
                                                                                            $strHighlightStatus = "Modified";
                                                                                    } else {
                                                                                            $strHighlightStatus = "Modified"; // -
                                                                                    }
                                                                            }else {
                                                                                if(($subTable=="oc_category"||$subTable=="oc_tax_class"||$subTable=="oc_geo_zone" || $subTable=="oc_zone_to_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }

                                                                            $arrMapping["sync_status"] = $strHighlightStatus;
                                                                            foreach($arrSyncColumns as $keyColumn => $valueColumn) {
                                                                                    $arrMapping[$valueColumn] = $result[$valueColumn];
                                                                                    $arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]];
                                                                            }
                                                                            $boolStatus = false;
                                                                            if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
                                                                                    $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
                                                                                    //echo $boolStatus;echo "<br />";//exit;
                                                                            } else { // Update
                                                                                    if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
                                                                                            $strCondition = " AND 1=0 ";
                                                                                            if(count($arrCustomTableKey[$subTable])=="1"){
                                                                                                    $strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' ";
                                                                                            }
                                                                                            //echo "<pre>";print_r($result);echo "</pre>";exit;
                                                                                            $boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
                                                                                            //echo $boolStatus;echo "<br />";//exit;
                                                                                    }
                                                                            }
                                                                    }

                                                            }

                                                            if($strSyncType=="sync_selected") {
                                                                    $strConditions = "";
                                                                    $strConditions .= " AND (";
                                                                            $arrOuterConditions = array();
                                                                            foreach($arrSelectedRows as $kvKey => $kvValue) {
                                                                                    $kvValue = explode("_",$kvValue);
                                                                                    //echo "<pre>";print_r($kvValue);echo "</pre>";exit;
                                                                                    foreach($kvValue as $innerKVKey => $innerKVVlue) {
                                                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;
                                                                                            $arrInnerConditions = array();
                                                                                            foreach($arrCustomTableKey["oc_zone"] as $kKey => $kValue) {
                                                                                                if($subTable == "oc_zone_to_geo_zone" && $kValue == "zone_id") {
                                                                                                    $arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
                                                                                                }
                                                                                                    
                                                                                            }
                                                                                            $arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
                                                                                            break; // just need loop 1 time only.*/
                                                                                    }
                                                                            }
                                                                            $strConditions .= implode(" OR ", $arrOuterConditions);
                                                                    $strConditions .= ")";
                                                                    //echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions, $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }


                                                    }
                                                    
                                                    $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                    
                                            }
							
						}
                                                
                                                else if($table=="oc_country") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_country" => array("country_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_country") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
							
						}
                                                
                                                else if($table=="oc_zip_code") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_zip_code" => array("zip_code_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_zip_code") {
								foreach ($results as $keyResult => $result) {
                                                                    unset($result["name"]);
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
							
						}
                                                
                                                else if($table=="oc_weight_class") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_weight_class" => array("weight_class_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_weight_class") {
								foreach ($results as $keyResult => $result) {
                                                                    unset($result["name"]);
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                
                                                $subTables = array(
                                                                "oc_weight_class_description"
                                                                
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_weight_class_description" => array("weight_class_id", "language_id")
                                                            );
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `weight_class_id` IN ('".implode("','",$arrSelectedRows)."')");
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }
    //                                                        $subArrSyncData2 = array();
    //                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
    //                                                        
    //                                                        print_r($subArrSyncData);exit;

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
                                                            //exit;

                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                                                            if($subTable=="oc_weight_class_description") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]])) {
										if(($subTable=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($subTable=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]]."_".$result[$arrCustomTableKey[$subTable][1]];
									}
									//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$subTable])=="2"){
												$strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
							}

                                                            if($strSyncType=="sync_selected") {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `weight_class_id` IN ('".implode("','",$arrSelectedRows)."')", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }


                                                    }
                                                    
                                                    $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                    
                                            }
							
						}
                                                
                                                else if($table=="oc_length_class") {
							
							$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, "");
							//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
							//exit;
							
							$arrCustomTableKey = array(
								"oc_length_class" => array("length_class_id")
							);
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
							
							//echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
							if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
								$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
//                                                        $arrSyncData2 = array();
//                                                        $arrSyncData2 = array_merge($arrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']));
//                                                        
//                                                        print_r($arrSyncData);exit;
                                                        
                                                    while($arrSyncData != "done") {if($arrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                        
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>";print_r($results);echo "</pre>";exit;
							
							$arrCustomTableKeyValues = array();
							foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
								foreach ($results as $result) {
									$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
								}
							}
							//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
							//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
							//exit;
							
							$compareResults = array();
							$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
							//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
							foreach ($compareTempResults as $tempResult) {
								if(count($arrCustomTableKey[$table])=="5"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="4"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="3"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="2"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
								} else if(count($arrCustomTableKey[$table])=="1"){
									$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
								}
							}
							//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
							
							if($table=="oc_length_class") {
								foreach ($results as $keyResult => $result) {
                                                                    unset($result["name"]);
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
										if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($table=="oc_category"||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($tableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
									}
									
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$table])=="1"){
												$strCondition = " AND `".$arrCustomTableKey[$table][0]."`='".$arrMapping[$arrCustomTableKey[$table][0]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
								
							}
                                                        
                                                        if($strSyncType=="sync_selected") {
								$strConditions = "";
								$strConditions .= " AND (";
									$arrOuterConditions = array();
									foreach($arrSelectedRows as $kvKey => $kvValue) {
										$kvValue = explode("_",$kvValue);
										//echo "<pre>";print_r($kvValue);echo "</pre>";exit;
										foreach($kvValue as $innerKVKey => $innerKVVlue) {
											//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
											$arrInnerConditions = array();
											foreach($arrCustomTableKey[$table] as $kKey => $kValue) {
												$arrInnerConditions[] = "`".$kValue."`='".$kvValue[$kKey]."'";
											}
											$arrOuterConditions[] = "(".implode(" AND ", $arrInnerConditions).")";
											break; // just need loop 1 time only.*/
										}
									}
									$strConditions .= implode(" OR ", $arrOuterConditions);
								$strConditions .= ")";
								//echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions, $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", $strConditions);
							} else {
                                                                $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
								//$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							}
                                                        
                                                        
                                                }
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                
                                                $subTables = array(
                                                                "oc_length_class_description"
                                                                
                                                        );
                                                        
                                                        foreach($subTables AS $keySubTable => $subTable) {
                                                            $arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $subTable, "");
                                                            //echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
                                                            //exit;
                                                            
                                                            if($subTable!= "") {
                                                                $subTableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($subTable)[0]['sync_date'];
                                                            }

                                                            $arrCustomTableKey = array(
                                                                    "oc_length_class_description" => array("length_class_id", "language_id")
                                                            );
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";exit;

                                                            //echo "<pre>";print_r($arrSelectedRows);echo "</pre>";exit;
                                                            if($strSyncType=="sync_selected") {
                                                                    //echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `length_class_id` IN ('".implode("','",$arrSelectedRows)."')");
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }
    //                                                        $subArrSyncData2 = array();
    //                                                        $subArrSyncData2 = array_merge($subArrSyncData,$this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']));
    //                                                        
    //                                                        print_r($subArrSyncData);exit;

                                                        while($subArrSyncData != "done") {if($subArrSyncData == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                                                            $results = (isset($subArrSyncData["tables"][$subTable])?$subArrSyncData["tables"][$subTable]:"");
                                                            //echo "<pre>";print_r($results);echo "</pre>";exit;

                                                            $arrCustomTableKeyValues = array();
                                                            foreach($arrCustomTableKey[$subTable] as $keyKey => $valueKey) {
                                                                    foreach ($results as $result) {
                                                                            $arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($arrCustomTableKey[$subTable]);echo "</pre>";
                                                            //echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
                                                            //exit;

                                                            $compareResults = array();
                                                            $compareTempResults = $this->model_tool_data_sync->getCustomTableNew($subTable, $arrCustomTableKey[$subTable], $arrCustomTableKeyValues);
                                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                                            foreach ($compareTempResults as $tempResult) {
                                                                    if(count($arrCustomTableKey[$subTable])=="5"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]][$tempResult[$arrCustomTableKey[$subTable][4]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="4"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]][$tempResult[$arrCustomTableKey[$subTable][3]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="3"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]][$tempResult[$arrCustomTableKey[$subTable][2]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="2"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]][$tempResult[$arrCustomTableKey[$subTable][1]]] = $tempResult;
                                                                    } else if(count($arrCustomTableKey[$subTable])=="1"){
                                                                            $compareResults[$tempResult[$arrCustomTableKey[$subTable][0]]] = $tempResult;
                                                                    }
                                                            }
                                                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                                                            if($subTable=="oc_length_class_description") {
								foreach ($results as $keyResult => $result) {
                                                                    $result["last_sync_date"] = $nowDT;
									$arrMapping = array();
									$strHighlightStatus = "New";
									if(isset($compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]])) {
										if(($subTable=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$subTable][0]]][$result[$arrCustomTableKey[$subTable][1]]]['last_sync_date']) {
											$strHighlightStatus = "Modified";
										} else {
											$strHighlightStatus = "Modified"; // -
										}
									}else {
                                                                                if(($subTable=="oc_tax_rate")) {
                                                                                        $dateAdded = new DateTime($result['date_added']);
                                                                                        $dateSynced = new DateTime($subTableSyncDate);

                                                                                        if($dateAdded < $dateSynced) {
                                                                                            $strHighlightStatus = "Deleted by User";
                                                                                        }
                                                                                }
                                                                            }
									$arrMapping["sync_status"] = $strHighlightStatus;
									foreach($arrSyncColumns as $keyColumn => $valueColumn) {
										$arrMapping[$valueColumn] = $result[$valueColumn];
										$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$subTable][0]]."_".$result[$arrCustomTableKey[$subTable][1]];
									}
									//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
									$boolStatus = false;
									if(($strSyncType=="sync_selected" && ($strHighlightStatus=="New"||$strHighlightStatus=="Deleted by User")) || ($strHighlightStatus=="Deleted by User" && strpos($strSyncType, "_rel")!==false) || ($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false)) { // Insert
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $subTable);
										//echo $boolStatus;echo "<br />";//exit;
									} else { // Update
										if(($strSyncType=="sync_selected" && $strHighlightStatus=="Modified") || ($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false)) {
											$strCondition = " AND 1=0 ";
											if(count($arrCustomTableKey[$subTable])=="2"){
												$strCondition = " AND `".$arrCustomTableKey[$subTable][0]."`='".$arrMapping[$arrCustomTableKey[$subTable][0]]."' AND `".$arrCustomTableKey[$subTable][1]."`='".$arrMapping[$arrCustomTableKey[$subTable][1]]."' ";
											}
											//echo "<pre>";print_r($result);echo "</pre>";exit;
											$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $subTable, $strCondition);
											//echo $boolStatus;echo "<br />";//exit;
										}
									}
								}
							}

                                                            if($strSyncType=="sync_selected") {
                                                                    //echo "<pre>";print_r($strConditions);echo "</pre>";exit;
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", " AND `length_class_id` IN ('".implode("','",$arrSelectedRows)."')", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", $strConditions);
                                                            } else {
                                                                    $subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "", $subArrSyncData['start_table'], $subArrSyncData['start_data'], $subArrSyncData['start_data_from']);
                                                                    //$subArrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $subTable, "", "");
                                                            }


                                                    }
                                                    
                                                    $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$subTable."'");
                                                        $arrHistory = array();
                                                        $arrHistory["sync_table"] = $subTable;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                        $arrHistory["sync_date"] = $nowDT;
                                                        $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
                                                    
                                            }
							
						}
                                                
                                                $this->model_tool_data_sync->ManualDeleteRecord("oc_sync_history", " AND `sync_table`='".$table."'");
                                                $arrHistory = array();
                                                $arrHistory["sync_table"] = $table;
//                                                        $arrHistory["sync_unique_id"] = $result["data_source"];
                                                $arrHistory["sync_date"] = $nowDT;
                                                $boolStatus = $this->model_tool_data_sync->ManualInsertRecord($arrHistory, "oc_sync_history");
					// exit;	
					}
					/* else if($this->request->post['sync_type']!="") {
						
						$strSyncType = $this->request->post['sync_type'];
						if($table=="oc_product") {
							
							$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", "");
							$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:"");
							//echo "<pre>xx";print_r($results);echo "</pre>";
							//exit;
							
							foreach ($results as $keyresult => $result) {
								$arrDataSourceIDs = array();
								$arrDataSourceIDs[] = $result['product_id'];
								$compareResults = array();
								$compareTempResults = $this->model_tool_data_sync->getProductsNew(array(), $arrDataSourceIDs);
								//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
								foreach ($compareTempResults as $tempResult) {
									$compareResults[$tempResult['data_source']] = $tempResult;
								}
								//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
								
								$strHighlightStatus = "New";
								if(isset($compareResults[$result['product_id']])) {
									// For the oc_product, will highlight the products has changes (comparing the date_modified field), EOL product (status field is 0), also the price changes will be highlighed too.
									if($result['status']=="0") {
										$strHighlightStatus = "End Of Life";
									} else if($result['date_modified']!=$compareResults[$result['product_id']]['date_modified']) {
										$strHighlightStatus = "Modified";
									} else if($result['price']!=$compareResults[$result['product_id']]['price']) {
										$strHighlightStatus = "Modified";
									} else {
										$strHighlightStatus = "-";
									}
								}
								
								unset($result["matching_code"]);
								unset($result["in_stock_status_id"]);
								unset($result["new_pro"]);
								unset($result["erp_status"]);
								unset($result["maxquantity"]);
								$result["data_source"] = $result["product_id"];
								unset($result["product_id"]);
								//echo "<pre>";print_r($result);echo "</pre>";exit;
								//echo $strHighlightStatus;echo "<br />";
								if($strHighlightStatus=="New") { // Insert
									if(($strHighlightStatus=="New" && strpos($strSyncType, "_new")!==false) || ($result['status']=="0" && strpos($strSyncType, "_eol")!==false)) {
										$boolStatus = $this->model_tool_data_sync->ManualInsertRecord($result, $table);
									}
									//echo $boolStatus;echo "<br />";//exit;
								} else { // Update
									if(($strHighlightStatus=="Modified" && strpos($strSyncType, "_mod")!==false) || ($strHighlightStatus=="End Of Life" && strpos($strSyncType, "_eol")!==false)) {
										$boolStatus = $this->model_tool_data_sync->ManualUpdateRecordByConditions($result, $table, " AND `data_source`='".$result["data_source"]."' ");
									} 
									//echo $boolStatus;echo "<br />";//exit;
								}
							}
							
						}  else if($table!="") {
							
							
							
						}
					
					}*/
				}
				
			}
			//sleep(2);
			$this->session->data['end_timer'] = date("Y-m-d H:i:s");
			$this->session->data['success'] = "Data sync has been successfully completed.";
			include(dirname(dirname(dirname(__DIR__))) . "/cron/check_duplicate_product.php");
			//exit;
			
                        if($this->config->get('partner_sync_all') == 1) {
                            $syncall = array();
                            $syncall['partner_sync_all'] = '0';
                            $this->load->model('setting/setting');
                            $this->model_setting_setting->editSetting('partner_sync', $syncall);
                        }
                        
			if($syncAllNew == "") {
				$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token']."&table=".$table, $this->ssl));
			} else {
				if(isset($this->request->post['auto_sync_all']) &&  $this->request->post['auto_sync_all'] != "" ) {
                                    echo "successfully sync all";exit;
                                }else {
                                    $this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));
                                }
				
			}
		}
		$this->getList();
		
	}

	public function settings() {
		$this->load->language('tool/data_sync');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('tool/data_sync');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateSettingsForm())) {
			/*if (!isset($this->request->post['data_sync_settings_use_export_cache'])) {
				$this->request->post['data_sync_settings_use_export_cache'] = '0';
			}
			if (!isset($this->request->post['data_sync_settings_use_import_cache'])) {
				$this->request->post['data_sync_settings_use_import_cache'] = '0';
			}*/
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('data_sync', $this->request->post);
			$this->session->data['success'] = "Settings has been successfully saved.";
			$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));
		}
		$this->getList();
	}
	
	protected function getList() {
            
                $data['partner_sync_all'] = $this->config->get('partner_sync_all');
                if($data['partner_sync_all'] == 1) {
                    $data['partner_sync_all_message'] = $this->language->get('partner_sync_all_message');
                }
		
		$table = "oc_product";
		if (isset($this->request->get['table'])) {
			$table = $this->request->get['table'];
		} else {
			$table = "";
		}
		$arrSyncTables = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_list_tables");
//echo "<pre>";print_r($arrSyncTables);echo "</pre>";
		$data["sync_tables"] = $arrSyncTables;
		$data["selected_table"] = $table;
                
                if($table!= "") {
                    $tableSyncDate = $this->model_tool_data_sync->getSyncHistoryDate($table)[0]['sync_date'];
                }
                
                $nowDT = (new DateTime('NOW'))->format('Y-m-d H:i:s');
		
		//echo $table;exit;
		
		$url = '';
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], $this->ssl)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl)
		);
		
		$this->document->addStyle('view/stylesheet/data_sync.css');
		$filter_data = array();
		$data['products'] = array();
		$data['sync_histories'] = array();
                
                
//                $generated = false;
                $tempMode = "";
                $firstGen = false;
                if (isset($this->request->get['firstGen'])) {
                    if($this->request->get['firstGen'] != "") {
                        $firstGen = true;
                    }
                }
                $data["selected_tempMode"] = "";
                if (isset($this->request->get['tempMode'])) {
			$tempMode = $this->request->get['tempMode'];
                        $data["selected_tempMode"] = $tempMode;
                        
//                        $lastTempTime = strtotime($this->config->get('temp_sync_time'));
//                        
//                        $minsTimsDiff = (time() - $lastTempTime)/60;
//                        
//                        if($this->config->get('temp_sync_mode') == $tempMode) {
//                            $generated = true;
//                            if($minsTimsDiff > 30) {
//                                $generated = false;
//                            }
//                        }else {
//                            $generated = false;
//                        }
                        
		}
                
                
                 if($table=="oc_product" && $tempMode != "" && $firstGen) {//$generated == false
                     
                     $this->model_tool_data_sync->clearTable('temp_oc_product');
                     $this->model_tool_data_sync->clearTable('temp_oc_product_description');
                     
			$this->load->model('tool/image');
			//$product_total = $this->model_tool_data_sync->getTotalProductsNew($filter_data);
			//$results = $this->model_tool_data_sync->getProductsNew($filter_data);
			//echo "<pre>";print_r($results);echo "</pre>";
			
			
			$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table);
                        
                        while($arrSyncData != "done") {
                        
                            if($arrSyncData == 'Running') {$this->session->data['runninghalt'] = "The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncData == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}

                            
                            //echo "<pre>";print_r($product_total);echo "</pre>";
                            $results = (isset($arrSyncData["tables"]["oc_product"])?$arrSyncData["tables"]["oc_product"]:array());
                            //echo "<pre>";print_r($results);echo "</pre>";
                            //exit;

                            $arrDataSourceIDs = array();
                            if(count($results)>0) {
                               foreach ($results as $result) {
                                            $arrDataSourceIDs[] = $result['product_id'];
                                    }
                            }
                            $compareResults = array();
                            $compareTempResults = $this->model_tool_data_sync->getProductsNew($filter_data, $arrDataSourceIDs);
                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                            foreach ($compareTempResults as $result) {
                                    $compareResults[$result['data_source']] = $result;
                            }
                            //echo "<pre>";print_r($compareResults);echo "</pre>";exit;

                            if(count($results)>0) {
                                    foreach ($results as $result) {
                                            if($result["sync_brp"] == 0) {
                                                continue;
                                            }
                                            unset($result["sync_brp"]);
                                            unset($result["sync_options"]);
                                            $insertData = false;
                                            unset($result["name"]);
                                            
                                            if ($result['image'] == '') {
                                                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                                            }else {
                                                $image = "http://brp.com.my/media/data-server-image/".$result['image'];
                                            }

                                            $special = false;
                                            $product_specials = $this->model_tool_data_sync->getProductSpecialsNew($result['product_id']);
                                            foreach ($product_specials  as $product_special) {
                                                    if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
                                                            $special = $product_special['price'];

                                                            break;
                                                    }
                                            }

                                            if($result["rr_price"] != 0) {
                                                $result["price"] = $result["rr_price"];
                                            }
                                            $strHighlightStatus = "New";
                                            if(isset($compareResults[$result['product_id']])) {
                                                    // For the oc_product, will highlight the products has changes (comparing the date_modified field), EOL product (status field is 0), also the price changes will be highlighed too.
                                                //$arrSyncedRow = $this->model_tool_data_sync->getSyncedHistoryNew(" AND `sync_table`='".$table."' AND `sync_unique_id`='".$result['product_id']."' ORDER BY `sync_date` DESC LIMIT 1 ");

                                                if($compareResults[$result['product_id']]['status'] != $result['status'] && $result['status']==0 && $tempMode=="EOL") {
                                                        $strHighlightStatus = "End Of Life";
                                                        $this->model_tool_data_sync->ManualInsertRecord($result, "temp_oc_product");
                                                        $insertData = true;
                                                } else if($result['price']!=$compareResults[$result['product_id']]['data_sync_price'] && $tempMode=="MP") {
                                                        $strHighlightStatus = "Modified - Price";
                                                        $this->model_tool_data_sync->ManualInsertRecord($result, "temp_oc_product");
                                                        $insertData = true;
                                                } else if($result['date_modified']>$compareResults[$result['product_id']]['last_sync_date'] && $tempMode=="M") {
                                                        $strHighlightStatus = "Modified";
                                                        $this->model_tool_data_sync->ManualInsertRecord($result, "temp_oc_product");
                                                        $insertData = true;
                                                }
                                                
                                            }else if($strHighlightStatus == "New"  && ($tempMode=="N" || $tempMode=="REL")) { //NEW PRODUCT
                                                
                                                if($tempMode=="REL") {
                                                    $dateAdded = new DateTime($result['date_added']);
                                                    $dateSynced = new DateTime($tableSyncDate);

                                                    if($dateAdded < $dateSynced) {
                                                        $this->model_tool_data_sync->ManualInsertRecord($result, "temp_oc_product");
                                                        $insertData = true;
                                                        $strHighlightStatus = "Deleted by User";
                                                    }
                                                }else {
                                                    $this->model_tool_data_sync->ManualInsertRecord($result, "temp_oc_product");
                                                    $insertData = true;
                                                }
                                            }
                                            
                                            if($insertData) {
                                                $arrSyncDataDesc = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", "oc_product_description", "", " AND `product_id` = '".$result["product_id"]."' ");
                                                if($arrSyncDataDesc == 'Running') {unset($this->session->data['start_timer']);$this->session->data['runninghalt'] = "Sync Interupted. The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncDataDesc == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                                                if(isset($arrSyncDataDesc["tables"]["oc_product_description"][0])&&is_array($arrSyncDataDesc["tables"]["oc_product_description"][0])&&count($arrSyncDataDesc["tables"]["oc_product_description"][0])>0) {
                                                        $arrQuickDesc = $arrSyncDataDesc["tables"]["oc_product_description"][0];
                                                        $boolDescStatus = $this->model_tool_data_sync->ManualInsertRecord($arrQuickDesc, "temp_oc_product_description");
                                                }
                                            }

//                                            $data['products'][] = array(
//                                                    'sync_status' => $strHighlightStatus,
//                                                    'product_id' => $result['product_id'],
//                                                    'image'      => $image,
//                                                    'name'       => (isset($result['name'])?$result['name']:""),
//                                                    'model'      => $result['model'],
//                                                    'price'      => $result['price'],
//                                                    'special'    => $special,
//                                                    'quantity'   => $result['quantity'],
//                                                    'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
//                                                    'edit'       => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, true)
//                                            );
                                    }
                            }
                            
                            $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServer($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, "", " ", $arrSyncData['start_table'], $arrSyncData['start_data'], $arrSyncData['start_data_from']);
			
                        }
                        
                        $tempSync = array();
                        $tempSync['temp_sync_mode'] = $tempMode;
                        $tempSync['temp_sync_time'] = (new DateTime('NOW'))->format('Y-m-d H:i:s');
                        $this->load->model('setting/setting');
                        $this->model_setting_setting->editSetting('temp_sync', $tempSync);
		
		}
		
		if($table=="") {
			
		} else if($table=="oc_product") {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}
                        
                        if (isset($this->request->get['filter_manufacturer_name'])) {
				$filter_manufacturer_name = $this->request->get['filter_manufacturer_name'];
			} else {
				$filter_manufacturer_name = null;
			}
                        
                        if (isset($this->request->get['filter_category_name'])) {
				$filter_category_name = $this->request->get['filter_category_name'];
			} else {
				$filter_category_name = null;
			}
	
			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = null;
			}
	
			if (isset($this->request->get['filter_price'])) {
				$filter_price = $this->request->get['filter_price'];
			} else {
				$filter_price = null;
			}
	
			if (isset($this->request->get['filter_quantity'])) {
				$filter_quantity = $this->request->get['filter_quantity'];
			} else {
				$filter_quantity = null;
			}
	
			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
			} else {
				$filter_status = null;
			}
                        
                        if (isset($this->request->get['filter_force_update_price'])) {
				$filter_force_update_price = $this->request->get['filter_force_update_price'];
			} else {
				$filter_force_update_price = null;
			}
                        
//                        if (isset($this->request->get['filter_sync_status'])) {
//				$filter_sync_status = $this->request->get['filter_sync_status'];
//			} else {
//				$filter_sync_status = null;
//			}
	
			if (isset($this->request->get['filter_image'])) {
				$filter_image = $this->request->get['filter_image'];
			} else {
				$filter_image = null;
			}
	
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'pd.name';
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
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
                        if (isset($this->request->get['filter_manufacturer_name'])) {
				$url .= '&filter_manufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_manufacturer_name'], ENT_QUOTES, 'UTF-8'));
			}
                        if (isset($this->request->get['filter_category_name'])) {
				$url .= '&filter_category_name=' . urlencode(html_entity_decode($this->request->get['filter_category_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
                        
                        if (isset($this->request->get['filter_force_update_price'])) {
				$url .= '&filter_force_update_price=' . $this->request->get['filter_force_update_price'];
			}
                        
//                        if (isset($this->request->get['filter_sync_status'])) {
//				$url .= '&filter_sync_status=' . $this->request->get['filter_sync_status'];
//			}
			if (isset($this->request->get['filter_image'])) {
				$url .= '&filter_image=' . $this->request->get['filter_image'];
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
                        
                        if (isset($this->request->get['tempMode'])) {
				$url .= '&tempMode=' . $this->request->get['tempMode'];
			}
	
			$filter_data = array(
				'filter_name'	  => $filter_name,
                                'filter_manufacturer_name'	  => $filter_manufacturer_name,
                                'filter_category_name'	  => $filter_category_name,
				'filter_model'	  => $filter_model,
				'filter_price'	  => $filter_price,
				'filter_quantity' => $filter_quantity,
				'filter_status'   => $filter_status,
                                'filter_force_update_price'   => $filter_force_update_price,
//                                'filter_sync_status'   => $filter_sync_status,
				'filter_image'    => $filter_image,
				'sort'            => $sort,
				'order'           => $order,
				'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit'           => $this->config->get('config_limit_admin')
			);
                        
                        $filter_product_id = array();
                        
                        if($filter_force_update_price != "") {
                            $compareID = $this->model_tool_data_sync->getProductsNew($filter_data);
                            $filter_product_id = array();
                            foreach ($compareID as $compare) {
                                if($compare['data_source']!= "") {
                                    array_push($filter_product_id,$compare['data_source']);
                                }
                            }
                        }
                        
	
			$filter_data = array(
                                'filter_product_ids'	  => implode(",",$filter_product_id),
				'filter_name'	  => $filter_name,
                                'filter_manufacturer_name'	  => $filter_manufacturer_name,
                                'filter_category_name'	  => $filter_category_name,
				'filter_model'	  => $filter_model,
				'filter_price'	  => $filter_price,
				'filter_quantity' => $filter_quantity,
				'filter_status'   => $filter_status,
                                'filter_force_update_price'   => $filter_force_update_price,
//                                'filter_sync_status'   => $filter_sync_status,
				'filter_image'    => $filter_image,
				'sort'            => $sort,
				'order'           => $order,
				'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit'           => $this->config->get('config_limit_admin')
			);
	
			$this->load->model('tool/image');
			//$product_total = $this->model_tool_data_sync->getTotalProductsNew($filter_data);
			//$results = $this->model_tool_data_sync->getProductsNew($filter_data);
			//echo "<pre>";print_r($results);echo "</pre>";
			
                        if($tempMode == "") {
                            $arrSyncTotal = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "total", $table, $filter_data);
                            $arrSyncData = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, $filter_data);
                            
                        }else {
                            $arrSyncTotal = $this->model_tool_data_sync->getTempTableTotal($filter_data);
                            $arrSyncData = $this->model_tool_data_sync->getTempTableData($filter_data);
                        }
                        
                        if($arrSyncData == 'Running') {$this->session->data['runninghalt'] = "The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncData == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
                        $product_total = (isset($arrSyncTotal["tables"]["oc_product"][0]["total"])?$arrSyncTotal["tables"]["oc_product"][0]["total"]:"0");
			//echo "<pre>";print_r($product_total);echo "</pre>";
			$results = (isset($arrSyncData["tables"]["oc_product"])?$arrSyncData["tables"]["oc_product"]:array());
			//echo "<pre>";print_r($results);echo "</pre>";
			//exit;
                        
			$arrDataSourceIDs = array();
			if(count($results)>0) {
			   foreach ($results as $result) {
					$arrDataSourceIDs[] = $result['product_id'];
				}
			}
			$compareResults = array();
			$compareTempResults = $this->model_tool_data_sync->getProductsNew($filter_data, $arrDataSourceIDs);
			//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
			foreach ($compareTempResults as $result) {
				$compareResults[$result['data_source']] = $result;
			}
                        
                        $apiParams = $compareTempResults;
                        $arrAPIResults = $this->ajaxAPI($this->config->get('config_using_warehouse_module'), $apiParams);
                        
			//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
			
			if(count($results)>0) {
				foreach ($results as $result) {
					if($tempMode == "") {
                                            if($result["sync_brp"] == 0) {
                                                continue;
                                            }
                                            unset($result["sync_brp"]);
                                            unset($result["sync_options"]);
                                        }
                                        if ($result['image'] == '') {
                                            $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                                        }else {
                                            $image = "http://brp.com.my/media/data-server-image/".$result['image'];
                                        }
                                        
					$special = false;
					$product_specials = $this->model_tool_data_sync->getProductSpecialsNew($result['product_id']);
					foreach ($product_specials  as $product_special) {
						if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
							$special = $product_special['price'];

							break;
						}
					}
					
                                        if($result["rr_price"] != 0) {
                                            $result["price"] = $result["rr_price"];
                                        }
					$strHighlightStatus = "New";
					if(isset($compareResults[$result['product_id']])) {
						// For the oc_product, will highlight the products has changes (comparing the date_modified field), EOL product (status field is 0), also the price changes will be highlighed too.
                                            //$arrSyncedRow = $this->model_tool_data_sync->getSyncedHistoryNew(" AND `sync_table`='".$table."' AND `sync_unique_id`='".$result['product_id']."' ORDER BY `sync_date` DESC LIMIT 1 ");
                                            
                                            if($compareResults[$result['product_id']]['status'] != $result['status'] && $result['status']==0) {
							$strHighlightStatus = "End Of Life";
						} else if($result['price']!=$compareResults[$result['product_id']]['data_sync_price']) {
							$strHighlightStatus = "Modified - Price";
						} else if($result['date_modified']>$compareResults[$result['product_id']]['last_sync_date']) {
							$strHighlightStatus = "Modified";
						} else {
                                                    
							$strHighlightStatus = "-";
						}
						
						
						//echo $strHighlightStatus;exit;
						//echo $result['date_modified']." :: ".$compareResults[$result['product_id']]['date_modified'];echo "<br />";
						//echo $result['price']." :: ".$compareResults[$result['product_id']]['price'];echo "<br />";
						//echo "<pre>";print_r($compareResults[$result['product_id']]);echo "</pre>";exit;
					}else {
                                            if($filter_force_update_price == "") {
                                                $dateAdded = new DateTime($result['date_added']);
                                                $dateSynced = new DateTime($tableSyncDate);

                                                if($dateAdded < $dateSynced) {
                                                    $strHighlightStatus = "Deleted by User";
                                                }
                                            }else {
                                                continue;
                                            }
                                        }
                                        
                                        $intNewQuantity = "-";
                                        $intNewERPBalance = "-";
                                        $intNewWMSBalance = "-";
                                        
                                        if(isset($compareResults[$result['product_id']])) {
                                            if(!$this->config->get('config_using_warehouse_module')&&$compareResults[$result['product_id']]['data_source']!=""&&$compareResults[$result['product_id']]['data_source']!="0") {
                                                    $intNewERPBalance = (isset($arrAPIResults["erp_data"][$compareResults[$result['product_id']]['matching_code']])?str_replace(".0000","",$arrAPIResults["erp_data"][$compareResults[$result['product_id']]['matching_code']]):"0");
                                            } if($this->config->get('config_using_warehouse_module')&&$compareResults[$result['product_id']]['data_source']!=""&&$compareResults[$result['product_id']]['data_source']!="0") {
                                                    $intNewERPBalance = (isset($arrAPIResults["erp_data"][$compareResults[$result['product_id']]['matching_code']])?str_replace(".0000","",$arrAPIResults["erp_data"][$compareResults[$result['product_id']]['matching_code']]):"0");
                                                    $intNewWMSBalance = ""; // Awaiting API
                                            } if($this->config->get('config_using_warehouse_module')&&$compareResults[$result['product_id']]['data_source']=="") {
                                                    $intNewQuantity = (isset($arrAPIResults["oc_data"][$compareResults[$result['product_id']]['product_id']])?$arrAPIResults["oc_data"][$compareResults[$result['product_id']]['product_id']]:"0");
                                                    $intNewWMSBalance = ""; // Awaiting API
                                            } if(!$this->config->get('config_using_warehouse_module')&&$compareResults[$result['product_id']]['data_source']=="") {
                                                    $intNewQuantity = (isset($arrAPIResults["oc_data"][$compareResults[$result['product_id']]['product_id']])?$arrAPIResults["oc_data"][$compareResults[$result['product_id']]['product_id']]:"0");
                                            }
                                        }
                                        
					
					$data['products'][] = array(
						'sync_status' => $strHighlightStatus,
						'product_id' => $result['product_id'],
						'image'      => $image,
						'name'       => (isset($result['name'])?$result['name']:""),
                                                'manufacturer_name'       => (isset($result['manufacturer_name'])?$result['manufacturer_name']:""),
                                                'category_name'       => (isset($result['category_name'])?$result['category_name']:""),
						'model'      => $result['model'],
                                                'selling_price'      => (isset($compareResults[$result['product_id']]['price'])?$compareResults[$result['product_id']]['price']:0.0000),
						'price'      => $result['price'],
						'special'    => $special,
                                                'quantity'   => $result['quantity'],
						'quantity_available'   	=> $intNewQuantity,
                                                'erp_balance'   => $intNewERPBalance,
                                                'wms_balance'   => $intNewWMSBalance,
						'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                                                'force_update_price'     =>     (isset($compareResults[$result['product_id']])?$compareResults[$result['product_id']]['force_update_price'] ? $this->language->get('text_yes') : $this->language->get('text_no'):"-"),
						'edit'       => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, true)
					);
				}
			}
			
			$data['text_list'] = $this->language->get('text_list');
			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
                        $data['text_yes'] = $this->language->get('text_yes');
			$data['text_no'] = $this->language->get('text_no');
			$data['text_confirm'] = $this->language->get('text_confirm');
	
			$data['column_image'] = $this->language->get('column_image');
			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
                        $data['column_selling_price'] = $this->language->get('column_selling_price');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_status'] = $this->language->get('column_status');
                        $data['column_force_update_price'] = $this->language->get('column_force_update_price');
			$data['column_action'] = $this->language->get('column_action');
	
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_model'] = $this->language->get('entry_model');
			$data['entry_price'] = $this->language->get('entry_price');
			$data['entry_quantity'] = $this->language->get('entry_quantity');
			$data['entry_status'] = $this->language->get('entry_status');
                        $data['entry_force_update_price'] = $this->language->get('entry_force_update_price');
//                        $data['entry_sync_status'] = $this->language->get('entry_sync_status');
			$data['entry_image'] = $this->language->get('entry_image');
	
			$data['button_copy'] = $this->language->get('button_copy');
			$data['button_add'] = $this->language->get('button_add');
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_delete'] = $this->language->get('button_delete');
			$data['button_filter'] = $this->language->get('button_filter');
			
                        $url = '';
			if (isset($this->request->get['table'])) {
				$url .= '&table=' . urlencode(html_entity_decode($this->request->get['table'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
                        if (isset($this->request->get['filter_manufacturer_name'])) {
				$url .= '&filter_manufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_manufacturer_name'], ENT_QUOTES, 'UTF-8'));
			}
                        if (isset($this->request->get['filter_category_name'])) {
				$url .= '&filter_category_name=' . urlencode(html_entity_decode($this->request->get['filter_category_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
                        if (isset($this->request->get['filter_force_update_price'])) {
				$url .= '&filter_force_update_price=' . $this->request->get['filter_force_update_price'];
			}
//                        if (isset($this->request->get['filter_sync_status'])) {
//				$url .= '&filter_sync_status=' . $this->request->get['filter_sync_status'];
//			}
			if (isset($this->request->get['filter_image'])) {
				$url .= '&filter_image=' . $this->request->get['filter_image'];
			}
			if ($order == 'ASC') {
				$url .= '&order=DESC';
			} else {
				$url .= '&order=ASC';
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
                        
                        if (isset($this->request->get['tempMode'])) {
				$url .= '&tempMode=' . $this->request->get['tempMode'];
			}
	
			$data['sort_name'] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, true);
                        $data['sort_manufacturer_name'] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort=pm.name' . $url, true);
                        $data['sort_category_name'] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort=pc.name' . $url, true);
			$data['sort_model'] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, true);
			$data['sort_price'] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, true);
			$data['sort_quantity'] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, true);
			$data['sort_status'] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, true);
                        $data['sort_force_update_price'] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort=p.force_update_price' . $url, true);
			$data['sort_order'] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, true);
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$data['filter_name'] = $filter_name;
                        $data['filter_manufacturer_name'] = $filter_manufacturer_name;
                        $data['filter_category_name'] = $filter_category_name;
			$data['filter_model'] = $filter_model;
			$data['filter_price'] = $filter_price;
			$data['filter_quantity'] = $filter_quantity;
			$data['filter_status'] = $filter_status;
                        $data['filter_force_update_price'] = $filter_force_update_price;
//                        $data['filter_sync_status'] = $filter_sync_status;
			$data['filter_image'] = $filter_image;
			$data['sort'] = $sort;
			$data['order'] = $order;
		
		} else if($table=="oc_product_attribute"||$table=="oc_product_description"||$table=="oc_product_discount"||$table=="oc_product_filter"
			||$table=="oc_product_image"||$table=="oc_product_option"||$table=="oc_product_option_value"||$table=="oc_product_recurring"
			||$table=="oc_product_related"||$table=="oc_product_reward"||$table=="oc_product_special"||$table=="oc_product_to_category"
			||$table=="oc_product_to_download"||$table=="oc_product_to_layout"||$table=="oc_product_to_store"||$table=="oc_product_maxqty_groups"
			||$table=="oc_product_to_field"
			) {
			
			$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, $filter_data);
			//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";exit;
			//exit;
			
			if (isset($this->request->get['filter_product_id'])) {
				$filter_product_id = $this->request->get['filter_product_id'];
			} else {
				$filter_product_id = null;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'product_id';
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
			$filter_data = array(
				'filter_product_id'    => $filter_product_id,
				'sort'            => $sort,
				'order'           => $order,
				'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit'           => $this->config->get('config_limit_admin')
			);
			//echo "<pre>";print_r($filter_data);echo "</pre>";
			
			$arrSyncTotal = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "total", $table, $filter_data);
			$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, $filter_data);
                        if($arrSyncData == 'Running') {$this->session->data['runninghalt'] = "The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncData == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
			//echo "<pre>";print_r($arrSyncData);echo "</pre>";exit;
			$product_total = (isset($arrSyncTotal["tables"][$table][0]["total"])?$arrSyncTotal["tables"][$table][0]["total"]:"0");
			//echo "<pre>";print_r($product_total);echo "</pre>";exit;
			$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:array());
			//echo "<pre>";print_r($results);echo "</pre>";
			//exit;
			
			// Product ID Mapping - Start
			$arrDataSourceIDs = array();
			foreach ($results as $result) {
				$arrDataSourceIDs[] = $result["product_id"];
			}
			$arrMappingSourceProdcutData = array();
			$arrMappingProductSourceData = array();
			$arrTempMappingData = $this->model_tool_data_sync->getProductMappingsNew($arrDataSourceIDs);
			foreach ($arrTempMappingData as $keyTemp => $keyResult) {
				$arrMappingSourceProdcutData[$keyResult["product_id"]] = $keyResult["data_source"];
				$arrMappingProductSourceData[$keyResult["data_source"]] = $keyResult["product_id"];
			}
			//echo "<pre>";print_r($arrMappingSourceProdcutData);echo "</pre>";
				//Array([19914] => 19899, [30126] => 19901)
			//echo "<pre>";print_r($arrMappingProductSourceData);echo "</pre>";
				//Array([19899] => 19914, [19901] => 30126)
			//exit;
			// Product ID Mapping - End

			$arrCustomTableKey = array(
				"oc_product_attribute" => array("product_id", "attribute_id", "language_id"),
				"oc_product_description" => array("product_id", "language_id"),
				"oc_product_discount" => array("product_discount_id"), //array("product_id", "product_discount_id", "customer_group_id"),
				"oc_product_filter" => array("product_id", "filter_id"),
				"oc_product_image" => array("product_image_id"), //array("product_id", "product_image_id"),
				"oc_product_option" => array("product_option_id"), //array("product_id", "product_option_id", "option_id"),
				"oc_product_option_value" => array("product_option_value_id"), //array("product_id", "product_option_value_id", "product_option_id", "option_id", "option_value_id"),
				"oc_product_recurring" => array("product_id", "recurring_id", "customer_group_id"),
				"oc_product_related" => array("product_id", "related_id"),
				"oc_product_reward" => array("product_reward_id"), //array("product_id", "product_reward_id", "customer_group_id"),
				"oc_product_special" => array("product_special_id"), //array("product_id", "product_special_id", "customer_group_id"),
				"oc_product_to_category" => array("product_id", "category_id"),
				"oc_product_to_download" => array("product_id", "download_id"),
				"oc_product_to_layout" => array("product_id", "store_id"),
				"oc_product_to_store" => array("product_id", "store_id"),
				"oc_product_maxqty_groups" => array("product_id", "customer_group_id"), // new table
				"oc_product_to_field" => array("product_id", "additional_product_id", "language_id") // new table
			);
			$arrCustomTableKeyValues = array();
			foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
				foreach ($results as $result) {
					if($valueKey=="product_id") {
						$arrCustomTableKeyValues[$valueKey][] = (isset($arrMappingProductSourceData[$result[$valueKey]])?$arrMappingProductSourceData[$result[$valueKey]]:$result[$valueKey]);
						//$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
					} else {
						$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
					}
				}
			}
			//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";//exit;
			//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";//exit;
			$compareResults = array();
			$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
			//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
			foreach ($compareTempResults as $tempResult) {
				if($arrCustomTableKey[$table][0]=="product_id") {
					$tempResult[$arrCustomTableKey[$table][0]] = (isset($arrMappingSourceProdcutData[$tempResult["product_id"]])?$arrMappingSourceProdcutData[$tempResult["product_id"]]:$tempResult["product_id"]); // convert the data_source to partner product_id
				}
				//echo "<pre>";print_r($tempResult);echo "</pre>";exit;
				if(count($arrCustomTableKey[$table])=="5"){
					$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
				} else if(count($arrCustomTableKey[$table])=="4"){
					$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
				} else if(count($arrCustomTableKey[$table])=="3"){
					$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
				} else if(count($arrCustomTableKey[$table])=="2"){
					$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
				} else if(count($arrCustomTableKey[$table])=="1"){
					$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
				}
			}
			//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
			
			/*// Product Related - Start
			$compareResults = array();
			$compareTempResults = $this->model_tool_data_sync->getProductRelatedNew($table, $arrMappingProductSourceData);
			//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
			foreach ($compareTempResults as $result) {
				$compareResults[$arrMappingSourceProdcutData[$result["product_id"]]] = $result;
			}
			//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
			// Product Related - End*/
			
			if(
				$table=="oc_product_discount" || 
				$table=="oc_product_image" || 
				$table=="oc_product_option" || 
				$table=="oc_product_option_value" ||
				$table=="oc_product_reward" || 
				$table=="oc_product_special"
				) {
				foreach ($results as $keyResult => $result) {
					$arrMapping = array();
					$strHighlightStatus = "New";
					if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
						if(($table=="new_tablex") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
							$strHighlightStatus = "Modified";
						} else {
							$strHighlightStatus = "-";
						}
                                            }
					$arrMapping["sync_status"] = $strHighlightStatus;
					foreach($arrSyncColumns as $keyColumn => $valueColumn) {
						if($table=="oc_product_image"&&$valueColumn=="image") {
							$result["image"] = "http://brp.com.my/media/data-server-image/".$result["image"];
						}
						$arrMapping[$valueColumn] = $result[$valueColumn];
						$arrMapping["product_id"] = (isset($arrMappingProductSourceData[$result["product_id"]])?$arrMappingProductSourceData[$result["product_id"]]:"");
						$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
					}
					//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
					$data['products'][] = $arrMapping;
				}
			} else if(
                                $table=="oc_product_description" || 
				$table=="oc_product_filter" || 
				$table=="oc_product_related" || 
				$table=="oc_product_to_category" || 
				$table=="oc_product_to_download" || 
				$table=="oc_product_to_store" ||
				$table=="oc_product_maxqty_groups" || 
				$table=="oc_product_to_layout"
				) {
				foreach ($results as $keyResult => $result) {
					$arrMapping = array();
					$strHighlightStatus = "New";
					//echo "<pre>";print_r($result);echo "</pre>";//exit;
					//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
					//echo "<pre>";print_r($result[$arrCustomTableKey[$table][0]]);echo "</pre>";//exit;
					if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]][$result[$arrCustomTableKey[$table][1]]])) {
						if(($table=="new_tablex") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]][$result[$arrCustomTableKey[$table][1]]]['last_sync_date']) {
							$strHighlightStatus = "Modified";
						} else {
							$strHighlightStatus = "-";
						}
					}
					$arrMapping["sync_status"] = $strHighlightStatus;
					foreach($arrSyncColumns as $keyColumn => $valueColumn) {
						//$intProductID = (isset($arrMappingProductSourceData[$result["product_id"]])?$arrMappingProductSourceData[$result["product_id"]]:"");
						//if($intProductID!="") {
							$arrMapping[$valueColumn] = $result[$valueColumn];
							$arrMapping["product_id"] = (isset($arrMappingProductSourceData[$result["product_id"]])?$arrMappingProductSourceData[$result["product_id"]]:"");
							$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]]."_".$result[$arrCustomTableKey[$table][1]];
						//}
					}
					//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
					//if(count($arrMapping)>0) {
						$data['products'][] = $arrMapping;
					//}
				}
			} else if(
                                $table=="oc_product_attribute" || 
				$table=="oc_product_recurring" ||
				$table=="oc_product_to_field"
				) {
				foreach ($results as $keyResult => $result) {
					$arrMapping = array();
					$strHighlightStatus = "New";
					if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]][$result[$arrCustomTableKey[$table][1]]][$result[$arrCustomTableKey[$table][2]]])) {
						if(($table=="new_tablex") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]][$result[$arrCustomTableKey[$table][1]]][$result[$arrCustomTableKey[$table][2]]]['last_sync_date']) {
							$strHighlightStatus = "Modified";
						} else {
							$strHighlightStatus = "-";
						}
					}
					$arrMapping["sync_status"] = $strHighlightStatus;
					foreach($arrSyncColumns as $keyColumn => $valueColumn) {
						$arrMapping[$valueColumn] = $result[$valueColumn];
						$arrMapping["product_id"] = (isset($arrMappingProductSourceData[$result["product_id"]])?$arrMappingProductSourceData[$result["product_id"]]:"");
						$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]]."_".$result[$arrCustomTableKey[$table][1]]."_".$result[$arrCustomTableKey[$table][2]];
					}
					//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
					$data['products'][] = $arrMapping;
				}
			}
			//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
			
			$data['text_list'] = $arrSyncTables[$table]." list";
			$data['table_columns'] = $arrSyncColumns;
			
			if (isset($this->request->get['table'])) {
				$url .= '&table=' . urlencode(html_entity_decode($this->request->get['table'], ENT_QUOTES, 'UTF-8'));
			}
			if ($order == 'ASC') {
				$url .= '&order=DESC';
			} else {
				$url .= '&order=ASC';
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			foreach($arrSyncColumns as $keyColumn => $valueColumn) {
				$data[$valueColumn] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort='.$valueColumn . $url, true);
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			//echo "<pre>";print_r($data);echo "</pre>";exit;
			
			$data['sort'] = $sort;
			$data['order'] = $order;
			
		} else if($table!="") {
			
			//$table = "oc_manufacturer_to_store";
			$arrSyncColumns = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "get_table_columns", $table, $filter_data);
			//echo "<pre>";print_r($arrSyncColumns);echo "</pre>";
			//exit;
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = '';
			}
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = '';
			}
			if($sort=="" || $order=="") {
				$sort = ''; $order = '';
			}
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			$filter_data = array(
				'sort'            => $sort,
				'order'           => $order,
				'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit'           => $this->config->get('config_limit_admin')
			);
			//echo "<pre>";print_r($filter_data);echo "</pre>";
			
			$arrSyncTotal = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "total", $table, $filter_data);
			$arrSyncData = $this->model_tool_data_sync->syncDataFromDataServerList($this->config->get('data_sync_api_url'), $this->config->get('data_sync_api_partner_key'), "data", $table, $filter_data);
                        if($arrSyncData == 'Running') {$this->session->data['runninghalt'] = "The BRP Data Server is currently being updated. Please try again in another minute.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}                        if($arrSyncData == 'Timezone') {unset($this->session->data['start_timer']);$this->session->data['timezonehalt'] = "The timezone of this server is different from BRP server. Please contact the administrator.";$this->response->redirect($this->url->link('tool/data_sync', 'token=' . $this->session->data['token'], $this->ssl));}
			//echo "<pre>";print_r($arrSyncData);echo "</pre>";
			$product_total = (isset($arrSyncTotal["tables"][$table][0]["total"])?$arrSyncTotal["tables"][$table][0]["total"]:"0");
			//echo "<pre>";print_r($product_total);echo "</pre>";
			$results = (isset($arrSyncData["tables"][$table])?$arrSyncData["tables"][$table]:array());
			//echo "<pre>";print_r($results);echo "</pre>";
			//exit;
			
			$arrCustomTableKey = array(
				"oc_module" => array("module_id"),
				"oc_banner" => array("banner_id"),
				"oc_banner_image" => array("banner_image_id"),
				"oc_banner_image_description" => array("banner_image_id", "language_id"), // new table
				"oc_manufacturer" => array("manufacturer_id"),
				"oc_manufacturer_description" => array("manufacturer_id", "language_id"), // new table
				"oc_manufacturer_to_store" => array("manufacturer_id", "store_id"),
				"oc_category" => array("category_id"),
				"oc_category_description" => array("category_id", "language_id"),
				"oc_category_filter" => array("category_id", "filter_id"),
				"oc_category_path" => array("category_id", "path_id"),
				"oc_category_to_layout" => array("category_id", "store_id"),
				"oc_category_to_store" => array("category_id", "store_id"), 
				"oc_attribute" => array("attribute_id"),
				"oc_attribute_description" => array("attribute_id", "language_id"),
				"oc_attribute_group" => array("attribute_group_id"),
				"oc_attribute_group_description" => array("attribute_group_id", "language_id"),
				"oc_filter" => array("filter_id"),
				"oc_filter_description" =>  array("filter_id", "language_id"),
				"oc_filter_group" => array("filter_group_id"),
				"oc_filter_group_description" => array("filter_group_id", "language_id"),
                                "oc_customer_group" => array("customer_group_id"), // new table
				"oc_customer_group_description" => array("customer_group_id", "language_id"), // new table
				"oc_additional_product" => array("additional_product_id", "language_id"), // new table
				"oc_additional_product_value" => array("additional_product_value_id", "additional_product_id", "language_id"), // new table
				"oc_tax_class" => array("tax_class_id"),
				"oc_tax_rate" => array("tax_rate_id"),
				"oc_tax_rate_to_customer_group" => array("tax_rate_id", "customer_group_id"),
				"oc_tax_rule" =>  array("tax_rule_id"),
				"oc_geo_zone" => array("geo_zone_id"),
				"oc_zone" => array("zone_id"), 
				"oc_zone_to_geo_zone" => array("zone_to_geo_zone_id"),
				"oc_country" => array("country_id"),
				"oc_zip_code" => array("zip_code_id"), // new table
				"oc_weight_class" => array("weight_class_id"),
				"oc_weight_class_description" => array("weight_class_id", "language_id"),
				"oc_length_class" => array("length_class_id"),
				"oc_length_class_description" => array("length_class_id", "language_id")
			);
			//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";exit;
			$arrCustomTableKeyValues = array();
			foreach($arrCustomTableKey[$table] as $keyKey => $valueKey) {
				if(count($results)>0) {
					foreach ($results as $result) {
						$arrCustomTableKeyValues[$valueKey][] = $result[$valueKey];
					}
				}
			}
			//echo "<pre>";print_r($arrCustomTableKey[$table]);echo "</pre>";
			//echo "<pre>";print_r($arrCustomTableKeyValues);echo "</pre>";
			$compareResults = array();
			$compareTempResults = $this->model_tool_data_sync->getCustomTableNew($table, $arrCustomTableKey[$table], $arrCustomTableKeyValues);
			//echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
			foreach ($compareTempResults as $tempResult) {
				if(count($arrCustomTableKey[$table])=="5"){
					$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]][$tempResult[$arrCustomTableKey[$table][4]]] = $tempResult;
				} else if(count($arrCustomTableKey[$table])=="4"){
					$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]][$tempResult[$arrCustomTableKey[$table][3]]] = $tempResult;
				} else if(count($arrCustomTableKey[$table])=="3"){
					$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]][$tempResult[$arrCustomTableKey[$table][2]]] = $tempResult;
				} else if(count($arrCustomTableKey[$table])=="2"){
					$compareResults[$tempResult[$arrCustomTableKey[$table][0]]][$tempResult[$arrCustomTableKey[$table][1]]] = $tempResult;
				} else if(count($arrCustomTableKey[$table])=="1"){
					$compareResults[$tempResult[$arrCustomTableKey[$table][0]]] = $tempResult;
				}
			}
			//echo "<pre>";print_r($compareResults);echo "</pre>";exit;
			
			if($table=="oc_zone_to_geo_zone" || $table=="oc_tax_rule" || $table=="oc_zone" || $table=="oc_module" || $table=="oc_tax_rate" || $table=="oc_filter" || $table=="oc_attribute" || $table=="oc_banner" || $table=="oc_banner_image" || $table=="oc_manufacturer" || $table=="oc_category" 
			     || $table=="oc_attribute_group" || $table=="oc_filter_group"
				 || $table=="oc_customer_group" || $table=="oc_tax_class" || $table=="oc_geo_zone" || $table=="oc_country"  || $table=="oc_zip_code"
				|| $table=="oc_weight_class" || $table=="oc_length_class") {
				foreach ($results as $keyResult => $result) {
                                    
                                        if($table=="oc_customer_group" && $result['customer_group_id'] != 8) {
                                            continue;
                                        }
                                    
					if($table == "oc_category") {
                                            $arrDataSourceIDs = array();
                                            $arrDataSourceIDs[] = $result['category_id'];
                                            $compareResults = array();
                                            $compareTempResults = $this->model_tool_data_sync->getCategoriesNew(array(), $arrDataSourceIDs);
                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                            foreach ($compareTempResults as $tempResult) {
                                                    $compareResults[$tempResult['data_source']] = $tempResult;
                                            }


                                            $result["last_sync_date"] = $nowDT;
                                                $arrMapping = array();
                                                $strHighlightStatus = "New";
                                                if(isset($compareResults[$result['category_id']])) {
                                                        // For the oc_product, will highlight the products has changes (comparing the date_modified field), EOL product (status field is 0), also the price changes will be highlighed too.
                                                        if($result['date_modified']>$compareResults[$result['category_id']]['last_sync_date']) {
                                                                $strHighlightStatus = "Modified";
                                                        } else {

                                                                $strHighlightStatus = "-";
                                                        }
                                                }else {
                                                    $dateAdded = new DateTime($result['date_added']);
                                                    $dateSynced = new DateTime($tableSyncDate);

                                                    if($dateAdded < $dateSynced) {
                                                        $strHighlightStatus = "Deleted by User";
                                                    }
                                                }
                                        }else if($table == "oc_manufacturer") {
                                            $arrDataSourceIDs = array();
                                            $arrDataSourceIDs[] = $result['manufacturer_id'];
                                            $compareResults = array();
                                            $compareTempResults = $this->model_tool_data_sync->getManufacturersNew(array(), $arrDataSourceIDs);
                                            //echo "<pre>";print_r($compareTempResults);echo "</pre>";exit;
                                            foreach ($compareTempResults as $tempResult) {
                                                    $compareResults[$tempResult['data_source']] = $tempResult;
                                            }


                                            $result["last_sync_date"] = $nowDT;
                                                $arrMapping = array();
                                                $strHighlightStatus = "New";
                                                if(isset($compareResults[$result['manufacturer_id']])) {
                                                        $strHighlightStatus = "-";
                                                        
                                                }
                                        }else {
                                    
                                                $arrMapping = array();
                                                $strHighlightStatus = "New";
                                                if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]])) {
                                                        if(($table=="oc_tax_rate" || $table=="oc_zone_to_geo_zone" ||$table=="oc_tax_class"||$table=="oc_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]]['last_sync_date']) {
                                                                $strHighlightStatus = "Modified";
                                                        } else {
                                                                $strHighlightStatus = "-";
                                                        }
                                                }else {
                                                    if(($table=="oc_tax_rate" || $table=="oc_zone_to_geo_zone" ||$table=="oc_tax_class"||$table=="oc_geo_zone")) {
                                                    $dateAdded = new DateTime($result['date_added']);
                                                    $dateSynced = new DateTime($tableSyncDate);

                                                    if($dateAdded < $dateSynced) {
                                                        $strHighlightStatus = "Deleted by User";
                                                    }
                                                    }
                                                }
                                        }
                                        
					$arrMapping["sync_status"] = $strHighlightStatus;
                                        if(isset($result['name'])) {$arrMapping["name"] = $result['name'];}
					foreach($arrSyncColumns as $keyColumn => $valueColumn) {
						$arrMapping[$valueColumn] = $result[$valueColumn];
						$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]];
					}
					//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
					$data['products'][] = $arrMapping;
				}
			} else if($table=="oc_category_to_layout" || $table=="oc_length_class_description" || $table=="oc_weight_class_description" || $table=="oc_customer_group_description" || $table=="oc_filter_group_description" || $table=="oc_attribute_group_description" || $table=="oc_attribute_description" || $table=="oc_manufacturer_to_store" || $table=="oc_category_description" || $table=="oc_manufacturer_description" || $table=="oc_banner_image_description" || $table=="oc_category_filter" || $table=="oc_category_path" || $table=="oc_category_to_store" 
				 || $table=="oc_filter_description" || $table=="oc_additional_product" 
				|| $table=="oc_tax_rate_to_customer_group" || $table=="new_tablex" || $table=="new_tablex") {
				foreach ($results as $keyResult => $result) {
					$arrMapping = array();
					$strHighlightStatus = "New";
					if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]][$result[$arrCustomTableKey[$table][1]]])) {
						if(($table=="oc_tax_rate") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]][$result[$arrCustomTableKey[$table][1]]]['last_sync_date']) {
							$strHighlightStatus = "Modified";
						} else {
							$strHighlightStatus = "-";
						}
					}else {
                                            if(($table=="oc_tax_rate")) {
                                            $dateAdded = new DateTime($result['date_added']);
                                            $dateSynced = new DateTime($tableSyncDate);
                                            
                                            if($dateAdded < $dateSynced) {
                                                $strHighlightStatus = "Deleted by User";
                                            }
                                            }
                                        }
					$arrMapping["sync_status"] = $strHighlightStatus;
                                        if(isset($result['name'])) {$arrMapping["name"] = $result['name'];}
					foreach($arrSyncColumns as $keyColumn => $valueColumn) {
						$arrMapping[$valueColumn] = $result[$valueColumn];
						$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]]."_".$result[$arrCustomTableKey[$table][1]];
					}
					//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
					$data['products'][] = $arrMapping;
				}
			} else if($table=="new_tablex" || $table=="oc_additional_product_value") {
				foreach ($results as $keyResult => $result) {
					$arrMapping = array();
					$strHighlightStatus = "New";
					if(isset($compareResults[$result[$arrCustomTableKey[$table][0]]][$result[$arrCustomTableKey[$table][1]]][$result[$arrCustomTableKey[$table][2]]])) {
						if(($table=="oc_zone_to_geo_zone") && $result['date_modified']>$compareResults[$result[$arrCustomTableKey[$table][0]]][$result[$arrCustomTableKey[$table][1]]][$result[$arrCustomTableKey[$table][2]]]['last_sync_date']) {
							$strHighlightStatus = "Modified";
						} else {
							$strHighlightStatus = "-";
						}
					}else {
                                            if(($table=="oc_zone_to_geo_zone")) {
                                            $dateAdded = new DateTime($result['date_added']);
                                            $dateSynced = new DateTime($tableSyncDate);
                                            
                                            if($dateAdded < $dateSynced) {
                                                $strHighlightStatus = "Deleted by User";
                                            }
                                            }
                                        }
					$arrMapping["sync_status"] = $strHighlightStatus;
                                        if(isset($result['name'])) {$arrMapping["name"] = $result['name'];}
					foreach($arrSyncColumns as $keyColumn => $valueColumn) {
						$arrMapping[$valueColumn] = $result[$valueColumn];
						$arrMapping["sync_unique_id"] = $result[$arrCustomTableKey[$table][0]]."_".$result[$arrCustomTableKey[$table][1]]."_".$result[$arrCustomTableKey[$table][2]];
					}
					//echo "<pre>";print_r($arrMapping);echo "</pre>";exit;
					$data['products'][] = $arrMapping;
				}
			}
			
			$data['text_list'] = $arrSyncTables[$table]." list";
                        if(!in_array("name", $arrSyncColumns)) {
                            array_splice( $arrSyncColumns, 0, 0, "name");
                        }else {
                            $keyName = array_search("name",$arrSyncColumns);
                            unset($arrSyncColumns[$keyName]);
                            array_splice( $arrSyncColumns, 0, 0, "name");
                        }
			$data['table_columns'] = $arrSyncColumns;
			
			if (isset($this->request->get['table'])) {
				$url .= '&table=' . urlencode(html_entity_decode($this->request->get['table'], ENT_QUOTES, 'UTF-8'));
			}
			if ($order == 'ASC') {
				$url .= '&order=DESC';
			} else {
				$url .= '&order=ASC';
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if(count($arrSyncColumns)>0) {
				foreach($arrSyncColumns as $keyColumn => $valueColumn) {
					$data[$valueColumn] = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . '&sort='.$valueColumn . $url, true);
				}
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			//echo "<pre>";print_r($data);echo "</pre>";exit;
			
			$data['sort'] = $sort;
			$data['order'] = $order;
		}
		
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
		
		$dtStartTime = ""; $dtEndTime = "";
		if (isset($this->session->data['start_timer'])) {
			$data['success'] .= "<br />Start Time: ".$this->session->data['start_timer'];
			$dtStartTime = $this->session->data['start_timer'];
			unset($this->session->data['start_timer']);
		} if (isset($this->session->data['end_timer'])) {
			$data['success'] .= "<br />End Time: ".$this->session->data['end_timer'];
			$dtEndTime = $this->session->data['end_timer'];
			unset($this->session->data['end_timer']);
		}
		
                if (isset($this->session->data['runninghalt'])) {
			$data['runninghalt'] = $this->session->data['runninghalt'];
			unset($this->session->data['runninghalt']);
		}
                
                if (isset($this->session->data['timezonehalt'])) {
			$data['timezonehalt'] = $this->session->data['timezonehalt'];
			unset($this->session->data['timezonehalt']);
		}
                
		if ($dtStartTime!="" && $dtEndTime!="") {
			//$dtStartTime = "2016-10-06 10:00:00";
			//$dtEndTime = "2016-10-06 11:02:05";
			$datetime1 = new DateTime($dtStartTime);
			$datetime2 = new DateTime($dtEndTime);
			$interval = $datetime1->diff($datetime2);
			$format = array();
    		//$data['success'] .= "<br />".$interval->format('%H Hour %I Minute %S Second');
			if($interval->y !== 0) { 
				$format[] = "%y year".($interval->y>1?"s":""); 
			} 
			if($interval->m !== 0) { 
				$format[] = "%m month".($interval->m>1?"s":""); 
			} 
			if($interval->d !== 0) { 
				$format[] = "%d day".($interval->d>1?"s":""); 
			} 
			if($interval->h !== 0) { 
				$format[] = "%h hour".($interval->h>1?"s":""); 
			} 
			if($interval->i !== 0) { 
				$format[] = "%i minute".($interval->i>1?"s":""); 
			} 
			if($interval->s !== 0) { 
				$format[] = "%s second".($interval->s>1?"s":""); 
			}
			// We use the two biggest parts 
			//if(count($format) > 1) { 
			//	$format = array_shift($format)." and ".array_shift($format); 
			//} else { 
			//	$format = array_pop($format); 
			//}
			if(count($format)>0) {
				$format = implode(" ", $format); 
				//echo $format;exit;
				// Prepend 'since ' or whatever you like 
				$data['success'] .= "<br />Duration: ".$interval->format($format); 
			}
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		

		if (isset($this->request->post['data_sync_api_url'])) {
			$data['data_sync_api_url'] = $this->request->post['data_sync_api_url'];
		} else {
			$data['data_sync_api_url'] = $this->config->get('data_sync_api_url');
		}
		
		if (isset($this->request->post['data_sync_api_partner_key'])) {
			$data['data_sync_api_partner_key'] = $this->request->post['data_sync_api_partner_key'];
		} else {
			$data['data_sync_api_partner_key'] = $this->config->get('data_sync_api_partner_key');
		}
		
		$arrTableHistories = $this->model_tool_data_sync->getSyncedHistoryNew(" GROUP BY `sync_table` ");
		//echo "<pre>";print_r($arrTableHistories);echo "</pre>";exit;
		$data['sync_histories'] = array();
		if(count($arrTableHistories)>0) {
			foreach($arrTableHistories as $thKey => $thValue) {
				$data['sync_histories'][] = array(
					'sync_table' => $thValue["sync_table"],
					'sync_date' => $thValue["sync_date"]
				);
			}
		}
		//echo "<pre>";print_r($data['sync_history']);echo "</pre>";exit;
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['tab_sync'] = $this->language->get( 'tab_sync' );
		$data['tab_history'] = $this->language->get( 'tab_history' );
		$data['tab_settings'] = $this->language->get( 'tab_settings' );
		
		$data['button_sync'] = $this->language->get( 'button_sync' );
		$data['button_sync_selected'] = $this->language->get( 'button_sync_selected' );
		$data['button_sync_all'] = $this->language->get( 'button_sync_all' );
		$data['button_sync_new_changes'] = $this->language->get( 'button_sync_new_changes' );
		$data['button_history'] = $this->language->get( 'button_history' );
		$data['button_settings'] = $this->language->get( 'button_settings' );

		$data['button_next'] = $this->language->get('button_next');
		$data['button_save'] = $this->language->get('button_save');
		$data['entry_api_url'] = $this->language->get('entry_api_url');
		$data['entry_markup_percentage'] = $this->language->get('entry_markup_percentage');
		$data['entry_api_partner_key'] = $this->language->get('entry_api_partner_key');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['curr_selected_table'] = str_replace(DB_PREFIX,"",$table);
		$data['text_loading_notifications'] = "Syncing data. This process may take a while. Please wait.";
                $data['text_loading_notifications_all'] = "Syncing all new data. This process may take a long time to complete. Please wait.";
			
		$product_total = (isset($product_total)?$product_total:"0");
		$page = (isset($page)?$page:"0");
		if($product_total>0) {
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			$pagination->url = $this->url->link('tool/data_sync', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
			$data['pagination'] = $pagination->render();
			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));
		}
		$data['sync'] = $this->url->link('tool/data_sync/sync', 'token=' . $this->session->data['token'], $this->ssl);
		$data['settings'] = $this->url->link('tool/data_sync/settings', 'token=' . $this->session->data['token'], $this->ssl);
		
		$data['total_records'] = $product_total;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		//$this->response->setOutput($this->load->view('catalog/product_list', $data));
		$this->response->setOutput($this->load->view('tool/data_sync', $data));
	}
	
	protected function validateDownloadForm() {
		if (!$this->user->hasPermission('access', 'tool/data_sync')) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}

		if (!$this->config->get( 'data_sync_settings_use_option_id' )) {
			$option_names = $this->model_tool_data_sync->getOptionNameCounts();
			foreach ($option_names as $option_name) {
				if ($option_name['count'] > 1) {
					$this->error['warning'] = str_replace( '%1', $option_name['name'], $this->language->get( 'error_option_name' ) );
					return false;
				}
			}
		}

		if (!$this->config->get( 'data_sync_settings_use_option_value_id' )) {
			$option_value_names = $this->model_tool_data_sync->getOptionValueNameCounts();
			foreach ($option_value_names as $option_value_name) {
				if ($option_value_name['count'] > 1) {
					$this->error['warning'] = str_replace( '%1', $option_value_name['name'], $this->language->get( 'error_option_value_name' ) );
					return false;
				}
			}
		}

		if (!$this->config->get( 'data_sync_settings_use_attribute_group_id' )) {
			$attribute_group_names = $this->model_tool_data_sync->getAttributeGroupNameCounts();
			foreach ($attribute_group_names as $attribute_group_name) {
				if ($attribute_group_name['count'] > 1) {
					$this->error['warning'] = str_replace( '%1', $attribute_group_name['name'], $this->language->get( 'error_attribute_group_name' ) );
					return false;
				}
			}
		}

		if (!$this->config->get( 'data_sync_settings_use_attribute_id' )) {
			$attribute_names = $this->model_tool_data_sync->getAttributeNameCounts();
			foreach ($attribute_names as $attribute_name) {
				if ($attribute_name['count'] > 1) {
					$this->error['warning'] = str_replace( '%1', $attribute_name['name'], $this->language->get( 'error_attribute_name' ) );
					return false;
				}
			}
		}

		if (!$this->config->get( 'data_sync_settings_use_filter_group_id' )) {
			$filter_group_names = $this->model_tool_data_sync->getFilterGroupNameCounts();
			foreach ($filter_group_names as $filter_group_name) {
				if ($filter_group_name['count'] > 1) {
					$this->error['warning'] = str_replace( '%1', $filter_group_name['name'], $this->language->get( 'error_filter_group_name' ) );
					return false;
				}
			}
		}

		if (!$this->config->get( 'data_sync_settings_use_filter_id' )) {
			$filter_names = $this->model_tool_data_sync->getFilterNameCounts();
			foreach ($filter_names as $filter_name) {
				if ($filter_name['count'] > 1) {
					$this->error['warning'] = str_replace( '%1', $filter_name['name'], $this->language->get( 'error_filter_name' ) );
					return false;
				}
			}
		}

		return true;
	}


	protected function validateUploadForm() {
		if (!$this->user->hasPermission('modify', 'tool/data_sync')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else if (!isset( $this->request->post['incremental'] )) {
			$this->error['warning'] = $this->language->get( 'error_incremental' );
		} else if ($this->request->post['incremental'] != '0') {
			if ($this->request->post['incremental'] != '1') {
				$this->error['warning'] = $this->language->get( 'error_incremental' );
			}
		}

		if (!isset($this->request->files['upload']['name'])) {
			if (isset($this->error['warning'])) {
				$this->error['warning'] .= "<br /\n" . $this->language->get( 'error_upload_name' );
			} else {
				$this->error['warning'] = $this->language->get( 'error_upload_name' );
			}
		} else {
			$ext = strtolower(pathinfo($this->request->files['upload']['name'], PATHINFO_EXTENSION));
			if (($ext != 'xls') && ($ext != 'xlsx') && ($ext != 'ods')) {
				if (isset($this->error['warning'])) {
					$this->error['warning'] .= "<br /\n" . $this->language->get( 'error_upload_ext' );
				} else {
					$this->error['warning'] = $this->language->get( 'error_upload_ext' );
				}
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}


	protected function validateSettingsForm() {
            
                $route = isset($this->request->get['route']) ? $this->request->get['route'] : '';
            
                $ignore = array(
                        'tool/data_sync/sync'
                );
                        
		if (!$this->user->hasPermission('access', 'tool/data_sync') && !in_array($route, $ignore)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}

//		if (empty($this->request->post['data_sync_settings_use_option_id'])) {
//			$option_names = $this->model_tool_data_sync->getOptionNameCounts();
//			foreach ($option_names as $option_name) {
//				if ($option_name['count'] > 1) {
//					$this->error['warning'] = str_replace( '%1', $option_name['name'], $this->language->get( 'error_option_name' ) );
//					return false;
//				}
//			}
//		}
//
//		if (empty($this->request->post['data_sync_settings_use_option_value_id'])) {
//			$option_value_names = $this->model_tool_data_sync->getOptionValueNameCounts();
//			foreach ($option_value_names as $option_value_name) {
//				if ($option_value_name['count'] > 1) {
//					$this->error['warning'] = str_replace( '%1', $option_value_name['name'], $this->language->get( 'error_option_value_name' ) );
//					return false;
//				}
//			}
//		}
//
//		if (empty($this->request->post['data_sync_settings_use_attribute_group_id'])) {
//			$attribute_group_names = $this->model_tool_data_sync->getAttributeGroupNameCounts();
//			foreach ($attribute_group_names as $attribute_group_name) {
//				if ($attribute_group_name['count'] > 1) {
//					$this->error['warning'] = str_replace( '%1', $attribute_group_name['name'], $this->language->get( 'error_attribute_group_name' ) );
//					return false;
//				}
//			}
//		}
//
//		if (empty($this->request->post['data_sync_settings_use_attribute_id'])) {
//			$attribute_names = $this->model_tool_data_sync->getAttributeNameCounts();
//			foreach ($attribute_names as $attribute_name) {
//				if ($attribute_name['count'] > 1) {
//					$this->error['warning'] = str_replace( '%1', $attribute_name['name'], $this->language->get( 'error_attribute_name' ) );
//					return false;
//				}
//			}
//		}
//
//		if (empty($this->request->post['data_sync_settings_use_filter_group_id'])) {
//			$filter_group_names = $this->model_tool_data_sync->getFilterGroupNameCounts();
//			foreach ($filter_group_names as $filter_group_name) {
//				if ($filter_group_name['count'] > 1) {
//					$this->error['warning'] = str_replace( '%1', $filter_group_name['name'], $this->language->get( 'error_filter_group_name' ) );
//					return false;
//				}
//			}
//		}
//
//		if (empty($this->request->post['data_sync_settings_use_filter_id'])) {
//			$filter_names = $this->model_tool_data_sync->getFilterNameCounts();
//			foreach ($filter_names as $filter_name) {
//				if ($filter_name['count'] > 1) {
//					$this->error['warning'] = str_replace( '%1', $filter_name['name'], $this->language->get( 'error_filter_name' ) );
//					return false;
//				}
//			}
//		}

		return true;
	}


	public function getNotifications() {
		sleep(1); // give the data some "feel" that its not in our system
		$this->load->model('tool/data_sync');
		$this->load->language( 'tool/data_sync' );
		$response = $this->model_tool_data_sync->getNotifications();
		$json = array();
		if ($response===false) {
			$json['message'] = '';
			$json['error'] = $this->language->get( 'error_notifications' );
		} else {
			$json['message'] = $response;
			$json['error'] = '';
		}
		$this->response->setOutput(json_encode($json));
	}
	
	
	
	
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['product_id']) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['product_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}
?>