<?php 
session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}

$client_id = 22;//$_SESSION['userid'];
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
          <h5>View Tickets</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="ticket_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
						<th>Location</th>
						<th>Product</th>
						<th>Nature Complaint</th>
						<th>Additional Details</th>
						<th>Machine Reading</th>                        
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $select_query = "SELECT * FROM raise_ticket a WHERE a.client_id = $client_id ";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        $datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
                        	$datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".++$row['ticket_id']."</td>";
									echo "<td>".$row['location_name']."</td>";
									echo "<td>".$row['product']."</td>";	
									echo "<td>".$row['complaint_name']."</td>";
									echo "<td>".$row['additional_details']."</td>";
									echo "<td>".$row['machine_reading']."</td>";
									$status = '';
									if($row['ticket_status'] == 'created'){
										$status = 'Your ticket will be attended shortly';
									}else if($row['ticket_status'] == 'assigned'){
										$status = 'Your ticket is assigned to engineer';
									}else if($row['ticket_status'] == 'engineerclosed' || $row['ticket_status'] == 'closed'){
										$status = 'Your ticket has been closed';
									}else if($row['ticket_status'] == 'spareawaiting'){
										$status = 'Your complaing will be closed upon changing the spare';
									}
									echo '<td>'.$status.'</td>';
                                echo "</tr>";
                            }
                        }else{
                        	echo '<tr><td colspan="6">No open tickets</td></tr>';
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
    $('#ticket_list').DataTable();
    <?php } ?>
    $('#li_sc_ticketmenu').addClass('active open');
});
function assign_ticket(ticket_id){
    engineer_id = $('#select_'+ticket_id).val();
	data = 'ticket_id='+ticket_id+'&engineer_id='+engineer_id+'&action=assign_ticket';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "TICKETUPDATED"){
				bootbox.alert(result.message);
				//$('#addticket_form')[0].reset();
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
    
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>