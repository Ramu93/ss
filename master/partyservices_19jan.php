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
	case 'add_party':
        $finaloutput = add_party();
    break;
    case 'edit_party':
        $finaloutput = edit_party();
    break;
	case 'sc_close_ticket':
        $finaloutput = sc_close_ticket();
    break;
	case 'add_partylocation':
        $finaloutput = add_partylocation();
    break;
	case 'spare_ticket':
        $finaloutput = spare_ticket();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function add_party(){
	global $dbc;
	$customer_name = mysqli_real_escape_string($dbc,trim($_POST['customer_name']));
	$address1 = mysqli_real_escape_string($dbc,trim($_POST['address1']));
	$address2 = mysqli_real_escape_string($dbc,trim($_POST['address2']));
	$city_town = mysqli_real_escape_string($dbc,trim($_POST['city_town']));
	$state = mysqli_real_escape_string($dbc,trim($_POST['state']));
	$pincode = mysqli_real_escape_string($dbc,trim($_POST['pincode']));
	$landline = mysqli_real_escape_string($dbc,trim($_POST['landline']));
	$primary_contact_name = mysqli_real_escape_string($dbc,trim($_POST['primary_contact_name']));
	$primary_contact_mobile = mysqli_real_escape_string($dbc,trim($_POST['primary_contact_mobile']));
	$primary_contact_email = mysqli_real_escape_string($dbc,trim($_POST['primary_contact_email']));
	$secondary_contact_name = mysqli_real_escape_string($dbc,trim($_POST['secondary_contact_name']));
	$secondary_contact_mobile = mysqli_real_escape_string($dbc,trim($_POST['secondary_contact_mobile']));
	$secondary_contact_email = mysqli_real_escape_string($dbc,trim($_POST['secondary_contact_email']));
	$pan_number = mysqli_real_escape_string($dbc,trim($_POST['pan_number']));
	$salestax_number = mysqli_real_escape_string($dbc,trim($_POST['salestax_number']));
	$servicetax_number = mysqli_real_escape_string($dbc,trim($_POST['servicetax_number']));
	$credit_days = mysqli_real_escape_string($dbc,trim($_POST['credit_days']));
	$credit_limit = mysqli_real_escape_string($dbc,trim($_POST['credit_limit']));
	$opening_balance = mysqli_real_escape_string($dbc,trim($_POST['opening_balance']));
	
	$query = "SELECT * FROM party_master WHERE customer_name = '$customer_name'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "PARTYEXISTS", "message" => "This party name is already chosen, please choose a different name");
	}else{
		$query = "INSERT INTO party_master (customer_name, address1, address2, city_town, state, pincode, landline, primary_contact_name, primary_contact_mobile, primary_contact_email, 
		secondary_contact_name, secondary_contact_mobile, secondary_contact_email, pan_number, salestax_number, servicetax_number, credit_days, credit_limit, opening_balance) 
		VALUES('$customer_name','$address1','$address2','$city_town','$state','$pincode','$landline','$primary_contact_name','$primary_contact_mobile','$primary_contact_email',
		'$secondary_contact_name','$secondary_contact_mobile','$secondary_contact_email','$pan_number','$salestax_number','$servicetax_number','$credit_days','$credit_limit','$opening_balance')";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "PARTYADDED", "message" => "Party added succesfully");
		}
		else {
			$output = array("infocode" => "ADDPARTYFAILED", "message" => "Unable to add Party, please try again!");
		}
	}
	return $output;
}

function edit_party(){
	global $dbc;
	$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
	$customer_name = mysqli_real_escape_string($dbc,trim($_POST['customer_name']));
	$address1 = mysqli_real_escape_string($dbc,trim($_POST['address1']));
	$address2 = mysqli_real_escape_string($dbc,trim($_POST['address2']));
	$city_town = mysqli_real_escape_string($dbc,trim($_POST['city_town']));
	$state = mysqli_real_escape_string($dbc,trim($_POST['state']));
	$pincode = mysqli_real_escape_string($dbc,trim($_POST['pincode']));
	$landline = mysqli_real_escape_string($dbc,trim($_POST['landline']));
	$primary_contact_name = mysqli_real_escape_string($dbc,trim($_POST['primary_contact_name']));
	$primary_contact_mobile = mysqli_real_escape_string($dbc,trim($_POST['primary_contact_mobile']));
	$primary_contact_email = mysqli_real_escape_string($dbc,trim($_POST['primary_contact_email']));
	$secondary_contact_name = mysqli_real_escape_string($dbc,trim($_POST['secondary_contact_name']));
	$secondary_contact_mobile = mysqli_real_escape_string($dbc,trim($_POST['secondary_contact_mobile']));
	$secondary_contact_email = mysqli_real_escape_string($dbc,trim($_POST['secondary_contact_email']));
	$pan_number = mysqli_real_escape_string($dbc,trim($_POST['pan_number']));
	$salestax_number = mysqli_real_escape_string($dbc,trim($_POST['salestax_number']));
	$servicetax_number = mysqli_real_escape_string($dbc,trim($_POST['servicetax_number']));
	$credit_days = mysqli_real_escape_string($dbc,trim($_POST['credit_days']));
	$credit_limit = mysqli_real_escape_string($dbc,trim($_POST['credit_limit']));
	$opening_balance = mysqli_real_escape_string($dbc,trim($_POST['opening_balance']));
		
	$query = "SELECT * FROM party_master WHERE (customer_name = '$customer_name') AND party_master_id != $party_master_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		if($customer_name == $row['customer_name']){
			$output = array("infocode" => "PARTYEXISTS", "message" => "This Party name is already chosen, please choose a different name");
		}
	}else{
		$query = "UPDATE party_master SET customer_name = '$customer_name',address1 = '$address1',address2 = '$address2',city_town = '$city_town',state = '$state',pincode = '$pincode',landline = '$landline',
	primary_contact_name = '$primary_contact_name',primary_contact_mobile = '$primary_contact_mobile',primary_contact_email = '$primary_contact_email',secondary_contact_name = '$secondary_contact_name',
	secondary_contact_mobile = '$secondary_contact_mobile',secondary_contact_email = '$secondary_contact_email',pan_number = '$pan_number',salestax_number = '$salestax_number',
	servicetax_number = '$servicetax_number',credit_days = '$credit_days',credit_limit = '$credit_limit',opening_balance = '$opening_balance' WHERE party_master_id = $party_master_id";
		if(mysqli_query($dbc,$query)){
			$output = array("infocode" => "PARTYUPDATED", "message" => "Party category updated succesfully");
		}
		else {
			$output = array("infocode" => "UPDATEPARTYFAILED", "message" => "Unable to update Party category, please try again!");
		}
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
function spare_ticket(){
	global $dbc;
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$spare_id = mysqli_real_escape_string($dbc,trim($_POST['spare_id']));
	$ticket_status = TICKET_ASSIGN_STATUS;
	
	$query = "UPDATE  sapres SET spare_id='$spare_id', ticket_status = '$ticket_status' WHERE ticket_id = $ticket_id";
	if(mysqli_query($dbc,$query)){
		$output = array("infocode" => "TICKETUPDATED", "message" => "Spare assigned succesfully");
	}
	else {
		$output = array("infocode" => "UPDATETICKETFAILED", "message" => "Unable to assign Spare, please try again!");
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