<?php
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';

if (isset($_POST['add_member'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $website_url = $_POST['website_url']; 
    $facebook_url = $_POST['facebook_url'];
    $instagram_url = $_POST['instagram_url'];
    $linkedin_url = $_POST['linkedin_url'];

    $image_name = 'default_avatar.png';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = "../../assets/img/team/"; 
        if(!is_dir($target_dir)){ mkdir($target_dir, 0755, true); }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = "team_".time().".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
    }

    $stmt = $dbcon->prepare("INSERT INTO team_members (name, position, image_file, website_url, facebook_url, instagram_url, linkedin_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $position, $image_name, $website_url, $facebook_url, $instagram_url, $linkedin_url);
    $stmt->execute();

    header('Location: manage_team.php');
    exit();
}


$team_members_result = $dbcon->query("SELECT * FROM team_members ORDER BY display_order ASC, id DESC");

$title = "Manage Team";
require_once "../Dashboard/header.php";
?>

<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Team Member</h4></div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Member Name</label><input type="text" class="form-control" name="name" required></div></div>
                <div class="col-md-6"><div class="form-group"><label>Position / Title</label><input type="text" class="form-control" name="position" required></div></div>
            </div>
            <div class="form-group"><label>Member Image</label><input type="file" class="form-control-file" name="image" required></div><hr><p class="text-muted">Social Media Links (Optional)</p>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Website URL</label><input type="url" class="form-control" name="website_url"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Facebook URL</label><input type="url" class="form-control" name="facebook_url"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Instagram URL</label><input type="url" class="form-control" name="instagram_url"></div></div>
                <div class="col-md-6"><div class="form-group"><label>LinkedIn URL</label><input type="url" class="form-control" name="linkedin_url"></div></div>
            </div>
            <button type="submit" name="add_member" class="btn btn-primary">Add Member</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-title">Existing Team Members</h4></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Image</th><th>Name</th><th>Position</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($team_members_result as $member) : ?>
                    <tr>
                        <td><img src="../../assets/img/team/<?= htmlspecialchars($member['image_file']) ?>" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;"></td>
                        <td><?= htmlspecialchars($member['name']) ?></td>
                        <td><?= htmlspecialchars($member['position']) ?></td>
                        <td>
                            <a href="team_edit.php?id=<?= $member['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                            
                            <a href="team_delete.php?id=<?= $member['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<?php require_once "../Dashboard/footer.php"; ?>