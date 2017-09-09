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
 
$engineer_master_id = 0;
$service_coordinator_id = isset($_GET['service_coordinator_id'])?$_GET['service_coordinator_id']:0;
if(isset($_GET['engineer_master_id'])){
    $engineer_master_id = $_GET['engineer_master_id'];
}else{
    header('Location: engineer_master_view.php'); exit();
}

$query = "SELECT * FROM  master_engineer WHERE engineer_master_id = $engineer_master_id";
$result = mysqli_query($dbc, $query);
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);
}else{
    header('Location: engineer_master_view.php'); exit();
}
?>
<style type="text/css">
.error{
    color:red;
}
</style>
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>index1.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-10">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Engineer List</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="editengineer_form" name="editengineer_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Service Coordinator :</label>
					<div class="controls">
						<?php $query2 = "SELECT * FROM service_coordinator";
                                $result2 = mysqli_query($dbc,$query2);
                                if(mysqli_num_rows($result2)>0){
                                    echo '<select name="service_coordinator_id" id="service_coordinator_id" class="form-control" onchange="">';
                                    while($row2=mysqli_fetch_array($result2)){
										$selected = ($service_coordinator_id == $row2['service_coordinator_id'])?'selected="selected"':'';
                                        echo '<option value="'.$row2['service_coordinator_id'].'" '.$selected.'>'.$row2['service_coordinator_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                                ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Engineer :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="engineer_name" id="engineer_name" placeholder="Engineer" value="<?php echo $row['engineer_name']; ?>" />
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="update_engineer_details();">Submit Ticket</button>
				</div>
				<input type="hidden" name="action" id="action" value="edit_engineer"/>
				<input type="hidden" name="engineer_master_id" id="engineer_master_id" value="<?php echo $engineer_master_id; ?>"/>
			</form>
		</div>
	</div>
</div>

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#editengineer_form').validate();
    $('#sb_ul_mastermenu').addClass('in');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function update_engineer_details(){
    if($('#editengineer_form').valid()){
        var data = $('#editengineer_form').serialize();
        $.ajax({
            url: "engineerservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "SERVICEUPDATED"){
                    bootbox.alert(result.message, function(){
                        window.location = 'engineer_master_view.php';
                    });
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