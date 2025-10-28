
<header id="header">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <h1 class="logo"><a href="index.php?lang=<?= $current_lang ?>"> <img src="<?= htmlspecialchars($settings['company_logo'] ?? 'assets/img/Artboard 8-8.png') ?>" alt="<?= htmlspecialchars($settings['company_name'] ?? 'Logo') ?>" class="logo">
            </a></h1>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="index.php#hero"><?= $lang['home_link'] ?? 'Home' ?></a></li>

                
                <li class="dropdown"><a href=""><span><?= $lang['about_link'] ?? 'About' ?></span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="index.php#features"><?= $lang['features_link'] ?? 'Features' ?></a></li>
                        <li><a href="index.php#team"><?= $lang['team_link'] ?? 'Team' ?></a></li>
                    </ul>
                </li>

                <li><a class="nav-link scrollto" href="index.php#services"><?= $lang['services_link'] ?? 'Services' ?></a></li>
                <li><a class="nav-link scrollto" href="index.php#project"><?= $lang['project_link'] ?? 'Projects' ?></a></li>
                <li><a class="nav-link scrollto" href="index.php#contact"><?= $lang['contact_link'] ?? 'Contact' ?></a></li>

              
                <li class="dropdown">
                    <a href="#Language"><span><i class="bi bi-globe"></i> Language</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="?lang=en">English</a></li>
                        <li><a href="?lang=ar">العربية</a></li>
                    </ul>
                </li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
        <div class="navbar-mobile"></div>

    </div>
</header>