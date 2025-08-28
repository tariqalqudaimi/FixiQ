(function() {
  "use strict";

  /**
   * Helper function to select elements
   */
  const select = (el, all = false) => {
    el = el.trim();
    return all ? [...document.querySelectorAll(el)] : document.querySelector(el);
  }

  /**
   * Helper function to add event listeners
   */
  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all);
    if (selectEl) {
      if (all) {
        selectEl.forEach(e => e.addEventListener(type, listener));
      } else {
        selectEl.addEventListener(type, listener);
      }
    }
  }

  /**
   * Helper function for scroll events
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener);
  }

  /**
   * Scrolls to an element with header offset
   */
  const scrollto = (el) => {
    let header = select('#header');
    let offset = header.offsetHeight;
    if (!header.classList.contains('header-scrolled')) {
      offset -= 16;
    }
    let elementPos = select(el).offsetTop;
    window.scrollTo({
      top: elementPos - offset,
      behavior: 'smooth'
    });
  }

  /**
   * Manages the .header-scrolled class on #header
   */
  let selectHeader = select('#header');
  if (selectHeader) {
    const headerScrolled = () => {
      window.scrollY > 100 ? selectHeader.classList.add('header-scrolled') : selectHeader.classList.remove('header-scrolled');
    }
    window.addEventListener('load', headerScrolled);
    onscroll(document, headerScrolled);
  }

  /**
   * Back to top button visibility
   */
  let backtotop = select('.back-to-top');
  if (backtotop) {
    const toggleBacktotop = () => {
      window.scrollY > 100 ? backtotop.classList.add('active') : backtotop.classList.remove('active');
    }
    window.addEventListener('load', toggleBacktotop);
    onscroll(document, toggleBacktotop);
  }

  /**
   * Handle .scrollto links
   */
  on('click', '.scrollto', function(e) {
    if (select(this.hash)) {
      e.preventDefault();
      scrollto(this.hash);
    }
  }, true);


  /**
   * Main function to run after page loads
   */
  window.addEventListener('load', () => {

    /**
     * Preloader
     */
    let preloader = select('#preloader');
    if (preloader) {
      preloader.classList.add('preloader-hidden');
      setTimeout(() => {
        preloader.remove();
      }, 600);
    }
    
    /**
     * Scroll to hash links on page load
     */
    if (window.location.hash && select(window.location.hash)) {
      scrollto(window.location.hash);
    }
    
    /**
     * Activate navbar links on scroll
     */
    let navbarlinks = select('#navbar .scrollto', true);
    const navbarlinksActive = () => {
      let position = window.scrollY + 200;
      navbarlinks.forEach(navbarlink => {
        if (!navbarlink.hash) return;
        let section = select(navbarlink.hash);
        if (!section) return;
        if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
          navbarlink.classList.add('active');
        } else {
          navbarlink.classList.remove('active');
        }
      });
    }
    navbarlinksActive();
    onscroll(document, navbarlinksActive);


    
    /**
     * Initialize AOS (Animation on scroll)
     */
    AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });

    /**
     * Initialize GLightbox (for portfolio images)
     */
    const portfolioLightbox = GLightbox({
      selector: '.portfolio-lightbox'
    });

    /**
     * Initialize ALL Swiper Sliders here
     */

// Portfolio Slider (NEW Single-Slide Gallery Configuration)
new Swiper('.portfolio-slider', {
  speed: 600,
  loop: true,
  
  // The most important changes are here:
  
  slidesPerView: 1, // Show only ONE slide at a time
  spaceBetween: 30,

  autoplay: {
    delay: 5000,
    disableOnInteraction: false
  },
  pagination: {
    el: '.portfolio .swiper-pagination',
    type: 'bullets',
    clickable: true
  },
  navigation: {
    nextEl: '.portfolio .swiper-button-next',
    prevEl: '.portfolio .swiper-button-prev',
  },
});

    // Team Slider
    new Swiper('.team-slider', {
      speed: 600,
      loop: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      slidesPerView: 'auto',
      pagination: { el: '#team .swiper-pagination', type: 'bullets', clickable: true },
      spaceBetween: 30,
      breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 30 },
        1200: { slidesPerView: 4, spaceBetween: 30 }
      }
    });
    
    // Testimonials slider (if you have one)
    new Swiper('.testimonials-slider', {
      speed: 600,
      loop: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      slidesPerView: 'auto',
      pagination: { el: '.testimonials .swiper-pagination', type: 'bullets', clickable: true },
      breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 40 },
        1200: { slidesPerView: 3, spaceBetween: 40 }
      }
    });
    
  }); // End of window.addEventListener('load')

})();