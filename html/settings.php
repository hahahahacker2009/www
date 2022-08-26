<?php
	$charset = "iso-8859-1";
        $page_title = "Intranet - Trang chu";
	include("{$_SERVER['DOCUMENT_ROOT']}/include/header.html");
?>

<!-- BAT DAU NOI DUNG TRANG -->

<h1>Cai dat nguoi dung</h1>
<h2 style="color:red">Khuyen cao: Sau khi thay doi cai dat nguoi dung, ban nen dang xuat ra va dang nhap lai de co trai nghiem nguoi dung on dinh nhat!</h2>
<h2 style="color:red">CANH BAO: Thao tac xoa tai khoan cua ban la KHONG THE HOAN TAC!!</h2>

<?php
	require_once("{$_SERVER['DOCUMENT_ROOT']}/include/config.php");
	if (isset($_SESSION['loggedin'])) {
		require_once("{$_SERVER['DOCUMENT_ROOT']}/../mysqli_connect.php");
		$msg = NULL;
		$query = "SELECT user_id, disp_name, username, email, password, registration_date FROM user WHERE username='{$_SESSION['username']}'";
		$result = @mysqli_query($dbc, $query);
		$assoc = mysqli_fetch_assoc($result);

		if ($assoc) {
			$userid = $assoc['user_id'];
		}

		if (isset($_POST['update'])) {
			if (!empty($_POST['auth'])) {
				$query = "SELECT user_id FROM user WHERE username='{$assoc['username']}' AND password=SHA2('{$_POST['auth']}', 256)";
				#echo "Cau truy van se duoc thuc hien: $query";
				$result = @mysqli_query($dbc, $query);
				$num = mysqli_num_rows($result);
				if ($num == 1) {
					if (!empty($_POST['disp_name'])) {
						if ($_POST['disp_name'] != $assoc['disp_name']) {
							$disp_name = escape_data_in($_POST['disp_name']);
							$query = "UPDATE user SET disp_name = '$disp_name' WHERE user_id='$userid' LIMIT 1";
							$result = @mysqli_query($dbc, $query);
							if (mysqli_affected_rows($dbc) == 1) {
								$msg .= 'Ten hien thi cua ban da duoc thay doi. <br />';
								$_SESSION['disp_name'] = $disp_name;
							} else {
								$msg .= 'Da gap loi khi thay doi ten hien thi cua ban! <br />';
							}
						}
					}

					if (!empty($_POST['username'])) {
						if ($_POST['username'] != $assoc['username']) {
							$username = escape_data_in($_POST['username']);
							$query = "UPDATE user SET username = '$username' WHERE user_id='$userid' LIMIT 1";
							$result = @mysqli_query($dbc, $query);
							if (mysqli_affected_rows($dbc) == 1) {
								$msg .= 'Ten dang nhap cua ban da duoc thay doi. <br />';
								$_SESSION['username'] = $username;
							} else {
								$msg .= 'Da gap loi khi thay doi ten dang nhap cua ban! <br />';
							}
						}
					}

					if (!empty($_POST['email'])) {
						if ($_POST['email'] != $assoc['email']) {
							$email = escape_data_in($_POST['email']);
							$query = "UPDATE user SET email = '$email' WHERE user_id='$userid' LIMIT 1";
							$result = @mysqli_query($dbc, $query);
							if (mysqli_affected_rows($dbc) == 1) {
								$msg .= 'Email cua ban da duoc thay doi. <br />';
							} else {
								$msg .= 'Da gap loi khi thay doi email cua ban! <br />';
							}
						}
					}
					
					if (!empty($_POST['passwd'])) {
						if ($_POST['passwd'] == $_POST['confirm']) {
							if (hash('sha256', $_POST['passwd']) != $assoc['password']) {
								$passwd = escape_data_in($_POST['passwd']);
								$query = "UPDATE user SET password = SHA2('$passwd', 256) WHERE user_id='$userid' LIMIT 1";
								$result = @mysqli_query($dbc, $query);
								if (mysqli_affected_rows($dbc) == 1) {
									$msg .= 'Mat khau cua ban da duoc thay doi. <br />';
								} else {
									$msg .= 'Da gap loi khi thay doi mat khau cua ban! <br />';
								}
							}
						} else {
							$msg .= "Mat khau khong khop voi xac nhan! <br />";
						}
					}
					
				} else {
					$msg .= "Mat khau hien tai khong dung! <br />";
				}
			} else {
				$msg .= "Truoc khi thay doi thong tin, NHAP VAO MAT KHAU CUA BAN! <br />";
			}
		}

		if (isset($_POST['delete_account'])) {
			if (!empty($_POST['auth'])) {
				$query = "SELECT user_id FROM user WHERE username='{$assoc['username']}' AND password=SHA2('{$_POST['auth']}', 256)";
                                #echo "Cau truy van se duoc thuc hien: $query";
                                $result = @mysqli_query($dbc, $query);
                                $num = mysqli_num_rows($result);
				if ($num == 1) {
					$query = "DELETE FROM user WHERE username='${assoc['username']}' LIMIT 1";
					$result = @mysqli_query($dbc, $query);
					if (mysqli_affected_rows($dbc) == 1) {
						$msg .= "Tai khoan cua ban da bi xoa. <br />";
						header("Location: {$_SERVER['HTTP_HOST']}/logout.php");
						exit();
					} else {
						$msg .= "Khong the xoa tai khoan cua ban! <br />";
					}
				} else {
					$msg .= "Mat khau hien tai khong dung! <br />";
				}
			} else {
				$msg .= "Truoc khi thay doi thong tin, NHAP VAO MAT KHAU CUA BAN! <br />";
			}
		}

		mysqli_close($dbc);

		if (isset($msg)) {
			echo "<p style=\"color: red;\">{$msg} </p>";
		}

	} else {
		header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
		exit();
	}
