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
  <link href="assets/img/Artboard 8-8.png" rel="apple-touch-icon">
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

  <!-- <?php include 'partials/header.php'; ?> -->

  <!-- The main container will now be the projects container itself -->
  <main id="immersive-showcase">
      <!-- Intro Slide -->
      <section class="immersive-slide is-intro">
          <div class="intro-content">
              <h2 class="section-title"><?= $lang['project_title'] ?? 'Our Projects' ?></h2>
              <p><?= $lang['project_description'] ?? 'Check out our beautiful projects.' ?></p>
              <div class="scroll-indicator">
                  <i class='bx bx-mouse'></i>
                  <span><?= ($current_lang == 'ar' ? 'مرّر لاستكشاف' : 'Scroll to Explore') ?></span>
              </div>
          </div>
      </section>

      <!-- Projects Slides -->
      <?php if ($products_query && $products_query->num_rows > 0): ?>
          <?php foreach ($products_query as $product): 
              $product_name = ($current_lang == 'ar' && !empty($product['name_ar'])) ? $product['name_ar'] : $product['name'];
              $product_desc = ($current_lang == 'ar' && !empty($product['description_ar'])) ? $product['description_ar'] : ($product['description'] ?? 'Default description.');
              $categories = !empty($product['category_names']) ? explode(', ', $product['category_names']) : [];
          ?>
              <section class="immersive-slide">
                  <div class="slide-background" style="background-image: url('assets/img/portfolio/<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>');"></div>
                  <div class="slide-overlay"></div>
                  <div class="slide-content-wrapper container">
                      <span class="slide-category"><?= htmlspecialchars(implode(' / ', $categories)) ?></span>
                      <h3 class="slide-title"><?= htmlspecialchars($product_name) ?></h3>
                      <p class="slide-description"><?= htmlspecialchars(substr($product_desc, 0, 200)) . (strlen($product_desc) > 200 ? '...' : '') ?></p>
                      <button class="slide-cta"
                              data-image="assets/img/portfolio/<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>"
                              data-title="<?= htmlspecialchars($product_name) ?>"
                              data-description="<?= htmlspecialchars($product_desc) ?>"
                              data-url="<?= htmlspecialchars($product['details_url'] ?? '#', ENT_QUOTES, 'UTF-8') ?>"
                              data-categories='<?= htmlspecialchars(json_encode($categories), ENT_QUOTES, 'UTF-8') ?>'>
                          <?= ($current_lang == 'ar' ? 'عرض التفاصيل' : 'View Details') ?>
                      </button>
                  </div>
              </section>
          <?php endforeach; ?>
      <?php endif; ?>

  </main>

  <!-- Modal Structure (Using non-conflicting classes) -->
  <div class="holo-modal" id="holoReelModal">
    <div class="holo-modal__backdrop"></div>
    <div class="holo-modal__content">
        <button class="holo-modal__close-btn"><i class='bx bx-x'></i></button>
        <div class="holo-modal__body">
            <div class="holo-modal__image"></div>
            <div class="holo-modal__details">
                <h2 class="holo-modal__title"></h2>
                <div class="holo-modal__categories"></div>
                <p class="holo-modal__description"></p>
                <a href="#" class="holo-modal__link btn-visit-website" target="_blank"><?= $lang['visit_website_btn'] ?? 'Visit Website' ?></a>
            </div>
        </div>
    </div>
  </div>

  <?php include 'partials/footer.php'; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  
  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <!-- Immersive Showcase Logic -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- Modal Logic ---
        const projectModal = document.getElementById('holoReelModal');
        if (!projectModal) return;

        const modalImage = projectModal.querySelector('.holo-modal__image');
        const modalTitle = projectModal.querySelector('.holo-modal__title');
        const modalCategoriesContainer = projectModal.querySelector('.holo-modal__categories');
        const modalDescription = projectModal.querySelector('.holo-modal__description');
        const modalLink = projectModal.querySelector('.holo-modal__link');

        const openModal = (button) => {
            modalImage.style.backgroundImage = `url('${button.dataset.image}')`;
            modalTitle.textContent = button.dataset.title;
            modalDescription.textContent = button.dataset.description;
            const categories = JSON.parse(button.dataset.categories);
            modalCategoriesContainer.innerHTML = '';
            if (categories) {
                categories.forEach(cat => {
                    const span = document.createElement('span');
                    span.textContent = cat;
                    modalCategoriesContainer.appendChild(span);
                });
            }
            if (button.dataset.url && button.dataset.url !== '#') {
                modalLink.href = button.dataset.url;
                modalLink.style.display = 'inline-block';
            } else {
                modalLink.style.display = 'none';
            }
            document.body.style.overflow = 'hidden';
            projectModal.classList.add('is-open');
        };

        const closeModal = () => {
            document.body.style.overflow = '';
            projectModal.classList.remove('is-open');
        };

        document.querySelectorAll('.slide-cta').forEach(button => {
            button.addEventListener('click', () => openModal(button));
        });

        projectModal.querySelector('.holo-modal__backdrop').addEventListener('click', closeModal);
        projectModal.querySelector('.holo-modal__close-btn').addEventListener('click', closeModal);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && projectModal.classList.contains('is-open')) closeModal();
        });
    });
  </script>
</body>
</html>