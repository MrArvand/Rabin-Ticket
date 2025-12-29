<?php

$karbar_darkhast=str_p('karbar_darkhast');
$karbar_p_darkhast=str_p('karbar_p_darkhast');
$olaviat=str_p('olaviat');
$daste=str_p('daste');
$sherkat=str_p('sherkat');
$titr=str_p('titr');
$matn=str_p('matn');
$tel_karbar=str_p('tel_karbar');
$name_sherkat="";



								$Query_sherkat="SELECT*from sherkatha where (code = '$sherkat' )LIMIT 1";
   if($Result_sherkat=mysqli_query($Link,$Query_sherkat)){
 while($q_sherkat=mysqli_fetch_array($Result_sherkat)){



$name_sherkat=$q_sherkat['name'];



}}




if($karbar_darkhast !="" || $olaviat !="" || $daste!="" || $titr !="" || $matn !=""  ){

//---------------------------------------------------------

$code_ticket="T-".time()."-".rand(11,99);

$log_txt="ایجاد . ثبت اولیه تیکت از طریق پنل پشتیبان در  $tarikh  - $saat";

$titr="[*]".$titr;

$Qery="INSERT INTO `ticket` (`titr`, `olaviat`, `matn`, `code`, `code_p_karbar`, `name_karbar`,`tel_karbar`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `daste`, `name_daste`, `name_sherkat`, `code_sherkat`, `code_p_karbar_anjam`, `name_karbar_anjam`, `tarikh_anjam`, `saat_anjam`, `log_txt`, `i_ticket`) 
VALUES ('$titr', '$olaviat', '$matn', '$code_ticket', '$karbar_p_darkhast', '$karbar_darkhast', '$tel_karbar', '$tarikh', '$saat', 'a', '$daste', '$daste', '$name_sherkat', '$sherkat', '', '', '', '', '$log_txt', NULL);";

$code_pasokh="G-".time()."-".rand(11,99);
// این کوئری بررسی شود
$Qery.="INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) 
VALUES ('$code_pasokh', '$code_ticket', '$code_p_run', '$name_karbar_run', '$karbar_darkhast', '$karbar_darkhast', '$matn', '$tarikh', '$saat', 'm', 'y', '', '', NULL);";



//------------------------------------------------------------------------------------sabt_file


$namefilep=strtolower($_FILES["file_peyvast"]["name"]);
if($namefilep !="" ){


  $namefilep=strtolower($_FILES["file_peyvast"]["name"]);
  
  if(strpos($namefilep, "jpg") > 1 )$kind_file="jpg";
  if(strpos($namefilep, "jpeg") > 1 )$kind_file="jpeg";
  if(strpos($namefilep, "pdf") > 1 )$kind_file="pdf";
  if(strpos($namefilep, "rar") > 1 )$kind_file="rar";
  if(strpos($namefilep, "zip") > 1 )$kind_file="zip";
  if(strpos($namefilep, "doc") > 1 )$kind_file="doc";
  if(strpos($namefilep, "docx") > 1 )$kind_file="docx";
  if(strpos($namefilep, "xlsx") > 1 )$kind_file="xlsx";
  if(strpos($namefilep, "xls") > 1 )$kind_file="xls";
  if(strpos($namefilep, "png") > 1 )$kind_file="png";
  
  $hajm=round(($_FILES['file_peyvast']['size']/1024),2);
  //---------------------------------------------------------
  
  $code_file="FI-".$code_ticket."-".$code_pasokh."-".rand(11,99);
  
  if($kind_file!=""){
      
      if(move_uploaded_file($_FILES["file_peyvast"]["tmp_name"], "files/peyvast/" . $code_file.".".$kind_file)){
      $ok_upload="y";
      }}
  
  
  
  if($ok_upload=="y"){
  
  $Qery.="INSERT INTO `file_pasokh` (`code_ticket`, `code_pasokh`, `code_file`, `titr`, `kind`, `hajm`,`vaziat`, `i_file`) 
VALUES ('$code_ticket', '$code_pasokh', '$code_file', '$titr', '$kind_file', '$hajm','m', NULL);";
}
}


if ($Link->multi_query($Qery ) === TRUE) {	
header("location: ?page=list_ticket&p=y");  
}else{
header("location: ?page=start_ticket&p=n");  
 }
}else{
header("location: ?page=start_ticket&p=n");     
}
 ?>


