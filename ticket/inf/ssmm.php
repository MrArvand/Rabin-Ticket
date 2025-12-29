<?php
include('date.php');
date_default_timezone_set('Asia/Tehran');

ini_set("soap.wsdl_cache_enabled", "0");
try{
$sms_client = new SoapClient('http://api.payamak-panel.com/post/Receive.asmx?wsdl', array('encoding'=>'UTF-8'));

$parameters['username'] = "9357798932";
$parameters['password'] = "aA@83184017";
$parameters['location'] =  1;
$parameters['from'] = "500010001000117";
$parameters['index'] = 0;
$parameters['count'] =10;

$all_sms=$sms_client->GetMessageStr($parameters)->GetMessageStrResult;
}catch(Exception $e){

}

$sms_r= explode( '|',$all_sms );

for($i='0';$i<'5';$i++){
 $sms_rs= explode( ',',$sms_r[$i] );
 $telsms='0'.$sms_rs['3'];
 $codsms=$sms_rs['1'];

 $Query="UPDATE `agahi` SET ok_sms = 'y'  WHERE `tel` = '$telsms' AND `ok_sms` = 'n' AND `code_sms` = '$codsms'; ";
 echo" $Query";echo"<br>";
if ($Link->query($Query ) === TRUE) {	

}}

?>