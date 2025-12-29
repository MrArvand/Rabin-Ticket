<?php



if($code !=""  ){


//---------------------------------------------------------



$Qery="DELETE FROM `karkerd` WHERE `code` ='$code' LIMIT 1 ;";


	if ($Link->query($Qery ) === TRUE) {	


	
header("location: ?page=my_work&code=$code_g&p=y");  


}else{
    header("location: ?page=my_work&code=$code_g&p=n");  
 }
}else{ 
    header("location: ?page=my_work&code=$code_g&p=t");  
 } ?>


