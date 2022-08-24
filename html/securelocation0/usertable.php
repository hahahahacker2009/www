<?php
$charset = "iso-8859-1";
$page_title = "Quan li nguoi dung";
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
			die("<p> Tai khoan cua ban khong co vai tro quan tri! </p>");

		}
	} else {
		header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
		exit();
	}

	require_once("../../mysqli_connect.php");
	$query = "SELECT disp_name, username, email, registration_date FROM user ORDER BY registration_date DESC";
	$result = @mysqli_query($dbc, $query);
	$num = mysqli_num_rows($result);

	if ($num > 0) {
		echo "<h3>Hien co $num nguoi dung da dang ki</h3>";
		echo '<table align="center" cellspacing="2" cellpadding="2">
			<tr>
				<td width="10%">&nbsp</td>
				<td width="13%" align="left"><b>Ten</b></td>
				<td width="13%" align="left"><b>Email</b></td>
				<td width="13%" align="left"><b>Ngay dang ky</b></td>
			</tr>
			';
		while ($row = mysqli_fetch_row($result)) {
			$name = escape_data_out("{$row[0]} ({$row[1]})");
			echo "
			<tr>
				<td width=\"10%\">&nbsp</td>
				<td width=\"13%\" align=\"left\">$name</td>
				<td width=\"13%\" align=\"left\">$row[2]</td>
				<td width=\"13%\" align=\"left\">$row[3]</td>
			</tr>
			\n";
		}
		echo '</table>';
		mysqli_free_result($result);
	} else {
		echo '<p>Hien chua co nguoi dung nao dang ky.</p>';
	}
	mysqli_close($dbc);
?>


<!-- KET THUC NOI DUNG TRANG -->
<?php
include("./include/footer.html");
?>
