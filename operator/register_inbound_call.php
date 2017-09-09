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
<style type="text/css">
.error{
	color: #ee0000;
}
</style>
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-10">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Call Register</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="addticket_form" name="addticket_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Call Type :</label>
					<div class="controls">
						<!--select id="call_type" name="call_type" onchange="calltypechange();">
							<option value="service" selected="selected">Service</option>
							<option value="sales">Sales</option>
						</select-->
						<label><input type="checkbox" name="call_type[]" class="form-control" value="service"><span style="margin-left:5px;margin-bottom:0;">Service</span></label>
						<label><input type="checkbox" name="call_type[]" class="form-control" value="consumable"><span style="margin-left:5px;margin-bottom:0;">Consumable Request</span></label>
						<label><input type="checkbox" name="call_type[]" class="form-control" value="payment"><span style="margin-left:5px;margin-bottom:0;">Payment Collection</span></label>
						<label><input type="checkbox" name="call_type[]" class="form-control" value="marketing"><span style="margin-left:5px;margin-bottom:0;">Marketing</span></label>
						<label><input type="checkbox" name="call_type[]" class="form-control" value="despatch"><span style="margin-left:5px;margin-bottom:0;">Despatch Request</span></label>
						<label for="call_type[]" generated="true" class="error" style="display: none;">Please select at least one call type.</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Customer Type :</label>
					<div class="controls">
						<label><input type="radio" name="customer_type" class="form-control" value="existing" checked="checked" onchange=""><span style="margin-left:5px;margin-bottom:0;" >Existing</span></label>
						<label><input type="radio" name="customer_type" class="form-control" value="new" onchange=""><span style="margin-left:5px;margin-bottom:0;">New</span></label>
					</div>
				</div>
				<div id="service_div">
					<div class="control-group">
						<label class="control-label">Select Customer :</label>
						<div class="controls">
							<!--select id="myselect" name="client_name">
								<option value="ABC Corp">ABC Corp</option>
								<option value="Ram Industries">Ram Industries</option>
								<option value="XYZ Ind">XYZ Ind</option>
							</select-->
						
							<?php $query = "SELECT * FROM party_master";
	                                $result = mysqli_query($dbc,$query);
	                                if(mysqli_num_rows($result)>0){
	                                    echo '<select name="party_master_id" id="party_master_id" class="form-control required" onchange="customer_change();">';
	                                    echo '<option value="" selected="selected">Select Customer</option>';
	                                    while($row=mysqli_fetch_array($result)){
	                                        echo '<option value="'.$row['party_master_id'].'">'.$row['customer_name'].'</option>';
	                                    }
	                                    echo '</select>';
	                                }
	                        ?>  
						</div>	
					</div>
					<div class="control-group">
						<label class="control-label">Select Location :</label>
						<div class="controls">
							<?php /*$query = "SELECT * FROM party_location";
	                                $result = mysqli_query($dbc,$query);
	                                if(mysqli_num_rows($result)>0){
	                                    echo '<select name="party_location_id" id="party_location_id" class="form-control required" onchange="location_change();">';
										echo '<option value="" selected="selected">Select Location</option>';
	                                    while($row=mysqli_fetch_array($result)){
	                                        echo '<option value="'.$row['party_location_id'].'">'.$row['location_name'].'</option>';
	                                    }
	                                    echo '</select>';
	                                }*/
	                        ?>
	                        <select name="party_location_id" id="party_location_id" class="form-control required" onchange="location_change();">
	                            <option value="">Select Location</option>
	                        </select>	
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Department :</label>
						<div class="controls">
							<select name="department_id" id="department_id" class="form-control required" onchange="department_change();">
								<option value="">Select Department</option>
							</select>
						</div>
					</div>
				</div>
				<div id="sales_div" style="display: none;">
					<div class="control-group">
						<label class="control-label">Customer Name:</label>
						<div class="controls">
							<input type="text" class="form-control required span11" id="customer_name" name="customer_name" placeholder="Customer Name" value=""/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Customer Address :</label>
						<div class="controls">
							<input type="text" class="form-control required span11" id="customer_address" name="customer_address" placeholder="Customer Address" value=""/>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Contact Person :</label>
					<div class="controls">
						<input type="text" class="form-control required span11" id="contact_person" name="contact_person" placeholder="Contact Person" value=""/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Contact Number :</label>
					<div class="controls">
						<input type="text" class="form-control required span11" id="contact_number" name="contact_number" placeholder="Contact Number" value=""/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Remarks :</label>
					<div class="controls">
						<input type="text" class="form-control  span11" id="remark" name="remark" placeholder="Remark" value=""/>
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="add_ticket_details();">Call Register</button>
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
g_deptdata = new Array;
$(document).ready(function(){
    $('#addticket_form').validate({
    	rules: { 
            "call_type[]": { 
                    required: true, 
                    minlength: 1 
            } 
	    }, 
	    messages: { 
	            "call_type[]": "Please select at least one call type."
	    } 
    });
    $('#li_sc_ticketmenu').addClass('active open');

    $('input[type=radio][name=customer_type]').change(function() {
        if (this.value == 'existing') {
            $('#sales_div').hide();
			$('#service_div').show();
        }
        else if (this.value == 'new') {
            $('#service_div').hide();
			$('#sales_div').show();
			$('#contact_person').val('');
			$('#contact_number').val('');
        }
    });
});

$(window).bind("load", function() {
    
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_ticket_details(){
    if($('#addticket_form').valid()){
        var data = $('#addticket_form').serialize();
        $.ajax({
            url: "callservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "REGISTERCALLADDED"){
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

function customer_change(){
    party_master_id = $('#party_master_id').val();
    var dp='';
    $.ajax({
        url: "generalservice.php",
        type: "POST",
        data:  'party_master_id='+party_master_id+'&action=customer_change',
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
                    //$('#department_id').removeAttr('disabled');
                }else{
                    $('#party_location_id').html('<option value="">No Location added</option>');
                    //$('#department_id').attr('disabled', 'disabled');
                }
            }else{
                //bootbox.alert(result.message);
                $('#party_location_id').html('<option value="">No Location added</option>');
                //$('#department_id').attr('disabled', 'disabled');
            }
        },
        error: function(){}             
    });
    
}
function location_change(){
	party_location_id = $('#party_location_id').val();
    var dp='';
    $.ajax({
        url: "generalservice.php",
        type: "POST",
        data:  'party_location_id='+party_location_id+'&action=location_change',
        dataType: 'json',
        success: function(result){
            if(result.infocode == "DEPARTMENTDATARETREIVED"){
                data = result.department_data;
                g_deptdata = result.department_data;
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

function department_change(){
	department_id = $('#department_id').val();
    for(c=0; c<g_deptdata.length;c++){
		if(g_deptdata[c].department_id == department_id){
			$('#contact_person').val(g_deptdata[c].contact_person);
			$('#contact_number').val(g_deptdata[c].contact_number);
		}
    }
    
}

function calltypechange(){
	call_type = $('#call_type').val();
	//call_type = $('#customer_type').();
	if(call_type == 'sales'){
		$('#service_div').hide();
		$('#sales_div').show();
		$('#contact_person').val('');
		$('#contact_number').val('');
	}else{
		$('#sales_div').hide();
		$('#service_div').show();
	}
}
</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>