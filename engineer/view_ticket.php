<?php 

session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}

$engr_id = $_SESSION['userid'];
include('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
include('..'.DIRECTORY_SEPARATOR.'header.php');
include('..'.DIRECTORY_SEPARATOR.'topbar.php');
include('..'.DIRECTORY_SEPARATOR.'sidebar.php');

function engineer_list($ticket_id){
	global $dbc;$op = '';
	$query = "SELECT * FROM engineer";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$op .= '<select name="select_'.$ticket_id.'" id="select_'.$ticket_id.'" class="form-control" style="width:90%;">';
		while($row = mysqli_fetch_assoc($result)){
			$op .= '<option value="'.$row['engineer_id'].'">'.$row['engineer_name'].'</option>';
		}
		$op .= '</select>';
	}
	return $op;
}

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

	<div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>View Tickets</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="ticket_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
						<th>Client</th>
                        <th>Location</th>
						<th>Product</th>
						<th>Nature Complaint</th>
						<!--th>Closed by</th-->
                        <th>Action</th>
						
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        //$select_query = "SELECT a . * , b.engineer_name FROM raise_ticket a, engineer b WHERE ticket_status IN ('assigned', 'closed') AND a.engineer_id = b.engineer_id";
                        $select_query = "SELECT a.* , b.customer_name, c.location_name, e.item_name, f.complaint_name
                        FROM raise_ticket a, party_master b, party_location c, asset_master d, item_master e, nature_of_comp f
                        WHERE a.ticket_status IN ('assigned', 'engineerclosed', 'sparespending') AND a.engineer_id = $engr_id AND a.client_id = b.party_master_id AND a.location_id = c.party_location_id AND a.asset_master_id = d.asset_master_id AND d.item_master_id = e.item_master_id AND a.complaint_id = f.NCMPLTID";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        $datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
                        	$datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".$row['ticket_id']."</td>";
									echo "<td>".$row['customer_name']."</td>";
                                    echo "<td>".$row['location_name']."</td>";
									echo "<td>".$row['item_name']."</td>";	
									echo "<td>".$row['complaint_name']."</td>";
									//echo "<td>".$row['engineer_name']."</td>";
									//echo '<input type="hidden" name="hidden_ticketid_'.$row['ticket_id'].'" value="'..'"
									//echo '<td>'.(engineer_list($row['ticket_id'])).'</td>';
									echo '<td>';
									echo '<button class="btn btn-success" onclick="view_ticket_detail(\''.$row['ticket_id'].'\',\''.$row['ticket_status'].'\');">View Details</button></td>';
									/*if($row['ticket_status'] == 'assigned'){
										echo '<button class="btn btn-primary" onclick="close_ticket(\''.$row['ticket_id'].'\');">Close Ticket</button>&nbsp;
									<button class="btn btn-success" onclick="window.location = \'spares_request.php?ticket_id='.$row['ticket_id'].'\';">Request Spares</button></td>';
									}*/
									echo '</td>';
                                echo "</tr>";
                            }
                        }else{
                        	echo '<tr><td colspan="7">No open tickets</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<div class="modal fade large" id="process_ticket_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">View Ticket</h4>

            </div>
            <div class="modal-body">
            	<h4 id="ticket_id_h4"></h4>
                <div role="tabpanel">
                    <!-- Nav tabs -->
					<div class="widget-title">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#tab_spares">Spares Request</a></li>
						<li><a data-toggle="tab" href="#tab_callreport">Call Report</a></li>
					</ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content" style="padding-top: 15px;">
                        <div role="tabpanel" class="tab-pane active" id="tab_spares">
                        	<div id="request_spare_div" style="display: none;">
								<form id="request_spare_form" name="request_spare_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
									<table>
										<thead>
											<tr><th>Spare</th><th>Available Qty</th><th>Needed Qty</th><th>Status</th><th></th></tr>
										</thead>
										<tbody id="tbody_spare">
											<tr id="tr_spare_template">
											<td style="width:200px;">
												<?php $query = "SELECT * FROM  item_master WHERE item_type IN ('SPARES','CS')";
													$result = mysqli_query($dbc,$query);
													if(mysqli_num_rows($result)>0){
														echo '<select id="spare_name" name="spare_name[]">';
														echo '<option value="" selected="selected">Select Spare</option>';
														while($row=mysqli_fetch_array($result)){
															echo '<option value="'.$row['item_master_id'].'">'.$row['item_name'].'</option>';
														}
														echo '</select>';
													}
											?>
											</td>
											<td>
												<label style="margin-top:5px;">10</label>
											</td>
											<td style="width:100px;">
												<input class="form-control" name="quantity[]" id="quantity" type="text" placeholder="Quantity" size="3">
											</td>
											<td style="width:200px;">
												<select class="form-control" name="spare_status[]" id="spare_status" >
													<option value="replaced">Replaced</option>
													<option value="pending">Pending</option>
												</select>
											</td>
											<td><button type="button" class="btn btn-primary" onclick="add_another_row();">Add </button></td>
											</tr>
										</tbody>
									</table>
									<div class="" style="text-align: left;margin-top: 10px;">
										<button type="button" class="btn btn-success" onclick="request_spare();">Request Spare</button>
									</div>
								</form>
							</div>
							<div id="requested_spare_list_div" style="display: none;padding-top: 20px;">
								<h4>Requested Spares List</h4>
								<table class="table table-bordered table-striped">
									<thead><tr><th>S.No.</th><th>Spare Name</th><th>Requested Qty</th><th>Requested By</th><th>Status</th></tr></thead>
									<tbody id="requested_spare_list_tbody"><tr><td colspan="5">No requested spares</td></tbody>
								</table>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="tab_callreport">
							<div id="enter_call_report_div" style="display: none;">
							<form id="call_report_form" name="call_report_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
								<label class="control-label">Opening Reading:</label>
								<div class="controls">
									<div  class="input-append">
										<input class="span22" type="text" name="opening_reading" id="opening_reading" placeholder="Opening Reading">
										<!--span class="add-on"><i class="icon-th"></i></span--> 
									</div>
								</div>
								<label class="control-label">Closing Reading:</label>
								<div class="controls">
									<div  class="input-append">
										<input class="span22" type="text" name="closing_reading" id="closing_reading" placeholder="Closing Reading">
										<!--span class="add-on"><i class="icon-th"></i></span--> 
									</div>
								</div>
								<label class="control-label">Visit Date:</label>
								<div class="controls">
									<div  class="input-append">
										<input class="span11" type="text" data-date-format="yyyy-mm-dd" name="visit_date" id="visit_date" placeholder="Visiting Date">
										<!--span class="add-on"><i class="icon-th"></i></span--> 
									</div>
								</div>
								<label class="control-label">Start time:</label>
								<div class="controls">
									<div  class="input-append">
										<input class="span11" type="text" name="start_time" id="start_time" placeholder="Start Time">
										<!--span class="add-on"><i class="icon-th"></i></span--> 
									</div>
								</div>
								<label class="control-label">End Time:</label>
								<div class="controls">
									<div  class="input-append">
										<input class="span11" type="text" name="end_time" id="end_time" placeholder="End Time">
										<!--span class="add-on"><i class="icon-th"></i></span--> 
									</div>
								</div>
								<label class="control-label">Remarks:</label>
								<div class="controls">
									<div  class="input-append">
										<input class="span22" type="text" name="remarks" id="remarks" placeholder="Remarks">
										<!--span class="add-on"><i class="icon-th"></i></span--> 
									</div>
								</div>
								<label class="control-label">Status:</label>
								<div class="controls">
									<div class="input-append ">
										<select class="form-control " name="ticket_status" id="ticket_status" style="width: 220px;">
											<option value="engineerclosed">Completed</option>
											<option value="sparespending">Spares Pending</option>
										</select>
									</div>
								</div>
								
								<button type="button" class="btn btn-success" onclick="engr_close_ticket();" style="margin: 5px 0 5px 0;">Close Ticket</button>
							</form>
							</div>
							<div id="show_call_report_div" style="display: none;">
							</div>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            	<span class="align-left" id="result_msg"></span>
            	<input class="form-control" name="h_ticket_id" id="h_ticket_id" type="hidden">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--button type="button" class="btn btn-primary save">Save changes</button-->
            </div>
        </div>
    </div>
</div>

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function() {
	<?php if($datatableflag){ ?>
    $('#ticket_list').DataTable();
    <?php } ?>
    $('#li_sc_ticketmenu').addClass('active open');
    $('#visit_date').datepicker({
    	startDate: 0
    });
});

function close_ticket(ticket_id){
    engineer_id = $('#select_'+ticket_id).val();
	data = 'ticket_id='+ticket_id+'&action=close_ticket';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "TICKETUPDATED"){
				bootbox.alert(result.message, function(){
						location.reload();
				});
				//$('#addticket_form')[0].reset();
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
    
}

</script>
<script type="text/javascript" src="js/view_ticket.js"></script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>