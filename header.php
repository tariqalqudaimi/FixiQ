<header id="header">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <h1 class="logo"><a href="index.php?lang=<?= $current_lang ?>"> <img src="<?= htmlspecialchars($settings['company_logo'] ?? 'assets\img\Artboard 8-8.png') ?>" alt="<?= htmlspecialchars($settings['company_name'] ?? 'Logo') ?>" class="logo">
            </a></h1>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="#hero"><?= $lang['home_link'] ?? 'Home' ?></a></li>
                <li class="dropdown"><span class="d-flex align-items-center justify-content-between"><?= $lang['about_link'] ?? 'About' ?> <i class="bi bi-chevron-down"></i></span>
                    <ul>
                        <li><a href="#features"><?= $lang['features_link'] ?? 'Features' ?></a></li>
                        <li><a href="#team"><?= $lang['team_link'] ?? 'Team' ?></a></li>
                    </ul>
                </li>
                <li><a class="nav-link scrollto" href="#services"><?= $lang['services_link'] ?? 'Services' ?></a></li>
                <li><a class="nav-link scrollto" href="#project"><?= $lang['project_link'] ?? 'Projects' ?></a></li>
                <li><a class="nav-link scrollto" href="#contact"><?= $lang['contact_link'] ?? 'Contact' ?></a></li>
                <div class="language-switcher dropdown">
                    <a href="#" class="btn btn-sm dropdown-toggle d-flex align-items-center justify-content-between" role="button" data-bs-toggle="dropdown" aria-expanded="false">    
                        <i class="bi bi-globe"></i> <span>Language</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                        <li><a class="dropdown-item" href="?lang=en">English</a></li>
                        <li><a class="dropdown-item" href="?lang=ar">العربية</a></li>
                    </ul>
            </div>
            </ul>

            <i class="bi bi-list mobile-nav-toggle"></i>

        </nav>

        <div class="navbar-mobile"></div>

    </div>
</header>