
(function () {
  "use strict";

  /**
 * Preloader
 */

  const select = (el, all = false) => el.trim() ? (all ? [...document.querySelectorAll(el)] : document.querySelector(el)) : null;
  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all);
    if (selectEl) {
      if (all) selectEl.forEach(e => e.addEventListener(type, listener));
      else selectEl.addEventListener(type, listener);
    }
  };
  const onscroll = (el, listener) => el.addEventListener('scroll', listener);
  const scrollto = (el) => {
    let header = select('#header');
    let offset = header.offsetHeight;
    if (!header.classList.contains('header-scrolled')) offset -= 20;
    let elementPos = select(el).offsetTop;
    window.scrollTo({ top: elementPos - offset, behavior: 'smooth' });
  };

  // Header scroll class
  let selectHeader = select('#header');
  if (selectHeader) {
    const headerScrolled = () => window.scrollY > 100 ? selectHeader.classList.add('header-scrolled') : selectHeader.classList.remove('header-scrolled');
    window.addEventListener('load', headerScrolled);
    onscroll(document, headerScrolled);
  }

  // Back to top button
  let backtotop = select('.back-to-top');
  if (backtotop) {
    const toggleBacktotop = () => window.scrollY > 100 ? backtotop.classList.add('active') : backtotop.classList.remove('active');
    window.addEventListener('load', toggleBacktotop);
    onscroll(document, toggleBacktotop);
  }

  /* --- All Mobile Navigation Logic (START) --- */

  const mobileNavContainer = select('.navbar-mobile');
  if (mobileNavContainer) {
    const navContent = select('#navbar ul').outerHTML;
    const actionsContent = select('.header-right-actions').innerHTML;

    mobileNavContainer.innerHTML = `
      <div class="navbar-mobile-content">
        ${navContent}
        <div class="mobile-actions">${actionsContent}</div>
      </div>
    `;

    const closeButton = select('#navbar .mobile-nav-toggle').cloneNode(true);
    closeButton.classList.remove('bi-list');
    closeButton.classList.add('bi-x');
    mobileNavContainer.appendChild(closeButton);
  }

  on('click', '.mobile-nav-toggle', function (e) {
    select('body').classList.toggle('mobile-nav-active');

    const originalToggle = select('#navbar .mobile-nav-toggle');
    if (select('body').classList.contains('mobile-nav-active')) {
      originalToggle.classList.remove('bi-list');
      originalToggle.classList.add('bi-x');
    } else {
      originalToggle.classList.remove('bi-x');
      originalToggle.classList.add('bi-list');
    }
  }, true);

  // 3. ACTIVATE DROPDOWNS: Handle clicks inside mobile nav
  on('click', '.navbar-mobile .dropdown > a', function (e) {
    if (this.nextElementSibling) e.preventDefault();
    this.nextElementSibling.classList.toggle('dropdown-active');
    this.classList.toggle('active');
  }, true);

  // 4. SCROLL & CLOSE: Close mobile nav when a link is clicked
  on('click', '.navbar-mobile a.scrollto', function (e) {
    if (select(this.hash)) {
      e.preventDefault();
      // Directly call the toggle click handler to ensure everything closes correctly
      select('.mobile-nav-toggle').click();
      scrollto(this.hash);
    }
  }, true);

  // All functions to run after page has fully loaded
  window.addEventListener('load', () => {
    let preloader = select('#preloader');

    if (preloader) {
      const animationDisplayTime = 2000;

      setTimeout(() => {
        preloader.classList.add('preloader-hidden');
        setTimeout(() => {
          preloader.remove();
        }, 600);

      }, animationDisplayTime);
    }


    if (window.location.hash && select(window.location.hash)) {
      scrollto(window.location.hash);
    }

    GLightbox({ selector: '.portfolio-lightbox' });

    AOS.init({ duration: 1000, easing: 'ease-in-out', once: true, mirror: false });

    new Swiper('.portfolio-slider', {
      speed: 600, loop: true, slidesPerView: 1, spaceBetween: 30,
      autoplay: { delay: 5000, disableOnInteraction: false },
      pagination: { el: '.portfolio .swiper-pagination', clickable: true },
      navigation: { nextEl: '.portfolio .swiper-button-next', prevEl: '.portfolio .swiper-button-prev' },
    });
    new Swiper('.team-slider', {
      speed: 600, loop: true, autoplay: { delay: 5000, disableOnInteraction: false },
      slidesPerView: 'auto', pagination: { el: '#team .swiper-pagination', clickable: true }, spaceBetween: 30,
      breakpoints: { 320: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1200: { slidesPerView: 4 } }
    });
  });


    /**
   * Contact Page Animated Swap
   */
  const show_info_btn = select("#show-info-btn");
  const show_form_btn = select("#show-form-btn");
  const container = select(".contact-page-container");

  if (container && show_info_btn && show_form_btn) {
    show_info_btn.addEventListener('click', () => {
        container.classList.add("show-info-mode");
    });

    show_form_btn.addEventListener('click', () => {
        container.classList.remove("show-info-mode");
    });
  }
})();

if (document.getElementById('particles-js')) {
  particlesJS("particles-js", { "particles": { "number": { "value": 80, "density": { "enable": true, "value_area": 800 } }, "color": { "value": "#8A4FFF" }, "shape": { "type": "circle" }, "opacity": { "value": 0.5 }, "size": { "value": 3, "random": true }, "line_linked": { "enable": true, "distance": 150, "color": "#8A4FFF", "opacity": 0.4, "width": 1 }, "move": { "enable": true, "speed": 4, "direction": "none", "out_mode": "out" } }, "interactivity": { "events": { "onhover": { "enable": true, "mode": "repulse" }, "onclick": { "enable": true, "mode": "push" } }, "modes": { "repulse": { "distance": 100 }, "push": { "particles_nb": 4 } } }, "retina_detect": true });
}