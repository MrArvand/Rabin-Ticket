<?php
$title     = mysqli_real_escape_string($Link, $_POST['title']);
$faal      = mysqli_real_escape_string($Link, $_POST['faal']);
$daste     = mysqli_real_escape_string($Link, $_POST['daste']);
$sherkat   = mysqli_real_escape_string($Link, $_POST['sherkat']);
$tarikh_1  = mysqli_real_escape_string($Link, $_POST['tarikh_1']);
$tarikh_2  = mysqli_real_escape_string($Link, $_POST['tarikh_2']);
$sn_ticket = mysqli_real_escape_string($Link, $_POST['sn_ticket']);

$count_q = mysqli_query(
    $Link,
    "SELECT COUNT(*) c 
     FROM ticket_filter_presets 
     WHERE code_p_karbar='$code_p_run'"
);
$count = mysqli_fetch_assoc($count_q)['c'];

if ($count >= 2) {
    exit('LIMIT_REACHED');
}

mysqli_query(
    $Link,
    "INSERT INTO ticket_filter_presets
     (code_p_karbar, title, faal, daste, sherkat, tarikh_1, tarikh_2, sn_ticket)
     VALUES
     ('$code_p_run', '$title', '$faal', '$daste', '$sherkat', '$tarikh_1', '$tarikh_2', '$sn_ticket')"
);
