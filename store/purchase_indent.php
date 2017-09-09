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
$thead = $tbody = '';$datatableflag = false;$data = $engineer_data = $item_data = $request_data = $indent_data = array();

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

//print_r($item_data);
$spare_status = "StoresPending','partiallyissued";
$item_type = "SPARES";
$disabled = '';

$query = "SELECT a.item_master_id , b.item_name , SUM(a.quantity) as quantity, SUM(a.replaced_quantity) as replaced_quantity
            FROM spare_request a, item_master b
            WHERE a.spare_status IN ('$spare_status') AND a.item_master_id = b.item_master_id
            GROUP BY a.item_master_id";
$result = mysqli_query($dbc, $query);
if(mysqli_num_rows($result)>0){
    while($row = mysqli_fetch_assoc($result)){
        $request_data[$row['item_master_id']]['pending_quantity'] = $row['quantity'] - $row['replaced_quantity'];
    }
}
//print_r($request_data);

$indent_query = "SELECT SUM(requested_quantity) as quantity, item_master_id
                    FROM purchase_indent_detail
                    WHERE status = 'created'
                    GROUP BY item_master_id";
$indent_result = mysqli_query($dbc, $indent_query);
if(mysqli_num_rows($indent_result)>0){
    while($indent_row = mysqli_fetch_assoc($indent_result)){
        $indent_data[$indent_row['item_master_id']]['indented_quantity'] = $indent_row['quantity'];
    }
}

$query = "SELECT a.item_name, IFNULL(b.current_stock,0) as current_stock, a.item_master_id, a.threshold_quantity
            FROM item_master a LEFT JOIN store_material_stock b 
            ON a.item_master_id = b.item_master_id 
            WHERE a.item_type IN ('$item_type')";
$result = mysqli_query($dbc, $query);
$thead = '<tr><th>Material Name</th><th>Needed Quantity</th><th>Indented Quantity</th><th>Request Quantity</th><th>Action</th>';
$snocount = 1;
if(mysqli_num_rows($result)>0){
    $datatableflag = true;$count = 0;
    while($row = mysqli_fetch_assoc($result)){
        $needed_qty = 0;
        if(array_key_exists($row['item_master_id'], $item_data)){
            $needed_qty += $item_data[$row['item_master_id']]['minimum_stock'] - $item_data[$row['item_master_id']]['current_stock'];
        }
        if(array_key_exists($row['item_master_id'], $request_data)){
            $needed_qty += $request_data[$row['item_master_id']]['pending_quantity'];
        }
        $needed_qty += $row['threshold_quantity'] - $row['current_stock'];
        if(array_key_exists($row['item_master_id'], $indent_data)){
            $indented_quantity = $indent_data[$row['item_master_id']]['indented_quantity'];
        }else{
            $indented_quantity = 0;
        }
        if($needed_qty > 0){
            $ukey = $row['item_master_id'];
            $suggested_quantity = (($needed_qty-$indented_quantity)>0)?($needed_qty-$indented_quantity):0;
            $tbody .= '<tr><td>'.$row['item_name'].'</td><td>'.$needed_qty.'</td><td>'.$indented_quantity.'</td><td><input type="text" class="form-control" name="request_quantity[]" value="'.$suggested_quantity.'"></td>
            <td><input type="checkbox" name="check_item[]" value="'.$ukey.'"></td></tr>';
            $count++;
        }
    }
    if($count == 0){
        $tbody = '<tr><td colspan="5">Stock in check</td></tr>';
        $disabled = 'disabled="disabled"';
    }
}else{
    $tbody = '<tr><td colspan="5">Stock in check</td></tr>';
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
        <form id="raise_purchase_indent_form" action="" onsubmit="return false;" method="POST">
            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
              <h5>Purchase Indent</h5>
            </div>
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span5">
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
            <div class="row-fluid" style="padding:25px;">
                <div class="span8"></div>
                <div class="span3 text-right">
                    <button type="button" class="btn btn-success" onclick="raise_purchase_indent();" id="btn_raise_purchase_indent" <?php echo $disabled; ?> >Raise Purchase Indent</button>
                    <input type="hidden" name="action" value="raise_purchase_indent" />
                    <label for="check_item[]" generated="true" class="error"></label>
                </div>
            </div>
        </form>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function() {
    $('#li_store_menu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');
    $('#raise_purchase_indent_form').validate({
        rules: { 
            "check_item[]": { 
                    required: true, 
                    minlength: 1 
            } 
        }, 
        messages: { 
                "check_item[]": "Please select at least one material."
        } 
    });
});

function raise_purchase_indent(){
    if($('#raise_purchase_indent_form').valid()){
        data = $('#raise_purchase_indent_form').serialize();
        $.ajax({
            url: "stockservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "RAISEPURCHASEINDENTSUCCESS"){
                    bootbox.alert(result.message, function(){
                        location.reload();
                    });
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>