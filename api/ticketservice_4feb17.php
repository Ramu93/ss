<?php
//session_start();
header('Access-Control-Allow-Origin: *');
require("dbconfig.php");
//format : action=device_login&username=12312&password=123&deviceid=123123
$timelimit = 7200; //2 hours

//$input = isset($_POST)?$_POST:$_GET;
$input = $_GET;
$finaloutput = array();
$action = isset($input['action'])?$input['action']:'';
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
    file_put_contents("ticketservice.log", "\nTicketStop\n".print_r($input, true), FILE_APPEND | LOCK_EX);
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

function writeerrorlog($text){
    $filename = "siteerrlog.log";
    $fp =fopen($filename,"a");
    fwrite($fp, $text);
    fclose($fp);
}

?>