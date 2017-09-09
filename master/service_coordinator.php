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
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>index1.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Service Coordinator</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="addservice_form" name="addservice_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Service Coordinator :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="service_coordinator_name" id="service_coordinator_name" placeholder="Service Coordinator" />
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="add_service_details();">Submit Ticket</button>
				</div>
				<input type="hidden" name="action" id="action" value="add_service"/>
			</form>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function(){
    $('#addservice_form').validate();
    $('#sb_ul_mastermenu').addClass('in');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_service_details(){
    if($('#addservice_form').valid()){
        var data = $('#addservice_form').serialize();
        $.ajax({
            url: "coordinatorservice.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "SERVICEADDED"){
                    bootbox.alert(result.message);
                    $('#addservice_form')[0].reset();
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