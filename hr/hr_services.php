<?php
  require('..'.DIRECTORY_SEPARATOR.'dbconfig.php');

  $finaloutput = array();
  if(!$_POST) {
    $action = $_GET['action'];
  }
  else {
    $action = $_POST['action'];
  }
  switch($action){
    case 'add_employee':
          $finaloutput = addEmployee();
    break;
    case 'update_employee':
          $finaloutput = updateEmployee();
    break;
    case 'get_employee_list':
        $finaloutput = getEmployeeList();
    break;
    case 'save_attendance':
        $finaloutput = saveAttendance();
    break;
    case 'view_attendance':
        $finaloutput = viewAttendance();
    break;
    case 'get_attendance_detail':
        $finaloutput = getAttendanceDetail();
    break;
    case 'update_attendance_detail':
        $finaloutput = updateAttendance();
    break;
    case 'get_employee':
        $finaloutput = getEmployee();
    break;
    case 'save_salary':
        $finaloutput = saveSalary();
    break;
    case 'get_salary_list':
        $finaloutput = getSalaryList();
    break;
    case 'get_emp_salary_detail':
        $finaloutput = getEmployeeSalaryDetail();
    break;
    case 'add_advance':
        $finaloutput = addAdvance();
    break;
    default:
          $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
  }

  echo json_encode($finaloutput);

  function addEmployee(){
    global $dbc;
    $employeeName = mysqli_real_escape_string($dbc,trim($_POST['employee_name']));
    $dob = date('Y-m-d', strtotime(mysqli_real_escape_string($dbc,trim($_POST['dob']))));
    $communicationAddress = mysqli_real_escape_string($dbc, trim($_POST['communication_address']));
    $panDetails = mysqli_real_escape_string($dbc,trim($_POST['pan_details']));
    $aadharDetails = mysqli_real_escape_string($dbc,trim($_POST['aadhar_details']));
    $empID = mysqli_real_escape_string($dbc,trim($_POST['emp_id']));
    $office = mysqli_real_escape_string($dbc,trim($_POST['office']));
    $location = mysqli_real_escape_string($dbc,trim($_POST['location']));
    $designation = mysqli_real_escape_string($dbc,trim($_POST['designation']));
    $paymentMode = mysqli_real_escape_string($dbc,trim($_POST['payment_mode']));
    $ctcMonthly = mysqli_real_escape_string($dbc,trim($_POST['ctc_monthly']));
    $doj = date('Y-m-d', strtotime(mysqli_real_escape_string($dbc,trim($_POST['doj']))));
    $qualification = mysqli_real_escape_string($dbc,trim($_POST['qualification']));
    $experience = mysqli_real_escape_string($dbc,trim($_POST['experience']));
    $department = mysqli_real_escape_string($dbc, trim($_POST['department']));
    $bankAccNumber = mysqli_real_escape_string($dbc,trim($_POST['bank_acc_number']));
    $bankName = mysqli_real_escape_string($dbc,trim($_POST['bank_name']));
    $bankIfsc = mysqli_real_escape_string($dbc,trim($_POST['ifsc']));

    $addEmployeeQuery = "INSERT INTO hr_employee_master (employee_name, dob, communication_address, pan_details, aadhar_details, emp_id, office, location, designation, payment_mode, bank_acc_num, bank_name, bank_ifsc, ctc_monthly, doj, qualification, experience, department) VALUES ('$employeeName','$dob', '$communicationAddress', '$panDetails', '$aadharDetails', '$empID', '$office', '$location', '$designation', '$paymentMode', '$bankAccNumber', '$bankName', '$bankIfsc', '$ctcMonthly', '$doj', '$qualification', '$experience', '$department')";

  	if(mysqli_query($dbc, $addEmployeeQuery)){
  		$output = array("infocode" => "EMPLOYEEADDED", "message" => "Employee added succesfully.");
  	}
  	else {
  		$output = array("infocode" => "ADDEMPLOYEEFAILED", "message" => "Unable to add Employee, please try again!");
  	}
  	return $output;
  }

  function updateEmployee(){
    global $dbc;
    $employeeName = mysqli_real_escape_string($dbc,trim($_POST['employee_name']));
    $dob = date('Y-m-d', strtotime(mysqli_real_escape_string($dbc,trim($_POST['dob']))));
    $communicationAddress = mysqli_real_escape_string($dbc, trim($_POST['communication_address']));
    $panDetails = mysqli_real_escape_string($dbc,trim($_POST['pan_details']));
    $aadharDetails = mysqli_real_escape_string($dbc,trim($_POST['aadhar_details']));
    $empID = mysqli_real_escape_string($dbc,trim($_POST['emp_id']));
    $office = mysqli_real_escape_string($dbc,trim($_POST['office']));
    $location = mysqli_real_escape_string($dbc,trim($_POST['location']));
    $designation = mysqli_real_escape_string($dbc,trim($_POST['designation']));
    $paymentMode = mysqli_real_escape_string($dbc,trim($_POST['payment_mode']));
    $ctcMonthly = mysqli_real_escape_string($dbc,trim($_POST['ctc_monthly']));
    $doj = date('Y-m-d', strtotime(mysqli_real_escape_string($dbc,trim($_POST['doj']))));
    $qualification = mysqli_real_escape_string($dbc,trim($_POST['qualification']));
    $experience = mysqli_real_escape_string($dbc,trim($_POST['experience']));
    $department = mysqli_real_escape_string($dbc, trim($_POST['department']));
    $bankAccNumber = mysqli_real_escape_string($dbc,trim($_POST['bank_acc_number']));
    $bankName = mysqli_real_escape_string($dbc,trim($_POST['bank_name']));
    $bankIfsc = mysqli_real_escape_string($dbc,trim($_POST['ifsc']));

    $updateEmpoyeeQuery = "UPDATE hr_employee_master SET employee_name='$employeeName', dob='$dob', communication_address='$communicationAddress', pan_details='$panDetails', aadhar_details='$aadharDetails', office='$office', location='$location', designation='$designation', payment_mode='$paymentMode', ctc_monthly='$ctcMonthly', doj='$doj', qualification='$qualification', experience='$experience', department='$department', bank_acc_num='$bankAccNumber', bank_name='$bankName', bank_ifsc='$bankIfsc' WHERE emp_id='$empID'";

    if(mysqli_query($dbc, $updateEmpoyeeQuery)){
      $output = array("infocode" => "EMPLOYEEUPDATED", "message" => "Employee updated succesfully.");
    }
    else {
      $output = array("infocode" => "EMPLOYEEUPDATEFAILURE", "message" => "Unable to update Employee, please try again!");
    }
    return $output;
  }

  function getEmployeeList(){
    global $dbc;
    $department = mysqli_real_escape_string($dbc, trim($_POST['department']));
    $location = mysqli_real_escape_string($dbc,trim($_POST['location']));
    $office = mysqli_real_escape_string($dbc,trim($_POST['office']));

    $getEmployeeListQuery = "SELECT * FROM hr_employee_master WHERE department='$department' AND location='$location' AND office='$office'";
    $result = mysqli_query($dbc, $getEmployeeListQuery);
    if(mysqli_num_rows($result) > 0){
      $out = array();
      while($row = mysqli_fetch_assoc($result)){
        $out[] = $row;
      }
      $output = array("infocode" => "GETEMPLOYEELISTSUCCESS", "data" => json_encode($out));
    } else {
      $output = array("infocode" => "GETEMPLOYEELISTFAILURE", "message" => "No employee list availabe for selected data, please try again!");
    }
    // file_put_contents("hr.log", "\n".print_r($output, true), FILE_APPEND | LOCK_EX);
    return $output;
  }

  function saveAttendance(){
    global $dbc;
    $employeeList = json_decode($_POST['employee_list']);
    $attendanceDate = strtotime(mysqli_real_escape_string($dbc,trim($_POST['date'])));
    $attendanceDate = date('Y-m-d', $attendanceDate);
    $department = mysqli_real_escape_string($dbc,trim($_POST['department']));
    $office = mysqli_real_escape_string($dbc,trim($_POST['office']));
    $location = mysqli_real_escape_string($dbc,trim($_POST['location']));
    $presentAbsent = $_POST['present_absent'];
    $entryTime = $_POST['entry_time'];
    $exitTime = $_POST['exit_time'];
    $lateBy = $_POST['late_by'];
    $reason = $_POST['reason'];
    $informedUninformed = $_POST['informed_uninformed_val'];

    for($index = 0; $index < count($employeeList); $index++){
      if($employeeList[$index]->isEmployeeSelected == 'true'){
        $empID = $employeeList[$index]->emp_id;
        $empAttendanceQuery = "INSERT INTO hr_attendance (emp_id, attendance_date, department, office, location, present_absent, entry_time, exit_time, late_by, reason, informed_uninformed) VALUES ('$empID', '$attendanceDate', '$department', '$office', '$location', '$presentAbsent[$index]', '$entryTime[$index]', '$exitTime[$index]', '$lateBy[$index]','$reason[$index]', '$informedUninformed[$index]')";  
        mysqli_query($dbc, $empAttendanceQuery);
      }
    }
    // file_put_contents("hr.log", "\n".print_r($employeeList, true), FILE_APPEND | LOCK_EX);
    $output = array("infocode" => "ATTENDANCEUPDATESUCCESS", "message" => "Attendance updated successfuly.");

    return $output;
  }

  function viewAttendance(){
    global $dbc;
    $attendanceDate = strtotime(mysqli_real_escape_string($dbc,trim($_POST['date'])));
    $attendanceDate = date('Y-m-d', $attendanceDate);
    $department = mysqli_real_escape_string($dbc,trim($_POST['department']));
    $office = mysqli_real_escape_string($dbc,trim($_POST['office']));
    $location = mysqli_real_escape_string($dbc,trim($_POST['location']));

    $getAttendanceDataQuery = "SELECT ha.hr_attendance_id ,ha.emp_id, ha.present_absent, hem.employee_name, hem.designation FROM hr_attendance ha, hr_employee_master hem WHERE ha.emp_id=hem.emp_id AND ha.attendance_date='$attendanceDate' AND ha.department='$department' AND ha.office='$office' AND ha.location='$location'";

    $result = mysqli_query($dbc, $getAttendanceDataQuery);
    if(mysqli_num_rows($result) > 0){
      $out = array();
      while($row = mysqli_fetch_assoc($result)){
        $out[] = $row;
      }
      $output = array("infocode" => "GETATTENDANCEDATASUCCESS", "data" => json_encode($out));
      // file_put_contents("hr.log", "\n".print_r($out, true), FILE_APPEND | LOCK_EX);

    } else {
      $output = array("infocode" => "GETATTENDANCEDATAFAILURE", "message" => "No attendance data availabe for selected criteria, please try again!");
    }
    return $output;
  }

  function getAttendanceDetail(){
    global $dbc;
    $attendanceID = mysqli_real_escape_string($dbc,trim($_POST['attendance_id']));

    $getAttendanceDetailQuery = "SELECT * FROM hr_attendance WHERE hr_attendance_id='$attendanceID'";
    $result = mysqli_query($dbc, $getAttendanceDetailQuery);
    if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_assoc($result);
      $output = array("infocode" => "GETATTENDANCEDETAILSUCCESS", "data" => json_encode($row));
    } else {
      $output = array("infocode" => "GETATTENDANCEDETAILFAILURE", "message" => "Unable to fetch attendance detail, please try again!");
    }
    return $output;
  }

  function updateAttendance(){
    global $dbc;
    $attendanceID = mysqli_real_escape_string($dbc,trim($_POST['attendance_id']));
    $presentAbsent = mysqli_real_escape_string($dbc,trim($_POST['present_absent']));
    $entryTime = mysqli_real_escape_string($dbc,trim($_POST['entry_time']));
    $exitTime = mysqli_real_escape_string($dbc,trim($_POST['exit_time']));
    $lateBy = mysqli_real_escape_string($dbc,trim($_POST['late_by']));
    $reason = mysqli_real_escape_string($dbc,trim($_POST['reason']));
    $informedUninformed = mysqli_real_escape_string($dbc,trim($_POST['informed_uninformed_val']));

    $updateAttendanceDetailQuery = "UPDATE hr_attendance SET present_absent='$presentAbsent', entry_time='$entryTime', exit_time='$exitTime', late_by='$lateBy', reason='$reason', informed_uninformed='$informedUninformed' WHERE hr_attendance_id='$attendanceID'";
    mysqli_query($dbc, $updateAttendanceDetailQuery);
    $output = array("infocode" => "UPDATEATTENDANCEDETAILSUCCESS", "message" => "Attendance updated successfuly.");
    return $output;
  }

  function getEmployee(){
    global $dbc;
    $empId = mysqli_real_escape_string($dbc,trim($_POST['emp_id']));
    $getEmployeeQuery = "SELECT * FROM hr_employee_master WHERE emp_id='$empId'";
    $result = mysqli_query($dbc, $getEmployeeQuery);
    if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_assoc($result);
      $output = array("infocode" => "GETEMPLOYEESUCCESS", "data" => json_encode($row));
    }
    return $output;
  }

  function saveSalary(){
    global $dbc;
    $empId = mysqli_real_escape_string($dbc,trim($_POST['emp_id'])); 
    $paymentMode = mysqli_real_escape_string($dbc,trim($_POST['payment_mode']));
    $bankAccNumber = mysqli_real_escape_string($dbc,trim($_POST['bank_acc_number']));
    $bankName = mysqli_real_escape_string($dbc,trim($_POST['bank_name']));
    $bankIfsc = mysqli_real_escape_string($dbc,trim($_POST['ifsc']));
    $salaryInfo = json_decode($_POST['salary_info']);

    $basic_monthly = $salaryInfo->basic_monthly;
    $hra = $salaryInfo->hra;
    $special_allowance = $salaryInfo->special_allowance;
    $vm = $salaryInfo->vm;
    $advance = $salaryInfo->advance;
    $pf = $salaryInfo->pf;
    $esi = $salaryInfo->esi;
    $professional_tax = $salaryInfo->professional_tax;
    $od = $salaryInfo->od;
    $tds = $salaryInfo->tds;
    $employer_pf = $salaryInfo->employer_pf;
    $employer_esi = $salaryInfo->employer_esi;
    $working_days = $salaryInfo->working_days;
    $lop = $salaryInfo->lop;
    $lop_amount = $salaryInfo->lop_amount;
    $earnings_per_day = $salaryInfo->earnings_per_day;
    $total_earnings = $salaryInfo->total_earnings;
    $total_deductions = $salaryInfo->total_deductions;
    $net_pay = $salaryInfo->net_pay;
    $salary_date = date("Y-m-d");

    $saveSalaryQuery = "INSERT INTO hr_salary_process (emp_id, payment_mode, bank_acc_num, bank_name, bank_ifsc, salary_date, basic_monthly, hra, special_allowance, vm, advance, pf, esi, professional_tax, od, tds, employer_pf, employer_esi, working_days, lop, lop_amount, earnings_per_day, total_earnings, total_deductions, net_pay) VALUES ('$empId', '$paymentMode', '$bankAccNumber', '$bankName', '$bankIfsc', '$salary_date', '$basic_monthly', '$hra', '$special_allowance', '$vm', '$advance', '$pf', '$esi', '$professional_tax', '$od', '$tds', '$employer_pf', '$employer_esi', '$working_days', '$lop', '$lop_amount', '$earnings_per_day', '$total_earnings', '$total_deductions', '$net_pay')";
    mysqli_query($dbc, $saveSalaryQuery);
    $output = array("infocode" => "SAVESALARYSUCCESS", "message" => "Salary information saved successfuly.");
    return $output;
  }

  function getSalaryList(){
    global $dbc;
    $department = mysqli_real_escape_string($dbc,trim($_POST['department']));
    $office = mysqli_real_escape_string($dbc,trim($_POST['office']));
    $location = mysqli_real_escape_string($dbc,trim($_POST['location']));
    $getSalaryListQuery = "SELECT hem.emp_id, hem.employee_name, hsp.net_pay, hsp.salary_date, hsp.hr_salary_process_id FROM hr_salary_process hsp, hr_employee_master hem WHERE hsp.emp_id=hem.emp_id AND hem.department='$department' AND hem.office='$office' AND hem.location='$location'";
    $result = mysqli_query($dbc, $getSalaryListQuery);
    if(mysqli_num_rows($result) > 0){
      $out = array();
      while($row = mysqli_fetch_assoc($result)){
        $out[] = $row;
      }
      $output = array("infocode" => "GETSALARYLISTSUCCESS", "data" => json_encode($out));
    } else {
      $output = array("infocode" => "GETSALARYLISTFAILURE", "message" => "No salary details found, please try again!");
    }
    return $output;
  }

  function getEmployeeSalaryDetail(){
    global $dbc;
    $salaryProcessID = mysqli_real_escape_string($dbc,trim($_POST['salary_process_id']));
    $query = "SELECT * FROM hr_salary_process WHERE hr_salary_process_id='$salaryProcessID'";
    $result = mysqli_query($dbc, $query);
    if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_assoc($result);
      $output = array("infocode" => "GETEMPLOYEESALARYSUCCESS", "data" => json_encode($row));
    } else {
      $output = array("infocode" => "GETEMPLOYEESALARYFAILURE", "message" => "No salary details found, please try again!");
    }
    return $output;
  }

  function addAdvance(){
    global $dbc;
    $advanceDate = date('Y-m-d', strtotime(mysqli_real_escape_string($dbc,trim($_POST['advance_date']))));
  }

?>
