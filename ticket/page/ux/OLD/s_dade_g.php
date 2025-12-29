<?php

$code_g=str_p('code_g');
$name_m=str_p('name_m');
$name_motaghayer=str_p('name_motaghayer');
$meghdar=str_p('meghdar');



if($code_g !="" || $name_m !="" || $meghdar !=""   ){


//---------------------------------------------------------


$code_band="BT-".time()."-".rand(11,99);


$Qery="INSERT INTO `dade` (`code_gharardad`, `id_motaghayer`, `name`, `vaziat`, `meghdar`, `kind_motaghayer`, `i_dade`) 
VALUES ('$code_g', '$name_m', '$name_motaghayer', 'y', '$meghdar', 'text', NULL);";


	if ($Link->query($Qery ) === TRUE) {	

        header("location: ?page=panel_g_data&code=$code_g&p=y");  
    }else{
        header("location: ?page=panel_g_data&code=$code_g&p=n");  
    }

}else{
    header("location: ?page=panel_g_data&code=$code_g&p=t");  
}