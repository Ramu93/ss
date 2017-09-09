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
define('SPARE_CREATE_STATUS','created');
define('SPARE_ASSIGN_STATUS','pending');
define('SPARE_RETURN_STATUS','returned');
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
	case 'assign_spare':
        $finaloutput = assign_spare();
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
	$quantity = mysqli_real_escape_string($dbc,trim($_POST['quantity']));
	
	$query = "INSERT INTO spares (ticket_id,product,spare_name,quantity) VALUES('$ticket_id','$product','$spare_name','$quantity')";
	if(mysqli_query($dbc,$query)){
		$spare_id = mysqli_insert_id($dbc);
		$output = array("infocode" => "SPAREADDED", "message" => "Spare request created succesfully.<br> Request ID : $spare_id");
	}
	else {
		$output = array("infocode" => "ADDSPAREFAILED", "message" => "Unable to add Spare, please try again!");
	}
	
	return $output;
}

function assign_spare(){
	global $dbc;
	$spare_id = mysqli_real_escape_string($dbc,trim($_POST['spare_id']));
	$spare_status = SPARE_RETURN_STATUS;
	
	$query = "UPDATE spares SET spare_status = '$spare_status' WHERE spare_id = $spare_id";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "SPAREUPDATED", "message" => "Old Spare returned succesfully");
	}
	else {
		$output = array("infocode" => "UPDATESPAREFAILED", "message" => "Unable to return old Spare, please try again!");
	}
	
	return $output;
}
