<?php
$charset = "utf-8";
$page_title = "Trang chủ";
include("{$_SERVER['DOCUMENT_ROOT']}/include/header.html");
?>
<!-- BAT DAU NOI DUNG TRANG -->
<h1>Trang chủ website</h1>
<h2>Chào mừng bạn đến với trang chủ của website!</h2>

<p>Cùng thiết kế website động với Apache, PHP và MariaDB!</p>
<?php
	require_once("{$_SERVER['DOCUMENT_ROOT']}/include/config.php");
	if (isset($_GET['action'])) {
		if ($_GET['action'] == "registered") {
			if (isset($_GET['name'])) {
				echo "<br /><h3>Chào mừng thành viên mới vừa đăng ký {$_GET['name']}</h3>";
			}
		}
	}

	if (isset($_SESSION['username'])) {
		echo "<p>Bạn hiện đang đăng nhập với tên người dùng là {$_SESSION['username']}</p>";
	}
?>


<!-- KET THUC NOI DUNG TRANG -->
<?php
include("{$_SERVER['DOCUMENT_ROOT']}/include/footer.html");
?>
