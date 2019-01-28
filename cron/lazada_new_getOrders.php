<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

require_once(dirname(__DIR__) . "/admin/config.php");
include "LazadaSDK/LazopSdk.php";

$conn = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$lazada_userId = '0';
$lazada_API_key = '0';
$lazada_enable = '0';
$brp_partner_id = '';
$store_name = 'Lazada';
$store_url = HTTP_CATALOG;

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'config_name' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $store_name = $row['value'];
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'config_unique_brp_partner_id' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $brp_partner_id = $row['value'];
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_enable' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lazada_enable = $row['value'];
}

if (!$lazada_enable){
    $dateKL = new DateTime("now", new DateTimeZone('Asia/Kuala_Lumpur'));
    echo $dateKL->format('Y-m-d H:i:s') . " Lazada Marketplace is disabled. Cronjob will not be run."; exit;
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

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_dummy_customer_id' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $customer_id = $row['value'];
} else {
    $customer_id = 0;
}

$sql = "SELECT `customer_group_id` FROM `oc_customer` WHERE `customer_id` = '$customer_id' LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $customer_group_id = $row['customer_group_id'];
} else {
    $customer_group_id = '';
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_openapi_access_token' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lazada_access_token = $row['value'];
} else {
    $lazada_access_token = '';
}

function getOrders($offset, $appkey, $appSecret, $lazada_access_token){
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $now = new DateTime();

    // ISO 8601 time format
    // set DiffTime accordingly
    $diffTime = strtotime("-8 hours");
    $date_filter = date('Y-m-d\TH:i:s',  $diffTime);
    $updated_after_date = date("c", strtotime($date_filter));

    //Call Lazada SDK
    $url = 'https://auth.lazada.com/rest';
    $c = new LazopClient($url,$appkey,$appSecret);
    $request = new LazopRequest('/orders/get','GET');
    $request->addApiParam('sort_direction','DESC');
    $request->addApiParam('offset',$offset);
    // $request->addApiParam('limit','2');
    $request->addApiParam('update_after',$updated_after_date);
    $request->addApiParam('sort_by','updated_at');
    $api_response = $c->execute($request, $lazada_access_token);
    $result = json_decode($api_response, true, 512, JSON_BIGINT_AS_STRING);

    $resultData = array();

    if($result['code'] == "0") {
        $resultData = $result['data']['orders'];

        if(count($resultData) >= 100) {
            $resultData = array_merge($resultData, getOrders($offset+100, $appkey, $appSecret, $lazada_access_token));
        }

    }

    return $resultData;
}


echo "Start Cronjob : \n";
$resultData = getOrders(0, $appkey, $appSecret, $lazada_access_token);

