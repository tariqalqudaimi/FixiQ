<?php
require_once "../UserSetting/user_auth.php";
$title = "Contact Information";
require_once "../Dashboard/header.php";
require_once '../Database/db.php';

$query = "SELECT * FROM contact_information";
$result = $dbcon->query($query);

$row = $result->fetch_assoc();


?>

<div class="card text-dark mb-3">
	<div class="card-header bg-success text-center">
		<h2>Contact Information</h2>
	</div>
	<div class="card-body">



		<?php if (isset($_SESSION['contact_information_change'])) { ?>

			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong><?= $_SESSION['contact_information_change'] ?></strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>


		<?php }
		unset($_SESSION['contact_information_change']);
		?>

		<table class="table table-bordered table-striped text-center mx-auto">
			<tr>
				<td>Address</td>
				<td><?= $row['address'] ?></td>
			</tr>
			<tr>
				<td>العنوان</td>
				<td><?= $row['address_ar'] ?></td>
			</tr>
			<tr>
				<td>Phone</td>
				<td><?= $row['phone'] ?></td>
			</tr>

			<tr>
				<td>Email</td>
				<td><?= $row['email'] ?></td>
			</tr>

		</table>

		<a class="btn btn-block btn-success" href="manage_contact.php">Change Contact Information</a>
	</div>
</div>



<?php
require_once "../Dashboard/footer.php";
?>