
              <?php
							$ok_hast="n";
								$Query_list="SELECT*from gharardad where ( code = '$code' )ORDER BY i_gharardad DESC LIMIT 1";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_gar=mysqli_fetch_array($Result_list)){
  $ok_hast="y";
  $titr = $q_gar['titr'];
  $name_user_sabt = $q_gar['name_user_sabt'];
  $code_user_sabt =$q_gar['code_user_sabt'];
  $sn_gharardad = $q_gar['sn_gharardad'];
  $tarikh_sabt = $q_gar['tarikh_sabt'];
  $sherkat = $q_gar['sherkat'];
  $kind_gharardad = $q_gar['kind'];
  $name_kind_gharardad = $q_gar['name_kind'];
  $vaziat_gharardad = $q_gar['vaziat'];
  $matn_log =$q_gar['matn_log'];
  $code_p_taid = $q_gar['code_taid'];
  $code_p_nazer = $q_gar['code_nazer'];
  $name_taraf1 = $q_gar['name_taraf1'];
  $name_taraf2 = $q_gar['name_taraf2'];
  $mablagh = $q_gar['mablagh'];

	 ?>
                <h5 class="">پنل قرار داد : <?php echo $q_gar['titr']; ?></h5>

<?php 
if($vaziat_gharardad=="a"){$rang_v="primary"; $name_v="در حال تکمیل"; $next_v="مرحله بعد تایید پیش نویس توسط ناظر";}
if($vaziat_gharardad=="na"){$rang_v="info"; $name_v="منتظر تایید ناظر"; $next_v="مرحله بعد امضاء قرارداد  ";}

if($vaziat_gharardad=="em"){$rang_v="seccendry"; $name_v="منتظر امضا قرار داد "; $next_v=" مرحله بعد تایید نهایی ";}
if($vaziat_gharardad=="y"){$rang_v="success"; $name_v="تایید نهایی "; $next_v=" مرحله بعد پایان قرارداد";}
if($vaziat_gharardad=="p"){$rang_v="success"; $name_v=" پایان قرارداد "; $next_v=" مرحله بعد پایان قرارداد می باشد  ";}
if($vaziat_gharardad=="v"){$rang_v="warning"; $name_v=" ویرایش قرار داد "; $next_v=" مرحله بعد تایید ناظر قرارداد";}
if($vaziat_gharardad=="c"){$rang_v="dangers"; $name_v="کنسل کردن قرارد اد "; $next_v="مرحله بعد بایگانی قرار داد ";}
if($vaziat_gharardad=="t"){$rang_v="warning"; $name_v=" تمدید قرارداد "; $next_v=" مرحله بعد پایان قرار داد  ";}
if($vaziat_gharardad=="f"){$rang_v="warning"; $name_v=" افزودن بند و تبصره و الحاقیه  "; $next_v=" مرحله بعد تایید ناظر قرارداد ";}








?>
                <div
                        class="d-flex bg-label-<?php echo $rang_v; ?> p-2 border rounded my-3"
                      >
                        <div
                          class="border border-2 border-<?php echo $rang_v; ?> rounded me-3 p-2 d-flex align-items-center justify-content-center w-px-40 h-px-40"
                        >
                          <i class="mdi mdi-star-outline mdi-24px"></i>
                        </div>
                        <div
                          class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2"
                        >
                          <div class="me-2">
                            <h6 class="mb-0 fw-semibold"><?php echo $name_v; ?></h6>
                            <span    class="small"><?php echo $next_v; ?></span>
                          </div>

                        </div>
                      </div>

















<div class="d-flex flex-wrap" id="icons-container">

<a href="?page=panel_g_info&code=<?php echo $code; ?>" >
<div class="card icon-card cursor-pointer text-center mb-4 mx-2  bg-secondary">
<div class="card-body"> <i class="mdi mdi-folder-information-outline mdi-36px"></i>
<p class="icon-name text-capitalize text-truncate mb-0 mt-2" >اطلاعات</p>
</div>
</div>
 </a>


 <a href="?page=panel_g_matn&code=<?php echo $code; ?>" >
<div class="card icon-card cursor-pointer text-center mb-4 mx-2  bg-secondary">
<div class="card-body"> <i class="mdi mdi-folder-text-outline mdi-36px"></i>
<p class="icon-name text-capitalize text-truncate mb-0 mt-2" >متن قرارداد</p>
</div>
</div>
 </a>

 <a href="?page=panel_g_data&code=<?php echo $code; ?>" >
<div class="card icon-card cursor-pointer text-center mb-4 mx-2  bg-secondary">
<div class="card-body"> <i class="mdi mdi-variable mdi-36px"></i>
<p class="icon-name text-capitalize text-truncate mb-0 mt-2" >متغیر ها</p>
</div>
</div>
 </a>


 <a href="?page=panel_g_yadavar&code=<?php echo $code; ?>" >
<div class="card icon-card cursor-pointer text-center mb-4 mx-2  bg-secondary">
<div class="card-body"> <i class="mdi mdi-bell-outline mdi-36px"></i>
<p class="icon-name text-capitalize text-truncate mb-0 mt-2" >یادآوری ها </p>
</div>
</div>
 </a>


 <a href="?page=panel_g_file&code=<?php echo $code; ?>" >
<div class="card icon-card cursor-pointer text-center mb-4 mx-2 bg-secondary">
<div class="card-body"> <i class="mdi mdi-file-document mdi-36px"></i>
<p class="icon-name text-capitalize text-truncate mb-0 mt-2" >مستندات</p>
</div>
</div>
 </a>



 <a href="?page=panel_g_chap&code=<?php echo $code; ?>" >
<div class="card icon-card cursor-pointer text-center mb-4 mx-2 bg-success">
<div class="card-body"> <i class="mdi mdi-printer-outline mdi-36px"></i>
<p class="icon-name text-capitalize text-truncate mb-0 mt-2" >خروجی قرارداد</p>
</div>
</div>
 </a>


 <a href="?page=panel_g_end&code=<?php  echo $code; ?>" >
<div class="card icon-card cursor-pointer text-center mb-4 mx-2 bg-warning">
<div class="card-body"> <i class="mdi mdi-key-change mdi-36px"></i>
<p class="icon-name text-capitalize text-truncate mb-0 mt-2" >تغییر وضعیت</p>
</div>
</div>
 </a>


 <a href="?page=panel_g_edit&code=<?php echo $code; ?>" >
<div class="card icon-card cursor-pointer text-center mb-4 mx-2 bg-warning">
<div class="card-body"> <i class="mdi mdi-application-edit-outline mdi-36px"></i>
<p class="icon-name text-capitalize text-truncate mb-0 mt-2" >ویرایش</p>
</div>
</div>
 </a>


 <a href="?page=panel_g_hazf&code=<?php echo $code; ?>" >
<div class="card icon-card cursor-pointer text-center mb-4 mx-2  bg-danger">
<div class="card-body"> <i class="mdi mdi-close-outline mdi-36px"></i>
<p class="icon-name text-capitalize text-truncate mb-0 mt-2" >حذف</p>
</div>
</div>
 </a>



</div>
<?php }}
/*

                <div class="btn-group" role="group" aria-label="First group">
                <a href="?page=panel_g_info&code=<?php echo $code; ?>" ><button type="button" class="btn btn-outline-primary"><i class="tf-icons mdi mdi-bell-outline"></i>اطلاعات </button></a>
                <a href="?page=panel_g_matn&code=<?php echo $code; ?>" ><button type="button" class="btn btn-outline-primary"><i class="tf-icons mdi mdi-calendar-blank-outline"></i> متن قرار داد </button></a>
                <a href="?page=panel_g_data&code=<?php echo $code; ?>" ><button type="button" class="btn btn-outline-primary"><i class="tf-icons mdi mdi-shield-check-outline"></i> متغیر ها </button></a>
                <a href="?page=panel_g_alarm&code=<?php echo $code; ?>" ><button type="button" class="btn btn-outline-primary"><i class="tf-icons mdi mdi-bell-outline"></i>یادآوری ها</button></a>
                <a href="?page=panel_g_chap&code=<?php echo $code; ?>" ><button type="button" class="btn btn-outline-primary"><i class="tf-icons fas fa-print"></i>چاپ </button></a>
                <a href="?page=panel_g_end&code=<?php  echo $code; ?>" ><button type="button" class="btn btn-outline-primary bg-success"><i class="tf-icons mdi mdi-chat-processing-outline"></i>تغییر وضعیت</button></a>
                <a href="?page=panel_g_edit&code=<?php echo $code; ?>" ><button type="button" class="btn btn-outline-primary bg-warning"><i class="tf-icons mdi mdi-chat-processing-outline"></i> ویرایش </button></a>
                <a href="?page=panel_g_hazf&code=<?php echo $code; ?>" ><button type="button" class="btn btn-outline-primary bg-danger"><i class="tf-icons mdi mdi-chat-processing-outline"></i> حذف</button></a>

     
  

                */
?>




                        
