<?php
$Host="localhost";
$User="requestr_requestr";
$Password="Y1AFEeU}v6";
$DBName="requestr_rahbarian";
$Link=mysqli_connect($Host,$User,$Password);
mysqli_select_db($Link, 'requestr_rahbarian');
mysqli_set_charset($Link, "utf8mb4");
