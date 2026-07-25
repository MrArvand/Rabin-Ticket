<?php
/**
 * User-cartable department referral:
 * - Caller picks a department only (no user picker)
 * - Ticket moves to that department's default_user
 * - System divider message (kind=dept_ref) notifies the new assignee
 */
require_once(__DIR__ . '/../../../jaryanyar/api_functions.php');

$code_ticket = str_g('code_ticket');
$daste_new = str_g('daste');

if (empty($code_ticket) || empty($daste_new) || $daste_new === '0') {
    header('Location: ?page=info_ticket&code=' . urlencode((string) $code_ticket) . '&p=t');
    exit;
}

$code_ticket_escaped = mysqli_real_escape_string($Link, $code_ticket);
$daste_new_escaped = mysqli_real_escape_string($Link, $daste_new);

$sender_code = isset($_SESSION['code_p']) ? (string) $_SESSION['code_p'] : (isset($code_p_run) ? (string) $code_p_run : '');
$sender_name = isset($_SESSION['name']) ? (string) $_SESSION['name'] : (isset($name_karbar_run) ? (string) $name_karbar_run : '');

if ($sender_code === '') {
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=n');
    exit;
}

$query = "SELECT * FROM ticket WHERE code = '$code_ticket_escaped' ORDER BY i_ticket DESC LIMIT 1";
$result = mysqli_query($Link, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=t');
    exit;
}

$row = mysqli_fetch_assoc($result);

// Only the ticket creator (issuer) can department-refer from the user cartable
if ((string) $row['code_p_karbar'] !== $sender_code) {
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=n');
    exit;
}

if (in_array($row['vaziat'], ['b', 'c'], true)) {
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=n');
    exit;
}

// Resolve department + default assignee
$query_dep = "SELECT id, name, default_user_code, default_user_name
              FROM departman
              WHERE id = '$daste_new_escaped' AND vaziat = 'y'
              LIMIT 1";
$result_dep = mysqli_query($Link, $query_dep);
if (!$result_dep || mysqli_num_rows($result_dep) === 0) {
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=t');
    exit;
}

$dep = mysqli_fetch_assoc($result_dep);
$department_name = (string) ($dep['name'] ?? '');
$default_user_code = trim((string) ($dep['default_user_code'] ?? ''));
$default_user_name = trim((string) ($dep['default_user_name'] ?? ''));

if ($department_name === '' || $default_user_code === '' || $default_user_name === '') {
    // Department must have a default user configured
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=t');
    exit;
}

// Optional restricted-department gate (same rules as start_ticket)
$restricted_helper = __DIR__ . '/../../inf/restricted_department_sadgan_fx.php';
if (is_file($restricted_helper)) {
    require_once $restricted_helper;
    if (function_exists('is_restricted_department')
        && function_exists('can_view_restricted_department')
        && is_restricted_department($daste_new)
        && !can_view_restricted_department($sender_code)
    ) {
        header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=n');
        exit;
    }
}

$old_assignee = (string) ($row['code_p_karbar_anjam'] ?? '');
$log_txt = (string) ($row['log_txt'] ?? '');
$log_txt .= " ارجاع به دپارتمان $department_name (کاربر پیش‌فرض: $default_user_name) توسط $sender_name در تاریخ $tarikh - $saat <br>";

$last_activity = mysqli_real_escape_string($Link, trim($tarikh . ' ' . $saat));
$code_pasokh = 'G-' . time() . '-' . rand(11, 99);

$department_name_escaped = mysqli_real_escape_string($Link, $department_name);
$default_user_code_escaped = mysqli_real_escape_string($Link, $default_user_code);
$default_user_name_escaped = mysqli_real_escape_string($Link, $default_user_name);
$sender_code_escaped = mysqli_real_escape_string($Link, $sender_code);
$sender_name_escaped = mysqli_real_escape_string($Link, $sender_name);
$log_txt_escaped = mysqli_real_escape_string($Link, $log_txt);
$code_pasokh_escaped = mysqli_real_escape_string($Link, $code_pasokh);
$old_assignee_escaped = mysqli_real_escape_string($Link, $old_assignee);

// matn stores department name for the system divider display
$Qery = "UPDATE `ticket` SET
         `vaziat` = 'm',
         `daste` = '$daste_new_escaped',
         `name_daste` = '$department_name_escaped',
         `code_p_karbar_anjam` = '$default_user_code_escaped',
         `name_karbar_anjam` = '$default_user_name_escaped',
         `log_txt` = '$log_txt_escaped',
         `last_activity` = '$last_activity'
         WHERE `code` = '$code_ticket_escaped'; ";

// Unread for new default assignee via code_karbar_sabt (same pattern as user referral)
$Qery .= "INSERT INTO `pasokh`
         (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`,
          `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `kind`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`)
         VALUES
         ('$code_pasokh_escaped', '$code_ticket_escaped', '$default_user_code_escaped', '$default_user_name_escaped',
          '$sender_code_escaped', '$sender_name_escaped', '$department_name_escaped',
          '$tarikh', '$saat', 'm', 'dept_ref', 'n', '', '', NULL);";

if ($old_assignee !== '' && $old_assignee !== $default_user_code) {
    $Qery .= "UPDATE `pasokh` SET `oksee` = 'y', `tarikh_see` = '$tarikh', `saat_see` = '$saat'
              WHERE `code_ticket` = '$code_ticket_escaped' AND `oksee` = 'n'
              AND `code` != '$code_pasokh_escaped'
              AND (
                `code_karbar2` = '$old_assignee_escaped'
                OR `code_karbar2` IS NULL OR `code_karbar2` = ''
                OR (`kind` IN ('referral', 'dept_ref') AND `code_karbar_sabt` = '$old_assignee_escaped')
              ); ";
}

if ($Link->multi_query($Qery)) {
    do {
        if ($result_mq = $Link->store_result()) {
            $result_mq->free();
        }
    } while ($Link->more_results() && $Link->next_result());

    // Notify new default assignee by SMS when possible
    require_once(__DIR__ . '/../../inf/s_sms.php');
    $tel_assignee = '';
    $q_tel = "SELECT tel FROM karbar WHERE code_p = '$default_user_code_escaped' LIMIT 1";
    if ($r_tel = mysqli_query($Link, $q_tel)) {
        if ($row_tel = mysqli_fetch_assoc($r_tel)) {
            $tel_assignee = (string) ($row_tel['tel'] ?? '');
        }
    }
    if ($tel_assignee !== '') {
        $titr_trimmed = function_exists('trim_text') ? trim_text($row['titr'], 80) : mb_substr($row['titr'], 0, 80, 'UTF-8');
        $sms_result = send_sms_pattern($tel_assignee, [
            'ticket_number' => $code_ticket,
            'ticket_title' => $titr_trimmed,
            'user_fullname' => $row['name_karbar']
        ], [
            'pattern_code' => 'MwoccILwQ3',
            'line_number' => '3000505',
            'number_format' => 'english'
        ]);
        if (!$sms_result['success']) {
            error_log('IranPayamak department-referral SMS failed for ticket ' . $code_ticket . ': ' . $sms_result['message']);
        }
    }

    $apiResult = updateTicket($code_ticket);
    if ($apiResult && !$apiResult['success']) {
        error_log('Jaryanyar updateTicket failed for ' . $code_ticket . ': ' . ($apiResult['error'] ?? json_encode($apiResult['response'])));
    }

    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=y');
    exit;
}

header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=n');
exit;
