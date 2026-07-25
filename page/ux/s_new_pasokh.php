<?php
require_once(__DIR__ . '/../../jaryanyar/api_functions.php');

$code_ticket = str_p('code_ticket');
$matn_pasokh = str_p('matn_pasokh');

$Qery = "";

$code_ticket_escaped = mysqli_real_escape_string($Link, $code_ticket);
$Query_ticket = "SELECT * FROM ticket WHERE code = '$code_ticket_escaped' ORDER BY i_ticket DESC LIMIT 1";
if ($Result_ticket = mysqli_query($Link, $Query_ticket)) {
    if ($q_ticket = mysqli_fetch_array($Result_ticket)) {

        $tel_karbar2 = $q_ticket['tel_karbar'];
        $titr = $q_ticket['titr'];
        $department_name = !empty($q_ticket['name_daste']) ? $q_ticket['name_daste'] : $q_ticket['daste'];
        $code_p_karbar_anjam = $q_ticket['code_p_karbar_anjam'];
        $name_karbar_anjam = $q_ticket['name_karbar_anjam'];
        $matn_ticket = $q_ticket['matn']; // For assignment SMS

    }
}

if (!empty($q_ticket) && ($code_ticket != "" || $matn_pasokh != "" || $code_p_run != "")) {

    //---------------------------------------------------------

    // Determine if reply is from support user or ticket creator
    $is_support_user = ($code_p_run == $code_p_karbar_anjam); // Current user is assigned support user
    $is_ticket_creator = ($code_p_run == $q_ticket['code_p_karbar']); // Current user is ticket creator

    // Recipient of this reply (used for read/unread tracking)
    if ($is_support_user && !$is_ticket_creator) {
        $code_karbar2 = $q_ticket['code_p_karbar'];
        $name_karbar2 = $q_ticket['name_karbar'];
    } elseif ($is_ticket_creator) {
        $code_karbar2 = $q_ticket['code_p_karbar_anjam'];
        $name_karbar2 = $q_ticket['name_karbar_anjam'];
    } else {
        $code_karbar2 = $q_ticket['code_p_karbar'];
        $name_karbar2 = $q_ticket['name_karbar'];
    }

    $code_pasokh = "G-" . time() . "-" . rand(11, 99);

    // Escape user input for SQL
    $code_pasokh_escaped = mysqli_real_escape_string($Link, $code_pasokh);
    $code_p_run_escaped = mysqli_real_escape_string($Link, $code_p_run);
    $name_karbar_run_escaped = mysqli_real_escape_string($Link, $name_karbar_run);
    $code_karbar2_escaped = mysqli_real_escape_string($Link, $code_karbar2);
    $name_karbar2_escaped = mysqli_real_escape_string($Link, $name_karbar2);
    $matn_pasokh_escaped = mysqli_real_escape_string($Link, $matn_pasokh);

    // Check if this is the first pasokh (reply) for this ticket
    // If ticket status is 'a' (ثبت اولیه), change it to 'm' (در حال بررسی) after first reply
    $is_first_reply = false;
    $current_ticket_status = $q_ticket['vaziat'] ?? '';

    // Check if there are any previous pasokh records (excluding the initial ticket creation pasokh)
    $query_pasokh_count = "SELECT COUNT(*) as pasokh_count FROM pasokh 
                        WHERE code_ticket = '$code_ticket_escaped' 
                        AND vaziat != 'a'";
    $pasokh_count = 0;
    if ($result_pasokh_count = mysqli_query($Link, $query_pasokh_count)) {
        if ($row_count = mysqli_fetch_array($result_pasokh_count)) {
            $pasokh_count = (int)$row_count['pasokh_count'];
        }
    }

    // Reject new replies when ticket is closed (بسته شده) or cancelled (کنسل شده)
    if ($current_ticket_status === 'b' || $current_ticket_status === 'c') {
        header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=n');
        exit;
    }

    // If this is the first reply (no previous pasokh with vaziat != 'a') and ticket status is 'a'
    $last_activity = mysqli_real_escape_string($Link, trim($tarikh . ' ' . $saat));
    if ($pasokh_count == 0 && $current_ticket_status === 'a') {
        $is_first_reply = true;
        // Update ticket status to 'm' (در حال بررسی) and bump activity
        $Qery = "UPDATE ticket SET vaziat = 'm', last_activity = '$last_activity' WHERE code = '$code_ticket_escaped'; ";
    } else {
        $Qery = "UPDATE ticket SET last_activity = '$last_activity' WHERE code = '$code_ticket_escaped'; ";
    }

    $Qery .= "INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) 
VALUES ('$code_pasokh_escaped', '$code_ticket_escaped', '$code_p_run_escaped', '$name_karbar_run_escaped', '$code_karbar2_escaped', '$name_karbar2_escaped', '$matn_pasokh_escaped', '$tarikh', '$saat', 'm', 'n', '', '', NULL);";

    if (isset($_FILES['file_peyvast'])) {
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar');

        $file_names = $_FILES['file_peyvast']['name'];
        if (!is_array($file_names)) {
            $file_names = array($file_names);
            $file_errors = array($_FILES['file_peyvast']['error']);
            $file_sizes = array($_FILES['file_peyvast']['size']);
            $file_tmps = array($_FILES['file_peyvast']['tmp_name']);
        } else {
            $file_errors = $_FILES['file_peyvast']['error'];
            $file_sizes = $_FILES['file_peyvast']['size'];
            $file_tmps = $_FILES['file_peyvast']['tmp_name'];
        }

        $total_files = count($file_names);
        for ($i = 0; $i < $total_files; $i++) {
            if (empty($file_names[$i]) || $file_errors[$i] != UPLOAD_ERR_OK) {
                continue;
            }

            $original_name = strtolower((string) $file_names[$i]);
            $kind_file = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

            if (!in_array($kind_file, $allowed_extensions, true)) {
                continue;
            }

            $hajm = round(($file_sizes[$i] / 1024), 2);
            $code_file = "FI-" . $code_ticket . "-" . $code_pasokh . "-" . $i . "-" . rand(1000, 9999);
            $destination = "files/peyvast/" . $code_file . "." . $kind_file;

            if (move_uploaded_file($file_tmps[$i], $destination)) {
                $file_title = mysqli_real_escape_string($Link, $original_name);
                $code_file_escaped = mysqli_real_escape_string($Link, $code_file);
                $Qery .= "INSERT INTO `file_pasokh` (`code_ticket`, `code_pasokh`, `code_file`, `titr`, `kind`, `hajm`,`vaziat`, `i_file`) 
VALUES ('$code_ticket_escaped', '$code_pasokh_escaped', '$code_file_escaped', '$file_title', '$kind_file', '$hajm','m', NULL);";
            }
        }
    }

    if ($Link->multi_query($Qery) === TRUE) {
    do {
        if ($result = $Link->store_result()) {
            $result->free();
        }
    } while ($Link->more_results() && $Link->next_result());
        //-------------------------------------------------------------------------------------- end_file

        //----------------------------------send sms
        // Include SMS helper function
        require_once(__DIR__ . '/../../inf/s_sms.php');

        // Prepare trimmed text for SMS
        $titr_trimmed = trim_text($titr, 80);

        if ($is_support_user && !$is_ticket_creator) {
            // Support user replied - send SMS to ticket creator
            if (!empty($tel_karbar2)) {
                $sms_result = send_sms_pattern($tel_karbar2, [
                    'department' => $department_name,
                    'ticket_number' => $code_ticket,
                    'ticket_title' => $titr_trimmed,
                    'supporter_name' => $name_karbar_run
                ], [
                    'pattern_code' => 'zex6EZRBbq',
                    'line_number' => '3000505',
                    'number_format' => 'english'
                ]);

                if (!$sms_result['success']) {
                    error_log('IranPayamak reply SMS to ticket creator failed for ticket ' . $code_ticket . ': ' . $sms_result['message']);
                }
            } else {
                error_log('IranPayamak reply SMS to ticket creator skipped for ticket ' . $code_ticket . ': creator phone is empty');
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
                    $sms_result = send_sms_pattern($tel_support, [
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
        }

        //----------------------------------end_send

        $apiResult = updateTicket($code_ticket);
        if ($apiResult && !$apiResult['success']) {
            error_log('Jaryanyar updateTicket failed for ' . $code_ticket . ': ' . ($apiResult['error'] ?? json_encode($apiResult['response'])));
        }

        header("location: ?page=info_ticket&code=$code_ticket&p=y");
    } else {
        header("location: ?page=info_ticket&code=$code_ticket&p=n");
    } //end query

} else {

    header("location: ?page=info_ticket&code=$code_ticket&p=t");
}
