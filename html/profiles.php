<?php
	$charset = "iso-8859-1";
	$page_title = "Tai khoan nguoi dung";
	include("./include/header.html");
?>

<!-- BAT DAU NOI DUNG TRANG -->

<h1>Ho so nguoi dung</h1>
<?php
	if (isset($_GET['username'])) {
		require_once("../mysqli_connect.php");
		$username = escape_data_in($_GET['username']);
		$query = "SELECT disp_name, username, email, registration_date FROM user WHERE username='{$username}'";
		echo "Cau truy van se duoc thuc hien: $query";
		echo "<br /><br /><br /><br />";
		$result = @mysqli_query($dbc, $query);
		$assoc = mysqli_fetch_assoc($result);

		if ($assoc) {
			echo "<h2>{$assoc['disp_name']} ({$assoc['username']})</h2>";
			echo "<h3>Ngay dang ki: {$assoc['registration_date']} (YYYY-MM-DD)</h3>";
			echo "<h4>Email nguoi dung: {$assoc['email']}</h4>";
		} else {
			echo "<h2 style=\"color: red;\">Khong tim thay nguoi dung!</h2>";
		}
	} else {
		echo "<h3 style=\"color: red;\">Ten nguoi dung chua duoc chi dinh!</h3>";
	}
?>

<!-- KET THUC NOI DUNG TRANG -->

<form name="searchuser" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
<input type="text" name="username" placeholder="Tim kiem bang ten nguoi dung" size="40" maxlength="30" />
<input type="submit" name="search" value="Tim kiem" />
</form>

<?php
	include("./include/footer.html");
?>

