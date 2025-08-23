<?php
require_once "user_auth.php";
$title = "About Me Overview";
require_once "header.php";
require_once "db.php";

$about_me_result = $dbcon->query("SELECT * FROM about_me WHERE id=1");
$result = $about_me_result->fetch_assoc();
?>

<div class="card text-dark mb-3">
    <div class="card-header bg-success text-center"><h3>About Me Details</h3></div>

    <div class="card-body">
        <?php if (isset($_SESSION['about_data_success'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?= $_SESSION['about_data_success'] ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php unset($_SESSION['about_data_success']); ?>
        <?php endif; ?>

        <table class="table table-bordered table-striped">
            <!-- Profile Photo -->
            <tr>
                <td colspan="2" class="text-center">
                    <img src="../admin/image/profile/<?= htmlspecialchars($result['photo']) ?>" style="width: 150px;" class="img-thumbnail">
                </td>
            </tr>

            <!-- Personal Info -->
            <tr><th width="20%">Field</th><th>Details</th></tr>
            <tr>
                <th>Name</th>
                <td>
                    <strong>EN:</strong> <?= htmlspecialchars($result['name']) ?><br>
                    <strong>AR:</strong> <span dir="rtl"><?= htmlspecialchars($result['name_ar']) ?></span>
                </td>
            </tr>
            <tr>
                <th>Intro</th>
                <td>
                    <strong>EN:</strong> <?= nl2br(htmlspecialchars($result['intro'])) ?><br><hr>
                    <strong>AR:</strong> <span dir="rtl"><?= nl2br(htmlspecialchars($result['intro_ar'])) ?></span>
                </td>
            </tr>
            <tr>
                <th>Details</th>
                <td>
                    <strong>EN:</strong> <?= nl2br(htmlspecialchars($result['details'])) ?><br><hr>
                    <strong>AR:</strong> <span dir="rtl"><?= nl2br(htmlspecialchars($result['details_ar'])) ?></span>
                </td>
            </tr>
            <tr>
                <th>Birthdate</th>
                <td><?= htmlspecialchars($result['birthdate']) ?></td>
            </tr>
            <tr>
            <th>Location</th>
            <td>
               <strong>EN:</strong> <?= htmlspecialchars($result['location']) ?><br>
               <strong>AR:</strong> <span dir="rtl"><?= htmlspecialchars($result['location_ar']) ?></span>
            </td>
            </tr>
            <tr>
            <th>Personality Type</th>
            <td>
               <strong>EN:</strong> <?= htmlspecialchars($result['personality_type']) ?><br>
               <strong>AR:</strong> <span dir="rtl"><?= htmlspecialchars($result['personality_type_ar']) ?></span>
            </td>
            </tr>

            <!-- CV Files -->
            <tr>
                <th>CV Files</th>
                <td>
                    <strong>English CV:</strong>
                    <?php if (!empty($result['cv_file_en'])) : ?>
                        <a href="cvs/<?= htmlspecialchars($result['cv_file_en']) ?>" target="_blank"><?= htmlspecialchars($result['cv_file_en']) ?></a>
                    <?php else : ?>
                        <span class="text-muted">Not uploaded.</span>
                    <?php endif; ?>
                    <br>
                    <strong>Arabic CV:</strong>
                    <?php if (!empty($result['cv_file_ar'])) : ?>
                        <a href="cvs/<?= htmlspecialchars($result['cv_file_ar']) ?>" target="_blank"><?= htmlspecialchars($result['cv_file_ar']) ?></a>
                    <?php else : ?>
                        <span class="text-muted">Not uploaded.</span>
                    <?php endif; ?>
                </td>
            </tr>

            <!-- Social Links -->
            <tr><th colspan="2" class="text-center bg-light">Social Media Links</th></tr>
            <tr>
                <th>Facebook Link</th>
                <td><a href="<?= htmlspecialchars($result['fb_link']) ?>" target="_blank"><?= htmlspecialchars($result['fb_link']) ?></a></td>
            </tr>
            <tr>
                <th>Github Link</th>
                <td><a href="<?= htmlspecialchars($result['github_link']) ?>" target="_blank"><?= htmlspecialchars($result['github_link']) ?></a></td>
            </tr>
            <tr>
                <th>Twitter Link</th>
                <td><a href="<?= htmlspecialchars($result['twitter_link']) ?>" target="_blank"><?= htmlspecialchars($result['twitter_link']) ?></a></td>
            </tr>
            <tr>
                <th>LinkedIn Link</th>
                <td><a href="<?= htmlspecialchars($result['linkedin_link']) ?>" target="_blank"><?= htmlspecialchars($result['linkedin_link']) ?></a></td>
            </tr>
        </table>

        <a class="btn btn-block btn-primary mt-3" href="about_me.php">Edit All Details</a>
    </div>
</div>

<?php require_once "footer.php"; ?>