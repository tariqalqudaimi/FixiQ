<?php
require_once 'error_handler.php';
require_once 'admin/Database/db.php';

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
  include 'lang/en.php';
}

$settings = $dbcon->query("SELECT * FROM company_settings WHERE id=1")->fetch_assoc();
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

// Fetch all products into an array to pass to JavaScript
$products_array = [];
if ($products_query) {
    while($row = $products_query->fetch_assoc()) {
        $products_array[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>" dir="<?= ($current_lang == 'ar' ? 'rtl' : 'ltr') ?>">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= htmlspecialchars($settings['company_name'] ?? 'Company Name') ?> - <?= $lang['project_link'] ?? 'Projects' ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="assets/img/Artboard 8-8.png" rel="icon">
  <link href="assets/img/Artboard 8-8.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Sora:300,300i,400,400i,500,500i,600,600i,700,700i|Tajwal:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">

  <Style>
    /*
  CRITICAL FIX: This rule is essential to prevent scrollbars from
  interfering when the slideshow is active.
*/
html.slideshow-open {
    overflow: hidden;
}

/*--------------------------------------------------------------
# 10. Projects Section (The Portal Showcase - Stabilized)
--------------------------------------------------------------*/
#portal-showcase {
    padding-top: 120px;
    padding-bottom: 120px;
}

.portal-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}

.portal-card {
    aspect-ratio: 3 / 4;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}
.portal-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.card-background {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transition: transform 0.5s ease;
}
.portal-card:hover .card-background {
    transform: scale(1.1);
}

.card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(var(--primary-dark-rgb), 0.8) 0%, transparent 60%);
}

.card-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 25px;
    color: #fff;
}
.card-content h3 {
    font-size: 1.8rem;
    font-weight: 600;
}

/* --- Slideshow Styles (Robust Version) --- */
.portal-slideshow {
    position: fixed;
    inset: 0;
    z-index: 9999; /* Extremely high z-index */
    background: var(--primary-dark);
    opacity: 0;
    visibility: hidden;
    transform: scale(1.1);
    transition: opacity 0.4s ease, visibility 0s linear 0.4s, transform 0.4s ease;
}
html.slideshow-open .portal-slideshow {
    opacity: 1;
    visibility: visible;
    transform: scale(1);
    transition: opacity 0.4s ease, visibility 0s linear 0s, transform 0.4s ease;
}

.slideshow-track {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.slideshow-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    pointer-events: none;
    display: grid;
    grid-template-columns: 1fr;
}
.slideshow-slide.is-active {
    opacity: 1;
    pointer-events: auto;
}

.slide-bg-container {
    grid-area: 1 / 1;
    overflow: hidden;
}
.slide-bg {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    animation: slide-bg-zoom-in 0.8s ease-out forwards;
}
@keyframes slide-bg-zoom-in {
    from { transform: scale(1.1); }
    to { transform: scale(1); }
}

.slide-details {
    grid-area: 1 / 1;
    position: relative;
    background: linear-gradient(90deg, rgba(var(--primary-dark-rgb), 0.8) 0%, rgba(var(--primary-dark-rgb), 0.4) 50%, transparent 100%);
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 5%;
}
html[dir="rtl"] .slide-details {
    background: linear-gradient(-90deg, rgba(var(--primary-dark-rgb), 0.8) 0%, rgba(var(--primary-dark-rgb), 0.4) 50%, transparent 100%);
    text-align: right;
}

.slide-category, .slide-title, .slide-description, .slide-link {
    opacity: 0;
    transform: translateY(30px);
    animation: slide-content-fade-in 0.6s ease-out forwards;
}
.slideshow-slide.is-active .slide-category { animation-delay: 0.3s; }
.slideshow-slide.is-active .slide-title { animation-delay: 0.4s; }
.slideshow-slide.is-active .slide-description { animation-delay: 0.5s; }
.slideshow-slide.is-active .slide-link { animation-delay: 0.6s; }

@keyframes slide-content-fade-in {
    to { opacity: 1; transform: translateY(0); }
}

