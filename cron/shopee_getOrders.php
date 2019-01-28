<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

require_once(dirname(__DIR__) . "/admin/config.php");

$conn = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//Initialize key settings
$shopee_partner_id = '0';
$shopee_shop_id    = '0';
$shopee_API_key    = '0';
$shopee_enable     = '0';
$brp_partner_id    = '';
$store_name        = 'Shopee';
$store_url         = HTTP_CATALOG;



$cronjob_name = basename(__FILE__, '.php');
# Removing Marker that prevent running twice, Deprecate the faeture.
# Replace with FLOCK instead
// $cronStatus = cronjobController($conn, $cronjob_name);
$cronStatus = false;

if ($cronStatus == false) {

    lockCronjob($conn, $cronjob_name);
    
    $shopee_enable = enableShopeeCronjob($conn);
    if ($shopee_enable == false) {
        $dateKL = new DateTime("now", new DateTimeZone('Asia/Kuala_Lumpur'));
        echo $dateKL->format('Y-m-d H:i:s') . " Shopee Marketplace is disabled. Cronjob will not be run.";
        exit;
    }
    // initializing variable value
    $shopee_partner_id = getPartnerId($conn);
    $shopee_shop_id    = getShopId($conn);
    $shopee_API_key    = getApiKey($conn);
    
    // stop cronjob if any of this value == 0
    if ($shopee_partner_id == false || $shopee_API_key == false || $shopee_shop_id == false) {
        echo $shopee_API_key . "\n";
        echo $shopee_partner_id . "\n";
        echo $shopee_shop_id . "\n";
        echo "Invalid Shopee UserID / ShopID / API key. ";
        exit;
    }
    
    // initializing variable value
    $store_name        = getStoreName($conn);
    $brp_partner_id    = getBrpPartnerId($conn);
    $getCustomerDetail = getCustomerDetail($conn);
    $customer_id       = $getCustomerDetail['customer_id'];
    $customer_group_id = $getCustomerDetail['customer_group_id'];
    
    
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $date          = new DateTime();
    $fromDate_init = date('Y-m-d H:i:s', time() - 60 * 60 * 2); //2 hours from today time()-7*(60*60*24));
    $fromDate      = strtotime($fromDate_init);
    
    $today_init = date('Y-m-d', time() + 1 * (60 * 60 * 24)); // a day ahead time()+1*(60*60*24));
    $today      = strtotime($today_init);
    
    $pagination_entries = 50;
    $pagination_offset  = 0;
    $more_orders_flag   = true;
    $parameters         = array(
        'shopid' => (integer) $shopee_shop_id,
        'partner_id' => (integer) $shopee_partner_id,
        'update_time_from' => $fromDate,
        'update_time_to' => $today,
        'pagination_entries_per_page' => $pagination_entries,
        'pagination_offset' => $pagination_offset
        
    );
    $orderlist_temp     = array();
    $orderlist          = array();
    //Get Order List
    $url                = "https://partner.shopeemobile.com/api/v1/orders/basics";
    $temp_parameters    = $parameters;
    while ($more_orders_flag) {
        $results_json = executecURL($temp_parameters, $url, $shopee_API_key);
        $results      = json_decode($results_json, true);
        if (isset($results['orders'])) {
            $orderlist_temp = $results['orders'];
            foreach ($orderlist_temp as $order) {
                $orderlist[] = $order['ordersn'];
            }
            $more_orders_flag = $results['more'] ? true : false;
            $pagination_offset += $pagination_entries;
            $temp_parameters['pagination_offset'] = $pagination_offset;
        } else if (isset($results['error'])) {
            print_r("Error retrieving order list: " . $results_json);
            $more_orders_flag = false;
        } else {
            print_r("Results : " . $results_json);
            $more_orders_flag = false;
        }
    }
    
    //Get model matching for bundle
    $bundleList = getBundleMatching($conn);
    if($bundleList == false){
    	$bundleList = array();
    }

    //Get Order Details
    $parameters   = array(
        'shopid' => (integer) $shopee_shop_id,
        'partner_id' => (integer) $shopee_partner_id
    );
    $url          = "https://partner.shopeemobile.com/api/v1/orders/detail";
    $country      = "Malaysia";
    $countryId    = 129;
    $currencyCode = "MYR";
    $currencyId   = 4;
    $lang         = 1;
    foreach ($orderlist as $ordersn) {
        $order_arr   = array();
        $order_arr[] = $ordersn;
 
        $getorder_parameters                 = $parameters;
        $getorder_parameters['ordersn_list'] = $order_arr;
        $results_json                        = executecURL($getorder_parameters, $url, $shopee_API_key);
        $results                             = json_decode($results_json, true);
        
        if (isset($results['orders'])) {
            $currentOrder = $results['orders'][0];
            
            $orderData  = $currentOrder;
            $ordNo      = $currentOrder['ordersn'];
            $trackingNo = $currentOrder['tracking_no'];
            
            //For debuging base on orderNo.
            //if($ordNo != '18121216195T5NE'){ continue; }
            
            //Prevent Null order Number being stored into db
            if ($ordNo == '' || empty($ordNo)) {
                continue;
            }
            
            $dateMod = date("Y-m-d H:i:s", $currentOrder['update_time']);
            $dateAdd = date("Y-m-d H:i:s", $currentOrder['create_time']);
            
            //to get voucher and coin base details
            $shopeeDiscountDetail = getDiscountDetails($currentOrder['ordersn'], (integer) $shopee_partner_id, (integer) $shopee_shop_id, $shopee_API_key);
            
            
            $existingOrder = checkOrderExists($conn, $ordNo);
            
            if ($existingOrder == false) { //new orders to be inserted
                
                $fName = $currentOrder['recipient_address']['name'];
                $fName = str_replace("'", "", $fName);
                $lName = '';
                if ($currentOrder['payment_method'] == "PAY_IPAY88") {
                    $payMethod = "iPay88 Payment Gateway";
                    $payCode   = "ipay88";
                } else {
                    $payMethod = $currentOrder['payment_method'];
                    $payCode   = "";
                }
                $orderData['payment_method'] = $payMethod; //mail
                $comment                     = "Shopee Order No: #" . $ordNo;
                $orderData['comment']        = $comment; //mail
                $price                       = str_replace(",", "", $currentOrder['total_amount']);
                $couponCode                  = '';
                $voucher_amount              = $shopeeDiscountDetail['order']['income_details']['voucher'];
                $coin_amount                 = $shopeeDiscountDetail['order']['income_details']['coin'];
                $seller_voucher              = $shopeeDiscountDetail['order']['income_details']['voucher_seller'];
                //$voucher                     = ($voucher_amount + $coin_amount + $seller_voucher);
                $payFName                    = $currentOrder['recipient_address']['name'];
                $payFName                    = str_replace("'", "", $payFName);
                $payLName                    = '';
                $phone                       = $currentOrder['recipient_address']['phone'];
                $payAddr1                    = $currentOrder['recipient_address']['full_address'];
                $payAddr1                    = str_replace("'", "", $payAddr1);
                $payAddr2                    = '';
                $payZone                     = addslashes($currentOrder['recipient_address']['state']); //selangor
                $payZoneId                   = getZoneId($payZone);
                $payCity                     = addslashes($currentOrder['recipient_address']['city']);
                $payPostCode                 = $currentOrder['recipient_address']['zipcode'];
                $email                       = '';
                $city                        = $currentOrder['recipient_address']['city'];
                $zipcode                     = $currentOrder['recipient_address']['zipcode'];
                $state                       = $currentOrder['recipient_address']['state'];
                $address_string_trim         = ", $city, $zipcode, $state";
                //Format address 1
                if (substr($payAddr1, -strlen($address_string_trim)) === $address_string_trim) {
                    $payAddr1 = substr($payAddr1, 0, strlen($payAddr1) - strlen($address_string_trim));
                }
                $payAddr1 = addslashes($payAddr1);
                
                $shipFName    = $currentOrder['recipient_address']['name'];
                $shipFName    = str_replace("'", "", $shipFName);
                $shipLName    = '';
                $shipAddr1    = $payAddr1;
                $shipAddr2    = '';
                $shipZone     = $payZone;
                $shipZoneId   = $payZoneId;
                $shipCity     = $payCity;
                $shipPostCode = $payPostCode;
                $status       = $currentOrder['order_status'];
                
                $orderData['status'] = $status;
                $statusId            = getOrderStatusId($status);
                //$total = str_replace(",", "", $currentOrder['total_amount']);
                $totalQty            = 0;
                foreach ($currentOrder['items'] as $items) {
                    $totalQty += $items['variation_quantity_purchased'];
                }
                
                
                $shipFee = str_replace(",", "", $currentOrder['estimated_shipping_fee']);
                
                if (!$shipFee) {
                    $shipFee = 0;
                }
                if ($shipFee == 0) {
                    $shipMethod = "Free Shipping";
                } else {
                    $shipMethod = "Shipping";
                }

                //get order items
                $order_items  = $currentOrder['items'];
                $bundle_total = 0;
                if (isset($order_items)) {
                    $itemSkus    = array();
                    $itemGroup   = array();
                    $itemIdQty   = array();
                    $bundleGroup = array();
                    foreach ($order_items AS $item) {
                        
                        $itemIdQty[$item['item_id']] = $item['variation_quantity_purchased'];
                        $itemGroup[]                 = $item;
                    }
                    //initialize variables for storing price from bundle into array.
                    $counting_bundle    = 0;
                    $bundle_price_array = array();
                    //get total of item with bundles through activity within associative array
                    foreach ($shopeeDiscountDetail['order']['activity'] as $Bundle_promotion) {
                        $bundle_total++;
                    }
                    if ($bundle_total > 0) {
                        do {
                            foreach ($shopeeDiscountDetail['order']['activity'][$counting_bundle]['items'] as $index => $bundle) {
                                $original_price          = $shopeeDiscountDetail['order']['activity'][$counting_bundle]['original_price'];
                                $discounted_bundle_price = $shopeeDiscountDetail['order']['activity'][$counting_bundle]['discounted_price'];
                                $discount_percentage_amount = getBundlePercentage($original_price, $discounted_bundle_price);
                                $bundle_price               = $bundle['original_price'];
                                
                                $rounded_amount       = round($bundle_price * ((100 - $discount_percentage_amount) / 100), 3);
                                $bundle_price_array[] = $rounded_amount;
                                
                            }
                            $counting_bundle++;
                            
                            
                        } while ($counting_bundle != $bundle_total);
                    }
                    
                    $finalTotal = 0;
                    $subTotal   = 0;
                    $totalTax   = 0;
                    $order_products = array();
                    // foreach Item in the order
                    foreach ($itemGroup AS $key => $item) {
                        $name  = mysqli_real_escape_string($conn, $item['item_name']);
                        $model = mysqli_real_escape_string($conn, $item['item_sku']);
                        
                        
                        /*
                         * Error handling for null model by replacing it with variation sku
                         */
                        $model_variation = mysqli_real_escape_string($conn, $item['variation_sku']);
                        if ($model == '') {
                            $model = $model_variation;
                        } else if ($model_variation == '' && $model == '') {
                            $model = "-";
                        }
                        
                        // Return BUNDLECODE INTO oc_order_product if the product name contain bundle, else let it empty
                        $matching_code = '';
                        $lower_case_name     = strtolower($name);
                        $find_bundle_in_name = strpos($lower_case_name, 'bundle');
                        $find_bundle_in_name ? $matching_code = "BUNDLECODE" : $matching_code = '';
                        
                        // Assign BUNDLECODE INTO matching code if model is exist in oc_product_bundle_matching
                        if ($matching_code != "BUNDLECODE") {
                            $bundleMatchingList = $bundleList;
                            $lowerCaseModel = strtolower($model);
                            in_array($lowerCaseModel, $bundleMatchingList) ? $matching_code = "BUNDLECODE" : $matching_code = '';
                        }
                        
                        $productDetails = getProductDetails($model);
                        if (empty($productDetails))
                            continue;
                        
                        //$price = $item['variation_discounted_price']; //remove GST
                        $disc_price = $item['variation_discounted_price'];
                        
                        if ($disc_price > 0) {
                            $price = str_replace(",", "", round($disc_price, 3));
                            
                            
                        } else {
                            
                            $item_bundle_price = $bundle_price_array[$key];
                            $price             = str_replace(",", "", round($item_bundle_price, 3));
                        }
                        
                        $tax      = 0;
                        $quantity = $item['variation_quantity_purchased'];
                        $total    = $price * $quantity;
                        //$finalTotal += (($price * $quantity) + ($tax * $quantity));
                        $subTotal += round($total, 3);
                        $totalTax += ($tax * $quantity);
                        
                        $productId   = $productDetails[0];
                        $mainCat     = $productDetails[1];
                        $subCat      = $productDetails[2];
                        $data_source = '';

                        if (!empty($productDetails[3])) {
                            $data_source = $productDetails[3];
                        }

                        // Adding matching_code from db, and validate it
                        // Matching code is used for ERP
                        $db_matching_code = $productDetails[4];
                        if ($matching_code != "BUNDLECODE" && !empty($db_matching_code) ) {
                             $matching_code =  $productDetails[4];
                        }
                        
                        $order_products[] = array(
                        	'product_id'	=> $productId,
                        	'name'			=> $name,
                        	'data_source'	=> $data_source,
                        	'model'			=> $model,
                        	'matching_code'	=> $matching_code,
                        	'quantity'		=> $quantity,
                        	'price'			=> $price,
                        	'total'			=> $total,
                        	'tax'			=> $tax
                        );

                        
                    }

                     // Total Validation, to validate all attributes are filled
                    $grandTotal = ( $subTotal + $shipFee - ($seller_voucher+$coin_amount+$voucher_amount) );
                    $finalTotal = $currentOrder['total_amount'];

                    
                    $validateTotal = abs($grandTotal - $finalTotal);
                    
                    if($validateTotal > 1) {
                    	
                    	echo "\n$ordNo has problem, skipped";
                    	echo "\nGrand total = $grandTotal\nFinal Total = $finalTotal";
                    	echo "\nDifference RM :".round($validateTotal,2)."\n";
                    	continue;
                    }


                    $orderData['shipping_method'] = $shipMethod; //mail
                	//$total = $total + $shipFee - $voucher;
               		$total_payment                = str_replace(",", "", $currentOrder['total_amount']);
                	$total                        = $total_payment;
                
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
                
	                if (mysqli_query($conn, $sql)) {
	                    echo "\nSuccess";
	                } else {
	                    echo mysqli_error($conn);
	                }
                
	                $brp_ord_id = mysqli_insert_id($conn);
	                if ($brp_ord_id == 0) 
	                    continue;
	                echo "\nOrder Id: " . $brp_ord_id . " has been inserted.\n";
	                if (!empty($brp_partner_id)) {
	                    $unique_order_id = $brp_partner_id . "@" . $brp_ord_id;
	                    $sql             = "UPDATE `oc_order` SET unique_order_id = '" . $unique_order_id . "', date_modified = NOW() WHERE order_id = '" . (int) $brp_ord_id . "'";
	                    mysqli_query($conn, $sql);
	                }



                     insert_order_product($conn, $order_products, $brp_ord_id);
                    
                    
                    $sql3       = "INSERT INTO oc_order_total (`order_id`, `code`, `title`, `value`, `sort_order`) 
	                VALUES 
	                ('$brp_ord_id', 'sub_total', 'Sub-Total (before GST)', '$subTotal', '1'),
	                ('$brp_ord_id', 'shipping', '$shipMethod', '$shipFee', '2'),
	                ('$brp_ord_id', 'tax', 'GST KL &amp; SEL (0%)', '$totalTax', '6'),
	                ('$brp_ord_id', 'total', 'Total', '$finalTotal', '7');";
                    mysqli_query($conn, $sql3);
                    
                   
                    // Inserting shopee discount variation into DB
                    $seller_voucher > 0.0 ? mysqli_query($conn, "INSERT INTO oc_order_total (`order_id`, `code`, `title`, `value`, `sort_order`) VALUES ('$brp_ord_id', 'coupon', 'Seller Voucher', '-$seller_voucher', '3');") : '';
                    
                    $voucher_amount > 0.0 ? mysqli_query($conn, "INSERT INTO oc_order_total (`order_id`, `code`, `title`, `value`, `sort_order`) VALUES ('$brp_ord_id', 'coupon', 'Shopee Voucher', '-$voucher_amount', '4');") : '';
                    
                    $coin_amount > 0.0 ? mysqli_query($conn, "INSERT INTO oc_order_total (`order_id`, `code`, `title`, `value`, `sort_order`) VALUES ('$brp_ord_id', 'coupon', 'Shopee Coin', '-$coin_amount', '5');") : '';
                    
                    //send mail
                    $curl     = curl_init();
                    $postData = array(
                        'order_id' => $brp_ord_id
                    );
                    
                    /*
                     * SEND EMAIL
                     * to client. comment out siteURL and curl with CURLOPT_URL
                     * code function sendMail3 located at catalog/controller/product/product
                     */
                    
                    $siteURL  = HTTP_CATALOG . "index.php?route=product/product/sendMail3";
                    
                    
                    $siteUrl = '';
                    curl_setopt($curl, CURLOPT_URL, $siteURL);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($curl, CURLOPT_VERBOSE, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
					
                    $resp = curl_exec($curl);
                    curl_close($curl);
        			
                }
            } else {  // if not exist, then update status only
                $status   = $currentOrder['order_status'];
                $statusId = getOrderStatusId($status);
                
                if ($statusId == 5) { //already delivered
                    echo "No update for order id: " . $existingOrder . " neccessary.\n";
                    continue;
                }
                
                $TheTotal = str_replace(",", "", $currentOrder['total_amount']);
                
                $shipmentStatus = checkShipmentStatus($conn, $ordNo);
                
                if ($shipmentStatus == "23") {
                    if ($status == "READY_TO_SHIP") {
                        $sql = "UPDATE oc_order SET `total` = '$TheTotal',`marketplace_tracking_no` = '$trackingNo', `date_modified` = '$dateMod' 
							WHERE `comment` = 'Shopee Order No: #" . $ordNo . "'";
                        
                    } else {
                        $sql = "UPDATE oc_order SET `total` = '$TheTotal', `order_status_id` = '$statusId', `marketplace_tracking_no` = '$trackingNo', `date_modified` = '$dateMod' 
							WHERE `comment` = 'Shopee Order No: #" . $ordNo . "'";
                        
                    }
                } else {
                    $sql = "UPDATE oc_order SET `total` = '$TheTotal', `order_status_id` = '$statusId', `marketplace_tracking_no` = '$trackingNo', `date_modified` = '$dateMod' 
							WHERE `comment` = 'Shopee Order No: #" . $ordNo . "'";
                }
                
                echo "Order Id: " . $existingOrder . " has been updated.\n";
                mysqli_query($conn, $sql);
                
            }
            
        } else if (isset($results['error'])) {
            print_r("Error retrieving order: " . $results_json);
        } else {
            print_r("Results : " . $results_json);
        }

    }


    releaseCronjob($conn, $cronjob_name);
    
    
    
} else {
    exit('cronjob is already running');
}

insertDatetimeCornjob($conn);


/*
 * Get List of bundle sku from oc_product_bundle_matching
 * return array of list if success and return false if fail
 */
function getBundleMatching($conn)
{
    
    $selectBundleMatching = "SELECT bundle_code from oc_product_bundle_matching";
    $bundleMatchingResult = mysqli_query($conn, $selectBundleMatching);
    
    if (!empty($bundleMatchingResult->num_rows)) {
        
        while ($row = mysqli_fetch_assoc($bundleMatchingResult)) {
            
            extract($row, EXTR_PREFIX_ALL, "bundle");
            
            $bundleMatching[] = strtolower($bundle_bundle_code);
            
        }
        
        return $bundleMatching;
        
    } else {
        
        return false;
    }
}

function getZoneId($zoneName)
{
    $id = 1985; //Kuala Lumpur
    if ($zoneName == "Selangor")
        $id = 1983;
    //else if($zoneName == "Kuala Lumpur") $id = 1985;
    else if ($zoneName == "Terengganu")
        $id = 1984;
    else if ($zoneName == "Sarawak")
        $id = 1982;
    else if ($zoneName == "Sabah")
        $id = 1981;
    else if ($zoneName == "Putrajaya")
        $id = 4035;
    else if ($zoneName == "Penang")
        $id = 1980;
    else if ($zoneName == "Perlis")
        $id = 1979;
    else if ($zoneName == "Perak")
        $id = 1978;
    else if ($zoneName == "Pahang")
        $id = 1977;
    else if ($zoneName == "Negeri Sembilan")
        $id = 1976;
    else if ($zoneName == "Melaka")
        $id = 1975;
    else if ($zoneName == "Labuan")
        $id = 1974;
    else if ($zoneName == "Kelantan")
        $id = 1973;
    else if ($zoneName == "Kedah")
        $id = 1972;
    else if ($zoneName == "Johor")
        $id = 1971;
    
    return $id;
}


/*
 * statusId refering to value from 'oc_order_status'
 * 2    = Processing
 * 5    = Complete
 * 1    = Pending
 * 7    = Canceled
 * 10   = Failed
 */
function getOrderStatusId($status)
{
    $statusId = 2;
    $status   = strtolower($status);
    if ($status == "completed")
        $statusId = 5;
    else if ($status == "unpaid")
        $statusId = 1;
    else if ($status == "ready_to_ship")
        $statusId = 2;
    else if ($status == "shipped")
        $statusId = 3;
    else if ($status == "cancelled")
        $statusId = 7;
    else if ($status == "invalid")
        $statusId = 10; //10 - failed, 16 - voided
    
    return $statusId;
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

function checkOrderExists($conn, $id)
{
    $sql    = "SELECT order_id FROM oc_order WHERE comment = 'Shopee Order No: #$id'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_row($result)) {
        return $row[0];
    } else
        return false;
}

function checkShipmentStatus($conn, $id)
{
    $sql    = "SELECT order_status_id FROM oc_order WHERE comment = 'Shopee Order No: #$id'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_row($result)) {
        return $row[0];
    } else
        return false;
}


