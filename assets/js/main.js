/**
 * Talent Bridge - Main JavaScript
 * Handles UI interactions, animations, and form validations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Header scroll effect
    const header = document.querySelector('.main-header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    if (tooltipTriggerList.length) {
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    if (forms.length) {
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }
    
    // Job filter functionality
    const jobFilter = document.getElementById('job-filter');
    const jobCards = document.querySelectorAll('.job-card');
    
    if (jobFilter && jobCards.length) {
        jobFilter.addEventListener('change', function() {
            const filterValue = this.value;
            
            jobCards.forEach(card => {
                if (filterValue === 'all' || card.dataset.jobType === filterValue) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    
    // Alert auto-dismiss
    const alerts = document.querySelectorAll('.alert-dismissible');
    if (alerts.length) {
        alerts.forEach(alert => {
            setTimeout(() => {
                const closeButton = alert.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                }
            }, 5000);
        });
    }
    
    // Animated counters for statistics
    const counterElements = document.querySelectorAll('.counter-value');
    if (counterElements.length) {
        const animateCounter = (el, final) => {
            let start = 0;
            const duration = 1500;
            const step = timestamp => {
                if (!start) start = timestamp;
                const progress = Math.min((timestamp - start) / duration, 1);
                el.innerText = Math.floor(progress * final);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    el.innerText = final;
                }
            };
            window.requestAnimationFrame(step);
        };
        
        const observerCallback = (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const finalValue = parseInt(target.dataset.count, 10);
                    animateCounter(target, finalValue);
                    observer.unobserve(target);
                }
            });
        };
        
        const observer = new IntersectionObserver(observerCallback, { threshold: 0.5 });
        counterElements.forEach(el => observer.observe(el));
    }
    
    // Mobile nav toggle
    const navToggle = document.getElementById('mobile-nav-toggle');
    const navMenu = document.getElementById('main-nav');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('show');
            this.setAttribute('aria-expanded', 
                this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true'
            );
        });
    }
    
    // Add animation to elements when they come into view
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    if (animatedElements.length) {
        const animateOnScroll = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated');
                    entry.target.classList.add(entry.target.dataset.animation || 'animate__fadeIn');
                    animateOnScroll.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(el => animateOnScroll.observe(el));
    }
});
