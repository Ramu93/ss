<?php 
require('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
$finaloutput = array();
if(!$_POST) {
	$action = $_GET['action'];
}
else {
	$action = $_POST['action'];
}
switch($action){
	case 'add_engineer':
        $finaloutput = add_engineer();
    break;
    case 'edit_engineer':
        $finaloutput = edit_engineer();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function add_engineer(){
	global $dbc;
	$engineer_name = mysqli_real_escape_string($dbc,trim($_POST['engineer_name']));
	$service_coordinator_id = mysqli_real_escape_string($dbc,trim($_POST['service_coordinator_id']));

	$query = "SELECT * FROM master_engineer WHERE engineer_name = '$engineer_name' AND service_coordinator_id = $service_coordinator_id ";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		if($engineer_name == $row['engineer_name']){
			$output = array("infocode" => "ENGINEEREXISTS", "message" => "This Engineer Name is already chosen, please choose a different name");
		}
	}else{
		$query = "INSERT INTO master_engineer (engineer_name,service_coordinator_id) VALUES('$engineer_name','$service_coordinator_id')";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "ENGINEERADDED", "message" => "Engineer Name added succesfully");
		}
		else {
			$output = array("infocode" => "ADDENGINEERFAILED", "message" => "Unable to add Engineer Name, please try again!");
		}
	}
	return $output;
}

function edit_engineer(){
	global $dbc;
	$engineer_master_id = mysqli_real_escape_string($dbc,trim($_POST['engineer_master_id']));
	$engineer_name = mysqli_real_escape_string($dbc,trim($_POST['engineer_name']));
	$service_coordinator_id = mysqli_real_escape_string($dbc,trim($_POST['service_coordinator_id']));
	
	$query = "SELECT * FROM master_engineer WHERE (engineer_name = '$engineer_name' AND service_coordinator_id = $service_coordinator_id ) AND engineer_master_id != $engineer_master_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		if($engineer_name == $row['engineer_name']){
			$output = array("infocode" => "ENGINEEREXISTS", "message" => "This Engineer Name is already chosen, please choose a different name");
		}
	}else{
		$query = "UPDATE master_engineer SET engineer_name = '$engineer_name', service_coordinator_id = $service_coordinator_id WHERE engineer_master_id = $engineer_master_id";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "ENGINEERUPDATED", "message" => "Engineer Name updated succesfully");
		}
		else {
			$output = array("infocode" => "UPDATEENGINEERFAILED", "message" => "Unable to update Engineer Name, please try again!");
		}
	}
	return $output;
}

function delete_tariff_details() {
    global $dbc;
    $flag = true;
    $tariff_master_id = mysqli_real_escape_string($dbc, trim($_POST['tariff_master_id']));
    
    $query = "DELETE FROM tariff_master WHERE tariff_master_id='$tariff_master_id'";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "TARIFFDELETED", "message" => "Tariff Details deleted succesfully");
	}else{
		$output = array("infocode" => "TARIFFDELETEFAILED", "message" => "Error occurred while deleting Tariff Details");
	}
	
	return $output;
}