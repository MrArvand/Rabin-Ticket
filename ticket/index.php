<?php

include('inf/f1.php');
if (!isset($_SESSION['ok_login_i'])) {
  $_SESSION['ok_login_i'] = "n";
}
if ($_SESSION['ok_login_i'] != 'y') {
  include('login.php');
} else {

  //----------------------------------------------------------------------
  $code_p_run = $_SESSION['code_p'];
  $name_karbar_run = $_SESSION['name'];
  $semat_karbar_run = $_SESSION['semat'];
  $avatar = $_SESSION['avatar'];


  $mojavez = "";
  $mojavez = "login*" . time();

  include('uxindex.php');

  $_SESSION['mojavez'] = $mojavez;

  //----------------------------------------------------------

?>



  <!DOCTYPE html>
  <html lang="en" dir="rtl">


  <!-- Mirrored from kodingwife.com/demos/templatemonster/one/default.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 07 May 2024 09:59:11 GMT -->

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>سامانه تیکت فراهم - <?php echo $name_karbar_run; ?> </title>
    <meta name="theme-color" content="#1c1f21">
    <!-- Meta -->
    <meta name="description" content="ticket for rahbarian group" />
    <meta name="author" content="okteam" />
    <link rel="canonical" href="https://request-r.ir/">
    <meta property="og:url" content="https://request-r.ir/">
    <meta property="og:title" content="سامانه تیکت پشتیبانی فراهم ">
    <meta property="og:description" content="سامانه تیکت پشتیبانی فراهم">
    <meta property="og:type" content="APP">
    <meta property="og:site_name" content="سامانه تیکت پشتیبانی فراهم">
    <link rel="shortcut icon" href="assets/images/logo.png" />

    <!-- *************
			************ CSS Files *************
		************* -->
    <link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="assets/css/main.min.css" />

    <!-- *************
			************ Vendor Css Files *************
		************ -->

    <!-- Scrollbar CSS -->
    <link rel="stylesheet" href="assets/vendor/overlay-scroll/OverlayScrollbars.min.css" />

    <!-- Additional CSS for active page highlighting and exit button styling -->
    <style>
      .sidebar-menu>li.current-page>a {
        background-color: #007bff !important;
        color: white !important;
        border-radius: 8px;
        margin: 2px 0;
      }

      .sidebar-menu>li.current-page>a:hover {
        background-color: #0056b3 !important;
        color: white !important;
      }

      .sidebar-menu>li.current-page>a i {
        color: white !important;
      }

      /* Exit button red styling */
      .sidebar-menu li.text-danger a {
        color: #dc3545 !important;
        font-weight: 600;
      }

      .sidebar-menu li.text-danger a:hover {
        color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.1) !important;
        border-radius: 8px;
        margin: 2px 0;
      }

      .sidebar-menu li.text-danger a i {
        color: #dc3545 !important;
      }

      .sidebar-menu li.text-danger a:hover i {
        color: #dc3545 !important;
      }
    </style>

  </head>

  <body>
    <!-- Page wrapper start -->
    <div class="page-wrapper">

      <!-- Main container start -->
      <div class="main-container">

        <!-- Sidebar wrapper start -->
        <nav id="sidebar" class="sidebar-wrapper">

          <!-- App brand starts -->
          <div class="app-brand px-3 py-2 d-flex align-items-center">
            <a href="index.php">
              <img src="assets/images/logo.png" class="logo" alt="FARAHAM Ticket" />
            </a>
          </div>
          <!-- App brand ends -->

          <!-- Sidebar profile starts -->
          <div class="sidebar-profile">
            <img src="assets/images/karbar.png" class="img-3x me-3 rounded-3" alt="Admin Dashboard" />
            <div class="m-0 text-nowrap">
              <p class="m-0"><?php echo $semat_karbar_run; ?></p>
              <h6 class="m-0"><?php echo $name_karbar_run; ?> </h6>
            </div>
          </div>
          <!-- Sidebar profile ends -->

          <!-- Sidebar menu starts -->
          <div class="sidebarMenuScroll">
            <ul class="sidebar-menu">
              <li class="<?php echo (empty($page) || $page == '0' || $page == 'null' || $page == '') ? 'current-page' : ''; ?>">
                <a href="index.php">
                  <i class="bi bi-house"></i>
                  <span class="menu-text">داشبورد</span>
                </a>
              </li>
              <li class="<?php echo ($page == 'list_ticket') ? 'current-page' : ''; ?>">
                <a href="?page=list_ticket">
                  <i class="bi bi-bar-chart-line"></i>
                  <span class="menu-text"> لیست تیکت ها</span>
                </a>
              </li>
              <li class="<?php echo ($page == 'start_ticket') ? 'current-page' : ''; ?>">
                <a href="?page=start_ticket">
                  <i class="bi bi-plus-circle"></i>
                  <span class="menu-text">تیکت جدید</span>
                </a>
              </li>

              <li class="<?php echo ($page == 'academi') ? 'current-page' : ''; ?>">
                <a href="?page=academi">
                  <i class="bi bi-book"></i>
                  <span class="menu-text">آکادمی آموزش</span>
                </a>
              </li>

              <li class="<?php echo ($page == 'help') ? 'current-page' : ''; ?>">
                <a href="?page=help">
                  <i class="bi bi-question-circle"></i>
                  <span class="menu-text">راهنمایی</span>
                </a>
              </li>

              <!-- Separator -->
              <li class="mt-3 mb-2">
                <hr class="my-2">
              </li>

              <!-- Exit button at bottom with red styling -->
              <li class="text-danger">
                <a href="?page=exit" class="text-danger">
                  <i class="bi bi-box-arrow-right"></i>
                  <span class="menu-text"> خروج</span>
                </a>
              </li>
            </ul>
          </div>
          <!-- Sidebar menu ends -->

        </nav>
        <!-- Sidebar wrapper end -->

        <!-- App container starts -->
        <div class="app-container">

          <!-- App header starts -->
          <div class="app-header">

            <!-- Toggle buttons start -->
            <div class="d-flex">
              <button class="btn btn-outline-info btn-sm me-3 toggle-sidebar" id="toggle-sidebar">
                <i class="bi bi-list fs-5"></i>
              </button>
              <button class="btn btn-outline-info btn-sm me-3 pin-sidebar" id="pin-sidebar">
                <i class="bi bi-list fs-5"></i>
              </button>
            </div>
            <!-- Toggle buttons end -->

            <!-- App brand start -->
            <div class="app-brand-sm">
              <a href="index.php" class="d-lg-none d-md-block">
                <img src="assets/images/logo.png" class="logo" alt="Faraham Ticket">
              </a>
            </div>
            <!-- App brand end -->

            <!-- App header actions start -->
            <div class="header-actions">




              <div class="dropdown ms-2">
                <a id="userSettings" class="dropdown-toggle d-flex py-2 align-items-center text-decoration-none"
                  href="#!" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="icon-box md bg-info text-white rounded-5">

                    <i class="fs-3 bi bi-person-fill"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-start">

                  <a class="dropdown-item d-flex align-items-center" href="?page=help"><i
                      class="bi bi-help fs-4 me-2"></i>راهنمایی</a>

                  <a class="dropdown-item d-flex align-items-center" href="?page=exit"><i
                      class="bi bi-escape fs-4 me-2"></i>خروج</a>
                </div>
              </div>
            </div>
            <!-- App header actions end -->

          </div>
          <!-- App header ends -->

          <!-- App hero header starts -->
          <div class="app-hero-header d-flex align-items-start">

            <!-- Breadcrumb start -->
            <ol class="breadcrumb d-none d-lg-flex">
              <li class="breadcrumb-item">
                <i class="bi bi-house lh-1"></i>
                <a href="index.html" class="text-decoration-none">خانه</a>
              </li>
              <li class="breadcrumb-item" aria-current="page"><?php echo $page; ?></li>
            </ol>
            <!-- Breadcrumb end -->

            <!-- Filter start -->
            <div class="ms-auto d-flex flex-row gap-1 day-filters">
              امروز : <?php echo $tarikh; ?>
              <!-- Debug: Current page = <?php echo $page; ?> -->
            </div>
            <!-- Filter end -->

          </div>
          <!-- App Hero header ends -->

          <!-- App body starts -->

          <div class="alert bg-danger alert-dismissible fade show" role="alert">
            توجه : کلیه تیکت هایی که از آخرین پاسخ تیم رابین 30 روز گذشته باشد و پاسخی دیگر ثبت نشده باشد بصورت خودکار بسته خواهد شد
          </div>

          <div class="app-body">
            <!-- Row start -->
            <div class="row gx-3">
              <div class="col-xxl-12">
                <div class="card mb-3">
                  <div class="card-body">

                    <?php include('uiindex.php'); ?>


                  </div>
                </div>
              </div>
            </div>
            <!-- Row end -->

          </div>
          <!-- App body ends -->

          <!-- App footer start -->
          <div class="app-footer">
            <span>طراحی و اجرا : www.okteam.ir</span>
          </div>
          <!-- App footer end -->

        </div>
        <!-- App container ends -->

      </div>
      <!-- Main container end -->

    </div>
    <!-- Page wrapper end -->

    <!-- *************
			************ JavaScript Files *************
		************* -->
    <!-- Required jQuery first, then Bootstrap Bundle JS -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- *************
			************ Vendor Js Files *************
		************* -->

    <!-- Overlay Scroll JS -->
    <script src="assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
    <script src="assets/vendor/overlay-scroll/custom-scrollbar.js"></script>

    <!-- Custom JS files -->
    <script src="assets/js/custom.js"></script>

    <!-- Active page highlighting enhancement -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Add active class to current page menu item
        const currentPage = '<?php echo $page; ?>';
        const menuItems = document.querySelectorAll('.sidebar-menu li');

        menuItems.forEach(function(item) {
          const link = item.querySelector('a');
          if (link) {
            const href = link.getAttribute('href');
            if (href === 'index.php' && (currentPage === '' || currentPage === '0' || currentPage === 'null')) {
              item.classList.add('current-page');
            } else if (href && href.includes('page=' + currentPage)) {
              item.classList.add('current-page');
            }
          }
        });
      });
    </script>
  </body>

  </html>


<?php
}
mysqli_close($Link);
?>