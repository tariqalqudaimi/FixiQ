<header id="header">
  <!-- الشعار على اليسار -->
  <h1 class="logo me-auto"><a href="index.php?lang=<?= $current_lang ?>"><?= htmlspecialchars($settings['company_name']) ?></a></h1>
  
  <!-- قائمة التنقل الرئيسية -->
  <nav id="navbar" class="navbar order-last order-lg-0">
    <ul>
      <li><a class="nav-link scrollto active" href="#hero"><?= $lang['home_link'] ?></a></li>
      <li><a class="nav-link scrollto" href="#services"><?= $lang['services_link'] ?></a></li>
      <li><a class="nav-link scrollto" href="#portfolio"><?= $lang['portfolio_link'] ?></a></li>
      <li><a class="nav-link scrollto" href="#team"><?= $lang['team_link'] ?></a></li>
      <li><a class="nav-link scrollto" href="#contact"><?= $lang['contact_link'] ?></a></li>
    </ul>
    <i class="bi bi-list mobile-nav-toggle"></i> <!-- أيقونة الهامبرغر للجوال -->
  </nav><!-- .navbar -->

  <!-- العناصر على اليمين -->
  <div class="header-right-actions d-flex align-items-center">
  
    <!-- محدد اللغة -->
    <div class="language-switcher dropdown">
      <a href="#" class="btn btn-sm dropdown-toggle" role="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-globe"></i> <span class="d-none d-lg-inline"><?= $lang['lang'] ?></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="languageDropdown">
        <li><a class="dropdown-item" href="?lang=en">English</a></li>
        <li><a class="dropdown-item" href="?lang=ar">العربية</a></li>
      </ul>
    </div>
  </div>
</header><!-- End Header -->