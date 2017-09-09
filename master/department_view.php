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

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Department View</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="spare_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Department ID</th>
                        <th>Party Name</th>
						<th>Location</th>
						<th>Department</th>
						<th>View</th>
                    </tr>
                </thead>
				<tbody>
                    <?php 
                        $select_query = "SELECT a.department_name, a.department_id,b.customer_name,a.party_master_id,c.location_name, a.party_location_id FROM department a, party_master b, party_location c WHERE b.party_master_id = a.party_master_id AND c.party_location_id = a.party_location_id ORDER BY a.department_id ASC";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
						$datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
							$datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".$row['department_id']."</td>";
                                    echo "<td>".$row['customer_name']."</td>";
									echo "<td>".$row['location_name']."</td>";
									echo "<td>".$row['department_name']."</td>";	
                                    echo "<td><a href='department_edit.php?department_id={$row['department_id']}&party_master_id={$row['party_master_id']}&party_location_id={$row['party_location_id']}'>View</a></td>";
                                echo "</tr>";
                            }
                        }else{
                            echo '<tr><td colspan="5">No Department to return</td></tr>';
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
    <?php if($datatableflag){ ?>
    $('#department_list').DataTable();
    <?php } ?>
    $('#li_mastermenu').addClass('active open');
});

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>