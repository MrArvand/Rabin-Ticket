<?php
require_once(__DIR__ . '/../../../jaryanyar/api_functions.php');

$code_ticket = str_p('code_ticket');
$matn_pasokh = str_p('matn_pasokh');
$kind_file = "";
$ok_upload = "n";

$email_kk = "";
$name_karbar5 = "";

if ($code_ticket != "" || $matn_pasokh != "" || $code_p_run != "") {

  $Query_ticket = "SELECT*from ticket where ( code = '$code_ticket' )ORDER BY i_ticket DESC LIMIT 200";
  if ($Result_ticket = mysqli_query($Link, $Query_ticket)) {
    while ($q_ticket = mysqli_fetch_array($Result_ticket)) {

      $code_karbar2 = $q_ticket['code_p_karbar'];
      $name_karbar2 = $q_ticket['name_karbar'];
      $titr = $q_ticket['titr'];
      $department_name = !empty($q_ticket['name_daste']) ? $q_ticket['name_daste'] : $q_ticket['daste'];
      $code_karbar_anjam = $q_ticket['code_p_karbar_anjam'];
      $vaziat_tt = $q_ticket['vaziat'];
      $daste_ticket = $q_ticket['daste'];
    }
  }



  $Query_karbar = "SELECT*from karbar where ( code_p = '$code_karbar_anjam' )ORDER BY i_karbar DESC LIMIT 200";
  if ($Result_karbar = mysqli_query($Link, $Query_karbar)) {
    while ($q_karbar = mysqli_fetch_array($Result_karbar)) {

      $email_kk = $q_karbar['email'];
      $tel_task_send = $q_karbar['tel'];
      $name_karbar5 = $q_karbar['name'];
    }
  }


  //---------------------------------------------------------

  // Reject new replies when ticket is closed (بسته شده) or cancelled (کنسل شده)
  if ($vaziat_tt === 'b' || $vaziat_tt === 'c') {
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=n');
    exit;
  }

  $code_pasokh = "G-" . time() . "-" . rand(11, 99);

  // Escape user input for SQL
  $matn_pasokh_escaped = mysqli_real_escape_string($Link, $matn_pasokh);
  $code_ticket_escaped = mysqli_real_escape_string($Link, $code_ticket);
  $last_activity = mysqli_real_escape_string($Link, trim($tarikh . ' ' . $saat));

  // Issuer reply must notify current assignee (strict per-user unread)
  $is_ticket_creator = ($code_p_run == $code_karbar2);
  $is_assigned_handler = ($code_p_run == $code_karbar_anjam);
  if ($is_ticket_creator || !$is_assigned_handler) {
    $reply_to_code = $code_karbar_anjam;
    $reply_to_name = $name_karbar5;
  } else {
    $reply_to_code = $code_karbar2;
    $reply_to_name = $name_karbar2;
  }
  $reply_to_code_escaped = mysqli_real_escape_string($Link, (string) $reply_to_code);
  $reply_to_name_escaped = mysqli_real_escape_string($Link, (string) $reply_to_name);

  $Qery = "INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) 
VALUES ('$code_pasokh', '$code_ticket_escaped', '$code_p_run', '$name_karbar_run', '$reply_to_code_escaped', '$reply_to_name_escaped', '$matn_pasokh_escaped', '$tarikh', '$saat', 'm', 'n', '', '', NULL);";

  if ($vaziat_tt != "a") {
    $Qery .= "UPDATE `ticket` SET
 `vaziat` = 'm',
 `last_activity` = '$last_activity'
 WHERE `code` ='$code_ticket_escaped'; ";
  } else {
    $Qery .= "UPDATE `ticket` SET
 `last_activity` = '$last_activity'
 WHERE `code` ='$code_ticket_escaped'; ";
  }


  //--------------------------------------------------------------------------------------sabt_file

  //-------------------------------------------------------------------------------------- MULTI FILE UPLOAD

  if (
    isset($_FILES['file_peyvast']) &&
    !empty($_FILES['file_peyvast']['name'][0])
  ) {

    $allowed_extensions = [
      'jpg',
      'jpeg',
      'png',
      'pdf',
      'doc',
      'docx',
      'xls',
      'xlsx',
      'zip',
      'rar'
    ];

    $total_files = count($_FILES['file_peyvast']['name']);

    for ($i = 0; $i < $total_files; $i++) {

      if ($_FILES['file_peyvast']['error'][$i] != UPLOAD_ERR_OK) {
        continue;
      }

      $original_name =
        strtolower($_FILES['file_peyvast']['name'][$i]);

      $kind_file =
        strtolower(
          pathinfo(
            $original_name,
            PATHINFO_EXTENSION
          )
        );

      if (!in_array($kind_file, $allowed_extensions)) {
        continue;
      }

      $hajm =
        round(
          ($_FILES['file_peyvast']['size'][$i] / 1024),
          2
        );

      $code_file =
        "FI-" .
        $code_ticket .
        "-" .
        $code_pasokh .
        "-" .
        $i .
        "-" .
        rand(1000, 9999);

      $destination =
        "../files/peyvast/" .
        $code_file .
        "." .
        $kind_file;

      if (
        move_uploaded_file(
          $_FILES['file_peyvast']['tmp_name'][$i],
          $destination
        )
      ) {

        $file_title =
          mysqli_real_escape_string(
            $Link,
            $original_name
          );

        $Qery .= "
          INSERT INTO `file_pasokh`
          (
              `code_ticket`,
              `code_pasokh`,
              `code_file`,
              `titr`,
              `kind`,
              `hajm`,
              `vaziat`,
              `i_file`
          )
          VALUES
          (
              '$code_ticket',
              '$code_pasokh',
              '$code_file',
              '$file_title',
              '$kind_file',
              '$hajm',
              'm',
              NULL
          );";
      }
    }
  }

  //-------------------------------------------------------------------------------------- END MULTI FILE UPLOAD

  if ($Link->multi_query($Qery) === TRUE) {
    $sabt_dastor = "y";

    if ($vaziat_tt != "a") {
      $apiResult = updateTicket($code_ticket);
      if ($apiResult && !$apiResult['success']) {
        error_log('Jaryanyar updateTicket failed for ' . $code_ticket . ': ' . ($apiResult['error'] ?? json_encode($apiResult['response'])));
      }
    }

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
        $sms_result = send_sms_pattern($tel_task_send, [
          'ticket_number' => $code_ticket,
          'ticket_title' => $titr_trimmed
        ], [
          'pattern_code' => 'OPGd5jbPVp',
          'line_number' => '3000505',
          'number_format' => 'english'
        ]);

        if (!$sms_result['success']) {
          error_log('IranPayamak reply SMS to supporter failed for ticket ' . $code_ticket . ': ' . $sms_result['message']);
        }
      } else {
        error_log('IranPayamak reply SMS to supporter skipped for ticket ' . $code_ticket . ': supporter phone is empty');
      }
    }
    //----------------------------------------------------------------




    header("location: ?page=info_ticket&code=$code_ticket&p=y");
  } else {
    header("location: ?page=info_ticket&code=$code_ticket&p=n");
  }
} else { //khali bodan
  header("location: ?page=info_ticket&code=$code_ticket&p=t");
}
