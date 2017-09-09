<?php 

session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}

include('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
include('..'.DIRECTORY_SEPARATOR.'header.php');
include('..'.DIRECTORY_SEPARATOR.'topbar.php');
include('..'.DIRECTORY_SEPARATOR.'sidebar_despatch.php');


?>

<link rel="stylesheet" href="<?php echo HOMEURL; ?>css/jquery.timepicker.min.css" />
<style type="text/css">
  .modal.large {
      width: 60%; /* respsonsive width */
      margin-left:-30%; /* width/2) */
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
          <h5>Despatch List</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="despatch_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        
                        <th>Despatch ID</th>
						<th>Department</th>
						<th>Customer Name</th>
						<th>Address</th>
						<th>Location</th>
						<th>Despatch Item</th>
						<th>Quantity</th>
						<th>Despatch Method</th>
						<th>Despatch Date/Time</th>
						<th>Warehouse Location</th>
						<th>Action</th>
                    </tr>
                </thead>
				<tbody>
                    <?php 
                       // $select_query = "SELECT a.department, a.despatch_request_id, c.customer_name, a.party_master_id, a.address, d.location, a.party_location_id FROM despatch_request a, party_master c, party_location d WHERE c.party_master_id = a.party_master_id AND d.party_location_id = a.party_location_id";
                        $select_query = "SELECT * FROM despatch_request WHERE status='pending'";
                        $result = mysqli_query($dbc,$select_query);
                        $rowIndex = 1;
						$datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
							$datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".$row['despatch_request_id']."</td>";
                                    echo "<td id='department_".$rowIndex."'>".$row['department']."</td>";
									echo "<td id='customer_name_".$rowIndex."'>".$row['customer_name']."</td>";
									echo "<td>".$row['address']."</td>";
									echo "<td id='location_".$rowIndex."'>".$row['location']."</td>";	
									echo "<td>".$row['despatch_item']."</td>";	
									echo "<td>".$row['quantity']."</td>";	
									echo "<td>".$row['despatch_method']."</td>";	
									echo "<td>".$row['delivery_by_date_time']."</td>";	
									echo "<td>".$row['warehouse_location']."</td>";	
                                    echo '<td><button type="button" class="btn btn-primary btn-lg" onclick="createDeliveryChallanModal(\''.$row['despatch_request_id'].'\',\''.$rowIndex.'\');">Create DC</button>&nbsp;</td>';
                                echo "</tr>";
                                $rowIndex++;
                            }
                        }else{
                            echo '<tr><td colspan="6">No Despatch added</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<!-- create dc modal -->
<div class="modal fade large" id="create_dc_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Edit Attendance</h4>

            </div>
            <div class="modal-body">
              <form id="create_dc_form" name="create_dc_form" method="post" class="form" action="" onsubmit="return false;">
                    <input type="hidden" name="despatch_request_id" id="despatch_request_id" />
                    <div class="controls" id="create_dc_div">
                    <div class="row-fluid">
                      <div class="control-group span6">
                        <label class="control-label">Date:</label>
                        <div class="controls">
                             <input type="text" class="form-control required" name="dc_date" id="dc_date" placeholder="" />
                        </div>
                      </div>
                      <div class="control-group span6">
                        <label class="control-label">Customer Name:</label>
                        <div class="controls">
                             <input type="text" class="form-control required" name="dc_customer_name" id="dc_customer_name" placeholder="" disabled="true" />
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="control-group span6">
                        <label class="control-label">Department:</label>
                        <div class="controls">
                             <input type="text" class="form-control required" name="dc_department" id="dc_department" placeholder="" disabled="true" />
                        </div>
                      </div>
                      <div class="control-group span6">
                        <label class="control-label">Location:</label>
                        <div class="controls">
                             <input type="text" class="form-control required" name="dc_location" id="dc_location" placeholder="" disabled="true" />
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="control-group span6">
                        <label class="control-label">Model:</label>
                        <div class="controls">
                             <input type="text" class="form-control required" name="dc_model" id="dc_model" placeholder="Model" />
                        </div>
                      </div>
                      <div class="control-group span6">
                        <label class="control-label">Reference Code:</label>
                        <div class="controls">
                             <input type="text" class="form-control" name="dc_ref_code" id="dc_ref_code" placeholder="Reference code" />
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="control-group span6">
                        <label class="control-label">DC Type:</label>
                        <div class="controls">
                             <select id="dc_type" name="dc_type" class="form-control required">
                                 <option value="" selected="">Select DC type...</option>
                                 <option value="Machine">Machine</option>
                             </select>
                        </div>
                      </div>
                      <div class="control-group span6">
                        <label class="control-label">DC Sub-type:</label>
                        <div class="controls">
                             <select id="dc_sub_type" name="dc_sub_type" class="form-control required">
                                 <option value="" selected="">Select DC sub-type...</option>
                                 <option value="New">New</option>
                                 <option value="Replacement">Replacement</option>
                                 <option value="Replacement and Scheme Changes">Replacement and Scheme Change</option>
                                 <option value="Demo">Demo</option>
                                 <option value="Stand By">Stand By</option>
                                 <option value="Machine Shifting">Machine Shifting</option>
                             </select>
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="control-group span6">
                        <label class="control-label">Installation Date:</label>
                        <div class="controls">
                             <input type="text" class="form-control required" name="installation_date" id="installation_date" placeholder="Installation date" />
                        </div>
                      </div>
                      <div class="control-group span6">
                        <label class="control-label">Installation Time:</label>
                        <div class="controls">
                             <input type="text" class="form-control required" name="installation_time" id="installation_time" placeholder="Installation time" />
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="control-group span6">
                        <label class="control-label">Quantity:</label>
                        <div class="controls">
                             <input type="text" class="form-control required" name="dc_qty" id="dc_qty" placeholder="Quantity" />
                        </div>
                      </div>
                      <div class="control-group span6">
                        <label class="control-label">Rate:</label>
                        <div class="controls">
                             <input type="text" class="form-control" name="dc_rate" id="dc_rate" placeholder="Rate" />
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="control-group span6">
                        <label class="control-label">Amount:</label>
                        <div class="controls">
                             <input type="text" class="form-control" name="dc_amount" id="dc_amount" placeholder="Amount" />
                        </div>
                      </div>
                    </div>
                    </div>
              </form>
            </div>
            <div class="modal-footer">
                <span class="align-left" style="color: red;" id="saved_message_div"></span>
                <button type="submit" onclick="createDeliveryChallan()" class="btn btn-primary save">Save</button>
                <button type="button" onclick="location.reload();" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- create dc modal ends -->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script src="<?php echo HOMEURL; ?>js/jquery.timepicker.min.js"></script> 
<script type="text/javascript">
//write all js here	
$(document).ready(function() {
    <?php if($datatableflag){ ?>
    $('#despatch_list').DataTable();
    <?php } ?>
    $('#li_sc_engineermenu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');

    $('#dc_date').val(getCurrentDate());

    $('#dc_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $('#installation_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $('#installation_time').timepicker({
        timeFormat: 'h:mm p',
        interval: 60,
        minTime: '10',
        maxTime: '6:00pm',
        defaultTime: '12',
        startTime: '10:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

});


function getCurrentDate(){
  var today = new Date();
  var yy = today.getFullYear();
  var mm = today.getMonth();
  var dd = today.getDate();
  mm += 1;
  if(dd < 10){
    dd = '0' + dd;
  } 
  if(mm < 10){
    mm = '0' + mm;
  }
  var todayDate = yy + '-' + mm + '-' + dd;
  return todayDate;
}

var gDcInfo = {};

function createDeliveryChallanModal(despatchRequestID, rowIndex){
    // set values for dc 
    
    gDcInfo.department = $('#department_'+rowIndex).html();
    gDcInfo.location = $('#location_'+rowIndex).html();
    gDcInfo.customerName = $('#customer_name_'+rowIndex).html();
    gDcInfo.despatchRequestId = despatchRequestID;
    $('#dc_department').val(gDcInfo.department);
    $('#dc_location').val(gDcInfo.location);
    $('#dc_customer_name').val(gDcInfo.customerName);
    $('#despatch_request_id').val(despatchRequestID);
    $('#create_dc_modal').modal();
}

function createDeliveryChallan(){
    if($('#create_dc_form').valid()){ 
        var data = $('#create_dc_form').serialize() + '&dc_info=' + JSON.stringify(gDcInfo) + '&action=create_dc';
        //alert(data);
        $.ajax({
            url: "despatch_services.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                $('#saved_message_div').html(result.message + ' DC ID: ' + result.dc_id);
                gDcInfo = {};
                
            },
            error: function(){}
        });
    }
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>