<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

require_once(dirname(__DIR__) . "/config.php");
//include("product_quantity_sync.php");
include "LazadaSDK/LazopSdk.php";

$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$lazada_enable = '0';
$lazada_price_markup_percentage = '0';
$lazada_price_markup_flat = '0';
$lazada_price_threshold = '0';
$sql_price_threshold = '1';

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
# to manually disable sync, overrride this to other value, like 1 for only sycn price & quantity or 0 for disable
$updateEnable = getProductUpdateStatus($conn);


if($updateEnable == false){
    exit("cronjob is disabled");
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

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_openapi_access_token' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lazada_access_token = $row['value'];
} else {
    $lazada_access_token = '';
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_price_markup_percentage' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lazada_price_markup_percentage = $row['value'];
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_price_markup_flat' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lazada_price_markup_flat = $row['value'];
}

$sql = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_price_threshold' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lazada_price_threshold = $row['value'];
}


 $requiredApi = array(
                'appKey'                => $appkey,
                'appSecret'             => $appSecret,
                'lazada_access_token'   => $lazada_access_token
            );

 # Global Mark to skip add new product, eq ERROR LIMIT 500 for new user
 $add_new_product = true;


if((int)$lazada_price_threshold > 0){
    $sql_price_threshold = round(($lazada_price_threshold * 100 / (100+$lazada_price_markup_percentage)) - $lazada_price_markup_flat, 2);
}

//echo "Markup percentage: " . $lazada_price_markup_percentage . "<br> Markup flat: " . $lazada_price_markup_flat; exit;
date_default_timezone_set("Asia/Kuala_Lumpur");

$rowData = array();
$sql = "SELECT a.category_id, a.lazada_id, b.parent_id FROM `oc_category_to_lazada` a LEFT JOIN `oc_category` b ON (a.category_id = b.category_id)";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $parent = 'Yes';
        if ($row['parent_id'] != 0){
            $parent = 'No';
        }

        $rowData[] = array(
            1 => $row['category_id'],
            4 => $row['lazada_id'],
            5 => $parent
        );
    }
} else {
    echo "No Result";
}

{ // html array table
    $HTML401NamedToNumeric = array('&iexcl;' , '&cent;' , '&pound;' , '&curren;' , '&yen;' , '&brvbar;' , '&sect;' , '&uml;' , '&copy;' , '&ordf;' , '&laquo;' , '&not;' , '&shy;' , '&reg;' , '&macr;' , '&deg;' , '&plusmn;' , '&sup2;' , '&sup3;' , '&acute;' , '&micro;' , '&para;' , '&middot;' , '&cedil;' , '&sup1;' , '&ordm;' , '&raquo;' , '&frac14;' , '&frac12;' , '&frac34;' , '&iquest;' , '&Agrave;' , '&Aacute;' , '&Acirc;' , '&Atilde;' , '&Auml;' , '&Aring;' , '&AElig;' , '&Ccedil;' , '&Egrave;' , '&Eacute;' , '&Ecirc;' , '&Euml;' , '&Igrave;' , '&Iacute;' , '&Icirc;' , '&Iuml;' , '&ETH;' , '&Ntilde;' , '&Ograve;' , '&Oacute;' , '&Ocirc;' , '&Otilde;' , '&Ouml;' , '&times;' , '&Oslash;' , '&Ugrave;' , '&Uacute;' , '&Ucirc;' , '&Uuml;' , '&Yacute;' , '&THORN;' , '&szlig;' , '&agrave;' , '&aacute;' , '&acirc;' , '&atilde;' , '&auml;' , '&aring;' , '&aelig;' , '&ccedil;' , '&egrave;' , '&eacute;' , '&ecirc;' , '&euml;' , '&igrave;' , '&iacute;' , '&icirc;' , '&iuml;' , '&eth;' , '&ntilde;' , '&ograve;' , '&oacute;' , '&ocirc;' , '&otilde;' , '&ouml;' , '&divide;' , '&oslash;' , '&ugrave;' , '&uacute;' , '&ucirc;' , '&uuml;' , '&yacute;' , '&thorn;' , '&yuml;' , '&fnof;' , '&Alpha;' , '&Beta;' , '&Gamma;' , '&Delta;' , '&Epsilon;' , '&Zeta;' , '&Eta;' , '&Theta;' , '&Iota;' , '&Kappa;' , '&Lambda;' , '&Mu;' , '&Nu;' , '&Xi;' , '&Omicron;' , '&Pi;' , '&Rho;' , '&Sigma;' , '&Tau;' , '&Upsilon;' , '&Phi;' , '&Chi;' , '&Psi;' , '&Omega;' , '&alpha;' , '&beta;' , '&gamma;' , '&delta;' , '&epsilon;' , '&zeta;' , '&eta;' , '&theta;' , '&iota;' , '&kappa;' , '&lambda;' , '&mu;' , '&nu;' , '&xi;' , '&omicron;' , '&pi;' , '&rho;' , '&sigmaf;' , '&sigma;' , '&tau;' , '&upsilon;' , '&phi;' , '&chi;' , '&psi;' , '&omega;' , '&thetasym;' , '&upsih;' , '&piv;' , '&bull;' , '&hellip;' , '&prime;' , '&Prime;' , '&oline;' , '&frasl;' , '&weierp;' , '&image;' , '&real;' , '&trade;' , '&alefsym;' , '&larr;' , '&uarr;' , '&rarr;' , '&darr;' , '&harr;' , '&crarr;' , '&lArr;' , '&uArr;' , '&rArr;' , '&dArr;' , '&hArr;' , '&forall;' , '&part;' , '&exist;' , '&empty;' , '&nabla;' , '&isin;' , '&notin;' , '&ni;' , '&prod;' , '&sum;' , '&minus;' , '&lowast;' , '&radic;' , '&prop;' , '&infin;' , '&ang;' , '&and;' , '&or;' , '&cap;' , '&cup;' , '&int;' , '&there4;' , '&sim;' , '&cong;' , '&asymp;' , '&ne;' , '&equiv;' , '&le;' , '&ge;' , '&sub;' , '&sup;' , '&nsub;' , '&sube;' , '&supe;' , '&oplus;' , '&otimes;' , '&perp;' , '&sdot;' , '&lceil;' , '&rceil;' , '&lfloor;' , '&rfloor;' , '&lang;' , '&rang;' , '&loz;' , '&spades;' , '&clubs;' , '&hearts;' , '&diams;' , '&quot;' , '&OElig;' , '&oelig;' , '&Scaron;' , '&scaron;' , '&Yuml;' , '&circ;' , '&tilde;' , '&ensp;' , '&emsp;' , '&thinsp;' , '&zwnj;' , '&zwj;' , '&lrm;' , '&rlm;' , '&ndash;' , '&mdash;' , '&lsquo;' , '&rsquo;' , '&sbquo;' , '&ldquo;' , '&rdquo;' , '&bdquo;' , '&dagger;' , '&Dagger;' , '&permil;' , '&lsaquo;' , '&rsaquo;' , '&euro;');
}

