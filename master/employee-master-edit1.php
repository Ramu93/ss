<?php session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}
include('../header.php');
include('../topbar.php');
include('../sidebar.php');
include('../dbconfig.php');
include('roleconfig.php');
$em_id = isset($_GET['em_id'])?$_GET['em_id']:'';
$query = "SELECT * FROM employee_master WHERE employee_master_id = $em_id";
$result = mysqli_query($dbc,$query);
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);
}else{
    header('Location : employee-master-view.php');
}
?>
<style type="text/css">
.error{
    color:red;
}
</style>
<div class="row">
    <div class="col-md-12">
      <center><h3>EMPLOYEE MASTER - Edit</h3></center>
    </div>
	<div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                 Employee Master - Edit
            </div>
            <div class="panel-body">
        		<form  id="editemployee_form" name="editemployee_form" method="post" class="validator-form1" action="" onsubmit="return false;"> 
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Edit Employee</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row margin_bottom_40">
                            <div class="col-lg-6">
                                <label class="control-label">Employee name</label>
                                <input type="text" class="form-control required" name="employee_name" id="employee_name" placeholder="Employee Name" value="<?php echo $row['employee_name']; ?>" />
                            </div>
                            <div class="col-lg-6">
                                <label class="control-label">Employee ID</label>
                                <input type="text" class="form-control required" name="employee_id" id="employee_id" placeholder="Employee ID" value="<?php echo $row['employee_id']; ?>"/>
                            </div>
                        </div><div class="clearfix"></div>
                        <div class="row margin_bottom_40">
                            <div class="col-lg-6">
                                <label class="control-label">Login ID</label>
                                <input type="text" class="form-control required" name="loginid" id="loginid" placeholder="Username for login" value="<?php echo $row['loginid']; ?>"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="control-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
                            </div>
                        </div><div class="clearfix"></div>
                        <div class="row margin_bottom_40">
                            <div class="col-lg-6">
                                <label class="control-label">Employee Role</label>
                                <?php $dataflag = false;
                                $query = "SELECT * FROM role_master";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '<select class="form-control required" name="role_name" id="role_name">';
                                    while($row2 = mysqli_fetch_assoc($result)){
                                        $selected = ($row['role_master_id'] == $row2['role_master_id'])?'selected="selected"':'';
                                        echo '<option value="'.$row2['role_master_id'].'" '.$selected.'>'.$row2['role_name'].'</option>';
                                    }
                                    echo '</select>';
                                    $dataflag = true;
                                }else{
                                    echo 'No roles added. <a href="role-master.php">Add Role Now</a>';
                                }
                                ?>
                            </div>
                            <div class="col-lg-6">
                            </div>
                        </div><div class="clearfix"></div>
                        <input type="hidden" name="action" id="action" value="edit_employee"/>
                        <input type="hidden" name="em_id" id="em_id" value="<?php echo $em_id; ?>"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-primary" onclick="edit_employee_details();">Update Employee</button>
                    </div>
                    <div class="col-lg-6 text-left">
                        <button type="button" class="btn btn-warning" onclick="window.location='employee-master-view.php';">View Employees</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../footer_jsimports.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#addemployee_form').validate();
});

function edit_employee_details(){
    $("#editresulterrmsg").html('');
    if($('#editemployee_form').valid()){
        var data = $('#editemployee_form').serialize();
        $.ajax({
            url: "employeeservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "EMPLOYEEUPDATED"){
                    bootbox.alert(result.message,function(){
                        window.location = 'employee-master-view.php';
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
<?php include('../footer.php'); ?>