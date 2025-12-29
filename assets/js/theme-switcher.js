/**
 * Theme Switcher Utility
 * Allows switching between dark and light themes
 */

(function() {
    'use strict';

    const ThemeSwitcher = {
        // Theme storage key
        STORAGE_KEY: 'rabin-ticket-theme',
        
        // Current theme
        currentTheme: 'dark',
        
        /**
         * Initialize theme switcher
         */
        init: function() {
            // Load saved theme or detect system preference
            const savedTheme = localStorage.getItem(this.STORAGE_KEY);
            const systemPreference = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
            
            this.currentTheme = savedTheme || systemPreference;
            this.applyTheme(this.currentTheme);
            
            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', (e) => {
                if (!localStorage.getItem(this.STORAGE_KEY)) {
                    this.applyTheme(e.matches ? 'light' : 'dark');
                }
            });
        },
        
        /**
         * Apply theme to document
         * @param {string} theme - 'light' or 'dark'
         */
        applyTheme: function(theme) {
            this.currentTheme = theme;
            
            // Remove existing theme classes
            document.documentElement.classList.remove('theme-light', 'theme-dark');
            document.documentElement.removeAttribute('data-theme');
            
            // Apply new theme
            if (theme === 'light') {
                document.documentElement.classList.add('theme-light');
                document.documentElement.setAttribute('data-theme', 'light');
            } else {
                document.documentElement.classList.add('theme-dark');
                document.documentElement.setAttribute('data-theme', 'dark');
            }
            
            // Update meta theme-color
            const metaThemeColor = document.querySelector('meta[name="theme-color"]');
            if (metaThemeColor) {
                metaThemeColor.setAttribute('content', 
                    theme === 'light' ? '#ffffff' : '#1c1f21'
                );
            }
            
            // Save to localStorage
            localStorage.setItem(this.STORAGE_KEY, theme);
            
            // Dispatch custom event
            document.dispatchEvent(new CustomEvent('themeChanged', { 
                detail: { theme: theme } 
            }));
        },
        
        /**
         * Toggle between light and dark themes
         */
        toggle: function() {
            const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
            this.applyTheme(newTheme);
            return newTheme;
        },
        
        /**
         * Set theme explicitly
         * @param {string} theme - 'light' or 'dark'
         */
        setTheme: function(theme) {
            if (theme === 'light' || theme === 'dark') {
                this.applyTheme(theme);
            }
        },
        
        /**
         * Get current theme
         * @returns {string} Current theme
         */
        getTheme: function() {
            return this.currentTheme;
        }
    };
    
    // Initialize theme switcher
    // Note: Theme is already applied by inline script in <head> to prevent FOUC
    // This initialization ensures the ThemeSwitcher object is available and syncs state
    function initializeThemeSwitcher() {
        // Get current theme from DOM (already set by inline script)
        const currentThemeAttr = document.documentElement.getAttribute('data-theme');
        const currentThemeClass = document.documentElement.classList.contains('theme-light') ? 'light' : 'dark';
        ThemeSwitcher.currentTheme = currentThemeAttr || currentThemeClass || 'dark';
        
        // Sync with localStorage if needed (in case localStorage was cleared)
        const savedTheme = localStorage.getItem(ThemeSwitcher.STORAGE_KEY);
        if (savedTheme && savedTheme !== ThemeSwitcher.currentTheme) {
            ThemeSwitcher.applyTheme(savedTheme);
        } else if (!savedTheme) {
            // Save current theme to localStorage
            localStorage.setItem(ThemeSwitcher.STORAGE_KEY, ThemeSwitcher.currentTheme);
        }
        
        // Listen for system theme changes (only if no saved preference)
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', (e) => {
                if (!localStorage.getItem(ThemeSwitcher.STORAGE_KEY)) {
                    ThemeSwitcher.applyTheme(e.matches ? 'light' : 'dark');
                }
            });
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeThemeSwitcher);
    } else {
        // DOM already loaded
        initializeThemeSwitcher();
    }
    
    // Expose to global scope
    window.ThemeSwitcher = ThemeSwitcher;
    
})();

