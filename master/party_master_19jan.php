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

	<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Customer Master</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="add_party" name="add_party" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group span4">
					<label class="control-label">Customer Name :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="customer_name" id="customer_name" placeholder="Customer Name" />	
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">Address1 :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="address1" id="address1" placeholder="Address1" />
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">Address2 :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="address2" id="address2" placeholder="Address2" />
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">City :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="city_town" id="city_town" placeholder="City" />
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">State :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="state" id="state" placeholder="State" />
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">PIN :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="pincode" id="pincode" placeholder="PIN" />
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">Landline :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="landline" id="landline" placeholder="Landline" />
					</div>
				</div>
				<div class="clearfix"></div><hr>
				<div class="control-group span6">
					<label class="control-label">Primary Contact :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="primary_contact_name" id="primary_contact_name" placeholder="Primary Contact" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Primary Contact Mobile :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="primary_contact_mobile" id="primary_contact_mobile" placeholder="Primary Contact Mobile" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Primary Contact Email :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="primary_contact_email" id="primary_contact_email" placeholder="Primary Contact Email" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Secondary Contact :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="secondary_contact_name" id="secondary_contact_name" placeholder="Secondary Contact" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Secondary Contact Mobile:</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="secondary_contact_mobile" id="secondary_contact_mobile" placeholder="Secondary Contact Mobile" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Secondary Contact Email :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="secondary_contact_email" id="secondary_contact_email" placeholder="Secondary Contact Email" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">PAN :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="pan_number" id="pan_number" placeholder="PAN" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Sales tax/GST :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="salestax_number" id="salestax_number" placeholder="Sales tax/GST" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Service tax regn :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="servicetax_number" id="servicetax_number" placeholder="Service tax regn" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Credit Days :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="credit_days" id="credit_days" placeholder="Credit Days" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Credit Limit :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="credit_limit" id="credit_limit" placeholder="Credit Limit" />
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Opening Balance :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="opening_balance" id="opening_balance" placeholder="Opening Balance" />
					</div>
				</div>
				<div class="control-group row-fluid" style="padding-bottom: 10px;">
                    <div class="span6 text-right">
					   <button type="submit" class="btn btn-success" onclick="add_party_details();">Add Customer</button>
                    </div>
                    <div class="span6">
                       <button type="button" class="btn btn-primary" onclick="window.location = 'party_view.php';">View Customer</button>
                    </div>
				</div>
				<input type="hidden" name="action" id="action" value="add_party"/>
			</form>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function(){
	//$('#pm_form').validate();
	$('#li_sc_ticketmenu').addClass('active open');
	$('#pm_inactive').datepicker({dateFormat: 'yy-mm-dd'});
});

function changepartytype(partytype){
	if(partytype == 'serviceprovider'){
		$('#sp_div').show();
		$('#sp_div2').hide();
	}else if(partytype == 'principalclient'){
		$('#sp_div2').show();
		$('#sp_div').hide();
	}else{
		$('#sp_div').hide();
		$('#sp_div2').hide();
	}
}

$(document).ready(function(){
    $('#add_party').validate();
    $('#sb_ul_mastermenu').addClass('in');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_party_details(){
    if($('#add_party').valid()){
        var data = $('#add_party').serialize();
        $.ajax({
            url: "partyservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "PARTYADDED"){
                    bootbox.alert(result.message);
                    $('#add_party')[0].reset();
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