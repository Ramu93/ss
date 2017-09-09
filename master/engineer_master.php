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

<style type="text/css">
.error{
    color:red;
}
</style>
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>index1.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-10">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Engineer Master</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="addengineer_form" name="addengineer_form" action="" method="post" class="form-horizontal" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Service Coordinator :</label>
					<div class="controls">
						<?php $query = "SELECT * FROM service_coordinator";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '<select name="service_coordinator_id" id="service_coordinator_id" class="form-control" onchange="">';
                                    while($row=mysqli_fetch_array($result)){
                                        echo '<option value="'.$row['service_coordinator_id'].'">'.$row['service_coordinator_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Engineer :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="engineer_name" id="engineer_name" placeholder="Engineer" />
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="add_engineer_details()">Submit Ticket</button>
				</div>
				<input type="hidden" name="action" id="action" value="add_engineer"/>
			</form>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function(){
    $('#addengineer_form').validate();
    $('#sb_ul_mastermenu').addClass('in');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_engineer_details(){
    if($('#addengineer_form').valid()){
        var data = $('#addengineer_form').serialize();
        $.ajax({
            url: "engineerservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "ENGINEERADDED"){
                    bootbox.alert(result.message);
                    $('#addengineer_form')[0].reset();
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