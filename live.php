<?php
date_default_timezone_set("Asia/Tehran");
include('inf/f1.php');

// List of allowed responder codes
$allowed_responders = ['1064046037', '1100085', '1100084', '1100113', '1100056', '1100074'];
$responders_list = "'" . implode("','", array_map(function($r) use ($Link) {
    return mysqli_real_escape_string($Link, $r);
}, $allowed_responders)) . "'";

// Fetch tickets: 
// 1) All 'ثبت اولیه' (status 'a') tickets - no filters on department or responder
// 2) new_software department tickets with specific responders (excluding status 'a')
// Sort: 'ثبت اولیه' (status 'a') first, then by newest
$Query_list = "SELECT * FROM ticket 
               WHERE vaziat = 'a'
                  OR (daste = 'new_software' 
                      AND vaziat != 'a'
                      AND code_p_karbar_anjam IN ($responders_list))
               ORDER BY CASE WHEN vaziat = 'a' THEN 0 ELSE 1 END, i_ticket DESC 
               LIMIT 100";

$tickets = [];
if ($Result_list = mysqli_query($Link, $Query_list)) {
    while ($q_list = mysqli_fetch_array($Result_list)) {
        $tickets[] = $q_list;
    }
}

// Helper function to convert numbers to Persian digits
function to_persian_digits($str) {
    $persian_digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english_digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($english_digits, $persian_digits, $str);
}

// Helper function to convert Persian date string (Ymd) to timestamp
function persian_date_to_timestamp($date_str) {
    if (empty($date_str) || strlen($date_str) != 8) {
        return time();
    }
    $year = (int)substr($date_str, 0, 4);
    $month = (int)substr($date_str, 4, 2);
    $day = (int)substr($date_str, 6, 2);
    
    // Convert Persian date to Gregorian (jalali_to_gregorian is available from inf/jdf.php via f1.php)
    if (function_exists('jalali_to_gregorian')) {
        list($gy, $gm, $gd) = jalali_to_gregorian($year, $month, $day);
        // Create timestamp
        return mktime(0, 0, 0, $gm, $gd, $gy);
    }
    return time();
}

// Status labels with DaisyUI colors
$status_labels = [
    'a' => ['label' => 'ثبت اولیه', 'badge' => 'badge-soft badge-error'],
    'm' => ['label' => 'درحال بررسی', 'badge' => 'badge-soft badge-info'],
    'w' => ['label' => 'روی میز', 'badge' => 'badge-soft badge-primary'],
    'b' => ['label' => 'بسته شده', 'badge' => 'badge-soft badge-success'],
    'k' => ['label' => 'انجام شد', 'badge' => 'badge-soft badge-success'],
    't' => ['label' => 'بررسی مجدد', 'badge' => 'badge-soft badge-warning'],
    'c' => ['label' => 'کنسل شده', 'badge' => 'badge-soft badge-neutral']
];

// Priority labels with DaisyUI colors
$priority_labels = [
    '1' => ['label' => 'ضروری', 'badge' => 'badge-soft badge-error badge-sm'],
    '2' => ['label' => 'متوسط', 'badge' => 'badge-soft badge-warning badge-sm'],
    '3' => ['label' => 'معمولی', 'badge' => 'badge-soft badge-info badge-sm'],
    '4' => ['label' => 'پایین', 'badge' => 'badge-soft badge-neutral badge-sm']
];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl" data-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>داشبورد زنده - نرم‌افزار جدید | سامانه تیکت فراهم</title>
    <meta name="description" content="داشبورد زنده تیکت‌های نرم‌افزار جدید" />
    <meta name="author" content="Rahbarian Group" />
    <meta name="theme-color" content="#1c1f21">
    <link rel="shortcut icon" href="assets/images/logo.png" />
    <meta http-equiv="refresh" content="30;url=/live.php">

    <!-- Tailwind CSS & DaisyUI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.min.css" />

    <style>
        @font-face {
            font-family: 'Vazirmatn';
            src: url('/assets/Vazirmatn-VariableFont_wght.ttf') format('truetype');
            font-weight: 100 900;
            font-style: normal;
            font-display: swap;
        }
        
        html {
            font-size: 16px; /* Reduced by 50% from 32px */
        }
        
        * {
            font-family: 'Vazirmatn', sans-serif;
        }
        
        /* Optimize for 4K TV display (3840x2160) - scaled down 50% */
        body {
            padding: 1rem;
            zoom: 0.5; /* Reduce everything by 50% */
            transform-origin: top right;
        }
        
        .container {
            max-width: 100%;
        }
        
        /* Larger cards for TV viewing */
        .ticket-card {
            min-height: 400px;
        }
        
        /* Ensure badges are readable on TV */
        .badge {
            padding: 0.75rem 1.25rem;
            font-size: 1.125em;
        }
        
        /* Larger footer text */
        footer, .footer {
            font-size: 1.25em;
            padding: 1.5rem;
        }
    </style>
