<?php session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}
include('../header.php');
include('../topbar.php');
include('../sidebar.php');
include('../dbconfig.php');
?>
<div class="row">
    <div class="col-md-12">
      <center><h3>Employee Master - List</h3></center>
    <div class="panel panel-default">
        <div class="panel-heading">
             Employee Master - View
        </div>
        <div class="panel-body">
	<div class="col-lg-12 col-md-12 col-sm-12">
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
                        $select_query = "SELECT * FROM employee_master";
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
<?php include('../footer_jsimports.php'); ?>
<script type="text/javascript">
    
$(document).ready(function() {
    $('#vogp_inward_list').DataTable();
});
</script>
<?php include('../footer.php'); ?>