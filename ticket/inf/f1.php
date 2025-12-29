<?php
session_start();
date_default_timezone_set('Asia/Tehran');
include('inf/date.php');
include('inf/jdf.php');
include('inf/d.php');
include('inf/configh.php');
$hash=rand(1000,9999);
$tarikh=jdate('Ymd');
$saat=jdate('H:i');
$zaman=$tarikh." - ".$saat;

$tpage=str_g('tpage');
$page=str_g('page');

$code=str_g('code');
$p=str_g('p');
$q=str_g('q');
$search=str_g('search');


$pfg=str_p('pfg');
?>