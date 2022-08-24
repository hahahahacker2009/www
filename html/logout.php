<?php
	$charset = "iso-8859-1";
	$page_title = "Dang xuat";
	include("{$_SERVER['DOCUMENT_ROOT']}/include/header.html");
?>

<!-- BAT DAU NOI DUNG TRANG -->

<h1>Dang xuat</h1>
<?php
	if (isset($_SESSION['loggedin'])) {
		$_SESSION = array();
		session_destroy();
		echo '<h2>Ban da dang xuat. Bam vao <a href="/">day</a> de ve trang chu.</h2>';
	} else {
		header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
		exit();
	}
?>
<!-- KET THUC NOI DUNG TRANG -->

<?php
include("{$_SERVER['DOCUMENT_ROOT']}/include/footer.html");
?>

