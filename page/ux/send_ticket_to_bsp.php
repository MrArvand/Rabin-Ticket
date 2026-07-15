<?php
require_once(__DIR__ . '/../../inf/mattermost_service.php');

$code_ticket = str_g('code');
$p_param = isset($_GET['p']) ? '&p=' . urlencode((string) $_GET['p']) : '';

if ($code_ticket === '') {
    header('Location: ?page=list_ticket');
    exit;
}

if (!ticket_can_send_to_bsp()) {
    header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . $p_param . '&bsp_error=1&bsp_msg=' . urlencode('دسترسی شما به ارسال تیکت به گروه ICT مجاز نیست.'));
    exit;
}

$actor_name = isset($_SESSION['name']) ? (string) $_SESSION['name'] : 'کاربر';
$result = ticket_send_to_bsp_mattermost($Link, $code_ticket, $actor_name);

$status_param = $result['success'] ? 'bsp_sent=1' : 'bsp_error=1';
$msg_param = '';
if (!$result['success'] && !empty($result['message'])) {
    $msg_param = '&bsp_msg=' . urlencode((string) $result['message']);
}

header('Location: ?page=info_ticket&code=' . urlencode($code_ticket) . $p_param . '&' . $status_param . $msg_param);
exit;
