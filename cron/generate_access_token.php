<?php

error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set("memory_limit","-1");
ini_set('max_execution_time', 0);
date_default_timezone_set('Asia/Kuala_Lumpur');

include "LazadaSDK/LazopSdk.php";

require_once(dirname(__DIR__) . "/config.php");
$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_app_key' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$appkey = $row['value'];
} else {
	$appkey = '';
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_app_secret' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$appSecret = $row['value'];
} else {
	$appSecret = '';
}

$response = array();

if (empty($appSecret) || empty($appkey)){
	$response['failed'] = "Unable to get app key & app secret.";
	echo json_encode($response);
	exit;
}

if(!empty($_POST)){
	$partner_id = isset($_POST['partner_id']) ? $_POST['partner_id'] : '';
	$code = isset($_POST['code']) ? $_POST['code'] : '';
	$url = 'https://auth.lazada.com/rest';
	$c = new LazopClient($url,$appkey,$appSecret);
	$request = new LazopRequest('/auth/token/create');
	$request->addApiParam('code', $code);
	$api_response = $c->execute($request);
	$details = json_decode($api_response, true);
	$response['details'] = $details;
	$insert_flag = false;
	if(!empty($details['access_token']) && !empty($details['refresh_token'])){
		//INSERT INTO DB access & refresh token
		$insert_flag = insertTokensToDatabase($conn, $details['access_token'], $details['refresh_token']);
		if ($insert_flag){
			$response['success'] =  "Successfully generated access token. Id: " . $partner_id . ", Code: " . $code ;
		} else {
			$response['failed'] = "Unable to insert tokens into database. ";
		}
	} else {
		$response['failed'] = 'Unable to get access token. Please try again later.';
	}


} else {
	$response['failed'] = "No post parameters detected.";

	$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_openapi_refresh_token' LIMIT 1";
	$result = mysqli_query($conn, $sql);

	if($row = mysqli_fetch_assoc($result)){
		$lazada_refresh_token = $row['value'];
	} else {
	    $lazada_refresh_token = '';
	}

	$response = array();

	if (empty($appSecret) || empty($appkey) || empty($lazada_refresh_token)){
		$response['failed'] = "Unable to get app key & app secret.";
		echo json_encode($response);
		exit;
	}

		$url = 'https://auth.lazada.com/rest';
		$c = new LazopClient($url,$appkey,$appSecret);
		$request = new LazopRequest('/auth/token/refresh');
		$request->addApiParam('refresh_token',$lazada_refresh_token);
		$api_response = $c->execute($request);
		$details = json_decode($api_response, true);
		//$response['details'] = $details;
		$insert_flag = false;
		if(!empty($details['access_token']) && !empty($details['refresh_token'])){
			//INSERT INTO DB access & refresh token
			$insert_flag = insertTokensToDatabase($conn, $details['access_token'], $details['refresh_token']);
			if ($insert_flag){
				$response['success'] =  "Successfully generated access token. " ;
			} else {
				$response['failed'] = "Unable to insert tokens into database. ";
			}
		} else {
			$response['failed'] = 'Unable to get access token. Please try again later.';
		}

	echo json_encode($response);
	$conn->close();
}

echo json_encode($response);
$conn->close();

function insertTokensToDatabase($conn, $access_token, $refresh_token){
	
	$sql = "DELETE FROM `oc_setting` WHERE `oc_setting`.`code` = 'lazada_openapi'";
	$conn->query($sql) or die(mysqli_error($conn));

	$sql = "INSERT INTO `oc_setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'lazada_openapi', 'lazada_openapi_access_token', '$access_token', '0'), (NULL, '0', 'lazada_openapi', 'lazada_openapi_refresh_token', '$refresh_token', '0');";
	
	if($conn->query($sql)){
		return true;
	} 

	return false;
}
?>