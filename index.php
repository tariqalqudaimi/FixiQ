<?php
require_once 'error_handler.php';
require_once 'admin/db.php';

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
    include 'lang/en.php'; // Fallback to English
}
// --- End Language Logic ---


// --- Optimized Data Fetching ---
$settings = $dbcon->query("SELECT company_name, hero_title, hero_title_ar, hero_subtitle, hero_subtitle_ar FROM company_settings WHERE id=1")->fetch_assoc();
$contact = $dbcon->query("SELECT address, email, phone, map_embed_code FROM contact_information WHERE id=1")->fetch_assoc();
$team_members_query = $dbcon->query("SELECT name, position, image_file, twitter_url, facebook_url, instagram_url, linkedin_url FROM team_members ORDER BY display_order ASC");

// ==============================================================================
// === THIS IS THE CORRECTED QUERY FOR PRODUCTS THAT FIXES THE ERROR ON LINE 28 ===
// ==============================================================================
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
// ==============================================================================
// ==============================================================================


$services_homepage = $dbcon->query("SELECT box_color_class, image_file, title, title_ar, description, description_ar FROM services ORDER BY id DESC LIMIT 6");
$total_services_count = $dbcon->query("SELECT COUNT(id) as count FROM services")->fetch_assoc()['count'];
$features_query = $dbcon->query("SELECT * FROM features ORDER BY display_order ASC");

?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>" dir="<?= ($current_lang == 'ar' ? 'rtl' : 'ltr') ?>">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale-1.0" name="viewport">
  <title><?= htmlspecialchars($settings['company_name'] ?? 'Company Name') ?> - Home</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="assets/img/Artboard 8-8.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <!-- or -->
  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>


<!-- ======= Preloader with SVG Line Animation ======= -->
<div id="preloader">
  <div class="preloader-logo-container">
      <!-- SVG Code - Rebuilt as paths for animation -->
      <svg width="40" height="40" viewBox="0 0 236 97" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
              <!-- This is the 'light flare' gradient for the animation -->
              <linearGradient id="light-flare" x1="0%" y1="0%" x2="100%" y2="0%">
                  <stop offset="0%" stop-color="rgba(138, 79, 255, 0)" />
                  <stop offset="50%" stop-color="rgba(255, 255, 255, 0.8)" />
                  <stop offset="100%" stop-color="rgba(138, 79, 255, 0)" />
              </linearGradient>
          </defs>
  <path class="logo-path light-flare-path "  d="M519.74,326.75l-88.51,166.5c-9.29,17.48-34.53,16.89-43-1l-78-164.81a24.07,24.07,0,0,0-21.75-13.77H275.23a24.07,24.07,0,0,0-21.87,34.12L387.48,639.57c8.48,18.44,34.57,18.75,43.49.52L555.28,385.78a24.07,24.07,0,0,1,21-13.49l96.43-2.61-.36.77-76.05,163c-8.6,18.44-34.79,18.54-43.54.17l-4.86-10.2c-9-18.8-35.93-18.13-43.94,1.09h0a24.07,24.07,0,0,0,.72,20.07l50.91,101.17a24.07,24.07,0,0,0,42.84.31l154.91-297.1a24.07,24.07,0,0,0-21.37-35.19L541,314A24.07,24.07,0,0,0,519.74,326.75ZM694.82,344v0h0Zm0-13.43h0Z"/>
        </svg>
  </div>
