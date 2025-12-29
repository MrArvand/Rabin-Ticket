<?php

$name_file_g=str_p('name_file_g');
$code_g=str_p('code_g');
$pass_file=str_p('pass_file');
$kind_file="";
$ok_upload="n";

if($name_file_g !="" || $code_g !=""  ){


$namefilep=strtolower($_FILES["file_for_g"]["name"]);

if(strpos($namefilep, "jpg") > 1 )$kind_file="jpg";
if(strpos($namefilep, "jpeg") > 1 )$kind_file="jpeg";
if(strpos($namefilep, "pdf") > 1 )$kind_file="pdf";
if(strpos($namefilep, "rar") > 1 )$kind_file="rar";
if(strpos($namefilep, "zip") > 1 )$kind_file="zip";
if(strpos($namefilep, "doc") > 1 )$kind_file="doc";
if(strpos($namefilep, "docx") > 1 )$kind_file="docx";

//---------------------------------------------------------

$code_file="GF-".$code_g."-".time()."-".rand(11,99);

if($kind_file!=""){
    
    if(move_uploaded_file($_FILES["file_for_g"]["tmp_name"], "files/doc/" . $code_file.".".$kind_file)){
    $ok_upload="y";
    }}



if($ok_upload=="y"){

$Qery="INSERT INTO `file_gharardad` (`name`, `code`, `code_g`, `tarikh_sabt`, `pass`, `code_karbar`, `vaziat`, `kind_file`, `i_file`) 
VALUES ('$name_file_g', '$code_file', '$code_g', '$tarikh', '$pass_file', '$code_p_run', 'm', '$kind_file', NULL);";


	if ($Link->query($Qery ) === TRUE) {	

$sabt_dastor="y";

	
header("location: ?page=panel_g_file&code=$code_g&p=y");  

}else{
    header("location: ?page=panel_g_file&code=$code_g&p=n");  
 }

}else{ 

    header("location: ?page=panel_g_file&code=$code_g&p=t1");  
 } 


}else{ 

    header("location: ?page=panel_g_file&code=$code_g&p=t2");  
 } ?>


