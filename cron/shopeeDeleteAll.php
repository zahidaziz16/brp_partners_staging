<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

require_once(dirname(__DIR__) . "/config.php");
include('html2text-master/src/Html2Text.php');
$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//Initialize key settings
$shopee_partner_id = '0';
$shopee_shop_id = '0';
$shopee_API_key = '0';
$shopee_enable = '0';
$shopee_price_markup_percentage = '0';
$shopee_price_markup_flat = '0';
$shopee_price_threshold = '0';
$shopee_shipping_fee = '0';
$sql_price_threshold = '1';
$url = '';

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_enable' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$shopee_enable = $row['value'];
}

if (!$shopee_enable){
	$dateKL = new DateTime("now", new DateTimeZone('Asia/Kuala_Lumpur'));
	echo $dateKL->format('Y-m-d H:i:s') . " Shopee Marketplace is disabled. Cronjob will not be run."; exit;
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_partner_id' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$shopee_partner_id = $row['value'];
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_shop_id' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$shopee_shop_id = $row['value'];
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_API_key' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$shopee_API_key = $row['value'];
}

if ($shopee_partner_id == '0' || $shopee_API_key == '0' || $shopee_shop_id == '0'){
	echo $shopee_API_key . "\n"; 
	echo $shopee_partner_id . "\n";
	echo $shopee_shop_id . "\n";
	echo "Invalid Shopee UserID / ShopID / API key. "; exit;
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_price_markup_percentage' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$shopee_price_markup_percentage = $row['value'];
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_price_markup_flat' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$shopee_price_markup_flat = $row['value'];
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_price_threshold' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$shopee_price_threshold = $row['value'];
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_shipping_fee' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$shopee_shipping_fee = $row['value'];
}


date_default_timezone_set('Asia/Kuala_Lumpur');
$date = new DateTime();
$fromDate_init = date('Y-m-d',  time()-7*(60*60*24)); //7 days from today time()-7*(60*60*24));
$fromDate =  strtotime($fromDate_init); 

$today_init = date('Y-m-d',  time()+1*(60*60*24)); // a day ahead time()+1*(60*60*24));
$today =  strtotime($today_init);


//SQL query to extract products list to be synced

$fetchDeletingProduct = "SELECT * FROM oc_shopee_products order by product_id ASC" ;


$result = mysqli_query($conn, $fetchDeletingProduct);



$starting_product = 0;
$number_of_product_toDeleted = 5000;
$maximum_count = $number_of_product_toDeleted/100;
$counter = 0;
$no = 0;
//GetItemList
 do {
$itemUrl = "https://partner.shopeemobile.com/api/v1/items/get";

/*
 'update_time_from' => $fromDate,
 'update_time_to' => $today,
 */
$itemListParameter = array (
				'pagination_offset' => $starting_product,
				'pagination_entries_per_page' => 100,
				
				'partner_id' => (integer)$shopee_partner_id,
				'shopid' => (integer)$shopee_shop_id
			);

	$get_results_json = getCurlRequest($itemListParameter, $itemUrl, $shopee_API_key);
    $get_results = json_decode($get_results_json, true);
    //print_r($get_results);
    

    foreach($get_results['items'] as $item) {
    	$item_id = $item['item_id'];


    	$url = "https://partner.shopeemobile.com/api/v1/item/delete";
		$delete_parameters = array(
		'shopid' => (integer)$shopee_shop_id,
		'partner_id' => (integer)$shopee_partner_id,
		'item_id' => $item_id
		);
		$results_json = postCurlRequest($delete_parameters, $url, $shopee_API_key);
		$results = json_decode($results_json, true);

		$no++;
		//	Once succefuly deleted from API, display the output and delete SHOPEE SELLER CENTER**
		
		if(isset($results['item_id'])){
			echo "\n $no - item_id = $item_id : ".$results['msg']."\n";
		} else {
			echo "\n $no - item_id = $item_id is not deleted \n";
		}


    }


    $counter++;
    	/*
		echo "\n ======================================================= \n"
		   . "           ".($counter*100)." product were checked\n" 
		   . "\n ======================================================= \n";
		*/
} while ($counter < $maximum_count);


    exit("\n Bye -- End of deleting standalone products from oc_products to shopee \n");






	
//this is post Curl
function postCurlRequest($parameters, $url, $shopee_API_key) {
	$encoded = array();
	$timenow = new DateTime();
	$parameters['timestamp'] = $timenow->getTimestamp();
	$data_string = json_encode($parameters);
	//echo $data_string;
	$concatenated = $url . "|" . $data_string;
	$api_key = $shopee_API_key;
	$signature = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, FALSE));
	$ch = curl_init();
	$headers = array();
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: '. $signature;

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POST, true );
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	$data = curl_exec($ch);

	curl_close($ch);
    unset($ch); unset($url);
	return $data;
}


function getCurlRequest($parameters, $url, $shopee_API_key) {
    $encoded = array();
    $timenow = new DateTime();
    $parameters['timestamp'] = $timenow->getTimestamp();
    $data_string = json_encode($parameters);
    $concatenated = $url . "|" . $data_string;
    $api_key = $shopee_API_key;
    $signature = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, FALSE));
    $ch = curl_init();
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: '. $signature;

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, true );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $data = curl_exec($ch);

    curl_close($ch);
    //print_r($data); echo "\n\n";

    unset($ch); unset($url);
    return $data;
}





function deleteShopeeProduct($product_id, $conn){
	$sql = "DELETE FROM `oc_shopee_products` WHERE `product_id` = '". (int)$product_id."'";
	
	if(mysqli_query($conn, $sql)){
		return true;
	} else {
		return false;
	}
}

function deleteDiscountItem($deleteParameters, $shopId, $item_id, $discount_id){

	$url = "https://partner.uat.shopeemobile.com/api/v1/discount/item/delete";
		$delete_parameters = array(
		'shopid' => (integer)$shopee_shop_id,
		'partner_id' => (integer)$shopee_partner_id,
		'item_id' => $item_id
		);
		$results_json = postCurlRequest($delete_parameters, $url, $shopee_API_key);
		$results = json_decode($results_json, true);
}

function getShopeeItemDetail($item_id, $partner_id, $shop_id, $shopee_API_key)
{


    $current_time = date('Y-m-d', time());
    $current_time = strtotime($current_time);

    $request_parameters = array(
        'item_id' => (integer)$item_id,
        'partner_id' => (integer)$partner_id,
        'shopid' => (integer)$shop_id,
        'timestamp' => $current_time
    );

    $url = "https://partner.shopeemobile.com/api/v1/item/get";


    $fetch_json = postCurlRequest($request_parameters, $url, $shopee_API_key);
    $result = json_decode($fetch_json, true);

    return $result;


}



?>