$today = date("Y-m-d");
$n = 0; $m = 0; $disableNo = 0;
echo "Start : " . date('Y-m-d H:i:s') . " \n ";

$sql = "SELECT a.product_id, 
       a.model, 
       a.quantity, 
       a.image, 
       a.price, 
       b.image2, 
       c.special_price, 
       c.date_start, 
       c.date_end, 
       d.description, 
       d.meta_description, 
       f.categories,
       a.status, 
       Coalesce(g.quantity_wms, 0) AS quantity_wms, 
       Coalesce(g.quantity_erp, 0) AS quantity_erp 
        FROM   oc_product a 
               LEFT JOIN oc_product_quantity_external g 
                      ON ( a.model = g.model ) 
               LEFT JOIN (SELECT product_id, 
                                 price AS special_price, 
                                 date_start, 
                                 date_end 
                          FROM   oc_product_special 
                          ORDER  BY product_special_id DESC) c 
                      ON ( c.product_id = a.product_id ) 
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

        WHERE  
               a.price >= 1
               AND a.sync_lazada = 1
               AND a.price >= $sql_price_threshold
        GROUP  BY a.product_id 
        ORDER  BY a.product_id ASC;" ;
$result = mysqli_query($conn, $sql);
$skuList = array();

while($row = mysqli_fetch_assoc($result)){
    $status = $row['status'];
    $xml = '<?xml version="1.0" encoding="UTF-8" ?><Request>';
    $row = preg_replace("/\s+/", " ", array_map('trim',$row));
    $productId = $row['product_id'];
    $model = str_replace('  ', ' ', $row['model']);
    //$price = round($row['price']*1.06, 2);
    $price = round(($row['price'] * ( 1 + $lazada_price_markup_percentage/100) + $lazada_price_markup_flat), 2);
    //$spPrice = round($row['special_price']*1.06, 2);
    $spPrice = round(($row['special_price'] * ( 1 + $lazada_price_markup_percentage/100) + $lazada_price_markup_flat), 2);
    $spDateFrom = $row['date_start'];
    $spDateTo = $row['date_end'];
    $desc = $row['description'];
    $metaDesc = $row['meta_description'];
    $categories = $row['categories'];
    $qty = (int)$row['quantity'];
    $qty_wms = (int)$row['quantity_wms'];
    $qty_erp = (int)$row['quantity_erp'];
    if($qty < 1){
        $qty = 0;
    }
    if($qty_erp < 2){
        $qty_erp = 0;
    } else {
        $qty_erp = round($qty_erp/2); //Take 50%
    }
    $qty = $qty + $qty_wms + $qty_erp;

    # TEMP for updating description
    /*
    $cleanRegex = "/(™|®|©|&trade;|&reg;|&copy;|&#8482;|&#174;|&#169;)/";
    //$name = preg_replace('/[[:^print:]]/',  '', $name);
    $shortDesc = preg_replace($cleanRegex, "", htmlentities($row['meta_description'], ENT_NOQUOTES, 'ISO-8859-1'));
    $shortDesc = strtr($shortDesc, $HTML401NamedToNumeric);
    $shortDesc =  preg_replace('/[[:^print:]]/',  '', $shortDesc);

    $desc = preg_replace($cleanRegex, "", htmlentities($row['description'], ENT_NOQUOTES, 'ISO-8859-1'));
    //$desc = strtr($desc, $HTML401NamedToNumeric);
    $desc = htmlspecialchars_decode($desc, ENT_NOQUOTES);
    $desc = str_replace($HTML401NamedToNumeric, "", $desc);
    $desc = preg_replace('/[[:^print:]]/',  '', $desc);

    if(strlen($desc) > 20000){
    $desc = $shortDesc;
    }

    if(empty($shortDesc)) $shortDesc = $name;
    if(empty($desc)) $desc = $shortDesc;
    */
    # TEMP END

    $image = $row['image'];
    $groupImages = $row['image2'];
    $strImages = array();
    if(!empty($image) && empty($groupImages)){
        $groupImages = $image;
    }
    if(!empty($groupImages) || $groupImages != NULL){
        $images = explode("|", $groupImages);
    }else{
        $images = array();
    }

    $price = number_format($price, 2, '.', '');
    if(empty($desc)) $desc = $metaDesc;
    $desc = str_replace('&quot;', '"', $desc);

    //query may return empty model, skip it
    if(empty($model) || $model == ''){
        continue;
    }

    $product_exist = is_product_exist($conn, $model);

    
    // Add New Product if it doesn't exist at Lazada
    // status == 1 -> product is enabled at opencart's oc_product 
    if($product_exist == false && $updateEnable == 3 && $status == 1 && $qty > 0){ //continue;
        //if(date('H') >= 12 && date('H') <= 14) continue;
        
        # Skip if error limit 500 sku occur for new partner
        if($add_new_product == false){
            echo "\n New item: ".$model. " - LIMIT 500 for new partner for 90 days\n";
            continue;
        }
        
        echo "\n New item: ".$model."\n";
        addProduct($conn, $rowData, $productId, $model, $qty, $price, $spPrice, $spDateFrom, $spDateTo, $images, $appkey, $appSecret, $lazada_access_token, $HTML401NamedToNumeric);
        $m++;
        continue;
    }
    // product exist and it is enabled, do update
    if($product_exist == 1 && $status == 1) {
        if($categories != '' && $categories != NULL){
           # for updating description
           $catArr = explode("|", $categories); //array of categories that match this product
            $catId = "";
            foreach($rowData AS $value){ 

            # find if product under a parent category id
                if (in_array($value[1], $catArr) && $value[5] == "Yes"){
                    $catId = $value[4];
                    break;
                }

            //find a child category id
                if(in_array($value[1], $catArr) && $value[5] == "No"){
                    $catId = $value[4];
                    break;
                }
            }
    
        }

        echo "\nUpdate item: ".$model."\n";

        
        // Update Price & Quantity
        if($updateEnable == 1 || $updateEnable == 3) {
            $qty_price_status = update_quantity_price( $model, $requiredApi, $qty, $price);
        }

        // Update Stock Only
        if($updateEnable == 2) {

           $qty_price_status = update_quantity_price( $model, $requiredApi, $qty);

        }


        # for update description, temporary
        /*if($updateEnable == 3){
            
            $update_params = array(
                'model' => $model,
                'short_description' => $shortDesc,
                'description'=> $desc
            );

            updateLazadaProduct($update_params);
        }*/

        $qty_price_output = $qty_price_status ? "update quantity price succesfull\n" : "update quantity price fail\n";
        echo $qty_price_output;

        if($qty_price_status) {
            $n++;
        } 
            
    }


} //end while row
echo "\n\n Products updated: ".$n.". Products created: ".$m."\n";
if($disableNo > 0) {
    echo "\n\n Products Disable : $disableNo \n";
}
echo date('Y-m-d H:i:s');


