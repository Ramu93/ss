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
          <h5>Create Ticket</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="addticket_form" name="addticket_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Select Client :</label>
					<div class="controls">
						<select id="myselect" name="client_name">
							<option value="ABC Corp">ABC Corp</option>
							<option value="Ram Industries">Ram Industries</option>
							<option value="XYZ Ind">XYZ Ind</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Select Location :</label>
					<div class="controls">
						<?php $query = "SELECT * FROM party_location";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '<select name="party_location_id" id="party_location_id" class="form-control required" onchange="location_change();">';
									echo '<option value="" selected="selected">Select Location</option>';
                                    while($row=mysqli_fetch_array($result)){
                                        echo '<option value="'.$row['party_location_id'].'">'.$row['location_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>	
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Department :</label>
					<div class="controls">
						<select name="department_id" id="department_id" class="form-control required" onchange="product_change();">
							<option value="">Select Department</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Select Product :</label>
					<div class="controls">
						<select name="item_master_id" id="item_master_id" class="form-control required" onchange="">
							<option value="">Select Product</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Nature of Complaint :</label>
					<div class="controls">
						<?php $query = "SELECT * FROM  complaint";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '<select name="complaint_master_id" id="complaint_master_id" class="form-control required" onchange="">';
									echo '<option value="" selected="selected">Select Complaint</option>';
                                    while($row=mysqli_fetch_array($result)){
                                        echo '<option value="'.$row['complaint_master_id'].'">'.$row['complaint_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Additional Details :</label>
					<div class="controls">
						<input type="text" class="span11 required" id="additional_details" name="additional_details" placeholder="Additional Details" value=""/>
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="add_ticket_details();">Create Ticket</button>
				</div>
				<input type="hidden" name="action" id="action" value="add_ticket"/>
			</form>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function(){
    $('#addticket_form').validate();
    $('#li_sc_ticketmenu').addClass('active open');
});

$(window).bind("load", function() {
    
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_ticket_details(){
    if($('#addticket_form').valid()){
        var data = $('#addticket_form').serialize();
        $.ajax({
            url: "test_services.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "TICKETADDED"){
                    bootbox.alert(result.message);
                    $('#addticket_form')[0].reset();
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
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
					$('#item_master_id').html('<option value="">Select Department</option>');
                    //$('#party_location_id').removeAttr('disabled');
                }else{
                    $('#department_id').html('<option value="">No Department added</option>');
					$('#item_master_id').html('<option value="">Select Item Master</option>');
                    //$('#party_location_id').attr('disabled', 'disabled');
                }
            }else{
                //bootbox.alert(result.message);
                $('#department_id').html('<option value="">No Department added</option>');
                $('#item_master_id').html('<option value="">Select Item Master</option>');
                //$('#party_location_id').attr('disabled', 'disabled');
            }
        },
        error: function(){}             
    });
    
}
function product_change(){
	department_id = $('#department_id').val();
    var dp='';
    $.ajax({
        url: "generalservices.php",
        type: "POST",
        data:  'department_id='+department_id+'&action=product_change',
        dataType: 'json',
        success: function(result){
            if(result.infocode == "PRODUCTDATARETREIVED"){
                data = result.item_data;
                if(data.length){
					dp += '<option value="">Select Item</option>';
                    for(c=0; c<data.length;c++){
						dp += '<option value="'+data[c].item_master_id+'">'+data[c].item_name+'</option>';
                    }
					$('#item_master_id').html(dp);
                    //$('#department_id').removeAttr('disabled');
                }else{
					$('#item_master_id').html('<option value="">No Item added</option>');
                    //$('#department_id').attr('disabled', 'disabled');
                }
            }else{
                //bootbox.alert(result.message);
				$('#item_master_id').html('<option value="">No Item added</option>');
                //$('#department_id').attr('disabled', 'disabled');
            }
        },
        error: function(){}             
    });
    
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>