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
    case 'receive_material':
        $finaloutput = receive_material();
    break;
    case 'issue_material':
        $finaloutput = issue_material();
    break;
    case 'return_material':
        $finaloutput = return_material();
    break;
    case 'raise_purchase_indent':
        $finaloutput = raise_purchase_indent();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function fetch_engineer_stock(){
    global $dbc;
    $engineer_id = mysqli_real_escape_string($dbc,trim($_POST['engineer_id']));
    $spare_data = array();
    $query = "SELECT a.*, b.item_name FROM engineer_stock a, item_master b WHERE a.engineer_id = $engineer_id AND a.item_master_id = b.item_master_id";
    $result = mysqli_query($dbc,$query);
    if(mysqli_num_rows($result)>0){
        while($row  = mysqli_fetch_assoc($result)){
            $engineer_data[] = $row;
        }
        $output = array("infocode" => "DATALOADED", "message" => "Engineer Data loaded succesfully", "engineer_data" => $engineer_data);
    }else{
        $output = array("infocode" => "NODATA", "message" => "No Engineer stock data available", "engineer_data" => array());
    }
    return $output;
}

function receive_material(){
    global $dbc; $ierrcount = $uerrcount = 0;
    $mrn_type = mysqli_real_escape_string($dbc,trim($_POST['mrn_type']));
    if($mrn_type == 'internal'){
        file_put_contents("stockinwarderr.log", "\n Input array : ".print_r($_POST,true), FILE_APPEND | LOCK_EX);
        $engineer_id = mysqli_real_escape_string($dbc,trim($_POST['engineer_id']));
        if(isset($_POST['picked_list']) && count($_POST['picked_list'])){
            $query = "INSERT INTO store_material_inward (mrn_type, engineer_id) VALUES ('$mrn_type',$engineer_id)";
            if(mysqli_query($dbc, $query)){
                $store_material_inward_id = mysqli_insert_id($dbc);
                foreach ($_POST['picked_list'] as $key => $value) {
                    $insert_flag = $update_flag = true;
                    $item_master_id = mysqli_real_escape_string($dbc,trim($_POST['h_item_master_id_'.$value]));
                    $returned_qty = mysqli_real_escape_string($dbc,trim($_POST['returned_qty_'.$value]));
                    $query2 = "INSERT INTO store_material_inward_detail (store_material_inward_id, item_master_id, accepted_quantity)
                        VALUES ($store_material_inward_id, $item_master_id, $returned_qty)";
                    if(!mysqli_query($dbc, $query2)){
                        $ierrcount++;
                        $insert_flag = false;
                        file_put_contents("stockinwarderr.log", "\n Stock Inward error for Store material inward id : $store_material_inward_id, item_id : $item_master_id \n $query2", FILE_APPEND | LOCK_EX);
                    }
                    if($insert_flag){
                        $query3 = "UPDATE store_material_stock SET current_stock = current_stock + $returned_qty WHERE item_master_id = $item_master_id";
                        if(!mysqli_query($dbc, $query3)){
                            $uerrcount++;
                            $update_flag = false;
                            file_put_contents("stockinwarderr.log", "\n Error while updating Material stock for item_id : $item_master_id", FILE_APPEND | LOCK_EX);
                        }
                    }
                    if($insert_flag && $update_flag){
                        $query4 = "UPDATE engineer_stock SET current_stock = current_stock - $returned_qty WHERE engineer_id = $engineer_id AND item_master_id = $item_master_id";
                        if(!mysqli_query($dbc, $query4)){
                            $uerrcount++;
                            file_put_contents("stockinwarderr.log", "\n Error while updating Engineer stock for Engineer : $engineer_id, for item_id : $item_master_id", FILE_APPEND | LOCK_EX);
                        }
                    }
                }
                $output = array("infocode" => "RECEIVEMATERIALSUCCESS", "message" => "Material received succesfully!");
            }else{
                $output = array("infocode" => "RECEIVEMATERIALFAILED", "message" => "Unable to receive material, please try again");
            }
        }else{
            $output = array("infocode" => "NOMATERIALSELECTED", "message" => "Please select at least one material");
        }
    }

    return $output;
}