// ===============================================================================
// disable below price_threshold or status is no longer synced 
// ===============================================================================


$disable_total = 0;
$disable_counter = 0;
$notExist_counter = 0;

$disable_product_sql = "
SELECT oc_product.product_id, oc_product.model, lazada_product.item_id, oc_product.status 
FROM oc_lazada_products AS lazada_product
LEFT JOIN oc_product ON (lazada_product.product_id = oc_product.product_id)
WHERE (oc_product.status = 0 OR oc_product.sync_lazada = 0) AND oc_product.price >= 1 
    OR 
      ( oc_product.status = 1 AND oc_product.sync_lazada = 1 AND oc_product.price <= $sql_price_threshold AND oc_product.price >= 1)
GROUP BY oc_product.product_id";

$result = mysqli_query($conn, $disable_product_sql);

while($row = mysqli_fetch_row($result)){
    $row = preg_replace("/\s+/", " ", array_map('trim',$row));
    $productId = $row[0]; $model = str_replace('  ', ' ', $row[1]); $status = $row[2];

    if($model == '' || empty($model)){
        continue;
    }

    $product_exist = is_product_exist($conn, $model);
    if($product_exist){

        $disable_status = update_quantity_price( $model, $requiredApi, 0);
        $disable_output = $disable_status ? "$model is disabled\n" : "$model failed to be disabled\n";
        echo $disable_output;

        $disable_counter++;
    } 
    $disable_total++;
}

