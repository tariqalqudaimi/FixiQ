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
  <link href="assets/img/favicon.png" rel="icon">
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
      <svg width="236" height="97" viewBox="0 0 236 97" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
              <!-- This is the 'light flare' gradient for the animation -->
              <linearGradient id="light-flare" x1="0%" y1="0%" x2="100%" y2="0%">
                  <stop offset="0%" stop-color="rgba(138, 79, 255, 0)" />
                  <stop offset="50%" stop-color="rgba(255, 255, 255, 0.8)" />
                  <stop offset="100%" stop-color="rgba(138, 79, 255, 0)" />
              </linearGradient>
          </defs>
   <path class="logo-path light-flare-path "  d="M147.86,13.9c-4.55-.56-6.93,1.45-9.38,4.38-18,21.44-36.16,42.75-54.31,64.08a24.28,24.28,0,0,1-2.29,2.09l-.47.91q.07.32-.29.46L79.8,87l0,0-.55.95-.19.26-4.56-.43h0L71.15,84l-.14-.1c.47-1.35,1.22-2.85-1.86-2.23l-.26-.22L67.12,79.3a.57.57,0,0,1-.35-.3l-1.34-1.91,0,0-2-2.46h0L54.16,63.42l.12-.23-.17.21-4-4.89.1-.2-.15.18L48.16,56l.11-.29-.14.28-4-4.83-.09-.07-1.94-2.45.11-.28-.14.27-6-7.32-.06,0-1.92-2.46.1-.3-.13.29L30.1,34l.11-.22-.16.2-8-9.81.1-.3-.14.3L17.29,18.2l0,0L10.06,9.41l.1-.25L10,9.4,8,7,8,7,6,4.61l-.11-.07L5.33,0h22.4c1.24.8,2.84,1.4,3.66,2.42Q54.07,30.58,76.57,58.83c.16.21.11.53.16.8l1.56,1.44,2.22-3,0,0,1.28-1.89a.55.55,0,0,1,.37-.31l2.39-2.73,0,0L88,48.85l.15-.1,10.66-13a.59.59,0,0,1,.35-.3l1.64-2.12a.58.58,0,0,1,.35-.29l2.41-2.74,0,0,1.52-1.91.15-.09,4.05-4.76.15.17-.09-.19.45-.69a.51.51,0,0,1,.44-.52l1-1.17.08-.06,1.27-1.46,0,0L113.59,18h0L116,15.26l.23-.18,2.41-2.79,0,0,3.07-4.08h0l2.59-2.72.07,0,2-2.41v.22l0-.21L128.62,0h0c32.35,0,64.7,0,97,.15,2,0,4.05,1.13,6.08,1.73l-.21.34-1.39,1.33c-9.59,11.85-19.1,23.74-28.78,35.54Q178,67.57,154.52,95.93c-1,1.2-2.09,2.35-3.77,4.23-4.86-5.4-9.52-10.31-13.83-15.41-6.23-7.37-12.2-14.9-18.32-22.34-2-2.49-2.5-4.78,0-7.34,3.34-3.36,6.37-6.92,10-10.94l22,26.42c10.48-13.15,20.81-26.1,31.11-39.05,4.39-5.53,8.73-11.09,13.09-16.63l.87-1.19a25.46,25.46,0,0,0-3.37-.45q-20.66-.05-41.32,0A16,16,0,0,0,147.86,13.9Z"/>
  
        </svg>
  </div>
