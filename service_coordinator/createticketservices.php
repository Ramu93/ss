<?php session_start();
require('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
define('TICKET_CREATE_STATUS','created'); 
define('TICKET_ASSIGN_STATUS','assigned'); 
define('TICKET_ENGG_CLOSE_STATUS','engineerclosed'); 
define('TICKET_SC_CLOSE_STATUS','closed'); 
define('TICKET_SPARES_PENDING_STATUS','sparespending'); 
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
	case 'sc_close_ticket':
        $finaloutput = sc_close_ticket();
    break;
	case 'loaddata_unassigned':
    	$finaloutput = loaddata_unassigned();
    break;
    case 'loaddata_assigned':
    	$finaloutput = loaddata_assigned();
    break;
    case 'loaddata_engineerclosed':
        $finaloutput = loaddata_engineerclosed();
    break;
    case 'loaddata_sparespending':
        $finaloutput = loaddata_sparespending();
    break;
    case 'request_spare':
    	$finaloutput = request_spare();
    break;
    case 'refresh_ticket_list':
    	$finaloutput = refresh_ticket_list();
    break;
    case 'fetch_material_stock':
    	$finaloutput = fetch_material_stock();
    break;
    case 'approve_reject_spare':
    	$finaloutput = approve_reject_spare();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function add_ticket(){
	global $dbc;
	$client_name = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
	//$client_id = 1;
	$location_name = mysqli_real_escape_string($dbc,trim($_POST['party_location_id']));
	$department_id = mysqli_real_escape_string($dbc,trim($_POST['department_id']));
	$product = mysqli_real_escape_string($dbc,trim($_POST['product_master_id']));
	$complaint_name = mysqli_real_escape_string($dbc,trim($_POST['complaint_master_id']));
	$ticket_status = TICKET_CREATE_STATUS;
	$additional_details = mysqli_real_escape_string($dbc,trim($_POST['additional_details']));
	$machine_reading = (trim($_POST['machine_reading'])!='')?mysqli_real_escape_string($dbc,trim($_POST['machine_reading'])):0;
	$sc_id = $_SESSION['userid'];
	if(isset($_POST['call_mode']) && $_POST['call_mode'] == 'from_ticket'){
		$call_id = mysqli_real_escape_string($dbc,trim($_POST['call_id']));
		$query = "INSERT INTO  raise_ticket (client_id,location_id,department_id,asset_master_id,complaint_id,ticket_status,additional_details,machine_reading,inbound_call_id,sc_id) 
		VALUES ('$client_name', '$location_name', '$department_id', '$product','$complaint_name','$ticket_status','$additional_details',$machine_reading,$call_id,$sc_id)";
	}else{
		$query = "INSERT INTO  raise_ticket (client_id,location_id,department_id,asset_master_id,complaint_id,ticket_status,additional_details,machine_reading,sc_id) 
		VALUES ('$client_name', '$location_name', '$department_id', '$product','$complaint_name','$ticket_status','$additional_details',$machine_reading,$sc_id)";
	}
	if(mysqli_query($dbc,$query)){
		$ticket_id = mysqli_insert_id($dbc);
		if(isset($_POST['call_mode']) && $_POST['call_mode'] == 'from_ticket'){
			$call_id = mysqli_real_escape_string($dbc,trim($_POST['call_id']));
			$q2 = "UPDATE inbound_call SET call_status = 'ticketcreated' WHERE inbound_call_id = $call_id";
			if(!mysqli_query($dbc, $q2)){
				file_put_contents("callerr.log","\nError while updating call status while creating ticket. CallID : $call_id , TicketID : $ticket_id", FILE_APPEND | LOCK_EX);
			}
		}
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
	$visit_date = mysqli_real_escape_string($dbc,trim($_POST['visit_date']));
	$ticket_status = TICKET_ASSIGN_STATUS;
	$engineer_name = '';
	$query = "UPDATE raise_ticket SET engineer_id='$engineer_id', ticket_status = '$ticket_status', visit_date = '$visit_date' WHERE ticket_id = $ticket_id";
	if(mysqli_query($dbc,$query)){
		$q2 = "SELECT employee_name FROM employee_master WHERE employee_master_id = $engineer_id";
		$r2 = mysqli_query($dbc, $q2);
		if(mysqli_num_rows($r2)>0){
			$rw2= mysqli_fetch_assoc($r2);
			$engineer_name = $rw2['employee_name'];
		}
		$output = array("infocode" => "TICKETUPDATED", "message" => "Engineer assigned succesfully" ,"engineer_name" => $engineer_name);
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

function request_spare(){
	global $dbc;
	$spare_data = array();
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$raised_by = 'SC';
	$sc_id = $_SESSION['userid'];
	$spare_status = 'StoresPending';
	$errcount = 0;
	foreach ($_POST['spare_name'] as $key => $value) {
		$spare_id = mysqli_real_escape_string($dbc,trim($value));
		$qty = mysqli_real_escape_string($dbc,trim($_POST['quantity'][$key]));

		$query = "INSERT INTO spare_request (ticket_id, item_master_id, quantity, spare_status, sc_id, raised_by) 
		VALUES ($ticket_id, $spare_id, $qty, '$spare_status', $sc_id, '$raised_by')";
		if(!mysqli_query($dbc, $query)){
			$errcount++;
			file_put_contents("spareerr.log","\nError while requesting spare for $ticket_id", FILE_APPEND | LOCK_EX);
		}
	}
	$query2 = "SELECT  a.item_name, b.quantity, b.raised_by, b.spare_remark, b.spare_status, b.issued_quantity, b.replaced_quantity, b.spare_request_id
			FROM item_master a, spare_request b
			WHERE b.ticket_id = $ticket_id AND a.item_master_id = b.item_master_id";
	$result2 = mysqli_query($dbc,$query2);
	if(mysqli_num_rows($result2)>0){
		while($row2 = mysqli_fetch_assoc($result2)){
			$spare_data[] = $row2;
		}
	}
	if($errcount){
		$output = array("infocode" => "SPAREREQERROR", "message" => "Error while requesting spares, please try again");
	}else{
		$output = array("infocode" => "SPAREREQSUCCESS", "message" => "Spare Request raised succesfully", "spare_data" => $spare_data);
	}
	
	return $output;
}

function loaddata_unassigned(){
	global $dbc; //sleep(2);
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));

	$asset_history = asset_historical_data($ticket_id);
	
	$output = array("infocode" => "DATALOADED", "message" => "UnAssigned Data loaded succesfully", "asset_history" => $asset_history);
	
	return $output;
}

function loaddata_assigned(){
	global $dbc; //sleep(2);
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$engineer_id = $engineer_name = '';$spare_data = array();
	$query = "SELECT a.employee_master_id, a.employee_name, b.visit_date FROM employee_master a, raise_ticket b WHERE b.ticket_id = $ticket_id AND a.employee_master_id = b.engineer_id";
	$result = mysqli_query($dbc,$query);
	if(mysqli_num_rows($result)>0){
		$row  = mysqli_fetch_assoc($result);
		$engineer_data = $row;
	}

	$query2 = "SELECT  a.item_name, b.quantity, b.raised_by, b.spare_remark, b.spare_status, b.issued_quantity, b.replaced_quantity, b.spare_request_id
			FROM item_master a, spare_request b
			WHERE b.ticket_id = $ticket_id AND a.item_master_id = b.item_master_id";
	$result2 = mysqli_query($dbc,$query2);
	if(mysqli_num_rows($result2)>0){
		while($row2 = mysqli_fetch_assoc($result2)){
			$spare_data[] = $row2;
		}
	}
	$asset_history = asset_historical_data($ticket_id);
	
	$output = array("infocode" => "DATALOADED", "message" => "Assigned Data loaded succesfully", "engineer_data" => $engineer_data, "spare_data" => $spare_data, "asset_history" => $asset_history);
	
	return $output;
}

function loaddata_engineerclosed(){
	global $dbc;
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$spare_data = $call_report = array();
	
	$query = "SELECT * FROM call_report WHERE ticket_id = $ticket_id ";
	$result = mysqli_query($dbc,$query);
	if(mysqli_num_rows($result)>0){
		$row  = mysqli_fetch_assoc($result);
		$call_report = $row;
	}

	$query2 = "SELECT  a.item_name, b.quantity, b.raised_by, b.spare_remark, b.spare_status, b.issued_quantity, b.replaced_quantity, b.spare_request_id
			FROM item_master a, spare_request b
			WHERE b.ticket_id = $ticket_id AND a.item_master_id = b.item_master_id";
	$result2 = mysqli_query($dbc,$query2);
	if(mysqli_num_rows($result2)>0){
		while($row2 = mysqli_fetch_assoc($result2)){
			$spare_data[] = $row2;
		}
	}
	$asset_history = asset_historical_data($ticket_id);
	
	$output = array("infocode" => "DATALOADED", "message" => "Assigned Data loaded succesfully", "spare_data" => $spare_data, "call_report" => $call_report, "asset_history" => $asset_history);
	
	return $output;
}

function loaddata_sparespending(){
	global $dbc;
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$spare_data = $call_report = $engineer_data = array();
	
	$query = "SELECT * FROM call_report WHERE ticket_id = $ticket_id ";
	$result = mysqli_query($dbc,$query);
	if(mysqli_num_rows($result)>0){
		$row  = mysqli_fetch_assoc($result);
		$call_report = $row;
	}

	$query2 = "SELECT  a.item_name, b.quantity, b.raised_by, b.spare_remark, b.spare_status, b.issued_quantity, b.replaced_quantity, b.spare_request_id
			FROM item_master a, spare_request b
			WHERE b.ticket_id = $ticket_id AND a.item_master_id = b.item_master_id";
	$result2 = mysqli_query($dbc,$query2);
	if(mysqli_num_rows($result2)>0){
		while($row2 = mysqli_fetch_assoc($result2)){
			$spare_data[] = $row2;
		}
	}

	$query3 = "SELECT a.employee_master_id, a.employee_name, b.visit_date FROM employee_master a, raise_ticket b WHERE b.ticket_id = $ticket_id AND a.employee_master_id = b.engineer_id";
	$result3 = mysqli_query($dbc,$query3);
	if(mysqli_num_rows($result3)>0){
		$row3  = mysqli_fetch_assoc($result3);
		$engineer_data = $row3;
	}

	$asset_history = asset_historical_data($ticket_id);
	
	$output = array("infocode" => "DATALOADED", "message" => "Assigned Data loaded succesfully", "spare_data" => $spare_data, "call_report" => $call_report, "engineer_data" => $engineer_data, "asset_history" => $asset_history);
	
	return $output;
}

function asset_historical_data($ticket_id){
	global $dbc; $ticket_data = array();
	$query = "SELECT a.ticket_id,a.ticket_status, a.remarks, a.machine_reading, a.visit_date, a.creation_date,  f.complaint_name, g.employee_name as sc_name, h.employee_name as engr_name
		FROM raise_ticket a, nature_of_comp f, employee_master g, employee_master h
		WHERE  a.complaint_id = f.NCMPLTID AND a.sc_id = g.employee_master_id AND a.engineer_id = h.employee_master_id AND ticket_id != $ticket_id AND a.asset_master_id = (SELECT asset_master_id FROM raise_ticket WHERE ticket_id = $ticket_id)";
	$result = mysqli_query($dbc,$query);
	if(mysqli_num_rows($result)>0){
		while($row  = mysqli_fetch_assoc($result)){
			$ticket_data[$row['ticket_id']] = $row;
		}
	}
	//file_put_contents("assethistory.log", "$query\n".print_r($ticket_data, true), FILE_APPEND | LOCK_EX);
	if(count($ticket_data)){
		$ticket_id_list = implode(',', array_keys($ticket_data));
		$query2 = "SELECT a.*, b.item_name
					FROM  spare_request a, item_master b
					WHERE  a.ticket_id IN ($ticket_id_list) AND a.item_master_id = b.item_master_id";
		$result2 = mysqli_query($dbc,$query2);
		if(mysqli_num_rows($result2)>0){
			while($row2  = mysqli_fetch_assoc($result2)){
				$ticket_data[$row2['ticket_id']]['spare_data'][$row2['spare_request_id']] = $row2;
			}
		}

		$query3 = "SELECT a.*, b.employee_name
					FROM  call_report a, employee_master b
					WHERE  a.ticket_id IN ($ticket_id_list) AND a.engineer_id = b.employee_master_id";
		$result3 = mysqli_query($dbc,$query3);
		if(mysqli_num_rows($result3)>0){
			while($row3  = mysqli_fetch_assoc($result3)){
				$ticket_data[$row3['ticket_id']]['call_report'][$row3['call_report_id']] = $row3;
			}
		}
		//file_put_contents("assethistory.log", "\n".print_r($ticket_data, true), FILE_APPEND | LOCK_EX);
		return $ticket_data;
	}else{
		return array();
	}
}

function refresh_ticket_list(){
	global $dbc; $ticket_list_data = array();
	$userid = $_SESSION['userid'];
	$ticket_status = mysqli_real_escape_string($dbc,trim($_POST['ticket_status']));
	if($ticket_status == 'assigned'){
		$pending_days = mysqli_real_escape_string($dbc,trim($_POST['pending_days']));
		$query = "SELECT a.* , b.customer_name, c.location_name, e.item_name, f.complaint_name
                    FROM raise_ticket a, party_master b, party_location c, asset_master d, item_master e, nature_of_comp f
                    WHERE a.ticket_status = '$ticket_status' AND a.client_id = b.party_master_id AND a.location_id = c.party_location_id AND a.asset_master_id = d.asset_master_id 
                    AND d.item_master_id = e.item_master_id AND a.complaint_id = f.NCMPLTID AND a.sc_id = $userid AND DATE(creation_date) <= DATE(CURDATE() - $pending_days)";
	}else{
		$from_date = mysqli_real_escape_string($dbc,trim($_POST['from_date']));
		$to_date = mysqli_real_escape_string($dbc,trim($_POST['to_date']));
		$query = "SELECT a.* , b.customer_name, c.location_name, e.item_name, f.complaint_name
                    FROM raise_ticket a, party_master b, party_location c, asset_master d, item_master e, nature_of_comp f
                    WHERE a.ticket_status = '$ticket_status' AND a.client_id = b.party_master_id AND a.location_id = c.party_location_id AND a.asset_master_id = d.asset_master_id 
                    AND d.item_master_id = e.item_master_id AND a.complaint_id = f.NCMPLTID AND a.sc_id = $userid AND DATE(creation_date) BETWEEN '$from_date' AND '$to_date'";
	}
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_assoc($result)){
			$ticket_list_data[] = $row;
		}
	}
	$output = array("infocode" => "DATALOADED", "message" => "Ticket List loaded succesfully", "ticket_list_data" => $ticket_list_data);
	return $output;
}

