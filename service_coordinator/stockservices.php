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
	case 'fetch_engineer_stock':
    	$finaloutput = fetch_engineer_stock();
    break;
    case 'transfer_material':
        $finaloutput = transfer_material();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function fetch_engineer_stock(){
    global $dbc;
    $engineer_id = mysqli_real_escape_string($dbc,trim($_POST['engineer_id']));
    $engineer_data = $engineer_list = array();
    $query = "SELECT a.*, b.item_name FROM engineer_stock a, item_master b WHERE a.engineer_id = $engineer_id AND a.item_master_id = b.item_master_id";
    $result = mysqli_query($dbc,$query);
    if(mysqli_num_rows($result)>0){
        while($row  = mysqli_fetch_assoc($result)){
            $engineer_data[] = $row;
        }
        $query2 = "SELECT * FROM employee_master WHERE rolename='engr' AND employee_master_id != $engineer_id";
        $result2 = mysqli_query($dbc, $query2);
        if(mysqli_num_rows($result2)>0){
            while($row2  = mysqli_fetch_assoc($result2)){
                $engineer_list[] = $row2;
            }
        }
        $output = array("infocode" => "DATALOADED", "message" => "Engineer Data loaded succesfully", "engineer_data" => $engineer_data, "engineer_list" => $engineer_list);
    }else{
        $output = array("infocode" => "NODATA", "message" => "No Engineer stock data available", "engineer_data" => array(), "engineer_list" => $engineer_list);
    }
    return $output;
}

function transfer_material(){
    global $dbc; 
    
    $issued_by = $_SESSION['userid'];
    $item_master_id = mysqli_real_escape_string($dbc,trim($_POST['item_master_id']));
    $from_engineer_id = mysqli_real_escape_string($dbc,trim($_POST['from_engineer_id']));
    $to_engineer_id = mysqli_real_escape_string($dbc,trim($_POST['to_engineer_id']));
    $transfer_qty = mysqli_real_escape_string($dbc,trim($_POST['transfer_qty']));
                
    $update_flag = $update_flag2 = true;       
    $query5 = "SELECT * FROM engineer_stock WHERE engineer_id = $to_engineer_id AND item_master_id = $item_master_id";
    $result5 = mysqli_query($dbc, $query5);
    if(mysqli_num_rows($result5)>0){
        $query4 = "UPDATE engineer_stock SET current_stock = current_stock + $transfer_qty WHERE engineer_id = $to_engineer_id AND item_master_id = $item_master_id";
    }else{
        $query4 = "INSERT INTO engineer_stock (engineer_id, item_master_id, current_stock) VALUES ($to_engineer_id, $item_master_id, $transfer_qty)";
    }
    
    if(!mysqli_query($dbc, $query4)){
        $update_flag = false;
        file_put_contents("materialtransfererr.log", "\n Error while Transfer stock : Adding Engineer stock for Engineer : $to_engineer_id, for item_id : $item_master_id", FILE_APPEND | LOCK_EX);
    }

    if($update_flag){
        $query = "UPDATE engineer_stock SET current_stock = current_stock - $transfer_qty WHERE engineer_id = $from_engineer_id AND item_master_id = $item_master_id";
        if(!mysqli_query($dbc, $query)){
            $update_flag2 = false;
            file_put_contents("materialtransfererr.log", "\n Error while Transfer stock : Deducting Engineer stock for Engineer : $from_engineer_id, for item_id : $item_master_id", FILE_APPEND | LOCK_EX);
        }
    }
                
    if($update_flag && $update_flag2){   
        $output = array("infocode" => "TRANSFERMATERIALSUCCESS", "message" => "Material transferred succesfully!");
    }else{
        $output = array("infocode" => "TRANSFERMATERIALFAILED", "message" => "Unable to transfer material, please try again");
    }
    
    return $output;
}