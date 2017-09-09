<?php

session_start();

$rolename = $_SESSION['rolename'];

if($rolename == 'client'){
	header('Location: client/create_ticket.php');
}else if($rolename == 'sc'){
	header('Location: service_coordinator/create_ticket.php');
}else if($rolename == 'engr'){
	header('Location: engineer/view_ticket.php');
}else if($rolename == 'admin'){
	header('Location: master/asset_master.php');
}else if($rolename == 'op'){
	header('Location: operator/register_inbound_call.php');
}else if($rolename == 'store'){
	header('Location: store/stock_list.php');
} else if($rolename == 'billing'){
	header('Location: billing/contract_master.php');
} else if($rolename == 'despatch'){
	header('Location: despatch/despatch_request_view.php');
} else if($rolename == 'hr'){
	header('Location: hr/employee_master.php');
} else if($rolename == 'sh'){
	header('Location: service_head/process_ticket.php');
}
