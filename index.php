<?php
require_once 'admin/db.php';

session_start(); // ابدأ الجلسة لتخزين اللغة

// تحديد اللغة الافتراضية إذا لم تكن محددة
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // اللغة الافتراضية هي الإنجليزية
}

// تغيير اللغة عند النقر على رابط اللغة
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$current_lang = $_SESSION['lang'];

// تحميل ملف اللغة المناسب
if (file_exists('lang/' . $current_lang . '.php')) {
    include 'lang/' . $current_lang . '.php';
} else {
    include 'lang/en.php'; // Fallback to English
}

// --- End Language Logic ---


// Fetch all data needed for the page from our new tables
$settings = $dbcon->query("SELECT * FROM company_settings WHERE id=1")->fetch_assoc();
$contact = $dbcon->query("SELECT * FROM contact_information WHERE id=1")->fetch_assoc();
$stats = $dbcon->query("SELECT * FROM site_stats");
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>" dir="<?= ($current_lang == 'ar' ? 'rtl' : 'ltr') ?>">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= htmlspecialchars($settings['company_name']) ?> - Home</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
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

   <section id="hero" class="d-flex align-items-center">
    <div class="container-fluid" data-aos="fade-up">
      <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 pt-3 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center text-center">
          <h1><?= htmlspecialchars($current_lang == 'en' ? $settings['hero_title']:$settings['hero_title_ar']) ?></h1>
          <h2><?= htmlspecialchars($current_lang == 'en'?$settings['hero_subtitle']:$settings['hero_subtitle_ar']) ?></h2>
          <div><a href="#about" class="btn-get-started scrollto"><?= $lang['get_started_btn'] ?></a></div>
        </div>
      </div>
    </div>
    <span class="hero-tag tag-developer hero-tag-pos-1"><?= $lang['hero_tag_developer'] ?></span>
    <span class="hero-tag tag-content-creator hero-tag-pos-2"><?= $lang['hero_tag_content_creator'] ?></span>
    <span class="hero-tag tag-designer hero-tag-pos-3"><?= $lang['hero_tag_designer'] ?></span>
    <span class="hero-tag tag-marketing hero-tag-pos-4"><?= $lang['hero_tag_marketing'] ?></span>
    <span class="hero-tag tag-analyst hero-tag-pos-5"><?= $lang['hero_tag_analyst'] ?></span>
  </section>

  <main id="main">
    
    <section id="services" class="services section-bg">
      <?php $services_homepage = $dbcon->query("SELECT * FROM services ORDER BY id DESC LIMIT 6");
      $total_services_count = $dbcon->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];?>
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['services_title'] ?></h2>
          <p><?= $lang['services_description'] ?></p>
        </div>
        <div class="row gy-4">
          <?php foreach($services_homepage as $service): ?>
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box <?= htmlspecialchars($service['box_color_class']) ?>">
              <div class="icon"><img src="assets/img/services/<?= htmlspecialchars($service['image_file']) ?>" alt="<?= htmlspecialchars($service['title']) ?>" class="img-fluid" style="max-height: 40px;"></div>
              <h4><a href=""><?= htmlspecialchars($current_lang == 'en' ? $service['title'] : $service['title_ar'])  ?></a></h4>
              <p><?= htmlspecialchars($current_lang == 'en'?$service['description']:$service['description_ar']) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php if ($total_services_count > 6): ?>
        <div class="text-center mt-5">
            <a href="services.php?lang=<?= $current_lang ?>" class="btn btn-primary"><?= $lang['see_all_services_btn'] ?></a>
        </div>
        <?php endif; ?>
      </div>
    </section>

    <section id="features" class="features">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['features_title'] ?></h2>
          <p><?= $lang['features_description'] ?></p>
        </div>
        <div class="row">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column align-items-lg-center">
            <div class="icon-box mt-5 mt-lg-0" data-aos="fade-up" data-aos-delay="100"><i class="bx bx-receipt"></i><h4>Est labore ad</h4><p>Consequuntur sunt aut quasi enim aliquam quae harum pariatur laboris nisi ut aliquip</p></div>
            <div class="icon-box mt-5" data-aos="fade-up" data-aos-delay="200"><i class="bx bx-cube-alt"></i><h4>Harum esse qui</h4><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p></div>
            <div class="icon-box mt-5" data-aos="fade-up" data-aos-delay="300"><i class="bx bx-images"></i><h4>Aut occaecati</h4><p>Aut suscipit aut cum nemo deleniti aut omnis. Doloribus ut maiores omnis facere</p></div>
            <div class="icon-box mt-5" data-aos="fade-up" data-aos-delay="400"><i class="bx bx-shield"></i><h4>Beatae veritatis</h4><p>Expedita veritatis consequuntur nihil tempore laudantium vitae denat pacta</p></div>
          </div>
          <div class="image col-lg-6 order-1 order-lg-2 " data-aos="zoom-in" data-aos-delay="100"><img src="assets/img/features.svg" alt="" class="img-fluid"></div>
        </div>
      </div>
    </section>

