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
          <label class="control-label">Marital Status:</label>
          <div class="controls">
             <select class="form-control required" name="marital_status" id="marital_status">
                <option value="">Select gender...</option>
                <option valu="Single">Single</option>
                <option valu="Married">Married</option>
             </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Gender:</label>
          <div class="controls">
             <select class="form-control required" name="gender" id="gender">
                <option value="">Select gender...</option>
                <option valu="Male">Male</option>
                <option valu="Female">Female</option>
             </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Blood Group:</label>
          <div class="controls">
             <select class="form-control required" name="blood_group" id="blood_group">
                <option value="">Select blood group...</option>
                <option value="A +ve">A +ve</option>
                <option value="A -ve">A -ve</option>
                <option value="A Unknown">A Unknown</option>
                <option value="B +ve">B +ve</option>
                <option value="B -ve">B -ve</option>
                <option value="B Unknown">B Unknown</option>
                <option value="AB +ve">AB +ve</option>
                <option value="AB -ve">AB -ve</option>
                <option value="AB Unknown">AB Unknown</option>
                <option value="O +ve">O +ve</option>
                <option value="O -ve">O -ve</option>
                <option value="O Unknown">O Unknown</option>
                <option value="Unknown">Unknown</option>
             </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Name of the Father/Husband:</label>
          <div class="controls">
             <input type="text" class="form-control required" name="name_father_husband" id="name_father_husband" placeholder="Name of Father/Husband" />
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
          <label class="control-label">Permanent Address:</label>
          <div class="controls">
               <textarea name="permanent_address" id="permanent_address" class="form-control required"></textarea>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Mobile Number:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="mobile_number" id="mobile_number" placeholder="Mobile number" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Email:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="email" id="email" placeholder="Email" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Emergency Contact Number:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="emergency_contact_number" id="emergency_contact_number" placeholder="Emergency contact number" />
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
               <input type="text" class="form-control required" name="emp_id" id="emp_id" placeholder="Employee ID" />
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
             <?php 
                foreach($department as $key => $value){
                  echo '<option value="'.$key.'">'.$value.'</option>';
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
                    <tr id="eduitemtr_1">

                      <td><input type="text" name="qualification[]" placeholder="" class="form-control" value=""></td>

                      <td><input type="text" name="institution[]" placeholder="" class="form-control" value=""></td>

                      <td><input type="text" name="year[]" placeholder="" class="form-control" value=""></td>

                      <td><input type="text" name="percentage[]" placeholder="" class="form-control" value=""></td>

                      <td><button onclick="addEducationRow(1);">+</button></td>
                    </tr>
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
                    <tr id="familyitemtr_1">

                      <td><input type="text" name="name[]" placeholder="" class="form-control" value=""></td>

                      <td><input type="text" name="dob[]" placeholder="" class="form-control dob" value=""></td>

                      <td>
                        <select class="form-control" name="gender[]">
                          <option value="">Gender...</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>
                      </td>

                      <td><input type="text" name="relationship[]" placeholder="" class="form-control" value=""></td>

                      <td><button onclick="addFamilyRow(1);">+</button></td>
                    </tr>
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
                    <tr id="employementitemtr_1">

                      <td class="span1"><input type="text" name="employer_name[]" placeholder="" class="form-control" value=""></td>

                      <td class="span1"><input type="text" name="position[]" placeholder="" class="form-control" value=""></td>

                      <td><input type="text" name="from[]"" placeholder="From" class="form-control dob" value=""><br /><input type="text" name="to[]" placeholder="To" class="form-control dob" value=""></td>

                      <td><input type="text" name="leaving_reason[]" placeholder="" class="form-control" value=""></td>

                      <td><input type="text" name="reference[]" placeholder="" class="form-control" value=""></td>

                      <td><button onclick="addEmploymentRow(1);">+</button></td>
                    </tr>
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
        autoclose: true,
        maxDate: 'now'
    });
    $('#doj').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        maxDate: 'now'
    });
    $('.dob').datepicker({
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
});


$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function addEmployeeDetails(){
  makeEducationDetailsJSON();
  makeFamilyDetailsJSON();
  makeEmploymentDetailsJSON();

  if($('#addemployee_form').valid()){ 
    var data = $('#addemployee_form').serialize() + '&academic_details=' + JSON.stringify(g_academicDetails) + '&family_details=' + JSON.stringify(g_familyDetails) + '&employment_details=' + JSON.stringify(g_employmentDetails) + '&action=add_employee';
    $.ajax({
        url: "hr_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            if(result.infocode == "EMPLOYEEADDED"){
                bootbox.alert(result.message);
                $('#addemployee_form')[0].reset();
                g_academicDetails = [];
                g_familyDetails = [];
                g_employmentDetails = [];
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
              <td><button onclick="addEducationRow([addcount]);">+</button><button class="item_removebutton" style="display:none;" onclick="removeEducationRow([removecount])">-</button></td></tr>';


var g_rowcountEducaion=2;
var g_educationRowCount = 1;

var g_academicDetails = [];

//called at addEmployeeDetails method
function makeEducationDetailsJSON(){
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
                .replace('[removecount]',g_rowcountEducaion)
  $('#addEducationItem_tbody').append(addrow);
  g_rowcountEducaion++;
  g_educationRowCount++;
  $('.item_removebutton').show();
}

function removeEducationRow(rowcount){
  $('#eduitemtr_'+rowcount).remove();
  g_educationRowCount--;
  if(g_educationRowCount < 2){
    $('.item_removebutton').hide();
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
              <td><button onclick="addFamilyRow([addcount]);">+</button><button class="item_removebutton" style="display:none;" onclick="removeFamilyRow([removecount])">-</button></td></tr>';


var g_rowcountFamily=2;
var g_familyRowCount = 1;
var g_familyDetails = [];

//called at addEmployeeDetails method
function makeFamilyDetailsJSON(){
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
  g_rowcountFamily++;
  g_familyRowCount++;
  $('.item_removebutton').show();
}

function removeFamilyRow(rowcount){
  $('#familyitemtr_'+rowcount).remove();
  g_familyRowCount--;
  if(g_familyRowCount < 2){
    $('.item_removebutton').hide();
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
              <td><button onclick="addEmploymentRow([addcount]);">+</button><button class="item_removebutton" style="display:none;" onclick="removeEmploymentRow([removecount])">-</button></td></tr>';


var g_rowcountEmployment=2;
var g_employmentRowCount = 1;
var g_employmentDetails = [];

//called at addEmployeeDetails method
function makeEmploymentDetailsJSON(){
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
  g_rowcountEmployment++;
  g_employmentRowCount++;
  $('.item_removebutton').show();
}

function removeEmploymentRow(rowcount){
  $('#employmentitemtr_'+rowcount).remove();
  g_employmentRowCount--;
  if(g_employmentRowCount < 2){
    $('.item_removebutton').hide();
  }
}
// employment detail modal and template ends


</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>
