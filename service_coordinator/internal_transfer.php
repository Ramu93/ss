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

$engineer_id = isset($_GET['engineer_id'])?$_GET['engineer_id']:'';
?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-90">
        <form id="receive_material_form" action="" onsubmit="return false;" method="POST">
            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
              <h5>Internal Material Transfer</h5>
            </div>
            <div class="widget-content nopadding">
                <div class="row-fluid">
                    <div class="span1"></div>
                    <div class="span4" id="engineer_list_div">
                        <label> Select Engineer: </label>
                        <div class="controls">
                            <?php $query = "SELECT * FROM employee_master WHERE rolename='engr'";
                                    $result = mysqli_query($dbc,$query);
                                    if(mysqli_num_rows($result)>0){
                                        echo '<select name="engineer_id" id="engineer_id" class="form-control required" onchange="fetch_engineer_stock();">';
                                        echo '<option value="" selected="selected">Select Engineer</option>';
                                        while($row=mysqli_fetch_array($result)){
                                            $selected = ($row['employee_master_id'] == $engineer_id)?'selected="selected"':'';
                                            echo '<option value="'.$row['employee_master_id'].'" '.$selected.'>'.$row['employee_name'].'</option>';
                                        }
                                        echo '</select>';
                                    }
                            ?>
                        </div>
                    </div>
                </div>
                <table id="internal_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Material Name</th>
                            <th>Available Qty</th>
                            <th>Transfer Qty</th>
                            <th>Transfer To</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="internal_list_tbody">
                         <tr><td colspan="5">Please select engineer</td></tr>           
                    </tbody>
                    <tfoot></tfoot>
                </table>
    		</div>
            <div class="row-fluid" style="padding:25px;">
                <div class="span8"></div>
                <div class="span3 text-right">
                    <!--button type="button" class="btn btn-success" onclick="receive_material();" disabled="disabled" id="btn_receive_material">Receive Material</button-->
                    <input type="hidden" name="action" value="receive_material" />
                    <label for="picked_list[]" generated="true" class="error"></label>
                </div>
            </div>
        </form>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript" src="js/internal_transfer.js"></script>
<script type="text/javascript">
//write all js here	
$(document).ready(function() {
    fetch_engineer_stock();
    //$('#li_store_menu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');
    $('#receive_material_form').validate({
        rules: { 
            "picked_list[]": { 
                    required: true, 
                    minlength: 1 
            } 
        }, 
        messages: { 
                "picked_list[]": "Please select at least one material."
        } 
    });
});

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>