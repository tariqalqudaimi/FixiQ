<?php
require_once "user_auth.php";
$title = "Edit Company Information";
require_once "header.php";
require_once "db.php";

$data_from_db = $dbcon->query("SELECT * FROM company_settings WHERE id=1");
$settings = $data_from_db->fetch_assoc();
?>
<div class="card">
    <div class="card-header bg-success text-center">
        <h2>Edit Website Content & Settings</h2>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['update_success'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?= $_SESSION['update_success'] ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php unset($_SESSION['update_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['update_error'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?= $_SESSION['update_error'] ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php unset($_SESSION['update_error']); ?>
        <?php endif; ?>

        <form action="update_company_info.php" method="post" enctype="multipart/form-data">
            <!-- Hero Section -->
            <h4>Hero Section</h4>
            <div class="form-group"><label>Hero Title</label><input type="text" class="form-control" name="hero_title" value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>"></div>
            <div class="form-group"><label>Hero Title Arabic</label><input type="text" class="form-control" name="hero_title_ar" value="<?= htmlspecialchars($settings['hero_title_ar'] ?? '') ?>"></div>
            <div class="form-group"><label>Hero Subtitle </label><textarea name="hero_subtitle" class="form-control"><?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?></textarea></div>
            <div class="form-group"><label>Hero Subtitle Arabic</label><textarea name="hero_subtitle_ar" class="form-control"><?= htmlspecialchars($settings['hero_subtitle_ar'] ?? '') ?></textarea></div>
            <div class="form-group"><label>Hero Image</label><input type="file" class="form-control-file" name="hero_image"><small>Current: <?= htmlspecialchars($settings['hero_image']) ?></small></div>
            <hr>

            <!-- About Section -->
            <h4>About Section</h4>
            <div class="form-group"><label>About Title</label><input type="text" class="form-control" name="about_title" value="<?= htmlspecialchars($settings['about_title'] ?? '') ?>"></div>
            <div class="form-group"><label>About Subtitle</label><textarea name="about_subtitle" class="form-control"><?= htmlspecialchars($settings['about_subtitle'] ?? '') ?></textarea></div>
            <div class="form-group"><label>About Image</label><input type="file" class="form-control-file" name="about_image"><small>Current: <?= htmlspecialchars($settings['about_image']) ?></small></div>
            <hr>

            <!-- Social Links -->
            <h4>Social Links (for Footer)</h4>
            <div class="form-group"><label>Facebook Link</label><input type="url" class="form-control" name="fb_link" value="<?= htmlspecialchars($settings['fb_link'] ?? '') ?>"></div>
            <div class="form-group"><label>WhatsApp Number</label><input type="text" class="form-control" name="whatsapp_number" placeholder="e.g., +15551234567" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '') ?>"><small class="form-text text-muted">Include the country code (e.g., +1, +44).</small></div>
            <div class="form-group"><label>Instagram Link</label><input type="url" class="form-control" name="instagram_link" value="<?= htmlspecialchars($settings['instagram_link'] ?? '') ?>"></div>
            <div class="form-group"><label>LinkedIn Link</label><input type="url" class="form-control" name="linkedin_link" value="<?= htmlspecialchars($settings['linkedin_link'] ?? '') ?>"></div>

            <div class="form-group"><input class="btn btn-block btn-success" type="submit" value="Save All Changes" name="submit"></div>

        </form>
    </div>
</div>
<?php require_once "footer.php"; ?>