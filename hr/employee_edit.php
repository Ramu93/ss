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
          <label class="control-label">Marital Status:</label>
          <div class="controls">
             <select class="form-control required" name="marital_status" id="marital_status">
                <option value="">Select gender...</option>
                <option valu="Single" <?php echo (($out['marital_status']=='Single')?'selected="selected"':''); ?> >Single</option>
                <option valu="Married" <?php echo (($out['marital_status']=='Married')?'selected="selected"':''); ?> >Married</option>
             </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Gender:</label>
          <div class="controls">
             <select class="form-control required" name="gender" id="gender">
                <option value="">Select gender...</option>
                <option valu="Male" <?php echo (($out['gender']=='Male')?'selected="selected"':''); ?> >Male</option>
                <option valu="Female" <?php echo (($out['gender']=='Female')?'selected="selected"':''); ?> >Female</option>
             </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Blood Group:</label>
          <div class="controls">
             <select class="form-control required" name="blood_group" id="blood_group">
                <option value="">Select blood group...</option>
                <option value="A +ve" <?php echo (($out['blood_group']=='A +ve')?'selected="selected"':''); ?> >A +ve</option>
                <option value="A -ve" <?php echo (($out['blood_group']=='A -ve')?'selected="selected"':''); ?> >A -ve</option>
                <option value="A Unknown" <?php echo (($out['blood_group']=='A Unknown')?'selected="selected"':''); ?> >A Unknown</option>
                <option value="B +ve" <?php echo (($out['blood_group']=='B +ve')?'selected="selected"':''); ?> >B +ve</option>
                <option value="B -ve" <?php echo (($out['blood_group']=='B -ve')?'selected="selected"':''); ?> >B -ve</option>
                <option value="B Unknown" <?php echo (($out['blood_group']=='B Unknown')?'selected="selected"':''); ?> >B Unknown</option>
                <option value="AB +ve" <?php echo (($out['blood_group']=='AB +ve')?'selected="selected"':''); ?> >AB +ve</option>
                <option value="AB -ve" <?php echo (($out['blood_group']=='AB -ve')?'selected="selected"':''); ?> >AB -ve</option>
                <option value="AB Unknown" <?php echo (($out['blood_group']=='AB Unknown')?'selected="selected"':''); ?> >AB Unknown</option>
                <option value="O +ve" <?php echo (($out['blood_group']=='O +ve')?'selected="selected"':''); ?> >O +ve</option>
                <option value="O -ve" <?php echo (($out['blood_group']=='O -ve')?'selected="selected"':''); ?> >O -ve</option>
                <option value="O Unknown" <?php echo (($out['blood_group']=='O Unknown')?'selected="selected"':''); ?> >O Unknown</option>
                <option value="Unknown" <?php echo (($out['blood_group']=='Unknown')?'selected="selected"':''); ?> >Unknown</option>
             </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Name of the Father/Husband:</label>
          <div class="controls">
             <input type="text" class="form-control required" name="name_father_husband" id="name_father_husband" placeholder="Name of Father/Husband" value="<?php echo $out['husband_father_name']; ?>" />
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
          <label class="control-label">Permanent Address:</label>
          <div class="controls">
               <textarea name="permanent_address" id="permanent_address" class="form-control required"><?php echo $out['permanent_address']; ?></textarea>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Mobile Number:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="mobile_number" id="mobile_number" placeholder="Mobile number" value="<?php echo $out['mobile_number']; ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Email:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="email" id="email" placeholder="Email" value="<?php echo $out['email']; ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Emergency Contact Number:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="emergency_contact_number" id="emergency_contact_number" placeholder="Emergency contact number" value="<?php echo $out['emergency_contact_no']; ?>" />
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
        <div class="control-group row-fluid" style="padding-bottom: 10px;">
          <div class="span4 text-right">
             <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#academic_details_modal">Academic Details</button>
          </div>
          <div class="span4">
             <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#family_details_modal">Family Details</button>
          </div>
          <div class="span4">
             <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#employement_details_modal">Previous Employement</button>
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
                 <option value="">Select office...</option>
                 <?php 
                    foreach($office as $key => $value){
                      if($key == $out['office']){
                        echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
                      } else {
                        echo '<option value="'.$key.'">'.$value.'</option>';
                      }
                    }
                 ?>
               </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Location:</label>
          <div class="controls">
               <select class="form-control required" name="location" id="location">
                 <option value="" >Select location...</option>
                 <?php 
                    foreach($location as $key => $value){
                      if($key == $out['location']){
                        echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
                      } else {
                        echo '<option value="'.$key.'">'.$value.'</option>';
                      }
                    }
                 ?>
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
                 <option value="">Select department...</option>
                 <?php 
                    foreach($department as $key => $value){
                      if($key == $out['department']){
                        echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
                      } else {
                        echo '<option value="'.$key.'">'.$value.'</option>';
                      }
                    }
                 ?>
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
          <label class="control-label">PF:</label>
          <div class="controls">
               <input type="radio" class="form-control" name="emp_pf" id="pf_yes" value="Yes" <?php echo ('Yes'==$out['pf'])?'checked':''; ?> >Yes&nbsp;&nbsp;&nbsp;<input type="radio" id="pf_no" name="emp_pf" value="No" <?php echo ('No'==$out['pf'])?'checked':''; ?> >No
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

