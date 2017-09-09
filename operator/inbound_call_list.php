<?php 

session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}

$engr_id = $_SESSION['userid'];
include('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
include('..'.DIRECTORY_SEPARATOR.'header.php');
include('..'.DIRECTORY_SEPARATOR.'topbar.php');
include('..'.DIRECTORY_SEPARATOR.'sidebar.php');

$call_type = isset($_GET['call_type'])?$_GET['call_type']:'service';
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->
    <div class="row-fluid">
        <div class="span1"></div>
        <div class="span4">
            <div class="form-control">
                <label class="form-control">Ticket Status : </label>
                    <select name="call_type" id="call_type" class="form-control" onchange="status_change();">
                        <option value="service" <?php echo ($call_type == 'service')?'selected="selected"':''; ?> >Service</option>
                        <option value="sales" <?php echo ($call_type == 'sales')?'selected="selected"':''; ?> >Sales</option>
                    </select>
                
            </div>
        </div>
    </div>
	<div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>View Call Register</h5>
        </div>
        <div class="widget-content nopadding">
            <?php if($call_type == 'service'){ ?>
			<table id="call_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Inbound Call ID</th>
                        <th>Customer Name</th>
						<th>Location</th>
						<th>Contact Person</th>
						<th>Phone Number</th>
                        <th>Remark </th>
                        <!--th>Action</th-->
					</tr>
                </thead>
                <tbody>
                    <?php 
                        $select_query = "SELECT a.*, b.customer_name, c.location_name
                        FROM inbound_call a, party_master b, party_location c
                        WHERE a.call_type LIKE '%service%' AND a.party_master_id = b.party_master_id AND a.party_location_id = c.party_location_id";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        $datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
                        	$datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".$row['inbound_call_id']."</td>";
									echo "<td>".$row['customer_name']."</td>";
                                    echo "<td>".$row['location_name']."</td>";
                                    echo "<td>".$row['contact_person']."</td>";	
									echo "<td>".$row['contact_number']."</td>";
									echo "<td>".$row['remarks']."</td>";
									//echo "<td><a href='inbound_call_edit.php?inbound_call_id={$row['inbound_call_id']}'>View</a></td>";
                                echo "</tr>";
                            }
                        }else{
                        	echo '<tr><td colspan="8">No service calls</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
            <?php }else{ ?>
                <table id="call_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Inbound Call ID</th>
                        <th>Customer Name</th>
                        <th>Customer Address</th>
                        <th>Contact Person</th>
                        <th>Phone Number</th>
                        <th>Remark </th>
                        <!--th>Action</th-->
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $select_query = "SELECT * FROM inbound_call  WHERE call_type LIKE '%sales%' OR call_type LIKE '%marketing%'";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        $datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
                            $datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".$row['inbound_call_id']."</td>";
                                    echo "<td>".$row['customer_name']."</td>";
                                    echo "<td>".$row['customer_address']."</td>";
                                    echo "<td>".$row['contact_person']."</td>"; 
                                    echo "<td>".$row['contact_number']."</td>";
                                    echo "<td>".$row['remarks']."</td>";
                                    //echo "<td><a href='inbound_call_edit.php?inbound_call_id={$row['inbound_call_id']}'>View</a></td>";
                                echo "</tr>";
                            }
                        }else{
                            echo '<tr><td colspan="8">No service calls</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
            <?php } ?>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function() {
    <?php if($datatableflag){ ?>
    $('#call_list').DataTable();
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

function status_change(){
    call_type = $('#call_type').val();
    window.location = 'inbound_call_list.php?call_type='+call_type;
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>