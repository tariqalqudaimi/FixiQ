(function () {
  "use strict";

  /**
   * Helper Functions from your original file
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

  /**
   * Header scroll class
   */
  let selectHeader = select('#header');
  if (selectHeader) {
    const headerScrolled = () => window.scrollY > 100 ? selectHeader.classList.add('header-scrolled') : selectHeader.classList.remove('header-scrolled');
    window.addEventListener('load', headerScrolled);
    onscroll(document, headerScrolled);
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top');
  if (backtotop) {
    const toggleBacktotop = () => window.scrollY > 100 ? backtotop.classList.add('active') : backtotop.classList.remove('active');
    window.addEventListener('load', toggleBacktotop);
    onscroll(document, toggleBacktotop);
  }

  // =================================================================================
  // START: BEYOND IMAGINATION JAVASCRIPT FUNCTIONS
  // These are the new functions for the amazing effects.
  // =================================================================================

  /**
   * BEYOND IMAGINATION: 3D Tilt Effect for Services and Team sections
   */
  function init3DTiltEffect() {
    const tiltElements = document.querySelectorAll('.services .icon-box, .team .member-style-2');
    tiltElements.forEach(element => {
      element.addEventListener('mousemove', (e) => {
        const rect = element.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const width = element.offsetWidth;
        const height = element.offsetHeight;
        const rotateX = -((height / 2) - y) / 20;
        const rotateY = ((width / 2) - x) / 20;
        element.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
        if (element.classList.contains('icon-box')) {
          element.style.setProperty('--mouse-x', `${x}px`);
          element.style.setProperty('--mouse-y', `${y}px`);
        }
      });
      element.addEventListener('mouseleave', () => {
        element.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
      });
    });
  }

  /**
   * BEYOND IMAGINATION: Spotlight Effect for Features section
   */
  function initFeaturesSpotlight() {
    const featuresSection = document.querySelector('#features');
    if (featuresSection) {
      featuresSection.addEventListener('mousemove', e => {
        const rect = featuresSection.getBoundingClientRect();
        featuresSection.style.setProperty('--mouse-x', (e.clientX - rect.left) + 'px');
        featuresSection.style.setProperty('--mouse-y', (e.clientY - rect.top) + 'px');
      });
    }
  }

  // =================================================================================
  // END: BEYOND IMAGINATION JAVASCRIPT FUNCTIONS
  // =================================================================================

  /**
   * Your original DOMContentLoaded listener, now with new function calls
   */
  document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('particle-canvas');
    if (canvas) {
      const ctx = canvas.getContext('2d');
      let particles = [];
      const particleCount = 70;
      const setCanvasSize = () => { canvas.width = window.innerWidth; canvas.height = window.innerHeight; };
      setCanvasSize();
      class Particle {
        constructor() { this.x = Math.random() * canvas.width; this.y = Math.random() * canvas.height; this.vx = (Math.random() - 0.5) * 0.3; this.vy = (Math.random() - 0.5) * 0.3; this.radius = Math.random() * 1.5 + 0.5; }
        draw() { ctx.beginPath(); ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2); ctx.fillStyle = 'rgba(138, 79, 255, 0.7)'; ctx.fill(); }
        update() { this.x += this.vx; this.y += this.vy; if (this.x < 0 || this.x > canvas.width) this.vx *= -1; if (this.y < 0 || this.y > canvas.height) this.vy *= -1; }
      }
      const init = () => { particles = []; for (let i = 0; i < particleCount; i++) particles.push(new Particle()); };
      const connectParticles = () => {
        for (let i = 0; i < particles.length; i++) {
          for (let j = i + 1; j < particles.length; j++) {
            const distance = Math.sqrt(Math.pow(particles[i].x - particles[j].x, 2) + Math.pow(particles[i].y - particles[j].y, 2));
            if (distance < 120) { ctx.beginPath(); ctx.moveTo(particles[i].x, particles[i].y); ctx.lineTo(particles[j].x, particles[j].y); ctx.strokeStyle = `rgba(138, 79, 255, ${1 - distance / 120})`; ctx.lineWidth = 0.5; ctx.stroke(); }
          }
        }
      };
      const animate = () => { ctx.clearRect(0, 0, canvas.width, canvas.height); particles.forEach(p => { p.update(); p.draw(); }); connectParticles(); requestAnimationFrame(animate); };
      window.addEventListener('resize', () => { setCanvasSize(); init(); });
      init(); animate();
    }
    init3DTiltEffect();
    initFeaturesSpotlight();
  });

  /**
   * Your original Mobile Navigation Logic
   */
  const mobileNavContainer = select('.navbar-mobile');
  if (mobileNavContainer) {
    const navContent = select('#navbar ul').outerHTML;
    const actionsContent = select('.header-right-actions') ? select('.header-right-actions').innerHTML : '';
    mobileNavContainer.innerHTML = `<div class="navbar-mobile-content">${navContent}<div class="mobile-actions">${actionsContent}</div></div>`;
    const closeButton = select('#navbar .mobile-nav-toggle').cloneNode(true);
    closeButton.classList.remove('bi-list');
    closeButton.classList.add('bi-x');
    mobileNavContainer.appendChild(closeButton);
  }
  on('click', '.mobile-nav-toggle', function (e) {
    select('body').classList.toggle('mobile-nav-active');
    const originalToggle = select('#navbar .mobile-nav-toggle');
    originalToggle.classList.toggle('bi-list');
    originalToggle.classList.toggle('bi-x');
  }, true);
  on('click', '.navbar-mobile .dropdown > a', function (e) {
    if (this.nextElementSibling) e.preventDefault();
    this.nextElementSibling.classList.toggle('dropdown-active');
    this.classList.toggle('active');
  }, true);
  on('click', '.navbar-mobile a.scrollto', function (e) {
    if (select(this.hash)) { e.preventDefault(); select('.mobile-nav-toggle').click(); scrollto(this.hash); }
  }, true);

  /**
   * All functions to run after page has fully loaded
   */
  window.addEventListener('load', () => {
    let preloader = select('#preloader');
    if (preloader) {
      const animationDisplayTime = 1500;
      setTimeout(() => {
        preloader.classList.add('preloader-hidden');
        setTimeout(() => { preloader.remove(); }, 600);
      }, animationDisplayTime);
    }
    if (window.location.hash && select(window.location.hash)) { scrollto(window.location.hash); }
    GLightbox({ selector: '.portfolio-lightbox' });
    AOS.init({ duration: 1000, easing: 'ease-in-out', once: true, mirror: false });

    // --- REPLACED SWIPER INITIALIZATIONS ---

    /**
     * BEYOND IMAGINATION v4: Pro Masonry Portfolio with Load More & Hero Modal
     */
/**
 * BEYOND IMAGINATION: The Kinetic Wall (v20.0 - MODAL VIEW)
 */
try {
    const portfolio = document.querySelector('.kinetic-portfolio');
    if (portfolio) {

        // --- 1. MODAL LOGIC (New & Unified for Desktop/Mobile) ---
        const modal = document.getElementById('portfolioModal');
        const wallItems = portfolio.querySelectorAll('.kinetic-track .wall-item');

        if (modal) {
            const modalImage = modal.querySelector('.modal-image');
            const modalTitle = modal.querySelector('.modal-title');
            const modalCategories = modal.querySelector('.modal-categories');
            const modalDescription = modal.querySelector('.modal-description');
            const modalLink = modal.querySelector('.modal-link');
            const closeModalBtn = modal.querySelector('.modal-close-btn');
            const modalBackdrop = modal.querySelector('.modal-backdrop');

            // Function to open the modal
            const openModal = (itemData) => {
                // Populate modal with data
                modalImage.style.backgroundImage = `url('${itemData.image}')`;
                modalTitle.textContent = itemData.title;
                modalDescription.textContent = itemData.description;
                modalLink.href = itemData.url;

                // Populate categories
                modalCategories.innerHTML = ''; // Clear previous categories
                const categories = JSON.parse(itemData.categories);
                categories.forEach(cat => {
                    const span = document.createElement('span');
                    span.textContent = cat;
                    modalCategories.appendChild(span);
                });

                // Show the modal
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            };

            // Function to close the modal
            const closeModal = () => {
                modal.classList.remove('is-open');
                document.body.style.overflow = ''; // Restore scrolling
            };

            // Add click listeners to all portfolio items
            wallItems.forEach(item => {
                item.addEventListener('click', () => {
                    openModal(item.dataset);
                });
            });

            // Add listeners to close the modal
            closeModalBtn.addEventListener('click', closeModal);
            modalBackdrop.addEventListener('click', closeModal);
        }

        // --- 2. LOAD MORE & DESKTOP ANIMATION LOGIC (Mostly Unchanged) ---
        const setupKineticWall = () => {
            const isMobile = window.matchMedia("(max-width: 991px)").matches;

            if (isMobile) {
                // Mobile "Load More" Functionality
                const loadMoreBtn = document.getElementById('mobile-load-more-btn');
                if (loadMoreBtn && !loadMoreBtn.hasAttribute('data-listener-attached')) {
                    const initialVisibleItemsCount = 3;
                    const itemsToLoadPerClick = 3;
                    let currentlyVisibleItems = initialVisibleItemsCount;

                    if (wallItems.length <= initialVisibleItemsCount) {
                        loadMoreBtn.style.display = 'none';
                    }

                    loadMoreBtn.addEventListener('click', () => {
                        let itemsShown = 0;
                        for (let i = currentlyVisibleItems; i < wallItems.length && itemsShown < itemsToLoadPerClick; i++) {
                            if (wallItems[i].classList.contains('is-hidden-mobile')) {
                                wallItems[i].classList.remove('is-hidden-mobile');
                                itemsShown++;
                            }
                        }
                        currentlyVisibleItems += itemsShown;
                        if (currentlyVisibleItems >= wallItems.length) {
                            loadMoreBtn.style.display = 'none';
                        }
                    });
                    loadMoreBtn.setAttribute('data-listener-attached', 'true');
                }
            } else {
                // Desktop Infinite Scroll & Prism Effect Logic
                const track = portfolio.querySelector('.kinetic-track');
                if (!track || track.children.length === 0) return;

                if (!track.hasAttribute('data-cloned')) {
                    const originalItems = Array.from(track.children);
                    originalItems.forEach(item => {
                        const clone = item.cloneNode(true);
                        track.appendChild(clone);
                    });
                    track.setAttribute('data-cloned', 'true');
                }

                const itemCount = track.children.length / 2;
                const duration = itemCount * 8;
                track.style.setProperty('--scroll-duration', `${duration}s`);
                
                wallItems.forEach(item => {
                    const prismImage = item.querySelector('.item-bg-prism');
                    item.addEventListener('mousemove', (e) => {
                        if (prismImage) {
                            const rect = item.getBoundingClientRect();
                            const x = e.clientX - rect.left;
                            const y = e.clientY - rect.top;
                            const moveX = (x / rect.width - 0.5) * 20;
                            const moveY = (y / rect.height - 0.5) * 20;
                            prismImage.style.setProperty('--mouse-x', `${moveX}px`);
                            prismImage.style.setProperty('--mouse-y', `${moveY}px`);
                        }
                    });
                });
            }
        };

        setupKineticWall();
        window.addEventListener('resize', setupKineticWall);
    }
} catch (error) {
    console.error("A critical error occurred in the Kinetic Wall script:", error);
}
    
    /**
     * BEYOND IMAGINATION: Team Swiper (Enhanced version)
     */
    new Swiper('.team-slider', {
      speed: 600,
      loop: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      slidesPerView: 'auto',
      pagination: { el: '#team .swiper-pagination', clickable: true },
      spaceBetween: 30,
      breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 30 },
        1200: { slidesPerView: 4, spaceBetween: 40 }
      }
    });
  });

  /**
   * Your existing Contact Page Animated Swap logic
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

  /**
   * Your existing AJAX handler for the Animated Contact Form
   */
  const animatedContactForm = select('#contact .animated-contact-form');
  if (animatedContactForm) {
    animatedContactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      let form = this;
      let loading = form.querySelector('.my-3 .loading');
      let errorMessage = form.querySelector('.my-3 .error-message');
      let sentMessage = form.querySelector('.my-3 .sent-message');
      loading.style.display = 'block';
      errorMessage.style.display = 'none';
      sentMessage.style.display = 'none';
      fetch(form.action, {
        method: form.method,
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => {
        if (response.ok) { return response.text(); } 
        else { return response.text().then(text => { throw new Error(text || 'Server responded with an error.'); }); }
      })
      .then(data => {
        loading.style.display = 'none';
        sentMessage.style.display = 'block';
        form.reset();
      })
      .catch(error => {
        loading.style.display = 'none';
        errorMessage.textContent = error.message;
        errorMessage.style.display = 'block';
      })
      .finally(() => {
        setTimeout(() => {
          sentMessage.style.display = 'none';
          errorMessage.style.display = 'none';
        }, 3000);
      });
    });
  }

})();