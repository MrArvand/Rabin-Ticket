<?php

$name_karbar=str_p('name_karbar');
$code_p_karbar=str_p('code_p_karbar');
$daste=str_p('daste');
$tel_karbar=str_p('tel_karbar');
$ok_set_login=str_p('ok_set_login');



if($name_karbar !="" || $code_p_karbar !="" || $daste !="" || $tel_karbar !=""  ){


$bedon_k="n";


    $Query_list="SELECT*from karbar where (name = '$name_karbar' || code_p ='$code_p_karbar' || tel = '$tel_karbar'   )ORDER BY i_karbar DESC LIMIT 200";
    if($Result_list=mysqli_query($Link,$Query_list)){
  while($q_model=mysqli_fetch_array($Result_list)){



    $bedon_k="y";

  }}

if($bedon_k=="n"){
//---------------------------------------------------------


$code_band="BT-".time()."-".rand(11,99);


$Qery="INSERT INTO `karbar` (`name`, `code_p`, `semat`, `pass`, `kind_karbar`, `tel`, `pic`, `code_sherkat`, `vaziat`, `i_karbar`, `set_login`)
 VALUES ('$name_karbar', '$code_p_karbar', '$daste', '123456', '$daste', '$tel_karbar', '', '', 'y', NULL,'$ok_set_login');";


	if ($Link->query($Qery ) === TRUE) {	

        header("location: ?page=sabt_karbar&p=y");  
    }else{
        header("location: ?page=sabt_karbar&p=n");  
    }

}else{
       header("location: ".$_SERVER['HTTP_REFERER']."&p=t"); 
}
}else{
    header("location: ".$_SERVER['HTTP_REFERER']."&p=t"); 
}