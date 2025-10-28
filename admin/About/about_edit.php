<?php
// about_section_edit.php

require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';

// --- (منطق المعالجة PHP يبقى كما هو تماماً) ---
$section_id = $_GET['id'] ?? null;
if (!$section_id) { header("Location: manage_about.php"); exit(); }

if (isset($_POST['update_section'])) {
    $id = $_POST['section_id'];
    $title = $_POST['title'];
    $title_ar = $_POST['title_ar'];
    $details = $_POST['details'];
    $details_ar = $_POST['details_ar'];
    $icon_class = $_POST['icon_class'];
    $stmt = $dbcon->prepare("UPDATE about_sections SET title = ?, title_ar = ?, details = ?, details_ar = ?, icon_class = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $title, $title_ar, $details, $details_ar, $icon_class, $id);
    $stmt->execute();
    header("Location: manage_about.php?status=updated");
    exit();
}
// --- (نهاية منطق المعالجة) ---


// --- جلب بيانات القسم للتعديل ---
$stmt = $dbcon->prepare("SELECT * FROM about_sections WHERE id = ? AND section_key IS NULL");
$stmt->bind_param("i", $section_id);
$stmt->execute();
$result = $stmt->get_result();
$section = $result->fetch_assoc();
if (!$section) { header("Location: manage_about.php"); exit(); }

$title = "Edit About Section";
require_once "../Dashboard/header.php";
?>

<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Edit Section: <?= htmlspecialchars($section['title']) ?></h4></div>
    <div class="card-body">
        <form action="about_edit.php?id=<?= $section['id'] ?>" method="post">
            <input type="hidden" name="section_id" value="<?= $section['id'] ?>">
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Section Title (English)</label><input type="text" class="form-control" name="title" value="<?= htmlspecialchars($section['title']) ?>" required></div></div>
                <div class="col-md-6"><div class="form-group"><label>Section Title (Arabic)</label><input type="text" class="form-control" name="title_ar" value="<?= htmlspecialchars($section['title_ar']) ?>" required></div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Details (English)</label>
                    <!-- استخدام الكلاس الجديد وإزالة htmlspecialchars -->
                    <textarea class="form-control ckeditor-editor" name="details" rows="8"><?= $section['details'] ?></textarea>
                </div></div>
                <div class="col-md-6"><div class="form-group"><label>Details (Arabic)</label>
                    <!-- استخدام الكلاس الجديد وإزالة htmlspecialchars -->
                    <textarea class="form-control ckeditor-editor" name="details_ar" rows="8"><?= $section['details_ar'] ?></textarea>
                </div></div>
            </div>
            <div class="form-group">
                <label>Icon Class</label>
                <input type="text" class="form-control" name="icon_class" value="<?= htmlspecialchars($section['icon_class']) ?>" placeholder="e.g., bx bx-buildings">
            </div>
            <button type="submit" name="update_section" class="btn btn-success">Update Section</button>
            <a href="manage_about.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php
require_once "../Dashboard/footer.php";
?>