<?php
function mailing($adres,$matn){
  $subject ="سامانه استاد مشاور";
// message up
$oksendemail="n";
$email=$adres;

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

// Additional headers
$headers .= 'To: OstadMoshaver>' . "\r\n";
$headers .= 'From:استاد مشاور   | OstadMoshaver <info@OstadMoshaver.ir>' . "\r\n";
// Mail it
/*
if(mail($email, $subject, $matn, $headers)){
$oksendemail="y";
}
*/
$oksendemail="y";

}
?>