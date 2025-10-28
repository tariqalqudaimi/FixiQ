<?php
// manage_about.php

require_once "../UserSetting/user_auth.php"; 
require_once '../Database/db.php';

// --- (منطق المعالجة PHP يبقى كما هو تماماً) ---
if (isset($_POST['update_main_description'])) {
    $details_en = $_POST['details_en'];
    $details_ar = $_POST['details_ar'];
    $id = $_POST['id'];
    $stmt = $dbcon->prepare("UPDATE about_sections SET details = ?, details_ar = ? WHERE id = ? AND section_key = 'main_description'");
    $stmt->bind_param("ssi", $details_en, $details_ar, $id);
    $stmt->execute();
    header("Location: manage_about.php?status=desc_updated");
    exit();
}
if (isset($_POST['add_section'])) {
    $title_en = $_POST['title'];
    $title_ar = $_POST['title_ar'];
    $details_en = $_POST['details'];
    $details_ar = $_POST['details_ar'];
    $icon_class = $_POST['icon_class'];
    $stmt = $dbcon->prepare("INSERT INTO about_sections (title, title_ar, details, details_ar, icon_class) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title_en, $title_ar, $details_en, $details_ar, $icon_class);
    $stmt->execute();
    header("Location: manage_about.php?status=added");
    exit();
}
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $dbcon->prepare("DELETE FROM about_sections WHERE id = ? AND section_key IS NULL");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: manage_about.php?status=deleted");
    exit();
}
// --- (نهاية منطق المعالجة) ---


// --- جلب البيانات للعرض ---
$main_description_row = $dbcon->query("SELECT * FROM about_sections WHERE section_key = 'main_description' LIMIT 1")->fetch_assoc();
if (!$main_description_row) {
    $dbcon->query("INSERT INTO about_sections (section_key, title, title_ar) VALUES ('main_description', 'Main Description', 'الوصف الرئيسي')");
    $main_description_row = $dbcon->query("SELECT * FROM about_sections WHERE section_key = 'main_description' LIMIT 1")->fetch_assoc();
}
$sections_result = $dbcon->query("SELECT * FROM about_sections WHERE section_key IS NULL ORDER BY id ASC");

$title = "Manage About Us Page";
require_once "../Dashboard/header.php"; 
?>

<!-- النموذج الأول: خاص بالوصف الرئيسي -->
<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Main Description</h4></div>
    <div class="card-body">
        <form action="manage_about.php" method="post">
            <input type="hidden" name="id" value="<?= $main_description_row['id'] ?>">
            <div class="form-group">
                <label>Description (English)</label>
                <!-- استخدام الكلاس الجديد ckeditor-editor -->
                <textarea class="form-control ckeditor-editor" name="details_en" rows="5"><?= $main_description_row['details'] ?? '' ?></textarea>
            </div>
            <div class="form-group">
                <label>Description (Arabic)</label>
                <!-- استخدام الكلاس الجديد ckeditor-editor -->
                <textarea class="form-control ckeditor-editor" name="details_ar" rows="5"><?= $main_description_row['details_ar'] ?? '' ?></textarea>
            </div>
            <button type="submit" name="update_main_description" class="btn btn-success">Update Description</button>
        </form>
    </div>
</div>

<!-- النموذج الثاني: لإضافة الأقسام الأخرى -->
<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Section (Company, Goals, etc.)</h4></div>
    <div class="card-body">
        <form action="manage_about.php" method="post">
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Section Title (English)</label><input type="text" class="form-control" name="title" required></div></div>
                <div class="col-md-6"><div class="form-group"><label>Section Title (Arabic)</label><input type="text" class="form-control" name="title_ar" required></div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Details (English)</label><textarea class="form-control ckeditor-editor" name="details" rows="8"></textarea></div></div>
                <div class="col-md-6"><div class="form-group"><label>Details (Arabic)</label><textarea class="form-control ckeditor-editor" name="details_ar" rows="8"></textarea></div></div>
            </div>
            <div class="form-group">
                <label>Icon Class</label>
                <input type="text" class="form-control" name="icon_class" placeholder="e.g., bx bx-buildings">
                <small class="form-text text-muted">Enter the CSS class for the icon (e.g., from BoxIcons).</small>
            </div>
            <button type="submit" name="add_section" class="btn btn-primary">Add Section</button>
        </form>
    </div>
</div>

<!-- الجدول: لعرض الأقسام -->
<div class="card">
    <div class="card-header"><h4 class="card-title">Existing Sections</h4></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Icon</th><th>Title</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if ($sections_result->num_rows > 0): ?>
                    <?php foreach ($sections_result as $section) : ?>
                        <tr>
                            <td><i class="<?= htmlspecialchars($section['icon_class']) ?>" style="font-size: 24px;"></i></td>
                            <td><?= htmlspecialchars($section['title']) ?></td>
                            <td>
                                <a href="about_edit.php?id=<?= $section['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                <a href="manage_about.php?delete_id=<?= $section['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center">No sections added yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once "../Dashboard/footer.php";
?>