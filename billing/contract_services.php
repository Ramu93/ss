<?php
  require('..'.DIRECTORY_SEPARATOR.'dbconfig.php');

  $finaloutput = array();
  if(!$_POST) {
    $action = $_GET['action'];
  }
  else {
    $action = $_POST['action'];
  }
  switch($action){
    case 'add_contract':
          $finaloutput = addContract();
    break;
    case 'search_asset':
          $finaloutput = searchAssetData();
    break;
    case 'update_contract':
          $finaloutput = updateContract();
    break;
    default:
          $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
  }

  echo json_encode($finaloutput);

  function searchAssetData(){
    global $dbc;
    $searchVal = mysqli_real_escape_string($dbc,trim($_POST['search_val']));

    $searchQuery = "SELECT am.asset_master_id, im.item_master_id, im.item_name FROM asset_master am, item_master im WHERE (asset_master_id='$searchVal' OR old_code='$searchVal') AND im.item_master_id=am.item_master_id";
    $result = mysqli_query($dbc, $searchQuery);
    if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_assoc($result);
      return array('infocode'=>'ASSETDATAFOUND', 'data'=>json_encode($row));
    } else {
      return array('infocode'=>'ASSETDATANOTFOUND', 'message'=>'Searched asset not available!');
    }

  }

  function addContract(){
    global $dbc;
    $partyMasterID = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
    $billType = mysqli_real_escape_string($dbc,trim($_POST['bill_type']));
    $contractStartDate = mysqli_real_escape_string($dbc,trim($_POST['contract_start_date']));
    $contractEndDate = mysqli_real_escape_string($dbc,trim($_POST['contract_end_date']));
    $billingCycleID = mysqli_real_escape_string($dbc,trim($_POST['billing_cycle']));
    $assetList = json_decode($_POST['asset_list']);
    //file_put_contents("contractservice.log", "\n".print_r($assetList[0]->itemName, true), FILE_APPEND | LOCK_EX);

    $contractMasterQuery = "INSERT INTO  contract_master (bill_type, party_master_id, billing_cycle_id, contract_start_date, contract_end_date) VALUES ('$billType','$partyMasterID', '$billingCycleID', '$contractStartDate', '$contractEndDate')";
  	if(mysqli_query($dbc,$contractMasterQuery)){
      $contractLastID = mysqli_insert_id($dbc);
      //bind contract and assets
      for($index = 0; $index < count($assetList); $index++){
        $assetQuery = "INSERT INTO contract_assets (asset_master_id, contract_master_id) VALUES ('".$assetList[$index]->assetID."', '$contractLastID')";
        mysqli_query($dbc, $assetQuery);
      }
  		$output = array("infocode" => "CONTRACTADDED", "message" => "Contract added succesfully.");
  	}
  	else {
  		$output = array("infocode" => "ADDCONTRACTFAILED", "message" => "Unable to add Contract, please try again!");
  	}
    // return array("infocode" => "CONTRACTADDED", "message" => "Contract added succesfully.");
  	return $output;
  }

  function updateContract(){
    global $dbc;
    $partyMasterID = mysqli_real_escape_string($dbc,trim($_POST['party_master_id']));
    $billType = mysqli_real_escape_string($dbc,trim($_POST['bill_type']));
    $contractStartDate = mysqli_real_escape_string($dbc,trim($_POST['contract_start_date']));
    $contractEndDate = mysqli_real_escape_string($dbc,trim($_POST['contract_end_date']));
    $billingCycleID = mysqli_real_escape_string($dbc,trim($_POST['billing_cycle']));
    $assetList = json_decode($_POST['asset_list']);
    $contractMasterID = mysqli_real_escape_string($dbc,trim($_POST['contract_master_id']));
    //file_put_contents("contractservice.log", "\n".print_r($assetList[0]->itemName, true), FILE_APPEND | LOCK_EX);

    $contractMasterQuery = "UPDATE contract_master SET party_master_id='$partyMasterID', bill_type='$billType', contract_start_date='$contractStartDate', contract_end_date='$contractEndDate', billing_cycle_id='$billingCycleID' WHERE contract_master_id='$contractMasterID'";
  	if(mysqli_query($dbc,$contractMasterQuery)){
  		$output = array("infocode" => "CONTRACTUPDATED", "message" => "Contract updated succesfully.");
  	}
  	else {
  		$output = array("infocode" => "UPDATECONTRACTFAILED", "message" => "Unable to update Contract, please try again!");
  	}
    // return array("infocode" => "CONTRACTADDED", "message" => "Contract added succesfully.");
  	return $output;
  }

?>
