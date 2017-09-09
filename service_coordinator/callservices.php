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
	case 'fetch_call_details':
        $finaloutput = fetch_call_details();
    break;
    case 'fetch_call_list':
        $finaloutput = fetch_call_list();
    break;
    case 'sc_close_call':
        $finaloutput = sc_close_call();
    break;
    case 'fetch_call_details_assigned':
        $finaloutput = fetch_call_details_assigned();
    break;
	default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function fetch_call_details(){
	global $dbc; $call_data = array();
	$call_id = mysqli_real_escape_string($dbc,trim($_POST['call_id']));
	
	$select_query = "SELECT a.* , b.customer_name, c.location_name, d.department_name
                        FROM inbound_call a, party_master b, party_location c, department d
                        WHERE a.inbound_call_id = $call_id AND a.party_master_id = b.party_master_id AND a.party_location_id = c.party_location_id AND a.department_id = d.department_id";
                        //$select_query = "SELECT * FROM raise_ticket WHERE ticket_status = '$ticket_status'";
    $result = mysqli_query($dbc,$select_query);
    $datatableflag = false;
    if(mysqli_num_rows($result) > 0) {
    	$row = mysqli_fetch_assoc($result);
		$call_data = $row;
		$output = array("infocode" => "CALLDETAILSUCCESS", "message" => "Call details retreived", "call_data" => $call_data);
	}else{
		$output = array("infocode" => "NOCALLDETAIL", "message" => "No call details available");
	}
	return $output;
}

function fetch_call_list(){
	global $dbc; $call_data = array();
	$call_status2 = mysqli_real_escape_string($dbc,trim($_POST['call_status2']));
	
	$select_query = "SELECT a.* , b.customer_name, c.location_name 
                        FROM inbound_call a, party_master b, party_location c
                        WHERE a.call_status IN ('$call_status2') AND a.call_type = 'service' AND a.party_master_id = b.party_master_id AND a.party_location_id = c.party_location_id";
                        
    $result = mysqli_query($dbc,$select_query);
    $row_counter = 0;
    $datatableflag = false;
    if(mysqli_num_rows($result) > 0) {
    	$datatableflag = true;
        while($row = mysqli_fetch_array($result)) {
   			$call_data[] = $row;
		}
		$output = array("infocode" => "CALLLISTSUCCESS", "message" => "Call List retreived", "call_data" => $call_data);
	}else{
		$output = array("infocode" => "NOCALLLIST", "message" => "No call list available");
	}
	return $output;
}

function sc_close_call(){
	global $dbc;
	$call_id = mysqli_real_escape_string($dbc,trim($_POST['call_id']));
	$remarks = mysqli_real_escape_string($dbc,trim($_POST['remarks']));
	$status = TICKET_SC_CLOSE_STATUS;
	
	$query = "UPDATE inbound_call SET remarks = '$remarks',call_status = '$status' WHERE inbound_call_id = $call_id";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "CALLUPDATED", "message" => "Call Closed succesfully");
	}
	else {
		$output = array("infocode" => "CLOSECALLFAILED", "message" => "Unable to Close Call, please try again!");
	}
	
	return $output;
}

function fetch_call_details_assigned(){
	global $dbc; $call_data = array();
	$call_id = mysqli_real_escape_string($dbc,trim($_POST['call_id']));
	
	$query = "SELECT * FROM inbound_call WHERE inbound_call_id = $call_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result) > 0) {
    	$row = mysqli_fetch_assoc($result);
		$call_data = $row;
		if($row['call_status'] == 'closed'){
			$output = array("infocode" => "CALLDETAILSUCCESS", "message" => "Call details retreived", "call_info" => "This call was closed with the following remarks : ".$row['remarks'] , "ticket_id" => 0);	
		}else if($row['call_status'] == 'ticketcreated'){
			$q2 = "SELECT * FROM raise_ticket WHERE inbound_call_id = $call_id";
			$r2 = mysqli_query($dbc, $q2);
			if(mysqli_num_rows($r2)>0){
				$rw2 = mysqli_fetch_assoc($r2);
				$output = array("infocode" => "CALLDETAILSUCCESS", "message" => "Call details retreived", "call_info" => "This call was converted as a ticket with Ticket ID : ".$rw2['ticket_id'], "ticket_id" => $rw2['ticket_id']);	
			}else{
				$output = array("infocode" => "CALLDETAILSUCCESS", "message" => "Call details retreived", "call_info" => "This call was converted as a ticket ", "ticket_id" => 0);	
			}
		}else{
			$output = array("infocode" => "NOCALLDETAIL", "message" => "No call details available");
		}
	}else{
		$output = array("infocode" => "NOCALLDETAIL", "message" => "No call details available");
	}
	return $output;
}
