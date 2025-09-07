<?php 
require_once "user_auth.php";
$title="profile";
require_once "header.php";
require_once "db.php";

$email = $_SESSION['user_email'];

$stmt = $dbcon->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$id = $row['id'];
?>
<div class="row">
    <div class="col-md-7">
        <div class="card text-dark mb-3">
            <div class="card-header bg-success text-center"><h2>Profile</h2></div>
            <div class="card-body">
                <table class="table table-bordered table-striped text-center mx-auto">
                    <tr>
                        <td colspan="2"><img src="image/users/<?= htmlspecialchars($row['photo']) ?>" alt="" width="100"></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><?= htmlspecialchars($row['fname']) ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><span class="badge badge-success p-2">Active</span></td>
                    </tr>
                </table>
                <a class="btn btn-block btn-primary" href="change_password.php">Change Password</a>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <?php if(isset($_SESSION['profile_photo_emty'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?= $_SESSION['profile_photo_emty'] ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php unset($_SESSION['profile_photo_emty']); ?>
        <?php endif; ?>

        
        <?php if(isset($_SESSION['profile_photo_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?= $_SESSION['profile_photo_success'] ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php unset($_SESSION['profile_photo_success']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-info text-white">
                <h4>Change Profile Image</h4>
            </div>
            <div class="card-body text-center">
                <img class="mb-3 img-thumbnail" src="image/users/<?= htmlspecialchars($row['photo']) ?>" alt="profile image" width='200'>
                <form action="profile_img_change.php?id=<?= base64_encode($id) ?>" enctype="multipart/form-data" method="post">
                    <div class="form-group">
                        <input type="file" name="photo" class="form-control-file" required>
                    </div>
                    <input class="btn btn-block btn-info" type="submit" name="photo_submit" value="Change Photo">
                </form>
            </div>
        </div>
    </div>
</div>
<?php 
require_once "footer.php";
?>