# Color System Documentation

## Overview

All colors in the Rabin-Ticket project have been converted to CSS variables, making it easy to switch between dark and light themes. The color system is centralized in `assets/css/colors.css`.

## File Structure

- **`assets/css/colors.css`** - Main color variables file (dark & light themes)
- **`assets/css/theme.css`** - Theme-specific overrides using variables
- **`assets/js/theme-switcher.js`** - JavaScript utility for theme switching

## Color Variables

### Primary Colors
```css
--color-primary: #3c92b1
--color-primary-dark: #2d7a93
--color-primary-light: #6fb4ce
```

### Semantic Colors
```css
--color-success: #a9bd7a
--color-danger: #bf7a6a
--color-warning: #d2a968
--color-info: #6fb4ce
--color-secondary: #50596a
```

### Background Colors
```css
--color-bg-primary: #1c1f21 (dark) / #f8f9fa (light)
--color-bg-secondary: #262b33 (dark) / #ffffff (light)
--color-bg-tertiary: #20252e (dark) / #f1f3f5 (light)
--color-bg-card: #303641 (dark) / #ffffff (light)
```

### Text Colors
```css
--color-text-primary: #95a0b1 (dark) / #212529 (light)
--color-text-secondary: #6a7384 (dark) / #6c757d (light)
--color-text-muted: #868fa0 (dark) / #868e96 (light)
```

### Border Colors
```css
--color-border-primary: #50596a (dark) / #dee2e6 (light)
--color-border-secondary: #495160 (dark) / #e9ecef (light)
--color-border-light: rgba(255, 255, 255, 0.1) (dark) / rgba(0, 0, 0, 0.08) (light)
```

### Shadow Colors
```css
--color-shadow-sm: rgba(0, 0, 0, 0.1) (dark) / rgba(0, 0, 0, 0.05) (light)
--color-shadow-md: rgba(0, 0, 0, 0.2) (dark) / rgba(0, 0, 0, 0.1) (light)
--color-shadow-lg: rgba(0, 0, 0, 0.3) (dark) / rgba(0, 0, 0, 0.15) (light)
```

### Badge Soft Colors
```css
--color-badge-primary-bg: rgba(60, 146, 177, 0.2) (dark) / rgba(13, 110, 253, 0.1) (light)
--color-badge-primary-text: #6fb4ce (dark) / #0d6efd (light)
--color-badge-success-bg: rgba(169, 189, 122, 0.2) (dark) / rgba(25, 135, 84, 0.1) (light)
--color-badge-success-text: #a9bd7a (dark) / #198754 (light)
--color-badge-danger-bg: rgba(191, 122, 106, 0.2) (dark) / rgba(220, 53, 69, 0.1) (light)
--color-badge-danger-text: #bf7a6a (dark) / #dc3545 (light)
--color-badge-warning-bg: rgba(210, 169, 104, 0.2) (dark) / rgba(255, 193, 7, 0.1) (light)
--color-badge-warning-text: #d2a968 (dark) / #ffc107 (light)
--color-badge-info-bg: rgba(111, 180, 206, 0.2) (dark) / rgba(13, 202, 240, 0.1) (light)
--color-badge-info-text: #6fb4ce (dark) / #0dcaf0 (light)
--color-badge-secondary-bg: rgba(108, 117, 125, 0.2) (dark) / rgba(108, 117, 125, 0.1) (light)
--color-badge-secondary-text: #6c757d (both themes)
```

## Usage

### In CSS
```css
/* Use variables instead of hardcoded colors */
.my-element {
    background-color: var(--color-bg-card);
    color: var(--color-text-primary);
    border: 1px solid var(--color-border-primary);
    box-shadow: 0 4px 12px var(--color-shadow-md);
}
```

### In Inline Styles (PHP)
```php
<div style="background-color: var(--color-bg-card); color: var(--color-text-primary);">
    Content
</div>
```

### With Fallbacks
```css
/* Always provide a fallback for compatibility */
.my-element {
    color: var(--color-text-primary, #95a0b1);
}
```

## Theme Switching

### Automatic Theme Detection
The theme switcher automatically detects system preference and applies it on first load.

### Manual Theme Switching

#### JavaScript API
```javascript
// Toggle between themes
ThemeSwitcher.toggle();

// Set specific theme
ThemeSwitcher.setTheme('light');
ThemeSwitcher.setTheme('dark');

// Get current theme
const currentTheme = ThemeSwitcher.getTheme();
```

#### HTML Button Example
```html
<button onclick="ThemeSwitcher.toggle()">
    Toggle Theme
</button>
```

#### Listen to Theme Changes
```javascript
document.addEventListener('themeChanged', function(e) {
    console.log('Theme changed to:', e.detail.theme);
});
```

## Theme Application

Themes are applied using the `data-theme` attribute and CSS classes:

- **Dark Theme (Default)**: No class needed, or `data-theme="dark"` / `.theme-dark`
- **Light Theme**: `data-theme="light"` / `.theme-light`

The theme switcher automatically:
1. Adds/removes theme classes
2. Updates `data-theme` attribute
3. Updates meta `theme-color`
4. Saves preference to localStorage
5. Dispatches `themeChanged` event

## Files Updated

### Core Files
- ✅ `assets/css/colors.css` - Created comprehensive color system
- ✅ `assets/css/theme.css` - Updated to use variables
- ✅ `assets/js/theme-switcher.js` - Created theme switching utility

### PHP Files
- ✅ `index.php` - Replaced hardcoded colors with variables
- ✅ `login.php` - Replaced hardcoded colors with variables
- ✅ `page/ui/list_ticket.php` - Replaced hardcoded colors with variables

### Remaining Files
The following files may still contain hardcoded colors and should be updated:
- `page/ui/info_ticket.php`
- `page/ui/profile.php`
- `page/ui/my_work.php`
- `page/ui/list_working_on.php`
- `page/ui/view_priority.php`
- `page/ui/set_priority.php`
- Other page files as needed

## Best Practices

1. **Always use variables** - Never hardcode colors in new code
2. **Provide fallbacks** - Use `var(--color-name, #fallback)` for compatibility
3. **Use semantic names** - Prefer `--color-text-primary` over `--color-gray-600`
4. **Test both themes** - Ensure your changes work in both dark and light modes
5. **Update both themes** - When adding new colors, define them for both themes

## Adding New Colors

To add a new color:

1. Add to `:root` in `colors.css`:
```css
:root {
    --color-my-new-color: #hexcode;
}
```

2. Add light theme override:
```css
[data-theme="light"],
.theme-light {
    --color-my-new-color: #lighthexcode;
}
```

3. Use in your code:
```css
.my-element {
    color: var(--color-my-new-color);
}
```

## Migration Checklist

When updating existing files:

- [ ] Replace all `#hex` colors with variables
- [ ] Replace all `rgb()` / `rgba()` with variables
- [ ] Test in both dark and light themes
- [ ] Ensure proper contrast ratios
- [ ] Update any JavaScript that manipulates colors

## Browser Support

CSS variables are supported in:
- Chrome 49+
- Firefox 31+
- Safari 9.1+
- Edge 15+

For older browsers, fallback values are provided.

---

*Last Updated: 2025-01-XX*
*Color System Version: 1.0*

