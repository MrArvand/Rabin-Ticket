<?php

$code_g=str_g('code_g');
$kind=str_g('kind');
$code=str_g('code');


if($code_g !="" || $code !="" ){


//---------------------------------------------------------
//data_garardad

$Query_listg="SELECT*from gharardad where (code = '$code_g' )ORDER BY i_gharardad DESC LIMIT 1";
if($Result_listg=mysqli_query($Link,$Query_listg)){
while($q_listg=mysqli_fetch_array($Result_listg)){

$vaziat =$q_listg['vaziat']; 
}}   

//dada_band
$Query_list="SELECT*from bandha where (code_band = '$code' )ORDER BY i_band DESC LIMIT 1";
if($Result_list=mysqli_query($Link,$Query_list)){
while($q_list_band=mysqli_fetch_array($Result_list)){
    $matn_band=$q_list_band['matn_band'];
    $kind_band=$q_list_band['kind_band'];
    $daste_band=$q_list_band['daste'];

}}

$tekrari = "n";
$Query_tek="SELECT*from band_gharardad where (code_gharardad = '$code_g' AND code_band = '$code' )ORDER BY i_band DESC LIMIT 1";
if($Result_tek=mysqli_query($Link,$Query_tek)){
while($q_tek_band=mysqli_fetch_array($Result_tek)){
  
    $tekrari = "y";

}}


if($tekrari == "y"){

    header("location: ".$_SERVER['HTTP_REFERER']."&p=t"); 

}else{


//---------------------------------------------------------
$code_band_g="BTG-".time()."-".rand(11,99);


$Qery="INSERT INTO `band_gharardad` (`code_gharardad`, `code_band`, `matn_band`, `set_motaghayer`, `kind_band`, `radif`, `code_sakhtar`, `shomare`, `code`, `i_band_g`) 
VALUES ('$code_g', '$code', '$matn_band', 'n', '$kind_band', '1', '$kind', '-', '$code_band_g', NULL);";


	if ($Link->query($Qery ) === TRUE) {	

        header("location:?page=panel_g_matn&code=$code_g&p=y");  
    }else{
        header("location: ?page=panel_g_matn&code=$code_g&p=n");  
    }
}
}else{
       header("location: ".$_SERVER['HTTP_REFERER']."&p=t"); 
}