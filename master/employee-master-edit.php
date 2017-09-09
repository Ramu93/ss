<?php session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}
include('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
include('..'.DIRECTORY_SEPARATOR.'header.php');
include('..'.DIRECTORY_SEPARATOR.'topbar.php');
include('..'.DIRECTORY_SEPARATOR.'sidebar.php');
//include('roleconfig.php');
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

<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>index1.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

    <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Edit Employee</h5>
        </div>
        <div class="widget-content ">
            <form  id="editemployee_form" name="editemployee_form" method="post" class="validator-form1" action="" onsubmit="return false;"> 
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row margin_bottom_40">
                            <div class="span5">
                                <label class="control-label">Employee name</label>
                                <input type="text" class="form-control required" name="employee_name" id="employee_name" placeholder="Employee Name" value="<?php echo $row['employee_name']; ?>" />
                            </div>
                            <div class="span5">
                                <label class="control-label">Employee ID</label>
                                <input type="text" class="form-control required" name="employee_id" id="employee_id" placeholder="Employee ID" value="<?php echo $row['employee_id']; ?>"/>
                            </div>
                        </div><div class="clearfix"></div>
                        <div class="row margin_bottom_40">
                            <div class="span5">
                                <label class="control-label">Login ID</label>
                                <input type="text" class="form-control required" name="loginid" id="loginid" placeholder="Username for login" value="<?php echo $row['loginid']; ?>"/>
                            </div>
                            <div class="span5">
                                <label class="control-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
                                <span>Leave blank to use the same password</span>
                            </div>
                        </div><div class="clearfix"></div>
                        <div class="row margin_bottom_40">
                            <div class="span5">
                                <label class="control-label">Employee Role</label>
                                <?php $dataflag = false;
                                $query = "SELECT * FROM role_master WHERE role_name != 'admin'";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '<select class="form-control required" name="role_name" id="role_name">';
                                    while($row2 = mysqli_fetch_assoc($result)){
                                        $selected = ($row['rolename'] == $row2['role_name'])?'selected="selected"':'';
                                        echo '<option value="'.$row2['role_name'].'" '.$selected.'>'.$row2['role_name'].'</option>';
                                    }
                                    echo '</select>';
                                    $dataflag = true;
                                }else{
                                    echo 'No roles added. <a href="role-master.php">Add Role Now</a>';
                                }
                                ?>
                            </div>
                            <div class="span5">
                            </div>
                        </div><div class="clearfix"></div>
                        <input type="hidden" name="action" id="action" value="edit_employee"/>
                        <input type="hidden" name="em_id" id="em_id" value="<?php echo $em_id; ?>"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="span5">
                        <button type="button" class="btn btn-primary" onclick="edit_employee_details();">Update Employee</button>
                    </div>
                    <div class="span5 text-left">
                        <button type="button" class="btn btn-warning" onclick="window.location='employee-master-view.php';">View Employees</button>
                    </div>
                </div>
                </form>
        </div>
    </div>
</div>


<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#editemployee_form').validate();
    $('#sb_ul_mastermenu').addClass('in');
    $('#li_sc_ticketmenu').addClass('active open');
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
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>