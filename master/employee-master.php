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
            <h5>Add Employee</h5>
        </div>
        <div class="widget-content ">
            <form  id="addemployee_form" name="addemployee_form" method="post" class="validator-form1" action="" onsubmit="return false;"> 
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row margin_bottom_40">
                            <div class="span5">
                                <label class="control-label">Employee name</label>
                                <input type="text" class="form-control required" name="employee_name" id="employee_name" placeholder="Employee Name" />
                            </div>
                            <div class="span5">
                                <label class="control-label">Employee ID</label>
                                <input type="text" class="form-control required" name="employee_id" id="employee_id" placeholder="Employee ID" />
                            </div>
                        </div><div class="clearfix"></div>
                        <div class="row margin_bottom_40">
                            <div class="span5">
                                <label class="control-label">Login ID</label>
                                <input type="text" class="form-control required" name="loginid" id="loginid" placeholder="Username for login" />
                            </div>
                            <div class="span5">
                                <label class="control-label">Password</label>
                                <input type="password" class="form-control required" name="password" id="password" placeholder="Password" />
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
                                    while($row = mysqli_fetch_assoc($result)){
                                        echo '<option value="'.$row['role_name'].'">'.$row['role_name'].'</option>';
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
                        <input type="hidden" name="action" id="action" value="add_employee"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span4 text-right">
                        <button type="button" class="btn btn-primary" onclick="add_employee_details();">Add Employee</button>
                    </div>
                    <div class="span4">
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
    $('#addemployee_form').validate();
    $('#sb_ul_mastermenu').addClass('in');
    $('#li_sc_ticketmenu').addClass('active open');
});

function add_employee_details(){
    $("#editresulterrmsg").html('');
    if($('#addemployee_form').valid()){
        var data = $('#addemployee_form').serialize();
        $.ajax({
            url: "employeeservices.php",
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