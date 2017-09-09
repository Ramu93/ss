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
          <h5>Salary List</h5>
        </div>
        <form id="employee_salary_form" name="employee_salary_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
        <!-- <div class="control-group">
          <label class="control-label">From Date:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="from_date" id="from_date" placeholder="From date" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">To Date:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="to_date" id="to_date" placeholder="To date" />
          </div>
        </div> -->
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
        <div class="control-group row-fluid" style="padding-bottom: 10px;" id="save_btn_div" >
          <div class="span4 text-right">
            <button type="submit" class="btn btn-success" onclick="getSalaryList();">Get Salary Details</button>
          </div>
        </div> 
      </form>
      <div class="widget-content nopadding" id='salary_details_table'>
			<table id="salary_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>S. No.</th>
            <th>Employee ID</th>
  					<th>Employee Name</th>
            <th>Salary Date</th>
            <th>Net Pay</th>
            <th>Action</th>
          </tr>
        </thead>
				<tbody id='salary_details_tbody'>
          
        </tbody>
      </table>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<!-- view employee salary modal -->
<div class="modal fade large" id="view_employee_salary_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Salary Details</h4>

            </div>
            <div class="modal-body">
              <div id="salary_details_modal_div">
                
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
<!-- view employee salary ends -->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here
$(document).ready(function() {
    $('#li_sc_engineermenu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');
    $('#from_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        maxDate: 'now'
    });
    $('#to_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        maxDate: 'now'
    });

    $('#salary_details_table').hide();
});

function getSalaryList(){
  //var fromDate = $('#from_date').val();
  //var toDate = $('#to_date').val();
  if($('#employee_salary_form').valid()){
    var department = $('#department').val();
    var office = $('#office').val();
    var location = $('#location').val();
    var data = 'department=' + department + '&office=' + office + '&location=' + location + '&action=get_salary_list'; 
    $.ajax({
      url: "hr_services.php",
      type: "POST",
      data:  data,
      dataType: 'json',
      success: function(result){
        if(result.infocode == "GETSALARYLISTSUCCESS"){
            var salaryList = JSON.parse(result.data);
            displaySalaryDetails(salaryList);
        }else{
            bootbox.alert(result.message);
        }
      },
      error: function(){}
    });
  }
}

function displaySalaryDetails(salaryList){
  var count = 0;
  var output = '';
  for(salary in salaryList){
    count++;
    output += '<tr>';
      output += '<td>'+count+'</td>';
      output += '<td>'+salaryList[salary].emp_id+'</td>';
      output += '<td>'+salaryList[salary].employee_name+'</td>';
      output += '<td>'+salaryList[salary].salary_date+'</td>';
      output += '<td>'+salaryList[salary].net_pay+'</td>';
      output += '<td><button class="btn btn-primary" onclick="viewDetails('+salaryList[salary].hr_salary_process_id+')">View Details</button></td>';
    output += '</tr>';
  }
  $('#salary_details_tbody').html(output);
  $('#salary_details_table').show();
}

function viewDetails(salaryProcessID){
  var data = 'salary_process_id=' + salaryProcessID + '&action=get_emp_salary_detail';
  $.ajax({
      url: "hr_services.php",
      type: "POST",
      data:  data,
      dataType: 'json',
      success: function(result){
        if(result.infocode == "GETEMPLOYEESALARYSUCCESS"){
          var salaryDetail = JSON.parse(result.data);
          displayEmployeeSalaryDetail(salaryDetail);
        }else{
            bootbox.alert(result.message);
        }
      },
      error: function(){}
    });
}

function displayEmployeeSalaryDetail(salaryDetail){
  var output = '';
  output += '<div class="control-group">';
    output += '<label class="control-label">Basic Monthly:</label>';
    output += '<label class="control-label">'+salaryDetail.basic_monthly+'</label>';
  output += '</div>';
  output += '<div class="control-group">';
    output += '<label class="control-label">HRA:</label>';
    output += '<label class="control-label">'+salaryDetail.hra+'</label>';
  output += '</div>';
  // output += '<div class="control-group">';
  //   output += '<label class="control-label">Special Allowance:</label>';
  //   output += '<label class="control-label">'+salaryDetail.special_allowance+'</label>';
  // output += '</div>';
  // output += '<div class="control-group">';
  //   output += '<label class="control-label">VM:</label>';
  //   output += '<label class="control-label">'+salaryDetail.vm+'</label>';
  // output += '</div>';
  // output += '<div class="control-group">';
  //   output += '<label class="control-label">Professional Tax:</label>';
  //   output += '<label class="control-label">'+salaryDetail.professional_tax+'</label>';
  // output += '</div>';
  // output += '<div class="control-group">';
  //   output += '<label class="control-label">OD:</label>';
  //   output += '<label class="control-label">'+salaryDetail.od+'</label>';
  // output += '</div>';
  // output += '<div class="control-group">';
  //   output += '<label class="control-label">TDS:</label>';
  //   output += '<label class="control-label">'+salaryDetail.tds+'</label>';
  // output += '</div>';
  // output += '<div class="control-group">';
  //   output += '<label class="control-label">Advance:</label>';
  //   output += '<label class="control-label">'+salaryDetail.advance+'</label>';
  // output += '</div>';
  output += '<div class="control-group">';
    output += '<label class="control-label">Working Days:</label>';
    output += '<label class="control-label">'+salaryDetail.working_days+'</label>';
  output += '</div>';
  output += '<div class="control-group">';
    output += '<label class="control-label">LOP:</label>';
    output += '<label class="control-label">'+salaryDetail.lop+'</label>';
  output += '</div>';
  output += '<div class="control-group">';
    output += '<label class="control-label">Total Earnings:</label>';
    output += '<label class="control-label">'+salaryDetail.total_earnings+'</label>';
  output += '</div>';
  output += '<div class="control-group">';
    output += '<label class="control-label">Total Deductions:</label>';
    output += '<label class="control-label">'+salaryDetail.total_deductions+'</label>';
  output += '</div>';
  output += '<div class="control-group">';
    output += '<label class="control-label">Net Pay:</label>';
    output += '<label class="control-label">'+salaryDetail.net_pay+'</label>';
  output += '</div>';
  $('#salary_details_modal_div').html(output);
  $('#view_employee_salary_modal').modal();
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>
