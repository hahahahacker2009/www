<?php
$charset = "iso-8859-1";
$page_title = "Quan li nguoi dung";
include("./include/header.html");
?>
<!-- BAT DAU NOI DUNG TRANG -->
<h1>Quan li nguoi dung</h1>
<h2>Xem va xoa nguoi dung. Chi ADMIN moi co the xoa nguoi dung.</h2>
<p style="color:red">Luu y: Thao tac xoa nguoi dung la KHONG THE HOAN TAC!</p>
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
	$msg = NULL;

	if (isset($_POST['delete'])) {
		if ($_SESSION['role'] == "ADMIN") {
			$id = NULL;
			if (isset($_POST['delete_id'])) {
				$id = NULL;
				foreach ($_POST['delete_id'] as $key => $value ) {
					$id = $value;
					$query = "SELECT username FROM user WHERE user_id='$id'";
					$result = @mysqli_query($dbc, $query);
					$assoc = mysqli_fetch_assoc($result);
					if ($assoc) {
						$role_query = "SELECT role FROM user_mod WHERE username='{$assoc['username']}'";
						$role_result = @mysqli_query($dbc, $role_query);
						$role_assoc = mysqli_fetch_assoc($role_result);
						if ($role_assoc['role'] != "ADMIN") {
							$query = "DELETE FROM user WHERE user_id=$id AND username='{$assoc['username']}' LIMIT 1";
							$result = @mysqli_query($dbc, $query);
							if (mysqli_affected_rows($dbc) == 1) {
								$msg .= "Da xoa nguoi dung {$assoc['username']}, ID $id <br />";
							} else {
								$msg .= "Khong the xoa nguoi dung {$assoc['username']}, ID $id ! <br />";
							}
						} else {
							$msg .= "Hien tai chung toi khong cho phep xoa mot tai khoan co vai tro la ADMIN!";
						}
					} else {
						$msg .= "Khong tim thay nguoi dung! <br />";
					}
				}
			}
		} else {
			$msg .= "Vai tro cua ban khong cho phep thuc hien tac vu nay. <br />";
		}
	}

	if (isset($msg)) {
		echo "<p style=\"color: red;\">{$msg}</p>";
	}

	$query = "SELECT user_id, disp_name, username, email, registration_date FROM user ORDER BY registration_date DESC";
	$result = @mysqli_query($dbc, $query);
	$num = mysqli_num_rows($result);

	if ($num > 0) {
		echo "<h3>Hien co $num nguoi dung da dang ki</h3>";
		echo "<form name=\"usertable\" action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\">";
		echo '<table align="center" cellspacing="2" cellpadding="2">
			<tr>
				<td width="10%">&nbsp</td>
				<td width="13%" align="left"><b>ID</b></td>
				<td width="13%" align="left"><b>Ten</b></td>
				<td width="13%" align="left"><b>Email</b></td>
				<td width="13%" align="left"><b>Ngay dang ky</b></td>
			</tr>
			';
		while ($row = mysqli_fetch_row($result)) {
			$name = escape_data_out("{$row[1]} ({$row[2]})");
			echo "
			<tr>
				<td width=\"10%\">&nbsp</td>
				<td width=\"13%\" align=\"left\"><input type=\"checkbox\" name=\"delete_id[]\" value=\"$row[0]\" />$row[0]</td>
				<td width=\"13%\" align=\"left\">$name</td>
				<td width=\"13%\" align=\"left\">$row[3]</td>
				<td width=\"13%\" align=\"left\">$row[4]</td>
			</tr>
			\n";
		}
		echo '</table>';
		echo '<br /><div align="center"><input type="submit" name="delete" value="Xoa nguoi dung" /></div>';
		echo '</form>';
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
