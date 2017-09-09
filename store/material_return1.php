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
/*
$list_type = isset($_GET['list_type'])?$_GET['list_type']:'engineer';
$thead = $tbody = '';$datatableflag = false;$data = $engineer_stock = array();

//Fetch Engineer Stock
$engr_query = "SELECT * FROM engineer_stock";
$engr_result = mysqli_query($dbc, $engr_query);
if(mysqli_num_rows($engr_result)>0){
    while ($engr_row = mysqli_fetch_assoc($engr_result)) {
        $engineer_stock[$engr_row['engineer_id']][$engr_row['item_master_id']] = $engr_row;
    }
}
//print_r($engineer_stock);

//Populate the pending materials
$spare_status = "StoresPending','partiallyissued";
$query = "SELECT a.item_master_id , b.item_name , a.ticket_id, a.quantity,d.employee_name, c.engineer_id, a.replaced_quantity
                FROM spare_request a, item_master b, raise_ticket c, employee_master d
                WHERE a.spare_status IN ('$spare_status') AND a.item_master_id = b.item_master_id AND c.ticket_status IN ('assigned','sparespending') AND a.ticket_id = c.ticket_id AND c.engineer_id = d.employee_master_id";
$result = mysqli_query($dbc, $query);
$thead = '<tr><th>Engineer</th><th>Material Name</th><th>Requested Quantity</th><th>Issued Quantity</th><th>Action</th>';
$snocount = 1;
if(mysqli_num_rows($result)>0){
    $datatableflag = true;
    while($row = mysqli_fetch_assoc($result)){
        if(array_key_exists($row['engineer_id'], $data) && array_key_exists($row['item_master_id'], $data[$row['engineer_id']])){
            $data[$row['engineer_id']][$row['item_master_id']]['quantity'] = $data[$row['ticket_id']][$row['item_master_id']]['quantity'] + $row['quantity'];
            $data[$row['engineer_id']][$row['item_master_id']]['replaced_quantity'] = $data[$row['ticket_id']][$row['item_master_id']]['replaced_quantity'] + $row['replaced_quantity'];
        }else{
            $data[$row['engineer_id']][$row['item_master_id']] = $row;
        }
        //$data[$row['ticket_id']][] = $row;
    }
    foreach ($data as $key => $value) {
        $engineer_id = $key;$tc1 = 1;
        foreach ($value as $key2 => $value2) {
            $ukey = $value2['engineer_id'].'_'.$value2['item_master_id'];
            if(array_key_exists($value2['engineer_id'], $engineer_stock) && array_key_exists($value2['item_master_id'], $engineer_stock[$value2['engineer_id']])){
                $needed_qty = $value2['quantity']-$value2['replaced_quantity']-$engineer_stock[$value2['engineer_id']][$value2['item_master_id']]['current_stock']+$engineer_stock[$value2['engineer_id']][$value2['item_master_id']]['minimum_stock'];
                if($needed_qty > 0){
                    if($tc1 == 1){
                        $tbody .= '<tr><td rowspan="[rs_engineer]">'.$value2['employee_name'].'</td><td>'.$value2['item_name'].'</td><td>'.($value2['quantity']-$value2['replaced_quantity']).'</td>
                        <td><input type="text" class="number" name="issued_qty_'.$ukey.'" /></td><td><input type="checkbox" name="issue_list[]" value="'.$ukey.'" />
                        <input type="hidden" name="engineer_id_'.$ukey.'" value="'.$value2['engineer_id'].'" /><input type="hidden" name="item_master_id_'.$ukey.'" value="'.$value2['item_master_id'].'" /></td></tr>';
                    }
                    else{
                        $tbody .= '<tr><td>'.$value2['item_name'].'</td><td>'.($value2['quantity']-$value2['replaced_quantity']).'</td><td><input type="text" class="number" name="issued_qty_'.$ukey.'" /></td><td><input type="checkbox" name="issue_list[]" value="'.$ukey.'" />
                        <input type="hidden" name="engineer_id_'.$ukey.'" value="'.$value2['engineer_id'].'" /><input type="hidden" name="item_master_id_'.$ukey.'" value="'.$value2['item_master_id'].'" /></td></tr>';
                    }
                    $tc1++;
                }
            }else{
                if($tc1 == 1){
                    $tbody .= '<tr><td rowspan="[rs_engineer]">'.$value2['employee_name'].'</td><td>'.$value2['item_name'].'</td><td>'.($value2['quantity']-$value2['replaced_quantity']).'</td>
                    <td><input type="text" class="number" name="issued_qty_'.$ukey.'" /></td><td><input type="checkbox" name="issue_list[]" value="'.$ukey.'" />
                    <input type="hidden" name="engineer_id_'.$ukey.'" value="'.$value2['engineer_id'].'" /><input type="hidden" name="item_master_id_'.$ukey.'" value="'.$value2['item_master_id'].'" /></td></tr>';
                }
                else{
                    $tbody .= '<tr><td>'.$value2['item_name'].'</td><td>'.($value2['quantity']-$value2['replaced_quantity']).'</td><td><input type="text" class="number" name="issued_qty_'.$ukey.'" /></td><td><input type="checkbox" name="issue_list[]" value="'.$ukey.'" />
                    <input type="hidden" name="engineer_id_'.$ukey.'" value="'.$value2['engineer_id'].'" /><input type="hidden" name="item_master_id_'.$ukey.'" value="'.$value2['item_master_id'].'" /></td></tr>';
                }
                $tc1++;
            }
        }
        $tbody = str_replace("[rs_ticket]",($tc1-1), $tbody);
        $tbody = str_replace("[rs_engineer]",($tc1-1), $tbody);
        $tbody = str_replace("[rs_customer]",($tc1-1), $tbody);
    }
    if($tbody == '') $tbody = '<tr><td colspan="4">Pending Materials have been already issued</td></tr>';
}else{
    $tbody = '<tr><td colspan="4">No Pending Material Request</td></tr>';
}
*/
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-90">
        <form id="issue_material_form" action="" onsubmit="return false;" method="POST">
            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
              <h5>Material Return</h5>
            </div>
            <!--div class="row-fluid">
                <div class="span1"></div>
                <div class="span5">
                    <!--label> List By: </label>
                    <select name="list_type" id="list_type" class="form-control" onchange="list_type_change();">
                        <option value="engineer" <?php //echo ($list_type == 'engineer')?'selected="selected"':'' ?>>Engineer</option>
                        <option value="ticket" <?php //echo ($list_type == 'ticket')?'selected="selected"':'' ?>>Ticket</option>
                    </select->
                </div>
            </div><div class="clearfix"></div>
            <div class="widget-content nopadding">
    			<table id="spare_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <?php //echo $thead; ?>
                    </thead>
    				<tbody>
    					<?php //echo $tbody; ?>		
                    </tbody>
                </table>
    		</div>
            <div class="row-fluid" style="padding:25px;">
                <div class="span8"></div>
                <div class="span3 text-right">
                    <button type="button" class="btn btn-success" onclick="issue_material();" id="btn_issue_material">Issue Material</button>
                    <input type="hidden" name="action" value="issue_material" />
                    <label for="issue_list[]" generated="true" class="error"></label>
                </div>
            </div-->
            <div class="row-fluid" style="padding:25px;">
                <div class="span8">Under Development</div>
            </div>
        </form>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function() {
    <?php //if($datatableflag){ ?>
    //$('#spare_list').DataTable().fnFakeRowspan();
    <?php //} ?>
    $('#li_store_menu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');
    $('#issue_material_form').validate({
        rules: { 
            "issue_list[]": { 
                    required: true, 
                    minlength: 1 
            } 
        }, 
        messages: { 
                "issue_list[]": "Please select at least one material."
        } 
    });
});

function list_type_change(){
    list_type = $('#list_type').val();
    window.location = 'material_issue.php?list_type='+list_type;
    
}

function issue_material(){
    if($('#issue_material_form').valid()){
        data = $('#issue_material_form').serialize();
        $.ajax({
            url: "stockservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "ISSUEMATERIALSUCCESS"){
                    bootbox.alert(result.message, function(){
                        //window.location = 'mrn.php?mrn_type='+mrn_type;
                        location.reload();
                    });
                }else{
                    bootbox.alert(result.message);
                    //$('#btn_receive_material').attr('disabled','disabled');
                }
            },
            error: function(){}             
        });
    }
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>