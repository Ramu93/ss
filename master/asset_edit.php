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
 
$asset_master_id = 0;
$item_master_id = isset($_GET['item_master_id'])?$_GET['item_master_id']:0;
$party_master_id = isset($_GET['party_master_id'])?$_GET['party_master_id']:0;
$party_location_id = isset($_GET['party_location_id'])?$_GET['party_location_id']:0;
if(isset($_GET['asset_master_id'])){
    $asset_master_id = $_GET['asset_master_id'];
}else{
    header('Location: asset_view.php'); exit();
}

$query = "SELECT * FROM  asset_master WHERE asset_master_id = $asset_master_id";
$result = mysqli_query($dbc, $query);
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);
}else{
    header('Location: asset_view.php'); exit();
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
          <h5>Asset Edit</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="editasset_form" name="editasset_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">RF ID :</label>
					<div class="controls">
                         <input type="text" class="form-control " name="rfid_tag" id="rfid_tag" placeholder="RF ID" value="<?php echo $row['rfid_tag']; ?>" />	
					</div>
				</div>
				<div class="control-group">
                    <label class="control-label">Product :</label>
                    <div class="controls">
                         <?php $query2 = "SELECT * FROM  item_master WHERE item_type = 'FG'";
                                $result2 = mysqli_query($dbc,$query2);
                                if(mysqli_num_rows($result2)>0){
                                    echo '<select name="item_master_id" id="item_master_id" class="form-control required" onchange="">';
                                    while($row2=mysqli_fetch_array($result2)){
                                        $selected2 = ($row2['item_master_id'] == $row['item_master_id'])?'selected="selected"':'';
                                        echo '<option value="'.$row2['item_master_id'].'" '.$selected2.'>'.$row2['item_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Manufacturer Serial Number:</label>
                    <div class="controls">
                         <input type="text" class="form-control" name="manu_sno" id="manu_sno" placeholder="Serial Number" value="<?php echo $row['manufacturer_serial_number']; ?>"  />  
                    </div>
                </div>
				<div class="control-group">
					<label class="control-label">Customer :</label>
					<div class="controls">
                         <?php $query3 = "SELECT * FROM party_master";
                                $result3 = mysqli_query($dbc,$query3);
                                if(mysqli_num_rows($result3)>0){
                                    echo '<select name="party_master_id" id="party_master_id" class="form-control-required" onchange="asset_change();">';
                                    while($row3=mysqli_fetch_array($result3)){
                                        $selected3 = ($row3['party_master_id'] == $row['party_master_id'])?'selected="selected"':'';
                                        echo '<option value="'.$row3['party_master_id'].'" '.$selected3.'>'.$row3['customer_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>	
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Location :</label>
					<div class="controls">
                        <?php $query4 = "SELECT * FROM  party_location WHERE party_master_id = $party_master_id";
                                $result4 = mysqli_query($dbc,$query4);
                                if(mysqli_num_rows($result4)>0){
                                    echo '<select name="party_location_id" id="party_location_id" class="form-control-required" onchange="location_change();">';
                                    while($row4=mysqli_fetch_array($result4)){
                                        $selected4 = ($row4['party_location_id'] == $row['party_location_id'])?'selected="selected"':'';
                                        echo '<option value="'.$row4['party_location_id'].'" '.$selected4.'>'.$row4['location_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Department :</label>
					<div class="controls">
                        <?php $query5 = "SELECT * FROM department WHERE party_master_id = $party_master_id AND party_location_id = {$row['party_location_id']}";
                                $result5 = mysqli_query($dbc,$query5);
                                if(mysqli_num_rows($result5)>0){
                                    echo '<select name="department_id" id="department_id" class="form-control-required" onchange="">';
                                    while($row5=mysqli_fetch_array($result5)){
                                        $selected5 = ($row5['department_id'] == $row['department_id'])?'selected="selected"':'';
                                        echo '<option value="'.$row5['department_id'].'"'.$selected5.'>'.$row5['department_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>
					</div>
				</div>
                <div class="control-group">
                    <label class="control-label">Machine Reading:</label>
                    <div class="controls">
                         <input type="text" class="form-control " name="machine_reading" id="machine_reading" placeholder="Machine Reading" value="<?php echo $row['machine_reading']; ?>" />  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Rent:</label>
                    <div class="controls">
                         <input type="text" class="form-control " name="rent" id="rent" placeholder="Rent" value="<?php echo $row['rent']; ?>"/>  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Free Copies:</label>
                    <div class="controls">
                         <input type="text" class="form-control " name="free_copies" id="free_copies" placeholder="Free Copies" value="<?php echo $row['free_copies']; ?>"/>  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Extra Copy Charges:</label>
                    <div class="controls">
                         <input type="text" class="form-control " name="extra_copy_charges" id="extra_copy_charges" placeholder="Extra Copy Charges" value="<?php echo $row['extra_copy_charges']; ?>" />  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Reference Code:</label>
                    <div class="controls">
                         <input type="text" class="form-control " name="old_code" id="old_code" placeholder="Reference Code" value="<?php echo $row['old_code']; ?>" />  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Effective from:</label>
                    <div class="controls">
                         <input type="text" class="form-control datepicker" name="effective_from" id="effective_from" placeholder="Effective From" value="<?php echo $row['active_from']; ?>" />  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Status:</label>
                    <div class="controls">
                         <select name="asset_status" id="asset_status" class="form-control required valid" onchange="">
                             <option value="live" <?php echo ($row['status']=='live')?'selected="selected"':''; ?>>Live</option>
                             <option value="maintenance" <?php echo ($row['status']=='maintenance')?'selected="selected"':''; ?>>Maintenance</option>
                             <option value="scrapped" <?php echo ($row['status']=='scrapped')?'selected="selected"':''; ?>>Scrapped</option>
                         </select>
                    </div>
                </div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="update_asset_details();">Update Asset</button>
				</div>
				<input type="hidden" name="action" id="action" value="edit_asset"/>
				<input type="hidden" name="asset_master_id" id="asset_master_id" value="<?php echo $asset_master_id; ?>"/>
			</form>
		</div>
	</div>
</div>

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#editasset_form').validate();
    //$('#sb_ul_assetmenu').addClass('in');
    $('#li_sc_ticketmenu').addClass('active open');
    $('#effective_from').datepicker({
        format: 'yyyy-mm-dd'
    });
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function update_asset_details(){
    if($('#editasset_form').valid()){
        var data = $('#editasset_form').serialize();
        $.ajax({
            url: "assetservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "ASSETUPDATED"){
                    bootbox.alert(result.message, function(){
                        window.location = 'asset_view.php';
                    });
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}

/*function asset_change(){
    party_master_id = $('#party_master_id').val();
    var dp='';
    $.ajax({
        url: "generalservice.php",
        type: "POST",
        data:  'party_master_id='+party_master_id+'&action=asset_change',
        dataType: 'json',
        async: false,
        success: function(result){
            if(result.infocode == "LOCATIONDATARETREIVED"){
                data = result.location_data;
                if(data.length){
                    for(c=0; c<data.length;c++){
                        dp += '<option value="'+data[c].party_location_id+'">'+data[c].party_location_id+'</option>';
                    }
                    $('#party_location_id').html(dp);
                    $('#party_location_id').removeAttr('disabled');
                }else{
                    $('#party_location_id').html('<option value="">No location added</option>');
                    $('#party_location_id').attr('disabled', 'disabled');
                }
            }else{
                //bootbox.alert(result.message);
                $('#party_location_id').html('<option value="">No location added</option>');
                $('#party_location_id').attr('disabled', 'disabled');
            }
        },
        error: function(){}             
    });
    
}*/

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