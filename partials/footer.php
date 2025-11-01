
<footer class="site-footer text-white pt-5 pb-4">
  <div class="container">
    <div class="row">
      <!-- Footer About / Description -->
      <div class="col-lg-4 col-md-6 mb-4">
        <h5 class="footer-title"><?= $lang['company_name'] ?? 'Company Name' ?></h5>
        <p>
          <?= $lang['footer_description'] ?? 'Your company description goes here.' ?>
        </p>
      </div>

      <!-- Footer Links -->
      <div class="col-lg-4 col-md-6 mb-4">
        <h5 class="footer-title"><?= $lang['footer_links'] ?? 'Quick Links' ?></h5>
        <ul class="list-unstyled">
          <li><a href="index.php?lang=<?= $current_lang ?>" class="text-white"><?= $lang['home_link'] ?></a></li>
          <li><a href="#about" class="text-white"><?= $lang['about_link'] ?></a></li>
          <li><a href="#services" class="text-white"><?= $lang['services_link'] ?></a></li>
          <li><a href="#team" class="text-white"><?= $lang['team_link'] ?></a></li>
          <li><a href="#contact" class="text-white"><?= $lang['contact_link'] ?></a></li>
        </ul>
      </div>

      <!-- Footer Contact -->
      <div class="col-lg-4 col-md-12 mb-4">
        <h5 class="footer-title"><?= $lang['contact_info_title'] ?? 'Contact Info' ?></h5>
        <p><i class='bx bxs-map-pin'></i> <?= $lang['company_address'] ?? 'Address here' ?></p>
        <p><i class='bx bxs-phone-call'></i> <?= $lang['company_phone'] ?? '+0000000000' ?></p>
        <p><i class='bx bxs-envelope'></i> <?= $lang['company_email'] ?? 'info@example.com' ?></p>
        <div class="footer-social-links mt-2">
          <a href="<?= $lang['fb_link'] ?? '#' ?>" target="_blank" class="social-icon"><i class='bx bxl-facebook'></i></a>
          <a href="<?= $lang['instagram_link'] ?? '#' ?>" target="_blank" class="social-icon"><i class='bx bxl-instagram'></i></a>
          <a href="<?= $lang['linkedin_link'] ?? '#' ?>" target="_blank" class="social-icon"><i class='bx bxl-linkedin'></i></a>
          <a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$lang['whatsapp_number'] ?? '000000000') ?>" target="_blank" class="social-icon"><i class='bx bxl-whatsapp'></i></a>
        </div>
      </div>
    </div>

    <div class="footer-bottom mt-4 text-center">
      <p class="mb-0">&copy; <?= date('Y') ?> <?= $lang['company_name'] ?? 'Company Name' ?>. <?= $lang['footer_rights'] ?? 'All Rights Reserved.' ?></p>
    </div>
  </div>
</footer>