function issue_material(){
    global $dbc; $ierrcount = $uerrcount = 0;
    //$mrn_type = mysqli_real_escape_string($dbc,trim($_POST['mrn_type']));
    $issued_by = $_SESSION['userid'];
    
    if(count($_POST['issue_list'])){
        $query = "INSERT INTO store_material_issue (issued_by) VALUES ($issued_by)";
        if(mysqli_query($dbc, $query)){
            $store_material_issue_id = mysqli_insert_id($dbc);
            foreach ($_POST['issue_list'] as $key => $value) {
                $insert_flag = $update_flag = $update_flag2 = true;
                $item_master_id = mysqli_real_escape_string($dbc,trim($_POST['item_master_id_'.$value]));
                $engineer_id = mysqli_real_escape_string($dbc,trim($_POST['engineer_id_'.$value]));
                $issued_qty = mysqli_real_escape_string($dbc,trim($_POST['issued_qty_'.$value]));
                $query2 = "INSERT INTO store_material_issue_detail (store_material_issue_id, engineer_id, item_master_id, issued_quantity)
                    VALUES ($store_material_issue_id, $engineer_id, $item_master_id, $issued_qty)";
                if(!mysqli_query($dbc, $query2)){
                    $ierrcount++;
                    $insert_flag = false;
                    file_put_contents("materialissueerr.log", "\n Stock Inward error for Store material inward id : $store_material_inward_id, item_id : $item_master_id \n $query2", FILE_APPEND | LOCK_EX);
                }
                if($insert_flag){
                    $query3 = "UPDATE store_material_stock SET current_stock = current_stock - $issued_qty WHERE item_master_id = $item_master_id";
                    if(!mysqli_query($dbc, $query3)){
                        $uerrcount++;
                        $update_flag = false;
                        file_put_contents("materialissueerr.log", "\n Error while updating Material stock for item_id : $item_master_id", FILE_APPEND | LOCK_EX);
                    }
                }
                if($insert_flag && $update_flag){
                    $query5 = "SELECT * FROM engineer_stock WHERE engineer_id = $engineer_id AND item_master_id = $item_master_id";
                    $result5 = mysqli_query($dbc, $query5);
                    if(mysqli_num_rows($result5)>0){
                        $query4 = "UPDATE engineer_stock SET current_stock = current_stock + $issued_qty WHERE engineer_id = $engineer_id AND item_master_id = $item_master_id";
                    }else{
                        $query4 = "INSERT INTO engineer_stock (engineer_id, item_master_id, current_stock) VALUES ($engineer_id, $item_master_id, $issued_qty)";
                    }
                    
                    if(!mysqli_query($dbc, $query4)){
                        $uerrcount++;
                        $update_flag2 = false;
                        file_put_contents("materialissueerr.log", "\n Error while updating Engineer stock for Engineer : $engineer_id, for item_id : $item_master_id", FILE_APPEND | LOCK_EX);
                    }
                }
            }
            $output = array("infocode" => "ISSUEMATERIALSUCCESS", "message" => "Material issued succesfully!");
        }else{
            $output = array("infocode" => "ISSUEMATERIALFAILED", "message" => "Unable to receive material, please try again");
        }
    }
    

    return $output;
}

function return_material(){
    global $dbc; $ierrcount = $uerrcount = 0;
    //$mrn_type = mysqli_real_escape_string($dbc,trim($_POST['mrn_type']));
    $issued_by = $_SESSION['userid'];
    
    if(count($_POST['return_list'])){
        
        foreach ($_POST['return_list'] as $key => $value) {
            $returned_quantity = mysqli_real_escape_string($dbc,trim($_POST['returned_qty_'.$value]));
            $query2 = "UPDATE spare_request SET returned_quantity = returned_quantity + $returned_quantity WHERE spare_request_id = $value";
            if(!mysqli_query($dbc, $query2)){
                $ierrcount++;
                file_put_contents("materialreturnerr.log", "\n Material Return error for Spare Request id : $value", FILE_APPEND | LOCK_EX);
            }            
        }
        if($ierrcount){
            $output = array("infocode" => "RETURNMATERIALFAILED", "message" => "Unable to return all materials, please try again");
        }else{
            $output = array("infocode" => "RETURNMATERIALSUCCESS", "message" => "Material(s) returned succesfully!");
        }
    }
    

    return $output;
}

function raise_purchase_indent(){
    global $dbc; $ierrcount = $uerrcount = 0;
    //$mrn_type = mysqli_real_escape_string($dbc,trim($_POST['mrn_type']));
    $created_by = $_SESSION['userid'];
    
    if(count($_POST['check_item'])){
        $status = 'created';
        $query = "INSERT INTO purchase_indent (created_by, status) VALUES ($created_by, '$status')";
        if(mysqli_query($dbc, $query)){
            $purchase_indent_id = mysqli_insert_id($dbc);
            foreach ($_POST['check_item'] as $key => $value) {
                $item_master_id = mysqli_real_escape_string($dbc,trim($value));
                $requested_quantity = mysqli_real_escape_string($dbc,trim($_POST['request_quantity'][$key]));
                $query2 = "INSERT INTO purchase_indent_detail (purchase_indent_id, item_master_id, requested_quantity, status)
                    VALUES ($purchase_indent_id, $item_master_id, $requested_quantity, '$status')";
                if(!mysqli_query($dbc, $query2)){
                    $ierrcount++;
                    $insert_flag = false;
                    file_put_contents("purchaseindenterr.log", "\n Purchase Indent error for Purchase Indent id : $purchase_indent_id, item_id : $item_master_id \n $query2", FILE_APPEND | LOCK_EX);
                }
            }
            $output = array("infocode" => "RAISEPURCHASEINDENTSUCCESS", "message" => "Purchase Indent raised succesfully!");
        }else{
            $output = array("infocode" => "RAISEPURCHASEINDENTFAILED", "message" => "Unable to raise purchase indent, please try again");
        }
    }
    

    return $output;
}