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
    case 'raise_despatch_request':
        $finaloutput = raise_despatch_request();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function attend_ticket(){
    global $dbc,$input;
    /*$output = $data = array();
    $query = "SELECT csr_id, remaining_units, DATE(bill_start_time) as created_date FROM csr WHERE party_master_id = {$input['customer_id']} AND status IN ('created','ongoing','approved','pending') LIMIT 10";
    $result = mysqli_query($dbc, $query);
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        $output = array('infocode' => 'STOCKLISTFETCHED', 'message' => 'Stock List fetched successfully', 'stock_list' => $data );
    }else{
        $output=array('infocode' => 'STOCKLISTEMPTY', 'message' => 'No active stock available, please go to Stock History to check older stocks');
    }*/
    file_put_contents("ticketservice.log", "\n".print_r($input, true), FILE_APPEND | LOCK_EX);
    if($input['mode'] == 'start')
        $output = array('infocode' => 'ATTENDTICKETSTARTSUCCESS', 'message' => 'Attend ticket has been succesfully started');
    else
        $output = array('infocode' => 'ATTENDTICKETSTOPSUCCESS', 'message' => 'Attend ticket has been succesfully stopped');
    return $output;
}


function writeerrorlog($text){
    $filename = "siteerrlog.log";
    $fp =fopen($filename,"a");
    fwrite($fp, $text);
    fclose($fp);
}

?>