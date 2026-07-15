<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/api_functions.php';

$ticketCode = '';
if (isset($_GET['ticket_number'])) {
    $ticketCode = trim((string) $_GET['ticket_number']);
} elseif (isset($_GET['ticketNumber'])) {
    $ticketCode = trim((string) $_GET['ticketNumber']);
} elseif (isset($_GET['code'])) {
    $ticketCode = trim((string) $_GET['code']);
}

if ($ticketCode === '') {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Missing ticket number. Use ?ticket_number=...'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$Link = mysqli_connect('localhost', 'rahbaria_software_team', 'C7L}n}U n<Y}^');
if (!$Link) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!mysqli_select_db($Link, 'rahbaria_requestr_rahbarian')) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to select database'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
mysqli_set_charset($Link, 'utf8mb4');

$apiResult = updateTicket($ticketCode);

if ($apiResult === null) {
    echo json_encode([
        'success' => true,
        'ticket_number' => $ticketCode,
        'updated' => false,
        'message' => 'Ticket not eligible for updateTicket logic or not found'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$ok = !empty($apiResult['success']);
http_response_code($ok ? 200 : 502);
echo json_encode([
    'success' => $ok,
    'ticket_number' => $ticketCode,
    'updated' => $ok,
    'status_code' => $apiResult['status_code'] ?? null,
    'response' => $apiResult['response'] ?? null,
    'error' => $apiResult['error'] ?? null,
], JSON_UNESCAPED_UNICODE);
