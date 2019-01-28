<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//III. WMS Cloud Server  
// Start cron job here
// 3.3 UploadReceivingList
//Input: 
//Partner/Customer ID,
//Transaction ID (auto generated), 
//Row Number
//Product ID, 
//Quantity,
//Product Type (Atoz or 3rd Party)
//Status (Pending or Completed)

// 3.4 DownloadReceivingProgress 
//Input: 
//Partner/Customer ID,
//Transaction ID (allow query more than 1 transaction) 
//Row Number

// 3.5 UploadDeliveryList
//Input: 
//Partner/Customer ID,
//Recipient Name, 
//Recipient Address,
//Order ID, 
//Product ID, 
//Quantity,
//Product Type (Atoz or 3rd Party)

// http://gohofficekl.dyndns.org:8090/api/WMS/DownloadAvailableLocation
if(!defined("GOHOFFICE_WMS_API_URL")) {
	define('GOHOFFICE_WMS_API_URL', 'http://gohofficekl.dyndns.org:8090/');
}

error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set("memory_limit","-1");
ini_set('max_execution_time', 0);
date_default_timezone_set('Asia/Kuala_Lumpur');

//require_once("../config.php");
// Settings are varies for different partners
$mysql_host = "localhost";
$mysql_user = "root"; // root	root
$mysql_passwd = "dbbrpR00t16"; // root	dbbrpR00t16
$mysql_database = "opencart_db_1"; // atoz_opencart_db	brp_partner
$mysql_prefix = "oc_";
$partner_unique_id = "";
$partner_unique_name = "";
$partner_gohoffice_key = "";
$partner_gohoffice_url = "";

$isDebug = false;
$link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd);
if ($link) {
	$db_selected = mysql_select_db($mysql_database, $link);
	if ($db_selected) {
		
		echo "Running... <br /><br />";
		
		GetSettingData();
		
		if($partner_unique_id!="" && $partner_unique_name!="") {
													
			// 5.1 Gohoffice.com to e-Commerce Portals (1 way data transfer)
			GO2Ecommerce();
			echo "<br /><br />";

			// 5.2 e-Commerce Portals to Gohoffice.com (1 way data transfer)
			Ecommerce2GO();
			echo "<br /><br />";
			
			
			// 3.3 UploadReceivingList
			UploadReceivingList();
			echo "<br /><br />";
			
			// 3.4 DownloadReceivingProgress 
			DownloadReceivingProgress();
			echo "<br /><br />";

			// 3.5 UploadDeliveryList
			UploadDeliveryList();
			echo "<br /><br />";

			// 3.2 DownloadDeliveryProgress
			DownloadDeliveryProgress();
			echo "<br /><br />";
		
		}
		echo "<br />Completed...<br /><br />";
		
	} else {
		echo "Failed to connect to DB!<br />";
	}
} else {
	echo "Failed to connect to mysql!<br />";
}

function GetSettingData() {
	global $mysql_prefix;
	global $isDebug;
	global $partner_unique_id;
	global $partner_unique_name;
	global $partner_gohoffice_key;
	global $partner_gohoffice_url;
	
	$sql1 = "SELECT `value` FROM `".$mysql_prefix."setting` WHERE `code`='config' AND `key`='config_unique_brp_partner_id' LIMIT 1 ";
	$result1 = mysql_query($sql1);
	if($result1) {
		if($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
			$partner_unique_id = $row1["value"];
		}
	}
	$sql2 = "SELECT `value` FROM `".$mysql_prefix."setting` WHERE `code`='config' AND `key`='config_name' LIMIT 1 ";
	$result2 = mysql_query($sql2);
	if($result2) {
		if($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$partner_unique_name = $row2["value"];
		}
	}
	$sql3 = "SELECT `value` FROM `".$mysql_prefix."setting` WHERE `code`='config' AND `key`='config_gohoffice_sync_key' LIMIT 1 ";
	$result3 = mysql_query($sql3);
	if($result3) {
		if($row3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
			$partner_gohoffice_key = $row3["value"];
		}
	}
	$sql4 = "SELECT `value` FROM `".$mysql_prefix."setting` WHERE `code`='config' AND `key`='config_gohoffice_sync_url' LIMIT 1 ";
	$result4 = mysql_query($sql4);
	if($result4) {
		if($row4 = mysql_fetch_array($result4, MYSQL_ASSOC)) {
			$partner_gohoffice_url = $row4["value"];
		}
	}
}

function GO2Ecommerce() {
	global $mysql_prefix;
	global $isDebug;
	global $partner_unique_id;
	global $partner_unique_name;
	global $partner_gohoffice_key;
	global $partner_gohoffice_url;
	
	if($partner_gohoffice_key!="" && $partner_gohoffice_url!="") {
		// GohOffice Function Call - Start
		$apiParams = array(
		   "partner_unique_id"=>$partner_unique_id,
		   "partner_gohoffice_key"=>$partner_gohoffice_key,
		   "partner_gohoffice_url"=>$partner_gohoffice_url
		);
		
		$arrAPIResults = ajaxAPI("gohoffice_to_ecommerce", $apiParams);
		//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
		if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
			echo "GO2Ecommerce: ".$arrAPIResults["failure_msg"]."<br /><br />";
			//break;
		} if(isset($arrAPIResults["success_msg"]) && $arrAPIResults["success_msg"]!="") {
			echo "GO2Ecommerce: ".$arrAPIResults["success_msg"]."<br /><br />";
		}
		// GohOffice Function Call - End
	}
}

