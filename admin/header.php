<?php 
// We start session here if it's not already started. This is good practice.
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
require_once "db.php"; 
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon (from your original file) -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- CSS files from your original, working file to guarantee responsiveness -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <!-- Adding modern icons for the new menu -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

    <!-- JS from your original file -->
    <script src="assets/js/modernizr.min.js"></script>
</head>

<body>
    <!-- Begin page -->
    <div id="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <div class="slimscroll-menu" id="remove-scroll">
                
                <?php
                    // --- 1. SECURELY FETCH USER AND COUNT DATA ---
                    $email_for_photo = $_SESSION['user_email'];
                    
                    // This is the SECURE version of your original query
                    $stmt = $dbcon->prepare("SELECT photo, fname FROM users WHERE email=?");
                    $stmt->bind_param("s", $email_for_photo);
                    $stmt->execute();
                    $user_data = $stmt->get_result()->fetch_assoc();

                    // Update session name if not set
                    if (!isset($_SESSION['user_name'])) {
                        $_SESSION['user_name'] = $user_data['fname'];
                    }

                    // Fetch counts for menu badges
                    $user_count = $dbcon->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
                    $service_count = $dbcon->query("SELECT COUNT(*) AS total FROM services")->fetch_assoc()['total'];
                    $product_count = $dbcon->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
                    $unread_messages = $dbcon->query("SELECT COUNT(*) as count FROM contact_messages WHERE status = 0")->fetch_assoc()['count'];

                ?>

                <!-- User box (Structure is from your original file) -->
                <div class="user-box">
                    <div class="user-img">
                        <img src="image/users/<?= htmlspecialchars($user_data['photo']) ?>" alt="user-img" title="<?= htmlspecialchars($user_data['fname']) ?>" class="rounded-circle img-fluid">
                    </div>
                    <h5><a href="profile.php"><?= htmlspecialchars($_SESSION['user_name']) ?></a> </h5>
                    <p class="text-muted"><?= htmlspecialchars($_SESSION['user_email']) ?></p>
                </div>

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- ================================================================== -->
                    <!-- THE ONLY PART WE CHANGE: THE MENU ITSELF -->
                    <!-- ================================================================== -->
                    <ul class="metismenu" id="side-menu">
                        <li class="menu-title">Navigation</li>
                        <li><a href="index.php"><i class="fi-air-play"></i> <span> Dashboard </span></a></li>
                        
                        <li class="menu-title">Content Management</li>
                        <li><a href="edit_company_info.php"><i class="bi bi-building"></i> <span> Website Settings </span></a></li>
                        <li>
                            <a href="javascript: void(0);"><i class="bi bi-box-seam"></i><span class="badge badge-primary badge-pill float-right"><?= $product_count ?></span> <span> Products </span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="manage_products.php">Manage Products</a></li>
                                <li><a href="manage_categories.php">Manage Categories</a></li>
                                
                            </ul>
                        </li>
                        <li><a href="manage_services.php"><i class="bi bi-gear-wide-connected"></i><span class="badge badge-info badge-pill float-right"><?= $service_count ?></span> <span> Services </span></a></li>
                       <li><a href="manage_team.php"><i class="bi bi-user"></i> <span> Team </span></a></li>
                       <li><a href="manage_features.php"><i class="bi bi-star"></i> <span> Features </span></a></li>
                        <li><a href="manage_stats.php"><i class="bi bi-graph-up"></i> <span> Site Statistics </span></a></li>
                        <li><a href="manage_contact.php"><i class="bi bi-envelope-fill"></i> <span> Contact Info </span></a></li>
                        <li><a href="manage_messages.php"><i class="bi bi-envelope-fill"></i><span> Messages </span><?php if($unread_messages > 0): ?><span class="badge badge-danger badge-pill float-right"><?= $unread_messages ?></span><?php endif; ?></a></li>
                        
                        <li class="menu-title">Admin</li>
                        <li><a href="users.php"><i class="fas fa-users-cog"></i><span class="badge badge-danger badge-pill float-right"><?= $user_count ?></span> <span> Users </span></a></li>
                        <li><a href="profile.php"><i class="fi-head"></i> <span> My Profile </span></a></li>
                        <li><a href="change_password.php"><i class="fi-lock"></i> <span> Change Password </span></a></li>
                    </ul>
                </div>
                <!-- Sidebar -->
                <div class="clearfix"></div>
            </div>
            <!-- Sidebar -left -->
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <!-- Top Bar Start (Structure is from your original file) -->
            <div class="topbar">
                <nav class="navbar-custom">
                    <ul class="list-unstyled topbar-right-menu float-right mb-0">
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="image/users/<?= htmlspecialchars($user_data['photo']) ?>" alt="user" class="rounded-circle"> <span class="ml-1"><?= htmlspecialchars($_SESSION['user_name']) ?> <i class="mdi mdi-chevron-down"></i> </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                                <div class="dropdown-item noti-title"><h6 class="text-overflow m-0">Welcome !</h6></div>
                                <a href="profile.php" class="dropdown-item notify-item"><i class="fi-head"></i> <span>My Account</span></a>
                                <a href="logout.php" class="dropdown-item notify-item"><i class="fi-power"></i> <span>Logout</span></a>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left disable-btn">
                                <i class="dripicons-menu"></i>
                            </button>
                        </li>
                        <li>
                            <h4 class="page-title-main"><?= $title ?></h4>
                            <ol class="breadcrumb">
                                <!-- Changed this link to be more useful -->
                                <li class="breadcrumb-item"><a href="../index.php" target="_blank">View Live Site</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active"><?= $title ?></li>
                            </ol>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- Top Bar End -->

            <!-- Start Page content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">