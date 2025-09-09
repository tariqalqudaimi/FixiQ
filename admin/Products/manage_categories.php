<?php
require_once "../UserSetting/user_auth.php";
$title = "Manage Product Categories";
require_once '../Database/db.php';

if(isset($_POST['add_category'])){
    $name = $_POST['name'];
    $filter_tag = "filter-" . strtolower(preg_replace('/[^a-z0-9]+/', '-', $name));
    
    $stmt = $dbcon->prepare("INSERT INTO product_categories (name, filter_tag) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $filter_tag);
    $stmt->execute();
    
    header('Location: manage_categories.php');
    exit();
}

if(isset($_GET['delete_id'])){
    $id = (int)$_GET['delete_id']; 
    $stmt = $dbcon->prepare("DELETE FROM product_categories WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header('Location: manage_categories.php');
    exit();
}

$categories_result = $dbcon->query("SELECT * FROM product_categories ORDER BY name ASC");


require_once "../Dashboard/header.php";
?>

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
                            <a href="category_edit.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                            
                            <a href="manage_categories.php?delete_id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category? This cannot be undone.');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once "../Dashboard/footer.php";
?>