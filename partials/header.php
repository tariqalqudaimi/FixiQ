<!-- ======= Header ======= -->
<header id="header">
    <div class="container-fluid d-flex align-items-center justify-content-between">

        <!-- 1. الشعار -->
        <h1 class="logo"><a href="index.php?lang=<?= $current_lang ?>">     <img src="<?= htmlspecialchars($settings['company_logo'] ?? 'assets/img/Asset 3.png') ?>" alt="<?= htmlspecialchars($settings['company_name'] ?? 'Logo') ?>" class="logo">
  </a></h1>

        <!-- 2. قائمة التنقل (تحتوي على قائمة الحاسوب وزر الهاتف) -->
        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="#hero"><?= $lang['home_link'] ?? 'Home' ?></a></li>
                <li class="dropdown"><a href="#"><span><?= $lang['about_link'] ?? 'About' ?></span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="#features"><?= $lang['features_link'] ?? 'Features' ?></a></li>
                        <li><a href="#team"><?= $lang['team_link'] ?? 'Team' ?></a></li>
                    </ul>
                </li>
                <li><a class="nav-link scrollto" href="#services"><?= $lang['services_link'] ?? 'Services' ?></a></li>
                <li><a class="nav-link scrollto" href="#portfolio"><?= $lang['portfolio_link'] ?? 'Portfolio' ?></a></li>
                <li><a class="nav-link scrollto" href="#contact"><?= $lang['contact_link'] ?? 'Contact' ?></a></li>
            </ul>
            
            <!-- زر الهمبرجر: يتم إظهاره وإخفاؤه عبر CSS -->
            <i class="bi bi-list mobile-nav-toggle"></i>

        </nav><!-- .navbar -->
        
        <!-- هذا الصندوق الفارغ سيستقبل قائمة الهاتف ديناميكيًا -->
        <div class="navbar-mobile"></div>

        <!-- 3. العناصر اليمنى (محدد اللغة) -->
        <div class="header-right-actions">
            <div class="language-switcher dropdown">
                <a href="#" class="btn btn-sm dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-globe"></i> <span class="d-none d-lg-inline"><?= $lang['lang'] ?? 'Language' ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                    <li><a class="dropdown-item" href="?lang=en">English</a></li>
                    <li><a class="dropdown-item" href="?lang=ar">العربية</a></li>
                </ul>
            </div>
        </div>

    </div>
</header><!-- End Header -->