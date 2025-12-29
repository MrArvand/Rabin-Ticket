<?php
$Host="localhost";
$User = "root";
$Password = "";
$DBName = "request";
$Link=mysqli_connect($Host,$User,$Password);
mysqli_select_db($Link, 'request');
mysqli_set_charset($Link, "utf8mb4");