function fetch_material_stock(){
	global $dbc; $ticket_list_data = array();
	$engineer_stock = $store_stock = 0;
	$userid = $_SESSION['userid'];
	$item_master_id = mysqli_real_escape_string($dbc,trim($_POST['item_master_id']));
	$engineer_id = mysqli_real_escape_string($dbc,trim($_POST['engineer_id']));
	
	$query = "SELECT * FROM engineer_stock WHERE engineer_id = $engineer_id AND item_master_id = $item_master_id";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		$engineer_stock = $row['current_stock'];
	}

	$query2 = "SELECT * FROM store_material_stock WHERE item_master_id = $item_master_id";
	$result2 = mysqli_query($dbc, $query2);
	if(mysqli_num_rows($result2)>0){
		$row2 = mysqli_fetch_assoc($result2);
		$store_stock = $row2['current_stock'];
	}
	$output = array("infocode" => "DATALOADED", "message" => "Material stock data loaded succesfully", "engineer_stock" => $engineer_stock, "store_stock" => $store_stock);
	return $output;
}

function approve_reject_spare(){
	global $dbc;
	$spare_data = array();
	$ticket_id = mysqli_real_escape_string($dbc,trim($_POST['ticket_id']));
	$spare_request_id = mysqli_real_escape_string($dbc,trim($_POST['spare_request_id']));
	$spare_status = mysqli_real_escape_string($dbc,trim($_POST['spare_status']));;
	$errcount = 0;
	
	$query = "UPDATE spare_request SET spare_status = '$spare_status' WHERE spare_request_id = $spare_request_id";
	if(!mysqli_query($dbc, $query)){
		$errcount++;
		file_put_contents("spareerr.log","\nError while updating spare status for $ticket_id, status $spare_status", FILE_APPEND | LOCK_EX);
	}
	
	$query2 = "SELECT  a.item_name, b.quantity, b.raised_by, b.spare_remark, b.spare_status, b.issued_quantity, b.replaced_quantity, b.spare_request_id
			FROM item_master a, spare_request b
			WHERE b.ticket_id = $ticket_id AND a.item_master_id = b.item_master_id";
	$result2 = mysqli_query($dbc,$query2);
	if(mysqli_num_rows($result2)>0){
		while($row2 = mysqli_fetch_assoc($result2)){
			$spare_data[] = $row2;
		}
	}
	if($errcount){
		$output = array("infocode" => "SPAREREQERROR", "message" => "Error while requesting spares, please try again");
	}else{
		$output = array("infocode" => "APRROVEREJECTSUCCESS", "message" => "Spare Request raised succesfully", "spare_data" => $spare_data);
	}
	
	return $output;
}
