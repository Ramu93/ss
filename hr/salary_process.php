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

include 'location-config.php';
include 'office-config.php';
include 'department-config.php';

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
          <h5>Salary Process</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="salary_process_form" name="salary_process_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
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
             <button type="submit" class="btn btn-primary" onclick="getEmployeesList();">Get Employee List</button>
             <button type="button" class="btn btn-success" onclick="window.location='salary_process_view.php'">View Salary Info.</button>
          </div>
        </div>
        <div id="employee_details_div">
          
        </div>
			</form>
      <form id="employee_salary_form" name="employee_salary_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
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
        <div id="other_salary_fields">
          <input type="hidden" name="emp_id" id='emp_id'>
          <div class="control-group">
            <label class="control-label">Number of Working Days:</label>
            <div class="controls">
              <input type="text" class="form-control required number" name="working_days" id="working_days" placeholder="Number of working days" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Loss of Pay(Days):</label>
            <div class="controls">
              <input type="text" class="form-control required number" name="lop" id="lop" placeholder="Loss of pay" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Vehicle Maintenance:</label>
            <div class="controls">
              <input type="text" class="form-control required" name="vm" id="vm" placeholder="Vehicle maintenance" value="0" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Pending Advance:</label>
            <label class="control-label" id="pending_advance_lbl"></label>
          </div>
          <div class="control-group">
            <label class="control-label">Advance:</label>
            <div class="controls">
              <input type="text" class="form-control required" name="advance" id="advance" placeholder="Advance" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Professional Tax:</label>
            <div class="controls">
              <input type="text" class="form-control required" name="professional_tax" id="professional_tax" placeholder="Professional tax" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">OD:</label>
            <div class="controls">
              <input type="text" class="form-control required number" name="od" id="od" placeholder="OD" value="0" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">TDS:</label>
            <div class="controls">
              <input type="text" class="form-control required number" value="0" name="tds" id="tds" placeholder="TDS" />
            </div>
          </div>
        </div>

        <div class="control-group row-fluid" id="compute_net_pay_btn_div" style="padding-bottom: 10px;">
          <div class="span4 text-right">
             <button type="button" class="btn btn-primary" onclick="computeNetPay();">Compute Net Pay</button>
             
          </div>
        </div>

        <div id="net_pay_div"></div>

        <div class="control-group row-fluid" style="padding-bottom: 10px;" id="save_btn_div" >
          <div class="span4 text-right">
            <button type="submit" class="btn btn-success" onclick="saveSalary();">Save</button>
          </div>
        </div>   
      </form>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<!-- view employee list modal -->
<div class="modal fade large" id="view_employees_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Employee List</h4>

            </div>
            <div class="modal-body">
              <div id="employee_div" class="widget-box-90">
          
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
<!-- view employee list modal ends -->