?>

<h3><a href="<?php echo "http://{$_SERVER['HTTP_HOST']}/profiles.php?username={$_SESSION['username']}"; ?>">Ho so cong khai</a></h3>

<form name="editinfo" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<fieldset>
<table border="0" width="100%" align="center">
	<legend><h3>Thong tin cua ban:</h3></legend>
	<tr>
		<td width="10%">&nbsp</td>
		<td width="15%"><b>Mat khau hien tai:</b></td> <td><input type="password" name="auth" size="20" maxlength="40" placeholder="Mat khau hien tai" required="required" /></td>
	</tr>
	<tr>
		<td width="10%">&nbsp</td>
		<td width="15%"><b>Ten hien thi:</b></td> <td><input type="text" name="disp_name" size="20" maxlength="40" placeholder="<?php echo $assoc['disp_name']; ?>" /></td>
	</tr>
	<tr>
		<td width="10%">&nbsp</td>
		<td width="15%"><b>Ten dang nhap:</b></td> <td><input type="text" name="username" size="20" maxlength="30" placeholder="<?php echo $assoc['username']; ?>" /></td>
	</tr>
	<tr>	
		<td width="10%">&nbsp</td>
		<td width="15%"><b>Dia chi email:</b></td> <td><input type="email" name="email" size="30" maxlength="60" placeholder="<?php echo $assoc['email']; ?>" /></td>
	</tr>
	<tr>	
		<td width="10%">&nbsp</td>
		<td width="15%"><b>Mat khau (moi):</b></td> <td><input type="password" name="passwd" size="30" maxlength="60" placeholder="Mat khau moi" /></td>
	</tr>
	<tr>	
		<td width="10%">&nbsp</td>
		<td width="15%"><b>Xac nhan:</b></td> <td><input type="password" name="confirm" size="30" maxlength="60" placeholder="Xac nhan" /></td>
	</tr>
	<tr>
		<td width="10%">&nbsp</td>
		<td width="15%"><input type="submit" name="update" value="Thay doi thong tin" /></td>
		<td width="15%"><input type="submit" name="delete_account" value="Xoa tai khoan cua toi"></td>
	</tr>
</table>
</fieldset>
</form>

<!-- KET THUC NOI DUNG TRANG -->
<?php
	include("{$_SERVER['DOCUMENT_ROOT']}/include/footer.html");
?>
