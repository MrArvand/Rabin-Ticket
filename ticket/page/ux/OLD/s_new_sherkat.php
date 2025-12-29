<?php

$name_co=str_p('name_co');
$modir_co=str_p('modir_co');
$sn_co=str_p('sn_co');



if($name_co !="" || $modir_co !="" || $sn_co !="" ){


//---------------------------------------------------------


$code_co="CO-".time()."-".rand(11,99);

$Qery="INSERT INTO `sherkatha` (`name`, `code`, `logo_sherkat`, `sn_sabt_sherkat`, `code_modiramel`, `i_sherkat`)
 VALUES ('$name_co', '$code_co', '', '$sn_co', '$modir_co', NULL);";


	if ($Link->query($Qery ) === TRUE) {	
	
header("location: ?page=list_sherkat&p=y");  

}else{
    header("location: ?page=sabt_sherkat&p=n");  
 }
}else{ 

    header("location: ?page=sabt_sherkat&p=t");  
 } ?>