function Ecommerce2GO() {
	global $mysql_prefix;
	global $isDebug;
	global $partner_unique_id;
	global $partner_unique_name;
	global $partner_gohoffice_key;
	global $partner_gohoffice_url;
	
	if($partner_gohoffice_key!="" && $partner_gohoffice_url!="") {
		$sql1 = "SELECT 
			o.order_id, 
			o.unique_order_id, 
			o.upload_delivery_status, 
			CONCAT(o.firstname, ' ', o.lastname) AS customer, 
			(SELECT os.name FROM " . $mysql_prefix . "order_status os WHERE os.order_status_id = o.order_status_id) AS order_status, 
			'' AS model, 
			(SELECT SUM(op.quantity) FROM " . $mysql_prefix . "order_product op WHERE op.order_id = o.order_id) AS quantity, 
			(SELECT SUM(op.price) FROM " . $mysql_prefix . "order_product op WHERE op.order_id = o.order_id) AS price, 
			(SELECT oc.code FROM " . $mysql_prefix . "customer oc WHERE oc.customer_id = o.customer_id) AS customer_code, 
			
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
		FROM `".$mysql_prefix."order` o WHERE o.`upload_delivery_status`!='Completed' AND o.`order_status_id` > '0' AND o.`order_status_id` NOT IN ('5','7','23')
		AND o.`unique_order_id` != '' AND o.`upload_delivery_status` = 'Pending'
		ORDER BY o.`order_id` ASC ";
		$result1 = mysql_query($sql1);
		if($result1) {
		
			while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {	
				$intOrderID = $row1["order_id"];
				$sql2 = "SELECT 
					op.order_product_id, 
					op.name, 
					op.data_source, 
					op.model, 
					op.matching_code, 
					op.quantity, 
					op.price, 
					op.total
				FROM `" . $mysql_prefix . "order_product` op";
				$sql2 .= " WHERE op.order_id='".$intOrderID."' AND op.configure_delivery='Gohoffice'";
				$sql2 .= " ORDER BY op.order_product_id ASC";
				//echo $sql2;exit;
				
				$result2 = mysql_query($sql2);
				$num_rows = mysql_num_rows($result2);
				if($num_rows>0) {
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
					while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {	
						//echo "<pre>";print_r($row2);echo "</pre><br />";
						if($customer_id!="") {
							$order_id .= "|" . $row1['order_id'];
							$customer_id .= "|" . $partner_unique_id;
							$unique_order_id .= "|" . $row1['unique_order_id'];
							$order_date .= "|" . date("d/m/Y", strtotime($row1['date_added']));
							$recipient_code .= "|" . $row1['customer_code'];
							$recipient_name .= "|" . $row1['customer'];
							$recipient_tel .= "|" . $row1['telephone'];
							$recipient_add1 .= "|" . $row1['address1'];
							$recipient_add2 .= "|" . $row1['address2'];
							$recipient_add3 .= "|" . $row1['address3'];
							$recipient_add4 .= "|" . $row1['address4'];
							$recipient_add5 .= "|" . $row1['address5'];
							$item_code .= "|" . $row2['model'];
							$matching_code .= "|" . $row2['matching_code'];
							$order_qty .= "|" . $row2['quantity'];
							$recipient_companiename .= "|" . $row1['shipping_company'];
							$recipient_shipping_add1 .= "|" . $row1['shipping_address_1'];
							$recipient_shipping_add2 .= "|" . $row1['shipping_address_2'];
							$recipient_shipping_city .= "|" . $row1['shipping_city'];
							$recipient_shipping_postcode .= "|" . $row1['shipping_postcode'];
							$recipient_shipping_state .= "|" . $row1['shipping_zone'];
							$recipient_shipping_country .= "|" . $row1['shipping_country'];
							$recipient_firstname .= "|" . $row1['firstname'];
							$recipient_lastname .= "|" . $row1['lastname'];
							$recipient_email .= "|" . $row1['email'];
							$recipient_tel .= "|" . $row1['telephone'];
							$recipient_fax .= "|" . $row1['fax'];	
						} else {
							$order_id = $row1['order_id'];
							$customer_id = $partner_unique_id;
							$unique_order_id = $row1['unique_order_id'];
							$order_date = date("d/m/Y", strtotime($row1['date_added']));
							$recipient_code = $row1['customer_code'];
							$recipient_name = $row1['customer'];
							$recipient_tel = $row1['telephone'];
							$recipient_add1 = $row1['address1'];
							$recipient_add2 = $row1['address2'];
							$recipient_add3 = $row1['address3'];
							$recipient_add4 = $row1['address4'];
							$recipient_add5 = $row1['address5'];
							$item_code = $row2['model'];
							$matching_code = $row2['matching_code'];
							$order_qty = $row2['quantity'];
							$recipient_companiename = $row1['shipping_company'];
							$recipient_shipping_add1 = $row1['shipping_address_1'];
							$recipient_shipping_add2 = $row1['shipping_address_2'];
							$recipient_shipping_city = $row1['shipping_city'];
							$recipient_shipping_postcode = $row1['shipping_postcode'];
							$recipient_shipping_state = $row1['shipping_zone'];
							$recipient_shipping_country = $row1['shipping_country'];
							$recipient_firstname = $row1['firstname'];
							$recipient_lastname = $row1['lastname'];
							$recipient_email = $row1['email'];
							$recipient_tel = $row1['telephone'];
							$recipient_fax = $row1['fax'];							
						}
					}
					$apiParams = array(
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
					   "company_name"=>$recipient_companiename,
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
					$arrAPIResults = ajaxAPI("ecommerce_to_gohoffice", $apiParams);
					//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
					if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
						echo "Ecommerce2GO: ".$arrAPIResults["failure_msg"]."<br /><br />";
						//break;
					} if(isset($arrAPIResults["success_msg"]) && $arrAPIResults["success_msg"]!="") {
						echo "Ecommerce2GO: ".$arrAPIResults["success_msg"]."<br /><br />";
					}
				} else {
					//echo "UploadDeliveryList: Status is not specified for Order ID: $intOrderID.<br />";
				}
			}
		}
	}
}

