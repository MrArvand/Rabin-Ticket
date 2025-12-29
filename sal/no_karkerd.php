<?php

include('date.php');
include('jdf.php');
$tarikh=jdate('Ymd');
 $phoneNumbers[]=""; 

if (date("w") == 5) {
    exit("امروز جمعه است. اجرای فایل متوقف شد.");
}

 $payam="همکار گرامی :
گزارش کارکرد شما امروز در سامانه تیکت ثبت نشده است 
*".$tarikh."*
";

$mmfooter="
--------------
رابین سامانه

";


$payam_end=$payam.$mmfooter;

$Query_dep="
SELECT k.*
FROM karbar k
LEFT JOIN karkerd kr ON k.code_p = kr.code_p 
    AND kr.tarikh_s = '$tarikh'
WHERE kr.code_p IS NULL AND k.gozaresh = 'y' 
";
if($Result_dep=mysqli_query($Link,$Query_dep)){
while($q_dep=mysqli_fetch_array($Result_dep)){
    
     $phoneNumbers[] = $q_dep['tel'];

     
 }}  

//$phoneNumbers[] ="09154200964";

$apiKey = 'ugv100n-yiirrcz-dkk7v38-bg6f45w-wmfvek5';

$data = [
    'phonenumber' => $phoneNumbers,
    'message' => $payam_end
    
];

$url = 'https://api.whatsiplus.com/sendMsg/' . $apiKey;


$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));


$response = curl_exec($ch);


mysqli_close($Link);

 ?>