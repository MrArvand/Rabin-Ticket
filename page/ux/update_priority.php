<?php
ob_start();

header('Content-Type: application/json; charset=utf-8');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['code_p'])) {
    http_response_code(401);
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'نشست کاربری منقضی شده است. لطفاً دوباره وارد شوید.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'روش درخواست مجاز نیست.'
    ]);
    exit;
}

$rawInput = file_get_contents('php://input');
$payload = json_decode($rawInput, true);

if (!is_array($payload)) {
    $payload = $_POST;
}

$ticketCode = isset($payload['ticket_code']) ? trim($payload['ticket_code']) : '';
$status = isset($payload['status']) ? trim($payload['status']) : '';
$order = isset($payload['order']) && is_array($payload['order']) ? $payload['order'] : [];

if ($ticketCode === '' || !in_array($status, ['y', 'n'], true)) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'اطلاعات ارسال شده معتبر نیست.'
    ]);
    exit;
}

$ticketCodeEscaped = mysqli_real_escape_string($Link, $ticketCode);
$normalizedOrder = [];

foreach ($order as $code) {
    $code = trim($code);
    if ($code === '') {
        continue;
    }
    if (!in_array($code, $normalizedOrder, true)) {
        $normalizedOrder[] = $code;
    }
}

if ($status === 'y' && !in_array($ticketCode, $normalizedOrder, true)) {
    $normalizedOrder[] = $ticketCode;
}

if (!@mysqli_begin_transaction($Link)) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'امکان آغاز تراکنش پایگاه داده وجود ندارد.'
    ]);
    exit;
}

try {
    if ($status === 'y') {
        $position = 1;
        foreach ($normalizedOrder as $code) {
            $codeEscaped = mysqli_real_escape_string($Link, $code);
            $query = "UPDATE ticket SET priority_status = 'y', priority_order = $position WHERE code = '$codeEscaped'";
            if (!mysqli_query($Link, $query)) {
                throw new Exception('عدم امکان به‌روزرسانی ترتیب اولویت.');
            }
            $position++;
        }
    } else {
        $query = "UPDATE ticket SET priority_status = 'n', priority_order = NULL WHERE code = '$ticketCodeEscaped'";
        if (!mysqli_query($Link, $query)) {
            throw new Exception('عدم امکان به‌روزرسانی وضعیت اولویت.');
        }

        if (!empty($normalizedOrder)) {
            $position = 1;
            foreach ($normalizedOrder as $code) {
                $codeEscaped = mysqli_real_escape_string($Link, $code);
                $query = "UPDATE ticket SET priority_status = 'y', priority_order = $position WHERE code = '$codeEscaped'";
                if (!mysqli_query($Link, $query)) {
                    throw new Exception('عدم امکان تنظیم مجدد ترتیب.');
                }
                $position++;
            }
        }
    }

    @mysqli_commit($Link);

    ob_clean();
    echo json_encode([
        'success' => true
    ]);
} catch (Exception $e) {
    @mysqli_rollback($Link);
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;

