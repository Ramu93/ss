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

	<div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Attendance</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="attendance_form" name="attendance_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
          <label class="control-label">Date:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="date" id="date" placeholder="" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Department:</label>
          <div class="controls">
           <select class="form-control required" name="department" id="department">
             <option value="" selected="selected">Select department...</option>
             <option value="HR">HR</option>
             <option value="Marketing">Marketing</option>
             <option value="Finance">Finance</option>
             <option value="Service">Service</option>
             <option value="Client Accountant">Client Accountant</option>
             <option value="Admin">Admin</option>
             <option value="Despatch">Despatch</option>
             <option value="Materials">Materials</option>
             <option value="Tech">Tech</option>
           </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Office/Entity:</label>
          <div class="controls">
               <select class="form-control required" name="office" id="office">
                 <option value="" selected="selected">Select office...</option>
                 <option value="SBBS">SBBS</option>
                 <option value="SBBM">SBBM</option>
               </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Location:</label>
          <div class="controls">
               <select class="form-control required" name="location" id="location">
                 <option value="" selected="selected">Select location...</option>
                 <option value="T. Nagar">T. Nagar</option>
                 <option value="Banglore">Banglore</option>
                 <option value="Poonamallee">Poonamallee</option>
               </select>
          </div>
        </div>
        <div class="control-group row-fluid" style="padding-bottom: 10px;">
          <div class="span4 text-right">
             <button type="submit" class="btn btn-success" onclick="getEmployeesList();">Get Employee List</button>
          </div>
          <div class="span4">
             <button type="button" class="btn btn-primary" onclick="viewAttedance();">View Attendance</button>
          </div>
        </div>
			</form>
      <form id="employee_attendance_form" name="employee_attendance_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
        <div id="employee_div" class="widget-box-90">
          
        </div>
        <div class="control-group row-fluid" style="padding-bottom: 10px;">
          <div class="span4 text-right" id="save_btn_div">
             <button type="submit" class="btn btn-success" onclick="saveAttendance();">Save</button>
          </div>
        </div>
      </form>
      <div id="attendance_view_div" class="widget-box-90">
          
      </div>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<!-- view attendance details modal -->
<div class="modal fade large" id="view_attendance_details_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Attendance Details</h4>

            </div>
            <div class="modal-body">
              <div class="controls" id="attendance_detail_div">

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
<!-- view attendance details modal ends -->

<!-- edit attendance details modal -->
<div class="modal fade large" id="edit_attendance_details_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Edit Attendance</h4>

            </div>
            <div class="modal-body">
              <form id="update_attendance_detail_form" name="update_attendance_detail_form" method="post" class="" action="" onsubmit="return false;" >
                <div class="controls" id="edit_attendance_detail_div">

                </div>
              </form>
            <div id="update_success_message" style="color: red;"></div>
            </div>
            <div class="modal-footer">
              <span class="align-left" id="result_msg"></span>
                <button type="button" class="btn btn-primary" onclick="updateAttendance();">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary save">Save</button> -->
            </div>
        </div>
    </div>
</div>
<!-- edit attendance details modal ends -->


