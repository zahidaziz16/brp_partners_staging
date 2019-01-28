<?php

error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set("memory_limit","-1");
ini_set('max_execution_time', 0);
date_default_timezone_set('Asia/Kuala_Lumpur');

require_once(dirname(__DIR__) . "/config.php");
$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$path_url = "http://brp.com.my/core/cron/product_quantity_json.php";
$curl = curl_init();
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($curl, CURLOPT_TIMEOUT, 300);
curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => str_replace(array("&amp;", " "),array("%26", "%20"),$path_url),
));
$resp = curl_exec($curl);
		
curl_close($curl);

	$emptyTable = "TRUNCATE TABLE `oc_product_quantity_external`";
	mysqli_query($conn, $emptyTable) or die(mysqli_error($conn));


$response = json_decode($resp, true);

$sql = 'INSERT IGNORE INTO oc_product_quantity_external (`model`, `quantity_erp`, `date_added`) VALUES ';
foreach($response as $value){
	$model = $value['model'];
	$quantity = (int)($value['quantity']);
$sql .= "('".$model."', ".$quantity.", NOW()), ";
}
$sql = rtrim($sql, ", ");
$sql .= ";";
mysqli_query($conn, $sql) or die(mysqli_error($conn));

$brp_code = '';
$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'config_unique_brp_partner_id' LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$brp_code = $row['value'];
}
//$brp_code = 'BRPBTS001';

if (!empty($brp_code)){
	$js_string = "{\"TSHTable\":";
	$result = getWMSProductListFromBRP($brp_code);
	$js_string .= $result;
	$js_string .= '}';
	if ($result == '[]'){
		$js_string = '';
	}
} else {
	$js_string = '';
}

$path_url = "http://gohofficekl.dyndns.org:8090/api/WMS/DownloadStockBalance?js=".$js_string.""; // Live
//echo $path_url;exit;
$curl = curl_init();
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($curl, CURLOPT_TIMEOUT, 300);
curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => str_replace(" ","%20",$path_url),
	CURLOPT_POST => 0
));
$resp = curl_exec($curl);
$resp = trim(str_replace("","",strip_tags($resp)));
$resp = json_decode($resp, true); //decode to string
$resp = json_decode($resp, true); //decode to array
//print_r($resp);
$product_stock = array();
if(is_array($resp) && isset($resp["TSHTable"])) {
	foreach($resp["TSHTable"] as $value) {
		$product_stock[] = array(
		'model' => $value['ItemCode'],
		'quantity' => number_format($value["CurrentStock"], 0, "", "") //CurrentStock , AvailableToOrder
		);
	}
}
//echo "<br>";
//print_r($product_stock);

foreach($product_stock as $value){
	$model = $value['model'];
	$quantity = $value['quantity'];
	
	$sql = "INSERT INTO `oc_product_quantity_external` (`model`, `quantity_wms`, `date_added`) VALUES ('".$model."', '".$quantity."', NOW()) ON DUPLICATE KEY UPDATE `quantity_wms` = '".$quantity."', `date_modified` = NOW() ;";
	//echo $sql;
	$conn->query($sql);
}

echo "Successfully synced product quantity. <br>";
$conn->close();
function getWMSProductListFromBRP($brp_code){
	$code = $brp_code;
	//$path_url = "http://localhost/atoz_brp_partner/cron/test.php?product=$code";
	$path_url = "http://brp.com.my/core/cron/product_details_wms.php?brpcode=$code";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($curl, CURLOPT_TIMEOUT, 300);
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => str_replace(array("&amp;", " "),array("%26", "%20"),$path_url),
	));
	$resp = curl_exec($curl);
	
	curl_close($curl);
	
	return $resp;
}
?>