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

	<div class="widget-box widget-box-10">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Spares Request</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="addspare_form" name="addspare_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Ticket ID :</label>
					<div class="controls">
						<?php 
						$query = "SELECT * FROM raise_ticket WHERE ticket_status != 'closed'";
						echo '<select name="ticket_id" id="ticket_id" class="form-control required" onchange="">';
						$result = mysqli_query($dbc,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_array($result)){
		                    	echo '<option value="'.$row['ticket_id'].'">'.$row['ticket_id'].'</option>';
							}
						}else{
							echo '<option value="">No tickets available</option>';
						}
						echo '</select>';
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Product :</label>
					<div class="controls">
						<?php /*$query = "SELECT distinct(product), product_id FROM raise_ticket";
										$result = mysqli_query($dbc,$query);
										if(mysqli_num_rows($result)>0){
										echo '<select name="product" id="product_id" class="form-control" onchange="">';
										while($row=mysqli_fetch_array($result)){
                                        echo '<option value="'.$row['product_id'].'">'.$row['product'].'</option>';
										}
										echo '</select>';
									}*/
						?>
						<select name="product" id="product_id" class="form-control" >
							<option value="Canon">Canon</option>
							<option value="GBC">GBC</option>
							<option value="Lexmark">Lexmark</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Spare :</label>
					<div class="controls">	
						<select multiple="multiple" id="myselect" name="spare_name">
							<option value="Toner">Toner</option>
							<option value="Cartridge">Cartridge</option>
							<option value="Board IC">Board IC</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Available Stock :</label>
					<div class="controls">
						<!--input type="text" class="span11 required number" id="available_stock" name="available_stock" placeholder="Available Stock" /-->
						<label style="margin-top:5px;">10</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Remark :</label>
					<div class="controls">
						<input class="form-control" name="spare_remark" id="spare_remark" type="text" placeholder="Remark">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Quantity :</label>
					<div class="controls">
						<input type="text" class="span11 required number" id="quantity" name="quantity" placeholder="Quantity" />
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="add_spare_details();">Request Spares</button>
				</div>
				<input type="hidden" name="action" id="action" value="add_spare"/>
			</form>
		</div>
	</div>
</div>

<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function(){
    $('#addspare_form').validate();
    $('#li_sc_ticketmenu').addClass('active open');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_spare_details(){
    if($('#addspare_form').valid()){
        var data = $('#addspare_form').serialize();
        $.ajax({
            url: "spareservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "SPAREADDED"){
                    bootbox.alert(result.message);
                    $('#addspare_form')[0].reset();
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>