<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here
$(document).ready(function(){
    $('#attendance_form').validate();
    $('#li_sc_ticketmenu').addClass('active open');
    $('#contract_start_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $('#employee_div').hide();
    $('#save_btn_div').hide();

    $('#date').val(getCurrentDate());

    $('#date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $('.entry_time_class').val('09:30 AM');
    $('.exit_time_class').val('06:00 PM');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
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

var gEmployeeList = Array;

function getEmployeesList(){
  // hide attendance list if shown 
  $('#attendance_view_div').hide();

  if($('#attendance_form').valid()){ 
    var data = $('#attendance_form').serialize() + '&action=get_employee_list';
    $.ajax({
        url: "hr_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            if(result.infocode == "GETEMPLOYEELISTSUCCESS"){
                var employeeData = JSON.parse(result.data);
                gEmployeeList = employeeData;
                console.log(gEmployeeList);
                displayEmployeeTable(employeeData);
            }else{
                bootbox.alert(result.message);
            }
        },
        error: function(){}
    });
  }
}

function displayEmployeeTable(employeeData){
  var count = 0;
  var output = '';
  output += '<table id="employee_list" class="table table-striped" cellspacing="0" width="100%">';
  output += '<thead>';
    output += '<tr>';
      output += '<th></th>';
      output += '<th>Employee ID</th>';
      output += '<th>Employee Name</th>';
      output += '<th>Designation</th>';
      output += '<th>Present/Absent</th>';
      output += '<th></th>';
    output += '</tr>';
  output += '</thead>';
  output += '<tbody>';
  for (employee in employeeData){
    count++;
    output += '<tr>';
      output += '<td><input type="checkbox" id="employee_select_checkbox_'+count+'" class="employee_select_checkbox" name="" value="' + employeeData[employee].emp_id + '"></td>';
      output += '<td>' + employeeData[employee].emp_id + '</td>';
      output += '<td>' + employeeData[employee].employee_name + '</td>';
      output += '<td>' + employeeData[employee].designation + '</td>';
      output += '<td>';
        output += '<select name="present_absent[]" id="present_absent_'+count+'" class="select_present_absent form-control"  >';
          output += '<option value="">Select...</option>';
          output += '<option value="present" selected="selected">Present</option>';
          output += '<option value="absent">Absent</option>';
        output += '</select>';
      output += '</td>';
      output += '<td>';
        output += '<div id="present_fields_'+count+'" class="present_absent_fields present_fields">';
          output += '<label id="entry_time_lbl_'+count+'">Entry Time:</label>';
          output += '<input type="text" value="09:30 AM" class="form-control entry_time_class" name="entry_time[]" id="entry_time_'+count+'" /><br/>';
          output += '<label id="exit_time_lbl_'+count+'">Exit Time:</label>';
          output += '<input type="text" value="06:00 PM" class="exit_time_class" name="exit_time[]" id="exit_time_'+count+'"  /><br/>';
          output += '<label id="late_by_lbl_'+count+'">Late By:</label>';
          output += '<input type="text" name="late_by[]" id="late_by_'+count+'" readonly /><br/>';
        output += '</div>';
        output += '<div id="absent_fields_'+count+'" class="present_absent_fields absent_fields">';
          output += '<label id="reason_lbl_'+count+'">Reason:</label>';
          output += '<input type="text" name="reason[]" id="reason_'+count+'" /><br/>';
          output += '<label id="informed_uninformed_lbl_'+count+'">Informed/Uniformed:</label>';
          output += '<input type="radio" name="informed_uninformed[]" class="informed_uninformed_radio" id="informed_uninformed_info_'+count+'" value="Informed" /> Informed &nbsp&nbsp';
          output += '<input type="radio" name="informed_uninformed[]" class="informed_uninformed_radio" id="informed_uninformed_uninfo_'+count+'" value="Uninformed"/> Uninformed </br>';
          output += '<input type="hidden" value="Informed" class="info_uninfo" name="informed_uninformed_val[]" id="informed_uninformed_val_'+count+'" />';
        output += '</div>';  
      output += '</td>';
    output += '</tr>';
  }
  output += '</tbody>';
  output += '</table>';
  $('#employee_div').html(output);
  //hide all the present absent fields initially
  $('.absent_fields').hide();
  $('#employee_div').show();
  $('#save_btn_div').show();
  setDefaultEmployeeSelectValueFalse();
  bindEmployeeListEvents();
}

function bindEmployeeListEvents(){

  $('.employee_select_checkbox').on("change", function() {
    var presentAbsentCheckBoxID = $(this).attr("id");
    var presentAbsentCheckBoxVal = $('#'+presentAbsentCheckBoxID).val();
    var presentAbsentCheckBoxIDArray = presentAbsentCheckBoxID.split('_'); 
    var idCountVal = presentAbsentCheckBoxIDArray[presentAbsentCheckBoxIDArray.length-1];
    console.log(idCountVal);
    if ($('#'+presentAbsentCheckBoxID).is(':checked')){
      gEmployeeList[idCountVal-1].isEmployeeSelected = 'true'; 
      console.log(gEmployeeList);
    } else {
      gEmployeeList[idCountVal-1].isEmployeeSelected = 'false';
    }  
  });

  $('.select_present_absent').on("change", function() {
    var presentAbsentID = $(this).attr("id");
    var presentAbsentVal = $('#'+presentAbsentID).val();
    var presentAbsentIDArray = presentAbsentID.split('_');
    var idCountVal = presentAbsentIDArray[presentAbsentIDArray.length-1];
    // alert(idCountVal);
    if(presentAbsentVal == 'present'){
      $('#present_fields_'+idCountVal).show();
      $('#absent_fields_'+idCountVal).hide();
    } else {
      $('#present_fields_'+idCountVal).hide();
      $('#absent_fields_'+idCountVal).show();
    }
    $('#entry_time_'+ idCountVal).val('');
    $('#exit_time_'+ idCountVal).val('');
    $('#late_by_'+ idCountVal).val('');
    $('#reason_'+ idCountVal).val('');
    $('#informed_uninformed_val_'+ idCountVal).val('');
  });

  $('.informed_uninformed_radio').on("change", function(){
    var presentAbsentCheckBoxID = $(this).attr("id");
    var presentAbsentCheckBoxVal = $('#'+presentAbsentCheckBoxID).val();
    var presentAbsentCheckBoxIDArray = presentAbsentCheckBoxID.split('_'); 
    var idCountVal = presentAbsentCheckBoxIDArray[presentAbsentCheckBoxIDArray.length-1];
    $('#informed_uninformed_val_' + idCountVal).val($(this).val());
  });
}

function setDefaultEmployeeSelectValueFalse(){
  for (employee in gEmployeeList){
    gEmployeeList[employee].isEmployeeSelected = 'false';
  }
}


function saveAttendance(){
  var data = $('#attendance_form').serialize() + "&" + $('#employee_attendance_form').serialize() + "&employee_list=" + JSON.stringify(gEmployeeList) + '&action=save_attendance';

  console.log(data);

  $.ajax({
    url: "hr_services.php",
    type: "POST",
    data:  data,
    dataType: 'json',
    success: function(result){
      if(result.infocode == "ATTENDANCEUPDATESUCCESS"){
          bootbox.alert(result.message);
          $('#attendance_form')[0].reset();
          $('#employee_attendance_form')[0].reset();
          $('#employee_div').hide();
          $('#save_btn_div').hide();
          $('#employee_div').html('');
          gEmployeeList = Array;

          $('#date').val(getCurrentDate());
      }else{
          bootbox.alert(result.message);
      }
    },
    error: function(){}
  });
}

function viewAttedance(){
  // hide employee list if shown
  $('#employee_div').hide();
  $('#save_btn_div').hide();

  var data = $('#attendance_form').serialize() + "&action=view_attendance";
  $.ajax({
    url: "hr_services.php",
    type: "POST",
    data:  data,
    dataType: 'json',
    success: function(result){
      if(result.infocode == "GETATTENDANCEDATASUCCESS"){
        var attendanceData = JSON.parse(result.data);
        displayAttendanceTable(attendanceData);
        //bootbox.alert(result.data);
      }else{
          bootbox.alert(result.message);
      }
    },
    error: function(){}
  });
}

function displayAttendanceTable(attendanceData){
  var count = 0;
  var output = '';
  output += '<table id="employee_list" class="table table-striped" cellspacing="0" width="100%">';
  output += '<thead>';
    output += '<tr>';
      output += '<th>S. No.</th>';
      output += '<th>Employee ID</th>';
      output += '<th>Employee Name</th>';
      output += '<th>Designation</th>';
      output += '<th>Present/Absent</th>';
      output += '<th>Actions</th>';
    output += '</tr>';
  output += '</thead>';
  output += '<tbody>';
  for (attendance in attendanceData){
    count++;
    output += '<tr>';
      output += '<td>'+count+'</td>';
      output += '<td>' + attendanceData[attendance].emp_id + '</td>';
      output += '<td>' + attendanceData[attendance].employee_name + '</td>';
      output += '<td>' + attendanceData[attendance].designation + '</td>';
      output += '<td>' + capitalizeFirstLetter(attendanceData[attendance].present_absent) + '</td>';
      output += '<td><button class="btn btn-primary" onclick="viewAttendanceDetails('+attendanceData[attendance].hr_attendance_id+')">View Details</button> <button class="btn btn-warning" onclick="editAttendance('+attendanceData[attendance].hr_attendance_id+');">Edit</button></td>';
    output += '</tr>';
  }
  output += '</tbody>';
  output += '</table>';
  $('#attendance_view_div').html(output);
  $('#attendance_view_div').show();
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function viewAttendanceDetails(attendanceID){
  var data = 'attendance_id=' + attendanceID + '&action=get_attendance_detail';
  $.ajax({
    url: "hr_services.php",
    type: "POST",
    data:  data,
    dataType: 'json',
    success: function(result){
      if(result.infocode == "GETATTENDANCEDETAILSUCCESS"){
        var attendanceDetail = JSON.parse(result.data);
        displayAttendanceDetail(attendanceDetail);
      }else{
          bootbox.alert(result.message);
      }
    },
    error: function(){}
  });
}

function displayAttendanceDetail(attendanceDetail){
  var output = '';

  if(attendanceDetail.present_absent == 'present'){
    output += '<label>Entry Time:</label>';
    output += '<label>'+ attendanceDetail.entry_time +'</label>';
    output += '<label>Exit Time:</label>';
    output += '<label>'+ attendanceDetail.exit_time +'</label>';
    output += '<label>Late By:</label>';
    output += '<label>'+ attendanceDetail.late_by +'</label>';
  } else {
    output += '<label>Reason:</label>';
    output += '<label>'+ attendanceDetail.reason +'</label>';
    output += '<label>Informed/Uniformed:</label>';
    output += '<label>'+ attendanceDetail.informed_uninformed +'</label>';
  }

  $('#attendance_detail_div').html(output);
  $('#view_attendance_details_modal').modal();
}

function editAttendance(attendanceID){
  var data = 'attendance_id=' + attendanceID + '&action=get_attendance_detail';
  $.ajax({
    url: "hr_services.php",
    type: "POST",
    data:  data,
    dataType: 'json',
    success: function(result){
      if(result.infocode == "GETATTENDANCEDETAILSUCCESS"){
        var attendanceDetail = JSON.parse(result.data);
        displayEditAttendanceDetailFields(attendanceDetail);
      }else{
          bootbox.alert(result.message);
      }
    },
    error: function(){}
  });
}

function displayEditAttendanceDetailFields(attendanceDetail){
  var output = '';
  output += '<input type="hidden" name="attendance_id" value="'+attendanceDetail.hr_attendance_id+'" />';
  output += '<select name="present_absent" id="present_absent" class="select_present_absent_edit" >';
    output += '<option value="">Select...</option>';
    output += '<option value="present">Present</option>';
    output += '<option value="absent">Absent</option>';
  output += '</select>';
  output += '<div id="present_fields_edit" class="present_absent_fields_edit">';
    output += '<label id="entry_time_lbl">Entry Time:</label>';
    output += '<input type="text" name="entry_time" id="entry_time" value="'+attendanceDetail.entry_time+'" /><br/>';
    output += '<label id="exit_time_lbl">Exit Time:</label>';
    output += '<input type="text" name="exit_time" id="exit_time" value="'+attendanceDetail.exit_time+'" /><br/>';
    output += '<label id="late_by_lbl">Late By:</label>';
    output += '<input type="text" name="late_by" id="late_by" readonly value="'+attendanceDetail.late_by+'" /><br/>';
  output += '</div>';
  output += '<div id="absent_fields_edit" class="present_absent_fields_edit">';
    output += '<label id="reason_lbl">Reason:</label>';
    output += '<input type="text" name="reason" id="reason" value="'+attendanceDetail.reason+'" /><br/>';
    output += '<label id="informed_uninformed_lbl">Informed/Uniformed:</label>';

    if(attendanceDetail.informed_uninformed == 'Informed'){
      output += '<input type="radio" name="informed_uninformed[]" class="informed_uninformed_radio" id="informed_uninformed_info" value="Informed" checked /> Informed &nbsp&nbsp';
      output += '<input type="radio" name="informed_uninformed[]" class="informed_uninformed_radio" id="informed_uninformed_uninfo" value="Uninformed"/> Uninformed </br>';
      output += '<input type="hidden" value="Informed" class="info_uninfo" name="informed_uninformed_val[]" id="informed_uninformed_val" />';
    } else {
      output += '<input type="radio" name="informed_uninformed[]" class="informed_uninformed_radio" id="informed_uninformed_info" value="Informed" /> Informed &nbsp&nbsp';
      output += '<input type="radio" name="informed_uninformed[]" class="informed_uninformed_radio" id="informed_uninformed_uninfo" value="Uninformed" checked/> Uninformed </br>';
      output += '<input type="hidden" value="Uninformed" class="info_uninfo" name="informed_uninformed_val" id="informed_uninformed_val" />';
    }
    
  output += '</div>';  
  $('#edit_attendance_detail_div').html(output);
  $('#present_absent').val(attendanceDetail.present_absent);
  if(attendanceDetail.present_absent == 'present'){
    $('#absent_fields_edit').hide();
  } else {
    $('#present_fields_edit').hide();
  }
  editAttendanceDetailBindEvents();
  $('#edit_attendance_details_modal').modal();
}

function editAttendanceDetailBindEvents(){
  $('.select_present_absent_edit').on("change", function() {
    var presentAbsentVal = $('#present_absent').val();
    if(presentAbsentVal == 'present'){
      $('#present_fields_edit').show();
      $('#absent_fields_edit').hide();
    } else {
      $('#present_fields_edit').hide();
      $('#absent_fields_edit').show();
    }
    $('#entry_time').val('');
    $('#exit_time').val('');
    $('#late_by').val('');
    $('#reason').val('');
    $('#informed_uninformed').val('');
  });

  $('.informed_uninformed_radio').on("change", function(){
    var presentAbsentCheckBoxID = $(this).attr("id");
    var presentAbsentCheckBoxVal = $('#'+presentAbsentCheckBoxID).val();
    var presentAbsentCheckBoxIDArray = presentAbsentCheckBoxID.split('_'); 
    var idCountVal = presentAbsentCheckBoxIDArray[presentAbsentCheckBoxIDArray.length-1];
    $('#informed_uninformed_val').val($(this).val());
  });
}

function updateAttendance(){
  var data = $('#update_attendance_detail_form').serialize() + "&action=update_attendance_detail";
  $.ajax({
    url: "hr_services.php",
    type: "POST",
    data:  data,
    dataType: 'json',
    success: function(result){
      if(result.infocode == "UPDATEATTENDANCEDETAILSUCCESS"){
        $('#update_success_message').html('Attendance updated successfully').fadeIn(400).fadeOut(4000);
        viewAttedance();
      }else{
          bootbox.alert(result.message);
      }
    },
    error: function(){}
  });
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>
