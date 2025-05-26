/**
 * Talent Bridge - Dark Mode Toggle
 * Handles user preference for dark/light mode
 */

document.addEventListener('DOMContentLoaded', function() {
    // Dark mode toggle functionality
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    
    // Function to set the theme
    const setTheme = (theme) => {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        // Update toggle button
        if (darkModeToggle) {
            const icon = darkModeToggle.querySelector('i');
            if (theme === 'dark') {
                icon.classList.remove('bi-moon');
                icon.classList.add('bi-sun');
                darkModeToggle.setAttribute('aria-label', 'Switch to light mode');
            } else {
                icon.classList.remove('bi-sun');
                icon.classList.add('bi-moon');
                darkModeToggle.setAttribute('aria-label', 'Switch to dark mode');
            }
        }
    };
    
    // Check for saved user preference, if any
    const storedTheme = localStorage.getItem('theme');
    
    // If the user has explicitly chosen a theme, use it
    if (storedTheme) {
        setTheme(storedTheme);
    } 
    // Otherwise, respect the system preference
    else if (prefersDarkScheme.matches) {
        setTheme('dark');
    }
    
    // Listen for toggle click
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });
    }
    
    // Listen for system preference changes
    prefersDarkScheme.addEventListener('change', function(e) {
        // Only update if the user hasn't manually set a preference
        if (!localStorage.getItem('theme')) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });
});
