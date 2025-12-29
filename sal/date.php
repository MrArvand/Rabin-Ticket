<?php
$Host = "localhost";
$User = "root";
$Password = "";
$DBName = "requestr_rahbarian";
$Link = mysqli_connect($Host, $User, $Password);
mysqli_select_db($Link, 'requestr_rahbarian');
mysqli_set_charset($Link, "utf8mb4");