function executecURL($parameters, $url, $shopee_API_key)
{
    $encoded                 = array();
    $timenow                 = new DateTime();
    $parameters['timestamp'] = $timenow->getTimestamp();
    $data_string             = json_encode($parameters);
    $concatenated            = $url . "|" . $data_string;
    $api_key                 = $shopee_API_key;
    $signature               = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, FALSE));
    $ch                      = curl_init();
    $headers                 = array();
    $headers[]               = 'Content-Type: application/json';
    $headers[]               = 'Authorization: ' . $signature;
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $data = curl_exec($ch);
    
    curl_close($ch);
    //print_r($data); echo "\n\n";
    
    unset($ch);
    unset($url);
    return $data;
}

/*
 * Get Discounted Margin from bundle
 */
function getBundlePercentage($sum, $disc_sum)
{
    
    $get_percentage = (($sum - $disc_sum) / $sum) * 100;
    return $get_percentage;
}


/*
 *   Get Discount Detail From Shopee via shopee GetEscrowDetails
 */
function getDiscountDetails($ordersn, $partner_id, $shopid, $shopee_API_key)
{
    
    $current_time = date('Y-m-d', time());
    $current_time = strtotime($current_time);
    
    $request_parameters = array(
        'ordersn' => $ordersn,
        'partner_id' => $partner_id,
        'shopid' => $shopid,
        'timestamp' => $current_time
    );
    
    $url = "https://partner.shopeemobile.com/api/v1/orders/my_income";
    
    
    $fetch_json = executecURL($request_parameters, $url, $shopee_API_key);
    $result     = json_decode($fetch_json, true);
    
    return $result;
    
    
}



