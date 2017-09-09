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

$ticket_status = isset($_GET['ticket_status'])?$_GET['ticket_status']:'created';
$from_date = isset($_GET['from_date'])?$_GET['from_date']:date('Y-m-d');
$to_date = isset($_GET['to_date'])?$_GET['to_date']:date('Y-m-d');
$pending_days = isset($_GET['pending_days'])?$_GET['pending_days']:0;
?>
<style type="text/css">
	.modal.large {
	    width: 60%; /* respsonsive width */
	    margin-left:-30%; /* width/2) */ 
	}
	.datepicker {
		z-index: 9999;
	}
	.overlay{
	  position: absolute;
	  top: 0;
	  left: 0;
	  width: 100%;
	  height: 100%;
	  z-index: 9998;
	  background-color: rgba(230,230,230,0.8); /*dim the background*/
	}
	.overlay2{
		top: 30%;
		left: 40%;
		position: relative;
	}
	.history_item{
		border: 1px black solid;
	}
</style>

<!--main-container-part-->
<div id="content">
	<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
  
	<!--End-breadcrumbs-->
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span3">
			<div class="form-control">
				<label class="form-control">Ticket Status : </label>
				<select name="ticket_status" id="ticket_status" class="form-control" onchange="status_change();">
					<option value="created" <?php echo ($ticket_status == 'created')?'selected="selected"':''; ?> >Unassigned</option>
					<option value="assigned" <?php echo ($ticket_status == 'assigned')?'selected="selected"':''; ?> >Pending</option>
					<option value="engineerclosed" <?php echo ($ticket_status == 'engineerclosed')?'selected="selected"':''; ?> >Closed by Engineer</option>
					<option value="closed" <?php echo ($ticket_status == 'closed')?'selected="selected"':''; ?> >Closed</option>
					<option value="sparespending" <?php echo ($ticket_status == 'sparespending')?'selected="selected"':''; ?> >Spares Pending</option>
				</select>
			</div>
		</div>
		<div id="daterange_div" style="display: none;">
			<div class="span3">
				<div class="form-control">
					<label class="form-control">From Date : </label>
					<input class="form-control datepicker" type="text" data-date-format="yyyy-mm-dd" id="from_date" name="from_date" value="<?php echo $from_date; ?>"/>
				</div>
			</div>
			<div class="span3">
				<div class="form-control">
					<label class="form-control">To Date : </label>
					<input class="form-control datepicker" type="text" data-date-format="yyyy-mm-dd" id="to_date" name="to_date" value="<?php echo $to_date; ?>"/>
				</div>
			</div>
		</div>
		<div id="pendingdate_div" style="display: none;">
			<div class="span3">
				<div class="form-control">
					<label class="form-control">Pending Days : </label>
					<select name="pending_days" id="pending_days" class="form-control" ">
						<option value="0" <?php echo ($pending_days == '0')?'selected="selected"':''; ?> >Today</option>
						<option value="1" <?php echo ($pending_days == '1')?'selected="selected"':''; ?> >> 1 day</option>
						<option value="2" <?php echo ($pending_days == '2')?'selected="selected"':''; ?> >> 2 days</option>
						<option value="3" <?php echo ($pending_days == '3')?'selected="selected"':''; ?> >> 3 days</option>
						<option value="4" <?php echo ($pending_days == '4')?'selected="selected"':''; ?> >> 4 days</option>
						<option value="5" <?php echo ($pending_days == '5')?'selected="selected"':''; ?> >> 5 days</option>
					</select>
				</div>
			</div>
			<!--div class="span3"></div-->
		</div>
		<div class="span1">
			<div class="form-control">
				<label class="form-control">&nbsp; </label>
				<button class="btn btn-primary"  id="btn_go" name="btn_go" onclick="list_tickets();" >Go</button>
			</div>
		</div>
	</div>

	<div class="widget-box widget-box-90">

        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Process Ticket</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="ticket_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
						<th>Customer</th>
                        <th>Location</th>
						<th>Product</th>
						<th>Nature Complaint</th>
						<th>Created Date</th>
                        <!--th>Engineer</th-->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="ticket_list_tbody">
                    <?php $userid = $_SESSION['userid'];
                    if($ticket_status == 'assigned'){
                        $select_query = "SELECT a.* , b.customer_name, c.location_name, e.item_name, f.complaint_name
                        FROM raise_ticket a, party_master b, party_location c, asset_master d, item_master e, nature_of_comp f
                        WHERE a.ticket_status = '$ticket_status' AND a.client_id = b.party_master_id AND a.location_id = c.party_location_id AND a.asset_master_id = d.asset_master_id 
                        AND d.item_master_id = e.item_master_id AND a.complaint_id = f.NCMPLTID AND a.sc_id = $userid AND DATE(creation_date) <= DATE(CURDATE() - $pending_days)";
                    }else{
                    	$select_query = "SELECT a.* , b.customer_name, c.location_name, e.item_name, f.complaint_name
                        FROM raise_ticket a, party_master b, party_location c, asset_master d, item_master e, nature_of_comp f
                        WHERE a.ticket_status = '$ticket_status' AND a.client_id = b.party_master_id AND a.location_id = c.party_location_id AND a.asset_master_id = d.asset_master_id 
                        AND d.item_master_id = e.item_master_id AND a.complaint_id = f.NCMPLTID AND a.sc_id = $userid AND DATE(creation_date) BETWEEN '$from_date' AND '$to_date'";
                    }
                        //$select_query = "SELECT * FROM raise_ticket WHERE ticket_status = '$ticket_status'";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
                        $datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
                        	$datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".$row['ticket_id']."</td>";
									echo "<td id='td_cust_name_".$row['ticket_id']."'>".$row['customer_name']."</td>";
                                    echo "<td id='td_location_name_".$row['ticket_id']."'>".$row['location_name']."</td>";
									echo "<td>".$row['item_name']."</td>";	
									echo "<td>".$row['complaint_name']."</td>";
									echo "<td>".$row['creation_date']."</td>";
									//echo '<input type="hidden" name="hidden_ticketid_'.$row['ticket_id'].'" value="'..'"
									//echo '<td>'.(engineer_list($row['ticket_id'])).'</td>';
									echo '<td><button type="button" class="btn btn-primary btn-lg" onclick="view_ticket_detail(\''.$row['ticket_id'].'\');">View Details</button>&nbsp;</td>';
                                    /*echo '<td><button class="btn btn-primary" onclick="assign_ticket(\''.$row['ticket_id'].'\');">Assign</button>&nbsp;
									<button class="btn btn-primary" onclick="addspare_ticket(\''.$row['ticket_id'].'\');">Spare</button>&nbsp;
									<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">View</button>&nbsp;
									<button class="btn btn-success"  onclick="close_ticket(\''.$row['ticket_id'].'\')">Close Ticket</button></td>';*/
                                echo "</tr>";
                            }
                        }else{
                        	echo '<tr><td colspan="7">No tickets available in this category</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('template_process_ticket.php'); ?>

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>

<script type="text/javascript">
//write all js here	
var g_ticket_status = '<?php echo $ticket_status; ?>';
$(document).ready(function() {
	<?php if($datatableflag){ ?>
    $('#ticket_list').DataTable();
    <?php } ?>
    $('#li_sc_ticketmenu').addClass('active open');
    $('#visit_date').datepicker({startDate:'0'});
    $('#visit_date_re').datepicker({startDate:'0'});
    $('#from_date').datepicker({endDate:'0'});
    $('#to_date').datepicker({endDate:'0'});
});

$(window).load(function(){
	status_change();
});

</script>
<script type="text/javascript" src="js/process_ticket.js"></script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>