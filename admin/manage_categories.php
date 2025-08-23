<?php
// STEP 1: All PHP logic (authentication, DB connection, form processing) goes first.
require_once "user_auth.php";
$title = "Manage Product Categories"; // It's okay to set the $title variable here
require_once "db.php";

// Handle form submissions for adding or deleting categories BEFORE sending any HTML.
if(isset($_POST['add_category'])){
    $name = $_POST['name'];
    // Create a URL-friendly filter tag from the name
    $filter_tag = "filter-" . strtolower(preg_replace('/[^a-z0-9]+/', '-', $name));
    
    $stmt = $dbcon->prepare("INSERT INTO product_categories (name, filter_tag) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $filter_tag);
    $stmt->execute();
    
    // This header() call will now work perfectly.
    header('Location: manage_categories.php');
    exit();
}

if(isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    $stmt = $dbcon->prepare("DELETE FROM product_categories WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // This header() call will also work.
    header('Location: manage_categories.php');
    exit();
}

// Fetch data that will be displayed on the page.
$categories_result = $dbcon->query("SELECT * FROM product_categories ORDER BY name ASC");


// STEP 2: Now that all the logic is done, we can start printing the HTML page.
require_once "header.php";
?>

<!-- STEP 3: The rest of the file is just the HTML for displaying the page content. -->

<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Category</h4></div>
    <div class="card-body">
        <form action="manage_categories.php" method="post">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-title">Existing Categories</h4></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Name</th><th>Filter Tag (for website)</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($categories_result as $cat) : ?>
                    <tr>
                        <td><?= htmlspecialchars($cat['name']) ?></td>
                        <td><?= htmlspecialchars($cat['filter_tag']) ?></td>
                        <td>
                            <a href="manage_categories.php?delete_id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category? This cannot be undone.');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once "footer.php";
?>