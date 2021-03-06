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

$list_type = isset($_GET['list_type'])?$_GET['list_type']:'engineer';
$thead = $tbody = '';$datatableflag = false;$data = array();
$spare_status = "StoresPending','partiallyissued";
if($list_type == 'engineer'){
    $query = "SELECT a.item_master_id , b.item_name , a.ticket_id, a.quantity,d.employee_name, c.engineer_id
                FROM spare_request a, item_master b, raise_ticket c, employee_master d
                WHERE a.spare_status IN ('$spare_status') AND a.item_master_id = b.item_master_id AND c.ticket_status IN ('assigned','sparespending') AND a.ticket_id = c.ticket_id AND c.engineer_id = d.employee_master_id";
    $result = mysqli_query($dbc, $query);
    $thead = '<tr><th>Engineer</th><th>Material Name</th><th>Requested Quantity</th>';
    $snocount = 1;
    if(mysqli_num_rows($result)>0){
        $datatableflag = true;
        while($row = mysqli_fetch_assoc($result)){
            if(array_key_exists($row['engineer_id'], $data) && array_key_exists($row['item_master_id'], $data[$row['engineer_id']])){
                $data[$row['engineer_id']][$row['item_master_id']]['quantity'] = $data[$row['engineer_id']][$row['item_master_id']]['quantity'] + $row['quantity'];
            }else{
                $data[$row['engineer_id']][$row['item_master_id']] = $row;
            }
        }
        foreach ($data as $key => $value) {
            $engineer_id = $key;$tc1 = 1;
            foreach ($value as $key2 => $value2) {
                if($tc1 == 1){
                    $tbody .= '<tr><td rowspan="[rs_engineer]">'.$value2['employee_name'].'</td><td>'.$value2['item_name'].'</td><td>'.$value2['quantity'].'</td></tr>';
                }
                else{
                    $tbody .= '<tr><td>'.$value2['item_name'].'</td><td>'.$value2['quantity'].'</td></tr>';
                }
                $tc1++;
            }
            $tbody = str_replace("[rs_engineer]",($tc1-1), $tbody);
            //$tbody = str_replace("[rs_customer]",($tc1-1), $tbody);
        }
        //print_r($data);
    }else{
        $tbody = '<tr><td colspan="3">No Pending Material Request</td></tr>';
    }                
}elseif($list_type == 'ticket'){
    $query = "SELECT a.item_master_id , b.item_name , a.ticket_id, a.quantity, c.customer_name,a.replaced_quantity
                FROM spare_request a, item_master b, party_master c, raise_ticket d
                WHERE a.spare_status IN ('$spare_status') AND a.item_master_id = b.item_master_id AND a.ticket_id = d.ticket_id AND d.client_id = c.party_master_id";
    $result = mysqli_query($dbc, $query);
    $thead = '<tr><th>Ticket ID</th><th>Customer Name</th><th>Material Name</th><th>Requested Quantity</th>';
    $snocount = 1;
    if(mysqli_num_rows($result)>0){
        $datatableflag = true;
        while($row = mysqli_fetch_assoc($result)){
            if(array_key_exists($row['ticket_id'], $data) && array_key_exists($row['item_master_id'], $data[$row['ticket_id']])){
                $data[$row['ticket_id']][$row['item_master_id']]['quantity'] = $data[$row['ticket_id']][$row['item_master_id']]['quantity'] + $row['quantity'];
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
                    $tbody .= '<tr><td rowspan="[rs_ticket]">'.$ticket_id.'</td><td rowspan="[rs_customer]">'.$value2['customer_name'].'</td><td>'.$value2['item_name'].'</td><td>'.($value2['quantity']-$value2['replaced_quantity']).'</td></tr>';
                }
                else{
                    $tbody .= '<tr><td>'.$value2['item_name'].'</td><td>'.($value2['quantity']-$value2['replaced_quantity']).'</td></tr>';
                }
                $tc1++;
            }
            $tbody = str_replace("[rs_ticket]",($tc1-1), $tbody);
            $tbody = str_replace("[rs_customer]",($tc1-1), $tbody);
        }
        //print_r($data);
    }else{
        $tbody = '<tr><td colspan="4">No Pending Material Request</td></tr>';
    }
}elseif($list_type == 'material'){
    $query = "SELECT a.item_master_id , b.item_name , SUM(a.quantity) as quantity
                FROM spare_request a, item_master b
                WHERE a.spare_status IN ('$spare_status') AND a.item_master_id = b.item_master_id
                GROUP BY a.item_master_id";
    $result = mysqli_query($dbc, $query);
    $thead = '<tr><th>Material Name</th><th>Requested Quantity</th>';
    $snocount = 1;
    if(mysqli_num_rows($result)>0){
        $datatableflag = true;
        while($row = mysqli_fetch_assoc($result)){
            $tbody .= '<tr><td>'.$row['item_name'].'</td><td>'.$row['quantity'].'</td></tr>';
        }
    }else{
        $tbody = '<tr><td colspan="2">No Pending Material Request</td></tr>';
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
          <h5>Material Request View</h5>
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
    window.location = 'material_request.php?list_type='+list_type;
    
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>