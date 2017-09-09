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
          <h5>Asset Master</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="addasset_form" name="addasset_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">RF ID :</label>
					<div class="controls">
                         <input type="text" class="form-control" name="rfid_tag" id="rfid_tag" placeholder="RF ID" />	
					</div>
				</div>
                <div class="control-group">
					<label class="control-label">Product :</label>
					<div class="controls">
                         <?php $query = "SELECT * FROM  item_master WHERE item_type = 'FG'";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '<select name="item_master_id" id="item_master_id" class="form-control required" onchange="">';
                                    while($row=mysqli_fetch_array($result)){
                                        echo '<option value="'.$row['item_master_id'].'">'.$row['item_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>
					</div>
				</div>
                <div class="control-group">
                    <label class="control-label">Manufacturer Serial Number:</label>
                    <div class="controls">
                         <input type="text" class="form-control" name="manu_sno" id="manu_sno" placeholder="Serial Number" />  
                    </div>
                </div>
				<div class="control-group">
					<label class="control-label">Customer :</label>
					<div class="controls">
                         <?php $query = "SELECT * FROM party_master";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '<select name="party_master_id" id="party_master_id" class="form-control required" onchange="asset_change();">';
									echo '<option value="" selected="selected">Select Customer</option>';
                                    while($row=mysqli_fetch_array($result)){
                                        echo '<option value="'.$row['party_master_id'].'">'.$row['customer_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>	
					</div>
				</div>
				<div class="control-group row-fluid">
					<label class="control-label">Location :</label>
					<div class="controls">
						<select name="party_location_id" id="party_location_id" class="form-control required" onchange="location_change();">
							<option value="">Select Location</option>
						</select>
                    </div>
				</div>
				<div class="control-group row-fluid">
					<label class="control-label">Department :</label>
					<div class="controls">
						<select name="department_id" id="department_id" class="form-control required" onchange="">
							<option value="">Select Department</option>
						</select>
					</div>
				</div>
                <div class="control-group">
                    <label class="control-label">Machine Reading:</label>
                    <div class="controls">
                         <input type="text" class="form-control " name="machine_reading" id="machine_reading" placeholder="Machine Reading" />  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Rent:</label>
                    <div class="controls">
                         <input type="text" class="form-control " name="rent" id="rent" placeholder="Rent" />  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Free Copies:</label>
                    <div class="controls">
                         <input type="text" class="form-control " name="free_copies" id="free_copies" placeholder="Free Copies" />  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Extra Copy Charges:</label>
                    <div class="controls">
                         <input type="text" class="form-control " name="extra_copy_charges" id="extra_copy_charges" placeholder="Extra Copy Charges" />  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Reference Code:</label>
                    <div class="controls">
                         <input type="text" class="form-control " name="old_code" id="old_code" placeholder="Reference Code" />  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Effective from:</label>
                    <div class="controls">
                         <input type="text" class="form-control datepicker" name="effective_from" id="effective_from" placeholder="Effective From" />  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Status:</label>
                    <div class="controls">
                         <select name="asset_status" id="asset_status" class="form-control required valid" onchange="">
                             <option value="live">Live</option>
                             <option value="maintenance">Maintenance</option>
                             <option value="scrapped">Scrapped</option>
                         </select>
                    </div>
                </div>
				<div class="control-group row-fluid" style="padding-bottom: 10px;">
                    <div class="span6 text-right">
					   <button type="submit" class="btn btn-success" onclick="add_asset_details();">Create Asset</button>
                    </div>
                    <div class="span6">
                       <button type="button" class="btn btn-primary" onclick="window.location = 'asset_view.php';">View Assets</button>
                    </div>
				</div>
				<input type="hidden" name="action" id="action" value="add_asset"/>
			</form>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function(){ 
    $('#addasset_form').validate();
    $('#li_sc_ticketmenu').addClass('active open');
    $('#effective_from').datepicker({
        format: 'yyyy-mm-dd'
    });
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_asset_details(){
    if($('#addasset_form').valid()){
        var data = $('#addasset_form').serialize();
        $.ajax({
            url: "assetservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "ASSETADDED"){
                    bootbox.alert(result.message);
                    $('#addasset_form')[0].reset();
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}

function asset_change(){
    party_master_id = $('#party_master_id').val();
    var dp='';
    $.ajax({
        url: "generalservices.php",
        type: "POST",
        data:  'party_master_id='+party_master_id+'&action=asset_change',
        dataType: 'json',
        success: function(result){
            if(result.infocode == "LOCATIONDATARETREIVED"){
                data = result.location_data;
                if(data.length){
					dp += '<option value="">Select Location</option>';
                    for(c=0; c<data.length;c++){
                        dp += '<option value="'+data[c].party_location_id+'">'+data[c].location_name+'</option>';
                    }
                    $('#party_location_id').html(dp);
					$('#department_id').html('<option value="">Select Department</option>');
                    //$('#party_location_id').removeAttr('disabled');
                }else{
                    $('#party_location_id').html('<option value="">No Location added</option>');
					$('#department_id').html('<option value="">Select Department</option>');
                    //$('#party_location_id').attr('disabled', 'disabled');
                }
            }else{
                //bootbox.alert(result.message);
                $('#party_location_id').html('<option value="">No Location added</option>');
                $('#department_id').html('<option value="">Select Department</option>');
                //$('#party_location_id').attr('disabled', 'disabled');
            }
        },
        error: function(){}             
    });
    
}
function location_change(){
	party_location_id = $('#party_location_id').val();
    var dp='';
    $.ajax({
        url: "generalservices.php",
        type: "POST",
        data:  'party_location_id='+party_location_id+'&action=location_change',
        dataType: 'json',
        success: function(result){
            if(result.infocode == "DEPARTMENTDATARETREIVED"){
                data = result.department_data;
                if(data.length){
					dp += '<option value="">Select Department</option>';
                    for(c=0; c<data.length;c++){
						dp += '<option value="'+data[c].department_id+'">'+data[c].department_name+'</option>';
                    }
					$('#department_id').html(dp);
                    //$('#department_id').removeAttr('disabled');
                }else{
					$('#department_id').html('<option value="">No Department added</option>');
                    //$('#department_id').attr('disabled', 'disabled');
                }
            }else{
                //bootbox.alert(result.message);
				$('#department_id').html('<option value="">No Department added</option>');
                //$('#department_id').attr('disabled', 'disabled');
            }
        },
        error: function(){}             
    });
    
}

function product_type_change(){
    product_type = $('#product_type').val();
    var dp='';
    $.ajax({
        url: "generalservices.php",
        type: "POST",
        data:  'product_type='+product_type+'&action=product_type_change',
        dataType: 'json',
        success: function(result){
            if(result.infocode == "PRODUCTDATARETREIVED"){
                data = result.item_data;
                if(data.length){
                    dp += '<option value="">Select Product</option>';
                    for(c=0; c<data.length;c++){
                        dp += '<option value="'+data[c].item_master_id+'">'+data[c].item_name+'</option>';
                    }
                    $('#item_master_id').html(dp);
                }else{
                    $('#item_master_id').html('<option value="">No Product added</option>');
                }
            }else{
                $('#item_master_id').html('<option value="">No Product added</option>');
            }
        },
        error: function(){}             
    });
    
}
</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>