<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>سامانه تیکت فراهم - انتخاب ورود</title>
    <meta name="theme-color" content="#1c1f21">
    <meta name="description" content="سامانه تیکت پشتیبانی فراهم" />
    <link rel="shortcut icon" href="assets/images/logo.png" />

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="assets/css/main.min.css" />

    <style>
        :root {
            --portal-bg: #1c1f21;
            --portal-card-bg: #2a2e32;
            --portal-card-hover: #363b40;
            --portal-text: #e9ecef;
            --portal-text-muted: #adb5bd;
            --portal-primary: #3c92b1;
            --portal-success: #198754;
            --portal-border: #50596a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--portal-bg) 0%, #2d3436 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'IranYekan', 'IranYekanNum', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .portal-container {
            width: 100%;
            max-width: 900px;
        }

        .portal-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .portal-logo {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .portal-title {
            color: var(--portal-text);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .portal-subtitle {
            color: var(--portal-text-muted);
            font-size: 1rem;
        }

        .portal-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .portal-card {
            background: var(--portal-card-bg);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            text-decoration: none;
            color: var(--portal-text);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .portal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--portal-primary), var(--portal-success));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .portal-card:hover {
            background: var(--portal-card-hover);
            border-color: var(--portal-primary);
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            color: var(--portal-text);
            text-decoration: none;
        }

        .portal-card:hover::before {
            opacity: 1;
        }

        .portal-card-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.5rem;
            transition: transform 0.3s ease;
        }

        .portal-card:hover .portal-card-icon {
            transform: scale(1.1);
        }

        .portal-card.support .portal-card-icon {
            background: linear-gradient(135deg, #3c92b1, #2d7a93);
            color: white;
        }

        .portal-card.ticket .portal-card-icon {
            background: linear-gradient(135deg, #198754, #157347);
            color: white;
        }

        .portal-card-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--portal-text);
        }

        .portal-card-description {
            color: var(--portal-text-muted);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .portal-card-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .portal-card.support .portal-card-btn {
            background: linear-gradient(135deg, #3c92b1, #2d7a93);
            color: white;
        }

        .portal-card.ticket .portal-card-btn {
            background: linear-gradient(135deg, #198754, #157347);
            color: white;
        }

        .portal-card:hover .portal-card-btn {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        .portal-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .portal-divider-text {
            background: var(--portal-bg);
            color: var(--portal-text-muted);
            padding: 10px 20px;
            font-size: 0.9rem;
            border-radius: 50px;
            border: 1px solid var(--portal-border);
        }

        .portal-footer {
            text-align: center;
            margin-top: 50px;
            color: var(--portal-text-muted);
            font-size: 0.85rem;
        }

        .portal-footer a {
            color: var(--portal-primary);
            text-decoration: none;
        }

        .portal-footer a:hover {
            text-decoration: underline;
        }

        /* Features list */
        .portal-features {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
            text-align: right;
        }

        .portal-features li {
            padding: 8px 0;
            color: var(--portal-text-muted);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .portal-features li i {
            color: var(--portal-primary);
            font-size: 1rem;
        }

        .portal-card.ticket .portal-features li i {
            color: #198754;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .portal-title {
                font-size: 1.5rem;
            }

            .portal-card {
                padding: 30px 20px;
            }

            .portal-card-title {
                font-size: 1.2rem;
            }

            .portal-cards {
                gap: 20px;
            }
        }

        /* Animation */
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

        .portal-header {
            animation: fadeInUp 0.6s ease;
        }

        .portal-card:first-child {
            animation: fadeInUp 0.6s ease 0.2s both;
        }

        .portal-card:last-child {
            animation: fadeInUp 0.6s ease 0.4s both;
        }

        .portal-footer {
            animation: fadeInUp 0.6s ease 0.6s both;
        }
    </style>
</head>

<body>
    <div class="portal-container">
        <!-- Header -->
        <div class="portal-header">
            <img src="assets/images/logo.png" alt="فراهم" class="portal-logo">
            <h1 class="portal-title">سامانه تیکت پشتیبانی فراهم</h1>
            <p class="portal-subtitle">لطفاً نوع دسترسی خود را انتخاب کنید</p>
        </div>

        <!-- Cards -->
        <div class="portal-cards">
            <!-- Support Portal -->
            <a href="support.php" class="portal-card support">
                <div class="portal-card-icon">
                    <i class="bi bi-headset"></i>
                </div>
                <h2 class="portal-card-title">ورود به سامانه پشتیبان</h2>
                <p class="portal-card-description">
                    کاربران پاسخگوی تیکت
                </p>
                <ul class="portal-features">
                    <li><i class="bi bi-check-circle-fill"></i> مدیریت و پاسخگویی به تیکت‌ها</li>
                    <li><i class="bi bi-check-circle-fill"></i> مشاهده گزارشات و آمار</li>
                    <li><i class="bi bi-check-circle-fill"></i> تنظیمات و مدیریت سیستم</li>
                </ul>
                <span class="portal-card-btn">
                    <i class="bi bi-box-arrow-in-left"></i>
                    ورود کارشناسان
                </span>
            </a>

            <!-- Ticket Portal -->
            <a href="ticket/index.php" class="portal-card ticket">
                <div class="portal-card-icon">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <h2 class="portal-card-title">ورود به سامانه اصلی</h2>
                <p class="portal-card-description">
                    ثبت تیکت جدید
                </p>
                <ul class="portal-features">
                    <li><i class="bi bi-check-circle-fill"></i> ثبت درخواست و تیکت جدید</li>
                    <li><i class="bi bi-check-circle-fill"></i> پیگیری وضعیت تیکت‌ها</li>
                    <li><i class="bi bi-check-circle-fill"></i> مشاهده پاسخ کارشناسان</li>
                </ul>
                <span class="portal-card-btn">
                    <i class="bi bi-box-arrow-in-left"></i>
                    ورود کاربران
                </span>
            </a>
        </div>

        <!-- Footer -->
        <div class="portal-footer">
            <p>طراحی و توسعه توسط <a href="https://rahbariangroup.com" target="_blank">رابین سامانه پارس</a></p>
            <p style="margin-top: 8px; font-size: 0.8rem;">نسخه 1.0.0 - دی ۱۴۰۴</p>
        </div>
    </div>
</body>

</html>
