<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>سامانه تیکت پشتیبانی فراهم - نسخه ویژه کاربران پشتیبان</title>

  <!-- Meta -->
  <meta name="description" content="سامانه تیکت پشتیبانی فراهم - نسخه ویژه کاربران پشتیبان" />
  <meta name="theme-color" content="var(--color-login-primary, #2563eb)">
  <meta name="author" content="okteam" />
  
  <!-- Critical: Apply theme immediately before render to prevent FOUC -->
  <script>
      (function() {
          // This runs synchronously before any rendering - prevents flash of wrong theme
          try {
              var STORAGE_KEY = 'rabin-ticket-theme';
              var savedTheme = null;
              
              // Try to get saved theme from localStorage (may fail in private mode)
              try {
                  savedTheme = localStorage.getItem(STORAGE_KEY);
              } catch(e) {
                  // localStorage not available (private browsing, etc.)
              }
              
              // Detect system preference
              var systemPreference = 'dark'; // default
              if (window.matchMedia) {
                  try {
                      systemPreference = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
                  } catch(e) {
                      // matchMedia not supported
                  }
              }
              
              // Determine theme: saved preference > system preference > dark (default)
              var theme = savedTheme || systemPreference || 'dark';
              
              // Apply theme class immediately to <html> element (before CSS loads)
              // This prevents any flash of unstyled content
              var html = document.documentElement;
              if (theme === 'light') {
                  html.classList.add('theme-light');
                  html.setAttribute('data-theme', 'light');
              } else {
                  html.classList.add('theme-dark');
                  html.setAttribute('data-theme', 'dark');
              }
              
              // Update meta theme-color immediately if element exists
              var metaThemeColor = document.querySelector('meta[name="theme-color"]');
              if (metaThemeColor) {
                  metaThemeColor.setAttribute('content', theme === 'light' ? '#ffffff' : '#1c1f21');
              }
          } catch(e) {
              // If anything fails, default to dark theme
              document.documentElement.classList.add('theme-dark');
              document.documentElement.setAttribute('data-theme', 'dark');
          }
      })();
  </script>
  <link rel="canonical" href="">
  <meta property="og:url" content="">
  <meta property="og:title" content="سامانه تیکت پشتیبانی فراهم - نسخه ویژه کاربران پشتیبان">
  <meta property="og:description" content="سامانه تیکت پشتیبانی فراهم - نسخه ویژه کاربران پشتیبان">
  <meta property="og:type" content="APP">
  <meta property="og:site_name" content="سامانه تیکت پشتیبانی فراهم">
  <link rel="shortcut icon" href="assets/images/logo.png" />

  <!-- *************
			************ CSS Files *************
		************* -->
  <link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="assets/css/main.min.css" />

  <!-- Custom Styles for Support Staff Login -->
  <style>
    /* Vazirmatn Font Face Declarations */
    @font-face {
      font-family: 'Vazirmatn';
      src: url('assets/fonts/Vazirmatn/static/Vazirmatn-Regular.ttf') format('truetype');
      font-weight: 400;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Vazirmatn';
      src: url('assets/fonts/Vazirmatn/static/Vazirmatn-Medium.ttf') format('truetype');
      font-weight: 500;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Vazirmatn';
      src: url('assets/fonts/Vazirmatn/static/Vazirmatn-SemiBold.ttf') format('truetype');
      font-weight: 600;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Vazirmatn';
      src: url('assets/fonts/Vazirmatn/static/Vazirmatn-Bold.ttf') format('truetype');
      font-weight: 700;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Vazirmatn';
      src: url('assets/fonts/Vazirmatn/static/Vazirmatn-ExtraBold.ttf') format('truetype');
      font-weight: 800;
      font-style: normal;
      font-display: swap;
    }

    :root {
      --primary-color: var(--color-login-primary, #2563eb);
      --primary-dark: var(--color-login-primary-dark, #1d4ed8);
      --primary-light: var(--color-login-primary-light, #3b82f6);
      --secondary-color: var(--color-login-secondary, #1e40af);
      --accent-color: var(--color-login-accent, #60a5fa);
      --gradient-start: var(--color-login-gradient-start, #2563eb);
      --gradient-end: var(--color-login-gradient-end, #1e40af);
      --text-primary: var(--color-login-text-primary, #1f2937);
      --text-secondary: var(--color-login-text-secondary, #6b7280);
      --bg-primary: var(--color-login-bg-primary, #f8fafc);
      --bg-secondary: var(--color-login-bg-secondary, #ffffff);
      --border-color: var(--color-login-border, #e5e7eb);
      --shadow: 0 10px 25px -5px var(--color-shadow-sm, rgba(0, 0, 0, 0.1)), 0 10px 10px -5px var(--color-shadow-sm, rgba(0, 0, 0, 0.04));
      --shadow-lg: 0 25px 50px -12px var(--color-shadow-lg, rgba(0, 0, 0, 0.25));
    }

    body {
      background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
      min-height: 100vh;
      font-family: 'Vazirmatn', 'Vazir', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      position: relative;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      pointer-events: none;
    }

    .page-wrapper {
      position: relative;
      z-index: 1;
    }

    .auth-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .login-card {
      background: var(--bg-secondary);
      border-radius: 24px;
      box-shadow: var(--shadow-lg);
      padding: 3rem;
      width: 100%;
      max-width: 450px;
      position: relative;
      overflow: hidden;
      border: 1px solid var(--color-border-light, rgba(255, 255, 255, 0.2));
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    }

    .auth-logo {
      display: block;
      text-align: center;
      margin-bottom: 2rem;
      transition: transform 0.3s ease;
    }

    .auth-logo:hover {
      transform: scale(1.05);
    }

    .auth-logo img {
      max-width: 120px;
      height: auto;
      filter: drop-shadow(0 4px 8px var(--color-shadow-sm, rgba(0, 0, 0, 0.1)));
    }

    .version-badge {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 50px;
      font-size: 0.875rem;
      font-weight: 600;
      font-family: 'Vazirmatn', 'Vazir', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      text-align: center;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px var(--color-shadow-md, rgba(37, 99, 235, 0.3));
      display: inline-block;
      width: 100%;
    }

    .login-title {
      color: var(--text-primary);
      font-size: 1.75rem;
      font-weight: 700;
      font-family: 'Vazirmatn', 'Vazir', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      text-align: center;
      margin-bottom: 0.5rem;
      line-height: 1.3;
    }

    .login-subtitle {
      color: var(--text-secondary);
      font-size: 1rem;
      font-family: 'Vazirmatn', 'Vazir', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      text-align: center;
      margin-bottom: 2rem;
      line-height: 1.5;
    }

    .form-group {
      margin-bottom: 1.25rem;
    }

    .form-label {
      color: var(--text-primary);
      font-weight: 600;
      font-family: 'Vazirmatn', 'Vazir', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin-bottom: 0.5rem;
      display: block;
      font-size: 0.9rem;
    }

    .input-group {
      position: relative;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 3px var(--color-shadow-sm, rgba(0, 0, 0, 0.1));
      transition: all 0.3s ease;
      border: 1px solid var(--border-color);
      background: var(--bg-secondary);
    }

    .input-group:focus-within {
      box-shadow: 0 0 0 2px var(--color-badge-primary-bg, rgba(37, 99, 235, 0.15));
      transform: translateY(-1px);
      border-color: var(--primary-color);
    }

    .input-group-text {
      background: var(--primary-color);
      border: none;
      color: white;
      padding: 0.75rem;
      border-radius: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 45px;
      font-size: 0.9rem;
    }

    .form-control {
      border: none;
      padding: 0.75rem;
      font-size: 0.95rem;
      border-radius: 0;
      transition: all 0.3s ease;
      background: transparent;
      height: auto;
      box-shadow: none;
    }

    .form-control:focus {
      border-color: transparent;
      box-shadow: none;
      background: transparent;
      outline: none;
    }

    .form-control::placeholder {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    .password-toggle {
      background: transparent;
      border: none;
      color: var(--text-secondary);
      padding: 0.75rem;
      transition: all 0.3s ease;
      cursor: pointer;
      font-size: 0.9rem;
    }

    .password-toggle:hover {
      background: var(--bg-primary);
      color: var(--primary-color);
    }

    .btn-login {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border: none;
      padding: 0.75rem 2rem;
      font-size: 1rem;
      font-weight: 600;
      font-family: 'Vazirmatn', 'Vazir', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      border-radius: 8px;
      color: white;
      width: 100%;
      transition: all 0.3s ease;
      box-shadow: 0 3px 8px var(--color-shadow-md, rgba(37, 99, 235, 0.3));
      position: relative;
      overflow: hidden;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, var(--color-border-light, rgba(255, 255, 255, 0.2)), transparent);
      transition: left 0.5s;
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px var(--color-shadow-lg, rgba(37, 99, 235, 0.4));
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .floating-shapes {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      pointer-events: none;
      z-index: 0;
    }

    .shape {
      position: absolute;
      background: var(--color-border-light, rgba(255, 255, 255, 0.1));
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    .shape:nth-child(1) {
      width: 80px;
      height: 80px;
      top: 20%;
      left: 10%;
      animation-delay: 0s;
    }

    .shape:nth-child(2) {
      width: 120px;
      height: 120px;
      top: 60%;
      right: 10%;
      animation-delay: 2s;
    }

    .shape:nth-child(3) {
      width: 60px;
      height: 60px;
      top: 40%;
      left: 80%;
      animation-delay: 4s;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0px) rotate(0deg);
      }

      50% {
        transform: translateY(-20px) rotate(180deg);
      }
    }

    .error-message {
      background: var(--color-login-error-bg, #fef2f2);
      border: 1px solid var(--color-login-error-border, #fecaca);
      color: var(--color-login-error-text, #dc2626);
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      text-align: center;
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .login-card {
        padding: 2rem;
        margin: 1rem;
      }

      .login-title {
        font-size: 1.5rem;
      }
    }

    .loading {
      display: none;
    }

    .loading.show {
      display: inline-block;
    }

    /* Startup Animation Styles */
    .login-card {
      opacity: 0;
      transform: translateY(30px);
      animation: fadeInUp 0.8s ease-out forwards;
    }

    .auth-logo {
      opacity: 0;
      transform: scale(0.8);
      animation: fadeInScale 0.6s ease-out 0.2s forwards;
    }

    .version-badge {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.6s ease-out 0.4s forwards;
    }

    .login-title {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.6s ease-out 0.6s forwards;
    }

    .login-subtitle {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.6s ease-out 0.8s forwards;
    }

    .form-group {
      opacity: 0;
      transform: translateX(-30px);
    }

    .form-group:first-of-type {
      animation: fadeInLeft 0.6s ease-out 1.0s forwards;
    }

    .form-group:last-of-type {
      animation: fadeInLeft 0.6s ease-out 1.2s forwards;
    }

    .d-grid {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.6s ease-out 1.4s forwards;
    }

    .error-message {
      opacity: 0;
      transform: scale(0.9);
      animation: fadeInScale 0.5s ease-out 0.5s forwards;
    }

    /* Animation Keyframes */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInLeft {
      from {
        opacity: 0;
        transform: translateX(-30px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeInScale {
      from {
        opacity: 0;
        transform: scale(0.8);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.05);
      }
    }

    /* Floating shapes animation */
    .shape {
      opacity: 0;
      animation: fadeInFloat 2s ease-out forwards;
    }

    .shape:nth-child(1) {
      animation-delay: 0.5s;
    }

    .shape:nth-child(2) {
      animation-delay: 1s;
    }

    .shape:nth-child(3) {
      animation-delay: 1.5s;
    }

    @keyframes fadeInFloat {
      from {
        opacity: 0;
        transform: translateY(50px) rotate(0deg);
      }

      to {
        opacity: 0.1;
        transform: translateY(0) rotate(360deg);
      }
    }

    /* Hover effects for interactive elements */
    .form-group:hover {
      transform: translateX(5px);
      transition: transform 0.3s ease;
    }

    .btn-login:hover {
      animation: pulse 0.6s ease-in-out;
    }

    /* Shake animation for error messages */
    @keyframes shake {

      0%,
      100% {
        transform: translateX(0);
      }

      25% {
        transform: translateX(-5px);
      }

      75% {
        transform: translateX(5px);
      }
    }
  </style>
</head>

<body>
  <!-- Floating shapes for visual appeal -->
  <div class="floating-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
  </div>

  <!-- Page wrapper starts -->
  <div class="page-wrapper">
    <!-- Auth container starts -->
    <div class="auth-container">
      <div class="d-flex justify-content-center">
        <!-- Form starts -->
        <form action="page/ux/checklogin.php" method="post" class="login-card">
          <!-- Logo starts -->
          <a href="support.php" class="auth-logo">
            <img src="assets/images/logo.png" alt="سامانه تیکت پشتیبانی فراهم" />
          </a>
          <!-- Logo ends -->

          <!-- Version Badge -->
          <div class="version-badge">
            نسخه ویژه کاربران پشتیبان
          </div>

          <!-- Title -->
          <h1 class="login-title">سامانه درخواست</h1>
          <p class="login-subtitle">گروه صنعتی رهباریان</p>

          <!-- Error message display -->
          <?php
          $error_message = "";
          if (isset($_GET['error'])) {
            switch ($_GET['error']) {
              case 'empty':
                $error_message = "نام کاربری و کلمه عبور را وارد نمایید";
                break;
              case 'invalid':
                $error_message = "نام کاربری / کلمه عبور وارد شده صحیح نیست";
                break;
              default:
                $error_message = "خطایی رخ داده است. لطفاً دوباره تلاش کنید";
            }
          } elseif (isset($payam_login)) {
            $error_message = $payam_login;
          }

          if (!empty($error_message)): ?>
            <div class="error-message">
              <?php echo $error_message; ?>
            </div>
          <?php endif; ?>

          <!-- Username field -->
          <div class="form-group">
            <label class="form-label" for="id_user">
              کد کاربری <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-person-fill"></i>
              </span>
              <input type="text" id="id_user" name="id_user" class="form-control"
                placeholder="کد پرسنلی خود را وارد کنید" required>
            </div>
          </div>

          <!-- Password field -->
          <div class="form-group">
            <label class="form-label" for="pass_user">
              رمز عبور <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-lock-fill"></i>
              </span>
              <input type="password" name="pass_user" id="pass_user" class="form-control"
                placeholder="رمز عبور خود را وارد کنید" required>
              <button class="password-toggle" type="button" onclick="togglePassword()">
                <i class="bi bi-eye" id="toggleIcon"></i>
              </button>
            </div>
          </div>

          <!-- Login button -->
          <div class="d-grid gap-2">
            <button type="submit" class="btn-login" id="loginBtn">
              <span class="loading" id="loadingSpinner">
                <i class="bi bi-arrow-clockwise spin"></i>
              </span>
              <span id="loginText">ورود به سیستم</span>
            </button>
          </div>
        </form>
        <!-- Form ends -->
      </div>
    </div>
    <!-- Auth container ends -->
  </div>
  <!-- Page wrapper ends -->

  <!-- JavaScript for enhanced functionality -->
  <script>
    // Page load animation trigger
    document.addEventListener('DOMContentLoaded', function() {
      // Add a subtle entrance effect to the body
      document.body.style.opacity = '0';
      document.body.style.transform = 'scale(0.95)';

      setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
        document.body.style.opacity = '1';
        document.body.style.transform = 'scale(1)';
      }, 100);
    });

    // Password toggle functionality
    function togglePassword() {
      const passwordField = document.getElementById('pass_user');
      const toggleIcon = document.getElementById('toggleIcon');

      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'bi bi-eye-slash';
      } else {
        passwordField.type = 'password';
        toggleIcon.className = 'bi bi-eye';
      }
    }

    // Form submission with loading state and validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const username = document.getElementById('id_user').value.trim();
      const password = document.getElementById('pass_user').value.trim();
      const loginBtn = document.getElementById('loginBtn');
      const loadingSpinner = document.getElementById('loadingSpinner');
      const loginText = document.getElementById('loginText');

      // Check if fields are empty
      if (username === '' || password === '') {
        e.preventDefault();

        // Show error message
        let errorDiv = document.querySelector('.error-message');
        if (!errorDiv) {
          errorDiv = document.createElement('div');
          errorDiv.className = 'error-message';
          document.querySelector('.login-subtitle').insertAdjacentElement('afterend', errorDiv);
        }
        errorDiv.textContent = 'نام کاربری و کلمه عبور را وارد نمایید';

        // Add shake animation
        errorDiv.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
          errorDiv.style.animation = '';
        }, 500);

        return false;
      }

      // Show loading state
      loginBtn.disabled = true;
      loadingSpinner.classList.add('show');
      loginText.textContent = 'در حال ورود...';
    });

    // Add focus effects to input groups
    document.querySelectorAll('.input-group').forEach(group => {
      const input = group.querySelector('.form-control');
      input.addEventListener('focus', () => {
        group.style.transform = 'translateY(-2px)';
      });

      input.addEventListener('blur', () => {
        group.style.transform = 'translateY(0)';
      });
    });

    // Add entrance animation to form elements when they come into view
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animationPlayState = 'running';
        }
      });
    }, observerOptions);

    // Observe all animated elements
    document.querySelectorAll('.form-group, .d-grid, .version-badge, .login-title, .login-subtitle').forEach(el => {
      observer.observe(el);
    });

    // Force animation on form groups if they don't animate
    setTimeout(() => {
      document.querySelectorAll('.form-group').forEach((el, index) => {
        if (el.style.opacity === '0' || el.style.opacity === '') {
          el.style.animation = `fadeInLeft 0.6s ease-out ${1.0 + (index * 0.2)}s forwards`;
        }
      });
    }, 100);

    // Add CSS for loading spinner
    const style = document.createElement('style');
    style.textContent = `
            .spin {
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
    document.head.appendChild(style);
  </script>
</body>

</html>