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
	$delete_okay = FALSE;

	if ($_SESSION['role_id'] < 2) {
		if (isset($_POST['delete'])) {
			if (isset($_POST['delete_id'])) {
				$id = NULL;
				foreach ($_POST['delete_id'] as $key => $value ) {
					$id = $value;
					$query = "SELECT user_id, username FROM user WHERE user_id=$id";
					$result = @mysqli_query($dbc, $query);

					if (mysqli_num_rows($result) == 1) {
						$target_user = mysqli_fetch_assoc($result)['username'];
						$query = "SELECT role FROM moderator WHERE user_id=$id";
						$result = @mysqli_query($dbc, $query);
						$assoc = mysqli_fetch_assoc($result);
						if ($assoc) {
							switch ($assoc['role']) {
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
								$is_mod = "quan tri vien";
								$delete_okay = TRUE;

							} else {
								$msg .= "Tai khoan cua ban chua du quyen de thuc hien tac vu nay! <br />";
								$delete_okay = FALSE;
							}

						} else {
							$is_mod = "nguoi dung";
							$delete_okay = TRUE;
						}

						if ($delete_okay == TRUE) {
							$query = "DELETE FROM user WHERE user_id=$id LIMIT 1";
							$result = @mysqli_query($dbc, $query);
							if (mysqli_affected_rows($dbc) == 1) {
								$msg .= "Da xoa $is_mod $target_user, ID $id <br />";
							} else {
								$msg .= "Khong the xoa $is_mod $target_user, ID $id ! <br />";
							}
						} else {
							$msg .= "Chua dap ung du dieu kien de xoa nguoi dung!";
						}

					} else {
						$msg .= "Khong tim thay nguoi dung! <br />";
					}
				}
			}
		}
	} else {
		$msg .= "Vai tro cua ban khong cho phep thuc hien tac vu nay. <br />";
	}

	if (isset($msg)) {
		echo "<p style=\"color: red;\">{$msg}</p>";
	}

	$query = "SELECT u.user_id, disp_name, username, email, registration_date, role FROM user as u LEFT JOIN moderator as m ON u.user_id=m.user_id ORDER BY registration_date DESC;";
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
			$name = "{$row[1]} ({$row[2]})";
			echo "
			<tr>
				<td width=\"5%\">&nbsp</td>
				<td width=\"10%\" align=\"left\"><input type=\"checkbox\" name=\"delete_id[]\" value=\"$row[0]\" />$row[0]</td>
				<td width=\"10%\" align=\"left\"><a href=\"/profiles.php?username={$row[2]}\">$name</td>
				<td width=\"10%\" align=\"left\">$row[3]</td>
				<td width=\"10%\" align=\"left\">$row[4]</td>
			";
			if (isset($row[5])) {
				echo "<td width=\"10%\" align=\"left\">{$row[5]}</td>";
			} else {
				echo "<td width=\"10%\" align=\"left\">Normal User</td>";
			}
			echo "
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
