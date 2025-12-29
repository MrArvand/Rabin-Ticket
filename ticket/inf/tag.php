<?php
$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
$j_month_name = array("", "Farvardin", "Ordibehesht", "Khordad", "Tir",
                      "Mordad", "Shahrivar", "Mehr", "Aban", "Azar",
                      "Dey", "Bahman", "Esfand");
                      $po=date('w');
if($po==0) $n_day="&#1740;&#1705;&#1588;&#1606;&#1576;&#1607;";
if($po==1) $n_day="&#1583;&#1608;&#1588;&#1606;&#1576;&#1607;";
if($po==2) $n_day="&#1587;&#1607; &#1588;&#1606;&#1576;&#1607;";
if($po==3) $n_day="&#1670;&#1607;&#1575;&#1585;&#1588;&#1606;&#1576;&#1607;";
if($po==4) $n_day="&#1662;&#1606;&#1580;&#1588;&#1606;&#1576;&#1607;";
if($po==5) $n_day="&#1580;&#1605;&#1593;&#1607;";
if($po==6) $n_day="&#1588;&#1606;&#1576;&#1607;";

function div($a, $b)
{
   return (int) ($a / $b);
} 
  
function gregorian_to_jalali_tag($g_y, $g_m, $g_d)
{
   global $g_days_in_month;
   global $j_days_in_month;
   
   $gy = $g_y-1600;
   $gm = $g_m-1;
   $gd = $g_d-1;

   $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400);

   for ($i=0; $i < $gm; ++$i)
      $g_day_no += $g_days_in_month[$i];
   if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
      /* leap and after Feb */
      ++$g_day_no;
   $g_day_no += $gd;

   $j_day_no = $g_day_no-79;

   $j_np = div($j_day_no, 12053);
   $j_day_no %= 12053;

   $jy = 979+33*$j_np+4*div($j_day_no,1461);

   $j_day_no %= 1461;

   if ($j_day_no >= 366) {
      $jy += div($j_day_no-1, 365);
      $j_day_no = ($j_day_no-1)%365;
   }

   for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) {
      $j_day_no -= $j_days_in_month[$i];
   }
   $jm = $i+1;
   $jd = $j_day_no+1;


   return array($jy, $jm, $jd);
}

function farsinum($str)
{
  if (strlen($str) == 1)
      $str = "0".$str;
  $out = "";
  for ($i = 0; $i < strlen($str); ++$i) {
    $c = substr($str, $i, 1); 
    $out .= pack("C*", 0xDB, 0xB0 + $c);
  }
  return $out;
}

list($jyear, $jmonth, $jday) = gregorian_to_jalali_tag(date("Y"),date("m"),date("d"));
$name_day= $n_day

?>