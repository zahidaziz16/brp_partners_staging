<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

require_once dirname(__DIR__) . "/config.php";
include 'html2text-master/src/Html2Text.php';
//include "product_quantity_sync.php";
$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//set mysqli to utf8 format
mysqli_set_charset($conn, 'utf8');

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

$cronjob_name = basename(__FILE__, '.php');

# Removing Marker that prevent running twice, Deprecate the faeture.
# Replace with FLOCK instead
// $cronStatus = cronjobController($conn, $cronjob_name);
$cronStatus = false;

if ($cronStatus == false) {

    $updateEnable = getProductUpdateStatus($conn);

    if($updateEnable == false){
        exit("cronjob is disabled");
    }

    lockCronjob($conn, $cronjob_name);

    $sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_enable' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $shopee_enable = $row['value'];
    }

    if (!$shopee_enable) {
        $dateKL = new DateTime("now", new DateTimeZone('Asia/Kuala_Lumpur'));
        echo $dateKL->format('Y-m-d H:i:s') . " Shopee Marketplace is disabled. Cronjob will not be run.";
        exit;
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

    if ($shopee_partner_id == '0' || $shopee_API_key == '0' || $shopee_shop_id == '0') {
        echo $shopee_API_key . "\n";
        echo $shopee_partner_id . "\n";
        echo $shopee_shop_id . "\n";
        echo "Invalid Shopee UserID / ShopID / API key. ";
        exit;
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

// Convert price threshold to take into account of price markup
    if ((int)$shopee_price_threshold > 0) {
        $sql_price_threshold = round(($shopee_price_threshold * 100 / (100 + $shopee_price_markup_percentage)) - $shopee_price_markup_flat, 2);
    }

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $date = new DateTime();

    $parameters = array(
        'shopid' => (integer)$shopee_shop_id,
        'partner_id' => (integer)$shopee_partner_id
    );
    $logistics_temp = array();
    $logistics = array();
    //TEST
    $url = "https://partner.shopeemobile.com/api/v1/logistics/channel/get";
    $temp_parameters = $parameters;
    //$temp_parameters['item_id'] = 1039859576;
    $results_json = executecURL($temp_parameters, $url, $shopee_API_key);
    $results = json_decode($results_json, true);
    if (isset($results['logistics'])) {
        //$logistics_temp = $results['logistics'];
        foreach ($results['logistics'] as $key => $logistic) {
            //Insert enabled logistic (shipping method)
            if ($logistic['enabled']) {
                if ($logistic['fee_type'] == "CUSTOM_PRICE") {
                    if ($shopee_shipping_fee) {
                        $results['logistics'][$key]['shipping_fee'] = (integer)$shopee_shipping_fee;
                    } else {
                        $results['logistics'][$key]['shipping_fee'] = 0;
                    }
                }
            }
        }

        $logistics = $results['logistics'];
    } else if (isset($results['error'])) {
        print_r("Error retrieving logistics: " . $results_json);
    } else {
        print_r("Results : " . $results_json);
    }
    //var_dump($logistics);
    $rowData = array();

//Verify whether the category in opencart is parent and populate into array
    $sql = "SELECT a.category_id, a.shopee_id, b.parent_id FROM `oc_category_to_shopee` a LEFT JOIN `oc_category` b ON (a.category_id = b.category_id)";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $parent = 'Yes';
            if ($row['parent_id'] != 0) {
                $parent = 'No';
            }

            $rowData[] = array(
                1 => $row['category_id'],
                4 => $row['shopee_id'],
                5 => $parent,
            );
        }
    } else {
        echo "No Result";
    }

    $update_count = 0;
    $insert_count = 0;
    echo "Start : " . date('Y-m-d H:i:s') . " \n ";
    {
        //SQL query to extract products list to be synced

        $sql = "SELECT a.product_id,
       a.model,
       a.weight,
       a.length,
       a.height,
       a.width,
       a.quantity,
       a.image,
       a.price,
       a.date_modified,
       b.image2,
       c.special_price,
       c.date_start,
       c.date_end,
       d.description,
       d.meta_description,
       f.categories,
       Coalesce(g.quantity_wms, 0) AS quantity_wms,
       Coalesce(g.quantity_erp, 0) AS quantity_erp
        FROM   oc_product a
                LEFT JOIN (SELECT product_id,
                price AS special_price,
                date_start,
                date_end
                FROM   oc_product_special
                ORDER  BY product_special_id DESC) c
                    ON ( c.product_id = a.product_id )
                LEFT JOIN oc_product_quantity_external g
                    ON ( a.model = g.model )
                LEFT JOIN oc_product_description d
                    ON ( a.product_id = d.product_id )
                LEFT JOIN (SELECT y.product_id                              AS pId,
                                 Group_concat(y.category_id SEPARATOR '|') AS categories
                          FROM   oc_product_to_category y
                          GROUP  BY y.product_id) f
                    ON ( f.pid = a.product_id )
                LEFT JOIN (SELECT product_id,
                                 Group_concat(image SEPARATOR '|') AS image2
                          FROM   oc_product_image
                          WHERE  image != ''
                          GROUP  BY product_id) b
                      ON ( a.product_id = b.product_id )

        WHERE  a.status = 1
               AND a.price >= $sql_price_threshold
               AND a.sync_shopee = 1 
        GROUP  BY a.product_id
        ORDER  BY a.product_id ASC";

        //             You can add additional field to debug like sql below :
        //                AND a.model = 'B TN 2360'
        //                AND d.name like '%Marie%'
        //                AND a.product_id >= 16850
    }
    $result = mysqli_query($conn, $sql);
    $skuList = array();
    $updateNo = 0;
    while ($row = mysqli_fetch_assoc($result)) {

        // Excluding image's path from being clean up. Image required full path including double white space and any duplicate symbol to work.
        if (!$row['image']) {
            $row = preg_replace("/\s+/", " ", array_map('trim', $row));
        }

        $productId = $row['product_id'];
        $model = str_replace('  ', ' ', $row['model']);

        // Decoding HTML tag such as &amp; and many more
        $model = html_entity_decode($model);
        $price = round(($row['price'] * (1 + $shopee_price_markup_percentage / 100) + $shopee_price_markup_flat), 2);
        $spPrice = null;
        //    $spPrice = $row['special_price'];
        //    if($spPrice != NULL && $spPrice > 0) {
        //    $spPrice = round(($row['special_price'] * ( 1 + $shopee_price_markup_percentage/100) + $shopee_price_markup_flat), 2);
        //    }
        $spDateFrom = $row['date_start'];
        $spDateTo = $row['date_end'];
        $desc = utf8_decode($row['description']);
        $html = new \Html2Text\Html2Text($desc);
        $desc = $html->getText();

        $weight = $row['weight'];

        if ($weight < 0.01) {

            $weight = 0.01;

        }

        // date matching to determine update
        $modifiedDate = $row['date_modified'];
        $lastProductSync = getLastSyncProductTime($conn);


        $metaDesc = $row['meta_description'];
        $categories = $row['categories'];
        $qty = (int)$row['quantity'];
        $qty_wms = (int)$row['quantity_wms'];
        $qty_erp = (int)$row['quantity_erp'];
        if ($qty < 1) {
            $qty = 0;
        }
        if ($qty_erp < 2) {
            $qty_erp = 0;
        } else {
            $qty_erp = round($qty_erp / 2); //Take 50%
        }
        $qty = $qty + $qty_wms + $qty_erp;

        $image = $row['image'];
        $groupImages = $row['image2'];
        //$image = str_replace(" ", "%20", $row['image']);
        //$groupImages = str_replace(" ", "%20", $row['image2']);
        $strImages = array();
        if (!empty($image) && empty($groupImages)) {
            $groupImages = $image;
        }
        if (!empty($groupImages) || $groupImages != null) {
            $images = explode("|", $groupImages);
        } else {
            $images = array();
        }

        $price = number_format($price, 2, '.', '');

        $width = round($row['width']);

        if ($width < 1) {
            $width = 1;
        }

        $length = round($row['length']);

        if ($length < 1) {
            $length = 1;
        }

        $height = round($row['height']);

        if ($height < 1) {
            $height = 1;
        }

        if (empty($desc)) {
            $desc = $metaDesc;
        }

        $desc = str_replace('&quot;', '"', $desc);

        // Overriding length of description to below 3000. (* Shopee Malaysia accept between 20 to 3000 characters *)
        if (strlen($desc) > 3000) {
            $desc = substr($desc, 0, 2950);
        }

        //check if product exists in shopee, if true return item_id else false.
        $shopee_item_id = searchProductExists($productId, $model, $conn);
        $updateDetail = '';

        if ($updateEnable == 3 && $shopee_item_id == false) {
            //If product doesn't exist -> add Product
            if ($shopee_item_id == '0') {

                echo "\n New item: : " . $model . "\n";
                $item_id = 0;
                // Skip from inserting into Shopee due to quantity is zero.
                if ($qty == 0) {
                    echo 'Quantity is 0. skipped';
                    continue;
                }

                $item_id = addProduct($conn, $rowData, $productId, $model, $qty, $price, $images, $shopee_partner_id, $shopee_shop_id, $shopee_API_key, $logistics);
                if ($item_id == null) {
                    continue;
                }

                // addProduct return null if the category is invalid, check product and its category
                if ($item_id && $item_id != null) {
                    $insert_count++;
                    //Check if discount expired
                    if (strtotime($spDateTo) <= time() && $spDateTo != '0000-00-00') {
                        continue;
                    }
                    //Add discount price
                    if ($spPrice != null && $spPrice > 1 && $spDateTo != null && $spDateFrom != null) {

                        $discount_id = addProductDiscount($conn, $item_id, $productId, $model, $spPrice, $spDateTo, $spDateFrom, $shopee_partner_id, $shopee_shop_id, $shopee_API_key);

                    }
                }
                continue;
            }

        } // end Add new product


        /*
            // do update if modified date is latest than cronjob sycn
        else if ($modifiedDate > $lastProductSync) {

            $shopeeCategoryId = getShopeeCategoryId($shopee_item_id, $shopee_partner_id, $shopee_shop_id, $shopee_API_key);

            $getNameSql = "SELECT name FROM oc_product_description WHERE product_id = '$productId' LIMIT 1";
            $getNameResult = $conn->query($getNameSql);

            if($getNameResult->num_rows > 0){
                $row = $getNameResult->fetch_assoc();
                $shopeeName = html_entity_decode($row['name']);
                $shopeeName = preg_replace('/[^A-Za-z0-9\-]/', ' ', $shopeeName);

                //Shopee character limit 80 characters for name
                if (strlen($shopeeName) > 80) {
                    $shopeeName = substr($shopeeName, 0, 80);
                }

            }

            $desc = html_entity_decode($desc);
            $desc = utf8_decode($desc);
            $html = new \Html2Text\Html2Text($desc);
            $desc = $html->getText();
            // encode into utf8, to prevent json breakdown
            $desc = utf8_encode($desc);
            // clean up characters that are not suitable with utf8
            $desc = str_replace("?", "", $desc);

             if (strlen($desc) > 3000) {

                $desc = substr($desc, 0, 2950);
            }


            $updateParam = array(
                'shopid'     => (integer) $shopee_shop_id,
                'partner_id' => (integer) $shopee_partner_id,
                'item_id'    => (integer) $shopee_item_id,
                'category_id'=> (integer) $shopeeCategoryId,
                'name'       => (string)  $shopeeName,
                'description'=> (string)  $desc,
                'item_sku'   => (string)  $model,
                'logistics'  => $logistics,
                'weight'     => (float) $weight,


            );

            //  'package_length' => (integer)$length,
            //  'package_width' => (integer)$width,
            //  'package_height' => (integer)$height


            $updateDetailStatus = updateShopeeProductDetail($updateParam, $shopee_API_key);


            $updateDetail = $updateDetailStatus['msg'];

        }


        echo "\n Update item: " . $model . "\n";

        if (!empty($updateDetail) || $updateDetail != null) {
            $updateNo++;
            echo "$updateNo -- Product Detail : " . $updateDetail . "\n";
        }
        */

        // Update Stock Only OR Product Price & Stock Level OR Add New Products
        else if ($updateEnable == 2 || $updateEnable == 1 || $updateEnable == 3) {

            // Skipping loop for item that is not registered to shopee
            if($shopee_item_id == false){
                continue;
            } else {
                echo "\nUpdate Product : ".$model. "\n";
            }

            //Initialize parameters
            $update_parameters = array(
                'shopid' => (integer)$shopee_shop_id,
                'partner_id' => (integer)$shopee_partner_id,
                'item_id' => (integer)$shopee_item_id,
            );

            //Update stock
            $url = "https://partner.shopeemobile.com/api/v1/items/update_stock";
            $update_parameters_stock = $update_parameters;
            //$qty = 0;
            $update_parameters_stock['stock'] = (integer)$qty;
            $results_json = executecURL($update_parameters_stock, $url, $shopee_API_key);
            $results = json_decode($results_json, true);

            if (isset($results['item'])) {
                
                print_r("Successfully updated stock : " . $results['item']['stock'] . "\n");
                $update_count++;
            } else if (isset($results['error'])) {
                print_r("Error updating stock: " . $results['msg']);
            } else {
                print_r("Results : " . $results);
            }
        }

        // Product Price & Stock Level OR Add New Products enable
        if ($updateEnable == 1 || $updateEnable == 3) {
            $discount = checkDiscountExists($productId, $conn);

            //Delete existing discount that's removed / expired
            if (!empty($discount) && ($spPrice == null || (strtotime($spDateTo) <= time() && $spDateTo != '0000-00-00'))) {

                $url = "https://partner.shopeemobile.com/api/v1/discount/delete";
                $delete_discount_parameters = array(
                    'shopid' => (integer)$shopee_shop_id,
                    'partner_id' => (integer)$shopee_partner_id,
                    'discount_id' => (integer)$discount_id,
                );
                $results_json = executecURL($delete_discount_parameters, $url, $shopee_API_key);
                $results = json_decode($results_json, true);

                if (isset($results['discount_id'])) {
                    print_r("Successfully deleted discount : " . $results['msg'] . "\n");
                    deleteShopeeProductDiscount($productId, $conn);
                    $discount = array();
                } else if (isset($results['error'])) {
                    print_r("Error deleting discount: " . $results['msg']);
                } else {
                    print_r("Results : " . $results);
                }
            }

            // Skip updating price if discount exists as normal price cannot be edited during promotional period
            if (!$discount) {
                //Update price
                $url = "https://partner.shopeemobile.com/api/v1/items/update_price";
                $update_parameters_price = $update_parameters;
                $update_parameters_price['price'] = (float)$price;
                $results_json = executecURL($update_parameters_price, $url, $shopee_API_key);
                $results = json_decode($results_json, true);

                if (isset($results['item'])) {
                    print_r("Successfully updated price : " . $results['item']['price'] . "\n");
                } else if (isset($results['error'])) {
                    print_r("Error updating price: " . $results['msg']);
                } else {
                    print_r("Delete discount Results : " . $results_json);
                }
            }

            if ($discount) {

                $discount_id = $discount['discount_id'];
                $discount_parameters = array(
                    'shopid' => (integer)$shopee_shop_id,
                    'partner_id' => (integer)$shopee_partner_id,
                    'discount_id' => (integer)$discount_id,
                );

                //Update discount
                if ($spPrice != null && $spPrice > 1 && $spDateTo != null && $spDateFrom != null) {

                    $spDateStartFlag = false;
                    $spDateEndFlag = false;

                    if ($spPrice != $discount['price']) {
                        $url = "https://partner.shopeemobile.com/api/v1/discount/items/update";

                        $item_id = $discount['item_id'];
                        $item_arr = array();
                        $item_arr['item_id'] = (int)$item_id;
                        $item_arr['item_promotion_price'] = (float)$spPrice;

                        $items = array();
                        $items[] = $item_arr;

                        $update_discount_item_parameters = $discount_parameters;
                        $update_discount_item_parameters['items'] = $items;

                        $results_json = executecURL($update_discount_item_parameters, $url, $shopee_API_key);
                        $results = json_decode($results_json, true);

                        if (isset($results['discount_id'])) {
                            print_r("Successfully updated discount price : " . $results['msg'] . "\n");
                            updateShopeeProductDiscount($conn, $productId, $spPrice);
                        } else if (isset($results['error'])) {
                            print_r("Error updating discount price: " . $results['msg']);
                        } else {
                            print_r("Update discount item Results : " . $results_json);
                        }
                    }

                    if ($spDateFrom != $discount['discount_date_start'] && strtotime($spDateFrom) > time() && strtotime($discount['discount_date_start']) > time()) {
                        $spDateStartFlag = true;
                    } else {
                        //Disable updating to DB
                        $spDateFrom = false;
                    }

                    if ($spDateTo != $discount['discount_date_end'] && strtotime($spDateTo) < strtotime($discount['discount_date_end'])) {
                        $spDateEndFlag = true;
                    } else {
                        //Disable updating to DB
                        $spDateTo = false;
                    }

                    if ($spDateStartFlag || $spDateEndFlag) {
                        $url = "https://partner.shopeemobile.com/api/v1/discount/update";

                        $update_discount_parameters = $discount_parameters;
                        if ($spDateStartFlag) {
                            $update_discount_parameters['start_time'] = strtotime($spDateFrom);
                        }
                        if ($spDateEndFlag) {
                            $update_discount_parameters['end_time'] = strtotime($spDateTo);
                        }
                        $results_json = executecURL($update_discount_parameters, $url, $shopee_API_key);
                        $results = json_decode($results_json, true);

                        if (isset($results['discount_id'])) {
                            print_r("Successfully updated discount date : " . $results['msg'] . "\n");
                            updateShopeeProductDiscount($conn, $productId, false, $spDateFrom, $spDateTo);
                        } else if (isset($results['error'])) {
                            print_r("Error updating discount date: " . $results['msg']);
                        } else {
                            print_r("Update discount time Results : " . $results_json);
                        }
                    }
                }
            } else {
                //Add new discount
                if (strtotime($spDateTo) <= time() && $spDateTo != '0000-00-00') {
                    continue;
                }
                if ($spPrice != null && $spPrice > 1 && $spDateTo != null && $spDateFrom != null) {
                    $item_id = (int)$shopee_item_id;
                    $discount_id = addProductDiscount($conn, $item_id, $productId, $model, $spPrice, $spDateTo, $spDateFrom, $shopee_partner_id, $shopee_shop_id, $shopee_API_key);

                }
            }

        }
    }
        echo "\n\n Products updated: " . $update_count . ". Products created: " . $insert_count . "\n";
        echo date('Y-m-d H:i:s');

