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
          <h5>Close Ticket</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="ticket_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
						<th>Client</th>
                        <th>Location</th>
						<th>Department</th>
						<th>Product</th>
						<th>Nature Complaint</th>
						<th>Closed by</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $select_query = "SELECT a.*,b.engineer_name,c.product,d.department_name FROM raise_ticket a, engineer b, product c, department d WHERE ticket_status = 'engineerclosed' AND a.engineer_id = b.engineer_id AND a.product_id=c.product_master_id AND a.department_id=d.department_id";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        $datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
                        	$datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".$row['ticket_id']."</td>";
									
									echo "<td>".$row['client_name']."</td>";
                                    echo "<td>".$row['location_name']."</td>";
									echo "<td>".$row['department_name']."</td>";
									echo "<td>".$row['product']."</td>";	
									echo "<td>".$row['complaint_name']."</td>";
									echo "<td>".$row['engineer_name']."</td>";
									//echo '<input type="hidden" name="hidden_ticketid_'.$row['ticket_id'].'" value="'..'"
									//echo '<td>'.(engineer_list($row['ticket_id'])).'</td>';
									echo '<td><button class="btn btn-primary" onclick="close_ticket(\''.$row['ticket_id'].'\');">Close Ticket</button></td>';
                                    
                                echo "</tr>";
                            }
                        }else{
                        	echo '<tr><td colspan="8">No tickets awaiting to be closed</td></tr>';
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
function close_ticket(ticket_id){
    engineer_id = $('#select_'+ticket_id).val();
	data = 'ticket_id='+ticket_id+'&action=close_ticket';
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