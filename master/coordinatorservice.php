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
	case 'add_service':
        $finaloutput = add_service();
    break;
    case 'edit_service':
        $finaloutput = edit_service();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function add_service(){
	global $dbc;
	$service_coordinator_name = mysqli_real_escape_string($dbc,trim($_POST['service_coordinator_name']));

	$query = "SELECT * FROM service_coordinator WHERE service_coordinator_name = '$service_coordinator_name' ";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		if($service_coordinator_name == $row['service_coordinator_name']){
			$output = array("infocode" => "SERVICEEXISTS", "message" => "This Service Coordinator name is already chosen, please choose a different name");
		}
	}else{
		$query = "INSERT INTO service_coordinator (service_coordinator_name) VALUES('$service_coordinator_name')";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "SERVICEADDED", "message" => "Service Coordinator added succesfully");
		}
		else {
			$output = array("infocode" => "ADDSERVICEFAILED", "message" => "Unable to add Service Coordinator, please try again!");
		}
	}
	return $output;
}

function edit_service(){
	global $dbc;
	$service_coordinator_id = mysqli_real_escape_string($dbc,trim($_POST['service_coordinator_id']));
	$service_coordinator_name = mysqli_real_escape_string($dbc,trim($_POST['service_coordinator_name']));
	
	$query = "SELECT * FROM service_coordinator WHERE (service_coordinator_name = '$service_coordinator_name' ) AND service_coordinator_id != $service_coordinator_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		if($service_coordinator_name == $row['service_coordinator_name']){
			$output = array("infocode" => "SERVICEEXISTS", "message" => "This Service Coordinator name is already chosen, please choose a different name");
		}
	}else{
		$query = "UPDATE service_coordinator SET service_coordinator_name = '$service_coordinator_name' WHERE service_coordinator_id = $service_coordinator_id";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "SERVICEUPDATED", "message" => "Service Coordinator updated succesfully");
		}
		else {
			$output = array("infocode" => "UPDATESERVICEFAILED", "message" => "Unable to update Service Coordinator, please try again!");
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