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
          <h5>Spares Request</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="spare_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>S.No</th>
						<th>Ticket ID</th>
                        <th>Product</th>
						<th>Spare</th>
						<th>Remark</th>
                        <th>Quantity</th>
						<th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $select_query = "SELECT * FROM spares";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".++$row_counter."</td>";
									
									echo "<td>".$row['ticket_id']."</td>";
                                    echo "<td>".$row['product']."</td>";
									echo "<td>".$row['spare_name']."</td>";	
									echo "<td>".$row['spare_remark']."</td>";
									echo "<td>".$row['quantity']."</td>";
									echo "<td><a href='spare_edit.php?spare_id={$row['spare_id']}'>View</a></td>";
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
    $('#spare_list').DataTable();
    $('#li_sc_ticketmenu').addClass('active open');
});

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>