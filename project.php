<?php
require_once 'error_handler.php';
require_once 'admin/Database/db.php';

session_start();

// --- Language Logic ---
if (!isset($_SESSION['lang'])) {
  $_SESSION['lang'] = 'en';
}
if (isset($_GET['lang'])) {
  $_SESSION['lang'] = $_GET['lang'];
}
$current_lang = $_SESSION['lang'];
if (file_exists('lang/' . $current_lang . '.php')) {
  include 'lang/' . $current_lang . '.php';
} else {
  include 'lang/en.php';
}

$settings = $dbcon->query("SELECT * FROM company_settings WHERE id=1")->fetch_assoc();
$products_query = $dbcon->query("
    SELECT 
        p.id, p.name, p.image,
        GROUP_CONCAT(c.name SEPARATOR ', ') as category_names
    FROM 
        products p
    LEFT JOIN 
        product_category_map pcm ON p.id = pcm.product_id
    LEFT JOIN 
        product_categories c ON pcm.category_id = c.id
    GROUP BY 
        p.id
    ORDER BY 
        p.id DESC
");
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>" dir="<?= ($current_lang == 'ar' ? 'rtl' : 'ltr') ?>">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= htmlspecialchars($settings['company_name'] ?? 'Company Name') ?> - <?= $lang['project_link'] ?? 'Projects' ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="assets/img/Artboard 8-8.png" rel="icon">
  <link href="assets/img/Artboard 8-8.png" rel="aArtboard 8-8">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Sora:300,300i,400,400i,500,500i,600,600i,700,700i|Tajwal:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

  <?php include 'partials/header.php'; ?>

  <main id="main">

    <section id="project" class="project-page section-bg" style="padding-top: 120px;">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2><?= $lang['project_title'] ?? 'Our Projects' ?></h2>
          <p><?= $lang['project_description'] ?? 'Check out our beautiful projects.' ?></p>
        </div>

        <div class="row gy-4">
          <?php if ($products_query && $products_query->num_rows > 0): ?>
            <?php foreach ($products_query as $product): 
                $product_name = ($current_lang == 'ar' && !empty($product['name_ar'])) ? $product['name_ar'] : $product['name'];
                $product_desc = ($current_lang == 'ar' && !empty($product['description_ar'])) ? $product['description_ar'] : ($product['description'] ?? 'Default description.');
                $categories = !empty($product['category_names']) ? explode(', ', $product['category_names']) : [];
            ?>
              <div class="col-lg-4 col-md-6">
                <div class="wall-item" 
                     data-image="assets/img/portfolio/<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>"
                     data-title="<?= htmlspecialchars($product_name) ?>"
                     data-description="<?= htmlspecialchars($product_desc) ?>"
                     data-url="<?= htmlspecialchars($product['details_url'] , ENT_QUOTES, 'UTF-8') ?>"
                     data-categories="<?= htmlspecialchars(json_encode($categories)) ?>">
                    
                    <div class="item-image-container">
                        <div class="item-bg" style="background-image: url('assets/img/portfolio/<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>');"></div>
                    </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12 text-center">
                <p>No projects found.</p>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ===== Modal Structure (copied from index.php) ===== -->
  <div class="project-modal" id="projectModal">
      <div class="modal-backdrop"></div>
      <div class="modal-content">
          <button class="modal-close-btn"><i class='bx bx-x'></i></button>
          <div class="modal-body">
              <div class="modal-image"></div>
              <div class="modal-details">
                  <h2 class="modal-title"></h2>
                  <div class="modal-categories"></div>
                  <p class="modal-description"></p>
                  <a href="#" class="modal-link btn-visit-website" target="_blank"><?= $lang['visit_website_btn'] ?? 'Visit Website' ?></a>
              </div>
          </div>
      </div>
  </div>

  <?php // You might need to include a footer here if you have one, e.g., include 'partials/footer.php'; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>

</html>
