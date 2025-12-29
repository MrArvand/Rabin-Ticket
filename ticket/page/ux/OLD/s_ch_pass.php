<?php

$pass1=str_p('pass1');
$pass2=str_p('pass2');
$pass3=str_p('pass3');

$code_p=$_SESSION ['code_p'];
if( ($pass1 !="" || $pass2 !="" || $pass3 !="") AND ($pass2 != $pass3)  ){

    header("location: ?page=ch_pass&p=t");  

}else{   
//---------------------------------------------------------



$Query_list="SELECT*from karbar where ( code_p = '$code_P' )";
if($Result_list=mysqli_query($Link,$Query_list)){
while($q_gar=mysqli_fetch_array($Result_list)){

$pass_s=$q_gar['pass'];
}}


if($pass_s == $pass1){

$Qery="UPDATE `karbar` SET
 `pass` = '$pass3' 
 WHERE `code_p` ='$code_p'; ";	

if ($Link->query($Qery ) === TRUE) {	

header("location: ?page=ch_pass&p=y");  

}else{
header("location: ?page=ch_pass&p=n1");  
}
}else{
header("location: ?page=ch_pass&p=n2");  
}
} ?>


