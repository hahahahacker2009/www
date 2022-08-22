<?php
	$charset = "iso-8859-1";
        $page_title = "Intranet - Trang chu";
	include("./include/header.html");
?>

<!-- BAT DAU NOI DUNG TRANG -->

<h1>Cai dat nguoi dung</h1>

<?php
	if (isset($_SESSION['loggedin'])) {
		require_once("../mysqli_connect.php");
		$query = "SELECT disp_name, username, email, registration_date FROM user WHERE username='{$_SESSION['username']}'";
		$result = @mysqli_query($dbc, $query);
		$assoc = mysqli_fetch_assoc($result);

		if ($assoc) {
			echo "<table align=\"left\">";
			echo "<tr>";
			echo "<td width=\"5%\">&nbsp</td>";
			echo "<td width=\"25%\"><h3>Ten hien thi: </h3></td> <td><h3>{$assoc['disp_name']}</h3></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width=\"5%\">&nbsp</td>";
			echo "<td width=\"25%\"><h3>Ten dang nhap: </h3></td> <td><h3>{$assoc['username']}</h3></td>";
			echo "</tr>";

			echo "</table>";
		}

	} else {
		header("Location: http://{$_SERVER['HTTP_HOST']}/index.html");
		exit();
	}
?>

<form name="">

<!-- KET THUC NOI DUNG TRANG -->
<?php
	include("./include/footer.html");
?>

