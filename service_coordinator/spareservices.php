<?php 
require('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
define('TICKET_CREATE_STATUS','created'); 
define('TICKET_ASSIGN_STATUS','assigned'); 
define('TICKET_ENGG_CLOSE_STATUS','engineerclosed'); 
define('TICKET_SC_CLOSE_STATUS','closed');
define('SPARES_SC_APPROVED_STATUS','approved');
define('SPARES_SC_REJECTED_STATUS','rejected');
$finaloutput = array();
if(!$_POST) {
	$action = $_GET['action'];
}
else {
	$action = $_POST['action'];
}
switch($action){
	case 'add_spare':
        $finaloutput = add_spare();
    break;
	case 'approved_spare':
        $finaloutput = approved_spare();
    break;
	case 'rejected_spare':
        $finaloutput = rejected_spare();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function add_spare(){
	global $dbc;
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$product = mysqli_real_escape_string($dbc,trim($_POST['product']));
	$spare_name = mysqli_real_escape_string($dbc,trim($_POST['spare_name']));
	$spare_remark = mysqli_real_escape_string($dbc,trim($_POST['spare_remark']));
	$quantity = mysqli_real_escape_string($dbc,trim($_POST['quantity']));
	
	$query = "INSERT INTO spares (ticket_id,product,spare_name,spare_remark,quantity) VALUES('$ticket_id','$product','$spare_name','$spare_remark','$quantity')";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "SPAREADDED", "message" => "Spare created succesfully");
	}
	else {
		$output = array("infocode" => "ADDSPAREFAILED", "message" => "Unable to add Spare, please try again!");
	}
	
	return $output;
}

function edit_ticket(){
	global $dbc;
	$spare_id = mysqli_real_escape_string($dbc,trim($_POST['spare_id']));
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$product = mysqli_real_escape_string($dbc,trim($_POST['product']));
	$spare_name = mysqli_real_escape_string($dbc,trim($_POST['spare_name']));
	$spare_remark = mysqli_real_escape_string($dbc,trim($_POST['spare_remark']));
	$quantity = mysqli_real_escape_string($dbc,trim($_POST['quantity']));
	
	$query = "SELECT * FROM  spares";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		if($location_name == $row['location_name']){
			$output = array("infocode" => "TICKETEXISTS", "message" => "This Ticket name is already chosen, please choose a different name");
		}else{
			$output = array("infocode" => "COLOREXISTS", "message" => "This color code is already chosen, please choose a different color");
		}
	}else{
		$query = "UPDATE  raise_ticket SET client_name = '$client_name', location_name = '$location_name', product = '$product', complaint_name = '$complaint_name',
		ticket_status = '$ticket_status', additional_details = '$additional_details'";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "TICKETUPDATED", "message" => "Ticket category updated succesfully");
		}
		else {
			$output = array("infocode" => "UPDATETICKETFAILED", "message" => "Unable to update Ticket category, please try again!");
		}
	}
	return $output;
}

function approved_spare(){
	global $dbc;
	$spare_id = mysqli_real_escape_string($dbc,trim($_POST['spare_id']));
	$spare_stock = SPARES_SC_APPROVED_STATUS;
	
	$query = "UPDATE spares SET spare_stock = '$spare_stock' WHERE spare_id = $spare_id";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "SPAREUPDATED", "message" => "Approved Spares succesfully");
	}
	else {
		$output = array("infocode" => "UPDATESPAREFAILED", "message" => "Unable to Approved Spares, please try again!");
	}
	
	return $output;
}

function rejected_spare(){
	global $dbc;
	$spare_id = mysqli_real_escape_string($dbc,trim($_POST['spare_id']));
	$spare_stock = SPARES_SC_REJECTED_STATUS;
	
	$query = "UPDATE spares SET spare_stock = '$spare_stock' WHERE spare_id = $spare_id";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "SPAREUPDATED", "message" => "Rejected Spares succesfully");
	}
	else {
		$output = array("infocode" => "UPDATESPAREFAILED", "message" => "Unable to Rejected Spares, please try again!");
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