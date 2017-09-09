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
          <h5>Employee List</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="asset_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>S. No.</th>
            <th>Employee ID</th>
  					<th>Employee Name</th>
  					<th>Office</th>
            <th>Location</th>
            <th>Designation</th>
  					<th>Department</th>
            <th>Communication Address</th>
            <th>Action</th>
          </tr>
        </thead>
				<tbody>
          <?php
              $select_query = "SELECT * FROM hr_employee_master";
              $result = mysqli_query($dbc,$select_query);
              $row_counter = 0;
	            $datatableflag = false;
              if(mysqli_num_rows($result) > 0) {
		              $datatableflag = true;
                  $count = 1;
                  while($row = mysqli_fetch_array($result)) {
                      echo "<tr>";
                          echo "<td>".$count++."</td>";
                          echo "<td>".$row['emp_id']."</td>";
                          echo "<td>".$row['employee_name']."</td>";
				                  echo "<td>".$row['office']."</td>";
                          echo "<td>".$row['location']."</td>";
                          echo "<td>".$row['designation']."</td>";
                          echo "<td>".$row['department']."</td>";
                          echo "<td>".$row['communication_address']."</td>";
                          echo "<td><a href='employee_edit.php?emp_id={$row['emp_id']}'>Edit</a></td>";
                      echo "</tr>";
                  }
              }else{
                  echo '<tr><td colspan="6">No employees added</td></tr>';
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
    $('#asset_list').DataTable();
    <?php } ?>
    $('#li_sc_engineermenu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');
});

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>