<!-- ======= Portfolio Section ======= -->
<section id="portfolio" class="portfolio">
    <?php
    // قمنا بتعديل الاستعلام لجلب اسم التصنيف بدلاً من "filter_tag"
    $products_query = $dbcon->query("SELECT p.*, c.name AS category_name FROM products p JOIN product_categories c ON p.category_id = c.id ORDER BY p.id DESC");
    $products = [];
    if ($products_query) {
        $products = $products_query->fetch_all(MYSQLI_ASSOC);
    }
    ?>

    <div class="container" data-aos="fade-up">
        <div class="section-title">
            <h2><?= $lang['portfolio_title'] ?? 'Portfolio' ?></h2>
            <p><?= $lang['portfolio_description'] ?? 'Check out our amazing work.' ?></p>
        </div>

        <!-- The Swiper Slider -->
        <div class="swiper portfolio-slider">
            <div class="swiper-wrapper">

                <?php foreach ($products as $product) : ?>
                    <div class="swiper-slide">
                        <div class="portfolio-item-wrap">
                            <!-- رابط يفتح الصورة في Glightbox عند النقر عليها -->
                            <a href="assets/img/portfolio/<?= htmlspecialchars($product['image']) ?>" class="portfolio-lightbox" data-gallery="portfolio-gallery">
                                <img src="assets/img/portfolio/<?= htmlspecialchars($product['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($product['name']) ?>">
                            </a>
                            
                            <div class="portfolio-content">
                                <h3><?= htmlspecialchars($product['name']) ?></h3>
                                <div class="portfolio-tags">
                                    <!-- ملاحظة: التصميم الحالي لقاعدة البيانات يسمح بتصنيف واحد فقط. سيتم عرض هذا التصنيف هنا -->
                                    <span><?= htmlspecialchars($product['category_name']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>

        <!-- Add Navigation -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

    </div>
</section><!-- End Portfolio Section -->
    <section id="faq" class="faq">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['faq_title'] ?></h2>
          <p><?= $lang['faq_description'] ?></p>
        </div>
        <div class="faq-list">
          <ul>
            <li data-aos="fade-up" data-aos-delay="100"><i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" class="collapse" data-bs-target="#faq-list-1">Non consectetur a erat nam at lectus urna duis? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a><div id="faq-list-1" class="collapse show" data-bs-parent=".faq-list"><p>Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.</p></div></li>
            <li data-aos="fade-up" data-aos-delay="200"><i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-2" class="collapsed">Feugiat scelerisque varius morbi enim nunc? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a><div id="faq-list-2" class="collapse" data-bs-parent=".faq-list"><p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p></div></li>
          </ul>
        </div>
      </div>
    </section>

    <section id="team" class="team">
      <div class="container">
        <div class="section-title">
          <h2><?= $lang['team_title'] ?></h2>
          <p><?= $lang['team_description'] ?></p>
        </div>
        <div class="row">
          <div class="col-xl-3 col-lg-4 col-md-6"><div class="member"><img src="assets/img/team/team-1.jpg" class="img-fluid" alt=""><div class="member-info"><div class="member-info-content"><h4>Walter White</h4><span>Chief Executive Officer</span><div class="social"><a href=""><i class="bi bi-twitter"></i></a><a href=""><i class="bi bi-facebook"></i></a><a href=""><i class="bi bi-instagram"></i></a><a href=""><i class="bi bi-linkedin"></i></a></div></div></div></div></div>
          <div class="col-xl-3 col-lg-4 col-md-6" data-wow-delay="0.1s"><div class="member"><img src="assets/img/team/team-2.jpg" class="img-fluid" alt=""><div class="member-info"><div class="member-info-content"><h4>Sarah Jhonson</h4><span>Product Manager</span><div class="social"><a href=""><i class="bi bi-twitter"></i></a><a href=""><i class="bi bi-facebook"></i></a><a href=""><i class="bi bi-instagram"></i></a><a href=""><i class="bi bi-linkedin"></i></a></div></div></div></div></div>
          <div class="col-xl-3 col-lg-4 col-md-6" data-wow-delay="0.2s"><div class="member"><img src="assets/img/team/team-3.jpg" class="img-fluid" alt=""><div class="member-info"><div class="member-info-content"><h4>William Anderson</h4><span>CTO</span><div class="social"><a href=""><i class="bi bi-twitter"></i></a><a href=""><i class="bi bi-facebook"></i></a><a href=""><i class="bi bi-instagram"></i></a><a href=""><i class="bi bi-linkedin"></i></a></div></div></div></div></div>
          <div class="col-xl-3 col-lg-4 col-md-6" data-wow-delay="0.3s"><div class="member"><img src="assets/img/team/team-4.jpg" class="img-fluid" alt=""><div class="member-info"><div class="member-info-content"><h4>Amanda Jepson</h4><span>Accountant</span><div class="social"><a href=""><i class="bi bi-twitter"></i></a><a href=""><i class="bi bi-facebook"></i></a><a href=""><i class="bi bi-instagram"></i></a><a href=""><i class="bi bi-linkedin"></i></a></div></div></div></div></div>
        </div>
      </div>
    </section>

    <section id="contact" class="contact section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title"><h2><?= $lang['contact_title'] ?></h2><p><?= $lang['contact_description'] ?></p></div>
        <div class="row">
          <div class="col-lg-6"><div class="info-box mb-4"><i class="bx bx-map"></i><h3><?= $lang['contact_our_address'] ?></h3><p><?= htmlspecialchars($contact['address']) ?></p></div></div>
          <div class="col-lg-3 col-md-6"><div class="info-box  mb-4"><i class="bx bx-envelope"></i><h3><?= $lang['contact_email_us'] ?></h3><p><?= htmlspecialchars($contact['email']) ?></p></div></div>
          <div class="col-lg-3 col-md-6"><div class="info-box  mb-4"><i class="bx bx-phone-call"></i><h3><?= $lang['contact_call_us'] ?></h3><p><?= htmlspecialchars($contact['phone']) ?></p></div></div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <?php
            if (!empty($contact['map_embed_code'])) { echo $contact['map_embed_code']; } 
            else { echo '<div style="border:0; width: 100%; height: 384px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color: #666;">Map will be displayed here.</div>'; }
            ?>
          </div>
          <div class="col-lg-6"><form action="forms/contact.php" method="post" role="form" class="php-email-form">
              <div class="row"><div class="col-md-6 form-group"><input type="text" name="name" class="form-control" id="name" placeholder="<?= $lang['contact_form_name_placeholder'] ?>" required></div><div class="col-md-6 form-group mt-3 mt-md-0"><input type="email" class="form-control" name="email" id="email" placeholder="<?= $lang['contact_form_email_placeholder'] ?>" required></div></div>
              <div class="form-group mt-3"><input type="text" class="form-control" name="subject" id="subject" placeholder="<?= $lang['contact_form_subject_placeholder'] ?>" required></div>
              <div class="form-group mt-3"><textarea class="form-control" name="message" rows="5" placeholder="<?= $lang['contact_form_message_placeholder'] ?>" required></textarea></div>
              <div class="my-3"><div class="loading">Loading</div><div class="error-message"></div><div class="sent-message">Your message has been sent. Thank you!</div></div>
              <div class="text-center"><button type="submit"><?= $lang['contact_form_send_btn'] ?></button></div>
          </form></div>
        </div>
      </div>
    </section>
  </main>

  <?php include 'partials/footer.php'; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>
  <script src="assets/vendor/purecounter/purecounter.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>