</div>
  <?php include 'partials/header.php'; ?>

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">
       <!-- START: Animated Logo for Hero Background -->
    <div class="hero-logo-animation">
        <svg width="140" height="97" viewBox="0 0 194 97" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path class="logo-path " d="M519.74,326.75l-88.51,166.5c-9.29,17.48-34.53,16.89-43-1l-78-164.81a24.07,24.07,0,0,0-21.75-13.77H275.23a24.07,24.07,0,0,0-21.87,34.12L387.48,639.57c8.48,18.44,34.57,18.75,43.49.52L555.28,385.78a24.07,24.07,0,0,1,21-13.49l96.43-2.61-.36.77-76.05,163c-8.6,18.44-34.79,18.54-43.54.17l-4.86-10.2c-9-18.8-35.93-18.13-43.94,1.09h0a24.07,24.07,0,0,0,.72,20.07l50.91,101.17a24.07,24.07,0,0,0,42.84.31l154.91-297.1a24.07,24.07,0,0,0-21.37-35.19L541,314A24.07,24.07,0,0,0,519.74,326.75ZM694.82,344v0h0Zm0-13.43h0Z"/>
          </svg>
    </div>
    <!-- END: Animated Logo for Hero Background -->

    <div class="container-fluid" data-aos="fade-up">
      
   

      <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 pt-3 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center text-center">
          <h1><?= htmlspecialchars($current_lang == 'en' ? ($settings['hero_title'] ?? '') : ($settings['hero_title_ar'] ?? '')) ?></h1>
           <a href="index.php?lang=<?= $current_lang ?>">
      <img src="<?= htmlspecialchars($settings['company_logo'] ?? 'assets\img\Artboard 8-8.png') ?>" alt="<?= htmlspecialchars($settings['company_name'] ?? 'Logo') ?>" class="logo">
    </a>
          <!-- <h2><?= htmlspecialchars($current_lang == 'en' ? ($settings['hero_subtitle'] ?? '') : ($settings['hero_subtitle_ar'] ?? '')) ?></h2> -->
          
          <div><a href="#services" class="btn-get-started scrollto"><?= $lang['get_started_btn'] ?? 'Get Started' ?></a></div>
        </div>
      </div>
    </div>
    
    <!-- <span class="hero-tag tag-developer hero-tag-pos-1"><?= $lang['hero_tag_developer'] ?? 'Developer' ?></span>
    <span class="hero-tag tag-content-creator hero-tag-pos-2"><?= $lang['hero_tag_content_creator'] ?? 'Creator' ?></span>
    <span class="hero-tag tag-designer hero-tag-pos-3"><?= $lang['hero_tag_designer'] ?? 'Designer' ?></span>
    <span class="hero-tag tag-marketing hero-tag-pos-4"><?= $lang['hero_tag_marketing'] ?? 'Marketing' ?></span>
    <span class="hero-tag tag-analyst hero-tag-pos-5"><?= $lang['hero_tag_analyst'] ?? 'Analyst' ?></span> -->
  </section><!-- End Hero -->

  <main id="main">
    
    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['services_title'] ?? 'Services' ?></h2>
          <p><?= $lang['services_description'] ?? 'Our Services' ?></p>
        </div>
        <div class="row gy-4">
          <?php if($services_homepage): foreach($services_homepage as $service): ?>
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box <?= htmlspecialchars($service['box_color_class']) ?>">
              <div class="icon"><img src="assets/img/services/<?= htmlspecialchars($service['image_file']) ?>" alt="<?= htmlspecialchars($service['title']) ?>" class="img-fluid" style="max-height: 40px;" loading="lazy"></div>
              <h4><a href=""><?= htmlspecialchars($current_lang == 'en' ? $service['title'] : $service['title_ar'])  ?></a></h4>
              <p><?= htmlspecialchars($current_lang == 'en' ? $service['description'] : $service['description_ar']) ?></p>
            </div>
          </div>
          <?php endforeach; endif; ?>
        </div>
        <?php if ($total_services_count > 6): ?>
        <div class="text-center mt-5">
            <a href="services.php?lang=<?= $current_lang ?>" class="btn btn-primary"><?= $lang['see_all_services_btn'] ?? 'See All Services' ?></a>
        </div>
        <?php endif; ?>
      </div>
    </section><!-- End Services Section -->

    <!-- ======= Features Section ======= -->
    <section id="features" class="features section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['features_title'] ?? 'Our Core Features' ?></h2>
          <!-- <p><?= $lang['features_description'] ?? 'Why partners choose to work with us' ?></p> -->
        </div>
        <div class="row">
          <?php if ($features_query && $features_query->num_rows > 0): ?>
            <?php 
              $delay = 0;
              foreach($features_query as $feature): 
              $delay += 100;
            ?>
              <div class="col-lg-6 mt-4">
                <div class="feature-box" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                  <i class="<?= htmlspecialchars($feature['icon_class']) ?>"></i>
                  <h3><?= htmlspecialchars($current_lang == 'en' ? $feature['title_en'] : $feature['title_ar']) ?></h3>
                  <p><?= htmlspecialchars($current_lang == 'en' ? $feature['description_en'] : $feature['description_ar']) ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section><!-- End Features Section -->

    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio">
      <div class="container" data-aos="fade-up">
          <div class="section-title">
          <h2><?= $lang['portfolio_title'] ?? 'Our Core Features' ?></h2>
          <p><?= $lang['portfolio_description'] ?? 'Why partners choose to work with us' ?></p>
        </div>
        <div class="swiper portfolio-slider">
          <div class="swiper-wrapper">
            <?php if($products_query): foreach ($products_query as $product) : ?>
              <div class="swiper-slide">
                <div class="portfolio-slide-content">
                  <div class="portfolio-item-wrap">
                    <a href="assets/img/portfolio/<?= htmlspecialchars($product['image']) ?>" class="portfolio-lightbox" data-gallery="portfolio-gallery" title="<?= htmlspecialchars($product['name']) ?>">
                      <img src="assets/img/portfolio/<?= htmlspecialchars($product['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
                    </a>
                  </div>
                  <div class="portfolio-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <!-- === THIS IS THE CORRECTED DISPLAY LOGIC FOR CATEGORIES === -->
                    <div class="portfolio-tags">
                      <?php
                        if (!empty($product['category_names'])) {
                            $categories = explode(', ', $product['category_names']);
                            foreach ($categories as $category):
                      ?>
                                <span><?= htmlspecialchars(trim($category)) ?></span>
                      <?php
                            endforeach;
                        }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; endif; ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </section><!-- End Portfolio Section -->

    <!-- ======= Team Section ======= -->
    <section id="team" class="team section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['team_title'] ?? 'Team' ?></h2>
          <p><?= $lang['team_description'] ?? 'Our hardworking team' ?></p>
        </div>
        <div class="swiper team-slider">
          <div class="swiper-wrapper">
            <?php if ($team_members_query && $team_members_query->num_rows > 0): foreach($team_members_query as $member): ?>
            <div class="swiper-slide">
              <div class="member-style-2 text-center">
                <img src="assets/img/team/<?= htmlspecialchars($member['image_file']) ?>" class="img-fluid" alt="<?= htmlspecialchars($member['name']) ?>" loading="lazy">
                <div class="member-info">
                  <h4><?= htmlspecialchars($member['name']) ?></h4>
                  <span><?= htmlspecialchars($member['position']) ?></span>
                </div>
                <div class="social-links">
                  <?php if (!empty($member['twitter_url'])): ?><a href="<?= htmlspecialchars($member['twitter_url']) ?>"><i class='bx bx-twitter-x'  ></i>  </a><?php endif; ?>
                  <?php if (!empty($member['facebook_url'])): ?><a href="<?= htmlspecialchars($member['facebook_url']) ?>"><i class="bi bi-facebook"></i></a><?php endif; ?>
                  <?php if (!empty($member['instagram_url'])): ?><a href="<?= htmlspecialchars($member['instagram_url']) ?>"><i class="bi bi-instagram"></i></a><?php endif; ?>
                  <?php if (!empty($member['linkedin_url'])): ?><a href="<?= htmlspecialchars($member['linkedin_url']) ?>"><i class="bi bi-linkedin"></i></a><?php endif; ?>
                </div>
              </div>
            </div>
            <?php endforeach; endif; ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section><!-- End Team Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['contact_title'] ?? 'Contact' ?></h2>
          <p><?= $lang['contact_description'] ?? '' ?></p>
        </div>
        <?php if($contact): ?>
        <div class="row">
          <div class="col-lg-6"><div class="info-box mb-4"><i class="bx bx-map"></i><h3><?= $lang['contact_our_address'] ?? 'Address' ?></h3><p><?= htmlspecialchars($contact['address']) ?></p></div></div>
          <div class="col-lg-3 col-md-6"><div class="info-box  mb-4"><i class="bx bx-envelope"></i><h3><?= $lang['contact_email_us'] ?? 'Email' ?></h3><p><?= htmlspecialchars($contact['email']) ?></p></div></div>
          <div class="col-lg-3 col-md-6"><div class="info-box  mb-4"><i class="bx bx-phone-call"></i><h3><?= $lang['contact_call_us'] ?? 'Call' ?></h3><p><?= htmlspecialchars($contact['phone']) ?></p></div></div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <?= !empty($contact['map_embed_code']) ? $contact['map_embed_code'] : '<div style="border:0; width: 100%; height: 384px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color: #666;">Map will be displayed here.</div>' ?>
          </div>
          <div class="col-lg-6"><form action="forms/contact.php" method="post" role="form" class="php-email-form">
              <div class="row"><div class="col-md-6 form-group"><input type="text" name="name" class="form-control" id="name" placeholder="<?= $lang['contact_form_name_placeholder'] ?? 'Name' ?>" required></div><div class="col-md-6 form-group mt-3 mt-md-0"><input type="email" class="form-control" name="email" id="email" placeholder="<?= $lang['contact_form_email_placeholder'] ?? 'Email' ?>" required></div></div>
              <div class="form-group mt-3"><input type="text" class="form-control" name="subject" id="subject" placeholder="<?= $lang['contact_form_subject_placeholder'] ?? 'Subject' ?>" required></div>
              <div class="form-group mt-3"><textarea class="form-control" name="message" rows="5" placeholder="<?= $lang['contact_form_message_placeholder'] ?? 'Message' ?>" required></textarea></div>
              <div class="my-3"><div class="loading">Loading</div><div class="error-message"></div><div class="sent-message">Your message has been sent. Thank you!</div></div>
              <div class="text-center"><button type="submit"><?= $lang['contact_form_send_btn'] ?? 'Send' ?></button></div>
          </form></div>
        </div>
        <?php endif; ?>
      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->
  </main><!-- End #main -->

  <!-- ======================================================= -->
  <!-- START: Floating Animated Logo (NEW POSITION)          -->
  <!-- ======================================================= -->
  <div class="floating-logo-animation">
      <svg width="194" height="97" viewBox="0 0 194 97" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
              <!-- Gradient for the light flare effect -->
              <linearGradient id="light-flare" x1="0%" y1="0%" x2="100%" y2="0%">
                  <stop offset="0%" stop-color="rgba(138, 79, 255, 0)" />
                  <stop offset="50%" stop-color="rgba(255, 255, 255, 0.8)" />
                  <stop offset="100%" stop-color="rgba(138, 79, 255, 0)" />
              </linearGradient>
          </defs>
 <path class="logo-path " d="M519.74,326.75l-88.51,166.5c-9.29,17.48-34.53,16.89-43-1l-78-164.81a24.07,24.07,0,0,0-21.75-13.77H275.23a24.07,24.07,0,0,0-21.87,34.12L387.48,639.57c8.48,18.44,34.57,18.75,43.49.52L555.28,385.78a24.07,24.07,0,0,1,21-13.49l96.43-2.61-.36.77-76.05,163c-8.6,18.44-34.79,18.54-43.54.17l-4.86-10.2c-9-18.8-35.93-18.13-43.94,1.09h0a24.07,24.07,0,0,0,.72,20.07l50.91,101.17a24.07,24.07,0,0,0,42.84.31l154.91-297.1a24.07,24.07,0,0,0-21.37-35.19L541,314A24.07,24.07,0,0,0,519.74,326.75ZM694.82,344v0h0Zm0-13.43h0Z"/>
        </svg>
  </div>
  <!-- ======================================================= -->
  <!-- END: Floating Animated Logo                           -->
  <!-- ======================================================= -->

  <?php include 'partials/footer.php'; ?>
 

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  
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