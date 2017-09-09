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

	<div class="widget-box widget-box-10">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Contract Master</h5>
        </div>
        <div class="widget-content nopadding">
			<form id="addcontract_form" name="addcontract_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
				<div class="control-group">
					<label class="control-label">Customer :</label>
					<div class="controls">
             <?php
                $query = "SELECT * FROM party_master";
                $result = mysqli_query($dbc,$query);
                if(mysqli_num_rows($result)>0){
                    echo '<select name="party_master_id" id="party_master_id" class="form-control required" onchange="asset_change();">';
	                  echo '<option value="" selected="selected">Select Customer</option>';
                    while($row=mysqli_fetch_array($result)){
                        echo '<option value="'.$row['party_master_id'].'">'.$row['customer_name'].'</option>';
                    }
                    echo '</select>';
                }
            ?>
					</div>
				</div>
				<!-- <div class="control-group row-fluid">
					<label class="control-label">Location :</label>
					<div class="controls">
						<select name="party_location_id" id="party_location_id" class="form-control required" onchange="location_change();">
							<option value="">Select Location</option>
						</select>
                    </div>
				</div> -->
				<!-- <div class="control-group row-fluid">
					<label class="control-label">Department :</label>
					<div class="controls">
						<select name="department_id" id="department_id" class="form-control required" onchange="">
							<option value="">Select Department</option>
						</select>
					</div>
				</div> -->
        <div class="control-group">
            <label class="control-label">Bill Type:</label>
            <div class="controls">
                 <select class="form-control required" name="bill_type">
                   <option value="" selected="selected">Select Bill Type</option>
                   <option value="color bill">Color bill</option>
                   <option value="combined">Combined</option>
                   <option value="single">Single</option>
                   <option value="manual">Manual</option>
                   <option value="others">Others</option>
                 </select>
            </div>
        </div>
        <div class="control-group">
          <label class="control-label">Contract Start Date:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="contract_start_date" id="contract_start_date" placeholder="Contract start date" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Contract End Date:</label>
          <div class="controls">
               <input type="text" class="form-control required" name="contract_end_date" id="contract_end_date" placeholder="Contract end date" />
          </div>
        </div>
        <div class="control-group">
					<label class="control-label">Billing Cycle:</label>
					<div class="controls">
             <?php
                $query = "SELECT * FROM billing_cycle";
                $result = mysqli_query($dbc,$query);
                if(mysqli_num_rows($result)>0){
                    echo '<select name="billing_cycle" id="billing_cycle" class="form-control required" onchange="asset_change();">';
	                  echo '<option value="" selected="selected">Select Billing Cycle</option>';
                    while($row=mysqli_fetch_array($result)){
                        echo '<option value="'.$row['billing_cycle_id'].'">'.$row['start_date'].' to: '.$row['end_date'].'</option>';
                    }
                    echo '</select>';
                }
            ?>
					</div>
				</div>
				<div class="control-group row-fluid" style="padding-bottom: 10px;">
          <div class="span4 text-right">
					   <button type="submit" class="btn btn-success" onclick="addContractDetails();">Create Contract</button>
          </div>
          <div class="span2">
             <button type="button" class="btn btn-danger" data-target="#add_assets_modal" data-toggle="modal">Add Assets</button>
          </div>
          <div class="span3">
             <button type="button" class="btn btn-primary" onclick="window.location = 'contract_view.php';">View Contracts</button>
          </div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end-main-container-part-->


<!-- add assets modal -->
<div class="modal fade large" id="add_assets_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Add Assets</h4>

            </div>
            <div class="modal-body">
              <form class="" name="addasset_form" id="addasset_form" action="#" method="post" onsubmit="return false">
                <div class="control-group">
                  <label class="control-label">Search by Asset ID/Old Code:</label>
                  <div class="controls">
                       <input type="text" class="form-control required" id="search_val" name="search_val" id="contract_end_date" placeholder="Search..." />
                  </div>
                  <div class="controls">
                    <button type="submit" class="btn btn-primary" name="search" onclick="searchAndAddAsset()">Add Asset</button>
                  </div>
                </div>
              </form>
              <div class="clearfix"></div>
              <div class="controls" id="asset_table_div">

              </div>
            </div>
            <div class="modal-footer">
            	<span class="align-left" id="result_msg"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary save">Save</button> -->
            </div>
        </div>
    </div>
</div>
<!-- add assets modal ends -->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here
$(document).ready(function(){
    $('#addasset_form').validate();
    $('#li_sc_ticketmenu').addClass('active open');
    $('#contract_start_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $('#contract_end_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function addContractDetails(){
    if($('#addcontract_form').valid()){
      if(gAssetList.length == 0){
        bootbox.alert('No assets added.');
      } else {
        var data = $('#addcontract_form').serialize() + '&asset_list=' + JSON.stringify(gAssetList) + '&action=add_contract';
        $.ajax({
            url: "contract_services.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "CONTRACTADDED"){
                    bootbox.alert(result.message);
                    $('#addcontract_form')[0].reset();
                    makeAssetTableEmpty();
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}
        });
      }
    }
}

function searchAndAddAsset(){
  if($('#addasset_form').valid()){
    var data = $('#addasset_form').serialize() + '&action=search_asset';
    $.ajax({
        url: "contract_services.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            if(result.infocode == "ASSETDATAFOUND"){
                //bootbox.alert(result.data);
                addAsset(JSON.parse(result.data));
                $('#addasset_form')[0].reset();
            }else{
                bootbox.alert(result.message);
            }
        },
        error: function(){}
    });
  }
}

var gAssetList = new Array;

function addAsset(asset){
		var assetItem = {};
		var assetDetail = new Array;
		assetItem.itemID = asset.item_master_id;
		assetItem.itemName = asset.item_name;
    assetItem.assetID = asset.asset_master_id;
		gAssetList.push(assetItem);
		displayAssets();
}

function displayAssets(){
  var count = 0;
  var output = '';
  output += '<table id="asset_list" class="table table-striped table-bordered" cellspacing="0" width="100%">';
  output += '<thead>';
    output += '<tr>';
      output += '<th>S. No.</th>';
      output += '<th>Asset ID</th>';
      output += '<th>Item ID</th>';
      output += '<th>Item Name</th>';
      output += '<th>Delete</th>';
    output += '</tr>';
  output += '</thead>';
  output += '<tbody>';
  for (asset in gAssetList){
    count++;
    output += '<tr>';
      output += '<td>' + count + '</td>';
      output += '<td>' + gAssetList[asset].assetID + '</td>';
      output += '<td>' + gAssetList[asset].itemID + '</td>';
      output += '<td>' + gAssetList[asset].itemName + '</td>';
      output += '<td><button style="color: white;" class="btn btn-danger" onclick="deleteAssetList('+ (count-1) +')">&times;</button></td>';
    output += '</tr>';
    output
  }
  output += '</tbody>';
  output += '</table>';
  $('#asset_table_div').html(output);
}

function deleteAssetList(index){
  gAssetList.splice(index,1);
  displayAssets();
}

function makeAssetTableEmpty(){
  gAssetList = new Array;
  $('#asset_table_div').html('');
}

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>
