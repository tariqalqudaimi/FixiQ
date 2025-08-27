<?php
// This check ensures we don't try to re-connect if db.php was already included.
if (!isset($dbcon)) {
    require_once __DIR__ . '/../admin/db.php';
}

// Fetch the service titles specifically for the footer links.
$footer_services_query = $dbcon->query("SELECT title,title_ar FROM services ORDER BY id DESC LIMIT 5");
?>

<!-- ======= Footer ======= -->
<footer id="footer">

  <div class="footer-top">
    <div class="container">
      <div class="row">

        <div class="col-lg-3 col-md-6 footer-contact">
          <h3><?= htmlspecialchars($settings['company_name'] ?? 'Techie') ?></h3>
          <p>
            <?= nl2br(htmlspecialchars($contact['address'] ?? 'A108 Adam Street <br>New York, NY 535022<br>United States')) ?><br><br>
            <strong>Phone:</strong> <?= htmlspecialchars($contact['phone'] ?? '+1 5589 55488 55') ?><br>
            <strong>Email:</strong> <?= htmlspecialchars($contact['email'] ?? 'info@example.com') ?><br>
          </p>
        </div>

        <div class="col-lg-2 col-md-6 footer-links">
          <h4><?= $lang['footer_useful_links'] ?></h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="#Home_link"><?= $lang['home_link'] ?></a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#services"><?= $lang['services_link'] ?></a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="services.php?lang=<?= $current_lang ?>"><?= $lang['portfolio_link'] ?></a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#"><?= $lang['team_link'] ?></a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#"><?= $lang['contact_link'] ?></a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6 footer-links">
          <h4><?= $lang['footer_our_services'] ?></h4>
          <ul>
            <?php
            if (isset($footer_services_query) && $footer_services_query->num_rows > 0) {
                foreach ($footer_services_query as $service) {
                    echo '<li><i class="bx bx-chevron-right"></i> <a href="#services">' . htmlspecialchars($current_lang == 'en' ?$service['title']:$service['title_ar']) . '</a></li>';
                }
            }
            ?>
          </ul>
        </div>

        <div class="col-lg-4 col-md-6 footer-newsletter">
          <h4><?= $lang['footer_join_newsletter'] ?></h4>
          <p><?= $lang['footer_newsletter_placeholder'] ?></p>
          <form action="" method="post">
            <input type="email" name="email"><input type="submit" value="<?= $lang['footer_subscribe_btn'] ?>">
          </form>
        </div>

      </div>
    </div>
  </div>

  <div class="container">
    <div class="copyright-wrap d-md-flex py-4">
      <div class="me-md-auto text-center text-md-start">
        <div class="copyright">
          &copy; Copyright <strong><span><?= htmlspecialchars($settings['company_name'] ?? 'Techie') ?></span></strong>. All Rights Reserved
        </div>
        <div class="credits">
          <?= $lang['footer_designed_by'] ?> <a href="https://bootstrapmade.com/"><?=$settings['company_name'] ?></a>
        </div>
      </div>
      <div class="social-links text-center text-md-right pt-3 pt-md-0">
        <a href="<?= htmlspecialchars($settings['twitter_link'] ?? '#') ?>" class="twitter"><i class="bx bxl-twitter"></i></a>
        <a href="<?= htmlspecialchars($settings['fb_link'] ?? '#') ?>" class="facebook"><i class="bx bxl-facebook"></i></a>
        <a href="<?= htmlspecialchars($settings['instagram_link'] ?? '#') ?>" class="instagram"><i class="bx bxl-instagram"></i></a>
        <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
        <a href="<?= htmlspecialchars($settings['linkedin_link'] ?? '#') ?>" class="linkedin"><i class="bx bxl-linkedin"></i></a>
      </div>
    </div>
  </div>
</footer><!-- End Footer -->