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
	case 'add_ticket':
        $finaloutput = add_ticket();
    break;
    case 'assign_ticket':
        $finaloutput = assign_ticket();
    break;
	case 'close_ticket':
        $finaloutput = close_ticket();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function add_ticket(){
	global $dbc;
	$call_type = implode(',',$_POST['call_type']); //mysqli_real_escape_string($dbc,trim($_POST['call_type']));
	$contact_person = mysqli_real_escape_string($dbc,trim($_POST['contact_person']));
	$contact_number = mysqli_real_escape_string($dbc,trim($_POST['contact_number']));
	$remark = mysqli_real_escape_string($dbc,trim($_POST['remark']));
	$customer_type = mysqli_real_escape_string($dbc,trim($_POST['customer_type']));
	if($customer_type == 'existing'){
		$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
		$party_location_id = mysqli_real_escape_string($dbc,trim($_POST['party_location_id']));
		$department_id = mysqli_real_escape_string($dbc,trim($_POST['department_id']));
		$sc_id = fetch_sc($party_location_id);
		$sc_id = ($sc_id!='')?$sc_id:2;
		$call_received_by = $_SESSION['userid'];
		$query = "INSERT INTO inbound_call (call_type,customer_type,party_master_id,party_location_id,department_id,contact_person,contact_number,remarks, call_received_by, sc_id, call_status) 
		VALUES ('$call_type','$customer_type','$party_master_id','$party_location_id','$department_id','$contact_person','$contact_number','$remark', $call_received_by, $sc_id, 'created')";
	}else{
		$customer_name = mysqli_real_escape_string($dbc,trim($_POST['customer_name']));
		$customer_address = mysqli_real_escape_string($dbc,trim($_POST['customer_address']));
		$query = "INSERT INTO inbound_call (call_type,customer_type,customer_name,customer_address,contact_person,contact_number,remarks) 
		VALUES ('$call_type','$customer_type','$customer_name','$customer_address','$contact_person','$contact_number','$remark')";
	}
	
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "REGISTERCALLADDED", "message" => "Inbound Call registered succesfully");
	}
	else {
		$output = array("infocode" => "ADDREGISTERCALLFAILED", "message" => "Unable to add Register Call, please try again!");
	}
	
	return $output;
}

function assign_ticket(){
	global $dbc;
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$engineer_id = mysqli_real_escape_string($dbc,trim($_POST['engineer_id']));
	$ticket_status = TICKET_ASSIGN_STATUS;
	
	$query = "UPDATE  raise_ticket SET engineer_id='$engineer_id', ticket_status = '$ticket_status' WHERE ticket_id = $ticket_id";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "TICKETUPDATED", "message" => "Engineer assigned succesfully");
	}
	else {
		$output = array("infocode" => "UPDATETICKETFAILED", "message" => "Unable to assign Engineer, please try again!");
	}
	
	return $output;
}

function close_ticket(){
	global $dbc;
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$ticket_status = TICKET_ENGG_CLOSE_STATUS;
	
	$query = "UPDATE  raise_ticket SET ticket_status = '$ticket_status' WHERE ticket_id = $ticket_id";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "TICKETUPDATED", "message" => "Ticket Closed succesfully");
	}
	else {
		$output = array("infocode" => "UPDATETICKETFAILED", "message" => "Unable to Close Ticket, please try again!");
	}
	
	return $output;
}

function fetch_sc($party_location_id){
	global $dbc;
	$query = "SELECT sc_id FROM party_location WHERE party_location_id = $party_location_id";
	$result = mysqli_query($dbc,$query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		$output = $row['sc_id'];
	}
	else {
		$output = '';
	}
	
	return $output;
}