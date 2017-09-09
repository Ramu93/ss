<?php
//session_start();
header('Access-Control-Allow-Origin: *');
require("dbconfig.php");
//format : action=device_login&username=12312&password=123&deviceid=123123
$timelimit = 7200; //2 hours
//$postdata = file_get_contents("php://input");
$postdata = json_decode(file_get_contents('php://input'), true);
//$input = isset($_POST)?$_POST:$_GET;
$input = $postdata;
$finaloutput = array();
//$action = isset($input['action'])?$input['action']:'';
//file_put_contents("ticketservice_stop.txt", "\nTicketStop : POST\n".print_r($_POST, true), FILE_APPEND | LOCK_EX);
    //file_put_contents("ticketservice_stop.log", "\nTicketStop : GET\n".print_r($_GET, true), FILE_APPEND | LOCK_EX);
file_put_contents("ticketservice_stop.txt", "\nTicketStop : RAW_POST\n".print_r($postdata, true), FILE_APPEND | LOCK_EX);
$action = $postdata['action'];

$finaloutput = array('infocode' => 'ATTENDTICKETSTOPSUCCESS', 'message' => 'Attend ticket has been succesfully stopped');

switch($action){
	case 'attend_ticket':
        $finaloutput = attend_ticket();
    break;
    case 'view_open_ticket':
        $finaloutput = view_open_ticket();
    break;
    case 'attend_ticket_start':
        $finaloutput = attend_ticket_start();
    break;
    case 'attend_ticket_stop':
        $finaloutput = attend_ticket_stop();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function attend_ticket(){
    global $dbc,$input;
    $output = $data = array();
    
    file_put_contents("ticketservice.log", "\n".print_r($input, true), FILE_APPEND | LOCK_EX);
    if($input['mode'] == 'start'){
        $ticket_id = isset($input['ticket_id'])?$input['ticket_id']:1;
        $engineer_id = 1;
        $status = 'start';
        $query = "INSERT INTO ticket_log (ticket_id, engineer_id, status) VALUES ($ticket_id, $engineer_id, '$status')";
        if(mysqli_query($dbc, $query)){
            $output = array('infocode' => 'ATTENDTICKETSTARTSUCCESS', 'message' => 'Attend ticket has been succesfully started');
        }else{
            $output = array('infocode' => 'ATTENDTICKETSTARTFAILED', 'message' => 'Unable to start Attend Ticket, please try again');
        }
    }else{
        $ticket_id = isset($input['ticket_id'])?$input['ticket_id']:1;
        $engineer_id = 1;
        $status = 'stop';
        $query = "INSERT INTO ticket_log (ticket_id, engineer_id, status) VALUES ($ticket_id, $engineer_id, '$status')";
        if(mysqli_query($dbc, $query)){
            $output = array('infocode' => 'ATTENDTICKETSTOPSUCCESS', 'message' => 'Attend ticket has been succesfully stopped');
        }else{
            $output = array('infocode' => 'ATTENDTICKETSTOPFAILED', 'message' => 'Unable to stop Attend Ticket, please try again');
        }
    }
    return $output;
}

function view_open_ticket(){
    global $dbc,$input;
    $output = $data = array();
    $engr_id = isset($input['userid'])?$input['userid']:3;
    //file_put_contents("ticketservice.log", "\n".print_r($input, true), FILE_APPEND | LOCK_EX);
    $query = "SELECT a.* , b.customer_name, c.location_name, e.item_name, f.complaint_name
                FROM raise_ticket a, party_master b, party_location c, asset_master d, item_master e, nature_of_comp f, department g
                WHERE a.ticket_status IN ('assigned', 'sparespending') AND a.engineer_id = $engr_id AND a.client_id = b.party_master_id AND a.location_id = c.party_location_id 
                AND a.asset_master_id = d.asset_master_id AND d.item_master_id = e.item_master_id AND a.department_id = g.department_id AND a.complaint_id = f.NCMPLTID";
    $result = mysqli_query($dbc,$query);
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        $output = array('infocode' => 'TICKETLISTFETCHED', 'message' => 'Ticket List Fetched', 'ticket_list' => $data);
    }else{
        $output = array('infocode' => 'NOOPENTICKETS', 'message' => 'No Open Tickets to be resolved');
    }
    return $output;
}

