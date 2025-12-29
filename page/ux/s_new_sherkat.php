<?php

$name_co=str_p('name_co');
$id_co=str_p('id_co');



if($name_co !="" || $id_co !=""  ){

//---------------------------------------------------------

//$code_ticket="T-".time()."-".rand(11,99);


$Qery="INSERT INTO `sherkatha` (`name`, `code`, `logo_sherkat`, `i_sherkat`) VALUES ('$name_co', '$id_co', '$id_co', NULL);";


if ($Link->query($Qery ) === TRUE) {	
header("location: ?page=setting&p=y");  
}else{
header("location: ?page=setting&p=n");  
 }
}else{
header("location: ?page=setting&p=n");     
}
 ?>


