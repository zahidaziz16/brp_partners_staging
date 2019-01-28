<?php
	
# nut! put this in a folder called connection or whatever rather than defining this on each file!
require_once(dirname(__DIR__) . "/config.php");
$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
# connection define end 

# Lazada SKD is required for Lazada's API	
include "LazadaSDK/LazopSdk.php";

# Initiate Variable
$params = get_default_params($conn);
$offset = 0;
$limit = 100;

while ($offset < 10000){

	# get Seller Center's Models from API
	$products = get_models($params, $offset, $limit);
	
	$offset += $limit;

	# loop each models and save attributes to table oc_lazada_products
	foreach ($products as $product){

		$model = $product['seller_sku'];

		# find product_id in oc_product
		$oc_product_id = get_oc_product_id($conn, $model);

		# skip if it is not inside oc_product
		if($oc_product_id == 0) {
			echo "Skip " .$product['seller_sku']. " , it is not inside the db\n";
			continue;
		}

		$is_model_exist = is_model_exist($conn, $model);

		if ($is_model_exist){
			echo $model . "-- Exist Skipping.. \n";
			continue;
		}
		
		$insert_params = array(
			'product_id' 	=> $oc_product_id,
			'item_id'		=> $product['item_id'],
			'model'			=> $product['seller_sku']	
		);

		$insert_status = insert_product($conn, $insert_params) ? $model . " inserted\n" : $product['model'] . "failed to be inserted\n";
		echo $insert_status;
	}
		

}

date_default_timezone_set("Asia/Kuala_Lumpur");
$current_date = date('d-m-Y H:i:s');
echo "\n loading models from sellercenter to oc_lazada_products finished at ". $current_date;



# ===============================
# function list
# ===============================

# return json of model or error message
function get_models( $params = array(), $offset, $limit  ){

if(empty($params)){
	return "Parameters are empty";
}

# initiating variable 
$api_url 		= default_api_url();
$app_key 		= $params['app_key'];
$app_secret 	= $params['app_secret'];
$access_token 	= $params['access_token'];

$client = new LazopClient($api_url,$app_key, $app_secret);
$request = new LazopRequest('/products/get','GET');
$request->addApiParam('filter','all');
$request->addApiParam('offset',$offset);
$request->addApiParam("limit", $limit);
$result = $client->execute($request, $access_token);
$result_json = json_decode($result, true);

if(!empty($result_json['data']['products'])){

	$products =  $result_json['data']['products'];

} else {

	$products = array();

}


# Assign varibales into array
$product_detail = array();

if(!empty($products)) {

	foreach ($products as $index => $product) {

		$product_detail[$index]['seller_sku'] 	= $product['skus']['0']['SellerSku'];
		$product_detail[$index]['item_id']		= $product['item_id'];

	}

}

return $product_detail;

}

# Default API URL for Lazada Malaysia API
function default_api_url(){

return 'https://auth.lazada.com/rest';

}

# Parameters for lazada default requirement.
# App Key
# App Secret
# App Token
function get_default_params($conn){

# Get App Key from Database
$get_app_key = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_app_key' LIMIT 1";
$app_key_result = $conn->query($get_app_key);

if ($app_key_result->num_rows > 0) {
    $app_key_row = $app_key_result->fetch_assoc();
    $app_key = $app_key_row['value'];
} else {
    $app_key = '';
}


# Get Access Secret from Database
$get_app_secret = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_app_secret' LIMIT 1";
$app_secret_result = $conn->query($get_app_secret);

if ($app_secret_result->num_rows > 0) {
    $app_secret_row = $app_secret_result->fetch_assoc();
    $app_secret = $app_secret_row['value'];
} else {
    $app_secret = '';
}	


# Get Access Token from Database
$get_access_token = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_openapi_access_token' LIMIT 1";
$access_token_result = $conn->query($get_access_token);

if ($access_token_result->num_rows > 0) {
    $access_token_row = $access_token_result->fetch_assoc();
    $access_token = $access_token_row['value'];
} else {
    $access_token = '';
}


$params = array(
	'app_key' 		=> $app_key,
	'access_token' 	=> $access_token,
	'app_secret'	=> $app_secret
);

return $params;

}


function insert_product($conn, $params){

	# Initiate Variables for insert
	$product_id = $params['product_id'];
	$item_id	= $params['item_id'];
	$model 		= $params['model'];

	$insert_lazada_product = $conn->prepare("INSERT INTO oc_lazada_products (product_id, item_id, model) VALUES (?, ?, ?);");
	$insert_lazada_product->bind_param("iis", $product_id, $item_id, $model );
	$insert_lazada_result = $insert_lazada_product->execute();

	if ($insert_lazada_result != false) {
	    return true;
	} else {
	    return false;
	}

}


function get_oc_product_id($conn, $model){

	$find_product_id = $conn->prepare("SELECT product_id FROM oc_product WHERE model = ? ;");
	$find_product_id->bind_param('s', $model);
	$product_id_result = $find_product_id->execute();
	# throw result into $product_id
	$find_product_id->bind_result($product_id);

	# using compilcated method, cause proper way require php.ini to be editted 
	# return product_id or 0 if not found
	 if ($product_id_result != false) {
	    $result = array();
	    while($find_product_id->fetch()) {
	    	$result['product_id'] = $product_id;
	    }

	    if (!empty($result)){
	    	return $result['product_id'];
		} else {
			return 0;
		}

	# if query fail, return 0
	} else {
	    return 0;
	}

}

# find model exist or not inside oc_lazada_products
function is_model_exist($conn, $model){

	$find_model = $conn->prepare("SELECT model FROM oc_lazada_products WHERE model = ? ;");
	$find_model->bind_param('s', $model);
	$product_model_result = $find_model->execute();
	# throw result into $product_id
	$find_model->bind_result($model);


	 if ($find_model != false) {
	    $result = array();
	    while($find_model->fetch()) {
	    	$result['model'] = $model;
	    }
	    
	    if(!empty($result)){
	    	return true;
	    } else {
	    	return false;
	    }

	} else {
	    return false;
	}

}

?>