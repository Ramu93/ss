<?php session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}
include('../header.php');
include('../topbar.php');
include('../sidebar.php');
include('../dbconfig.php');
?>
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Party Master View</h5>
			<div style="float: right;">
							<?php 
								$select_query = "SELECT * FROM party_master";
								$result = mysqli_query($dbc,$select_query);
								$row_counter = 0;
								if(mysqli_num_rows($result) > 0) {
									while($row = mysqli_fetch_array($result)) {
										echo "<tr>";
											echo '<td><button class="btn btn-success"  onclick="close_ticket(\''.$row['party_master_id'].'\')">Add</button></td>';
										echo "</tr>";
									}
								}
							?>
			</div>
        </div>
        <div class="widget-content nopadding 
			<table id="spare_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        			<table id="party_master_list_data" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
							    <th>Party Master Id</th>
                                <th>Customer Name</th>
                                <th>City</th>
                                <th>State</th>
                                <!--th>FAX</th>
                                <th>Sales</th>
                                <th>Service Tax</th>
                                <th>Licence</th>
                                <th>Tin</th>
                                <th>PAN</th>
                                <th>Inactive</th-->
                                <th>Primary Contact</th>
                                <th>Primary Mobile</th>
                                <th>Primary Email</th>
                                <th>Credit Days</th>
                                <th>Credit Limit</th>
                                <th>Opening Balance</th>
                                <th>Created date</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
						    <?php 
								$select_query = "SELECT * FROM party_master";
								$result = mysqli_query($dbc,$select_query);
								$row_counter = 0;
								if(mysqli_num_rows($result) > 0) {
									while($row = mysqli_fetch_array($result)) {
										echo "<tr>";
											echo "<td>".++$row_counter."</td>";

											echo "<td>".$row['customer_name']."</td>";
											echo "<td>".$row['city_town']."</td>";
											echo "<td>".$row['state']."</td>";
											echo "<td>".$row['primary_contact_name']."</td>";
											echo "<td>".$row['primary_contact_mobile']."</td>";
											echo "<td>".$row['primary_contact_email']."</td>";
											echo "<td>".$row['credit_days']."</td>";
											echo "<td>".$row['credit_limit']."</td>";
											echo "<td>".$row['opening_balance']."</td>";
											echo "<td>".$row['created_date']."</td>";
											echo "<td><a href='party_edit.php?party_master_id={$row['party_master_id']}'>View</a></td>";
									
										echo "</tr>";
									}
								}else{
									echo '<tr><td colspan="12">No tickets awaiting to be assigned</td></tr>';
								}
							?>

                        </tbody>
                    </table>
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
</style>
<div class="modal fade" id="modal_close_ticket" role="dialog" style="width: 750px;">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Party Master</h4>
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
				<button type="submit" class="btn btn-success" onclick="add_party_details();">Submit Request</button>
			</div>
				  <input type="hidden" name="action" id="action" value="add_party"/>
        </div>
		</form>
      </div>
    </div>
</div>
<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
    
$(document).ready(function() {
    //$('#party_master_list_data').DataTable();
    fetch_partymaster();
});

$(document).ready(function() {
    $('#party_list').DataTable();
    $('#sb_ul_mastermenu').addClass('in');
});
function close_ticket(party_master_id){
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
</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>