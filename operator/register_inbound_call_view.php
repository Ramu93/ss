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

function engineer_list($ticket_id){
	global $dbc;$op = '';
	$query = "SELECT * FROM engineer";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result)>0){
		$op .= '<select name="select_'.$ticket_id.'" id="select_'.$ticket_id.'" class="form-control" style="width:90%;">';
		while($row = mysqli_fetch_assoc($result)){
			$op .= '<option value="'.$row['engineer_id'].'">'.$row['engineer_name'].'</option>';
		}
		$op .= '</select>';
	}
	return $op;
}

$ticket_status = isset($_GET['ticket_status'])?$_GET['ticket_status']:'created';
?>
<style type="text/css">
.modal.large {
    width: 60%; /* respsonsive width */
    margin-left:-30%; /* width/2) */ 
}
</style>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->
	<div class="widget-box widget-box-90">

        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Inbound Call View</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="ticket_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Inbound Call</th>
                        <th>Call Type</th>
						<th>Customer Name</th>
						<th>Location</th>
						<th>Department</th>
                        <th>Customer Address</th>
						<th>Contact Person</th>
						<th>Phone Number</th>
                        <th>Remark </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $select_query = "SELECT * FROM inbound_call";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        $datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {	
                        	$datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".$row['inbound_call_id']."</td>";
									
                                    echo "<td>".$row['call_type']."</td>";
                                    echo "<td>".$row['customer_name']."</td>";
                                    echo "<td>".$row['party_location_id']."</td>";
                                    echo "<td>".$row['department_id']."</td>";
									echo "<td>".$row['customer_address']."</td>";	
									echo "<td>".$row['contact_person']."</td>";
									echo "<td>".$row['contact_number']."</td>";
									echo "<td>".$row['remarks']."</td>";
									
									echo "<td><a href='inbound_call_edit.php?inbound_call_id={$row['inbound_call_id']}'>View</a></td>";
                                echo "</tr>";
                            }
                        }else{
                        	echo '<tr><td colspan="10">No Inbound Call awaiting to be assigned</td></tr>';
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
<script type="text/javascript" src="js/process_ticket.js"></script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>