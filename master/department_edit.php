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
 
$department_id = 0;
$party_master_id = isset($_GET['party_master_id'])?$_GET['party_master_id']:0;
$party_location_id = isset($_GET['party_location_id'])?$_GET['party_location_id']:0;
if(isset($_GET['department_id'])){
    $department_id = $_GET['department_id'];
}else{
    header('Location: department_view.php'); exit();
}

$query = "SELECT * FROM  department WHERE department_id = $department_id";
$result = mysqli_query($dbc, $query);
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);
}else{
    header('Location: department_view.php'); exit();
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
          <h5>Department Edit</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="editdepartment_form" name="editdepartment_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Party Name :</label>
					<div class="controls">
                         <?php $query = "SELECT * FROM party_master";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '<select name="party_master_id" id="party_master_id" class="form-control required" onchange="asset_change();">';
                                    while($row=mysqli_fetch_array($result)){
                                        echo '<option value="'.$row['party_master_id'].'">'.$row['customer_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>	
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Location :</label>
					<div class="controls">
                        <?php $query = "SELECT * FROM  party_location WHERE party_master_id = $party_master_id";
                                $result = mysqli_query($dbc,$query);
                                if(mysqli_num_rows($result)>0){
                                    echo '<select name="party_location_id" id="party_location_id" class="form-control-required" onchange="">';
                                    while($row=mysqli_fetch_array($result)){
                                        echo '<option value="'.$row['party_location_id'].'">'.$row['location_name'].'</option>';
                                    }
                                    echo '</select>';
                                }
                        ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Department :</label>
					<div class="controls">
						<input type="text" class="form-control required" name="department_name" id="department_name" placeholder="Department" value="<?php echo $row['department_name']; ?>" />
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success" onclick="update_department_details();">Submit Department</button>
				</div>
				<input type="hidden" name="action" id="action" value="edit_department"/>
				<input type="hidden" name="department_id" id="department_id" value="<?php echo $department_id; ?>"/>
			</form>
		</div>
	</div>
</div>

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#editdepartment_form').validate();
    $('#li_mastermenu').addClass('in');
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function update_department_details(){
    if($('#editdepartment_form').valid()){
        var data = $('#editdepartment_form').serialize();
        $.ajax({
            url: "departmentservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "DEPARTMENTUPDATED"){
                    bootbox.alert(result.message, function(){
                        window.location = 'department_view.php';
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