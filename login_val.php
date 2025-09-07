<?php
session_start();
if (isset($_POST['login'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];

	$stmt = $dbcon->prepare("SELECT * FROM users WHERE email=?");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$user_check = $stmt->get_result();

	if ($user_check->num_rows == 0) {

		$email_not_matched = "Your email not matched!";
	} else {

		$row = $user_check->fetch_assoc();
		if (password_verify($password, $row['password'])) {
			if ($row['status'] == 1) {
				$waiting = "Waiting for admin approval";
			} else {
				$_SESSION['user_email'] = $email;
				$_SESSION['user_name']  = $row['fname'];
				$_SESSION['photo']      = $row['photo'];
				header('location: admin/index.php');
			}
		} else {

			$password_not_matched = "Your password not matched";
		}
	}
}
