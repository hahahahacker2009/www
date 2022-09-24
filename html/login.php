<?php
$page_title = "Đăng nhập";
include("{$_SERVER['DOCUMENT_ROOT']}/include/header.html");
?>

<?php
	require_once("{$_SERVER['DOCUMENT_ROOT']}/include/config.php");
	require_once("{$_SERVER['DOCUMENT_ROOT']}/../mysqli_connect.php");
	$msg = NULL;
	if (isset($_SESSION['loggedin'])) {
		header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
		exit();
	}

	if (isset($_POST['login'])) {
		if (!empty($_POST['username'])) {
			$username = escape_data_in($_POST['username']);
		} else {
			$username = FALSE;
			$msg .= "Nhap vao ten dang nhap cua ban! <br />";
		}

		if (!empty($_POST['password'])) {
			$password = hash("sha256", escape_data_in($_POST['password']));

		} else {
			$password = FALSE;
			$msg .= "Nhap vao mat khau cua ban! <br />";
		}

		if ($username && $password) {
			$query = "SELECT user_id, disp_name, username FROM user WHERE username='$username' and password=SHA1('$password')";
			echo "<!-- Cau truy van se duoc thuc hien: $query -->";
			$result = @mysqli_query($dbc, $query);
			$assoc = mysqli_fetch_assoc($result);
			if ($assoc) {
				$_SESSION['loggedin'] = TRUE;
				$_SESSION['user_id'] = $assoc['user_id'];
				$_SESSION['disp_name'] = $assoc['disp_name'];
				$_SESSION['username'] = $assoc['username'];
				login_log(TRUE, $username);
				echo "<h2>Ban da dang nhap thanh cong!</h2>";
				$query = "SELECT role FROM moderator WHERE user_id='{$assoc['user_id']}'";
				$result = @mysqli_query($dbc, $query);
				$assoc = mysqli_fetch_assoc($result);
				if ($assoc) {
					$_SESSION['role'] = $assoc['role'];
					switch($_SESSION['role']) {
						case "ROOT":
							$_SESSION['role_id'] = 0;
							break;
						case "ADMIN":
							$_SESSION['role_id'] = 1;
							break;
						case "MOD":
							$_SESSION['role_id'] = 2;
							break;
					}
				}
				header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
				exit();
			} else {
				$msg = "Khong the dang nhap vao tai khoan ban! <br />" . mysqli_error($dbc);
				login_log(FALSE, $username);
			}

			mysqli_free_result($result);
			mysqli_close($dbc);

		} else {
			$msg .= "Hay thu lai. <br />";
		}
	}

	if (isset($msg)) {
		echo "<p style=\"color: red;\">{$msg}</p>";
	}
?>

<h1>Dang nhap tai khoan</h1>
<h2>Dang nhap vao tai khoan cua ban</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<fieldset>
<!-- <div align="center"> -->
<legend><h3>Nhap vao thong tin dang nhap</h3></legend>
<table style="text-align: center; border-width:0; width:100%;">
	<tr>
		<td style="width:20%;">&nbsp;</td>
		<td style="width:10%;"><b>Ten dang nhap:</b></td> <td><label for="username"><input id="username" type="text" name="username" size="24" maxlength="32" placeholder="Ten dang nhap" value="<?php if(isset($_POST['username'])) {echo escape_data_out($_POST['username']);} ?>" required="required" /></label></td>
	</tr>
	<tr>
		<td style="width:20%;">&nbsp;</td>
		<td style="width:10%;"><b>Mat khau:</b></td> <td><label for="password"><input id="password" type="password" name="password" size="24" maxlength="48" placeholder="Mat khau" required="required" /></label></td>
	</tr>
	<tr>
		<td style="width:20%;">&nbsp;</td>
		<td style="width:10%;"><label for="login"><input id="login" type="submit" name="login" value="Dang nhap!" /></label></td>
	</tr>
</table>
</fieldset>
</form>

<?php
	include("{$_SERVER['DOCUMENT_ROOT']}/include/footer.html");
?>
