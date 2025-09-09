<?php
// category_edit.php

// --- SETUP AND SECURITY ---
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';
ob_start();

// --- HANDLE THE FORM SUBMISSION (UPDATE LOGIC) ---
if (isset($_POST['update_category'])) {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];

    // Recalculate the filter tag based on the new name, just like when adding
    $filter_tag = "filter-" . strtolower(preg_replace('/[^a-z0-9]+/', '-', $name));

    // Update the database
    $stmt = $dbcon->prepare("UPDATE product_categories SET name = ?, filter_tag = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $filter_tag, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the main category list
    header('Location: manage_categories.php');
    exit();
}

// --- GET THE DATA FOR THE CATEGORY TO BE EDITED ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_categories.php');
    exit();
}
$id_to_edit = (int)$_GET['id'];

// Fetch the category's current data from the database
$stmt = $dbcon->prepare("SELECT * FROM product_categories WHERE id = ?");
$stmt->bind_param("i", $id_to_edit);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
$stmt->close();

// If no category with that ID exists, redirect back
if (!$category) {
    header('Location: manage_categories.php');
    exit();
}

// --- PAGE SETUP AND HTML FORM ---
$title = "Edit Category";
require_once "../Dashboard/header.php"; 
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Category: <?= htmlspecialchars($category['name']) ?></h4>
    </div>
    <div class="card-body">
        <form action="category_edit.php?id=<?= $id_to_edit ?>" method="post">
            <!-- Hidden input to pass the ID during submission -->
            <input type="hidden" name="id" value="<?= $id_to_edit ?>">

            <div class="form-group">
                <label>Category Name</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>
            
            <hr>
            <div class="text-right">
                 <a href="manage_categories.php" class="btn btn-secondary">Cancel</a>
                 <button type="submit" name="update_category" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php
require_once "../Dashboard/footer.php";
?>