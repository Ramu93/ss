<?php
session_start();
include '../commonmethods.php';
if (!isset($_SESSION['login'])) {
    header('Location: ' . HOMEPATH);exit();
}

include '..' . DIRECTORY_SEPARATOR . 'dbconfig.php';
include '..' . DIRECTORY_SEPARATOR . 'header.php';
include '..' . DIRECTORY_SEPARATOR . 'topbar.php';
include '..' . DIRECTORY_SEPARATOR . 'sidebar.php';

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
          <h5>Generate Bill</h5>
        </div>
        <div class="widget-content nopadding">
      <form id="addcontract_form" name="addcontract_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
        <div class="control-group">
          <label class="control-label">Billing Cycle:</label>
          <div class="controls">
             <?php
$query  = "SELECT * FROM billing_cycle";
$result = mysqli_query($dbc, $query);
if (mysqli_num_rows($result) > 0) {
    echo '<select name="billing_cycle" id="billing_cycle" class="form-control required" onchange="getCustomerData();">';
    echo '<option value="" selected="selected">Select Billing Cycle</option>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<option value="' . $row['billing_cycle_id'] . '">' . $row['start_date'] . ' to: ' . $row['end_date'] . '</option>';
    }
    echo '</select>';
}
?>
          </div>
        </div>
        <div class="control-group" id="customer_combo">

        </div>
        <div id="contract_master_detail_div" class="widget-box-90">

        </div>
        <p></p>
        <div id="additional_charges" class="widget-box-90">
          <table class="table" cellspacing="0" width="100%">
            <tbody>
              <tr>
                <td><label class="">Billed For:</label></td>
                <td><label class="">Billing Amount:</label></td>
                <td><label class="">Add/Sub:</label></td>
                <td></td>
              </tr>
              <tr>
                <td><input type="text" name="billed_for" id="billed_for"  placeholder="Billed for"></td>
                <td><input type="text" name="billing_amount" id="billing_amount"  placeholder="Billing amount"></td>
                <td>
                  <select name="add_sub_amount" id="add_sub_amount" onchange="">
                    <option selected="selected" value="add">+</option>
                    <option value="sub">-</option>
                  </select>
                </td>
                <td><button onclick="addAdditionalItem()" class="btn btn-success" >Add Item</button> &nbsp; <!-- <button id="clear_all_items_btn" onclick="makeAdditionalItemsTableEmpty()" class="btn btn-danger" >Clear All Items</button> --></td>
                <!-- <td></td> -->
              </tr>
            </tbody>
          </table>
        </div>
        <p></p>
        <div id="additional_items_list_div" class="widget-box-90" >

        </div>
        <p></p>
        <div id="asset_div" class="widget-box-90">

        </div>
        <div class="control-group row-fluid" style="padding-bottom: 10px;">
          <div id="total_amount_div">
            <label class="control-label">Total Amount:</label>
            <label class="control-label" id="total_amount"></label>
          </div>
          <div class="span4 text-right">
             <!-- <button type="submit" class="btn btn-success" id="generate_bill_btn" onclick="generateBill();">Generate Bill</button> -->
          </div>
          <div class="span3">
             <!-- <button type="button" class="btn btn-primary" onclick="window.location = 'contract_view.php';">View Contracts</button> -->
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!--end-main-container-part-->

<?php include '..' . DIRECTORY_SEPARATOR . 'footer_js.php';?>
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

    makeAdditionalItemsTableEmpty();
    $('#additional_charges').hide();
    $('#total_amount_div').hide();
});

$(window).bind("load", function() {
    //$('#sb_ul_mastermenu').addClass('in');
    //$('#sb_ul_mastermenu').css({height:auto});
});

function getCustomerData(){
  var billingCycleID = $('#billing_cycle').val();
  var data = 'billing_cycle_id=' + billingCycleID + '&action=get_customer_data';
  $.ajax({
    url : 'billing_services.php',
    data : data,
    dataType : 'JSON',
    type : 'POST',
    success : function(response){
      if(response.infocode == 'GETCUSTOMERDATASUCCESS'){
        var customerData = JSON.parse(response.data);
        displayCustomerCombo(customerData);
      } else {
        bootbox.alert(response.message);
      }
    },
    error : function(response){

    }
  });
}

function displayCustomerCombo(customerData){
  var output = '<label class="control-label">Customer Name:</label>';
  output += '<div class="controls">';
  output += '<select name="party_master_id" id="party_master_id" class="form-control required" onchange="getContractData();">';
  output += '<option value="" selected="selected">Select Customer</option>';
  for(customer in customerData){
    output += '<option value="'+ customerData[customer].party_master_id +'">'+ customerData[customer].customer_name +'</option>';
  }
  output += '</select>';
  output += '</div>';
  $('#customer_combo').html(output);
}



