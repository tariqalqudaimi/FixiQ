<?php
// STEP 1: All PHP logic (authentication, DB connection, form processing) goes first.
require_once "user_auth.php";
require_once "db.php";

// Handle form submissions for adding or deleting stats BEFORE sending any HTML.
if(isset($_POST['add_stat'])){
    $title = $_POST['title'];
    $count = $_POST['count'];
    $stmt = $dbcon->prepare("INSERT INTO site_stats (title, count) VALUES (?, ?)");
    $stmt->bind_param("si", $title, $count);
    $stmt->execute();
    
    // This header() call will now work perfectly.
    header('Location: manage_stats.php');
    exit();
}

if(isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    $stmt = $dbcon->prepare("DELETE FROM site_stats WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // This header() call will also work.
    header('Location: manage_stats.php');
    exit();
}

// Fetch data that will be displayed on the page.
$stats_result = $dbcon->query("SELECT * FROM site_stats ORDER BY id ASC");


// STEP 2: Now that all the logic is done, we can set the title and start printing the HTML page.
$title = "Manage Site Statistics";
require_once "header.php";
?>

<!-- STEP 3: The rest of the file is just the HTML for displaying the page content. -->

<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Statistic</h4></div>
    <div class="card-body">
        <form action="manage_stats.php" method="post">
            <div class="form-group">
                <label>Title (e.g., Happy Clients)</label>
                <input type="text" class="form-control" name="title" required>
            </div>
             <div class="form-group">
                <label>Number (e.g., 232)</label>
                <input type="number" class="form-control" name="count" required>
            </div>
            <button type="submit" name="add_stat" class="btn btn-primary">Add Stat</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-title">Existing Stats</h4></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Title</th><th>Count</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($stats_result as $stat) : ?>
                    <tr>
                        <td><?= htmlspecialchars($stat['title']) ?></td>
                        <td><?= htmlspecialchars($stat['count']) ?></td>
                        <td>
                            <a href="manage_stats.php?delete_id=<?= $stat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this stat?');">Delete</a>
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