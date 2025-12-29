<?php

$name_daste=str_p('name_daste');
$id_daste=str_p('id_daste');




if($name_daste !="" || $id_daste !=""  ){

//---------------------------------------------------------

//$code_ticket="T-".time()."-".rand(11,99);


$Qery="INSERT INTO `departman` (`name`, `id`, `modir`, `vaziat`, `i_dep`) VALUES ('$name_daste', '$id_daste', '24277', 'y', NULL);";


if ($Link->query($Qery ) === TRUE) {	
header("location: ?page=setting&p=y");  
}else{
header("location: ?page=setting&p=n");  
 }
}else{
header("location: ?page=setting&p=n");     
}
 ?>


