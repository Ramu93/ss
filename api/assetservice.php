<?php
//session_start();
header('Access-Control-Allow-Origin: *');
require("dbconfig.php");
//format : action=device_login&username=12312&password=123&deviceid=123123
$timelimit = 7200; //2 hours
$item_types = "'SPARES','CS'";
//$input = isset($_POST)?$_POST:$_GET;
$input = $_GET;
$finaloutput = array();
$action = isset($input['action'])?$input['action']:'';
switch($action){
	case 'fetch_asset':
        $finaloutput = fetch_asset();
    break;
    case 'spare_list':
        $finaloutput = spare_list();
    break;
    case 'raise_despatch_request':
        $finaloutput = raise_despatch_request();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

/*function fetch_asset(){
    global $dbc,$input;
    $output = $data = array();
    if($input['fetch_type'] == 'tag'){
        $query = "SELECT a.rfid_tag, a.asset_master_id, b.item_name, a.item_master_id, c.customer_name, a.party_master_id, d.location_name, a.party_location_id, d.address, b.brand
        FROM asset_master a, item_master b, party_master c, party_location d 
        WHERE c.party_master_id = a.party_master_id AND d.party_location_id = a.party_location_id AND b.item_master_id = a.item_master_id AND a.rfid_tag = '{$input['tag_id']}'";
    }else{
        $query = "SELECT a.rfid_tag, a.asset_master_id, b.item_name, a.item_master_id, c.customer_name, a.party_master_id, d.location_name, a.party_location_id, d.address, b.brand
        FROM asset_master a, item_master b, party_master c, party_location d 
        WHERE c.party_master_id = a.party_master_id AND d.party_location_id = a.party_location_id AND b.item_master_id = a.item_master_id AND a.asset_master_id = '{$input['tag_id']}'";
    }
    $result = mysqli_query($dbc, $query);
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        $row['asset_id'] = $row['asset_master_id'];
        $row['ticket_id'] = 1;
        $row['ticket_status'] = 'assigned';
        $output = array('infocode' => 'ASSETDETAILSFETCHED', 'message' => 'Asset details fetched successfully', 'asset_data' => $row );
    }else{
        $output=array('infocode' => 'INVALIDASSETID', 'message' => 'Invalid Asset ID passed, please check and try again!');
    }
    //file_put_contents("assetservice.log", "\n".print_r($input, true), FILE_APPEND | LOCK_EX);
    $data = array("asset_id"=>"1", "item_name"=> "Canon 100D", "brand"=> "Canon", "customer_name" => "ABC Enterprises", "location_name" => "Mount road office", "address" => "No.10, Mount Road, Chennai","ticket_id"=>"123", "ticket_status"=>"assigned");
    $output = array('infocode' => 'ASSETDETAILSFETCHED', 'message' => 'Asset details fetched successfully', 'asset_data' => $data );//
    return $output;
}*/

function fetch_asset(){
    global $dbc,$input;
    $output = $data = array();
    
    $query = "SELECT a.* , b.customer_name, c.location_name, e.item_name, f.complaint_name, d.rfid_tag, d.asset_master_id
            FROM raise_ticket a, party_master b, party_location c, asset_master d, item_master e, nature_of_comp f, department g
            WHERE a.ticket_status IN ('assigned', 'sparespending') AND a.client_id = b.party_master_id AND a.location_id = c.party_location_id 
            AND a.asset_master_id = d.asset_master_id AND d.item_master_id = e.item_master_id AND a.department_id = g.department_id AND a.complaint_id = f.NCMPLTID
            AND d.rfid_tag = '{$input['tag_id']}'";
    
    $result = mysqli_query($dbc, $query);
    if(mysqli_num_rows($result)>0){
        $count = 0;
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
            $count++;
        }
        $output = array('infocode' => 'TICKETDETAILSFETCHED', 'message' => 'Ticket details fetched successfully', 'ticket_data' => $data, 'ticket_count' => $count );
    }else{
        $output=array('infocode' => 'NOOPENTICKET', 'message' => 'There is no open ticket for this asset!');
    }
    //file_put_contents("assetservice.log", "\n".print_r($input, true), FILE_APPEND | LOCK_EX);
    return $output;
}

function spare_list(){
    global $dbc,$input,$item_types;
    $output = $data = array();
    
    $query = "SELECT * FROM item_master WHERE item_type IN ($item_types)";
    $result = mysqli_query($dbc, $query);
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
            $output = array('infocode' => 'SPARELISTFETCHED', 'message' => 'Asset details fetched successfully', 'spare_data' => $data );
        }
    }else{
        $output=array('infocode' => 'NOSPARES', 'message' => 'No spares available!');
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