/*
 *  Function Deprecated, replace with FLOCK
 *  cronjob controller to prevent the same cronjob run at the same time.
 */
function cronjobController($conn, $cronjob_name)
{
    $cronjobControllerSQL = "SELECT * FROM `oc_setting` WHERE `key` LIKE '$cronjob_name' LIMIT 1";
    
    $cronjobControllerResult = $conn->query($cronjobControllerSQL);
    
    if ($cronjobControllerResult->num_rows > 0) {
        
        $row            = $cronjobControllerResult->fetch_assoc();
        $cronjob_status = $row['value'];
        
        if ($cronjob_status > 0) {
            return true;
        } else {
            return false;
        }
        
        
    } else {
        
        /*
         * Inserting into oc_setting table if the cronjob is not marked.
         */
        $insertCronjobSQL = "INSERT INTO `oc_setting` (setting_id, store_id, code, `key`, `value`, serialized) VALUES('','0','Shopee','$cronjob_name','0','0')";
        
        $cronjobInsertResult = $conn->query($insertCronjobSQL);
        
        if ($cronjobInsertResult) {
            
            echo "Cronjob controller key is not exist, system inserted the key as `$cronjob_name` into oc_setting table";
            exit();
            
        }
        
        exit("Cronjob_controller key is missing from oc_setting. Please add it, the configuration as below. \n store_id = 0 \n code = Shopee \n key = cronjob_controller \n value = 0 \n serialized = 0\n");
    }
}