//set product to disabled
        $disable_total = 0;
        $disable_counter = 0;
// ADD INTO SQL ITEM_ID filter
        $sql = "SELECT a.product_id, a.model, b.item_id, a.status FROM oc_shopee_products b
        LEFT JOIN oc_product a ON (b.product_id = a.product_id)
        WHERE (a.status = 0 OR a.sync_shopee = 0) AND a.price >= 1 OR (a.status = 1 AND a.sync_shopee = 1 AND a.price <= $sql_price_threshold AND a.price >= 1)
        GROUP BY a.product_id";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $row = preg_replace("/\s+/", " ", array_map('trim', $row));
            $productId = $row['product_id'];
            $model = $row['model'];
            $status = $row['status'];
            $shopee_item_id = $row['item_id'];
            $update_parameters = array(
                'shopid' => (integer)$shopee_shop_id,
                'partner_id' => (integer)$shopee_partner_id,
                'item_id' => (integer)$shopee_item_id,
            );

            //Disable product (Set stock to 0)
            $url = "https://partner.shopeemobile.com/api/v1/items/update_stock";
            $update_parameters_disable = $update_parameters;
            $update_parameters_disable['stock'] = 0;
            $results_json = executecURL($update_parameters_disable, $url, $shopee_API_key);
            $results = json_decode($results_json, true);
            echo "\n Disable item: " . $model . "\n";
            if (isset($results['item'])) {
                print_r("Successfully disabled product : " . $results['item']['item_id'] . "\n");
                $disable_counter++;
            } else if (isset($results['error'])) {
                print_r("Error disabling stock: " . $results['msg']);
            } else {
                print_r("Results : " . $results_json);
            }
            $disable_total++;
        }

        echo "\n Total products to be disabled: " . $disable_total . " \nTotal products disabled in Shopee: " . $disable_counter . "\n";

        //Insert datetime of cronjob last sync into DB
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $dateNow = date('Y-m-d H:i:s');

        $sql = "UPDATE `oc_setting` SET `value` = '$dateNow' WHERE `oc_setting`.`key` = 'shopee_last_cronjob_date_product';";
        $conn->query($sql) or die(mysqli_error($conn));

        releaseCronjob($conn, $cronjob_name);



} else {
    exit("\n Cronjob is running, exiting...");
}


