<?php
$charset = "iso-8859-1";
$page_title = "Intranet - Trang chu";
include("./include/header.html");
?>
<!-- BAT DAU NOI DUNG TRANG -->
<h1>Trang chu quan tri</h1>
<h2>Chao mung ban den voi trang chu quan tri cua website!</h2>
<p>Quan tri website dong cua ban</p>
<?php
	//require_once("{$_SERVER['DOCUMENT_ROOT']}/include/config.php");
	require_once("./auth.php");

	phpinfo();
?>


<!-- KET THUC NOI DUNG TRANG -->
<?php
include("./include/footer.html");
?>