</head>
<body class="bg-base-200 min-h-screen p-2">
    <div class="container mx-auto max-w-[1920px]">
        <!-- Header - Optimized for 4K TV -->
        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body p-8">
                <div class="flex items-center justify-between gap-8">
                    <!-- Date Widget - Left -->
                    <div class="flex-shrink-0">
                        <?php 
                        $weekday = jdate('l');
                        $day = jdate('d');
                        $month_year = jdate('M Y');
                        ?>
                        <div class="flex flex-col items-center justify-center gap-6 mt-0.5">
                            <span class="text-2xl !leading-none text-content drop-shadow-md mb-6"><?php echo htmlspecialchars($weekday); ?></span>
                            <div class="text-9xl font-bold leading-[1] h-[0.3em] flex items-center text-content drop-shadow-md"><?php echo to_persian_digits(htmlspecialchars($day)); ?></div>
                            <div class="flex flex-col">
                                <span class="text-3xl font-medium transition-all duration-200 text-content drop-shadow-md"><?php echo to_persian_digits(htmlspecialchars($month_year)); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Live Clock - Middle -->
                    <div class="flex-1 flex justify-center items-center">
                        <div class="text-center">
                            <div class="text-9xl font-bold font-mono text-primary drop-shadow-md" id="clockDisplay">--:--:--</div>
                        </div>
                    </div>
                    
                    <!-- Ticket Count - Right -->
                    <div class="flex-shrink-0">
                        <div class="text-center">
                            <div class="text-xl text-base-content/70 mb-2">تعداد تیکت‌ها</div>
                            <div class="text-5xl font-bold text-primary"><?php echo to_persian_digits(count($tickets)); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Cards Grid - Optimized for 4K TV -->
        <?php if (count($tickets) > 0): ?>
            <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 2xl:grid-cols-8 gap-6">
                <?php foreach ($tickets as $ticket): 
                    $status = $status_labels[$ticket['vaziat']] ?? $status_labels['a'];
                    $priority = $priority_labels[$ticket['olaviat']] ?? $priority_labels['3'];
                    
                    // Format date using jdate
                    $date_timestamp = persian_date_to_timestamp($ticket['tarikh_sabt']);
                    $formatted_date = jdate('l - d M Y', $date_timestamp);
                    
                    // Check if this is a 'ثبت اولیه' ticket
                    $is_initial = ($ticket['vaziat'] == 'a');
                ?>
                    <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow ticket-card">
                        <div class="card-body p-6">
                            <!-- Badges Row -->
                            <div class="flex flex-wrap gap-3 mb-4">
                                <span class="badge <?php echo $status['badge']; ?> text-lg">
                                    <?php echo $status['label']; ?>
                                </span>
                                <span class="badge <?php echo $priority['badge']; ?> text-lg">
                                    <?php echo $priority['label']; ?>
                                </span>
                            </div>
                            
                            <!-- Title -->
                            <h2 class="card-title text-2xl line-clamp-2 mb-4 min-h-[4rem]">
                                <?php echo htmlspecialchars($ticket['titr']); ?>
                            </h2>
                            
                            <!-- Code -->
                            <div class="text-xl font-mono text-base-content/70 mb-4">
                                <?php echo htmlspecialchars($ticket['code']); ?>
                            </div>
                            
                            <!-- Info with Badges -->
                            <div class="flex flex-col gap-3">
                                <?php if ($is_initial): ?>
                                    <span class="badge badge-ghost text-lg">
                                        دپارتمان: <?php echo htmlspecialchars($ticket['name_daste'] ?? $ticket['daste'] ?? '---'); ?>
                                    </span>
                                <?php endif; ?>
                                <span class="badge badge-ghost text-lg">
                                    درخواست دهنده: <?php echo htmlspecialchars($ticket['name_karbar']); ?>
                                </span>
                                <span class="badge badge-ghost text-lg">
                                    🏢 <?php echo htmlspecialchars($ticket['name_sherkat'] ?? '---'); ?>
                                </span>
                                <?php if (!$is_initial): ?>
                                    <span class="badge badge-ghost text-lg">
                                        پشتیبان جاری: <?php echo htmlspecialchars($ticket['name_karbar_anjam'] ?? '---'); ?>
                                    </span>
                                <?php endif; ?>
                                <span class="badge badge-ghost text-lg">
                                    📅 <?php echo to_persian_digits($formatted_date); ?> - <?php echo to_persian_digits(htmlspecialchars($ticket['saat_sabt'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body items-center text-center py-12">
                    <div class="text-6xl mb-4">📭</div>
                    <h2 class="card-title text-2xl">تیکتی یافت نشد</h2>
                    <p class="text-base-content/60">در حال حاضر هیچ تیکتی با این فیلترها وجود ندارد.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Footer - Optimized for 4K TV -->
        <div class="text-center text-xl text-base-content/70 mt-6 py-4">
            <p>گروه صنعتی رهباریان | سامانه تیکت پشتیبانی | به‌روزرسانی خودکار هر 30 ثانیه</p>
        </div>
    </div>

    <script>
        // Convert English digits to Persian digits
        function toPersianDigits(str) {
            const persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            const englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            return str.replace(/[0-9]/g, function(w) {
                return persianDigits[englishDigits.indexOf(w)];
            });
        }

        // Update clock every second
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const displayTime = `${hours}:${minutes}:${seconds}`;
            const clockEl = document.getElementById('clockDisplay');
            if (clockEl) {
                clockEl.textContent = toPersianDigits(displayTime);
            }
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
