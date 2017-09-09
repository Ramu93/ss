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

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

    <div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Delivery Schedule List</h5>
        </div>
        <div class="widget-content nopadding">
            <table id="despatch_schedule_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        
                        <th>DS ID</th>
                        <th>Vehicle Number</th>
                        <th>Driver Name</th>
                        <th>Created Date/Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                       // $select_query = "SELECT a.department, a.despatch_request_id, c.customer_name, a.party_master_id, a.address, d.location, a.party_location_id FROM despatch_request a, party_master c, party_location d WHERE c.party_master_id = a.party_master_id AND d.party_location_id = a.party_location_id";
                        $select_query = "SELECT * FROM despatch_schedule WHERE status='ds_created'";
                        $result = mysqli_query($dbc,$select_query);
                        $rowIndex = 0;
                        $datatableflag = false;
                        $out = array();
                        if(mysqli_num_rows($result) > 0) {
                            $datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                $out[] = $row;
                                echo "<tr>";
                                    echo "<td>".$row['despatch_schedule_id']."</td>";
                                    echo "<td>".$row['vehicle_number']."</td>";
                                    echo "<td>".$row['driver_name']."</td>";
                                    echo "<td>".$row['created_date']."</td>";
                                    // echo "<td>".$row['dc_sub_type']."</td>";    
                                    // echo "<td>".$row['installation_date']."</td>"; 
                                    // echo "<td style='text-align:center;'><input type='checkbox' class='select_dc' id='dc_checkbox_".$rowIndex."' ></td>"; 
                                    echo "<td><button class='btn btn-primary' onclick='viewDespatchScheduleDetails(".$row['despatch_schedule_id'].")'>View Details</button></td>";
                                echo "</tr>";
                                $rowIndex++;
                            }
                            //file_put_contents("despatch.log", "\n".print_r(json_encode($out), true), FILE_APPEND | LOCK_EX);

                        }else{
                            echo '<tr><td colspan="6">No delivery challan added</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--end-main-container-part-->

<!-- view delivery challan details modal -->
<div class="modal fade large" id="delivery_challan_details_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Delivery Challan Details</h4>

            </div>
            <div class="modal-body">
              <div class="controls" id="delivery_challan_div">
                <form id="complete_ds_form" name="complete_ds_form" method="post" class="form" action="" onsubmit="return false;">
                    <input type="hidden" name="despatch_schedule_id" id="hidden_despatch_schedule_id" />
                    <table id="delivery_challan_table" class="table table-striped table-bordered" cellspacing="0" width="100%">

                    </table>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                              <label class="control-label">Completed Date:</label>
                              <div class="controls">
                                   <input type="text" class="form-control required" name="return_date" id="completed_date" placeholder="Completed date" />
                              </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                              <label class="control-label">Completed Time:</label>
                              <div class="controls">
                                   <input type="text" class="form-control required" name="return_time" id="completed_time" placeholder="Completed time" />
                              </div>
                            </div>
                        </div>
                    </div>
                </form>
              </div>
            </div>
            <div class="modal-footer">
              <span class="align-left" id="result_msg"></span>
                <button type="button" class="btn btn-success save" onclick="updateDS()">Despatch Schedule Complete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- view delivery challan details modal ends -->


<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script src="<?php echo HOMEURL; ?>js/jquery.timepicker.min.js"></script> 

<script type="text/javascript">

var gDCList = [];
//write all js here 
$(document).ready(function() {
    <?php if($datatableflag){ ?>
    $('#despatch_schedule_list').DataTable();
    <?php } ?>
    $('#li_sc_engineermenu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');

    $('#completed_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $('#completed_time').timepicker({
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

function viewDespatchScheduleDetails(despatchScheduleID){
    $('#hidden_despatch_schedule_id').val(despatchScheduleID);
    var data = 'despatch_schedule_id=' + despatchScheduleID + '&action=get_dc_list';
    $.ajax({
        url: "despatch_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            gDCList = JSON.parse(result.data);
            displayDespatchScheduleDetails(gDCList);
            console.log(gDCList);
        },
        error: function(){}
    });
}

function displayDespatchScheduleDetails(dcList){
    var output = '';
    var count = 0;
    output += '<thead>';
        output += '<tr>';
            output += '<th>S. No.</th>';
            output += '<th>DC ID</th>';
            output += '<th>Customer Name</th>';
            output += '<th>Location</th>';
            output += '<th>DC Type</th>';
            output += '<th>Serial Number</th>';
        output += '</tr>';
    output += '</thead>';
    output += '<tbody>';
        for(dc in dcList){
            count++;
            output += '<tr>';
                output += '<td>'+count+'</td>';
                output += '<td>'+dcList[dc].delivery_challan_id+'</td>';
                output += '<td>'+dcList[dc].customer_name+'</td>';
                output += '<td>'+dcList[dc].location+'</td>';
                output += '<td>'+dcList[dc].dc_type+'</td>';
                output += '<td><input type="text" name="serial_number[]" id="serial_number_'+count+'" /></td>';
            output += '</tr>';
        }
    output += '</tbody>';
    $('#delivery_challan_table').html(output);
    $('#delivery_challan_details_modal').modal();
}

function updateDS(){
    for(dc in gDCList){
        var count = parseInt(dc) + 1;
        var serial_number = $('#serial_number_'+count).val();
        gDCList[dc].serial_number = serial_number;
    }
    
    var despatchScheduleID = $('#hidden_despatch_schedule_id').val();
    var completedDate = $('#return_time').val();
    var completedTime = $('#return_date').val();
    var data = 'despatch_schedule_id=' + despatchScheduleID + '&dc_list=' + JSON.stringify(gDCList) + '&completed_time=' + completedTime + '&completed_date=' + completedDate + '&action=update_ds';
    $.ajax({
        url: "despatch_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            if(result.infocode == "DSUPDATEDSUCCESSFULLY"){
                $('#saved_message_div').html(result.message).fadeIn(400).fadeOut(4000);
                $('#complete_ds_form')[0].reset();
                location.reload();
            }else{
                bootbox.alert(result.message);
            }
        },
        error: function(){}             
    });
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>