<?php 
session_start();
require('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
include('../commonmethods.php');
define('DC_CREATED_STATUS', 'dc_created');
define('DS_CREATED_STATUS', 'ds_created');
define('DS_COMPLETED_STATUS','ds_completed');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}
$finaloutput = array();
if(!$_POST) {
	$action = $_GET['action'];
}
else {
	$action = $_POST['action'];
}
switch($action){
	case 'create_dc':
        $finaloutput = createDC();
    break;
    case 'get_dc_details':
    	$finaloutput = getDCDetails();
    break;
    case 'create_ds':
    	$finaloutput = createDS();
    break;
    case 'get_dc_list':
    	$finaloutput = getDCList();
    break;
    case 'update_ds':
    	$finaloutput = updateDS();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function createDC(){
	global $dbc;
	$dcDate = mysqli_real_escape_string($dbc,trim($_POST['dc_date']));
	$dcInfo = json_decode($_POST['dc_info']);
	$department = $dcInfo->department;
	$location = $dcInfo->location;
	$customerName = $dcInfo->customerName;
	$despatchRequestId = $dcInfo->despatchRequestId;
	$model = mysqli_real_escape_string($dbc,trim($_POST['dc_model']));
	$oldCode = mysqli_real_escape_string($dbc,trim($_POST['dc_ref_code']));
	$type = mysqli_real_escape_string($dbc,trim($_POST['dc_type']));
	$subType = mysqli_real_escape_string($dbc,trim($_POST['dc_sub_type']));
	$installationDate = mysqli_real_escape_string($dbc,trim($_POST['installation_date']));
	$installationTime = mysqli_real_escape_string($dbc,trim($_POST['installation_time']));
	$quantity = mysqli_real_escape_string($dbc,trim($_POST['dc_qty']));
	$rate = mysqli_real_escape_string($dbc,trim($_POST['dc_rate']));
	$amount = mysqli_real_escape_string($dbc,trim($_POST['dc_amount']));
	$dcCreatedStatus = DC_CREATED_STATUS;

	$query = "INSERT INTO delivery_challan (dc_date, despatch_request_id, customer_name, department, location, model,  old_code, dc_type, dc_sub_type, installation_date, installation_time, quantity, rate, amount, status) VALUES('$dcDate', '$despatchRequestId', '$customerName', '$department', '$location', '$model', '$oldCode', '$type', '$subType', '$installationDate', '$installationTime', '$quantity', '$rate', '$amount', '$dcCreatedStatus')";

   	//file_put_contents("despatch.log", "\n".print_r($query, true), FILE_APPEND | LOCK_EX);

	if(mysqli_query($dbc, $query)){
		$lastInsertDC = mysqli_insert_id($dbc);
		$inQuery = "UPDATE despatch_request set status='dc_created' WHERE despatch_request_id='$despatchRequestId'";
		mysqli_query($dbc, $inQuery);
		return array("infocode" => "DCCREATEDSUCCESSFULLY", "message" => "DC created succesfully.", "dc_id" => $lastInsertDC);
	} else {
		return array("infocode" => "DCCREATEDFAILURE", "message" => "DC created Failure");
	}	
}

function getDCDetails(){
	global $dbc;
	$deliveryChallanID = mysqli_real_escape_string($dbc,trim($_POST['delivery_challan_id']));
	$query = "SELECT * FROM delivery_challan WHERE delivery_challan_id='$deliveryChallanID'";
	$result = mysqli_query($dbc, $query);
	$out = array();
	if(mysqli_num_rows($result) > 0){
		$row = mysqli_fetch_assoc($result);
		return array("infocode" => "DCRETRIEVEDSUCCESSFULLY", "data" => json_encode($row));
	} else {
		return array("infocode" => "DCRETRIEVENOTSUCCESSFUL", "message" => "DC created succesfully");
	}
}

function createDS(){
	global $dbc;
	$dcList = json_decode($_POST['dc_list']);
	$vehicleNumber = mysqli_real_escape_string($dbc,trim($_POST['ds_vehicle_number']));
	$driverName = mysqli_real_escape_string($dbc,trim($_POST['ds_driver_name']));
	$startDate = mysqli_real_escape_string($dbc,trim($_POST['start_date']));
	$startTime = mysqli_real_escape_string($dbc,trim($_POST['start_time']));
	$dsStatus = DS_CREATED_STATUS;
	
	$dsQuery = "INSERT INTO despatch_schedule (vehicle_number, driver_name, status, start_date, start_time) VALUES ('$vehicleNumber', '$driverName', '$dsStatus', '$startDate', '$startTime')";
	//file_put_contents("despatch.log", "\n".print_r($dsQuery, true), FILE_APPEND | LOCK_EX);

	if(mysqli_query($dbc, $dsQuery)){
		$lastDespatchScheduleId = mysqli_insert_id($dbc);
		for($index = 0; $index < count($dcList); $index++){
			if($dcList[$index]->isDCSelected == 'true'){
				$deliveryChallanID = $dcList[$index]->delivery_challan_id;
				$despatchScheduleID = $lastDespatchScheduleId;
				$dsDetailQuery = "INSERT INTO despatch_schedule_detail (despatch_schedule_id, delivery_challan_id) VALUES ('$despatchScheduleID', '$deliveryChallanID')";
				if(mysqli_query($dbc, $dsDetailQuery)){
					$dcStatusUpdateQuery = "UPDATE delivery_challan SET status='$dsStatus' WHERE delivery_challan_id='$deliveryChallanID'";
					if(mysqli_query($dbc, $dcStatusUpdateQuery)){
						$despatchRequestID = getDespatchRequestID($deliveryChallanID);
						$despatchRequestStatusUpdateQuery = "UPDATE despatch_request SET status='$dsStatus' WHERE despatch_request_id='$despatchRequestID'";
						mysqli_query($dbc, $despatchRequestStatusUpdateQuery);
					}

				}
			}
		}
		$output = array("infocode" => "DSCREATEDSUCCESSFULLY", "message" => "DC created succesfully.", "ds_id" => $lastDespatchScheduleId); 
	} else {
		$output = array("infocode" => "DSCREATIONFAILURE", "message" => "DC not created succesfully");
	}


   	//file_put_contents("despatch.log", "\n".print_r($_POST['dc_list'], true), FILE_APPEND | LOCK_EX);
   	return $output;
}

function getDespatchRequestID($dcID){
	global $dbc;
	$query = "SELECT despatch_request_id FROM delivery_challan WHERE delivery_challan_id='$dcID'";
	$result = mysqli_query($dbc, $query);
	$despatchRequestID = '';
	if(mysqli_num_rows($result) > 0){
		$row = mysqli_fetch_assoc($result);
		$despatchRequestID = $row['despatch_request_id'];
	}
	return $despatchRequestID;
}

function getDCList(){
	global $dbc;
	$despatchScheduleID = mysqli_real_escape_string($dbc, $_POST['despatch_schedule_id']);
	$query = "SELECT dc.delivery_challan_id, dc.customer_name, dc.location, dc.model, dc.dc_type, dc.dc_sub_type FROM despatch_schedule_detail dsd, delivery_challan dc WHERE dsd.delivery_challan_id=dc.delivery_challan_id AND dsd.despatch_schedule_id='$despatchScheduleID'";
	$result = mysqli_query($dbc, $query);
	$out = array();
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			$out[] = $row;
		} 
		$output = array("infocode" => "DCRETRIEVEDSUCCESSFULLY", "data" => json_encode($out));
	} else {
		$output = array("infocode" => "DCRETRIEVENOTSUCCESSFUL", "message" => "DC not created succesfully");
	}
	return $output;
}

