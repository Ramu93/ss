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
 
$item_master_id = 0;
if(isset($_GET['item_master_id'])){
    $item_master_id = $_GET['item_master_id'];
}else{
    header('Location: item_view.php'); exit();
}

$query = "SELECT * FROM  item_master WHERE item_master_id = $item_master_id";
$result = mysqli_query($dbc, $query);
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);
}else{
    header('Location: item_view.php'); exit();
}
?>
<style type="text/css">
.error{
    color:red;
}
</style>
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>index1.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-10">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Edit Product</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="edititem_form" name="edititem_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Product Name :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="item_name" id="item_name" placeholder="Item Name" value="<?php echo $row['item_name']; ?>" />	
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Product Type :</label>
					<div class="controls">
                        <select name="product_type" id="product_type" class="form-control required valid" onchange="">
                             <option value="FG" <?php echo ($row['item_type']=='FG')?'selected="selected"':''; ?> >Finished Goods</option>
                             <option value="CS" <?php echo ($row['item_type']=='CS')?'selected="selected"':''; ?>>Consumables</option>
                             <option value="SPARES" <?php echo ($row['item_type']=='SPARES')?'selected="selected"':''; ?>>Spares</option>
                         </select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Brand :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="brand" id="brand" placeholder="Brand" value="<?php echo $row['brand']; ?>" />
					</div>
				</div>
                <div class="control-group">
                    <label class="control-label">Product Group :</label>
                    <div class="controls">
                        <input type="text" class="form-control " name="item_group" id="item_group" placeholder="Product Group" value="<?php echo $row['group_name']; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Product Code :</label>
                    <div class="controls">
                        <input type="text" class="form-control " name="item_code" id="item_code" placeholder="Product Code" value="<?php echo $row['item_code']; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Threshold Quantity :</label>
                    <div class="controls">
                        <input type="text" class="form-control number" name="threshold_quantity" id="threshold_quantity" placeholder="Threshold Quantity" value="<?php echo $row['threshold_quantity']; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Minimum Order Quantity :</label>
                    <div class="controls">
                        <input type="text" class="form-control number" name="moq" id="moq" placeholder="MOQ" value="<?php echo $row['minimum_order_quantity']; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Returnable Product:</label>
                    <div class="controls">
                        <select name="returnable_flag" id="returnable_flag" class="form-control required">
                             <option value="yes" <?php echo ($row['is_returnable']=='yes')?'selected="selected"':''; ?>>Yes</option>
                             <option value="no" <?php echo ($row['is_returnable']=='no')?'selected="selected"':''; ?>>No</option>
                         </select>
                    </div>
                </div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="update_item_details();">Update Product</button>
				</div>
				<input type="hidden" name="action" id="action" value="edit_item"/>
				<input type="hidden" name="item_master_id" id="item_master_id" value="<?php echo $item_master_id; ?>"/>
			</form>
		</div>
	</div>
</div>

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#edititem_form').validate();
    $('#sb_ul_itemmenu').addClass('in');
    $('#li_sc_ticketmenu').addClass('active open');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function update_item_details(){
    if($('#edititem_form').valid()){
        var data = $('#edititem_form').serialize();
        $.ajax({
            url: "itemservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "ITEMUPDATED"){
                    bootbox.alert(result.message, function(){
                        window.location = 'item_view.php';
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