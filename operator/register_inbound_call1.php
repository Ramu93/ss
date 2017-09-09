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
          <h5>Inbound Call</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="addcall_form" name="addcall_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Call Type :</label>
					<div class="controls">
						<select id="myselect" name="call_type">
							<option value="sales">Sales</option>
							<option value="service">Service</option>
						</select>
					</div>
				</div>
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
					<label class="control-label">Remark :</label>
					<div class="controls">
						<input type="text" class="form-control required span11" id="remark" name="remark" placeholder="Remark" value=""/>
					</div>
				</div>	
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="add_ticket_details();">Call Register</button>
					<input type="hidden" name="action" id="action" value="add_call"/>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function(){
    $('#addcall_form').validate();
    $('#li_sc_ticketmenu').addClass('active open');
});

$(window).bind("load", function() {
    
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_ticket_details(){
    if($('#addcall_form').valid()){
        var data = $('#addcall_form').serialize();
        $.ajax({
            url: "register_inbound_call_services.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "REGISTERCALLADDED"){
                    bootbox.alert(result.message);
                    $('#addcall_form')[0].reset();
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