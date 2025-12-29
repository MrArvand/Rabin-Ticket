<?php
session_start();
date_default_timezone_set('Asia/Tehran');
include('../../inf/d.php');

$username = str_p('id_user');
$password = str_p('pass_user');

// Check if fields are empty
if (empty($username) || empty($password)) {
    header("location: ../../login.php?error=empty");
    exit;
}

$vaziat_login = "n";

$from = "200";
$pattern_code = "100";
$to = "1000";
$input_data = array("user_d" => "$username", "pass_d" => "$password");
$url = "https://paloodyar.ir/api/send_login.php?input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
$handler = curl_init($url);
curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($handler);

if ($response != "") {
    $drproje2 = explode("#", $response);
    $vaziat_login = $drproje2[0];
    if ($vaziat_login == "y") {
        $name_karbar = $drproje2[1];
        $tel_karbar = $drproje2[2];
        $semat_karbar = $drproje2[4];

        $_SESSION['name'] = $name_karbar;
        $_SESSION['semat'] = $semat_karbar;
        $_SESSION['code_p'] = $username;
        $_SESSION['avatar'] = "karbar";
        $_SESSION['tel_k'] = $tel_karbar;

        $_SESSION['ok_login_i'] = "y";

        header("location: ../../index.php");
        exit;
    } else {
        header("location: ../../login.php?error=invalid");
        exit;
    }
} else {
    header("location: ../../login.php?error=invalid");
    exit;
}
