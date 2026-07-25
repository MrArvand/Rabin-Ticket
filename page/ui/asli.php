 <?php
// Get current user code
$code_p_run = isset($_SESSION['code_p']) ? $_SESSION['code_p'] : '';
$code_p_run_escaped = mysqli_real_escape_string($Link, $code_p_run);

// Get today's date
$tarikh_emroz = $tarikh; // Using global $tarikh variable

// Statistics queries
// 1. Total tickets
$t_kol = 0;
$Query_total = "SELECT COUNT(*) as total FROM ticket";
if ($Result_total = mysqli_query($Link, $Query_total)) {
    $row_total = mysqli_fetch_array($Result_total);
    $t_kol = (int)$row_total['total'];
}

// 2. Unassigned tickets (waiting for distribution)
$t_kol_m = 0;
$Query_unassigned = "SELECT COUNT(*) as total FROM ticket WHERE (code_p_karbar_anjam = '' OR code_p_karbar_anjam IS NULL OR code_p_karbar_anjam = '0') AND vaziat != 'c'";
if ($Result_unassigned = mysqli_query($Link, $Query_unassigned)) {
    $row_unassigned = mysqli_fetch_array($Result_unassigned);
    $t_kol_m = (int)$row_unassigned['total'];
}

// 3. Tickets assigned to current user (in progress)
$t_kol_ej = 0;
if (!empty($code_p_run)) {
    $Query_my_tickets = "SELECT COUNT(*) as total FROM ticket WHERE code_p_karbar_anjam = '$code_p_run_escaped' AND vaziat IN ('m', 'w')";
    if ($Result_my_tickets = mysqli_query($Link, $Query_my_tickets)) {
        $row_my_tickets = mysqli_fetch_array($Result_my_tickets);
        $t_kol_ej = (int)$row_my_tickets['total'];
    }
}

// 4. Unread messages for current user
$t_unread = 0;
if (!empty($code_p_run)) {
     $Query_unread = "SELECT COUNT(DISTINCT p.code_ticket) as total 
                     FROM pasokh p 
                     INNER JOIN ticket t ON p.code_ticket = t.code 
                     WHERE p.oksee = 'n' 
                     AND (t.code_p_karbar = '$code_p_run_escaped' OR t.code_p_karbar_anjam = '$code_p_run_escaped')
                     AND (
                         (p.kind IN ('referral', 'dept_ref') AND p.code_karbar_sabt = '$code_p_run_escaped')
                         OR (
                             (p.kind IS NULL OR p.kind = '' OR p.kind NOT IN ('referral', 'dept_ref'))
                             AND (p.code_karbar_sabt IS NULL OR p.code_karbar_sabt = '' OR p.code_karbar_sabt != '$code_p_run_escaped')
                             AND p.code_karbar2 = '$code_p_run_escaped'
                         )
                     )";
    if ($Result_unread = mysqli_query($Link, $Query_unread)) {
        $row_unread = mysqli_fetch_array($Result_unread);
        $t_unread = (int)$row_unread['total'];
    }
}

// 5. Today's tickets
$t_today = 0;
$Query_today = "SELECT COUNT(*) as total FROM ticket WHERE tarikh_sabt = '$tarikh_emroz'";
if ($Result_today = mysqli_query($Link, $Query_today)) {
    $row_today = mysqli_fetch_array($Result_today);
    $t_today = (int)$row_today['total'];
}

// 6. High priority tickets (urgent)
$t_urgent = 0;
$Query_urgent = "SELECT COUNT(*) as total FROM ticket WHERE olaviat = '1' AND vaziat NOT IN ('b', 'c', 'k')";
if ($Result_urgent = mysqli_query($Link, $Query_urgent)) {
    $row_urgent = mysqli_fetch_array($Result_urgent);
    $t_urgent = (int)$row_urgent['total'];
}

// 7. Completed tickets this month
$t_kol_anjam = 0;
$Query_completed = "SELECT COUNT(*) as total FROM ticket WHERE vaziat = 'k' AND tarikh_anjam LIKE '" . substr($tarikh_emroz, 0, 7) . "%'";
if ($Result_completed = mysqli_query($Link, $Query_completed)) {
    $row_completed = mysqli_fetch_array($Result_completed);
    $t_kol_anjam = (int)$row_completed['total'];
}

