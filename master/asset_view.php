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
          <h5>Asset List</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="asset_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Asset ID</th>
  					<th>Product Name</th>
  					<th>Customer Name</th>
  					<th>Location</th>
  					<th>Department</th>
  					<th>View</th>
          </tr>
        </thead>
				<tbody>
          <?php
              $select_query = "SELECT a.rfid_tag, a.asset_master_id, b.item_name, a.item_master_id, c.customer_name, a.party_master_id, d.location_name, a.party_location_id,e.department_name,a.department_id FROM asset_master a, item_master b, party_master c, party_location d,department e WHERE c.party_master_id = a.party_master_id AND d.party_location_id = a.party_location_id AND b.item_master_id = a.item_master_id AND e.department_id = a.department_id ORDER BY a.asset_master_id ASC";
              $result = mysqli_query($dbc,$select_query);
              $row_counter = 0;
	            $datatableflag = false;
              if(mysqli_num_rows($result) > 0) {
		              $datatableflag = true;
                  while($row = mysqli_fetch_array($result)) {
                      echo "<tr>";
                          echo "<td>".$row['asset_master_id']."</td>";
                          echo "<td>".$row['item_name']."</td>";
				                  echo "<td>".$row['customer_name']."</td>";
				                  echo "<td>".$row['location_name']."</td>";
				                  echo "<td>".$row['department_name']."</td>";
                          echo "<td><a href='asset_edit.php?asset_master_id={$row['asset_master_id']}&party_master_id={$row['party_master_id']}&party_location_id={$row['party_location_id']}&department_id={$row['department_id']}'>View</a></td>";
                      echo "</tr>";
                  }
              }else{
                  echo '<tr><td colspan="6">No Assets added</td></tr>';
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
