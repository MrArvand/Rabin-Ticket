<?php

$code_p = $_SESSION['code_p'];
$current_password = str_p('current_password');
$new_password = str_p('new_password');
$confirm_password = str_p('confirm_password');

if ($current_password === '0' || $new_password === '0' || $confirm_password === '0') {
    header('location: ?page=profile&p=n');
    exit;
}

if ($new_password !== $confirm_password) {
    header('location: ?page=profile&p=m');
    exit;
}

if (strlen($new_password) < 6) {
    header('location: ?page=profile&p=n');
    exit;
}

$code_p_escaped = mysqli_real_escape_string($Link, $code_p);
$Query_user = "SELECT pass FROM karbar WHERE code_p = '$code_p_escaped' LIMIT 1";
$stored_password = null;

if ($Result_user = mysqli_query($Link, $Query_user)) {
    if ($user_row = mysqli_fetch_array($Result_user)) {
        $stored_password = $user_row['pass'];
    }
}

if ($stored_password === null || $stored_password !== $current_password) {
    header('location: ?page=profile&p=t');
    exit;
}

$new_password_escaped = mysqli_real_escape_string($Link, $new_password);
$Query = "UPDATE `karbar` SET `pass` = '$new_password_escaped' WHERE `code_p` = '$code_p_escaped'";

if (mysqli_query($Link, $Query)) {
    header('location: ?page=profile&p=y');
} else {
    header('location: ?page=profile&p=n');
}

exit;
