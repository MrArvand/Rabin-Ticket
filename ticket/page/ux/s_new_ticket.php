<?php

$karbar_darkhast=$code_p_run;
$name_karbar_darkhast=$name_karbar_run;
$olaviat=str_p('olaviat');
$daste=str_p('daste');
$sherkat=str_p('sherkat');
$titr=str_p('titr');
$matn=str_p('matn');

$Qery="";



$name_sherkar ="";
$Query_list="SELECT*from sherkatha where (code = '$sherkat' )";
if($Result_list=mysqli_query($Link,$Query_list)){
while($q_sherkar=mysqli_fetch_array($Result_list)){
$name_sherkar = $q_sherkar['name'];
}}




if($karbar_darkhast !="" || $olaviat !="" || $daste!="" || $titr !="" || $matn !=""  ){

//---------------------------------------------------------

$code_ticket=time()."-".rand(11,99);

$log_txt="ایجاد . ثبت اولیه تیکت از طریق پنل پشتیبان در  $tarikh  - $saat";

$tel_karbar = $_SESSION ['tel_k'];

  // Check if department has a default user assigned and get department name
  $default_user_code = '';
  $default_user_name = '';
  $department_name = '';
  $default_user_tel = '';
  if (!empty($daste)) {
    $daste_escaped = mysqli_real_escape_string($Link, $daste);
    $query_default = "SELECT name, default_user_code, default_user_name FROM departman WHERE id = '$daste_escaped' AND vaziat = 'y' LIMIT 1";
    if ($result_default = mysqli_query($Link, $query_default)) {
      if ($row_default = mysqli_fetch_array($result_default)) {
        $department_name = $row_default['name'];
        if (!empty($row_default['default_user_code']) && !empty($row_default['default_user_name'])) {
          $default_user_code = mysqli_real_escape_string($Link, $row_default['default_user_code']);
          $default_user_name = mysqli_real_escape_string($Link, $row_default['default_user_name']);
          
          // Get default user's phone number
          $default_user_code_escaped = mysqli_real_escape_string($Link, $default_user_code);
          $query_user_tel = "SELECT tel FROM karbar WHERE code_p = '$default_user_code_escaped' LIMIT 1";
          if ($result_user_tel = mysqli_query($Link, $query_user_tel)) {
            if ($row_user_tel = mysqli_fetch_array($result_user_tel)) {
              $default_user_tel = $row_user_tel['tel'];
            }
          }
        }
      }
    }
  }

  // Auto-assign to default user if exists, otherwise leave empty (status remains 'a')
  $code_p_karbar_anjam = $default_user_code;
  $name_karbar_anjam = $default_user_name;

  $Qery .= "INSERT INTO `ticket` (`titr`, `olaviat`, `matn`, `code`, `code_p_karbar`, `name_karbar`,  `tel_karbar`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `daste`, `name_daste`, `name_sherkat`, `code_sherkat`, `code_p_karbar_anjam`, `name_karbar_anjam`, `tarikh_anjam`, `saat_anjam`, `log_txt`, `i_ticket`) 
VALUES ('$titr', '$olaviat', '$matn', '$code_ticket', '$karbar_darkhast', '$name_karbar_darkhast','$tel_karbar', '$tarikh', '$saat', 'a', '$daste', '$daste', '$name_sherkar', '$sherkat', '$code_p_karbar_anjam', '$name_karbar_anjam', '', '', '$log_txt', NULL);";

$code_pasokh="G-".time()."-".rand(11,99);
// این کوئری بررسی شود
$Qery.="INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) 
VALUES ('$code_pasokh', '$code_ticket', '$code_p_run', '$name_karbar_run', '', '', '$matn', '$tarikh', '$saat', 'a', 'n', '', '', NULL);";



//--------------------------------------------------------------------------------------sabt_file


$namefilep=strtolower($_FILES["file_peyvast"]["name"]);
if($namefilep !="" ){

  
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
  
  

  $Qery.="INSERT INTO `file_pasokh` (`code_ticket`, `code_pasokh`, `code_file`, `titr`, `kind`, `hajm`, `vaziat`, `i_file`) 
VALUES ('$code_ticket', '$code_pasokh', '$code_file', '$titr', '$kind_file', '$hajm','m', NULL);";
}
}


if ($Link->multi_query($Qery ) === TRUE) {	
    
    // Include SMS helper function
    require_once(__DIR__ . '/../../inf/s_sms.php');
    
    // Send SMS to ticket creator (if phone number exists)
    if($tel_karbar !="" ){   
        $username = "09154200964";
        $password = '83184017morteza';
        $from = "+985000125475";
        $pattern_code = "3il987e2pdej5ji";
        $to = array("$tel_karbar");
        $input_data = array("sn_ticket" => "$code_ticket");
        $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handler);
    }
    
    // Send SMS to default user if assigned (using new IPPanel Edge API)
    if (!empty($default_user_code) && !empty($default_user_tel)) {
        // Prepare trimmed text for SMS
        $titr_trimmed = trim_text($titr, 80);
        
        // Use department name or fallback to daste id
        $display_department_name = !empty($department_name) ? $department_name : $daste;
        
        $sms_message = "📨 تیکت جدیدی برای دپارتمان " . $display_department_name . " ثبت شد.
        
شماره تیکت: " . $code_ticket . "
عنوان تیکت: " . $titr_trimmed . "
کاربر ثبت کننده: " . $name_karbar_darkhast;
        
        send_sms_ippanel($default_user_tel, $sms_message);
    }
    
header("location: ?page=new_gharardad&p=y");  
}else{
header("location: ?page=start_ticket&p=n");  
 }
}else{
header("location: ?page=start_ticket&p=n");     
}
?>