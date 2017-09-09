<?php
//session_start();
header('Access-Control-Allow-Origin: *');
require("dbconfig.php");
//format : action=device_login&username=12312&password=123&deviceid=123123
$timelimit = 7200; //2 hours

//$input = isset($_POST)?$_POST:$_GET;
$input = $_GET;
$finaloutput = array();
$action = isset($input['action'])?$input['action']:'';
switch($action){
	case 'user_login':
        $finaloutput = user_login();
    break;
    case 'device_logout':
        $finaloutput = device_logout();
    break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);


function user_login(){
    global $dbc,$input;
    $output = array();
    $query = "SELECT * FROM login WHERE loginid = '{$input['userid']}' AND password = '".md5($input['password'])."'";
    $result = mysqli_query($dbc, $query);
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        $output = array('infocode' => 'LOGINSUCCESS', 'message' => 'Login success','customer_id' => $row['userid'],'userid' => $input['userid']);
    }else{
        $output=array('infocode' => 'INVALIDLOGIN', 'message' => 'Invalid User ID/Password');
    }
    return $output;
}

function writeerrorlog($text){
    $filename = "siteerrlog.log";
    $fp =fopen($filename,"a");
    fwrite($fp, $text);
    fclose($fp);
}

?>