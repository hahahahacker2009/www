<?php
	$page_title = "Dang ki nguoi dung";
	include("{$_SERVER['DOCUMENT_ROOT']}/include/header.html");
?>

<!-- BAT DAU NOI DUNG TRANG -->
<h1>Dang ki tai khoan</h1>
<h2>Dang ki tai khoan de co nhieu quyen truy cap hon vao cac tinh nang cua website</h2>

<?php
	require_once("{$_SERVER['DOCUMENT_ROOT']}/include/config.php");
	if (isset($_SESSION['loggedin'])) {
		header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
		exit();
	}
	if (isset($_POST['register'])) {
		require_once("{$_SERVER['DOCUMENT_ROOT']}/../mysqli_connect.php");
		$msg = NULL;
		if (preg_match("/^[[:alnum:].' -_]{4,48}$/i", escape_data_out($_POST['disp_name']))) {
			$disp_name = escape_data_in($_POST['disp_name']);
		} else {
			$disp_name = FALSE;
			$msg .= "Vui long nhap ten hien thi hop le! <br />";
		}

		if (preg_match("/^[[:alnum:]_]{4,64}$/i", escape_data_out($_POST['username']))) {
			$username = escape_data_in($_POST['username']);
		} else {
			$username = FALSE;
			$msg .= "Vui long nhap ten dang nhap hop le! <br />";
		}

		if (preg_match("/^[[:lower:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,8}$/i", escape_data_out($_POST['email']))) {
			$email = escape_data_in($_POST['email']);
		} else {
			$email = FALSE;
			$msg .= "Vui long nhap email hop le! <br />";
		}

		if (preg_match("/^[[:alnum:]$#@%^.]{8,48}$/", escape_data_out($_POST['password']))) {
			if ($_POST['password'] == $_POST['verify']) {
				$password = hash("sha256", escape_data_in($_POST['password']));
			} else {
				$password = FALSE;
				$msg .= "Mat khau khong khop voi xac nhan! <br />";
			}
		} else {
			$password = FALSE;
			$msg .= "Vui long nhap mat khau hop le! <br />";
		}

		if ($disp_name && $username && $email && $password) {
			$exist['username'] = check_username($username, $dbc);
			$exist['email'] = check_email($email, $dbc);

			if ($exist['username'] == FALSE && $exist['email'] == FALSE) {
				$query = "INSERT INTO user (disp_name, username, email, password, registration_date) VALUES ('$disp_name', '$username', '$email', SHA1('$password'), CURRENT_TIMESTAMP());";
				echo "Cau lenh se thuc hien: $query";
				$result = @mysqli_query($dbc, $query);
				if ($result) {
					echo "<h2>Ban da duoc dang ki!</h2>";
					$_SESSION['loggedin'] = TRUE;
					$_SESSION['disp_name'] = $disp_name;
					$_SESSION['username'] = $username;
					header("Location: http://{$_SERVER['HTTP_HOST']}/index.php?action=registered&&name={$_POST['disp_name']}");
					exit();	
				} else {
					$msg .= '<h3>Ban khong the dang ki do mot loi he thong. Chung toi xin loi vi su co nay.</h3>' . mysqli_error($dbc);
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
<legend><h3>Nhap vao thong tin cua ban:</h3></legend>
<table style="text-align: center; margin-left:auto; margin-right:auto; border-width:0; width:100%;">
	<tr>
		<td style="width:20%;">&nbsp;</td>
		<td style="width:10%;"><b>Ten hien thi:</b></td> <td><label for="disp_name"><input id="disp_name" type="text" name="disp_name" size="24" maxlength="48" placeholder="Ten hien thi" value="<?php if(isset($_POST['disp_name'])) {echo stripslashes($_POST['disp_name']);} ?>" required="required" /></label></td>
	</tr>
	<tr>
		<td style="width:20%;">&nbsp;</td>
		<td style="width:10%;"><b>Ten dang nhap:</b></td> <td><label for="username"><input id="username" type="text" name="username" size="24" maxlength="32" placeholder="Ten dang nhap" value="<?php if(isset($_POST['username'])) {echo stripslashes($_POST['username']);} ?>" required="required" /></label></td>
	</tr>
	<tr>	
		<td style="width:20%;">&nbsp;</td>
		<td style="width:10%;"><b>Dia chi email:</b></td> <td><label for="email"><input id="email" type="email" name="email" size="32" maxlength="64" placeholder="Email" value="<?php if(isset($_POST['email'])) {echo stripslashes($_POST['email']);} ?>" required="required" /></label></td>
	</tr>
	<tr>
		<td style="width:20%;">&nbsp;</td>
		<td style="width:10%;"><b>Mat khau:</b></td> <td><label for="password"><input id="password" type="password" name="password" size="24" maxlength="48" placeholder="Mat khau" required="required" /></label></td>
	</tr>
	<tr>
		<td style="width:20%;">&nbsp;</td>
		<td style="width:10%;"><b>Xac nhan:</b></td> <td><label for="verify"><input id="verify" type="password" name="verify" size="24" maxlength="48" placeholder="Xac nhan" required="required" /></label></td>
	</tr>
	<tr>
		<td style="width:20%;">&nbsp;</td>
		<td style="width:10%;"><label for="register"><input id="register" type="submit" name="register" value="Dang ki!" /></label></td>
	</tr>
</table>
</fieldset>

</form>
<!-- KET THUC NOI DUNG TRANG -->
<?php
include("{$_SERVER['DOCUMENT_ROOT']}/include/footer.html");
?>