/*
 * Lock the cronjob and prevent same cronjob run twice
 */
function lockCronjob($conn, $cronjob)
{
    
    $updateCronStatus = "UPDATE`oc_setting` SET `value` = 1 WHERE `key` LIKE '$cronjob' LIMIT 1";
    $result           = $conn->query($updateCronStatus);
    
    if ($result) {
        echo "START : '$cronjob' \n";
    } else {
        echo "Something is wrong with lockCronjob method, check it";
    }
}

function releaseCronjob($conn, $cronjob)
{
    
    $updateCronStatus = "UPDATE`oc_setting` SET `value` = 0 WHERE `key` LIKE '$cronjob' LIMIT 1";
    $result           = $conn->query($updateCronStatus);
    
    if ($result) {
        echo "\nSTOP : '$cronjob'";
    } else {
        echo "\nSomething is wrong with Release Cronjob method, check it";
    }
}

//Insert datetime of cronjob
function insertDatetimeCornjob($conn)
{
    
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $dateNow = date('Y-m-d H:i:s');
    
    $sql = "UPDATE `oc_setting` SET `value` = '$dateNow' WHERE `oc_setting`.`key` = 'shopee_last_cronjob_date_order';";
    $conn->query($sql) or die(mysqli_error($conn));
    
    if ($conn->query($sql)) {
        echo "\nCron Job Finished at $dateNow";
    }
    
}


