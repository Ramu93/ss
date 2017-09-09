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
	case 'add_complaint':
        $finaloutput = add_complaint();
    break;
    case 'edit_complaint':
        $finaloutput = edit_complaint();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function add_complaint(){
	global $dbc;
	$complaint_name = mysqli_real_escape_string($dbc,trim($_POST['complaint_name']));
	$complaint_code = mysqli_real_escape_string($dbc,trim($_POST['complaint_code']));

	$query = "SELECT * FROM nature_of_comp WHERE complaint_name = '$complaint_name' ";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		if($complaint_name == $row['complaint_name']){
			$output = array("infocode" => "COMPLAINTEXISTS", "message" => "This Service Coordinator name is already chosen, please choose a different name");
		}
	}else{
		$query = "INSERT INTO nature_of_comp (complaint_name,complaint_code) VALUES('$complaint_name','$complaint_code')";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "COMPLAINTADDED", "message" => " Complaint added succesfully");
		}
		else {
			$output = array("infocode" => "ADDCOMPLAINTFAILED", "message" => "Unable to add Complaint, please try again!");
		}
	}
	return $output;
}

function edit_complaint(){
	global $dbc;
	$NCMPLTID = mysqli_real_escape_string($dbc,trim($_POST['NCMPLTID']));
	$complaint_name = mysqli_real_escape_string($dbc,trim($_POST['complaint_name']));
	$complaint_code = mysqli_real_escape_string($dbc,trim($_POST['complaint_code']));
	
	$query = "SELECT * FROM nature_of_comp WHERE (complaint_name = '$complaint_name' ) AND NCMPLTID != $NCMPLTID";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
			$row = mysqli_fetch_assoc($result);
			if($complaint_name == $row['complaint_name']){
			$output = array("infocode" => "COMPLAINTEXISTS", "message" => "This Complaint name is already chosen, please choose a different name");
			}
		}else{
			$query = "UPDATE nature_of_comp SET complaint_name = '$complaint_name' WHERE NCMPLTID = $NCMPLTID";
			if(mysqli_query($dbc,$query)){
				$output = array("infocode" => "COMPLAINTUPDATED", "message" => "Complaint updated succesfully");
			}
			else {
			$output = array("infocode" => "UPDATECOMPLAINFAILED", "message" => "Unable to update Complaint, please try again!");
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