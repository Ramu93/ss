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
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Customer List</h5>
        </div>
        <div class="widget-content nopadding">
			
        			<table id="customer_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
							    <th>Customer Id</th>
                                <th>Customer Name</th>
                                <th>City</th>
                                <th>State</th>
                                
                                <th>Primary Contact</th>
                                <th>Primary Mobile</th>
                                <th>Primary Email</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
						    <?php 
								$select_query = "SELECT * FROM party_master";
								$result = mysqli_query($dbc,$select_query);
								$row_counter = 0;
                                $datatableflag = false;
								if(mysqli_num_rows($result) > 0) {
                                    $datatableflag = true;
									while($row = mysqli_fetch_array($result)) {
										echo "<tr>";
											echo "<td>".++$row_counter."</td>";

											echo "<td>".$row['customer_name']."</td>";
											echo "<td>".$row['city_town']."</td>";
											echo "<td>".$row['state']."</td>";
											echo "<td>".$row['primary_contact_name']."</td>";
											echo "<td>".$row['primary_contact_mobile']."</td>";
											echo "<td>".$row['primary_contact_email']."</td>";
											/*echo "<td>".$row['credit_days']."</td>";
											echo "<td>".$row['credit_limit']."</td>";
											echo "<td>".$row['opening_balance']."</td>";
											echo "<td>".$row['created_date']."</td>";*/
											
											echo "<td><a href='party_edit.php?party_master_id={$row['party_master_id']}'>View</a></td>";
										echo "</tr>";
									}
								}
							?>

                        </tbody>
                    </table>
        </div>
	</div>
</div>
<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
    
$(document).ready(function() {
    //$('#party_master_list_data').DataTable();
    $('#li_sc_ticketmenu').addClass('active open');
    //fetch_partymaster();
    <?php if($datatableflag){ ?>
    $('#customer_list').DataTable();
    <?php } ?>

});

$(document).ready(function() {
    $('#party_list').DataTable();
    $('#sb_ul_mastermenu').addClass('in');
});
</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>