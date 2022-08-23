<?php
$charset = "iso-8859-1";
$page_title = "Intranet - Trang chu";
include("./include/header.html");
?>

<?php
	require_once("../mysqli_connect.php");
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
			$password = escape_data_in($_POST['password']);

		} else {
			$password = FALSE;
			$msg .= "Nhap vao mat khau cua ban! <br />";
		}

		if ($username && $password) {
			$query = "SELECT disp_name, username FROM user WHERE username='$username' and password=SHA2('$password', 256)";
			echo "Cau truy van se duoc thuc hien: $query";
			$result = @mysqli_query($dbc, $query);
			$assoc = mysqli_fetch_assoc($result);
			if ($assoc) {
				$_SESSION['loggedin'] = TRUE;
				$_SESSION['disp_name'] = $assoc['disp_name'];
				$_SESSION['username'] = $assoc['username'];
				echo "<h2>Ban da dang nhap thanh cong!</h2>";
				header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
				exit();
			} else {
				$msg = "Khong the dang nhap vao tai khoan ban! <br />" . mysqli_error($dbc);
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
<form name="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<fieldset>
<!-- <div align="center"> -->
<table border="0" width="100%" align="center">
	<legend><h3>Nhap vao thong tin dang nhap</h3></legend>
	<tr>
		<td width="30%">&nbsp</td>
		<td width="13%"><b>Ten dang nhap:</b></td> <td><input type="text" name="username" size="20" maxlength="30" placeholder="Ten dang nhap" value="<?php if(isset($_POST['username'])) {echo stripslashes($_POST['username']);} ?>" required="required" /></td>
	</tr>
	<tr>
		<td width="30%">&nbsp</td>
		<td width="13%"><b>Mat khau:</b></td> <td><input type="password" name="password" size="25" maxlength="48" placeholder="Mat khau" required="required" /></td>
	</tr>
	<tr>
		<td width="30%">&nbsp</td>
		<td width="13%"><input type="submit" name="login" value="Dang nhap!" /></td>
	</tr>
</table>
</fieldset>
</form>

<?php
	include("./include/footer.html");
?>

