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

	<div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Employee List</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="asset_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>S. No.</th>
            <th>Employee ID</th>
  					<th>Employee Name</th>
  					<th>Advance Date</th>
            <th>Payment Mode</th>
            <th>Advance Amount</th>
            <th>Action</th>
          </tr>
        </thead>
				<tbody>
          <?php
              $select_query = "SELECT emp_mst.employee_name, emp_adv.emp_master_id, emp_adv.advance_date, emp_adv.advance_amount, emp_adv.payment_mode FROM hr_employee_master emp_mst, hr_employee_advance emp_adv WHERE emp_adv.emp_master_id=emp_mst.hr_emp_master_id AND emp_adv.advance_amount>0";
              $result = mysqli_query($dbc,$select_query);
              $row_counter = 0;
	            $datatableflag = false;
              if(mysqli_num_rows($result) > 0) {
		              $datatableflag = true;
                  $count = 1;
                  while($row = mysqli_fetch_array($result)) {
                      echo "<tr>";
                          echo "<td>".$count++."</td>";
                          echo "<td>".$row['emp_master_id']."</td>";
                          echo "<td>".$row['employee_name']."</td>";
				                  echo "<td>".$row['advance_date']."</td>";
                          echo "<td>".$row['payment_mode']."</td>";
                          echo "<td>".$row['advance_amount']."</td>";
                          echo "<td><a onclick='selectAdvance({$row['emp_master_id']});' >Update</a></td>";
                      echo "</tr>";
                  }
              }else{
                  echo '<tr><td colspan="6">No advance records added</td></tr>';
              }
          ?>
        </tbody>
      </table>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<!-- update advance modal -->
<div class="modal fade large" style="width: 70%; margin-left: -40% !important;" id="update_advance_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Update Advance Details</h4>

            </div>
            <div class="modal-body">
              <div class="widget-content nopadding">
                <form id="update_advance_form" name="update_advance_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
                  <div class="control-group">
                    <label class="control-label">Employee ID:</label>
                    <div class="controls">
                      <input type="text" class="form-control" name="emp_mst_id_val" id="emp_mst_id_val" placeholder="" readonly="true" />
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Employee Name:</label>
                    <div class="controls">
                     <input type="text" class="form-control" name="emp_name_val" id="emp_name_val" placeholder="" readonly="true" />
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Total Advance Amount:</label>
                    <div class="controls">
                     <input type="text" class="form-control" name="advance_amount" id="advance_amount" placeholder="" readonly="true" />
                    </div>
                  </div>
                  <div class="control-group" id="deduction_type_div">
                    <label class="control-label">Deduction Type:</label>
                    <div class="controls">
                     <input type="radio" name="deduction_type" id="deduction_type_auto" value="auto"> Auto&nbsp;&nbsp;&nbsp;<input type="radio" name="deduction_type" id="deduction_type_manual" value="manual"> Manual
                    </div>
                  </div>
                  <div class="control-group" id="deduction_amount_div">
                    <label class="control-label">Deduction Amount:</label>
                    <div class="controls">
                     <input type="text" class="form-control required" name="deduction_amount" id="deduction_amount" placeholder="Deduction amount" />
                    </div>
                  </div>
                  <div class="control-group" id="deduction_amount_div">
                    <label class="control-label">Repay Amount:</label>
                    <div class="controls">
                     <input type="text" class="form-control required" name="repay_amount" id="repay_amount" placeholder="Repay amount" value="0" />
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="modal-footer">
              <span id="saved_message_div" style="color: red;"></span>
              <span class="align-left" id="result_msg"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save" onclick="saveAdvance();">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- update advance modal ends -->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here
$(document).ready(function() {
    <?php if($datatableflag){ ?>
    $('#asset_list').DataTable();
    <?php } ?>
    $('#li_sc_engineermenu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');
    bindAdvanceEvents();
});

function selectAdvance(empMasterId){
  var data = 'emp_mst_id='+empMasterId+'&action=get_advance_details';
  $.ajax({
    url: "hr_services.php",
    type: "POST",
    data:  data,
    dataType: 'json',
    success: function(result){
      if(result.infocode == "ADVANCEAVAILABLE"){
          $('#advance_amount').val(result.advance_amount);
          $('#emp_name_val').val(result.employee_name);
          $('#emp_mst_id_val').val(result.employee_id);
          if(result.deduction_type == 'auto'){
            $('#deduction_type_auto').attr('checked','');
            $('#deduction_amount_div').show();
          } else {
            $('#deduction_type_manual').attr('checked','');
            $('#deduction_amount_div').hide();
          }
          $('#deduction_amount').val(result.deduction_amount);
      }
    },
    error: function(){}
  });
  $('#update_advance_modal').modal();
}

function bindAdvanceEvents(){
  $('#deduction_type_manual').change(function(){
    if($('#deduction_type_manual').val() == 'manual'){
      $('#deduction_amount_div').hide();
    }
  });
  $('#deduction_type_auto').change(function(){
    if($('#deduction_type_auto').val() == 'auto'){
      $('#deduction_amount_div').show();
    }
  });
}

function saveAdvance(){
  var data = $('#update_advance_form').serialize() + '&action=update_advance';
  $.ajax({
        url: "hr_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            if(result.infocode == "ADVANCEUPDATED"){
                $('#saved_message_div').html(result.message).fadeIn(400).fadeOut(4000);
                $('#repay_amount').val(0);
                selectAdvance($('#emp_mst_id_val').val());
            }else{
                $('#saved_message_div').html(result.message).fadeIn(400).fadeOut(4000);
            }
        },
        error: function(){}
    });
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>