function getContractData(){
  //hide additional items list, total amount and asset data if shown previously
  hideElementAndResetTotalAmountOnCustomerChange();

  var billingCycleID = $('#billing_cycle').val();
  var partyMasterID = $('#party_master_id').val();
  var data = 'billing_cycle_id=' + billingCycleID + '&party_master_id=' + partyMasterID + '&action=get_contract_data';
  $.ajax({
    url: 'billing_services.php',
    type: 'POST',
    data: data,
    dataType: 'json',
    success: function(result){
      if(result.infocode == 'GETCONTRACTDATASUCCESS'){
        var contractData = JSON.parse(result.data);
        displayContractTable(contractData);
        $('#contract_master_detail_div').show();
      } else {
        bootbox.alert(result.message);
      }
    },
    error: function(result){
      bootbox.alert('Error!');
    }
  });
}

function displayContractTable(contractData){
  var count = 0;
  var output = '';
  output += '<table id="asset_list" class="table table-striped table-bordered" cellspacing="0" width="100%">';
  output += '<thead>';
    output += '<tr>';
      output += '<th>S. No.</th>';
      output += '<th>Contract ID</th>';
      output += '<th>Billing Type</th>';
      //output += '<th>Location</th>';
      output += '<th>View</th>';
    output += '</tr>';
  output += '</thead>';
  output += '<tbody>';
  for (contract in contractData){
    count++;
    output += '<tr>';
      output += '<td>' + count + '</td>';
      output += '<td>' + contractData[contract].contract_master_id + '</td>';
      output += '<td id="billing_type_value_td">' + contractData[contract].bill_type + '</td>';
      //output += '<td>' + contractData[contract].location + '</td>';
      output += '<td><button class="btn btn-primary" onclick="getAssetData(' + contractData[contract].contract_master_id + ')">Select</buton></td>';
    output += '</tr>';
  }
  output += '</tbody>';
  output += '</table>';
  $('#contract_master_detail_div').html(output);

  //show additional items list, total amount and asset data if hidden previously
  //$('#total_amount_div').show();
  $('#additional_items_list_div').show();
  $('#asset_div').show();
}

var gAssetDataObj = new Array;
var gAdditionalItemsList = new Array;
var gTotalAmount = 0;
var gPreviousAssetTotalAmount = 0;

function addAdditionalItem(){
  var item = {};
  var assetDetail = new Array;
  var billedFor = $('#billed_for').val();
  var billingAmount = $('#billing_amount').val();
  var addSubAmount = $('#add_sub_amount').val();
  if(billedFor != '' && billingAmount != ''){
    item.billed_for = billedFor;
    item.billing_amount = billingAmount;
    item.add_sub_amount = addSubAmount;
    gAdditionalItemsList.push(item);
    displayAdditionalItemsList();
    makeAdditionalItemsFieldsEmpty();
    $('#clear_all_items_btn').prop('disabled', false);
    //add amount to total
    if(addSubAmount == 'add'){
      gTotalAmount += parseInt(billingAmount);
    } else {
      gTotalAmount -= parseInt(billingAmount);
    }
    console.log(gTotalAmount);
    displayTotalAmount();
    $('#total_amount_div').show();
  }
}

function deleteAadditionalItemFromList(index){
  //subtract item amount from total
  if(gAdditionalItemsList[index].add_sub_amount == 'add'){
    gTotalAmount -= parseInt(gAdditionalItemsList[index].billing_amount);
  } else {
    gTotalAmount += parseInt(gAdditionalItemsList[index].billing_amount);
  }
  gAdditionalItemsList.splice(index,1);
  displayAdditionalItemsList();
  console.log(gTotalAmount);
  displayTotalAmount();
}

function makeAdditionalItemsTableEmpty(){
  gAdditionalItemsList = new Array;
  $('#additional_items_list_div').html('');
  $('#clear_all_items_btn').prop('disabled', true);
}

function makeAdditionalItemsFieldsEmpty(){
  $('#billed_for').val('');
  $('#billing_amount').val('');
  $('#add_sub_amount').val('add');
}

function displayAdditionalItemsList(){
  var count = 0;
  var output = '';
  var symbol = '+';
  output += '<table id="" class="table table-striped table-bordered" cellspacing="0" width="100%">';
  output += '<thead>';
    output += '<tr>';
      output += '<th>S. No.</th>';
      output += '<th>Billed For</th>';
      output += '<th>Add/Sub</th>';
      output += '<th>Billed Amount</th>';
      output += '<th>Delete</th>';
    output += '</tr>';
  output += '</thead>';
  output += '<tbody>';
  for (item in gAdditionalItemsList){
    count++;
    if(gAdditionalItemsList[item].add_sub_amount == 'sub'){
      symbol = '-';
    }
    output += '<tr>';
      output += '<td>' + count + '</td>';
      output += '<td>' + gAdditionalItemsList[item].billed_for + '</td>';
      output += '<td>' + symbol + '</td>';
      output += '<td>' + gAdditionalItemsList[item].billing_amount + '</td>';
      output += '<td><button style="color: white;" class="btn btn-danger" onclick="deleteAadditionalItemFromList('+ (count-1) +')">&times;</button></td>'
    output += '</tr>';
  }
  output += '</tbody>';
  output += '</table>';
  $('#additional_items_list_div').html(output);
}

