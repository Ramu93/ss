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

$party_master_id = 0;
if(isset($_GET['party_master_id'])){
    $party_master_id = $_GET['party_master_id'];
}else{
    header('Location: party_view.php'); exit();
}

$query = "SELECT * FROM party_master WHERE party_master_id = $party_master_id";
$result = mysqli_query($dbc, $query);
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);
}else{
    header('Location: party_view.php'); exit();
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

	<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Edit Customer</h5>
		  <div style="text-align: right;"><!--button type="submit" class="btn btn-success" onclick="add_partylocation();">Add Party Location</button--></div>
        </div>
        <div class="widget-content nopadding">
			<form id="edit_party" name="edit_party" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group span4">
					<label class="control-label">Customer Name :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="customer_name" id="customer_name" placeholder="Customer Name" value="<?php echo $row['customer_name']; ?>"/>	
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">Address1 :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="address1" id="address1" placeholder="Address1" value="<?php echo $row['address1']; ?>" />
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">Address2 :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="address2" id="address2" placeholder="Address2" value="<?php echo $row['address2']; ?>"/>
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">City :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="city_town" id="city_town" placeholder="City" value="<?php echo $row['city_town']; ?>"/>
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">State :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="state" id="state" placeholder="State" value="<?php echo $row['state']; ?>"/>
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">PIN :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="pincode" id="pincode" placeholder="PIN" value="<?php echo $row['pincode']; ?>"/>
					</div>
				</div>
				<div class="control-group span4">
					<label class="control-label">Landline :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="landline" id="landline" placeholder="Landline" value="<?php echo $row['landline']; ?>"/>
					</div>
				</div>
				<div class="clearfix"></div><hr>
				<div class="control-group span6">
					<label class="control-label">Primary Contact :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="primary_contact_name" id="primary_contact_name" placeholder="Primary Contact" value="<?php echo $row['primary_contact_name']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Primary Contact Mobile :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="primary_contact_mobile" id="primary_contact_mobile" placeholder="Primary Contact Mobile" value="<?php echo $row['primary_contact_mobile']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Primary Contact Email :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="primary_contact_email" id="primary_contact_email" placeholder="Primary Contact Email" value="<?php echo $row['primary_contact_email']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Secondary Contact :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="secondary_contact_name" id="secondary_contact_name" placeholder="Secondary Contact" value="<?php echo $row['secondary_contact_name']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Secondary Contact Mobile:</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="secondary_contact_mobile" id="secondary_contact_mobile" placeholder="Secondary Contact Mobile" value="<?php echo $row['secondary_contact_mobile']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Secondary Contact Email :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="secondary_contact_email" id="secondary_contact_email" placeholder="Secondary Contact Email" value="<?php echo $row['secondary_contact_email']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">PAN :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="pan_number" id="pan_number" placeholder="PAN" value="<?php echo $row['pan_number']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Sales tax/GST :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="salestax_number" id="salestax_number" placeholder="Sales tax/GST" value="<?php echo $row['salestax_number']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Service tax regn :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="servicetax_number" id="servicetax_number" placeholder="Service tax regn" value="<?php echo $row['servicetax_number']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Credit Days :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="credit_days" id="credit_days" placeholder="Credit Days" value="<?php echo $row['credit_days']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Credit Limit :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="credit_limit" id="credit_limit" placeholder="Credit Limit" value="<?php echo $row['credit_limit']; ?>"/>
					</div>
				</div>
				<div class="control-group span6">
					<label class="control-label">Opening Balance :</label>
					<div class="controls">
                        <input type="text" class="form-control required" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="<?php echo $row['opening_balance']; ?>"/>
					</div>
				</div>
                <div class="modal-footer">
                    <div class="col-lg" style="text-align: center;">
                        <button type="button" class="btn btn-primary" onclick="update_party_details();">Update Customer</button>
						
                    </div>

                </div>
				<div class="clearfix"></div>
                    <input type="hidden" name="action" id="action" value="edit_party"/>
                    <input type="hidden" name="party_master_id" id="party_master_id" value="<?php echo $party_master_id; ?>"/>
            </form>
		</div>
    </div>
</div>

<style>
.modal-body.span4 {
    width: 345px;
    padding: 0px;
}
.modal.fade.in {
    top: 0%;
}
.modal-content .form-horizontal .control-label {
    padding-top: 6px;
    width: 180px;
    text-align: left;
}
</style>
<div class="modal fade" id="modal_close_ticket" role="dialog" style="width: 750px;">
    <div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Party Master</h4>
			</div>
			<form id="add_party" name="add_party" method="post" class="form-horizontal" action="" onsubmit="return false;">
			<div class="modal-body span4">
				<label class="control-label">Customer Name :</label>
					<input type="text" class="form-control required" name="customer_name" id="customer_name" placeholder="Customer Name" />		
			</div>
			<div class="modal-body span4">
				<label class="control-label">Address1 :</label>
					<input type="text" class="form-control required" name="address1" id="address1" placeholder="Address1" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Address2 :</label>
					<input type="text" class="form-control required" name="address2" id="address2" placeholder="Address2" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">City :</label>
					<input type="text" class="form-control required" name="city_town" id="city_town" placeholder="City" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">State :</label>
					<input type="text" class="form-control required" name="state" id="state" placeholder="State" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">PIN :</label>
					<input type="text" class="form-control required" name="pincode" id="pincode" placeholder="PIN" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Landline :</label>
					<input type="text" class="form-control required" name="landline" id="landline" placeholder="Landline" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Primary Contact :</label>
					<input type="text" class="form-control required" name="primary_contact_name" id="primary_contact_name" placeholder="Primary Contact" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Primary Contact Mobile :</label>
					<input type="text" class="form-control required" name="primary_contact_mobile" id="primary_contact_mobile" placeholder="Primary Contact Mobile" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Primary Contact Email :</label>
					<input type="text" class="form-control required" name="primary_contact_email" id="primary_contact_email" placeholder="Primary Contact Email" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Secondary Contact :</label>
					<input type="text" class="form-control required" name="secondary_contact_name" id="secondary_contact_name" placeholder="Secondary Contact" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Secondary Contact Mobile:</label>
					<input type="text" class="form-control required" name="secondary_contact_mobile" id="secondary_contact_mobile" placeholder="Secondary Contact Mobile" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Secondary Contact Email :</label>
					<input type="text" class="form-control required" name="secondary_contact_email" id="secondary_contact_email" placeholder="Secondary Contact Email" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">PAN :</label>
					<input type="text" class="form-control required" name="pan_number" id="pan_number" placeholder="PAN" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Sales tax/GST :</label>
					<input type="text" class="form-control required" name="salestax_number" id="salestax_number" placeholder="Sales tax/GST" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Service tax regn :</label>
					<input type="text" class="form-control required" name="servicetax_number" id="servicetax_number" placeholder="Service tax regn" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Credit Days :</label>
					<input type="text" class="form-control required" name="credit_days" id="credit_days" placeholder="Credit Days" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Credit Limit :</label>
					<input type="text" class="form-control required" name="credit_limit" id="credit_limit" placeholder="Credit Limit" />
			</div>
			<div class="modal-body span4">
				<label class="control-label">Opening Balance :</label>
					<input type="text" class="form-control required" name="opening_balance" id="opening_balance" placeholder="Opening Balance" />
			</div>
			<div class="modal-footer" style="text-align: center;">
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="add_party_details();" style="margin: 5px 0px 5px 0px;">Update Party</button>
				</div>
					  <input type="hidden" name="action" id="action" value="add_party"/>
			</div>
		</div>
    </div>
</div>
<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#edit_party').validate();
    $('#sb_ul_mastermenu').addClass('in');
    $('#li_sc_ticketmenu').addClass('active open');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function update_party_details(){
    if($('#edit_party').valid()){
        var data = $('#edit_party').serialize();
        $.ajax({
            url: "partyservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "PARTYUPDATED"){
                    bootbox.alert(result.message, function(){
                        window.location = 'party_view.php';
                    });
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}
function add_partylocation(party_master_id){
	$('#h_party_master_id').val(party_master_id);
	$('#modal_close_ticket').modal('show');
}
function sc_add_party(party_master_id){
    party_master_id = $('#h_party_master_id').val();
    customer_name = $('#customer_name').val();
	data = 'party_master_id='+party_master_id+'&customer_name='+customer_name+'&action=sc_add_party';
	$.ajax({
		url: "partyservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "PARTYUPDATED"){
				bootbox.alert(result.message, function(){
					location.reload();
				});
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
    
}
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