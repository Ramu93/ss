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
            <h5>List Employee</h5>
        </div>
        <div class="widget-content ">
            <div class="responsive">
            <table id="vogp_inward_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Employee Name</th>
                        <th>Employee ID</th>
                        <th>Login ID</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $select_query = "SELECT * FROM employee_master WHERE rolename != 'admin'";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".++$row_counter."</td>";
                                    echo "<td>".$row['employee_name']."</td>";
                                    echo "<td>".$row['employee_id']."</td>";
                                    echo "<td>".$row['loginid']."</td>";
                                    echo "<td><a href='employee-master-edit.php?em_id={$row['employee_master_id']}'>View</a></td>";
                                echo "</tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>

        </div>
        </div>
    </div>
</div>


<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#addemployee_form').validate();
    $('#sb_ul_mastermenu').addClass('in');
    $('#li_sc_ticketmenu').addClass('active open');
    $('#vogp_inward_list').DataTable();
});


</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>