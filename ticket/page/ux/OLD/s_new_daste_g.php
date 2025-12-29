<?php

$name_daste=str_p('name_daste');


$id_daste="DASTE-".time()."-".rand(11,99);

if($name_daste !="" || $id_daste !=""  ){


//---------------------------------------------------------



$Qery="INSERT INTO `kind_gharardad` (`name_gharardad`, `id_gharardad`, `vaziat`, `i_gharardad`) VALUES ('$name_daste', '$id_daste', 'y', NULL);";


	if ($Link->query($Qery ) === TRUE) {	
        header("location: ".$_SERVER['HTTP_REFERER']."&p=y"); 
    }else{
        header("location: ".$_SERVER['HTTP_REFERER']."&p=n"); 
    }
}else{
    header("location: ".$_SERVER['HTTP_REFERER']."&p=k"); 
}