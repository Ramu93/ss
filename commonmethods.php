<?php
$host = $_SERVER['HTTP_HOST'];
//define('HOMEPATH','http://'.$host.'/samanapern/sbbs/');
define('HOMEPATH','http://localhost:8888/ss/');
define('DOCPATH',$_SERVER['DOCUMENT_ROOT'].'/ss/');
function hasPermission($rolename){
	$roles = json_decode($_SESSION['role_permissions']);
	$rolesplit = explode('__', $rolename);
	if(isset($roles->$rolesplit[0]) && isset($roles->$rolesplit[0]->$rolesplit[1]) && $roles->$rolesplit[0]->$rolesplit[1]=='yes'){
		return true;
	}
	return false;
}

function hasMenu($menuname){
		$roles = json_decode($_SESSION['role_permissions']);
		if(isset($roles->$menuname)){
			return true;
		}
		return false;
	}
?>
