<?php
$charset = "iso-8859-1";
$page_title = "Intranet - Trang chu";
include("./include/header.html");
?>
<!-- BAT DAU NOI DUNG TRANG -->
<h1>Quan li vai tro</h1>
<h2>Quan li vai tro quan tri, cap vai tro quan tri, xoa vai tro quan tri</h2>
<p>Quan tri website dong cua ban</p>
<?php
	require_once("{$_SERVER['DOCUMENT_ROOT']}/include/config.php");
	require_once("./auth.php");

	$msg = NULL;
	$allowed_role = ["ADMIN", "MOD"];

	if (isset($_POST['update_role'])) {
		if ($_SESSION['role_id'] < 2) {
			if (isset($_POST['role'])) {
				$role = NULL;
				foreach ($_POST['role'] as $key => $value ) {
					if ($value != 0 && $value != 2) {
						$role = $value;
						if (in_array($role, $allowed_role)) {
							$query = "SELECT mod_id FROM user_mod WHERE username=$key";
							$result = @mysqli_query($dbc, $query);
							$num = mysqli_num_rows($result);
							if ($num == 1) {
								$role_query = "SELECT role FROM user_mod WHERE username=$key";
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
										$query = "UPDATE user_mod SET role='$role' WHERE username=$key LIMIT 1";
										$result = @mysqli_query($dbc, $query);
										if (mysqli_affected_rows($dbc) == 1) {
										        $msg .= "Da cap nhat vai tro cua quan tri vien $key thanh $role <br />";
										} else {
										        $msg .= "Khong the cap nhat vai tro cua quan tri vien $key thanh $role! <br />";
										}
									} else {
										$msg .= "Tai khoan cua ban chua du quyen de thuc hien tac vu nay! <br />";
									}
								} else {
									$msg .= "Co so du lieu gap truc trac!";
								}
							} else {
								$msg .= "Loi khi tim kiem nguoi dung!";
							}
						} else {
							die("Gia tri khong an toan!");
						}
					} else {
						if ($value == 2) {
							$query = "DELETE FROM user_mod WHERE username=$key LIMIT 1;";
							echo "Cau truy van se duoc thuc hien: $query";
				                               $result = @mysqli_query($dbc, $query);
							if (mysqli_affected_rows($dbc) == 1) {
								$msg .= "Da xoa quan tri vien $key khoi CSDL quan tri! <br />";
							} else {
								$msg .= "Khong the xoa quan tri vien $key khoi CSDL quan tri! <br />";
			                                }
			                                mysqli_free_result($result);
							}
						}
					}
				}
			} else {
				$msg .= "Vai tro cua tai khoan ban khong cho phep thuc hien tac vu nay. <br />";
			}
	}

	if (isset($msg)) {
                echo "<p style=\"color: red;\">{$msg}</p>";
	}

        $query = "SELECT mod_id, username, role FROM user_mod";
        $result = @mysqli_query($dbc, $query);
	$num = mysqli_num_rows($result);

	if ($num > 0) {
                echo "<h3>Hien co $num quan tri vien</h3>";
                echo "<form name=\"roletable\" action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\">";
                echo '<table align="center" cellspacing="2" cellpadding="2">
                <tr>
			<td width="10%">&nbsp</td>
			<td width="18%" align="left"><b>ID</b></td>
			<td width="18%" align="left"><b>Ten dang nhap</b></td>
			<td width="18%" align="left"><b>Vai tro</b></td>
                </tr>
                ';
                while ($row = mysqli_fetch_row($result)) {
			if ($row[2] == "ROOT") {
				echo "
				<tr>
                                <td width=\"10%\">&nbsp</td>
                                <td width=\"18%\" align=\"left\">$row[0]</td>
                                <td width=\"18%\" align=\"left\"><a href=\"/profiles.php?username={$row[1]}\">$row[1]</td>
                                <td width=\"18%\" align=\"left\">$row[2]</td>
				</tr>
                        \n";
                	} else {
				switch ($row[2]) {
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
						<select name=\"role['{$row[1]}']\">
							<option value=\"0\">$row[2]</option>
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
							$query = "SELECT mod_id FROM user_mod WHERE username='$newmod_username'";
							$result = @mysqli_query($dbc, $query);

							if (mysqli_num_rows($result) == 0) {
								$query = "INSERT INTO user_mod (username, role) VALUES ('$newmod_username', '$newmod_role') ";
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
		} else {
			echo "<h3Vai tro cua ban khong cho phep thuc hien tac vu nay.</h3>>";
		}
	}

        mysqli_close($dbc);
?>

<br /><br /><br /><br />
<form name="add_mod" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="text" name="newmod_username" placeholder="Ten nguoi dung" size="20" maxlength="30" required="required" /><br /><br />
<label for="newrole"><input type="radio" name="newrole" value="ADMIN" />ADMIN</label>
<label for="newrole"><input type="radio" name="newrole" value="MOD" />MOD</label>
<br /><br />
<input type="submit" name="add_new_mod" value="Them quan tri vien!" />
</form>

<!-- KET THUC NOI DUNG TRANG -->
<?php
include("./include/footer.html");
?>
