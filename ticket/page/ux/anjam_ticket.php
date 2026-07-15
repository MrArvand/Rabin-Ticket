<?php
require_once(__DIR__ . '/../../../jaryanyar/api_functions.php');

$code_ticket=str_g('code');
$kind=str_g('kind');

if( $code_ticket !=""  ){
	
$name_vaziat_gha="";	
$name_vaziat="";
//---------------------------------------------------------



$Query_list="SELECT*from ticket where ( code = '$code_ticket' )ORDER BY i_ticket DESC LIMIT 1";
if($Result_list=mysqli_query($Link,$Query_list)){
while($q_gar=mysqli_fetch_array($Result_list)){


    $vaziat_ticket=$q_gar['vaziat'];
    $daste_ticket=$q_gar['daste'];
    $designer_ticket=$q_gar['code_p_karbar_anjam'];
	
    $log_txt=$q_gar['log_txt'];

    if($vaziat_ticket=="a"){$name_vaziat="ثبت اولیه";}
    if($vaziat_ticket=="m"){$name_vaziat="در حال بررسی";}
    if($vaziat_ticket=="b"){$name_vaziat="بسته شده";}
    if($vaziat_ticket=="t"){$name_vaziat="بررسی مجدد";}
    if($vaziat_ticket=="c"){$name_vaziat="کنسل شده";}
    if($vaziat_ticket=="k"){$name_vaziat="انجام شد";}

if($kind == "b"){
$name_vaziat_gha =" بسته شده ";
$revaziat="b";
$revaziat2="b";
}

if($kind == "c"){
$name_vaziat_gha =" کنسل کردن ";
$revaziat="c";
$revaziat2="m";
}


if($kind == "t"){
$name_vaziat_gha ="بررسی مجدد ";
$revaziat="t";
$revaziat2="m";
}



if($kind == "k"){
$name_vaziat_gha =" انجام شد  ";
$revaziat="m";
$revaziat2="k";
}

}}




$log_txt=$log_txt." تغییر وضعیت از  حالت  $name_vaziat  به  $name_vaziat_gha  در تاریخ  $tarikh - $saat  <br>";

$code_ticket_escaped = mysqli_real_escape_string($Link, $code_ticket);
$name_run = isset($_SESSION['name']) ? $_SESSION['name'] : 'کاربر';
$code_p_run = isset($_SESSION['code_p']) ? $_SESSION['code_p'] : '';
$name_run_escaped = mysqli_real_escape_string($Link, $name_run);
$code_p_run_escaped = mysqli_real_escape_string($Link, $code_p_run);

$Qery="UPDATE `ticket` SET
 `vaziat` = '$revaziat' 
, `log_txt` = '$log_txt' 
 WHERE `code` ='$code_ticket'; ";	
 
 
 $Qery.="UPDATE `pasokh` SET
 `vaziat` = '$revaziat2' 
 WHERE `code_ticket` ='$code_ticket'; ";	
 
  $Qery.="UPDATE `file_pasokh` SET
 `vaziat` = '$revaziat2' 
 WHERE `code_ticket` ='$code_ticket'; ";	

// System message for تسک انجام شد (بسته شده)
if ($kind == 'b') {
    $code_pasokh_sys = 'G-' . time() . '-' . rand(11, 99);
    $code_pasokh_sys_escaped = mysqli_real_escape_string($Link, $code_pasokh_sys);
    $matn_sys = 'وضعیت تیکت توسط کاربر ' . $name_run . ' به «تسک انجام شد» (بسته شده) تغییر یافت.';
    $matn_sys_escaped = mysqli_real_escape_string($Link, $matn_sys);
    $Qery .= "INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `kind`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) ";
    $Qery .= "VALUES ('$code_pasokh_sys_escaped', '$code_ticket_escaped', '$code_p_run_escaped', '$name_run_escaped', '', '', '$matn_sys_escaped', '$tarikh', '$saat', '$revaziat2', 'done', 'n', '', '', NULL); ";
}

if ($Link->multi_query($Qery ) === TRUE) {	

    $apiResult = updateTicket($code_ticket);
    if ($apiResult && !$apiResult['success']) {
      error_log('Jaryanyar updateTicket failed for ' . $code_ticket . ': ' . ($apiResult['error'] ?? json_encode($apiResult['response'])));
    }

header("location: ?page=list_ticket&code=$code_ticket&p=y");
exit;  
}else{
header("location: ?page=info_ticket&code=$code_ticket&p=n");  
 }}else{ 
header("location: ?page=info_ticket&code=$code_ticket&p=t");  
 } ?>


