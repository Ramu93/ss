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

	<div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Complaint View</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="complaint_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>S.No</th>
						<th>Complaint Name</th>
						<th>Complaint Code</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $select_query = "SELECT * FROM nature_of_comp";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".++$row_counter."</td>";
									
									echo "<td>".$row['complaint_name']."</td>";
									echo "<td>".$row['complaint_code']."</td>";
									echo "<td><a href='complaint_edit.php?NCMPLTID={$row['NCMPLTID']}'>View</a></td>";
									                                
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
    $('#complaint_list').DataTable();
    $('#sb_ul_mastermenu').addClass('in');
    $('#li_sc_ticketmenu').addClass('active open');
});
</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>