function getAssetData(contractMasterID){
  //show additional item fields
  $('#additional_charges').show();
  //clear all items
  makeAdditionalItemsTableEmpty();

  var data = 'contract_master_id=' + contractMasterID + '&action=get_asset_data';
  $.ajax({
    url: 'billing_services.php',
    type: 'POST',
    data: data,
    dataType: 'json',
    success: function(response){
      if(response.infocode == 'GETASSETDATASUCCESS'){
        var assetData = JSON.parse(response.data);
        //alert(response.data);
        displayAssetData(assetData);
      } else {
        bootbox.alert(response.message);
      }
    },
    error: function(response){

    }
  });
}

function makeAssetObjectEmpty(){
  gAssetDataObj = new Array;
  $('#asset_div').html('');
}

function displayAssetData(assetData){
  //alert(JSON.stringify(assetData));
  var count = 0;
  var index = 0;
  var output = '';
  output += '<table id="asset_list" class="table table-striped" cellspacing="0" width="100%">';
  output += '<thead>';
    output += '<tr>';
      output += '<th>S. No.</th>';
      output += '<th>Item Name</th>';
      output += '<th>Opening Reading</th>';
      output += '<th>Closing Reading</th>';
      output += '<th>Amount</th>';
    output += '</tr>';
  output += '</thead>';
  output += '<tbody>';
  //billing_type_value_td
  for (asset in assetData){
    var assetDataObj = assetData[asset];
    count++;
    output += '<tr>';
      output += '<td>' + count + '</td>';
      output += '<td>' + assetDataObj.item_name + '</td>';
      output += '<td><input type="text" name="opening_reading_'+index+'" id="opening_reading_'+index+'" placeholder="Opening reading" onfocusout="computeAmount('+index+')" /></td>';
      output += '<td><input type="text" name="closing_reading_'+index+'" id="closing_reading_'+index+'" placeholder="Closing reading" onfocusout="computeAmount('+index+')" /></td>';
      output += '<td id="amount_'+index+'"></td>';
    output += '</tr>';
    index++;

    //set initial value of amount to 0
    assetDataObj.amount = 0;
    gAssetDataObj.push(assetDataObj);
  }
  output += '</tbody>';
  output += '</table>';
  $('#asset_div').html(output);
  // console.log('disp func');
  // console.log(JSON.stringify(gAssetDataObj));
}

function computeAmount(index){
  var asset = gAssetDataObj[index];
  var openingReading = $('#opening_reading_'+index).val();
  var closingReading = $('#closing_reading_'+index).val();
  if(openingReading != '' && closingReading != ''){
    //console.log(asset.count);
    var rent = asset.rent;
    var bwFreeCopies = parseInt(asset.bw_free_copies);
    var bwExtraCopyCharges = parseFloat(asset.bw_extra_copy_charges);
    var colorA4FreeCopies = parseInt(asset.color_a4_free_copies);
    var colorA4ExtraCopyCharges = parseFloat(asset.color_a4_extra_copy_charges);
    var colorA3FreeCopies = parseInt(asset.color_a3_free_copies);
    var colorA3ExtraCopyCharges = parseFloat(asset.color_a3_extra_copy_charges);
    var currentReading = closingReading - openingReading;
    var amount = rent;
    var extraCopiesTaken = currentReading - bwFreeCopies;
    if(extraCopiesTaken > 0){
      amount =  parseFloat(extraCopiesTaken * bwExtraCopyCharges) + parseFloat(amount);
    }

    $('#amount_'+index).html('Rs. '+ amount);
    //add this amount to gAssetDataObj array for total amount calculation
    gAssetDataObj[index].amount = amount;

    $('#amount_'+index).show();
    $('#total_amount_div').show();
    computeTotalAmount();
    displayTotalAmount();
  } else {
    $('#amount_'+index).hide();
  }
}

function computeTotalAmount(){
  var currentAssetTotalAmount = 0;
  //subtract previos asset total from global gTotalAmount
  //console.log();
  gTotalAmount -= gPreviousAssetTotalAmount;
  for(asset in gAssetDataObj){
    currentAssetTotalAmount += parseInt(gAssetDataObj[asset].amount);
  }
  gTotalAmount += currentAssetTotalAmount;

  //assigning current amount value to previous amount value to compute the lates amount from the asset list in case if the opening and closing readings are edited
  gPreviousAssetTotalAmount = currentAssetTotalAmount;
}

function displayTotalAmount(){
  $('#total_amount').html(gTotalAmount);
}

function hideElementAndResetTotalAmountOnCustomerChange(){
  $('#total_amount_div').hide();
  $('#additional_items_list_div').hide();
  $('#additional_charges').hide();
  $('#asset_div').hide();
  makeAdditionalItemsTableEmpty();
  makeAssetObjectEmpty();
  gTotalAmount = 0;
}

</script>
<?php include '..' . DIRECTORY_SEPARATOR . 'footer.php';?>
