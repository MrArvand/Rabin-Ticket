<?php

$code_g=str_g('code_g');
$code_band=str_g('code_band');
$kind=str_g('kind');


if($code_g !="" || $code !="" || $kind !=""  ){


//---------------------------------------------------------


$code_gha="G-".time()."-".rand(11,99);


$Qery="DELETE FROM `band_gharardad` WHERE `code` ='$code_band' LIMIT 1 ;";


	if ($Link->query($Qery ) === TRUE) {	

$sabt_dastor="y";

	
header("location: ?page=panel_g_matn&code=$code_g&p=y");  


}else{
    header("location: ?page=panel_g_matn&code=$code_g&p=n");  
 }
}else{ 
    header("location: ?page=panel_g_matn&code=$code_g&p=t");  
 } ?>