echo "\n Total products to be disabled: " . $disable_total . " Total products disabled in Lazada: " . $disable_counter;


//Insert datetime of cronjob
date_default_timezone_set("Asia/Kuala_Lumpur");
$dateNow = date('Y-m-d H:i:s');

$sql = "UPDATE `oc_setting` SET `value` = '$dateNow' WHERE `oc_setting`.`key` = 'lazada_last_cronjob_date_product';";
$conn->query($sql) or die(mysqli_error($conn));

echo "\n\nProducts updated: ".$n.". Products created: ".$m."\n";




/*
 * disable product base on model from OpenCart
 */
function disableProduct( $model, $apiData){

    $xml = '<?xml version="1.0" encoding="UTF-8" ?>
            <Request>';
    $xml .= '<Product>';
    $xml .= '<Skus>
            <Sku>
            <SellerSku>'.$model.'</SellerSku>
            <active>false</active>
            </Sku>
            </Skus>
            </Product>
            </Request>';


    $disableStatus = updateLazadaProduct($xml, $apiData['appKey'], $apiData['appSecret'], $apiData['lazada_access_token']);

    if($disableStatus) {
        return true;
    }else{
        return false;
    }

}



function update_quantity_price( $model, $apiData, $quantity = null, $price = null){

    if(empty($apiData)){
        return false;
    }

    $xml = '<?xml version="1.0" encoding="UTF-8" ?>
            <Request>';
    $xml .= '<Product>';
    $xml .= '<Skus>
            <Sku>';
    $xml .= '<SellerSku>'.$model.'</SellerSku>';

    if($price != null) {
        $xml .= '<Price>'.$price.'</Price>';
    }

    if($quantity < 1) {
        $quantity = 0;
        $xml .= '<Quantity>'.$quantity.'</Quantity>';
    } else {
        $xml .= '<Quantity>'.$quantity.'</Quantity>';
    }

    
    $xml .= '        
            </Sku>
            </Skus>
            </Product>
            </Request>';


    $url = 'https://auth.lazada.com/rest';
    $client = new LazopClient($url,$apiData['appKey'],$apiData['appSecret']);
    $request = new LazopRequest('/product/price_quantity/update');
    $request->addApiParam('payload', $xml);
    $response = $client->execute($request, $apiData['lazada_access_token']);
    $result = $response;
    $response = json_decode($response, true);

    if(isset($response['code']) && $response['code'] == "0"){
        echo "Update item success.\n";
        return true;
    }else{
        echo "Update quantity price failed. Response: " . $result . "\n\n";
        return false;
    }


    if($updateStatus) {
        return true;
    }else{
        return false;
    }
}



function createLazadaProduct($xml, $appkey, $appSecret, $lazada_access_token, $model){
    $url = 'https://auth.lazada.com/rest';
    $client = new LazopClient($url,$appkey,$appSecret);
    $request = new LazopRequest('/product/create');
    $request->addApiParam('payload', $xml);
    $response = $client->execute($request, $lazada_access_token);
    $result = $response;
    $response = json_decode($response, true);

    $oc_product_id = get_oc_product_id($GLOBALS['conn'], $model);

    if(isset($response['code']) && $response['code'] == "0"){
        echo "Create item success. Response: " . $response['request_id'] . "\n";

        # Initiate Variables for insert into db for updating purpose on next call.
        $lazada_item_id = $response['data']['item_id'];
        $lazada_model = $response['data']['sku_list']['0']['seller_sku'];
       
       
        $insert_params = array(
            'product_id'    => $oc_product_id,
            'item_id'       => $lazada_item_id,
            'model'         => $lazada_model
        );
   
        insert_product_to_db($GLOBALS['conn'], $insert_params);
    

    }else{
        echo "Create item failed. Response: \n";
        echo "\n";
        # getting error message from JSON
        if( !empty($response['detail']['0']['message']) ){
            $error_message = $response['detail']['0']['message'];
            echo $error_message . "\n";

            if($error_message == "Seller sku is exist"){

                $insert_params = array(
                    'product_id'    => $oc_product_id,
                    'item_id'       => 0,
                    'model'         => $model
                );
           
                insert_product_to_db($GLOBALS['conn'], $insert_params);
                return ;

            }

            # update lazada sync status if the product rejected by lazada, out of our control
            echo update_fail_sync_status($GLOBALS['conn'], $error_message, $model);
            # if error message like this, skip adding new product.
            if($error_message == "NEW_SELLER_PUBLISH_LIMIT") {
                $GLOBALS['add_new_product'] =  false; 
            }

        } else {
            print_r($response);
            
        }
        
    }

}

