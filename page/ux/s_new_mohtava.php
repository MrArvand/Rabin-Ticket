<?php

$name_w=str_p('name_w');
$titr=str_p('titr');
$link_vi=str_p('link_vi');
$kind_u=str_p('kind_u');
$daste=str_p('daste');
$sherkat=str_p('sherkat');
$cat1=str_p('cat1');
$cat2=str_p('cat2');


$matn=str_p('matn');


$name_sherkat="";
$code_file="";
$kind_file="";

if($name_w !="" || $titr !="" || $cat2!="" || $titr !=""   ){



								$Query_sherkat="SELECT*from sherkatha where (code = '$sherkat' )LIMIT 1";
   if($Result_sherkat=mysqli_query($Link,$Query_sherkat)){
 while($q_sherkat=mysqli_fetch_array($Result_sherkat)){



$name_sherkat=$q_sherkat['name'];



}}



								$Query_she="SELECT*from departman where (id = '$daste' )LIMIT 1";
   if($Result_she=mysqli_query($Link,$Query_she)){
 while($q_she=mysqli_fetch_array($Result_she)){



$name_daste=$q_she['name'];



}}





								$Query_sherkat="SELECT*from daste_mohtava where ( id_daste = '$cat2' )LIMIT 1";
   if($Result_sherkat=mysqli_query($Link,$Query_sherkat)){
 while($q_dasteha=mysqli_fetch_array($Result_sherkat)){


$name_cat1 = $q_dasteha['name_daste'];
$id_cat1 = $q_dasteha['id_daste'];

$name_cat2 = $q_dasteha['name_f_daste'];
$id_cat2 = $q_dasteha['id_f_daste'];


}}


$code_file_p="";

$code_mohtava="T-".time()."-".rand(11,99);
$poster_pic=$code_mohtava.".jpg";
$kind_file="";
//----------------------------------------------------------------------------------- poster


      
      if(move_uploaded_file($_FILES["poster"]["tmp_name"], "files/poster/" . $code_mohtava.".jpg")){
          
       $ok_up_poster="y";   
      }

//--------------------------------------------------------------------------------------sabt_file



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
  
  $hajm=($_FILES["file_peyvast"]["size"]/1024);
  //---------------------------------------------------------
  
  $code_file="GF-".$code_mohtava."-".time()."-".rand(11,99);
  
  if($kind_file!=""){
      
      if(move_uploaded_file($_FILES["file_peyvast"]["tmp_name"], "files/amozesh/" . $code_file.".".$kind_file)){
      $ok_upload="y";
      }}
  
  
  
  if($ok_upload=="y"){
$code_file_p=$code_file.".".$kind_file;

}}
$Qery="INSERT INTO `mohtava` (`titr`, `kind`, `link`, `name_file`, `kind_file`, `sherkat`, `daste`, `name_daste`, `cat1`, `name_cat1`, `cat2`, `name_cat2`, `vaziat`, `code`, `matn`, `tarikh_sabt`, `nevisande`, `poster`, `i_mohtava`) 
VALUES ('$titr', '$kind_u', '$link_vi', '$code_file','$kind_file', '$sherkat', '$daste', '$name_daste', '$id_cat1', '$name_cat1', '$id_cat2', '$name_cat2', 'y', '$code_mohtava', '$matn', '$tarikh','$name_w','$poster_pic',NULL);";





if ($Link->query($Qery) === TRUE) {	
    
header("location: ?page=list_mohtava&p=y");  
}else{
header("location: ?page=list_mohtava&p=n");  
 }
}else{
header("location: ?page=list_mohtava&p=n");     
}
 ?>


