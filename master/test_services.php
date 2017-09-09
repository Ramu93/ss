<?php 
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
	case 'sc_close_ticket':
        $finaloutput = sc_close_ticket();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function add_ticket(){
	global $dbc;
	$client_name = mysqli_real_escape_string($dbc,trim($_POST['client_name']));
	$client_id = 1;
	$location_name = mysqli_real_escape_string($dbc,trim($_POST['location_name']));
	$department_name = mysqli_real_escape_string($dbc,trim($_POST['department_name']));
	$item_name = mysqli_real_escape_string($dbc,trim($_POST['item_name']));
	$complaint_name = mysqli_real_escape_string($dbc,trim($_POST['complaint_name']));
	$ticket_status = TICKET_CREATE_STATUS;
	$additional_details = mysqli_real_escape_string($dbc,trim($_POST['additional_details']));
	
	$query = "INSERT INTO  raise_ticket (client_name, client_id,location_name,department_name,item_name,complaint_name,ticket_status,additional_details) VALUES('$client_name',$client_id, '$location_name',
	'$department_name','$item_name','$complaint_name','$ticket_status','$additional_details')";
	if(mysqli_query($dbc,$query)){
		$ticket_id = mysqli_insert_id($dbc);
		$output = array("infocode" => "TICKETADDED", "message" => "Ticket created succesfully<br> Your ticket id: $ticket_id", "ticket_id" => $ticket_id);
	}
	else {
		$output = array("infocode" => "ADDTICKETFAILED", "message" => "Unable to create Ticket, please try again!");
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


function sc_close_ticket(){
	global $dbc;
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$remarks = mysqli_real_escape_string($dbc,trim($_POST['remarks']));
	$status = TICKET_SC_CLOSE_STATUS;
	
	$query = "UPDATE  raise_ticket SET remarks = '$remarks',ticket_status = '$status' WHERE ticket_id = $ticket_id";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "TICKETUPDATED", "message" => "Ticket Closed succesfully");
	}
	else {
		$output = array("infocode" => "UPDATETICKETFAILED", "message" => "Unable to Close Ticket, please try again!");
	}
	
	return $output;
}
