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
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function add_ticket(){
	global $dbc;
	$client_name = 'ABC Corp';//mysqli_real_escape_string($dbc,trim($_POST['client_name']));
	$location_name = mysqli_real_escape_string($dbc,trim($_POST['location_name']));
	$product = mysqli_real_escape_string($dbc,trim($_POST['product']));
	$complaint_name = mysqli_real_escape_string($dbc,trim($_POST['complaint_name']));
	$ticket_status = TICKET_CREATE_STATUS;
	$additional_details = mysqli_real_escape_string($dbc,trim($_POST['additional_details']));
	$machine_reading = mysqli_real_escape_string($dbc,trim($_POST['machine_reading']));
	$client_id = 1;
	
	$query = "INSERT INTO  raise_ticket (client_name,client_id, location_name,product,complaint_name,ticket_status,additional_details,machine_reading) VALUES('$client_name',$client_id ,'$location_name',
	'$product','$complaint_name','$ticket_status','$additional_details','$machine_reading')";
	if(mysqli_query($dbc,$query)){
		$ticket_id = mysqli_insert_id($dbc);
		$output = array("infocode" => "TICKETADDED", "message" => "Ticket created succesfully<br> Your ticket id: $ticket_id", "ticket_id" => $ticket_id);
	}
	else {
		$output = array("infocode" => "ADDTICKETFAILED", "message" => "Unable to create Ticket, please try again!");
	}
	
	return $output;
}
