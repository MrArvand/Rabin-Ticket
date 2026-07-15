<?php
require_once(__DIR__ . '/../../inf/f1.php');
require_once(__DIR__ . '/../../inf/ticket_tags.php');

$owner = $_SESSION['code_p'] ?? '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($owner === '' || $id <= 0 || !ticket_tags_table_exists($Link)) {
    header('Location: ?page=manage_tags&error=invalid_id');
    exit;
}

$tag = ticket_tag_get_by_id($Link, $id);
if (!$tag || $tag['owner_code_p'] !== $owner) {
    header('Location: ?page=manage_tags&error=not_found');
    exit;
}

$stmt = mysqli_prepare($Link, 'DELETE FROM ticket_tags WHERE id = ? AND owner_code_p = ?');
if (!$stmt) {
    header('Location: ?page=manage_tags&error=db');
    exit;
}
mysqli_stmt_bind_param($stmt, 'is', $id, $owner);
mysqli_stmt_execute($stmt);
$affected = mysqli_stmt_affected_rows($stmt);
mysqli_stmt_close($stmt);

if ($affected > 0) {
    header('Location: ?page=manage_tags&p=deleted');
    exit;
}

header('Location: ?page=manage_tags&error=not_found');
exit;