function UploadReceivingList() {
	global $mysql_prefix;
	global $isDebug;
	global $partner_unique_id;
	global $partner_unique_name;
	global $partner_gohoffice_key;
	global $partner_gohoffice_url;
	
	$sql1 = "SELECT `id`, `transaction_no`, `date_added`, `received_datetime` FROM `".$mysql_prefix."stockmovement_headers` WHERE `ecowarehouse_sync_status`!='Completed' AND `ecowarehouse_sync_status`!='Cancelled' ORDER BY `id` ASC ";
	$result1 = mysql_query($sql1);
	if($result1) {
		while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {	
			$intHeaderID = $row1["id"];
			$strTranNo = $row1["transaction_no"];
			$dtTransactionDateTime = $row1["date_added"];
			$dtReceivedDateTime = $row1["received_datetime"];
			$sql2 = "SELECT * FROM " . $mysql_prefix . "stockmovement_details WHERE 1=1";
			$sql2 .= " AND transaction_id = '" . trim($intHeaderID) . "' ";
			$sql2 .= " GROUP BY id ORDER BY row_no ASC ";
			$result2 = mysql_query($sql2);
			while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {	
				//echo "<pre>";print_r($row2);echo "</pre>";
				$results[0] = $row2;
				//echo "<pre>";print_r($results);echo "</pre><br />";
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
				foreach ($results as $result) {
					//$sql3 = "SELECT DISTINCT * FROM " . $mysql_prefix . "product p WHERE p.product_id = '" . (int)$result["product_id"] . "' ";
					$sql3 = "SELECT DISTINCT p.*, pd.name as prod_name  FROM " . $mysql_prefix . "product p, " . $mysql_prefix . "product_description pd WHERE p.product_id = '" . (int)$result["product_id"] . "' AND p.product_id=pd.product_id ";
					$result3 = mysql_query($sql3);
					while($row3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
						$arrProductData = $row3;
						//echo "<pre>";print_r($arrProductData);echo "</pre><br />";
						if($customer_ids!="") {
							if($headerID=="") { $headerID = $intHeaderID; }
							$header_ids .= "|" . $intHeaderID;
							$detail_ids .= "|" . $result['id'];
							$customer_ids .= "|" . $partner_unique_id;
							$customer_names .= "|" . $partner_unique_name;
							$doc_nos .= "|" . $partner_unique_id . "@" . $strTranNo; // partnerID + delimiter + transaction number
							$doc_dates .= "|" . ""; //date("d/m/Y", strtotime($result['date_added']));
							$item_line_nos .= "|" . $result['row_no'];
							if(false&&$arrProductData['data_source']!="" && $arrProductData['data_source']!="0") {
								$item_codes .= "|" . $arrProductData['matching_code'];
							} else {
								$item_codes .= "|" . $arrProductData['model'];
							}
							$item_desc1s .= "|" . $arrProductData['prod_name']." : ".$result['remarks'];
							$item_desc2s .= "|" . "";
							$item_uoms .= "|" . $result['uom'];
							$item_contain_serials .= "|" . "N";
							$item_categorys .= "|" . "";
							$item_brands .= "|" . "";
							$item_barcodes .= "|" . (isset($arrProductData["upc"])&&$arrProductData["upc"]!=""?$arrProductData["upc"]:"");//constant("PARTNER_UNIQUE_ID") . "@" . $result['id'];
							$item_qtys .= "|" . $result['quantity'];
							$item_num_of_bins .= "|" . "";
						} else {
							$header_ids .= $intHeaderID;
							$detail_ids .= $result['id'];
							$customer_ids .= $partner_unique_id;
							$customer_names .= $partner_unique_name;
							$doc_nos .= $partner_unique_id . "@" . $strTranNo; // partnerID + delimiter + transaction number
							$doc_dates .= ""; //date("d/m/Y", strtotime($result['date_added']));
							$item_line_nos .= $result['row_no'];
							if(false&&$arrProductData['data_source']!="" && $arrProductData['data_source']!="0") {
								$item_codes .= $arrProductData['matching_code'];
							} else {
								$item_codes .= $arrProductData['model'];
							}
							$item_desc1s .= $arrProductData['prod_name']." : ".$result['remarks'];
							$item_desc2s .= "";
							$item_uoms .= $result['uom'];
							$item_contain_serials .= "N";
							$item_categorys .= "";
							$item_brands .= "";
							$item_barcodes .= (isset($arrProductData["upc"])&&$arrProductData["upc"]!=""?$arrProductData["upc"]:"");//constant("PARTNER_UNIQUE_ID") . "@" . $result['id'];
							$item_qtys .= $result['quantity'];
							$item_num_of_bins .= "";
						}
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
				);
				//echo "<pre>";print_r($apiParams);echo "</pre><br />";
				$arrAPIResults = ajaxAPI("upload_receiving_list", $apiParams);
				//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
				if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
					echo "UploadReceivingList: ".$arrAPIResults["failure_msg"]."<br /><br />";
					//break;
				} if(isset($arrAPIResults["failure_ignore_msg"]) && $arrAPIResults["failure_ignore_msg"]!="") {
					echo "UploadReceivingList: ".$arrAPIResults["failure_ignore_msg"]."<br /><br />";
				} if(isset($arrAPIResults["success_msg"]) && $arrAPIResults["success_msg"]!="") {
					echo "UploadReceivingList: ".$arrAPIResults["success_msg"]."<br /><br />";
				}
				// WMS Function Call - End
			}
			//echo "<br />";
		}
	}
}

function DownloadReceivingProgress() {
	global $mysql_prefix;
	global $isDebug;
	global $partner_unique_id;
	global $partner_unique_name;
	global $partner_gohoffice_key;
	global $partner_gohoffice_url;
	
	$sql1 = "SELECT `id`, `transaction_no` FROM `".$mysql_prefix."stockmovement_headers` WHERE `warehouseeco_sync_status`!='Completed' AND `warehouseeco_sync_status`!='Cancelled' ORDER BY `id` ASC ";
	$result1 = mysql_query($sql1);
	if($result1) {
		while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {	
			$intHeaderID = $row1["id"];
			$strTranNo = $row1["transaction_no"];
			// WMS Function Call - Start
			$header_ids = $intHeaderID;
			$customer_ids = $partner_unique_id;
			$doc_nos = $partner_unique_id . "@" . $strTranNo; // partnerID + delimiter + transaction number
			$apiParams = array(
			   "header_id"=>$header_ids, 
			   "customer_id"=>$customer_ids, 
			   "doc_no"=>$doc_nos
			);
			//echo "<pre>";print_r($apiParams);echo "</pre><br />";
			$arrAPIResults = ajaxAPI("download_receiving_progress", $apiParams);
			//echo "<pre>";print_r($arrAPIResults);echo "</pre>";exit;
			if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
				echo "DownloadReceivingProgress: ".$arrAPIResults["failure_msg"]."<br /><br />";
				//break;
			} if(isset($arrAPIResults["failure_ignore_msg"]) && $arrAPIResults["failure_ignore_msg"]!="") {
				echo "DownloadReceivingProgress: ".$arrAPIResults["failure_ignore_msg"]."<br /><br />";
			} if(isset($arrAPIResults["success_msg"]) && $arrAPIResults["success_msg"]!="") {
				echo "DownloadReceivingProgress: ".$arrAPIResults["success_msg"]."<br /><br />";
			}
			// WMS Function Call - End
		}
	}
}

function UploadDeliveryList() {
	global $mysql_prefix;
	global $isDebug;
	global $partner_unique_id;
	global $partner_unique_name;
	global $partner_gohoffice_key;
	global $partner_gohoffice_url;
	
	$sql1 = "SELECT 
		o.order_id, 
		o.unique_order_id, 
		o.upload_delivery_status, 
		CONCAT(o.firstname, ' ', o.lastname) AS customer, 
		(SELECT os.name FROM " . $mysql_prefix . "order_status os WHERE os.order_status_id = o.order_status_id) AS order_status, 
		'' AS model, 
		(SELECT SUM(op.quantity) FROM " . $mysql_prefix . "order_product op WHERE op.order_id = o.order_id) AS quantity, 
		(SELECT SUM(op.price) FROM " . $mysql_prefix . "order_product op WHERE op.order_id = o.order_id) AS price, 
		(SELECT oc.code FROM " . $mysql_prefix . "customer oc WHERE oc.customer_id = o.customer_id) AS customer_code, 
		
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
	FROM `".$mysql_prefix."order` o WHERE o.`upload_delivery_status`!='Completed' AND o.`order_status_id` > '0' AND o.`order_status_id` NOT IN ('5','7','23')
	AND o.`unique_order_id` != '' AND o.`upload_delivery_status` = 'Pending'
	ORDER BY o.`order_id` ASC ";
	$result1 = mysql_query($sql1);
	if($result1) {
	
		while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {	
			$intOrderID = $row1["order_id"];
			$sql2 = "SELECT 
				op.order_product_id, 
				op.name, 
				op.data_source, 
				op.model, 
				op.matching_code, 
				op.quantity, 
				op.price, 
				op.total
			FROM `" . $mysql_prefix . "order_product` op";
			$sql2 .= " WHERE op.order_id='".$intOrderID."' AND op.configure_delivery='BRP Warehouse'";
			$sql2 .= " ORDER BY op.order_product_id ASC";
			//echo $sql2;exit;
			
			$result2 = mysql_query($sql2);
			$num_rows = mysql_num_rows($result2);
			if($num_rows>0) {
				while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {	
					//echo "<pre>";print_r($row2);echo "</pre><br />";
					// WMS Function Call - Start
					$order_ids = $row1['order_id'];
					$customer_ids = $partner_unique_id;
					$unique_order_ids = $row1['unique_order_id'];
					$order_dates = date("d/m/Y", strtotime($row1['date_added']));
					$recipient_codes = $row1['customer_code'];
					$recipient_names = $row1['customer'];
					$recipient_tels = $row1['telephone'];
					$recipient_add1s = $row1['address1'];
					$recipient_add2s = $row1['address2'];
					$recipient_add3s = $row1['address3'];
					$recipient_add4s = $row1['address4'];
					$recipient_add5s = $row1['address5'];
					if(false&&$row2['data_source']!="" && $row2['data_source']!="0") {
						$item_codes = $row2['matching_code'];
					} else {
						$item_codes = $row2['model'];
					}
					//$item_codes = $row2['model'];
					$order_qtys = $row2['quantity'];
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
					   "order_qty"=>$order_qtys
					);
					$arrAPIResults = ajaxAPI("upload_delivery_status", $apiParams);
					if(isset($arrAPIResults["failure_msg"]) && $arrAPIResults["failure_msg"]!="") {
						echo "UploadDeliveryList: ".$arrAPIResults["failure_msg"]."<br /><br />";
						//break;
					} if(isset($arrAPIResults["success_msg"]) && $arrAPIResults["success_msg"]!="") {
						echo "UploadDeliveryList: ".$arrAPIResults["success_msg"]."<br /><br />";
					}
					// WMS Function Call - End
				}
			} else {
				//echo "UploadDeliveryList: Status is not specified for Order ID: $intOrderID.<br />";
			}
		}
	}
}

