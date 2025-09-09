<?php
require_once "user_auth.php";
$title = "Change Password";
require_once "../Dashboard/header.php";
require_once '../Database/db.php';

$email = $_SESSION['user_email'];

// Use prepared statements to fetch data
$stmt = $dbcon->prepare("SELECT password FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$password_from_db = $row['password'];

if (isset($_POST['submit'])) {
	$old_password = $_POST['old_pass'];
	$new_password = $_POST['new_pass'];
	$con_password = $_POST['con_pass'];

	if (password_verify($old_password, $password_from_db)) {
		if (!password_verify($new_password, $password_from_db)) {
			// Password strength check (simplified but better)
			if (strlen($new_password) >= 8) {
				if ($new_password == $con_password) {
					$new_hash_password = password_hash($new_password, PASSWORD_BCRYPT);

					// Use prepared statements for update
					$update_stmt = $dbcon->prepare("UPDATE users SET password = ? WHERE email=?");
					$update_stmt->bind_param("ss", $new_hash_password, $email);
					if ($update_stmt->execute()) {
						$password_change_success = "Your password changed successfully";
					} else {
						$con_pass_not_match = "Error updating password.";
					}
				} else {
					$con_pass_not_match = "Confirm password does not match new password.";
				}
			} else {
				$pass_lenght = "Password must be at least 8 characters long.";
			}
		} else {
			$old_new_matched = "You can't use your old password as the new password.";
		}
	} else {
		$old_pass_not_match = "Old Password does not match.";
	}
}
?>



<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-6 m-auto">
				<div class="card-box">
					<h4 class="header-title mb-4">Account Overview</h4>

					<!-- error old password not match  -->
					<?php if (isset($old_pass_not_match)) { ?>
						<div class="alert alert-danger">
							<?= $old_pass_not_match ?>
						</div>
					<?php } ?>

					<!-- error old and new password matched  -->
					<?php if (isset($old_new_matched)) { ?>
						<div class="alert alert-danger">
							<?= $old_new_matched ?>
						</div>
					<?php } ?>

					<!-- password validation error  -->
					<?php if (isset($pass_lenght)) { ?>
						<div class="alert alert-danger">
							<?= $pass_lenght ?>
						</div>
					<?php } ?>

					<!-- password validation error  -->
					<?php if (isset($con_pass_not_match)) { ?>
						<div class="alert alert-danger">
							<?= $con_pass_not_match ?>
						</div>
					<?php } ?>

					<!-- password change success message -->

					<?php if (isset($password_change_success)) { ?>
						<div class="alert alert-success">
							<?= $password_change_success ?>
						</div>
					<?php } ?>

					<form action="" method="post">

						<div class="form-group">
							<label for="old_pass">Old Password</label>
							<input class="form-control" type="password" name="old_pass" id="old_pass">
						</div>

						<div class="form-group">
							<label for="new_pass">New Password</label>
							<input class="form-control" type="password" name="new_pass" id="new_pass">
						</div>

						<div class="form-group">
							<label for="con_pass">Confirm Password</label>
							<input class="form-control" type="password" name="con_pass" id="con_pass">
						</div>

						<div class="form-group">
							<input class="btn btn-block btn-success" type="submit" value="Change Password" name="submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div> <!-- container -->

</div> <!-- content -->

<!-- ============ footer content =============== -->
<?php
require_once "../Dashboard/footer.php";
?>