<?php

session_start();
date_default_timezone_set('Asia/Tehran');
include('../../inf/date.php');
include('../../inf/tag.php');
include('../../inf/d.php');

$hash = rand(1000, 9999);

$tarikh = "$jyear-$jmonth-$jday";
$saat = date("H:i");

$page = str_p('page');
$code = str_p('code');
$kind = str_p('kind');
$p = str_p('p');

$okvotod = "no";

$id_user = str_p('id_user');
$pass_user = str_p('pass_user');

// Check if fields are empty
if (empty($id_user) || empty($pass_user)) {
   header("location: ../../login.php?error=empty");
   exit;
}

$Query = "SELECT*from karbar where (code_p = '$id_user' AND pass = '$pass_user' AND vaziat = 'y'  )";

if ($Result = mysqli_query($Link, $Query)) {
   $found = false;
   while ($modir = mysqli_fetch_array($Result)) {
      $mktime = time();

      $_SESSION['name'] = $modir['name'];
      $_SESSION['semat'] = $modir['semat'];
      $_SESSION['code_p'] = $modir['code_p'];
      $_SESSION['avatar'] = $modir['avatar'];
      $_SESSION['let'] = $modir['let'];

      $_SESSION['ok_login_user_i'] = "y";
      $okvotod = "yes";
      $found = true;

      header("location: ../../index.php");
      exit;
   }

   // If no matching user found
   if (!$found) {
      header("location: ../../login.php?error=invalid");
      exit;
   }
} else {
   header("location: ../../login.php?error=invalid");
   exit;
}

if ($okvotod != "yes") {
   header("location: ../../login.php?error=invalid");
   exit;
}

header("location: ../../index.php");
