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
					<label class="control-label">Select Location :</label>
					<div class="controls">
						<select id="myselect" name="location_name">
							<option value="Chennai">Chennai</option>
							<option value="Bangalore">Bangalore</option>
							<option value="Hyderabad">Hyderabad</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Select Product :</label>
					<div class="controls">
						<select id="myselect" name="product">
							<option value="Canon">Canon</option>
							<option value="GBC">GBC</option>
							<option value="Lexmark">Lexmark</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Nature of Complaint :</label>
					<div class="controls">	
						<select id="myselect" name="complaint_name">
							<option value="Paper Jam">Paper Jam</option>
							<option value="Not powering on">Not powering on</option>
							<option value="Others">Others</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Additional Details :</label>
					<div class="controls">
						<input type="text" class="span11 required" id="additional_details" name="additional_details" placeholder="Additional Details" value=""/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Machine Reading :</label>
					<div class="controls">
						<input type="text" class="span11 required" id="machine_reading" name="machine_reading" placeholder="Machine Reading" value=""/>
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
            url: "ticketservices.php",
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


</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>