</div>
  <?php include 'partials/header.php'; ?>

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">
       <!-- START: Animated Logo for Hero Background -->
    <div class="hero-logo-animation">
        <svg width="194" height="97" viewBox="0 0 194 97" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="logo-path " d="M147.86,13.9c-4.55-.56-6.93,1.45-9.38,4.38-18,21.44-36.16,42.75-54.31,64.08a24.28,24.28,0,0,1-2.29,2.09l-.47.91q.07.32-.29.46L79.8,87l0,0-.55.95-.19.26-4.56-.43h0L71.15,84l-.14-.1c.47-1.35,1.22-2.85-1.86-2.23l-.26-.22L67.12,79.3a.57.57,0,0,1-.35-.3l-1.34-1.91,0,0-2-2.46h0L54.16,63.42l.12-.23-.17.21-4-4.89.1-.2-.15.18L48.16,56l.11-.29-.14.28-4-4.83-.09-.07-1.94-2.45.11-.28-.14.27-6-7.32-.06,0-1.92-2.46.1-.3-.13.29L30.1,34l.11-.22-.16.2-8-9.81.1-.3-.14.3L17.29,18.2l0,0L10.06,9.41l.1-.25L10,9.4,8,7,8,7,6,4.61l-.11-.07L5.33,0h22.4c1.24.8,2.84,1.4,3.66,2.42Q54.07,30.58,76.57,58.83c.16.21.11.53.16.8l1.56,1.44,2.22-3,0,0,1.28-1.89a.55.55,0,0,1,.37-.31l2.39-2.73,0,0L88,48.85l.15-.1,10.66-13a.59.59,0,0,1,.35-.3l1.64-2.12a.58.58,0,0,1,.35-.29l2.41-2.74,0,0,1.52-1.91.15-.09,4.05-4.76.15.17-.09-.19.45-.69a.51.51,0,0,1,.44-.52l1-1.17.08-.06,1.27-1.46,0,0L113.59,18h0L116,15.26l.23-.18,2.41-2.79,0,0,3.07-4.08h0l2.59-2.72.07,0,2-2.41v.22l0-.21L128.62,0h0c32.35,0,64.7,0,97,.15,2,0,4.05,1.13,6.08,1.73l-.21.34-1.39,1.33c-9.59,11.85-19.1,23.74-28.78,35.54Q178,67.57,154.52,95.93c-1,1.2-2.09,2.35-3.77,4.23-4.86-5.4-9.52-10.31-13.83-15.41-6.23-7.37-12.2-14.9-18.32-22.34-2-2.49-2.5-4.78,0-7.34,3.34-3.36,6.37-6.92,10-10.94l22,26.42c10.48-13.15,20.81-26.1,31.11-39.05,4.39-5.53,8.73-11.09,13.09-16.63l.87-1.19a25.46,25.46,0,0,0-3.37-.45q-20.66-.05-41.32,0A16,16,0,0,0,147.86,13.9Z" />
        </svg>
    </div>
    <!-- END: Animated Logo for Hero Background -->

    <div class="container-fluid" data-aos="fade-up">
      
   

      <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 pt-3 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center text-center">
          <h1><?= htmlspecialchars($current_lang == 'en' ? ($settings['hero_title'] ?? '') : ($settings['hero_title_ar'] ?? '')) ?></h1>
           <a href="index.php?lang=<?= $current_lang ?>">
      <img src="<?= htmlspecialchars($settings['company_logo'] ?? 'assets/img/Asset 3.png') ?>" alt="<?= htmlspecialchars($settings['company_name'] ?? 'Logo') ?>" class="logo">
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
          <path class="logo-path" d="M147.86,13.9c-4.55-.56-6.93,1.45-9.38,4.38-18,21.44-36.16,42.75-54.31,64.08a24.28,24.28,0,0,1-2.29,2.09l-.47.91q.07.32-.29.46L79.8,87l0,0-.55.95-.19.26-4.56-.43h0L71.15,84l-.14-.1c.47-1.35,1.22-2.85-1.86-2.23l-.26-.22L67.12,79.3a.57.57,0,0,1-.35-.3l-1.34-1.91,0,0-2-2.46h0L54.16,63.42l.12-.23-.17.21-4-4.89.1-.2-.15.18L48.16,56l.11-.29-.14.28-4-4.83-.09-.07-1.94-2.45.11-.28-.14.27-6-7.32-.06,0-1.92-2.46.1-.3-.13.29L30.1,34l.11-.22-.16.2-8-9.81.1-.3-.14.3L17.29,18.2l0,0L10.06,9.41l.1-.25L10,9.4,8,7,8,7,6,4.61l-.11-.07L5.33,0h22.4c1.24.8,2.84,1.4,3.66,2.42Q54.07,30.58,76.57,58.83c.16.21.11.53.16.8l1.56,1.44,2.22-3,0,0,1.28-1.89a.55.55,0,0,1,.37-.31l2.39-2.73,0,0L88,48.85l.15-.1,10.66-13a.59.59,0,0,1,.35-.3l1.64-2.12a.58.58,0,0,1,.35-.29l2.41-2.74,0,0,1.52-1.91.15-.09,4.05-4.76.15.17-.09-.19.45-.69a.51.51,0,0,1,.44-.52l1-1.17.08-.06,1.27-1.46,0,0L113.59,18h0L116,15.26l.23-.18,2.41-2.79,0,0,3.07-4.08h0l2.59-2.72.07,0,2-2.41v.22l0-.21L128.62,0h0c32.35,0,64.7,0,97,.15,2,0,4.05,1.13,6.08,1.73l-.21.34-1.39,1.33c-9.59,11.85-19.1,23.74-28.78,35.54Q178,67.57,154.52,95.93c-1,1.2-2.09,2.35-3.77,4.23-4.86-5.4-9.52-10.31-13.83-15.41-6.23-7.37-12.2-14.9-18.32-22.34-2-2.49-2.5-4.78,0-7.34,3.34-3.36,6.37-6.92,10-10.94l22,26.42c10.48-13.15,20.81-26.1,31.11-39.05,4.39-5.53,8.73-11.09,13.09-16.63l.87-1.19a25.46,25.46,0,0,0-3.37-.45q-20.66-.05-41.32,0A16,16,0,0,0,147.86,13.9Z" />
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