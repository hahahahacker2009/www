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
	if (isset($_SESSION['loggedin'])) {
		if (isset($_SESSION['role'])) {
			if (isset($_SESSION['username'])) {
				echo "<p>Ban dang dang nhap voi ten nguoi dung la {$_SESSION['username']}, vai tro quan tri cua ban la {$_SESSION['role']}</p>";
			}
		} else {
			echo "<p> Tai khoan cua ban khong co vai tro quan tri! </p>";
		}
	} else {
		header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
		exit();
	}
?>


<!-- KET THUC NOI DUNG TRANG -->
<?php
include("./include/footer.html");
?>
