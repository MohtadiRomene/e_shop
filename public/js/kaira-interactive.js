/**
 * Kaira Template - Interactive Features
 * Makes all buttons and interactions work dynamically
 */

(function() {
  'use strict';

  // Wait for DOM to be ready
  document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // 1. SEARCH FUNCTIONALITY
    // ============================================
    const searchTrigger = document.querySelector('.search-trigger');
    const searchModal = document.getElementById('searchModal');
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');

    if (searchTrigger && searchModal) {
      searchTrigger.addEventListener('click', function(e) {
        e.preventDefault();
        const modal = new bootstrap.Modal(searchModal);
        modal.show();
        setTimeout(() => {
          if (searchInput) searchInput.focus();
        }, 300);
      });
    }

    if (searchForm) {
      searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = searchInput ? searchInput.value.trim() : '';
        if (query) {
          // Here you can add actual search functionality
          console.log('Searching for:', query);
          // Example: window.location.href = '/search?q=' + encodeURIComponent(query);
          alert('Search functionality will be implemented in backend: ' + query);
        }
      });
    }

    // ============================================
    // 2. WISHLIST FUNCTIONALITY
    // ============================================
    const wishlistButtons = document.querySelectorAll('.btn-wishlist');
    let wishlistCount = parseInt(localStorage.getItem('wishlistCount') || '0');
    updateWishlistCount(wishlistCount);

    wishlistButtons.forEach(button => {
      // Check if already in wishlist
      const productId = button.closest('.product-item')?.dataset?.productId || Math.random();
      if (localStorage.getItem('wishlist_' + productId)) {
        button.classList.add('active');
      }

      button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const icon = this.querySelector('svg use');
        const isActive = this.classList.contains('active');
        const productId = this.closest('.product-item')?.dataset?.productId || Math.random();
        
        if (isActive) {
          this.classList.remove('active');
          wishlistCount = Math.max(0, wishlistCount - 1);
          localStorage.removeItem('wishlist_' + productId);
          showNotification('Removed from wishlist', 'info');
        } else {
          this.classList.add('active');
          wishlistCount++;
          localStorage.setItem('wishlist_' + productId, 'true');
          showNotification('Added to wishlist!', 'success');
        }
        
        localStorage.setItem('wishlistCount', wishlistCount);
        updateWishlistCount(wishlistCount);
        
        // Add animation
        this.style.transform = 'scale(1.2)';
        setTimeout(() => {
          this.style.transform = 'scale(1)';
        }, 200);
      });
    });

    function updateWishlistCount(count) {
      const wishlistCounts = document.querySelectorAll('.wishlist-count');
      wishlistCounts.forEach(element => {
        const text = element.textContent || element.innerText;
        element.textContent = text.replace(/\(\d+\)/, `(${count})`);
      });
    }

    // ============================================
    // 3. CART FUNCTIONALITY
    // ============================================
    const addToCartButtons = document.querySelectorAll('.btn-add-to-cart, .add-to-cart');
    let cartCount = parseInt(localStorage.getItem('cartCount') || '0');
    updateCartCount(cartCount);

    addToCartButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const productItem = this.closest('.product-item');
        const productName = productItem?.querySelector('h3')?.textContent || 'Product';
        const productId = productItem?.dataset?.productId || Math.random();
        
        cartCount++;
        localStorage.setItem('cartCount', cartCount);
        updateCartCount(cartCount);
        
        // Store cart item
        const cartItem = {
          id: productId,
          name: productName,
          quantity: 1
        };
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const existingItem = cart.find(item => item.id === productId);
        if (existingItem) {
          existingItem.quantity++;
        } else {
          cart.push(cartItem);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Show notification
        showNotification(productName + ' added to cart!', 'success');
        
        // Add animation
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
          this.style.transform = 'scale(1)';
        }, 200);
      });
    });

    function updateCartCount(count) {
      const cartCounts = document.querySelectorAll('.cart-count');
      cartCounts.forEach(element => {
        const text = element.textContent || element.innerText;
        element.textContent = text.replace(/\(\d+\)/, `(${count})`);
      });
    }

    // ============================================
    // 4. PRODUCT QUICK VIEW / MODAL
    // ============================================
    const quickViewButtons = document.querySelectorAll('.quick-view, .btn-quick-view');
    
    quickViewButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        // This will be implemented when you have product pages
        console.log('Quick view clicked');
      });
    });

    // ============================================
    // 5. SMOOTH SCROLLING FOR ANCHOR LINKS
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href.length > 1) {
          const target = document.querySelector(href);
          if (target) {
            e.preventDefault();
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        }
      });
    });

    // ============================================
    // 6. NAVBAR SCROLL EFFECT
    // ============================================
    const navbar = document.querySelector('.navbar.fixed-top');
    if (navbar) {
      let lastScroll = 0;
      
      window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
          navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
          navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        } else {
          navbar.style.backgroundColor = '#fff';
          navbar.style.boxShadow = 'none';
        }
        
        lastScroll = currentScroll;
      });
    }

    // ============================================
    // 7. IMAGE ZOOM EFFECT ON HOVER
    // ============================================
    const imageZoomElements = document.querySelectorAll('.image-zoom-effect');
    
    imageZoomElements.forEach(element => {
      const image = element.querySelector('img');
      if (image) {
        element.addEventListener('mouseenter', function() {
          image.style.transform = 'scale(1.1)';
          image.style.transition = 'transform 0.3s ease';
        });
        
        element.addEventListener('mouseleave', function() {
          image.style.transform = 'scale(1)';
        });
      }
    });

    // ============================================
    // 8. DROPDOWN MENUS
    // ============================================
    const navItems = document.querySelectorAll('.navbar-nav .nav-item');
    
    navItems.forEach(item => {
      const link = item.querySelector('.nav-link');
      if (link && link.querySelector('svg')) {
        item.addEventListener('mouseenter', function() {
          this.classList.add('show');
          const dropdown = this.querySelector('.dropdown-menu');
          if (dropdown) {
            dropdown.classList.add('show');
          }
        });
        
        item.addEventListener('mouseleave', function() {
          this.classList.remove('show');
          const dropdown = this.querySelector('.dropdown-menu');
          if (dropdown) {
            dropdown.classList.remove('show');
          }
        });
      }
    });

    // ============================================
    // 9. NEWSLETTER FORM
    // ============================================
    const newsletterForms = document.querySelectorAll('form[action*="newsletter"], .newsletter-form');
    
    newsletterForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const emailInput = this.querySelector('input[type="email"]');
        const email = emailInput ? emailInput.value.trim() : '';
        
        if (email && isValidEmail(email)) {
          showNotification('Thank you for subscribing!', 'success');
          emailInput.value = '';
        } else {
          showNotification('Please enter a valid email address', 'error');
        }
      });
    });

    // ============================================
    // 10. NOTIFICATION SYSTEM
    // ============================================
    function showNotification(message, type = 'info') {
      const notification = document.createElement('div');
      notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
      notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
      notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;
      
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
      }, 3000);
    }

    // ============================================
    // 11. UTILITY FUNCTIONS
    // ============================================
    function isValidEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    }

    // ============================================
    // 12. INITIALIZE SWIPER SLIDERS
    // ============================================
    if (typeof Swiper !== 'undefined') {
      // Hero Banner Swiper
      const heroSwiper = document.querySelector('.slideshow.swiper');
      if (heroSwiper) {
        new Swiper(heroSwiper, {
          loop: true,
          autoplay: {
            delay: 5000,
            disableOnInteraction: false,
          },
          effect: 'fade',
          fadeEffect: {
            crossFade: true
          },
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
        });
      }

      // Product Sliders
      const productSliders = document.querySelectorAll('.product-swiper, .testimonial-swiper');
      productSliders.forEach(slider => {
        new Swiper(slider, {
          slidesPerView: 1,
          spaceBetween: 30,
          loop: true,
          autoplay: {
            delay: 4000,
          },
          breakpoints: {
            768: {
              slidesPerView: 2,
            },
            992: {
              slidesPerView: 3,
            },
            1200: {
              slidesPerView: 4,
            }
          },
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
        });
      });
    }

    // ============================================
    // 13. LAZY LOADING IMAGES
    // ============================================
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
              img.src = img.dataset.src;
              img.removeAttribute('data-src');
              observer.unobserve(img);
            }
          }
        });
      });

      document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
      });
    }

    // ============================================
    // 14. MOBILE MENU CLOSE ON CLICK
    // ============================================
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');

    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        if (window.innerWidth < 992 && navbarCollapse.classList.contains('show')) {
          navbarToggler.click();
        }
      });
    });

    // ============================================
    // 15. BOOTSTRAP DROPDOWN INITIALIZATION (Turbo compatible)
    // ============================================
    function initializeDropdowns() {
      // Réinitialiser tous les dropdowns Bootstrap
      const dropdownElements = document.querySelectorAll('.dropdown-toggle');
      dropdownElements.forEach(element => {
        // Vérifier si Bootstrap est disponible
        if (typeof bootstrap !== 'undefined') {
          // Créer une nouvelle instance de Dropdown si elle n'existe pas
          const dropdown = bootstrap.Dropdown.getOrCreateInstance(element, {
            boundary: 'viewport',
            popperConfig: {
              placement: 'bottom-end'
            }
          });
          
          // S'assurer que le dropdown est fonctionnel
          element.addEventListener('click', function(e) {
            e.preventDefault();
            dropdown.toggle();
          });
        }
      });
    }

    // Initialiser les dropdowns au chargement initial
    initializeDropdowns();

    // Réinitialiser les dropdowns après chaque chargement Turbo
    if (typeof Turbo !== 'undefined') {
      document.addEventListener('turbo:load', function() {
        initializeDropdowns();
      });
      
      document.addEventListener('turbo:render', function() {
        initializeDropdowns();
      });
    }

    // Fallback pour les navigateurs sans Turbo
    // Réinitialiser les dropdowns si le DOM change
    const observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.addedNodes.length > 0) {
          // Vérifier si un nouveau dropdown a été ajouté
          const newDropdowns = document.querySelectorAll('.dropdown-toggle:not([data-bs-dropdown-initialized])');
          if (newDropdowns.length > 0) {
            initializeDropdowns();
            // Marquer comme initialisé
            newDropdowns.forEach(el => el.setAttribute('data-bs-dropdown-initialized', 'true'));
          }
        }
      });
    });

    // Observer les changements dans le body
    if (document.body) {
      observer.observe(document.body, {
        childList: true,
        subtree: true
      });
    }

    console.log('Kaira Interactive Features Loaded!');
  });

})();

