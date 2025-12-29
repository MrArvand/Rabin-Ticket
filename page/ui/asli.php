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

    <div class="card mb-3">
                  <div class="card-header">
                    <h5 class="card-title">لیست 20 پاسخ آخر</h5>
                  </div>
                  <div class="card-body">
                    <div class="">
                      <div class="my-2">

                      <?php
                      $monhh="مسئول پاسخگویی به";
                      //
								$shomare=0;
								
									// Select columns explicitly to avoid ambiguity with 'code' column in both tables
									$Query_pasokh="SELECT pasokh.*, pasokh.code_ticket, ticket.code_p_karbar_anjam  
FROM  ticket
INNER JOIN  pasokh
ON pasokh.code_ticket = ticket.code

where ( ticket.code_p_karbar_anjam = '$code_p_run'  AND  pasokh.matn NOT like '%$monhh%'   )ORDER BY pasokh.i_pasokh DESC LIMIT 200";
								
								
								
						//		$Query_pasokh="SELECT*from pasokh where ((code_karbar2 = '$code_p_run' || code_karbar_sabt = '$code_p_run' )  AND  matn NOT like '%$monhh%'   )ORDER BY i_pasokh DESC LIMIT 200";
   if($Result_pasokh=mysqli_query($Link,$Query_pasokh)){
 while($q_ticket2=mysqli_fetch_array($Result_pasokh)){
	 $shomare++;
	 // Use code_ticket instead of ambiguous 'code' column
	 $code_pasokh=$q_ticket2['code_ticket'];

   ?>

                        <div class="activity-block d-flex position-relative">
                          <img src="assets/images/user3.png" class="img-4x me-3 rounded-circle activity-user"
                            alt="Admin Dashboard" />
                          <div class="mb-3">
                            <h5>
                            <?php if($q_ticket2['oksee']=="y"){ ?>  
                            <i title="<?php echo $q_ticket2['tarikh_see']; ?> - <?php echo $q_ticket2['saat_see']; ?> " class="fs-3 bi bi-eye"></i><?php }else{ ?>
                            <i class="fs-3 bi bi-eye-slash"></i>
                            <?php } 
                            echo $q_ticket2['name_karbar_sabt']; ?> -  <?php echo $q_ticket2['name_karbar2']; ?></h5>
                            <p><?php echo nl2br($q_ticket2['matn']); ?></p>
                            

                            <span class="badge bg-primary"><?php echo $q_ticket2['tarikh_sabt']; ?> - <?php echo $q_ticket2['saat_sabt']; ?> </span>
                            <a href="?page=info_ticket&code=<?php  echo $q_ticket2['code_ticket']; ?>"><span class="btn btn-success">بررسی</span> </a>
                          
                          </div>  
                        </div><hr>
<?php }} 
?>
                      </div>
                    </div>
                  </div>
                </div>
               