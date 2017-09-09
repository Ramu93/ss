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
	case 'add_item':
        $finaloutput = add_item();
    break;
    case 'edit_item':
        $finaloutput = edit_item();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function add_item(){
	global $dbc;
	$item_name = mysqli_real_escape_string($dbc,trim($_POST['item_name']));
	$item_type = mysqli_real_escape_string($dbc,trim($_POST['product_type']));
	$brand = mysqli_real_escape_string($dbc,trim($_POST['brand']));
	$item_group = mysqli_real_escape_string($dbc,trim($_POST['item_group']));
	$item_code = mysqli_real_escape_string($dbc,trim($_POST['item_code']));
	$threshold_quantity = mysqli_real_escape_string($dbc,trim($_POST['threshold_quantity']));
	$moq = mysqli_real_escape_string($dbc,trim($_POST['moq']));
	$returnable_flag = mysqli_real_escape_string($dbc,trim($_POST['returnable_flag']));
	
	$query = "SELECT * FROM item_master WHERE item_name = '$item_name'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "ITEMEXISTS", "message" => "This Product name is already chosen, please choose a different name");
	}else{
		$query = "INSERT INTO  item_master (item_name, item_type, brand, group_name, item_code, threshold_quantity, minimum_order_quantity, is_returnable) 
		VALUES ('$item_name','$item_type','$brand','$item_group','$item_code','$threshold_quantity','$moq','$returnable_flag')";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "ITEMADDED", "message" => "Product added succesfully");
		}
		else {
			$output = array("infocode" => "ADDITEMFAILED", "message" => "Unable to add Product, please try again!");
		}
	}
	return $output;
}

function edit_item(){
	global $dbc;
	$item_master_id = mysqli_real_escape_string($dbc,trim($_POST['item_master_id']));
	$item_name = mysqli_real_escape_string($dbc,trim($_POST['item_name']));
	$item_type = mysqli_real_escape_string($dbc,trim($_POST['product_type']));
	$brand = mysqli_real_escape_string($dbc,trim($_POST['brand']));
	$item_group = mysqli_real_escape_string($dbc,trim($_POST['item_group']));
	$item_code = mysqli_real_escape_string($dbc,trim($_POST['item_code']));
	$threshold_quantity = mysqli_real_escape_string($dbc,trim($_POST['threshold_quantity']));
	$moq = mysqli_real_escape_string($dbc,trim($_POST['moq']));
	$returnable_flag = mysqli_real_escape_string($dbc,trim($_POST['returnable_flag']));
	
	$query = "SELECT * FROM item_master WHERE item_name = '$item_name' AND item_master_id != $item_master_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "ITEMEXISTS", "message" => "This Product name is already chosen, please choose a different name");
	}else{
		$query = "UPDATE item_master SET item_name = '$item_name', item_type = '$item_type', brand = '$brand', group_name = '$item_group', item_code = '$item_code',
		threshold_quantity = '$threshold_quantity', minimum_order_quantity = '$moq', is_returnable = '$returnable_flag' WHERE item_master_id = $item_master_id";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "ITEMUPDATED", "message" => "Product Updated succesfully");
		}
		else {
		$output = array("infocode" => "UPDATEITEMFAILED", "message" => "Unable to Product, please try again!");
		}
	}
	return $output;
}
