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

$NCMPLTID = 0;
if(isset($_GET['NCMPLTID'])){
    $NCMPLTID = $_GET['NCMPLTID'];
}else{
    header('Location: complaint_view.php'); exit();
}

$query = "SELECT * FROM nature_of_comp WHERE NCMPLTID = $NCMPLTID";
$result = mysqli_query($dbc, $query);
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);
}else{
    header('Location: complaint_view.php'); exit();
}
?>

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
			<form id="editcomplaint_form" name="editcomplaint_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Complaint Name :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="complaint_name" id="complaint_name" placeholder="Complaint Name"  value="<?php echo $row['complaint_name']; ?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Complaint Code :</label>
					<div class="controls">
                         <input type="text" class="form-control required" name="complaint_code" id="complaint_code" placeholder="Complaint Code" value="<?php echo $row['complaint_code']; ?>" />
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="update_complaint_details();">Update Complaint</button>
				</div>
				<input type="hidden" name="action" id="action" value="edit_complaint"/>
				<input type="hidden" name="NCMPLTID" id="NCMPLTID" value="<?php echo $NCMPLTID; ?>"/>
			</form>
		</div>
	</div>
</div>

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#editcomplaint_form').validate();
    $('#sb_ul_mastermenu').addClass('in');
    $('#li_sc_ticketmenu').addClass('active open');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function update_complaint_details(){
    if($('#editcomplaint_form').valid()){
        var data = $('#editcomplaint_form').serialize();
        $.ajax({
            url: "complaintservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "COMPLAINTUPDATED"){
                    bootbox.alert(result.message, function(){
                        window.location = 'complaint_view.php';
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