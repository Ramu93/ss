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
 
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-10">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Product Master</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="additem_form" name="additem_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Product Name :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="item_name" id="item_name" placeholder="Product Name" />	
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Product Type :</label>
					<div class="controls">
                        <select name="product_type" id="product_type" class="form-control required valid" onchange="">
                             <option value="FG">Finished Goods</option>
                             <option value="CS">Consumables</option>
                             <option value="SPARES">Spares</option>
                         </select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Brand :</label>
					<div class="controls">
                        <input type="text" class="form-control " name="brand" id="brand" placeholder="Brand" />
					</div>
				</div>
                <div class="control-group">
                    <label class="control-label">Product Group :</label>
                    <div class="controls">
                        <input type="text" class="form-control " name="item_group" id="item_group" placeholder="Product Group" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Product Code :</label>
                    <div class="controls">
                        <input type="text" class="form-control " name="item_code" id="item_code" placeholder="Product Code" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Threshold Quantity :</label>
                    <div class="controls">
                        <input type="text" class="form-control number" name="threshold_quantity" id="threshold_quantity" placeholder="Threshold Quantity" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Minimum Order Quantity :</label>
                    <div class="controls">
                        <input type="text" class="form-control number" name="moq" id="moq" placeholder="MOQ" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Returnable Product:</label>
                    <div class="controls">
                        <select name="returnable_flag" id="returnable_flag" class="form-control required">
                             <option value="yes">Yes</option>
                             <option value="no">No</option>
                         </select>
                    </div>
                </div>
				<div class="control-group row-fluid" style="padding-bottom: 10px;">
                    <div class="span6 text-right">
                       <button type="submit" class="btn btn-success" onclick="add_item_details();">Add Product</button>
                    </div>
                    <div class="span6">
                       <button type="button" class="btn btn-primary" onclick="window.location = 'item_view.php';">View Products</button>
                    </div>
                </div>
				<input type="hidden" name="action" id="action" value="add_item"/>
			</form>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function(){ 
    $('#additem_form').validate();
    $('#li_sc_ticketmenu').addClass('active open');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_item_details(){
    if($('#additem_form').valid()){
        var data = $('#additem_form').serialize();
        $.ajax({
            url: "itemservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "ITEMADDED"){
                    bootbox.alert(result.message);
                    $('#additem_form')[0].reset();
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