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
	case 'add_call':
        $finaloutput = add_call();
    break;
    case 'edit_item':
        $finaloutput = edit_item();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function add_call(){
	global $dbc;
	$call_type = mysqli_real_escape_string($dbc,trim($_POST['call_type']));
	$customer_name = mysqli_real_escape_string($dbc,trim($_POST['customer_name']));
	$customer_address = mysqli_real_escape_string($dbc,trim($_POST['customer_address']));
	$contact_person = mysqli_real_escape_string($dbc,trim($_POST['contact_person']));
	$contact_number = mysqli_real_escape_string($dbc,trim($_POST['contact_number']));
	$remark = mysqli_real_escape_string($dbc,trim($_POST['remark']));
	
	$query = "SELECT * FROM inbound_call WHERE customer_name = '$customer_name'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "REGISTERCALLEXISTS", "message" => "This Register Call is already chosen, please choose a different name");
	}else{
	$query = "INSERT INTO inbound_call (call_type, customer_name, customer_address, contact_person, contact_number, remark) VALUES('$call_type','$customer_name','$customer_address','$contact_person','$contact_number','$remark')";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "REGISTERCALLADDED", "message" => "Register Call added succesfully");
		}
		else {
		$output = array("infocode" => "ADDREGISTERCALLFAILED", "message" => "Unable to add Register Call, please try again!");
		}
	}
	return $output;
}
function edit_item(){
	global $dbc;
	$item_master_id = mysqli_real_escape_string($dbc,trim($_POST['item_master_id']));
	$item_name = mysqli_real_escape_string($dbc,trim($_POST['item_name']));
	$item_type = mysqli_real_escape_string($dbc,trim($_POST['item_type']));
	$brand = mysqli_real_escape_string($dbc,trim($_POST['brand']));
	
	$query = "SELECT * FROM item_master WHERE (item_name = '$item_name' AND item_type = '$item_type' AND brand = '$item_type') AND item_master_id != $item_master_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "ITEMEXISTS", "message" => "This Item name is already chosen, please choose a different name");
	}else{
		$query = "UPDATE item_master SET item_name= '$item_name', item_type = '$item_type', brand ='$brand' WHERE item_master_id = $item_master_id";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "ITEMUPDATED", "message" => "Item Update succesfully");
		}
		else {
		$output = array("infocode" => "UPDATEITEMFAILED", "message" => "Unable to Item, please try again!");
		}
	}
	return $output;
}
