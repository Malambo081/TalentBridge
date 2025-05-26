/**
 * Enhanced Header JavaScript for TalentBridge
 * Handles mobile menu toggle, dark mode toggle, and header scroll effects
 */
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown accessibility
    document.querySelectorAll('.nav-dropdown > .nav-link').forEach(function(dropdownToggle) {
        dropdownToggle.setAttribute('aria-haspopup', 'true');
        dropdownToggle.setAttribute('aria-expanded', 'false');
        dropdownToggle.setAttribute('tabindex', '0');
        dropdownToggle.setAttribute('role', 'button');

        var dropdownMenu = dropdownToggle.nextElementSibling;
        if (dropdownMenu) {
            dropdownMenu.setAttribute('role', 'menu');
            dropdownMenu.querySelectorAll('.dropdown-item').forEach(function(item) {
                item.setAttribute('role', 'menuitem');
            });
        }

        // Keyboard navigation
        dropdownToggle.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                dropdownToggle.parentElement.classList.toggle('open');
                var expanded = dropdownToggle.getAttribute('aria-expanded') === 'true';
                dropdownToggle.setAttribute('aria-expanded', !expanded);
                if (!expanded && dropdownMenu) dropdownMenu.querySelector('.dropdown-item')?.focus();
            }
            if (e.key === 'Escape') {
                dropdownToggle.parentElement.classList.remove('open');
                dropdownToggle.setAttribute('aria-expanded', 'false');
                dropdownToggle.blur();
            }
        });
        dropdownToggle.addEventListener('focus', function() {
            dropdownToggle.parentElement.classList.add('focus');
        });
        dropdownToggle.addEventListener('blur', function() {
            dropdownToggle.parentElement.classList.remove('focus');
        });
    });

    // Set active class on dropdown items if subpage is active
    var path = window.location.pathname.split('/').pop();
    document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(function(item) {
        if (item.href && item.href.indexOf(path) !== -1) {
            item.classList.add('active');
        }
    });

    // Mobile menu toggle
    const mobileNavToggle = document.getElementById('mobile-nav-toggle');
    const mainNav = document.getElementById('main-nav');

    if (mobileNavToggle && mainNav) {
        mobileNavToggle.addEventListener('click', function() {
            mainNav.classList.toggle('show');
            mobileNavToggle.setAttribute('aria-expanded', 
                mainNav.classList.contains('show') ? 'true' : 'false');
        });
    }

    // Handle clicks outside the mobile menu to close it
    document.addEventListener('click', function(event) {
        if (mainNav && mainNav.classList.contains('show')) {
            // If clicking outside the mobile menu and not on the toggle button
            if (!mainNav.contains(event.target) && 
                !mobileNavToggle.contains(event.target)) {
                mainNav.classList.remove('show');
                mobileNavToggle.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // Header scroll effect
    const header = document.querySelector('.main-header');
    
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // Dark mode toggle functionality
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const body = document.body;
    
    // Check for saved theme preference or use preferred color scheme
    const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const savedTheme = localStorage.getItem('theme');
    
    // Apply saved theme or use system preference
    if (savedTheme === 'dark' || (!savedTheme && prefersDarkMode)) {
        body.setAttribute('data-theme', 'dark');
        if (darkModeToggle) {
            darkModeToggle.innerHTML = '<i class="bi bi-sun"></i>';
        }
    } else {
        body.setAttribute('data-theme', 'light');
        if (darkModeToggle) {
            darkModeToggle.innerHTML = '<i class="bi bi-moon"></i>';
        }
    }
    
    // Dark mode toggle click handler
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            if (body.getAttribute('data-theme') === 'light') {
                body.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                darkModeToggle.innerHTML = '<i class="bi bi-sun"></i>';
            } else {
                body.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
                darkModeToggle.innerHTML = '<i class="bi bi-moon"></i>';
            }
        });
    }

    // Add active class to current page in navigation
    const currentLocation = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');
        if (linkPath && currentLocation.includes(linkPath) && linkPath !== 'index.php') {
            link.classList.add('active');
        }
    });
});
