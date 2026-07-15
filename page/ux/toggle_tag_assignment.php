<?php
require_once(__DIR__ . '/../../inf/f1.php');
require_once(__DIR__ . '/../../inf/ticket_tags.php');

header('Content-Type: application/json; charset=utf-8');

$owner = $_SESSION['code_p'] ?? '';
$ticket_code = trim((string)($_POST['ticket_code'] ?? ''));
$tag_id = isset($_POST['tag_id']) ? intval($_POST['tag_id']) : 0;

if ($owner === '' || $ticket_code === '' || $tag_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'درخواست نامعتبر است'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!ticket_tags_table_exists($Link) || !ticket_tags_assignments_table_exists($Link)) {
    echo json_encode(['success' => false, 'message' => 'سیستم برچسب فعال نیست — migration را اجرا کنید'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (mb_strlen($ticket_code, 'UTF-8') > 100) {
    echo json_encode(['success' => false, 'message' => 'شناسه تیکت نامعتبر است'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!ticket_tag_user_can_use($Link, $tag_id, $owner)) {
    echo json_encode(['success' => false, 'message' => 'برچسب یافت نشد'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Check if assignment exists
$exists_stmt = mysqli_prepare(
    $Link,
    'SELECT id FROM ticket_tag_assignments WHERE tag_id = ? AND ticket_code = ? LIMIT 1'
);
$assignment_id = 0;
if ($exists_stmt) {
    mysqli_stmt_bind_param($exists_stmt, 'is', $tag_id, $ticket_code);
    mysqli_stmt_execute($exists_stmt);
    $ex_res = mysqli_stmt_get_result($exists_stmt);
    if ($ex_res && ($ex = mysqli_fetch_assoc($ex_res))) {
        $assignment_id = (int)($ex['id'] ?? 0);
    }
    mysqli_stmt_close($exists_stmt);
}

if ($assignment_id > 0) {
    // Remove assignment
    $del = mysqli_prepare($Link, 'DELETE FROM ticket_tag_assignments WHERE id = ?');
    if ($del) {
        mysqli_stmt_bind_param($del, 'i', $assignment_id);
        mysqli_stmt_execute($del);
        mysqli_stmt_close($del);
    }
} else {
    // Add assignment
    $ins = mysqli_prepare(
        $Link,
        'INSERT INTO ticket_tag_assignments (tag_id, ticket_code, owner_code_p) VALUES (?, ?, ?)'
    );
    if ($ins) {
        mysqli_stmt_bind_param($ins, 'iss', $tag_id, $ticket_code, $owner);
        mysqli_stmt_execute($ins);
        mysqli_stmt_close($ins);
    }
}

// Reload assignments for this ticket
$assigned_map = ticket_tag_load_assignments($Link, $owner, [$ticket_code]);
$assigned = $assigned_map[$ticket_code] ?? [];

echo json_encode([
    'success' => true,
    'assigned' => $assigned,
], JSON_UNESCAPED_UNICODE);
exit;
