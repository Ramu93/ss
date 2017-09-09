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
          <h5>Complaint Master</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="addcomplaint_form" name="addcomplaint_form" action="" method="post" class="form-horizontal" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Complaint Name :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="complaint_name" id="complaint_name" placeholder="Complaint Name" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Complaint Code :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="complaint_code" id="complaint_code" placeholder="Complaint Code" />
					</div>
				</div>
				<div class="control-group row-fluid" style="padding-bottom: 10px;">
                    <div class="span6 text-right">
                       <button type="submit" class="btn btn-success" onclick="add_complaint_details();">Add Complaint</button>
                    </div>
                    <div class="span6">
                       <button type="button" class="btn btn-primary" onclick="window.location = 'complaint_view.php';">View Complaints</button>
                    </div>
                </div>
				<input type="hidden" name="action" id="action" value="add_complaint"/>
			</form>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function(){
    $('#addcomplaint_form').validate();
    $('#sb_ul_mastermenu').addClass('in');
    $('#li_sc_ticketmenu').addClass('active open');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function add_complaint_details(){
    if($('#addcomplaint_form').valid()){
        var data = $('#addcomplaint_form').serialize();
        $.ajax({
            url: "complaintservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "COMPLAINTADDED"){
                    bootbox.alert(result.message);
                    $('#addcomplaint_form')[0].reset();
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