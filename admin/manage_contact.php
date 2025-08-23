<?php
// STEP 1: All PHP logic goes first.
require_once "user_auth.php";
require_once "db.php";
ob_start();

// Handle form submission for updating contact info
if (isset($_POST['submit'])) {
    // This UPDATE query now uses the 'map_embed_code' column
    $stmt = $dbcon->prepare("UPDATE contact_information SET
        address = ?,
        email = ?,
        phone = ?,
        map_embed_code = ?
        WHERE id=1");

    // The bind_param now has 4 's' for 4 string values
    $stmt->bind_param("ssss",
        $_POST['address'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['map_embed_code'] // Use the correct POST variable
    );

    if ($stmt->execute()) {
        $_SESSION['update_success'] = "Contact information updated successfully!";
    } else {
        $_SESSION['update_error'] = "Error updating information: " . $stmt->error;
    }

    header('Location: manage_contact.php');
    exit();
}

// Fetch the existing contact data to pre-fill the form
$contact = $dbcon->query("SELECT * FROM contact_information WHERE id=1")->fetch_assoc();


// STEP 2: Now we can start the HTML page.
$title = "Manage Contact Page";
require_once "header.php";
?>

<!-- STEP 3: HTML content -->
<div class="card">
    <div class="card-header"><h4 class="card-title">Edit Contact Page Details</h4></div>
    <div class="card-body">

        <?php if (isset($_SESSION['update_success'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?= $_SESSION['update_success'] ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['update_success']); ?>
        <?php endif; ?>

        <form action="manage_contact.php" method="post">
            <h5 class="mt-3">Primary Contact Info</h5>
            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($contact['address'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($contact['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($contact['phone'] ?? '') ?>">
            </div>
            
            <hr>
            <h5 class="mt-3">Google Maps Embed</h5>
            <div class="form-group">
                <label>Full Google Maps "Embed a map" Code</label>
                <textarea class="form-control" name="map_embed_code" rows="6" placeholder='Paste the full <iframe> code from Google Maps here.'><?= htmlspecialchars($contact['map_embed_code'] ?? '') ?></textarea>
                <small class="form-text text-muted">
                    1. Go to Google Maps and find your location.<br>
                    2. Click "Share", then go to the "Embed a map" tab.<br>
                    3. Click "COPY HTML" and paste the entire code here.
                </small>
            </div>

            <button type="submit" name="submit" class="btn btn-primary mt-3">Save Contact Info</button>
        </form>
    </div>
</div>

<?php
require_once "footer.php";
?>