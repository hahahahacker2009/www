<!-- BAT DAU NOI DUNG TRANG -->
<h1>Trang chu quan tri</h1>
<h2>Chao mung ban den voi trang chu quan tri cua website!</h2>
<p>Quan tri website dong cua ban</p>
<?php
	require_once("{$_SERVER['DOCUMENT_ROOT']}/include/config.php");
	if (isset($_SESSION['loggedin'])) {
		if (isset($_SESSION['role'])) {
			require_once("{$_SERVER['DOCUMENT_ROOT']}/../mysqli_connect.php");
			$query = "SELECT role FROM user_mod WHERE username='{$_SESSION['username']}'";
			$result = @mysqli_query($dbc, $query);
			$num = mysqli_num_rows($result);
			if ($num > 0) {
				$assoc = mysqli_fetch_assoc($result);
				if ($assoc) {
					$_SESSION['role'] = $assoc['role'];
					switch ($_SESSION['role']) {
					case "ROOT":
						$_SESSION['role_id'] = 0;
						break;
					case "ADMIN":
						$_SESSION['role_id'] = 1;
						break;
					case "MOD":
						$_SESSION['role_id'] = 2;
						break;

					echo "Da cap nhat vai tro hien tai cua ban tu co so du lieu: Vai tro cua ban hien tai la {$_SESSION['role']} ({$_SESSION['role_id']}) ";
					}
				} else {
					die ("Ma kich ban da ngung hoat dong do loi. Xin loi vi su co nay.");
				}
			} else {
				unset($_SESSION['role']);
				unset($_SESSION['role_id']);
				die ("Ban khong con vai tro quan tri!");
			}

			echo "<p>Ban dang dang nhap voi ten nguoi dung la {$_SESSION['username']}, vai tro quan tri cua ban la {$_SESSION['role']} ({$_SESSION['role_id']})</p> <br />";

		} else {
			die ("<p> Tai khoan cua ban khong co vai tro quan tri! </p>");
		}

	} else {
		header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
		exit();
	}

?>


<!-- KET THUC NOI DUNG TRANG -->