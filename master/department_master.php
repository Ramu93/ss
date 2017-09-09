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
          <h5>Department Master</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="adddepartment_form" name="adddepartment_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Party Name :</label>
					<div class="controls">
                         <?php $query = "SELECT * FROM party_master";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '<select name="party_master_id" id="party_master_id" class="form-control required" onchange="asset_change();">';
									echo '<option value="" selected="selected">Select Party Name</option>';
                                    while($row=mysqli_fetch_array($result)){
                                        echo '<option value="'.$row['party_master_id'].'">'.$row['customer_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>	
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Location :</label>
					<div class="controls">
						<select name="party_location_id" id="party_location_id" class="form-control required" onchange="">
							<option value="">Select Location</option>
						</select>
                        <?php /*$query = "SELECT * FROM  party_location";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '';
                                    while($row=mysqli_fetch_array($result)){
                                        echo '<option value="'.$row['party_location_id'].'">'.$row['location_name'].'</option>';
                                    }
                                    echo '';
                                }*/
                        ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Department :</label>
					<div class="controls">
						<input type="text" class="form-control required" name="department_name" id="department_name" placeholder="Department" />
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="add_department_details();">Submit Request</button>
				</div>
				<input type="hidden" name="action" id="action" value="add_department"/>
			</form>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function(){ 
    $('#adddepartment_form').validate();
    $('#li_mastermenu').addClass('active open');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_department_details(){
    if($('#adddepartment_form').valid()){
        var data = $('#adddepartment_form').serialize();
        $.ajax({
            url: "departmentservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "DEPARTMENTADDED"){
                    bootbox.alert(result.message);
                    $('#adddepartment_form')[0].reset();
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

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>