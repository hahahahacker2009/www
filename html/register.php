<?php
	$charset = "iso-8859-1";
	$page_title = "Intranet - Trang chu";
	include("./include/header.html");
?>

<!-- BAT DAU NOI DUNG TRANG -->
<h1>Dang ki tai khoan</h1>
<h2>Dang ki tai khoan de co nhieu quyen truy cap hon vao cac tinh nang cua website</h2>
<br /><br />

<?php
	if (isset($_SESSION['loggedin'])) {
		header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
		exit();
	}
	if (isset($_POST['register'])) {
		require_once("../mysqli_connect.php");
		$msg = NULL;
		if (!empty($_POST['disp_name'])) {
			$disp_name = escape_data_in($_POST['disp_name']);
		} else {
			$disp_name = FALSE;
			$msg .= "Vui long nhap ten hien thi! <br />";
		}

		if (!empty($_POST['username'])) {
			$username = escape_data_in($_POST['username']);
		} else {
			$username = FALSE;
			$msg .= "Vui long nhap ten dang nhap! <br />";
		}

		if (!empty($_POST['email'])) {
			$email = escape_data_in($_POST['email']);
		} else {
			$email = FALSE;
			$msg .= "Vui long nhap email cua ban! <br />";
		}

		if (!empty($_POST['password'])) {
			if ($_POST['password'] == $_POST['verify']) {
				if (strlen($_POST['password']) >= 8) {
					$password = escape_data_in($_POST['password']); 
				} else {
					$password = FALSE;
					$msg .= "Mat khau phai co do dai tu 8 den 48 ki tu! <br />";
				}
			} else {
				$password = FALSE;
				$msg .= "Mat khau khong khop voi xac nhan! <br />";
			}
		} else {
			$password = FALSE;
			$msg .= "Vui long nhap mat khau cua ban! <br />";
		}

		if ($disp_name && $username && $email && $password) {
			$exist['username'] = check_username($username, $dbc);
			$exist['email'] = check_email($email, $dbc);

			if ($exist['username'] == FALSE && $exist['email'] == FALSE) {
				$query = "INSERT INTO user (disp_name, username, email, password, registration_date) VALUES ('$disp_name', '$username', '$email', SHA2('$password', 256), NOW());";
				echo "Cau lenh se thuc hien: $query";
				$result = @mysqli_query($dbc, $query);
				if ($result) {
					echo "<h2>Ban da duoc dang ki!</h2>";
					$_SESSION['loggedin'] = TRUE;
					$_SESSION['disp_name'] = $disp_name;
					$_SESSION['username'] = $username;
					echo "<h2>Ban da dang nhap thanh cong!</h2>";
					header("Location: http://{$_SERVER['HTTP_HOST']}/index.php?action=registered&&name={$_POST['disp_name']}");
					exit();	
				} else {
					$msg = '<h3>Ban khong the dang ki do mot loi he thong. Chung toi xin loi vi su co nay.</h3>' . mysqli_error($dbc);
				}
			} else {
				if ($exist['username'] == TRUE) {
					$msg .= 'Ten dang nhap da duoc su dung. <br />';
				}

				if ($exist['email'] == TRUE) {
					$msg .= 'Email da duoc su dung. <br />';
				}
			}
			mysqli_close($dbc);

		} else {
			$msg .= "Vui long thu lai!";
		}
	}

	if (isset($msg)) {
		echo "<p style=\"color: red;\">{$msg}</p>";
	}
?>

<form name="register" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<fieldset>
<table border="0" width="100%" align="center">
	<legend><h3>Nhap vao thong tin cua ban:</h3></legend>
	<tr>
		<td width="25%">&nbsp</td>
		<td width="20%"><b>Ten hien thi:</b></td> <td><input type="text" name="disp_name" size="20" maxlength="40" placeholder="Ten hien thi" value="<?php if(isset($_POST['disp_name'])) {echo stripslashes($_POST['disp_name']);} ?>" required="required" /></td>
	</tr>
	<tr>
		<td width="25%">&nbsp</td>
		<td width="20%"><b>Ten dang nhap:</b></td> <td><input type="text" name="username" size="20" maxlength="30" placeholder="Ten dang nhap" value="<?php if(isset($_POST['username'])) {echo stripslashes($_POST['username']);} ?>" required="required" /></td>
	</tr>
	<tr>	
		<td width="25%">&nbsp</td>
		<td width="20%"><b>Dia chi email:</b></td> <td><input type="email" name="email" size="30" maxlength="60" placeholder="Email" value="<?php if(isset($_POST['email'])) {echo stripslashes($_POST['email']);} ?>" required="required" /></td>
	</tr>
	<tr>
		<td width="25%">&nbsp</td>
		<td width="20%"><b>Mat khau:</b></td> <td><input type="password" name="password" size="25" maxlength="48" placeholder="Mat khau" required="required" /></td>
	</tr>
	<tr>
		<td width="25%">&nbsp</td>
		<td width="20%"><b>Mat khau (xac nhan):</b></td> <td><input type="password" name="verify" size="25" maxlength="48" placeholder="Xac nhan" required="required" /></td>
	</tr>
	<tr>
		<td width="25%">&nbsp</td>
		<td width="20%"><input type="submit" name="register" value="Dang ki!" /></td>
	</tr>
</table>
</fieldset>

</form>
<!-- KET THUC NOI DUNG TRANG -->
<?php
include("./include/footer.html");
?>