function DownloadDeliveryProgress() {
	global $mysql_prefix;
	global $isDebug;
	global $partner_unique_id;
	global $partner_unique_name;
	global $partner_gohoffice_key;
	global $partner_gohoffice_url;
	
	$sql1 = "SELECT 
		o.order_id, 
		o.unique_order_id, 
		o.upload_delivery_status, 
		CONCAT(o.firstname, ' ', o.lastname) AS customer, 
		(SELECT os.name FROM " . $mysql_prefix . "order_status os WHERE os.order_status_id = o.order_status_id) AS order_status, 
		'' AS model, 
		(SELECT SUM(op.quantity) FROM " . $mysql_prefix . "order_product op WHERE op.order_id = o.order_id) AS quantity, 
		(SELECT SUM(op.price) FROM " . $mysql_prefix . "order_product op WHERE op.order_id = o.order_id) AS price, 
		(SELECT oc.code FROM " . $mysql_prefix . "customer oc WHERE oc.customer_id = o.customer_id) AS customer_code, 
		
		o.telephone AS telephone,  
		
		CASE WHEN o.shipping_company != '' THEN concat(o.shipping_company, '\r\n', o.shipping_address_1) 
        ELSE o.shipping_address_1 END AS address1,
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
	FROM `".$mysql_prefix."order` o WHERE o.`upload_delivery_status`!='Completed' AND o.`order_status_id` > '0' AND o.`order_status_id` NOT IN ('5','7','23')
	AND o.`unique_order_id` != '' AND o.`upload_delivery_status` = 'Pending'
	ORDER BY o.`order_id` ASC ";
	$result1 = mysql_query($sql1);
	if($result1) {
	
		while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {	
			$intOrderID = $row1["order_id"];
			$sql2 = "SELECT 
				op.order_product_id, 
				op.name, 
				op.data_source, 
				op.model, 
				op.matching_code, 
				op.quantity, 
				op.price, 
				op.total
			FROM `" . $mysql_prefix . "order_product` op";
			$sql2 .= " WHERE op.order_id='".$intOrderID."'";
			$sql2 .= " ORDER BY op.order_product_id ASC";
			//echo $sql2;exit;
			
			$result2 = mysql_query($sql2);
			$num_rows = mysql_num_rows($result2);
			if($num_rows>0) {
				while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {	
					//echo "<pre>";print_r($row2);echo "</pre><br />";
					// WMS Function Call - Start
					$unique_order_ids = $row1['unique_order_id'];
					$customer_ids = $partner_unique_id;
					$apiParams = array(
					   "unique_order_id"=>$unique_order_ids, 
					   "customer_id"=>$customer_ids
					);
					$arrAPIResults = ajaxAPI("download_delivery_status", $apiParams);
					if(isset($arrAPIResults["success_msg"]) && $arrAPIResults["success_msg"]!="") {
						echo "DownloadDeliveryProgress: ".$arrAPIResults["success_msg"]."<br /><br />";
					}
					// WMS Function Call - End
				}
			} else {
				//echo "UploadDeliveryList: Status is not specified for Order ID: $intOrderID.<br />";
			}
		}
	}
}

