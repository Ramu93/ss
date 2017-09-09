<?php 
session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}

$userid = $_SESSION['userid'];
include('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
include('..'.DIRECTORY_SEPARATOR.'header.php');
include('..'.DIRECTORY_SEPARATOR.'topbar.php');
include('..'.DIRECTORY_SEPARATOR.'sidebar.php');

$call_status = isset($_GET['call_status'])?$_GET['call_status']:'created';
?>
<style type="text/css">
.modal.large {
    width: 60%; /* respsonsive width */
    margin-left:-30%; /* width/2) */ 
}
.datepicker {
	z-index: 9999;
}
</style>

<!--main-container-part-->
<div id="content">
	<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
	<!--End-breadcrumbs-->
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span4">
			<div class="form-control">
				<label class="form-control">Call Status : </label>
					<select name="call_status" id="call_status" class="form-control" onchange="status_change();">
						<option value="created" <?php echo ($call_status == 'created')?'selected="selected"':''; ?> >Unattended</option>
						<option value="assigned" <?php echo ($call_status == 'closed' || $call_status == 'ticketcreated' || $call_status == 'assigned')?'selected="selected"':''; ?> >Attended</option>
					</select>
				
			</div>
		</div>
	</div>

	<div class="widget-box widget-box-90">

        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Inbound Calls</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="ticket_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Call ID</th>
						<th>Customer</th>
                        <th>Location</th>
						<th>Action</th>
                    </tr>
                </thead>
                <tbody id="call_list_tbody">
                    <?php 	if($call_status == 'assigned') $call_status2 = "ticketcreated','closed";
                    		else $call_status2 = 'created';
                        $select_query = "SELECT a.* , b.customer_name, c.location_name 
                        FROM inbound_call a, party_master b, party_location c
                        WHERE a.call_status IN ('$call_status2') AND a.call_type LIKE '%service%' AND a.sc_id = $userid AND a.party_master_id = b.party_master_id AND a.party_location_id = c.party_location_id";
                        //$select_query = "SELECT * FROM raise_ticket WHERE ticket_status = '$ticket_status'";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        $datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
                        	$datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".$row['inbound_call_id']."</td>";
									echo "<td>".$row['customer_name']."</td>";
                                    echo "<td>".$row['location_name']."</td>";
									echo '<td><button type="button" class="btn btn-primary btn-lg" onclick="view_call_detail(\''.$row['inbound_call_id'].'\');">View Details</button>&nbsp;';
                                echo "</tr>";
                            }
                        }else{
                        	echo '<tr><td colspan="4">No inbound calls in this category</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
		</div>
	</div>
</div>
<!--end-main-container-part-->
<div class="modal fade large" id="process_call_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Call Details</h4>

            </div>
            <div class="modal-body">
                <div role="tabpanel">
                    <!-- Nav tabs -->
					<div class="widget-title">
					<ul class="nav nav-tabs">
						<li class="active" id="li_createticket"><a data-toggle="tab" href="#tab_createticket">Create Ticket</a></li>
						<li id="li_closecall"><a data-toggle="tab" href="#tab_closecall">Close Call</a></li>
						<li id="li_callstatus" style="display: none;"><a data-toggle="tab" href="#tab_callstatus">Call Status</a></li>
					</ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content" style="padding-top: 15px;">
                        <div role="tabpanel" class="tab-pane active" id="tab_createticket">
                        	<div id="unattended_call_div" style="display: none;">
								<form id="addticket_form" name="addticket_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
									<div class="control-group">
										<label class="control-label"> Customer :</label>
										<div class="controls" id="customer_div">
										</div>		
									</div>
									<div class="control-group">
										<label class="control-label"> Location :</label>
										<div class="controls" id="location_div">
											<!--select name="party_location_id" id="party_location_id" class="form-control required" onchange="location_change();">
					                            <option value="">Select Location</option>
					                        </select-->	
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Department :</label>
										<div class="controls" id="dept_div">
											<!--select name="department_id" id="department_id" class="form-control required" onchange="product_change();">
												<option value="">Select Department</option>
											</select-->
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Select Product :</label>
										<div class="controls">
											<select name="product_master_id" id="product_master_id" class="form-control required" onchange="">
												<option value="">Select Product</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Nature of Complaint :</label>
										<div class="controls">
											<?php $query = "SELECT * FROM  nature_of_comp";
					                                $result = mysqli_query($dbc,$query);
					                                if(mysqli_num_rows($result)>0){
					                                    echo '<select name="complaint_master_id" id="complaint_master_id" class="form-control required" onchange="">';
														echo '<option value="" selected="selected">Select Complaint</option>';
					                                    while($row=mysqli_fetch_array($result)){
					                                        echo '<option value="'.$row['NCMPLTID'].'">'.$row['complaint_name'].'</option>';
					                                    }
					                                    echo '</select>';
					                                }
					                        ?>
										</div>
									</div>
					                <div class="control-group">
					                    <label class="control-label">Machine Reading :</label>
					                    <div class="controls">
					                        <input type="text" class="span11" id="machine_reading" name="machine_reading" placeholder="Machine Reading" value=""/>
					                    </div>
					                </div>
									<div class="control-group">
										<label class="control-label">Additional Details :</label>
										<div class="controls">
											<input type="text" class="span11" id="additional_details" name="additional_details" placeholder="Additional Details" value=""/>
										</div>
									</div>
									<div class="form-actions">
										<button type="submit" class="btn btn-success" onclick="create_ticket();">Create Ticket</button>
									</div>
									<input type="hidden" name="action" id="action" value="add_ticket"/>
									<input type="hidden" name="party_master_id" id="party_master_id" value=""/>
									<input type="hidden" name="party_location_id" id="party_location_id" value=""/>
									<input type="hidden" name="department_id" id="department_id" value=""/>
									<input type="hidden" name="call_id" id="call_id" value=""/>
									<input type="hidden" name="call_mode" id="call_mode" value="from_ticket"/>
								</form>
							</div>
							<div id="attended_call_div" style="display: none;">
								
							</div>
						</div>
                       	<div role="tabpanel" class="tab-pane" id="tab_closecall">
                       		<div id="close_call_div" style="">
								<label class="control-label">Remarks:</label>
								<div class="controls">
									<div  class="input-append">
										<input class="span22" type="text" id="closing_remarks" placeholder="Closing Remarks">
										<!--span class="add-on"><i class="icon-th"></i></span--> 
									</div>
								</div>
								<button type="button" class="btn btn-success" onclick="sc_close_call();" style="margin: 5px 0 5px 0;">Close Call</button>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="tab_callstatus">
							<div id="call_status_div"></div>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            	<span class="align-left" id="result_msg"></span>
            	<input class="form-control" name="h_call_id" id="h_call_id" type="hidden">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--button type="button" class="btn btn-primary save">Save changes</button-->
            </div>
        </div>
    </div>
</div>

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>

<script type="text/javascript">
//write all js here	
var g_call_status = '<?php echo $call_status; ?>';
var g_call_status2 = "<?php echo $call_status2; ?>";
$(document).ready(function() {
	<?php if($datatableflag){ ?>
    $('#ticket_list').DataTable();
    <?php } ?>
    $('#li_sc_ticketmenu').addClass('active open');
    $('#visit_date').datepicker();
});


</script>
<script type="text/javascript" src="js/inbound_call.js"></script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>