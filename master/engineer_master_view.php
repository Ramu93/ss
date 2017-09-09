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
 
?>

<style type="text/css">
.error{
    color:red;
}
</style>
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>index1.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Engineer List</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="engineer_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>S.No</th>
						<th>Service Coordinator Name</th>
						<th>Engineer Name</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $select_query = "SELECT a.engineer_name, a.engineer_master_id, b.service_coordinator_name, b.service_coordinator_id FROM master_engineer a, service_coordinator b WHERE a.service_coordinator_id = b.service_coordinator_id ORDER BY b.service_coordinator_id ASC";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".++$row_counter."</td>";
									
									echo "<td>".$row['service_coordinator_name']."</td>";
									echo "<td>".$row['engineer_name']."</td>";
									echo "<td><a href='engineer_edit.php?engineer_master_id={$row['engineer_master_id']}&service_coordinator_id={$row['service_coordinator_id']}'>View</a></td>";                                
									
								echo "</tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function() {
    $('#engineer_list').DataTable();
    $('#sb_ul_mastermenu').addClass('in');
});
</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>