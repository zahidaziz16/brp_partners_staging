<?php
// DYNAMIC VERSION
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

//require_once(dirname(__DIR__) . "/config.php");

$conn = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$sql = "SELECT group_concat(product_id) As product_ids, model, count(model) as ttl_model FROM oc_product WHERE model != '' GROUP BY model having ttl_model>1";
$result = $conn->query($sql);
$string_ids = '';
while($row2 = mysqli_fetch_assoc($result)){
	$ids = explode(',', $row2['product_ids']);
	foreach ($ids as $id){
		$string_ids .= $id . ", ";
	}
	//print_r($row2);
}


$string_ids = rtrim($string_ids, ", ");
$sql = "SELECT product_id, data_source, model, status FROM `oc_product` WHERE `product_id` IN (". $string_ids . ") ORDER BY `model`";
//echo $sql;
$result = $conn->query($sql);
$update_ids = '';
while($row2 = mysqli_fetch_assoc($result)){
	if($row2['status'] == 0){
		//echo "ROW CONTINUE: <br>";
		//print_r($row2);
		continue;
	}
	$data_source = $row2['data_source'];
	$product_id = $row2['product_id'];
	if(!empty($data_source)){
		$response = checkDataSourceExist($data_source);
		$response = json_decode($response, true);
		if(isset($response['success'])){
			//print_r($response);
			
		} else {
			$update_ids .= $product_id . ", ";
			//echo "FALSE";
		}
	}
	//exit;
}
$update_ids = rtrim($update_ids, ", ");
if($update_ids){
	
	echo "<br> Delete ids = " . $update_ids . "<br>";
	/*
	$sql = "UPDATE oc_product SET status = 0 WHERE product_id IN (".$update_ids.")";
	echo "<br>" . $sql . "<br>";
	if($conn->query($sql)){
		echo count(explode(",", $update_ids))." products disabled.";
	}
	*/
	$arr_ids = explode(", ", $update_ids);
	foreach($arr_ids as $product_id){
		$sql = "DELETE FROM oc_product WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_attribute WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_description WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_discount WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_filter WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_image WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_option WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_option_value WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_related WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_related WHERE related_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_reward WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_special WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_to_category WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_to_download WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_to_layout WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_to_store WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_product_recurring WHERE product_id = " . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_review WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_url_alias WHERE query = 'product_id=" . (int)$product_id . "'";
		$conn->query($sql);
		$sql = "DELETE FROM oc_coupon_product WHERE product_id = '" . (int)$product_id . "'";
		$conn->query($sql);
	}
	echo count(explode(",", $update_ids))." products deleted.";
} else {
	echo "<br>No products deleted. 0 bad data found";
}

function checkDataSourceExist($data_source){
	$datasource = $data_source;
	//$path_url = "http://localhost/atoz_brp_partner/cron/test.php?product=$code";
	$path_url = "http://brp.com.my/core/cron/check_product_exist.php?product=$datasource";
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
