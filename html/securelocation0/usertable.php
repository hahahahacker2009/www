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
	//require_once("{$_SERVER['DOCUMENT_ROOT']}/include/config.php");
	require_once("./auth.php");
	$msg = NULL;

	if ($_SESSION['role_id'] < 2) {
		if (isset($_POST['delete'])) {
			if (isset($_POST['delete_id'])) {
				$id = NULL;
				foreach ($_POST['delete_id'] as $key => $value ) {
					$id = $value;
					$query = "SELECT username FROM user WHERE user_id='$id'";
					$result = @mysqli_query($dbc, $query);
					$assoc = mysqli_fetch_assoc($result);
					if ($assoc) {
						$role_query = "SELECT role FROM moderator WHERE moderator_id='$id'";
						$role_result = @mysqli_query($dbc, $role_query);
						$role_assoc = mysqli_fetch_assoc($role_result);
						if ($role_assoc) {
							switch ($role_assoc['role']) {
								case "ROOT":
									$target_role_id = 0;
									break;
								case "ADMIN":
									$target_role_id = 1;
									break;
								case "MOD":
									$target_role_id = 2;
									break;
							}

							if ($target_role_id > $_SESSION['role_id']) {
								$query = "DELETE FROM user WHERE user_id=$id AND username='{$assoc['username']}' LIMIT 1";
								$result = @mysqli_query($dbc, $query);
								if (mysqli_affected_rows($dbc) == 1) {
									$msg .= "Da xoa quan tri vien {$assoc['username']}, ID $id khoi CSDL nguoi dung. <br />";
								} else {
									$msg .= "Khong the xoa quan tri vien {$assoc['username']}, ID $id khoi CSDL nguoi dung ! <br />";
								}

								/*
								$query = "DELETE FROM user_mod WHERE username='{$assoc['username']}' LIMIT 1";
								$result = @mysqli_query($dbc, $query);
								if (mysqli_affected_rows($dbc) == 1) {
									$msg .= "Da xoa quan tri vien {$assoc['username']}, ID $id khoi CSDL quan tri. <br />";
								} else {
									$msg .= "Khong the xoa quan tri vien {$assoc['username']}, ID $id khoi CSDL quan tri ! <br />";
								}
								*/

								// use a foreign key and link user(username) column to user_mod(username)

							} else {
								$msg .= "Tai khoan cua ban chua du quyen de thuc hien tac vu nay! <br />";
							}
						} else {
							$query = "DELETE FROM user WHERE user_id=$id AND username='{$assoc['username']}' LIMIT 1";
							$result = @mysqli_query($dbc, $query);
							if (mysqli_affected_rows($dbc) == 1) {
								$msg .= "Da xoa nguoi dung {$assoc['username']}, ID $id <br />";
							} else {
								$msg .= "Khong the xoa nguoi dung {$assoc['username']}, ID $id ! <br />";
							}
						}

					} else {
						$msg .= "Khong tim thay nguoi dung! <br />";
					}
				}
			}
	} else {
		$msg .= "Vai tro cua ban khong cho phep thuc hien tac vu nay. <br />";
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
				<td width="5%">&nbsp</td>
				<td width="10%" align="left"><b>ID</b></td>
				<td width="10%" align="left"><b>Ten</b></td>
				<td width="10%" align="left"><b>Email</b></td>
				<td width="10%" align="left"><b>Ngay dang ky</b></td>
				<td width="10%" align="left"><b>Vai tro</b></td>
			</tr>
			';
		while ($row = mysqli_fetch_row($result)) {
			$name = escape_data_out("{$row[1]} ({$row[2]})");
			$role_query_table = "SELECT role FROM moderator WHERE user_id={$row[0]}";
			$role_result_table = @mysqli_query($dbc, $role_query_table);
			$role_assoc_table = mysqli_fetch_assoc($role_result_table);
			if ($role_assoc_table) {
				echo "
				<tr>
					<td width=\"5%\">&nbsp</td>
					<td width=\"10%\" align=\"left\"><input type=\"checkbox\" name=\"delete_id[]\" value=\"$row[0]\" />$row[0]</td>
					<td width=\"10%\" align=\"left\"><a href=\"/profiles.php?username={$row[2]}\">$name</td>
					<td width=\"10%\" align=\"left\">$row[3]</td>
					<td width=\"10%\" align=\"left\">$row[4]</td>
					<td width=\"10%\" align=\"left\">{$role_assoc_table['role']}</td>
				</tr>
				\n";
			} else {
				echo "
				<tr>
					<td width=\"5%\">&nbsp</td>
					<td width=\"10%\" align=\"left\"><input type=\"checkbox\" name=\"delete_id[]\" value=\"$row[0]\" />$row[0]</td>
					<td width=\"10%\" align=\"left\"><a href=\"/profiles.php?username={$row[2]}\">$name</td>
					<td width=\"10%\" align=\"left\">$row[3]</td>
					<td width=\"10%\" align=\"left\">$row[4]</td>
					<td width=\"10%\" align=\"left\">Normal User</td>
				</tr>
				\n";
			}
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
