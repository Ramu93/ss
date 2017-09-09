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
    case 'update_partylocation':
        $finaloutput = update_partylocation();
    break;
	case 'add_department':
        $finaloutput = add_department();
    break;
    case 'display_department':
        $finaloutput = display_department();
    break;
    case 'delete_location':
        $finaloutput = delete_location();
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

function add_partylocation(){
	global $dbc;
	$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['h_party_master_id']));
	$location_name = mysqli_real_escape_string($dbc,trim($_POST['location_name']));
	//$address = mysqli_real_escape_string($dbc,trim($_POST['address']));
	$sc_id = mysqli_real_escape_string($dbc,trim($_POST['assign_sc_id']));
	
	$query = "SELECT * FROM party_location WHERE location_name = '$location_name' AND party_master_id = $party_master_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "PARTYLOCATIONEXISTS", "message" => "This Location name is already chosen for this Customer, please choose a different name");
	}else{
		//$query = "INSERT INTO party_location (party_master_id, location_name, address, sc_id) VALUES('$party_master_id','$location_name','$address', $sc_id)";
		$query = "INSERT INTO party_location (party_master_id, location_name, sc_id) VALUES('$party_master_id','$location_name', $sc_id)";
		if(mysqli_query($dbc,$query)){
			$q2 = "SELECT a.*,b.employee_name as sc_name FROM party_location a, employee_master b WHERE a.party_master_id = $party_master_id AND a.sc_id = b.employee_master_id";
			$data = array();
			$r2 = mysqli_query($dbc, $q2);
			if(mysqli_num_rows($r2)>0){
				while($rw2 = mysqli_fetch_assoc($r2)){
					$data[] = $rw2;
				}
			}
			$output = array("infocode" => "PARTYLOCATIONADDED", "message" => "Customer Location added succesfully", "location_data" => $data);
		}
		else {
			$output = array("infocode" => "ADDPARTYLOCATIONFAILED", "message" => "Unable to add Customer Location, please try again!");
		}
	}
	return $output;
}

function update_partylocation(){
	global $dbc;
	$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
	$party_location_id = mysqli_real_escape_string($dbc,trim($_POST['party_location_id']));
	$location_name = mysqli_real_escape_string($dbc,trim($_POST['location_name']));
	$sc_id = mysqli_real_escape_string($dbc,trim($_POST['sc_id']));
	
	
	$query = "UPDATE party_location SET location_name = '$location_name', sc_id =  $sc_id WHERE party_location_id = $party_location_id";
	if(mysqli_query($dbc,$query)){
		$q2 = "SELECT a.*,b.employee_name as sc_name FROM party_location a, employee_master b WHERE a.party_master_id = $party_master_id AND a.sc_id = b.employee_master_id";
		$data = array();
		$r2 = mysqli_query($dbc, $q2);
		if(mysqli_num_rows($r2)>0){
			while($rw2 = mysqli_fetch_assoc($r2)){
				$data[] = $rw2;
			}
		}
		$output = array("infocode" => "PARTYLOCATIONUPDATED", "message" => "Customer Location details updated succesfully", "location_data" => $data);
	}
	else {
		$output = array("infocode" => "UPDATEPARTYLOCATIONFAILED", "message" => "Unable to update Customer Location details, please try again!");
	}
	
	return $output;
}

function add_department(){
	global $dbc;
	$party_master_id = mysqli_real_escape_string($dbc,trim($_POST['dept_party_master_id']));
	$party_location_id = mysqli_real_escape_string($dbc,trim($_POST['dept_party_location_id']));
	$department_name = mysqli_real_escape_string($dbc,trim($_POST['department_name']));
	$contact_person = mysqli_real_escape_string($dbc,trim($_POST['contact_person']));
	$contact_number = mysqli_real_escape_string($dbc,trim($_POST['contact_number']));
	
	$query = "SELECT * FROM department WHERE department_name = '$department_name' AND party_location_id = $party_location_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "DEPARTMENTEXISTS", "message" => "This Department name is already chosen, please choose a different name");
	}else{
		$query = "INSERT INTO department (party_master_id, party_location_id, department_name, contact_person, contact_number) VALUES('$party_master_id','$party_location_id','$department_name','$contact_person','$contact_number')";
		if(mysqli_query($dbc,$query)){
			$q2 = "SELECT * FROM department WHERE party_location_id = $party_location_id";
			$data = array();
			$r2 = mysqli_query($dbc, $q2);
			if(mysqli_num_rows($r2)>0){
				while($rw2 = mysqli_fetch_assoc($r2)){
					$data[] = $rw2;
				}
			}
			$output = array("infocode" => "DEPARTMENTADDED", "message" => "Department added succesfully", "dept_data" => $data);
		}
		else {
			$output = array("infocode" => "ADDDEPARTMENTFAILED", "message" => "Unable to add Department, please try again!");
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

function display_department(){
	global $dbc;
    $flag = true;
    $party_location_id = mysqli_real_escape_string($dbc, trim($_POST['dept_party_location_id']));
    
	$q2 = "SELECT * FROM department WHERE party_location_id = $party_location_id";
	$data = array();
	$r2 = mysqli_query($dbc, $q2);
	if(mysqli_num_rows($r2)>0){
		while($rw2 = mysqli_fetch_assoc($r2)){
			$data[] = $rw2;
		}
		$output = array("infocode" => "DEPTLISTSUCCESS", "message" => "Department list fetched succesfully", "dept_data" => $data);
	}else{
		$output = array("infocode" => "NODEPT", "message" => "No department added in this location", "dept_data" => 0);
	}
	
	return $output;
}
function delete_location() {
    global $dbc;
    $flag = true;
    $party_location_id = mysqli_real_escape_string($dbc, trim($_POST['party_location_id']));
    $party_master_id = mysqli_real_escape_string($dbc,trim($_POST['h_party_master_id']));
    
    //$query = "DELETE FROM party_location WHERE party_location_id=$party_location_id";
    $query = "UPDATE party_location SET is_deleted = 'yes' WHERE party_location_id=$party_location_id";
	if(mysqli_query($dbc,$query)){
		$q2 = "SELECT a.*,b.employee_name as sc_name FROM party_location a, employee_master b WHERE a.party_master_id = $party_master_id AND a.sc_id = b.employee_master_id";
		$data = array();
		$r2 = mysqli_query($dbc, $q2);
		if(mysqli_num_rows($r2)>0){
			while($rw2 = mysqli_fetch_assoc($r2)){
				$data[] = $rw2;
			}
		}
		$output = array("infocode" => "LOCATIONDELETED", "message" => "Location details deleted succesfully", "location_data" => $data);
	}else{
		$output = array("infocode" => "LOCATIONDELETEFAILED", "message" => "Error occurred while deleting Location Details");
	}
	
	return $output;
}