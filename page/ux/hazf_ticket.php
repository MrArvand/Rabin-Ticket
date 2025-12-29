<?php



if($code !=""  ){


//---------------------------------------------------------



$Qery="DELETE FROM `ticket` WHERE `code` ='$code' LIMIT 1 ;";
$Qery.="DELETE FROM `pasokh` WHERE `code_ticket` ='$code' LIMIT 100 ;";

	if ($Link->multi_query($Qery ) === TRUE) {	


	
header("location: ?page=list_ticket&code=$code&p=y");  


}else{
    header("location: ?page=list_ticket&code=$code&p=n");  
 }
}else{ 
    header("location: ?page=list_ticket&code=$code&p=t");  
 } ?>


