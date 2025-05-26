document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileNavToggle = document.getElementById('mobile-nav-toggle');
    const mainNav = document.getElementById('main-nav');
    
    if (mobileNavToggle && mainNav) {
        mobileNavToggle.addEventListener('click', function() {
            mainNav.classList.toggle('show');
            mobileNavToggle.setAttribute('aria-expanded', 
                mobileNavToggle.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
        });
    }
    
    // Dark mode toggle
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const body = document.body;
    
    // Check for saved dark mode preference
    const savedDarkMode = localStorage.getItem('darkMode') === 'true';
    
    // Apply dark mode if previously saved
    if (savedDarkMode) {
        document.documentElement.setAttribute('data-theme', 'dark');
        if (darkModeToggle) {
            const icon = darkModeToggle.querySelector('i');
            if (icon) icon.classList.replace('bi-moon', 'bi-sun');
        }
    }
    
    // Dark mode toggle functionality
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            // Set the theme attribute
            document.documentElement.setAttribute('data-theme', newTheme);
            
            // Save preference to localStorage
            localStorage.setItem('darkMode', newTheme === 'dark');
            
            // Toggle icon
            const icon = darkModeToggle.querySelector('i');
            if (icon) {
                icon.classList.replace(
                    newTheme === 'dark' ? 'bi-moon' : 'bi-sun',
                    newTheme === 'dark' ? 'bi-sun' : 'bi-moon'
                );
            }
        });
    }
    
    // Header scroll effect
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.main-header');
        if (header) {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
    });
});
