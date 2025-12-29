<?php

$code_ticket=str_p('code_ticket');
$matn_pasokh=str_p('matn_pasokh');
$kind_file="";
$ok_upload="n";




$Qery="";

								$code_ticket_escaped = mysqli_real_escape_string($Link, $code_ticket);
								$Query_ticket="SELECT * FROM ticket WHERE code = '$code_ticket_escaped' ORDER BY i_ticket DESC LIMIT 1";
   if($Result_ticket=mysqli_query($Link,$Query_ticket)){
 if($q_ticket=mysqli_fetch_array($Result_ticket)){

$code_karbar2=$q_ticket['code_p_karbar'];
$name_karbar2=$q_ticket['name_karbar'];
$tel_karbar2=$q_ticket['tel_karbar'];
$titr=$q_ticket['titr'];
$code_p_karbar_anjam=$q_ticket['code_p_karbar_anjam'];
$name_karbar_anjam=$q_ticket['name_karbar_anjam'];
$matn_ticket=$q_ticket['matn']; // For assignment SMS

 }}

if($code_karbar2 !="" || $code_ticket !="" || $matn_pasokh!="" || $code_p_run !=""  ){

//---------------------------------------------------------

// Determine if reply is from support user or ticket creator
$is_support_user = ($code_p_run == $code_p_karbar_anjam); // Current user is assigned support user
$is_ticket_creator = ($code_p_run == $code_karbar2); // Current user is ticket creator

$code_pasokh="G-".time()."-".rand(11,99);

// Escape user input for SQL
$code_pasokh_escaped = mysqli_real_escape_string($Link, $code_pasokh);
$code_p_run_escaped = mysqli_real_escape_string($Link, $code_p_run);
$name_karbar_run_escaped = mysqli_real_escape_string($Link, $name_karbar_run);
$code_karbar2_escaped = mysqli_real_escape_string($Link, $code_karbar2);
$name_karbar2_escaped = mysqli_real_escape_string($Link, $name_karbar2);
$matn_pasokh_escaped = mysqli_real_escape_string($Link, $matn_pasokh);

$Qery="INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) 
VALUES ('$code_pasokh_escaped', '$code_ticket_escaped', '$code_p_run_escaped', '$name_karbar_run_escaped', '$code_karbar2_escaped', '$name_karbar2_escaped', '$matn_pasokh_escaped', '$tarikh', '$saat', 'm', 'n', '', '', NULL);";






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
      
      if(move_uploaded_file($_FILES["file_peyvast"]["tmp_name"], "files/peyvast/" . $code_file.".".$kind_file)){
      $ok_upload="y";
      }}
  
  
  
  if($ok_upload=="y"){
  
  $Qery.="INSERT INTO `file_pasokh` (`code_ticket`, `code_pasokh`, `code_file`, `titr`, `kind`, `hajm`,`vaziat`, `i_file`) 
VALUES ('$code_ticket', '$code_pasokh', '$code_file', '$titr', '$kind_file', '$hajm','m', NULL);";
  
  }  }
    if ($Link->multi_query($Qery ) === TRUE) {	
  
	//-------------------------------------------------------------------------------------- end_file

//----------------------------------send sms
// Include SMS helper function
require_once(__DIR__ . '/../../inf/s_sms.php');

// Prepare trimmed text for SMS
$titr_trimmed = trim_text($titr, 80);
$matn_pasokh_trimmed = trim_text($matn_pasokh, 150);

if ($is_support_user && !$is_ticket_creator) {
    // Support user replied - send SMS to ticket creator
    if (!empty($tel_karbar2)) {
        $sms_message = "✉️ پاسخ جدیدی برای تیکت شما ثبت شد

شماره تیکت: " . $code_ticket . "
عنوان تیکت: " . $titr_trimmed . "
کاربر پشتیبانی: " . $name_karbar_run . "

پاسخ:
" . $matn_pasokh_trimmed;
        
        send_sms_ippanel($tel_karbar2, $sms_message);
    }
} elseif ($is_ticket_creator) {
    // Ticket creator replied - send SMS to assigned support user
    if (!empty($code_p_karbar_anjam)) {
        // Get assigned support user's phone number
        $tel_support = "";
        $code_p_karbar_anjam_escaped = mysqli_real_escape_string($Link, $code_p_karbar_anjam);
        $Query_support = "SELECT tel FROM karbar WHERE code_p = '$code_p_karbar_anjam_escaped' LIMIT 1";
        if ($Result_support = mysqli_query($Link, $Query_support)) {
            if ($row_support = mysqli_fetch_array($Result_support)) {
                $tel_support = $row_support['tel'];
            }
        }
        
        if (!empty($tel_support)) {
            $sms_message = "✉️ پاسخ جدیدی برای تیکت پشتیبانی

شماره تیکت: " . $code_ticket . "
عنوان تیکت: " . $titr_trimmed . "
کاربر ثبت کننده: " . $name_karbar2 . "

پاسخ:
" . $matn_pasokh_trimmed;
            
            send_sms_ippanel($tel_support, $sms_message);
        }
    }
}

 //----------------------------------end_send
	    
	    
	header("location: ?page=info_ticket&code=$code_ticket&p=y");      


    }else{
       header("location: ?page=info_ticket&code=$code_ticket&p=n");    
    } //end query
      
}else{ 

    header("location: ?page=info_ticket&code=$code_ticket&p=t");  
 } ?>