<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here
$(document).ready(function(){
    $('#salary_process_form').validate();
    $('#li_sc_ticketmenu').addClass('active open');
    $('#contract_start_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $('employee_details_div').hide();
    $('#employee_salary_form').hide();
    $('#net_pay_div').hide();
    $('#save_btn_div').hide();

    $('#date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
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
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});


var gEmployeeList = Array;
var gEmployee = {};
var gSalaryInfo = {};

function getEmployeesList(){
  // hide attendance list if shown 
  $('#attendance_view_div').hide();

  if($('#salary_process_form').valid()){ 
    var data = $('#salary_process_form').serialize() + '&action=get_employee_list';
    $.ajax({
        url: "hr_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            if(result.infocode == "GETEMPLOYEELISTSUCCESS"){
                var employeeData = JSON.parse(result.data);
                gEmployeeList = employeeData;
                //console.log(gEmployeeList);
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
  output += '<table id="employee_list" class="table table-striped table-bordered" cellspacing="0" width="100%">';
  output += '<thead>';
    output += '<tr>';
      output += '<th>S. No.</th>';
      output += '<th>Employee ID</th>';
      output += '<th>Employee Name</th>';
      output += '<th>Designation</th>';
      output += '<th>Action</th>';
    output += '</tr>';
  output += '</thead>';
  output += '<tbody>';
  for (employee in employeeData){
    count++;
    output += '<tr>';
      output += '<td>'+count+'</td>';
      output += '<td>' + employeeData[employee].emp_id + '</td>';
      output += '<td>' + employeeData[employee].employee_name + '</td>';
      output += '<td>' + employeeData[employee].designation + '</td>';
      output += '<td>';
        output += '<button class="btn btn-primary" data-dismiss="modal" onclick="getSelectedEmployee('+employeeData[employee].emp_id+')">Select</button>';
      output += '</td>';
    output += '</tr>';
  }
  output += '</tbody>';
  output += '</table>';
  $('#employee_div').html(output);
  $('#view_employees_modal').modal();
}


function getSelectedEmployee(empID){
  var data = "emp_id=" + empID + "&action=get_employee";
  $.ajax({
    url: "hr_services.php",
    type: "POST",
    data:  data,
    dataType: 'json',
    success: function(result){
      if(result.infocode == "GETEMPLOYEESUCCESS"){
          var employee = JSON.parse(result.data);
          displayEmployeeDetails(employee);
          gEmployee = employee;
          $('#payment_mode_div').show();
          $('#compute_net_pay_btn_div').show();
          $('#other_salary_fields').show();
      }else{
          bootbox.alert(result.message);
      }
      getAdvanceDetails(empID);
      if(gEmployee.pf == 'Yes'){
        determineProfessionalTax();
      } else {
        $('#professional_tax').val('0');
      }
    },
    error: function(){}
  });
}

function getAdvanceDetails(empID){
  var data = "emp_id=" + empID + "&action=get_advance_details";
  $.ajax({
    url: "hr_services.php",
    type: "POST",
    data:  data,
    dataType: 'json',
    success: function(result){
      if(result.infocode == "ADVANCEAVAILABLE"){
          $('#pending_advance_lbl').html(result.advance_amount);
          if(result.deduction_type == 'auto'){
            if(parseInt(result.advance_amount) < parseInt(result.deduction_amount)){
              $('#advance').val(result.advance_amount);
            } else {
              $('#advance').val(result.deduction_amount);
            }
          } else {
            $('#advance').val(0);
          }
      }
    },
    error: function(){}
  });
}

function displayEmployeeDetails(employee){
  $('#emp_id').val(employee.emp_id);

  var output = '';
  output += '<div class="control-group">';
    output += '<label class="control-label">Employee ID:</label>';
    output += '<label class="control-label">'+employee.emp_id+'</label>';
  output += '</div>';
  output += '<div class="control-group">';
    output += '<label class="control-label">Employee Name:</label>';
    output += '<label class="control-label">'+employee.employee_name+'</label>';
  output += '</div>';
  output += '<div class="control-group">';
    output += '<label class="control-label">Designation:</label>';
    output += '<label class="control-label">'+employee.designation+'</label>';
  output += '</div>';
  output += '<div class="control-group">';
    output += '<label class="control-label">Payment Mode:</label>';
    output += '<label class="control-label">'+employee.payment_mode+'</label>';
  output += '</div>';
  output += '<div class="control-group">';
    output += '<label class="control-label">CTC Monthly:</label>';
    output += '<label class="control-label">'+employee.ctc_monthly+'</label>';
  output += '</div>';

  if(employee.payment_mode == 'Bank'){
    $('#bank_acc_number').val(employee.bank_acc_num);
    $('#bank_name').val(employee.bank_name);
    $('#ifsc').val(employee.bank_ifsc);
  }

  $('#employee_details_div').html(output);
  $('employee_details_div').show();
  $('#employee_salary_form').show();
  // window.setTimeout(determineProfessionalTax(), 5000);
}

function determineProfessionalTax(){
  var ctc = parseInt(gEmployee.ctc_monthly);
  if(ctc > 12500){
    $('#professional_tax').val('183');
  } else if(ctc > 10000 && ctc <= 12500){
    $('#professional_tax').val('127');
  } else if(ctc > 7500 && ctc <= 10000){
    $('#professional_tax').val('85');
  } else if(ctc > 5000 && ctc <= 7500){
    $('#professional_tax').val('30');
  } else if(ctc < 3500){
    $('#professional_tax').val('17');
  }
}

function computeNetPay(){
  if($('#employee_salary_form').valid()){
    var ctc = parseInt(gEmployee.ctc_monthly);
    var basicPercentage = parseFloat(gEmployee.basic_percentage);
    var hraPercentage = parseFloat(gEmployee.hra_percentage);
    var conAll = parseFloat(gEmployee.conveyance_allowance);
    var medAll = parseFloat(gEmployee.medical_allowance);

    //earnings
    var basic = 0;
    var hra = 0;
    var conveyanceAllowance = 0;
    var medicalAllowance = 0;
    var specialAllowance = 0;
    var vm = parseInt($('#vm').val());
    //deductions
    var advance = parseInt($('#advance').val());
    var pf = 0;
    var esi = 0;
    var professionalTax = 0;
    var od = parseInt($('#od').val());
    var tds = parseInt($('#tds').val());

    if(gEmployee.pf == 'Yes'){
      basic = (parseFloat(basicPercentage)/100) * ctc;
      hra = (parseFloat(hraPercentage)/100) * basic;
      conveyanceAllowance = conAll;
      medicalAllowance = medAll;
      specialAllowance = ctc - (basic + hra + conveyanceAllowance + medicalAllowance);
      pf = 0.12 * basic;
      esi = 0.0175 * ctc;
      professionalTax = parseFloat($('#professional_tax').val());
    }

    //employer contributions
    var employerPF = 0.136 * basic;
    var employerESI = 0.0475 * ctc;

    //lop
    var payableDays = parseInt($('#working_days').val());
    var lopDays = parseInt($('#lop').val());
    var paidDays = payableDays - lopDays;
    var payDays = paidDays / payableDays;

    var paidBasic = basic * payDays;
    var paidHra = hra * payDays;
    var paidConvAll = conveyanceAllowance * payDays;
    var paidMedAll = medicalAllowance * payDays;
    var paidSplAll = specialAllowance * payDays;

    var grossSalary = paidBasic + paidHra + paidConvAll + paidMedAll + paidSplAll;
    var totalDeductions = advance + pf + esi + professionalTax + od + tds;
    if(gEmployee.pf != 'Yes'){
      grossSalary = ctc;
      var lopAmount = ctc * (lopDays / payableDays); 
      totalDeductions += lopAmount;
    }
    totalDeductions = Math.round(totalDeductions);

    var netPay = grossSalary - totalDeductions + vm;
    netPay = Math.round(netPay);

    gSalaryInfo.basic_monthly = paidBasic;
    gSalaryInfo.hra = paidHra;
    gSalaryInfo.special_allowance = paidSplAll;
    gSalaryInfo.vm = vm;
    gSalaryInfo.advance = advance;
    gSalaryInfo.pf = pf;
    gSalaryInfo.esi = esi;
    gSalaryInfo.professional_tax = professionalTax;
    gSalaryInfo.od = od;
    gSalaryInfo.tds = tds;
    gSalaryInfo.employer_pf = employerPF;
    gSalaryInfo.employer_esi = employerESI;
    gSalaryInfo.lop = lopDays;
    // gSalaryInfo.lop_amount = lopAmount;
    gSalaryInfo.working_days = payableDays;
    gSalaryInfo.total_earnings = grossSalary;
    gSalaryInfo.total_deductions = totalDeductions;
    gSalaryInfo.net_pay = netPay;

    var output = '';
    output += '<div class="control-group">';
      output += '<label class="control-label">Total Earnings:</label>';
      output += '<label class="control-label">'+grossSalary+'</label>';
    output += '</div>';
    output += '<div class="control-group">';
      output += '<label class="control-label">Total Deductions:</label>';
      output += '<label class="control-label">'+totalDeductions+'</label>';
    output += '</div>';
    output += '<div class="control-group">';
      output += '<label class="control-label">Net Pay:</label>';
      output += '<div class="controls">';
        output += '<input type="text" name="net_pay" id="net_pay" disabled="true" value="'+netPay+'" />';
      output += '</div>';
    output += '</div>';

    $('#net_pay_div').html(output);
    $('#net_pay_div').show();
    $('#save_btn_div').show();
  }
}

function saveSalary(){
  var data = $('#employee_salary_form').serialize() + '&salary_info=' + JSON.stringify(gSalaryInfo) + "&action=save_salary";
  $.ajax({
    url: "hr_services.php",
    type: "POST",
    data:  data,
    dataType: 'json',
    success: function(result){
      if(result.infocode == "SAVESALARYSUCCESS"){
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

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>
