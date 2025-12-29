<?php

$code_ticket=str_p('code_ticket');
$matn_pasokh=str_p('matn_pasokh');




$email_kk="";
$name_karbar5="";

if(  $code_ticket !="" || $matn_pasokh!="" || $code_p_run !=""  ){

								$Query_ticket="SELECT*from ticket where ( code = '$code_ticket' )ORDER BY i_ticket DESC LIMIT 200";
   if($Result_ticket=mysqli_query($Link,$Query_ticket)){
 while($q_ticket=mysqli_fetch_array($Result_ticket)){

$code_karbar2=$q_ticket['code_p_karbar'];
$name_karbar2=$q_ticket['name_karbar'];
$titr=$q_ticket['titr'];
$code_karbar_anjam=$q_ticket['code_p_karbar_anjam'];
$vaziat_tt=$q_ticket['vaziat'];

 }}
 
 
 
 								$Query_karbar ="SELECT*from karbar where ( code_p = '$code_karbar_anjam' )ORDER BY i_karbar DESC LIMIT 200";
   if($Result_karbar=mysqli_query($Link,$Query_karbar )){
 while($q_karbar =mysqli_fetch_array($Result_karbar )){

$email_kk=$q_karbar['email'];
$tel_task_send=$q_karbar['tel'];
$name_karbar5=$q_karbar['name'];

 }}
 
 
 
 
 
 
 



//---------------------------------------------------------


$code_pasokh="G-".time()."-".rand(11,99);

// Escape user input for SQL
$matn_pasokh_escaped = mysqli_real_escape_string($Link, $matn_pasokh);
$code_ticket_escaped = mysqli_real_escape_string($Link, $code_ticket);

$Qery="INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) 
VALUES ('$code_pasokh', '$code_ticket_escaped', '$code_p_run', '$name_karbar_run', '', '', '$matn_pasokh_escaped', '$tarikh', '$saat', 'm', 'n', '', '', NULL);";

if($vaziat_tt !="a" ){
    
$Qery.="UPDATE `ticket` SET
 `vaziat` = 'm' 
 WHERE `code` ='$code_ticket_escaped'; ";	
 
}


//--------------------------------------------------------------------------------------sabt_file


$namefilep=strtolower($_FILES["file_peyvast"]["name"]);
if($namefilep !="" ){


  $namefilep=strtolower($_FILES["file_peyvast"]["name"]);
  
  if(strpos($namefilep, "jpg") > 1 )$kind_file="jpg";
  if(strpos($namefilep, "jpeg") > 1 )$kind_file="jpeg";
  if(strpos($namefilep, "pdf") > 1 )$kind_file="pdf";
  if(strpos($namefilep, "rar") > 1 )$kind_file="rar";
  if(strpos($namefilep, "zip") > 1 )$kind_file="zip";
  if(strpos($namefilep, "doc") > 1 )$kind_file="doc";
  if(strpos($namefilep, "docx") > 1 )$kind_file="docx";
  if(strpos($namefilep, "xlsx") > 1 )$kind_file="xlsx";
  if(strpos($namefilep, "xls") > 1 )$kind_file="xls";
  if(strpos($namefilep, "png") > 1 )$kind_file="png";
  
  $hajm=round(($_FILES['file_peyvast']['size']/1024),2);
  //---------------------------------------------------------
  
  $code_file="FI-".$code_ticket."-".$code_pasokh."-".rand(11,99);
  
  if($kind_file!=""){
      
      if(move_uploaded_file($_FILES["file_peyvast"]["tmp_name"], "../files/peyvast/" . $code_file.".".$kind_file)){
      $ok_upload="y";
      }}
  
  
  
  if($ok_upload=="y"){
  
  $Qery.="INSERT INTO `file_pasokh` (`code_ticket`, `code_pasokh`, `code_file`, `titr`, `kind`, `hajm`,`vaziat`, `i_file`) 
VALUES ('$code_ticket', '$code_pasokh', '$code_file', '$titr', '$kind_file','$hajm','m', NULL);";

  }
//----------------------------------------------------- send email for poshtiban






















//-----------------------------------------------------------------------------

    
} // name_file
	if ($Link->multi_query($Qery) === TRUE) {	    
	    $sabt_dastor="y";
	    
//---------------------------------------------	    
//----------------------------------send sms
// Include SMS helper function
require_once(__DIR__ . '/../../inf/s_sms.php');

// Check if reply is from ticket creator (sender)
$is_ticket_creator = ($code_p_run == $code_karbar2);

// If ticket creator replied, send SMS to assigned user
if ($is_ticket_creator && !empty($code_karbar_anjam)) {
    // Get assigned user's phone number (already fetched as $tel_task_send)
    if (!empty($tel_task_send)) {
        // Prepare trimmed text for SMS
        $titr_trimmed = trim_text($titr, 80);
        $matn_pasokh_trimmed = trim_text($matn_pasokh, 150);
        
        $sms_message = "✉️ پاسخ جدیدی برای تیکت پشتیبانی

شماره تیکت: " . $code_ticket . "
عنوان تیکت: " . $titr_trimmed . "
کاربر ثبت کننده: " . $name_karbar2 . "

پاسخ:
" . $matn_pasokh_trimmed;
        
        send_sms_ippanel($tel_task_send, $sms_message);
    }
}
//----------------------------------end_send_sms

$payam="پاسخ کاربر به تیکت : 
*".$titr."*
ثبت کننده :".$name_karbar_run."
پاسخ:
".$matn_pasokh."

کاربر انجام : ".
$name_karbar5;

$mmfooter="
--------------
سامانه تیکت رابین
https://request-r.ir
";

$payam_end=$payam.$mmfooter;

//-----------------------------------------------------------------


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
       'phonenumber' => $tel_task_send,   
       'message' => $payam_end,  
   ),  
));  

$response = curl_exec($curl);  
curl_close($curl);  
//----------------------------------------------------------------
	    
	    
	    
	    
	  header("location: ?page=new_gharardad&p=y");  
  
	    
	}else{
	    header("location: ?page=new_gharardad&p=n");      
	}
    
}else{//khali bodan
    header("location: ?page=new_gharardad&p=t");  
 } ?>