function updateDS(){
	global $dbc;
	$dcList = json_decode($_POST['dc_list']);
	$despatchScheduleID = mysqli_real_escape_string($dbc, $_POST['despatch_schedule_id']);
	$completedDate = mysqli_real_escape_string($dbc, $_POST['completed_date']);
	$completedTime = mysqli_real_escape_string($dbc, $_POST['completed_time']);
   	//file_put_contents("despatch.log", "\n".print_r($dcList, true), FILE_APPEND | LOCK_EX);
	for($index = 0; $index < count($dcList); $index++){
		$deliveryChallanID = $dcList[$index]->delivery_challan_id;
		$serialNumber = $dcList[$index]->serial_number;
		$despatchRequestID = getDespatchRequestID($deliveryChallanID);
		$status = DS_COMPLETED_STATUS;
		$updateDSQuery = "UPDATE despatch_schedule SET status='$status', completed_date='$completedDate', completed_time='$completedTime' WHERE despatch_schedule_id='$despatchScheduleID'";
		if(mysqli_query($dbc, $updateDSQuery)){
			$updateDCQuery = "UPDATE delivery_challan SET serial_number='$serialNumber', status='$status' WHERE delivery_challan_id='$deliveryChallanID'";
			if(mysqli_query($dbc, $updateDCQuery)){
				$updateDRQuery = "UPDATE despatch_request SET status='$status' WHERE despatch_request_id='$despatchRequestID'";
				mysqli_query($dbc, $updateDRQuery);
			}
		}
	}
	return array("infocode" => "DSUPDATEDSUCCESSFULLY", "message" => "DC updated succesfully");
}


?>
	