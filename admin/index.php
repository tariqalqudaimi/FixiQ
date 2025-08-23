<?php
require_once "user_auth.php";
$title = "Dashboard";
require_once "header.php";
require_once "db.php";

// Fetch Logged-in User Data
$email = $_SESSION['user_email'];
$stmt = $dbcon->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

// Fetch Company Settings
$settings_data = $dbcon->query("SELECT * FROM company_settings WHERE id=1")->fetch_assoc();

// Fetch Stats for Widgets
$total_users = $dbcon->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_services = $dbcon->query("SELECT COUNT(*) AS total FROM services")->fetch_assoc()['total'];
$total_products = $dbcon->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];

?>

<div class="row">
    <div class="col-xl-4">
        <div class="card-box">
            <h4 class="header-title mt-1">Company Information</h4>
            <hr>
            <p class="text-muted font-13"><strong>Company Name:</strong> <?= htmlspecialchars($settings_data['company_name']) ?></p>
            <p class="text-muted font-13"><strong>Hero Title:</strong> <?= htmlspecialchars($settings_data['hero_title']) ?></p>
            <hr/>
            <ul class="social-links list-inline mt-4 mb-0 mx-auto">
                <li class="list-inline-item"><a title="Facebook" href="<?= htmlspecialchars($settings_data['fb_link']) ?>"><i class="fab fa-facebook-f"></i></a></li>
                <li class="list-inline-item"><a title="Twitter" href="<?= htmlspecialchars($settings_data['twitter_link']) ?>"><i class="fab fa-twitter"></i></a></li>
                <li class="list-inline-item"><a title="Instagram" href="<?= htmlspecialchars($settings_data['instagram_link']) ?>"><i class="fab fa-instagram"></i></a></li>
                <li class="list-inline-item"><a title="Linkedin" href="<?= htmlspecialchars($settings_data['linkedin_link']) ?>"><i class="fab fa-linkedin"></i></a></li>
            </ul>
            <br>
            <a class="btn btn-sm btn-block btn-success" href="edit_company_info.php">Edit Website Content</a>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="row">
            <div class="col-sm-4">
                <div class="card-box tilebox-one"><i class="mdi mdi-account-multiple-outline float-right text-muted"></i>
                    <h6 class="text-muted text-uppercase mt-0">Users</h6>
                    <h2 data-plugin="counterup"><?= $total_users ?></h2>
                </div>
                <a class="btn btn-sm btn-block btn-primary" href="users.php">Manage Users</a>
            </div>

            <div class="col-sm-4">
                <div class="card-box tilebox-one"><i class="dripicons-briefcase float-right text-muted"></i>
                    <h6 class="text-muted text-uppercase mt-0">Services</h6>
                    <h2><span data-plugin="counterup"><?= $total_services ?></span></h2>
                </div>
                <a class="btn btn-sm btn-block btn-primary" href="manage_services.php">Manage Services</a>
            </div>

            <div class="col-sm-4">
                <div class="card-box tilebox-one"><i class="dripicons-cart float-right text-muted"></i>
                    <h6 class="text-muted text-uppercase mt-0">Products</h6>
                    <h2><span data-plugin="counterup"><?= $total_products ?></span></h2>
                </div>
                <a class="btn btn-sm btn-block btn-primary" href="manage_products.php">Manage Products</a>
            </div>
        </div>
    </div>
</div>
<?php require_once "footer.php"; ?>