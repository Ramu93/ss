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
 
$service_coordinator_id = 0;
if(isset($_GET['service_coordinator_id'])){
    $service_coordinator_id = $_GET['service_coordinator_id'];
}else{
    header('Location: service_coordinator_view.php'); exit();
}

$query = "SELECT * FROM service_coordinator WHERE service_coordinator_id = $service_coordinator_id";
$result = mysqli_query($dbc, $query);
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);
}else{
    header('Location: service_coordinator_view.php'); exit();
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

	<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Service Coordinator</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="ediservice_form" name="ediservice_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Service Coordinator :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="service_coordinator_name" id="service_coordinator_name" placeholder="Service Coordinator"  value="<?php echo $row['service_coordinator_name']; ?>" />
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="update_service_details();">Submit Ticket</button>
				</div>
				<input type="hidden" name="action" id="action" value="edit_service"/>
				<input type="hidden" name="service_coordinator_id" id="service_coordinator_id" value="<?php echo $service_coordinator_id; ?>"/>
			</form>
		</div>
	</div>
</div>

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#ediservice_form').validate();
    $('#sb_ul_mastermenu').addClass('in');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function update_service_details(){
    if($('#ediservice_form').valid()){
        var data = $('#ediservice_form').serialize();
        $.ajax({
            url: "coordinatorservice.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "SERVICEUPDATED"){
                    bootbox.alert(result.message, function(){
                        window.location = 'service_coordinator_view.php';
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