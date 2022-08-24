<?php

function logaccess() {
	$msg = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['SCRIPT_NAME']} {$_SERVER['SERVER_PROTOCOL']}
REMOTE_ADDR: {$_SERVER['REMOTE_ADDR']}
HTTP_USER_AGENT: {$_SERVER['HTTP_USER_AGENT']}
Referer: {$_SERVER['HTTP_REFERER']}
\n";
	$file = fopen("{$_SERVER['DOCUMENT_ROOT']}/../access.log", "a");
	fwrite($file, $msg);
	fclose($file);
}

function user_logaccess() {
	if (isset($_SESSION['loggedin'])) {
		$msg = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['SCRIPT_NAME']} {$_SERVER['SERVER_PROTOCOL']}
	REMOTE_ADDR: {$_SERVER['REMOTE_ADDR']}
	HTTP_USER_AGENT: {$_SERVER['HTTP_USER_AGENT']}
	Referer: {$_SERVER['HTTP_REFERER']}
	{$_SESSION['disp_name']} ({$_SESSION['username']})
	\n";
		$file = fopen("{$_SERVER['DOCUMENT_ROOT']}/../user_access.log", "a");
		fwrite($file, $msg);
		fclose($file);
	}
}

logaccess();
user_logaccess();

?>