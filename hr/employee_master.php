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

	<div class="widget-box widget-box-10">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Employee Master</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="addemployee_form" name="addemployee_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
          <label class="control-label">Name of the Employee:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="employee_name" id="employee_name" placeholder="Employee name" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Date of birth:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="dob" id="dob" placeholder="Date of birth" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Address for Communication:</label>
          <div class="controls">
               <textarea name="communication_address" id="communication_address" class="form-control required"></textarea>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">PAN Card Details:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="pan_details" id="pan_details" placeholder="PAN Details" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Aadhar Card Details:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="aadhar_details" id="aadhar_details" placeholder="Aadhar Details" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Employee ID:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="emp_id" id="emp_id" placeholder="Employee ID" />
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
        <div class="control-group">
          <label class="control-label">Designation:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="designation" id="designation" placeholder="Designation" />
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
        <div class="control-group">
          <label class="control-label">CTC Monthly:</label>
          <div class="controls">
               <input type="text" class="form-control required number" name="ctc_monthly" id="ctc_monthly" placeholder="CTC monthly" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Date of joining:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="doj" id="doj" placeholder="Date of joining" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Qualification:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="qualification" id="qualification" placeholder="Qualification" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Experience:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="experience" id="experience" placeholder="Experience" />
          </div>
        </div>
        <div class="control-group row-fluid" style="padding-bottom: 10px;">
          <div class="span4 text-right">
             <button type="submit" class="btn btn-success" onclick="addEmployeeDetails();">Add Employee</button>
          </div>
          <div class="span4">
             <button type="button" class="btn btn-primary" onclick="window.location = 'employee_view.php';">View Employees</button>
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
    $('#contract_start_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $('#dob').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        maxDate: 'now'
    });
    $('#doj').datepicker({
        format: 'yyyy-mm-dd',
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
});


$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function addEmployeeDetails(){
  if($('#addemployee_form').valid()){ 
    var data = $('#addemployee_form').serialize() + '&action=add_employee';
    $.ajax({
        url: "hr_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            if(result.infocode == "EMPLOYEEADDED"){
                bootbox.alert(result.message);
                $('#addemployee_form')[0].reset();
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
