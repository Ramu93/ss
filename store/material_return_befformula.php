<?php 

session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}

include('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
include('..'.DIRECTORY_SEPARATOR.'header.php');
include('..'.DIRECTORY_SEPARATOR.'topbar.php');
include('..'.DIRECTORY_SEPARATOR.'sidebar.php');

//Load the pending request
$list_type = isset($_GET['list_type'])?$_GET['list_type']:'engineer';
$thead = $tbody = '';$datatableflag = false;$data = $engineer_data = $item_data = array();

//Fetch Engineer Stock
$engr_query = "SELECT * FROM engineer_stock";
$engr_result = mysqli_query($dbc, $engr_query);
if(mysqli_num_rows($engr_result)>0){
    while ($engr_row = mysqli_fetch_assoc($engr_result)) {
        $engineer_stock[$engr_row['engineer_id']][$engr_row['item_master_id']] = $engr_row;
        if(array_key_exists($engr_row['item_master_id'], $item_data)){
            $item_data[$engr_row['item_master_id']]['current_stock'] = $item_data[$engr_row['item_master_id']]['current_stock'] + $engr_row['current_stock'];
            $item_data[$engr_row['item_master_id']]['minimum_stock'] = $item_data[$engr_row['item_master_id']]['minimum_stock'] + $engr_row['minimum_stock'];
        }else{
            $item_data[$engr_row['item_master_id']]['current_stock'] = $engr_row['current_stock'];
            $item_data[$engr_row['item_master_id']]['minimum_stock'] = $engr_row['minimum_stock'];
        }
    }
}


$spare_status = "StoresPending','partiallyissued";
if($list_type == 'engineer'){
    $query = "SELECT a.item_master_id , b.item_name , a.ticket_id, a.quantity,d.employee_name, c.engineer_id, a.replaced_quantity, a.returned_quantity
                FROM spare_request a, item_master b, raise_ticket c, employee_master d
                WHERE a.item_master_id = b.item_master_id AND a.ticket_id = c.ticket_id AND c.engineer_id = d.employee_master_id AND b.is_returnable = 'yes' AND (a.replaced_quantity - a.returned_quantity) > 0";
    $result = mysqli_query($dbc, $query);
    $thead = '<tr><th>Engineer</th><th>Material Name</th><th>Returnable Quantity</th>';
    $snocount = 1;
    if(mysqli_num_rows($result)>0){
        $datatableflag = true;
        while($row = mysqli_fetch_assoc($result)){
            if(array_key_exists($row['engineer_id'], $data) && array_key_exists($row['item_master_id'], $data[$row['engineer_id']])){
                $data[$row['engineer_id']][$row['item_master_id']]['replaced_quantity'] = $data[$row['engineer_id']][$row['item_master_id']]['replaced_quantity'] + $row['replaced_quantity'];
                $data[$row['engineer_id']][$row['item_master_id']]['returned_quantity'] = $data[$row['engineer_id']][$row['item_master_id']]['returned_quantity'] + $row['returned_quantity'];
            }else{
                $data[$row['engineer_id']][$row['item_master_id']] = $row;
            }
        }
        foreach ($data as $key => $value) {
            $engineer_id = $key;$tc1 = 1;
            foreach ($value as $key2 => $value2) {
                /*if(array_key_exists($value2['engineer_id'], $engineer_stock) && array_key_exists($value2['item_master_id'], $engineer_stock[$value2['engineer_id']])){
                    $needed_qty = $value2['quantity']-$value2['replaced_quantity']-$engineer_stock[$value2['engineer_id']][$value2['item_master_id']]['current_stock']+$engineer_stock[$value2['engineer_id']][$value2['item_master_id']]['minimum_stock'];
                    if($needed_qty > 0){
                        if($tc1 == 1){
                            $tbody .= '<tr><td rowspan="[rs_engineer]">'.$value2['employee_name'].'</td><td>'.$value2['item_name'].'</td><td>'.$needed_qty.'</td></tr>';
                        }
                        else{
                            $tbody .= '<tr><td>'.$value2['item_name'].'</td><td>'.$needed_qty.'</td></tr>';
                        }
                        $tc1++;
                    }
                }else{*/
                    if($tc1 == 1){
                        $tbody .= '<tr><td rowspan="[rs_engineer]">'.$value2['employee_name'].'</td><td>'.$value2['item_name'].'</td><td>'.($value2['replaced_quantity']-$value2['returned_quantity']).'</td></tr>';
                    }
                    else{
                        $tbody .= '<tr><td>'.$value2['item_name'].'</td><td>'.($value2['replaced_quantity']-$value2['returned_quantity']).'</td></tr>';
                    }
                    $tc1++;
                //}

            }
            $tbody = str_replace("[rs_engineer]",($tc1-1), $tbody);
            //$tbody = str_replace("[rs_customer]",($tc1-1), $tbody);
        }
        //print_r($data);
    }else{
        $tbody = '<tr><td colspan="3">No Pending Material Return</td></tr>';
    }                
}elseif($list_type == 'ticket'){
    $query = "SELECT a.item_master_id , b.item_name , a.ticket_id, a.quantity,d.customer_name, c.engineer_id, a.replaced_quantity, a.returned_quantity
        FROM spare_request a, item_master b, raise_ticket c, party_master d
        WHERE a.item_master_id = b.item_master_id AND a.ticket_id = c.ticket_id AND c.client_id = d.party_master_id AND b.is_returnable = 'yes' AND (a.replaced_quantity - a.returned_quantity) > 0";
    $result = mysqli_query($dbc, $query);
    $thead = '<tr><th>Ticket ID</th><th>Customer Name</th><th>Material Name</th><th>Returnable Quantity</th><th>Returned Quantity</th><th>Action</th>';
    $snocount = 1;
    if(mysqli_num_rows($result)>0){
        $datatableflag = true;
        while($row = mysqli_fetch_assoc($result)){
            if(array_key_exists($row['ticket_id'], $data) && array_key_exists($row['item_master_id'], $data[$row['ticket_id']])){
                $data[$row['ticket_id']][$row['item_master_id']]['returned_quantity'] = $data[$row['ticket_id']][$row['item_master_id']]['returned_quantity'] + $row['returned_quantity'];
                $data[$row['ticket_id']][$row['item_master_id']]['replaced_quantity'] = $data[$row['ticket_id']][$row['item_master_id']]['replaced_quantity'] + $row['replaced_quantity'];
            }else{
                $data[$row['ticket_id']][$row['item_master_id']] = $row;
            }
            //$data[$row['ticket_id']][] = $row;
        }
        foreach ($data as $key => $value) {
            $ticket_id = $key;$tc1 = 1;
            foreach ($value as $key2 => $value2) {
                if($tc1 == 1){
                    $tbody .= '<tr><td rowspan="[rs_ticket]">'.$ticket_id.'</td><td rowspan="[rs_customer]">'.$value2['customer_name'].'</td><td>'.$value2['item_name'].'</td><td>'.($value2['replaced_quantity']-$value2['returned_quantity']).'</td></tr>';
                }
                else{
                    $tbody .= '<tr><td>'.$value2['item_name'].'</td><td>'.($value2['replaced_quantity']-$value2['returned_quantity']).'</td></tr>';
                }
                $tc1++;
            }
            $tbody = str_replace("[rs_ticket]",($tc1-1), $tbody);
            $tbody = str_replace("[rs_customer]",($tc1-1), $tbody);
        }
        print_r($data);
    }else{
        $tbody = '<tr><td colspan="4">No Pending Material Return</td></tr>';
    }
}elseif($list_type == 'material'){
    $query = "SELECT a.item_master_id , b.item_name , SUM(a.replaced_quantity) as replaced_quantity, SUM(a.returned_quantity) as returned_quantity
            FROM spare_request a, item_master b
            WHERE a.item_master_id = b.item_master_id AND (a.replaced_quantity - a.returned_quantity) > 0
            GROUP BY a.item_master_id";
    $result = mysqli_query($dbc, $query);
    $thead = '<tr><th>Material Name</th><th>Returnable Quantity</th>';
    $snocount = 1;
    if(mysqli_num_rows($result)>0){
        $datatableflag = true;
        while($row = mysqli_fetch_assoc($result)){
            /*if(array_key_exists($row['item_master_id'], $item_data)){
                $needed_qty = $row['quantity'] - $row['replaced_quantity'] - $item_data[$row['item_master_id']]['current_stock'] + $item_data[$row['item_master_id']]['minimum_stock'];
                if($needed_qty > 0){
                    $tbody .= '<tr><td>'.$row['item_name'].'</td><td>'.$needed_qty.'</td></tr>';
                }
            }else{
                if(($row['quantity']-$row['replaced_quantity'])>0)*/
                    $tbody .= '<tr><td>'.$row['item_name'].'</td><td>'.($row['replaced_quantity']-$row['returned_quantity']).'</td></tr>';
            //}
        }
    }else{
        $tbody = '<tr><td colspan="2">No Pending Material Return</td></tr>';
    }
}
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Material Return</h5>
        </div>
        <div class="row-fluid">
            <div class="span1"></div>
            <div class="span5">
                <label> List By: </label>
                <select name="list_type" id="list_type" class="form-control" onchange="list_type_change();">
                    <option value="engineer" <?php echo ($list_type == 'engineer')?'selected="selected"':'' ?>>Engineer</option>
                    <option value="ticket" <?php echo ($list_type == 'ticket')?'selected="selected"':'' ?>>Ticket</option>
                    <option value="material" <?php echo ($list_type == 'material')?'selected="selected"':'' ?>>Material</option>
                </select>
            </div>
        </div><div class="clearfix"></div>
        <div class="widget-content nopadding">
			<table id="spare_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <?php echo $thead; ?>
                </thead>
				<tbody>
					<?php echo $tbody; ?>		
                </tbody>
            </table>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function() {
    <?php //if($datatableflag){ ?>
    //$('#spare_list').DataTable();
    <?php //} ?>
    $('#li_store_menu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');
});

function list_type_change(){
    list_type = $('#list_type').val();
    window.location = 'material_return.php?list_type='+list_type;
    
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>