// 8. Active departments
$t_departments = 0;
$Query_dep = "SELECT COUNT(*) as total FROM departman WHERE vaziat = 'y'";
if ($Result_dep = mysqli_query($Link, $Query_dep)) {
    $row_dep = mysqli_fetch_array($Result_dep);
    $t_departments = (int)$row_dep['total'];
}
	 ?>
  <!-- Row start -->
  <div class="row gx-3">
              <div class="col-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <!-- Row start -->
                    <div class="row g-4">
                      <!-- Total Tickets -->
                      <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">
                            <i class="bi bi-ticket-perforated text-primary me-1"></i>تعداد کل تیکت
                          </p>
                          <h3 class="my-2 text-primary"><?php echo number_format($t_kol); ?></h3>
                          <p class="m-0 small text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            تمام تیکت‌های ثبت شده
                          </p>
                        </div>
                      </div>

                      <!-- Unassigned Tickets -->
                      <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">
                            <i class="bi bi-hourglass-split text-warning me-1"></i>منتظر توزیع
                          </p>
                          <h3 class="my-2 text-warning"><?php echo number_format($t_kol_m); ?></h3>
                          <p class="m-0 small text-muted">
                            <?php if ($t_kol > 0): ?>
                            <span class="text-warning me-1">
                              <i class="bi bi-percent"></i>
                              <?php echo number_format(($t_kol_m / $t_kol) * 100, 1); ?>%
                            </span>
                            <?php endif; ?>
                            از کل تیکت‌ها
                          </p>
                        </div>
                      </div>

                      <!-- My Active Tickets -->
                      <?php if (!empty($code_p_run)): ?>
                      <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">
                            <i class="bi bi-briefcase text-info me-1"></i>تیکت‌های من
                          </p>
                          <h3 class="my-2 text-info"><?php echo number_format($t_kol_ej); ?></h3>
                          <p class="m-0 small text-muted">
                            <i class="bi bi-arrow-right-circle me-1"></i>
                            در حال بررسی
                          </p>
                        </div>
                      </div>
                      <?php endif; ?>

                      <!-- Unread Messages -->
                      <?php if (!empty($code_p_run)): ?>
                      <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">
                            <i class="bi bi-envelope-exclamation text-danger me-1"></i>پیام‌های خوانده نشده
                          </p>
                          <h3 class="my-2 text-danger"><?php echo number_format($t_unread); ?></h3>
                          <p class="m-0 small text-muted">
                            <i class="bi bi-bell me-1"></i>
                            نیاز به بررسی
                          </p>
                        </div>
                      </div>
                      <?php endif; ?>

                      <!-- Today's Tickets -->
                      <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">
                            <i class="bi bi-calendar-day text-success me-1"></i>تیکت‌های امروز
                          </p>
                          <h3 class="my-2 text-success"><?php echo number_format($t_today); ?></h3>
                          <p class="m-0 small text-muted">
                            <i class="bi bi-clock me-1"></i>
                            ثبت شده در <?php echo $tarikh_emroz; ?>
                          </p>
                        </div>
                      </div>

                      <!-- Urgent Tickets -->
                      <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">
                            <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i>تیکت‌های ضروری
                          </p>
                          <h3 class="my-2 text-danger"><?php echo number_format($t_urgent); ?></h3>
                          <p class="m-0 small text-muted">
                            <i class="bi bi-flag-fill me-1"></i>
                            اولویت بالا
                          </p>
                        </div>
                      </div>

                      <!-- Completed This Month -->
                      <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">
                            <i class="bi bi-check-circle-fill text-success me-1"></i>انجام شده این ماه
                          </p>
                          <h3 class="my-2 text-success"><?php echo number_format($t_kol_anjam); ?></h3>
                          <p class="m-0 small text-muted">
                            <i class="bi bi-calendar-month me-1"></i>
                            ماه جاری
                          </p>
                        </div>
                      </div>

                      <!-- Active Departments -->
                      <div class="px-0 col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">
                            <i class="bi bi-building text-secondary me-1"></i>دپارتمان‌های فعال
                          </p>
                          <h3 class="my-2 text-secondary"><?php echo number_format($t_departments); ?></h3>
                          <p class="m-0 small text-muted">
                            <i class="bi bi-diagram-3 me-1"></i>
                            واحدهای فعال
                          </p>
                        </div>
                      </div>



                    <!-- Row end -->
                  </div>
                </div>
              </div>
            </div>
            <!-- Row end -->

    <!-- Ticket Ordering Information Section -->
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title" style="color: var(--color-text-primary, var(--bs-body-color));">
          <i class="bi bi-info-circle me-2" style="color: var(--color-info, var(--bs-info));"></i>ترتیب نمایش تیکت‌ها
        </h5>
      </div>
      <div class="card-body">
        <div class="alert mb-0" style="background: var(--color-badge-info-bg, rgba(111, 180, 206, 0.2)); color: var(--color-text-primary, var(--bs-body-color)); border: 1px solid var(--color-border-primary, var(--bs-border-color));">
          <p class="mb-2"><strong style="color: var(--color-badge-info-text, var(--color-info, var(--bs-info)));">نحوه مرتب‌سازی و نمایش تیکت‌ها:</strong></p>
          <ul class="mb-0 pe-3" style="color: var(--color-text-primary, var(--bs-body-color));">
            <li class="mb-2">
              در ابتدای لیست تیکت‌هایی را مشاهده می‌کنید که پاسخ‌دهنده آنها شما هستید و بعد از لیست فوق در صورت داشتن دسترسی تیکت‌های ثبت اولیه و سایر تیکت‌ها بر اساس تاریخ ثبت تیکت و تاریخ آخرین پاسخ درج شده در تیکت نمایش داده خواهند شد.
            </li>
            <li class="mb-0">
              پیام‌های خوانده نشده ابتدا در بالاترین جایگاه قرار خواهند گرفت و پس از خوانده شدن به جایگاه خود بر اساس تاریخ و زمان منتقل خواهند شد.
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- End Ticket Ordering Information Section -->

    <!-- Changelog Section -->
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title">
          <i class="bi bi-journal-text me-2"></i>تغییرات اخیر سامانه
        </h5>
      </div>
      <div class="card-body">
        <div class="changelog-list">
          
          <div class="mb-3">
            <h6 class="mb-2">
              <span class="badge bg-primary me-2">نسخه 1.0.1</span>
              <span class="text-muted small">دی ۱۴۰۴</span>
            </h6>
            <ul class="list-unstyled mb-0 pe-3">
              <li class="mb-2">
                <i class="bi bi-plus-circle-fill text-info me-2"></i>
                افزودن پاسخ های پیش فرض در صفحه مکالمه
              </li>
              <li class="mb-2">
                <i class="bi bi-bug-fill text-warning me-2"></i>
                رفع باگ اسکرول پیامها در صفحه مکالمه
              </li>
              <li class="mb-2">
                <i class="bi bi-bug-fill text-warning me-2"></i>
                رفع مشکل ناخوانا بودن نام ارسال کننده پیام و تاریخ ثبت پیام در صفحه مکالمه
              </li>
                <li class="mb-2">
                <i class="bi bi-bug-fill text-warning me-2"></i>
                رفع مشکل نمایش نام دپارتمان در صفحه تیکت  و لیست تیکت ها
              </li>
            </ul>
          </div>

          <div class="mb-3">
            <h6 class="mb-2">
              <span class="badge bg-secondary me-2">نسخه 1.0.0</span>
              <span class="text-muted small">دی ۱۴۰۴</span>
            </h6>
            <ul class="list-unstyled mb-0 pe-3">
              <li class="mb-2">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                بهبود ترتیب نمایش تیکتها (نمایش تیکتهای جدیدتر با پاسخهای خوانده نشده در ابتدای لیست)
              </li>
              <li class="mb-2">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                افزودن سیستم خوانده نشده / خوانده شده برای پیام ها
              </li>
              <li class="mb-2">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                فعالسازی اعلان پیامکی
              </li>
              <li class="mb-2">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                تنظیم کاربر پذیرش پیش فرض برای هر دپارتمان و ارجاع اولیه مستقیم تیکت های هر دپارتمان به کاربر پیش فرض
              </li>
              <li class="mb-2">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                اضافه شدن تم رنگی روشن (آزمایشی)
              </li>
              <li class="mb-0">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                رفع خطاها و ایرادات گزارش شده
              </li>
            </ul>
          </div>
          
        </div>
      </div>
    </div>
    <!-- End Changelog Section -->
               