/*
 * return
 * 3    =>  Add New Products
 * 2    =>  Stock Level Only
 * 1    =>  Product Price & Stock Level
 * 0    =>  Disable
 */
function getProductUpdateStatus($conn)
{

    $selectUpdateEnable = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'shopee_add_product_enable' LIMIT 1";
    $resultUpdateEnable = $conn->query($selectUpdateEnable);

    if ($resultUpdateEnable->num_rows > 0) {
        $row = $resultUpdateEnable->fetch_assoc();
        $updateEnable = $row['value'];
        return $updateEnable;
    } else {
        $updateEnable = false;
        return $updateEnable;
    }

}

/*
    search product within oc_shopee_products table
 */
function searchProductExists($product_id, $model, $conn)
{
    //$item_id = '0';

    $sql = "SELECT item_id FROM `oc_shopee_products` WHERE product_id = $product_id LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $item_id = $row['item_id'];

        return $item_id;
        
    } else {

        return false;
    }

}

function saveShopeeProduct($itemId, $productId, $model, $conn)
{

    /* 
     * prepare return object or false
     * prepare allows single quote to be inserted into db 
     */
    $productId = (integer)$productId;
    $itemId = (integer)$itemId;
    $model = (string)$model;
    $currentTime = (string)date("Y-m-d H:i:s");

    $shopeeProductSql = $conn->prepare("INSERT INTO oc_shopee_products (product_id, item_id, model, date_added) VALUES (?, ?, ?, ?);");
    $shopeeProductSql->bind_param("iiss", $productId, $itemId, $model, $currentTime);
    $shopeeProductResult = $shopeeProductSql->execute();

    if ($shopeeProductResult != false) {
        return true;
    } else {
        return false;
    }

}