function enableShopeeCronjob($conn)
{
    $sql    = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_enable' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row           = $result->fetch_assoc();
        $shopee_enable = $row['value'];
        
        return $shopee_enable;
    } else {
        return false;
    }
    
}

function getPartnerId($conn)
{
    $sql    = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_partner_id' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row               = $result->fetch_assoc();
        $shopee_partner_id = $row['value'];
        return $shopee_partner_id;
    } else {
        return false;
    }
}




function getShopId($conn)
{
    $sql    = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_shop_id' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row            = $result->fetch_assoc();
        $shopee_shop_id = $row['value'];
        return $shopee_shop_id;
    } else {
        return false;
    }
}


function getApiKey($conn)
{
    $sql    = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_API_key' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row            = $result->fetch_assoc();
        $shopee_API_key = $row['value'];
        return $shopee_API_key;
    } else {
        return false;
    }
}

function getStoreName($conn)
{
    $sql    = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'config_name' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row        = $result->fetch_assoc();
        $store_name = $row['value'];
        return $store_name;
    } else {
        return "";
    }
}


function getBrpPartnerId($conn)
{
    $sql    = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'config_unique_brp_partner_id' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row            = $result->fetch_assoc();
        $brp_partner_id = $row['value'];
        return $brp_partner_id;
    } else {
        return '';
    }
}


