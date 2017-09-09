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
    case 'get_contract_data':
      $finaloutput = getContractData();
    break;
    case 'get_customer_data':
      $finaloutput = getCustomerData();
    break;
    case 'get_asset_data':
      $finaloutput = getAssetData();
    break;
    default:
          $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
  }

  echo json_encode($finaloutput);

  function getCustomerData(){
    global $dbc;
    $billingCycleID = mysqli_real_escape_string($dbc, $_POST['billing_cycle_id']);
    $customerQuery = "SELECT DISTINCT pm.party_master_id, pm.customer_name FROM contract_master cm, party_master pm WHERE cm.party_master_id=pm.party_master_id AND cm.billing_cycle_id=".$billingCycleID;
    $customerResult = mysqli_query($dbc, $customerQuery);
    if(mysqli_num_rows($customerResult)>0){
      $out = array();
      while($row = mysqli_fetch_assoc($customerResult)){
        $out[] = $row;
      }
      return array('infocode'=>'GETCUSTOMERDATASUCCESS','data'=>json_encode($out)); 
    } else {
      return array('infocode'=>'GETCUSTOMERDATAFAILURE','message'=>'No customer data available for selected billing cycle.');
    }
  }

  function getContractData(){
    global $dbc;
    $billingCycleID = mysqli_real_escape_string($dbc, $_POST['billing_cycle_id']);
    $partyMasterID = mysqli_real_escape_string($dbc, $_POST['party_master_id']);
    $contractQuery = "SELECT cm.contract_master_id, pm.customer_name, cm.bill_type FROM contract_master cm, party_master pm WHERE cm.party_master_id=pm.party_master_id AND cm.party_master_id=".$partyMasterID." AND billing_cycle_id=".$billingCycleID;
    //$contractQuery = "SELECT cm.contract_master_id, pm.customer_name, cm.bill_type, pl.location_name FROM contract_master cm, party_master pm, party_location pl WHERE cm.party_master_id=pm.party_master_id AND pm.party_master_id=pl.party_master_id AND cm.party_master_id=".$partyMasterID." AND billing_cycle_id=".$billingCycleID;
    $contractResult = mysqli_query($dbc, $contractQuery);
    if(mysqli_num_rows($contractResult)>0){
      $out = array();
      while ($row = mysqli_fetch_assoc($contractResult)) {
        $out[] = $row;
      }
      return array('infocode'=>'GETCONTRACTDATASUCCESS','data'=>json_encode($out));
      //file_put_contents("contract.log", "\n".print_r($out, true), FILE_APPEND | LOCK_EX);
    } else {
      return array('infocode'=>'GETCONTRACTDATAFAILURE','message'=>'No contract data available for selected customer.');
    }
  }

  function getAssetData(){
    global $dbc;
    $contractMasterID = mysqli_real_escape_string($dbc, $_POST['contract_master_id']);
    $assetQuery = "SELECT am.asset_master_id, im.item_name, am.item_master_id, am.rent, am.bw_free_copies, am.bw_extra_copy_charges, am.color_a4_free_copies, am.color_a4_extra_copy_charges, am.color_a3_free_copies, am.color_a3_extra_copy_charges FROM contract_assets ca, asset_master am, item_master im WHERE am.asset_master_id=ca.asset_master_id AND im.item_master_id=am.item_master_id AND ca.contract_master_id=".$contractMasterID;
    $assetResult = mysqli_query($dbc, $assetQuery);
    if(mysqli_num_rows($assetResult)>0){
      $out = array();
      while($row = mysqli_fetch_assoc($assetResult)){
        $out[] = $row;
      }
      return array('infocode'=>'GETASSETDATASUCCESS','data'=>json_encode($out));
    } else {
      return array('infocode'=>'GETASSETDATAFAILURE','message'=>'No asset data available for selected customer.');
    }
  }

?>
