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

$mrn_type = isset($_GET['mrn_type'])?$_GET['mrn_type']:'internal';
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
              <h5>Material Receipt Note</h5>
            </div>
            <div class="widget-content nopadding">
                <div class="row-fluid">
                    <div class="span1"></div>
                    <div class="span4">
                        <label> MRN Type: </label>
                        <select name="mrn_type" id="mrn_type" class="form-control" onchange="mrn_type_change();">
                            <option value="internal" <?php echo ($mrn_type == 'internal')?'selected="selected"':'' ?>>Internal</option>
                            <option value="purchase" <?php echo ($mrn_type == 'purchase')?'selected="selected"':'' ?>>Purchase</option>
                        </select>
                    </div>
                    <div class="span4" id="engineer_list_div">
                        <label> Select Engineer: </label>
                        <div class="controls">
                            <?php $query = "SELECT * FROM employee_master WHERE rolename='engr'";
                                    $result = mysqli_query($dbc,$query);
                                    if(mysqli_num_rows($result)>0){
                                        echo '<select name="engineer_id" id="engineer_id" class="form-control required" onchange="fetch_engineer_stock();">';
                                        echo '<option value="" selected="selected">Select Engineer</option>';
                                        while($row=mysqli_fetch_array($result)){
                                            echo '<option value="'.$row['employee_master_id'].'">'.$row['employee_name'].'</option>';
                                        }
                                        echo '</select>';
                                    }
                            ?>
                        </div>
                    </div>
                    <!--div class="span2" id="without_po_div" style="display: none;">
                        <label><input type="checkbox" class="form-control" name="without_po" id="without_po" onchange="without_po_change();" value="without_po">Without PO</label>
                    </div-->
                    <div class="span5" style="display: none;" id="po_div">
                        <label> Purchase Order No: </label>
                        <input type="text" name="purchase_order_number" id="purchase_order_number" class="form-control">
                        <button type="button" class="btn btn-primary" name="fetch_po" class="form-control" onclick="fetch_purchase_order();">Fetch</button>
                    </div>
                </div>
                <table id="internal_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Material Name</th>
                            <th>Available Qty</th>
                            <th>Returned Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="internal_list_tbody">
                         <tr><td colspan="5">Please select engineer</td></tr>           
                    </tbody>
                    <tfoot></tfoot>
                </table>
    			<table id="purchase_list" class="table table-striped table-bordered" cellspacing="0" width="100%" style="display: none;">
                    <thead>
                        <tr>
                            <th>S.No</th>
    						<th>Material Name</th>
    						<th>Accepted Qty</th>
                            <th>Rejected Qty</th>
    						<th>Action</th>
                        </tr>
                    </thead>
    				<tbody id="purchase_list_tbody">
    					<tr><td colspan="5">Please enter PO Details</td></tr>
                    </tbody>
                    <tfoot></tfoot>
                </table>
                
    		</div>
            <div class="row-fluid" style="padding:25px;">
                <div class="span8"></div>
                <div class="span3 text-right">
                    <button type="button" class="btn btn-success" onclick="receive_material();" disabled="disabled" id="btn_receive_material">Receive Material</button>
                    <input type="hidden" name="action" value="receive_material" />
                    <label for="picked_list[]" generated="true" class="error"></label>
                </div>
            </div>
        </form>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript" src="js/mrn.js"></script>
<script type="text/javascript">
//write all js here	
$(document).ready(function() {
    <?php //if($datatableflag){ ?>
    //$('#spare_list').DataTable();
    <?php //} ?>
    $('#li_store_menu').addClass('active open');
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