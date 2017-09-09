<?php 
session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}

require('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
define('TICKET_CREATE_STATUS','created'); 
define('TICKET_ASSIGN_STATUS','assigned'); 
define('TICKET_ENGG_CLOSE_STATUS','engineerclosed'); 
define('TICKET_SC_CLOSE_STATUS','closed'); 
$finaloutput = array();
if(!$_POST) {
	$action = $_GET['action'];
}
else {
	$action = $_POST['action'];
}
switch($action){
	case 'add_asset':
        $finaloutput = add_asset();
    break;
    case 'edit_asset':
        $finaloutput = edit_asset();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function add_asset(){
	global $dbc;
	$rfid_tag = mysqli_real_escape_string($dbc,trim($_POST['rfid_tag']));
	$item_master_id = mysqli_real_escape_string($dbc,trim($_POST['item_master_id']));
	$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
	$party_location_id = mysqli_real_escape_string($dbc,trim($_POST['party_location_id']));
	$department_id = mysqli_real_escape_string($dbc,trim($_POST['department_id']));
	$manufacturer_serial_number = mysqli_real_escape_string($dbc,trim($_POST['manu_sno']));
	$machine_reading = mysqli_real_escape_string($dbc,trim($_POST['machine_reading']));
	$rent = mysqli_real_escape_string($dbc,trim($_POST['rent']));
	$free_copies = mysqli_real_escape_string($dbc,trim($_POST['free_copies']));
	$extra_copy_charges = mysqli_real_escape_string($dbc,trim($_POST['extra_copy_charges']));
	$old_code = mysqli_real_escape_string($dbc,trim($_POST['old_code']));
	$active_from = mysqli_real_escape_string($dbc,trim($_POST['effective_from']));
	$status = mysqli_real_escape_string($dbc,trim($_POST['asset_status']));
	
	$query = "SELECT * FROM asset_master WHERE rfid_tag = '$rfid_tag'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "ASSETEXISTS", "message" => "This RFID tag is already assigned, please choose a different one");
	}else{
	$query = "INSERT INTO  asset_master (rfid_tag, item_master_id, party_master_id , party_location_id, department_id, manufacturer_serial_number, machine_reading, rent, free_copies, extra_copy_charges, old_code, active_from, status) 
	VALUES('$rfid_tag','$item_master_id','$party_master_id','$party_location_id','$department_id','$manufacturer_serial_number', $machine_reading, $rent, $free_copies, $extra_copy_charges, '$old_code', '$active_from','$status')";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "ASSETADDED", "message" => "Asset added succesfully");
		}
		else {
		$output = array("infocode" => "ADDASSETFAILED", "message" => "Unable to add Asset, please try again!");
		}
	}
	return $output;
}

function edit_asset(){
	global $dbc;
	$asset_master_id = mysqli_real_escape_string($dbc,trim($_POST['asset_master_id']));
	$rfid_tag = mysqli_real_escape_string($dbc,trim($_POST['rfid_tag']));
	$item_master_id = mysqli_real_escape_string($dbc,trim($_POST['item_master_id']));
	$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
	$party_location_id = mysqli_real_escape_string($dbc,trim($_POST['party_location_id']));
	$department_id = mysqli_real_escape_string($dbc,trim($_POST['department_id']));
	$manufacturer_serial_number = mysqli_real_escape_string($dbc,trim($_POST['manu_sno']));
	$machine_reading = mysqli_real_escape_string($dbc,trim($_POST['machine_reading']));
	$rent = mysqli_real_escape_string($dbc,trim($_POST['rent']));
	$free_copies = mysqli_real_escape_string($dbc,trim($_POST['free_copies']));
	$extra_copy_charges = mysqli_real_escape_string($dbc,trim($_POST['extra_copy_charges']));
	$old_code = mysqli_real_escape_string($dbc,trim($_POST['old_code']));
	$active_from = mysqli_real_escape_string($dbc,trim($_POST['effective_from']));
	$status = mysqli_real_escape_string($dbc,trim($_POST['asset_status']));
	
	$query = "SELECT * FROM asset_master WHERE (rfid_tag = '$rfid_tag') AND asset_master_id != $asset_master_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "ASSETEXISTS", "message" => "This Tag is mapped to another asset, please choose a different tag");
	}else{
		$query = "UPDATE asset_master SET rfid_tag= '$rfid_tag', item_master_id = $item_master_id, party_master_id = $party_master_id, party_location_id = $party_location_id,
		department_id = '$department_id', manufacturer_serial_number = '$manufacturer_serial_number', machine_reading = $machine_reading, rent = $rent, free_copies = $free_copies,
		extra_copy_charges = $extra_copy_charges, old_code = '$old_code', active_from ='$active_from', status = '$status' WHERE asset_master_id = $asset_master_id";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "ASSETUPDATED", "message" => "Asset details updated succesfully");
		}
		else {
		$output = array("infocode" => "UPDATEASSETFAILED", "message" => "Unable to Update Asset details, please try again!");
		}
	}
	return $output;
}