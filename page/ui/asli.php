 <!-- Row start -->
<strong class="text-center  text-warning">
    توجه : 
      از ابتدای مرداد ماه پر کردن گزارش کار روزانه در سامانه تیکت برای تمام پرسنل گروه های فناوری اطلاعات  شامل هوش تجاری - جریانکار - پشتیبانی - برنامه نویسی - سخت افزار و شبکه الزامی می باشد
</strong>
 <?php
$t_kol=0;
$t_kol_m=0;
$t_kol_ok=0;
$t_kol_c=0;
$t_kol_ej=0;
$t_kol_t=0;
$t_kol_anjam=0;

$Query_list="SELECT*from ticket where (1)ORDER BY i_ticket DESC LIMIT 10000";
if($Result_list=mysqli_query($Link,$Query_list)){
while($q_list=mysqli_fetch_array($Result_list)){
	
  $t_kol++;

if($q_list['vaziat']=="a")$t_kol_m++;
if($q_list['vaziat']=="c")$t_kol_c++;
if($q_list['vaziat']=="m")$t_kol_ej++;
if($q_list['vaziat']=="t")$t_kol_t++;
if($q_list['vaziat']=="b")$t_kol_ok++;
if($q_list['vaziat']=="k")$t_kol_anjam++;


 }}
	 ?>
  <!-- Row start -->
  <div class="row gx-3">
              <div class="col-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <!-- Row start -->
                    <div class="row g-4">
                      <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">تعداد کل تیکت</p>
                          <h3 class="my-2"><?php echo $t_kol; ?></h3>
                          <p class="m-0 small">
                            <span class="text-danger me-1">
                            <i class="bi bi-star"></i>
                              <?php echo round( $t_kol/11,2); ?>%</span>
                          میانگین تیم
                           </p>
                        </div>
                      </div>
                      <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">منتظر توزیع</p>
                          <h3 class="my-2"><?php echo $t_kol_m; ?></h3>
                          <p class="m-0 small">
                            <span class="text-success me-1">
                              <i class="bi bi-star"></i>
                              <?php echo round( $t_kol_m/$t_kol,2); ?>%</span>
                           میانگین از کل
                           </p>
                        </div>
                      </div>
                      <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">
                      در حال اجرا
                          </p>
                          <h3 class="my-2"><?php echo $t_kol_ej; ?></h3>
                          <p class="m-0 small">
                            <span class="text-success me-1">
                              <i class="bi bi-star"></i>
                              <?php echo round( $t_kol_ej/$t_kol,2); ?>%</span>
                           میانگین از کل
                           </p>
                        </div>
                      </div>

                   <div class="px-0 border-end col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">کنسل شده</p>
                          <h3 class="my-2"><?php echo $t_kol_c; ?></h3>
                          <p class="m-0 small">
                            <span class="text-success me-1">
                              <i class="bi bi-star"></i>
                              <?php echo round( $t_kol_c/$t_kol,2); ?>%</span>
                           میانگین از کل
                           </p>
                        </div>
                      </div>

                      <div class="px-0 border-end  col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">بررسی مجدد</p>
                          <h3 class="my-2"><?php echo $t_kol_t; ?></h3>
                          <p class="m-0 small">
                            <span class="text-success me-1">
                              <i class="bi bi-star"></i>
                              <?php echo round( $t_kol_t/$t_kol,2); ?>%</span>
                           میانگین از کل
                           </p>
                        </div>
                   
                     </div>


                      <div class="px-0 border-end   col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">انجام شده</p>
                          <h3 class="my-2"><?php echo $t_kol_anjam; ?></h3>
                          <p class="m-0 small">
                            <span class="text-success me-1">
                              <i class="bi bi-star"></i>
                              <?php echo round( $t_kol_anjam/$t_kol,2); ?>%</span>
                           میانگین از کل
                           </p>
                        </div>
                      </div>
                      
                      

                      <div class="px-0 border-end   col-xl-3 col-sm-6">
                        <div class="text-center">
                          <p class="m-0 small">بسته شده</p>
                          <h3 class="my-2"><?php echo $t_kol_ok; ?></h3>
                          <p class="m-0 small">
                            <span class="text-success me-1">
                              <i class="bi bi-star"></i>
                              <?php echo round( $t_kol_ok/$t_kol,2); ?>%</span>
                           میانگین از کل
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
              <span class="badge bg-primary me-2">نسخه 1.0.0</span>
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
               