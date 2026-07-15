<?php
require_once(__DIR__ . '/../../../jaryanyar/api_functions.php');

$code_ticket = str_g('code');

if ($code_ticket === '') {
    header('Location: ?page=list_ticket');
    exit;
}

$code_ticket_escaped = mysqli_real_escape_string($Link, $code_ticket);
$Query_list = "SELECT * FROM ticket WHERE code = '$code_ticket_escaped' ORDER BY i_ticket DESC LIMIT 1";

if (!($Result_list = mysqli_query($Link, $Query_list)) || !($q_gar = mysqli_fetch_array($Result_list))) {
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=n');
    exit;
}

if ($q_gar['vaziat'] !== 'b') {
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=n');
    exit;
}

$name_karbar_run = isset($_SESSION['name']) ? $_SESSION['name'] : 'کاربر';
$code_p_run = isset($_SESSION['code_p']) ? $_SESSION['code_p'] : '';
$name_karbar_run_escaped = mysqli_real_escape_string($Link, $name_karbar_run);
$code_p_run_escaped = mysqli_real_escape_string($Link, $code_p_run);

$log_txt = $q_gar['log_txt'];
$log_txt = $log_txt . ' بازگشت وضعیت به در حال بررسی توسط ' . $name_karbar_run . ' در تاریخ ' . $tarikh . ' - ' . $saat . ' <br>';
$log_txt_escaped = mysqli_real_escape_string($Link, $log_txt);

$matn_pasokh = 'وضعیت تیکت توسط کاربر ' . $name_karbar_run . ' به «در حال بررسی» بازگردانده شد.';
$matn_pasokh_escaped = mysqli_real_escape_string($Link, $matn_pasokh);

$code_pasokh = 'G-' . time() . '-' . rand(11, 99);
$code_pasokh_escaped = mysqli_real_escape_string($Link, $code_pasokh);

$Qery = "UPDATE `ticket` SET `vaziat` = 'm', `log_txt` = '$log_txt_escaped' WHERE `code` = '$code_ticket_escaped'; ";
$Qery .= "INSERT INTO `pasokh` (`code`, `code_ticket`, `code_karbar_sabt`, `name_karbar_sabt`, `code_karbar2`, `name_karbar2`, `matn`, `tarikh_sabt`, `saat_sabt`, `vaziat`, `kind`, `oksee`, `tarikh_see`, `saat_see`, `i_pasokh`) ";
$Qery .= "VALUES ('$code_pasokh_escaped', '$code_ticket_escaped', '$code_p_run_escaped', '$name_karbar_run_escaped', '', '', '$matn_pasokh_escaped', '$tarikh', '$saat', 'm', 'reopen', 'n', '', '', NULL);";

if ($Link->multi_query($Qery) === true) {
    $apiResult = updateTicket($code_ticket);
    if ($apiResult && !$apiResult['success']) {
        error_log('Jaryanyar updateTicket failed for ' . $code_ticket . ': ' . ($apiResult['error'] ?? json_encode($apiResult['response'])));
    }
    $p_param = isset($_GET['p']) ? '&p=' . htmlspecialchars($_GET['p']) : '';
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . $p_param . '&reopened=1');
    exit;
}

header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . '&p=n');
exit;
