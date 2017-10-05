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

$empID = isset($_GET['emp_id'])?$_GET['emp_id']:0;

$query = "SELECT * FROM hr_employee_master WHERE emp_id='$empID'";
$result = mysqli_query($dbc, $query);
$out = array();
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);
    $out = $row;
    if($out['payment_mode'] != 'Bank'){
      $out['bank_acc_num'] = '';
      $out['bank_name'] = '';
      $out['bank_ifsc'] = '';
    }
}


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
			<form id="updateemployee_form" name="addemployee_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
          <label class="control-label">Name of the Employee:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="employee_name" id="employee_name" placeholder="Employee name" value="<?php echo $out['employee_name']; ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Date of birth:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="dob" id="dob" placeholder="Date of birth" value="<?php echo $out['dob']; ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Address for Communication:</label>
          <div class="controls">
               <textarea name="communication_address" id="communication_address" class="form-control required"><?php echo $out['communication_address']; ?></textarea>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">PAN Card Details:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="pan_details" id="pan_details" placeholder="PAN Details" value="<?php echo $out['pan_details']; ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Aadhar Card Details:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="aadhar_details" id="aadhar_details" placeholder="Aadhar Details" value="<?php echo $out['aadhar_details']; ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Employee ID:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="emp_id" id="emp_id" placeholder="Employee ID" value="<?php echo $out['emp_id']; ?>" disabled="true"/>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Office/Entity:</label>
          <div class="controls">
               <select class="form-control required" name="office" id="office">
                 <option value="" selected="selected">Select office...</option>
                 <option value="SBBS" <?php echo ('SBBS'==$out['office'])?'selected="selected"':''; ?> >SBBS</option>
                 <option value="SBBM" <?php echo ('SBBM'==$out['office'])?'selected="selected"':''; ?> >SBBM</option>
               </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Location:</label>
          <div class="controls">
               <select class="form-control required" name="location" id="location">
                 <option value="" selected="selected">Select location...</option>
                 <option value="T. Nagar" <?php echo ('T. Nagar'==$out['location'])?'selected="selected"':''; ?> >T. Nagar</option>
                 <option value="Banglore" <?php echo ('Banglore'==$out['location'])?'selected="selected"':''; ?> >Banglore</option>
                 <option value="Poonamallee" <?php echo ('Poonamallee'==$out['location'])?'selected="selected"':''; ?>>Poonamallee</option>
               </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Designation:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="designation" id="designation" placeholder="Designation" value="<?php echo $out['designation']; ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Department:</label>
          <div class="controls">
               <select class="form-control required" name="department" id="department">
                 <option value="" selected="selected">Select department...</option>
                 <option value="HR" <?php echo ('HR'==$out['department'])?'selected="selected"':''; ?> >HR</option>
                 <option value="Marketing" <?php echo ('Marketing'==$out['department'])?'selected="selected"':''; ?> >Marketing</option>
                 <option value="Finance" <?php echo ('Finance'==$out['department'])?'selected="selected"':''; ?> >Finance</option>
                 <option value="Service" <?php echo ('Service'==$out['department'])?'selected="selected"':''; ?> >Service</option>
                 <option value="Client Accountant" <?php echo ('Client Accountant'==$out['department'])?'selected="selected"':''; ?> >Client Accountant</option>
                 <option value="Admin" <?php echo ('Admin'==$out['department'])?'selected="selected"':''; ?> >Admin</option>
                 <option value="Despatch" <?php echo ('Despatch'==$out['department'])?'selected="selected"':''; ?> >Despatch</option>
                 <option value="Materials" <?php echo ('Materials'==$out['department'])?'selected="selected"':''; ?> >Materials</option>
                 <option value="Tech" <?php echo ('Tech'==$out['department'])?'selected="selected"':''; ?> >Tech</option>
               </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Payment Mode:</label>
          <div class="controls">
              <select class="form-control required" name="payment_mode" id="payment_mode">
                <option value="" selected="selected">Select payment mode...</option>
                <option value="Bank" <?php echo ('Bank'==$out['payment_mode'])?'selected="selected"':''; ?> >Bank</option>
                <option value="Cash" <?php echo ('Cash'==$out['payment_mode'])?'selected="selected"':''; ?> >Cash</option>
              </select>
          </div>
        </div>
        <div id="bank_details_div">
          <div class="control-group">
            <label class="control-label">Bank Account Number:</label>
            <div class="controls">
                 <input type="text" class="form-control number" name="bank_acc_number" id="bank_acc_number" value="<?php echo $out['bank_acc_num']; ?>" placeholder="Bank Account Number" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Bank Name:</label>
            <div class="controls">
                 <input type="text" class="form-control" name="bank_name" id="bank_name" value="<?php echo $out['bank_name']; ?>" placeholder="Bank name" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">IFSC Code:</label>
            <div class="controls">
                 <input type="text" class="form-control" name="ifsc" id="ifsc" value="<?php echo $out['bank_ifsc']; ?>" placeholder="IFSC" />
            </div>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">CTC Monthly:</label>
          <div class="controls">
               <input type="text" class="form-control required number" name="ctc_monthly" id="ctc_monthly" placeholder="CTC monthly" value="<?php echo $out['ctc_monthly']; ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Date of joining:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="doj" id="doj" placeholder="Date of joining" value="<?php echo $out['doj']; ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Qualification:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="qualification" id="qualification" placeholder="Qualification" value="<?php echo $out['qualification']; ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Experience:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="experience" id="experience" placeholder="Experience" value="<?php echo $out['experience']; ?>" />
          </div>
        </div>
        <div class="control-group row-fluid" style="padding-bottom: 10px;">
          <div class="span4 text-right">
            <button type="button" class="btn btn-primary" onclick="window.location = 'employee_view.php';">Go Back</button>
          </div>
          <div class="span4">
             <button type="submit" class="btn btn-success" onclick="updateEmployeeDetails();">Update Employee</button>
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
        format: 'dd-mm-yyyy',
        autoclose: true
    });
    $('#dob').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
    $('#doj').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
    
    <?php if($out['payment_mode'] != 'Bank'){ ?>
    $('#bank_details_div').hide();
    <?php } ?>
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

function updateEmployeeDetails(){
  var empID = "<?php echo $_GET['emp_id']; ?>";
  if($('#updateemployee_form').valid()){ 
    var data = $('#updateemployee_form').serialize() + "&emp_id=" + empID + '&action=update_employee';
    $.ajax({
        url: "hr_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            if(result.infocode == "EMPLOYEEUPDATED"){
                bootbox.alert(result.message, function(){
                  window.location = 'employee_view.php';
                });
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
