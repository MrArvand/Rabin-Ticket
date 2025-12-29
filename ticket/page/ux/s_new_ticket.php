<?php

$karbar_darkhast=$code_p_run;
$name_karbar_darkhast=$name_karbar_run;
$olaviat=str_p('olaviat');
$daste=str_p('daste');
$sherkat=str_p('sherkat');
$titr=str_p('titr');
$matn=str_p('matn');

$Qery="";



$name_sherkar ="";
$Query_list="SELECT*from sherkatha where (code = '$sherkat' )";
if($Result_list=mysqli_query($Link,$Query_list)){
while($q_sherkar=mysqli_fetch_array($Result_list)){
$name_sherkar = $q_sherkar['name'];
}}




if($karbar_darkhast !="" || $olaviat !="" || $daste!="" || $titr !="" || $matn !=""  ){

//---------------------------------------------------------

$code_ticket=time()."-".rand(11,99);

$log_txt="ایجاد . ثبت اولیه تیکت از طریق پنل پشتیبان در  $tarikh  - $saat";

$tel_karbar = $_SESSION ['tel_k'];
$Qery.="INSERT INTO `ticket` (`titr`, `olaviat`, `matn`, `code`, `code_p_karbar`, `name_karbar`,  `tel_karbar`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `daste`, `name_daste`, `name_sherkat`, `code_sherkat`, `code_p_karbar_anjam`, `name_karbar_anjam`, `tarikh_anjam`, `saat_anjam`, `log_txt`, `i_ticket`) 
VALUES ('$titr', '$olaviat', '$matn', '$code_ticket', '$karbar_darkhast', '$name_karbar_darkhast','$tel_karbar', '$tarikh', '$saat', 'a', '$daste', '$daste', '$name_sherkar', '$sherkat', '', '', '', '', '$log_txt', NULL);";

$code_pasokh="G-".time()."-".rand(11,99);
// این کوئری بررسی شود
$Qery.="INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) 
VALUES ('$code_pasokh', '$code_ticket', '$code_p_run', '$name_karbar_run', '', '', '$matn', '$tarikh', '$saat', 'a', 'n', '', '', NULL);";



//--------------------------------------------------------------------------------------sabt_file


$namefilep=strtolower($_FILES["file_peyvast"]["name"]);
if($namefilep !="" ){

  
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
      
      if(move_uploaded_file($_FILES["file_peyvast"]["tmp_name"], "../files/peyvast/" . $code_file.".".$kind_file)){
      $ok_upload="y";
      }}
  
  
  
  if($ok_upload=="y"){
  
  

  $Qery.="INSERT INTO `file_pasokh` (`code_ticket`, `code_pasokh`, `code_file`, `titr`, `kind`, `hajm`, `vaziat`, `i_file`) 
VALUES ('$code_ticket', '$code_pasokh', '$code_file', '$titr', '$kind_file', '$hajm','m', NULL);";
}
}


if ($Link->multi_query($Qery ) === TRUE) {	
    
    
 if($tel_karbar !="" ){   
$username = "09154200964";
$password = '83184017morteza';
$from = "+985000125475";
$pattern_code = "3il987e2pdej5ji";
$to = array("$tel_karbar");
$input_data = array("sn_ticket" => "$code_ticket");
$url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
$handler = curl_init($url);
curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($handler);
    
 }
    
    
    
    
    
header("location: ?page=new_gharardad&p=y");  
}else{
header("location: ?page=start_ticket&p=n");  
 }
}else{
header("location: ?page=start_ticket&p=n");     
}
 ?>