function checkDiscountExists($product_id, $conn)
{
    $resultRow = array();

    $sql = "SELECT * FROM `oc_shopee_products_discount` WHERE `product_id` = '$product_id' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $resultRow = $result->fetch_assoc();
    }

    return $resultRow;
}

function saveShopeeProductDiscount($discount_id, $product_id, $item_id, $model, $spPrice, $discount_date_start, $discount_date_end, $conn)
{
    $sql = "INSERT INTO `oc_shopee_products_discount` (`product_id`, `discount_id`, `item_id`, `model`, `price`, `discount_date_start`, `discount_date_end`, `date_added`, `date_modified`) VALUES ";

    $sql .= "('$product_id', '$discount_id', '$item_id', '$model', '$spPrice', '$discount_date_start', '$discount_date_end', NOW(), NOW());";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

function deleteShopeeProductDiscount($product_id, $conn)
{
    $sql = "DELETE FROM `oc_shopee_products_discount` WHERE `product_id` = '" . (int)$product_id . "'";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

function updateShopeeProductDiscount($conn, $product_id, $spPrice = false, $discount_date_start = false, $discount_date_end = false)
{
    $sql = "UPDATE `oc_shopee_products_discount` SET ";

    if ($spPrice) {
        $sql .= " `price` = '$spPrice', ";
    }

    if ($discount_date_start) {
        $sql .= " `discount_date_start` = '$discount_date_start', ";
    }

    if ($discount_date_end) {
        $sql .= " `discount_date_end` = '$discount_date_end', ";
    }

    $sql .= " `date_modified` = NOW() ";

    $sql .= " WHERE `product_id` = '" . (int)$product_id . "'";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

function getCategoryAttributes($catId, $parameters, $shopee_API_key)
{
    $attributes = array();
    $url = "https://partner.shopeemobile.com/api/v1/item/attributes/get";

    $temp_parameters = $parameters;
    $temp_parameters['category_id'] = (integer)$catId;
    $results_json = executecURL($temp_parameters, $url, $shopee_API_key);
    $results = json_decode($results_json, true);

    if (isset($results['attributes'])) {
        $attributes = $results['attributes'];
        return $attributes;
    } else if (isset($results['error'])) {
        print_r("Error retrieving attributes: " . $results_json);
        return "ERROR"; //.$results['msg']."\n";
    } else {
        print_r("Results : " . $results_json);
        return "BAD_GATEWAY";
    }
    return $results;
}

function addProduct($conn, $rowData, $productId, $model, $qty, $price, $images, $shopee_partner_id, $shopee_shop_id, $shopee_API_key, $logistics = array())
{

    $temp_parameters = array(
        'shopid' => (integer)$shopee_shop_id,
        'partner_id' => (integer)$shopee_partner_id,
    );
    $url = "https://partner.shopeemobile.com/api/v1/item/add";

    $sql = "SELECT a.image, a.weight AS package_weight, a.length AS package_length, a.width AS package_width, a.height AS package_height,
                   b.name, b.description AS description2, b.meta_description AS description, d.name AS brand, f.categories,
                   pa2.text AS storage, pa3.text AS display, pa4.text AS package_content
            FROM oc_product a LEFT JOIN oc_product_description b ON (a.product_id = b.product_id)
            LEFT JOIN oc_manufacturer d ON (a.manufacturer_id = d.manufacturer_id)
            LEFT JOIN
            (
                SELECT y.product_id AS pId, GROUP_CONCAT(y.category_id SEPARATOR '|') AS categories
                FROM oc_product_to_category y GROUP BY y.product_id
            ) f ON (f.pId = a.product_id)
            LEFT JOIN oc_product_attribute pa2 ON pa2.product_id = a.product_id AND pa2.attribute_id = 52
            LEFT JOIN oc_product_attribute pa3 ON pa3.product_id = a.product_id AND pa3.attribute_id = 53
            LEFT JOIN oc_product_attribute pa4 ON pa4.product_id = a.product_id AND pa4.attribute_id = 37
            WHERE a.product_id = '$productId' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $cleanRegex = "/(™|®|©|&trade;|&reg;|&copy;|&#8482;|&#174;|&#169;)/";

    if ($row = mysqli_fetch_assoc($result)) {

        // clean up all white space.
        $row = preg_replace("/\s+/", " ", array_map('trim', $row));

        $strImages = array();

        $image = $row['image'];
        $pWeight = (float)$row['package_weight'];
        $pLength = (float)$row['package_length'];
        $pWidth = (float)$row['package_width'];
        $pHeight = (float)$row['package_height'];
        $brand = $row['brand'];
        $categories = $row['categories'];
        $storage = $row['storage'];
        $display = $row['display'];
        $packageContent = trim($row['package_content']);
        $name = html_entity_decode($row['name']);
        $name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name);
        $shortDesc = $row['description'];
        $shortDesc = html_entity_decode($shortDesc);
        $desc = html_entity_decode($row['description2']);
        $desc = utf8_decode($desc);
        $desc = str_replace('&nbsp;', ' ', $desc);
        $html = new \Html2Text\Html2Text($desc);
        $desc = $html->getText();

        //Shopee character limit 80 characters for name
        if (strlen($name) > 80) {
            $name = substr($name, 0, 80);
        }
        //Shopee character limit 3000 characters for desc, minimum above 20 characters
        if (strlen($desc) > 3000) {
            //$desc = $shortDesc;
            $desc = substr($desc, 0, 2950);
        }

        if (empty($shortDesc)) {
            $shortDesc = $name;
        }

        if (empty($desc)) {
            $desc = $shortDesc;
        }

        $desc = str_replace('&quot;', '"', $desc);
        $desc = str_replace('&nbsp;', ' ', $desc);

        if ($brand == null) {
            $brand = "No Brand";
        }

        if (empty($categories) || $categories == '') {

            echo "\nCategory is not set at Opencart";
            return null;
        }

        if ($categories != '' && $categories != null) {
            $catArr = explode("|", $categories); //array of categories that match this product
            $catId = "";
            foreach ($rowData as $value) {

                # find if product under a parent category id
                if (in_array($value[1], $catArr) && $value[5] == "Yes"){
                    $catId = $value[4];
                    break;
                }

                # find a child category id
                if (in_array($value[1], $catArr) && $value[5] == "No") {
                    $catId = $value[4];
                    break;
                }
                
            }

            if ($catId != "") {
                //match found
                $arr_images = array();
                if (empty($images) && !empty($image)) {
                    array_push($images, $image);
                }
                foreach ($images as $img) {
                    //migrate images before uploading

                    if (strpos($img, "brp.com.my") === false) {
                        //this is for production, testing site using whatever domain you have on your partner with http://
                        $defaultPath = ''.HTTP_SERVER;
                        $defaultPath = str_replace('https', 'http', $defaultPath);
                        $imageURL = $defaultPath .'image/'.$img;

                        //for local testing
                        //$imageURL = 'http://wonderworld.com.my/image/' . $img;
                    } else {
                        $imageURL = $img;
                    }


                    //new image encoding, breakdown images's path and decode each one and assemble back.
                    $imageSplit = explode("/", $imageURL);

                    // Initiating container for image;
                    $reconstructImage = '';

                    foreach ($imageSplit as $index => $singleImage) {

                        if ($index > 0) {
                            $reconstructImage .= '/' . rawurlencode($singleImage);
                        } else {
                            $reconstructImage .= rawurlencode($singleImage);
                        }

                    }

                    $imageURL = $reconstructImage;
                    // Clean up ':' represent as %3A on HTTP_SERVER
                    $imageURL = str_replace('%3A', ':', $imageURL);
                    $image_URL = $imageURL;
                    $arr_images[] = array('url' => $image_URL);

                }
                if (empty($arr_images)) {
                    echo "No image for product: " . $model . "\n";
                    return;
                }


                //List of required normal attributes to be ignored from auto-selecting values
                $arrExcludedNormalAttributes = array('brand');

                $attributesList = getCategoryAttributes($catId, $temp_parameters, $shopee_API_key);

                //skipping if there is an error on shopee's side or category not being set properly
                if ($attributesList == "ERROR") {

                    echo "Invalid Category, maybe category is not set";
                    return null;

                } else if ($attributesList == "ERROR_GATEWAY") {

                    echo "502 Bad Gateway";
                    return null;
                }


                $arrNormalAttr = array();
                $brand_counter = 0;
                foreach ($attributesList as $attribute) {
                    $resultText = "";

                    $attribute['is_mandatory'] = !empty($attribute['is_mandatory']) ? $attribute['is_mandatory'] : 0;

                    if ($attribute['is_mandatory'] == 1) {

                        //Check for brand
                        if (strtolower($attribute['attribute_name']) == 'brand' && $brand_counter < 1) {

                            $brand_counter++;
                            // Getting Brand List from Shopeee API

                            $brandList = getShopeeBrandList($catId, 'en', $shopee_partner_id, $shopee_shop_id, $shopee_API_key);
                            $shopeeBrandList = array();
                            foreach ($brandList['attributes']['0']['options'] as $shopeeBrand) {

                                $shopeeBrandList[] = $shopeeBrand;

                            }

                            $oc_brand = $row['brand'];

                            //checking if shopee brand exist

                            if (in_array($oc_brand, $shopeeBrandList)) {
                                $brand = $oc_brand;
                            } else {
                                $brand = $shopeeBrandList[0];
                            }

                            $arrNormalAttr[] = array(
                                'attributes_id' => $attribute['attribute_id'],
                                'value' => $brand,
                            );

                        }

                        //Skip excluded attributes

                        if (in_array(strtolower($attribute['attribute_name']), $arrExcludedNormalAttributes)) {
                            continue;
                        }
                        if (empty($attribute['options'])) {
                            $resultText = "Not Specified";
                        } else {
                            $resultText = $attribute['options'][0];
                        }

                        $temp_arr_attribute = array(
                            'attributes_id' => $attribute['attribute_id'],
                            'value' => $resultText,
                        );
                        $arrNormalAttr[] = $temp_arr_attribute;
                    }

                }

                //}
                //end category attribute

                //Initialize parameters for POST
                $temp_parameters['category_id'] = (integer)$catId;
                $temp_parameters['name'] = utf8_encode($name);
                $temp_parameters['description'] = utf8_encode($desc);
                $temp_parameters['price'] = (float)$price;
                $temp_parameters['stock'] = (integer)$qty;
                $temp_parameters['item_sku'] = $model;

                // Shopee only accpet maximum 9 images, above 9 remove them.
                $image_number = sizeof($arr_images);
                if ($image_number >= 9) {

                    $unset_image_number = $image_number - 9;
                    for ($i = 0; $i < $unset_image_number; $i++) {
                        unset($arr_images[$i + 9]);
                    }

                }

                $temp_parameters['images'] = $arr_images;
                $temp_parameters['attributes'] = $arrNormalAttr;
                $temp_parameters['logistics'] = $logistics;
                /*
                logistics
                  logistic_id
                  enabled
                  shipping_fee
                 */

                // shopee accepts weight between 0.01 and 1000000 KG
                if ($pWeight < 0.01) {

                    $pWeight = 0.01;

                }

                $temp_parameters['weight'] = (float)$pWeight;

                if ($pLength <= 0) {
                    $pLength = 5;
                }

                if ($pWidth <= 0) {
                    $pWidth = 5;
                }

                if ($pHeight <= 0) {
                    $pHeight = 5;
                }

                $results_json = executecURL($temp_parameters, $url, $shopee_API_key);
                $results = json_decode($results_json, true);
                print_r("Results : " . $results['msg']);

                if (isset($results['item_id'])) {
                    $item_id = $results['item_id'];
                    $saveShopeeProductStatus = saveShopeeProduct($item_id, $productId, $model, $conn);

                    /*
                     * Output productId when fail to save to oc_shopee_product table.
                     * you may neet to rectify this issue immediately, as it is urgent and may create duplication of data at shopee
                     */
                    if ($saveShopeeProductStatus == false) {
                        echo "\n *** '$productId' - Product is not saved into oc_shopee_products *** \n";
                    }

                    return $item_id;
                } else if (isset($results['error'])) {
                    print_r(" Error adding product: " . $results['msg']);

                    if ($results['msg'] == "All images download fail") {
                        echo "\n image url : ";
                        print_r($temp_parameters['images']);
                    }

                    if ($results['msg'] == " Description length invalid") {
                        $descriptionLength = strlen($desc);
                        echo $results['msg'] . " Length : " . $descriptionLength . "characters";
                    }

                    echo "\n";
                } else {
                    print_r("Results : " . $results['msg']);
                }

            } else {
                echo "Category is disabled / not matched. \n";
            }
        }
    }
}


function getShopeeCategoryId($item_id, $partner_id, $shop_id, $shopee_API_key)
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


    $fetch_json = executecURL($request_parameters, $url, $shopee_API_key);
    $result = json_decode($fetch_json, true);

    return $result['item']['category_id'];


}

function updateShopeeProductDetail($requiredParam, $apiKey)
{

    $current_time = date('Y-m-d', time());
    $current_time = strtotime($current_time);

    $requiredParam['timestamp'] = $current_time;

    $url = "https://partner.shopeemobile.com/api/v1/item/update";
    //print_r($requiredParam);
    $fetch_json = executecURL($requiredParam, $url, $apiKey);
    $result = json_decode($fetch_json, true);

    return $result;

}


function addProductDiscount($conn, $item_id, $product_id, $model, $spPrice, $spDateTo, $spDateFrom, $shopee_partner_id, $shopee_shop_id, $shopee_API_key)
{
    $temp_parameters = array(
        'shopid' => (integer)$shopee_shop_id,
        'partner_id' => (integer)$shopee_partner_id,
    );
    $url = "https://partner.shopeemobile.com/api/v1/discount/add";

    //Initialize discount start date
    if (time() >= strtotime($spDateFrom)) {
        //Add 2 minutes as shopee doesn't allow date that have passed
        $discount_date_start_timestamp = strtotime("+2 minutes");
    } else {
        $discount_date_start_timestamp = strtotime($spDateFrom);
    }

    //Initialize discount end date
    if ($spDateTo == '0000-00-00') {
        $discount_date_end_timestamp = date('Y-m-t', strtotime('+2 years'));
        $discount_date_end_timestamp = strtotime($discount_date_end_timestamp);
    } else if (strtotime($spDateTo) >= time()) {
        $discount_date_end_timestamp = strtotime($spDateTo);
    } else {
        return false;
    }

    //Initialize discount item
    $item_arr = array();
    $item_arr['item_id'] = $item_id;
    $item_arr['item_promotion_price'] = (float)$spPrice;
    $item_arr['purchase_limit'] = 0;

    $items = array();
    $items[] = $item_arr;

    //Initialize curl POST parameters
    $temp_parameters['discount_name'] = "Product discount " . $product_id;
    $temp_parameters['start_time'] = $discount_date_start_timestamp;
    $temp_parameters['end_time'] = $discount_date_end_timestamp;
    $temp_parameters['items'] = $items;

    $results_json = executecURL($temp_parameters, $url, $shopee_API_key);
    $results = json_decode($results_json, true);

    if (isset($results['discount_id'])) {
        print_r("Successfully added discount : " . $results_json . "\n");
        $discount_id = $results['discount_id'];
        if (isset($results['count']) && $results['count'] > 0) {
            saveShopeeProductDiscount($discount_id, $product_id, $item_id, $model, $spPrice, $spDateFrom, $spDateTo, $conn);
        } else {
            print_r("Error adding product discount item: " . $results_json . "\n");
        }
        return $discount_id;
    } else if (isset($results['error'])) {
        print_r("Error adding product discount: " . $results_json);
        echo "\n"; //print_r($temp_parameters);//echo "</pre>";
    } else {
        print_r("Add discount Results : " . $results_json);
    }

}

/*
 * To get shopee brand list base on product category / $catId
 * some categories require brand meanwhile other may not require brand to be input.
 */
function getShopeeBrandList($category_id, $shopee_language, $partner_id, $shop_id, $shopee_API_key)
{

    $current_time = date('Y-m-d', time());
    $current_time = strtotime($current_time);

    $request_parameters = array(
        'category_id' => (integer)$category_id,
        'language' => $shopee_language,
        'partner_id' => (integer)$partner_id,
        'shopid' => (integer)$shop_id,
        'timestamp' => $current_time,
    );

    $url = "https://partner.shopeemobile.com/api/v1/item/attributes/get";

    $fetch_json = executecURL($request_parameters, $url, $shopee_API_key);
    $result = json_decode($fetch_json, true);

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

        $row = $cronjobControllerResult->fetch_assoc();
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
        $insertCronjobSQL = "INSERT INTO `oc_setting` (setting_id, store_id, code, `key`, `value`, serialized) VALUES('','0','Shopee','$cronjob_name','1','0')";

        $cronjobInsertResult = $conn->query($insertCronjobSQL);

        if ($cronjobInsertResult) {

            echo "Cronjob controller key is not exist, system inserted the key as `$cronjob_name` into oc_setting table";
            return false;

        }

        exit("Cronjob_controller key is missing from oc_setting. Please add it, the configuration as below. \n store_id = 0 \n code = Shopee \n key = cronjob_controller \n value = 0 \n serialized = 0\n");
    }
}


