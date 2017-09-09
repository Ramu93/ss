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
	case 'add_department':
        $finaloutput = add_department();
    break;
    case 'edit_department':
        $finaloutput = edit_department();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function add_department(){
	global $dbc;
	$department_name = mysqli_real_escape_string($dbc,trim($_POST['department_name']));
	$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
	$party_location_id = mysqli_real_escape_string($dbc,trim($_POST['party_location_id']));
	
	$query = "SELECT * FROM department WHERE department_name='$department_name' AND party_master_id = '$party_master_id' AND party_location_id = '$party_location_id'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "DEPARTMENTEXISTS", "message" => "This Department name is already chosen, please choose a different name");
	}else{
	$query = "INSERT INTO  department (department_name, party_master_id, party_location_id) VALUES('$department_name','$party_master_id','$party_location_id')";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "DEPARTMENTADDED", "message" => "Department added succesfully");
		}
		else {
		$output = array("infocode" => "ADDDEPARTMENTFAILED", "message" => "Unable to add Department, please try again!");
		}
	}
	return $output;
}
function edit_department(){
	global $dbc;
	$department_id = mysqli_real_escape_string($dbc,trim($_POST['department_id']));
	$department_name = mysqli_real_escape_string($dbc,trim($_POST['department_name']));
	$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
	$party_location_id = mysqli_real_escape_string($dbc,trim($_POST['party_location_id']));
	
	$query = "SELECT * FROM department WHERE (department_name = '$department_name' AND party_master_id = $party_master_id AND party_location_id = $party_location_id ) AND department_id != $department_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "DEPARTMENTEXISTS", "message" => "This Department name is already chosen, please choose a different name");
	}else{
		$query = "UPDATE department SET department_name = '$department_name', party_master_id = $party_master_id, party_location_id = $party_location_id WHERE department_id = $department_id";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "DEPARTMENTUPDATED", "message" => "Department updated succesfully");
		}
		else {
			$output = array("infocode" => "UPDATEDEPARTMENTFAILED", "message" => "Unable to update Department , please try again!");
		}
	}
	return $output;
}

