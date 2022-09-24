<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/include/logging.php");

/*
function logaccess() {
	if (isset($_SERVER['HTTP_REFERER'])) {
		$msg = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['SCRIPT_NAME']} {$_SERVER['SERVER_PROTOCOL']}
	REMOTE_ADDR: {$_SERVER['REMOTE_ADDR']}
	HTTP_USER_AGENT: {$_SERVER['HTTP_USER_AGENT']}
	Referer: {$_SERVER['HTTP_REFERER']}
	\n";
	} else {
		$msg = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['SCRIPT_NAME']} {$_SERVER['SERVER_PROTOCOL']}
	REMOTE_ADDR: {$_SERVER['REMOTE_ADDR']}
	HTTP_USER_AGENT: {$_SERVER['HTTP_USER_AGENT']}
	\n";
	}

	$file = fopen("{$_SERVER['DOCUMENT_ROOT']}/../access.log", "a");
	fwrite($file, $msg);
	fclose($file);
}

function user_logaccess() {
	if (isset($_SERVER['HTTP_REFERER'])) {
		$msg = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['SCRIPT_NAME']} {$_SERVER['SERVER_PROTOCOL']}
	REMOTE_ADDR: {$_SERVER['REMOTE_ADDR']}
	HTTP_USER_AGENT: {$_SERVER['HTTP_USER_AGENT']}
	Referer: {$_SERVER['HTTP_REFERER']}
	{$_SESSION['disp_name']} ({$_SESSION['username']})
	\n";
	} else {
		$msg = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['SCRIPT_NAME']} {$_SERVER['SERVER_PROTOCOL']}
	REMOTE_ADDR: {$_SERVER['REMOTE_ADDR']}
	HTTP_USER_AGENT: {$_SERVER['HTTP_USER_AGENT']}
	{$_SESSION['disp_name']} ({$_SESSION['username']})
	\n";
	}

	$file = fopen("{$_SERVER['DOCUMENT_ROOT']}/../user_access.log", "a");
	fwrite($file, $msg);
	fclose($file);
}


function login_log($status, $login) {
	$curtime = date("Y-m-d H:i:s",time());

	switch ($status) {
	case TRUE:
		$msg = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['SCRIPT_NAME']} {$_SERVER['SERVER_PROTOCOL']}
	REMOTE_ADDR: {$_SERVER['REMOTE_ADDR']}
	HTTP_USER_AGENT: {$_SERVER['HTTP_USER_AGENT']}
	Login: $login
	Status: SUCCESS
	Time: $curtime
	\n";
		break;

	case FALSE:
		$msg = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['SCRIPT_NAME']} {$_SERVER['SERVER_PROTOCOL']}
	REMOTE_ADDR: {$_SERVER['REMOTE_ADDR']}
	HTTP_USER_AGENT: {$_SERVER['HTTP_USER_AGENT']}
	Login: $login
	Status: FAILED
	Time: $curtime
	\n";
		break;
	}

	$file = fopen("{$_SERVER['DOCUMENT_ROOT']}/../user_login.log", "a");
	fwrite($file, $msg);
	fclose($file);
}
*/

logaccess();
if (isset($_SESSION['loggedin'])) {
	user_logaccess();
}

?>