function getLastSyncProductTime($conn)
{

    $selectCronjobSql = "SELECT oc_setting.value FROM oc_setting WHERE `key` LIKE 'shopee_last_cronjob_date_product' LIMIT 1";
    $selectCronjobResult = $conn->query($selectCronjobSql);

    if ($selectCronjobResult->num_rows > 0) {
        $row = $selectCronjobResult->fetch_assoc();
        $lastProductShopeeSycn = $row['value'];
        return $lastProductShopeeSycn;
    } else {
        return 0;
    }
}


/*
 * Lock the cronjob and prevent same cronjob run twice
 */
function lockCronjob($conn, $cronjob)
{

    $updateCronStatus = "UPDATE`oc_setting` SET `value` = 1 WHERE `key` LIKE '$cronjob' LIMIT 1";
    $result = $conn->query($updateCronStatus);

    if ($result) {
        echo "START : '$cronjob' \n";
    } else {
        echo "Something is wrong with lockCronjob method, check it";
    }
}

function releaseCronjob($conn, $cronjob)
{

    $updateCronStatus = "UPDATE`oc_setting` SET `value` = 0 WHERE `key` LIKE '$cronjob' LIMIT 1";
    $result = $conn->query($updateCronStatus);

    if ($result) {
        echo "STOP : '$cronjob'";
    } else {
        echo "Something is wrong with Release Cronjob method, check it";
    }
}


function executecURL($parameters, $url, $shopee_API_key)
{
    $encoded = array();
    $timenow = new DateTime();
    $parameters['timestamp'] = $timenow->getTimestamp();
    $data_string = json_encode($parameters);
    //echo $data_string;
    $concatenated = $url . "|" . $data_string;
    $api_key = $shopee_API_key;
    $signature = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, false));
    $ch = curl_init();
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: ' . $signature;

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $data = curl_exec($ch);

    curl_close($ch);
    unset($ch);
    unset($url);
    return $data;
}



