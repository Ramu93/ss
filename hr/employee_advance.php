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

include 'department-config.php';
include 'office-config.php';
include 'location-config.php';

?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-10">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Employee Advance</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="getEmployee_form" name="attendance_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
        <div class="control-group">
          <label class="control-label">Department:</label>
          <div class="controls">
           <select class="form-control required" name="department" id="department">
             <option value="" selected="selected">Select department...</option>
             <?php 
                foreach($department as $key => $value){
                  echo '<option value="'.$key.'">'.$value.'</option>';
                }
             ?>
           </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Office/Entity:</label>
          <div class="controls">
               <select class="form-control required" name="office" id="office">
                 <option value="" selected="selected">Select office...</option>
                 <?php 
                    foreach($office as $key => $value){
                      echo '<option value="'.$key.'">'.$value.'</option>';
                    }
                 ?>
               </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Location:</label>
          <div class="controls">
               <select class="form-control required" name="location" id="location">
                 <option value="" selected="selected">Select location...</option>
                 <?php 
                    foreach($location as $key => $value){
                      echo '<option value="'.$key.'">'.$value.'</option>';
                    }
                 ?>
               </select>
          </div>
        </div>
        <div class="control-group row-fluid" style="padding-bottom: 10px;">
          <div class="span4 text-right">
             <button type="submit" class="btn btn-success" onclick="getEmployeesList();">Get Employee List</button>
          </div>
        </div>
      </form>
      <form id="employee_advance_form" name="employee_attendance_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
        <div id="employee_div" class="widget-box-90">
        </div>
        <div id="employee_details_div">
          <div class="control-group">
            <label class="control-label">Employee ID:</label>
            <div class="controls">
              <input type="text" class="form-control" readonly="" name="emp_id_val" id="emp_id_val" placeholder="" />
              <input type="hidden" name="emp_master_id_val" id="emp_master_id_val">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Employee Name:</label>
            <div class="controls">
             <input type="text" class="form-control" readonly="" name="emp_name_val" id="emp_name_val" placeholder="" />
            </div>
          </div>
        </div>
        <div class="control-group" id="advance_date_div">
          <label class="control-label">Date:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="advance_date" id="advance_date" placeholder="" />
          </div>
        </div>
        <div class="control-group" id="payment_mode_div">
          <label class="control-label">Payment Mode:</label>
          <div class="controls">
            <select class="form-control required" name="payment_mode" id="payment_mode">
              <option value="" selected="selected">Select payment mode...</option>
              <option value="Bank">Bank</option>
              <option value="Cash">Cash</option>
            </select>
          </div>
        </div>
        <div id="bank_details_div">
          <div class="control-group">
            <label class="control-label">Bank Account Number:</label>
            <div class="controls">
                 <input type="text" class="form-control number" name="bank_acc_number" id="bank_acc_number" placeholder="Bank Account Number" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Bank Name:</label>
            <div class="controls">
                 <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Bank name" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">IFSC Code:</label>
            <div class="controls">
                 <input type="text" class="form-control" name="ifsc" id="ifsc" placeholder="IFSC" />
            </div>
          </div>
        </div>
        <div class="control-group" id="advance_amount_div">
          <label class="control-label">Advance amount:</label>
          <div class="controls">
           <input type="text" class="form-control required" name="advance_amount" id="advance_amount" placeholder="Advance amount" />
          </div>
        </div>
        <div class="control-group" id="deduction_type_div">
          <label class="control-label">Advance amount:</label>
          <div class="controls">
           <input type="radio" name="deduction_type" value="auto"> Auto&nbsp;&nbsp;&nbsp;<input type="radio" name="deduction_type" value="manual"> Manual
          </div>
        </div>
        <div class="control-group row-fluid" style="padding-bottom: 10px;">
          <div class="span4 text-right" id="save_btn_div">
             <button type="submit" class="btn btn-success" onclick="saveAdvance();">Save</button>
          </div>
        </div>
      </form>
		</div>
	</div>
</div>
<!--end-main-container-part-->


<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here
$(document).ready(function(){
  $('#addasset_form').validate();
  $('#li_sc_ticketmenu').addClass('active open');
  $('#advance_date').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true,
      maxDate: 'now'
  });

  $('#bank_details_div').hide();
  $('#payment_mode').on('change', function(){
    var paymentMode = $(this).val();
    if(paymentMode == 'Bank'){
      $('#bank_details_div').show();
    } else{
      $('#bank_details_div').hide();
    }
  });

  $('#payment_mode_div').hide();
  $('#save_btn_div').hide();
  $('#advance_date_div').hide();
  $('#employee_details_div').hide();
  $('#advance_amount_div').hide();
  $('#deduction_type_div').hide();
});


$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

var gEmployeeList = Array;

function getEmployeesList(){
  // hide attendance list if shown 
  $('#attendance_view_div').hide();

  if($('#getEmployee_form').valid()){ 
    var data = $('#getEmployee_form').serialize() + '&action=get_employee_list';
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
      output += '<th></th>';
    output += '</tr>';
  output += '</thead>';
  output += '<tbody>';
  for (employee in employeeData){
    count++;
    output += '<tr>';
      output += '<td>' + employeeData[employee].emp_id + '</td>';
      output += '<td>' + employeeData[employee].employee_name + '</td>';
      output += '<td>' + employeeData[employee].designation + '</td>';
      output += '<td><input type="button" value="Select" class="btn btn-primary" onclick="selectEmployee(' + employeeData[employee].hr_emp_master_id + ')" /></td>';
    output += '</tr>';
  }
  output += '</tbody>';
  output += '</table>';
  $('#employee_div').html(output);
}

function selectEmployee(empMasterId){
  for (employee in gEmployeeList){
    if(gEmployeeList[employee].hr_emp_master_id == empMasterId){
      $('#emp_id_val').val(gEmployeeList[employee].emp_id);
      $('#emp_master_id_val').val(gEmployeeList[employee].hr_emp_master_id);
      $('#emp_name_val').val(gEmployeeList[employee].employee_name);
      break;
    }
  }
  $('#save_btn_div').show();
  $('#advance_date_div').show();
  $('#employee_details_div').show();
  $('#payment_mode_div').show();
  $('#advance_amount_div').show();
  $('#deduction_type_div').show();
}

function saveAdvance(){
  if($('#employee_advance_form').valid()){ 
    var data = $('#employee_advance_form').serialize() + '&action=add_advance';
    $.ajax({
        url: "hr_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            if(result.infocode == "ADVANCEDATAADDED"){
                bootbox.alert(result.message);
                $('#save_btn_div').hide();
                $('#advance_date_div').hide();
                $('#employee_details_div').hide();
                $('#advance_amount_div').hide();
                $('#payment_mode_div').hide();
                $('#bank_details_div').hide();
                $('#deduction_type_div').hide();
                $('#employee_advance_form')[0].reset();
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
