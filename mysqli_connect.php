<?php
DEFINE ('DB_ID', 'apache2');
DEFINE ('DB_PW', 'php8apache2_4.dll');
DEFINE ('DB_HOST', '127.0.0.1');
DEFINE ('DB_NAME', 'apache2');

$dbc = mysqli_connect(DB_HOST, DB_ID, DB_PW) OR die("Khong the thiet lap ket noi den co so du lieu!");
mysqli_select_db($dbc, DB_NAME) OR die("Khong the chon co so du lieu!");

function escape_data($data, $trim=TRUE) {
	global $dbc;
	if (ini_get('magic_quotes_gpc')) {
		$data = stripslashes($data);
	}

	if ($trim=FALSE) {
		return mysqli_real_escape_string($dbc, htmlspecialchars(strip_tags($data)));
	} else 

	return mysqli_real_escape_string($dbc, trim(htmlspecialchars(strip_tags($data))));
}

?>