function getCustomerDetail($conn)
{
    $sql    = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_dummy_customer_id' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row         = $result->fetch_assoc();
        $customer_id = $row['value'];
    } else {
        $customer_id = 0;
    }
    
    $sql    = "SELECT `customer_group_id` FROM `oc_customer` WHERE `customer_id` = '$customer_id' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row               = $result->fetch_assoc();
        $customer_group_id = $row['customer_group_id'];
    } else {
        $customer_group_id = '';
    }
    
    $customerDetails = array(
        'customer_id' => $customer_id,
        'customer_group_id' => $customer_group_id
    );
    
    return $customerDetails;
}


function insert_order_product($conn, $insert_params, $order_id) {

    foreach ($insert_params as $insert_param) {

        $insert_order_product = "INSERT INTO oc_order_product 
        (`order_id`, 
         `product_id`, 
         `name`, 
         `data_source`, 
         `model`, 
         `matching_code`, 
         `quantity`, 
         `price`, 
         `total`, 
         `tax`)
        VALUES 
        ('".$order_id."',
         '".$insert_param['product_id']."',
         '".$insert_param['name']."', 
         '".$insert_param['data_source']."', 
         '".$insert_param['model']."', 
         '".$insert_param['matching_code']."', 
         '".$insert_param['quantity']."', 
         '".$insert_param['price']."', 
         '".$insert_param['total']."', 
         '".$insert_param['tax']."');";
            
        echo "- Order Product Items inserted.\n";
        mysqli_query($conn, $insert_order_product);
	}
}

/* Write log for malfunction loading
*  eq. when grand total is not match with total
*/
function writeLog($orderNo, $orderID, $status){

	$current_date = date("Y-m-d");
	$current_date_renamed = str_replace("-", "_", $current_date);

	if($status == "staging"){
		//staging
		$dir_location = "/home/polaris/work/atoz_brp_partner/cron/shopee_order_log/";

	}else if($status == "production") {
		//production
		$dir_location = "/var/www/html/cronjob/shopee_order_log/";

	}

	$fileName = $dir_location.$current_date_renamed.'.log';
	//write File if it is not exist, @file_get_content to hide error
	if(@file_get_contents($fileName) == false){
		$logOpen = fopen($fileName, "w");
		chmod($fileName, 0777);
		fclose($logOpen);
		echo "File name . ".$fileName. " Created !";
		}
		$write_text = date("Y/m/d"). " - ".date("h:i:sa")." -- Order Id : " .$orderID. " -- Order No: " .$orderNo . "\n";
		file_put_contents($fileName, $write_text, FILE_APPEND);
		echo $orderNo." Appended into : ".$fileName."\n";
}


?>