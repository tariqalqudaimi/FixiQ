<?php

require_once "../UserSetting/user_auth.php"; 
require_once '../Database/db.php';
ob_start();

if (isset($_POST['update_member'])) {
    $member_id = (int)$_POST['member_id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $website_url = $_POST['website_url'];
    $facebook_url = $_POST['facebook_url'];
    $instagram_url = $_POST['instagram_url'];
    $linkedin_url = $_POST['linkedin_url'];

    $stmt_img = $dbcon->prepare("SELECT image_file FROM team_members WHERE id = ?");
    $stmt_img->bind_param("i", $member_id);
    $stmt_img->execute();
    $current_image = $stmt_img->get_result()->fetch_assoc()['image_file'];
    $stmt_img->close();

    $new_image_name = $current_image;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        if ($current_image != 'default_avatar.png') {
            $old_filepath = '../../assets/img/team/' . $current_image;
            if (file_exists($old_filepath)) {
                unlink($old_filepath);
            }
        }
        $target_dir = "../../assets/img/team/";
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image_name = "team_updated_" . time() . "." . $file_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $new_image_name);
    }
    
    $stmt_update = $dbcon->prepare("UPDATE team_members SET name=?, position=?, image_file=?, website_url=?, facebook_url=?, instagram_url=?, linkedin_url=? WHERE id=?");
    $stmt_update->bind_param("sssssssi", $name, $position, $new_image_name, $website_url, $facebook_url, $instagram_url, $linkedin_url, $member_id);
    $stmt_update->execute();
    $stmt_update->close();

    header('Location: manage_team.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_team.php');
    exit();
}
$id_to_edit = (int)$_GET['id'];

$stmt = $dbcon->prepare("SELECT * FROM team_members WHERE id = ?");
$stmt->bind_param("i", $id_to_edit);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$member) {
    header('Location: manage_team.php');
    exit();
}

$title = "Edit Team Member";
require_once "../Dashboard/header.php"; 
?>

<div class="card">
    <div class="card-header"><h4 class="card-title">Edit Member: <?= htmlspecialchars($member['name']) ?></h4></div>
    <div class="card-body">
        <form action="team_edit.php?id=<?= $id_to_edit ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="member_id" value="<?= $id_to_edit ?>">

            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Name</label><input type="text" class="form-control" name="name" value="<?= htmlspecialchars($member['name']) ?>" required></div></div>
                <div class="col-md-6"><div class="form-group"><label>Position</label><input type="text" class="form-control" name="position" value="<?= htmlspecialchars($member['position']) ?>" required></div></div>
            </div>
            
            <div class="form-group">
                <label>Current Image</label>
                <div>
                    <img src="../../assets/img/team/<?= htmlspecialchars($member['image_file']) ?>" alt="Current Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                </div>
                <label class="mt-2">Upload New Image (Optional)</label>
                <input type="file" class="form-control-file" name="image">
                <small class="form-text text-muted">Leave blank to keep the current image.</small>
            </div>
            <hr>
            
            <p class="text-muted">Social Media Links (Optional)</p>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Website URL</label><input type="url" class="form-control" name="website_url" value="<?= htmlspecialchars($member['website_url']) ?>"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Facebook URL</label><input type="url" class="form-control" name="facebook_url" value="<?= htmlspecialchars($member['facebook_url']) ?>"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Instagram URL</label><input type="url" class="form-control" name="instagram_url" value="<?= htmlspecialchars($member['instagram_url']) ?>"></div></div>
                <div class="col-md-6"><div class="form-group"><label>LinkedIn URL</label><input type="url" class="form-control" name="linkedin_url" value="<?= htmlspecialchars($member['linkedin_url']) ?>"></div></div>
            </div>
            
            <hr>
            <div class="text-right">
                <a href="manage_team.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_member" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php require_once "../Dashboard/footer.php"; ?>