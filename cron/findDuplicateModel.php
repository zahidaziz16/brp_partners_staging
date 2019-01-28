<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);


/*
 * How to run this :
 * copy this to your terminal / putty command line
 * for local : 
 * php /home/polaris/work/atoz_brp_partner/cron/findDuplicateModel.php
 *
 * for production :
 * php /var/www/html/partner1/cron/findDuplicateModel.php
 * 
 * you will find a csv file created by this script name after the partner
 * example WONDER WORLD, then it will create  wonderworld_books_sdn_bhd.csv
 */


require_once dirname(__DIR__) . "/config.php";

$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
	die("Connection Failed : " . $conn->connect_error);
}

$fileName  	= strtolower(getCompanyName($conn));
$fileName	= str_replace(" ", "_", $fileName);
$fileName	.= ".csv";
$modelList = getModelList($conn);

if($modelList != false ){


	$createFile = fopen($fileName, 'w');
	foreach ($modelList as $model) {

				
				
				// skipping null value
				if($model['model'] == '' || $model['model'] == null) {
					continue;
				}
				echo "product_id = ". $model['model'] ." - model = ". $model['total_count'] . " \n";
				$new_array = array(
					'model' => html_entity_decode($model['model']),
					'total' => $model['total_count']
				);
				fputcsv($createFile, $new_array);
	}

	fclose($createFile);
	echo "\n\n ********* $fileName is created ! at cron folder **********";


} else {

	echo "No Model Selected";

}

exit("\n finish ...");

function getModelList($conn){

	$selectModelQuery = "SELECT oc_product.model, COUNT(oc_product.model) as totcount FROM oc_product GROUP BY oc_product.model HAVING totcount > 1 AND totcount ORDER BY `totcount` DESC";

	$modelQueryResult = $conn->query($selectModelQuery);

	if($modelQueryResult->num_rows > 0){
		
		while($modelQueryRow = $modelQueryResult->fetch_assoc()){ 
		
			$modelListArray[] = array( 
				'total_count' 	=>	$modelQueryRow['totcount'],
				'model'			=>	$modelQueryRow['model']
			);
		}

		return $modelListArray;

	} else {

		return false;

	}
}


function getCompanyName($conn) {
	$sql    = "SELECT * FROM `oc_setting` WHERE `key` LIKE 'config_name' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row            = $result->fetch_assoc();
        $companyName = $row['value'];

        return $companyName;

    } else {

    	return 'default';
    }

}




?>