<!-- Academic details modal -->
<div class="modal fade large" style="width: 80%; margin-left: -40% !important;" id="academic_details_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Academic Details</h4>

            </div>
            <div class="modal-body">
              <div class="widget-content nopadding">
                <table id="education_list" class="table table-striped table-bordered" cellspacing="0" width="50%">
                  <thead>
                    <tr>
                      <th>Qualification</th>
                      <th>Institution/University</th>
                      <th>Year</th>
                      <th>Percentage</th>
                    </tr>
                  </thead>
                  <tbody id="addEducationItem_tbody">
                    
                  </tbody>
                </table>
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
<!-- Academic details modal ends -->

<!-- family details modal -->
<div class="modal fade large" style="width: 70%; margin-left: -40% !important;" id="family_details_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Family Details</h4>

            </div>
            <div class="modal-body">
              <div class="widget-content nopadding">
                <table id="family_list" class="table table-striped table-bordered" cellspacing="0" width="50%">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>DOB</th>
                      <th>Gender</th>
                      <th>Relationship</th>
                    </tr>
                  </thead>
                  <tbody id="addFamilyitem_tbody">
                    
                  </tbody>
                </table>
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
<!-- family details modal ends -->

<!-- employement details modal -->
<div class="modal fade large" style="width: 88%; margin-left: -45% !important;" id="employement_details_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Previous Employement Details</h4>

            </div>
            <div class="modal-body">
              <div class="widget-content nopadding">
                <table id="family_list" class="table table-striped table-bordered" cellspacing="0" width="50%">
                  <thead>
                    <tr>
                      <th>Name of the Employer</th>
                      <th>Position</th>
                      <th>Period</th>
                      <th>Reason for leaving</th>
                      <th>Reference</th>
                    </tr>
                  </thead>
                  <tbody id="addEmploymentitem_tbody">
                    
                  </tbody>
                </table>
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
<!-- employement details modal ends -->

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
    $('.dob').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        maxDate: 'now'
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
  makeEducationDetailsJSON();
  makeFamilyDetailsJSON();
  makeEmploymentDetailsJSON();

  var empID = "<?php echo $_GET['emp_id']; ?>";
  if($('#updateemployee_form').valid()){ 
    var data = $('#updateemployee_form').serialize() + "&emp_id=" + empID + '&academic_details=' + JSON.stringify(g_academicDetails) + '&family_details=' + JSON.stringify(g_familyDetails) + '&employment_details=' + JSON.stringify(g_employmentDetails) + '&action=update_employee';
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

// academic detail modal and template starts
var addEduitem_template = '<tr id="[trid]">\
              <td><input type="text" name="qualification[]" placeholder="" class="form-control value=""></td>\
              <td><input type="text" name="institution[]" placeholder="" class="form-control" value=""></td>\
              <td><input type="text" name="year[]" placeholder="" class="form-control" value=""></td>\
              <td><input type="text" name="percentage[]" placeholder="" class="form-control" value=""></td>\
              </td>\
              <td><button onclick="addEducationRow([addcount]);">+</button><button class="eduitem_removebutton" style="display:none;" onclick="removeEducationRow([removecount])">-</button></td></tr>';

var editEduitem_template = '<tr id="[trid]">\
              <td><input type="text" name="qualification[]" placeholder="" class="form-control" value="[edit_qualification]"></td>\
              <td><input type="text" name="institution[]" placeholder="" class="form-control" value="[edit_institution]"></td>\
              <td><input type="text" name="year[]" placeholder="" class="form-control" value="[edit_year]"></td>\
              <td><input type="text" name="percentage[]" placeholder="" class="form-control" value="[edit_percentage]"></td>\
              </td>\
              <td><button onclick="addEducationRow([addcount]);">+</button><button class="eduitem_removebutton" style="display:none;" onclick="removeEducationRow([removecount])">-</button></td></tr>';


var g_rowcountEducaion = 0;
var g_educationRowCount = 0;

var g_academicDetails = [];
<?php 
  if(!is_null($out['academic_details']) && !empty($out['academic_details'])){
    echo 'g_academicDetails = ' . json_encode(json_decode($out['academic_details'], true)) . ';';
    echo 'setAcademicDetailValues();';
  } else {
    echo 'addEducationRow(1);';
  }
?>

//called at addEmployeeDetails method
function makeEducationDetailsJSON(){
  g_academicDetails = [];
  var qualificationData = $("input[name='qualification[]']").map(function(){return $(this).val();}).get();
  var institutionData = $("input[name='institution[]']").map(function(){return $(this).val();}).get();
  var yearData = $("input[name='year[]']").map(function(){return $(this).val();}).get();
  var percentageData = $("input[name='percentage[]']").map(function(){return $(this).val();}).get();

  for(var i = 0; i < g_educationRowCount; i++){
    var academicObject = {};
    academicObject.qualification = qualificationData[i];
    academicObject.institution = institutionData[i];
    academicObject.year = yearData[i];
    academicObject.percentage = percentageData[i];
    g_academicDetails.push(academicObject);
  }
}

function addEducationRow(rowcount){
  var addrow = addEduitem_template.replace('[trid]','eduitemtr_'+g_rowcountEducaion)
                .replace('[addcount]',g_rowcountEducaion)
                .replace('[removecount]',g_rowcountEducaion);
  $('#addEducationItem_tbody').append(addrow);
  if(g_rowcountEducaion >= 1){
    $('.eduitem_removebutton').show();
  }
  g_rowcountEducaion++;
  g_educationRowCount++;
}

function removeEducationRow(rowcount){
  $('#eduitemtr_'+rowcount).remove();
  g_educationRowCount--;
  if(g_educationRowCount < 2){
    $('.eduitem_removebutton').hide();
  }
}

function addAcademicDataFilledRow(academicDetail){
  var addrow = editEduitem_template.replace('[trid]','eduitemtr_'+g_rowcountEducaion)
                .replace('[edit_qualification]',academicDetail.qualification)
                .replace('[edit_institution]',academicDetail.institution)
                .replace('[edit_year]',academicDetail.year)
                .replace('[edit_percentage]',academicDetail.percentage)
                .replace('[addcount]',g_rowcountEducaion)
                .replace('[removecount]',g_rowcountEducaion);
  $('#addEducationItem_tbody').append(addrow);
  g_rowcountEducaion++;
  $('.eduitem_removebutton').show();
}

function setAcademicDetailValues(){
  if(g_academicDetails.length > 0){
    g_educationRowCount = g_academicDetails.length;
    g_rowcountEducaion = g_academicDetails.length;
    g_academicDetails.forEach( function(academicDetail, index) {
      addAcademicDataFilledRow(academicDetail);
    });
    g_academicDetails = [];  
  }
}

// academic detail modal and template ends

// family detail modal and template starts
var addFamilyitem_template = '<tr id="[trid]">\
              <td><input type="text" name="name[]" placeholder="" class="form-control value=""></td>\
              <td><input type="text" name="dob[]" placeholder="" class="form-control dob" value=""></td>\
              <td>\
                <select class="form-control" name="gender[]">\
                  <option value="">Gender...</option>\
                  <option value="Male">Male</option>\
                  <option value="Female">Female</option>\
                </select>\
              </td>\
              <td><input type="text" name="relationship[]" placeholder="" class="form-control" value=""></td>\
              </td>\
              <td><button onclick="addFamilyRow([addcount]);">+</button><button class="familyitem_removebutton" style="display:none;" onclick="removeFamilyRow([removecount])">-</button></td></tr>';

  var editFamilyitem_template = '<tr id="[trid]">\
              <td><input type="text" name="name[]" placeholder="" class="form-control" value="[edit_name]"></td>\
              <td><input type="text" name="dob[]" placeholder="" class="form-control dob" value="[edit_dob]"></td>\
              <td>\
                <select class="form-control" name="gender[]">\
                  <option value="">Gender...</option>\
                  <option value="Male" [edit_selected_male]>Male</option>\
                  <option value="Female" [edit_selected_female]>Female</option>\
                </select>\
              </td>\
              <td><input type="text" name="relationship[]" placeholder="" class="form-control" value="[edit_relationship]"></td>\
              </td>\
              <td><button onclick="addFamilyRow([addcount]);">+</button><button class="familyitem_removebutton" style="display:none;" onclick="removeFamilyRow([removecount])">-</button></td></tr>';


var g_rowcountFamily = 0;
var g_familyRowCount = 0;
var g_familyDetails = [];
<?php 
  if(!is_null($out['family_details']) && !empty($out['family_details'])){
    echo 'g_familyDetails = ' . json_encode(json_decode($out['family_details'], true)) . ';';
    echo 'setFamilyDetailValues();';
  } else {
    echo 'addFamilyRow(1);';
  }
?>

//called at addEmployeeDetails method
function makeFamilyDetailsJSON(){
  g_familyDetails = [];
  var nameData = $("input[name='name[]']").map(function(){return $(this).val();}).get();
  var dobData = $("input[name='dob[]']").map(function(){return $(this).val();}).get();
  var genderData = $("select[name='gender[]']").map(function(){return $(this).val();}).get();
  var relationshipData = $("input[name='relationship[]']").map(function(){return $(this).val();}).get();

  for(var i = 0; i < g_familyRowCount; i++){
    var familyObject = {};
    familyObject.name = nameData[i];
    familyObject.dob = dobData[i];
    familyObject.gender = genderData[i];
    familyObject.relationship = relationshipData[i];
    g_familyDetails.push(familyObject);
  }
}

function addFamilyRow(rowcount){
  var addrow = addFamilyitem_template.replace('[trid]','familyitemtr_'+g_rowcountFamily)
                .replace('[addcount]',g_rowcountFamily)
                .replace('[removecount]',g_rowcountFamily)
  $('#addFamilyitem_tbody').append(addrow);
  if(g_rowcountFamily >= 1){
    $('.familyitem_removebutton').show();
  }
  g_rowcountFamily++;
  g_familyRowCount++;
}

function removeFamilyRow(rowcount){
  $('#familyitemtr_'+rowcount).remove();
  g_familyRowCount--;
  if(g_familyRowCount < 2){
    $('.familyitem_removebutton').hide();
  }
}

function addFamilyDataFilledRow(familyDetail){
  var addrow = editFamilyitem_template.replace('[trid]','familyitemtr_'+g_rowcountFamily)
                .replace('[edit_name]',familyDetail.name)
                .replace('[edit_dob]',familyDetail.dob)
                .replace('[edit_relationship]',familyDetail.relationship)
                .replace('[addcount]',g_rowcountFamily)
                .replace('[removecount]',g_rowcountFamily);
                if(familyDetail.gender == 'Male'){
                  addrow = addrow.replace('[edit_selected_male]', 'selected="selected"');
                } else if(familyDetail.gender == 'Female') {
                  addrow = addrow.replace('[edit_selected_female]', 'selected="selected"');
                }
  $('#addFamilyitem_tbody').append(addrow);
  g_rowcountFamily++;
  $('.familyitem_removebutton').show();
}

function setFamilyDetailValues(){
  if(g_familyDetails.length > 0){
    g_familyRowCount = g_familyDetails.length;
    g_rowcountFamily = g_familyDetails.length;
    g_familyDetails.forEach( function(familyDetail, index) {
      addFamilyDataFilledRow(familyDetail);
    });
    g_familyDetails = [];  
  }
}
// family detail modal and template ends


// employment detail modal and template starts
var addEmploymentitem_template = '<tr id="[trid]">\
              <td><input type="text" name="employer_name[]" placeholder="" class="form-control value=""></td>\
              <td><input type="text" name="position[]" placeholder="" class="form-control" value=""></td>\
              <td><input type="text" name="from[]" placeholder="From" class="form-control dob" value=""><br /><input type="text" name="to[]" placeholder="To" class="form-control dob" value=""></td>\
              <td><input type="text" name="leaving_reason[]" placeholder="" class="form-control" value=""></td>\
              <td><input type="text" name="reference[]" placeholder="" class="form-control" value=""></td>\
              </td>\
              <td><button onclick="addEmploymentRow([addcount]);">+</button><button class="empitem_removebutton" style="display:none;" onclick="removeEmploymentRow([removecount])">-</button></td></tr>';

var editEmploymentitem_template = '<tr id="[trid]">\
              <td><input type="text" name="employer_name[]" placeholder="" class="form-control" value="[edit_employer_name]"></td>\
              <td><input type="text" name="position[]" placeholder="" class="form-control" value="[edit_position]"></td>\
              <td><input type="text" name="from[]" placeholder="From" class="form-control dob" value="[edit_from]"><br /><input type="text" name="to[]" placeholder="To" class="form-control dob" value="[edit_to]"></td>\
              <td><input type="text" name="leaving_reason[]" placeholder="" class="form-control" value="[edit_leaving_reason]"></td>\
              <td><input type="text" name="reference[]" placeholder="" class="form-control" value="[edit_reference]"></td>\
              </td>\
              <td><button onclick="addEmploymentRow([addcount]);">+</button><button class="empitem_removebutton" style="display:none;" onclick="removeEmploymentRow([removecount])">-</button></td></tr>';


var g_rowcountEmployment = 0;
var g_employmentRowCount = 0;
var g_employmentDetails = [];
<?php 
  if(!is_null($out['employment_details']) && !empty($out['employment_details'])){
    echo 'g_employmentDetails = ' . json_encode(json_decode($out['employment_details'], true)) . ';';
    echo 'setEmploymentDetailValues();';
  } else {
    echo 'addEmploymentRow(1);';
  }
?>

//called at addEmployeeDetails method
function makeEmploymentDetailsJSON(){
  g_employmentDetails = [];
  var employerNameData = $("input[name='employer_name[]']").map(function(){return $(this).val();}).get();
  var positionData = $("input[name='position[]']").map(function(){return $(this).val();}).get();
  var fromData = $("input[name='from[]']").map(function(){return $(this).val();}).get();
  var toData = $("input[name='to[]']").map(function(){return $(this).val();}).get();
  var leavingReasonData = $("input[name='leaving_reason[]']").map(function(){return $(this).val();}).get();
  var referenceData = $("input[name='reference[]']").map(function(){return $(this).val();}).get();

  for(var i = 0; i < g_employmentRowCount; i++){
    var employmentObject = {};
    employmentObject.employer_name = employerNameData[i];
    employmentObject.position = positionData[i];
    employmentObject.from = fromData[i];
    employmentObject.to = toData[i];
    employmentObject.leaving_reason = leavingReasonData[i];
    employmentObject.reference = referenceData[i];
   
    g_employmentDetails.push(employmentObject);
  }
}

function addEmploymentRow(rowcount){
  var addrow = addEmploymentitem_template.replace('[trid]','employmentitemtr_'+g_rowcountEmployment)
                .replace('[addcount]',g_rowcountEmployment)
                .replace('[removecount]',g_rowcountEmployment)
  $('#addEmploymentitem_tbody').append(addrow);
  if(g_rowcountEmployment >= 1){
    $('.empitem_removebutton').show();
  }
  g_rowcountEmployment++;
  g_employmentRowCount++;
}

function removeEmploymentRow(rowcount){
  $('#employmentitemtr_'+rowcount).remove();
  g_employmentRowCount--;
  if(g_employmentRowCount < 2){
    $('.empitem_removebutton').hide();
  }
}

function addEmploymentDataFilledRow(employmentDetail){
  var addrow = editEmploymentitem_template.replace('[trid]','employmentitemtr_'+g_rowcountEmployment)
                .replace('[edit_employer_name]',employmentDetail.employer_name)
                .replace('[edit_position]',employmentDetail.position)
                .replace('[edit_from]',employmentDetail.from)
                .replace('[edit_to]',employmentDetail.to)
                .replace('[edit_leaving_reason]',employmentDetail.leaving_reason)
                .replace('[edit_reference]',employmentDetail.reference)
                .replace('[addcount]',g_rowcountEmployment)
                .replace('[removecount]',g_rowcountEmployment);
  $('#addEmploymentitem_tbody').append(addrow);
  g_rowcountEmployment++;
  $('.empitem_removebutton').show();
}

function setEmploymentDetailValues(){
  if(g_employmentDetails.length > 0){
    g_employmentRowCount = g_employmentDetails.length;
    g_rowcountEmployment = g_employmentDetails.length;
    g_employmentDetails.forEach( function(employmentDetail, index) {
      addEmploymentDataFilledRow(employmentDetail);
    });
    g_employmentDetails = [];  
  }
}
// employment detail modal and template ends


</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>
