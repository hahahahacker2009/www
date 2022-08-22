<?php
$charset = "iso-8859-1";
$page_title = "Intranet - Trang chu";
include("./include/header.html");
?>
<!-- BAT DAU NOI DUNG TRANG -->
<h1>Trang chu</h1>
<h2>Chao mung ban den voi trang chu cua website!</h2>
<p>Cung thiet ke website dong bang Apache, PHP va MariaDB!</p>
<?php
	if (isset($_GET['action'])) {
		if ($_GET['action'] == "registered") {
			if (isset($_GET['name'])) {
				echo "<br /><h3>Chao mung thanh vien moi vua dang ki {$_GET['name']}</h3>";
			}
		}
	}

	if (isset($_SESSION['username'])) {
		echo "<p>Ban dang dang nhap voi ten nguoi dung la {$_SESSION['username']}</p>";
	}

?>


<!-- KET THUC NOI DUNG TRANG -->
<?php
include("./include/footer.html");
?>
