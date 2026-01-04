/**
 * Enhanced Animations & Interactions
 * Modern JavaScript for better UX
 */

(function() {
  'use strict';

  document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // SCROLL ANIMATIONS (Intersection Observer)
    // ============================================
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          
          // Add specific animation class based on data attribute
          const animationType = entry.target.dataset.animation || 'fadeInUp';
          entry.target.classList.add(animationType);
          
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    // Observe all elements with animate-on-scroll class
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
      observer.observe(el);
    });

    // ============================================
    // NAVBAR SCROLL EFFECT
    // ============================================
    const navbar = document.querySelector('.navbar.fixed-top');
    let lastScroll = 0;
    let ticking = false;

    function updateNavbar() {
      const currentScroll = window.pageYOffset;
      
      if (currentScroll > 50) {
        navbar?.classList.add('scrolled');
      } else {
        navbar?.classList.remove('scrolled');
      }
      
      lastScroll = currentScroll;
      ticking = false;
    }

    window.addEventListener('scroll', function() {
      if (!ticking) {
        window.requestAnimationFrame(updateNavbar);
        ticking = true;
      }
    }, { passive: true });

    // ============================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href.length > 1) {
          const target = document.querySelector(href);
          if (target) {
            e.preventDefault();
            const offsetTop = target.offsetTop - 80; // Account for fixed navbar
            
            window.scrollTo({
              top: offsetTop,
              behavior: 'smooth'
            });
          }
        }
      });
    });

    // ============================================
    // ENHANCED PRODUCT CARD INTERACTIONS
    // ============================================
    const productItems = document.querySelectorAll('.product-item');
    
    productItems.forEach(item => {
      const addToCartBtn = item.querySelector('.add-to-cart');
      const imageContainer = item.querySelector('.image-zoom-effect');
      
      // Show add to cart button on hover
      if (imageContainer && addToCartBtn) {
        const hiddenBtn = imageContainer.querySelector('.position-absolute.bottom-0');
        
        item.addEventListener('mouseenter', function() {
          if (hiddenBtn) {
            hiddenBtn.style.transform = 'translateY(0)';
          }
        });
        
        item.addEventListener('mouseleave', function() {
          if (hiddenBtn) {
            hiddenBtn.style.transform = 'translateY(100%)';
          }
        });
      }

      // Add ripple effect on click
      item.addEventListener('click', function(e) {
        if (e.target.closest('.btn')) return; // Don't trigger on button clicks
        
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
          position: absolute;
          width: ${size}px;
          height: ${size}px;
          left: ${x}px;
          top: ${y}px;
          background: rgba(255, 255, 255, 0.5);
          border-radius: 50%;
          transform: scale(0);
          animation: ripple 0.6s ease-out;
          pointer-events: none;
          z-index: 1000;
        `;
        
        this.style.position = 'relative';
        this.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
      });
    });

    // ============================================
    // ENHANCED BUTTON INTERACTIONS
    // ============================================
    const buttons = document.querySelectorAll('.btn');
    
    buttons.forEach(button => {
      button.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
      });
      
      button.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
      });
      
      button.addEventListener('mousedown', function() {
        this.style.transform = 'translateY(0) scale(0.98)';
      });
      
      button.addEventListener('mouseup', function() {
        this.style.transform = 'translateY(-2px) scale(1)';
      });
    });

    // ============================================
    // ENHANCED SWIPER CONFIGURATION
    // ============================================
    if (typeof Swiper !== 'undefined') {
      // Hero Banner with enhanced effects
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
          speed: 1000,
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
          on: {
            slideChange: function() {
              // Animate content on slide change
              const activeSlide = this.slides[this.activeIndex];
              const content = activeSlide.querySelector('.banner-content');
              if (content) {
                content.style.animation = 'none';
                setTimeout(() => {
                  content.style.animation = 'fadeInUp 0.8s ease-out';
                }, 10);
              }
            }
          }
        });
      }
    }

    // ============================================
    // PARALLAX EFFECT FOR SECTIONS
    // ============================================
    const parallaxElements = document.querySelectorAll('.parallax');
    
    if (parallaxElements.length > 0) {
      window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach(element => {
          const rate = scrolled * 0.5;
          element.style.transform = `translateY(${rate}px)`;
        });
      }, { passive: true });
    }

    // ============================================
    // ENHANCED SEARCH MODAL
    // ============================================
    const searchModal = document.getElementById('searchModal');
    const searchInput = document.getElementById('searchInput');
    
    if (searchModal) {
      searchModal.addEventListener('shown.bs.modal', function() {
        if (searchInput) {
          setTimeout(() => searchInput.focus(), 300);
        }
      });
    }

    // ============================================
    // LAZY LOADING WITH FADE IN
    // ============================================
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
              img.src = img.dataset.src;
              img.classList.add('fade-in');
              img.removeAttribute('data-src');
              observer.unobserve(img);
            }
          }
        });
      }, {
        rootMargin: '50px'
      });

      document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
      });
    }

    // ============================================
    // ENHANCED NOTIFICATIONS
    // ============================================
    function showEnhancedNotification(message, type = 'info', duration = 3000) {
      const notification = document.createElement('div');
      notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed notification`;
      notification.style.cssText = `
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 400px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border-radius: 8px;
        animation: slideInFromTop 0.5s ease-out;
      `;
      
      const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';
      notification.innerHTML = `
        <div class="d-flex align-items-center">
          <span class="me-2" style="font-size: 1.2rem;">${icon}</span>
          <span>${message}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;
      
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.classList.add('hiding');
        setTimeout(() => notification.remove(), 500);
      }, duration);
    }

    // Expose to global scope for use in other scripts
    window.showEnhancedNotification = showEnhancedNotification;

    // ============================================
    // STAGGER ANIMATION FOR LISTS
    // ============================================
    const staggerContainers = document.querySelectorAll('.stagger-container');
    
    staggerContainers.forEach(container => {
      const items = container.querySelectorAll('.stagger-item');
      const itemObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animate');
            itemObserver.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1 });
      
      items.forEach(item => itemObserver.observe(item));
    });

    // ============================================
    // ENHANCED FORM VALIDATION ANIMATIONS
    // ============================================
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
      const inputs = form.querySelectorAll('input, textarea, select');
      
      inputs.forEach(input => {
        input.addEventListener('blur', function() {
          if (this.value.trim() !== '') {
            this.classList.add('has-value');
          } else {
            this.classList.remove('has-value');
          }
        });
        
        input.addEventListener('invalid', function(e) {
          e.preventDefault();
          this.classList.add('is-invalid');
          this.style.animation = 'shake 0.5s ease';
        });
      });
    });

    // Shake animation for invalid inputs
    const style = document.createElement('style');
    style.textContent = `
      @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
      }
      
      @keyframes ripple {
        0% {
          transform: scale(0);
          opacity: 1;
        }
        100% {
          transform: scale(4);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);

    // ============================================
    // LOADING STATES
    // ============================================
    function setLoadingState(button, isLoading) {
      if (isLoading) {
        button.disabled = true;
        button.dataset.originalText = button.textContent;
        button.innerHTML = '<span class="loading-spinner me-2"></span>Loading...';
      } else {
        button.disabled = false;
        button.textContent = button.dataset.originalText || button.textContent;
      }
    }

    // Apply to all buttons with data-loading attribute
    document.querySelectorAll('[data-loading]').forEach(button => {
      button.addEventListener('click', function() {
        setLoadingState(this, true);
        // Simulate async operation
        setTimeout(() => setLoadingState(this, false), 2000);
      });
    });

    // ============================================
    // ENHANCED MOBILE MENU
    // ============================================
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
      navbarToggler.addEventListener('click', function() {
        setTimeout(() => {
          if (navbarCollapse.classList.contains('show')) {
            navbarCollapse.style.animation = 'fadeInDown 0.3s ease-out';
          }
        }, 10);
      });
    }

    // ============================================
    // PERFORMANCE: Debounce scroll events
    // ============================================
    function debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }

    // Apply debounce to scroll-heavy operations
    const optimizedScrollHandler = debounce(() => {
      // Heavy scroll operations here
    }, 100);

    window.addEventListener('scroll', optimizedScrollHandler, { passive: true });

    console.log('Enhanced Animations Loaded! 🎨');
  });

})();

