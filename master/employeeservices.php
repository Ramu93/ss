<?php 

//require('../dbconfig.php');
include('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
//include('roleconfig.php');
$finaloutput = array();
if(!$_POST) {
	$action = $_GET['action'];
}
else {
	$action = $_POST['action'];
}
switch($action){
	case 'add_role':
        $finaloutput = add_role();
    break;
    case 'edit_role':
        $finaloutput = edit_role();
    break;
    case 'add_employee':
        $finaloutput = add_employee();
    break;
    case 'edit_employee':
        $finaloutput = edit_employee();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function add_role(){
	global $dbc,$role_list,$rolenames; $role_permissions = array();
	$role_name = mysqli_real_escape_string($dbc,trim($_POST['role_name']));
	$query = "SELECT * FROM role_master WHERE role_name = '$role_name'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "ROLENAMEEXISTS", "message" => "This role name already exists, please choose a different one");
	}else{
		foreach($role_list as $k => $v){
            foreach ($rolenames as $k2 => $v2) {
            	$elename = $k.'__'.$v2;
            	if(isset($_POST[$elename])){
            		$role_permissions[$k][$v2] = 'yes';	
            	}
            }
        }
        $role_permissions = json_encode($role_permissions);
    	$query2 = "INSERT INTO role_master (role_name,role_permissions) VALUES ('$role_name','$role_permissions')";  
    	if(mysqli_query($dbc,$query2)){
			$output = array("infocode" => "ROLEADDED", "message" => "Role added succesfully");
		}
		else {
			$output = array("infocode" => "ADDROLEAILED", "message" => "Something went wrong");
		}
	}

	return $output;
}

function edit_role(){
	global $dbc,$role_list,$rolenames; $role_permissions = array();
	$role_name = mysqli_real_escape_string($dbc,trim($_POST['role_name']));
	foreach($role_list as $k => $v){
        foreach ($rolenames as $k2 => $v2) {
        	$elename = $k.'__'.$v2;
        	if(isset($_POST[$elename])){
        		$role_permissions[$k][$v2] = 'yes';	
        	}
        }
    }
    $role_permissions = json_encode($role_permissions);
	$query2 = "UPDATE role_master SET role_permissions = '$role_permissions' WHERE role_name = '$role_name'";
	if(mysqli_query($dbc,$query2)){
		$output = array("infocode" => "ROLEUPDATED", "message" => "Role details updated succesfully");
	} else {
		$output = array("infocode" => "UPDATEROLEAILED", "message" => "Something went wrong");
	}
	return $output;
}

function add_employee(){
	global $dbc,$role_list,$rolenames; $role_permissions = array();
	$employee_id = mysqli_real_escape_string($dbc,trim($_POST['employee_id']));
	$loginid = mysqli_real_escape_string($dbc,trim($_POST['loginid']));
	$query = "SELECT * FROM employee_master WHERE employee_id = '$employee_id' OR loginid = '$loginid'";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "EMPLOYEEEXISTS", "message" => "This employee ID / Login ID already exists, please choose a different one");
	}else{
		$employee_name = mysqli_real_escape_string($dbc,trim($_POST['employee_name']));
		$password = md5(mysqli_real_escape_string($dbc,trim($_POST['password'])));
		$role_name = mysqli_real_escape_string($dbc,trim($_POST['role_name']));
    	$query2 = "INSERT INTO employee_master (employee_name, employee_id,loginid,password,rolename) VALUES ('$employee_name','$employee_id','$loginid','$password','$role_name')";  
    	if(mysqli_query($dbc,$query2)){
			$output = array("infocode" => "EMPLOYEEADDED", "message" => "Employee added succesfully");
		}
		else {
			$output = array("infocode" => "ADDEMPLOYEEFAILED", "message" => "Something went wrong");
		}
	}

	return $output;
}

function edit_employee(){
	global $dbc,$role_list,$rolenames; $role_permissions = array();
	$em_id = mysqli_real_escape_string($dbc,trim($_POST['em_id']));
	$employee_id = mysqli_real_escape_string($dbc,trim($_POST['employee_id']));
	$loginid = mysqli_real_escape_string($dbc,trim($_POST['loginid']));
	$query = "SELECT * FROM employee_master WHERE employee_master_id != $em_id AND (employee_id = '$employee_id' OR loginid = '$loginid')";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$output = array("infocode" => "EMPLOYEEEXISTS", "message" => "The modified employee ID / Login ID already exists, please choose a different one");
	}else{
		$employee_name = mysqli_real_escape_string($dbc,trim($_POST['employee_name']));
		$password = (trim($_POST['password'])!='')?md5(mysqli_real_escape_string($dbc,trim($_POST['password']))):'';
		$role_name = mysqli_real_escape_string($dbc,trim($_POST['role_name']));
		$pwdfield = ($password != '')?" , password='$password'":"";
    	$query2 = "UPDATE employee_master SET employee_name='$employee_name', employee_id='$employee_id', loginid='$loginid',rolename='$role_name' $pwdfield WHERE employee_master_id = $em_id";  
    	if(mysqli_query($dbc,$query2)){
			$output = array("infocode" => "EMPLOYEEUPDATED", "message" => "Employee details updated succesfully");
		} else {
			$output = array("infocode" => "UPDATEEMPLOYEEFAILED", "message" => "Something went wrong");
		}
	}

	return $output;
}
