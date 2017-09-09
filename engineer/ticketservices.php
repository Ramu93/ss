<?php
session_start();
include '../commonmethods.php';
if (!isset($_SESSION['login'])) {
    header('Location: ' . HOMEPATH);exit();
}

require '..' . DIRECTORY_SEPARATOR . 'dbconfig.php';
define('TICKET_CREATE_STATUS', 'created');
define('TICKET_ASSIGN_STATUS', 'assigned');
define('TICKET_ENGG_CLOSE_STATUS', 'engineerclosed');
define('TICKET_SC_CLOSE_STATUS', 'closed');
$finaloutput = array();
if (!$_POST) {
    $action = $_GET['action'];
} else {
    $action = $_POST['action'];
}
switch ($action) {
    case 'loaddata_assigned':
        $finaloutput = loaddata_assigned();
        break;
    case 'loaddata_engineerclosed':
        $finaloutput = loaddata_engineerclosed();
        break;
    case 'request_spare':
        $finaloutput = request_spare();
        break;
    case 'close_ticket':
        $finaloutput = close_ticket();
        break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function loaddata_assigned()
{
    global $dbc;
    $ticket_id   = mysqli_real_escape_string($dbc, trim($_POST['ticket_id']));
    $engineer_id = $engineer_name = '';
    $spare_data  = array();
    /*$query = "SELECT a.employee_master_id, a.employee_name, b.visit_date FROM employee_master a, raise_ticket b WHERE b.ticket_id = $ticket_id AND a.employee_master_id = b.engineer_id";
    $result = mysqli_query($dbc,$query);
    if(mysqli_num_rows($result)>0){
    $row  = mysqli_fetch_assoc($result);
    $engineer_data = $row;
    }*/

    $query2 = "SELECT  a.item_name, b.quantity, b.raised_by, b.spare_remark, b.spare_status
			FROM item_master a, spare_request b
			WHERE b.ticket_id = $ticket_id AND a.item_master_id = b.item_master_id";
    $result2 = mysqli_query($dbc, $query2);
    if (mysqli_num_rows($result2) > 0) {
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $spare_data[] = $row2;
        }
    }

    $output = array("infocode" => "DATALOADED", "message" => "Assigned Data loaded succesfully", "spare_data" => $spare_data);

    return $output;
}

function loaddata_engineerclosed()
{
    global $dbc;
    $ticket_id  = mysqli_real_escape_string($dbc, trim($_POST['ticket_id']));
    $spare_data = $call_report = array();

    $query  = "SELECT * FROM call_report WHERE ticket_id = $ticket_id ";
    $result = mysqli_query($dbc, $query);
    if (mysqli_num_rows($result) > 0) {
        $row         = mysqli_fetch_assoc($result);
        $call_report = $row;
    }

    $query2 = "SELECT  a.item_name, b.quantity, b.raised_by, b.spare_remark, b.spare_status
			FROM item_master a, spare_request b
			WHERE b.ticket_id = $ticket_id AND a.item_master_id = b.item_master_id";
    $result2 = mysqli_query($dbc, $query2);
    if (mysqli_num_rows($result2) > 0) {
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $spare_data[] = $row2;
        }
    }

    $output = array("infocode" => "DATALOADED", "message" => "Assigned Data loaded succesfully", "spare_data" => $spare_data, "call_report" => $call_report);

    return $output;
}

function request_spare()
{
    global $dbc;

    $ticket_id = mysqli_real_escape_string($dbc, trim($_POST['ticket_id']));
    $raised_by = 'Engineer';
    $engr_id   = $_SESSION['userid'];
    //$spare_status = 'StoresPending';
    $errcount = 0;
    foreach ($_POST['spare_name'] as $key => $value) {
        $spare_id     = mysqli_real_escape_string($dbc, trim($value));
        $qty          = mysqli_real_escape_string($dbc, trim($_POST['quantity'][$key]));
        $spare_status = mysqli_real_escape_string($dbc, trim($_POST['spare_status'][$key]));

        $query = "INSERT INTO spare_request (ticket_id, item_master_id, quantity, spare_status, engineer_id, raised_by)
		VALUES ($ticket_id, $spare_id, $qty, '$spare_status', $engr_id, '$raised_by')";
        if (!mysqli_query($dbc, $query)) {
            $errcount++;
            file_put_contents("spareerr.log", "\nError while requesting spare for $ticket_id", FILE_APPEND | LOCK_EX);
        }
    }
    if ($errcount) {
        $output = array("infocode" => "SPAREREQERROR", "message" => "Error while requesting spares, please try again");
    } else {
        $output = array("infocode" => "SPAREREQSUCCESS", "message" => "Spare Request raised succesfully");
    }

    return $output;
}

function close_ticket()
{
    global $dbc;
    $ticket_id       = mysqli_real_escape_string($dbc, trim($_POST['ticket_id']));
    $engr_id         = $_SESSION['userid'];
    $ticket_status   = mysqli_real_escape_string($dbc, trim($_POST['ticket_status']));
    $opening_reading = mysqli_real_escape_string($dbc, trim($_POST['opening_reading']));
    $closing_reading = mysqli_real_escape_string($dbc, trim($_POST['closing_reading']));
    $visit_date      = mysqli_real_escape_string($dbc, trim($_POST['visit_date']));
    $start_time      = mysqli_real_escape_string($dbc, trim($_POST['start_time']));
    $end_time        = mysqli_real_escape_string($dbc, trim($_POST['end_time']));
    $remarks         = mysqli_real_escape_string($dbc, trim($_POST['remarks']));

    $query = "UPDATE  raise_ticket SET ticket_status = '$ticket_status' WHERE ticket_id = $ticket_id";
    if (mysqli_query($dbc, $query)) {
        $query2 = "INSERT INTO call_report (ticket_id, engineer_id, opening_reading, closing_reading, visit_date, start_time, end_time, remarks)
		VALUES ($ticket_id, $engr_id, $opening_reading, $closing_reading, '$visit_date', '$start_time', '$end_time', '$remarks')";
        if (mysqli_query($dbc, $query2)) {
            $output = array("infocode" => "TICKETUPDATED", "message" => "Ticket Closed succesfully");
        } else {
            $output = array("infocode" => "TICKETUPDATEDCALLREPORTERR", "message" => "Ticket Closed succesfully, but error occurred while saving call report");
            file_put_contents("callreporterr.log", "\nError while updating call report for $ticket_id", FILE_APPEND | LOCK_EX);
        }
    } else {
        $output = array("infocode" => "UPDATETICKETFAILED", "message" => "Unable to Close Ticket, please try again!");
    }

    return $output;
}
