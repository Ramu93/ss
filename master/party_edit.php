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
.modal.large {
    width: 60%; /* respsonsive width */
    margin-left:-30%; /* width/2) */ 
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
		  <div style="text-align: right;"><button type="button" class="btn btn-success" onclick="show_partylocation();">Add Customer Location</button></div>
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
<!--end-main-container-part-->

<div class="modal fade large" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Location & Department</h4>

            </div>
            <div class="modal-body">
                <div role="tabpanel">
                    <!-- Nav tabs -->
					<div class="widget-title">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#tab1">Customer Location</a></li>
						<li><a data-toggle="tab" href="#tab2">Department</a></li>
					</ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tab1">
                        	<form id="addpartylocation_form" name="addpartylocation_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
                        		<div class="span4">
									<label>Customer Name</label>
										<?php echo $row['customer_name']; ?>
								</div><div class="clearfix"></div>
								<div id="add_location_div">
									<div class="span3">
										<label>Location Name :</label>
										<input class="form-control required" name="location_name" id="location_name" type="text" placeholder="Location Name">
									</div>
									<!--div class="span3">
										<label>Address :</label>
										<input class="form-control required" name="address" id="address" type="text" placeholder="Address">
									</div-->
									<div class="span3">
										<label>Service Coordinator :</label>
										<?php 
										$query5 = "SELECT * FROM employee_master WHERE rolename='sc'";
										$result5 = mysqli_query($dbc,$query5);
										if(mysqli_num_rows($result5)>0){
											echo '<select name="assign_sc_id" id="assign_sc_id" class="form-control required" onchange="">';
											echo '<option value="" selected="selected">Select Coordinator</option>';
											while($row5=mysqli_fetch_array($result5)){
												echo '<option value="'.$row5['employee_master_id'].'">'.$row5['employee_name'].'</option>';
											}
											echo '</select>';
										}
										?>
									</div>
									<div class="span3" ><label></label>
										<input type="hidden" name="action" id="action" value="add_partylocation"/>
										<input type="hidden" name="h_party_master_id" id="h_party_master_id" value="<?php echo $party_master_id; ?>"/>
										<button type="button" class="btn btn-success" onclick="add_partylocation_details();">Add Location</button>
									</div>
								</div><!--/add_location_div-->
								<div id="edit_location_div" style="display: none;">
									<div class="span3">
										<label>Location Name :</label>
										<input class="form-control required" name="location_name_edit" id="location_name_edit" type="text" placeholder="Location Name">
									</div>
									<!--div class="span3">
										<label>Address :</label>
										<input class="form-control required" name="address" id="address" type="text" placeholder="Address">
									</div-->
									<div class="span3">
										<label>Service Coordinator :</label>
										<?php 
										$query5 = "SELECT * FROM employee_master WHERE rolename='sc'";
										$result5 = mysqli_query($dbc,$query5);
										if(mysqli_num_rows($result5)>0){
											echo '<select name="assign_sc_id_edit" id="assign_sc_id_edit" class="form-control required" onchange="">';
											echo '<option value="" selected="selected">Select Coordinator</option>';
											while($row5=mysqli_fetch_array($result5)){
												echo '<option value="'.$row5['employee_master_id'].'">'.$row5['employee_name'].'</option>';
											}
											echo '</select>';
										}
										?>
									</div>
									<div class="span3" ><label></label>
									<input type="hidden" name="h_party_location_id" id="h_party_location_id" value=""/>
										<button type="button" class="btn btn-warning" onclick="update_partylocation_details();">Update Location</button>
										<button type="button" class="btn btn-danger" onclick="cancel_location_update();">Cancel</button>
									</div>
								</div><!--/add_location_div-->
							</form>
							
							<div class="widget-box widget-box-90">
								<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
								  <h5>Location List</h5>
								</div>
								<div class="widget-content nopadding">
									
									<table id="party_master_list_data" class="table table-striped table-bordered" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Location Name</th>
												<!--th>Address</th-->
												<th>Coordinator</th>
												<th>View</th>
											</tr>
										</thead>
										<tbody id="location_data_tbody">
											<?php 
												$select_query2 = "SELECT a.*,b.employee_name as sc_name FROM party_location a, employee_master b WHERE a.party_master_id = $party_master_id AND a.sc_id = b.employee_master_id";
												$result2 = mysqli_query($dbc,$select_query2);
												$row_counter = 0;
												if(mysqli_num_rows($result2) > 0) {
													while($row2 = mysqli_fetch_array($result2)) {
														$key = $row2['party_location_id'];
														echo "<tr>";
														echo "<td>".$row2['location_name']."</td>";
														//echo "<td>".$row2['address']."</td>";
														echo "<td>".ucfirst($row2['sc_name'])."</td>";
														echo '<td><button type="button" class="btn btn-warning" onclick="edit_location(\''.$row2['party_location_id'].'\');">Edit</button></td>';
														//echo '<td><button type="button" class="btn btn-danger" onclick="delete_location(\''.$row2['party_location_id'].'\');">Delete</button></td>';
														echo '<input type="hidden" id="e_party_location_id_'.$key.'" value="'.$row2['party_location_id'].'">';
														echo '<input type="hidden" id="e_sc_id_'.$key.'" value="'.$row2['sc_id'].'">';
														echo '<input type="hidden" id="e_location_name_'.$key.'" value="'.$row2['location_name'].'">';
														echo "</tr>";
													}
												}else{
													echo '<tr><td colspan="4">No Location added for this Customer</td></tr>';
												}
											?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
						
                        <div role="tabpanel" class="tab-pane" id="tab2">
							<form id="adddepartment_form" name="adddepartment_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
								<div class="span3">
									<label>Customer Name</label>
										<?php echo $row['customer_name']; ?>
								</div>
								<div class="span3">
									<label>Location Name</label>
										<?php $init_party_location_id = 0;
												$query3 = "SELECT * FROM party_location WHERE party_master_id = $party_master_id";
												echo '<select name="dept_party_location_id" id="dept_party_location_id" class="form-control required" onchange="show_department_list();">';
												$result3 = mysqli_query($dbc,$query3);
												if(mysqli_num_rows($result3)>0){
													$tmpcount=1;
													while($row3 = mysqli_fetch_array($result3)){
														if($tmpcount == 1){ 
															$init_party_location_id = $row3['party_location_id']; 
															$selected = 'selected="selected"'; 
														}else{
															$selected = '';
														}
														echo '<option value="'.$row3['party_location_id'].'" '.$selected.'>'.$row3['location_name'].'</option>';
														$tmpcount ++;
													}
												}else{
													echo '<option value="">No Location available</option>';
												}
												echo '</select>';
										?>
								</div>
								<div class="span3">
									<label>Department Name</label>
									<input class="form-control required" name="department_name" id="department_name" type="text" placeholder="Department Name">
								</div>
								<div class="span3">
									<label>Contact Person</label>
									<input class="form-control" name="contact_person" id="contact_person" type="text" placeholder="Contact Person">
								</div>
								<div class="span3">
									<label>Contact No</label>
									<input class="form-control" name="contact_number" id="contact_number" type="text" placeholder="Contact No">
								</div>
								<div class="span3" style="">
									<input type="hidden" name="action" id="action" value="add_department"/>
									<input type="hidden" name="dept_party_master_id" id="dept_party_master_id" value="<?php echo $party_master_id; ?>"/>
									<button type="button" class="btn btn-success" onclick="add_department_details();">Add Department</button>
								</div>
							</form>
							<div class="widget-box widget-box-90">
								<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
								  <h5>Department List</h5>
								</div>
								<div class="widget-content nopadding">
									<table id="party_master_list_data" class="table table-striped table-bordered" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Department Name</th>
												<th>Contact Person</th>
												<th>Contact No</th>
												<th>View</th>
											</tr>
										</thead>
										<tbody id="department_list_tbody">
											<?php 
												$select_query6 = "SELECT * FROM department WHERE party_location_id = $init_party_location_id";
												$result6 = mysqli_query($dbc,$select_query6);
												$row_counter = 0;
												if(mysqli_num_rows($result6) > 0) {
													while($row6 = mysqli_fetch_array($result6)) {
														echo "<tr>";
														echo "<td>".$row6['department_name']."</td>";
														echo "<td>".$row6['contact_person']."</td>";
														echo "<td>".$row6['contact_number']."</td>";
														echo '<td><button type="button" class="btn btn-danger" onclick="delete_department(\''.$row6['department_id'].'\');">Delete</button></td>';
														echo "</tr>";
													}
												}else{
													echo '<tr><td colspan="4">No Department added in this location</td></tr>';
												}
											?>

										</tbody>
									</table>
								</div>
							</div>

						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript" src="js/party_edit.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#edit_party').validate();
    $('#addpartylocation_form').validate();
    $('#adddepartment_form').validate();
    $('#sb_ul_mastermenu').addClass('in');
    $('#li_sc_ticketmenu').addClass('active open');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});


</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>