<?php
require_once(__DIR__ . '/../../inf/f1.php');
require_once(__DIR__ . '/../../inf/ticket_tags.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ?page=manage_tags');
    exit;
}

$owner = $_SESSION['code_p'] ?? '';
$tag_title = (string)($_POST['tag_title'] ?? '');
$tag_color = (string)($_POST['tag_color'] ?? '#6366f1');
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$backPage = $id > 0 ? ('manage_tags&edit_id=' . $id) : 'manage_tags';

// Also check tag_color_picker as fallback
if ($tag_color === '#6366f1' && isset($_POST['tag_color_picker'])) {
    $tag_color = (string)$_POST['tag_color_picker'];
}

$formQs = '&title=' . rawurlencode($tag_title) . '&color=' . rawurlencode($tag_color);
$duplicateQs = '&error=duplicate' . $formQs;

if ($owner === '' || !ticket_tags_table_exists($Link)) {
    header('Location: ?page=manage_tags&error=no_session');
    exit;
}

$tag_title = ticket_tag_normalize_title($tag_title);
$tag_color = ticket_tag_normalize_color($tag_color);

if ($tag_title === '' || mb_strlen($tag_title, 'UTF-8') > 100) {
    header('Location: ?page=manage_tags&error=invalid_name' . $formQs);
    exit;
}

if (ticket_tag_title_taken($Link, $owner, $tag_title, $id)) {
    header('Location: ?page=manage_tags' . $duplicateQs);
    exit;
}

if ($id > 0) {
    // Update existing tag
    $existing = ticket_tag_get_by_id($Link, $id);
    if (!$existing || $existing['owner_code_p'] !== $owner) {
        header('Location: ?page=manage_tags&error=not_found');
        exit;
    }

    $upd = mysqli_prepare($Link, 'UPDATE ticket_tags SET title = ?, color = ? WHERE id = ? AND owner_code_p = ?');
    if (!$upd) {
        header('Location: ?page=' . $backPage . '&error=save_failed');
        exit;
    }
    mysqli_stmt_bind_param($upd, 'ssis', $tag_title, $tag_color, $id, $owner);
    try {
        $updOk = mysqli_stmt_execute($upd);
    } catch (mysqli_sql_exception $e) {
        $updOk = false;
    }
    if (!$updOk) {
        $isDuplicate = mysqli_stmt_errno($upd) === 1062;
        mysqli_stmt_close($upd);
        if ($isDuplicate) {
            header('Location: ?page=manage_tags' . $duplicateQs);
            exit;
        }
        header('Location: ?page=' . $backPage . '&error=save_failed');
        exit;
    }
    mysqli_stmt_close($upd);
} else {
    // Create new tag
    $ins = mysqli_prepare($Link, 'INSERT INTO ticket_tags (owner_code_p, title, color) VALUES (?, ?, ?)');
    if (!$ins) {
        header('Location: ?page=manage_tags&error=save_failed');
        exit;
    }
    mysqli_stmt_bind_param($ins, 'sss', $owner, $tag_title, $tag_color);
    try {
        $insOk = mysqli_stmt_execute($ins);
    } catch (mysqli_sql_exception $e) {
        $insOk = false;
    }
    if (!$insOk) {
        $isDuplicate = mysqli_stmt_errno($ins) === 1062;
        mysqli_stmt_close($ins);
        if ($isDuplicate) {
            header('Location: ?page=manage_tags' . $duplicateQs);
            exit;
        }
        header('Location: ?page=manage_tags&error=save_failed');
        exit;
    }
    mysqli_stmt_close($ins);
}

header('Location: ?page=manage_tags&p=y');
exit;
