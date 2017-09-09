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
	case 'asset_change':
        $finaloutput = asset_change();
    break; 
	case 'location_change':
        $finaloutput = location_change();
    break; 
	case 'product_change':
        $finaloutput = product_change();
    break; 
    case 'product_type_change':
        $finaloutput = product_type_change();
    break; 
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function asset_change(){
	global $dbc; $location_data = array();
	$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
	
	$query = "SELECT * FROM party_location WHERE party_master_id = '$party_master_id'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_assoc($result)){
			$location_data[] = $row;
		}
		$output = array("infocode" => "LOCATIONDATARETREIVED", "message" => "Asset data retreived", "location_data" => $location_data);
	}else{
		$output = array("infocode" => "NOASSETDATA", "message" => "No Asset data available for this Asset");
	}
	return $output;
}
function location_change(){
	global $dbc; $department_data = array();
	$party_location_id = mysqli_real_escape_string($dbc,trim($_POST['party_location_id']));
	
	$query = "SELECT * FROM department WHERE party_location_id = '$party_location_id'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_assoc($result)){
			$department_data[] = $row;
		}
		$output = array("infocode" => "DEPARTMENTDATARETREIVED", "message" => "Asset data retreived", "department_data" => $department_data);
	}else{
		$output = array("infocode" => "NOASSETDATA", "message" => "No Asset data available for this Asset");
	}
	return $output;
}
function department_change(){
	global $dbc; $location_data = array();
	$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
	
	$query = "SELECT * FROM party_location WHERE party_master_id = '$party_master_id'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_assoc($result)){
			$location_data[] = $row;
		}
		$output = array("infocode" => "DEPARTMENTDATARETREIVED", "message" => "Department data retreived", "location_data" => $location_data);
	}else{
		$output = array("infocode" => "NODEPARTMENTDATA", "message" => "No Department data available for this Department");
	}
	return $output;
}
function product_change(){
	global $dbc; $item_data = array();
	$item_master_id = mysqli_real_escape_string($dbc,trim($_POST['item_master_id']));
	
	$query = "SELECT * FROM item_master WHERE item_master_id = '$item_master_id'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_assoc($result)){
			$item_data[] = $row;
		}
		$output = array("infocode" => "PRODUCTDATARETREIVED", "message" => "Product data retreived", "item_data" => $item_data);
	}else{
		$output = array("infocode" => "NOPRODUCTDATA", "message" => "No Product data available for this Product");
	}
	return $output;
}

function product_type_change(){
	global $dbc; $item_data = array();
	$product_type = mysqli_real_escape_string($dbc,trim($_POST['product_type']));
	
	$query = "SELECT * FROM item_master WHERE item_type = '$product_type'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_assoc($result)){
			$item_data[] = $row;
		}
		$output = array("infocode" => "PRODUCTDATARETREIVED", "message" => "Product data retreived", "item_data" => $item_data);
	}else{
		$output = array("infocode" => "NOPRODUCTDATA", "message" => "No Product data available for this Product");
	}
	return $output;
}