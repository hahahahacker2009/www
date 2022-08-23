<?php
	$charset = "iso-8859-1";
        $page_title = "Intranet - Trang chu";
	include("./include/header.html");
?>

<!-- BAT DAU NOI DUNG TRANG -->

<h1>Cai dat nguoi dung</h1>
<h2 style="color:red">Khuyen cao: Sau khi thay doi cai dat nguoi dung, ban nen dang xuat ra va dang nhap lai de co trai nghiem nguoi dung on dinh nhat!</h2>

<?php
	if (isset($_SESSION['loggedin'])) {

		require_once("../mysqli_connect.php");
		$query = "SELECT user_id, disp_name, username, email, registration_date FROM user WHERE username='{$_SESSION['username']}'";
		$result = @mysqli_query($dbc, $query);
		$assoc = mysqli_fetch_assoc($result);

		if ($assoc) {
			$userid = $assoc['user_id'];
		}

		if (isset($_POST['update'])) {
			if (!empty($_POST['disp_name'])) {
				if ($_POST['disp_name'] != $assoc['username']) {
					$disp_name = escape_data_in($_POST['disp_name']);
					$query = "UPDATE user SET disp_name = '$disp_name' WHERE user_id='$userid' LIMIT 1";
					$result = @mysqli_query($dbc, $query);
					if (mysqli_affected_rows($dbc) == 1) {
						echo 'Ten hien thi cua ban da duoc thay doi.';
					} else {
						echo 'Da gap loi khi thay doi ten hien thi cua ban!';
					}
				}
			}

			if (!empty($_POST['username'])) {
				if ($_POST['username'] != $assoc['username']) {
					$username = escape_data_in($_POST['username']);
					$query = "UPDATE user SET username = '$username' WHERE user_id='$userid' LIMIT 1";
					$result = @mysqli_query($dbc, $query);
					if (mysqli_affected_rows($dbc) == 1) {
						echo 'Ten dang nhap cua ban da duoc thay doi. <br />';
					} else {
						echo 'Da gap loi khi thay doi ten dang nhap cua ban! <br />';
					}
				}
			}

			if (!empty($_POST['email'])) {
				if ($_POST['email'] != $assoc['email']) {
					$email = escape_data_in($_POST['email']);
					$query = "UPDATE user SET email = '$email' WHERE user_id='$userid' LIMIT 1";
					$result = @mysqli_query($dbc, $query);
					if (mysqli_affected_rows($dbc) == 1) {
						echo 'Email cua ban da duoc thay doi. <br />';
					} else {
						echo 'Da gap loi khi thay doi email cua ban! <br />';
					}
				}
			}
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
		<td width="25%">&nbsp</td>
		<td width="20%"><b>Ten hien thi:</b></td> <td><input type="text" name="disp_name" size="20" maxlength="40" placeholder="<?php echo $assoc['disp_name']; ?>" /></td>
	</tr>
	<tr>
		<td width="25%">&nbsp</td>
		<td width="20%"><b>Ten dang nhap:</b></td> <td><input type="text" name="username" size="20" maxlength="30" placeholder="<?php echo $assoc['username']; ?>" /></td>
	</tr>
	<tr>	
		<td width="25%">&nbsp</td>
		<td width="20%"><b>Dia chi email:</b></td> <td><input type="email" name="email" size="30" maxlength="60" placeholder="<?php echo $assoc['email']; ?>" /></td>
	</tr>
	<tr>
		<td width="25%">&nbsp</td>
		<td width="20%"><input type="submit" name="update" value="Thay doi thong tin" /></td>
	</tr>
</table>
</fieldset>
</form>

<!-- KET THUC NOI DUNG TRANG -->
<?php
	include("./include/footer.html");
?>