if(!empty($resultData)){

    $country = "Malaysia";
    $countryId = 129;
    $currencyCode = "MYR";
    $currencyId = 4;
    $lang = 1;

    foreach($resultData AS $data){
        foreach($data AS $k=>$temp){
            $data[$k] = str_replace("'", "\'", $temp);
        }
        $orderData = $data;
        $ordId = $data['order_id'];
        $ordNo = $data['order_number'];
        $dateMod = date('Y-m-d H:i:s', strtotime($data['updated_at']));
        $dateAdd = date('Y-m-d H:i:s', strtotime($data['created_at']));
        $existingOrder = checkOrderExists($conn, $ordNo);

        if($existingOrder == false){ //new orders to be inserted
            $fName = $data['customer_first_name'];
            $lName = $data['customer_last_name'];
            if($data['payment_method'] == "IPay88"){
                $payMethod = "iPay88 Payment Gateway";
                $payCode = "ipay88";
            }else{
                $payMethod = $data['payment_method'];
                $payCode = "";
            }
            $orderData['payment_method'] = $payMethod; //mail
            $comment = "Lazada Order No: #".$ordNo;
            $orderData['comment'] = $comment; //mail
            $price = str_replace(",", "", $data['price']);
            $couponCode = $data['voucher_code'];
            $voucher = str_replace(",", "", $data['voucher']);

            $payFName = $data['address_billing']['first_name'];
            $payLName = $data['address_billing']['last_name'];
            $phone = $data['address_billing']['phone'];
            if(empty($phone)) $phone = $data['address_billing']['phone2'];
            $payAddr1 = addslashes($data['address_billing']['address1']);
            $payAddr2 = addslashes($data['address_billing']['address2']);
            $payZone = addslashes($data['address_billing']['address3']); //selangor
            $payZoneId = getZoneId($payZone);
            $payCity = $data['address_billing']['address4']; //pj
            $payPostCode = $data['address_billing']['post_code'];
            $email = $data['address_billing']['customer_email'];

            $shipFName = $data['address_shipping']['first_name'];
            $shipLName = $data['address_shipping']['last_name'];
            if(empty($phone)) $phone = $data['address_shipping']['phone'];
            if(empty($phone)) $phone = $data['address_shipping']['phone2'];
            $shipAddr1 = addslashes($data['address_shipping']['address1']);
            $shipAddr2 = addslashes($data['address_shipping']['address2']);
            $shipZone = addslashes($data['address_shipping']['address3']);
            $shipZoneId = getZoneId($shipZone);
            $shipCity = $data['address_shipping']['address4'];
            $shipPostCode = $data['address_shipping']['post_code'];

            foreach($data['statuses'] AS $stat){
                $status = $stat; //get last status
            }
            $orderData['status'] = $status;
            $statusId = getOrderStatusId($status);
            $total = str_replace(",", "", $data['price']);
            $totalQty = str_replace(",", "", $data['items_count']);
            $deliveryDate = $data['promised_shipping_times'];
            $shipFee = str_replace(",", "", $data['shipping_fee']);
            if($shipFee == 0) $shipMethod = "Free Shipping"; else $shipMethod = "Shipping";
            $orderData['shipping_method'] = $shipMethod; //mail
            $total = $total + $shipFee - $voucher;



            $sql = "INSERT INTO oc_order (`store_name`, `store_url`, `customer_id`,`customer_group_id`, `firstname`, `lastname`, `email`, `telephone`, `payment_firstname`, `payment_lastname`, 
                                          `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_country`, 
                                          `payment_country_id`, `payment_zone`, `payment_zone_id`, `payment_method`, `payment_code`,
                                          `shipping_firstname`, `shipping_lastname`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, 
                                          `shipping_postcode`, `shipping_country`, `shipping_country_id`, `shipping_zone`, `shipping_zone_id`, `shipping_method`,
                                          `comment`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency_code`, `date_added`, 
                                          `date_modified`, `total_qty`, `coupon_code`)
                    VALUES ('$store_name', '$store_url', '$customer_id', '$customer_group_id', '$fName', '$lName', '$email', '$phone', '$payFName', '$payLName', 
                            '$payAddr1', '$payAddr2', '$payCity', '$payPostCode', '$country', 
                            '$countryId', '$payZone', '$payZoneId', '$payMethod', '$payCode',
                            '$shipFName', '$shipLName', '$shipAddr1', '$shipAddr2', '$shipCity', 
                            '$shipPostCode', '$country', '$countryId', '$shipZone', '$shipZoneId', '$shipMethod',
                            '$comment', '$total', '$statusId', '$lang', '$currencyId', '$currencyCode', '$dateAdd', 
                            '$dateMod', '$totalQty', '$couponCode')";


            if(mysqli_query($conn, $sql)){
                echo 'Success';
            }else{
                echo mysqli_error($conn);
            }
            $brp_ord_id = mysqli_insert_id($conn);
            if($brp_ord_id == 0) continue;
            echo "\nOrder Id: ".$brp_ord_id." has been inserted.\n";
            if(!empty($brp_partner_id)){
                $unique_order_id = $brp_partner_id."@".$brp_ord_id;
                $sql = "UPDATE `oc_order` SET unique_order_id = '" . $unique_order_id . "', date_modified = NOW() WHERE order_id = '" . (int)$brp_ord_id . "'";
                mysqli_query($conn, $sql);
            }

            //get order items
            $result2 = getOrderItems($ordId, $appkey, $appSecret, $lazada_access_token);
            $result2 = json_decode($result2, true);
            $result2 = $result2['data'];

            //initializing voucher
            $sellerVoucher = 0;
            $lazadaVoucher = 0;
            $sellerVoucherTotal = 0;
            $lazadaVoucherTotal = 0;


            if(isset($result2)){
                $itemSkus = array(); $itemGroup = array(); $itemIdQty = array();
                foreach($result2 AS $item){

                    //initializing voucher
                    $lazadaVoucher = $item['voucher_platform'];
                    $sellerVoucher = $item['voucher_seller'];
                    
                    if($lazadaVoucher > 0.0) {
                        
                        $lazadaVoucherTotal += $lazadaVoucher;
                    }

                    if($sellerVoucher > 0.0){
                      
                        $sellerVoucherTotal += $sellerVoucher;
                    }


                    if(!in_array($item['sku'], $itemSkus)){
                        array_push($itemSkus, $item['sku']);
                        $itemGroup[] = $item;
                        $itemIdQty[$item['sku']] = 1;
                    }
                    else{
                        $itemIdQty[$item['sku']]++;
                    }
                }
				
                

                $finalTotal = 0; $subTotal = 0; $totalTax = 0;
                foreach($itemGroup AS $item){
                    $name = mysqli_real_escape_string($conn, $item['name']);
                    $model = mysqli_real_escape_string($conn, $item['sku']);
                    $productDetails = getProductDetails($model);

                    // Return BUNDLECONDE INTO oc_order_product if the product name contain bundle, else let it empty
                    $lower_case_name = strtolower($name);
                    $find_bundle_in_name = strpos($lower_case_name, 'bundle');
                    $find_bundle_in_name ? $matching_code = "BUNDLECODE" : $matching_code = '';

                    // Assign BUNDLECODE into matching code if it is exist in oc_product_bundle_matching
                    if(getBundleMatching($conn) != false &&  $matching_code != "BUNDLECODE") {
                        $bundleMatchingList = getBundleMatching($conn);
                        in_array($model, $bundleMatchingList ) ? $matching_code = "BUNDLECODE" : $matching_code = '';
                    }

                    

                    if(empty($productDetails))
                        continue;
                    if($item['voucher_amount'] > 0){
                        $price = $item['item_price'];
                    }else{
                        $price = $item['paid_price'];
                    }
                    $tax = 0;
                    $quantity = $itemIdQty[$item['sku']];
                    $total = $price*$quantity;
                    $finalTotal += (($price*$quantity) + ($tax*$quantity));
                    $subTotal += ($price*$quantity);
                    $totalTax += ($tax*$quantity);
                    $lItemId = $item['order_item_id'];

                    $productId = $productDetails[0];
                    $mainCat = $productDetails[1];
                    $subCat = $productDetails[2];
                    $data_source = '';
                    if(!empty($productDetails[3])){
                        $data_source = $productDetails[3];
                    }

                    // Adding matching_code from db, and validate it
                    // Matching code is used for ERP
                    $db_matching_code = $productDetails[4];
                    if ($matching_code != "BUNDLECODE" && !empty($db_matching_code) ) {
                        $matching_code =  $productDetails[4];
                    }


                    $sql2 = "INSERT INTO oc_order_product (`order_id`, `product_id`, `name`, `data_source`, `model`, `matching_code`, `quantity`, `price`, `total`, `tax`)
                            VALUES ('$brp_ord_id', '$productId', '$name', '$data_source', '$model', '$matching_code', '$quantity', '$price', '$total', '$tax');";

                    echo "- Order Product Items inserted.\n";
                    mysqli_query($conn, $sql2);
                }
                //Order Total - Shipping & voucher only
                $finalTotal = $finalTotal + $shipFee - $voucher;

                $sql3 ="INSERT INTO oc_order_total (`order_id`, `code`, `title`, `value`, `sort_order`) 
                VALUES 
                ('$brp_ord_id', 'sub_total', 'Sub-Total (before GST)', '$subTotal', '1'),
                ('$brp_ord_id', 'shipping', '$shipMethod', '$shipFee', '2'),
                ('$brp_ord_id', 'tax', 'GST KL &amp; SEL (0%)', '$totalTax', '5'),
                ('$brp_ord_id', 'total', 'Total', '$finalTotal', '6');";
                mysqli_query($conn, $sql3);


                //inserting Lazada discount variation into DB
                $sellerVoucherTotal > 0.0 ? mysqli_query($conn, "INSERT INTO oc_order_total (`order_id`, `code`, `title`, `value`, `sort_order`) VALUES ('$brp_ord_id', 'coupon', 'Seller Voucher', '-$sellerVoucherTotal', '3');") : '';

                $lazadaVoucherTotal > 0.0 ? mysqli_query($conn, "INSERT INTO oc_order_total (`order_id`, `code`, `title`, `value`, `sort_order`) VALUES ('$brp_ord_id', 'coupon', 'Lazada Voucher', '-$lazadaVoucherTotal', '4');") : '';

                //send mail
                $curl = curl_init();
                $postData = array(
                    'order_data'    => json_encode($orderData),
                    'order_id'      => $brp_ord_id,
                    'order_product' => json_encode($itemGroup),
                    'item_qty'      => json_encode($itemIdQty)
                );


                $encoded_order = json_encode($orderData);
                $encoded_item = json_encode($itemGroup);
                $encoded_item_quantity = json_encode($itemIdQty);
                
				$currentDir = dirname(__DIR__);
                file_put_contents($currentDir.'/cron/orderData.txt',  $encoded_order);
                file_put_contents($currentDir.'/cron/itemGroup.txt',  $encoded_item);
                file_put_contents($currentDir.'/cron/itemIdQty.txt',  $encoded_item_quantity);

                $siteURL = HTTP_CATALOG . "index.php?route=product/product/sendMail";
                curl_setopt($curl, CURLOPT_URL, $siteURL);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                curl_setopt($curl, CURLOPT_VERBOSE, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                //curl_setopt($curl, CURLOPT_SAFE_UPLOAD, 0);
                $resp = curl_exec($curl);
                curl_close($curl);

            }
        }
        else{
			
            //get order items
            $result2 = getOrderItems($ordId, $appkey, $appSecret, $lazada_access_token);
            $result2 = json_decode($result2, true);
            $result2 = $result2['data'];

            if(isset($result2)){
                foreach($result2 AS $item){
					$tracking_code = $item['tracking_code'];
                }
			}
				// var_dump($tracking_code); 

            foreach($data['statuses'] AS $stat){
                $status = $stat; //get last status
            }
            $statusId = getOrderStatusId($status);
            if($statusId == 5){ //already delivered
                echo "No update for order id: ".$existingOrder." neccessary.\n";
                continue;
            }
            $total = str_replace(",", "", $data['price']) + str_replace(",", "", $data['shipping_fee']) - str_replace(",", "", $data['voucher']);
            $sql = "UPDATE oc_order SET `total` = '$total', `order_status_id` = '$statusId', `date_modified` = '$dateMod'
                    WHERE `comment` = 'Lazada Order No: #".$ordNo."'";

            echo "Order Id: ".$existingOrder." has been updated.\n";
            mysqli_query($conn, $sql);
        }

    }
}

   

//Insert datetime of cronjob
date_default_timezone_set("Asia/Kuala_Lumpur");
$dateNow = date('Y-m-d H:i:s');
 echo "\n Cronjob Stop at : $dateNow";

$sql = "UPDATE `oc_setting` SET `value` = '$dateNow' WHERE `oc_setting`.`key` = 'lazada_last_cronjob_date_order';";
$conn->query($sql) or die(mysqli_error($conn));

function strpos_arr($haystack, $needle){
    if(!is_array($needle)) $needle = array(strtolower($needle));
    foreach($needle as $what) {
        if(($pos = strpos(strtolower($haystack), strtolower($what)))!==false) return $pos;
    }
    return false;
}

/*
 * Get List of bundle sku from oc_product_bundle_matching
 * return array of list if success and return false if fail
 */
function getBundleMatching($conn) {

    $selectBundleMatching = "SELECT bundle_code FROM oc_product_bundle_matching";
    $bundleMatchingResult = mysqli_query($conn, $selectBundleMatching);

    if ( !empty($bundleMatchingResult->num_rows) ){

        while($row = mysqli_fetch_assoc($bundleMatchingResult)){

            extract($row, EXTR_PREFIX_ALL, "bundle");

            $bundleMatching[] = $bundle_bundle_code;

        }

        return $bundleMatching;

    } else {

        return false;
    }
}

function getZoneId($zoneName){
    $id = 1985;
    if($zoneName == "Selangor") $id = 1983;
    //else if($zoneName == "Wp Kuala Lumpur") $id = 1985;
    else if($zoneName == "Terengganu") $id = 1984;
    else if($zoneName == "Sarawak") $id = 1982;
    else if($zoneName == "Sabah") $id = 1981;
    else if($zoneName == "Wp Putrajaya") $id = 4035;
    else if($zoneName == "Penang") $id = 1980;
    else if($zoneName == "Perlis") $id = 1979;
    else if($zoneName == "Perak") $id = 1978;
    else if($zoneName == "Pahang") $id = 1977;
    else if($zoneName == "Negeri Sembilan") $id = 1976;
    else if($zoneName == "Melaka") $id = 1975;
    else if($zoneName == "Wp Labuan") $id = 1974;
    else if($zoneName == "Kelantan") $id = 1973;
    else if($zoneName == "Kedah") $id = 1972;
    else if($zoneName == "Johor") $id = 1971;

    return $id;
}


/*
  Table in DB oc_order_status
  Status ID 1   => Pending
            2   => Processing
            3   => Shipped
            5   => Complete
            7   => Canceled
            8   => Denied
            9   => Canceled Reversal
            10  => Failed
            11  => Refunded
            12  => Reversed
            13  => Chargeback
            14  => Expired
            15  => Processed
            16  => Voided
            21  => Draft
            22  => Packing
            23  => Shipment
 */
function getOrderStatusId($status){
    $statusId = 2;
    if($status == "delivered") $statusId = 5;
    else if($status == "unpaid") $statusId  = 1;
    else if($status == "pending") $statusId = 2;
    else if($status == "ready_to_ship") $statusId = 23;
    else if($status == "failed") $statusId = 10;
    else if($status == "canceled") $statusId = 7;
    else if($status == "returned") $statusId = 11;
    else if($status == "shipped") $statusId = 3;
    else if($status == "return_rejected") $statusId = 8;
    else if($status == "return_waiting_for_approval") $statusId = 2;
    else if($status == "return_shipped_by_customer") $statusId = 12;

    return $statusId;
}

function getOrderItems($ordId, $appkey, $appSecret, $lazada_access_token){
    $url = 'https://auth.lazada.com/rest';
    $client = new LazopClient($url,$appkey,$appSecret);
    $request = new LazopRequest('/order/items/get','GET');
    $request->addApiParam('order_id', $ordId);
    $response = $client->execute($request, $lazada_access_token);
    return $response;
}


/*
 * Returning 
 * opencart product_id
 * parent category
 * child category
 * data_source 	 -> sku from Atoz
 * natching_code -> sku from Goh Office
 */
function getProductDetails($sku)
{

	$conn  	= $GLOBALS["conn"];
    $sku    = str_replace(" ", "", $sku);
    $sql    = "SELECT 	product.product_id,
						product_parent_category.parent_category,
				        product_child_category.child_category, 
				        product.data_source, 
				        product.matching_code
				        
				FROM	oc_product AS product
				            
				            LEFT JOIN
				            (
				                SELECT 
				                    product_to_category.category_id AS parent_category, 
				                    product_to_category.product_id 
				                FROM oc_product_to_category AS product_to_category
				                	LEFT JOIN oc_category AS category ON (product_to_category.category_id = category.category_id)
				                WHERE category.parent_id = 0
				            ) product_parent_category ON (product_parent_category.product_id = product.product_id)
				            
				            LEFT JOIN
				            (
				                SELECT 	product_to_category.category_id AS child_category,
				                		product_to_category.product_id 
				                FROM oc_product_to_category AS product_to_category
				                	LEFT JOIN oc_category AS category2 ON (product_to_category.category_id = category2.category_id)
				                WHERE category2.parent_id != 0
				            ) product_child_category ON (product_child_category.product_id = product.product_id)
				            
	            WHERE 
	            	((REPLACE(product.sku, ' ', '') = '$sku') OR (REPLACE(product.model, ' ', '') = '$sku')) AND product.matching_code != '' AND product.model != ''
	            GROUP BY product.product_id";

    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_row($result)) {
        return $row;
    } else {
        # Map to not matching product, especially third party product
        $data[0] = 0;  //product id
        $data[1] = 0;  //parent_category
        $data[2] = 0;  //child_category
        $data[3] = ''; //data source
        $data[4] = ''; //matching_code
        return $data;
    }
}

function checkOrderExists($conn, $id){
    $sql = "SELECT order_id FROM oc_order WHERE comment = 'Lazada Order No: #$id'";
    $result = mysqli_query($conn, $sql);
    if($row = mysqli_fetch_row($result)){
        return $row[0];
    }
    else return false;
}
?>