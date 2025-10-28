<?php
require_once 'error_handler.php';
require_once 'admin/Database/db.php';

session_start();

// --- Language Logic ---
if (!isset($_SESSION['lang'])) {
  $_SESSION['lang'] = 'en';
}
if (isset($_GET['lang'])) {
  $_SESSION['lang'] = $_GET['.lang'];
}
$current_lang = $_SESSION['lang'];
if (file_exists('lang/' . $current_lang . '.php')) {
  include 'lang/' . $current_lang . '.php';
} else {
  include 'lang/en.php';
}

$settings = $dbcon->query("SELECT * FROM company_settings WHERE id=1")->fetch_assoc();
$contact = $dbcon->query("SELECT * FROM contact_information WHERE id=1")->fetch_assoc();
$team_members_query = $dbcon->query("SELECT name, position, image_file, website_url, facebook_url, instagram_url, linkedin_url FROM team_members ORDER BY display_order ASC");
$services_homepage = $dbcon->query("SELECT box_color_class, image_file, title, title_ar, description, description_ar FROM services ORDER BY id DESC LIMIT 6");
$total_services_count = $dbcon->query("SELECT COUNT(id) as count FROM services")->fetch_assoc()['count'];
$features_query = $dbcon->query("SELECT * FROM features ORDER BY display_order ASC");
$products_query = $dbcon->query("
    SELECT 
        p.id, p.name, p.name_ar, p.image, p.details_url, p.description, p.description_ar,
        GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') as category_names
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
    LIMIT 4
");

// Fetch all products into an array to pass to JavaScript
$products_array = [];
if ($products_query) {
  while ($row = $products_query->fetch_assoc()) {
    $products_array[] = $row;
  }
}



?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>" dir="<?= ($current_lang == 'ar' ? 'rtl' : 'ltr') ?>">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= htmlspecialchars($settings['company_name'] ?? 'Company Name') ?> - Home</title>
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
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <link rel="stylesheet"
    href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="project/style.css" rel="stylesheet">

  <!-- ========== NEW FONTS (SORA & TAJAWAL) ========== -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
  <!-- =================================================== -->
</head>

<body>


  <div id="preloader">
    <div class="preloader-logo-container">
      <svg width="40" height="40" viewBox="0 0 1000 97" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <linearGradient id="light-flare" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="rgba(138, 79, 255, 0)" />
            <stop offset="50%" stop-color="rgba(255, 255, 255, 0.8)" />
            <stop offset="100%" stop-color="rgba(138, 79, 255, 0)" />
          </linearGradient>
        </defs>
        <path class="logo-path light-flare-path " d="M516.74,326.75l-88.5,166.51c-9.3,17.47-34.54,16.89-43-1l-78-164.81a24.06,24.06,0,0,0-21.75-13.77H272.23a24.07,24.07,0,0,0-21.87,34.12L384.48,639.57c8.48,18.44,34.58,18.75,43.49.51l124.31-254.3a24.07,24.07,0,0,1,21-13.49l96.43-2.61-.36.77-76.05,163c-8.61,18.44-34.79,18.54-43.54.17l-4.85-10.2c-9-18.8-35.94-18.13-43.95,1.09h0a24.06,24.06,0,0,0,.72,20.07l50.91,101.17a24.07,24.07,0,0,0,42.84.31l154.91-297.1a24.07,24.07,0,0,0-21.38-35.2L538,314A24.07,24.07,0,0,0,516.74,326.75ZM691.82,344v0h0Zm0-13.43h0Z" />
      </svg>
    </div>
  </div>
  <canvas id="particle-canvas"></canvas>
  <?php include 'partials/header.php'; ?>

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">
    <div class="hero-logo-animation">
      <svg width="140" height="97" viewBox="0 0 194 97" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path class="logo-path " d="M519.74,326.75l-88.51,166.5c-9.29,17.48-34.53,16.89-43-1l-78-164.81a24.07,24.07,0,0,0-21.75-13.77H275.23a24.07,24.07,0,0,0-21.87,34.12L387.48,639.57c8.48,18.44,34.57,18.75,43.49.52L555.28,385.78a24.07,24.07,0,0,1,21-13.49l96.43-2.61-.36.77-76.05,163c-8.6,18.44-34.79,18.54-43.54.17l-4.86-10.2c-9-18.8-35.93-18.13-43.94,1.09h0a24.07,24.07,0,0,0,.72,20.07l50.91,101.17a24.07,24.07,0,0,0,42.84.31l154.91-297.1a24.07,24.07,0,0,0-21.37-35.19L541,314A24.07,24.07,0,0,0,519.74,326.75ZM694.82,344v0h0Zm0-13.43h0Z" />
      </svg>
    </div>
    <div class="container-fluid" data-aos="fade-up">
      <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 pt-3 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center text-center">
          <h1><?= htmlspecialchars($current_lang == 'en' ? ($settings['hero_title'] ?? '') : ($settings['hero_title_ar'] ?? '')) ?></h1>
          <a href="#">
            <img src="<?= htmlspecialchars($settings['company_logo'] ?? 'assets\img\Artboard 8-8.png') ?>" alt="<?= htmlspecialchars($settings['company_name'] ?? 'Logo') ?>" class="logo">
          </a>
          <!-- <h2><?= htmlspecialchars($current_lang == 'en' ? ($settings['hero_subtitle'] ?? '') : ($settings['hero_subtitle_ar'] ?? '')) ?></h2> -->

          <div><a href="#about" class="btn-get-started scrollto"><?= $lang['get_started_btn'] ?? 'Get Started' ?></a></div>
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
    <!-- ======= About Us Section (NEURAL NEBULA VERSION) ======= -->
    <section id="about" class="about section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2><?= $lang['about_title'] ?? 'About Us' ?></h2>
           <p><?= $lang['about_description'] ?? 'Explore the core of our identity' ?></p> 
        </div>

        <div class="about-us-container">
          <!-- Animated Particle Background -->
          <canvas id="particle-canvas"></canvas>

          <!-- The Neural Core -->
          <div class="neural-core">
            <div class="core-glow"></div>
            <i class='bx bxs-brain'></i>
          </div>

          <!-- NEW: The central point where all synapse lines will originate from -->
          <div class="synapse-origin">
            <div class="synapse-path synapse-path-1">
              <div class="synapse-pulse"></div>
            </div>
            <div class="synapse-path synapse-path-2">
              <div class="synapse-pulse"></div>
            </div>
            <div class="synapse-path synapse-path-3">
              <div class="synapse-pulse"></div>
            </div>
            <div class="synapse-path synapse-path-4">
              <div class="synapse-pulse"></div>
            </div>
          </div>

          <!-- The wrapper for the nodes -->
          <div class="neural-nodes-wrapper">
            <div class="neural-node pos-1">
              <div class="node-content">
                <button class="close-node-btn"><i class='bx bx-x'></i></button>
                <i class="bx bx-buildings"></i>
                <h4><?= $lang['about_company_title'] ?? 'About' ?></h4>
                <p><?= $lang['about_company_text'] ?? 'A brief description of the company.' ?></p>
                <div class="node-details">
                  <p><?= $lang['about_company_details'] ?? 'Here is the full, detailed information about our company...' ?></p>
                </div>
              </div>
            </div>

            <!-- Node 2: Goals -->
            <div class="neural-node pos-2">
              <div class="node-content">
                <button class="close-node-btn"><i class='bx bx-x'></i></button>
                <i class="bx bx-target-lock"></i>
                <h4><?= $lang['about_goals_title'] ?? 'Goals' ?></h4>
                <p><?= $lang['about_goals_text'] ?? 'Our key objectives and targets.' ?></p>
                <div class="node-details">
                  <p><?= $lang['about_goals_details'] ?? 'Our goals are centered around innovation, customer satisfaction...' ?></p>
                </div>
              </div>
            </div>

            <!-- Node 3: Vision -->
            <div class="neural-node pos-3">
              <div class="node-content">
                <button class="close-node-btn"><i class='bx bx-x'></i></button>
                <i class="bx bx-show"></i>
                <h4><?= $lang['about_vision_title'] ?? 'Vision' ?></h4>
                <p><?= $lang['about_vision_text'] ?? 'Our long-term aspirations.' ?></p>
                <div class="node-details">
                  <p><?= $lang['about_vision_details'] ?? 'Our vision is to be the globally recognized leader in our field...' ?></p>
                </div>
              </div>
            </div>

            <!-- Node 4: Mission -->
            <div class="neural-node pos-4">
              <div class="node-content">
                <button class="close-node-btn"><i class='bx bx-x'></i></button>
                <i class="bx bx-paper-plane"></i>
                <h4><?= $lang['about_mission_title'] ?? 'Mission' ?></h4>
                <p><?= $lang['about_mission_text'] ?? 'Our purpose and what we stand for.' ?></p>
                <div class="node-details">
                  <p><?= $lang['about_mission_details'] ?? 'Our mission is to deliver superior, cutting-edge products...' ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End About Us Section -->
    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['services_title'] ?? 'Services' ?></h2>
          <p><?= $lang['services_description'] ?? 'Our Services' ?></p>
        </div>
        <div class="row gy-4">
          <?php if ($services_homepage): foreach ($services_homepage as $service): ?>
              <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                <div class="icon-box <?= htmlspecialchars($service['box_color_class']) ?>">
                  <div class="icon"><img src="assets/img/services/<?= htmlspecialchars($service['image_file']) ?>" alt="<?= htmlspecialchars($service['title']) ?>" class="img-fluid" style="max-height: 40px;" loading="lazy"></div>
                  <h4><a href=""><?= htmlspecialchars($current_lang == 'en' ? $service['title'] : $service['title_ar'])  ?></a></h4>
                  <p><?= htmlspecialchars($current_lang == 'en' ? $service['description'] : $service['description_ar']) ?></p>
                </div>
              </div>
          <?php endforeach;
          endif; ?>
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
            foreach ($features_query as $feature):
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

    <section id="portal-showcase" class="section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['project_title'] ?? 'Our Projects' ?></h2>
          <p><?= $lang['project_description'] ?? 'Check out our beautiful projects.' ?></p>
        </div>

        <div class="portal-grid">
          <?php if (!empty($products_array)): ?>
            <?php foreach ($products_array as $index => $product): ?>
              <!-- تمت إضافة data-product-id لجلب الصور لاحقاً -->
              <div class="portal-card" data-index="<?= $index ?>" data-product-id="<?= $product['id'] ?>" tabindex="0">
                <div class="card-background" style="background-image: url('assets/img/portfolio/<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>');"></div>
                <div class="card-overlay"></div>
                <div class="card-content">
                  <h3><?= ($current_lang == 'ar' && !empty($product['name_ar'])) ? htmlspecialchars($product['name_ar']) : htmlspecialchars($product['name']); ?></h3>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="modal_btn_see">
        <a href="project.php" class="modal-link btn-visit-website project_see_more " target="_blank"><?= $lang['see_project'] ?? 'See All Projects' ?></a>
      </div>
      <!-- This is the Slideshow structure, initially hidden -->
      <div class="portal-slideshow">
        <button class="slideshow-close-btn"><i class='bx bx-x'></i></button>
        <div class="slideshow-nav prev"><i class='bx bx-chevron-left'></i></div>
        <div class="slideshow-nav next"><i class='bx bx-chevron-right'></i></div>
        <div class="slideshow-track">
          <!-- Slides will be injected here by JavaScript -->
        </div>
      </div>
    </section>



    <!-- ======= Team Section ======= -->
    <section id="team" class="team section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['team_title'] ?? 'Team' ?></h2>
          <p><?= $lang['team_description'] ?? 'Our Constellation of Experts' ?></p>
        </div>
        <div class="swiper team-slider">
          <div class="swiper-wrapper">
            <?php if ($team_members_query && $team_members_query->num_rows > 0): foreach ($team_members_query as $member): ?>
                <div class="swiper-slide">
                  <div class="celestial-profile">
                    <div class="profile-image-wrapper">
                      <div class="orbital-ring"></div>
                      <img src="assets/img/team/<?= htmlspecialchars($member['image_file']) ?>" class="img-fluid" alt="<?= htmlspecialchars($member['name']) ?>" loading="lazy">
                    </div>
                    <div class="member-info">
                      <h4><?= htmlspecialchars($member['name']) ?></h4>
                      <span><?= htmlspecialchars($member['position']) ?></span>
                    </div>
                    <div class="social-links">
                      <?php if (!empty($member['website_url'])): ?><a href="<?= htmlspecialchars($member['website_url']) ?>"><i class='bi bi-globe'></i></a><?php endif; ?>
                      <?php if (!empty($member['facebook_url'])): ?><a href="<?= htmlspecialchars($member['facebook_url']) ?>"><i class="bi bi-facebook"></i></a><?php endif; ?>
                      <?php if (!empty($member['instagram_url'])): ?><a href="<?= htmlspecialchars($member['instagram_url']) ?>"><i class="bi bi-instagram"></i></a><?php endif; ?>
                      <?php if (!empty($member['linkedin_url'])): ?><a href="<?= htmlspecialchars($member['linkedin_url']) ?>"><i class="bi bi-linkedin"></i></a><?php endif; ?>
                    </div>
                  </div>
                </div>
            <?php endforeach;
            endif; ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section><!-- End Team Section -->

    <!-- ======= Contact Section (Animated Swap Style) ======= -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['contact_title'] ?? 'Contact' ?></h2>
          <p><?= $lang['contact_description'] ?? '' ?></p>
        </div>

        <div class="contact-page-container show-info-mode">
          <div class="forms-container">
            <div class="contact-info-swap">

              <!-- The Contact Form -->
              <form action="forms/contact.php" method="POST" class="contact-form-main animated-contact-form">
                <h2 class="title"><?= $lang['contact_form_title'] ?? 'Send a Message' ?></h2>
                <div class="input-field">
                  <i class="bx bxs-user"></i>
                  <input type="text" name="name" placeholder="<?= $lang['contact_form_name_placeholder'] ?? 'Full Name' ?>" required>
                </div>
                <div class="input-field">
                  <i class='bx bx-envelope'></i>
                  <input type="email" name="email" placeholder="<?= $lang['contact_form_email_placeholder'] ?? 'Email' ?>" required>
                </div>
                <div class="input-field">
                  <i class='bx bxs-pen'></i>
                  <input type="text" name="subject" placeholder="<?= $lang['contact_form_subject_placeholder'] ?? 'Subject' ?>" required>
                </div>
                <div class="input-field textarea-field">
                  <i class='bx bxs-comment-dots'></i>
                  <textarea name="message" placeholder="<?= $lang['contact_form_message_placeholder'] ?? 'Your Message' ?>" required></textarea>
                </div>

                <div class="my-3">
                  <div class="loading"><?= $lang['form_loading'] ?? 'Loading' ?></div>
                  <div class="error-message"></div>
                  <div class="sent-message"><?= $lang['form_sent_message'] ?? 'Your message has been sent. Thank you!' ?></div>
                </div>

                <input type="submit" value="<?= $lang['contact_form_send_btn'] ?? 'Send' ?>" class="btns solid">
              </form>

              <div class="contact-info-panel">
                <h2 class="title"><?= $lang['contact_info_title'] ?? 'Our Information' ?></h2>
                <div class="info-item">
                  <i class='bx bxs-map-pin'></i>
                  <p>
                    <?php
                    if ($current_lang == 'ar' && !empty($contact['address_ar'])) {
                      echo htmlspecialchars($contact['address_ar']);
                    } else {
                      echo htmlspecialchars($contact['address'] ?? 'Address not available');
                    }
                    ?>
                  </p>
                </div>
                <div class="info-item">
                  <i class='bx bxs-phone-call'></i>
                  <p><?= htmlspecialchars($contact['phone'] ?? 'Phone not available') ?></p>
                </div>
                <div class="info-item">
                  <i class='bx bxs-paper-plane'></i>
                  <p><?= htmlspecialchars($contact['email'] ?? 'Email not available') ?></p>
                </div>
                <div class="info-social-links">
                  <?php if (!empty($settings['whatsapp_number'])): ?>
                    <a href="https://wa.me/<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $settings['whatsapp_number'])) ?>" target="_blank" class="social-icon"><i class="bx bxl-whatsapp"></i></a>
                  <?php endif; ?>

                  <?php if (!empty($settings['fb_link'])): ?>
                    <a href="<?= htmlspecialchars($settings['fb_link']) ?>" target="_blank" class="social-icon"><i class="bx bxl-facebook"></i></a>
                  <?php endif; ?>

                  <?php if (!empty($settings['instagram_link'])): ?>
                    <a href="<?= htmlspecialchars($settings['instagram_link']) ?>" target="_blank" class="social-icon"><i class="bx bxl-instagram"></i></a>
                  <?php endif; ?>

                  <?php if (!empty($settings['linkedin_link'])): ?>
                    <a href="<?= htmlspecialchars($settings['linkedin_link']) ?>" target="_blank" class="social-icon"><i class="bx bxl-linkedin"></i></a>
                  <?php endif; ?>
                </div>
              </div>

            </div>
          </div>

          <div class="panels-container">
            <div class="panel left-panel">
              <div class="content">
                <h3><?= $lang['contact_panel_details_title'] ?? 'Contact Details' ?></h3>
                <p><?= $lang['contact_panel_details_desc'] ?? 'Need our address or phone number? Find all of our contact information here.' ?></p>
                <button class="btns transparent" id="show-info-btn"><?= $lang['contact_panel_show_info_btn'] ?? 'Show Info' ?></button>
              </div>
              <img src="assets/img/contact/info.png" class="image" alt="Contact Info Illustration">
            </div>

            <div class="panel right-panel">
              <div class="content">
                <h3><?= $lang['contact_panel_question_title'] ?? 'Have a Question?' ?></h3>
                <p><?= $lang['contact_panel_question_desc'] ?? 'Fill out our contact form and our team will get back to you as soon as possible.' ?></p>
                <button class="btns transparent" id="show-form-btn"><?= $lang['contact_panel_message_us_btn'] ?? 'Message Us' ?></button>
              </div>
              <img src="assets/img/contact/form.png" class="image" alt="Contact Form Illustration">
            </div>
          </div>
        </div>
      </div>
    </section><!-- End Contact Section -->
  </main><!-- End #main -->
  </main>

  <!-- ======================================================= -->
  <!-- START: Floating Animated Logo (NEW POSITION)          -->
  <!-- ======================================================= -->
  <div class="floating-logo-animation">
    <svg width="194" height="97" viewBox="0 0 194 97" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="light-flare" x1="0%" y1="0%" x2="100%" y2="0%">
          <stop offset="0%" stop-color="rgba(138, 79, 255, 0)" />
          <stop offset="50%" stop-color="rgba(255, 255, 255, 0.8)" />
          <stop offset="100%" stop-color="rgba(138, 79, 255, 0)" />
        </linearGradient>
      </defs>
      <path class="logo-path " d="M519.74,326.75l-88.51,166.5c-9.29,17.48-34.53,16.89-43-1l-78-164.81a24.07,24.07,0,0,0-21.75-13.77H275.23a24.07,24.07,0,0,0-21.87,34.12L387.48,639.57c8.48,18.44,34.57,18.75,43.49.52L555.28,385.78a24.07,24.07,0,0,1,21-13.49l96.43-2.61-.36.77-76.05,163c-8.6,18.44-34.79,18.54-43.54.17l-4.86-10.2c-9-18.8-35.93-18.13-43.94,1.09h0a24.07,24.07,0,0,0,.72,20.07l50.91,101.17a24.07,24.07,0,0,0,42.84.31l154.91-297.1a24.07,24.07,0,0,0-21.37-35.19L541,314A24.07,24.07,0,0,0,519.74,326.75ZM694.82,344v0h0Zm0-13.43h0Z" />
    </svg>
  </div>



  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="assets/vendor/purecounter/purecounter.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <script>
    /**
     * Interactive "About Us" Section Logic
     */
    document.addEventListener('DOMContentLoaded', () => {
      const aboutContainer = document.querySelector('.about-us-container');
      if (aboutContainer) {
        const nodes = aboutContainer.querySelectorAll('.neural-node');

        const resetNodes = (exceptNode = null) => {
          aboutContainer.classList.remove('node-active-mode');
          nodes.forEach(n => {
            if (n !== exceptNode) {
              n.classList.remove('active');
            }
          });
        };

        nodes.forEach(node => {
          const closeBtn = node.querySelector('.close-node-btn');

          node.addEventListener('click', (e) => {
            // Do nothing if the close button was clicked or if the node is already active
            if ((closeBtn && closeBtn.contains(e.target)) || node.classList.contains('active')) {
              return;
            }
            // Reset other nodes and activate the clicked one
            resetNodes(node);
            aboutContainer.classList.add('node-active-mode');
            node.classList.add('active');
          });

          if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
              e.stopPropagation(); // Prevent the node's click event from firing
              resetNodes(); // Resets all nodes, including the current one
            });
          }
        });
      }


      //project js
      const allProjectsData = <?= json_encode($products_array, JSON_UNESCAPED_UNICODE); ?>;
      const currentLang = '<?= $current_lang; ?>';

      const grid = document.querySelector('.portal-grid');
      const slideshow = document.querySelector('.portal-slideshow');
      const slideshowTrack = slideshow.querySelector('.slideshow-track');
      const closeBtn = slideshow.querySelector('.slideshow-close-btn');
      const nextBtn = slideshow.querySelector('.slideshow-nav.next');
      const prevBtn = slideshow.querySelector('.slideshow-nav.prev');
      const htmlEl = document.documentElement;

      if (!grid || !slideshow || allProjectsData.length === 0) return;

      // إنشاء عناصر السلايد الأساسية بدون الصور المصغرة
      const slideElements = allProjectsData.map(product => {
        const slide = document.createElement('div');
        slide.className = 'slideshow-slide';
        // إضافة معرف المنتج للسلايد للوصول إليه لاحقاً
        slide.dataset.productId = product.id;

        const productName = (currentLang === 'ar' && product.name_ar) ? product.name_ar : product.name;
        const productDesc = (currentLang === 'ar' && product.description_ar) ? product.description_ar : (product.description || '');
        const categories = product.category_names || '';
        const visitText = currentLang === 'ar' ? 'زيارة الموقع' : 'Visit Website';

        slide.innerHTML = `
            <div class="slide-bg-container">
                <div class="slide-bg" style="background-image: url('assets/img/portfolio/${product.image}');"></div>
            </div>
            <div class="slide-details">
                <div class="container">
                    <span class="slide-category">${categories}</span>
                    <h2 class="slide-title">${productName}</h2>
                    
                    ${(product.details_url && product.details_url !== '#') ? `<a href="${product.details_url}" class="slide-link" target="_blank">${visitText}</a>` : ''}
                    <!-- حاوية الصور المصغرة، ستكون فارغة في البداية -->
                    <div class="slide-thumbnails-container"></div>
                </div>
            </div>
        `;
        return slide;
      });
      slideshowTrack.append(...slideElements);

      let currentIndex = 0;
      let isAnimating = false;

      // --- NEW: Function to load additional images on demand ---
      async function loadThumbnailsForSlide(slideElement) {
        // التحقق إذا تم تحميل الصور مسبقاً لمنع الطلبات المتكررة
        if (slideElement.dataset.imagesLoaded === 'true') {
          return;
        }

        const productId = slideElement.dataset.productId;
        const mainImage = allProjectsData.find(p => p.id == productId).image;
        const thumbnailsContainer = slideElement.querySelector('.slide-thumbnails-container');
        thumbnailsContainer.innerHTML = '<span>Loading...</span>'; // رسالة تحميل مؤقتة

        try {
          const response = await fetch(`project/get_product_images.php?id=${productId}`);
          if (!response.ok) throw new Error('Network response was not ok');

          const additionalImages = await response.json();

          // دمج الصورة الرئيسية مع الصور الإضافية
          const allImages = [mainImage, ...additionalImages];

          thumbnailsContainer.innerHTML = ''; // إفراغ حاوية التحميل

          // عرض المعرض فقط إذا كان هناك أكثر من صورة
          if (allImages.length > 1) {
            const thumbnailsHTML = allImages.map((img, index) => `
                    <div 
                      class="thumbnail-item ${index === 0 ? 'is-active' : ''}" 
                      style="background-image: url('assets/img/portfolio/${img}');"
                      data-src="assets/img/portfolio/${img}">
                    </div>
                `).join('');
            thumbnailsContainer.innerHTML = thumbnailsHTML;
          }

          // وضع علامة تفيد بأن الصور قد تم تحميلها
          slideElement.dataset.imagesLoaded = 'true';

        } catch (error) {
          console.error('Failed to fetch additional images:', error);
          thumbnailsContainer.innerHTML = '<span>Failed to load images.</span>';
        }
      }

      function updateSlideshow(newIndex, direction) {
        if (isAnimating) return;
        isAnimating = true;

        const oldIndex = currentIndex;
        currentIndex = (newIndex + slideElements.length) % slideElements.length;

        const oldSlide = slideElements[oldIndex];
        const newSlide = slideElements[currentIndex];

        // --- NEW: Load images for the new active slide ---
        loadThumbnailsForSlide(newSlide);

        const inClass = direction === 'next' ? 'slide-in-next' : 'slide-in-prev';
        const outClass = direction === 'next' ? 'slide-out-next' : 'slide-out-prev';

        newSlide.classList.add('is-active', inClass);
        oldSlide.classList.add(outClass);

        setTimeout(() => {
          oldSlide.classList.remove('is-active', outClass);
          newSlide.classList.remove(inClass);
          isAnimating = false;
        }, 600);
      }

      function openSlideshow(startIndex) {
        currentIndex = startIndex;
        const firstSlide = slideElements[startIndex];

        slideElements.forEach((slide, index) => {
          slide.classList.toggle('is-active', index === startIndex);
        });

        // تحميل الصور لأول سلايد يتم فتحه
        loadThumbnailsForSlide(firstSlide);

        htmlEl.classList.add('slideshow-open');
      }

      function closeSlideshow() {
        htmlEl.classList.remove('slideshow-open');
      }

      nextBtn.addEventListener('click', () => updateSlideshow(currentIndex + 1, 'next'));
      prevBtn.addEventListener('click', () => updateSlideshow(currentIndex - 1, 'prev'));

      grid.querySelectorAll('.portal-card').forEach(card => {
        card.addEventListener('click', () => {
          openSlideshow(parseInt(card.dataset.index, 10));
        });
      });

      closeBtn.addEventListener('click', closeSlideshow);

      // منطق النقر على الصور المصغرة (Thumbnails) يبقى كما هو
      slideshowTrack.addEventListener('click', (e) => {
        if (e.target.classList.contains('thumbnail-item')) {
          const clickedThumbnail = e.target;
          const activeSlide = slideshowTrack.querySelector('.slideshow-slide.is-active');
          if (!activeSlide) return;

          const newImageSrc = clickedThumbnail.dataset.src;
          const slideBg = activeSlide.querySelector('.slide-bg');
          slideBg.style.backgroundImage = `url('${newImageSrc}')`;

          const parentContainer = clickedThumbnail.parentElement;
          parentContainer.querySelector('.thumbnail-item.is-active')?.classList.remove('is-active');
          clickedThumbnail.classList.add('is-active');
        }
      });
    });
  </script>

  <script src="assets/js/main.js"></script>
</body>

</html>