function migrateImageToLazada($xml, $appkey, $appSecret, $lazada_access_token){
    $url = 'https://auth.lazada.com/rest';
    $client = new LazopClient($url,$appkey,$appSecret);
    $request = new LazopRequest('/image/migrate');
    $request->addApiParam('payload', $xml);
    $response = $client->execute($request, $lazada_access_token);
    $response = json_decode($response, true);

    //echo $response['data']['image']['url']; echo "\n\n"; echo "\n\n";
    if(isset($response['data']['image']['url'])){
        $urlRes = $response['data']['image']['url'];
    }else{
        print_r($response);
        $urlRes = '';
    }

    return $urlRes;
}

/*
 * update lazada attribute base on payload
 * list can be updated :
 * - description
 * - short_description
 * - product status like disable and enable
 */
function updateLazadaProduct($payload = array()){

    $appkey = $GLOBALS['appkey'];
    $appSecret = $GLOBALS['appSecret'];
    $lazada_access_token = $GLOBALS['lazada_access_token'];


     $xml = '<?xml version="1.0" encoding="UTF-8" ?>
            <Request>
                <Product>
                    <Attributes>
                        <description><![CDATA['.$payload['description'].']]></description>
                        <short_description>'.$payload['short_description'].'</short_description>
                    </Attributes>
                    <Skus>
                        <Sku>
                        <SellerSku>'.$payload['model'].'</SellerSku>
                        <active>true</active>
                        </Sku>
                    </Skus>
                </Product>
            </Request>';
    echo "\n".$xml."\n";
    $url = 'https://auth.lazada.com/rest';
    $client = new LazopClient($url,$appkey,$appSecret);
    $request = new LazopRequest('/product/update');
    $request->addApiParam('payload', $xml);
    $response = $client->execute($request, $lazada_access_token);
    $result = $response;
    $response = json_decode($response, true);

    if(isset($response['code']) && $response['code'] == "0"){
        echo "Update item detail success. \n";
        return true;
    }else{
        echo "Update item detail failed. Response: " . $result . "\n\n";
        return false;
    }

}

function searchProductExists($model, $appkey, $appSecret, $lazada_access_token){
    $url = 'https://auth.lazada.com/rest';
    $client = new LazopClient($url,$appkey,$appSecret);
    $request = new LazopRequest('/products/get','GET');
    $request->addApiParam('filter','all');
    $request->addApiParam('sku_seller_list',' ["'.$model.'"]');
    $response = $client->execute($request, $lazada_access_token);
    $response = json_decode($response, true);
    if(isset($response['data']['products'])){
        $response = $response['data']['products'];
    }
    else{
        $response = array();
    }

    if(empty($response)){
        return false;
    } else{
        return true;
    }
}


