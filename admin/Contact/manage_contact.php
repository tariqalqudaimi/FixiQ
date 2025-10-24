<?php
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';ob_start();

if (isset($_POST['submit'])) {
    $stmt = $dbcon->prepare("UPDATE contact_information SET
        address = ?,
        
        email = ?,
        phone = ?
        WHERE id=1");

    $stmt->bind_param("sss",
        $_POST['address'],
       
        $_POST['email'],
        $_POST['phone']
    );

    if ($stmt->execute()) {
        $_SESSION['update_success'] = "Contact information updated successfully!";
    } else {
        $_SESSION['update_error'] = "Error updating information: " . $stmt->error;
    }

    header('Location: manage_contact.php');
    exit();
}

$contact = $dbcon->query("SELECT * FROM contact_information WHERE id=1")->fetch_assoc();

$title = "Manage Contact Page";
require_once "../Dashboard/header.php";
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Contact Page Details</h4>
    </div>
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
                <label>Address (English)</label>
                <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($contact['address'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Address (Arabic)</label>
                <input type="text" class="form-control" name="address_ar" value="<?= htmlspecialchars($contact['address_ar'] ?? '') ?>" dir="rtl">
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($contact['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($contact['phone'] ?? '') ?>">
            </div>

            <button type="submit" name="submit" class="btn btn-primary mt-3">Save Contact Info</button>
        </form>
    </div>
</div>

<?php
require_once "../Dashboard/footer.php";
?>