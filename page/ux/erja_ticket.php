<?php
/*
$code_ticket=str_g('code_ticket');
$karbar_f=str_g('karbar');
$send_em = "m";




if( $code_ticket !="" ||  $karbar_f !="" ){
	

//---------------------------------------------------------

 $name_kk="";

$Query_list="SELECT*from ticket where ( code = '$code_ticket' )ORDER BY i_ticket DESC LIMIT 1";
if($Result_list=mysqli_query($Link,$Query_list)){
while($q_gar=mysqli_fetch_array($Result_list)){


     $vaziat_ticket=$q_gar['vaziat'];
     $name_daste=$q_gar['name_daste'];
     $vaziat_ticket=$q_gar['vaziat'];
	 $log_txt=$q_gar['log_txt'];
	 $titr=$q_gar['titr'];
	 $matn_darkhast=$q_gar['matn'];
	 $name_sherkat=$q_gar['name_sherkat'];
	 $olaviat=$q_gar['olaviat'];
	 $name_darkhast=$q_gar['name_karbar'];
	 $tel_darkhast=$q_gar['tel_karbar'];


}}


$Query_kk="SELECT*from karbar where ( code_p = '$karbar_f'  )";
if($Result_kk=mysqli_query($Link,$Query_kk)){
while($q_kk=mysqli_fetch_array($Result_kk)){


     $name_kk=$q_kk['name'];
	 $email_kk=$q_kk['email'];
	 $tel_karbar2=$q_kk['tel'];




}}



if($name_kk !=""){



$log_txt=$log_txt." تغییر وضعیت از  حالت ارجاع انجام پشتیبانی به  $karbar_f   در تاریخ  $tarikh - $saat  <br>";

$Qery="UPDATE `ticket` SET
 `vaziat` = 'm' 
 , `code_p_karbar_anjam` = '$karbar_f' 
 , `name_karbar_anjam` = '$name_kk' 
, `log_txt` = '$log_txt' 
 WHERE `code` ='$code_ticket'; ";	


$code_pasokh="G-".time()."-".rand(11,99);
$code_karbar_pasokh=$karbar_f;
$name_karbar_pasokh=$name_kk;
$matn_pasokh=" مسئول پاسخگویی به شما ". $name_karbar_pasokh." می باشد و در اولین فرصت خدمات پشتیبانی ارائه می گردد. با تشکر از شما";



 $Qery.="INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) 
VALUES ('$code_pasokh', '$code_ticket', '$code_karbar_pasokh', '$name_karbar_pasokh', '', '', '$matn_pasokh', '$tarikh', '$saat', 'm', 'n', '', '', NULL);";


 

if ($Link->multi_query($Qery ) === TRUE) {	
//----------------------------------------------------ersal email



 
$payam ="
*یک تیکت به شما ارجاع داده شد*
دسته ".$name_daste."

عنوان".$titr."

نام درخواست دهنده:".$name_darkhast."

شرکت درخواست دهنده: ".$name_sherkat."

درجه اهمیت 1 تا 4 :".$olaviat."

شماره تماس :".$tel_darkhast."

---------- متن تیکت
".$matn_darkhast;


$mmfooter="
--------------
رابین سامانه پارس
https://request-r.ir/
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
       'phonenumber' => $tel_karbar2,   
       'message' => $payam_end,  
   ),  
));  

$response = curl_exec($curl);  
curl_close($curl);  





//----------------------------------------------------

header("location: ?page=list_ticket&code=$code_ticket&p=y&se=$send_em");  // base ok
}else{
header("location: ?page=info_ticket&code=$code_ticket&p=n");  //base error
 }
}else{
header("location: ?page=info_ticket&code=$code_ticket&p=t");  	//karbar_eshteba
}
}else{ 
header("location: ?page=info_ticket&code=$code_ticket&p=t");   // khali bodan
 }


*/



require_once(__DIR__ . '/../../jaryanyar/api_functions.php');

$code_ticket = str_g('code_ticket');
$karbar_f = str_g('karbar');
$send_em = "m";