function attend_ticket_start(){
    global $dbc,$input;
    $output = $data = array();
    $input['mode'] = 'start';
    file_put_contents("ticketservice.log", "\nTicketStart\n".print_r($input, true), FILE_APPEND | LOCK_EX);
    if($input['mode'] == 'start'){
        $ticket_id = isset($input['ticket_id'])?$input['ticket_id']:1;
        $engineer_id = 1;
        $status = 'start';
        $query = "INSERT INTO ticket_log (ticket_id, engineer_id, status) VALUES ($ticket_id, $engineer_id, '$status')";
        if(mysqli_query($dbc, $query)){
            $output = array('infocode' => 'ATTENDTICKETSTARTSUCCESS', 'message' => 'Attend ticket has been succesfully started');
        }else{
            $output = array('infocode' => 'ATTENDTICKETSTARTFAILED', 'message' => 'Unable to start Attend Ticket, please try again');
        }
    }else{
        $ticket_id = isset($input['ticket_id'])?$input['ticket_id']:1;
        $engineer_id = 1;
        $status = 'stop';
        $query = "INSERT INTO ticket_log (ticket_id, engineer_id, status) VALUES ($ticket_id, $engineer_id, '$status')";
        if(mysqli_query($dbc, $query)){
            $output = array('infocode' => 'ATTENDTICKETSTOPSUCCESS', 'message' => 'Attend ticket has been succesfully stopped');
        }else{
            $output = array('infocode' => 'ATTENDTICKETSTOPFAILED', 'message' => 'Unable to stop Attend Ticket, please try again');
        }
    }
    return $output;
}

function attend_ticket_stop(){
    global $dbc,$input;
    $output = $data = array();
    $input['mode'] = 'stop';
    $spare_details = $input['sparedetails'];
    $closing_reading = mysqli_real_escape_string($dbc,trim($input['closing_reading']));
    $remarks = mysqli_real_escape_string($dbc,trim($input['remarks']));
    $stop_latitude = mysqli_real_escape_string($dbc,trim($input['lattitude']));
    $stop_longitude = mysqli_real_escape_string($dbc,trim($input['longitude']));
    $ticket_status = strtolower(mysqli_real_escape_string($dbc,trim($input['ticket_status'])));
    $ticket_id = $input['ticketId'];
    $q2 = "SELECT engineer_id FROM raise_ticket WHERE ticket_id = $ticket_id";
    $r2 = mysqli_query($dbc, $q2);
    $rw2 = mysqli_fetch_assoc($r2);
    $engineer_id = $rw2['engineer_id'];
    $status = 'stop';
    $end_time = date('H:i:s');
    $flag = true;
    $query3 = "UPDATE call_report SET closing_reading = '$closing_reading', remarks = '$remarks', stop_latitude = '$stop_latitude', stop_longitude = '$stop_longitude', end_time = '$end_time'
        WHERE ticket_id = $ticket_id AND engineer_id = $engineer_id";

    if(!mysqli_query($dbc, $query3)){
        $flag = false;
        file_put_contents("ticketerr.log","\nUnable to enter end call report for Ticket ID : $ticket_id \n$query3", FILE_APPEND | LOCK_EX);   
    }

    foreach ($spare_details as $key => $value) {
        $replaced_quantity = 0;
        $item_master_id = $value['spare_id'];
        $spare_status = strtolower($value['spare_status']);
        if($spare_status == 'replaced')
            $replaced_quantity = $value['spare_count'];
        $quantity = $value['spare_count'];
        $query4 = "INSERT INTO spare_request (ticket_id, item_master_id, spare_status, quantity, replaced_quantity)
            VALUES ($ticket_id, $item_master_id, '$spare_status', $quantity, $replaced_quantity)";
        if(!mysqli_query($dbc, $query4)){
            $flag = false;
            file_put_contents("ticketerr.log","\nUnable to enter spare details of Ticket ID : $ticket_id, item_id : $item_master_id \n$query4", FILE_APPEND | LOCK_EX);   
        }
    }

    //Update raise_ticket
    $ticket_status2 = ($ticket_status == 'completed')?'engineerclosed':'sparespending';
    $query5 = "UPDATE raise_ticket SET ticket_status = '$ticket_status2' WHERE ticket_id = $ticket_id";
    if(!mysqli_query($dbc, $query5)){
        $flag = false;
        file_put_contents("ticketerr.log","\nUnable to update ticket details of Ticket ID : $ticket_id, status : $ticket_status2 \n$query4", FILE_APPEND | LOCK_EX);   
    }

    $query = "INSERT INTO ticket_log (ticket_id, engineer_id, status) VALUES ($ticket_id, $engineer_id, '$status')";
    if(!mysqli_query($dbc, $query)){
        file_put_contents("ticketerr.log","\nUnable to enter in ticket log for Ticket ID : $ticket_id", FILE_APPEND | LOCK_EX);
        //$output = array('infocode' => 'ATTENDTICKETSTOPSUCCESS', 'message' => 'Attend ticket has been succesfully stopped');
    }

    if($flag){
        $output = array('infocode' => 'ATTENDTICKETSTOPSUCCESS', 'message' => 'Attend ticket has been succesfully stopped');
    }else{
        $output = array('infocode' => 'ATTENDTICKETSTOPFAILED', 'message' => 'Unable to stop Attend Ticket, please try again');
    }
   
    return $output;
}

function writeerrorlog($text){
    $filename = "siteerrlog.log";
    $fp =fopen($filename,"a");
    fwrite($fp, $text);
    fclose($fp);
}

?>