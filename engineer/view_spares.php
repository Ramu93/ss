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

$engineer_id = $_SESSION['userid'];

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
          <h5>Material Return</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="spare_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Client Name</th>
						<th>Location</th>
						<th>Material Name</th>
                        <th>Returnable Quantity</th>
					</tr>
                </thead>
                <tbody>
                    <?php 
                        $select_query = "SELECT a.spare_request_id,a.ticket_id, a.item_master_id, b.item_name, a.quantity,a.replaced_quantity, a.returned_quantity , a.spare_status, d.customer_name, e.location_name
                                        FROM spare_request a, item_master b, raise_ticket c, party_master d, party_location e
                                        WHERE (a.replaced_quantity - a.returned_quantity) > 0 AND a.item_master_id = b.item_master_id AND b.is_returnable = 'yes' AND c.engineer_id = $engineer_id 
                                        AND a.ticket_id = c.ticket_id AND c.client_id = d.party_master_id AND c.location_id = e.party_location_id";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        $datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
                            $datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>".$row['ticket_id']."</td>";
                                echo "<td>".$row['customer_name']."</td>";
								echo "<td>".$row['location_name']."</td>";
								echo "<td>".$row['item_name']."</td>";
								echo "<td>".($row['replaced_quantity'] - $row['returned_quantity'])."</td>";								
                                echo "</tr>";
                            }
                        }else{
                            echo '<tr><td colspan="6">No old spares to return</td></tr>';
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
    $('#spare_list').DataTable();
    <?php } ?>
    $('#li_sc_ticketmenu').addClass('active open');
});
function assign_spare(spare_id){
    engineer_id = $('#select_'+spare_id).val();
	data = 'spare_id='+spare_id+'&action=assign_spare';
	$.ajax({
		url: "spareservice.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "SPAREUPDATED"){
				bootbox.alert(result.message, function(){
                    location.reload();
                });
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