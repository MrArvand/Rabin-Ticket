<?php

$name_k=str_p('name_k');
$code_k=str_p('code_k');
$semat_k=str_p('semat_k');
$tel_k=str_p('tel_k');
$email_k=str_p('email_k');
$passs=str_p('passs');

if($name_k !="" || $code_k !=""  ){

//---------------------------------------------------------

$cooode_k="T-".time()."-".rand(11,99);


$Qery="INSERT INTO `karbar` (`name`, `code_p`, `kind`, `code_karbar`, `semat`, `tel`, `email`, `vaziat`, `pass`, `name_sherkat`, `code_sherkat`, `kind_daste`, `avatar`, `i_karbar`, `let`, `gozaresh`) 
VALUES ('$name_k', '$code_k', 'poshtiban', '$cooode_k', '$semat_k', '$tel_k', '$email_k', 'y', '$passs', 'هلدینگ صنعتی رهباریان', 'holding', 'مدیران', 'morteza', NULL, '','');";


if ($Link->query($Qery ) === TRUE) {	
header("location: ?page=setting&p=y");  
}else{
header("location: ?page=setting&p=n");  
 }
}else{
header("location: ?page=setting&p=n");     
}
 ?>


