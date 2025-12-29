<?php

$name_band=str_p('name_band');
$kind_band=str_p('kind_band');
$daste=str_p('daste');
$matn=str_p('matn');


if($name_band !="" || $kind_band !="" || $daste !="" || $matn !=""  ){


//---------------------------------------------------------


$code_band="BT-".time()."-".rand(11,99);


$Qery="INSERT INTO `bandha` (`name_band`, `kind_band`, `matn_band`, `last_tarikh_v`, `code_user_v`, `motoghayer`, `code_band`, `daste`, `i_band`) 
VALUES ('$name_band', '$kind_band', '$matn', '$tarikh', '$code_p_run', 'y', '$code_band', '$daste', NULL);";


	if ($Link->query($Qery ) === TRUE) {	

        header("location: ?page=new_band&p=y");  
    }else{
        header("location: ?page=new_band&p=n");  
    }

}else{
       header("location: ".$_SERVER['HTTP_REFERER']."&p=t"); 
}