function isJSON($string){
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}
function generateRunningNo($id){
	global $mysql_prefix;
	$no = "";
	if($id != ""){
		$sql = "SELECT * FROM `".$mysql_prefix."running_number` WHERE `module_uid`='".$id."' LIMIT 1";
		$query = mysql_query($sql);
		while($result = mysql_fetch_array($query, MYSQL_ASSOC)) {	
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
function checkIsUniqueData($value) {
	global $mysql_prefix;
	$sql = "SELECT COUNT(*) AS total FROM `".$mysql_prefix."stockmovement_headers` WHERE transaction_no = '" . $value . "'";
	$query = mysql_query($sql);
	$total_item_lines = 0;
	if($result = mysql_fetch_array($query, MYSQL_ASSOC)) {
		$total_item_lines = $result["total"];
	}
	//echo $total_item_lines;exit;
	if($total_item_lines==0) {
		return true;
	} else {
		return false;	
	}
}
function updateRunningNo($id){
	global $mysql_prefix;
	$sql = "SELECT * FROM `".$mysql_prefix."running_number` WHERE `module_uid`='".$id."'";
	$query = mysql_query($sql);
	while($result = mysql_fetch_array($query, MYSQL_ASSOC)) {
		$current = $result['current'] + 1;
	}
	if(isset($current)){
		$sql = "UPDATE `oc_running_number` SET `current`='".$current."' WHERE `module_uid`='".$id."'";
		mysql_query($sql);
	}
}

function ajaxAPI($apitype = '', $params) {
	global $mysql_prefix;
	global $isDebug;
	global $partner_unique_id;
	global $partner_unique_name;
	global $partner_gohoffice_key;
	global $partner_gohoffice_url;
	$array = array();
	
	if($apitype=="upload_receiving_list") {
		if (isset($params['header_id'])) { $header_id = $params['header_id']; }
		
		if (isset($params['detail_id'])) { $detail_id = $params['detail_id']; }
		
		if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
		
		if (isset($params['customer_name'])) { $customer_name = $params['customer_name']; }
		
		if (isset($params['doc_no'])) { $doc_no = $params['doc_no']; }
		
		if (isset($params['doc_date'])) { $doc_date = $params['doc_date']; }
		
		if (isset($params['item_line_no'])) { $item_line_no = $params['item_line_no']; }
		
		if (isset($params['item_code'])) { $item_code = $params['item_code']; }
		
		if (isset($params['item_desc1'])) { $item_desc1 = $params['item_desc1']; }
		
		if (isset($params['item_desc2'])) { $item_desc2 = $params['item_desc2']; }
		
		if (isset($params['item_uom'])) { $item_uom = $params['item_uom']; }
		
		if (isset($params['item_contain_serial'])) { $item_contain_serial = $params['item_contain_serial']; }
		
		if (isset($params['item_category'])) { $item_category = $params['item_category']; }
		
		if (isset($params['item_brand'])) { $item_brand = $params['item_brand']; }
		
		if (isset($params['item_barcode'])) { $item_barcode = $params['item_barcode']; }
		
		if (isset($params['item_qty'])) { $item_qty = $params['item_qty']; }
		
		if (isset($params['item_num_of_bin'])) { $item_num_of_bin = $params['item_num_of_bin']; }
		
		$header_ids = ""; $detail_ids = ""; $customer_ids = ""; $customer_names = ""; $doc_nos = ""; $doc_dates = ""; $item_line_nos = ""; $item_codes = ""; $item_desc1s = "";
		$item_desc2s = ""; $item_uoms = ""; $item_contain_serials = ""; $item_categorys = ""; $item_brands = ""; $item_barcodes = ""; $item_qtys = ""; $item_num_of_bins = "";
		
		if($header_id!="") {
			$header_ids = explode("|",$header_id);
			$header_ids = array_unique($header_ids);
			foreach($header_ids as $keyHeader => $valueHeader) {
				$sql1 = "SELECT DISTINCT * FROM " . $mysql_prefix . "stockmovement_headers WHERE id = '" . (int)$valueHeader . "' ";
				$result1 = mysql_query($sql1);
				while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
					$arrHeaderData = $row1;
					//echo "<pre>";print_r($arrHeaderData);echo "</pre>";exit;
					
					//DownloadAvailableLocation API - Start
					//$path_url = "http://localhost:8080/atoz_opencart/download_available_location.txt"; // Local
					$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadAvailableLocation"; // Live
					//echo $path_url;exit;
					//echo "<pre>";print_r($path_url);echo "</pre>";exit;

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
					if(isJSON($resp)) {
						$resp = json_decode($resp, true);
					}
					$arrAvailableBinData = $resp;
					//echo "<pre>";print_r($arrAvailableBinData["TSHTable"][0]["NumOfLoc"]);echo "</pre>";exit;
					$intAvailableBin = (isset($arrAvailableBinData["TSHTable"][0]["NumOfLoc"])?$arrAvailableBinData["TSHTable"][0]["NumOfLoc"]:"0");
					// DownloadAvailableLocation API - End
					if($intAvailableBin==0) { 
						$array["failure_msg"] = "Insufficient number of bins for storage.";
						break;
					}
					if($arrHeaderData["num_of_bin"]=="" || !is_numeric($arrHeaderData["num_of_bin"])) {
						$arrHeaderData["num_of_bin"] = "0";	
					}
					if($intAvailableBin<$arrHeaderData["num_of_bin"]) { 
						$array["failure_ignore_msg"] = "Insufficient number of bins for storage.";
					}
					$detail_ids = explode("|",$detail_id);
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
					$dataJS = array();
					foreach($customer_ids as $key => $value) {
						$dataJS["TSHTable"][$key]["CustCode"] = (isset($customer_ids[$key])?$customer_ids[$key]:"");
						$dataJS["TSHTable"][$key]["CustName"] = (isset($customer_names[$key])?addslashes($customer_names[$key]):"");
						$dataJS["TSHTable"][$key]["Docno"] = (isset($doc_nos[$key])?$doc_nos[$key]:"");
						$dataJS["TSHTable"][$key]["DocDate"] = date("d/m/Y", strtotime($arrHeaderData["date_added"])); //(isset($doc_dates[$key])?$doc_dates[$key]:"");
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
					}
					$strJson = json_encode($dataJS);
					
					//Query WMS Cloud Server - Start
					//UploadDeliveryList
					//$path_url = "http://localhost:8080/atoz_opencart/upload_receiving_list_return1.txt?js=".$strJson.""; // Local
					$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/UploadReceivingList?js=".$strJson.""; // Live
					echo $path_url."<br />";//exit;
					
					if(!$isDebug) {
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
						//$resp = "\"Success\"";
						$resp = json_decode($resp, true);
						if(isJSON($resp)) {
							$resp = json_decode($resp, true);
						}
						//$arrData = json_decode($resp, true);
						//echo $resp;exit;
						$new_header_ids = array($valueHeader);
						if($resp=="Success") {
							$array["success_msg"] = "Transaction successfully saved"; //$resp;
							mysql_query("UPDATE `" . $mysql_prefix . "stockmovement_headers` SET ecowarehouse_sync_status = 'Completed', ecowarehouse_sync_timest = '".date("Y-m-d H:i:s")."' WHERE id IN ('" . implode("','",$new_header_ids) . "')");
						} else {
							if(!is_array($resp)) {
								$array["failure_msg"] = $resp;
							} else {
								$array["failure_msg"] = (isset($resp["Message"])?$resp["Message"]:"An error has occurred.");
							}
							mysql_query("UPDATE `" . $mysql_prefix . "stockmovement_headers` SET ecowarehouse_sync_status = 'Pending', ecowarehouse_sync_timest = '".date("Y-m-d H:i:s")."' WHERE id IN ('" . implode("','",$new_header_ids) . "')");
						}
					}
					//sleep(2);
					//usleep(500000); // 1 sec is 1000000
				}
			}
		}
		
	} else if($apitype=="download_receiving_progress") {
		
		if (isset($params['header_id'])) { $header_id = $params['header_id']; }
		
		if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
		
		if (isset($params['doc_no'])) { $doc_no = $params['doc_no']; }
		
		$header_ids = ""; $customer_ids = ""; $doc_nos = "";
		
		$header_ids = explode("|",$header_id); $header_ids = array_unique($header_ids);
		$customer_ids = explode("|",$customer_id);
		$doc_nos = explode("|",$doc_no);
		foreach($header_ids as $keyHeader => $valueHeader) {
			
			$sql1 = "SELECT DISTINCT * FROM " . $mysql_prefix . "stockmovement_headers WHERE id = '" . (int)$valueHeader . "' ";
			$result1 = mysql_query($sql1);
			while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
				$arrHeaderData = $row1;
				//echo "<pre>";print_r($arrHeaderData);echo "</pre>";exit;
				
				//$item_num_of_bins = explode("|",$item_num_of_bin);
				$dataJS = array();
				foreach($customer_ids as $key => $value) {
					$dataJS["TSHTable"][$key]["CustCode"] = (isset($customer_ids[$key])?$customer_ids[$key]:"");
					$dataJS["TSHTable"][$key]["Docno"] = (isset($doc_nos[$key])?$doc_nos[$key]:"");
				}
				$strJson = json_encode($dataJS);
				
				//Query WMS Cloud Server - Start
				//DownloadReceivingProgress
				//$path_url = "http://localhost:8080/atoz_opencart/download_receiving_progress_return1.txt?js=".$strJson.""; // Local
				$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadReceivingProgress?js=".$strJson.""; // Live
				echo $path_url."<br />";//exit;
				
				if(!$isDebug) {
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
					if(isJSON($resp)) {
						$resp = json_decode($resp, true);
					}
					$arrData = $resp;
					//echo "<pre>";print_r($doc_nos);echo "</pre>";//exit;
					if(isset($arrData["TSHTable"]) && count($arrData["TSHTable"])>0) {
						foreach($arrData["TSHTable"] as $key => $value) {
							if(isset($header_ids[$key])) {
								$new_header_ids = array($header_ids[$key]);
								if($doc_nos[$key] == $value["Docno"] && $value["ProgressStatus"] == "Completed") {
									$array["success_msg"] = "Transaction successfully saved";
									mysql_query("UPDATE `" . $mysql_prefix . "stockmovement_headers` SET warehouseeco_sync_status = '".$value["ProgressStatus"]."', warehouseeco_sync_timest = '".date("Y-m-d H:i:s")."', actual_num_of_bin = '".$value["NumOfBin"]."' WHERE id IN ('" . implode("','",$new_header_ids) . "')");
									//$this->model_warehouse_transactions->updateDownloadReceivingProgressFromWarehouse("1", $new_header_ids);
								} else {
									$array["failure_msg"] = "Transaction has failed to save";
									mysql_query("UPDATE `" . $mysql_prefix . "stockmovement_headers` SET warehouseeco_sync_status = '".$value["ProgressStatus"]."', warehouseeco_sync_timest = '".date("Y-m-d H:i:s")."', actual_num_of_bin = '".$value["NumOfBin"]."' WHERE id IN ('" . implode("','",$new_header_ids) . "')");
									//$this->model_warehouse_transactions->updateDownloadReceivingProgressFromWarehouse("0", $new_header_ids);
								}
							} else {
								$array["failure_msg"] = "Transaction has failed to save";
							}
						}
					} else {
						$array["failure_msg"] = "Transaction has failed to save";
					}
				}
			}
		}
	} else if($apitype=="upload_delivery_status") {
		
		if (isset($params['order_id'])) { $order_id = $params['order_id']; }
		
		if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
		
		if (isset($params['unique_order_id'])) { $unique_order_id = $params['unique_order_id']; }
		
		if (isset($params['order_date'])) { $order_date = $params['order_date']; }
		
		if (isset($params['recipient_code'])) { $recipient_code = $params['recipient_code']; }
		
		if (isset($params['recipient_name'])) { $recipient_name = $params['recipient_name']; }
		
		if (isset($params['recipient_tel'])) { $recipient_tel = $params['recipient_tel']; }
		
		if (isset($params['recipient_add1'])) { $recipient_add1 = $params['recipient_add1']; }
		
		if (isset($params['recipient_add2'])) { $recipient_add2 = $params['recipient_add2']; }
		
		if (isset($params['recipient_add3'])) { $recipient_add3 = $params['recipient_add3']; }
		
		if (isset($params['recipient_add4'])) { $recipient_add4 = $params['recipient_add4']; }

		if (isset($params['recipient_add5'])) { $recipient_add5 = $params['recipient_add5']; }
		
		if (isset($params['item_code'])) { $item_code = $params['item_code']; }
		
		if (isset($params['order_qty'])) { $order_qty = $params['order_qty']; }
					
		$order_ids = ""; $customer_ids = ""; $unique_order_ids = ""; $order_dates = ""; $recipient_codes = ""; $recipient_names = ""; $recipient_tels = "";
		$recipient_add1s = ""; $recipient_add2s = ""; $recipient_add3s = ""; $recipient_add4s = ""; $recipient_add5s = ""; $item_codes = ""; $order_qtys = "";
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
		}
		$strJson = json_encode($dataJS);
		//UploadDeliveryList
		//$path_url = "http://localhost:8080/atoz_opencart/upload_delivery_list_return1.txt?js=".$strJson.""; // Local
		$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/UploadDeliveryList?js=".$strJson.""; // Live
		echo $path_url."<br />";//exit;
		
		if(!$isDebug) {
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
			//$arrData = json_decode($resp, true);
			
			$resp = json_decode($resp, true);
			if(isJSON($resp)) {
				$resp = json_decode($resp, true);
			}
			//echo "<pre>";print_r($resp);echo "</pre>";exit;
			
			//echo "<pre>";print_r($order_ids);echo "</pre>";
			if($resp=="Success") {
				$array["success_msg"] = "Successfully uploaded delivery list"; //$resp;
				mysql_query("UPDATE `" . $mysql_prefix . "order` SET upload_delivery_status = 'Completed' WHERE order_id IN ('" . implode("','",$order_ids) . "')");
				//$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Completed", $order_ids);
			} else {
				if(!is_array($resp)) {
					$array["failure_msg"] = $resp;
				} else {
					$array["failure_msg"] = (isset($resp["Message"])?$resp["Message"]:"An error has occurred.");
				}
				mysql_query("UPDATE `" . $mysql_prefix . "order` SET upload_delivery_status = 'Pending' WHERE order_id IN ('" . implode("','",$order_ids) . "')");
				//$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Pending", $order_ids);
			}
		}
	} else if($apitype=="download_delivery_status") {
		
		if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
		
		if (isset($params['unique_order_id'])) { $unique_order_id = $params['unique_order_id']; }

		$customer_ids = ""; $unique_order_ids = "";
		$customer_ids = explode("|",$customer_id);
		$unique_order_ids = explode("|",$unique_order_id);
		$dataJS = array();
		foreach($customer_ids as $key => $value) {
			$dataJS["TSHTable"][$key]["CustCode"] = (isset($customer_ids[$key])?$customer_ids[$key]:"");
			$dataJS["TSHTable"][$key]["OrderID"] = (isset($unique_order_ids[$key])?$unique_order_ids[$key]:"");
		}
		$strJson = json_encode($dataJS);
		//UploadDeliveryList
		//$path_url = "http://localhost:8080/atoz_opencart/upload_delivery_list_return1.txt?js=".$strJson.""; // Local
		$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/DownloadDeliveryProgress?js=".$strJson.""; // Live
		echo $path_url."<br />";//exit;
		
		if(!$isDebug) {
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
			//$arrData = json_decode($resp, true);
			
			$resp = json_decode($resp, true);
			if(isJSON($resp)) {
				$resp = json_decode($resp, true);
			}
			//echo "<pre>";print_r($resp);echo "</pre>";exit;
			$arrData = $resp;
			$arrData = (isset($arrData["TSHTable"])?$arrData["TSHTable"]:array());
			//{"TSHTable":[{"Docno":"partUnique1234@1","Status":"Completed","NoOfLoc":"2","DateLastUpdated":"13\/02\/2017"}]}
			//$arrData[] = array("OrderID"=>"partUnique1234@1", "OrderStatus"=>"Draft");
			
			$arrTempData = array();
			$strMsg = "No Order Status updated.";
			if(is_array($arrData) && count($arrData)>0) {
				foreach($arrData as $key => $value) {
					$arrTempData[$value["OrderID"]] = $value["OrderStatus"];
					$arrOrderIDs = explode("@", $value["OrderID"]);
					if($value["OrderStatus"]=="Draft") {
						//$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '21' WHERE order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
						mysql_query("UPDATE `" . $mysql_prefix . "order` SET order_status_id = '21' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
						$strMsg = "Successfully update Order Status to Draft.";
					} else if($value["OrderStatus"]=="Packing") {
						//$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '22' WHERE order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
						mysql_query("UPDATE `" . $mysql_prefix . "order` SET order_status_id = '22' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
						$strMsg = "Successfully update Order Status to Packing.";
					} else if($value["OrderStatus"]=="Shipment") {
						//$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '23' WHERE order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
						mysql_query("UPDATE `" . $mysql_prefix . "order` SET order_status_id = '23' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
						$strMsg = "Successfully update Order Status to Shipment.";
					} else {
						//$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '1' WHERE order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
						//mysql_query("UPDATE `" . $mysql_prefix . "order` SET order_status_id = '1' WHERE order_status_id NOT IN ('5','7') AND order_id = '" . (isset($arrOrderIDs[1])?$arrOrderIDs[1]:"0") . "'");
					}
				}
			}
			
			$array["success_msg"] = $strMsg; //$resp;
		}
	} else if($apitype=="gohoffice_to_ecommerce") {

		$partner_unique_id = "";
		if (isset($params['partner_unique_id'])) { $partner_unique_id = $params['partner_unique_id']; }
		
		$partner_gohoffice_key = "";
		if (isset($params['partner_gohoffice_key'])) { $partner_gohoffice_key = $params['partner_gohoffice_key']; }
		
		$partner_gohoffice_url = "";
		if (isset($params['partner_gohoffice_url'])) { $partner_gohoffice_url = $params['partner_gohoffice_url']; }
		
		// http://localhost:8080/gohoffice/staging/cronjob/brp_partner_sync.php?key=Yu5aQwjzxZsBK6tZ&sync=storage&partner=BRPTester
		$path_url = $partner_gohoffice_url."?key=".$partner_gohoffice_key."&sync=storage&partner=".$partner_unique_id."";
		echo $path_url."<br />";//exit;
		
		if(!$isDebug) {
			// Gohoffice API
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
			//$arrData = json_decode($resp, true);
			
			$resp = json_decode($resp, true);
			if(isJSON($resp)) {
				$resp = json_decode($resp, true);
			}
			//echo "<pre>";print_r($resp);echo "</pre>";exit;
			$arrData = $resp;
			$arrData = (isset($arrData["data"])?$arrData["data"]:array());
			//{"TSHTable":[{"Docno":"partUnique1234@1","Status":"Completed","NoOfLoc":"2","DateLastUpdated":"13\/02\/2017"}]}
			//$arrData[] = array("OrderID"=>"partUnique1234@1", "OrderStatus"=>"Draft");
			//echo "<pre>";print_r($arrData);echo "</pre>";exit;
			
			$arrOrderIDs = array();
			$arrTransIDs = array();
			
			$strMsg = "";
			$intCreatedStorage = 0;
			if(is_array($arrData) && count($arrData)>0) {
				//echo "<pre>";print_r(count($arrData));echo "</pre>";exit;
				foreach($arrData as $arrDataKey => $arrDataValue) {
					//echo "<pre>";print_r($arrDataValue);echo "</pre>";exit;
					//Check is unique transaction no - Start
					$maxTry = 0;
					$strTransactionNo = generateRunningNo('Storage');
					while($strTransactionNo!="" && !checkIsUniqueData($strTransactionNo)){
						updateRunningNo('Storage');
						$strTransactionNo = generateRunningNo('Storage');
						$maxTry++;
						if($maxTry>1000){break;}
					}
					//echo $strTransactionNo;exit;
					//Check is unique transaction no - End
					
					$arrHeaderData = array();
					$arrHeaderData[] = "";
					mysql_query(
						"INSERT INTO `" . $mysql_prefix . "stockmovement_headers` 
						(
							`transaction_type`, `transaction_no`, `remarks`, `total_item_lines`, 
							`received_datetime`, `num_of_bin`, `actual_num_of_bin`, `status`, 
							`ecowarehouse_sync_status`, `ecowarehouse_sync_timest`, `warehouseeco_sync_status`, `warehouseeco_sync_timest`, `date_added`
						)
						VALUES
						(
							'Warehouse', '".$strTransactionNo."', 'Gohoffice.com Order ID: ".$arrDataKey."', '".count($arrDataValue)."',
							'".date("Y-m-d H:i:s", strtotime("+7 day", time()))."', '1', '', '1',
							'-', '', '-', '', '".date('Y-m-d H:i:s')."'
						)"
					);
					$intLastID = mysql_insert_id();
					//echo $intLastID;exit;
					$innerCount = 0;
					
					$arrOrderIDs[] = $arrDataKey;
					$arrTransIDs[] = $strTransactionNo;
					foreach($arrDataValue as $key => $arrOrderData) {
						foreach($arrOrderData as $innerKey => $innerValue) {
							//echo "<pre>";print_r($innerValue);echo "</pre>";exit;
							// Pre Checking - Start
							$intProductID = $innerValue["product_id"];
							$strProductName = $innerValue["product_name"];
							$strModel = $innerValue["product_code"];
							$strProductType = "Third Party";
							$strMatchingCode = $innerValue["product_model"]; //$innerValue["product_model"];
							$sqlPre = "SELECT * FROM `" . $mysql_prefix . "product` WHERE `matching_code`='".$innerValue["product_code"]."' LIMIT 1";
							$resultPre = mysql_query($sqlPre);
							if(mysql_num_rows($resultPre) > 0) {
								if($rowPre = mysql_fetch_array($resultPre, MYSQL_ASSOC)) {
									$intProductID = $rowPre["product_id"];
									//$strProductName = "";
									$strModel = $rowPre["model"];
									$strProductType = "BRP";
									$strMatchingCode = $rowPre["matching_code"];
								}						
							}
							// Pre Checking - End
							mysql_query(
								"INSERT INTO `" . $mysql_prefix . "stockmovement_details` 
								(
									`transaction_id`, `row_no`, `product_id`, `product_name`, 
									`product_model`, `product_type`, `matching_code`, `quantity`, 
									`uom`, `remarks`, `product_status`, `sync_status`, `date_added`
								)
								VALUES
								(
									'".$intLastID."', '".($innerCount+1)."', '".$intProductID."', '".$strProductName."',
									'".$strModel."', '".$strProductType."', '".$strMatchingCode."', '".$innerValue["quantity"]."',
									'', '', '1', 'Pending', '".date('Y-m-d H:i:s')."'
								)"
							);
							$innerCount++;
						}
					}
					$intCreatedStorage++;
				}
			}
			
			// Hide - Start
			if(isset($arrOrderIDs) && is_array($arrOrderIDs) && count($arrOrderIDs)>0) {
				//$arrOrderIDs = array_unique($arrOrderIDs);
				//$arrTransIDs = array_unique($arrTransIDs);
				//echo "<pre>";print_r($arrOrderIDs);echo "</pre>";exit;
				$path_url = $partner_gohoffice_url."?key=".$partner_gohoffice_key."&sync=storage_hide_orders&partner=".$partner_unique_id."&ids=".implode(",",$arrOrderIDs)."&trans=".implode(",",$arrTransIDs)."";
				//$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/UpdateLocationRentalSyncStatus?js=".$strJson.""; // Live
				echo $path_url;echo "<br />";//exit;
				// Gohoffice API
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
				// Gohoffice - End
			}
			
			if($intCreatedStorage>0) {
				$strMsg = "Successfully created ".$intCreatedStorage." storage";
			}
			$array["success_msg"] = $strMsg; //$resp;
		}
	} else if($apitype=="ecommerce_to_gohoffice") {

		if (isset($params['order_id'])) { $order_id = $params['order_id']; }
		
		if (isset($params['customer_id'])) { $customer_id = $params['customer_id']; }
		
		if (isset($params['unique_order_id'])) { $unique_order_id = $params['unique_order_id']; }
		
		if (isset($params['order_date'])) { $order_date = $params['order_date']; }
		
		if (isset($params['recipient_code'])) { $recipient_code = $params['recipient_code']; }
		
		if (isset($params['recipient_name'])) { $recipient_name = $params['recipient_name']; }
		
		if (isset($params['recipient_tel'])) { $recipient_tel = $params['recipient_tel']; }
		
		if (isset($params['recipient_add1'])) { $recipient_add1 = $params['recipient_add1']; }
		
		if (isset($params['recipient_add2'])) { $recipient_add2 = $params['recipient_add2']; }
		
		if (isset($params['recipient_add3'])) { $recipient_add3 = $params['recipient_add3']; }
		
		if (isset($params['recipient_add4'])) { $recipient_add4 = $params['recipient_add4']; }

		if (isset($params['recipient_add5'])) { $recipient_add5 = $params['recipient_add5']; }
		
		if (isset($params['item_code'])) { $item_code = $params['item_code']; }
		
		if (isset($params['matching_code'])) { $matching_code = $params['matching_code']; }
		
		if (isset($params['order_qty'])) { $order_qty = $params['order_qty']; }
		
		if (isset($params['company_name'])) { $recipient_companyname = $params['company_name']; }
		
		if (isset($params['recipient_shipping_add1'])) { $recipient_shipping_add1 = $params['recipient_shipping_add1']; }
		
		if (isset($params['recipient_shipping_add2'])) { $recipient_shipping_add2 = $params['recipient_shipping_add2']; }
		
		if (isset($params['recipient_shipping_city'])) { $recipient_shipping_city = $params['recipient_shipping_city']; }
		
		if (isset($params['recipient_shipping_postcode'])) { $recipient_shipping_postcode = $params['recipient_shipping_postcode']; }
		
		if (isset($params['recipient_shipping_state'])) { $recipient_shipping_state = $params['recipient_shipping_state']; }
		
		if (isset($params['recipient_shipping_country'])) { $recipient_shipping_country = $params['recipient_shipping_country']; }
		
		if (isset($params['first_name'])) { $recipient_firstname = $params['first_name']; }
		
		if (isset($params['last_name'])) { $recipient_lastname = $params['last_name']; }
		
		if (isset($params['email'])) { $recipient_email = $params['email']; }
		
		if (isset($params['tel'])) { $recipient_tel = $params['tel']; }
		
		if (isset($params['fax'])) { $recipient_fax = $params['fax']; }
				  
		$order_ids = ""; $customer_ids = ""; $unique_order_ids = ""; $order_dates = ""; $recipient_codes = ""; $recipient_names = ""; $recipient_tels = "";
		$recipient_add1s = ""; $recipient_add2s = ""; $recipient_add3s = ""; $recipient_add4s = ""; $recipient_add5s = ""; $item_codes = ""; $matching_codes = ""; $order_qtys = "";
		$recipient_companynames = ""; $recipient_shipping_add1s = ""; $recipient_shipping_add2s = ""; $recipient_shipping_citys = ""; $recipient_shipping_postcodes = ""; $recipient_shipping_states = ""; $recipient_shipping_countrys = ""; $recipient_firstnames = ""; $recipient_lastnames = ""; $recipient_emails = ""; $recipient_tels = ""; $recipient_faxs = "";
		
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
		
		//$path_url = constant("GOHOFFICE_WMS_API_URL")."api/WMS/UploadDeliveryList?js=".$strJson.""; // Live
		// http://localhost:8080/gohoffice/staging/cronjob/brp_partner_sync.php?key=Yu5aQwjzxZsBK6tZ&sync=delivery&partner=BRPTester&js={"TSHTable":[{"CustCode":"BRPTester","OrderID":"BRPTester@1","OrderDate":"","RecipientCode":"","RecipientName":"Mr. Tan","RecipientTel":"0378426612","RecipientAddress1":"PIN HWA SCHOOL","RecipientAddress2":"No. M13,","RecipientAddress3":"JALAN GOH HOCK HUAT","RecipientAddress4":"KLANG 41400","RecipientAddress5":"Selangor, Malaysia","ItemCode":"Can - 325","MatchingCode":"Can - 325","OrderQty":"10"}]}
		$path_url = $partner_gohoffice_url."?key=".$partner_gohoffice_key."&sync=delivery&partner=".$partner_unique_id."&js=".$strJson."";
		echo $path_url."<br />";//exit;
		
		if(!$isDebug) {
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
			//$arrData = json_decode($resp, true);
			
			$resp = json_decode($resp, true);
			if(isJSON($resp)) {
				$resp = json_decode($resp, true);
			}
			
			//echo "<pre>";print_r($order_ids);echo "</pre>";
			if(isset($resp["success"]) && $resp["success"]) {
				$array["success_msg"] = "Successfully uploaded Gohoffice delivery list"; //$resp;
				mysql_query("UPDATE `" . $mysql_prefix . "order` SET upload_delivery_status = 'Completed' WHERE order_id IN ('" . implode("','",$order_ids) . "')");
				//$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Completed", $order_ids);
			} else {
				if(!is_array($resp)) {
					$array["failure_msg"] = $resp;
				} else {
					$array["failure_msg"] = (isset($resp["error"])?$resp["error"]:"An error has occurred.");
				}
				mysql_query("UPDATE `" . $mysql_prefix . "order` SET upload_delivery_status = 'Pending' WHERE order_id IN ('" . implode("','",$order_ids) . "')");
				//$this->model_sale_upload_delivery_list->updateUploadDeliveryStatus("Pending", $order_ids);
			}
		}
	}
	
	//$array['data'] = rand(1,100);
	return $array;
	//$this->response->addHeader('Content-Type: application/json');
	//$this->response->setOutput(json_encode($json));
}
?>