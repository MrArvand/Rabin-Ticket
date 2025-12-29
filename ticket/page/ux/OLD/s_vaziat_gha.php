<?php

$code_sabt_g=str_p('code_sabt_g');
$vaziat_gha=str_p('vaziat_gha');
$tarikh_sabt=str_p('tarikh_sabt');
$code_g=str_p('code_g');
$matn=str_p('matn');


if( $vaziat_gha !="" || $tarikh_sabt !="" || $code_g !=""  ){

if(($vaziat_gha == "y" || $vaziat_gha == "em" || $vaziat_gha == "p" || $vaziat_gha == "v" || $vaziat_gha == "f" || $vaziat_gha == "c" || $vaziat_gha == "f" ) AND $code_sabt_g==""){

    header("location: ?page=panel_g_info&code=$code_g&p=t");  

}else{   
//---------------------------------------------------------



$Query_list="SELECT*from gharardad where ( code = '$code_g' )ORDER BY i_gharardad DESC LIMIT 1";
if($Result_list=mysqli_query($Link,$Query_list)){
while($q_gar=mysqli_fetch_array($Result_list)){


    $vaziat_gharardad=$q_gar['vaziat'];
    $matn_log=$q_gar['matn_log'];

    if($vaziat_gharardad=="a"){$name_vaziat_gharardad="در حال تکمیل";}
    if($vaziat_gharardad=="na"){$name_vaziat_gharardad="تایید ناظر";}
    if($vaziat_gharardad=="y"){$name_vaziat_gharardad="تایید نهایی";}
    if($vaziat_gharardad=="em"){$name_vaziat_gharardad="امضا";}
    if($vaziat_gharardad=="p"){$name_vaziat_gharardad="پایان";}
    if($vaziat_gharardad=="v"){$name_vaziat_gharardad="ویرایش";}
    if($vaziat_gharardad=="c"){$name_vaziat_gharardad="کنسل";}
    if($vaziat_gharardad=="t"){$name_vaziat_gharardad="تمدید";}
    if($vaziat_gharardad=="f"){$name_vaziat_gharardad="افزودن بند / الحاقیه";}



}}


if($vaziat_gha=="a"){$name_vaziat_gha="در حال تکمیل";}
if($vaziat_gha=="na"){$name_vaziat_gha="تایید ناظر";}
if($vaziat_gha=="y"){$name_vaziat_gha="تایید نهایی";}
if($vaziat_gha=="em"){$name_vaziat_gha="امضا";}
if($vaziat_gha=="p"){$name_vaziat_gha="پایان";}
if($vaziat_gha=="v"){$name_vaziat_gha="ویرایش";}
if($vaziat_gha=="c"){$name_vaziat_gha="کنسل";}
if($vaziat_gha=="t"){$name_vaziat_gha="تمدید";}
if($vaziat_gha=="f"){$name_vaziat_gha="افزودن بند / الحاقیه";}


$matn_log=$matn_log." تغییر وضعیت از  حالت  $name_vaziat_gharardad   به  $name_vaziat_gha  در تاریخ  $tarikh - $saat  <br>";

$Qery="UPDATE `gharardad` SET
 `sn_gharardad` = '$code_sabt_g' 
, `vaziat` = '$vaziat_gha' 
, `matn_log` = '$matn_log' 
 WHERE `code` ='$code_g'; ";	

if ($Link->query($Qery ) === TRUE) {	

$sabt_dastor="y";

	
header("location: ?page=panel_g_info&code=$code_g&p=y");  


}else{
    header("location: ?page=panel_g_info&code=$code_g&p=n");  
 }
}}else{ 
    header("location: ?page=panel_g_info&code=$code_g&p=t");  
 } ?>


