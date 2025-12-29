<?php

include('inf/f1.php');
if (!isset($_SESSION['ok_login_user_i'])) {
    $_SESSION['ok_login_user_i'] = "n";
}
if ($_SESSION['ok_login_user_i'] != 'y') {
    include('login.php');
} else {

    //----------------------------------------------------------------------
    $code_p_run = $_SESSION['code_p'];
    $name_karbar_run = $_SESSION['name'];
    $semat_karbar_run = $_SESSION['semat'];
    $avatar = $_SESSION['avatar'];


    $mojavez = "";
    $mojavez = "login*" . time();
    $shomare = 1;
    include('uxindex.php');

    $_SESSION['mojavez'] = $mojavez;

    //----------------------------------------------------------

    $dastrasi_all_proje = "y";

    //----------------------------------------------------------
    $special_view_codes = ["24277", "25662", "20612", "23056", "22695", "20072", "1100105", "1100056", "1064046037"];

?>



    <!DOCTYPE html>
    <html lang="en" dir="rtl">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>سامانه تیکت فراهم - <?php echo $name_karbar_run; ?> </title>

        <!-- Meta -->
        <meta name="theme-color" content="var(--color-bg-primary, #1c1f21)">
        <link rel="shortcut icon" href="assets/images/logo.png" />

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
                    } catch (e) {
                        // localStorage not available (private browsing, etc.)
                    }

                    // Detect system preference
                    var systemPreference = 'dark'; // default
                    if (window.matchMedia) {
                        try {
                            systemPreference = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
                        } catch (e) {
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
                } catch (e) {
                    // If anything fails, default to dark theme
                    document.documentElement.classList.add('theme-dark');
                    document.documentElement.setAttribute('data-theme', 'dark');
                }
            })();
        </script>

        <!-- PERFORMANCE: DNS prefetch and preconnect for faster resource loading -->
        <link rel="dns-prefetch" href="//fonts.googleapis.com">
        <link rel="preconnect" href="//fonts.googleapis.com" crossorigin>

        <?php if ($page == "wait_page") { ?>
            <meta http-equiv="refresh" content="45;url=/index.php?page=wait_page">
        <?php } ?>

        <!-- PERFORMANCE: Preload critical CSS -->
        <link rel="preload" href="assets/css/main.min.css" as="style">
        <link rel="preload" href="assets/fonts/bootstrap/bootstrap-icons.min.css" as="style">

        <!-- *************
			************ CSS Files *************
		************* -->
        <link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.min.css" />
        <link rel="stylesheet" href="assets/css/main.min.css" />
        <link rel="stylesheet" href="assets/css/colors.css" />
        <link rel="stylesheet" href="assets/css/theme.css" />



        <!-- *************
			************ Vendor Css Files *************
		************ -->

        <!-- Scrollbar CSS -->
        <link rel="stylesheet" href="assets/vendor/overlay-scroll/OverlayScrollbars.min.css" />

        <!-- Custom CSS for exit button styling -->
        <style>
            .sidebar-menu li.text-danger a {
                color: var(--color-danger, #dc3545) !important;
                font-weight: 600;
            }

            .sidebar-menu li.text-danger a:hover {
                color: var(--color-danger, #dc3545) !important;
                background-color: var(--color-badge-danger-bg, rgba(220, 53, 69, 0.1)) !important;
                border-radius: 8px;
                margin: 2px 0;
            }

            .sidebar-menu li.text-danger a i {
                color: var(--color-danger, #dc3545) !important;
            }

            .sidebar-menu li.text-danger a:hover i {
                color: var(--color-danger, #dc3545) !important;
            }

            /* ClockPicker Custom Styles */
            .clockpicker-popover {
                z-index: 9999 !important;
                position: fixed !important;
            }

            /* Make the popover match the dark theme */
            .clockpicker-popover.popover {
                background-color: var(--color-bg-card, var(--bs-card-bg, #303641)) !important;
                border: 1px solid var(--color-border-primary, var(--bs-border-color, #50596a)) !important;
                color: var(--color-text-primary, var(--bs-body-color, #95a0b1)) !important;
            }

            .clockpicker-popover .popover-header,
            .clockpicker-popover .popover-title {
                background-color: var(--color-bg-tertiary, var(--bs-tertiary-bg, #20252e)) !important;
                color: var(--color-text-primary, var(--bs-body-color, #95a0b1)) !important;
                border-bottom: 1px solid var(--color-border-primary, var(--bs-border-color, #50596a)) !important;
            }

            .clockpicker-popover .popover-body,
            .clockpicker-popover .popover-content {
                background-color: var(--color-bg-card, var(--bs-card-bg, #303641)) !important;
                color: var(--color-text-primary, var(--bs-body-color, #95a0b1)) !important;
            }

            .popover {
                z-index: 9999 !important;
            }

            .clockpicker-plate {
                background-color: var(--color-bg-card, var(--bs-card-bg, #303641)) !important;
                border: 1px solid var(--color-border-primary, var(--bs-border-color, #50596a)) !important;
            }

            .clockpicker-canvas line {
                stroke: var(--color-primary, var(--bs-primary, #3c92b1)) !important;
            }

            .clockpicker-canvas-bg {
                fill: var(--color-bg-tertiary, var(--bs-tertiary-bg, #20252e)) !important;
            }

            .clockpicker-canvas-bearing {
                fill: var(--color-primary, var(--bs-primary, #3c92b1)) !important;
            }

            .clockpicker-canvas-fg {
                fill: var(--color-primary, var(--bs-primary, #3c92b1)) !important;
            }

            .clockpicker-tick {
                color: var(--color-text-primary, var(--bs-body-color, #95a0b1)) !important;
            }

            .clockpicker-tick.active,
            .clockpicker-tick:hover {
                background-color: var(--color-primary, var(--bs-primary, #3c92b1)) !important;
                color: var(--color-white, #ffffff) !important;
            }

            .clockpicker-button {
                background-color: var(--color-primary, var(--bs-primary, #3c92b1)) !important;
                color: var(--color-white, #ffffff) !important;
                border: none !important;
            }

            .clockpicker-button:hover {
                background-color: var(--color-primary-dark, #2d7a93) !important;
            }

            /* Fix hour/minute display order for RTL:
       - Disable RTL just inside the clockpicker popover (keep default LTR layout)
       - So hours stay on the left and minutes on the right, like the original plugin */
            .clockpicker-popover,
            .clockpicker-popover * {
                direction: ltr !important;
                text-align: center !important;
                unicode-bidi: bidi-override;
                /* Force a Latin-digit font here so selected numbers stay Latin */
                font-family: "Segoe UI", Tahoma, Arial, sans-serif !important;
            }

            .clockpicker-display {
                background-color: var(--color-bg-tertiary, var(--bs-tertiary-bg, #20252e)) !important;
                color: var(--color-text-primary, var(--bs-body-color, #95a0b1)) !important;
            }

            .clockpicker-span-hours.text-primary,
            .clockpicker-span-minutes.text-primary {
                color: var(--color-primary, var(--bs-primary, #3c92b1)) !important;
            }

            /* Theme Toggle Button Styles */
            #theme-toggle-btn {
                color: var(--color-text-primary, var(--bs-body-color));
                transition: all 0.3s ease;
                border: none;
                background: transparent;
                padding: 0.5rem;
            }

            #theme-toggle-btn:hover {
                color: var(--color-primary, var(--bs-primary));
                transform: rotate(15deg);
                background-color: var(--color-bg-hover, rgba(255, 255, 255, 0.05));
                border-radius: 8px;
            }

            #theme-toggle-btn:active {
                transform: rotate(30deg) scale(0.95);
            }

            #theme-toggle-btn .bi {
                transition: all 0.3s ease;
            }

            .theme-light #theme-toggle-btn {
                color: var(--color-text-primary, #212529);
            }

            .theme-light #theme-toggle-btn:hover {
                color: var(--color-primary, #0d6efd);
                background-color: var(--color-bg-hover, rgba(0, 0, 0, 0.04));
            }
        </style>

        <!-- ClockPicker CSS -->
        <link rel="stylesheet" href="assets/clockpicker/bootstrap-clockpicker.min.css" />
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
                        <img src="assets/images/<?php echo $avatar; ?>.png" class="img-3x me-3 rounded-3"
                            alt="Admin Dashboard" />
                        <div class="m-0 text-nowrap">
                            <p class="m-0"><?php echo $semat_karbar_run; ?></p>
                            <h6 class="m-0"><?php echo $name_karbar_run; ?> </h6>
                        </div>
                    </div>
                    <!-- Sidebar profile ends -->

                    <!-- Sidebar menu starts -->
                    <div class="sidebarMenuScroll">
                        <ul class="sidebar-menu">
                            <li
                                class="<?php echo ($page == '' || $page == '0' || $page == 'null') ? 'current-page' : ''; ?>">
                                <a href="index.php">
                                    <i class=" bi bi-pie-chart"></i>
                                    <span class="menu-text">داشبورد</span>
                                </a>
                            </li>
                            <li class="<?php echo ($page == 'list_ticket') ? 'current-page' : ''; ?>">
                                <a href="?page=list_ticket">
                                    <i class="bi bi-bar-chart-line"></i>
                                    <span class="menu-text">لیست تیکت ها</span>
                                </a>
                            </li>
                            <li class="<?php echo ($page == 'my_work') ? 'current-page' : ''; ?>">
                                <a href="?page=my_work">
                                    <i class="bi bi-box"></i>
                                    <span class="menu-text">کارکرد</span>
                                </a>
                            </li>
                            <li class="<?php echo ($page == 'history_ticket') ? 'current-page' : ''; ?>">
                                <a href="?page=history_ticket">
                                    <i class="bi bi-calendar2"></i>
                                    <span class="menu-text">تاریخچه تیکت</span>
                                </a>
                            </li>

                            <li class="<?php echo ($page == 'wait_page') ? 'current-page' : ''; ?>">
                                <a href="?page=wait_page">
                                    <i class="bi bi-check-circle"></i>
                                    <span class="menu-text">صفحه انتظار</span>
                                </a>
                            </li>

                            <li class="<?php echo ($page == 'list_pasokh_no') ? 'current-page' : ''; ?>">
                                <a href="?page=list_pasokh_no">
                                    <i class="bi bi-chat-dots"></i>
                                    <span class="menu-text">پاسخ ها</span>
                                </a>
                            </li>

                            <li
                                class="<?php echo ($page == 'priority' || $page == 'set_priority' || $page == 'view_priority') ? 'current-page' : ''; ?>">
                                <a href="?page=priority">
                                    <i class="bi bi-sort-numeric-down"></i>
                                    <span class="menu-text">اولویت تیکت ها</span>
                                </a>
                            </li>

                            <?php if (in_array($_SESSION['code_p'], $special_view_codes, true) || $_SESSION['code_p'] === "1002") {
                            ?>
                                <li class="<?php echo ($page == 'list_working_on') ? 'current-page' : ''; ?>">
                                    <a href="?page=list_working_on">
                                        <i class="bi bi-people"></i>
                                        <span class="menu-text">تیکت های روی میز</span>
                                    </a>
                                </li>
                            <?php } ?>

                            <li class="<?php echo ($page == 'gozareshat') ? 'current-page' : ''; ?>">
                                <a href="?page=gozareshat">
                                    <i class="bi bi-graph-up"></i>
                                    <span class="menu-text">گزارشات</span>
                                </a>
                            </li>

                            <li class="<?php echo ($page == 'setting') ? 'current-page' : ''; ?>">
                                <a href="?page=setting">
                                    <i class="bi bi-gear"></i>
                                    <span class="menu-text"> تنظیمات</span>
                                </a>
                            </li>

                            <li class="<?php echo ($page == 'list_mohtava') ? 'current-page' : ''; ?>">
                                <a href="?page=list_mohtava">
                                    <i class="bi bi-gear"></i>
                                    <span class="menu-text">محتوای آموزشی</span>
                                </a>
                            </li>

                            <li class="<?php echo ($page == 'profile') ? 'current-page' : ''; ?>">
                                <a href="?page=profile">
                                    <i class="bi bi-person-square"></i>
                                    <span class="menu-text"> پروفایل</span>
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


                            <a class="dropdown-toggle d-flex p-3 position-relative" href="?page=start_ticket" role="button">
                                <span class="bi bi-plus fs-4"></span>
                            </a>

                            <!-- Fullscreen Toggle Button -->
                            <span class="dropdown">
                                <button type="button" class="btn" id="fullscreen-toggle-btn" title="تمام صفحه">
                                    <span class="bi bi-fullscreen fs-5" id="fullscreen-icon-enter"></span>
                                    <span class="bi bi-fullscreen-exit fs-5" id="fullscreen-icon-exit" style="display: none;"></span>
                                </button>
                            </span>

                            <!-- Theme Toggle Button -->
                            <span class="dropdown">
                                <button type="button" class="btn" id="theme-toggle-btn" title="تغییر تم">
                                    <span class="bi bi-sun-fill fs-5" id="theme-icon-light" style="display: none;"></span>
                                    <span class="bi bi-moon-fill fs-5" id="theme-icon-dark"></span>
                                </button>
                            </span>
                            <?php
                            // PERFORMANCE OPTIMIZED: Reduced from multiple nested queries to 2-3 queries total
                            $code_p_run_notif = $_SESSION['code_p'];
                            $code_p_run_notif_escaped = mysqli_real_escape_string($Link, $code_p_run_notif);
                            $monhh_notif = mysqli_real_escape_string($Link, "مسئول پاسخگویی به");

                            $unread_notifications = [];
                            $unread_count = 0;

                            // OPTIMIZED: Single query to get all tickets with unread messages and their counts
                            // Uses UNION to combine creator and assigned user tickets
                            $query_all_tickets = "
                                SELECT 
                                    t.code, t.titr, t.vaziat, t.olaviat, t.name_daste,
                                    COUNT(p.i_pasokh) as unread_count,
                                    MAX(CONCAT(p.tarikh_sabt, ' ', p.saat_sabt)) as latest_msg_time,
                                    'creator' as user_role
                                FROM ticket t
                                INNER JOIN pasokh p ON t.code = p.code_ticket
                                WHERE t.code_p_karbar = '$code_p_run_notif_escaped'
                                AND p.oksee = 'n'
                                AND p.matn NOT LIKE '%$monhh_notif%'
                                GROUP BY t.code, t.titr, t.vaziat, t.olaviat, t.name_daste
                                
                                UNION ALL
                                
                                SELECT 
                                    t.code, t.titr, t.vaziat, t.olaviat, t.name_daste,
                                    COUNT(p.i_pasokh) as unread_count,
                                    MAX(CONCAT(p.tarikh_sabt, ' ', p.saat_sabt)) as latest_msg_time,
                                    'assigned' as user_role
                                FROM ticket t
                                INNER JOIN pasokh p ON t.code = p.code_ticket
                                WHERE t.code_p_karbar_anjam = '$code_p_run_notif_escaped'
                                AND t.code_p_karbar != '$code_p_run_notif_escaped'
                                AND p.oksee = 'n'
                                AND p.matn NOT LIKE '%$monhh_notif%'
                                AND (p.code_karbar2 = '$code_p_run_notif_escaped' OR p.code_karbar2 IS NULL OR p.code_karbar2 = '')
                                GROUP BY t.code, t.titr, t.vaziat, t.olaviat, t.name_daste
                                
                                ORDER BY latest_msg_time DESC
                                LIMIT 20";

                            $ticket_codes = [];
                            if ($result_tickets = mysqli_query($Link, $query_all_tickets)) {
                                while ($ticket_row = mysqli_fetch_assoc($result_tickets)) {
                                    // Skip duplicates (creator takes priority)
                                    if (isset($unread_notifications[$ticket_row['code']])) {
                                        continue;
                                    }

                                    $ticket_codes[] = "'" . mysqli_real_escape_string($Link, $ticket_row['code']) . "'";
                                    $unread_notifications[$ticket_row['code']] = [
                                        'ticket_code' => $ticket_row['code'],
                                        'ticket_title' => $ticket_row['titr'],
                                        'messages' => [], // Will be filled below
                                        'count' => (int) $ticket_row['unread_count'],
                                        'sort_timestamp' => $ticket_row['latest_msg_time'],
                                        'vaziat' => $ticket_row['vaziat'] ?? '',
                                        'olaviat' => $ticket_row['olaviat'] ?? '',
                                        'name_daste' => $ticket_row['name_daste'] ?? '',
                                        'user_role' => $ticket_row['user_role']
                                    ];
                                    $unread_count += (int) $ticket_row['unread_count'];
                                }
                            }

                            // OPTIMIZED: Single query to get latest 3 messages for all tickets at once
                            if (!empty($ticket_codes)) {
                                $ticket_codes_str = implode(',', $ticket_codes);

                                // Get all unread messages for these tickets, then filter in PHP
                                $query_messages = "SELECT p.code_ticket, p.i_pasokh, p.matn, p.name_karbar_sabt, p.tarikh_sabt, p.saat_sabt
                                    FROM pasokh p
                                    WHERE p.code_ticket IN ($ticket_codes_str)
                                    AND p.oksee = 'n'
                                    AND p.matn NOT LIKE '%$monhh_notif%'
                                    ORDER BY p.code_ticket, p.i_pasokh DESC";

                                if ($result_messages = mysqli_query($Link, $query_messages)) {
                                    $messages_by_ticket = [];
                                    while ($msg_row = mysqli_fetch_assoc($result_messages)) {
                                        $ticket_code = $msg_row['code_ticket'];
                                        if (!isset($messages_by_ticket[$ticket_code])) {
                                            $messages_by_ticket[$ticket_code] = [];
                                        }
                                        // Only keep first 3 messages per ticket
                                        if (count($messages_by_ticket[$ticket_code]) < 3) {
                                            $messages_by_ticket[$ticket_code][] = $msg_row;
                                        }
                                    }

                                    // Assign messages to notifications
                                    foreach ($unread_notifications as $code => &$notification) {
                                        if (isset($messages_by_ticket[$code])) {
                                            $notification['messages'] = $messages_by_ticket[$code];
                                        }
                                    }
                                    unset($notification); // Break reference
                                }
                            }

                            // Sort notifications by date (most recent first) - already sorted by query but ensure order
                            uasort($unread_notifications, function ($a, $b) {
                                $timestamp_a = str_replace(['/', '-', '_'], '', $a['sort_timestamp']);
                                $timestamp_b = str_replace(['/', '-', '_'], '', $b['sort_timestamp']);
                                return strcmp($timestamp_b, $timestamp_a);
                            });
                            ?>

                            <style>
                                .notification-item:hover {
                                    background-color: var(--bs-tertiary-bg) !important;
                                }

                                .badge-soft-danger {
                                    background: var(--color-badge-danger-bg, rgba(220, 53, 69, 0.1));
                                    color: var(--color-badge-danger-text, #dc3545);
                                    border: 1px solid var(--color-badge-danger-text, rgba(220, 53, 69, 0.2));
                                }

                                .badge-soft-info {
                                    background: var(--color-badge-info-bg, rgba(13, 202, 240, 0.1));
                                    color: var(--color-badge-info-text, #0dcaf0);
                                    border: 1px solid var(--color-badge-info-text, rgba(13, 202, 240, 0.2));
                                }

                                .badge-soft-primary {
                                    background: var(--color-badge-primary-bg, rgba(13, 110, 253, 0.1));
                                    color: var(--color-badge-primary-text, #0d6efd);
                                    border: 1px solid var(--color-badge-primary-text, rgba(13, 110, 253, 0.2));
                                }

                                .badge-soft-success {
                                    background: var(--color-badge-success-bg, rgba(25, 135, 84, 0.1));
                                    color: var(--color-badge-success-text, #198754);
                                    border: 1px solid var(--color-badge-success-text, rgba(25, 135, 84, 0.2));
                                }

                                .badge-soft-warning {
                                    background: var(--color-badge-warning-bg, rgba(255, 193, 7, 0.1));
                                    color: var(--color-badge-warning-text, #ffc107);
                                    border: 1px solid var(--color-badge-warning-text, rgba(255, 193, 7, 0.2));
                                }

                                .badge-soft-secondary {
                                    background: var(--color-badge-secondary-bg, rgba(108, 117, 125, 0.1));
                                    color: var(--color-badge-secondary-text, #6c757d);
                                    border: 1px solid var(--color-badge-secondary-text, rgba(108, 117, 125, 0.2));
                                }

                                .notification-item .status-pill {
                                    padding: 4px 10px;
                                    border-radius: 20px;
                                    font-size: 0.7rem;
                                    font-weight: 600;
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 4px;
                                }

                                .dropdown-menu.dropdown-menu-lg-end {
                                    overflow-x: hidden !important;
                                }

                                .notification-item {
                                    word-wrap: break-word;
                                    overflow-wrap: break-word;
                                }

                                /* Custom scrollbar for notification dropdown */
                                .dropdown-menu.dropdown-menu-lg-end::-webkit-scrollbar {
                                    width: 8px;
                                }

                                .dropdown-menu.dropdown-menu-lg-end::-webkit-scrollbar-track {
                                    background: var(--bs-tertiary-bg, #20252e);
                                    border-radius: 10px;
                                }

                                .dropdown-menu.dropdown-menu-lg-end::-webkit-scrollbar-thumb {
                                    background: var(--bs-border-color, #50596a);
                                    border-radius: 10px;
                                    border: 2px solid var(--bs-tertiary-bg, #20252e);
                                }

                                .dropdown-menu.dropdown-menu-lg-end::-webkit-scrollbar-thumb:hover {
                                    background: var(--bs-primary, #3c92b1);
                                }

                                /* Firefox scrollbar */
                                .dropdown-menu.dropdown-menu-lg-end {
                                    scrollbar-width: thin;
                                    scrollbar-color: var(--bs-border-color, #50596a) var(--bs-tertiary-bg, #20252e);
                                }
                            </style>

                            <!-- Notification Bell -->
                            <div class="dropdown ms-2">
                                <a class="dropdown-toggle d-flex p-3 position-relative text-decoration-none" href="#!"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false" id="notificationBell">
                                    <i class="bi bi-bell fs-4"></i>
                                    <?php if ($unread_count > 0) { ?>
                                        <span class="position-absolute translate-middle badge rounded-pill bg-danger"
                                            style="font-size: 0.65rem; min-width: 18px; height: 18px; line-height: 18px; padding: 0 5px; left: 40%;">
                                            <?php echo $unread_count > 99 ? '99+' : $unread_count; ?>
                                            <span class="visually-hidden">unread notifications</span>
                                        </span>
                                    <?php } ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-start dropdown-menu-lg-end"
                                    style="width: 380px; max-width: 90vw; max-height: 500px; overflow-y: auto; overflow-x: hidden;">
                                    <div class="dropdown-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="bi bi-bell me-2"></i>اعلان‌ها
                                        </h6>
                                        <?php if ($unread_count > 0) { ?>
                                            <span class="badge bg-danger rounded-pill"><?php echo $unread_count; ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="dropdown-divider"></div>

                                    <?php if (empty($unread_notifications)) { ?>
                                        <div class="dropdown-item-text text-center py-4 text-muted">
                                            <i class="bi bi-check-circle fs-1 d-block mb-2 opacity-50"></i>
                                            <p class="mb-0">هیچ پیام خوانده نشده‌ای وجود ندارد</p>
                                        </div>
                                    <?php } else { ?>
                                        <?php foreach ($unread_notifications as $ticket_code => $notification) {
                                            $ticket_url = "?page=info_ticket&code=" . $notification['ticket_code'];
                                            $first_message = $notification['messages'][0];
                                            $message_preview = mb_substr(strip_tags($first_message['matn']), 0, 80, 'UTF-8');
                                            if (mb_strlen($first_message['matn'], 'UTF-8') > 80) {
                                                $message_preview .= '...';
                                            }

                                            // Get status and priority info
                                            $vaziat = $notification['vaziat'] ?? '';
                                            $olaviat = $notification['olaviat'] ?? '';

                                            // Status labels and colors
                                            $status_info = [
                                                'a' => ['text' => 'ثبت اولیه', 'class' => 'badge-soft-danger', 'icon' => 'bi-file-earmark-plus'],
                                                'm' => ['text' => 'درحال بررسی', 'class' => 'badge-soft-info', 'icon' => 'bi-hourglass-split'],
                                                'w' => ['text' => 'روی میز', 'class' => 'badge-soft-primary', 'icon' => 'bi-briefcase'],
                                                'b' => ['text' => 'بسته شده', 'class' => 'badge-soft-success', 'icon' => 'bi-check-circle'],
                                                'k' => ['text' => 'انجام شد', 'class' => 'badge-soft-success', 'icon' => 'bi-check2-all'],
                                                't' => ['text' => 'بررسی مجدد', 'class' => 'badge-soft-warning', 'icon' => 'bi-arrow-repeat'],
                                                'c' => ['text' => 'کنسل شده', 'class' => 'badge-soft-secondary', 'icon' => 'bi-x-circle']
                                            ];
                                            $status = $status_info[$vaziat] ?? ['text' => 'نامشخص', 'class' => 'badge-soft-secondary', 'icon' => 'bi-question-circle'];

                                            // Priority labels and colors
                                            $priority_info = [
                                                '1' => ['text' => 'ضروری', 'class' => 'bg-danger text-white', 'icon' => 'bi-exclamation-triangle-fill'],
                                                '2' => ['text' => 'متوسط', 'class' => 'bg-warning text-dark', 'icon' => 'bi-exclamation-circle'],
                                                '3' => ['text' => 'معمولی', 'class' => 'bg-info text-white', 'icon' => 'bi-info-circle'],
                                                '4' => ['text' => 'پایین', 'class' => 'bg-secondary text-white', 'icon' => 'bi-arrow-down-circle']
                                            ];
                                            $priority = $priority_info[$olaviat] ?? ['text' => '', 'class' => '', 'icon' => ''];
                                        ?>
                                            <a href="<?php echo $ticket_url; ?>" class="dropdown-item notification-item px-3 py-3"
                                                style="border-bottom: 1px solid var(--bs-border-color); transition: background-color 0.2s; width: 100%; box-sizing: border-box;">
                                                <div class="d-flex w-100 justify-content-between align-items-start"
                                                    style="gap: 8px; max-width: 100%;">
                                                    <div class="flex-grow-1" style="min-width: 0; max-width: 100%;">
                                                        <!-- Ticket Title with Priority Badge -->
                                                        <div class="d-flex align-items-center mb-2 flex-wrap" style="gap: 6px;">
                                                            <h6 class="mb-0 fw-semibold"
                                                                style="font-size: 0.85rem; color: var(--bs-body-color); flex: 1 1 auto; min-width: 0; max-width: 100%; overflow: hidden;">
                                                                <span class="text-truncate d-block" style="max-width: 100%;"
                                                                    title="<?php echo htmlspecialchars($notification['ticket_title']); ?>">
                                                                    <?php echo htmlspecialchars($notification['ticket_title']); ?>
                                                                </span>
                                                            </h6>
                                                            <?php if (!empty($priority['text'])) { ?>
                                                                <span
                                                                    class="badge <?php echo $priority['class']; ?> rounded-pill flex-shrink-0"
                                                                    style="font-size: 0.6rem; padding: 2px 6px; white-space: nowrap;">
                                                                    <i class="<?php echo $priority['icon']; ?>"
                                                                        style="font-size: 0.65rem;"></i>
                                                                    <?php echo $priority['text']; ?>
                                                                </span>
                                                            <?php } ?>
                                                            <?php if ($notification['count'] > 1) { ?>
                                                                <span class="badge bg-danger rounded-pill flex-shrink-0"
                                                                    style="font-size: 0.6rem; padding: 2px 6px; white-space: nowrap;">
                                                                    <i class="bi-envelope-fill" style="font-size: 0.65rem;"></i>
                                                                    <?php echo $notification['count']; ?>
                                                                </span>
                                                            <?php } ?>
                                                        </div>

                                                        <!-- Status Badge -->
                                                        <div class="mb-2 d-flex align-items-center flex-wrap" style="gap: 6px;">
                                                            <span class="status-pill <?php echo $status['class']; ?> flex-shrink-0"
                                                                style="font-size: 0.65rem; padding: 3px 8px; white-space: nowrap;">
                                                                <i class="<?php echo $status['icon']; ?>"
                                                                    style="font-size: 0.7rem;"></i>
                                                                <?php echo $status['text']; ?>
                                                            </span>
                                                            <?php if (!empty($notification['name_daste'])) { ?>
                                                                <span class="badge bg-light text-dark border flex-shrink-0"
                                                                    style="font-size: 0.6rem; padding: 3px 6px; white-space: nowrap; max-width: 150px; overflow: hidden; text-overflow: ellipsis;">
                                                                    <i class="bi-folder me-1" style="font-size: 0.65rem;"></i>
                                                                    <span class="text-truncate d-inline-block" style="max-width: 120px;"
                                                                        title="<?php echo htmlspecialchars($notification['name_daste']); ?>">
                                                                        <?php echo htmlspecialchars($notification['name_daste']); ?>
                                                                    </span>
                                                                </span>
                                                            <?php } ?>
                                                        </div>

                                                        <!-- Message Preview -->
                                                        <p class="mb-2 text-muted"
                                                            style="font-size: 0.75rem; line-height: 1.4; color: var(--bs-secondary-color) !important; word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;">
                                                            <i class="bi-chat-left-text me-1 text-primary"></i>
                                                            <span
                                                                style="word-break: break-word;"><?php echo htmlspecialchars($message_preview); ?></span>
                                                        </p>

                                                        <!-- Footer Info -->
                                                        <div class="d-flex align-items-center flex-wrap"
                                                            style="font-size: 0.7rem; gap: 6px; max-width: 100%;">
                                                            <span class="text-muted d-flex align-items-center flex-shrink-0"
                                                                style="max-width: 45%; overflow: hidden;">
                                                                <i class="bi-person-fill me-1 text-info"></i>
                                                                <strong class="text-truncate" style="max-width: 100px;"
                                                                    title="<?php echo htmlspecialchars($first_message['name_karbar_sabt']); ?>">
                                                                    <?php echo htmlspecialchars($first_message['name_karbar_sabt']); ?>
                                                                </strong>
                                                            </span>
                                                            <span class="text-muted flex-shrink-0">•</span>
                                                            <span class="text-muted d-flex align-items-center flex-shrink-0">
                                                                <i class="bi-clock me-1 text-success"></i>
                                                                <span
                                                                    style="white-space: nowrap;"><?php echo $first_message['tarikh_sabt'] . ' ' . $first_message['saat_sabt']; ?></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php } ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-center text-primary fw-bold" href="?page=list_ticket">
                                            <i class="bi bi-list-ul me-2"></i>مشاهده همه تیکت‌ها
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="dropdown ms-2">
                                <a id="userSettings"
                                    class="dropdown-toggle d-flex py-2 align-items-center text-decoration-none" href="#!"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="icon-box md bg-info text-white rounded-5">

                                        <i class="fs-3 bi bi-person-fill"></i>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-start">
                                    <a class="dropdown-item d-flex align-items-center" href="?page=profile"><i
                                            class="bi bi-person fs-4 me-2"></i> پروفایل</a>
                                    <a class="dropdown-item d-flex align-items-center" href="?page=setting"><i
                                            class="bi bi-gear fs-4 me-2"></i> تنظیمات</a>

                                    <a class="dropdown-item d-flex align-items-center" href="?page=gozareshat"><i
                                            class="bi bi-gear fs-4 me-2"></i>گزارشات</a>


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
                    </div>
                    <!-- App Hero header ends -->

                    <!-- App body starts -->
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
        <!-- PERFORMANCE: jQuery must load first (no defer), others can defer -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js" defer></script>

        <!-- *************
			************ Vendor Js Files *************
		************* -->
        <!-- ClockPicker JS - defer since it's not needed immediately -->
        <script src="assets/clockpicker/bootstrap-clockpicker.min.js" defer></script>
        <!-- Overlay Scroll JS - defer for faster initial load -->
        <script src="assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js" defer></script>
        <script src="assets/vendor/overlay-scroll/custom-scrollbar.js" defer></script>

        <!-- Custom JS files - defer for faster initial page render -->
        <!-- Note: theme-switcher.js runs after DOM ready to handle dynamic changes -->
        <script src="assets/js/theme-switcher.js" defer></script>
        <script src="assets/js/custom.js" defer></script>
        <script src="assets/js/uijindex.js" defer></script>
        <script src="assets/js/uxjindex.js" defer></script>



        <script>
            // Fullscreen utility functions
            function requestFullscreen(element) {
                if (element.requestFullscreen) {
                    return element.requestFullscreen();
                } else if (element.webkitRequestFullscreen) {
                    return element.webkitRequestFullscreen();
                } else if (element.mozRequestFullScreen) {
                    return element.mozRequestFullScreen();
                } else if (element.msRequestFullscreen) {
                    return element.msRequestFullscreen();
                } else {
                    console.log('Fullscreen API is not supported.');
                    return Promise.reject('Fullscreen not supported');
                }
            }

            function exitFullscreen() {
                if (document.exitFullscreen) {
                    return document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    return document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    return document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    return document.msExitFullscreen();
                } else {
                    console.log('Fullscreen API is not supported.');
                    return Promise.reject('Fullscreen not supported');
                }
            }

            function isFullscreen() {
                return !!(document.fullscreenElement ||
                    document.webkitFullscreenElement ||
                    document.mozFullScreenElement ||
                    document.msFullscreenElement);
            }

            function updateFullscreenIcon() {
                var iconEnter = document.getElementById('fullscreen-icon-enter');
                var iconExit = document.getElementById('fullscreen-icon-exit');

                if (isFullscreen()) {
                    iconEnter.style.display = 'none';
                    iconExit.style.display = 'inline-block';
                } else {
                    iconEnter.style.display = 'inline-block';
                    iconExit.style.display = 'none';
                }
            }

            // Fullscreen Toggle Button Handler
            document.addEventListener('DOMContentLoaded', function() {
                var fullscreenToggleBtn = document.getElementById('fullscreen-toggle-btn');
                var iconEnter = document.getElementById('fullscreen-icon-enter');
                var iconExit = document.getElementById('fullscreen-icon-exit');

                if (fullscreenToggleBtn) {
                    // Initial icon state
                    updateFullscreenIcon();

                    // Toggle fullscreen on button click
                    fullscreenToggleBtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        if (isFullscreen()) {
                            exitFullscreen().then(function() {
                                updateFullscreenIcon();
                            }).catch(function(err) {
                                console.error('Error exiting fullscreen:', err);
                            });
                        } else {
                            requestFullscreen(document.documentElement).then(function() {
                                updateFullscreenIcon();
                            }).catch(function(err) {
                                console.error('Error entering fullscreen:', err);
                            });
                        }
                    });

                    // Listen for fullscreen change events (user might exit via ESC key)
                    document.addEventListener('fullscreenchange', updateFullscreenIcon);
                    document.addEventListener('webkitfullscreenchange', updateFullscreenIcon);
                    document.addEventListener('mozfullscreenchange', updateFullscreenIcon);
                    document.addEventListener('MSFullscreenChange', updateFullscreenIcon);
                }
            });

            // Theme Toggle Button Handler
            document.addEventListener('DOMContentLoaded', function() {
                var themeToggleBtn = document.getElementById('theme-toggle-btn');
                var themeIconLight = document.getElementById('theme-icon-light');
                var themeIconDark = document.getElementById('theme-icon-dark');

                if (themeToggleBtn) {
                    // Update icon on page load
                    function updateThemeIcon() {
                        var currentTheme = ThemeSwitcher ? ThemeSwitcher.getTheme() : 'dark';
                        if (currentTheme === 'light') {
                            themeIconLight.style.display = 'inline-block';
                            themeIconDark.style.display = 'none';
                        } else {
                            themeIconLight.style.display = 'none';
                            themeIconDark.style.display = 'inline-block';
                        }
                    }

                    // Initial icon update (wait for ThemeSwitcher to initialize)
                    setTimeout(updateThemeIcon, 200);

                    // Toggle theme on button click
                    themeToggleBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (ThemeSwitcher) {
                            var newTheme = ThemeSwitcher.toggle();
                            updateThemeIcon();
                        }
                    });

                    // Listen for theme changes (in case theme is changed elsewhere)
                    document.addEventListener('themeChanged', function(e) {
                        updateThemeIcon();
                    });
                }
            });
        </script>




    </body>

    </html>


<?php
}
mysqli_close($Link);
?>