if (!empty($code_ticket) && !empty($karbar_f)) {

    $code_ticket_escaped = mysqli_real_escape_string($Link, $code_ticket);
    $karbar_f_escaped = mysqli_real_escape_string($Link, $karbar_f);
    $query = "SELECT t.*, k.name AS karbar_name, k.email, k.tel AS karbar_tel 
              FROM ticket t 
              LEFT JOIN karbar k ON k.code_p = '$karbar_f_escaped' 
              WHERE t.code = '$code_ticket_escaped' 
              ORDER BY t.i_ticket DESC LIMIT 1";

    $result = mysqli_query($Link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);


        if (empty($row['karbar_name'])) {
            header("location: ?page=info_ticket&code=$code_ticket&p=t");
            exit;
        }


        $vaziat_ticket = $row['vaziat'];
        $name_daste = $row['name_daste'];
        $log_txt = $row['log_txt'] . " تغییر وضعیت از حالت ارجاع انجام پشتیبانی به $karbar_f در تاریخ $tarikh - $saat <br>";
        $titr = $row['titr'];
        $matn_darkhast = $row['matn'];
        $name_sherkat = $row['name_sherkat'];
        $olaviat = $row['olaviat'];
        $name_darkhast = $row['name_karbar'];
        $tel_darkhast = $row['tel_karbar'];
        $name_kk = $row['karbar_name'];
        $email_kk = $row['email'];
        $tel_karbar2 = $row['karbar_tel'];


        // Get referring admin info from session
        $code_admin_refer = isset($_SESSION['code_p']) ? $_SESSION['code_p'] : '';
        $name_admin_refer = isset($_SESSION['name']) ? $_SESSION['name'] : '';

        // If not set, try to get from global variables (set in index.php)
        if (empty($code_admin_refer) && isset($code_p_run)) {
            $code_admin_refer = $code_p_run;
        }
        if (empty($name_admin_refer) && isset($name_karbar_run)) {
            $name_admin_refer = $name_karbar_run;
        }

        $code_pasokh = "G-" . time() . "-" . rand(11, 99);
        $matn_pasokh = " مسئول پاسخگویی به شما " . $name_kk . " می باشد و در اولین فرصت خدمات پشتیبانی ارائه می گردد. با تشکر از شما";

        // Escape variables for SQL
        $karbar_f_escaped_sql = mysqli_real_escape_string($Link, $karbar_f);
        $name_kk_escaped = mysqli_real_escape_string($Link, $name_kk);
        $log_txt_escaped = mysqli_real_escape_string($Link, $log_txt);
        $code_pasokh_escaped = mysqli_real_escape_string($Link, $code_pasokh);
        $matn_pasokh_escaped = mysqli_real_escape_string($Link, $matn_pasokh);
        $code_admin_refer_escaped = mysqli_real_escape_string($Link, $code_admin_refer);
        $name_admin_refer_escaped = mysqli_real_escape_string($Link, $name_admin_refer);

        $Qery = "UPDATE `ticket` SET 
                 `vaziat` = 'm', 
                 `code_p_karbar_anjam` = '$karbar_f_escaped_sql', 
                 `name_karbar_anjam` = '$name_kk_escaped', 
                 `log_txt` = '$log_txt_escaped' 
                 WHERE `code` ='$code_ticket_escaped'; ";

        // Store referral info: code_karbar2/name_karbar2 = referring admin, kind = 'referral'
        $Qery .= "INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `kind`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) 
                 VALUES ('$code_pasokh_escaped', '$code_ticket_escaped', '$karbar_f_escaped_sql', '$name_kk_escaped', '$code_admin_refer_escaped', '$name_admin_refer_escaped', '$matn_pasokh_escaped', '$tarikh', '$saat', 'm', 'referral', 'n', '', '', NULL);";

        // Drop referrer's bookmark: after ارجاع they may no longer be code_p_karbar / code_p_karbar_anjam,
        // so list_ticket would block removing it while the row still shows under bookmarked filter.
        if ($code_admin_refer !== '') {
            $Qery .= "DELETE FROM `ticket_bookmarks` WHERE `ticket_code` = '$code_ticket_escaped' AND `code_p_karbar` = '$code_admin_refer_escaped'; ";
        }

        if ($Link->multi_query($Qery)) {

            // Include SMS helper function
            require_once(__DIR__ . '/../../inf/s_sms.php');

            // Send SMS to assigned user
            if (!empty($tel_karbar2)) {
                $titr_trimmed = trim_text($titr, 80);
                $sms_result = send_sms_pattern($tel_karbar2, [
                    'ticket_number' => $code_ticket,
                    'ticket_title' => $titr_trimmed,
                    'user_fullname' => $name_darkhast
                ], [
                    'pattern_code' => 'MwoccILwQ3',
                    'line_number' => '3000505',
                    'number_format' => 'english'
                ]);

                if (!$sms_result['success']) {
                    error_log('IranPayamak referral SMS failed for ticket ' . $code_ticket . ': ' . $sms_result['message']);
                }
            } else {
                error_log('IranPayamak referral SMS skipped for ticket ' . $code_ticket . ': assigned user phone is empty');
            }

            $apiResult = updateTicket($code_ticket);
            if ($apiResult && !$apiResult['success']) {
              error_log('Jaryanyar updateTicket failed for ' . $code_ticket . ': ' . ($apiResult['error'] ?? json_encode($apiResult['response'])));
            }

            header("location: ?page=info_ticket&code=$code_ticket&p=y");
            exit;
        } else {
            header("location: ?page=info_ticket&code=$code_ticket&p=n");
            exit;
        }
    } else {
        header("location: ?page=info_ticket&code=$code_ticket&p=t");
        exit;
    }
} else {
    header("location: ?page=info_ticket&code=$code_ticket&p=t");
    exit;
}
