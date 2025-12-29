<?php

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
 
 
 
 

if ($Link->multi_query($Qery ) === TRUE) {	


echo $Qery;
	
header("location: ?page=list_ticket&code=$code_ticket&p=y");  
}else{
header("location: ?page=info_ticket&code=$code_ticket&p=n");  
 }}else{ 
header("location: ?page=info_ticket&code=$code_ticket&p=t");  
 } ?>


