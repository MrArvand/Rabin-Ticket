<?php

include('inf/f1.php');

$shomare=0;

	$martn = 	"*گزارش تیکت های دریافتی در گروه رابین*"."
	$tarikh
	";
	
	
          $Query_list="SELECT*from ticket where (tarikh_sabt =  '$tarikh'   )ORDER BY i_ticket DESC LIMIT 100 ";
	 
	 
	    if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 
$martn=$martn. "# *".$q_list['name_karbar']."*:
".$q_list['titr']."[".$q_list['name_sherkat']."]
--------------------------- 
"; 
	 
	 



}}



$mmfooter="
✅
سامانه پشتیبانی رابین ";

$payam_end=$martn.$mmfooter;

//-----------------------------------------------------------------


if($shomare > 0 ){

$curl = curl_init();

curl_setopt_array($curl, array(
   CURLOPT_URL => 'https://api.whatsiplus.com/sendMsg/ugv100n-yiirrcz-dkk7v38-bg6f45w-wmfvek5',
   CURLOPT_RETURNTRANSFER => true,  
   CURLOPT_ENCODING => '',  
   CURLOPT_MAXREDIRS => 10,  
   CURLOPT_TIMEOUT => 0,  
   CURLOPT_FOLLOWLOCATION => true,  
   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,  
   CURLOPT_CUSTOMREQUEST => 'POST',  
   CURLOPT_POSTFIELDS => array(  
       'phonenumber' => "09128887373",   
       'message' => $payam_end,  
   ),  
));  

$response = curl_exec($curl);  
curl_close($curl);  
echo $response;  

}
 ?>