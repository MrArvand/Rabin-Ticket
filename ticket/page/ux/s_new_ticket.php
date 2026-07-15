<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once(__DIR__ . '/../../../jaryanyar/api_functions.php');
$karbar_darkhast = $code_p_run;
$name_karbar_darkhast = $name_karbar_run;
$olaviat = str_p('olaviat');
$daste = str_p('daste');
$sherkat = str_p('sherkat');
$titr = str_p('titr');
$matn = str_p('matn');
$targetSherkat = str_p('target_sherkat');
require_once __DIR__ . '/../../inf/restricted_department_sadgan_fx.php';

$Qery = "";



$name_sherkar = "";
$Query_list = "SELECT*from sherkatha where (code = '$sherkat' )";
if ($Result_list = mysqli_query($Link, $Query_list)) {
  while ($q_sherkar = mysqli_fetch_array($Result_list)) {
    $name_sherkar = $q_sherkar['name'];
  }
}


$name_target_sherkat = "";
if ($targetSherkat != "") {
  $targetSherkat_escaped = mysqli_real_escape_string($Link, $targetSherkat);
  $Query_target = "SELECT name FROM sherkatha WHERE code = '$targetSherkat_escaped' LIMIT 1";
  if ($Result_target = mysqli_query($Link, $Query_target)) {
    if ($row_target = mysqli_fetch_array($Result_target)) {
      $name_target_sherkat = $row_target['name'];
    }
  }
}




if ($karbar_darkhast != "" && $olaviat != "" && $daste != "" && $titr != "" && $matn != "") {
  $karbar_code = trim((string) $karbar_darkhast);
  if ((string) $daste === $restricted_department_id && !in_array($karbar_code, $restricted_department_allowed_users, true)) {
    header("location: ?page=start_ticket&p=n");
    exit;
  }

  //---------------------------------------------------------

  $code_ticket = time() . "-" . rand(11, 99);

  $log_txt = "ایجاد . ثبت اولیه تیکت از طریق پنل پشتیبان در  $tarikh  - $saat";

  $tel_karbar = $_SESSION['tel_k'];

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

  $Qery .= "
INSERT INTO `ticket` (
  `titr`,
  `olaviat`,
  `matn`,
  `code`,
  `code_p_karbar`,
  `name_karbar`,
  `tel_karbar`,
  `tarikh_sabt`,
  `saat_sabt`,
  `vaziat`,
  `daste`,
  `name_daste`,
  `name_sherkat`,
  `code_sherkat`,
  `target_sherkat_code`,
  `target_sherkat_name`,
  `code_p_karbar_anjam`,
  `name_karbar_anjam`,
  `tarikh_anjam`,
  `saat_anjam`,
  `log_txt`,
  `i_ticket`
) VALUES (
  '$titr',
  '$olaviat',
  '$matn',
  '$code_ticket',
  '$karbar_darkhast',
  '$name_karbar_darkhast',
  '$tel_karbar',
  '$tarikh',
  '$saat',
  'a',
  '$daste',
  '$department_name',
  '$name_sherkar',
  '$sherkat',
  '$targetSherkat',
  '$name_target_sherkat',
  '$code_p_karbar_anjam',
  '$name_karbar_anjam',
  '',
  '',
  '$log_txt',
  NULL
);
";


  $code_pasokh = "G-" . time() . "-" . rand(11, 99);
  // این کوئری بررسی شود
  $Qery .= "INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) 
VALUES ('$code_pasokh', '$code_ticket', '$code_p_run', '$name_karbar_run', '', '', '$matn', '$tarikh', '$saat', 'a', 'n', '', '', NULL);";



  //--------------------------------------------------------------------------------------sabt_file


  $namefilep = strtolower($_FILES["file_peyvast"]["name"]);
  if ($namefilep != "") {


    if (strpos($namefilep, "jpg") > 1) $kind_file = "jpg";
    if (strpos($namefilep, "jpeg") > 1) $kind_file = "jpeg";
    if (strpos($namefilep, "pdf") > 1) $kind_file = "pdf";
    if (strpos($namefilep, "rar") > 1) $kind_file = "rar";
    if (strpos($namefilep, "zip") > 1) $kind_file = "zip";
    if (strpos($namefilep, "doc") > 1) $kind_file = "doc";
    if (strpos($namefilep, "docx") > 1) $kind_file = "docx";
    if (strpos($namefilep, "xlsx") > 1) $kind_file = "xlsx";
    if (strpos($namefilep, "xls") > 1) $kind_file = "xls";
    if (strpos($namefilep, "png") > 1) $kind_file = "png";

    $hajm = round(($_FILES['file_peyvast']['size'] / 1024), 2);
    //---------------------------------------------------------

    $code_file = "FI-" . $code_ticket . "-" . $code_pasokh . "-" . rand(11, 99);

    if ($kind_file != "") {

      if (move_uploaded_file($_FILES["file_peyvast"]["tmp_name"], "../files/peyvast/" . $code_file . "." . $kind_file)) {
        $ok_upload = "y";
      }
    }



    if ($ok_upload == "y") {



      $Qery .= "INSERT INTO `file_pasokh` (`code_ticket`, `code_pasokh`, `code_file`, `titr`, `kind`, `hajm`, `vaziat`, `i_file`) 
VALUES ('$code_ticket', '$code_pasokh', '$code_file', '$titr', '$kind_file', '$hajm','m', NULL);";
    }
  }


  if ($Link->multi_query($Qery) === TRUE) {

    $apiResult = updateTicket($code_ticket);
    if ($apiResult && !$apiResult['success']) {
      error_log('Jaryanyar updateTicket failed for ' . $code_ticket . ': ' . ($apiResult['error'] ?? json_encode($apiResult['response'])));
    }

    // Include SMS helper function
    require_once(__DIR__ . '/../../inf/s_sms.php');

    // Send pattern SMS to default user if assigned
    if (!empty($default_user_code) && !empty($default_user_tel)) {
      $titr_trimmed = trim_text($titr, 80);
      $display_department_name = !empty($department_name) ? $department_name : $daste;

      $sms_result = send_sms_pattern($default_user_tel, [
        'department' => $display_department_name,
        'ticket_number' => $code_ticket,
        'ticket_title' => $titr_trimmed,
        'sender_name' => $name_karbar_darkhast
      ], [
        'pattern_code' => 'UlTaEoqWQ0',
        'line_number' => '3000505',
        'number_format' => 'english'
      ]);

      if (!$sms_result['success']) {
        error_log('IranPayamak SMS failed for ticket ' . $code_ticket . ': ' . $sms_result['message']);
      }
    } elseif (!empty($default_user_code)) {
      error_log('IranPayamak SMS skipped for ticket ' . $code_ticket . ': default user phone is empty');
    }


    header("location: ?page=new_gharardad&p=y");
    exit;
  } else {
    header("location: ?page=start_ticket&p=n");
    exit;
  }
} else {
  header("location: ?page=start_ticket&p=n");
  exit;
}