.slide-category { color: var(--accent-purple); font-weight: 500; }
.slide-title { font-size: 4rem; font-weight: 700; margin: 15px 0; }
.slide-description { max-width: 600px; font-size: 1.1rem; line-height: 1.7; color: var(--text-muted); }
.slide-link {
    margin-top: 30px;
    border: 2px solid var(--accent-purple);
    color: var(--accent-purple);
    padding: 14px 35px;
    border-radius: 50px;
    text-decoration: none;
    align-self: flex-start;
    transition: all 0.3s ease;
}
html[dir="rtl"] .slide-link { align-self: flex-end; }
.slide-link:hover { background: var(--accent-purple); color: #fff; }


/* --- Slideshow Navigation & Close Button --- */
.slideshow-close-btn, .slideshow-nav {
    position: absolute;
    z-index: 10;
    background: rgba(0,0,0,0.3);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    font-size: 28px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}
.slideshow-close-btn:hover, .slideshow-nav:hover {
    background: var(--accent-purple);
    transform: scale(1.1) translateY(-50%);
}
.slideshow-close-btn { top: 30px; right: 30px; }
html[dir="rtl"] .slideshow-close-btn { right: auto; left: 30px; }
.slideshow-nav { top: 50%; transform: translateY(-50%); }
.slideshow-nav.prev { left: 30px; }
.slideshow-nav.next { right: 30px; }
html[dir="rtl"] .slideshow-nav.prev { left: auto; right: 30px; }
html[dir="rtl"] .slideshow-nav.next { right: auto; left: 30px; }

/* --- Slide transition animations --- */
.slideshow-slide.slide-in-next { animation: slide-in-next 0.6s ease-out forwards; }
.slideshow-slide.slide-out-next { animation: slide-out-next 0.6s ease-out forwards; }
.slideshow-slide.slide-in-prev { animation: slide-in-prev 0.6s ease-out forwards; }
.slideshow-slide.slide-out-prev { animation: slide-out-prev 0.6s ease-out forwards; }

@keyframes slide-in-next { from { transform: translateX(100%); } to { transform: translateX(0); } }
@keyframes slide-out-next { from { transform: translateX(0); } to { transform: translateX(-100%); } }
@keyframes slide-in-prev { from { transform: translateX(-100%); } to { transform: translateX(0); } }
@keyframes slide-out-prev { from { transform: translateX(0); } to { transform: translateX(100%); } }


@media (max-width: 991px) {
    .slide-details { background: rgba(var(--primary-dark-rgb), 0.7); text-align: center; }
    .slide-title { font-size: 2.5rem; }
    .slide-description { margin-left: auto; margin-right: auto; }
    .slide-link { align-self: center; }
}
  </Style>
</head>

<body>


  <main id="main">
    <section id="portal-showcase" class="section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2><?= $lang['project_title'] ?? 'Our Projects' ?></h2>
          <p><?= $lang['project_description'] ?? 'Check out our beautiful projects.' ?></p>
        </div>

        <div class="portal-grid">
          <?php if (!empty($products_array)): ?>
            <?php foreach ($products_array as $index => $product): ?>
              <div class="portal-card" data-index="<?= $index ?>" tabindex="0">
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
    </section>
  </main>

  <!-- This is the Slideshow structure, initially hidden -->
  <div class="portal-slideshow">
    <button class="slideshow-close-btn"><i class='bx bx-x'></i></button>
    <div class="slideshow-nav prev"><i class='bx bx-chevron-left'></i></div>
    <div class="slideshow-nav next"><i class='bx bx-chevron-right'></i></div>
    <div class="slideshow-track">
      <!-- Slides will be injected here by JavaScript -->
    </div>
  </div>

 

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <!-- Portal Showcase Logic -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const allProjectsData = <?= json_encode($products_array); ?>;
      const currentLang = '<?= $current_lang; ?>';

      const grid = document.querySelector('.portal-grid');
      const slideshow = document.querySelector('.portal-slideshow');
      const slideshowTrack = slideshow.querySelector('.slideshow-track');
      const closeBtn = slideshow.querySelector('.slideshow-close-btn');
      const nextBtn = slideshow.querySelector('.slideshow-nav.next');
      const prevBtn = slideshow.querySelector('.slideshow-nav.prev');
      const htmlEl = document.documentElement;
      
      if (!grid || !slideshow || allProjectsData.length === 0) return;

      // Create all slide elements once and store them
      const slideElements = allProjectsData.map(product => {
        const slide = document.createElement('div');
        slide.className = 'slideshow-slide';
        
        const productName = (currentLang === 'ar' && product.name_ar) ? product.name_ar : product.name;
        const productDesc = (currentLang === 'ar' && product.description_ar) ? product.description_ar : product.description;
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
              <p class="slide-description">${productDesc}</p>
              ${product.details_url ? `<a href="${product.details_url}" class="slide-link" target="_blank">${visitText}</a>` : ''}
            </div>
          </div>
        `;
        return slide;
      });
      slideshowTrack.append(...slideElements);
      
      let currentIndex = 0;
      let isAnimating = false;

      function updateSlideshow(newIndex, direction) {
        if (isAnimating) return;
        isAnimating = true;

        const oldIndex = currentIndex;
        currentIndex = (newIndex + slideElements.length) % slideElements.length;

        const oldSlide = slideElements[oldIndex];
        const newSlide = slideElements[currentIndex];
        
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
          slideElements.forEach((slide, index) => {
              slide.classList.toggle('is-active', index === startIndex);
          });
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
    });
  </script>
</body>
</html>