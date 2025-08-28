<?php
// STEP 1: Includes and form processing logic
require_once "user_auth.php";
require_once "db.php";

// Handle Add Team Member form
if (isset($_POST['add_member'])) {
    // ... (Your existing 'add_member' code remains unchanged)
    $name = $_POST['name'];
    $position = $_POST['position'];
    $twitter_url = $_POST['twitter_url'];
    $facebook_url = $_POST['facebook_url'];
    $instagram_url = $_POST['instagram_url'];
    $linkedin_url = $_POST['linkedin_url'];

    $image_name = 'default_avatar.png';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = "../assets/img/team/";
        if(!is_dir($target_dir)){ mkdir($target_dir, 0755, true); }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = "team_".time().".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
    }

    $stmt = $dbcon->prepare("INSERT INTO team_members (name, position, image_file, twitter_url, facebook_url, instagram_url, linkedin_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $position, $image_name, $twitter_url, $facebook_url, $instagram_url, $linkedin_url);
    $stmt->execute();

    header('Location: manage_team.php');
    exit();
}

// Handle Edit Team Member form
if (isset($_POST['edit_member'])) {
    $member_id = $_POST['member_id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $twitter_url = $_POST['twitter_url'];
    $facebook_url = $_POST['facebook_url'];
    $instagram_url = $_POST['instagram_url'];
    $linkedin_url = $_POST['linkedin_url'];

    // Handle image update
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // (Optional but recommended) Delete old image
        $old_img_stmt = $dbcon->prepare("SELECT image_file FROM team_members WHERE id = ?");
        $old_img_stmt->bind_param('i', $member_id);
        $old_img_stmt->execute();
        $result = $old_img_stmt->get_result()->fetch_assoc();
        if ($result && $result['image_file'] != 'default_avatar.png') {
            unlink("../assets/img/team/" . $result['image_file']);
        }
        $old_img_stmt->close();
        
        // Upload new image
        $target_dir = "../assets/img/team/";
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = "team_updated_" . time() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);

        $stmt = $dbcon->prepare("UPDATE team_members SET name=?, position=?, image_file=?, twitter_url=?, facebook_url=?, instagram_url=?, linkedin_url=? WHERE id=?");
        $stmt->bind_param("sssssssi", $name, $position, $image_name, $twitter_url, $facebook_url, $instagram_url, $linkedin_url, $member_id);
    } else {
        // Update without changing the image
        $stmt = $dbcon->prepare("UPDATE team_members SET name=?, position=?, twitter_url=?, facebook_url=?, instagram_url=?, linkedin_url=? WHERE id=?");
        $stmt->bind_param("ssssssi", $name, $position, $twitter_url, $facebook_url, $instagram_url, $linkedin_url, $member_id);
    }
    $stmt->execute();

    header('Location: manage_team.php');
    exit();
}

// Fetch all team members
$team_members_result = $dbcon->query("SELECT * FROM team_members ORDER BY display_order ASC, id DESC");


// STEP 2: Start the HTML page
$title = "Manage Team";
require_once "header.php";
?>

<!-- Add New Member Card (no changes needed here) -->
<div class="card mb-4">
    <!-- ... Your existing "Add New Team Member" form ... -->
    <div class="card-header"><h4 class="card-title">Add New Team Member</h4></div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Member Name</label><input type="text" class="form-control" name="name" required></div></div>
                <div class="col-md-6"><div class="form-group"><label>Position / Title</label><input type="text" class="form-control" name="position" required></div></div>
            </div>
            <div class="form-group"><label>Member Image</label><input type="file" class="form-control-file" name="image" required></div><hr><p class="text-muted">Social Media Links (Optional)</p>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Twitter URL</label><input type="url" class="form-control" name="twitter_url"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Facebook URL</label><input type="url" class="form-control" name="facebook_url"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Instagram URL</label><input type="url" class="form-control" name="instagram_url"></div></div>
                <div class="col-md-6"><div class="form-group"><label>LinkedIn URL</label><input type="url" class="form-control" name="linkedin_url"></div></div>
            </div>
            <button type="submit" name="add_member" class="btn btn-primary">Add Member</button>
        </form>
    </div>
</div>

<!-- Existing Members Table -->
<div class="card">
    <div class="card-header"><h4 class="card-title">Existing Team Members</h4></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Image</th><th>Name</th><th>Position</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($team_members_result as $member) : ?>
                    <tr>
                        <td><img src="../assets/img/team/<?= htmlspecialchars($member['image_file']) ?>" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;"></td>
                        <td><?= htmlspecialchars($member['name']) ?></td>
                        <td><?= htmlspecialchars($member['position']) ?></td>
                        <td>
                            <!-- EDIT BUTTON that triggers the modal -->
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editTeamModal-<?= $member['id'] ?>">
                                Edit
                            </button>
                            <a href="team_delete.php?id=<?= $member['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>

                    <!-- EDIT MODAL for each team member -->
                    <div class="modal fade" id="editTeamModal-<?= $member['id'] ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Member: <?= htmlspecialchars($member['name']) ?></h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="member_id" value="<?= $member['id'] ?>">
                                        <div class="row">
                                            <div class="col-md-6"><div class="form-group"><label>Name</label><input type="text" class="form-control" name="name" value="<?= htmlspecialchars($member['name']) ?>" required></div></div>
                                            <div class="col-md-6"><div class="form-group"><label>Position</label><input type="text" class="form-control" name="position" value="<?= htmlspecialchars($member['position']) ?>" required></div></div>
                                        </div>
                                        <div class="form-group"><label>New Image (Optional - leave blank to keep current)</label><input type="file" class="form-control-file" name="image"></div><hr>
                                        <p class="text-muted">Social Media Links (Optional)</p>
                                        <div class="row">
                                            <div class="col-md-6"><div class="form-group"><label>Twitter</label><input type="url" class="form-control" name="twitter_url" value="<?= htmlspecialchars($member['twitter_url']) ?>"></div></div>
                                            <div class="col-md-6"><div class="form-group"><label>Facebook</label><input type="url" class="form-control" name="facebook_url" value="<?= htmlspecialchars($member['facebook_url']) ?>"></div></div>
                                            <div class="col-md-6"><div class="form-group"><label>Instagram</label><input type="url" class="form-control" name="instagram_url" value="<?= htmlspecialchars($member['instagram_url']) ?>"></div></div>
                                            <div class="col-md-6"><div class="form-group"><label>LinkedIn</label><input type="url" class="form-control" name="linkedin_url" value="<?= htmlspecialchars($member['linkedin_url']) ?>"></div></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="edit_member" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once "footer.php"; ?>