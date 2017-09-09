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
          <h5>Delivery Challan List</h5>
        </div>
        <div class="widget-content nopadding">
            <table id="delivery_challan_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        
                        <th>DC ID</th>
                        <th>DC Date</th>
                        <th>Model</th>
                        <th>DC Type</th>
                        <th>DC Subtype</th>
                        <th>Installation Date</th>
                        <th>Select</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                       // $select_query = "SELECT a.department, a.despatch_request_id, c.customer_name, a.party_master_id, a.address, d.location, a.party_location_id FROM despatch_request a, party_master c, party_location d WHERE c.party_master_id = a.party_master_id AND d.party_location_id = a.party_location_id";
                        $select_query = "SELECT * FROM delivery_challan WHERE status='dc_created'";
                        $result = mysqli_query($dbc,$select_query);
                        $rowIndex = 0;
                        $datatableflag = false;
                        $out = array();
                        if(mysqli_num_rows($result) > 0) {
                            $datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                $out[] = $row;
                                echo "<tr>";
                                    echo "<td>".$row['delivery_challan_id']."</td>";
                                    echo "<td>".$row['dc_date']."</td>";
                                    echo "<td>".$row['model']."</td>";
                                    echo "<td>".$row['dc_type']."</td>";
                                    echo "<td>".$row['dc_sub_type']."</td>";    
                                    echo "<td>".$row['installation_date']."</td>"; 
                                    echo "<td style='text-align:center;'><input type='checkbox' class='select_dc' id='dc_checkbox_".$rowIndex."' ></td>"; 
                                    echo "<td><button class='btn btn-primary' onclick='viewDeliveryChallanDetails(".$row['delivery_challan_id'].")'>View Details</button></td>";
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
        <form class="form-horizontal">
            <div class="row-fluid">
                <div class="span4 text-right">
                    <button type="button" class="btn btn-success" onclick="createDSModal()">Create DS</button>
                </div>
            </div>
        </form>
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

              </div>
            </div>
            <div class="modal-footer">
              <span class="align-left" id="result_msg"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary save">Save</button> -->
            </div>
        </div>
    </div>
</div>
<!-- view delivery challan details modal ends -->

<!-- add delivery schedule details modal -->
<div class="modal fade large" id="add_delivery_schedule_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Add Delivery Schedule</h4>

            </div>
            <div class="modal-body">
              <form id="create_dc_form" name="create_dc_form" method="post" class="form" action="" onsubmit="return false;">
                <div class="control-group">
                  <label class="control-label">Vehicle Number:</label>
                  <div class="controls">
                       <input type="text" class="form-control required" name="ds_vehicle_number" id="ds_vehicle_number" placeholder="Vehicle number" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Driver Name:</label>
                  <div class="controls">
                       <input type="text" class="form-control required" name="ds_driver_name" id="ds_driver_name" placeholder="Driver name" />
                  </div>
                </div>
                <div class="row-fluid">
                    <div class="control-group span6">
                      <label class="control-label">Start Date:</label>
                      <div class="controls">
                           <input type="text" class="form-control required" name="start_date" id="start_date" placeholder="Schedule date" />
                      </div>
                    </div>
                    <div class="control-group span6">
                      <label class="control-label">Start Time:</label>
                      <div class="controls">
                           <input type="text" class="form-control required" name="start_time" id="start_time" placeholder="Schedule time" />
                      </div>
                    </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
                <span class="align-left" style="color: red;" id="saved_message_div"></span>
                <button type="submit" onclick="createDS()" class="btn btn-primary save">Save</button>
                <button type="button" onclick="location.reload();" class="btn btn-default" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary save">Save</button> -->
            </div>
        </div>
    </div>
</div>
<!-- add delivery schedule details modal ends -->


<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script src="<?php echo HOMEURL; ?>js/jquery.timepicker.min.js"></script> 

<script type="text/javascript">

var gDCDetail = {};
var gDCList = <?php echo json_encode($out); ?>;
var gDCListSelectedCount = 0;
//write all js here 
$(document).ready(function() {
    <?php if($datatableflag){ ?>
    $('#delivery_challan_list').DataTable();
    <?php } ?>
    $('#li_sc_engineermenu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');
    bindDCListEvent();
    setDefaultSelectedValueToFalse();

    $('#start_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $('#start_time').timepicker({
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

function setDefaultSelectedValueToFalse(){
    for(dc in gDCList){
        gDCList[dc].isDCSelected = 'false';
    }
    console.log(gDCList);
}

function viewDeliveryChallanDetails(dcID){
    var data = 'delivery_challan_id=' + dcID + '&action=get_dc_details';
    $.ajax({
        url: "despatch_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            gDCDetail = JSON.parse(result.data);
            displayDCDetails(gDCDetail);
        },
        error: function(){}
    });
}

function bindDCListEvent(){
    $('.select_dc').on("change", function() {
        var dcCheckboxID = $(this).attr("id");
        var dcCheckboxVal = $('#'+dcCheckboxID).val();
        var dcCheckboxArray = dcCheckboxID.split('_'); 
        var idCountVal = dcCheckboxArray[dcCheckboxArray.length-1];
        //alert(idCountVal);
        if ($('#'+dcCheckboxID).is(':checked')){
          gDCList[idCountVal].isDCSelected = 'true'; 
          console.log(gDCList);
          gDCListSelectedCount++;
        } else {
          gDCList[idCountVal].isDCSelected = 'false';
          console.log(gDCList);
          gDCListSelectedCount--;
        }  
    });
}

function displayDCDetails(gDCDetail){
    var output = '';
    output += '<div class="row-fluid">';
        output += '<div class="span6">'
            output += '<label>Customer Name:</label>';
            output += '<label>'+ gDCDetail.customer_name +'</label>';
        output += '</div>';
        output += '<div class="span6">'
            output += '<label>Department:</label>';
        output += '<label>'+ gDCDetail.department +'</label>';
        output += '</div>';
    output += '</div>';
    output += '<div class="row-fluid">';
        output += '<div class="span6">'
            output += '<label>Location:</label>';
            output += '<label>'+ gDCDetail.location +'</label>';
        output += '</div>';
        output += '<div class="span6">'
            output += '<label>Customer Name:</label>';
            output += '<label>'+ gDCDetail.customer_name +'</label>';
        output += '</div>';
    output += '</div>';
    output += '<div class="row-fluid">';
        output += '<div class="span6">'
            output += '<label>Reference Code:</label>';
            output += '<label>'+ gDCDetail.old_code +'</label>';
        output += '</div>';
        output += '<div class="span6">'
            output += '<label>Quantity:</label>';
        output += '<label>'+ gDCDetail.quantity +'</label>';
        output += '</div>';
    output += '</div>';
    output += '<div class="row-fluid">';
        output += '<div class="span6">'
            output += '<label>Rate:</label>';
            output += '<label>'+ gDCDetail.rate +'</label>';
        output += '</div>';
        output += '<div class="span6">'
            output += '<label>Amount:</label>';
            output += '<label>'+ gDCDetail.amount +'</label>';
        output += '</div>';
    output += '</div>';
    $('#delivery_challan_div').html(output);
    $('#delivery_challan_details_modal').modal();

}

function createDSModal(){
    if(gDCListSelectedCount > 0){
        $('#add_delivery_schedule_modal').modal();
    } else {
        bootbox.alert('No DCs selected!');
    }
}

function createDS(){
    if($('#create_dc_form').valid()){
        var data = $('#create_dc_form').serialize() + '&dc_list=' + JSON.stringify(gDCList) + '&action=create_ds';

        $.ajax({
            url: "despatch_services.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "DSCREATEDSUCCESSFULLY"){
                    $('#saved_message_div').html(result.message + ' DS ID: ' + result.ds_id);
                    $('#create_dc_form')[0].reset();
                    
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