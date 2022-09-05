<?php
$charset = "iso-8859-1";
$page_title = "Quan li vai tro";
include("./include/header.html");
?>
<!-- BAT DAU NOI DUNG TRANG -->
<h1>Quan li vai tro</h1>
<h2>Quan li vai tro quan tri, cap vai tro quan tri, xoa vai tro quan tri</h2>
<p>Quan tri website dong cua ban</p>
<?php
	//require_once("{$_SERVER['DOCUMENT_ROOT']}/include/config.php");
	require_once("./auth.php");

	$msg = NULL;
	$allowed_role = ["ADMIN", "MOD", 2];
	$process_okay = FALSE;

	if ($_SESSION['role_id'] < 2) {
		if (isset($_POST['update_role']) && isset($_POST['role'])) {
			$role = NULL;
			foreach ($_POST['role'] as $key => $value ) {
				if ($value != 0) {
					$role = $value;
					if (in_array($role, $allowed_role)) {
						$query = "SELECT username, moderator_id, role FROM user AS u, moderator AS m WHERE u.user_id=m.user_id AND u.user_id=$key";
						$result = @mysqli_query($dbc, $query);
						if (mysqli_num_rows($result) == 1) {
							$assoc = mysqli_fetch_assoc($result);
							$target_user = $assoc['username'];
							$target_role = $assoc['role'];
							switch ($target_role) {
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
								if ($value == 2) {
									$process_okay = TRUE;
									$process = "xoa";

								} else {
									$process_okay = TRUE;
									$process = "cap nhat vai tro cua";
								}
							} else {
								$process_okay = FALSE;
								$msg .= "Tai khoan cua ban chua du quyen de thuc hien tac vu nay! <br />";
							}

							if ($process_okay == TRUE) {
								switch ($process) {
								case "xoa":
									$query = "DELETE FROM moderator WHERE user_id=$key LIMIT 1;";
									echo "Cau truy van se duoc thuc hien: $query";
									break;
								case "cap nhat vai tro cua":
									$query = "UPDATE moderator SET role='$role' WHERE user_id=$key LIMIT 1";
									echo "Cau truy van se duoc thuc hien: $query";
									break;
								}

								$result = @mysqli_query($dbc, $query);
								if (mysqli_affected_rows($dbc) == 1) {
									$msg .= "Da $process quan tri vien $target_user ($key) (khoi CSDL quan tri) <br />";
								} else {
									$msg .= "Khong the $process quan tri vien $target_user ($key) (khoi CSDL quan tri) <br />";
								}

							} else {
								$msg .= "Chua dap ung du dieu kien de thuc hien hanh dong voi quan tri vien!";
							}
							
						}

					} else {
						die("Gia tri khong an toan!");
					}
				}
			}
		}

	} else {
		$msg .= "Vai tro cua tai khoan ban khong cho phep thuc hien tac vu nay. <br />";
	}

	if (isset($_POST['add_new_mod'])) {
		if ($_SESSION['role_id'] < 2) {
			if (!empty($_POST['newmod_username'])) {
				if (!empty($_POST['newrole'])) {
					$newmod_username = escape_data_in($_POST['newmod_username']);
					$newmod_role = escape_data_in($_POST['newrole']);
					if (in_array($newmod_role, $allowed_role)) {
						$query = "SELECT user_id FROM user WHERE username='$newmod_username'";
						$result = @mysqli_query($dbc, $query);

						if (mysqli_num_rows($result) == 1) {
							$target_id = mysqli_fetch_assoc($result)['user_id'];
							$query = "SELECT moderator_id FROM moderator WHERE user_id=$target_id";
							$result = @mysqli_query($dbc, $query);

							if (mysqli_num_rows($result) == 0) {
								$query = "INSERT INTO moderator (user_id, role) VALUES ($target_id, '$newmod_role') ";
								$result = @mysqli_query($dbc, $query);
								if ($result) {
									echo "<h3>Da them nguoi dung $newmod_username ($newmod_role) vao CSDL quan tri!</h3>";
								} else {
									echo "<h3>Loi khi them nguoi dung vao CSDL</h3>";
								}
							} else {
								echo "<h3>Nguoi dung nay hien dang la quan tri vien.</h3>";
							}
						} else {
							echo "<h3>Nguoi dung khong ton tai!</h3>";
						}
					} else {
						die ("<h3>Gia tri khong an toan!</h3>");
					}
				} else {
					echo "<h3>Dien vao vai tro moi!</h3>";
				}
			} else {
				echo "<h3>Nhap vao ten dang nhap cua nguoi dung!</h3>";
			}
		}

	}


	if (isset($msg)) {
                echo "<p style=\"color: red;\">{$msg}</p>";
	}

        $query = "SELECT u.user_id, username, moderator_id, role FROM user AS u INNER JOIN moderator AS m ON u.user_id=m.user_id";
        $result = @mysqli_query($dbc, $query);
	$num = mysqli_num_rows($result);

	if ($num > 0) {
                echo "<h3>Hien co $num quan tri vien</h3>";
                echo "<form name=\"roletable\" action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\">";
                echo '<table align="center" cellspacing="2" cellpadding="2">
                <tr>
			<td width="10%">&nbsp</td>
			<td width="18%" align="left"><b>ID (Nguoi dung)</b></td>
			<td width="18%" align="left"><b>Ten dang nhap</b></td>
			<td width="18%" align="left"><b>Vai tro</b></td>
                </tr>
                ';
                while ($row = mysqli_fetch_row($result)) {
			if ($row[3] == "ROOT") {
				echo "
				<tr>
                                <td width=\"10%\">&nbsp</td>
                                <td width=\"18%\" align=\"left\">$row[0]</td>
                                <td width=\"18%\" align=\"left\"><a href=\"/profiles.php?username={$row[1]}\">$row[1]</td>
                                <td width=\"18%\" align=\"left\">$row[3]</td>
				</tr>
                        \n";
                	} else {
				switch ($row[3]) {
				case "ADMIN":
					$role_next = "MOD";
					break;
				case "MOD":
					$role_next = "ADMIN";
					break;
				}
	                        echo "
				<tr>
					<td width=\"10%\">&nbsp</td>
					<td width=\"18%\" align=\"left\">$row[0]</td>
					<td width=\"18%\" align=\"left\"><a href=\"/profiles.php?username={$row[1]}\">$row[1]</td>
					<td width=\"18%\" align=\"left\">
						<select name=\"role[{$row[0]}]\">
							<option value=\"0\">$row[3]</option>
							<option value=\"{$role_next}\">$role_next</option>
							<option value=\"2\">Normal User</option>
						</select>
					</td>
	                        </tr>
				\n";
			}
                }
                echo '</table>';
                echo '<br /><div align="center"><input type="submit" name="update_role" value="Cap nhat vai tro" /></div>';
		echo '</form>';
                mysqli_free_result($result);
	} else {
		echo '<p>Co ve nhu chua co quan tri vien nao ca</p>';
	}

        mysqli_close($dbc);
?>

<br /><br /><br />
<form name="add_mod" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<div align="center"><input type="text" name="newmod_username" placeholder="Ten nguoi dung" size="20" maxlength="30" required="required" /></div>
<br />
<div align="center"><label for="newrole"><input type="radio" name="newrole" value="ADMIN" />ADMIN</label></div>
<div align="center"><label for="newrole"><input type="radio" name="newrole" value="MOD" />MOD</label></div>
<br />
<div align="center"><input type="submit" name="add_new_mod" value="Them quan tri vien!" /></div>
</form>

<!-- KET THUC NOI DUNG TRANG -->
<?php
include("./include/footer.html");
?>
