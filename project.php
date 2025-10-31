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
");

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
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
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
    </section>
  </main>

  <!-- This is the Slideshow structure, initially hidden -->
  <div class="portal-slideshow">
    <button class="slideshow-close-btn"><i class='bx bx-x'></i></button>
    <div class="slideshow-nav prev"><i class='bx bx-chevron-left'></i></div>
    <div class="slideshow-nav next"><i class='bx bx-chevron-right'></i></div>
    <div class="slideshow-track">
    </div>
  </div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
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

    const slideElements = allProjectsData.map(product => {
        const slide = document.createElement('div');
        slide.className = 'slideshow-slide';
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

    async function loadThumbnailsForSlide(slideElement) {
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
            
            const allImages = [mainImage, ...additionalImages];
            
            thumbnailsContainer.innerHTML = ''; 

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
</body>
</html>