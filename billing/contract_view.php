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
          <h5>Contract List</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="asset_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>S. No.</th>
            <th>Contract ID</th>
  					<th>Customer Name</th>
  					<th>Billing Cycle</th>
            <th>Billing Type</th>
  					<th>View</th>
          </tr>
        </thead>
				<tbody>
          <?php
              $select_query = "SELECT cm.contract_master_id, cm.bill_type, cm.billing_cycle_id, pm.customer_name, bc.start_date, bc.end_date FROM contract_master cm, billing_cycle bc, party_master pm WHERE cm.billing_cycle_id=bc.billing_cycle_id AND cm.party_master_id=pm.party_master_id";
              $result = mysqli_query($dbc,$select_query);
              $row_counter = 0;
	            $datatableflag = false;
              if(mysqli_num_rows($result) > 0) {
		              $datatableflag = true;
                  $count = 1;
                  while($row = mysqli_fetch_array($result)) {
                      echo "<tr>";
                          echo "<td>".$count++."</td>";
                          echo "<td>".$row['contract_master_id']."</td>";
				                  echo "<td>".$row['customer_name']."</td>";
				                  echo "<td>".$row['start_date']." to ".$row['end_date']."</td>";
                          echo "<td>".$row['bill_type']."</td>";
                          echo "<td><a href='contract_edit.php?contract_master_id={$row['contract_master_id']}'>View</a></td>";
                      echo "</tr>";
                  }
              }else{
                  echo '<tr><td colspan="6">No Contracts added</td></tr>';
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