# find model exist or not inside oc_lazada_products
function is_product_exist($conn, $model){

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

function getCategoryAttributes($catId, $appkey, $appSecret, $lazada_access_token){
    $url = 'https://auth.lazada.com/rest';
    $client = new LazopClient($url,$appkey,$appSecret);
    $request = new LazopRequest('/category/attributes/get','GET');
    $request->addApiParam('primary_category_id',$catId);
    $response = $client->execute($request, $lazada_access_token);
    $response = json_decode($response, true);
    if(isset($response['data'])){
        $response = $response['data'];
    }
    else{
        $response = array();
    }

    return $response;
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

    $selectUpdateEnable = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'lazada_product_enable' LIMIT 1";
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


function getExistingBrandName($conn){
    $index = 0;
    $selectLazadaBrand = "SELECT brand_id, brand_name FROM lazada_brand;";
    $selectLazadaResult = mysqli_query($conn, $selectLazadaBrand);  
    while($row = mysqli_fetch_assoc($selectLazadaResult)){
        $brand[$index] = $row['brand_id'];
        $brand[$index] = $row['brand_name'];
        $index++;
        
    }

    return $brand;
}
/*
 Matching brand between lazada and opencart
 */
function matchBrandLazada( $conn, $brandName ){

    $selectLazadaBrand =$conn->prepare("SELECT brand_id, brand_name FROM lazada_brand WHERE brand_name = ? limit 1;");
    $selectLazadaBrand->bind_param("s", $brandName );
    $selectLazadaBrand->execute();
    $selectLazadaBrand->bind_result($brand_id, $brand_name);

    if($selectLazadaBrand->fetch()){
        return $brand_id;
    }else {
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

# populate oc_lazada_products for updating product in next api call.
function insert_product_to_db($conn, $params){
    
    # Initiate Variables for insert
    $product_id = $params['product_id'];
    $item_id    = $params['item_id'];
    $model      = $params['model'];

    $insert_lazada_product = $conn->prepare("INSERT INTO oc_lazada_products (product_id, item_id, model) VALUES (?, ?, ?);");
    $insert_lazada_product->bind_param("iis", $product_id, $item_id, $model );
    $insert_lazada_result = $insert_lazada_product->execute();

    if ($insert_lazada_result != false) {
        return true;
    } else {
        return false;
    }

}


/*
* Lazada Error Code  | Error Description                            | oc_product code 
* E128268           -> Brand Policy Restriction                     -> 2
* E129782           -> Contain contact number in Description        -> 3
* E53092            -> Contain contact number in description        -> 3
* E128560           -> Contain contact number in description        -> 3
* E53123            -> possible counterfeit content                 -> 4
* E129909           -> Item that resemble weapons                   -> 5
* E53090            -> item is prohibited to be sold on Lazada      -> 6
* E128688           -> Illicit drug                                 -> 7
* E54254            -> rejected category due to cultural sensitive  -> 8
* fix/delete        -> Broken image in description                  -> 9
* brand             -> brand is not authorized                      -> 10
* Category          -> Category is not leaf                         -> 11
* E53401			-> possible counterfeit content 				-> 12
*/
function update_fail_sync_status($conn, $error_message = false, $model){
    
    $error_code_list = array(
        'ategory',
        'brand',
        'fix/delete',
        'E128268',
        'E129782',
        'E53092',
        'E53123',
        'E129909',
        'E53090',
        'E128688',
        'E54254',
        'E53401',
        'E128560'
    );

    foreach($error_code_list as $error_code){
        
        $error_finding = strpos($error_message, $error_code);
        
        if ($error_finding != false) {
            # update status lazada to whatever code
            switch ($error_code) {
                case 'E128268':
                    $update_sync =  update_sync_status($conn, 2, $model);
                    break;
                
                case 'E129782':
                    $update_sync =  update_sync_status($conn, 3, $model);
                    break;

                case 'E53092':
                    $update_sync =  update_sync_status($conn, 3, $model);
                    break;

                case 'E128560':
                    $update_sync =  update_sync_status($conn, 3, $model);
                    break;

                case 'E53123':
                    $update_sync =  update_sync_status($conn, 4, $model);
                    break;

                case 'E129909':
                    $update_sync =  update_sync_status($conn, 5, $model);
                    break;

                case 'E53090':
                    $update_sync =  update_sync_status($conn, 6, $model);
                    break;

                case 'E128688':
                    $update_sync =  update_sync_status($conn, 7, $model);
                    break;

                case 'E54254':
                    $update_sync =  update_sync_status($conn, 8, $model);
                    break;

                case 'fix/delete':
                    $update_sync =  update_sync_status($conn, 9, $model);
                    break;

                case 'brand':
                    $update_sync =  update_sync_status($conn, 10, $model);
                    break; 

                case 'ategory':
                    $update_sync =  update_sync_status($conn, 11, $model);
                    break; 

                case 'E53401':
                    $update_sync =  update_sync_status($conn, 12, $model);
                    break;            

                default:
                    break;
            }
            return $update_sync. "\n";
        }
    }   
}


function update_sync_status($conn, $sync_code, $model){

    $update_sycn_status = $conn->prepare("UPDATE oc_product SET sync_lazada = '$sync_code' WHERE model = ? ;");
    $update_sycn_status->bind_param("s", $model );
    $update_result = $update_sycn_status->execute();

    if ($update_result != false) {
        return "$model -- sync_lazada updated to code $sync_code\n";
    } else {
        return "$model -- sync_lazada failed to be updated\n";
    }
}


function addProduct($conn, $rowData, $productId, $model, $qty, $price, $spPrice, $spDateFrom, $spDateTo, $images, $appkey, $appSecret, $lazada_access_token, $HTML401NamedToNumeric){

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

    if($row = mysqli_fetch_assoc($result)){
        $xml = '<?xml version="1.0" encoding="UTF-8" ?><Request>';
        $image = $row['image'];
        $row = preg_replace("/\s+/", " ", array_map('trim',$row));

        $strImages = array();
        $pWeight = (float)$row['package_weight'];
        $pLength = (float)$row['package_length'];
        $pWidth = (float)$row['package_width'];
        $pHeight = (float)$row['package_height'];
        $brand = $row['brand'];
        $categories = $row['categories'];
        $storage = $row['storage'];
        $display = $row['display'];
        $packageContent = trim($row['package_content']);
        $packageContent =  preg_replace('/[[:^print:]]/',  '', $packageContent);
        $name = preg_replace($cleanRegex, "", htmlentities($row['name'], ENT_NOQUOTES, 'ISO-8859-1'));
        //$name = strtr($name, $HTML401NamedToNumeric);
        $name = str_replace($HTML401NamedToNumeric, "", $name);
        $name = preg_replace('/[[:^print:]]/',  '', $name);
        
        $shortDesc = preg_replace($cleanRegex, "", htmlentities($row['description'], ENT_NOQUOTES, 'ISO-8859-1'));
        //$shortDesc = strtr($shortDesc, $HTML401NamedToNumeric);
        $shortDesc = str_replace($HTML401NamedToNumeric, "", $shortDesc);
        $desc = preg_replace($cleanRegex, "", htmlentities($row['description2'], ENT_NOQUOTES, 'ISO-8859-1'));
        //$desc = strtr($desc, $HTML401NamedToNumeric);
        $desc = str_replace($HTML401NamedToNumeric, "", $desc);

        // Escaping special character from description, ENT_NOQUOTES -> ignoring conversion for "" and ''
        $desc = htmlspecialchars_decode($desc, ENT_NOQUOTES);   
        $desc = preg_replace('/[[:^print:]]/',  '', $desc);

        // Escaping special character from short_description
        $shortDesc =  preg_replace('/[[:^print:]]/',  '', $shortDesc);
        
        if(strlen($desc) > 20000){
            $desc = $shortDesc;
        }
        //echo $desc;

        if(empty($shortDesc)) $shortDesc = $name;
        if(empty($desc)) $desc = $shortDesc;
        $desc = str_replace('&quot;', '"', $desc);
     
        //match brand from opencart to lazada's. return result or false
        if($brand != '' || empty($brand) ) {
            $brand = matchBrandLazada($conn, $brand);
        }else {
            $brand = "No Brand";
        }

        if($brand == false) {
            $brand = "No Brand";
        }

        if($categories != '' && $categories != NULL){
            $catArr = explode("|", $categories); //array of categories that match this product
            $catId = "";
            foreach($rowData AS $value){ 

            	# find if product under a parent category id
                if (in_array($value[1], $catArr) && $value[5] == "Yes"){
                    $catId = $value[4];
                    break;
                }
            	
            	//find a child category id
                if(in_array($value[1], $catArr) && $value[5] == "No"){
                    $catId = $value[4];
                    break;
                }
            }
            if($catId != ""){ //match found
                if(empty($images) && !empty($image)){
                    array_push($images, $image);
                }
                foreach($images AS $img){ //migrate images before uploading

                    if(strpos($img, "brp.com.my")===false){
                        $imageURL = HTTP_SERVER.'image/'.$img;
                    } else {
                        $imageURL = $img;
                    }
                    //Properly encode img url
                    $encoded_url = rawurlencode($imageURL);
                    $imageURL = str_replace("%2F", "/", $encoded_url);
                    $imageURL = str_replace("%3A", ":", $imageURL);
                    $imgXML = '<?xml version="1.0" encoding="UTF-8" ?>
                                <Request>
                                    <Image>
                                        <Url>'.$imageURL.'</Url>
                                    </Image>
                                </Request>';
                    //echo $img."\n";
                    $strImg = migrateImageToLazada($imgXML, $appkey, $appSecret, $lazada_access_token);
                    if(!empty($strImg)){
                        array_push($strImages, $strImg);
                    }
                }
                if(empty($strImages)){
                    echo "No image for product: " . $model . "\n";
                    return;
                }

                //Get dynamic required category attribute from Lazada
                //List of required normal attributes to be ignored
                $arrExcludedNormalAttributes = array('name', 'short_description', 'brand', 'model', 'name_ms', 'warranty_type', 'warranty');
                //List of required SKU attributes to be ignored
                $arrExcludedSKUAttributes = array('color_family', 'SellerSku', 'price', 'package_content', 'package_weight', 'package_length', 'package_width','package_height', 'tax_class');
                $attributesList = getCategoryAttributes($catId, $appkey, $appSecret, $lazada_access_token);
                $arrNormalAttr = array();
                $arrSKUAttr = array();
                foreach ($attributesList as $attribute){
                    $resultText = "";
                    if ($attribute['is_mandatory'] === 1 && $attribute['attribute_type'] == 'normal'){
                        if (in_array($attribute['name'], $arrExcludedNormalAttributes)){
                            continue;
                        }
                        if (empty($attribute['options'])){
                            if($attribute['input_type'] == 'numeric'){
                                $resultText = "5";
                            } else {
                                $resultText = "Not Specified";
                            }
                        } else {
                            foreach ($attribute['options'] as $option){
                                if ($option['name'] == "Universal"){
                                    $resultText = "Universal";
                                    break;
                                } else if ($option['name'] == "Not Specified"){
                                    $resultText = "Not Specified";
                                    break;
                                }
                            }
                            if (empty($resultText)){
                                $resultText = $attribute['options'][0]['name'];
                            }
                        }
                        $stringXML = "<".$attribute['name'].">".$resultText."</".$attribute['name'].">";
                        $arrNormalAttr[] = $stringXML;
                    } else if ($attribute['is_mandatory'] === 1 && $attribute['attribute_type'] == 'sku'){
                        if (in_array($attribute['name'], $arrExcludedSKUAttributes)){
                            continue;
                        }
                        if (empty($attribute['options'])){
                            if($attribute['input_type'] == 'numeric'){
                                $resultText = "5";
                            } else {
                                $resultText = "Not Specified";
                            }
                        } else {
                            foreach ($attribute['options'] as $option){
                                if ($option['name'] == "Universal"){
                                    $resultText = "Universal";
                                    break;
                                } else if ($option['name'] == "Not Specified"){
                                    $resultText = "Not Specified";
                                    break;
                                }
                            }
                            if (empty($resultText)){
                                $resultText = $attribute['options'][0]['name'];
                            }
                        }
                        $stringXML = "<".$attribute['name'].">".$resultText."</".$attribute['name'].">";
                        $arrSKUAttr[] = $stringXML;

                    }

                }
                //end category attribute
                $xml .= '<Product>
                        <PrimaryCategory>'.$catId.'</PrimaryCategory>
                        <Attributes>
                            <name>'.$name.'</name>
                            <name_ms>'.$name.'</name_ms>
                            <short_description>'.$shortDesc.'</short_description>
                            <description><![CDATA['.$desc.']]></description>
                            <brand>'.$brand.'</brand>
                            <model>'.$model.'</model>';

                $xml .= '   <warranty_type>Local Manufacturer Warranty</warranty_type>
                            <warranty>1 Year</warranty>';

                foreach($arrNormalAttr as $normalAttributeXML){
                    //Insert all mandatory attribute - formatted this way for easier reading
                    $xml .= '
                            '.$normalAttributeXML;
                }

                if($pWeight <= 0.01) $pWeight = 1; if($pLength <=  0.01) $pLength = 5; if($pWidth <=  0.01) $pWidth = 5; if($pHeight <=  0.01) $pHeight = 5;
                if($packageContent == NULL || $packageContent == '-') $packageContent = "1 x ".$name;
                $today = date("Y-m-d");
                $xml .= '   </Attributes>
                            <Skus>
                                <Sku>
                                    <SellerSku>'.$model.'</SellerSku>
                                    <color_family>Not Specified</color_family>
                                    <quantity>'.$qty.'</quantity>
                                    <price>'.$price.'</price>
                                    <package_weight>'.$pWeight.'</package_weight>
                                    <package_length>'.$pLength.'</package_length>
                                    <package_width>'.$pWidth.'</package_width>
                                    <package_height>'.$pHeight.'</package_height>
                                    <package_content>'.$packageContent.'</package_content>
                                    <tax_class>default</tax_class>';
                if($spDateTo != '0000-00-00' && $spDateFrom != '0000-00-00' && $spDateTo != NULL && $spDateFrom != NULL){ //special promotion
                    if(strtotime($spDateTo) >= strtotime($today) && $spPrice > 0){ //ongoing
                        $xml .= '   <special_price>'.$spPrice.'</special_price>
                                    <special_from_date>'.date('Y-m-d', strtotime($spDateFrom)).'</special_from_date>
                                    <special_to_date>'.date('Y-m-d', strtotime($spDateTo)).'</special_to_date>';
                    }
                }
                foreach($arrSKUAttr as $skuAttributeXML){
                    //Insert all mandatory attribute - formatted this way for easier reading
                    $xml .= '
                            '.$skuAttributeXML;
                }
                $xml .= '           <Images>';
                $strImages = array_unique($strImages);

                foreach($strImages AS $img){
                    $xml .= '           <Image>'.$img.'</Image>';
                }
                $xml .= '           </Images>
                                </Sku>
                            </Skus>
                        </Product>
                    </Request>';
                
                createLazadaProduct($xml, $appkey, $appSecret, $lazada_access_token, $model);
            } else {
                echo "Category is disabled / not matched. \n";
            }
        }
    }
}
?>