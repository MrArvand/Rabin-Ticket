<?php

$id_kind=str_p('name_kind');
$name_bakhash=str_p('name_bakhash');

$janamay=str_p('janamay');

$id_bakhash="BG-".time()."-".rand(11,99);

if( strlen($janamay) < 1 ){
  $janamay= chr(ord($janamay) + 1);
  }else{
  
    $lastChar = substr($janamay, -1);
    $janamay= chr(ord($lastChar) + 1);
  }




$kijjhd="n";

$Query_list="SELECT*from sakhtar where ( radif = '$janamay' AND  kind_gharardad='$id_kind' )";
if($Result_list=mysqli_query($Link,$Query_list)){
while($q_model=mysqli_fetch_array($Result_list)){

  $kijjhd="y";

}}


if($kijjhd=="y"){

  $lastChar = substr($janamay, -1);
  $janamay= $janamay.chr(ord($lastChar) + 1);

}
if($name_kind !="" || $name_bakhash !="" || $id_bakhash !=""   || $janamay !="" ){


    $Query_selc="SELECT*from kind_gharardad where (vaziat = 'y')ORDER BY i_gharardad DESC LIMIT 200";
    if($Result_selc=mysqli_query($Link,$Query_selc)){
  while($q_selc=mysqli_fetch_array($Result_selc)){

    $name_kind=$q_selc['name_gharardad'];
  }}


//---------------------------------------------------------



$Qery="INSERT INTO `sakhtar` (`name`, `radif`, `kind_gharardad`, `name_gharardad`, `vaziat`, `id_radif`, `set_khali`, `i_radif`) 
VALUES ('$name_bakhash', '$janamay', '$id_kind', '$name_kind', 'y', '$id_bakhash', 'y', NULL);";


	if ($Link->query($Qery ) === TRUE) {	
        header("location: ".$_SERVER['HTTP_REFERER']."&p=y"); 
    }else{
        header("location: ".$_SERVER['HTTP_REFERER']."&p=n"); 
    }
}else{
    header("location: ".$_SERVER['HTTP_REFERER']."&p=k"); 
}